<?php

namespace App\Http\Controllers\Client;

use App\ClientPayment;
use App\Helper\Paypal\PaypalIPN;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Invoice;
use App\PaymentGatewayCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use PayPal\Api\Agreement;
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\ShippingAddress;
use PayPal\Common\PayPalModel;
use Validator;
use URL;
use Session;
use Redirect;
use Illuminate\Support\Facades\Input;

/** All Paypal Details class **/

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use Carbon\Carbon;

class PaypalController extends Controller
{
    private $_api_context;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $credential = PaymentGatewayCredentials::first();

        /** setup PayPal api context **/
        config(['paypal.settings.mode' => $credential->paypal_mode]);
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($credential->paypal_client_id, $credential->paypal_secret));
        $this->_api_context->setConfig($paypal_conf['settings']);

        $this->pageTitle = 'Paypal';
    }

    /**
     * Show the application paywith paypalpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function payWithPaypal()
    {
        return view('paywithpaypal', $this->data);
    }

    /**
     * Store a details of payment with paypal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paymentWithpaypal(Request $request, $invoiceId)
    {
        $redirectRoute = 'client.invoices.show';
        $id = $invoiceId;

        return $this->makePaypalPayment($request, $invoiceId, $redirectRoute, $id);
    }

    public function paymentWithpaypalPublic(Request $request, $invoiceId)
    {
        $redirectRoute = 'client.invoices.show';
        $id = md5($invoiceId);

        return $this->makePaypalPayment($request, $invoiceId, $redirectRoute, $id);
    }

    private function makePaypalPayment($request, $invoiceId, $redirectRoute, $id)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        if ($invoice->recurring == 'no') {
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $item_1 = new Item();

            $item_1->setName('Payment for invoice #' . $invoice->invoice_number)
                /** item name **/
                ->setCurrency($invoice->currency->currency_code)
                ->setQuantity(1)
                ->setPrice($invoice->amountDue());
            /** unit price **/

            $item_list = new ItemList();
            $item_list->setItems(array($item_1));

            $amount = new Amount();
            $amount->setCurrency($invoice->currency->currency_code)
                ->setTotal($invoice->amountDue());

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription($this->global->companyName . ' payment for invoice #' . $invoice->invoice_number);

            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(route('client.status'))
                /** Specify return URL **/
                ->setCancelUrl(route('client.status'));

            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            /** dd($payment->create($this->_api_context));exit; **/

            $credential = PaymentGatewayCredentials::first();

            try {

                config(['paypal.secret' => $credential->paypal_secret]);
                config(['paypal.settings.mode' => $credential->paypal_mode]);
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                if (\Config::get('app.debug')) {
                    \Session::put('error', 'Connection timeout');
                    return Redirect::route($redirectRoute, $id);
                    /** echo "Exception: " . $ex->getMessage() . PHP_EOL; **/
                    /** $err_data = json_decode($ex->getData(), true); **/
                    /** exit; **/
                } else {
                    \Session::put('error', __('messages.errorOccured'));
                    return Redirect::route($redirectRoute, $id);
                    /** die(__('messages.errorOccured')); **/
                }
            }

            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }

            /** add payment ID to session **/
            Session::put('paypal_payment_id', $payment->getId());
            Session::put('invoice_id', $invoice->id);
            Session::put('enc_invoice_id', $id);

            //        Save details in database and redirect to paypal
            $clientPayment = new ClientPayment();
            $clientPayment->currency_id = $invoice->currency_id;
            $clientPayment->amount = $invoice->total;
            $clientPayment->transaction_id = $payment->getId();
            $clientPayment->gateway = 'PayPal';
            $clientPayment->status = 'pending';
            if (!is_null($invoice->project_id)) {
                $clientPayment->project_id = $invoice->project_id;
            }
            $clientPayment->project_id = $invoice->project_id;
            $clientPayment->save();

            if (isset($redirect_url)) {
                /** redirect to paypal **/
                return Redirect::away($redirect_url);
            }

            \Session::put('error', 'Unknown error occurred');
            return Redirect::route($redirectRoute, $id);
        } else {

            $plan = new Plan();
            $plan->setName('#' . $invoice->invoice_number)
                ->setDescription('Payment for invoice #' . $invoice->invoice_number)
                ->setType('fixed');

            $paymentDefinition = new PaymentDefinition();
            $paymentDefinition->setName('Payment for invoice #' . $invoice->invoice_number)
                ->setType('REGULAR')
                ->setFrequency(strtoupper($invoice->billing_frequency))
                ->setFrequencyInterval($invoice->billing_interval)
                ->setCycles($invoice->billing_cycle - 1)
                ->setAmount(new Currency(array('value' => $invoice->total, 'currency' => $invoice->currency->currency_code)));

            $merchantPreferences = new MerchantPreferences();
            $merchantPreferences->setReturnUrl(route('client.paypal-recurring') . "?success=true&invoice_id=" . $invoiceId)
                ->setCancelUrl(route('client.paypal-recurring') . "?success=false&invoice_id=" . $invoiceId)
                ->setAutoBillAmount("yes")
                ->setInitialFailAmountAction("CONTINUE")
                ->setMaxFailAttempts("0")
                ->setSetupFee(new Currency(array('value' => $invoice->total, 'currency' => $invoice->currency->currency_code)));

            $plan->setPaymentDefinitions(array($paymentDefinition));
            $plan->setMerchantPreferences($merchantPreferences);

            try {
                $output = $plan->create($this->_api_context);
            } catch (Exception $ex) {
                if (\Config::get('app.debug')) {
                    \Session::put('error', 'Connection timeout');
                    return Redirect::route($redirectRoute, $id);
                } else {
                    \Session::put('error', __('messages.errorOccured'));
                    return Redirect::route($redirectRoute, $id);
                }
            }

            try {
                $patch = new Patch();
                $value = new PayPalModel('{
                   "state":"ACTIVE"
                 }');
                $patch->setOp('replace')
                    ->setPath('/')
                    ->setValue($value);
                $patchRequest = new PatchRequest();
                $patchRequest->addPatch($patch);
                $output->update($patchRequest, $this->_api_context);
                $newPlan = Plan::get($output->getId(), $this->_api_context);
            } catch (Exception $ex) {
                if (\Config::get('app.debug')) {
                    \Session::put('error', 'Connection timeout');
                    return Redirect::route($redirectRoute, $id);
                } else {
                    \Session::put('error', __('messages.errorOccured'));
                    return Redirect::route($redirectRoute, $id);
                }
            }

            // Calculating next billing date
            $today = Carbon::now();
            if ($invoice->billing_frequency == 'day') {
                $today = $today->addDays($invoice->billing_interval);
            } else if ($invoice->billing_frequency == 'week') {
                $today = $today->addWeeks($invoice->billing_interval);
            } else if ($invoice->billing_frequency == 'month') {
                $today = $today->addMonths($invoice->billing_interval);
            } else if ($invoice->billing_frequency == 'year') {
                $today = $today->addYears($invoice->billing_interval);
            }
            $startingDate = $today->format('Y-m-d\TH:i:s\Z');

            $agreement = new Agreement();
            $agreement->setName('#' . $invoice->invoice_number)
                ->setDescription('Payment for invoice #' . $invoice->invoice_number)
                ->setStartDate("$startingDate");

            $plan1 = new Plan();
            $plan1->setId($newPlan->getId());
            $agreement->setPlan($plan1);

            // Add Payer
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            $agreement->setPayer($payer);

            // ### Create Agreement
            try {
                // Please note that as the agreement has not yet activated, we wont be receiving the ID just yet.
                $agreement = $agreement->create($this->_api_context);

                $approvalUrl = $agreement->getApprovalLink();
            } catch (Exception $ex) {
                if (\Config::get('app.debug')) {
                    \Session::put('error', 'Connection timeout');
                    return Redirect::route($redirectRoute, $id);
                } else {
                    \Session::put('error', __('messages.errorOccured'));
                    return Redirect::route($redirectRoute, $id);
                }
            }
            /** add payment ID to session **/
            Session::put('paypal_payment_id', $newPlan->getId());
            Session::put('invoice_id', $invoice->id);
            Session::put('enc_invoice_id', $id);

            $clientPayment = new ClientPayment();
            $clientPayment->currency_id = $invoice->currency_id;
            $clientPayment->amount = $invoice->total;
            $clientPayment->status = 'complete';
            $clientPayment->plan_id = $newPlan->getId();
            $clientPayment->gateway = 'Paypal';
            $clientPayment->save();

            if (isset($approvalUrl)) {
                /** redirect to paypal **/
                return Redirect::away($approvalUrl);
            }

            \Session::put('error', 'Unknown error occurred');
            return Redirect::route($redirectRoute, $id);
        }
    }

    public function getPaymentStatus(Request $request)
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        $invoice_id = Session::get('invoice_id');
        $enc_invoice_id = Session::get('enc_invoice_id');

        if ($enc_invoice_id == $invoice_id) {
            $redirectRoute = 'client.invoices.show';
        } else {
            $redirectRoute = 'front.invoice';
        }

        $clientPayment =  ClientPayment::where('transaction_id', $payment_id)->first();
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        if (empty($request->PayerID) || empty($request->token)) {
            \Session::put('error', 'Payment failed');
            return redirect(route($redirectRoute, $enc_invoice_id));
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        /** PaymentExecution object includes information necessary **/
        /** to execute a PayPal account payment. **/
        /** The payer_id is added to the request query parameters **/
        /** when the user is redirected from paypal back to your site **/
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        /** dd($result);exit; /** DEBUG RESULT, remove it later **/
        if ($result->getState() == 'approved') {

            /** it's all right **/
            /** Here Write your database logic like that insert record or value in database if you want **/
            $clientPayment->status = 'complete';
            $clientPayment->paid_on = Carbon::now();
            $clientPayment->save();

            $invoice = Invoice::findOrFail($invoice_id);
            $invoice->status = 'paid';
            $invoice->save();

            Session::put('success', 'Payment success');
            return Redirect::route($redirectRoute, $enc_invoice_id);
        }
        Session::put('error', 'Payment failed');

        return Redirect::route($redirectRoute, $enc_invoice_id);
    }

    public function payWithPaypalRecurrring(Request $requestObject)
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        $invoice_id = Session::get('invoice_id');
        $enc_invoice_id = Session::get('enc_invoice_id');

        if ($enc_invoice_id == $invoice_id) {
            $redirectRoute = 'client.invoices.show';
        } else {
            $redirectRoute = 'front.invoice';
        }

        $clientPayment =  ClientPayment::where('plan_id', $payment_id)->first();
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');

        if ($requestObject->get('success') == true && $requestObject->has('token')) {
            $token = $requestObject->get('token');
            $agreement = new Agreement();
            try {
                // ## Execute Agreement
                // Execute the agreement by passing in the token
                $agreement->execute($token, $this->_api_context);

                if ($agreement->getState() == 'Active') {
                    $clientPayment->transaction_id = $agreement->getId();
                    $clientPayment->status = 'complete';
                    $clientPayment->paid_on = Carbon::now();
                    $clientPayment->save();

                    $invoice = Invoice::findOrFail($clientPayment->invoice_id);
                    $invoice->status = 'paid';
                    $invoice->save();

                    \Session::put('success', 'Payment success');
                    return Redirect::route($redirectRoute, $enc_invoice_id);
                }

                \Session::put('error', 'Payment failed');

                return Redirect::route($redirectRoute, $enc_invoice_id);
            } catch (Exception $ex) {
                if (\Config::get('app.debug')) {
                    \Session::put('error', 'Connection timeout');
                    return Redirect::route($redirectRoute, $enc_invoice_id);
                } else {
                    \Session::put('error', __('messages.errorOccured'));
                    return Redirect::route($redirectRoute, $enc_invoice_id);
                }
            }
        } else if ($requestObject->get('fail') == true) {
            \Session::put('error', 'Payment failed');

            return Redirect::route($redirectRoute, $enc_invoice_id);
        } else {
            abort(403);
        }
    }
}
