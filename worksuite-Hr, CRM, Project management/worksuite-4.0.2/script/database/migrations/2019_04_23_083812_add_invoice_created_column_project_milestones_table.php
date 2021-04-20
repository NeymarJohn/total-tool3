<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceCreatedColumnProjectMilestonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_milestones', function (Blueprint $table) {
            $table->boolean('invoice_created');
            $table->integer('invoice_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_milestones', function (Blueprint $table) {
            $table->dropColumn(['invoice_created']);
            $table->dropColumn(['invoice_id']);
        });
    }
}
