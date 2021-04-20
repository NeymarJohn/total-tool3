<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompanySettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organisation_settings', function(Blueprint $table){
            $table->string('date_format', 20)->default('d-m-Y')->after('timezone');
            $table->string('time_format', 20)->default('h:i a')->after('date_format');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organisation_settings', function(Blueprint $table){
            $table->dropColumn(['date_format', 'time_format']);
        });
    }
}
