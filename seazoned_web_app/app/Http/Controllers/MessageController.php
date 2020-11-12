<?php
namespace App\Http\Controllers;
use \Illuminate\Http\Request;
use DB;
use Session;
use App\BookService;

class MessageController extends Controller {
    
    public function userMsg(Request $request)
    {     
         $bookService_obj = new BookService; 
        if(session("user_id")!="" && session("profile_id")==2){
            $user_id = session("user_id");            
            $data["services_pend"] = $bookService_obj->getServiceBooking();
        }
        
        if(session("user_id")!="" && session("profile_id")==3)
        {
            $user_id = session("user_id");
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
        }
        
        $user_id = session('user_id');
        if($user_id != ""){
        $sql_one = "SELECT book_services.id as book_service_id, book_services.customer_id,book_services.landscaper_id,users.id as user_landscaper_id,
                    book_services.order_no,services.service_name,services.id as service_id,services.logo_name as service_logo FROM book_services INNER JOIN landscapers ON book_services.landscaper_id=landscapers.id INNER JOIN
                    users ON landscapers.user_id=users.id INNER JOIN services ON landscapers.service_id = services.id WHERE customer_id =$user_id  ORDER BY book_services.service_date DESC , book_services.service_time DESC ";
        $data['message'] = DB::select($sql_one); 
        
        if(!empty ($data['message'])){
        Session::put('key', $data['message'][0]->order_no);
        Session::put('key1', $data['message'][0]->service_name);
        Session::put('key2', $data['message'][0]->user_landscaper_id);
        Session::put('key3', $data['message'][0]->service_id);
        
        $data['send_message']=DB::table('user_messages')->where('sender_id',$user_id)
                                                        ->orWhere('receiver_id',$user_id)
                                                       ->orderBy('id','DESC')->get();
        
        }
         return view('Message/user-msg',$data);
        }else{
             return redirect()->route("home");
        }
       
    }
    
    public function userChat(Request $request){
        
        $data['order_no'] = $request->order_no;
        $service_id = $request->service_id;
        $data['services'] =  DB::table('services')->where('id',$service_id)->first();
        $data['user_landscaper_id'] = $request->user_landscaper_id;   
        
//        $user_id = session('user_id');
//         $data['send_message']=DB::table('user_messages')->where('sender_id',$user_id)
//                                                        ->orWhere('receiver_id',$user_id)  
//                                                       ->orderBy('id','DESC')->get();
               
//         DB::table('user_messages')
//            ->where('msg_id', $request->order_no)
//            ->where('sender_id' ,'!=',$request->session()->get('user_id'))     
//            ->update(['status' => 1]);
//        print_r($data);
        return view('Message/user-chat',$data);
    }
    
     public function userAddMessage(Request $request){
      
        $data['order_no'] =  $request->order_no;
        $data['service_name'] = $request->service_name;
        $data['user_landscaper_id'] = $request->user_landscaper_id;
        $data['message'] = $request->message;
        $service_id = $request->service_id;
      

        DB::table("user_messages")->insert(['sender_id'        =>  $request->session()->get('user_id'),
                                            'receiver_id'      =>  $request->user_landscaper_id,
                                            'msg_id'           =>  $request->order_no,
                                            'subject'          =>  $request->service_name,
                                            'description'      =>  $request->message                                    
            ]);
//        $user_id = session('user_id');
//        $data['send_message']=DB::table('user_messages')->where('sender_id',$user_id)
//                                                        ->orWhere('receiver_id',$user_id)
//                                                       ->orderBy('id','DESC')->get();
         return view('Message/user-chat',$data);
    }
    
      public function users_activity_check(Request $request){
       
       $user_id = session('user_id');
        $data['send_message']=DB::table('user_messages')->where('sender_id',$user_id)
                                                        ->orWhere('receiver_id',$user_id)
                                                       ->orderBy('id','DESC')->get();
       return view('Message/user-convo',$data);
   }
   
   

   public function users_check_new_messages(Request $request){
       
       $user_id = session('user_id');
        $new_message=DB::table('user_messages')->where('receiver_id',$user_id)
                                                        ->where('status',"0")
                                                       ->orderBy('id','DESC')->get();
//                                       print_r($new_message);
       return $new_message;
   }
    
