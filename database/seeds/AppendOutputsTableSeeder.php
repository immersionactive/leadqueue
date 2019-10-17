<?php

use App\Models\AppendOutput;
use Illuminate\Database\Seeder;

class AppendOutputsTableSeeder extends Seeder
{

    public function run()
    {
        
        AppendOutput::create([
            'slug' => 'person_gender',
            'name' => 'Person: Gender',
            'description' => 'person.basicdemographics.gender: Indicates the gender of the individual.',
            'uses_person_document' => true,
            'uses_household_document' => false,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'person_address_street',
            'name' => 'Person: Address - Street Address',
            'description' => 'person.postalcontact.(multiple): First line of the person\'s postal address',
            'uses_person_document' => true,
            'uses_household_document' => false,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'person_address_unit',
            'name' => 'Person: Address - Unit Number',
            'description' => 'person.postalcontact.(multiple): Second line of the person\'s postal address',
            'uses_person_document' => true,
            'uses_household_document' => false,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'person_address_street_and_unit',
            'name' => 'Person: Address - Street Address w/ Unit Number',
            'description' => 'person.postalcontact.(multiple): First and second lines of the person\'s postal address',
            'uses_person_document' => true,
            'uses_household_document' => false,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'person_address_city',
            'name' => 'Person: Address - City',
            'description' => 'person.postalcontact.city: City',
            'uses_person_document' => true,
            'uses_household_document' => false,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'person_address_state',
            'name' => 'Person: Address - State',
            'description' => 'person.postalcontact.: State',
            'uses_person_document' => true,
            'uses_household_document' => false,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'person_address_zip',
            'name' => 'Person: Address - ZIP Code',
            'description' => 'person.postalcontact.zipCode & person.postalcontact.zipExtension: ZIP Code',
            'uses_person_document' => true,
            'uses_household_document' => false,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'person_length_of_residence',
            'name' => 'Person: Length of Residence',
            'description' => 'person.basicdemographics.lengthOfResidence: Length of time in years individual has occupied current residence',
            'uses_person_document' => true,
            'uses_household_document' => false,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'person_recent_home_buyer',
            'name' => 'Person: Recent Home Buyer?',
            'description' => 'person.mortgagesandloans.recentHomeBuyer: Indicates that someone has bought a home within last 6 months.',
            'uses_person_document' => true,
            'uses_household_document' => false,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'household_length_of_residence',
            'name' => 'Household: Length of Residence',
            'description' => 'household.basicdemographics.lengthOfResidence: Length of time in years that the residence has been occupied.',
            'uses_person_document' => false,
            'uses_household_document' => true,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'household_recent_home_buyer',
            'name' => 'Household: Recent Home Buyer?',
            'description' => 'household.mortgagesandloans.recentHomeBuyer: Indicates that someone has bought a home within last 6 months.',
            'uses_person_document' => false,
            'uses_household_document' => true,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'place_property_assessed_value',
            'name' => 'Home: Assessed Value',
            'description' => 'place.propertyvalue.assessedValue: Assessed value of the home.',
            'uses_person_document' => false,
            'uses_household_document' => false,
            'uses_place_document' => true
        ]);

        AppendOutput::create([
            'slug' => 'place_property_market_value',
            'name' => 'Home: Market Value',
            'description' => 'place.propertyvalue.marketValue: Market value of the home.',
            'uses_person_document' => false,
            'uses_household_document' => false,
            'uses_place_document' => true
        ]);

        AppendOutput::create([
            'slug' => 'place_property_market_value_decile',
            'name' => 'Home: Market Value Decile',
            'description' => 'place.propertyvalue.marketValueDecile: Relative home market value as compared to all of the homes located within the same county.',
            'uses_person_document' => false,
            'uses_household_document' => false,
            'uses_place_document' => true
        ]);

        AppendOutput::create([
            'slug' => 'place_property_market_value_quality_indicator',
            'name' => 'Home: Market Value Quality Indicator',
            'description' => 'place.propertyvalue.marketValueQualityIndicator: Method used to derive the home market value estimate.',
            'uses_person_document' => false,
            'uses_household_document' => false,
            'uses_place_document' => true
        ]);

        AppendOutput::create([
            'slug' => 'person_estimated_income',
            'name' => 'Person: Estimated Income',
            'description' => 'person.investmentsandassets.estimatedIncomeMin & person.investmentsandassets.estimatedIncomeMax: Estimated income minimum/maximum in dollars.',
            'uses_person_document' => true,
            'uses_household_document' => false,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'household_estimated_income',
            'name' => 'Household: Estimated Income',
            'description' => 'household.investmentsAndAssets.estimatedIncomeMin & household.investmentsAndAssets.estimatedIncomeMax: Estimated income minimum/maximum in dollars.',
            'uses_person_document' => false,
            'uses_household_document' => true,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'household_adults_age_range',
            'name' => 'Household: Adults Age Range',
            'description' => 'household.basicdemographics.adultsagerange: Adult age ranges present in the household',
            'uses_person_document' => false,
            'uses_household_document' => true,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'person_age',
            'name' => 'Person: Age',
            'description' => 'person.basicdemographics.age: Indicates the age of the individual.',
            'uses_person_document' => true,
            'uses_household_document' => false,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'person_phone',
            'name' => 'Person: Phone Number',
            'description' => 'person.phonecontact.(array)',
            'uses_person_document' => true,
            'uses_household_document' => false,
            'uses_place_document' => false
        ]);

        AppendOutput::create([
            'slug' => 'person_email',
            'name' => 'Person: Email Address',
            'description' => 'person.emailcontact.(array)',
            'uses_person_document' => true,
            'uses_household_document' => false,
            'uses_place_document' => false
        ]);

    }

}
