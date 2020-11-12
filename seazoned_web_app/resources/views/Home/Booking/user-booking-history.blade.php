@extends("layouts.dashboardlayout")
@section('content')


<section class="main-content booking-list p-y-30">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-heading m-0 regular">Booking History</h3>
                        <hr class="m-t-25 m-b-35">
                        <?php if(session('msg') != ""){ ?>
                                <div class="alert alert-custom d-block text-center">
                                    <span class="big-icon text-success"><i class="fa fa fa-check-circle"></i></span>
                                    <h5>Success!</h5>
                                    <p>{{ session('msg') }}</p>
                                </div><br>
                        <?php } 
                            if(count($services_pend)>0){
                            foreach($services_pend as $one_service){                              
                        ?>
                        <div class="card m-b-35 custom-card card-no-radius booking-item">                    
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="{{ url("/user/booking-history-payment") }}<?php echo '/'.$one_service['book_service']->order_no; ?>" class="medium"><?php echo $one_service['book_service']->order_no; ?></a>
                                    </div>  <div class="col-4 text-left">
                                     <?php
                                        if($one_service["book_service"]->status > 0){
//                                        $service_status='<h6 class="service-status text-info d-inline-block"><i class="fa fa-check m-r-5"></i> Request sent</h6>';
                                        if($one_service["book_service"]->status==1)
                                        $service_status='<h6 class="service-status text-warning d-inline-block"><i class="fa fa-hourglass-start m-r-5"></i>Make Payment To Start Work</h6>';
                                        if($one_service["book_service"]->status==2 && $one_service["book_service"]->is_completed==1)
                                        $service_status='<h6 class="service-status text-warning d-inline-block"><i class="fa fa-hourglass-start m-r-5"></i> Payment is in Escrow</h6>';
                                        if($one_service["book_service"]->status==3 && $one_service["book_service"]->is_completed==1)
                                        $service_status='<h6 class="service-status text-warning d-inline-block"><i class="fa fa-hourglass-start m-r-5"></i> Payment is in Escrow</h6>';
                                        if($one_service["book_service"]->status==3 && $one_service["book_service"]->is_completed==2)
                                        $service_status='<h6 class="service-status text-success d-inline-block"><i class="fa fa-check-circle m-r-5"></i> Payment Success</h6>';
                                        echo $service_status;
                                        }
                                        ?>
                                    </div>
                                    <div class="col-2 text-right">
                                        <h5 class="m-b-2 m-0 medium">$<?php echo $one_service['book_service']->service_price; ?></h5>
                                    </div> 
                                </div>
                            </div>               
                            <div class="card-block">                        
                                <div class="row">
                                    <div class="col-md service-type-details">
                                        <h4 class="m-0 m-b-20 light text-dark"><?php echo $one_service['name']->service_name; ?></h4>
                                        <p class="m-0 m-b-25">Provider : {{ $one_service["landscaper_name"] }}</p>
                                        <?php
                                        $service_status='<h6 class="service-status text-info d-inline-block"><i class="fa fa-check m-r-5"></i> Request sent</h6>';
                                        if($one_service["book_service"]->status==1 && $one_service["book_service"]->is_completed==0)
                                        $service_status='<h6 class="service-status text-warning d-inline-block"><i class="fa fa-hourglass-start m-r-5"></i>Request Accepted</h6>';
                                        if($one_service["book_service"]->status==2 && $one_service["book_service"]->is_completed==1)
                                        $service_status='<h6 class="service-status text-warning d-inline-block"><i class="fa fa-exclamation-circle"></i>Work In Progress</h6>';
                                        if($one_service["book_service"]->status==3 && $one_service["book_service"]->is_completed==1)
                                        $service_status='<h6 class="service-status text-warning d-inline-block"><i class="fa fa-hourglass-start m-r-5"></i> Job has been completed by Landscaper, waiting for your confirmation</h6>';
                                        if($one_service["book_service"]->status==3 && $one_service["book_service"]->is_completed==2)
                                        $service_status='<h6 class="service-status text-success d-inline-block"><i class="fa fa-check-circle m-r-5"></i> Job Completed</h6>';
                                        echo $service_status;
                                        ?>
                                       
                                    </div>
                                    <div class="col-md service-date-details">
                                        <p class="m-0 m-b-10 text-dark regular">Service Request Date</p>
                                        <p class="m-0 m-b-35 light"><?php echo date('D, M d Y',  strtotime($one_service['book_service']->service_date)).' '.date('h:i a',  strtotime($one_service['book_service']->service_time)); ?></p>
                                        <p class="m-0 m-b-10 text-dark">Service Completed Date</p>
                                        <p class=""><?php echo (isset($one_service['book_service']->completion_date) && $one_service['book_service']->completion_date!="")?date('D, M d Y',  strtotime($one_service['book_service']->completion_date )):'--'  ?></p>
                                    </div>
                                    <div class="col-md service-address-details">
                                        <p class="m-0 m-b-10 text-dark regular">Service Address</p>
                                        <p class="m-0 m-b-10 regular"><?php echo $one_service['book_address']->name; ?> </p>
                                        <p class="m-0 m-b-10 light"><?php echo $one_service['book_address']->address; ?> </p>
                                        <p class="m-0 m-b-10 light"><?php echo $one_service['book_address']->contact_number; ?> </p>
                                        <p class="m-0 m-b-10 light"><?php echo $one_service['book_address']->email_address; ?> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }} else { ?>
                            <div class="alert alert-danger">
                                No Booking History Found
                            </div><br>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
@endsection