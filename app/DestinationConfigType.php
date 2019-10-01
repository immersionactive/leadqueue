<?php

namespace App;

use App\Models\LeadDestination;
use App\Models\DestinationConfig;
use App\Http\Requests\Backend\StoreLeadDestinationRequest;
use App\Http\Requests\Backend\UpdateLeadDestinationRequest;

abstract class DestinationConfigType
{

    /**
     * Should return the human-readable name of the destination config type
     * (e.g., "HubSpot").
     * @return string
     */
    abstract public static function getName(): string;

    /**
     * @todo Document this method.     
     * @return string
     */
    abstract public static function getModelClassname(): string;

    /**
     * Should return the name of the source config type as a slug, suitable for
     * use in URLs and similar contexts (e.g., "webflow-form").
     * @return string
     */
    abstract public static function getSlug(): string;

    /**
     * @todo Document this method.
     * @return string
     */
    abstract public static function getShowView(): string;

    /**
     * @todo Document this method.     
     * @todo Does this approach allow the main app to override views?
     * @return string
     */
    abstract public static function getCreateView(): string;

    /**
     * @todo Document this method.
     * @return string
     */
    abstract public static function getEditView(): string;

    /**
     * @todo Document this method.
     * @todo Should we pass the Validator instance to the get*Rules() methods
     *       instead? Would that be more flexible?
     * @return array
     */
    abstract public static function getStoreRules(): array;

    /**
     * @todo Document this method.
     * @return array
     */
    abstract public static function getUpdateRules(LeadDestination $lead_destination): array;

    abstract public static function buildConfig(StoreLeadDestinationRequest $request, LeadDestination $lead_destination): DestinationConfig;

    abstract public static function patchConfig(UpdateLeadDestinationRequest $request, LeadDestination $lead_destination, DestinationConfig $config): void;

}
