@php
    $user = auth('admin')->user();

@endphp


@extends('templates.admin')



@push('meta')
    <title> Users </title>
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
        .user_count{
            display: inline-block;
            vertical-align: middle;
        }
        .user_count > span{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            font-size: 14px;
            height: 40px;
            border-radius: 50%;
            background-color: #000;
            color: #FFF;
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
                            <th></th>
                            <th>Email</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Signup Date</th>
                        </tr>
                        <tr>
                            <th></th>
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



    <div class="modal" id="modal_alogin">
        <div class="modal-dialog">
            <div class="modal-content bg-primary">
                <div style="text-align:right"> <button type="button" class="close"
                        data-bs-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body py-5" style="font-size:16px; color:#000">
               
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
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

    <script src="{{ asset('admin-assets/js/users.js?ver='.config('app.version')) }}?v=enxforcupdate"></script>

    @endpush
