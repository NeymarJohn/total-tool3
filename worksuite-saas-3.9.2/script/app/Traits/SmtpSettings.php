<?php

/**
 * Created by PhpStorm.
 * User: DEXTER
 * Date: 24/05/17
 * Time: 11:29 PM
 */

namespace App\Traits;

use App\PushNotificationSetting;
use App\SmtpSetting;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Support\Facades\Config;

trait SmtpSettings
{

    public function setMailConfigs()
    {
        $smtpSetting = SmtpSetting::first();
        $pushSetting = PushNotificationSetting::first();
        $company = company();
        $settings = global_settings();

        $companyName = $company ? $company->company_name : $smtpSetting->mail_from_name;
        $companyEmail = $company ? $company->company_email : $smtpSetting->mail_from_email;

        if (\config('app.env') !== 'development') {
            Config::set('mail.driver', $smtpSetting->mail_driver);
            Config::set('mail.host', $smtpSetting->mail_host);
            Config::set('mail.port', $smtpSetting->mail_port);
            Config::set('mail.username', $smtpSetting->mail_username);
            Config::set('mail.password', $smtpSetting->mail_password);
            Config::set('mail.encryption', $smtpSetting->mail_encryption);
        }

        Config::set('mail.reply_to.name', $companyName);
        Config::set('mail.reply_to.address', $companyEmail);
        Config::set('mail.from.name', $companyName);
        // SES and other mail services which require email from verified sources
        if (\config('mail.verified') === true) {
            #Config::set('mail.from.name', $smtpSetting->mail_from_name);
            Config::set('mail.from.address', $smtpSetting->mail_from_email);
        } else {
            Config::set('mail.from.address', $companyEmail);
        }

        Config::set('services.onesignal.app_id', $pushSetting->onesignal_app_id);
        Config::set('services.onesignal.rest_api_key', $pushSetting->onesignal_rest_api_key);

        Config::set('app.name', $companyName);

        if ($company) {
            Config::set('app.logo', $company->logo_url);
        } else {
            Config::set('app.logo', $settings->logo_url);
        }

        (new MailServiceProvider(app()))->register();
    }
}
