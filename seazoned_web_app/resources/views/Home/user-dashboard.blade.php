@extends("layouts.dashboardlayout")
@section('content')

    <section class="main-content user-profile p-y-30">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card side-menu">
                        <div class="card-block p-t-40 p-b-30">
                            <div class="avatar">
                                <img src="{!! asset("default/images/avatar-landscaper.jpg") !!}">
                                <span class="blink"></span>
                            </div>
                            <h4 class="profile-name text-center m-0 m-t-20">{{ $info[0]["first_name"] . " " . $info[0]["last_name"] }}</h4>
                        </div>
                        <ul class="list-group list-group-flush list-unstyled">
                            <li class="list-group-item"><a href="#service_request" class="w-100 text-center">Change Password</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h4 class="m-0 regular">{{ $info[0]["first_name"] . " " . $info[0]["last_name"] }}</h4>
                            <a href="" class="float-right header-link regular">Edit Info</a>
                        </div>
                        <div class="card-block user-profile-details">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="m-b-30"><b>Date of Birth</b><br>{{ $info[0]["date_of_birth"]}}</p>
                                    <p><b>Cell phone</b><br>{{ $info[0]["phone_number"]}}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="m-b-30"><b>Email ID</b><br>{{ $info[0]["email"]}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card m-t-30 card-no-radius">
                        <div class="card-header card-header-success">
                            <h4 class="m-0 regular">My Address Book</h4>
                            <a href="" class="float-right medium add-icon header-link">ADD NEW ADDRESS</a>
                        </div>
                        <div class="card-block">
                            <div class="address-block clearfix d-block w-100">
                                <div class="address-left float-left">
                                    <h5 class="regular m-b-10">Tom Wilson</h5>
                                    <p class="m-b-10 light">29 Sleepy Hollow Drive, Hamilton Parish, Bermuda</p>
                                    <p class="m-b-10 light">Phone: (123) 123-456</p>
                                    <p class="m-b-10 light">E-Mail: office@example.com</p>
                                </div>
                                <a href="" class="float-right medium">EDIT</a>
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="card-block">
                            <div class="address-block clearfix d-block">
                                <div class="address-left float-left">
                                    <h5 class="regular m-b-10">Mike Doe</h5>
                                    <p class="m-b-10 light">12345 Little Lonsdale St, Melbourne</p>
                                    <p class="m-b-10 light">Phone: (123) 123-456</p>
                                    <p class="m-b-10 light">E-Mail: office@example.com</p>
                                </div>
                                <a href="" class="float-right medium">EDIT</a>
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="card-block">
                            <div class="address-block clearfix d-block">
                                <div class="address-left float-left">
                                    <h5 class="regular m-b-10">Mike Doe</h5>
                                    <p class="m-b-10 light">12345 Little Lonsdale St, Melbourne</p>
                                    <p class="m-b-10 light">Phone: (123) 123-456</p>
                                    <p class="m-b-10 light">E-Mail: office@example.com</p>
                                </div>
                                <a href="" class="float-right medium">EDIT</a>
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="card-block">
                            <div class="address-block clearfix d-block w-100">
                                <div class="address-left float-left">
                                    <h5 class="regular m-b-10">Tom Wilson</h5>
                                    <p class="m-b-10 light">29 Sleepy Hollow Drive, Hamilton Parish, Bermuda</p>
                                    <p class="m-b-10 light">Phone: (123) 123-456</p>
                                    <p class="m-b-10 light">E-Mail: office@example.com</p>
                                </div>
                                <a href="" class="float-right medium">EDIT</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
