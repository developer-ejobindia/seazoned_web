<div class="row">
    <div class="col-md-12">
        <p class="text-success m-b-10 m-t-15 form-label-success">Pool Water Type</p>
    </div>
    <div class="form-group col-md-6">
        <label for="price-1">Chlorine</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="chlorine" name="chlorine" value="{{ isset($water_type) ? $water_type[0]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="chlorine_err"> </span>
    </div>
    <div class="form-group col-md-6">
        <label for="price-1">Saline</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="saline" name="saline" value="{{ isset($water_type) ? $water_type[1]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="saline_err"> </span>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6">
        <label for="price-1" class="text-success form-label-success">Include a Spa/Hot Tub</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="spa_hot_tub" name="spa_hot_tub" value="{{ isset($spa) ? $spa[0]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="spa_hot_tub_err"> </span>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <p class="text-success m-b-10 m-t-15 form-label-success">Pool type</p>
    </div>
    <div class="form-group col-md-6">
        <label for="price-1">Inground</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="inground" name="inground" value="{{ isset($pool_type) ? $pool_type[0]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="inground_err"> </span>
    </div>
    <div class="form-group col-md-6">
        <label for="price-1">Above Ground</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="above_ground" name="above_ground" value="{{ isset($pool_type) ? $pool_type[1]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="above_ground_err"> </span>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <p class="text-success m-t-15 m-b-10 form-label-success">State of Pool</p>
    </div>
    <div class="form-group col-md-4">
        <label for="price-1">Relatively Clear</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="clear" name="clear" value="{{ isset($pool_state) ? $pool_state[0]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="clear_err"> </span>
    </div>
    <div class="form-group col-md-4">
        <label for="price-1">Moderately Cloudy</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="cloudy" name="cloudy" value="{{ isset($pool_state) ? $pool_state[1]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="cloudy_err"> </span>
    </div>
    <div class="form-group col-md-4">
        <label for="price-1">Heavy Algae Present</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="heavy" name="heavy" value="{{ isset($pool_state) ? $pool_state[2]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="heavy_err"> </span>
    </div>
</div>

<div class="price-preview">
    <p class="text-success m-b-10 form-label-success" onclick="alterTablePool()"><a href="javascript:void(0)">Click here to Preview Pricing</a></p>

    <table class="table table-sm custom-table" id="pool_table" style="display: none">
        <thead>
        <tr>
            <th class="medium">Pool Water Type</th>
            <th class="medium">Include a Spa/Hot Tub</th>
            <th class="medium">Pool Type</th>
            <th class="medium">State of Pool</th>
            <th class="medium">Price</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Chlorine</td>
            <td>Yes</td>
            <td>Inground</td>
            <td>Relatively Clear</td>
            <td>$ <span id="p_1"></span></td>
        </tr>
        <tr>
            <td>Chlorine</td>
            <td>Yes</td>
            <td>Inground</td>
            <td>Moderately Cloudy</td>
            <td>$ <span id="p_2"></span></td>
        </tr>
        <tr>
            <td>Chlorine</td>
            <td>Yes</td>
            <td>Inground</td>
            <td>Heavy Algae Present</td>
            <td>$ <span id="p_3"></span></td>
        </tr>
        <tr>
            <td>Chlorine</td>
            <td>Yes</td>
            <td>Above Ground</td>
            <td>Relatively Clear</td>
            <td>$ <span id="p_4"></span></td>
        </tr>
        <tr>
            <td>Chlorine</td>
            <td>Yes</td>
            <td>Above Ground</td>
            <td>Moderately Cloudy</td>
            <td>$ <span id="p_5"></span></td>
        </tr>
        <tr>
            <td>Chlorine</td>
            <td>Yes</td>
            <td>Above Ground</td>
            <td>Heavy Algae Present</td>
            <td>$ <span id="p_6"></span></td>
        </tr>
        <tr>
            <td>Saline</td>
            <td>Yes</td>
            <td>Inground</td>
            <td>Relatively Clear</td>
            <td>$ <span id="p_7"></span></td>
        </tr>
        <tr>
            <td>Saline</td>
            <td>Yes</td>
            <td>Inground</td>
            <td>Moderately Cloudy</td>
            <td>$ <span id="p_8"></span></td>
        </tr>
        <tr>
            <td>Saline</td>
            <td>Yes</td>
            <td>Inground</td>
            <td>Heavy Algae Present</td>
            <td>$ <span id="p_9"></span></td>
        </tr>
        <tr>
            <td>Saline</td>
            <td>Yes</td>
            <td>Above Ground</td>
            <td>Relatively Clear</td>
            <td>$ <span id="p_10"></span></td>
        </tr>
        <tr>
            <td>Saline</td>
            <td>Yes</td>
            <td>Above Ground</td>
            <td>Moderately Cloudy</td>
            <td>$ <span id="p_11"></span></td>
        </tr>
        <tr>
            <td>Saline</td>
            <td>Yes</td>
            <td>Above Ground</td>
            <td>Heavy Algae Present</td>
            <td>$ <span id="p_12"></span></td>
        </tr>
        </tbody>
    </table>
