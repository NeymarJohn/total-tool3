<?php

namespace App\Observers;


use App\InvoiceSetting;

class InvoiceSettingObserver
{

    public function saving(InvoiceSetting $invoice)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $invoice->company_id = company()->id;
            session()->forget('invoice_setting');
        }
    }

}
