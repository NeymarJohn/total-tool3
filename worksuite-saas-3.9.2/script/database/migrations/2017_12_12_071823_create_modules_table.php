<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Module;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module_name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Module::insert([
            ['module_name' => 'clients', 'description' => ''],
            ['module_name' => 'employees', 'description' => ''],
            ['module_name' => 'projects', 'description' => __('modules.permission.projectNote')],
            ['module_name' => 'attendance', 'description' => __('modules.permission.attendanceNote')],
            ['module_name' => 'tasks', 'description' => __('modules.permission.taskNote')],
            ['module_name' => 'estimates', 'description' => ''],
            ['module_name' => 'invoices', 'description' => ''],
            ['module_name' => 'payments', 'description' => ''],
            ['module_name' => 'timelogs', 'description' => ''],
            ['module_name' => 'tickets', 'description' => __('modules.permission.ticketNote')],
            ['module_name' => 'events', 'description' => __('modules.permission.eventNote')],
            ['module_name' => 'messages', 'description' => ''],
            ['module_name' => 'notices', 'description' => ''],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
