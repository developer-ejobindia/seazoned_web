<div class="row">
    <div class="form-group col-md-6">
        <p class="text-success m-b-10 form-label-success">Acerage</p>
        <label for="price-1">First 0.25 acreage price</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="aera_first_acre" name="aera_first_acre" value="{{ isset($acre) ? $acre[0]->service_field_price : "" }}" >
        </div>
          <span class="text text-danger" id="aera_first_acre_err"> </span>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="price-1">Next each 0.25 acreage increase price</label>
        <div class="input-group">
            <span class="input-group-addon" id="price-1">$</span>
            <input type="number" step="0.01" min="0" class="form-control" aria-describedby="price-1" id="aera_next_acre" name="aera_next_acre" value="{{ (isset($acre) && count($acre) > 1) ? $acre[1]->service_field_price - $acre[0]->service_field_price : "" }}" >
        </div>
          <span class="text text-danger" id="aera_next_acre_err"> </span>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="price-1">Max size of lawn you are willing to service in acres</label>
        <div class="input-group">
            <!--<span class="input-group-addon" id="price-1"></span>-->
            <input type="number" class="form-control" aria-describedby="price-1" step="0.25" min="0.25" id="aera_acre_limit" name="aera_acre_limit" value="{{ isset($acre) ? count($acre)/4 : "" }}" >
        </div>
         <span class="text text-danger" id="aera_acre_limit_err"> </span>
    </div>
</div>




<div class="price-preview">
    <p class="text-success m-b-10 form-label-success" onclick="alterTableAera()"><a href="javascript:void(0)">Click here to Preview Pricing</a></p>

    <table class="table table-sm custom-table" id="aeration-table">
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
<div class="card card-no-radius m-b-30" id="recurring_div_4">
    <!--<div class="card-header card-header-success">-->
        <!--<h4 class="m-0 medium">Services</h4>-->
    <!--</div>-->
    <div class="card-block custom-form p-0">
<!--        <div class="row m-0 p-y-10">
            <div class="col-6 medium">-->
            <!--<input type='checkbox' name='service_chk[]' id='service_recurring_4' <?php if(isset($service_prices)){echo "checked";} ?>>--> 
                <!--Recurring Services-->
            <!--</div>-->
        <!--</div>-->
        <div id="recurring_service_div_4" style="display:none;">
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
                        <input type="number" class="form-control recurring_services" name="recurring_services[4][<?php echo $index; ?>]" id="recurring_services_4_<?php echo $index; ?>" value='<?php if(isset($service_prices)){echo $service_prices[$index]->discount_price;}else{echo "0";} ?>'/>
                    </div>
                </div>
            </div>
            <!--<hr class="m-0">-->                
        <?php } ?>
        </div> 
        <input type="hidden" name="recurring_services[4][3]" id="recurring_services_4_3"  value='0'/>
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
    <span class="text text-danger text-center" id="recurring_services_err_4"> </span>
</div>
</div>
<script type="text/javascript">

     $("#service_recurring_4").click(function(){
        if($("#service_recurring_4").is(':checked')){
            $("#recurring_service_div_4").show();
        } else {
            $("#recurring_service_div_4").hide();
        }
    });                               

    function alterTableAera() {
        

        $('#aeration-table').toggle();
        if ($("#aera_acre_limit").val() != "" && $("#aera_first_acre").val() != "" && $("#aera_next_acre").val() != "") {

            $('#aeration-table > tbody > tr').remove();
            $('#aeration-table > thead > tr').remove();

            $('#aeration-table > thead').append('' +
                '<tr>\n' +
                '            <th class="medium">Acreage</th>\n' +
                '            <th class="medium">Price</th>\n' +
                '        </tr>');

            var aera_acre_limit = parseFloat($("#aera_acre_limit").val()) / 0.25;
            var aera_first_acre = parseFloat($("#aera_first_acre").val());
            var aera_next_acre = parseFloat($("#aera_next_acre").val());
            var price = 0;

            for (var i = 1; i <= aera_acre_limit; i++) {
                var acre = 0.25 * i;
                if (i == 1)
                    price += aera_first_acre;
                else
                    price += aera_next_acre;

                $('#aeration-table > tbody:last-child').append('<tr>' +
                    '    <td>' + (acre - 0.25) + ' - ' + acre + ' acre</td>' +
                    '    <td>$ ' + price + '</td>' +
                    '</tr>'
                );

            }
            $("#submit-zone").show();
        } else {
            $('#aeration-table > tbody > tr').remove();
            $('#aeration-table > thead > tr').remove();
            $("#submit-zone").hide();
        }

    }


    @if(isset($acre))
    alterTableAera();
    @endif

</script>