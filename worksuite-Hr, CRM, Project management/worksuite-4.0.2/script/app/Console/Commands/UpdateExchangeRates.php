<?php

namespace App\Console\Commands;

use App\Currency;
use App\Setting;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class UpdateExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-exchange-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the exchange rates for all the currencies in currencies table.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $currencies = Currency::all();
        $setting = Setting::first();
        $currencyApiKey = ($setting->currency_converter_key) ? $setting->currency_converter_key : env('CURRENCY_CONVERTER_KEY');

        foreach($currencies as $currency){

            $currency = Currency::findOrFail($currency->id);

            // get exchange rate
            $client = new Client();
            $res = $client->request('GET', 'https://free.currconv.com/api/v7/convert?q='.$setting->currency->currency_code.'_'.$currency->currency_code.'&compact=ultra&apiKey='.$currencyApiKey);
            $conversionRate = $res->getBody();
            $conversionRate = json_decode($conversionRate, true);

            $currency->exchange_rate = $conversionRate[$setting->currency->currency_code.'_'.$currency->currency_code];
            $currency->save();
        }
    }
}
