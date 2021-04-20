<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetCreditNoteAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `credit_notes` CHANGE `sub_total` `sub_total` DOUBLE(15,2) NOT NULL;");
        DB::statement("ALTER TABLE `credit_notes` CHANGE `total` `total` DOUBLE(15,2) NOT NULL;");
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
