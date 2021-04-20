<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\LeadStatus;
use App\LeadSource;

class AddDefaultLeadSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sources = [
            ['type' => 'email'],
            ['type' => 'google'],
            ['type' => 'facebook'],
            ['type' => 'friend'],
            ['type' => 'direct visit'],
            ['type' => 'tv ad']
        ];

        LeadSource::insert($sources);

        $status = [
            ['type' => 'pending'],
            ['type' => 'inprocess'],
            ['type' => 'converted']
        ];

        LeadStatus::insert($status);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
