<?php

namespace App\Observers;

use App\StorageSetting;

class StorageSettingObserver
{

    public function saving(StorageSetting $storage)
    {
        session()->forget('storage_setting');
    }

}
