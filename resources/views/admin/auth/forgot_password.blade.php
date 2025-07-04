<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} | Forgot Password</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/bootstrap/css/bootstrap.min.css?ver='.config('app.version')) }}">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/font-awesome/css/font-awesome.min.css?ver='.config('app.version')) }}">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/fontastic.css?ver='.config('app.version')) }}">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/grasp_mobile_progress_circle-1.0.0.min.css?ver='.config('app.version')) }}">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet"
        href="{{ asset('admin-assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css?ver='.config('app.version')) }}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/style.default.css?ver='.config('app.version')) }}" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css?ver='.config('app.version')) }}">
    <!-- Favicon-->
    
    

    <meta name="theme-color" content="#0F0">

    <link rel="stylesheet" href="{{ asset('css/toastr.min.css?ver='.config('app.version')) }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css?ver={{config('app.version')}}">


    <!-- Tweaks for older IEs-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js?ver={{config('app.version')}}"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js?ver={{config('app.version')}}"></script><![endif]-->
</head>

<body>
    <div class="page login-page">
        <div class="container">
            <div class="form-outer text-center d-flex align-items-center">
                <div style="width:100%; max-width:600px;" id="form_area">
                    <div class="form-inner" id="2fax">
                        <img src="{{ asset('img/logo.png') }}" style="max-width:150px; border-radius:0px;">
                        <div class="logo text-uppercase"><small>Forgot Password ?</small></div>
                        <p>Please enter your email address.</p>
                        <div id="errmsg" style="color:#F00; text-align:center"></div>
                        <form method="get" class="text-left form-validate" onSubmit="forgot_pw(event)">
                            <div class="form-group">
                                <label>Email Address <span class="reqr">*</span></label>
                                <input id="email" name="email" required class="form-control"
                                    data-error="Please eneter a valid email address">
                            </div>
                            <div id="sbutton" class="form-group text-center"><button class="btn btn-primary btn-lg"
                                    style="min-width:200px; border-radius:5px;"> Submit</button>
                                <!-- This should be submit button but I replaced it with <a> for demo purposes-->
                            </div>
                        </form>
                        <a href="./login" class="forgot-pass">Login</a>

                    </div>


                    <div class="copyrights text-center">
                        <p> <span class="fa fa-star"></span> <a href="" class="external"><small></small></a></p>
                        <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript files-->
    <script src="{{ asset('admin-assets/vendor/jquery/jquery.min.js?ver='.config('app.version')) }}?version=nililc"></script>
    <script src="{{ asset('admin-assets/vendor/popper.js?ver='.config('app.version')) }}?version=nililc"></script>
    <script src="{{ asset('admin-assets/vendor/bootstrap/js/bootstrap.min.js?ver='.config('app.version')) }}?version=nililc"></script>
    <script src="{{ asset('admin-assets/js/main.js?ver='.config('app.version')) }}?version=nililc"></script>
    <script src="{{ asset('admin-assets/vendor/jquery-validation/jquery.validate.min.js?ver='.config('app.version')) }}?version=nililc"></script>
    <script
        src="{{ asset('admin-assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js?ver='.config('app.version')) }}?version=nililc">
    </script>
    <!-- Main File-->
    <script src="{{ asset('admin-assets/js/front.js?ver='.config('app.version')) }}?version=nililc"></script>





    <script src="{{ asset('js/toastr.min.js?ver='.config('app.version')) }}"></script>
    <script>
        function toggle_pview(event) {
            event.preventDefault();
            $(event.target).toggleClass('fa-eye fa-eye-slash');
            let type = $(event.target).closest('.form-group').find('input').attr('type');
            $(event.target).closest('.form-group').find('input').attr('type', (type == 'password') ? 'text' : 'password');
        }
    </script>


    <script src="{{ asset('admin-assets/js/signing.js?ver='.config('app.version')) }}?version=nililc"></script>

</body>

</html>
