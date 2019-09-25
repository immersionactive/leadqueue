<?php

namespace ImmersionActive\WebflowLeadSource;

use App\LeadSourceType;

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

}
