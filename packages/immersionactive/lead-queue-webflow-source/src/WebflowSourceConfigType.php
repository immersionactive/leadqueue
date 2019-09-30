<?php

namespace ImmersionActive\LeadQueueWebflowSource;

use App\SourceConfigType;
use App\Http\Requests\Backend\StoreClientLeadSourceRequest;
use App\Http\Requests\Backend\UpdateClientLeadSourceRequest;
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

    public static function getCreateView(): string
    {
        return 'lead-queue-webflow-source::partials.create-edit';
    }

    public static function getEditView(): string
    {
        return 'lead-queue-webflow-source::partials.create-edit';
    }

    public static function getStoreRules(): array
    {

        $rules = self::getCommonRules();
        $rules['source_config.webflow_site_id'][] = 'unique:webflow_source_configs,webflow_site_id';
        return $rules;

    }

    public static function getUpdateRules(LeadSource $lead_source): array
    {

        $rules = self::getCommonRules();
        $rules['source_config.webflow_site_id'][] = 'unique:webflow_source_configs,webflow_site_id,' . $lead_source->source_config_id;
        return $rules;

    }

    protected static function getCommonRules(): array
    {

        return [

            'source_config.webflow_site_id' => [
                'required',
                'max:255',
            ],

            'source_config.webflow_form_name' => [
                'required',
                'max:255',
            ],

        ];

    }

    public static function buildConfig(StoreClientLeadSourceRequest $request, LeadSource $lead_source): SourceConfig
    {

        $config = new WebflowSourceConfig();
        $config->webflow_site_id = $request->input('source_config.webflow_site_id');
        $config->webflow_form_name = $request->input('source_config.webflow_form_name');

        return $config;

    }

    public static function patchConfig(UpdateClientLeadSourceRequest $request, LeadSource $lead_source, SourceConfig $config): void
    {

        $config->webflow_site_id = $request->input('source_config.webflow_site_id');
        $config->webflow_form_name = $request->input('source_config.webflow_form_name');

    }

    public static function processInsert(Request $request, LeadSource $lead_source): void
    {
        throw new \Exception('unimplemented');
    }

}
