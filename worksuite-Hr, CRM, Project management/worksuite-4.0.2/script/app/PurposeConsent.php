<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurposeConsent extends BaseModel
{
    protected $table = 'purpose_consent';
    protected $fillable = ['name', 'description'];

    public function lead()
    {
        return $this->hasOne(PurposeConsentLead::class, 'purpose_consent_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(PurposeConsentUser::class, 'purpose_consent_id', 'id');
    }
}
