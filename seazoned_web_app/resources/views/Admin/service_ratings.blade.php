@extends('layouts.adminlayout')

@section('title')
{{ $title }}
@endsection

@section('content_header')

<h1>
    <?php
        echo 'Service Rating';
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

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Customer Name</th> 
                            <th>Landscaper Details</th> 
                            <th>Initiated By</th>                      
                            <th>Rating Details</th>   
                            <th>Review</th> 
                            <th>Date/Time</th>     
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($data) > 0) {
                            foreach ($data as $key => $each_data) {
                                ?>
                                <tr>
                                    <td>
                                        <?php
                                        echo $each_data->first_name.' '.$each_data->last_name;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo '<a href="#" class="show_overall_rating" data-toggle="tooltip" title="Show Overall Rating" onClick="getOverallRating(\'' . $each_data->landscaper_id . '\');" data-placement="top">'.$each_data->name.'</a>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($each_data->landscaper_id==$each_data->initiated_by)
                                        echo $each_data->name;
                                        else
                                        echo $each_data->first_name.' '.$each_data->last_name;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->rating_value;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->review;
                                        ?>
                                    </td>  
                                    <td>
                                        <?php
                                        echo $each_data->log_time;
                                        ?>
                                    </td>
                                </tr>                    
                                <?php
                            }
                        } else {
                            ?>
                            <tr><td colspan="4">No record found.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="showOverallRating" tabindex="-1" role="dialog" aria-labelledby="showAddress">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="app_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Rating Details</h4>
                </div>
                <div class="modal-body" id="overall_rating_div">
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<script>
    function getOverallRating(landscaper_id)
    {
        $.post("{{ route('getOverallRating') }}", {landscaper_id: landscaper_id}, function(data){
            //$("#time_slot").html("(Available Slots:" + data + ")");
            $("#overall_rating_div").html(data);
        });
    }
</script>
