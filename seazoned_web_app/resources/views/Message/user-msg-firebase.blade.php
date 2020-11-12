@extends("layouts.dashboardlayout")
@section('content')
{{-- <script src="https://www.gstatic.com/firebasejs/5.2.0/firebase.js"></script>
<script>
    // Initialize Firebase
    var config = {
        apiKey: "AIzaSyDIDLetQGch6NOn9vS1g9GjzVzLvNA6mIc",
        authDomain: "seazoned-f8b8c.firebaseapp.com",
        databaseURL: "https://seazoned-f8b8c.firebaseio.com",
        projectId: "seazoned-f8b8c",
        storageBucket: "seazoned-f8b8c.appspot.com",
        messagingSenderId: "643308592889"
    };
    firebase.initializeApp(config);
</script> --}}

<script src="https://www.gstatic.com/firebasejs/5.3.0/firebase.js"></script>
<script>
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyCDAl1IQjYxD4Cno77dozxpFYxOc7ESlxU",
    authDomain: "seazoned-2470f.firebaseapp.com",
    databaseURL: "https://seazoned-2470f.firebaseio.com",
    projectId: "seazoned-2470f",
    storageBucket: "seazoned-2470f.appspot.com",
    messagingSenderId: "806611022850"
  };
  firebase.initializeApp(config);
</script>

<script>
    var database = firebase.database();

    function getData(orderid, landscaper_id) {
        // Get all data from firebase
        database.ref('/orders/').child('/' + orderid + '/messages').once('value').then(function (snapshot) {

            var data = snapshot.val();
            //console.log(data);
            var list = "";
            var no_msg = "";
            if (data != null)
            {
                for (value in data) {
                    if (data[value].senderid == landscaper_id)
                    {
                        list += '<div class="chat-bubble-left float-left p-15 m-b-25 medium"><div class="chat-text"><p>' + data[value].text + '</p><span class="float-right"></span></div></div><div class="clearfix"></div>';
                    } else
                    {
                        list += '<div class="chat-bubble-right float-right p-15 m-b-25 medium"><div class="chat-text"><p>' + data[value].text + '</p><span class="float-right"></span></div></div><div class="clearfix"></div>';
                    }
                }

                $("#no_msg").hide();
                $("#msg_list").show();
                $("#msg_list").html(list);
            } else
            {
                no_msg += '<p>No Message Found !!!</p>';

                $("#msg_list").hide();
                $("#no_msg").show();
                $("#no_msg").html(no_msg);
            }
        });
    }

    function user_chat(order_no, service_id, landscaper_id) {
        var old_ord_id = $("#order_id").val();
        $.ajax({
            url: '{{ url('user/show-chat-firebase') }}',
            data: {"service_id": service_id, "order_no": order_no, "user_landscaper_id": landscaper_id},
            method: 'POST',
            success: function (result)
            {
                $("#order_id").val(order_no);
                $("#landscaper_id").val(landscaper_id);
                $("#user_chat").html(result);
                
                database.ref('/orders/'+old_ord_id+'/messages').off();
                database.ref('/orders/'+order_no+'/messages').on('child_added', function(snapshot) {       
                    getData(order_no, landscaper_id);
                });
            }
        });
    }

    $(document).ready(function () {
        $("#user_message_add").click(function () {
            var order_no = $("#order_id").val();
            var receiverid = $("#landscaper_id").val();
            var text = $("#user_message").val();
            var senderid = $("#sender_id").val();
            var date = $("#date_time").val();
            //alert(order_no);

            // Add data to firebase
            database.ref('/orders/').child('/' + order_no + '/messages').push().set({
                date: date,
                receiverid: receiverid,
                senderName: '',
                senderid: senderid,
                text: text
            });

            $("#user_message").val("");

            getData(order_no, receiverid);
        });

        // When an item gets added
        var old_ord_id = $("#order_id").val();
        database.ref('/orders/'+old_ord_id+'/messages').off();
        var order_no = $("#order_id").val();
        var landscaper = $("#landscaper_id").val();
        database.ref('/orders/' + order_no + '/messages').on('child_added', function (snapshot) {
            getData(order_no, landscaper);
        });


        // When an item gets added
//    database.ref('/users').on('child_added', function(snapshot) {
//      var data = snapshot.val();
//      var key = snapshot.key;
//      var list = "";
//      list += '<li>'+data.name+' <a href="javascript:void(0)" onclick="deleteData(\''+key+'\')">(X) DELETE</a></li>';
//      initMap(data.name, data.latitude, data.longitude);
//      $("#place_list").append(list);
//    });

//getData();
    });



</script>
<?php if ($empty_flag == 0) { ?>
    <body onload="getData('<?php echo $message[0]->order_no; ?>', '<?php echo $message[0]->user_landscaper_id; ?>')">
        <section class="user-message main-content">  
            <?php if (!empty($message)) {
                ?>
                <div class="container">
                    <div class="total-wrap-msg">

                        <div class="left-user-msg-box">
                            <h3 class="m-b-8 m-t-8">Users</h3>

                            <ul class="list-group list-group-flush mCustomScrollbar">  
                                <?php
                                if (!empty($message)) {
                                    foreach ($message as $val) {
                                        ?>
                                        <li class="online unread">
                                            <a class="list-group-item" href="javascript:void(0)" id="chat_show" onclick="getData('<?php echo $val->order_no; ?>', '<?php echo $val->user_landscaper_id; ?>'); user_chat('<?php echo $val->order_no; ?>', '<?php echo $val->service_id; ?>', '<?php echo $val->user_landscaper_id; ?>');">
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
                                        <?php
                                    }
                                } else {
                                    echo "No Order Found !!!!";
                                }
                                ?>
                            </ul>
                        </div> 
                        <div class="right-chat-sec">
                            <div class="chat-section-header">                  
                                <div class="chat-user p-x-30 p-y-20" id="user_chat">
                                    <div class="media m-b-4"> 
                                        <div class="user">
                                            <?php if (!empty($message)) {
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
                                   <?php  }else{
                                    echo "";
                                } ?>
                                    </div>

                                    <div class="chat-form">
                                        <input type="hidden" id="order_id" value="<?php echo Session::get('key'); ?>">
                                        <input type="hidden" id="service_name" value="<?php echo $message[0]->service_name; ?>">
                                        <input type="hidden" id="landscaper_id" value="<?php echo session("key2"); ?>">
                                        <input type="hidden" id="sender_id" value="<?php echo session("user_id"); ?>">
                                        <input type="hidden" id="service_id" value="<?php echo $message[0]->service_id; ?>">
                                        <input type="hidden" id="date_time" value="<?php echo date("d-m-Y h:m a"); ?>">

                                        <div class="input-group m-b-0">
                                            <input type="text" class="form-control m-r-15" placeholder="Start typing to send a message" id="user_message" >
                                            <span><button type="submit"  id="user_message_add" class="circle float-right"><img src="{{ asset('default/images/enter-arrow.png') }}"></button></span>
                                        </div> 
                                    </div>
                                </div>
                            <div class="chat-bubble clearfix mCustomScrollbar">
                                <div id="chat-window">
                                    <div class=" p-x-50 p-y-25" id='msg_list'>
                                    </div>
                                    <div class="alert alert-danger" id='no_msg' style="display: none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="alert alert-danger">
                    No Messages Found.
                </div>
            <?php } ?>
        </section>
    <?php } else { ?>
        <section class="user-message main-content">  
            <div class="container">
                <div class="text-center alert alert-danger">
                    No Messages Found.
                </div>
            </div>
        </section>
    <?php }
    ?>

</body>
@endsection