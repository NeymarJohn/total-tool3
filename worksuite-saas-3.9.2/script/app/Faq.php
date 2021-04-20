<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faq extends BaseModel
{
    protected $table = 'faqs';

    public $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return ($this->image) ? asset_url('faq-files/' .$this->id.'/'. $this->image) : asset('saas/img/svg/mock-2.svg');
    }

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }

    public function files()
    {
        return $this->hasMany(FaqFile::class, 'faq_id');
    }

}
