<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model classes for concrete source config types should inherit from this class.
 */
abstract class SourceConfig extends Model
{

    public function lead_source()
    {
        return $this->morphOne('App\Models\LeadSource', 'source_config');
    }

}
