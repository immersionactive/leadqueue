<?php

namespace App\Http\Controllers\Backend;

use DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreLeadDestinationRequest;
use App\Http\Requests\Backend\UpdateLeadDestinationRequest;
use App\DestinationConfigTypeRegistry;
use App\Models\AppendProperty;
use App\Models\Client;
use App\Models\LeadDestination;
use App\Models\DestinationAppend;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Validator;

class LeadDestinationController extends Controller
{

    protected $destination_config_type_registry;

    // https://laravel.io/forum/how-to-properly-use-controller-middleware
    public function __construct(DestinationConfigTypeRegistry $destination_config_type_registry)
    {

        $this->destination_config_type_registry = $destination_config_type_registry;

        $this->middleware('permission:client.lead_destination.index', ['only' => ['index']]);
        $this->middleware('permission:client.lead_destination.show', ['only' => ['show']]);
        $this->middleware('permission:client.lead_destination.store', ['only' => ['create', 'store']]);
        $this->middleware('permission:client.lead_destination.update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:client.lead_destination.destroy', ['only' => ['destroy']]);

    }

    public function index(Client $client)
    {

        $lead_destinations = LeadDestination::where('client_id', $client->id)->paginate(20);
        $destination_config_types_by_model_classname = $this->destination_config_type_registry->getAllByModelClassname();

        return view('backend.client.lead_destination.index', [
            'client' => $client,
            'lead_destinations' => $lead_destinations,
            'destination_config_type_classnames' => $this->destination_config_type_registry->getRegisteredTypes(),
            'destination_config_types_by_model_classname' => $destination_config_types_by_model_classname
        ]);

    }

    public function create(Client $client, $destination_config_type_slug)
    {

        $destination_config_type_classname = $this->getDestinationConfigTypeClassnameBySlug($destination_config_type_slug);
        $destination_config_model_classname = $destination_config_type_classname::getModelClassname();
        $append_properties = AppendProperty::all();

        /**
         * Scaffold a new LeadDestination, along with its associated children.
         */

        $lead_destination = new LeadDestination();

        // scaffold the DestinationConfig

        $lead_destination->destination_config = new $destination_config_model_classname;

        // scaffold the DestinationAppends

        foreach ($append_properties as $append_property) {

            $destination_append = new DestinationAppend();
            $destination_append->append_property_slug = $append_property->slug;

            $lead_destination->destination_appends[] = $destination_append;

        }
       
        return view('backend.client.lead_destination.create-edit', [
            'client' => $client,
            'lead_destination' => $lead_destination,
            'destination_config_type_classname' => $destination_config_type_classname,
            'append_properties' => $append_properties
        ]);

    }

    /**
     * Because we're adding validation rules dynamically, the FormRequest
     * doesn't do any validation. Instead, validation is handled here in the
     * controller.
     */
    public function store(StoreLeadDestinationRequest $request, Client $client, $destination_config_type_slug)
    {

        $destination_config_type_classname = $this->getDestinationConfigTypeClassnameBySlug($destination_config_type_slug);

        // Validate the request

        $rules = $this->getValidationRules($client);
        $rules += $destination_config_type_classname::getStoreRules();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.client.lead_destination.create', [$client, $destination_config_type_slug])
                ->withErrors($validator)
                ->withInput();
        }

        // Create the lead destination (but don't save it yet)

        $lead_destination = new LeadDestination();
        $lead_destination->client_id = $client->id;
        $lead_destination->name = $request->input('name');
        $lead_destination->is_active = !!$request->input('is_active');
        $lead_destination->notes = mb_strlen($request->input('notes')) ? $request->input('notes') : ''; // because the ConvertEmptyStringsToNull middleware breaks this otherwise

        // Create the destination config (but don't save it yet)

        $destination_config = $destination_config_type_classname::buildConfig($request, $lead_destination);

        // Create LeadDestinationAppends

        $destination_appends = [];

        if (is_array($request->input('destination_appends'))) {

            $append_properties = AppendProperty::all();

            foreach ($append_properties as $append_property) {

                $destination_append = new DestinationAppend();
                $destination_append->append_property_slug = $append_property->slug;

                // Did the request include an entry for this append property?
                if (array_key_exists($append_property->slug, $request->input('destination_appends'))) {
                    $destination_append->is_enabled = !!$request->input('destination_appends')[$append_property->slug]['is_enabled'];
                    // TODO: build the associated destination_append_config
                } else {
                    $destination_append->is_enabled = false;
                }

                $destination_appends[] = $destination_append;

            }

        }

        echo '<pre>' . json_encode($destination_appends, JSON_PRETTY_PRINT) . '</pre>'; exit;

        throw new \Exception('unimplemented');

        // Now save both.
        // This might not be the best way to save a morphTo relation.
        DB::transaction(function () use ($lead_destination, $destination_config) {
            $lead_destination->save();
            $destination_config->save();
            $destination_config->lead_destination()->save($lead_destination);
        });

