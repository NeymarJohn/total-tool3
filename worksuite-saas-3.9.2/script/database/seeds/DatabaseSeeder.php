<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run() {

        // Set Seeding to true check if data is seeding.
        // This is required to stop notification in installation
        config(['app.seeding' => true]);

        $this->call(GlobalCurrencySeeder::class);
        $this->call(GlobalSettingTableSeeder::class);
        $this->call(PackageTableSeeder::class);

        $this->call(EmailSettingSeeder::class);

        $this->call(FrontSeeder::class);
        $this->call(FrontFeatureSeeder::class);
        $this->call(UsersTableSeeder::class);

        if (!App::environment('codecanyon')) {
            $this->call(ProjectCategorySeeder::class);
            $this->call(ProjectSeeder::class);
            $this->call(EstimateSeeder::class);
            $this->call(ExpenseSeeder::class);
            $this->call(TicketSeeder::class);
            $this->call(LeaveSeeder::class);
            $this->call(NoticesTableSeeder::class);
            $this->call(EventTableSeeder::class);
            $this->call(AttendanceTableSeeder::class);
            $this->call(HolidaySeeder::class);
        }
        config(['app.seeding' => false]);
    }

}
