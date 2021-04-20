<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Admin\SocialAuth\UpdateRequest;
use App\SocialAuthSetting;


class SocialAuthSettingsController extends AdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.socialLogin';
        $this->pageIcon = 'icon-settings';
    }

    public function index() {
        $this->credentials = SocialAuthSetting::first();
        return view('admin.social-login-settings.index', $this->data);
    }

    public function update(UpdateRequest $request) {
        $socialAuth = SocialAuthSetting::first();

        $socialAuth->twitter_client_id = $request->twitter_client_id;
        $socialAuth->twitter_secret_id = $request->twitter_secret_id;
        ($request->twitter_status) ? $socialAuth->twitter_status = 'enable' : $socialAuth->twitter_status = 'disable';

        $socialAuth->facebook_client_id = $request->facebook_client_id;
        $socialAuth->facebook_secret_id = $request->facebook_secret_id;
        ($request->facebook_status) ? $socialAuth->facebook_status = 'enable' : $socialAuth->facebook_status = 'disable';

        $socialAuth->linkedin_client_id = $request->linkedin_client_id;
        $socialAuth->linkedin_secret_id = $request->linkedin_secret_id;
        ($request->linkedin_status) ? $socialAuth->linkedin_status = 'enable' : $socialAuth->linkedin_status = 'disable';

        $socialAuth->google_client_id = $request->google_client_id;
        $socialAuth->google_secret_id = $request->google_secret_id;
        ($request->google_status) ? $socialAuth->google_status = 'enable' : $socialAuth->google_status = 'disable';

        $socialAuth->save();

        return Reply::success(__('messages.settingsUpdated'));
    }

}
