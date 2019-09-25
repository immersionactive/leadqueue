<?php

namespace App;

abstract class LeadSourceType
{

    /**
     * Should return the human-readable name of the lead source type (e.g.,
     * "Webflow Form").
     * @return string
     */
    abstract public static function getName(): string;

    /**
     * Should return the name of the lead source type as a slug, suitable for
     * use in URLs and similar contexts (e.g., "webflow-form").
     * @return string
     */
    abstract public static function getSlug(): string;

}
