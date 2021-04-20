<?php

use App\Package;
use Illuminate\Database\Seeder;

class PackageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencyID =  \App\GlobalSetting::first()->currency_id;

        $defaultPackage = Package::first();
        $defaultPackage->currency_id = $currencyID;
        $defaultPackage->save();

        // \DB::table('packages')->delete();
        // \DB::statement('ALTER TABLE packages AUTO_INCREMENT = 1');


        $package = new Package();
        $package->name = 'Free';
        $package->currency_id = $currencyID;
        $package->description = 'It\'s a free package.';
        $package->max_storage_size = -1;
        $package->max_file_size = 10;
        $package->max_employees = 20;
        $package->sort = 0;
        $package->module_in_package = '{"1":"clients","2":"employees","3":"attendance","4":"projects","5":"tasks","6":"estimates","7":"invoices","8":"payments","9":"expenses","10":"timelogs","11":"tickets","12":"messages","13":"events","14":"leaves","15":"notices","16":"leads","17":"holidays","18":"products"}';
        $package->save();


        $package = new Package();
        $package->name = 'Starter';
        $package->currency_id = $currencyID;
        $package->description = 'Quidem deserunt nobis asperiores fuga Ullamco corporis culpa';
        $package->max_storage_size = -1;
        $package->max_file_size = 30;
        $package->max_employees = 50;
        $package->annual_price = 500;
        $package->monthly_price = 50;
        $package->billing_cycle = 10;
        $package->sort = 1;
        $package->module_in_package = '{"1":"clients","2":"employees","3":"attendance","4":"projects","5":"tasks","6":"estimates","7":"invoices","8":"payments","9":"expenses","10":"timelogs","11":"tickets","17":"holidays"}';
        $package->stripe_annual_plan_id = 'starter_annual';
        $package->stripe_monthly_plan_id = 'starter_monthly';
        $package->save();

        $package = new Package();
        $package->name = 'Medium';
        $package->currency_id = $currencyID;
        $package->description = 'Quidem deserunt nobis asperiores fuga Ullamco corporis culpa';
        $package->max_storage_size = -1;
        $package->max_file_size = 50;
        $package->max_employees = 100;
        $package->annual_price = 1000;
        $package->monthly_price = 100;
        $package->billing_cycle = 10;
        $package->sort = 2;
        $package->module_in_package = '{"1":"clients","2":"employees","3":"attendance","4":"projects","5":"tasks","6":"estimates","7":"invoices","8":"payments","9":"expenses","10":"timelogs","11":"tickets","12":"messages","13":"events","14":"leaves","15":"notices","16":"leads","17":"holidays"}';
        $package->stripe_annual_plan_id = 'medium_annual';
        $package->stripe_monthly_plan_id = 'medium_monthly';
        $package->save();


        $package = new Package();
        $package->name = 'Larger';
        $package->currency_id = $currencyID;
        $package->description = 'Quidem deserunt nobis asperiores fuga Ullamco corporis culpa';
        $package->max_storage_size = -1;
        $package->max_file_size = 100;
        $package->max_employees = 500;
        $package->annual_price = 5000;
        $package->monthly_price = 500;
        $package->billing_cycle = 10;
        $package->sort = 3;
        $package->module_in_package = '{"1":"clients","2":"employees","3":"attendance","4":"projects","5":"tasks","6":"estimates","7":"invoices","8":"payments","9":"expenses","10":"timelogs","11":"tickets","12":"messages","13":"events","14":"leaves","15":"notices","16":"leads","17":"holidays","18":"products"}';
        $package->stripe_annual_plan_id = 'larger_annual';
        $package->stripe_monthly_plan_id = 'larger_monthly';
        $package->save();


    }
}
