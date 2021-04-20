<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RazorpayInvoice extends BaseModel
{
    protected $table = 'razorpay_invoices';
    protected $dates = ['pay_date', 'next_pay_date'];

    public function company() {
        return $this->belongsTo(Company::class, 'company_id')->withoutGlobalScopes(['active']);
    }
    public function currency() {
        return $this->belongsTo(GlobalCurrency::class, 'currency_id')->withTrashed();
    }

    public function package() {
        return $this->belongsTo(Package::class, 'package_id');
    }


}
