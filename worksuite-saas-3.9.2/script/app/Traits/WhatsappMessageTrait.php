<?php

namespace Modules\Sms\Http\Traits;


trait WhatsappMessageTrait
{

    public function toWhatsapp($notifiable, $message)
    {
        $settings = sms_setting();

        if (!$settings->whatsapp_status) {
            return true;
        }

        $toNumber = '+'.$notifiable->country->phonecode.$notifiable->mobile;
        $fromNumber = $settings->whatapp_from_number;

        $twilio = new \Twilio\Rest\Client($settings->account_sid, $settings->auth_token);

        $message = $twilio->messages
            ->create(
                "whatsapp:$toNumber", // to 
                array(
                    "from" => "whatsapp:$fromNumber",
                    "body" => $message
                )
            );
    }
}
