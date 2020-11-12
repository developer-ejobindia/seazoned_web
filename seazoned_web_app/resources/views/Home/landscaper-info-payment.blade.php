@extends("layouts.dashboardlayout")
@section('content')
<section class="main-content user-profile my-profile-payment-info p-y-30">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-no-radius side-menu m-b-30">
                    <div class="card-block p-t-40 p-b-30">
                         <?php
                         $prof_pic = url('/') . '/default/images/avatar-landscaper.jpg';
                         if (session("prof_img") !== "") {
                             if (file_exists(public_path() . '/uploads/profile_picture/' . session("prof_img"))) {
                                 $prof_pic = url('/') . '/uploads/profile_picture/' . session("prof_img");
                             }
                         }
                        ?>
                        <div class="avtar">
                         <img src="{{ $prof_pic }}" alt="" >                     
                        </div>
                        <h4 class="profile-name text-center m-0 m-t-20"><?php echo isset($landscaper_info[0]->name) && $landscaper_info[0]->name != '' ?$landscaper_info[0]->name : ''?></h4>
                    </div> 
                    <ul class="list-group list-group-flush list-unstyled job-btn-group">
                        <li class="active"><a href="{{ url("/landscaper/transcation-history") }}" class="list-group-item">Transaction History</a></li>
                        <li class=""><a href="{{ url("/landscaper/payment-info") }}" class="list-group-item">Account</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8 col-sm-12 col-12">
                <div class="card custom-card card-no-radius">
                    <div class="card-header">
                        <h4 class="m-0 regular">Transaction History</h4>                                
                    </div>
                    <div class="card-block user-profile-details">
                        <?php if (count($transaction_history) != 0) { ?>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">Service ID</th>
                                        <th scope="col">Service</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($transaction_history as $service) {
                                        ?>
                                        <tr>
                                            <td><a href="{{ url('landscaper-dashboard/view-service-details').'/'.$service['book_service']->id }}"><?php echo ($service['book_service']->order_no); ?></a></td>
                                            <td>
                                                <p class="m-b-0 medium"><?php echo $service['name']->service_name; ?></p>
                                                <p class="m-b-0 medium"><small><?php echo $service['landscaper_name'] ?></small></p> 
                                                <small class="m-b-0"><?php echo date('D, M d Y',strtotime($service['book_service']->service_date)) ?></small> 
                                            </td>
                                            <td>
                                                <label class="badge badge-warning">
                                                    <?php
                                                    $payment_status = '';
                                                    if ($service['book_service']->status == 1)
                                                        $payment_status = '';
                                                    if ($service['book_service']->status == 2)
                                                        $payment_status = 'Processing';
                                                    if ($service['book_service']->status == 3)
                                                        $payment_status = 'Success';
                                                    echo $payment_status;
                                                    ?>

                                                </label></td>
                                            <?php if (($service['book_service']->landscaper_payment) != 0) { ?>  
                                                <td>$<?php echo $service['book_service']->landscaper_payment ?></td>

                                                <?php } else { ?> 
                                                <td>$0</td>
                                            <?php } ?>
                                        </tr>
                                        <?php } ?>

                                </tbody>
                            </table>
                        <?php } else { ?>
                            <div class="alert alert-custom d-block text-center"> 
                                    <span class="big-icon text-danger"><i class="fa fa-times-circle" aria-hidden="true"></i></span>
                                    <h5>Sorry!</h5>
                               <p> No Transaction Found</p>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div> 
    </div>
</section>
</body>
</html>

@endsection