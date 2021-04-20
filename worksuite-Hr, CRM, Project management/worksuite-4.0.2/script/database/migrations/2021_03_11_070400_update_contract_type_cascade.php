<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateContractTypeCascade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `contracts` CHANGE `contract_type_id` `contract_type_id` BIGINT UNSIGNED NULL;');

        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['contract_type_id']);

            $table->foreign('contract_type_id')->references('id')->on('contract_types')->onDelete('SET NULL')->onUpdate('cascade');
        });
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
