<form class="m-t-30" action="{{ url("/lanscaper/edit-service-hours") }}" method="post" enctype="multipart/form-data" >                            
    <?php
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thrusday', 'Friday', 'Saturday', 'Sunday'];
    ?> <span class="text text-danger" id="err_service"></span>
    <div class="card card-no-radius m-b-30">
        <div class="card-block custom-form p-0">
            <div class="row m-0 p-y-10">
               
                <div class="col-4 medium">Day</div>
                <div class="col medium">Start Time</div>
                <div class="col medium">End Time</div>
            </div>
            <hr class="m-0">
            <?php foreach ($service_time as $index => $val) { ?>
                <div class="row m-0 p-y-10">
                    <div class="col-4">
                        <div class="form-group m-0">
                            <div class="checkbox styled-checkbox m-t-5">
                                <label class="m-b-0">
                                    <input type="checkbox" name="days[<?php echo $index; ?>]" id="check_<?php echo $index; ?>" value="<?php echo $val['service_day']; ?>" <?php echo($val['start_time'] != "") ? 'checked' : ''; ?>/>
                                    <span><?php echo $val['service_day']; ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group m-0">
                            <div class="input-group date form_time custom-icon timepicker">
                                <input type="text" placeholder="Time" class="form-control" id="dtp_start_<?php echo $index; ?>" name="start[<?php echo $index; ?>]" value="<?php echo ($val['start_time']!='')?date('h:i A',strtotime($val['start_time'])):''; ?>" />
                                <span class="input-group-addon">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                            <span class="text text-danger" id="start_time_err_<?php echo $index; ?>"> </span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group m-0">
                            <div class="input-group date form_time custom-icon timepicker">
                                <input type="text" placeholder="Time" class="form-control" id="dtp_end_<?php echo $index; ?>" name="end[<?php echo $index; ?>]"  value="<?php echo ($val['end_time']!='')?date('h:i A',strtotime($val['end_time'])):''; ?>"/>
                        <!--<input class="form-control" size="16" type="text" value="" >-->
                                <span class="input-group-addon">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                            <span class="text text-danger" id="end_time_err_<?php echo $index; ?>"> </span>
                        </div>
                    </div>
                </div>
                <hr class="m-0">
                <input type="hidden" name="landscaper_id" value="<?php echo $landscaper_id; ?>">
                <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
            <?php } ?>       

        </div>
    </div>
    <div class="card-footer p-y-20">
        <input type="submit" value="Update Service Hours" onClick="return check()" class="btn btn-success">
    </div>
</form>

