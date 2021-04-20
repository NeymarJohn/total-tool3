<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToClientDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_details', function (Blueprint $table) {
            $table->string('skype')->nullable()->after('website');
            $table->string('twitter')->nullable()->after('website');
            $table->string('facebook')->nullable()->after('website');
            $table->string('linkedin')->nullable()->after('website');
            $table->text('note')->nullable()->after('website');
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
            $table->dropColumn(['skype', 'twitter', 'facebook', 'linkedin', 'note']);
        });
    }
}
