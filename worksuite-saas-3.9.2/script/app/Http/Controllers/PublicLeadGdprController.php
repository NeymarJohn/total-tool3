<?php

namespace App\Http\Controllers;

use App\GdprSetting;
use App\Helper\Reply;
use App\Http\Requests\Gdpr\RemoveLeadRequest;
use App\Http\Requests\GdprLead\UpdateRequest;
use App\Lead;
use App\LeadSource;
use App\LeadStatus;
use App\Notifications\RemovalRequestAdminNotification;
use App\PurposeConsent;
use App\PurposeConsentLead;
use App\RemovalRequestLead;
use App\Setting;
use App\User;
use Illuminate\Http\Request;

class PublicLeadGdprController extends Controller
{

    public function lead($id)
    {
        $pageTitle = __('app.menu.lead');
        $gdprSetting = GdprSetting::first();

        if(!$gdprSetting->public_lead_edit)
        {
            return view('errors.404');
        }

        $lead = Lead::whereRaw('md5(id) = ?', $id)->firstOrFail();
        $sources = LeadSource::all();
        $status = LeadStatus::all();

        return view('public-gdpr.lead', [
            'pageTitle' => $pageTitle,
            'lead'  =>  $lead,
            'sources'  =>  $sources,
            'status'  =>  $status,
            'gdprSetting'  =>  $gdprSetting

        ]);
    }

    public function updateLead(UpdateRequest $request, $id)
    {
        $gdprSetting = GdprSetting::first();
        if(!$gdprSetting->public_lead_edit)
        {
            return Reply::error('messages.unAuthorisedUser');
        }

        $lead = Lead::whereRaw('md5(id) = ?', $id)->firstOrFail();
        $lead->company_name = $request->company_name;
        $lead->website = $request->website;
        $lead->address = $request->address;
        $lead->client_name = $request->client_name;
        $lead->client_email = $request->client_email;
        $lead->mobile = $request->mobile;
        $lead->note = $request->note;
        $lead->status_id = $request->status;
        $lead->source_id = $request->source;
        $lead->next_follow_up = $request->next_follow_up;
        $lead->save();

        return Reply::success('messages.LeadUpdated');
    }

    public function consent($id)
    {
        $pageTitle = __('modules.gdpr.consent');
        $gdprSetting = GdprSetting::first();

        if(!$gdprSetting->consent_leads)
        {
            return view('errors.404');
        }

        $lead = Lead::whereRaw('md5(id) = ?', $id)->firstOrFail();
        $allConsents = PurposeConsent::with(['lead' => function($query) use ($lead) {
            $query->where('lead_id', $lead->id)
                  ->orderBy('created_at', 'desc');
        }])->get();

        return view('public-gdpr.consent', [
            'pageTitle' => $pageTitle,
            'lead'  =>  $lead,
            'gdprSetting'  =>  $gdprSetting,
            'allConsents'  =>  $allConsents

        ]);
    }

    public function updateConsent(Request $request, $id)
    {
        $lead = Lead::whereRaw('md5(id) = ?', $id)->firstOrFail();

        $allConsents = $request->has('consent_customer') ? $request->consent_customer : [];

        foreach ($allConsents as $allConsentId => $allConsentStatus)
        {
            $newConsentLead = new PurposeConsentLead();
            $newConsentLead->lead_id = $lead->id;
            $newConsentLead->purpose_consent_id = $allConsentId;
            $newConsentLead->status = $allConsentStatus;
            $newConsentLead->ip = $request->ip();
            $newConsentLead->save();

        }

        return Reply::redirect(route('front.gdpr.consent', $id), 'messages.updateSuccess');
    }

    public function removeLeadRequest(RemoveLeadRequest $request)
    {
        $gdprSetting = GdprSetting::first();
        if(!$gdprSetting->lead_removal_public_form)
        {
            return Reply::error('messages.unAuthorisedUser');
        }

        $lead = Lead::find($request->lead_id);

        $removal = new RemovalRequestLead();
        $removal->lead_id = $request->lead_id;
        $removal->name = $lead->company_name;
        $removal->description = $request->description;
        $removal->save();

        return Reply::success('modules.gdpr.removalRequestSuccess');
    }
}
