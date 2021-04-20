<?php

namespace App\Traits;


use App\StorageSetting;

trait FileSystemSettingTrait
{

    public function setFileSystemConfigs()
    {
        $settings = storage_setting();

        switch ($settings->filesystem) {
            case 'local':
                config(['filesystems.default' => $settings->filesystem]);
                break;

            case 'aws':
                $authKeys = json_decode($settings->auth_keys);
                $driver = $authKeys->driver;
                $key = $authKeys->key;
                $secret = $authKeys->secret;
                $region = $authKeys->region;
                $bucket = $authKeys->bucket;
                config(['filesystems.default' => $driver]);
                config(['filesystems.cloud' => $driver]);
                config(['filesystems.disks.s3.key' => $key]);
                config(['filesystems.disks.s3.secret' => $secret]);
                config(['filesystems.disks.s3.region' => $region]);
                config(['filesystems.disks.s3.bucket' => $bucket]);
                break;

        }

    }

}
