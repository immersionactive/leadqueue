<?php

namespace App;

/**
 * Given Person, Household, and/or Place objects (returned by the USADATA API),
 * this class is responsible for extracting the specific fields that we're
 * interested in (and, in some cases, for doing some additional massaging of
 * the raw values - e.g., concatenation, converting booleans to "Yes" or "No",
 * etc.).
 */
class AppendOutputExtractor
{

    public function extractPersonGender($person): string
    {
        // TODO
    }

    public function extractPersonAddressStreet($person): string
    {
        // TODO
    }

    public function extractPersonAddressUnit($person): string
    {
        // TODO
    }

    public function extractPersonAddressCity($person): string
    {
        // TODO
    }

    public function extractPersonAddressState($person): string
    {
        // TODO
    }

    public function extractPersonAddressZip($person): string
    {
        // TODO
    }

    public function extractPersonLengthOfResidence($person): string
    {
        // TODO
    }

    public function extractPersonRecentHomeBuyer($person): string
    {
        // TODO
    }

    public function extractHouseholdLengthOfResidence($household): string
    {
        // TODO
    }

    public function extractHouseholdRecentHomeBuyer($household): string
    {
        // TODO
    }

    public function extractPlacePropertyAssessedValue($place): string
    {
        // TODO
    }

    public function extractPlacePropertyMarketValue($place): string
    {
        // TODO
    }

    public function extractPlacePropertyMarketValueDecile($place): string
    {
        // TODO
    }

    public function extractPlacePropertyMarketValueQualityIndicator($place): string
    {
        // TODO
    }

    public function extractPersonEstimatedIncome($person): string
    {
        // TODO
    }

    public function extractHouseholdEstimatedIncome($household): string
    {
        // TODO
    }

    public function extractHouseholdAdultsAgeRange($household): string
    {
        // TODO
    }

    public function extractPersonAge($person): string
    {
        // TODO
    }

    public function extractPersonPhone($person): string
    {
        // TODO
    }

}
