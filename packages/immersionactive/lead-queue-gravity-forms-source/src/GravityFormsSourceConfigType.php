<?php

namespace ImmersionActive\LeadQueueGravityFormsSource;

use App\SourceConfigType;
use App\Http\Requests\Backend\StoreLeadSourceRequest;
use App\Http\Requests\Backend\UpdateLeadSourceRequest;
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

    public static function getCreateView(): string
    {
        return 'lead-queue-gravity-forms-source::partials.create-edit';
    }

    public static function getEditView(): string
    {
        return 'lead-queue-gravity-forms-source::partials.create-edit';
    }

    public static function getStoreRules(): array
    {

        $rules = self::getCommonRules();
        // TODO
        return $rules;

    }

    public static function getUpdateRules(LeadSource $lead_source): array
    {

        $rules = self::getCommonRules();
        // TODO
        return $rules;

    }

    protected static function getCommonRules(): array
    {

        return [
            // TODO
        ];

    }

    public static function buildConfig(StoreLeadSourceRequest $request, LeadSource $lead_source): SourceConfig
    {

        $config = new GravityFormsSourceConfig();
        // TODO

        return $config;

    }

    public static function patchConfig(UpdateLeadSourceRequest $request, LeadSource $lead_source, SourceConfig $config): void
    {

        // TODO

    }

    /**
     * @todo Document this method.
     * @return void
     */
    public static function processInsert(Request $request, LeadSource $lead_source): void
    {

        // TODO

    }

}
