<?php

namespace App\Console\Commands;

use App\Company;
use App\Currency;
use App\GlobalSetting;
use App\ModuleSetting;
use App\Notifications\LicenseExpire;
use App\Notifications\LicenseExpirePre;
use App\Notifications\TaskCompleted;
use App\Package;
use App\PackageSetting;
use App\Setting;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class LicenceExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'licence-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set licence expire status of companies in companies table.';

    /**
     * Create a new command instance.
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
        $packageSettingData = PackageSetting::first();
        $companies = Company::with('package')->where('status', 'active')
            ->whereNotNull('licence_expire_on')
            ->whereRaw('`licence_expire_on` < ?', [Carbon::now()->format('Y-m-d')])
            ->get();

        $packageSetting = ($packageSettingData->status == 'active') ? $packageSettingData : null;
        $packages = Package::all();

        $trialPackage = $packages->filter(function ($value, $key) {
            return $value->default == 'trial';
        })->first();

        $defaultPackage = $packages->filter(function ($value, $key) {
            return $value->default == 'yes';
        })->first();

        $otherPackage = $packages->filter(function ($value, $key) {
            return $value->default == 'no';
        })->first();

        if($packageSetting && !is_null($trialPackage)){
            $selectPackage = $trialPackage;
        }
        elseif($defaultPackage)
            $selectPackage = $defaultPackage;
        else {
            $selectPackage = $otherPackage;
        }

        // Set default package for license expired companies.
        if($companies){

            if($selectPackage){
                foreach($companies as $company){
                    $currentPackage = $company->package;
                    if($company->package->is_free == 0){
                        ModuleSetting::where('company_id', $company->id)->delete();

                        $moduleInPackage = (array)json_decode($selectPackage->module_in_package);
                        $clientModules = ['projects', 'tickets', 'invoices', 'estimates', 'events', 'tasks', 'messages', 'payments', 'contracts', 'notices'];
                        if($moduleInPackage){
                            foreach ($moduleInPackage as $module) {

                                if(in_array($module, $clientModules)){
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

                        if($currentPackage->default == 'trial' && !is_null($packageSetting) && !is_null($defaultPackage)){
                            $company->package_id = $defaultPackage->id;
                            $company->licence_expire_on = null;
                        }
                        elseif($packageSetting && !is_null($trialPackage)){
                            $company->package_id = $selectPackage->id;
                            $noOfDays = (!is_null($packageSetting->no_of_days) && $packageSetting->no_of_days != 0) ? $packageSetting->no_of_days : 30;
                            $company->licence_expire_on = Carbon::now()->addDays($noOfDays)->format('Y-m-d');
                        }elseif(is_null($packageSetting) && !is_null($defaultPackage)){
                            $company->package_id = $defaultPackage->id;
                            $company->licence_expire_on = null;
                        }
                        $company->status = 'license_expired';
                        $company->save();

                        if($company->company_email){
                            $companyUser = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                                ->where('users.company_id', $company->id)
                                ->where('roles.name', 'admin')->first();

                            $companyUser->notify(new LicenseExpire(($companyUser)));

                        }
                    }

                }
            }
        }

        if (!is_null($packageSettingData) && !is_null($packageSettingData->notification_before))
        {
            $companiesNotify = Company::with('package')
                ->where('status', 'active')
                ->whereNotNull('licence_expire_on')
                ->whereRaw('`licence_expire_on` = ?', [Carbon::now()->addDays($packageSettingData->notification_before)->format('Y-m-d')])
                ->get();

            foreach($companiesNotify as $cmp){
                if($cmp->package->is_free == 0) {
                    $companyUser = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->where('users.company_id', $cmp->id)
                        ->where('roles.name', 'admin')->first();
                    if ($companyUser->email) {
                        $companyUser->notify(new LicenseExpirePre(($companyUser)));
                    }
                }
            }
        }
    }
}
