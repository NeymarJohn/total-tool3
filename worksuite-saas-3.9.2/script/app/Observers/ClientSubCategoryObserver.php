<?php

namespace App\Observers;

use App\ClientSubCategory;

class ClientSubCategoryObserver
{
    public function saving(ClientSubCategory $clientSubCategory)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if (company()) {
                $clientSubCategory->company_id = company()->id;
            }
        }
    }

}
