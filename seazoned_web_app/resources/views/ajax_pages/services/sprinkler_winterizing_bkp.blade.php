{{-- <script type="text/javascript" src="{{URL::asset('assets/js/services_validation1.js')}}"></script> --}}
<?php if(isset($acre[0]->service_field_price) || isset($zone[0]->service_field_price)) { ?>
<div class="row">
    <div class="form-group col-md-6">        
        <p class="text-success m-b-10 form-label-success">
            <input type="radio" name="winter_section" id="winter_section_acer" value="acer" {{ isset($acre[0]->service_field_price) ? "checked" : "" }}>
            Acerage
        </p>
    </div>  
    <div class="form-group col-md-6">        
        <p class="text-success m-b-10 form-label-success">
            <input type="radio" name="winter_section" id="winter_section_zone" value="zone" {{ isset($zone[0]->service_field_price) ? "checked" : "" }}>
            Zones
        </p>
    </div>    
</div>
<?php } else { ?>
<div class="row">
    <div class="form-group col-md-6">        
        <p class="text-success m-b-10 form-label-success">
            <input type="radio" name="winter_section" id="winter_section_acer" value="acer" checked>
            Acerage
        </p>
    </div>  
    <div class="form-group col-md-6">        
        <p class="text-success m-b-10 form-label-success">
            <input type="radio" name="winter_section" id="winter_section_zone" value="zone">
            Zones
        </p>
    </div>    
</div>
<?php } ?>

<div id="acer_div" style="display:none">
<div class="row">
    <div class="form-group col-md-6">
        <p class="text-success m-b-10 form-label-success">Acerage</p>
        <label for="price-1">First 0.25 acreage price</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="win_first_acre" name="win_first_acre" value="{{ isset($acre[0]->service_field_price) ? $acre[0]->service_field_price : "" }}">
        </div>
    <span class="text text-danger" id="win_first_acre_err"> </span>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="price-1">Next each 0.25 acreage increase price</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="win_next_acre" name="win_next_acre" value="{{ (isset($acre[0]->service_field_price) && count($acre) > 1) ? $acre[1]->service_field_price - $acre[0]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="win_next_acre_err"> </span>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="price-1">Max size of lawn you are willing to service in acres</label>
        <div class="input-group">
            {{-- <span class="input-group-addon" id="price-1"></span> --}}
            <input type="number" class="form-control" aria-describedby="price-1" id="win_acre_limit" step="0.25" min="0.25" name="win_acre_limit" value="{{ isset($acre) ? count($acre)/4 : "" }}" >
        </div>
          <span class="text text-danger" id="win_acre_limit_err"> </span>
    </div>
</div>
</div>

<div id="zone_div" style="display:none">
<div class="row">        
        <div class="form-group col-md-6">
            <p class="text-success m-b-10 form-label-success">Zones</p>
            <label for="price-1">Upto 3 zones price</label>
            <div class="input-group">
                <span class="input-group-addon" id="price-1">$</span>
                <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="win_first_zone" name="win_first_zone" value="{{ isset($zone[0]->service_field_price) ? $zone[0]->service_field_price : "" }}">
            </div>
             <span class="text text-danger" id="win_first_zone_err"> </span>
        </div>        
    </div>
    
<div class="row">
    <div class="form-group col-md-6">
        <label for="price-1">Next each 3 zones increase</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="win_next_zone" name="win_next_zone" value="{{ (isset($zone[0]->service_field_price) && count($zone) > 1) ? $zone[1]->service_field_price - $zone[0]->service_field_price : "" }}" >
        </div>
            <span class="text text-danger" id="win_next_zone_err"> </span>
    </div>
</div>
    
<div class="row">
    <div class="form-group col-md-6">
        <label for="price-1">Upper Limit for zone (multiple of 3)</label>
        <div class="input-group">
            {{-- <span class="input-group-addon" id="price-1"></span> --}}
            <input type="number" class="form-control" aria-describedby="price-1" id="win_zone_limit" step="3" min="3" name="win_zone_limit" value="{{ isset($zone) ? count($zone) * 3 : "" }}" >
        </div>
            <span class="text text-danger" id="win_zone_limit_err"> </span>
    </div>
</div>
</div>


<div class="price-preview">
    <p class="text-success m-b-10 form-label-success" onclick="alterTableWinter()"><a href="javascript:void(0)">Click here to Preview Pricing</a></p>

    <table class="table table-sm custom-table" id="winter-table">
        <thead>

        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<?php 
    $recurring_services = ['Every 7 days','Every 10 days','Every 14 days'];
    $just_once = ['3' => 'Just Once'];
