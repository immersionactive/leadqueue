<?php

namespace ImmersionActive\LeadQueuePropertybaseDestination\Models;

use App\Models\DestinationConfig;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertybaseWebToProspectDestinationConfig extends DestinationConfig
{

    protected $table = 'propertybase_webtoprospect_destination_configs';

    use SoftDeletes;

}