<script type="text/javascript" src="{{ url('/') }}/default/plugins/custom-timepicker/js/custom_timepicker.js"></script>
<script>
    function check(){
      
     if ($('input[type="checkbox"]:checked').length > 0){  
                    $("#err_service").hide();
                    $("#err_service").html("");
                    if ($("#check_0").prop('checked') == true){
                    var start_time0 = $("#dtp_start_0").val();
                    var end_time0 = $("#dtp_end_0").val();
                    
                    
                    var start_time = start_time0;
                    var end_time = end_time0;
                    start_time = start_time.split(" ");
                    var time = start_time[0].split(":");
                    var stime = time[0];
                    if(start_time[1] == "PM" && stime<12) stime = parseInt(stime) + 12;
                    start_time = stime + ":" + time[1] + ":00";

                    end_time = end_time.split(" ");
                    var time1 = end_time[0].split(":");
                    var etime = time1[0];
                    if(end_time[1] == "PM" && etime<12) etime = parseInt(etime) + 12;
                    end_time = etime + ":" + time1[1] + ":00";

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
                    } else if (start_time > end_time){
                    $("#start_time_err_0").html("The start time should not exceed end time");
                    $("#end_time_err_0").html("");
                    $("#dtp_start_0").focus();
                    return false;
                    } else if (start_time == end_time){
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
                    return false;
                    }

                    if ($("#check_1").prop('checked') == true){

                    var start_time1 = $("#dtp_start_1").val();
                    var end_time1 = $("#dtp_end_1").val();
                    
                    var start_time = start_time1;
                    var end_time = end_time1;
                    start_time = start_time.split(" ");
                    var time = start_time[0].split(":");
                    var stime = time[0];
                    if(start_time[1] == "PM" && stime<12) stime = parseInt(stime) + 12;
                    start_time = stime + ":" + time[1] + ":00";

                    end_time = end_time.split(" ");
                    var time1 = end_time[0].split(":");
                    var etime = time1[0];
                    if(end_time[1] == "PM" && etime<12) etime = parseInt(etime) + 12;
                    end_time = etime + ":" + time1[1] + ":00";
                    
                    
                    
                    
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
                    } else if (start_time > end_time){
                    $("#start_time_err_1").html("The start time should not exceed end time");
                    $("#end_time_err_1").html("");
                    $("#dtp_start_1").focus();
                    return false;
                    } else if (start_time == end_time){
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
                    
                    var start_time = start_time2;
                    var end_time = end_time2;
                    start_time = start_time.split(" ");
                    var time = start_time[0].split(":");
                    var stime = time[0];
                    if(start_time[1] == "PM" && stime<12) stime = parseInt(stime) + 12;
                    start_time = stime + ":" + time[1] + ":00";

                    end_time = end_time.split(" ");
                    var time1 = end_time[0].split(":");
                    var etime = time1[0];
                    if(end_time[1] == "PM" && etime<12) etime = parseInt(etime) + 12;
                    end_time = etime + ":" + time1[1] + ":00";
                    
                    
                    
                    
                    
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
                    } else if (start_time > end_time){
                    $("#start_time_err_2").html("The start time should not exceed end time");
                    $("#end_time_err_2").html("");
                    $("#dtp_start_2").focus();
                    return false;
                    } else if (start_time == end_time){
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
                    
                    var start_time = start_time3;
                    var end_time = end_time3;
                    start_time = start_time.split(" ");
                    var time = start_time[0].split(":");
                    var stime = time[0];
                    if(start_time[1] == "PM" && stime<12) stime = parseInt(stime) + 12;
                    start_time = stime + ":" + time[1] + ":00";

                    end_time = end_time.split(" ");
                    var time1 = end_time[0].split(":");
                    var etime = time1[0];
                    if(end_time[1] == "PM" && etime<12) etime = parseInt(etime) + 12;
                    end_time = etime + ":" + time1[1] + ":00";
                     
               
                    
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
                    } else if (start_time > end_time){
                    $("#start_time_err_3").html("The start time should not exceed end time");
                    $("#end_time_err_3").html("");
                    $("#dtp_start_3").focus();
                    return false;
                    } else if (start_time == end_time){
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
                    
                    var start_time = start_time4;
                    var end_time = end_time4;
                    start_time = start_time.split(" ");
                    var time = start_time[0].split(":");
                    var stime = time[0];
                    if(start_time[1] == "PM" && stime<12) stime = parseInt(stime) + 12;
                    start_time = stime + ":" + time[1] + ":00";

                    end_time = end_time.split(" ");
                    var time1 = end_time[0].split(":");
                    var etime = time1[0];
                    if(end_time[1] == "PM" && etime<12) etime = parseInt(etime) + 12;
                    end_time = etime + ":" + time1[1] + ":00";
                    
                    
                    
                    
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
                    } else if (start_time > end_time){
                    $("#start_time_err_4").html("The start time should not exceed end time");
                    $("#end_time_err_4").html("");
                    $("#dtp_start_4").focus();
                    return false;
                    } else if (start_time == end_time){
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
                    
                    var start_time = start_time5;
                    var end_time = end_time5;
                    start_time = start_time.split(" ");
                    var time = start_time[0].split(":");
                    var stime = time[0];
                    if(start_time[1] == "PM" && stime<12) stime = parseInt(stime) + 12;
                    start_time = stime + ":" + time[1] + ":00";

                    end_time = end_time.split(" ");
                    var time1 = end_time[0].split(":");
                    var etime = time1[0];
                    if(end_time[1] == "PM" && etime<12) etime = parseInt(etime) + 12;
                    end_time = etime + ":" + time1[1] + ":00";
                    
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
                    } else if (start_time > end_time){
                    $("#start_time_err_5").html("The start time should not exceed end time");
                    $("#end_time_err_5").html("");
                    $("#dtp_start_5").focus();
                    return false;
                    } else if (start_time == end_time){
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
                    
                    
                    var start_time = start_time6;
                    var end_time = end_time6;
                    start_time = start_time.split(" ");
                    var time = start_time[0].split(":");
                    var stime = time[0];
                    if(start_time[1] == "PM" && stime<12) stime = parseInt(stime) + 12;
                    start_time = stime + ":" + time[1] + ":00";

                    end_time = end_time.split(" ");
                    var time1 = end_time[0].split(":");
                    var etime = time1[0];
                    if(end_time[1] == "PM" && etime<12) etime = parseInt(etime) + 12;
                    end_time = etime + ":" + time1[1] + ":00";
                    
                    
                    
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
                    } else if (start_time > end_time){
                    $("#start_time_err_6").html("The start time should not exceed end time");
                    $("#end_time_err_6").html("");
                    $("#dtp_start_6").focus();
                    return false;
                    } else if (start_time == end_time){
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
//                    $("#check_0").focus();
                    return false;
                    }
                }   
    </script>