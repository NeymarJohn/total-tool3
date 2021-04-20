<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfflinePaymentMethod extends BaseModel
{
    protected $table = 'offline_payment_methods';
    protected $dates = ['created_at'];

    public static function activeMethod(){
       return OfflinePaymentMethod::where('status', 'yes')->get();
    }
}
