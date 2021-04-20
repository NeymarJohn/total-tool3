<?php

use App\Invoice;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

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
        try {
            $client = \App\User::where('email', '=', 'client@example.com')->first();
            $company = \App\Company::first();
            $employee = \App\User::where('email', '<>', 'client@example.com')->where('company_id', $company->id)->pluck('id')->toArray('id');
            $taskboard = \App\TaskboardColumn::where('company_id', $company->id)->pluck('id')->toArray('id');
            $currency = \App\Currency::where('company_id', $company->id)->first();

            factory(\App\Project::class, 10)->create()->each(function ($project) use ($company, $employee, $client, $currency, $taskboard) {
                $project->company_id = $company->id;
                $project->category_id = rand(1, 5);
                $project->client_id = $client->id;
                $project->save();

                $k = array_rand($employee);
                $randomEmployee = $employee[$k];

                $taskboard_random = array_rand($taskboard);
                $randomTaskBoard = $taskboard[$taskboard_random];


                $activity = new \App\ProjectActivity();
                $activity->project_id = $project->id;
                $activity->company_id = $company->id;
                $activity->activity = ucwords($project->project_name) . ' added as new project.';
                $activity->save();

                $search = new \App\UniversalSearch();
                $search->searchable_id = $project->id;
                $search->company_id = $company->id;
                $search->title = $project->project_name;
                $search->route_name = 'admin.projects.show';
                $search->module_type = 'project';
                $search->save();

                // Assign member
                $member = new \App\ProjectMember();
                $member->user_id = $randomEmployee;
                $member->company_id = $company->id;
                $member->project_id = $project->id;
                $member->save();

                $activity = new \App\ProjectActivity();
                $activity->company_id = $company->id;
                $activity->project_id = $project->id;
                $activity->activity = 'New member added to the project.';
                $activity->save();

                $array = \array_diff($employee, [$randomEmployee]);

                $k = array_rand($array);
                $randomSecondEmployee = $array[$k];

                $member = new \App\ProjectMember();
                $member->company_id = $company->id;
                $member->user_id = $randomSecondEmployee;
                $member->project_id = $project->id;
                $member->save();

                $activity = new \App\ProjectActivity();
                $activity->company_id = $company->id;
                $activity->project_id = $project->id;
                $activity->activity = 'New member added to the project.';
                $activity->save();

                //create task
                $task = new \App\Task();
                $task->company_id = $company->id;
                $task->heading = $project->project_name . ' Task 1';
                $task->description = 'olore aute non dolor odio ut aut sed et illo laudantium, aliqua.';
                $task->due_date = \Carbon\Carbon::now()->format('Y-m-d');
                $task->board_column_id = $randomTaskBoard;
                $task->project_id = $project->id;
                $task->priority = 'high';
                $task->status = 'completed';
                $task->is_private = 0;
                $task->save();
                $task->users()->attach($randomEmployee);

                $search = new \App\UniversalSearch();
                $search->searchable_id = $task->id;
                $search->company_id = $company->id;
                $search->title = $task->heading;
                $search->route_name = 'admin.all-tasks.edit';
                $search->module_type = 'task';
                $search->save();

                $activity = new \App\ProjectActivity();
                $activity->project_id = $project->id;
                $activity->company_id = $company->id;
                $activity->activity = 'New task added to the project.';
                $activity->save();

                $task = new \App\Task();
                $task->heading = $project->project_name . ' Task 2';
                $task->company_id = $company->id;
                $task->description = 'olore aute non dolor odio ut aut sed et illo laudantium, aliqua.';
                $task->due_date = \Carbon\Carbon::now()->format('Y-m-d');

                $task->project_id = $project->id;
                $task->priority = 'high';
                $task->status = 'incomplete';
                $task->board_column_id = $randomTaskBoard;

                $task->save();
                $task->users()->attach($randomEmployee);

                $search = new \App\UniversalSearch();
                $search->searchable_id = $task->id;
                $search->company_id = $company->id;
                $search->title = $task->heading;
                $search->route_name = 'admin.all-tasks.edit';
                $search->module_type = 'task';
                $search->save();

                $activity = new \App\ProjectActivity();
                $activity->company_id = $company->id;
                $activity->project_id = $project->id;
                $activity->activity = 'New task added to the project.';
                $activity->save();

                $task = new \App\Task();
                $task->company_id = $company->id;
                $task->heading = $project->project_name . ' Task 3';
                $task->description = 'aliquam placeat, laborum. In libero natus velit non est aut libero quo ducimus,';
                $task->due_date = \Carbon\Carbon::now()->format('Y-m-d');
                $task->project_id = $project->id;
                $task->priority = 'high';
                $task->status = 'incomplete';
                $task->board_column_id = $randomTaskBoard;
                $task->save();
                $task->users()->attach($randomEmployee);

                $search = new \App\UniversalSearch();
                $search->searchable_id = $task->id;
                $search->company_id = $company->id;
                $search->title = $task->heading;
                $search->route_name = 'admin.all-tasks.show';
                $search->module_type = 'task';
                $search->save();

                $activity = new \App\ProjectActivity();
                $activity->project_id = $project->id;
                $activity->company_id = $company->id;
                $activity->activity = 'New task added to the project.';
                $activity->save();

                //create invoice
                $randAmountOne = rand(1000, 5000);
                $randAmountTwo = rand(1000, 5000);
                $items = ['item 1', 'item 2'];
                $cost_per_item = [$randAmountOne, $randAmountTwo];
                $quantity = ['1', '1'];
                $amount = [$randAmountOne, $randAmountTwo];
                $type = ['item', 'item'];

                $invoice = new \App\Invoice();
                $invoice->project_id = $project->id;
                $invoice->company_id = $company->id;
                $invoice->invoice_number = Invoice::count() + 1;
                $invoice->issue_date = \Carbon\Carbon::now()->subDays(rand(3, 60))->format('Y-m-d');
                $invoice->due_date = \Carbon\Carbon::now()->subDays(rand(3, 60))->format('Y-m-d');
                $invoice->sub_total = ($randAmountOne + $randAmountTwo);
                $invoice->total = ($randAmountOne + $randAmountTwo);
                $invoice->currency_id = $currency->id;
                $invoice->status = 'paid';
                $invoice->save();

                $search = new \App\UniversalSearch();
                $search->searchable_id = $invoice->id;
                $search->company_id = $company->id;
                $search->title = 'Invoice ' . $invoice->invoice_number;
                $search->route_name = 'admin.all-invoices.show';
                $search->module_type = 'invoice';
                $search->save();

                foreach ($items as $key => $item):
                    \App\InvoiceItems::create(['invoice_id' => $invoice->id, 'item_name' => $item, 'type' => $type[$key], 'quantity' => $quantity[$key], 'unit_price' => $cost_per_item[$key], 'amount' => $amount[$key]]);
                endforeach;

                $payment = new Payment();
                $payment->company_id = $company->id;
                $payment->project_id = $project->id;
                $payment->invoice_id = $invoice->id;
                $payment->currency_id = $currency->id;
                $payment->amount = $invoice->total;
                $payment->gateway = 'cash';
                $payment->transaction_id = '123' . $project->id;
                $payment->paid_on = Carbon::parse($invoice->due_date)->format('Y-m-d');
                $payment->status = 'complete';
                $payment->save();

                $randAmountOne = rand(1000, 5000);
                $randAmountTwo = rand(1000, 5000);
                $items = ['item 3', 'item 4'];
                $cost_per_item = [$randAmountOne, $randAmountTwo];
                $quantity = ['1', '1'];
                $amount = [$randAmountOne, $randAmountTwo];
                $type = ['item', 'item'];

                $invoice = new \App\Invoice();
                $invoice->project_id = $project->id;
                $invoice->company_id = $company->id;
                $invoice->invoice_number = Invoice::count() + 1;
                $invoice->issue_date = \Carbon\Carbon::now()->subDays(rand(3, 60))->format('Y-m-d');
                $invoice->due_date = \Carbon\Carbon::now()->subDays(rand(3, 60))->format('Y-m-d');
                $invoice->sub_total = ($randAmountOne + $randAmountTwo);
                $invoice->total = ($randAmountOne + $randAmountTwo);
                $invoice->currency_id = $currency->id;
                $invoice->status = 'paid';
                $invoice->save();

                $search = new \App\UniversalSearch();
                $search->searchable_id = $invoice->id;
                $search->company_id = $company->id;
                $search->title = 'Invoice ' . $invoice->invoice_number;
                $search->route_name = 'admin.all-invoices.show';
                $search->module_type = 'invoice';
                $search->save();

                foreach ($items as $key => $item):
                    \App\InvoiceItems::create(['invoice_id' => $invoice->id, 'item_name' => $item, 'type' => $type[$key], 'quantity' => $quantity[$key], 'unit_price' => $cost_per_item[$key], 'amount' => $amount[$key]]);
                endforeach;

                $payment = new Payment();
                $payment->company_id = $company->id;
                $payment->project_id = $project->id;
                $payment->invoice_id = $invoice->id;
                $payment->currency_id = $currency->id;
                $payment->amount = $invoice->total;
                $payment->gateway = 'cash';
                $payment->transaction_id = '1234' . $project->id;
                $payment->paid_on = Carbon::parse($invoice->due_date)->format('Y-m-d');
                $payment->status = 'complete';
                $payment->save();

                $randAmountOne = rand(1000, 5000);
                $items = ['item 5'];
                $cost_per_item = [$randAmountOne];
                $quantity = ['1'];
                $amount = [$randAmountOne];
                $type = ['item'];

                $invoice = new \App\Invoice();
                $invoice->company_id = $company->id;
                $invoice->project_id = $project->id;
                $invoice->invoice_number = Invoice::count() + 1;
                $invoice->issue_date = \Carbon\Carbon::now()->subDays(rand(3, 60))->format('Y-m-d');
                $invoice->due_date = \Carbon\Carbon::now()->subDays(rand(3, 60))->format('Y-m-d');
                $invoice->sub_total = $randAmountOne;
                $invoice->total = $randAmountOne;
                $invoice->currency_id = $currency->id;
                $invoice->status = 'paid';
                $invoice->save();

                $search = new \App\UniversalSearch();
                $search->searchable_id = $invoice->id;
                $search->company_id = $company->id;
                $search->title = 'Invoice ' . $invoice->invoice_number;
                $search->route_name = 'admin.all-invoices.show';
                $search->module_type = 'invoice';
                $search->save();

                foreach ($items as $key => $item):
                    \App\InvoiceItems::create(['invoice_id' => $invoice->id, 'item_name' => $item, 'type' => $type[$key], 'quantity' => $quantity[$key], 'unit_price' => $cost_per_item[$key], 'amount' => $amount[$key]]);
                endforeach;

                $payment = new Payment();
                $payment->invoice_id = $invoice->id;
                $payment->company_id = $company->id;
                $payment->project_id = $project->id;
                $payment->currency_id = $currency->id;
                $payment->amount = $invoice->total;
                $payment->gateway = 'cash';
                $payment->transaction_id = '12345' . $project->id;
                $payment->paid_on = Carbon::parse($invoice->due_date)->format('Y-m-d');
                $payment->status = 'complete';
                $payment->save();

                $randAmountOne = rand(1000, 5000);
                $items = ['item 6'];
                $cost_per_item = [$randAmountOne];
                $quantity = ['1'];
                $amount = [$randAmountOne];
                $type = ['item'];

                $invoice = new \App\Invoice();
                $invoice->company_id = $company->id;
                $invoice->project_id = $project->id;
                $invoice->invoice_number = Invoice::count() + 1;
                $invoice->issue_date = \Carbon\Carbon::now()->subDays(rand(3, 60))->format('Y-m-d');
                $invoice->due_date = \Carbon\Carbon::now()->subDays(rand(3, 60))->format('Y-m-d');
                $invoice->sub_total = $randAmountOne;
                $invoice->total = $randAmountOne;
                $invoice->currency_id = $currency->id;
                $invoice->status = 'unpaid';
                $invoice->save();

                $search = new \App\UniversalSearch();
                $search->searchable_id = $invoice->id;
                $search->company_id = $company->id;
                $search->title = 'Invoice ' . $invoice->invoice_number;
                $search->route_name = 'admin.all-invoices.show';
                $search->module_type = 'invoice';
                $search->save();

                foreach ($items as $key => $item):
                    \App\InvoiceItems::create(['invoice_id' => $invoice->id, 'item_name' => $item, 'type' => $type[$key], 'quantity' => $quantity[$key], 'unit_price' => $cost_per_item[$key], 'amount' => $amount[$key]]);
                endforeach;

                //Create time logs
                $timeLog = new \App\ProjectTimeLog();
                $timeLog->project_id = $project->id;
                $timeLog->company_id = $company->id;
                $timeLog->user_id = $randomEmployee;
                $attDay = rand(3, 60);
                $timeLog->start_time = \Carbon\Carbon::now()->subDays($attDay)->format('Y-m-d') . ' ' . \Carbon\Carbon::parse('07:15 AM')->format('H:i:s');
                $timeLog->start_time = Carbon::createFromFormat('Y-m-d H:i:s', $timeLog->start_time, 'Asia/Kolkata')->setTimezone('UTC');
                $timeLog->end_time = \Carbon\Carbon::now()->subDays($attDay)->format('Y-m-d') . ' ' . Carbon::parse('04:15 PM')->format('H:i:s');
                $timeLog->end_time = Carbon::createFromFormat('Y-m-d H:i:s', $timeLog->end_time, 'Asia/Kolkata')->setTimezone('UTC');
                $timeLog->total_hours = $timeLog->end_time->diff($timeLog->start_time)->format('%d') * 24 + $timeLog->end_time->diff($timeLog->start_time)->format('%H');

                $timeLog->total_minutes = $timeLog->total_hours * 60;
                $timeLog->memo = 'working on database';
                $timeLog->save();

                $timeLog = new \App\ProjectTimeLog();
                $timeLog->project_id = $project->id;
                $timeLog->company_id = $company->id;
                $timeLog->user_id = $randomEmployee;
                $attDay = rand(3, 60);
                $timeLog->start_time = \Carbon\Carbon::now()->subDays($attDay)->format('Y-m-d') . ' ' . \Carbon\Carbon::parse('08:15 AM')->format('H:i:s');
                $timeLog->start_time = Carbon::createFromFormat('Y-m-d H:i:s', $timeLog->start_time, 'Asia/Kolkata')->setTimezone('UTC');
                $timeLog->end_time = \Carbon\Carbon::now()->subDays($attDay)->format('Y-m-d') . ' ' . Carbon::parse('04:15 PM')->format('H:i:s');
                $timeLog->end_time = Carbon::createFromFormat('Y-m-d H:i:s', $timeLog->end_time, 'Asia/Kolkata')->setTimezone('UTC');
                $timeLog->total_hours = $timeLog->end_time->diff($timeLog->start_time)->format('%d') * 24 + $timeLog->end_time->diff($timeLog->start_time)->format('%H');

                $timeLog->total_minutes = $timeLog->total_hours * 60;
                $timeLog->memo = 'working on database';
                $timeLog->save();

            });
        } catch (\Exception $e) {

        }

        $_ENV['SEEDING'] = false;
    }
}
