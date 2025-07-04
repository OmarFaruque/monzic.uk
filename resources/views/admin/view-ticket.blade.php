@php
    $user = auth('admin')->user();
@endphp


@extends('templates.admin')



@push('meta')

    @if ($ticket != null)
        <title>Ticket #{{ $ticket->ticket_id }} - {{ $ticket->subject }}</title>
    @else
        <title>Ticket not found or link broken</title>
    @endif

@endpush

@push('css')
@endpush



@section('content')
    <!-- Item Section -->
    <section>
        <div class="container-fluid px-3 px-md-5 py-3 mb-5 bg-white my_list_cod" style="">


            @if ($ticket != null)
                {{-- Ticket Header --}}
                <div class="mb-4 border-bottom pb-2">
                    <h4 class="fw-bold">{{ $ticket->subject }} - #{{ $ticket->ticket_id }}</h4>
                    <p class="text-muted mb-1">Opened on: {{ $ticket->created_at->format('F j, Y \a\t g:i A') }}</p>

                    <div class="d-flex align-items-center w-100 mt-2" id="st_button">
                        {{-- Status Badge --}}
                        <span class="badge {{ $ticket->is_closed ? 'bg-secondary' : 'bg-success' }} me-2">
                            {{ $ticket->is_closed ? 'Closed' : 'Open' }}
                        </span>
                    
                            <button onclick="updateTicketState(event, {{ $ticket->is_closed ? '0' : '1' }}, 0)" type="submit" class="ml-2 btn btn-sm {{ $ticket->is_closed ? 'btn-success' : 'btn-warning' }}">
                                {{ $ticket->is_closed ? 'Reopen Ticket' : 'Close Ticket' }}
                            </button>
                            <div class="ml-auto">
                            <button onclick="deleteTicket(event, 0)" type="submit" class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                            </div>
                    </div>
                    
                </div>

                {{-- Ticket Info --}}
                <div class="row mb-4">
                    {{-- Customer Details --}}
                    <div class="col-md-6 mb-3">
                        <h5 class="fw-semibold">Customer Details</h5>
                        <ul class="list-unstyled mb-0">
                            <li><strong>Name:</strong> {{ $ticket->first_name }} {{ $ticket->last_name }}</li>
                            <li><strong>Email:</strong> <a href="mailto:{{ $ticket->email }}">{{ $ticket->email }}</a>
                            </li>
                            <li><strong>Phone:</strong> {{ $ticket->phone ?? '-' }}</li>
                        </ul>
                    </div>

                    {{-- Ticket Details --}}
                    <div class="col-md-6 mb-3">
                        <h5 class="fw-semibold">Ticket Details</h5>
                        <ul class="list-unstyled mb-0">
                            <li><strong>Ticket ID:</strong> #{{ $ticket->ticket_id }}</li>
                            @if($ticket->policy_number != null)
                            <li><strong>Order Number:</strong> {{ $ticket->policy_number ?? '-' }}</li>
                            @endif
                            <li><strong>Status:</strong> {{ $ticket->is_closed ? 'Closed' : 'Open' }}</li>
                            <li><strong>Last Updated:</strong> {{ $ticket->updated_at->format('F j, Y g:i A') }}</li>
                        </ul>
                    </div>
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
                           
                            <form  onsubmit="contactReply(event)">
                           
                                <input type="hidden" name="ticket_id" value="{{ $ticket->ticket_id }}">
                                <div class="mb-3">
                                    <textarea required id="message" name="message" class="form-control" rows="5"
                                        placeholder="Write your reply here...">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="sbutton"><button type="submit" class="btn btn-primary">Send Reply</button>
                                </div>
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
    </section>




    
    <!--  Modal  ALert -->
    <div class="modal" id="modal_delete">
        <div class="modal-dialog">
            <div class="modal-content bg-warning">
                <div style="text-align:right"> <button type="button" class="close"
                        data-bs-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body py-5" style="font-size:16px; color:#000">
                    Do you want to delete this project?
                </div>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn  btn-danger delete_action_btn">Delete</button>
                </div>

            </div>
        </div>
    </div>



@endsection('content')



@push('js')
<script>
    const TICKET_ID = "{{ $ticket->ticket_id }}";
</script>
    <script src="{{ asset('admin-assets/js/tickets.js?ver=' . config('app.version')) }}"></script>
@endpush
