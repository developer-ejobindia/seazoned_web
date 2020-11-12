@extends('layouts.adminlayout')

@section('title')
@endsection

@section('content_header')

<h1>
    <?php
    echo 'Manage Paypal';
    ?>
</h1>
You need Business-Pro account for payment through credit/debit card
@endsection
@section('content')

<?php if (count($data) > 0) { ?>
    <script>
        $(document).ready(function () {
            $('#example2').dataTable();
        });
    </script>
<?php } ?>
<br/>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                {!!Form::open(array('url' => '/Admin/AddPaypleName', 'method' => 'post','enctype' => 'multipart/form-data'))!!}
                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("name","Account Holder Name")!!}
                        {!!Form::text("name", isset($data['name']) ? $data['name'] : "" ,array("required"=>"required","maxlength"=>255,"placeholder"=>"Account Holder Name","class"=>"form-control"))!!}
                        @if($errors->has('name'))
                        {!!$errors->first('name') !!}
                        @endif
                    </div>                                         
                </div>
                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("account_email","Account Holder Email")!!}
                        {!!Form::text("account_email",isset($data['account_email']) ? $data['account_email'] : "" ,array("required"=>"required","maxlength"=>255,"placeholder"=>"Account Holder Email","class"=>"form-control"))!!}
                        @if($errors->has('account_email'))
                        {!!$errors->first('account_email') !!}
                        @endif
                    </div>                                         
                </div>
                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("account_details","Account Holder API Username")!!}
                        {!!Form::text("account_details",isset($data['account_dtls']) ? $data['account_dtls'] : "" ,array("required"=>"required","maxlength"=>255,"placeholder"=>"Account Holder API Username","class"=>"form-control"))!!}
                        @if($errors->has('account_details'))
                        {!!$errors->first('account_details') !!}
                        @endif
                    </div>                                         
                </div>
                 <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("account_signature ","Account Holder API Signature")!!}
                        {!!Form::text("account_signature",isset($data['account_signature']) ? $data['account_signature'] : "" ,array("required"=>"required","maxlength"=>255,"placeholder"=>"Account Holder API Signature","class"=>"form-control"))!!}
                        @if($errors->has('account_signature'))
                        {!!$errors->first('account_signature') !!}
                        @endif
                    </div>                                         
                </div>
                 <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("account_password","Account Holder API Password")!!}
                        {!!Form::text("account_password",isset($data['account_password']) ? $data['account_password'] : "" ,array("required"=>"required","maxlength"=>255,"placeholder"=>"Account Holder API Password","class"=>"form-control"))!!}
                        @if($errors->has('account_password'))
                        {!!$errors->first('account_password') !!}
                        @endif
                    </div>                                         
                </div>
                <div class="box-footer">
                    {!!Form::submit("Submit",array("class"=>"btn bg-blue-custom"))!!}
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>



@endsection

