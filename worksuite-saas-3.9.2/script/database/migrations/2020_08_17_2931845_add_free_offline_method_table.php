<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFreeOfflineMethodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companies = \App\Company::all();
        if($companies){
            foreach($companies as $company){
                $offlineMethod = new \App\OfflinePaymentMethod();
                $offlineMethod->company_id = $company->id;
                $offlineMethod->name = 'free';
                $offlineMethod->description = 'free plan';
                $offlineMethod->save();

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
