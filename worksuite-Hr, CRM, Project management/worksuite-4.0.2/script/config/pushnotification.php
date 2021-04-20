<?php
/**
 * @see https://github.com/Edujugon/PushNotification
 */

return [
    'gcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'My_ApiKey',
    ],
    'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => env('FCM_KEY'),
    ],
    'apn' => [
        'certificate' => base_path() .'/'. env('APN_PEM', 'aps.pem'),
        'dry_run' => true,
    ],
];
