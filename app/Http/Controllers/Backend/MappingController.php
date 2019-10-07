<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\LeadSource;
use App\Models\LeadDestination;
use App\Models\Mapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Validator;

class MappingController extends Controller
{

    public function index(Client $client)
    {

        $this->authorize('client.mapping.index');

        $mappings = Mapping::where('client_id', $client->id)->paginate(20);

        return view('backend.client.mapping.index', [
            'client' => $client,
            'mappings' => $mappings
        ]);

    }

    public function show(Client $client, Mapping $mapping)
    {

        $this->authorize('client.mapping.show');

        return view('backend.client.mapping.show', [
            'client' => $client,
            'mapping' => $mapping
        ]);

    }

    public function edit(Request $request, Client $client, Mapping $mapping = null)
    {

        $this->authorize('client.mapping.edit');

        if (!$mapping) {
            $mapping = $this->buildMapping($client);
        }

        $view = view('backend.client.mapping.edit');

        /**
         * Handle for submissions
         */

        if ($request->method() === 'POST') {
            
            // patch

            $mapping->name = $request->input('name');
            $mapping->is_active = !!$request->input('is_active');
            $mapping->notes = $request->input('notes') ?? '';
            $mapping->lead_source_id = $request->input('lead_source_id');
            $mapping->lead_destination_id = $request->input('lead_destination_id');

            // validate

            $validator = $this->buildValidator($request->all(), $mapping);

            if (!$validator->fails()) {

                $is_new = !$mapping->exists;

                $mapping->save();

                $user = auth()->user();
                Log::info('User ' . $user->id . ' (' . $user->email . ') ' . ($is_new ? 'created' : 'updated') . ' mapping ' . $mapping->id . ' (' . $mapping->name . ') for client ' . $client->id . ' (' . $client->name . ')');

                return redirect()->route('admin.client.mapping.show', [$client, $mapping])->withFlashSuccess('Mapping ' . ($is_new ? 'created' : 'updated') . '.');

            }

            $view->withErrors($validator);

        }

        /**
         * Render view
         */

        $lead_source_list = ['' => ''] + LeadSource::getList($client->id);
        $lead_destination_list = ['' => ''] + LeadDestination::getList($client->id);

        return $view->with([
            'client' => $client,
            'mapping' => $mapping,
            'lead_source_list' => $lead_source_list,
            'lead_destination_list' => $lead_destination_list
        ]);

    }

    public function destroy(Client $client, Mapping $mapping)
    {

        $mapping->delete();

        $user = auth()->user();
        Log::info('User ' . $user->id . ' (' . $user->email . ') deleted mapping ' . $mapping->id . ' (' . $mapping->name . ') for client ' . $client->id . ' (' . $client->name . ')');
            
        return redirect()->route('admin.client.mapping.index', $client)->withFlashSuccess('Mapping deleted.');        

    }

    protected function buildMapping(Client $client): Mapping
    {
        $mapping = new Mapping();
        $mapping->client_id = $client->id;
        $mapping->is_active = true;
        $mapping->notes = '';
        return $mapping;
    }

    protected function buildValidator($input, Mapping $mapping): \Illuminate\Validation\Validator
    {

        // Ensure that this mapping's name is unique (within the scope of this client)

        $unique_name_rule = Rule::unique('mappings')->where(function ($query) use ($mapping) {
            return $query->where('client_id', $mapping->client_id);
        });

        if ($mapping->exists) {
            $unique_name_rule->ignore($mapping->id);
        }

        $rules = [

            'name' => [
                'required',
                'max:255',
                $unique_name_rule
            ],

            'lead_source_id' => [
                'bail',
                'required',
                'exists:lead_sources,id',
                function ($attribute, $value, $fail) use ($mapping) {
                    $count = LeadSource::where(['id' => $value, 'client_id' => $mapping->client_id])->count();
                    if (!$count) {
                        $fail('The selected lead source does not belong to client ' . $mapping->client_id . '.');
                    }
                }
            ],

            'lead_destination_id' => [
                'bail',
                'required',
                'exists:lead_destinations,id',
                function ($attribute, $value, $fail) use ($mapping) {
                    $count = LeadDestination::where(['id' => $value, 'client_id' => $mapping->client_id])->count();
                    if (!$count) {
                        $fail('The selected lead destination does not belong to client ' . $mapping->client_id . '.');
                    }
                }
            ],
            
        ];

        $validator = Validator::make($input, $rules);

        return $validator;

    }

}
