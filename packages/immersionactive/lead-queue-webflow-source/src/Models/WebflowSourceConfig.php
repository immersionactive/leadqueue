<?php

namespace ImmersionActive\LeadQueueWebflowSource\Models;

use App\Models\SourceConfig;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebflowSourceConfig extends SourceConfig
{

    use SoftDeletes;

    public function lead_source()
    {
        return $this->morphOne('App\Models\LeadSource', 'source_config');
    }

}
