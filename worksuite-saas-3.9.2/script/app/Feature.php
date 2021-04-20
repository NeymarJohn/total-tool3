<?php

namespace App;

class Feature extends BaseModel
{
    protected $table = 'features';
    protected $appends = ['image_url'];

    public function language()
    {
        return $this->belongsTo(LanguageSetting::class, 'language_setting_id');
    }

    public function getImageUrlAttribute()
    {
        if ($this->type == 'image' && is_null($this->image)) {
            if ($this->id == 1) {
                return asset('saas/img/svg/mock-banner.svg');
            }
            if ($this->id == 2) {
                return asset('saas/img/svg/mock-2.svg');
            }
            if ($this->id == 3) {
                return asset('saas/img/svg/mock-1.svg');
            }
        }
        if ($this->type == 'apps') {
            if(!is_null($this->image)){
                return asset_url('front/feature/' . $this->image);
            }

            if (strtolower($this->title) == 'onesignal') {
                return asset('saas/img/pages/app-2.png');
            }
            if (strtolower($this->title) == 'paypal') {
                return asset('saas/img/pages/app-0.png');
            }
            if (strtolower($this->title) == 'slack') {
                return asset('saas/img/pages/app-5.png');
            }
            return asset('saas/img/pages/app-' . (($this->id) % 6) . '.png');
        }

        return ($this->image) ? asset_url('front/feature/' . $this->image) : asset('front/img/tools.png');
    }
}
