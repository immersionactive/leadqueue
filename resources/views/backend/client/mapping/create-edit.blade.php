@extends('backend.layouts.app')

@section('title', $client->name . ' – ' . ($mapping->exists ? 'Edit Mapping: ' . $mapping->name : 'Create Mapping'))

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @if ($mapping->exists)
        {{ html()->modelForm($mapping, 'PATCH', route('admin.client.mapping.update', [$client, $mapping]))->class('form-horizontal')->novalidate()->open() }}
    @else
        {{ html()->form('POST', route('admin.client.mapping.store', $client))->class('form-horizontal')->novalidate()->open() }}
    @endif

        <div class="card">

            <div class="card-body">

                <div class="row">

                    <div class="col-sm-5">
                        <h4 class="card-title mb-0">
                            {{ $client->name }}
                        </h4>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col">

                        @include('backend.client.includes.tabs', ['active_tab' => 'mappings', 'client' => $client])

                        <div class="tab-content">
                            <div class="tab-pane active" role="tabpanel" aria-expanded="true">

                                <h2 class="h4">
                                    @if ($mapping->exists)
                                        Edit Mapping: {{ $mapping->name }}
                                    @else
                                        New Mapping
                                    @endif
                                </h2>

                                {{-- Name --}}

                                <div class="form-group row">
                                    
                                    {{ html()->label('Name')
                                        ->class('col-md-2 form-control-label')
                                        ->for('name')
                                    }}

                                    <div class="col-md-10">
                                        {{ html()->text('name', old('name', $mapping->name))
                                            ->class('form-control')
                                            ->placeholder('Name')
                                            ->attribute('maxlength', 255)
                                            ->required()
                                            ->autofocus()
                                        }}
                                    </div>
                                    
                                </div>

                                {{-- Lead Source --}}

                                <div class="form-group row">
                                    
                                    {{ html()->label('Lead Source')
                                        ->class('col-md-2 form-control-label')
                                        ->for('lead_source_id')
                                    }}

                                    <div class="col-md-10">
                                        {{ html()->select('lead_source_id', $lead_source_options, old('lead_source_id', $mapping->lead_source_id))->class('form-control')->required() }}
                                    </div>
                                    
                                </div>

                                {{-- Lead Destination --}}

                                <div class="form-group row">
                                    
                                    {{ html()->label('Lead Destination')
                                        ->class('col-md-2 form-control-label')
                                        ->for('lead_destination_id')
                                    }}

                                    <div class="col-md-10">
                                        {{ html()->select('lead_destination_id', $lead_destination_options, old('lead_destination_id', $mapping->lead_destination_id))->class('form-control')->required() }}
                                    </div>
                                    
                                </div>

                            </div> <!-- .tab-pane -->
                        </div> <!-- .tab-content -->

                    </div> <!-- .col -->
                </div> <!-- .row -->

            </div> <!-- .card-body -->

            <div class="card-footer">
                <div class="row">
                    
                    <div class="col">
                        {{ form_cancel(route('admin.client.mapping.index', $client), __('buttons.general.cancel')) }}
                    </div>

                    <div class="col text-right">
                        {{ form_submit($mapping->exists ? __('buttons.general.crud.update') : __('buttons.general.crud.create')) }}
                    </div>

                </div>                
            </div> <!-- .card-footer -->

        </div> <!-- .card -->

    @if ($mapping->exists)
        {{ html()->closeModelForm() }}
    @else
        {{ html()->form()->close() }}
    @endif

@endsection
