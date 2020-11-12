@extends('layouts.adminlayout')

@section('title')
{{ $title }}
@endsection

@section('content_header')

<h1>
    <?php
        echo 'List Messages';
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
                            <th>Sender Details</th>
                            <th>Receiver Details</th>                       
                            <th>Message Details</th>    
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($data) > 0) {
                            foreach ($data as $key => $each_data) {
                                if($key==0 || $key%2==0)
                                {
                                ?>
                                <tr>
                                    <td>
                                        <?php
                                        echo $data[$key+1]->first_name.' '.$data[$key+1]->last_name;                                        
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->first_name.' '.$each_data->last_name;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo '<a href="#" class="show_message" data-toggle="tooltip" title="Show Details" onClick="msgDetails(\'' . $each_data->id . '\');" data-placement="top">View Details</a>';
                                        ?>
                                    </td> 
                                </tr>                    
                                <?php
                                }
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
<div class="modal fade" id="showMessage" tabindex="-1" role="dialog" aria-labelledby="showMessage">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="app_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Message Details</h4>
                </div>
                <div class="modal-body" id="message_div">
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<script>
    function msgDetails(id)
    {
        $.post("{{ route('getMessageDetails') }}", {id: id}, function(data){
            //$("#time_slot").html("(Available Slots:" + data + ")");
            $("#message_div").html(data);
        });
    }
</script>