<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FrontFaq extends Model
{
    protected $guarded = ['id'];

    public function language()
    {
        return $this->belongsTo(LanguageSetting::class, 'language_setting_id');
    }
}
