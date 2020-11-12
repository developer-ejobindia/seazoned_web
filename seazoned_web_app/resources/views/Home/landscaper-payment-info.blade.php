@extends("layouts.dashboardlayout")
@section('content')
        
        <section class="main-content user-profile my-profile-payment-info p-y-30">
            <div class="container">
                
             <?php
            $prof_pic = url('/') . '/default/images/avatar-landscaper.jpg';
            if (session("prof_img") !== "") {
                if (file_exists(public_path() . '/uploads/profile_picture/' . session("prof_img"))) {
                    $prof_pic = url('/public') . '/uploads/profile_picture/' . session("prof_img");
                }
            }
             if ($payment_dtls != '') { ?>
                <div class="row">
                <div class="col-md-4">
                        <div class="card card-no-radius side-menu m-b-30">
                            <div class="card-block p-t-40 p-b-30">
                                <div class="avtar">
                                    <img src="{{ $prof_pic }}" alt="">                   
                                </div>
                                <h4 class="profile-name text-center m-0 m-t-20"><?php echo isset($landscaper_info[0]->name) && $landscaper_info[0]->name != '' ?$landscaper_info[0]->name : ''?></h4>
                            </div> 
                            <ul class="list-group list-group-flush list-unstyled job-btn-group">
                                <li class=""><a href="{{ url("/landscaper/transcation-history") }}" class="list-group-item"> Transaction History</a></li>
                                <li class="active"><a href="{{ url("/landscaper/payment-info") }}" class="list-group-item">Account</a></li>
                            </ul>
                        </div>
                    </div>
                
                
            <div class="col-md-8">                           
                                            
                            <div class="col-md-12">
                                <div class="card custom-card card-no-radius m-b-30"> 
                                    <div class="card-header">
                                        <h4 class="m-0 regular">View Paypal Account</h4>                                    
                                    </div>
                                    <div class="card-block">                                        
                                       <img src="{{ asset("/default/images//PayPal-logo.png") }}" alt="" class="m-t-10 m-b-30" width="160">                                      
                                        <div class="view-paypal-account user-profile-details">
                                            <div class="row">      <?php foreach($payment_dtls as $payment){ ?>                                          
                                                <div class="col-md-6">
                                                    <p class="m-b-30"><b>Account Holder Name </b><br>{{ $payment->name}}</p>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <p class="m-b-30"><b>Account Holder Email </b><br>{{ $payment->account_email }}</p>
                                                </div>
                                                </div>
                                            <div class="row"> 
                                                <div class="col-md-12">
                                                    <p class="m-b-30"><b>Account Holder API Username </b><br>{{ $payment->account_details  }}</p>
                                                </div>
                                                <div class="col-md-12">
                                                    <p class="m-b-30"><b>Account Holder API Signature </b><br>{{ $payment->account_signature   }}</p>
                                                </div>
                                                 <div class="col-md-12">
                                                    <p class="m-b-30"><b>Account Holder API Password </b><br>{{ $payment->account_password   }}</p>
                                                </div>
                                            </div>
                                            <div class="text-center m-b-10">
                                            <a class="btn btn-info d-inline-block" href="javascript:void(0)" onclick="del_card(<?php echo $payment->id; ?>)"class="float-right medium m-l-15">Remove Account</a>
                                            <?php  } ?>
                                            </div>
                                        </div>
                                    </div>                           
                                </div> 
                            </div>
                        </div>
                    </div>
             <?php } else { ?>  
            
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-no-radius side-menu m-b-30">
                            <div class="card-block p-t-40 p-b-30">
                                <div class="avtar">
                                    <img src="{{ $prof_pic }}">                   
                                </div>
                                <h4 class="profile-name text-center m-0 m-t-20"><?php echo isset($landscaper_info[0]->name) && $landscaper_info[0]->name != '' ?$landscaper_info[0]->name : ''?></h4>
                            </div> 
                            <ul class="list-group list-group-flush list-unstyled job-btn-group">
                                <li class=""><a href="{{ url("/landscaper/transcation-history") }}" class="list-group-item">Transaction History</a></li>
                                <li class="active"><a href="{{ url("/landscaper/payment-info") }}" class="list-group-item">Account</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-8">                          
                        <div class="row">                            
                            <div class="col-md-12">
                                <div class="card custom-card card-no-radius m-b-30"> 
                                    <div class="card-block">
                                        <div class="add-paypal-account user-profile-details text-center">
                                            <img src="{{ asset("/default/images//PayPal-logo.png") }}" alt="" class="m-t-10 m-b-30" width="160">
                                            <p class="">To Receive Payment<br> Plesae Submit Your Paypal Account Details</p>   
                                            <p>You need Business-Pro account for payment through credit/debit card</p>                                         
                                            <a class="btn btn-info m-t-20 m-b-10" href="#" data-toggle="modal" data-target="#addpaypal_account">Add Paypal Account</a>                                           
                                        </div>
                                    </div>                           
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php } ?>
        </section>
        
        
        <!--start modal -->
        <div class="modal fade" id="addpaypal_account" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form class="modal-content" action="{{ url("landscaper/add-landscaper-payment-info") }}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Paypal Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ asset("/default/images//PayPal-logo.png") }}" alt="" class="m-t-10 m-b-30" width="150">
                        <div class="form-group">
                            <label>Account Holder Name</label>
                            <input type="text" class="form-control" name="paypal_name" id =""  required>
                        </div>
                        <div class="form-group">
                            <label>Account Holder Email</label>
                            <input type="text" class="form-control" name="account_email" id =""  required>
                        </div>
                        <div class="form-group">
                            <label>Paypal Account API Username</label>
                            <input type="text" class="form-control" name="paypal_account_id" id=''  required>
                        </div>   
                         <div class="form-group">
                             <div class="form-group">
                            <label>Paypal Account API Signature</label>
                            <input type="text" class="form-control" name="paypal_api_sign" id=''  required>
                        </div>
                            <label>Paypal Account API Password</label>
                            <input type="password" class="form-control" name="paypal_api_pass" id=''  required>
                        </div> 
                          
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div> <!-- Modal -->

<script>
   function del_card(id){
       if(confirm("Are you sure?")){
           window.location.href = "<?php echo url('landscaper/delete-landscaper-payment-info');?>/"+id;
       }
   }
</script>

@endsection

