@php

    $isEmailVerified = false;
    if (Auth::check()) {
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
        
        .bank-details {
            max-width: 600px;
            margin: auto;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            padding: 2rem;
        }

        .bank-details h5 {
            color: #CCC;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .bank-details .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .bank-details .detail-left {
            display: flex;
            align-items: center;
            flex: 1;
        }
        .bank-details .detail-left i.fa {
            color: var(--gtheme-color);
        }

        .bank-details .detail-item i.fa-copy {
            color: #CCC;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .bank-details .detail-item i.fa-copy:hover {
            transform: scale(1.2);
        }

        .bank-details .detail-item i.fa-check {
            color: green;
            display: none;
        }

        .bank-details .label {
            font-weight: 600;
            margin-right: 0.5rem;
            color: #333;
        }

        .bank-details .value {
            color: #555;
            word-break: break-word;
        }

        .bank-details .note {
            font-size: 0.875rem;
            color: #888;
            margin-top: 1.5rem;
            text-align: center;
        }

        .info-message {
            text-align: center;
            background-color: rgba(0, 123, 255, 0.05);
            border: 1px solid var(--gtheme-color);
            padding: 1rem;
            border-radius: 0.5rem;
            color: #333;
            margin-top: 2rem;
            font-size: 0.95rem;
        }

        .confirm-btn {
            display: block;
            margin: 1rem auto 0;
            background-color: var(--gtheme-color);
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .confirm-btn:hover {
            filter: brightness(0.95);
        }
        .transfer-amount {
        text-align: center;
        font-size: 1.1rem;
        margin-top: -1rem;
        margin-bottom: 1.5rem;
        color: #333;
        }
        .transfer-amount strong {
        color: var(--gtheme-color);
        }
        .choice-radio{
            display: flex;
            align-items: center;
        }
        .choice-radio input{
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
        }
    </style>
@endpush

@section('content')
    <div class="row py-5" x-data="{ tipAmount: 0 }">
        <div class="col-12 col-md-7 order-1 order-md-0" id="payment_cal_path">
            <div class=" mb-4">
                <h3 class="cart_header mt-2">INFORMATION</h3>
                <div id="login_region">
                    @auth('web')
                        <div style="font-size: 16px;">Welcome back,
                            <b>{{ auth('web')->user()->first_name . ' ' . auth('web')->user()->last_name }}</b>
                            ({{ auth('web')->user()->email }})
                        </div>
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
                        <input type="hidden" value="{{ $quote?->first_name }}" id="new_first_name">
                        <input type="hidden" value="{{ $quote?->last_name }}" id="new_last_name">

                        <div class="py-2" style="font-size: 16px;">If you do not have an account, we will create one for you
                        </div>
                    </div>
                @endguest

                <input type="hidden" value="{{ auth('web')->check() ? auth('web')->user()->email : '' }}" readonly
                    type="" class="form-control verify_email_address" readonly id="user_email"
                    placeholder="Email Address">
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

            <div>
                <h3>Amount: <span class="ms-5">£<span class="cpw_amount" x-text="(parseFloat({{ number_format($quote?->cpw ?? $aiPrice, 2, '.', '') }}) + parseFloat(tipAmount)).toFixed(2)">{{ number_format($quote?->cpw ?? $aiPrice, 2) }}</span></span>
                </h3>

                @if ($aiDoc && $ai_document_tips && $ai_document_tips == 'on')
                    <div class="mt-2">
                        <label for="tip-slider" class="form-label fw-semibold">Add a tip (optional):</label>
                        <div class="d-flex align-items-center">
                            <input type="range" class="form-range" min="0" max="10" step="1" id="tip-slider" x-model="tipAmount">
                            <span class="ms-3" x-text="'£' + tipAmount"></span>
                        </div>
                    </div>
                @endif

                <form class="mb-4" onsubmit="applyPromoCode(event)">
                    <label class="mt-3">Have promo code?</label>
                    <div class="input-group" style="max-width:450px">
                        <input autocomplete="off" value="{{ $quote?->promo_code }}" class="form-control" id="promo_code"
                            placeholder="Promo code">
                        <div class="input-group-append" placeholder="Code">
                            <button class="sbutton input-group-text btn btn-secondary px-5">Apply</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Container for the Card Element -->
            <div class="card" style="max-width: 450px;">
                <div class="card-body">
                    <div id="payment_areas">
                        <div class="payment_area">
                            <div class="choice-radio">
                                <label for="choice_lemonsqueezy">
                                    <input checked type="radio" autocomplete="off" name="choice" id="choice_lemonsqueezy" value="lemonsqueezy">
                                    <i></i>
                                    <span>
                                        &nbsp;<img src="{{ asset('img/icon/Lemon-Squeezy.png') }}" alt="Lemon Squeezy" style="height: 22px; display: inline-block; margin-left: 0px; margin-right: 15px;">
                                    </span>
                                </label>
                            </div>
                            <div class="payment_body" style="flex-direction: column; gap:10px; padding: 10px 20px 20px; align-items: flex-start; text-align: left;">
                                You will be redirected to Lemon Squeezy's secure checkout page to complete your payment. After successful payment, you will be redirected back to our website.
                            </div>
                        </div>

                        @if($show_bank && $quote)
                            <div class="payment_area bank_parea">
                                <div class="choice-radio"><label for="choice_bank"><input type="radio" name="choice" autocomplete="off" id="choice_bank" value="bank"><i></i>  <span><img src="/img/icons/bank.png"> Bank Transfer <span style="font-size:12px"> @if($bank_per_off > 0)
                                    ( {{$bank_per_off}}% off )
                                    @endif </span></span></label></div>
                                <div class="payment_body  d-none" id="bank-container" style="flex-direction: column; gap:10px; padding: 10px 20px 20px; align-items: flex-start; text-align: left;">
                                    This will provide you with account details where the payment will be manually approved.
                                </div>
                            </div>
                        @endif
                    </div>
                    <div id="payment_error" class="payment_error mt-2">
                        <!-- Display an error message to your customers here -->
                    </div>
                </div>
            </div>

            @if ($show_checkout_notice == 'yes')
                <div style="max-width: 450px;">
                    <div class="alert alert-warning">
                        {!! $checkout_notice !!}
                    </div>
                </div>
            @endif

            @guest('web')
                <div class="auth_region" style="max-width: 450px;">
                    <input type="password" style="display: none">
                    <h3 class="cart_header mt-4">CREATE AN ACCOUNT PASSWORD</h3>
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

            @php
                $checkout_checkbox = explode('||', $checkout_checkbox);
            @endphp
            @foreach ($checkout_checkbox as $box)
                @if (!empty(trim($box)))
                    <div class="mt-3 text-left d-flex gap-2"><input class="ckbox" required autocomplete="off"
                            type="checkbox">
                        <div style="flex:1"> {!! $box !!} <span class="text-danger">*</span></div>
                    </div>
                @endif
            @endforeach

            <div class="text-end mt-3" style="max-width: 450px;"><button id="complete_payment" class="btn  btn-primary pay_btn py-3 px-4"> Complete
                    Payment</button></div>
        </div>

        <div class="col-12 col-md-5 order-0 order-md-1">
            <div id="cfw-cart-summary" class="cart_summary" role="complementary">
                @if ($quote)
                    <x-quote-summery :quote="$quote" />
                @endif
                @if ($aiDoc)
                    <x-ai-summery :aiDoc="$aiDoc" :aiPrice="$aiPrice" />
                @endif
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
                    <div class="tab-content" id="authTabContent">
                        <div class="tab-pane fade show active" id="login" role="tabpanel"
                            aria-labelledby="login-tab">
                            <form onsubmit="loginForm(event)">
                                <p>Please enter your login details below.</p>
                                <div class="form-floating mb-3">
                                    <input style="padding-top: 32px !important" class="form-control"
                                        placeholder="Email address" name="username" id="username" required>
                                    <label for="username" class="form-label">Email Address</label>
                                </div>
                                <div class="form-floating  mb-3">
                                    <input style="padding-top: 32px !important" type="password" class="form-control"
                                        placeholder="Password" id="password" required>
                                    <label for="password" class="form-label">Password</label>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="rememberme">
                                        <label class="form-check-label" for="rememberme">Remember Me</label>
                                    </div>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#fgetModal"
                                        class="text-decoration-none">Forgot
                                        Password?</a>
                                </div>
                                <div class="sbutton"><button type="submit"
                                        class="btn btn-primary w-100 py-3 pay_btn">Login</button></div>
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

    @if ($show_home_notice == 'yes' && ($choosen_page_notice == 'checkout' || $choosen_page_notice == 'both'))
        <div class="modal fade" id="noticeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="noticeModalLabel" aria-hidden="true">
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
@endsection

@push('js')
    <script>
        let EMAIL_VERIFICATION_STATE = {{ $isEmailVerified ? 'true' : 'false' }};

        @if ($show_home_notice == 'yes' && ($choosen_page_notice == 'checkout' || $choosen_page_notice == 'both'))
            document.addEventListener("DOMContentLoaded", function() {
                const modal = new bootstrap.Modal(document.getElementById('noticeModal'));
                const closeBtn = document.getElementById('closeNoticeBtn');
                const noticeKey = 'noticeDismissedAt';
                const durationHours = 2;

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

                closeBtn.addEventListener('click', function() {
                    localStorage.setItem(noticeKey, new Date().toISOString());
                    modal.hide();
                });
            });
        @endif

        const ENTITY_ID = {{ $quote?->id ?? $aiDoc?->id }};
        const IS_AI_DOC = {{ $aiDoc ? 'true' : 'false' }};
        let THIS_CFR_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.getElementById('complete_payment').addEventListener('click', completePayment);

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

        async function completePayment() {
            closeError();
            showProgress('Preparing your checkout...');

            let ckret = false;
            document.querySelectorAll("input.ckbox").forEach(function(el) {
                if (!el.checked) {
                    el.focus();
                    ckret = true;
                }
            });
            if (ckret) {
                toastr.error('Please agree by checking all the boxes.');
                closeProgress();
                return;
            }

            if (document.getElementById("new_email")) {
                await userAuthentication();
            }

            // userAuthentication might have failed or needs verification, so we stop here if the element still exists
            if (document.getElementById("new_email")) {
                closeProgress();
                return;
            }

            if (!EMAIL_VERIFICATION_STATE) {
                $("#verifyModal").modal("show");
                $(".need-verify-msg").addClass('d-none');
                $(".resend-verify-email").removeClass('d-none');
                closeProgress();
                return;
            }

            let tipSlider = document.querySelector('#tip-slider');
            let tipAmount = parseFloat(tipSlider ? tipSlider.value : 0);

            let choice = $(`input[name="choice"]:checked`).val();

            if(choice == "bank"){
                showProgress('Generation invoice');

                $.ajax({
                    type: "POST",
                    url:   "/checkout-bank-payment",
                    data: {id: ENTITY_ID, type: (IS_AI_DOC ? 'ai' : 'quote'), tip: tipAmount},
                    dataType: 'json',
                    success: function(data){
                        let this_amount = parseFloat({{ number_format($quote?->cpw ?? $aiPrice, 2, '.', '') }}).toFixed(2);

                        let bank_per_off = parseInt({{$bank_per_off ?? 0}});
                        if(bank_per_off > 0){
                            this_amount =  this_amount * (1 - (bank_per_off / 100));
                        }

                        let html = `<div class="bank-details">
                                    <h5>Bank Transfer Details</h5>

                                    <p class="transfer-amount">Amount to Transfer: <strong>£${this_amount}</strong></p>

                                    <div class="detail-item">
                                        <div class="detail-left">
                                        <i class="fas fa-user me-2"></i>
                                        <span class="label">Account Name:</span>
                                        <span class="value copy-text">${data.bank_name}</span>
                                        </div>
                                        <i class="fas fa-copy copy-btn"></i>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-left">
                                        <i class="fas fa-code-branch me-2"></i>
                                        <span class="label">Sort Code:</span>
                                        <span class="value copy-text">${data.bank_sort_code}</span>
                                        </div>
                                        <i class="fas fa-copy copy-btn"></i>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-left">
                                        <i class="fas fa-hashtag me-2"></i>
                                        <span class="label">Account Number:</span>
                                        <span class="value copy-text">${data.bank_account_number}</span>
                                        </div>
                                        <i class="fas fa-copy copy-btn"></i>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-left">
                                        <i class="fas fa-pen me-2"></i>
                                        <span class="label">Reference:</span>
                                        <span class="value copy-text">${data.bank_ref_number}-${data.policy_number}</span>
                                        </div>
                                        <i class="fas fa-copy copy-btn"></i>
                                    </div>

                                    <div class="note d-none">
                                        Please use the reference exactly as shown when making your transfer.
                                    </div>

                                    <div class="info-message">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{$bank_infor_text ?? ''}}
                                    </div>

                                    <form action="/bank-confirm-payment/${data.policy_number}">
                                        <input type="hidden" name="type" value="{{isset($aiDoc) ? 'ai' : 'quote'}}">
                                        <div class="mt-3 text-left d-flex gap-2"><input required autocomplete="off" type="checkbox"> <div style="flex:1"> I confirm that I have sent the payment with the exact reference shown  <span class="text-danger">*</span></div></div>

                                        <div class="mt-3 text-left d-flex gap-2"><input required autocomplete="off" type="checkbox"> <div style="flex:1"> I acknowledge that the payment has to be manually approved, which may take up to 12 hours until the policy is active.  <span class="text-danger">*</span></div></div>

                                        
                                    <button class="confirm-btn">
                                        <i class="fas fa-check-circle me-1"></i> Confirm Order
                                    </button>
                                   </form> 
                                </div>`;

                                $("#payment_cal_path").html(html);
                                closeProgress();
                                $("#payment_cal_path")[0].scrollIntoView();

                        $('.copy-btn').click(function () {
                            const value = $(this).closest('.detail-item').find('.copy-text').text().trim();
                            const tempInput = $('<input>');
                            $('body').append(tempInput);
                            tempInput.val(value).select();
                            document.execCommand('copy');
                            tempInput.remove();
                            const icon = $(this);
                            icon.removeClass('fa-copy').addClass('fa-check');
                            setTimeout(() => {
                                icon.removeClass('fa-check').addClass('fa-copy');
                            }, 1000);
                        });
                    },
                    error: function (xhr, status, error) {
                        closeProgress();
                        try {
                            render_errors(JSON.parse(xhr.responseText), 'toast', $('body'));
                        } catch(e) {
                            showError('An error occurred while processing bank payment.');
                        }
                    }
                });
            }
            else{
                let payload = {
                    id: ENTITY_ID,
                    tip: tipAmount,
                     _token: THIS_CFR_TOKEN
                };

                if (IS_AI_DOC) {
                    payload.type = 'ai';
                }

                try {
                    const response = await fetch('/lemonsqueezy/create-checkout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': THIS_CFR_TOKEN
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();

                    if (response.ok && data.checkoutUrl) {
                        // Store payment data in localStorage before redirecting
                        // This allows the success page to confirm payment without relying on email/timeframe matching
                        const paymentData = {
                            entity_id: ENTITY_ID,
                            entity_type: IS_AI_DOC ? 'ai_document' : 'quote',
                            timestamp: Date.now()
                        };
                        try {
                            localStorage.setItem('lemonsqueezy_payment', JSON.stringify(paymentData));
                            console.log('Payment data stored in localStorage:', paymentData);
                        } catch (storageError) {
                            console.warn('Failed to store payment data in localStorage:', storageError);
                            // Continue anyway, webhook will be the fallback
                        }
                        
                        window.location.href = data.checkoutUrl;
                    } else {
                        showError(data.error || 'Could not create payment session.');
                        if(data.details && data.details.errors) {
                            let errorMsg = data.details.errors.map(e => e.detail).join('<br>');
                            showError(errorMsg);
                        }
                        closeProgress();
                    }
                } catch (error) {
                    console.error('Payment initialization failed:', error);
                    showError('An unexpected error occurred. Please try again.');
                    closeProgress();
                }
            }
        }

        async function userAuthentication() {
            let fdata = {
                id: ENTITY_ID
            };

            $(".form_error").remove();
            fdata["new_email"] = $("#new_email").val().trim();
            fdata["first_name"] = $("#new_first_name").val().trim();
            fdata["last_name"] = $("#new_last_name").val().trim();
            fdata["new_password"] = $("#new_password").val().trim();

            showProgress('Authenticating Session. Please wait');

            try {
                const res = await fetch('/lemonsqueezy/registration', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': THIS_CFR_TOKEN,
                    },
                    body: JSON.stringify(fdata),
                });

                if (!res.ok) {
                    const errorData = await res.json();
                    let omessage = errorData.message || '';
                    if (errorData.errors) {
                        Object.keys(errorData.errors).forEach(key => {
                            omessage += '<br>' + errorData.errors[key].join(', ');
                            $(`#${key}`).after(`<div class="form_error">${errorData.errors[key].join(', ')}</div>`);
                            if ($(`#${key}`).length) $(`#${key}`)[0].scrollIntoView();
                        });
                    }
                    toastr.error(omessage || 'An error occurred during registration.');
                    closeProgress();
                    return;
                }
                
                const { user_name, user_email, token } = await res.json();

                if (!user_email) {
                    toastr.error(`Error creating account`);
                    closeProgress();
                    return;
                }
                
                $("#user_email").val(user_email);
                $("#user_name").val(user_name);
                $(".auth_region").remove();

                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': token } });
                THIS_CFR_TOKEN = token;

                $("#login_region").html(
                    `<div style="font-size: 16px;">Your account has been created, and you are currently logged in as  <b>${user_name}</b> <span class="verify_email_address">(${user_email})</span></div>`
                );
                
                $(".verify_email_address").val(user_email).text(user_email);
                $("#verifyModal").modal("show");
                $(".need-verify-msg").addClass('d-none');
                $(".resend-verify-email").removeClass('d-none');

            } catch (error) {
                toastr.error(`Error: ${error.message}`);
            } finally {
                // It might not be correct to always close progress here, depends on flow
                // closeProgress();
            }
        }
    </script>
@endpush