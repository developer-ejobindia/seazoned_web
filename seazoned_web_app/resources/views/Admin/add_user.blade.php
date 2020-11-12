@extends('layouts.adminlayout')

@section('title')
{{ $title }}
@endsection

@section('content_header')
<h1>
    {{ $title }}
</h1>
@endsection
@section('content')
{!!Form::open(array('url' => '/Admin/CreateUserSubmit', 'method' => 'post','enctype' => 'multipart/form-data'))!!}

<!-- general form elements -->
<div class="box box-default">
    <div class="box-header">
        <h3 class="box-title">Add New User</h3>
    </div><!-- /.box-header -->
    <!-- form start -->

    <div class="box-body">

        <div class="row">
            <div class="col-md-6">


               <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("first_name","First Name")!!}
                        {!!Form::text("first_name",Input::old('first_name'),array("required"=>"required","maxlength"=>255,"placeholder"=>"Enter First Name","class"=>"form-control"))!!}
                        <p class="text-danger">
                        @if($errors->has('first_name'))
                        {!!$errors->first('first_name') !!}
                        @endif
                        </p>
                    </div>                                         
                </div>

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("last_name","Last Name")!!}
                        {!!Form::text("last_name",Input::old('last_name'),array("required"=>"required","maxlength"=>255,"placeholder"=>"Enter Last Name","class"=>"form-control"))!!}
                        <p class="text-danger">
                        @if($errors->has('last_name'))
                        {!!$errors->first('last_name') !!}
                        @endif
                        </p>
                    </div>                                         
                </div>

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("email","Email")!!}
                        {!!Form::text("email",Input::old('email'),array("required"=>"required","maxlength"=>255,"placeholder"=>"Enter Email","class"=>"form-control"))!!}
                        <p class="text-danger">
                        @if($errors->has('email'))
                        {!!$errors->first('email') !!}
                        @endif
                        </p>
                    </div>                                         
                </div>

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("phone_number","Phone Number")!!}
                        {!!Form::text("phone_number",Input::old('phone_number'),array("required"=>"required","maxlength"=>255,"placeholder"=>"Enter Phone Number","class"=>"form-control"))!!}
                        <p class="text-danger">
                        @if($errors->has('phone_number'))
                        {!!$errors->first('phone_number') !!}
                        @endif
                        </p>
                    </div>                                         
                </div>

                <!-- <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("username","Username")!!}
                        {!!Form::text("username",Input::old('username'),array("required"=>"required","maxlength"=>255,"placeholder"=>"Enter Username","class"=>"form-control"))!!}
                        @if($errors->has('username'))
                        {!!$errors->first('username') !!}
                        @endif
                    </div>                                         
                </div> -->

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("password","Password")!!}
                        {!!Form::password("password",array("required"=>"required","maxlength"=>255,"placeholder"=>"Enter Password","class"=>"form-control"))!!}
                        <p class="text-danger">
                        @if($errors->has('password'))
                        {!!$errors->first('password') !!}
                        @endif
                        </p>
                    </div>                                         
                </div>

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("address","Address")!!}
                        {!!Form::textarea("address",Input::old('address'),array("required"=>"required","placeholder"=>"Enter Address","class"=>"form-control"))!!}
                        <p class="text-danger">
                        @if($errors->has('address'))
                        {!!$errors->first('address') !!}
                        @endif
                        </p>
                    </div>                                         
                </div>

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("city","City")!!}
                        {!!Form::text("city",Input::old('city'),array("required"=>"required","placeholder"=>"Enter City","class"=>"form-control"))!!}
                        <p class="text-danger">
                        @if($errors->has('city'))
                        {!!$errors->first('city') !!}
                        @endif
                        </p>
                    </div>                                         
                </div>

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("state","State")!!}
                        {!!Form::text("state",Input::old('state'),array("required"=>"required","placeholder"=>"Enter State","class"=>"form-control"))!!}
                        <p class="text-danger">
                        @if($errors->has('state'))
                        {!!$errors->first('state') !!}
                        @endif
                        </p>
                    </div>                                         
                </div>

<!--                            <div class="form-group">
                                <div class="input text required">
                                    {!!Form::label("country","Country")!!}
                                    <br>
                                    {!!Form::select('country', $country_arr,Input::old('country'),array("required"=>"required","maxlength"=>255,"placeholder"=>"Country","class"=>"form-control"))!!}
                                    @if($errors->has('country'))
                                    {!!$errors->first('country') !!}
                                    @endif
                                </div>                                         
                            </div>-->

                            <div class="form-group">
                                <div class="input text required">
                                    {!!Form::label("active","Status")!!}
                                    <br>
                                    {!!Form::select('active', $active_arr,Input::old('active'),array("required"=>"required","maxlength"=>255,"placeholder"=>"Status","class"=>"form-control"))!!}
                                    <p class="text-danger">
                                    @if($errors->has('active'))
                                    {!!$errors->first('active') !!}
                                    @endif
                                    </p>
                                </div>                                         
                            </div>
                





            </div>



        </div>

    </div><!-- /.box-body -->

    <div class="box-footer">
        {!!Form::submit("Submit",array("class"=>"btn bg-blue-custom"))!!}
    </div>


</div><!-- /.box -->

{!!Form::token()!!}
{!!Form::close()!!}
@endsection