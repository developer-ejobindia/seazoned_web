@extends('layouts.adminlayout')

@section('title')
{{ $title }}
@endsection

@section('content_header')

<a href="{{ route('CreateServicePrices') }}" title="Add Service Price" class="btn bg-blue-custom pull-right"><i class="fa fa-plus"></i> Add Service Price</a>

<h1>
    <?php
        echo 'Service Price';
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
                            <th>Landscaper Details</th> 
                            <th>Service Details</th> 
                            <th>Service Frequency</th>                       
                            <th>Discount Amount</th>   
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
                                        echo $each_data->name;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->service_name;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->service_frequency;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo '$'.$each_data->discount_price;
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $edit_url = 'Admin/EditServicePrices/' . $each_data->id;
                                        $delete_url = 'Admin/DeleteServicePrices/' . $each_data->id;
                                        ?>
                                        <a href="{{ URL::to($edit_url) }}" class="text-muted" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil fa-lg"></i></a> &nbsp; &nbsp; 
<!--                                        <a href="#" data-toggle="tooltip" title="Delete" class="text-muted"><i class="fa fa-trash-o fa-lg"></i></a>-->

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
