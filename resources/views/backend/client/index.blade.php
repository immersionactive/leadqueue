@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@section('content')

    <div class="card">        
        <div class="card-body">

            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        Clients
                    </h4>
                </div>

                <div class="col-sm-7">
                    <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                        @can('client.create')
                            <a href="{{ route('admin.client.create') }}" class="btn btn-success ml-1" data-toggle="tooltip" title="@lang('labels.general.create_new')"><i class="fas fa-plus-circle"></i></a>
                        @endcan
                    </div>
                </div>

            </div>

            <div class="row mt-4">
                <div class="col">

                    {{-- Filters (TODO: finish implementing this) --}}

                    {{--
                    <form method="get" action="{{ route('admin.client.index') }}">

                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="active">Active Only</option>
                            <option value="inactive">Inactive Only</option>
                            <option value="all">All</option>
                        </select>

                        <button type="submit" class="btn btn-primary">Apply Filters</button>

                    </form>
                    --}}

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Client Name</th>
                                    <th scope="col">Active</th>
                                    <th scope="col">@lang('labels.general.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                    <tr>
                                        <td>
                                            @if ($client->trashed())
                                                <strike>{{ $client->name }}</strike>
                                            @else
                                                {{ $client->name }}
                                            @endif
                                        </td>
                                        <td>
                                            @include('backend.includes.partials.yn-badge', ['active' => $client->is_active])
                                        </td>
                                        <td>

                                            <div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">

                                                @can('client.show')
                                                    <a href="{{ route('admin.client.show', $client) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.view')" class="btn btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan

                                                @can('client.update')
                                                    <a href="{{ route('admin.client.edit', $client) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.edit')" class="btn btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('client.delete')
                                                    <a href="{{ route('admin.client.destroy', $client) }}"
                                                       data-method="delete"
                                                       data-trans-button-cancel="@lang('buttons.general.cancel')"
                                                       data-trans-button-confirm="@lang('buttons.general.crud.delete')"
                                                       data-trans-title="@lang('strings.backend.general.are_you_sure')"
                                                       class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.delete')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                @endcan

                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $clients->links() }}

                </div>
            </div>

        </div>
    </div>    

@endsection
