<?php

namespace App;

/**
 * Given Person, Household, and/or Place documents (returned by the USADATA API),
 * this class is responsible for extracting the specific fields that we're
 * interested in (and, in some cases, for doing some additional massaging of
 * the raw values - e.g., concatenation, converting booleans to "Yes" or "No",
 * etc.).
 */
class AppendOutputExtractor
{

    public static function extract(string $append_output_slug, array $documents)
    {
        $method = 'extract' . self::camelize($append_output_slug);
        if (!method_exists(self::class, $method)) {
            throw new \Exception('Sorry, but I don\'t know how to extract "' . $append_output_slug . '".');
        }
        return self::$method($documents);
    }

    public static function extractPersonGender(array $documents)
    {
        return self::getValue($documents, 'person', 'basicdemographics', 'gender');
    }

    public static function extractPersonEmail(array $documents)
    {

        $output = null;

        $email_contacts = self::getValue($documents, 'person', 'emailcontact');

        if (
            is_array($email_contacts) &&
            count($email_contacts) > 0 &&
            is_object($email_contacts[0]) &&
            property_exists($email_contacts[0], 'email')
        ) {
            $output = $email_contacts[0]->email;
        }

        return $output;

    }

    public static function extractPersonAddressStreet(array $documents)
    {
        
        $postal_contacts = self::getValue($documents, 'person', 'postalcontact');

        $parts = [];

        $properties = [
            'primaryNumber',
            'preDirectional',
            'street',
            'streetSuffix',
            'postDirectional'
        ];

        foreach ($properties as $property) {
            $part = self::getFirstPostalContactProperty($postal_contacts, $property);
            if ($part !== null) {
                $parts[] = $part;
            }
        }

        $output = null;

        if (count($parts) > 0) {
            $output = implode(' ', $parts);
        }

        return $output;

    }

    public static function extractPersonAddressUnit(array $documents)
    {

        $postal_contacts = self::getValue($documents, 'person', 'postalcontact');

        $parts = [];

        $properties = [
            'unitDesignator',
            'secondaryNumber'
        ];

        foreach ($properties as $property) {
            $part = self::getFirstPostalContactProperty($postal_contacts, $property);
            if ($part !== null) {
                $parts[] = $part;
            }
        }

        $output = null;

        if (count($parts) > 0) {
            $output = implode(' ', $parts);
        }

        return $output;

    }

    public static function extractPersonAddressCity(array $documents)
    {
        $postal_contacts = self::getValue($documents, 'person', 'postalcontact');
        return self::getFirstPostalContactProperty($postal_contacts, 'city');
    }

    public static function extractPersonAddressState(array $documents)
    {
        $postal_contacts = self::getValue($documents, 'person', 'postalcontact');
        return self::getFirstPostalContactProperty($postal_contacts, 'state');
    }

    public static function extractPersonAddressZip(array $documents)
    {

        $output = null;        
        $postal_contacts = self::getValue($documents, 'person', 'postalcontact');

        $zip_code = self::getFirstPostalContactProperty($postal_contacts, 'zipCode');

        if ($zip_code !== null) {
            $output = $zip_code;
            $zip_extension = self::getFirstPostalContactProperty($postal_contacts, 'zipExtension');
            if ($zip_extension !== null) {
                $output .= '-' . $zip_extension;
            }
        }

        return $output;

    }

    public static function extractPersonLengthOfResidence(array $documents)
    {
        return self::getValue($documents, 'person', 'basicdemographics', 'lengthOfResidence');
    }

    public static function extractPersonRecentHomeBuyer(array $documents)
    {
        return self::makeBooleanHumanReadable(
            self::getValue($documents, 'person', 'mortgagesandloans', 'recentHomeBuyer')
        );
    }

    public static function extractHouseholdLengthOfResidence(array $documents)
    {
        return self::getValue($documents, 'household', 'basicdemographics', 'lengthOfResidence');
    }

    public static function extractHouseholdRecentHomeBuyer(array $documents)
    {
        return self::makeBooleanHumanReadable(self::getValue($documents, 'household', 'mortgagesandloans', 'recentHomeBuyer'));
    }

    public static function extractPlacePropertyAssessedValue(array $documents)
    {

        $output = null;

        $value = self::getValue($documents, 'place', 'propertyvalue', 'assessedValue');

        if ($value !== null) {
            $output = '$' . $value;
        }

        return $output;

    }

