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
    <link rel="stylesheet" href="/plugins/elementor-pro/assets/css/widget-woocommerce.min.css?ver={{ config('app.version') }}">

    {{-- <script src="https://cdn.paddle.com/paddle/paddle.js"></script> --}}
    <script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>
    {{-- <script src="https://cdn.paddle.com/paddle/paddle-v2.js"></script> --}}

@endpush




@section('content')


<a href='#' 
  class='paddle_button'
  data-display-mode='overlay'
  data-theme='light'
  data-locale='en'
  data-items='[
    {
      "priceId": "pri_01jz8wrh3rcztfxz50veyerk6h",
      "quantity": 1
    }
  ]'
>
  Buy now
</a>



    <div data-elementor-type="wp-page" data-elementor-id="1306" class="elementor elementor-1306"
        data-elementor-post-type="page">
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-5b3eada hide-logged-out elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="5b3eada" data-element_type="section">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-8d5a021"
                    data-id="8d5a021" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-2e88fe6 elementor-align-center elementor-widget__width-inherit elementor-widget-mobile__width-initial elementor-widget elementor-widget-button"
                            data-id="2e88fe6" data-element_type="widget" data-widget_type="button.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-button-wrapper">
                                    <a class="elementor-button elementor-button-link elementor-size-sm"
                                        href="/my-account/orders">
                                        <span class="elementor-button-content-wrapper">
                                            <span class="elementor-button-icon">
                                                <i aria-hidden="true" class="far fa-file-alt"></i> </span>
                                            <span class="elementor-button-text">View orders</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-37347fe"
                    data-id="37347fe" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-6a1849c elementor-align-center elementor-widget__width-inherit elementor-widget elementor-widget-button"
                            data-id="6a1849c" data-element_type="widget" data-widget_type="button.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-button-wrapper">
                                    <a class="elementor-button elementor-button-link elementor-size-sm"
                                        href="/my-account/edit-account">
                                        <span class="elementor-button-content-wrapper">
                                            <span class="elementor-button-icon">
                                                <i aria-hidden="true" class="fas fa-user-circle"></i> </span>
                                            <span class="elementor-button-text">Edit Account</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-82a0911"
                    data-id="82a0911" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-e55ad50 elementor-align-center elementor-widget__width-inherit elementor-widget elementor-widget-button"
                            data-id="e55ad50" data-element_type="widget" data-widget_type="button.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-button-wrapper">
                                    <a class="elementor-button elementor-button-link elementor-size-sm"
                                        href="/my-account/logout">
                                        <span class="elementor-button-content-wrapper">
                                            <span class="elementor-button-icon">
                                                <i aria-hidden="true" class="fas fa-sign-out-alt"></i> </span>
                                            <span class="elementor-button-text">Log Out</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section>
            <div class="container py-5">
                <!-- Heading Section -->
                <div class="text-center mb-4">
                    <span class="badge bg-success mb-2">AI-Powered Document Generation</span>
                    <h1 class="fw-bold">Transform Ideas into <span class="text-success">Professional Documents</span></h1>
                    <p class="text-muted fs-5">
                        Our advanced AI technology creates high-quality, personalized documents in seconds. From business
                        proposals to technical specifications, get professionally formatted content instantly.
                    </p>
                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <span class="text-success"><i class="bi bi-bolt-fill"></i> Instant Generation</span>
                        <span class="text-success"><i class="bi bi-award-fill"></i> Professional Quality</span>
                    </div>
                </div>

                <!-- Pricing Box -->
                <div class="row justify-content-center mb-5">
                    <div class="col-md-10">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="text-center mb-4">How it Works</h5>
                                <div class="row text-center">
                                    <div class="col-md-6 border-end">
                                        <span class="badge bg-success mb-2">FREE</span>
                                        <h6 class="fw-bold">Generate & Preview</h6>
                                        <ul class="list-unstyled text-muted small">
                                            <li>✔ Unlimited document generation</li>
                                            <li>✔ Full preview & editing</li>
                                            <li>✔ No time limits</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="badge bg-primary mb-2">£10</span>
                                        <h6 class="fw-bold">Professional PDF</h6>
                                        <ul class="list-unstyled text-muted small">
                                            <li>✔ High-quality PDF format</li>
                                            <li>✔ Print-ready quality</li>
                                            <li>✔ Instant download</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="alert alert-info text-center mt-3 small">
                                    Perfect for trying before buying. Generate and perfect your document completely free,
                                    then pay only when you’re satisfied and ready to download.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Generate Document Section -->
                <div class="card shadow-sm mb-4" x-data="aiDocumentCallback()">
                    <template x-if="!generatedDoc">
                        <div>
                            <div class="card-header bg-success text-white fw-semibold">
                                Generate Your Document
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
                                    <button @click="generateDocument"
                                        :class="{ 'opacity-50 cursor-not-allowed': !docPrompt.trim() }"
                                        :disabled="!docPrompt.trim()" class="btn btn-success btn-lg px-5">🧠 Generate
                                        Document</button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="generatedDoc">
                        <div>
                            <div
                                class="card-header bg-success text-white fw-semibold d-flex justify-content-between align-items-center">
                                <div
                                    class="bg-gradient text-white px-4 px-sm-5 py-4 d-flex flex-column gap-3 flex-sm-row align-items-sm-center justify-content-sm-between w-full">
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
                                            <h3 class="text-lg sm:text-xl font-bold text-white">Your Document is Ready!
                                            </h3>
                                            <p class="text-emerald-100 text-sm sm:text-base">Review your generated content
                                                below</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-3">
                                        <button
                                            class="ring-offset-background focus-visible:outline-hidden focus-visible:ring-ring gap-2 whitespace-nowrap rounded-md font-medium transition-colors focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:text-accent-foreground border px-4 py-2 border-teal-300 text-teal-100 hover:bg-teal-500 hover:border-white bg-transparent flex items-center justify-center space-x-2 text-sm sm:text-base h-12 touch-manipulation"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pen-line w-4 h-4">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                            </svg><span>Edit Request</span>
                                        </button>
                                        

                                        @php
                                            $isAuth = Auth::check();
                                            $email = $isAuth ? Auth::user()->email : '';
                                        @endphp
                                        <button
                                            @if($isAuth)
                                                @click="handlePaddleCheckout($event)"
                                                data-email="{{ $email }}"
                                            @else
                                                @click="window.location.href='{{ route('myAccount') }}'"
                                            @endif
                                            data-price="{{ $ai_document_price }}"
                                            data-title="Customize AI Document"
                                            class="ring-offset-background focus-visible:outline-hidden focus-visible:ring-ring gap-2 whitespace-nowrap rounded-md transition-colors focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 px-4 py-2 bg-white text-teal-700 hover:bg-gray-50 flex items-center justify-center space-x-2 font-semibold text-sm sm:text-base h-12 touch-manipulation"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-download w-4 h-4">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                <line x1="12" x2="12" y1="15" y2="3"></line>
                                            </svg>
                                            <span>
                                                {{ $isAuth ? 'Download PDF - £' . $ai_document_price : 'Login to Download PDF' }}
                                            </span>
                                        </button>

                                       
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="p-5">
                                    <div x-html="generatedDoc" class="generated-doc-content"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </section>
    </div>
@endsection('content')



@push('js')
    {{-- extra jss files here --}}

        <script>
        // Set your Paddle environment and seller ID
        // Paddle.Environment.set('sandbox'); // remove this in live mode
        // Paddle.Setup({ vendor: 28742 }); // replace with your Paddle vendor ID
Paddle.Environment.set("sandbox");
            Paddle.Initialize({
                token: 'test_37f6eedf8d3e718977bba94d8d5',
                eventCallback: function(data) {

                    console.log('error is: ', data);
                    if (data.type === 'checkout.error') {
                        console.error('Paddle Checkout Error:', data);
                    }
                }
            });

            var itemsList = [
                {
                    price_id: 'pri_01jz994qpr51zcbgddyezjhpn8',
                    quantity: 1
                }
            ];
    Paddle.Checkout.open({
            items: itemsList,
            settings: {
                displayMode: "overlay",
                successUrl: 'https://044a-103-16-24-142.ngrok-free.app/'
            }
        });


       
    </script>
@endpush
