<?php

namespace App\Http\Controllers;

use App\AddressBook;
use App\BookService;
use App\Landscaper;
use App\Service;
use App\UserDetail;
use App\PaymentAccounts;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use File;
use Mail;
use App\User;

class LandscaperController extends Controller {

    public function viewServiceDetails($book_service_id = "") {

        if (session("user_id") != "" && session("profile_id") == 2) {

            $bookService_obj = new BookService;
            $data["services_pend"] = $bookService_obj->getServiceBooking();
        }
        $bookService_obj = new BookService;
        $paymentacc_obj = new PaymentAccounts();
        $data["paymentacc_obj"] = $paymentacc_obj;
        
        $data['service'] = $bookService_obj->getSeviceDetails($book_service_id);
        $data['profile_image'] = $bookService_obj->getLandscaperImage();
        $data['book_service_id'] = $book_service_id;
        $data['menu'] = "viewServiceDetails";
        if (session("user_id") != "" && session("profile_id") == 3) {
            $user_id = session("user_id");
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
        }
        return view('Home.booking-history-details', $data);
    }

    public function updateService(Request $request, $book_service_id = "", $status = "") {

        $bookService_obj = new BookService;
        $rows = $bookService_obj->updateSeviceDetails($book_service_id, $status);

        $info = DB::select("SELECT bs.id as order_id, bs.service_time ,l.name as landscaper_name,ab.address ,ab.city,ab.state,bs.service_date ,bs.order_no,bs.status,bs.customer_id, "
                        . " ud.profile_image, ud.first_name , ud.last_name,ud.email, ud.phone_number,ud.address as landscaper_address,s.service_name,s.id as service_id "
                        . " FROM book_services bs,landscapers l,services s,user_details ud,address_books ab "
                        . " WHERE bs.landscaper_id = l.id AND l.service_id = s.id AND ud.user_id = l.user_id AND bs.address_book_id = ab.id "
                        . " AND bs.id = " . $book_service_id);

        if ($rows > 0) {
            if ($status == 1) {
                $request->session()->flash('msg', 'Job has been accepted');

                $count_acc = DB::select("SELECT COUNT(id) as count_data FROM payment_accounts where user_id = " . $info[0]->customer_id . " and is_primary = 1");
                if ($count_acc[0]->count_data > 0) {
                    $service_con_obj = new ServiceController();
                    $service_con_obj->autoCardPayment($info[0]->order_no);
                }
                //print_r($vv);
//                exit;
            } else
                $request->session()->flash('msg', 'Job has been decline');
        }

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
                // $message ->to('demo.mlindia@gmail.com');  //For Local
                $message->to($email['customer_email']);    //For Server
            });
        }

        return redirect()->route("landscaper-dashboard");
    }

    public function bookingHistory(Request $request) {

        $data['menu'] = "bookHistory";
        $data['services'] = Service::all();
        $bookService_obj = new BookService;
        $paymentacc_obj = new PaymentAccounts();
        $data["paymentacc_obj"] = $paymentacc_obj;
//        $data["services_pend"] = $bookService_obj->getServicePending($request);
        $data["services_pend"] = $bookService_obj->getbookingHistory($request);
        if (session("user_id") != "" && session("profile_id") == 3) {
            $user_id = session("user_id");
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
            $data["notification_status"] = $bookService_obj->notificationStatusLandscaper();
        }
//        echo "<pre>"; print_r ($data);echo "</pre>";die;
        return view('Home.booking-history', $data);
    }

    public function myProfile() {

        if (session("user_id") != "" && session("profile_id") == 2) {

            $bookService_obj = new BookService;
            $data["services_pend"] = $bookService_obj->getServiceBooking();
        }
        $Landscaper_obj = new Landscaper;
        $data["user_details"] = UserDetail::where("user_id", "=", session("user_id"))->first();
        $data["services"] = Service::all();
        $servicesall = Landscaper::where("user_id", "=", session("user_id"))->get();
        $data['user_info'] = User::find(session("user_id"));
        if (!$servicesall->isEmpty()) {
            $data["lands_service"] = array();
            $data["service_distance"] = [];
            foreach ($servicesall as $value) {
                $data["lands_service"][] = $value->service_id;
                $data["service_distance"][] = $value->distance;
            }
            $data["landscaper_name"] = $servicesall[0]->name;
            $data["feature_image"] = $servicesall[0]->profile_image;
        }
        $data["service_hours"] = $Landscaper_obj->getServiceHours();
        $data['menu'] = "landscaperProfile";
        $bookService_obj = new BookService;
        if (session("user_id") != "" && session("profile_id") == 3) {
            $user_id = session("user_id");
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
            $data["notification_status"] = $bookService_obj->notificationStatusLandscaper();
        }
//   echo "<pre>";     print_r($data);die;
        return view("Home.Landscaper.Landscaper-Profile", $data);
    }

    public function loadService($id) {
        $Landscaper_obj = new Landscaper;
        $response = $Landscaper_obj->getLoadService($id);
        return $response;
    }

    public function editLandscaper(Request $request) {
        $profile = UserDetail::where('user_id', session("user_id"))->first();
        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->phone_number = $request->tel;
        $profile->address = $request->address;
        

        $user = User::find(session("user_id"));
        $user->ssn_no = $request->ssn_no;


        $longitude = '';
        $latitude = '';
        if ($request->address != "") {
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

            $formattedAddr = str_replace(' ', '+', $request->address);
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

        $userid = session("user_id");
        $provider_name = $request->provider_name;
        $update = 'UPDATE landscapers SET location="' . $request->address . '",latitude="' . $latitude . '",longitude="' . $longitude . '",name="' . $provider_name . '" WHERE user_id=' . $userid . '';
        $update = DB::update($update);

        session(["user_name" => $profile->first_name . " " . $profile->last_name]);
        $profile->save();
        $user->save();
        session()->flash("msg", "Landscaper Profile Updated Successfully");
        return redirect()->route("landscaper-profile");
    }

    public function getSeviceHours(Request $request) {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thrusday', 'Friday', 'Saturday', 'Sunday'];
        $final_service_times = array();
        $user_id = session("user_id");
        $userdata = Landscaper::where("user_id", "=", $user_id)->get();

        foreach ($days as $i => $day) {
            $service_times = DB::table('service_times')->where(['landscaper_id' => $userdata[0]->id, 'service_id' => $userdata[0]->service_id, 'service_day' => $day])->get();
            $id = (isset($service_times[0]->id) && $service_times[0]->id != "") ? $service_times[0]->id : '';
            $start_time = (isset($service_times[0]->start_time) && $service_times[0]->start_time != "") ? $service_times[0]->start_time : '';
            $end_time = (isset($service_times[0]->end_time) && $service_times[0]->end_time != "") ? $service_times[0]->end_time : '';

            $final_service_times[$i] = array(
                'id' => $id,
                'landscaper_id' => $userdata[0]->id,
                'service_id' => $userdata[0]->service_id,
                'service_day' => $day,
                'start_time' => $start_time,
                'end_time' => $end_time
            );
        }

        $data['service_time'] = $final_service_times;
        $data['landscaper_id'] = $userdata[0]->id;
        $data['service_id'] = $userdata[0]->service_id;

        return view("Home.Landscaper.service-hours", $data);
    }

    public function editServiceHours(Request $request) {
        $user_id = session("user_id");
        $userdata = Landscaper::where("user_id", "=", $user_id)->get();

        foreach ($userdata as $rowdata) {
            $landscaper_id = $rowdata->id;
            $service_id = $rowdata->service_id;

            DB::table('service_times')->where(['landscaper_id' => $landscaper_id, 'service_id' => $service_id])->delete();

            $days = $request->days;
            $start = $request->start;
            $end = $request->end;

            foreach ($days as $key => $day) {
                if ($start[$key] != "" && $end[$key] != "") {
                    $data[] = array(
                        'landscaper_id' => $landscaper_id,
                        'service_id' => $service_id,
                        'service_day' => $day,
                        'start_time' => $start[$key],
                        'end_time' => $end[$key],
                    );
                }
            }
        }
        DB::table('service_times')->insert($data);

        session()->flash("msg", "Service Hours Updated Successfully");
        return redirect()->route("landscaper-profile");
    }

    public function updateProfImg(Request $request) {
        //Profile Image
        $profile = UserDetail::where("user_id", "=", session("user_id"));
        $old_image = $profile->get()[0]->profile_image;
        $profile = $profile->first();

        if (Input::hasFile('profile_image') && Input::file('profile_image')->isValid()) {
            $avatar = $request->file("profile_image");
            $fileName = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
            $avatar->move("public/uploads/profile_picture/", $fileName);
            $profile->profile_image = $fileName;
            $profile->save();
            if ($old_image != "" && File::exists("uploads/profile_picture/" . $old_image)) {
                File::delete("uploads/profile_picture/" . $old_image);
            }
            session(["profile_image" => url("uploads/profile_picture/" . $fileName), "prof_img" => $fileName]);
        }

        session()->flash("msg", "Profile Image Updated Successfully");
        return redirect()->route("landscaper-profile");
    }

    public function updateFeatureImg(Request $request) {
        $user_id = session("user_id");
        $userdata = Landscaper::where("user_id", "=", $user_id)->get();

        if (Input::hasFile('landscaper_image') && Input::file('landscaper_image')->isValid()) {
            $avatar = $request->file("landscaper_image");
            $fileName = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
            $avatar->move("uploads/services/", $fileName);
        }

        foreach ($userdata as $rowdata) {
            $landscaper_id = $rowdata->id;
            $old_image = $rowdata->profile_image;

            $landscaper = Landscaper::where("id", "=", $landscaper_id)->first();
            $landscaper->profile_image = $fileName;
            $landscaper->save();
            if ($old_image != "" && File::exists("uploads/services/" . $old_image)) {
                File::delete("uploads/services/" . $old_image);
            }
        }

        session()->flash("msg", "Profile Image Updated Successfully");
        return redirect()->route("landscaper-profile");
    }


    public function updateDriversLisence(Request $request) {
        $user_id = session("user_id");
        $user_info = User::find($user_id);

        $drivers_license = "";
        if (Input::hasFile('drivers_license') && Input::file('drivers_license')->isValid()) {
            $avatar = $request->file("drivers_license");
            $drivers_license = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
            $avatar->move("uploads/drivers_license/", $drivers_license);
        }

        if ($user_info) {
            $old_image = $user_info->drivers_license;
            
            $user_info->drivers_license = $drivers_license;
            $user_info->save();
            if ($old_image != "" && File::exists("uploads/drivers_license/" . $old_image)) {
                File::delete("uploads/drivers_license/" . $old_image);
            }
        }

        session()->flash("msg", "Drivers License Updated Successfully");
        return redirect()->route("landscaper-profile");
    }
    public function updateProfile(Request $request) {

        //Image Upload
        //Profile Image
        $profile = UserDetail::where("user_id", "=", session("user_id"));
        $old_image = $profile->get()[0]->profile_image;
        $profile = $profile->first();

        if (Input::hasFile('profile_image') && Input::file('profile_image')->isValid()) {
            $avatar = $request->file("profile_image");
            $fileName = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
            $avatar->move("uploads/profile_picture/", $fileName);
            $profile->profile_image = $fileName;
            $profile->save();
            if ($old_image != "" && File::exists("uploads/profile_picture/" . $old_image)) {
                File::delete("uploads/profile_picture/" . $old_image);
            }
            session(["profile_image" => url("uploads/profile_picture/" . $fileName), "prof_img" => $fileName]);
        }

        if ($request->landscaper_id != 0) {
            DB::table('service_details')->where([['landscaper_id', '=', $request->landscaper_id], ["service_id", "=", $request->service_id]])->delete();
            $service_id = $request->service_id;
            $landscaper_id = $request->landscaper_id;
            $landscaper_obj = new Landscaper;
            switch ($service_id) {
                case 1:
                    $landscaper_obj->addLawnMawning($request, $landscaper_id, $service_id);
                    break;
                case 2:
                    $landscaper_obj->addLeafRemoval($request, $landscaper_id, $service_id);
                    break;
                case 3:
                    $landscaper_obj->addLawnTreatment($request, $landscaper_id, $service_id);
                    break;
                case 4:
                    $landscaper_obj->addAeration($request, $landscaper_id, $service_id);
                    break;
                case 5:
                    $landscaper_obj->addSprinklerWinterizing($request, $landscaper_id, $service_id);
                    break;
                case 6:
                    $landscaper_obj->addPoolCleaning($request, $landscaper_id, $service_id);
                    break;
                case 7:
                    $landscaper_obj->addSnowRemoval($request, $landscaper_id, $service_id);
                    break;
                default:
                    return "Method Not Found";
            }

            //Landscaper Image

            $landscaper = Landscaper::where([["id", "=", $landscaper_id], ["user_id", "=", session("user_id")], ["service_id", "=", $service_id]]);
            $old_image = $landscaper->get()[0]->profile_image;
            $landscaper = $landscaper->first();

            if (Input::hasFile('landscaper_image') && Input::file('landscaper_image')->isValid()) {
                $avatar = $request->file("landscaper_image");
                $fileName = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
                $avatar->move("uploads/services/", $fileName);
                $landscaper->profile_image = $fileName;
                $landscaper->save();
                if ($old_image != "" && File::exists("uploads/profile_picture/" . $old_image)) {
                    File::delete("uploads/services/" . $old_image);
                }
            }
        }
        return redirect()->route("landscaper-profile");
    }

    public function updateServiceDetails(Request $request) {
        $serviceArr = $request->serviceid;
        $landscaperArr = $request->landscaper_id;
        $update = 'UPDATE landscapers SET distance=' . $request->provider_distance . ' WHERE user_id=' . session('user_id');
        $update = DB::update($update);
        if (count($serviceArr) != 0) {
            foreach ($serviceArr as $key => $service_id) {
                $landscaper_id = $landscaperArr[$key];

                if ($landscaper_id != 0) {
                    DB::table('service_details')->where([['landscaper_id', '=', $landscaper_id], ["service_id", "=", $service_id]])->delete();
                    $landscaper_obj = new Landscaper;
                    switch ($service_id) {
                        case 1:
                            $landscaper_obj->addLawnMawning($request, $landscaper_id, $service_id);
                            $landscaper_obj->updateRecurringServices($request, $landscaper_id, $service_id);
                            break;
                        case 2:
                            $landscaper_obj->addLeafRemoval($request, $landscaper_id, $service_id);
                            $landscaper_obj->updateRecurringServices($request, $landscaper_id, $service_id);
                            break;
                        case 3:
                            $landscaper_obj->addLawnTreatment($request, $landscaper_id, $service_id);
                            $landscaper_obj->updateRecurringServices($request, $landscaper_id, $service_id);
                            break;
                        case 4:
                            $landscaper_obj->addAeration($request, $landscaper_id, $service_id);
                            $landscaper_obj->updateRecurringServices($request, $landscaper_id, $service_id);
                            break;
                        case 5:
                            $landscaper_obj->addSprinklerWinterizing($request, $landscaper_id, $service_id);
                            $landscaper_obj->updateRecurringServices($request, $landscaper_id, $service_id);
                            break;
                        case 6:
                            $landscaper_obj->addPoolCleaning($request, $landscaper_id, $service_id);
                            $landscaper_obj->updateRecurringServices($request, $landscaper_id, $service_id);
                            break;
                        case 7:
                            $landscaper_obj->addSnowRemoval($request, $landscaper_id, $service_id);
                            $landscaper_obj->updateRecurringServices($request, $landscaper_id, $service_id);
                            break;
                        default:
                            return "Method Not Found";
                    }
                }
            }
        }
        session()->flash("msg", "Service Details Updated Successfully");
        return redirect()->route("landscaper-profile");
    }

    public function EndJob(Request $request) {
//        if (Input::hasFile('end_job_image') && Input::file('end_job_image')->isValid()) {
//            $avatar = $request->file("end_job_image");
//            $fileName = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
//            $avatar->move("uploads/property/", $fileName);
        //$data['end_job_image_name'] = [];

        if (Input::hasFile('end_job_image') && Input::file('end_job_image')) {
            $end_job_images = $request->file("end_job_image");

            foreach ($end_job_images as $end_job_image) {
                $fileName = rand(1111, 9999) . "_" . time() . "_" . $end_job_image->getClientOriginalName();
                //$end_job_image->move("uploads/temp/", $fileName);
                $end_job_image->move("uploads/property/", $fileName);
                //$data['end_job_image_name'][] = $fileName;
                //File::move("uploads/temp/" . $end_job_image, "uploads/property/" . $end_job_image);
//                    $ar = [
//                        "book_service_id" => $request->book_service_id,
//                        "uploaded_by" => session("user_id"),
//                        "service_image" => $fileName];
//                    ServiceImages::insert($ar);
                DB::insert("INSERT INTO service_images VALUES(NULL," . $request->book_service_id . "," . session('user_id') . ",'" . $fileName . "')");
            }

            //DB::insert("INSERT INTO service_images VALUES(NULL," . $request->book_service_id . "," . session('user_id') . ",'" . $fileName . "')");
//                date_default_timezone_set(session('current_timezone'));
//                date_default_timezone_set(date('d-m-Y H:i:s'));
            $required_time = date("Y-m-d H:i:s");
            DB::update("UPDATE book_services SET status=3 ,`completion_date`= '$required_time',notification_status_user=1,notification_status_landscaper=1 WHERE id=" . $request->book_service_id . "");
            session()->flash("msg", "");
            return redirect()->route("booking-history-landscaper");
        }
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
        return redirect()->route("landscaper-profile");
    }

}
