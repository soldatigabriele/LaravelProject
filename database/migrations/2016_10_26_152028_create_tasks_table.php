<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('task_id')->nullable();
            $table->longText('description')->nullable();
            $table->string('content')->nullable();
            $table->string('tags')->default(' ');
            $table->boolean('completed')->default(false);
            $table->string('creatorFirstName')->nullable();
            $table->string('creatorLastName')->nullable();
            $table->string('parentTaskId')->nullable();
            $table->string('responsible_party_ids')->nullable();
            $table->integer('fk_tasklist')->nullable();
            $table->integer('fk_project')->nullable();
            $table->integer('fk_user')->nullable();
            $table->integer('visible')->default(true);
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
        Schema::dropIfExists('tasks');
    }
}
