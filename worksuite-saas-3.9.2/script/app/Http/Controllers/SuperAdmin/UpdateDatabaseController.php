<?php

namespace App\Http\Controllers\SuperAdmin;

use GuzzleHttp\Client;
use App\Helper\Reply;
use Illuminate\Http\Request;
use Zip;
use Illuminate\Support\Facades\File;

class UpdateDatabaseController extends SuperAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.updates';
        $this->pageIcon = 'ti-reload';
    }

    public function index()
    {
        return view('super-admin.update-database.index', $this->data);
    }

    public function store(Request $request)
    {

        config(['filesystems.default' => 'storage']);
        $path = storage_path('app') . '/Modules/'.$request->file->getClientOriginalName();
        if(file_exists($path)){
            File::delete($path);
        }

        $request->file->storeAs('/', $request->file->getClientOriginalName());
    }

    public function manual()
    {
        $client = new Client();
        $res = $client->request('GET', config('froiden_envato.updater_file_path'), ['verify' => false]);
        $lastVersion = $res->getBody();
        $lastVersion = json_decode($lastVersion, true);

        if ($lastVersion['version'] > File::get('version.txt')) {
            $this->lastVersion = $lastVersion['version'];
            $this->updateInfo = $lastVersion['description'];
        }

        $domain = \request()->getHost();
        $purchaseCode = $this->global->purchase_code;
        $fullUrl = urlencode(url()->full());
        $envatoItemId = config('froiden_envato.envato_item_id');

        $this->linkParameter = "domain=" . $domain . "&purchaseCode=" . $purchaseCode . "&appUrl=" . $fullUrl . "&itemId=" . $envatoItemId;

        $this->encryptedDownloadLink = $this->encryptDownloadLink($this->linkParameter);

        $this->updateFilePath = config('froiden_envato.tmp_path');
        return view('super-admin.update-database.manual', $this->data);
    }

    public function encryptDownloadLink($token)
    {
        $cipher_method = 'aes-128-ctr';
        $enc_key = openssl_digest('froiden-envato', 'SHA256', TRUE);
        $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_method));
        $crypted_token = openssl_encrypt($token, $cipher_method, $enc_key, 0, $enc_iv) . "::" . bin2hex($enc_iv);
        unset($token, $cipher_method, $enc_key, $enc_iv);

        return $crypted_token;
    }

    public function decryptDownloadLink($crypted_token)
    {
        list($crypted_token, $enc_iv) = explode("::", $crypted_token);
        $cipher_method = 'aes-128-ctr';
        $enc_key = openssl_digest('froiden-envato', 'SHA256', TRUE);
        $token = openssl_decrypt($crypted_token, $cipher_method, $enc_key, 0, hex2bin($enc_iv));
        unset($crypted_token, $cipher_method, $enc_key, $enc_iv);
        return $token;
    }

    public function deleteFile(Request $request)
    {
        $filePath = $request->filePath;
        File::delete($filePath);
        return Reply::success(__('messages.fileDeleted'));
    }

    public function install(Request $request)
    {
        File::put(public_path() . '/install-version.txt', 'complete');

        $filePath = $request->filePath;
        $zip = Zip::open($filePath);

        // extract whole archive
        $zip->extract(base_path());
        session()->forget('check_migrate_status');
    }
}
