<?php

namespace App\Http\Controllers\Admin;

use App\AuthorizationInvoice;
use App\AuthorizeSubscription;
use App\GlobalSetting;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Admin\Billing\OfflinePaymentRequest;
use App\Http\Requests\AuthorizePaymentRequest;
use App\Http\Requests\StripePayment\PaymentRequest;
use App\Module;
use App\MollieInvoice;
use App\MollieSubscription;
use App\Notifications\OfflinePackageChangeRequest;
use App\OfflineInvoice;
use App\OfflinePaymentMethod;
use App\OfflinePlanChange;
use App\Package;
use App\PaypalInvoice;
use App\PaystackInvoice;
use App\PaystackSubscription;
use App\RazorpayInvoice;
use App\RazorpaySubscription;
use App\Scopes\CompanyScope;
use App\StripeSetting;
use App\Subscription;
use App\TicketFile;
use App\Traits\MollieSettings;
use App\Traits\PaystackSettings;
use App\Traits\StripeSettings;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Mollie\Laravel\Facades\Mollie;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Stripe\Stripe;
use Unicodeveloper\Paystack\Paystack;
use Yajra\DataTables\Facades\DataTables;
use App\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CompanyUpdatedPlan;
use Razorpay\Api\Api;
use Laravel\Cashier\Exceptions\IncompletePayment;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Payment;
use Stripe\PaymentIntent as StripePaymentIntent;
use App\Country;

