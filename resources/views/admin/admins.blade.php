@php
$user = auth('admin')->user();  

$adminRoles = array_flip(auth('admin')->user()->adminRoles());
@endphp


@extends('templates.admin')



@push('meta')
    <title> Admins </title>

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
    </style>
@endpush



@section('content')

    
    <!-- Item Section -->
<section>
    <div class="container-fluid px-3 my-3 bg-white my_list_cod" style="">
<h1>Admins</h1>
        <div class="table-responsiven" id="user_area">
            <table id="myTable" class="table  table-striped table-bordered nlead_table" style="width:100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>User Right</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th class=""></th>
                        <th class="sch"></th>
                        <th class="sch"></th>
                        <th class="sch"></th>
                        <th class="sch"></th>
                        <th class="sch_sel">
                            <select id="rolex" style="background-color: #FFF; border: 1px solid #CCC; max-width:200px; height:30px">
                                <option value=""></option>
                                @foreach ($adminRoles as $key => $value)
                                    <option value="{{ $key  }}">{{ $value  }}</option>
                                @endforeach
                            </select>
                        </th>
                        <th></th>
                    </tr>
                </thead>
            </table>

        </div>


    </div>
</section>



<div class="modal" id="modal_entry" data-backdrop="static" data-keyboard="false">
    <form action=""onsubmit="update_entry(event)">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="">
                <div class="modal-header">
                    <h4>Update Entry</h4>
                    <div style="text-align:right"> <button data-bs-dismiss="modal" type="button"
                            class="close">&times;</button></div>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6 form-group">
                            <input id="entry_id" name="e_id" type="hidden">
                            <label>First name </label>
                            <input id="fname" name="fname" required class="form-control">
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label>Last name </label>
                            <input id="lname" name="lname" required class="form-control">
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label>Email address </label>
                            <input id="email" type="email" required name="email" class="form-control">
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label>Phone number </label>
                            <input id="phone" name="phone" class="form-control">
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label>User Right</label>
                            <select id="role" name="role" required class="form-control">
                                <option></option>
                                @foreach ($adminRoles as $key => $value)
                                    <option value="{{ $key  }}">{{ $value  }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label>Password </label>
                            <input id="password" name="password" required minlength="6" type="password"
                                class="form-control">
                        </div>
                        
                    
                    </div>

                    <p id="errmsg"></p>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-between" id="sbutton">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" style="font-weight:bold" type="submit"> Update</button>
                </div>

            </div>
        </div>
    </form>
</div>


<!--  Modal  ALert -->
<div class="modal" id="modal_delete">
    <div class="modal-dialog">
        <div class="modal-content bg-warning">
            <div style="text-align:right"> <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
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

<script>
    let glob_roles = {!! json_encode($adminRoles) !!} ;
    </script>
    

<script src="{{ asset('admin-assets/js/datatables.min.js?ver='.config('app.version')) }}"></script>
<script src="{{ asset('admin-assets/js/dataTables.buttons.min.js?ver='.config('app.version')) }}"></script>
<script src="{{ asset('admin-assets/js/buttons.html5.min.js?ver='.config('app.version')) }}"></script>
<script src="{{ asset('admin-assets/js/buttons.colVis.min.js?ver='.config('app.version')) }}"></script>
<script src="{{ asset('admin-assets/js/selectize.min.js?ver='.config('app.version')) }}"></script>

<script src="{{ asset('admin-assets/js/admin.js?ver='.config('app.version')) }}"></script>



@endpush


