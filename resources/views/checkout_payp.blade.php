@php
    
    $isEmailVerified = false;
    if (Auth::check()) {
        // User verified already
        $authuser = auth::user();
        if ($authuser->email_verified_at != null) {
            $isEmailVerified = true;
        }
    }
    $isCheckoutPage = true;

@endphp

@extends('templates.page')


@push('meta')
    <title>Checkout - {{ config('app.name') }}</title>
@endpush

@push('css')
    {{-- Extra css files here --}}
    <style>
        .view_summ_action {
            cursor: pointer;
            color: var(--gtheme-color);
            font-weight: 600;
            text-decoration: none;
        }

        .pay_btn {
            background-color: var(--gtheme-color);
        }

        .card-header {
            background-color: #eaebec;
        }

        .cart_summary {
            background-color: #f8f8f8;
            padding: 20px;
        }

        .cart_header {
            font-size: 1.4em;
            color: #333;
            font-weight: 700;
        }

        .cart_header2 {
            font-size: 1.2em;
            color: #333;
            font-weight: 700;
        }

        .cart_header3 {
            font-size: 1.1em;
            color: #333;
            font-weight: 700;
        }

        .variationx .dt {
            font-size: 0.75em;
            font-weight: 700;
            color: rgb(113, 113, 113);
            font-size: 0.75em;
            width: 100%;
            line-height: 1;
        }

        .variationx .dd {
            font-size: 0.75em;
            font-weight: 400;
            width: 100%;
            color: rgb(113, 113, 113);
            margin-bottom: 10px;
            line-height: 1;
        }

        .payment_error {
            color: red;
            font-size: 14px;
        }

        .modal .form-control {
            padding: 14px 10px !important;
        }

        .payment_method_icons img {
            height: 30px;
        }

        .auth_modal input {
            font-size: 16px;
        }

        .auth_modal .modal-content {
            box-shadow: 2px 2px 4px 6px var(--gtheme-color);
        }
        input, input[type="email"], input[type="text"]{
            /* font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important; */
            font-family: 'Roboto', sans-serif !important;
            font-weight: 400 !important;
        }
    </style>
@endpush






