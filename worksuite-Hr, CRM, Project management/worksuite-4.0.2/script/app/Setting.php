<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends BaseModel
{
    protected $table = 'organisation_settings';
    protected $appends = ['logo_url', 'login_background_url','show_public_message','moment_date_format'];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function invoice_setting()
    {
        return $this->hasOne(InvoiceSetting::class, 'company_id');
    }

    public function getLogoUrlAttribute()
    {
        if (is_null($this->logo)) {
            return asset('img/worksuite-logo.png');
        }
        return asset_url('app-logo/'.$this->logo);
    }

    public function getLoginBackgroundUrlAttribute()
    {
        if (is_null($this->login_background) || $this->login_background == 'login-background.jpg') {
            return asset('img/login-bg.jpg');
        }

        return asset_url('login-background/'.$this->login_background);
    }

    public function getShowPublicMessageAttribute()
    {
        if (strpos(request()->url(), request()->getHost().'/public') !== false) {
            return true;
        }
        return false;
    }

    public function getMomentDateFormatAttribute()
    {
        $momentDateFormats = [
            'd-m-Y' => 'DD-MM-YYYY',
            'm-d-Y' => 'MM-DD-YYYY',
            'Y-m-d' => 'YYYY-MM-DD',
            'd.m.Y' => 'DD.MM.YYYY',
            'm.d.Y' => 'MM.DD.YYYY',
            'Y.m.d' => 'YYYY.MM.DD',
            'd/m/Y' => 'DD/MM/YYYY',
            'm/d/Y' => 'MM/DD/YYYY',
            'Y/m/d' => 'YYYY/MM/DD',
            'd/M/Y' => 'DD/MMM/YYYY',
            'd.M.Y' => 'DD.MMM.YYYY',
            'd-M-Y' => 'DD-MMM-YYYY',
            'd M Y' => 'DD MMM YYYY',
            'd F, Y' => 'DD MMMM, YYYY',
            'D/M/Y' => 'ddd/MMM/YYYY',
            'D.M.Y' => 'ddd.MMM.YYYY',
            'D-M-Y' => 'ddd-MMM-YYYY',
            'D M Y' => 'ddd MMM YYYY',
            'd D M Y' => 'DD ddd MMM YYYY',
            'D d M Y' => 'ddd DD MMM YYYY',
            'dS M Y' => 'Do MMM YYYY',
        ];
        return $momentDateFormats[$this->date_format];
    }

    public static function organisationSetting()
    {
        return cache()->remember(
            'global-setting',
            60*60*24,
            function () {
                return \App\Setting::with('currency')->first();
            }
        );
    }
}
