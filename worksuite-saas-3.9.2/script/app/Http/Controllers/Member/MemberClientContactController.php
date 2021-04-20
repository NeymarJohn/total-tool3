<?php

namespace App\Http\Controllers\Member;

use App\ClientContact;
use App\Helper\Reply;
use App\Http\Requests\ClientContacts\StoreContact;
use App\User;
use Yajra\DataTables\Facades\DataTables;

class MemberClientContactController extends MemberBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageIcon = 'user-follow';
        $this->pageTitle = 'clients';
        $this->middleware(function ($request, $next) {
            if(!in_array('clients',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });

    }

    public function show($id) {
        $this->client = User::findClient($id);
        return view('member.client-contacts.show', $this->data);
    }

    public function data($id) {
        $timeLogs = ClientContact::where('user_id', $id)->get();

        return DataTables::of($timeLogs)
            ->addColumn('action', function($row){
                return '<a href="javascript:;" class="btn btn-info btn-circle edit-contact"
                      data-toggle="tooltip" data-contact-id="'.$row->id.'"  data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                    <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-contact-id="'.$row->id.'" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
            })
            ->editColumn('contact_name', function($row){
                return ucwords($row->contact_name);
            })
            ->removeColumn('user_id')
            ->make(true);
    }

    public function store(StoreContact $request) {
        $contact = new ClientContact();
        $contact->user_id = $request->user_id;
        $contact->contact_name = $request->contact_name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->save();

        return Reply::success(__('messages.contactAdded'));
    }

    public function edit($id) {
        $this->contact = ClientContact::findOrFail($id);
        return view('member.client-contacts.edit', $this->data);
    }

    public function update(StoreContact $request, $id) {
        $contact = ClientContact::findOrFail($id);
        $contact->contact_name = $request->contact_name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->save();

        return Reply::success(__('messages.contactUpdated'));
    }

    public function destroy($id) {
        ClientContact::destroy($id);

        return Reply::success(__('messages.contactDeleted'));
    }
}
