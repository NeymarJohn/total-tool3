<?php

namespace App\Http\Controllers\Client;

use App\ClientPayment;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorizePaymentRequest;
use App\Invoice;
use App\PaymentGatewayCredentials;
use Carbon\Carbon;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AuthorizeController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->pageTitle = 'Authorize';
    }

    public function handleOnlinePay(AuthorizePaymentRequest $request) {
        $input = $request->input();
        $invoice = Invoice::find($request->invoice_id);

        $credential = PaymentGatewayCredentials::withoutGlobalScope(CompanyScope::class)
            ->where('company_id', $invoice->company_id)
            ->first();

        /* Create a merchantAuthenticationType object with authentication details
          retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($credential->authorize_api_login_id);
        $merchantAuthentication->setTransactionKey($credential->authorize_transaction_key);

        // Set the transaction's refId
        $refId = 'ref' . time();
        $cardNumber = preg_replace('/\s+/', '', $input['card_number']);

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($input['expiration-year'] . "-" .$input['expiration-month']);
        $creditCard->setCardCode($input['cvv']);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($invoice->total);
        $transactionRequestType->setPayment($paymentOne);

        // Assemble the complete transaction request
        $requests = new AnetAPI\CreateTransactionRequest();
        $requests->setMerchantAuthentication($merchantAuthentication);
        $requests->setRefId($refId);
        $requests->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($requests);

        if($credential->authorize_environment == 'sandbox') {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        }

        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {

                    $invoice->status = 'paid';
                    $invoice->save();
                    // Save details in database and redirect to paypal
                    $clientPayment = new ClientPayment();
                    $clientPayment->currency_id = $invoice->currency_id;
                    $clientPayment->amount = $invoice->total;

                    $clientPayment->transaction_id = $tresponse->getTransId();
                    $clientPayment->gateway = 'Authorize.net';
                    $clientPayment->status = 'complete';
                    $clientPayment->paid_on = Carbon::now()->format('Y-m-d H:i:s');

                    $clientPayment->company_id = $invoice->company_id;
                    $clientPayment->invoice_id = $invoice->id;
                    $clientPayment->project_id = $invoice->project_id;
                    $clientPayment->save();

                } else {

                    return Reply::error($tresponse->getErrors()[0]->getErrorText());
                }
                // Or, print errors if the API request wasn't successful
            } else {

                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getErrors() != null) {

                    return Reply::error($tresponse->getErrors()[0]->getErrorText());
                } else {

                    return Reply::error($response->getMessages()->getMessage()[0]->getText());
                }
            }
        } else {
            return Reply::error("No response returned");
        }
        return Reply::success('Invoice Successfully paid.');
    }

}
