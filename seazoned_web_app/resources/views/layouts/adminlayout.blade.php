<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo session('user_type'); ?> | @yield('title')</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!---SeazonedApp CSS-->
        <link href="{{ url('/') }}/admin_asset/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Font Awesome -->
        <link href="{{ url('/') }}/admin_asset/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="{{ url('/') }}/admin_asset/bower_components/Ionicons/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="{{ url('/') }}/admin_asset/dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
              page. However, you can choose any other skin. Make sure you
              apply the skin class to the body tag so the changes take effect. -->
        <link href="{{ url('/') }}/admin_asset/dist/css/skins/skin-black.css" rel="stylesheet" type="text/css" />
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <!---Datatable CSS -->
        <link rel="stylesheet" href="{{ url('/') }}/admin_asset/plugins/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css" />
        <link rel="stylesheet" href="{{ url('/') }}/admin_asset/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        <!---End SeazonedApp CSS-->


        <!---SeazonedApp JS-->
        <!-- jQuery 3 -->
        <script src="{{ url('/') }}/admin_asset/bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="{{ url('/') }}/admin_asset/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- AdminLTE App -->
        <script src="{{ url('/') }}/admin_asset/dist/js/adminlte.min.js"></script>
        <!-- Datatable JS -->
        <script src="{{ url('/') }}/admin_asset/plugins/datatables/media/js/jquery.dataTables.js"></script>
        <script src="{{ url('/') }}/admin_asset/plugins/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <script src="{{ url('/') }}/admin_asset/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

        <!---End SeazonedApp JS-->

        
    </head>

    <!--
    BODY TAG OPTIONS:
    =================
    Apply one or more of the following classes to get the
    desired effect
    |---------------------------------------------------------|
    | SKINS         | skin-blue                               |
    |               | skin-black                              |
    |               | skin-purple                             |
    |               | skin-yellow                             |
    |               | skin-red                                |
    |               | skin-green                              |
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
    -->

    <body class="hold-transition skin-black sidebar-mini">
        <div class="wrapper">

            <!-- Main Header -->
            <header class="main-header">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Logo -->
                <a href="{{ url('AdminHome') }}" class="logo">
                    <img src="{{ url('/') }}/admin_asset/dist/img/logo_seazoned.png" alt="Seazoned" />
                </a>

                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->

                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">

                            <!-- User Account Menu -->
                            <li class="dropdown">
                                <!-- Menu Toggle Button -->
                                <a href="{{ route('Adminlogout') }}">
                                    <i class="fa fa-power-off"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">

                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">

                    <!-- Sidebar Menu -->
                    <ul class="sidebar-menu" data-widget="tree">
                        <li class="<?php
                        if (Route::currentRouteName() == 'ManageLandscapers' || Route::currentRouteName() == 'CreateLandscaper' || Route::currentRouteName() == 'EditLandscaper')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('ManageLandscapers') }}"><i class="fa fa-link"></i> <span>Manage Landscapers</span></a></li>
                        <li class="<?php
                        if (Route::currentRouteName() == 'ManageUsers' || Route::currentRouteName() == 'CreateUser' || Route::currentRouteName() == 'EditUser')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('ManageUsers') }}"><i class="fa fa-link"></i> <span>Manage Users</span></a></li>
                        <li class="<?php
                        if (Route::currentRouteName() == 'ManageServices' || Route::currentRouteName() == 'EditServices')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('ManageServices') }}"><i class="fa fa-link"></i> <span>Manage Services</span></a></li>
                        <li class="<?php
                        if (Route::currentRouteName() == 'ManageBookings')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('ManageBookings') }}"><i class="fa fa-link"></i> <span>Service Booking Status</span></a></li>  
                        <li class="<?php
                        if (Route::currentRouteName() == 'ViewPayments')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('ViewPayments') }}"><i class="fa fa-link"></i> <span>View Payments</span></a></li>                         
                        <li class="<?php
                        if (Route::currentRouteName() == 'ServicePrices' || Route::currentRouteName() == 'CreateServicePrices' || Route::currentRouteName() == 'CreateServicePricesSubmit' || Route::currentRouteName() == 'EditServicePrices' || Route::currentRouteName() == 'EditServicePricesSubmit')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('ServicePrices') }}"><i class="fa fa-link"></i> <span>Service Price</span></a></li>  
                        <li class="<?php
                        if (Route::currentRouteName() == 'ServiceRatings')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('ServiceRatings') }}"><i class="fa fa-link"></i> <span>Service Rating</span></a></li>    
                        <li class="<?php
                        if (Route::currentRouteName() == 'AddressBooks')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('AddressBooks') }}"><i class="fa fa-link"></i> <span>Address Book</span></a></li> 
                        <li class="<?php
                        if (Route::currentRouteName() == 'UserMessages')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('UserMessages') }}"><i class="fa fa-link"></i> <span>List Messages</span></a></li>  
                        <li class="<?php
                        if (Route::currentRouteName() == 'PaymentPercentage')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('PaymentPercentage') }}"><i class="fa fa-link"></i> <span>Payment Percentage</span></a></li> 
                        <li class="<?php
                        if (Route::currentRouteName() == 'ManagePayple')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('ManagePayple') }}"><i class="fa fa-link"></i> <span>Manage Paypal</span></a></li>    
                        <li class="<?php
                        if (Route::currentRouteName() == 'PrivacyPolicy')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('PrivacyPolicy') }}"><i class="fa fa-link"></i> <span>Privacy Policy</span></a></li> 
                        <li class="<?php
                        if (Route::currentRouteName() == 'faq')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('faq') }}"><i class="fa fa-link"></i> <span>Frequently Asked Questions</span></a></li> 
                        <li class="<?php
                        if (Route::currentRouteName() == 'why-work-with-us')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('why-work-with-us') }}"><i class="fa fa-link"></i> <span>Why Work with Us</span></a></li> 
                        <li class="<?php
                        if (Route::currentRouteName() == 'lawn-mowing-tips')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('lawn-mowing-tips') }}"><i class="fa fa-link"></i> <span>Lawn Mowing Tips</span></a></li> 
                        <li class="<?php
                        if (Route::currentRouteName() == 'terms-services')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('terms-services') }}"><i class="fa fa-link"></i> <span>Terms and Services</span></a></li> 
                        <li class="<?php
                        if (Route::currentRouteName() == 'about-us')
                            echo 'active';
                        else
                            echo '';
                        ?>"><a href="{{ route('about-us') }}"><i class="fa fa-link"></i> <span>About Us</span></a></li> 
                        
                        
                        <!---<li class="treeview">
                            <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#">Link in level 2</a></li>
                                <li><a href="#">Link in level 2</a></li>
                            </ul>
                        </li>-->
                    </ul>
                    <!-- /.sidebar-menu -->
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    @yield('content_header')
                </section>

                <!-- Main content -->
                <section class="content container-fluid">
                    @if(Session::has("global"))
                    {!!Session::get("global")!!}
                    @endif 
                    @yield('content')

                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <footer class="main-footer">
                <!-- Default to the left -->
                Copyright &copy; 2018 Seazoned. All rights reserved.
            </footer>

        </div><!-- ./wrapper -->

        <script>
$(function () {
   $('.show_address').click(function (e) {
       e.preventDefault();

       $('#showAddress').modal('show');
   });

   $('.show_booking_history').click(function (e) {
       e.preventDefault();

       $('#showBookingHistory').modal('show');
   });

   $('.show_landscaper_revenue').click(function (e) {
       e.preventDefault();

       $('#showLandscaperRevenue').modal('show');
   });

   $('.show_service_revenue').click(function (e) {
       e.preventDefault();

       $('#showServiceRevenue').modal('show');
   });

   $('.show_overall_rating').click(function (e) {
       e.preventDefault();

       $('#showOverallRating').modal('show');
   });

   $('.show_message').click(function (e) {
       e.preventDefault();

       $('#showMessage').modal('show');
   });
})
        </script>

    </body>
</html>