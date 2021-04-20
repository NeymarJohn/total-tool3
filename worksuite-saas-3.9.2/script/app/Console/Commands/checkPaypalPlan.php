<?php

namespace App\Console\Commands;

use App\GlobalSetting;
use Illuminate\Console\Command;
use App\PaypalInvoice;
use App\StripeSetting;
use Illuminate\Support\Facades\Config;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Plan;
use App\Company;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use App\User;
use App\Notifications\CancelPaypalLicense;

class checkPaypalPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-paypal-plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check paypal plan status';

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
        $now = Carbon::now();
        $credential = StripeSetting::first();
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($credential->paypal_client_id, $credential->paypal_secret));
        $this->_api_context->setConfig($paypal_conf['settings']);
        $paypalPlans = PaypalInvoice::whereNotNull('paid_on')->where('status', 'pending')->get();

        foreach ($paypalPlans as $paypalPlan) {
             $plan = Plan::get($paypalPlan->plan_id, $this->_api_context);
             
             if($plan->state == "ACTIVE") {
                PaypalInvoice::where('id', $paypalPlan->id)
                    ->update([
                        'status' => 'paid'
                    ]);
                $company = Company::find($paypalPlan->company_id);
                $company->status = 'active';
                $company->save();
             }
             else {
                $timeDifference = $paypalPlan->paid_on->diffInMinutes($now, false);
                if ($timeDifference > 60) { //expire license if 1 hour is over
                    $company = Company::find($paypalPlan->company_id);
                    if ($company->status != 'license_expired') {
                        $company->status = 'license_expired';
                        $company->save();

                        $admin = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                            ->join('roles', 'roles.id', '=', 'role_user.role_id')
                            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
                            ->where('roles.name', 'admin')
                            ->where('roles.company_id', $company->id)
                            ->orderBy('users.id', 'asc')
                            ->first();

                        Notification::send($admin, new CancelPaypalLicense($company, $paypalPlan->id));
    
                        $generatedBy = User::whereNull('company_id')->get();
                        Notification::send($generatedBy, new CancelPaypalLicense($company, $paypalPlan->id));
                    }
                }
             }
        }
    }
}
