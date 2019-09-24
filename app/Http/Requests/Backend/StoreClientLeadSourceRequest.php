<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientLeadSourceRequest extends FormRequest
{

    public function authorize()
    {
        return $this->user()->can('client.lead_source.create');
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:255',
                'unique:lead_sources' // TODO: only require this to be unique in the scope of the owning client
            ]
        ];
    }

}
