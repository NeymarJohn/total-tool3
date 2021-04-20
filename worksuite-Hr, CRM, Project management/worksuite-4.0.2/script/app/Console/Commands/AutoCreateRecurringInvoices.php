<?php

namespace App\Console\Commands;

use App\Invoice;
use App\InvoiceItems;
use App\Notifications\NewInvoiceRecurring;
use App\RecurringInvoice;
use App\UniversalSearch;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoCreateRecurringInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring-invoice-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'auto create recurring invoices ';

    /**
     * AutoCreateRecurringInvoices constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $company = \App\Setting::with('currency')->first();

        $recurringInvoices = RecurringInvoice::with(['recurrings', 'items'])
            ->where('status', 'active')
            ->get();

        $recurringInvoices->each(function ($recurring) use($company) {
            if ($recurring->unlimited_recurring == 1 || ($recurring->unlimited_recurring == 0 && $recurring->recurrings->count() <= $recurring->billing_cycle)) {
                // Why type of date is today
                $today = Carbon::now()->timezone($company->timezone);
                $isMonthly = ($today->day === $recurring->day_of_month);
                $isWeekly = ($today->dayOfWeek === $recurring->day_of_week);
                $isBiWeekly = ($isWeekly && $today->weekOfYear % 2 === 0);
                $isQuarterly = ($isMonthly && $today->month % 3 === 1);
                $isHalfYearly = ($isMonthly && $today->month % 6 === 1);
                $isAnnually = ($isMonthly && $today->month % 12 === 1);
                if ($recurring->rotation === 'daily' ||
                    ($recurring->rotation === 'weekly' && $isWeekly) ||
                    ($recurring->rotation === 'bi-weekly' && $isBiWeekly) ||
                    ($recurring->rotation === 'monthly' && $isMonthly) ||
                    ($recurring->rotation === 'quarterly' && $isQuarterly) ||
                    ($recurring->rotation === 'half-yearly' && $isHalfYearly) ||
                    ($recurring->rotation === 'annually' && $isAnnually)
                ) {
                    $this->invoiceCreate($recurring);
                }
            }

            });
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function invoiceCreate($invoiceData)
    {
        $recurring = $invoiceData;

        $diff = $recurring->issue_date->diffInDays($recurring->due_date);
        $currentDate = Carbon::now();
        $dueDate = $currentDate->addDays($diff)->format('Y-m-d');

        $invoice = new Invoice();
        $invoice->invoice_recurring_id  = $recurring->id;
        $invoice->project_id            = $recurring->project_id ?? null;
        $invoice->client_id             = $recurring->project_id == '' && $recurring->client_id ? $recurring->client_id : null;
        $invoice->invoice_number        = $this->lastInvoiceNumber() + 1;
        $invoice->issue_date            =  $currentDate->format('Y-m-d');
        $invoice->due_date              = $dueDate;
        $invoice->sub_total             = round($recurring->sub_total, 2);
        $invoice->discount              = round($recurring->discount_value, 2);
        $invoice->discount_type         = $recurring->discount_type;
        $invoice->total                 = round($recurring->total, 2);
        $invoice->currency_id           = $recurring->currency_id;
        $invoice->note                  = $recurring->note;
        $invoice->show_shipping_address = $recurring->show_shipping_address;
        $invoice->send_status  = 1;
        $invoice->save();

        if ($invoice->shipping_address) {
            if ($invoice->project_id != null && $invoice->project_id != '') {
                $client = $invoice->project->clientdetails;
                $client->shipping_address = $invoice->shipping_address;

                $client->save();
            } elseif ($invoice->client_id != null && $invoice->client_id != '') {
                $client = $invoice->clientdetails;
                $client->shipping_address = $invoice->shipping_address;
                $client->save();
            }

        }

        foreach ($invoiceData->items as $key => $item) :
            InvoiceItems::create(
                [
                    'invoice_id'   => $invoice->id,
                    'item_name'    => $item->item_name,
                    'item_summary' => $item->item_summary,
                    'hsn_sac_code' => (isset($item->hsn_sac_code)) ? $item->hsn_sac_code : null ,
                    'type'         => 'item',
                    'quantity'     => $item->quantity,
                    'unit_price'   => $item->unit_price,
                    'amount'       => $item->amount,
                    'taxes'        => $item->taxes
                ]
            );
        endforeach;

        if (($invoice->project && $invoice->project->client_id != null) || $invoice->client_id != null) {
            $clientId = ($invoice->project && $invoice->project->client_id != null) ? $invoice->project->client_id : $invoice->client_id;
            // Notify client
            $notifyUser = User::withoutGlobalScopes(['active'])->findOrFail($clientId);

            $notifyUser->notify(new NewInvoiceRecurring($invoice));
        }

        //log search
        $this->logSearchEntry($invoice->id, 'Invoice ' . $invoice->invoice_number, 'admin.all-invoices.show', 'invoice');

    }

    /**
     * @param $searchableId
     * @param $title
     * @param $route
     * @param $type
     */
    public function logSearchEntry($searchableId, $title, $route, $type)
    {
        $search = new UniversalSearch();
        $search->searchable_id = $searchableId;
        $search->title = $title;
        $search->route_name = $route;
        $search->module_type = $type;
        $search->save();

    }

    /**
     * @return mixed
     */
    public static function lastInvoiceNumber()
    {
        $invoice = DB::select('SELECT MAX(CAST(`invoice_number` as UNSIGNED)) as invoice_number FROM `invoices`');
        return $invoice[0]->invoice_number;
    }
}
