<?php

namespace App;

use App\Observers\InvoicePaymentReceivedObserver;
use Illuminate\Database\Eloquent\Model;

class ClientPayment extends BaseModel
{
    protected $table = 'payments';

    protected $dates = ['paid_on'];

    protected static function boot()
    {
        parent::boot();
        static::observe(InvoicePaymentReceivedObserver::class);
    }

    public function invoice() {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
