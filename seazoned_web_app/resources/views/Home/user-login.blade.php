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
                <div class="alert alert-danger">
                    {{ session('msg') }}
                </div><br>
            <?php } ?>
            {!! Form::open(['url' => '/user-login', 'id' => 'login_form', 'method' => 'post', "class" => "m-l-50 m-r-50"]) !!}
            <h1 class="m-b-20">Member Login</h1>
            <div class="form-group floating-label m-b-20">
                {!! Form::text("username", Input::old('username'), ["required" => "required", "placeholder" => "Enter Email ID", "class" => "form-control rounded-0"]) !!}
                @if($errors->has('username'))
                <span class="error">{!!$errors->first('username') !!}</span>
                @endif
            </div>
            <div class="form-group floating-label m-b-20">
                <input type="password" required = "required" placeholder = "Enter Password" class = "form-control rounded-0" name = "password">
                @if($errors->has('password'))
                <span class="error">{!!$errors->first('password') !!}</span>
                @endif
            </div>
            <input type="hidden" name="latitude" id="latitude"/>
            <input type="hidden" name="longitude" id="longitude"/>
            <input type="hidden" id="fb_access_token" name="fb_access_token" value=""/>
            <div class="row m-b-20">
                <div class="col-6">
                    <div class="form-check">
                        <label class="form-check-label float-left">
                            <input type="checkbox" class="form-check-input rounded-0 border-0">
                            Remember Me
                        </label>
                    </div>
                </div>
                <div class="col-6">
                    <a href="{{ url("/forgot-password") }}" class="float-right">Forgot Password?</a>
                </div>
            </div>
            {!! Form::submit("LOGIN", ["class"=>"btn btn-block btn-login rounded-0 m-b-20"]) !!}
            <div class="row m-b-20">
                <div class="col-md">
                    <button type="button" id="fb_login" class="btn btn-block btn-login-facebook rounded-0 text-right"><span><i class="fab fa-facebook-f float-left"></i></span>Login with facebook</button>
                </div>
                <div class="col-md p-l-0">
                    <button type="button" onclick="callfunc();" class="btn btn-block btn-login-google rounded-0 text-right"><span><i class="fab fa-google-plus-g float-left"></i></span>Sign in with Google+</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="login-form-footer text-center p-25">
            <p class="m-0">If yo don’t have an account Register now by <a href="{{ url("/user-register") }}">click here</a></p>
        </div>
    </div>
</div>
<?php
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
$protocol = substr($sp, 0, strpos($sp, "/")) . $s;

if($protocol =='http'){
?>
<script src="http://apis.google.com/js/client:platform.js" type="text/javascript"></script>
<?php
}else{
?>
<script src="https://apis.google.com/js/client:platform.js" type="text/javascript"></script>
<?php
}
?>
<script> 
$(document).ready(function(){ 

    if (navigator.geolocation) { 

        navigator.geolocation.getCurrentPosition(showLocation); 

    } else { 

        $('#location').html('Geolocation is not supported by this browser.'); 

    } 
    renderBtn();
}); 

    // Load the SDK asynchronously
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    window.fbAsyncInit = function () {
        FB.init({
            appId: '185927128804319',
            cookie: false, // enable cookies to allow the server to access
            // the session
            xfbml: true, // parse social plugins on this page
            version: 'v2.1' // use version 2.1
        });


    };

function showLocation(position) { 

var latitude = position.coords.latitude; 

var longitude = position.coords.longitude; 

$('#latitude').val(latitude);
$('#longitude').val(longitude);

}

$('#fb_login').click(function(){
   fblogin('http://ratemyitrecruiter.com/login','employee');
});

function fblogin(url_to_redirect,user_selected) {
  FB.login(function(response) {
     if (response.authResponse) {
			$('#fb_access_token').val(response.authResponse.accessToken);
            var form = document.forms.login_form;
            form.submit();
     }
     else {
     }
 }, {scope:'public_profile,email'});
}

function callfunc() {
$("#renderMe button").trigger("click");
}

var loginFinished = function (authResult) {
        if (authResult) {
            console.log(authResult);
        }

        gapi.client.load('oauth2', 'v2', function () {
            gapi.client.oauth2.userinfo.get()
                    .execute(function (response) {
                        $('#google_username').val(response.email);
                        $('#google_name').val(response.name);
                        $('#google_gender').val(response.gender);
                        var form = document.forms.login_form;
                        form.submit();
                    });
        });

    };

    var options = {
        'callback': loginFinished,
        'approvalprompt': 'force',
        'clientid': '1095769995276-ls5smmpq3esb49a3poh7nnvvi0f1bms8.apps.googleusercontent.com',
        'scope': 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email',
        'requestvisibleactions': 'http://schemas.google.com/CommentActivity http://schemas.google.com/ReviewActivity',
        'cookiepolicy': 'single_host_origin'
    };

    var renderBtn = function () {
        gapi.signin.render('renderMe', options);
    }

</script>
<div id="googleplus" style='display:none;'><div id="renderMe"></div></div>

@endsection

@section("footer")
@endsection

