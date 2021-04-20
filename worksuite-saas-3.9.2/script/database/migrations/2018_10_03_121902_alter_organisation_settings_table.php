<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrganisationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE organisation_settings RENAME TO companies;');

        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedInteger('package_id')->nullable()->after('currency_id');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null')->onUpdate('cascade');
            $table->enum('package_type', ['monthly', 'annual'])->after('package_id')->default('monthly');
            $table->enum('status', ['active', 'inactive'])->after('active_theme')->default('active');

            $table->string('stripe_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('package_id');
        });

    }
}
