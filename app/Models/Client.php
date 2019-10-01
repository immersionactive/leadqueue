<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    
    use SoftDeletes;

    public function lead_sources()
    {
        return $this->hasMany('App\Models\LeadSource');
    }

    // public function lead_destinations()
    // {
    //     return $this->hasMany('App\Models\LeadDestination');
    // }

    public function mappings()
    {
        return $this->hasMany('App\Models\Mapping');
    }

}
