//var submit_flag = 0;
$(document).ready(function(){    
    $('form').submit(function(){
        var form_id = $(this).attr('id');
        var final_value = 1;
        var flag = 1; 
        $(this).find('input[type=hidden], input[type=text], input[type=password], input[type=file], textarea, select').each(function(i, item){
            if($("#" + item.id).hasClass('required')){
                flag = validate_required('#' + item.id);
                if(flag == 0){
                    final_value = 0;
                }
            }
            if($("#" + item.id).hasClass('email')){
                flag = validate_email('#' + item.id);
                if(flag == 0){
                    final_value = 0;
                }
            }
            if($("#" + item.id).hasClass('image')){
                flag = validate_image('#' + item.id);
                if(flag == 0){
                    final_value = 0;
                }
            }
            if($("#" + item.id).hasClass('doc')){
                flag = validate_document('#' + item.id);
                if(flag == 0){
                    final_value = 0;
                }
            }
            
            if($("#" + item.id).hasClass('phone')){
                flag = validate_phone('#' + item.id);
                if(flag == 0){
                    final_value = 0;
                }
            }
            
            if($("#" + item.id).hasClass('decimal')){
                flag = validate_decimal('#' + item.id);
                if(flag == 0){
                    final_value = 0;
                }
            }
            
            if($("#" + item.id).hasClass('integer')){
                flag = validate_integer('#' + item.id);
                if(flag == 0){
                    final_value = 0;
                }
            }
            
            if($("#" + item.id).hasClass('url')){
                flag = validate_url('#' + item.id);
                if(flag == 0){
                    final_value = 0;
                }
            }
            
//            if(submit_flag == 0){
//                if($("#" + item.id).hasClass('duplicate_email')){
//                    flag = checkEmail('#' + item.id, 1, form_id);
//                    if(flag == 0){
//                        final_value = 0;
//                    }
//                }
//            }

            if($("#" + item.id).hasClass('request_document')){
                flag = validate_request_document('#' + item.id);
                if(flag == 0){
                    final_value = 0;
                }
            }

        });
        
        
        /*
         * This section is for matching password/confirm password and email/confirm email
         */
        
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        var user_password = $("#user_password").val();
        var user_confirm_password = $("#user_confirm_password").val();
        var password_edit = $("#password_edit").val();
        var confirm_password_edit = $("#confirm_password_edit").val();
        var startdate = $("#startdate").val();
        var enddate = $("#enddate").val();
        var startdate_time = $("#startdate").val()+" "+$("#starttime").val();
        var enddate_time = $("#enddate").val()+" "+$("#endtime").val();
        var date_time_request_check = $("#date_time_request_check").val();
        var date_time_request_formatted = $("#date_time_request_formatted").val();
        
        var pass_check = matchValue(confirm_password, password, 'confirm_password', 'Confirm Password', 'Password');
        var user_pass_check = matchValue(user_password, user_confirm_password, 'user_confirm_password', 'Confirm Password', 'Password');
        var pass_check_edit = matchValue(confirm_password_edit, password_edit, 'confirm_password_edit', 'Confirm Password', 'New Password');
        var valid_date = compareValue(startdate, enddate, 'enddate', 'Start Date', 'End Date');
        var valid_datetime = compareValue(startdate_time, enddate_time, 'endtime', 'Start Time', 'End Time');
        var valid_starttime = compareTime(date_time_request_check, startdate_time, 'starttime', 'Start date & time', date_time_request_formatted);
        var valid_endtime = compareTime(date_time_request_check, enddate_time, 'endtime', 'End date & time', date_time_request_formatted);

        if(pass_check == 0 || user_pass_check == 0 || pass_check_edit == 0 || valid_date == 0 || valid_datetime == "" || valid_starttime == "" || valid_endtime == ""){
            final_value = 0;
        }
        
        /*
         * End of matching field validation
         */
                
        if(final_value == 0)
            return false; 
    });
    
    $('form').find('input[type=hidden], input[type=text], input[type=password], input[type=file], textarea, select').on('blur keyup keypress change',function(){
        if($(this).hasClass('required')){
            validate_required('#'+$(this).attr('id'));
        }
        
        if($(this).hasClass('email')){
            validate_email('#'+$(this).attr('id'));
        }
        
        if($(this).hasClass('image')){
            validate_image('#'+$(this).attr('id'));
        }
        if($(this).hasClass('doc')){
            validate_document('#'+$(this).attr('id'));
        }
        
        if($(this).hasClass('phone')){
            validate_phone('#'+$(this).attr('id'));
        }
        
        if($(this).hasClass('decimal')){
            validate_decimal('#'+$(this).attr('id'));
        }
        
        if($(this).hasClass('integer')){
            validate_integer('#'+$(this).attr('id'));
        }
        
        if($(this).hasClass('url')){
            validate_url('#'+$(this).attr('id'));
        }
        
        if($(this).hasClass('duplicate_email')){
            checkEmail('#'+$(this).attr('id'));
        }

        if($(this).hasClass('request_document')){
            validate_request_document('#'+$(this).attr('id'));
        }
        
    });  
    
    $("#startdate").blur(function(){
        var startdate = $("#startdate").val();
        var enddate = $("#enddate").val();
        var startdate_time = $("#startdate").val()+" "+$("#starttime").val();
        var date_time_request_check = $("#date_time_request_check").val();
        var date_time_request_formatted = $("#date_time_request_formatted").val();
        
        compareValue(startdate, enddate, 'enddate', 'Start Date', 'End Date');
        compareTime(date_time_request_check, startdate_time, 'starttime', 'Start date & time', date_time_request_formatted);
    });

    $("#enddate").blur(function(){
        var startdate = $("#startdate").val();
        var enddate = $("#enddate").val();
        var enddate_time = $("#enddate").val()+" "+$("#endtime").val();
        var date_time_request_check = $("#date_time_request_check").val();
        var date_time_request_formatted = $("#date_time_request_formatted").val();
        
        compareValue(startdate, enddate, 'enddate', 'Start Date', 'End Date');
        compareTime(date_time_request_check, enddate_time, 'endtime', 'End date & time', date_time_request_formatted);
    });

    $("#starttime").blur(function(){
        var startdate_time = $("#startdate").val()+" "+$("#starttime").val();
        var enddate_time = $("#enddate").val()+" "+$("#endtime").val();
        var date_time_request_check = $("#date_time_request_check").val();
        var date_time_request_formatted = $("#date_time_request_formatted").val();
        
        compareValue(startdate_time, enddate_time, 'endtime', 'Start Time', 'End Time');
        compareTime(date_time_request_check, startdate_time, 'starttime', 'Start date & time', date_time_request_formatted);
    });

    $("#endtime").blur(function(){
        var startdate_time = $("#startdate").val()+" "+$("#starttime").val();
        var enddate_time = $("#enddate").val()+" "+$("#endtime").val();
        var date_time_request_check = $("#date_time_request_check").val();
        var date_time_request_formatted = $("#date_time_request_formatted").val();
        
        compareValue(startdate_time, enddate_time, 'endtime', 'Start Time', 'End Time');
        compareTime(date_time_request_check, enddate_time, 'endtime', 'End date & time', date_time_request_formatted);
    });

    $("#confirm_password").blur(function(){
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        
        matchValue(confirm_password, password, 'confirm_password', 'Confirm Password', 'Password');
    });
    
    $("#confirm_password").keyup(function(){
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        
        matchValue(confirm_password, password, 'confirm_password', 'Confirm Password', 'Password');
    });
    
    $("#password").blur(function(){
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        
        matchValue(confirm_password, password, 'confirm_password', 'Confirm Password', 'Password');
    });
    
    $("#password").keyup(function(){
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        
        matchValue(confirm_password, password, 'confirm_password', 'Confirm Password', 'Password');
    });
    
    $("#user_password").blur(function(){
        var user_password = $("#user_password").val();
        var user_confirm_password = $("#user_confirm_password").val();
        
        matchValue(user_password, user_confirm_password, 'user_confirm_password', 'Confirm Password', 'Password');
    });
    
    $("#user_confirm_password").blur(function(){
        var user_password = $("#user_password").val();
        var user_confirm_password = $("#user_confirm_password").val();
        
        matchValue(user_password, user_confirm_password, 'user_confirm_password', 'Confirm Password', 'Password');
    });

    $("#user_password").keyup(function(){
        var user_password = $("#user_password").val();
        var user_confirm_password = $("#user_confirm_password").val();
        
        matchValue(user_password, user_confirm_password, 'user_confirm_password', 'Confirm Password', 'Password');
    });
    
    $("#user_confirm_password").keyup(function(){
        var user_password = $("#user_password").val();
        var user_confirm_password = $("#user_confirm_password").val();
        
        matchValue(user_password, user_confirm_password, 'user_confirm_password', 'Confirm Password', 'Password');
    });
    
    $("#confirm_password_edit").blur(function(){
        var password_edit = $("#password_edit").val();
        var confirm_password_edit = $("#confirm_password_edit").val();
        
        matchValue(confirm_password_edit, password_edit, 'confirm_password_edit', 'Confirm Password', 'New Password');
    });
    
    $("#confirm_password_edit").keyup(function(){
        var password_edit = $("#password_edit").val();
        var confirm_password_edit = $("#confirm_password_edit").val();
        
        matchValue(confirm_password_edit, password_edit, 'confirm_password_edit', 'Confirm Password', 'New Password');
    });
    
    $("#password_edit").blur(function(){
        var password_edit = $("#password_edit").val();
        var confirm_password_edit = $("#confirm_password_edit").val();
        
        matchValue(confirm_password_edit, password_edit, 'confirm_password_edit', 'Confirm Password', 'New Password');
    });
    
    $("#password_edit").keyup(function(){
        var password_edit = $("#password_edit").val();
        var confirm_password_edit = $("#confirm_password_edit").val();
        
        matchValue(confirm_password_edit, password_edit, 'confirm_password_edit', 'Confirm Password', 'New Password');
    });
    
    $('.phone').keypress(function(e){
       var data = $(this).val();
       if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)){
           return false;
       }
       $(this).val(data);
       var part1 = /^[0-9]{3}$/;
       var part2 = /^\(?[0-9]{3}\)[ . ]?[0-9]{3}$/;
       var temp = data;
       if(data.trim() != ""){
           if(data.match(part1) && e.keyCode != 8){
               data = '('+data + ') ';
               $(this).val(data);
           }else if(data.match(part2) && e.keyCode != 8){
               data = data + '-';
               $(this).val(data);
           }
       }else{
           $(this).val("");
       }
    });
    
});

