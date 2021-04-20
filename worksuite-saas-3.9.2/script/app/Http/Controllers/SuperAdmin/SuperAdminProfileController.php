<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\Profile\UpdateSuperAdmin;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminProfileController extends SuperAdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.profileSettings';
        $this->pageIcon = 'icon-user';
    }

    public function index()
    {
        $this->userDetail = User::withoutGlobalScope('active')->findOrFail($this->user->id);

        return view('super-admin.profile.edit', $this->data);
    }

    public function update(UpdateSuperAdmin $request, $id)
    {
        $user = User::withoutGlobalScope('active')->where('super_admin', '1')->findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->password != '') {
            $user->password = Hash::make($request->input('password'));
        }
        $user->mobile = $request->input('mobile');
        $user->gender = $request->input('gender');

        if ($request->hasFile('image')) {
            Files::deleteFile($user->image, 'avatar');
            $user->image = Files::upload($request->image, 'avatar', 300);
        }

        $user->save();

        session()->forget('user');
        session()->forget('superAdmin');

        return Reply::successWithData(__('messages.superAdminUpdated'), []);
    }

    public function updateOneSignalId(Request $request)
    {
        $user = User::find($this->user->id);
        $user->onesignal_player_id = $request->userId;
        $user->save();
    }
}
