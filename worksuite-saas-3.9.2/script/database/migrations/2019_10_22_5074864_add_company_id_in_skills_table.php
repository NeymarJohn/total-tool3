<?php

use App\EmployeeSkill;
use App\Scopes\CompanyScope;
use App\Skill;
use App\Company;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyIdInskillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->unsignedInteger('company_id')->nullable()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
        });

        $companies = Company::get();
        if ($companies->count() > 0){
            $skills = Skill::withoutGlobalScope(CompanyScope::class)->get();
            if ($skills->count() > 0){
                foreach ($companies as $company){
                    foreach ($skills as $skill){
                        $newSkill = new Skill();
                        $newSkill->company_id = $company->id;
                        $newSkill->name = $skill->name;
                        $newSkill->save();
                    }
                }
                $employeeSkills = EmployeeSkill::with(['user' => function($q){
                    $q->withoutGlobalScope(CompanyScope::class);
                }, 'skill' => function($q){
                    $q->withoutGlobalScope(CompanyScope::class);
                }])->get();
                if ($employeeSkills->count() > 0){
                    foreach ($employeeSkills as $employeeSkill){
                        $skill = Skill::withoutGlobalScope(CompanyScope::class)
                            ->where('name', $employeeSkill->skill->name)
                            ->where('company_id', $employeeSkill->user->company_id)
                            ->first();
                        $employeeSkill->skill_id = $skill->id;
                        $employeeSkill->save();
                    }
                }
                $skills = Skill::withoutGlobalScope(CompanyScope::class)->where('company_id', null)->get();
                if ($skills->count() > 0){
                    foreach ($skills as $skill){
                        Skill::destroy($skill->id);
                    }
                }
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
        Schema::table('skills', function (Blueprint $table) {
            $table->dropForeign('skills_company_id_foreign');
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id']);
        });
    }
}
