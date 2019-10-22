<?php

namespace ImmersionActive\Conductor\Destinations\Infusionsoft;

use App\DestinationConfigType;
use App\Models\LeadDestination;
use App\Models\DestinationConfig;
use App\Models\DestinationAppend;
use App\Models\DestinationAppendConfig;
use App\Models\Lead;
use App\Models\MappingField;
use App\Models\DestinationFieldConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use ImmersionActive\Conductor\Destinations\Infusionsoft\Models\InfusionsoftDestinationConfig;
use Infusionsoft\Infusionsoft;

class InfusionsoftDestinationConfigType extends DestinationConfigType
{

    public static function getName(): string
    {
        return 'Infusionsoft';
    }

    public static function getModelClassname(): string
    {
        return InfusionsoftDestinationConfig::class;
    }

    public static function getSlug(): string
    {
        return 'infusionsoft';
    }

    /**
     * Methods related to DestinationConfigs
     */

    public static function getShowView(): string
    {
        return 'conductor-destination-infusionsoft::partials.show';
    }

    public static function getCreateView(): string
    {
        return 'conductor-destination-infusionsoft::partials.create-edit';
    }

    public static function getEditView(): string
    {
        return 'conductor-destination-infusionsoft::partials.create-edit';
    }

    public static function buildDestinationConfig(LeadDestination $lead_destination): DestinationConfig
    {
        $destination_config = new InfusionsoftDestinationConfig();
        return $destination_config;
    }

    public static function patchDestinationConfig(Request $request, LeadDestination $lead_destination, DestinationConfig $destination_config): void
    {
        $destination_config->client_id = $request->input('destination_config.client_id');
        $destination_config->client_secret = $request->input('destination_config.client_secret');
    }

    public static function getDestinationConfigValidationRules(LeadDestination $lead_destination): array
    {

        $rules = [
            
            'destination_config.client_id' => [
                'required',
                'max:255',
            ],

            'destination_config.client_secret' => [
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
        return 'conductor-destination-infusionsoft::partials.destination-append-config-summary';
    }

    public static function getDestinationAppendConfigCreateView(): string
    {
        return 'conductor-destination-infusionsoft::partials.destination-append-config-create-edit';
    }

    public static function getDestinationAppendConfigEditView(): string
    {
        return 'conductor-destination-infusionsoft::partials.destination-append-config-create-edit';
    }

    public static function buildDestinationAppendConfig(DestinationAppend $destination_append): DestinationAppendConfig
    {
        // TODO
        throw new \Exception('unimplemented');
        // $destination_append_config = new PropertybaseLeadDestinationAppendConfig();
        // return $destination_append_config;
    }

    public static function patchDestinationAppendConfig(Request $request, DestinationAppend $destination_append, DestinationAppendConfig $destination_append_config): void
    {
        // TODO
        throw new \Exception('unimplemented');
        // $destination_append_config->contact_field_name = $request->input('destination_append_config.contact_field_name');
    }

    public static function getDestinationAppendConfigValidationRules(DestinationAppend $destination_append): array
    {

        throw new \Exception('unimplemented');

        $rules = [
            // TODO
        ];

        return $rules;

    }

    /**
     * Methods related to Mappings & MappingFields
     */

    public static function getDestinationFieldConfigSummaryView(): string
    {
        return 'conductor-destination-infusionsoft::partials.destination-field-config-summary';
    }

    public static function getDestinationFieldConfigShowView(): string
    {
        return 'conductor-destination-infusionsoft::partials.destination-field-config-show';        
    }

    public static function getDestinationFieldConfigCreateView(): string
    {
        return 'conductor-destination-infusionsoft::partials.destination-field-config-create-edit';
    }

    public static function getDestinationFieldConfigEditView(): string
    {
        return 'conductor-destination-infusionsoft::partials.destination-field-config-create-edit';
    }

    public static function getDestinationFieldConfigValidationRules(MappingField $mapping_field): array
    {

        throw new \Exception('unimplemented');

        $rules = [
            // TODO
        ];

        return $rules;

    }

    public static function buildDestinationFieldConfig(MappingField $mapping_field): DestinationFieldConfig
    {
        // TODO
        throw new \Exception('unimplemented');
        // $destination_field_config = new PropertybaseDestinationFieldConfig();
        // return $destination_field_config;
    }

    public static function patchDestinationFieldConfig(Request $request, MappingField $mapping_field, DestinationFieldConfig $destination_field_config): void
    {
        // TODO
        throw new \Exception('unimplemented');
        // $destination_field_config->contact_field_name = $request->input('destination_field_config.contact_field_name');
    }

    public static function insert(LeadDestination $lead_destination, Lead $lead, Collection $lead_inputs, Collection $appended_values): string
    {

        throw new \Exception('unimplemented');

        $infusionsoft = new Infusionsoft([
            'clientId' => $lead_destination->destination_config->client_id,
            'clientSecret' => $lead_destination->destination_config->client_secret,
            'redirectUri' => 'http://example.com'
        ]);

        $token = $this->getAPIToken();

    }

    protected static function getAPIToken(): string
    {

        $token = Cache::get('infusionsoft_api_token');
        // TODO
        throw new \Exception('unimplemented');

    }

}
