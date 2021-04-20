<?php

use App\InvoiceItems;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInvoiceItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('taxes')->nullable()->default(null)->after('amount');
        });

        $invoiceItems = InvoiceItems::all();
        if ($invoiceItems->count() > 0){
            foreach ($invoiceItems as $invoiceItem){
                $arr = [];
                if ($invoiceItem->tax_id){
                    $arr[] = (string)$invoiceItem->tax_id;
                }
                $invoiceItem->taxes = $arr ? json_encode($arr) : null;
                $invoiceItem->save();
            }
        }

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropForeign('invoice_items_tax_id_foreign');
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
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn('taxes');
        });
    }
}
