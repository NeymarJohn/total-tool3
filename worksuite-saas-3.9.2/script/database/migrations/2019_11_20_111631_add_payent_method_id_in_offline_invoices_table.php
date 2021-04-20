<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayentMethodIdInOfflineInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offline_invoices', function (Blueprint $table) {
            $table->string('package_type')->nullable()->after('package_id');
            $table->integer('offline_method_id')->unsigned()->nullable()->after('package_type');
            $table->foreign('offline_method_id')->references('id')->on('offline_payment_methods')->onDelete('SET NULL')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offline_invoices', function (Blueprint $table) {
            //
        });
    }
}
