<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class SourceFieldConfig extends Model
{

    public function mapping_field()
    {
        return $this->morphOne('App\Models\MappingField', 'source_field_config');
    }

}
