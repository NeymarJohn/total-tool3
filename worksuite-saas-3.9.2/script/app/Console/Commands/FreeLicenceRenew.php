<?php

namespace App\Console\Commands;

use App\Company;
use App\Currency;
use App\GlobalSetting;
use App\ModuleSetting;
use App\Notifications\LicenseExpire;
use App\Notifications\LicenseExpirePre;
use App\Notifications\TaskCompleted;
use App\Package;
use App\PackageSetting;
use App\Setting;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FreeLicenceRenew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'free-licence-renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Free licence renew.';

    /**
     * Create a new command instance.
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
        $companies = Company::with('package')
            ->join('packages','packages.id', 'companies.package_id')
            ->where('companies.status', 'active')
            ->where('packages.is_free', 1)
            ->where('packages.is_auto_renew', 1)
            ->whereNotNull('companies.licence_expire_on')
            ->whereRaw('`companies.licence_expire_on` < ?', [Carbon::now()->format('Y-m-d')])
            ->get();

        // Set default package for license expired companies.
        if($companies) {
            foreach ($companies as $company) {
                if ($company->package_type == 'monthly') {
                    $company->licence_expire_on = Carbon::now()->addMonth()->format('Y-m-d');
                } else {
                    $company->licence_expire_on = Carbon::now()->addYear()->format('Y-m-d');
                }
                $company->save();
            }

        }

    }
}
