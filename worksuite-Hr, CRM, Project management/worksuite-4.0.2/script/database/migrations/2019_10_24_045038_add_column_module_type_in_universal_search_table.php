<?php

use App\ClientDetails;
use App\CreditNotes;
use App\EmployeeDetails;
use App\Estimate;
use App\Invoice;
use App\Lead;
use App\Notice;
use App\Project;
use App\Proposal;
use App\Task;
use App\Ticket;
use App\UniversalSearch;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnModuleTypeInUniversalSearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('universal_search', function (Blueprint $table) {
            $table->enum('module_type', ['ticket', 'invoice', 'notice', 'proposal', 'task', 'creditNote', 'client', 'employee', 'project', 'estimate', 'lead'])->nullable()->default(null)->after('searchable_id');
        });

        $universalSearches = UniversalSearch::all();
        if ($universalSearches->count() > 0){
            foreach ($universalSearches as $universalSearch){
                UniversalSearch::destroy($universalSearch->id);
            }
        }
        $tickets = Ticket::all();
        if ($tickets->count() > 0){
            foreach ($tickets as $ticket){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $ticket->id;
                $universalSearch->title = 'Ticket: '.$ticket->subject;
                $universalSearch->route_name = 'admin.tickets.edit';
                $universalSearch->module_type = 'ticket';
                $universalSearch->save();
            }
        }

        $proposals = Proposal::all();
        if ($proposals->count() > 0){
            foreach ($proposals as $proposal){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $proposal->id;
                $universalSearch->title = 'Proposal: '.$proposal->id;
                $universalSearch->route_name = 'admin.proposals.edit';
                $universalSearch->module_type = 'proposal';
                $universalSearch->save();
            }
        }

        $invoices = Invoice::all();
        if ($invoices->count() > 0){
            foreach ($invoices as $invoice){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $invoice->id;
                $universalSearch->title = 'Invoice ' . $invoice->invoice_number;
                $universalSearch->route_name = 'admin.all-invoices.show';
                $universalSearch->module_type = 'invoice';
                $universalSearch->save();
            }
        }

        $notices = Notice::all();
        if ($notices->count() > 0){
            foreach ($notices as $notice){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $notice->id;
                $universalSearch->title = 'Notice: '.$notice->heading;
                $universalSearch->route_name = 'admin.notices.edit';
                $universalSearch->module_type = 'notice';
                $universalSearch->save();
            }
        }

        $tasks = Task::all();
        if ($tasks->count() > 0){
            foreach ($tasks as $task){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $task->id;
                $universalSearch->title = 'Task: '.$task->heading;
                $universalSearch->route_name = 'admin.all-tasks.edit';
                $universalSearch->module_type = 'task';
                $universalSearch->save();
            }
        }

        $creditNotes = CreditNotes::all();
        if ($creditNotes->count() > 0){
            foreach ($creditNotes as $creditNote){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $creditNote->id;
                $universalSearch->title = 'Credit Note: '.$creditNote->cn_number;
                $universalSearch->route_name = 'admin.all-credit-notes.show';
                $universalSearch->module_type = 'creditNote';
                $universalSearch->save();
            }
        }

        $projects = Project::all();
        if ($projects->count() > 0){
            foreach ($projects as $project){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $project->id;
                $universalSearch->title = 'Project: '.$project->project_name;
                $universalSearch->route_name = 'admin.projects.show';
                $universalSearch->module_type = 'project';
                $universalSearch->save();
            }
        }

        $estimates = Estimate::all();
        if ($estimates->count() > 0){
            foreach ($estimates as $estimate){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $estimate->id;
                $universalSearch->title = 'Estimate #'.$estimate->id;
                $universalSearch->route_name = 'admin.estimates.edit';
                $universalSearch->module_type = 'estimate';
                $universalSearch->save();
            }
        }

        $leads = Lead::all();
        if ($leads->count() > 0){
            foreach ($leads as $lead){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $lead->id;
                $universalSearch->title = $lead->client_name;
                $universalSearch->route_name = 'admin.leads.show';
                $universalSearch->module_type = 'lead';
                $universalSearch->save();

                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $lead->id;
                $universalSearch->title = $lead->client_email;
                $universalSearch->route_name = 'admin.leads.show';
                $universalSearch->module_type = 'lead';
                $universalSearch->save();

                if ($lead->company_name){
                    $universalSearch = new UniversalSearch();
                    $universalSearch->searchable_id = $lead->id;
                    $universalSearch->title = $lead->company_name;
                    $universalSearch->route_name = 'admin.leads.show';
                    $universalSearch->module_type = 'lead';
                    $universalSearch->save();
                }
            }
        }

        $clients = ClientDetails::all();
        if ($clients->count() > 0){
            foreach ($clients as $client){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $client->user_id;
                $universalSearch->title = 'Client '.$client->name;
                $universalSearch->route_name = 'admin.clients.edit';
                $universalSearch->module_type = 'client';
                $universalSearch->save();

                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $client->user_id;
                $universalSearch->title = 'Client '.$client->email;
                $universalSearch->route_name = 'admin.clients.edit';
                $universalSearch->module_type = 'client';
                $universalSearch->save();

                if ($client->company_name){
                    $universalSearch = new UniversalSearch();
                    $universalSearch->searchable_id = $client->user_id;
                    $universalSearch->title = 'Client '.$client->company_name;
                    $universalSearch->route_name = 'admin.clients.edit';
                    $universalSearch->module_type = 'client';
                    $universalSearch->save();
                }
            }
        }
        $employees = EmployeeDetails::with('user')->get();
        if ($employees->count() > 0){
            foreach ($employees as $employee){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $employee->user_id;
                $universalSearch->title = 'Employee '.$employee->user->name;
                $universalSearch->route_name = 'admin.employees.show';
                $universalSearch->module_type = 'employee';
                $universalSearch->save();

                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $employee->user_id;
                $universalSearch->title = 'Employee '.$employee->user->email;
                $universalSearch->route_name = 'admin.employees.show';
                $universalSearch->module_type = 'employee';
                $universalSearch->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('universal_search', function (Blueprint $table) {
            $table->dropColumn('module_type');
        });
    }
}
