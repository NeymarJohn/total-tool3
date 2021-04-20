<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaypalInvoice extends BaseModel
{
    protected $table = 'paypal_invoices';
    protected $dates = ['paid_on', 'next_pay_date'];

    public function company() {
        return $this->belongsTo(Company::class, 'company_id')->withoutGlobalScopes(['active']);
    }

    public function package() {
        return $this->belongsTo(Package::class, 'package_id');
    }
    public function currency() {
        return $this->belongsTo(GlobalCurrency::class, 'currency_id')->withTrashed();
    }
}
