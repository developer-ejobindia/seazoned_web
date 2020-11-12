<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use App\Http\Requests\UserRegister;
use App\Service;
use App\UserDetail;
use Illuminate\Support\Facades\DB;
use App\Properties;
use App\User;
use App\save_properties;
use App\share_properties;
use App\AddressBook;
use App\Landscaper;
use App\BookService;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Mail;
use Validator;
use Redirect;
use Session;
use App\Country;
use App\TestModel;


class CardController extends Controller
{    

    public function editLandscaperRating(Request $request, $id) {
       
        
        $rating_exists = DB::select("select count(*) as rating_count from service_ratings where initiated_by = " . $id . " AND landscaper_id = " . $request->landscaper_id . " AND customer_id = " . $request->user_id . ";");
        $rating_exists = $rating_exists[0]->rating_count;
        if ($rating_exists == 0)
            DB::insert("INSERT INTO service_ratings VALUES(NULL," . $request->landscaper_id . "," . $request->user_id . "," . session('user_id') . "," . $request->rating . ",'" . $request->review . "','" . date("Y-m-d h:i:s") . "')");
        else
            DB::insert("UPDATE service_ratings SET rating_value=" . $request->rating . ",review='" . $request->review . "',log_time='" . date("Y-m-d h:i:s") . "' WHERE initiated_by = " . session('user_id') . " AND landscaper_id = " . $request->landscaper_id . " AND customer_id = " . $request->user_id . "");
        
      
    }
    
    public function transactionHistory($user_id) {
        
           $data['info'] = DB::select("SELECT first_name,last_name,profile_image,book_services.order_no,book_services.landscaper_payment,book_services.status FROM `user_details` INNER JOIN book_services ON user_details.user_id = book_services.customer_id WHERE book_services.status IN (2,3)");
           for($i=0;$i<count($data['info']);$i++)
         {
             if ($data['info'][$i]->status == 2)
             {
                 $data['info'][$i]->payment_status = 'Processing';
                 $data['info'][$i]->payment_date = "April 03, 17:50";
             }
              else if ($data['info'][$i]->status == 3)
              {
               $data['info'][$i]->payment_status= 'Success';
               $data['info'][$i]->payment_date = "April 03, 17:50";
              }     
         }    
     }
    
    
    
    public function getLandIds(){
        $select = DB::select("select id from landscapers where user_id in (20,21,23,24,25,27,28,29,33,34,35,36,37,38,41,42,43,44,45,46,49,50,51,53,56,58,59,60,61,62,63,65,66,67,68,73,74,76,77,78,79,83,84,85,86,87,88,89,90)");
        
        foreach($select as $obj){
            $id[] = $obj->id;
        }
        
        echo $id = implode(',',$id);
        echo "<br>";
        
        $select2 = DB::select("SELECT * FROM `address_books` WHERE `user_id` in (20,21,23,24,25,27,28,29,33,34,35,36,37,38,41,42,43,44,45,46,49,50,51,53,56,58,59,60,61,62,63,65,66,67,68,73,74,76,77,78,79,83,84,85,86,87,88,89,90)");
        
        foreach($select2 as $obj2){
            $id2[] = $obj2->id;
        }
        
        echo $id2 = implode(',',$id2);
        echo "<br>";
        $id1 = [];
        $select1 = DB::select("select id from book_services where landscaper_id in (".$id.")");
        
        foreach($select1 as $obj1){
            $id1[] = $obj1->id;
        }
        
        echo implode(',',$id1);
    }
    
    
    
    
}
