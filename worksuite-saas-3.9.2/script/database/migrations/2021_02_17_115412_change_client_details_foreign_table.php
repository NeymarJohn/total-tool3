<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\GlobalSetting;

class ChangeClientDetailsForeignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_details', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('client_categories')->onDelete('SET NUll')->onUpdate('cascade');
        });

        Schema::table('client_details', function (Blueprint $table) {
            $table->dropForeign(['sub_category_id']);
            $table->foreign('sub_category_id')->references('id')->on('client_sub_categories')->onDelete('SET NUll')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
