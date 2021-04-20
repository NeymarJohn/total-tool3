<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Permission;

class AddLeavesPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $leave = \App\Module::where('module_name', 'leaves')->first();

        Permission::insert([
            ['name' => 'add_leave', 'display_name' => 'Add Leave', 'module_id' => $leave->id],
            ['name' => 'view_leave', 'display_name' => 'View Leave', 'module_id' => $leave->id],
            ['name' => 'edit_leave', 'display_name' => 'Edit Leave', 'module_id' => $leave->id],
            ['name' => 'delete_leave', 'display_name' => 'Delete Leave', 'module_id' => $leave->id],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $leave = \App\Module::where('module_name', 'leaves')->first();
        Permission::where('module_id', $leave->id)->delete();
    }
}
