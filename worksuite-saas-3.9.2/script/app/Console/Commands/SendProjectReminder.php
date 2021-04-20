<?php

namespace App\Console\Commands;

use App\Company;
use App\GlobalSetting;
use App\Notifications\ProjectReminder;
use App\Project;
use App\ProjectSetting;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;

class SendProjectReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-project-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send project reminder to the admins before specified days of the project';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $companies = Company::all();

        if ($companies) {
            foreach($companies as $company) {
                if ($this->project_setting($company->id)->send_reminder == 'yes') {
                    $projects = Project::whereNotNull('deadline')
                        ->whereDate('deadline', Carbon::now($company->timezone)->addDays($this->project_setting($company->id)->remind_time))
                        ->where('company_id', $company->id)
                        ->get()->makeHidden('isProjectAdmin');

                    if ($projects->count() > 0) {
                        $members = [];

                        foreach ($projects as $project) {
                            // get project members
                            foreach ($project->members as $member) {
                                $members = Arr::add($members, $member->user->id, $member->user);
                            }
                        }
                        $members = collect(array_values($members));

                        $users = [];
                        if (in_array('admins', $this->project_setting($company->id)->remind_to) && in_array('members', $this->project_setting($company->id)->remind_to)) {
                            $admins = $this->allAdmins($company->id)->makeHidden('unreadNotifications');
                            $users = $admins->merge($members);
                        }
                        else {
                            if (in_array('admins', $this->project_setting($company->id)->remind_to)) {
                                $users = $this->allAdmins($company->id)->makeHidden('unreadNotifications');
                            }
                            if (in_array('members', $this->project_setting($company->id)->remind_to)) {
                                $users = collect($users)->merge($members);
                            }
                        }
                        if($users->count() > 0) {
                            foreach ($users as $user) {
                                $projectsArr = [];
                                foreach ($user->member as $projectMember) {
                                    $projectsArr = Arr::add($projectsArr, $projectMember->project->id, $projectMember->project->makeHidden('isProjectAdmin'));
                                }
                                $projectsArr = collect(array_values($projectsArr));
                                if (!$user->isAdmin($user->id)) {
                                    $projectsArr = $this->filterProjects($projectsArr, $company);
                                }
                                else {
                                    if (!in_array('admins', $this->project_setting($company->id)->remind_to)) {
                                        $projectsArr = $this->filterProjects($projectsArr, $company);
                                    }
                                    else {
                                        $projectsArr = $projects;
                                    }
                                }
                                $user->notify(new ProjectReminder($projectsArr, ['company' => $company, 'project_setting' => $this->project_setting($company->id)]));
                            }
                        }
                    }
                }
            }
        }
    }

    public function allAdmins($company_id)
    {
        return User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'admin')->where('users.company_id', $company_id)->get();
    }

    public function project_setting($company_id)
    {
        return ProjectSetting::where('company_id', $company_id)->first();

    }

    public function filterProjects($projectsArr, $company)
    {
        return $projectsArr->filter(function($project) use ($company) {
            return Carbon::parse($project->deadline, $company->timezone)->equalTo(Carbon::now($company->timezone)->addDays($this->project_setting($company->id)->remind_time)->startOfDay());
        });
    }
}
