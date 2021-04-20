<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_field_groups', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('model')->nullable();
            $table->index('model');
        });

        Schema::create('custom_fields', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('custom_field_group_id')->unsigned()->nullable();
            $table->foreign('custom_field_group_id')->references('id')->on('custom_field_groups')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->string('label', 100);
            $table->string('name', 100);
            $table->string('type', 10);
            $table->enum('required', ['yes', 'no'])->default('no');
            $table->string('values', 5000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('custom_fields');
        Schema::drop('custom_field_groups');
    }
}
