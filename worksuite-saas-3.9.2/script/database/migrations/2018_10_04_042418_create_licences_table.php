<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licences', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('set null');
            $table->char('license_number', 29);
            $table->unsignedInteger('package_id')->nullable();
            $table->foreign('package_id')->references('id')->on('packages')->onUpdate('cascade')->onDelete('set null');
            $table->string('company_name', 100);
            $table->string('email', 255);
            $table->string('contact_person', 70)->nullable();
            $table->string('billing_name', 100)->nullable();
            $table->string('billing_address', 255)->nullable();
            $table->string('tax_number', 100)->nullable();
            $table->date('expire_date')->nullable();
            $table->date('last_payment_date')->nullable();
            $table->date('next_payment_date')->nullable();
            $table->enum('status', ['valid', 'invalid'])->default('valid');
            $table->timestamps();
        });

        // Seed countries table as soon as its migrated
        \Illuminate\Support\Facades\Artisan::call('db:seed', array('--class' => CountriesTableSeeder::class));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licences');
    }
}
