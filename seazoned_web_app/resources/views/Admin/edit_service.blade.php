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
{!!Form::open(array('url' => '/Admin/EditServicesSubmit', 'method' => 'post','enctype' => 'multipart/form-data'))!!}

<!-- general form elements -->
<div class="box box-default">
    <div class="box-header">
        <h3 class="box-title">Edit Service</h3>
    </div><!-- /.box-header -->
    <!-- form start -->

    <div class="box-body">
        <div class="row">
            <div class="col-md-6">

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("service_name","Service Name")!!}
                        {!!Form::text("service_name",$data[0]->service_name,array("required"=>"required","maxlength"=>255,"placeholder"=>"Enter Service Name","class"=>"form-control"))!!}
                        @if($errors->has('service_name'))
                        {!!$errors->first('service_name') !!}
                        @endif
                    </div>                                         
                </div>

                <div class="form-group">
                    <div class="input text required">
                        {!!Form::label("description","Description")!!}
                        {!!Form::textarea("description",strip_tags($data[0]->description),array("required"=>"required","placeholder"=>"Enter Description","class"=>"form-control"))!!}
                        @if($errors->has('description'))
                        {!!$errors->first('description') !!}
                        @endif
                    </div>                                         
                </div>

            </div>

        </div>

    </div><!-- /.box-body -->

    <div class="box-footer">
        <input type="hidden" name="id" value="<?php echo $data[0]->id ?>">
        {!!Form::submit("Submit",array("class"=>"btn bg-blue-custom"))!!}
    </div>


</div><!-- /.box -->

{!!Form::token()!!}
{!!Form::close()!!}
@endsection