<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\TicketType;

class CreateLeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->nullable()->default(null);
            $table->integer('source_id')->nullable()->default(null);
            $table->integer('status_id')->nullable()->default(null);
            $table->string('company_name');
            $table->string('website');
            $table->text('address');
            $table->string('client_name');
            $table->string('client_email');
            $table->string('mobile');
            $table->text('note');

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
        Schema::dropIfExists('leads');
    }
}
