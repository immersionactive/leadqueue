@extends('backend.layouts.app')

@section('title', $client->name . ': Lead Sources')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.lead_source.components.tabbox', ['client' => $client, 'lead_sources' => $lead_sources, 'source_config_type_classnames' => $source_config_type_classnames])

        <p class="mb-0">Please select a lead source to the left.</p>

    @endcomponent
    
@endsection
