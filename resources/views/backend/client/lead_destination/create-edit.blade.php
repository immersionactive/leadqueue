@extends('backend.layouts.app')

@section('title', $client->name . ' – ' . ($lead_destination->exists ? 'Edit Lead Destination: ' . $lead_destination->name : 'Create Lead Destination'))

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.lead_destination.components.tabbox', ['client' => $client, 'lead_destinations' => $lead_destinations, 'active_lead_destination_id' => $lead_destination->id, 'destination_config_type_classnames' => $destination_config_type_classnames])

        <h2 class="h4">
            @if ($lead_destination->exists)
                Edit Lead Destination: {{ $lead_destination->name }}
            @else
                New Lead Destination
            @endif
        </h2>

        @if ($lead_destination->exists)
            {{ html()->modelForm($lead_destination, 'PATCH', route('admin.client.lead_destination.update', [$client, $lead_destination]))->class('form-horizontal')->novalidate()->open() }}
        @else
            {{ html()->form('POST', route('admin.client.lead_destination.store', [$client, $destination_config_type_classname::getSlug()]))->class('form-horizontal')->novalidate()->open() }}
        @endif

        <h2 class="h5">General Fields</h2>

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

        {{-- Active --}}

        <div class="form-group row">

            {{ html()->label('Active')
                ->class('col-md-2 form-control-label')
                ->for('is_active')
            }}

            <div class="col-md-10">

                <div class="checkbox d-flex align-items-center">
                    {{-- TODO: Add help text explaining that when a client is deactivated, so is all of their lead processing --}}
                    {{ html()->label(
                            html()->checkbox('is_active', old('is_active', $lead_destination->is_active))
                                  ->class('switch-input')
                                  ->id('is_active')
                                . '<span class="switch-slider" data-checked="on" data-unchecked="off"></span>')
                            ->class('switch switch-label switch-pill switch-primary mr-2')
                        ->for('is_active')
                    }}
                </div>

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

        {{-- Additional type-specific fields --}}

        <h2 class="h5">{{ $destination_config_type_classname::getName() }} Fields</h2>

        @if ($lead_destination->exists)
            @include($destination_config_type_classname::getEditView())
        @else
            @include($destination_config_type_classname::getCreateView())
        @endif

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

        @if ($lead_destination->exists)
            {{ html()->closeModelForm() }}
        @else
            {{ html()->form()->close() }}
        @endif

    @endcomponent

@endsection
