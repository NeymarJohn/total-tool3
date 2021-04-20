<?php

namespace App\Observers;

use App\Estimate;
use App\Notifications\NewEstimate;
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
    }

    public function created(Estimate $estimate)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if ($estimate->client) {
                if (request()->type != "save" && request()->type != "draft") {
                    $estimate->client->notify(new NewEstimate($estimate));
                }
            }
        }
    }

    public function saving(Estimate $estimate)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $estimate->company_id = company()->id;
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
