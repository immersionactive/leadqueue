<?php

namespace ImmersionActive\LeadQueuePropertybaseDestination;

use App\DestinationConfigType;
use App\Models\LeadDestination;
use App\Models\DestinationConfig;
use Illuminate\Http\Request;
use ImmersionActive\LeadQueuePropertybaseDestination\Models\PropertybaseWebToProspectDestinationConfig;

class PropertybaseDestinationConfigType extends DestinationConfigType
{

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

}
