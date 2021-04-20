<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Psy\Util\Json;

class MenuSetting extends Model
{
    protected $table = 'menu_settings';

    public function getMainMenuAttribute($value)
    {
        // decode json to array
        $settings = json_decode($value);

        // fetch all menus
        $menus = Menu::where('setting_menu', 0)->get();

        $menuSettings = [];

        foreach($settings as $key => $setting) {
            if(isset($setting->children)){
                $children = $setting->children;

                $menuObj = $menus->filter(function($item) use($setting) {
                    return $item->id == $setting->id;
                })->first();

                if($menuObj) {
                    $menuSettings[$key] = [
                        'id' => $menuObj->id,
                        'menu_name' => $menuObj->menu_name,
                        'translate_name' => $menuObj->translate_name,
                        'translated_name' => __($menuObj->translate_name),
                        'route' => $menuObj->route,
                        'module' => $menuObj->module,
                        'icon' => $menuObj->icon,
                        'setting_menu' => $menuObj->setting_menu,
                    ];
                }

                foreach($children as $childKey => $child) {
                    $menuObj = $menus->filter(function($item) use($child) {
                        return $item->id == $child->id;
                    })->first();

                    if($menuObj) {
                        $menuSettings[$key]['children'][] = [
                            'id' => $menuObj->id,
                            'menu_name' => $menuObj->menu_name,
                            'translate_name' => $menuObj->translate_name,
                            'translated_name' => __($menuObj->translate_name),
                            'route' => $menuObj->route,
                            'module' => $menuObj->module,
                            'icon' => $menuObj->icon,
                            'setting_menu' => $menuObj->setting_menu,
                        ];
                    }
                }
            } else {
                $menuObj = $menus->filter(function($item) use($setting) {
                    return $item->id == $setting->id;
                })->first();

                if($menuObj) {
                    $menuSettings[$key] = [
                        'id' => $menuObj->id,
                        'menu_name' => $menuObj->menu_name,
                        'translate_name' => $menuObj->translate_name,
                        'translated_name' => __($menuObj->translate_name),
                        'route' => $menuObj->route,
                        'module' => $menuObj->module,
                        'icon' => $menuObj->icon,
                        'setting_menu' => $menuObj->setting_menu,
                    ];
                }


            }
        }

        return $menuSettings;
    }

    public function getSettingMenuAttribute($value)
    {
        // decode json to array
        $settings = json_decode($value);

        // fetch all menus
        $menus = Menu::where('setting_menu', 1)->get();

        $settingMenu = [];

        foreach($settings as $key => $setting) {
            if(isset($setting->children)) {
                $children = $setting->children;

                $menuObj = $menus->filter(function($item) use($setting) {
                    return $item->id == $setting->id;
                })->first();

                if($menuObj) {
                    $settingMenu[$key] = [
                        'id' => $menuObj->id,
                        'menu_name' => $menuObj->menu_name,
                        'translate_name' => $menuObj->translate_name,
                        'translated_name' => __($menuObj->translate_name),
                        'route' => $menuObj->route,
                        'module' => $menuObj->module,
                        'icon' => $menuObj->icon,
                        'setting_menu' => $menuObj->setting_menu,
                    ];
                }

                foreach($children as $childKey => $child) {
                    $menuObj = $menus->filter(function($item) use($child) {
                        return $item->id == $child->id;
                    })->first();

                    if($menuObj) {
                        $settingMenu[$key]['children'][] = [
                            'id' => $menuObj->id,
                            'menu_name' => $menuObj->menu_name,
                            'translate_name' => $menuObj->translate_name,
                            'translated_name' => __($menuObj->translate_name),
                            'route' => $menuObj->route,
                            'module' => $menuObj->module,
                            'icon' => $menuObj->icon,
                            'setting_menu' => $menuObj->setting_menu,
                        ];
                    }
                }
            } else {
                $menuObj = $menus->filter(function($item) use($setting) {
                    return $item->id == $setting->id;
                })->first();

                if($menuObj) {
                    $settingMenu[$key] = [
                        'id' => $menuObj->id,
                        'menu_name' => $menuObj->menu_name,
                        'translate_name' => $menuObj->translate_name,
                        'translated_name' => __($menuObj->translate_name),
                        'route' => $menuObj->route,
                        'module' => $menuObj->module,
                        'icon' => $menuObj->icon,
                        'setting_menu' => $menuObj->setting_menu,
                    ];

                }

            }
        }

        return $settingMenu;
    }
}
