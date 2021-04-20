<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FrontMenu extends Model
{
    protected $guarded = ['id'];
    protected $table = 'front_menu_buttons';

    public function language()
    {
        return $this->belongsTo(LanguageSetting::class, 'language_setting_id');
    }
}
