@extends("layouts.dashboardlayout")
@section('content')
<section class="main-content booking-details p-y-30">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">                        
                <div class="card card-no-radius custom-card booking-service-details">
                    <div class="card-header p-x-20 p-y-20">
                        <div class="header-back d-inline-block">
                            <a class="back-btn text-center d-inline-block" href="{{ url("/booking-history") }}">
                                <i class="fa fa-angle-left"></i>
                            </a>      
                            <h4 class="m-0 regular p-l-10">
                                <?php echo $service['book_service']->order_no; ?>
                                <?php //echo "<pre>";   print_r($service); echo "</pre>"; die;?>
                            </h4>
                        </div>

                        <?php
                        $service_status = '<h5 class="service-status text-info float-right d-inline-block"><i class="fa fa-check m-r-5"></i> Request received</h5>';
                        if ($service['book_service']->status == 1 && $service["book_service"]->is_completed == 0)
                            $service_status = '<h5 class="service-status text-warning float-right d-inline-block"><i class="fa fa-hourglass-start m-r-5"></i>Payment Pending</h5>';
                        if ($service['book_service']->status == 2 && $service["book_service"]->is_completed == 1)
                            $service_status = '<h5 class="service-status text-warning float-right d-inline-block"><i class="fa fa-exclamation-circle"></i>Work In Progress</h5>';
                        if ($service['book_service']->status == 3 && $service["book_service"]->is_completed == 1)
                            $service_status = '<h5 class="service-status text-success float-right d-inline-block"><i class="fa fa-check-circle"></i>Job Completed</h5>';
                        if ($service['book_service']->status == 3 && $service["book_service"]->is_completed == 2)
                            $service_status = '<h5 class="service-status text-success float-right d-inline-block"><i class="fa fa-check-circle m-r-5"></i>Job Completed</h5>';
                        echo $service_status;
                        ?>

                    </div>
                    <div class="card-block p-x-20 p-y-35">
                        <div class="row">
                            <div class="col-md-5 customer-details">
                                <h5 class="light m-0 m-b-30"><?php echo $service['service_name']->service_name; ?></h5>
                                <div class="media">
                                    <div class="avtar">
                                        <?php
                                        $prof_pic = url('/') . '/default/images/avatar-landscaper.jpg';
                                        if (isset($service['customer_image']->profile_image) && $service['customer_image']->profile_image != "") {
                                            if (file_exists(public_path() . '/uploads/profile_picture/' . $service['customer_image']->profile_image)) {
                                                $prof_pic = url('/') . '/uploads/profile_picture/' . $service['customer_image']->profile_image;
                                            }
                                        }
                                        ?>
                                        <img src="{{ $prof_pic }}">
                                        <img src="{{ $prof_pic }}" alt="" height="45" width="160">    
                                    </div>                                    
                                    <div class="media-body p-l-15">
                                        <p class="m-b-0 light">Service Provider:</p>
                                        <h5 class="m-b-0 medium"><?php echo $service['landscaper']->name; ?></h5>                                            
                                    </div>
                                </div>
                            </div>
                            <div class="col-md service-info">
                                <p class="m-0 m-b-10 text-dark regular">Service Request Date</p>
                                <p class="m-0 m-b-20 light"><?php echo date('D, M d Y', strtotime($service['book_service']->service_date)) . " " . date('h:i A', strtotime($service['book_service']->service_time)); ?></p>
                                <p class="m-0 m-b-10 text-dark">Service Completed Date</p>
                                <p class=""><?php echo (isset($service['book_service']->completion_date) && $service['book_service']->completion_date != "") ? date('D, M d Y', strtotime($service['book_service']->completion_date)) : '--' ?></p>
                            </div>
                            <div class="col-md service-info">
                                <p class="m-0 m-b-10 text-dark regular">Frequency</p>
                                <p class="m-0 m-b-20 light"><?php echo $service['service_prices']->service_frequency; ?></p>