class AdminBillingController extends AdminBaseController
{
    use StripeSettings, PaystackSettings, MollieSettings;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.billing';
        $this->setStripConfigs();
        $this->pageIcon = 'icon-book-open';
    }

    public function index(Request $request)
    {

        $this->nextPaymentDate = '-';
        $this->previousPaymentDate = '-';
        $this->stripeSettings = StripeSetting::first();
        $this->subscription = Subscription::where('company_id', company()->id)->first();
        $this->razorPaySubscription = RazorpaySubscription::where('company_id', company()->id)->orderBy('id', 'Desc')->first();
        $this->payStackSubscription = PaystackSubscription::where('company_id', company()->id)->where('status', 'active')->orderBy('id', 'Desc')->first();
        $this->mollieSubscription = MollieSubscription::where('company_id', company()->id)->orderBy('id', 'Desc')->first();
        $this->authorizeSubscription = AuthorizeSubscription::where('company_id', company()->id)->orderBy('id', 'Desc')->first();

        $this->message = '';
        $this->success = '';


        if($request->has('success')){
            $this->success = $request->get('success');
        }

        if($request->has('message')){
            $this->message = $request->get('message');
        }

        $stripe = DB::table("stripe_invoices")
            ->join('packages', 'packages.id', 'stripe_invoices.package_id')
            ->selectRaw('stripe_invoices.id , "Stripe" as method, stripe_invoices.pay_date as paid_on, "" as end_on ,stripe_invoices.next_pay_date, stripe_invoices.created_at')
            ->whereNotNull('stripe_invoices.pay_date')
            ->where('stripe_invoices.company_id', company()->id);

        $razorpay = DB::table("razorpay_invoices")
            ->join('packages', 'packages.id', 'razorpay_invoices.package_id')
            ->selectRaw('razorpay_invoices.id , "Razorpay" as method, razorpay_invoices.pay_date as paid_on, "" as end_on ,razorpay_invoices.next_pay_date, razorpay_invoices.created_at')
            ->whereNotNull('razorpay_invoices.pay_date')
            ->where('razorpay_invoices.company_id', company()->id);

        $paystack = DB::table("paystack_invoices")
            ->join('packages', 'packages.id', 'paystack_invoices.package_id')
            ->selectRaw('paystack_invoices.id , "Paystack" as method, paystack_invoices.pay_date as paid_on, "" as end_on ,paystack_invoices.next_pay_date, paystack_invoices.created_at')
            ->whereNotNull('paystack_invoices.pay_date')
            ->where('paystack_invoices.company_id', company()->id);

        $mollie = DB::table("mollie_invoices")
            ->join('packages', 'packages.id', 'mollie_invoices.package_id')
            ->selectRaw('mollie_invoices.id , "Mollie" as method, mollie_invoices.pay_date as paid_on, "" as end_on ,mollie_invoices.next_pay_date, mollie_invoices.created_at')
            ->whereNotNull('mollie_invoices.pay_date')
            ->where('mollie_invoices.company_id', company()->id);

        $authorize = DB::table("authorize_invoices")
            ->join('packages', 'packages.id', 'authorize_invoices.package_id')
            ->selectRaw('authorize_invoices.id , "Authorize" as method, authorize_invoices.pay_date as paid_on, "" as end_on ,authorize_invoices.next_pay_date, authorize_invoices.created_at')
            ->whereNotNull('authorize_invoices.pay_date')
            ->where('authorize_invoices.company_id', company()->id);

        $allInvoices = DB::table("paypal_invoices")
            ->join('packages', 'packages.id', 'paypal_invoices.package_id')
            ->selectRaw('paypal_invoices.id, "Paypal" as method, paypal_invoices.paid_on, paypal_invoices.end_on,paypal_invoices.next_pay_date,paypal_invoices.created_at')
            ->where('paypal_invoices.status', 'paid')
            ->where('paypal_invoices.company_id', company()->id)
            ->union($stripe)
            ->union($razorpay)
            ->union($paystack)
            ->union($authorize)
            ->union($mollie)
            ->get();

        $this->firstInvoice = $allInvoices->sortByDesc(function ($temp, $key) {
            return Carbon::parse($temp->created_at)->getTimestamp();
        })->first();

        if ($this->firstInvoice) {
            if ($this->firstInvoice->next_pay_date) {
                if ($this->firstInvoice->method == 'Paypal'  && $this->firstInvoice !== null  &&  is_null($this->firstInvoice->end_on)) {
                    $this->nextPaymentDate = Carbon::parse($this->firstInvoice->next_pay_date)->toFormattedDateString();
                }
                if ($this->firstInvoice->method == 'Stripe' && $this->subscription !== null &&  is_null($this->subscription->ends_at)) {
                    $this->nextPaymentDate = Carbon::parse($this->firstInvoice->next_pay_date)->toFormattedDateString();
                }
                if ($this->firstInvoice->method == 'Razorpay' && $this->razorPaySubscription !== null &&  is_null($this->razorPaySubscription->ends_at)) {
                    $this->nextPaymentDate = Carbon::parse($this->firstInvoice->next_pay_date)->toFormattedDateString();
                }
                if ($this->firstInvoice->method == 'Paystack' && $this->payStackSubscription !== null) {
                    $this->nextPaymentDate = Carbon::parse($this->firstInvoice->next_pay_date)->toFormattedDateString();
                }
                if ($this->firstInvoice->method == 'Mollie' && $this->mollieSubscription !== null) {
                    $this->nextPaymentDate = Carbon::parse($this->firstInvoice->next_pay_date)->toFormattedDateString();
                }
                if ($this->firstInvoice->method == 'Authorize' && $this->authorizeSubscription !== null) {
                    $this->nextPaymentDate = Carbon::parse($this->firstInvoice->next_pay_date)->toFormattedDateString();
                }
            }
            if ($this->firstInvoice->paid_on) {
                $this->previousPaymentDate = Carbon::parse($this->firstInvoice->paid_on)->toFormattedDateString();
            }
        }
        $this->paypalInvoice = PaypalInvoice::where('company_id', company()->id)->orderBy('created_at', 'desc')->first();


        return view('admin.billing.index', $this->data);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function data()
    {
        $stripe = DB::table("stripe_invoices")
            ->join('packages', 'packages.id', 'stripe_invoices.package_id')
            ->selectRaw('stripe_invoices.id ,stripe_invoices.invoice_id , packages.name as name, "Stripe" as method,stripe_invoices.amount, stripe_invoices.pay_date as paid_on ,stripe_invoices.next_pay_date,stripe_invoices.created_at')
            ->whereNotNull('stripe_invoices.pay_date')
            ->where('stripe_invoices.company_id', company()->id);

        $razorpay = DB::table("razorpay_invoices")
            ->join('packages', 'packages.id', 'razorpay_invoices.package_id')
            ->selectRaw('razorpay_invoices.id ,razorpay_invoices.invoice_id , packages.name as name, "Razorpay" as method,razorpay_invoices.amount, razorpay_invoices.pay_date as paid_on ,razorpay_invoices.next_pay_date,razorpay_invoices.created_at')
            ->whereNotNull('razorpay_invoices.pay_date')
            ->where('razorpay_invoices.company_id', company()->id);
        $paystack = DB::table("paystack_invoices")
            ->join('packages', 'packages.id', 'paystack_invoices.package_id')
            ->selectRaw('paystack_invoices.id ,"" as invoice_id, packages.name as name, "Paystack" as method,paystack_invoices.amount, paystack_invoices.pay_date as paid_on,paystack_invoices.next_pay_date, paystack_invoices.created_at')
            ->whereNotNull('paystack_invoices.pay_date')
            ->where('paystack_invoices.company_id', company()->id);

        $authorize = DB::table("authorize_invoices")
            ->join('packages', 'packages.id', 'authorize_invoices.package_id')
            ->selectRaw('authorize_invoices.id ,"" as invoice_id, packages.name as name, "Authorize" as method,authorize_invoices.amount, authorize_invoices.pay_date as paid_on,authorize_invoices.next_pay_date, authorize_invoices.created_at')
            ->whereNotNull('authorize_invoices.pay_date')
            ->where('authorize_invoices.company_id', company()->id);

        $paypal = DB::table("paypal_invoices")
            ->join('packages', 'packages.id', 'paypal_invoices.package_id')
            ->selectRaw('paypal_invoices.id,"" as invoice_id, packages.name as name, "Paypal" as method ,paypal_invoices.total as amount, paypal_invoices.paid_on,paypal_invoices.next_pay_date, paypal_invoices.created_at')
            ->where('paypal_invoices.status', 'paid')
            ->where('paypal_invoices.company_id', company()->id);

        $mollie = DB::table("mollie_invoices")
            ->join('packages', 'packages.id', 'mollie_invoices.package_id')
            ->selectRaw('mollie_invoices.id,"" as invoice_id, packages.name as name, "Mollie" as method ,mollie_invoices.amount as amount, mollie_invoices.pay_date  as paid_on,mollie_invoices.next_pay_date, mollie_invoices.created_at')
            ->whereNotNull('mollie_invoices.pay_date')
            ->where('mollie_invoices.company_id', company()->id);

        $offline = DB::table("offline_invoices")
            ->join('packages', 'packages.id', 'offline_invoices.package_id')
            ->selectRaw('offline_invoices.id,"" as invoice_id, packages.name as name, "Offline" as method ,offline_invoices.amount as amount, offline_invoices.pay_date as paid_on,offline_invoices.next_pay_date, offline_invoices.created_at')
            ->where('offline_invoices.company_id', company()->id)
            ->union($paypal)
            ->union($stripe)
            ->union($razorpay)
            ->union($paystack)
            ->union($mollie)
            ->union($authorize)
            ->get();

        $paypalData = $offline->sortByDesc(function ($temp, $key) {
            return Carbon::parse($temp->created_at)->getTimestamp();
        })->all();

        return DataTables::of($paypalData)
            ->editColumn('name', function ($row) {
                return ucfirst($row->name);
            })
            ->editColumn('paid_on', function ($row) {
                if (!is_null($row->paid_on)) {
                    return Carbon::parse($row->paid_on)->format($this->global->date_format);
                }
                return '-';
            })
            ->editColumn('next_pay_date', function ($row) {
                if (!is_null($row->next_pay_date)) {
                    return Carbon::parse($row->next_pay_date)->format($this->global->date_format);
                }
                return '-';
            })
            ->addColumn('action', function ($row) {
                if ($row->method == 'Stripe' && $row->invoice_id) {
                    return '<a href="' . route('admin.stripe.invoice-download', $row->invoice_id) . '" class="btn btn-primary btn-circle waves-effect" data-toggle="tooltip" data-original-title="Download"><span></span> <i class="fa fa-download"></i></a>';
                }

                if ($row->method == 'Paypal') {
                    return '<a href="' . route('admin.paypal.invoice-download', $row->id) . '" class="btn btn-primary btn-circle waves-effect" data-toggle="tooltip" data-original-title="Download"><span></span> <i class="fa fa-download"></i></a>';
                }

                if ($row->method == 'Razorpay') {
                    return '<a href="' . route('admin.billing.razorpay-invoice-download', $row->id) . '" class="btn btn-primary btn-circle waves-effect" data-toggle="tooltip" data-original-title="Download"><span></span> <i class="fa fa-download"></i></a>';
                }

                if ($row->method == 'Offline') {
                    return '<a href="' . route('admin.billing.offline-invoice-download', $row->id) . '" class="btn btn-primary btn-circle waves-effect" data-toggle="tooltip" data-original-title="Download"><span></span> <i class="fa fa-download"></i></a>';
                }

                if ($row->method == 'Paystack') {
                    return '<a href="' . route('admin.billing.paystack-invoice-download', $row->id) . '" class="btn btn-primary btn-circle waves-effect" data-toggle="tooltip" data-original-title="Download"><span></span> <i class="fa fa-download"></i></a>';
                }

                if ($row->method == 'Mollie') {
                    return '<a href="' . route('admin.billing.mollie-invoice-download', $row->id) . '" class="btn btn-primary btn-circle waves-effect" data-toggle="tooltip" data-original-title="Download"><span></span> <i class="fa fa-download"></i></a>';
                }

                if ($row->method == 'Authorize') {
                    return '<a href="' . route('admin.billing.authorize-invoice-download', $row->id) . '" class="btn btn-primary btn-circle waves-effect" data-toggle="tooltip" data-original-title="Download"><span></span> <i class="fa fa-download"></i></a>';
                }

                return '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function packages()
    {
        $this->packages = Package::where('default', 'no')->where('is_private', 0)->get();
        $this->modulesData = Module::all();
        $this->stripeSettings = StripeSetting::first();
        $this->offlineMethods = OfflinePaymentMethod::withoutGlobalScope(CompanyScope::class)->whereNull('company_id')->where('status', 'yes')->count();
        $this->pageTitle = 'app.menu.packages';
        $this->company = company();
        return view('admin.billing.package', $this->data);
    }

    public function payment(PaymentRequest $request)
    {
//        $this->setStripConfigs();
        $token = $request->payment_method;
        $email = $request->stripeEmail;
        $plan =  Package::find($request->plan_id);


        $stripe = DB::table("stripe_invoices")
            ->join('packages', 'packages.id', 'stripe_invoices.package_id')
            ->selectRaw('stripe_invoices.id , "Stripe" as method, stripe_invoices.pay_date as paid_on ,stripe_invoices.next_pay_date')
            ->whereNotNull('stripe_invoices.pay_date')
            ->where('stripe_invoices.company_id', company()->id);

        $razorpay = DB::table("razorpay_invoices")
            ->join('packages', 'packages.id', 'razorpay_invoices.package_id')
            ->selectRaw('razorpay_invoices.id ,"Razorpay" as method, razorpay_invoices.pay_date as paid_on ,razorpay_invoices.next_pay_date')
            ->whereNotNull('razorpay_invoices.pay_date')
            ->where('razorpay_invoices.company_id', company()->id);

        $allInvoices = DB::table("paypal_invoices")
            ->join('packages', 'packages.id', 'paypal_invoices.package_id')
            ->selectRaw('paypal_invoices.id, "Paypal" as method, paypal_invoices.paid_on,paypal_invoices.next_pay_date')
            ->where('paypal_invoices.status', 'paid')
            ->whereNull('paypal_invoices.end_on')
            ->where('paypal_invoices.company_id', company()->id)
            ->union($stripe)
            ->union($razorpay)
            ->get();

        $firstInvoice = $allInvoices->sortByDesc(function ($temp, $key) {
            return Carbon::parse($temp->paid_on)->getTimestamp();
        })->first();

        $subcriptionCancel = true;

        if (!is_null($firstInvoice) && $firstInvoice->method == 'Paypal') {
            $subcriptionCancel = $this->cancelSubscriptionPaypal();
        }
        if (!is_null($firstInvoice) && $firstInvoice->method == 'Razorpay') {
            $subcriptionCancel = $this->cancelSubscriptionPaypal();
        }

        if ($subcriptionCancel) {
            if ($plan->max_employees < $this->company->employees->count()) {
                return back()->withError('You can\'t downgrade package because your employees length is ' . $this->company->employees->count() . ' and package max employees lenght is ' . $plan->max_employees)->withInput();
            }

            $company = $this->company;
            $subscription = $company->subscriptions;
            try {
                if ($subscription->count() > 0) {
                    $company->subscription('main')->swap($plan->{'stripe_' . $request->type . '_plan_id'});
                } else {

                    $company->newSubscription('main', $plan->{'stripe_' . $request->type . '_plan_id'})->create($token, [
                        'email' => $email,
                    ]);
                }

                $company = $this->company;

                $company->package_id = $plan->id;
                $company->package_type = $request->type;

                // Set company status active
                $company->status = 'active';
                $company->licence_expire_on = null;

                $company->save();

                //send superadmin notification
                $generatedBy = User::withoutGlobalScopes([CompanyScope::class, 'active'])->whereNull('company_id')->get();
                $allAdmins = User::frontAllAdmins($company->id);
                Notification::send($generatedBy, new CompanyUpdatedPlan($company, $plan->id));
                Notification::send($allAdmins, new CompanyUpdatedPlan($company, $plan->id));

                \Session::flash('message', __('messages.paymentSuccessfullyDone'));
                //                return Reply::success('Payment successfully done.');
                return Redirect::route('admin.billing');
            } catch (IncompletePayment $exception) {
                return view('cashier::payment', [
                    'stripeKey' => config('cashier.key'),
                    'payment' => new Payment(
                        StripePaymentIntent::retrieve($exception->payment->id, Cashier::stripeOptions())
                    ),
                    'redirect' => route('admin.billing'),
                ]);
            }

        }
        //        return back()->withError('User not found by ID ' . $request->input('user_id'))->withInput();
    }

    public function download(Request $request, $invoiceId)
    {
        $this->setStripConfigs();

        return $this->company->downloadInvoice($invoiceId, [
            'vendor'  => $this->company->company_name,
            'product' => $this->company->package->name,
            'global' => GlobalSetting::first(),
            'logo' => $this->company->logo,
        ]);
    }

    public function cancelSubscriptionPaypal()
    {
        $credential = StripeSetting::first();
        $paypal_conf = Config::get('paypal');
        $api_context = new ApiContext(new OAuthTokenCredential($credential->paypal_client_id, $credential->paypal_secret));
        $api_context->setConfig($paypal_conf['settings']);

        $paypalInvoice = PaypalInvoice::whereNotNull('transaction_id')->whereNull('end_on')
            ->where('company_id', company()->id)->where('status', 'paid')->first();

        if ($paypalInvoice) {
            $agreementId = $paypalInvoice->transaction_id;
            $agreement = new Agreement();

            $agreement->setId($agreementId);
            $agreementStateDescriptor = new AgreementStateDescriptor();
            $agreementStateDescriptor->setNote("Cancel the agreement");

            try {
                $agreement->cancel($agreementStateDescriptor, $api_context);
                $cancelAgreementDetails = Agreement::get($agreement->getId(), $api_context);

                // Set subscription end date
                $paypalInvoice->end_on = Carbon::parse($cancelAgreementDetails->agreement_details->final_payment_date)->format('Y-m-d H:i:s');
                $paypalInvoice->save();
            } catch (Exception $ex) {
                //\Session::put('error','Some error occur, sorry for inconvenient');
                return false;
            }

            return true;
        }
    }

    public function cancelSubscriptionRazorpay()
    {
        $credential = StripeSetting::first();
        $apiKey    = $credential->razorpay_key;
        $secretKey = $credential->razorpay_secret;
        $api       = new Api($apiKey, $secretKey);

        // Get subscription for unsubscribe
        $subscriptionData = RazorpaySubscription::where('company_id', company()->id)->whereNull('ends_at')->first();

        if ($subscriptionData) {
            try {
                //                  $subscriptions = $api->subscription->all();
                $subscription  = $api->subscription->fetch($subscriptionData->subscription_id);
                if ($subscription->status == 'active') {

                    // unsubscribe plan
                    $subData = $api->subscription->fetch($subscriptionData->subscription_id)->cancel(['cancel_at_cycle_end' => 0]);

                    // plan will be end on this date
                    $subscriptionData->ends_at = \Carbon\Carbon::createFromTimestamp($subData->end_at)->format('Y-m-d');
                    $subscriptionData->save();
                }
            } catch (Exception $ex) {
                return false;
            }
            return true;
        }
    }

    public function cancelSubscription(Request $request)
    {
        $type = $request->type;
        $credential = StripeSetting::first();
        if ($type == 'paypal') {
            $paypal_conf = Config::get('paypal');
            $api_context = new ApiContext(new OAuthTokenCredential($credential->paypal_client_id, $credential->paypal_secret));
            $api_context->setConfig($paypal_conf['settings']);

            $paypalInvoice = PaypalInvoice::whereNotNull('transaction_id')->whereNull('end_on')
                ->where('company_id', company()->id)->where('status', 'paid')->first();

            if ($paypalInvoice) {
                $agreementId = $paypalInvoice->transaction_id;
                $agreement = new Agreement();
                $paypalInvoice = PaypalInvoice::whereNotNull('transaction_id')->whereNull('end_on')
                    ->where('company_id', company()->id)->where('status', 'paid')->first();

                $agreement->setId($agreementId);
                $agreementStateDescriptor = new AgreementStateDescriptor();
                $agreementStateDescriptor->setNote("Cancel the agreement");

                try {
                    $agreement->cancel($agreementStateDescriptor, $api_context);
                    $cancelAgreementDetails = Agreement::get($agreement->getId(), $api_context);

                    // Set subscription end date
                    $paypalInvoice->end_on = Carbon::parse($cancelAgreementDetails->agreement_details->final_payment_date)->format('Y-m-d H:i:s');
                    $paypalInvoice->save();
                } catch (Exception $ex) {
                    \Session::put('error', 'Some error occur, sorry for inconvenient');
                    return Redirect::route('admin.billing.packages');
                }
            }
        } elseif ($type == 'razorpay') {

            $apiKey    = $credential->razorpay_key;
            $secretKey = $credential->razorpay_secret;
            $api       = new Api($apiKey, $secretKey);

            // Get subscription for unsubscribe
            $subscriptionData = RazorpaySubscription::where('company_id', company()->id)->whereNull('ends_at')->first();
            if ($subscriptionData) {
                try {
                    //                  $subscriptions = $api->subscription->all();
                    $subscription  = $api->subscription->fetch($subscriptionData->subscription_id);
                    if ($subscription->status == 'active') {

                        // unsubscribe plan
                        $subData = $api->subscription->fetch($subscriptionData->subscription_id)->cancel(['cancel_at_cycle_end' => 1]);

                        // plan will be end on this date
                        $subscriptionData->ends_at = \Carbon\Carbon::createFromTimestamp($subData->end_at)->format('Y-m-d');
                        $subscriptionData->save();
                    }
                } catch (Exception $ex) {
                    \Session::put('error', 'Some error occur, sorry for inconvenient');
                    return Redirect::route('admin.billing.packages');
                }
                return Reply::redirectWithError(route('admin.billing.packages'), 'There is no data found for this subscription');
            }
        } elseif ($type == 'paystack') {
            // Get subscription for unsubscribe
            $this->setPaystackConfigs();
            $subscriptionData = PaystackSubscription::where('company_id', company()->id)->where('status', 'active')->first();
            if ($subscriptionData) {
                try {
                    $paystack = new Paystack();
                    $request->code = $subscriptionData->subscription_id;
                    $request->token = $subscriptionData->token;

                    $paystack->disableSubscription();

                    $subscriptionData->status = 'inactive';
                    $subscriptionData->save();
                } catch (Exception $ex) {
                    \Session::put('error', 'Some error occur, sorry for inconvenient');
                    return Redirect::route('admin.billing.packages');
                }
            }
        } elseif ($type == 'mollie') {
            // Get subscription for unsubscribe
            $this->setMollieConfigs();
            $subscriptionData = MollieSubscription::where('company_id', company()->id)->first();
            if ($subscriptionData) {
                try {
                    $customer = Mollie::api()->customers()->get($subscriptionData->customer_id);

                    $subscription = Mollie::api()->subscriptions()->cancelFor($customer, $subscriptionData->subscription_id);

                    $subscriptionData->ends_at = Carbon::now();
                    $subscriptionData->save();
                } catch (\Exception $ex) {

                    \Session::put('error', 'Some error occur, sorry for inconvenient');
                    return Redirect::route('admin.billing.packages');
                }
            }
        }  elseif ($type == 'authorize') {
            // Get subscription for unsubscribe
            $this->setMollieConfigs();
            $subscriptionData = AuthorizeSubscription::where('company_id', company()->id)->first();
            if ($subscriptionData) {
                try {

                    $credential = StripeSetting::first();
                    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();

                    $merchantAuthentication->setName($credential->authorize_api_login_id);
                    $merchantAuthentication->setTransactionKey($credential->authorize_transaction_key);

                    // Set the transaction's refId
                    $refId = 'ref' . time();

                    $request = new AnetAPI\ARBCancelSubscriptionRequest();
                    $request->setMerchantAuthentication($merchantAuthentication);
                    $request->setRefId($refId);
                    $request->setSubscriptionId($subscriptionData->subscription_id);

                    $controller = new AnetController\ARBCancelSubscriptionController($request);

                    if($credential->authorize_environment == 'sandbox') {
                        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
                    } else {
                        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
                    }

                    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
                    {

                        $subscriptionData->ends_at = Carbon::now();
                        $subscriptionData->save();

                    }
                    else
                    {
                        $errorMessages = $response->getMessages()->getMessage();
                        return Reply::error($errorMessages[0]->getText());

                    }


                } catch (\Exception $ex) {

                    \Session::put('error', 'Some error occur, sorry for inconvenient');
                    return Redirect::route('admin.billing.packages');
                }
            }
        } else {
            $this->setStripConfigs();
            $company = company();
            $subscription = Subscription::where('company_id', company()->id)->whereNull('ends_at')->first();
            if ($subscription) {
                try {
                    $company->subscription('main')->cancel();
                } catch (Exception $ex) {
                    \Session::put('error', 'Some error occur, sorry for inconvenient');
                    return Redirect::route('admin.billing.packages');
                }
            }
        }

        return Reply::redirect(route('admin.billing'), __('messages.unsubscribeSuccess'));
    }

    public function selectPackage(Request $request, $packageID)
    {
        $this->setStripConfigs();
        $this->package = Package::findOrFail($packageID);
        $this->free = false;
        if((!round($this->package->monthly_price) > 0 &&  $this->package->default == 'no' ) ||  $this->package->is_free == 1  ){
            $this->free = true;
        }
        $this->company = company();
        $this->type    = $request->type;
        $this->stripeSettings = StripeSetting::first();
        $this->logo = $this->company->logo_url;

        $this->intent =  '';

        if($this->stripeSettings->api_key && $this->stripeSettings->api_secret && $this->stripeSettings->stripe_status === 'active'){
            $this->intent = $this->company->createSetupIntent();
        }
        $this->countries = Country::all();
        $this->methods = OfflinePaymentMethod::withoutGlobalScope(CompanyScope::class)->where('status', 'yes')->whereNull('company_id')->get();
        return View::make('admin.billing.payment-method-show', $this->data);
    }

    public function razorpayPayment(Request $request)
    {
        $credential = StripeSetting::first();

        $apiKey    = $credential->razorpay_key;
        $secretKey = $credential->razorpay_secret;

        $paymentId = request('paymentId');
        $razorpaySignature = $request->razorpay_signature;
        $subscriptionId = $request->subscription_id;

        $api = new Api($apiKey, $secretKey);

        $plan = Package::with('currency')->find($request->plan_id);
        $type = $request->type;

        $expectedSignature = hash_hmac('sha256', $paymentId . '|' . $subscriptionId, $secretKey);

        if ($expectedSignature === $razorpaySignature) {
            if ($plan->max_employees < $this->company->employees->count()) {
                return back()->withError('You can\'t downgrade package because your employees length is ' . $this->company->employees->count() . ' and package max employees lenght is ' . $plan->max_employees)->withInput();
            }

            try {
                $api->payment->fetch($paymentId);

                $payment = $api->payment->fetch($paymentId); // Returns a particular payment

                if ($payment->status == 'authorized') {
                    //TODO::change INR into default currency code
                    $payment->capture(array('amount' => $payment->amount, 'currency' => $plan->currency->currency_code));
                }

                $company = $this->company;

                $company->package_id = $plan->id;
                $company->package_type = $type;

                // Set company status active
                $company->status = 'active';
                $company->licence_expire_on = null;

                $company->save();

                $subscription = new RazorpaySubscription();

                $subscription->subscription_id = $subscriptionId;
                $subscription->company_id      = company()->id;
                $subscription->razorpay_id     = $paymentId;
                $subscription->razorpay_plan   = $type;
                $subscription->quantity        = 1;
                $subscription->save();

                //send superadmin notification
                $generatedBy = User::withoutGlobalScopes([CompanyScope::class, 'active'])->whereNull('company_id')->get();
                $allAdmins = User::frontAllAdmins($company->id);
                Notification::send($generatedBy, new CompanyUpdatedPlan($company, $plan->id));
                Notification::send($allAdmins, new CompanyUpdatedPlan($company, $plan->id));

                return Reply::redirect(route('admin.billing'), __('messages.paymentSuccessfullyDone'));
            } catch (\Exception $e) {
                return back()->withError($e->getMessage())->withInput();
            }
        }
    }

    public function razorpaySubscription(Request $request)
    {
        $credential = StripeSetting::first();

        $plan = Package::find($request->plan_id);
        $type =  $request->type;

        $planID = ($type == 'annual') ? $plan->razorpay_annual_plan_id : $plan->razorpay_monthly_plan_id;

        $apiKey    = $credential->razorpay_key;
        $secretKey = $credential->razorpay_secret;

        $api        = new Api($apiKey, $secretKey);
        $subscription  = $api->subscription->create(array('plan_id' => $planID, 'customer_notify' => 1, 'total_count' => 100));

        return Reply::dataOnly(['subscriprion' => $subscription->id]);
    }

    public function razorpayInvoiceDownload($id)
    {
        $this->invoice = RazorpayInvoice::with(['company', 'currency', 'package'])->findOrFail($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('razorpay-invoice.invoice-1', $this->data);
        $filename = $this->invoice->pay_date->format($this->global->date_format) . '-' . $this->invoice->next_pay_date->format($this->global->date_format);
        return $pdf->download($filename . '.pdf');
    }

    public function offlineInvoiceDownload($id)
    {
        $this->invoice = OfflineInvoice::with(['company', 'package'])->findOrFail($id);
        $pdf = app('dompdf.wrapper');
        $this->generatedBy = $this->superadmin;
        $pdf->loadView('offline-invoice.invoice-1', $this->data);
        $filename = $this->invoice->pay_date->format($this->global->date_format) . '-' . $this->invoice->next_pay_date->format($this->global->date_format);
        return $pdf->download($filename . '.pdf');
    }

    public function paystackInvoiceDownload($id)
    {
        $this->invoice = PaystackInvoice::with(['company', 'package'])->findOrFail($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('paystack-invoice.invoice-1', $this->data);
        $filename = $this->invoice->pay_date->format($this->global->date_format) . '-' . $this->invoice->next_pay_date->format($this->global->date_format);
        return $pdf->download($filename . '.pdf');
    }

    public function mollieInvoiceDownload($id)
    {
        $this->invoice = MollieInvoice::with(['company', 'package'])->findOrFail($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('paystack-invoice.invoice-1', $this->data);
        $filename = $this->invoice->pay_date->format($this->global->date_format) . '-' . $this->invoice->next_pay_date->format($this->global->date_format);
        return $pdf->download($filename . '.pdf');
    }

    public function authorizeInvoiceDownload($id)
    {
        $this->invoice = AuthorizationInvoice::with(['company', 'package'])->findOrFail($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('authorize-invoice.invoice-1', $this->data);
        $filename = $this->invoice->pay_date->format($this->global->date_format) . '-' . $this->invoice->next_pay_date->format($this->global->date_format);
        return $pdf->download($filename . '.pdf');
    }

    public function offlinePayment(Request $request)
    {
        $this->package_id = $request->package_id;
        $this->offlineId = $request->offlineId;
        $this->type = $request->type;

        return \view('admin.billing.offline-payment', $this->data);
    }

    public function offlinePaymentSubmit(OfflinePaymentRequest $request)
    {
        $checkAlreadyRequest = OfflinePlanChange::where('company_id', company()->id)->where('status', 'pending')->first();

        if ($checkAlreadyRequest) {
            return Reply::error('You have already raised a request.');
        }

        $package = Package::find($request->package_id);

        // create offline invoice
        $offlineInvoice = new OfflineInvoice();
        $offlineInvoice->package_id = $request->package_id;
        $offlineInvoice->package_type = $request->type;
        $offlineInvoice->offline_method_id = $request->offline_id;
        $offlineInvoice->amount = $request->type == 'monthly' ? $package->monthly_price : $package->annual_price;
        $offlineInvoice->pay_date = Carbon::now()->format('Y-m-d');
        $offlineInvoice->next_pay_date = $request->type == 'monthly' ? Carbon::now()->addMonth()->format('Y-m-d') : Carbon::now()->addYear()->format('Y-m-d');
        $offlineInvoice->save();

        // create offline plan change request
        $offlinePlanChange = new OfflinePlanChange();
        $offlinePlanChange->package_id = $request->package_id;
        $offlinePlanChange->package_type = $request->type;
        $offlinePlanChange->company_id = company()->id;
        $offlinePlanChange->invoice_id = $offlineInvoice->id;
        $offlinePlanChange->offline_method_id = $request->offline_id;
        $offlinePlanChange->description = $request->description;

        if ($request->hasFile('slip')) {
            $offlinePlanChange->file_name = Files::upload($request->slip, 'offline-payment-files',null,null,false);
        }

        $offlinePlanChange->save();

        return Reply::redirect(route('admin.billing'));
    }
    public function freePlan(Request $request)
    {
        $freeOfflineMethod = OfflinePaymentMethod::where('name', 'free')->first();
        if($freeOfflineMethod){
            // create offline invoice
            $offlineInvoice = new OfflineInvoice();
            $offlineInvoice->package_id = $request->package_id;
            $offlineInvoice->package_type = $request->type;
            $offlineInvoice->offline_method_id = $freeOfflineMethod->id;
            $offlineInvoice->amount = 0;
            $offlineInvoice->pay_date = Carbon::now()->format('Y-m-d');
            $offlineInvoice->status = 'paid';
            $offlineInvoice->next_pay_date = $request->type == 'monthly' ? Carbon::now()->addMonth()->format('Y-m-d') : Carbon::now()->addYear()->format('Y-m-d');
            $offlineInvoice->save();

            // create offline plan change request
            $offlinePlanChange = new OfflinePlanChange();
            $offlinePlanChange->package_id = $request->package_id;
            $offlinePlanChange->package_type = $request->type;
            $offlinePlanChange->company_id = company()->id;
            $offlinePlanChange->invoice_id = $offlineInvoice->id;
            $offlinePlanChange->offline_method_id = $freeOfflineMethod->id;
            $offlinePlanChange->description = 'Free plan';
            $offlinePlanChange->status = 'verified';

            $offlinePlanChange->save();

            $company = company();
            // Change company package
            $company->package_id = $request->package_id;
            $company->package_type = $request->type;
            $company->save();
        }


        return Reply::redirect(route('admin.billing'));
    }
}
