<?php

namespace App\Traits;

use Froiden\Envato\Helpers\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

trait ModuleVerify
{

    private $appSetting;
    private $reply;


    private function setSetting($module)
    {
        $setting = config($module . '.setting');
        $this->appSetting = (new $setting)::first();
    }

    /**
     * @param $module
     * @return bool
     * Check if Purchase code is stored in settings table and is verified
     */
    public function isModuleLegal($module)
    {
        // Check if verification is required for this module or not
        if (!config($module.'.verification_required')) {
            return true;
        }

        $this->setSetting($module);
        $domain = \request()->getHost();

        if ($domain == 'localhost' || $domain == '127.0.0.1' || $domain == '::1') {
            return true;
        }

        if (is_null($this->appSetting->purchase_code)) {
            return false;
        }

        $version = File::get(module_path($module).'/version.txt');

        $data = [
            'purchaseCode' => $this->appSetting->purchase_code,
            'domain' => $domain,
            'itemId' => config($module.'.envato_item_id'),
            'appUrl' => urlencode(url()->full()),
            'version' => $version,
        ];

        $response = $this->curl($data);
        // $this->saveSupportSettings($response);

        if ($response['status'] == 'success') {
            return true;
        }

        return false;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Show verify page for verification
     */
    public function verifyModulePurchase($module)
    {
        return view('admin.custom-modules.show', compact('module'));
    }

    /**
     * @param Request $request
     * @return array
     * Send request on froiden envato server to validate
     */
    public function modulePurchaseVerified($module, $purchaseCode = null)
    {
        $this->setSetting($module);

        if (!is_null($purchaseCode)) {
            return $this->getServerData($purchaseCode, $module);
        }

        return $this->getServerData($this->appSetting->purchase_code, $module, false);
    }


    /**
     * @param $purchaseCode
     */
    public function saveToModuleSettings($purchaseCode, $module)
    {
        $this->setSetting($module);
        $setting = $this->appSetting;
        $setting->purchase_code = $purchaseCode;
        $setting->save();
    }

    public function saveSupportModuleSettings($response, $module)
    {
        $this->setSetting($module);
        if (isset($response['supported_until']) && ($response['supported_until'] !== $this->appSetting->supported_until)) {
            $this->appSetting->supported_until = $response['supported_until'];
            $this->appSetting->save();
        }
    }

    /**
     * @param $postData
     * @return mixed
     * Curl post to the server
     */
    public function curl($postData)
    {
        // Verify purchase

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, config('froiden_envato.verify_url'));

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);
            $response = json_decode($server_output, true);
            curl_close($ch);

            return $response;
        } catch (\Exception $e) {

            return [
                'status' => 'success',
                'messages' => 'Your purchase code is successfully verified'
            ];
        }
    }

    /**
     * @param $purchaseCode
     * @param bool $savePurchaseCode
     * @return mixed
     */
    private function getServerData($purchaseCode, $module, $savePurchaseCode = true)
    {
        $version = File::get(module_path($module).'/version.txt');

        $postData = [
            'purchaseCode' => $purchaseCode,
            'domain' => \request()->getHost(),
            'itemId' => config($module . '.envato_item_id'),
            'appUrl' => urlencode(url()->full()),
            'version' => $version,
        ];

        // Send request to froiden server to validate the license
        $response = $this->curl($postData);

        // $this->saveSupportModuleSettings($response, $module);

        if ($response['status'] === 'success') {

            if ($savePurchaseCode) {
                $this->saveToModuleSettings($purchaseCode, $module);
            }

            return Reply::successWithData($response['message'] . ' <a href="">Click to go back</a>', ['server' => $response]);
        }

        return Reply::error($response['message'], null, ['server' => $response]);
    }

    public function showInstall()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            echo view('vendor.froiden-envato.install_message');
            exit(1);
        }
    }
}
