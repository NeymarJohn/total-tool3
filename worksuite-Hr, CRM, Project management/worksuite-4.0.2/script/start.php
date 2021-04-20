<?php

/*
|--------------------------------------------------------------------------
| Register Namespaces And Routes
|--------------------------------------------------------------------------
|
| When a module starting, this file will executed automatically. This helps
| to register some namespaces like translator or view. Also this file
| will load the routes file for each module. You may also modify
| this file as you want.
|
*/

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('user')) {

    /**
     * Return current logged in user
     */
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

if (!function_exists('client_theme')) {


    function client_theme()
    {
        if (!session()->has('client_theme')) {
            session(['client_theme' => \App\ThemeSetting::where('panel', 'client')->first()]);
        }

        return session('client_theme');
    }
}

if (!function_exists('global_setting')) {


    function global_setting()
    {
        if (!session()->has('global_setting')) {
            $setting = cache()->remember(
                'global-setting',
                60 * 60 * 24,
                function () {
                    return \App\Setting::first();
                }
            );
            session(['global_setting' => $setting]);
        }

        return session('global_setting');
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

if (!function_exists('language_setting')) {

    function language_setting()
    {
        if (!session()->has('language_setting')) {
            session(['language_setting' => \App\LanguageSetting::where('status', 'enabled')->get()]);
        }

        return session('language_setting');
    }
}

if (!function_exists('smtp_setting')) {

    function smtp_setting()
    {
        if (!session()->has('smtp_setting')) {
            session(['smtp_setting' => \App\SmtpSetting::first()]);
        }

        return session('smtp_setting');
    }
}

if (!function_exists('message_setting')) {

    function message_setting()
    {
        if (!session()->has('message_setting')) {
            session(['message_setting' => \App\MessageSetting::first()]);
        }

        return session('message_setting');
    }
}

if (!function_exists('storage_setting')) {

    function storage_setting()
    {
        if (!session()->has('storage_setting')) {
            $setting = cache()->remember(
                'storage-setting',
                60 * 60 * 24,
                function () {
                    return \App\StorageSetting::where('status', 'enabled')->first();
                }
            );
            session(['storage_setting' => $setting]);
        }

        return session('storage_setting');
    }
}

if (!function_exists('email_notification_setting')) {


    function email_notification_setting()
    {

        if (user()->hasRole('client') || user()->hasRole('employee')) {
            return \App\EmailNotificationSetting::all();
        }

        if (!session()->has('email_notification_setting')) {
            session(['email_notification_setting' => \App\EmailNotificationSetting::all()]);
        }

        return session('email_notification_setting');
    }
}

if (!function_exists('asset_url')) {

    // @codingStandardsIgnoreLine
    function asset_url($path)
    {
        $path = 'user-uploads/' . $path;
        $storageUrl = $path;

        if (!Str::startsWith($storageUrl, 'http')) {
            return url($storageUrl);
        }

        return $storageUrl;
    }
}

if (!function_exists('user_modules')) {


    function user_modules()
    {
        if (!session()->has('user_modules')) {
            $user = auth()->user();
            if($user){
                 $module = new \App\ModuleSetting();
            
                if ($user->hasRole('admin')) {
                    $module = $module->where('type', 'admin');
                } elseif ($user->hasRole('client')) {
                    $module = $module->where('type', 'client');
                } elseif ($user->hasRole('employee')) {
                    $module = $module->where('type', 'employee');
                }

                $module = $module->where('status', 'active');
                $module->select('module_name');

                $module = $module->get();
                $moduleArray = [];
                foreach ($module->toArray() as $item) {
                    array_push($moduleArray, array_values($item)[0]);
                }

                session(['user_modules' => $moduleArray]);
            }
           
        }

        return session('user_modules');
    }
}

if (!function_exists('worksuite_plugins')) {

    function worksuite_plugins()
    {

        if (!session()->has('worksuite_plugins')) {
            $plugins = \Nwidart\Modules\Facades\Module::allEnabled();
            // dd(array_keys($plugins));

            foreach ($plugins as $plugin) {
                Artisan::call('module:migrate', array($plugin, '--force' => true));
            }

            session(['worksuite_plugins' => array_keys($plugins)]);
        }
        return session('worksuite_plugins');
    }
}

if (!function_exists('pusher_settings')) {

    function pusher_settings()
    {
        if (!session()->has('pusher_settings')) {
            session(['pusher_settings' => \App\PusherSetting::first()]);
        }

        return session('pusher_settings');
    }
}

if (!function_exists('main_menu_settings')) {

    function main_menu_settings()
    {
        if (!session()->has('main_menu_settings')) {
            session(['main_menu_settings' => \App\MenuSetting::first()->main_menu]);
        }

        return session('main_menu_settings');
    }
}

if (!function_exists('sub_menu_settings')) {

    function sub_menu_settings()
    {
        if (!session()->has('sub_menu_settings')) {
            session(['sub_menu_settings' => \App\MenuSetting::first()->setting_menu]);
        }

        return session('sub_menu_settings');
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
            $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();

            $command = $client->getCommand('GetObject', [
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key' => $path
            ]);

            $request = $client->createPresignedRequest($command, '+20 minutes');

            $presignedUrl = (string)$request->getUri();
            return $presignedUrl;

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


if (!function_exists('gdpr_setting')) {

    function gdpr_setting()
    {
        if (!session()->has('gdpr_setting')) {
            session(['gdpr_setting' => \App\GdprSetting::first()]);
        }

        return session('gdpr_setting');
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

if (!function_exists('time_log_setting')) {

    function time_log_setting()
    {
        if (!session()->has('time_log_setting')) {
            session(['time_log_setting' => \App\LogTimeFor::first()]);
        }

        return session('time_log_setting');
    }
}

if (!function_exists('check_migrate_status')) {

    function check_migrate_status()
    {

        if (!session()->has('check_migrate_status')) {

            $status = Artisan::call('migrate:check');

            if ($status && !request()->ajax()) {
                Artisan::call('migrate', array('--force' => true)); //migrate database
                Artisan::call('optimize:clear');
            }

            session(['check_migrate_status' => 'Good']);
        }

        return session('check_migrate_status');
    }
}

if (!function_exists('module_enabled')) {
    function module_enabled($moduleName)
    {
        return \Nwidart\Modules\Facades\Module::collections()->has($moduleName);
    }
}
