<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StripeAnnualPlanNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE `packages` CHANGE `stripe_annual_plan_id` `stripe_annual_plan_id` VARCHAR(191) NULL DEFAULT NULL;');
        \DB::statement('ALTER TABLE `packages` CHANGE `stripe_monthly_plan_id` `stripe_monthly_plan_id` VARCHAR(191) NULL DEFAULT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            //
        });
    }
}
