<?php

namespace App\Http\Controllers\Admin;

use App\ClientDetails;
use App\Currency;
use App\DataTables\Admin\EstimatesDataTable;
use App\Estimate;
use App\EstimateItem;
use App\Helper\Reply;
use App\Http\Requests\StoreEstimate;
use App\InvoiceSetting;
use App\Notifications\NewEstimate;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Tax;
use App\Product;

class ManageEstimatesController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.estimates';
        $this->pageIcon = 'ti-file';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('estimates', $this->user->modules),403);
            return $next($request);
        });
    }

    public function index(EstimatesDataTable $dataTable)
    {
        return $dataTable->render('admin.estimates.index', $this->data);
    }

    public function create()
    {
        $this->clients = ClientDetails::all();
        $this->currencies = Currency::all();
        $this->lastEstimate = Estimate::lastEstimateNumber() + 1;
        $this->invoiceSetting = InvoiceSetting::first();
        $this->zero = '';
        if (strlen($this->lastEstimate) < $this->invoiceSetting->estimate_digit) {
            for ($i = 0; $i < $this->invoiceSetting->estimate_digit - strlen($this->lastEstimate); $i++) {
                $this->zero = '0' . $this->zero;
            }
        }
        $this->taxes = Tax::all();
        $this->products = Product::select('id', 'name as title', 'name as text')->get();


        $estimate = new Estimate();
        $this->fields = $estimate->getCustomFieldGroupsWithFields()->fields;
        return view('admin.estimates.create', $this->data);
    }

    public function duplicateEstimate($id)
    {
        $this->clients = ClientDetails::all();
        $this->currencies = Currency::all();
        $this->lastEstimate = Estimate::lastEstimateNumber() + 1;
        $this->invoiceSetting = InvoiceSetting::first();
        $this->zero = '';
        $this->estimate = Estimate::findOrFail($id);
        if (strlen($this->lastEstimate) < $this->invoiceSetting->estimate_digit) {
            for ($i = 0; $i < $this->invoiceSetting->estimate_digit - strlen($this->lastEstimate); $i++) {
                $this->zero = '0' . $this->zero;
            }
        }
        $this->taxes = Tax::all();
        $this->products = Product::select('id', 'name as title', 'name as text')->get();


        return view('admin.estimates.duplicate-estimate', $this->data);
    }

    public function store(StoreEstimate $request)
    {
        DB::beginTransaction();
        $items = $request->input('item_name');
        $itemsSummary = $request->input('item_summary');
        $cost_per_item = $request->input('cost_per_item');
        $quantity = $request->input('quantity');
        $hsnSacCode = request()->input('hsn_sac_code');
        $amount = $request->input('amount');
        $tax = $request->input('taxes');

        if (trim($items[0]) == '' || trim($items[0]) == '' || trim($cost_per_item[0]) == '') {
            return Reply::error(__('messages.addItem'));
        }

        foreach ($quantity as $qty) {
            if (!is_numeric($qty) && (intval($qty) < 1)) {
                return Reply::error(__('messages.quantityNumber'));
            }
        }

        foreach ($cost_per_item as $rate) {
            if (!is_numeric($rate)) {
                return Reply::error(__('messages.unitPriceNumber'));
            }
        }

        foreach ($amount as $amt) {
            if (!is_numeric($amt)) {
                return Reply::error(__('messages.amountNumber'));
            }
        }

        foreach ($items as $itm) {
            if (is_null($itm)) {
                return Reply::error(__('messages.itemBlank'));
            }
        }

        $estimate = new Estimate();
        $estimate->client_id = $request->client_id;
        $estimate->estimate_number = Estimate::lastEstimateNumber() + 1;
        $estimate->valid_till = Carbon::createFromFormat($this->global->date_format, $request->valid_till)->format('Y-m-d');
        $estimate->sub_total = round($request->sub_total, 2);
        $estimate->total = round($request->total, 2);
        $estimate->currency_id = $request->currency_id;
        $estimate->note = $request->note;
        $estimate->discount = round($request->discount_value, 2);
        $estimate->discount_type = $request->discount_type;
        $estimate->status = 'waiting';
        /*dd($estimate->estimate_number);*/
        $estimate->save();

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $estimate->updateCustomFieldData($request->get('custom_fields_data'));
        }

        foreach ($items as $key => $item) :
            if (!is_null($item)) {
                EstimateItem::create(
                    [
                        'estimate_id' => $estimate->id,
                        'item_name' => $item,
                        'item_summary' => $itemsSummary[$key],
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

        $this->logSearchEntry($estimate->id, 'Estimate #' . $estimate->id, 'admin.estimates.edit', 'estimate');
        DB::commit();

        return Reply::redirect(route('admin.estimates.index'), __('messages.estimateCreated'));
    }

    public function edit($id)
    {
        $this->estimate = Estimate::findOrFail($id)->withCustomFields();
        $this->fields = $this->estimate->getCustomFieldGroupsWithFields()->fields;
        $this->clients = ClientDetails::all();
        $this->currencies = Currency::all();
        $this->taxes = Tax::all();
        $this->products = Product::select('id', 'name as title', 'name as text')->get();

        return view('admin.estimates.edit', $this->data);
    }

    public function update(StoreEstimate $request, $id)
    {
        $items = $request->input('item_name');
        $itemsSummary = $request->input('item_summary');
        $cost_per_item = $request->input('cost_per_item');
        $quantity = $request->input('quantity');
        $hsnSacCode = request()->input('hsn_sac_code');
        $amount = $request->input('amount');
        $tax = $request->input('taxes');


        if (trim($items[0]) == '' || trim($items[0]) == '' || trim($cost_per_item[0]) == '') {
            return Reply::error(__('messages.addItem'));
        }

        foreach ($quantity as $qty) {
            if (!is_numeric($qty) && $qty < 1) {
                return Reply::error(__('messages.quantityNumber'));
            }
        }

        foreach ($cost_per_item as $rate) {
            if (!is_numeric($rate)) {
                return Reply::error(__('messages.unitPriceNumber'));
            }
        }

        foreach ($amount as $amt) {
            if (!is_numeric($amt)) {
                return Reply::error(__('messages.amountNumber'));
            }
        }

        foreach ($items as $itm) {
            if (is_null($itm)) {
                return Reply::error(__('messages.itemBlank'));
            }
        }


        $estimate = Estimate::findOrFail($id);
        $estimate->client_id = $request->client_id;
        $estimate->valid_till = Carbon::createFromFormat($this->global->date_format, $request->valid_till)->format('Y-m-d');
        $estimate->sub_total = round($request->sub_total, 2);
        $estimate->total = round($request->total, 2);
        $estimate->currency_id = $request->currency_id;
        $estimate->status = $request->status;
        $estimate->discount = round($request->discount_value, 2);
        $estimate->discount_type = $request->discount_type;
        $estimate->note = $request->note;
        $estimate->save();

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $estimate->updateCustomFieldData($request->get('custom_fields_data'));
        }

        // delete and create new
        EstimateItem::where('estimate_id', $estimate->id)->delete();

        foreach ($items as $key => $item) :
            EstimateItem::create(
                [
                    'estimate_id' => $estimate->id,
                    'item_name' => $item,
                    'item_summary' => $itemsSummary[$key],
                    'hsn_sac_code' => (isset($hsnSacCode[$key]) && !is_null($hsnSacCode[$key])) ? $hsnSacCode[$key] : null,
                    'quantity' => $quantity[$key],
                    'unit_price' => round($cost_per_item[$key], 2),
                    'amount' => round($amount[$key], 2),
                    'taxes' => $tax ? array_key_exists($key, $tax) ? json_encode($tax[$key]) : null : null
                ]
            );
        endforeach;

        return Reply::redirect(route('admin.estimates.index'), __('messages.estimateUpdated'));
    }

    public function destroy($id)
    {
        $firstEstimate = Estimate::orderBy('id', 'desc')->first();
        if ($firstEstimate->id == $id) {
            Estimate::destroy($id);
            return Reply::success(__('messages.estimateDeleted'));
        } else {
            return Reply::error(__('messages.estimateCanNotDeleted'));
        }
    }

    public function domPdfObjectForDownload($id)
    {
        $this->estimate = Estimate::findOrFail($id);
        if ($this->estimate->discount > 0) {
            if ($this->estimate->discount_type == 'percent') {
                $this->discount = (($this->estimate->discount / 100) * $this->estimate->sub_total);
            } else {
                $this->discount = $this->estimate->discount;
            }
        } else {
            $this->discount = 0;
        }
        $taxList = array();

        $items = EstimateItem::whereNotNull('taxes')
            ->where('estimate_id', $this->estimate->id)
            ->get();
        $this->invoiceSetting = InvoiceSetting::first();
        foreach ($items as $item) {
            if ($this->estimate->discount > 0 && $this->estimate->discount_type == 'percent') {
                $item->amount = $item->amount - (($this->estimate->discount / 100) * $item->amount);
            }
            foreach (json_decode($item->taxes) as $tax) {
                $this->tax = EstimateItem::taxbyid($tax)->first();
                if ($this->tax) {
                    if (!isset($taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'])) {
                        $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = ($this->tax->rate_percent / 100) * $item->amount;
                    } else {
                        $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] + (($this->tax->rate_percent / 100) * $item->amount);
                    }
                }
            }
        }

        $this->taxes = $taxList;

        $this->settings = $this->global;

        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        App::setLocale($this->invoiceSetting->locale);
        Carbon::setLocale($this->invoiceSetting->locale);
        $this->invoice = $this->estimate;

        $pdf->loadView('estimate.' . $this->estimate->activeTemplate(), $this->data);
        // $pdf->loadView('admin.estimates.estimate-pdf', $this->data);

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(530, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));

        $filename = $this->estimate->estimate_number;

        return [
            'pdf' => $pdf,
            'fileName' => $filename
        ];
    }

    public function download($id)
    {
      
        $pdfOption = $this->domPdfObjectForDownload($id);
        $pdf = $pdfOption['pdf'];
        $filename = $pdfOption['fileName'];
        return $pdf->download($filename . '.pdf');
    }

    public function sendEstimate($id)
    {
        $estimate = Estimate::findOrFail($id);
        if(!is_null($estimate->client)){
            $estimate->client->notify(new NewEstimate($estimate));
        }
        
        $estimate->send_status = 1;
        if ($estimate->status == 'draft') {
            $estimate->status = 'waiting';
        }
        $estimate->save();
        return Reply::success(__('messages.estimateSend'));

    }

    public function changeStatus(Request $request, $id)
    {
        $estimate = Estimate::findOrFail($id);
        $estimate->status = 'canceled';
        $estimate->save();

        return Reply::success(__('messages.updateSuccess'));
    }
    
}
