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
        \DB::table('lead_sources')->delete();
        \DB::table('lead_status')->delete();
        \DB::table('leads')->delete();
        // Lead Source start
        $sources = new \App\LeadSource();
        $sources->type = 'Social Media';
        $sources->save();

        $sources = new \App\LeadSource();
        $sources->type = 'Google';
        $sources->save();

        $sources = new \App\LeadSource();
        $sources->type = 'other';
        $sources->save();
        // Lead Source end

        // Lead Status start
        $sources = new \App\LeadStatus();
        $sources->type = 'Pending';
        $sources->priority = 1;
        $sources->default = 1;
        $sources->save();
        $pendingStatus = $sources;

        $sources = new \App\LeadStatus();
        $sources->type = 'Overview';
        $sources->label_color = '#0c00ff';
        $sources->priority = 2;
        $sources->save();

        $sources = new \App\LeadStatus();
        $sources->type = 'Confirmed';
        $sources->label_color = '#54a00b';
        $sources->priority = 3;
        $sources->save();
        // Lead Status end

        $lead = new \App\Lead();
        $lead->company_name = 'Test Lead';
        $lead->website = 'www.testing.com';
        $lead->address = 'www.testing.com';
        $lead->client_name = 'Test client';
        $lead->client_email = 'testing@test.com';
        $lead->mobile = '123456789';
        $lead->status_id = $pendingStatus->id;
        $lead->note = 'Quas consectetur, tempor incidunt, aliquid voluptatem, velit mollit et illum, adipisicing ea officia aliquam placeat';
        $lead->save();

    }
}
