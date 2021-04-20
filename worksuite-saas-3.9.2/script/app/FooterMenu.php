<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FooterMenu extends BaseModel
{
    protected $table = 'footer_menu';

    public function language()
    {
        return $this->belongsTo(LanguageSetting::class, 'language_setting_id');
    }
    
    public function getVideoUrlAttribute()
    {
        return ($this->file_name) ? asset_url('footer-files/' . $this->file_name) : '';
    }
}
