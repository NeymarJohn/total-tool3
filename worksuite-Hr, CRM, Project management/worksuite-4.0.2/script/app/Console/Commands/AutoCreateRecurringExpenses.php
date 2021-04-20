<?php

namespace App\Console\Commands;

use App\Expense;
use App\ExpenseRecurring;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoCreateRecurringExpenses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring-expenses-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'auto create recurring expenses ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $company = \App\Setting::first();

        $recurringExpenses = ExpenseRecurring::with('recurrings')->where('status', 'active')->get();
        $recurringExpenses->each(function($recurring) use ($company) {
            if($recurring->unlimited_recurring ==  1 || ($recurring->unlimited_recurring == 0 && $recurring->recurrings->count() <= $recurring->billing_cycle)){
                // Why type of date is today
                $today = Carbon::now()->timezone($company->timezone);
                $isMonthly = ($today->day === $recurring->day_of_month);
                $isWeekly = ($today->dayOfWeek === $recurring->day_of_week);
                $isBiWeekly = ($isWeekly && $today->weekOfYear % 2 === 0);
                $isQuarterly = ($isMonthly && $today->month % 3 === 1);
                $isHalfYearly = ($isMonthly && $today->month % 6 === 1);
                $isAnnually = ($isMonthly && $today->month % 12 === 1);
                if ($recurring->rotation === 'daily' ||
                    ($recurring->rotation === 'weekly' && $isWeekly) ||
                    ($recurring->rotation === 'bi-weekly' && $isBiWeekly) ||
                    ($recurring->rotation === 'monthly' && $isMonthly) ||
                    ($recurring->rotation === 'quarterly' && $isQuarterly) ||
                    ($recurring->rotation === 'half-yearly' && $isHalfYearly) ||
                    ($recurring->rotation === 'annually' && $isAnnually)
                ) {
                    $this->makeExpense($recurring);
                }
            }
        });
    }
    private function makeExpense($recurring)
    {
        $expense = new Expense();
        $expense->expenses_recurring_id = $recurring->id;
        $expense->category_id           = $recurring->category_id;
        $expense->project_id            = $recurring->project_id;
        $expense->currency_id           = $recurring->currency_id;
        $expense->user_id               = $recurring->user_id;
        $expense->created_by            = $recurring->created_by;
        $expense->item_name             = $recurring->item_name;
        $expense->description           = $recurring->description;
        $expense->price                 = $recurring->price;
        $expense->purchase_date         = Carbon::now()->format('Y-m-d');
        $expense->created_by            = $recurring->created_by;
        $expense->status                = 'approved';
        $expense->save();
    }
}
