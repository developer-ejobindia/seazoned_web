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
{!!Form::open(array('url' => '/Admin/CreateServicePricesSubmit', 'method' => 'post','enctype' => 'multipart/form-data'))!!}

<!-- general form elements -->
<div class="box box-default">
    <div class="box-header">
        <h3 class="box-title">Add Service Price</h3>
    </div><!-- /.box-header -->
    <!-- form start -->

    <div class="box-body">

        <div class="row">
            <div class="col-md-6">


                            <div class="form-group">
                                <div class="input text required">
                                    {!!Form::label("landscaper_id","Select Landscaper")!!}
                                    <br>
                                    {!!Form::select('landscaper_id', $landscaper_arr,Input::old('landscaper_id'),array("required"=>"required","maxlength"=>255,"placeholder"=>"Select Landscaper","class"=>"form-control"))!!}
                                    @if($errors->has('landscaper_id'))
                                    {!!$errors->first('landscaper_id') !!}
                                    @endif
                                </div>                                         
                            </div>

                            <div class="form-group">
                                <div class="input text required">
                                    {!!Form::label("service_id","Select Service")!!}
                                    <br>
                                    {!!Form::select('service_id', $service_arr,Input::old('service_id'),array("required"=>"required","maxlength"=>255,"placeholder"=>"Select Service","class"=>"form-control"))!!}
                                    @if($errors->has('service_id'))
                                    {!!$errors->first('service_id') !!}
                                    @endif
                                </div>                                         
                            </div>

                            <div class="form-group">
                                <div class="input text required">
                                    {!!Form::label("service_frequency","Service Frequency")!!}
                                    {!!Form::text("service_frequency",Input::old('service_frequency'),array("required"=>"required","maxlength"=>255,"placeholder"=>"Service Frequency","class"=>"form-control"))!!}
                                    @if($errors->has('service_frequency'))
                                    {!!$errors->first('service_frequency') !!}
                                    @endif
                                </div>                                         
                            </div>

                            <div class="form-group">
                                <div class="input text required">
                                    {!!Form::label("discount_price","Discount Amount")!!}
                                    {!!Form::text("discount_price",Input::old('discount_price'),array("required"=>"required","maxlength"=>255,"placeholder"=>"Discount Amount","class"=>"form-control"))!!}
                                    @if($errors->has('discount_price'))
                                    {!!$errors->first('discount_price') !!}
                                    @endif
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