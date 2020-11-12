<?php

namespace App\Http\Controllers;

use App\AddressBook;
use App\BookService;
use App\Landscaper;
use App\Service;
use App\ServiceImages;
use App\ServicePrice;
use App\UserDetail;
use App\PaymentAccounts;
use App\Country;
use App\ServiceRating;
use App\Classes\PayPal;
use App\Classes\PayPalMass;
use App\Classes\PaypalPro;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Session;
use File;
use App\Http\Controllers\HomeController;

class ServiceController extends Controller {

    public function servicePage($id = "") {

        if (session("user_id") != "" && session("profile_id") == 2) {

            $bookService_obj = new BookService;
            $data["services_pend"] = $bookService_obj->getServiceBooking();
        }

        $data['select_service_id'] = $id;
        $data["services"] = Service::all()->sortBy("id");
        $data["providers"] = DB::select("SELECT l.* FROM landscapers l,user_details ud WHERE l.user_id = ud.user_id AND l.service_id = '$id'");
        $bookService_obj = new BookService;
        if (session("user_id") != "" && session("profile_id") == 3) {
            $user_id = session("user_id");
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
        }

        return view("Home.search-list", $data);
    }

    public function servicePageLocation(Request $request) {
//        print_r($request->all());exit;
        if (session("user_id") != "" && session("profile_id") == 2) {

            $bookService_obj = new BookService;
            $data["services_pend"] = $bookService_obj->getServiceBooking();
            $data["notification_status"] = $bookService_obj->notificationStatus();
        }
        
        
        $select_service_id = (Input::get("select_service_id") != "") ? Input::get("select_service_id") : 1;
        $data['loc_txt'] = Input::get("loc_txt");
        $service_id = Input::get("service_txt");
        $data['state'] = Input::get("state");
        $data['city'] = Input::get("city");
        $data['country'] = Input::get("country");
        $radius = Input::get("radius");
        if ($radius == "")
            $radius = 15;

        $latitude = "";
        $longitude = "";        
        $landscapers_id = -1;
        $landscapers = [];
        $data["providers"] = [];
        if ($data['loc_txt'] != "") {

            $formattedAddr = str_replace(' ', '+', $data['loc_txt']);

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
            
            
            if ($latitude != "" && $longitude != "") {
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
                        if ($service_id != '') {
                            $data['flag'] = "hide";
                            $data['service_id'] = $service_id;
                            
                            $data["providers"] = DB::select("SELECT l.* FROM landscapers l,user_details ud WHERE l.user_id = ud.user_id AND l.service_id = '$service_id' HAVING l.id IN($landscapers_id) AND (SELECT COUNT(id) FROM payment_accounts WHERE user_id = l.user_id) > 0");
                        } else {
                            $data['flag'] = "hide";
                            $data["providers"] = DB::select("SELECT l.* FROM landscapers l,user_details ud WHERE l.user_id = ud.user_id AND l.service_id = '$select_service_id' HAVING l.id IN($landscapers_id) AND (SELECT COUNT(id) FROM payment_accounts WHERE user_id = l.user_id) > 0");
                        }
                    } $data['flag'] = "hide";
                }
            }
        } else {
            $data["providers"] = DB::select("SELECT l.* FROM landscapers l,user_details ud WHERE l.user_id = ud.user_id AND l.service_id = '$select_service_id' HAVING (SELECT COUNT(id) FROM payment_accounts WHERE user_id = l.user_id) > 0");
        }
        if (empty($data["providers"])) {
            $data["providers"] = [];
        }
        
        
        $data['service_id'] = $service_id;
        $data["services"] = Service::all()->sortBy("id");
        $data['select_service_id'] = $select_service_id;
        $bookService_obj = new BookService;
        if (session("user_id") != "" && session("profile_id") == 3) {
            $user_id = session("user_id");
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
        }
        $data['min_rate'] = $request->min_rate;
        $data['max_rate'] = $request->max_rate;

        $landscaper_model_obj = new Landscaper();

        foreach ($data["providers"] as $key => $one_provider) {
            $rating = $landscaper_model_obj->get_overall_rating($one_provider->id);
            $data_min_max = $this->getMinMaxPriceOfLanscaper($one_provider->id);

            $data["providers"][$key]->min_price = $data_min_max['min'];
            $data["providers"][$key]->max_price = $data_min_max['max'];

            if ($request->min_rate != "" && $request->max_rate != "") {
                if ($rating < $request->min_rate || $rating > $request->max_rate) {
                    unset($data["providers"][$key]);
                }
            }
        }

        $data['price_filter'] = (isset($request->filter_price) && $request->filter_price != "") ? $request->filter_price : 'l';

        if ($data['price_filter'] == "l") {
            usort($data["providers"], function($x, $y) {
                return $x->min_price > $y->min_price ? 1 : -1;
            });
        } else if ($data['price_filter'] == "h") {
            usort($data["providers"], function($x, $y) {
                return $x->min_price < $y->min_price ? 1 : -1;
            });
        }

        return view("Home.search-list", $data);
    }

    public function getMinMaxPriceOfLanscaper($landscaper_id) {
        $data_p = [];
        $data_min_max['min'] = 0;
        $data_min_max['max'] = 0;

        $sql = "select distinct(service_field_id) from service_details where landscaper_id=" . $landscaper_id;
        $data = DB::select($sql);
        foreach ($data as $ar) {
            $sql_p = "select min(service_field_price) as min_price,max(service_field_price) as max_price from service_details where landscaper_id=" . $landscaper_id . " AND service_field_id = " . $ar->service_field_id;
            $data_p[] = DB::select($sql_p);
        }
        foreach ($data_p as $ar_min_max) {
            $data_min_max['min'] += $ar_min_max[0]->min_price;
            $data_min_max['max'] += $ar_min_max[0]->max_price;
        }
        return $data_min_max;
    }

