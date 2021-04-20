<?php

namespace App\Observers;

use App\Estimate;
use App\Events\NewInvoiceEvent;
use App\Events\NewProductPurchaseEvent;
use App\Invoice;
use App\InvoiceItems;
use App\UniversalSearch;
use App\User;

class InvoiceObserver
{

    public function creating(Invoice $invoice)
    {
        if (request()->type && request()->type == "send" || !is_null($invoice->invoice_recurring_id)) {
            $invoice->send_status = 1;
        } else {
            $invoice->send_status = 0;

        }

        if (request()->total && request()->total == 0) {
            $invoice->status = 'paid';
        }

        if (request()->type && request()->type == "draft") {
            $invoice->status = 'draft';
        }

        if (!is_null($invoice->estimate_id)) {
            $estimate = Estimate::findOrFail($invoice->estimate_id);
            if($estimate->status == 'accepted'){
                $invoice->send_status = 1;
            }
        }

        $invoice->invoice_number = Invoice::lastInvoiceNumber() + 1;

    }

    public function created(Invoice $invoice)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if (!empty(request()->item_name)) {

                $itemsSummary = request()->input('item_summary');
                $cost_per_item = request()->input('cost_per_item');
                $quantity = request()->input('quantity');
                $hsnSacCode = request()->input('hsn_sac_code');
                $amount = request()->input('amount');
                $tax = request()->input('taxes');

                foreach (request()->item_name as $key => $item) :
                    if (!is_null($item)) {
                        InvoiceItems::create(
                            [
                                'invoice_id' => $invoice->id,
                                'item_name' => $item,
                                'item_summary' => $itemsSummary[$key] ? $itemsSummary[$key] : '',
                                'hsn_sac_code' => (isset($hsnSacCode[$key]) && !is_null($hsnSacCode[$key])) ? $hsnSacCode[$key] : null,
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

            if (($invoice->project && $invoice->project->client_id != null) || $invoice->client_id != null) {
                $clientId = ($invoice->project && $invoice->project->client_id != null) ? $invoice->project->client_id : $invoice->client_id;
                // Notify client
                $notifyUser = User::withoutGlobalScope('active')->findOrFail($clientId);
                if (request()->type && request()->type == "send") {
                    event(new NewInvoiceEvent($invoice, $notifyUser));
                }
            }
            $isClient = User::isClient(user()->id);
            if($isClient){
                event(new NewProductPurchaseEvent($invoice));
            }
        }
    }

    public function deleting(Invoice $invoice)
    {
        $universalSearches = UniversalSearch::where('searchable_id', $invoice->id)->where('module_type', 'invoice')->get();
        if ($universalSearches) {
            foreach ($universalSearches as $universalSearch) {
                UniversalSearch::destroy($universalSearch->id);
            }
        }
    }
}
