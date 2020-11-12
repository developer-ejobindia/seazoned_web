<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegister;
use App\Service;
use App\UserDetail;
use App\PaymentAccounts;
use Illuminate\Support\Facades\DB;
use App\Properties;
use App\User;
use App\save_properties;
use App\share_properties;
use App\AddressBook;
use App\Landscaper;
use App\BookService;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Mail;
use Log;
use Validator;
use Redirect;
use Session;
use App\Country;
use App\TestModel;

Class HomeController extends Controller {

    public function Index(Request $request) {
        $allData = array('title' => 'Home Page', 'layout' => 'dashboardlayout', 'tab' => 'index_page');

        $allData["services"] = Service::all();
        $bookService_obj = new BookService;

        if (session("user_id") != "" && session("profile_id") == 2) {
            $user_id = session("user_id");
            $allData["services_pend"] = $bookService_obj->getServiceBooking();
            $allData["notification_status"] = $bookService_obj->notificationStatus();
        }

        if (session("user_id") != "" && session("profile_id") == 3) {
            $user_id = session("user_id");
            $allData["requested_services"] = $bookService_obj->getRequestedServices($user_id);
        }

        return View('Home.index', $allData);
    }

    public function userRegister() {
        $data["countrys"] = Country::all()->sortBy("country_name");
        return view('Home.user-register', $data);
    }

    public function addUser(UserRegister $request) {
        $user = new User();
        $user->username = $request->email;
        $user->password = md5($request->password);
        $user->active = 1;
        $user->profile_id = 2;
        $user->user_type = "Users";
        $user->save();

        $profile = new UserDetail();
        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->email = $request->email;
        $profile->phone_number = $request->tel;
        $profile->date_of_birth = date("Y-m-d", strtotime($request->dob));
        $profile->address = $request->street;
        $profile->city = $request->city;
        $profile->state = $request->state;
        $profile->country = $request->country;
        $profile->user_id = $user->id;

        if (Input::hasFile('profile_picture') && Input::file('profile_picture')->isValid()) {
            $avatar = $request->file("profile_picture");
            $fileName = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
            $avatar->move("uploads/profile_picture/", $fileName);
            $profile->profile_image = $fileName;
        }

        $profile->save();

        $addr_book = new AddressBook();
        $addr_book->user_id = $user->id;
        $addr_book->name = $request->first_name . ' ' . $request->last_name;
        $addr_book->address = $request->street;
        $addr_book->city = $request->city;
        $addr_book->state = $request->state;
        $addr_book->country = $request->country;
        $addr_book->contact_number = $request->tel;
        $addr_book->email_address = $request->email;
        $addr_book->primary_address = 1;
        $addr_book->save();

        session()->flash("msg", "Registration Successful");

        return redirect()->route("user-login");
    }

    public function userLogin() {
        return view('Home.user-login');
    }

    public function forgotPassword() {
        return view('Home.forgot-password');
    }

    public function verifyEmail(Request $request) {
        $email = $_POST['username'];

        $val1 = DB::table('users')->where('username', '=', $email)->get();



        if (count($val1) != 0) {
            $val2 = DB::select("SELECT CONCAT(first_name,' ',last_name) AS name, email FROM `user_details` WHERE user_id = '" . $val1[0]->id . "'");

            $data['name'] = $val2[0]->name;
            $data['email'] = $val2[0]->email;
            $otp = mt_rand(100000, 999999);
            $data['otp'] = $otp;

            $sql = DB::update("UPDATE users SET otp= '" . $data['otp'] . "', forgot_password_status = 1 WHERE id ='" . $val1[0]->id . "'");

            //$update = "UPDATE users SET otp='".$data['otp']."', forgot_password_status = 1 WHERE id='".$val1[0]->id."'";
            //$update = DB::select($update);

            Mail::send('emailcontent', ['data' => $data], function ($message) use($data) {
                $message->subject("Seazoned Forgot Password Confirmation");
                $message->from('admin@seazoned.com', 'Seazoned');
                $message->to($data['email']);
            });

            $request->session()->flash('msg', 'Please check your email for a message with your code. Your code is 6 numbers long.');
            return redirect()->route("confirm-otp");
        } else {
            $request->session()->flash('msg', 'Invalid Email Id !!!');
            return redirect()->route("forgot-password");
        }
    }

    public function confirmOtp() {
        return view('Home.confirm-otp');
    }

    public function verifyOtp(Request $request) {
        $otp = $_POST['otp'];

        $val = DB::table('users')->where('otp', '=', $otp)->get();

        if (count($val) != 0) {
            Session::set('otp', $otp);
            return redirect()->route("reset-password");
        } else {
            $request->session()->flash('msg', 'Invalid OTP !!!');
            return redirect()->route("confirm-otp");
        }
    }

    public function resetPassword() {
        return view('Home.reset-password');
    }

    public function updatePassword(Request $request) {
        $otp = Session::get('otp');
        $new_password = $_POST['new_password'];
        $confirm_new_password = $_POST['confirm_new_password'];


        if ($new_password == $confirm_new_password) {
            $sql = DB::update("UPDATE users SET password = '" . md5($new_password) . "' WHERE otp ='" . $otp . "'");

            Session::forget('otp');
            session()->flash("msg", "Password Updated Successfully");
            return redirect()->route("user-login");
        } else {
            $request->session()->flash('msg', 'Password Mismatch !!!');
            return redirect()->route("reset-password");
        }
    }

    public function loginAccess(Request $request) {
        if ($_REQUEST['fb_access_token'] != null) {
            $fb_user_details = "https://graph.facebook.com/me?fields=id,first_name,last_name,email,picture&access_token=" . $_REQUEST['fb_access_token'];
            $fb_response = file_get_contents($fb_user_details);
            $fb_response = json_decode($fb_response);

            if ($fb_response && $fb_response->id != null) {
                $user_obj = new User();
                $user_detail_obj = new UserDetail();

                $chk_user = DB::select("select count(*) as user_count from users where username = '$fb_response->email';");

                if ($chk_user[0]->user_count == 0) {
                    $user_obj->username = urldecode($fb_response->email);
                    $user_obj->active = 1;
                    $user_obj->profile_id = 2;
                    $user_obj->user_type = "Users";
                    $user_obj->social_id = $fb_response->id;
                    $user_obj->social_source = "Facebook";
                    if ($user_obj->save()) {
                        $user_detail_obj->user_id = $user_obj->id;
                        $user_detail_obj->first_name = $fb_response->first_name;
                        $user_detail_obj->user_id = $fb_response->last_name;
                        $user_detail_obj->user_id = $fb_response->email;
                        $success = DB::insert("INSERT INTO user_details VALUES(NULL," . $user_obj->id . ",'" . $fb_response->first_name . "','" . $fb_response->last_name . "','" . $fb_response->email . "',NULL,NULL,NULL,NULL,NULL,NULL,'" . $fb_response->picture->data->url . "')");
                        if ($success == 1)
                            $user = DB::select("select * from users where username = '$fb_response->email';");
                    }
                } else
                    $user = DB::select("select * from users where username = '$fb_response->email';");
            }
        }

        else {
            $this->validate($request, [
                'username' => 'required',
                'password' => 'required',
            ]);

            $username = $request->username;
            $password = md5($request->password);
            $user = DB::select("select * from users where username = '$username' and password = '$password';");
        }

        if (count($user) == 0) {
            session()->flash("msg", "Invalid Username Or Password");
            return redirect()->route("user-login");
        }

        $user_info = DB::select("select * from user_details where user_id = '{$user[0]->id}';");


        session(["user_id" => $user[0]->id, "profile_id" => $user[0]->profile_id, "user_email" => $user[0]->username, "user_role" => $user[0]->user_type, "user_name" => $user_info[0]->first_name . " " . $user_info[0]->last_name, "profile_image" => url("/uploads/profile_picture/" . $user_info[0]->profile_image), "prof_img" => $user_info[0]->profile_image, "social_source" => $user[0]->social_source]);

        $latitude = null;
        $longitude = null;
        if ($request->latitude != null)
            $latitude = $request->latitude;
        if ($request->longitude != null)
            $longitude = $request->longitude;


        $current_loc = '';
        if ($latitude != null && $longitude != null) {
            $geolocation = $latitude . ',' . $longitude;
            $request = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $geolocation . '&sensor=false';
            $file_contents = file_get_contents($request);
            $json_decode = json_decode($file_contents);

            $response = array();
            $response1 = array();
            if (isset($json_decode->results[0])) {
                $counter = 1;
                foreach ($json_decode->results[0]->address_components as $addressComponet) {
                    //if(in_array('political', $addressComponet->types)) {
                    if ($counter == 7) {
                        $current_loc = $addressComponet->long_name . ', ' . $addressComponet->short_name;
                    }
                    //}
                    $counter++;
                }
            }
        }

        session(['current_loc' => $current_loc]);


        if ($user[0]->user_type == "Users") {
            return redirect()->route("home");
        }

        if ($user[0]->user_type == "Landscaper") {
            return redirect()->route("landscaper-dashboard");
        }
    }

    public function landscaperDashboard(Request $request) {
        if ($request->session()->get('user_id') == "") {
            return redirect()->route("user-login");
        }
        $acc_data = DB::select("SELECT COUNT(id) as data_count FROM payment_accounts WHERE user_id = " . $request->session()->get('user_id'));
        $data['acc_data'] = $acc_data[0]->data_count;
        $data['menu'] = "landscaperDashboard";
        $data["info"] = UserDetail::where(["user_id" => $request->session()->get('user_id')])->get();
//        print_r($data["info"]);
        $data["land_info"] = Landscaper::where(["user_id" => $request->session()->get('user_id')])->get();
        $bookService_obj = new BookService;
        $paymentacc_obj = new PaymentAccounts();
        $data["paymentacc_obj"] = $paymentacc_obj;
        $data["services_req"] = $bookService_obj->getServiceRequest($request);
        $data["services_pend"] = $bookService_obj->getServicePending($request);
        $data["total_revenue"] = $bookService_obj->getTotalRevenue($request);
        $data['services'] = Service::all();
        if (session("user_id") != "" && session("profile_id") == 3) {
            $user_id = session("user_id");
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
            $data["notification_status"] = $bookService_obj->notificationStatusLandscaper();
        }
        
        $weather = [];
        $user_latitude = '';
        $user_longitude = '';
        
        if(isset($data["info"][0]->address) && $data["info"][0]->address != ''){
        $formattedAddr1 = str_replace(' ', '+', $data["info"][0]->address);

            $arrContextOptions2 = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );

            $geocodeFromAddr2 = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyC1XtsegzOIIBaqGL0QugHFIaL2pvp8f8A&address=' . $formattedAddr1 . '&sensor=false', false, stream_context_create($arrContextOptions2));
            $maps_result2 = json_decode($geocodeFromAddr2);


            if (!empty($maps_result2)) {
                if ($maps_result2->status != 'OVER_QUERY_LIMIT') {
                    if (isset($maps_result2->results[0]->geometry->location->lat))
                        $user_latitude = $maps_result2->results[0]->geometry->location->lat;
                    if (isset($maps_result2->results[0]->geometry->location->lng))
                        $user_longitude = $maps_result2->results[0]->geometry->location->lng;
                }
            }
            
            $home_con_obj = new HomeController();
            
            $weather = $home_con_obj->callYahooWeather($user_latitude,$user_longitude);
        }
        
        $data["weather"] = $weather;
        $data["lat"] = $user_latitude;
        $data["lng"] = $user_longitude;
        
        return view('Home.landscaper-dashboard', $data);
    }

    public function addLandscapper(Request $request) {
        $this->validate($request, [
            "first_name" => "required",
            "last_name" => "required",
            "dob" => "required",
            "street" => "required",
            "email" => "required|email|unique:users,username",
            "tel" => "required",
            "password" => "required|confirmed",
            "password_confirmation" => "required",
            "drivers_license" => "required",
            "ssn_no" => "required|digits:9"
        ]);
        $address = $request->city . ", " . $request->state . ", " . $request->country;
        $longitude = '';
        $latitude = '';
        if ($address != "") {
//            $ch = curl_init();
//            $url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false';
//            $url = htmlspecialchars($url);           
//            // Disable SSL verification
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
//            // Will return the response, if false it print the response
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            // Set the url
//            curl_setopt($ch, CURLOPT_URL, $url);
//            // Execute
//          $maps_result = json_decode(curl_exec($ch)); 
//       echo "<pre>";   print_r($maps_result->status);

            $formattedAddr = str_replace(' ', '+', $address);
            $arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );

            $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyC1XtsegzOIIBaqGL0QugHFIaL2pvp8f8A&address=' . $formattedAddr . '&sensor=false', false, stream_context_create($arrContextOptions));
            $maps_result = json_decode($geocodeFromAddr);

            if (!empty($maps_result->status)) {
                if ($maps_result->status != 'OVER_QUERY_LIMIT') {
                    if (isset($maps_result->results[0]->geometry->location->lat))
                        $latitude = $maps_result->results[0]->geometry->location->lat;
                    if (isset($maps_result->results[0]->geometry->location->lng))
                        $longitude = $maps_result->results[0]->geometry->location->lng;
                }
            }
        }
