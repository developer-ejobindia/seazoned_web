@extends("layouts.dashboardlayout")
@section('content')

<section class="main-content dashboard p-y-30">
    <?php // echo "<pre>";print_r($landscapper_info['id']); echo "</pre>";die();   ?>
    <span id="k" class="alert alert-success"></span>
    <input type="hidden" value="<?php print_r($landscapper_info['id']); ?>" id="land_id">
    <div class="container">
        <form class="booking-freq" action="{{ url("Home/Service/Check-Out") }}" method="post" enctype="multipart/form-data">
            @foreach($order_data as $key => $data)
            <input type="hidden" name="{{ $key }}" value="{{ $data }}" />
            @endforeach
            <input type="hidden" name="landscaper_id" value="{{ $landscapper_info->id }}" />
            <h3 class="m-b-15 page-heading">Select Frequency</h3>

            <div class="frequency-type m-b-30">
                <div class="row">
                    <?php
                    if ($discount_info[0]->discount_price == 0 &&
                            $discount_info[1]->discount_price == 0 &&
                            $discount_info[2]->discount_price == 0) {
                        ?>

                        <div class="col-md-3">
                            <label for="frequency-4" class="radio-frequency-box">
                                <input id="frequency-4" name="service_price_id" type="radio" value="{{ $discount_info[3]->id }}" checked>
                                <span class="total-text-ser">
                                    <i class="fa fa-check float-right"></i>
                                    <span class="type-of-service regular">Single services</span>
                                    <span class="time-of-service medium">Just Once</span>
                                    <span class="price-of-service single-service medium">$ {{ $order_data["service_price"] - $discount_info[3]->discount_price }}</span>
                                </span>
                            </label>
                        </div>
                    <?php } else { ?>
                        <div class="col-md-3">
                            <label for="frequency-1" class="radio-frequency-box">
                                <input id="frequency-1" name="service_price_id" type="radio" value="{{ $discount_info[0]->id }}">
                                <span class="total-text-ser">
                                    <i class="fa fa-check float-right"></i>
                                    <span class="type-of-service regular">Recurring services</span>
                                    <span class="time-of-service medium">Every 7 days</span>
                                    <span class="price-of-service medium">$ {{ $order_data["service_price"] - $discount_info[0]->discount_price }}</span>
                                </span>
                            </label>
                        </div>
                        <div class="col-md-3">
                            <label for="frequency-2" class="radio-frequency-box">
                                <input id="frequency-2" name="service_price_id" type="radio" value="{{ $discount_info[1]->id }}">
                                <span class="total-text-ser">
                                    <i class="fa fa-check float-right"></i>
                                    <span class="type-of-service regular">Recurring services</span>
                                    <span class="time-of-service medium">Every 10 days</span>
                                    <span class="price-of-service medium">$ {{ $order_data["service_price"] - $discount_info[1]->discount_price }}</span>
                                </span>
                            </label>
                        </div>
                        <div class="col-md-3">
                            <label for="frequency-3" class="radio-frequency-box">
                                <input id="frequency-3" name="service_price_id" type="radio" value="{{ $discount_info[2]->id }}">
                                <span class="total-text-ser">
                                    <i class="fa fa-check float-right"></i>
                                    <span class="type-of-service regular">Recurring services</span>
                                    <span class="time-of-service medium">Every 14 days</span>
                                    <span class="price-of-service medium">$ {{ $order_data["service_price"] - $discount_info[2]->discount_price }}</span>
                                </span>
                            </label>
                        </div>
                        <div class="col-md-3">
                            <label for="frequency-4" class="radio-frequency-box">
                                <input id="frequency-4" name="service_price_id" type="radio" value="{{ $discount_info[3]->id }}" checked>
                                <span class="total-text-ser">
                                    <i class="fa fa-check float-right"></i>
                                    <span class="type-of-service regular">Single services</span>
                                    <span class="time-of-service medium">Just Once</span>
                                    <span class="price-of-service single-service medium">$ {{ $order_data["service_price"] - $discount_info[3]->discount_price }}</span>
                                </span>
                            </label>
                        </div>
                    <?php } ?>
                </div><!-- /.row -->
            </div><!-- /.frequency-type -->
            <div class="row custom-form">
                <div class="col-md-9">
                    <div class="service-dtls m-b-50">
                        <div class="card">
                            <div class="card-header card-header-success">
                                <h4 class="m-0 regular">Service Details</h4>
                            </div>
                            <div class="card-block user-profile-details">
                                <div class="row custom-form">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="medium">Select Service Date<span class="m-l-5">*</span> </label>
                                            <div class="input-group date form_date1 dob" data-date-format="dd MM yyyy" data-link-field="dtp_input1">
                                                <input type="text" class="form-control select-date" placeholder="Select Date" name="service_date" required>
                                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group timepicker-input">
                                            <label for="time" class="medium">Preferred Time<span class="m-l-5">*</span></label>
                                            <div class="input-group timepicker">
                                                <input class="form-control" type="text" id="dtp_end_1" name="service_time">
                                                <span class="input-group-addon"><i class="far fa-clock"></i></span>
                                            </div>
                                            <p class="m-t-15 m-b-0"><i class="fas fa-info-circle m-r-5"></i>Preferred time is only a preference as service providers may
                                                be working other Seazoned jobs at that time.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="image" class="medium">Upload Project Image (Optional)</label>
                                            <div class="input-group">
                                                <input type="file" id="image" name="property_image[]" multiple/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="text" class="medium">Anything more you would like your provider to
                                                know?</label>
                                            <textarea class="form-control card-no-radius" id="text" rows="4" name="additional_note"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="service-add">
                        <div class="card m-t-30 card-no-radius">
                            <div class="card-header card-header-success">
                                <h4 class="m-0 regular">My Saved Card</h4>

                                <a href="{{url('/user/payment-info')}}" target="_blank" class="float-right medium add-icon header-link" >ADD NEW CREDIT/DEBIT CARD</a>

                            </div>
                            <?php
                            if ($payment_dtls != '') {
                                foreach ($payment_dtls as $payment) {
                                    ?>
                                    <div class="card-block">
                                        <div class="form-check">
                                            <label class="form-check-label d-block">                                    
                                                <span class="address-block clearfix d-block w-100">
                                                    <div class="address-left float-left">
                                                        <p class="m-b-10 bold">{{$payment->card_brand}}</p>
                                                        <h5 class="regular m-b-10">{{$paymentacc_obj->showLastFourDigit($payment->card_no)}}</h5>
                                                        <p class="m-b-10 light">Expiry Date: {{$payment->month}}/{{$payment->year}}</p>
                                                    </div>                                        
                                                </span>
                                                <div class="clearfix"></div>
                                                <label class="float-right">                                            
                                                    <input type="radio" name="is_primary" id="is-primary-{{$payment->id}}" name="is_primary[{{$payment->id}}]" <?php echo ($payment->is_primary == 1) ? 'checked' : '' ?> onclick="save_primary_card({{$payment->id}});" />
                                                    Set as Primary
                                                </label>
                                                <div class="clearfix"></div>
                                                <hr>
                                            </label>
                                        </div>                                        
                                    </div>
                                    <?php
                                }
                            }
                            ?> 
                        </div>
                        <div class="card m-t-30 card-no-radius">
                            <div class="card-header card-header-success">
                                <h4 class="m-0 regular">My Address Book</h4>
                                <a href="" class="float-right medium add-icon header-link" data-toggle="modal" data-target="#add_address">ADD NEW ADDRESS</a>
                            </div>
                            <div class="card-block">
                                @forelse($addresses as $address)

                                <div class="form-check">
                                    <label class="form-check-label d-block">
                                        <input type="radio" class="form-check-input" name="address_book_id" value="{{ $address->id }}" <?php ($address->primary_address == 1) ? "checked" : "" ?>  onclick="checkdistance(this,<?php echo $address->id; ?>)" id="address_book_id_value" >
                                        <div class="address-block clearfix d-block w-100">
                                            <div class="address-left float-left">
                                                <h5 class="regular m-b-10">{{ $address->name }}</h5>
                                                <p class="m-b-10 light">{{ $address->address }}, {{ $address->city }}, {{ $address->state }}</p>
                                                <p class="m-b-10 light">{{ $address->contact_number }}</p>
                                                <p class="m-b-10 light">E-Mail: {{ $address->email_address }}</p>   <div class="text text-danger" id="distance_chk_err_<?php echo $address->id; ?>"></div>
                                            </div>
                                            <a href="" class="float-right medium" data-toggle="modal" data-target="#edit_address" onClick="return editAddress({{ $address->id }},{{"' $address->name '"}},{{"' $address->address '"}},{{"' $address->city '"}},{{"' $address->country '"}},{{"' $address->contact_number '"}},{{"' $address->email_address '"}});">EDIT</a>
                                        </div>
                                    </label>
                                </div>
                                @empty
                                <p>No Address Found</p>
                                @endforelse
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn-secondary noradius btn float-left">Cancel</button>
                                <?php
                                if ($count_acc[0]->count_data > 0) {
                                    ?>
                                    <button type="submit" class="btn-success noradius btn float-right" id="continue_btn" disabled="disabled">Continue <i class="fa fa-long-arrow-alt-right"></i></button>
                                    <?php
                                } else {
                                    ?>
                                    <button type="button" class="btn-success noradius btn float-right" id="continue_btn2" disabled="disabled">Continue <i class="fa fa-long-arrow-alt-right"></i></button>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>                    

                </div>

                <?php // echo session("landscaper_profile_picture")   ?>
                <div class="col-md-3">
                    <div class="card side-menu card-no-radius">
                        <div class="card-block p-t-40 p-b-30">
                            <div class="avtar">
                                <?php
                                $prof_pic = url('/') . '/default/images/avatar-landscaper.jpg';
                                if (session("landscaper_profile_picture") != "") {
                                    if (file_exists(public_path() . '/uploads/profile_picture/' . session("landscaper_profile_picture"))) {
                                        $prof_pic = url('/public') . '/uploads/profile_picture/' . session("landscaper_profile_picture");
                                    }
                                }
                                ?>
                                <img src="{{ $prof_pic }}">
                            </div>
                            <h4 class="profile-name text-center m-0 m-t-20 medium">{{ $landscapper_info->name }}</h4>
                            <p class="text-center m-t-20 light">Service : {{ $landscapper_info->service_name }}</p>
                        </div>
                    </div><!-- /.card -->

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
                </div><!-- /.col-md-3 -->
            </div><!-- /.row.custom-form -->
        </form>
    </div>
</section>

<!--  Add Address Modal  -->

<div class="modal fade" id="add_address" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Add New Address</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form class="m-t-30" action="{{ url("/user/add-address") }}" method="post" enctype="multipart/form-data">
                <div class="modal-body">        
                    <div class="form-group">
                        <label for="name">Contact Name</label>
                        <input type="text" class="form-control" name="addressbook_name" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Street Address</label>
                        <input type="text" class="form-control" name="addressbook_address" value="" required>
                    </div>
                    <div class="form-group">    
                        <label for="city">City</label>
                        <input type="city" class="form-control" name="addressbook_city" value="" required>
                    </div>
                    <!--                    <div class="form-group">
                                            <label for="country">Country</label>
                                            <select class="form-control" name="addressbook_country">
                                                @forelse($countrys as $country)
                                                <option value="{{ $country["id"] }}">{{ $country["country_name"] }}</option>
                                                @empty
                                                <option value="0">No Country Found</option>
                                                @endforelse
                                            </select>
                                            @if($errors->has('country_name'))
                                            <span class="error">{!! $errors->first('country_name') !!}</span>
                                            @endif 
                                        </div>-->
                    <div class="form-group">
                        <label for="addressbook_contact">Contact Number</label>
                        <input type="text" class="form-control" name="addressbook_contact" value="">  
                    </div>
                    <div class="form-group">
                        <label for="addressbook_email">Email Address</label>
                        <input type="text" class="form-control" name="addressbook_email" value="">                   
                        <input type="hidden" name="user_id" value="{{ session('user_id') }}">    
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save </button>
                </div> 
            </form> 
        </div>
    </div>
</div>
<!--   end modal  -->

<!--  Edit Address Modal  -->

<div class="modal fade" id="edit_address" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit Address</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form class="m-t-30" action="{{ url("/user/edit-address") }}" method="post" enctype="multipart/form-data">                            

                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Contact Name</label>
                        <input type="text" class="form-control" name="editaddressbook_name" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Street Address</label>
                        <input type="text" class="form-control" name="editaddressbook_address" value="" required>
                    </div>
                    <div class="form-group">    
                        <label for="city">City</label>
                        <input type="city" class="form-control" name="editaddressbook_city" value="" required>
                    </div>
                    <!--                    <div class="form-group">    
                                            <label for="country">Country</label>
                                            <select class="form-control" name="editaddressbook_country">
                                                @forelse($countrys as $country)
                                                <option value="{{ $country["id"] }}">{{ $country["country_name"] }}</option>
                                                @empty
                                                <option value="0">No Country Found</option>
                                                @endforelse
                                            </select>
                                            @if($errors->has('country_name'))
                                            <span class="error">{!! $errors->first('country_name') !!}</span>
                                            @endif 
                                        </div>-->
                    <div class="form-group">
                        <label for="addressbook_contact">Contact Number</label>
                        <input type="text" class="form-control" name="editaddressbook_contact" value="">  
                    </div>
                    <div class="form-group">
                        <label for="addressbook_email">Email Address</label>
                        <input type="text" class="form-control" name="editaddressbook_email" value="">                   

                        <input type="hidden" name="address_id" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save </button>
                </div> 
            </form>  
        </div>
    </div>
</div>
<!--   end modal  -->

<script>
            function editAddress(address_id, address_name, address, city, country, contact_number, email_address)
            {
            $('input[name="editaddressbook_name"]').val(address_name);
                    $('input[name="editaddressbook_address"]').val(address);
                    $('input[name="editaddressbook_city"]').val(city);
                    $('input[name="editaddressbook_country"]').val(country);
                    $('input[name="editaddressbook_contact"]').val(contact_number);
                    $('input[name="editaddressbook_email"]').val(email_address);
                    $('input[name="address_id"]').val(address_id);
            }

    function checkdistance(radio, address_id)
    {
    var address = radio.value;
            var lanscaper_id = $("#land_id").val();
            $.ajax({
            url:'{{ url("Home/DistanceCheck") }}',
                    method:"POST",
                    data:{
                    "landscaper_id":lanscaper_id,
                            "address_book_id":address
                    },
                    success:function(d)
                    {

                    if (d == 'n')
                    {
                    var msg = "Address is too far, out of range from landscaper. Please select another address.";
                            $("#distance_chk_err_" + address_id).html(msg);
                            $('#continue_btn').disabled = true;
                            $('#continue_btn2').disabled = true;
                    }
                    else
                    {
                    $("#distance_chk_err_" + address_id).html();
                            $('#continue_btn').removeAttr('disabled');
                            $('#continue_btn2').removeAttr('disabled');
                    }
                    }
            });
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

    $(document).on('click', '#continue_btn2', function(){
    alert('You must first add a payment method in order to complete your transaction. Please click on "add new credit/debit card" to do so. You may also visit the "payment info" tab at the top of the screen to add and manage your payment methods.');
    });

</script>

@endsection