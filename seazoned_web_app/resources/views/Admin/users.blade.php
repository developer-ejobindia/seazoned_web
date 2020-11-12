@extends('layouts.adminlayout')

@section('title')
{{ $title }}
@endsection

@section('content_header')

<a href="{{ route('CreateUser') }}" title="Add New User" class="btn bg-blue-custom pull-right"><i class="fa fa-plus"></i> Add New User</a>

<h1>
    <?php
        echo 'User List';
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
                            <th>Name</th> 
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
                                    <td><?php echo $each_data->first_name . ' ' . $each_data->last_name ?></td>
                                    <td>
                                        <?php
                                        echo $each_data->email;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->phone_number;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->address.', '.$each_data->city.', '.$each_data->state;
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $edit_url = 'Admin/EditUser/' . $each_data->user_id;
                                        $delete_url = 'Admin/DeleteUser/' . $each_data->user_id;
                                        if($each_data->user_id!=2){
                                        ?>
                                        <a href="{{ URL::to($edit_url) }}" class="text-muted" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil fa-lg"></i></a> &nbsp; &nbsp; 
                                        <a href="{{ URL::to($delete_url) }}" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this User?');" class="text-muted"><i class="fa fa-trash-o fa-lg"></i></a>
                                        <?php
                                        } else {
                                            echo "N/A"; 
                                        }
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
@endsection
