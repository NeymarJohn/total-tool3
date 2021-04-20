<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketFile extends BaseModel
{
   protected $appends = ['file_url','icon'];

    public function getFileUrlAttribute()
    {
        return (!is_null($this->external_link)) ? $this->external_link : asset_url_local_s3('ticket-files/'.$this->ticket_reply_id.'/'.$this->hashname);
    }
}
