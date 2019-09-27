<?php

namespace ImmersionActive\LeadQueueGravityFormsSource\Models;

use App\Models\SourceConfig;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GravityFormsSourceConfig extends SourceConfig
{

    use SoftDeletes;

    public function lead_source()
    {
        return $this->morphOne('App\Models\LeadSource', 'source_config');
    }

}
