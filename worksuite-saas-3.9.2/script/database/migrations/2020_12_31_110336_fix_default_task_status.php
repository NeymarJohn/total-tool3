<?php

use App\Scopes\CompanyScope;
use App\TaskboardColumn;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixDefaultTaskStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companies = \App\Company::where('default_task_status', '1')->orWhereNull('default_task_status')->get();
        if ($companies) {
            foreach ($companies as $company) {
                $taskBoard = TaskboardColumn::withoutGlobalScopes([CompanyScope::class, 'active'])->where('slug', 'incomplete')->where('company_id', $company->id)->first();
                $company->default_task_status = $taskBoard->id;
                $company->save();
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
