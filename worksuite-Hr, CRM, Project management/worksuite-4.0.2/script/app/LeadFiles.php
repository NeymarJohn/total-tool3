<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * Class Holiday
 * @package App\Models
 */
class LeadFiles extends BaseModel
{
    // Don't forget to fill this array
    protected $fillable = [];

    protected $guarded = ['id'];
    protected $table =  'lead_files';

   protected $appends = ['file_url','icon'];

    public function getFileUrlAttribute()
    {
        return (!is_null($this->external_link)) ? $this->external_link : asset_url_local_s3('lead-files/'.$this->lead_id.'/'.$this->hashname);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
