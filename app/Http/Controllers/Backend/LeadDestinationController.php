<?php

namespace App\Http\Controllers\Backend;

use DB;
use App\Http\Controllers\Controller;
use App\DestinationConfigTypeRegistry;
use App\Models\Client;
use App\Models\LeadDestination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Validator;

class LeadDestinationController extends Controller
{

    protected $destination_config_type_registry;

    public function __construct(DestinationConfigTypeRegistry $destination_config_type_registry)
    {
        $this->destination_config_type_registry = $destination_config_type_registry;
    }

    public function index(Client $client)
    {

        $this->authorize('client.lead_destination.index');

        $lead_destinations = LeadDestination::where('client_id', $client->id)->paginate(20);

        return view('backend.client.lead_destination.index', [
            'client' => $client,
            'lead_destinations' => $lead_destinations,
            'destination_config_type_classnames' => $this->destination_config_type_registry->getRegisteredTypes()
        ]);

    }

    public function show(Client $client, LeadDestination $lead_destination)
    {

        // TODO: check permissions

        $destination_config_type_classname = $this->getDestinationConfigTypeClassnameByModelClassname($lead_destination->destination_config_type);

        return view('backend.client.lead_destination.show', [
            'client' => $client,
            'lead_destination' => $lead_destination,
            'destination_config_type_classname' => $destination_config_type_classname
        ]);

    }

    public function edit(Request $request, Client $client, LeadDestination $lead_destination = null)
    {

        $this->authorize('client.lead_destination.edit');

        if (!$lead_destination) {
            $destination_config_type_slug = $request->input('type');
            $destination_config_type_classname = $this->getDestinationConfigTypeClassnameBySlug($destination_config_type_slug);
            $lead_destination = $this->buildLeadDestination($client, $destination_config_type_classname);
            $destination_config = $destination_config_type_classname::buildDestinationConfig($lead_destination);
        } else {
            $destination_config_type_classname = $this->getDestinationConfigTypeClassnameByModelClassname($lead_destination->destination_config_type);
            $destination_config = $lead_destination->destination_config;
        }

        $view = view('backend.client.lead_destination.edit');

        if ($request->method() === 'POST') {

            // patch

            $lead_destination->name = $request->input('name');
            $lead_destination->notes = $request->input('notes') ?? '';

            $destination_config_type_classname::patchDestinationConfig($request, $lead_destination, $destination_config);

            // validate

            $validator = $this->buildValidator($request->all(), $client, $lead_destination, $destination_config_type_classname);

            if (!$validator->fails()) {

                $is_new = !$lead_destination->exists;
                
                DB::transaction(function () use ($lead_destination, $destination_config) {
                    // this probably isn't the ideal way to save a polymorphic relation, but Laravel's docs are
                    // pretty sketchy...and this works.
                    $destination_config->save();
                    $lead_destination->destination_config_id = $destination_config->id;
                    $lead_destination->destination_config_type = get_class($destination_config);
                    $lead_destination->save();
                });

                $user = auth()->user();
                Log::info('User ' . $user->id . ' (' . $user->email . ') ' . ($is_new ? 'created' : 'updated') . ' lead destination ' . $lead_destination->id . ' (' . $lead_destination->name . ') for client ' . $client->id . ' (' . $client->name . ')');

                return redirect()->route('admin.client.lead_destination.show', [$client, $lead_destination])->withFlashSuccess('Lead destination ' . ($is_new ? 'created' : 'updated') . '.');

            }

            $view->withErrors($validator);

        }

        return $view->with([
            'client' => $client,
            'lead_destination' => $lead_destination,
            'destination_config' => $destination_config,
            'destination_config_type_classname' => $destination_config_type_classname
        ]);

    }

    public function destroy(Client $client, LeadDestination $lead_destination)
    {

        $this->authorize('client.lead_destination.destroy');

        $lead_destination->delete();

        // TODO: Log

        return redirect()->route('admin.client.lead_destination.index', [$client])->withFlashSuccess('Lead Destination deleted.');        

    }


    protected function buildLeadDestination(Client $client, string $destination_config_type_classname): LeadDestination
    {
        $lead_destination = new LeadDestination();
        $lead_destination->client_id = $client->id;
        $lead_destination->notes = '';
        return $lead_destination;
    }

    protected function buildValidator($input, Client $client, LeadDestination $lead_destination, string $destination_config_type_classname): \Illuminate\Validation\Validator
    {

        // Make sure this client doesn't already have an existing lead
        // destination with the same name.

        $unique_name_rule = Rule::unique('lead_destinations')->where(function ($query) use ($client) {
            return $query->where('client_id', $client->id);
        });

        // If we're editing an existing lead destination, then exclude it
        // from the uniqueness check.

        if ($lead_destination->exists) {
            $unique_name_rule->ignore($lead_destination->id);
        }

        $rules = [
            'name' => [
                'required',
                'max:255',
                $unique_name_rule,
            ],
        ];

        $rules += $destination_config_type_classname::getDestinationConfigValidationRules($lead_destination);

        $validator = Validator::make($input, $rules);

        return $validator;

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
