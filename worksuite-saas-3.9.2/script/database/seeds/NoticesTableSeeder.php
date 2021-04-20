<?php

use Illuminate\Database\Seeder;

class NoticesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('notices')->delete();

        \DB::statement('ALTER TABLE notices AUTO_INCREMENT = 1');
        $count = env('SEED_RECORD_COUNT', 30);

        factory(\App\Notice::class, (int) $count)->create();
    }
}
