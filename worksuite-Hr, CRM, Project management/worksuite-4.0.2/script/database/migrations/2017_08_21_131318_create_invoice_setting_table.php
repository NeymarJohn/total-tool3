<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_prefix');
            $table->string('template');
            $table->integer('due_after');
            $table->text('invoice_terms');
            $table->timestamps();
        });

        $setting = new \App\InvoiceSetting();
        $setting->invoice_prefix = 'INV';
        $setting->template = 'invoice-1';
        $setting->due_after = 15;
        $setting->invoice_terms = 'Thank you for your business. Please process this invoice within the due date.';
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_settings');
    }
}
