@php
    $user = auth('admin')->user();
@endphp

@extends('templates.admin')

@push('meta')
    <title>Refund Requests</title>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css?ver='.config('app.version')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/datatables.min.css?ver='.config('app.version')) }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/buttons.dataTables.min.css?ver='.config('app.version')) }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/dataTables.fontAwesome.css?ver='.config('app.version')) }}" />
@endpush

@section('content')
    <section>
        <div class="container-fluid px-3 py-3 bg-white my_list_cod">
            <div class="table-responsiven" id="refund_event_area">
                <table id="myTable" class="table table-striped table-bordered nlead_table" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th class="sch"></th>
                            <th class="sch"></th>
                            <th class="sch"></th>
                            <th class="sch"></th>
                            <th class="sch"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
    <div class="modal" id="refund_blacklist_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" style="max-width: 500px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Blacklist customer</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Choose what you want to block:</p>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="block_email" checked>
                        <label class="form-check-label" for="block_email">Email</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="block_address">
                        <label class="form-check-label" for="block_address">Address</label>
                    </div>
                    <input type="hidden" id="refund_blacklist_quote_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="submitBlacklistSelection()">Block</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('admin-assets/js/datatables.min.js?ver='.config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/js/dataTables.buttons.min.js?ver='.config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/js/buttons.html5.min.js?ver='.config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/js/buttons.colVis.min.js?ver='.config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/js/refund-events.js?ver='.config('app.version')) }}"></script>
@endpush