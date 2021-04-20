<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Permission;
use App\Module;

class AddLeadsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Module::insert([
            ['module_name' => 'leads']
        ]);

        Permission::insert([
            ['name' => 'add_lead', 'display_name' => 'Add Lead', 'module_id' => 14],
            ['name' => 'view_lead', 'display_name' => 'View Lead', 'module_id' => 14],
            ['name' => 'edit_lead', 'display_name' => 'Edit Lead', 'module_id' => 14],
            ['name' => 'delete_lead', 'display_name' => 'Delete Lead', 'module_id' => 14],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('module_id', 14)->delete();
    }
}
