<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSendStatusColumnEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->boolean('send_status')->default(1);
        });
        DB::statement("ALTER TABLE `estimates` CHANGE `status` `status` ENUM('declined','accepted','waiting','sent','draft') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'waiting';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn(['send_status']);
        });
        DB::statement("ALTER TABLE `estimates` CHANGE `status` `status` ENUM('declined','accepted','waiting','sent') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'waiting';");
    }
}
