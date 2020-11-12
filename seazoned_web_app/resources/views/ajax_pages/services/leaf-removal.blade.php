<div class="row">
    <div class="form-group col-md-6">
        <p class="text-success m-b-10 form-label-success">Acerage</p>
        <label for="price-1">First 0.25 acreage price</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="leaf_first_acre" name="leaf_first_acre"  value="{{ isset($acre) ? $acre[0]->service_field_price : "" }}">
        </div>
         <span class="text text-danger" id="leaf_first_acre_err"> </span>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6">
        <label for="price-1">Next each 0.25 acreage increase price</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="leaf_next_acre" name="leaf_next_acre"  value="{{ (isset($acre) && count($acre) > 1) ? $acre[1]->service_field_price - $acre[0]->service_field_price : "" }}">
        </div>
         <span class="text text-danger" id="leaf_next_acre_err"> </span>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="price-1">Max size of lawn you are willing to service in acres</label>
        <div class="input-group">
            <!--<span class="input-group-addon" id="price-1"></span>-->
            <input type="number" class="form-control" aria-describedby="price-1" step="0.25" min="0.25" id="leaf_acre_limit" name="leaf_acre_limit"  value="{{ isset($acre) ? count($acre)/4 : "" }}">
        </div>
         <span class="text text-danger" id="leaf_acre_limit_err"> </span>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <p class="text-success m-b-10 m-t-15 form-label-success">Accumulation</p>
    </div>
    <div class="form-group col-md-6">
        <label for="price-1">Light</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" class="form-control" aria-describedby="price-1" id="leaf_light" name="leaf_light" value="{{ isset($leaf) ? $leaf[0]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="leaf_light_err"> </span>
    </div>
    <div class="form-group col-md-6">
        <label for="price-1">Medium</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" class="form-control" aria-describedby="price-1" id="leaf_medium" name="leaf_medium" value="{{ isset($leaf) ? $leaf[1]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="leaf_medium_err"> </span>
    </div>

    <div class="form-group col-md-6">
        <label for="price-1">Heavy</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" class="form-control" aria-describedby="price-1" id="leaf_heavy" name="leaf_heavy" value="{{ isset($leaf) ? $leaf[2]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="leaf_heavy_err"> </span>
    </div>
    <div class="form-group col-md-6">
        <label for="price-1">Over the top</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" class="form-control" aria-describedby="price-1" id="leaf_over_top" name="leaf_over_top" value="{{ isset($leaf) ? $leaf[3]->service_field_price : "" }}" >
        </div>
         <span class="text text-danger" id="leaf_over_top_err"> </span>
    </div>
</div>


<div class="price-preview">
    <p class="text-success m-b-10 form-label-success" onclick="alterTableLeaf()"><a href="javascript:void(0)">Click here to Preview Pricing</a></p>

    <table class="table table-sm custom-table" id="leaf-table">
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
<div class="card card-no-radius m-b-30" id="recurring_div_2">
    <!--<div class="card-header card-header-success">-->
        <!--<h4 class="m-0 medium">Services</h4>-->
    <!--</div>-->
    <div class="card-block custom-form p-0">
<!--        <div class="row m-0 p-y-10">
            <div class="col-6 medium">-->
            <!--<input type='checkbox' name='service_chk[]' id='service_recurring_2' <?php if(isset($service_prices)){echo "checked";} ?>>-->
                <!--Recurring Services-->
<!--            </div>
        </div>-->
        <div id="recurring_service_div_2" style="display:none;">
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
                       <input type="number" class="form-control recurring_services" name="recurring_services[2][<?php echo $index; ?>]" id="recurring_services_2_<?php echo $index; ?>" value='<?php if(isset($service_prices)){echo $service_prices[$index]->discount_price;}else{echo "0";} ?>'/>
                    </div>
                </div>
            </div>
            <!--<hr class="m-0">-->                
        <?php } ?>
        </div> 
        <input type="hidden" name="recurring_services[2][3]" id="recurring_services_2_3"  value='0'/>
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
<!--        <hr class="m-0">    
    </div>-->
    <span class="text text-danger text-center" id="recurring_services_err_2"> </span>
</div>
</div>
<script type="text/javascript">
    $("#service_recurring_2").click(function(){
        if($("#service_recurring_2").is(':checked')){
            $("#recurring_service_div_2").show();
        } else {
            $("#recurring_service_div_2").hide();
        }
    });

    function alterTableLeaf() {
          $('#leaf-table').toggle();
        if ($("#leaf_acre_limit").val() != "" && $("#leaf_first_acre").val() != "" && $("#leaf_next_acre").val() != "") {
            $('#leaf-table > tbody > tr').remove();
            $('#leaf-table > thead > tr').remove();

            $('#leaf-table > thead').append('' +
                '<tr>\n' +
                '            <th class="leaf_medium">Acreage</th>\n' +
                '            <th class="leaf_medium">Accumulation</th>\n' +
                '            <th class="leaf_medium">Price</th>\n' +
                '        </tr>');

            var leaf_acre_limit = parseFloat($("#leaf_acre_limit").val()) / 0.25;
            var leaf_first_acre = parseFloat($("#leaf_first_acre").val());
            var leaf_next_acre = parseFloat($("#leaf_next_acre").val());

            var leaf_light = parseInt($("#leaf_light").val());
            var leaf_medium = parseInt($("#leaf_medium").val());
            var leaf_heavy = parseInt($("#leaf_heavy").val());
            var leaf_over_top = parseInt($("#leaf_over_top").val());

            var price = 0;

            for (var i = 1; i <= leaf_acre_limit; i++) {
                var acre = 0.25 * i;

                if (i == 1)
                    price = leaf_first_acre;
                else
                    price += leaf_next_acre;

                $('#leaf-table > tbody:last-child').append('<tr>' +
                    '    <td>' + (acre - 0.25) + ' - ' + acre + ' acre</td>' +
                    '    <td>' + 'Light' + ' </td>' +
                    '    <td>$ ' + (leaf_light + price) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '    <td>' + (acre - 0.25) + ' - ' + acre + ' acre</td>' +
                    '    <td>' + 'Medium' + ' </td>' +
                    '    <td>$ ' + (leaf_medium + price) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '    <td>' + (acre - 0.25) + ' - ' + acre + ' acre</td>' +
                    '    <td>' + 'Heavy' + ' </td>' +
                    '    <td>$ ' + (leaf_heavy + price) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '    <td>' + (acre - 0.25) + ' - ' + acre + ' acre</td>' +
                    '    <td>' + 'Over the top' + ' </td>' +
                    '    <td>$ ' + (leaf_over_top + price) + '</td>' +
                    '</tr>'
                );
            }
            $("#submit-zone").show();
        } else {
            $('#leaf-table > tbody > tr').remove();
            $('#leaf-table > thead > tr').remove();
            $("#submit-zone").hide();
        }

    }

    @if(isset($leaf) && isset($acre))
    alterTableLeaf();
    @endif

</script>