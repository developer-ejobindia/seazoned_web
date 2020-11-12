<?php use App\Landscaper; ?>
@extends("layouts.dashboardlayout")
@section('content')
<section class="main-content user-profile p-y-30">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <div class="search-filter">
                    <div class="header-collapse clearfix m-b-15">
                        <h4 class="bold m-0 d-inline-block">Service Type</h4>
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <div class="search-service-list">
                        <ul class="list-unstyled m-0">
                            <?php 
                            foreach ($services as $service) {
                                $url = url('/') . '/Home/Search-List/' . $service->id;
                                ?><?php if(isset($service_id) && $service_id != '') { ?>
                             <li class="<?php echo (isset($service_id) && $service_id == $service->id) ? 'active' : ''; ?>"><a href="javascript:void(0)" onclick="filterServicewise(<?php echo $service->id; ?>)"><?php echo $service->service_name; ?></a></li>    
                                <?php  }else { ?>
                                <li class="<?php echo (isset($select_service_id) && $select_service_id == $service->id) ? 'active' : ''; ?>"><a href="javascript:void(0)" onclick="filterServicewise(<?php echo $service->id; ?>)"><?php echo $service->service_name; ?></a></li>                     
                                <?php
                            }
                        }
                          ?>
                        </ul>
                    </div>
                </div>
                <div class="search-filter">
                    <div class="filter-title m-b-15">
                        <h4 class="filter-title bold m-0 d-inline-block">Rating Range</h4>
                    </div>
                    <div>
                        <input type="text" id="example_id" name="example_name" value="" />
                    </div>
                </div>
                <div class="search-filter">
                    <div class="filter-title m-b-15">
                        <h4 class="filter-title bold m-0 d-inline-block">Price</h4>
                    </div>
                    <div>
                        <select name="price_id" id="price_id" onchange="filterByPrice(this.value)" class="form-control">
                            <option value="l" <?php echo(isset($price_filter) && $price_filter == "l") ? "selected" : ""; ?> >Price Low to High</option>
                            <option value="h" <?php echo(isset($price_filter) && $price_filter == "h") ? "selected" : ""; ?> >Price High to Low</option>
                        </select>
                    </div>
                </div>
                <script>
                    $(function(){
                        $("#example_id").ionRangeSlider({
                            type: "double",
                            min:0,
                            max:5, 
                            from:<?php echo(isset($min_rate) && $min_rate != "") ? $min_rate : 0; ?>,
                            to:<?php echo(isset($max_rate) && $max_rate != "") ? $max_rate : 5; ?>,
                            step: 1,
                            grid: false,
                            grid_snap: true,
                            onFinish: function (data) {
                                filterResult(data.from,data.to);
                            }
                        });
                    });

                    function filterResult(from,to){                        
                        var price_filter = '<?php echo(isset($price_filter) && $price_filter != "") ? $price_filter : ''; ?>';
                        var service = '<?php echo(isset($service_id) && $service_id != "") ? $service_id : $select_service_id; ?>';
                        var loc = '<?php echo(isset($loc_txt) && $loc_txt != "") ? $loc_txt : ''; ?>';
                        var state = '<?php echo(isset($state) && $state != "") ? $state : ''; ?>';
                        var city = '<?php echo(isset($city) && $city != "") ? $city : ''; ?>';
                        var country = '<?php echo(isset($country) && $country != "") ? $country : ''; ?>';
                        $("#min_rate").val(from);
                        $("#max_rate").val(to);
                        $("#loc_txt").val(loc);
                        $("#administrative_area_level_1").val(state);
                        $("#locality").val(city);
                        $("#country").val(country);
                        $("#filter_price").val(price_filter);
                        $("#select_service_id").val(service);
                        $("#search-list-location").submit();
                    }

                    function filterServicewise(sid){
                        var min = '<?php echo(isset($min_rate) && $min_rate != "") ? $min_rate : 0; ?>';
                        var max = '<?php echo(isset($max_rate) && $max_rate != "") ? $max_rate : 5; ?>';
                        var loc = '<?php echo(isset($loc_txt) && $loc_txt != "") ? $loc_txt : ''; ?>';
                        var state = '<?php echo(isset($state) && $state != "") ? $state : ''; ?>';
                        var city = '<?php echo(isset($city) && $city != "") ? $city : ''; ?>';
                        var country = '<?php echo(isset($country) && $country != "") ? $country : ''; ?>';
                        var service = sid;
                        var price_filter = '<?php echo(isset($price_filter) && $price_filter != "") ? $price_filter : ''; ?>';
                        $("#min_rate").val(min);
                        $("#max_rate").val(max);
                        $("#loc_txt").val(loc);
                        $("#administrative_area_level_1").val(state);
                        $("#locality").val(city);
                        $("#country").val(country);
                        $("#service_txt").val(service);
                        $("#select_service_id").val(service);
                        $("#filter_price").val(price_filter);
                        $("#search-list-location").submit();
                    }
                    
                    function filterByPrice(val){
                        var min = '<?php echo(isset($min_rate) && $min_rate != "") ? $min_rate : 0; ?>';
                        var max = '<?php echo(isset($max_rate) && $max_rate != "") ? $max_rate : 5; ?>';
                        var loc = '<?php echo(isset($loc_txt) && $loc_txt != "") ? $loc_txt : ''; ?>';
                        var state = '<?php echo(isset($state) && $state != "") ? $state : ''; ?>';
                        var city = '<?php echo(isset($city) && $city != "") ? $city : ''; ?>';
                        var country = '<?php echo(isset($country) && $country != "") ? $country : ''; ?>';
                        var service = '<?php echo(isset($select_service_id) && $select_service_id != "") ? $select_service_id : ''; ?>';
                        var service = '<?php echo(isset($service_id) && $service_id != "") ? $service_id : $select_service_id; ?>';
                        var price_filter = val;
                        $("#min_rate").val(min);
                        $("#max_rate").val(max);
                        $("#loc_txt").val(loc);
                        $("#administrative_area_level_1").val(state);
                        $("#locality").val(city);
                        $("#country").val(country);
                        $("#select_service_id").val(service);
                        $("#filter_price").val(price_filter);
                        $("#search-list-location").submit();
                    }
                </script>
            </div>
            <div class="col-md-10 search-listing">
                <h2 class="heading m-0 regular m-b-35">{{ count($providers) }} Results Found In <span class="text-danger">{{ (isset($loc_txt) && $loc_txt!="")?$loc_txt:"" }}</span></h2>

                <div class="row">
                    @forelse($providers as $provider)
                    <div class="col-md-4 m-b-30">
                        <?php 
                            $landscaper_obj = new Landscaper();
                            if (session("user_role") != "" && session("user_role") == "Users") {
                                
                                $visible = $landscaper_obj->get_favorite($provider->id);
                            }                
                            $rating  = $landscaper_obj->get_overall_rating($provider->id);
                            $review  = $landscaper_obj->get_total_review_count($provider->id);
                        ?>
                        <div class="search-item <?php echo (isset($visible) && $visible==1)?'active':''; ?>" id='fav_div_<?php echo $provider->id ?>'>
                            <a href="{{ url("/Home/Service-Details/" . $provider->id) }}">  <div class="assistance-image" style="cursor:pointer;background-image: url({{ ($provider->profile_image == NULL) ? url("/default/images/assistance-image-1.png") : url("/uploads/services/" . $provider->profile_image) }} )">
                                    <div class="shadow"></div>
                                    <?php 
                                        if (session("user_role") != "" && session("user_role") == "Users") { ?>
                                    <a href="javascript:void(0)" class="favourite" onclick="add_fav('<?php echo $provider->id ?>')"><i class="fa fa-heart"></i></a>
                                        <?php } ?>
                                </div></a>
                            <div id="cust_div">
                                <a class="assistance-details p-x-15 p-y-20 d-block" href="{{ url("/Home/Service-Details/" . $provider->id) }}">
                                    <h4 class="assistance-name regular m-b-10">{{ $provider->name }}</h4>
                                    <p class="assistance-address regular m-0 m-b-10">{{ $provider->location }}</p>
                                    <p class="assistance-address regular m-0 m-b-10">${{ $provider->min_price }}</p>
                                    <span class="badge badge-success">{{(isset($rating) && $rating!="")?$rating:0}}<i class="fa fa-star"></i></span>
                                    <small class="text-dark">{{(isset($review) && $review!="")?$review:0}}</small>
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
</section>

<script>
    $(document).ready(function () {
        if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showLocation);
        } else {
        $('#location').html('Geolocation is not supported by this browser.');
        }
    });
    
    function showLocation(position) {

        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        $('#cust_div a').each(function () {
        this.href += '/' + latitude + '/' + longitude;
    });
    }

    function add_fav(provider_id){
        $.ajax({
            url: "{{ url('/home/add-favourite') }}",
            type: 'POST',
            data: {
                landscaper_id:provider_id
            },
            success: function (html) {
                if(html == 1)
                    $("#fav_div_"+provider_id).addClass("active");
                if(html == 0)
                    $("#fav_div_"+provider_id).removeClass("active");
            }
        });
    }
</script>

@endsection