<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MappingField extends Model
{

    public function mapping()
    {
        return $this->belongsTo('App\Models\Mapping');
    }

    public function source_field_config()
    {
        return $this->morphTo();
    }

    public function destination_field_config()
    {
        return $this->morphTo();
    }

}
