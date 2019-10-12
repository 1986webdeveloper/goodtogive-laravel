<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectDonationPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_donation_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('project_id');
            $table->float('amount');
            $table->enum('payment_status', ['pending', 'completed', 'paid', 'unpaid'])->default('unpaid');
            $table->enum('payment_gateway_type', ['stripe', 'braintree'])->default('stripe');
            $table->json('payment_gateway_response')->nullable();
            $table->integer('payment_transaction_id')->nullable();
            $table->enum('qr_scanned_verified',['yes','no'])->default('no');
            $table->enum('is_deleted', ['0', '1'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_donation_payments');
    }
}
