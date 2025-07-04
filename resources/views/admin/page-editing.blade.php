@php
    $user = Auth::user();
@endphp
@extends('templates.admin')



@push('meta')
    <title>Page Editing </title>
@endpush



@section('content')
    


    <style>
        .page_elm {
            margin-bottom: 20px;
        }
        details{
            padding: 10px; font-size:18px; border:2px solid #999;
        }
    </style>

    <section>
        <div class="container py-2 sform text-left">
            <h2>Page Editing</h2>
            <form class="py-4" onsubmit="updateSettings(event, 11)" autocomplete="off" >
            <div class="row">


                <div class="col-12 page_elm">
                    <details>
                        <summary style="padding: 10px; font-size:18px; border:2px solid #999">HOME PAGE TITLE AREA
                        </summary>
                        <div class="row">

                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>HOME PAGE: GET A PRICE</label>
                                <input class="form-control" name="pags[home_get_price]"
                                    value="{{ @$pags['home_get_price'] }}" required>
                            </div>

                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>HOME PAGE: SUBMIT</label>
                                <input class="form-control" name="pags[home_submit]" value="{{ @$pags['home_submit'] }}"
                                    required>
                            </div>


                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>HOME PAGE: TITLE TEXT BOX - TOP TEXT</label>
                                <input class="form-control" name="pags[home_title_top_text]"
                                    value="{{ @$pags['home_title_top_text'] }}" required>
                            </div>

                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>HOME PAGE: TITLE TEXT BOX - BOTTOM TEXT</label>
                                <input class="form-control" name="pags[home_title_bottom_text]"
                                    value="{{ @$pags['home_title_bottom_text'] }}" required>
                            </div>

                        </div>
                    </details>
                </div>


                <div class="col-12 page_elm">
                    <details>
                        <summary style="padding: 10px; font-size:18px; border:2px solid #999">HOME PAGE SECOND BOX
                        </summary>
                        <div class="row">

                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>BOX HEADER TEXT</label>
                                <input class="form-control" name="pags[home_second_box_header]"
                                    value="{{ @$pags['home_second_box_header'] }}" required>
                            </div>

                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>BOX PARAGRAPH TEXT</label>
                                <input class="form-control" name="pags[home_second_box_text]"
                                    value="{{ @$pags['home_second_box_text'] }}" required>
                            </div>

                            <div class="col-12 col-md-12 page_elm form-group">
                                <label>BOX BULLET POINTS. (In new line)</label>
                                <textarea class="form-control" name="pags[home_second_box_bullet]" required>{!! @$pags['home_second_box_bullet'] !!}</textarea>
                            </div>

                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>GET STARTED BUTTON TEXT</label>
                                <input class="form-control" name="pags[home_second_box_btn]"
                                    value="{{ @$pags['home_second_box_btn'] }}" required>
                            </div>


                        </div>
                    </details>
                </div>




                <div class="col-12 page_elm">
                    <details>
                        <summary style="padding: 10px; font-size:18px; border:2px solid #999">CAR BOX 1
                        </summary>
                        <div class="row">

                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>BOX HEADER TEXT</label>
                                <input class="form-control" name="pags[car_box1_header]"
                                    value="{{ @$pags['car_box1_header'] }}" required>
                            </div>

                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>BOX PARAGRAPH TEXT</label>
                                <textarea class="form-control" name="pags[car_box1_text]" required>{!! @$pags['car_box1_text'] !!}</textarea>
                            </div>


                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>BUTTON TEXT</label>
                                <input class="form-control" name="pags[car_box1_btn]" value="{{ @$pags['car_box1_btn'] }}"
                                    required>
                            </div>


                        </div>
                    </details>
                </div>


                <div class="col-12 page_elm">
                    <details>
                        <summary style="padding: 10px; font-size:18px; border:2px solid #999">CAR BOX 2
                        </summary>
                        <div class="row">

                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>BOX HEADER TEXT</label>
                                <input class="form-control" name="pags[car_box2_header]"
                                    value="{{ @$pags['car_box2_header'] }}" required>
                            </div>

                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>BOX PARAGRAPH TEXT</label>
                                <textarea class="form-control" name="pags[car_box2_text]" required>{!! @$pags['car_box2_text'] !!}</textarea>
                            </div>


                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>BUTTON TEXT</label>
                                <input class="form-control" name="pags[car_box2_btn]" value="{{ @$pags['car_box2_btn'] }}"
                                    required>
                            </div>


                        </div>
                    </details>
                </div>




                <div class="col-12 page_elm">
                    <details>
                        <summary style="padding: 10px; font-size:18px; border:2px solid #999">DOCUMENT Box
                        </summary>
                        <div class="row">

                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>BOX HEADER TEXT</label>
                                <input class="form-control" name="pags[doc_header]" value="{{ @$pags['doc_header'] }}"
                                    required>
                            </div>

                            <div class="col-8 col-md-9 page_elm form-group">
                                <label>Certicate Label</label>
                                <input class="form-control" name="pags[doc_certificate]" required
                                    value="{{ @$pags['doc_certificate'] }}">
                            </div>

                            <div class="col-4 col-md-3 page_elm form-group">
                                <label>Enable Certicate</label>
                                <select class="form-control" name="pags[doc_certificate_en]" required >
                                    <option value=""></option>
                                    <option {{ (@$pags['doc_certificate_en'] == 1)?'selected':'' }}  value="1">Enabled</option>
                                    <option {{ (@$pags['doc_certificate_en'] == 0)?'selected':'' }}  value="0">Disabled</option>
                                </select>
                            </div>

                            <div class="col-8 col-md-9 page_elm form-group">
                                <label>Information Label</label>
                                <input class="form-control" name="pags[doc_information]" required
                                    value="{{ @$pags['doc_information'] }}">
                            </div>

                            <div class="col-4 col-md-3 page_elm form-group">
                                <label>Enable Information</label>
                                <select class="form-control" name="pags[doc_information_en]" required >
                                    <option value=""></option>
                                    <option {{ (@$pags['doc_information_en'] == 1)?'selected':'' }}  value="1">Enabled</option>
                                    <option {{ (@$pags['doc_information_en'] == 0)?'selected':'' }}  value="0">Disabled</option>
                                </select>
                            </div>

                            <div class="col-8 col-md-9 page_elm form-group">
                                <label>Statement Label</label>
                                <input class="form-control" name="pags[doc_statement]" required
                                    value="{{ @$pags['doc_statement'] }}">
                            </div>

                            <div class="col-4 col-md-3 page_elm form-group">
                                <label>Enable Statement</label>
                                <select class="form-control" name="pags[doc_statement_en]" required >
                                    <option value=""></option>
                                    <option {{ (@$pags['doc_statement_en'] == 1)?'selected':'' }}  value="1">Enabled</option>
                                    <option {{ (@$pags['doc_statement_en'] == 0)?'selected':'' }}  value="0">Disabled</option>
                                </select>
                            </div>

                            <div class="col-8 col-md-9 page_elm form-group">
                                <label>Schedule Label</label>
                                <input class="form-control" name="pags[doc_schedule]" required
                                    value="{{ @$pags['doc_schedule'] }}">
                            </div>

                            <div class="col-4 col-md-3 page_elm form-group">
                                <label>Enable Schedule</label>
                                <select class="form-control" name="pags[doc_schedule_en]" required >
                                    <option value=""></option>
                                    <option {{ (@$pags['doc_schedule_en'] == 1)?'selected':'' }}  value="1">Enabled</option>
                                    <option {{ (@$pags['doc_schedule_en'] == 0)?'selected':'' }}  value="0">Disabled</option>
                                </select>
                            </div>




                        </div>
                    </details>
                </div>







                <div class="col-12 page_elm">
                    <details>
                        <summary style="padding: 10px; font-size:18px; border:2px solid #999">PAYMENT TEXT
                        </summary>
                        <div class="row">

                            <div class="col-12 col-md-6 page_elm form-group">
                                <label>Airwallex </label>
                                <input class="form-control" name="pags[airwallex_name]" value="{{ @$pags['airwallex_name'] }}"
                                    required>
                            </div>

                            <div class="col-12 col-md-6 page_elm form-group">
                                <label> NowPayments </label>
                                <input class="form-control" name="pags[nowpay_name]" value="{{ @$pags['nowpay_name'] }}"
                                    required>
                            </div>

                            

                         

                          

                        </div>
                    </details>
                </div>





                <div class="py-3"><button class="btn btn-primary btn-lg px-5">Update</button></div>


            </div>

            </form>

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


            CKEDITOR.config.height = 300;
            CKEDITOR.config.width = 'auto';
            CKEDITOR.replace('checkout_notice');
            CKEDITOR.replace('home_notice');

        });
    </script>
@endpush