function validate_required(field_id){
    var field_val = $(field_id).val();
    //    var field_name = $(field_id).attr('placeholder');
    var field_name = $(field_id).parent('div').find('label').text();
    
    if(field_name == ""){
        field_name = $(field_id).parent('div').parent('div').find('label').text();
        if(field_val == ''){
            $(field_id).parent('div').parent('div').find('.errorMessage').html(field_name+' cannot be blank');
            //        $(field_id).parent('div').find('.required').addClass('error');
            return 0;
        }else{
            $(field_id).parent('div').parent('div').find('.errorMessage').html('');
            //        $(field_id).parent('div').find('.required').removeClass('error');
            return 1;
        }
    }else{    
        if(field_val == ''){
            $(field_id).parent('div').find('.errorMessage').html(field_name+' cannot be blank');
            //        $(field_id).parent('div').find('.required').addClass('error');
            return 0;
        }else{
            $(field_id).parent('div').find('.errorMessage').html('');
            //        $(field_id).parent('div').find('.required').removeClass('error');
            return 1;
        }
    }
}

function validate_email(field_id){
    var field_val = $(field_id).val();
    //    var field_name = $(field_id).attr('placeholder');
    var field_name = $(field_id).parent('div').find('label').text();
    var email = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

    
    if(field_val != ''){
        if(field_name == ""){
            field_name = $(field_id).parent('div').parent('div').find('label').text();
            if(email.test(field_val) == false){
                $(field_id).parent('div').parent('div').find('.errorMessage').html(field_name+' is not a valid email address');
                //        $(field_id).parent('div').find('.required').addClass('error');
                return 0;
            }else{
                $(field_id).parent('div').parent('div').parent('div').find('.errorMessage').html('');
                //        $(field_id).parent('div').find('.required').removeClass('error');
                return 1;
            }
        }else{
            if(email.test(field_val) == false){
                $(field_id).parent('div').find('.errorMessage').html(field_name+' is not a valid email address');
                //        $(field_id).parent('div').find('.required').addClass('error');
                return 0;
            }else{
                $(field_id).parent('div').find('.errorMessage').html('');
                //        $(field_id).parent('div').find('.required').removeClass('error');
                return 1;
            }
        }
    }
}

