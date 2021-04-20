<?php

namespace App\Observers;

use App\Setting;

class SettingsObserver
{
    public function saving(Setting $setting)
    {

        $user =  user();
        if($user){
            $setting->last_updated_by = $user->id;
        }

        if ($setting->isDirty('date_format')) {
            switch ($setting->date_format) {
                case 'd-m-Y':
                    $setting->date_picker_format = 'dd-mm-yyyy';
                    break;
                case 'm-d-Y':
                    $setting->date_picker_format = 'mm-dd-yyyy';
                    break;
                case 'Y-m-d':
                    $setting->date_picker_format = 'yyyy-mm-dd';
                    break;
                case 'd.m.Y':
                    $setting->date_picker_format = 'dd.mm.yyyy';
                    break;
                case 'm.d.Y':
                    $setting->date_picker_format = 'mm.dd.yyyy';
                    break;
                case 'Y.m.d':
                    $setting->date_picker_format = 'yyyy.mm.dd';
                    break;
                case 'd/m/Y':
                    $setting->date_picker_format = 'dd/mm/yyyy';
                    break;
                case 'Y/m/d':
                    $setting->date_picker_format = 'yyyy/mm/dd';
                    break;
                case 'd-M-Y':
                    $setting->date_picker_format = 'dd-M-yyyy';
                    break;
                case 'd/M/Y':
                    $setting->date_picker_format = 'dd/M/yyyy';
                    break;
                case 'd.M.Y':
                    $setting->date_picker_format = 'dd.M.yyyy';
                    break;
                case 'd M Y':
                    $setting->date_picker_format = 'dd M yyyy';
                    break;
                case 'd F, Y':
                    $setting->date_picker_format = 'dd MM, yyyy';
                    break;
                case 'd D M Y':
                    $setting->date_picker_format = 'dd D M yyyy';
                    break;
                case 'D d M Y':
                    $setting->date_picker_format = 'D dd M yyyy';
                    break;
                default:
                    $setting->date_picker_format = 'mm/dd/yyyy';
                    break;
            }
        }

    }

}
