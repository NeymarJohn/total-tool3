<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInEstimateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimate_items', function (Blueprint $table) {
            $table->unsignedInteger('tax_id')->nullable()->after('id');
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->string('discount');
            $table->string('discount_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimate_items', function (Blueprint $table) {
            $table->dropColumn('tax_id');
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn('discount');
            $table->dropColumn('discount_type');
        });
    }
}
