<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\User;
use App\UserDetail;
use App\AddressBook;
use App\ServiceImages;
use App\BookService;
use App\ServicePrice;
use App\PaymentAccounts;
use App\Service;
use App\Country;
use App\Landscaper;
use App\ApiFunctions;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Mail;
use Log;
use App\Services\JWT;
use URL;
use File;
use App\Classes\PayPal;
use App\Classes\PaypalPro;
use App\Classes\PayPalMass;

//use App\Http\Controllers\ServiceController;

Class ApiController extends Controller {

    public function authenticate(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $input = Input::all();
        $landscaper_arr = [];
        $landscaper_str = '';
        $service_flag = 0;
        if (isset($input['username'], $input['password'], $input['profile_id'])) {
            $input['username'] = urldecode($input['username']);
            $input['password'] = urldecode($input['password']);

            if ($input['username'] != "" && $input['password'] != "") {
                $input['password'] = md5($input['password']);
                $profile_id = $input['profile_id'];


                $sql = "SELECT ud.*,u.id as user_primary_id,u.profile_id,u.username,u.password,u.user_type,u.active, u.ssn_no, u.drivers_license "
                        . "FROM user_details ud LEFT JOIN users u ON(u.id=ud.user_id) "
                        . "WHERE u.username = '" . $input['username'] . "' AND u.password = '" . $input['password'] . "' "
                        . "AND u.profile_id = '" . $profile_id . "' AND u.active = 1";

                $data = DB::select($sql);

                if (count($data) == 1) {
                    $jwt = new JWT();
                    $secret = $jwt->getSecretKey();
                    $token = JWT::encode($data[0], $secret);

                    if ($data[0]->user_type == "Landscaper") {
                        $sql_l = "SELECT id FROM landscapers WHERE user_id = " . $data[0]->user_id;
                        $data_l = DB::select($sql_l);
                        $data[0]->service_count = count($data_l);

                        if ($data[0]->service_count == 0) {
                            $service_flag = 1;
                            $data[0]->provider_status = 0;
                        } else {

                            foreach ($data_l as $val) {
                                $landscaper_arr[] = $val->id;
                            }

                            $landscaper_str = implode(',', $landscaper_arr);

                            if ($landscaper_str != "") {
                                $sql_h = 'SELECT count(id) as count FROM service_prices WHERE landscaper_id IN (' . $landscaper_str . ')';
                                $data_h = DB::select($sql_h);
                                $sql_q = 'SELECT count(id) as count FROM service_times WHERE landscaper_id IN (' . $landscaper_str . ')';
                                $data_q = DB::select($sql_q);
                                $data[0]->provider_status = ($data_h[0]->count > 0 && $data_q[0]->count > 0) ? 1 : 0;
                                ;
//                                    ($data_h[0]->count > 0) ? 1 : 0;
                            }
                        }
                    }

                    if ($data[0]->profile_image != "")
                        $data[0]->profile_image = url("uploads/profile_picture/" . $data[0]->profile_image);
                    if ($data[0]->drivers_license != "")
                        $data[0]->drivers_license = url("uploads/drivers_license/" . $data[0]->drivers_license);

                    $response['token'] = $token;
                    $response['data'] = $data[0];
                    $response['success'] = 1;
                    $response['msg'] = "Login Successful.";
                }else {
                    $response['msg'] = "Please provide valid credentials.";
                }
            } else {
                $response['msg'] = "Please fill up all the fields";
            }
        } else {
            $response['msg'] = "Please fill up all the fields";
        }

        return response()->json($response);
    }

    public function userRegistration(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $source = urldecode($request->source);

        if ($request->email != "" && $request->password != "" && $request->first_name != "" && $request->last_name != "" && $request->tel != "" && $request->dob != "" && $request->street != "" && $request->city != "" && $request->state != "" && $request->country != "") {

            $user = new User();
            $profile = new UserDetail();
            $addr_book = new AddressBook();

            $num = $user->check_user_email(urldecode($request->email));

            if ($num == 0) {
                $user->username = urldecode($request->email);
                $user->password = md5(urldecode($request->password));
                $user->active = 1;
                $user->profile_id = 2;
                $user->user_type = "Users";

                if ($user->save()) {

                    $profile->first_name = urldecode($request->first_name);
                    $profile->last_name = urldecode($request->last_name);
                    $profile->email = urldecode($request->email);
                    $profile->phone_number = urldecode($request->tel);
                    $profile->date_of_birth = date("Y-m-d", strtotime(urldecode($request->dob)));
                    $profile->address = urldecode($request->street);
                    $profile->city = urldecode($request->city);
                    $profile->state = urldecode($request->state);
                    $profile->country = urldecode($request->country);
                    $profile->user_id = $user->id;

                    if ($source == 'android') {
                        if (Input::hasFile('profile_picture') && Input::file('profile_picture')->isValid()) {
                            $avatar = $request->file("profile_picture");
                            $fileName = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
                            $avatar->move("uploads/profile_picture/", $fileName);
                            $profile->profile_image = $fileName;
                        }
                    } else if ($source == 'iphone') {
                        if (isset($request->profile_picture) && $request->profile_picture != "") {
                            $pos = strpos($request->profile_picture, ';');
                            $type = explode('/', explode(':', substr($request->profile_picture, 0, $pos))[1])[1];

                            $destinationPath = "uploads/profile_picture/";
                            $fileName = rand(1111, 9999) . "_" . time() . "_iphone." . $type;
                            $target_path = $destinationPath . $fileName;

                            file_put_contents($target_path, base64_decode($request->profile_picture));
                            $profile->profile_image = $fileName;
                        }
                    }

                    $addr_book->user_id = $user->id;
                    $addr_book->name = urldecode($request->first_name) . ' ' . urldecode($request->last_name);
                    $addr_book->address = urldecode($request->street);
                    $addr_book->city = urldecode($request->city);
                    $addr_book->state = urldecode($request->state);
                    $addr_book->country = urldecode($request->country);
                    $addr_book->contact_number = urldecode($request->tel);
                    $addr_book->email_address = urldecode($request->email);
                    $addr_book->primary_address = 1;
                }

                if ($profile->save() && $addr_book->save()) {
                    $response['data'] = [];
                    $response['success'] = 1;
                    $response['msg'] = "User Registration Successful";
                } else {
                    $response['msg'] = "User Registration Failed";
                }
            } else {
                $response['msg'] = "User Email Already Exist.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function landscaperRegistration(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->first_name != "" && $request->last_name != "" && $request->email != "" && $request->tel != "" && $request->street != "" && $request->city != "" && $request->state != "" && $request->password != "" && $request->latitude != "" && $request->longitude != "" && $request->ssn_no != "" && $request->source != "") {

            $user = new User();
            $profile = new UserDetail();
            $addr_book = new AddressBook();
            $landscaper = new Landscaper();

            $num = $user->check_user_email(urldecode($request->email));

            if ($num == 0) {

                // Upload drivers license
                $user->drivers_license = "";
                $source = urldecode($request->source);
                if (isset($source) && $source != "") {
                    if ($source == 'android') {
                        if (is_uploaded_file($_FILES['drivers_license']['tmp_name'])) {
                            $fileName = rand(1111, 9999) . "_" . time() . "_" . $_FILES['drivers_license']['name'];
                            if (move_uploaded_file($_FILES['drivers_license']['tmp_name'], "uploads/drivers_license/" . $fileName)) {
                                $user->drivers_license = $fileName;                        
                            }
                        }
                    } else if ($source == 'iphone') {
                        if (isset($request->drivers_license) && $request->drivers_license != "") {

                            $destinationPath = "uploads/drivers_license/";
                            $fileName = rand(1111, 9999) . "_" . time() . "_iphone.jpg";
                            $target_path = $destinationPath . $fileName;

                            file_put_contents($target_path, base64_decode($request->drivers_license));
                            $user->drivers_license = $fileName;
                        }
                    }
                }

                $user->username = urldecode($request->email);
                $user->password = md5(urldecode($request->password));
                $user->ssn_no = $request->ssn_no;
                $user->active = 1;
                $user->profile_id = 3;
                $user->user_type = "Landscaper";

                if ($user->save()) {

                    $profile->first_name = urldecode($request->first_name);
                    $profile->last_name = urldecode($request->last_name);
                    $profile->email = urldecode($request->email);
                    $profile->phone_number = urldecode($request->tel);
                    $profile->address = urldecode($request->street);
                    $profile->city = urldecode($request->city);
                    $profile->state = urldecode($request->state);
                    $profile->country = urldecode($request->country);
                    $profile->user_id = $user->id;
                }

                if ($profile->save()) {
                    $response['data'] = ["user_id" => $user->id, 'latitude' => $request->latitude, 'longitude' => $request->longitude,];
                    $response['success'] = 1;
                    $response['msg'] = "Provider Details added successfully";
                } else {
                    $response['msg'] = "Provider Details failed successfully";
                }
            } else {
                $response['msg'] = "User Email Already Exist.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function addLanscaper(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->user_id != "") {
            $serverIds_json = $request->service_id;
            $serverIds = json_decode($serverIds_json, TRUE);
            if (!empty($serverIds)) {
                foreach ($serverIds as $one_serverid) {
                    $lanscaper_details = $this->addLanscaperDetailsOld($request, $one_serverid['index']);

                    $landscaper_id = $lanscaper_details['data']['landscaper_id'];
                    $service_id = $lanscaper_details['data']['service_id'];

                    if ($service_id == 1)
                        $this->addLawnMawningService($request, $landscaper_id, $service_id);
                    if ($service_id == 2)
                        $this->addLeafRemovalService($request, $landscaper_id, $service_id);
                    if ($service_id == 3)
                        $this->addLawnTreatmentService($request, $landscaper_id, $service_id);
                    if ($service_id == 4)
                        $this->addAerationService($request, $landscaper_id, $service_id);
                    if ($service_id == 5)
                        $this->addSprinklerWinterizingService($request, $landscaper_id, $service_id);
                    if ($service_id == 6)
                        $this->addPoolCleaningService($request, $landscaper_id, $service_id);
                    if ($service_id == 7)
                        $this->addSnowRemovalService($request, $landscaper_id, $service_id);

                    $ServiceDays = $this->addServiceDays($request, $landscaper_id, $service_id);
                    $RecurringServices = $this->addRecurringServices($request, $landscaper_id, $service_id);
                }

                if ($RecurringServices['success'] == 1) {
                    $response['data'] = [];
                    $response['success'] = 1;
                    $response['msg'] = "Data Saved successfully";
                } else {
                    $response['data'] = [];
                    $response['success'] = 0;
                    $response['msg'] = "Data Save Failed";
                }
            } else {
                $response['msg'] = "Please fill up all the fields.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function addLanscaperDetailsOld(Request $request, $service_id) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->user_id != "") {

            $landscaper = new Landscaper();

            $landscaper->user_id = urldecode($request->user_id);
            $landscaper->service_id = $service_id;

            $landscaper->name = urldecode($request->provider_name);
            $landscaper->description = urldecode($request->description);

            $landscaper->latitude = $request->latitude;
            $landscaper->longitude = $request->longitude;

            $source = urldecode($request->source);
            if (isset($source) && $source != "") {
                if ($source == 'android') {
                    if (Input::hasFile('feature_image') && Input::file('feature_image')->isValid()) {
                        $avatar = $request->file("feature_image");
                        $fileName = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
                        $avatar->move("uploads/services/", $fileName);
                        $landscaper->profile_image = $fileName;
                    }
                } else if ($source == 'iphone') {
                    if (isset($request->feature_image) && $request->feature_image != "") {
                        $pos = strpos($request->feature_image, ';');
                        $type = explode('/', explode(':', substr($request->feature_image, 0, $pos))[1])[1];

                        $destinationPath = "uploads/services/";
                        $fileName = rand(1111, 9999) . "_" . time() . "_iphone." . $type;
                        $target_path = $destinationPath . $fileName;

                        file_put_contents($target_path, base64_decode($request->feature_image));
                        $landscaper->profile_image = $fileName;
                    }
                }
            }

            if ($landscaper->save()) {
                $response['data'] = [
                    "landscaper_id" => $landscaper->id,
                    "service_id" => $landscaper->service_id,
                ];
                $response['success'] = 1;
                $response['msg'] = "Provider Details added successfully";
            } else {
                $response['msg'] = "Provider Details failed";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return $response;
        //return response()->json($response);
    }

    public function addProviderDetails(Request $request) {
        $response = [
            'data' => [],
            'success' => 0,
            'msg' => ''
        ];

        if ($request->user_id != "") {
            $latitude = '';
            $longitude = '';
            $userId = $request->user_id;
            $provider_name = urldecode($request->provider_name);
            $description = urldecode($request->description);
            $latitude = urldecode($request->latitude);
            $longitude = urldecode($request->longitude);
            $distance_provided = urldecode($request->distance_provided);

            DB::table('landscapers')->where('user_id', $userId)->update(['name' => $provider_name, 'description' => $description, 'latitude' => $latitude, 'longitude' => $longitude, 'distance' => $distance_provided]);

            $sql = "SELECT id,service_id FROM landscapers WHERE user_id = " . $userId;
            $result = DB::select($sql);

            if (!empty($result)) {
                foreach ($result as $row) {
                    $landscaperId = $row->id;
                    $serviceId = $row->service_id;

                    $ServiceDays = $this->addServiceDays($request, $landscaperId, $serviceId);

//                    if ($ServiceDays['success'] == 1) {
//                        $RecurringServices = $this->addRecurringServices($request, $landscaperId, $serviceId);

                    if ($ServiceDays['success'] == 1) {
                        $response['success'] = 1;
                        $response['msg'] = "Provider Details Saved";
                    } else {
                        $response['msg'] = "ServiceDays Not Inserted";
                    }
//                    } else {
//                        $response['msg'] = "Service Days Not Inserted";
//                    }
                }
            } else {
                $response['msg'] = "Provider Details Not Inserted";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function addLanscaperDetails(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->user_id != "" && $request->service_id != "" && $request->service_id <= 7) {

            $landscaper = new Landscaper();
            $landscaper->user_id = $request->user_id;
            $landscaper->service_id = $request->service_id;

            $sql = DB::select("SELECT * FROM landscapers WHERE user_id =" . $request->user_id . " AND latitude != '' AND longitude != ''");

            if (!empty($sql)) {
                $landscaper->description = $sql[0]->description;
                $landscaper->latitude = $sql[0]->latitude;
                $landscaper->longitude = $sql[0]->longitude;
                $landscaper->name = $sql[0]->name;
                $landscaper->distance = $sql[0]->distance;
            }

            $sql2 = DB::select("SELECT * FROM landscapers WHERE user_id =" . $request->user_id);

            if (empty($sql2)) {
                $sql1 = DB::select("SELECT * FROM user_details WHERE user_id = " . $request->user_id);
                if ($sql1[0]->address != "") {

                    $formattedAddr = str_replace(' ', '+', $sql1[0]->address);
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
                        $landscaper->latitude = $latitude;
                        $landscaper->longitude = $longitude;
                        $landscaper->distance = 15;
                        $landscaper->description = 'N/A';
                        $landscaper->name = 'N/A';
                    }
                }
            }

            $landscaper->save();

            $landscaper_id = $landscaper->id;
            $service_id = $landscaper->service_id;
            $price_arr = [];
            $service_arr = [];

            if ($landscaper_id != "" && $service_id != "") {
//                $prices = DB::select("select * from service_prices where landscaper_id in (select id from landscapers where user_id =".$request->user_id.")");
////                 print_r($service_days);die;
//                if(!empty($prices)){
//                    
//                    foreach($prices as $one_price){
//                        $price_arr[$one_price->service_frequency] = $one_price->discount_price;
//                    }
//
//                    foreach ($price_arr as $index => $val) {
//                        $price_data[] = array(
//                            'landscaper_id' => $landscaper_id,
//                            'service_id' => $service_id,
//                            'service_frequency' => $index,
//                            'discount_price' => $val
//                        );
//                    }                
//    
//                    $insert_one = DB::table('service_prices')->insert($price_data);
//                }

                $service_days = DB::select("select * from service_times where landscaper_id in (select id from landscapers where user_id =" . $request->user_id . ")");
                if (!empty($service_days)) {

                    foreach ($service_days as $days) {

                        $service_day[$days->service_day]['start_time'] = $days->start_time;
                        $service_day[$days->service_day]['end_time'] = $days->end_time;
                    }

                    foreach ($service_day as $index => $val) {

                        $service_data[] = array(
                            'landscaper_id' => $landscaper_id,
                            'service_id' => $service_id,
                            'service_day' => $index,
                            'start_time' => $val['start_time'],
                            'end_time' => $val['end_time']
                        );

//                        print_r($service_data);
//                        exit;
                    }

                    $insert_two = DB::table('service_times')->insert($service_data);
                }
            }

            if ($service_id == 1)
                $response = $this->addLawnMawningService($request, $landscaper_id, $service_id);
            elseif ($service_id == 2)
                $response = $this->addLeafRemovalService($request, $landscaper_id, $service_id);
            elseif ($service_id == 3)
                $response = $this->addLawnTreatmentService($request, $landscaper_id, $service_id);
            elseif ($service_id == 4)
                $response = $this->addAerationService($request, $landscaper_id, $service_id);
            elseif ($service_id == 5)
                $response = $this->addSprinklerWinterizingService($request, $landscaper_id, $service_id);
            elseif ($service_id == 6)
                $response = $this->addPoolCleaningService($request, $landscaper_id, $service_id);
            elseif ($service_id == 7)
                $response = $this->addSnowRemovalService($request, $landscaper_id, $service_id);
            else
                $response['msg'] = "Invalid Service";

            $this->addRecurringServicesNew($request, $landscaper_id, $service_id);

            $response['data'] = [
                "user_id" => $request->user_id,
                "landscaper_id" => $landscaper->id,
                "service_id" => $landscaper->service_id
            ];
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function addLawnMawningService(Request $request, $landscaper_id, $service_id) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($landscaper_id != "" && $service_id != "" &&
                $request->lawn_acre_limit != "" && $request->lawn_first_acre != "" &&
                $request->lawn_first_grass != "" && $request->lawn_next_acre != "" && $request->lawn_next_grass != "") {

            $acre_limit = urldecode($request->lawn_acre_limit) / 0.25;
            $first_acre = urldecode($request->lawn_first_acre);
            $first_grass = urldecode($request->lawn_first_grass);
            $next_acre = urldecode($request->lawn_next_acre);
            $next_grass = urldecode($request->lawn_next_grass);

            $price = 0;
            $data["service_table"] = [];
            for ($i = 1; $i <= $acre_limit; $i++) {
                $acre = 0.25 * $i;
                if ($i == 1)
                    $price = $first_acre;
                else
                    $price += $next_acre;

                $data["service_table"][$i]["service_id"] = $service_id;
                $data["service_table"][$i]["service_field_value"] = ($acre - 0.25) . ' - ' . $acre;
                $data["service_table"][$i]["service_field_price"] = $price;
                $data["service_table"][$i]["service_field_id"] = 1;
                $data["service_table"][$i]["landscaper_id"] = $landscaper_id;
            }

            $insert_one = DB::table('service_details')->insert($data["service_table"]);

            unset($data);

            $data = [[
            "service_id" => $service_id,
            "service_field_value" => "0 - 6",
            "service_field_price" => $first_grass,
            "service_field_id" => 2,
            "landscaper_id" => $landscaper_id
                ],
                ["service_id" => $service_id,
                    "service_field_value" => ">6",
                    "service_field_price" => $next_grass + $first_grass,
                    "service_field_id" => 2,
                    "landscaper_id" => $landscaper_id]
            ];

            $insert_two = DB::table('service_details')->insert($data);

            if ($insert_one && $insert_two) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Mowing And Edging added successfully";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Mowing And Edging failed";
            }
        } else {
            $response['msg'] = "Please fill up all the fields1.";
        }
        return $response;
        //return response()->json($response);
    }

    public function addLawnTreatmentService(Request $request, $landscaper_id, $service_id) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($landscaper_id != "" && $service_id != "" &&
                $request->lawntreat_acre_limit != "" && $request->lawntreat_first_acre != "" && $request->lawntreat_next_acre != "") {
//            $landscaper_id = urldecode($request->landscaper_id);
//            $service_id = urldecode($request->service_id);

            $acre_limit = urldecode($request->lawntreat_acre_limit) / 0.25;
            $first_acre = urldecode($request->lawntreat_first_acre);
            $next_acre = urldecode($request->lawntreat_next_acre);
            $price = 0;
            $data["service_table"] = [];

            for ($i = 1; $i <= $acre_limit; $i++) {
                $acre = 0.25 * $i;
                if ($i == 1)
                    $price = $first_acre;
                else
                    $price += $next_acre;

                $data["service_table"][$i]["service_id"] = $service_id;
                $data["service_table"][$i]["service_field_value"] = ($acre - 0.25) . ' - ' . $acre;
                $data["service_table"][$i]["service_field_price"] = $price;
                $data["service_table"][$i]["service_field_id"] = 1;
                $data["service_table"][$i]["landscaper_id"] = $landscaper_id;
            }

            $insert_one = DB::table('service_details')->insert($data["service_table"]);

            if ($insert_one) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Lawn Treatment added successfully";
            } else {
                $response['msg'] = "Lawn Treatment failed";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return $response;
        //return response()->json($response);
    }

    public function addSprinklerWinterizingService(Request $request, $landscaper_id, $service_id) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($landscaper_id != "" && $service_id != "") {

            if ($request->winter_acre_limit != "" && $request->winter_first_acre != "" && $request->winter_next_acre != "") {

                $acre_limit = urldecode($request->winter_acre_limit) / 0.25;
                $first_acre = urldecode($request->winter_first_acre);
                $next_acre = urldecode($request->winter_next_acre);
                $price = 0;
                $data = [];

                for ($i = 1; $i <= $acre_limit; $i++) {
                    $acre = 0.25 * $i;
                    if ($i == 1)
                        $price = $first_acre;
                    else
                        $price += $next_acre;

                    $data[$i]["service_id"] = $service_id;
                    $data[$i]["service_field_value"] = ($acre - 0.25) . ' - ' . $acre;
                    $data[$i]["service_field_price"] = $price;
                    $data[$i]["service_field_id"] = 1;
                    $data[$i]["landscaper_id"] = $landscaper_id;
                }

                $insert = DB::table('service_details')->insert($data);
            } else if ($request->winter_zone_limit != "" && $request->winter_first_zone != "" && $request->winter_next_zone != "") {

                $zone_limit = floor(urldecode($request->winter_zone_limit) / 3);
                $first_zone = urldecode($request->winter_first_zone);
                $next_zone = urldecode($request->winter_next_zone);
                $price = 0;
                $data = [];

                for ($i = 1; $i <= $zone_limit; $i++) {
                    if ($i == 1)
                        $price = $first_zone;
                    else
                        $price += $next_zone;

                    $data[$i]["service_id"] = $service_id;
                    $data[$i]["service_field_value"] = (($i * 3) - 3) . ' - ' . ($i * 3);
                    $data[$i]["service_field_price"] = $price;
                    $data[$i]["service_field_id"] = 11;
                    $data[$i]["landscaper_id"] = $landscaper_id;
                }

                $insert = DB::table('service_details')->insert($data);
            } else {
                $response['msg'] = "Please fill up all the fields.";
            }


            if ($insert) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Sprinkler Winterizing added successfully";
            } else {
                $response['msg'] = "Sprinkler Winterizing failed";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return $response;
    }

    public function addAerationService(Request $request, $landscaper_id, $service_id) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($landscaper_id != "" && $service_id != "" &&
                $request->area_acre_limit != "" && $request->area_first_acre != "" && $request->area_next_acre != "") {
//            $landscaper_id = urldecode($request->landscaper_id);
//            $service_id = urldecode($request->service_id);

            $acre_limit = urldecode($request->area_acre_limit) / 0.25;
            $first_acre = urldecode($request->area_first_acre);
            $next_acre = urldecode($request->area_next_acre);
            $price = 0;
            $data["service_table"] = [];

            for ($i = 1; $i <= $acre_limit; $i++) {
                $acre = 0.25 * $i;
                if ($i == 1)
                    $price = $first_acre;
                else
                    $price += $next_acre;

                $data["service_table"][$i]["service_id"] = $service_id;
                $data["service_table"][$i]["service_field_value"] = ($acre - 0.25) . ' - ' . $acre;
                $data["service_table"][$i]["service_field_price"] = $price;
                $data["service_table"][$i]["service_field_id"] = 1;
                $data["service_table"][$i]["landscaper_id"] = $landscaper_id;
            }

            $insert_one = DB::table('service_details')->insert($data["service_table"]);

            if ($insert_one) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Aeration added successfully";
            } else {
                $response['msg'] = "Aeration failed";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return $response;
//        return response()->json($response);
    }

    public function addPoolCleaningService(Request $request, $landscaper_id, $service_id) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($landscaper_id != "" && $service_id != "" &&
                $request->pool_chlorine != "" && $request->pool_saline != "" && $request->pool_spa_hot_tub != "" &&
                $request->pool_inground != "" && $request->pool_above_ground != "" &&
                $request->pool_clear != "" && $request->pool_cloudy != "" && $request->pool_heavy != "") {

//            $landscaper_id = urldecode($request->landscaper_id);
//            $service_id = urldecode($request->service_id);

            $chlorine = urldecode($request->pool_chlorine);
            $saline = urldecode($request->pool_saline);

            $spa_hot_tub = urldecode($request->pool_spa_hot_tub);

            $inground = urldecode($request->pool_inground);
            $above_ground = urldecode($request->pool_above_ground);

            $clear = urldecode($request->pool_clear);
            $cloudy = urldecode($request->pool_cloudy);
            $heavy = urldecode($request->pool_heavy);

            $data = [array(
            'landscaper_id' => $landscaper_id,
            'service_id' => $service_id,
            'service_field_id' => 7,
            'service_field_value' => 'Chlorine',
            'service_field_price' => $chlorine,
                ), array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_field_id' => 7,
                    'service_field_value' => 'Saline',
                    'service_field_price' => $saline,
                ), array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_field_id' => 8,
                    'service_field_value' => 'Yes',
                    'service_field_price' => $spa_hot_tub,
                ), array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_field_id' => 8,
                    'service_field_value' => 'No',
                    'service_field_price' => 0,
                ), array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_field_id' => 9,
                    'service_field_value' => 'Inground',
                    'service_field_price' => $inground,
                ), array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_field_id' => 9,
                    'service_field_value' => 'Above Ground',
                    'service_field_price' => $above_ground,
                ), array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_field_id' => 10,
                    'service_field_value' => 'Relatively Clear',
                    'service_field_price' => $clear,
                ), array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_field_id' => 10,
                    'service_field_value' => 'Moderately Cloudy',
                    'service_field_price' => $cloudy,
                ), array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_field_id' => 10,
                    'service_field_value' => 'Heavy Algae Present',
                    'service_field_price' => $heavy,
            )];

            $insert_one = DB::table('service_details')->insert($data);

            if ($insert_one) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Pool Cleaning added successfully";
            } else {
                $response['msg'] = "Pool Cleaning failed";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return $response;
        //return response()->json($response);
    }

    public function addLeafRemovalService(Request $request, $landscaper_id, $service_id) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($landscaper_id != "" && $service_id != "" &&
                $request->leaf_acre_limit != "" && $request->leaf_first_acre != "" &&
                $request->leaf_next_acre != "" && $request->light != "" && $request->medium != "" && $request->heavy != "" && $request->over_top != "") {

//            $landscaper_id = urldecode($request->landscaper_id);
//            $service_id = urldecode($request->service_id);

            $acre_limit = urldecode($request->leaf_acre_limit) / 0.25;
            $first_acre = urldecode($request->leaf_first_acre);
            $next_acre = urldecode($request->leaf_next_acre);

            $light = urldecode($request->light);
            $medium = urldecode($request->medium);
            $heavy = urldecode($request->heavy);
            $over_top = urldecode($request->over_top);

            $price = 0;
            $data["service_table"] = [];
            for ($i = 1; $i <= $acre_limit; $i++) {
                $acre = 0.25 * $i;
                if ($i == 1)
                    $price = $first_acre;
                else
                    $price += $next_acre;

                $data["service_table"][$i]["service_id"] = $service_id;
                $data["service_table"][$i]["service_field_value"] = ($acre - 0.25) . ' - ' . $acre;
                $data["service_table"][$i]["service_field_price"] = $price;
                $data["service_table"][$i]["service_field_id"] = 1;
                $data["service_table"][$i]["landscaper_id"] = $landscaper_id;
            }

            $insert_one = DB::table('service_details')->insert($data["service_table"]);

            unset($data);

            $data = [
                array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_field_id' => 6,
                    'service_field_value' => 'Light',
                    'service_field_price' => $light,
                ), array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_field_id' => 6,
                    'service_field_value' => 'Medium',
                    'service_field_price' => $medium,
                ), array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_field_id' => 6,
                    'service_field_value' => 'Heavy',
                    'service_field_price' => $heavy,
                ), array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_field_id' => 6,
                    'service_field_value' => 'Over the top',
                    'service_field_price' => $over_top,
                )
            ];

            $insert_two = DB::table('service_details')->insert($data);

            if ($insert_one && $insert_two) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Leaf Removal added successfully";
            } else {
                $response['msg'] = "Leaf Removal failed";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return $response;
//        return response()->json($response);
    }

    public function addSnowRemovalService(Request $request, $landscaper_id, $service_id) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($landscaper_id != "" && $service_id != "" &&
                $request->car_limit != "" && $request->first_car != "" && $request->next_car != "" &&
                $request->straight != "" && $request->circular != "" && $request->incline != "" && $request->front_door != "" && $request->stairs != "" && $request->side_door != "") {

//            $landscaper_id = urldecode($request->landscaper_id);
//            $service_id = urldecode($request->service_id);

            $car_limit = urldecode($request->car_limit) / 2;
            $first_car = urldecode($request->first_car);
            $next_car = urldecode($request->next_car);

            $driveway_type = [
                'Straight' => urldecode($request->straight),
                'Circular' => urldecode($request->circular),
                'Incline' => urldecode($request->incline)
            ];

            $service_type = [
                'Front Door Walk Way' => urldecode($request->front_door),
                'Stairs and Front Landing' => urldecode($request->stairs),
                'Side Door Walk Way' => urldecode($request->side_door)
            ];

            $price = 0;
            $data["service_table"] = [];
            for ($i = 1; $i <= $car_limit; $i++) {
                $car = 2 * $i;
                if ($i == 1)
                    $price = $first_car;
                else
                    $price += $next_car;

                $data["service_table"][$i]["service_id"] = $service_id;
                $data["service_table"][$i]["service_field_value"] = $car;
                $data["service_table"][$i]["service_field_price"] = $price;
                $data["service_table"][$i]["service_field_id"] = 3;
                $data["service_table"][$i]["landscaper_id"] = $landscaper_id;
            }

            $insert_one = DB::table('service_details')->insert($data["service_table"]);

            unset($data);

            foreach ($driveway_type as $value => $price) {
                $data[] = array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_field_id' => 4,
                    'service_field_value' => $value,
                    'service_field_price' => $price,
                );
            }

            $insert_two = DB::table('service_details')->insert($data);

            unset($data);

            foreach ($service_type as $value => $price) {
                $data[] = array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_field_id' => 5,
                    'service_field_value' => $value,
                    'service_field_price' => $price,
                );
            }

            $insert_three = DB::table('service_details')->insert($data);

            if ($insert_one && $insert_two && $insert_three) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Snow Removal added successfully";
            } else {
                $response['msg'] = "Snow Removal failed";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return $response;
        //return response()->json($response);
    }

    public function addServiceDays(Request $request, $landscaper_id, $service_id) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($landscaper_id != "" && $service_id != "" && $request->days != "" && $request->start != "" && $request->end != "") {

            $days_json = $request->days;
            $days = json_decode($days_json, TRUE);

            $start_json = $request->start;
            $start = json_decode($start_json, TRUE);

            $end_json = $request->end;
            $end = json_decode($end_json, TRUE);
            foreach ($days as $key => $day) {
                if ($start[$key]['s'] != "" && $end[$key]['e'] != "") {
                    $data[] = array(
                        'landscaper_id' => $landscaper_id,
                        'service_id' => $service_id,
                        'service_day' => urldecode($day['day']),
                        'start_time' => urldecode($start[$key]['s']),
                        'end_time' => urldecode($end[$key]['e']),
                    );
                }
            }

            $insert_one = DB::table('service_times')->insert($data);

            if ($insert_one) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Service Days added successfully";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Service Days failed";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return $response;
        //return response()->json($response);
    }

    public function addRecurringServices(Request $request, $landscaper_id, $service_id) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($landscaper_id != "" && $service_id != "" && $request->recurring_services != "") {

            $recurring_services = ['Every 7 days', 'Every 10 days', 'Every 14 days', 'Just Once'];
            $rec_services_json = $request->recurring_services;
            $rec_services = json_decode($rec_services_json, TRUE);

            foreach ($recurring_services as $index => $val) {
                $data[] = array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_frequency' => $val,
                    'discount_price' => urldecode($rec_services[$index]['rs'])
                );
            }

            $insert_one = DB::table('service_prices')->insert($data);

            if ($insert_one) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Recurring Services Added Successfully";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Recurring Services Add Failed";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return $response;
        //return response()->json($response);
    }

    public function addRecurringServicesNew(Request $request, $landscaper_id, $service_id) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($landscaper_id != "" && $service_id != "" && $request->recurring_services != "") {

            $recurring_services = ['Every 7 days', 'Every 10 days', 'Every 14 days'];
            $rec_services_json = $request->recurring_services;
            $rec_services = json_decode($rec_services_json, TRUE);

            foreach ($recurring_services as $index => $val) {
                $data[] = array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_frequency' => $val,
                    'discount_price' => urldecode($rec_services[$index]['rs'])
                );
            }

            $insert_one = DB::table('service_prices')->insert($data);

            $static_data[] = array(
                'landscaper_id' => $landscaper_id,
                'service_id' => $service_id,
                'service_frequency' => 'Just Once',
                'discount_price' => 0
            );

            $insert_one_static = DB::table('service_prices')->insert($static_data);

            if ($insert_one && $insert_one_static) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Recurring Services Added Successfully";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Recurring Services Add Failed";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return $response;
        //return response()->json($response);
    }

    public function serviceList() {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $service = new Service();
        $payment_acc_obj = new PaymentAccounts();
        $data = $service->ServiceDetails();

        $percentage = $payment_acc_obj->getPercentage();

        if (count($data) > 0) {
            $response['tax_rate'] = 0;
            $response['percentage'] = $percentage;
            $response['data'] = $data;
            $response['success'] = 1;
            $response['msg'] = count($data) . " services found.";
        } else {
            $response['data'] = [];
            $response['success'] = 0;
            $response['msg'] = "No services found.";
        }
        return response()->json($response);
    }

    public function countryList() {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $country = new Country();
        $data = $country->CountryDetails();

        if (count($data) > 0) {
            $response['data'] = $data;
            $response['success'] = 1;
            $response['msg'] = count($data) . " countries found.";
        } else {
            $response['data'] = [];
            $response['success'] = 0;
            $response['msg'] = "No countries found.";
        }

        return response()->json($response);
    }

    public function providerListByService(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->service_id != "") {

            $service_id = urldecode($request->service_id);
            $providers = DB::select("SELECT l.*, '0' as rating, '0' as usercount FROM landscapers l,user_details ud WHERE l.user_id = ud.user_id AND l.service_id = '$service_id'");

            foreach ($providers as $key => $one_provider) {
                if ($one_provider->profile_image != "")
                    $providers[$key]->profile_image = url("uploads/services/" . $one_provider->profile_image);
            }

            if (count($providers) > 0) {
                $response['data'] = $providers;
                $response['success'] = 1;
                $response['msg'] = count($providers) . " providers found.";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "No providers found.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function providerListByLocation(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->search_location != "") {

            $search_location = urldecode($request->search_location);

            $providers = DB::select("SELECT l.*,'0' as rating, '0' as usercount FROM landscapers l,user_details ud WHERE l.user_id = ud.user_id AND (ud.city LIKE '$search_location%' OR ud.state LIKE '$search_location%' OR ud.country LIKE '$search_location%' OR ud.address LIKE '$search_location%')");

            foreach ($providers as $key => $one_provider) {
                if ($one_provider->profile_image != "")
                    $providers[$key]->profile_image = url("uploads/services/" . $one_provider->profile_image);
            }

            if (count($providers) > 0) {
                $response['data'] = $providers;
                $response['success'] = 1;
                $response['msg'] = count($providers) . " providers found.";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "No providers found.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function providerListByServiceLocation(Request $request) {

        $user_info = $request->userInfo;


        $response = [
            'success' => 0,
            'msg' => ''
        ];


        $user_id = $user_info->user_id;
        $service_id = $request->service_id;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $landscapers = [];
        $radius = 15;
        if ($latitude != "" && $longitude != '' && $service_id != "") {
            $radius_query = "SELECT `id`,distance as distance_provided,
                ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $latitude ) ) + COS( RADIANS( `latitude` ) )
                * COS( RADIANS( $latitude )) * COS( RADIANS( `longitude` ) - RADIANS( $longitude )) ) * 3958.756 AS `distance`
                FROM `landscapers`";

            $nearby = DB::select($radius_query);
            if (!empty($nearby)) {
                foreach ($nearby as $landscapers_obj) {
                    if ($landscapers_obj->id != "" && $landscapers_obj->distance_provided != '') {
                        if ($landscapers_obj->distance <= $landscapers_obj->distance_provided) {
                            $landscapers[] = $landscapers_obj->id;
                        }
                    }
                }

                if (!empty($landscapers)) {
                    $landscapers_id = implode(',', $landscapers);
                }
            }

            if (!empty($landscapers)) {
                $providers = DB::select("SELECT l.id as lanscaper_id,l.user_id as landscaper_user_id,l.service_id,l.name,l.description,l.profile_image as feature_image"
                                . ",ud.address,ud.city,ud.state"
                                . " FROM landscapers l,user_details ud WHERE l.user_id = ud.user_id AND l.service_id = $service_id HAVING l.id IN($landscapers_id) AND (SELECT COUNT(id) FROM payment_accounts WHERE user_id = l.user_id) > 0");

                foreach ($providers as $key => $one_provider) {
                    if ($one_provider->feature_image != "") {
                        $providers[$key]->feature_image = url("uploads/services/" . $one_provider->feature_image);
                    } else {
                        $providers[$key]->feature_image = $this->getFeatureImgByLandUserId($one_provider->landscaper_user_id);
                    }
                    $providers[$key]->favorite_status = $this->getfavbyid($one_provider->lanscaper_id, $user_id);
                    $lanscaper_obj = new Landscaper();
                    $providers[$key]->rating = $lanscaper_obj->get_overall_rating($one_provider->lanscaper_id);
                    $providers[$key]->usercount = $lanscaper_obj->get_total_review_count($one_provider->lanscaper_id);
                    $data_min_max = app('App\Http\Controllers\ServiceController')->getMinMaxPriceOfLanscaper($one_provider->lanscaper_id);
                    $providers[$key]->min_price = $data_min_max['min'];
                    if ($request->min_rate != "" && $request->max_rate != "") {
                        if ($providers[$key]->rating < $request->min_rate || $providers[$key]->rating > $request->max_rate) {
                            unset($providers[$key]);
                        }
                    }
                }

                $filter_price = $request->filter_price;
                if ($filter_price == "l") {
                    usort($providers, function($x, $y) {
                        return $x->min_price > $y->min_price ? 1 : -1;
                    });
                } else if ($filter_price == "h") {
                    usort($providers, function($x, $y) {
                        return $x->min_price < $y->min_price ? 1 : -1;
                    });
                }

                $pders = array_merge($providers);

                if (!empty($pders)) {
                    $response['data'] = $pders;
                    $response['success'] = 1;
                    $response['msg'] = count($pders) . " providers found.";
                } else {

                    $response['data'] = '';
                    $response['success'] = 0;
                    $response['msg'] = "No results found";
                }
            } else {
                $response['success'] = 0;
                $response['msg'] = "No results found";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function getFeatureImgByLandUserId($landscaperUid = "") {
        if ($landscaperUid != "") {
            $sql = "SELECT profile_image FROM landscapers WHERE profile_image!='' AND user_id =" . $landscaperUid . " LIMIT 0,1";
            $feature_image = DB::select($sql);

            if (isset($feature_image[0]->profile_image) && $feature_image[0]->profile_image != "") {
                return url("uploads/services/" . $feature_image[0]->profile_image);
            } else {
                return "";
            }
        }
    }

//    public function providerListByServiceLocation(Request $request) {
//        
//        $user_info = $request->userInfo;
//        
//        
//        $response = [
//            'success' => 0,
//            'msg' => ''
//        ];
//        
//        
//        $user_id = $user_info->user_id;
//        $service_id = $request->service_id;  
//        $latitude = $request->latitude; 
//        $longitude = $request->longitude;
//        $landscapers = [];
//        $radius = 15;
//        
//        
//        if ($latitude != "" && $longitude != '' && $service_id != "") {
//             $radius_query = "SELECT `id`,
//                ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $latitude ) ) + COS( RADIANS( `latitude` ) )
//                * COS( RADIANS( $latitude )) * COS( RADIANS( `longitude` ) - RADIANS( $longitude )) ) * 3958.756 AS `distance`
//                FROM `landscapers`
//                WHERE
//                ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $latitude ) ) + COS( RADIANS( `latitude` ) )
//                * COS( RADIANS( $latitude )) * COS( RADIANS( `longitude` ) - RADIANS( $longitude )) ) * 3958.756 < " . $radius;
//
//            $nearby = DB::select($radius_query); 
//            
//            
//            
//            if (!empty($nearby)) {
//                foreach ($nearby as $landscapers_obj) {
//                    if($landscapers_obj->id != ""){
//                        $landscapers[] = $landscapers_obj->id;
//                    }
//                }
//
//                if (!empty($landscapers)) {
//                    $landscapers_id = implode(',', $landscapers);
//                }
//            } 
//            
//           if (!empty($landscapers)) {
//        $providers = DB::select("SELECT l.id as lanscaper_id,l.user_id as landscaper_user_id,l.service_id,l.name,l.description,l.profile_image as feature_image"
//                . ",ud.address,ud.city,ud.state"
//                . " FROM landscapers l,user_details ud WHERE l.user_id = ud.user_id AND l.service_id = $service_id HAVING l.id IN($landscapers_id)");        
//   
//        
//        foreach ($providers as $key => $one_provider) {
//                if ($one_provider->feature_image != ""){
//                    $providers[$key]->feature_image = url("uploads/services/" . $one_provider->feature_image);
//                }
//                else{
//                $providers[$key]->feature_image = "";
//                }
//                $providers[$key]->favorite_status = $this->getfavbyid($one_provider->lanscaper_id, $user_id);
//                $lanscaper_obj = new Landscaper();
//                $providers[$key]->rating  = $lanscaper_obj->get_overall_rating($one_provider->lanscaper_id);
//                $providers[$key]->usercount  = $lanscaper_obj->get_total_review_count($one_provider->lanscaper_id);
//
//                if($request->min_rate!="" && $request->max_rate!=""){
//                    if($providers[$key]->rating<$request->min_rate || $providers[$key]->rating>$request->max_rate){
//                        unset($providers[$key]);
//                    } 
//                }               
//        }
//		
//        $pders = array_merge($providers); 
//		
//        if(!empty($pders)){
//                $response['data'] = $pders;
//                $response['success'] = 1;
//                $response['msg'] = count($pders). " providers found.";
//        }else{
//            
//                $response['data'] = '';
//                $response['success'] = 0;
//                $response['msg'] = "No results found";
//   
//        }   
//     }else{
//                $response['success'] = 0;
//                $response['msg'] = "No results found";
//     }
//        }else{
//             $response['msg'] = "Please fill up all the fields.";
//            
//        }
//            return response()->json($response);
//    }
//        if ($request->address != ""  && $request->service_id != "") {
//
//            $address = urldecode($request->address);
//            $service_id = urldecode($request->service_id);
//
//            $sql = "SELECT l.id as lanscaper_id,l.user_id,l.service_id,l.name,l.description,l.profile_image as feature_image,"
//                    . "ud.address,ud.city,ud.state,'0' as rating, '0' as usercount FROM landscapers l,user_details ud WHERE l.user_id = ud.user_id ";
//            if ($service_id != "")
//                $sql .= " AND l.service_id = '$service_id' ";
//            if ($address != "") {
//
//                $addr_arr = explode(',', $address);
//
//                $sql .= " AND (ud.address LIKE '%$addr_arr[0]%' ";
//
//                unset($addr_arr[0]);
//
//                foreach ($addr_arr as $addr) {
//                    $sql .= " OR ud.address LIKE '%" . trim($addr) . "%' ";
//                }
//            }
//
//            if ($city != "")
//                $sql .= " OR ud.city LIKE '$city%' ";
//
//            $sql .= ")";
//            $providers = DB::select($sql);
//
//            foreach ($providers as $key => $one_provider) {
//                if ($one_provider->feature_image != "")
//                    $providers[$key]->feature_image = url("uploads/services/" . $one_provider->feature_image);
//                else
//                    $providers[$key]->feature_image = "";
//
//                $providers[$key]->favorite_status = $this->getfavbyid($one_provider->lanscaper_id, $user_id);
//            }
//
//
//            if (count($providers) > 0) {
//                $response['data'] = $providers;
//                $response['success'] = 1;
//                $response['msg'] = count($providers) . " providers found.";
//            } else {
//                $response['data'] = [];
//                $response['success'] = 0;
//                $response['msg'] = "No providers found.";
//            }
//        } else {
//            $response['msg'] = "Please fill up all the fields.";
//        }
//
//        return response()->json($response);
//    }

    public function userInfo(Request $request) {
        $user_info = $request->userInfo;

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $user_detail = User::find($user_info->user_id);
        $user = UserDetail::where("user_id", "=", $user_info->user_id)->get()[0];
        $landscaper_info = Landscaper::where("user_id", "=", $user_info->user_id)->first();
        if ($user->profile_image != "") {
            if (filter_var($user->profile_image, FILTER_VALIDATE_URL)) {
                $user->profile_image = $user->profile_image;
            } else {
                $user->profile_image = url("uploads/profile_picture/" . $user->profile_image);
            }
        } else
            $user->profile_image = "";

        $user->ssn_no = $user_detail->ssn_no;
        $user->drivers_license = "";
        if ($user_detail && $user_detail->drivers_license != "")
            $user->drivers_license = url("uploads/drivers_license/" . $user_detail->drivers_license);

        $user->featured_image = "";
        if ($landscaper_info && $landscaper_info->profile_image != "")
            $user->featured_image = url("uploads/services/" . $landscaper_info->profile_image);
            

        if ($landscaper_info && $landscaper_info->latitude != '') {
            $user->landscaper_latitude = $landscaper_info->latitude;
        } else {
            $user->landscaper_latitude = '';
        }
        if ($landscaper_info && $landscaper_info->longitude != '') {
            $user->landscaper_longitude = $landscaper_info->longitude;
        } else {
            $user->landscaper_longitude = '';
        }



        if ($user) {
            $response['data'] = $user;
            $response['success'] = 1;
            $response['msg'] = "User info found.";
        } else {
            $response['data'] = [];
            $response['success'] = 0;
            $response['msg'] = "No providers found.";
        }

        return response()->json($response);
    }

    public function userinfoEdit(Request $request) {
        $user_info = $request->userInfo;

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $profile = UserDetail::where("user_id", "=", $user_info->user_id)->get()[0];
        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->phone_number = $request->tel;
        $profile->date_of_birth = date("Y-m-d", strtotime($request->dob));

        if ($profile->save()) {
            $response['data'] = [];
            $response['success'] = 1;
            $response['msg'] = "Profile Updated Successfully";
        } else {
            $response['data'] = [];
            $response['success'] = 0;
            $response['msg'] = "No providers found.";
        }

        return response()->json($response);
    }

    public function serviceRequestInfo(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $userId = $user_info->user_id;

        $all_data = array();
        $pay_acc_obj = new PaymentAccounts();

        $sql = "SELECT * FROM landscapers WHERE user_id = " . $userId;
        $land_data = DB::select($sql);
        $index = 0;
        foreach ($land_data as $land_row) {
            $sql_one = "SELECT * FROM book_services bs WHERE status=0 AND landscaper_id = " . $land_row->id . " ORDER BY bs.id DESC";
            $db_one_all = DB::select($sql_one);

            foreach ($db_one_all as $db_one) {
                if (!empty($db_one)) {
                    $all_data[$index]['book_service'] = $db_one;
//                    $all_data[$index]['book_service']->service_price = $pay_acc_obj->getLandscaperAmountOnly($all_data[$index]['book_service']->service_price);
                    $all_data[$index]['book_service']->service_price = $all_data[$index]['book_service']->service_booked_price;

                    $sql_two = "SELECT * FROM address_books WHERE id = " . $all_data[$index]['book_service']->address_book_id;
                    $db_two = DB::select($sql_two);
                    if (!empty($db_two))
                        $all_data[$index]['book_address'] = $db_two[0];

//                    $country_name = $this->getCountrynameByid($all_data[$index]['book_address']->country);
//                    $all_data[$index]['book_address']->country = $country_name;

                    $all_data[$index]['book_address']->country = "";

                    $sql_three = "SELECT service_name FROM services WHERE id = " . $land_row->service_id;
                    $db_three = DB::select($sql_three);
                    if (!empty($db_three))
                        $all_data[$index]['name'] = $db_three[0];

                    $index++;
                }
            }
        }

        if (count($all_data) > 0) {
//            $response['data'] = $all_data;
            $response['data'] = $all_data;
            $response['success'] = 1;
            $response['msg'] = count($all_data) . " Service Request found.";
        } else {
            $response['data'] = [];
            $response['success'] = 0;
            $response['msg'] = "No Service Request found.";
        }

        return response()->json($response);
    }

    public function servicePendingInfo(Request $request) {
        $user_info = $request->userInfo;

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $userId = $user_info->user_id;

        $all_data = array();
        $pay_acc_obj = new PaymentAccounts();

        $sql = "SELECT id,service_id FROM landscapers WHERE user_id = " . $userId;
        $land_data = DB::select($sql);

        $index = 0;
        $lids = [];
        $lids_str = -1;

        foreach ($land_data as $land_row) {
            $lids[] = $land_row->id;
        }

        if (!empty($lids))
            $lids_str = implode(',', $lids);

        $sql_one = "SELECT bs.*,s.service_name FROM book_services bs,services s,landscapers l WHERE bs.status IN(1,2) AND bs.landscaper_id IN (" . $lids_str . ") AND l.user_id = " . $userId . " AND l.service_id=s.id AND l.id=bs.landscaper_id GROUP BY bs.order_no ORDER BY bs.service_date DESC";
        $db_one_all = DB::select($sql_one);
        foreach ($db_one_all as $db_one) {
            if (!empty($db_one)) {
                $all_data[$index]['book_service'] = $db_one;
//                $all_data[$index]['book_service']->service_price = $pay_acc_obj->getLandscaperAmountOnly($all_data[$index]['book_service']->service_price);
                $all_data[$index]['book_service']->service_price = $all_data[$index]['book_service']->service_booked_price;

                $sql_two = "SELECT * FROM address_books WHERE id = " . $all_data[$index]['book_service']->address_book_id;
                $db_two = DB::select($sql_two);
                if (!empty($db_two))
                    $all_data[$index]['book_address'] = $db_two[0];

//                    $country_name = $this->getCountrynameByid($all_data[$index]['book_address']->country);
//                    $all_data[$index]['book_address']->country = $country_name;

                $all_data[$index]['book_address']->country = "";

                $all_data[$index]['name']['service_name'] = $db_one->service_name;

                // $sql_three = "SELECT service_name FROM services WHERE id = " . $land_row->service_id;
                // $db_three = DB::select($sql_three);
                // if (!empty($db_three))
                //     $all_data[$index]['name'] = $db_three[0];

                $index++;
            }
        }

        if (count($all_data) > 0) {
            $response['data'] = $all_data;
            $response['success'] = 1;
            $response['msg'] = count($all_data) . " Pending Service found.";
        } else {
            $response['data'] = [];
            $response['success'] = 0;
            $response['msg'] = "No Pending Service found.";
        }

        return response()->json($response);
    }

    public function acceptRejectService(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $book_service_id = $request->book_service_id;
        $status = $request->status;
        if ($request->accept_time != '' || $request->reject_time != '') {



            if ($status == 1) {
                $sql = "UPDATE book_services SET status=$status,accept_time='$request->accept_time',notification_status_user=1 WHERE id = $book_service_id";
                $update = DB::update($sql);
                $book_srvc_obj = DB::select("SELECT id,customer_id,payment_date"
                                . " FROM book_services"
                                . " WHERE id = " . $book_service_id);
                $count_acc_obj = DB::select("SELECT COUNT(id) as count_data FROM payment_accounts where user_id = " . $book_srvc_obj[0]->customer_id . " and is_primary = 1");

                if ($count_acc_obj[0]->count_data > 0) {

                    $this->autoPaymentUsingCard($book_srvc_obj[0]->customer_id, $request->accept_time, $book_srvc_obj[0]->id);
                }
            } else {
                $sql = "UPDATE book_services SET status=$status,reject_time='$request->reject_time',notification_status_user=1 WHERE id = $book_service_id";
                $update = DB::update($sql);
            }

            $info = DB::select("SELECT bs.id as order_id, bs.service_time ,l.name as landscaper_name,ab.address ,ab.city,ab.state,bs.service_date ,bs.order_no,bs.status,bs.is_completed,bs.customer_id, "
                            . " bs.accept_time,bs.reject_time,ud.profile_image, ud.first_name , ud.last_name,ud.email, ud.phone_number,ud.address as landscaper_address,s.service_name,s.id as service_id "
                            . " FROM book_services bs,landscapers l,services s,user_details ud,address_books ab "
                            . " WHERE bs.landscaper_id = l.id AND l.service_id = s.id AND ud.user_id = l.user_id AND bs.address_book_id = ab.id "
                            . " AND bs.id = " . $book_service_id);

            if ($info[0]->status == 0 && $info[0]->is_completed == 0) {
                $info[0]->status_name = "Service Request Sent";
            } elseif ($info[0]->status == 1 && $info[0]->is_completed == 0) {
                $info[0]->status_name = "Service Request Accepted,Make Payment To Start Work";
            } elseif ($info[0]->status == 2 && $info[0]->is_completed == 1) {
                $info[0]->status_name = "Work In Progress,Payment is in Escrow";
            } elseif ($info[0]->status == 3 && $info[0]->is_completed == 1) {
                $info[0]->status_name = "Job has been completed by Landscaper waiting for your confirmation,Payment is in Escrow";
            } elseif ($info[0]->status == 3 && $info[0]->is_completed == 2) {
                $info[0]->status_name = "Job Complete,Payment Success";
            } elseif ($info[0]->status == -1) {
                $info[0]->status_name = "Service Request Rejected";
            }

            $data = [];
            if ($info[0]->profile_image != '') {
                $data['profile_iamge'] = url("uploads/profile_picture/" . $info[0]->profile_image);
            } else {
                $data['profile_iamge'] = '';
            }
            $data['landscaper_name'] = $info[0]->first_name . ' ' . $info[0]->last_name;
            $data['status_name'] = ($request->status == 1) ? "Service Request has been Accepted" : "We are sorry to inform you that the provider is unable to fulfill your request at this time. Please search for another provider";
            $data['service_name'] = $info[0]->service_name;
            $data['date'] = $info[0]->service_date;
            $data['time'] = $info[0]->service_time;
            $data['order_id'] = $info[0]->order_id;
            $data['order_no'] = $info[0]->order_no;
            $data['status'] = ($info[0]->status == 0) ? 1 : -1;
            $data['notify_user_id'] = $info[0]->customer_id;
            $data['body'] = ($request->status == 1) ? "Service Request has been Accepted" : "We are sorry to inform you that the provider is unable to fulfill your request at this time. Please search for another provider";
            $data['accept_time'] = ($request->status == 1) ? $info[0]->accept_time : '';
            $data['reject_time'] = ($request->status == -1) ? $info[0]->reject_time : '';

            if ($status == -1) {
                $sql1 = DB::select("SELECT * FROM user_details ud WHERE ud.user_id =" . $info[0]->customer_id);

                $email['provider_name'] = $info[0]->landscaper_name;
                $email['person_name'] = $info[0]->first_name . ' ' . $info[0]->last_name;
                $email['customer_name'] = $sql1[0]->first_name . ' ' . $sql1[0]->last_name;
                $email['customer_address'] = $info[0]->address . ',' . $info[0]->city . ',' . $info[0]->state;
                $email['landscaper_address'] = $info[0]->landscaper_address;
                $email['landscaper_phone'] = $info[0]->phone_number;
                $email['landscaper_email'] = $info[0]->email;
                $email['service_name'] = $info[0]->service_name;
                $email['service_date'] = $info[0]->service_date;
                $email['service_time'] = $info[0]->service_time;
                $email['order_no'] = $info[0]->order_no;
                $email['customer_email'] = $sql1[0]->email;

                Mail::send('reject_service_admin', ['data' => $email], function ($message) use($email) {
                    $message->subject("Service Declined Notice");
                    $message->from('admin@seazoned.com', 'Seazoned');
//            $message ->to('demo.mlindia@gmail.com');  //For Local
                    $message->to($email['customer_email']);    //For Server
                });
            }
            if ($update) {
                $this->send_notification($info[0]->customer_id, $data, 'user');

                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Service Updated Successfully.";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Service Not Updated.";
            }
        } else {
            $response['success'] = 0;
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function confirmJob(Request $request) {

        if ((isset($request->order_no) && $request->order_no != '') && (isset($request->is_completed) && $request->is_completed != '')) {

            $order_no = $request->order_no;
            $is_completed = $request->is_completed;


            $landscaper_id = null;
            $service_price = null;
            $admin_amount = null;
            $landscaper_amount = null;

            $landscaper_details = DB::select("SELECT user_id,service_price,service_booked_price FROM book_services bs,landscapers l where bs.landscaper_id=l.id AND order_no='" . $order_no . "'");
            $landscaper_id = $landscaper_details[0]->user_id;
            $service_price = $landscaper_details[0]->service_price;
            $service_booked_price = $landscaper_details[0]->service_booked_price;
            $landscaper_payment_details = DB::select("SELECT * from payment_accounts where user_id=" . $landscaper_id);
            $admin_payment_details = DB::select("SELECT * from payment_accounts where user_id=(SELECT id FROM users WHERE profile_id = 1)");
            $admin_percentages = DB::select("SELECT * from payment_percentages");
            $admin_amount = ($service_booked_price * $admin_percentages[0]->percentage) / 100;
//            $landscaper_amount = $service_price - $admin_amount;
            $landscaper_amount = $service_booked_price;

            $paypal_ret = $this->paypalAcctoAccTransfer($admin_payment_details[0]->account_details, $admin_payment_details[0]->account_password, $admin_payment_details[0]->account_signature, $landscaper_payment_details[0]->account_email, $landscaper_amount);

            if ($paypal_ret) {
                DB::update("UPDATE book_services SET is_completed=" . $is_completed . " WHERE order_no='" . $order_no . "'");
                DB::update("UPDATE book_services SET landscaper_payment='" . $landscaper_amount . "',admin_payment='" . $admin_amount . "' WHERE order_no='" . $order_no . "'");

                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Job Completed Successfully.";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Job Not Completed.";
            }
        } else {
            $response['success'] = 0;
            $response['msg'] = "Please fill up all the fields.";
        }

        $info = DB::select("SELECT bs.id as order_id, bs.service_time ,l.name as landscaper_name,ab.address ,ab.city,ab.state,bs.service_date ,bs.order_no,bs.status,bs.is_completed,bs.customer_id,l.user_id as landscaper_user_id, "
                        . " bs.accept_time,bs.reject_time,ud.profile_image, ud.first_name , ud.last_name,ud.email, ud.phone_number,ud.address as landscaper_address,s.service_name,s.id as service_id "
                        . " FROM book_services bs,landscapers l,services s,user_details ud,address_books ab "
                        . " WHERE bs.landscaper_id = l.id AND l.service_id = s.id AND ud.user_id = l.user_id AND bs.address_book_id = ab.id "
                        . " AND bs.order_no = '" . $order_no . "'");

        $data = [];
        if ($info[0]->profile_image != '') {
            $data['profile_iamge'] = url("uploads/profile_picture/" . $info[0]->profile_image);
        } else {
            $data['profile_iamge'] = '';
        }
        $data['landscaper_name'] = $info[0]->first_name . ' ' . $info[0]->last_name;
        $data['status_name'] = ($info[0]->is_completed == 2) ? "Job has been confirmed" : "Job has not been confirmed";
        $data['service_name'] = $info[0]->service_name;
        $data['date'] = $info[0]->service_date;
        $data['time'] = $info[0]->service_time;
        $data['order_id'] = $info[0]->order_id;
        $data['order_no'] = $info[0]->order_no;
        $data['status'] = ($info[0]->status == 0) ? 1 : -1;
        $data['notify_user_id'] = $info[0]->landscaper_user_id;
        $data['body'] = ($info[0]->is_completed == 2) ? "Job has been confirmed" : "Job has not been confirmed";
        $data['accept_time'] = ($info[0]->status == 1) ? $info[0]->accept_time : '';
        $data['reject_time'] = ($info[0]->status == -1) ? $info[0]->reject_time : '';

        $this->send_notification($info[0]->landscaper_user_id, $data, 'landscaper');

        return response()->json($response);
    }

    public function paypalAcctoAccTransfer($sender_acc, $sender_pass, $sender_sig, $receiver_email_acc, $receiver_amt) {
        $config = array(
            // Signature Credential
            "acct1.UserName" => $sender_acc,
            "acct1.Password" => $sender_pass,
            "acct1.Signature" => $sender_sig,
            "mode" => "sandbox",
            'log.LogEnabled' => false,
            'log.FileName' => '../PayPal.log',
            'log.LogLevel' => 'FINE'
        );


        $PayPalMass = new PayPalMass($config);
        $response = $PayPalMass->call(
                array(
            array(
                'mail' => $receiver_email_acc,
                'currencyCode' => 'USD',
                'amount' => $receiver_amt
            )
                ), 'EmailAddress'
        );

        if ($response == 'Success') {
            return true;
        } else {
            return false;
        }
    }

    public function editLandscaper(Request $request) {

        $user_info = $request->userInfo;

        $response = [
            'success' => 0,
            'msg' => ''
        ];
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $user = User::find($user_info->user_id);
        $profile = UserDetail::where("user_id", "=", $user_info->user_id)->get()[0];
        $sql = DB::update("UPDATE `landscapers` SET `latitude`='$latitude',`longitude`='$longitude' WHERE user_id = $user_info->user_id");
        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->phone_number = $request->tel;
        $profile->address = $request->address;

        // Update ssn no
        $user->ssn_no = $request->ssn_no;

        if ($profile->save() && $user->save()) {
            $response['data'] = [];
            $response['success'] = 1;
            $response['msg'] = "Landscaper Profile Updated Successfully";
        } else {
            $response['data'] = [];
            $response['success'] = 0;
            $response['msg'] = "Landscaper Profile not Updated";
        }

        return response()->json($response);
    }

    public function getCountrynameByid($id) {
        $country_sql = 'SELECT country_name FROM countries WHERE id = ' . $id;
        $country = DB::select($country_sql)[0]->country_name;
        return $country;
    }

    public function sampleApi(Request $request) {
//        $days_json = $request->days;
//        $days = json_decode($days_json,TRUE); 
//
//        $start_json = $request->start;
//        $start = json_decode($start_json,TRUE); 
//
//        $end_json = $request->end;
//        $end = json_decode($end_json,TRUE);
//        print_r($days);echo "<br>";
//        print_r($start);echo "<br>";
//        echo $start[0]['s'];echo "<br>";
//        print_r($end);echo "<br>";
//        echo $end[0]['e'];echo "<br>";
//        exit;
//        foreach ($days as $key => $day) {
//            if ($start[$key]['s'] != "" && $end[$key]['e'] != "") {
//                $data[] = array(
//                    'service_day' => urldecode($day['day']),
//                    'start_time' => urldecode($start[$key]['s']),
//                    'end_time' => urldecode($end[$key]['e']),
//                );
//            }
//        }
//        print_r($data);exit;
//        $recurring_services = ['Every 7 days', 'Every 10 days', 'Every 14 days', 'Just Once'];
//        $rec_services_json = $request->recurring_services;
//            $rec_services = json_decode($rec_services_json,TRUE); 
//
//            foreach ($recurring_services as $index => $val) {
//                $data[] = array(
//                    'service_frequency' => $val,
//                    'discount_price' => urldecode($rec_services[$index]['rs'])
//                );
//            }
//            $finalarr['name'] = $data['landscapper_info'][0]->name;
//            $finalarr['description'] = $data['landscapper_info'][0]->description;
//            $finalarr['profile_image'] = $data['landscapper_info'][0]->profile_image;
//            $finalarr['location'] = $data['landscapper_info'][0]->location;
//            $finalarr['service_day'] = $data['service_time'][0]->service_day;
//            $finalarr['start_time'] = $data['service_time'][0]->start_time;
//            $finalarr['end_time'] = $data['service_time'][0]->end_time;
//         print_r($data);exit;
    }

    public function landscaperDetails(Request $request) {
        $user_info = $request->userInfo;

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->landscaper_id != "") {

            $landscaper_id = urldecode($request->landscaper_id);
            $data["landscapper_info"] = DB::select("select * from landscapers where id = '$landscaper_id'");
            $data["service_time"] = DB::select("select * from service_times where landscaper_id = '$landscaper_id';");

            if ($data["landscapper_info"][0]->profile_image != "")
                $data["landscapper_info"][0]->feature_image = url("uploads/services/" . $data["landscapper_info"][0]->profile_image);
            else
                $data["landscapper_info"][0]->feature_image = "";

            $user_id = $data["landscapper_info"][0]->user_id;

            $xdata["user_info"] = DB::select("select * from user_details where user_id = '$user_id'");
            if ($xdata["user_info"][0]->profile_image != "")
                $data["landscapper_info"][0]->profile_image = url("uploads/profile_picture/" . $xdata["user_info"][0]->profile_image);
            else
                $data["landscapper_info"][0]->profile_image = "";

            $user_user_id = $user_info->user_id;

            $data["landscapper_info"][0]->favorite_status = $this->getfavbyid($landscaper_id, $user_user_id);

            if ($data["landscapper_info"][0]->location == NULL) {
                $landscaper_address = DB::select("select address,city,state from user_details where user_id = " . $data['landscapper_info'][0]->user_id);
                $data["landscapper_info"][0]->location = $landscaper_address[0]->address;
            } else {
                $data["landscapper_info"][0]->location = "";
            }
            $data['service_prices'] = DB::table('service_prices')->where(['landscaper_id' => $landscaper_id])->get();

            $landscaper_obj = new Landscaper();

            $data['overall_rating'] = $landscaper_obj->get_overall_rating($landscaper_id);
            $data['total_rating'] = $landscaper_obj->get_total_rating_count($landscaper_id);
            $data['total_review'] = $landscaper_obj->get_total_review_count($landscaper_id);
            $data['all_rating'] = $landscaper_obj->get_all_rating($landscaper_id);

            $data['service_rating_details'] = $landscaper_obj->get_service_rating_details($landscaper_id);
            if (!empty($data['service_rating_details'])) {
                foreach ($data['service_rating_details'] as $value) {
                    if (!empty($value->profile_image)) {
                        $value->profile_image = url("uploads/profile_picture/" . $value->profile_image);
                    } else {
                        $value->profile_image = "";
                    }
                }
            }

            $data['landscaper_service_images'] = $landscaper_obj->get_landscaper_service_images($landscaper_id);
            if (!empty($data['landscaper_service_images'])) {
                foreach ($data['landscaper_service_images'] as $value) {
                    if (!empty($value->service_image)) {
                        $value->service_image = url("uploads/property/" . $value->service_image);
                    } else {
                        $value->service_image = "";
                    }
                }
            }
            //print_r($data);
            //exit;
            if (count($data) > 0) {
                $response['data'] = $data;
                $response['success'] = 1;
                $response['msg'] = count($data) . " Landscapper found.";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "No Landscapper found.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    function changeProfilePicture(Request $request) {
        // $user_info = $request->userInfo;

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        // $userId = $user_info->user_id;
        $userId = $request->get('user_id');

        $profile = UserDetail::where("user_id", "=", $userId)->first();
        $old_image = $profile->profile_image;
        // $profile = $profile->first();

        $source = urldecode($request->source);
        if (isset($source) && $source != "") {
            if ($source == 'android') {
                if (is_uploaded_file($_FILES['profile_picture']['tmp_name'])) {
                    $fileName = rand(1111, 9999) . "_" . time() . "_" . $_FILES['profile_picture']['name'];
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], "uploads/profile_picture/" . $fileName)) {
                        $profile->profile_image = $fileName;
                        if ($old_image != "" && File::exists("uploads/profile_picture/" . $old_image)) {
                            File::delete("uploads/profile_picture/" . $old_image);
                        }
                    }
                }
                // if (Input::hasFile('profile_picture') && Input::file('profile_picture')->isValid()) {
                //     $avatar = $request->file("profile_picture");
                //     echo $fileName = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
                //     $avatar->move("uploads/services/", $fileName);
                //     $landscaper->profile_image = $fileName;
                //     if ($old_image != "" && File::exists("uploads/services/" . $old_image)) {
                //         File::delete("uploads/services/" . $old_image);
                //     }
                // }
            } else if ($source == 'iphone') {
                if (isset($request->profile_picture) && $request->profile_picture != "") {
                    // $pos = strpos($request->profile_picture, ';');
                    // $type = explode('/', explode(':', substr($request->profile_picture, 0, $pos))[1])[1];

                    $destinationPath = "uploads/profile_picture/";
                    // $fileName = rand(1111, 9999) . "_" . time() . "_iphone." . $type;
                    $fileName = rand(1111, 9999) . "_" . time() . "_iphone.jpg";
                    $target_path = $destinationPath . $fileName;

                    file_put_contents($target_path, base64_decode($request->profile_picture));
                    $profile->profile_image = $fileName;
                    if ($old_image != "" && File::exists("uploads/profile_picture/" . $old_image)) {
                        File::delete("uploads/profile_picture/" . $old_image);
                    }
                }
            }
            // $profile = UserDetail::where("user_id", "=", $userId)->get()[0];
            // $profile->profile_image = $landscaper->profile_image;

            if ($profile->save()) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Profile Image Updated Successfully";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Profile Image not Updated";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    function changeLandscaperFeaturePicture(Request $request) {
        // $user_info = $request->userInfo;

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        // $userId = $user_info->user_id;
        $userId = $request->get('user_id');

        $profile = Landscaper::where("user_id", "=", $userId)->first();
        $old_image = $profile->profile_image;
        // $profile = $profile->first();

        $flag = 0;
        $source = urldecode($request->source);
        if (isset($source) && $source != "") {
            if ($source == 'android') {
                if (is_uploaded_file($_FILES['feature_image']['tmp_name'])) {
                    $fileName = rand(1111, 9999) . "_" . time() . "_" . $_FILES['feature_image']['name'];
                    if (move_uploaded_file($_FILES['feature_image']['tmp_name'], "uploads/services/" . $fileName)) {
                        $profile->profile_image = $fileName;

                        DB::table('landscapers')->where('user_id', $userId)->update(['profile_image' => $fileName]);

                        $flag = 1;
                        if ($old_image != "" && File::exists("uploads/services/" . $old_image)) {
                            File::delete("uploads/services/" . $old_image);
                        }
                    }
                }

                // if (Input::hasFile('feature_image') && Input::file('feature_image')->isValid()) {
                //     $avatar = $request->file("feature_image");
                //     echo $fileName = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
                //     $avatar->move("uploads/services/", $fileName);
                //     $landscaper->profile_image = $fileName;
                //     if ($old_image != "" && File::exists("uploads/services/" . $old_image)) {
                //         File::delete("uploads/services/" . $old_image);
                //     }
                // }
            } else if ($source == 'iphone') {
                if (isset($request->feature_image) && $request->feature_image != "") {
                    // $pos = strpos($request->feature_image, ';');
                    // $type = explode('/', explode(':', substr($request->feature_image, 0, $pos))[1])[1];

                    $destinationPath = "uploads/services/";
                    // $fileName = rand(1111, 9999) . "_" . time() . "_iphone." . $type;
                    $fileName = rand(1111, 9999) . "_" . time() . "_iphone.jpg";
                    $target_path = $destinationPath . $fileName;

                    file_put_contents($target_path, base64_decode($request->feature_image));
                    $profile->profile_image = $fileName;

                    DB::table('landscapers')->where('user_id', $userId)->update(['profile_image' => $fileName]);

                    $flag = 1;
                    if ($old_image != "" && File::exists("uploads/services/" . $old_image)) {
                        File::delete("uploads/services/" . $old_image);
                    }
                }
            }
            // $profile = Landscaper::where("user_id", "=", $userId)->get()[0];
            // $profile->profile_image = $landscaper->profile_image;

            if ($flag == 1) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Landscaper Feature Image Updated Successfully";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Landscaper Feature Image not Updated";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function userLoginFB(Request $request) {

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->email != "" && $request->first_name != "" && $request->last_name != "") {

            $user = new User();
            $profile = new UserDetail();
            $addr_book = new AddressBook();

            $num = $user->check_user_email(urldecode($request->email));

            if ($num == 0) {
                $user->username = urldecode($request->email);
                $user->active = 1;
                $user->profile_id = 2;
                $user->user_type = "Users";
                if ($request->facebook_id != "")
                    $user->social_id = $request->facebook_id;

                $user->social_source = "Facebook";

                if ($user->save()) {

                    $profile->first_name = urldecode($request->first_name);
                    $profile->last_name = urldecode($request->last_name);
                    $profile->email = urldecode($request->email);
                    if ($request->dob != "")
                        $profile->date_of_birth = date("Y-m-d", strtotime(urldecode($request->dob)));

                    $profile->profile_image = $request->profile_image;
                    $profile->user_id = $user->id;

                    $addr_book->user_id = $user->id;
                    $addr_book->name = urldecode($request->first_name) . ' ' . urldecode($request->last_name);
                    $addr_book->email_address = urldecode($request->email);
                    $addr_book->primary_address = 1;
                }

                if ($profile->save() && $addr_book->save()) {
                    $sql = "SELECT ud.*,u.profile_id,u.username,u.user_type,u.active "
                            . "FROM user_details ud LEFT JOIN users u ON(u.id=ud.user_id) "
                            . "WHERE u.username = '" . $request->email . "'"
                            . "AND u.social_source = 'Facebook' AND u.profile_id = 2 AND u.active = 1";

                    $data = DB::select($sql);

                    if (count($data) == 1) {
                        $jwt = new JWT();
                        $secret = $jwt->getSecretKey();
                        $token = JWT::encode($data[0], $secret);

                        $response['token'] = $token;
                        $response['data'] = $data[0];
                        $response['success'] = 1;
                        $response['msg'] = "Login Successful.";
                    } else {
                        $response['msg'] = "Please provide valid credentials.";
                    }
                } else {
                    $response['msg'] = "User Registration Failed";
                }
            } else {
                $sql = "SELECT ud.*,u.profile_id,u.username,u.user_type,u.active "
                        . "FROM user_details ud LEFT JOIN users u ON(u.id=ud.user_id) "
                        . "WHERE u.username = '" . $request->email . "'"
                        . "AND u.social_source = 'Facebook' AND u.profile_id = 2 AND u.active = 1";

                $data = DB::select($sql);

                if (count($data) == 1) {
                    $jwt = new JWT();
                    $secret = $jwt->getSecretKey();
                    $token = JWT::encode($data[0], $secret);

                    $response['token'] = $token;
                    $response['data'] = $data[0];
                    $response['success'] = 1;
                    $response['msg'] = "Login Successful.";
                } else {
                    $response['msg'] = "Please provide valid credentials.";
                }
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function landscaperLoginFB(Request $request) {

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->email != "" && $request->first_name != "" && $request->last_name != "") {

            $user = new User();
            $profile = new UserDetail();

            $num = $user->check_user_email(urldecode($request->email));

            if ($num == 0) {
                $user->username = urldecode($request->email);
                $user->active = 1;
                $user->profile_id = 3;
                $user->user_type = "Landscaper";
                if ($request->facebook_id != "")
                    $user->social_id = $request->facebook_id;

                $user->social_source = "Facebook";

                if ($user->save()) {

                    $profile->first_name = urldecode($request->first_name);
                    $profile->last_name = urldecode($request->last_name);
                    $profile->email = urldecode($request->email);
                    if ($request->dob != "")
                        $profile->date_of_birth = date("Y-m-d", strtotime(urldecode($request->dob)));

                    $profile->profile_image = $request->profile_image;
                    $profile->user_id = $user->id;
                }

                if ($profile->save()) {
                    $sql = "SELECT ud.*,u.profile_id,u.username,u.user_type,u.active "
                            . "FROM user_details ud LEFT JOIN users u ON(u.id=ud.user_id) "
                            . "WHERE u.username = '" . $request->email . "'"
                            . "AND u.social_source = 'Facebook' AND u.profile_id = 3 AND u.active = 1";

                    $data = DB::select($sql);

                    if ($data[0]->user_type == "Landscaper") {

                        $sql_l = "SELECT id FROM landscapers WHERE user_id = " . $data[0]->user_id;
                        $data_l = DB::select($sql_l);
                        $data[0]->service_count = count($data_l);

                        if ($data[0]->service_count == 0) {
                            $service_flag = 1;
                            $data[0]->provider_status = 0;
                        } else {

                            foreach ($data_l as $val) {
                                $landscaper_arr[] = $val->id;
                            }

                            $landscaper_str = implode(',', $landscaper_arr);

                            if ($landscaper_str != "") {
                                $sql_h = 'SELECT count(id) as count FROM service_prices WHERE landscaper_id IN (' . $landscaper_str . ')';
                                $data_h = DB::select($sql_h);
                                $data[0]->provider_status = ($data_h[0]->count > 0) ? 1 : 0;
                            }
                        }
                    }



                    if (count($data) == 1) {
                        $jwt = new JWT();
                        $secret = $jwt->getSecretKey();
                        $token = JWT::encode($data[0], $secret);

                        $response['token'] = $token;
                        $response['data'] = $data[0];
                        $response['success'] = 1;
                        $response['msg'] = "Login Successful.";
                    } else {
                        $response['msg'] = "Please provide valid credentials.";
                    }
                } else {
                    $response['msg'] = "Lanscaper Registration Failed";
                }
            } else {
                $sql = "SELECT ud.*,u.profile_id,u.username,u.user_type,u.active "
                        . "FROM user_details ud LEFT JOIN users u ON(u.id=ud.user_id) "
                        . "WHERE u.username = '" . $request->email . "'"
                        . "AND u.social_source = 'Facebook' AND u.profile_id = 3 AND u.active = 1";

                $data = DB::select($sql);

                if ($data[0]->user_type == "Landscaper") {

                    $sql_l = "SELECT id FROM landscapers WHERE user_id = " . $data[0]->user_id;
                    $data_l = DB::select($sql_l);
                    $data[0]->service_count = count($data_l);

                    if ($data[0]->service_count == 0) {
                        $service_flag = 1;
                        $data[0]->provider_status = 0;
                    } else {

                        foreach ($data_l as $val) {
                            $landscaper_arr[] = $val->id;
                        }

                        $landscaper_str = implode(',', $landscaper_arr);

                        if ($landscaper_str != "") {
                            $sql_h = 'SELECT count(id) as count FROM service_prices WHERE landscaper_id IN (' . $landscaper_str . ')';
                            $data_h = DB::select($sql_h);
                            $data[0]->provider_status = ($data_h[0]->count > 0) ? 1 : 0;
                        }
                    }
                }

                if (count($data) == 1) {
                    $jwt = new JWT();
                    $secret = $jwt->getSecretKey();
                    $token = JWT::encode($data[0], $secret);

                    $response['token'] = $token;
                    $response['data'] = $data[0];
                    $response['success'] = 1;
                    $response['msg'] = "Login Successful.";
                } else {
                    $response['msg'] = "Please provide valid credentials.";
                }
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function userLoginGoogle(Request $request) {

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->email != "" && $request->first_name != "" && $request->last_name != "") {

            $user = new User();
            $profile = new UserDetail();
            $addr_book = new AddressBook();

            $num = $user->check_user_email(urldecode($request->email));

            if ($num == 0) {
                $user->username = urldecode($request->email);
                $user->active = 1;
                $user->profile_id = 2;
                $user->user_type = "Users";
                if ($request->google_id != "")
                    $user->social_id = $request->google_id;

                $user->social_source = "Google";

                if ($user->save()) {

                    $profile->first_name = urldecode($request->first_name);
                    $profile->last_name = urldecode($request->last_name);
                    $profile->email = urldecode($request->email);
                    $profile->user_id = $user->id;

                    $addr_book->user_id = $user->id;
                    $addr_book->name = urldecode($request->first_name) . ' ' . urldecode($request->last_name);
                    $addr_book->email_address = urldecode($request->email);
                    $addr_book->primary_address = 1;
                }

                if ($profile->save() && $addr_book->save()) {
                    $sql = "SELECT ud.*,u.profile_id,u.username,u.user_type,u.active "
                            . "FROM user_details ud LEFT JOIN users u ON(u.id=ud.user_id) "
                            . "WHERE u.username = '" . $request->email . "'"
                            . "AND u.social_source = 'Google' AND u.profile_id = 2 AND u.active = 1";

                    $data = DB::select($sql);

                    if (count($data) == 1) {
                        $jwt = new JWT();
                        $secret = $jwt->getSecretKey();
                        $token = JWT::encode($data[0], $secret);

                        $response['token'] = $token;
                        $response['data'] = $data[0];
                        $response['success'] = 1;
                        $response['msg'] = "Login Successful.";
                    } else {
                        $response['msg'] = "Please provide valid credentials.";
                    }
                } else {
                    $response['msg'] = "User Registration Failed";
                }
            } else {
                $sql = "SELECT ud.*,u.profile_id,u.username,u.user_type,u.active "
                        . "FROM user_details ud LEFT JOIN users u ON(u.id=ud.user_id) "
                        . "WHERE u.username = '" . $request->email . "'"
                        . "AND u.social_source = 'Google' AND u.profile_id = 2 AND  u.active = 1";

                $data = DB::select($sql);

                if (count($data) == 1) {
                    $jwt = new JWT();
                    $secret = $jwt->getSecretKey();
                    $token = JWT::encode($data[0], $secret);

                    $response['token'] = $token;
                    $response['data'] = $data[0];
                    $response['success'] = 1;
                    $response['msg'] = "Login Successful.";
                } else {
                    $response['msg'] = "Please provide valid credentials.";
                }
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function landscaperLoginGoogle(Request $request) {

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->email != "" && $request->first_name != "" && $request->last_name != "") {

            $user = new User();
            $profile = new UserDetail();

            $num = $user->check_user_email(urldecode($request->email));

            if ($num == 0) {
                $user->username = urldecode($request->email);
                $user->active = 1;
                $user->profile_id = 3;
                $user->user_type = "Landscaper";
                if ($request->google_id != "")
                    $user->social_id = $request->google_id;

                $user->social_source = "Google";

                if ($user->save()) {

                    $profile->first_name = urldecode($request->first_name);
                    $profile->last_name = urldecode($request->last_name);
                    $profile->email = urldecode($request->email);
                    $profile->user_id = $user->id;
                }

                if ($profile->save()) {
                    $sql = "SELECT ud.*,u.profile_id,u.username,u.user_type,u.active "
                            . "FROM user_details ud LEFT JOIN users u ON(u.id=ud.user_id) "
                            . "WHERE u.username = '" . $request->email . "'"
                            . "AND u.social_source = 'Google' AND u.profile_id = 3 AND u.active = 1";

                    $data = DB::select($sql);

                    if ($data[0]->user_type == "Landscaper") {

                        $sql_l = "SELECT id FROM landscapers WHERE user_id = " . $data[0]->user_id;
                        $data_l = DB::select($sql_l);
                        $data[0]->service_count = count($data_l);

                        if ($data[0]->service_count == 0) {
                            $service_flag = 1;
                            $data[0]->provider_status = 0;
                        } else {

                            foreach ($data_l as $val) {
                                $landscaper_arr[] = $val->id;
                            }

                            $landscaper_str = implode(',', $landscaper_arr);

                            if ($landscaper_str != "") {
                                $sql_h = 'SELECT count(id) as count FROM service_prices WHERE landscaper_id IN (' . $landscaper_str . ')';
                                $data_h = DB::select($sql_h);
                                $data[0]->provider_status = ($data_h[0]->count > 0) ? 1 : 0;
                            }
                        }
                    }

                    if (count($data) == 1) {
                        $jwt = new JWT();
                        $secret = $jwt->getSecretKey();
                        $token = JWT::encode($data[0], $secret);

                        $response['token'] = $token;
                        $response['data'] = $data[0];
                        $response['success'] = 1;
                        $response['msg'] = "Login Successful.";
                    } else {
                        $response['msg'] = "Please provide valid credentials.";
                    }
                } else {
                    $response['msg'] = "Lanscaper Registration Failed";
                }
            } else {
                $sql = "SELECT ud.*,u.profile_id,u.username,u.user_type,u.active "
                        . "FROM user_details ud LEFT JOIN users u ON(u.id=ud.user_id) "
                        . "WHERE u.username = '" . $request->email . "'"
                        . "AND  u.social_source = 'Google' AND u.profile_id = 3 AND u.active = 1";

                $data = DB::select($sql);

                if ($data[0]->user_type == "Landscaper") {

                    $sql_l = "SELECT id FROM landscapers WHERE user_id = " . $data[0]->user_id;
                    $data_l = DB::select($sql_l);
                    $data[0]->service_count = count($data_l);

                    if ($data[0]->service_count == 0) {
                        $service_flag = 1;
                        $data[0]->provider_status = 0;
                    } else {

                        foreach ($data_l as $val) {
                            $landscaper_arr[] = $val->id;
                        }

                        $landscaper_str = implode(',', $landscaper_arr);

                        if ($landscaper_str != "") {
                            $sql_h = 'SELECT count(id) as count FROM service_prices WHERE landscaper_id IN (' . $landscaper_str . ')';
                            $data_h = DB::select($sql_h);
                            $data[0]->provider_status = ($data_h[0]->count > 0) ? 1 : 0;
                        }
                    }
                }

                if (count($data) == 1) {
                    $jwt = new JWT();
                    $secret = $jwt->getSecretKey();
                    $token = JWT::encode($data[0], $secret);

                    $response['token'] = $token;
                    $response['data'] = $data[0];
                    $response['success'] = 1;
                    $response['msg'] = "Login Successful.";
                } else {
                    $response['msg'] = "Please provide valid credentials.";
                }
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function changePassword(Request $request) {

        $user_info = $request->userInfo;

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->old_pw != "" && $request->new_pw != "" && $request->conf_pw != "") {

            $old = $user_info->password;
            $oldpw = md5($request->old_pw);
            $newpw = md5($request->new_pw);
            $conpw = md5($request->conf_pw);

            if ($old == $oldpw) {
                if ($newpw == $conpw) {

                    $user = User::where("id", "=", $user_info->user_primary_id)->get()[0];
                    $user->password = $newpw;

                    if ($user->save()) {
                        $response['data'] = [];
                        $response['success'] = 1;
                        $response['msg'] = "Password Updated Successfully";
                    } else {
                        $response['data'] = [];
                        $response['success'] = 0;
                        $response['msg'] = "Password Not Updated";
                    }
                } else {
                    $response['data'] = [];
                    $response['success'] = 0;
                    $response['msg'] = "Confirm & New Password Mismatch";
                }
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Old Password Mismatch";
            }
        } else {
            $response['data'] = [];
            $response['success'] = 0;
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function viewAddedServiceList(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->user_id != "") {

            $data["servies"] = DB::select("select l.id as added_service_id,l.service_id,s.service_name from landscapers l,services s where l.service_id=s.id AND user_id = '$request->user_id'");

            if (count($data) > 0) {
                $response['data'] = $data;
                $response['success'] = 1;
                $response['msg'] = count($data) . " servies found.";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "No servies found.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function deleteAddedServiceList(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->added_service_id != "") {

            $book_services = DB::table('book_services')->where('landscaper_id', '=', $request->added_service_id)->get();

            if (count($book_services) == 0) {

                $service_prices = DB::table('service_prices')->where('landscaper_id', '=', $request->added_service_id)->get();

                if (count($service_prices) > 0)
                    DB::table('service_prices')->where('landscaper_id', '=', $request->added_service_id)->delete();

                $service_times = DB::table('service_times')->where('landscaper_id', '=', $request->added_service_id)->get();

                if (count($service_times) > 0)
                    DB::table('service_times')->where('landscaper_id', '=', $request->added_service_id)->delete();

                $service_details = DB::table('service_details')->where('landscaper_id', '=', $request->added_service_id)->get();

                if (count($service_details) > 0)
                    DB::table('service_details')->where('landscaper_id', '=', $request->added_service_id)->delete();

                $favorite_landscapers = DB::table('favorite_landscapers')->where('landscaper_id', '=', $request->added_service_id)->get();

                if (count($favorite_landscapers) > 0)
                    DB::table('favorite_landscapers')->where('landscaper_id', '=', $request->added_service_id)->delete();

                $landscapers = DB::table('landscapers')->where('id', '=', $request->added_service_id)->get();

                if (count($landscapers) > 0)
                    DB::table('landscapers')->where('id', '=', $request->added_service_id)->delete();

                $response['success'] = 1;
                $response['msg'] = "Data deleted successfully.";
            } else {
                $response['msg'] = "Service can not be deleted as it has already been booked.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function viewService(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->added_service_id != "" && $request->service_id != "") {
            $service_id = $request->service_id;

            switch ($service_id) {
                case 1:
                    $data["mowing_acre"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '1' and landscaper_id = '{$request->added_service_id}' and service_field_id = '1' ORDER BY service_field_price;");
                    $data["mowing_grass"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '1' and landscaper_id = '{$request->added_service_id}' and service_field_id = '2' ORDER BY service_field_price;");
                    $data['service_prices'] = DB::select("SELECT * FROM service_prices WHERE landscaper_id = $request->added_service_id AND service_id = $service_id");
                    break;
                case 2:
                    $data["leaf_acre"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '2' and landscaper_id = '{$request->added_service_id}' and service_field_id = '1' ORDER BY service_field_price;");
                    $data["leaf_accumulation"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '2' and landscaper_id = '{$request->added_service_id}' and service_field_id = '6' ORDER BY service_field_price;");
                    $data['service_prices'] = DB::select("SELECT * FROM service_prices WHERE landscaper_id = $request->added_service_id AND service_id = $service_id");
                    break;
                case 3:
                    $data["lawn_acre"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '3' and landscaper_id = '{$request->added_service_id}' and service_field_id = '1' ORDER BY service_field_price;");
                    $data['service_prices'] = DB::select("SELECT * FROM service_prices WHERE landscaper_id = $request->added_service_id AND service_id = $service_id");
                    break;
                case 4:
                    $data["aeration_acre"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '4' and landscaper_id = '{$request->added_service_id}' and service_field_id = '1' ORDER BY service_field_price;");
                    $data['service_prices'] = DB::select("SELECT * FROM service_prices WHERE landscaper_id = $request->added_service_id AND service_id = $service_id");
                    break;
                case 5:
                    $data["sprinkler_acre"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '5' and landscaper_id = '{$request->added_service_id}' and service_field_id = '1' ORDER BY service_field_price;");
                    $data["sprinkler_zone"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '5' and landscaper_id = '{$request->added_service_id}' and service_field_id = '11' ORDER BY service_field_price;");
                    $data['service_prices'] = DB::select("SELECT * FROM service_prices WHERE landscaper_id = $request->added_service_id AND service_id = $service_id");
                    break;
                case 6:
                    $data["pool_water_type"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '6' and landscaper_id = '{$request->added_service_id}' and service_field_id = '7' ORDER BY service_field_price;");
                    $data["pool_spa"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '6' and landscaper_id = '{$request->added_service_id}' and service_field_id = '8' ORDER BY service_field_price;");
                    $data["pool_type"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '6' and landscaper_id = '{$request->added_service_id}' and service_field_id = '9' ORDER BY service_field_price;");
                    $data["pool_state"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '6' and landscaper_id = '{$request->added_service_id}' and service_field_id = '10' ORDER BY service_field_price;");
                    $data['service_prices'] = DB::select("SELECT * FROM service_prices WHERE landscaper_id = $request->added_service_id AND service_id = $service_id");
                    break;
                case 7:
                    $data["snow_car"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '7' and landscaper_id = '{$request->added_service_id}' and service_field_id = '3' ORDER BY service_field_price;");
                    $data["snow_driveway_type"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '7' and landscaper_id = '{$request->added_service_id}' and service_field_id = '4';");
                    $data["snow_service_type"] = DB::select("select landscaper_id as added_service_id,service_id,service_field_value,service_field_price from service_details where service_id = '7' and landscaper_id = '{$request->added_service_id}' and service_field_id = '5';");
                    $data['service_prices'] = DB::select("SELECT * FROM service_prices WHERE landscaper_id = $request->added_service_id AND service_id = $service_id");
                    break;
            }

            if (count($data) > 0) {
                $response['data'] = $data;
                $response['success'] = 1;
                $response['msg'] = count($data) . " servies found.";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "No servies found.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function editService(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($request->added_service_id != "" && $request->service_id != "" && $request->service_id <= 7) {
            DB::table('service_details')->where([['landscaper_id', '=', $request->added_service_id], ["service_id", "=", $request->service_id]])->delete();
            $latitude = '';
            $longitude = '';
            $landscaper_id = $request->added_service_id;
            $service_id = $request->service_id;
//            $latitude = $request->latitude;
//            $longitude = $request->longitude;
//            $landscaper = new Landscaper();
//            $landscaper->user_id = $landscaper_id;
//            $landscaper->service_id = $request->service_id;
//            $landscaper->save();



            if ($service_id == 1)
                $response = $this->addLawnMawningService($request, $landscaper_id, $service_id);
            elseif ($service_id == 2)
                $response = $this->addLeafRemovalService($request, $landscaper_id, $service_id);
            elseif ($service_id == 3)
                $response = $this->addLawnTreatmentService($request, $landscaper_id, $service_id);
            elseif ($service_id == 4)
                $response = $this->addAerationService($request, $landscaper_id, $service_id);
            elseif ($service_id == 5)
                $response = $this->addSprinklerWinterizingService($request, $landscaper_id, $service_id);
            elseif ($service_id == 6)
                $response = $this->addPoolCleaningService($request, $landscaper_id, $service_id);
            elseif ($service_id == 7)
                $response = $this->addSnowRemovalService($request, $landscaper_id, $service_id);
            else
                $response['msg'] = "Invalid Service";

            $s_price = DB::table('service_prices')
                            ->where('landscaper_id', '=', $landscaper_id)
                            ->where('service_id', '=', $service_id)->get();

            $rec_services_json = $request->recurring_services;
            $rec_services = json_decode($rec_services_json, TRUE);
            $rec_services[3] = 0;
            foreach ($s_price as $index => $one_price) {
                DB::table('service_prices')
                        ->where('id', '=', $one_price->id)
                        ->update(['discount_price' => $rec_services[$index]['rs']]);
            }
            $response['data'] = [
                "landscaper_id" => $landscaper_id,
                "service_id" => $service_id
            ];
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function viewServiceHoursAndDetails(Request $request) {
        $user_info = $request->userInfo;

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "") {

            $landscaper = DB::table('landscapers')->where(['user_id' => $user_info->user_id])->get();
            $data['service_hours'] = DB::table('service_times')->where(['landscaper_id' => $landscaper[0]->id, 'service_id' => $landscaper[0]->service_id])->get();
            $data['service_prices'] = DB::table('service_prices')->where(['landscaper_id' => $landscaper[0]->id, 'service_id' => $landscaper[0]->service_id])->get();
            $data['provider_name'] = $landscaper[0]->name;
            $data['description'] = $landscaper[0]->description;
            $data['distance_provided'] = $landscaper[0]->distance;

            if (count($data) > 0) {
                $response['data'] = $data;
                $response['success'] = 1;
                $response['msg'] = count($data) . " servies found.";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "No servies found.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function editServiceHoursAndDetails(Request $request) {
        $user_info = $request->userInfo;

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "") {

            $provider_name = urldecode($request->provider_name);
            $description = urldecode($request->description);
            $distance_provided = urldecode($request->distance_provided);

            DB::table('landscapers')->where('user_id', $user_info->user_id)->update(['name' => $provider_name, 'description' => $description, 'distance' => $distance_provided]);

            $userdata = Landscaper::where("user_id", "=", $user_info->user_id)->get();

            foreach ($userdata as $rowdata) {
                $landscaper_id = $rowdata->id;
                $service_id = $rowdata->service_id;

                DB::table('service_times')
                        ->where('landscaper_id', '=', $landscaper_id)
                        ->where('service_id', '=', $service_id)->delete();

                $this->addServiceDays($request, $landscaper_id, $service_id);

//                $s_price = DB::table('service_prices')
//                                ->where('landscaper_id', '=', $landscaper_id)
//                                ->where('service_id', '=', $service_id)->get();
//
//                $rec_services_json = $request->recurring_services;
//                $rec_services = json_decode($rec_services_json, TRUE);
//
//                foreach ($s_price as $index => $one_price) {
//                    DB::table('service_prices')
//                            ->where('id', '=', $one_price->id)
//                            ->update(['discount_price' => $rec_services[$index]['rs']]);
//                }
            }
            $response['success'] = 1;
            $response['msg'] = "Service Hours And Details Updated Successfully.";
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function viewAddressBookList(Request $request) {
        $user_info = $request->userInfo;

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "") {

            $data['addresses'] = DB::table('address_books')->where(['user_id' => $user_info->user_id])->get();

            if (count($data) > 0) {
                $response['data'] = $data;
                $response['success'] = 1;
                $response['msg'] = count($data['addresses']) . " addresses found.";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "No address found.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function addAddressBook(Request $request) {
        $user_info = $request->userInfo;

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->contact_name != "" && $request->email_address != "" && $request->street_address != "") {

            $addressbook = new AddressBook();
            $addressbook->user_id = $user_info->user_id;
            $addressbook->name = $request->contact_name;
            $addressbook->address = $request->street_address;
            $addressbook->city = $request->city;
            $addressbook->state = $request->state;
            $addressbook->country = $request->country;
            $addressbook->contact_number = $request->contact_number;
            $addressbook->email_address = $request->email_address;

            if ($addressbook->save() > 0) {
                $response['success'] = 1;
                $response['msg'] = "Address Added Successfully.";
            } else {
                $response['success'] = 0;
                $response['msg'] = "Address Not Added.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function editAddressBook(Request $request) {
        $user_info = $request->userInfo;

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->address_id != "" && $request->contact_name != "" && $request->email_address != "" && $request->street_address != "") {

            $addressbook = AddressBook::where("id", "=", $request->address_id)->where("user_id", "=", $user_info->user_id)->get()[0];

            $addressbook->name = $request->contact_name;
            $addressbook->address = $request->street_address;
            $addressbook->city = $request->city;
            $addressbook->state = $request->state;
            $addressbook->country = $request->country;
            $addressbook->contact_number = $request->contact_number;
            $addressbook->email_address = $request->email_address;

            if ($addressbook->save() > 0) {
                $response['success'] = 1;
                $response['msg'] = "Address Updated Successfully.";
            } else {
                $response['success'] = 0;
                $response['msg'] = "Address Not Updated.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function confirmBooking(Request $request) {
        $user_info = $request->userInfo;


        $response = [
            'success' => 0,
            'msg' => ''
        ];
        $service_date = strtotime(date('Y-m-d', strtotime($request->service_date)));
        $cur_date = strtotime(date('Y-m-d'));

//       echo $user_info->user_id."<br>";
//       echo $request->landscaper_id."<br>";
//       echo $request->address_book_id."<br>";
//       echo $request->service_price_id."<br>";die();
//       echo "<br>".

        if ($user_info->user_id != "" && $request->booking_time != "" && $request->landscaper_id != "" && $request->address_book_id != "" && $request->service_price_id != "") {
            if ($service_date >= $cur_date) {

                $distance_chk = $this->DistanceCheck($request->landscaper_id, $request->address_book_id);
                if ($distance_chk == 'y') {

                    $payment_acc_obj = new PaymentAccounts();
                    $percentage = $payment_acc_obj->getPercentage();
                    
                    $original_service_price = $request->service_price;
                    $after_tax_service_price = $original_service_price + ($original_service_price * ($percentage/100));

                    $insertData = array(
                        'customer_id' => $user_info->user_id,
                        'landscaper_id' => $request->landscaper_id,
                        'address_book_id' => $request->address_book_id,
                        'service_price_id' => $request->service_price_id,
                        'service_date' => date('Y-m-d', strtotime($request->service_date)),
                        'service_time' => date('H:i:s', strtotime($request->service_time)),
                        'additional_note' => $request->additional_note,
                        'service_price' => $after_tax_service_price,
                        'service_booked_price' => $original_service_price,
                        'lawn_area' => $request->lawn_area,
                        'grass_length' => $request->grass_length,
                        'no_of_cars' => $request->no_of_cars,
                        'driveway_type' => $request->driveway_type,
                        'service_type' => $request->service_type,
                        'leaf_accumulation' => $request->leaf_accumulation,
                        'water_type' => $request->water_type,
                        'include_spa' => $request->include_spa,
                        'pool_type' => $request->pool_type,
                        'pool_state' => $request->pool_state,
                        'no_of_zones' => $request->no_of_zones,
                        'order_no' => "OD" . time() . rand("111111", "999999"),
                        'booking_time' => $request->booking_time,
                        'status' => 0
                    );

                    $bookServiceId = BookService::insertGetId($insertData);

                    $info = DB::select("SELECT bs.id as order_id, bs.service_time ,bs.service_date ,bs.order_no,bs.status,l.user_id as landscaper_user_id, "
                                    . " bs.booking_time,ud.profile_image, ud.first_name , ud.last_name, s.service_name,s.id as service_id "
                                    . " FROM book_services bs,landscapers l,services s,user_details ud "
                                    . " WHERE bs.landscaper_id = l.id AND l.service_id = s.id AND ud.user_id = l.user_id "
                                    . " AND bs.id = " . $bookServiceId);
                    if ($info[0]->status == 0) {
                        $info[0]->status_name = "Service Request Sent";
                    } elseif ($info[0]->status == 1) {
                        $info[0]->status_name = "Request Accepted,Make Payment To Start Work";
                    } elseif ($info[0]->status == 2) {
                        $info[0]->status_name = "Work In Progress,Payment Success";
                    } elseif ($info[0]->status == 3) {
                        $info[0]->status_name = "Job Complete,Payment Success";
                    } elseif ($info[0]->status == -1) {
                        $info[0]->status_name = "Service Request Rejected";
                    }
                    $data = [];
                    if ($info[0]->profile_image != '') {
                        $data['profile_iamge'] = url("uploads/profile_picture/" . $info[0]->profile_image);
                    } else {
                        $data['profile_iamge'] = '';
                    }
                    $data['landscaper_name'] = $info[0]->first_name . ' ' . $info[0]->last_name;
                    $data['status_name'] = "A New Service Requested";
                    $data['service_name'] = $info[0]->service_name;
                    $data['date'] = $info[0]->service_date;
                    $data['time'] = $info[0]->service_time;
                    $data['order_id'] = $info[0]->order_id;
                    $data['order_no'] = $info[0]->order_no;
                    $data['status'] = 0;
                    $data['notify_user_id'] = $info[0]->landscaper_user_id;
                    $data['body'] = "A New Service Requested";
                    $data['booking_time'] = $info[0]->booking_time;

                    $this->send_notification($info[0]->landscaper_user_id, $data, 'landscaper');

                    if ($bookServiceId != "") {
                        $response['data'] = [
                            'bookServiceId' => $bookServiceId,
                            'upload_by' => $user_info->user_id
                        ];
                        $response['success'] = 1;
                        $response['msg'] = "Service Booked Successfully.";
                    } else {
                        $response['success'] = 0;
                        $response['msg'] = "Service Booked Failed.";
                    }
                } else {
                    $response['success'] = 0;
                    $response['msg'] = "Address is too far, out of range from landscaper. Please select another address.";
                }
            } else {
                $response['success'] = 0;
                $response['msg'] = "Service Date should not be less than current date";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

//        }
//        else
//        {
//             $response['success'] = 0;
//             $response['msg'] = "Service Date should not be less than current date";
//        }

        return response()->json($response);
    }

    function uploadServiceImage(Request $request) {

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $bookServiceId = $request->bookServiceId;
        $uploadBy = $request->upload_by;

        $flag = 0;
        $source = $request->source;
        if (isset($source) && $source != "" && $uploadBy != "" && $bookServiceId != "") {
            if ($source == 'android') {
                if (is_uploaded_file($_FILES['property_image']['tmp_name'])) {
                    $fileName = rand(1111, 9999) . "_" . time() . "_" . $_FILES['property_image']['name'];
                    if (move_uploaded_file($_FILES['property_image']['tmp_name'], "uploads/property/" . $fileName)) {
                        $imgArr = [
                            "book_service_id" => $bookServiceId,
                            "uploaded_by" => $uploadBy,
                            "service_image" => $fileName
                        ];
                        ServiceImages::insert($imgArr);
                        $flag = 1;
                    }
                } else {
                    $flag = 0;
                }
            } else if ($source == 'iphone') {
                if (isset($request->property_image) && $request->property_image != "") {

                    $destinationPath = "uploads/property/";
                    $fileName = rand(1111, 9999) . "_" . time() . "_iphone.jpg";
                    $target_path = $destinationPath . $fileName;

                    file_put_contents($target_path, base64_decode($request->property_image));
                    $imgArr = [
                        "book_service_id" => $bookServiceId,
                        "uploaded_by" => $uploadBy,
                        "service_image" => $fileName
                    ];
                    ServiceImages::insert($imgArr);
                    $flag = 1;
                } else {
                    $flag = 0;
                }
            }

            if ($flag == 1) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Property Image Updated Successfully";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Property Image not Updated";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function bookingHistory(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];
        if ($user_info->user_id != "") {


            $sql = "SELECT l.name as landscaper_provider_name,bs.id as order_id, l.user_id as landscaper_user_id, bs.customer_id, bs.landscaper_id, bs.order_no, bs.status, bs.is_completed,bs.transaction_id, "
                    . " ud.profile_image, ud.first_name , ud.last_name, s.service_name,s.id as service_id,bs.booking_time "
                    . " FROM book_services bs,landscapers l,services s,user_details ud "
                    . " WHERE bs.landscaper_id = l.id AND l.service_id = s.id AND ud.user_id = l.user_id "
                    . " AND bs.customer_id = " . $user_info->user_id . " ORDER BY bs.id DESC";

            $result = DB::select($sql);

            foreach ($result as $key => $row) {

                if ($row->profile_image != "")
                    $result[$key]->profile_image = url("uploads/profile_picture/" . $row->profile_image);
                else
                    $result[$key]->profile_image = "";

                if ($row->status == 0)
                    $result[$key]->status_name = "Service Request Sent";
                elseif ($row->status == 1)
                    $result[$key]->status_name = "Request Accepted,Make Payment To Start Work";
                elseif ($row->status == 2)
                    $result[$key]->status_name = "Work In Progress,Payment Success";
                elseif ($row->status == 3)
                    $result[$key]->status_name = "Job Complete,Payment Success";
                elseif ($row->status == -1)
                    $result[$key]->status_name = "Service Request Rejected";

                $result[$key]->favourite_status = $this->getfavbyid($row->landscaper_id, $user_info->user_id);
                //$result[$key]->landscaper_name = $row->first_name . ' ' . $row->last_name;
                $result[$key]->landscaper_user_id = $row->landscaper_user_id;
                $result[$key]->landscaper_name = $row->landscaper_provider_name;
            }

            if (count($result) > 0) {
                $response['data'] = $result;
                $response['success'] = 1;
                $response['msg'] = count($result) . " booking history found.";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Booking History Not Found";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function bookingHistoryDetails(Request $request) {
        $user_info = $request->userInfo;
        $user_id = $user_info->user_id;

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->order_id != '') {

            $sql = "SELECT l.user_id as landscaper_user_id,bs.id as order_id, bs.*,sp.service_frequency,l.name as landscaper_business_name, "
                    . " ud.profile_image, ud.first_name , ud.last_name, s.service_name,s.id as service_id "
                    . " FROM book_services bs,landscapers l,services s,user_details ud,service_prices sp "
                    . " WHERE bs.service_price_id=sp.id AND bs.landscaper_id = l.id AND l.service_id = s.id AND ud.user_id = l.user_id "
                    . " AND bs.id = " . $request->order_id;

            $result = DB::select($sql);
            if (!empty($result)) {
                $landscaper_user_id = $result[0]->landscaper_user_id;
                $landscaper_id = $result[0]->landscaper_id;

                if ($result[0]->profile_image != "")
                    $result[0]->profile_image = url("uploads/profile_picture/" . $result[0]->profile_image);
                else
                    $result[0]->profile_image = "";

                if ($result[0]->status == 0)
                    $result[0]->status_name = "Service Request Sent";
                elseif ($result[0]->status == 1)
                    $result[0]->status_name = "Request Accepted,Make Payment To Start Work";
                elseif ($result[0]->status == 2)
                    $result[0]->status_name = "Work In Progress,Payment Success";
                elseif ($result[0]->status == 3)
                    $result[0]->status_name = "Job Complete,Payment Success";
                elseif ($result[0]->status == -1)
                    $result[0]->status_name = "Service Request Rejected";

                $result[0]->lanscaper_name = $result[0]->first_name . ' ' . $result[0]->last_name;
                $data['landscaper_business_name'] = $result[0]->landscaper_business_name;
                $data['order_details'] = $result[0];

                $sql_one = "SELECT * FROM address_books WHERE id = " . $result[0]->address_book_id;
                $result_one = DB::select($sql_one);

                $data['service_address'] = $result_one[0];

                $sql_two = "SELECT * FROM service_images WHERE book_service_id = " . $request->order_id . " AND uploaded_by =" . $user_id;
                $result_two = DB::select($sql_two);

                foreach ($result_two as $key => $row) {
                    if ($row->service_image != "")
                        $result_two[$key]->service_image = url("uploads/property/" . $row->service_image);
                    else
                        $result_two[$key]->service_image = "";
                }

                $sql_three = "SELECT * FROM service_images WHERE book_service_id = " . $request->order_id . " AND uploaded_by =" . $landscaper_user_id;
                $result_three = DB::select($sql_three);

                foreach ($result_three as $key => $row) {
                    if ($row->service_image != "")
                        $result_three[$key]->service_image = url("uploads/property/" . $row->service_image);
                    else
                        $result_three[$key]->service_image = "";
                }

                $data['service_images'] = $result_two;

                $data['landscaper_service_images'] = $result_three;
                $data['rating_review'] = [];
                $rating_review = $this->getRatingReviewByLandscaper($result[0]->landscaper_id, $user_id, $request->order_id);

                foreach ($rating_review as $one_row) {
                    $data['rating_review'][] = array(
                        'name' => $one_row->first_name . ' ' . $one_row->last_name,
                        'profile_picture' => ($one_row->profile_image != "") ? url("uploads/profile_picture/" . $one_row->profile_image) : "",
                        'rating' => $one_row->rating_value,
                        'review' => $one_row->review,
                        'date' => date('d M Y', strtotime($one_row->log_time))
                    );
                }
                $data["favourite_status"] = $this->getfavbyid($landscaper_id, $user_id);

                if (count($data) > 0) {
                    $response['data'] = $data;
                    $response['success'] = 1;
                    $response['msg'] = "Booking history details found.";
                } else {
                    $response['data'] = [];
                    $response['success'] = 0;
                    $response['msg'] = "Booking history not found";
                }
            } else {
                $response['msg'] = "Wrong Order ID.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function addCard(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->card_no != "" && $request->month != "" && $request->year != "") {

            $payment_accounts = new PaymentAccounts;
            $service_price_obj = new ServicePrice;

            $payment_accounts->user_id = $user_info->user_id;
            $payment_accounts->name = $request->card_holder_name;
            $payment_accounts->card_no = $request->card_no;
            $payment_accounts->month = $request->month;
            $payment_accounts->year = $request->year;
            $payment_accounts->cvv_no = $payment_accounts->encodeCvv($request->cvv_no);
            $payment_accounts->card_brand = $service_price_obj->validatecard($request->card_no);

            if ($payment_accounts->save()) {
                $response['success'] = 1;
                $response['msg'] = "Card Added Successfully.";
            } else {
                $response['success'] = 0;
                $response['msg'] = "Card Not Added";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function viewCardList(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "") {

            $sql = "SELECT * FROM payment_accounts WHERE user_id = " . $user_info->user_id;
            $data['payment_accounts'] = DB::select($sql);

            if (count($data['payment_accounts']) > 0) {
                $response['data'] = $data;
                $response['success'] = 1;
                $response['msg'] = count($data['payment_accounts']) . " Cards Found";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "No Card Found";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function editCard(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->card_id != "" && $request->card_no != "" && $request->month != "" && $request->year != "") {

            $service_price_obj = new ServicePrice;
            $payment_accounts = PaymentAccounts::where("id", "=", $request->card_id)->where("user_id", "=", $user_info->user_id)->get()[0];

            $payment_accounts->user_id = $user_info->user_id;
            $payment_accounts->name = $request->card_holder_name;
            $payment_accounts->card_no = $request->card_no;
            $payment_accounts->month = $request->month;
            $payment_accounts->year = $request->year;
            //$payment_accounts->cvv_no = $request->cvv_no;          
            $payment_accounts->card_brand = $service_price_obj->validatecard($request->card_no);

            if ($payment_accounts->save()) {
                $response['success'] = 1;
                $response['msg'] = "Card Updated Successfully.";
            } else {
                $response['success'] = 0;
                $response['msg'] = "Card Not Updated";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function deleteCard(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->card_id != "") {

            $del = DB::table('payment_accounts')
                    ->where('id', $request->card_id)
                    ->where('user_id', $user_info->user_id)
                    ->delete();

            if ($del) {
                $response['success'] = 1;
                $response['msg'] = "Card Deleted Successfully.";
            } else {
                $response['success'] = 0;
                $response['msg'] = "Card Not Deleted";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function addPaypalAccount(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->paypal_api_username != "" && $request->paypal_api_password != "" && $request->paypal_api_signature != "") {

            $payment_accounts = new PaymentAccounts;

            $payment_accounts->user_id = $user_info->user_id;
            $payment_accounts->name = $request->paypal_name;
            $payment_accounts->account_details = $request->paypal_api_username;
            $payment_accounts->account_password = $request->paypal_api_password;
            $payment_accounts->account_signature = $request->paypal_api_signature;
            $payment_accounts->account_email = $request->paypal_account_email;
            if ($payment_accounts->save()) {
                $response['success'] = 1;
                $response['msg'] = "Paypal Account Added Successfully.";
            } else {
                $response['success'] = 0;
                $response['msg'] = "Paypal Account Not Added";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function viewPaypalAccount(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "") {

            $sql = "SELECT * FROM payment_accounts WHERE user_id = " . $user_info->user_id;
            $data['payment_accounts'] = DB::select($sql);

            if (count($data['payment_accounts']) > 0) {
                $response['data'] = $data;
                $response['success'] = 1;
                $response['msg'] = count($data['payment_accounts']) . " Paypal Account Found";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "No Paypal Account Found";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function editPaypalAccount(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->paypal_id != "" && $request->paypal_api_username != "" && $request->paypal_api_password != "" && $request->paypal_api_signature != "") {

            $payment_accounts = PaymentAccounts::where("id", "=", $request->paypal_id)->where("user_id", "=", $user_info->user_id)->get()[0];

            $payment_accounts->user_id = $user_info->user_id;
            $payment_accounts->name = $request->paypal_name;
            $payment_accounts->account_details = $request->paypal_api_username;
            $payment_accounts->account_password = $request->paypal_api_password;
            $payment_accounts->account_signature = $request->paypal_api_signature;
            $payment_accounts->account_email = $request->paypal_account_email;
            if ($payment_accounts->save()) {
                $response['success'] = 1;
                $response['msg'] = "Paypal Account Updated Successfully.";
            } else {
                $response['success'] = 0;
                $response['msg'] = "Paypal Account Not Updated";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function deletePaypalAccount(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->paypal_id != "") {

            $del = DB::table('payment_accounts')
                    ->where('id', $request->paypal_id)
                    ->where('user_id', $user_info->user_id)
                    ->delete();

            if ($del) {
                $response['success'] = 1;
                $response['msg'] = "Paypal Account Deleted Successfully.";
            } else {
                $response['success'] = 0;
                $response['msg'] = "Paypal Account Not Deleted";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function addFavorite(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->landscaper_id != "" && $request->status != "") {

            $data['user_id'] = $user_info->user_id;
            $data['landscaper_id'] = $request->landscaper_id;
            $res = DB::select("SELECT visible from favorite_landscapers where user_id=" . $user_info->user_id . ' AND landscaper_id = ' . $data['landscaper_id']);

            if (empty($res)) {
                $data['visible'] = 1;
                DB::table('favorite_landscapers')->insert($data);
            } else {
                DB::update("update favorite_landscapers set visible=(case visible when 1 then 0 else 1 end) where user_id=" . $user_info->user_id . ' AND landscaper_id = ' . $data['landscaper_id']);
                $data['visible'] = ($res[0]->visible == 0) ? 1 : 0;
            }

            if ($data['visible'] == 1) {
                $response['success'] = 1;
                $response['msg'] = "Landscaper add to favorite";
            } else {
                $response['success'] = 0;
                $response['msg'] = "Error";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function removeFavorite(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->landscaper_id != "" && $request->status != "") {

            $data['user_id'] = $user_info->user_id;
            $data['landscaper_id'] = $request->landscaper_id;
            $res = DB::select("SELECT visible from favorite_landscapers where user_id=" . $user_info->user_id . ' AND landscaper_id = ' . $data['landscaper_id']);

            if (empty($res)) {
                $data['visible'] = 1;
                DB::table('favorite_landscapers')->insert($data);
            } else {
                DB::update("update favorite_landscapers set visible=(case visible when 1 then 0 else 1 end) where user_id=" . $user_info->user_id . ' AND landscaper_id = ' . $data['landscaper_id']);
                $data['visible'] = ($res[0]->visible == 0) ? 1 : 0;
            }

            if ($data['visible'] == 0) {
                $response['success'] = 1;
                $response['msg'] = "Landscaper remove from favorite";
            } else {
                $response['success'] = 0;
                $response['msg'] = "Error";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    function getfavbyid($lanscaper_id, $user_id) {
        $res = DB::select("SELECT visible FROM favorite_landscapers WHERE user_id=" . $user_id . ' AND landscaper_id = ' . $lanscaper_id);

        if (!empty($res))
            return $res[0]->visible;
        else
            return 0;
    }

    public function viewFavoriteLandscaperList(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "") {

            $sql = "SELECT l.id as lanscaper_id,l.user_id,l.service_id,l.name,l.description,l.profile_image as feature_image, "
                    . "ud.address,ud.city,ud.state FROM landscapers l,user_details ud,favorite_landscapers fl WHERE fl.landscaper_id=l.id  "
                    . " AND l.user_id = ud.user_id AND fl.user_id='" . $user_info->user_id . "' AND fl.visible=1 GROUP BY l.user_id";

            $providers = DB::select($sql);

            foreach ($providers as $key => $one_provider) {
                if ($one_provider->feature_image != "")
                    $providers[$key]->feature_image = url("uploads/services/" . $one_provider->feature_image);
                else
                    $providers[$key]->feature_image = "";

                $providers[$key]->favorite_status = $this->getfavbyid($one_provider->lanscaper_id, $user_info->user_id);
                $lanscaper_obj = new Landscaper();
                $providers[$key]->rating = $lanscaper_obj->get_overall_rating($one_provider->lanscaper_id);
                $providers[$key]->usercount = $lanscaper_obj->get_total_review_count($one_provider->lanscaper_id);
            }

            if (count($providers) > 0) {
                $response['data'] = $providers;
                $response['success'] = 1;
                $response['msg'] = count($providers) . " landscapers Found";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "No landscapers Found";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function bookingHistoryLandscaper(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];
        if ($user_info->user_id != "") {



            $sql_l = "SELECT id FROM landscapers WHERE user_id = " . $user_info->user_id;
            $data_l = DB::select($sql_l);

            foreach ($data_l as $val) {
                $landscaper_arr[] = $val->id;
            }

            $landscaper_str = implode(',', $landscaper_arr);

            if ($landscaper_str != "") {

                $sql = "SELECT bs.id as order_id, bs.customer_id, bs.landscaper_id, bs.order_no, bs.status, bs.is_completed, bs.transaction_id, "
                        . " ud.profile_image, ud.first_name , ud.last_name, s.service_name,s.id as service_id,bs.booking_time "
                        . " FROM book_services bs,landscapers l,services s,user_details ud "
                        . " WHERE bs.landscaper_id = l.id AND l.service_id = s.id AND ud.user_id = bs.customer_id "
                        . " AND bs.landscaper_id IN (" . $landscaper_str . ") ORDER BY bs.id DESC";

                $result = DB::select($sql);

                foreach ($result as $key => $row) {

                    if ($row->profile_image != "")
                        $result[$key]->profile_image = url("uploads/profile_picture/" . $row->profile_image);
                    else
                        $result[$key]->profile_image = "";

                    if ($row->status == 0)
                        $result[$key]->status_name = "Service Request Received";
                    elseif ($row->status == 1)
                        $result[$key]->status_name = "Service Request Accepted,Payment Pending";
                    elseif ($row->status == 2)
                        $result[$key]->status_name = "Work In Progress,Payment Success";
                    elseif ($row->status == 3)
                        $result[$key]->status_name = "Success,Payment Success";
                    elseif ($row->status == -1)
                        $result[$key]->status_name = "Service Request Rejected";

                    $result[$key]->user_name = $row->first_name . ' ' . $row->last_name;
                    $result[$key]->landscaper_user_id = $user_info->user_id;
                }

                if (count($result) > 0) {
                    $response['data'] = $result;
                    $response['success'] = 1;
                    $response['msg'] = count($result) . " booking history found.";
                } else {
                    $response['data'] = [];
                    $response['success'] = 0;
                    $response['msg'] = "Booking History Not Found";
                }
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Booking History Not Found";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function bookingHistoryDetailsLandscaper(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->order_id != '') {

            $pay_acc_pbj = new PaymentAccounts();

            $sql = "SELECT bs.id as order_id, bs.*,sp.service_frequency, "
                    . " ud.profile_image, ud.first_name , ud.last_name, s.service_name,s.id as service_id "
                    . " FROM book_services bs,landscapers l,services s,user_details ud,service_prices sp "
                    . " WHERE bs.service_price_id=sp.id AND bs.landscaper_id = l.id AND l.service_id = s.id AND ud.user_id = l.user_id "
                    . " AND bs.id = " . $request->order_id;

            $result = DB::select($sql);

            if ($result[0]->profile_image != "")
                $result[0]->profile_image = url("uploads/profile_picture/" . $result[0]->profile_image);
            else
                $result[0]->profile_image = "";

            if ($result[0]->status == 0)
                $result[0]->status_name = "Service Request Received";
            elseif ($result[0]->status == 1)
                $result[0]->status_name = "Service Request Accepted,Payment Pending";
            elseif ($result[0]->status == 2)
                $result[0]->status_name = "Work In Progress,Payment Success";
            elseif ($result[0]->status == 3)
                $result[0]->status_name = "Success,Payment Success";
            elseif ($result[0]->status == -1)
                $result[0]->status_name = "Service Request Rejected";

            $result[0]->lanscaper_name = $result[0]->first_name . ' ' . $result[0]->last_name;

            $data['order_details'] = $result[0];
//            $data['order_details']->service_price = $pay_acc_pbj->getLandscaperAmountOnly($data['order_details']->service_price);
            $data['order_details']->service_price = $data['order_details']->service_booked_price;

            $sql_one = "SELECT * FROM address_books WHERE id = " . $result[0]->address_book_id;
            $result_one = DB::select($sql_one);

            $data['service_address'] = $result_one[0];

            $sql_two = "SELECT * FROM service_images WHERE book_service_id = " . $request->order_id;
            $result_two = DB::select($sql_two);

            foreach ($result_two as $key => $row) {
                if ($row->service_image != "")
                    $result_two[$key]->service_image = url("uploads/property/" . $row->service_image);
                else
                    $result_two[$key]->service_image = "";
            }

            $data['service_images'] = $result_two;

            if (count($data) > 0) {
                $response['tax_rate'] = 20;
                $response['data'] = $data;
                $response['success'] = 1;
                $response['msg'] = "Booking history details found.";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Booking history not found";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function giveRateReview(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->order_id != "" && $request->landscaper_id != "" && $request->rating != "" && $request->rating_time != "") {

            $rating_exists = DB::select("select count(*) as rating_count from service_ratings where order_id = " . $request->order_id . " AND initiated_by = " . $user_info->user_id . " AND landscaper_id = " . $request->landscaper_id . " AND customer_id = " . $user_info->user_id . ";");
            $rating_exists = $rating_exists[0]->rating_count;
            if ($rating_exists == 0) {
                $sql = DB::insert("INSERT INTO service_ratings VALUES(NULL," . $request->landscaper_id . "," . $user_info->user_id . "," . $request->order_id . "," . $user_info->user_id . "," . $request->rating . ",'" . $request->review . "','" . $request->rating_time . "')");
            } else {
                $sql = DB::insert("UPDATE service_ratings SET rating_value=" . $request->rating . ",review='" . $request->review . "',log_time='" . $request->rating_time . "' WHERE initiated_by = " . $user_info->user_id . " AND landscaper_id = " . $request->landscaper_id . " AND customer_id = " . $user_info->user_id . "");
            }
            if ($sql) {
                $response['success'] = 1;
                $response['msg'] = "Review and rating added";
            } else {
                $response['success'] = 0;
                $response['msg'] = "Review and rating not added";
            }
        } else {
            $response['msg'] = "Please fill up all the fields";
        }
        return response()->json($response);
    }

    public function getRatingReviewByLandscaper($landscaper_id = "", $user_id = "", $order_id = "") {

        $landscapper_info = DB::select("select * from landscapers where id = '$landscaper_id'");
        $l_user_id = $landscapper_info[0]->user_id;
        $sql = "SELECT ud.first_name,ud.last_name,ud.profile_image,sr.* FROM service_ratings sr,user_details ud WHERE sr.customer_id = ud.user_id AND sr.order_id = $order_id AND sr.customer_id= $user_id AND sr.landscaper_id = " . $landscaper_id . " AND sr.initiated_by != " . $l_user_id;
        $result = DB::select($sql);

        return $result;
    }

    public function viewTransactionHistory(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "") {

            $landscaper_arr = [];
            $sql_l = "SELECT id FROM landscapers WHERE user_id = " . $user_info->user_id;
            $data_l = DB::select($sql_l);

            foreach ($data_l as $val) {
                $landscaper_arr[] = $val->id;
            }

            $landscaper_str = implode(',', $landscaper_arr);

            if ($landscaper_str != "") {

                $sql = "SELECT bs.payment_date,ud.first_name,ud.last_name,ud.profile_image,bs.id as order_id,bs.order_no,bs.landscaper_payment,bs.status,bs.transaction_id "
                        . " FROM user_details ud,book_services bs "
                        . " WHERE ud.user_id = bs.customer_id "
                        . " AND bs.status IN (2,3) AND bs.landscaper_id IN (" . $landscaper_str . ") ORDER BY bs.payment_date DESC ";

                $history = DB::select($sql);

                $total_amount = 0;
                foreach ($history as $key => $one_history) {
                    $one_history->full_name = $one_history->first_name . ' ' . $one_history->last_name;

                    if ($one_history->profile_image != "")
                        $history[$key]->profile_image = url("uploads/profile_picture/" . $one_history->profile_image);
                    else
                        $history[$key]->profile_image = "";

                    if ($one_history->landscaper_payment == "" || $one_history->landscaper_payment == null) {
                        $history[$key]->landscaper_payment = "0.00";
                    } else {
                        $total_amount += $one_history->landscaper_payment;
                    }

//                    if ($one_history->status == 2)
//                        $history[$key]->status_name = 'Processing';
//                    if ($one_history->status == 3)
//                        $history[$key]->status_name = 'Success';

                    if ($one_history->transaction_id != NULL) {
                        $history[$key]->transaction_id = $one_history->transaction_id;
                        $history[$key]->status_name = 'Success';
                    } else {
                        $history[$key]->transaction_id = "";
                        $history[$key]->status_name = 'Processing';
                    }
                    $history[$key]->payment_date = date('d M Y, H:i', strtotime($one_history->payment_date));
                }

                if (count($history) > 0) {
                    $response['data']['total_amount'] = $total_amount;
                    $response['data']['transaction_list'] = $history;
                    $response['success'] = 1;
                    $response['msg'] = count($history) . " Transaction History Found";
                } else {
                    $response['data'] = [];
                    $response['success'] = 0;
                    $response['msg'] = "No Transaction History Found";
                }
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "No Transaction History Found";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function paymentUsingCard(Request $request) {
        $user_info = $request->userInfo;
        $order_id = $request->order_id;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->payment_time != '') {

            $landscaper_id = null;
            $service_price = null;
            $admin_amount = null;
            $landscaper_amount = null;
            $transaction_id = null;
            $landscaper_details = DB::select("SELECT user_id,service_price,bs.order_no FROM book_services bs,landscapers l where bs.landscaper_id=l.id AND bs.id='" . $request->order_id . "'");
            $landscaper_id = $landscaper_details[0]->user_id;
            $service_price = $landscaper_details[0]->service_price;
            $landscaper_payment_details = DB::select("SELECT * from payment_accounts where user_id=" . $landscaper_id);
            $admin_id = DB::select("SELECT id FROM `users` WHERE username= 'admin'");
            $admin_payment_details = DB::select("SELECT * from payment_accounts where user_id=" . $admin_id[0]->id);
            $admin_percentages = DB::select("SELECT * from payment_percentages");
            $admin_amount = ($service_price * $admin_percentages[0]->percentage) / 100;
            $landscaper_amount = $service_price - $admin_amount;
            $payment_time = $request->payment_time;
            $name_arr = explode(' ', $request->customer_name);
            $last_name = '';
            $first_name = $name_arr[0];
            if (isset($name_arr[1]))
                $last_name = $name_arr[1];
            if (!empty($landscaper_payment_details) && !empty($admin_payment_details)) {
                $config1 = array(
                    'apiUsername' => $landscaper_payment_details[0]->account_details,
                    'apiPassword' => $landscaper_payment_details[0]->account_password,
                    'apiSignature' => $landscaper_payment_details[0]->account_signature
                );
                $config2 = array(
                    'apiUsername' => $admin_payment_details[0]->account_details,
                    'apiPassword' => $admin_payment_details[0]->account_password,
                    'apiSignature' => $admin_payment_details[0]->account_signature
                );

                $paypalParam1 = array(
                    'paymentAction' => 'Sale',
                    'itemName' => $landscaper_details[0]->order_no,
                    'itemNumber' => $landscaper_details[0]->order_no,
                    'amount' => $landscaper_amount,
                    'currencyCode' => "USD",
                    'creditCardType' => "VISA",
                    'creditCardNumber' => $request->card_number,
                    'expMonth' => $request->month,
                    'expYear' => $request->year,
                    'cvv' => $request->cvv,
                    'firstName' => $first_name,
                    'lastName' => $last_name,
                    'city' => '',
                    'zip' => '',
                    'countryCode' => 'US',
                );

                $paypalParam2 = array(
                    'paymentAction' => 'Sale',
                    'itemName' => $landscaper_details[0]->order_no,
                    'itemNumber' => $landscaper_details[0]->order_no,
                    'amount' => $admin_amount,
                    'currencyCode' => "USD",
                    'creditCardType' => "VISA",
                    'creditCardNumber' => $request->card_number,
                    'expMonth' => $request->month,
                    'expYear' => $request->year,
                    'cvv' => $request->cvv,
                    'firstName' => $first_name,
                    'lastName' => $last_name,
                    'city' => '',
                    'zip' => '',
                    'countryCode' => 'US',
                );

                $landscaper_pay_transaction_id = $this->paypal_pay_card($config1, $paypalParam1, 'landscaper', $payment_time);

                if ($landscaper_pay_transaction_id != 0) {
                    $admin_pay_transaction_id = $this->paypal_pay_card($config2, $paypalParam2, 'admin', $payment_time);
                }
//echo $landscaper_pay_transaction_id;
//echo $landscaper_pay_transaction_id;
//die;
                if ($landscaper_pay_transaction_id != 0 && $admin_pay_transaction_id != 0) {

                    DB::update("UPDATE book_services SET transaction_id='" . $admin_pay_transaction_id . "',notification_status_landscaper=1,status=2,card_no=" . $paypalParam['creditCardNumber'] . ",mode_of_payment='Debit Card',payment_date='" . $payment_time . "',landscaper_payment=" . $landscaper_amount . ",admin_payment=" . $admin_amount . " WHERE order_no='" . $landscaper_details[0]->order_no . "'");

                    $user_id = $user_info->id;
                    $info = DB::select("SELECT bs.id as order_id, bs.service_time ,bs.service_date ,bs.order_no,bs.status,l.user_id as landscaper_user_id, "
                                    . " bs.payment_date,ud.profile_image, ud.first_name , ud.last_name, s.service_name,s.id as service_id "
                                    . " FROM book_services bs,landscapers l,services s,user_details ud "
                                    . " WHERE bs.landscaper_id = l.id AND l.service_id = s.id AND ud.user_id = l.user_id "
                                    . " AND bs.id = " . $request->order_id);

                    if ($info[0]->status == 0) {
                        $info[0]->status_name = "Service Request Sent";
                    } elseif ($info[0]->status == 1) {
                        $info[0]->status_name = "Request Accepted,Make Payment To Start Work";
                    } elseif ($info[0]->status == 2) {
                        $info[0]->status_name = "Work In Progress,Payment Success";
                    } elseif ($info[0]->status == 3) {
                        $info[0]->status_name = "Job Complete,Payment Success";
                    } elseif ($info[0]->status == -1) {
                        $info[0]->status_name = "Service Request Rejected";
                    }
                    $data = [];
                    if ($info[0]->profile_image != '') {
                        $data['profile_iamge'] = url("uploads/profile_picture/" . $info[0]->profile_image);
                    } else {
                        $data['profile_iamge'] = '';
                    }
                    $data['landscaper_name'] = $info[0]->first_name . ' ' . $info[0]->last_name;
                    $data['status_name'] = "Payment Successful";
                    $data['service_name'] = $info[0]->service_name;
                    $data['date'] = $info[0]->service_date;
                    $data['time'] = $info[0]->service_time;
                    $data['order_id'] = $info[0]->order_id;
                    $data['order_no'] = $info[0]->order_no;
                    $data['status'] = 3;
                    $data['notify_user_id'] = $info[0]->landscaper_user_id;
                    $data['body'] = "Payment Successful";
                    $data['payment_date'] = $info[0]->payment_date;

                    $this->send_notification($info[0]->landscaper_user_id, $data, 'landscaper');

                    $response['success'] = 1;
                    $response['msg'] = "Payment successfully";
                } else {
                    $response['success'] = 0;
                    $response['msg'] = "Payment not successful";
                }
            } else {
                $response['success'] = 0;
                $response['msg'] = "Payment setup is incomplete.";
            }
        } else {
            $response['success'] = 0;
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function autoPaymentUsingCard($user_id, $payment_time, $order_id) {

        $payment_acc_obj = new PaymentAccounts();

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_id != "" && $payment_time != '') {

            $landscaper_id = null;
            $service_price = null;
            $admin_amount = null;
            $landscaper_amount = null;
            $transaction_id = null;
            $landscaper_details = DB::select("SELECT user_id,service_price,bs.order_no,bs.customer_id FROM book_services bs,landscapers l where bs.landscaper_id=l.id AND bs.id='" . $order_id . "'");
            $landscaper_id = $landscaper_details[0]->user_id;
            $service_price = $landscaper_details[0]->service_price;
//            $landscaper_payment_details = DB::select("SELECT * from payment_accounts where user_id=" . $landscaper_id);
            $saved_card_details = DB::select("SELECT * FROM payment_accounts where user_id = " . $landscaper_details[0]->customer_id . " and is_primary = 1");
//        print_r($saved_card_details);exit;

            $admin_id = DB::select("SELECT id FROM `users` WHERE username= 'admin'");
            $admin_payment_details = DB::select("SELECT * from payment_accounts where user_id=" . $admin_id[0]->id);
            $admin_percentages = DB::select("SELECT * from payment_percentages");
            $admin_amount = $service_price;
            $landscaper_amount = $service_price - $admin_amount;
            $payment_time = $payment_time;
            $name_arr = explode(' ', $saved_card_details[0]->name);
            $last_name = '';
            $first_name = $name_arr[0];
            if (isset($name_arr[1]))
                $last_name = $name_arr[1];
//            if (!empty($landscaper_payment_details) && !empty($admin_payment_details)) {
            if (!empty($admin_payment_details)) {

                $config2 = array(
                    'apiUsername' => $admin_payment_details[0]->account_details,
                    'apiPassword' => $admin_payment_details[0]->account_password,
                    'apiSignature' => $admin_payment_details[0]->account_signature
                );



                $paypalParam = array(
                    'paymentAction' => 'Sale',
                    'itemName' => $landscaper_details[0]->order_no,
                    'itemNumber' => $landscaper_details[0]->order_no,
                    'amount' => $admin_amount,
                    'currencyCode' => "USD",
                    'creditCardType' => $saved_card_details[0]->card_brand,
                    'creditCardNumber' => $saved_card_details[0]->card_no,
                    'expMonth' => $saved_card_details[0]->month,
                    'expYear' => $saved_card_details[0]->year,
                    'cvv' => $payment_acc_obj->decodeCvv($saved_card_details[0]->cvv_no),
                    'firstName' => $first_name,
                    'lastName' => $last_name,
                    'city' => '',
                    'zip' => '',
                    'countryCode' => 'US',
                );


                $admin_pay_transaction_id = $this->paypal_pay_card($config2, $paypalParam, 'admin', $payment_time);

//echo $landscaper_pay_transaction_id;
//echo $landscaper_pay_transaction_id;
//die;
//                echo $admin_pay_transaction_id;
                if ($admin_pay_transaction_id != 0) {

                    DB::update("UPDATE book_services SET transaction_id='" . $admin_pay_transaction_id . "',notification_status_landscaper=1,status=2,is_completed=1,card_no=" . $paypalParam['creditCardNumber'] . ",mode_of_payment='Debit Card',payment_date='" . $payment_time . "',landscaper_payment=" . $landscaper_amount . ",admin_payment=" . $admin_amount . " WHERE order_no='" . $landscaper_details[0]->order_no . "'");


//                    $user_id = $user_info->id;
                    $info = DB::select("SELECT bs.id as order_id, bs.service_time ,bs.service_date ,bs.order_no,bs.status,bs.is_completed,l.user_id as landscaper_user_id, "
                                    . " bs.payment_date,ud.profile_image, ud.first_name , ud.last_name, s.service_name,s.id as service_id "
                                    . " FROM book_services bs,landscapers l,services s,user_details ud "
                                    . " WHERE bs.landscaper_id = l.id AND l.service_id = s.id AND ud.user_id = l.user_id "
                                    . " AND bs.id = " . $order_id);

                    if ($info[0]->status == 0 && $info[0]->is_completed == 0) {
                        $info[0]->status_name = "Request Sent";
                    } elseif ($info[0]->status == 1 && $info[0]->is_completed == 0) {
                        $info[0]->status_name = "Request Accepted,Make Payment To Start Work";
                    } elseif ($info[0]->status == 2 && $info[0]->is_completed == 1) {
                        $info[0]->status_name = "Work In Progress,Payment Success";
                    } elseif ($info[0]->status == 3 && $info[0]->is_completed == 1) {
                        $info[0]->status_name = "Waiting for your confirmation,Payment Success";
                    } elseif ($info[0]->status == 3 && $info[0]->is_completed == 2) {
                        $info[0]->status_name = "Job Complete,Payment Success";
                    } elseif ($info[0]->status == -1) {
                        $info[0]->status_name = "Service Request Rejected";
                    }
                    $data = [];
                    if ($info[0]->profile_image != '') {
                        $data['profile_iamge'] = url("uploads/profile_picture/" . $info[0]->profile_image);
                    } else {
                        $data['profile_iamge'] = '';
                    }
                    $data['landscaper_name'] = $info[0]->first_name . ' ' . $info[0]->last_name;
                    $data['status_name'] = "Payment Successful";
                    $data['service_name'] = $info[0]->service_name;
                    $data['date'] = $info[0]->service_date;
                    $data['time'] = $info[0]->service_time;
                    $data['order_id'] = $info[0]->order_id;
                    $data['order_no'] = $info[0]->order_no;
                    $data['status'] = 3;
                    $data['notify_user_id'] = $info[0]->landscaper_user_id;
                    $data['body'] = "Payment Successful";
                    $data['payment_date'] = $info[0]->payment_date;

//                    $this->send_notification($info[0]->landscaper_user_id, $data, 'landscaper');

                    $response['success'] = 1;
                    $response['msg'] = "Payment successfully";
                } else {
                    $response['success'] = 0;
                    $response['msg'] = "Payment not successful";
                }
            } else {
                $response['success'] = 0;
                $response['msg'] = "Payment setup is incomplete.";
            }
        } else {
            $response['success'] = 0;
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function paypal_pay_card($config_arr, $paypalParam, $user_type, $payment_time) {
        $paypal = new PaypalPro($config_arr);

        //Payment details
        $response = $paypal->paypalCall($paypalParam);
        if (isset($response['ACK']) && $response['ACK'] == 'Success') {
            return $response['TRANSACTIONID'];
        } else {
            return 0;
        }
    }

    public function uplaodLandscaperImages(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $bookServiceId = $request->bookServiceId;
        $uploadBy = $request->upload_by;

        $flag = 0;
        $source = $request->source;
        if (isset($source) && $source != "" && $uploadBy != "" && $bookServiceId != "") {
            if ($source == 'android') {
                if (is_uploaded_file($_FILES['property_image']['tmp_name'])) {
                    $fileName = rand(1111, 9999) . "_" . time() . "_" . $_FILES['property_image']['name'];
                    if (move_uploaded_file($_FILES['property_image']['tmp_name'], "uploads/property/" . $fileName)) {
                        $imgArr = [
                            "book_service_id" => $bookServiceId,
                            "uploaded_by" => $uploadBy,
                            "service_image" => $fileName
                        ];
                        ServiceImages::insert($imgArr);
                        $flag = 1;
                    }
                } else {
                    $flag = 0;
                }
            } else if ($source == 'iphone') {
                if (isset($request->property_image) && $request->property_image != "") {

                    $destinationPath = "uploads/property/";
                    $fileName = rand(1111, 9999) . "_" . time() . "_iphone.jpg";
                    $target_path = $destinationPath . $fileName;

                    file_put_contents($target_path, base64_decode($request->property_image));
                    $imgArr = [
                        "book_service_id" => $bookServiceId,
                        "uploaded_by" => $uploadBy,
                        "service_image" => $fileName
                    ];
                    ServiceImages::insert($imgArr);
                    $flag = 1;
                } else {
                    $flag = 0;
                }
            }

            if ($flag == 1) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Property Image Updated Successfully";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Property Image not Updated";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function paymentUsingPaypal(Request $request) {

        $result['payKey'] = $request->payKey;
        $landscaper_amount = $request->landscaper_amount;
        $admin_amount = $request->admin_amount;
        $order_no = $request->order_no;
        $order_id = $request->order_id;
        $payment_time = $request->payment_time;

        if ($result['payKey'] != '' && $landscaper_amount != '' && $admin_amount != '' && $order_no != '' && $payment_time != '') {
            $qry = DB::update("UPDATE book_services SET transaction_id='" . $result['payKey'] . "',status=2,mode_of_payment='PayPal',payment_date='" . $payment_time . "'  ,landscaper_payment=" . $landscaper_amount . ",admin_payment=" . $admin_amount . ",notification_status_landscaper=1 WHERE order_no='" . $order_no . "'");
            $user_info = $request->userInfo;
            $user_id = $user_info->id;
            $info = DB::select("SELECT bs.id as order_id, bs.service_time ,bs.service_date ,bs.order_no,bs.status,l.user_id as landscaper_user_id, "
                            . " bs.payment_date,ud.profile_image, ud.first_name , ud.last_name, s.service_name,s.id as service_id "
                            . " FROM book_services bs,landscapers l,services s,user_details ud "
                            . " WHERE bs.landscaper_id = l.id AND l.service_id = s.id AND ud.user_id = l.user_id "
                            . " AND bs.id = " . $request->order_id);

            if ($info[0]->status == 0) {
                $info[0]->status_name = "Service Request Sent";
            } elseif ($info[0]->status == 1) {
                $info[0]->status_name = "Request Accepted,Make Payment To Start Work";
            } elseif ($info[0]->status == 2) {
                $info[0]->status_name = "Work In Progress,Payment Success";
            } elseif ($info[0]->status == 3) {
                $info[0]->status_name = "Job Complete,Payment Success";
            } elseif ($info[0]->status == -1) {
                $info[0]->status_name = "Service Request Rejected";
            }
            $data = [];
            if ($info[0]->profile_image != '') {
                $data['profile_iamge'] = url("uploads/profile_picture/" . $info[0]->profile_image);
            } else {
                $data['profile_iamge'] = '';
            }
            $data['landscaper_name'] = $info[0]->first_name . ' ' . $info[0]->last_name;
            $data['status_name'] = "Payment Successful";
            $data['service_name'] = $info[0]->service_name;
            $data['date'] = $info[0]->service_date;
            $data['time'] = $info[0]->service_time;
            $data['order_id'] = $info[0]->order_id;
            $data['order_no'] = $info[0]->order_no;
            $data['status'] = 3;
            $data['notify_user_id'] = $info[0]->landscaper_user_id;
            $data['body'] = "Payment Successful";
            $data['payment_date'] = $info[0]->payment_date;

            $this->send_notification($info[0]->landscaper_user_id, $data, 'landscaper');

            $response['success'] = 1;
            $response['msg'] = "Payment successfully";
        } else {
            $response['msg'] = "Please provide all the details";
        }
        return response()->json($response);
    }

    public function notify($type, $tokens, $data) {
        /*
          echo $type;
          echo "<br>";
          print_r($tokens);
          echo "<br>";
          print_r($data);
          echo "<br>";
          exit;
         */

        $android_token = "AAAAu83DdAI:APA91bEZvhxX4LPEWjvx_PUbbnsKrIaSjvZwdcyFE7B8wAOHWePL1tW3yOj1pfY40Jkx8BzgeNxN4TwHRk0G-rIyc9MAgtl_vyfVLEtiM8ZwTn_-a6rLHL0-S7rglC3kx0zLYuLzWNCHrR90NS2mUOwNKwUgBji4Sw";
        $ios_token = "AAAAu83DdAI:APA91bEZvhxX4LPEWjvx_PUbbnsKrIaSjvZwdcyFE7B8wAOHWePL1tW3yOj1pfY40Jkx8BzgeNxN4TwHRk0G-rIyc9MAgtl_vyfVLEtiM8ZwTn_-a6rLHL0-S7rglC3kx0zLYuLzWNCHrR90NS2mUOwNKwUgBji4Sw";

        if ($type == "android") {
            $api_key = $android_token;

            //$data['body'] = "A new service is available.";

            $fcmFields = array(
                'registration_ids' => $tokens,
                'priority' => 'high',
                'data' => $data
            );
        } else if ($type == "iphone") {
            $api_key = $ios_token;

            //$data['body'] = "A new service is available.";
            $data['title'] = "Seazoned";
            $data['sound'] = "default";
            $data['color'] = "#203E78";
            //$data['badge'] = "1";

            $fcmFields = array(
                'registration_ids' => $tokens,
                'priority' => 'high',
                'notification' => $data
            );
        }

        // Send Notifications
        $headers = array(
            'Authorization: key=' . $api_key,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
        $result = curl_exec($ch);
        curl_close($ch);

        // echo "<pre>";
        // print_r($result);
        // echo "</pre>";
        // exit;
    }

    public function subscribe(Request $request) {
        $user_info = $request->userInfo;
        $user_id = $user_info->user_id;

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $device_token = $request->device_token;
        $device_type = $request->device_type;
        $user_type = $request->user_type;

        if ($device_token != "" && $device_type != "") {

            $count = DB::table('firebases')
                    ->where('user_id', $user_id)
                    ->where('device_type', $device_type)
                    ->where('user_type', $user_type)
                    ->count();

            if ($count == 0) {
                DB::table('firebases')->insert(
                        [
                            'user_id' => $user_id,
                            'device_type' => $device_type,
                            'device_token' => $device_token,
                            'user_type' => $user_type,
                        ]
                );

                $response['success'] = 1;
                $response['msg'] = "Subscribed successfully";
            } else {
                DB::table('firebases')
                        ->where('user_id', $user_id)
                        ->where('device_type', $device_type)
                        ->where('user_type', $user_type)
                        ->update(['device_token' => $device_token]);

                $response['success'] = 1;
                $response['msg'] = "Updated successfully";
            }
        } else {
            $response['msg'] = "Please provide all the fields.";
        }

        return response()->json($response);
    }

    public function notification_test(Request $request) {

        $data = [];

        $data['profile_iamge'] = url("uploads/profile_picture/profile_image.jpg");
        $data['landscaper_name'] = "Garden Assistance Team";
        $data['status_name'] = "Completed";
        $data['service_name'] = "Lawn Mowing";
        $data['date'] = "26 March 2018";
        $data['time'] = "03:15 pm";
        $data['order_id'] = "1";
        $data['order_no'] = "ODR9874521360";
        $data['status'] = 1;

        echo json_encode($data);

        //$android_user_token = ["eCFVoAB7BEA:APA91bGhJKdYVpwXC34bSulZmR-MBOR0ldIJKt0IAQdyyxgWYNUdC3qJOhXoqikAm-HkJXZsq1VOt1j-PBQDUO6dxqxfYXIUEeSqOHEw-2sKpS2huSd5oWC6_z6iaMb17_W5DBHIEnGk"];
        $android_landscaper_token = ["fpNYm4H0ayE:APA91bEc9xK_XcRpuGYKADI0gNPPahmbNkkXbYZpYuSvK2tetZPiTuvdt32SqtBr6yb-Y3IbyzASymNIXqb_LSplY4yca0-B7quyMcFyDt86KHKnmm_1qdw6QsGGfuE5wvF6D_4KmYWW"];
        //$ios_token = ["eXt8nMWDRgY:APA91bHSjmSO5XwBHiXX-ymEo60qqfXF7mLoBkOw0UTVvOjpzXWjmPybNGv_TZRdRtQwCWvQMKLKWraxS-zkPdatfFrs0_nFjcZTmGFfnmhSke-tNAV9j-fCVMa1eUAej34u3m5Ag6xo"];
        //$this->notify('android', $android_user_token, $data);
        $this->notify('android', $android_landscaper_token, $data);
        //$this->notify('iphone', $ios_token, $data);
    }

    public function send_notification($user_id = "", $data = array(), $user_type = "") {
        if ($user_id != "" && !empty($data) && $user_type != "") {

            $firebase_android = DB::table('firebases')
                    ->where('user_id', $user_id)
                    ->where('device_type', 'android')
                    ->where('user_type', $user_type)
                    ->first();

            if ($firebase_android) {
                $token = [$firebase_android->device_token];
                $this->notify('android', $token, $data);
            }

            $firebase_iphone = DB::table('firebases')
                    ->where('user_id', $user_id)
                    ->where('device_type', 'iphone')
                    ->where('user_type', $user_type)
                    ->first();

            if ($firebase_iphone) {
                $token = [$firebase_iphone->device_token];
                $this->notify('iphone', $token, $data);
            }
        }
    }

    public function endJobLandscaper(Request $request) {
        $user_info = $request->userInfo;
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        if ($user_info->user_id != "" && $request->completion_time != '') {

            $order_id = $request->order_id;
            $completion_time = $request->completion_time;
            $sql = DB::update("UPDATE book_services SET status=3 ,`completion_date`= '$completion_time',notification_status_user=1 WHERE id=" . $order_id . "");

            $info = DB::select("SELECT bs.id as order_id, bs.service_time ,bs.completion_date,bs.service_date ,bs.order_no,bs.status,bs.customer_id, "
                            . " ud.profile_image, ud.first_name , ud.last_name, s.service_name,s.id as service_id "
                            . " FROM book_services bs,landscapers l,services s,user_details ud "
                            . " WHERE bs.landscaper_id = l.id AND l.service_id = s.id AND ud.user_id = l.user_id "
                            . " AND bs.id = " . $order_id);
            if ($info[0]->status == 0) {
                $info[0]->status_name = "Service Request Sent";
            } elseif ($info[0]->status == 1) {
                $info[0]->status_name = "Service Request Accepted,Payment Pending";
            } elseif ($info[0]->status == 2) {
                $info[0]->status_name = "Work In Progress,Payment Success";
            } elseif ($info[0]->status == 3) {
                $info[0]->status_name = "Success,Payment Success";
            } elseif ($info[0]->status == -1) {
                $info[0]->status_name = "Service Request Rejected";
            }

            $data = [];
            if ($info[0]->profile_image != '') {
                $data['profile_iamge'] = url("uploads/profile_picture/" . $info[0]->profile_image);
            } else {
                $data['profile_iamge'] = '';
            }
            $data['landscaper_name'] = $info[0]->first_name . ' ' . $info[0]->last_name;
            $data['status_name'] = "Job has been completed";
            $data['service_name'] = $info[0]->service_name;
            $data['date'] = $info[0]->service_date;
            $data['time'] = $info[0]->service_time;
            $data['order_id'] = $info[0]->order_id;
            $data['order_no'] = $info[0]->order_no;
            $data['status'] = 2;
            $data['notify_user_id'] = $info[0]->customer_id;
            $data['body'] = "Job has been completed";
            $data['completion_date'] = $info[0]->completion_date;

            $this->send_notification($info[0]->customer_id, $data, 'user');

            if ($sql) {
                $response['success'] = 1;
                $response['msg'] = "Job Done successfully";
            } else {
                $response['msg'] = "Not updated successfully";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function admin_paypal_details() {

        $admin_id = DB::select("SELECT id FROM `users` WHERE username= 'admin'");
        if ($admin_id[0]->id != '') {
            $data['admin_payment_details'] = DB::select("SELECT * from payment_accounts where user_id=" . $admin_id[0]->id);
            $data['admin_percentages'] = DB:: select("SELECT percentage FROM `payment_percentages` WHERE user_id =" . $admin_id[0]->id);

            $response['data'] = $data;
            $response['success'] = 1;
            $response['msg'] = "Admin details";
        } else {
            $response['msg'] = "No such username exists";
        }
        return response()->json($response);
    }

    public function landscaper_paypal_details(Request $request) {

        $landscaper_id = $request->landscaper_id;
        $user_info = DB::select("SELECT user_id FROM `landscapers` WHERE id =" . $landscaper_id);

        if ($user_info[0]->user_id != "") {

            $sql = "SELECT * FROM payment_accounts WHERE user_id = " . $user_info[0]->user_id;
            $data['payment_accounts'] = DB::select($sql);

            if (count($data['payment_accounts']) > 0) {
                $response['data'] = $data;
                $response['success'] = 1;
                $response['msg'] = count($data['payment_accounts']) . " Paypal Account Found";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "No Paypal Account Found";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function contact_us_landscaper(Request $request) {

        if ($request->name != '' && $request->email != '' && $request->desc != '') {

            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['description'] = $request->desc;

            DB::table("contacts")->insert(['email' => $request->email,
                'name' => $request->name,
                'description' => $request->desc]);


            Mail::send('Home.emailcontent', ['data' => $data], function ($message) {
                $message->subject("Contact Us ");
                $message->from('admin@seazoned.com', 'Seazoned Admin');
                $message->to('info@seazoned.com');
            });

            $response['data'] = $data;
            $response['success'] = 1;
            $response['msg'] = "Thank you. Your Message Has Been Sent to Seazoned. You will be notified shortly.";
        } else {
            $response['msg'] = "Please fill up all the fields";
        }
        return response()->json($response);
    }

    public function contact_us_user(Request $request) {

        if ($request->name != '' && $request->email != '' && $request->desc != '') {

            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['description'] = $request->desc;

            DB::table("contacts")->insert(['email' => $request->email,
                'name' => $request->name,
                'description' => $request->desc]);

            //Log::info("Request cycle without Queues started");

            Mail::send('Home.emailcontent', ['data' => $data], function ($message) {
                $message->subject("Contact Us");
                $message->from('admin@seazoned.com', 'Seazoned Admin');
                $message->to('info@seazoned.com');

                //Log::info("End of Email Processing....");
            });
            //Log::info("Request cycle without Queues finished");

            $response['data'] = $data;
            $response['success'] = 1;
            $response['msg'] = "Thank you. Your Message Has Been Sent to Seazoned. You will be notified shortly.";
        } else {
            $response['msg'] = "Please fill up all the fields";
        }
        return response()->json($response);
    }

    public function get_notification_list_user(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $user_info = $request->userInfo;
        $user_id = $user_info->user_id;
        if (isset($user_id)) {
            $sql = "UPDATE book_services SET notification_status_user = 0 WHERE customer_id = " . $user_id . " AND notification_status_user = 1";
            $res = DB::update($sql);
            $bookService_obj = new BookService;
            $data['notification_list_user'] = $bookService_obj->notification_list_user_get($user_id);

            if (count($data) > 0) {
                $response['data'] = $data;
                $response['success'] = 1;
                $response['msg'] = "Notification found.";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "No Notification found.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function get_notification_list_landscaper(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $user_info = $request->userInfo;
        $user_id = $user_info->user_id;
        $lands_ids = [];
        if (isset($user_id)) {
            $l_ids = DB::select("SELECT * FROM landscapers WHERE user_id =" . $user_id);
            if (!empty($l_ids)) {
                foreach ($l_ids as $lids) {
                    $lands_ids[] = $lids->id;
                }
                $lands_ids = implode(',', $lands_ids);
                $sql = "UPDATE book_services SET notification_status_landscaper = 0 WHERE landscaper_id IN ($lands_ids) AND notification_status_landscaper = 1";
                $res = DB::update($sql);
            }

            $bookService_obj = new BookService;
            $data["notification_list_landcaper"] = $bookService_obj->notification_list_landscaper_get($user_id);

            if (count($data) > 0) {
                $response['data'] = $data;
                $response['success'] = 1;
                $response['msg'] = "Notification found.";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "No Notification found.";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

    public function faq() {

        $data['faq'] = DB::select("SELECT * FROM `faq` ");
        if (!empty($data)) {
            $response['data'] = $data;
            $response['success'] = 1;
            $response['msg'] = "FAQ's";
        } else {
            $response['success'] = 0;
            $response['msg'] = "No FAQ's found";
        }
        return response()->json($response);
    }

    public function get_faq(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $profile_id = $request->profile_id;
        if ($profile_id != "") {
            if ($profile_id == 2 || $profile_id == 3) {
                $data = DB::select("SELECT * FROM `faq` WHERE profile_id=" . $profile_id);
                if (!empty($data)) {

                    foreach ($data as $key => $val) {
                        $clear = "";
                        // Strip HTML Tags
                        $clear = strip_tags($data[$key]->answers);
                        // Clean up things like &amp;
                        $clear = html_entity_decode($clear);
                        // Strip out any url-encoded stuff
                        $clear = urldecode($clear);
                        // Replace non-AlNum characters with space
                        $clear = preg_replace('/[^A-Za-z0-9]/', ' ', $clear);
                        // Replace Multiple spaces with single space
                        $clear = preg_replace('/ +/', ' ', $clear);
                        // Trim the string of leading/trailing space
                        $clear = trim($clear);

                        $data[$key]->answers = $clear;
                    }

                    $response['data'] = $data;
                    $response['success'] = 1;
                    $response['msg'] = "FAQ's";
                } else {
                    $response['success'] = 0;
                    $response['msg'] = "No FAQ's found";
                }
            } else {

                $response['success'] = 0;
                $response['msg'] = "Please Give Valid Profile_id";
            }
        } else {
            $response['success'] = 0;
            $response['msg'] = "Please fill up the fields.";
        }
        return response()->json($response);
    }

    public function user_chat_list(Request $request) {

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $user_info = $request->userInfo;
        $user_id = $user_info->user_id;
        if (isset($user_id)) {
            $sql = DB::select("SELECT * FROM user_details WHERE user_id = $user_id");
            $data['user_profile_image'] = $sql[0]->profile_image;
            if (!empty($data['user_profile_image']))
                $data['user_profile_image'] = url("uploads/profile_picture/" . $data['user_profile_image']);
            else {
                $data['user_profile_image'] = '';
            }
            $sql_one = "SELECT landscapers.name as landscapers_business_name,ud.profile_image as landscaper_profile_image, book_services.id as book_service_id, book_services.customer_id,book_services.landscaper_id,users.id as landscaper_user_id,
                    book_services.order_no,services.service_name,services.id as service_id,services.logo_name as service_logo, ud.first_name as landscaper_user_first_name,ud.last_name as landscaper_user_last_name FROM book_services INNER JOIN landscapers ON book_services.landscaper_id=landscapers.id INNER JOIN
                    users ON landscapers.user_id=users.id INNER JOIN services ON landscapers.service_id = services.id INNER JOIN user_details ud ON landscapers.user_id = ud.user_id WHERE customer_id =$user_id  ORDER BY book_services.id DESC ";
            $data['message'] = DB::select($sql_one);
            foreach ($data['message'] as $key => $d) {

                if ($d->landscaper_profile_image != '') {
                    $data['message'][$key]->landscaper_profile_image = url("uploads/profile_picture/" . $d->landscaper_profile_image);
                } else {
                    $data['message'][$key]->landscaper_profile_image = '';
                }
                $data['message'][$key]->android_firebase_token = $this->getFirebaseDetails($d->landscaper_user_id, 'android');
                $data['message'][$key]->iphone_firebase_token = $this->getFirebaseDetails($d->landscaper_user_id, 'iphone');

                if ($data['message'][$key]->android_firebase_token == "" && $data['message'][$key]->iphone_firebase_token == "") {
                    unset($data['message'][$key]);
                }
            }
            $data['message'] = array_merge($data['message']);

            if (!empty($data['message'])) {
                $response['data'] = $data;
                $response['success'] = 1;
                $response['msg'] = "Chat List Found";
            } else {
                $response['success'] = 0;
                $response['msg'] = "No Chat List Found";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function getFirebaseDetails($land_id, $device_type) {
        $sql = DB::select("SELECT * FROM firebases WHERE user_id = $land_id AND device_type='$device_type'");
        if (!empty($sql)) {
            return $sql[0]->device_token;
        } else {
            return '';
        }
    }

    public function landscaper_chat_list(Request $request) {

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $user_info = $request->userInfo;
        $user_id = $user_info->user_id;
        if (isset($user_id)) {
            $sql = DB::select("SELECT * FROM landscapers WHERE user_id =$user_id ");
            $data['landscaper_profile_image'] = $sql[0]->profile_image;
            if (!empty($data['landscaper_profile_image']))
                $data['landscaper_profile_image'] = url("uploads/profile_picture/" . $data['landscaper_profile_image']);
            else {
                $data['landscaper_profile_image'] = '';
            }
            $data['message'] = DB::select("SELECT bs.id as book_id,bs.customer_id,bs.order_no,l.id as landscaper_id,l.name as landscaper_name,l.user_id as landscaper_user_id,l.service_id,services.service_name,services.logo_name as service_logo,"
                            . "ud.profile_image as user_profile_image,ud.first_name as customer_first_name,ud.last_name as customer_last_name "
                            . "FROM landscapers l INNER JOIN book_services bs ON l.id=bs.landscaper_id INNER JOIN services ON services.id = l.service_id INNER JOIN user_details ud ON"
                            . " ud.user_id = bs.customer_id  "
                            . "WHERE l.user_id =" . $user_id . " ORDER BY bs.id DESC ");

            foreach ($data['message'] as $key => $d) {

                if ($d->user_profile_image != '') {
                    $data['message'][$key]->user_profile_image = url("uploads/profile_picture/" . $d->user_profile_image);
                } else {
                    $data['message'][$key]->user_profile_image = '';
                }
                $data['message'][$key]->android_firebase_token = $this->getFirebaseDetails($d->customer_id, 'android');
                $data['message'][$key]->iphone_firebase_token = $this->getFirebaseDetails($d->customer_id, 'iphone');
                if ($data['message'][$key]->android_firebase_token == "" && $data['message'][$key]->iphone_firebase_token == "") {
                    unset($data['message'][$key]);
                }
            }

            $data['message'] = array_merge($data['message']);
            if (!empty($data['message'])) {
                $response['data'] = $data;
                $response['success'] = 1;
                $response['msg'] = "Chat List Found";
            } else {
                $response['success'] = 0;
                $response['msg'] = "No Chat List Found";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }
        return response()->json($response);
    }

    public function emailCheck(Request $request) {

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $otp = rand(111111, 999999);
        $email = $request->email;
        $profile_id = $request->profile_id;
        if ($email != '' && $profile_id != '') {
            $data = [];
            $sql = DB::select("select * from users where username='$email'");
            if (!empty($sql)) {
                if ($sql[0]->profile_id == $profile_id) {
                    $modify = DB::table("users")->where('username', $email)->update([
                        'otp' => $otp,
                        'forgot_password_status' => 1
                    ]);
                    if ($modify) {
                        $data['otp'] = $otp;
                        $data['email'] = $email;

                        $ud = DB::select("select first_name,last_name,user_id from user_details where email='$email'");

                        if (!empty($ud)) {
                            $data['name'] = $ud[0]->first_name . ' ' . $ud[0]->last_name;
                        } else {
                            $data['name'] = "";
                        }

                        Mail::send('otp_send', ['data' => $data], function ($message) use($data) {
                            $message->subject("Seazoned Forgot Password Confirmation");
                            $message->from('admin@seazoned.com', 'Seazoned ');
                            $message->to($data['email']);
                        });


                        $response['data'] = $data['otp'];
                        $response['success'] = 1;
                        $response['msg'] = "OTP Sending Successful";
                    } else {
                        $response['success'] = 0;
                        $response['msg'] = "OTP Not Send";
                    }
                } else {
                    $response['success'] = 0;

                    if ($profile_id == 2) {
                        $response['msg'] = "Enter Correct User Email Id";
                    } else if ($profile_id == 3) {
                        $response['msg'] = "Enter Correct Provider Email Id";
                    }
                }
            } else {
                $response['success'] = 0;
                $response['msg'] = "Email does not exist";
            }
        } else {
            $response['success'] = 0;
            $response['msg'] = "Please fill up all the fields";
        }
        return response()->json($response);
    }

    public function otpCheck(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];
        $email = $request->email;
        $otp = $request->otp;
        if ($email != "" && $otp != "") {
            $sql = DB::select("select otp,username from users where username='$email' and forgot_password_status='1'");
            if (!empty($sql)) {
                if ($sql[0]->otp == $otp) {
                    $response['data'] = $sql[0]->username;
                    $response['success'] = 1;
                    $response['msg'] = "Correct Otp Inserted";
                } else {
                    $response['success'] = 0;
                    $response['msg'] = "Enter Correct Otp";
                }
            } else {
                $response['success'] = 0;
                $response['msg'] = "Enter valid email Address";
            }
        } else {
            $response['success'] = 0;
            $response['msg'] = "Please Fill Up All The Fields";
        }
        return response()->json($response);
    }

    public function enterNewPassword(Request $request) {
        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $email = $request->email;
        if ($request->new_password != "" && $email != "") {

            $new_password = md5(urldecode($request->new_password));
            $sql = DB::select("select * from users where username='$email' and forgot_password_status='1'");
            if (!empty($sql)) {
                if ($sql[0]->password != $new_password) {
                    $modify = DB::table("users")->where('username', $email)->update([
                        'password' => $new_password,
                        'otp' => '',
                        'forgot_password_status' => 0
                    ]);
                    if ($modify) {
                        $response['success'] = 1;
                        $response['msg'] = "Password Changed Successfully";
                    } else {
                        $response['success'] = 0;
                        $response['msg'] = "Password Not Changed";
                    }
                } else {
                    $response['success'] = 0;
                    $response['msg'] = "Old Password And New Password Both Are Same";
                }
            } else {
                $response['success'] = 0;
                $response['msg'] = "User Details Not Found";
            }
        } else {
            $response['success'] = 0;
            $response['msg'] = "Please Fill Up All The Fields";
        }
        return response()->json($response);
    }

    public function DistanceCheck($landscaper_id = "", $address_book_id = "") {
        $lanscaper_id = $landscaper_id;
        $data = AddressBook::where("id", "=", $address_book_id)->get()[0];
        $address = $data['address'];

        $formattedAddr = str_replace(' ', '+', $address);
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyC1XtsegzOIIBaqGL0QugHFIaL2pvp8f8A&address=' . $formattedAddr . '&sensor=false', false, stream_context_create($arrContextOptions));
        $maps_result = json_decode($geocodeFromAddr);

        if (!empty($maps_result)) {
            if ($maps_result->status != 'OVER_QUERY_LIMIT') {
                if (isset($maps_result->results[0]->geometry->location->lat))
                    $latitude = $maps_result->results[0]->geometry->location->lat;
                if (isset($maps_result->results[0]->geometry->location->lng))
                    $longitude = $maps_result->results[0]->geometry->location->lng;
            }
        }

        $radius_query = "SELECT id,distance,
                ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $latitude ) ) + COS( RADIANS( `latitude` ) )
                * COS( RADIANS( $latitude )) * COS( RADIANS( `longitude` ) - RADIANS( $longitude )) ) * 6371 AS `fromdistance`
                FROM `landscapers`
                WHERE
                id=" . $lanscaper_id;

        $nearby = DB::select($radius_query);
        $distance = $nearby[0]->distance;
        $fromdistance = $nearby[0]->fromdistance;

        if ($fromdistance <= $distance) {
            return 'y';
        } else {
            return 'n';
        }
    }

    public function notificationStatus(Request $request) {

        $response = [
            'success' => 0,
            'msg' => ''
        ];
        $user_info = $request->userInfo;
        $user_id = $user_info->user_id;
        if ($user_id != '') {
            if ($user_info->profile_id == 2) {
                $sql = "SELECT count(id) as count FROM book_services WHERE customer_id = " . $user_id . " AND notification_status_user = 1";
            } else {
                $sql = "SELECT count(bs.id) as count FROM landscapers l INNER JOIN book_services bs ON l.id=bs.landscaper_id WHERE l.user_id = " . $user_id . " AND bs.notification_status_landscaper = 1";
            }
            $rows = DB::select($sql);
            if ($rows[0]->count > 0) {
                $response['notification_count'] = $rows[0]->count;
                $response['success'] = 1;
                $response['msg'] = $rows[0]->count . " Unread Notifications Found";
            } else {
                $response['notification_count'] = 0;
                $response['success'] = 0;
                $response['msg'] = "No Unread Notifications Found";
            }
        } else {
            $response['success'] = 0;
            $response['msg'] = "Please Fill Up All The Fields";
        }
        return response()->json($response);
    }

    public function setPrimaryCard(Request $request) {

        $response = [
            'success' => 0,
            'msg' => ''
        ];

        $card_id = $request->card_id;
        $user_id = $request->user_id;
        if ($card_id != '' && $user_id != '') {

            $sql1 = "UPDATE payment_accounts SET is_primary=0 WHERE user_id=" . $user_id;
            $sql2 = "UPDATE payment_accounts SET is_primary=1 WHERE id=" . $card_id;

            $ret1 = DB::update($sql1);
            $ret2 = DB::update($sql2);
            if ($ret2) {

                $response['success'] = 1;
                $response['msg'] = "Card has saved as primary successfully";
            } else {

                $response['success'] = 0;
                $response['msg'] = "Error occurred";
            }
        } else {
            $response['success'] = 0;
            $response['msg'] = "Please Fill Up All The Fields";
        }
        return response()->json($response);
    }

    public function checkPrimaryCard(Request $request) {

        $response = [
            'success' => 0,
            'msg' => ''
        ];


        $user_id = $request->user_id;
        if ($user_id != '') {

            $sql = "SELECT COUNT(id) as data_count FROM payment_accounts WHERE is_primary=1 AND user_id=" . $user_id;


            $ret = DB::select($sql);
            if ($ret[0]->data_count > 0) {

                $response['success'] = 1;
                $response['msg'] = "Card found";
            } else {

                $response['success'] = 0;
                $response['msg'] = "You must first add a payment method in order to complete your transaction. Please click on \"add new credit/debit card\" to do so. You may also visit the \"payment info\" tab at the top of the screen to add and manage your payment methods.";
            }
        } else {
            $response['success'] = 0;
            $response['msg'] = "Please Fill Up All The Fields";
        }
        return response()->json($response);
    }

    function changeLandscaperDriverImage(Request $request) {

        $response = [
            'data' => [],
            'success' => 0,
            'msg' => ''
        ];

        $userId = $request->get('user_id');

        $user = User::find($userId);
        $old_image = $user->drivers_license;

        $flag = 0;
        $source = urldecode($request->source);
        if (isset($source) && $source != "") {
            if ($source == 'android') {
                if (is_uploaded_file($_FILES['drivers_license']['tmp_name'])) {
                    $fileName = rand(1111, 9999) . "_" . time() . "_" . $_FILES['drivers_license']['name'];
                    if (move_uploaded_file($_FILES['drivers_license']['tmp_name'], "uploads/drivers_license/" . $fileName)) {
                        $user->drivers_license = $fileName;                        

                        $user->save();
                        $flag = 1;
                        if ($old_image != "" && File::exists("uploads/drivers_license/" . $old_image)) {
                            File::delete("uploads/drivers_license/" . $old_image);
                        }
                    }
                }
            } else if ($source == 'iphone') {
                if (isset($request->drivers_license) && $request->drivers_license != "") {

                    $destinationPath = "uploads/drivers_license/";
                    $fileName = rand(1111, 9999) . "_" . time() . "_iphone.jpg";
                    $target_path = $destinationPath . $fileName;

                    file_put_contents($target_path, base64_decode($request->drivers_license));
                    $user->drivers_license = $fileName;

                    $user->save();

                    $flag = 1;
                    if ($old_image != "" && File::exists("uploads/drivers_license/" . $old_image)) {
                        File::delete("uploads/drivers_license/" . $old_image);
                    }
                }
            }

            if ($flag == 1) {
                $response['data'] = [];
                $response['success'] = 1;
                $response['msg'] = "Driver License Image Updated Successfully";
            } else {
                $response['data'] = [];
                $response['success'] = 0;
                $response['msg'] = "Driver License Image Not Updated";
            }
        } else {
            $response['msg'] = "Please fill up all the fields.";
        }

        return response()->json($response);
    }

}

?>