    public function landscaperMsg(Request $request)
    {    
        
        $bookService_obj = new BookService; 
        if(session("user_id")!="" && session("profile_id")==2){
            $user_id = session("user_id");            
            $data["services_pend"] = $bookService_obj->getServiceBooking();
        }
        
        if(session("user_id")!="" && session("profile_id")==3)
        {
            $user_id = session("user_id");
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
        }
        
        if(session("user_id") != ''){
        
        $data['message'] = DB::select("SELECT bs.id as book_id,bs.order_no,l.id,l.service_id,l.name,bs.customer_id,services.service_name,services.logo_name as service_logo "
                           . "FROM landscapers l INNER JOIN book_services bs ON l.id=bs.landscaper_id INNER JOIN services ON services.id = l.service_id "
                           . "WHERE l.user_id =".$request->session()->get('user_id')." ORDER BY bs.service_date DESC , bs.service_time DESC "); 
        
//        $data['description']=DB::table('user_messages')->where('sender_id',$request->session()->get('user_id'))
//                                                       ->where('msg_id',$order_no)
//                                                       ->orderBy('id','ASC')->get();
//        $data['receive_msg'] =    DB::table('user_messages')->where('receiver_id',$request->session()->get('user_id'))
//                                                            ->where('msg_id',$order_no)
//                                                            ->orderBy('id','ASC')
//                                                            ->get();
        if(!empty ($data['message'])){
            
        Session::put('key',  $data['message'][0]->order_no);
        Session::put('key1', $data['message'][0]->service_name);
        Session::put('key2', $data['message'][0]->customer_id);
        Session::put('key3', $data['message'][0]->service_id);
        $data['description']=DB::table('user_messages')->where('sender_id',$request->session()->get('user_id'))
                                                        ->orWhere('receiver_id',$request->session()->get('user_id'))
                                                       ->orderBy('id','DESC')->get();
            
        }
        
         return view('Message/landscaper-msg',$data);
        }else{
             return redirect()->route("home");
        }
       
    }
    
    public function landscaperChat(Request $request){
    
        $data['order_no'] = $request->order_no;
        $service_id = $request->service_id;
        $data['services'] =  DB::table('services')->where('id',$service_id)->first();
//        $data['description']=DB::table('user_messages')->where('sender_id',$request->session()->get('user_id'))
//                                                       ->where('msg_id',$request->order_no)
//                                                       ->orderBy('id','ASC')->get();
        $data['customer_id'] = $request->customer_id;
//        $data['receive_msg'] =    DB::table('user_messages')->where('receiver_id',$request->session()->get('user_id'))
//                                                            ->where('msg_id',$request->order_no)
//                                                            ->get();
//        DB::table('user_messages')
//            ->where('msg_id', $request->order_no)
//            ->where('sender_id' ,'!=',$request->session()->get('user_id'))
//            ->update(['status' => 1]);

        return view('Message/landscaper-chat',$data);
    }
    
     public function landscaperAddMessage(Request $request){
      
//        $data['order_no'] =  $request->order_no;
//        $data['service_name'] = $request->service_name;
//        $data['customer_id'] = $request->customer_id;
//        $data['message'] = $request->message;
//        $service_id = $request->service_id;
        DB::table("user_messages")->insert(['sender_id'        =>  $request->session()->get('user_id'),
                                            'receiver_id'      =>  $request->customer_id,
                                            'msg_id'           =>  $request->order_no,
                                            'subject'          =>  $request->service_name,
                                            'description'      =>  $request->message  
                                        ]);
    }
    
    public function landscapers_activity_check(Request $request){
        $data['send_message']=DB::table('user_messages')->where('sender_id',$request->session()->get('user_id'))
                                                        ->orWhere('receiver_id',$request->session()->get('user_id'))
                                                       ->orderBy('id','DESC')->get();
       return view('Message/landscaper-convo',$data);
   }
  
