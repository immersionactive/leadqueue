<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{

    public function authorize()
    {
        return $this->user()->can('client.update');
    }

    public function rules()
    {
        
        $client = $this->route('client');

        return [
            'name' => [
                'required',
                'max:255',
                'unique:clients,name,' . $client->id
            ]
        ];
    }

}
