<?php

namespace App\Observers;

use App\ThemeSetting;

class ThemeSettingObserver
{

    public function saving(ThemeSetting $theme)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $theme->company_id = company()->id;
            session()->forget('admin_theme');
            session()->forget('employee_theme');
        }
    }

}
