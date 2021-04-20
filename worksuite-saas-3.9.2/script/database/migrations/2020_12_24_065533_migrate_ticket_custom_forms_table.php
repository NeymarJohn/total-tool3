<?php

use App\TicketCustomForm;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateTicketCustomFormsTable extends Migration
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
            $fields = ['Name','Email','Ticket Subject', 'Ticket Description', 'Type', 'Priority'];
            $fieldsName = ['name','email', 'ticket_subject', 'ticket_description', 'type', 'priority'];
            $fieldsType = ['text','text', 'text', 'textarea', 'select', 'select'];

            foreach ($fields as $key => $value) {

                    TicketCustomForm::firstOrCreate([
                        'field_display_name' => $value,
                        'field_name' => $fieldsName[$key],
                        'field_order' => $key+1,
                        'field_type' => $fieldsType[$key],
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
//        Schema::dropIfExists('ticket_custom_forms');
    }
}
