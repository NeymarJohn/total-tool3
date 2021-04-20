<?php

namespace App\Http\Controllers\SuperAdmin;

use App\GlobalCurrency;
use App\GlobalSetting;
use App\Helper\Reply;
use App\Http\Requests\Currency\StoreCurrency;
use App\Http\Requests\Currency\StoreCurrencyExchangeKey;
use App\Traits\GlobalCurrencyExchange;
use GuzzleHttp\Client;

class SuperAdminCurrencySettingController extends SuperAdminBaseController
{
    use GlobalCurrencyExchange;

    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-settings';
        $this->pageTitle = 'app.menu.currencySettings';
    }

    public function index()
    {
        $this->currencies = GlobalCurrency::all();
        return view('super-admin.currency-settings.index', $this->data);
    }

    public function create()
    {
        return view('super-admin.currency-settings.create', $this->data);
    }

    public function edit($id)
    {
        $this->currency = GlobalCurrency::findOrFail($id);
        return view('super-admin.currency-settings.edit', $this->data);
    }

    public function store(StoreCurrency $request)
    {

        $currency = new GlobalCurrency();
        $currency->currency_name = $request->currency_name;
        $currency->currency_symbol = $request->currency_symbol;
        $currency->currency_code = $request->currency_code;
        $currency->usd_price = $request->usd_price;
        $currency->is_cryptocurrency = $request->is_cryptocurrency;
        $currency->currency_position = $request->currency_position;

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
                    $currency->exchange_rate = $conversionRate[strtoupper($this->global->currency->currency_code . '_' . $currency->currency_code)];
                }

            } else {
                if ($this->global->currency->currency_code != 'USD') {
                    // get exchange rate
                    $client = new Client();
                    $res = $client->request('GET', 'https://' . $currencyApiKeyVersion . '.currconv.com/api/v7/convert?q=' . $this->global->currency->currency_code . '_USD&compact=ultra&apiKey=' . $currencyApiKey, ['verify' => false]);
                    $conversionRate = $res->getBody();
                    $conversionRate = json_decode($conversionRate, true);

                    $usdExchangePrice = $conversionRate[strtoupper($this->global->currency->currency_code) . '_USD'];
                    $currency->exchange_rate = ceil(($currency->usd_price / $usdExchangePrice));
                }

            }
        } catch (\Exception $th) {
            //throw $th;
        }
        session()->forget('global_currency_position');
        $currency->save();

        try {
            $this->updateExchangeRates();
        } catch (\Throwable $th) {
            //throw $th;
        }

        return Reply::redirect(route('super-admin.currency.edit', $currency->id), __('messages.currencyAdded'));
    }

    public function update(StoreCurrency $request, $id)
    {
        $currency = GlobalCurrency::findOrFail($id);
        $currency->currency_name = $request->currency_name;
        $currency->currency_symbol = $request->currency_symbol;
        $currency->currency_code = $request->currency_code;
        $currency->exchange_rate = $request->exchange_rate;
        $currency->currency_position = $request->currency_position;
        $currencyApiKey = GlobalSetting::first()->currency_converter_key;
        $currencyApiKeyVersion = GlobalSetting::first()->currency_key_version;
        $currencyApiKey = ($currencyApiKey) ? $currencyApiKey : env('CURRENCY_CONVERTER_KEY');

        $currency->usd_price = $request->usd_price;
        $currency->is_cryptocurrency = $request->is_cryptocurrency;

        if ($request->is_cryptocurrency == 'no') {
            // get exchange rate
            $client = new Client();
            $res = $client->request('GET', 'https://'.$currencyApiKeyVersion.'.currconv.com/api/v7/convert?q=' . $this->global->currency->currency_code . '_' . $currency->currency_code . '&compact=ultra&apiKey=' . $currencyApiKey, ['verify' => false]);
            $conversionRate = $res->getBody();
            $conversionRate = json_decode($conversionRate, true);

            if (!empty($conversionRate)) {
                $currency->exchange_rate = $conversionRate[strtoupper($this->global->currency->currency_code) . '_' . $currency->currency_code];
            }
        } else {

            if ($this->global->currency->currency_code != 'USD') {
                // get exchange rate
                $client = new Client();
                $res = $client->request('GET', 'https://'.$currencyApiKeyVersion.'.currconv.com/api/v7/convert?q=' . $this->global->currency->currency_code . '_USD&compact=ultra&apiKey=' . $currencyApiKey, ['verify' => false]);
                $conversionRate = $res->getBody();
                $conversionRate = json_decode($conversionRate, true);

                $usdExchangePrice = $conversionRate[strtoupper($this->global->currency->currency_code) . '_USD'];
                $currency->exchange_rate = $usdExchangePrice;
            }
        }

        $currency->save();
        session()->forget('global_currency_position');

        try {
            $this->updateExchangeRates();
        } catch (\Throwable $th) {
            //throw $th;
        }


        return Reply::success(__('messages.currencyUpdated'));
    }

    public function destroy($id)
    {
        if ($this->global->currency_id == $id) {
            return Reply::error(__('modules.currencySettings.cantDeleteDefault'));
        }
        session()->forget('global_currency_position');
        GlobalCurrency::destroy($id);
        return Reply::success(__('messages.currencyDeleted'));
    }

    public function exchangeRate($currency)
    {
        $currencyApiKey = GlobalSetting::first()->currency_converter_key;
        $currencyApiKeyVersion = GlobalSetting::first()->currency_key_version;
        $currencyApiKey = ($currencyApiKey) ? $currencyApiKey : env('CURRENCY_CONVERTER_KEY');

        // get exchange rate
        $client = new Client();
        $res = $client->request('GET', 'https://'.$currencyApiKeyVersion.'.currconv.com/api/v7/convert?q=' . $this->global->currency->currency_code . '_' . $currency . '&compact=ultra&apiKey=' . $currencyApiKey, ['verify' => false]);
        $conversionRate = $res->getBody();
        $conversionRate = json_decode($conversionRate, true);

        return $conversionRate[strtoupper($this->global->currency->currency_code) . '_' . $currency];
    }

    public function updateExchangeRate()
    {
        try {
            $this->updateExchangeRates();
        } catch (\Throwable $th) {
            //throw $th;
        }
        return Reply::success(__('messages.exchangeRateUpdateSuccess'));
    }

    public function currencyExchangeKey()
    {
        return view('super-admin.currency-settings.currency_exchange_key', $this->data);
    }

    public function currencyExchangeKeyStore(StoreCurrencyExchangeKey $request)
    {
        $this->global->currency_converter_key = $request->currency_converter_key;
        $this->global->currency_key_version = $request->currency_key_version;
        $this->global->save();
        return Reply::success(__('messages.currencyConvertKeyUpdated'));
    }
}
