<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends BaseModel
{
    protected $table = 'companies';
    protected $appends = ['logo_url'];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withoutGlobalScopes(['enable']);
    }

    public function getLogoUrlAttribute()
    {
        if (is_null($this->logo)) {
            $global = global_settings();
            return $global->logo_url;
        }
        return asset_url('app-logo/' . $this->logo);
    }
}
