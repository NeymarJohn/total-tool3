<?php

namespace App\Http\Controllers\Client;

use App\Event;
use App\GdprSetting;
use App\Helper\Reply;
use App\Http\Requests\Gdpr\RemoveUserRequest;
use App\ModuleSetting;
use App\Notifications\NewLeaveRequest;
use App\Notifications\ProjectReminder;
use App\Notifications\RemovalRequestAdminNotification;
use App\PurposeConsent;
use App\PurposeConsentUser;
use App\RemovalRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClientGdprController extends ClientBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.gdpr';
        $this->pageIcon = 'icon-lock';
    }

    public function index()
    {
        return view('client.gdpr.index', $this->data);
    }

    public function terms()
    {
        $this->pageTitle = 'Terms and conditions';
        $this->terms = GdprSetting::first()->terms;
        return view('client.gdpr.terms', $this->data);
    }

    public function privacy()
    {
        $this->pageTitle = 'Terms and conditions';
        return view('client.gdpr.terms', $this->data);
    }

    public function removeRequest(RemoveUserRequest $request)
    {
        $removal = new RemovalRequest();
        $removal->user_id = $this->user->id;
        $removal->name = $this->user->name;
        $removal->description = $request->description;
        $removal->save();

        return Reply::success('Removal request has been sent to the admin. You will informed once it is approved');
    }

    public function downloadJSON(Request $request){
        $table = User::with('client_details', 'attendance', 'employee', 'employeeDetail', 'projects', 'member', 'group')->find(\user()->id);
        $filename = "user-uploads/user.json";
        $handle = fopen($filename, 'w+');
        fputs($handle, $table->toJson(JSON_PRETTY_PRINT));
        fclose($handle);
        $headers = array('Content-type'=> 'application/json');
        return response()->download($filename,'user.json',$headers);
    }

    public function consent()
    {
        $pageTitle = __('modules.gdpr.consent');
        $gdprSetting = gdpr_setting();

        if(!$gdprSetting->consent_leads)
        {
            return view('errors.404');
        }
        $userId = $this->user->id;
        $allConsents = PurposeConsent::with(['user' => function($query) use($userId) {
            $query->where('client_id', $userId)
                ->orderBy('created_at', 'desc');
        }])->get();

        $this->allConsents = $allConsents;
        $this->gdprSetting = $gdprSetting;
        $this->pageTitle = $pageTitle;
        return view('client.gdpr.consent', $this->data);
    }

    public function updateConsent(Request $request)
    {

        $allConsents = $request->has('consent_customer') ? $request->consent_customer : [];

        foreach ($allConsents as $allConsentId => $allConsentStatus)
        {
            $newConsentLead = new PurposeConsentUser();
            $newConsentLead->client_id = $this->user->id;
            $newConsentLead->updated_by_id = $this->user->id;
            $newConsentLead->purpose_consent_id = $allConsentId;
            $newConsentLead->status = $allConsentStatus;
            $newConsentLead->ip = $request->ip();
            $newConsentLead->save();

        }

        return Reply::redirect(route('client.gdpr.consent'), 'messages.updateSuccess');
    }
}
