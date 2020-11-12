<?php

use App\User; ?>
@extends("layouts.dashboardlayout")
@section('content')
<?php

use App\Landscaper;

$Landscaper_obj = new Landscaper;
?>
<section class="main-content user-profile p-y-30">
    <?php if(isset($lands_service) && !empty($lands_service)){  ?>
    <div class="container">
        <div class="landscaper-profile">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="row">
                        <div class="col-md col-sm-12">
                            <div class="card side-menu text-center card-no-radius m-b-30">
                                <form action="{{ url("/landscapper/update-prof-img") }}" method="post" enctype="multipart/form-data">
                                    <div class="card-block p-t-40 p-b-0">
                                        <?php
                                        $prof_pic = url('/') . '/default/images/avatar-landscaper.jpg';
                                        if (session("prof_img") !== "") {
                                            if (file_exists(public_path() . '/uploads/profile_picture/' . session("prof_img"))) {
                                                $prof_pic = url('/public') . '/uploads/profile_picture/' . session("prof_img");
                                            }
                                        }
                                        ?>
                                        <div class="avtar"> 
                                            <img src="{{ $prof_pic }}" alt="" id="profile-img-tag">
                                            <span class="status"></span>
                                            <!--<label class="change-photo-overlay animated slideInUp">
                                                <input type="file" name="profile_image" class="d-none" id="profile_image"/>
                                                <span>Change</span>
                                            </label>-->
                                        </div>                                                                                
                                    </div>
                                    <?php
                                    $user_obj = new User();
                                    $owner_rating = $user_obj->owner_landscaper_rating();
                                    ?>
                                    <div class="justify-content-md-center d-flex p-t-15">
                                        <div class="rating mx-auto">
                                            <ul class="list-unstyled m-b-0">
                                                <?php
                                                if ($owner_rating != 0) {
                                                    for ($i = 1; $i < $owner_rating; $i++) {
                                                        ?>  
                                                        <li ><i class="fa fa-star"></i></li>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div> 
                                    <h4 class="profile-name text-center m-0 m-t-20 m-b-50">{{ session("user_name") }}</h4>
                                    <div class="text-center">
                                        <label class="btn btn-info m-b-15">
                                            <input type="file" name="profile_image" class="d-none" id="profile_image"/>
                                            <span>Browse</span>
                                        </label>
                                        <input type="submit" value="Update Profile Image" class="btn btn-success m-b-15" onClick="return check()">
                                    </div>
                                    <span class="text text-danger text-center" id="profile_image_err"> </span>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <div class="featured-img">
                                <div class="card card-no-radius ">
                                    <div class="card-block p-b-0">
                                        <h3 class="m-0 medium m-b-20">Featured image</h3>
                                        <form action="{{ url("/landscapper/update-feature-img") }}" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="{{ $feature_image == NULL ?  asset("/default/images/lands-mowing.jpg") : url('/').'/uploads/services/'.$feature_image }}" class="w-100 m-b-15" id="feature-image-tag">
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <label class="btn btn-info m-b-20">
                                                    <input type="file" name="landscaper_image" class="d-none" id="feature_image"/>
                                                    <span>Browse</span>
                                                </label>
                                                <input type="submit" value="Update Feature Image" class="btn btn-success m-b-20"  onClick="return check1()">
                                            </div>   
                                    </div>
                                    <span class="text text-danger text-center" id="feature_image_err"> </span>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md">
                            <div class="featured-img">
                                <div class="card card-no-radius m-t-30">
                                    <div class="card-block p-b-0">
                                        <h3 class="m-0 medium m-b-20">Drivers license</h3>
                                        <form action="{{ url("/landscapper/update-drivers-lisence") }}" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="{{ $user_info->drivers_license == NULL ?  asset("/default/images/lands-mowing.jpg") : url('/').'/uploads/drivers_license/'.$user_info->drivers_license }}" class="w-100 m-b-15" id="feature-image-tag">
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <label class="btn btn-info m-b-20">
                                                    <input type="file" name="drivers_license" class="d-none" id="drivers_license" required accept="image/*"/>
                                                    <span>Browse</span>
                                                </label>
                                                <input type="submit" value="Update Driver License" class="btn btn-success m-b-20"  onClick="return check_drivers_license()">
                                            </div>   
                                    </div>
                                    <span class="text text-danger text-center" id="drivers_license_err"> </span>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md">                                   
                            <div class="service-hours m-t-30 m-b-30">
                                <div class="card card-no-radius ">
                                    <div class="card-block">
                                        <h4 class="medium m-0 medium m-b-20">Service Hours
                                            <span><a href="javascript:void(0)" class="float-right header-link regular text-success" onclick="getSeviceHours()">Edit</a></span>
                                        </h4>
                                        <table class="table table-condensed">
                                            <tbody>
                                                <?php
                                                foreach ($service_hours as $time) {
                                                    ?>
                                                    <tr>
                                                        <td class="regular">{{ $time->service_day }}</td>
                                                        <td class="text-right">{{ strtoupper(date("g:i a", strtotime($time->start_time))) }} - {{ strtoupper(date("g:i a", strtotime($time->end_time))) }}</td>
                                                    </tr>
                                                <?php } ?>                                        
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-8 col-sm-12">
                    <?php
                    if (session('msg') != "") {
                        ?>
                        <div class="alert alert-success">
                            {{ session('msg') }}
                        </div><br>
                    <?php } ?>
                    <div class="card custom-card card-no-radius m-b-45">
                        <div class="card-header">
                            <h4 class="m-0 regular">{{ session("user_name") }}</h4>
                            <a href="javascript:void(0)" class="float-right header-link regular text-success" data-toggle="modal" data-target="#edit_info">Edit Info</a><br/>
                            
                        </div>
                        <div class="card-block user-profile-details">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="m-b-30"><b>Business Address</b><br>
                                        {{ $user_details->address }} </p>
                                    <p><b>Cell phone</b><br>
                                        {{ $user_details->phone_number }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="m-b-30"><b>Email ID</b><br>
                                        {{ session("user_email") }}</p>
                                    <p><b>Social Security Number</b><br>
                                        {{ $user_info->ssn_no }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-y-20">
                                  <a href="javascript:void(0)" class="header-link regular text-success" data-toggle="modal" data-target="#change_password">Change Password</a>
                                </div>
                    </div>
                    <div class="card card-no-radius m-b-30">
                        <div class="card-header card-header-success">
                            <h4 class="m-0 medium">Service details</h4>
                        </div>
                        <form action="{{ url("/landscapper/update-service-details") }}" method="post">
                            
                             <div class="card-block custom-form p-b-0">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="price-1">Services to be provided within </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="provider_distance" id ="provider_distance" value='<?php echo isset($service_distance) && $service_distance[0] != ''?$service_distance[0]:'' ?>'>
                                           <label> miles</label>
                                        </div>
                                          <span class="text text-danger" id="provider_distance_err"> </span>
                                    </div>
                                </div>

                            </div>
                            
                            <?php
                            $str_arr = [];
                            $str = "";
                            foreach ($lands_service as $key => $one_service) {
                                $str_arr[]=$one_service;                                
                                ?>
                                <div class="card-block custom-form p-b-0">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="service-name">Service Name</label>
                                            <select class="form-control" id="service-name-{{ $key }}"  disabled="">
                                                <?php
                                                foreach ($services as $service) {
                                                    ?>
                                                    <option value="{{ $service->id }}" <?php echo ($one_service == $service->id) ? "selected" : "" ?>>{{ $service->service_name }}</option>
                                                <?php } ?>
                                            </select>
                                            <input type="hidden" name="serviceid[]" value="{{ $one_service }}">                                            
                                        </div>                                
                                    </div>
                                    <?php
                                    $data = $Landscaper_obj->getLoadServiceNew($one_service);
                                    echo base64_decode($data['form']);
                                    ?>
                                    <input type="hidden" name="landscaper_id[]" value="{{ $data['landscaper_id'] }}">
                                </div>
                                <hr class="m-0">
                            <?php } 
                            $str = implode(',',$str_arr);
                            ?>
                            <div class="card-footer p-y-20">
                                <input type="submit" value="Update Service details" class="btn btn-success" onclick ='return edit()'>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--  Edit Information Modal  -->
<div class="modal fade" id="edit_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit Personal Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form class="m-t-30" action="{{ url("/lanscaper/edit-profile") }}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="f-name">First Name</label>
                        <input type="text" class="form-control" name="first_name" value="{{ $user_details->first_name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="l-name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" value="{{ $user_details->last_name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="f-name">Provider Name</label>
                        <input type="text" class="form-control" name="provider_name" value="<?php echo (isset($landscaper_name) && $landscaper_name != '')? $landscaper_name : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tel">Phone Number</label>
                        <input type="tel" class="form-control" name="tel" value="{{ $user_details->phone_number }}" required>
                    </div>
                    <div class="form-group">
                        <label for="datepicker">Business Address</label>
                        <input type="text" class="form-control" name="address" value="{{ $user_details->address }}" required>
                    </div>
                    <div class="form-group">
                        <label for="ssn_no">Social Security Number</label>
                        <input type="number" class="form-control" name="ssn_no" value="{{ $user_info->ssn_no }}" required id="ssn_no" min="100000000" max="999999999">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save </button>
                </div> 
            </form>  
        </div>
    </div>
</div>
<!--   end modal  -->

<!--  Change Password Modal  -->
<div class="modal fade" id="change_password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Change Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form class="m-t-30" id="edit_password" action="{{ url("/landscaper/update-password") }}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="f-name">Old Password</label>
                        <input type="password" class="form-control" name="old_password" id="old_password" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="l-name">New Password</label>
                        <input type="password" class="form-control" name="new_password" id="new_password" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="tel">Re Enter New Password</label>
                        <input type="password" class="form-control" name="confirm_new_password" id="confirm_new_password" value="" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="change_pass_button" class="btn btn-success">Save</button>
                </div> 
            </form>  
        </div>
    </div>
</div>
<!--   end modal  -->

<!--  Edit Service Hours Modal  -->
<div class="modal fade" id="edit_service" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit Service Hours</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="edit_service_body"></div>
        </div>
    </div>
</div>
<!--   end modal  -->
    <?php  }else {   ?>
       <div class="alert alert-custom-danger text-center"> 
    <!--<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->
    <i class="fa fa-info-circle" aria-hidden="true"></i> 
    <strong>Oops! It looks like you have not added a service yet.</strong><br>
     Please <a href="{{ url("/add-landscapper-mobile") }}" class="links"><strong> Click on the link</strong> </a>to add a service </div> 
  <?php  } ?>
<script>
    $(function () {
        $("#profile_image").change(function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#profile-img-tag').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        $("#feature_image").change(function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#feature-image-tag').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        $("#change_pass_button").click(function ()
        {
            var old_password = $("#old_password").val();
            var new_password = $("#new_password").val();
            var confirm_new_password = $("#confirm_new_password").val();

            if (new_password != confirm_new_password)
            {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Password Mismatch !!!'
                })
                return false;
            } else
            {
                $.ajax({
                    type: "POST",
                    url: "{{ url('/landscaper/match-password') }}",
                    data: {old_password: old_password},
                    success: function (result) {
                        //alert(result);
                        //return false;
                        if (result == 1) {
                            $("#edit_password").submit();
                            return true;
                        } else {
                            swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Wrong old password !!!'
                            })
                            return false;
                        }
                    }
                });
            }
        });
    });

    function getSeviceHours() {
        $.post("{{ url('/lanscaper/get-service-hours') }}", {
        }, function (data) {
            $("#edit_service").modal('show');
            $("#edit_service_body").html(data);

        });
    }
        
        if($("#service_recurring_1").is(':checked')){
            $("#recurring_service_div_1").show();
        } else {
            $("#recurring_service_div_1").hide();
        }
        if($("#service_recurring_2").is(':checked')){
            $("#recurring_service_div_2").show();
        } else {
            $("#recurring_service_div_2").hide();
        }
        if($("#service_recurring_3").is(':checked')){
            $("#recurring_service_div_3").show();
        } else {
            $("#recurring_service_div_3").hide();
        }
        if($("#service_recurring_4").is(':checked')){
            $("#recurring_service_div_4").show();
        } else {
            $("#recurring_service_div_4").hide();
        }
        if($("#service_recurring_5").is(':checked')){
            $("#recurring_service_div_5").show();
        } else {
            $("#recurring_service_div_5").hide();
        }
        if($("#service_recurring_6").is(':checked')){
            $("#recurring_service_div_6").show();
        } else {
            $("#recurring_service_div_6").hide();
        }
        if($("#service_recurring_7").is(':checked')){
            $("#recurring_service_div_7").show();
        } else {
            $("#recurring_service_div_7").hide();
        }
       
    
</script>
<script>
    function check(){
        
                var feature_img = $("#profile_image").val();
                var ext = feature_img.substring(feature_img.lastIndexOf('.') + 1);
               
                if(feature_img != ''){
                if(ext == 'png' || ext == 'jpeg' || ext == 'jpg' || ext == 'bmp'){
                    $("#profile_image_err").html("");
                }else{
                $("#profile_image_err").html("Upload Only: .png|.jpeg|.jpg|.bmp");
                 window.scrollTo("155", "18");
                 return false;
                }
                }else{
                $("#profile_image_err").html("Select a Profile Image");
                 window.scrollTo("155", "18");
                 return false;
                } 
                
            }
        function check1(){
                var feature_img = $("#feature_image").val();
                var ext = feature_img.substring(feature_img.lastIndexOf('.') + 1);
               
                if(feature_img != ''){
                if(ext == 'png' || ext == 'jpeg' || ext == 'jpg' || ext == 'bmp'){
                    $("#feature_image_err").html("");
                }else{
                $("#feature_image_err").html("Upload Only: .png|.jpeg|.jpg|.bmp");
                 window.scrollTo("730", "580");
                 return false;
                }
                }else{
                $("#feature_image_err").html("Select a Feature Image");
                 window.scrollTo("730", "580");
                 return false;
                }
        }    

        function check_drivers_license() {
            var feature_img = $("#drivers_license").val();
            var ext = feature_img.substring(feature_img.lastIndexOf('.') + 1);
            
            if(feature_img != ''){
            if(ext == 'png' || ext == 'jpeg' || ext == 'jpg' || ext == 'bmp'){
                $("#drivers_license_err").html("");
            }else{
            $("#drivers_license_err").html("Upload Only: .png|.jpeg|.jpg|.bmp");
                window.scrollTo("730", "580");
                return false;
            }
            }else{
            $("#drivers_license_err").html("Select a Driver License");
                window.scrollTo("730", "580");
                return false;
            }
        }
            
        function edit(){
            
           var provider_distance =$("#provider_distance").val();
           if(provider_distance == '' || provider_distance == 0){
                $("#provider_distance_err").html("Enter the range,till the service would be provided");
                $("#provider_distance").focus();
                return false;
           }else if(provider_distance > 50){
                    $("#provider_distance_err").html("The range should not exceed 50 miles");
                    $("#provider_distance").focus();
                     return false;
                }else{
               $("#provider_distance_err").html("");
           }
            
            
            var str = '<?php echo isset($str)?$str:''; ?>';
            var str1 = str.split(",");
            for(var i=0;i<str1.length;i++){
                var check = str1[i];
                if(check == 5){
               
                var acreage_price = $("#win_first_acre").val();
                var zone = $("#win_first_zone").val();
                var next_acreage = $("#win_next_acre").val();
                var next_zone = $("#win_next_zone").val();
                var up_limit_acr = $("#win_acre_limit").val();
                var up_limit_zone = $("#win_zone_limit").val();
                
                
            //    var check_up_limit_acr = up_limit_acr % 0.25 ;
            //    var check_up_limit_zone = up_limit_zone % 3;

               var check_up_limit_acr = (up_limit_acr!="")?(up_limit_acr % 0.25):0;
               var check_up_limit_zone = (up_limit_zone!="")?(up_limit_zone % 3):0;
            //    alert('q');
               
                    if(check_up_limit_acr != '' && $('#acer_div').css('display') == 'block'){
                        $("#win_acre_limit_err").html("The Upper Limit must be a multiple of 0.25");
                        $("#win_acre_limit").focus();
                     return false;
                    }else{
                        $("#win_acre_limit_err").html("");
                    }
                    if(check_up_limit_zone != '' && $('#zone_div').css('display') == 'block'){
                        $("#win_zone_limit_err").html("The Upper Limit must be a multiple of 3");
                         $("#win_zone_limit").focus();
                     return false;
                    }else{
                         $("#win_zone_limit_err").html("");
                    }
                
                if (acreage_price == "" && $('#acer_div').css('display') == 'block'){
                    $("#win_first_acre_err").html("Enter First 0.25 acreage price");
                    $("#win_first_acre").focus();
                     return false;
                     
                  }else if(zone == "" && $('#zone_div').css('display') == 'block'){
                    $("#win_first_zone_err").html("Enter Upto 3 zones price");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone").focus(); 
                     return false;
                }else if(next_acreage == "" && $('#acer_div').css('display') == 'block'){
                    $("#win_next_acre_err").html("Enter Next each 0.25 acreage increase price");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre").focus();
                     return false;
                }else if(next_zone == "" && $('#zone_div').css('display') == 'block'){
                    $("#win_next_zone_err").html("Enter Next each 3 zones increase");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre_err").html("");
                    $("#win_next_zone").focus();
                     return false;
                }else if(up_limit_acr == "" && $('#acer_div').css('display') == 'block'){
                    $("#win_acre_limit_err").html("Enter Upper Limit for acreage");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre_err").html("");
                    $("#win_next_zone_err").html("");
                    $("#win_acre_limit").focus();
                     return false;
                }else if(check_up_limit_acr != '' && $('#acer_div').css('display') == 'block'){
                    $("#win_acre_limit_err").html("The Upper Limit must be a multiple of 0.25");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre_err").html("");
                    $("#win_next_zone_err").html("");
                    $("#win_acre_limit").focus();
                     return false;
                    }else if(up_limit_zone == "" && $('#zone_div').css('display') == 'block'){
                    $("#win_zone_limit_err").html("Enter Upper Limit for zone");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre_err").html("");
                    $("#win_next_zone_err").html("");
                    $("#win_acre_limit_err").html("");
                    $("#win_zone_limit").focus();
                     return false;
                }else if(check_up_limit_zone != '' && $('#zone_div').css('display') == 'block'){
                    $("#win_zone_limit_err").html("The Upper Limit must be a multiple of 3");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre_err").html("");
                    $("#win_next_zone_err").html("");
                    $("#win_acre_limit_err").html("");
                    $("#win_zone_limit").focus();
                     return false;
                    }else{
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre_err").html("");
                    $("#win_next_zone_err").html("");
                    $("#win_acre_limit_err").html("");
                    $("#win_zone_limit_err").html("");
                    }
         
        
        }else if(check == 1){
            
//                     Mowing And Edging
             
                var first_acre = $("#mow_first_acre").val();
                var first_grass = $("#mow_first_grass").val();
                var next_acre = $("#mow_next_acre").val();
                var next_grass = $("#mow_next_grass").val();
                var mow_limit = $("#mow_acre_limit").val();
                var check_mow_limit = mow_limit % 0.25 ;
   
                    if (first_acre == ""){
                    $("#mow_first_acre_err").html("Enter First 0.25 acreage price");
                    $("#mow_first_acre").focus();
                     return false;
                     
                  }else if(first_grass == ""){
                    $("#mow_first_grass_err").html("Enter Upto 6 Inches price");
                    $("#mow_first_acre_err").html("");
                    $("#mow_first_grass").focus(); 
                     return false;
                }else if(next_acre == ""){
                    $("#mow_next_acre_err").html("Enter Next each 0.25 acreage increase price");
                    $("#mow_first_acre_err").html("");
                    $("#mow_first_grass_err").html("");
                    $("#mow_next_acre").focus();
                     return false;
                }else if(next_grass == ""){
                    $("#mow_next_grass_err").html("Enter Next each 6 inches increase");
                    $("#mow_first_acre_err").html("");
                    $("#mow_first_grass_err").html("");
                    $("#mow_next_acre_err").html("");
                    $("#mow_next_grass").focus();
                     return false;
                }else if(mow_limit == ""){
                    $("#mow_acre_limit_err").html("Enter Upper Limit");
                    $("#mow_first_acre_err").html("");
                    $("#mow_first_grass_err").html("");
                    $("#mow_next_acre_err").html("");
                    $("#mow_next_grass_err").html("");
                    $("#mow_acre_limit").focus();
                     return false;
                }else if(check_mow_limit != ''){
                    $("#mow_acre_limit_err").html("The Upper Limit must be a multiple of 0.25");
                    $("#mow_first_acre_err").html("");
                    $("#mow_first_grass_err").html("");
                    $("#mow_next_acre_err").html("");
                    $("#mow_next_grass_err").html("");
                    $("#mow_acre_limit").focus();
                     return false;
                    }else{
                    $("#mow_acre_limit_err").html("");
                    $("#mow_first_acre_err").html("");
                    $("#mow_first_grass_err").html("");
                    $("#mow_next_acre_err").html("");
                    $("#mow_next_grass_err").html("");
                    }
            
        }else if(check == 2){
            
//             Leaf Removal
        
                var leaf_first_acre = $("#leaf_first_acre").val();
                var leaf_next_acre = $("#leaf_next_acre").val();
                var leaf_acre_limit = $("#leaf_acre_limit").val();
                var leaf_light = $("#leaf_light").val();
                var leaf_medium = $("#leaf_medium").val();
                var leaf_heavy = $("#leaf_heavy").val();
                var leaf_over_top = $("#leaf_over_top").val();
                var check_leaf_acre_limit = leaf_acre_limit % 0.25 ;
                
               
                    if (leaf_first_acre == ""){
                    $("#leaf_first_acre_err").html("Enter First 0.25 acreage price");
                    $("#leaf_first_acre").focus();
                     return false;
                     
                  }else if(leaf_next_acre == ""){
                    $("#leaf_next_acre_err").html("Enter Next each 0.25 acreage increase price");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_next_acre").focus(); 
                     return false;
                }else if(leaf_acre_limit == ""){
                    $("#leaf_acre_limit_err").html("Enter Upper Limit");
                    $("#leaf_next_acre_err").html("");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_acre_limit").focus();
                     return false;
                }else  if(check_leaf_acre_limit != ''){
                    $("#leaf_acre_limit_err").html("The Upper Limit must be a multiple of 0.25");
                    $("#leaf_medium_err").html("");
                    $("#leaf_light_err").html("");
                    $("#leaf_next_acre_err").html("");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_heavy_err").html("");
                    $("#leaf_acre_limit").focus();
                     return false;
                    }else if(leaf_light == ""){
                    $("#leaf_light_err").html("Enter Light Accumulation");
                    $("#leaf_acre_limit_err").html("");
                    $("#leaf_next_acre_err").html("");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_light").focus();
                     return false;
                }else if(leaf_medium == ""){
                    $("#leaf_medium_err").html("Enter Medium Accumulation");
                    $("#leaf_light_err").html("");
                    $("#leaf_acre_limit_err").html("");
                    $("#leaf_next_acre_err").html("");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_medium").focus();
                     return false;
                }else if(leaf_heavy == ""){
                    $("#leaf_heavy_err").html("Enter Heavy Accumulation");
                    $("#leaf_medium_err").html("");
                    $("#leaf_light_err").html("");
                    $("#leaf_acre_limit_err").html("");
                    $("#leaf_next_acre_err").html("");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_heavy").focus();
                     return false;
                }else if(leaf_over_top == ""){
                    $("#leaf_over_top_err").html("Enter Over The Top");
                    $("#leaf_medium_err").html("");
                    $("#leaf_light_err").html("");
                    $("#leaf_acre_limit_err").html("");
                    $("#leaf_next_acre_err").html("");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_heavy_err").html("");
                    $("#leaf_over_top").focus();
                     return false;
                }else{
                    $("#leaf_over_top_err").html("");
                    $("#leaf_medium_err").html("");
                    $("#leaf_light_err").html("");
                    $("#leaf_acre_limit_err").html("");
                    $("#leaf_next_acre_err").html("");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_heavy_err").html("");
                    }


                    } else if (check == 3){

                    //    Lawn Treatment

                    var lawn_first_acre = $("#lawn_first_acre").val();
                    var lawn_next_acre = $("#lawn_next_acre").val();
                    var lawn_acre_limit = $("#lawn_acre_limit").val();
                    var check_leaf_acre_limit = lawn_acre_limit % 0.25;
                    if (lawn_first_acre == ""){
                    $("#lawn_first_acre_err").html("Enter First 0.25 acreage price");
                    $("#lawn_first_acre").focus();
                    return false;
                    } else if (lawn_next_acre == ""){
                    $("#lawn_next_acre_err").html("Enter Next each 0.25 acreage increase price");
                    $("#lawn_first_acre_err").html("");
                    $("#lawn_next_acre").focus();
                    return false;
                    } else if (lawn_acre_limit == ""){
                    $("#lawn_acre_limit_err").html("Enter Upper Limit");
                    $("#lawn_first_acre_err").html("");
                    $("#lawn_next_acre_err").html("");
                    $("#lawn_acre_limit").focus();
                    return false;
                    } else if (check_leaf_acre_limit != ''){
                    $("#lawn_acre_limit_err").html("The Upper Limit must be a multiple of 0.25");
                    $("#lawn_first_acre_err").html("");
                    $("#lawn_next_acre_err").html("");
                    $("#lawn_acre_limit").focus();
                    return false;
                    } else{
                    $("#lawn_acre_limit_err").html("");
                    $("#lawn_first_acre_err").html("");
                    $("#lawn_next_acre_err").html("");
                    }

                    } else if (check == 4){


                    //      Aeration

                    var aera_first_acre = $("#aera_first_acre").val();
                    var aera_next_acre = $("#aera_next_acre").val();
                    var aera_acre_limit = $("#aera_acre_limit").val();
                    var check_aera_acre_limit = aera_acre_limit % 0.25;
                    if (aera_first_acre == ""){
                    $("#aera_first_acre_err").html("Enter First 0.25 acreage price");
                    $("#aera_first_acre").focus();
                    return false;
                    } else if (aera_next_acre == ""){
                    $("#aera_next_acre_err").html("Enter Next each 0.25 acreage increase price");
                    $("#aera_first_acre_err").html("");
                    $("#aera_next_acre").focus();
                    return false;
                    } else if (aera_acre_limit == ""){
                    $("#aera_acre_limit_err").html("Enter Upper Limit");
                    $("#aera_first_acre_err").html("");
                    $("#aera_next_acre_err").html("");
                    $("#aera_acre_limit").focus();
                    return false;
                    } else if (check_aera_acre_limit != ''){
                    $("#aera_acre_limit_err").html("The Upper Limit must be a multiple of 0.25");
                    $("#aera_first_acre_err").html("");
                    $("#aera_next_acre_err").html("");
                    $("#aera_acre_limit").focus();
                    return false;
                    } else{
                    $("#aera_acre_limit_err").html("");
                    $("#aera_first_acre_err").html("");
                    $("#aera_next_acre_err").html("");
                    }


                    } else if (check == 6){

//            Pool Cleaning

                    var chlorine = $("#chlorine").val();
                    var saline = $("#saline").val();
                    var spa_hot_tub = $("#spa_hot_tub").val();
                    var inground = $("#inground").val();
                    var above_ground = $("#above_ground").val();
                    var clear = $("#clear").val();
                    var cloudy = $("#cloudy").val();
                    var heavy = $("#heavy").val();
                    if (chlorine == ""){
                    $("#chlorine_err").html("Enter Chlorine Amount");
                    $("#chlorine").focus();
                    return false;
                    } else if (saline == ""){
                    $("#saline_err").html("Enter Saline Amount");
                    $("#chlorine_err").html("");
                    $("#saline").focus();
                    return false;
                    } else if (spa_hot_tub == ""){
                    $("#spa_hot_tub_err").html("Enter a Spa/Hot Tub");
                    $("#saline_err").html("");
                    $("#chlorine_err").html("");
                    $("#spa_hot_tub").focus();
                    return false;
                    }  if (inground == ""){
                    $("#inground_err").html("Enter Inground value");
                    $("#saline_err").html("");
                    $("#chlorine_err").html("");
                    $("#spa_hot_tub_err").html("");
                    $("#inground").focus();
                    return false;
                    } else if (above_ground == ""){
                    $("#above_ground_err").html("Enter Above ground value");
                    $("#saline_err").html("");
                    $("#chlorine_err").html("");
                    $("#spa_hot_tub_err").html("");
                    $("#inground_err").html("");
                    $("#above_ground").focus();
                    return false;
                    } else if (clear == ""){
                    $("#clear_err").html("Enter Relatively Clear");
                    $("#saline_err").html("");
                    $("#chlorine_err").html("");
                    $("#spa_hot_tub_err").html("");
                    $("#inground_err").html("");
                    $("#above_ground_err").html("");
                    $("#clear").focus();
                    return false;
                    }  if (cloudy == ""){
                    $("#cloudy_err").html("Enter Moderately Cloudy");
                    $("#saline_err").html("");
                    $("#chlorine_err").html("");
                    $("#spa_hot_tub_err").html("");
                    $("#inground_err").html("");
                    $("#above_ground_err").html("");
                    $("#clear_err").html("");
                    $("#cloudy").focus();
                    return false;
                    } else if (heavy == ""){
                    $("#heavy_err").html("Enter Heavy Algae Present");
                    $("#cloudy_err").html("");
                    $("#saline_err").html("");
                    $("#chlorine_err").html("");
                    $("#spa_hot_tub_err").html("");
                    $("#inground_err").html("");
                    $("#above_ground_err").html("");
                    $("#clear_err").html("");
                    $("#cloudy_err").html("");
                    $("#heavy").focus();
                    return false;
                    } else{
                    $("#heavy_err").html("");
                    $("#cloudy_err").html("");
                    $("#saline_err").html("");
                    $("#chlorine_err").html("");
                    $("#spa_hot_tub_err").html("");
                    $("#inground_err").html("");
                    $("#above_ground_err").html("");
                    $("#clear_err").html("");
                    $("#cloudy_err").html("");
                    }

                    } else if (check == 7){

//            Snow Removal

                    var first_car = $("#first_car").val();
                    var next_car = $("#next_car").val();
                    var car_limit = $("#car_limit").val();
                    var straight = $("#straight").val();
                    var circular = $("#circular").val();
                    var incline = $("#incline").val();
                    var front_door = $("#front_door").val();
                    var stairs = $("#stairs").val();
                    var side_door = $("#side_door").val();
                    var check_car_limit = car_limit % 2;
                    if (first_car == ""){
                    $("#first_car_err").html("Enter First 2 car ");
                    $("#first_car").focus();
                    return false;
                    } else if (next_car == ""){
                    $("#next_car_err").html("Enter Next each 2 car increase ");
                    $("#first_car_err").html("");
                    $("#next_car").focus();
                    return false;
                    } else if (car_limit == ""){
                    $("#car_limit_err").html("Enter Upper Limit");
                    $("#first_car_err").html("");
                    $("#next_car_err").html("");
                    $("#car_limit").focus();
                    return false;
                    } else if (check_car_limit != ''){
                    $("#car_limit_err").html("The Upper Limit must be a multiple of 2");
                    $("#stairs_err").html("");
                    $("#incline_err").html("");
                    $("#first_car_err").html("");
                    $("#next_car_err").html("");
                    $("#straight_err").html("");
                    $("#circular_err").html("");
                    $("#front_door_err").html("");
                    $("#car_limit").focus();
                    return false;
                    } else if (straight == ""){
                    $("#straight_err").html("Enter Straight details");
                    $("#first_car_err").html("");
                    $("#next_car_err").html("");
                    $("#car_limit_err").html("");
                    $("#straight").focus();
                    return false;
                    } else if (circular == ""){
                    $("#circular_err").html("Enter Circular details");
                    $("#first_car_err").html("");
                    $("#next_car_err").html("");
                    $("#car_limit_err").html("");
                    $("#straight_err").html("");
                    $("#circular").focus();
                    return false;
                    } else if (incline == ""){
                    $("#incline_err").html("Enter Incline details");
                    $("#first_car_err").html("");
                    $("#next_car_err").html("");
                    $("#car_limit_err").html("");
                    $("#straight_err").html("");
                    $("#circular_err").html("");
                    $("#incline").focus();
                    return false;
                    } else if (front_door == ""){
                    $("#front_door_err").html("Enter Front Door Walk Way");
                    $("#incline_err").html("");
                    $("#first_car_err").html("");
                    $("#next_car_err").html("");
                    $("#car_limit_err").html("");
                    $("#straight_err").html("");
                    $("#circular_err").html("");
                    $("#front_door").focus();
                    return false;
                    } else if (stairs == ""){
                    $("#stairs_err").html("Enter Stairs and Front Landing");
                    $("#incline_err").html("");
                    $("#first_car_err").html("");
                    $("#next_car_err").html("");
                    $("#car_limit_err").html("");
                    $("#straight_err").html("");
                    $("#circular_err").html("");
                    $("#front_door_err").html("");
                    $("#stairs").focus();
                    return false;
                    } else if (side_door == ""){
                    $("#side_door_err").html("Enter Side Door Walk Way");
                    $("#stairs_err").html("");
                    $("#incline_err").html("");
                    $("#first_car_err").html("");
                    $("#next_car_err").html("");
                    $("#car_limit_err").html("");
                    $("#straight_err").html("");
                    $("#circular_err").html("");
                    $("#front_door_err").html("");
                    $("#side_door").focus();
                    return false;
                    }
                    else{
                    $("#side_door_err").html("");
                    $("#stairs_err").html("");
                    $("#incline_err").html("");
                    $("#first_car_err").html("");
                    $("#next_car_err").html("");
                    $("#car_limit_err").html("");
                    $("#straight_err").html("");
                    $("#circular_err").html("");
                    $("#front_door_err").html("");
                    }
                    }

            }
        }    
    
</script>
@endsection