<?php

namespace App\Http\Controllers\Backend;

use App\CSVStreamDownload;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreClientRequest;
use App\Http\Requests\Backend\UpdateClientRequest;
use App\Models\AppendOutput;
use App\Models\Client;
use App\Models\DestinationAppend;
use App\Models\Lead;
use App\Models\LeadAppendedValue;
use App\Models\LeadInput;
use App\Models\Mapping;
use App\Models\MappingField;
use App\SourceConfigTypeRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{

    protected $source_config_type_registry;

    public function __construct(SourceConfigTypeRegistry $source_config_type_registry)
    {
        $this->source_config_type_registry = $source_config_type_registry;
    }

    public function index(Request $request, Client $client, Mapping $mapping)
    {

        $this->authorize('client.mapping.lead.index');

        $filters = $this->getFilters($request);
        $validator = $this->buildValidator($filters);
        $leads = $this->buildLeadsQuery($mapping->id, $filters, $validator);

        // I don't know why the "$leads =" is necessary, but it breaks
        // horribly without it
        $leads = $leads->paginate(20);

        return view('backend.client.mapping.lead.index', [
            'client' => $client,
            'mapping' => $mapping,
            'leads' => $leads,
            'lead_statuses_list' => Lead::getStatusList(true),
            'filters' => $filters
        ])->withErrors($validator);

    }

    public function download(Request $request, Client $client, Mapping $mapping)
    {

        $this->authorize('client.mapping.lead.index');

        $filters = $this->getFilters($request);
        $validator = $this->buildValidator($filters);
        $leads = $this->buildLeadsQuery($mapping->id, $filters, $validator);

        $leads = $leads->get();

        $filename = $this->filenameize($client->name) . '_' . $this->filenameize($mapping->name) . '_Leads.csv';
        $lead_statuses_list = Lead::getStatusList(true);

        $header_row = $this->buildCSVHeaderRow($mapping);

        // TODO (performance): This duplicates the query run in buildCSVHeaderRow(), which isn't great. (It's not a super-expensive query, though.)
        $mapping_fields = MappingField::where('mapping_id', $mapping->id)->orderBy('id')->get();

        $destination_appends = DestinationAppend::where('lead_destination_id', $mapping->lead_destination_id)->orderBy('append_output_slug')->get();

        $csv = new CSVStreamDownload(
            $leads,
            function ($lead) use ($lead_statuses_list, $mapping_fields, $destination_appends) {

                $row = [
                    $lead->id,
                    $lead->created_at,
                    array_key_exists($lead->status, $lead_statuses_list) ? $lead_statuses_list[$lead->status] : $lead_status,
                ];

                /**
                 * Add MappingField columns
                 */

                $lead_inputs = LeadInput::where('lead_id', $lead->id)->get()->keyBy('mapping_field_id');
                $lead_inputs_array = $lead_inputs->toArray();

                foreach ($mapping_fields as $mapping_field) {

                    if (array_key_exists($mapping_field->id, $lead_inputs_array)) {
                        $row[] = $lead_inputs[$mapping_field->id]->value;
                    } else {                    
                        $row[] = '';
                    }

                }

                /**
                 * Add DestinationAppend columns
                 */

                // Don't even bother issuing the query if the lead hasn't been appended
                $lead_is_appended = $lead->isAppended();
                if ($lead_is_appended) {
                    $lead_appended_values = LeadAppendedValue::where('lead_id', $lead->id)->get()->keyBy('destination_append_id');
                    $lead_appended_values_array = $lead_appended_values->toArray();
                }

                foreach ($destination_appends as $destination_append) {

                    if (
                        $lead_is_appended &&
                        array_key_exists($destination_append->id, $lead_appended_values_array)
                    ) {
                        $row[] = $lead_appended_values[$destination_append->id]->value;
                    } else {
                        $row[] = '';
                    }
                    
                }

                return $row;

            },
            $header_row
        );

        // Uncomment this line if you want to view the CSV in the browser as an HTML table (for development & debugging)
        // echo $csv->renderTable(); exit;

        return response()->streamDownload(function () use ($csv) {
            echo $csv->render();
        }, $filename);

    }

    protected function buildCSVHeaderRow(Mapping $mapping): array
    {
        
        $header_row = [
            'Lead ID',
            'Received (UTC)',
            'Status'
        ];

        /**
         * Add MappingField columns
         */

        $mapping_fields = MappingField::where('mapping_id', $mapping->id)->orderBy('id')->get();

        foreach ($mapping_fields as $mapping_field) {
            $header_row[] = $mapping_field->name;
        }

        /**
         * Add DestinationAppend columns
         */

        $destination_appends = DestinationAppend::where('lead_destination_id', $mapping->lead_destination_id)->orderBy('append_output_slug')->get();
        $append_outputs_list = AppendOutput::getList();

        foreach ($destination_appends as $destination_append) {
            $name = array_key_exists($destination_append->append_output_slug, $append_outputs_list) ? $append_outputs_list[$destination_append->append_output_slug] : $destination_append->append_output_slug;
            $header_row[] = 'Appended: ' . $name;
        }

        return $header_row;

    }

    public function show(Client $client, Mapping $mapping, Lead $lead)
    {

        $this->authorize('client.mapping.lead.show');
        $source_config_type_classname = $this->source_config_type_registry->getByModelClassname($mapping->lead_source->source_config_type);

        return view('backend.client.mapping.lead.show', [
            'client' => $client,
            'mapping' => $mapping,
            'lead' => $lead,
            'source_config_type_classname' => $source_config_type_classname
        ]);

    }

    protected function getFilters(Request $request): array
    {

        $filters = [
            'status' => $request->query('status'),
            'created_at_start' => $request->query('created_at_start'),
            'created_at_end' => $request->query('created_at_end')
        ];

        return $filters;

    }

    protected function buildValidator(array $filters): \Illuminate\Validation\Validator
    {

        $datetime_regex = '/^
            (20[0-9]{2}) # year
            -
            (0[1-9]|1[0-2]) # month
            -
            (0[1-9]|[1-2][0-9]|3[0-1]) # day
            [ ]
            ([0-1][0-9]|2[0-3]) # hour
            :
            [0-5][0-9] # minute
            :
            [0-5][0-9] # second
            $/x';

        $validator = Validator::make(
            $filters,
            [
                'created_at_start' => [
                    'nullable',
                    'regex:' . $datetime_regex
                ],
                'created_at_end' => [
                    'nullable',
                    'regex:' . $datetime_regex
                ]
            ],
            [
                'regex' => ':attribute: Invalid format. Must use the format "2019-10-14 16:47:19".'
            ]
        );

        return $validator;

    }

    protected function buildLeadsQuery(int $mapping_id, array $filters, \Illuminate\Validation\Validator $validator) // TODO: type-hint return value
    {

        $leads = Lead::where('mapping_id', $mapping_id)->orderBy('created_at', 'DESC');

        $errors = $validator->errors();

        if ($filters['status']) {
            $leads->where('status', $filters['status']);
        }

        if (
            $filters['created_at_start'] &&
            !$errors->has('created_at_start')
        ) {
            $leads->where('created_at', '>=', $filters['created_at_start']);
        }

        if (
            $filters['created_at_end'] &&
            !$errors->has('created_at_end')
        ) {
            $leads->where('created_at', '<=', $filters['created_at_end']);
        }

        return $leads;

    }

    /**
     * Removes all non-alphanumeric characters from a string, and replaces all
     * sequences of whitespace with hyphens.
     */
    protected function filenameize(string $input)
    {
        
        $cleaned = preg_replace('/[^ a-zA-Z0-9]/', '', $input);
        $cleaned = preg_replace('/(\s+)/', '-', $cleaned);
        return $cleaned;

    }

}
