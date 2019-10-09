@extends('backend.layouts.app')

@section('title', $client->name . ' – ' . ($lead_destination->exists ? 'Edit Lead Destination: ' . $lead_destination->name : 'Create Lead Destination'))

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.lead_destination.components.tabbox', ['client' => $client, 'lead_destinations' => $lead_destinations, 'active_lead_destination_id' => $lead_destination->id, 'destination_config_type_classnames' => $destination_config_type_classnames])

        <h2 class="h4 mb-4">
            @if ($lead_destination->exists)
                Edit Lead Destination: {{ $lead_destination->name }}
            @else
                New Lead Destination
            @endif
        </h2>

        {{ html()->modelForm($lead_destination, 'POST', route('admin.client.lead_destination.edit', ['client' => $client, 'lead_destination' => $lead_destination, 'type' => $lead_destination->exists ? null : $destination_config_type_classname::getSlug() ]))->class('form-horizontal')->novalidate()->open() }}

            <div class="row">

                <div class="col-md-6">

                    <div class="card">

                        <div class="card-header">
                            General
                        </div>

                        <div class="card-body">

                            {{-- Name --}}

                            <div class="form-group row">
                                
                                {{ html()->label('Name')
                                    ->class('col-md-2 form-control-label')
                                    ->for('name')
                                }}

                                <div class="col-md-10">
                                    {{ html()->text('name', old('name', $lead_destination->name))
                                        ->class('form-control')
                                        ->placeholder('Name')
                                        ->attribute('maxlength', 255)
                                        ->required()
                                        ->autofocus()
                                    }}
                                </div>
                                
                            </div>

                            {{-- Notes --}}

                            <div class="form-group row">

                                {{ html()->label('Notes')->class('col-md-2 form-control-label')->for('notes') }}

                                <div class="col-md-10">

                                    {{ html()->textarea('notes', old('notes', $lead_destination->notes))
                                        ->class('form-control')
                                        ->placeholder('Notes')
                                    }}

                                </div>

                            </div>

                        </div> <!-- .card-body -->

                    </div> <!-- .card -->

                </div> <!-- .col -->

                <div class="col-md-6">

                    <div class="card">

                        <div class="card-header">
                            {{ $destination_config_type_classname::getName() }} Connection Info
                        </div>

                        <div class="card-body">

                            @if ($lead_destination->exists)
                                @include($destination_config_type_classname::getEditView())
                            @else
                                @include($destination_config_type_classname::getCreateView())
                            @endif

                        </div> <!-- .card-body -->

                    </div> <!-- .card -->

                </div> <!-- .col -->

            </div> <!-- .row -->

            <div class="row">
                
                <div class="col">
                    @if ($lead_destination->exists)
                        {{ form_cancel(route('admin.client.lead_destination.show', [$client, $lead_destination]), __('buttons.general.cancel')) }}
                    @else
                        {{ form_cancel(route('admin.client.lead_destination.index', $client), __('buttons.general.cancel')) }}
                    @endif
                </div>

                <div class="col text-right">
                    {{ form_submit($lead_destination->exists ? __('buttons.general.crud.update') : __('buttons.general.crud.create')) }}
                </div>

            </div>                

        {{ html()->closeModelForm() }}

    @endcomponent

@endsection
