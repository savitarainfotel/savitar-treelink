<?php

namespace App\Http\Controllers\User\PaymentMethods;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\CheckoutController;
use App\Models\PaymentGateway;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    /**
     * Process the payment
     *
     * @param  Transaction  $transaction
     */
    public static function pay(Transaction $transaction)
    {
        $gateway = PaymentGateway::where('key', 'stripe')->first();

        /* Check pay mode */
        if ($gateway->payment_mode == 'both') {
            $pay_mode = request()->get('payment_mode', 'one_time');
        } else {
            $pay_mode = $gateway->payment_mode;
        }

        if ($transaction->details->interval == 'LIFETIME') {
            $pay_mode = 'one_time';
        }

        $title = "Payment for ".$transaction->details->title." Plan (".$transaction->details->interval.')';

        $fees = ($transaction->total * $gateway->fees) / 100;
        $price = round(($transaction->total + $fees), 2);
        $price = in_array(config('settings.currency')->code, [
            'MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF',
            'XPF'
        ])
            ? $price
            : $price * 100;

        Stripe::setApiKey($gateway->credentials->secret_key);

        if ($pay_mode == 'recurring') {
            /* Recurring */

            /* Try to get the product */
            try {
                $stripe_product = \Stripe\Product::retrieve($transaction->plan_id);

                /* Check if the plan's name has changed */
                if ($transaction->details->title != $stripe_product->name) {

                    /* Update the product name */
                    try {
                        $stripe_product = \Stripe\Product::update($stripe_product->id, [
                            'name' => $transaction->details->title
                        ]);
                    } catch (\Exception $e) {
                        Log::info($e->getMessage());
                        quick_alert_error($e->getMessage());
                        return back();
                    }
                }
            } catch (\Exception $e) {
                /* Create the product if not already created */
                try {
                    $stripe_product = \Stripe\Product::create([
                        'id' => $transaction->plan_id,
                        'name' => $transaction->details->title
                    ]);
                } catch (\Exception $e) {
                    Log::info($e->getMessage());
                    quick_alert_error($e->getMessage());
                    return back();
                }
            }

            /* Generate the plan id */
            $stripe_plan_id = $transaction->plan_id.'_'.$transaction->details->interval.'_'.$price.'_'.config('settings.currency')->code;

            /* Get the payment plan */
            try {
                $stripe_plan = \Stripe\Plan::retrieve($stripe_plan_id);
            } catch (\Exception $e) {

                /* Create the plan if not already created */
                try {
                    $stripe_plan = \Stripe\Plan::create([
                        'amount' => $price,
                        'interval' => 'day',
                        'interval_count' => $transaction->details->interval == 'MONTHLY' ? 30 : 365,
                        'product' => $stripe_product->id,
                        'id' => $stripe_plan_id,
                        'currency' => config('settings.currency')->code,
                    ]);
                } catch (\Exception $e) {
                    Log::info($e->getMessage());
                    quick_alert_error($e->getMessage());
                    return back();
                }
            }

            try {
                $transaction->update(['fees' => $fees]);
                $metadata = $transaction->getAttributes();

                $session = Session::create([
                    'cancel_url' => route('subscription'),
                    'success_url' => route('ipn', 'stripe').'?payment_id={CHECKOUT_SESSION_ID}&pay_mode='.$pay_mode,
                    'payment_method_types' => ['card'],
                    'subscription_data' => [
                        'items' => [
                            ['plan' => $stripe_plan->id]
                        ],
                        'metadata' => $metadata,
                    ],
                    'metadata' => $metadata,
                ]);

                Log::info(json_encode($metadata));

                /* Delete this transaction, we will create new one for the recurring payment */
                $transaction->delete();

            } catch (\Exception $e) {
                Log::info($e->getMessage());
                quick_alert_error($e->getMessage());
                return back();
            }

        } else {
            /* One Time */
            try {
                $session = Session::create([
                    'line_items' => [
                        [
                            'price_data' => [
                                'product_data' => [
                                    'name' => $title,
                                    'description' => $title,
                                ],
                                'unit_amount' => $price,
                                'currency' => config('settings.currency')->code,
                            ],
                            'quantity' => 1,
                        ]
                    ],
                    'payment_method_types' => ['card'],
                    'mode' => 'payment',
                    'cancel_url' => route('subscription'),
                    'success_url' => route('ipn', 'stripe').'?payment_id={CHECKOUT_SESSION_ID}&pay_mode='.$pay_mode,
                ]);

                if ($session) {
                    $transaction->update(['payment_id' => $session->id, 'fees' => $fees]);
                }
            } catch (\Exception $e) {
                error_log($e->getMessage());
                quick_alert_error($e->getMessage());
                return back();
            }
        }

        /* redirect to payment gateway page */
        return redirect($session->url);
    }

    /**
     * Handle the IPN
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public static function ipn(Request $request)
    {

        if ($request->pay_mode == 'one_time') {

            /* One Time */
            try {
                $gateway = PaymentGateway::where('key', 'stripe')->first();

                Stripe::setApiKey($gateway->credentials->secret_key);

                $payment_id = $request->payment_id;
                $transaction = Transaction::where([
                    ['user_id', request()->user()->id],
                    ['status', Transaction::STATUS_UNPAID],
                    ['payment_id', $payment_id],
                ])->first();

                if (is_null($transaction)) {
                    quick_alert_error(___('Invalid transaction, please try again.'));
                    return redirect()->route('subscription');
                }

                $session = Session::retrieve($payment_id);

                if ($session->payment_status == "paid" && $session->status == "complete") {

                    $update = $transaction->update([
                        'gateway' => $gateway->id,
                        'payment_id' => $session->id,
                        'total' => ($transaction->total + $transaction->fees),
                        'status' => Transaction::STATUS_PAID,
                    ]);
                    if ($update) {
                        CheckoutController::updateUserPlan($transaction);
                        quick_alert_success(___('Payment successful'));
                    }
                } else {
                    quick_alert_error(___('Payment failed, please try again.'));
                }
            } catch (\Exception $e) {
                error_log($e->getMessage());
                quick_alert_error(___('Payment failed, please try again.'));
            }

        } else {
            /* Send a success message for recurring payment */
            quick_alert_success(___('Payment successful'));
        }

        return redirect()->route('subscription');
    }

    /**
     * Handle the Webhook
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function webhook(Request $request)
    {
        $gateway = PaymentGateway::where('key', 'stripe')->first();

        $stripe_secret_key = $gateway->credentials->secret_key;
        $stripe_publishable_key = $gateway->credentials->publishable_key;
        $stripe_webhook_secret = $gateway->credentials->webhook_secret;

        Stripe::setApiKey($stripe_secret_key);

        try {
            $event = \Stripe\Webhook::constructEvent(
                $request->getContent(),
                $request->server('HTTP_STRIPE_SIGNATURE'),
                $stripe_webhook_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::info($e->getMessage());

            return response()->json([
                'message' => $e->getMessage(),
                'status' => 400
            ], 400);

        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Log::info($e->getMessage());

            return response()->json([
                'message' => $e->getMessage(),
                'status' => 400
            ], 400);
        }

        /*  */
        if ($event->type == 'invoice.paid') {

            $session = $event->data->object;

            // Get the metadata
            $metadata = $session->lines->data[0]->metadata ?? ($session->metadata ?? null);

            if($metadata){
                return CheckoutController::processWebhook($gateway, $metadata, $session->id, $session->subscription);
            } else {
                return response()->json([
                    'status' => 400
                ], 400);
            }
        }

        return response()->json([
            'message' => 'successful',
            'status' => 200
        ], 200);
    }
}
