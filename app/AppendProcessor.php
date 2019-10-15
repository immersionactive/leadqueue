<?php

namespace App;

use App\AppendOutputExtractor;
use App\Models\AppendOutput;
use App\Models\Lead;
use App\Models\LeadAppendedValue;
use Carbon\Carbon;
use ImmersionActive\USADataAPI\USADataAPIClient;
use ImmersionActive\USADataAPI\LeadList;
use ImmersionActive\USADataAPI\Lead as USADataLead;

class AppendProcessor
{

    protected $usadata_api;
    protected $append_outputs_by_slug;
    protected $max_attempts;

    public function __construct()
    {
        $this->max_attempts = env('MAX_APPEND_ATTEMPTS', 5);
        $this->buildClient();
        $this->loadAppendOutputs();
    }

    protected function buildClient(): void
    {

        $username = env('USADATA_USERNAME');
        $password = env('USADATA_PASSWORD');
        $api_key = env('USADATA_API_KEY');

        if (
            $username === null ||
            $password === null ||
            $api_key === null
        ) {
            throw new \Exception('Environment variables were not found for USADATA_USERNAME, USADATA_PASSWORD, and/or USADATA_API_KEY. Please make sure these variables are configured in your .env file.');
        }

        $this->usadata_api = new USADataAPIClient($username, $password, $api_key);

    }

    /**
     * Preload all AppendOutputs.
     * @return void
     */
    protected function loadAppendOutputs(): void
    {

        $append_outputs_by_slug = [];

        $append_outputs = AppendOutput::all();

        foreach ($append_outputs as $append_output) {
            $append_outputs_by_slug[$append_output->slug] = $append_output;
        }

        $this->append_outputs_by_slug = $append_outputs_by_slug;

    }

    public function append(Lead &$lead): void
    {

        if ($lead->status !== 'new') {
            throw new \Exception('Lead ' . $lead->id . ' has already been appended; refusing to append it again.');
        }

        try {

            // Determine which AppendOutputs we need to extract for this LeadDestination

            $destination_appends = $lead->mapping->lead_destination->destination_appends;

            if ($destination_appends->count() > 0) {

                $documents_needed = $this->getDocumentsNeeded($destination_appends);
                $usadata_lead_properties = $this->buildUSADataLeadProperties($lead);
                $documents = $this->getDocuments($documents_needed, $usadata_lead_properties);

                // Now that we have the documents, extract the values that we're interested in, and
                // save them as new LeadAppendedValues.

                foreach ($destination_appends as $destination_append) {
                    $value = AppendOutputExtractor::extract($destination_append->append_output_slug, $documents);
                    if ($value === null) {
                        continue;
                    }
                    $lead_appended_value = new LeadAppendedValue();
                    $lead_appended_value->lead_id = $lead->id;
                    $lead_appended_value->destination_append_id = $destination_append->id;
                    $lead_appended_value->value = $value;
                    $lead_appended_value->save();
                }

            }

            $lead->status = 'appended';
            $lead->appended_at = Carbon::now();
            $lead->save();

        } catch (\Exception $e) {

            $lead->failed_append_attempts++;
            if ($lead->failed_append_attempts >= $this->max_attempts) {
                $lead->status = 'append_failed';
            }
            $lead->save();
            if ($lead->status === 'append_failed') {
                // Rethrow the exception, so the LeadProcess command will
                // notify someone.
                throw $e;
            }

        }

    }

    /**
     * Given a list of DestinationAppends, determines which document types we
     * need to load from the USADATA API in order to get the corresponding
     * append data. (Each call to the API costs money, so we don't want to
     * load documents that we don't need.)
     */
    protected function getDocumentsNeeded($destination_appends): array
    {

        $documents_needed = [
            'person' => false,
            'household' => false,
            'place' => false
        ];

        foreach ($destination_appends as $destination_append) {

            if (array_key_exists($destination_append->append_output_slug, $this->append_outputs_by_slug)) {

                $append_output = $this->append_outputs_by_slug[$destination_append->append_output_slug];

                if ($append_output->uses_person_document) {
                    $documents_needed['person'] = true;
                }

                if ($append_output->uses_household_document) {
                    $documents_needed['household'] = true;
                }

                if ($append_output->uses_place_document) {
                    $documents_needed['place'] = true;
                }

            }

        }

        $output = [];

        foreach ($documents_needed as $document => $needed) {
            if ($needed) {
                $output[] = $document;
            }
        }

        return $output;

    }

