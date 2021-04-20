<?php

namespace App\Http\Controllers\Front;

use App\ClientDetails;
use App\Company;
use App\CreditNotes;
use App\Feature;
use App\FooterMenu;
use App\FrontClients;
use App\FrontDetail;
use App\FrontFaq;
use App\FrontFeature;
use App\GlobalSetting;
use App\Helper\Reply;
use App\Http\Requests\Front\ContactUs\ContactUsRequest;
use App\Http\Requests\Lead\StoreRequest;
use App\Http\Requests\TicketForm\StoreTicket;
use App\Invoice;
use App\InvoiceItems;
use App\Notifications\ContactUsMail;
use App\Package;
use App\PackageSetting;
use App\Payment;
use App\Project;
use App\Proposal;
use App\ProposalItem;
use App\ProposalSign;
use App\Role;
use App\Scopes\CompanyScope;
use App\Setting;
use App\Task;
use App\Testimonials;
use App\Ticket;
use App\TicketCustomForm;
use App\TicketReply;
use App\TicketType;
use App\UniversalSearch;
use App\User;
use Carbon\Carbon;
use Froiden\RestAPI\ApiResponse;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Module;
use App\InvoiceSetting;
use App\Lead;
use App\LeadCustomForm;
use App\LeadStatus;
use App\OfflinePaymentMethod;
use App\PaymentGatewayCredentials;
use App\SeoDetail;
use App\TaskFile;
use App\TrFrontDetail;
use Illuminate\Support\Facades\App;
use Stripe\Stripe;

