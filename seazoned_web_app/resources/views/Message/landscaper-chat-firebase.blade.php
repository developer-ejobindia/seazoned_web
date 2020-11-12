
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
            <a href="javascript:void(0)" class="expand-chat float-right m-t-15"><i class="fa fa-expand"></i></a>
</div> 
            <?php }else{
                    echo "";
                } ?>
        

<script>
    getData('<?php echo $order_no; ?>', '<?php echo $customer_id; ?>');
    
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



