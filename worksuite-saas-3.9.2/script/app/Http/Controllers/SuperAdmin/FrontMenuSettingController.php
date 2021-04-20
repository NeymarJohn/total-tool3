<?php

namespace App\Http\Controllers\SuperAdmin;

use App\FrontClients;
use App\FrontDetail;
use App\FrontFaq;
use App\FrontMenu;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\Feature\UpdateTitleRequest;
use App\Http\Requests\SuperAdmin\FrontMenuSetting\UpdateRequest;
use App\LanguageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FrontMenuSettingController extends SuperAdminBaseController
{
    /**
     * SuperAdminInvoiceController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'Front Menu Settings';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->frontMenu    = FrontMenu::first();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.front-menu-settings.index', $this->data);
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


    public function store()
    {
        //

    }


    public function edit($id)
    {
        //
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $frontMenu = FrontMenu::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();

        $frontMenu->home           = $request->home;
        $frontMenu->price          = $request->price;
        $frontMenu->contact        = $request->contact;
        $frontMenu->feature        = $request->feature;
        $frontMenu->get_start      = $request->get_start;
        $frontMenu->login          = $request->login;
        $frontMenu->contact_submit = $request->contact_submit;
        $frontMenu->save();

        return Reply::success('messages.updatedSuccessfully');
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function destroy(Request $request, $id)
    {
        FrontFaq::destroy($id);
        return Reply::redirect(route('super-admin.faq-settings.index'), 'messages.frontFaq.deletedSuccess');
    }

    public function changeForm(Request $request)
    {
        $headerData = FrontMenu::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();

        if (empty($headerData)) {
            $view = view('super-admin.front-menu-settings.new-form', ['languageId' => $request->language_settings_id])->render();
        } else {
            $view = view('super-admin.front-menu-settings.edit-form', ['frontMenu' => $headerData])->render();
        }
        
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * @param UpdateTitleRequest $request
     * @return array
     */
    public function updateTitles(UpdateRequest $request)
    {
        $frontMenu = FrontMenu::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();
        
        $data = [
            'language_setting_id'   => $request->language_settings_id,
            'home'                  => $request->home,
            'price'                 => $request->price,
            'contact'               => $request->contact,
            'feature'               => $request->feature,
            'get_start'             => $request->get_start,
            'login'                 => $request->login,
            'contact_submit'        => $request->contact_submit,
        ];

        if (!is_null($frontMenu)) {
            $frontMenu->update($data);
        } else {
            FrontMenu::create($data);
        }

        return Reply::success('messages.updatedSuccessfully');

    }

}
