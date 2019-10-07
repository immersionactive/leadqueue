<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class DestinationAppendConfig extends Model
{

    public function destination_append()
    {
        return $this->morphOne('App\Models\DestinationAppend', 'destination_append_config');
    }

}
