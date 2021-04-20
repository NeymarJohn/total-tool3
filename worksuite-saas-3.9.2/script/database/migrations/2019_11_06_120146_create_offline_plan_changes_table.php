<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflinePlanChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_plan_changes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('package_id');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');
            $table->string('package_type');
            
            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('offline_invoices')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('offline_method_id');
            $table->foreign('offline_method_id')->references('id')->on('offline_payment_methods')->onDelete('cascade')->onUpdate('cascade');

            $table->string('file_name')->nullable();
            $table->enum('status', ['verified', 'pending', 'rejected'])->default('pending');
            $table->mediumText('description');
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
        Schema::dropIfExists('offline_plan_changes');
    }
}
