<?php

use App\FileStorage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('superAdmin')) {
    function superAdmin()
    {
        if (session()->has('user')) {
            return session('user');
        }

        $user = auth()->user();

        if ($user) {
            session(['user' => $user]);
            return session('user');
        }

        return null;
    }
}

if (!function_exists('user')) {
    function user()
    {
        if (session()->has('user')) {
            return session('user');
        }
        $user = auth()->user();

        if ($user) {
            session(['user' => $user]);
            return session('user');
        }

        return null;
    }
}

if (!function_exists('company')) {
    function company()
    {

        if (session()->has('company')) {
            return session('company');
        }

        if (auth()->user()) {
            $companyId = auth()->user()->company_id;
            if (!is_null($companyId)) {
                $company = \App\Company::find($companyId);
                session(['company' => $company]);
            }
            return session('company');
        }

        return false;
    }
}

if (!function_exists('asset_url')) {

    // @codingStandardsIgnoreLine
    function asset_url($path)
    {
        if (config('filesystems.default') == 's3') {
            //            return "https://" . config('filesystems.disks.s3.bucket') . ".s3.amazonaws.com/".$path;
        }

        $path = 'user-uploads/' . $path;
        $storageUrl = $path;

        if (!Str::startsWith($storageUrl, 'http')) {
            return url($storageUrl);
        }

        return $storageUrl;
    }
}

if (!function_exists('worksuite_plugins')) {

    function worksuite_plugins()
    {

        if (!session()->has('worksuite_plugins')) {
            $plugins = \Nwidart\Modules\Facades\Module::allEnabled();

            session(['worksuite_plugins' => array_keys($plugins)]);
        }
        return session('worksuite_plugins');
    }
}

if (!function_exists('isSeedingData')) {

    /**
     * Check if app is seeding data
     * @return boolean
     */
    function isSeedingData()
    {
        // We set config(['app.seeding' => true]) at the beginning of each seeder. And check here
        return config('app.seeding');
    }
}
if (!function_exists('isRunningInConsoleOrSeeding')) {

    /**
     * Check if app is seeding data
     * @return boolean
     */
    function isRunningInConsoleOrSeeding()
    {
        // We set config(['app.seeding' => true]) at the beginning of each seeder. And check here
        return app()->runningInConsole() || isSeedingData();
    }
}


if (!function_exists('asset_url_local_s3')) {

    // @codingStandardsIgnoreLine
    function asset_url_local_s3($path)
    {
        if (config('filesystems.default') == 's3') {
            return generateS3SignedUrl($path);
        }

        $path = 'user-uploads/' . $path;
        $storageUrl = $path;

        if (!Str::startsWith($storageUrl, 'http')) {
            return url($storageUrl);
        }

        return $storageUrl;
    }
}

if (!function_exists('download_local_s3')) {

    // @codingStandardsIgnoreLine
    function download_local_s3($file, $path)
    {
        if (config('filesystems.default') == 's3') {
            $ext = pathinfo($file->filename, PATHINFO_EXTENSION);
            $fs = Storage::getDriver();
            $stream = $fs->readStream($path);

            return Response::stream(function () use ($stream) {
                fpassthru($stream);
            }, 200, [
                "Content-Type" => $ext,
                "Content-Length" => $file->size,
                "Content-disposition" => "attachment; filename=\"" . basename($file->filename) . "\"",
            ]);
        }

        $path = 'user-uploads/' . $path;
        return response()->download($path, $file->filename);
    }
}
if (!function_exists('module_enabled')) {
    function module_enabled($moduleName)
    {
        return \Nwidart\Modules\Facades\Module::collections()->has($moduleName);
    }
}

