<?php

use App\Landscaper; ?>
@extends("layouts.dashboardlayout")
@section('content')
@if(session("user_role") != "Users" && session("user_id") == "")
<div class="alert alert-custom-danger text-center"> 
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <i class="fa fa-info-circle" aria-hidden="true"></i> 
    <strong>Info!</strong> Please <a href="{{ url("/user-login") }}" class="links"><strong>Log in/Register</strong></a> to view price and book now</div>
@endif
<section class="feature-banner" style="background: linear-gradient(180deg, rgba(0,0,0,0.0) 15%, rgba(0,0,0,0.8) 80%), url('{{ ($landscapper_info[0]->profile_image == NULL) ? url("/default/images/feature-image-big.jpg") : url("/uploads/services/" . $landscapper_info[0]->profile_image) }}') no-repeat 0 0 / cover;">
    <div class="banner-profile-position">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="banner-profile-sec clearfix">
                        <div class="avtar">
                            <?php
                            $prof_pic = url('/') . '/default/images/avatar-landscaper.jpg';
                            if (isset($landscaper_profile_picture) && $landscaper_profile_picture != "") {
                                if (file_exists(public_path() . '/uploads/profile_picture/' . $landscaper_profile_picture)) {
                                    $prof_pic = url('/public') . '/uploads/profile_picture/' . $landscaper_profile_picture;
                                }
                            }
                            ?>
                            <img src="{{ $prof_pic }}">
                        </div>
                        <?php
                        $landscaper_obj = new Landscaper();
                        $result = $landscaper_obj->get_overall_rating($landscaper_id);
                        $data = $landscaper_obj->get_all_rating($landscaper_id);
                        $res = $landscaper_obj->get_total_rating_count($landscaper_id);
                        $res1 = $landscaper_obj->get_total_review_count($landscaper_id);
                        ?>
                        <div class="pro-details">
                            <h4 class="profile-name medium m-0">{{ $landscapper_info[0]->name }}</h4>
                            <h5 class="address light m-0 m-t-10"><i class=" fa fa-location-arrow"></i> {{ $landscapper_info[0]->location }}</h5>
                            <div class="m-t-10">
                                <span class="badge badge-primary">{{$result}} <i class="fa fa-star"></i></span>
                                <small>({{$res}} ratings & {{$res1}} reviews)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>              