    public static function extractPlacePropertyMarketValue(array $documents)
    {

        $output = null;

        $value = self::getValue($documents, 'place', 'propertyvalue', 'marketValue');

        if ($value !== null) {
            $output = '$' . $value;
        }

        return $output;

    }

    public static function extractPlacePropertyMarketValueDecile(array $documents)
    {
        return self::getValue($documents, 'place', 'propertyvalue', 'marketValueDecile');
    }

    public static function extractPlacePropertyMarketValueQualityIndicator(array $documents)
    {
        return self::getValue($documents, 'place', 'propertyvalue', 'marketValueQualityIndicator');
    }

    public static function extractPersonEstimatedIncome(array $documents)
    {
        
        $output = null;
        $parts = [];

        $properties = [
            'estimatedIncomeMin',
            'estimatedIncomeMax'
        ];

        foreach ($properties as $property) {
            $part = self::getValue($documents, 'person', 'investmentsandassets', $property);
            if ($part !== null) {
                $parts[] = '$' . $part;
            }
        }
        
        if (count($parts) > 0) {
            $output = implode(' - ', $parts);
        }

        return $output;

    }

    public static function extractHouseholdEstimatedIncome(array $documents)
    {

        $output = null;
        $parts = [];

        $properties = [
            'estimatedIncomeMin',
            'estimatedIncomeMax'
        ];

        foreach ($properties as $property) {
            $part = self::getValue($documents, 'household', 'investmentsandassets', $property);
            if ($part !== null) {
                $parts[] = '$' . $part;
            }
        }
        
        if (count($parts) > 0) {
            $output = implode(' - ', $parts);
        }

        return $output;

    }

    public static function extractHouseholdAdultsAgeRange(array $documents)
    {
        return self::getValue($documents, 'household', 'basicdemographics', 'adultsagerange');
    }

    public static function extractPersonAge(array $documents)
    {
        return self::getValue($documents, 'person', 'basicdemographics', 'age');
    }

    public static function extractPersonPhone(array $documents)
    {
        
        $output = null;

        $phone_contacts = self::getValue($documents, 'person', 'phonecontact');

        if (
            is_array($phone_contacts) && 
            count($phone_contacts) > 0 &&
            is_object($phone_contacts[0]) &&
            property_exists($phone_contacts[0], 'phone')
        ) {
            $output = $phone_contacts[0]->phone;
        }

        return $output;

    }

    /**
     * Internal utility methods
     */

    protected static function camelize(string $snake_case)
    {
        $words = explode('_', $snake_case);
        $words = array_map('ucfirst', $words);
        $camelized = implode('', $words);
        return $camelized;
    }

    protected static function getValue(array $documents, string $document_name, string $bundle_name, string $property_name = null)
    {
        
        if (
            !array_key_exists($document_name, $documents) ||
            !is_object($documents[$document_name])
        ) {
            var_dump(array_keys($documents));
            throw new \Exception('Couldn\'t find a "' . $document_name . '" document.');
        }

        $document = $documents[$document_name];

        if (
            !property_exists($document, $bundle_name)
        ) {
            return null;
            // throw new \Exception('Couldn\'t find a bundle named "' . $bundle_name . '" in the "' . $document_name . '" document.');
        }

        $bundle = $document->$bundle_name;

        if ($property_name === null) {
            // just return the entire bundle
            return $bundle;
        }

        if (!property_exists($bundle, $property_name)) {
            return null;
            // throw new \Exception('Coudln\'t find a property named "' . $property_name . '" in the "' . $bundle_name . '" on the "' . $document_name . '" document.');
        }

        return $bundle->$property_name;

    }

    protected static function makeBooleanHumanReadable($bool): ?string
    {

        if ($bool === null) {
            return null;
        }

        return $bool ? 'Yes' : 'No';

    }

    protected static function getFirstPostalContactProperty($postal_contacts, string $property)
    {

        $output = null;

        if (
            is_array($postal_contacts) &&
            count($postal_contacts) > 0 &&
            is_object($postal_contacts[0]) &&
            property_exists($postal_contacts[0], $property)
        ) {
            $output = $postal_contacts[0]->$property;
        }

        return $output;

    }

}
