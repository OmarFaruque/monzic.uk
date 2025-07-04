@php
    if (Auth::check()) {
        $authuser = auth::user();
    } else {
        $authuser = null;
    }

    $verificationCode = session('verification_code');
    $createdAt = session('created_at');
    $sessionEmail = session('verification_email');
    $isWithinTwoHours = false;
    if ($createdAt) {
        $isWithinTwoHours = \Carbon\Carbon::parse($createdAt)->addHours(2)->isFuture();
    }
    if (Auth::check()) {
        // User verified already
        if ($authuser->email_verified_at != null) {
            $verificationCode = false;
            $isWithinTwoHours = false;
        } elseif (strtolower($authuser->email) != strtolower($sessionEmail)) {
            // This session is not cor login user
            // So we invalidate it
            $verificationCode = false;
            $isWithinTwoHours = false;
        }
    }
// phpinfo(); die();


@endphp

<!doctype html>
<html class="no-js" lang="en-GB" dir="ltr"><!--<![endif]-->

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* ROOT THEME COLOR */
        :root {
            --gtheme-color: {{ config('app.theme_color') }};
            --gtheme-color-dark: {{ config('app.theme_color_dark') }};
            --gtheme-color-light: {{ config('app.theme_color_light') }};
            --gtheme-color-light-2: {{ config('app.theme_color_light_2') }};
            --gtheme-color-box: {{ config('app.theme_color_box') }};
            --gtheme-color-2: {{ config('app.theme_color_2') }};
            
            
        }
        .asin{
            color: rgba(27, 69, 67, 0.4);

            color: #2d8194;

            color: #4daca3;
            font-style: italic;
            text-decoration: underline;

        }
    </style>



    <link rel="apple-touch-icon" sizes="57x57" href="/img/icon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/img/icon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/img/icon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/icon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/img/icon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/img/icon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/img/icon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/img/icon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/icon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/img/icon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/img/icon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/icon/favicon-16x16.png">
    {{-- <link rel="manifest" href="/manifest.js?ver={{config('app.version')}}"> --}}
    <meta name="msapplication-TileColor" content="var(--gtheme-color)">
    <meta name="msapplication-TileImage" content="/img/icon/ms-icon-144x144.png">
    <meta name="theme-color" content="var(--gtheme-color)">


    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('meta')


    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link  href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&amp;family=Roboto:wght@300;400;500&amp;display=swap"
        rel="stylesheet">
    
    
        {{-- <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css?ver={{config('app.version')}}"> --}}

    <link rel="stylesheet" href="/themes/mg/js/timepicker.css?ver={{config('app.version')}}" />

    <style>img:is([sizes="auto" i], [sizes^="auto," i]) { contain-intrinsic-size: 3000px 1500px }</style>


    {{-- <link rel='stylesheet' id='text-editor-style-css'
        href='/plugins/metform/public/assets/css/text-editor9086.css?ver={{config('app.version')}}' type='text/css' media='all' />
   --}}

     {{-- JQUERY UI --}}
     <link rel='stylesheet' id='jquery-ui-style-css' href='/plugins/woo-extra-product-options/public/assets/css/jquery-ui/jquery-ui9704.css?ver={{config('app.version')}}' type='text/css' media='all' />


   {{-- Font assome --}}
   <link rel='stylesheet' id='font-awesome-5-all-css'
        href='/plugins/elementor/assets/lib/font-awesome/css/all.mind5d5.css?ver={{config('app.version')}}' type='text/css'
        media='all' />
        {{-- <link rel='stylesheet' id='elementor-icons-fa-brands-css'
        href='/plugins/elementor/assets/lib/font-awesome/css/brands.min52d5.css?ver={{config('app.version')}}' type='text/css'
        media='all' />
        <link rel='stylesheet' id='font-awesome-4-shim-css'
        href='/plugins/elementor/assets/lib/font-awesome/css/v4-shims.mind5d5.css?ver={{config('app.version')}}' type='text/css'
        media='all' /> --}}
    

   {{-- Main Style Sheet --}}
   <link rel='stylesheet' id='style-css' href='/themes/mg/style9704.css?ver={{config('app.version')}}' type='text/css' media='all' />

   {{-- FRONT END  ALL --}}
    <link rel='stylesheet' id='elementor-frontend-css'
        href='/plugins/elementor/assets/css/frontend-lite.mind5d5.css?ver={{config('app.version')}}' type='text/css' media='all' />


      {{-- FOOTER    --}}
    <link rel='stylesheet' id='elementor-post-6-css' href='/uploads/elementor/css/post-69620.css?ver={{config('app.version')}}'
        type='text/css' media='all' />
    <link rel='stylesheet' id='elementor-post-2469-css' href='/uploads/elementor/css/post-2469b919.css?ver={{config('app.version')}}'
        type='text/css' media='all' />
    




    
    {{-- <script type="text/javascript"
        src="/plugins/woocommerce/assets/js/jquery-blockui/jquery.blockUI.mina7df.js?ver={{config('app.version')}}"
        id="jquery-blockui-js" defer="defer" data-wp-strategy="defer"></script> --}}







        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <!-- Load your combined static JS -->
        <script src="{{ secure_asset('js/script.js') }}"></script>
    
        <link rel="stylesheet" href="/css/bootstrap.min.css?ver={{config('app.version')}}">

        <link rel="stylesheet" href="/css/toastr.min.css?ver={{config('app.version')}}">


        <style>
            .form_error, .formError{font-size: 12px; color: red !important}
            

            /* ==================== # Page modal ================== */
            .page-modal{
            background: linear-gradient(to right, rgba(2,2,2,0.7), rgba(2,2,2,0.8));
            box-sizing: border-box;

            }
            .page-modal *{
                box-sizing: border-box;
            }
            .page-modal .modal-content {
            background: #FFFFFF;
            border-radius: 16px;
            max-width: 450px;
            }
            .page-modal .modal-content .modal-header h4 {
            font-size: 18px;
            }
            .page-modal .modal-title {
            font-style: normal;
            font-weight: 700;
            font-size: 16px;
            line-height: 170%;
            text-align: center;
            letter-spacing: -0.02em;
            color: #000000;
            }
            .page-modal  .form-container {
            display: inline-block;
            width: 100%;
            max-width: 400px;
            padding: 10px 20px;
            }
            .page-modal .modal-title-info {
            font-style: normal;
            font-weight: 400;
            font-size: 13px;
            line-height: 16px;
            align-items: center;
            text-align: center;
            letter-spacing: -0.02em;
            color: #3f3e3e;
            }

            .page-modal .text-link {
            font-style: normal;
            font-weight: 400;
            font-size: 14px;
            line-height: 16px;
            letter-spacing: -0.02em;
            color: var(--gtheme-color);
            text-decoration: none;
            cursor: pointer;
            }

            .page-modal .text-normal {
            font-style: normal;
            font-weight: 400;
            font-size: 14px;
            line-height: 16px;
            letter-spacing: -0.02em;
            color: #141B34;
            }

            .page-modal .btn-close {
            position: absolute;
            right: 20px;
            top: 20px;
            color: #FFF !important;
            }
            .page-modal .sx-btn-alt{
                background-color: var(--gtheme-color);
                color: #FFF;
            }
            .digit6 {
                display: flex;
                gap: 10px;
                justify-content: center;
                }

                .digit6 input {
                width: 50px;
                height: 50px;
                text-align: center;
                font-size: 24px;
                border: 2px solid #ccc;
                border-radius: 10px;
                opacity: 0.4;
                padding: 1rem 1rem !important;
                transition: opacity 0.3s, box-shadow 0.3s;
                }

                /* For Chrome, Safari, Edge, and Opera */
                .digit6 input::-webkit-outer-spin-button,
                .digit6 input::-webkit-inner-spin-button {
                    -webkit-appearance: none;
                    margin: 0;
                }
                /* For Firefox */
                .digit6 input {
                    -moz-appearance: textfield;
                }

                .digit6 input.filled {
                opacity: 1;
                border: 1px solid blue !important;
                box-shadow: 0 0 5px 2px rgba(0, 123, 255, 0.5); /* Blue shade box-shadow */
                }

            .area-overlay{
            width: 100%;
            height: 100%;
            position: absolute;
            z-index: 999999;
            left: 0px;
            top: 0px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to right, rgba(161, 160, 160, 0.6), rgba(140, 138, 138, 0.6));
            color: #FFF;
            font-size: 40px;
            }
            .area-overlay .message{
            font-size: 18px;
            font-weight: bold;
            }

        </style>

        @stack('css')
</head>

<body
    class="home omar page-template page-template-elementor_header_footer page page-id-85265199 wp-custom-logo theme-mg woocommerce-no-js logged-out-condition elementor-default elementor-template-full-width elementor-kit-6 elementor-page elementor-page-85265199">
    <div id="header">
        <div class="container">
            <div class="header-wrapper">
                <div id="logo">
                    <a href="/" class="custom-logo-link" rel="home" aria-current="page"><img
                            fetchpriority="high" width="500" height="191"
                            src="/img/logo.png?ver={{ config('app.version') }}" class="custom-logo" alt="{{config('app.name')}}"
                            decoding="async" /></a>

                </div><!--Logo-->
                <div class="mainmenu">
                    <div class="main-menu-row">
                        <ul id="menu-header-menu" class="slimmenu">
                            <li id="menu-item-2159" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2159"><a href="/contact">Contact</a></li>
                            <li id="menu-item-2159" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2159"><a href="{{ route('aidocument.show') }}">Ai Document</a></li>

                                    
                            <li id="menu-item-2267"
                                class="menu-btn account-btn menu-item menu-item-type-post_type menu-item-object-page menu-item-2267">
                                <a href="/my-account">My Account</a></li>
                            <li id="menu-item-2266"
                                class="menu-btn account-btn menu-item menu-item-type-post_type menu-item-object-product menu-item-2266">
                                <a href="/order/get-quote">{{ $pagstn['home_get_price'] }}</a></li>
                        </ul>
                    </div>
                </div><!--mainmenu-->

            </div>
        </div><!--container-->
    </div><!--header-->


    <div id="wrapper">

        <div class="container top_container" style="max-width: 100%; min-height: 80vh">


                @yield('content')


            </div>
        </div>
        
        <div id="footer">
            <div class="container" style="max-width: 100% !important; padding: 0px !important">
                <div class="footer-bottom">
                    <div data-elementor-type="wp-page" data-elementor-id="2469"
                        class="elementor elementor-2469" data-elementor-post-type="page">
                        <section
                            class="elementor-section elementor-top-section elementor-element elementor-element-554e8c5 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="554e8c5" data-element_type="section"
                            data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-1d34191"
                                    data-id="1d34191" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-314c3d5 elementor-widget elementor-widget-text-editor"
                                            data-id="314c3d5" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>86-90 Paul Street, London, England, United Kingdom, EC2A 4NE</p>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-4c27c3f elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                            data-id="4c27c3f" data-element_type="widget"
                                            data-widget_type="divider.default">
                                            <div class="elementor-widget-container">
                                                <style>
                                                    /*! elementor - v3.23.0 - 05-08-2024 */
                                                    .elementor-widget-divider {
                                                        --divider-border-style: none;
                                                        --divider-border-width: 1px;
                                                        --divider-color: #0c0d0e;
                                                        --divider-icon-size: 20px;
                                                        --divider-element-spacing: 10px;
                                                        --divider-pattern-height: 24px;
                                                        --divider-pattern-size: 20px;
                                                        --divider-pattern-url: none;
                                                        --divider-pattern-repeat: repeat-x
                                                    }

                                                    .elementor-widget-divider .elementor-divider {
                                                        display: flex
                                                    }

                                                    .elementor-widget-divider .elementor-divider__text {
                                                        font-size: 15px;
                                                        line-height: 1;
                                                        max-width: 95%
                                                    }

                                                    .elementor-widget-divider .elementor-divider__element {
                                                        margin: 0 var(--divider-element-spacing);
                                                        flex-shrink: 0
                                                    }

                                                    .elementor-widget-divider .elementor-icon {
                                                        font-size: var(--divider-icon-size)
                                                    }

                                                    .elementor-widget-divider .elementor-divider-separator {
                                                        display: flex;
                                                        margin: 0;
                                                        direction: ltr
                                                    }

                                                    .elementor-widget-divider--view-line_icon .elementor-divider-separator,
                                                    .elementor-widget-divider--view-line_text .elementor-divider-separator {
                                                        align-items: center
                                                    }

                                                    .elementor-widget-divider--view-line_icon .elementor-divider-separator:after,
                                                    .elementor-widget-divider--view-line_icon .elementor-divider-separator:before,
                                                    .elementor-widget-divider--view-line_text .elementor-divider-separator:after,
                                                    .elementor-widget-divider--view-line_text .elementor-divider-separator:before {
                                                        display: block;
                                                        content: "";
                                                        border-block-end: 0;
                                                        flex-grow: 1;
                                                        border-block-start: var(--divider-border-width) var(--divider-border-style) var(--divider-color)
                                                    }

                                                    .elementor-widget-divider--element-align-left .elementor-divider .elementor-divider-separator>.elementor-divider__svg:first-of-type {
                                                        flex-grow: 0;
                                                        flex-shrink: 100
                                                    }

                                                    .elementor-widget-divider--element-align-left .elementor-divider-separator:before {
                                                        content: none
                                                    }

                                                    .elementor-widget-divider--element-align-left .elementor-divider__element {
                                                        margin-left: 0
                                                    }

                                                    .elementor-widget-divider--element-align-right .elementor-divider .elementor-divider-separator>.elementor-divider__svg:last-of-type {
                                                        flex-grow: 0;
                                                        flex-shrink: 100
                                                    }

                                                    .elementor-widget-divider--element-align-right .elementor-divider-separator:after {
                                                        content: none
                                                    }

                                                    .elementor-widget-divider--element-align-right .elementor-divider__element {
                                                        margin-right: 0
                                                    }

                                                    .elementor-widget-divider--element-align-start .elementor-divider .elementor-divider-separator>.elementor-divider__svg:first-of-type {
                                                        flex-grow: 0;
                                                        flex-shrink: 100
                                                    }

                                                    .elementor-widget-divider--element-align-start .elementor-divider-separator:before {
                                                        content: none
                                                    }

                                                    .elementor-widget-divider--element-align-start .elementor-divider__element {
                                                        margin-inline-start: 0
                                                    }

                                                    .elementor-widget-divider--element-align-end .elementor-divider .elementor-divider-separator>.elementor-divider__svg:last-of-type {
                                                        flex-grow: 0;
                                                        flex-shrink: 100
                                                    }

                                                    .elementor-widget-divider--element-align-end .elementor-divider-separator:after {
                                                        content: none
                                                    }

                                                    .elementor-widget-divider--element-align-end .elementor-divider__element {
                                                        margin-inline-end: 0
                                                    }

                                                    .elementor-widget-divider:not(.elementor-widget-divider--view-line_text):not(.elementor-widget-divider--view-line_icon) .elementor-divider-separator {
                                                        border-block-start: var(--divider-border-width) var(--divider-border-style) var(--divider-color)
                                                    }

                                                    .elementor-widget-divider--separator-type-pattern {
                                                        --divider-border-style: none
                                                    }

                                                    .elementor-widget-divider--separator-type-pattern.elementor-widget-divider--view-line .elementor-divider-separator,
                                                    .elementor-widget-divider--separator-type-pattern:not(.elementor-widget-divider--view-line) .elementor-divider-separator:after,
                                                    .elementor-widget-divider--separator-type-pattern:not(.elementor-widget-divider--view-line) .elementor-divider-separator:before,
                                                    .elementor-widget-divider--separator-type-pattern:not([class*=elementor-widget-divider--view]) .elementor-divider-separator {
                                                        width: 100%;
                                                        min-height: var(--divider-pattern-height);
                                                        -webkit-mask-size: var(--divider-pattern-size) 100%;
                                                        mask-size: var(--divider-pattern-size) 100%;
                                                        -webkit-mask-repeat: var(--divider-pattern-repeat);
                                                        mask-repeat: var(--divider-pattern-repeat);
                                                        background-color: var(--divider-color);
                                                        -webkit-mask-image: var(--divider-pattern-url);
                                                        mask-image: var(--divider-pattern-url)
                                                    }

                                                    .elementor-widget-divider--no-spacing {
                                                        --divider-pattern-size: auto
                                                    }

                                                    .elementor-widget-divider--bg-round {
                                                        --divider-pattern-repeat: round
                                                    }

                                                    .rtl .elementor-widget-divider .elementor-divider__text {
                                                        direction: rtl
                                                    }

                                                    .e-con-inner>.elementor-widget-divider,
                                                    .e-con>.elementor-widget-divider {
                                                        width: var(--container-widget-width, 100%);
                                                        --flex-grow: var(--container-widget-flex-grow)
                                                    }
                                                </style>
                                                <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-433dc69 elementor-widget elementor-widget-text-editor"
                                            data-id="433dc69" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>Copyright © 2025 {{ucfirst(config('app.name_replace'))}}.co.uk.<br></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-272f971"
                                    data-id="272f971" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <section
                                            class="elementor-section elementor-inner-section elementor-element elementor-element-78dc0cc elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                            data-id="78dc0cc" data-element_type="section">
                                            <div class="elementor-container elementor-column-gap-default">
                                                <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-08e3f6c"
                                                    data-id="08e3f6c" data-element_type="column">
                                                    <div
                                                        class="elementor-widget-wrap elementor-element-populated">
                                                        <div class="elementor-element elementor-element-7f3f3ff elementor-widget elementor-widget-wp-widget-nav_menu"
                                                            data-id="7f3f3ff" data-element_type="widget"
                                                            data-widget_type="wp-widget-nav_menu.default">
                                                            <div class="elementor-widget-container">
                                                                <div class="menu-footer-menu-01-container">
                                                                    <ul id="menu-footer-menu-01"
                                                                        class="menu">
                                                                        <li id="menu-item-2494"
                                                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-2494">
                                                                            <a href="/contact">Contact
                                                                                us</a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-7e41e16"
                                                    data-id="7e41e16" data-element_type="column">
                                                    <div
                                                        class="elementor-widget-wrap elementor-element-populated">
                                                        <div class="elementor-element elementor-element-d6c8929 elementor-widget elementor-widget-wp-widget-nav_menu"
                                                            data-id="d6c8929" data-element_type="widget"
                                                            data-widget_type="wp-widget-nav_menu.default">
                                                            <div class="elementor-widget-container">
                                                                <div class="menu-footer-menu-02-container">
                                                                    <ul id="menu-footer-menu-02"
                                                                        class="menu">
                                                                        <li id="menu-item-2498"
                                                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-2498">
                                                                            <a rel="privacy-policy"
                                                                                href="/privacy-policy">Privacy
                                                                                Policy</a></li>
                                                                        <li id="menu-item-2499"
                                                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-2499">
                                                                            <a href="/cookies">Cookie
                                                                                Agreement</a></li>
                                                                        <li id="menu-item-2500"
                                                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-2500">
                                                                            <a
                                                                                href="/customer-terms-of-business">Terms
                                                                                of Business</a></li>
                                                                        <li id="menu-item-2501"
                                                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-2501">
                                                                            <a
                                                                                href="/website-terms-of-use">Terms
                                                                                of Use</a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-aab23f4"
                                    data-id="aab23f4" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-6a4fbc6 elementor-widget elementor-widget-heading"
                                            data-id="6a4fbc6" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h4 class="elementor-heading-title elementor-size-default">Get
                                                    in touch</h4>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-8ac9e72 e-grid-align-right e-grid-align-mobile-left elementor-shape-rounded elementor-grid-0 elementor-widget elementor-widget-social-icons"
                                            data-id="8ac9e72" data-element_type="widget"
                                            data-widget_type="social-icons.default">
                                            <div class="elementor-widget-container">
                                                <style>
                                                    /*! elementor - v3.23.0 - 05-08-2024 */
                                                    .elementor-widget-social-icons.elementor-grid-0 .elementor-widget-container,
                                                    .elementor-widget-social-icons.elementor-grid-mobile-0 .elementor-widget-container,
                                                    .elementor-widget-social-icons.elementor-grid-tablet-0 .elementor-widget-container {
                                                        line-height: 1;
                                                        font-size: 0
                                                    }

                                                    .elementor-widget-social-icons:not(.elementor-grid-0):not(.elementor-grid-tablet-0):not(.elementor-grid-mobile-0) .elementor-grid {
                                                        display: inline-grid
                                                    }

                                                    .elementor-widget-social-icons .elementor-grid {
                                                        grid-column-gap: var(--grid-column-gap, 5px);
                                                        grid-row-gap: var(--grid-row-gap, 5px);
                                                        grid-template-columns: var(--grid-template-columns);
                                                        justify-content: var(--justify-content, center);
                                                        justify-items: var(--justify-content, center)
                                                    }

                                                    .elementor-icon.elementor-social-icon {
                                                        font-size: var(--icon-size, 25px);
                                                        line-height: var(--icon-size, 25px);
                                                        width: calc(var(--icon-size, 25px) + 2 * var(--icon-padding, .5em));
                                                        height: calc(var(--icon-size, 25px) + 2 * var(--icon-padding, .5em))
                                                    }

                                                    .elementor-social-icon {
                                                        --e-social-icon-icon-color: #fff;
                                                        display: inline-flex;
                                                        background-color: var(--gtheme-color-dark);
                                                        align-items: center;
                                                        justify-content: center;
                                                        text-align: center;
                                                        cursor: pointer
                                                    }

                                                    .elementor-social-icon i {
                                                        color: var(--e-social-icon-icon-color)
                                                    }

                                                    .elementor-social-icon svg {
                                                        fill: var(--e-social-icon-icon-color)
                                                    }

                                                    .elementor-social-icon:last-child {
                                                        margin: 0
                                                    }

                                                    .elementor-social-icon:hover {
                                                        opacity: .9;
                                                        color: #fff
                                                    }

                                                    .elementor-social-icon-android {
                                                        background-color: #a4c639
                                                    }

                                                    .elementor-social-icon-apple {
                                                        background-color: #999
                                                    }

                                                    .elementor-social-icon-behance {
                                                        background-color: #1769ff
                                                    }

                                                    .elementor-social-icon-bitbucket {
                                                        background-color: #205081
                                                    }

                                                    .elementor-social-icon-codepen {
                                                        background-color: #000
                                                    }

                                                    .elementor-social-icon-delicious {
                                                        background-color: #39f
                                                    }

                                                    .elementor-social-icon-deviantart {
                                                        background-color: #05cc47
                                                    }

                                                    .elementor-social-icon-digg {
                                                        background-color: #005be2
                                                    }

                                                    .elementor-social-icon-dribbble {
                                                        background-color: #ea4c89
                                                    }

                                                    .elementor-social-icon-elementor {
                                                        background-color: #d30c5c
                                                    }

                                                    .elementor-social-icon-envelope {
                                                        background-color: #ea4335
                                                    }

                                                    .elementor-social-icon-facebook,
                                                    .elementor-social-icon-facebook-f {
                                                        background-color: #3b5998
                                                    }

                                                    .elementor-social-icon-flickr {
                                                        background-color: #0063dc
                                                    }

                                                    .elementor-social-icon-foursquare {
                                                        background-color: #2d5be3
                                                    }

                                                    .elementor-social-icon-free-code-camp,
                                                    .elementor-social-icon-freecodecamp {
                                                        background-color: #006400
                                                    }

                                                    .elementor-social-icon-github {
                                                        background-color: #333
                                                    }

                                                    .elementor-social-icon-gitlab {
                                                        background-color: #e24329
                                                    }

                                                    .elementor-social-icon-globe {
                                                        background-color: var(--gtheme-color-dark)
                                                    }

                                                    .elementor-social-icon-google-plus,
                                                    .elementor-social-icon-google-plus-g {
                                                        background-color: #dd4b39
                                                    }

                                                    .elementor-social-icon-houzz {
                                                        background-color: #7ac142
                                                    }

                                                    .elementor-social-icon-instagram {
                                                        background-color: #262626
                                                    }

                                                    .elementor-social-icon-jsfiddle {
                                                        background-color: #487aa2
                                                    }

                                                    .elementor-social-icon-link {
                                                        background-color: #818a91
                                                    }

                                                    .elementor-social-icon-linkedin,
                                                    .elementor-social-icon-linkedin-in {
                                                        background-color: #0077b5
                                                    }

                                                    .elementor-social-icon-medium {
                                                        background-color: #00ab6b
                                                    }

                                                    .elementor-social-icon-meetup {
                                                        background-color: #ec1c40
                                                    }

                                                    .elementor-social-icon-mixcloud {
                                                        background-color: #273a4b
                                                    }

                                                    .elementor-social-icon-odnoklassniki {
                                                        background-color: #f4731c
                                                    }

                                                    .elementor-social-icon-pinterest {
                                                        background-color: #bd081c
                                                    }

                                                    .elementor-social-icon-product-hunt {
                                                        background-color: #da552f
                                                    }

                                                    .elementor-social-icon-reddit {
                                                        background-color: #ff4500
                                                    }

                                                    .elementor-social-icon-rss {
                                                        background-color: #f26522
                                                    }

                                                    .elementor-social-icon-shopping-cart {
                                                        background-color: #4caf50
                                                    }

                                                    .elementor-social-icon-skype {
                                                        background-color: #00aff0
                                                    }

                                                    .elementor-social-icon-slideshare {
                                                        background-color: #0077b5
                                                    }

                                                    .elementor-social-icon-snapchat {
                                                        background-color: #fffc00
                                                    }

                                                    .elementor-social-icon-soundcloud {
                                                        background-color: #f80
                                                    }

                                                    .elementor-social-icon-spotify {
                                                        background-color: #2ebd59
                                                    }

                                                    .elementor-social-icon-stack-overflow {
                                                        background-color: #fe7a15
                                                    }

                                                    .elementor-social-icon-steam {
                                                        background-color: #00adee
                                                    }

                                                    .elementor-social-icon-stumbleupon {
                                                        background-color: #eb4924
                                                    }

                                                    .elementor-social-icon-telegram {
                                                        background-color: #2ca5e0
                                                    }

                                                    .elementor-social-icon-threads {
                                                        background-color: #000
                                                    }

                                                    .elementor-social-icon-thumb-tack {
                                                        background-color: #1aa1d8
                                                    }

                                                    .elementor-social-icon-tripadvisor {
                                                        background-color: #589442
                                                    }

                                                    .elementor-social-icon-tumblr {
                                                        background-color: #35465c
                                                    }

                                                    .elementor-social-icon-twitch {
                                                        background-color: #6441a5
                                                    }

                                                    .elementor-social-icon-twitter {
                                                        background-color: #1da1f2
                                                    }

                                                    .elementor-social-icon-viber {
                                                        background-color: #665cac
                                                    }

                                                    .elementor-social-icon-vimeo {
                                                        background-color: #1ab7ea
                                                    }

                                                    .elementor-social-icon-vk {
                                                        background-color: #45668e
                                                    }

                                                    .elementor-social-icon-weibo {
                                                        background-color: #dd2430
                                                    }

                                                    .elementor-social-icon-weixin {
                                                        background-color: #31a918
                                                    }

                                                    .elementor-social-icon-whatsapp {
                                                        background-color: #25d366
                                                    }

                                                    .elementor-social-icon-wordpress {
                                                        background-color: #21759b
                                                    }

                                                    .elementor-social-icon-x-twitter {
                                                        background-color: #000
                                                    }

                                                    .elementor-social-icon-xing {
                                                        background-color: #026466
                                                    }

                                                    .elementor-social-icon-yelp {
                                                        background-color: #af0606
                                                    }

                                                    .elementor-social-icon-youtube {
                                                        background-color: #cd201f
                                                    }

                                                    .elementor-social-icon-500px {
                                                        background-color: #0099e5
                                                    }

                                                    .elementor-shape-rounded .elementor-icon.elementor-social-icon {
                                                        border-radius: 10%
                                                    }

                                                    .elementor-shape-circle .elementor-icon.elementor-social-icon {
                                                        border-radius: 50%
                                                    }
                                                </style>
                                                <div class="elementor-social-icons-wrapper elementor-grid">
                                                    <span class="elementor-grid-item">
                                                        <a class="elementor-icon elementor-social-icon elementor-social-icon-twitter elementor-repeater-item-eeb8345"
                                                            target="_blank">
                                                            <span class="elementor-screen-only">Twitter</span>
                                                            <i class="fab fa-twitter"></i> </a>
                                                    </span>
                                                    <span class="elementor-grid-item">
                                                        <a class="elementor-icon elementor-social-icon elementor-social-icon-facebook elementor-repeater-item-4b0e4c3"
                                                            target="_blank">
                                                            <span class="elementor-screen-only">Facebook</span>
                                                            <i class="fab fa-facebook"></i> </a>
                                                    </span>
                                                    <span class="elementor-grid-item">
                                                        <a class="elementor-icon elementor-social-icon elementor-social-icon-instagram elementor-repeater-item-8d8baff"
                                                            target="_blank">
                                                            <span
                                                                class="elementor-screen-only">Instagram</span>
                                                            <i class="fab fa-instagram"></i> </a>
                                                    </span>
                                                    <span class="elementor-grid-item">
                                                        <a class="elementor-icon elementor-social-icon elementor-social-icon-youtube elementor-repeater-item-3db0c93"
                                                            target="_blank">
                                                            <span class="elementor-screen-only">Youtube</span>
                                                            <i class="fab fa-youtube"></i> </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-6a56a03 elementor-widget elementor-widget-text-editor"
                                            data-id="6a56a03" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>Monday-Friday:<br />9am-5pm<br /><i>Saturday-8am-2pm<br />(email
                                                        only)</i></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div><!--footer-bottom-->
            </div><!--container-->
        </div><!--footer-->





        @if ( !($adminQuotePage ?? false) &&    ((($isCheckoutPage ?? false) && !($isEmailVerified ?? false))  || ($verificationCode && $isWithinTwoHours) || ($authuser != null && !$authuser->email_verified_at)))
        <!-- Verify Email -->
        <div class="modal page-modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel"
            aria-hidden="true" data-bs-backdrop="static">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center py-4">
                        <h3>Email Verification</h3>
                        <!-- Login form -->
                        <div class="form-container">
                            <div class="modal-title-info px-2 px-md-5"
                                style="color: #444; font-weight:500; font-size:14px">Please enter the 6-digits
                                verification code that was sent to your email <br> <a
                                    class="text-primary verify_email_address"></a></div>
                            <form class="mt-4" onsubmit="verifyEmail(event)" id="vf_form" autocomplete="off">

                                <div class="py-3 pb-4">
                                    
                                    <div class="digit6">
                                        <input required type="text" class="digit-input" inputmode="numeric" maxlength="1">
                                        <input required type="text" class="digit-input" inputmode="numeric" maxlength="1">
                                        <input required type="text" class="digit-input" inputmode="numeric" maxlength="1">
                                        <input required type="text" class="digit-input" inputmode="numeric" maxlength="1">
                                        <input required type="text" class="digit-input" inputmode="numeric" maxlength="1">
                                        <input required type="text" class="digit-input" inputmode="numeric" maxlength="1">
                                    </div>

                                </div>

                                <div class="mb-1" id="vf_sbutton">
                                    <button type="submit" style="font-size:18px"
                                        class="sx-btn-alt w-100 d-block py-2">Verify Email</button>
                                </div>

                                <div class="mt-4 mb-2 font16 text-normal">Didn't receive an email? <a
                                        class="text-link font16" onclick="resendVerificationCode(event, 1)">Resend</a>
                                </div>

                                <div class="mt-3 font16 mb-4 text-normal"><a class="text-link"
                                        onclick="changeVerificationEmail(event)"><b>Change email</b></a></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Verify Email -->
        <div class="modal page-modal fade" id="resendCodeModal" tabindex="-1"
            aria-labelledby="resendCodeModalLabel" aria-hidden="true" data-bs-backdrop="static">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center py-4">
                        <h3 class="mb-0">Email Verification</h3>
                        <!-- Login form -->
                        <div class="form-container pt-0">
                            <div class="modal-title-info px-2 px-md-5"
                                style="color: #444; font-weight:500; font-size:14px"><a
                                    class="text-primary verify_email_address"></a></div>

                            <form class="mt-4" onsubmit="forgot(event)" id="rc_form" autocomplete="off">


                                <div class="py-5 text-center need-verify-msg" style="font-size:18px; opacity:0.8">To
                                    use the full feature of this site, verify your email.</div>

                                <div class="form-floating my-5 resend-verify-email d-none">
                                    <input type="email" class="form-control verify_email_address"
                                        placeholder="Enter new email address">
                                    <label>Enter new email address</label>
                                </div>

                                <div class="mb-1" id="rc_sbutton">
                                    <button onclick="resendVerificationCode(event, 0)" type="submit"
                                        style="font-size:18px" class="sx-btn-alt w-100 d-block py-2">Send
                                        Code</button>
                                </div>


                                <div class="mt-5 font16 mb-4 text-normal need-verify-msg"><a class="text-link"
                                        onclick="changeVerificationEmail(event)"><b>Change email</b></a></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal page-modal fade" id="resendRespModal" tabindex="-1"
            aria-labelledby="resendRespModalLabel" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 350px">
                <div class="modal-content">
                    <div class="modal-body text-center py-4">
                        <button type="button" style="top:5px; right:5px; width:15px" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                        <h3><span class="bg-primary text-white px-2 py-1"><i class="far fa-envelope"></i></span></h3>

                        <h5 class="mt-3">Resend Verification Code</h5>

                        <!-- Login form -->
                        <div class="form-container">
                            <div class="modal-title-info" style="color: #444; font-size:14px">We have just sent an
                                email
                                with 6-digits verification code to <span class="verify_email_address"></span></div>

                            <div class="d-flex justify-content-between mt-4">
                                <button data-bs-dismiss="modal" class="btn btn-primary">Got it!</button>
                                <button onclick="resendVerificationCode(event, 1)" class="btn btn-default"
                                    style="border: 2px solid blue;">Send again</button>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



        <script type="text/javascript" src="/js/jquery/jquery.minf43b.js?ver={{config('app.version')}}" id="jquery-core-js"></script>
        <script type="text/javascript" src="/js/jquery/jquery-migrate.min5589.js?ver={{config('app.version')}}" id="jquery-migrate-js"></script>
        
        <script type="text/javascript" src="/js/bootstrap.min.js?ver={{config('app.version')}}"></script>

        <script type="text/javascript" src="/js/underscore.min3ab8.js?ver={{config('app.version')}}" id="underscore-js"></script>

        
 
        <script src="/themes/mg/js/jquery.menu.js?ver={{config('app.version')}}"></script>
                <script src="/themes/mg/js/timepicker.js?ver={{config('app.version')}}"></script>

                <script type="text/javascript">
                    jQuery('ul.slimmenu').slimmenu({
                        resizeWidth: '980',
                        collapserTitle: '',
                        animSpeed: 'medium',
                        indentChildren: true,
                        childrenIndenter: '&raquo;'
                    });
                </script>





                <div id="um_upload_single" style="display:none;"></div>

                <div id="um_view_photo" style="display:none;">
                    <a href="javascript:void(0);" data-action="um_remove_modal" class="um-modal-close"
                        aria-label="Close view photo modal">
                        <i class="um-faicon-times"></i>
                    </a>

                    <div class="um-modal-body photo">
                        <div class="um-modal-photo"></div>
                    </div>
                </div>
                
                
                <script type='text/javascript'>
                    const lazyloadRunObserver = () => {
                        const lazyloadBackgrounds = document.querySelectorAll(`.e-con.e-parent:not(.e-lazyloaded)`);
                        const lazyloadBackgroundObserver = new IntersectionObserver((entries) => {
                            entries.forEach((entry) => {
                                if (entry.isIntersecting) {
                                    let lazyloadBackground = entry.target;
                                    if (lazyloadBackground) {
                                        lazyloadBackground.classList.add('e-lazyloaded');
                                    }
                                    lazyloadBackgroundObserver.unobserve(entry.target);
                                }
                            });
                        }, {
                            rootMargin: '200px 0px 200px 0px'
                        });
                        lazyloadBackgrounds.forEach((lazyloadBackground) => {
                            lazyloadBackgroundObserver.observe(lazyloadBackground);
                        });
                    };
                    const events = [
                        'DOMContentLoaded',
                        'elementor/lazyload/observe',
                    ];
                    events.forEach((event) => {
                        document.addEventListener(event, lazyloadRunObserver);
                    });
                </script>
                <script type='text/javascript'>
                    (function() {
                        var c = document.body.className;
                        c = c.replace(/woocommerce-no-js/, 'woocommerce-js');
                        document.body.className = c;
                    })();

                </script>




<script>

    @if ($verificationCode && $isWithinTwoHours)
        const verifyModalAction = "show_verify_window";
        let curVerifyEmail = "{{ $sessionEmail }}";
    @elseif ($authuser != null && !$authuser->email_verified_at)
        const verifyModalAction = "show_resend_window";
        let curVerifyEmail = "{{ $authuser->email }}";
    @else
        const verifyModalAction = "";
        let curVerifyEmail = "";
    @endif

</script>


                @stack('js')


                <script src="/js/toastr.min.js?ver={{config('app.version')}}"></script>

                <script src="/js/main.js?ver={{config('app.version')}}"></script>







            </body>

            </html>
