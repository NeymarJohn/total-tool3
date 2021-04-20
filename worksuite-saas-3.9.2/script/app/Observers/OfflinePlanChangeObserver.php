<?php

namespace App\Observers;

use App\Notifications\OfflinePackageChangeConfirmation;
use App\Notifications\OfflinePackageChangeRequest;
use App\OfflinePlanChange;
use App\Scopes\CompanyScope;
use App\User;
use Illuminate\Support\Facades\Notification;

class OfflinePlanChangeObserver
{

    public function created(OfflinePlanChange $offlinePlanChange)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $company = company();
            $generatedBy = User::withoutGlobalScope(CompanyScope::class)->whereNull('company_id')->get();
            Notification::send($generatedBy, new OfflinePackageChangeRequest($company, $offlinePlanChange));
        }
    }

    public function updated(OfflinePlanChange $offlinePlanChange)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if ($offlinePlanChange->isDirty('status')) {

                $offlinePlanChange->company->notify(new OfflinePackageChangeConfirmation($offlinePlanChange));
            }
        }
    }
}
