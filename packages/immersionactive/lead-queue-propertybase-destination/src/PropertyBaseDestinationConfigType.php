<?php

namespace ImmersionActive\LeadQueuePropertybaseDestination;

use App\DestinationConfigType;
use App\Models\DestinationAppend;
use App\Models\DestinationAppendConfig;
use App\Models\DestinationConfig;
use App\Models\DestinationFieldConfig;
use App\Models\Lead;
use App\Models\LeadDestination;
use App\Models\Mapping;
use App\Models\MappingField;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use ImmersionActive\LeadQueuePropertybaseDestination\Models\PropertybaseWebToProspectDestinationConfig;
use ImmersionActive\LeadQueuePropertybaseDestination\Models\PropertybaseLeadDestinationAppendConfig;
use ImmersionActive\LeadQueuePropertybaseDestination\Models\PropertybaseDestinationFieldConfig;

class PropertybaseDestinationConfigType extends DestinationConfigType
{

    /**
     * General methods
     */

    public static function getName(): string
    {
        return 'Propertybase WebToProspect REST API';
    }

    public static function getModelClassname(): string
    {
        return PropertybaseWebToProspectDestinationConfig::class;
    }

    public static function getSlug(): string
    {
        return 'propertybase-webtoprospect';
    }

    /**
     * Methods related to DestinationConfigs
     */

    public static function getShowView(): string
    {
        return 'lead-queue-propertybase-destination::partials.show';
    }

    public static function getCreateView(): string
    {
        return 'lead-queue-propertybase-destination::partials.create-edit';
    }

    public static function getEditView(): string
    {
        return 'lead-queue-propertybase-destination::partials.create-edit';
    }

    public static function buildDestinationConfig(LeadDestination $lead_destination): DestinationConfig
    {
        $destination_config = new PropertybaseWebToProspectDestinationConfig();
        return $destination_config;
    }

    public static function patchDestinationConfig(Request $request, LeadDestination $lead_destination, DestinationConfig $destination_config): void
    {
        $destination_config->api_site_domain = $request->input('destination_config.api_site_domain');
        $destination_config->token = $request->input('destination_config.token');
    }

    public static function getDestinationConfigValidationRules(LeadDestination $lead_destination): array
    {

        $rules = [

            'destination_config.api_site_domain' => [
                'required',
                'max:255',
            ],

            'destination_config.token' => [
                'required',
                'max:255',
            ],

        ];

        return $rules;

    }

    /**
     * Methods related to DestinationAppends
     */

    public static function getDestinationAppendConfigSummaryView(): string
    {
        return 'lead-queue-propertybase-destination::partials.destination-append-config-summary';
    }

    public static function getDestinationAppendConfigCreateView(): string
    {
        return 'lead-queue-propertybase-destination::partials.destination-append-config-create-edit';
    }

    public static function getDestinationAppendConfigEditView(): string
    {
        return 'lead-queue-propertybase-destination::partials.destination-append-config-create-edit';
    }

    public static function buildDestinationAppendConfig(DestinationAppend $destination_append): DestinationAppendConfig
    {
        $destination_append_config = new PropertybaseLeadDestinationAppendConfig();
        return $destination_append_config;
    }

    public static function patchDestinationAppendConfig(Request $request, DestinationAppend $destination_append, DestinationAppendConfig $destination_append_config): void
    {
        $destination_append_config->contact_field_name = $request->input('destination_append_config.contact_field_name');
    }

