<?php

use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = \App\Company::first();

        $year = \Carbon\Carbon::now()->format('Y');

        $daysss = [];
        $this->days = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ];

        $office_holiday_days = [6, 7];
        foreach ($office_holiday_days as $holiday) {
            $daysss[] = $this->days[($holiday - 1)];
            $day = $holiday;
            if ($holiday == 7) {
                $day = 0;
            }
            $dateArr = $this->getDateForSpecificDayBetweenDates($year . '-01-01', $year . '-12-31', ($day));

            foreach ($dateArr as $date) {
                \App\Holiday::firstOrCreate([
                    'company_id' => $company->id,
                    'date' => $date,
                    'occassion' => $this->days[$day]
                ]);
            }
        }

    }

    public function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber)
    {
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        $dateArr = [];

        do {
            if (date('w', $startDate) != $weekdayNumber) {
                $startDate += (24 * 3600); // add 1 day
            }
        } while (date('w', $startDate) != $weekdayNumber);


        while ($startDate <= $endDate) {
            $dateArr[] = date('Y-m-d', $startDate);
            $startDate += (7 * 24 * 3600); // add 7 days
        }

        return ($dateArr);
    }
}
