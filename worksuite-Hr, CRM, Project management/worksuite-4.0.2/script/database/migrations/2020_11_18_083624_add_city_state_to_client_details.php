<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCityStateToClientDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_details', function (Blueprint $table) {
            $table->string('cell')->nullable()->after('shipping_address');
            $table->string('office')->nullable()->after('shipping_address');
            $table->string('city')->nullable()->after('shipping_address');
            $table->string('state')->nullable()->after('shipping_address');
            $table->string('country')->nullable()->after('shipping_address');
            $table->string('postal_code')->nullable()->after('shipping_address');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_details', function (Blueprint $table) {
            $table->dropColumn(['cell', 'office', 'city', 'state', 'country','postal_code']);
        });
    }
}
