<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultLanguageFrontDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('front_details', function (Blueprint $table) {
            $table->string('locale')->nullable()->default('en');
            $table->longText('contact_html')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('front_details', function (Blueprint $table) {
            $table->dropColumn(['locale']);
            $table->dropColumn(['contact_html']);
        });
    }
}
