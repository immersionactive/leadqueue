@extends('backend.layouts.app')

@section('title', $client ? 'Edit Client: ' . $client->name : 'Create Client')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    
@endsection
