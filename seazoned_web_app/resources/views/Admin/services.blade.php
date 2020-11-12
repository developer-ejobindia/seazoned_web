@extends('layouts.adminlayout')

@section('title')
{{ $title }}
@endsection

@section('content_header')

<h1>
    <?php
        echo 'Service List';
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
                            <th>Service Name</th> 
                            <th>Description</th>                       
                            <th>Status</th>   
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
                                        echo $each_data->service_name;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->description;
                                        ?>
                                    </td>
                                    <td>
                                    Active
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $edit_url = 'Admin/EditServices/' . $each_data->id;
                                        $delete_url = 'Admin/DeleteServices/' . $each_data->id;
                                        ?>
                                        <a href="{{ URL::to($edit_url) }}" class="text-muted" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil fa-lg"></i></a> &nbsp; &nbsp; 
<!--                                        <a href="{{ URL::to($delete_url) }}" data-toggle="tooltip" title="Delete" class="text-muted"><i class="fa fa-trash-o fa-lg"></i></a>-->

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
