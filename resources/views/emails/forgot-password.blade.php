@php

    $title = 'Reset your password -  ' . config('app.name');

@endphp


@extends('emails.templates.base')

@push('css')
@endpush

@section('content')
    @if ($userType == 'user')
        <div class="content left">
            <h1 class="center">Reset your Password</h1>
            <div class="left">
                <p>Click the link below in order to reset your password.</p>
                <div class="center"><a href="{{ url('/reset-password/' . $token) }}" class="button">Reset your
                        Password</a><br>This link will expire within two hours of receiving this message.</div>

                <p>If you did not sign up for a {{config('app.name')}} account, please ignore this email.</p>
                <p>Best regards,<br>{{config('app.name')}} Team</p>
            </div>
        </div>
    @else
        <div class="content left">
            <h1 class="center">Reset your Password</h1>
            <div class="left">
                <p>Click the link below in order to reset your password.</p>
                <div class="center"><a href="{{ url('/admin/reset-password/' . $token) }}" class="button">Reset your
                        Password</a><br>This link will expire within two hours of receiving this message.</div>

                <p>If you did not sign up for a {{config('app.name')}} account, please ignore this email.</p>
                <p>Best regards,<br>{{config('app.name')}} Team</p>
            </div>
    @endif
@endsection
