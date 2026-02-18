<?php
/* Version 1.4 */

use Illuminate\Support\Facades\DB;

/* Add new payment gateway */
$gateways = [
    ['name' => 'Paddle','key' => 'paddle','logo' => 'paddle.png','fees' => '0','test_mode' => '1','credentials' => '{"vendor_id":"","api_key":"","public_key":""}','status' => '0']
];
DB::table('payment_gateways')->insert($gateways);
