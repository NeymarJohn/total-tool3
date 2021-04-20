<?php

namespace App\Observers;

use App\UniversalSearch;

class UniversalSearchObserver
{

    public function saving(UniversalSearch $universalSearch)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $universalSearch->company_id = company()->id;
        }
    }

}
