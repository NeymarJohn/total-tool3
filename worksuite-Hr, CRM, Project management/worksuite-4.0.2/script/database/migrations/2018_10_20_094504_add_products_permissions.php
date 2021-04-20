<?php

use Illuminate\Database\Migrations\Migration;
use App\Module;
use App\Permission;

class AddProductsPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $moduleCheck = Module::where('module_name', 'products')->first();

        if (!$moduleCheck){
            $module = new Module();
            $module->module_name = 'products';
            $module->save();
            $id = $module->id;
        }
        else{
            $id = $moduleCheck->id;
        }

        Permission::insert([
            ['name' => 'add_product', 'display_name' => 'Add Product', 'module_id' => $id],
            ['name' => 'view_product', 'display_name' => 'View Product', 'module_id' => $id],
            ['name' => 'edit_product', 'display_name' => 'Edit Product', 'module_id' => $id],
            ['name' => 'delete_product', 'display_name' => 'Delete Product', 'module_id' => $id],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('module_id', 16)->delete();
    }
}
