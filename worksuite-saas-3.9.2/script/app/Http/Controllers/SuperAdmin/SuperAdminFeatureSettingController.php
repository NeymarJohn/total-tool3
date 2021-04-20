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

class SuperAdminFeatureSettingController extends SuperAdminBaseController
{
    /**
     * SuperAdminInvoiceController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'Front Feature Settings';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->type        = $request->type;
        $this->features    = Feature::with('language:id,language_name')->where('type', $this->type)->whereNull('front_feature_id')->get();
        $this->frontDetail = TrFrontDetail::first();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();
        $this->frontFeatures = FrontFeature::all();

        return view('super-admin.feature-settings.index', $this->data);
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->type     = $request->type;
        $this->featureId = $request->featureId;
        $this->features = Feature::whereNull('language_setting_id')->get();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.feature-settings.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $feature = new Feature();
        $type =  $request->type;

        $feature->language_setting_id = $request->language == 0 ? null : $request->language;
        $feature->title               = $request->title;
        $feature->type                = $request->type;
        $feature->description         = $request->description;
        $feature->front_feature_id    = ($request->featureId) ? $request->featureId : null;

        if($request->has('icon')){
            $feature->icon = $request->icon;
        }
        if ($request->hasFile('image')) {
            $feature->image = Files::upload($request->image, 'front/feature');
        }

        $feature->save();

        return Reply::redirect(route('super-admin.feature-settings.index').'?type='.$type, 'messages.feature.addedSuccess');

    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->feature = Feature::findOrFail($id);
        $this->type = $request->type;
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.feature-settings.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $feature = Feature::findOrFail($id);

        $feature->language_setting_id = $request->language == 0 ? null : $request->language;
        $feature->title = $request->title;

        $feature->type = $request->type;
        $feature->description = $request->description;
        if($request->has('icon')){
            $feature->icon = $request->icon;
        }

        if ($request->hasFile('image')) {
            Files::deleteFile($feature->image, 'front/feature');
            $feature->image = Files::upload($request->image, 'front/feature');
        }

        $type =  $request->type;
        $feature->save();

        return Reply::redirect(route('super-admin.feature-settings.index').'?type='.$type, 'messages.feature.addedSuccess');

    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function destroy(Request $request, $id)
    {
        $type =  $request->type;
        Feature::destroy($id);
        return Reply::redirect(route('super-admin.feature-settings.index').'?type='.$type, 'messages.feature.deletedSuccess');

    }

    public function changeForm(Request $request)
    {
        $headerData = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();

        if (empty($headerData)) {
            $view = view('super-admin.feature-settings.new-form', ['languageId' => $request->language_settings_id, 'type' => $request->type])->render();
        } else {
            $view = view('super-admin.feature-settings.edit-form', ['frontDetail' => $headerData, 'type' => $request->type])->render();
        }
        
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * @param UpdateTitleRequest $request
     * @return array
     */
    public function updateTitles(UpdateTitleRequest $request)
    {
        $feature = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();
        if (is_null($feature)) {
            $feature = new TrFrontDetail();
        }
        if($request->type == 'task')    {
            $feature->task_management_title  = $request->title;
            $feature->task_management_detail = $request->detail;
        }
        elseif($request->type == 'bills'){
            $feature->manage_bills_title  = $request->title;
            $feature->manage_bills_detail = $request->detail;
        }
        elseif($request->type == 'image'){
            $feature->feature_title       = $request->title;
            $feature->feature_description = $request->detail;
        }
        elseif($request->type == 'team'){
            $feature->teamates_title  = $request->title;
            $feature->teamates_detail = $request->detail;
        }
        elseif($request->type == 'apps'){
            $feature->favourite_apps_title  = $request->title;
            $feature->favourite_apps_detail = $request->detail;
        }
        $feature->language_setting_id = $request->language_settings_id;
        $feature->save();

        return Reply::success('messages.feature.addedSuccess');
    }
}
