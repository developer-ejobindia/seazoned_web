@extends('layouts.adminlayout')

@section('title')
{{ $title }}
@endsection

@section('content_header')

<h1>
    <?php
        echo 'Address Book';
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
                            <th>User Name</th>
                            <th>Address</th>                       
                            <th>Email</th>   
                            <th>Contact Number</th>
                            <th>Primary Contact</th>     
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
                                        echo '<a href="#" class="show_address" data-toggle="tooltip" title="Show Details" onClick="getAddress(\'' . $each_data->user_id . '\');" data-placement="top">'.$each_data->first_name.' '.$each_data->last_name.'</a>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->address.', '.$each_data->city.', '.$each_data->state.', '.$each_data->country_name;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->email_address;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->contact_number;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $each_data->primary_address==1?'Yes':'No';
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
<div class="modal fade" id="showAddress" tabindex="-1" role="dialog" aria-labelledby="showAddress">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="app_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Available Address Books</h4>
                </div>
                <div class="modal-body" id="address_div">
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<script>
    function getAddress(user_id)
    {
        $.post("{{ route('getAddress') }}", {user_id: user_id}, function(data){
            //$("#time_slot").html("(Available Slots:" + data + ")");
            $("#address_div").html(data);
        });
    }
</script>