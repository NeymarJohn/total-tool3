<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Company;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\Packages\DeleteRequest;
use App\Http\Requests\SuperAdmin\Packages\StoreRequest;
use App\Http\Requests\SuperAdmin\Packages\UpdateRequest;
use App\Module;
use App\ModuleSetting;
use App\Package;
use Yajra\DataTables\Facades\DataTables;


class SuperAdminPackageController extends SuperAdminBaseController
{
    /**
     * AdminProductController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.packages';
        $this->pageIcon = 'icon-basket';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->totalPackages = Package::where('default', '!=', 'trial')->count();
        return view('super-admin.packages.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->modules = Module::all();
        $this->position = Package::count();
        return view('super-admin.packages.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        if ($request->has('is_recommended') && $request->is_recommended == 'on') {
            Package::where('is_recommended', 1)->update(['is_recommended' => 1]);
        }
        $data = $this->modifyRequest($request);
        Package::create($data);

        return Reply::redirect(route('super-admin.packages.index'), 'Package successfully added.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->package = Package::find($id);
        $this->modules = Module::all();

        return view('super-admin.packages.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function update(UpdateRequest $request, $id)
    {
        if ($request->has('is_recommended') && $request->is_recommended == 'on') {
            Package::where('is_recommended', 1)->update(['is_recommended' => 1]);
        }

        $package = Package::with('companies')->find($id);
        $data = $this->modifyRequest($request);
        $package->update($data);

        ModuleSetting::whereNull('company_id')->delete();

        if ($request->has('module_in_package')) {
            $moduleInPackage = (array)json_decode($package->module_in_package);

            foreach ($package->companies as $company) {
                $this->packageModify($moduleInPackage, $company);
            }
        }

        return Reply::redirect(route('super-admin.packages.index'), 'Package updated successfully.');
    }

    private function packageModify($moduleInPackage, $company)
    {
        ModuleSetting::where('company_id', $company->id)->delete();
        $clientModules = ['projects', 'tickets', 'invoices', 'estimates', 'events', 'tasks', 'messages', 'payments', 'contracts', 'notices', 'products'];
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
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request, $id)
    {
        $companies = Company::where('package_id', $id)->get();
        if ($companies) {
            $defaultPackage = Package::where('default', 'yes')->first();
            if ($defaultPackage) {
                foreach ($companies as $company) {
                    ModuleSetting::where('company_id', $company->id)->delete();

                    $moduleInPackage = (array) json_decode($defaultPackage->module_in_package);
                    $clientModules = ['projects', 'tickets', 'invoices', 'estimates', 'events', 'tasks', 'messages', 'payments', 'contracts', 'notices', 'products'];
                    if ($moduleInPackage) {
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
                    $company->package_id = $defaultPackage->id;
                    $company->save();
                }
            }
        }

        Package::destroy($id);
        return Reply::success('Package deleted successfully.');
    }

    /**
     * @return mixed
     */
    public function data()
    {
        $packages = Package::where('default', '!=', 'trial')->get();
        return Datatables::of($packages)
            ->addColumn('action', function ($row) {
                $action = '';
                if ($row->default == 'no') {
                    $action = ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }

                return '<a href="' . route('super-admin.packages.edit', [$row->id]) . '" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>' . $action;
            })
            ->editColumn('name', function ($row) {
                $name = ucfirst($row->name);
                if ($row->is_private) {
                    $name .= ' <i data-toggle="tooltip" data-original-title="' . __('app.private') . '" class="fa fa-lock" style="color: #ea4c89"></i>';
                }
                if ($row->is_recommended) {
                    $name .= ' <label class="label label-success inline-block" style="display: inline-block" >Recommended</label>';
                }
                return $name;
            })
            ->addColumn('fileStorage', function ($row) {
                if ($row->max_storage_size == -1) {
                    return __('app.unlimited');
                }
                return $row->max_storage_size . ' (' . strtoupper($row->storage_unit) . ')';
            })
            ->editColumn('sort', function ($row) {
                return $row->sort;
            })
            ->editColumn('module_in_package', function ($row) {
                $modules = json_decode($row->module_in_package,true);

                if(!$modules){
                    return 'No module selected';
                }
                $moduleArrayList = Module::all();
                $string = '';
                foreach ($moduleArrayList as $module) {
                    $sign = in_array($module->module_name,$modules) ?('<i class="fa fa-check"></i>'):('<i class="fa fa-times"></i>');
                    $string .= '<li>'.$sign.' ' . __('modules.module.' . $module->module_name) . '</li>';
                }

                $string ='<ul>'.$string. '<ul>';
                return $string;
            })

            ->rawColumns(['action', 'module_in_package', 'name'])
            ->make(true);
    }

    private function modifyRequest($request)
    {
        $data = $request->all();
        $data['module_in_package'] = json_encode($request->module_in_package);
        $data['is_private'] = $request->has('is_private') && $request->is_private == 'true' ? 1 : 0;
        $data['is_recommended'] = $request->has('is_recommended') && $request->is_recommended == 'on' ? 1 : 0;
        $data['is_free'] = $request->has('is_free') && $request->is_free == 'true' ? 1 : 0;
        $data['is_auto_renew'] = $request->has('is_auto_renew') && $request->is_auto_renew == 'true' ? 1 : 0;
        $data['sort'] = $request->sort;

        if ($request->has('is_free') && $request->is_free == 'true') {
            $data['monthly_price'] = 0;
            $data['annual_price'] = 0;
        }
        return $data;
    }
}
