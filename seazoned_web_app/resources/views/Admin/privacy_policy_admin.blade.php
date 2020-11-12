@extends('layouts.adminlayout')

@section('title')
@endsection

@section('content_header')

<h1>
    <?php
    echo 'Privacy Policy';
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

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                {!!Form::open(array('url' => '/Admin/AddprivacyPolicy', 'method' => 'post','enctype' => 'multipart/form-data'))!!}
                <div class="form-group">
                    
              
                    <div class="input text required">
                        {!!Form::label("privacy_policy","Privacy Policy")!!}
                        {!!Form::textarea("content",$data,array("required"=>"required","maxlength"=>1000,"placeholder"=>"Privacy Policy","class"=>"textarea form-control"))!!}
                        @if($errors->has('content'))
                        {!!$errors->first('content') !!}
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
