<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaystackInvoice extends Model
{
    protected $dates = [
        'pay_date',
        'next_pay_date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
