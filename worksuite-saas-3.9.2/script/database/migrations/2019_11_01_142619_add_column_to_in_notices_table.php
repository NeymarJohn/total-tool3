<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToInNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->string('to')->after('id')->default('employee');
        });

        $companies = \App\Company::all();

        foreach($companies as $company) {
            $modulesClient = new \App\ModuleSetting();
            $modulesClient->module_name = 'notices';
            $modulesClient->company_id = $company->id;
            $modulesClient->type = 'client';
            $modulesClient->status = 'active';
            $modulesClient->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn('to');
        });
    }
}
