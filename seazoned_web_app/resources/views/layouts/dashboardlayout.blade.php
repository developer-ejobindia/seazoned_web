<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <link rel="shortcut icon" href="{{ url('/') }}/default/images/favicon.ico" type="image/x-icon">
        <link rel="icon" href="{{ url('/') }}/default/images/favicon.ico" type="image/x-icon">
        <title>Seazoned App</title>

        <!-- Bootstrap -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900"
              rel="stylesheet">
       
        <link rel="stylesheet" type="text/css" href="{{ asset("default/plugins/bootstrap-4.0.0/css/bootstrap.css") }}" rel="stylesheet">
        <link href="{{ asset("default/plugins/fontawesome-free-5.0.0/web-fonts-with-css/css/fontawesome-all.css") }}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset("default/css/style.css") }}">
        <link rel="stylesheet" type="text/css" href="{{ asset("default/css/responsive.css") }}">
        <link rel="stylesheet" type="text/css" href="{{ asset("default/plugins/custom-timepicker/css/custom_timepicker.css") }}">
        <link rel="stylesheet" type="text/css" href="{{ url('/') }}/default/plugins/sweetalert2-7.16.0/dist/sweetalert2.css">
        <link rel="stylesheet" type="text/css" href="{{ asset("default/plugins/custom-scrollbar/jquery.mCustomScrollbar.css") }}">
        <!--<link rel="stylesheet" type="text/css" href="{{ asset("default/css/jquery-ui.css") }}">-->
        <!--<script type="text/javascript" src="{{ asset("default/js/jquery-ui.js") }}"></script>-->

        <link rel="stylesheet" type="text/css" href="{{ asset("default/plugins/fancybox-master/dist/jquery.fancybox.min.css") }}">
        <link rel="stylesheet" href="{{ asset("default/plugins/OwlCarousel2-2.2.1/dist/assets/owl.carousel.min.css") }}">

        <!--<link href="{{ asset("default/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker-standalone.css") }}" rel="stylesheet" type="text/css">-->
        <link href="{{ asset("default/plugins/bootstrap-star-rating-master/css/star-rating.css") }}" rel="stylesheet" type="text/css">
        <!-- ionRange Slider -->
        <link href="{{ asset("default/plugins/rangeSlider/css/normalize.css") }}" rel="stylesheet" type="text/css">
        <link href="{{ asset("default/plugins/rangeSlider/css/ion.rangeSlider.css") }}" rel="stylesheet" type="text/css">
        <link href="{{ asset("default/plugins/rangeSlider/css/ion.rangeSlider.skinNice.css") }}" rel="stylesheet" type="text/css">

        
        <script type="text/javascript" src="{{ asset("default/js/jquery-3.2.1.js") }}"></script>
        <script type="text/javascript" src="{{ asset("default/plugins/bootstrap-4.0.0/js/bootstrap.min.js") }}"></script>
        <script type="text/javascript" src="{{ url('/') }}/default/plugins/sweetalert2-7.16.0/dist/sweetalert2.min.js"></script>
        <script type="text/javascript" src="{{ asset("default/plugins/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js") }}"></script>
        <script type="text/javascript" src="{{ url('/') }}/default/js/custom.js"></script>
        <script type="text/javascript" src="{{ url('/') }}/default/plugins/custom-timepicker/js/custom_timepicker.js"></script>
        <script src="{{ asset("default/js/less.min.js") }}"></script>

        <script type="text/javascript" src="{{ asset("default/plugins/fancybox-master/dist/jquery.fancybox.js") }}"></script>
        <script type="text/javascript" src="{{ asset("default/plugins/OwlCarousel2-2.2.1/dist/owl.carousel.js") }}"></script>

        <?php 
        $currentRouteName = \Request::route()->getName();
        
//        if($currentRouteName!="" && ($currentRouteName=="homeSearchListLocation" || $currentRouteName=="homeServiceDetails")){ 
            ?>
<!--            <script type="text/javascript" src="{{ asset("default/js/weather-widget.js") }}"></script>
            <script type="text/javascript" src="{{ asset("default/plugins/jquery.simpleWeather/jquery.simpleWeather.min.js") }}"></script>-->
        <?php 
        
