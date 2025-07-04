@php

$title = "Verify your Email at  ".config('app.name');

@endphp


@extends('emails.templates.base')

@push('css')

@endpush

@section('content')


<div class="content left">
    <h1 class="center">Verify Your Email Address</h1>
    <div class="left">
        <p>Your email verification code is :</p>

        <div class="center">
            <span class="code">{{$code}}</span>
            <br><br><small>This link will expire within two hours of receiving this message.</small></div>
      
            <p>If you did not sign up for a {{config('app.name')}} account, please ignore this email.</p>
            <p>Best regards,<br>{{config('app.name')}} Team</p>
    </div>
</div>



@endsection