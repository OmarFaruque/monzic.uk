@php

$title = "Order confirmation - {{$quote->policy_number}}";

@endphp


@extends('emails.templates.base')

@push('css')

@endpush

@section('content')


<div class="content left">



{!!  $pageContent  !!}



</div>

@endsection
