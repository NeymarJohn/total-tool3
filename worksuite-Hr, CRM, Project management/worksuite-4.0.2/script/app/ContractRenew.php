<?php

namespace App;

use App\Helper\Reply;
use Illuminate\Database\Eloquent\Model;

class ContractRenew extends BaseModel
{
    protected $dates = [
        'start_date',
        'end_date'
    ];
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
    public function renewedBy()
    {
        return $this->belongsTo(User::class, 'renewed_by');
    }
}
