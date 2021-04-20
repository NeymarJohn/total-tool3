<?php

namespace App\Observers;

use App\PaymentGatewayCredentials;

class PaymentGatewayCredentialObserver
{

    public function saving(PaymentGatewayCredentials $credenrial)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $credenrial->company_id = company()->id;
        }
    }

}
