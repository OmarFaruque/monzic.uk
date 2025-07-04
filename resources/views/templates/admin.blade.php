<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="theme-color" content="#fff">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('meta')

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="">

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
    </style>
    

    <link rel='stylesheet' id='font-awesome-5-all-css'
    href='/plugins/elementor/assets/lib/font-awesome/css/all.mind5d5.css?ver={{config('app.version')}}' type='text/css'
    media='all' />


    <link rel="preconnect" as="font" href="https://fonts.googleapis.com/">


    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&amp;display=swap" rel="stylesheet">


    <link href="{{ asset('css/aos.css?ver='.config('app.version')) }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/toastr.min.css?ver='.config('app.version')) }}">
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css?ver='.config('app.version')) }}">
    <link rel="stylesheet" href="{{ asset('css/userstyle.css?ver='.config('app.version')) }}">


    @stack('css')

</head>


<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center d-none align-items-center">
            <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60"
                width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/admin" class="nav-link">DashBoard</a>
                </li>
            </ul>

            
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item" style="flex: 1">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->


        <!-- Main Sidebar Container -->
        <aside class="main-sidebar smidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/admin" class="brand-link">
                <img style="width:100%; max-width:180px;" src="{{ asset('img/logo.png') }}" alt="Logo" class="brand-imagem"><br>
                {{-- <p class="slogan">{{ config('app.name') }}</p> --}}
            </a>
            
            <!-- Sidebar -->
            <div class="sidebar" style="margin-top: calc(3.5rem + 18px);">
                <!-- Sidebar user panel (optional) -->
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item client">
                            <a class="nav-link" href="/admin">
                                <i class="nav-icon fa fa-dashboard"></i>
                                <p> DASHBOARD </p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/orders">
                                <i class="nav-icon fa fa-shield-alt"></i>
                                <p> Orders </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/unconfirmed-orders">
                                <i class="nav-icon fa fa-shield-alt"></i>
                                <p> Unconfirmed Orders </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/tickets">
                                <i class="nav-icon fa fa-ticket-alt"></i>
                                <p> Support Tickets </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/coupons">
                                <i class="nav-icon fa fa-percent"></i>
                                <p> Coupon </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/blacklists">
                                <i class="nav-icon fa fa-stop"></i>
                                <p> Blacklists </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/users">
                                <i class="nav-icon fa fa-users"></i>
                                <p> Users </p>
                            </a>
                        </li>

                        @if( @auth('admin')->user()->isAllowed(["SUPER_ADMIN"]))
                            
                        
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-cogs"></i>
                                <p>Settings
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/admin/quote-formula" class="nav-link">
                                        <i class="fa nav-icon"></i>
                                        <p>Quote Formula</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/admin/payment-settings" class="nav-link">
                                        <i class="fa nav-icon"></i>
                                        <p>Payment Settings</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/admin/settings" class="nav-link">
                                        <i class="fa nav-icon"></i>
                                        <p>Other Settings</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/admin/page-editing" class="nav-link">
                                        <i class="fa nav-icon"></i>
                                        <p>Page Editing</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/admin/page-template" class="nav-link">
                                        <i class="fa nav-icon"></i>
                                        <p>Page Templates</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        

                        
                        
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/admins">
                                    <i class="nav-icon fa fa-user-secret"></i>
                                    <p> Admins </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a target="_blank" class="nav-link" href="/admin/optimize">
                                    <i class="nav-icon fa fa-line-chart"></i>
                                    <p> Optimize </p>
                                </a>
                            </li>
                        
                        @endif

                        

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon far fa-user"></i>
                                <p>My account
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/admin/update-password" class="nav-link">
                                        <i class="fa nav-icon"></i>
                                        <p>Update password</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        


                     
                        <li class="nav-item">
                            <a href="/logout" onclick="logoutAccount(event)" class="nav-link">
                                <i class="nav-icon fa fa-sign-out-alt"></i>
                                <p>
                                    Logout
                                </p>
                            </a>
                        </li>


                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrappern">

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" style="background-color: #f8e2e3">
                @yield('content')

            </div>
        </div>

    </div>


<!--  Modal  ALert -->
<div class="modal" id="modal_alert">
    <div class="modal-dialog modal-dialog-centered" >
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close pull-right" data-bs-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body" style="font-size:14px;">
          <h4>We are nnnnnnnnhere</h4>
          <p>thrhrrjkrkrrkk</p>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer" style="justify-content:space-between">
          <button type="button" class="btn btn-primary action_btn" >Delete</button>
          <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
        </div>

      </div>
    </div>
  </div>

   <!--  Modal  Confirm -->
<div class="modal" id="modal_confirm">
    <div class="modal-dialog modal-dialog-centered" >
      <div class="modal-content">
        <!-- Modal body -->
        <div class="modal-body" style="font-size:14px;">
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
        </div>

      </div>
    </div>
  </div>

    <div id="sg_loader_holder" class="d-none">
        <div class="sg_loader">
            <img src="{{ asset('img/logo.png') }}">
            <div><i class="fa fa-spin fa-spinner"></i></div>
        </div>
    </div>


<script>
            const verifyModalAction = "";
            let   curVerifyEmail = "";
</script>
    <script src="{{ asset('js/jquery.min.js?ver='.config('app.version')) }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js?ver='.config('app.version')) }}"></script>

    <script src="{{ asset('js/toastr.min.js?ver='.config('app.version')) }}"></script>

    <script src="{{ asset('js/adminlte.min.js?ver='.config('app.version')) }}"></script>
    
    <script src="{{ asset('js/form_validator.js?ver='.config('app.version')) }}"></script>


    @stack('js')

    <script>
     const AUTH_USER_ID = "";
    </script>

   <script src="{{ asset('admin-assets/js/main.js?ver='.config('app.version')) }}?v=update_2.a"></script>

</body>

</html>
