<?php
$company = \App\Company::first();

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Ticket::class, function (Faker\Generator $faker) use ($company) {

    $types = \App\TicketType::where('company_id', $company->id)->get()->pluck('id')->toArray();
    $users = \App\User::where('company_id', $company->id)->get()->pluck('id')->toArray();
    $channels = \App\TicketChannel::where('company_id', $company->id)->get()->pluck('id')->toArray();
    $agents = \App\User::select('users.id as id')
        ->join('employee_details', 'users.id', '=', 'employee_details.user_id')
        ->join('role_user', 'role_user.user_id', '=', 'users.id')
        ->join('roles', 'roles.id', '=', 'role_user.role_id')
        ->where('roles.name', 'employee')
        ->where('users.company_id', $company->id)
        ->inRandomOrder()
        ->get()->pluck('id')->toArray();

    return [
        'subject' => $faker->realText(70),
        'status' => $faker->randomElement(['open', 'pending', 'resolved', 'closed']),
        'priority' => $faker->randomElement(['low', 'high', 'medium', 'urgent']),
        'user_id' => $faker->randomElement($users),
        'company_id' => $company->id,
        'agent_id' => $faker->randomElement($agents),
        'channel_id' => $faker->randomElement($channels),
        'type_id' => $faker->randomElement($types),
        'created_at' => $faker->randomElement([date('Y-m-d', strtotime( '+'.mt_rand(0, 7).' days')), $faker->dateTimeThisYear($max = 'now')]),
        'updated_at' => $faker->randomElement([date('Y-m-d', strtotime( '+'.mt_rand(0, 7).' days')), $faker->dateTimeThisYear($max = 'now')]),
    ];
});

$factory->define(App\Leave::class, function (Faker\Generator $faker) use ($company) {

    $employees = \App\User::allEmployeesByCompany($company->id)->pluck('id')->toArray();
    $leaveType = \App\LeaveType::where('company_id', $company->id)->get()->pluck('id')->toArray();

    return [
        'company_id' => $company->id,
        'user_id' => $faker->randomElement($employees),
        'leave_type_id' => $faker->randomElement($leaveType),
        'duration' => $faker->randomElement(['single', 'multiple']),
        'leave_date' => $faker->dateTimeThisMonth(Carbon\Carbon::now()),
        'reason' => $faker->realText(200),
        'status' => $faker->randomElement(['pending', 'approved', 'rejected']),
    ];
});

$factory->define(App\Notice::class, function (Faker\Generator $faker) use ($company) {
    return [
        'company_id' => $company->id,
        'heading' => $faker->realText(70),
        'description' => $faker->realText(1000),
        'created_at' => $faker->randomElement([date('Y-m-d', strtotime( '+'.mt_rand(0,7).' days')),$faker->dateTimeThisMonth($max = 'now'), $faker->dateTimeThisYear($max = 'now')]),
    ];
});

$factory->define(App\Event::class, function (Faker\Generator $faker) use ($company) {
    return [
        'company_id' => $company->id,
        'event_name' => $faker->text(20),
        'label_color' => $faker->randomElement(['bg-info', 'bg-warning', 'bg-purple', 'bg-danger', 'bg-success', 'bg-inverse']),
        'where' => $faker->address,
        'description' => $faker->paragraph,
        'start_date_time' => $start = $faker->randomElement([$faker->dateTimeThisMonth($max = 'now'), $faker->dateTimeThisYear($max = 'now')]),
        'end_date_time' => $faker->dateTimeBetween($start, \Carbon\Carbon::parse($start)->addDays(1)->addMinutes(20)),
        'repeat' => 'no',
    ];
});
$factory->define(App\Project::class, function (Faker\Generator $faker) use ($company) {

    return [
        'company_id' => $company->id,
        'project_name' => $faker->text(20),
        'feedback' => $faker->text(150),
        'project_summary' => 'Obcaecati doloremque consectetur, dolore aute non dolor odio ut aut sed et illo laudantium, aliqua. Minus esse excepturi esse aliquip excepteur labore elit, qui omnis voluptas aut dolorum magnam doloremque irure ut veritatis exercitationem aut occaecat qui praesentium quas sed cum elit, ratione exercitation placeat, pariatur? Quas consectetur, tempor incidunt, aliquid voluptatem, velit mollit et illum, adipisicing ea officia aliquam placeat, laborum. In libero natus velit non est aut libero quo ducimus, voluptate officiis est, ut rem aut quam optio, deleniti.',
        'start_date' => \Carbon\Carbon::now()->subDays(3)->format('Y-m-d'),
        'deadline' => \Carbon\Carbon::now()->addDays(8)->format('Y-m-d'),
        'notes' => 'Quas consectetur, tempor incidunt, aliquid voluptatem, velit mollit et illum, adipisicing ea officia aliquam placeat',
        'completion_percent' => $faker->randomElement(['20', '40', '60', '80']),
    ];
});

$factory->define(App\Expense::class, function (Faker\Generator $faker) use ($company) {
    $employee = \App\User::where('email', '<>', 'client@example.com')->where('company_id', $company->id)->pluck('id')->toArray();
    $currency = \App\Currency::where('company_id', $company->id)->first();

    return [
        'item_name' => $faker->text(20),
        'purchase_date' => $start = $faker->randomElement([$faker->dateTimeThisMonth($max = 'now'), $faker->dateTimeThisYear($max = 'now')]),
        'purchase_from' => $faker->realText(10),
        'price' => $faker->numberBetween(100, 2000),
        'user_id' => $faker->randomElement($employee),
        'status' => $faker->randomElement(['approved', 'pending', 'rejected']),
        'currency_id' => $currency->id
    ];
});
