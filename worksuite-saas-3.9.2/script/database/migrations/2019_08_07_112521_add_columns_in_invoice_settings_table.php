<?php

use App\Estimate;
use App\Invoice;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInInvoiceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->unsignedInteger('invoice_digit')->after('invoice_prefix')->default(3);
            $table->string('estimate_prefix')->after('invoice_digit')->default('EST');
            $table->unsignedInteger('estimate_digit')->after('estimate_prefix')->default(3);
            $table->string('credit_note_prefix')->after('estimate_digit')->default('CN');
            $table->unsignedInteger('credit_note_digit')->after('credit_note_prefix')->default(3);
        });

//        Update all existing invoice numbers
        $invoice = Invoice::whereNotNull('deleted_at')->delete();
        $invoices = Invoice::all();
        $i = 1;
        foreach ($invoices as $invoice){
            $invoice->invoice_number = $i;
            $invoice->save();
            $i++;
        }

        //        Update all existing estimate numbers
        $estimate = Estimate::whereNotNull('deleted_at')->delete();
        $estimates = Estimate::all();
        $j = 1;
        foreach ($estimates as $estimate){
            $estimate->estimate_number = $j;
            $estimate->save();
            $j++;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->removeColumn('invoice_digit');
            $table->removeColumn('estimate_prefix');
            $table->removeColumn('estimate_digit');
            $table->removeColumn('credit_note_prefix');
            $table->removeColumn('credit_note_digit');
        });
    }
}
