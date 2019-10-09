<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadAppendedValue extends Model
{

    public function lead()
    {
        return $this->belongsTo('App\Models\Lead');
    }

    public function destination_append()
    {
        return $this->belongsTo('App\Models\DestinationAppend');
    }

}
