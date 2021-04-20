<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MakeSmtpTypeNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `smtp_settings` CHANGE `mail_encryption` `mail_encryption` ENUM('tls','ssl') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'tls';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `smtp_settings` CHANGE `mail_encryption` `mail_encryption` ENUM('tls','ssl') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'tls';");
    }
}
