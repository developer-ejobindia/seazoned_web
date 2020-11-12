@extends('layouts.adminlayout')

@section('title')
{{ $title }}
@endsection

@section('content_header')

<h1>
    <?php
        echo 'Service Booking Status';
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
                            <th>Service</th>   
                            <th>Service Date</th>
                            <th>Service Time</th>
                            <th>Order Number</th>
                            <th>Order Status</th>    
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
                                        echo '<a href="#" class="show_booking_history" data-toggle="tooltip" title="Show Booking History" onClick="getBookingHistory(\'' . $each_data->customer_id . '\');" data-placement="top">'.$each_data->first_name.' '.$each_data->last_name.'</a>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo '<a href="#" class="show_booking_history" data-toggle="tooltip" title="Show Booking History" onClick="getLanscaperHistory(\'' . $each_data->landscaper_id . '\');" data-placement="top">'.$each_data->name.'</a>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->service_name;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->service_date;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->service_time;
                                        ?>
                                    </td>    
                                    <td>
                                        <?php
                                        echo $each_data->order_no;
                                        ?>
                                    </td>
                                    <?php
                                    if($each_data->status==-1)
                                    $service_status='Rejected';
                                    elseif($each_data->status==0)
                                    $service_status='Requested';
                                    elseif($each_data->status==1)
                                    $service_status='Confirmed';
                                    elseif($each_data->status==2)
                                    $service_status='Payment Due';
                                    else
                                    $service_status='Completed';
                                    ?>
                                    <td><?php echo $service_status; ?></td>  
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
<div class="modal fade" id="showBookingHistory" tabindex="-1" role="dialog" aria-labelledby="showAddress">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="app_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Booking History</h4>
                </div>
                <div class="modal-body" id="booking_history_div">
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<script>
    function getBookingHistory(customer_id)
    {
        $.post("{{ route('getBookingHistory') }}", {customer_id: customer_id}, function(data){
            //$("#time_slot").html("(Available Slots:" + data + ")");
            $("#booking_history_div").html(data);
        });
    }
    function getLanscaperHistory(landscaper_id)
    {
        $.post("{{ route('getBookingHistory') }}", {landscaper_id: landscaper_id}, function(data){
            //$("#time_slot").html("(Available Slots:" + data + ")");
            $("#booking_history_div").html(data);
        });        
    }
</script>