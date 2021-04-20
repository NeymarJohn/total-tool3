<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Company;
use App\GlobalSetting;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\FrontSetting\UpdateContactSettings;
use App\Module;
use App\ModuleSetting;
use App\Package;
use App\PackageSetting;
use Illuminate\Http\Request;

class SuperAdminPackageSettingController extends SuperAdminBaseController
{
    /**
     * SuperAdminInvoiceController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle =  __('app.package').' Settings';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->global = GlobalSetting::first();
        $this->packageSetting = PackageSetting::first();
        $this->package = Package::where('default', 'trial')->first();
        $this->modules = Module::all();

        return view('super-admin.package-settings.index', $this->data);
    }

    /**
     * @param UpdateContactSettings $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        $setting = PackageSetting::findOrFail($id);

        $setting->no_of_days = $request->input('no_of_days');
        $setting->status = ($request->has('status')) ? 'active' : 'inactive';
        $setting->trial_message = $request->trial_message;
        $setting->modules = json_encode($request->module_in_package);
        $setting->notification_before = $request->notification_before;
        $setting->save();

        $package = Package::where('default', 'trial')->first();
        if($package){
            $package->module_in_package = $setting->modules;
            $package->name = $request->input('name');
            $package->max_employees = $request->input('max_employees');
            $package->max_storage_size = $request->max_storage_size;
            $package->storage_unit = $request->storage_unit;
            $package->currency_id = $this->global->currency_id;
            $package->save();
        }

        if($request->has('module_in_package') && !is_null($package)){
            $companies = Company::where('package_id', $package->id)->get();

            foreach($companies as $company){
                ModuleSetting::where('company_id', $company->id)->delete();

                $moduleInPackage = (array)json_decode($package->module_in_package);
                $clientModules = ['projects', 'tickets', 'invoices', 'estimates', 'events', 'tasks', 'messages', 'payments', 'contracts', 'notices'];
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
        }

        return Reply::success(__('messages.uploadSuccess'));

    }
}
