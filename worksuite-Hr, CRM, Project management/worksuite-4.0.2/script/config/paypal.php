<?php
/**
 * Created by PhpStorm.
 * User: DEXTER
 * Date: 02/08/17
 * Time: 6:17 PM
 */

/** set your paypal credential **/
return array(
    'client_id' => 'ARS__T_4RHRztChfydh8z-tHDWT_PJmI2RS4GnCtMnMTSzT0nL1yI_UMYRMgOCnt5boFvCt-fNN1qrob',
    'secret' => 'EDpHQ4ZDPtZuY3A4fFNDinkGozFg4KL6IfXJJtk4q-f810mYUPVhfUITiLI2FvxfV7ODmaCqtO1b8QHu',
    /**
     * SDK configuration
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => env('PAYPAL_ENV', 'live'),
        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 1000000,
        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,
        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',
        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);