@extends("layouts.dashboardlayout")
@section('content')

<?php //print_r(session("user_id")); ?>

<form action="{{ url("/Landscapper/Update-Profile") }}" method="post" enctype="multipart/form-data">
    <input type="hidden" name="landscaper_id" id="landscaper_id" value="0">
    <section class="main-content user-profile p-y-30">
        <div class="container">
            <div class="landscaper-profile">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md">
                                <div class="card side-menu card-no-radius m-b-30">
                                    <div class="card-block p-t-40 p-b-0">
                                        <div class="avtar">
                                            <img src="{{ (session("prof_img") == NULL) ? asset("/default/images/avatar-landscaper.jpg") : session("profile_image") }}" alt="">
                                            <span class="status"></span>
                                            <label class="change-photo-overlay animated slideInUp">
                                                <input type="file" name="profile_image" class="d-none"/>
                                                <span>Change</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="justify-content-md-center d-flex p-t-15">
                                        <div class="rating col-md-auto">
                                            <ul class="list-unstyled m-b-0">
                                                <li><i class="fas fa-star"></i></li>
                                                <li><i class="fas fa-star"></i></li>
                                                <li><i class="fas fa-star"></i></li>
                                                <li><i class="fas fa-star"></i></li>
                                                <li><i class="far fa-star"></i></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <h4 class="profile-name text-center m-0 m-t-20 m-b-50">{{ session("user_name") }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <div class="featured-img">
                                    <div class="card card-no-radius ">
                                        <div class="card-block">
                                            <h3 class="m-0 medium m-b-20">Featured image</h3>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img alt="" class="w-100 m-b-15" id="featured_image">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="image-upload-btn btn btn-success noradius m-t-15">
                                                        <i class="fa fa-upload"></i>&nbsp; &nbsp;Select Image to Upload
                                                        <input type="file" name="landscaper_image">
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    {{--<a href="#" class="btn btn-remove noradius"> REMOVE IMAGE</a>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <div class="service-hours m-t-30 p-20">
                                    <h4 class="medium">Service Hours
                                        <span><a href="javascript:void(0)" class="float-right header-link regular text-success" onclick="getSeviceHours()">Edit</a></span>
                                    </h4>
                                    <div id="service_time">

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-8">
                        <?php
                        if (session('msg') != "")
                        {
                            ?>
                            <div class="alert alert-success">
                                {{ session('msg') }}
                            </div><br>
<?php } ?>
                        <div class="card custom-card card-no-radius m-b-45">
                            <div class="card-header">
                                <h4 class="m-0 regular">{{ session("user_name") }}</h4>
                                <a href="javascript:void(0)" class="float-right header-link regular text-success" data-toggle="modal" data-target="#edit_info">Edit Info</a>
                            </div>
                            <div class="card-block user-profile-details">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="m-b-30"><b>Business Address</b><br>{{ $user_details->address }}</p>
                                        <p><b>Cell phone</b><br>{{ $user_details->phone_number }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="m-b-30"><b>Email ID</b><br>{{ session("user_email") }}</p>
                                    </div>
                                </div>
                                {{--<div class="row">--}}
                                {{--<div class="col-md">--}}
                                {{--<p class="m-b-30"><b>Description</b><br>--}}
                                {{--Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.--}}
                                {{--</p>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                        <div class="card card-no-radius m-b-30">
                            <div class="card-header card-header-success">
                                <h4 class="m-0 medium">Service details</h4>
                            </div>
                            <div class="card-block custom-form p-b-0">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="service-name">Service Name</label>
                                        <select class="form-control" id="service-name" name="service_id" disabled="">
                                            @foreach($services as $service)
                                            <option value="{{ $service->id }}" {{ ($default_tab == $service->id) ? "selected" : "" }}>{{ $service->service_name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="service_id" value="{{ $default_tab }}">
                                    </div>
                                </div>



                                {{--<hr class="m-0">--}}

                                <div id="service_area">

                                </div>
                            </div>

                            {{--<div class="card-footer p-y-20">--}}
                            {{--<a href="" class="text-info"><i class="fa fa-plus"></i>&nbsp;&nbsp;ADD NEW SERVICE</a>--}}
                            {{--</div>--}}

                        </div>
                        <input type="submit" value="Update Profile" class="btn btn-success">
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>

<!--  Edit Information Modal  -->

<div class="modal fade" id="edit_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>                
            </div>
            <form class="m-t-30" action="{{ url("/lanscaper/edit-profile") }}" method="post" enctype="multipart/form-data">
                <div class="modal-body">      
                    <div class="form-group">
                        <label for="f-name">First Name</label>
                        <input type="text" class="form-control" name="first_name" value="{{ $user_details->first_name }}" required>
                    </div>   
                    <div class="form-group">
                        <label for="l-name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" value="{{ $user_details->last_name }}" required>
                    </div>   
                    <div class="form-group">
                        <label for="tel">Phone Number</label>
                        <input type="tel" class="form-control" name="tel" value="{{ $user_details->phone_number }}" required>
                    </div>   
                    <div class="form-group">
                        <label for="datepicker">Business Address</label>
                        <input type="text" class="form-control" name="address" value="{{ $user_details->address }}" required>
                        <!--                    <label for="datepicker">Email</label>
                                            <input type="text" class="form-control" name="email" value="{{ $user_details->email }}" required>-->
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

<!--  Edit Information Modal  -->

<div class="modal fade" id="edit_service" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Service Hours</h4>
            </div>
            <div class="modal-body" id="edit_service_body">

            </div>
        </div>
    </div>
</div>
<!--   end modal  -->

<script type="text/javascript">
    $(function () {
    getData("{{ $default_tab }}");
    $("#service-name").on('change', function () {
    getData(this.value);
    });
    });
    function getData(value) {
    switch (value) {
    case "1":
            $.get("{{ url(" / landscaper - load - service / 1") }}", function (data) {
            if (data != "No Services Found") {
            var returnedData = JSON.parse(data);
            $("#featured_image").attr("src", returnedData['profile_image']);
            $("#service_time").html(atob(returnedData['service_time']));
            $("#service_area").html(atob(returnedData['form']));
            $("#landscaper_id").val(returnedData["landscaper_id"]);
            } else {
            $("#service_area").html(data);
            $("#featured_image").attr("src", "");
            $("#service_time").html("");
            $("#service_area").html("<p class='alert alert-danger'>No Data Found</p>");
            $("#landscaper_id").val("0");
            }

            });
    break;
    case "2":
            $.get("{{ url(" / landscaper - load - service / 2") }}", function (data) {
            if (data != "No Services Found") {
            var returnedData = JSON.parse(data);
            $("#featured_image").attr("src", returnedData['profile_image']);
            $("#service_time").html(atob(returnedData['service_time']));
            $("#service_area").html(atob(returnedData['form']));
            $("#landscaper_id").val(returnedData["landscaper_id"]);
            } else {
            $("#service_area").html(data);
            $("#featured_image").attr("src", "");
            $("#service_time").html("");
            $("#service_area").html("<p class='alert alert-danger'>No Data Found</p>");
            $("#landscaper_id").val("0");
            }

            });
    break;
    case "3":
            $.get("{{ url(" / landscaper - load - service / 3") }}", function (data) {
            if (data != "No Services Found") {
            var returnedData = JSON.parse(data);
            $("#featured_image").attr("src", returnedData['profile_image']);
            $("#service_time").html(atob(returnedData['service_time']));
            $("#service_area").html(atob(returnedData['form']));
            $("#landscaper_id").val(returnedData["landscaper_id"]);
            } else {
            $("#service_area").html(data);
            $("#featured_image").attr("src", "");
            $("#service_time").html("");
            $("#service_area").html("<p class='alert alert-danger'>No Data Found</p>");
            $("#landscaper_id").val("0");
            }

            });
    break;
    case "4":
            $.get("{{ url(" / landscaper - load - service / 4") }}", function (data) {
            if (data != "No Services Found") {
            var returnedData = JSON.parse(data);
            $("#featured_image").attr("src", returnedData['profile_image']);
            $("#service_time").html(atob(returnedData['service_time']));
            $("#service_area").html(atob(returnedData['form']));
            $("#landscaper_id").val(returnedData["landscaper_id"]);
            } else {
            $("#service_area").html(data);
            $("#featured_image").attr("src", "");
            $("#service_time").html("");
            $("#service_area").html("<p class='alert alert-danger'>No Data Found</p>");
            $("#landscaper_id").val("0");
            }

            });
    break;
    case "5":
            $.get("{{ url(" / landscaper - load - service / 5") }}", function (data) {
            if (data != "No Services Found") {
            var returnedData = JSON.parse(data);
            $("#featured_image").attr("src", returnedData['profile_image']);
            $("#service_time").html(atob(returnedData['service_time']));
            $("#service_area").html(atob(returnedData['form']));
            $("#landscaper_id").val(returnedData["landscaper_id"]);
            } else {
            $("#service_area").html(data);
            $("#featured_image").attr("src", "");
            $("#service_time").html("");
            $("#service_area").html("<p class='alert alert-danger'>No Data Found</p>");
            $("#landscaper_id").val("0");
            }

            });
    break;
    case "6":
            $.get("{{ url(" / landscaper - load - service / 6") }}", function (data) {
            if (data != "No Services Found") {
            var returnedData = JSON.parse(data);
            $("#featured_image").attr("src", returnedData['profile_image']);
            $("#service_time").html(atob(returnedData['service_time']));
            $("#service_area").html(atob(returnedData['form']));
            $("#landscaper_id").val(returnedData["landscaper_id"]);
            } else {
            $("#service_area").html(data);
            $("#featured_image").attr("src", "");
            $("#service_time").html("");
            $("#service_area").html("<p class='alert alert-danger'>No Data Found</p>");
            $("#landscaper_id").val("0");
            }

            });
    break;
    case "7":
            $.get("{{ url(" / landscaper - load - service / 7") }}", function (data) {
            if (data != "No Services Found") {
            var returnedData = JSON.parse(data);
            $("#featured_image").attr("src", returnedData['profile_image']);
            $("#service_time").html(atob(returnedData['service_time']));
            $("#service_area").html(atob(returnedData['form']));
            $("#landscaper_id").val(returnedData["landscaper_id"]);
            } else {
            $("#service_area").html(data);
            $("#featured_image").attr("src", "");
            $("#service_time").html("");
            $("#service_area").html("<p class='alert alert-danger'>No Data Found</p>");
            $("#landscaper_id").val("0");
            }

            });
    break;
    }
    }

    function getSeviceHours(){
    var service_id = $("#service-name").val();
    var landscaper_id = $("#landscaper_id").val();
    $.post("{{ url('/lanscaper/get-service-hours') }}", {
    service_id:service_id,
            landscaper_id:landscaper_id
    }, function (data) {
//                 alert(data);
    $("#edit_service").modal('show');
    $("#edit_service_body").html(data);
    });
    }

</script>

@endsection