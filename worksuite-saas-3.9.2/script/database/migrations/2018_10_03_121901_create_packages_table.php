<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('description', 1000)->nullable();
            $table->unsignedInteger('max_storage_size');
            $table->unsignedInteger('max_file_size')->default(0);
            $table->unsignedDecimal('annual_price')->default(0);
            $table->unsignedDecimal('monthly_price')->default(0);
            $table->unsignedTinyInteger('billing_cycle')->default(0);
            $table->integer('max_employees')->unsigned()->default(0);
            $table->string('sort');
            $table->string('module_in_package', 1000);
            $table->string('stripe_annual_plan_id', 255);
            $table->string('stripe_monthly_plan_id', 255);
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
        Schema::dropIfExists('packages');
    }
}
