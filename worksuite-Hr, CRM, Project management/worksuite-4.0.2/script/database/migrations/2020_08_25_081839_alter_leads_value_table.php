<?php

use App\EmployeeLeaveQuota;
use App\LeaveType;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLeadsValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'value')){
                $table->double('value')->nullable()->default(0);
                $table->unsignedInteger('currency_id')->nullable()->default(null);
                $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null')->onUpdate('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign('currency_id');
            $table->dropColumn('due_amount');
            $table->dropColumn('currency_id');
        });
    }
}
