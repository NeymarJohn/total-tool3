<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends BaseModel
{
    protected $table = 'packages';

    protected $guarded = ['id'];
    protected $appends = [
        'formatted_annual_price',
        'formatted_monthly_price'
    ];

    public function companies(){
        return $this->hasMany(Company::class);
    }
    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' GB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' MB';
        } else {
            $bytes = '0 MB';
        }

        return $bytes;
    }

    public function currency()
    {
        return $this->belongsTo(GlobalCurrency::class, 'currency_id')->withTrashed();
    }

    function getFormattedAnnualPriceAttribute()
    {
        $global = global_settings();

        return currency_position($this->annual_price,$global->currency->currency_symbol);
    }

    function getFormattedMonthlyPriceAttribute()
    {
        $global = global_settings();
        return currency_position($this->monthly_price,$global->currency->currency_symbol);
    }
}
