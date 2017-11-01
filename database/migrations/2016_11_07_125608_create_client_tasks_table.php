<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClienttasksTable extends Migration
{
    public function up()
    {
        Schema::create('client_tasks', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('content')->nullable();
            $table->longText('description')->nullable()->default(null);
            $table->longText('instructions')->nullable()->default(null);
            $table->integer('amount')->nullable()->default(null);
            $table->boolean('active')->default(false);
            $table->boolean('completed')->default(false);
            $table->boolean('other_task')->default(false);
            $table->integer('visible')->default(true);
            $table->integer('fk_task')->nullable();
            $table->integer('fk_tasklist')->nullable();
            $table->integer('fk_user')->nullable();
            $table->integer('fk_project')->nullable();
            $table->integer('fk_tag')->nullable()->default(null);
            $table->string('url')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_tasks');
    }
}
