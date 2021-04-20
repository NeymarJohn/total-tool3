<?php

namespace App\Observers;


use App\PurposeConsent;

class PurposeConsentObserver
{

    public function saving(PurposeConsent $purposeConsent)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $purposeConsent->company_id = company()->id;
        }
    }

}
