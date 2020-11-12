@extends("layouts.dashboardlayout")
@section("content")

<section class="register-member-banner">
    <div class="container">
        <div class="register-member text-center">
            <p class="text-uppercase m-b-0">member Register</p>
        </div>
    </div>
</section>

<section class="register-member-form">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 custom-form">
                <div class="card p-45">
                    <div class="card-block p-0">
                        <form class="m-t-30" action="{{ url("/add-user/") }}" method="post" enctype="multipart/form-data" onsubmit="return isDate()">
                            <div class="avtar m-b-30">
                                <img src="{{ asset("/default/images/register-image.jpg") }}" alt="" id="user_image_tag">
                                <span class="status"></span>
                                <label class="change-photo-overlay animated slideInUp">
                                    <input type="file" name="profile_picture" class="d-none" id="user_image" required/>
                                    <span>Upload</span>
                                </label>
                            </div>
                            <span id="user_image_err" class="text-danger"></span>
                            <div class="row m-b-20">
                                <div class="col-md m-b-0 form-group">
                                    <label for="f-name">First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="{{ Input::old("first_name") }}" required>
                                    @if($errors->has('first_name'))
                                    <span class="error">{!! $errors->first('first_name') !!}</span>
                                    @endif
                                </div>
                                <div class="col-md m-b-0 form-group">
                                    <label for="l-name">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" value="{{ old("last_name") }}" required>
                                    @if($errors->has('last_name'))
                                    <span class="error">{!! $errors->first('last_name') !!}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row m-b-20">
                                <div class="col-md m-b-0 form-group">
                                    <label for="email">Email ID</label>
                                    <input type="email" class="form-control" name="email" value="{{ old("email") }}" required>
                                    @if($errors->has('email'))
                                    <span class="error">{!! $errors->first('email') !!}</span>
                                    @endif
                                </div>
                                <div class="col-md m-b-0 form-group">
                                    <label for="tel">Phone Number</label>
                                    <input type="tel" class="form-control" name="tel" value="{{ old("tel") }}" required>
                                    @if($errors->has('tel'))
                                    <span class="error">{!! $errors->first('tel') !!}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row m-b-20">
                                <div class="col-md m-b-0 form-group" data-provide="datepicker">
                                    <label for="">Date of Birth<code>(Use mm/dd/yyyy format)</code></label>
                                    <div class="" data-date="1979-09-16" data-date-format="dd-mm-yyyy" data-link-field="dtp_input1">
                                        <input placeholder="" class="form-control" type="text" name="dob" id="date_slot" value="{{ old("dob") }}"  required>
                                        <!--<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>-->
                                    </div>
                                    @if($errors->has('dob'))                             
                                    <span class="error">{!! $errors->first('dob') !!}</span>
                                    @endif
                                    <span id="error1" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="row m-b-20">
                                <div class="col-md m-b-0 form-group">
                                    <label for="street">Street/Location</label>
                                    <input type="text" class="form-control" name="street" value="{{ old("street") }}" required>
                                    @if($errors->has('street'))
                                    <span class="error">{!! $errors->first('street') !!}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row m-b-20">
                                <div class="col-md m-b-0 form-group">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" name="city" value="{{ old("city") }}" required>
                                    @if($errors->has('city'))
                                    <span class="error">{!! $errors->first('city') !!}</span>
                                    @endif
                                </div>
                                <div class="col-md m-b-0 form-group">
                                    <label for="state">State</label>
                                    <input type="text" class="form-control" name="state" value="{{ old("state") }}" required>
                                    @if($errors->has('state'))
                                    <span class="error">{!! $errors->first('state') !!}</span>
                                    @endif
                                </div>
                            </div>
<!--                            <div class="row m-b-20">
                                <div class="col-md-12 m-b-0 form-group">
                                    <label for="country">Country</label>
                                    <select class="form-control" name="country">
                                        @forelse($countrys as $country)
                                        <option value="{{ $country["id"] }}">{{ $country["country_name"] }}</option>
                                        @empty
                                        <option value="0">No Country Found</option>
                                        @endforelse
                                    </select>
                                    @if($errors->has('country_name'))
                                    <span class="error">{!! $errors->first('country_name') !!}</span>
                                    @endif
                                </div>
                            </div>-->
                            <div class="row m-b-20">
                                <div class="col-md m-b-0 form-group">
                                    <label for="city">Password</label>
                                    <input type="password" class="form-control" name="password" value="{{ old("password") }}" required>
                                    @if($errors->has('password'))
                                    <span class="error">{!! $errors->first('password') !!}</span>
                                    @endif
                                </div>
                                <div class="col-md m-b-0 form-group">
                                    <label for="state">Confirm Password</label>
                                    <input type="password" class="form-control" value="{{ old("password_confirmation") }}" name="password_confirmation" required>
                                    @if($errors->has('password_confirmation'))
                                    <span class="error">{!! $errors->first('password_confirmation') !!}</span>
                                    @endif
                                </div>
                            </div>
                            <input type="hidden" value="2" name="user_type">
                            <button type="submit" class="btn noradius btn-success mx-auto d-block m-t-40" id="sub_btn">Register
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(function () {
        $("#user_image").change(function () {
            if (this.files && this.files[0]) {

                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#user_image_tag').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        $("#sub_btn").click(function(){
            if($("#user_image").val()==""){
                $("#user_image_err").html("Please Upload Profile Picture");
                return false;
            } else {
                $("#user_image_err").html("");
            }
        });
    });

</script>

        <script>
         function isDate()

	 {
            
            
	   var currVal = document.getElementById("date_slot").value;

	   if(currVal == '')
           {
              
             return false;  
           }

	    

	   

	   //Declare Regex 

	   var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;

	   var dtArray = currVal.match(rxDatePattern); // is format OK?

	 

	   if (dtArray == null)
           {
              $("#error1").html("Enter the date in mm/dd/yyyy format");
	      return false;
          }
	  

	   //Checks for mm/dd/yyyy format.


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

	   return true;

	 }
        </script>
@endsection
