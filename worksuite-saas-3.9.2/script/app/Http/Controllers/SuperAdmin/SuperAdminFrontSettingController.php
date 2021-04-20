<?php

namespace App\Http\Controllers\SuperAdmin;

use App\FrontDetail;
use App\GlobalCurrency;
use App\GlobalSetting;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\ContactSetting\ContactUsSettings;
use App\Http\Requests\SuperAdmin\ContactSetting\UpdateContactUsSettings;
use App\Http\Requests\SuperAdmin\FrontSetting\UpdateDetail;
use App\Http\Requests\SuperAdmin\FrontSetting\UpdateFrontSettings;
use App\Http\Requests\SuperAdmin\PriceSetting\UpdatePriceSettings;
use App\Http\Requests\ThemeUpdate\UpdateRequest;
use App\LanguageSetting;
use App\SeoDetail;
use App\TrFrontDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class SuperAdminFrontSettingController extends SuperAdminBaseController
{
    /**
     * SuperAdminInvoiceController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'Front Settings';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->frontDetail = FrontDetail::first();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();
        $this->trFrontDetail = TrFrontDetail::whereNull('language_setting_id')->first();
        $this->currencies  = GlobalCurrency::all();
        $this->languages   = LanguageSetting::where('status', 'enabled')->get();

        if($this->global->front_design == 1) {
            return view('super-admin.front-settings.new-theme.index', $this->data);
        }
        return view('super-admin.front-settings.index', $this->data);
    }

    /**
     * @param UpdateFrontSettings $request
     * @param $id
     * @return array
     */
    public function update(UpdateFrontSettings $request, $id)
    {
        $setting = FrontDetail::findOrFail($id);

        $setting->primary_color      = $request->input('primary_color');
        $setting->get_started_show   = ($request->get_started_show == 'yes') ? 'yes' : 'no';
        $setting->sign_in_show       = ($request->sign_in_show == 'yes') ? 'yes' : 'no';
        $setting->locale             = $request->default_language;

        if($this->global->front_design == 0){
            $setting->address = $request->input('address');
            $setting->contact_html = $request->input('contact_html');
            $setting->phone = $request->input('phone');
            $setting->email = $request->input('email');
            $setting->custom_css = $request->input('custom_css');
        }

        if($this->global->front_design == 1)
        {
            $setting->custom_css_theme_two = $request->input('custom_css');
        }

        $links = [];
        foreach ($request->social_links as $name => $value) {
            $link_details=[];
            $link_details = Arr::add($link_details, 'name', $name);
            $link_details = Arr::add($link_details, 'link', $value);
            array_push($links, $link_details);
        }

        $setting->social_links = json_encode($links);

        $setting->save();

        return Reply::success(__('messages.uploadSuccess'));

    }
    public function themeSetting()
    {
        $this->global      = GlobalSetting::first();
        $this->frontDetail = FrontDetail::first();
        $this->currencies  = GlobalCurrency::all();

        return view('super-admin.front-theme-settings.index', $this->data);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function themeUpdate(UpdateRequest $request)
    {
        $global = GlobalSetting::first();
        $global->front_design = $request->input('theme');
        $global->frontend_disable = $request->has('frontend_disable') ? 1 : 0;
        $global->setup_homepage = $request->setup_homepage;
        $global->custom_homepage_url = $request->custom_homepage_url;

        if ($request->has('login_ui')) {
            $global->login_ui = $request->input('login_ui');
        }

        $global->save();
        session()->forget('global_settings');

        return Reply::redirect(route('super-admin.theme-settings'), __('messages.updateSuccess'));

    }
    public function authSetting()
    {
        $this->global      = GlobalSetting::first();
        $this->frontDetail = FrontDetail::first();
        $this->currencies  = GlobalCurrency::all();

        return view('super-admin.auth-setting.index', $this->data);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function authUpdate(Request $request)
    {
        $global = GlobalSetting::first();

        if($global->login_ui == 1 && $global->front_design == 1)
        {
            $global->auth_css_theme_two = $request->input('auth_css');
        }
        else{
            $global->auth_css = $request->input('auth_css');
        }

        $global->save();
        session()->forget('global_settings');

        return Reply::redirect(route('super-admin.auth-settings'), __('messages.updateSuccess'));

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact()
    {
        $this->frontDetail = FrontDetail::first();
        return view('super-admin.contact-settings.index', $this->data);
    }


    public function contactUpdate(ContactUsSettings $request)
    {
        $setting = FrontDetail::first();

        $setting->address = $request->input('address');
        $setting->phone   = $request->input('phone');
        $setting->email   = $request->input('email');
        $setting->contact_html   = $request->input('contact_html');
        $setting->save();

        return Reply::success(__('messages.uploadSuccess'));

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function price()
    {
        $this->frontDetail = TrFrontDetail::first();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.price-settings.index', $this->data);
    }

    /**
     * @param UpdatePriceSettings $request
     * @return array
     */
    public function priceUpdate(UpdatePriceSettings $request)
    {
        $setting = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();
        
        $data = [
            'price_title'       => $request->input('price_title'),
            'price_description' => $request->input('price_description')
        ];

        if (!is_null($setting)) {
            $setting->update($data);
        } else {
            TrFrontDetail::create($data);
        }

        return Reply::success(__('messages.uploadSuccess'));
    }

    public function changePriceForm(Request $request)
    {
        $headerData = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();

        if (empty($headerData)) {
            $view = view('super-admin.price-settings.new-form', ['languageId' => $request->language_settings_id])->render();
        } else {
            $view = view('super-admin.price-settings.edit-form', ['frontDetail' => $headerData])->render();
        }
        
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function changeForm(Request $request)
    {
        $headerData = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();
        
        if (empty($headerData)) {
            if ($this->global->front_design === 0) {
                $view = view('super-admin.front-settings.new-form', ['languageId' => $request->language_settings_id])->render();
            } else {
                $view = view('super-admin.front-settings.new-theme.new-form', ['languageId' => $request->language_settings_id])->render();
            }
        } else {
            if ($this->global->front_design === 0) {
                $view = view('super-admin.front-settings.edit-form', ['trFrontDetail' => $headerData])->render();
            } else {
                $view = view('super-admin.front-settings.new-theme.edit-form', ['trFrontDetail' => $headerData])->render();
            }
        }
        
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function updateDetail(UpdateDetail $request)
    {
        $setting = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();

        $data = [
            'language_setting_id' => $request->language_settings_id == 0 ? null : $request->language_settings_id,
            'header_title' => $request->header_title,
            'header_description' => $request->header_description,
        ];

        if ($request->hasFile('image')) {
            if (!is_null($setting)) {
                Files::deleteFile($setting->image, 'front');
            }
            $data['image'] = Files::upload($request->image, 'front');
        }

        if (!is_null($setting)) {
            $setting->update($data);
        } else {
            TrFrontDetail::create($data);
        }

        return Reply::success(__('messages.updatedSuccessfully'));
    }
}
