<?php

use Illuminate\Database\Seeder;
use App\Role;

class ProjectAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new Role();
        $admin->name = 'project_admin';
        $admin->display_name = 'Project Admin'; // optional
        $admin->description = 'Project admin is allowed to manage all the projects in a company.'; // optional
        $admin->save();
    }
}
