<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadInput extends Model
{

    public function lead()
    {
        return $this->belongsTo('App\Models\Lead');
    }

    public function mapping_field()
    {
        return $this->belongsTo('App\Models\MappingField');
    }

}
