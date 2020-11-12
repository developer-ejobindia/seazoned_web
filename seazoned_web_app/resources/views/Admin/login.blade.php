<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Seazoned App</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
        <!---End SeazonedApp CSS-->

    </head>
    <body class="login-page">
        <div class="login-box">
            <div class="login-logo text-center">
                <img src="{{ url('/') }}/admin_asset/dist/img/logo.png" alt="" />
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                <h4 class="text-center">Sign in to start your session</h4><br/>
                <form action="{{ route('AdminLoginAccess') }}" method="post">
                    <div class="form-group has-feedback">
                        <input type="text" id="username" name="username" class="form-control input-lg" placeholder="Username"/>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" id="passowrd" name="password" class="form-control input-lg" placeholder="Password"/>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <input type="hidden" name="_token" value="{{ Session::token()}}" />
                    </div>
                    @if(count($errors) > 0)
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="callout callout-danger">
                                @foreach($errors->all() as $error)
                                <p><i class="fa fa-warning"></i> {{ $error }}</p>
                                @endforeach
                            </div>
                        </div>    
                    </div>
                    @endif

                    @if(Session::has("global"))
                    {!!Session::get("global")!!}
                    @endif 
                    @yield('content')
                    
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="submit" class="btn bg-blue-custom btn-block btn-flat btn-lg">Sign In</button>
                        </div><!-- /.col -->
                    </div>
                </form> 

            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->

        <p class="text-center text-white">&copy; <?php echo date('Y'); ?> Seazoned App.</p>

        <!---SeazonedApp JS-->
        <!-- jQuery 3 -->
        <script src="{{ url('/') }}/admin_asset/bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="{{ url('/') }}/admin_asset/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.min.js"></script>
        <!---End SeazonedApp JS-->
        <script>
$(function () {
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
});
        </script>
    </body>
</html>