<?php

use App\Estimate;
use App\Invoice;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvocieNumberInInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  Update all existing invoice numbers
        $invoices = Invoice::select('invoice_number', 'id')->orderBy('id', 'asc')->get();
        foreach ($invoices as $key=>$invoice) {
            $invoice->invoice_number = $key+1;
            $invoice->save();
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
