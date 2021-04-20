<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomFieldsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields_data', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('custom_field_id')->unsigned();
            $table->foreign('custom_field_id')->references('id')->on('custom_fields')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('model_id')->unsigned();
            $table->string('model')->nullable();
            $table->index('model');
            $table->string('value', 10000); // Max 10,000 chars can be stored. For larger text instruct user to store as a file
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('custom_fields_data');
    }
}
