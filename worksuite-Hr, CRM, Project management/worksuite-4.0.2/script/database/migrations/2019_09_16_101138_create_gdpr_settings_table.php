<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGdprSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gdpr_settings', function (Blueprint $table) {
            $table->increments('id');

            $table->boolean('enable_gdpr')->default(0);
            $table->boolean('show_customer_area')->default(0);
            $table->boolean('show_customer_footer')->default(0);
            $table->longText('top_information_block')->nullable();

            $table->boolean('enable_export')->default(0);

            $table->boolean('data_removal')->default(0);
            $table->boolean('lead_removal_public_form')->default(0);

            $table->boolean('terms_customer_footer')->default(0);
            $table->longText('terms')->nullable();
            $table->longText('policy')->nullable();

            $table->boolean('public_lead_edit')->default(0);

            $table->boolean('consent_customer')->default(0);
            $table->boolean('consent_leads')->default(0);
            $table->longText('consent_block')->nullable();

            $table->timestamps();
        });

        $gdpr = new \App\GdprSetting();
        $gdpr->create();

        Schema::create('purpose_consent', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->text('description')->nullable();

            $table->timestamps();
        });

        Schema::create('purpose_consent_leads', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('lead_id')->unsigned();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('purpose_consent_id')->unsigned();
            $table->foreign('purpose_consent_id')->references('id')->on('purpose_consent')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['agree', 'disagree'])->default('agree');
            $table->string('ip')->nullable();

            $table->integer('updated_by_id')->unsigned()->nullable()->default(null);
            $table->foreign('updated_by_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->text('additional_description')->nullable();

            $table->timestamps();
        });

        Schema::create('purpose_consent_users', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('purpose_consent_id')->unsigned();
            $table->foreign('purpose_consent_id')->references('id')->on('purpose_consent')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['agree', 'disagree'])->default('agree');
            $table->string('ip')->nullable();

            $table->integer('updated_by_id')->unsigned();
            $table->foreign('updated_by_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->text('additional_description')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gdpr_settings');
        Schema::dropIfExists('purpose_consent_leads');
        Schema::dropIfExists('purpose_consent_users');
        Schema::dropIfExists('purpose_consent');
    }
}
