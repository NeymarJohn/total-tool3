<?php

namespace App\Http\Controllers\Client;

use App\ClientDetails;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\User\UpdateProfile;
use App\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;

class ClientProfileController extends ClientBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = "app.menu.profileSettings";
        $this->pageIcon = 'icon-user';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->userDetail = auth()->user();
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->userDetail->id)->first();
        return view('client.profile.edit', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfile $request, $id)
    {
        config(['filesystems.default' => 'local']);

        $user = User::withoutGlobalScope('active')->findOrFail($id);

        if ($request->password != '') {
            $user->password = Hash::make($request->input('password'));
        }
        $user->email = $request->email;
        $newName = null;

        if ($request->hasFile('image')) {
            Files::deleteFile($user->image, 'avatar');
            $newName = Files::upload($request->image, 'avatar', 300);
            $user->image = $newName;
        }
        $user->email_notifications = $request->email_notifications;

        $user->save();

        $validate = Validator::make(['address' => $request->address], [
            'address' => 'required'
        ]);

        if ($validate->fails()) {
            return Reply::formErrors($validate);
        }

        $client = ClientDetails::where('user_id', $user->id)->first();

        if (empty($client)) {
            $client = new ClientDetails();
            $client->user_id = $user->id;
        }

        $client->address = $request->address;
        $client->name = $request->name;
        $client->email = $request->email;
        $client->mobile = $request->mobile;
        $client->company_name = $request->company_name;
        $client->website = $request->website;
        $client->gst_number = $request->gst_number;
        $client->shipping_address = $request->shipping_address;

        if ($request->hasFile('image')) {
            $client->image = $newName;
        }

        $client->save();
        session()->forget('user');

        return Reply::redirect(route('client.profile.index'), __("messages.profileUpdated"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeLanguage(Request $request)
    {
        $setting = User::findOrFail($this->user->id);
        $setting->locale = $request->input('lang');
        $setting->save();
        session()->forget('user');
        return Reply::success('Language changed successfully.');
    }

    public function changeCompany(Request $request)
    {

        $clientDetails = ClientDetails::withoutGlobalScope(CompanyScope::class)
            ->select('id', 'user_id', 'company_id')
            ->with('company')
            ->where('user_id', Auth::user()->id)
            ->where('company_id', $request->company_id)
            ->first();

        session(['company' => $clientDetails->company]);
        session(['client_company' => $clientDetails->company_id]);
        return response(Reply::success( __('messages.companyChanged')))->withCookie(Cookie::forget('productDetails'));
    }

    public function loginAdmin(Request $request)
    {

        $user = User::where('id', auth()->user()->id)->withoutGlobalScope(CompanyScope::class)->first();
        session()->flush();
        Auth::logout();
        Auth::login($user);

        return Reply::redirect(route('admin.dashboard'));
    }

}
