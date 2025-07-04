@php
    $title = $emailTitle ?? 'Message from ' . config('app.name');
    $type = $emailType ?? 'receipt'; // 'receipt', 'user_reply', 'admin_reply'
@endphp

@extends('emails.templates.base')

@push('css')
    {{-- Email clients often block external styles; use inline where possible --}}
@endpush

@section('content')
    <div class="content left" style="font-family: Arial, sans-serif; line-height: 1.5;">

        {{-- Intro Section --}}
        @if ($type === 'receipt')
            <p style="color: #333;">
                🧾 <strong>Thank you!</strong> We’ve received your message. Our support team will get back to you shortly.
            </p>
        @elseif ($type === 'user_reply')
            <p style="color: #333;">
                👤 <strong>Your reply has been received.</strong>
            </p>
        @elseif ($type === 'admin_reply')
            <p style="color: #333;">
                🛠️ <strong>You’ve got a new reply from our support team.</strong> Please see the message below.
            </p>
        @endif

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ddd;">

        {{-- Main Message --}}
        <div style="background-color: #f8f8f8; padding: 15px; border-radius: 6px; color: #222;">
            {!! $emailBody ?? 'No content provided.' !!}
        </div>

        {{-- Call to action --}}
        @if (!empty($ticketLink))
            <p style="margin-top: 20px;">
                <a href="{{ $ticketLink }}"
                style="color: #FFF;" class="btn">
                    View Your Ticket
                </a>
            </p>
        @endif

    </div>
@endsection
