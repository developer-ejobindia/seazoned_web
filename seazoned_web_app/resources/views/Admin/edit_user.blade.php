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
{!!Form::open(array('url' => '/Admin/EditUserSubmit', 'method' => 'post','enctype' => 'multipart/form-data'))!!}

<!-- general form elements -->
<div class="box box-default">
    <div class="box-header">
        <h3 class="box-title">Edit User</h3>
    </div><!-- /.box-header -->
    <!-- form start -->

    <div class="box-body">
        <div class="row">
            <div class="col-md-6">

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("first_name","First Name")!!}
                        {!!Form::text("first_name",$data[0]->first_name,array("required"=>"required","maxlength"=>255,"placeholder"=>"Enter First Name","class"=>"form-control"))!!}
                        @if($errors->has('first_name'))
                        {!!$errors->first('first_name') !!}
                        @endif
                    </div>                                         
                </div>

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("last_name","Last Name")!!}
                        {!!Form::text("last_name",$data[0]->last_name,array("required"=>"required","maxlength"=>255,"placeholder"=>"Enter Last Name","class"=>"form-control"))!!}
                        @if($errors->has('last_name'))
                        {!!$errors->first('last_name') !!}
                        @endif
                    </div>                                         
                </div>

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("email","Email")!!}
                        {!!Form::text("email",$data[0]->email,array("required"=>"required","maxlength"=>255,"placeholder"=>"Enter Email","class"=>"form-control"))!!}
                        @if($errors->has('email'))
                        {!!$errors->first('email') !!}
                        @endif
                    </div>                                         
                </div>

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("phone_number","Phone Number")!!}
                        {!!Form::text("phone_number",$data[0]->phone_number,array("required"=>"required","maxlength"=>255,"placeholder"=>"Enter Phone Number","class"=>"form-control"))!!}
                        @if($errors->has('phone_number'))
                        {!!$errors->first('phone_number') !!}
                        @endif
                    </div>                                         
                </div>

                <!-- <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("username","Username")!!}
                        {!!Form::text("username",$data[0]->username,array("required"=>"required","maxlength"=>255,"placeholder"=>"Enter Username","class"=>"form-control"))!!}
                        @if($errors->has('username'))
                        {!!$errors->first('username') !!}
                        @endif
                    </div>                                         
                </div> -->

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("password","Password")!!}
                        {!!Form::password("password",array("password" =>$data[0]->password,"required"=>"required","maxlength"=>255,"placeholder"=>"Enter Password","class"=>"form-control"))!!}
                        @if($errors->has('password'))
                        {!!$errors->first('password') !!}
                        @endif
                    </div>                                         
                </div>

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("address","Address")!!}
                        {!!Form::textarea("address",strip_tags($data[0]->address),array("required"=>"required","placeholder"=>"Enter Address","class"=>"form-control"))!!}
                        @if($errors->has('address'))
                        {!!$errors->first('address') !!}
                        @endif
                    </div>                                         
                </div>

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("city","City")!!}
                        {!!Form::text("city",strip_tags($data[0]->city),array("required"=>"required","placeholder"=>"Enter City","class"=>"form-control"))!!}
                        @if($errors->has('city'))
                        {!!$errors->first('city') !!}
                        @endif
                    </div>                                         
                </div>

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("state","State")!!}
                        {!!Form::text("state",strip_tags($data[0]->state),array("required"=>"required","placeholder"=>"Enter State","class"=>"form-control"))!!}
                        @if($errors->has('state'))
                        {!!$errors->first('state') !!}
                        @endif
                    </div>                                         
                </div>

<!--                            <div class="form-group">
                                <div class="input text required">
                                    {!!Form::label("country","Country")!!}
                                    <br>
                                    {!!Form::select('country', $country_arr,$data[0]->country,array("required"=>"required","maxlength"=>255,"placeholder"=>"Country","class"=>"form-control"))!!}
                                    @if($errors->has('country'))
                                    {!!$errors->first('country') !!}
                                    @endif
                                </div>                                         
                            </div>-->

                            <div class="form-group">
                                <div class="input text required">
                                    {!!Form::label("active","Status")!!}
                                    <br>
                                    {!!Form::select('active', $active_arr,$data[0]->active,array("required"=>"required","maxlength"=>255,"placeholder"=>"Status","class"=>"form-control"))!!}
                                    @if($errors->has('active'))
                                    {!!$errors->first('active') !!}
                                    @endif
                                </div>                                         
                            </div>

            </div>

        </div>

    </div><!-- /.box-body -->

    <div class="box-footer">
        <input type="hidden" name="id" value="<?php echo $data[0]->user_id ?>">
        {!!Form::submit("Submit",array("class"=>"btn bg-blue-custom"))!!}
    </div>


</div><!-- /.box -->

{!!Form::token()!!}
{!!Form::close()!!}
@endsection