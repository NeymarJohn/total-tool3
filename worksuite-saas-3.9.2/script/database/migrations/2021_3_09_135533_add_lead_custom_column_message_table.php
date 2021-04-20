<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \App\Company;
use \App\LeadCustomForm;

class AddLeadCustomColumnMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        LeadCustomForm::where('field_name', 'client_name')->update(['field_name' => 'name', 'field_display_name' => 'Name']);
        LeadCustomForm::where('field_name', 'client_email')->update(['field_name' => 'email', 'field_display_name' => 'Email']);
        $companies =  Company::withoutGlobalScope('active')->get();
        foreach ($companies as $company) {
            LeadCustomForm::create([
                'field_display_name' => 'Message',
                'field_name' => 'message',
                'field_order' => 7,
                'company_id' => $company->id
            ]);
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
