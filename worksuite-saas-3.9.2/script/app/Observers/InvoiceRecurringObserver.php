<?php

namespace App\Observers;


use App\Events\NewInvoiceRecurringEvent;
use App\RecurringInvoice;
use App\RecurringInvoiceItems;

class InvoiceRecurringObserver
{

    public function creating(RecurringInvoice $invoice)
    {
     //
    }

    public function saving(RecurringInvoice $invoice)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $invoice->company_id = company()->id;
        }
    }

    public function created(RecurringInvoice $invoice)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if (!empty(request()->item_name)) {
                $itemsSummary = request()->input('item_summary');
                $cost_per_item = request()->input('cost_per_item');
                $quantity = request()->input('quantity');
                $amount = request()->input('amount');
                $tax = request()->input('taxes');
                $hsnSacCode = request()->input('hsn_sac_code');
                foreach (request()->item_name as $key => $item) :
                    if (!is_null($item)) {
                        RecurringInvoiceItems::create(
                            [
                                'invoice_recurring_id' => $invoice->id,
                                'item_name' => $item,
                                'hsn_sac_code' => (isset($hsnSacCode[$key]) && !is_null($hsnSacCode[$key])) ? $hsnSacCode[$key] : null,
                                'item_summary' => $itemsSummary[$key] ? $itemsSummary[$key] : '',
                                'type' => 'item',
                                'quantity' => $quantity[$key],
                                'unit_price' => round($cost_per_item[$key], 2),
                                'amount' => round($amount[$key], 2),
                                'taxes' => $tax ? array_key_exists($key, $tax) ? json_encode($tax[$key]) : null : null
                            ]
                        );
                    }
                endforeach;
            }
        }

        if(!isSeedingData()){
            $userType = '';
            event(new NewInvoiceRecurringEvent($invoice, $userType));
        }
    }

    public function updated(RecurringInvoice $invoice)
    {
        if (!isSeedingData()) {

            if ($invoice->isDirty('status')) {
                $userType = 'status';
                event(new NewInvoiceRecurringEvent($invoice, $userType));
            }
        }
    }

    public function deleting(RecurringInvoice $invoice)
    {
       //
    }
}