function matchValue(first_val, second_val, field_id, first_name, second_name){
    if(typeof first_val != "undefined" && typeof second_val != "undefined" && first_val != "" && second_val != ""){
        if(first_val != second_val){
            if(!$("#validate").hasClass('required')){
                $("#validate").addClass('required');
            }
            $("#" + field_id).parent('div').find('.errorMessage').html(first_name + ' doesnot match ' + second_name);
            return 0;
        }else{
            $("#validate").removeClass('required');
            $("#" + field_id).parent('div').find('.errorMessage').html('');
            return 1;
        }
    }else{
        return 1;
    }
}

function compareValue(startdate, enddate, field_id, first_name, second_name){
    if(typeof startdate != "undefined" && typeof enddate != "undefined" && startdate != "" && enddate != ""){
        var st_date = new Date(startdate);
        var end_date = new Date(enddate);
        if(end_date < st_date){
            if(!$("#validate").hasClass('required')){
                $("#validate").addClass('required');
            }
            $("#" + field_id).parent('div').parent('div').find('.errorMessage').html(second_name + ' cannot be before ' + first_name);
            return 0;
        }else{
            $("#validate").removeClass('required');
            $("#" + field_id).parent('div').parent('div').find('.errorMessage').html('');
            return 1;
        }
    }else{
        return 1;
    }
}

