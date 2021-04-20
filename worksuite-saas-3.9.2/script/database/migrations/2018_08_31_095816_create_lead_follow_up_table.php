<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\TicketType;

class CreateLeadFollowUpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_follow_up', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_id')->unsigned();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade')->onUpdate('cascade');
            $table->longText('remark')->nullable()->default(null);
            $table->dateTime('next_follow_up_date')->nullable()->default(null);
            $table->enum('next_follow_up',['yes', 'no'])->nullable()->default('no');
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
        Schema::dropIfExists('lead_follow_up');
    }
}