class HomeController extends FrontBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $lang = $this->localeLanguage ? $this->localeLanguage->id : null;
        $trFrontDetailCount = TrFrontDetail::select('id', 'language_setting_id')->where('language_setting_id', $lang)->count();
        $this->trFrontDetail = TrFrontDetail::where('language_setting_id', $trFrontDetailCount > 0 ? $lang   : null)->first();
        $this->defaultTrFrontDetail = TrFrontDetail::where('language_setting_id', null)->first();
    }

    /**
     * @param null $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($slug = null)
    {
        if ($this->global->setup_homepage == "custom") {
            return response(
                file_get_contents($this->global->custom_homepage_url)
            );
        }

        if ($this->global->setup_homepage == "signup") {
            return $this->loadSignUpPage();
        }

        if ($this->global->setup_homepage == "login") {
            return $this->loadLoginPage();
        }

        $this->seoDetail = SeoDetail::where('page_name', 'home')->first();

        $this->pageTitle = $this->seoDetail ? $this->seoDetail->seo_title : __('app.menu.home');
        $this->packages = Package::where('default', 'no')->where('is_private', 0)->orderBy('sort', 'ASC')->get();

        $imageFeaturesCount = Feature::select('id', 'language_setting_id', 'type')->where(['language_setting_id' => $this->localeLanguage ? $this->localeLanguage->id : null, 'type' => 'image'])->count();
        $iconFeaturesCount = Feature::select('id', 'language_setting_id', 'type')->where(['language_setting_id' => $this->localeLanguage ? $this->localeLanguage->id : null, 'type' => 'icon'])->count();
        $frontClientsCount = FrontClients::select('id', 'language_setting_id')->where('language_setting_id', $this->localeLanguage ? $this->localeLanguage->id : null)->count();
        $testimonialsCount = Testimonials::select('id', 'language_setting_id')->where('language_setting_id', $this->localeLanguage ? $this->localeLanguage->id : null)->count();

        $this->featureWithImages = Feature::where([
            'language_setting_id' => $imageFeaturesCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null,
            'type' => 'image'
        ])->whereNull('front_feature_id')->get();

        $this->featureWithIcons = Feature::where([
            'language_setting_id' => $iconFeaturesCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null,
            'type' => 'icon'
        ])->whereNull('front_feature_id')->get();

        $this->frontClients = FrontClients::where('language_setting_id', $frontClientsCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null)->get();
        $this->testimonials = Testimonials::where('language_setting_id', $testimonialsCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null)->get();

        $this->packageFeaturesModuleData = Module::get();

        $this->packageFeatures   = $this->packageFeaturesModuleData->pluck('module_name')->toArray();
        $this->packageModuleData = $this->packageFeaturesModuleData->pluck('module_name', 'id')->all();

        $moduleActive = [];
        foreach ($this->packageFeatures as $key => $moduleData) {
            foreach ($this->packages as $packageData) {
                $packageModules = (array)json_decode($packageData->module_in_package);

                if (in_array($moduleData, $packageModules)) {
                    $moduleActive[$key] = $moduleData;
                }
            }
        }

        $this->activeModule = $moduleActive;
        // Check if trail is active
        $this->packageSetting = PackageSetting::where('status', 'active')->first();
        $this->trialPackage = Package::where('default', 'trial')->first();


        if ($slug) {
            $this->slugData = FooterMenu::where('slug', $slug)->first();
            $this->pageTitle = ucwords($this->slugData->name);
            return view('saas.footer-page', $this->data);
        }
        if ($this->setting->front_design == 1) {
            return view('saas.home', $this->data);
        }
        return view('front.home', $this->data);
    }

    public function feature()
    {

        $this->seoDetail = SeoDetail::where('page_name', 'feature')->first();

        $this->pageTitle = isset($this->seoDetail) ? $this->seoDetail->seo_title : __('app.menu.features');
        $types = ['task', 'bills', 'team', 'apps'];

        foreach ($types as $type) {
            $featureCount = Feature::select('id', 'language_setting_id', 'type')->where(['language_setting_id' => $this->localeLanguage ? $this->localeLanguage->id : null, 'type' => $type])->count();
            $this->data['feature' . ucfirst(str_plural($type))] = Feature::where([
                'language_setting_id' => $featureCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null,
                'type' => $type
            ])->get();
        }

        $frontClientsCount = FrontClients::select('id', 'language_setting_id')->where('language_setting_id', $this->localeLanguage ? $this->localeLanguage->id : null)->count();
        $this->frontClients = FrontClients::where('language_setting_id', $frontClientsCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null)->get();
        $iconFeaturesCount = Feature::select('id', 'language_setting_id', 'type')->where(['language_setting_id' => $this->localeLanguage ? $this->localeLanguage->id : null, 'type' => 'icon'])->count();

        $this->frontFeatures = FrontFeature::with('features')->where([
            'language_setting_id' => $iconFeaturesCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null,
        ])->get();

        abort_if($this->setting->front_design != 1,403);

        return view('saas.feature', $this->data);
    }

    public function pricing()
    {
        $this->seoDetail = SeoDetail::where('page_name', 'pricing')->first();
        $this->pageTitle = isset($this->seoDetail) ? $this->seoDetail->seo_title : __('app.menu.pricing');
        $this->packages  = Package::where('default', 'no')->where('is_private', 0)
            ->orderBy('sort', 'ASC')
            ->get();

        $frontFaqsCount = FrontFaq::select('id', 'language_setting_id')->where('language_setting_id', $this->localeLanguage ? $this->localeLanguage->id : null)->count();

        $this->frontFaqs = FrontFaq::where('language_setting_id', $frontFaqsCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null)->get();

        $this->packageFeaturesModuleData = Module::get();

        $this->packageFeatures   = $this->packageFeaturesModuleData->pluck('module_name')->toArray();
        $this->packageModuleData = $this->packageFeaturesModuleData->pluck('module_name', 'id')->all();

        $moduleActive = [];
        foreach ($this->packageFeatures as $key => $moduleData) {
            foreach ($this->packages as $packageData) {
                $packageModules = (array)json_decode($packageData->module_in_package);

                if (in_array($moduleData, $packageModules)) {
                    $moduleActive[$key] = $moduleData;
                }
            }
        }

        $this->activeModule = $moduleActive;
        // Check if trail is active
        $this->packageSetting = PackageSetting::where('status', 'active')->first();
        $this->trialPackage = Package::where('default', 'trial')->first();

        abort_if($this->setting->front_design != 1,403);


        return view('saas.pricing', $this->data);
    }

    public function contact()
    {
        $this->seoDetail = SeoDetail::where('page_name', 'contact')->first();
        $this->pageTitle = $this->seoDetail ? $this->seoDetail->seo_title : __('app.menu.contact');

        abort_if($this->setting->front_design != 1,403);

        return view('saas.contact', $this->data);
    }

    public function page($slug = null)
    {

        $this->slugData = FooterMenu::where('slug', $slug)->first();
        abort_if(is_null($this->slugData),404);

        $this->seoDetail = SeoDetail::where('page_name', $this->slugData->slug)->first();
        $this->pageTitle = isset($this->seoDetail) ? $this->seoDetail->seo_title : __('app.menu.contact');

        if ($this->setting->front_design == 1) {
            return view('saas.footer-page', $this->data);
        }
        return view('front.footer-page', $this->data);
    }

    public function contactUs(ContactUsRequest $request)
    {

        $this->pageTitle = 'app.menu.contact';
        $generatedBys = User::allSuperAdmin();
        $frontDetails = FrontDetail::first();
        $this->table = '<table><tbody style="color:#0000009c;">
        <tr>
            <td><p>Name : </p></td>
            <td><p>' . ucwords($request->name) . '</p></td>
        </tr>
        <tr>
            <td><p>Email : </p></td>
            <td><p>' . $request->email . '</p></td>
        </tr>
        <tr>
            <td style="font-family: Avenir, Helvetica, sans-serif;box-sizing: border-box;min-width: 98px;vertical-align: super;"><p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Message : </p></td>
            <td><p>' . $request->message . '</p></td>
        </tr>
</tbody>
        
</table><br>';

        if ($frontDetails->email) {
            Notification::route('mail', $frontDetails->email)
                ->notify(new ContactUsMail($this->data));
        } else {
            Notification::route('mail', $generatedBys)
                ->notify(new ContactUsMail($this->data));
        }


        return Reply::success('Thanks for contacting us. We will catch you soon.');
    }

    public function invoice($id)
    {
        $this->pageTitle = __('app.menu.invoices');
        $this->pageIcon = 'icon-people';

        $this->invoice = Invoice::whereRaw('md5(id) = ?', $id)->with('payment')->firstOrFail();
        App::setLocale(isset($this->invoice->company->locale) ? $this->invoice->company->locale : 'en');
        // public url company session set.
        session(['company' => $this->invoice->company]);
        $this->paidAmount = $this->invoice->getPaidAmount();

        $this->discount = 0;
        if ($this->invoice->discount > 0) {
            $this->discount = $this->invoice->discount;

            if ($this->invoice->discount_type == 'percent') {
                $this->discount = (($this->invoice->discount / 100) * $this->invoice->sub_total);
            }
        }

        $taxList = array();

        $items = InvoiceItems::whereNotNull('taxes')
            ->where('invoice_id', $this->invoice->id)
            ->get();

        foreach ($items as $item) {
            if ($this->invoice->discount > 0 && $this->invoice->discount_type == 'percent') {
                $item->amount = $item->amount - (($this->invoice->discount / 100) * $item->amount);
            }
            foreach (json_decode($item->taxes) as $tax) {
                $this->tax = InvoiceItems::taxbyid($tax)->first();
                if (!isset($taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'])) {
                    $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = ($this->tax->rate_percent / 100) * $item->amount;
                } else {
                    $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] + (($this->tax->rate_percent / 100) * $item->amount);
                }
            }
        }

        $this->taxes = $taxList;

        $this->settings = Company::findOrFail($this->invoice->company_id);
        $this->credentials = PaymentGatewayCredentials::where('company_id', $this->invoice->company_id)->first();

        $this->methods = OfflinePaymentMethod::activeMethod();
        $this->invoiceSetting = InvoiceSetting::first();

        return view('invoice', [
            'companyName' => $this->settings->company_name,
            'pageTitle' => $this->pageTitle,
            'pageIcon' => $this->pageIcon,
            'global' => $this->global,
            'setting' => $this->settings,
            'settings' => $this->settings,
            'invoice' => $this->invoice,
            'paidAmount' => $this->paidAmount,
            'discount' => $this->discount,
            'credentials' => $this->credentials,
            'taxes' => $this->taxes,
            'methods' => $this->methods,
            'invoiceSetting' => $this->invoiceSetting,
        ]);
    }

    public function proposal($id)
    {
        $this->pageTitle = __('app.proposal');
        $this->pageIcon  = 'icon-people';

        $this->proposal = Proposal::with(['items'])->whereRaw('md5(id) = ?', $id)->firstOrFail();
        $this->settings = Company::with(['invoice_setting'])->findOrFail($this->proposal->company_id);
        $this->invoiceSetting = $this->settings->invoice_setting;
        session(['company' => $this->proposal->company]);

        $this->discount = 0;

        $this->taxes = [];
        return view('proposal-front.proposal', [
            'proposal' => $this->proposal,
            'pageTitle' => $this->pageTitle,
            'pageIcon' => $this->pageIcon,
            'settings' => $this->settings,
            'global' => $this->settings,
            'taxes' => $this->taxes,
            'discount' => $this->discount,
            'invoiceSetting' => $this->invoiceSetting,
        ]);
    }

    public function proposalAction(Request $request, $id)
    {
        $this->proposal = Proposal::with(['items'])->whereRaw('md5(id) = ?', $id)->firstOrFail();

        return view('proposal-front.accept', [
            'proposal' => $this->proposal,
            'type' => $request->type
        ]);
    }

    public function proposalActionStore(Request $request, $id)
    {
        $this->proposal = Proposal::whereRaw('md5(id) = ?', $id)->firstOrFail();

        if (!$this->proposal) {
            return Reply::error('you are not authorized to access this.');
        }

        if ($request->type == 'accept') {
            if ($this->proposal->signature_approval == 1) {
                $sign = new ProposalSign();
                $sign->full_name   = $request->name;
                $sign->proposal_id = $this->proposal->id;
                $sign->email       = $request->email;
                $sign->proposal_id = $this->proposal->id;

                $image     = $request->signature;  // your base64 encoded
                $image     = str_replace('data:image/png;base64,', '', $image);
                $image     = str_replace(' ', '+', $image);
                $imageName = str_random(32) . '.' . 'jpg';

                if (!\File::exists(public_path('user-uploads/' . 'proposal/sign'))) {
                    $result = \File::makeDirectory(public_path('user-uploads/proposal/sign'), 0775, true);
                }

                \File::put(public_path() . '/user-uploads/proposal/sign/' . $imageName, base64_decode($image));

                $sign->signature = $imageName;
                $sign->save();
            }

            $this->proposal->status = 'accepted';
        } elseif ($request->status == 'accept') {
        } else {
            $this->proposal->client_comment = $request->comment;
            $this->proposal->status = 'declined';
        }
        $this->proposal->save();

        return Reply::success('Proposal accepted successfully');
    }

    public function stripeModal(Request $request)
    {
        $id = $request->invoice_id;
        $this->invoice = Invoice::with('offline_invoice_payment', 'offline_invoice_payment.payment_method')->where([
            'id' => $id,
            //            'credit_note' => 0
        ])->firstOrFail();

        $this->settings = $this->global;
        $this->credentials = PaymentGatewayCredentials::withoutGlobalScope(CompanyScope::class)
            ->where('company_id', $this->invoice->company_id)
            ->first();

        if ($this->credentials->stripe_secret) {
            Stripe::setApiKey($this->credentials->stripe_secret);

            $total = $this->invoice->total;
            $totalAmount = $total;

            $customer = \Stripe\Customer::create([
                'email' => $this->invoice->client->email,
                'name' => $request->clientName,
                'address' => [
                    'line1' => $request->clientName,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country' => $request->country,
                ],
            ]);

            $intent = \Stripe\PaymentIntent::create([
                'amount' => $totalAmount * 100,
                'currency' => $this->invoice->currency->currency_code,
                'customer' => $customer->id,
                'setup_future_usage' => 'off_session',
                'payment_method_types' => ['card'],
                'description' => $this->invoice->invoice_number . ' Payment',
                'metadata' => ['integration_check' => 'accept_a_payment', 'invoice_id' => $id]
            ]);

            $this->intent = $intent;
        }
        $customerDetail = [
            'email' => $this->invoice->client->email,
            'name' => $request->clientName,
            'line1' => $request->clientName,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
        ];

        $this->customerDetail = $customerDetail;

        $view = view('front.stripe-payment', $this->data)->render();

        return Reply::dataOnly(['view' => $view]);
    }

    public function domPdfObjectForDownload($id)
    {
        $this->invoice = Invoice::whereRaw('md5(id) = ?', $id)->firstOrFail();
        App::setLocale(isset($this->invoice->company->locale) ? $this->invoice->company->locale : 'en');
        $this->paidAmount = $this->invoice->getPaidAmount();
        $this->creditNote = 0;
        if ($this->invoice->credit_note) {
            $this->creditNote = CreditNotes::where('invoice_id', $id)
                ->select('cn_number')
                ->first();
        }

        if ($this->invoice->discount > 0) {
            if ($this->invoice->discount_type == 'percent') {
                $this->discount = (($this->invoice->discount / 100) * $this->invoice->sub_total);
            } else {
                $this->discount = $this->invoice->discount;
            }
        } else {
            $this->discount = 0;
        }

        $taxList = array();

        $items = InvoiceItems::whereNotNull('taxes')
            ->where('invoice_id', $this->invoice->id)
            ->get();

        foreach ($items as $item) {
            if ($this->invoice->discount > 0 && $this->invoice->discount_type == 'percent') {
                $item->amount = $item->amount - (($this->invoice->discount / 100) * $item->amount);
            }
            foreach (json_decode($item->taxes) as $tax) {
                $this->tax = InvoiceItems::taxbyid($tax)->first();
                if (!isset($taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'])) {
                    $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = ($this->tax->rate_percent / 100) * $item->amount;
                } else {
                    $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] + (($this->tax->rate_percent / 100) * $item->amount);
                }
            }
        }

        $this->taxes = $taxList;

        $this->settings = $this->global;

        $this->invoiceSetting = InvoiceSetting::where('company_id', $this->invoice->company_id)->first();
        //        return view('invoices.'.$this->invoiceSetting->template, $this->data);

        $pdf = app('dompdf.wrapper');
        $this->company = $this->invoice->company;
        $this->payments = Payment::with(['offlineMethod'])->where('invoice_id', $this->invoice->id)->where('status', 'complete')->orderBy('paid_on', 'desc')->get();

        $pdf->getDomPDF()->set_option("enable_php", true);
        App::setLocale($this->invoiceSetting->locale);
        Carbon::setLocale($this->invoiceSetting->locale);
        $pdf->loadView('invoices.' . $this->invoiceSetting->template, $this->data);

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(530, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        $filename = $this->invoice->invoice_number;

        return [
            'pdf' => $pdf,
            'fileName' => $filename
        ];
    }

    public function downloadInvoice($id)
    {

        $this->invoice = Invoice::whereRaw('md5(id) = ?', $id)->firstOrFail();
        App::setLocale(isset($this->invoice->company->locale) ? $this->invoice->company->locale : 'en');
        // Download file uploaded
        if ($this->invoice->file != null) {
            return response()->download(storage_path('app/public/invoice-files') . '/' . $this->invoice->file);
        }

        $pdfOption = $this->domPdfObjectForDownload($id);
        $pdf = $pdfOption['pdf'];
        $filename = $pdfOption['fileName'];

        return $pdf->download($filename . '.pdf');
    }

    public function domPdfObjectProposalDownload($id)
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

        $this->settings = $this->proposal->company;

        $pdf = app('dompdf.wrapper');
        $this->company = $this->proposal->company;
        $this->invoiceSetting = InvoiceSetting::where('company_id', $this->proposal->company_id)->first();

        $pdf->getDomPDF()->set_option("enable_php", true);
        App::setLocale($this->company->invoice_setting->locale);
        Carbon::setLocale($this->company->invoice_setting->locale);
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

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadProposal($id)
    {

        $this->proposal = Proposal::whereRaw('md5(id) = ?', $id)->first();

        App::setLocale(isset($this->proposal->company->locale) ? $this->proposal->company->locale : 'en');

        // Download file uploaded
        if ($this->proposal->file != null) {
            return response()->download(storage_path('app/public/proposal-files') . '/' . $this->proposal->file);
        }

        $pdfOption = $this->domPdfObjectProposalDownload($this->proposal->id);
        $pdf = $pdfOption['pdf'];
        $filename = $pdfOption['fileName'];

        return $pdf->download($filename . '.pdf');
    }

    public function app()
    {
        return ['data' => GlobalSetting::select('id', 'company_name')->first()];
    }

    public function gantt($ganttProjectId)
    {
        $this->project = Project::whereRaw('md5(id) = ?', $ganttProjectId)->firstOrFail();
        $this->settings = Setting::findOrFail($this->project->company_id);
        $this->superadmin = GlobalSetting::first();
        $this->ganttProjectId = $ganttProjectId;

        return view('gantt', [
            'ganttProjectId' => $this->ganttProjectId,
            'global' => $this->settings,
            'superadmin' => $this->superadmin,
            'project' => $this->project
        ]);
    }

    public function ganttData($ganttProjectId)
    {
        $tasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')->whereRaw('md5(project_id) = ?', $ganttProjectId)->select('tasks.*')
            ->orderBy('start_date', 'asc')
            ->groupBy('tasks.id')
            ->get();

        $data = array();

        foreach ($tasks as $key => $task) {

            $data[] = [
                'id' => "task-" . $task->id,
                'name' => ucfirst($task->heading),
                'start' => (!is_null($task->start_date)) ? $task->start_date->format('Y-m-d') : $task->due_date->format('Y-m-d'),
                'end' => $task->due_date->format('Y-m-d'),
                'progress' => 0,
                'bg_color' => $task->board_column->label_color,
                'taskid' => $task->id,
                'draggable' => true
            ];

            if (!is_null($task->dependent_task_id)) {
                $data[$key]['dependencies'] = "task-" . $task->dependent_task_id;
            }
        }

        return response()->json($data);
    }

    public function changeLanguage($lang)
    {
        $cookie = Cookie::forever('language', $lang);
        return redirect()->back()->withCookie($cookie);
    }

    public function taskShare($id)
    {
        $this->pageTitle = __('app.task');

        $this->task = Task::with('board_column', 'subtasks', 'project', 'users')->whereRaw('md5(id) = ?', $id)->firstOrFail();
        $this->settings = Setting::findOrFail($this->task->company_id);

        return view('task-share', [
            'task' => $this->task,
            'superadmin' => $this->superadmin,
            'global' => $this->settings
        ]);
    }

    public function taskFiles($id)
    {
        $this->taskFiles = TaskFile::where('task_id', $id)->get();
        return view('task-files', ['taskFiles' => $this->taskFiles]);
    }

    /**
     * load signup page on home
     *
     * @return \Illuminate\Http\Response
     */
    public function loadSignUpPage()
    {
        if (\user()) {
            return redirect(getDomainSpecificUrl(route('login'), \user()->company));
        }
        $this->seoDetail = SeoDetail::where('page_name', 'home')->first();
        $this->pageTitle = 'Sign Up';

        $view = ($this->setting->front_design == 1) ? 'saas.register' : 'front.register';

        $global = GlobalSetting::first();

        if ($global->frontend_disable) {
            $view = 'auth.register';
        }
        $trFrontDetailCount = TrFrontDetail::select('id', 'language_setting_id')->where('language_setting_id', $this->localeLanguage ? $this->localeLanguage->id : null)->count();

        $this->trFrontDetail = TrFrontDetail::where('language_setting_id', $trFrontDetailCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null)->first();
        $this->defaultTrFrontDetail = TrFrontDetail::where('language_setting_id', null)->first();
        return view($view, $this->data);
    }

    /**
     * show login page on home
     *
     * @return \Illuminate\Http\Response
     */
    public function loadLoginPage()
    {
        if (\user()) {
            return redirect(getDomainSpecificUrl(route('login'), \user()->company));
        }

        if (!$this->isLegal()) {
            return redirect('verify-purchase');
        }

        if ($this->global->frontend_disable) {
            return view('auth.login', $this->data);
        }

        if (module_enabled('Subdomain')) {
            $this->pageTitle = __('subdomain::app.core.workspaceTitle');

            $view = ($this->setting->front_design == 1) ? 'subdomain::saas.workspace' : 'subdomain::workspace';
            return view($view, $this->data);
        }

        if ($this->setting->front_design == 1 && $this->setting->login_ui == 1) {
            return view('saas.login', $this->data);
        }
        $this->pageTitle = 'Login Page';
        return view('auth.login', $this->data);
    }

    /**
     * custom lead form
     *
     * @return \Illuminate\Http\Response
     */
    public function leadForm($id)
    {
        $this->pageTitle = 'modules.lead.leadForm';
        $this->leadFormFields = LeadCustomForm::where('status', 'active')
            ->whereRaw('md5(company_id) = ?', $id)
            ->orderBy('field_order', 'asc')
            ->get();

        $this->settings = Setting::whereRaw('md5(id) = ?', $id)->first();
        $this->superadmin = GlobalSetting::first();;

        return view('lead-form', [
            'pageTitle' => $this->pageTitle,
            'leadFormFields' => $this->leadFormFields,
            'global' => $this->settings,
            'superadmin' => $this->superadmin
        ]);
    }

    /**
     * save lead
     *
     * @return \Illuminate\Http\Response
     */
    public function leadStore(StoreRequest $request)
    {
        $setting = \App\Company::findOrFail($request->company_id);

        if ($setting->lead_form_google_captcha) {
            // Checking is google recaptcha is valid
            $gRecaptchaResponseInput = 'g-recaptcha-response';
            $gRecaptchaResponse = $setting->google_captcha_version == "v2" ? $request->{$gRecaptchaResponseInput} : $request->get('recaptcha_token');
            $validateRecaptcha = $this->validateGoogleRecaptcha($gRecaptchaResponse);

            if (!$validateRecaptcha) {
                return Reply::error(__('auth.recaptchaFailed'));
            }
        }

        $leadStatus = LeadStatus::where('default', '1')->first();
        $settings = \App\Setting::find($request->company_id);

        $lead = new Lead();
        $lead->company_name = (request()->has('company_name') ? $request->company_name : '');
        $lead->website      = (request()->has('website') ? $request->website : '');
        $lead->address      = (request()->has('address') ? $request->address : '');
        $lead->client_name  = (request()->has('name') ? $request->name : '');
        $lead->client_email = (request()->has('email') ? $request->email : '');
        $lead->mobile       = (request()->has('mobile') ? $request->mobile : '');
        $lead->status_id    = $leadStatus->id;
        $lead->value        = 0;
        $lead->currency_id  = $settings->currency->id;
        $lead->company_id   = $request->company_id;
        $lead->note         = $request->message;
        $lead->save();

        return Reply::success(__('messages.LeadAddedUpdated'));
    }


    /**
     * custom lead form
     *
     * @return \Illuminate\Http\Response
     */
    public function ticketForm($id)
    {
        $this->pageTitle = 'app.ticketForm';
        $this->ticketFormFields = TicketCustomForm::where('status', 'active')
            ->whereRaw('md5(company_id) = ?', $id)
            ->orderBy('field_order', 'asc')
            ->get();
        $this->types = TicketType::whereRaw('md5(company_id) = ?', $id)->get();
        $this->settings = Setting::whereRaw('md5(id) = ?', $id)->first();
        $this->superadmin = GlobalSetting::first();

        return view('embed-forms.ticket-form', [
            'pageTitle' => $this->pageTitle,
            'ticketFormFields' => $this->ticketFormFields,
            'global' => $this->settings,
            'types' => $this->types,
            'superadmin' => $this->superadmin
        ]);
    }

    /**
     * save lead
     *
     * @return \Illuminate\Http\Response
     */
    public function ticketStore(StoreTicket $request)
    {
        $setting = \App\Company::with('currency')->where('id', $request->company_id)->first();

        if ($setting->ticket_form_google_captcha) {
            // Checking is google recaptcha is valid
            $gRecaptchaResponseInput = 'g-recaptcha-response';
            $gRecaptchaResponse = $setting->google_captcha_version == "v2" ? $request->{$gRecaptchaResponseInput} : $request->get('recaptcha_token');
            $validateRecaptcha = $this->validateGoogleRecaptcha($gRecaptchaResponse);

            if (!$validateRecaptcha) {
                return Reply::error(__('auth.recaptchaFailed'));
            }
        }

        $existing_user = User::withoutGlobalScopes(['active', CompanyScope::class])->select('id', 'email')->where('email', $request->input('email'))->first();
        $newUser = $existing_user;
        if (!$existing_user) {
            $password = str_random(8);
            // create new user
            $client = new User();
            $client->name           = $request->input('name');
            $client->email          = $request->input('email');
            $client->mobile         = $request->input('mobile_number');
            $client->password       = Hash::make($password);
            $client->company_id     = $request->company_id;;
            $client->save();

            // attach role
            $role = Role::where('name', 'client')->first();
            $client->attachRole($role->id);

            $clientDetail = new ClientDetails();
            $clientDetail->user_id      = $client->id;
            $clientDetail->name         = $request->input('name');
            $clientDetail->email        = $request->input('email');
            $clientDetail->mobile       = $request->input('mobile_number');
            $clientDetail->company_id   = $request->company_id;;
            $clientDetail->save();

            // log search
            if (!is_null($client->company_name)) {
                $user_id = $existing_user ? $existing_user->id : $client->id;
                $this->logSearchEntry($user_id, $client->company_name, 'admin.clients.edit', 'client');
            }
            //log search
            $this->logSearchEntry($client->id, $request->name, 'admin.clients.edit', 'client');
            $this->logSearchEntry($client->id, $request->email, 'admin.clients.edit', 'client');
            $newUser = $client;
        }

        // Create New Ticket
        $ticket = new Ticket();
        $ticket->subject        = (request()->has('ticket_subject') ? $request->ticket_subject : '');;
        $ticket->status         = 'open';
        $ticket->user_id        = $newUser->id;
        $ticket->type_id        = (request()->has('type') ? $request->type : null);
        $ticket->priority       = (request()->has('priority') ? $request->priority : null);
        $ticket->company_id     = $request->company_id;
        $ticket->save();

        //save first message
        $reply = new TicketReply();
        $reply->message     = (request()->has('message') ? $request->message : '');
        $reply->ticket_id   = $ticket->id;
        $reply->user_id     = $newUser->id;; //current logged in user
        $reply->company_id  = $request->company_id;
        $reply->save();

        return Reply::success(__('messages.ticketAddSuccess'));
    }

    public function validateGoogleRecaptcha($googleRecaptchaResponse)
    {
        $setting = GlobalSetting::first();

        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' =>
                [
                    'secret' => $setting->google_recaptcha_secret,
                    'response' => $googleRecaptchaResponse,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ]
            ]
        );

        $body = json_decode((string) $response->getBody());

        return $body->success;
    }

    public function logSearchEntry($searchableId, $title, $route, $type)
    {
        $search = new UniversalSearch();
        $search->searchable_id  = $searchableId;
        $search->title          = $title;
        $search->route_name     = $route;
        $search->module_type    = $type;
        $search->save();
    }

    public function installedModule()
    {
        $message = '';
        $plugins = \Nwidart\Modules\Facades\Module::allEnabled();
        $applicationVersion = trim(
            preg_replace(
                '/\s\s+/',
                ' ',
                !file_exists(\File::get(public_path() . '/version.txt')) ?
                    \File::get(public_path() . '/version.txt') : '0'
            )
        );
        $enableModules = [];
        $enableModules['application'] = 'worksuite-saas';
        $enableModules['version'] = $applicationVersion;
        $enableModules['worksuite-saas'] = $applicationVersion;
        foreach ($plugins as $plugin) {
            $enableModules[$plugin->getName()] = trim(
                preg_replace(
                    '/\s\s+/',
                    ' ',
                    !file_exists(\File::get($plugin->getPath() . '/version.txt')) ?
                        \File::get($plugin->getPath() . '/version.txt') : '0'
                )
            );
        }

        if (!Arr::exists($enableModules, 'RestAPI')) {
            $message .= 'Rest API plugin is not installed or enabled';
            $enableModules['message'] = $message;
            return ApiResponse::make('Plugin data fetched successfully', $enableModules);
        }

        if (((int) str_replace('.', '', $enableModules['RestAPI'])) < 110) {
            $message .= 'Please update Rest API module greater then 1.1.0 version';
        }

        if (((int) str_replace('.', '', $enableModules['worksuite-saas'])) < 386) {
            $message .= 'Please update' . ucfirst(config('app.name')) . ' greater then 3.8.6 version';
        }
        $enableModules['message'] = $message;

        return ApiResponse::make('Plugin data fetched successfully', $enableModules);
    }
}
