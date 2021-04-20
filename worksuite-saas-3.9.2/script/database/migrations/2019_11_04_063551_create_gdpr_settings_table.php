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

            $table->unsignedInteger('company_id')->nullable()->default(null);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');

            $table->boolean('enable_gdpr')->default(false);
            $table->boolean('show_customer_area')->default(false);
            $table->boolean('show_customer_footer')->default(false);
            $table->longText('top_information_block')->nullable();

            $table->boolean('enable_export')->default(false);

            $table->boolean('data_removal')->default(false);
            $table->boolean('lead_removal_public_form')->default(false);

            $table->boolean('terms_customer_footer')->default(false);
            $table->longText('terms')->nullable();
            $table->longText('policy')->nullable();

            $table->boolean('public_lead_edit')->default(false);

            $table->boolean('consent_customer')->default(false);
            $table->boolean('consent_leads')->default(false);
            $table->longText('consent_block')->nullable();

            $table->timestamps();
        });

        $allCompanies = \App\Company::all();
        foreach ($allCompanies as $allCompany)
        {
            $gdpr = new \App\GdprSetting();
            $gdpr->company_id = $allCompany->id;
            $gdpr->save();
        }

        Schema::create('purpose_consent', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('company_id')->nullable()->default(null);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('purpose_consent');
        Schema::dropIfExists('purpose_consent_leads');
        Schema::dropIfExists('purpose_consent_users');

    }
}
