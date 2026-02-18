<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key', 100);
            $table->string('name', 100);
            $table->string('logo');
            $table->integer('fees');
            $table->text('credentials');
            $table->boolean('test_mode')->nullable();
            $table->enum('payment_mode', ['one_time','recurring','both'])->nullable()->default('one_time');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });

        $payment_gateways = [
            ['id' => '1','name' => 'Paypal','key' => 'paypal','logo' => 'paypal.png','fees' => '0','test_mode' => '1','credentials' => '{"client_id":"","client_secret":"","app_id":""}','status' => '1'],
            ['id' => '2','name' => 'Stripe','key' => 'stripe','logo' => 'stripe.png','fees' => '0','test_mode' => NULL,'credentials' => '{"publishable_key":"","secret_key":"","webhook_secret":""}','status' => '1'],
            ['id' => '3','name' => 'Mollie','key' => 'mollie','logo' => 'mollie.png','fees' => '0','test_mode' => NULL,'credentials' => '{"api_key":""}','status' => '1'],
            ['id' => '4','name' => 'Razorpay','key' => 'razorpay','logo' => 'razorpay.png','fees' => '0','test_mode' => NULL,'credentials' => '{"key_id":"","key_secret":""}','status' => '1'],
            ['id' => '5','name' => 'Paddle','key' => 'paddle','logo' => 'paddle.png','fees' => '0','test_mode' => '1','credentials' => '{"vendor_id":"","api_key":"","public_key":""}','status' => '0'],
            ['id' => '6','name' => 'Bank Deposit (Offline Payment)','key' => 'wire_transfer','logo' => 'wire_transfer.png','fees' => '0','test_mode' => NULL,'credentials' => '{"bank_information":""}','status' => '0']
        ];


        DB::table('payment_gateways')->insert($payment_gateways);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gateways');
    }
};
