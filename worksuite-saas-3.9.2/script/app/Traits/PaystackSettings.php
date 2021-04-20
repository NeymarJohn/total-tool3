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

trait PaystackSettings{

    public function setPaystackConfigs(){
        $settings = StripeSetting::first();
        $key       = ($settings->paystack_client_id)? $settings->paystack_client_id : env('PAYSTACK_PUBLIC_KEY');
        $apiSecret = ($settings->paystack_secret)? $settings->paystack_secret : env('PAYSTACK_SECRET_KEY');
        $email = ($settings->paystack_merchant_email)? $settings->paystack_merchant_email : env('MERCHANT_EMAIL');
        $url = ($settings->paystack_payment_url)? $settings->paystack_payment_url : env('PAYSTACK_PAYMENT_URL');

        Config::set('paystack.publicKey', $key);
        Config::set('paystack.secretKey', $apiSecret);
        Config::set('paystack.paymentUrl', $url);
        Config::set('paystack.merchantEmail', $email);
    }
}



