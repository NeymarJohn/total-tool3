<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnInFrontSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('front_details', function (Blueprint $table) {
            $table->string('primary_color')->nullable()->default(null);
            $table->string('task_management_title')->nullable()->default(null);
            $table->text('task_management_detail')->nullable()->default(null);
            $table->string('manage_bills_title')->nullable()->default(null);
            $table->text('manage_bills_detail')->nullable()->default(null);
            $table->string('teamates_title')->nullable()->default(null);
            $table->text('teamates_detail')->nullable()->default(null);
            $table->string('favourite_apps_title')->nullable()->default(null);
            $table->text('favourite_apps_detail')->nullable()->default(null);
            $table->string('cta_title')->nullable()->default(null);
            $table->text('cta_detail')->nullable()->default(null);
            $table->string('client_title')->nullable()->default(null);
            $table->text('client_detail')->nullable()->default(null);
            $table->string('testimonial_title')->nullable()->default(null);
            $table->text('testimonial_detail')->nullable()->default(null);
            $table->string('faq_title')->nullable()->default(null);
            $table->text('faq_detail')->nullable()->default(null);
            $table->text('footer_copyright_text')->nullable()->default(null);
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
            $table->dropColumn('task_management_title');
            $table->dropColumn('task_management_detail');
            $table->dropColumn('manage_bills_title');
            $table->dropColumn('manage_bills_detail');
            $table->dropColumn('teamates_title');
            $table->dropColumn('teamates_detail');
            $table->dropColumn('favourite_apps_title');
            $table->dropColumn('favourite_apps_detail');
            $table->dropColumn('cta_title');
            $table->dropColumn('cta_detail');
            $table->dropColumn('client_title');
            $table->dropColumn('client_detail');
            $table->dropColumn('testimonial_title');
            $table->dropColumn('testimonial_detail');
            $table->dropColumn('faq_title');
            $table->dropColumn('faq_detail');
        });
    }
}
