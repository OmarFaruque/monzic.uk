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
@endpush




@section('content')
    <div data-elementor-type="wp-page" data-elementor-id="1306" class="elementor elementor-1306"
        data-elementor-post-type="page">



        <section>
            <div class="container py-5 text-center">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card shadow-lg border-0">
                            <div class="card-body py-5 px-4">
                                <h1 class="text-success fw-bold mb-3">
                                    🎉 Payment Successful!
                                </h1>

                                <p class="lead mb-4">
                                    Thank you for your purchase. Your AI-generated document is ready for download.
                                </p>

                                <a href="{{ route('download.document') }}" class="btn btn-primary btn-lg">
                                    📥 Download Your Document
                                </a>

                                <div class="mt-4">
                                    <a href="{{ url('/') }}" class="text-decoration-none text-muted">
                                        ⬅️ Back to Home
                                    </a>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection('content')



@push('js')
    {{-- extra jss files here --}}
@endpush
