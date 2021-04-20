<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Module;

class AddGlobalDefaultLocale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $globalsetting = \App\GlobalSetting::first();
        if(!is_null($globalsetting) && (is_null($globalsetting->new_company_locale) || $globalsetting->new_company_locale =="")){
            $globalsetting->new_company_locale = 'en';
            $globalsetting->save();
        }

        $companies = \App\Company::all();

        if($companies){
            foreach($companies as $company){
                if(is_null($company->locale) || $company->locale == "")
                {
                    $company->locale = 'en';
                    $company->save();
                }
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
        //
    }
}
