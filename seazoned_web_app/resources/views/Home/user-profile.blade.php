@extends("layouts.dashboardlayout")
@section('content')

<section class="main-content user-profile p-y-30" xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <form action="{{ url("/User/Update-Profile/") }}" method="post" enctype="multipart/form-data">
                    <div class="card side-menu">
                        <div class="card-block p-t-40 p-b-30">
                            <div class="avtar">
                                <?php
                                    $prof_pic = url('/') . '/default/images/userdefault.png';
                                if ( session("prof_img") !== "") {
                                    if (file_exists(public_path() . '/uploads/profile_picture/' .  session("prof_img"))) {
                                        $prof_pic = url('/public') . '/uploads/profile_picture/' .  session("prof_img");
                                    }
                                }
                                if (session("social_source") != null) {
                                    ?>
                                    <img src="{{ $prof_pic }}" alt="" id="user_image_tag">
                                    <!--<img src="{{ (session("prof_img") == NULL && !file_exists("/uploads/profile_picture/".session("prof_img"))) ? asset("/default/images/profile_pic.jpg") : session("prof_img") }}" alt="" id="user_image_tag">-->                                                      
                                    <?php
                                } else {
                                    ?>
                                    <img src="{{ $prof_pic }}" alt="" id="user_image_tag">
                                    <?php
                                }
                                ?>              
                                <label class="change-photo-overlay animated slideInUp">
                                    <input type="file" name="profile_image" class="d-none" id="user_image"/>
                                    <span>Change</span>
                                </label>
                            </div>
                            <h4 class="profile-name text-center m-0 m-t-20">{{ $user_info->first_name . " " . $user_info->last_name }}</h4>
                        </div>
                        <ul class="list-group list-group-flush list-unstyled">
                            <li class="list-group-item"><a href="javascript:void(0)" class="w-100 text-center" data-toggle="modal" data-target="#change_password">Change Password </a></li>
                        </ul>
                        <div class="p-15 text-center">
                        <button type="submit"  class="btn btn-default"/><i class="fa fa-upload"></i> Upload</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <?php
                if (session('msg') != "") {
                ?>
                <div class="alert alert-success">{{ session('msg') }}</div><br>
                <?php } ?>
                <div class="card custom-card">
                    <div class="card-header">
                        <h4 class="m-0 regular">{{ $user_info->first_name . " " . $user_info->last_name }}</h4>
                        <a href="javascript:void(0)" class="link-edit-info float-right header-link regular">Edit Info</a>
                    </div>
                    <div class="card-block user-profile-details">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="m-b-30"><b>Date of Birth</b><br>{{ date('m/d/Y',strtotime($user_info->date_of_birth))}}</p>
                                <p><b>Cell phone</b><br>{{ $user_info->phone_number }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="m-b-30"><b>Email ID</b><br>{{ $user_info->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card m-t-30 card-no-radius">
                    <div class="card-header card-header-success">
                        <h4 class="m-0 regular">My Address Book</h4>
                        <a href="javascript:void(0)" class="float-right medium add-icon header-link" data-toggle="modal" data-target="#add_address">ADD NEW ADDRESS</a>
                    </div>
                    @forelse($addresses as $address_key=>$address)
                    <div class="card-block">

                        <div class="address-block clearfix d-block w-100">
                            <div class="address-left float-left">
                                <h5 class="regular m-b-10">{{ $address->name }}</h5>
                                <p class="m-b-10 light">{{ $address->address }}</p>
                                <p class="m-b-10 light">Phone: {{ $address->contact_number }}</p>
                                <p class="m-b-10 light">E-Mail: {{ $address->email_address }}</p>
                            </div>
                            <a href="" class="float-right medium" data-toggle="modal" data-target="#edit_address" onClick="return editAddress({{ $address->id }},{{"' $address->name '"}},{{"' $address->address '"}},{{"' $address->city '"}},{{"' $address->country '"}},{{"' $address->contact_number '"}},{{"' $address->email_address '"}});">EDIT</a>
                        </div>

                    </div>
                    <hr class="m-0">
                        @empty
                        <p class="text-danger">No Address Found</p>
                        @endforelse
                </div>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>

<!--  Edit Information Modal  -->

<div class="modal fade" id="edit_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ url("/user/edit-profile") }}" method="post" enctype="multipart/form-data">                            
                <div class="modal-body">
                    <div class="form-group">
                        <label for="f-name">First Name</label>
                        <input type="text" class="form-control" name="first_name" value="{{ $user_info->first_name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="l-name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" value="{{ $user_info->last_name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="tel">Phone Number</label>
                        <input type="tel" class="form-control" name="tel" value="{{ $user_info->phone_number }}" required>
                    </div>
                    

                    <div class=" form-group" data-provide="datepicker">
                        <label for="">Date of Birth</label>
                        <div class="" data-date="" data-date-format="dd mm yyyy" data-link-field="dtp_input1">
                            <input  class="form-control" type="text" name="dob" id="date_slot" value="{{ date('m/d/Y',strtotime($user_info->date_of_birth))  }}"  required>
                        </div> 
                        <span id="error1" class="text-danger"></span>
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

<!--  Change Password Modal  -->
<div class="modal fade" id="change_password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Change Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form class="m-t-30" id="edit_password" action="{{ url("/user/update-password") }}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="f-name">Old Password</label>
                        <input type="password" class="form-control" name="old_password" id="old_password" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="l-name">New Password</label>
                        <input type="password" class="form-control" name="new_password" id="new_password" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="tel">Re Enter New Password</label>
                        <input type="password" class="form-control" name="confirm_new_password" id="confirm_new_password" value="" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="change_pass_button" class="btn btn-success">Save</button>
                </div> 
            </form>  
        </div>
    </div>
</div>
<!--   end modal  -->

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

                    <div class="form-group">
                        <label for="addressbook_contact">Contact Number</label>
                        <input type="text" class="form-control" name="addressbook_contact" value="">  
                    </div>
                    <div class="form-group">
                        <label for="addressbook_email">Email Address</label>
                        <input type="text" class="form-control" name="addressbook_email" value=""/>                   
                        <input type="hidden" name="user_id" value="{{ $user_info->user_id }}"/> 
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
                     <div class="form-group">
                        <label for="addressbook_contact">Contact Number</label>
                        <input type="text" class="form-control" name="editaddressbook_contact" value="">  
                    </div>  
                    <div class="form-group">
                        <label for="addressbook_email">Email Address</label>
                        <input type="text" class="form-control" name="editaddressbook_email" value=""/>                   
                        <input type="hidden" name="address_id" value=""/>
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
    $('.link-edit-info').click(function(){
    $('#edit_info').modal('show');
    });
    
    $("#change_pass_button").click(function()
    {
       var old_password = $("#old_password").val();
       var new_password = $("#new_password").val();
       var confirm_new_password = $("#confirm_new_password").val();
       
       if(new_password!=confirm_new_password)
       {
           swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Password Mismatch !!!'
                })
           return false;
       }
       else
       {
             $.ajax({
                type: "POST",
                url: "{{ url('/user/match-password') }}",
                data: {old_password: old_password},
                success: function(result){
                    //alert(result);
                    //return false;
                    if(result==1){
                         $("#edit_password").submit();
                         return true;
                    } else {
                        swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Wrong old password !!!'
                            })
                        return false;
                    }
                }
            });
            } 
        });
    });
</script>

<script>
    // function isDate()

    // {


    // var currVal = document.getElementById("date_slot").value;
    // if (currVal == '')
    // {

    // return false;
    // }
    
    // //Declare Regex 

    // var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
    // var dtArray = currVal.match(rxDatePattern); // is format OK?



    // if (dtArray == null)
    // {
    // $("#error1").html("Enter the date in dd-mm-yyyy format");
    // return false;
    // }


    // //Checks for mm/dd/yyyy format.

    // dtDay = dtArray[1];
    // dtMonth = dtArray[3];
    // dtYear = dtArray[5];
    // if (dtMonth < 1 || dtMonth > 12)
    // {
    // $("#error1").html("Enter valid month");
    // return false;
    // } else if (dtDay < 1 || dtDay > 31)
    // {
    // $("#error1").html("Enter valid date");
    // return false;
    // } else if ((dtMonth == 4 || dtMonth == 6 || dtMonth == 9 || dtMonth == 11) && dtDay == 31)
    // {

    // return false;
    // } else if (dtMonth == 2)

    // {

    // var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
    // if (dtDay > 29 || (dtDay == 29 && !isleap))
    // {
    // return false;
    // }
    // }

    // return true;
    // }
</script>

@endsection
