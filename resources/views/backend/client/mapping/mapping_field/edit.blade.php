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

                        </div> <!-- .card-body -->

                    </div> <!-- .card -->

                </div> <!-- .col -->

                <div class="col-md-6">

                    <div class="card">

                        <div class="card-header">
                            <strong>Destination Field</strong> (in {{ $destination_config_type_classname::getName() }})
                        </div>

                        <div class="card-body">

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