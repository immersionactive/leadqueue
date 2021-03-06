@extends('backend.layouts.app')

@section('title', 'TODO')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.lead_destination.components.tabbox', ['client' => $client, 'lead_destinations' => $lead_destinations, 'active_lead_destination_id' => $lead_destination->id, 'destination_config_type_classnames' => $destination_config_type_classnames])

        {{ html()->modelForm($destination_append, 'POST', route('admin.client.lead_destination.destination_append.edit', ['client' => $client, 'lead_destination' => $lead_destination, 'destination_append' => $destination_append]))->class('form-horizontal')->novalidate()->open() }}

            <div class="form-group row">
                
                {{ html()->label('Append Output')
                    ->class('col-md-2 form-control-label')
                    ->for('append_output_slug')
                }}

                <div class="col-md-10">
                    {{ html()->select('append_output_slug', $append_outputs_list, $destination_append->append_output_slug)->required()->class('form-control') }}
                </div>
                
            </div>

            @if ($destination_append->exists)
                @include($destination_config_type_classname::getDestinationAppendConfigEditView())
            @else
                @include($destination_config_type_classname::getDestinationAppendConfigCreateView())
            @endif

            <div class="row">
                
                <div class="col">
                    {{ form_cancel(route('admin.client.lead_destination.destination_append.index', [$client, $lead_destination]), __('buttons.general.cancel')) }}
                </div>

                <div class="col text-right">
                    {{ form_submit($lead_destination->exists ? __('buttons.general.crud.update') : __('buttons.general.crud.create')) }}
                </div>

            </div>

        {{ html()->closeModelForm() }}

    @endcomponent

@endsection
