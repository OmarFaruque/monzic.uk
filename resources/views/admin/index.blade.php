@php
    $user = auth('admin')->user();
@endphp
@extends('templates.admin')



@push('meta')
    <title>DashBoard - {{ config('app.name') }}</title>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css?ver='.config('app.version')) }}" />
@endpush




@section('content')
    <div class="container-fluid">

        
    </div>
@endsection('content')



@push('js')


@endpush
