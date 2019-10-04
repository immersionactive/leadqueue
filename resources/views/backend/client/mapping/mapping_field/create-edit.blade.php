@extends('backend.layouts.app')

@section('title', $mapping->name . ': Fields')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.mapping.components.tabbox', ['client' => $client, 'mappings' => $mappings, 'active_mapping_id' => $mapping->id])

        {{--

        <h2 class="h4">
            @if ($mapping_field->exists)
                Edit Field
            @else
                New Field
            @endif
        </h2>

        @if ($mapping->exists)
            {{ html()->modelForm($mapping, 'PATCH', route('admin.client.mapping.mapping_field.update', [$client, $mapping, $mapping_field]))->class('form-horizontal')->novalidate()->open() }}
        @else
            {{ html()->form('POST', route('admin.client.mapping.mapping_field.store', [$client, $mapping]))->class('form-horizontal')->novalidate()->open() }}
        @endif

        <div class="row">

            <div class="col-md-4">

                <div class="card">

                    <div class="card-header">
                        Source Field
                    </div>

                    <div class="card-body">

                        <div class="form-group row">
                            
                            {{ html()->label('Name')
                                ->class('col-md-3 form-control-label')
                                ->for('name')
                            }}

                            <div class="col-md-9">
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


            </div>

            <div class="col-md-4">

                <div class="card">

                    <div class="card-header">
                        Destination Field
                    </div>

                    <div class="card-body">

                        <div class="form-group row">
                            
                            {{ html()->label('Name')
                                ->class('col-md-3 form-control-label')
                                ->for('name')
                            }}

                            <div class="col-md-9">
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

            </div>

            <div class="col-md-4">

                <div class="card">

                    <div class="card-header">
                        Pass to USADATA As&hellip;
                    </div>

                    <div class="card-body">

                        {{ html()->label('Append As&hellip;')
                            ->class('form-control-label')
                            ->for('append_as')
                        }}

                        <select class="form-control">
                            <option value="">None (No Append)</option>
                            <option value="">Phone</option>
                        </select>

                    </div>

                </div>

            </div>

        </div>

        @if ($mapping->exists)
            {{ html()->closeModelForm() }}
        @else
            {{ html()->form()->close() }}
        @endif

        --}}

    @endcomponent

@endsection
