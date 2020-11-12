@extends("layouts.dashboardlayout")
@section('content')

    <form id="confirm-booking-form" action="{{ url("Home/Service/Check-Out-Final") }}" method="post">
        <section class="main-content booking-step p-40">
            <div class="container">
                <h3 class="m-b-15 page-heading">Review Your Service Details</h3>
                <div class="row">
                    <div class="col-md-9">
                        <div class="card card-no-radius custom-card service-review">
                            <div class="card-header p-30 p-t-20 p-b-20">
                                <div class="media">
                                    <div class="avtar">
                                        <?php
                                        $prof_pic = url('/') . '/default/images/avatar-landscaper.jpg';
                                        if(session("landscaper_profile_picture")!=""){
                                       if (file_exists(public_path() . '/uploads/profile_picture/' .session("landscaper_profile_picture"))) {
                                            $prof_pic = url('/public') . '/uploads/profile_picture/' .session("landscaper_profile_picture");
                                        }
                                    }
                                       ?>
                                        <img class="d-flex align-self-start" src="{{ $prof_pic }}" alt="">
                                    </div>
                                    <div class="media-body p-l-15">
                                        <p class="m-b-0 light">Service Provider:</p>
                                        <h5 class="m-b-0 medium">{{ $landscapper_info->name }}</h5>
                                    </div>
                                    {{--<a href="" class="m-t-10 text-success">Edit</a>--}}
                                </div>
                            </div>
                            <div class="card-block p-0">
                                <div class="row">
                                    <div class="col-md">
                                        <div class="media">
                                            <img class="d-flex align-self-start m-r-15 m-30" src="{{ (!isset(session("property_image_new")[0])) ? asset('/default/images/gallery-1.jpg') : asset("uploads/temp/" . session("property_image_new")[0]) }}" alt="" style="height: 66px;width: 116px;">
                                            <div class="media-body m-t-30">
                                                <h4 class="m-b-15 light text-success">{{ $service_name }}</h4>
                                                <p class="m-b-10"><b class="regular">Date:</b> {{ date('D, M d Y',strtotime($service_date)) }}</p>
                                                <p class="m-b-10"><b class="regular">Time:</b> {{ date("h:i a",strtotime($service_time)) }}</p>
                                                {{--<p class="m-b-10"><b class="regular">Lawn Size:</b> 1.50 - 2.00 acres</p>--}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md border-left">
                                        <address class="m-t-30">
                                            <h4 class="m-0 m-b-10 regular">Service Address</h4>
                                            <h5 class="m-0 m-b-10 medium">{{ $address->name }}</h5>
                                            <p class="m-b-10 m-0 light">{{ $address->address . ", " . $address->city . ", " . $address->state }}</p>
                                            <p class="m-b-10 m-0 light"><span>Phone:</span> {{ $address->contact_number }}</p>
                                            <p class="m-b-10 m-0 light"><span>E-Mail:</span> {{ $address->email_address }}</p>
                                        </address>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer p-30 service-review-description">
                                <h5 class="m-b-5 regular">Description</h5>
                                <p class="m-b-0 light">{{ session("order_info")["additional_note"] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-no-radius price-box">
                            <div class="card-block">
                                <h5 class="card-title m-b-0 regular">Price Detail</h5>
                            </div>
                            <ul class="list-group list-group-flush">
<!--                                <li class="list-group-item d-block"><p class="m-b-0 regular">
                                       
                               Service booked
                                        <span class="inline-block float-right">$ {{ session("order_info")["service_booked_price"] }}</span></p></li>-->
                                <!--<li class="list-group-item d-block"><p class="m-b-0 text-dark regular">Tax<span class="inline-block float-right">20%</span></p></li>-->
                                <li class="list-group-item d-block total-price-bg"><p class="m-b-0 text-dark regular">Service Total<span class="inline-block float-right">$ {{ session("order_info")["service_price"] }}  </span></p></li>
                            </ul>
                        </div>
                        
                        
                        <button type="submit" class="btn btn-success m-t-20 m-r-5 noradius">Confirm Booking</button>                        
                        
                        <a href="{{route('user-booking-history')}}" class="btn btn-secondary m-t-20 noradius">cancel</a>
                        <p class="text-justify m-t-10"><b>Your card will not be charged {{ session("order_info")["service_price"] }}$ until after the provider accepts the booking. Once this happens, it will be placed in an escrow account and will not be paid to the provider until after the work has been completed. Once completed, you will be able to release payment to the provider.</b></p>
                        <p class="text-justify text-danger">In the chance your payment method declines, you will have to manually complete the payment process once the provider accepts your service request. Otherwise your service will not be started until payment is successful.</p>
                    </div>
                </div>
            </div>
        </section>
    </form>
    
<script>

</script>
@endsection