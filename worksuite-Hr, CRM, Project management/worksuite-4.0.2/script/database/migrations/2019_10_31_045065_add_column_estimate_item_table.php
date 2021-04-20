<?php

use App\EstimateItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnEstimateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimate_items', function (Blueprint $table) {
            $table->string('taxes')->nullable()->default(null)->after('amount');
        });

        $estimateItems = EstimateItem::all();
        if ($estimateItems->count() > 0){
            foreach ($estimateItems as $estimateItem){
                $arr = [];
                if ($estimateItem->tax_id){
                    $arr[] = (string)$estimateItem->tax_id;
                }
                $estimateItem->taxes = $arr ? json_encode($arr) : null;
                $estimateItem->save();
            }
        }

        Schema::table('estimate_items', function (Blueprint $table) {
            $table->dropForeign('estimate_items_tax_id_foreign');
            $table->dropColumn('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimate_items', function (Blueprint $table) {
            $table->dropColumn('taxes');
        });
    }
}
