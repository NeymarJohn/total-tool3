<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MakeTaxesNullablePropsalItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `proposal_items` CHANGE `taxes` `taxes` VARCHAR(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `proposal_items` CHANGE `taxes` `taxes` VARCHAR(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
    }
}