?>
<div class="card card-no-radius m-b-30" id="recurring_div_5">
    <!--<div class="card-header card-header-success">-->
        <!--<h4 class="m-0 medium">Services</h4>-->
    <!--</div>-->
    <div class="card-block custom-form p-0">
        <!--<div class="row m-0 p-y-10">-->
            <!--<div class="col-6 medium">-->
            <!--<input type='checkbox' name='service_chk[]' id='service_recurring_5' <?php if(isset($service_prices)){echo "checked";} ?>>-->
                <!--Recurring Services-->
            <!--</div>-->
        <!--</div>-->
        <div id="recurring_service_div_5" style="display:none;">
        <!--<div class="row m-0 p-y-10">-->
            <!--<div class="col-6 medium">Service Frequency</div>-->
            <!--<div class="col-6 medium">Discount Price</div>-->
        <!--</div>-->
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
                         <input type="number" class="form-control recurring_services" name="recurring_services[5][<?php echo $index; ?>]" id="recurring_services_5_<?php echo $index; ?>" value='<?php if(isset($service_prices)){echo $service_prices[$index]->discount_price;}else{echo "0";} ?>'/>
                    </div>
                </div>
            </div>
            <!--<hr class="m-0">-->                
        <?php } ?>
        </div> 
        <input type="hidden" name="recurring_services[5][3]" id="recurring_services_5_3"  value='0'/>
        
    <span class="text text-danger text-center" id="recurring_services_err_5"> </span>
</div>
</div>
<script type="text/javascript">
    if($("#winter_section_zone").is(':checked')){
        $("#zone_div").show();
        $("#win_acre_limit").val('');        
    } 

    if($("#winter_section_acer").is(':checked')){
        $("#acer_div").show();
        $("#win_zone_limit").val('');
    }

    $('#winter_section_zone').click(function(){
        $("#zone_div").show();
        $("#acer_div").hide();
        $("#win_first_acre").val('');
        $("#win_next_acre").val('');
        $("#win_acre_limit").val('');
    });

    $('#winter_section_acer').click(function(){
        $("#acer_div").show();
        $("#zone_div").hide();
        $("#win_first_zone").val('');
        $("#win_next_zone").val('');
        $("#win_zone_limit").val('');
    });

    $("#service_recurring_5").click(function(){
        if($("#service_recurring_5").is(':checked')){
            $("#recurring_service_div_5").show();
        } else {
            $("#recurring_service_div_5").hide();
        }
    });

    function alterTableWinter() {
           $('#winter-table').toggle();

        if ($('#acer_div').css('display') == 'block' && $("#win_first_acre").val() != "" && $("#win_next_acre").val() != "" && $("#win_acre_limit").val() != "") {
            $('#winter-table > tbody > tr').remove();
            $('#winter-table > thead > tr').remove();

            $('#winter-table > thead').append('' +
                '<tr>\n' +
                '            <th class="medium">Acreage</th>\n' +
                '            <th class="medium">Price</th>\n' +
                '        </tr>');
            
            var win_first_acre = parseFloat($("#win_first_acre").val());
            var win_next_acre = parseFloat($("#win_next_acre").val());
            var win_acre_limit = parseFloat($("#win_acre_limit").val()) / 0.25;
            var price = 0;

            for (var i = 1; i <= win_acre_limit; i++) {
                var acre = 0.25 * i;

                if (i == 1)
                    price += win_first_acre;
                else
                    price += win_next_acre;

                $('#winter-table > tbody:last-child').append('<tr>' +
                    '    <td>' + (acre - 0.25) + ' - ' + acre + ' Acre</td>' +
                    '    <td>$ ' + price + '</td>' +
                    '</tr>');
            }

            $("#submit-zone").show();
        } else if ($('#zone_div').css('display') == 'block' && $("#win_first_zone").val() != "" && $("#win_next_zone").val() != "" && $("#win_zone_limit").val() != "") {
            $('#winter-table > tbody > tr').remove();
            $('#winter-table > thead > tr').remove();

            $('#winter-table > thead').append('' +
                '<tr>\n' +
                '            <th class="medium">Zone</th>\n' +
                '            <th class="medium">Price</th>\n' +
                '        </tr>');
            
            var win_first_zone = parseFloat($("#win_first_zone").val());
            var win_next_zone = parseFloat($("#win_next_zone").val());
            var win_zone_limit = Math.floor(parseFloat($("#win_zone_limit").val()) / 3);
            var zone = 0;

            for (var j = 1; j <= win_zone_limit; j++) {
                if (j == 1) {
                    zone = 3;
                    price = win_first_zone;
                } else {
                    zone += 3;
                    price = win_first_zone + (win_next_zone * (j - 1));
                }

                    $('#winter-table > tbody:last-child').append('<tr>' +
                        '    <td>' + (zone - 3) + ' - ' + zone + ' Zones</td>' +
                        '    <td>$ ' + price + '</td>' +
                        '</tr>');
            }
            $("#submit-zone").show();
        } else {
            $('#winter-table > tbody > tr').remove();
            $('#winter-table > thead > tr').remove();
            $("#submit-zone").hide();
        }
    }
    
    // @if(isset($acre) && isset($zone))
    // alterTableWinter();
    // @endif

</script>