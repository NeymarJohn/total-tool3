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
        Permission::insert([
            ['name' => 'add_leave', 'display_name' => 'Add Leave', 'module_id' => 13],
            ['name' => 'view_leave', 'display_name' => 'View Leave', 'module_id' => 13],
            ['name' => 'edit_leave', 'display_name' => 'Edit Leave', 'module_id' => 13],
            ['name' => 'delete_leave', 'display_name' => 'Delete Leave', 'module_id' => 13],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('module_id', 13)->delete();
    }
}
