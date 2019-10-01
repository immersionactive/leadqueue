<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadSourceRequest extends FormRequest
{

    public function authorize()
    {
        return $this->user()->can('client.lead_source.store');
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
