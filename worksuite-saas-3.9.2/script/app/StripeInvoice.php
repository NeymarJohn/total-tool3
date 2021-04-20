<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeInvoice extends BaseModel
{
    protected $table = 'stripe_invoices';
    protected $dates = ['pay_date', 'next_pay_date'];

    public function company() {
        return $this->belongsTo(Company::class, 'company_id')->withoutGlobalScopes(['active']);
    }

    public function package() {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
