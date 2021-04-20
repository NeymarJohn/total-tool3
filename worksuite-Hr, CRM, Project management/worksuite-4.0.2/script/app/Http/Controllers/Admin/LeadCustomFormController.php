<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\LeadCustomForm;
use Illuminate\Http\Request;

class LeadCustomFormController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = __('icon-pencil');
        $this->pageTitle = 'modules.lead.leadForm';
        $this->middleware(function ($request, $next) {
            if (!in_array('leads', $this->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $this->leadFormFields = LeadCustomForm::orderBy('field_order', 'asc')->get();
        return view('admin.lead-form.index', $this->data);
    }

    /**
     * update record
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($id == 0){
            $status = ($request->status == 'active') ? 1 : 0;
            $this->global->lead_form_google_captcha = $status;
            $this->global->save();
            session()->forget('global_setting');
            cache()->forget('global-setting');
            $setting = cache()->remember(
                'global-setting', 60*60*24, function () {
                return \App\Setting::first();
            }
            );
        }
        else{
            LeadCustomForm::where('id', $id)->update([
                'status' => $request->status
            ]);
        }


        return Reply::success(__('messages.updateSuccess'));
    }

    /**
     * sort fields order
     *
     * @return \Illuminate\Http\Response
     */
    public function sortFields()
    {
        $sortedValues = request('sortedValues');

        foreach ($sortedValues as $key => $value) {
            LeadCustomForm::where('id', $value)->update(['field_order' => $key+1]);
        }

        return Reply::dataOnly([]);
    }

}