//    public function servicePageLocation(Request $request) {
//        
//        if(session("user_id")!="" && session("profile_id")==2){
//            
//            $bookService_obj = new BookService;
//             $data["services_pend"] = $bookService_obj->getServiceBooking();
//            
//        }       
//        $loc = Input::get("loc_txt");
//        $select_service_id = (Input::get("select_service_id") != "") ? Input::get("select_service_id") : 1 ;
//        //$select_service_id = 4;
//        $data['loc_txt'] = Input::get("loc_txt");         
//  
//        $radius = Input::get("radius");
//        if ($radius == "")
//            $radius = 15;
//
//        $latitude = ""; 
//        $longitude = "";
//        $landscapers_id = -1;
//        $landscapers = [];
//         $data["providers"] = [];
//        if ($data['loc_txt'] != "") {
////            $ch = curl_init();
////            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($data['loc_txt']) . '&sensor=true&key=AIzaSyC1XtsegzOIIBaqGL0QugHFIaL2pvp8f8A';
////            $url = htmlspecialchars($url);           
////            // Disable SSL verification
////            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
////            // Will return the response, if false it print the response
////            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
////            // Set the url
////            curl_setopt($ch, CURLOPT_URL, $url);
////            // Execute
////          $maps_result = json_decode(curl_exec($ch)); 
//
//        $formattedAddr = str_replace(' ','+',$data['loc_txt']);
//        
//        $arrContextOptions=array(
//            "ssl"=>array(
//                "verify_peer"=>false,
//                "verify_peer_name"=>false,
//            ),
//        );  
//
//        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyC1XtsegzOIIBaqGL0QugHFIaL2pvp8f8A&address='.$formattedAddr.'&sensor=false', false, stream_context_create($arrContextOptions)); 
//        $maps_result = json_decode($geocodeFromAddr);
// 
//            
//            if (!empty($maps_result)) {
//                if($maps_result->status != 'OVER_QUERY_LIMIT'){
//                    if (isset($maps_result->results[0]->geometry->location->lat))
//                        $latitude = $maps_result->results[0]->geometry->location->lat;
//                    if (isset($maps_result->results[0]->geometry->location->lng))
//                        $longitude = $maps_result->results[0]->geometry->location->lng;
//                }
//            }
//            if ($latitude != "" && $longitude != "") {
//             $radius_query = "SELECT `id`,
//                ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $latitude ) ) + COS( RADIANS( `latitude` ) )
//                * COS( RADIANS( $latitude )) * COS( RADIANS( `longitude` ) - RADIANS( $longitude )) ) * 6371 AS `distance`
//                FROM `landscapers`
//                WHERE
//                ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $latitude ) ) + COS( RADIANS( `latitude` ) )
//                * COS( RADIANS( $latitude )) * COS( RADIANS( `longitude` ) - RADIANS( $longitude )) ) * 6371 < " . $radius;
//
//            $nearby = DB::select($radius_query); 
//            if (!empty($nearby)) {
//                foreach ($nearby as $landscapers_obj) {
//                    if($landscapers_obj->id != ""){
//                        $landscapers[] = $landscapers_obj->id;
//                    }
//                }
//                if (!empty($landscapers)) {
//                    $landscapers_id = implode(',', $landscapers);
//                    $data["providers"] = DB::select("SELECT l.* FROM landscapers l,user_details ud WHERE l.user_id = ud.user_id AND l.service_id = '$select_service_id' HAVING l.id IN($landscapers_id)");
//                }
//            }
//        }    
//        }else{
//            $data["providers"] = DB::select("SELECT l.* FROM landscapers l,user_details ud WHERE l.user_id = ud.user_id AND l.service_id = '$select_service_id'");
//        }
//         if (empty($data["providers"])) {
//             $data["providers"] = [];
//         }
//
//        $data["services"] = Service::all()->sortBy("id");
//        $data['select_service_id'] = $select_service_id;
////        $data["providers"] = DB::select("SELECT l.* FROM landscapers l,user_details ud WHERE l.user_id = ud.user_id AND l.service_id = '$select_service_id' AND (ud.city LIKE '$loc%' OR ud.state LIKE '$loc%' OR ud.country LIKE '$loc%' OR ud.address LIKE '$loc%')");
////        $data["providers"] = DB::select("SELECT l.* FROM landscapers l,user_details ud WHERE l.user_id = ud.user_id HAVING l.id IN($landscapers_id)");
//        $bookService_obj = new BookService;
//        if(session("user_id")!="" && session("profile_id")==3)
//        {
//            $user_id = session("user_id");
//            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
//        }
//        $data['min_rate'] = $request->min_rate;
//        $data['max_rate'] = $request->max_rate;
//
//        $landscaper_model_obj = new Landscaper();
//
//        foreach ($data["providers"]  as $key => $one_provider) {
//            $rating  = $landscaper_model_obj->get_overall_rating($one_provider->id);
//            if($request->min_rate!="" && $request->max_rate!=""){
//                if($rating<$request->min_rate || $rating>$request->max_rate){
//                    unset($data["providers"][$key]);
//                } 
//            }
//        }
//
//        return view("Home.search-list", $data);
//    }

    public function serviceDetails($id, $latitude = null, $longitude = null) {

        if (session("user_id") != "" && session("profile_id") == 2) {

            $bookService_obj = new BookService;
            $data["services_pend"] = $bookService_obj->getServiceBooking();
            $data["notification_status"] = $bookService_obj->notificationStatus();
        }

        $current_loc = '';
        $weather = [];
        $payment_acc_obj = new PaymentAccounts();
        $user_details_obj = new UserDetail();
        $user_latitude = '';
        $user_longitude = '';
        
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
                    if ($counter == 7) {
                        $current_loc = $addressComponet->long_name . ', ' . $addressComponet->short_name;
                    }
                    $counter++;
                }
            }
            
                        
