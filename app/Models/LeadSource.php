<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class LeadSource extends Model
{
    
    use SoftDeletes;

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function source_config()
    {
        return $this->morphTo();
    }

    public function leads()
    {
        return $this->hasMany('App\Models\Lead');
    }

    public function mappings()
    {
        return $this->hasMany('App\Models\Mappings');
    }

    /**
     * Returns a list of active lead sources for the specified client,
     * suitable for use in the $options parameter of html()->select() (IDs as
     * keys; names as values).
     *
     * If the optional $include_inactive_id parameter is provided, then a
     * the specified lead source will be included in the list, even if it's
     * inactive.
     * 
     * @param int $client_id
     * @param ?int $include_inactive_id
     * @return array
     * @todo Cache this.
     */
    public static function getOptions(int $client_id, ?int $include_inactive_id = null)
    {

        $cache_key = 'LeadSource_getOptions_clientId' . $client_id . ($include_inactive_id ? '_includeInactiveId' . $include_inactive_id : '');

        $lead_sources = self::where(['client_id' => $client_id])->where(function ($q) use ($include_inactive_id) {
            $q->where('is_active', true);
            if ($include_inactive_id) {
                $q->orWhere('id', $include_inactive_id);
            }
        });

        $options = [null => ''];

        foreach ($lead_sources->get() as $lead_source) {
            $options[$lead_source->id] = $lead_source->name . ($lead_source->is_active ? '' : ' (inactive)');
        }

        return $options;

    }

}
