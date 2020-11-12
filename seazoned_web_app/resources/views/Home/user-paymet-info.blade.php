@extends("layouts.dashboardlayout")
@section('content')

<section class="main-content user-profile my-profile-payment-info p-y-30">
    <div class="container">
        <?php
        $prof_pic = url('/') . '/default/images/userdefault.png';
        if (session("prof_img") !== "") {
            if (file_exists(public_path() . '/uploads/profile_picture/' . session("prof_img"))) {
                $prof_pic = url('/') . '/uploads/profile_picture/' . session("prof_img");
            }
        }
        if ($payment_dtls != '') {
            ?>
            <div class="row">
                <div class="col-md-4 col-sm-12 col-12 m-b-30">
                    <div class="card card-no-radius side-menu">
                        <div class="card-block p-t-40 p-b-30">
                            <div class="avtar">
                                <!--<img src="{{ (session("prof_img") == NULL && !file_exists("/uploads/profile_picture/".session("prof_img"))) ? asset("/default/images/profile_pic.jpg") : session("profile_image") }}" alt="">-->                     

                                <img src="{{ $prof_pic }}" alt="">       
                            </div>
                            <h4 class="profile-name text-center m-0 m-t-20">{{ $user_info->first_name . " " . $user_info->last_name }}</h4>
                        </div>                           
                    </div>
                </div>
                <div class="col-md-8 col-sm-12 col-12">
                    <div class="card m-t-30 card-no-radius">
                        <div class="card-header card-header-success">
                            <h4 class="m-0 regular">My Saved Card</h4>
                            <a href="" class="float-right medium add-icon header-link" data-toggle="modal" data-target="#addcard1">ADD NEW CREDIT/DEBIT CARD</a>
                        </div>
                        <?php
                        //print_r($payment_dtls);
                        foreach ($payment_dtls as $payment) {
                            ?>
                            <div class="card-block">
                                <div class="form-check">
                                    <div class="form-check-label d-block">
                                        <div class="address-block clearfix d-block">
                                            <div class="address-left float-left">
                                                <p class="m-b-10 bold">{{$payment->card_brand}}</p>
                                                <h5 class="regular m-b-10">{{$payment->card_no}}</h5>
                                                <p class="m-b-10 light">Expiry Date: {{$payment->month}}/{{$payment->year}}</p>

                                            </div>
                                            <a href="javascript:void(0)" onclick="del_card(<?php echo $payment->id; ?>)"class="float-right medium m-l-15"> REMOVE</a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label class="float-right">                                            
                                            <input type="radio" name="is_primary" id="is-primary-{{$payment->id}}" name="is_primary[{{$payment->id}}]" <?php echo ($payment->is_primary == 1) ? 'checked' : '' ?> onclick="save_primary_card({{$payment->id}});" />
                                            Set as Primary
                                        </label>
                                        <div class="clearfix"></div>
                                        <hr>
                                    </div>
                                </div>                                        
                            </div> 
                        <?php } ?>
                        <!--                        <hr class="m-0">
                                                <div class="card-block">
                                                    <div class="form-check">
                                                        <div class="form-check-label d-block">
                                                            <div class="address-block clearfix d-block">
                                                                <div class="address-left float-left">
                                                                    <p class="m-b-10 bold">MASTER</p>
                                                                    <h5 class="regular m-b-10">XXXX XXXX XXXX XX1245</h5>
                                                                    <p class="m-b-10 light">Expiry Date: 08/27</p>
                                                                </div>
                                                                <a href="" class="float-right medium m-l-15"> REMOVE</a>
                                                            </div>
                                                        </div>
                                                    </div>                                        
                                                </div> -->

                    </div>
                </div>
            </div>
        <?php } else { ?>                                                  
            <div class="row">
                <div class="col-md-4 col-sm-12 col-12 m-b-30">
                    <div class="card card-no-radius side-menu">
                        <div class="card-block p-t-40 p-b-30">
                            <div class="avtar">
                                <!--<img src="{{ (session("prof_img") == NULL && !file_exists("/uploads/profile_picture/".session("prof_img"))) ? asset("/default/images/profile_pic.jpg") : session("profile_image") }}" alt="">-->                     
                                <img src="{{ $prof_pic }}" alt="">    
                            </div>
                            <h4 class="profile-name text-center m-0 m-t-20">{{ $user_info->first_name . " " . $user_info->last_name }}</h4>
                        </div>                           
                    </div>
                </div>
                <div class="col-md-8 col-sm-12 col-12">
                    <div class="card custom-card card-no-radius">
                        <div class="card-header">
                            <h4 class="m-0 regular">{{ $user_info->first_name . " " . $user_info->last_name }}</h4>                                
                        </div>
                        <div class="card-block user-profile-details">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="m-b-30"><b>Date of Birth</b><br>
                                        {{ $user_info->date_of_birth }}</p>
                                    <p><b>Cell phone</b><br>
                                        {{ $user_info->phone_number }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="m-b-30"><b>Email ID</b><br>
                                        {{ $user_info->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-lg-12 ">
                            <div class="card custom-card card-no-radius m-b-30 m-t-30"> 
                                <div class="card-block">
                                    <div class="add-paypal w-100 text-center">
                                        <img src="{{ asset("/default/images/credit-card(2).svg") }}" alt="" class="m-t-10" width="80">
                                        <div class="clearfix"></div>
                                        <a class="btn btn-secondary m-t-20" href="#" data-toggle="modal" data-target="#addcard">Add Credit/Debit Card</a>

                                    </div>
                                </div>                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="modal fade" id="addcard" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content" action="{{ url("payment/add-payment-info") }}" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Card</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Card Holder Name</label>
                        <input type="text" class="form-control" placeholder="Enter Card Holder Name" name="card_holder_name" required>
                    </div>
                    <div class="form-group">
                        <label>Card No</label>
                        <input type="text" class="form-control" placeholder="Enter Card No" name="card_no" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Month</label>
                                <select class="form-control" name="month" required>
                                    <option value="">Month</option>
                                    <?php for ($month = 1; $month <= 12; $month++) { ?>
                                        <option value="{{$month}}">{{$month}}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Year</label>
                                <select class="form-control" name="year" required>
                                    <option value="">Year</option>
                                    <?php for ($year = date('Y'); $year <= date('Y') + 20; $year++) { ?>
                                        <option value="{{$year}}">{{$year}}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>CVV Number</label>
                                <input type="text" class="form-control" placeholder="Enter 3 Digit CVV" name="cvv_no" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <!--modal 2-->
    <div class="modal fade" id="addcard1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content" action="{{ url("payment/add-payment-info") }}" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Card1</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Card Holder Name</label>
                        <input type="text" class="form-control" placeholder="Enter Card Holder Name" name="card_holder_name" required>
                    </div>
                    <div class="form-group">
                        <label>Card No</label>
                        <input type="text" class="form-control" placeholder="Enter Card No" name="card_no" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Month</label>
                                <select class="form-control" name="month" required>
                                    <option value="">Month</option>
                                    <?php for ($month = 1; $month <= 12; $month++) { ?>
                                        <option value="{{$month}}">{{$month}}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Year</label>
                                <select class="form-control" name="year" required>
                                    <option value="">Year</option>
                                    <?php for ($year = date('Y'); $year <= date('Y') + 20; $year++) { ?>
                                        <option value="{{$year}}">{{$year}}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>CVV Number</label>
                                <input type="text" class="form-control" placeholder="Enter 3 Digit CVV" name="cvv_no" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div> 
</section>
<script>
    function del_card(id){
    if (confirm("Are you sure?")){
    window.location.href = "<?php echo url('payment/delete-payment-info'); ?>/" + id;
    }
    }

    function save_primary_card(card_p_id){

    var is_chkd = $('#is-primary-' + card_p_id).is(':checked');
    var is_primary = 0;
    if (is_chkd){

    is_primary = 1;
    }
    //alert(is_primary);
    $.ajax({
    url: "{{route('save-primary-card')}}",
            type: "POST",
            data : {"is_primary":is_primary, "card_p_id":card_p_id},
            success: function(html){
            
            }
    });
    }
</script>

@endsection