<!--                                <p class="m-0 m-b-10 text-dark regular">Lawn Size</p>
                                <p class="m-0 m-b-10 light"><?php // echo $service['book_service']->lawn_area;  ?> acres</p>-->
                                <?php
                                if ($service['landscaper']->service_id == 1) {
                                    ?>
                                    <p class="m-0 m-b-10 text-dark regular">Lawn Size</p>
                                    <p class="m-0 m-b-10 light">{{ $service['book_service']->lawn_area }} acres</p>
                                    <p class="m-0 m-b-10 text-dark regular">Grass Length</p>
                                    <p class="m-0 m-b-10 light">{{ $service['book_service']->grass_length }} inches</p>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($service['landscaper']->service_id == 2) {
                                    ?>
                                    <p class="m-0 m-b-10 text-dark regular">Lawn Size</p>
                                    <p class="m-0 m-b-10 light">{{ $service['book_service']->lawn_area }} acres</p>
                                    <p class="m-0 m-b-10 text-dark regular">Leaf Accumulation</p>
                                    <p class="m-0 m-b-10 light">{{ $service['book_service']->leaf_accumulation }}</p>
    <?php
}
?>
                                <?php
                                if ($service['landscaper']->service_id == 3) {
                                    ?>
                                    <p class="m-0 m-b-10 text-dark regular">Lawn Size</p>
                                    <p class="m-0 m-b-10 light">{{ $service['book_service']->lawn_area }} acres</p>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($service['landscaper']->service_id == 4) {
                                    ?>
                                    <p class="m-0 m-b-10 text-dark regular">Lawn Size</p>
                                    <p class="m-0 m-b-10 light">{{ $service['book_service']->lawn_area }} acres</p>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($service['landscaper']->service_id == 5) {
                                    if (isset($service['book_service']->lawn_area)) {
                                        ?>
                                        <p class="m-0 m-b-10 text-dark regular">Lawn Size</p>
                                        <p class="m-0 m-b-10 light">{{ $service['book_service']->lawn_area }} acres</p>
                                    <?php } else { ?>
                                        <p class="m-0 m-b-10 text-dark regular">No of Zones</p>
                                        <p class="m-0 m-b-10 light">{{ $service['book_service']->no_of_zones }}</p>
        <?php
    }
}
?>
                                <?php
                                if ($service['landscaper']->service_id == 6) {
                                    ?>
                                    <p class="m-0 m-b-10 text-dark regular">Pool Water Type</p>
                                    <p class="m-0 m-b-10 light">{{ $service['book_service']->water_type }}</p>
                                    <p class="m-0 m-b-10 text-dark regular">Include Spa / Hot Tub</p>
                                    <p class="m-0 m-b-10 light">{{ $service['book_service']->include_spa }}</p>
                                    <p class="m-0 m-b-10 text-dark regular">Pool Type</p>
                                    <p class="m-0 m-b-10 light">{{ $service['book_service']->pool_type }}</p>
    <?php
}
?>
<?php
if ($service['landscaper']->service_id == 7) {
    ?>
                                    <p class="m-0 m-b-10 text-dark regular">No of Cars</p>
                                    <p class="m-0 m-b-10 light">{{ $service['book_service']->no_of_cars }}</p>
                                    <p class="m-0 m-b-10 text-dark regular">Driveway Type</p>
                                    <p class="m-0 m-b-10 light">{{ $service['book_service']->driveway_type }}</p>
                                    <p class="m-0 m-b-10 text-dark regular">Service Type</p>
                                    <p class="m-0 m-b-10 light">{{ $service['book_service']->service_type }}</p>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="card-block p-x-20 p-y-35">
                        <div class="row">
                            <div class="col-md-4 service-info">
                                <p class="m-0 m-b-10 text-dark regular">Service Address</p>
                                <p class="m-0 m-b-10 regular"><?php echo $service['address_book']->name; ?></p>
                                <p class="m-0 m-b-10 light"><?php echo $service['address_book']->address; ?></p>
                                <!--<p class="m-0 m-b-10 light">Melbourne, Australia</p>-->
                            </div>
                            <div class="col-md-4 service-info">
                                <p class="m-0 m-b-10 text-dark regular">Phone</p>
                                <p class="m-0 m-b-35 regular"><?php echo $service['address_book']->contact_number; ?></p>
                                <p class="m-0 m-b-10 text-dark regular">Email</p>
                                <p class="m-0 m-b-10 light"><?php echo $service['address_book']->email_address; ?> </p>
                            </div>
<?php if ($service['book_service']->status == 3) { ?>
                                <div class="col-md-4">
                                    <a href="#" class="btn btn-success noradius m-t-35 float-right" data-toggle="modal" data-target="#landscaper_rating">Edit Rate & Review</a>
                                </div>
<?php } ?>
                        </div>
                    </div>
                    <hr class="m-0">

                    <div class="card-block p-x-20 p-y-35 service-review-description">
                        <h5 class="m-b-5 regular">Description</h5>
                        <p class="m-b-0 light"><?php echo $service['book_service']->additional_note; ?></p>
                    </div>
                    <div class="card-block p-x-20 p-b-30 p-t-0">
                        <div class="owl-carousel owl-theme">
<?php
if (!empty($service['service_images'])) {
    foreach ($service['service_images'] as $service_image_arr) {
        $image_path = url("/uploads/property/" . $service_image_arr->service_image);
        ?>
                                    <a class="item" href="{{$image_path }}" data-fancybox="group" data-caption="">
                                        <img src="{{ $image_path }}"/>
                                    </a>
                                    <?php
                                }
                            } else {
                                ?>    
                                <!--<a class="item" href="{{ asset("/default/images/gallery-2.jpg") }}" data-fancybox="group" data-caption="">
                                        <img src="{{ asset("/default/images/gallery-2.jpg") }}"/>
                                    </a>-->
                                <p class="alert alert-danger">No image is uploaded.</p>
                                <?php }
                            ?> 
                        </div>
                    </div>
                    <hr class="m-0">
                            <?php if (!empty($service['service_rating']->log_time) && !empty($service['service_rating']->rating_value) && !empty($service['service_rating']->review)) { ?>
                        <div class="card-block p-x-20 p-y-30 user-review-group d-table">
                            <div class="user-review-avtar d-table-cell align-top">
                                <div class="avtar">
                                <?php
                                $prof_pic = url('/') . '/default/images/userdefault.png';
                                if (isset($service['customer_image']->profile_image) && $service['customer_image']->profile_image != "") {
                                    if (file_exists(public_path() . '/uploads/profile_picture/' . $service['customer_image']->profile_image)) {
                                        $prof_pic = url('/') . '/uploads/profile_picture/' . $service['customer_image']->profile_image;
                                    }
                                }
                                ?>
                                    <img src="{{ $prof_pic }}">

                                </div>
                            </div>

                            <div class="col-md booking-details-user-review d-table-cell align-top p-l-15">
                                <div class="username-date w-100">

                                    <?php //echo "<pre>"; print_r($service);die; ?>
                                    <h4 class="text-left d-inline-block regular m-b-15">{{ $service['address_book']->name }}</h4>

                                    <h5 class="d-inline-block regular float-right">{{ date('D, M d Y',strtotime($service['service_rating']->log_time))  }}</h5>
                                </div>

                                <div class="rating col-md-auto m-b-10">
                                    <ul class="list-unstyled m-b-0">
    <?php
    for ($i = 0; $i < $service['service_rating']->rating_value; $i++) {
        ?>
                                            <li><i class="fa fa-star"></i></li>
        <?php
    }
    ?>
                                    </ul>
                                </div>

                                <p class="m-0">{{ $service['service_rating']->review }}</p>

                            </div>
                        </div>
                                    <?php } else { ?>
                        <div class="text-center m-t-30">
                            <a class="text-danger" href="#">No reviews found</a>
                        </div><hr>
                                    <?php } ?>
                </div> 
            </div>
            <div class="col-lg-3">
                <div class="card no-b-radius price-box">
                    <div class="card-block">
                        <h5 class="card-title m-b-0 regular">Price Detail</h5>
                    </div>
                    <ul class="list-group list-group-flush">                                
<!--                        <li class="list-group-item d-block"><p class="m-b-0 regular">Single <span class="inline-block float-right">$ <?php echo $service['book_service']->service_price; ?></span></p></li>-->
                        <li class="list-group-item d-block"><p class="m-b-0 regular"> Service booked<span class="inline-block float-right">$ <?php echo $service['book_service']->service_booked_price; ?></span></p></li>
<!--                       <li class="list-group-item d-block"><p class="m-b-0 text-dark regular">Tax Rate<span class="inline-block float-right">0.00 %</span></p></li>-->

<!--                        <li class="list-group-item d-block"><p class="m-b-0 text-dark regular">Tax<span class="inline-block float-right">20%</span></p></li>
                        <li class="list-group-item d-block total-price-bg"><p class="m-b-0 text-dark regular">Grand Total<span class="inline-block float-right">$ <?php echo $service['book_service']->service_price; ?></span></p></li>-->
                    </ul>
                </div>
<?php
if ($service['book_service']->status == 1) {
    ?>
                    <div class="amount-paid m-t-25">
                        <!--<p class="light m-l-5 text-warning"><i class="fa fa-exclamation-circle m-r-5"></i><span class="regular">Pending</span> Payment of $<?php echo $service['book_service']->service_price; ?></p>-->
                        <p class="light m-l-5 text-warning"><i class="fa fa-exclamation-circle m-r-5"></i><span class="regular">Pending</span> Payment of $<?php echo $service['book_service']->service_booked_price; ?></p>
                        <!--<button type="submit" class="btn btn-success d-block m-t-20 noradius w-100" data-toggle="modal" data-target="#endjobModal">End Job</button>-->
    <?php
}
?>
<?php
if ($service['book_service']->status > 1) {
    ?>
                    <?php
                    if ($service['book_service']->status == 2) {
                        ?>
                            <div class="amount-paid m-t-25">
                            <!--<p class="light m-l-5"><i class="fa fa-check m-r-5"></i><span class="text-success regular">Paid</span> Total Amount $<?php // echo $service['book_service']->service_price; ?></p>-->    
                                <button type="submit" class="btn btn-success d-block m-t-20 noradius w-100" data-toggle="modal" data-target="#endjobModal">End Job</button>
                               <!--<p class="light m-l-5 text-warning"><i class="fa fa-exclamation-circle m-r-5"></i><span class="regular">Pending</span> Payment of $<?php //echo $service['book_service']->service_price;  ?></p>-->
                            <?php
                        }
                        ?>
                        <?php
                        if ($service['book_service']->status == 3) {
                            ?>
                                <!--<button type="submit" class="btn btn-success d-block m-t-20 noradius w-100" data-toggle="modal" data-target="#endjobModal">End Job</button>-->
                                <p class="light m-l-5"><i class="fa fa-check m-r-5"></i><span class="text-success regular">Paid</span> Total Amount $<?php echo $service['book_service']->service_booked_price; ?></p>
                            <?php
                        }
                        ?>							
                        </div>
    <?php
}
?>
                </div>
            </div>
        </div>

</section>

<!-- End Job Modal -->
<div class="modal fade" id="endjobModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" id="end_job" action="{{ route('EndJob') }}" method="post" enctype="multipart/form-data" onsubmit="return image_check()">   
            <div class="modal-content complete-job-modal">
                <div class="modal-header">
                    <button type="button" class="close-btn close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="col-md text-center p-t-50" >
                        <img src="{{ asset('default/images/coplete-job-icon.png') }}">
                        <h4 class="regular m-t-20 m-b-25">Complete Your Job</h4>
                    </div>
                </div>
                <div class="modal-body text-center p-y-40">
                    <div class="form-group text-center">
                        <label for="exampleFormControlFile1" class="medium">Example file input</label>
                        <input type="file" name="end_job_image[]" multiple class="form-control-file m-auto" id="end_job_image"/>
                        <input type="hidden" name="book_service_id" id="book_service_id" value="{{ $book_service_id }}">
                        <span class="text text-danger "id="end_job_img_err"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="btn_submit">Submit</button>
                </div>
        </form>
    </div>
</form>
</div>
</div>

<!-- Rating & Modal -->
<div class="modal fade" id="landscaper_rating" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" action="{{ url("/user/edit-landscaper-rating") }}" method="post" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">How was your service with</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class=" custom-card m-b-50">
                    <!--                            <div class="card-header landscapers-review-heading text-center p-t-35 p-b-25">
                                                    <h4 class="m-0 light m-b-10"></h4>
                                                    <h5  class="m-0 regular">Jhon Harison ?</h5>
                                                </div>-->
                    <div class=" text-center">
                        <div class="form-group landscapers-rating text-center m-b-40">
                            <label for="rating" class="sm-bold m-b-20">Your Rating</label>
                            <input id="input-21c" name="rating" value="0" type="number" class="rating" min=0 max=5 step=1 data-size="sm" data-stars="5" required>
                        </div>
                        <div class="form-group">
                            <label for="review" class="sm-bold m-b-20">Write a Review</label>
                            <textarea class="form-control" rows="5" name="review" id="review" required></textarea>
                        </div>
                        <input type="hidden" name="user_id" id="user_id" value="{{ $service['book_service']->customer_id }}">
                        <input type="hidden" name="landscaper_id" id="landscaper_id" value="{{ $service['book_service']->landscaper_id }}">
                        <input type="hidden" name="book_service_id" id="book_service_id" value="{{ $book_service_id }}">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success noradius p-x-50">Submit</button>
            </div>
        </form>
    </div>
</div> <!-- Modal -->
<script>

    $(document).ready(function () {
        $("#btn_submit").click(function () {
            var end_job_image = $("#end_job_image").val();
            if (end_job_image == '') {
                $("#end_job_img_err").html("Please upload end job images");
                return false;
            } else {
                $("#end_job_img_err").html();
            }

        });
    });

</script>
@endsection