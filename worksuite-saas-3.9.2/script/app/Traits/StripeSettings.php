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

trait StripeSettings{

    public function setStripConfigs(){
        $settings = StripeSetting::first();
        $key       = ($settings->api_key)? $settings->api_key : env('STRIPE_KEY');
        $apiSecret = ($settings->api_secret)? $settings->api_secret : env('STRIPE_SECRET');
        $webhookKey= ($settings->webhook_key)? $settings->webhook_key : env('STRIPE_WEBHOOK_SECRET');

        Config::set('cashier.key', $key);
        Config::set('cashier.secret', $apiSecret);
        Config::set('cashier.webhook.secret', $webhookKey);
    }
}



