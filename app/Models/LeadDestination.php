<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadDestination extends Model
{

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function destination_config()
    {
        return $this->morphTo();
    }

    public function mappings()
    {
        return $this->hasMany('App\Models\Mappings');
    }

}
