<?php

namespace ImmersionActive\Conductor\Destinations\Infusionsoft\Models;

use App\Models\DestinationConfig;

class InfusionsoftDestinationConfig extends DestinationConfig
{

    protected $dates = [
        'access_token_expires_at'
    ];

}
