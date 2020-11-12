<?php

namespace App\Http\Controllers;

use App\AddressBook;
use App\BookService;
use App\Landscaper;
use App\Service;
use App\UserDetail;
use App\PaymentAccounts;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use File;
use App\ServicePrice;

class PaymentController extends Controller {
    
    public function paymentInfo() {
        
        
        if(session("user_id")!="" && session("profile_id")==2){
            $user_id = session("user_id");
            $bookService_obj = new BookService;
            $data["services_pend"] = $bookService_obj->getServiceBooking();
            $data["notification_status"] = $bookService_obj->notificationStatus();
        }
        $data['menu'] = "user-payment-info";
        $data["user_info"] = UserDetail::where("user_id", "=", session("user_id"))->get()[0];
        $data['services'] = Service::all();
//        $data['payment_dtls']= PaymentAccounts::where("user_id", "=", session('user_id'))->get()[0];
        $data['payment_details']=DB::select("SELECT * from payment_accounts where user_id=".session('user_id'));        
        if(count($data['payment_details'])!=0)
            $data['payment_dtls']=$data['payment_details'];
        else
            $data['payment_dtls']='';
        $bookService_obj = new BookService;
       
        return view('Home.user-paymet-info', $data);
    }
    
    public function addUserPaymentDetails() {
        
    }
    public function addPaymentInfo(Request $request){
        
//         $this->encodeCvv($request->cvv_no);
        $service_price_obj = new ServicePrice;
        $profile = new PaymentAccounts();
        $profile->user_id=session("user_id");
        $profile->name = $request->card_holder_name;
        $profile->card_no = $request->card_no;
        $profile->month = $request->month;
        $profile->year = $request->year;
        $profile->cvv_no = $profile->encodeCvv($request->cvv_no);
        $profile->card_brand = $service_price_obj->validatecard($request->card_no);
        $profile->save();        
        session()->flash("msg", "Payment Details Added Successfully");
        if(isset($request->current_order_no) && $request->current_order_no!=""){
            return redirect(route("user-booking-history-payment", array('order_no' => $request->current_order_no)));
        } else {
            return redirect()->route("user-payment-info");
        }
        
    }
    
    public function savePrimaryCard(Request $request){
        
        
        if((isset($request->is_primary) && $request->is_primary!='') && (isset($request->card_p_id) && $request->card_p_id!='')){
            
            DB::table('payment_accounts')
                    ->where('user_id', session("user_id"))
                    ->update(['is_primary'=>0]);
            
            DB::table('payment_accounts')
                    ->where('id', $request->card_p_id)
                    ->update(['is_primary'=>$request->is_primary]);
        }
    }
    
    public function deletePaymentInfo($id,$order_no=null){
        $del=DB::table('payment_accounts')->where('id', $id)->delete();
        if($del){
            //return redirect()->route("user-payment-info");
            if(isset($order_no) && $order_no!=""){
                return redirect(route("user-booking-history-payment", array('order_no' => $order_no)));
            } else {
                return redirect()->route("user-payment-info");
            }
        }
    }
    
    public function paymentInfoLandscaper() {
        
        
        $data['menu'] = "landscaper-payment-info";
        $data["landscaper_info"] = DB::select("SELECT * from landscapers where user_id=".session('user_id'));
//        UserDetail::where("user_id", "=", session("user_id"))->get()[0];
        $data['payment_details']=DB::select("SELECT * from payment_accounts where user_id=".session('user_id'));
        $data['services'] = Service::all();
        if(count($data['payment_details'])!=0)
            $data['payment_dtls']=$data['payment_details'];
        else
            $data['payment_dtls']='';
        
        $bookService_obj = new BookService;
        if(session("user_id")!="" && session("profile_id")==3)
        {
            $user_id = session("user_id");
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
            $data["notification_status"] = $bookService_obj->notificationStatusLandscaper();
        }
        //print_r($data);exit;
        return view('Home.landscaper-payment-info', $data);
        
        
    }
    
    public function addLandscaperPaymentInfo(Request $request){
        $profile = new PaymentAccounts();
        $profile->user_id=session("user_id");
        $profile->account_email = $request->account_email;
        $profile->account_signature = $request->paypal_api_sign;
        $profile->account_password = $request->paypal_api_pass;
        $profile->name = $request->paypal_name;
        $profile->account_details = $request->paypal_account_id;
        $profile->save();        
        session()->flash("msg", "Payment Details Added Successfully");
        return redirect()->route("landscaper-payment-info");
       
    }
    
    public function deleteLandscaperPaymentInfo($id){
        
        $del=DB::table('payment_accounts')->where('id', $id)->delete();
        if($del){
            return redirect()->route("landscaper-payment-info");
        }
    }
    
    public function landscaperTransaction(Request $request) {
        
        
        if(session("user_id")!="" && session("profile_id")==3)
        {
            $user_id = session("user_id");
            $bookService_obj = new BookService;
            $data["services_pend"] = $bookService_obj->getServiceBooking();
            $data["requested_services"] = $bookService_obj->getRequestedServices($user_id);
            $data["notification_status"] = $bookService_obj->notificationStatusLandscaper();
            $data['menu'] = 'landscaper-payment-info';
        }
        
        
        $data["landscaper_info"] = DB::select("SELECT * from landscapers where user_id=".session('user_id'));
        $bookService_obj = new BookService;
        $data["transaction_history"] = $bookService_obj->gettransactionHistory($request);
        
//       echo "<pre>";       print_r($data);       echo '</pre>';die;
        return view('Home.landscaper-info-payment', $data);
    }
    
    
}