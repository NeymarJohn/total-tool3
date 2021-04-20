<?php

namespace App\Observers;

use App\ClientCategory;

class ClientCategoryObserver
{
    public function saving(ClientCategory $clientCategory)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if (company()) {
                $clientCategory->company_id = company()->id;
            }
        }
    }

}
