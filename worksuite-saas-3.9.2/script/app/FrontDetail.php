<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FrontDetail extends BaseModel
{
    protected $table = 'front_details';

    protected $appends = ['image_url','light_color'];

    public function getImageUrlAttribute()
    {
        return ($this->image) ? asset_url('front/' . $this->image) : asset('saas/img/home/home-crm.png');
    }

    public function getLightColorAttribute()
    {
        if(strlen($this->primary_color)===7){
            return $this->primary_color.'26';
        }
        return $this->primary_color;
    }
}