function compareTime(date_time_request_check, date_time, field_id, first_name, date_time_request_check){
    if(typeof date_time_request_check != "undefined" && typeof date_time != "undefined" && date_time_request_check != "" && date_time != ""){
        var dt_time_request_check = new Date(date_time_request_check);
        var dt_time = new Date(date_time);
        if(dt_time_request_check < dt_time){
            if(!$("#validate").hasClass('required')){
                $("#validate").addClass('required');
            }
            $("#" + field_id).parent('div').parent('div').find('.errorMessage').html(first_name + ' cannot be after ' + date_time_request_check);
            return 0;
        }else{
            $("#validate").removeClass('required');
            $("#" + field_id).parent('div').parent('div').find('.errorMessage').html('');
            return 1;
        }
    }else{
        return 1;
    }
}

function validate_image(field_id) {
    var field_val = $(field_id).val();
    var allowedFiles = [".jpg", ".jpeg", ".png"];
    var field_name = $(field_id).parent('div').find('label').text();
    var image = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
    
    if(field_val != ''){
        if(image.test(field_val) == false){
            $(field_id).parent('div').find('.errorMessage').html(field_name+' must be a jpg, jpeg or png file');
            //        $(field_id).parent('div').find('.required').addClass('error');
            return 0;
        }else{
            $(field_id).parent('div').find('.errorMessage').html('');
            //        $(field_id).parent('div').find('.required').removeClass('error');
            return 1;
        }
    }else{
        return 1;
    }
}



function validate_phone(field_id){
    var field_val = $(field_id).val();
    //    var field_name = $(field_id).attr('placeholder');
    var field_name = $(field_id).parent('div').find('label').text();
    var email = /^\(?[0-9]{3}\)[ . ]?[0-9]{3}[-. ]?[0-9]{4}$/;

    
    if(field_val != ''){
        if(!field_val.match(email)){
            $(field_id).parent('div').find('.errorMessage').html(field_name+' must be like (333) 333-3333');
            //        $(field_id).parent('div').find('.required').addClass('error');
            return 0;
        }else{
            $(field_id).parent('div').find('.errorMessage').html('');
            //        $(field_id).parent('div').find('.required').removeClass('error');
            return 1;
        }
    }
}

function checkEmail(field_id, submit, form_id){
    var field_val = $(field_id).val();
    //    var field_name = $(field_id).attr('placeholder');
    var field_name = $(field_id).parent('div').find('label').text();
    var baseUrl = $("#baseUrl").val();
    var email = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    
    if(email.test(field_val) == true){
        $.ajax({
            type: 'POST',
            url: baseUrl + 'Home/email_check',
            data: {
                email:field_val
            },
            success:function(response){
                if(field_name == ""){
                    field_name = $(field_id).parent('div').parent('div').find('label').text();
                    if(response==1){    
                        if(!$("#validate").hasClass('required')){
                            $("#validate").addClass('required');
                        }
//                        $(field_id).parent('div').parent('div').find('.errorMessage').html(field_name+' already exists');
                        $(field_id).parent('div').find('.errorMessage').html(field_name+' already exists');
                        return 0;
                    }else{
                        $("#validate").removeClass('required');
//                        $(field_id).parent('div').parent('div').find('.errorMessage').html('');
                        //        $(field_id).parent('div').find('.required').removeClass('error');
                        $(field_id).parent('div').find('.errorMessage').html('');
                        if(typeof submit != 1){
//                            submit_flag = 1;
                            $("#" + form_id).submit();
                        }
                        return 1;
                    } 
                }else{
                    if(response==1){    
                        if(!$("#validate").hasClass('required')){
                            $("#validate").addClass('required');
                        }
//                        $(field_id).parent('div').find('.errorMessage').html(field_name+' already exists');
                        $(field_id).parent('div').find('.errorMessage').html(field_name+' already exists');
                        return 0;
                    }else{
                        $("#validate").removeClass('required');
//                        $(field_id).parent('div').find('.errorMessage').html('');
                        //        $(field_id).parent('div').find('.required').removeClass('error');
                        $(field_id).parent('div').find('.errorMessage').html('');
                        if(typeof submit != 1){
//                            submit_flag = 1;
                            $("#" + form_id).submit();
                        }
                        return 1;
                    }  
                }
            }
        });
    }
    
    return 0;   
}

