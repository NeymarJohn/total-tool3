<?php

use App\FooterMenu;
use App\GlobalSetting;
use App\SeoDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('page_name');
            $table->string('seo_title')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('seo_author')->nullable();
            $table->timestamps();
        });

        $globalSetting = GlobalSetting::first();

        $seoDetail = new SeoDetail();
        $seoDetail->page_name = 'home';
        $seoDetail->seo_title = 'Home';
        $seoDetail->seo_author = $globalSetting ? $globalSetting->company_name : 'Worksuite';
        $seoDetail->seo_keywords = 'best crm,hr management software, web hr software, hr software online, free hr software, hr software for sme, hr management software for small business, cloud hr software, online hr management software';
        $seoDetail->seo_description = 'Worksuite saas is easy to use CRM software that is designed for B2B. It include  everything you need to run your businesses. like manage customers, projects, invoices, estimates, timelogs, contract and much more.';
        $seoDetail->save();

        $seoDetail = new SeoDetail();
        $seoDetail->page_name = 'feature';
        $seoDetail->seo_title = 'Feature';
        $seoDetail->seo_author = $globalSetting ? $globalSetting->company_name : 'Worksuite';
        $seoDetail->seo_keywords = 'best crm,hr management software, web hr software, hr software online, free hr software, hr software for sme, hr management software for small business, cloud hr software, online hr management software';
        $seoDetail->seo_description = 'Worksuite saas is easy to use CRM software that is designed for B2B. It include  everything you need to run your businesses. like manage customers, projects, invoices, estimates, timelogs, contract and much more.';
        $seoDetail->save();

        $seoDetail = new SeoDetail();
        $seoDetail->page_name = 'pricing';
        $seoDetail->seo_title = 'Pricing';
        $seoDetail->seo_author = $globalSetting ? $globalSetting->company_name : 'Worksuite';
        $seoDetail->seo_keywords = 'best crm,hr management software, web hr software, hr software online, free hr software, hr software for sme, hr management software for small business, cloud hr software, online hr management software';
        $seoDetail->seo_description = 'Worksuite saas is easy to use CRM software that is designed for B2B. It include  everything you need to run your businesses. like manage customers, projects, invoices, estimates, timelogs, contract and much more.';
        $seoDetail->save();

        $seoDetail = new SeoDetail();
        $seoDetail->page_name = 'contact';
        $seoDetail->seo_title = 'Contact';
        $seoDetail->seo_author = $globalSetting ? $globalSetting->company_name : 'Worksuite';
        $seoDetail->seo_keywords = 'best crm,hr management software, web hr software, hr software online, free hr software, hr software for sme, hr management software for small business, cloud hr software, online hr management software';
        $seoDetail->seo_description = 'Worksuite saas is easy to use CRM software that is designed for B2B. It include  everything you need to run your businesses. like manage customers, projects, invoices, estimates, timelogs, contract and much more.';
        $seoDetail->save();

        $footerPages = FooterMenu::all();
        if(count($footerPages) > 0){
            foreach($footerPages as $footerPage){
                $seoDetail = new SeoDetail();
                $seoDetail->page_name = $footerPage->slug;
                $seoDetail->seo_title = $footerPage->name;
                $seoDetail->seo_keywords = 'best crm,hr management software, web hr software, hr software online, free hr software, hr software for sme, hr management software for small business, cloud hr software, online hr management software';
                $seoDetail->seo_author = $globalSetting ? $globalSetting->company_name : 'Worksuite';
                $seoDetail->seo_description = 'Worksuite saas is easy to use CRM software that is designed for B2B. It include  everything you need to run your businesses. like manage customers, projects, invoices, estimates, timelogs, contract and much more.';
                $seoDetail->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seo_details');
    }
}
