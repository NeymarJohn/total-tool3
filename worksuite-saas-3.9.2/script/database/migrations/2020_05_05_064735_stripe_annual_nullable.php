<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StripeAnnualNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // \DB::statement('ALTER TABLE `packages` MODIFY `stripe_annual_plan_id` INTEGER UNSIGNED  NULL;');
        // \DB::statement('ALTER TABLE `packages` MODIFY `stripe_monthly_plan_id` INTEGER UNSIGNED  NULL;');

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
