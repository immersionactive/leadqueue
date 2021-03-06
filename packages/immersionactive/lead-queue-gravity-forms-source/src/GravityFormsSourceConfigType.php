<?php

namespace ImmersionActive\LeadQueueGravityFormsSource;

use App\SourceConfigType;
use App\Models\LeadSource;
use App\Models\SourceConfig;
use App\Models\MappingField;
use App\Models\SourceFieldConfig;
use Illuminate\Http\Request;
use ImmersionActive\LeadQueueGravityFormsSource\Models\GravityFormsSourceConfig;
use ImmersionActive\LeadQueueGravityFormsSource\Models\GravityFormsSourceFieldConfig;

class GravityFormsSourceConfigType extends SourceConfigType
{

    public static function getName(): string
    {
        return 'Gravity Forms Webhook';
    }

    public static function getModelClassname(): string
    {
        return GravityFormsSourceConfig::class;
    }

    public static function getSlug(): string
    {
        return 'gravity-forms-webhook';
    }

    public static function getShowView(): string
    {
        return 'lead-queue-gravity-forms-source::partials.show';
    }

    public static function getCreateView(): string
    {
        return 'lead-queue-gravity-forms-source::partials.create-edit';
    }

    public static function getEditView(): string
    {
        return 'lead-queue-gravity-forms-source::partials.create-edit';
    }

    public static function getSourceConfigValidationRules(LeadSource $lead_source): array
    {

        $rules = [
            // this space intentionally left blank
        ];

        return $rules;

    }

    public static function buildSourceConfig(LeadSource $lead_source): SourceConfig
    {
        $config = new GravityFormsSourceConfig();
        return $config;
    }

    public static function patchSourceConfig(Request $request, LeadSource $lead_source, SourceConfig $config): void
    {
        // this space intentionally left blank
        // (this source type has no options, so there's nothing to patch)        
    }

    public static function getSourceFieldConfigSummaryView(): string
    {
        return 'lead-queue-gravity-forms-source::partials.source-field-config-summary';
    }

    public static function getSourceFieldConfigShowView(): string
    {
        return 'lead-queue-gravity-forms-source::partials.source-field-config-show';
    }

    public static function getSourceFieldConfigCreateView(): string
    {
        return 'lead-queue-gravity-forms-source::partials.source-field-config-create-edit';
    }

    public static function getSourceFieldConfigEditView(): string
    {
        return 'lead-queue-gravity-forms-source::partials.source-field-config-create-edit';
    }

    public static function getSourceFieldConfigValidationRules(MappingField $mapping_field): array
    {
        return [

            'source_field_config.field_name' => [
                'required',
                'max:255'
            ],

        ];
    }

    public static function buildSourceFieldConfig(MappingField $mapping_field): SourceFieldConfig
    {
        $source_field_config = new GravityFormsSourceFieldConfig();
        // TODO: anything? maybe not
        return $source_field_config;
    }

    public static function patchSourceFieldConfig(Request $request, MappingField $mapping_field, SourceFieldConfig $source_field_config): void
    {
        $source_field_config->field_name = $request->input('source_field_config.field_name');
    }

    public static function extractSourceFieldFromInsertRequest(Request $request, MappingField $mapping_field)
    {
        
        $field_name = $mapping_field->source_field_config->field_name;

        // We can't use $request->input($field_name), because Gravity Forms
        // passes some fields with a period in the name (e.g., "3.5"). For a
        // JSON request, Laravel interprets that as "find a property named '3',
        // and then find a sub-property named '5'). That's not what we want -
        // we just want to find a property named '3.5'. This little workaround
        // accomplishes that.

        $input = $request->input();
        $value = array_key_exists($field_name, $input) ? $input[$field_name] : null;
        return $value;

    }

}
