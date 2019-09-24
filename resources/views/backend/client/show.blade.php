@extends('backend.layouts.app')

@section('title', 'View Client')

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
                            <small class="text-muted">View Client</small>
                        </h4>
                    </div>

                    <div class="col-sm-7 pull-right">
                        
                        <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                            @can('client.edit', $client)
                                <a href="{{ route('admin.client.edit', $client) }}" class="btn btn-success ml-1" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                            @endcan
                        </div>

                    </div>

                </div>

                <hr>

                <div class="row mt-4">
                    <div class="col">

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
                                            @if ($client->is_active)
                                                <span class="badge badge-success">@lang('labels.general.yes')</span>
                                            @else
                                                <span class="badge badge-danger">@lang('labels.general.no')</span>
                                            @endif
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
                </div>

            </div> <!-- .card-body -->

            <div class="card-footer">
                <div class="row">
                    <div class="col">
                        <small class="float-right text-muted">
                            <strong>Created At:</strong> {{ timezone()->convertToLocal($client->created_at) }} ({{ $client->created_at->diffForHumans() }}),
                            <strong>Last Updated:</strong> {{ timezone()->convertToLocal($client->updated_at) }} ({{ $client->updated_at->diffForHumans() }})
                            @if($client->trashed())
                                <strong>Deleted At:</strong> {{ timezone()->convertToLocal($client->deleted_at) }} ({{ $client->deleted_at->diffForHumans() }})
                            @endif
                        </small>
                    </div><!--col-->
                </div><!--row-->
            </div><!--card-footer-->

        </div> <!-- .card -->

    {{ html()->form()->close() }}
    
@endsection
