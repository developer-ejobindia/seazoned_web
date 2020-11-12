<?php

namespace App;
use DB;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'phone_number', 'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //protected $hidden = [
        //'password', 'remember_token',
    //];

    public function UserDetails()
    {
     try {
        $select = 'SELECT u.id,u.user_type,u.username,u.password,u.profile_id FROM users u';
        $all_data = DB::select($select);
        return $all_data;
         } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function LandscaperDetails()
    {
     try {
        $select = 'SELECT * FROM users u,user_details ud WHERE u.profile_id=3 AND u.id=ud.user_id';
        $data = DB::select($select);  
        return $data;   
         } catch (Exception $e) {
            echo $e->getMessage();
        }   
    }

    public function GetLandscaper($id)
    {
     try {
            $select = 'SELECT u.*,ud.*,ud.profile_image as user_profile_iamge,l.*,l.profile_image as landscaper_feature_iamge FROM users u LEFT JOIN user_details ud ON(u.id=ud.user_id) LEFT JOIN landscapers l ON(u.id=l.user_id) WHERE u.id='.$id.' ';
            $data = DB::select($select);  
            return $data;   
         } catch (Exception $e) {
            echo $e->getMessage();
        }   
    }

    public function GeneralUserDetails()
    {
     try {
            $select = 'SELECT * FROM users u,user_details ud WHERE u.profile_id=2 AND u.id=ud.user_id';
            $data = DB::select($select);
            return $data;
         } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function GetGeneralUser($id)
    {
     try {
            $select = 'SELECT u.*,ud.* FROM users u LEFT JOIN user_details ud ON(u.id=ud.user_id) WHERE u.id='.$id.' ';
            $data = DB::select($select);
            return $data;
         } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getLandscaperNameByUserID($user_id){
        try {
            $select = 'SELECT name FROM landscapers WHERE user_id='.$user_id;
            $data = DB::select($select);

            if(!empty($data))
                return $data[0]->name;
            else
                return 'N/A';

         } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getLandscaperServiceNameByUserID($user_id){
        try {
            $select = 'SELECT s.service_name FROM services s,landscapers l WHERE l.service_id = s.id AND l.user_id='.$user_id;
            $data = DB::select($select);

            if(!empty($data))
                return $data[0]->service_name;
            else
                return 'N/A';

         } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
