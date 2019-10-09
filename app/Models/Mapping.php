<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapping extends Model
{
    
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function lead_source()
    {
        return $this->belongsTo('App\Models\LeadSource');
    }

    public function lead_destination()
    {
        return $this->belongsTo('App\Models\LeadDestination');
    }

    public function mapping_fields()
    {
        return $this->hasMany('App\Models\MappingField');
    }

    public function leads()
    {
        return $this->hasMany('App\Models\Lead');
    }

}
