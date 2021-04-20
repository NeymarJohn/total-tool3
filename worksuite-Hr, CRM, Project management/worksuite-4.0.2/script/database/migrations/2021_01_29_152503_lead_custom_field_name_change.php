<?php

use App\LeadCustomForm;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LeadCustomFieldNameChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $customFields = LeadCustomForm::where('field_name', 'client_name')
            ->orWhere('field_name', 'client_email')
            ->get();
        if ($customFields) {
            foreach ($customFields as $key => $field) {
                $field->field_display_name  = str_replace('Client ', '', $field->field_display_name);
                $field->field_name  = str_replace('client_', '', $field->field_name);
                $field->save();
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
        Schema::table('lead_custom_forms', function (Blueprint $table) {
            //
        });
    }
}
