@extends('layouts.adminlayout')

@section('title')
@endsection

@section('content_header')

<h1>
    <?php
    echo 'Lawn Mowing Tips';
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
                {!!Form::open(array('url' => '/Admin/Add-lawn-mowing-tips', 'method' => 'post','enctype' => 'multipart/form-data'))!!}
                <div class="form-group">
                    
              
                    <div class="input text required">
                        {!!Form::label("lawn_mowing_tips","Lawn Mowing Tips")!!}
                        {!!Form::textarea("tips",$data,array("required"=>"required","maxlength"=>1000,"placeholder"=>"Lawn Mowing Tips","class"=>"textarea form-control"))!!}
                        @if($errors->has('tips'))
                        {!!$errors->first('tips') !!}
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

