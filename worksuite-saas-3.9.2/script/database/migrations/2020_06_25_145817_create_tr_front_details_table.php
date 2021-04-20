<?php

use App\FrontDetail;
use App\LanguageSetting;
use App\TrFrontDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrFrontDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_front_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('language_setting_id')->nullable();
            $table->foreign('language_setting_id')->references('id')->on('language_settings')->onDelete('cascade')->onUpdate('cascade');
            $table->string('header_title',200);
            $table->text('header_description');
            $table->string('image',200);
            $table->string('feature_title')->nullable();
            $table->string('feature_description')->nullable();
            $table->string('price_title')->nullable();
            $table->string('price_description')->nullable();
            $table->string('task_management_title')->nullable();
            $table->text('task_management_detail')->nullable();
            $table->string('manage_bills_title')->nullable();
            $table->text('manage_bills_detail')->nullable();
            $table->string('teamates_title')->nullable();
            $table->text('teamates_detail')->nullable();
            $table->string('favourite_apps_title')->nullable();
            $table->text('favourite_apps_detail')->nullable();
            $table->string('cta_title')->nullable();
            $table->text('cta_detail')->nullable();
            $table->string('client_title')->nullable();
            $table->text('client_detail')->nullable();
            $table->string('testimonial_title')->nullable();
            $table->text('testimonial_detail')->nullable();
            $table->string('faq_title')->nullable();
            $table->text('faq_detail')->nullable();
            $table->text('footer_copyright_text')->nullable();
            $table->timestamps();
        });

        $frontDetail = FrontDetail::first();

        if ($frontDetail) {
            $trFrontDetail = new TrFrontDetail();
    
            $trFrontDetail->header_title = $frontDetail->header_title;
            $trFrontDetail->header_description = $frontDetail->header_description;
            $trFrontDetail->image = $frontDetail->image;
            $trFrontDetail->feature_title = $frontDetail->feature_title;
            $trFrontDetail->feature_description = $frontDetail->feature_description;
            $trFrontDetail->price_title = $frontDetail->price_title;
            $trFrontDetail->price_description = $frontDetail->price_description;
            $trFrontDetail->task_management_title = $frontDetail->task_management_title;
            $trFrontDetail->task_management_detail = $frontDetail->task_management_detail;
            $trFrontDetail->manage_bills_title = $frontDetail->manage_bills_title;
            $trFrontDetail->manage_bills_detail = $frontDetail->manage_bills_detail;
            $trFrontDetail->teamates_title = $frontDetail->teamates_title;
            $trFrontDetail->teamates_detail = $frontDetail->teamates_detail;
            $trFrontDetail->favourite_apps_title = $frontDetail->favourite_apps_title;
            $trFrontDetail->favourite_apps_detail = $frontDetail->favourite_apps_detail;
            $trFrontDetail->cta_title = $frontDetail->cta_title;
            $trFrontDetail->cta_detail = $frontDetail->cta_detail;
            $trFrontDetail->client_title = $frontDetail->client_title;
            $trFrontDetail->client_detail = $frontDetail->client_detail;
            $trFrontDetail->testimonial_title = $frontDetail->testimonial_title;
            $trFrontDetail->testimonial_detail = $frontDetail->testimonial_detail;
            $trFrontDetail->faq_title = $frontDetail->faq_title;
            $trFrontDetail->faq_detail = $frontDetail->faq_detail;
            $trFrontDetail->footer_copyright_text = $frontDetail->footer_copyright_text;
    
            $trFrontDetail->save();
        }

        Schema::table('front_details', function (Blueprint $table) {
            $table->dropColumn('header_title');
            $table->dropColumn('header_description');
            $table->dropColumn('image');
            $table->dropColumn('feature_title');
            $table->dropColumn('feature_description');
            $table->dropColumn('price_title');
            $table->dropColumn('price_description');
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
            $table->dropColumn('footer_copyright_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_front_details');

        Schema::table('front_details', function (Blueprint $table) {
            $table->string('header_title',200);
            $table->text('header_description');
            $table->string('image',200);
            $table->string('feature_title')->nullable();
            $table->string('feature_description')->nullable();
            $table->string('price_title')->nullable();
            $table->string('price_description')->nullable();
            $table->string('task_management_title')->nullable();
            $table->text('task_management_detail')->nullable();
            $table->string('manage_bills_title')->nullable();
            $table->text('manage_bills_detail')->nullable();
            $table->string('teamates_title')->nullable();
            $table->text('teamates_detail')->nullable();
            $table->string('favourite_apps_title')->nullable();
            $table->text('favourite_apps_detail')->nullable();
            $table->string('cta_title')->nullable();
            $table->text('cta_detail')->nullable();
            $table->string('client_title')->nullable();
            $table->text('client_detail')->nullable();
            $table->string('testimonial_title')->nullable();
            $table->text('testimonial_detail')->nullable();
            $table->string('faq_title')->nullable();
            $table->text('faq_detail')->nullable();
            $table->text('footer_copyright_text')->nullable();
        });
    }
}
