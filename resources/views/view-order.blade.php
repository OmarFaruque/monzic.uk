@php
    $user = auth('web')->user();
@endphp
@extends('templates.page')


@push('meta')
    <title>View Order - {{ config('app.name') }}</title>
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
        div#header,
        div#footer {
            display: none !important;
        }
    </style>
@endpush




@section('content')
    <div data-elementor-type="wp-page" data-elementor-id="1306" class="elementor elementor-1306"
        data-elementor-post-type="page">

        <style>
            body * {
                font-family: Raleway, system-ui, sans-serif;
            }

            body {
                font-size: 16px;
            }

            td .btn-sm {
                background-color: #FFF;
                color: #444;
                font-weight: bold;
                border: 2px solid #387a8c;
            }

            #policyModal th {
                color: #387a8c;
            }

            body {
                background: linear-gradient(to right, var(--gtheme-color), var(--gtheme-color-light));

                /* background: linear-gradient(to right, rgba(0, 50, 135, 1), rgb(45, 105, 209)); */
            }

            .card-section {
                display: flex;
                align-items: center;
                justify-content: center;
                border: none !important;
                background-color: transparent !important;
                box-shadow: none !important;

            }

            .vcard.card,
            .policy-details .card {
                box-shadow: 2px 2px 2px 4px var(--gtheme-color);
                border: none;
                padding: 30px 20px;
                background: var(--gtheme-color-2);

                width: 100%;
                max-width: 650px;

            }

            .vcard .card-body {}

            .vcard .card-body label {
                color: #FFF !important;
                color: white;
                line-height: 19px;
                margin: 10px 0px;
                position: relative;
                display: inline-block;
                font-weight: normal;
                font-size: 16px;
                text-align: left !important;
                height: 19px !important;

            }

            .vcard .card-body .form-group {
                margin-bottom: 20px;
            }

            .policy-info {
                font-style: normal;
                font-weight: 400;
                font-size: 32px;
                line-height: 38px;
                text-align: center;

            }

            .policy-info-sm {
                color: #FFF;
                font-size: 12px;
                margin: 10px 0;
                position: relative;
                display: inline-block;
                text-align: left !important;
                font-style: normal !important;
                font-weight: 400 !important;
                font-size: 16px !important;
                line-height: 150% !important;
            }

            .vcard .card-body input {
                height: 64px !important;
                border: 2px solid rgba(255, 255, 255, 0.4);
                background-color: transparent !important;
                color: white;
                border-radius: 8px;
                /* font-family: 'Raleway'; */
                padding: 6px 12px;
                line-height: 1.42857143;
                font-size: 16px !important;

            }

            .vcard .card-body input:focus{
                box-shadow: 0 0 5px #FFF !important;
            }
            
            .vcard .card-body input::placeholder {
                opacity: 1;
                color: #bbb9b9;
            }

            .vcard .card-body input:focus {
                border-color: #FFF !important;
                background-color: transparent;
                color: #FFF;
            }


            .date-row {

                height: 64px !important;
                border: 2px solid rgba(255, 255, 255, 0.4);
                background-color: transparent !important;
                color: white;
                border-radius: 8px;
                padding: 6px 12px;
                line-height: 1.42857143;
                font-size: 16px !important;

                color: #FFF !important;
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
            }

            .date-row:has(input:focus) {
                box-shadow: 0 0 5px #FFF;
                border-color: #FFF;
            }

            .date-row .date-input {
                flex: 1;
                width: calc(33% - 2px);
                border: none !important;
                outline: none !important;
                display: inline-block !important;
                margin: 3px 0px !important;
                height: 20px !important;
                text-align: center;
                border-radius: 0px !important;
                box-sizing: border-box;
            }

            /* For Chrome, Safari, Edge, and Opera */
            .date-input::-webkit-outer-spin-button,
            .date-input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            /* For Firefox */
            .date-input {
                -moz-appearance: textfield;
            }

            .vcard .card-body .date-row .date-input:focus {
                outline: none !important;
                border: none !important;
                box-shadow: none !important;
            }

            .date-row .date-line {
                background-color: #777;
                display: inline-block;
                width: 1px;
                border: 1px solid #777;
                height: 80%;
                box-sizing: border-box;
            }

            .vcard .card-body #sbutton button {
                width: 100%;

                height: 64px !important;
                border: 2px solid rgba(255, 255, 255, 0.4);
                background-color: #257963;
                color: white;
                border-radius: 8px;
                padding: 6px 12px;
                line-height: 1.42857143;
                font-size: 16px !important;

            }


            .policy-details {
                /* background-color: #FFF; */
            }

            .line {
                display: flex;
                /* justify-content: space-between; */
                flex-direction: column;
                /* align-items: center; */
                padding: 9px 0;
            }

            .line label {
                color: rgba(255, 255, 255, 0.6);
                font-size: .85rem;
                flex-basis: 40%;
                text-align: left;
                margin-bottom: 5px;
            }

            .line .value {
                color: rgba(255, 255, 255, 1);
                text-align: left;
                flex-basis: 60%;
                font-size: 1rem;
            }

            .card {
                /* border-left: 4px solid var(--gtheme-color); */
                border-radius: 12px;
                /* box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1); */
            }

            .card-title {
                color: rgba(255, 255, 255, 1);
                font-weight: inherit;
                font-size: 1.5rem;
            }

            .card-section * {
                box-sizing: border-box;
            }

            .cert-btn {
                font-size: 1.05rem;
                margin-bottom: 15px;
                color: #FFF;
                display: inline-block;
                cursor: pointer;
            }

            .cert-btn:hover {
                color: #ebebeb;
            }

            .cert-btn>span {
                margin-left: 10px;
                font-weight: 600;
                text-decoration: underline !important;
            }

            .progress_main {
                background: #bbb9b9;
                height: 10px;
                border-radius: 10px;
            }

            .progress_main>div {
                background-color: var(--gtheme-color-light-2);
                border-radius: 10px;
                height: 100%;

            }
        </style>


        <div style="border: none" data-elementor-type="wp-page" data-elementor-id="1306" class="elementor elementor-1306"
            data-elementor-post-type="page">
            <section
                class="card-section elementor-section elementor-top-section elementor-element elementor-element-5b3eada hide-logged-out elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                data-id="5b3eada" data-element_type="section">

                <div id="policy_details" class="policy-details d-none">
                    <h3 class="text-center text-white mb-5 policy-info">Order info</h3>

                    <div class="container" style="display: flex; justify-content: center;">
                        <!-- Policy Summary -->
                        <div class="summary-section" style="width:100%; max-width: 650px;">

                            <div class="row">
                                <div class="col-12 col-md-12 my-3 myxn">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title">Order</h5>

                                            <div class="line">
                                                <div class="value" id="policy_status">Expired order</div>
                                                <div
                                                    class="flex overflow-hidden relative w-full h-2 rounded-lg progress_main">
                                                    <div class="absolute top-0 left-0 h-2 transition-transform"
                                                        style="width: 30%;">
                                                    </div>
                                                </div>
                                                <label class="mt-2" id="time_diff"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Vehicle -->
                                <div class="col-md-12 my-3 myxn">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title">Vehicle</h5>
                                            <div class="line">
                                                <div class="value-2"><span
                                                        style="display:inline-block; font-size: 0.9rem; border-radius: 10px;; padding: 3px 7px; background-color: var(--gtheme-color-light); color:#FFF"
                                                        id="reg_number"></span></div>
                                            </div>
                                            <div class="line">
                                                <div class="value"><span id="vehicle_make"></span> <span id="vehicle_model"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{-- COVER START AND END --}}

                                <div class="col-12 col-md-6 my-3 myxn">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title">Start</span></h5>
                                            <div class="line">
                                                <label><i class="far fa-calendar"></i> Date</label>
                                                <div class="value"><span id="start_date"></span></div>
                                            </div>
                                            <div class="line">
                                                <label><i class="far fa-clock"></i> Time (local UK time) </label>
                                                <div class="value"><span id="start_time"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 my-3 myxn">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title">End</h5>
                                            <div class="line">
                                                <label><i class="far fa-calendar"></i> Date</label>
                                                <div class="value"><span id="end_date"></span></div>
                                            </div>
                                            <div class="line">
                                                <label><i class="far fa-clock"></i> Time (local UK time) </label>
                                                <div class="value"><span id="end_time"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Driver Information -->
                                <div class="col-md-12 my-3 myxn">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title">Personal Details</h5>

                                            <div class="line">
                                                <label><i class="far fa-user"></i> Name</label>
                                                <div class="value"><span id="first_name"></span> <span
                                                        id="middle_name"></span>
                                                    <span id="last_name"></span>
                                                </div>
                                            </div>
                                            <div class="line">
                                                <label><i class="fa fa-calendar"></i> Date of birth</label>
                                                <div class="value" id="date_of_birth"></div>
                                            </div>
                                            <div class="line">
                                                <label><i class="fa fa-briefcase "></i> Occupation</label>
                                                <div class="value" id="occupation"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!-- Contact  Information -->
                                <div class="col-12 col-md-12 my-3 myxn">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title">Contact details</h5>

                                            <div class="line">
                                                <label><i class="far fa-envelope"></i> Email address</label>
                                                <div class="value" id="email"></div>
                                            </div>
                                            <div class="line">
                                                <label><i class="fa fa-phone-square"></i> Contact number</label>
                                                <div class="value" id="contact_number"></div>
                                            </div>
                                            <div class="line">
                                                <label><i class="fa fa-map-marker-alt"></i> Home address</label>
                                                <div class="value"><span id="address"></span>, <span
                                                        id="postcode"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <!-- Personal and Licence Information -->
                                <div class="col-12 col-md-12 my-3 myxn">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title">{{$pagstn['doc_header']}}</h5>

                                            <div>

                                                @if($pagstn['doc_certificate_en'] == 1)
                                                <div><a onclick="viewDocument(event, '/pdf/{{Str::slug($pagstn['doc_certificate'])}}')"
                                                        class=" cert-btn  m-1"><i class="fa fa-download"></i>
                                                        <span>{{$pagstn['doc_certificate']}}</span></a></div>
                                                @endif

                                                @if($pagstn['doc_information_en'] == 1)
                                                <div><a onclick="viewDocument(event, '/pdf/{{Str::slug($pagstn['doc_information'])}}')"
                                                        class=" cert-btn  m-1"><i class="fa fa-download"></i>
                                                        <span>{{$pagstn['doc_information']}}</span></a></div>
                                                @endif

                                                @if($pagstn['doc_statement_en'] == 1)
                                                <div><a onclick="viewDocument(event, '/pdf/{{Str::slug($pagstn['doc_statement'])}}')"
                                                        class=" cert-btn  m-1"><i class="fa fa-download"></i>
                                                        <span>{{$pagstn['doc_statement']}}</span></a></div>
                                                @endif

                                                @if($pagstn['doc_schedule_en'] == 1)
                                                <div><a onclick="viewDocument(event, '/pdf/{{Str::slug($pagstn['doc_schedule'])}}')"
                                                        class=" cert-btn  m-1"><i class="fa fa-download"></i>
                                                        <span>{{$pagstn['doc_schedule']}}</span></a></div>
                                                @endif

                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <!-- Cover -->
                                <div class="col-12 col-md-12 my-3 myxn">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <div class="line text-center" style="text-align: center !important;">
                                                <label style="text-align: center !important;">Total price</label>
                                                <div class="value" id="cpw_total"
                                                    style="font-size: 2rem; font-weight:normal; text-align: center !important;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>







                <div class="vform" style=" max-width:100%">
                    <h3 class="text-center text-white mb-5 policy-info">Retrieve your information</h3>
                    <div class="card vcard">
                        <div class="card-body table-responsive">
                            <form onsubmit="viewPolicy(event)" autocomplete="off" id="baseForm">
                                @csrf <!-- Generates the CSRF token field -->
                                <div class="row">
                                    <div class="col-12 col-md-12 form-group">
                                        <div class="policy-info-sm"> To protect your personal data, we need to verify information about you, the customer.
                                            Please enter your details below.</div>
                                    </div>
                                    <input name="policy_number" value="{{ $policy_number }}" type="hidden">
                                    <div class="col-12 col-md-12 form-group">
                                        <label><i class="far fa-user"></i> Surname</label>
                                        <input placeholder="e.g. Smith" value="" required name="last_name"
                                            class="form-control">
                                    </div>
                                    <div class="col-12 col-md-12 form-group">
                                        <label><i class="far fa-calendar"></i> Date of Birth</label>
                                        <div class="date-row">
                                            <input required type="number" class="date-input" id="dd"
                                                placeholder="DD" maxlength="2">
                                            <span class="date-line"></span>
                                            <input required type="number" class="date-input" id="mm"
                                                placeholder="MM" maxlength="2">
                                            <span class="date-line"></span>
                                            <input required type="number" class="date-input" id="yyyy"
                                                placeholder="YYYY" maxlength="4">
                                        </div>
                                        <input type="hidden" name="date_of_birth" id="ndate_of_bith">

                                    </div>

                                    <div class="col-12 col-md-12 form-group">
                                        <label><i class="far fa-address-card"></i> Postcode</label>
                                        <input style="max-width: 300px;" placeholder="eg. ABI 2CD" value=""
                                            required name="postcode" class="form-control text-uppercase">
                                    </div>
                                    <div class="col-12 col-md-12 mt-4 form-group text-center" id="sbutton">

                                        <button class="btn button btn-primary  btn-block">Retrieve Information</button>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    @endsection('content')






    @push('js')
        <script type="text/javascript" src="/js/zebra_datepicker.min.js?ver={{ config('app.version') }}"></script>


        <script>
            const startDate = "{{ date('d-m-Y', strtotime('-75 years')) }}";
            const startDaten = "{{ date('d-m-Y', strtotime('-24 years')) }}";
            const endDate = "{{ date('d-m-Y', strtotime('-17 years')) }}";

            const startYear = {{ date('Y', strtotime('-78 years')) }};
            const endYear = {{ date('Y', strtotime('-16 years')) }};

            function enroll() {
                console.log("Invalid Date Entered!");
            }

            function valid() {
                console.log("Valid Date Entered!");
            }

            jQuery(document).ready(function($) {
                // Allow only digits and auto-move to the next input
                $(".date-input").on("input", function() {
                    let val = $(this).val().replace(/\D/g, ''); // Remove non-digits
                    $(this).val(val); // Set cleaned value

                    let id = $(this).attr("id");

                    //Enforce maxlength is obey
                    let maxLength = parseInt($(this).prop('maxlength'));
                    if($(this).val().length > maxLength){
                        $(this).val($(this).val().slice(0, maxLength)).trigger('changes');
                    }
                    val = $(this).val().replace(/\D/g, '');//  Re initialize val


                    // Auto-move to the next input when full
                    if ((id === "dd" || id === "mm") && val.length === 2) {
                        let nextInput = $(this).closest('.date-row').find('.date-input').eq($(".date-input")
                            .index(this) + 1);
                        if (nextInput.length) {
                            nextInput.focus();
                        }
                    } else if (id === "yyyy" && val.length === 4) {
                        // validateDate();
                    }
                    validateDate();
                });

                // Restrict non-numeric input (Allow: digits, Backspace, Tab, Arrow keys, Delete)
                // $(".date-inputd").on("keypress", function (e) {
                //     if (!e.key.match(/[0-9]/) && ![8, 9, 37, 39, 46].includes(e.keyCode)) {
                //         e.preventDefault();
                //     }
                // });

                // Handle paste event (multiple date formats)
                $(document).on("paste", ".date-input", function(e) {


                    let data = e.originalEvent.clipboardData.getData("text");

                    let cleanData = data.replace(/[^\d]/g, "");

                    // Extract numbers from various formats (e.g., DD/MM/YYYY, D-M-YYYY, DDMMYYYY)
                    let day, month, year;

                    if (data.split("-").length == 3) {
                        let rdata = data.split("-");
                        day = (rdata[0].length <= 2) ? rdata[0] : "";
                        month = (rdata[1].length <= 2) ? rdata[1] : "";
                        year = (rdata[2].length <= 4) ? rdata[2] : "";
                    } else if (data.split("/").length == 3) {
                        let rdata = data.split("-");
                        day = (rdata[0].length <= 2) ? rdata[0] : "";
                        month = (rdata[1].length <= 2) ? rdata[1] : "";
                        year = (rdata[2].length <= 4) ? rdata[2] : "";
                    } else if (data.length === 8) {
                        day = cleanData.substring(0, 2);
                        month = cleanData.substring(2, 4);
                        year = cleanData.substring(4, 8);
                    }

                    if (day && month) {
                        // Fill inputs
                        $("#dd").val(day);
                        $("#mm").val(month);
                        $("#yyyy").val(year);
                        validateDate();
                    }
                    e.preventDefault();
                });

            });

            // Validate Date and Call Appropriate Function
            function validateDate() {
                let day = parseInt($("#dd").val(), 10);
                let month = parseInt($("#mm").val(), 10);
                let year = parseInt($("#yyyy").val(), 10);

                let isValid = !isNaN(day) && !isNaN(month) && !isNaN(year);
                if (isValid) {
                    let date = new Date(year, month - 1, day);
                    isValid =
                        date.getFullYear() === year &&
                        date.getMonth() === month - 1 &&
                        date.getDate() === day;
                }
                if (isValid) {
                    $(".date_error").remove();
                    if (year >= startYear && year <= endYear) {
                        let date_n = `${ (day>9)?day:"0"+day}-${(month>9)?month:"0"+month}-${year}`;
                        $("#ndate_of_bith").val(date_n);
                        return date_n;
                    } else {
                        $(".date-row").after('<div class="form_error date_error">Year out of range</div>');
                        return "";
                    }
                } else {
                    $(".date_error").remove();
                    if ($("#dd").val() && $("#mm").val() && $("#yyyy").val()) {
                        $(".date-row").after('<div class="form_error date_error">Not a valid date</div>');
                    }
                    return "";
                }
            }
        </script>
    @endpush
