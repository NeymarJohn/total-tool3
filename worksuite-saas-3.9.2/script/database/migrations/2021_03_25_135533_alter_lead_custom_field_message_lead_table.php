<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLeadCustomFieldMessageLeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companies =  \App\Company::withoutGlobalScope('active')->get();
        foreach ($companies as $company) {
            $leadForm = \App\LeadCustomForm::where('company_id', $company->id)
                ->where('field_name', 'message')->first();
            if(is_null($leadForm)){
                \App\LeadCustomForm::create([
                    'field_display_name' => 'Message',
                    'field_name' => 'message',
                    'field_order' => 7,
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
       //
    }
}
