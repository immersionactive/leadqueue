<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadSourceRequest extends Model
{

    public function lead_source()
    {
        return $this->belongsTo('App\Models\LeadSource');
    }

    public function leads()
    {
        return $this->hasMany('App\Models\Lead');
    }

}
