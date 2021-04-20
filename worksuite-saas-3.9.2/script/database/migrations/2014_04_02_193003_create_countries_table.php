<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'countries', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->tinyInteger('is_visible');
            $table->char('iso_alpha2', 2);
            $table->index('iso_alpha2');
            $table->char('iso_alpha3', 2);
            $table->index('iso_alpha3');
            $table->unsignedInteger('iso_numeric');
            $table->string('currency_code', 3);
            $table->string('currency_name', 32);
            $table->string('currency_symbol', 3);
            $table->string('flag', 6);
        });

        // Seed countries table as soon as its migrated
        \Illuminate\Support\Facades\Artisan::call('db:seed', array('--class' => CountriesTableSeeder::class));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
