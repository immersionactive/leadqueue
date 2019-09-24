<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadSource extends Model
{
    
    use SoftDeletes;

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function source_config()
    {
        return $this->morphTo();
    }

}
