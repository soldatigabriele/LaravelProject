<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('folder_id');
            $table->string('folder_name')->default('Assets');
            $table->integer('fk_project');
            $table->integer('fk_user');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('folders');
    }
}
