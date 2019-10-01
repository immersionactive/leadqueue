<?php

namespace ImmersionActive\LeadQueuePropertybaseDestination;

use App\DestinationConfigType;
use App\Http\Requests\Backend\StoreLeadDestinationRequest;
use App\Http\Requests\Backend\UpdateLeadDestinationRequest;
use App\Models\LeadDestination;
use App\Models\DestinationConfig;
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

    public static function getCreateView(): string
    {
        return 'lead-queue-propertybase-destination::partials.create-edit';
    }

    public static function getEditView(): string
    {
        return 'lead-queue-propertybase-destination::partials.create-edit';
    }

    public static function getStoreRules(): array
    {
        $rules = self::getCommonRules();
        // TODO: do we need to do anything more here? should we allow only one destination for a given Propertybase account?
        return $rules;
    }

    public static function getUpdateRules(LeadDestination $lead_destination): array
    {
        $rules = self::getCommonRules();
        // TODO: do we need to do anything more here? should we allow only one destination for a given Propertybase account?
        return $rules;
    }

    protected static function getCommonRules(): array
    {

        return [

            'destination_config.account' => [
                'required',
                'max:255',
            ],

            'destination_config.token' => [
                'required',
                'max:255',
            ],

        ];

    }

    public static function buildConfig(StoreLeadDestinationRequest $request, LeadDestination $lead_destination): DestinationConfig
    {

        $config = new PropertybaseWebToProspectDestinationConfig();
        $config->account = $request->input('destination_config.account');
        $config->token = $request->input('destination_config.token');

        return $config;

    }

    public static function patchConfig(UpdateLeadDestinationRequest $request, LeadDestination $lead_destination, DestinationConfig $config): void
    {

        $config->account = $request->input('destination_config.account');
        $config->token = $request->input('destination_config.token');

    }

}
