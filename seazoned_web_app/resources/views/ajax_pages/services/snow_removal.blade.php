<div class="row">
    <div class="col-md-12">
        <p class="text-success m-b-10 m-t-15 form-label-success">Car Fit in Driveway</p>
    </div>
    <div class="form-group col-md-4">
        <label for="price-1">First 2 car </label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="first_car" name="first_car" value="{{ isset($car_number) ? $car_number[0]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="first_car_err"> </span>
    </div>
    <div class="form-group col-md-4">
        <label for="price-1">Next each 2 car increase </label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="next_car" name="next_car" value="{{ (isset($car_number) && count($car_number) > 1) ? $car_number[1]->service_field_price - $car_number[0]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="next_car_err"> </span>
    </div>
    <div class="form-group col-md-4">
        <label for="price-1">Upper Limit</label>
        <div class="input-group">
             <!--<span class="input-group-addon" id="price-1"></span>-->
            <input type="number" class="form-control" aria-describedby="price-1" id="car_limit" step="2" min="2" name="car_limit" value="{{ isset($car_number) ? count($car_number) * 2 : "" }}" >
        </div>
         <span class="text text-danger" id="car_limit_err"> </span>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <p class="text-success m-b-10 m-t-15 form-label-success">Driveway Type</p>
    </div>
    <div class="form-group col-md-4">
        <label for="price-1">Straight</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="straight" name="straight" value="{{ isset($road_type) ? $road_type[0]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="straight_err"> </span>
    </div>
    <div class="form-group col-md-4">
        <label for="price-1">Circular</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="circular" name="circular" value="{{ isset($road_type) ? $road_type[1]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="circular_err"> </span>
    </div>
    <div class="form-group col-md-4">
        <label for="price-1">Incline</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="incline" name="incline" value="{{ isset($road_type) ? $road_type[2]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="incline_err"> </span>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <p class="text-success m-b-10 m-t-15 form-label-success">Snow Removal Service type</p>
    </div>
    <div class="form-group col-md-4">
        <label for="price-1">Front Door Walk Way</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="front_door" name="front_door" value="{{ isset($where) ? $where[0]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="front_door_err"> </span>
    </div>
    <div class="form-group col-md-4">
        <label for="price-1">Stairs and Front Landing</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="stairs" name="stairs" value="{{ isset($where) ? $where[1]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="stairs_err"> </span>
    </div>
    <div class="form-group col-md-4">
        <label for="price-1">Side Door Walk Way</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="side_door" name="side_door" value="{{ isset($where) ? $where[2]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="side_door_err"> </span>
    </div>
</div>

<div class="price-preview">
    <p class="text-success m-b-10 form-label-success" onclick="alterTableSnow()"><a href="javascript:void(0)">Click here to Preview Pricing</a></p>

    <table class="table table-sm custom-table" id="snow-table">
        <thead>
        <!--            <tr>
                        <th class="medium">Car fit Driveway</th>
                        <th class="medium">Driveway type</th>
                        <th class="medium">Service type</th>
                        <th class="medium">Price</th>
                    </tr>-->
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<?php 
    $recurring_services = ['Every 7 days','Every 10 days','Every 14 days'];
    $just_once = ['3' => 'Just Once'];
?>
<div class="card card-no-radius m-b-30" id="recurring_div_7">
<!--    <div class="card-header card-header-success">
        <h4 class="m-0 medium">Services</h4>
    </div>-->
    <div class="card-block custom-form p-0">
        <!--<div class="row m-0 p-y-10">-->
            <!--<div class="col-6 medium">-->
<!--            <input type='checkbox' name='service_chk[]' id='service_recurring_7' <?php if(isset($service_prices)){echo "checked";} ?>>
                Recurring Services
            </div>-->
        <!--</div>-->
        <div id="recurring_service_div_7" style="display:none;">
