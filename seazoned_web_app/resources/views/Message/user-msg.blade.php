@extends("layouts.dashboardlayout")
@section('content')
<script>
   
    function chat(order_no,service_id,l_user_id){
       
        $.ajax({
            url:'{{ url('user/show-chat') }}',
            data:{"service_id":service_id,"order_no":order_no,"user_landscaper_id":l_user_id },
            method:'POST',
            success:function(d)
            {
                $("#chat").html(d);
            }
        });
    } 
    
    
    setInterval(function () {
    check_users_activity();
    }, 1000);

   function check_users_activity() {
//      alert("1");  
   $.ajax({
   url: '{{ url('user/activity-check') }}',
   data: '', 
   method: 'GET',
   success: function (data) {
//       alert(data);
            $("#chat-window").html(data);       
  }
 });
}

    function addMessage(order_no,srv_name,l_user_id,service_id){
      
       var message = $("#message").val();
       var date = new Date();
       var hour = date.getHours();
       var min = date.getMinutes();
        $.ajax({
            url:"{{ url('user/add-message') }}",
            data:{"service_name":srv_name,"order_no":order_no,"user_landscaper_id":l_user_id,"message":message,"service_id":service_id},
            method:"POST",
            success:function(data){
                     }
            });
            $("#message").val('');
//            $('#chat-window').html("");
//            $('#chat-window').append('<div class="p-x-50 p-y-25"><div class="chat-bubble-left float-left p-15 m-b-25 medium">\n\
//                                      <div class="chat-text" ><p class="m-b-10 regular" >'+message+'</p>\n\
//                                      <span class="float-right">'+hour+':'+min+'</span>\n\
//                                      </div></div></div>');
    }     
    
</script>
<section class="user-message main-content">  
    <?php  if(!empty($message) ) {
                    ?>
    <div class="container">
        <div class="total-wrap-msg">
            
            <div class="left-user-msg-box">
                <h3 class="m-b-8 m-t-8">Users</h3>
                
                <ul class="list-group list-group-flush mCustomScrollbar">  
                <?php  if(!empty($message)) {
                foreach($message as $val){
                 ?>
                    <li class="online unread">
                        <a class="list-group-item" href="javascript:void(0)" id="chat_show" onclick="chat('<?php echo $val->order_no;   ?>', '<?php echo $val->service_id;  ?>','<?php echo $val->user_landscaper_id;  ?>')">
                            <div class="media w-100 d-table" >       
                                <div class="user d-table-cell">
                                    <img src="{{ asset('default/images/'.$val->service_logo) }}" alt="">                               
                                    <span class="status"></span>
                                </div>
                                <div class="media-body d-table-cell align-middle p-l-20">
                                    <h5 class="regular m-0">{{$val->order_no}}</h5> 
                                    <p class="m-0">{{$val->service_name}}</p>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php  }}else{
                    echo "";
                } ?>
                </ul>
            </div> 
            <div class="right-chat-sec" id="chat">
                <div class="chat-section-header">                  
                    <div class="chat-user p-x-30 p-y-20" >
                        <div class="media m-b-4"> 
                            <div class="user">
                                  <?php if(!empty($message)) { 
                                   ?>  
                                   <img src="{{ asset('default/images/'.$message[0]->service_logo) }}" alt="">                                 
                                     
                                <span class="status"></span>
                            </div>
                            
                              <div class="media-body p-l-20">
                                <h5 class="medium m-0 m-t-5">{{$message[0]->order_no}}</h5>
                                <p class="m-b-0">{{$message[0]->service_name}}</p>                                            
                              </div>
                          
                            
                            <a href="javascript:void(0)" class="expand-chat float-right m-t-15"><i class="fa fa-expand"></i></a>
                        </div>                               
                    </div>
                                  
                    <div class="chat-form">
                        <form class="" action="javascript:void(0);" method="post" id="message_add" onsubmit="addMessage('<?php echo $message[0]->order_no; ?>', '<?php echo  $message[0]->service_name; ?>', '<?php echo $message[0]->user_landscaper_id; ?>','<?php  $message[0]->service_id; ?>')">
                            <div class="input-group m-b-0">
                                <input type="text" class="form-control m-r-15" placeholder="Start typing to send a message" name="message" id="message" >
                                <span><button type="submit" class="circle float-right"><img src="{{ asset('default/images/enter-arrow.png') }}"></button></span>
                            </div>                                     
                        </form>
                    </div>
                </div>
                <?php  } ?>
                <div class="chat-bubble clearfix mCustomScrollbar" >
                    <div id="chat-window"> 
                    <div  class=" p-x-50 p-y-25 " >
                        <?php 
                        if(!empty($send_message)){
                        foreach ($send_message as $msg) { 
                            if($msg->msg_id == $message[0]->order_no ){  
                                  if($msg->sender_id == session('user_id')){
                            ?>
                        <div  class="chat-bubble-left float-left p-15 m-b-25 medium" >
                            <div class="chat-text"  >
                                <p class="m-b-10 regular" >{{$msg->description}}</p>                                        
                                <span class="float-right">{{date("H:i",strtotime($msg->time))}}</span>
                            </div>
                        </div>   
                        <div class="clearfix"></div>      
                           <?php }else{ ?> 
                            <div class="chat-bubble-right float-right p-15 m-b-25 medium" >
                                <div class="chat-text">
                                    <p class="m-b-10 regular">{{$msg->description}}
                                    </p>
                                    <span class="float-right">{{date("H:i",strtotime($msg->time))}}</span>
                                </div>
                            </div>
                          <div class="clearfix"></div>  
                        
                            <?php }}
                          echo "";
                        }}else
                             echo "";
                             
                             ?>  
                          </div>
                    </div>
                </div>
            </div>
         </div>
       </div>
     <?php   }else{ ?>
           <div class="alert alert-danger">
                                No Mesages Found
                            </div>
      <?php      }
            ?>
</section>
@endsection