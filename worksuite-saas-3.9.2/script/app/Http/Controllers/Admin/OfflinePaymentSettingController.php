<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\OfflinePaymentSetting\StoreRequest;
use App\Http\Requests\OfflinePaymentSetting\UpdateRequest;
use App\OfflinePaymentMethod;


class OfflinePaymentSettingController extends AdminBaseController
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
        $this->offlineMethods = OfflinePaymentMethod::all();
        return view('admin.payment-gateway-credentials.offline-method.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.payment-gateway-credentials.offline-method.create-modal', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $method = new OfflinePaymentMethod();
        $method->name = $request->name;
        $method->description = $request->description;
        $method->save();

        return Reply::redirect(route('admin.offline-payment-setting.index'), __('messages.methodsAdded'));
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

        return view('admin.payment-gateway-credentials.offline-method.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $method = OfflinePaymentMethod::findOrFail($id);
        $method->name = $request->name;
        $method->description = $request->description;
        $method->status = $request->status;
        $method->save();

        return Reply::redirect(route('admin.offline-payment-setting.index'), __('messages.methodsUpdated'));

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

        return Reply::redirect(route('admin.offline-payment-setting.index'), __('messages.methodsDeleted'));

    }

    public function createModal(){
        return view('admin.payment-gateway-credentials.offline-method.create-modal');
    }
}
