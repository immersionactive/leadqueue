<?php

namespace ImmersionActive\LeadQueueGravityFormsSource;

use App\SourceConfigType;
use App\Models\LeadSource;
use App\Models\SourceConfig;
use Illuminate\Http\Request;
use ImmersionActive\LeadQueueGravityFormsSource\Models\GravityFormsSourceConfig;

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
            // TODO
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
        // TODO
    }

}
