<?php

namespace App;

use DB;
//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ServicePrice extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_frequency', 'service_price',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //protected $hidden = [
    //'password', 'remember_token',
    //];

    public function ServicePriceDetails() {
        try {
            $select = 'SELECT sp.*,l.name,s.service_name FROM service_prices sp, landscapers l, services s WHERE sp.landscaper_id=l.id AND sp.service_id=s.id ORDER BY sp.service_id';
            $data = DB::select($select);
            return $data;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function GetServicePrice($id) {
        try {
            $select = 'SELECT * FROM service_prices WHERE id=' . $id . '';
            $data = DB::select($select);
            return $data;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function validatecard($number) {
        global $type;

        $cardtype = array(
            "visa" => "/^4[0-9]{12}(?:[0-9]{3})?$/",
            "mastercard" => "/^5[1-5][0-9]{14}$/",
            "amex" => "/^3[47][0-9]{13}$/",
            "discover" => "/^6(?:011|5[0-9]{2})[0-9]{12}$/",
        );
        
        $type = "UNKNOWN";

        if (preg_match($cardtype['visa'], $number)) {
            $type = "VISA";
        } else if (preg_match($cardtype['mastercard'], $number)) {
            $type = "MASTERCARD";
        } else if (preg_match($cardtype['amex'], $number)) {
            $type = "AMEX";
        } else if (preg_match($cardtype['discover'], $number)) {
            $type = "DISCOVER";
        }        
        
        return $type;
    }

}
