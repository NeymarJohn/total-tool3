<?php

namespace App\Http\Controllers\SuperAdmin;

use App\FooterMenu;
use App\FrontDetail;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\FooterSetting\FooterTextRequest;
use App\Http\Requests\SuperAdmin\FooterSetting\StoreRequest;
use App\Http\Requests\SuperAdmin\FooterSetting\UpdateRequest;
use App\LanguageSetting;
use App\SeoDetail;
use App\TrFrontDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuperAdminFooterSettingController extends SuperAdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'Menu Settings';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->footer = FooterMenu::with('language:id,language_name')->get();

        return view('super-admin.footer-settings.index', $this->data);
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->footer = FooterMenu::all();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.footer-settings.create', $this->data);
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function footerText(Request $request)
    {
        $this->frontDetail = TrFrontDetail::first();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.footer-settings.footer-text', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $videoType = $request->video_type;

        $footer = new FooterMenu();

        $footer->language_setting_id = $request->language == 0 ? null : $request->language;
        $footer->name          = $request->title;
        $footer->slug          = Str::slug($request->title);
        $footer->description   = $request->description;
        $footer->external_link = $request->external_link;
        $footer->status        = $request->status;
        $footer->type          = $request->type;
        $footer->save();

        SeoDetail::updateOrCreate(
            ['page_name' => $footer->slug], [ 
                'language_setting_id' => $footer->language_setting_id,
                'seo_title' => $request->seo_title,
                'seo_description' => $request->seo_description,
                'seo_author' => $request->seo_author
            ]
        );

        if($videoType == 'upload'){
            return Reply::dataOnly(['footer_id' => $footer->id]);
        }

        return Reply::redirect(route('super-admin.footer-settings.index'), 'messages.feature.addedSuccess');
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->footer = FooterMenu::findOrFail($id);
        $this->seoDetail = SeoDetail::where('page_name', $this->footer->slug)->first();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.footer-settings.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $videoType = $request->video_type;
        $footer = FooterMenu::findOrFail($id);

        $footer->language_setting_id = $request->language == 0 ? null : $request->language;
        $footer->name          = $request->title;
        $footer->description   = $request->description;
        $footer->external_link = $request->external_link;
        $footer->status        = $request->status;
        $footer->type          = $request->type;
        $footer->save();

        SeoDetail::updateOrCreate(
            ['page_name' => 'footer-'.$footer->slug],
            [
                'language_setting_id' => $request->language == 0 ? null : $request->language,
                'seo_title' => $request->seo_title,
                'seo_description' => $request->seo_description,
                'seo_author' => $request->seo_author
            ]
        );
        if($videoType == 'upload'){
            return Reply::dataOnly(['footer_id' => $footer->id]);
        }

        return Reply::redirect(route('super-admin.footer-settings.index'), 'messages.feature.addedSuccess');
    }

    public function changeFooterTextForm(Request $request)
    {
        $headerData = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();

        if (empty($headerData)) {
            $view = view('super-admin.footer-settings.new-footer-text-form', ['languageId' => $request->language_settings_id])->render();
        } else {
            $view = view('super-admin.footer-settings.edit-footer-text-form', ['frontDetail' => $headerData])->render();
        }
        
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * @param FooterTextRequest $request
     * @param $id
     * @return array
     */
    public function updateText(FooterTextRequest $request)
    {
        $request->language_settings_id  = $request->language_settings_id == 0 ? null : $request->language_settings_id;
        $frontClients = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();

        $data = [
            'footer_copyright_text' => $request->footer_copyright_text,
            'language_setting_id' => $request->language_settings_id
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
        $footerMenu = FooterMenu::select('id', 'language_setting_id', 'slug')->where('id', $id)->first();
        $seoDetail = SeoDetail::select('id', 'page_name')->where('page_name', $footerMenu->slug)->first();

        $seoDetail->delete();
        $footerMenu->delete();

        return Reply::redirect(route('super-admin.footer-settings.index'), 'messages.feature.deletedSuccess');
    }
}
