<?php

namespace ImmersionActive\LeadQueuePropertybaseDestination;

use App\DestinationConfigType;
use App\Models\LeadDestination;
use App\Models\DestinationConfig;
use App\Models\DestinationAppend;
use App\Models\DestinationAppendConfig;
use Illuminate\Http\Request;
use ImmersionActive\LeadQueuePropertybaseDestination\Models\PropertybaseWebToProspectDestinationConfig;
use ImmersionActive\LeadQueuePropertybaseDestination\Models\PropertybaseLeadDestinationAppendConfig;

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
        $destination_config->account = $request->input('destination_config.account');
        $destination_config->token = $request->input('destination_config.token');
    }

    public static function getDestinationConfigValidationRules(LeadDestination $lead_destination): array
    {

        $rules = [

            'destination_config.account' => [
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
            ],

        ];

        return $rules;

    }

}
