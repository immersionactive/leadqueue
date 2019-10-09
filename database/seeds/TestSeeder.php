<?php

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
            'account' => 'noblespondhomes--npsandbox',
            'token' => 'TODO'
        ]);

        $lead_destination = LeadDestination::create([
            'client_id' => $client->id,
            'name' => 'Noble\'s Pond Propertybase Sandbox',
            'notes' => '',
            'destination_config_id' => $destination_config->id,
            'destination_config_type' => PropertybaseWebToProspectDestinationConfig::class
        ]);

        $destination_append_defns = [
            [
                'append_output_slug' => 'age',
                'contact_field_name' => 'Append_Age'
            ],
            [
                'append_output_slug' => 'gender',
                'contact_field_name' => 'Gender'
            ],
        ];

        foreach ($destination_append_defns as $destination_append_defn) {

            $destination_append_config = PropertybaseLeadDestinationAppendConfig::create([
                'contact_field_name' => $destination_append_defn['contact_field_name']
            ]);

            $destination_append = DestinationAppend::create([
                'lead_destination_id' => $lead_destination->id,
                'append_output_slug' => $destination_append_defn['append_output_slug'],
                'destination_append_config_id' => $destination_append_config->id,
                'destination_append_config_type' => PropertybaseLeadDestinationAppendConfig::class
            ]);

        }

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
                'source_field_name' => '3.3',
                'destination_contact_field_name' => 'FirstName',
                'append_input_property' => 'FirstName'
            ],
            [
                'source_field_name' => '3.6',
                'destination_contact_field_name' => 'LastName',
                'append_input_property' => 'LastName'
            ],
            [
                'source_field_name' => '4',
                'destination_contact_field_name' => 'Email',
                'append_input_property' => 'Email'
            ],
            [
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
                'source_field_config_id' => $source_field_config->id,
                'source_field_config_type' => GravityFormsSourceFieldConfig::class,
                'destination_field_config_id' => $destination_field_config->id,
                'destination_field_config_type' => PropertybaseDestinationFieldConfig::class,
                'append_input_property' => $mapping_field_defn['append_input_property']
            ]);

        }

        return $mapping;

    }

}
