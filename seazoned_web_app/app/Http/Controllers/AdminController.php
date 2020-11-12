<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
//use App\AdminLogin;
use App\LoginDetail;
use App\User;
use App\Properties;
use \Illuminate\Http\Request;
//use App\ReferralResources;
//use \App\ReferralForm;
//use App\Reference;
//use App\Handout;
use Illuminate\Support\Facades\Input;
use Validator;
use Redirect;
use Session;
use Mail;
use File;
use Excel;
use PDF;
use App\UserDetail;
use App\Country;
use App\Service;
use App\BookService;
use App\ServicePrice;
use App\ServiceRating;
use App\AddressBook;
use App\Landscaper;

Class AdminController extends Controller {

    public function Login(Request $request) {
        if ($request->session()->has('admin_userid')) {

            return redirect()->route('AdminHome');
            Redirect::to('/AdminHome');
        } else {
            $data = array('title' => 'Login Page', 'theme_type' => 'admin');
            return View('Admin.login', $data);
        }
    }

    public function Logout(Request $request) {
        $request->session()->forget('key');
        $request->session()->flush();
        return redirect()->route('Admin');
    }

    public function LoginAccess(Request $request) {

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $user_detail_obj = new UserDetail;
        $all_data = $user_detail_obj->UserDetails();

        $match = 0;

        foreach ($all_data as $each_data) {
            if ($each_data->username == $request['username'] && $each_data->password == md5($request['password'])) {
                $match = 1;
                session(['admin_userid' => $each_data->id]);
                session(['user_type' => $each_data->user_type]);
                session(['profile' => $each_data->profile_id]);
                break;
            }
        }

        if ($match == 0) {
            $user_class = '';
            $title = '';
            return Redirect::route("Admin")->with('global', '<div class="alert alert-error alert-dismissable">
                                            <i class="fa fa-check"></i>
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <b>Alert!</b> Wrong Username or Password</div>')->with('user_class', $user_class)->with('title', $title);
            return redirect()->back();
        } else
        //return redirect()->route('AdminHome');
            return redirect()->route('ManageLandscapers');
    }

    public function AdminHome(Request $request) {
        if ($request->session()->has('admin_userid')) {
            return View('Admin.home', ['title' => 'Seazoned App']);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function ManageLandscapers(Request $request) {
        if ($request->session()->has('admin_userid')) {

            $user_detail_obj = new UserDetail;
            $data = $user_detail_obj->LandscaperDetails();

            $prop_class = 'active';
            $title = session('user_type');

            return View('Admin.landscapers', ['data' => $data, 'prop_class' => $prop_class, 'title' => $title]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function EditLandscaper(Request $request, $id) {
        if ($request->session()->has('admin_userid')) {

            $user_detail_obj = new UserDetail;
            $data = $user_detail_obj->GetLandscaper($id);

            $prop_class = 'active';
            $title = 'Landscaper';
            $active_arr = array(0 => 'Inactive', 1 => 'Active');
            $country_arr = 'SELECT id,country_name FROM countries';
            $country_arr = DB::select($country_arr);
            foreach ($country_arr as $country_val) {
                $temp_arr[$country_val->id] = $country_val->country_name;
            }
            $country_arr = $temp_arr;
            $service_arr = array(1 => 'Mowing and Edging', 2 => 'Leaf Removal', 3 => 'Lawn Treatment', 4 => 'Aeration', 5 => 'Sprinkler Winterizing', 6 => 'Pool Cleaning & Upkeep', 7 => 'Snow Removal');

            return View('Admin.edit_landscaper', ['data' => $data, 'prop_class' => $prop_class, 'title' => $title, 'active_arr' => $active_arr, 'service_arr' => $service_arr, 'country_arr' => $country_arr]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function EditLandscaperSubmit(Request $request) {
        if ($request->session()->has('admin_userid')) {

            $validator = Validator::make(Input::all(), array(
                        'name' => "required|max:255",
                        'first_name' => "required|max:255",
                        'last_name' => "required|max:255",
                        'email' => 'required',
                        'address' => "required",
                        'username' => 'required',
                        'password' => 'required|min:8',
                        'address' => 'required',
                        'city' => 'required',
                        'state' => 'required'
                            )
            );

            if ($validator->fails()) {
                $from = 'edit_user';
                $user_class = 'active';
                $title = 'Edit Landscaper';
                return Redirect::route("EditLandscaper", array('id' => $_REQUEST['id']))
                                ->with('user_class', $user_class)->with('title', $title)
                                ->withErrors($validator)
                                ->withInput();
            } else {
                $id = $_REQUEST['id'];
                $first_name = $_REQUEST['first_name'];
                $last_name = $_REQUEST['last_name'];
                $email = $_REQUEST['email'];
                $phone_number = $_REQUEST['phone_number'];
                $address = $_REQUEST['address'];
                $username = $_REQUEST['username'];
                $password = md5($_REQUEST['password']);
                $city = $_REQUEST['city'];
                $state = $_REQUEST['state'];
                //$country = $_REQUEST['country'];
                $status = $_REQUEST['active'];
                $service_id = $_REQUEST['service_id'];
                $name = $_REQUEST['name'];
                $description = $_REQUEST['description'];
                $profile_picture = "";

                //if (Input::hasFile('profile_image') && Input::file('profile_image')->isValid())
                if ($request->file('profile_image')) {

                    $imageName = rand(1111, 9999) . time() . '_' . $request->file('profile_image')->getClientOriginalName();
                    $request->file('profile_image')->move('uploads/profile_picture/', $imageName);
                    $profile_picture = $imageName;
                }

                DB::table('users')
                        ->where('id', $id)
                        ->update(array('username' => $username, 'password' => $password, 'active' => $status));

                DB::table('user_details')
                        ->where('user_id', $id)
                        ->update(array('first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'phone_number' => $phone_number, 'address' => $address, 'city' => $city, 'state' => $state));

                DB::table('landscapers')
                        ->where('user_id', $id)
                        ->update(array('service_id' => $service_id, 'name' => $name, 'description' => $description, 'profile_image' => $profile_picture));

                $user_class = 'active';
                $title = 'Landscaper';
                if ($id) {
                    return Redirect::route("ManageLandscapers")->with('global', '<div class="alert alert-success alert-dismissable">
                                            <i class="fa fa-check"></i>
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <b>Alert!</b> The Landscaper is successfully updated!</div>')->with('user_class', $user_class)->with('title', $title);
                }
            }
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function CreateLandscaper(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $from = 'add_user';
            $user_class = 'active';
            $title = 'Landscaper';
            $active_arr = array(0 => 'Active', 1 => 'Inactive');

            $country_obj = new Country;
            $country_arr = $country_obj->CountryDetails();

            foreach ($country_arr as $country_val) {
                $temp_arr[$country_val->id] = $country_val->country_name;
            }
            $country_arr = $temp_arr;
            $service_arr = array(1 => 'Mowing and Edging', 2 => 'Leaf Removal', 3 => 'Lawn Treatment', 4 => 'Aeration', 5 => 'Sprinkler Winterizing', 6 => 'Pool Cleaning & Upkeep', 7 => 'Snow Removal');
            return View('Admin.add_landscaper', ['user_class' => $user_class, 'form' => $from, 'title' => $title, 'active_arr' => $active_arr, 'service_arr' => $service_arr, 'country_arr' => $country_arr]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function CreateLandscaperSubmit(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $validator = Validator::make(Input::all(), array(
                        'name' => "required|max:255",
                        'first_name' => "required|max:255",
                        'last_name' => "required|max:255",
                        'email' => 'required',
                        'address' => "required",
                        'username' => 'required',
                        'password' => 'required|min:8',
                        'address' => 'required',
                        'city' => 'required',
                        'state' => 'required'
                            //'profile_image'=>'required'
                            )
            );

            if ($validator->fails()) {
                $from = 'add_user';
                $user_class = 'active';
                $title = 'Landscaper';
                return Redirect::route("CreateLandscaper")
                                ->with('user_class', $user_class)->with('title', $title)
                                ->withErrors($validator)
                                ->withInput();
            } else {
                $first_name = $_REQUEST['first_name'];
                $last_name = $_REQUEST['last_name'];
                $email = $_REQUEST['email'];
                $phone_number = $_REQUEST['phone_number'];
                $address = $_REQUEST['address'];
                $username = $_REQUEST['username'];
                $password = md5($_REQUEST['password']);
                $city = $_REQUEST['city'];
                $state = $_REQUEST['state'];
                $country = $_REQUEST['country'];
                $status = 0;
                if ($_REQUEST['active'] == 0)
                    $status = 1;
                $service_id = $_REQUEST['service_id'];
                $name = $_REQUEST['name'];
                $description = $_REQUEST['description'];
                $profile_picture = "";

                //if (Input::hasFile('profile_image') && Input::file('profile_image')->isValid())
                if ($request->file('profile_image')) {

                    $imageName = rand(1111, 9999) . time() . '_' . $request->file('profile_image')->getClientOriginalName();
                    $request->file('profile_image')->move('uploads/profile_picture/', $imageName);
                    $profile_picture = $imageName;
                }


                $id = DB::table('users')->insertGetId(
                        [
                            'profile_id' => 3,
                            'username' => $username,
                            'password' => md5($password),
                            'user_type' => 'landscaper',
                            'active' => 0
                        ]
                );

                if ($id != null) {
                    DB::table('user_details')->insertGetId(
                            [
                                'user_id' => $id,
                                'first_name' => $first_name,
                                'last_name' => $last_name,
                                'email' => $email,
                                'phone_number' => $phone_number,
                                'date_of_birth' => '1987-04-02',
                                'address' => $address,
                                'city' => $city,
                                'state' => $state,
                                'country' => $country,
                                'profile_image' => null
                            ]
                    );

                    DB::table('landscapers')->insertGetId(
                            [
                                'user_id' => $id,
                                'service_id' => $service_id,
                                'name' => $name,
                                'description' => $description,
                                'profile_image' => $profile_picture,
                                'location' => null
                            ]
                    );
                }


                $user_class = 'active';
                $title = 'Landscaper';
                if ($id) {
                    return Redirect::route("ManageLandscapers")->with('global', '<div class="alert alert-success alert-dismissable">
                                            <i class="fa fa-check"></i>
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <b>Alert!</b> The ManageLandscaper is successfully added!</div>')->with('user_class', $user_class)->with('title', $title);
                }
            }
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function DeleteLandscaper(Request $request, $id) {
        if ($request->session()->has('admin_userid')) {
            $landscapers = [];
            $bookIds = [];
            $data = DB::table('users')->where('id', '=', $id)->get();
            $sql = DB::select("SELECT * FROM landscapers WHERE user_id = ".$data[0]->id);
            if(!empty($sql)){
                foreach($sql as $landscaper){ 
                    $landscapers[] = $landscaper->id;
                }
                if(!empty($landscapers)){
                    $landscapers_id = implode(',', $landscapers);
                }
            }

            if(isset($landscapers_id) &&  $landscapers_id!=""){
                $qry = DB::select("SELECT * FROM book_services WHERE landscaper_id IN ($landscapers_id)");
                if(!empty($qry)){
                    foreach($qry as $book){ 
                        $bookIds[] = $book->id;
                    }
                    if(!empty($bookIds)){
                        $book_ids = implode(',', $bookIds);
                    }
                    DB::delete("DELETE FROM service_images WHERE book_service_id IN ($book_ids)");
                }

                DB::delete("DELETE FROM book_services WHERE landscaper_id IN ($landscapers_id)");
                DB::delete("DELETE FROM service_prices WHERE landscaper_id IN ($landscapers_id)");            
                DB::delete("DELETE FROM service_details WHERE landscaper_id IN ($landscapers_id)");            
                DB::delete("DELETE FROM service_ratings WHERE landscaper_id IN ($landscapers_id)");
                DB::delete("DELETE FROM favorite_landscapers WHERE landscaper_id IN ($landscapers_id)");
                DB::delete("DELETE FROM service_times WHERE landscaper_id IN ($landscapers_id)");            
                DB::delete("DELETE FROM landscapers WHERE id IN ($landscapers_id)");
            }
           
//            DB::table('book_services')->whereIn('landscaper_id',$landscapers_id)->delete();
//            DB::table('service_details')->whereIn('landscaper_id',$landscapers_id)->delete();
//            DB::table('service_prices')->whereIn('landscaper_id',$landscapers_id)->delete();
//            DB::table('service_ratings')->whereIn('landscaper_id',$landscapers_id)->delete();
//            DB::table('favorite_landscapers')->whereIn('landscaper_id',$landscapers_id)->delete();
//            DB::table('service_times')->whereIn('landscaper_id',$landscapers_id)->delete();
//            DB::table('landscapers')->whereIn('id',$landscapers_id)->delete();
            
            DB::delete("DELETE FROM payment_accounts WHERE user_id = $id");
            DB::delete("DELETE FROM user_messages WHERE (sender_id = $id OR receiver_id = $id)");
            DB::table('user_details')->where('user_id', '=', $id)->delete();
            DB::table('users')->where('id', '=', $id)->delete();

            $prop_class = 'active';
            $title = 'Landscaper';

            return Redirect::route("ManageLandscapers")->with('global', '<div class="alert alert-success alert-dismissable">
                                        <i class="fa fa-check"></i>
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <b>Alert!</b> The Landscaper is successfully deleted!</div>')->with('prop_class', $prop_class)->with('title', $title);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function ManageUsers(Request $request) {
        if ($request->session()->has('admin_userid')) {

            $user_detail_obj = new UserDetail;
            $data = $user_detail_obj->GeneralUserDetails();

            $prop_class = 'active';
            $title = session('user_type');

            return View('Admin.users', ['data' => $data, 'prop_class' => $prop_class, 'title' => $title]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function EditUser(Request $request, $id) {
        if ($request->session()->has('admin_userid')) {

            $user_detail_obj = new UserDetail;
            $data = $user_detail_obj->GetGeneralUser($id);

            $prop_class = 'active';
            $title = 'User';
            $active_arr = array(0 => 'Inactive', 1 => 'Active');
            $country_obj = new Country;
            $country_arr = $country_obj->CountryDetails();
            foreach ($country_arr as $country_val) {
                $temp_arr[$country_val->id] = $country_val->country_name;
            }
            $country_arr = $temp_arr;

            return View('Admin.edit_user', ['data' => $data, 'prop_class' => $prop_class, 'title' => $title, 'active_arr' => $active_arr, 'country_arr' => $country_arr]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function EditUserSubmit(Request $request) {
        if ($request->session()->has('admin_userid')) {

            $validator = Validator::make(Input::all(), array(
                        'first_name' => "required|max:255",
                        'last_name' => "required|max:255",
                        'email' => 'required',
                        'address' => "required",
                        'password' => 'required|min:8',
                        'address' => 'required',
                        'city' => 'required',
                        'state' => 'required'
                            )
            );

            if ($validator->fails()) {
                $from = 'edit_user';
                $user_class = 'active';
                $title = 'Edit User';
                return Redirect::route("EditUser", array('id' => $_REQUEST['id']))
                                ->with('user_class', $user_class)->with('title', $title)
                                ->withErrors($validator)
                                ->withInput();
            } else {
                $id = $_REQUEST['id'];
                $first_name = $_REQUEST['first_name'];
                $last_name = $_REQUEST['last_name'];
                $email = $_REQUEST['email'];
                $phone_number = $_REQUEST['phone_number'];
                $address = $_REQUEST['address'];
                $username = $_REQUEST['email'];
                $password = md5($_REQUEST['password']);
                $city = $_REQUEST['city'];
                $state = $_REQUEST['state'];
                //$country = $_REQUEST['country'];
                $status = $_REQUEST['active'];

                DB::table('users')
                        ->where('id', $id)
                        ->update(array('username' => $username, 'password' => $password, 'active' => $status));

                DB::table('user_details')
                        ->where('user_id', $id)
                        ->update(array('first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'phone_number' => $phone_number, 'address' => $address, 'city' => $city, 'state' => $state));

                $user_class = 'active';
                $title = 'User';
                if ($id) {
                    return Redirect::route("ManageUsers")->with('global', '<div class="alert alert-success alert-dismissable">
                                            <i class="fa fa-check"></i>
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <b>Alert!</b> The User is successfully updated!</div>')->with('user_class', $user_class)->with('title', $title);
                }
            }
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function CreateUser(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $from = 'add_user';
            $user_class = 'active';
            $title = 'User';
            $active_arr = array(0 => 'Active', 1 => 'Inactive');
            $country_obj = new Country;
            $country_arr = $country_obj->CountryDetails();
            foreach ($country_arr as $country_val) {
                $temp_arr[$country_val->id] = $country_val->country_name;
            }
            $country_arr = $temp_arr;
            return View('Admin.add_user', ['user_class' => $user_class, 'form' => $from, 'title' => $title, 'active_arr' => $active_arr, 'country_arr' => $country_arr]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function CreateUserSubmit(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $validator = Validator::make(Input::all(), array(
                        'first_name' => "required|max:255",
                        'last_name' => "required|max:255",
                        'email' => 'required',
                        'address' => "required",
                        'password' => 'required|min:8',
                        'address' => 'required',
                        'city' => 'required',
                        'state' => 'required'
                            )
            );

            if ($validator->fails()) {
                $from = 'add_user';
                $user_class = 'active';
                $title = 'User';
                return Redirect::route("CreateUser")
                                ->with('user_class', $user_class)->with('title', $title)
                                ->withErrors($validator)
                                ->withInput();
            } else {
                $first_name = $_REQUEST['first_name'];
                $last_name = $_REQUEST['last_name'];
                $email = $_REQUEST['email'];
                $phone_number = $_REQUEST['phone_number'];
                $address = $_REQUEST['address'];
                $username = $_REQUEST['email'];
                $password = md5($_REQUEST['password']);
                $city = $_REQUEST['city'];
                $state = $_REQUEST['state'];
                //$country = $_REQUEST['country'];
                $status = 0;
                if ($_REQUEST['active'] == 0)
                    $status = 1;

                $user = DB::select("select id from users where username = '".$username."'");

                if(count($user) == 0){
                    $id = DB::table('users')->insertGetId(
                            [
                                'profile_id' => 2,
                                'username' => $username,
                                'password' => md5($password),
                                'user_type' => 'user',
                                'active' => 0
                            ]
                    );

                    $arr = [
                        'user_id' => $id,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $email,
                        'phone_number' => $phone_number,
                        'date_of_birth' => '1987-04-02',
                        'address' => $address,
                        'city' => $city,
                        'state' => $state,
                        //'country' => $country,
                        'profile_image' => null
                    ];
                    //print_r($arr);exit;

                    if ($id != null) {
                        DB::table('user_details')->insertGetId($arr);
                    }


                    $user_class = 'active';
                    $title = 'User';
                    if ($id) {
                        return Redirect::route("ManageUsers")->with('global', '<div class="alert alert-success alert-dismissable">
                                                <i class="fa fa-check"></i>
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <b>Alert!</b> The User is successfully added!</div>')->with('user_class', $user_class)->with('title', $title);
                    }

                } else {
                    $user_class = 'active';
                    $title = 'User';
                        return Redirect::route("ManageUsers")->with('global', '<div class="alert alert-danger alert-dismissable">
                                                <i class="fa fa-check"></i>
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <b>Alert!</b> Email Already Exist! </div>')->with('user_class', $user_class)->with('title', $title);
                
                }
            } 
        } else {
                Redirect::to('/AdminHome');
            }
        }

    public function DeleteUser(Request $request, $id) {
        if ($request->session()->has('admin_userid')) {

            $data = DB::table('users')->where('id', '=', $id)->get();

            $book_data = DB::table('book_services')->where('customer_id', '=', $id)->get();

            if(count($book_data)!=0){
                foreach($book_data as $each_book_data){
                $book_service_id = $each_book_data->id;
                DB::table('service_images')->where('book_service_id', '=', $book_service_id)->delete();
            }
            
                }

            DB::table('service_ratings')->where('customer_id', '=', $id)->delete();
            DB::table('payment_accounts')->where('user_id', '=', $id)->delete();
            DB::table('favorite_landscapers')->where('user_id', '=', $id)->delete();
            DB::table('book_services')->where('customer_id', '=', $id)->delete();
            DB::delete("DELETE FROM user_messages WHERE (sender_id = $id OR receiver_id = $id)");
            DB::table('address_books')->where('user_id', '=', $id)->delete();
            DB::table('user_details')->where('user_id', '=', $id)->delete();
            DB::table('users')->where('id', '=', $id)->delete();

            $prop_class = 'active';
            $title = 'User';

            return Redirect::route("ManageUsers")->with('global', '<div class="alert alert-success alert-dismissable">
                                        <i class="fa fa-check"></i>
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <b>Alert!</b> The User is successfully deleted!</div>')->with('prop_class', $prop_class)->with('title', $title);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function ManageServices(Request $request) {
        if ($request->session()->has('admin_userid')) {

            $service_obj = new Service;
            $data = $service_obj->ServiceDetails();

            $prop_class = 'active';
            $title = session('user_type');

            return View('Admin.services', ['data' => $data, 'prop_class' => $prop_class, 'title' => $title]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function EditServices(Request $request, $id) {
        if ($request->session()->has('admin_userid')) {

            $service_obj = new Service;
            $data = $service_obj->GetServices($id);

            $prop_class = 'active';
            $title = 'Services';

            return View('Admin.edit_service', ['data' => $data, 'prop_class' => $prop_class, 'title' => $title]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function EditServicesSubmit(Request $request) {
        if ($request->session()->has('admin_userid')) {

            $validator = Validator::make(Input::all(), array(
                        'service_name' => "required|max:255"
                            )
            );

            if ($validator->fails()) {
                $from = 'edit_user';
                $user_class = 'active';
                $title = 'Edit Service';
                return Redirect::route("EditServices", array('id' => $_REQUEST['id']))
                                ->with('user_class', $user_class)->with('title', $title)
                                ->withErrors($validator)
                                ->withInput();
            } else {
                $id = $_REQUEST['id'];
                $service_name = $_REQUEST['service_name'];
                $description = $_REQUEST['description'];

                DB::table('services')
                        ->where('id', $id)
                        ->update(array('service_name' => $service_name, 'description' => $description));

                $user_class = 'active';
                $title = 'Service';
                if ($id) {
                    return Redirect::route("ManageServices")->with('global', '<div class="alert alert-success alert-dismissable">
                                            <i class="fa fa-check"></i>
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <b>Alert!</b> The Service is successfully updated!</div>')->with('user_class', $user_class)->with('title', $title);
                }
            }
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function ManageBookings(Request $request) {
        if ($request->session()->has('admin_userid')) {

            $book_service_obj = new BookService;
            $data = $book_service_obj->BookingDetails();

            $prop_class = 'active';
            $title = session('user_type');

            return View('Admin.service_bookings', ['data' => $data, 'prop_class' => $prop_class, 'title' => $title]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function ServicePrices(Request $request) {
        if ($request->session()->has('admin_userid')) {

            $service_price_obj = new ServicePrice;
            $data = $service_price_obj->ServicePriceDetails();

            $prop_class = 'active';
            $title = session('user_type');

            return View('Admin.service_prices', ['data' => $data, 'prop_class' => $prop_class, 'title' => $title]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function CreateServicePrices(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $from = 'add_service_price';
            $user_class = 'active';
            $title = 'Service Price';

            $service_obj = new Service;
            $service_arr = $service_obj->ServiceDetails();
            foreach ($service_arr as $service_val) {
                $temp_arr[$service_val->id] = $service_val->service_name;
            }
            $service_arr = $temp_arr;

            $landscaper_obj = new Landscaper;
            $landscaper_arr = $landscaper_obj->LandscaperDetails();
            foreach ($landscaper_arr as $landscaper_val) {
                $temp_arr1[$landscaper_val->id] = $landscaper_val->name;
            }
            $landscaper_arr = $temp_arr1;

            return View('Admin.add_service_price', ['user_class' => $user_class, 'form' => $from, 'title' => $title, 'service_arr' => $service_arr, 'landscaper_arr' => $landscaper_arr]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function CreateServicePricesSubmit(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $validator = Validator::make(Input::all(), array(
                        'service_frequency' => "required|max:255",
                        'discount_price' => "required|max:255"
                            )
            );

            if ($validator->fails()) {
                $from = 'add_service_price';
                $user_class = 'active';
                $title = 'Service Price';
                return Redirect::route("CreateUser")
                                ->with('user_class', $user_class)->with('title', $title)
                                ->withErrors($validator)
                                ->withInput();
            } else {
                $landscaper_id = $_REQUEST['landscaper_id'];
                $service_id = $_REQUEST['service_id'];
                $service_frequency = $_REQUEST['service_frequency'];
                $discount_price = $_REQUEST['discount_price'];

                $id = DB::table('service_prices')->insertGetId(
                        [
                            'landscaper_id' => $landscaper_id,
                            'service_id' => $service_id,
                            'service_frequency' => $service_frequency,
                            'discount_price' => $discount_price
                        ]
                );


                $user_class = 'active';
                $title = 'Service Price';
                if ($id) {
                    return Redirect::route("ServicePrices")->with('global', '<div class="alert alert-success alert-dismissable">
                                            <i class="fa fa-check"></i>
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <b>Alert!</b> TService Price is successfully added!</div>')->with('user_class', $user_class)->with('title', $title);
                }
            }
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function EditServicePrices(Request $request, $id) {
        if ($request->session()->has('admin_userid')) {

            $service_price_obj = new ServicePrice;
            $data = $service_price_obj->GetServicePrice($id);

            $service_obj = new Service;
            $service_arr = $service_obj->ServiceDetails();
            foreach ($service_arr as $service_val) {
                $temp_arr[$service_val->id] = $service_val->service_name;
            }
            $service_arr = $temp_arr;

            $landscaper_obj = new Landscaper;
            $landscaper_arr = $landscaper_obj->LandscaperDetails();
            foreach ($landscaper_arr as $landscaper_val) {
                $temp_arr1[$landscaper_val->id] = $landscaper_val->name;
            }
            $landscaper_arr = $temp_arr1;

            $prop_class = 'active';
            $title = 'Services';

            return View('Admin.edit_service_price', ['data' => $data, 'prop_class' => $prop_class, 'title' => $title, 'service_arr' => $service_arr, 'landscaper_arr' => $landscaper_arr]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function EditServicePricesSubmit(Request $request) {
        if ($request->session()->has('admin_userid')) {

            $validator = Validator::make(Input::all(), array(
                        'service_frequency' => "required|max:255",
                        'discount_price' => "required|max:255"
                            )
            );

            if ($validator->fails()) {
                $from = 'edit_service_price';
                $user_class = 'active';
                $title = 'Edit Service Price';
                return Redirect::route("EditServicePrices", array('id' => $_REQUEST['id']))
                                ->with('user_class', $user_class)->with('title', $title)
                                ->withErrors($validator)
                                ->withInput();
            } else {
                $id = $_REQUEST['id'];
                $landscaper_id = $_REQUEST['landscaper_id'];
                $service_id = $_REQUEST['service_id'];
                $service_frequency = $_REQUEST['service_frequency'];
                $discount_price = $_REQUEST['discount_price'];

                DB::table('service_prices')
                        ->where('id', $id)
                        ->update(array('landscaper_id' => $landscaper_id, 'service_id' => $service_id, 'service_frequency' => $service_frequency, 'discount_price' => $discount_price));

                $user_class = 'active';
                $title = 'Service Price';
                if ($id) {
                    return Redirect::route("ServicePrices")->with('global', '<div class="alert alert-success alert-dismissable">
                                            <i class="fa fa-check"></i>
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <b>Alert!</b> Service price is successfully updated!</div>')->with('user_class', $user_class)->with('title', $title);
                }
            }
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function ServiceRatings(Request $request) {
        if ($request->session()->has('admin_userid')) {

            $service_rating_obj = new ServiceRating;
            $data = $service_rating_obj->ServiceRatingDetails();

            $prop_class = 'active';
            $title = session('user_type');

            return View('Admin.service_ratings', ['data' => $data, 'prop_class' => $prop_class, 'title' => $title]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function AddressBooks(Request $request) {
        if ($request->session()->has('admin_userid')) {

            $address_book_obj = new AddressBook;
            $data = $address_book_obj->AddressBookDetails();

            $prop_class = 'active';
            $title = session('user_type');

            return View('Admin.address_books', ['data' => $data, 'prop_class' => $prop_class, 'title' => $title]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function getAddress(Request $request) {
        if ($request->session()->has('admin_userid')) {
            if (isset($request->user_id) && $request->user_id != null) {
                $select = 'SELECT ab.*,ud.first_name,ud.last_name,c.* FROM address_books ab, user_details ud, countries c WHERE ab.user_id=ud.user_id AND ab.country=c.id AND ab.user_id=' . $request->user_id . '';
                $data = DB::select($select);
                if (!empty($data)) {
                    foreach ($data as $each_data) {

                        echo '<div class="form-group">';
                        echo '<label>Contact Name:&nbsp&nbsp</label>';
                        echo $each_data->name;
                        echo '</div>';

                        echo '<div class="form-group">';
                        echo '<label>Address:&nbsp&nbsp</label>';
                        echo $each_data->address . ', ' . $each_data->city . ', ' . $each_data->state . ', ' . $each_data->country_name;
                        echo '</div>';

                        echo '<div class="form-group">';
                        echo '<label>Email:&nbsp&nbsp</label>';
                        echo $each_data->email_address;
                        echo '</div>';

                        echo '<div class="form-group">';
                        echo '<label>Contact Number:&nbsp&nbsp</label>';
                        echo $each_data->contact_number;
                        echo '</div>';

                        echo '<div class="form-group">';
                        echo '<label>Primary Contact:&nbsp&nbsp</label>';
                        echo $each_data->primary_address == 1 ? 'Yes' : 'No';
                        echo '</div>';

                        echo '<hr>';
                    }
                }
            }
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function getBookingHistory(Request $request) {
        if ($request->session()->has('admin_userid')) {
            if ((isset($request->customer_id) && $request->customer_id != null) || (isset($request->landscaper_id) && $request->landscaper_id != null)) {
                if (isset($request->customer_id))
                    $select = 'SELECT bs.*,l.*,ud.*,s.* FROM book_services bs LEFT JOIN landscapers l ON(bs.landscaper_id=l.id) LEFT JOIN user_details ud ON(bs.customer_id=ud.user_id) LEFT JOIN services s ON(l.service_id=s.id) WHERE bs.customer_id=' . $request->customer_id . '';
                else
                    $select = 'SELECT bs.*,l.*,ud.*,s.* FROM book_services bs LEFT JOIN landscapers l ON(bs.landscaper_id=l.id) LEFT JOIN user_details ud ON(bs.customer_id=ud.user_id) LEFT JOIN services s ON(l.service_id=s.id) WHERE bs.landscaper_id=' . $request->landscaper_id . '';
                $data = DB::select($select);
                if (!empty($data)) {
                    foreach ($data as $each_data) {
                        if (isset($request->landscaper_id) && !isset($request->customer_id)) {
                            echo '<div class="form-group">';
                            echo '<label>Customer Name:&nbsp&nbsp</label>';
                            echo $each_data->first_name . ' ' . $each_data->last_name;
                            echo '</div>';
                        }

                        if (isset($request->customer_id) && !isset($request->landscaper_id)) {
                            echo '<div class="form-group">';
                            echo '<label>Landscaper Details:&nbsp&nbsp</label>';
                            echo $each_data->name;
                            echo '</div>';
                        }

                        echo '<div class="form-group">';
                        echo '<label>Service:&nbsp&nbsp</label>';
                        echo $each_data->service_name;
                        echo '</div>';

                        echo '<div class="form-group">';
                        echo '<label>Service Date:&nbsp&nbsp</label>';
                        echo $each_data->service_date;
                        echo '</div>';

                        echo '<div class="form-group">';
                        echo '<label>Service Time:&nbsp&nbsp</label>';
                        echo $each_data->service_time;
                        echo '</div>';

                        echo '<div class="form-group">';
                        echo '<label>Order Number:&nbsp&nbsp</label>';
                        echo $each_data->order_no;
                        echo '</div>';

                        if ($each_data->status == 0)
                            $service_status = 'Requested';
                        elseif ($each_data->status == 1)
                            $service_status = 'Confirmed';
                        else
                            $service_status = 'Completed';

                        echo '<div class="form-group">';
                        echo '<label>Order Status:&nbsp&nbsp</label>';
                        echo $service_status;
                        echo '</div>';

                        echo '<hr>';
                    }
                }
            }
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function UserMessages(Request $request) {
        if ($request->session()->has('admin_userid')) {

            $select = 'SELECT * FROM user_details ud,user_messages um WHERE (um.sender_id=ud.user_id OR um.receiver_id=ud.user_id)';
            $data = DB::select($select);

            $prop_class = 'active';
            $title = session('user_type');

            return View('Admin.user_messages', ['data' => $data, 'prop_class' => $prop_class, 'title' => $title]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function getMessageDetails(Request $request) {
        if ($request->session()->has('admin_userid')) {
            if (isset($request->id) && $request->id != null) {
                $select = 'SELECT * FROM user_messages WHERE id=' . $request->id . '';
                $data = DB::select($select);
                if (!empty($data)) {
                    foreach ($data as $each_data) {

                        echo '<div class="form-group">';
                        echo '<label>Subject:&nbsp&nbsp</label>';
                        echo $each_data->subject;
                        echo '</div>';

                        echo '<div class="form-group">';
                        echo '<label>Description:&nbsp&nbsp</label>';
                        echo $each_data->description;
                        echo '</div>';

                        echo '<hr>';
                    }
                }
            }
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function ViewPayments(Request $request) {
        if ($request->session()->has('admin_userid')) {

            $book_service_obj = new BookService;
            $data = $book_service_obj->PaymentDetails();
            $percentage = $book_service_obj->getPercentage();
            $prop_class = 'active';
            $title = session('user_type');

            return View('Admin.view_payments', ['data' => $data,'percentage' => $percentage,'prop_class' => $prop_class, 'title' => $title]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function getLandscaperRevenue(Request $request) {
        if ($request->session()->has('admin_userid')) {
            if (isset($request->landscaper_id) && $request->landscaper_id != null) {
                $select = 'SELECT service_date,service_price,status FROM book_services WHERE landscaper_id=' . $request->landscaper_id . ' AND status=1';
                $data = DB::select($select);
                $total_revenue = 0.00;
                if (!empty($data)) {
                    foreach ($data as $each_data) {
                        echo '<div class="form-group">';
                        echo '<label>Received Date:&nbsp&nbsp</label>';
                        echo $each_data->service_date;
                        echo '</div>';

                        if ($each_data->status == 0)
                            $service_status = 'Pending';
                        elseif ($each_data->status == 1)
                            $service_status = 'Pending';
                        else
                            $service_status = 'Received';

                        echo '<div class="form-group">';
                        echo '<label>Revenue:&nbsp&nbsp</label>';
                        echo '$' . ($each_data->service_price / 10) . '.00' . ' (' . $service_status . ')';
                        echo '</div>';

                        $total_revenue += $each_data->service_price / 10;

                        echo '<hr>';
                    }
                    echo '<div class="form-group">';
                    echo '<label>Total Revenue:&nbsp&nbsp</label>';
                    echo '$' . $total_revenue . '.00';
                    echo '</div>';
                }
            }
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function getServiceRevenue(Request $request) {
        if ($request->session()->has('admin_userid')) {
            if (isset($request->service_id) && $request->service_id != null) {
                $select = 'SELECT service_date,service_price,status FROM book_services bs,landscapers l WHERE bs.landscaper_id=l.id AND l.service_id=' . $request->service_id . ' AND status=1';
                $data = DB::select($select);
                $total_revenue = 0.00;
                if (!empty($data)) {
                    foreach ($data as $each_data) {
                        echo '<div class="form-group">';
                        echo '<label>Received Date:&nbsp&nbsp</label>';
                        echo $each_data->service_date;
                        echo '</div>';

                        if ($each_data->status == 0)
                            $service_status = 'Pending';
                        elseif ($each_data->status == 1)
                            $service_status = 'Pending';
                        else
                            $service_status = 'Received';

                        echo '<div class="form-group">';
                        echo '<label>Revenue:&nbsp&nbsp</label>';
                        echo '$' . ($each_data->service_price / 10) . '.00' . ' (' . $service_status . ')';
                        echo '</div>';

                        $total_revenue += $each_data->service_price / 10;

                        echo '<hr>';
                    }
                    echo '<div class="form-group">';
                    echo '<label>Total Revenue:&nbsp&nbsp</label>';
                    echo '$' . $total_revenue . '.00';
                    echo '</div>';
                }
            }
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function getOverallRating(Request $request) {
        if ($request->session()->has('admin_userid')) {
            if (isset($request->landscaper_id) && $request->landscaper_id != null) {
                $select = 'SELECT AVG(rating_value) AS overall_rating FROM service_ratings WHERE landscaper_id=' . $request->landscaper_id . '';
                $data = DB::select($select);
                if (!empty($data)) {
                    foreach ($data as $each_data) {
                        echo '<div class="form-group">';
                        echo '<label>Overall Rating:&nbsp&nbsp</label>';
                        echo $each_data->overall_rating;
                        echo '</div>';

                        echo '<hr>';
                    }
                }
            }
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function PaymentPercentage(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $select = "SELECT * FROM payment_percentages where user_id=" . session('admin_userid');
            $data1 = DB::select($select);
            if (count($data1) != '') {
                $data = $data1[0]->percentage;
            } else {
                $data = '';
            }
            return View('Admin.payment_percentage', ['data' => $data]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function AddpaymentPercentage(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $admin_id = DB::select("SELECT user_id FROM payment_percentages where user_id=" . session('admin_userid'));
            if (count($admin_id) == 0) {
                DB::table('payment_percentages')->insert(['user_id' => session('admin_userid'), 'percentage' => $request->payment_percentage]);
            } else {
                DB::table('payment_percentages')->where(['user_id' => session('admin_userid')])->update(['percentage' => $request->payment_percentage]);
            }
            return Redirect::to('/PaymentPercentage');
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function ManagePayple(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $select = "SELECT * FROM payment_accounts where user_id=" . session('admin_userid');
            $data1 = DB::select($select);
            if(count($data1)!=0){
                $data['name']=$data1[0]->name;
                $data['account_dtls']=$data1[0]->account_details;
                $data['account_email']=$data1[0]->account_email;
                $data['account_signature']=$data1[0]->account_signature;
                $data['account_password']=$data1[0]->account_password;
            }else{
                $data='';
            }
            return View('Admin.manage_payple', ['data' => $data]);
        } else {
            Redirect::to('/AdminHome');
        }
    }

    public function AddPaypleName(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $admin_id = DB::select("SELECT user_id FROM payment_accounts where user_id=" . session('admin_userid'));
            if (count($admin_id) == 0) {
                DB::table('payment_accounts')->insert(['user_id' => session('admin_userid'),'name' => $request->name,'account_email' => $request->account_email,'account_details'=>$request->account_details,'account_signature'=>$request->account_signature,'account_password'=>$request->account_password ]);
            } else {
                DB::table('payment_accounts')->where(['user_id' => session('admin_userid')])->update(['name' => $request->name,'account_email' => $request->account_email,'account_details'=>$request->account_details,'account_signature'=>$request->account_signature,'account_password'=>$request->account_password ]);
            }
            return Redirect::to('/ManagePayple');
        } else {
            Redirect::to('/AdminHome');
        }
    }
    
    public function PrivacyPolicy(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $select = "SELECT * FROM privacy_policy ";
            $data1 = DB::select($select);
            if (count($data1) != '') {
                $data = $data1[0]->content;
            } else {
                $data = '';
            }
            return View('Admin.privacy_policy_admin', ['data' => $data]);
        } else {
            Redirect::to('/AdminHome');
        }
    }
    
    public function AddprivacyPolicy(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $privacy_content = DB::select("SELECT id FROM privacy_policy ");
            if (count($privacy_content) == 0) {
                DB::table('privacy_policy')->insert(['content' => $request->content]);
            } else {
                DB::table('privacy_policy')->update(['content' => $request->content]);
            }
            return Redirect::to('/PrivacyPolicy');
        } else {
            Redirect::to('/AdminHome');
        }
    }
    
    public function faq(Request $request) {
       
        if ($request->session()->has('admin_userid')) {
            $select = "SELECT * FROM faq ";
            $data = DB::select($select);   
            return View('Admin.faq_admin', ['data' => $data]);
        } else {
            Redirect::to('/AdminHome');
        }
    }
    
//    public function Addfaq(Request $request) {
//        if ($request->session()->has('admin_userid')) {
//            $admin_id = DB::select("SELECT user_id FROM faq where user_id=" . session('admin_userid'));
//            if (count($admin_id) == 0) {
//                DB::table('faq')->insert(['user_id' => session('admin_userid'), 'questions' => $request->questions,'answers' => $request->answers]);
//            } else {
//                DB::table('faq')->where(['user_id' => session('admin_userid')])->update(['questions' => $request->questions,'answers' => $request->answers]);
//            }
//            return Redirect::to('/faq');
//        } else {
//            Redirect::to('/AdminHome');
//        }
//    }
     public function new_faq(Request $request) {
         
         if ($request->session()->has('admin_userid')) {
             
             return view('Admin.add_faq_admin');
        } else {
            Redirect::to('/AdminHome');
        }
         
     }
     public function Add_new_faq(Request $request) {      
         if ($request->session()->has('admin_userid')) {
         
         
         DB::table('faq')->insert(['profile_id'=>$request->profile_id,'questions' => $request->questions,'answers' => $request->answers]);
       
          return Redirect::to('/faq');
          } else {
            Redirect::to('/AdminHome');
        }
     }
       public function Edit_faq(Request $request,$id) {
          if ($request->session()->has('admin_userid')) {
         
         $data = DB::select("select * from faq where id= ".$id );
        
             
             return view('Admin.edit_faq', ['data' => $data]);
        } else {
            Redirect::to('/AdminHome');
        }
       }   
         public function update_faq(Request $request,$id) {
          if ($request->session()->has('admin_userid')) {
         
        $data['questions'] = $request->questions;
        $data['answers']= $request->answers;
        
//        print_r($data);
        DB::table('faq')
            ->where('id', $id)
            ->update($data);
   
         return redirect()->route("faq");
     }
        
        
     }
     
     public function delete_faq(Request $request,$id) {
 
         DB::select("DELETE FROM `faq` WHERE id =".$id);
      
          return redirect()->route("faq");
     }
     
      public function why_work_with_us(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $select = "SELECT * FROM why_work_with_us ";
            $data1 = DB::select($select);
            if (count($data1) != '') {
                $data = $data1[0]->reasons;
            } else {
                $data = '';
            }
            return View('Admin.why_work_with_us_admin', ['data' => $data]);
        } else {
            Redirect::to('/AdminHome');
        }
    }
    
    public function Add_why_work_with_us(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $admin_content = DB::select("SELECT id FROM why_work_with_us ");
            if (count($admin_content) == 0) {
                DB::table('why_work_with_us')->insert(['reasons' => $request->reasons]);
            } else {
                DB::table('why_work_with_us')->update(['reasons' => $request->reasons]);
            }
            return Redirect::to('why-work-with-us');
        } else {
            Redirect::to('/AdminHome');
        }
    }
     
     public function lawn_mowing_tips(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $select = "SELECT * FROM lawn_mowing_tips ";
            $data1 = DB::select($select);
            if (count($data1) != '') {
                $data = $data1[0]->tips;
            } else {
                $data = '';
            }
            return View('Admin.lawn_mowing_tips_admin', ['data' => $data]);
        } else {
            Redirect::to('/AdminHome');
        }
    }
    
    public function Add_lawn_mowing_tips(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $tips_content = DB::select("SELECT id FROM lawn_mowing_tips ");
            if (count($tips_content) == 0) {
                DB::table('lawn_mowing_tips')->insert(['tips' => $request->tips]);
            } else {
                DB::table('lawn_mowing_tips')->update(['tips' => $request->tips]);
            }
            return Redirect::to('lawn-mowing-tips');
        } else {
            Redirect::to('/AdminHome');
        }
    }
    
    
    public function terms_services(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $select = "SELECT * FROM terms_services ";
            $data1 = DB::select($select);
            if (count($data1) != '') {
                $data = $data1[0]->content;
            } else {
                $data = '';
            }
            return View('Admin.terms_services_admin', ['data' => $data]);
        } else {
            Redirect::to('/AdminHome');
        }
    }
    
    public function Add_terms_services(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $terms_content = DB::select("SELECT id FROM terms_services ");
            if (count($terms_content) == 0) {
                DB::table('terms_services')->insert(['content' => $request->content]);
            } else {
                DB::table('terms_services')->update(['content' => $request->content]);
            }
            return Redirect::to('terms-services');
        } else {
            Redirect::to('/AdminHome');
        }
    }
     
    public function about_us(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $select = "SELECT * FROM about_us ";
            $data1 = DB::select($select);
            if (count($data1) != '') {
                $data = $data1[0]->content;
            } else {
                $data = '';
            }
            return View('Admin.about_us_admin', ['data' => $data]);
        } else {
            Redirect::to('/AdminHome');
        }
    }
    
    public function Add_about_us(Request $request) {
        if ($request->session()->has('admin_userid')) {
            $admin_content = DB::select("SELECT id FROM about_us ");
            if (count($admin_content) == 0) {
                DB::table('about_us')->insert(['content' => $request->content]);
            } else {
                DB::table('about_us')->update(['content' => $request->content]);
            }
            return Redirect::to('about-us');
        } else {
            Redirect::to('/AdminHome');
        }
    } 
    
    
    
    
    
}

?>
