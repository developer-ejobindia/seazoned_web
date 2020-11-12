@extends("layouts.dashboardlayout")
@section('content')
<section class="main-content booking-details p-y-30">
    <div class="container">
        <div class="row">
            <div class="col-md-9">                        
                <div class="card card-no-radius custom-card booking-service-details">
                    <div class="card-header p-x-20 p-y-20">
                        <div class="header-back d-inline-block">
                            <a class="back-btn text-center d-inline-block" href="{{ url("/user/booking-history") }}">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <h4 class="m-0 regular p-l-10">{{ $booking_details[0]->order_no }}</h4>
                        </div>

                        <?php
                        $service_status = '<h5 class="service-status text-info float-right d-inline-block"><i class="fa fa-check m-r-5"></i> Request sent</h5>';
                        if ($booking_details[0]->status == 1 && $booking_details[0]->is_completed == 0)
                            $service_status = '<h5 class="service-status text-warning float-right d-inline-block"><i class="fa fa-hourglass-start m-r-5"></i>Request Accepted</h5>';
                        if ($booking_details[0]->status == 2 && $booking_details[0]->is_completed == 1)
                            $service_status = '<h5 class="service-status text-warning float-right d-inline-block"><i class="fa fa-hourglass-start m-r-5"></i>Work In Progress</h5>';
                        if ($booking_details[0]->status == 3 && $booking_details[0]->is_completed == 1)
                            $service_status = '<h5 class="service-status text-warning float-right d-inline-block"><i class="fa fa-hourglass-start m-r-5"></i>Job has been completed by provider, waiting for your confirmation</h5>';
                        if ($booking_details[0]->status == 3 && $booking_details[0]->is_completed == 2)
                            $service_status = '<h5 class="service-status text-success float-right d-inline-block"><i class="fa fa-check-circle m-r-5"></i>Job Completed</h5>';
                        echo $service_status;
                        ?>

                    </div>
                    <div class="card-block p-x-20 p-y-35">
                        <div class="row">
                            <div class="col-md-5 customer-details">
                                <h5 class="light m-0 m-b-30">{{ $booking_details[0]->service_name }}</h5>
                                <div class="media">
                                    <div class="avtar">
                                        <?php
                                        $prof_pic = url('/') . '/default/images/avatar-landscaper.jpg';
                                        if (isset($profile_image->profile_image) && $profile_image->profile_image != "") {
                                            if (file_exists(public_path() . '/uploads/profile_picture/' . $profile_image->profile_image)) {
                                                $prof_pic = url('/') . '/uploads/profile_picture/' . $profile_image->profile_image;
                                            }
                                        }
                                        ?>      
                                        <img class="d-flex align-self-start" src="{{$prof_pic}}" alt="">
                                    </div>                                    
                                    <div class="media-body p-l-15">
                                        <p class="m-b-0 light">Service Provider:</p>
                                        <h5 class="m-b-0 medium">{{ $booking_details[0]->provider_name }}</h5>                                            
                                    </div>
                                </div>
                            </div>
                            <div class="col-md service-info">
                                <p class="m-0 m-b-10 text-dark regular">Service Request Date</p>
                                <p class="m-0 m-b-20 light">
                                    <?php
                                    echo date("D, M j Y", strtotime($booking_details[0]->service_date)) . ' ' . date("h:i a", strtotime($booking_details[0]->service_time));
                                    ?>
                                </p>
                                <p class="m-0 m-b-10 text-dark">Service Completed Date</p>
                                <p class=""><?php echo (isset($booking_details[0]->completion_date) && $booking_details[0]->completion_date != "") ? date('D, M d Y', strtotime($booking_details[0]->completion_date)) : '--' ?></p>
                            </div>
                            <div class="col-md service-info">
                                <p class="m-0 m-b-10 text-dark regular">Frequency</p>
                                <p class="m-0 m-b-20 light">{{ $booking_details[0]->service_frequency }}</p>
                                <?php
                                if ($booking_details[0]->service_id == 1) {
                                    ?>
                                    <p class="m-0 m-b-10 text-dark regular">Lawn Size</p>
                                    <p class="m-0 m-b-10 light">{{ $booking_details[0]->lawn_area }} acres</p>
                                    <p class="m-0 m-b-10 text-dark regular">Grass Length</p>
                                    <p class="m-0 m-b-10 light">{{ $booking_details[0]->grass_length }} inches</p>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($booking_details[0]->service_id == 2) {
                                    ?>
                                    <p class="m-0 m-b-10 text-dark regular">Lawn Size</p>
                                    <p class="m-0 m-b-10 light">{{ $booking_details[0]->lawn_area }} acres</p>
                                    <p class="m-0 m-b-10 text-dark regular">Leaf Accumulation</p>
                                    <p class="m-0 m-b-10 light">{{ $booking_details[0]->leaf_accumulation }}</p>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($booking_details[0]->service_id == 3) {
                                    ?>
                                    <p class="m-0 m-b-10 text-dark regular">Lawn Size</p>
                                    <p class="m-0 m-b-10 light">{{ $booking_details[0]->lawn_area }} acres</p>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($booking_details[0]->service_id == 4) {
                                    ?>
                                    <p class="m-0 m-b-10 text-dark regular">Lawn Size</p>
                                    <p class="m-0 m-b-10 light">{{ $booking_details[0]->lawn_area }} acres</p>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($booking_details[0]->service_id == 5) {
                                    if (isset($booking_details[0]->lawn_area)) {
                                        ?>
                                        <p class="m-0 m-b-10 text-dark regular">Lawn Size</p>
                                        <p class="m-0 m-b-10 light">{{ $booking_details[0]->lawn_area }} acres</p>
                                    <?php } else { ?>
                                        <p class="m-0 m-b-10 text-dark regular">No of Zones</p>
                                        <p class="m-0 m-b-10 light">{{ $booking_details[0]->no_of_zones }}</p>
                                        <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($booking_details[0]->service_id == 6) {
                                    ?>
                                    <p class="m-0 m-b-10 text-dark regular">Pool Water Type</p>
                                    <p class="m-0 m-b-10 light">{{ $booking_details[0]->water_type }}</p>
                                    <p class="m-0 m-b-10 text-dark regular">Include Spa / Hot Tub</p>
                                    <p class="m-0 m-b-10 light">{{ $booking_details[0]->include_spa }}</p>
                                    <p class="m-0 m-b-10 text-dark regular">Pool Type</p>
                                    <p class="m-0 m-b-10 light">{{ $booking_details[0]->pool_type }}</p>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($booking_details[0]->service_id == 7) {
                                    ?>
                                    <p class="m-0 m-b-10 text-dark regular">No of Cars</p>
                                    <p class="m-0 m-b-10 light">{{ $booking_details[0]->no_of_cars }}</p>
                                    <p class="m-0 m-b-10 text-dark regular">Driveway Type</p>
                                    <p class="m-0 m-b-10 light">{{ $booking_details[0]->driveway_type }}</p>
                                    <p class="m-0 m-b-10 text-dark regular">Service Type</p>
                                    <?php
                                    $service_type_arr = explode(',', $booking_details[0]->service_type);
                                    foreach ($service_type_arr as $service_type_val) {
                                        ?>
                                        <p class="m-0 m-b-10 light">{{ $service_type_val }}</p>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="card-block p-x-20 p-y-35">
                        <div class="row">
                            <div class="col-md-4 service-info">
                                <p class="m-0 m-b-10 text-dark regular">Service Address</p>
                                <p class="m-0 m-b-10 regular">{{ $booking_details[0]->name }} </p>
                                <p class="m-0 m-b-10 light">{{ $booking_details[0]->address }}, </p>
                                <p class="m-0 m-b-10 light">{{ $booking_details[0]->city }}, {{ $booking_details[0]->state }}</p>
                            </div>
                            <div class="col-md-4 service-info">
                                <p class="m-0 m-b-10 text-dark regular">Phone</p>
                                <p class="m-0 m-b-35 regular">{{ $booking_details[0]->contact_number }}</p>
                                <p class="m-0 m-b-10 text-dark regular">Email</p>
                                <p class="m-0 m-b-10 light">{{ $booking_details[0]->email_address }} </p>
                            </div>
                            <?php
                            if(isset($booking_details[0]->card_no) && $booking_details[0]->card_no != ''){
                            ?>
                            <div class="col-md-4 service-info">
                                <p class="m-0 m-b-10 text-dark regular">Used Card Details</p>
                                <p class="m-0 m-b-10 regular">{{ $payment_acc_obj->getCardDetailsByCardNo($booking_details[0]->card_no)[0]->card_brand }} </p>
                                <p class="m-0 m-b-10 light">{{ $payment_acc_obj->showLastFourDigit($booking_details[0]->card_no) }} </p>
                            </div>
                            <?php 
                            }
                            if ($booking_details[0]->status == 3) { 
                                
                            
                            ?>
                                <div class="col-md-4">
                                    <!--<a href="{{ url("/user/user-rating") }}<?php echo '/' . $booking_details[0]->landscaper_id . '/' . $order_no; ?>" class="btn btn-success noradius m-t-35 float-right" >Edit Rate &amp; Review</a>-->
                                    <a href="#" class="btn btn-success noradius m-t-35 float-right" data-toggle="modal" data-target="#ratting" disabled>Write a Review</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="card-block p-x-20 p-y-35 service-review-description">
                        <h5 class="m-b-5 regular">Description</h5>
                        <p class="m-b-0 light">{{ $booking_details[0]->additional_note }}</p>
                    </div>

                    <div class="card-block p-x-20 p-b-30 p-t-0">
                        <div class="owl-carousel owl-theme">
                            <?php foreach ($user_service_images as $img) { ?>
                                <a class="item" href="{{ ($img->service_image == NULL) ? asset("/default/images/user.jpg") : asset("uploads/property/" . $img->service_image) }}" data-fancybox="group" data-caption="">

                                    <img src="{{ $img->service_image == '' ? asset("/default/images/user.jpg") : asset("uploads/property/" . $img->service_image) }}"/>

                                </a>
                            <?php } ?>
                        </div>
                    </div>

                    <hr class="m-0">
                    <?php
                    if (!empty($rating_details)) {
                        ?>
                        <div class="card-block p-x-20 p-y-30 user-review-group d-table">
                            <div class="user-review-avtar d-table-cell align-top">
                                <div class="avtar">
                                    <img src="<?php echo($profile_image->profile_image != "" && file_exists("uploads/profile_picture/" . $profile_image->profile_image)) ? url("uploads/profile_picture/" . $profile_image->profile_image) : asset('/default/images/avatar-landscaper.jpg'); ?>">
                                </div>
                            </div>
                            <?php
                            foreach ($rating_details as $rating_val) {
                                ?>
                                <div class="col-md booking-details-user-review d-table-cell align-top p-l-15">
                                    <div class="username-date w-100">
                                        <h4 class="text-left d-inline-block regular m-b-15">{{ $rating_val->name }}</h4>
                                        <h5 class="d-inline-block regular float-right">{{ date('D, M d Y',strtotime($rating_val->log_time)) }}</h5>
                                    </div>
                                    <div class="rating col-md-auto m-b-10">
                                        <ul class="list-unstyled m-b-0">
                                            <?php
                                            for ($i = 1; $i <= $rating_val->rating_value; $i++) {
                                                ?>
                                                <li><i class="fa fa-star"></i></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <p class="m-0">{{ $rating_val->review }}</p>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <hr class="m-0">
                    <div class="card-block p-x-20 p-y-30 service-review-description">
                        <h5 class="m-b-5 bold">Uploaded By Provider</h5>

                    </div>
                    <div class="card-block p-x-20 p-b-30 p-t-0">
                        <div class="owl-carousel owl-theme">
                            <?php
                            if (!empty($landscaper_service_images)) {
                                foreach ($landscaper_service_images as $img) {
                                    ?>
                                    <a class="item" href="{{ ($img->service_image == NULL) ? asset("/default/images/user.jpg") : asset("uploads/property/" . $img->service_image) }}" data-fancybox="group" data-caption="">

                                        <img src="{{ $img->service_image == '' ? asset("/default/images/user.jpg") : asset("uploads/property/" . $img->service_image) }}"/>

                                    </a>
                                    <?php
                                }
                            } else {
                                ?>
                                <p class="alert alert-danger">No image is uploaded yet.</p>
                                <?php
                            }
                            ?>
                        </div>
                    </div>


                </div> 
            </div>
            <div class="col-md-3">
                <div class="card no-b-radius price-box">
                    <div class="card-block">
                        <h5 class="card-title m-b-0 regular">Price Detail</h5>
                    </div>
                    <ul class="list-group list-group-flush">   
<!--                                <li class="list-group-item d-block"><p class="m-b-0 regular"> {{ $booking_details[0]->service_frequency }}<span class="inline-block float-right">$ {{ $booking_details[0]->service_price }}</span></p></li>-->

                        <!--<li class="list-group-item d-block"><p class="m-b-0 regular">Service booked<span class="inline-block float-right">$ {{ $booking_details[0]->service_booked_price }}</span></p></li>-->
<!--                                <li class="list-group-item d-block"><p class="m-b-0 text-dark regular">Tax Rate<span class="inline-block float-right">0.00 %</span></p></li>-->

                        <!--<li class="list-group-item d-block"><p class="m-b-0 text-dark regular">Tax<span class="inline-block float-right">20%</span></p></li>-->
                        <li class="list-group-item d-block total-price-bg"><p class="m-b-0 text-dark regular">Service Total<span class="inline-block float-right">$ {{ $booking_details[0]->service_price }}</span></p></li>
                    </ul>
                </div>
                <div class="amount-paid m-t-25">

                    <?php
                    if ($booking_details[0]->status == 3 || $booking_details[0]->status == 2) {
                        ?>  <p class="light m-l-5 text-success">
                        <?php
                        if ($booking_details[0]->is_completed == 1)
                            echo '<i class="fa fa-check-circle m-r-5"></i>Payment is in Escrow ';
                        if ($booking_details[0]->is_completed == 2)
                            echo '<i class="fa fa-check-circle m-r-5"></i>Payment Done ';
                        //Paid
                        if ($booking_details[0]->status == 3 && $booking_details[0]->is_completed == 1) {
                            ?>
                            <div class="clearfix">
                                <a href="javascript:void(0)" id="confirm-job-link" class="btn btn-success noradius m-t-15 m-b-15" >Accept Job Completion</a>
                                <p id="con-sug-msg" class="text-justify m-t-10"><b>Accepting the job will release payment to the provider. If you do not click "accept job completion," the funds will automatically release to the provider 24 hours after the provider marks the service as complete. If you have questions or concerns with the work that was performed, please contact customer service.</b></p>
                            </div>
                            <div id="confirm-job-msg" class=""></div>
                            <?php
                        }
                    } else if ($booking_details[0]->status == 1) {
                        ?>    
                        </p>
                        <p class="light m-l-5 text-warning">
                            <?php
                            echo '<i class="fa fa-exclamation-circle m-r-5 m-r-5"></i> Make Payment $' . $booking_details[0]->service_price . " to start work";
                        }
                        ?>
                        <!--</span> Total Amount ${{ $booking_details[0]->service_price }}</p>-->
                </div>
            </div>
        </div>
        <?php
        if ($booking_details[0]->status > 0) {
            ?>
            <div class="row"> 
                <div class="col-md-9 col-sm-12 col-12">
                    <?php
                    if ($booking_details[0]->status == 1) {
                        ?>
                        <div class="service-add m-b-30">
                            <div class="card m-t-30 card-no-radius">

                                <div class="card-block">
                                    <p><img src="{{ asset("/default/images/PayPal-logo.png") }}" alt="paypal" class="m-b-15" width="100"></p>
                                    <?php
                                    /* if ($booking_details[0]->status == 2 || $booking_details[0]->status == 3) {
                                      ?>
                                      <p class="btn btn-success btn-lg">Paid Total Amount ${{ $booking_details[0]->service_price }}</p>
                                      <?php
                                      } */

                                    if (!empty($landscaper_payment_details) && !empty($admin_payment_details)) {
                                        ?>
                                        <a  href="{{ url("/user/paypal-payment") }}<?php echo '/' . $order_no; ?>" class="btn btn-primary btn-lg" >Pay ${{ $booking_details[0]->service_price }} With Paypal</a>
                                        <?php
                                    } else {
                                        ?>
                                        <p class="alert alert-info d-block"><i class="fa fa-info-circle"></i> Please try after some time for payment</p>
                                        <?php
                                    }
                                    ?>
                                </div>

                            </div>
                        </div>
                        <?php
                    }
                    ?>

                    <?php if ($payment_dtls == '') { ?>

                        <div class="card custom-card card-no-radius m-b-30 m-t-30"> 
                            <div class="card-block">
                                <div class="add-paypal w-100 text-center">
                                    <img src="{{ asset("/default/images/credit-card(2).svg") }}" alt="" class="m-t-10" width="80">
                                    <div class="clearfix"></div>
                                    <a class="btn btn-secondary m-t-20" href="#" data-toggle="modal" data-target="#addcard">Add Credit/Debit Crad</a>
                                </div>
                            </div>                           
                        </div>


                    <?php } else { ?>





                        <div class="service-add m-b-30">
                            <?php
                            if ($booking_details[0]->status < 2) {
                                ?>


                                <div class="card m-t-30 card-no-radius">
                                    <div class="card-header card-header-success">
                                        <h4 class="m-0 regular">Pay With Debit/Credit Card</h4>

                                    </div>
                                    <div class="card-block">
                                        <form  id="new_card_payment" action="{{ url("/user/card-payment") }}" method="post">

                                            <div class="form-group">
                                                <label>Card Holder Name</label>
                                                <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Enter Card Holder Name" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Card No</label>
                                                <input type="text" id="card_number" name="card_number" class="form-control" placeholder="Enter Card No" maxlength="16" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Month</label>
                                                        <select class="form-control" name="month" required>
                                                            <option value="">Month</option>
                                                            <?php
                                                            for ($month = 1; $month <= 12; $month++) {
                                                                if ($month < 10)
                                                                    $month = '0' . $month;
                                                                ?>
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

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>CVV Number</label>
                                                        <input type="text" id="cvv" name="cvv" class="form-control" placeholder="Enter 3 Digit CVV" maxlength="3">
                                                    </div>
                                                </div>
                                                <input type="hidden" id="order_no" name="order_no" value="{{$order_no}}">
                                            </div>

                                    </div> 
                                    <div class="card-footer">
                                        <div class="row">

                                            <div class="col-md-12 col-sm-12 col-12 text-center">
                                                <?php
                                                if (!empty($landscaper_payment_details) && !empty($admin_payment_details) && !empty($payment_details)) {
                                                    ?>
                                                    <button type="submit" class="btn btn-success btn-lg">Pay ${{ $booking_details[0]->service_price }}</button>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <p class="alert alert-info d-block"><i class="fa fa-info-circle"></i> Please try after some time for payment</p>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>                                       
                                    </div>
                                    </form>
                                </div>
                            </div>

                            <?php
                        }

                        if ($booking_details[0]->status == 1) {
                            ?>
                            <div class="card m-t-30 card-no-radius">
                                <div class="card-header card-header-success">
                                    <h4 class="m-0 regular">My Saved Card</h4>
                                    <a href="" class="float-right medium add-icon header-link" data-toggle="modal" data-target="#addcard">ADD NEW CREDIT/DEBIT CARD</a>
                                </div>
                                <?php foreach ($payment_dtls as $payment) { ?>
                                    <div class="card-block">
                                        <div class="form-check">
                                            <label class="form-check-label d-block">
                                                <input class="form-check-input" name="optionsRadios" id="optionsRadios1" value="option1" type="radio" onClick="selectedCard('{{$payment->name}}',{{$payment->card_no}},{{$payment->month}},{{$payment->year}},{{$payment->cvv_no}});">
                                                <span class="address-block clearfix d-block w-100">
                                                    <div class="address-left float-left">
                                                        <p class="m-b-10 bold">{{$payment->card_brand}}</p>
                                                        <h5 class="regular m-b-10">{{$payment->card_no}}</h5>
                                                        <p class="m-b-10 light">Expiry Date: {{$payment->month}}/{{$payment->year}}</p>
                                                    </div>
                                                    <a href="javascript:void(0)" onclick="del_card(<?php echo $payment->id; ?>)" class="float-right medium m-l-15"> REMOVE</a>
                                                </span>
                                            </label>
                                        </div>                                        
                                    </div>
                                    <?php
                                }
                            
                            ?>




                            <!--<div class="card-footer">-->
                            <?php
                            /* if ($booking_details[0]->status != 3) {
                              ?>

                              <form class="modal-content" id="new_card_payment" action="{{ url("/user/card-payment") }}" method="post">
                              <input type="hidden" class="saved_customer_name" name="customer_name" id="customer_name" value="">
                              <input type="hidden" class="saved_card_number" name="card_number" id="card_number" value="">
                              <input type="hidden" class="saved_month" name="month" id="month" value="">
                              <input type="hidden" class="saved_year" name="year" id="year" value="">
                              <input type="hidden" class="saved_cvv" name="cvv" id="cvv" value="">
                              <input type="hidden" name="order_no" id="order_no" value="{{ $order_no }}">
                              <?php
                              if (!empty($landscaper_payment_details) && !empty($admin_payment_details) && !empty($payment_details)) {
                              ?>
                              <button type="submit" class="btn btn-success btn-lg">Pay ${{ $booking_details[0]->service_price }}</button>
                              <?php
                              } else {
                              ?>
                              <div class="alert alert-info m-b-0"><i class="fa fa-info-circle"></i> Please try after some time for payment</div>
                              <?php
                              }
                              ?>
                              </form>
                              <?php
                              } */
                            ?>

                            <!--</div>-->                                      



                        </div>
                        <?php
                        }
                    }
                    ?>
                </div>                                                  
                <?php
            }
            ?>    
        </div>  
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
                <input type="hidden" name="current_order_no" id="current_order_no" value="{{ $order_no }}">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>  
</div>
</div>  
</div>
<!-- Rating & Modal -->
<div class="modal fade" id="ratting" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" action="{{ url("/user/edit-user-rating") }}" method="post" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">How was your service with</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class=" custom-card m-b-50">
                    <!--                            <div class="card-header landscapers-review-heading text-center p-t-35 p-b-25">
                                                    <h4 class="m-0 light m-b-10"></h4>
                                                    <h5  class="m-0 regular">Jhon Harison ?</h5>
                                                </div>-->
                    <div class=" text-center">
                        <div class="form-group landscapers-rating text-center m-b-40">
                            <label for="rating" class="sm-bold m-b-20">Your Rating</label>
                            <input id="input-21c" name="rating" value="0" type="number" class="rating" min=0 max=5 step=1 data-size="sm" data-stars="5" required>
                        </div>
                        <div class="form-group">
                            <label for="review" class="sm-bold m-b-20">Write a Review</label>
                            <textarea class="form-control" rows="5" name="review" id="review" required></textarea>
                        </div>
                        <input type="hidden" name="landscaper_id" id="landscaper_id" value="{{ $booking_details[0]->landscaper_id }}">
                        <input type="hidden" name="order_no" id="order_no" value="{{ $order_no }}">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success noradius p-x-50">Submit</button>
            </div>
        </form>
    </div> 
</div> <!-- Modal -->
</div>
</section>

<?php
if (isset($msg)) {
    echo "<h1>HI</h1>";
    die;
}
?>
<!--    <script>
       $(window).load(function () {
           swal("Review submitted successfully",'', "success");
       });
   </script>-->
<?php ?>
<script>
    function del_card(id){
    if (confirm("Are you sure?")){
    window.location.href = "<?php echo url('payment/delete-payment-info'); ?>/" + id + "/<?php echo $order_no; ?>";
    }
    }

    function selectedCard(name, card_no, month, year, cvv_no)
    {
    $('.saved_customer_name').val(name);
    $('.saved_card_number').val(card_no);
    $('.saved_month').val(month);
    $('.saved_year').val(year);
    $('.saved_cvv').val(cvv_no);
    }
    $(document).ready(function(){
    $('#confirm-job-link').click(function(){
    var order_no = $('#current_order_no').val();
    //alert(order_no);
    $.ajax({
    url: '<?php echo url('/user/pay-from-escrow'); ?>',
            type: "POST",
            data: {order_no : order_no, is_completed : 2},
            success: function(data){
//                            alert(data.error);
            if (data.error == 0){
            $('#confirm-job-link').css('display', 'none');
            $('#confirm-job-msg').addClass('alert');
            $('#confirm-job-msg').addClass('alert-success');
            $('#con-sug-msg').css('display', 'none');
            $('#confirm-job-msg').html('Thank you for using Seazoned. You have successfully confirmed the job completion. The funds will now be released to the provider.');
            } else{
            $('#confirm-job-msg').addClass('alert');
            $('#confirm-job-msg').addClass('alert-danger');
            $('#confirm-job-msg').html('Something went wrong');
            }
            },
            dataType: "json"
    });
    });
    });
</script>
@endsection