<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRecommendedColumnPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->boolean('is_recommended')->default(0);
        });

        $packages = \App\Package::all();

        $firstPackage = $packages->filter(function ($value, $key) {
            return $value->default == 'no' && 'is_private' == 0;
        })->first();
        $recommendedPackage = $packages->filter(function ($value, $key) {
            return $value->is_recommended == 1;
        })->first();

        if(!is_null($firstPackage) && is_null($recommendedPackage)){
            $firstPackage->is_recommended = 1;
            $firstPackage->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['is_recommended']);
        });
    }
}
