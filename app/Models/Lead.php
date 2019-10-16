<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{

    protected $dates = [
        'appended_at',
        'destination_at'
    ];

    public static function getStatusList(bool $include_empty = false): array
    {

        $statuses = [];

        if ($include_empty) {
            $statuses[''] = 'All';
        }

        $statuses += [
            'new' => 'New',
            'appended' => 'Appended',
            'complete' => 'Complete',
            'append_failed' => 'Append Failed',
            'destination_failed' => 'Destination Failed'
        ];

        return $statuses;
    }

    public function lead_source_request()
    {
        return $this->belongsTo('App\Models\LeadSourceRequest');
    }

    public function mapping()
    {
        return $this->belongsTo('App\Models\Mapping');
    }

    public function lead_inputs()
    {
        return $this->hasMany('App\Models\LeadInput');
    }

    public function lead_appended_values()
    {
        return $this->hasMany('App\Models\LeadAppendedValue');
    }

    public function isAppended(): bool
    {
        return in_array($this->status, ['appended', 'complete', 'destination_failed']);
    }

}
