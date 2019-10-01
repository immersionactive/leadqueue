<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MappingField extends Model
{

    public function mapping()
    {
        return $this->belongsTo('App\Models\Mapping');
    }

}
