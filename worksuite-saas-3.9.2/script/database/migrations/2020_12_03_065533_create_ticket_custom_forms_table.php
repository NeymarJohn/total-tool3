<?php

use App\TicketCustomForm;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketCustomFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_custom_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->string('field_display_name');
            $table->string('field_name');
            $table->string('field_type')->default('text');
            $table->integer('field_order');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        $companies =  \App\Company::withoutGlobalScope('active')->get();
        foreach ($companies as $company) {
            $fields = ['Name','Email','Ticket Subject', 'Ticket Description', 'Type', 'Priority'];
            $fieldsName = ['name','email', 'ticket_subject', 'ticket_description', 'type', 'priority'];
            $fieldsType = ['text','text', 'text', 'textarea', 'select', 'select'];

            foreach ($fields as $key => $value) {

                    TicketCustomForm::create([
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
        Schema::dropIfExists('ticket_custom_forms');
    }
}