   public function landscaperMsgFirebase(Request $request)
    {    
        $data['empty_flag'] = 0;
        $bookService_obj = new BookService; 
        if(session("user_id")!="" && session("profile_id")==2){
            $user_id = session("user_id");            
            $data["services_pend"] = $bookService_obj->getServiceBooking();
        }
        
        if(session("user_id")!="" && session("profile_id")==3)
        {
            $user_id = session("user_id");
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
        }
        
        if(session("user_id") != ''){
        
        $data['message'] = DB::select("SELECT bs.id as book_id,bs.order_no,l.id,l.service_id,l.name,bs.customer_id,services.service_name,services.logo_name as service_logo "
                           . "FROM landscapers l INNER JOIN book_services bs ON l.id=bs.landscaper_id INNER JOIN services ON services.id = l.service_id "
                           . "WHERE l.user_id =".$request->session()->get('user_id')." ORDER BY bs.service_date DESC , bs.service_time DESC "); 
        
//        $data['description']=DB::table('user_messages')->where('sender_id',$request->session()->get('user_id'))
//                                                       ->where('msg_id',$order_no)
//                                                       ->orderBy('id','ASC')->get();
//        $data['receive_msg'] =    DB::table('user_messages')->where('receiver_id',$request->session()->get('user_id'))
//                                                            ->where('msg_id',$order_no)
//                                                            ->orderBy('id','ASC')
//                                                            ->get();
        if(!empty ($data['message'])){
            
        Session::put('key',  $data['message'][0]->order_no);
        Session::put('key1', $data['message'][0]->service_name);
        Session::put('key2', $data['message'][0]->customer_id);
        Session::put('key3', $data['message'][0]->service_id);
        $data['description']=DB::table('user_messages')->where('sender_id',$request->session()->get('user_id'))
                                                        ->orWhere('receiver_id',$request->session()->get('user_id'))
                                                       ->orderBy('id','DESC')->get();
            
        }
        else {
            $data['empty_flag'] = 1;
        }
        
         return view('Message/landscaper-msg-firebase',$data);
        }else{
             return redirect()->route("home");
        }
       
    }
    
    public function landscaperChatFirebase(Request $request)
    {
    
        $data['order_no'] = $request->order_no;
        $service_id = $request->service_id;
        $data['services'] =  DB::table('services')->where('id',$service_id)->first();
        $data['customer_id'] = $request->customer_id;
        
        Session::forget('key');
        Session::forget('key2');
        Session::put('key', $request->order_no);
        Session::put('key2', $request->customer_id);
        
        return view('Message/landscaper-chat-firebase',$data);
    }
    
    public function userMsgFirebase(Request $request)
    {
        $data['empty_flag'] = 0;
         $bookService_obj = new BookService; 
        if(session("user_id")!="" && session("profile_id")==2){
            $user_id = session("user_id");            
            $data["services_pend"] = $bookService_obj->getServiceBooking();
        }
        
        if(session("user_id")!="" && session("profile_id")==3)
        {
            $user_id = session("user_id");
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
        }
        
        $user_id = session('user_id');
        if($user_id != ""){
        $sql_one = "SELECT book_services.id as book_service_id, book_services.customer_id,book_services.landscaper_id,users.id as user_landscaper_id,
                    book_services.order_no,services.service_name,services.id as service_id,services.logo_name as service_logo FROM book_services INNER JOIN landscapers ON book_services.landscaper_id=landscapers.id INNER JOIN
                    users ON landscapers.user_id=users.id INNER JOIN services ON landscapers.service_id = services.id WHERE customer_id =$user_id  ORDER BY book_services.service_date DESC , book_services.service_time DESC ";
        $data['message'] = DB::select($sql_one); 
        
        if(!empty ($data['message'])){
        Session::put('key', $data['message'][0]->order_no);
        Session::put('key1', $data['message'][0]->service_name);
        Session::put('key2', $data['message'][0]->user_landscaper_id);
        Session::put('key3', $data['message'][0]->service_id);
        
        $data['send_message']=DB::table('user_messages')->where('sender_id',$user_id)
                                                        ->orWhere('receiver_id',$user_id)
                                                       ->orderBy('id','DESC')->get();
        
        } else {
            $data['empty_flag'] = 1;
        }
        //print_r($data);exit;
        
         return view('Message/user-msg-firebase',$data);
        }else{
             return redirect()->route("home");
        }
       
    }
    
    public function userChatFirebase(Request $request){
        
        $data['order_no'] = $request->order_no;
        $service_id = $request->service_id;
        $data['services'] =  DB::table('services')->where('id',$service_id)->first();
        $data['user_landscaper_id'] = $request->user_landscaper_id;
        
        Session::forget('key');
        Session::forget('key2');
        Session::put('key', $request->order_no);
        Session::put('key2', $request->user_landscaper_id);
        
        return view('Message/user-chat-firebase',$data);
    }
    
}