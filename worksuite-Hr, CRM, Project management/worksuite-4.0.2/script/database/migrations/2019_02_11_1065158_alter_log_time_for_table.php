<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLogTimeForTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_time_for', function(Blueprint $table){
            $table->enum('auto_timer_stop',['yes','no'])->default('no')->after('log_time_for');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_time_for', function(Blueprint $table){
            $table->dropColumn('auto_timer_stop');
        });
    }
}
