<div class="row">
    <div class="form-group col-md-6">
        <p class="text-success m-b-10 form-label-success">Acerage</p>
        <label for="price-1">First 0.25 acreage price</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" class="form-control" aria-describedby="price-1" step="0.01" min="0" id="mow_first_acre" name="mow_first_acre"  value="{{ isset($acre) ? $acre[0]->service_field_price : "" }}">
        </div>
         <span class="text text-danger" id="mow_first_acre_err"> </span>
    </div>
    <div class="form-group col-md-6">
        <p class="text-success m-b-10 form-label-success">Grass Length</p>
        <label for="price-1">Upto 6 Inches price</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" class="form-control" aria-describedby="price-1" step="0.01" min="0" id="mow_first_grass" name="mow_first_grass"  value="{{ isset($grass) ? $grass[0]->service_field_price : "" }}">
        </div>
          <span class="text text-danger" id="mow_first_grass_err"> </span>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="price-1">Next each 0.25 acreage increase price</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" class="form-control" aria-describedby="price-1" step="0.01" min="0" id="mow_next_acre" name="mow_next_acre"  value="{{ (isset($acre) && count($acre) > 1) ? $acre[1]->service_field_price - $acre[0]->service_field_price : "" }}">
        </div>
          <span class="text text-danger" id="mow_next_acre_err"> </span>
    </div>
    <div class=" form-group col-md-6">
        <label for="price-1">Next each 6 inches increase</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" class="form-control" aria-describedby="price-1" step="0.01" min="0" id="mow_next_grass" name="mow_next_grass"  value="{{ (isset($grass) && count($grass) > 1) ? $grass[1]->service_field_price - $grass[0]->service_field_price : "" }}">
        </div>
          <span class="text text-danger" id="mow_next_grass_err"> </span>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="price-1">Max size of lawn you are willing to service in acres</label>
        <div class="input-group">
            <!--<span class="input-group-addon" id="price-1"></span>-->
            <input type="number" class="form-control" aria-describedby="price-1" step="0.25" min="0.25" id="mow_acre_limit" name="mow_acre_limit"  value="{{ isset($acre) ? count($acre)/4 : "" }}">
        </div>
          <span class="text text-danger" id="mow_acre_limit_err"> </span>
    </div>
</div>


<div class="price-preview">
    <p class="text-success m-b-10 form-label-success" onclick="alterTableMow()"><a href="javascript:void(0)">Click here to Preview Pricing</a></p>

    <table class="table table-sm custom-table" id="lawn-mawning-table">
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
<div class="card card-no-radius m-b-30" id="recurring_div_1">
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
            <input type='checkbox' name='service_chk[]' id='service_recurring_1' <?php if(isset($service_prices)){echo "checked";} ?>>
                Recurring Services
            </div>
        </div>
        <div id="recurring_service_div_1" style="display:none;">
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
                        <input type="number" class="form-control recurring_services" aria-describedby="price-1" name="recurring_services[1][<?php echo $index; ?>]" id="recurring_services_1_<?php echo $index; ?>" value='<?php if(isset($service_prices)){echo $service_prices[$index]->discount_price;}else{echo "0";} ?>'/>
                    </div>
                </div>
            </div>
            <hr class="m-0">                
        <?php } ?>
        </div> 
        <input type="hidden" name="recurring_services[1][3]" id="recurring_services_1_3"  value='0'/>
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
    <span class="text text-danger text-center" id="recurring_services_err_1"> </span>
</div>

<script type="text/javascript">

    $("#service_recurring_1").click(function(){
        if($("#service_recurring_1").is(':checked')){
            $("#recurring_service_div_1").show();
        } else {
            $("#recurring_service_div_1").hide();
        }
    });       

    function alterTableMow() {
        $('#lawn-mawning-table').toggle();
        if ($("#mow_acre_limit").val() != "" && $("#mow_first_acre").val() != "" && $("#mow_first_grass").val() != "" && $("#mow_next_acre").val() != "" && $("#mow_next_grass").val() != "") {
            $('#lawn-mawning-table > tbody > tr').remove();
            $('#lawn-mawning-table > thead > tr').remove();

            $('#lawn-mawning-table > thead').append('' +
                '<tr>\n' +
                '            <th class="medium">Acreage</th>\n' +
                '            <th class="medium">Grass Size</th>\n' +
                '            <th class="medium">Price</th>\n' +
                '        </tr>');

            var mow_acre_limit = parseFloat($("#mow_acre_limit").val()) / 0.25;
            var mow_first_acre = parseFloat($("#mow_first_acre").val());
            var mow_first_grass = parseFloat($("#mow_first_grass").val());
            var mow_next_acre = parseFloat($("#mow_next_acre").val());
            var mow_next_grass = parseFloat($("#mow_next_grass").val());
            var price = 0;

            for (var i = 1; i <= mow_acre_limit; i++) {
                var acre = 0.25 * i;
                if (i == 1)
                    price += mow_first_acre + mow_first_grass;
                else
                    price += mow_next_acre;

                $('#lawn-mawning-table > tbody:last-child').append('<tr>' +
                    '    <td>' + (acre - 0.25) + ' - ' + acre + ' acre</td>' +
                    '    <td>' + '0 - 6' + ' inch</td>' +
                    '    <td>$ ' + price + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '    <td>' + (acre - 0.25) + ' - ' + acre + ' acre</td>' +
                    '    <td>' + '>6' + ' inch</td>' +
                    '    <td>$ ' + (mow_next_grass + price) + '</td>' +
                    '</tr>'
                );
            }
            $("#submit-zone").show();
        } else {
            $('#lawn-mawning-table > tbody > tr').remove();
            $('#lawn-mawning-table > thead > tr').remove();
            $("#submit-zone").hide();
        }

    }

    @if(isset($grass) && isset($acre))
    alterTableMow();
    @endif

</script>