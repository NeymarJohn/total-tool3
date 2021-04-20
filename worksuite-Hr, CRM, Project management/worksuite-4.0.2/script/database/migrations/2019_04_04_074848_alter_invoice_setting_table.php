<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoiceSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('invoice_settings')){
            Schema::table('invoice_settings', function (Blueprint $table) {
                $table->string('gst_number')->nullable()->default(null)->after('invoice_terms');
                $table->enum('show_gst', ['yes', 'no'])->nullable()->default('no')->after('gst_number');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('invoice_settings')) {
            Schema::table('invoice_settings', function (Blueprint $table) {
                $table->dropColumn('gst_number');
                $table->dropColumn('show_gst');
            });
        }
    }
}
