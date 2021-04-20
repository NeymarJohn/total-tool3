<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Permission;

class AddModuleIdColumnPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->integer('module_id')->unsigned()->after('description');
            $table->foreign('module_id')->references('id')->on('modules')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Permission::insert([
            ['name' => 'add_clients', 'display_name' => 'Add Clients', 'module_id' => 1],
            ['name' => 'view_clients', 'display_name' => 'View Clients', 'module_id' => 1],
            ['name' => 'edit_clients', 'display_name' => 'Edit Clients', 'module_id' => 1],
            ['name' => 'delete_clients', 'display_name' => 'Delete Clients', 'module_id' => 1],

            ['name' => 'add_employees', 'display_name' => 'Add Employees', 'module_id' => 2],
            ['name' => 'view_employees', 'display_name' => 'View Employees', 'module_id' => 2],
            ['name' => 'edit_employees', 'display_name' => 'Edit Employees', 'module_id' => 2],
            ['name' => 'delete_employees', 'display_name' => 'Delete Employees', 'module_id' => 2],

            ['name' => 'add_projects', 'display_name' => 'Add Project', 'module_id' => 3],
            ['name' => 'view_projects', 'display_name' => 'View Project', 'module_id' => 3],
            ['name' => 'edit_projects', 'display_name' => 'Edit Project', 'module_id' => 3],
            ['name' => 'delete_projects', 'display_name' => 'Delete Project', 'module_id' => 3],

            ['name' => 'add_attendance', 'display_name' => 'Add Attendance', 'module_id' => 4],
            ['name' => 'view_attendance', 'display_name' => 'View Attendance', 'module_id' => 4],

            ['name' => 'add_tasks', 'display_name' => 'Add Tasks', 'module_id' => 5],
            ['name' => 'view_tasks', 'display_name' => 'View Tasks', 'module_id' => 5],
            ['name' => 'edit_tasks', 'display_name' => 'Edit Tasks', 'module_id' => 5],
            ['name' => 'delete_tasks', 'display_name' => 'Delete Tasks', 'module_id' => 5],

            ['name' => 'add_estimates', 'display_name' => 'Add Estimates', 'module_id' => 6],
            ['name' => 'view_estimates', 'display_name' => 'View Estimates', 'module_id' => 6],
            ['name' => 'edit_estimates', 'display_name' => 'Edit Estimates', 'module_id' => 6],
            ['name' => 'delete_estimates', 'display_name' => 'Delete Estimates', 'module_id' => 6],

            ['name' => 'add_invoices', 'display_name' => 'Add Invoices', 'module_id' => 7],
            ['name' => 'view_invoices', 'display_name' => 'View Invoices', 'module_id' => 7],
            ['name' => 'edit_invoices', 'display_name' => 'Edit Invoices', 'module_id' => 7],
            ['name' => 'delete_invoices', 'display_name' => 'Delete Invoices', 'module_id' => 7],

            ['name' => 'add_payments', 'display_name' => 'Add Payments', 'module_id' => 8],
            ['name' => 'view_payments', 'display_name' => 'View Payments', 'module_id' => 8],
            ['name' => 'edit_payments', 'display_name' => 'Edit Payments', 'module_id' => 8],
            ['name' => 'delete_payments', 'display_name' => 'Delete Payments', 'module_id' => 8],

            ['name' => 'add_timelogs', 'display_name' => 'Add Timelogs', 'module_id' => 9],
            ['name' => 'view_timelogs', 'display_name' => 'View Timelogs', 'module_id' => 9],
            ['name' => 'edit_timelogs', 'display_name' => 'Edit Timelogs', 'module_id' => 9],
            ['name' => 'delete_timelogs', 'display_name' => 'Delete Timelogs', 'module_id' => 9],

            ['name' => 'add_tickets', 'display_name' => 'Add Tickets', 'module_id' => 10],
            ['name' => 'view_tickets', 'display_name' => 'View Tickets', 'module_id' => 10],
            ['name' => 'edit_tickets', 'display_name' => 'Edit Tickets', 'module_id' => 10],
            ['name' => 'delete_tickets', 'display_name' => 'Delete Tickets', 'module_id' => 10],

            ['name' => 'add_events', 'display_name' => 'Add Events', 'module_id' => 11],
            ['name' => 'view_events', 'display_name' => 'View Events', 'module_id' => 11],
            ['name' => 'edit_events', 'display_name' => 'Edit Events', 'module_id' => 11],
            ['name' => 'delete_events', 'display_name' => 'Delete Events', 'module_id' => 11],

            ['name' => 'add_notice', 'display_name' => 'Add Notice', 'module_id' => 12],
            ['name' => 'view_notice', 'display_name' => 'View Notice', 'module_id' => 12],
            ['name' => 'edit_notice', 'display_name' => 'Edit Notice', 'module_id' => 12],
            ['name' => 'delete_notice', 'display_name' => 'Delete Notice', 'module_id' => 12],
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropColumn(['module_id']);
        });

        \Illuminate\Support\Facades\DB::table('permissions')->delete();
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE permissions AUTO_INCREMENT = 1;');

    }
}
