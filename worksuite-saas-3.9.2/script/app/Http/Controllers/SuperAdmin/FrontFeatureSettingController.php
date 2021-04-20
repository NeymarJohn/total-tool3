<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Feature;
use App\FrontDetail;
use App\FrontFeature;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\Feature\UpdateTitleRequest;
use App\Http\Requests\SuperAdmin\FeatureSetting\FrontStoreRequest;
use App\Http\Requests\SuperAdmin\FeatureSetting\FrontUpdateRequest;
use App\Http\Requests\SuperAdmin\FeatureSetting\StoreRequest;
use App\Http\Requests\SuperAdmin\FeatureSetting\UpdateRequest;
use App\Http\Requests\SuperAdmin\FrontSetting\UpdateContactSettings;
use App\LanguageSetting;
use App\TrFrontDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class FrontFeatureSettingController extends SuperAdminBaseController
{
    /**
     * SuperAdminInvoiceController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Front Feature Page Settings';
        $this->pageIcon = 'icon-settings';

//
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($this->global->front_design == 0) {
            return Redirect::route('super-admin.theme-settings');
        }
        $this->type = $request->type;
        $this->features = Feature::with('language:id,language_name')->where('type', $this->type)->get();
        $this->frontDetail = TrFrontDetail::first();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();
        $this->frontFeatures = FrontFeature::all();
        return view('super-admin.front-feature-settings.index', $this->data);
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.front-feature-settings.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(FrontStoreRequest $request)
    {
        $feature = new FrontFeature();

        $feature->language_setting_id = $request->language == 0 ? null : $request->language;
        $feature->title = $request->title;
        $feature->description = $request->description;
        $feature->save();

        return Reply::redirect(route('super-admin.front-feature-settings.index'), 'messages.feature.addedSuccess');

    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function show(Request $request, $id)
    {
        $this->frontFeature = FrontFeature::with('features')->findOrFail($id);
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.front-feature-settings.show', $this->data);

    }


    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->feature = FrontFeature::findOrFail($id);
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.front-feature-settings.edit', $this->data);
    }


    /**
     * @param FrontUpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(FrontUpdateRequest $request, $id)
    {
        $feature = FrontFeature::findOrFail($id);
        $feature->language_setting_id = $request->language == 0 ? null : $request->language;
        $feature->title = $request->title;
        $feature->description = $request->description;
        $feature->save();

        return Reply::redirect(route('super-admin.front-feature-settings.index'), 'messages.feature.addedSuccess');

    }


    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function destroy(Request $request, $id)
    {
        Feature::where('front_feature_id', $id)->delete();
        FrontFeature::destroy($id);
        return Reply::redirect(route('super-admin.front-feature-settings.index'), 'messages.feature.deletedSuccess');

    }
}
