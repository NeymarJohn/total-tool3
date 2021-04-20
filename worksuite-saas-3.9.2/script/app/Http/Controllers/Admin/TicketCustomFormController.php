<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\TicketCustomForm;
use Illuminate\Http\Request;

class TicketCustomFormController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = __('icon-pencil');
        $this->pageTitle = 'app.embedForm';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('tickets', $this->user->modules),403);
            return $next($request);
        });
    }

    public function index()
    {
        $this->ticketFormFields = TicketCustomForm::orderBy('field_order', 'asc')->get();
        return view('admin.ticket-form.index', $this->data);
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
            $this->global->ticket_form_google_captcha = $status;
            $this->global->save();
            session()->forget('company');
            session(['company' => $this->global]);
        }
        else{
            TicketCustomForm::where('id', $id)->update([
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
            TicketCustomForm::where('id', $value)->update(['field_order' => $key+1]);
        }

        return Reply::dataOnly([]);
    }

}
