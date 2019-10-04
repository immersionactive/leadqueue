<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Mapping;
use App\Models\MappingField;
use Illuminate\Support\Facades\Log;

class MappingFieldController extends Controller
{

    // https://laravel.io/forum/how-to-properly-use-controller-middleware
    public function __construct()
    {
        $this->middleware('permission:client.mapping.mapping_field.index', ['only' => ['index']]);
        $this->middleware('permission:client.mapping.mapping_field.show', ['only' => ['show']]);
        $this->middleware('permission:client.mapping.mapping_field.store', ['only' => ['create', 'store']]);
        $this->middleware('permission:client.mapping.mapping_field.update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:client.mapping.mapping_field.destroy', ['only' => ['destroy']]);
    }

    public function index(Client $client, Mapping $mapping)
    {

        $mapping_fields = MappingField::where('mapping_id', $mapping->id)->paginate(20);

        return view('backend.client.mapping.mapping_field.index', [
            'client' => $client,
            'mapping' => $mapping,
            'mapping_fields' => $mapping_fields
        ]);

    }

    public function create(Client $client, Mapping $mapping)
    {

        $mapping_field = new MappingField();
        $mapping_field->mapping_id = $mapping->id;

        return view('backend.client.mapping.mapping_field.create-edit', [
            'client' => $client,
            'mapping' => $mapping,
            'mapping_field' => $mapping_field
        ]);

    }

}