        $user = auth()->user();
        Log::info('User ' . $user->id . ' (' . $user->email . ') created lead destination ' . $lead_destination->id . ' (' . $lead_destination->name . ') for client ' . $client->id . ' (' . $client->name . ')');

        return redirect()->route('admin.client.lead_destination.show', [$client, $lead_destination])->withFlashSuccess('Lead destination created.');

    }

    public function show(Client $client, LeadDestination $lead_destination)
    {

        $destination_config_type_classname = $this->getDestinationConfigTypeClassnameByModelClassname($lead_destination->destination_config_type);

        return view('backend.client.lead_destination.show', [
            'client' => $client,
            'lead_destination' => $lead_destination,
            'destination_config_type_classname' => $destination_config_type_classname
        ]);

    }

    public function edit(Client $client, LeadDestination $lead_destination)
    {

        $destination_config_type_classname = $this->getDestinationConfigTypeClassnameByModelClassname($lead_destination->destination_config_type);

        if (!$destination_config_type_classname) {
            throw new \Exception('"' . $lead_destination->destination_config_type . '" is not a recognized destination config type classname.');
        }

        return view('backend.client.lead_destination.create-edit', [
            'client' => $client,
            'lead_destination' => $lead_destination,
            'destination_config_type_classname' => $destination_config_type_classname
        ]);

    }

    /**
     * Because we're adding validation rules dynamically, the FormRequest
     * doesn't do any validation. Instead, validation is handled here in the
     * controller.
     */
    public function update(UpdateLeadDestinationRequest $request, Client $client, LeadDestination $lead_destination)
    {

        $destination_config_type_classname = $this->getDestinationConfigTypeClassnameByModelClassname($lead_destination->destination_config_type);

        // Validate the request

        $rules = $this->getValidationRules($client, $lead_destination->id);
        $rules += $destination_config_type_classname::getUpdateRules($lead_destination);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.client.lead_destination.edit', [$client, $lead_destination])
                ->withErrors($validator)
                ->withInput();
        }

        // Update the lead destination (but don't save it yet)

        $lead_destination->name = $request->input('name');
        $lead_destination->is_active = !!$request->input('is_active');
        $lead_destination->notes = mb_strlen($request->input('notes')) ? $request->input('notes') : ''; // because the ConvertEmptyStringsToNull middleware breaks this otherwise

        // Update the destination config (but don't save it yet)

        $destination_config_type_classname::patchConfig($request, $lead_destination, $lead_destination->destination_config);

        // Save both the lead destination and the destination config

        $lead_destination->push();

        // Log the action

        $user = auth()->user();
        Log::info('User ' . $user->id . ' (' . $user->email . ') updated lead destination ' . $lead_destination->id . ' (' . $lead_destination->name . ') for client ' . $client->id . ' (' . $client->name . ')');
        
        return redirect()->route('admin.client.lead_destination.show', [$client, $lead_destination])->withFlashSuccess('Lead destination updated.');

    }

    public function destroy(Client $client, LeadDestination $lead_destination)
    {
        
        $lead_destination->delete();

        $user = auth()->user();
        Log::info('User ' . $user->id . ' (' . $user->email . ') deleted lead destination ' . $lead_destination->id . ' (' . $lead_destination->name . ') for client ' . $client->id . ' (' . $client->name . ')');
            
        return redirect()->route('admin.client.lead_destination.index', $client)->withFlashSuccess('Lead destination deleted.');

    }

    /**
     * @param string $mode Either "store" or "update"
     * @return array
     */
    protected function getValidationRules(Client $client, $lead_destination_id = null): array
    {

        // Make sure this client doesn't already have an existing lead destination
        // with the same name.

        $unique_name_rule = Rule::unique('lead_destinations')->where(function ($query) use ($client) {
            return $query->where('client_id', $client->id);
        });

        // If we're editing an existing lead destination, then exclude it from the
        // uniqueness check.

        if ($lead_destination_id) {
            $unique_name_rule->ignore($lead_destination_id);
        }

        return [
            'name' => [
                'required',
                'max:255',
                $unique_name_rule,
            ],
        ];

    }

    protected function getDestinationConfigTypeClassnameBySlug(string $slug): string
    {
        $destination_config_type_classname = $this->destination_config_type_registry->getBySlug($slug);
        if ($destination_config_type_classname === false) {
            throw new \Exception('"' . $slug . '" is not a recognized destination config type slug.');
        }
        return $destination_config_type_classname;
    }

    protected function getDestinationConfigTypeClassnameByModelClassname(string $model_classname): string
    {
        $destination_config_type_classname = $this->destination_config_type_registry->getByModelClassname($model_classname);
        if (!$destination_config_type_classname) {
            throw new \Exception('"' . $model_classname . '" is not a recognized destination config type classname.');
        }
        return $destination_config_type_classname;
    }

}
