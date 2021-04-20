<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecurringColumnsInInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('recurring', ['yes', 'no'])->default('no')->after('status');
            $table->string('billing_frequency')->nullable()->default(null)->after('recurring');
            $table->integer('billing_interval')->nullable()->default(null)->after('recurring');
            $table->integer('billing_cycle')->nullable()->default(null)->after('recurring');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['recurring', 'billing_frequency', 'billing_interval', 'billing_cycle']);
        });
    }
}
