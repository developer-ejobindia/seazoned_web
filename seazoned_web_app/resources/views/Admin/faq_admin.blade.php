
@extends('layouts.adminlayout')
@section('content_header')
<a href="{{ route('new-faq') }}" title="Add New FAQ's" class="btn bg-blue-custom pull-right"><i class="fa fa-plus"></i> Add New FAQ's</a>
<h1>
    <?php
        echo 'Frequently Asked Questions';
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
                            <th width ="190">Profile</th>
                            <th width ="250">Questions</th>
                            <th > Answers</th>                       
                            <th width="60">Action</th>   
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($data) > 0) {
                            foreach ($data as $val) {
//                                print_r($val);die;
                                ?>
                                <tr>
                                    <td>
                                        <?php
                                        if(!empty ( $val->profile_id)){
                                             if($val->profile_id == 2)
                                             {
                                                echo "Customer"; 
                                             }
                                             if($val->profile_id == 3)
                                             {
                                                 echo "Provider";
                                             }
                                                                              
                                        ?>
                                    </td>
                             <?php }else{  ?>
                                    <td>
                                        <?php
                                        echo  '';
                            }    ?>
                                    </td>
                                    <td>
                                        <?php
                                        if(!empty ( $val->questions)){
                                        echo   $val->questions;                                      
                                        ?>
                                    </td>
                             <?php }else{  ?>
                                    <td>
                                        <?php
                                        echo  '';
                            }    ?>
                                    </td>
                                    <td>
                                        <?php
                                        if(!empty ( $val->answers)){
                                        echo  $val->answers;
                                        ?>
                                    </td>
                                        <?php  }else{  ?>
                                    <td>
                                        <?php
                                        echo  '';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                       if(!empty ( $val->id)){
                                        $edit_url = 'Admin/Editfaq/' . $val->id;
                                        $delete_url = 'Admin/Deletefaq/' . $val->id;
                                        ?>
                                        <a href="{{ URL::to($edit_url) }}" class="text-muted" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil fa-lg"></i></a> &nbsp; &nbsp; 
                                        <a href="{{ URL::to($delete_url) }}" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this FAQ?');" class="text-muted"><i class="fa fa-trash-o fa-lg"></i></a>
                                        
                                    </td> 
                                       <?php  }else{  ?>
                                    <td>
                                        <?php
                                        echo  '';
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

<!-- Modal -->
<!--<div class="modal fade" id="showMessage" tabindex="-1" role="dialog" aria-labelledby="showMessage">
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
</div>-->

@endsection

