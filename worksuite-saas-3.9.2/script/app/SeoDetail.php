<?php

namespace App;

class SeoDetail extends BaseModel
{
    protected $table = 'seo_details';
    protected $fillable = ['language_setting_id','page_name','seo_title','seo_author','seo_description','seo_keywords'];
    protected $appends = ['og_image_url'];

    public function getOgImageUrlAttribute()
    {
        return ($this->og_image) ? asset_url('front/seo-detail/' . $this->og_image) : asset('img/home-crm.png');
    }
}