</section>
<section class="main-content search-details p-y-30">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-12">

                <div class="services-list m-t-40">
                    <h3 class="page-heading m-0 regular">{{ $landscapper_info[0]->name }}’s Services</h3>
                    <hr class="m-b-20">
                    <div class="row">
                        @foreach($users_landscapers as $landscapers)
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <a class="service-box text-center " href="{{ url('/Home/Service-Details/'.$landscapers->landscaper_id )}}">
                                <div class="service-img">
                                    <img src="{{ asset("/default/images/" . $landscapers->logo_name) }}">
                                </div>
                                <h4 class="d-block m-0 m-t-20">{{ $landscapers->service_name }}<i class="fa fa-caret-right"></i></h4>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="description">
                    <h3 class="page-heading m-0 regular">Description</h3>
                    <hr class="m-b-20">
                    <p>{{ $landscapper_info[0]->description }}</p>
                </div>
                <div class="rating m-t-30">
                    <h3 class="page-heading m-0 regular">Rating and reviews</h3>
                    <hr class="m-b-20">
                    <div class="rating-line-total">
                        <div class="total-value">
                            {{$result}}
                        </div>
                        <div class="scale-bar">
                            <div class="ratingvalue">
                                <p class="value">5.0 <i class="fa fa-star" aria-hidden="true"></i></p>
                                <div class="ratingline-total">
                                    <div class="rating-line">
                                        <div class="line-value green-bg" style="width: {{($res!=0)?($data[5]*100)/$res:0}}%;"></div>
                                    </div>
                                </div>
                                <div class="rating-count">{{$data[5]}}</div>
                            </div>
                            <div class="ratingvalue">
                                <p class="value">4.0 <i class="fa fa-star" aria-hidden="true"></i></p>
                                <div class="ratingline-total">
                                    <div class="rating-line">
                                        <div class="line-value green-bg" style="width: {{($res!=0)?($data[4]*100)/$res:0}}%;"></div>
                                    </div>
                                </div>
                                <div class="rating-count">{{$data[4]}}</div>
                            </div>
                            <div class="ratingvalue">
                                <p class="value">3.0 <i class="fa fa-star" aria-hidden="true"></i></p>
                                <div class="ratingline-total">
                                    <div class="rating-line">
                                        <div class="line-value orange-bg" style="width: {{($res!=0)?($data[3]*100)/$res:0}}%;"></div>
                                    </div>
                                </div>
                                <div class="rating-count">{{$data[3]}}</div>
                            </div>
                            <div class="ratingvalue">
                                <p class="value">2.0 <i class="fa fa-star" aria-hidden="true"></i></p>
                                <div class="ratingline-total">
                                    <div class="rating-line">
                                        <div class="line-value orange-bg" style="width: {{($res!=0)?($data[2]*100)/$res:0}}%;"></div>
                                    </div>
                                </div>
                                <div class="rating-count">{{$data[2]}}</div>
                            </div>
                            <div class="ratingvalue">
                                <p class="value">1.0 <i class="fa fa-star" aria-hidden="true"></i></p>
                                <div class="ratingline-total">
                                    <div class="rating-line">
                                        <div class="line-value red-bg" style="width: {{($res!=0)?($data[1]*100)/$res:0}}%;"></div>
                                    </div>
                                </div>
                                <div class="rating-count">{{$data[1]}}</div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <?php
                    if (count($service_rating) != 0) {
                        foreach ($service_rating as $rating_array) {
                            ?>

                            <div class="card-block col-md p-x-0 p-y-15 user-review-group d-table">
                                <div class="user-review-avtar d-table-cell align-top">
        <?php
        $prof_pic = url('/') . '/default/images/userdefault.png';
        if (isset($rating_array->profile_image) && $rating_array->profile_image != "") {
            if (file_exists(public_path() . '/uploads/profile_picture/' . $rating_array->profile_image)) {
                $prof_pic = url('/') . '/uploads/profile_picture/' . $rating_array->profile_image;
            }
        }
        ?>
                                    <div class="avtar">
                                        <img src="{{$prof_pic}}">
                                    </div>
                                </div>
                                <div class="booking-details-user-review d-table-cell align-top p-l-15">
                                    <div class="username-date w-100">
                                        <h4 class="text-left d-inline-block regular m-b-15">{{ $rating_array->first_name.' '.$rating_array->last_name }}</h4>
        <?php
        if ($rating_array->log_time != "") {
            ?>
                                            <h5 class="d-inline-block regular float-right">{{ date('d-M-Y',strtotime($rating_array->log_time))  }}</h5>
                                        <?php } ?>                                        
                                    </div>
                                    <div class="rating col-md-auto m-b-10">
                                        <ul class="list-unstyled m-b-0">
        <?php
        if ($rating_array->rating_value != 0) {
            for ($j = 0; $j < $rating_array->rating_value; $j++) {
                ?>  
                                                    <li ><i class="fa fa-star"></i></li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                            <?php
                                            if ($rating_array->review != "") {
                                                ?>
                                        <p class="m-0">{{ $rating_array->review }}</p>
                                    <?php } ?>
                                </div>
                            </div>
                            <hr>
                                    <?php
                                }
                            } else {
                                ?> 
                        <p class="alert alert-danger"> No Reviews Found.</p>
                    <?php } ?>

                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="book-now-form">
                    <form action="{{ url("Home/Service/Add-To-Cart") }}" method="post" enctype="multipart/form-data">
                        <div class="form-header">
                            @if(session("user_role") == "Users" && session("user_id") != "")
                            <h2 class="text-uppercase sm-bold">BOOK NOW</h2>
                            @endif    
                            <div class="service-intro">
                                @if(session("user_role") == "Users" && session("user_id") != "")
                                <p class="sm-bold m-b-5">Lawn Treatment Total</p>
                                @endif
                                @if(session("user_role") == "Landscaper" || session("user_role") == "")
                                <p class="sm-bold m-b-5">Lawn Treatment</p>
                                @endif    
                                @if(session("user_role") == "Users" && session("user_id") != "")
                                <h3>$<span class="price_value">0.00</span></h3>
                                @endif    
                            </div>
                        </div>
                        <input type="hidden" name="landscaper_id" value="{{ $landscaper_id }}">
                        <div class="form-body">
                            <div class="form-group">
                                <label for="lawnsize" class="sm-bold title-label">Size of lawn (Acreage) <span class="text-danger">*</span></label>
                                <select class="form-control item" id="lawnsize">
                                    @foreach($acres as $acre)
                                    <option value="{{ $acre->service_field_price }}">{{ $acre->service_field_value }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="lawn_area" id="lawn_area">
                            </div>
                            <input type="hidden" value="{{ $landscapper_info[0]->service_id }}" name="service_id">
                            <input type="hidden" value="NaN" name="service_price" class="price_value">
                            <input type="hidden" value="{{$percentage}}" name="percentage" id="percentage">
                            <input type="hidden" value="NaN" name="service_booked_price" id="service_booked_price">
                            <input type="hidden" value="<?php echo $lat ?>" name="lat" >
                            <input type="hidden" value="<?php echo $lng ?>" name="lng" >
                            @if(session("user_role") == "Users" && session("user_id") != "")
                            <div class="text-center">
                                <button class="btn btn-success text-uppercase btn-lg sm-bold btn-block">CONTINUE BOOKING</button>
                            </div>


                        </div>

                    </form>

                </div>
                @else
                <!-- <div >
                    <a href="{{ url("/user-login") }}">Log in/Register to view price and book now</a>
                </div> -->
                @endif
                <div class="weather-widget m-t-30">
                    <div class="current-weather">
                        <p class="day">
                            <?php echo date('l'); ?>
                        </p>
                        <div class="date">                            
                            <h5 class="month"><?php echo ucwords(date('M')); ?><span><?php echo date('d-y'); ?></span></h5>
                        </div>
                        <div class="weather-icon text-center m-b-25" id="weather-icon">
                            
                            <?php
                            if(isset($weather['current_observation']['condition']['code'])){
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
                                    if(isset($weather['current_observation']['condition']['temperature'])){
                                        echo $weather['current_observation']['condition']['temperature'].'F';
                                    }
                                ?>
                                </h2>
                            </div>
                            <div class="weather-type-location p-l-15">
                                <h4 class="m-0" id="weather_code">
                                <?php
                                if(isset($weather['current_observation']['condition']['text'])){
                                    echo $weather['current_observation']['condition']['text'];
                                }
                                ?>
                                </h4>
                                <p class="m-0 m-t-5" id="weather_location">
                                <?php
                                if(isset($weather['location']['city'])){
                                    echo $weather['location']['city'];
                                }
                                ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="weather-other-details">
                        <div class="row no-gutters">
                            <div class="col text-center text-white" id="weather_wind">
                                <?php
                                if(isset($weather['current_observation']['wind']['speed'])){
                                ?>
                                <img src="{{asset('')}}default/images/wind.png" style="vertical-align: top;"> &nbsp; <span> <?php echo $weather['current_observation']['wind']['speed'].' Mi/h' ?> </span>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="col text-center" id="weather_humidity">
                                <?php
                                if(isset($weather['current_observation']['atmosphere']['humidity'])){
                                ?>
                                <img src="{{asset('')}}default/images/humidity.png" style="vertical-align: top;"> &nbsp; <span> <?php echo $weather['current_observation']['atmosphere']['humidity'].' %' ?> </span>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="service-hours m-t-30">
                    <h4 class="sm-bold">Service Hours</h4>
                    <table class="table">

                        <tbody>
                            @foreach($service_time as $time)
                            <tr>
                                <td><b>{{ $time->service_day }}</b></td>
                                <td class="text-right">{{ strtoupper(date("g:i a", strtotime($time->start_time))) }} - {{ strtoupper(date("g:i a", strtotime($time->end_time))) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function () {
        var price = parseFloat($("#lawnsize").val());
        $("#service_booked_price").val(price);
        var percentage = parseFloat($("#percentage").val());
        price = price + (price * (percentage / 100));

        $(".price_value").html(price.toFixed(2));
        $(".price_value").val(price.toFixed(2))
        $("#lawn_area").val($("#lawnsize option:selected").text());

        $(".item").change(function () {
            price = parseFloat($("#lawnsize").val());
            $("#service_booked_price").val(price);
            var percentage = parseFloat($("#percentage").val());
            price = price + (price * (percentage / 100));
            $(".price_value").html(price.toFixed(2));
            $(".price_value").val(price.toFixed(2));
            $("#lawn_area").val($("#lawnsize option:selected").text());
        });
    });
</script>
@endsection