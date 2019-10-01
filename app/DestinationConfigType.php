<?php

namespace App;

abstract class DestinationConfigType
{

    /**
     * Should return the human-readable name of the destination config type
     * (e.g., "HubSpot").
     * @return string
     */
    abstract public static function getName(): string;

}
