<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ModuleSetting;

class AddTimelogModuleClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companies = \App\Company::all();

        foreach ($companies as $company) {
            ModuleSetting::firstOrCreate(
                [
                    'module_name' => 'timelogs',
                    'type' => 'client',
                    'status' => 'active',
                    'company_id' => $company->id
                ]
            );
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
