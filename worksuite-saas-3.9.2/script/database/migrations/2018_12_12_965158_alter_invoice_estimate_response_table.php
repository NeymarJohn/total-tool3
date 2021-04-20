<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoiceEstimateResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function(Blueprint $table){
            $table->date('start_date')->nullable()->default(null)->after('due_date');
        });

        Schema::table('sub_tasks', function(Blueprint $table){
            $table->date('start_date')->nullable()->default(null)->after('due_date');
        });

        Schema::table('projects', function(Blueprint $table){
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE `estimates` CHANGE `sub_total` `sub_total` DOUBLE(8,2) NOT NULL");
        DB::statement("ALTER TABLE `estimates` CHANGE `total` `total` DOUBLE(8,2) NOT NULL");
        DB::statement("ALTER TABLE `proposals` CHANGE `sub_total` `sub_total` DOUBLE(8,2) NOT NULL");
        DB::statement("ALTER TABLE `proposals` CHANGE `total` `total` DOUBLE(8,2) NOT NULL");
        DB::statement("ALTER TABLE `proposal_items` CHANGE `unit_price` `unit_price` DOUBLE(8,2) NOT NULL");
        DB::statement("ALTER TABLE `estimate_items` CHANGE `unit_price` `unit_price` DOUBLE(8,2) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      //
    }
}
