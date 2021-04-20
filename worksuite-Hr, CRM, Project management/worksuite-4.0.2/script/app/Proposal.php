<?php

namespace App;

use App\Observers\ProposalObserver;
use Illuminate\Database\Eloquent\Model;

class Proposal extends BaseModel
{
    protected $table = 'proposals';
//    use Notifiable;

    protected $dates = ['valid_till'];

    protected static function boot()
    {
        parent::boot();
        static::observe(ProposalObserver::class);
    }


    public function items() {
        return $this->hasMany(ProposalItem::class);
    }

    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    public function lead(){
        return $this->belongsTo(Lead::class);
    }

    public function signature()
    {
        return $this->hasOne(ProposalSign::class);
    }

}
