@extends('layouts.adminlayout')

@section('title')
@endsection

@section('content_header')

<h1>
    <?php
    echo 'Frequently Asked Questions';
    ?>
</h1>
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
<?php 
//     print_r($data[0]->questions);die;
      $data['questions']= $data[0]->questions;
      $data['answers']= $data[0]->answers;  
      $data['id']=$data[0]->id;
      
     
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                {!!Form::open(array('url' => '/Admin/update-faq/'. $data['id'], 'method' => 'post','enctype' => 'multipart/form-data'))!!}
                <div class="form-group">
                    
             
                    <div class="input text required">
                        {!!Form::label("faq","Frequently Asked Questions")!!}
                        {!!Form::text("questions",$data['questions'],array("required"=>"required","maxlength"=>1000,"placeholder"=>"Privacy Policy","class"=>"form-control"))!!}<br>
                        {!!Form::textarea("answers",$data['answers'],array("required"=>"required","maxlength"=>1000,"placeholder"=>"Privacy Policy","class"=>"textarea form-control"))!!}
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

