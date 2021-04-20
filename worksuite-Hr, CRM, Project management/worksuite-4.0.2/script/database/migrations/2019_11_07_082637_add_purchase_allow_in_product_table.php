<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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

        \App\ModuleSetting::where('type' ,'client')->delete();
        $clientModules = ['projects', 'tickets', 'invoices', 'estimates', 'events','product', 'messages'];
        foreach($clientModules as $moduleSetting){
            $modulesClient = new \App\ModuleSetting();
            $modulesClient->module_name = $moduleSetting;
            $modulesClient->type = 'client';
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
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['allow_purchase']);
        });
    }
}
