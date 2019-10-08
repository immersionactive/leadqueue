<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

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
