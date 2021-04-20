<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Company;
use App\Helper\Reply;
use App\Http\Controllers\SuperAdmin\SuperAdminBaseController;
use App\Http\Requests\UpdateThemeSetting;
use App\Scopes\CompanyScope;
use App\Setting;
use App\ThemeSetting;
use Illuminate\Http\Request;

class SuperAdminThemeSettingsController extends SuperAdminBaseController
{

    public function __construct()
    {
        parent::__construct();
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
        $this->adminTheme = ThemeSetting::withoutGlobalScope(CompanyScope::class)->where('panel', 'superadmin')->first();
        return view('super-admin.theme-settings.edit', $this->data);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UpdateThemeSetting $request)
    {
        $adminTheme = ThemeSetting::where('panel', 'superadmin')->first();
        $adminTheme->header_color = $request->theme_settings[1]['header_color'];
        $adminTheme->sidebar_color = $request->theme_settings[1]['sidebar_color'];
        $adminTheme->sidebar_text_color = $request->theme_settings[1]['sidebar_text_color'];
        $adminTheme->link_color = $request->theme_settings[1]['link_color'];
        $adminTheme->save();

        session()->forget('superadmin_theme');

        return Reply::redirect(route('super-admin.theme-settings.index'), __('messages.settingsUpdated'));
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function activeTheme(Request $request)
    {
        $setting = global_settings();
        $setting->active_theme = $request->active_theme;
        $setting->save();
        session()->forget('global_settings');
        return Reply::redirect(route('super-admin.theme-settings.index'), __('messages.settingsUpdated'));
    }

    public function roundedTheme(Request $request)
    {
        $setting = $this->global;
        $setting->rounded_theme = $request->rounded_theme;
        $setting->save();
        session()->forget('company_setting');

        return Reply::redirect(route('admin.theme-settings.index'), __('messages.settingsUpdated'));
    }
}
