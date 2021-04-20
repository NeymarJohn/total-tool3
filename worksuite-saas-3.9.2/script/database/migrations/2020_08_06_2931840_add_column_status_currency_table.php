<?php

use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStatusCurrencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->enum('status', ['enable', 'disable'])->default('enable');
        });
        Schema::table('global_currencies', function (Blueprint $table) {
            $table->enum('status', ['enable', 'disable'])->default('enable');
        });

        // client status null to active
        $clients = User::withoutGlobalScopes([CompanyScope::class, 'active'])->where('status', '')->select('id', 'status')->update(['status' => 'active']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn(['status']);
        });
        Schema::table('global_currencies', function (Blueprint $table) {
            $table->dropColumn(['status']);
        });
    }
}
