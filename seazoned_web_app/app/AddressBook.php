<?php

namespace App;
use DB;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class AddressBook extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','address','contact_number','email_address',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //protected $hidden = [
        //'password', 'remember_token',
    //];

    public function AddressBookDetails()
    {
     try {
            $select = 'SELECT ab.*,ud.first_name,ud.last_name,c.* FROM address_books ab, user_details ud, countries c WHERE ab.user_id=ud.user_id AND ab.country=c.id AND ab.primary_address=1';
            $data = DB::select($select);
            return $data;
         } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
