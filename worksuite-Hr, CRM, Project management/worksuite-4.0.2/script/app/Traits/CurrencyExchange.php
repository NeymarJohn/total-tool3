<?php

/**
 * Created by PhpStorm.
 * User: DEXTER
 * Date: 23/11/17
 * Time: 6:07 PM
 */

namespace App\Traits;

use App\Currency;
use App\Setting;
use GuzzleHttp\Client;

trait CurrencyExchange
{

    public function updateExchangeRates()
    {
        $currencies = Currency::all();
        $setting = cache()->remember(
            'global-setting', 60*60*24, function () {
                return \App\Setting::first();
            }
        );
        $currencyApiKey = ($setting->currency_converter_key) ? $setting->currency_converter_key : env('CURRENCY_CONVERTER_KEY');

        foreach ($currencies as $currency) {

            $currency = Currency::findOrFail($currency->id);
            try {
            if ($currency->is_cryptocurrency == 'no') {

                    // get exchange rate
                    $client = new Client();
                    $res = $client->request('GET', 'https://free.currconv.com/api/v7/convert?q=' . $setting->currency->currency_code . '_' . $currency->currency_code . '&compact=ultra&apiKey=' . $currencyApiKey);
                    $conversionRate = $res->getBody();
                    $conversionRate = json_decode($conversionRate, true);

                    if (!empty($conversionRate)) {
                        $currency->exchange_rate = $conversionRate[strtoupper($setting->currency->currency_code) . '_' . $currency->currency_code];
                    }

            } else {
                    // get exchange rate
                    $client = new Client();
                    $res = $client->request('GET', 'https://free.currconv.com/api/v7/convert?q=' . $setting->currency->currency_code . '_USD&compact=ultra&apiKey=' . $currencyApiKey);
                    $conversionRate = $res->getBody();
                    $conversionRate = json_decode($conversionRate, true);

                    $usdExchangePrice = $conversionRate[strtoupper($setting->currency->currency_code) . '_USD'];
                    $currency->exchange_rate = $usdExchangePrice;
            }
            } catch (\Throwable $th) {
                //throw $th;
            }

            $currency->save();
        }
    }
}
