@php
    $user = auth('admin')->user();
@endphp


@extends('templates.admin')



@push('meta')
    <title> Support Tickets </title>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css?ver=' . config('app.version')) }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/css/datatables.min.css?ver=' . config('app.version')) }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/css/buttons.dataTables.min.css?ver=' . config('app.version')) }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/css/dataTables.fontAwesome.css?ver=' . config('app.version')) }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/css/select2.min.css?ver=' . config('app.version')) }}" />
    <style>
        td {
            vertical-align: middle !important;
        }

        .dt-button.buttons-columnVisibility.active {
            background: #0C0 !important;
            background-color: #0C0 !important;
        }

        .modal input {
            border: 1px solid #999 !important;
        }

        .select2-selection__choice {
            background-color: #f0f4ff !important;
            border: 1px solid #b3c7ff !important;
            color: #1a2b6d !important;
            font-size: 0.9rem !important;
            padding: 3px 8px !important;
            border-radius: 0.25rem !important;
        }

        /* Tabs container */
    .nav-tabs {
      border-bottom: none;
      background: #f8f9fa;
      border-radius: 8px;
      padding: 5px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Individual tab */
    .nav-tabs .nav-link {
      border: none;
      margin: 0 5px;
      border-radius: 6px;
      color: #333;
      background-color: transparent;
      transition: all 0.3s ease;
      padding: 10px 20px;
      font-weight: 500;
      font-weight: bold;
    }

    /* Active tab */
    .nav-tabs .nav-link.active {
      background-color: var(--gtheme-color);
      color: #fff !important;
      box-shadow: var(--gtheme-color2);
    }

    /* Hover */
    .nav-tabs .nav-link:hover {
      background-color: #e2e6ea;
      color: #007bff;
    }

    /* Tab content */
    .tab-content {
      margin-top: 20px;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      border: 1px solid #dee2e6;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    /* Hide and show tab content */
    .tab-pane { display: none; }
    .tab-pane.active { display: block; }

    /* Badge styling */
    .badge {
      font-size: 0.8rem;
      padding: 4px 8px;
      vertical-align: middle;
    }
    </style>
@endpush



@section('content')
    <!-- Item Section -->
    <section>
        <div class="container-fluid px-3 py-3 bg-white my_list_cod" style="">




            <ul class="nav nav-tabs" id="customTabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#" data-target="#tickets">
                        Tickets @if($tcount) <span class="badge badge-warning" id="ticket-count">{{$tcount}}</span> @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-target="#send">
                        Send Message
                    </a>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <div class="tab-pane active" id="tickets">
                    <h4>Tickets</h4>
                    <div class="table-responsiven" id="user_area">
                        <table id="myTable" class="table  table-striped table-bordered nlead_table" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="px-4 py-2">ID</th>
                                    <th class="px-4 py-2">Customer</th>
                                    <th class="px-4 py-2">Subject</th>
                                    <th class="px-4 py-2">Updated At</th>
                                    {{-- <th class="px-4 py-2">Actions</th> --}}
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="send">
                    <h4>Send Message</h4>
                    <div class="card">
                        <div class="card-body">
                            {{-- <h4 class="mb-3">Send Email</h4> --}}

                            <form id="adminEmailForm" autocomplete="off" onsubmit="sendMessage(event)">
                                <!-- Send Type -->
                                <div class="mb-3">
                                    <label for="send_type" class="form-label">Send To</label>
                                    <select class="form-select" id="send_type" name="send_type">
                                        <option selected value="all">All Users</option>
                                        <option value="selected">Selected Users</option>
                                    </select>
                                </div>

                                <!-- Select Users (Hidden by default) -->
                                <div class="mb-3 d-none" id="user_select_wrapper">
                                    <label for="email_users" class="form-label">Select Users</label>
                                    <select class="form-select" id="email_users" name="email_users[]" multiple="multiple"
                                        style="width: 100%"></select>
                                </div>

                                <!-- Subject -->
                                <div class="mb-3">
                                    <label for="email_subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" name="email_subject" id="email_subject"
                                        required>
                                </div>

                                <!-- Message -->
                                <div class="mb-3">
                                    <label for="email_message" class="form-label">Message</label>
                                    <textarea class="form-control" name="email_message" id="email_message" rows="6" required></textarea>
                                </div>

                                <!-- Submit -->
                                <div class="text-right sbutton">
                                    <button type="submit" class="btn btn-success">Send Email</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
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
@endsection('content')



@push('js')
    <script>
        const USER_DATA = {!! json_encode($users) !!};
    </script>

    <script src="{{ asset('admin-assets/js/datatables.min.js?ver=' . config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/js/dataTables.buttons.min.js?ver=' . config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/js/buttons.html5.min.js?ver=' . config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/js/buttons.colVis.min.js?ver=' . config('app.version')) }}"></script>

    <script src="{{ asset('admin-assets/js/select2.min.js?ver=' . config('app.version')) }}"></script>

    <script src="{{ asset('admin-assets/ckeditor/ckeditor.js') }}"></script>


    <script src="{{ asset('admin-assets/js/tickets.js?ver=' . config('app.version')) }}"></script>
@endpush
