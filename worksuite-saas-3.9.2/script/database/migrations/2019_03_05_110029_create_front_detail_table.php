<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateFrontDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('front_details', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('header_title',200);
            $table->text('header_description');
            $table->string('image',200);
            $table->enum('get_started_show',['yes', 'no'])->default('yes');
            $table->enum('sign_in_show',['yes', 'no'])->default('yes');
            $table->string('feature_title')->nullable()->default(null);
            $table->string('feature_description')->nullable()->default(null);
            $table->string('price_title')->nullable()->default(null);
            $table->string('price_description')->nullable()->default(null);
            $table->text('address')->nullable()->default(null);
            $table->string('phone', 20)->nullable()->default(null);
            $table->string('email', 60)->nullable()->default(null);
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
		Schema::drop('front_details');
	}

}
