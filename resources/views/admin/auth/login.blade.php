<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{config('app.name')}} | Login</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="all,follow">
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
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css?ver='.config('app.version')) }}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/style.default.css?ver='.config('app.version')) }}" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css?ver='.config('app.version')) }}">
    <!-- Favicon-->

    <meta name="msapplication-TileColor" content="#0F0">
    <meta name="msapplication-TileImage" content="{{asset('img/icons/ms-icon-144x144.png')}}">
    <meta name="theme-color" content="#0F0">
    <!-- Tweaks for older IEs-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js?ver={{config('app.version')}}"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js?ver={{config('app.version')}}"></script><![endif]-->
</head>

<body>
    <div class="page login-page">
        <div class="container" id="2fax">
            <div class="form-outer text-center d-flex align-items-center">
                <div class="" style="width:100%; max-width:600px;">
                    <div class="form-inner" style="border-radius:10px;">

                        <img src="{{asset('img/logo.png')}}" style="max-width:120px; border-radius:0px;">
                        <div class="text-center" style="font-size: 22px; font-weight:bold;">{{config('app.name')}}</div>
                        <p>Please enter your user details.</p>
                        <div id="errmsg" style="color:#F00; text-align:center"></div>
                        <form method="get" class="text-left form-validate" onSubmit="sign_in(event)">
                            <div class=" form-group">
                                <label for="username"><span class="fa fa-envelope-o"></span> Email address</label>
                                <input required id="username" type="email" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group-material">
                                <label for="password"><span class="fa fa-lock"></span> Password</label>
                                <input id="password" type="password" autocomplete="off" required minlength="6" class="form-control">
                            </div>
                            <div class="form-group-material">
                                <input type="checkbox" id="remember">
                                <label for="remember"> Remember login?</label>
                            </div>

                            <div id="sbutton" class="form-group text-center"><button class="btn btn-primary btn-lg" style="min-width:200px; border-radius:5px;">Login</button>
                                <!-- This should be submit button but I replaced it with <a> for demo purposes-->
                            </div>
                        </form>
                        <a href="/admin/forgot-password" class="forgot-pass"> Forgot password ?</a>

                    </div>
                    <div class="copyrights text-center">
                        <p> <span class="fa fa-star"></span> <a href="" class="external"><small>{{config('app.name')}}</small></a></p>

                    </div>
                </div>
            </div>
        </div>
        
    </div>


    <!-- JavaScript files-->
    <script src="{{ asset('admin-assets/vendor/jquery/jquery.min.js?ver='.config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/vendor/popper.js?ver='.config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/vendor/bootstrap/js/bootstrap.min.js?ver='.config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/vendor/jquery-validation/jquery.validate.min.js?ver='.config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js?ver='.config('app.version')) }}">
    </script>
    <!-- Main File-->
    <script src="{{ asset('admin-assets/js/front.js?ver='.config('app.version')) }}"></script>
    <script src="{{ asset('admin-assets/js/signing.js?ver='.config('app.version')) }}"></script>

</body>

</html>