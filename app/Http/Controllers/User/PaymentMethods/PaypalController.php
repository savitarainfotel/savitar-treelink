<?php

namespace App\Http\Controllers\User\PaymentMethods;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\CheckoutController;
use App\Models\PaymentGateway;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{

    /**
     * Get paypal provider
     *
     * @return PayPalClient
     */
    public static function getPaypalProvider()
    {
        $gateway = PaymentGateway::where('key', 'paypal')->first();

        if ($gateway->test_mode) {
            $config = [
                'mode' => 'sandbox',
                'sandbox' => [
                    'client_id' => $gateway->credentials->client_id,
                    'client_secret' => $gateway->credentials->client_secret,
                    'app_id' => 'APP-80W284485P519543T',
                ],

                'payment_action' => 'Sale',
                'currency' => config('settings.currency')->code,
                'notify_url' => '',
                'validate_ssl' => false,
                'locale' => get_lang()
            ];
        } else {
            $config = [
                'mode' => 'live',
                'live' => [
                    'client_id' => $gateway->credentials->client_id,
                    'client_secret' => $gateway->credentials->client_secret,
                    'app_id' => $gateway->credentials->app_id,
                ],

                'payment_action' => 'Sale',
                'currency' => config('settings.currency')->code,
                'notify_url' => '',
                'validate_ssl' => true,
                'locale' => get_lang()
            ];
        }

        $provider = new PayPalClient($config);
        $provider->getAccessToken();
        return $provider;
    }

    /**
     * Process the payment
     *
     * @param  Transaction  $transaction
     */
    public static function pay(Transaction $transaction)
    {

        $gateway = PaymentGateway::where('key', 'paypal')->first();

        /* Check pay mode */
        if ($gateway->payment_mode == 'both') {
            $pay_mode = request()->get('payment_mode', 'one_time');
        } else {
            $pay_mode = $gateway->payment_mode;
        }

        if ($transaction->details->interval == 'LIFETIME') {
            $pay_mode = 'one_time';
        }

        $fees = ($transaction->total * $gateway->fees) / 100;
        $price = ($transaction->total + $fees);

        try {
            $provider = self::getPaypalProvider();

            if ($pay_mode == 'recurring') {
                /* Recurring */

                $product_id = 'product_'.$transaction->plan_id;

                /* Try to get the product */
                try {
                    $paypal_product = $provider->showProductDetails($product_id);
                } catch (\Exception $e) {
                    /* Create the product if not already created */
                    try {
                        $paypal_product = $provider->createProduct([
                            'id' => $product_id,
                            'name' => $transaction->details->title,
                            'description' => $transaction->details->title,
                            'type' => 'SERVICE'
                        ]);
                    } catch (\Exception $e) {
                        Log::info($e->getMessage());
                        quick_alert_error($e->getMessage());
                        return back();
                    }
                }

                /* Generate the plan id */
                $paypal_plan_name = 'plan_'.$transaction->plan_id.'_'.$transaction->details->interval.'_'.$price.'_'.config('settings.currency')->code;

                /* Create the plan */
                try {
                    $paypal_plan = $provider->createPlan([
                        'product_id' => $paypal_product['id'],
                        'name' => $paypal_plan_name,
                        'status' => 'ACTIVE',
                        'billing_cycles' => [
                            [
                                'frequency' => [
                                    'interval_unit' => 'DAY',
                                    'interval_count' => $transaction->details->interval == 'MONTHLY' ? 30 : 365,
                                ],
                                'tenure_type' => 'REGULAR',
                                'sequence' => 1,
                                'total_cycles' => 0,
                                'pricing_scheme' => [
                                    'fixed_price' => [
                                        'value' => $price,
                                        'currency_code' => config('settings.currency')->code,
                                    ],
                                ]
                            ]
                        ],
                        'payment_preferences' => [
                            'auto_bill_outstanding' => true,
                            'payment_failure_threshold' => 0,
                        ],
                    ]);
                } catch (\Exception $e) {
                    Log::info($e->getMessage());
                    quick_alert_error($e->getMessage());
                    return back();
                }

                /* Create the subscription */
                try {
                    $transaction->update(['fees' => $fees]);

                    $metadata = $transaction->getAttributes();

                    $paypal_subscription = $provider->createSubscription([
                        'plan_id' => $paypal_plan['id'],
                        'application_context' => [
                            'brand_name' => config('settings.site_title'),
                            'shipping_preference' => 'NO_SHIPPING',
                            'user_action' => 'SUBSCRIBE_NOW',
                            'payment_method' => [
                                'payer_selected' => 'PAYPAL',
                                'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED',
                            ],
                            'return_url' => route('ipn', 'paypal').'?pay_mode='.$pay_mode,
                            'cancel_url' => route('subscription')
                        ],
                        'custom_id' => http_build_query($metadata)
                    ]);

                    $redirect_url = $paypal_subscription['links'][0]['href'];

                    /* Delete this transaction, we will create new one for the recurring payment */
                    $transaction->delete();

                } catch (\Exception $e) {
                    Log::info($e->getMessage());
                    quick_alert_error($e->getMessage());
                    return back();
                }

            } else {
                /* One Time */
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    'application_context' => [
                        'brand_name' => config('settings.site_title'),
                        'shipping_preference' => 'NO_SHIPPING',
                        'user_action' => 'PAY_NOW',
                        "return_url" => route('ipn', 'paypal').'?pay_mode='.$pay_mode,
                        "cancel_url" => route('subscription')
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => config('settings.currency')->code,
                                "value" => number_format((float) $price, 2)
                            ]
                        ]
                    ]
                ]);

                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    $redirect_url = $response['links'][1]['href'];

                    $transaction->update(['payment_id' => $response['id'], 'fees' => $fees]);
                } else {
                    Log::info(json_encode($response));
                    quick_alert_error(!empty($response['error']['message'])
                        ? ___('Payment failed').' : '.$response['error']['message']
                        : ___('Payment failed, check the credentials.'));
                    return back();
                }
            }

            /* redirect to payment gateway page */
            return redirect($redirect_url);

        } catch (\Exception $e) {
            Log::info($e->getMessage());
            quick_alert_error($e->getMessage());
            return back();
        }
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
            try {
                $provider = self::getPaypalProvider();

                $response = $provider->capturePaymentOrder($request['token']);

                $transaction = Transaction::where([
                    ['user_id', request()->user()->id],
                    ['payment_id', $request['token']],
                    ['status', Transaction::STATUS_UNPAID],
                ])->first();

                if (is_null($transaction)) {
                    quick_alert_error(___('Invalid transaction, please try again.'));
                    return redirect()->route('subscription');
                }

                $gateway = PaymentGateway::where('key', 'paypal')->first();

                if (isset($response['status']) && $response['status'] == 'COMPLETED') {

                    $update = $transaction->update([
                        'gateway' => $gateway->id,
                        'payment_id' => $request['token'],
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
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function webhook(Request $request)
    {
        $payload = json_decode($request->getContent());
        if ($payload) {

            try {
                /* Recurring payment */
                if ($payload->event_type == 'PAYMENT.SALE.COMPLETED') {
                    $provider = self::getPaypalProvider();

                    /* Get subscription details */
                    $response = $provider->showSubscriptionDetails($payload->resource->billing_agreement_id);

                    if ($response) {
                        // Get the metadata
                        parse_str($payload->resource->custom_id ?? ($payload->resource->custom ?? null), $metadata);
                        if ($metadata) {
                            $metadata = array_to_object($metadata);
                            $gateway = PaymentGateway::where('key', 'paypal')->first();
                            return CheckoutController::processWebhook($gateway, $metadata, $payload->resource->id,
                                $payload->resource->billing_agreement_id);
                        } else {
                            return response()->json([
                                'status' => 400
                            ], 400);
                        }
                    }

                }


            } catch (\Exception $e) {
                Log::info($e->getMessage());

                return response()->json([
                    'message' => $e->getMessage(),
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
