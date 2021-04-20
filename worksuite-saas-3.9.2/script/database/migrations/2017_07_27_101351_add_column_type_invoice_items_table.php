<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Invoice;
use App\InvoiceItems;
use Illuminate\Support\Facades\DB;

class AddColumnTypeInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->enum('type', ['item', 'discount', 'tax'])->default('item')->after('item_name');
        });

        // Add tax and discount records from existing invoices
        $invoices = DB::table('invoices')->select('*')->get();

        foreach($invoices as $invoice){
            if($invoice->tax_percent > 0){
                $item = new InvoiceItems();
                $item->invoice_id = $invoice->id;
                $item->item_name = 'Tax';
                $item->type = 'tax';
                $item->quantity = 1;
                $item->unit_price = ($invoice->sub_total-$invoice->discount)*($invoice->tax_percent/100);
                $item->amount = $item->unit_price;
                $item->save();
            }

            if($invoice->discount > 0){
                $item = new InvoiceItems();
                $item->invoice_id = $invoice->id;
                $item->item_name = 'Discount';
                $item->type = 'discount';
                $item->quantity = 1;
                $item->unit_price = $invoice->discount;
                $item->amount = $item->unit_price;
                $item->save();
            }
        }

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['discount', 'tax_percent']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        InvoiceItems::where('type', 'tax')->orWhere('type', 'discount')->delete();

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['type']);
        });

    }
}
