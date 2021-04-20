<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class GlobalCurrency extends BaseModel
{
    use SoftDeletes;

    protected $table = 'global_currencies';
}
