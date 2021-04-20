<?php

namespace App;

use App\Observers\PaymentObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Payment extends BaseModel
{
    protected $dates = ['paid_on'];

    protected $appends = ['total_amount', 'paid_date','file_url'];

    protected static function boot()
    {
        parent::boot();

        static::observe(PaymentObserver::class);
    }

    public function client()
    {
        if(!is_null($this->project_id) && $this->project->client_id){
            return $this->project->client;
        }
        if($this->invoice_id != null){
            if($this->invoice->client_id){
                return $this->invoice->client;
            }
            if(!is_null($this->invoice->project_id) && $this->invoice->project->client_id){
                return $this->invoice->project->client;
            }
        }
        return null;
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function offlineMethod()
    {
        return $this->belongsTo(OfflinePaymentMethod::class, 'offline_method_id');
    }

    public function getTotalAmountAttribute()
    {

        if (!is_null($this->amount) && !is_null($this->currency_symbol) && !is_null($this->currency_code)) {
            return  $this->amount;
        }

        return "";
    }

    public function getPaidDateAttribute()
    {
        if (!is_null($this->paid_on)) {
            return Carbon::parse($this->paid_on)->format('d F, Y H:i A');
        }
        return "";
    }

    public function getFileUrlAttribute()
    {
        return asset_url_local_s3('payment-receipt/'.$this->bill);
    }
}
