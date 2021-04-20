<?php

use App\LeadCustomForm;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadCustomFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_custom_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->string('field_display_name');
            $table->string('field_name');
            $table->integer('field_order');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        $companies =  \App\Company::withoutGlobalScope('active')->get();
        foreach ($companies as $company) {
            $fields = ['Client Name', 'Client Email', 'Company Name', 'Website', 'Address', 'Mobile'];
            $fieldsName = ['client_name', 'client_email', 'company_name', 'website', 'address', 'mobile'];

            foreach ($fields as $key => $value) {
                LeadCustomForm::create([
                    'field_display_name' => $value,
                    'field_name' => $fieldsName[$key],
                    'field_order' => $key+1,
                    'company_id' => $company->id
                ]);
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
        Schema::dropIfExists('lead_custom_forms');
    }
}
