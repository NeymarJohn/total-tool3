<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FrontClients extends Model
{
    protected $guarded = ['id'];
    protected $appends = ['image_url'];

    public function language()
    {
        return $this->belongsTo(LanguageSetting::class, 'language_setting_id');
    }

    public function getImageUrlAttribute()
    {
        return ($this->image) ? asset_url('front/client/' . $this->image) : asset('saas/img/home/client-'.($this->id).'.png');
    }
}
