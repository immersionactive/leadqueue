@extends('backend.layouts.app')

@section('title', 'View Client')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    <div class="card">

        <div class="card-body">

            <h4 class="card-title mb-0">
                {{ $client->name }}
            </h4>

            <div class="row mt-4">
                <div class="col">

                    @include('backend.client.includes.tabs', ['active_tab' => 'overview', 'client' => $client])

                    <div class="tab-content">
                        <div class="tab-pane active" role="tabpanel" aria-expanded="true">

                            <div class="row mb-4">
                                <div class="col-md-12 pull-right">
                                    <div class="btn-group float-right" role="group" aria-label="TODO">

                                        @can('client.edit')
                                             <a href="{{ route('admin.client.edit', [$client]) }}" class="btn btn-success" data-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('client.destroy')
                                            <a href="{{ route('admin.client.destroy', [$client]) }}"
                                               data-method="post"
                                               data-trans-button-cancel="@lang('buttons.general.cancel')"
                                               data-trans-button-confirm="@lang('buttons.general.crud.delete')"
                                               data-trans-title="@lang('strings.backend.general.are_you_sure')"
                                               class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.delete')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endcan

                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tbody>

                                        {{-- Name --}}

                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $client->name }}</td>
                                        </tr>

                                        {{-- Active --}}

                                        <tr>
                                            <th>Active</th>
                                            <td>
                                                @include('backend.includes.partials.yn-badge', ['active' => $client->is_active])
                                            </td>
                                        </tr>

                                        {{-- Notes --}}

                                        <tr>
                                            <th>Notes</th>
                                            <td>{!! nl2br(e($client->notes)) !!}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div> <!-- .tab-content -->

                </div>
            </div>

        </div> <!-- .card-body -->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <small class="float-right text-muted">
                        <strong>Created At:</strong> {{ timezone()->convertToLocal($client->created_at) }} ({{ $client->created_at->diffForHumans() }}),
                        <strong>Last Updated:</strong> {{ timezone()->convertToLocal($client->updated_at) }} ({{ $client->updated_at->diffForHumans() }})
                    </small>
                </div>
            </div>
        </div> <!--. card-footer -->

    </div> <!-- .card -->
    
@endsection
