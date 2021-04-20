<?php

use Illuminate\Database\Seeder;
use App\Leave;
use Carbon\Carbon;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('leaves')->delete();

        \DB::statement('ALTER TABLE leaves AUTO_INCREMENT = 1');

        $count = env('SEED_RECORD_COUNT', 20);
        factory(\App\Leave::class, (int) $count)->create();
    }
}
