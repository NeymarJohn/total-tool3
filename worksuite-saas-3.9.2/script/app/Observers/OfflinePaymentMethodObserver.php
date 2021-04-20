<?php

namespace App\Observers;

use App\OfflinePaymentMethod;

class OfflinePaymentMethodObserver
{

    public function saving(OfflinePaymentMethod $paymentMethod)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $paymentMethod->company_id = company()->id;
        }
    }

}
