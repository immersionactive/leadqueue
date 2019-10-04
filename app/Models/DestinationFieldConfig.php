<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class DestinationFieldConfig extends Model
{

    public function mapping_field()
    {
        return $this->morphOne('App\Models\MappingField', 'destination_field_config');
    }

}
