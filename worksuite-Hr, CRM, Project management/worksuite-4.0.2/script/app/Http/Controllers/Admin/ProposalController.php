<?php

namespace App\Http\Controllers\Admin;

use App\Currency;
use App\DataTables\Admin\ProposalDataTable;
use App\Events\NewProposalEvent;
use App\Helper\Reply;
use App\Http\Requests\Proposal\StoreRequest;
use App\Invoice;
use App\InvoiceSetting;
use App\Lead;
use App\Notifications\NewProposal;
use App\Proposal;
use App\ProposalItem;
use App\Setting;
use App\Tax;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;

class ProposalController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = __('icon-people');
        $this->pageTitle = 'app.menu.lead';
        $this->middleware(function ($request, $next) {
            if (!in_array('leads', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $this->totalProposals = Proposal::count();
        return view('admin.proposals.index', $this->data);
    }

    public function show(ProposalDataTable $dataTable, $id)
    {
        $this->lead = Lead::where('id', $id)->first();
        return $dataTable->render('admin.proposals.show', $this->data);
    }

    public function detail($id)
    {
        $this->proposal = Proposal::findOrFail($id);
        $this->Leads = Lead::all();
        $this->taxes = Tax::all();
        $this->currencies = Currency::all();

        if ($this->proposal->discount > 0) {
            if ($this->proposal->discount_type == 'percent') {
                $this->discount = (($this->proposal->discount / 100) * $this->proposal->sub_total);
            } else {
                $this->discount = $this->proposal->discount;
            }
        } else {
            $this->discount = 0;
        }

        $taxList = array();

        $items = ProposalItem::whereNotNull('taxes')
            ->where('proposal_id', $this->proposal->id)
            ->get();
        foreach ($items as $item) {
            if ($this->proposal->discount > 0 && $this->proposal->discount_type == 'percent') {
                $item->amount = $item->amount - (($this->proposal->discount / 100) * $item->amount);
            }
            foreach (json_decode($item->taxes) as $tax) {
                $this->tax = ProposalItem::taxbyid($tax)->first();
                if (!isset($taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'])) {
                    $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = ($this->tax->rate_percent / 100) * $item->amount;
                } else {
                    $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] + (($this->tax->rate_percent / 100) * $item->amount);
                }
            }
        }
        $this->taxes = $taxList;

        $this->settings = Setting::organisationSetting();
        return view('admin.proposals.show-proposal', $this->data);
    }

    public function data($id = null)
    {
        $lead = Proposal::select('proposals.id', 'leads.company_name', 'leads.client_name as name', 'leads.client_id', 'total', 'valid_till', 'status', 'currencies.currency_symbol')
            ->join('currencies', 'currencies.id', '=', 'proposals.currency_id')
            ->join('leads', 'leads.id', 'proposals.lead_id');

        if ($id) {
            $lead = $lead->where('proposals.lead_id', $id);
        }
        $lead = $lead->get();

        return DataTables::of($lead)
            ->addColumn('action', function ($row) {
                $convert = '';

                if ($row->status == 'waiting') {
                    $convert = '<li><a href="' . route("admin.proposals.convert-proposal", $row->id) . '" ><i class="fa fa-file"></i> Convert Invoice </a></li>
                                <li><a href="' . route("admin.proposals.edit", $row->id) . '" ><i class="fa fa-pencil"></i> ' . __('modules.proposal.edit') . ' </a></li>';
                }
                return '<div class="btn-group m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-outline  dropdown-toggle waves-effect waves-light" type="button">' . __('modules.proposal.action') . ' <span class="caret"></span></button>
                <ul role="menu" class="dropdown-menu">
                  <li><a href="' . route("admin.proposals.download", $row->id) . '" ><i class="fa fa-download"></i> ' . __('modules.proposal.download') . '</a></li>
                  <li><a target="_blank" href="' . route("front.proposal", md5($row->id)) . '" ><i class="fa fa-link"></i> ' . __('modules.proposal.publicLink') . '</a></li>
                 
                  ' . $convert . '
                  <li><a class="sa-params" href="javascript:;" data-proposal-id="' . $row->id . '"><i class="fa fa-times"></i> ' . __('modules.proposal.delete') . ' </a></li>
                  <li><a class="send-mail" href="javascript:;" data-proposal-id="' . $row->id . '"  ><i class="fa fa-mail-reply"></i> ' . __('modules.proposal.sendProposal') . ' </a></li>
                </ul>
              </div>';
            })
            ->editColumn('name', function ($row) {
                if ($row->client_id) {
                    return '<a href="' . route('admin.clients.show', $row->client_id) . '">' . ucwords($row->name) . '</a>';
                }
                return ucwords($row->name);
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'waiting') {
                    return '<label class="label label-warning">' . strtoupper($row->status) . '</label>';
                }
                if ($row->status == 'declined') {
                    return '<label class="label label-danger">' . strtoupper($row->status) . '</label>';
                } else {
                    return '<label class="label label-success">' . strtoupper($row->status) . '</label>';
                }
            })
            ->editColumn('total', function ($row) {
                return $row->currency_symbol . $row->total;
            })
            ->editColumn(
                'valid_till',
                function ($row) {
                    return Carbon::parse($row->valid_till)->format($this->global->date_format);
                }
            )
            ->addIndexColumn()
            ->rawColumns(['name', 'action', 'status'])
            ->removeColumn('currency_symbol')
            ->removeColumn('client_id')
            ->make(true);
    }

    public function create($leadID = null)
    {
        $this->taxes = Tax::all();
        $this->leads = Lead::all();

        if ($leadID) {
            $this->lead = Lead::findOrFail($leadID);
        }

        $this->currencies = Currency::all();
        return view('admin.proposals.create', $this->data);
    }

    public function sendProposal($id)
    {
        $proposal = Proposal::findOrFail($id);
        $type = 'new';
        event(new NewProposalEvent($proposal, $type));
        return Reply::success( __('messages.proposalSendSuccess'));
    }

    public function store(StoreRequest $request)
    {
        $items = $request->item_name;
        $cost_per_item = $request->cost_per_item;
        $hsnSacCode = request()->input('hsn_sac_code');
        $quantity = $request->quantity;
        $amount = $request->amount;
        $itemsSummary = $request->input('item_summary');
        $tax = $request->input('taxes');
        $type = $request->type;


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

        $proposal = new Proposal();
        $proposal->lead_id = $request->lead_id;
        $proposal->valid_till = Carbon::createFromFormat($this->global->date_format, $request->valid_till)->format('Y-m-d');
        $proposal->sub_total = $request->sub_total;
        $proposal->total = $request->total;
        $proposal->currency_id = $request->currency_id;
        $proposal->note = $request->note;
        $proposal->discount = round($request->discount_value, 2);
        $proposal->discount_type = $request->discount_type;
        $proposal->status = 'waiting';
        $proposal->signature_approval = ($request->require_signature) ? 1 : 0;
        $proposal->save();




        foreach ($items as $key => $item) :
            if (!is_null($item)) {
                ProposalItem::create(
                    [
                        'proposal_id' => $proposal->id,
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

        $this->logSearchEntry($proposal->id, 'Proposal #' . $proposal->id, 'admin.proposals.edit', 'proposal');

        return Reply::redirect(route('admin.proposals.show', $proposal->lead_id), __('messages.proposalCreated'));
    }

    public function edit($id)
    {
        $this->Leads = Lead::all();
        $this->taxes = Tax::all();
        $this->currencies = Currency::all();
        $this->perposal = Proposal::findOrFail($id);
        return view('admin.proposals.edit', $this->data);
    }

    public function update(StoreRequest $request, $id)
    {
        $items = $request->item_name;
        $cost_per_item = $request->cost_per_item;
        $hsnSacCode = request()->input('hsn_sac_code');
        $quantity = $request->quantity;
        $amount = $request->amount;
        $type = $request->type;
        $itemsSummary = $request->input('item_summary');
        $tax = $request->input('taxes');

        if (trim($items[0]) == '' || trim($items[0]) == '' || trim($cost_per_item[0]) == '') {
            return Reply::error(__('messages.addItem'));
        }

        foreach ($quantity as $qty) {
            if (!is_numeric($qty)) {
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

        $proposal = Proposal::findOrFail($id);
        $proposal->lead_id = $request->lead_id;
        $proposal->valid_till = Carbon::createFromFormat($this->global->date_format, $request->valid_till)->format('Y-m-d');
        $proposal->sub_total = $request->sub_total;
        $proposal->total = $request->total;
        $proposal->currency_id = $request->currency_id;
        $proposal->status = $request->status;
        $proposal->note = $request->note;
        $proposal->discount = round($request->discount_value, 2);
        $proposal->discount_type = $request->discount_type;
        $proposal->signature_approval = ($request->require_signature) ? 1 : 0;
        $proposal->save();

        // delete and create new
        ProposalItem::where('proposal_id', $proposal->id)->delete();

        foreach ($items as $key => $item) :
            if (!is_null($item)) {
                ProposalItem::create(
                    [
                        'proposal_id' => $proposal->id,
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

        return Reply::redirect(route('admin.proposals.show', $proposal->lead_id), __('messages.proposalUpdated'));
    }

    public function destroy($id)
    {
        Proposal::destroy($id);
        return Reply::success(__('messages.proposalDeleted'));
    }


    public function download($id)
    {
        $pdfOption = $this->domPdfObjectForDownload($id);
        $pdf = $pdfOption['pdf'];
        $filename = $pdfOption['fileName'];

        return $pdf->download($filename . '.pdf');
    }

    public function domPdfObjectForDownload($id)
    {
        $this->proposal = Proposal::findOrFail($id);
        if ($this->proposal->discount > 0) {
            if ($this->proposal->discount_type == 'percent') {
                $this->discount = (($this->proposal->discount / 100) * $this->proposal->sub_total);
            } else {
                $this->discount = $this->proposal->discount;
            }
        } else {
            $this->discount = 0;
        }

        $this->taxes = ProposalItem::where('type', 'tax')
            ->where('proposal_id', $this->proposal->id)
            ->get();

        $items = ProposalItem::whereNotNull('taxes')
            ->where('proposal_id', $this->proposal->id)
            ->get();

        $taxList = array();

        foreach ($items as $item) {
            if ($this->proposal->discount > 0 && $this->proposal->discount_type == 'percent') {
                $item->amount = $item->amount - (($this->proposal->discount / 100) * $item->amount);
            }
            foreach (json_decode($item->taxes) as $tax) {
                $this->tax = ProposalItem::taxbyid($tax)->first();
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

        $this->settings = Setting::organisationSetting();
        $this->invoiceSetting = InvoiceSetting::first();
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        App::setLocale($this->invoiceSetting->locale);
        Carbon::setLocale($this->invoiceSetting->locale);
        $pdf->loadView('admin.proposals.proposal-pdf', $this->data);

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(530, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        $filename = 'proposal-' . $this->proposal->id;

        return [
            'pdf' => $pdf,
            'fileName' => $filename
        ];
    }

    public function convertProposal($id)
    {
        $this->proposalId = $id;
        $this->invoice = Proposal::with('items', 'lead', 'lead.client')->findOrFail($id);
        $this->lastInvoice = Invoice::lastInvoiceNumber() + 1;
        $this->invoiceSetting = invoice_setting();
        $this->currencies = Currency::all();
        $this->taxes = Tax::all();
        $this->clients = User::allClients();
        $this->zero = '';

        if (!is_null($this->invoice->client_id)) {
            $this->clientDetail = User::findOrFail($this->invoice->client_id);
        }

        if (strlen($this->lastInvoice) < $this->invoiceSetting->invoice_digit) {
            for ($i = 0; $i < $this->invoiceSetting->invoice_digit - strlen($this->lastInvoice); $i++) {
                $this->zero = '0' . $this->zero;
            }
        }
        //        foreach ($this->invoice->items as $items)

        $discount = $this->invoice->items->filter(function ($value, $key) {
            return $value->type == 'discount';
        });

        $tax = $this->invoice->items->filter(function ($value, $key) {
            return $value->type == 'tax';
        });

        $this->totalTax = $tax->sum('amount');
        $this->totalDiscount = $discount->sum('amount');

        return view('admin.proposals.convert_proposal', $this->data);
    }
}
