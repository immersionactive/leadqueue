<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadDestination extends Model
{

    use SoftDeletes;

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
