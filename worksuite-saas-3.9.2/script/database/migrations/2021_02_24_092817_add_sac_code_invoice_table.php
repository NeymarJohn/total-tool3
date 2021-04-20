<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSacCodeInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->boolean('hsn_sac_code_show')->default(1);
            $table->string('locale')->nullable()->default('en');
        });
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('hsn_sac_code')->nullable();
        });
        Schema::table('credit_note_items', function (Blueprint $table) {
            $table->string('hsn_sac_code')->nullable();
        });
        Schema::table('estimate_items', function (Blueprint $table) {
            $table->string('hsn_sac_code')->nullable();
        });
        Schema::table('invoice_recurring_items', function (Blueprint $table) {
            $table->string('hsn_sac_code')->nullable();
        });
        Schema::table('proposal_items', function (Blueprint $table) {
            $table->string('hsn_sac_code')->nullable();
        });
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->string('hsn_sac_code')->nullable();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->string('hsn_sac_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->dropColumn(['hsn_sac_code_show']);
        });
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['hsn_sac_code']);
        });
        Schema::table('credit_note_items', function (Blueprint $table) {
            $table->dropColumn(['hsn_sac_code']);
        });
        Schema::table('estimate_items', function (Blueprint $table) {
            $table->dropColumn(['hsn_sac_code']);
        });
        Schema::table('invoice_recurring_items', function (Blueprint $table) {
            $table->dropColumn(['hsn_sac_code']);
        });
        Schema::table('proposal_items', function (Blueprint $table) {
            $table->dropColumn(['hsn_sac_code']);
        });
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->dropColumn(['hsn_sac_code']);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['hsn_sac_code']);
        });
    }
}