if (!function_exists('getDomainSpecificUrl')) {
    function getDomainSpecificUrl($url, $company = false)
    {
        if (module_enabled('Subdomain')) {
            // If company specific

            if ($company) {
                $url = str_replace(request()->getHost(), $company->sub_domain, $url);
                $url = str_replace('www.', '', $url);
                // Replace https to http for sub-domain to
                if (!\config('app.redirect_https')) {
                    return $url = str_replace('https', 'http', $url);
                }
            }

            // If there is no company and url has login means
            // New superadmin is created
            return $url = str_replace('login', 'super-admin-login', $url);
        }

        return $url;
    }
}

if (!function_exists('getSubdomainSchema')) {
    function getSubdomainSchema()
    {

        if (!session()->has('subdomain_schema')) {
            if (\Illuminate\Support\Facades\Schema::hasTable('sub_domain_module_settings')) {
                $data = \Illuminate\Support\Facades\DB::table('sub_domain_module_settings')->first();
            }
            session(['subdomain_schema' => isset($data->schema) ? $data->schema : 'http']);
        }
        return session('subdomain_schema');
    }
}

if (!function_exists('global_settings')) {

    function global_settings()
    {
        //        if (!session()->has('global_settings')) {
        session(['global_settings' => \App\GlobalSetting::with('currency')->first()]);
        //        }

        return session('global_settings');
    }
}

if (!function_exists('company_setting')) {

    function company_setting()
    {
        if (!session()->has('company_setting')) {
            session(['company_setting' => \App\Company::with('currency', 'package')->withoutGlobalScope('active')->where('id', auth()->user()->company_id)->first()]);
        }

        return session('company_setting');
    }
}

if (!function_exists('check_migrate_status')) {

    function check_migrate_status()
    {
        //        if (!session()->has('check_migrate_status')) {

        $status = Artisan::call('migrate:check');

        if ($status && !request()->ajax()) {
            Artisan::call('migrate', array('--force' => true)); //migrate database
            Artisan::call('optimize:clear');
        }
        //            session(['check_migrate_status' => true]);
        //        }

        //        return session('check_migrate_status');
    }
}

if (!function_exists('time_log_setting')) {

    function time_log_setting()
    {
        if (!session()->has('time_log_setting')) {
            session(['time_log_setting' => \App\LogTimeFor::first()]);
        }

        return session('time_log_setting');
    }
}

if (!function_exists('package_setting')) {

    function package_setting()
    {
        if (!session()->has('package_setting')) {
            session(['package_setting' => \App\PackageSetting::first()]);
        }

        return session('package_setting');
    }
}

if (!function_exists('invoice_setting')) {

    function invoice_setting()
    {
        if (!session()->has('invoice_setting')) {
            session(['invoice_setting' => \App\InvoiceSetting::first()]);
        }

        return session('invoice_setting');
    }
}

if (!function_exists('language_setting')) {

    function language_setting()
    {
        if (!session()->has('language_setting')) {
            session(['language_setting' => \App\LanguageSetting::where('status', 'enabled')->get()]);
        }

        return session('language_setting');
    }
}

if (!function_exists('push_setting')) {

    function push_setting()
    {
        if (!session()->has('push_setting')) {
            session(['push_setting' => \App\PushNotificationSetting::first()]);
        }

        return session('push_setting');
    }
}

if (!function_exists('admin_theme')) {

    function admin_theme()
    {
        if (!session()->has('admin_theme')) {
            session(['admin_theme' => \App\ThemeSetting::where('panel', 'admin')->first()]);
        }

        return session('admin_theme');
    }
}

if (!function_exists('employee_theme')) {

    function employee_theme()
    {
        if (!session()->has('employee_theme')) {
            session(['employee_theme' => \App\ThemeSetting::where('panel', 'employee')->first()]);
        }

        return session('employee_theme');
    }
}

if (!function_exists('superadmin_theme')) {

    function superadmin_theme()
    {
        if (!session()->has('superadmin_theme')) {
            session(['superadmin_theme' => \App\ThemeSetting::where('panel', 'superadmin')->first()]);
        }

        return session('superadmin_theme');
    }
}

