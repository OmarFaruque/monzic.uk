@php
    $user = Auth::user();
@endphp
@extends('templates.admin')



@push('meta')
    <title>Page Settings </title>
@endpush



@section('content')
    <h2>Page Settings</h2>



    <section>






        <div class="container mt-5">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="certificate-tab" data-toggle="tab" href="#certificate"
                        role="tab">Certificate PDF</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="email-tab" data-toggle="tab" href="#email" role="tab">Confirmation Email</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="expr_email-tab" data-toggle="tab" href="#expr_email_con" role="tab">Order Expires Email</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="canc_email-tab" data-toggle="tab" href="#canc_email_con" role="tab">Order Cancellation Email</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="privacy-tab" data-toggle="tab" href="#privacy" role="tab">Privacy Policy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="terms-tab" data-toggle="tab" href="#terms" role="tab">Customer Terms of
                        Business</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="cookies-tab" data-toggle="tab" href="#cookies" role="tab">Cookies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="website-tab" data-toggle="tab" href="#website" role="tab">Website Terms of
                        Use</a>
                </li>
            </ul>

            <div class="tab-content mt-3" id="myTabContent">
                <div class="tab-pane fade show active" id="certificate" role="tabpanel">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Certificate PDF</div>
                        <div class="card-body">
                            <form class="is_ckedit" autocomplete="off" onsubmit="updateSettings(event, 10)">
                                <textarea name="page[cert_pdf]" class="form-control" required>{!! @$page['cert_pdf'] !!}</textarea>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade" id="email" role="tabpanel">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Confirmation Email</div>
                        <div class="card-body">
                            <form class="is_ckedit" autocomplete="off" onsubmit="updateSettings(event, 10)">
                                <textarea name="page[conf_email]" class="form-control" required>{!! @$page['conf_email'] !!}</textarea>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="expr_email_con" role="tabpanel">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Order Expires Email</div>
                        <div class="card-body">
                            <form class="is_ckedit" autocomplete="off" onsubmit="updateSettings(event, 10)">
                                <textarea name="page[expr_email]" class="form-control" required>{!! @$page['expr_email'] !!}</textarea>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade" id="canc_email_con" role="tabpanel">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Order Cancellation Email</div>
                        <div class="card-body">
                            <form class="is_ckedit" autocomplete="off" onsubmit="updateSettings(event, 10)">
                                <textarea name="page[canc_email]" class="form-control" required>{!! @$page['canc_email'] !!}</textarea>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade" id="privacy" role="tabpanel">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Privacy Policy</div>
                        <div class="card-body">
                            <form class="is_ckedit" autocomplete="off" onsubmit="updateSettings(event, 10)">
                                <textarea name="page[privacy_policy]" class="form-control" required>{!! @$page['privacy_policy'] !!}</textarea>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade" id="terms" role="tabpanel">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Customer Terms of Business</div>
                        <div class="card-body">
                            <form class="is_ckedit" autocomplete="off" onsubmit="updateSettings(event, 10)">
                                <textarea name="page[term_business]" class="form-control" required>{!! @$page['term_business'] !!}</textarea>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade" id="cookies" role="tabpanel">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Cookies</div>
                        <div class="card-body">
                            <form class="is_ckedit" autocomplete="off" onsubmit="updateSettings(event, 10)">
                                <textarea name="page[cookies_page]" class="form-control" required>{!! @$page['cookies_page'] !!}</textarea>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="website" role="tabpanel">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Terms of use</div>
                        <div class="card-body">
                            <form class="is_ckedit" autocomplete="off" onsubmit="updateSettings(event, 10)">
                                <textarea name="page[term_use]" class="form-control" required>{!! @$page['term_use'] !!}</textarea>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </section>




    <!--  Modal  ALert -->
    <div class="modal" id="modal_confirm">
        <div class="modal-dialog">
            <div class="modal-content bg-warning">
                <div style="text-align:right"> <button type="button" class="close"
                        data-bs-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body py-5 text-center" style="font-size:18px; color:#000">

                </div>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="updateSettings(event, 1)" type="button" class="btn  btn-success"
                        data-bs-dismiss="modal">Confirm Action</button>
                </div>

            </div>
        </div>
    </div>
@endsection('content')



@push('js')
    <script src="{{ asset('/admin-assets/js/settings.js?ver=' . config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/ckeditor/ckeditor.js') }}"></script>

    <script>
        $(document).ready(function() {

            CKEDITOR.config.height = 500;
            CKEDITOR.config.width = 'auto';

            $("textarea").each(function() {
                let this_id = $(this).prop('name').replace('page[', '').replace(']', '');
                $(this).prop('id', this_id);
                CKEDITOR.replace(this_id);
            });


        });

        $(document).ready(function() {
            $('#myTab .nav-link').on('click', function(e) {
                e.preventDefault();

                // Remove active classes from all tabs and tab content
                $('.nav-link').removeClass('active');
                $('.tab-pane').removeClass('show active');

                // Add active class to clicked tab
                $(this).addClass('active');

                // Get the target content ID from href
                var target = $(this).attr('href');

                // Show the corresponding tab pane
                $(target).addClass('show active');
            });
        });
    </script>
@endpush
