<?php

namespace App\Http\Controllers\Backend;

use DB;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Mapping;
use App\Models\MappingField;
use App\SourceConfigTypeRegistry;
use App\DestinationConfigTypeRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;

class MappingFieldController extends Controller
{

    protected $source_config_type_registry;
    protected $destination_config_type_registry;

    public function __construct(SourceConfigTypeRegistry $source_config_type_registry, DestinationConfigTypeRegistry $destination_config_type_registry)
    {
        $this->source_config_type_registry = $source_config_type_registry;
        $this->destination_config_type_registry = $destination_config_type_registry;
    }

    public function index(Client $client, Mapping $mapping)
    {

        $this->authorize('client.mapping.mapping_field.index');

        $mapping_fields = MappingField::where('mapping_id', $mapping->id)->paginate(20);

        return view('backend.client.mapping.mapping_field.index', [
            'client' => $client,
            'mapping' => $mapping,
            'mapping_fields' => $mapping_fields,
            'source_config_type_classnames_by_model_classname' => $this->source_config_type_registry->getAllByModelClassname(),
            'destination_config_type_classnames_by_model_classname' => $this->destination_config_type_registry->getAllByModelClassname()
        ]);

    }

    public function show(Client $client, Mapping $mapping, MappingField $mapping_field)
    {

        return view('backend.client.mapping.mapping_field.show', [
            'client' => $client,
            'mapping' => $mapping,
            'mapping_field' => $mapping_field
        ]);

    }

    public function edit(Request $request, Client $client, Mapping $mapping, MappingField $mapping_field = null)
    {

        $this->authorize('client.mapping.mapping_field.edit');

        // Determine the source and destination types

        $source_config_type_classname = $this->source_config_type_registry->getByModelClassname($mapping->lead_source->source_config_type);
        $destination_config_type_classname = $this->destination_config_type_registry->getByModelClassname($mapping->lead_destination->destination_config_type);

        if (!$mapping_field) {
            
            $mapping_field = new MappingField();
            $mapping_field->mapping_id = $mapping->id;

            $source_field_config = $source_config_type_classname::buildSourceFieldConfig($mapping_field);
            $destination_field_config = $destination_config_type_classname::buildDestinationFieldConfig($mapping_field);

        }  else {

            $source_field_config = $mapping_field->source_field_config;
            $destination_field_config = $mapping_field->destination_field_config;

        }

        $view = view('backend.client.mapping.mapping_field.edit');

        if ($request->method() === 'POST') {

            // patch

            $source_config_type_classname::patchSourceFieldConfig($request, $mapping_field, $source_field_config);
            $destination_config_type_classname::patchDestinationFieldConfig($request, $mapping_field, $destination_field_config);

            // validate

            $validator = $this->buildValidator($request->all(), $mapping_field, $source_config_type_classname, $destination_config_type_classname);

            if (!$validator->fails()) {

                $is_new = !$mapping_field->exists;

                DB::transaction(function () use ($mapping_field, $source_field_config, $destination_field_config) {
                    
                    $source_field_config->save();
                    $mapping_field->source_field_config_id = $source_field_config->id;
                    $mapping_field->source_field_config_type = get_class($source_field_config);

                    $destination_field_config->save();
                    $mapping_field->destination_field_config_id = $destination_field_config->id;
                    $mapping_field->destination_field_config_type = get_class($destination_field_config);

                    $mapping_field->save();

                });

                $user = auth()->user();
                Log::info('User ' . $user->id . ' (' . $user->email . ') ' . ($is_new ? 'created' : 'updated') . ' mapping field ' . $mapping_field->id . ' for client ' . $client->id . ' (' . $client->name . ')');

                return redirect()->route('admin.client.mapping.mapping_field.show', [$client, $mapping, $mapping_field])->withFlashSuccess('Field ' . ($is_new ? 'created' : 'updated') . '.');

            }

            $view->withErrors($validator);

        }

        return $view->with([
            'client' => $client,
            'mapping' => $mapping,
            'mapping_field' => $mapping_field,
            'source_field_config' => $source_field_config,
            'source_config_type_classname' => $source_config_type_classname,
            'destination_field_config' => $destination_field_config,
            'destination_config_type_classname' => $destination_config_type_classname
        ]);

    }

    public function destroy(Client $client, Mapping $mapping, MappingField $mapping_field)
    {

        $this->authorize('client.mapping.mapping_field.destroy');

        $mapping_field->delete();

        $user = auth()->user();
        Log::info('User ' . $user->id . ' (' . $user->email . ') deleted mapping field ' . $mapping_field->id . ' for client ' . $client->id . ' (' . $client->name . ')');
            
        return redirect()->route('admin.client.mapping.mapping_field.index', [$client, $mapping])->withFlashSuccess('Field deleted.');        

    }

    protected function buildValidator($input, MappingField $mapping_field, string $source_config_type_classname, string $destination_config_type_classname): \Illuminate\Validation\Validator
    {

        $rules = [];

        $rules += $source_config_type_classname::getSourceFieldConfigValidationRules($mapping_field);
        $rules += $destination_config_type_classname::getDestinationFieldConfigValidationRules($mapping_field);

        $validator = Validator::make($input, $rules);

        return $validator;

    }

}
