@extends('layouts.'.$layout)
@section('content')

    <section class="banner">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-md-7 align-self-center">
                    <div class="welcome-text">
                        <input type="hidden" id="timezone" name="timezone" value="">
                        <h2>Have a healthy lawn and home all with the click of a button. High quality work without the
                            high prices. Oh yeah, did we mention our Incredible Service? #AWESOME!!!</h2>
                        <h1>How do I get started?</h1>
                        <a href="{{url("Home/search-list-location")}}" class="btn btn-danger">Explore now</a>
                    </div>
                </div>
                <div class="col-md-4 offset-md-1">
                    @if(session("user_id") == "")
                        <div class="index-register-form-container">
                            <div class="p-20">
                                <ul class="list-unstyled">
                                    <li class="register-form-btn d-table w-100">
                                        <div class="d-table-cell p-l-10 align-middle">
                                            <h3 class="m-0">Find a service</h3>
                                            <a href="{{ url('/user-register') }}" class="d-block m-t-15">Register<i class="fas fa-long-arrow-alt-right m-l-10"></i></a>
                                        </div>
                                        <div class="register-form-image d-table-cell float-right">
                                            <img src="{{ asset("default/images/find-service.png") }}" alt="Find a Service">
                                        </div>
                                    </li>
                                    <li class="reg-devider">
                                        <span class="reg-devider-text">OR</span>
                                    </li>
                                    <li class="register-form-btn d-table">
                                        <div class="d-table-cell align-middle">
                                            <h3 class="m-0">Become a Seazoned Provider</h3>
                                        </div>
                                        <div class="register-form-image p-l-10 d-table-cell">
                                            <img src="{{ asset("default/images/seazoned-provider.png") }}" alt="Find a Service">
                                        </div>
                                    </li>
                                </ul>
                                <div class="index-register-form p-t-10">
                                    <form action="{{ url("/add-landscapper/") }}" method="post" onsubmit="return isDate()" enctype="multipart/form-data"> 
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="first_name" value="{{ Input::old("first_name") }}" required>
                                            @if($errors->has('first_name'))
                                                <span class="error">{!! $errors->first('first_name') !!}</span>
                                            @endif
                                            <span class="highlight"></span>
                                            <label for="f-name">First Name</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="last_name" value="{{ Input::old("last_name") }}" required>
                                            @if($errors->has('last_name'))
                                                <span class="error">{!! $errors->first('last_name') !!}</span>
                                            @endif
                                            <span class="highlight"></span>
                                            <label for="l-name">Last Name</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="date_slot" name="dob" value="{{ Input::old("dob") }}"  required>
                                            @if($errors->has('dob'))
                                                <span class="error">{!! $errors->first('dob') !!}</span>
                                            @endif
                                            <span class="highlight"></span>
                                            <span id="error1" class="text-danger"></span>
                                            <label for="dob">Date Of Birth (eg : 06/28/1990)</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="street" value="{{ Input::old("street") }}" id="street_address" onfocus="initAutocompleteRegistration(this.id)" placeholder="" required>
                                            @if($errors->has('street'))
                                                <span class="error">{!! $errors->first('street') !!}</span>
                                            @endif
                                            <span class="highlight"></span>
                                            <span id="address_err" class="text-danger"></span>
                                            <label for="address">Business Address</label>
                                            <input type="hidden" name="state" id="administrative_area_level_1" value="{{ Input::old("street") }}" >
                                            <input type="hidden" name="city" id="locality" value="{{ Input::old("street") }}"  >
                                            <input type="hidden" name="country" id="country" value="{{ Input::old("street") }}" >
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="email" value="{{ Input::old("email") }}" required>
                                            @if($errors->has('email'))
                                                <span class="error">{!! $errors->first('email') !!}</span>
                                            @endif
                                            <span class="highlight"></span>
                                            <label for="email">Email</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="tel" value="{{ Input::old("tel") }}" id="phone" required>
                                            @if($errors->has('tel'))
                                                <span class="error">{!! $errors->first('tel') !!}</span>
                                            @endif
                                            <span class="highlight"></span>
                                            <span id="phone_err" class="text-danger"></span>
                                            <label for="phone">Cell Phone</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="password" value="{{ Input::old("password") }}" required>
                                            @if($errors->has('password'))
                                                <span class="error">{!! $errors->first('password') !!}</span>
                                            @endif
                                            <span class="highlight"></span>
                                            <label for="phone">Password</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="password_confirmation" value="{{ Input::old("password_confirmation") }}" required>
                                            @if($errors->has('password_confirmation'))
                                                <span class="error">{!! $errors->first('password_confirmation') !!}</span>
                                            @endif
                                            <span class="highlight"></span>
                                            <label for="phone">Confirm Password</label>
                                        </div>
                                        <p class="text-white f-s-12">To ensure customer safety, we require a background check. Please enter the following:</p>
                                        
                                        <div class="form-group">
                                            <label for="drivers_license" class="driver-license-label">Drivers License</label>
                                            <input type="file" name="drivers_license" required accept="image/*" id="drivers_license">
                                            @if($errors->has('drivers_license'))
                                                <span class="error">{!! $errors->first('drivers_license') !!}</span>
                                            @endif
                                            <span class="highlight"></span>
                                        </div>

                                        <div class="form-group">
                                            <input type="number" class="form-control" name="ssn_no" value="{{ Input::old("ssn_no") }}" required id="ssn_no" min="100000000" max="999999999">
                                            @if($errors->has('ssn_no'))
                                                <span class="error">{!! $errors->first('ssn_no') !!}</span>
                                            @endif
                                            <span class="highlight"></span>
                                            <label for="ssn_no">Social Security Number</label>
                                        </div>
                                        
                                        <input type="hidden" value="3" name="user_type">
                                        <button type="submit" class="btn btn-block btn-continue ">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section class="service p-b-0">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading text-center heading-with-border">
                        <h3 class="m-0">Our Services</h3>
                        <p class="m-0 m-t-20">A beautiful lawn and home doesn’t happen all by itself. Select one of our
                            stellar services and let us do the rest</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="service-box-group p-b-0">
            <?php
                $counter1 = 1;
                $counter2 = 1;
            ?>
            @forelse($services as $service)
            <?php
                if($counter1 == 1 && $counter2 == 1){
                    echo '<div class="container">';
                    echo '<div class="row no-gutters">';
                }
                if($counter1 == 5 && $counter2 == 1){
                    echo '</div>'; //.row
                    echo '</div>'; //.container
                    echo '<hr class="custom-border m-0">';
                    echo '<div class="container">';
                    echo '<div class="row no-gutters">';
                }
            ?>                
                <div class="col-md-3 <?php echo ($counter1!=4)?'service-border-right':'' ?>">
                    <a class="service-box text-center" href="javascript:void(0)">
                        <div class="service-img">
                            <img src="{{ url("/default/images/" . $service->logo_name) }}">
                        </div>
                        <h4 class="d-block m-0 m-t-20">{{ $service->service_name }} <i class="fa fa-caret-right"></i></h4>
                    </a>
                </div>
            <?php
                if($counter2 == 4){ $counter2 = 0; }
                $counter1++;
                $counter2++;
            ?>
            @empty
                <p>No Services Found</p>
            @endforelse
            <?php
            echo '</div>'; //.row
            echo '</div>'; //.container
            ?>
        </div>
    </section>
    <section class="how-it-works">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading text-center heading-with-border">
                        <h3 class="m-0">How it Works</h3>
                            <!--<p class="m-0 m-t-20">The Jackson5 had one thing right. Booking is as easy as 1, 2, 3</p>-->
                            <p class="m-0 m-t-20">Booking is as easy as 1, 2, 3</p>
                    </div>
                </div>
            </div>
            <div class="row no-gutters text-center how-work-step-container">
                <div class="col-md-3">
                    <div class="how-work-step">
                        <div class="how-work-step-image">
                            <img src="{{ url('/') }}/default/images/step-one.svg">
                            <span>1</span>
                        </div>
                        <h5 class="m-0 m-t-20">1. Get Instant pricing</h5>
                        <p class="m-0 m-t-15">Choose a service and Answer a few questions</p>
                    </div>
                </div>
                <div class="col">
                    <div class="step-connection"></div>
                </div>
                <div class="col-md-3">
                    <div class="how-work-step">
                        <div class="how-work-step-image">
                            <img src="{{ url('/') }}/default/images/step-two.svg">
                            <span>2</span>
                        </div>
                        <h5 class="m-0 m-t-20">2. Select a Service Date</h5>
                        <p class="m-0 m-t-15">Choose a date that works for you</p>
                    </div>
                </div>
                <div class="col">
                    <div class="step-connection"></div>
                </div>
                <div class="col-md-3">
                    <div class="how-work-step">
                        <div class="how-work-step-image">
                            <img src="{{ url('/') }}/default/images/step-three.svg">
                            <span>3</span>
                        </div>
                        <h5 class="m-0 m-t-20">3. Our Seazoned Pros Do The work</h5>
                        <p class="m-0 m-t-15">Relax....we got you covered. We’ll even send you a photo of the finished
                            job</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <hr class="custom-border">
    <section class="why-seazoned">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading text-left">
                        <h3 class="m-0 p-b-25">Why Seazoned?</h3>
                    </div>
                </div>
            </div>
            <div class="row row-eq-height">
                <div class="col-md-4">
                    <div class="why-seazoned-icon d-table-cell">
                        <img src="{{ url('/') }}/default/images/clock.svg">
                    </div>
                    <div class="why-seazoned-content d-table-cell p-l-25 align-top">
                        <h4 class="m-0 m-b-10">Schedule with Ease…. </h4>
                        <p class="m-0 m-b-50">Our sweet process allows you to schedule a service in less than 5 minutes.
                            Answering a few questions sets you on your way to happiness</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="why-seazoned-icon d-table-cell">
                        <img src="{{ url('/') }}/default/images/settings.svg">
                    </div>
                    <div class="why-seazoned-content d-table-cell p-l-25 align-top">
                        <h4 class="m-0 m-b-10">Account Management with Frictionless Payment</h4>
                        <p class="m-0 m-b-50">Not at home? Don’t worry. With Seazoned you pay right through the app.
                            Also, schedule, change or view history via your own personalized account.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="why-seazoned-icon d-table-cell">
                        <img src="{{ url('/') }}/default/images/list.svg">
                    </div>
                    <div class="why-seazoned-content d-table-cell p-l-25 align-top">
                        <h4 class="m-0 m-b-10">Our Service</h4>
                        <p class="m-0 m-b-50">No matter the Seazon we’re there. We offer services that others do not.
                            Lawn mowing, fertilization,snow removal, etc. We pretty much
                            do it all</p>
                    </div>
                </div>
