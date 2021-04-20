<?php

use App\FrontDetail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSocialLinksColumnInFrontDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('front_details', 'social_links')) {
            Schema::table('front_details', function (Blueprint $table) {
                $table->text('social_links')->after('email')->nullable();
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('front_details', function (Blueprint $table) {
            $table->dropColumn('social_links');
        });
    }
}
