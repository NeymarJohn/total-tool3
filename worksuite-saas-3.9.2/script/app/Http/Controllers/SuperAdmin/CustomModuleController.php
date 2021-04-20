<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\Reply;
use App\Traits\ModuleVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Nwidart\Modules\Facades\Module;
use Zip;

class CustomModuleController extends SuperAdminBaseController
{
    use ModuleVerify;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.moduleSettings';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->type = 'custom';
        $this->updateFilePath = config('froiden_envato.tmp_path');
        $this->allModules = Module::all();
        $plugins = \Froiden\Envato\Functions\EnvatoUpdate::plugins();
        $version = [];
        foreach ($plugins as $key => $value) {
            $version[$value['envato_id']] = $value['version'];
        }
        $this->version = $version;

        return view('super-admin.custom-modules.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->type = 'custom';
        $this->updateFilePath = config('froiden_envato.tmp_path');
        return view('super-admin.custom-modules.install', $this->data);
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function store(Request $request)
    {
        File::put(public_path() . '/install-version.txt', 'complete');

        $filePath = $request->filePath;
        $zip = Zip::open($filePath);
        $array = explode('/', $filePath);
        $zipName  = end($array);
        $moduleName = str_replace('.zip', '', $zipName);

        // Extract the files to storage folder first for checking the right plugin
        $zip->extract(storage_path('app') . '/Modules');

        if ($this->validateModule($moduleName)) {
            // Move files to Modules if modules belongs to this product
            File::moveDirectory(storage_path('app') . '/Modules/' . $moduleName, base_path() . '/Modules/' . $moduleName, true);

            $this->flushData();
            return Reply::success('Installed successfully.');
        }
        return Reply::error('The zip that you are trying to install doesn\'t belongs to this product');
    }

    public function validateModule($moduleName)
    {
        if (file_exists(storage_path('app') . '/Modules/' . $moduleName . '/Config/config.php')) {
            $config = require_once storage_path('app') . '/Modules/' . $moduleName . '/Config/config.php';
            if (isset($config['parent_envato_id']) && ($config['parent_envato_id'] == config('froiden_envato.envato_item_id'))) {
                return true;
            }
        }

        return false;
    }

    private function flushData()
    {
        Artisan::call('optimize:clear');
        Artisan::call('module:migrate');

        session()->forget('check_migrate_status');

        Session::flush();
        Auth::logout();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->verifyModulePurchase($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $module = Module::find($id);

        if ($request->status == 'active') {
            $module->enable();
        } else {
            $module->disable();
        }

        $plugins = \Nwidart\Modules\Facades\Module::allEnabled();

        foreach ($plugins as $plugin) {
            Artisan::call('module:migrate', array($plugin, '--force' => true));
        }

        session()->forget('check_migrate_status');
        session(['worksuite_plugins' => array_keys($plugins)]);
        return Reply::redirect(route('super-admin.custom-modules.index'), 'Status Changed. Reloading');
    }

    public function verifyingModulePurchase(Request $request)
    {
        $request->validate([
            'purchase_code' => 'required|max:80',
        ]);

        $module = $request->module;
        $purchaseCode = $request->purchase_code;
        return $this->modulePurchaseVerified($module, $purchaseCode);
    }
}
