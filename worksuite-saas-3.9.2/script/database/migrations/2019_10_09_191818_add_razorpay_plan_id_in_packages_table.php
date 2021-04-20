<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRazorpayPlanIdInPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('razorpay_annual_plan_id')->nullable()->default(null)->after('stripe_annual_plan_id');
            $table->string('razorpay_monthly_plan_id')->nullable()->default(null)->after('razorpay_annual_plan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('razorpay_annual_plan_id');
            $table->dropColumn('razorpay_monthly_plan_id');
        });
    }
}