//        } 
        ?>
        
        <?php //if(!isset($tab) || $tab!="index_page") { ?>
        <!--<script type="text/javascript" src="{{ asset("default/js/weather-widget.js") }}"></script>
        <script type="text/javascript" src="{{ asset("default/plugins/jquery.simpleWeather/jquery.simpleWeather.min.js") }}"></script>-->
        <?php //} ?>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
        <!--<script type="text/javascript" src="{{ asset("default/plugins/bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js") }}"></script>-->
        <!--<script type="text/javascript" src="{{ asset("default/plugins/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.fr.js") }}" charset="UTF-8"></script>-->
        <script type="text/javascript" src="{{ asset("default/plugins/bootstrap-star-rating-master/js/star-rating.js") }}" charset="UTF-8"></script>

        <script type="text/javascript" src="{{ asset("default/plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.js") }}"></script>
        <link href="{{ asset("default/plugins/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css") }}" rel="stylesheet" type="text/css">

        <script type="text/javascript" src="{{ url('/') }}/default/plugins/rangeSlider/js/ion-rangeSlider/ion.rangeSlider.js"></script>
        



        <script type="text/javascript">
$(function () {
    //            $(".select-date").datepicker({
    //                yearRange: "-100:-18",
    //                changeYear: true,
    //                changeMonth: true
    //            });

    $('.form_time').datetimepicker({
        language: 'en',
        weekStart: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0,
        showMeridian: true
    });
});
$(document).ready(function () {
    $("#btn-show-search").click(function () {
        $("body").addClass("show-search");
    });
    $('#btn-close-search').click(function (e) {
        e.preventDefault();
        $("body").removeClass("show-search");
    });

    //            $("#find_btn").click(function () {
    //                var txt = $("#loc_txt").val();
    //                url = {{ url("/Home/Search-List/")}} + "" + txt;
    //                $.post(url, function (data) {
    //                });
    //            });

    var owl = $('.owl-carousel');
    owl.owlCarousel({
        margin: 20,
        nav: true,
        loop: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });
});
        </script>

        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-36251023-1']);
            _gaq.push(['_setDomainName', 'jqueryscript.net']);
            _gaq.push(['_trackPageview']);

            (function () {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();


            $(function () {
                $(".mobile-menu-icon").click(function () {
                    $("#mobile-menu-drop").toggleClass()
                });
            });
        </script>
        <!-- End Plugin -->
        <style>
            .error {
                color: red;
            }
        </style>
    </head>

    <body class="search-result">

        @section("header")

        @if(session("user_id") == "")
        
        <header class="header">
            <div class="top-header">
                <div class="container-fluid">
                    <div class="row">                     
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-5 col-7">
                            <a class="logo" href="{{ url("/") }}">
                                <img src="{{ url('/') }}/default/images/logo_seazoned.png" alt="Seazoned Logo" class="d-none d-md-block">
                                <img src="{{ url('/') }}/default/images/logomain.jpg" alt="Seazoned Logo" class="d-sm-block d-md-none d-lg-none">
                            </a>
                        </div>

                        <div class="col-xl-7 col-lg-6 col-md-7 col- nav-search-container">
                            <div class="nav-search">
                                <form class="form-inline" method="post" action="{{ url("/Home/search-list-location") }}" id="search-list-location" onsubmit="return checkAddress()">
                                    <input type="hidden" name="min_rate" id="min_rate"> 
                                    <input type="hidden" name="max_rate" id="max_rate">
                                    <input type="hidden" name="filter_price" id="filter_price">
                                    <!--<input type="hidden" name="service_id" id="service_id">-->
                                    <input type="hidden" name="_token" value="<?php echo csrf_token() ?>"> 
                                    <input type="hidden" name="select_service_id" id="select_service_id" value="<?php echo(isset($select_service_id) && $select_service_id != "") ? $select_service_id : ''; ?>">
                                    <div class="input-group input-group-lg w-100"> 
                                       <h5 class="d-lg-none d-sm-none d-md-none d-sm-block">Search service</h5>
                                       
                                        <input type="text" class="form-control" value="<?php echo(isset($loc_txt) && $loc_txt != "") ? $loc_txt : ''; ?>"
                                               placeholder="Location, City Name, Area, Zip Code" id="loc_txt" name="loc_txt" onfocus="initAutocompleteSearch(this.id)"  >
                                        <!--<input type="hidden" name="service_txt" id="service_txt" value="1">-->
                                                   <?php if(isset($flag) && ($flag == "hide")){
                                                       echo '';?>
                                            <?php } else{ 
                                                if(isset($services) && $services != ''){?>
                                        <!--<label for="service-name">Service Name</label>-->
                                        <select class="form-control service_select_box_cls" name="service_txt"  id="service_txt" >
                                            <option value="0" disabled selected>Select a option</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}">{{ $service->service_name }}</option>
                                            @endforeach
                                        </select>
                                                <?php      }else{
                                                     echo '';
                                                }  }?>
                                         <span class="input-group-btn">
                                            <button class="btn btn-lg" type="submit"  id="find_btn"><i
                                                    class="fa fa-search"></i> Find</button>
                                        </span>
                                       
                                        
                                        
                                        
                                      
                                     <span class="input-group-btn d-lg-none d-sm-none d-md-none d-sm-block">
                                            <a class="btn btn-danger" id="btn-close-search" href="#">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        </span>
                                        
                                    
                                    </div>
                                </form>
                                <span id="address_err" class="text-danger"></span>
                            </div>
                            <span style="float:right;text-align:center;" id="sid_err" class="text-danger"></span>
                        </div>
                         
                        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-5 offset-xl-0 offset-lg-0 offset-md-0 offset-sm-2 col-5">
                            <nav class="header-menu float-lg-right">
                                <ul class="list-unstyled mb-0">
                                    <li class="search-icon hidden-md-up" id="btn-show-search"><a href="javascript:void(0)"><img src="{{ url('/') }}/default/images/magnifying-glass.svg" alt="" width="24"></a></li>
                                    <li class="menu-link hidden-md-down"><a href="{{ url("/FAQ") }}">FAQ</a></li>
                                    <li class="menu-link hidden-md-down"><a href="">Help</a></li>
                                    <li class="menu-login-btn"><a href="{{ url("/user-login/") }}">Login</a></li>
                                    <li class="menu-collapse-btn" id="button"><img src="{{ url('/') }}/default/images/menu-bars.svg" alt="" width="24"></li>
                                </ul>
                            </nav>
                        </div>
                        <div id="mySidenav" class="sidenav" id="nav-backdrop in">
                            <a href="javascript:void(0)" class="close-sidebar">&times;</a>
                            <div class="sidenav-menu">
                                <a href="{{url("/")}}">Home</a>
                                <a href="{{url("/about-us-view")}}">About Us</a>
                                <a href="{{url("Home/search-list-location")}}">Explore Services</a>
                                <a href="">Seazoned Blog</a>
                                <a href="{{ url("/privacyPolicy") }}">Privacy Policy</a>
                                <a href="">Press Room</a>
                                <a href="{{ url("/FAQ") }}" class="hidden-lg-up">FAQ</a>
                                <a href="" class="hidden-lg-up">Help</a>
                                <a href="" id = "contact-focus">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        @else
        <header class="header">
            <div class="top-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-5 col-5">
                            <a class="logo mt-2" href="{{ url("/") }}">
                                <img src="{{ url('/') }}/default/images/logo_seazoned.png" alt="Seazoned Logo" class="d-none d-md-block">
                                <img src="{{ url('/') }}/default/images/logomain.jpg" alt="Seazoned Logo" class="d-sm-block d-md-none d-lg-none">
                            </a>
                        </div>
                        <div class="col-xl-7 col-lg-6 col-md-7 col- nav-search-container">
                            <div class="nav-search">
                                <form class="form-inline" method="post" action="{{ url("/Home/search-list-location") }}" id="search-list-location" onsubmit="return checkAddress()">
                                    <input type="hidden" name="min_rate" id="min_rate"> 
                                        <input type="hidden" name="max_rate" id="max_rate"> 
                                        <input type="hidden" name="filter_price" id="filter_price"> 
                                        <input type="hidden" name="_token" value="<?php echo csrf_token() ?>"> 
                                        <input type="hidden" name="select_service_id" id="select_service_id" value="<?php echo(isset($select_service_id) && $select_service_id != "") ? $select_service_id : ''; ?>"> 
                                        @if(session("profile_id") != "3")
                                        <div class="input-group input-group-lg w-100">
                                         <h5 class="d-lg-none d-sm-none d-md-none d-sm-block">Search service</h5>
                                       <input type="text" class="form-control" value="<?php echo(isset($loc_txt) && $loc_txt != "") ? $loc_txt : ''; ?>"
                                               placeholder="Location, City Name, Area, Zip Code" id="loc_txt" name="loc_txt" onfocus="initAutocompleteSearch(this.id)" >
                                        
                                        <?php if(isset($flag) && ($flag == "hide")){
                                                       echo '';?>
                                            <?php } else{ 
                                                if(isset($services) && $services != ''){?>
                                        <!--<label for="service-name">Service Name</label>-->
                                        <select class="form-control service_select_box_cls" name="service_txt"  id="service_txt">
                                            <option value="0" disabled selected>Select A Option</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}">{{ $service->service_name }}</option>
                                            @endforeach
                                        </select>
                                                <?php      }else{
                                                     echo '';
                                                }  }?>
                               <?php //echo "<pre>";       print_r($services_pend); ?>         
                                        
                                        <span class="input-group-btn">
                                            <button class="btn btn-lg" type="submit"  id="find_btn"><i
                                                    class="fa fa-search"></i>Find</button>
                                        </span>
 <span class="input-group-btn d-lg-none d-sm-none d-md-none d-sm-block">
                                            <a class="btn btn-danger" id="btn-close-search" href="#">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        </span>
                                    </div>
                                    @endif
                                </form>
                                <span id="address_err" class="text-danger"></span>
                            </div>
                             <span style="float:right;text-align:center;" id="sid_err" class="text-danger"></span>
                        </div>
                          
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-5 offset-xl-0 offset-lg-0 offset-md-0 offset-sm-2 col-7 col-staic-small">
                            <nav class="header-menu float-lg-right">
                                <ul class="list-unstyled m-b-0">
                                    <li class="search-icon hidden-md-up" id="btn-show-search"><a href="javascript:void(0)"><img src="{{ url('/') }}/default/images/magnifying-glass.svg" alt="" width="24"></a></li>
                                     <?php if (session("user_role") != "" && session("user_role") == "Landscaper") { ?>
                                    <li class="nav-link-icon notification-icon-bell"><a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id='noti_bar_land'><img src="{{ asset("/default/images/bell.svg") }}">
                                        <?php if (isset($notification_status[0]->count) && $notification_status[0]->count > 0) { ?>  
                                            <span class="blink" id="blink_land"></span></a>
                                        <?php } else { ?>
                                        <span></span></a>
                                    <?php } ?>
                                        <div class="dropdown-menu header-notification-box p-0" aria-labelledby="dropdownMenuLink">
                                            <input type='hidden' id='user_id' value='<?php echo session('user_id') ?>'>
                                                <ul class="list-unstyled">
                                                    <?php
                                                    if (isset($requested_services)) {     
                                                        if (count($requested_services) > 0) {                                                        
                                                            foreach ($requested_services as $one_service) {
                                                                ?>
                                                                <li class="notification-item">
                                                                    <a href="{{ url('landscaper-dashboard/view-service-details').'/'.$one_service->id }}">
                                                                        <div class="avtar">
                                                                            <img src="{{ ($one_service->profile_image == NULL && !file_exists("/public/uploads/profile_picture/".$one_service->profile_image)) ? asset('/default/images/profile_pic.jpg') : url("/public/uploads/profile_picture/" . $one_service->profile_image) }}" alt="{{asset('/default/images/profile_pic.jpg')}}">
                                                                        </div>
                                                                        <div class="notification-text">
                                                                            <p class="m-0 light"><span class="text-dark regular">{{ $one_service->first_name.' '.$one_service->last_name }}</span>
                                                                                <?php echo $one_service->status_name ?>
                                                                                job for  {{ $one_service->service_name  }}&nbsp;({{ $one_service->order_no  }}) 
                                                                            </p>
                                                                            <span class="date"><?php echo date('D, M d Y h:i a', strtotime($one_service->show_time)); ?></span>
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                            <?php
                                                            }
                                                        }else{ ?>
                                                                <div class="alert alert-danger">
                                                                                No Records Found
                                                                            </div>    
                                                      <?php  }
                                                    }
                                                    ?>
                                                </ul>

                                                <?php } else { ?>
                                            <li class="nav-link-icon notification-icon-bell"><a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id='noti_bar'><img src="{{ asset("/default/images/bell.svg") }}">
                                                  <?php 
                                                  if(isset($notification_status[0]->count) && $notification_status[0]->count > 0){ ?>  
                                                    <span class="blink" id="blink_user"></span></a>
                                                  <?php  }else{  ?>
                                                      <span></span></a>
                                              <?php    } ?>
                                                    <div class="dropdown-menu header-notification-box p-0" aria-labelledby="dropdownMenuLink">
                                                <ul class="list-unstyled">
                                                    <?php
                                                    if (isset($services_pend)) {
                                                        if (count($services_pend) > 0) {
                                                            foreach ($services_pend as $one_service) {
                                                                ?>
                                                                <li class="notification-item">
                                                                    <a href="{{ url("/user/booking-history-payment") }}<?php echo '/' . $one_service['book_service']->order_no; ?>">
                                                                        <div class="avtar">
                                                                            <img src="{{ ($one_service['profile_image']->profile_image == NULL && !file_exists("/uploads/profile_picture/".$one_service['profile_image']->profile_image)) ? asset('/default/images/avatar-landscaper.jpg') : url("uploads/profile_picture/" . $one_service['profile_image']->profile_image) }}">
                                                                        </div>
                                                                        <div class="notification-text">
                                                                            <input type='hidden' id='user_id' value='<?php echo session('user_id') ?>'>
                                                                            <p class="m-0 light"><span class="text-dark regular">{{ $one_service["landscaper_name"] }}</span>
                                                                                <span class="text-success"><?php
                                                                                    $service_status = "<span class='text-info'>Service request sent</span>";
                                                                                    if ($one_service["book_service"]->status == 1)
                                                                                        $service_status = "<span class='text-warning'>Work in progress</span>";
                                                                                    if ($one_service["book_service"]->status == 2)
                                                                                        $service_status = "<span class='text-danger'>Payment Due</span>";
                                                                                    if ($one_service["book_service"]->status == 3)
                                                                                        $service_status = "<span class='text-success'>Success</span>";
                                                                                    ?><?php echo $service_status ?></span> job for <?php echo $one_service['name']->service_name; ?>
                                                                                    <br>
                                                                                     <span class="date"><?php echo date('D, M d Y h:i a', strtotime($one_service['show_time'])); ?></span>
                                                                            </p>
                                                                                   
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                            <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </ul>

<?php } ?>
                                        </div>
                                    </li>
                                        
                                    <?php if (session("user_role") != "" && session("user_role") == "Landscaper") { ?>
                                    <li class="nav-link-icon"><a href="{{ route('landscaper-message-firebase') }}"><img src="{{ asset("/default/images/envelope.svg") }}"></a><span class="blink"></span></li>
                                    <?php } if (session("user_role") != "" && session("user_role") == "Users") { ?>
                                    <li class="nav-link-icon"><a href="{{ route('user-message-firebase') }}"><img src="{{ asset("/default/images/envelope.svg") }}"></a><span class="blink"></span></li>
                                    <?php } ?>
                                    
                                    <li class="nav-user-login">
                                        <div class="dropdown">
                                            <a href="" class="dropdown-toggle user-mobile" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset("/default/images/avatar.svg") }}"></a>
                                            <a class="dropdown-toggle user-desk" href="" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <div class="avtar-container">
                                                    <div class="avtar">
                                                        <?php
                                                        
                                                        if (session('profile_id') == 2) {
                                                            $prof_pic = url('/') . '/default/images/userdefault.png';
                                                        if (session("prof_img") !== "") {
                                                            if (file_exists(public_path() . '/uploads/profile_picture/' . session("prof_img"))) {
                                                                $prof_pic = url('/public') . '/uploads/profile_picture/' . session("prof_img");
                                                            }
                                                        }  
                                                            if (session("social_source") != null) {
                                                                ?>
                                                                 <img src="{{ $prof_pic }}">
                                                                <!--<img src="{{ (session("prof_img") == NULL && !file_exists("/uploads/profile_picture/".session("prof_img"))) ? asset("/default/images/profile_pic.jpg") : session("prof_img") }}">-->                                                      
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <img src="{{ $prof_pic }}">
                                                                <?php
                                                            }
                                                        }
                                                        if (session('profile_id') == 3) {
                                                            $prof_pic = url('/') . '/default/images/avatar-landscaper.jpg';
                                                            if (session("prof_img") !== "") {
                                                                if (file_exists(public_path() . '/uploads/profile_picture/' . session("prof_img"))) {
                                                                    $prof_pic = url('/public') . '/uploads/profile_picture/' . session("prof_img");
                                                                }
                                                            }
                                                            if (session("social_source") != null) {
                                                                ?>
                                                                 <img src="{{ $prof_pic }}">
                                                                <!--<img src="{{ (session("prof_img") == NULL && !file_exists("/uploads/profile_picture/".session("prof_img"))) ? asset("/default/images/avatar-landscaper.jpg") : session("prof_img") }}">-->                                                      
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <img src="{{ $prof_pic }}">
                                                                <?php
                                                            }
                                                        }
                                                        ?>    
                                                        <span class="status"></span>
                                                    </div>
                                                </div>
                                                <div class="user-info"><p><b>Welcome! </b> <i class="fa fa-caret-down"></i><span>{{ session("user_name") }}</span></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <?php
                                                if (session('profile_id') == 2) {
                                                    ?>
                                                    <!-- <a class="dropdown-item" href="{{ url("/Home/Search-List/1/") }}">Book a Service</a> -->
                                                    <?php
                                                }
                                                ?>
                                                    <a class="dropdown-item" href="{{ url("/log-out") }}"><i class="fa fa-power-off"></i> Log-Out</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="menu-collapse-btn"><img src="{{ url('/') }}/default/images/menu-bars.svg" alt="" width="24"></li>
                                </ul>
                            </nav>
                        </div>
                        <div id="mySidenav" class="sidenav">
                            <a href="javascript:void(0)" class="close-sidebar">&times;</a>
                            <div class="sidenav-menu"> 
                                <a href="{{url("/")}}">Home</a>
                                <a href="{{url("/about-us-view")}}">About Us</a>
                                <a href="{{url("Home/search-list-location")}}">Explore Services</a>
                                <a href="">Seazoned Blog.</a>
                                <a href="{{ url("/privacyPolicy") }}">Privacy Policy</a>
                                <a href="">Press Room</a>
                                <a href="{{ url("/FAQ") }}" class="hidden-lg-up">FAQ</a>
                                <a href="" class="hidden-lg-up">Help</a>
                                <a href="" id = "contact-focus">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom-menu">
                <div class="container">
                    
                        <div class="header-bottom-menu-scroll">
                            <?php
                            if (session('user_role') == 'Landscaper' && session('profile_id') != '') {
                                ?>
                                <ul class="list-unstyled list-inline">
                                    <li class="list-inline-item <?php echo (isset($menu) && $menu == 'landscaperDashboard') ? 'active' : ''; ?>"><a href="{{ url("/landscaper-dashboard") }}"><i class="fas fa-tachometer-alt"></i> DASHBOARD</a></li>
                                    <li class="list-inline-item {{ (isset($menu) && $menu == 'landscaperProfile') ? 'active' : '' }}"><a href="{{ url("/landscaper-profile") }}"><i class="far fa-user" ></i> MY PROFILE</a></li>
                                    <li class="list-inline-item <?php echo (isset($menu) && ($menu == 'bookHistory' || $menu == 'viewServiceDetails')) ? 'active' : ''; ?>"><a href="{{ url("/booking-history") }}"><i class="fas fa-history"></i> JOB HISTORY</a></li>
                                    <li class="list-inline-item <?php echo (isset($menu) && $menu == 'landscaper-payment-info') ? 'active' : ''; ?>"><a href="{{ url("/landscaper/payment-info") }}"><i class="fas fa-dollar-sign"></i> PAYMENT INFO</a></li>
                                </ul>
                                <?php
                            } elseif (session('user_role') == 'Users') {
                                ?>
                                <ul class="list-unstyled list-inline">
                                    <li class="list-inline-item <?php echo (isset($menu) && $menu == 'userProfile') ? 'active' : ''; ?>"><a href="{{ url("/user/my-profile") }}"><i class="far fa-user" ></i> MY PROFILE</a></li>
                                    <li class="list-inline-item <?php echo (isset($menu) && ($menu == 'userBookHistory' || $menu == 'userBookingPaymentDetails')) ? 'active' : ''; ?>"><a href="{{ url("/user/booking-history") }}"><i class="fas fa-history"></i> BOOKING HISTORY</a></li>
                                    <li class="list-inline-item <?php echo (isset($menu) && $menu == 'userFavorite') ? 'active' : ''; ?>"><a href="{{ url("/user/favorite-history") }}"><i class="far fa-heart"></i> FAVORITE</a></li>
                                    <li class="list-inline-item <?php echo (isset($menu) && $menu == 'user-payment-info') ? 'active' : ''; ?>"><a href="{{ url("/user/payment-info") }}"><i class="fas fa-credit-card"></i> PAYMENT INFO</a></li>
                                </ul>
<?php } ?>
                        </div>
                    
                </div>
            </div>
        </header>
        @endif

        @show

        @yield('content')

        @section("footer")

        <footer class="footer">
            <div class="top-footer p-y-40">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-3 footer-about">
                            <h5 class="m-b-20">About Seazoned</h5>
                            <p class="m-0">The Seazoned app brings you on-demand yard and home solutions at the click of a
                                button. Choose from a list of services offered by Seazoned pros at a price that works for
                                you.</p>
                            <div class="footer-social clearfix m-t-20">
                                <ul class="list-unstyled list-inline">
                                    <li class="list-inline-item"><a href=""><i class="fab fa-facebook-f"></i></a></li>
                                    <li class="list-inline-item"><a href=""><i class="fab fa-google-plus-g"></i></a></li>
                                    <li class="list-inline-item"><a href=""><i class="fab fa-youtube"></i></a></li>
                                    <li class="list-inline-item"><a href=""><i class="fab fa-twitter"></i></a></li>
                                    <li class="list-inline-item"><a href=""><i class="fab fa-instagram"></i></a></li>
                                </ul>
                            </div>
                            <div class="footer-app-btn m-t-30  m-l-0">
                                
                                        <a href="" class="app-btn-footer"><img src="{{ url('/') }}/default/images/app-store-btn.png" alt=""></a>
                                  
                                        <a href="" class="app-btn-footer"><img src="{{ url('/') }}/default/images/google-play-btn.png" alt=""></a>
                                  
                            </div>
                        </div>
                        <div class="col-12  col-md-5 quick-links">
                            <h5>Popular links</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <?php if (session('profile_id') == '') { ?>
                                            <li><a href="{{ url("/user-login") }}">Customer Login or Register</a></li>
                                        <?php } else { ?>
                                            <li><a href="">Customer Login or Register</a></li>
                                        <?php } ?>      
                                        <?php
                                        if (session('profile_id') != '') {
                                            if (session('user_role') == 'Users') {
                                                ?>
                                                <li><a href="{{ url("/user/booking-history") }}">Order Service</a></li>
                                                <?php
                                            } elseif (session('user_role') == 'Landscaper') {
                                                ?>   
                                                <li><a href="{{ url("/booking-history") }}">Order Service</a></li>
                                            <?php }
                                        } else { ?>  
                                            <li><a href="{{ url("/") }}">Order Service</a></li>
<?php } ?>
                                        <li><a href="{{ url("/FAQ") }}">Frequently Asked Questions</a></li>
                                        <li><a href="">Our Blog</a></li>
                                        <li><a href="{{ url("/privacyPolicy") }}">Privacy Policy</a></li>
                                    </ul>
                                </div>
                                <div class="col-12 col-md-6">
                                    <ul class="list-unstyled">
                                        <?php if (session('profile_id') == '') { ?>
                                            <li><a href="{{ url("/user-login") }}">Provider Login/Registration</a></li>
                                        <?php } else { ?>
                                            <li><a href="">Provider Login/Registration</a></li>
<?php } ?>
                                        <li><a href="{{ url("/why-work-with-us-view") }}">Why Work with Us</a></li>
                                        <li><a href="{{ url("/lawn-mowing-tips-view") }}">Lawn Mowing Tips</a></li>
                                        <li><a href="{{ url("/terms-services-view") }}">Terms of Services</a></li>
                                        <li><a href="">Customer Support</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-12  col-md-4">
                            <h5 class="m-b-15">Contact Us Now</h5>
                            <form class="footer-contact-form" action="{{url("/add-contact")}}">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Your Full Name" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Your email address" required>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" id="textArea" name="textArea" rows="5"
                                              placeholder="Write here..." required ></textarea>
                                </div>
                                <button class="btn btn-warning p-10 noradius" type="submit">SEND MESSAGE</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottom-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md">
                            <p class="m-0 text-center p-y-15">&copy Copyright 2018 Seazoned</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        @show
    </body>

</html>
<script>
    
    $('#noti_bar').click(function () {
        var user_id = $("#user_id").val();
        $("#blink_user").removeClass();
        $.ajax({
            url:'{{ url("updateNotification") }}',
            data:{"user_id":user_id},
            method:'POST',
            success:function(d)
            {
            }
        });
    });
    
    $('#noti_bar_land').click(function () {
        var user_id = $("#user_id").val();
        $("#blink_land").removeClass();
        $.ajax({
            url:'{{ url("updateNotificationLandscaper") }}',
            data:{"user_id":user_id},
            method:'POST',
            success:function(d)
            {
            }
        });
     
    });
    
    
    $('#contact-focus').click(function () {
        $('html').animate({
            scrollTop: $("#name").offset().top},
                'slow');
//       $("[id*='nav-backdrop in']").remove(); 
        $('#mySidenav').hide();
        $('#name').focus();
        return false;
    });
    $('#button').click(function () {
        $('#mySidenav').show();
    });
</script>
<script type="text/javascript">

//initializing datepicker
    $('.form_date').datetimepicker({
        language: 'en',
        weekStart: 1,
        autoclose: 1,
        todayHighlight: 0,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

//disabling past date from datepicker
    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

    $('.form_date1').datetimepicker({
        language: 'en',
        weekStart: 1,
        autoclose: 1,
        todayHighlight: 0,
        startView: 2,
        minView: 2,
        forceParse: 0,
        startDate: today
    });
</script>
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

            /*$("body").mCustomScrollbar({
                theme: "minimal"
            });*/

        });
    })(jQuery);

    $(".scroll-pane").mCustomScrollbar({
        mouseWheelPixels: 150 //change this to a value, that fits your needs
    });
</script>
<script type="text/javascript">
        function initAutocompleteSearch(id) {
            // Create the autocomplete object, restricting the search to geographical
            // location types.
            autocomplete = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */(document.getElementById(id)),
                {types: ['geocode']});
        }
    </script>
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1XtsegzOIIBaqGL0QugHFIaL2pvp8f8A&libraries=places" async defer></script>
 <script>
        function checkAddress()

	{
          var location = document.getElementById("loc_txt").value;
          
          if(location == ''){
              
               $("#address_err").html("Please enter a valid address");
               return false;
          }else{
               $("#address_err").html("");
          }
          if(document.getElementById("service_txt")){
            var sid = document.getElementById("service_txt").value;
            if( typeof sid !== null){
                 if(sid == 0) {
                    $("#sid_err").html("Please select a service");
                   return false;
            }else{
                 $("#sid_err").html("");
            }
          }
      }
	  return true;
        }
  </script>      