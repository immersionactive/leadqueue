<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebflowSourceConfig extends Model
{

    use SoftDeletes;

    public function lead_source()
    {
        return $this->morphOne('App\Models\LeadSource', 'source_config');
    }

}