    /**
     * Given a Lead, extracts the raw input values that we need to send to
     * USADATA.
     */
    protected function buildUSADataLeadProperties(Lead $lead): array
    {

        $request_body = [];

        foreach ($lead->lead_inputs as $lead_input) {
            if ($lead_input->mapping_field->append_input_property !== null) {
                $value = $lead_input->value;
                $request_body[$lead_input->mapping_field->append_input_property] = $lead_input->value;
            }
        }

        return $request_body;

    }

    protected function getDocuments(array $documents_needed, array $usadata_lead_properties): array
    {

        $documents = [];

        foreach ($documents_needed as $document_needed) {

            switch ($document_needed) {
                case 'person':
                    $documents['person'] = $this->callMatchEndpoint($usadata_lead_properties, USADataAPIClient::DOCTYPE_PERSON);
                    break;
                case 'household':
                    $documents['household'] = $this->callMatchEndpoint($usadata_lead_properties, USADataAPIClient::DOCTYPE_HOUSEHOLD);                    
                    break;
                case 'place':
                    $documents['place'] = $this->callMatchEndpoint($usadata_lead_properties, USADataAPIClient::DOCTYPE_PLACE);                    
                    break;
            }

        }

        return $documents;

    }

    /**
     * Calls USADATA's Match endpoint, makes sure that the response matches
     * the expected format and has a code of 200, and returns the relevant
     * document.
     */
    protected function callMatchEndpoint(array $usadata_lead_properties, int $document_type) // TODO: type-hint return value?
    {

        $lead_list = $this->buildLeadList($usadata_lead_properties, $document_type);
        $response = $this->usadata_api->match($lead_list);

        if (!property_exists($response, 'Code')) {
            throw new \Exception('The response from the USADATA Match endpoint does not have a "Code" property.');
        }

        if ($response->Code !== 200) {
            throw new \Exception('The USADATA Match endpoint returned code ' . $response->Code . '.');
        }

        if (
            !property_exists($response, 'usadataResponseList') ||
            !is_array($response->usadataResponseList)
        ) {
            throw new \Exception('The response from the USADATA Match endpoint does not have a "usadataResponseList" property (or the property is not an array).');            
        }

        if (count($response->usadataResponseList) === 0) {
            throw new \Exception('The USADATA Match endpoint returned an empty usadataResponseList.');
        }

        $item = $response->usadataResponseList[0];

        if (
            property_exists($item, 'code') &&
            $item->code !== 200
        ) {
            throw new \Exception('The USADATA Match endpoint returned a usadataResponseList item with a code of ' . $item->code . '.');
        }

        if (
            !property_exists($item, 'document') ||
            !is_object($item->document)
        ) {
            throw new \Exception('The USADATA Match endpoint returned a usadataResponseList item that lacks a "document" property (or the property is not an object).');
        }

        $document = $item->document;

        switch ($document_type) {
            case USADataAPIClient::DOCTYPE_PERSON:
                $document_name = 'person';
                break;
            case USADataAPIClient::DOCTYPE_HOUSEHOLD:
                $document_name = 'household';
                break;
            case USADataAPIClient::DOCTYPE_PLACE:
                $document_name = 'place';
                break;
            default:
                throw new \Exception('Unsupported documentType: ' . $document_type);
                break;
        }

        if (
            !property_exists($document, $document_name) ||
            !is_object($document->$document_name)
        ) {
            throw new \Exception('The USADATA Match endpoint returned a usadataResponseList item with a document that lacks a "' . $document_name . '" property (or the property is not an object).');
        }

        return $document->$document_name;

    }

    protected function buildLeadList(array $usadata_lead_properties, int $document_type): LeadList
    {

        $usadata_lead_properties['documentType'] = $document_type;
        $usadata_lead_properties['Bundles'] = [''];
        $usadata_lead = new USADataLead($usadata_lead_properties);

        $lead_list = new LeadList();
        $lead_list->addLead($usadata_lead);

        return $lead_list;

    }

}
