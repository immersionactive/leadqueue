<?php

namespace ImmersionActive\LeadQueuePropertyBaseDestination;

use App\DestinationConfigType;

class PropertyBaseDestinationConfigType extends DestinationConfigType
{

    public static function getName(): string
    {
        return 'PropertyBase';
    }

}
