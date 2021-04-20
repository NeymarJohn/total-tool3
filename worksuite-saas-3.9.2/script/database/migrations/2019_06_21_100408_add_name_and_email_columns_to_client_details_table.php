<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\ClientDetails;

class AddNameAndEmailColumnsToClientDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_details', function (Blueprint $table) {
            $table->string('name')->nullable()->after('user_id');
            $table->string('email')->nullable()->after('name');
            $table->string('image')->nullable()->after('email');
            $table->string('mobile')->nullable()->after('image');
        });

        $users = DB::select('select * from users');

        foreach ($users as $user) {
            $client = ClientDetails::where('user_id', $user->id)->first();
            
            if($client) {
                $client->name = $user->name;
                $client->email = $user->email;
                $client->image = $user->image;
                $client->mobile = $user->mobile;

                $client->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_details', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('email');
            $table->dropColumn('image');
            $table->dropColumn('mobile');
        });
    }
}
