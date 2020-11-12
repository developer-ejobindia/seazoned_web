@extends('layouts.adminlayout')

@section('title')
@endsection

@section('content_header')

<h1>
    <?php
    echo 'Payment Percentage';
    ?>
</h1>
@endsection
@section('content')

<?php if ($data!="") { ?>
    <script>
        $(document).ready(function () {
            $('#example2').dataTable();
        });
    </script>
<?php } ?>
<br/>
<?php // print_r($data);die;?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                {!!Form::open(array('url' => '/Admin/AddpaymentPercentage', 'method' => 'post','enctype' => 'multipart/form-data'))!!}
                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("payment_percentage","Payment Percentage")!!}
                        {!!Form::text("payment_percentage",$data,array("required"=>"required","maxlength"=>255,"placeholder"=>"Payment Percentage","class"=>"form-control"))!!}
                        @if($errors->has('payment_percentage'))
                        {!!$errors->first('payment_percentage') !!}
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

