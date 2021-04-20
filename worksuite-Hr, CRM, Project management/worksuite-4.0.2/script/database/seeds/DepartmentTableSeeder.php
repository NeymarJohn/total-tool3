<?php

use App\Designation;
use App\Team;
use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            'Marketing',
            'Sales',
            'Human Resources',
            'Public Relations',
            'Research',
            'Finance'
        ];

        $designations = [
            'Trainee',
            'Senior',
            'Junior',
            'Team Lead',
            'Project Manager'
        ];

        foreach ($departments as $department) {
            Team::create([
                'team_name' => $department,
            ]);
        }
        foreach ($designations as $designation) {
            Designation::create([
                'name' => $designation,
            ]);
        }
    }
}
