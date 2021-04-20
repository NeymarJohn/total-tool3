<?php

use App\CreditNoteItem;
use App\CreditNotes;
use App\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Payment;
use App\TaskboardColumn;
use App\TaskUser;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $_ENV['SEEDING'] = true;
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('projects')->delete();
        \DB::table('project_activity')->delete();
        \DB::table('project_members')->delete();
        \DB::table('taskboard_columns')->delete();
        \DB::table('tasks')->delete();
        \DB::table('invoices')->delete();
        \DB::table('invoice_items')->delete();
        \DB::table('payments')->delete();
        \DB::table('project_time_logs')->delete();
        \DB::table('credit_notes')->delete();
        \DB::table('credit_note_items')->delete();

        \DB::statement('ALTER TABLE projects AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE project_activity AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE project_members AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE taskboard_columns AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE tasks AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE invoices AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE invoice_items AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE payments AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE project_time_logs AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE credit_notes AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE credit_note_items AUTO_INCREMENT = 1');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $count = env('SEED_PROJECT_RECORD_COUNT', 20);

        $faker = \Faker\Factory::create();

        \DB::beginTransaction();

        // Create taskboard column
        $this->taskBoardColumn();

        factory(\App\Project::class, (int) $count)->create()->each(function ($project) use ($faker, $count) {
            $activity = new \App\ProjectActivity();
            $activity->project_id = $project->id;
            $activity->activity = ucwords($project->project_name) . ' added as new project.';
            $activity->save();

            $search = new \App\UniversalSearch();
            $search->searchable_id = $project->id;
            $search->title = $project->project_name;
            $search->route_name = 'admin.projects.show';
            $search->save();

            $randomRange = $faker->numberBetween(1, 5);

            // Assign random members
            for ($i = 1; $i <= $randomRange; $i++) {
                $this->assignMembers($project->id);
            }



            //create tasks
            for ($i = 1; $i <= $randomRange; $i++) {
                $this->createTask($faker, $project);
            }

            // Create invoice

            for ($i = 1; $i <= $count; $i++) {
                $this->createInvoice($faker, $project);
            }

            // Create project time log
            for ($i = 1; $i <= $count; $i++) {
                $this->createTimeLog($faker, $project);
            }
        });

        \DB::commit();
        $_ENV['SEEDING'] = false;
    }

    private function assignMembers($projectId)
    {
        $employeeId = $this->getRandomEmployee();

        // Assign member
        $member = new \App\ProjectMember();
        $member->user_id = $employeeId->id;
        $member->project_id = $projectId;
        $member->hourly_rate = $employeeId->hourly_rate;
        $member->save();

        $activity = new \App\ProjectActivity();
        $activity->project_id = $projectId;
        $activity->activity = 'New member added to the project.';
        $activity->save();
    }

    private function getRandomEmployee()
    {
        return User::select('users.id as id', 'employee_details.hourly_rate')
            ->join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', 'employee')
            ->inRandomOrder()
            ->first();
    }

    private function taskBoardColumn()
    {
        //create taskboard column
        $maxPriority = TaskboardColumn::max('priority');
        if ($maxPriority == null) {
            $maxPriority = 0;
        }
        // dd($maxPriority);

        //create taskboard column
        $maxPriority = TaskboardColumn::max('priority');
        $board2 = new TaskboardColumn();
        $board2->column_name = 'Incomplete';
        $board2->slug = str_slug($board2->column_name, '_');
        $board2->label_color = '#d21010';
        $board2->priority = ($maxPriority + 1);
        $board2->save();

        $board1 = new TaskboardColumn();
        $board1->column_name = 'To Do';
        $board1->slug = str_slug($board1->column_name, '_');
        $board1->label_color = '#f5c308';
        $board1->priority = ($maxPriority + 1);
        $board1->save();
        
        $maxPriority = TaskboardColumn::max('priority');
        $board1 = new TaskboardColumn();
        $board1->column_name = 'Doing';
        $board1->label_color = '#00b5ff';
        $board1->slug = str_slug($board1->column_name, '_');
        $board1->priority = ($maxPriority + 1);
        $board1->save();

                
        //create taskboard column
        $maxPriority = TaskboardColumn::max('priority');
        $board2 = new TaskboardColumn();
        $board2->column_name = 'Completed';
        $board2->slug = str_slug($board2->column_name, '_');
        $board2->label_color = '#679c0d';
        $board2->priority = ($maxPriority + 1);
        $board2->save();

    }

    private function createTask($faker, $project)
    {
        $assignee = \App\ProjectMember::inRandomOrder()->where('project_id', $project->id)
            ->first();

        $boards = TaskboardColumn::all()->pluck('id')->toArray();

        $startDate = $faker->randomElement([$faker->dateTimeThisMonth($max = 'now'), $faker->dateTimeThisYear($max = 'now')]);

        $task = new \App\Task();
        $task->heading = $faker->realText(20);
        $task->description = $faker->realText(200);
        $task->start_date = $startDate;
        $task->due_date = Carbon::parse($startDate)->addDays(rand(1, 10))->toDateString();
        $task->project_id = $project->id;
        $task->priority = $faker->randomElement(['high', 'medium', 'low']);
        $task->status = $faker->randomElement(['incomplete', 'completed']);
        $task->board_column_id = $faker->randomElement($boards);
        $task->save();

        TaskUser::create(
            [
                'user_id' => $assignee->user_id,
                'task_id' => $task->id
            ]
        );

        $search = new \App\UniversalSearch();
        $search->searchable_id = $task->id;
        $search->title = $task->heading;
        $search->route_name = 'admin.all-tasks.edit';
        $search->save();

        $activity = new \App\ProjectActivity();
        $activity->project_id = $project->id;
        $activity->activity = 'New task added to the project.';
        $activity->save();
    }

    private function createInvoice($faker, $project)
    {
        $items = [$faker->word, $faker->word];
        $cost_per_item = [$faker->numberBetween(1000, 2000), $faker->numberBetween(1000, 2000)];
        $quantity = [$faker->numberBetween(1, 20), $faker->numberBetween(1, 20)];
        $amount = [$cost_per_item[0] * $quantity[0], $cost_per_item[1] * $quantity[1]];
        $type = ['item', 'item'];

        $invoice = new \App\Invoice();
        $invoice->project_id = $project->id;
        $invoice->client_id = $project->client_id;
        $invoice->invoice_number = \App\Invoice::count() == 0 ? 1 : \App\Invoice::count() + 1;
        $invoice->issue_date = \Carbon\Carbon::parse((date('m') - 1) . '/' . $faker->numberBetween(1, 30) . '/' . date('Y'))->format('Y-m-d');
        $invoice->due_date = \Carbon\Carbon::parse($invoice->issue_date)->addDays(10)->format('Y-m-d');
        $invoice->sub_total = array_sum($amount);
        $invoice->total = array_sum($amount);
        $invoice->currency_id = '1';
        $invoice->status = $faker->randomElement(['paid', 'unpaid']);
        $invoice->save();

        $search = new \App\UniversalSearch();
        $search->searchable_id = $invoice->id;
        $search->title = 'Invoice ' . $invoice->invoice_number;
        $search->route_name = 'admin.all-invoices.show';
        $search->save();

        foreach ($items as $key => $item) :
            \App\InvoiceItems::create(['invoice_id' => $invoice->id, 'item_name' => $item,'hsn_sac_code' => (isset($hsnSacCode[$key]) && !is_null($hsnSacCode[$key])) ? $hsnSacCode[$key] : null, 'type' => $type[$key], 'quantity' => $quantity[$key], 'unit_price' => $cost_per_item[$key], 'amount' => $amount[$key]]);
        endforeach;
        $input = ['invoice_id' => $invoice->id, 'project_id' => $project->id];
        $rand_keys = array_rand($input, 1);

        $payment = new \App\Payment();
        $payment->amount = $invoice->total;
        if($rand_keys == 'invoice_id'){
            $payment->invoice_id = $input[$rand_keys];
        }
        if($rand_keys == 'project_id'){
            $payment->project_id = $input[$rand_keys];
        }
        $payment->gateway = 'Bank Transfer';
        $payment->transaction_id = $faker->unique()->numberBetween(100000, 123212);
        $payment->currency_id = '1';
        $payment->status = $faker->randomElement(['complete', 'pending']);
        $payment->paid_on = \Carbon\Carbon::parse($faker->numberBetween(1, 12) . '/' . $faker->numberBetween(1, 30) . '/' . date('Y') . ' ' . $faker->numberBetween(1, 23) . ':' . $faker->numberBetween(1, 59) . ':' . $faker->numberBetween(1, 59))->format('Y-m-d H:i:s');
        $payment->save();

       // $this->createCreditNotes($invoice);
    }

    private function createTimeLog($faker, $project)
    {

        $randomEmployee = $this->getRandomEmployee();
        //Create time logs
        $timeLog = new \App\ProjectTimeLog();
        $timeLog->project_id = $project->id;
        $timeLog->user_id = $randomEmployee->id;
        $timeLog->start_time = $faker->randomElement([date('Y-m-d', strtotime('+' . mt_rand(0, 7) . ' days')), $faker->dateTimeThisMonth($max = 'now'), $faker->dateTimeThisYear($max = 'now')]);
        $timeLog->start_time = Carbon::createFromFormat('Y-m-d H:i:s', $timeLog->start_time, 'Asia/Kolkata')->setTimezone('UTC');
        $timeLog->end_time = $timeLog->start_time->addDays($faker->numberBetween(1, 10))->format('Y-m-d') . ' ' . Carbon::parse('04:15 PM')->format('H:i:s');
        $timeLog->end_time = Carbon::createFromFormat('Y-m-d H:i:s', $timeLog->end_time, 'Asia/Kolkata')->setTimezone('UTC');
        $timeLog->total_hours = $timeLog->end_time->diff($timeLog->start_time)->format('%d') * 24 + $timeLog->end_time->diff($timeLog->start_time)->format('%H');

        $timeLog->total_hours = $timeLog->end_time->diff($timeLog->start_time)->format('%d') * 24 + $timeLog->end_time->diff($timeLog->start_time)->format('%H');
        $timeLog->total_minutes = ($timeLog->total_hours * 60) + ($timeLog->end_time->diff($timeLog->start_time)->format('%i'));

        $timeLog->memo = 'working on' . $faker->word;
        $timeLog->save();
    }

    private function createCreditNotes($invoice)
    {
        $creditNote = new CreditNotes();
        $creditNote->project_id = $invoice->project_id;
        $creditNote->cn_number = CreditNotes::count() + 1;
        $creditNote->invoice_id = $invoice->id ? $invoice->id : null;
        $creditNote->issue_date = Carbon::parse(Carbon::now())->format('Y-m-d');
        $creditNote->due_date = Carbon::parse(Carbon::now()->addMonths(2))->format('Y-m-d');
        $creditNote->sub_total = round($invoice->sub_total, 2);
        $creditNote->discount = round($invoice->discount_value, 2);
        $creditNote->discount_type = 'percent';
        $creditNote->total = round($invoice->total, 2);
        $creditNote->currency_id = $invoice->currency_id;
        $creditNote->recurring = 'no';
        $creditNote->billing_frequency = $invoice->recurring_payment == 'yes' ? $invoice->billing_frequency : null;
        $creditNote->billing_interval = $invoice->recurring_payment == 'yes' ? $invoice->billing_interval : null;
        $creditNote->billing_cycle = $invoice->recurring_payment == 'yes' ? $invoice->billing_cycle : null;
        $creditNote->note = $invoice->note;
        $creditNote->status = $invoice->status == 'paid' ? 'closed' : 'open';
        $creditNote->save();


        $invoice->credit_note = 1;

        if ($invoice->status != 'paid') {
            $amount = round($invoice->total, 2);

            if (round($invoice->total, 2) > round($invoice->total - $invoice->getPaidAmount(), 2)) {
                // create payment for invoice total
                if ($invoice->status == 'partial') {
                    $amount = round($invoice->total - $invoice->getPaidAmount(), 2);
                }

                $invoice->status = 'paid';
            } else {
                $amount = round($invoice->total, 2);
                $invoice->status = 'partial';
                $creditNote->status = 'closed';

                if (round($invoice->total, 2) == round($invoice->total - $invoice->getPaidAmount(), 2)) {
                    if ($invoice->status == 'partial') {
                        $amount = round($invoice->total - $invoice->getPaidAmount(), 2);
                    }

                    $invoice->status = 'paid';
                }
            }
            $creditNote->invoices()
                ->attach($invoice->id, [
                    'credit_amount' => $amount,
                    'date' => Carbon::now()
                ]);
            $creditNote->save();
        }
        $invoice->save();

        foreach ($invoice->items as $key => $item) :
            if (!is_null($item)) {
                CreditNoteItem::create([
                    'credit_note_id' => $creditNote->id,
                    'item_name' => $item->item_name,
                    'type' => 'item',
                    'quantity' => $item->quantity,
                    'unit_price' => round($item->unit_price, 2),
                    'amount' => round($item->amount, 2),
                    'taxes' => null
                ]);
            }
        endforeach;
    }
}
