<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceCreditNotePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_notes_invoice', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('credit_notes_id');
            $table->unsignedBigInteger('invoice_id');
            $table->dateTime('date');
            $table->float('credit_amount', 16, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_notes_invoice');
    }
}
