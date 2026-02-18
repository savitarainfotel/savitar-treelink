<?php
/* Version 2.0 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/* Add new payment gateway */
$gateways = [
    [
        'name' => 'Bank Deposit (Offline Payment)', 'key' => 'wire_transfer', 'logo' => 'wire_transfer.png',
        'fees' => '0', 'test_mode' => null, 'credentials' => '{"bank_information":""}', 'status' => '0'
    ]
];
DB::table('payment_gateways')->insert($gateways);

/* Make plan interval nullable */
Schema::table('users', function (Blueprint $table) {
    $table->string('plan_interval', 20)->default('LIFETIME')->nullable()->change();
});
