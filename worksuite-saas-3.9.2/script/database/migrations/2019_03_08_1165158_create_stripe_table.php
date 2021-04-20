<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('stripe_setting')){
            Schema::create('stripe_setting', function(Blueprint $table)
            {
                $table->increments('id');
                $table->string('api_key')->nullable()->default(null);
                $table->string('api_secret')->nullable()->default(null);
                $table->string('webhook_key')->nullable()->default(null);
                $table->timestamps();
            });

            $stripe = new \App\StripeSetting();
            $stripe->api_key = null;
            $stripe->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripe_setting');

    }
}
