<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationAppend extends Model
{
    
    public function lead_destination()
    {
        return $this->belongsTo('App\Model\LeadDestination');
    }

    public function destination_append_config()
    {
        return $this->morphTo();
    }

}
