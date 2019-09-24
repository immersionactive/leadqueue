<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreClientRequest;
use App\Http\Requests\Backend\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    // https://laravel.io/forum/how-to-properly-use-controller-middleware
    public function __construct()
    {
        $this->middleware('permission:client.index', ['only' => ['index']]);
        $this->middleware('permission:client.show', ['only' => ['show']]);
        $this->middleware('permission:client.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:client.update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:client.destroy', ['only' => ['destroy']]);
    }

    public function index()
    {

        $clients = Client::paginate(20);

        return view('backend.client.index', [
            'clients' => $clients
        ]);

    }

    public function create()
    {
       
        return view('backend.client.create');

    }

    public function store(StoreClientRequest $request)
    {

        $client = new Client();
        $client->name = $request->input('name');
        $client->is_active = !!$request->input('is_active');
        // $client->notes = $request->input('notes');
        $client->save();

        return redirect()->route('admin.client.index')->withFlashSuccess('The client was successfully created.');

    }

    public function show(Client $client)
    {
        
        return view('backend.client.show', [
            'client' => $client
        ]);

    }

    public function edit(Client $client)
    {
        
        return view('backend.client.edit', [
            'client' => $client
        ]);

    }

    public function update(UpdateClientRequest $request, Client $client)
    {

        $client->name = $request->input('name');
        $client->is_active = !!$request->input('is_active');
        // $client->notes = $request->input('notes');

        $client->save();
        
        return redirect()->route('admin.client.index')->withFlashSuccess('Client updated.');

    }

    public function destroy(Client $client)
    {
        
        $client->delete();    
            
        return redirect()->route('admin.client.index')->withFlashSuccess('Client deleted.');        

    }

}
