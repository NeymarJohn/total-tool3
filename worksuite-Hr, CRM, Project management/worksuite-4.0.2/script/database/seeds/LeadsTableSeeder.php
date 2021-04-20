<?php

use App\Lead;
use App\LeadAgent;
use App\LeadSource;
use App\LeadStatus;
use App\UniversalSearch;
use Illuminate\Database\Seeder;

class LeadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('leads')->delete();

        \DB::statement('ALTER TABLE leads AUTO_INCREMENT = 1');

        $count = env('SEED_PROJECT_RECORD_COUNT', 20);
        $faker = \Faker\Factory::create();

        factory(\App\Lead::class, (int) $count)->create()->each(function ($lead) use($faker, $count) {
            $lead->agent_id = $faker->randomElement($this->getLeadAgent());
            $lead->source_id = $faker->randomElement($this->getLeadSource());
            $lead->status_id = $faker->randomElement($this->getLeadStatus());
            $lead->save();
        });

        //log search
//        $this->logSearchEntry($lead->id, $lead->client_name, 'admin.leads.show', 'lead');
//        $this->logSearchEntry($lead->id, $lead->client_email, 'admin.leads.show', 'lead');
//        if(!is_null($lead->company_name)){
//            $this->logSearchEntry($lead->id, $lead->company_name, 'admin.leads.show', 'lead');
//        }
    }

    private function getLeadAgent()
    {
        return LeadAgent::with('user')->get()->pluck('id')->toArray();
    }

    private function getLeadStatus()
    {
        return LeadStatus::get()->pluck('id')->toArray();
    }

    private function getLeadSource()
    {
        return LeadSource::get()->pluck('id')->toArray();
    }

    public function logSearchEntry($searchableId, $title, $route, $type)
    {
        $search = new UniversalSearch();
        $search->searchable_id = $searchableId;
        $search->title = $title;
        $search->route_name = $route;
        $search->module_type = $type;
        $search->save();
    }
}
