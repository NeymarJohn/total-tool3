<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceRecurringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_recurring', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('currency_id')->unsigned()->nullable()->default(null);
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('project_id')->unsigned()->nullable()->default(null);
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('client_id')->unsigned()->nullable()->default(null);
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('user_id')->unsigned()->nullable()->default(null);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('created_by')->unsigned()->nullable()->default(null);
            $table->foreign('created_by')->references('id')->on('users')->onDelete(null)->onUpdate('cascade');
            $table->date('issue_date');
            $table->date('due_date');
            $table->double('sub_total')->default(0);
            $table->double('total')->default(0);
            $table->double('discount')->default(0);
            $table->enum('discount_type', ['percent', 'fixed'])->default('percent');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('file')->nullable()->default(null);
            $table->string('file_original_name')->nullable()->default(null);
            $table->text('note')->nullable()->default(null);
            $table->enum('show_shipping_address', ['yes', 'no'])->default('no');
            $table->integer('day_of_month')->nullable()->default(1);
            $table->integer('day_of_week')->nullable()->default(1);
            $table->string('payment_method')->nullable();
            $table->enum('rotation', ['monthly', 'weekly', 'bi-weekly', 'quarterly', 'half-yearly', 'annually', 'daily']);
            $table->integer('billing_cycle')->nullable()->default(null);
            $table->boolean('unlimited_recurring')->default(0);
            $table->boolean('client_can_stop')->default(1);
            $table->dateTime('deleted_at')->nullable()->default(null);
            $table->text('shipping_address')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::create('invoice_recurring_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('invoice_recurring_id');
            $table->foreign('invoice_recurring_id')->references('id')->on('invoice_recurring')->onDelete('cascade')->onUpdate('cascade');
            $table->string('item_name');
            $table->double('quantity');
            $table->double('unit_price');
            $table->double('amount');
            $table->text('taxes')->nullable()->default(null);
            $table->enum('type',['item', 'discount', 'tax'])->default('item');
            $table->text('item_summary')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_recurring_id')->nullable()->default(null);
            $table->foreign('invoice_recurring_id')->references('id')->on('invoice_recurring')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('created_by')->unsigned()->nullable()->default(null);
            $table->foreign('created_by')->references('id')->on('users')->onDelete(null)->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_recurring');
        Schema::dropIfExists('invoice_recurring_items');

        Schema::table('invoice', function (Blueprint $table) {
            $table->dropForeign(['invoice_recurring_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn('invoice_recurring_id', 'created_by');
        });
    }
}
