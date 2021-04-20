<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_agents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status',['enabled', 'disabled'])->default('enabled');
            $table->timestamps();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->bigInteger('agent_id')->unsigned()->nullable()->default(null)->after('status_id');
            $table->foreign('agent_id')->references('id')->on('lead_agents')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_agents');
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('agent_id');
        });
    }
}
