<?php

namespace App;

use App\DestinationConfigTypeRegistry;
use App\Models\Lead;
use App\Models\LeadAppendedValue;
use App\Models\LeadInput;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * This class is responsible for inserting appended leads into their
 * LeadDestinations.
 * 
 * It delegates the actual insertion to destination packages.
 */
class DestinationProcessor
{

    protected $destination_config_type_registry;
    protected $max_destination_attempts;

    public function __construct(DestinationConfigTypeRegistry $destination_config_type_registry)
    {
        $this->destination_config_type_registry = $destination_config_type_registry;
        $this->max_attempts = env('MAX_DESTINATION_ATTEMPTS', 5);
    }

    public function insert(Lead $lead): void
    {        

        $lead_destination = $lead->mapping->lead_destination;
        $destination_config_type_classname = $this->destination_config_type_registry->getByModelClassname($lead_destination->destination_config_type);

        $lead_inputs = $this->getLeadInputs($lead);
        $appended_values = $this->getAppendedValues($lead);

        try {
            $destination_id = $destination_config_type_classname::insert($lead_destination, $lead, $lead_inputs, $appended_values);
            $lead->status = 'complete';
            $lead->destination_id = $destination_id;
            $lead->destination_at = Carbon::now();
            $lead->save();
        } catch (\Exception $e) {
            throw $e; exit; // TODO: remove this
            $lead->failed_destination_attempts++;
            if ($lead->failed_destination_attempts >= $this->max_attempts) {
                $lead->status = 'destination_failed';
            }
            $lead->save();
            if ($lead->status === 'destination_failed') {
                // Rethrow the exception, so the LeadProcess command will
                // notify someone.
                throw $e;
            }
        }

    }

    public function getLeadInputs(Lead $lead): Collection
    {
        $mapping_field_ids = $lead->mapping->mapping_fields->pluck('id');
        $lead_inputs = LeadInput::where('lead_id', $lead->id)->whereIn('mapping_field_id', $mapping_field_ids)->get();
        // TODO: do we want to do any further massaging of this? include the destination_field_configs?
        return $lead_inputs;
    }

    public function getAppendedValues(Lead $lead): Collection
    {
        $destination_append_ids = $lead->mapping->lead_destination->destination_appends->pluck('id');
        // echo '$destination_append_ids: '; var_dump($destination_append_ids); exit;
        $lead_appended_values = LeadAppendedValue::where('lead_id', $lead->id)->whereIn('destination_append_id', $destination_append_ids)->get();
        // TODO: do we want to do any further massaging of this? include the destination_appends?
        return $lead_appended_values;
    }

}
