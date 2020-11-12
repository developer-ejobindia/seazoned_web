@extends('layouts.adminlayout')

@section('title')
{{ $title }}
@endsection

@section('content_header')

<a href="{{ route('CreateLandscaper') }}" title="Add New Landscaper" class="btn bg-blue-custom pull-right"><i class="fa fa-plus"></i> Add New Landscaper</a>

<h1>
    <?php
        echo 'Landscaper List';
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
use App\UserDetail;
$user_detail_obj = new UserDetail();
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Service Provider</th> 
                            <th>Service Details</th>
                            <th>Owner's Name</th>  
                            <th>Email</th>                     
                            <th>Phone Number</th>  
                            <th>Address</th>   
                            <th width="80">Action</th>     
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
                                        //echo $each_data->user_id;
                                        $name  = $user_detail_obj->getLandscaperNameByUserID($each_data->user_id);
                                        echo $name;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        //echo $each_data->service_name;
                                        $service_name  = $user_detail_obj->getLandscaperServiceNameByUserID($each_data->user_id);
                                        echo $service_name;
                                        ?>
                                    </td>
                                    <td><?php echo $each_data->first_name . ' ' . $each_data->last_name ?></td>
                                    <td>
                                        <?php
                                        echo $each_data->email;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo (isset($each_data->phone_number) && $each_data->phone_number!="")?$each_data->phone_number:'N/A';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($each_data->address=="" && $each_data->city=="" && $each_data->state==""){
                                            echo "N/A";
                                        } else {
                                            echo $each_data->address.', '.$each_data->city.', '.$each_data->state;
                                        }                                        
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $edit_url = 'Admin/EditLandscaper/' . $each_data->user_id;
                                        $delete_url = 'Admin/DeleteLandscaper/' . $each_data->user_id;
                                        ?>
                                        <a href="{{ URL::to($edit_url) }}" class="text-muted" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil fa-lg"></i></a> &nbsp; &nbsp; 
                                        <a href="{{ URL::to($delete_url) }}" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this Landscaper?');" class="text-muted"><i class="fa fa-trash-o fa-lg"></i></a>

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
@endsection
