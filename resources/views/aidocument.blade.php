@php
    $user = auth('web')->user();
@endphp
@extends('templates.page')


@push('meta')
    <title>My Account - {{ config('app.name') }}</title>
@endpush

@push('css')
    {{-- Extra css files here --}}

    {{-- Login Reg --}}
    <link rel='stylesheet' id='elementor-post-1306-css'
        href='/uploads/elementor/css/post-1306b08c.css?ver={{ config('app.version') }}' type='text/css' media='all' />
    <link rel="stylesheet"
        href="/plugins/elementor-pro/assets/css/widget-woocommerce.min.css?ver={{ config('app.version') }}">

    {{-- <script src="https://cdn.paddle.com/paddle/paddle.js"></script> --}}
    <script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>
    {{-- <script src="https://cdn.paddle.com/paddle/paddle-v2.js"></script> --}}
    <style>
        .generated-doc-content {
            background-color: #fafbfb;
            padding: 20px;
            max-height: 800px;
            overflow-x: auto;
            border-radius: 10px;
        }

        .generated-doc-content h1 {
            font-weight: bold;
            margin-bottom: 30px;
            color: var(--gtheme-color);
        }

        .generated-doc-content h3,
        .generated-doc-content h2 {
            color: var(--gtheme-color);
        }
        .ai-button-group button{position: relative;}
        .ai-button-group button:hover::before{width:100%;}
        .ai-button-group button:before{
            content: '';
            transition: all 0.2s;
            width: 0%;
            height: 100%;
            position: absolute;
            left:0;
            top:0;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
    </style>
@endpush




@section('content')
    <div data-elementor-type="wp-page" data-elementor-id="1306" class="elementor elementor-1306"
        data-elementor-post-type="page">
        <section>
            <div class="container py-5">
                <!-- Heading Section -->
                <div class="text-center mb-4">
                    <span class="badge mb-2"
                        style="border-radius: 100px; background-color: #40a3ba42; color: var(--gtheme-color); font-weight: 300; font-size: 16px; padding: 13px 33px;">
                        <svg width="15" height="15" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                        AI-Powered Document Generation</span>
                    <h1 class="fw-bold">Transform Ideas into <br /><span class=""
                            style="color:var(--gtheme-color)">Professional Documents</span></h1>
                    <p class="text-muted fs-5" style="max-width:700px; margin:0 auto;">
                        Our advanced AI technology creates high-quality, personalized documents in seconds. From business
                        proposals to technical specifications, get professionally formatted content instantly.
                    </p>
                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <span class="text-success"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-clock w-4 h-4 sm:w-5 sm:h-5 text-teal-600">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg> <span class="text-black">Instant Generation</span></span>
                        <span class="text-success"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-shield w-4 h-4 sm:w-5 sm:h-5 text-teal-600">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path>
                            </svg> <span class="text-black">Professional Quality</span></span>
                    </div>
                </div>

                <!-- Pricing Box -->
                <div class="row justify-content-center mb-5">
                    <div class="col-md-10">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="text-center mb-0"><strong>How it Works</strong></h5>
                                <p class="text-center mb-4 mt-2">Generate unlimited documents for free, download when ready
                                </p>


                                <div class="row justify-content-center">
                                    <div class="col-md-10 ">
                                        <div class="row text-left ai-features">
                                            <div class="col-md-6 ">
                                                <div class="rounded border p-3 bg-success bg-opacity-25 position-relative mb-3 mb-sm-0"
                                                    style="border-color:rgb(187 247 208 / var(--tw-border-opacity, 1)) !important;">
                                                    <span class="badge bg-success mb-2 position-absolute"
                                                        style="left:20px; top:-7px; border-radius:3px; background-color:var(--gtheme-color);padding:8px;">FREE</span>
                                                    <h6 style="display:flex; align-items: center;" class="fw-bold mt-4"><svg
                                                            style="color: rgb(22 163 74 / var(--tw-text-opacity, 1)); background-color: rgb(220 252 231 / var(--tw-bg-opacity, 1)); border-radius: 18px; padding: 5px; margin-right: 6px;"
                                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-eye w-4 h-4 text-green-600">
                                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>Generate & Preview</h6>
                                                    <ul class="list-unstyled text-muted small">
                                                        <li>✔ Unlimited document generation</li>
                                                        <li>✔ Full preview & editing</li>
                                                        <li>✔ No time limits</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="rounded border p-3 bg-success bg-opacity-10 position-relative"
                                                    style="border-color: rgb(153 246 228 / var(--tw-border-opacity, 1)) !important;">
                                                    <span class="badge mb-2 position-absolute left-0"
                                                        style="left:20px; top:-7px; border-radius:3px; background-color:var(--gtheme-color);padding:8px;">£10</span>
                                                    <h6 style="display:flex; align-items: center;" class="fw-bold mt-4"><svg
                                                            style="color: rgb(13 148 136 / var(--tw-text-opacity, 1)); background-color: rgb(204 251 241 / var(--tw-bg-opacity, 1)); border-radius: 18px; padding: 5px; margin-right: 6px;"
                                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-download w-4 h-4 text-teal-600">
                                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                            <polyline points="7 10 12 15 17 10"></polyline>
                                                            <line x1="12" x2="12" y1="15"
                                                                y2="3"></line>
                                                        </svg>Professional PDF</h6>
                                                    <ul class="list-unstyled text-muted small">
                                                        <li>✔ High-quality PDF format</li>
                                                        <li>✔ Print-ready quality</li>
                                                        <li>✔ Instant download</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-info text-center mt-3 small">
                                            Perfect for trying before buying. Generate and perfect your document completely
                                            free,
                                            then pay only when you’re satisfied and ready to download.
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

                <!-- Generate Document Section -->
                <div class="card shadow-sm mb-4" x-data='aiDocumentCallback(@json(Auth::check()), @json(Auth::user()?->email))'>


                    <div :class="loading ? 'd-flex' : 'd-none'"
                        class="loader-overlay d-flex justify-content-center align-items-center" x-show="loading" x-cloak
                        style="position: absolute; inset: 0; background-color: rgba(255, 255, 255, 0.8); z-index: 10;">
                        <div class="loader fw-semibold fs-5" style="color:var(--gtheme-color);">
                            <span>
                                <span class="spinner-border spinner-border-sm me-2" role="status"
                                    aria-hidden="true"></span>
                                Generating...
                            </span>
                        </div>
                    </div>



                    <template x-if="!generatedDoc">
                        <div>
                            <div class="card-header text-white fw-semibold" style="background-color:var(--gtheme-color)">
                                <h3><strong>Generate Your Document</strong></h3>
                                <p>Describe what you need and let our AI create it for you</p>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="docPrompt" class="form-label fw-semibold">What type of document do you
                                        need?</label>
                                    <textarea class="form-control" id="docPrompt" x-model="docPrompt"
                                        placeholder="e.g., A comprehensive business proposal for a tech startup, a detailed marketing strategy for a mobile app launch, a technical specification document..."></textarea>
                                    <div class="form-text text-end mt-1">
                                        <a href="#" class="text-decoration-none">📎 Attach Files</a>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-semibold mb-2">Quick Start Templates</h6>
                                    <div class="list-group">
                                        <button
                                            @click="docPrompt='Write a comprehensive marketing strategy for a new mobile app'"
                                            class="list-group-item list-group-item-action my-2">📝 Write a comprehensive
                                            marketing strategy for a new mobile app</button>
                                        <button
                                            @click="docPrompt='Create a detailed technical specification for a web platform'"
                                            class="list-group-item list-group-item-action my-2">🛠 Create a detailed
                                            technical specification for a web platform</button>
                                        <button
                                            @click="docPrompt='Draft a professional investor pitch deck for a fintech startup'"
                                            class="list-group-item list-group-item-action my-2">💼 Draft a professional
                                            investor pitch deck for a fintech startup</button>
                                        <button
                                            @click="docPrompt='Develop a strategic business expansion plan for international markets'"
                                            class="list-group-item list-group-item-action my-2">🌍 Develop a strategic
                                            business expansion plan for international markets</button>
                                    </div>
                                </div>



                                <div class="text-center">
                                    <button id="aiDocumentGeneratBtn"
                                        @click="isAuth ? generateDocument($event) : showLoginModal()"
                                        {{-- @if ($isAuth) @click="generateDocument" @else @click="new bootstrap.Modal(document.getElementById('authModal')).show()" @endif --}}
                                        style="background-color:var(--gtheme-color); border-width:0; color:white;"
                                        :class="{ 'opacity-50 cursor-not-allowed': !docPrompt.trim() }"
                                        :disabled="!docPrompt.trim()" class="btn btn-default btn-lg px-5">🧠 Generate
                                        Document</button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="generatedDoc">
                        <div>
                            <div
                                class="card-header text-white fw-semibold d-flex justify-content-between align-items-center" style="background-color:var(--gtheme-color);">
                                <div
                                    class="text-white px-4 px-sm-5 py-4 d-flex justify-content-between w-100 flex-column gap-3 flex-sm-row align-items-sm-center justify-content-sm-between w-full">
                                    <div class="d-flex align-items-center gap-3">
                                        <div
                                            class="d-flex align-items-center justify-content-center rounded bg-white-opacity size-default size-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-file-text w-4 h-4 sm:w-5 sm:h-5 text-white">
                                                <path
                                                    d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z">
                                                </path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" x2="8" y1="13" y2="13">
                                                </line>
                                                <line x1="16" x2="8" y1="17" y2="17">
                                                </line>
                                                <line x1="10" x2="8" y1="9" y2="9">
                                                </line>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg sm:text-xl font-bold text-white mb-0">Your Document is
                                                Ready!
                                            </h3>
                                            <p class="text-emerald-100 text-sm sm:text-base mb-0">Review your generated
                                                content
                                                below</p>
                                        </div>
                                    </div>
                                    <div class="d-grid flex-column gap-2 flex-sm-row ai-button-group" style="gap:20px; grid-template-columns: repeat(2, 1fr);">
                                        <div>
                                            <button @click="editRequest($event)" style="border-color:transparent;"
                                                class="w-100 w-sm-auto d-flex align-items-center justify-content-center gap-2 flex-wrap rounded border-0 px-4 py-2 bg-white text-teal fw-semibold text-sm text-sm-sm"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-pen-line w-4 h-4">
                                                    <path d="M12 20h9"></path>
                                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                                </svg><span>Edit Request</span>
                                            </button>
                                        </div>


                                        {{-- //@click="handlePaddleCheckout($event)" // Old click handler --}}
                                        <div>
                                            <button style="border-color:transparent; color:var(--gtheme-color);" id="pdfDownloadBtn" x-ref="downloadBtn"
                                                @click="goToCheckout()" 
                                                    {{-- @click="handlePaddleCheckout($event)" --}}
                                                    data-price="{{ $ai_document_price }}"
                                                    data-title="Customize AI Document"
                                                    class="w-100 w-sm-auto d-flex align-items-center justify-content-center gap-2 text-nowrap rounded px-4 py-2 bg-white fw-semibold fs-6 h-auto user-select-none border-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-download w-4 h-4">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                        <polyline points="7 10 12 15 17 10"></polyline>
                                                        <line x1="12" x2="12" y1="15" y2="3">
                                                        </line>
                                                    </svg>
                                                    <span x-text="'Download PDF - £' + (parseFloat({{ $ai_document_price }}) + parseFloat(tipAmount)).toFixed(2)">
                                                    </span>
                                            </button>
                                           
                                        </div>

                                        


                                    </div>
                                    
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="p-3">
                                    <div x-html="generatedDoc" class="generated-doc-content"></div>
                                </div>
                            </div>
                        </div>
                    </template>



                    <!-- Modal -->
                    <div class="modal fade auth_modal" id="authModal" tabindex="-1" aria-labelledby="authModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="authModalLabel"><b>WELCOME BACK</b></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs mb-3 justify-content-center d-none" id="authTab"
                                        role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="login-tab" data-bs-toggle="tab"
                                                data-bs-target="#login" type="button" role="tab"
                                                aria-controls="login" aria-selected="true">
                                                Login
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="register-tab" data-bs-toggle="tab"
                                                data-bs-target="#register" type="button" role="tab"
                                                aria-controls="register" aria-selected="false">
                                                Register
                                            </button>
                                        </li>
                                    </ul>

                                    <!-- Tab Content -->
                                    <div class="tab-content ai-doc" id="authTabContent">
                                        <!-- Login Form -->
                                        <div class="tab-pane fade show active" id="login" role="tabpanel"
                                            aria-labelledby="login-tab">
                                            <form @submit.prevent="loginForm">
                                                <p>Please enter your login details below.</p>
                                                <div class="form-floating mb-3">
                                                    <input style="padding-top: 32px !important" class="form-control"
                                                        placeholder="Email address" x-model="username" name="username" id="username"
                                                        required>
                                                    <label for="username" class="form-label">Email Address</label>
                                                </div>
                                                <div class="form-floating  mb-3">
                                                    <input style="padding-top: 32px !important" type="password"
                                                        class="form-control" x-model="password" placeholder="Password" id="password"
                                                        required>
                                                    <label for="password" class="form-label">Password</label>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" x-model="rememberme" id="rememberme">
                                                        <label class="form-check-label" for="rememberme">Remember
                                                            Me</label>
                                                    </div>
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#fgetModal"
                                                        class="text-decoration-none">Forgot
                                                        Password?</a>
                                                </div>
                                                <div class="sbutton"><button type="submit"
                                                        class="btn btn-primary w-100 py-3 pay_btn">Login</button></div>
                                            </form>
                                        </div>

                                        <!-- Register Form -->
                                        <div class="tab-pane fade d-none" id="register" role="tabpanel"
                                            aria-labelledby="register-tab">
                                            <form onsubmit="registerForm(event)">
                                                <div class="mb-3">
                                                    <label for="reg_first_name" class="form-label">First Name</label>
                                                    <input type="text" class="form-control" name="first_name"
                                                        id="reg_first_name" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="reg_last_name" class="form-label">Last Name</label>
                                                    <input type="text" class="form-control" name="last_name"
                                                        id="reg_last_name" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="reg_email" class="form-label">Email address</label>
                                                    <input type="email" class="form-control" name="email"
                                                        id="reg_email" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="reg_password" class="form-label">Password</label>
                                                    <input type="password" class="form-control" name="password"
                                                        id="reg_password" required>
                                                </div>
                                                <div class="sbutton"><button type="submit"
                                                        class="btn btn-success w-100 py-3 ">Register</button></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer ">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
@endsection('content')



@push('js')
    <script>
        window.aiDocumentCallback = function(isAuthenticated, userEmail) {
            return {
                isAuth: isAuthenticated,
                docPrompt: '',
                loading: false,
                generatedDoc: '',
                username:'', 
                password:'', 
                rememberme:false,
                uuid: '',
                email:userEmail,
                token:'',
                tipAmount: 0,
                async init() {
                    window.authAlpine = this;
                    try {

                        const res = await fetch('/pp/paddle/token', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            credentials: 'same-origin',
                        });

                        const data = await res.json();

                        if (!data.token) {
                            console.error("❌ No Paddle token received");
                            return;
                        }

                        if(data.paddle_mode != 'live'){
                            Paddle.Environment.set("sandbox"); // Remove for live
                        }
                        Paddle.Initialize({
                            token: data.token,
                            eventCallback: function(event) {
                                if (event.type === "checkout.error") {
                                    console.error("Paddle Checkout Error:", event);
                                }
                            }
                        });
                    } catch (e) {
                        console.error("Paddle init error:", e);
                    }
                },
                async showLoginModal(){
                    const modal = new bootstrap.Modal(document.getElementById('authModal'));
                    modal.show();
                },
                async loginForm(event){
                    event.preventDefault();

                    let parent = $(event.target).closest('form');
                    parent.addClass('was-validatedOmarFaruque');
                    parent.css("opacity", "0.5").css("pointer-events", "none");
                    var sbutton = parent.find(".sbutton").html(); 
                    $(".form_error, .formError").remove();
                    parent.find(".sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Logging in...');
                    
                    const fdata = {
                        username: this.username,
                        password: this.password,
                        rememberme: this.rememberme
                    }
                    fetch('/login', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify(fdata)
                    })
                    .then(response => {
                        if (!response.ok) throw response;
                        return response.json();
                    })
                    .then(data => {
                        if(data.status){
                            this.isAuth = true;
                            this.email = data.email;

                            const modal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
                            if(modal) modal.hide();
                            this.token = data.token;

                            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                            if(tokenMeta) tokenMeta.setAttribute('content', data.token);
                        }
                    }).catch(async (err) => {
                        
                        if (err.json) {
                            const json = await err.json();
                            parent.css("opacity", "1").css("pointer-events", "auto");
                            parent.find(".sbutton").html(sbutton);
                            render_errors(json, 'toast', parent);
                        } else {
                            toastr.error("Something went wrong.");
                        }
                    });
                },
                async editRequest() {
                    this.generatedDoc = '';
                },
                async generateDocument() {
                    if (!this.docPrompt.trim()) return;

                    this.loading = true;
                    this.generatedDoc = '';

                    try {   
                        const response = await fetch('/generate-ai-document', {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                prompt: this.docPrompt
                            })
                        });

                        const data = await response.json();
                        this.generatedDoc = data?.content || '⚠️ No response';
                        this.uuid = data?.uuid || 'No Response';
                    } catch (error) {
                        console.error(error);
                        this.generatedDoc = '❌ Error generating document.';
                    }

                    this.loading = false;
                },
                async handlePaddleCheckout() {
                    if (!this.generatedDoc) return alert("Generate the document first.");


                    const button = event.currentTarget;

                    const price = parseFloat(button.dataset.price);
                    const title = button.dataset.title || "Untitled Document";
                    const email = button.dataset.email || null;


                    const res = await fetch('/generate-pay-link', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    });

                    const data = await res.json();

                    if (data.error) return alert("❌ " + data.error);


                    var itemsList = [{
                        priceId: data.price_id,
                        quantity: 1
                    }];


                    try {
                        Paddle.Checkout.open({
                            settings: {
                                displayMode: "overlay",
                                theme: "light",
                                locale: "en",
                                successUrl: data.success_url
                            },
                            items: itemsList,
                            customData: {
                                doc_uuid: this.uuid
                            },
                        });
                    } catch (error) {
                        console.error("❌ Checkout threw error:", error);
                    }

                    // Redirect to Paddle Pay Link
                    // window.location.href = data.url;
                },

                goToCheckout() {
                    const url = new URL('{{ route('checkout.page') }}');
                    url.searchParams.append('tip', this.tipAmount);
                    window.location.href = url.toString();
                }

            }
        }
    </script>
@endpush