<!--        <div class="row m-0 p-y-10">
            <div class="col-6 medium">Service Frequency</div>
            <div class="col-6 medium">Discount Price</div>
        </div>-->
        <!--<hr class="m-0">-->                                
        <?php foreach($recurring_services as $index => $val) { ?>                                
            <div class="row m-0 p-y-10" >
                <div class="col-6">
                    <div class="form-group m-0">
                        <div class="checkbox styled-checkbox m-t-5">
                            <label class="m-b-0">
                                <span><?php echo $val; ?></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group m-0">
                        <input type="number" class="form-control recurring_services" name="recurring_services[7][<?php echo $index; ?>]" id="recurring_services_7_<?php echo $index; ?>" value='<?php if(isset($service_prices)){echo $service_prices[$index]->discount_price;}else{echo "0";} ?>'/>
                    </div>
                </div>
            </div>
            <!--<hr class="m-0">-->                
        <?php } ?>
        </div> 
        <input type="hidden" name="recurring_services[7][3]" id="recurring_services_7_3"  value='0'/>
        <!-- <div id="once_div">
            <div class="row m-0 p-y-10" >
                <div class="col-6">
                    <div class="form-group m-0">
                        <div class="checkbox styled-checkbox m-t-5">
                            <label class="m-b-0">
                                <span><?php //echo $just_once['3']; ?></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group m-0">
                        <input type="number" class="form-control recurring_services" name="recurring_services[3]" id="recurring_services_3"  value='0'/>
                    </div>
                </div>
            </div>
        </div> -->
        <!--<hr class="m-0">-->    
    <!--</div>-->
    <span class="text text-danger text-center" id="recurring_services_err_7"> </span>
</div>

<script type="text/javascript">
    $("#service_recurring_7").click(function(){
        if($("#service_recurring_7").is(':checked')){
            $("#recurring_service_div_7").show();
        } else {
            $("#recurring_service_div_7").hide();
        }
    });
    
    function alterTableSnow() {
         $('#snow-table').toggle();
        if ($("#car_limit").val() != "" && $("#first_car").val() != "" && $("#next_car").val() != "") {

            $('#snow-table > tbody > tr').remove();
            $('#snow-table > thead > tr').remove();

            $('#snow-table > thead').append('' +
                '<tr>\n' +
                '            <th class="medium">Car fit Driveway</th>\n' +
                '            <th class="medium">Driveway type</th>\n' +
                '            <th class="medium">Service type</th>\n' +
                '            <th class="medium">Price</th>\n' +
                '        </tr>');

            var car_limit = parseInt($("#car_limit").val()) / 2;
            var first_car = parseInt($("#first_car").val());
            var next_car = parseInt($("#next_car").val());

            var straight = parseInt($("#straight").val());
            var circular = parseInt($("#circular").val());
            var incline = parseInt($("#incline").val());

            var front_door = parseInt($("#front_door").val());
            var stairs = parseInt($("#stairs").val());
            var side_door = parseInt($("#side_door").val());

            var price = 0;
            var table_data = "";
            var driveway_type = ['Straight', 'Circular', 'Incline'];
            var driveway_type_price = [straight, circular, incline];
            var service_type = ['Front Door Walk Way', 'Stairs and Front Landing', 'Side Door Walk Way'];
            var service_type_price = [front_door, stairs, side_door];

            for (var i = 1; i <= car_limit; i++) {
                var car = 2 * i;

                if (i == 1)
                    price = first_car;
                else
                    price += next_car;

                for (var j = 0; j <= 2; j++) {
                    for (var k = 0; k <= 2; k++) {

                        table_data += '<tr>' +
                            '    <td>' + car + ' </td>' +
                            '    <td>' + driveway_type[j] + ' </td>' +
                            '    <td>' + service_type[k] + ' </td>' +
                            '    <td>$ ' + (price + driveway_type_price[j] + service_type_price[k]) + '</td>' +
                            '</tr>';
                    }
                }
            }
            $('#snow-table > tbody:last-child').append(table_data);

            $("#submit-zone").show();
        } else {
            $('#snow-table > tbody > tr').remove();
            $('#snow-table > thead > tr').remove();
            $("#submit-zone").hide();
        }
    }

    @if(isset($where, $car_number, $road_type))
    alterTableSnow();
    @endif

</script>