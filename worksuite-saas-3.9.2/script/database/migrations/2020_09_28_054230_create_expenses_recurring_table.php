<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesRecurringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses_recurring', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('category_id')->nullable()->default(null);
            $table->foreign('category_id')->references('id')->on('expenses_category')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('currency_id')->unsigned()->nullable()->default(null);
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('project_id')->unsigned()->nullable()->default(null);
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('user_id')->unsigned()->nullable()->default(null);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('created_by')->unsigned()->nullable()->default(null);
            $table->foreign('created_by')->references('id')->on('users')->onDelete(null)->onUpdate('cascade');
            $table->string('item_name');
            $table->integer('day_of_month')->nullable()->default(1);
            $table->integer('day_of_week')->nullable()->default(1);
            $table->string('payment_method')->nullable();
            $table->enum('rotation', ['monthly', 'weekly', 'bi-weekly', 'quarterly', 'half-yearly', 'annually', 'daily']);
            $table->integer('billing_cycle')->nullable()->default(null);
            $table->boolean('unlimited_recurring')->default(0);
            $table->double('price');
            $table->string('bill')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('expenses_recurring_id')->nullable()->default(null);
            $table->foreign('expenses_recurring_id')->references('id')->on('expenses_recurring')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('created_by')->unsigned()->nullable()->default(null);
            $table->foreign('created_by')->references('id')->on('users')->onDelete(null)->onUpdate('cascade');
            $table->text('description')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses_recurring');

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['expenses_recurring_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn('expenses_recurring_id', 'created_by');
        });
    }
}
