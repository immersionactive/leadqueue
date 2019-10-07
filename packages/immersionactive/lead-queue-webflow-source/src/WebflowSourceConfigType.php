<?php

namespace ImmersionActive\LeadQueueWebflowSource;

use App\SourceConfigType;
use App\Models\LeadSource;
use App\Models\SourceConfig;
use Illuminate\Http\Request;
use ImmersionActive\LeadQueueWebflowSource\Models\WebflowSourceConfig;

class WebflowSourceConfigType extends SourceConfigType
{

    public static function getName(): string
    {
        return 'Webflow Webhook';
    }

    public static function getModelClassname(): string
    {
        return WebflowSourceConfig::class;
    }

    public static function getSlug(): string
    {
        return 'webflow-webhook';
    }

    public static function getShowView(): string
    {
        return 'lead-queue-webflow-source::partials.show';
    }

    public static function getCreateView(): string
    {
        return 'lead-queue-webflow-source::partials.create-edit';
    }

    public static function getEditView(): string
    {
        return 'lead-queue-webflow-source::partials.create-edit';
    }

    public static function getSourceConfigValidationRules(LeadSource $lead_source): array
    {

        $rules = [

            'source_config.webflow_site_id' => [
                'required',
                'max:255',
                'unique:webflow_source_configs,webflow_site_id' . ($lead_source->exists ? ',' . $lead_source->source_config_id : '')
            ],

            'source_config.webflow_form_name' => [
                'required',
                'max:255',
            ],

        ];

        return $rules;

    }

    public static function buildSourceConfig(LeadSource $lead_source): SourceConfig
    {
        $config = new WebflowSourceConfig();
        return $config;
    }

    public static function patchSourceConfig(Request $request, LeadSource $lead_source, SourceConfig $config): void
    {
        $config->webflow_site_id = $request->input('source_config.webflow_site_id');
        $config->webflow_form_name = $request->input('source_config.webflow_form_name');
    }

}
