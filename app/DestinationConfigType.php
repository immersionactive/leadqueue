<?php

namespace App;

use App\Models\LeadDestination;
use App\Models\DestinationConfig;
use App\Models\DestinationAppend;
use App\Models\DestinationAppendConfig;
use Illuminate\Http\Request;

abstract class DestinationConfigType
{

    /**
     * General methods
     */

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
     * Methods related to DestinationConfigs
     */

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
     * @return DestinationConfig
     */
    abstract public static function buildDestinationConfig(LeadDestination $lead_destination): DestinationConfig;

    /**
     * @todo Document this method.
     * @return void
     */
    abstract public static function patchDestinationConfig(Request $request, LeadDestination $lead_destination, DestinationConfig $destination_config): void;

    /**
     * @todo Document this method.
     * @todo Should we pass the Validator instance to the get*Rules() methods
     *       instead? Would that be more flexible?
     * @return array
     */
    abstract public static function getDestinationConfigValidationRules(LeadDestination $lead_destination): array;

    /**
     * Methods related to DestinationAppends
     */

    /**
     * @todo Document this method.
     * @return string
     */
    abstract public static function getDestinationAppendConfigCreateView(): string;

    /**
     * @todo Document this method.
     * @return string
     */
    abstract public static function getDestinationAppendConfigEditView(): string;

    /**
     * @todo Document this method.
     * @return DestinationAppendConfig
     */
    abstract public static function buildDestinationAppendConfig(DestinationAppend $destination_append): DestinationAppendConfig;

    /**
     * @todo Document this method.
     * @return void
     */
    abstract public static function patchDestinationAppendConfig(Request $request, DestinationAppend $destination_append, DestinationAppendConfig $destination_append_config): void;

    /**
     * @todo Document this method.
     * @return array
     */
    abstract public static function getDestinationAppendConfigValidationRules(DestinationAppend $destination_append): array;

}
