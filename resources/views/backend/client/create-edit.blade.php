@extends('backend.layouts.app')

@section('title', $client->exists ? 'Edit Client: ' . $client->name : 'Create Client')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @if ($client->exists)
        {{ html()->modelForm($client, 'PATCH', route('admin.client.update', $client))->class('form-horizontal')->novalidate()->open() }}
    @else
        {{ html()->form('POST', route('admin.client.store'))->class('form-horizontal')->novalidate()->open() }}
    @endif

        <div class="card">

            <div class="card-body">

                <div class="row">
                    <div class="col-sm-5">
                        <h4 class="card-title mb-0">
                            Clients
                            <small class="text-muted">{{ $client->exists ? 'Edit' : 'Create' }} Client</small>
                        </h4>
                    </div>
                </div>

                <hr>

                <div class="row mt-4">
                    <div class="col">

                        {{-- Name --}}

                        <div class="form-group row">
                            
                            {{ html()->label('Name')
                                ->class('col-md-2 form-control-label')
                                ->for('name')
                            }}

                            <div class="col-md-10">
                                {{ html()->text('name', old('name', $client->name))
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
                                            html()->checkbox('is_active', old('is_active', $client->is_active))
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

                                {{ html()->textarea('notes', old('notes', $client->notes))
                                    ->class('form-control')
                                    ->placeholder('Notes')
                                }}

                            </div>

                        </div>

                    </div> <!-- .col -->
                </div> <!-- .row -->

            </div> <!-- .card-body -->

            <div class="card-footer">
                <div class="row">
                    
                    <div class="col">
                        {{ form_cancel(route('admin.client.index'), __('buttons.general.cancel')) }}
                    </div>

                    <div class="col text-right">
                        {{ form_submit($client->exists ? __('buttons.general.crud.update') : __('buttons.general.crud.create')) }}
                    </div>

                </div>                
            </div> <!-- .card-footer -->

        </div> <!-- .card -->

    @if ($client->exists)
        {{ html()->closeModelForm() }}
    @else
        {{ html()->form()->close() }}
    @endif
    
@endsection
