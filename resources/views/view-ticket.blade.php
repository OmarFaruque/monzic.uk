@php
    $user = auth('web')->user();
@endphp
@extends('templates.page')


@push('meta')
    @if ($emailPassed && $ticket != null)
        <title>Ticket #{{ $ticket->ticket_id }} - {{ $ticket->subject }}</title>
    @else
        <title>Ticket not found or link broken</title>
    @endif
@endpush

@push('css')
    {{-- Extra css files here --}}

    {{-- Login Reg --}}
    <link rel='stylesheet' id='elementor-post-1306-css'
        href='/uploads/elementor/css/post-1306b08c.css?ver={{ config('app.version') }}' type='text/css' media='all' />
    <link rel="stylesheet"
        href="/plugins/elementor-pro/assets/css/widget-woocommerce.min.css?ver={{ config('app.version') }}">

    <link rel='stylesheet' href='/css/zebra_datepicker.css?ver={{ config('app.version') }}' type='text/css' />


    <style>

    </style>
@endpush



@section('content')
    <div class="container py-4">

        @if ($emailPassed && $ticket != null)
            {{-- Ticket Header --}}
            <div class="mb-4 border-bottom pb-2">
                <h4 class="fw-bold">{{ $ticket->subject }} - #{{ $ticket->ticket_id }}</h4>
                <p class="text-muted mb-0">Opened on: {{ $ticket->created_at->format('F j, Y \a\t g:i A') }}</p>
            </div>

            {{-- Message Thread --}}
            <div class="mb-5 message_cont">
                @foreach ($ticket->messages as $message)
                    <div
                        class="mb-3 p-3 rounded border 
                {{ $message->is_admin ? 'bg-light border-primary' : 'bg-white border-secondary' }}">

                        <div class="mb-1 small fw-bold">
                            {{ $message->is_admin ? 'Admin' : $ticket->first_name . ' ' . $ticket->last_name }}
                            <span class="text-muted fw-normal ms-2">
                                {{ $message->created_at->format('M d, Y g:i A') }}
                            </span>
                        </div>

                        <div class="text-body">
                            {!! $message->message !!}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Reply Form --}}
            @if (!$ticket->is_closed)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Reply to this ticket</h5>
                        <input type="hidden" id="user_name" value="{{$ticket->first_name . ' ' . $ticket->last_name }}">
                        <form method="POST" action="" onsubmit="contactReply(event)">
                            @csrf
                            
                            <input type="hidden" name="token" value="{{$ticket->token}}">
                            <input type="hidden" name="email" value="{{$ticket->email}}">
                            <div class="mb-3">
                                <textarea required id="message" name="message" class="form-control" rows="5" placeholder="Write your reply here...">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="sbutton"><button type="submit" class="btn btn-primary">Send Reply</button></div>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mt-4">
                    This ticket has been closed. You can no longer send replies.
                </div>
            @endif
        @else
            <div class="py-5  alert alert-warning">Ticket not found or the access link is broken</div>
        @endif

    </div>
@endsection

@push('js')
    {{-- Optional JS if needed --}}
@endpush
