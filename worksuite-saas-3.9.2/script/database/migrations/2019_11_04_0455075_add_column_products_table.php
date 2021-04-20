<?php

use App\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('taxes')->nullable()->default(null)->after('price');
            $table->text('description')->nullable();
        });

        $products = Product::all();
        if ($products->count() > 0){
            foreach ($products as $product){
                $arr = [];
                if ($product->tax_id){
                    $arr[] = (string)$product->tax_id;
                }
                $product->taxes = $arr ? json_encode($arr) : null;
                $product->save();
            }
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_tax_id_foreign');
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
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('taxes');
            $table->dropColumn('description');
        });
    }
}
