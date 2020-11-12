
<div class="chat-section-header">                  
<div class="chat-user p-x-30 p-y-20" id="chat">
    <div class="media m-b-4"> 
        <div class="user">
            <?php 
             if(!empty($services)){
            if ($services->id == 1) { ?>
                <img src="{{ asset('default/images/mowing.svg') }}" alt="">
            <?php } elseif ($services->id == 2) { ?>
                <img src="{{ asset('default/images/leaf-removal.svg') }}" alt="">
            <?php } elseif ($services->id == 3) { ?>
                <img src="{{ asset('default/images/lawn-treatment.svg') }}" alt="" >
            <?php } elseif ($services->id == 4) { ?>
                <img src="{{ asset('default/images/aeration.png') }}" alt="" >
            <?php } elseif ($services->id == 5) { ?>
                <img src="{{ asset('default/images/sprinkler.svg') }}" alt="" >
            <?php } elseif ($services->id == 6) { ?>
                <img src="{{ asset('default/images/swimming-pool-ladder.svg') }}" alt="" >
            <?php } elseif ($services->id == 7) { ?>
                <img src="{{ asset('default/images/snow-removal.svg') }}" alt="" >
            <?php } ?>
            <span class="status"></span>
        </div>
        <div class="media-body p-l-20">
            <h5 class="medium m-0 m-t-5">{{$order_no}}</h5>
            <p class="m-b-0">{{$services->service_name}}</p>                                            
        </div>
        <?php  Session::put('key', $order_no);  ?>
         <?php  Session::put('key1', $services->service_name);  ?>
         <?php  Session::put('key2', $user_landscaper_id);  ?>
         <?php  Session::put('key3', $services->id);  ?>
        <a href="javascript:void(0)" class="expand-chat float-right m-t-15"><i class="fa fa-expand"></i></a>
    </div>   
</div>
    <?php }else{
                    echo "";
                } ?>
     
<div class="chat-form">
    <form class="" action="javascript:void(0);" method="post" id="message_add" onsubmit="addMessage('<?php echo session('key'); ?>', '<?php echo session('key1'); ?>', '<?php echo session('key2'); ?>','<?php echo session('key3'); ?>')">
        <div class="input-group m-b-0">
            <input type="text" class="form-control m-r-15" placeholder="Start typing to send a message" name="message" id="message">
            <span><button type="submit" class="circle float-right"><img src="{{ asset('default/images/enter-arrow.png') }}"></button></span>
        </div>                                     
    </form>
</div>
</div>

<div class="chat-bubble clearfix mCustomScrollbar">
<div id="chat-window"> 
<div class=" p-x-50 p-y-25 " >
    <?php 
     if(!empty($send_message)){
    foreach ($send_message as $msg) {
        if($msg->msg_id == session('key')){
             if($msg->sender_id == session('user_id')){
        ?>
        <div  class="chat-bubble-left float-left p-15 m-b-25 medium" >
            <div class="chat-text">
                <p class="m-b-10 regular">{{$msg->description}}</p>                                        
                <span class="float-right">{{date("H:i",strtotime($msg->time))}}</span>
            </div>
        </div>

        <div class="clearfix"></div>
    <?php } else{ ?>
   
  
     
        
        <div id="checking">

    <div class="chat-bubble-right float-right p-15 m-b-25 medium" >
        <div class="chat-text">
            <p class="m-b-10 regular"> {{$msg->description}}</p>
            <span class="float-right">{{date("H:i",strtotime($msg->time))}}</span>
        </div>
    </div>

    <div class="clearfix"></div>
    </div>
         <?php }  } }
     }else{
          echo "";
      }
?>
</div>   
    </div>
</div>
<script>
    $(function () {
        $('.expand-chat').click(function (e) {
            e.preventDefault();
            $('body').toggleClass('chat-expand');
            var that = $(this);
            if ($('body').hasClass('chat-expanded')) {
                $('.expand-chat i').addClass('fa-compress').removeClass('fa-expand');
            } else {
                $('.expand-chat i').removeClass('fa-compress').addClass('fa-expand');
            }
        });
    });
    
     (function ($) {
                $(window).on("load", function () {

                    $("body").mCustomScrollbar({
                        theme: "minimal"
                    });

                });
            })(jQuery);

            $(".scroll-pane").mCustomScrollbar({
                mouseWheelPixels: 150 //change this to a value, that fits your needs
            });
</script>