<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


class MakeMessageNullableTicketReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `ticket_replies` CHANGE `message` `message` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;');
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
