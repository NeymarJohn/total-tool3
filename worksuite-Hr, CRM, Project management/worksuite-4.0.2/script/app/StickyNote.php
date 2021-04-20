<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StickyNote
 * @package App
 */
class StickyNote extends BaseModel
{
    protected $table = 'sticky_notes';

    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userDetail()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

}
