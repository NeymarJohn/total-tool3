<?php

namespace App;

use App\Observers\PaymentGatewayCredentialObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PaymentGatewayCredentials extends BaseModel
{
    protected $appends = ['show_pay'];

    protected static function boot()
    {
        parent::boot();

        static::observe(PaymentGatewayCredentialObserver::class);

        static::addGlobalScope(new CompanyScope);

    }

    public function getShowPayAttribute() {
        return $this->attributes['paypal_status'] == 'active' || $this->attributes['stripe_status'] == 'active' || $this->attributes['paystack_status'] == 'active' || $this->attributes['razorpay_status'] == 'active' || $this->attributes['mollie_status'] == 'active' || $this->attributes['authorize_status'] == 'active';
    }
}
