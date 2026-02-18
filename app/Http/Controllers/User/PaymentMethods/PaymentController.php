<?php

namespace App\Http\Controllers\User\PaymentMethods;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    /**
     * Handle the IPN
     */
    public function ipn(Request $request, $gateway)
    {
        $gateway = PaymentGateway::where('key', $gateway)->where('status', 1)->firstOrFail();
        $paymentController = __NAMESPACE__.'\\'
        .str_replace(
            ' ', '',
            ucwords(
                str_replace('_', ' ', $gateway->key))
        )
        .'Controller';
        return $paymentController::ipn($request);
    }

    /**
     * Handle the Webhook
     */
    public function webhook(Request $request, $gateway)
    {
        $gateway = PaymentGateway::where('key', $gateway)->where('status', 1)->firstOrFail();
        $paymentController = __NAMESPACE__.'\\'
            .str_replace(
                ' ', '',
                ucwords(
                    str_replace('_', ' ', $gateway->key))
            )
            .'Controller';
        return $paymentController::webhook($request);
    }
}
