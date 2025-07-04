@php
    // $user = auth('user')->user();
@endphp
@extends('templates.page')


@push('meta')
    <title>Payment Confirmation - {{ config('app.name') }}</title>
@endpush

@push('css')
    {{-- Extra css files here --}}
    <style>

    </style>
@endpush






@section('content')
    <div class="container">


        {!! $html !!}


    </div>
@endsection()
