<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\ProjectSetting\UpdateProjectSetting;
use App\Menu;
use App\MenuSetting;
use Illuminate\Http\Request;

class MenuSettingController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.menuSetting';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->menuSettings = MenuSetting::first();
        return view('admin.menu-settings.index', $this->data);
    }


    public function update(Request $request, $id)
    {
        $menuSetting = MenuSetting::find($id);

        session()->forget('main_menu_settings');
        session()->forget('sub_menu_settings');

        if($request->has('type') == 'reset') {
            $menuSetting->main_menu = $menuSetting->default_main_menu;
            $menuSetting->setting_menu = $menuSetting->default_setting_menu;
            $menuSetting->save();



            return Reply::redirect(route('admin.menu-settings.index'), __('messages.menuSettingReset'));
        }

        $menuSetting->main_menu = json_encode($request->main_menu);
        $menuSetting->setting_menu = json_encode($request->setting_menu);
        $menuSetting->save();

        return Reply::redirect(route('admin.menu-settings.index') , __('messages.menuSettingUpdated'));
    }
}
