<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxColumnInProposalItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proposal_items', function (Blueprint $table) {
            $table->string('taxes')->after('amount');
            $table->text('item_summary')->nullable()->default(null)->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposal_items', function (Blueprint $table) {
            $table->dropForeign(['taxes']);
            $table->dropForeign(['item_summary']);
        });
    }
}
