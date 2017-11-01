<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('email');
            $table->string('other_email')->nullable()->default(null);
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('mandate')->nullable()->default(null);
            $table->string('customer')->nullable()->default(null);
            $table->string('teamwork_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('password')->nullable();
            $table->boolean('admin')->default(false);
            $table->string('confirmation_code')->nullable();
            $table->boolean('confirmed')->default(0);
            $table->rememberToken()->nullable();
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
        Schema::dropIfExists('users');
    }
}
