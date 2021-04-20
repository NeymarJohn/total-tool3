<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanylogoAddressColumnInContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('contract_name')->nullable()->after('description');
            $table->string('company_logo')->nullable()->after('contract_name');
            $table->string('alternate_address')->nullable()->after('company_logo');
            $table->date('original_end_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('contract_name');
            $table->dropColumn('company_logo');
            $table->dropColumn('alternate_address');
            $table->dropColumn('original_end_date');
        });
    }
}
