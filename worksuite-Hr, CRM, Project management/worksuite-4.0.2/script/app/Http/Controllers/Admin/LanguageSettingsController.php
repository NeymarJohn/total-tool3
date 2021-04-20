<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Admin\Language\StoreRequest;
use App\Http\Requests\Admin\Language\UpdateRequest;
use App\LanguageSetting;
use Illuminate\Http\Request;
use Barryvdh\TranslationManager\Models\Translation;
use Illuminate\Support\Facades\File;

class LanguageSettingsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.languageSetting';
        $this->pageIcon = 'icon-settings';
        $this->langPath = base_path().'/resources/lang';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $this->languages = LanguageSetting::all();
        return view('admin.language-settings.index', $this->data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id){
        $setting = LanguageSetting::findOrFail($request->id);
        $setting->status = $request->status;
        $setting->save();
        session(['language_setting' => \App\LanguageSetting::where('status', 'enabled')->get()]);

        return Reply::success(__('messages.languageUpdated'));
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function updateData(UpdateRequest $request, $id)
    {
        $setting = LanguageSetting::findOrFail($request->id);

        // check and create lang folder
        $langExists = File::exists($this->langPath.'/'.strtolower($request->language_code));
        
        if (!$langExists) {
            // update lang folder name
            File::move($this->langPath.'/'.strtolower($setting->language_code), $this->langPath.'/'.strtolower($request->language_code));

            Translation::where('locale', strtolower($setting->language_code))->get()->map(function ($translation) {
                $translation->delete();
            });
        }

        $setting->language_name = $request->language_name;
        $setting->language_code = $request->language_code;
        $setting->status = $request->status;
        $setting->save();

        session(['language_setting' => \App\LanguageSetting::where('status', 'enabled')->get()]);

        return Reply::redirect(route('admin.language-settings.index'), __('messages.languageUpdated'));
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        // check and create lang folder
        $langExists = File::exists($this->langPath.'/'.strtolower($request->language_code));

        if (!$langExists) {
            File::makeDirectory($this->langPath.'/'.strtolower($request->language_code));
        }

        $setting = new LanguageSetting();
        $setting->language_name = $request->language_name;
        $setting->language_code = $request->language_code;
        $setting->status = $request->status;
        $setting->save();
        session(['language_setting' => \App\LanguageSetting::where('status', 'enabled')->get()]);

        return Reply::redirect(route('admin.language-settings.index'), __('messages.languageAdded'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('admin.language-settings.create', $this->data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $this->languageSetting = LanguageSetting::findOrFail($id);
        session(['language_setting' => \App\LanguageSetting::where('status', 'enabled')->get()]);
        return view('admin.language-settings.edit', $this->data);
    }

    /**
     * @param $id
     * @return array
     */
    public function destroy($id){
        $language = LanguageSetting::findOrFail($id);
        $setting = cache()->remember(
            'global-setting', 60*60*24, function () {
                return \App\Setting::first();
            }
        );

        if($language->language_code == $setting->locale){
            $setting->locale = 'en';
            $setting->last_updated_by = $this->user->id;
            $setting->save();
            session()->forget('user');
        }

        $language->destroy($id);
        session(['language_setting' => \App\LanguageSetting::where('status', 'enabled')->get()]);
        return  Reply::success(__('messages.languageDeleted'));
    }
}
