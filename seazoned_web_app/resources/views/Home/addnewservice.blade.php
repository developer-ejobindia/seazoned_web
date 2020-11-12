<?php
$i = $add_count;
?>
<div id="new_div_<?php echo $i; ?>">
    <div class="card-block custom-form p-b-0">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="service-name">Service Name</label>
                <select class="form-control service_select_box_cls" name="service_id[]" data-add-id="<?php echo $i; ?>">
                    <option value="0" disabled selected>Select A Option</option>
                    @foreach($services as $service)
                    <option value="{{ $service->id }}">{{ $service->service_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="ajax_service_zone_<?php echo $i; ?>">

        </div>

    </div>

    <hr class="m-0">
    <div class="row">
        <div class="text-right  col-md-12">
            <button class="btn-danger noradius btn float-right" type="button" id="remove_btn" data-removeid="new_div_<?php echo $i; ?>"><i aria-hidden="true" class="fa fa-times-circle"></i> Remove
            </button>
        </div>
    </div>
    <hr class="m-0">
</div>

<script type="text/javascript">
        $(function () {
//            $("#service_select_box").on('change', function () {
            $(".service_select_box_cls").on('change', function () {
                var id = $(this).attr("data-add-id");
                var service_id = this.value;
                
                 $("#one_service_id").val(service_id);
               // $("#service_id_hdn").val($("#service_id_hdn").val()+','+service_id);
                var row = $("#service_id_hdn").val($("#service_id_hdn").val()+','+service_id);
                
//                if($("#service_id_hdn").val()=="")
//                    $("#service_id_hdn").val(service_id);
//                else{
                    var i = $("#add_count").val();
                   
                    if(i<2)
                    {
                        var newstring = $('#service_id_hdn').val().substr(0,i);
                    }
                    else if(i>=2 && i<3)
                    {
                        var j = parseInt(i)+1;
                        var newstring = $('#service_id_hdn').val().substr(0,j);  
                    }else if(i>=3 && i<4)
                    {
                        var j = parseInt(i)+2;
                        var newstring = $('#service_id_hdn').val().substr(0,j);
                    }else if(i>=4 && i<5)
                    {
                        var j = parseInt(i)+3;
                        var newstring = $('#service_id_hdn').val().substr(0,j);    
                    }else if(i>=5 && i<6)
                    {
                        var j = parseInt(i)+4;
                        var newstring = $('#service_id_hdn').val().substr(0,j);   
                    }
                    else
                    {
                        var j = parseInt(i)+5; 
                        var newstring = $('#service_id_hdn').val().substr(0,j);
                    }
                    
                     var ty = $('#service_id_hdn').val();
                     var p = ty.length;
                     var laststring = ty.substr(p-1);  
                     var finalstring = newstring + "," + laststring;
                    
                   
                  $("#service_id_hdn").val("");  
                  $("#service_id_hdn").val(finalstring);

                switch (this.value) {
                    case "1":
                        $.get("{{ url("ajax_pages/services/lawn-mawning") }}", function (data) {
                            $("#ajax_service_zone_"+id).html(data);
                        });
                        break;
                    case "2":
                        $.get("{{ url("ajax_pages/services/leaf-removal") }}", function (data) {
                            $("#ajax_service_zone_"+id).html(data);
                        });
                        break;
                    case "3":
                        $.get("{{ url("ajax_pages/services/lawn-treatment") }}", function (data) {
                            $("#ajax_service_zone_"+id).html(data);
                        });
                        break;
                    case "4":
                        $.get("{{ url("ajax_pages/services/aeration") }}", function (data) {
                            $("#ajax_service_zone_"+id).html(data);
                        });
                        break;
                    case "5":
                        $.get("{{ url("ajax_pages/services/sprinkler_winterizing") }}", function (data) {
                            $("#ajax_service_zone_"+id).html(data);
                        });
                        break;
                    case "6":
                        $.get("{{ url("ajax_pages/services/pool-cleaning") }}", function (data) {
                            $("#ajax_service_zone_"+id).html(data);
                        });
                        break;
                    case "7":
                        $.get("{{ url("ajax_pages/services/snow_removal") }}", function (data) {
                            $("#ajax_service_zone_"+id).html(data);
                        });
                        break;
                    case "8":
                        $.get("{{ url("ajax_pages/services/snow_removal") }}", function (data) {
                            $("#ajax_service_zone_"+id).html(data);
                        });
                        break;
                }
            });            
        });
          function check(){
     
//            Profile Image
     
                var prof_img = $("#avter_pic").val();
                var ext = prof_img.substring(prof_img.lastIndexOf('.') + 1);
                if(prof_img != ''){
                if(ext == 'png' || ext == 'jpeg' || ext == 'jpg' || ext == 'bmp'){
                    $("#avter-img_err").html("");
                }else{
                $("#avter-img_err").html("Upload Only: .png|.jpeg|.jpg|.bmp");
                 window.scrollTo("0", "0");
                 return false;
                }
                }else{
                $("#avter-img_err").html("Upload a Profile Picture");
                 window.scrollTo("0", "0");
                 return false;
                }
     
     
//          Feature image
       
                var feature_img = $("#profile-img").val();
                var ext = feature_img.substring(feature_img.lastIndexOf('.') + 1); 
                if(feature_img != ''){
                if(ext == 'png' || ext == 'jpeg' || ext == 'jpg' || ext == 'bmp'){ 
                   
                    $("#profile-img_err").html("");
                }else{
                $("#profile-img_err").html("Upload Only: .png|.jpeg|.jpg|.bmp");
                 window.scrollTo("730", "580");
                 return false;
                }
                }else{
                $("#profile-img_err").html("Upload a Feature Image");
                 window.scrollTo("730", "580");
                 return false;
                }                 
                
//                Provider Name

                var provider_name = $("#provider_name").val();
                if(provider_name == ''){
                    $("#provider_name_err").html("Enter a Provider name");
                    $("#provider_name").focus();
                     return false;
                }else{
                    $("#provider_name_err").html("");
                }
                
//               Services to be provided within
                
                var provider_distance = $("#provider_distance").val();
                if(provider_distance == ''){
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
                
 //     Ajax Services 
                
              // Sprinkler Winterizing 
var x = $("#service_id_hdn").val();
x = Array.from(new Set(x.split(','))).toString();

var str1 = x.split(",");
            for(var i=0;i<str1.length;i++){
                var check = str1[i];
                if(check == 5){
                var acreage_price = $("#win_first_acre").val();
                var zone = $("#win_first_zone").val();
                var next_acreage = $("#win_next_acre").val();
                var next_zone = $("#win_next_zone").val();
                var up_limit_acr = $("#win_acre_limit").val();
                var up_limit_zone = $("#win_zone_limit").val();

                // var check_up_limit_acr = up_limit_acr % 0.25 ;
                // var check_up_limit_zone = up_limit_zone % 3;

                var check_up_limit_acr = (up_limit_acr!="")?(up_limit_acr % 0.25):0;
                var check_up_limit_zone = (up_limit_zone!="")?(up_limit_zone % 3):0;
               
                    if(check_up_limit_acr != '' && $('#acer_div').css('display') == 'block'){
                        $("#win_acre_limit_err").html("The max size of lawn must be a multiple of 0.25");
                        $("#win_acre_limit").focus();
                     return false;
                    }else{
                        $("#win_acre_limit_err").html("");
                    }
                    if(check_up_limit_zone != '' && $('#zone_div').css('display') == 'block'){
                        $("#win_zone_limit_err").html("The Upper Limit must be a multiple of 3");
                         $("#win_zone_limit").focus();
                    return false;
                    } else{
                    $("#win_zone_limit_err").html("");
                    }

                    if (acreage_price == "" && $('#acer_div').css('display') == 'block'){
                    $("#win_first_acre_err").html("Enter First 0.25 acreage price");
                    $("#win_first_acre").focus();
                    return false;
                    } else if (zone == "" && $('#zone_div').css('display') == 'block'){
                    $("#win_first_zone_err").html("Enter Upto 3 zones price");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone").focus();
                    return false;
                    } else if (next_acreage == "" && $('#acer_div').css('display') == 'block'){
                    $("#win_next_acre_err").html("Enter Next each 0.25 acreage increase price");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre").focus();
                    return false;
                    } else if (next_zone == "" && $('#zone_div').css('display') == 'block'){
                    $("#win_next_zone_err").html("Enter Next each 3 zones increase");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre_err").html("");
                    $("#win_next_zone").focus();
                    return false;
                    } else if (up_limit_acr == "" && $('#acer_div').css('display') == 'block'){
                    $("#win_acre_limit_err").html("Enter the max size of lawn");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre_err").html("");
                    $("#win_next_zone_err").html("");
                    $("#win_acre_limit").focus();
                    return false;
                    }else if(up_limit_acr > 10 && $('#acer_div').css('display') == 'block'){
                    $("#win_acre_limit_err").html("The max size of lawn must not exceed 10 acres");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre_err").html("");
                    $("#win_next_zone_err").html("");
                    $("#win_acre_limit").focus();
                     return false;
                } else if (check_up_limit_acr != '' && $('#acer_div').css('display') == 'block'){
                    $("#win_acre_limit_err").html("The max size of lawn must be a multiple of 0.25");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre_err").html("");
                    $("#win_next_zone_err").html("");
                    $("#win_acre_limit").focus();
                    return false;
                    } else if (up_limit_zone == "" && $('#zone_div').css('display') == 'block'){
                    $("#win_zone_limit_err").html("Enter Upper Limit for zone");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre_err").html("");
                    $("#win_next_zone_err").html("");
                    $("#win_acre_limit_err").html("");
                    $("#win_zone_limit").focus();
                    return false;
                    } else if (check_up_limit_zone != '' && $('#zone_div').css('display') == 'block'){
                    $("#win_zone_limit_err").html("The Upper Limit must be a multiple of 3");
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre_err").html("");
                    $("#win_next_zone_err").html("");
                    $("#win_acre_limit_err").html("");
                    $("#win_zone_limit").focus();
                    return false;
                    } else{
                    $("#win_first_acre_err").html("");
                    $("#win_first_zone_err").html("");
                    $("#win_next_acre_err").html("");
                    $("#win_next_zone_err").html("");
                    $("#win_acre_limit_err").html("");
                    $("#win_zone_limit_err").html("");
                    }


                    } else if (check == 1){

//                     Mowing And Edging

                    var first_acre = $("#mow_first_acre").val();
                    var first_grass = $("#mow_first_grass").val();
                    var next_acre = $("#mow_next_acre").val();
                    var next_grass = $("#mow_next_grass").val();
                    var mow_limit = $("#mow_acre_limit").val();
                    var check_mow_limit = mow_limit % 0.25;
                    if (first_acre == ""){
                    $("#mow_first_acre_err").html("Enter First 0.25 acreage price");
                    $("#mow_first_acre").focus();
                    return false;
                    } else if (first_grass == ""){
                    $("#mow_first_grass_err").html("Enter Upto 6 Inches price");
                    $("#mow_first_acre_err").html("");
                    $("#mow_first_grass").focus();
                    return false;
                    } else if (next_acre == ""){
                    $("#mow_next_acre_err").html("Enter Next each 0.25 acreage increase price");
                    $("#mow_first_acre_err").html("");
                    $("#mow_first_grass_err").html("");
                    $("#mow_next_acre").focus();
                    return false;
                    } else if (next_grass == ""){
                    $("#mow_next_grass_err").html("Enter Next each 6 inches increase");
                    $("#mow_first_acre_err").html("");
                    $("#mow_first_grass_err").html("");
                    $("#mow_next_acre_err").html("");
                    $("#mow_next_grass").focus();
                    return false;
                    } else if (mow_limit == ""){
                    $("#mow_acre_limit_err").html("Enter the max size of lawn");
                    $("#mow_first_acre_err").html("");
                    $("#mow_first_grass_err").html("");
                    $("#mow_next_acre_err").html("");
                    $("#mow_next_grass_err").html("");
                    $("#mow_acre_limit").focus();
                    return false;
                    }else if (mow_limit > 10){
                    $("#mow_acre_limit_err").html("The max size of lawn must not exceed 10 acres");
                    $("#mow_first_acre_err").html("");
                    $("#mow_first_grass_err").html("");
                    $("#mow_next_acre_err").html("");
                    $("#mow_next_grass_err").html("");
                    $("#mow_acre_limit").focus();
                    return false;
                    } else if (check_mow_limit != ''){
                    $("#mow_acre_limit_err").html("The max size of lawn must be a multiple of 0.25");
                    $("#mow_first_acre_err").html("");
                    $("#mow_first_grass_err").html("");
                    $("#mow_next_acre_err").html("");
                    $("#mow_next_grass_err").html("");
                    $("#mow_acre_limit").focus();
                    return false;
                    } else{
                    $("#mow_acre_limit_err").html("");
                    $("#mow_first_acre_err").html("");
                    $("#mow_first_grass_err").html("");
                    $("#mow_next_acre_err").html("");
                    $("#mow_next_grass_err").html("");
                    }

                    } else if (check == 2){

//             Leaf Removal

                    var leaf_first_acre = $("#leaf_first_acre").val();
                    var leaf_next_acre = $("#leaf_next_acre").val();
                    var leaf_acre_limit = $("#leaf_acre_limit").val();
                    var leaf_light = $("#leaf_light").val();
                    var leaf_medium = $("#leaf_medium").val();
                    var leaf_heavy = $("#leaf_heavy").val();
                    var leaf_over_top = $("#leaf_over_top").val();
                    var check_leaf_acre_limit = leaf_acre_limit % 0.25;
                    if (leaf_first_acre == ""){
                    $("#leaf_first_acre_err").html("Enter First 0.25 acreage price");
                    $("#leaf_first_acre").focus();
                    return false;
                    } else if (leaf_next_acre == ""){
                    $("#leaf_next_acre_err").html("Enter Next each 0.25 acreage increase price");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_next_acre").focus();
                    return false;
                    } else if (leaf_acre_limit == ""){
                    $("#leaf_acre_limit_err").html("Enter the max size of lawn");
                    $("#leaf_next_acre_err").html("");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_acre_limit").focus();
                    return false;
                    }else if (leaf_acre_limit > 10){
                    $("#leaf_acre_limit_err").html("The max size of lawn must not exceed 10 acres");
                    $("#leaf_next_acre_err").html("");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_acre_limit").focus();
                    return false;
                    } else  if (check_leaf_acre_limit != ''){
                    $("#leaf_acre_limit_err").html("The max size of lawn must be a multiple of 0.25");
                    $("#leaf_medium_err").html("");
                    $("#leaf_light_err").html("");
                    $("#leaf_next_acre_err").html("");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_heavy_err").html("");
                    $("#leaf_acre_limit").focus();
                    return false;
                    } else if (leaf_light == ""){
                    $("#leaf_light_err").html("Enter Light Accumulation");
                    $("#leaf_acre_limit_err").html("");
                    $("#leaf_next_acre_err").html("");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_light").focus();
                    return false;
                    } else if (leaf_medium == ""){
                    $("#leaf_medium_err").html("Enter Medium Accumulation");
                    $("#leaf_light_err").html("");
                    $("#leaf_acre_limit_err").html("");
                    $("#leaf_next_acre_err").html("");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_medium").focus();
                    return false;
                    } else if (leaf_heavy == ""){
                    $("#leaf_heavy_err").html("Enter Heavy Accumulation");
                    $("#leaf_medium_err").html("");
                    $("#leaf_light_err").html("");
                    $("#leaf_acre_limit_err").html("");
                    $("#leaf_next_acre_err").html("");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_heavy").focus();
                    return false;
                    } else if (leaf_over_top == ""){
                    $("#leaf_over_top_err").html("Enter Over The Top");
                    $("#leaf_medium_err").html("");
                    $("#leaf_light_err").html("");
                    $("#leaf_acre_limit_err").html("");
                    $("#leaf_next_acre_err").html("");
                    $("#leaf_first_acre_err").html("");
                    $("#leaf_heavy_err").html("");
                    $("#leaf_over_top").focus();
                    return false;
                    } else{
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
                    $("#lawn_acre_limit_err").html("Enter the max size of lawn");
                    $("#lawn_first_acre_err").html("");
                    $("#lawn_next_acre_err").html("");
                    $("#lawn_acre_limit").focus();
                    return false;
                    } else if (lawn_acre_limit > 10){
                    $("#lawn_acre_limit_err").html("The max size of lawn must not exceed 10 acres");
                    $("#lawn_first_acre_err").html("");
                    $("#lawn_next_acre_err").html("");
                    $("#lawn_acre_limit").focus();
                    return false;
                    }else if (check_leaf_acre_limit != ''){
                    $("#lawn_acre_limit_err").html("The max size of lawn must be a multiple of 0.25");
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
//                Service Hours 


                    if ($('input[type="checkbox"]:checked').length > 0){
                    $("#err_service").hide();
                    $("#err_service").html("");
                    if ($("#check_0").prop('checked') == true){
                    var start_time0 = $("#dtp_start_0").val();
                    var end_time0 = $("#dtp_end_0").val();
                    if (start_time0 == '' && end_time0 == ''){
                    $("#start_time_err_0").html("Enter the start time");
                    $("#end_time_err_0").html("Enter the end time");
                    $("#dtp_start_0").focus();
                    return false;
                    }
                    else if (start_time0 == ''){
                    $("#start_time_err_0").html("Enter the start time");
                    $("#dtp_start_0").focus();
                    return false;
                    }
                    else if (end_time0 == ''){
                    $("#end_time_err_0").html("Enter the end time");
                    $("#start_time_err_0").html("");
                    $("#dtp_end_0").focus();
                    return false;
                    } else if (start_time0 > end_time0){
                    $("#start_time_err_0").html("The start time should not exceed end time");
                    $("#end_time_err_0").html("");
                    $("#dtp_start_0").focus();
                    return false;
                    } else if (start_time0 == end_time0){
                    $("#end_time_err_0").html("");
                    $("#start_time_err_0").html("The start time and end time cannot be same");
                    $("#dtp_end_0").focus();
                    return false;
                    } else{
                    $("#start_time_err_0").html("");
                    $("#end_time_err_0").html("");
                    }
                    } else{
                    $("#start_time_err_0").html("");
                    $("#end_time_err_0").html("");
                    }

                    if ($("#check_1").prop('checked') == true){

                    var start_time1 = $("#dtp_start_1").val();
                    var end_time1 = $("#dtp_end_1").val();
                    if (start_time1 == '' && end_time1 == ''){
                    $("#start_time_err_1").html("Enter the start time");
                    $("#end_time_err_1").html("Enter the end time");
                    $("#dtp_start_1").focus();
                    return false;
                    }
                    else if (start_time1 == ''){
                    $("#start_time_err_1").html("Enter the start time");
                    $("#dtp_start_1").focus();
                    return false;
                    }
                    else if (end_time1 == ''){
                    $("#end_time_err_1").html("Enter the end time");
                    $("#start_time_err_1").html("");
                    $("#dtp_end_1").focus();
                    return false;
                    } else if (start_time1 > end_time1){
                    $("#start_time_err_1").html("The start time should not exceed end time");
                    $("#end_time_err_1").html("");
                    $("#dtp_start_1").focus();
                    return false;
                    } else if (start_time1 == end_time1){
                    $("#end_time_err_1").html("");
                    $("#start_time_err_1").html("The start time and end time cannot be same");
                    $("#dtp_end_1").focus();
                    return false;
                    } else{
                    $("#start_time_err_1").html("");
                    $("#end_time_err_1").html("");
                    }
                    } else{
                    $("#start_time_err_1").html("");
                    $("#end_time_err_1").html("");
                    }


                    if ($("#check_2").prop('checked') == true){

                    var start_time2 = $("#dtp_start_2").val();
                    var end_time2 = $("#dtp_end_2").val();
                    if (start_time2 == '' && end_time2 == ''){
                    $("#start_time_err_2").html("Enter the start time");
                    $("#end_time_err_2").html("Enter the end time");
                    $("#dtp_start_2").focus();
                    return false;
                    }
                    else if (start_time2 == ''){
                    $("#start_time_err_2").html("Enter the start time");
                    $("#dtp_start_2").focus();
                    return false;
                    }
                    else if (end_time2 == ''){
                    $("#end_time_err_2").html("Enter the end time");
                    $("#start_time_err_2").html("");
                    $("#dtp_end_2").focus();
                    return false;
                    } else if (start_time2 > end_time2){
                    $("#start_time_err_2").html("The start time should not exceed end time");
                    $("#end_time_err_2").html("");
                    $("#dtp_start_2").focus();
                    return false;
                    } else if (start_time2 == end_time2){
                    $("#end_time_err_2").html("");
                    $("#start_time_err_2").html("The start time and end time cannot be same");
                    $("#dtp_end_2").focus();
                    return false;
                    } else{
                    $("#start_time_err_2").html("");
                    $("#end_time_err_2").html("");
                    }
                    } else{
                    $("#start_time_err_2").html("");
                    $("#end_time_err_2").html("");
                    }

                    if ($("#check_3").prop('checked') == true){

                    var start_time3 = $("#dtp_start_3").val();
                    var end_time3 = $("#dtp_end_3").val();
                    if (start_time3 == '' && end_time3 == ''){
                    $("#start_time_err_3").html("Enter the start time");
                    $("#end_time_err_3").html("Enter the end time");
                    $("#dtp_start_3").focus();
                    return false;
                    }
                    else if (start_time3 == ''){
                    $("#start_time_err_3").html("Enter the start time");
                    $("#dtp_start_3").focus();
                    return false;
                    }
                    else if (end_time3 == ''){
                    $("#end_time_err_3").html("Enter the end time");
                    $("#start_time_err_3").html("");
                    $("#dtp_end_3").focus();
                    return false;
                    } else if (start_time3 > end_time3){
                    $("#start_time_err_3").html("The start time should not exceed end time");
                    $("#end_time_err_3").html("");
                    $("#dtp_start_3").focus();
                    return false;
                    } else if (start_time3 == end_time3){
                    $("#end_time_err_3").html("");
                    $("#start_time_err_3").html("The start time and end time cannot be same");
                    $("#dtp_end_3").focus();
                    return false;
                    } else{
                    $("#start_time_err_3").html("");
                    $("#end_time_err_3").html("");
                    }
                    } else{
                    $("#start_time_err_3").html("");
                    $("#end_time_err_3").html("");
                    }


                    if ($("#check_4").prop('checked') == true){

                    var start_time4 = $("#dtp_start_4").val();
                    var end_time4 = $("#dtp_end_4").val();
                    if (start_time4 == '' && end_time4 == ''){
                    $("#start_time_err_4").html("Enter the start time");
                    $("#end_time_err_4").html("Enter the end time");
                    $("#dtp_start_4").focus();
                    return false;
                    }
                    else if (start_time4 == ''){
                    $("#start_time_err_4").html("Enter the start time");
                    $("#dtp_start_4").focus();
                    return false;
                    }
                    else if (end_time4 == ''){
                    $("#end_time_err_4").html("Enter the end time");
                    $("#start_time_err_4").html("");
                    $("#dtp_end_4").focus();
                    return false;
                    } else if (start_time4 > end_time4){
                    $("#start_time_err_4").html("The start time should not exceed end time");
                    $("#end_time_err_4").html("");
                    $("#dtp_start_4").focus();
                    return false;
                    } else if (start_time4 == end_time4){
                    $("#end_time_err_4").html("");
                    $("#start_time_err_4").html("The start time and end time cannot be same");
                    $("#dtp_end_4").focus();
                    return false;
                    } else{
                    $("#start_time_err_4").html("");
                    $("#end_time_err_4").html("");
                    }
                    } else{
                    $("#start_time_err_4").html("");
                    $("#end_time_err_4").html("");
                    }


                    if ($("#check_5").prop('checked') == true){

                    var start_time5 = $("#dtp_start_5").val();
                    var end_time5 = $("#dtp_end_5").val();
                    if (start_time5 == '' && end_time5 == ''){
                    $("#start_time_err_5").html("Enter the start time");
                    $("#end_time_err_5").html("Enter the end time");
                    $("#dtp_start_5").focus();
                    return false;
                    }
                    else if (start_time5 == ''){
                    $("#start_time_err_5").html("Enter the start time");
                    $("#dtp_start_5").focus();
                    return false;
                    }
                    else if (end_time5 == ''){
                    $("#end_time_err_5").html("Enter the end time");
                    $("#start_time_err_5").html("");
                    $("#dtp_end_5").focus();
                    return false;
                    } else if (start_time5 > end_time5){
                    $("#start_time_err_5").html("The start time should not exceed end time");
                    $("#end_time_err_5").html("");
                    $("#dtp_start_5").focus();
                    return false;
                    } else if (start_time5 == end_time5){
                    $("#end_time_err_5").html("");
                    $("#start_time_err_5").html("The start time and end time cannot be same");
                    $("#dtp_end_5").focus();
                    return false;
                    } else{
                    $("#start_time_err_5").html("");
                    $("#end_time_err_5").html("");
                    }
                    } else{
                    $("#start_time_err_5").html("");
                    $("#end_time_err_5").html("");
                    }

                    if ($("#check_6").prop('checked') == true){

                    var start_time6 = $("#dtp_start_6").val();
                    var end_time6 = $("#dtp_end_6").val();
                    if (start_time6 == '' && end_time6 == ''){
                    $("#start_time_err_6").html("Enter the start time");
                    $("#end_time_err_6").html("Enter the end time");
                    $("#dtp_start_6").focus();
                    return false;
                    }
                    else if (start_time6 == ''){
                    $("#start_time_err_6").html("Enter the start time");
                    $("#dtp_start_6").focus();
                    return false;
                    }
                    else if (end_time6 == ''){
                    $("#end_time_err_6").html("Enter the end time");
                    $("#start_time_err_6").html("");
                    $("#dtp_end_6").focus();
                    return false;
                    } else if (start_time6 > end_time6){
                    $("#start_time_err_6").html("The start time should not exceed end time");
                    $("#end_time_err_6").html("");
                    $("#dtp_start_6").focus();
                    return false;
                    } else if (start_time6 == end_time6){
                    $("#end_time_err_6").html("");
                    $("#start_time_err_6").html("The start time and end time cannot be same");
                    $("#dtp_end_6").focus();
                    return false;
                    } else{
                    $("#start_time_err_6").html("");
                    $("#end_time_err_6").html("");
                    }
                    } else{
                    $("#start_time_err_6").html("");
                    $("#end_time_err_6").html("");
                    }
                    } else {
                    $("#err_service").show();
                    $("#err_service").html("Please Check Service Hours.");
                    $("#check_0").focus();
                    return false;
                    }

//                Discount of Recurring Services

                    var value0 = $("#recurring_services_0").val();
                    var value1 = $("#recurring_services_1").val();
                    var value2 = $("#recurring_services_2").val();
                    var value3 = $("#recurring_services_3").val();
                    if (value0 == ''){
                    $("#recurring_services_err").html("Please fill up the discount price of recurring services");
                    $("#recurring_services_0").focus();
                    return false;
                    } else if (value1 == '') {
                    $("#recurring_services_err").html("Please fill up the discount price of recurring services");
                    $("#recurring_services_1").focus();
                    return false;
                    } else if (value2 == ''){
                    $("#recurring_services_err").html("Please fill up the discount price of recurring services");
                    $("#recurring_services_2").focus();
                    return false;
                    } else if (value3 == ''){
                    $("#recurring_services_err").html("Please fill up the discount price of recurring services");
                    $("#recurring_services_3").focus();
                    return false;
                    }
                    else{
                    $("#recurring_services_err").html("");
                    }

//             Description

                    var description = $("#description").val();
                    if (description == ''){
                    $("#description_err").html("Enter Description");
                    $("#description").focus();
                    return false;
                    } else{
                    $("#description_err").html("");
                    }
                    }     
               
        
    </script>