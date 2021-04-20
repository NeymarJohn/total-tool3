<?php

namespace App\Http\Controllers;

use App\ClientPayment;
use App\Company;
use App\Notification;
use App\Notifications\CompanyPurchasedPlan;
use App\Notifications\CompanyUpdatedPlan;
use App\PaypalInvoice;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaypalIPNController extends Controller
{
    public function verifyIPN(Request $request)
    {

        $txnType = $request->get('txn_type');

        if ($txnType == 'recurring_payment') {

            $recurringPaymentId = $request->get('recurring_payment_id');
            $eventId = $request->get('ipn_track_id');

            $event = ClientPayment::where('event_id', $eventId)->count();

            if($event == 0)
            {
                $payment =  ClientPayment::where('transaction_id', $recurringPaymentId)->first();

                $clientPayment = new ClientPayment();
                $clientPayment->invoice_id = $payment->invoice_id;
                $clientPayment->amount = $payment->amount;
                $clientPayment->gateway = 'Paypal';
                $clientPayment->status = 'complete';
                $clientPayment->event_id = $eventId;
                $clientPayment->paid_on = Carbon::now();
                $clientPayment->save();

                return response('IPN Handled', 200);
            }

        }
    }

    public function verifyBillingIPN(Request $request)
    {
        $txnType = $request->get('txn_type');
        if ($txnType == 'recurring_payment') {

            $recurringPaymentId = $request->get('recurring_payment_id');
            $eventId = $request->get('ipn_track_id');

            $event = PaypalInvoice::where('event_id', $eventId)->count();

            if($event == 0)
            {
                $payment =  PaypalInvoice::where('transaction_id', $recurringPaymentId)->first();

                $today = Carbon::now();
                if(company()->package_type == 'annual') {
                    $nextPaymentDate = $today->addYear();
                } else if(company()->package_type == 'monthly') {
                    $nextPaymentDate = $today->addMonth();
                }

                $paypalInvoice = new PaypalInvoice();
                $paypalInvoice->transaction_id = $recurringPaymentId;
                $paypalInvoice->company_id = $payment->company_id;
                $paypalInvoice->currency_id = $payment->currency_id;
                $paypalInvoice->total = $payment->total;
                $paypalInvoice->status = 'paid';
                $paypalInvoice->plan_id = $payment->plan_id;
                $paypalInvoice->billing_frequency = $payment->billing_frequency;
                $paypalInvoice->event_id = $eventId;
                $paypalInvoice->billing_interval = 1;
                $paypalInvoice->paid_on = $today;
                $paypalInvoice->next_pay_date = $nextPaymentDate;
                $paypalInvoice->save();

                // Change company status active after payment
                $company = Company::findOrFail($payment->company_id);
                $company->status = 'active';
                $company->save();

                $generatedBy = User::whereNull('company_id')->get();
                $lastInvoice = PaypalInvoice::where('company_id')->count();

                if($lastInvoice > 1){
                    Notification::send($generatedBy, new CompanyUpdatedPlan($company, $payment->plan_id));
                }else{
                    Notification::send($generatedBy, new CompanyPurchasedPlan($company, $payment->plan_id));
                }

                return response('IPN Handled', 200);
            }

        }
    }
}
