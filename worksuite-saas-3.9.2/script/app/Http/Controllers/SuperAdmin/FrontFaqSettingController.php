<?php

namespace App\Http\Controllers\SuperAdmin;

use App\FrontClients;
use App\FrontDetail;
use App\FrontFaq;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\Feature\UpdateTitleRequest;
use App\Http\Requests\SuperAdmin\FrontFaqSetting\StoreRequest;
use App\Http\Requests\SuperAdmin\FrontFaqSetting\UpdateRequest;
use App\LanguageSetting;
use App\TrFrontDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FrontFaqSettingController extends SuperAdminBaseController
{
    /**
     * SuperAdminInvoiceController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'Front Faq Settings';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->frontFaqs    = FrontFaq::with('language:id,language_name')->get();
        $this->frontDetail  = TrFrontDetail::first();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.front-faq-settings.index', $this->data);
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

        return view('super-admin.front-faq-settings.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $frontFaq = new FrontFaq();

        $frontFaq->language_setting_id = $request->language == 0 ? null : $request->language;
        $frontFaq->question = $request->question;
        $frontFaq->answer   = $request->answer;
        $frontFaq->save();

        return Reply::redirect(route('super-admin.faq-settings.index'), 'messages.frontFaq.addedSuccess');

    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->frontFaq = FrontFaq::findOrFail($id);
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.front-faq-settings.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $frontFaq = FrontFaq::findOrFail($id);

        $frontFaq->language_setting_id = $request->language == 0 ? null : $request->language;
        $frontFaq->question = $request->question;
        $frontFaq->answer   = $request->answer;
        $frontFaq->save();

        return Reply::redirect(route('super-admin.faq-settings.index'), 'messages.frontFaq.updatedSuccess');

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
        $headerData = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();

        if (empty($headerData)) {
            $view = view('super-admin.front-faq-settings.new-form', ['languageId' => $request->language_settings_id])->render();
        } else {
            $view = view('super-admin.front-faq-settings.edit-form', ['frontDetail' => $headerData])->render();
        }
        
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * @param UpdateTitleRequest $request
     * @return array
     */
    public function updateTitles(UpdateTitleRequest $request)
    {
        $frontClients = TrFrontDetail::where('language_setting_id', $request->language_setting_id == 0 ? null : $request->language_setting_id)->first();

        $data = [
            'faq_title' => $request->title,
            'faq_detail' => $request->detail,
            'language_setting_id' => $request->language_setting_id,
        ];

        if (!is_null($frontClients)) {
            $frontClients->update($data);
        } else {
            TrFrontDetail::create($data);
        }

        return Reply::success('messages.updatedSuccessfully');
    }
}
