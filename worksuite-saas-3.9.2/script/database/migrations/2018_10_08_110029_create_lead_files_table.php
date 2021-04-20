<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lead_files', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('lead_id')->unsigned();
            $table->foreign('lead_id')
                ->references('id')
                ->on('leads')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('filename',200);
            $table->string('hashname',200);
            $table->string('size',200);
            $table->text('description')->nullable();
            $table->string('google_url')
                ->nullable();
            $table->string('dropbox_link')
                ->nullable();
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
		Schema::drop('lead_files');
	}

}
