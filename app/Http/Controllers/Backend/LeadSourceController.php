<?php

namespace App\Http\Controllers\Backend;

use DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreLeadSourceRequest;
use App\Http\Requests\Backend\UpdateLeadSourceRequest;
use App\SourceConfigTypeRegistry;
use App\Models\Client;
use App\Models\LeadSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Validator;

class LeadSourceController extends Controller
{

    protected $source_config_type_registry;

    // https://laravel.io/forum/how-to-properly-use-controller-middleware
    public function __construct(SourceConfigTypeRegistry $source_config_type_registry)
    {
        $this->source_config_type_registry = $source_config_type_registry;
    }

    public function index(Client $client)
    {

        $this->authorize('client.lead_source.index');

        return view('backend.client.lead_source.index', [
            'client' => $client
        ]);

    }

    public function show(Client $client, LeadSource $lead_source)
    {

        $this->authorize('client.lead_source.show');

        $source_config_type_classname = $this->getSourceConfigTypeClassnameByModelClassname($lead_source->source_config_type);

        return view('backend.client.lead_source.show', [
            'client' => $client,
            'lead_source' => $lead_source,
            'source_config_type_classname' => $source_config_type_classname
        ]);

    }

    public function edit(Request $request, Client $client, LeadSource $lead_source = null)
    {

        $this->authorize('client.lead_source.edit');

        if (!$lead_source) {
            $source_config_type_slug = $request->input('type');
            $source_config_type_classname = $this->getSourceConfigTypeClassnameBySlug($source_config_type_slug);
            $lead_source = $this->buildLeadSource($client);
            $source_config = $source_config_type_classname::buildSourceConfig($lead_source);
        } else {
            $source_config = $lead_source->source_config;
            $source_config_type_classname = $lead_source->source_config_type;
        }

        $view = view('backend.client.lead_source.edit');

        if ($request->method() === 'POST') {

            // patch

            $lead_source->name = $request->input('name');
            $lead_source->notes = $request->input('notes') ?? '';

            $source_config_type_classname::patchSourceConfig($request, $lead_source, $source_config);

            // validate

            $validator = $this->buildValidator($request->all(), $client, $lead_source, $source_config_type_classname);

            if (!$validator->fails()) {

                $is_new = !$lead_source->exists;
                
                DB::transaction(function () use ($lead_source, $source_config) {
                    // this probably isn't the ideal way to save a polymorphic relation, but Laravel's docs are
                    // pretty sketchy...and this works.
                    $source_config->save();
                    $lead_source->source_config_id = $source_config->id;
                    $lead_source->source_config_type = get_class($source_config);
                    $lead_source->save();
                });

                $user = auth()->user();
                Log::info('User ' . $user->id . ' (' . $user->email . ') ' . ($is_new ? 'created' : 'updated') . ' lead source ' . $lead_source->id . ' (' . $lead_source->name . ') for client ' . $client->id . ' (' . $client->name . ')');

                return redirect()->route('admin.client.lead_source.show', [$client, $lead_source])->withFlashSuccess('Lead source ' . ($is_new ? 'created' : 'updated') . '.');

            }

            $view->withErrors($validator);

        }

        return $view->with([
            'client' => $client,
            'lead_source' => $lead_source,
            'source_config_type_classname' => $source_config_type_classname
        ]);

    }

    protected function getSourceConfigTypeClassnameBySlug(string $slug): string
    {
        $source_config_type_classname = $this->source_config_type_registry->getBySlug($slug);
        if ($source_config_type_classname === false) {
            throw new \Exception('"' . $slug . '" is not a recognized source config type slug.');
        }
        return $source_config_type_classname;
    }

    protected function getSourceConfigTypeClassnameByModelClassname(string $model_classname): string
    {
        $source_config_type_classname = $this->source_config_type_registry->getByModelClassname($model_classname);
        if (!$source_config_type_classname) {
            throw new \Exception('"' . $model_classname . '" is not a recognized source config type classname.');
        }
        return $source_config_type_classname;
    }

    protected function buildLeadSource(Client $client)
    {
        $lead_source = new LeadSource();
        $lead_source->client_id = $client->id;
        $lead_source->notes = '';
        return $lead_source;
    }    

    protected function buildValidator($input, Client $client, LeadSource $lead_source, string $source_config_type_classname): \Illuminate\Validation\Validator
    {

        $unique_name_rule = Rule::unique('lead_sources')->where(function ($query) use ($client) {
            return $query->where('client_id', $client->id);
        });

        // If we're editing an existing lead source, then exclude it from the
        // uniqueness check.

        if ($lead_source->exists) {
            $unique_name_rule->ignore($lead_source->id);
        }

        $rules = [
            'name' => [
                'required',
                'max:255',
                $unique_name_rule,
            ],
        ];

        $rules += $source_config_type_classname::getSourceConfigValidationRules($lead_source);

        $validator = Validator::make($input, $rules);

        return $validator;

    }

}
