<?php

namespace App\Http\Controllers\Member;

use App\ClientContact;
use App\Helper\Reply;
use App\Http\Requests\ClientContacts\StoreContact;
use App\User;
use Yajra\DataTables\Facades\DataTables;

class MemberClientContactController extends MemberBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'user-follow';
        $this->pageTitle = 'clients';
        $this->middleware(function ($request, $next) {
            if (!in_array('clients', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function show($id)
    {
        $this->client = User::withoutGlobalScope('active')->findOrFail($id);
        return view('member.client-contacts.show', $this->data);
    }

    public function data($id)
    {
        $timeLogs = ClientContact::where('user_id', $id)->get();

        return DataTables::of($timeLogs)
            ->addColumn('action', function ($row) {
                $action = '<div class="btn-group dropdown m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                <ul role="menu" class="dropdown-menu pull-right">';

                $action.='<li><a href="javascript:;" class="edit-contact" data-toggle="tooltip" data-contact-id="' . $row->id . '"  data-original-title="Edit" ><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>';

                $action.='<li><a href="javascript:;"  data-contact-id="' . $row->id . '"  class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';

                $action .= '</ul> </div>';
                return $action;
            })
            ->editColumn('contact_name', function ($row) {
                return ucwords($row->contact_name);
            })
            ->addIndexColumn()
            ->removeColumn('user_id')
            ->make(true);
    }

    public function store(StoreContact $request)
    {
        $contact = new ClientContact();
        $contact->user_id = $request->user_id;
        $contact->contact_name = $request->contact_name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->save();

        return Reply::success(__('messages.contactAdded'));
    }

    public function edit($id)
    {
        $this->contact = ClientContact::findOrFail($id);
        return view('member.client-contacts.edit', $this->data);
    }

    public function update(StoreContact $request, $id)
    {
        $contact = ClientContact::findOrFail($id);
        $contact->contact_name = $request->contact_name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->save();

        return Reply::success(__('messages.contactUpdated'));
    }

    public function destroy($id)
    {
        ClientContact::destroy($id);

        return Reply::success(__('messages.contactDeleted'));
    }
}
