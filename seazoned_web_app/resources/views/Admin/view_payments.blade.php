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
                            <th>Order Number</th>
                            <th>Service Price</th>
                            <th>Admin's Commission</th>
                            <th>Mode of Payment</th>
                            <th>Card Number</th>
                            <th>Order Status</th>     
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_revenue=0.00;
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
                                        echo '<a href="#" class="show_landscaper_revenue" data-toggle="tooltip" title="Show Total Revenue" onClick="getLandscaperRevenue(\'' . $each_data->landscaper_id . '\');" data-placement="top">'.$each_data->name.'</a>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo '<a href="#" class="show_service_revenue" data-toggle="tooltip" title="Show Total Revenue" onClick="getServiceRevenue(\'' . $each_data->service_id . '\');" data-placement="top">'.$each_data->service_name.'</a>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->service_date;
                                        ?>
                                    </td>    
                                    <td>
                                        <?php
                                        echo $each_data->order_no;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo '$'.$each_data->service_price;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($each_data->admin_payment == '')
                                        echo '$'.(($each_data->service_price*$percentage)/100)."( pending)";
                                        else
                                         echo '$'.($each_data->admin_payment);    
                                        ?>
                                    </td>
                                    <td>
                                         <?php
                                          if($each_data->mode_of_payment != '')
                                        echo ($each_data->mode_of_payment);
                                        else
                                        echo "N/A";
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($each_data->card_no != 0){
                                        $card = substr($each_data->card_no,0,12);
                                        $card = str_replace($card,"************",$each_data->card_no);
//                                        $maskedNumber = str_pad(substr($each_data->card_no, 0, 4), strlen($each_data->card_no), "*");
                                        echo ($card);   
                                        }
                                        else
                                            echo 'N/A';
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
                                    $service_status='Payment Done';
                                    else
                                    $service_status='Job Completed';
                                    ?>
                                    <td><?php echo $service_status; ?></td>   
                                </tr>                    
                                <?php
                                $total_revenue+=$each_data->service_price/10;
                            }
                        } else {
                            ?>
                            <tr><td colspan="4">No record found.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
                Total Revenue Earned: <?php echo '$'.$total_revenue.'.00'; ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="showLandscaperRevenue" tabindex="-1" role="dialog" aria-labelledby="showAddress">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="app_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Revenue Details</h4>
                </div>
                <div class="modal-body" id="landscaper_revenue_div">
                </div>
            </form>
        </div>
    </div>
</div>

<!--<div class="modal fade" id="showServiceRevenue" tabindex="-1" role="dialog" aria-labelledby="showAddress">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="app_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Revenue Details1</h4>
                </div>
                <div class="modal-body" id="service_revenue_div">
                </div>
            </form>
        </div>
    </div>
</div>-->

@endsection

<script>
    function getLandscaperRevenue(landscaper_id)
    {
        $.post("{{ route('getLandscaperRevenue') }}", {landscaper_id: landscaper_id}, function(data){
            //$("#time_slot").html("(Available Slots:" + data + ")");
            $("#landscaper_revenue_div").html(data);
        });
    }

    function getServiceRevenue(service_id)
    {
        $.post("{{ route('getServiceRevenue') }}", {service_id: service_id}, function(data){
            //$("#time_slot").html("(Available Slots:" + data + ")");
            $("#service_revenue_div").html(data);
        });
    }
    
     
//        if (typeof ssn !== 'undefined' && ssn != null) {
//            let ssn_new = ssn.replace(/^.{6}/g, '***-**')
//            return ssn_new
//        } else {
//            return '';
//        }
</script>