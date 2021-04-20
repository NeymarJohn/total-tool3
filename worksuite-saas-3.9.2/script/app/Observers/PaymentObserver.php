<?php

namespace App\Observers;

use App\Estimate;
use App\Notifications\NewInvoice;
use App\Notifications\NewPayment;
use App\Payment;
use App\Scopes\CompanyScope;
use App\User;

class PaymentObserver
{

    public function saving(Payment $payment)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $payment->company_id = company()->id;
        }
    }

    public function created(Payment $payment){
        if (!isSeedingData()) {
            if (($payment->project_id && $payment->project->client_id != null) || ($payment->invoice_id && $payment->invoice->client_id != null)) {
                $clientId = ($payment->project_id && $payment->project->client_id != null) ? $payment->project->client_id : $payment->invoice->client_id;
                // Notify client
                $notifyUser = User::withoutGlobalScopes(['active', CompanyScope::class])->findOrFail($clientId);
                $notifyUser->notify(new NewPayment($payment));
            }
        }
    }

}
