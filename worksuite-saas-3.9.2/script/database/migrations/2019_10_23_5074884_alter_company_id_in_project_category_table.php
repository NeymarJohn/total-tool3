<?php

use App\ClientDetails;
use App\CreditNotes;
use App\EmployeeDetails;
use App\Estimate;
use App\Invoice;
use App\Lead;
use App\Notice;
use App\Project;
use App\ProjectCategory;
use App\Proposal;
use App\Company;
use App\Scopes\CompanyScope;
use App\Task;
use App\Ticket;
use App\UniversalSearch;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompanyIdInProjectCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_category', function (Blueprint $table) {
            $table->dropForeign('project_category_company_id_foreign');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
        });
        $projectCategories = ProjectCategory::withoutGlobalScope(CompanyScope::class)->where('company_id', null)->get();
        if ($projectCategories->count() > 0){
            foreach ($projectCategories as $projectCategory){
                ProjectCategory::destroy($projectCategory->id);
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
        Schema::table('project_category', function (Blueprint $table) {
        });
    }
}
