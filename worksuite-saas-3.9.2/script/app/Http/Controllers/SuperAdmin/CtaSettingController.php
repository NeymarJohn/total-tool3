<?php

namespace App\Http\Controllers\SuperAdmin;

use App\FrontClients;
use App\FrontDetail;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\Feature\UpdateTitleRequest;
use App\Http\Requests\SuperAdmin\FrontClientSetting\StoreRequest;
use App\Http\Requests\SuperAdmin\FrontClientSetting\UpdateRequest;
use App\LanguageSetting;
use App\TrFrontDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CtaSettingController extends SuperAdminBaseController
{
    /**
     * SuperAdminInvoiceController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'Front CTA Settings';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->frontDetail = TrFrontDetail::first();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.cta-settings.index', $this->data);

    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       //
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {


    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
    }

    public function update(UpdateTitleRequest $request, $id)
    {
        $frontClients = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();

        $data = [
            'cta_title' => $request->title,
            'cta_detail' => $request->detail
        ];

        if (!is_null($frontClients)) {
            $frontClients->update($data);
        } else {
            TrFrontDetail::create($data);
        }

        return Reply::success('messages.updatedSuccessfully');

    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function destroy(Request $request, $id)
    {
        //
    }

    public function changeForm(Request $request)
    {
        $headerData = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();

        if (empty($headerData)) {
            $view = view('super-admin.cta-settings.new-form', ['languageId' => $request->language_settings_id])->render();
        } else {
            $view = view('super-admin.cta-settings.edit-form', ['frontDetail' => $headerData])->render();
        }
        
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function updateTitles(UpdateTitleRequest $request)
    {
        $frontClients = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();

        $data = [
            'cta_title' => $request->title,
            'cta_detail' => $request->detail
        ];

        if (!is_null($frontClients)) {
            $frontClients->update($data);
        } else {
            TrFrontDetail::create($data);
        }

        return Reply::success('messages.updatedSuccessfully');

    }
}
