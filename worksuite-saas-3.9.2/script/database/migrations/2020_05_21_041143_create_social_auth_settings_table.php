<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialAuthSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_auth_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('facebook_client_id')->nullable();
            $table->string('facebook_secret_id')->nullable();
            $table->enum('facebook_status', ['enable', 'disable'])->default('disable');
            $table->string('google_client_id')->nullable();
            $table->string('google_secret_id')->nullable();
            $table->enum('google_status', ['enable', 'disable'])->default('disable');
            $table->string('twitter_client_id')->nullable();
            $table->string('twitter_secret_id')->nullable();
            $table->enum('twitter_status', ['enable', 'disable'])->default('disable');
            $table->string('linkedin_client_id')->nullable();
            $table->string('linkedin_secret_id')->nullable();
            $table->enum('linkedin_status', ['enable', 'disable'])->default('disable');
            $table->timestamps();
        });

        Schema::create('socials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->text('social_id');
            $table->text('social_service');
            $table->timestamps();
        });

        \App\SocialAuthSetting::create([
            'facebook_status' => 'disable',
            'google_status' => 'disable',
            'linkedin_status' => 'disable',
            'twitter_status' => 'disable',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_auth_settings');
        Schema::dropIfExists('socials');
    }
}
