<?php

namespace App;

use App\Observers\EstimateObserver;
use App\Traits\CustomFieldsTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Estimate extends BaseModel
{
    use Notifiable;
    use CustomFieldsTrait;

    protected $dates = ['valid_till'];
    protected $appends = ['total_amount', 'valid_date', 'original_estimate_number'];

    protected static function boot()
    {
        parent::boot();
        static::observe(EstimateObserver::class);
    }

    public function items()
    {
        return $this->hasMany(EstimateItem::class, 'estimate_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->withoutGlobalScopes(['active']);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function sign()
    {
        return $this->hasOne(AcceptEstimate::class, 'estimate_id');
    }

    public function getTotalAmountAttribute()
    {

        if (!is_null($this->total) && !is_null($this->currency_symbol)) {
            return $this->currency_symbol . $this->total;
        }

        return "";
    }

    public function getValidDateAttribute()
    {
        if (!is_null($this->valid_till)) {
            return Carbon::parse($this->valid_till)->format('d F, Y');
        }
        return "";
    }

    public function getOriginalEstimateNumberAttribute()
    {
        $invoiceSettings = invoice_setting();
        $zero = '';
        if (strlen($this->estimate_number) < $invoiceSettings->estimate_digit) {
            for ($i = 0; $i < $invoiceSettings->estimate_digit - strlen($this->estimate_number); $i++) {
                $zero = '0' . $zero;
            }
        }
        $zero = $zero . $this->estimate_number;
        return $zero;
    }

    public function getEstimateNumberAttribute($value)
    {
        if (!is_null($value)) {
            $invoiceSettings = invoice_setting();
            $zero = '';
            if (strlen($value) < $invoiceSettings->estimate_digit) {
                for ($i = 0; $i < $invoiceSettings->estimate_digit - strlen($value); $i++) {
                    $zero = '0' . $zero;
                }
            }
            $zero = $invoiceSettings->estimate_prefix . '#' . $zero . $value;
            return $zero;
        }
        return "";
    }

    public static function lastEstimateNumber()
    {
        $invoice = DB::select('SELECT MAX(CAST(`estimate_number` as UNSIGNED)) as estimate_number FROM `estimates`');
        return $invoice[0]->estimate_number;
    }
}
