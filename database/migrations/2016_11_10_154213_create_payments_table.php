<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('description')->nullable();
            $table->integer('amount')->nullable();
            $table->string('status')->nullable()->default('submitted');
            $table->string('user_mandate')->nullable();
            $table->string('user_customer')->nullable();
            $table->string('payment_id')->nullable()->default(null);
            $table->integer('fk_client_task')->nullable();
            $table->integer('fk_user')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