</div>

<?php 
    $recurring_services = ['Every 7 days','Every 10 days','Every 14 days'];
    $just_once = ['3' => 'Just Once'];
?>
<div class="card card-no-radius m-b-30" id="recurring_div_6">
    <div class="card-header card-header-success">
        <h4 class="m-0 medium">Services</h4>
    </div>
    <div class="alert alert-info m-t-15 m-r-15 m-l-15">
            <i class="fa fa-info-circle" aria-hidden="true"></i> Enter the discounted amount you would like to have subtracted from total base price.
    </div>
<div class="info-extra m-r-15 m-l-15">Example : Regular price $25, Every 7 days (-$5.00) Now 7 day Recurring =($20.00)</div>
    <div class="card-block custom-form p-0">
        <div class="row m-0 p-y-10">
            <div class="col-6 medium">
            <input type='checkbox' name='service_chk[]' id='service_recurring_6' <?php if(isset($service_prices)){echo "checked";} ?>>
                Recurring Services
            </div>
        </div>
        <div id="recurring_service_div_6" style="display:none;">
        <div class="row m-0 p-y-10">
            <div class="col-6 medium">Service Frequency</div>
            <div class="col-6 medium">Discount Price</div>
        </div>
        <hr class="m-0">                                
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
                    <div class="input-group">
                         <span class="input-group-addon" id="price-1">-$</span>
                       <input type="number" class="form-control recurring_services" aria-describedby="price-1" name="recurring_services[6][<?php echo $index; ?>]" id="recurring_services_6_<?php echo $index; ?>" value='<?php if(isset($service_prices)){echo $service_prices[$index]->discount_price;}else{echo "0";} ?>'/>
                    </div>
                </div>
            </div>
            <hr class="m-0">                
        <?php } ?>
        </div> 
        <input type="hidden" name="recurring_services[6][3]" id="recurring_services_6_3"  value='0'/>
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
        <hr class="m-0">    
    </div>
    <span class="text text-danger text-center" id="recurring_services_err_6"> </span>
</div>

<script type="text/javascript">

    $("#service_recurring_6").click(function(){
        if($("#service_recurring_6").is(':checked')){
            $("#recurring_service_div_6").show();
        } else {
            $("#recurring_service_div_6").hide();
        }
    });

    function alterTablePool() {
        if ($("#chlorine").val() != "" && $("#saline").val() != "") {
            $("#pool_table").toggle();
            var chlorine = parseInt($("#chlorine").val());
            var saline = parseInt($("#saline").val());
            var spa_hot_tub = parseInt($("#spa_hot_tub").val());
            var inground = parseInt($("#inground").val());
            var above_ground = parseInt($("#above_ground").val());
            var clear = parseInt($("#clear").val());
            var cloudy = parseInt($("#cloudy").val());
            var heavy = parseInt($("#heavy").val());

            var price = [];

            price[0] = 0;

            price[1] = chlorine + spa_hot_tub + inground + clear;
            price[2] = chlorine + spa_hot_tub + inground + cloudy;
            price[3] = chlorine + spa_hot_tub + inground + heavy;

            price[4] = chlorine + spa_hot_tub + above_ground + clear;
            price[5] = chlorine + spa_hot_tub + above_ground + cloudy;
            price[6] = chlorine + spa_hot_tub + above_ground + heavy;

            price[7] = saline + spa_hot_tub + inground + clear;
            price[8] = saline + spa_hot_tub + inground + cloudy;
            price[9] = saline + spa_hot_tub + inground + heavy;

            price[10] = saline + spa_hot_tub + above_ground + clear;
            price[11] = saline + spa_hot_tub + above_ground + cloudy;
            price[12] = saline + spa_hot_tub + above_ground + heavy;

            for (var i = 1; i <= 12; i++) {
                $("#p_" + i).html(price[i]);
            }

            $("#submit-zone").show();
        }
        else {
            $("#submit-zone").hide();
        }

    }

    // @if(isset($pool_state, $pool_type, $water_type, $spa))
    // alterTablePool();
    // @endif

</script>