<!--                <div class="col-md-4">
                    <div class="why-seazoned-icon d-table-cell">
                        <img src="{{ url('/') }}/default/images/insurance.svg">
                    </div>
                    <div class="why-seazoned-content d-table-cell p-l-25 align-top">
                        <h4 class="m-0 m-b-10">Insured and vetted Professionals</h4>
                        <p class="m-0 m-b-50">Your property and safety are our top priority. Seazoned providers are
                            required to carry liability insurance and receive a background check.</p>
                    </div>
                </div>-->
                <div class="col-md-4">
                    <div class="why-seazoned-icon d-table-cell">
                        <img src="{{ url('/') }}/default/images/guarantee.svg">
                    </div>
                    <div class="why-seazoned-content d-table-cell p-l-25 align-top">
                        <h4 class="m-0 m-b-10">Quality Guaranteed</h4>
                        <p class="m-0 m-b-50">If you are not happy, we won't charge you. It's as simple as that.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="why-seazoned-icon d-table-cell">
                        <img src="{{ url('/') }}/default/images/favorites.svg">
                    </div>
                    <div class="why-seazoned-content d-table-cell p-l-25 align-top">
                        <h4 class="m-0 m-b-10">Raving Reviews</h4>
                        <p class="m-0 m-b-50">Don’t believe us? Check out our 5-star reviews</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="customer-says">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h3 class="text-center m-b-25 text-light">What our customers are saying</h3>
                    </div>
                    <div class="col-md-12 text-center m-b-30">
                        <i class="fa fa-quote-right" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-8 offset-md-2 ">
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0"
                                    class="active m-r-10"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1" class="m-r-10"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2" class="m-r-10"></li>
                            </ol>
                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item active">
                                    <div class="carousel-item-content text-center">
                                        <p>I haven’t ever seen my lawn look so nice. The grass edges were trimmed and
                                            precise. Also, the Seazoned app was so easy to use and it allowed me to find
                                            a provider a at a price I was willing to pay. I’m pretty sure I’ll only have
                                            Seazoned do it moving forward.</p>
                                        <h5 class="m-b-40">- Ty Jonas</h5>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="carousel-item-content text-center">
                                        <p>I haven’t ever seen my lawn look so nice. The grass edges were trimmed and
                                            precise. Also, the Seazoned app was so easy to use and it allowed me to find
                                            a provider a at a price I was willing to pay. I’m pretty sure I’ll only have
                                            Seazoned do it moving forward.</p>
                                        <h5 class="d-block m-b-40">- Ty Jonas</h5>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="carousel-item-content text-center">
                                        <p>I haven’t ever seen my lawn look so nice. The grass edges were trimmed and
                                            precise. Also, the Seazoned app was so easy to use and it allowed me to find
                                            a provider a at a price I was willing to pay. I’m pretty sure I’ll only have
                                            Seazoned do it moving forward.</p>
                                        <h5 class="d-block m-b-40">- Ty Jonas</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <section class="seazoned-app">
        <div class="container">
            <div class="row">
                <div class="col-md-3 d-none d-md-block">
                    <img src="{{ url('/') }}/default/images/mob-app3.png" alt="" class="andro-mobile">
                </div>
                <div class="col-md-6 text-center p-50 mobile-padding1">
                    <h4 class="m-t-50">You Have an App for This?</h4>
                    <p class="m-b-40">This is our specialty. Whether at home or on the go,
                        download our app for the complete Seazoned experience.
                        #ItAllHapsWithSeazoned </p>
                    <a href="" class="mobile-app-btn"><img src="{{ url('/') }}/default/images/app-store-btn.png" alt=""></a>
                    <a href="" class="mobile-app-btn"><img src="{{ url('/') }}/default/images/google-play-btn.png" alt=""></a>
                </div>
                <div class="col-md-3 mobile-text-center">
                    <img src="{{ url('/') }}/default/images/mob-app2.png" alt="" class="ipnonex-mobile m-t-20">
                </div>
            </div>
        </div>
    </section>
    <?php if (session('user_id') != "") { ?>
    <script>
                            
            $(function () {
                    var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                    $.ajax({
                        url: '{{ url("/Home/getDateTimeByTimezone") }}',
                        data: {timezone:timezone},
                        type: 'post',
                        success: function (response) {
                        }
                    });
                });
            </script>
    <?php } ?>
    
    <script type="text/javascript">
        var componentForm = {
            //street_number: 'short_name',
            //route: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'short_name',
            country: 'long_name',
            //postal_code: 'short_name'
        };
        var total_location="";
        function initAutocompleteRegistration(id) {
            // Create the autocomplete object, restricting the search to geographical
            // location types.
            autocomplete = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */(document.getElementById(id)),
                {types: ['geocode']});
                
            // When the user selects an address from the dropdown, populate the address
            // fields in the form.
            autocomplete.addListener('place_changed', fillInAddress);
        }

        function fillInAddress() {
            // Get the place details from the autocomplete object.
            var place = autocomplete.getPlace();
            
           // alert(place);
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                    $("#" + addressType).val(val);
                   total_location=total_location+val;
                }
            }
            
