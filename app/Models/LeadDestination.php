<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LeadDestination extends Model
{

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
     * Returns a list of lead destinations for the specified client,
     * suitable for use in the $options parameter of html()->select() (IDs as
     * keys; names as values).
     * 
     * @param int $client_id
     * @return array
     * @todo Cache this.
     */
    public static function getList(int $client_id)
    {
        return self::where(['client_id' => $client_id])->orderBy('name')->pluck('name', 'id')->toArray();
    }

}
