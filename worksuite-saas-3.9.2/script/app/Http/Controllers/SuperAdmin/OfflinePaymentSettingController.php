<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\Reply;
use App\Http\Controllers\SuperAdmin\SuperAdminBaseController;
use App\Http\Requests\OfflinePaymentSetting\StoreRequest;
use App\Http\Requests\OfflinePaymentSetting\SuperAdminStoreRequest;
use App\Http\Requests\OfflinePaymentSetting\SuperAdminUpdateRequest;
use App\Http\Requests\OfflinePaymentSetting\UpdateRequest;
use App\OfflinePaymentMethod;
use App\Scopes\CompanyScope;


class OfflinePaymentSettingController extends SuperAdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageIcon = 'user-follow';
        $this->pageTitle = 'Offline Payment Method';

//        if(!in_array('leads',$this->user->modules)){
//            abort(403);
//        }
    }

    public function index()
    {
        $this->offlineMethods = OfflinePaymentMethod::withoutGlobalScope(CompanyScope::class)->whereNull('company_id')->get();
        return view('super-admin.payment-settings.offline-method.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super-admin.payment-settings.offline-method.create-modal', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SuperAdminStoreRequest $request)
    {
        $method = new OfflinePaymentMethod();
        $method->name = $request->name;
        $method->description = $request->description;
        $method->save();

        $allMethods = OfflinePaymentMethod::whereNull('company_id')->get();
        return Reply::successWithData( __('messages.methodsAdded'), ['data' => $allMethods]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->method = OfflinePaymentMethod::findOrFail($id);

        return view('super-admin.payment-settings.offline-method.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SuperAdminUpdateRequest $request, $id)
    {
        $method = OfflinePaymentMethod::findOrFail($id);
        $method->name = $request->name;
        $method->description = $request->description;
        $method->status = $request->status;
        $method->save();

        return Reply::redirect(route('super-admin.offline-payment-setting.index'), __('messages.methodsUpdated'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        OfflinePaymentMethod::destroy($id);
        $allMethods = OfflinePaymentMethod::whereNull('company_id')->get();
        return Reply::successWithData( __('messages.methodsDeleted'), ['data' => $allMethods]);

    }

    public function createModal(){
        return view('super-admin.payment-settings.offline-method.create-modal');
    }

    public function offlinePaymentMethod()
    {
        $this->offlineMethods = OfflinePaymentMethod::withoutGlobalScope(CompanyScope::class)->whereNull('company_id')->get();
        return view('super-admin.companies.create-modal', $this->data);
    }
}
