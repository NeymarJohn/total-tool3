<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogTimeForTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('log_time_for', function(Blueprint $table)
		{
			$table->increments('id');
			$table->enum('log_time_for',['project','task'])->default('project');
			$table->timestamps();
		});

		$logTimeFor = new \App\LogTimeFor();
        $logTimeFor->log_time_for = 'project';
        $logTimeFor->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('log_time_for');
	}

}
