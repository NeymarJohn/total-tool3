<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ModuleSetting;

class AddPurchaseAllowInProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('allow_purchase')->default(0)->after('taxes');
        });

        $companies = \App\Company::all();

        foreach($companies as $company){
            $package         = \App\Package::findOrFail($company->package_id);
            $moduleInPackage = (array) json_decode($package->module_in_package);

            if (in_array('products', $moduleInPackage)) {
                $moduleSetting = new ModuleSetting();
                $moduleSetting->company_id = $company->id;
                $moduleSetting->module_name = 'products';
                $moduleSetting->status = 'active';
                $moduleSetting->type = 'client';
                $moduleSetting->save();
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
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['allow_purchase']);
        });
    }
}