//             var enter_value=document.getElementById(id).value;
//             alert(enter_value);
        }

    </script>
    <script>
         function isDate()

	 {
            
	   var currVal = document.getElementById("date_slot").value;

	   if(currVal == '')
           {
              
             return false;  
           }

	    

	   

	//   //Declare Regex 

	   var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;

	   var dtArray = currVal.match(rxDatePattern); // is format OK?

	 

	   if (dtArray == null)
           {
              $("#error1").html("Enter the date in mm/dd/yyyy format");
	      return false;
          }
	  

	//   //Checks for mm/dd/yyyy format.

	   dtDay= dtArray[3];
           
	     dtMonth = dtArray[1];
           
	     dtYear = dtArray[5];
	 

	   if (dtMonth < 1 || dtMonth > 12)
           {    
                $("#error1").html("Enter valid month");
	       return false;

           }else if (dtDay < 1 || dtDay> 31)
           {     
                $("#error1").html("Enter valid date");
	       return false;

           }else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
           {
              
	       return false;

           }else if (dtMonth == 2)

	   {

	      var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));

	      if (dtDay> 29 || (dtDay ==29 && !isleap))
              {
	           return false;
                   }
	   }
          
//           var area = document.getElementById("administrative_area_level_1").value;
//           var locality = document.getElementById("locality").value;
//           var country = document.getElementById("country").value;
//          
//           if(area == '' || locality == '' || country == ''){
//              
//                $("#address_err").html("Please enter a valid address");
//                return false;
//           }
          var phone = document.getElementById("phone").value;
          if(isNaN(phone)){
               $("#phone_err").html("Enter a valid number");
	       return false;
          }

	   return true;

         

	 }
        </script>

    <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1XtsegzOIIBaqGL0QugHFIaL2pvp8f8A&libraries=places" async defer></script>-->
@endsection