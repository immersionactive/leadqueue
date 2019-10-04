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

            {{--
            <div class="row">

                <div class="col-md-6">

                    <div class="card">

                        <div class="card-header">
                            Data Append Fields
                        </div>

                        <div class="card-body">
                            
                            <p>Any appended data returned from the USADATA API will be inserted into these fields.</p>

                            <table class="table">
                                <thead>
                                    <th scope="col">Property</th>
                                    <th scope="col">
                                        Enabled
                                        <i class="fas fa-question-circle text-info" data-toggle="popover" data-placement="top" data-content="If unchecked, this appended data will not be saved to the destination CRM."></i>
                                    </th>
                                    <th scope="col">
                                        Destination Field
                                        <i class="fas fa-question-circle text-info" data-toggle="popover" data-placement="top" data-content="The field that the appended data will be saved to in the destination CRM."></i>
                                    </th>
                                </thead>
                                <tbody>
                                    @foreach ($append_properties as $append_property)
                                        <tr>
                                            <th scope="row">{{ $append_property->label }}</th>
                                            <td>
                                                
                                                <!-- TODO: we'll probably need to adjust the naming of these inputs -->

                                                <div class="checkbox d-flex align-items-center">

                                                    {{
                                                    html()
                                                        ->label(
                                                            html()
                                                                ->checkbox('destination_appends[' . $append_property->slug . '][is_enabled]' /* TODO: old value */)
                                                                ->class('switch-input')
                                                                ->id('destination_append__' . $append_property->slug . '__is_enabled')
                                                            . '<span class="switch-slider" data-checked="on" data-unchecked="off"></span>'
                                                        )
                                                        ->class('switch switch-label switch-pill switch-primary mr-2')
                                                        ->for('destination_append__' . $append_property->slug . '__is_enabled')
                                                    }}

                                                </div>

                                            </td>
                                            <td>

                                                @if ($lead_destination->exists)
                                                    @include($destination_config_type_classname::getAppendConfigEditView())
                                                @else
                                                    @include($destination_config_type_classname::getAppendConfigCreateView())
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                    </div>

                </div>

            </div>
            --}}

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
