<?php

use App\ClientDetails;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrationForClientNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $allClient = ClientDetails::whereNull('name')->get();

        foreach ($allClient as $key => $client) {
            $client->name = $client->user->getOriginal('name');
            $client->email = $client->user->getOriginal('email');
            $client->save();
        }
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