if (!function_exists('storage_setting')) {

    function storage_setting()
    {
        if (!session()->has('storage_setting')) {
            session(['storage_setting' => \App\StorageSetting::where('status', 'enabled')
                ->first()]);
        }
        return session('storage_setting');
    }
}

if (!function_exists('user_modules')) {
    function user_modules()
    {
        $user = auth()->user();
        $user_modules = $user->modules;

        if ($user) {
            session(['user_modules' => $user_modules]);
            return session('user_modules');
        }

        return null;
    }
}

if (!function_exists('get_domain')) {

    function get_domain($host = false)
    {
        if (!$host) {
            $host = $_SERVER['SERVER_NAME'];
        }
        $shortDomain = config('app.short_domain_name');
        $dotCount = ($shortDomain === true) ? 2 : 3;

        $myhost = strtolower(trim($host));
        $count = substr_count($myhost, '.');
        if ($count === 2) {
            if (strlen(explode('.', $myhost)[1]) > $dotCount) $myhost = explode('.', $myhost, 2)[1];
        } else if ($count > 2) {
            $myhost = get_domain(explode('.', $myhost, 2)[1]);
        }
        return $myhost;
    }
}

if (!function_exists('global_currency_position')) {

    function global_currency_position($currency_symbol)
    {

        if (!session()->has('global_currency_position')) {
            $currency = \App\GlobalCurrency::where('currency_symbol', $currency_symbol)->first();

            session(['global_currency_position' => !is_null($currency) ? $currency->currency_position : null]);
        }
        return session('global_currency_position');
    }
}

if (!function_exists('company_currency_position')) {

    function company_currency_position($currency_symbol)
    {

        if (!session()->has('company_currency_position')) {
            $currency = \App\Currency::where('currency_symbol', $currency_symbol)->first();
            session(['company_currency_position' => !is_null($currency) ? $currency->currency_position : null]);
        }

        return session('company_currency_position');
    }
}

if (!function_exists('currency_position')) {

    function currency_position($amount = null, $symbol = null)
    {
        $position = 'front';

        if (!is_null(company_currency_position($symbol)) && company()) {
            $position = company_currency_position($symbol);
        } elseif (!is_null(company_currency_position($symbol))) {
            $position = global_currency_position($symbol);
        }
        
        // FOR PRICING PAGE
        if (is_null($amount)) {
            return $position;
        }
        try{
            if(config('app.currency_position') > 0){
                $amount = number_format($amount,config('app.currency_position'));
            }

        }catch(\Exception $e){
            
        }
        
        return ($position == 'front') ? ($symbol . $amount) : ($amount . $symbol);
    }
}

if (!function_exists('can_upload')) {

    function can_upload($size = 0)
    {
        if (!session()->has('client_company')) {
            session()->forget(['company_setting', 'company']);
        }

        // Return true for unlimited file storage
        if (company()->package->max_storage_size == -1) {
            return true;
        }

        // Total Space in package in MB
        $totalSpace = (company()->package->storage_unit == 'mb') ? company()->package->max_storage_size : company()->package->max_storage_size * 1024;

        // Used space in mb
        $fileStorage = FileStorage::all();
        $usedSpace = $fileStorage->count() > 0 ? round($fileStorage->sum('size') / (1000 * 1024), 4) : 0;

        $remainingSpace = $totalSpace - $usedSpace;

        if ($usedSpace > $totalSpace || $size > $remainingSpace) {
            return false;
        }

        return true;
    }
}

function generateS3SignedUrl($path)
{
    $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();

    $command = $client->getCommand('GetObject', [
        'Bucket' => config('filesystems.disks.s3.bucket'),
        'Key' => $path
    ]);

    $request = $client->createPresignedRequest($command, '+20 minutes');

    $presignedUrl = (string)$request->getUri();
    return $presignedUrl;
}
