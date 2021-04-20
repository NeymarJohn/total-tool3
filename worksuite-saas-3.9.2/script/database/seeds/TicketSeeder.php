<?php

use App\TicketAgentGroups;
use App\TicketGroup;
use App\TicketReply;
use App\User;
use Illuminate\Database\Seeder;


class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = \App\Company::first();
        //save agent

        \DB::table('tickets')->delete();
        \DB::table('ticket_agent_groups')->delete();
        \DB::table('ticket_replies')->delete();

        \DB::statement('ALTER TABLE tickets AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE ticket_agent_groups AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE ticket_replies AUTO_INCREMENT = 1');

        $faker = \Faker\Factory::create();
        $agents = $this->getEmployees($company->id);
        $groups = $this->getGroups($company->id);

        $count = env('SEED_RECORD_COUNT', 10);

        try{
            for ($i = 1; $i <= $count; $i++) {
                $agent = new TicketAgentGroups();
                $agent->company_id = $company->id;
                $agent->agent_id = $faker->randomElement($agents);
                $agent->group_id = $faker->randomElement($groups);
                $agent->save();
            }

            factory(\App\Ticket::class, (int)$count)->create()->each(function ($ticket) use ($faker, $count, $company) {

                $usersArray = [$ticket->user_id, $ticket->agent_id];
                $admins = $this->getAdmins($company->id);
                $usersData = array_merge($usersArray, $admins);

                for ($i = 1; $i <= $count; $i++) {
                    //save  message
                    $reply = new TicketReply();
                    $reply->message = $faker->realText(50);
                    $reply->ticket_id = $ticket->id;
                    $reply->user_id = $faker->randomElement($usersData); //current logged in user
                    $reply->save();

                    //log search
                    $search = new \App\UniversalSearch();
                    $search->searchable_id = $ticket->id;
                    $search->title = 'Ticket: ' . $ticket->subject;
                    $search->route_name = 'admin.tickets.edit';
                    $search->save();
                }
            });
        }catch (\Exception $e){}

    }

    private function getEmployees($companyID)
    {
        return User::select('users.id as id')
            ->join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', 'employee')
            ->where('users.company_id', $companyID)
            ->inRandomOrder()
            ->get()->pluck('id')->toArray();
    }

    private function getAdmins($companyID)
    {
        return User::select('users.id as id')
            ->join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', 'admin')
            ->where('users.company_id', $companyID)
            ->inRandomOrder()
            ->get()->pluck('id')->toArray();
    }

    private function getGroups($companyID)
    {
        return TicketGroup::where('company_id', $companyID)->inRandomOrder()
            ->get()->pluck('id')->toArray();
    }
}
