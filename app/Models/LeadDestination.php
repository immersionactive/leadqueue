<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class LeadDestination extends Model
{

    use SoftDeletes;

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function destination_config()
    {
        return $this->morphTo();
    }

    public function destination_appends()
    {
        return $this->hasMany('App\Model\DestinationAppend');
    }

    public function mappings()
    {
        return $this->hasMany('App\Models\Mappings');
    }

    /**
     * Returns a list of active lead destinations for the specified client,
     * suitable for use in the $options parameter of html()->select() (IDs as
     * keys; names as values).
     *
     * If the optional $include_inactive_id parameter is provided, then a
     * the specified lead destination will be included in the list, even if it's
     * inactive.
     * 
     * @param int $client_id
     * @param ?int $include_inactive_id
     * @return array
     * @todo Cache this.
     */
    public static function getOptions(int $client_id, ?int $include_inactive_id = null)
    {

        $lead_destinations = self::where(['client_id' => $client_id])->where(function ($q) use ($include_inactive_id) {
            $q->where('is_active', true);
            if ($include_inactive_id) {
                $q->orWhere('id', $include_inactive_id);
            }
        });

        $options = [null => ''];

        foreach ($lead_destinations->get() as $lead_destination) {
            $options[$lead_destination->id] = $lead_destination->name . ($lead_destination->is_active ? '' : ' (inactive)');
        }

        return $options;

    }

}
