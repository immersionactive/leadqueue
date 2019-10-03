<?php

namespace App\Http\Requests\Backend;

use App\Models\LeadSource;
use App\Models\LeadDestination;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMappingRequest extends FormRequest
{

    public function authorize()
    {
        return $this->user()->can('client.mapping.update');
    }

    public function rules()
    {

        $client = $this->route('client');
        $mapping = $this->route('mapping');

        return [
            
            'name' => [
                'required',
                'max:255',
                Rule::unique('mappings')->ignore($mapping->id)->where(function ($query) use ($client) {
                    return $query->where('client_id', $client->id);
                })
            ],

            'lead_source_id' => [
                'bail',
                'required',
                'exists:lead_sources,id',
                function ($attribute, $value, $fail) use ($client) {
                    $count = LeadSource::where(['id' => $value, 'client_id' => $client->id])->count();
                    if (!$count) {
                        $fail('The selected lead source does not belong to client ' . $client->id . '.');
                    }
                }
            ],

            'lead_destination_id' => [
                'bail',
                'required',
                'exists:lead_destinations,id',
                function ($attribute, $value, $fail) use ($client) {
                    $count = LeadDestination::where(['id' => $value, 'client_id' => $client->id])->count();
                    if (!$count) {
                        $fail('The selected lead destination does not belong to client ' . $client->id . '.');
                    }
                }
            ],

        ];

    }

}
