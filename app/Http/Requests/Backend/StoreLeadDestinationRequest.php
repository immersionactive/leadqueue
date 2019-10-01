<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadDestinationRequest extends FormRequest
{

    public function authorize()
    {
        return $this->user()->can('client.lead_destination.store');
    }

    /**
     * This form request is only used for authorization. All validation is handled
     * in the controller.
     */
    public function rules()
    {
        return [];
    }

}
