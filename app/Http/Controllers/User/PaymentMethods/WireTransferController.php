<?php

namespace App\Http\Controllers\User\PaymentMethods;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Transaction;

class WireTransferController extends Controller
{

    /**
     * Process the payment
     *
     * @param  Transaction  $transaction
     */
    public static function pay(Transaction $transaction)
    {
        $title = "Payment for " . $transaction->details->title . " Plan (" . $transaction->details->interval .')';

        $gateway = PaymentGateway::where('key', 'wire_transfer')->first();

        $fees = ($transaction->total * $gateway->fees) / 100;
        $price = $transaction->total + $fees;
        $price = number_format((float) $price, 2);

        /* Update transaction status */
        $transaction->update([
            'gateway' => $gateway->id,
            'fees' => $fees,
            'total' => $price,
            'status' => Transaction::STATUS_PENDING
        ]);

        create_notification(___('New offline payment request is pending.'), 'new_payment',
            route('admin.transactions.index'));

        /* display wire transfer details */
        return view(active_theme()."user.gateways.wire-transfer", compact('transaction', 'title', 'gateway'));
    }
}
