<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeoImageColumnInSeoDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seo_details', function (Blueprint $table) {
            $table->string('og_image')->nullable()->after('seo_author')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seo_details', function (Blueprint $table) {
            $table->dropColumn('og_image');
        });
    }
}
