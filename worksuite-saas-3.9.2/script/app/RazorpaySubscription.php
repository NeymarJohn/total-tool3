<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RazorpaySubscription extends BaseModel
{
    protected $dates = ['created_at'];

    protected $table = 'razorpay_subscriptions';

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function currency() {
        return $this->belongsTo(Currency::class);
    }
}
