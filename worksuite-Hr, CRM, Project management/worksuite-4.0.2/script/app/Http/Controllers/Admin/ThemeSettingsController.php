<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\UpdateThemeSetting;
use App\Setting;
use App\ThemeSetting;
use Illuminate\Http\Request;

class ThemeSettingsController extends AdminBaseController
{

    public function __construct()
    {
        parent:: __construct();
        $this->pageTitle = 'app.menu.themeSettings';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $themeSetting = ThemeSetting::get();

        // get theme from single database query and then grouby panel as key
        $themes = $themeSetting->groupBy('panel');


        $this->adminTheme = $themes['admin'][0];
        $this->projectAdminTheme = $themes['project_admin'][0];
        $this->employeeTheme = $themes['employee'][0];
        $this->clientTheme = $themes['client'][0];

        return view('admin.theme-settings.edit', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param UpdateThemeSetting $request
     * @return array
     */
    public function store(UpdateThemeSetting $request)
    {
        $adminTheme = ThemeSetting::where('panel', 'admin')->first();
        $this->themeUpdate($adminTheme, $request->theme_settings[1]);


        $employeeTheme = ThemeSetting::where('panel', 'employee')->first();
        $this->themeUpdate($employeeTheme, $request->theme_settings[3]);

        $clientTheme = ThemeSetting::where('panel', 'client')->first();
        $this->themeUpdate($clientTheme, $request->theme_settings[4]);

        session()->forget(['admin_theme', 'employee_theme', 'client_theme']);

        return Reply::redirect(route('admin.theme-settings.index'), __('messages.settingsUpdated'));
    }

    private function themeUpdate($updateObject, $theme_setting)
    {
        $updateObject->header_color = $theme_setting['header_color'];
        $updateObject->sidebar_color = $theme_setting['sidebar_color'];
        $updateObject->sidebar_text_color = $theme_setting['sidebar_text_color'];
        $updateObject->link_color = $theme_setting['link_color'];
        $updateObject->user_css = $theme_setting['user_css'];
        $updateObject->save();
        session()->forget(['admin_theme', 'employee_theme', 'client_theme']);
    }

    public function activeTheme(Request $request)
    {
        $setting = $this->global;
        $setting->active_theme = $request->active_theme;
        $setting->save();
        session()->forget('global_setting');
        cache()->forget('global-setting');

        return Reply::redirect(route('admin.theme-settings.index'), __('messages.settingsUpdated'));
    }

    public function roundedTheme(Request $request)
    {
        $setting = $this->global;
        $setting->rounded_theme = $request->rounded_theme;
        $setting->save();
        session()->forget('global_setting');
        cache()->forget('global-setting');

        return Reply::redirect(route('admin.theme-settings.index'), __('messages.settingsUpdated'));
    }

    public function logoBackgroundColor(Request $request)
    {
        $setting = $this->global;
        $setting->logo_background_color = $request->logo_background_color;
        $setting->save();
        session()->forget('global_setting');
        cache()->forget('global-setting');
        
        return Reply::redirect(route('admin.theme-settings.index'), __('messages.settingsUpdated'));
    }

}
