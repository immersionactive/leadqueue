<?php

namespace App\Http\Controllers\Backend;

use DB;
use App\DestinationConfigTypeRegistry;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\LeadDestination;
use App\Models\DestinationAppend;
use App\Models\AppendOutput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DestinationAppendController extends Controller
{

    protected $destination_config_type_registry;

    // https://laravel.io/forum/how-to-properly-use-controller-middleware
    public function __construct(DestinationConfigTypeRegistry $destination_config_type_registry)
    {
        $this->destination_config_type_registry = $destination_config_type_registry;
    }

    public function index(Client $client, LeadDestination $lead_destination)
    {

        $this->authorize('client.lead_destination.destination_append.index'); // TODO: make sure this works as expected

        $destination_appends = DestinationAppend::where('lead_destination_id', $lead_destination->id)->paginate(20);
        $append_outputs_list = AppendOutput::getList();
        $destination_config_type_classname = $this->getDestinationConfigTypeClassnameByModelClassname($lead_destination->destination_config_type);

        return view('backend.client.lead_destination.destination_append.index', [
            'client' => $client,
            'lead_destination' => $lead_destination,
            'destination_appends' => $destination_appends,
            'append_outputs_list' => $append_outputs_list,
            'destination_config_type_classname' => $destination_config_type_classname
        ]);

    }

    public function edit(Request $request, Client $client, LeadDestination $lead_destination, DestinationAppend $destination_append = null)
    {

        $this->authorize('client.lead_destination.destination_append.edit');

        $destination_config_type_classname = $this->getDestinationConfigTypeClassnameByModelClassname($lead_destination->destination_config_type);

        if (!$destination_append) {
            
            $destination_append = new DestinationAppend();
            $destination_append->lead_destination_id = $lead_destination->id;

            $destination_append_config = $destination_config_type_classname::buildDestinationAppendConfig($destination_append);

        } else {
            
            // TODO: load $destination_append_config
            $destination_append_config = $destination_append->destination_append_config;

        }

        $view = view('backend.client.lead_destination.destination_append.edit');

        if ($request->getMethod() === 'POST') {
            
            // patch

            $destination_append->append_output_slug = $request->input('append_output_slug');
            $destination_config_type_classname::patchDestinationAppendConfig($request, $destination_append, $destination_append_config);

            // validate

            $unique_rule = Rule::unique('destination_appends')->where(function ($query) use ($lead_destination) {
                return $query->where('lead_destination_id', $lead_destination->id);
            });

            if ($destination_append->exists) {
                $unique_rule->ignore($destination_append->id);
            }

            $rules = [                
                'append_output_slug' => [
                    'required',
                    'exists:append_outputs,slug',
                    $unique_rule,
                ]
            ];

            $rules += $destination_config_type_classname::getDestinationAppendConfigValidationRules($destination_append);

            $validator = Validator::make($request->all(), $rules);

            if (!$validator->fails()) {

                $is_new = !$destination_append->exists;

                DB::transaction(function () use ($destination_append, $destination_append_config) {
                    // this probably isn't the ideal way to save a polymorphic relation, but Laravel's docs are
                    // pretty sketchy...and this works.
                    $destination_append_config->save();
                    $destination_append->destination_append_config_id = $destination_append_config->id;
                    $destination_append->destination_append_config_type = get_class($destination_append_config);
                    $destination_append->save();
                });

                // TODO: also save the associated DestinationAppendConfig
                $destination_append->save();

                $user = auth()->user();
                Log::info('User ' . $user->id . ' (' . $user->email . ') ' . ($is_new ? 'created' : 'updated') . ' destination append ' . $destination_append->id . ' for lead destination ' . $lead_destination->id . ' (' . $lead_destination->name . ') for client ' . $client->id . ' (' . $client->name . ')');

                return redirect()->route('admin.client.lead_destination.destination_append.index', [$client, $lead_destination])->withFlashSuccess('Destination append ' . ($is_new ? 'created' : 'updated') . '.');

            }

            $view->withErrors($validator);

        }

        $append_outputs_list = ['' => ''] + AppendOutput::getList();

        return $view->with([
            'client' => $client,
            'lead_destination' => $lead_destination,
            'destination_config_type_classname' => $destination_config_type_classname,
            'destination_append' => $destination_append,
            'destination_append_config' => $destination_append_config,
            'append_outputs_list' => $append_outputs_list
        ]);

    }

    public function destroy(Client $client, LeadDestination $lead_destination, DestinationAppend $destination_append)
    {

        $this->authorize('client.lead_destination.destination_append.destroy');

        $destination_append->delete();

        // TODO: Log
            
        return redirect()->route('admin.client.lead_destination.destination_append.index', [$client, $lead_destination])->withFlashSuccess('Destination Append deleted.');        

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
