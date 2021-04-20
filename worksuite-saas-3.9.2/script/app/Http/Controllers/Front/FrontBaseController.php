<?php

namespace App\Http\Controllers\Front;

use App\FooterMenu;
use App\FrontDetail;
use App\FrontMenu;
use App\FrontWidget;
use App\GlobalSetting;
use App\Http\Controllers\Controller;
use App\LanguageSetting;
use App\Social;
use App\SocialAuthSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

class FrontBaseController extends Controller
{
    /**
     * @var array
     */
    public $data = [];

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * UserBaseController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        try {
            rename(public_path("front-uploads"), public_path("user-uploads/front"));
        } catch (\Exception $e) {
        }

        $this->setting = GlobalSetting::first();

        $this->languages = LanguageSetting::where('status', 'enabled')->get();
        $this->socialAuthSettings = SocialAuthSetting::first();
        $this->global = $this->setting;
        $this->frontDetail    = FrontDetail::first();

        if (Cookie::get('language')) {
            $langArray =  explode('|', decrypt(Cookie::get('language'), false));
            $this->locale = isset($langArray[1]) ? $langArray[1] : $langArray[0];
            App::setLocale($this->locale);
        } else {
            $this->locale = $this->frontDetail->locale;
            App::setLocale($this->locale);
        }

        $this->localeLanguage = LanguageSetting::where('language_code', App::getLocale())->first();

        $frontMenuCount = FrontMenu::select('id', 'language_setting_id')->where('language_setting_id', $this->localeLanguage ? $this->localeLanguage->id : null)->count();
        $footerMenuCount = FooterMenu::select('id', 'language_setting_id')->where('language_setting_id', $this->localeLanguage ? $this->localeLanguage->id : null)->count();

        Carbon::setLocale($this->locale);

        $this->footerSettings = FooterMenu::whereNotNull('slug')->where('language_setting_id', $footerMenuCount > 0 ? ( $this->localeLanguage ? $this->localeLanguage->id : null ) : null)->get();

        $this->frontMenu = FrontMenu::where('language_setting_id', $frontMenuCount > 0 ? ( $this->localeLanguage ? $this->localeLanguage->id : null ) : null)->first();

        $this->frontDetail = FrontDetail::first();

        $this->frontWidgets = FrontWidget::all();

        setlocale(LC_TIME, $this->locale . '_' . strtoupper($this->locale));

        $this->detail = $this->frontDetail;

    }
}
