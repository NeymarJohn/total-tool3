<?php

namespace App;

class InvoiceSetting extends BaseModel
{

    protected $appends = ['logo_url'];

    public function getLogoUrlAttribute()
    {
        return (is_null($this->logo)) ? asset('img/worksuite-logo.png') : asset_url('app-logo/' . $this->logo);
    }
}
