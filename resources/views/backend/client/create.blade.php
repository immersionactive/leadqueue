@extends('backend.layouts.app')

@section('title', 'Create Client')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    {{ html()->form('POST', route('admin.client.store'))->class('form-horizontal')->open() }}

        <div class="card">

            <div class="card-body">

                <div class="row">
                    <div class="col-sm-5">
                        <h4 class="card-title mb-0">
                            Clients
                            <small class="text-muted">Create Client</small>
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
                                {{ html()->text('name', old('name'))
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
                                            html()->checkbox('is_active', !!old('is_active'), '1')
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

                                {{ html()->textarea('notes', old('notes'))
                                    ->class('form-control')
                                    ->placeholder('Notes')
                                }}

                            </div>

                        </div>

                    </div>
                </div>

            </div> <!-- .card-body -->

            <div class="card-footer">
                <div class="row">
                    
                    <div class="col">
                        {{ form_cancel(route('admin.client.index'), __('buttons.general.cancel')) }}
                    </div>

                    <div class="col text-right">
                        {{ form_submit(__('buttons.general.crud.create')) }}
                    </div>

                </div>                
            </div> <!-- .card-footer -->

        </div> <!-- .card -->

    {{ html()->form()->close() }}
    
@endsection
