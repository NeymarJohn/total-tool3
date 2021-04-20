<?php

use App\GlobalSetting;
use App\Package;
use Illuminate\Database\Seeder;

class GlobalSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currency =  \App\GlobalCurrency::first();

        $setting = new \App\GlobalSetting();

        $setting->currency_id = $currency->id;
        $setting->company_name = 'Worksuite Saas';
        $setting->company_email = 'company@email.com';
        $setting->company_phone = '1234567891';
        $setting->address = 'Company address';
        $setting->website = 'www.domain.com';
        $setting->currency_converter_key = '6c12788708871d0c499d';
        $setting->google_map_key = '';
        $setting->save();

        $package = Package::where('default', 'trial')->first();
        if ($package) {
            $global = GlobalSetting::with('currency')->first();

            if ($global) {
                $package->currency_id = $global->currency_id;
            }
            $package->save();
        }
    }
}
