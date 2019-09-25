<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreClientLeadSourceRequest;
// use App\Http\Requests\Backend\UpdateClientLeadSourceRequest;
use App\LeadSourceTypeRegistry;
use App\Models\Client;
use App\Models\LeadSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeadSourceController extends Controller
{

    protected $lead_source_type_registry;

    // https://laravel.io/forum/how-to-properly-use-controller-middleware
    public function __construct(LeadSourceTypeRegistry $lead_source_type_registry)
    {

        $this->lead_source_type_registry = $lead_source_type_registry;

        $this->middleware('permission:client.lead_source.index', ['only' => ['index']]);
        $this->middleware('permission:client.lead_source.show', ['only' => ['show']]);
        $this->middleware('permission:client.lead_source.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:client.lead_source.update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:client.lead_source.destroy', ['only' => ['destroy']]);

    }

    public function index(Client $client)
    {

        $lead_sources = LeadSource::where('client_id', $client->id)->paginate(20);

        return view('backend.client.lead_source.index', [
            'client' => $client,
            'lead_sources' => $lead_sources,
            'lead_source_type_classnames' => $this->lead_source_type_registry->getRegisteredTypes()
        ]);

    }

    public function create(Client $client, $lead_source_type_slug)
    {

        // die('in LeadSourceController->create(): ' . $lead_source_type_slug);
       
        return view('backend.client.lead_source.create', [
            'client' => $client
        ]);

    }

    public function store(StoreClientLeadSourceRequest $request, Client $client)
    {

        $lead_source = new LeadSource();
        $lead_source->client_id = $client->id;
        $lead_source->name = $request->input('name');
        $lead_source->is_active = !!$request->input('is_active');
        $lead_source->notes = mb_strlen($request->input('notes')) ? $request->input('notes') : ''; // because the ConvertEmptyStringsToNull middleware breaks this otherwise
        
        $lead_source->save();

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
        
        return view('backend.client.lead_source.edit', [
            'client' => $client,
            'lead_source' => $lead_source
        ]);

    }

    public function update(Request $request, Client $client, LeadSource $lead_source)
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

}
