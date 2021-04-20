<?php

use Illuminate\Database\Seeder;

class EmailSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        // When new user registers or added by admin
        \App\EmailNotificationSetting::create([
            'setting_name' => 'User Registration/Added by Admin',
            'send_email' => 'yes',
            'slug' => str_slug('User Registration/Added by Admin')
        ]);

        // When employee is added to project
        \App\EmailNotificationSetting::create([
            'setting_name' => 'Employee Assign to Project',
            'send_email' => 'yes',
            'slug' => str_slug('Employee Assign to Project')
        ]);

        // When notice published by admin
        \App\EmailNotificationSetting::create([
            'setting_name' => 'New Notice Published',
            'send_email' => 'no',
            'slug' => str_slug('New Notice Published')
        ]);

        // When user is assigned to a task
        \App\EmailNotificationSetting::create([
            'setting_name' => 'User Assign to Task',
            'send_email' => 'yes',
            'slug' => str_slug('User Assign to Task')
        ]);
    }

}
