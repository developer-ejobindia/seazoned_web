<?php

namespace App;
use DB;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class BookService extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_date','service_time','additional_note','service_booked_price','service_price',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //protected $hidden = [
        //'password', 'remember_token',
    //];

    public $timestamps = false;
    protected $guarded = ["id"];

    public function BookingDetails()
    {
     try {
            $select = 'SELECT bs.*,l.*,ud.*,s.* FROM book_services bs LEFT JOIN landscapers l ON(bs.landscaper_id=l.id) LEFT JOIN user_details ud ON(bs.customer_id=ud.user_id) LEFT JOIN services s ON(l.service_id=s.id)';
            $data = DB::select($select);
            return $data;
         } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function PaymentDetails()
    {
     try {
            $select = 'SELECT bs.*,l.*,ud.*,s.* FROM book_services bs LEFT JOIN landscapers l ON(bs.landscaper_id=l.id) LEFT JOIN user_details ud ON(bs.customer_id=ud.user_id) LEFT JOIN services s ON(l.service_id=s.id)';
            $data = DB::select($select);
            return $data;
         } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    public function getServiceRequest($request = array())
    {
        $service_data = array();
        $all_data = array();
        
        $sql = "SELECT l.*, bs.customer_id FROM landscapers l INNER JOIN book_services bs ON l.id=bs.landscaper_id WHERE bs.status=0 AND user_id = ".$request->session()->get('user_id');
        $land_data = DB::select($sql);
        
        foreach($land_data as $index => $land_row){
            $sql_one = "SELECT * FROM book_services WHERE status=0 AND landscaper_id = ".$land_row->id." AND customer_id =$land_row->customer_id";
            $db_one = DB::select($sql_one);
            if(!empty($db_one)){
                $all_data[$index]['book_service'] = $db_one[0];
            
                $sql_two = "SELECT * FROM address_books WHERE id = ".$all_data[$index]['book_service']->address_book_id;
                $db_two = DB::select($sql_two);
                if(!empty($db_two))
                 $all_data[$index]['book_address'] = $db_two[0];

                $sql_three = "SELECT service_name FROM services WHERE id = ".$land_row->service_id;
                $db_three = DB::select($sql_three);
                if(!empty($db_three))
                    $all_data[$index]['name'] = $db_three[0];
                
                $sql_four = "SELECT profile_image FROM user_details WHERE user_id = ".$land_row->customer_id;
                $db_four = DB::select($sql_four);

                $all_data[$index]['image'] = $db_four[0];
            }
        }
        return $all_data;
    }
    
    public function getSeviceDetails($book_service_id = ""){
        
            $sql = "SELECT * FROM book_services WHERE id = ".$book_service_id;
            $book_service = DB::select($sql)[0];
            $service_booked_price = $book_service->service_booked_price;

            $sql2 = "SELECT * FROM address_books WHERE id = ".$book_service->address_book_id;
            $address_book = DB::select($sql2)[0];

            $qry1 = "SELECT * FROM service_prices WHERE id = ".$book_service->service_price_id;
            $service_prices = DB::select($qry1)[0];
            
            $sql3 = "SELECT * FROM  landscapers WHERE id = ".$book_service->landscaper_id;
            $landscaper = DB::select($sql3)[0];
            
            $sql4 = "SELECT service_name FROM services WHERE id = "
                    . "(SELECT service_id FROM landscapers WHERE id = $book_service->landscaper_id)";
            $service_name = DB::select($sql4)[0];
            
            $sql5 = "SELECT profile_image FROM user_details WHERE user_id =$book_service->customer_id ";
            $customer_image = DB::select($sql5)[0];
            
            $service_rating=DB::select("SELECT rating_value,review,log_time FROM  service_ratings WHERE customer_id = ".$book_service->customer_id." AND landscaper_id = ".$book_service->landscaper_id." AND initiated_by!=".session('user_id').";");
            if(!empty($service_rating))
            $service_rating=$service_rating[0];

            $service_images=DB::select("SELECT service_image FROM service_images WHERE book_service_id = ".$book_service_id." AND uploaded_by=".$book_service->customer_id.";");
            
            $data = array(
                'book_service' => $book_service,
                'address_book' => $address_book,
                'landscaper' => $landscaper,
                'service_name' => $service_name,
                'service_rating' => $service_rating,
                'service_images' => $service_images,
                'service_prices' => $service_prices,
                'service_booked_price'=>$service_booked_price,
                'customer_image' => $customer_image
            );
            return $data;     
    }
    
    public function getLandscaperImage()
    {
        $sql5 = "SELECT profile_image FROM user_details WHERE user_id = '".session("user_id")."'";
        $profile_image = DB::select($sql5)[0];
        return $profile_image;
    }
    
    public function selectLandscaperImage($order_no="")
    {
        $user_id = session("user_id");
        
        $select1 = 'SELECT l.user_id FROM landscapers l INNER JOIN book_services bs ON l.id=bs.landscaper_id WHERE bs.customer_id="'.$user_id.'" AND bs.order_no="'.$order_no.'"';
        $data1 = DB::select($select1);
       
        $select = 'SELECT profile_image FROM user_details WHERE user_id="'.$data1[0]->user_id.'"';
        $data = DB::select($select)[0];
        
        return $data;
    }
    
    public function updateSeviceDetails($book_service_id = "",$status = ""){
//        date_default_timezone_set(session('current_timezone'));
        $required_time = date("Y-m-d H:i:s");
        
        if($status == -1){
        $sql = "UPDATE book_services SET status = $status , notification_status_user=1,notification_status_landscaper=1,reject_time = '$required_time'  WHERE id = $book_service_id";     
        $rows = DB::update($sql);
        return $rows;
        }else{
        $sql = "UPDATE book_services SET status = $status , notification_status_user=1,notification_status_landscaper=1,accept_time = '$required_time'  WHERE id = $book_service_id";     
        $rows = DB::update($sql);
        return $rows;
        }
    }
    public function notificationStatus(){
        $sql = "SELECT count(id) as count FROM book_services WHERE customer_id = ".session("user_id"). " AND notification_status_user = 1";     
        $rows = DB::select($sql);
        return $rows;
    }
    public function notificationStatusLandscaper(){
        $sql = "SELECT count(bs.id) as count FROM landscapers l INNER JOIN book_services bs ON l.id=bs.landscaper_id WHERE l.user_id = ".session("user_id")." AND bs.notification_status_landscaper = 1";     
        $rows = DB::select($sql);
        return $rows;
    }
    
    public function getServicePending($request = array())
    {
        $service_data = array();
        $all_data = array();
        
        $sql = "SELECT bs.id as bs_id,l.id,l.service_id,l.name,bs.customer_id FROM landscapers l INNER JOIN book_services bs ON l.id=bs.landscaper_id WHERE  bs.status IN(1,2) AND l.user_id = ".$request->session()->get('user_id');
        $land_data = DB::select($sql);
        //print_r($land_data);
        //exit;
        foreach($land_data as $index => $land_row){
            $sql_one = "SELECT * FROM book_services WHERE id = ".$land_row->bs_id;
            $db_one = DB::select($sql_one);
            
            
            if(!empty($db_one)){
                $all_data[$index]['book_service'] = $db_one[0];
                 
                $sql_two = "SELECT * FROM address_books WHERE id = ".$all_data[$index]['book_service']->address_book_id;
                $db_two = DB::select($sql_two);
                
                if(!empty($db_two))
                 $all_data[$index]['book_address'] = $db_two[0];

                $sql_three = "SELECT service_name FROM services WHERE id = ".$land_row->service_id;
                $db_three = DB::select($sql_three);
                
                if(!empty($db_three))
                    $all_data[$index]['name'] = $db_three[0];
                
                $all_data[$index]['landscaper_name'] = $land_row->name;
            }
            
            $sql_three = "SELECT profile_image FROM user_details WHERE user_id = ".$land_row->customer_id;
            $db_three = DB::select($sql_three);
            
            $all_data[$index]['image'] = $db_three[0];
        }
        return $all_data;
    }
    
    public function getTotalRevenue($request = array())
    {
        $sql = "SELECT SUM(landscaper_payment) AS total_revenue FROM book_services bs INNER JOIN landscapers l ON bs.landscaper_id=l.id WHERE l.user_id = ".$request->session()->get('user_id');
        $land_data = DB::select($sql);
        return $land_data;
    }
    
     public function getbookingHistory($request = array())
    {
        $service_data = array();
        $all_data = array();
        
        $sql = "SELECT bs.id as book_id,l.id,l.service_id,l.name,bs.customer_id FROM landscapers l INNER JOIN book_services bs ON l.id=bs.landscaper_id WHERE l.user_id = ".$request->session()->get('user_id')." ORDER BY bs.id DESC";
        $land_data = DB::select($sql);
        foreach($land_data as $index => $land_row){
            $sql_one = "SELECT * FROM book_services WHERE id=".$land_row->book_id." AND landscaper_id = ".$land_row->id;
            $db_one = DB::select($sql_one);
            
            foreach ($db_one as $key => $value) {
                if(!empty($db_one)){
                    $all_data[$index]['book_service'] = $db_one[$key];

                    $sql_two = "SELECT * FROM address_books WHERE id = ".$all_data[$index]['book_service']->address_book_id;
                    $db_two = DB::select($sql_two);

                    if(!empty($db_two))
                     $all_data[$index]['book_address'] = $db_two[0];

                    $sql_three = "SELECT service_name FROM services WHERE id = ".$land_row->service_id;
                    $db_three = DB::select($sql_three);

                    if(!empty($db_three))
                        $all_data[$index]['name'] = $db_three[0];

                    $all_data[$index]['landscaper_name'] = $land_row->name;
                }
            }
            
            $sql_three = "SELECT profile_image FROM user_details WHERE user_id = ".$land_row->customer_id;
            $db_three = DB::select($sql_three);
            
            $all_data[$index]['image'] = $db_three[0];
        }
        return $all_data;
    }
    
    public function gettransactionHistory($request = array())
    {
        $service_data = array();
        $all_data = array();
        
        $sql = "SELECT bs.id as book_id,l.id,l.service_id,l.name,bs.customer_id FROM landscapers l INNER JOIN book_services bs ON l.id=bs.landscaper_id WHERE bs.status IN (2,3) AND l.user_id = ".$request->session()->get('user_id');
        $land_data = DB::select($sql);
        
        foreach($land_data as $index => $land_row){
            $sql_one = "SELECT * FROM book_services WHERE id=".$land_row->book_id." AND landscaper_id = ".$land_row->id;
            $db_one = DB::select($sql_one);
            
            foreach ($db_one as $key => $value) {
                if(!empty($db_one)){
                    $all_data[$index]['book_service'] = $db_one[$key];

                    $sql_two = "SELECT * FROM address_books WHERE id = ".$all_data[$index]['book_service']->address_book_id;
                    $db_two = DB::select($sql_two);

                    if(!empty($db_two))
                     $all_data[$index]['book_address'] = $db_two[0];

                    $sql_three = "SELECT service_name FROM services WHERE id = ".$land_row->service_id;
                    $db_three = DB::select($sql_three);

                    if(!empty($db_three))
                        $all_data[$index]['name'] = $db_three[0];

                    $all_data[$index]['landscaper_name'] = $land_row->name;
                }
            }
            
            $sql_three = "SELECT profile_image FROM user_details WHERE user_id = ".$land_row->customer_id;
            $db_three = DB::select($sql_three);
            
            $all_data[$index]['image'] = $db_three[0];
        }
        
        return $all_data;
    }
    
    public function getServiceBooking()
    { 
        $all_data = array();
        $user_id = session('user_id');
        $sql_one = "SELECT * FROM book_services WHERE customer_id = ".$user_id." ORDER BY notification_check_time DESC";
        $db_one = DB::select($sql_one);        
        
        if(!empty($db_one)){
            foreach($db_one as $k => $v){
                $all_data[$k]['book_service'] = $v;
                if($v->status == 0){
                $all_data[$k]['show_time'] = $v->booking_time;
                }else if($v->status == 1){
                $all_data[$k]['show_time'] = $v->accept_time;
                }else if($v->status == 2){
                $all_data[$k]['show_time'] = $v->completion_date;    
                }else if($v->status == 3){
                $all_data[$k]['show_time'] = $v->payment_date;   
                }else if($v->status == -1){
                $all_data[$k]['show_time'] = $v->reject_time;    
                }
                $sql_two = "SELECT * FROM address_books WHERE id = ".$v->address_book_id;
                $db_two = DB::select($sql_two);
                if(!empty($db_two)){
                    $all_data[$k]['book_address'] = $db_two[0];
                }
                $sql_4 = "SELECT service_frequency  FROM service_prices WHERE  id = ".$v->service_price_id;
                 $db_4 = DB::select($sql_4);
                 if(!empty($db_4)) {                    
                    $all_data[$k]['service_frequency'] = $db_4;  
                } 
                $sql_5 = "SELECT service_image  FROM service_images WHERE book_service_id = ".$v->id . " AND uploaded_by = ".$v->customer_id;
                $db_5 = DB::select($sql_5);
                 if(!empty($db_5)) {                                  
                    $all_data[$k]['service_image'] = $db_5;  
                } 
                
                $sql = "SELECT service_id, name FROM landscapers WHERE id = ".$v->landscaper_id;
                $ldata = DB::select($sql)[0];
                $sql_three = "SELECT service_name FROM services WHERE id = ".$ldata->service_id;
                $db_three = DB::select($sql_three);                
                if(!empty($db_three)) { 
                    $all_data[$k]['name'] = $db_three[0];
                    $all_data[$k]['landscaper_name'] = $ldata->name;  
                }
                
                $sql_6 = "SELECT profile_image FROM user_details WHERE user_id=(SELECT user_id FROM landscapers l INNER JOIN book_services bs ON l.id=bs.landscaper_id WHERE bs.id='".$v->id."' AND bs.landscaper_id='".$v->landscaper_id."')";
                $db_6= DB::select($sql_6);
                if(!empty($sql_6)) {                                  
                    $all_data[$k]['profile_image'] = $db_6[0];  
                }
                   
            }
        }  
    
        
        
        
        return $all_data;
    }
    
    public function notification_list_user_get($user_id="")
    { 
        $all_data = array();
        $sql_one = "SELECT * FROM book_services bs WHERE customer_id = ".$user_id." ORDER BY bs.notification_check_time DESC";
        $db_one = DB::select($sql_one);  
        if(!empty($db_one)){
            foreach($db_one as $k => $v){
                if($v->status!=0){
                 $all_data[$k]['service_status'] = $v->status;
                 $all_data[$k]['landscaper_id'] = $v->landscaper_id;
                 $all_data[$k]['order_id'] = $v->id;
                 
//                if ($v->status == 0){
////                    $all_data[$k]['status'] = "Service Request Sent";
////                    $all_data[$k]['timestamp'] = $v->booking_time;
//                }
                if ($v->status == 1){
                    $all_data[$k]['status'] = "Service Request has been Accepted";
                    $all_data[$k]['timestamp'] = $v->accept_time;
                }
                elseif ($v->status == 2){
                    $all_data[$k]['status'] = "Payment Done";
                    $all_data[$k]['timestamp'] = $v->payment_date;
                }
               elseif ($v->status == 3){
                   $all_data[$k]['status'] = "Job Complete";
                   $all_data[$k]['timestamp'] = $v->completion_date;
               }
                elseif ($v->status == -1){
                    $all_data[$k]['status'] = "We are sorry to inform you that the provider is unable to fulfill your request at this time. Please search for another provider";
                    $all_data[$k]['timestamp'] = $v->reject_time;
                }
                $all_data[$k]['order_no'] = $v->order_no;
                $sql = "SELECT service_id, name FROM landscapers WHERE id = ".$v->landscaper_id;
                $ldata = DB::select($sql)[0];
                $sql_three = "SELECT service_name FROM services WHERE id = ".$ldata->service_id;
                $db_three = DB::select($sql_three);                
                if(!empty($db_three)) { 
                    $all_data[$k]['name'] = $db_three[0]->service_name;
                    $all_data[$k]['landscaper_name'] = $ldata->name;  
                }
                
                $sql_6 = "SELECT profile_image FROM user_details WHERE user_id=(SELECT user_id FROM landscapers l INNER JOIN book_services bs ON l.id=bs.landscaper_id WHERE bs.id='".$v->id."' AND bs.landscaper_id='".$v->landscaper_id."')";
                $db_6= DB::select($sql_6);
                if(!empty($sql_6))
                {
                    if(!empty($db_6[0]->profile_image))
                    {
                        $all_data[$k]['profile_image'] = url("uploads/profile_picture/" . $db_6[0]->profile_image);
                    }
                    else
                    {
                        $all_data[$k]['profile_image'] = "";
                    }
                } 
                
                
            }
            }
            $all_data = array_merge($all_data);
        }
        return $all_data;
    }
    
    public function notification_list_landscaper_get($user_id="")
    { 
        $all_data = array();
        $sql = "SELECT bs.id as book_id,bs.status as status,bs.order_no as order_no,bs.booking_time,bs.accept_time,bs.completion_date,bs.payment_date,bs.reject_time,l.id,l.service_id,bs.customer_id FROM landscapers l INNER JOIN book_services bs ON l.id=bs.landscaper_id WHERE l.user_id = ".$user_id." ORDER BY bs.notification_check_time DESC" ;
        $land_data = DB::select($sql);
         foreach($land_data as $index => $land_row){
              if($land_row->status!=1 && $land_row->status!=-1){  
             $all_data[$index]['service_status'] = $land_row->status;
             $all_data[$index]['landscaper_id'] = $land_row->id;
             $all_data[$index]['order_id'] = $land_row->book_id;
             
             if ($land_row->status == 0){
                    $all_data[$index]['status'] = "Service Request Received";
                    $all_data[$index]['timestamp'] = $land_row->booking_time;
             }
//                elseif ($land_row->status == 1){
//                    $all_data[$index]['status'] = "Service Request has been Accepted";
//                    $all_data[$index]['timestamp'] = $land_row->accept_time;
//             }
               elseif ($land_row->status == 2){
                   $all_data[$index]['status'] = "Payment Done";
                   $all_data[$index]['timestamp'] = $land_row->payment_date;
            }        
                elseif ($land_row->status == 3){
                    $all_data[$index]['status'] = "Job Complete";
                    $all_data[$index]['timestamp'] = $land_row->completion_date;
             }
//                elseif ($land_row->status == -1){
//                    $all_data[$index]['status'] = "We are sorry to inform you that the provider is unable to fulfill your request at this time. Please search for another provider";
//                    $all_data[$index]['timestamp'] = $land_row->reject_time;
//                }   
                 $all_data[$index]['order_no'] = $land_row->order_no;
                
                $sql = "SELECT service_id, name FROM landscapers WHERE id = ".$land_row->id;
                $ldata = DB::select($sql)[0];
                $sql_three = "SELECT service_name FROM services WHERE id = ".$ldata->service_id;
                $db_three = DB::select($sql_three);                
                if(!empty($db_three)) { 
                    $all_data[$index]['service_name'] = $db_three[0]->service_name;
                }
                
                $sql_6 = "SELECT profile_image,first_name,last_name FROM user_details WHERE user_id=".$land_row->customer_id;
                $db_6= DB::select($sql_6);
                $all_data[$index]['customer_name'] =$db_6[0]->first_name.' '.$db_6[0]->last_name ;
                if(!empty($sql_6))
                {
                    if(!empty($db_6[0]->profile_image))
                    {
                        $all_data[$index]['profile_image'] = url("uploads/profile_picture/" . $db_6[0]->profile_image);
                        }
                    else
                    {
                        $all_data[$index]['profile_image'] = "";
                    }
                } 
            }
         }
         $all_data = array_merge($all_data);  

        return $all_data;
    }

    public function getBookingDetails($order_no=null)
    {
//        try {
//            $select = 'SELECT bs.*,sp.*,s.service_name,l.name,l.profile_image,ab.name,ab.address,ab.city,ab.state,ab.country,ab.contact_number,ab.email_address,si.service_image FROM book_services bs,service_prices sp,services s,landscapers l,address_books ab,service_images si WHERE bs.service_price_id=sp.id AND sp.service_id=s.id AND bs.landscaper_id=l.id AND bs.address_book_id=ab.id AND bs.id=si.book_service_id AND bs.order_no="'.$order_no.'"';
//            $data = DB::select($select);
//            return $data;
//         } catch (Exception $e) {
//            echo $e->getMessage();
//        }      
        
        try {
            $select = 'SELECT bs.*,sp.*,s.service_name,l.name as provider_name,l.profile_image,ab.name,ab.address,ab.city,ab.state,ab.country,ab.contact_number,ab.email_address FROM book_services bs,service_prices sp,services s,landscapers l,address_books ab WHERE bs.service_price_id=sp.id AND sp.service_id=s.id AND bs.landscaper_id=l.id AND bs.address_book_id=ab.id AND bs.order_no="'.$order_no.'"';
            $data = DB::select($select);
            return $data;
         } catch (Exception $e) {
            echo $e->getMessage();
        } 
    }
    
    public function getUserServiceImages($order_no=null)
    {     
        $user_id = session("user_id");
        try {
            $select = 'SELECT si.service_image FROM book_services bs,service_images si WHERE  bs.id = si.book_service_id AND si.uploaded_by ="'.$user_id.'" AND bs.order_no="'.$order_no.'"';
            $data = DB::select($select);
            return $data;
         } catch (Exception $e) {
            echo $e->getMessage();
        } 
    }
    
    public function getLandscaperServiceImages($order_no=null)
    {     
        $user_id = session("user_id");
        try {
            $select1 = 'SELECT l.user_id FROM landscapers l INNER JOIN book_services bs ON l.id=bs.landscaper_id WHERE bs.customer_id="'.$user_id.'" AND bs.order_no="'.$order_no.'"';
            $data1 = DB::select($select1);
            
            $select = 'SELECT si.service_image FROM book_services bs,service_images si WHERE  bs.id = si.book_service_id AND si.uploaded_by ="'.$data1[0]->user_id.'" AND bs.order_no="'.$order_no.'"';
            $data = DB::select($select);
            
            return $data;
         } 
         catch (Exception $e) {
            echo $e->getMessage();
        } 
    }

    public function getRatingDetails($order_no=null)
    {
        try {
            $landscaper_data = 'SELECT landscaper_id FROM book_services WHERE order_no="'.$order_no.'"';
            $landscaper_data = DB::select($landscaper_data);

            $select = 'SELECT sr.*,l.name FROM service_ratings sr,landscapers l WHERE sr.landscaper_id=l.id AND sr.landscaper_id='.$landscaper_data[0]->landscaper_id.' AND sr.customer_id='.session('user_id').' AND sr.initiated_by!='.session('user_id').'';
            $data = DB::select($select);
            return $data;
         } catch (Exception $e) {
            echo $e->getMessage();
        }         
    }
    
    public function getRequestedServices($user_id)
    {            
            $sql = "SELECT bs.id, bs.customer_id,bs.accept_time,bs.reject_time,bs.booking_time,bs.completion_date,bs.payment_date,ud.first_name, ud.last_name, ud.profile_image, bs.status,bs.order_no,s.service_name "
                . " FROM book_services bs,landscapers l,user_details ud,services s "
                . " WHERE bs.landscaper_id = l.id AND ud.user_id=bs.customer_id AND l.service_id=s.id "
                . " AND l.user_id = ".$user_id ." ORDER BY bs.notification_check_time DESC";
            
            $result = DB::select($sql);
            
            foreach($result as $k=>$row){
                
                if ($row->status == 0){
                    $result[$k]->status_name = "<span class='text-info'>Service Request Sent</span>";
                    $result[$k]->show_time = $row->booking_time;
                }
                elseif ($row->status == 1){
                    $result[$k]->status_name = "<span class='text-warning'>Work In Progress</span>";
                    $result[$k]->show_time =   $row->accept_time;
                }
                elseif ($row->status == 2){
                    $result[$k]->status_name = "<span class='text-danger'>Payment Pending</span>";
                    $result[$k]->show_time =   $row->completion_date;
                }
                elseif ($row->status == 3){
                    $result[$k]->status_name = "<span class='text-success'>Payment Done</span>";
                    $result[$k]->show_time =   $row->payment_date;
                }
                elseif ($row->status == -1){
                    $result[$k]->status_name = "<span class='text-danger'>Service Request Rejected</span>";
                    $result[$k]->show_time =   $row->reject_time;
                }
            }
            return $result;   
            
    }
    
     public function getPercentage()
    {
     try {
            $admin_percentages = DB::select("SELECT * from payment_percentages");
            $admin_percentage =  $admin_percentages[0]->percentage;
            return $admin_percentage;
         } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
