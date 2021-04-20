<?php

namespace App\Observers;

use App\ClientDetails;
use App\Company;
use App\Currency;
use App\DashboardWidget;
use App\EmployeeDetails;
use App\EmployeeLeaveQuota;
use App\Events\CompanyRegistered;
use App\GdprSetting;
use App\GlobalCurrency;
use App\LeadSource;
use App\LeadStatus;
use App\LeaveType;
use App\LogTimeFor;
use App\MessageSetting;
use App\ModuleSetting;
use App\Package;
use App\PackageSetting;
use App\Role;
use App\RoleUser;
use App\Scopes\CompanyScope;
use App\TaskboardColumn;
use App\ThemeSetting;
use App\TicketChannel;
use App\TicketCustomForm;
use App\TicketGroup;
use App\TicketType;
use App\GlobalSetting;
use App\LeadCustomForm;
use App\ProjectSetting;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class CompanyObserver
{

    public function created(Company $company)
    {

        // Package setting for get trial package active or not
        $packageSetting = PackageSetting::where('status', 'active')->first();
        $packages = Package::all();

        // get trial package data
        $trialPackage = $packages->filter(function ($value, $key) {
            return $value->default == 'trial';
        })->first();

        // get default package data
        $defaultPackage = $packages->filter(function ($value, $key) {
            return $value->default == 'yes';
        })->first();

        // get another  package data if trial and default package not found
        $otherPackage = $packages->filter(function ($value, $key) {
            return $value->default == 'no';
        })->first();

        // if trial package is active set package to company
        if ($packageSetting && !is_null($trialPackage)) {
            $company->package_id = $trialPackage->id;
            // set company license expire date
            $noOfDays = (!is_null($packageSetting->no_of_days) && $packageSetting->no_of_days != 0) ? $packageSetting->no_of_days : 30;
            $company->licence_expire_on = Carbon::now()->addDays($noOfDays)->format('Y-m-d');
        }

        // if trial package is not active set default package to company
        elseif (!is_null($defaultPackage))
            $company->package_id = $defaultPackage->id;
        else {
            $company->package_id = $otherPackage->id;
        }

        if ($company->date_picker_format == '') {
            $company->date_picker_format = 'dd-mm-yyyy';
        }

        if (is_null($company->locale)) {
            $company->locale = is_null(global_settings()->new_company_locale) ? "en" : global_settings()->new_company_locale;
        }

        switch ($company->date_format) {
            case 'd-m-Y':
                $company->moment_format = 'DD-MM-YYYY';
                break;
            case 'm-d-Y':
                $company->moment_format = 'MM-DD-YYYY';
                break;
            case 'Y-m-d':
                $company->moment_format = 'YYYY-MM-DD';
                break;
            case 'd.m.Y':
                $company->moment_format = 'DD.MM.YYYY';
                break;
            case 'm.d.Y':
                $company->moment_format = 'MM.DD.YYYY';
                break;
            case 'Y.m.d':
                $company->moment_format = 'YYYY.MM.DD';
                break;
            case 'd/m/Y':
                $company->moment_format = 'DD/MM/YYYY';
                break;
            case 'Y/m/d':
                $company->moment_format = 'YYYY/MM/DD';
                break;
            case 'd-M-Y':
                $company->moment_format = 'DD-MMM-YYYY';
                break;
            case 'd/M/Y':
                $company->moment_format = 'DD/MMM/YYYY';
                break;
            case 'd.M.Y':
                $company->moment_format = 'DD.MMM.YYYY';
                break;
            case 'd M Y':
                $company->moment_format = 'DD MMM YYYY';
                break;
            case 'd F, Y':
                $company->moment_format = 'DD MMMM, YYYY';
                break;
            case 'D/M/Y':
                $company->moment_format = 'ddd/MMM/YYYY';
                break;
            case 'D.M.Y':
                $company->moment_format = 'ddd.MMM.YYYY';
                break;
            case 'D-M-Y':
                $company->moment_format = 'ddd-MMM-YYYY';
                break;
            case 'D M Y':
                $company->moment_format = 'ddd MMM YYYY';
                break;
            case 'd D M Y':
                $company->moment_format = 'DD ddd MMM YYYY';
                break;
            case 'D d M Y':
                $company->moment_format = 'ddd DD MMMM YYYY';
                break;
            case 'dS M Y':
                $company->moment_format = 'Do MMM YYYY';
                break;
            default:
                $company->moment_format = 'DD-MM-YYYY';
                break;
        }

        $company->save();

        $this->addTaskBoard($company);
        $this->addTicketChannel($company);
        $this->addTicketType($company);
        $this->addTicketGroup($company);
        $this->addLeaveType($company);
        $this->addEmailNotificationSettings($company);
        $this->addDefaultCurrencies($company);
        $this->addDefaultThemeSettings($company);
        $this->addPaymentGatewaySettings($company);
        $this->addInvoiceSettings($company);
        $this->addSlackSettings($company);
        $this->addProjectSettings($company);
        $this->addAttendanceSettings($company);
        $this->addCustomFieldGroup($company);
        $this->addRoles($company);
        $this->addMessageSetting($company);
        $this->addLogTImeForSetting($company);
        $this->addLeadSourceAndLeadStatus($company);
        //$this->addProjectCategory($company);
        $this->addDashboardWidget($company);
        $this->insertGDPR($company);
        $this->insertTicketFormField($company);

        $this->addDefaultTimezone($company);
        $this->addLeadCustomForm($company);

        event(new CompanyRegistered($company));
    }

    public function addDefaultTimezone($company){
        $globalSetting = GlobalSetting::first();
        $company->timezone = $globalSetting->timezone;
        $company->save();
    }

    public function saving(Company $company)
    {
        session()->forget('company_setting');
        session()->forget('company');
    }

    public function updated(Company $company)
    {

        if ($company->isDirty('package_id')) {
            ModuleSetting::where('company_id', $company->id)->delete();
            ModuleSetting::whereNull('company_id')->delete();
            $package = Package::findOrFail($company->package_id);

            $moduleInPackage = (array) json_decode($package->module_in_package);
            $clientModules = ['projects', 'tickets', 'invoices', 'estimates', 'events', 'products', 'tasks', 'messages', 'payments', 'contracts', 'notices', 'timelogs'];
            foreach ($moduleInPackage as $module) {

                if (in_array($module, $clientModules)) {
                    $moduleSetting = new ModuleSetting();
                    $moduleSetting->company_id = $company->id;
                    $moduleSetting->module_name = $module;
                    $moduleSetting->status = 'active';
                    $moduleSetting->type = 'client';
                    $moduleSetting->save();
                }

                $moduleSetting = new ModuleSetting();
                $moduleSetting->company_id = $company->id;
                $moduleSetting->module_name = $module;
                $moduleSetting->status = 'active';
                $moduleSetting->type = 'employee';
                $moduleSetting->save();

                $moduleSetting = new ModuleSetting();
                $moduleSetting->company_id = $company->id;
                $moduleSetting->module_name = $module;
                $moduleSetting->status = 'active';
                $moduleSetting->type = 'admin';
                $moduleSetting->save();
            }
        }
        session()->forget('company_setting');
        session()->forget('company');
    }

    public function updating(Company $company)
    {

        $user = user();

        if ($user) {
            $company->last_updated_by = $user->id;
        }

        if ($company->isDirty('date_format')) {
            switch ($company->date_format) {
                case 'd-m-Y':
                    $company->date_picker_format = 'dd-mm-yyyy';
                    break;
                case 'm-d-Y':
                    $company->date_picker_format = 'mm-dd-yyyy';
                    break;
                case 'Y-m-d':
                    $company->date_picker_format = 'yyyy-mm-dd';
                    break;
                case 'd.m.Y':
                    $company->date_picker_format = 'dd.mm.yyyy';
                    break;
                case 'm.d.Y':
                    $company->date_picker_format = 'mm.dd.yyyy';
                    break;
                case 'Y.m.d':
                    $company->date_picker_format = 'yyyy.mm.dd';
                    break;
                case 'd/m/Y':
                    $company->date_picker_format = 'dd/mm/yyyy';
                    break;
                case 'Y/m/d':
                    $company->date_picker_format = 'yyyy/mm/dd';
                    break;
                case 'd-M-Y':
                    $company->date_picker_format = 'dd-M-yyyy';
                    break;
                case 'd/M/Y':
                    $company->date_picker_format = 'dd/M/yyyy';
                    break;
                case 'd.M.Y':
                    $company->date_picker_format = 'dd.M.yyyy';
                    break;
                case 'd M Y':
                    $company->date_picker_format = 'dd M yyyy';
                    break;
                case 'd F, Y':
                    $company->date_picker_format = 'dd MM, yyyy';
                    break;
                case 'D/M/Y':
                    $company->date_picker_format = 'D/M/yyyy';
                    break;
                case 'D.M.Y':
                    $company->date_picker_format = 'D.M.yyyy';
                    break;
                case 'D-M-Y':
                    $company->date_picker_format = 'D-M-yyyy';
                    break;
                case 'D M Y':
                    $company->date_picker_format = 'D M yyyy';
                    break;
                case 'd D M Y':
                    $company->date_picker_format = 'dd D M yyyy';
                    break;
                case 'D d M Y':
                    $company->date_picker_format = 'D dd M yyyy';
                    break;
                case 'dS M Y':
                    $company->date_picker_format = 'dd M yyyy';
                    break;
                default:
                    $company->date_picker_format = 'mm/dd/yyyy';
                    break;
            }

            switch ($company->date_format) {
                case 'd-m-Y':
                    $company->moment_format = 'DD-MM-YYYY';
                    break;
                case 'm-d-Y':
                    $company->moment_format = 'MM-DD-YYYY';
                    break;
                case 'Y-m-d':
                    $company->moment_format = 'YYYY-MM-DD';
                    break;
                case 'd.m.Y':
                    $company->moment_format = 'DD.MM.YYYY';
                    break;
                case 'm.d.Y':
                    $company->moment_format = 'MM.DD.YYYY';
                    break;
                case 'Y.m.d':
                    $company->moment_format = 'YYYY.MM.DD';
                    break;
                case 'd/m/Y':
                    $company->moment_format = 'DD/MM/YYYY';
                    break;
                case 'Y/m/d':
                    $company->moment_format = 'YYYY/MM/DD';
                    break;
                case 'd-M-Y':
                    $company->moment_format = 'DD-MMM-YYYY';
                    break;
                case 'd/M/Y':
                    $company->moment_format = 'DD/MMM/YYYY';
                    break;
                case 'd.M.Y':
                    $company->moment_format = 'DD.MMM.YYYY';
                    break;
                case 'd M Y':
                    $company->moment_format = 'DD MMM YYYY';
                    break;
                case 'd F, Y':
                    $company->moment_format = 'DD MMMM, YYYY';
                    break;
                case 'D/M/Y':
                    $company->moment_format = 'ddd/MMM/YYYY';
                    break;
                case 'D.M.Y':
                    $company->moment_format = 'ddd.MMM.YYYY';
                    break;
                case 'D-M-Y':
                    $company->moment_format = 'ddd-MMM-YYYY';
                    break;
                case 'D M Y':
                    $company->moment_format = 'ddd MMM YYYY';
                    break;
                case 'd D M Y':
                    $company->moment_format = 'DD ddd MMM YYYY';
                    break;
                case 'D d M Y':
                    $company->moment_format = 'ddd DD MMMM YYYY';
                    break;
                case 'dS M Y':
                    $company->moment_format = 'Do MMM YYYY';
                    break;
                default:
                    $company->moment_format = 'MM/DD/YYYY';
                    break;
            }
        }
    }

    public function deleting(Company $company)
    {
        $projects = \App\Project::where('company_id', $company->id)->get();

        $otherCompanyClient = ClientDetails::with('user')
                                    ->select('client_details.id as clientid','users.id as userid','client_details.company_id as client_company_id',
                                        'client_details.name as clientname')
                                    ->withoutGlobalScope(CompanyScope::class)->where('client_details.company_id', '<>',$company->id)
                                    ->join('users', 'users.id', 'client_details.user_id')
                                    ->where('users.company_id', $company->id)
                                    ->get();

        foreach($otherCompanyClient as $clientUser){

            $userData = User::withoutGlobalScopes(['active', CompanyScope::class])->find($clientUser->userid);
            $userData->company_id = $clientUser->client_company_id;
            $userData->name = $clientUser->clientname;
            $userData->save();
            $employeeRole = Role::where('name', 'admin')->first();
            RoleUser::where('user_id', $userData->id)->where('role_id', $employeeRole->id)->delete();
        }


        foreach ($projects as $project) {
            File::deleteDirectory('user-uploads/project-files/' . $project->id);
            $project->forceDelete();
        }

        $expenses = \App\Expense::where('company_id', $company->id)->get();
        foreach ($expenses as $expense) {
            File::delete('user-uploads/expense-invoice/' . $expense->bill);
        }

        $users = \App\User::where('company_id', $company->id)->get();
        foreach ($users as $user) {
            File::delete('user-uploads/avatar/' . $user->image);
        }

        File::delete('user-uploads/app-logo/' . $company->logo);
    }

    public function addTaskBoard($company)
    {

        $uncatColumn = new TaskboardColumn();
        $uncatColumn->company_id = $company->id;
        $uncatColumn->column_name = 'Incomplete';
        $uncatColumn->slug = 'incomplete';
        $uncatColumn->label_color = '#d21010';
        $uncatColumn->label_color = '#d21010';
        $uncatColumn->priority = 1;
        $uncatColumn->save();

        $completeColumn = new TaskboardColumn();
        $completeColumn->company_id = $company->id;
        $completeColumn->column_name = 'Completed';
        $completeColumn->slug = 'completed';
        $completeColumn->label_color = '#679c0d';
        $completeColumn->priority = $uncatColumn->priority + 1;
        $completeColumn->save();
    }

    public function addTicketChannel($company)
    {
        $channel = new TicketChannel();
        $channel->company_id = $company->id;
        $channel->channel_name = 'Email';
        $channel->save();

        $channel = new TicketChannel();
        $channel->company_id = $company->id;
        $channel->channel_name = 'Phone';
        $channel->save();

        $channel = new TicketChannel();
        $channel->company_id = $company->id;
        $channel->channel_name = 'Twitter';
        $channel->save();

        $channel = new TicketChannel();
        $channel->company_id = $company->id;
        $channel->channel_name = 'Facebook';
        $channel->save();
    }

    public function addTicketType($company)
    {
        $type = new TicketType();
        $type->company_id = $company->id;
        $type->type = 'Question';
        $type->save();

        $type = new TicketType();
        $type->company_id = $company->id;
        $type->type = 'Problem';
        $type->save();

        $type = new TicketType();
        $type->company_id = $company->id;
        $type->type = 'Incident';
        $type->save();

        $type = new TicketType();
        $type->company_id = $company->id;
        $type->type = 'Feature Request';
        $type->save();
    }

    public function addTicketGroup($company)
    {
        $group = new TicketGroup();
        $group->company_id = $company->id;
        $group->group_name = 'Sales';
        $group->save();

        $group = new TicketGroup();
        $group->company_id = $company->id;
        $group->group_name = 'Code';
        $group->save();

        $group = new TicketGroup();
        $group->company_id = $company->id;
        $group->group_name = 'Management';
        $group->save();
    }

    public function addLeaveType($company)
    {
        $category = new LeaveType();
        $category->company_id = $company->id;
        $category->type_name = 'Casual';
        $category->color = 'success';
        $category->save();

        $category = new LeaveType();
        $category->company_id = $company->id;
        $category->type_name = 'Sick';
        $category->color = 'danger';
        $category->save();

        $category = new LeaveType();
        $category->company_id = $company->id;
        $category->type_name = 'Earned';
        $category->color = 'info';
        $category->save();

        $leaveTypes = LeaveType::where('company_id', $company->id)->get();
        $employees = EmployeeDetails::where('company_id', $company->id)->get();

        foreach ($employees as $key => $employee) {
            foreach ($leaveTypes as $key => $value) {
                EmployeeLeaveQuota::create(
                    [
                        'company_id' => $company->id,
                        'user_id' => $employee->user_id,
                        'leave_type_id' => $value->id,
                        'no_of_leaves' => $value->no_of_leaves
                    ]
                );
            }
        }
    }

    public function addEmailNotificationSettings($company)
    {
        // When new expense added by member
        \App\EmailNotificationSetting::create([
            'setting_name' => 'New Expense/Added by Admin',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);

        // When new expense added by member
        \App\EmailNotificationSetting::create([
            'setting_name' => 'New Expense/Added by Member',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);

        // When expense status changed
        \App\EmailNotificationSetting::create([
            'setting_name' => 'Expense Status Changed',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);

        // New Support Ticket Request
        \App\EmailNotificationSetting::create([
            'setting_name' => 'New Support Ticket Request',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);

        // When new user registers or added by admin
        \App\EmailNotificationSetting::create([
            'setting_name' => 'User Registration/Added by Admin',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);

        // When employee is added to project
        \App\EmailNotificationSetting::create([
            'setting_name' => 'Employee Assign to Project',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);

        // When notice published by admin
        \App\EmailNotificationSetting::create([
            'setting_name' => 'New Notice Published',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);

        // When user is assigned to a task
        \App\EmailNotificationSetting::create([
            'setting_name' => 'User Assign to Task',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);

        // When new leave application added
        \App\EmailNotificationSetting::create([
            'setting_name' => 'New Leave Application',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);

        // When task completed
        \App\EmailNotificationSetting::create([
            'setting_name' => 'Task Completed',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);

        // When task completed
        \App\EmailNotificationSetting::create([
            'setting_name' => 'Invoice Create/Update Notification',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);

        // When task completed
        \App\EmailNotificationSetting::create([
            'setting_name' => 'Payment Create/Update Notification',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);

        // New 
        \App\EmailNotificationSetting::create([
            'setting_name' => 'Discussion Reply',
            'send_push' => 'yes',
            'send_email' => 'yes',
            'company_id' => $company->id
        ]);

        // When new expense added by admin
        \App\EmailNotificationSetting::create([
            'setting_name' => 'New Project/Added by Admin',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);

        // When new expense added by member
        \App\EmailNotificationSetting::create([
            'setting_name' => 'New Project/Added by Member',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);

        // When expense status changed
        \App\EmailNotificationSetting::create([
            'setting_name' => 'Project File Added',
            'send_email' => 'yes',
            'send_push' => 'yes',
            'company_id' => $company->id
        ]);
        // When new lead added by admin
        \App\EmailNotificationSetting::create([
            'setting_name' => 'Lead notification',
            'send_email' => 'yes',
            'company_id' => $company->id
        ]);
            
    }

    /**
     * @param $company
     */
    public function addDashboardWidget($company)
    {
        // When new widget added
        \App\DashboardWidget::create([
            'widget_name' => 'total_clients',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'total_employees',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'total_projects',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'total_unpaid_invoices',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'total_hours_logged',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'total_pending_tasks',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'total_today_attendance',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'total_unresolved_tickets',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'total_resolved_tickets',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'recent_earnings',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'settings_leaves',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'new_tickets',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'overdue_tasks',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'completed_tasks',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

//        \App\DashboardWidget::create([
//            'widget_name' => 'client_feedbacks',
//            'status' => 1,
//           'company_id' => $company->id,
//            'dashboard_type' => 'admin-dashboard'
//        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'pending_follow_up',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'project_activity_timeline',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        \App\DashboardWidget::create([
            'widget_name' => 'user_activity_timeline',
            'status' => 1,
           'company_id' => $company->id,
            'dashboard_type' => 'admin-dashboard'
        ]);

        $widgets = [
            ['widget_name' => 'total_clients', 'status' => 1,'company_id' => $company->id, 'dashboard_type' => 'admin-client-dashboard'],
            ['widget_name' => 'total_leads', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-client-dashboard'],
            ['widget_name' => 'total_lead_conversions', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-client-dashboard'],
            ['widget_name' => 'total_contracts_generated', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-client-dashboard'],
            ['widget_name' => 'total_contracts_signed', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-client-dashboard'],
            ['widget_name' => 'client_wise_earnings', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-client-dashboard'],
            ['widget_name' => 'client_wise_timelogs', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-client-dashboard'],
            ['widget_name' => 'lead_vs_status', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-client-dashboard'],
            ['widget_name' => 'lead_vs_source', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-client-dashboard'],
            ['widget_name' => 'latest_client', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-client-dashboard'],
            ['widget_name' => 'recent_login_activities', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-client-dashboard'],

            ['widget_name' => 'total_paid_invoices', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'total_expenses', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'total_earnings', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'total_profit', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'total_pending_amount', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'invoice_overview', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'estimate_overview', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'proposal_overview', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'invoice_tab', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'estimate_tab', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'expense_tab', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'payment_tab', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'due_payments_tab', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'proposal_tab', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'earnings_by_client', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],
            ['widget_name' => 'earnings_by_projects', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-finance-dashboard'],

            ['widget_name' => 'total_leaves_approved', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-hr-dashboard'],
            ['widget_name' => 'total_new_employee', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-hr-dashboard'],
            ['widget_name' => 'total_employee_exits', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-hr-dashboard'],
            ['widget_name' => 'average_attendance', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-hr-dashboard'],
            ['widget_name' => 'department_wise_employee', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-hr-dashboard'],
            ['widget_name' => 'designation_wise_employee', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-hr-dashboard'],
            ['widget_name' => 'gender_wise_employee', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-hr-dashboard'],
            ['widget_name' => 'role_wise_employee', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-hr-dashboard'],
            ['widget_name' => 'leaves_taken', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-hr-dashboard'],
            ['widget_name' => 'late_attendance_mark', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-hr-dashboard'],

            ['widget_name' => 'total_project', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-project-dashboard'],
            ['widget_name' => 'total_hours_logged', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-project-dashboard'],
            ['widget_name' => 'total_overdue_project', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-project-dashboard'],
            ['widget_name' => 'status_wise_project', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-project-dashboard'],
            ['widget_name' => 'pending_milestone', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-project-dashboard'],

            ['widget_name' => 'total_unresolved_tickets', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-ticket-dashboard'],
            ['widget_name' => 'total_unassigned_ticket', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-ticket-dashboard'],
            ['widget_name' => 'type_wise_ticket', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-ticket-dashboard'],
            ['widget_name' => 'status_wise_ticket', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-ticket-dashboard'],
            ['widget_name' => 'channel_wise_ticket', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-ticket-dashboard'],
            ['widget_name' => 'new_tickets', 'status' => 1, 'company_id' => $company->id, 'dashboard_type' => 'admin-ticket-dashboard'],
        ];

        foreach ($widgets as $widget) {
            DashboardWidget::create($widget);
        }

    }

    public function addDefaultCurrencies($company)
    {
        $globalCurrencies = GlobalCurrency::all();
        $globalSetting = GlobalSetting::first();

        foreach ($globalCurrencies as $globalCurrency) {
            $currency = new Currency();
            $currency->company_id = $company->id;
            $currency->currency_name = $globalCurrency->currency_name;
            $currency->currency_symbol = $globalCurrency->currency_symbol;
            $currency->currency_code = $globalCurrency->currency_code;
            $currency->currency_position = $globalCurrency->currency_position;
            $currency->save();

            if ($globalSetting->currency_id == $globalCurrency->id) {
                $company->currency_id = $currency->id;
                $company->save();
            }
        }
    }

    public function addDefaultThemeSettings($company)
    {
        $theme = new ThemeSetting();
        $theme->company_id = $company->id;
        $theme->panel = "admin";
        $theme->header_color = "#ed4040";
        $theme->sidebar_color = "#292929";
        $theme->sidebar_text_color = "#cbcbcb";
        $theme->save();

        // project admin panel
        $theme = new ThemeSetting();
        $theme->company_id = $company->id;
        $theme->panel = "project_admin";
        $theme->header_color = "#5475ed";
        $theme->sidebar_color = "#292929";
        $theme->sidebar_text_color = "#cbcbcb";
        $theme->save();

        // employee panel
        $theme = new ThemeSetting();
        $theme->company_id = $company->id;
        $theme->panel = "employee";
        $theme->header_color = "#f7c80c";
        $theme->sidebar_color = "#292929";
        $theme->sidebar_text_color = "#cbcbcb";
        $theme->save();

        // client panel
        $theme = new ThemeSetting();
        $theme->company_id = $company->id;
        $theme->panel = "client";
        $theme->header_color = "#00c292";
        $theme->sidebar_color = "#292929";
        $theme->sidebar_text_color = "#cbcbcb";
        $theme->save();
    }

    public function addPaymentGatewaySettings($company)
    {
        $credential = new \App\PaymentGatewayCredentials();
        $credential->company_id = $company->id;
        $credential->paypal_client_id = null;
        $credential->paypal_secret = null;
        $credential->save();
    }

    public function addInvoiceSettings($company)
    {
        $invoice = new \App\InvoiceSetting();
        $invoice->company_id = $company->id;
        $invoice->invoice_prefix = 'INV';
        $invoice->template = 'invoice-1';
        $invoice->due_after = 15;
        $invoice->invoice_terms = 'Thank you for your business. Please process this invoice within the due date.';
        $invoice->save();
    }

    public function addSlackSettings($company)
    {
        $slack = new \App\SlackSetting();
        $slack->company_id = $company->id;
        $slack->slack_webhook = null;
        $slack->slack_logo = null;
        $slack->save();
    }

    public function addProjectSettings($company)
    {
        $project_setting = new ProjectSetting();

        $project_setting->company_id = $company->id;
        $project_setting->send_reminder = 'no';
        $project_setting->remind_time = 5;
        $project_setting->remind_type = 'days';

        $project_setting->save();
    }

    public function addAttendanceSettings($company)
    {
        $attendance = new \App\AttendanceSetting();
        $attendance->company_id = $company->id;
        $attendance->office_start_time = '09:00:00';
        $attendance->office_end_time = '18:00:00';
        $attendance->late_mark_duration = '20';
        $attendance->save();
    }

    public function addCustomFieldGroup($company)
    {
        \DB::table('custom_field_groups')->insert([
            'name' => 'Client',
            'model' => 'App\ClientDetails',
            'company_id' => $company->id
        ]);

        \DB::table('custom_field_groups')->insert([
            'name' => 'Employee',
            'model' => 'App\EmployeeDetails',
            'company_id' => $company->id
        ]);

        \DB::table('custom_field_groups')->insert([
            'name' => 'Project',
            'model' => 'App\Project',
            'company_id' => $company->id
        ]);

        \DB::table('custom_field_groups')->insert(
            [
                'company_id' => $company->id, 'name' => 'Invoice', 'model' => 'App\Invoice',
            ]
        );
        \DB::table('custom_field_groups')->insert(
            [
                'company_id' => $company->id, 'name' => 'Estimate', 'model' => 'App\Estimate',
            ]
        );
        \DB::table('custom_field_groups')->insert(
            [
                'company_id' => $company->id, 'name' => 'Task', 'model' => 'App\Task',
            ]
        );
        \DB::table('custom_field_groups')->insert(
            [
                'company_id' => $company->id, 'name' => 'Expense', 'model' => 'App\Expense',
            ]
        );
        \DB::table('custom_field_groups')->insert(
            [
                'company_id' => $company->id, 'name' => 'Lead', 'model' => 'App\Lead',
            ]
        );
    }

    public function addRoles($company)
    {
        $admin = new Role();
        $admin->company_id = $company->id;
        $admin->name = 'admin';
        $admin->display_name = 'App Administrator'; // optional
        $admin->description = 'Admin is allowed to manage everything of the app.'; // optional
        $admin->save();

        $employee = new Role();
        $employee->company_id = $company->id;
        $employee->name = 'employee';
        $employee->display_name = 'Employee'; // optional
        $employee->description = 'Employee can see tasks and projects assigned to him.'; // optional
        $employee->save();

        $client = new Role();
        $client->company_id = $company->id;
        $client->name = 'client';
        $client->display_name = 'Client'; // optional
        $client->description = 'Client can see own tasks and projects.'; // optional
        $client->save();
    }

    public function addMessageSetting($company)
    {
        $setting = new MessageSetting();
        $setting->company_id = $company->id;
        $setting->allow_client_admin = 'yes';
        $setting->allow_client_employee = 'yes';
        $setting->save();
    }


    public function addLogTImeForSetting($company)
    {
        $storage = new LogTimeFor();
        $storage->company_id = $company->id;
        $storage->log_time_for = 'project';
        $storage->save();
    }

    public function addLeadSourceAndLeadStatus($company)
    {
        $sources = [
            ['type' => 'email', 'company_id' => $company->id],
            ['type' => 'google', 'company_id' => $company->id],
            ['type' => 'facebook', 'company_id' => $company->id],
            ['type' => 'friend', 'company_id' => $company->id],
            ['type' => 'direct visit', 'company_id' => $company->id],
            ['type' => 'tv ad', 'company_id' => $company->id]
        ];

        LeadSource::insert($sources);

        $status = [
            ['type' => 'pending', 'company_id' => $company->id, 'default' => 1, 'priority' => 1],
            ['type' => 'inprocess', 'company_id' => $company->id, 'default' => 0, 'priority' => 2],
            ['type' => 'converted', 'company_id' => $company->id, 'default' => 0, 'priority' => 3]
        ];

        LeadStatus::insert($status);
    }

    public function addProjectCategory($company)
    {
        $category = new \App\ProjectCategory();
        $category->category_name = 'Laravel';
        $category->company_id = $company->id;
        $category->save();

        $category = new \App\ProjectCategory();
        $category->category_name = 'Java';
        $category->company_id = $company->id;
        $category->save();
    }

    private function insertGDPR($company)
    {
        $gdpr = new GdprSetting();
        $gdpr->company_id = $company->id;
        $gdpr->save();
    }

    public function addLeadCustomForm($company)
    {
        $fields = ['Name', 'Email', 'Company Name', 'Website', 'Address', 'Mobile', 'Message'];
        $fieldsName = ['name', 'email', 'company_name', 'website', 'address', 'mobile', 'message'];
        $required = [1, 1, 0, 0, 0, 0, 0];

        foreach ($fields as $key => $value) {
            LeadCustomForm::create([
                'field_display_name' => $value,
                'field_name' => $fieldsName[$key],
                'field_order' => $key+1,
                'company_id' => $company->id,
                'required' => $required[$key]
            ]);
        }
    }

    private function insertTicketFormField(Company $company)
    {
        $fields = ['Name','Email','Ticket Subject', 'Ticket Description', 'Type', 'Priority'];
        $fieldsName = ['name','email', 'ticket_subject', 'ticket_description', 'type', 'priority'];
        $fieldsType = ['text','text', 'text', 'textarea', 'select', 'select'];
        $required = [1, 1, 1, 1, 0, 0];

        foreach ($fields as $key => $value) {

            TicketCustomForm::create([
                'field_display_name' => $value,
                'field_name' => $fieldsName[$key],
                'field_order' => $key+1,
                'field_type' => $fieldsType[$key],
                'company_id' => $company->id,
                'required' => $required[$key]
            ]);

        }
    }

}
