<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeatureTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('features', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('title');
            $table->longText('description')->nullable()->default(null);
            $table->string('image',200)->nullable()->default(null);
            $table->string('icon',200)->nullable()->default(null);
            $table->enum('type',['image', 'icon'])->default('image');
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
		Schema::drop('features');
	}

}
