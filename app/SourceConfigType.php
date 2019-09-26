<?php

namespace App;

use App\Http\Requests\Backend\StoreClientLeadSourceRequest;
use App\Http\Requests\Backend\UpdateClientLeadSourceRequest;
use App\Models\LeadSource;
use App\Models\SourceConfig;

abstract class SourceConfigType
{

    /**
     * Should return the human-readable name of the source config type (e.g.,
     * "Webflow Form").
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
    abstract public static function getUpdateRules(LeadSource $lead_source): array;

    /**
     * @todo Document this method.
     * @return SourceConfig
     */
    abstract public static function buildConfig(StoreClientLeadSourceRequest $request, LeadSource $lead_source): SourceConfig;

    /**
     * @todo Document this method.
     * @return SourceConfig
     */
    abstract public static function patchConfig(UpdateClientLeadSourceRequest $request, LeadSource $lead_source, SourceConfig $config): void;

}
