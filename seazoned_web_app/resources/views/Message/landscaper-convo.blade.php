<div  class=" p-x-50 p-y-25 " >
<?php 
     if(!empty($send_message)){
    foreach ($send_message as $msg) {
        if($msg->msg_id == session('key')){
            if($msg->sender_id == session('user_id')){
        ?>
        <div  class="chat-bubble-left float-left p-15 m-b-25 medium"  >
            <div class="chat-text">
                <p class="m-b-10 regular">{{$msg->description}}</p>                                        
                <span class="float-right">{{date("H:i",strtotime($msg->time))}}</span>
            </div>
        </div>

        <div class="clearfix"></div>
            <?php }else{ ?> 
       

        <div class="chat-bubble-right float-right p-15 m-b-25 medium" >
            <div class="chat-text">
                <p class="m-b-10 regular"> {{$msg->description}}</p>
                <span class="float-right">{{date("H:i",strtotime($msg->time))}}</span>
            </div>
        </div>

    <div class="clearfix"></div>
          
  
        <?php }
        }
        else { echo '';
      }
    }
        }
        else{
            
          echo "";
      }
?>
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