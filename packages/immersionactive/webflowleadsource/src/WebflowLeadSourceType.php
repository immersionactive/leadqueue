<?php

namespace ImmersionActive\WebflowLeadSource;

use App\LeadSourceType;
use App\Http\Requests\Backend\StoreClientLeadSourceRequest;
use App\Models\LeadSource;
use App\Models\SourceConfig;
use ImmersionActive\WebflowLeadSource\Models\WebflowSourceConfig;

class WebflowLeadSourceType extends LeadSourceType
{

    public static function getName(): string
    {
        return 'Webflow Form';
    }

    public static function getSlug(): string
    {
        return 'webflow-form';
    }

    public static function getCreateView(): string
    {
        return 'webflowleadsource::partials.create';
    }

    public static function getEditView(): string
    {
        return 'webflowleadsource::partials.edit';
    }

    public static function getStoreRules(): array
    {
        return [

            'webflow_site_id' => [
                'required',
                'max:255',
                'unique:webflow_source_configs',
            ],

            'webflow_form_name' => [
                'required',
                'max:255',
            ],

        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            // TODO
        ];
    }

    public static function buildConfig(StoreClientLeadSourceRequest $request, LeadSource $lead_source): SourceConfig
    {
        
        $config = new WebflowSourceConfig();
        $config->webflow_site_id = $request->input('webflow_site_id');
        $config->webflow_form_name = $request->input('webflow_form_name');

        return $config;

    }

}
