<?php

namespace App;
use DB;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_name', 'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //protected $hidden = [
        //'password', 'remember_token',
    //];

    public function ServiceDetails()
    {
     try {
            $select = 'SELECT * FROM services';
            $data = DB::select($select);
            return $data;
         } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function GetServices($id)
    {
     try {
            $select = 'SELECT * FROM services WHERE id='.$id.'';
            $data = DB::select($select);
            return $data;
         } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
