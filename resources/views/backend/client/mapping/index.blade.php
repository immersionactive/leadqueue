@extends('backend.layouts.app')

@section('title', $client->name . ': Mappings')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.mapping.components.tabbox', ['client' => $client, 'mappings' => $mappings])

        <p class="mb-0">Please select a mapping to the left.</p>

    @endcomponent
    
@endsection
