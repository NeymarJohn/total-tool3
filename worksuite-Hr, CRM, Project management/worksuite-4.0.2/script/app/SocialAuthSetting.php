<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialAuthSetting extends Model
{
    protected $table = 'social_auth_settings';
    protected $guarded = ['id'];
}
