<?php

namespace App;

use App\Models\LeadSource;
use App\Models\SourceConfig;
use App\Models\MappingField;
use App\Models\SourceFieldConfig;
use Illuminate\Http\Request;

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
     * @return array
     */
    abstract public static function getSourceConfigValidationRules(LeadSource $lead_source): array;

    /**
     * @todo Document this method.
     * @return SourceConfig
     */
    abstract public static function buildSourceConfig(LeadSource $lead_source): SourceConfig;

    /**
     * @todo Document this method.
     * @return void
     */
    abstract public static function patchSourceConfig(Request $request, LeadSource $lead_source, SourceConfig $config): void;

    /**
     * Methods related to Mappings & MappingFields
     */

    /**
     * @todo Document this method.
     * @return string
     */
    abstract public static function getSourceFieldConfigSummaryView(): string;

    /**
     * @todo Document this method.
     * @return string
     */
    abstract public static function getSourceFieldConfigCreateView(): string;

    /**
     * @todo Document this method.
     * @return string
     */
    abstract public static function getSourceFieldConfigEditView(): string;

    /**
     * @todo Document this method.
     * @return array
     */
    abstract public static function getSourceFieldConfigValidationRules(MappingField $mapping_field): array;

    /**
     * @todo Document this method.
     * @return SourceFieldConfig
     */
    abstract public static function buildSourceFieldConfig(MappingField $mapping_field): SourceFieldConfig;

    /**
     * @todo Document this method.
     * @return void
     */
    abstract public static function patchSourceFieldConfig(Request $request, MappingField $mapping_field, SourceFieldConfig $source_field_config): void;

}
