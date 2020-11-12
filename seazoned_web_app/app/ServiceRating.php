<?php

namespace App;
use DB;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ServiceRating extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rating_value','review',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //protected $hidden = [
        //'password', 'remember_token',
    //];

    public function ServiceRatingDetails()
    {
     try {
            $select = 'SELECT sr.*,l.*,ud.* FROM service_ratings sr, landscapers l, user_details ud WHERE sr.landscaper_id=l.id AND sr.customer_id=ud.user_id';
            $data = DB::select($select);
            return $data;
         } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
