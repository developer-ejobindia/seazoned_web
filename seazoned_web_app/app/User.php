<?php

namespace App;

use DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'users';
    public static $columns = array(
        "First Name" => 'first_name',
        "Last Name" => "last_name",
        "Username" => "username",
        "Email address" => "email",
        "Role" => "role",
        "Status" => "active"
    );
    protected $fillable = [
        'first_name', 'last_name', 'role', 'phone_number', 'gender', 'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function CheckUserMail($user_id, $mail_id) {
        try {
            $query_uesr = 'SELECT COUNT(id) AS total_mail_no FROM users WHERE email="' . $mail_id . '" AND id NOT IN (' . $user_id . ') ';
            $data = DB::select($query_uesr);
            $total_mail_no = $data[0]->total_mail_no;
            return $total_mail_no;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function check_user_mail($mail_id) {
        try {
            $query_uesr = 'SELECT COUNT(id) AS total_mail_no FROM users WHERE email="' . $mail_id . '"  ';
            $data = DB::select($query_uesr);
            $total_mail_no = $data[0]->total_mail_no;
            return $total_mail_no;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function get_user_name($mail_id) {
        try {
            $query_uesr = 'SELECT CONCAT(first_name," ",last_name) AS user FROM users WHERE email="' . $mail_id . '"  ';
            $data = DB::select($query_uesr);
            $user = $data[0]->user;
            return $user;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function get_password_recover_user($mail_id) {
        try {
            $query_uesr = 'SELECT id FROM users WHERE email="' . $mail_id . '"  ';
            $data = DB::select($query_uesr);
            $user = $data[0]->id;
            return $user;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function update_reset_password_key($user_id, $user_key) {
        try {
            DB::table('users')
                    ->where('id', '=', $user_id)
                    ->update(['reset_pass_key' => $user_key]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function update_user_password($user_key, $new_password) {
        try {
            $query_check_reset_pass_key = 'SELECT COUNT(id) AS reset_pass_no FROM users WHERE reset_pass_key="' . $user_key . '"  ';
            $data_check_reset_pass_key = DB::select($query_check_reset_pass_key);
            $reset_pass_no = $data_check_reset_pass_key[0]->reset_pass_no;

            if ($reset_pass_no > 0) {
                DB::table('users')
                        ->where('reset_pass_key', '=', $user_key)
                        ->update(['password' => md5($new_password), 'reset_pass_key' => ""]);
            }

            return $reset_pass_no;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function check_user_email($mail_id) {
        try {
            $query_uesr = 'SELECT COUNT(id) AS total_mail_no FROM users WHERE username="' . $mail_id . '"  ';
            $data = DB::select($query_uesr);
            $total_mail_no = $data[0]->total_mail_no;
            return $total_mail_no;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    public function owner_landscaper_rating(){
        
        $data= [];
       
        $qry  =   Landscaper::select('id')->where("user_id", "=", session("user_id"))->pluck('id');
        $a = $qry->toArray();
        $landscaper_id = implode(',', $a);
        if(!empty ($landscaper_id )){
        $qry1 = DB::select("SELECT avg(rating_value) as owner_landscaper_rating FROM `service_ratings` WHERE landscaper_id in(".$landscaper_id.")");
        $data = round($qry1[0]->owner_landscaper_rating,1);
        return $data;
        }
        else{
            return 0;
        }
    }
}
