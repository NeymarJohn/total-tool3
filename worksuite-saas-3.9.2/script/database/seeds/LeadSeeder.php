<?php

use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('leads')->delete();
        $company = \App\Company::first();

        $lead = new \App\Lead();
        $lead->company_id = $company->id;
        $lead->company_name = 'Test Lead';
        $lead->website = 'www.testing.com';
        $lead->address = 'www.testing.com';
        $lead->client_name = 'Test client';
        $lead->client_email = 'testing@test.com';
        $lead->mobile = '123456789';
        $lead->note = 'Quas consectetur, tempor incidunt, aliquid voluptatem, velit mollit et illum, adipisicing ea officia aliquam placeat';
        $lead->save();
    }
}