//            print_r($weathers);
        }
        
        $user_data = $user_details_obj->GetGeneralUser(session("user_id"));
//        print_r($user_data);exit;
        
        if(isset($user_data[0]->address) && $user_data[0]->address != ''){
        $formattedAddr1 = str_replace(' ', '+', $user_data[0]->address);

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
            
//        $data['user_latitude'] = $user_latitude;
//        $data['user_longitude'] = $user_longitude;
        
        $data["weather"] = $weather;
        $data["lat"] = $user_latitude;
        $data["lng"] = $user_longitude;

        $data["landscapper_info"] = DB::select("select * from landscapers where id = '$id'");
        $data["service_time"] = DB::select("select * from service_times where landscaper_id = '$id';");
        $user_id = $data['landscapper_info'][0]->user_id;
        $data["users_landscapers"] = DB::select("SELECT l.id as landscaper_id,s.* FROM `landscapers` l INNER JOIN services s ON l.service_id = s.id WHERE user_id ='$user_id'");
//        $services = DB::select("select group_concat(service_id separator ',') as service_id from landscapers where user_id = '{$user_id}'")[0]->service_id;
//        $data["services"] = DB::select("select * from services where id in ($services)");
        $data['landscaper_id'] = $id;
        $data["landscaper_profile_picture"] = DB::select("select user_details.profile_image from user_details inner join landscapers on landscapers.user_id = user_details.user_id where landscapers.id = '$id'")[0]->profile_image;

        $data['service_rating'] = DB::select("SELECT sr.rating_value,sr.review,sr.log_time,ud.first_name,ud.last_name,ud.profile_image FROM  service_ratings sr,user_details ud WHERE sr.customer_id = ud.user_id AND sr.initiated_by !=" . $user_id . " AND sr.landscaper_id = " . $id);

        session(["landscaper_profile_picture" => $data["landscaper_profile_picture"]]);
        
        $percentage = $payment_acc_obj->getPercentage();
        $data["percentage"] = $percentage;
        $data["current_loc"] = $current_loc;
        $data['services'] = Service::all();
        $bookService_obj = new BookService;
        if (session("user_id") != "" && session("profile_id") == 3) {
            $user_id = session("user_id");
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
        }

        switch ($data["landscapper_info"][0]->service_id) {

            case 1:
                $data["acres"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '1' ORDER BY service_field_price;");
                $data["grasses"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '2' ORDER BY service_field_price;");
                return view("Home.Service.Mowing-Edging", $data);

            case 2:
                $data["acres"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '1' ORDER BY service_field_price;");
                $data["leafs"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '6' ORDER BY service_field_price;");
                return view("Home.Service.Leaf-Removal", $data);

            case 3:
                $data["acres"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '1' ORDER BY service_field_price;");
                return view("Home.Service.Lawn-Treatment", $data);

            case 4:
                $data["acres"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '1' ORDER BY service_field_price;");
                return view("Home.Service.Aeration", $data);

            case 5:
                $data["acres"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '1' ORDER BY service_field_price;");
                $data["zones"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '11' ORDER BY service_field_price;");
                return view("Home.Service.Winterizing", $data);

            case 6:
                $data["water_type"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '7' ORDER BY service_field_price;");
                $data["spa"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '8' ORDER BY service_field_price;");
                $data["pool_type"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '9' ORDER BY service_field_price;");
                $data["pool_state"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '10' ORDER BY service_field_price;");
                return view("Home.Service.Pool-Cleaning", $data);

            case 7:
                $data["car_limit"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '3' ORDER BY service_field_price;");
                $data["driveway_type"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '4';");
                $data["service_type"] = DB::select("select * from service_details where landscaper_id = '$id' and service_field_id = '5';");
                return view("Home.Service.Snow-Clean", $data);

            default:
                return response("Page Not Found", 404);
        }
    }

    public function addToCart() {
        $data["order_data"] = Input::all();
//        print_r($data["order_data"]);exit;
        unset($data["order_data"]["cars"]);
        unset($data["order_data"]["driveway"]);
        unset($data["order_data"]["servicesytype"]);
        
        $weather = [];
        
        if(isset($data["order_data"]["lat"]) && isset($data["order_data"]["lng"])){
            
            $home_con_obj = new HomeController();            
            $weather = $home_con_obj->callYahooWeather($data["order_data"]["lat"],$data["order_data"]["lng"]);
        }
        
        $data["weather"] = $weather;

        $user_id = session('user_id');
        $bookService_obj = new BookService;
        $paymentacc_obj = new PaymentAccounts();
        $data["paymentacc_obj"] = $paymentacc_obj;
        $data["landscapper_info"] = Landscaper::where("id", "=", Input::get("landscaper_id"))->get()[0];
        $data["landscapper_info"]["service_name"] = Service::select("service_name")->where("id", "=", $data["landscapper_info"]["service_id"])->get()[0]["service_name"];
        $data["addresses"] = AddressBook::where("user_id", "=", $user_id)->get();
        $data["discount_info"] = ServicePrice::where(["landscaper_id" => Input::get("landscaper_id"), "service_id" => Input::get("service_id")])->get();
//        print_r($data["discount_info"]);exit;
        $data["countrys"] = Country::all()->sortBy("country_name");
        $data["notification_status"] = $bookService_obj->notificationStatus();
        $data['payment_details'] = DB::select("SELECT * from payment_accounts where user_id=" . session('user_id'));
        $data['count_acc'] = DB::select("SELECT COUNT(id) as count_data FROM payment_accounts where user_id = " . session("user_id") . " and is_primary = 1");
        if (count($data['payment_details']) != 0)
            $data['payment_dtls'] = $data['payment_details'];
        else
            $data['payment_dtls'] = '';
        return view("Home.Booking.Booking-Step-One", $data);
    }

    public function checkOut(Request $request) {
        $data = Input::all();
//        print_r($data);exit;
        $service_id = $data["service_id"];
        unset($data["service_id"]);
        $data["order_no"] = "OD" . time() . rand("111111", "999999");
        $data["customer_id"] = session("user_id");
        $data["status"] = 0;
        $data["service_date"] = date("Y-m-d", strtotime($data["service_date"]));
        $data["service_time"] = date("H:i", strtotime($data["service_time"]));
        date_default_timezone_set(session('current_timezone'));
        $data["booking_time"] = date("Y-m-d H:i:s");
        $discount_info = ServicePrice::where(["id" => Input::get("service_price_id")])->get()[0];
        $data["service_booked_price"] = number_format((float)($data['service_booked_price'] - $discount_info->discount_price), 2, '.', '');
//        $data['service_price'] = $this->GetTax($data["service_price"], 20);
        $data['service_price'] = $data["service_price"];
        $session_data = $data;
        unset($data['property_image']);
        unset($session_data['property_image']);
        session(["order_info" => $session_data]);

        $data['property_image_name'] = [];

        if (Input::hasFile('property_image') && Input::file('property_image')) {
            $property_images = $request->file("property_image");
            foreach ($property_images as $property_image) {
                $fileName = rand(1111, 9999) . "_" . time() . "_" . $property_image->getClientOriginalName();
                $property_image->move("uploads/temp/", $fileName);
                $data['property_image_name'][] = $fileName;
            }
        }
        
        $bookService_obj = new BookService;
        session(["property_image_new" => $data['property_image_name']]);
        $data["landscapper_info"] = Landscaper::where("id", "=", Input::get("landscaper_id"))->get()[0];
        $data["address"] = AddressBook::where("id", "=", $data["address_book_id"])->get()[0];
        $data["service_name"] = Service::select("service_name")->where("id", "=", $service_id)->get()[0]["service_name"];
        $data["notification_status"] = $bookService_obj->notificationStatus();
        return view("Home.Booking.Booking-Step-Two", $data);
    }

    public function checkOutFinal(Request $request) {
        
        $order_info_arr = session("order_info");
        unset($order_info_arr['is_primary']);
        unset($order_info_arr['percentage']);
        unset($order_info_arr['lat']);
        unset($order_info_arr['lng']);
        $request->session()->put('order_info', $order_info_arr);
         
        $BookService_id = BookService::insertGetId(session("order_info"));
        $request->property_image_name = session("property_image_new");

        if ($request->property_image_name != "") {

            $property_images = $request->property_image_name;
            foreach ($property_images as $property_image) {
                File::move("uploads/temp/" . $property_image, "uploads/property/" . $property_image);
                $ar = [
                    "book_service_id" => $BookService_id,
                    "uploaded_by" => session("user_id"),
                    "service_image" => $property_image];
                ServiceImages::insert($ar);
            }
        }
        $request->session()->flash('msg', 'Service Request Sent Successfully. Your job will start after the Landscaper accepts the request.');
        return redirect()->route("user-booking-history");
    }

    public function serviceBooking() {

        if (session("user_id") != "" && session("profile_id") == 2) {
            $user_id = session("user_id");
            $bookService_obj = new BookService;
            $data["services_pend"] = $bookService_obj->getServiceBooking();
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
            $data["notification_status"] = $bookService_obj->notificationStatus();
        }
        $data['services'] = Service::all();
        $data["user_id"] = UserDetail::where("user_id", "=", session("user_id"))->get()[0];
        $data['payment_details'] = DB::select("SELECT * from payment_accounts where user_id=" . session('user_id'));
        if (count($data['payment_details']) != 0)
            $data['payment_dtls'] = $data['payment_details'];
        else
            $data['payment_dtls'] = '';
        $data['menu'] = "userBookHistory";
        $bookService_obj = new BookService;


        return view('Home.Booking.user-booking-history', $data);
    }

    public function updateNotification() {
        $sql = "UPDATE book_services SET notification_status_user = 0 WHERE customer_id = " . session('user_id') . " AND notification_status_user = 1";
        $res = DB::update($sql);
        return $res;
    }

    public function updateNotificationLandscaper() {
        $lands_ids = [];
        $res = '';
        $l_ids = DB::select("SELECT * FROM landscapers WHERE user_id =" . session('user_id'));
        if (!empty($l_ids)) {
            foreach ($l_ids as $lids) {
                $lands_ids[] = $lids->id;
            }
            $lands_ids = implode(',', $lands_ids);
            $sql = "UPDATE book_services SET notification_status_landscaper = 0 WHERE landscaper_id IN ($lands_ids) AND notification_status_landscaper = 1";
            $res = DB::update($sql);
        }
        return $res;
    }

    public function userProfile() {

        if (session("user_id") != "" && session("profile_id") == 2) {
            $user_id = session("user_id");
            $bookService_obj = new BookService;
            $data["services_pend"] = $bookService_obj->getServiceBooking();
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
            $data["notification_status"] = $bookService_obj->notificationStatus();
        }
        $data['menu'] = "userProfile";
        $data["user_info"] = UserDetail::where("user_id", "=", session("user_id"))->get()[0];
        $data["addresses"] = AddressBook::where("user_id", "=", session("user_id"))->get();
        $data['services'] = Service::all();
        $bookService_obj = new BookService;

        $data["countrys"] = Country::all()->sortBy("country_name");
        return view('Home.user-profile', $data);
    }

//    public function likeFavorite(Request $request, $landscaper_id) {
//
//        $loc = Input::get("loc_txt");
//        $select_service_id = (Input::get("select_service_id") != "") ? Input::get("select_service_id") : 1;
//        ;
//
//        $data['loc_txt'] = Input::get("loc_txt");
//        $data["services"] = Service::all()->sortBy("id");
//        $data['select_service_id'] = $select_service_id;
//        $data["providers"] = DB::select("SELECT l.* FROM landscapers l,user_details ud WHERE l.user_id = ud.user_id AND l.service_id = '$select_service_id' AND (ud.city LIKE '$loc%' OR ud.state LIKE '$loc%' OR ud.country LIKE '$loc%' OR ud.address LIKE '$loc%')");
//
//        $data['user_id'] = session("user_id");
//        $data['landscaper_id'] = $landscaper_id;
//        $data['res'] = DB::select("SELECT visible from favorite_landscapers where user_id=" . session('user_id') . ' AND landscaper_id = ' . $data['landscaper_id']);
//        if (empty($data['res'])) {
//            $data['visible'] = 1;
//            DB::table('favorite_landscapers')->insert(
//                    array(
//                        'user_id' => $data['user_id'],
//                        'landscaper_id' => $data['landscaper_id'],
//                        'visible' => $data['visible']
//                    )
//            );
//        } else {
//            $qry = DB::select("update favorite_landscapers set visible=(case visible when 1 then 0 else 1 end) where user_id=" . session('user_id') . ' AND landscaper_id = ' . $data['landscaper_id']);
//        }
//        DB::select("SELECT * from favorite_landscapers where user_id=" . session('user_id'));
//        return view('Home.search-list', $data);
//    }

    public function addFavorite(Request $request) {

        $data['user_id'] = session("user_id");
        $data['landscaper_id'] = $request->landscaper_id;
        $res = DB::select("SELECT visible from favorite_landscapers where user_id=" . session('user_id') . ' AND landscaper_id = ' . $data['landscaper_id']);

        if (empty($res)) {
            $data['visible'] = 1;
            DB::table('favorite_landscapers')->insert($data);
        } else {
            DB::update("update favorite_landscapers set visible=(case visible when 1 then 0 else 1 end) where user_id=" . session('user_id') . ' AND landscaper_id = ' . $data['landscaper_id']);
            $data['visible'] = ($res[0]->visible == 0) ? 1 : 0;
        }

        return $data['visible'];
    }

    public function favoriteHistory() {

        if (session("user_id") != "" && session("profile_id") == 2) {
            $user_id = session("user_id");
            $bookService_obj = new BookService;
            $data["services_pend"] = $bookService_obj->getServiceBooking();
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
            $data["notification_status"] = $bookService_obj->notificationStatus();
        }
        $data['menu'] = "userFavorite";
        $user_id = session("user_id");
        $data['services'] = Service::all();
        $sql = 'SELECT l.* FROM favorite_landscapers fl,landscapers l WHERE fl.landscaper_id=l.id AND fl.user_id=' . $user_id . ' AND fl.visible=1';
        $data["providers"] = DB::select($sql);
        $bookService_obj = new BookService;

        return view('Home.favorite-landscapers', $data);
    }

    public function bookingPaymentDetails($order_no = null) {

        $data = array();
        if (session("user_id") != "" && session("profile_id") == 2) {
            $user_id = session("user_id");
            $bookService_obj = new BookService;
            $data["services_pend"] = $bookService_obj->getServiceBooking();
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
        }
        $data["user_info"] = UserDetail::where("user_id", "=", session("user_id"))->get()[0];
        $data['payment_details'] = DB::select("SELECT * from payment_accounts where user_id=" . session('user_id'));
        if (count($data['payment_details']) != 0)
            $data['payment_dtls'] = $data['payment_details'];
        else
            $data['payment_dtls'] = '';

        $data['menu'] = "userBookingPaymentDetails";
        $bookService_obj = new BookService;
        $payment_acc_obj = new PaymentAccounts();
        $data["payment_acc_obj"] = $payment_acc_obj;
        $data["booking_details"] = $bookService_obj->getBookingDetails($order_no);
        $data["rating_details"] = $bookService_obj->getRatingDetails($order_no);
        $data["user_service_images"] = $bookService_obj->getUserServiceImages($order_no);
        $data["landscaper_service_images"] = $bookService_obj->getLandscaperServiceImages($order_no);
        $data["order_no"] = $order_no;

        $landscaper_id = null;
        $landscaper_details = DB::select("SELECT user_id,service_price,service_booked_price FROM book_services bs,landscapers l where bs.landscaper_id=l.id AND order_no='" . $order_no . "'");
        $landscaper_id = $landscaper_details[0]->user_id;
        $data["landscaper_payment_details"] = DB::select("SELECT * from payment_accounts where user_id=" . $landscaper_id);
        $data["admin_payment_details"] = DB::select("SELECT * from payment_accounts where user_id=(SELECT id FROM users WHERE profile_id = 1)");
        $data["profile_image"] = $bookService_obj->selectLandscaperImage($order_no);
        return view('Home.Booking.user-booking-history-payment', $data);
    }

    public function paypalPayment($order_no = null) {
        $data = array();
        $data["user_info"] = UserDetail::where("user_id", "=", session("user_id"))->get()[0];
        $data['payment_details'] = DB::select("SELECT * from payment_accounts where user_id=" . session('user_id'));
        if (count($data['payment_details']) != 0)
            $data['payment_dtls'] = $data['payment_details'];
        else
            $data['payment_dtls'] = '';

        $data['menu'] = "userBookingPaymentDetails";
        //$bookService_obj = new BookService;
        //$data["booking_details"] = $bookService_obj->getBookingDetails($order_no);
        //$data["rating_details"] = $bookService_obj->getRatingDetails($order_no);
        $data["order_no"] = $order_no;

        //paypal adaptive payment..... 
        $landscaper_id = null;
        $service_price = null;
        $admin_amount = null;
        $landscaper_amount = null;
        $transaction_id = null;
        $landscaper_details = DB::select("SELECT user_id,service_price FROM book_services bs,landscapers l where bs.landscaper_id=l.id AND order_no='" . $order_no . "'");
        $landscaper_id = $landscaper_details[0]->user_id;
        $service_price = $landscaper_details[0]->service_price;
        $landscaper_payment_details = DB::select("SELECT * from payment_accounts where user_id=" . $landscaper_id);
        $admin_payment_details = DB::select("SELECT * from payment_accounts where user_id=(SELECT id FROM users WHERE profile_id = 1)");
        $is_completed_by_user = DB::select("SELECT is_completed from book_services where order_no='" . $order_no . "'");
        if ($is_completed_by_user[0]->is_completed == 0) {
            $admin_percentages = DB::select("SELECT * from payment_percentages");
            $admin_amount = $service_price;
        } else {
            $admin_percentages = DB::select("SELECT * from payment_percentages");
            $admin_amount = ($service_price * $admin_percentages[0]->percentage) / 100;
        }
        $landscaper_amount = $service_price - $admin_amount;

        $config = array(
            "environment" => "sandbox", # or live
            "userid" => "info-facilitator_api1.commercefactory.org",
            "password" => "1399139964",
            "signature" => "AFcWxV21C7fd0v3bYYYRCpSSRl31ABA-4mmfZiu.G30Dl3DKyBo9-GF8",
                // "appid" => "", # You can set this when you go live
        );
        $paypal = new PayPal($config);

        $result = $paypal->call(
                array(
            'actionType' => 'PAY',
            'currencyCode' => 'USD',
            'feesPayer' => 'EACHRECEIVER',
            'memo' => 'Order number #' . $order_no,
            'cancelUrl' => route("user-booking-history-payment", array('order_no' => $order_no)),
            'returnUrl' => route("paypalSuccess", array('order_no' => base64_encode($order_no))),
            'receiverList' => array(
                'receiver' => array(
//                    array(
//                        'amount' => $service_price,
//                        'email' => $landscaper_payment_details[0]->account_email,
//                        'primary' => 'true',
//                    ),
                    array(
                        'amount' => $admin_amount,
                        'email' => $admin_payment_details[0]->account_email,
                    ),
                ),
            ),
                ), 'Pay'
        );

        if ($result['responseEnvelope']['ack'] == 'Success') {
            $transaction_id = $result['payKey'];
            $return_url = $paypal->redirect($result);
            date_default_timezone_set(session('current_timezone'));
            $required_time = date("Y-m-d H:i:s");
            if ($return_url)
                DB::update("UPDATE book_services SET transaction_id='" . $result['payKey'] . "',status=2,is_completed=1,landscaper_payment=" . $landscaper_amount . ",notification_status_user=1,notification_status_landscaper=1,admin_payment=" . $admin_amount . ",mode_of_payment='PayPal',payment_date='" . $required_time . "' WHERE order_no='" . $order_no . "'");
            return redirect($return_url);
        } else {
            echo 'Handle the payment creation failure';
        }
        //end.....
    }

    public function payFromEscrow() {
        try {
            if ((isset($_REQUEST['order_no']) && $_REQUEST['order_no'] != '') && (isset($_REQUEST['is_completed']) && $_REQUEST['is_completed'] != '')) {
                $order_no = $_REQUEST['order_no'];
                

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
//                $landscaper_amount = $service_price - $admin_amount;
                $landscaper_amount = $service_booked_price;
                
                $config = array(
				// Signature Credential
				"acct1.UserName" => $admin_payment_details[0]->account_details,
				"acct1.Password" => $admin_payment_details[0]->account_password,
				"acct1.Signature" => $admin_payment_details[0]->account_signature,
                                "mode" => "sandbox",
                                'log.LogEnabled' => false,
                                'log.FileName' => '../PayPal.log',
                                'log.LogLevel' => 'FINE'
                    );
//                                $config = array(
//				// Signature Credential
//				"acct1.UserName" => "rupsena_bus_api1.gmail.com",
//				"acct1.Password" => "F23LSZ7VBVHZJRH3",
//				"acct1.Signature" => "AFcWxV21C7fd0v3bYYYRCpSSRl31AuK0jN6a8HcnkczrWHcdS7eLFKR8",
//                                "mode" => "sandbox",
//                                'log.LogEnabled' => false,
//                                'log.FileName' => '../PayPal.log',
//                                'log.LogLevel' => 'FINE'
//                    );
                
                $PayPalMass = new PayPalMass($config);
                $response = $PayPalMass->call(
                        array(
                            array(                                        
                                    'mail'=> $landscaper_payment_details[0]->account_email,
                                    'currencyCode'=>'USD',
                                    'amount' => $landscaper_amount
                                )
                            ),'EmailAddress'
                        );
                
                if($response == 'Success'){
                    DB::update("UPDATE book_services SET is_completed=" . $_REQUEST['is_completed'] . " WHERE order_no='" . $order_no . "'");
                    DB::update("UPDATE book_services SET landscaper_payment='" . $landscaper_amount . "',admin_payment='" . $admin_amount . "' WHERE order_no='" . $order_no . "'");
                    echo json_encode(array('error' => 0));
                }else{
                    echo json_encode(array('error' => 1));
                }
            }
        } catch (\Exception $e) {
            echo json_encode(array('error' => 1));
        }
    }

    public function paypalSuccess() {
        $order_no = null;
        if (isset($_REQUEST['order_no'])) {
            $order_no = base64_decode($_REQUEST['order_no']);
            DB::update("UPDATE book_services SET status=2 WHERE order_no='" . $order_no . "'");
            return redirect(route("user-booking-history-payment", array('order_no' => $order_no)));
        }
    }

    public function cardPayment(Request $request) {
        $landscaper_id = null;
        $service_price = null;
        $admin_amount = null;
        $landscaper_amount = null;
        $transaction_id = null;
        $landscaper_details = DB::select("SELECT user_id,service_price FROM book_services bs,landscapers l where bs.landscaper_id=l.id AND order_no='" . $request->order_no . "'");
        $landscaper_id = $landscaper_details[0]->user_id;
        $service_price = $landscaper_details[0]->service_price;
        $landscaper_payment_details = DB::select("SELECT * from payment_accounts where user_id=" . $landscaper_id);
        $admin_payment_details = DB::select("SELECT * from payment_accounts where user_id=(SELECT id FROM users WHERE profile_id = 1)");
        $admin_percentages = DB::select("SELECT * from payment_percentages");
//        $admin_amount = ($service_price * $admin_percentages[0]->percentage) / 100;
        $admin_amount = $service_price;
        $landscaper_amount = $service_price - $admin_amount;

        $name_arr = explode(' ', $request->customer_name);
        $last_name = '';
        $first_name = $name_arr[0];
        if (isset($name_arr[1]))
            $last_name = $name_arr[1];

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
            'itemName' => $request->order_no,
            'itemNumber' => $request->order_no,
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
            'itemName' => $request->order_no,
            'itemNumber' => $request->order_no,
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

        $this->paypal_pay($config1, $paypalParam1, 'landscaper');
        $this->paypal_pay($config2, $paypalParam2, 'admin');

        return redirect(route("user-booking-history-payment", array('order_no' => $request->order_no)));
    }
    
    public function autoCardPayment($order_no) {
//        echo $order_no;exit;
        $payment_acc_obj = new PaymentAccounts();
        $landscaper_id = null;
        $service_price = null;
        $admin_amount = null;
        $landscaper_amount = null;
        $transaction_id = null;
        $landscaper_details = DB::select("SELECT user_id,service_price,customer_id FROM book_services bs,landscapers l where bs.landscaper_id=l.id AND order_no='" . $order_no . "'");
        $landscaper_id = $landscaper_details[0]->user_id;
        $service_price = $landscaper_details[0]->service_price;
        $landscaper_payment_details = DB::select("SELECT * from payment_accounts where user_id=" . $landscaper_id);
        $admin_payment_details = DB::select("SELECT * from payment_accounts where user_id=(SELECT id FROM users WHERE profile_id = 1)");
        $admin_percentages = DB::select("SELECT * from payment_percentages");
        $admin_amount = $service_price;
        $landscaper_amount = $service_price - $admin_amount;
        
        $saved_card_details = DB::select("SELECT * FROM payment_accounts where user_id = ".$landscaper_details[0]->customer_id." and is_primary = 1");
//        print_r($saved_card_details);exit;
        $name_arr = explode(' ', $saved_card_details[0]->name);
        $last_name = '';
        $first_name = $name_arr[0];
        if (isset($name_arr[1]))
            $last_name = $name_arr[1];
        
        $config_arr = array(
            'apiUsername' => $admin_payment_details[0]->account_details,
            'apiPassword' => $admin_payment_details[0]->account_password,
            'apiSignature' => $admin_payment_details[0]->account_signature
        );
        
        $paypalParam = array(
            'paymentAction' => 'Sale',
            'itemName' => $order_no,
            'itemNumber' => $order_no,
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
        
        $paypal = new PaypalPro($config_arr);

//Payment details
        $response = $paypal->paypalCall($paypalParam);
        if (isset($response['ACK']) && $response['ACK'] == 'Success') {
            date_default_timezone_set(session('current_timezone'));
            $required_time = date("Y-m-d H:i:s");
            
                DB::update("UPDATE book_services SET transaction_id='" . $response['TRANSACTIONID'] . "',status=2,is_completed=1,notification_status_user=1,notification_status_landscaper=1,card_no=" . $paypalParam['creditCardNumber'] . ",landscaper_payment=" . $landscaper_amount . ",mode_of_payment='Debit Card',payment_date='" . $required_time . "' WHERE order_no='" . $paypalParam['itemNumber'] . "'");
            
                DB::update("UPDATE book_services SET transaction_id='" . $response['TRANSACTIONID'] . "',status=2,is_completed=1,admin_payment=" . $admin_amount . ",card_no=" . $paypalParam['creditCardNumber'] . ",payment_date='" . $required_time . "' WHERE order_no='" . $paypalParam['itemNumber'] . "'");
        }
//        return redirect(route("user-booking-history-payment", array('order_no' => $request->order_no)));
    }

    public function paypal_pay($config_arr, $paypalParam, $user_type) {
        $paypal = new PaypalPro($config_arr);

//Payment details
        $response = $paypal->paypalCall($paypalParam);
        if (isset($response['ACK']) && $response['ACK'] == 'Success') {
            date_default_timezone_set(session('current_timezone'));
            $required_time = date("Y-m-d H:i:s");
            if ($user_type == 'landscaper')
                DB::update("UPDATE book_services SET transaction_id='" . $response['TRANSACTIONID'] . "',status=2,is_completed=1,notification_status_user=1,notification_status_landscaper=1,card_no=" . $paypalParam['creditCardNumber'] . ",landscaper_payment=" . $paypalParam['amount'] . ",mode_of_payment='Debit Card',payment_date='" . $required_time . "' WHERE order_no='" . $paypalParam['itemNumber'] . "'");
            if ($user_type == 'admin')
                DB::update("UPDATE book_services SET transaction_id='" . $response['TRANSACTIONID'] . "',status=2,is_completed=1,admin_payment=" . $paypalParam['amount'] . ",card_no=" . $paypalParam['creditCardNumber'] . ",payment_date='" . $required_time . "' WHERE order_no='" . $paypalParam['itemNumber'] . "'");
        }
    }

    public function userRating($landscaper_id = null, $order_no = null) {
        $data = array();
        $data['landscaper_id'] = $landscaper_id;
        $data['order_no'] = $order_no;
        return view('Home.Booking.user-rating', $data);
    }

    public function editUserRating(Request $request) {
        
        $rating_exists = DB::select("select count(*) as rating_count from service_ratings where initiated_by = " . session('user_id') . " AND landscaper_id = " . $request->landscaper_id . ";");
        $rating_exists = $rating_exists[0]->rating_count;
        $order_arr = DB::select("select * from book_services where order_no = '" . $request->order_no . "'");
        date_default_timezone_set(session('current_timezone'));
        $required_time = date("Y-m-d H:i:s");
        if ($rating_exists == 0) {
            DB::insert("INSERT INTO service_ratings (landscaper_id,customer_id,order_id,initiated_by,rating_value,review,log_time) VALUES(" . $request->landscaper_id . "," . session('user_id') . ",".$order_arr[0]->id."," . session('user_id') . "," . $request->rating . ",'" . $request->review . "','" . $required_time . "')");
        } else {
            DB::insert("UPDATE service_ratings SET rating_value=" . $request->rating . ",review='" . $request->review . "',log_time='" . $required_time . "' WHERE initiated_by = " . session('user_id') . " AND landscaper_id = " . $request->landscaper_id . "");
        }
        session()->flash("msg", "Rating/Review Successfully Added");
        $ar['msg'] = "ar";
        return redirect()->route("user-booking-history-payment", array('order_no' => $request->order_no));
    }

    public function editLandscaperRating(Request $request) {

        $rating_exists = DB::select("select count(*) as rating_count from service_ratings where initiated_by = " . session('user_id') . " AND landscaper_id = " . $request->landscaper_id . " AND customer_id = " . $request->user_id . ";");
        $rating_exists = $rating_exists[0]->rating_count;        
        if ($rating_exists == 0)
            DB::insert("INSERT INTO service_ratings VALUES(NULL," . $request->landscaper_id . "," . $request->user_id . ",".$request->book_service_id."," . session('user_id') . "," . $request->rating . ",'" . $request->review . "','" . date("Y-m-d h:i:s") . "')");
        else
            DB::insert("UPDATE service_ratings SET rating_value=" . $request->rating . ",review='" . $request->review . "',log_time='" . date("Y-m-d h:i:s") . "' WHERE initiated_by = " . session('user_id') . " AND landscaper_id = " . $request->landscaper_id . " AND customer_id = " . $request->user_id . "");
        session()->flash("msg", "Rating/Review Successfully Added");
        return redirect()->route("view-service-details", array('book_service_id' => $request->book_service_id));
    }

    public function matchPassword(Request $request) {
        $current_password = $request->old_password;
        $user_id = session("user_id");

        $Landscaper_obj = new Landscaper;
        $response = $Landscaper_obj->match_password($current_password, $user_id);
        echo $response;
        exit;
    }

    public function updatePassword(Request $request) {
        $user_id = session("user_id");
        $update = 'UPDATE users SET password="' . md5($request->new_password) . '" WHERE id=' . $user_id . '';
        $update = DB::select($update);

        session()->flash("msg", "Password Updated Successfully");
        return redirect()->route("user-my-profile");
    }

    public function DistanceCheck(Request $request) {
        $lanscaper_id = $request->landscaper_id;
        $data = AddressBook::where("id", "=", $request->address_book_id)->get()[0];
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

    public function GetTax($price, $tax) {
        $tax = ($price * $tax) / 100;
        $total_price = $price + $tax;
        return $total_price;
    }

}
