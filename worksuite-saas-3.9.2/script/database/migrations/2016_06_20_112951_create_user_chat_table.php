<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateUserChatTable
 */

class CreateUserChatTable extends Migration
{

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('users_chat', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_one')->unsigned();
            $table->foreign('user_one')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('message');
            $table->integer('from')->unsigned()->nullable();
            $table->foreign('from')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('to')->unsigned()->nullable();
            $table->foreign('to')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->enum('message_seen', ['yes','no'])->default('no');
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
        Schema::drop('users_chat');
    }

}
