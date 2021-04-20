<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAttendanceCheckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_settings', function(Blueprint $table){
            $table->text('ip_address')->nullable()->default(null)->after('office_open_days');
            $table->integer('radius')->nullable()->default(null)->after('ip_address');
            $table->enum('radius_check',['yes','no'])->default('no')->after('radius');
            $table->enum('ip_check',['yes','no'])->default('no')->after('radius_check');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_settings', function(Blueprint $table){
            $table->dropColumn(['ip_address','radius','radius_check','ip_check']);
        });
    }
}
