<?php

use App\Models\AppendOutput;
use App\Models\Client;
use App\Models\DestinationAppend;
use App\Models\LeadDestination;
use App\Models\LeadSource;
use App\Models\Mapping;
use App\Models\MappingField;
use ImmersionActive\LeadQueueGravityFormsSource\Models\GravityFormsSourceConfig;
use ImmersionActive\LeadQueueGravityFormsSource\Models\GravityFormsSourceFieldConfig;
use ImmersionActive\LeadQueuePropertybaseDestination\Models\PropertybaseWebToProspectDestinationConfig;
use ImmersionActive\LeadQueuePropertybaseDestination\Models\PropertybaseDestinationFieldConfig;
use ImmersionActive\LeadQueuePropertybaseDestination\Models\PropertybaseLeadDestinationAppendConfig;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $client = $this->createClient();
        $lead_source = $this->createLeadSource($client);
        $lead_destination = $this->createLeadDestination($client);
        $this->createDestinationAppends($lead_destination);
        $mapping = $this->createMapping($client, $lead_source, $lead_destination);

    }

    protected function createClient(): Client
    {

        $client = Client::create([
            'name' => 'Noble\'s Pond',
            'is_active' => true,
            'notes' => '',
            'is_active' => true
        ]);

        return $client;

    }

    protected function createLeadSource(Client $client): LeadSource
    {

        $source_config = GravityFormsSourceConfig::create([]);

        $lead_source = LeadSource::create([
            'client_id' => $client->id,
            'name' => 'Gravity Forms Webhook',
            'notes' => '',
            'source_config_id' => $source_config->id,
            'source_config_type' => GravityFormsSourceConfig::class
        ]);

        return $lead_source;

    }

    protected function createLeadDestination(Client $client): LeadDestination
    {

        $destination_config = PropertybaseWebToProspectDestinationConfig::create([
            'api_site_domain' => 'npsandbox-noblespond.cs16.force.com',
            'token' => 'TODO' // DO NOT PUT REAL TOKENS HERE, OR IN ANY OTHER FILE THAT IS COMMITTED TO THE REPOSITORY!
        ]);

        $lead_destination = LeadDestination::create([
            'client_id' => $client->id,
            'name' => 'Noble\'s Pond Propertybase Sandbox',
            'notes' => '',
            'destination_config_id' => $destination_config->id,
            'destination_config_type' => PropertybaseWebToProspectDestinationConfig::class
        ]);

        return $lead_destination;

    }

    protected function createMapping(Client $client, LeadSource $lead_source, LeadDestination $lead_destination): Mapping
    {

        $mapping = Mapping::create([
            'client_id' => $client->id,
            'name' => 'Gravity Forms to Propertybase',
            'notes' => '',
            'lead_source_id' => $lead_source->id,
            'lead_destination_id' => $lead_destination->id,
            'is_active' => true
        ]);

        $mapping_field_defns = [
            [
                'name' => 'First Name',
                'source_field_name' => '3.3',
                'destination_contact_field_name' => 'FirstName',
                'append_input_property' => 'FirstName'
            ],
            [
                'name' => 'Last Name',
                'source_field_name' => '3.6',
                'destination_contact_field_name' => 'LastName',
                'append_input_property' => 'LastName'
            ],
            [
                'name' => 'Email',
                'source_field_name' => '4',
                'destination_contact_field_name' => 'Email',
                'append_input_property' => 'Email'
            ],
            [
                'name' => 'Phone',
                'source_field_name' => '5',
                'destination_contact_field_name' => 'Phone',
                'append_input_property' => 'Phone'
            ]
        ];

        foreach ($mapping_field_defns as $mapping_field_defn) {

            $source_field_config = GravityFormsSourceFieldConfig::create([
                'field_name' => $mapping_field_defn['source_field_name']
            ]);

            $destination_field_config = PropertybaseDestinationFieldConfig::create([
                'contact_field_name' => $mapping_field_defn['destination_contact_field_name']
            ]);

            $mapping_field = MappingField::create([
                'mapping_id' => $mapping->id,
                'name' => $mapping_field_defn['name'],
                'source_field_config_id' => $source_field_config->id,
                'source_field_config_type' => GravityFormsSourceFieldConfig::class,
                'destination_field_config_id' => $destination_field_config->id,
                'destination_field_config_type' => PropertybaseDestinationFieldConfig::class,
                'append_input_property' => $mapping_field_defn['append_input_property']
            ]);

        }

        return $mapping;

    }

    protected function createDestinationAppends(LeadDestination $lead_destination): void
    {

        $defns = [
            [
                'append_output_slug' => 'person_length_of_residence',
                'contact_field_name' => 'Append_Person_LengthOfResidence' // custom field
            ],
            [
                'append_output_slug' => 'person_recent_home_buyer',
                'contact_field_name' => 'Append_Person_RecentHomeBuyer' // custom field
            ],
            [
                'append_output_slug' => 'household_length_of_residence',
                'contact_field_name' => 'Append_Household_LengthOfResidence' // custom field
            ],
            [
                'append_output_slug' => 'household_recent_home_buyer',
                'contact_field_name' => 'Append_Household_RecentHomeBuyer' // custom field
            ],
            [
                'append_output_slug' => 'place_property_assessed_value',
                'contact_field_name' => 'Append_Place_PropertyAssessedValue' // custom field
            ],
            [
                'append_output_slug' => 'place_property_market_value',
                'contact_field_name' => 'Append_Place_PropertyMarketValue' // custom field
            ],
            [
                'append_output_slug' => 'place_property_market_value_decile',
                'contact_field_name' => 'Append_Place_PropertyMarketValueDecile' // custom field
            ],
            [
                'append_output_slug' => 'place_property_market_value_quality_indicator',
                'contact_field_name' => 'Append_Place_PropertyMarketValueQualityIndicator' // custom field
            ],
            [
                'append_output_slug' => 'person_age',
                'contact_field_name' => 'Append_Person_Age' // custom field
            ],
            [
                'append_output_slug' => 'person_gender',
                'contact_field_name' => 'Append_Person_Gender' // custom field
            ],
            [
                'append_output_slug' => 'person_estimated_income',
                'contact_field_name' => '' // capture, but don't insert into the destination system
            ],
            [
                'append_output_slug' => 'household_estimated_income',
                'contact_field_name' => '' // capture, but don't insert into the destination system
            ],
            [
                'append_output_slug' => 'household_adults_age_range',
                'contact_field_name' => '' // capture, but don't insert into the destination system
            ],
            [
                'append_output_slug' => 'person_phone',
                'contact_field_name' => '' // capture, but don't insert into the destination system
            ],
            [
                'append_output_slug' => 'person_address_street_and_unit',
                'contact_field_name' => 'MailingStreet' // standard field
            ],
            [
                'append_output_slug' => 'person_address_city',
                'contact_field_name' => 'MailingCity' // standard field
            ],
            [
                'append_output_slug' => 'person_address_state',
                'contact_field_name' => 'MailingState' // standard field
            ],
            [
                'append_output_slug' => 'person_address_zip',
                'contact_field_name' => 'MailingPostalCode' // standard field
            ]
        ];

        foreach ($defns as $defn) {

            $destination_append_config = PropertybaseLeadDestinationAppendConfig::create([
                'contact_field_name' => $defn['contact_field_name']
            ]);

            $destination_append = DestinationAppend::create([
                'lead_destination_id' => $lead_destination->id,
                'append_output_slug' => $defn['append_output_slug'],
                'destination_append_config_id' => $destination_append_config->id,
                'destination_append_config_type' => PropertybaseLeadDestinationAppendConfig::class
            ]);

        }

    }

}