    public static function getDestinationAppendConfigValidationRules(DestinationAppend $destination_append): array
    {

        $rules = [

            'destination_append_config.contact_field_name' => [
                'required',
                'max:255',

                // Make sure that this LeadDestination doesn't have any other
                // DestinationAppends which are linked to the same field name.
                function ($attribute, $value, $fail) use ($destination_append) {

                    $query = DestinationAppend::where('lead_destination_id', $destination_append->lead_destination_id);

                    // if we're editing an existing record, exclude it from the query
                    if ($destination_append->exists) {
                        $query->where('id', '<>', $destination_append->id);
                    }

                    $other_config_ids = $query->pluck('destination_append_config_id');
                    $other_configs = PropertybaseLeadDestinationAppendConfig::whereIn('id', $other_config_ids)->where('contact_field_name', $value)->limit(1);

                    if ($other_configs->count() > 0) {
                        $fail('This Lead Destination already has a Destination Append associated with the Contact Field Name "' . $value . '".');
                    }

                },

                // Make sure that this LeadDestination doesn't have any
                // Mappings which contain MappingFields that are linked to the
                // same field name.
                function ($attribute, $value, $fail) use ($destination_append) {
                    
                    $mapping_ids = Mapping::where('lead_destination_id', $destination_append->lead_destination_id)->pluck('id');
                    $append_destination_field_config_ids = MappingField::whereIn('mapping_id', $mapping_ids)->pluck('destination_field_config_id');
                    $append_destination_field_configs = PropertybaseDestinationFieldConfig::whereIn('id', $append_destination_field_config_ids)->where('contact_field_name', $value)->limit(1);

                    if ($append_destination_field_configs->count() > 0) {
                        $fail('This Lead Destination already has a Mapping which contains a Mapping Field associated with the contact field name "' . $value . '".');
                    }

                }
            ],

        ];

        return $rules;

    }

    public static function getDestinationFieldConfigSummaryView(): string
    {
        return 'lead-queue-propertybase-destination::partials.destination-field-config-summary';
    }

    public static function getDestinationFieldConfigShowView(): string
    {
        return 'lead-queue-propertybase-destination::partials.destination-field-config-show';
    }

    public static function getDestinationFieldConfigCreateView(): string
    {
        return 'lead-queue-propertybase-destination::partials.destination-field-config-create-edit';
    }

    public static function getDestinationFieldConfigEditView(): string
    {
        return 'lead-queue-propertybase-destination::partials.destination-field-config-create-edit';
    }

    public static function getDestinationFieldConfigValidationRules(MappingField $mapping_field): array
    {

        $rules = [

            'destination_field_config.contact_field_name' => [
                'required',
                'max:255',

                // Make sure that this is the only MappingField in this
                // Mapping which is linked to this contact field name
                function ($attribute, $value, $fail) use ($mapping_field) {

                    $query = MappingField::where('mapping_id', $mapping_field->mapping_id);

                    if ($mapping_field->exists) {
                        $query->where('id', '<>', $mapping_field->id);
                    }

                    $other_config_ids = $query->pluck('destination_field_config_id');
                    $other_configs = PropertybaseDestinationFieldConfig::whereIn('id', $other_config_ids)->where('contact_field_name', $value)->limit(1);

                    if ($other_configs->count() > 0) {
                        $fail('This Mapping already contains a Field that is associated with the contact field name "' . $value . '".');
                    }

                },

                // Make sure that this MappingField's LeadDestination doesn't
                // have any DestinationAppends which are associated with the
                // same destination field
                function ($attribute, $value, $fail) use ($mapping_field) {

                    $destination_append_config_ids = DestinationAppend::where('lead_destination_id', $mapping_field->mapping->lead_destination_id)->pluck('destination_append_config_id');
                    $destination_append_configs = PropertybaseLeadDestinationAppendConfig::whereIn('id', $destination_append_config_ids)->where('contact_field_name', $value)->limit(1);

                    if ($destination_append_configs->count() > 0) {
                        $fail('The Lead Destination "' . $mapping_field->mapping->lead_destination->name . '" contains a Destination Append which is already associated with the contact field name "' . $value . '".');
                    }

                }

            ],

        ];

        return $rules;

    }

    public static function buildDestinationFieldConfig(MappingField $mapping_field): DestinationFieldConfig
    {
        $destination_field_config = new PropertybaseDestinationFieldConfig();
        return $destination_field_config;
    }

    /**
     * @todo Document this method.
     * @return void
     */
    public static function patchDestinationFieldConfig(Request $request, MappingField $mapping_field, DestinationFieldConfig $destination_field_config): void
    {
        $destination_field_config->contact_field_name = $request->input('destination_field_config.contact_field_name');
    }

    public static function insert(Lead $lead): string
    {
        
        // throw new \Exception('Unimplemented');

        return '12345';

    }

}
