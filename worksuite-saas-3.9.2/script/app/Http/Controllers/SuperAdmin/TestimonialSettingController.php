<?php

namespace App\Http\Controllers\SuperAdmin;

use App\FrontDetail;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\Feature\UpdateTitleRequest;
use App\Http\Requests\SuperAdmin\TestimonialSettings\StoreRequest;
use App\Http\Requests\SuperAdmin\TestimonialSettings\UpdateRequest;
use App\LanguageSetting;
use App\Testimonials;
use App\TrFrontDetail;
use Illuminate\Http\Request;

class TestimonialSettingController extends SuperAdminBaseController
{
    /**
     * SuperAdminInvoiceController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Testimonial Settings';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->testimonials = Testimonials::with('language:id,language_name')->get();
        $this->frontDetail = TrFrontDetail::first();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.testimonial-settings.index', $this->data);
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->testimonials = Testimonials::all();
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.testimonial-settings.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $testimonial = new Testimonials();

        $testimonial->language_setting_id = $request->language == 0 ? null : $request->language;
        $testimonial->name    = $request->name;
        $testimonial->comment = $request->comment;
        $testimonial->rating  = $request->rating;

        $testimonial->save();

        return Reply::redirect(route('super-admin.testimonial-settings.index'), 'messages.testimonial.addedSuccess');
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->testimonial = Testimonials::findOrFail($id);
        $this->activeLanguages = LanguageSetting::where('status', 'enabled')->orderBy('language_name', 'asc')->get();

        return view('super-admin.testimonial-settings.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $testimonial = Testimonials::findOrFail($id);

        $testimonial->language_setting_id = $request->language == 0 ? null : $request->language;
        $testimonial->name    = $request->name;
        $testimonial->comment = $request->comment;
        $testimonial->rating  = $request->rating;
        $testimonial->save();

        return Reply::redirect(route('super-admin.testimonial-settings.index'), 'messages.testimonial.addedSuccess');
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function destroy(Request $request, $id)
    {
        Testimonials::destroy($id);
        return Reply::redirect(route('super-admin.testimonial-settings.index'), 'messages.testimonial.deletedSuccess');
    }

    public function changeForm(Request $request)
    {
        $headerData = TrFrontDetail::where('language_setting_id', $request->language_settings_id == 0 ? null : $request->language_settings_id)->first();

        if (empty($headerData)) {
            $view = view('super-admin.testimonial-settings.new-form', ['languageId' => $request->language_settings_id])->render();
        } else {
            $view = view('super-admin.testimonial-settings.edit-form', ['frontDetail' => $headerData])->render();
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



        if (!is_null($feature)) {
            $feature->update(['testimonial_title' => $request->title]);
        } else {
            $data = [
                'testimonial_title' => $request->title,
                'language_setting_id' => $request->language_settings_id,
            ];
            TrFrontDetail::create($data);
        }

        return Reply::success(__('messages.updatedSuccessfully'));
    }
}
