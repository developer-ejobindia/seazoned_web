<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class PaymentAccounts extends Model
{
    public $timestamps = false;
    
    public function encodeCvv($cvv_no){
        $str1 = str_shuffle(rand('0000','9999'));
        $str2 = str_shuffle(rand('0000','9999'));
        $str3 = base64_encode($str1.$cvv_no.$str2);
        
//        $this->decodeCvv($str3);
        return $str3;
    }
    
    public function decodeCvv($encoded_cvv_no){
        
        $str1 = base64_decode($encoded_cvv_no);
        $str2 = substr($str1, 4);
//        
        return substr($str2, 0,-4);
        
    }
    
    public function showLastFourDigit($card_no){
        
        $return  = substr($card_no, -4);
        $return = str_repeat('X', (strlen($card_no)-4)).$return;
        
        return $return;
        
    }
    
    public function getCardDetailsByCardNo($card_no){
        
        $data = DB::select("SELECT card_brand from payment_accounts where card_no='" . $card_no."'");
        return $data;
    }
    
    public function getLandscaperAmountOnly($service_price){
        
        $admin_percentages = DB::select("SELECT * from payment_percentages WHERE user_id=1");
        
        $admin_amount = ($service_price * $admin_percentages[0]->percentage) / 100;
        
        $ret = number_format((float)($service_price - $admin_amount), 2, '.', '');
        
        return $ret;
    }
    
    public function getPercentage() {
        try {

            $data = DB::table('payment_percentages')
                    ->where('user_id', '=', 1)
                    ->pluck('percentage');
            
            if(isset($data[0]))
                return $data[0];
            else
                return '0.00';
            
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
