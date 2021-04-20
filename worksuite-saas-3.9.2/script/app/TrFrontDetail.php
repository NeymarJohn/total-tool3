<?php

namespace App;

class TrFrontDetail extends BaseModel
{
    protected $guarded = ['id'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return ($this->image) ? asset_url('front/' . $this->image) : asset('saas/img/home/home-crm.png');
    }

    public function language()
    {
        return $this->belongsTo(LanguageSetting::class, 'language_setting_id');
    }
}
