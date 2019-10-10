<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationAppend extends Model
{
    
    public function lead_destination()
    {
        return $this->belongsTo('App\Models\LeadDestination');
    }

    public function append_output()
    {
        return $this->belongsTo('App\Models\AppendOutput');
    }

    public function destination_append_config()
    {
        return $this->morphTo();
    }

    public static function getList(int $lead_destination_id)
    {
        // TODO: cache this
        return self::where(['lead_destination_id' => $lead_destination_id])->pluck('append_output_path', 'id')->toArray();
    }

}