//        print_r($longitude);"<br>".print_r($latitude);die;

        // Upload drivers license
        $drivers_license = "";
        if (Input::hasFile('drivers_license') && Input::file('drivers_license')->isValid()) {
            $avatar = $request->file("drivers_license");
            $drivers_license = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
            $avatar->move("uploads/drivers_license/", $drivers_license);
        }

        session([
            "temp_first_name" => $request->first_name,
            "temp_last_name" => $request->last_name,
            "temp_dob" => date("Y-m-d", strtotime($request->dob)),
            "temp_street_address" => $request->street,
            "temp_email" => $request->email,
            "temp_phone_number" => $request->tel,
            "temp_password" => $request->password,
            "temp_city" => $request->city,
            "temp_state" => $request->state,
            "temp_country" => 80,
            "temp_latitude" => $latitude,
            "temp_longitude" => $longitude,
            "temp_drivers_license" => $drivers_license,
            "temp_ssn_no" => $request->ssn_no
        ]);

        $data["services"] = Service::all();
        return view("Home.register-step-one", $data);
    }

    public function addLandscapperMobile(Request $request) {
        $sql = DB::select("SELECT * FROM landscapers WHERE user_id = " . session("user_id"));
        if (empty($sql)) {
            $profile = UserDetail::where('user_id', session("user_id"))->first();
            $data['address'] = $profile->address;
            $data['name'] = $profile->first_name . " " . $profile->last_name;
            $data["services"] = Service::all();
            return view("Home.register-step-one-mobile", $data);
        } else {
            return redirect()->route("landscaper-profile");
        }
    }

    public function addLandscapperFinal(Request $request) {
        $serverIds = $request->service_id;

        $user = new User();
        $user->username = session("temp_email");
        $user->password = md5(session("temp_password"));
        $user->drivers_license = session('temp_drivers_license');
        $user->ssn_no = session('temp_ssn_no');
        $user->active = 1;
        $user->profile_id = 3;
        $user->user_type = "Landscaper";
        $user->save();


        $user_id = $user->id;
        $fileName1 = "";
        if (Input::hasFile('avter_pic') && Input::file('avter_pic')->isValid()) {
            $avatar = $request->file("avter_pic");
            $fileName1 = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
            $avatar->move("uploads/profile_picture/", $fileName1);
        }
        $profile = new UserDetail();
        $profile->first_name = session("temp_first_name");
        $profile->last_name = session("temp_last_name");
        $profile->date_of_birth = session("temp_dob");
        ;
        $profile->email = session("temp_email");
        $profile->phone_number = session("temp_phone_number");
        $profile->address = session("temp_street_address");
        $profile->user_id = $user_id;
        $profile->city = session("temp_city");
        $profile->state = session("temp_state");
        $profile->country = session("temp_country");
        $profile->profile_image = $fileName1;
        $profile->save();

        $fileName = "";
        if (Input::hasFile('profile_picture') && Input::file('profile_picture')->isValid()) {
            $avatar = $request->file("profile_picture");
            $fileName = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
            $avatar->move("uploads/services/", $fileName);
        }

        if (!empty($serverIds)) {
            foreach ($serverIds as $service_id) {

                $landscaper = new Landscaper();
                $landscaper->name = $request->provider_name;
                $landscaper->user_id = $user_id;
                $landscaper->service_id = $service_id;
                $landscaper->location = session("temp_city") . ", " . session("temp_state") . ", " . session("temp_country");
                $landscaper->description = $request->description;
                $landscaper->profile_image = $fileName;
                $landscaper->latitude = session("temp_latitude");
                $landscaper->longitude = session("temp_longitude");
                $landscaper->distance = $request->provider_distance;


                $landscaper->save();

                $landscaper_id = $landscaper->id;

                $landscaper_obj = new Landscaper;

                if ($service_id == 1) {
                    $landscaper_obj->addLawnMawning($request, $landscaper_id, $service_id);
                }
                if ($service_id == 2) {
                    $landscaper_obj->addLeafRemoval($request, $landscaper_id, $service_id);
                }
                if ($service_id == 3) {
                    $landscaper_obj->addLawnTreatment($request, $landscaper_id, $service_id);
                }
                if ($service_id == 4) {
                    $landscaper_obj->addAeration($request, $landscaper_id, $service_id);
                }
                if ($service_id == 5) {
                    $landscaper_obj->addSprinklerWinterizing($request, $landscaper_id, $service_id);
                }
                if ($service_id == 6) {
                    $landscaper_obj->addPoolCleaning($request, $landscaper_id, $service_id);
                }
                if ($service_id == 7) {
                    $landscaper_obj->addSnowRemoval($request, $landscaper_id, $service_id);
                }

                $landscaper_obj->addServiceDays($request, $landscaper_id, $service_id);

                $landscaper_obj->addRecurringServices($request, $landscaper_id, $service_id);
            }
        }

        $request->session()->flush();
//        session(["check" => 1]);
        $request->session()->flash('msg', 'Provider Details & Service Details added successfully.');
        return redirect()->route("user-login");
    }

    public function addLandscapperFinalMobile(Request $request) {
        $serverIds = $request->service_id;
        $profile = UserDetail::where('user_id', session("user_id"))->first();

        $fileName1 = "";
        if (Input::hasFile('avter_pic') && Input::file('avter_pic')->isValid()) {
            $avatar = $request->file("avter_pic");
            $fileName1 = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
            $avatar->move("uploads/profile_picture/", $fileName1);
        }
        $fileName = "";
        if (Input::hasFile('profile_picture') && Input::file('profile_picture')->isValid()) {
            $avatar = $request->file("profile_picture");
            $fileName = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
            $avatar->move("uploads/services/", $fileName);
        }

        $data['address'] = $profile->address;
        $longitude = '';
        $latitude = '';
        if ($data['address'] != "") {
            $formattedAddr = str_replace(' ', '+', $data['address']);
            $arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );

            $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyC1XtsegzOIIBaqGL0QugHFIaL2pvp8f8A&address=' . $formattedAddr . '&sensor=false', false, stream_context_create($arrContextOptions));
            $maps_result = json_decode($geocodeFromAddr);

            if (!empty($maps_result->status)) {
                if ($maps_result->status != 'OVER_QUERY_LIMIT') {
                    if (isset($maps_result->results[0]->geometry->location->lat))
                        $latitude = $maps_result->results[0]->geometry->location->lat;
                    if (isset($maps_result->results[0]->geometry->location->lng))
                        $longitude = $maps_result->results[0]->geometry->location->lng;
                }
            }
        }

        if (!empty($serverIds)) {
            foreach ($serverIds as $service_id) {
                $sql = DB::update("UPDATE user_details SET profile_image ='" . $fileName1 . "' WHERE user_id =" . session("user_id"));
                $landscaper = new Landscaper();
                $landscaper->name = $request->provider_name;
                $landscaper->user_id = session("user_id");
                $landscaper->service_id = $service_id;
                $landscaper->location = $profile->address;
                $landscaper->description = $request->description;
                $landscaper->profile_image = $fileName;
                $landscaper->latitude = $latitude;
                $landscaper->longitude = $longitude;
                $landscaper->distance = $request->provider_distance;
                $landscaper->save();

                $landscaper_id = $landscaper->id;

                $landscaper_obj = new Landscaper;

                if ($service_id == 1) {
                    $landscaper_obj->addLawnMawning($request, $landscaper_id, $service_id);
                }
                if ($service_id == 2) {
                    $landscaper_obj->addLeafRemoval($request, $landscaper_id, $service_id);
                }
                if ($service_id == 3) {
                    $landscaper_obj->addLawnTreatment($request, $landscaper_id, $service_id);
                }
                if ($service_id == 4) {
                    $landscaper_obj->addAeration($request, $landscaper_id, $service_id);
                }
                if ($service_id == 5) {
                    $landscaper_obj->addSprinklerWinterizing($request, $landscaper_id, $service_id);
                }
                if ($service_id == 6) {
                    $landscaper_obj->addPoolCleaning($request, $landscaper_id, $service_id);
                }
                if ($service_id == 7) {
                    $landscaper_obj->addSnowRemoval($request, $landscaper_id, $service_id);
                }

                $landscaper_obj->addServiceDays($request, $landscaper_id, $service_id);

                $landscaper_obj->addRecurringServices($request, $landscaper_id, $service_id);
            }
        }

        $request->session()->flash('msg', 'Provider Details & Service Details added successfully.');
        return redirect()->route("landscaper-profile");
    }

    public function editUser(Request $request) {
        $profile = UserDetail::where('user_id', session("user_id"))->first();
        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->phone_number = $request->tel;
        $profile->date_of_birth = date("Y-m-d", strtotime($request->dob));

        $profile->save();
        session()->flash("msg", "Profile Updated Successfully");
        return redirect()->route("user-my-profile");
    }

    public function editPassword(Request $request) {
        $update = 'UPDATE users SET password="' . md5($request->password) . '" WHERE id=' . $request->user_id . '';
        $update = DB::select($update);

        session()->flash("msg", "Password Updated Successfully");
        return redirect()->route("user-my-profile");
    }

    public function addAddress(Request $request) {
        $addressbook = new AddressBook();

        $addressbook->user_id = $request->user_id;
        $addressbook->name = $request->addressbook_name;
        $addressbook->address = $request->addressbook_address;
        $addressbook->city = $request->addressbook_city;
        $addressbook->country = $request->addressbook_country;
        $addressbook->contact_number = $request->addressbook_contact;
        $addressbook->email_address = $request->addressbook_email;

        $addressbook->save();
        session()->flash("msg", "Address Successfully Added");
        return redirect()->route("user-my-profile");
    }

    public function editAddress(Request $request) {
        $update = 'UPDATE address_books SET name="' . $request->editaddressbook_name . '",address="' . $request->editaddressbook_address . '",city="' . $request->editaddressbook_city . '",country="' . $request->editaddressbook_country . '",contact_number="' . $request->editaddressbook_contact . '",email_address="' . $request->editaddressbook_email . '" WHERE id=' . $request->address_id . '';
        $update = DB::update($update);

        session()->flash("msg", "Address Successfully Updated");
        return redirect()->route("user-my-profile");
    }

    public function addNewService(Request $request) {
        $data['add_count'] = $request->add_count;
        $id = explode(',', $request->service_id_hdn);
        $id1 = array_unique($id);
        $data['service_id_hdn'] = implode(",", $id1);

        $select = 'SELECT * FROM services WHERE id NOT IN (' . $data['service_id_hdn'] . ')';
        $data["services"] = DB::select($select);
        return view("Home.addnewservice", $data);
    }

    public function testFunction() {
        $test = new TestModel();
        $data = $test->testFunction();
        return view("Home.test", $data);
    }

    public function privacyPolicy() {


        $data['privacy_content'] = DB::select("SELECT content FROM `privacy_policy` ");


        return view("Home.privacy_policy", $data);
    }

    public function get_customer_faq() {
        $profile_id = 2;
        $data['faq_content'] = DB::select("SELECT * FROM `faq` WHERE profile_id=" . $profile_id);
        //$data['profile_id'] = $profile_id;

        return view("Home.get_customer_faq", $data);
    }

    public function get_provider_faq() {
        $profile_id = 3;
        $data['faq_content'] = DB::select("SELECT * FROM `faq` WHERE profile_id=" . $profile_id);
        //$data['profile_id'] = $profile_id;

        return view("Home.get_provider_faq", $data);
    }

    public function faq(Request $request) {
        $profile_id = 2;
        if ($request->profile_id == 3) {
            $profile_id = 3;
        }

        $data['faq_content'] = DB::select("SELECT * FROM `faq` WHERE profile_id=" . $profile_id);
        $data['profile_id'] = $profile_id;

        return view("Home.faq", $data);
    }

    public function why_work_with_us_view() {


        $data['reasons_content'] = DB::select("SELECT reasons FROM `why_work_with_us` ");


        return view("Home.why_work_with_us", $data);
    }

    public function lawn_mowing_tips_view() {


        $data['tips_content'] = DB::select("SELECT tips FROM `lawn_mowing_tips` ");


        return view("Home.lawn_mowing_tips", $data);
    }

    public function terms_services_view() {


        $data['terms_content'] = DB::select("SELECT content FROM `terms_services` ");


        return view("Home.terms_services", $data);
    }

    public function about_us_view() {


        $data['about_content'] = DB::select("SELECT content FROM `about_us` ");


        return view("Home.about_us", $data);
    }

    public function add_contact(Request $request) {

        DB::table("contacts")->insert(['email' => $request->email,
            'name' => $request->name,
            'description' => $request->textArea]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['description'] = $request->textArea;

        Log::info("Request cycle without Queues started");

        Mail::send('Home.emailcontent', ['data' => $data], function ($message) {
            $message->subject("Contact Us");
            $message->from('admin@seazoned.com', 'Admin Seazoned');
            $message->to('info@seazoned.com');

            Log::info("End of Email Processing....");
        });
        Log::info("Request cycle without Queues finished");
        return redirect()->route("home");
    }

    public function getDateTimeByTimezone(Request $request) {
        if ($request->timezone != "") {
            session(['current_timezone' => $request->timezone]);
            echo $request->timezone;
        } else {
            session(['current_timezone' => date('d-m-Y H:i:s')]);
        }
    }

    public function get_terms_conditions() {
        return view('Home.terms_conditions');
    }

    public function get_terms_conditions_provider() {
        return view('Home.terms_conditions_provider');
    }

    public function both_terms_condition(Request $request) {
        $profile_id = 2;

        if ($request->input('profile_id') == 3) {
            $profile_id = $request->profile_id;
        }
        $data['profile_id'] = $profile_id;
        return view('Home.both_terms_condition', $data);
    }

    public function buildBaseString($baseURI, $method, $params) {

        $r = array();
        ksort($params);
        foreach ($params as $key => $value) {
            $r[] = "$key=" . rawurlencode($value);
        }
        return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }

    public function buildAuthorizationHeader($oauth) {

        $r = 'Authorization: OAuth ';
        $values = array();
        foreach ($oauth as $key => $value) {
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        }
        $r .= implode(', ', $values);
        return $r;
    }

    public function callYahooWeather($lat, $lng) {

        $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
        $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
        
        if ($protocol == 'http') {
            $url = 'http://weather-ydn-yql.media.yahoo.com/forecastrss';
        } else {
            $url = 'https://weather-ydn-yql.media.yahoo.com/forecastrss';
        }
        $app_id = 'QZYW3s5i';
        $consumer_key = 'dj0yJmk9b01UcTRRRjFLbnRUJnM9Y29uc3VtZXJzZWNyZXQmc3Y9MCZ4PWZh';
        $consumer_secret = '7b6d14d662288371cce993f2660dc237dbe00288';
        $query = array(
//            'location' => 'sunnyvale,ca',
            'lat' => $lat,
            'lon' => $lng,
            'u' => 'f',
            'format' => 'json',
        );
        $oauth = array(
            'oauth_consumer_key' => $consumer_key,
            'oauth_nonce' => uniqid(mt_rand(1, 1000)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0'
        );
        $base_info = $this->buildBaseString($url, 'GET', array_merge($query, $oauth));
        $composite_key = rawurlencode($consumer_secret) . '&';
        $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;
        $header = array(
            $this->buildAuthorizationHeader($oauth),
            'Yahoo-App-Id: ' . $app_id
        );
        $options = array(
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_HEADER => false,
            CURLOPT_URL => $url . '?' . http_build_query($query),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        );
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);
//        print_r($response);
        $return_data = json_decode($response, true);
//        print_r($return_data);
        return $return_data;
    }

}
