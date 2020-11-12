<?php

use App\User; ?>
<?php

use App\Landscaper; ?>
@extends("layouts.dashboardlayout")
@section('content')

<section class="main-content dashboard p-y-30">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-no-radius user-profile side-menu">
                    <div class="card-block p-t-30 p-b-30">
                        <div class="avtar">
                            <?php
                        $user_obj = new User();
                        $owner_rating = $user_obj->owner_landscaper_rating();
                        $prof_pic = url('/') . '/default/images/avatar-landscaper.jpg';
                        if (session("prof_img") !== "") {
                            if (file_exists(public_path() . '/uploads/profile_picture/' . session("prof_img"))) {
                                $prof_pic = url('/public') . '/uploads/profile_picture/' . session("prof_img");
                            }
                        }
                        ?>
                            <img src="{{ $prof_pic }}">
                            <span class="profile-status"></span>
                        </div>
                        <div class="justify-content-md-center d-flex p-t-15">
                            <div class="rating col-md-auto">
                                <ul class="list-unstyled m-b-0">
                                    <?php
                                    if ($owner_rating != 0) {
                                        for ($i = 1; $i < $owner_rating; $i++) {
                                            ?>  
                                            <li ><i class="fa fa-star"></i></li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <h5 class="address text-center m-0 m-t-20"><i class=" fa fa-location-arrow"></i> <?php echo $info[0]->address ?></h5>
                        <h4 class="profile-name text-center m-0 m-t-10">
                            <?php echo(count($land_info) != 0 && isset($land_info[0]->name) && $land_info[0]->name != '') ? $land_info[0]->name : '' ?>
                        </h4>
                    </div>
                    <ul class="list-group list-group-flush list-unstyled job-btn-group">
                        <li class="active"><a href="#service_request" class="list-group-item" >Service Requests</a></li>
                        <li><a href="#pending_jobs" class="list-group-item">Pending Jobs</a></li>
                    </ul>
                </div>

                <div class="weather-widget m-t-30">
                        <div class="current-weather">
                            <p class="day">
                                <?php echo date('l'); ?>
                            </p>
                            <div class="date">                            
                                <h5 class="month"><?php echo ucwords(date('M')); ?><span><?php echo date('d-y'); ?></span></h5>
                            </div>
                            <div class="weather-icon text-center m-b-10" id="weather-icon">

                                <?php
                                if (isset($weather['current_observation']['condition']['code'])) {
                                    ?>
                                    <img class="weathericon" src="{{asset('')}}default/images/weathericons/<?php echo $weather['current_observation']['condition']['code'] ?>.svg">
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="weather-info">
                                <div class="temperature">
                                    <h2 class="m-b-0" id="temperature">
                                        <?php
                                        if (isset($weather['current_observation']['condition']['temperature'])) {
                                            echo $weather['current_observation']['condition']['temperature'] . 'F';
                                        }
                                        ?>
                                    </h2>
                                </div>
                                <div class="weather-type-location p-l-15">
                                    <h4 class="m-0" id="weather_code">
                                        <?php
                                        if (isset($weather['current_observation']['condition']['text'])) {
                                            echo $weather['current_observation']['condition']['text'];
                                        }
                                        ?>
                                    </h4>
                                    <p class="m-0 m-t-5" id="weather_location">
                                        <?php
                                        if (isset($weather['location']['city'])) {
                                            echo $weather['location']['city'];
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div><!-- /.weather-info -->
                        </div><!-- /.current-weather -->
                        <div class="weather-other-details">
                            <div class="row no-gutters">
                                <div class="col text-center text-white" id="weather_wind">
                                    <?php
                                    if (isset($weather['current_observation']['wind']['speed'])) {
                                        ?>
                                        <img src="{{asset('')}}default/images/wind.png" style="vertical-align: top;"> &nbsp; <span> <?php echo $weather['current_observation']['wind']['speed'] . ' Mi/h' ?> </span>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="col text-center" id="weather_humidity">
                                    <?php
                                    if (isset($weather['current_observation']['atmosphere']['humidity'])) {
                                        ?>
                                        <img src="{{asset('')}}default/images/humidity.png" style="vertical-align: top;"> &nbsp; <span> <?php echo $weather['current_observation']['atmosphere']['humidity'] . ' %' ?> </span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div><!-- /.weather-other-details -->
                        <div class="weekly-report">
                            <div class="weakly-report-scroll" id="content-6">
                                <ul class="list-inline list-unstyled clearfix m-b-0" id="weather_forecast">
                                    <?php
                                    if (isset($weather['forecasts']) && !empty($weather['forecasts'])) {
                                        foreach ($weather['forecasts'] as $key => $data1) {
                                            if ($key > 0 && $key < 7) {
                                                ?>
                                                <li class="text-center"><div class="day-temp clearfix"><h6 class="day"><?php echo $data1['day'] ?></h6><h5 class="temperature m-b-0 m-t-0"><?php echo $data1['code']; ?>&#x2103;</h5></div><div class="weather-icon"><img class="weathericon" src="{{asset('')}}default/images/weathericons/<?php echo $data1['code'] ?>.svg"></div></li>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                            </div><!-- //.weakly-report-scroll -->
                        </div><!-- /.weekly-report -->
                    </div><!-- /.weather-widget -->

            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-4">
                        <div class="color-block block-violet p-y-30 p-x-20">
                            <div class="color-block-icon">
                                <img src="{{ asset("default/images/suitcase.svg") }}">
                            </div>
                            <div class="color-block-text p-l-15">
                                <!--                                        <h5 class="m-0">15</h5>-->
                                <h5 class="m-0">{{ sprintf("%02d", count($services_req)) }}</h5>
                                <p class="m-0 m-t-10">New Job</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="color-block block-blue p-y-30 p-x-20">
                            <div class="color-block-icon">
                                <img src="{{ asset("default/images/money-bag.svg") }}">
                            </div>
                            <div class="color-block-text p-l-15">
                                <!--<h5 class="m-0">&dollar;7568.00</h5>-->
                                <h5 class="m-0">{{ sprintf("%02d", $total_revenue[0]->total_revenue) }}</h5>
                                <p class="m-0 m-t-10">Total Revenue</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="color-block block-red p-y-30 p-x-20">
                            <div class="color-block-icon">
                                <img src="{{ asset("default/images/warning.svg") }}">
                            </div>
                            <div class="color-block-text p-l-15">
                                <h5 class="m-0">{{ sprintf("%02d", count($services_pend)) }}</h5>
                                <p class="m-0 m-t-10">Pending Jobs</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (session('msg') != "") { ?>
                    <div class="alert alert-custom d-block text-center m-t-30">
                            <span class="big-icon text-success"><i class="fa fa fa-check-circle" aria-hidden="true"></i></span>
                            <h5>Success!</h5>
                            <p> {{ session('msg') }}</p>
                        </div>
                    
                <?php 
                
                } 
                if (count($services_req) > 0) {
                ?>
                <div class="card custom-card card-no-radius m-t-30" id="service_request">
                    
                    <div class="card-header">
                        Service Request
                    </div>

                    <?php
                    
                        $i = 1;
                        foreach ($services_req as $one_service) {

                            $landscaper_obj = new Landscaper();
                            $user_rating = $landscaper_obj->get_user_overall_rating($one_service['book_service']->customer_id);
                          
                        $user_obj = new User();
                        $owner_rating = $user_obj->owner_landscaper_rating();
                        $prof_pic = url('/') . '/default/images/userdefault.png';
                        if (session("prof_img") !== "") {
                            if (file_exists(public_path() . '/uploads/profile_picture/' . session("prof_img"))) {
                                $prof_pic = url('/public') . '/uploads/profile_picture/' . session("prof_img");
                            }
                        }
                            ?>
                            <div class="card-block">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="service-req-box d-block">
                                            <div class="client-image text-center">
                                                <div class="avtar">
                                                    <img src="{{ $prof_pic }}">
                                                </div>
                                                <span class="badge badge-success">{{(isset($user_rating) && $user_rating!="")?$user_rating:0}}<i class="fa fa-star"></i></span>
                                            </div>
                                            <div class="client-details p-l-15 align-top">
                                                <h4 class="m-0"><?php echo $one_service['book_address']->name; ?></h4>
                                                <p class="m-0 m-t-8"><?php echo $one_service['book_address']->address; ?></p>
                                            </div>
                                        </div>
                                        <div class="m-t-20 service-req-btn">
                                            <a href="{{ url("landscaper-dashboard/update-service").'/'.$one_service['book_service']->id.'/-1' }}" class="btn btn-secondary">Decline</a>
                                            <a href="{{ url("landscaper-dashboard/update-service").'/'.$one_service['book_service']->id.'/1' }}" class="btn btn-success">Accept Job</a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="dashboard-service-details">
                                            <h4 class="m-0 m-b-10"><?php echo $one_service['name']->service_name; ?></h4>
                                            <p class="m-0 m-b-10"><b>Price :</b> $<?php echo $one_service['book_service']->service_booked_price; ?></p>
                                            <p class="m-0 m-b-10"><b>Service Date :</b> <?php echo date('D, M d Y', strtotime($one_service['book_service']->service_date)); ?></p>
                                            <p class="m-0 m-b-10"><b>Preferred Time :</b> <?php echo date('h:i a', strtotime($one_service['book_service']->service_time)); ?></p>
                                            <a href="{{ url('landscaper-dashboard/view-service-details').'/'.$one_service['book_service']->id }}">View Service Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                            if($i < count($services_req)){
                                echo '<hr class="m-0">';
                            }
                            $i++;
                        }
                    
                    /*else {
                        ?>
                        <div class="card-block">
                            <div class="alert alert-custom d-block text-center">
                                <span class="big-icon text-danger"><i class="fa fa-times-circle" aria-hidden="true"></i></span>
                                <h5>Sorry!</h5>
                                <p>No Service Request Found</p>
                            </div>
                        </div>
                    <?php 
                    
                    } */
                    ?>

                </div>
                <?php
                }
                ?>

                <div class="card card-no-radius custom-card m-t-30" id="pending_jobs">
                    <div class="card-header">
                        Pending Jobs
                    </div>
                    <div class="card-block">
                        <?php
                        if (count($services_pend) > 0) {
                            foreach ($services_pend as $one_service) {
//                                  
                                $landscaper_obj = new Landscaper();
                                $user_rating = $landscaper_obj->get_user_overall_rating($one_service['book_service']->customer_id);
                                ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="service-req-box d-block">
                                            <div class="client-image text-center">
                                                <div class="avtar">
                                                    <?php
                                                    $user_obj = new User();
                                                    $owner_rating = $user_obj->owner_landscaper_rating();
                                                    $prof_pic = url('/') . '/default/images/userdefault.png';
                                                    if (session("prof_img") !== "") {
                                                        if (file_exists(public_path() . '/uploads/profile_picture/' . session("prof_img"))) {
                                                            $prof_pic = url('/public') . '/uploads/profile_picture/' . session("prof_img");
                                                        }
                                                    }
                                                    ?>
                                                    <!--<img src="{{ url('/') }}/uploads/profile_picture/{{$one_service['image']->profile_image}}">-->
                                                    <img src="{{ $prof_pic }}">
                                                </div>
                                                <span class="badge badge-success">{{(isset($user_rating) && $user_rating!="")?$user_rating:0 }} <i class="fa fa-star"></i></span>
                                            </div>
                                            <div class="client-details p-l-15 align-top">
                                                <h4 class="m-0"><?php echo $one_service['book_address']->name; ?></h4>
                                                <p class="m-0 m-t-8"><?php echo $one_service['book_address']->address; ?></p>
                                            </div>
                                        </div>
                                        <!--                                        <div class="m-t-20 service-req-btn">
                                                                                    <a href="" class="btn btn-secondary">Decline</a>
                                                                                    <a href="" class="p-x-20 m-l-10"><img src="{{ asset("default/images/send-copy.png") }}"></a>
                                                                                </div>-->
                                    </div>
                                    <div class="col-md-6">
                                        <div class="dashboard-service-details">
                                            <h4 class="m-0 m-b-10"><?php echo $one_service['name']->service_name; ?></h4>
                                            <p class="m-0 m-b-10"><b>Price :</b> $<?php echo $one_service['book_service']->service_booked_price; ?></p>
                                            <p class="m-0 m-b-10"><b>Service Date :</b> <?php echo date('D, M d Y', strtotime($one_service['book_service']->service_date)); ?></p>
                                            <p class="m-0 m-b-10"><b>Preferred Time :</b> <?php echo date('h:i a', strtotime($one_service['book_service']->service_time)); ?></p>
                                            <a href="{{ url('landscaper-dashboard/view-service-details').'/'.$one_service['book_service']->id }}">View Service Details</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                            }
                        } else {
                            ?>
                            <div class="alert alert-custom text-center d-block">
                                <span class="big-icon text-success"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                                <h5>Great!</h5>
                                You have No Pending Jobs
                            </div><br>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<script>
                            
         $(document).ready(function () {
                var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                $.ajax({
                    url: '{{ url("/Home/getDateTimeByTimezone") }}',
                    data: {timezone:timezone},
                    type: 'post',
                    success: function (response) {
                    }
                });
                
                <?php
                if($acc_data == 0){
                ?>
                alert("Please add your account details to connect with seazoned.");
                window.location.href = "{{route('landscaper-payment-info')}}";
                <?php
                }
                ?>
            });
</script>
@endsection

