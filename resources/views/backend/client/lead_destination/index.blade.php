@extends('backend.layouts.app')

@section('title', $client->name . ': Lead Destinations')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.lead_destination.components.tabbox', ['client' => $client, 'lead_destinations' => $lead_destinations, 'destination_config_type_classnames' => $destination_config_type_classnames])

        <p class="mb-0">Please select a lead destination to the left.</p>

    @endcomponent

@endsection
