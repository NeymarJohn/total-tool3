<?php

namespace App\Observers;

use App\FileStorage;

class FileStorageObserver
{
    public function creating(FileStorage $fileStorage)
    {
        if(company()) {
            $fileStorage->company_id = company()->id;
        }
    }
}
