<?php

namespace App\Http\Controllers\Admin;

use App\Currency;
use App\GlobalSetting;
use App\Helper\Reply;
use App\Http\Requests\Currency\StoreCurrency;
use App\Http\Requests\Currency\StoreCurrencyExchangeKey;
use App\Http\Requests\Currency\UpdateCurrency;
use App\Traits\CurrencyExchange;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CurrencySettingController extends AdminBaseController
{
    use CurrencyExchange;

    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-settings';
        $this->pageTitle = 'app.menu.currencySettings';
    }

    public function index()
    {
        $this->currencies = Currency::withoutGlobalScopes(['enable'])->get();
        return view('admin.currencies.index', $this->data);
    }

    public function create()
    {
        return view('admin.currencies.create', $this->data);
    }

    public function edit($id)
    {
        $this->currency = Currency::withoutGlobalScope('enable')->findOrFail($id);

        return view('admin.currencies.edit', $this->data);
    }

    public function store(StoreCurrency $request)
    {
        $currency = new Currency();
        $currency->currency_name = $request->currency_name;
        $currency->currency_symbol = $request->currency_symbol;
        $currency->currency_code = $request->currency_code;
        $currency->usd_price = $request->usd_price;
        $currency->is_cryptocurrency = $request->is_cryptocurrency;
        $currency->currency_position = $request->currency_position;

        $currencyApiKey = GlobalSetting::first()->currency_converter_key;
        $currencyApiKeyVersion = GlobalSetting::first()->currency_key_version;
        $currencyApiKey = ($currencyApiKey) ? $currencyApiKey : env('CURRENCY_CONVERTER_KEY');

        if ($request->is_cryptocurrency == 'no') {
            try {
                // get exchange rate
                $client = new Client();
                $res = $client->request('GET', 'https://' . $currencyApiKeyVersion . '.currconv.com/api/v7/convert?q=' . $this->global->currency->currency_code . '_' . $currency->currency_code . '&compact=ultra&apiKey=' . $currencyApiKey, ['verify' => false]);
                $conversionRate = $res->getBody();
                $conversionRate = json_decode($conversionRate, true);

                if (!empty($conversionRate)) {
                    $currency->exchange_rate = $conversionRate[strtoupper($this->global->currency->currency_code . '_' . $currency->currency_code)];
                }
            } catch (\Exception $th) {
                //throw $th;
            }
        } else {
            try {
                if ($this->global->currency->currency_code != 'USD') {
                    // get exchange rate
                    $client = new Client();
                    $res = $client->request('GET', 'https://' . $currencyApiKeyVersion . '.currconv.com/api/v7/convert?q=' . $this->global->currency->currency_code . '_USD&compact=ultra&apiKey=' . $currencyApiKey, ['verify' => false]);
                    $conversionRate = $res->getBody();
                    $conversionRate = json_decode($conversionRate, true);

                    $usdExchangePrice = $conversionRate[strtoupper($this->global->currency->currency_code) . '_USD'];
                    $currency->exchange_rate = ceil(($currency->usd_price / $usdExchangePrice));
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        $currency->save();

        try {
            $this->updateExchangeRates();
        } catch (\Throwable $th) {
            //throw $th;
        }

        return Reply::redirect(route('admin.currency.index'), __('messages.currencyAdded'));
    }

    public function update(UpdateCurrency $request, $id)
    {
        $currency = Currency::withoutGlobalScope('enable')->findOrFail($id);
        $currency->currency_name = $request->currency_name;
        $currency->currency_symbol = $request->currency_symbol;
        $currency->currency_code = $request->currency_code;
        $currency->exchange_rate = $request->exchange_rate;

        $currency->usd_price = $request->usd_price;
        $currency->is_cryptocurrency = $request->is_cryptocurrency;
        $currency->currency_position = $request->currency_position;
        $currency->status = $request->status;

        $currencyApiKey = GlobalSetting::first()->currency_converter_key;
        $currencyApiKeyVersion = GlobalSetting::first()->currency_key_version;
        $currencyApiKey = ($currencyApiKey) ? $currencyApiKey : env('CURRENCY_CONVERTER_KEY');
        try {
            if ($request->is_cryptocurrency == 'no') {

                $client = new Client();
                $res = $client->request('GET', 'https://' . $currencyApiKeyVersion . '.currconv.com/api/v7/convert?q=' . $this->global->currency->currency_code . '_' . $currency->currency_code . '&compact=ultra&apiKey=' . $currencyApiKey, ['verify' => false]);
                $conversionRate = $res->getBody();
                $conversionRate = json_decode($conversionRate, true);

                if (!empty($conversionRate)) {
                    $currency->exchange_rate = $conversionRate[strtoupper($this->global->currency->currency_code) . '_' . $currency->currency_code];
                }

            } else {

                if ($this->global->currency->currency_code != 'USD') {
                    // get exchange rate
                    $client = new Client();
                    $res = $client->request('GET', 'https://' . $currencyApiKeyVersion . '.currconv.com/api/v7/convert?q=' . $this->global->currency->currency_code . '_USD&compact=ultra&apiKey=' . $currencyApiKey, ['verify' => false]);
                    $conversionRate = $res->getBody();
                    $conversionRate = json_decode($conversionRate, true);

                    $usdExchangePrice = $conversionRate[strtoupper($this->global->currency->currency_code) . '_USD'];
                    $currency->exchange_rate = $usdExchangePrice;
                }
            }

        } catch (\Exception $th) {
            //throw $th;
        }

        $currency->save();


        try {
            $this->updateExchangeRates();
        } catch (\Throwable $th) {
            //throw $th;
        }


        return Reply::redirect(route('admin.currency.index'), __('messages.currencyUpdated'));
    }

    public function destroy($id)
    {
//        if ($this->global->currency_id == $id) {
//            return Reply::error(__('modules.currencySettings.cantDeleteDefault'));
//        }
//        Currency::destroy($id);
//        return Reply::success(__('messages.currencyDeleted'));
    }

    public function exchangeRate($currency)
    {
        $currencyApiKey = GlobalSetting::first()->currency_converter_key;
        $currencyApiKeyVersion = GlobalSetting::first()->currency_key_version;
        $currencyApiKey = ($currencyApiKey != "" && !is_null($currencyApiKey)) ? $currencyApiKey : env('CURRENCY_CONVERTER_KEY');

        try {
            // get exchange rate
            $client = new Client();
            $res = $client->request('GET', 'https://' . $currencyApiKeyVersion . '.currconv.com/api/v7/convert?q=' . $this->global->currency->currency_code . '_' . $currency . '&compact=ultra&apiKey=' . $currencyApiKey, ['verify' => false]);
            $conversionRate = $res->getBody();
            $conversionRate = json_decode($conversionRate, true);
            return $conversionRate[strtoupper($this->global->currency->currency_code) . '_' . $currency];
        } catch (\Exception $th) {
            //throw $th;
        }
    }

    /**
     * @return array
     */
    public function updateExchangeRate()
    {
        try {
            $this->updateExchangeRates();
        } catch (\Throwable $th) {
            //throw $th;
        }
        return Reply::success(__('messages.exchangeRateUpdateSuccess'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function currencyExchangeKey()
    {
        return view('admin.currencies.currency_exchange_key', $this->data);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function currencyExchangeKeyStore(StoreCurrencyExchangeKey $request)
    {
        $this->global->currency_converter_key = $request->currency_converter_key;
        $this->global->save();
        return Reply::success(__('messages.currencyConvertKeyUpdated'));
    }
}
