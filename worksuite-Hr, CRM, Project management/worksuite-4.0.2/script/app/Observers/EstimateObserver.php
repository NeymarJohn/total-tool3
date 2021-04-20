<?php

namespace App\Observers;

use App\Estimate;
use App\Events\EstimateDeclinedEvent;
use App\Events\NewEstimateEvent;
use App\UniversalSearch;

class EstimateObserver
{
    public function creating(Estimate $estimate)
    {
        if (request()->type && (request()->type == "save" || request()->type == "draft")) {
            $estimate->send_status = 0;
        }

        if (request()->type == "draft") {
            $estimate->status = 'draft';
        }

        $estimate->estimate_number = Estimate::lastEstimateNumber() + 1;
    }

    public function created(Estimate $estimate)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if (request()->type != "save" && request()->type != "draft") {
                event(new NewEstimateEvent($estimate));
            }
        }
    }

    public function updated(Estimate $estimate){
        if($estimate->status == 'declined'){
            event(new EstimateDeclinedEvent($estimate));
        }
    }

    public function deleting(Estimate $estimate)
    {
        $universalSearches = UniversalSearch::where('searchable_id', $estimate->id)->where('module_type', 'estimate')->get();
        if ($universalSearches) {
            foreach ($universalSearches as $universalSearch) {
                UniversalSearch::destroy($universalSearch->id);
            }
        }
    }
}
