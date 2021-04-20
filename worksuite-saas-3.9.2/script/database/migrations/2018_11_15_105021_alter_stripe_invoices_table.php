<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStripeInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE stripe_invoices MODIFY invoice_id varchar(255) null");
        \DB::statement("ALTER TABLE stripe_invoices MODIFY transaction_id varchar(255) null");
        Schema::table('companies', function (Blueprint $table) {
            $table->date('licence_expire_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('licence_expire_on');
        });
    }
}
