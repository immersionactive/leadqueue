@extends('backend.layouts.app')

@section('title', $client->name . ' – ' . ($lead_source->exists ? 'Edit Lead Source: ' . $lead_source->name : 'Create Lead Source'))

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @if ($lead_source->exists)
        {{ html()->modelForm($lead_source, 'PATCH', route('admin.client.lead_source.update', [$client, $lead_source]))->class('form-horizontal')->novalidate()->open() }}
    @else
        {{ html()->form('POST', route('admin.client.lead_source.store', [$client, $source_config_type_classname::getSlug()]))->class('form-horizontal')->novalidate()->open() }}
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

                        @include('backend.client.includes.tabs', ['active_tab' => 'lead_sources', 'client' => $client])

                        <div class="tab-content">
                            <div class="tab-pane active" role="tabpanel" aria-expanded="true">

                                <h2 class="h5">General Fields</h2>

                                {{-- Name --}}

                                <div class="form-group row">
                                    
                                    {{ html()->label('Name')
                                        ->class('col-md-2 form-control-label')
                                        ->for('name')
                                    }}

                                    <div class="col-md-10">
                                        {{ html()->text('name', old('name', $lead_source->name))
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
                                                    html()->checkbox('is_active', old('is_active', $lead_source->is_active))
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

                                        {{ html()->textarea('notes', old('notes', $lead_source->notes))
                                            ->class('form-control')
                                            ->placeholder('Notes')
                                        }}

                                    </div>

                                </div>

                                {{-- Additional type-specific fields --}}

                                <h2 class="h5">{{ $source_config_type_classname::getName() }} Fields</h2>

                                @if ($lead_source->exists)
                                    @include($source_config_type_classname::getEditView())
                                @else
                                    @include($source_config_type_classname::getCreateView())
                                @endif

                            </div> <!-- .tab-pane -->
                        </div> <!-- .tab-content -->

                    </div> <!-- .col -->
                </div> <!-- .row -->

            </div> <!-- .card-body -->

            <div class="card-footer">
                <div class="row">
                    
                    <div class="col">
                        {{ form_cancel(route('admin.client.lead_source.index', $client), __('buttons.general.cancel')) }}
                    </div>

                    <div class="col text-right">
                        {{ form_submit($lead_source->exists ? __('buttons.general.crud.update') : __('buttons.general.crud.create')) }}
                    </div>

                </div>                
            </div> <!-- .card-footer -->

        </div> <!-- .card -->

    @if ($lead_source->exists)

    @else

    @endif

@endsection