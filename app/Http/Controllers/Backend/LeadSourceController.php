<?php

namespace App\Http\Controllers\Backend;

use DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreLeadSourceRequest;
use App\Http\Requests\Backend\UpdateLeadSourceRequest;
use App\SourceConfigTypeRegistry;
use App\Models\Client;
use App\Models\LeadSource;
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

        $this->middleware('permission:client.lead_source.index', ['only' => ['index']]);
        $this->middleware('permission:client.lead_source.show', ['only' => ['show']]);
        $this->middleware('permission:client.lead_source.store', ['only' => ['create', 'store']]);
        $this->middleware('permission:client.lead_source.update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:client.lead_source.destroy', ['only' => ['destroy']]);

    }

    public function index(Client $client)
    {

        $lead_sources = LeadSource::where('client_id', $client->id)->paginate(20);
        $source_config_types_by_model_classname = $this->source_config_type_registry->getAllByModelClassname();

        return view('backend.client.lead_source.index', [
            'client' => $client,
            'lead_sources' => $lead_sources,
            'source_config_type_classnames' => $this->source_config_type_registry->getRegisteredTypes(),
            'source_config_types_by_model_classname' => $source_config_types_by_model_classname
        ]);

    }

    public function create(Client $client, $source_config_type_slug)
    {

        $source_config_type_classname = $this->getSourceConfigTypeClassnameBySlug($source_config_type_slug);

        $lead_source = new LeadSource();
       
        return view('backend.client.lead_source.create-edit', [
            'client' => $client,
            'lead_source' => $lead_source,
            'source_config_type_classname' => $source_config_type_classname
        ]);

    }

    /**
     * Because we're adding validation rules dynamically, the FormRequest
     * doesn't do any validation. Instead, validation is handled here in the
     * controller.
     */
    public function store(StoreLeadSourceRequest $request, Client $client, $source_config_type_slug)
    {

        $source_config_type_classname = $this->getSourceConfigTypeClassnameBySlug($source_config_type_slug);

        // Validate the request

        $rules = $this->getValidationRules($client);
        $rules += $source_config_type_classname::getStoreRules();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.client.lead_source.create', [$client, $source_config_type_slug])
                ->withErrors($validator)
                ->withInput();
        }

        // Create the lead source (but don't save it yet)

        $lead_source = new LeadSource();
        $lead_source->client_id = $client->id;
        $lead_source->name = $request->input('name');
        $lead_source->is_active = !!$request->input('is_active');
        $lead_source->notes = mb_strlen($request->input('notes')) ? $request->input('notes') : ''; // because the ConvertEmptyStringsToNull middleware breaks this otherwise

        // Create the source config (but don't save it yet)

        $source_config = $source_config_type_classname::buildConfig($request, $lead_source);

        // Now save both.
        // This might not be the best way to save a morphTo relation.
        DB::transaction(function () use ($lead_source, $source_config) {
            $lead_source->save();
            $source_config->save();
            $source_config->lead_source()->save($lead_source);
        });

        $user = auth()->user();
        Log::info('User ' . $user->id . ' (' . $user->email . ') created lead source ' . $lead_source->id . ' (' . $lead_source->name . ') for client ' . $client->id . ' (' . $client->name . ')');

        return redirect()->route('admin.client.lead_source.show', [$client, $lead_source])->withFlashSuccess('Lead source created.');

    }

    public function show(Client $client, LeadSource $lead_source)
    {

        $source_config_type_classname = $this->getSourceConfigTypeClassnameByModelClassname($lead_source->source_config_type);

        return view('backend.client.lead_source.show', [
            'client' => $client,
            'lead_source' => $lead_source,
            'source_config_type_classname' => $source_config_type_classname
        ]);

    }

    public function edit(Client $client, LeadSource $lead_source)
    {

        $source_config_type_classname = $this->getSourceConfigTypeClassnameByModelClassname($lead_source->source_config_type);

        if (!$source_config_type_classname) {
            throw new \Exception('"' . $lead_source->source_config_type . '" is not a recognized source config type classname.');
        }

        return view('backend.client.lead_source.create-edit', [
            'client' => $client,
            'lead_source' => $lead_source,
            'source_config_type_classname' => $source_config_type_classname
        ]);

    }

    /**
     * Because we're adding validation rules dynamically, the FormRequest
     * doesn't do any validation. Instead, validation is handled here in the
     * controller.
     */
    public function update(UpdateLeadSourceRequest $request, Client $client, LeadSource $lead_source)
    {

        $source_config_type_classname = $this->getSourceConfigTypeClassnameByModelClassname($lead_source->source_config_type);

        // Validate the request

        $rules = $this->getValidationRules($client, $lead_source->id);
        $rules += $source_config_type_classname::getUpdateRules($lead_source);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.client.lead_source.edit', [$client, $lead_source])
                ->withErrors($validator)
                ->withInput();
        }

        // Update the lead source (but don't save it yet)

        $lead_source->name = $request->input('name');
        $lead_source->is_active = !!$request->input('is_active');
        $lead_source->notes = mb_strlen($request->input('notes')) ? $request->input('notes') : ''; // because the ConvertEmptyStringsToNull middleware breaks this otherwise

        // Update the source config (but don't save it yet)

        $source_config_type_classname::patchConfig($request, $lead_source, $lead_source->source_config);

        // Save both the lead source and the source config

        $lead_source->push();

        // Log the action

        $user = auth()->user();
        Log::info('User ' . $user->id . ' (' . $user->email . ') updated lead source ' . $lead_source->id . ' (' . $lead_source->name . ') for client ' . $client->id . ' (' . $client->name . ')');
        
        return redirect()->route('admin.client.lead_source.show', [$client, $lead_source])->withFlashSuccess('Lead source updated.');

    }

    public function destroy(Client $client, LeadSource $lead_source)
    {
        
        $lead_source->delete();

        $user = auth()->user();
        Log::info('User ' . $user->id . ' (' . $user->email . ') deleted lead source ' . $lead_source->id . ' (' . $lead_source->name . ') for client ' . $client->id . ' (' . $client->name . ')');
            
        return redirect()->route('admin.client.lead_source.index', $client)->withFlashSuccess('Lead source deleted.');

    }

    /**
     * @param string $mode Either "store" or "update"
     * @return array
     */
    protected function getValidationRules(Client $client, $lead_source_id = null): array
    {

        // Make sure this client doesn't already have an existing lead source
        // with the same name.

        $unique_name_rule = Rule::unique('lead_sources')->where(function ($query) use ($client) {
            return $query->where('client_id', $client->id);
        });

        // If we're editing an existing lead source, then exclude it from the
        // uniqueness check.

        if ($lead_source_id) {
            $unique_name_rule->ignore($lead_source_id);
        }

        return [
            'name' => [
                'required',
                'max:255',
                $unique_name_rule,
            ],
        ];

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

}
