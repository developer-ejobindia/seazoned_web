@extends("layouts.dashboardlayout")
@section("header")
@endsection

@section("content")
<div class="login-page-background">
    <div class="login-page-form-wrap text-center">
        <div class="login-page-form-header p-20">
            <a href="{!! url("/") !!}" class="logo">
               <img src="{{ asset("default/images/logo_seazoned.png") }}" alt="">
            </a>
        </div>
        <div class="login-form-body p-20">
            <?php if (session('msg') != "") { ?>
                <div class="alert alert-success">
                    {{ session('msg') }}
                </div><br>
            <?php } ?>
            {!! Form::open(['url' => '/verify-email', 'id' => 'forgot_password_form', 'method' => 'post', "class" => "m-l-50 m-r-50"]) !!}
            <h1 class="m-b-20">Forgot Password</h1>
            <div class="form-group floating-label m-b-20">
                {!! Form::text("username", Input::old('username'), ["required" => "required", "placeholder" => "Please input your email address to send verification code", "class" => "form-control text-center rounded-0"]) !!}
                @if($errors->has('username'))
                <span class="error">{!!$errors->first('username') !!}</span>
                @endif
                <div class="col-md">
                    <a href="{{ url("/user-login") }}" class="float-right">Return To Login</a>
                </div>
                <br/>
            </div>
            {!! Form::submit("SUBMIT", ["class"=>"btn btn-block btn-login rounded-0 m-b-20"]) !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script src="https://apis.google.com/js/client:platform.js" type="text/javascript"></script>
<div id="googleplus" style='display:none;'><div id="renderMe"></div></div>

@endsection

@section("footer")
@endsection

