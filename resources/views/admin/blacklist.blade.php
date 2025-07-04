@php
    $user = auth('admin')->user();
@endphp


@extends('templates.admin')



@push('meta')
    <title> Blacklist </title>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css?ver='.config('app.version')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/datatables.min.css?ver='.config('app.version')) }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/buttons.dataTables.min.css?ver='.config('app.version')) }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/dataTables.fontAwesome.css?ver='.config('app.version')) }}" />
    <style>
        td {
            vertical-align: middle !important;
        }

        .dt-button.buttons-columnVisibility.active {
            background: #0C0 !important;
            background-color: #0C0 !important;
        }
        .match_tab input{
            border: 1px solid #999 !important;
        }
    </style>
@endpush



@section('content')
    <!-- Item Section -->
    <section>
        <div class="container-fluid px-3 py-3 bg-white my_list_cod" style="">

            <div class="table-responsiven" id="user_area">
                <table id="myTable" class="table  table-striped table-bordered nlead_table" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Matches</th>
                            <th>Total hit</th>
                            <th>Created at</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>

            </div>


        </div>
    </section>


    <div class="modal" id="modal_entry" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" style="width:100%; max-width:600px">
            <div class="modal-content" style="">
                <form onsubmit="update_entry(event)">
                    <div class="modal-header">
                        <h4>Update Entry</h4>
                        <div style="text-align:right"> <button data-bs-dismiss="modal" type="button"
                                class="close">&times;</button></div>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="_method" id="_method">
                        <div class="row">
                            <div class="col-12 mt-3 form-group">
                                <label>Matches:</label>
                                <div>Note: Any combination will be an AND Operation.</div>
                                <table class="table match_tab">
                                    <tbody>
                                        <tr><th><small>Last Name:</small></th><td><input class="form-control" name="last_name" id="last_name"></td></tr>
                                        <tr><th><small>First Name:</small></th><td><input class="form-control" name="first_name" id="first_name"></td></tr>
                                        <tr><th><small>Email:</small></th><td><input class="form-control" name="email" id="email"></td></tr>
                                        <tr><th><small>Date of Birth:</small></th><td><input placeholder="YYYY or YYYY-mm or YYYY-mm-dd" class="form-control" name="birth_date" id="birth_date" ></td></tr>
                                        <tr><th colspan="2"><small>Registrations (Comma separeted):</small><input placeholder="GL69 RZB, GL88 RZB" class="form-control" name="registrations" id="registrations" ></th></tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <p id="errmsg"></p>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-between" id="sbutton">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-main px-3"> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


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
                    <button type="button" class="btn  btn-danger delete_action_btn" data-bs-dismiss="modal">Delete</button>
                </div>

            </div>
        </div>
    </div>
@endsection('content')



@push('js')
    <script src="{{ asset('admin-assets/js/datatables.min.js?ver='.config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/js/dataTables.buttons.min.js?ver='.config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/js/buttons.html5.min.js?ver='.config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/js/buttons.colVis.min.js?ver='.config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/js/selectize.min.js?ver='.config('app.version')) }}"></script>


    <script src="{{ asset('admin-assets/js/blacklists.js?ver='.config('app.version')) }}"></script>
@endpush
