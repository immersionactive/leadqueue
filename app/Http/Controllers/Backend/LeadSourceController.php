<?php

namespace App\Http\Controllers\Backend;

use DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreClientLeadSourceRequest;
use App\Http\Requests\Backend\UpdateClientLeadSourceRequest;
use App\SourceConfigTypeRegistry;
use App\Models\Client;
use App\Models\LeadSource;
use Illuminate\Support\Facades\Log;
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

        return view('backend.client.lead_source.index', [
            'client' => $client,
            'lead_sources' => $lead_sources,
            'source_config_type_classnames' => $this->source_config_type_registry->getRegisteredTypes()
        ]);

    }

    public function create(Client $client, $source_config_type_slug)
    {

        $source_config_type_classname = $this->source_config_type_registry->getBySlug($source_config_type_slug);

        if ($source_config_type_classname === false) {
            // TODO: Should this be a more specific kind of exception?
            throw new \Exception('"' . $source_config_type_slug . '" is not a recognized lead source type slug.');
        }
       
        return view('backend.client.lead_source.create', [
            'client' => $client,
            'source_config_type_classname' => $source_config_type_classname
        ]);

    }

    /**
     * Because we're adding validation rules dynamically, the FormRequest
     * doesn't do any validation. Instead, validation is handled here in the
     * controller.
     */
    public function store(StoreClientLeadSourceRequest $request, Client $client, $source_config_type_slug)
    {

        $source_config_type_classname = $this->source_config_type_registry->getBySlug($source_config_type_slug);

        if ($source_config_type_classname === false) {
            // TODO: Should this be a more specific kind of exception?
            throw new \Exception('"' . $source_config_type_slug . '" is not a recognized lead source type slug.');
        }

        $rules = $this->getValidationRules();
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

        return view('backend.client.lead_source.show', [
            'client' => $client,
            'lead_source' => $lead_source
        ]);

    }

    public function edit(Client $client, LeadSource $lead_source)
    {

        // TODO: how to get the $lead_source_type_classname from $lead_source?
        var_dump($lead_source->source_config_type); exit;
        
        return view('backend.client.lead_source.edit', [
            'client' => $client,
            'lead_source' => $lead_source
        ]);

    }

    /**
     * Because we're adding validation rules dynamically, the FormRequest
     * doesn't do any validation. Instead, validation is handled here in the
     * controller.
     */
    public function update(UpdateClientLeadSourceRequest $request, Client $client, LeadSource $lead_source)
    {

        $lead_source->name = $request->input('name');
        $lead_source->is_active = !!$request->input('is_active');
        $lead_source->notes = mb_strlen($request->input('notes')) ? $request->input('notes') : ''; // because the ConvertEmptyStringsToNull middleware breaks this otherwise

        $lead_source->save();

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

    protected function getValidationRules()
    {

        return [
            'name' => [
                'required',
                'max:255',
                // TODO: only require this to be unique in the scope of the owning client
                // TODO: exclude the current ID when editing an existing record
                'unique:lead_sources',
            ]
        ];

    }

}
