@php

    $title = $subject;

@endphp


@extends('emails.templates.base')

@push('css')
@endpush

@section('content')

<div class="content left">

{!! $email_message !!}

</div>

@endsection
