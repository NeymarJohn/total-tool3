<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaystackSubscription extends Model
{
    protected $dates = ['created_at'];

    protected $table = 'paystack_subscriptions';

    public function company() {
        return $this->belongsTo(Company::class);
    }
}
