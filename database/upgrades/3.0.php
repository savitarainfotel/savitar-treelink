<?php
/* Version 3.0 */

use App\Models\PaymentGateway;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/* Add new column */
if (!Schema::hasColumn('payment_gateways', 'payment_mode')) {
    Schema::table('payment_gateways', function (Blueprint $table) {
        $table->enum('payment_mode',
            ['one_time', 'recurring', 'both'])->nullable()->default('one_time')->after('test_mode');
    });
}

/* Add webhook_secret in stripe credentials */
$gateway = PaymentGateway::where('key', 'stripe')->first();

$credentials = $gateway->credentials;
$credentials->webhook_secret = '';

$gateway->credentials = $credentials;
$gateway->save();


/* Add new column */
if (!Schema::hasColumn('users', 'plan_subscription_id')) {
    Schema::table('users', function (Blueprint $table) {
        $table->string('plan_subscription_id')->nullable()->after('plan_about_to_expire_reminder');
    });
}
