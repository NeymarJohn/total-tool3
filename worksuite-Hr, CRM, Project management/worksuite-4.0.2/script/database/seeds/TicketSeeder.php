<?php

use App\User;
use Illuminate\Database\Seeder;
use App\Ticket;
use App\TicketReply;
use App\TicketAgentGroups;
use App\TicketGroup;


class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //save agent

        \DB::table('tickets')->delete();
        \DB::table('ticket_agent_groups')->delete();
        \DB::table('ticket_replies')->delete();

        \DB::statement('ALTER TABLE tickets AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE ticket_agent_groups AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE ticket_replies AUTO_INCREMENT = 1');

        $faker = \Faker\Factory::create();
        $agents = $this->getEmployees();
        $groups = $this->getGroups();

        $count = env('SEED_RECORD_COUNT', 30);

        for($i=1; $i <= $count; $i++) {
            $agent = new TicketAgentGroups();
            $agent->agent_id = $faker->unique(false, 25000)->randomElement($agents);
            $agent->group_id = $faker->randomElement($groups);
            $agent->save();
        }

        factory(\App\Ticket::class, (int) $count)->create()->each(function ($ticket) use($faker, $count) {

            $usersArray = [$ticket->user_id, $ticket->agent_id];
            $admins = $this->getAdmins();
            $usersData = array_merge($usersArray, $admins);

            for($i = 1; $i <= $count; $i++) {
                //save  message
                $reply = new TicketReply();
                $reply->message = $faker->realText(50);
                $reply->ticket_id = $ticket->id;
                $reply->user_id = $faker->randomElement($usersData); //current logged in user
                $reply->save();

                //log search
                $search = new \App\UniversalSearch();
                $search->searchable_id = $ticket->id;
                $search->title = 'Ticket: '.$ticket->subject;
                $search->route_name = 'admin.tickets.edit';
                $search->save();
            }
        });
    }

    private function getEmployees()
    {
        return User::select('users.id as id')
            ->join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', 'employee')
            ->inRandomOrder()
            ->get()->pluck('id')->toArray();
    }

    private function getAdmins()
    {
        return User::select('users.id as id')
            ->join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', 'admin')
            ->inRandomOrder()
            ->get()->pluck('id')->toArray();
    }

    private function getGroups()
    {
        return TicketGroup::inRandomOrder()
            ->get()->pluck('id')->toArray();
    }
}
