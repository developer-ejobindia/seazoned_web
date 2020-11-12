@extends('layouts.adminlayout')

@section('title')
@endsection

@section('content_header')
<a href="{{ route('new-faq') }}" title="Add New FAQ's" class="btn bg-blue-custom pull-right"><i class="fa fa-plus"></i> Add New FAQ's</a>
<h1>
    <?php
    echo 'Frequently Asked Questions';
    ?>
</h1>
@endsection
@section('content')


<br/>
<?php // print_r($data);die;?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                {!!Form::open(array('url' => '/Admin/Add_new_faq', 'method' => 'post','enctype' => 'multipart/form-data'))!!}
                <div class="form-group">
                    
              
                    <div class="input text required">
                        {!!Form::label("faq_profile","Select Profile")!!} 
                        <select class="form-control" name="profile_id">
                              <option value="">--Select User--</option>
                              <option value="2">Customers</option>
                              <option value="3">Providers</option>
                        </select>
                        {!!Form::label("faq","Frequently Asked Questions")!!}
                        @if(empty($data['questions']))
                        {!!Form::text("questions", '',array("required"=>"required","maxlength"=>1000,"placeholder"=>" Questions","class"=>"form-control"))!!}<br>
                        @else
                        {!!Form::text("questions", $data['questions'],array("required"=>"required","maxlength"=>1000,"placeholder"=>" Questions","class"=>"form-control"))!!}<br>
                        @endif
                        
                        
                        
                        @if(empty($data['answers']))
                         {!!Form::textarea("answers",'',array("required"=>"required","maxlength"=>1000,"placeholder"=>"Answers","class"=>"textarea form-control"))!!}
                       @else
                        {!!Form::textarea("answers",$data['answers'],array("required"=>"required","maxlength"=>1000,"placeholder"=>"Answers","class"=>"textarea form-control"))!!}
                       @endif
                        @if($errors->has('questions'))
                        {!!$errors->first('questions') !!}
                        @endif
                        @if($errors->has('answers'))
                        {!!$errors->first('answers') !!}
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
<script>
  $(function () {
    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5()
  })
</script>



@endsection

