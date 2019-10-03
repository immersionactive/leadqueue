<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreMappingRequest;
use App\Http\Requests\Backend\UpdateMappingRequest;
use App\Models\Client;
use App\Models\LeadSource;
use App\Models\LeadDestination;
use App\Models\Mapping;
use Illuminate\Support\Facades\Log;

class MappingController extends Controller
{

    // https://laravel.io/forum/how-to-properly-use-controller-middleware
    public function __construct()
    {
        $this->middleware('permission:client.mapping.index', ['only' => ['index']]);
        $this->middleware('permission:client.mapping.show', ['only' => ['show']]);
        $this->middleware('permission:client.mapping.store', ['only' => ['create', 'store']]);
        $this->middleware('permission:client.mapping.update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:client.mapping.destroy', ['only' => ['destroy']]);
    }

    public function index(Client $client)
    {

        $mappings = Mapping::where('client_id', $client->id)->paginate(20);

        return view('backend.client.mapping.index', [
            'client' => $client,
            'mappings' => $mappings
        ]);

    }

    public function show(Client $client, Mapping $mapping)
    {

        return view('backend.client.mapping.show', [
            'client' => $client,
            'mapping' => $mapping
        ]);

    }

    public function create(Client $client)
    {

        $mapping = new Mapping();

        $lead_source_options = LeadSource::getOptions($client->id);
        $lead_destination_options = LeadDestination::getOptions($client->id);

        return view('backend.client.mapping.create-edit', [
            'client' => $client,
            'mapping' => $mapping,
            'lead_source_options' => $lead_source_options,
            'lead_destination_options' => $lead_destination_options
        ]);

    }

    public function store(StoreMappingRequest $request, Client $client)
    {

        $mapping = new Mapping();
        $mapping->client_id = $client->id;
        $mapping->name = $request->input('name');
        $mapping->lead_source_id = $request->input('lead_source_id');
        $mapping->lead_destination_id = $request->input('lead_destination_id');

        $mapping->save();

        $user = auth()->user();
        Log::info('User ' . $user->id . ' (' . $user->email . ') created mapping ' . $mapping->id . ' (' . $mapping->name . ') for client ' . $client->id . ' (' . $client->name . ')');

        return redirect()->route('admin.client.mapping.show', [$client, $mapping])->withFlashSuccess('Mapping created.');

    }

    public function edit(Client $client, Mapping $mapping)
    {

        $lead_source_options = LeadSource::getOptions($client->id, $mapping->lead_source_id);
        $lead_destination_options = LeadDestination::getOptions($client->id, $mapping->lead_destination_id);

        return view('backend.client.mapping.create-edit', [
            'client' => $client,
            'mapping' => $mapping,
            'lead_source_options' => $lead_source_options,
            'lead_destination_options' => $lead_destination_options
        ]);

    }

    public function update(UpdateMappingRequest $request, Client $client, Mapping $mapping)
    {

        $mapping->name = $request->input('name');
        $mapping->lead_source_id = $request->input('lead_source_id');
        $mapping->lead_destination_id = $request->input('lead_destination_id');

        $mapping->save();

        $user = auth()->user();
        Log::info('User ' . $user->id . ' (' . $user->email . ') updated mapping ' . $mapping->id . ' (' . $mapping->name . ') for client ' . $client->id . ' (' . $client->name . ')');

        return redirect()->route('admin.client.mapping.show', [$client, $mapping])->withFlashSuccess('Mapping updated.');

    }

    public function destroy(Client $client, Mapping $mapping)
    {

        $mapping->delete();

        $user = auth()->user();
        Log::info('User ' . $user->id . ' (' . $user->email . ') deleted mapping ' . $mapping->id . ' (' . $mapping->name . ') for client ' . $client->id . ' (' . $client->name . ')');
            
        return redirect()->route('admin.client.mapping.index', $client)->withFlashSuccess('Mapping deleted.');        

    }

}
