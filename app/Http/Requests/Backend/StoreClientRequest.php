<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{

    public function authorize()
    {
        return $this->user()->can('client.create');
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:255',
                'unique:clients'
            ]
        ];
    }

}
