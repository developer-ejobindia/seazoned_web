<?php

namespace App;

use DB;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function CountryDetails()
    {
        try {
            $country_arr = 'SELECT id, country_name FROM countries';
            $country_arr = DB::select($country_arr);
            return $country_arr;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
