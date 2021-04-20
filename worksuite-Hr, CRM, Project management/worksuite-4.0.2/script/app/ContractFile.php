<?php

namespace App;

use App\Observers\FileUploadObserver;

class ContractFile extends BaseModel
{
    protected $appends = ['file_url','icon'];

    public function getFileUrlAttribute()
    {

        return (!is_null($this->external_link) && $this->external_link != "") ? $this->external_link : asset_url_local_s3('contract-files/'.$this->contract_id.'/'.$this->hashname);
    }

    public function contract(){
        return $this->belongsTo(Contract::class);
    }


    protected static function boot()
    {
        parent::boot();
//        static::observe(FileUploadObserver::class);
    }
}
