<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{

    public function lead_source_request()
    {
        return $this->belongsTo('App\Models\LeadSourceRequest');
    }

    public function mapping()
    {
        return $this->belongsTo('App\Models\Mapping');
    }

    public function lead_inputs()
    {
        return $this->hasMany('App\Models\LeadInput');
    }

}
