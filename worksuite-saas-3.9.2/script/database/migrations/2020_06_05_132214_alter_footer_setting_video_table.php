<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFooterSettingVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('footer_menu', function (Blueprint $table) {
            $table->string('video_link')->nullable()->default(null);
            $table->text('video_embed')->nullable()->default(null);
            $table->string('file_name')->nullable()->default(null);
            $table->string('hash_name')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('footer_menu', function (Blueprint $table) {
            $table->dropColumn(['video_link']);
            $table->dropColumn(['video_embed']);
            $table->dropColumn(['file_name']);
            $table->dropColumn(['hash_name']);
        });
    }
}
