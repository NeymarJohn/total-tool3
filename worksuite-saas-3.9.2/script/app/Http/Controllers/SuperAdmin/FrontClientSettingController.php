<?php

namespace App\Http\Controllers\SuperAdmin;

use App\FrontClients;
use App\FrontDetail;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\Feature\UpdateTitleRequest;
use App\Http\Requests\SuperAdmin\FrontClientSetting\StoreRequest;
use App\Http\Requests\SuperAdmin\FrontClientSetting\UpdateRequest;
use App\LanguageSetting;
use App\TrFrontDetail;
use Illuminate\Http\Request;

class FrontClientSettingController extends SuperAdminBaseController
{
    /**
     * SuperAdminInvoiceController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Front Client Settings';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->frontClients = FrontClients::with('language:id,language_name')->get();
        $this->frontDetail = TrFrontDetail::first();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.front-client-settings.index', $this->data);
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->frontClients = FrontClients::all();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.front-client-settings.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $frontClients = new FrontClients();

        $frontClients->language_setting_id = $request->language == 0 ? null : $request->language;
        $frontClients->title = $request->title;
        if ($request->hasFile('image')) {
            $frontClients->image = Files::upload($request->image, 'front/client');
        }

        $frontClients->save();

        return Reply::redirect(route('super-admin.client-settings.index'), 'messages.testimonial.addedSuccess');

    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->frontClient = FrontClients::findOrFail($id);
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.front-client-settings.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function update(UpdateRequest $request, $id)
    {
        $frontClients = FrontClients::findOrFail($id);

        $frontClients->language_setting_id = $request->language == 0 ? null : $request->language;
        $frontClients->title = $request->title;

        if ($request->hasFile('image')) {
            Files::deleteFile($frontClients->image, 'front/client');
            $frontClients->image = Files::upload($request->image, 'front/client');
        }

        $frontClients->save();

        return Reply::redirect(route('super-admin.client-settings.index'), 'messages.frontClient.addedSuccess');

    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function destroy(Request $request, $id)
    {
        FrontClients::destroy($id);
        return Reply::redirect(route('super-admin.client-settings.index'), 'messages.frontClient.deletedSuccess');
    }

    public function changeForm(Request $request)
    {
        $headerData = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();
        
        if (empty($headerData)) {
            $view = view('super-admin.front-client-settings.new-form', ['languageId' => $request->language_settings_id])->render();
        } else {
            $view = view('super-admin.front-client-settings.edit-form', ['frontDetail' => $headerData])->render();
        }
        
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * @param UpdateTitleRequest $request
     * @return array
     */
    public function updateTitles(UpdateTitleRequest $request)
    {
        $frontClients = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();

        $data = [
            'client_title' => $request->title,
            'client_detail' => $request->detail,
            'language_setting_id' => $request->language_settings_id,
        ];
        
        if (!is_null($frontClients)) {
            $frontClients->update($data);
        } else {
            TrFrontDetail::create($data);
        }

        return Reply::success(__('messages.updatedSuccessfully'));

    }

}
