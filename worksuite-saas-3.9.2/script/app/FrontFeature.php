<?php

namespace App;

class FrontFeature extends BaseModel
{
    protected $table = 'front_features';

    public function features()
    {
        return $this->hasMany(Feature::class, 'front_feature_id');
    }
}
