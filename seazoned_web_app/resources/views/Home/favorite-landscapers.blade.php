<?php

use App\Landscaper; ?>
@extends("layouts.dashboardlayout")
@section('content')
<section class="main-content user-profile p-y-30">
    <div class="container">
        <div class="profile-favorite">
            <div class="row"> 
                <div class="col-md-12 search-listing">
                    <h2 class="heading m-0 regular m-b-25">Favorite</h2>
                    <hr class="m-b-25">
                    <div class="row">
                        @forelse($providers as $provider)
                        <div class="col-md-4 m-b-30">
                            <?php
                            $landscaper_obj = new Landscaper();
                            $visible = $landscaper_obj->get_favorite($provider->id);
                            $result = $landscaper_obj->get_overall_rating($provider->id);
                            $res1 = $landscaper_obj->get_total_review_count($provider->id);
                            ?>
                            <div class="search-item <?php echo ($visible == 1) ? 'active' : ''; ?>" id='fav_div_<?php echo $provider->id ?>'>
                                <a href="{{ url("/Home/Service-Details/" . $provider->id) }}">  <div class="assistance-image" style="cursor:pointer;background-image: url({{ ($provider->profile_image == NULL) ? url("/default/images/assistance-image-1.png") : url("/uploads/services/" . $provider->profile_image) }} )">
                                        <div class="shadow"></div>
                                        <a href="javascript:void(0)" class="favourite" onclick="add_fav('<?php echo $provider->id ?>')"><i class="fa fa-heart"></i></a>
                                    </div></a>
                                <div id="cust_div">
                                    <a class="assistance-details p-x-15 p-y-20 d-block" href="{{ url("/Home/Service-Details/" . $provider->id) }}">
                                        <h4 class="assistance-name regular m-b-10">{{ $provider->name }}</h4>
                                        <p class="assistance-address regular m-0 m-b-10">{{ $provider->location }}</p>
                                        <span class="badge badge-success">{{$result}}<i class="fa fa-star"></i></span>
                                        <small class="text-dark">{{$res1}}</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-md-12">
                            <div class="alert alert-danger">No Data Found</div>                                    
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function add_fav(provider_id) {
        $.ajax({
            url: "{{ url('/home/add-favourite') }}",
            type: 'POST',
            data: {
                landscaper_id: provider_id
            },
            success: function (html) {
                if (html == 1)
                    $("#fav_div_" + provider_id).addClass("active");
                if (html == 0)
                    $("#fav_div_" + provider_id).removeClass("active");
            }
        });
    }
</script>
@endsection