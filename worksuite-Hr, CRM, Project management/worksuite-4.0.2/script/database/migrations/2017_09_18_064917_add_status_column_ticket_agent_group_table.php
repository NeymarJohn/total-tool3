<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColumnTicketAgentGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_agent_groups', function (Blueprint $table) {
            $table->enum('status', ['enabled', 'disabled'])->default('enabled')->after('group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_agent_groups', function (Blueprint $table) {
            $table->dropColumn(['status']);
        });
    }
}