function validate_decimal(field_id){
    var field_val = $(field_id).val();
    //    var field_name = $(field_id).attr('placeholder');
    var field_name = $(field_id).parent('div').find('label').text();
    
    if(field_val != ''){
        if(field_name == ""){
            field_name = $(field_id).parent('div').parent('div').find('label').text();
            if(isNaN(field_val)){
                $(field_id).parent('div').parent('div').find('.errorMessage').html(field_name+' must be numeric');
                //        $(field_id).parent('div').find('.required').addClass('error');
                return 0;
            }else{
                $(field_id).parent('div').parent('div').parent('div').find('.errorMessage').html('');
                //        $(field_id).parent('div').find('.required').removeClass('error');
                return 1;
            }
        }else{
            if(isNaN(field_val)){
                $(field_id).parent('div').find('.errorMessage').html(field_name+' must be a numeric value');
                //        $(field_id).parent('div').find('.required').addClass('error');
                return 0;
            }else{
                $(field_id).parent('div').find('.errorMessage').html('');
                //        $(field_id).parent('div').find('.required').removeClass('error');
                return 1;
            }
        }
    }
}

function validate_integer(field_id){
    var field_val = $(field_id).val();
    //    var field_name = $(field_id).attr('placeholder');
    var field_name = $(field_id).parent('div').find('label').text();
    var num = /^\d+$/;

    
    if(field_val != ''){
        if(num.test(field_val) == false){
            $(field_id).parent('div').find('.errorMessage').html(field_name+' must be an integer');
            //        $(field_id).parent('div').find('.required').addClass('error');
            return 0;
        }else{
            $(field_id).parent('div').find('.errorMessage').html('');
            //        $(field_id).parent('div').find('.required').removeClass('error');
            return 1;
        }
    }
}

function validate_url(field_id){
    var field_val = $(field_id).val();
    //    var field_name = $(field_id).attr('placeholder');
    var field_name = $(field_id).parent('div').find('label').text();
    var url = /[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/;

    
    if(field_val != ''){
        if(url.test(field_val) == false){
            $(field_id).parent('div').find('.errorMessage').html(field_name+' must be a valid url');
            //        $(field_id).parent('div').find('.required').addClass('error');
            return 0;
        }else{
            $(field_id).parent('div').find('.errorMessage').html('');
            //        $(field_id).parent('div').find('.required').removeClass('error');
            return 1;
        }
    }
}

function validate_document(field_id) {
    var field_val = $(field_id).val();
    var allowedFiles = [".doc", ".pdf", ".rtf", ".xls", ".txt", ".docx", ".xlsx", ".ppt", ".pptx"];
    var field_name = $(field_id).parent('div').find('label').text();
    var image = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
    
    if(field_val != ''){
        if(image.test(field_val) == false){
            $(field_id).parent('div').find('.errorMessage').html(field_name+' must be a document file');
            //        $(field_id).parent('div').find('.required').addClass('error');
            return 0;
        }else{
            $(field_id).parent('div').find('.errorMessage').html('');
            //        $(field_id).parent('div').find('.required').removeClass('error');
            return 1;
        }
    }else{
        return 1;
    }
}

function validate_request_document(field_id){
    var field_val = $(field_id).val();
    var allowedFiles = [".doc", ".pdf", ".rtf", ".xls", ".txt", ".docx", ".xlsx", ".ppt", ".pptx", ".jpg", ".jpeg", ".png"];
    var field_name = $(field_id).parent('div').find('label').text();
    var image = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
    
    if(field_val != ''){
        if(image.test(field_val) == false){
            $(field_id).parent('div').find('.errorMessage').html(field_name+' must be a document or image');
            //        $(field_id).parent('div').find('.required').addClass('error');
            return 0;
        }else{
            $(field_id).parent('div').find('.errorMessage').html('');
            //        $(field_id).parent('div').find('.required').removeClass('error');
            return 1;
        }
    }else{
        return 1;
    }
}