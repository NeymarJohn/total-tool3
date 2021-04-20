<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class SmtpConfigProvider extends ServiceProvider
{


    public function register()
    {
        try {
            $smtpSetting = DB::table('smtp_settings')->first();

            if ($smtpSetting) {
                $settings = DB::table('organisation_settings')->first();

                if (\config('app.env') !== 'development') {
                    Config::set('mail.driver', $smtpSetting->mail_driver);
                    Config::set('mail.host', $smtpSetting->mail_host);
                    Config::set('mail.port', $smtpSetting->mail_port);
                    Config::set('mail.username', $smtpSetting->mail_username);
                    Config::set('mail.password', $smtpSetting->mail_password);
                    Config::set('mail.encryption', $smtpSetting->mail_encryption);
                }

                Config::set('mail.from.name', $smtpSetting->mail_from_name);
                Config::set('mail.from.address', $smtpSetting->mail_from_email);

                Config::set('app.name', $settings->company_name);
                Config::set('app.name', $settings->company_name);

                if (is_null($settings->logo)) {
                    Config::set('app.logo', asset('img/worksuite-logo.png'));
                } else {
                    Config::set('app.logo', asset_url('app-logo/' . $settings->logo));
                }

                $pushSetting = DB::table('push_notification_settings')->first();
                if ($pushSetting) {
                    Config::set('services.onesignal.app_id', $pushSetting->onesignal_app_id);
                    Config::set('services.onesignal.rest_api_key', $pushSetting->onesignal_rest_api_key);
                }
            }
        } catch (\Exception $e) {
        }


        $app = App::getInstance();
        $app->register('Illuminate\Mail\MailServiceProvider');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