@section('content')
    <div class="row py-5">




        <div class="col-12 col-md-7 order-1 order-md-0">

            <div class=" mb-4">
                <h3 class="cart_header mt-2">INFORMATION</h3>

                <div id="login_region">
                    @auth('web')
                        <div style="font-size: 16px;">Welcome back,
                            <b>{{ auth('web')->user()->first_name . ' ' . auth('web')->user()->last_name }}</b>
                            ({{ auth('web')->user()->email }})</div>
                    @endauth
                </div>

                @guest('web')
                    <div class="auth_region">
                        <div class="py-2" style="font-size: 16px;">Already have an account with us? <a
                                style="cursor: pointer; color: #06F; font-weight: bold;" data-bs-toggle="modal"
                                data-bs-target="#authModal">Login</a> </div>

                        <div class="form-floating mb-3">
                            <input autocomplete="off" style="max-width:500px" value="" type="email" class="form-control"
                                id="new_email" placeholder="Email Address">
                            <label for="new_email">Email Address</label>
                        </div>
                            <input type="hidden" value="{{$quote->first_name}}"  id="new_first_name">
                            <input type="hidden" value="{{$quote->last_name}}"  id="new_last_name">

                       
                                <div class="py-2" style="font-size: 16px;">If you do not have an account, we will create one for you
                        </div>
                    </div>
                @endguest



                <input type="hidden" value="{{ auth('web')->check() ? auth('web')->user()->email : '' }}" readonly
                    type="" class="form-control verify_email_address" readonly id="user_email" placeholder="Email Address">
                <br>
                <input type="hidden"
                    value="{{ auth('web')->check() ? auth('web')->user()->first_name . ' ' . auth('web')->user()->last_name : '' }}"
                    readonly type="" class="form-control" readonly id="user_name" placeholder="Name">
                
                <input type="hidden"
                    value="{{ auth('web')->check() ? auth('web')->user()->first_name:'' }}"  readonly id="user_first_name">
                <input type="hidden"
                    value="{{ auth('web')->check() ? auth('web')->user()->last_name : '' }}"  readonly id="user_last_name">

            </div>


            <h3 class="cart_header mt-2">PAYMENT</h3>
            <h4 class="cart_header2">All transactions are secure and encrypted.</h4>

            <hr>

            <h3>Amount: <span class="ms-5">£<span class="cpw_amount">{{ number_format($quote->cpw, 2) }}</span></span>
            </h3>

            <form class="mb-4" onsubmit="applyPromoCode(event)">
                <label class="mt-3">Have promo code?</label>
                <div class="input-group" style="max-width:450px">
                    <input autocomplete="off" value="{{ $quote->promo_code }}" class="form-control" id="promo_code"
                        placeholder="Promo code">
                    <div class="input-group-append" placeholder="Code">
                        <button class="sbutton input-group-text btn btn-secondary px-5">Apply</button>
                    </div>
                </div>
            </form>

            @if($show_checkout_notice == "yes")
            <div style="max-width: 450px;">
                <div class="alert alert-warning">
                    {!! $checkout_notice !!}
                </div>
            </div>
            @endif


            @guest('web')
            <div class="auth_region" style="max-width: 450px;">
                <input type="password" style="display: none">
                <h3 class="cart_header mt-4">CREATE AN ACCOUNT  PASSWORD</h3>
                <div class="form-floating mb-3">
                    <input type="password" autocomplete="off" class="form-control" id="new_password"
                        placeholder="Choose a Password">
                    <label for="new_password">Password</label>
                </div>
                <div style="font-size: 14px; color: #777">Your personal data will be used to process your order, support
                    your experience throughout this website, and for other purposes described in our <a
                        href="/privacy-policy" target="_blank">privacy policy.</a></div>
            </div>
            @endguest

            
            

            <!-- Container for the Card Element -->
            <div class="card" style="max-width: 450px;">
                <div class="card-body">
                    <div id="express-checkout-element">
                        <!-- Express Checkout Element will be inserted here -->
                        
                        <style>
                            .choice-radio{
                                display: flex;
                                align-items: center;
                            }
                            .choice-radio input{
                                /* display: none; */
                                width: 0px;
                                opacity: 0;
                            }
                            .choice-radio input + i{
                                width: 20px;
                                height: 20px;
                                display: inline-block;
                                border: 1px solid #333;
                                border-radius: 50%;
                                background-color: #FFF;
                            }
                            .choice-radio input:checked + i{
                                border: 5px solid #1a78cf;
                                background-color: #FFF;
                                /* width: 5px;
                                height: 5px; */
                                
                            }

                            .choice-radio label{
                                width: 100%;
                                box-sizing: border-box;
                                display: flex;
                                align-items: center;
                                cursor: pointer;
                                font-size: 18px;
                                padding: 20px 5px;
                            }
                            .choice-radio label span{
                                flex: 1;
                                display: inline-block;
                                /* text-align: center; */
                                font-size: 18px;
                                font-weight: 600;
                            }
                            .choice-radio label span img{
                                height: 22px;
                                display: inline-block;
                                margin-right: 20px;
                                margin-left: 15px;
                            }
                            .payment_area{
                                border-bottom: 1px solid #d0cece;
                                border-radius: 10px;

                            }
                            .payment_body{
                                width: 100%;
                                display: flex;
                                padding: 5px; 20px;
                                justify-content: center;
                                align-items: center;
                                /* background-color: #e5eae9; */
                            }
                        </style>
                        <div id="payment_areas">


                        </div>
                    

                    </div>
                    <div id="payment_error" class="payment_error">
                        <!-- Display an error message to your customers here -->
                    </div>
                </div>
            </div>

            


            <div class="text-end mt-3 d-none" style="max-width: 450px;"><button onclick="completePayment()" class="btn  btn-primary pay_btn py-3 px-4"> Complete Payment</button></div>



        </div>

        <div class="col-12 col-md-5 order-0 order-md-1">

            <div id="cfw-cart-summary" class="cart_summary" role="complementary">
                <div id="cfw-cart-summary-content">
                    <h3 class="cart_header">
                        YOUR QUOTE </h3>
                    <hr>
                    <div class="d-block d-md-none mb-3"><a class="view_summ_action">View Summary Details <i
                                class="fa fa-caret-down"></i></a></div>

                    <div id="cfw-checkout-before-order-review"></div>
                    <div class="d-none d-md-block  quotation_summ">
                        <table id="cfw-cart" class="cfw-module">
                            <tbody>
                                <tr class="cart-item-row cart-item-f4bc63535943868b6eab0ed53bff19e0 cart_item">



                                    <th class="cfw-cart-item-description">

                                        <div class="cfw-cart-item-data">
                                            <div class="variationx">
                                                <div class="dt">Registration Number:</div>
                                                <div class="dd">{{ $quote->reg_number }}</div>
                                                <div class="dt">Vehicle Make:</div>
                                                <div class="dd">{{ $quote->vehicle_make }}</div>
                                                <div class="dt">Vehicle Model:</div>
                                                <div class="dd">{{ $quote->vehicle_model }}</div>
                                                <div class="dt">Engine CC:</div>
                                                <div class="dd">{{ $quote->engine_cc }}</div>
                                                <div class="dt">Start Date:</div>
                                                <div class="dd">{{ date('d-m-Y', strtotime($quote->start_date)) }}
                                                </div>
                                                <div class="dt">Start Time:</div>
                                                <div class="dd">{{ date('h:m a', strtotime($quote->start_date .' '.$quote->start_time)) }}</div>
                                                <div class="dt">End Date:</div>
                                                <div class="dd">{{ date('d-m-Y', strtotime($quote->end_date)) }}</div>
                                                <div class="dt">End Time:</div>
                                                <div class="dd">{{ date('h:m a', strtotime($quote->end_date .' '.$quote->end_time))  }}</div>
                                                <div class="dt">Date of Birth:</div>
                                                <div class="dd">{{ date('d-m-Y', strtotime($quote->date_of_birth)) }}
                                                </div>
                                                <div class="dt">Reason:</div>
                                                <div class="dd">{{ $quote->cover_reason }}</div>
                                                <div class="dt">Name(s):</div>
                                                <div class="dd">{{ $quote->title }} {{ $quote->first_name }} {{ $quote->middle_name }} {{ $quote->last_name }}</div>
                                                <div class="dt">Address:</div>
                                                <div class="dd">{{ $quote->address }}</div>
                                                <div class="dt">Postcode:</div>
                                                <div class="dd">{{ $quote->postcode }}</div>
                                                <div class="dt">Occupation:</div>
                                                <div class="dd">{{ $quote->occupation }}</div>
                                                <div class="dt">Licence Type:</div>
                                                <div class="dd">{{ $quote->licence_type }}</div>
                                                <div class="dt">Licence Held Duration:</div>
                                                <div class="dd">{{ $quote->licence_held_duration }}</div>
                                                <div class="dt">Vehicle Value:</div>
                                                <div class="dd">{{ $quote->vehicle_type }}</div>
                                            </div>
                                        </div>
                                        <div class="cfw_cart_item_after_data">
                                        </div>
                                    </th>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="cfw-totals-list" class="cfw-module d-none d-md-block  quotation_summ">
                        <table class="cfw-module table">

                            <tbody>
                                <tr class="cart-subtotal">
                                    <th>Subtotal</th>
                                    <td><span class="woocommerce-Price-amount amount"><bdi><span
                                                    class="woocommerce-Price-currencySymbol">£</span><span
                                                    class="cpw_subtotal">{{ number_format($quote->cpw, 2) }}</span></bdi></span>
                                    </td>
                                </tr>
                                <tr class="cart-discount d-none">
                                    <th>Discount</th>
                                    <td><span class="woocommerce-Price-amount amount"><bdi><span
                                                    class="woocommerce-Price-currencySymbol">£</span><span
                                                    class="cpw_discount"></span></bdi></span>
                                    </td>
                                </tr>
                                <tr class="order-total">
                                    <th>Total</th>
                                    <td><strong><span class="woocommerce-Price-amount amount"><bdi><span
                                                        class="woocommerce-Price-currencySymbol">£</span><span
                                                        class="cpw_total">{{ number_format($quote->cpw, 2) }}</span></bdi></span></strong>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>

                </div>
            </div>


        </div>
    </div>




    <!-- Modal -->
    <div class="modal fade auth_modal" id="authModal" tabindex="-1" aria-labelledby="authModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel"><b>WELCOME BACK</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-3 justify-content-center d-none" id="authTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login"
                                type="button" role="tab" aria-controls="login" aria-selected="true">
                                Login
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register"
                                type="button" role="tab" aria-controls="register" aria-selected="false">
                                Register
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="authTabContent">
                        <!-- Login Form -->
                        <div class="tab-pane fade show active" id="login" role="tabpanel"
                            aria-labelledby="login-tab">
                            <form onsubmit="loginForm(event)">
                                <p>Please enter your login details below.</p>
                                <div class="form-floating mb-3">
                                    <input style="padding-top: 32px !important" class="form-control" placeholder="Email address" name="username"
                                        id="username" required>
                                    <label for="username" class="form-label">Email Address</label>
                                </div>
                                <div class="form-floating  mb-3">
                                    <input style="padding-top: 32px !important" type="password" class="form-control" placeholder="Password" id="password"
                                        required>
                                    <label for="password" class="form-label">Password</label>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="rememberme">
                                        <label class="form-check-label" for="rememberme">Remember Me</label>
                                    </div>
                                    <a href="#"  data-bs-toggle="modal"
                                    data-bs-target="#fgetModal"  class="text-decoration-none">Forgot
                                        Password?</a>
                                </div>
                                <div class="sbutton"><button type="submit"
                                        class="btn btn-primary w-100 py-3 pay_btn">Login</button></div>
                            </form>
                        </div>

                        <!-- Register Form -->
                        <div class="tab-pane fade d-none" id="register" role="tabpanel" aria-labelledby="register-tab">
                            <form onsubmit="registerForm(event)">
                                <div class="mb-3">
                                    <label for="reg_first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" id="reg_first_name"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="reg_last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" id="reg_last_name"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="reg_email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" name="email" id="reg_email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="reg_password" class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="reg_password"
                                        required>
                                </div>
                                <div class="sbutton"><button type="submit"
                                        class="btn btn-success w-100 py-3 ">Register</button></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- FORGET Modal -->
    <div class="modal fade" id="fgetModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">Forgot Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">We will send a password reset link to your email address</div>
                    <form onsubmit="forgotForm(event)">
                        <div class="mb-3">
                            <label for="fgt_email" class="form-label">Email Address</label>
                            <input class="form-control" name="email" id="fgt_email" required>
                        </div>
                        <div class="sbutton"><button type="submit"
                                class="btn btn-primary w-100 py-3 pay_btn">Submit</button></div>
                    </form>

                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <style>
        .payment_indicator {
            position: fixed;
            width: 100%;
            height: 100%;
            left: 0px;
            top: 0px;
            background: linear-gradient(to right, rgba(2, 2, 2, 0.7), rgba(2, 2, 2, 0.7));
            z-index: 999999;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #FFF;
            display: none;
        }

        .payment_indicator p {
            font-size: 18px;
        }
    </style>
    <div class="payment_indicator" id="payment_indicator">
        <div>
            <i class="fa fa-spin  fa-5x fa-spinner"></i>
            <p>Processing payment</p>
        </div>
    </div>

    @if($show_home_notice == "yes" && ($choosen_page_notice == "checkout" || $choosen_page_notice == "both"))
    <!-- Modal -->
    <div class="modal fade" id="noticeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-color: var(--gtheme-color)">
            <div class="modal-header">
            <h5 class="modal-title" id="noticeModalLabel">Important Notice</h5>
            </div>
            <div class="modal-body">

                {!! $home_notice !!}
            </div>
            <div class="modal-footer">
            <button id="closeNoticeBtn" type="button" class="btn btn-primary">Close</button>
            </div>
        </div>
        </div>
    </div>
    @endif


