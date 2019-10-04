<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{

    public function index()
    {

        $this->authorize('client.index'); // TODO: make sure this works as expected

        // TODO: Log

        $clients = Client::paginate(20);

        return view('backend.client.index', [
            'clients' => $clients
        ]);

    }

    public function show(Client $client)
    {

        $this->authorize('client.show'); // TODO: make sure this works as expected

        // TODO: Log

        return view('backend.client.show', [
            'client' => $client
        ]);

    }

    public function edit(Request $request, Client $client = null)
    {

        $this->authorize('client.edit'); // TODO: make sure this works as expected

        // Set up the $client object

        if (!$client) {
            $client = new Client();
            $client->is_active = true;
            $client->notes = '';
        }

        $view = view('backend.client.edit');

        // If this is a POST request, then validate and (maybe) save

        if ($request->method() === 'POST') {

            // patch

            $client->name = $request->input('name');
            $client->is_active = !!$request->input('is_active');
            $client->notes = $request->input('notes') ?? '';

            // validate

            $validator = Validator::make($request->all(), [                
                'name' => [
                    'required',
                    'max:255',
                    'unique:clients' . ($client->exists ? ',name,' . $client->id : ''),
                ]
            ]);

            if (!$validator->fails()) {

                $is_new = !$client->exists;
                $client->save();

                // TODO: Log

                return redirect()->route('admin.client.show', $client)->withFlashSuccess('Client ' . ($is_new ? 'created' : 'updated') . '.');

            }

            $view->withErrors($validator);

        }

        return $view->with([
            'client' => $client
        ]);
        
    }

    public function delete($client_id)
    {

        $this->authorize('client.delete'); // TODO: make sure this works as expected

        $client->delete();

        // TODO: Log
            
        return redirect()->route('admin.client.index')->withFlashSuccess('Client deleted.');        

    }

}
