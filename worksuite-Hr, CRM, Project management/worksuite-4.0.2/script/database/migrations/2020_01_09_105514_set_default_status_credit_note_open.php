<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetDefaultStatusCreditNoteOpen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `credit_notes` CHANGE COLUMN `status` `status` ENUM('closed','open') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'open'  COMMENT '' AFTER `currency_id`; ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `credit_notes` CHANGE COLUMN `status` `status` ENUM('closed','open') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'closed'  COMMENT '' AFTER `currency_id`; ");
    }
}
