@extends('backend.layouts.app')

@section('title', $mapping->name . ': Fields')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.mapping.components.tabbox', ['client' => $client, 'mappings' => $mappings, 'active_mapping_id' => $mapping->id])

        <div class="row mb-4">

            <div class="col-md-6">
                <h4>{{ $mapping->name }}: Fields: {{ $mapping_field->exists ? 'Edit' : 'Create' }} Field</h4>
            </div>

        </div>

        {{ html()->modelForm($mapping_field, 'POST', route('admin.client.mapping.mapping_field.edit', [$client, $mapping, $mapping_field]))->class('form-horizontal')->novalidate()->open() }}

            <div class="card">

                <div class="card-header">
                    General
                </div>

                <div class="card-body">

                    <div class="form-group row">
                        
                        {{ html()->label('Name')
                            ->class('col-md-2 form-control-label')
                            ->for('name')
                        }}

                        <div class="col-md-10">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder('Name')
                                ->attribute('maxlength', 255)
                                ->required()
                                ->autofocus()
                            }}
                        </div>
                        
                    </div>
                        
                </div>

            </div>

            <div class="row">

                <div class="col-md-6">

                    <div class="card">

                        <div class="card-header">
                            <strong>Source Field</strong> (from {{ $source_config_type_classname::getName() }})
                        </div>

                        <div class="card-body">

                            @if ($mapping_field->exists)
                                @include($source_config_type_classname::getSourceFieldConfigEditView())
                            @else
                                @include($source_config_type_classname::getSourceFieldConfigCreateView())
                            @endif

                            {{-- Append Input --}}

                            <div class="form-group row">
                                
                                {{ html()->label('Append Input')
                                    ->class('col-md-4 form-control-label')
                                    ->for('append_input_property')
                                }}

                                <div class="col-md-8">
                                    {{ html()->select('append_input_property', $append_inputs_list, $mapping_field->append_input_property)->class('form-control')->required() }}
                                </div>
                                
                            </div>

                        </div> <!-- .card-body -->

                    </div> <!-- .card -->

                </div> <!-- .col -->

                <div class="col-md-6">

                    <div class="card">

                        <div class="card-header">
                            <strong>Destination Field</strong> (in {{ $destination_config_type_classname::getName() }})
                        </div>

                        <div class="card-body">

                            <p>Leave blank if you don't want to insert this field into the destination system. (This is useful if you want to configure a field for append only.)</p>

                            @if ($mapping_field->exists)
                                @include($destination_config_type_classname::getDestinationFieldConfigEditView())
                            @else
                                @include($destination_config_type_classname::getDestinationFieldConfigCreateView())
                            @endif

                        </div> <!-- .card-body -->

                    </div> <!-- .card -->

                </div> <!-- .col -->

            </div> <!-- .row -->

            <div class="row">
                
                <div class="col">
                    @if ($mapping_field->exists)
                        {{ form_cancel(route('admin.client.mapping.mapping_field.show', [$client, $mapping, $mapping_field]), __('buttons.general.cancel')) }}
                    @else
                        {{ form_cancel(route('admin.client.mapping.mapping_field.index', [$client, $mapping, null]), __('buttons.general.cancel')) }}
                    @endif
                </div>

                <div class="col text-right">
                    {{ form_submit($mapping->exists ? __('buttons.general.crud.update') : __('buttons.general.crud.create')) }}
                </div>

            </div>                

        {{ html()->closeModelForm() }}

    @endcomponent

@endsection
