<?php
/**
 * Created by PhpStorm.
 * User: DEXTER
 * Date: 23/11/17
 * Time: 6:07 PM
 */

namespace App\Traits;

use App\GlobalCurrency;
use App\GlobalSetting;
use GuzzleHttp\Client;

trait GlobalCurrencyExchange
{

    public function updateExchangeRates()
    {
        $currencies = GlobalCurrency::all();
        $setting = GlobalSetting::first();

        $currencyApiKeyVersion = GlobalSetting::first()->currency_key_version;
        $currencyApiKey = ($setting->currency_converter_key) ? $setting->currency_converter_key : env('CURRENCY_CONVERTER_KEY');

        foreach ($currencies as $currency) {
            try {
                if ($currency->is_cryptocurrency == 'no') {
                    // get exchange rate
                    $client = new Client();
                    $res = $client->request('GET', 'https://' . $currencyApiKeyVersion . '.currconv.com/api/v7/convert?q=' . $setting->currency->currency_code . '_' . $currency->currency_code . '&compact=ultra&apiKey=' . $currencyApiKey);
                    $conversionRate = $res->getBody();
                    $conversionRate = json_decode($conversionRate, true);
                    if (!empty($conversionRate)) {
                        $currency->exchange_rate = $conversionRate[strtoupper($setting->currency->currency_code) . '_' . $currency->currency_code];
                    }
                } else {
                    // get exchange rate
                    $client = new Client();
                    $res = $client->request('GET', 'https://' . $currencyApiKeyVersion . '.currconv.com/api/v7/convert?q=' . $setting->currency->currency_code . '_USD&compact=ultra&apiKey=' . $currencyApiKey);
                    $conversionRate = $res->getBody();
                    $conversionRate = json_decode($conversionRate, true);

                    $usdExchangePrice = $conversionRate[strtoupper($setting->currency->currency_code) . '_USD'];
                    $currency->exchange_rate = $usdExchangePrice;
                }
            } catch (\Exception $e) {
            }
            $currency->save();
        }
    }

    public function updateExchangeRatesCompanyWise($setting)
    {
        $currencies = GlobalCurrency::all();

        $currencyApiKey = GlobalSetting::first()->currency_converter_key;
        $currencyApiKeyVersion = GlobalSetting::first()->currency_key_version;
        $currencyApiKey = ($currencyApiKey) ? $currencyApiKey : env('CURRENCY_CONVERTER_KEY');

        foreach ($currencies as $currency) {
            try {
                if ($currency->is_cryptocurrency == 'no') {
                    // get exchange rate
                    $client = new Client();
                    $res = $client->request('GET', 'https://' . $currencyApiKeyVersion . '.currconv.com/api/v7/convert?q=' . $setting->currency->currency_code . '_' . $currency->currency_code . '&compact=ultra&apiKey=' . $currencyApiKey);
                    $conversionRate = $res->getBody();
                    $conversionRate = json_decode($conversionRate, true);

                    if (!empty($conversionRate)) {
                        $currency->exchange_rate = $conversionRate[strtoupper($setting->currency->currency_code) . '_' . $currency->currency_code];
                    }
                } else {
                    // get exchange rate
                    $client = new Client();
                    $res = $client->request('GET', 'https://' . $currencyApiKeyVersion . '.currconv.com/api/v7/convert?q=' . $setting->currency->currency_code . '_USD&compact=ultra&apiKey=' . $currencyApiKey);
                    $conversionRate = $res->getBody();
                    $conversionRate = json_decode($conversionRate, true);

                    $usdExchangePrice = $conversionRate[strtoupper($setting->currency->currency_code) . '_USD'];
                    $currency->exchange_rate = $usdExchangePrice;
                }
            } catch (\Exception $e) {

            }
            $currency->save();
        }
    }
}