@endsection('content')



@push('js')
    {{-- extra jss files here --}}

    <script
    src="https://www.paypal.com/sdk/js?client-id={{$paypalPublic}}&currency=GBP&components=buttons&enable-funding=applepay,card"
    data-sdk-integration-source="developer-studio"
></script>


    

    <script>

        let EMAIL_VERIFICATION_STATE = {{ $isEmailVerified? 'true':'false' }};

        @if($show_home_notice == "yes" && ($choosen_page_notice == "checkout" || $choosen_page_notice == "both"))
        document.addEventListener("DOMContentLoaded", function () {
            const modal = new bootstrap.Modal(document.getElementById('noticeModal'));
            const closeBtn = document.getElementById('closeNoticeBtn');

            const noticeKey = 'noticeDismissedAt';
            const durationHours = 2; // Change to 24 for once a day

            function shouldShowNotice() {
                const dismissedAt = localStorage.getItem(noticeKey);
                if (!dismissedAt) return true;


                const dismissedTime = new Date(dismissedAt);
                const now = new Date();
                const diffInMs = now - dismissedTime;
                const diffInHours = diffInMs / (1000 * 60 * 60);
                return diffInHours >= durationHours;
            }

            if (shouldShowNotice()) {
                modal.show();
            }

            closeBtn.addEventListener('click', function () {
                localStorage.setItem(noticeKey, new Date().toISOString());
                modal.hide();
            });
        });
        @endif


        const backdatedTime = {{$backdatedTime}};
        const timeStart = new Date();

        if(backdatedTime == 1){
            setInterval(function(){
                let timeCurrent =  new Date();
                let timeDiff = (timeCurrent - timeStart) / (1000 * 60);
                
                if(timeDiff > 60){
                    alert("Page expired. Please fill form again");
                    window.location.href = "/order/get-quote";
                }

            }, 2000);
        }

        const QUOTATION_ID = {{ $quote->id }};
        const CPW_AMOUNT_DEFAULT = {{ $quote->cpw }};
        let CPW_AMOUNT = {{ $quote->cpw }};

        let CLIENT_SECRET = "";

        let alreadySetUp = false;

        let THIS_CFR_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


        paypayPMethods  = true;

        const paypalUrl = "/paypal-action";

        
        function showProgress(message) {
            $("#payment_indicator p").html(message);
            $("#payment_indicator").css('display', 'flex');
        }

        function closeProgress() {
            $("#payment_indicator p").html('');
            $("#payment_indicator").css('display', 'none');
        }

        function showError(message) {
            $(`#payment_error`).html(message);
        }

        function closeError() {
            $(".payment_error").html('');
        }

        
        setTimeout(()=>{
            
            if($(".card_parea").length){
                return;
            }
            setUpPayment();

        }, 2000);
        

        async function setUpPayment(){

            if(alreadySetUp){
                return;
            }
            alreadySetUp = true;
    
            closeError();

            let this_amount = parseFloat(CPW_AMOUNT).toFixed(2);

            $("#payment_areas").html('');

            
            paypal.Buttons({
            locale: 'en_US',
            style: {
                size: 'medium',
                color: 'gold',
                shape: 'pill',
            },
            // Set up a payment
            createOrder: async function () {
                
                if ($("#new_email").length) {
                    await userAuthentication();
                }

                // Should be logged in now
                if ($("#new_email").length) {
                    return;
                }

                if( ! EMAIL_VERIFICATION_STATE){
                    $("#verifyModal").modal("show");
                    $(".need-verify-msg").addClass('d-none')
                    $(".resend-verify-email").removeClass('d-none');
                    return;
                }

                showProgress('Please wait'); 

                return fetch(paypalUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': THIS_CFR_TOKEN
                    },
                    body: JSON.stringify({
                        action: "create_order",
                        id: QUOTATION_ID,
                    }),
                })
                .then(response => response.json())
                .then(order => {
                    closeProgress();  // Clear progress after the request completes successfully
                    return order.id;
                })
                .catch(error => {
                    closeProgress();  // Also clear progress if there's an error
                    console.error("Error:", error);
                });
            },
            onApprove: function (data, actions) {
                console.log(data);
                let fdata = {
                    action: "capture_order",
                    orderID: data.orderID,
                    id: QUOTATION_ID

                };
                showProgress('Please wait');
                return fetch(paypalUrl, {
                    method: "POST",
                    headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': THIS_CFR_TOKEN
                    },
                    body: JSON.stringify(fdata)
                })
                .then((response) => response.json())
                .then((orderData) => {

                    closeProgress();

                    const errorDetail = orderData?.details?.[0];

                    if (errorDetail?.issue === "INSTRUMENT_DECLINED") {
                        // (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
                        // recoverable state, per
                        // https://developer.paypal.com/docs/checkout/standard/customize/handle-funding-failures/
                        return actions.restart();
                    } else if (errorDetail) {
                        // (2) Other non-recoverable errors -> Show a failure message
                        throw new Error(
                            `${errorDetail.description} (${orderData.debug_id})`
                        );

                    } else if (!orderData.purchase_units) {
                        throw new Error(JSON.stringify(orderData));
                    } else {

                        window.location.href = "{{ url('/confirmed') }}" + "?id=" + QUOTATION_ID;

                    }

                    // Successful capture! For dev/demo purposes:
                    // console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                    // const transaction = orderData.purchase_units[0].payments.captures[0];
                    // $('#payment_form_parent').html(paypalSuccessMsg);
                    // $('#payment_form_parent')[0].scrollIntoView();


                })
                .catch(error => {
                    
                    closeProgress();

                    // Handle error response
                    console.error('There was an error!', error);
                    // Access the error response as JSON if it exists
                    showError(error.message || error.toString());

                });
            
            },
            onCancel: function (e) {
                toastr.error("The payment has been cancelled!");
            },
            onError: function (e) {
            toastr.error("The payment has been cancelled!");
            },
        }).render('#payment_areas');
        


    }


       
       

        

        async function userAuthentication() {

            let fdata = {
                id: QUOTATION_ID
            };

            $(".form_error").remove();
            
            fdata["new_email"] = $("#new_email").val().trim();
            fdata["first_name"] = $("#new_first_name").val().trim();
            fdata["last_name"] = $("#new_last_name").val().trim();
            fdata["new_password"] = $("#new_password").val().trim();
        
            showProgress('Authenticating Session. Please wait');

            try {
                const res = await fetch('/paypal-checkout-registration', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json', // Specify content type
                        'X-CSRF-TOKEN': THIS_CFR_TOKEN, // Add CSRF token
                    },
                    body: JSON.stringify(fdata), // Add body if required
                });
                if (!res.ok) {
                    closeError();
                    closeProgress();
                    // Parse response JSON to extract errors
                    const errorData = await res.json();
                    // Render errors using your `render_errors` function


                    console.log(errorData);
                    let omessage = "";
                    if('message' in errorData){
                        omessage += errorData.message;
                    }
                    if('errors' in errorData){
                        let errors = errorData.errors;
                        for(key in errors){
                            omessage += '<br>' + errors[key].join(', ');
                            $(`#${key}`).after(`<div class="form_error">${errors[key].join(', ')}</div>`);
                            
                            if($(`#${key}`).length){
                                $(`#${key}`)[0].scrollIntoView();
                            }
                        }
                    }
                    if(omessage){
                        toastr.error(omessage);
                    }
                    else{
                        render_errors(errorData, 'toast', $("body"));
                    }

                    return "";
                }
                const {
                    user_name,
                    first_name,
                    last_name,
                    user_email,
                    token
                } = await res.json();

                if (! user_email) {
                    closeError();
                    closeProgress();
                    toastr.error(`Error creating account`);
                    return "";
                } else {

                    $("#user_email").val(user_email);
                    $("#user_name").val(user_name);
                    $("#user_first_name").val(first_name);
                    $("#user_last_name").val(last_name);
                    $(".auth_region").remove();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    });

                    THIS_CFR_TOKEN = token;


                    $("#login_region").html(
                        `<div style="font-size: 16px;">Your account have been created, and you are currently logged in as  <b>${user_name}</b> <span class="verify_email_address">(${user_email})</span></div>`
                    );

                    $("input.verify_email_address").val(user_email)
                    $(".verify_email_address:not(input)").html(user_email);
                    $("#verifyModal").modal("show");
                    $(".need-verify-msg").addClass('d-none')
                    $(".resend-verify-email").removeClass('d-none');

                }
            } catch (error) {
                // Catch both network and server-side errors
                closeError();
                closeProgress();
                toastr.error(`Error: ${error.message}`);
                return "";
            }

        }





    </script>
@endpush
