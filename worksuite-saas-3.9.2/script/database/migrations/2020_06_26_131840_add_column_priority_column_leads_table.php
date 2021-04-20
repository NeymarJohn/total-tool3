<?php

use App\Lead;
use App\LeadStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPriorityColumnLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->integer('column_priority')->after('status_id');
        });

        Schema::table('lead_status', function (Blueprint $table) {
            $table->integer('priority');
            $table->boolean('default');
            $table->string('label_color')->default('#ff0000');
        });

        $companies = \App\Company::all();

        if ($companies) {
            foreach ($companies as $company) {
                $status = LeadStatus::where('company_id', $company->id)->orderBy('id', 'asc')->get();
                foreach ($status as $key => $value) {
                    if ($key == 0) {
                        $value->default = 1;
                    }
                    $maxPriority = LeadStatus::where('company_id', $company->id)->max('priority');
                    $value->priority = ($maxPriority + 1);
                    $value->save();
                }
                
                $defaultStatus = LeadStatus::where('default', 1)->where('company_id', $company->id)->first();
                Lead::whereNull('status_id')->where('company_id', $company->id)->update(['status_id' => $defaultStatus->id]);
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
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['column_priority']);
        });
        Schema::table('lead_status', function (Blueprint $table) {
            $table->dropColumn(['priority', 'label_color', 'default']);
        });
    }
}
