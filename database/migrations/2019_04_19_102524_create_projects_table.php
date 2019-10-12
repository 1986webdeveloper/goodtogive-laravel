<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('users');
            $table->integer('church_fund_id')->unsigned();
            $table->foreign('church_fund_id')->references('id')->on('church_funds');
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamp('startdate')->nullable();
            $table->timestamp('enddate')->nullable();
            $table->float('goal_amount');
            $table->string('qrcode')->nullable();
            $table->enum('need_to_scan_qr', ['enable', 'disable'])->default('disable');
            $table->enum('donation_slab_custom_amount', ['true', 'false'])->default('false');
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
        Schema::dropIfExists('projects');
    }
}
