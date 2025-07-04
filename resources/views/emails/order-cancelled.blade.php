@php

$title = config('app.name')." - Order Cancelled - {{$quote->policy_number}}";

@endphp


@extends('emails.templates.base')

@push('css')

@endpush

@section('content')


<div class="content left">


    {!! $pageContent !!}





</div>

@endsection
