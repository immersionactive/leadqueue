<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model classes for concrete destination config types should inherit from this class.
 */
abstract class DestinationConfig extends Model
{

    public function lead_destination()
    {
        return $this->morphOne('App\Models\LeadDestination', 'destination_config');
    }

}
