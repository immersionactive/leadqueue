<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    
    public function lead_source()
    {
        return $this->belongsTo('App\Model\LeadSource');
    }

}
