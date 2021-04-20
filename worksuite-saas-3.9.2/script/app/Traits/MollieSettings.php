<?php
/**
 * Created by PhpStorm.
 * User: DEXTER
 * Date: 24/05/17
 * Time: 11:29 PM
 */

namespace App\Traits;

use App\StripeSetting;
use Illuminate\Support\Facades\Config;

trait MollieSettings{

    public function setMollieConfigs(){
        $settings = StripeSetting::first();
        $key       = ($settings->mollie_api_key)? $settings->mollie_api_key : env('MOLLIE_KEY');
        Config::set('mollie.key', $key);
    }
}



