
<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
<style>
.checkbox-animated {
  position: relative;
  margin-top: 10px;
  margin-bottom: 10px;
}
.checkbox-animated input[type="checkbox"] {
  display: none;
}
.checkbox-animated input[type="checkbox"]:disabled ~ label .box {
  border-color: #777;
  background-color: #e6e6e6;
}
.checkbox-animated input[type="checkbox"]:disabled ~ label .check {
  border-color: #777;
}
.checkbox-animated input[type="checkbox"]:checked ~ label .box {
  opacity: 0;
  -webkit-transform: scale(0) rotate(-180deg);
  -moz-transform: scale(0) rotate(-180deg);
  transform: scale(0) rotate(-180deg);
}
.checkbox-animated input[type="checkbox"]:checked ~ label .check {
  opacity: 1;
  -webkit-transform: scale(1) rotate(45deg);
  -moz-transform: scale(1) rotate(45deg);
  transform: scale(1) rotate(45deg);
}
.checkbox-animated label {
  cursor: pointer;
  padding-left: 28px;
  font-weight: normal;
  margin-bottom: 0;
}
.checkbox-animated label span {
  display: block;
  position: absolute;
  left: 0;
  -webkit-transition-duration: 0.3s;
  -moz-transition-duration: 0.3s;
  transition-duration: 0.3s;
}
.checkbox-animated label .box {
  border: 2px solid #000;
  height: 20px;
  width: 20px;
  z-index: 888;
  -webkit-transition-delay: 0.2s;
  -moz-transition-delay: 0.2s;
  transition-delay: 0.2s;
}
.checkbox-animated label .check {
  top: -7px;
  left: 6px;
  width: 12px;
  height: 24px;
  border: 4px solid #1e3374;
  border-top: none;
  border-left: none;
  opacity: 0;
  z-index: 888;
  -webkit-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  transform: rotate(180deg);
  -webkit-transition-delay: 0.3s;
  -moz-transition-delay: 0.3s;
  transition-delay: 0.3s;
}
.checkbox-animated-inline {
  position: relative;
  margin-top: 10px;
  margin-bottom: 10px;
}
.checkbox-animated-inline input[type="checkbox"] {
  display: none;
}
.checkbox-animated-inline input[type="checkbox"]:disabled ~ label .box {
  border-color: #777;
  background-color: #e6e6e6;
}
.checkbox-animated-inline input[type="checkbox"]:disabled ~ label .check {
  border-color: #777;
}
.checkbox-animated-inline input[type="checkbox"]:checked ~ label .box {
  opacity: 0;
  -webkit-transform: scale(0) rotate(-180deg);
  -moz-transform: scale(0) rotate(-180deg);
  transform: scale(0) rotate(-180deg);
}
.checkbox-animated-inline input[type="checkbox"]:checked ~ label .check {
  opacity: 1;
  -webkit-transform: scale(1) rotate(45deg);
  -moz-transform: scale(1) rotate(45deg);
  transform: scale(1) rotate(45deg);
}
.checkbox-animated-inline label {
  cursor: pointer;
  padding-left: 28px;
  font-weight: normal;
  margin-bottom: 0;
}
.checkbox-animated-inline label span {
  display: block;
  position: absolute;
  left: 0;
  -webkit-transition-duration: 0.3s;
  -moz-transition-duration: 0.3s;
  transition-duration: 0.3s;
}
.checkbox-animated-inline label .box {
  border: 2px solid #000;
  height: 20px;
  width: 20px;
  z-index: 888;
  -webkit-transition-delay: 0.2s;
  -moz-transition-delay: 0.2s;
  transition-delay: 0.2s;
}
.checkbox-animated-inline label .check {
  top: -7px;
  left: 6px;
  width: 12px;
  height: 24px;
  border: 2px solid #bada55;
  border-top: none;
  border-left: none;
  opacity: 0;
  z-index: 888;
  -webkit-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  transform: rotate(180deg);
  -webkit-transition-delay: 0.3s;
  -moz-transition-delay: 0.3s;
  transition-delay: 0.3s;
}
.checkbox-animated-inline.checkbox-animated-inline {
  display: inline-block;
}
.checkbox-animated-inline.checkbox-animated-inline + .checkbox-animated-inline {
  margin-left: 10px;
}
</style>

<style type="text/css">
  	label.error {
	  /* remove the next line when you have trouble in IE6 with labels in list */
	  color: red;
	  font-style: italic
  	}

  	.alert-ignore{
	    font-size:12px;
	    border-color: #b38d41;
  	}
  	small{
	    color:orange;
	    font-weight: bold;
	}
	.btnbtn{
       padding-left:0px;
    }

	.btnval {
	    width: 9%;
	    font-size: 11px;
	    height: auto;
	    padding-left: 3px;
	    margin-left: 5px;
	    background: #ffffff;
	    border-color: #373737;
	    border-radius: 6px;
	    color: #373737;
	    font-weight: bold;
	}

	.btnval1 {
	    width: 9%;
	    font-size: 11px;
	    height: auto;
	    padding-left: 3px;
	    margin-left: 5px;
	    background: #ffffff;
	    border-color: #373737;
	    border-radius: 6px;
	    color: #373737;
	    font-weight: bold;
	}

	.btn-group, .btn-group-vertical {
	    position: relative;
	    display: inline-block;
	    vertical-align: middle;
	    padding-bottom: 10px;
	}
	.close{
	  margin-top: -2%;
	}
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Add Buy Order Triggers</h1>

  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
        <li><a href="<?php echo SURL; ?>admin/buy_orders/">Buy Order Listing</a></li>
        <li class="active"><a href="#">Add Buy Order</a></li>
    </ul>
  </div>

  <div class="innerAll spacing-x2">

        <?php
if ($this->session->flashdata('err_message')) {
    ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('err_message'); ?></div>
        <?php
}
if ($this->session->flashdata('ok_message')) {
    ?>
        <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('ok_message'); ?></div>
        <?php
}
?>

        <div class="row">
          <div class="col-md-6">
            <div class="widget widget-inverse">
                <form class="form-horizontal margin-none" method="post" action="<?php echo SURL ?>admin/buy_orders/add_buy_order_process_trigers" class="form-horizontal margin-none" id="validateSubmitForm">
                <div class="widget-body">

                  <!-- Row -->
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Coin</label>
                        <select name="coin" id="coin" class="form-control" required>
                          <?php
if (count($coins_arr) > 0) {
    foreach ($coins_arr as $row) {
        if ($row['symbol'] == 'NCASHBTC') {continue;}
        ?>

                              <option value="<?php echo $row['symbol']; ?>" <?php if ($this->session->userdata('global_symbol') == $row['symbol']) {
            ?> selected <?php
}?>><?php echo $row['symbol']; ?></option>
                              <?php

    }
}?>
                        </select>
                      </div>
                    </div>
                  </div>

                    <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Select Triggers</label>
                        <select name="trigger_type" id="trigger_type" class="form-control" required="required">
                              <option value="">Select Triggers</option>
                                <?php if ($this->session->userdata('special_role') == 1) {?>
                                <option value="market_trend_trigger">market_trend_trigger</option>
                                <option value="trigger_1">trigger_1</option>
                                <option value="trigger_2">trigger_2</option>
                                <option value="box_trigger_3">Box Trigger_3</option>
                                <option value="rg_15">Trigger rg_15</option>
                                <option value="barrier_trigger">barrier_trigger</option>
                                <option value="barrier_percentile_trigger">barrier_percentile_trigger</option>
                                <?php } else {
                                ?>
                                <option value="barrier_trigger">barrier_trigger</option>
                                <option value="barrier_percentile_trigger">barrier_percentile_trigger</option>
                                <?php if (isset($_GET['trigger7'])) {
                                  ?><option value="barrier_percentile_trigger">barrier_percentile_trigger</option>
                                  <?php
                                }
                                }?>
                        </select>
                      </div>
                    </div>
                   </div>


                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Select Mode</label>
                          <select name="order_mode" id="order_mode" class="form-control" required="required">
                          <option value="" selected="selected">Select Mode</option>
                          <?php if ($this->session->userdata('global_mode') == 'live') {
                            echo '<option value="live">RealTime Live</option>';
                            } else {
                            echo '<option value="test_simulator" >Simulator Test</option>
                            <option value="test_live">RealTime Test</option>';
                            }?>
                          </select>
                      </div>
                    </div>
                  </div>

                    
                    <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-8">
                        <label class="control-label">Select Desired Profit Percentage</label>
                        <select name="sell_percentage" id="defined_sell_percentage" class="form-control">
                        <option value="1000" selected="selected">Select Percentage</option>
                        <?php
                        for ($i = 1; $i <= 10; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                        <?php
                        }
                        ?>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        </select>
                        </div>
                        <div class="form-group col-md-4 decimal" style="padding-left:35px; display:none;">
                          <label class="control-label">Decimal</label>
                          <input type="number" id="decimal" name="decimal" required="required" class="form-control" onchange="setTwoNumberDecimal22()" step="0.01" max="0.99" min="0.00" value="0.00" />
                        <!-- <select class="form-control" id="decimal" name="decimal">
		                     <option value="0.00" <?php if ($fraction == 0) {echo "selected";}?>>0.00</option>
		                     <option value="0.25" <?php if ($fraction == 0.25) {echo "selected";}?>>0.25</option>
		                     <option value="0.50" <?php if ($fraction == 0.5) {echo "selected";}?>>0.50</option>
		                     <option value="0.75" <?php if ($fraction == 0.75) {echo "selected";}?>>0.75</option>
		                   </select> -->
                          <script type="text/javascript">
                            function setTwoNumberDecimal22(event) {
                                $('#decimal').val(parseFloat($('#decimal').val()).toFixed(2));
                            }
                          </script>
                        </div>
                        <input type="hidden" id="sell_per" name="defined_sell_percentage" value="1000">
                    </div>
                  </div>

                  <div class="row">
                  <div class="col-md-12">
                  <div class="form-group col-md-12">
                  <label class="control-label">Select Stop Loss</label>
                  <select name="stop_loss_rule" id="stop_loss" class="form-control" required="required">
                  <option value="">select stop loss</option>
                  <option value="stop_loss_rule_big_wall" >Stop Loss Rule Big Wall </option>
                  <option value="custom_stop_loss" >Custom Stop Loss</option>
                  <option value="aggrisive_define_percentage_followup" >Aggrisive Define Percentage Followup </option>
                  </select>
                  </div>
                  </div>
                  </div>


                  <div class="row show_hide_cls"  style="display:none">
                    <div class="col-md-12">
                    <div class="form-group col-md-8">
                      <label class="control-label">(Set custom stop loss percentage behind buy price)</label>
                        <select name="custom_stop_loss_percentage_frst" id="custom_stop_loss_percentage" class="form-control" required="required">
                            <option value="">select percentage</option>
                          <?php
                          for ($i = 1; $i <= 100; $i++) {
                          ?>
                          <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                          <?php
                          }
                          ?>
                      </select>
                    </div>

                      <div class="col-md-4 loss_decimal" style="display: none;">
                        <label class="control-label">Decimal</label>
                        <input type="number" id="loss_decimal" name="ldecimal" required="required" class="form-control" onchange="setTwoNumberDecimal2()" step="0.01" max="0.99" min="0.00" value="0.00" />

                        <!-- <select class="form-control" id="loss_decimal" name="ldecimal">
                         <option value="0.00" <?php if ($fraction == 0) {echo "selected";}?>>0.00</option>
                         <option value="0.25" <?php if ($fraction == 0.25) {echo "selected";}?>>0.25</option>
                         <option value="0.50" <?php if ($fraction == 0.5) {echo "selected";}?>>0.50</option>
                         <option value="0.75" <?php if ($fraction == 0.75) {echo "selected";}?>>0.75</option>
                       </select> -->
                      </div>
                      <input type="hidden" id="loss_per" name="custom_stop_loss_percentage" value="1000">

                      <script type="text/javascript">
                            function setTwoNumberDecimal2(event) {
                                $('#loss_decimal').val(parseFloat($('#loss_decimal').val()).toFixed(2));
                            }
                          </script>
                      <script>
                        $("body").on("change","#custom_stop_loss_percentage",function(e) {
                                   var dec = parseFloat($('#loss_decimal').val());
                                   var sell = parseFloat($('#custom_stop_loss_percentage').val());
                                   var total = sell + dec;
                                 if ($(this).val() != '1000') {
                                   $('#loss_per').val(parseFloat(total));
                                   $('.loss_decimal').show();
                                 }else{
                                   $('#loss_per').val(parseFloat(total));
                                   $('.loss_decimal').hide();
                                 }
                        });

                         $(document).on('change','#loss_decimal',function(){
                         var sell = parseFloat($('#custom_stop_loss_percentage').val());
                         var dec = parseFloat($('#loss_decimal').val());

                         var total = sell + dec;

                         $('#loss_per').val(parseFloat(total));


                         })
                      </script>
                    </div>

                  <div class="col-md-12">
                    <div class="form-group col-md-12">
                      <label class="control-label">(When desire % Meet your your selected secondary stop loss rules will be active ))</label>
                        <select name="activate_stop_loss_profit_percentage" id="activate_stop_loss_profit_percentage" class="form-control" required="required">
                           <option value="">select percentage</option>
                            <?php
                            for ($i = 1; $i <= 20; $i++) {
                            ?>
                                <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <span id="msg_secondry_stop_loss" style="color:red;display:none"> Please select profit % for activate secondy stop loss </span>
                  </div>
                    
                    
                  <!-- %%%%%%%%%%% secondary stop loss  %%%-->
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Select secondary stop loss rules</label>
                        <select name="secondary_stop_loss_rule" id="secondary_stop_loss_rule" class="form-control" >
                      
                        </select>
                      </div>
                    </div>
                  </div>
                  <!-- %%%%%%%%%%% End of secondary stop loss %%% -->

                  
                  </div>

                 

                   <div class="row order_type" style="display:none">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Order Type</label>
                        <select name="order_type" class="form-control order_type">
                          <option value="market_order">Market Order</option>
                          <!-- <option value="limit_order">Limit Order</option>
                          <option value="stop_loss_limit_order">Stop loss limit Order</option>
                          <option value="stop_loss_market_order">Stop loss Market Order</option> -->
                        </select>
                      </div>
                    </div>
                  </div>


                   <!-- <div class="row show_hide_tip" style="display: none;">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                         <label><input type="checkbox" name="buy_one_tip_above"  value="yes" >Send order for Buy one tick Above</label>
                         <br>
                          <label><input type="checkbox" name="sell_one_tip_below" value="yes" >Send order for sell one tick below</label>

                      </div>
                    </div>
                  </div> -->

                  <div class="row show_hide_tip" style="display: none;">
                      <div class="checkbox-animated">
                          <input id="checkbox_animated_4" name="sell_one_tip_below" value="yes" type="checkbox">
                          <label for="checkbox_animated_4">
                              <span class="check"></span>
                              <span class="box"></span>
                              Send order for sell one tick below
                          </label>
                      </div>
                      <div class="checkbox-animated-inline">
                          <input id="checkbox_animated_5" name="buy_one_tip_above" value="yes" type="checkbox">
                          <label for="checkbox_animated_5">
                              <span class="check"></span>
                              <span class="box"></span>
                              Send order for buy one tick above
                          </label>
                      </div>
                  </div>


                  


                  <!-- %%%%%%% order Level %%%%%%%%%%%%%%%%% -->
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-8">
                        <label class="control-label">Select Order Rules</label>
                        <select name="order_level" id="order_level" class="form-control" required = "true">
                        <option value="level_1" selected>Rule 1</option>
                        <option value="level_2">Rule 2</option>
                        <option value="level_3">Rule 3</option>
                        <option value="level_4">Rule 4</option>
                        <option value="level_5">Rule 5</option>

                        <option value="level_6">Rule 6</option>
                        <option value="level_7">Rule 7</option>
                        <option value="level_8">Rule 8</option>
                        <option value="level_9">Rule 9</option>
                        <option value="level_10">Rule 10</option>

                        <option value="level_11">Rule 11</option>
                        <option value="level_12">Rule 12</option>
                        <option value="level_13">Rule 13</option>
                        <option value="level_14">Rule 14</option>
                        <option value="level_15">Rule 15</option>
                        
                        </select>
                        </div>
                    </div>
                  </div>
                  <!-- End of order Rule %%%%%%%%%%%%%%%%%% -->
                  <div class="row">
                    <div class="col-md-12">
                      <div class="checkbox-animated">
                          <input id="checkbox_animated_1" name="lth_functionality" value="yes" type="checkbox">
                          <label for="checkbox_animated_1">
                              <span class="check"></span>
                              <span class="box"></span>
                              Enable Longterm Hold Functionality (If Stoploss make the trade LTH)
                          </label>
                      </div>
                    </div>
                  </div>

                  <?php
                $special_role = $this->session->userdata('special_role');
                if ($special_role == 1) {
                    ?>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="checkbox-animated">
                          <input id="checkbox_animated_2" name="un_limit_child_orders" value="yes" type="checkbox">
                          <label for="checkbox_animated_2">
                              <span class="check"></span>
                              <span class="box"></span>
                                if(check unlimited child orders will be buy if sufficient balance )
                          </label>
                      </div>
                    </div>
                  </div>

                  <?php }?>

                  <div class="row percentage_tip" style="display: none;">
                      <div class="checkbox-animated">
                          <label class="control-label" for="lth_profit">Enter Desired Profit Percentage for Long Term Pool</label>
                          <input type="number" id="lth_profit" name="lth_profit" step="0.1" min="0" max="100" class="form-control">
                      </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-8">
                        <label class="control-label">Quantity</label>
                        <input type="number" id="quantity" name="quantity" required="required" class="form-control" onchange="setTwoNumberDecimal()" step="any"/>
                        <script type="text/javascript">
                          function setTwoNumberDecimal(event) {
                              $('#quantity').val(parseFloat($('#quantity').val()).toFixed(2));
                          }
                        </script>

                        <input type="hidden" id="quantity_check_min" name="quantity_check_min">
                        <input type="hidden" id="quantity_check_max" name="quantity_check_max">
                      </div>
                      <div class="form-group col-md-4" style="padding-left:35px;">
                        <label class="control-label">Amount In USD</label>
                          <div class="label label-success" id="usd" style="height: 33px;padding-top: 9px;font-size: 15px;"></div>
                      </div>

                    </div>
                  </div>
                  <div class="col-md-12" id="quantitydv">

                    </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Inactive Date</label>
                        <input type="text" id="inactive_time" name="inactive_time" class="form-control datetime_picker">
                      </div>
                    </div>
                  </div>
                  <script type="text/javascript">
                        $(function () {
                            $('.datetime_picker').datetimepicker();
                        });
                    </script>
                  <!-- Form actions -->
                  <div class="form-actions">
                    <button class="btn btn-success" id="add_order_p" type="submit"><i class="fa fa-check-circle"></i> Add Order Trigger </button>
                  </div>
                  <!-- // Form actions END -->

                </div>
                </form>
            </div>
          </div>
        </div>
  </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Trigger Order</h4>
      </div>
      <div class="modal-body">
        <p id="modal_p"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){

    $(document).on('change','#trigger_type',function(){
      var trigger_type = $(this).val();
      var html = '';
      var html_secondary = '';
      if(trigger_type == 'barrier_percentile_trigger'){
        html +='<option value="percentile_stop_loss">percentile_stop_loss</option>';
        html +='<option value="custom_stop_loss" >Custom Stop Loss</option>';
        
        html_secondary +='<option value="">select stop loss</option><option value="percentile_stop_loss">percentile_stop_loss</option>';
        
      }else{
        html +='<option value="">select stop loss</option>';
        html +='<option value="stop_loss_rule_big_wall" >Stop Loss Rule Big Wall </option>\
          <option value="custom_stop_loss" >Custom Stop Loss</option>\
          <option value="aggrisive_define_percentage_followup" >Aggrisive Define Percentage Followup </option>';


          html_secondary += '<option value="">select stop loss</option><option value="stop_loss_rule_big_wall" >Stop Loss Rule Big Wall </option>\
          <option value="custom_stop_loss" >Custom Stop Loss</option>\
          <option value="aggrisive_define_percentage_followup" >Aggrisive Define Percentage Followup </option>';


      }
      $('#stop_loss').empty().append(html);
      $('#secondary_stop_loss_rule').empty().append(html_secondary);
      
    })


    $(document).on('change','#secondary_stop_loss_rule',function(){
       var secondary_stop_loss_rule = $(this).val();

       if(secondary_stop_loss_rule == ''){
        $("#add_order_p").attr("disabled", false);
        $('#msg_secondry_stop_loss').hide();
       }else{

        var activate_stop_loss_profit_percentage = $('#activate_stop_loss_profit_percentage').val();
        if(activate_stop_loss_profit_percentage ==''){
          $("#add_order_p").attr("disabled", true);
          $('#msg_secondry_stop_loss').show();
        }else{
          $("#add_order_p").attr("disabled", false);
          $('#msg_secondry_stop_loss').hide();
        }

        
       }
    })


    $(document).on('change','#activate_stop_loss_profit_percentage',function(){
      var activate_stop_loss_profit_percentage = $('#activate_stop_loss_profit_percentage').val();
        if(activate_stop_loss_profit_percentage ==''){
          $("#add_order_p").attr("disabled", true);
          $('#msg_secondry_stop_loss').show();
        }else{
          $("#add_order_p").attr("disabled", false);
          $('#msg_secondry_stop_loss').hide();
        }
    })

    $(document).on('change','#order_mode',function(){

      if($(this).val()== 'live'){
        $('.order_type').show();
      }else{
        $('.order_type').hide();
      }
    })


    $('#trigger_typeComment').change(function(e){



      if ($(this).val() == 'trigger_1') {
        $('#modal_p').html('Coming Soon');
         $('#myModal').modal('show');
      }else if($(this).val() == 'trigger_2'){
        $('#modal_p').html('In Trigger 2, System Checks the last demand candle and calculate the x percentage of that value, then it compares the value with the market price, if the market price is less then or equal to the x value, it will buy your order. after that it will check the market buy price and calculate the y percentage of that value if market crosses that percentage it will automatically sell your order');
         $('#myModal').modal('show');
      }
    })
  });
  calculate_min_notation();
  calculate_max_notation();
  calculate_price_in_usd();
  $("body").on("change","#coin",function(e){
   calculate_min_notation();
   calculate_max_notation();
   calculate_price_in_usd();
  });

  function calculate_min_notation()
  {
    var symbol = $('#coin').val();
    var sss = "1";
    $.ajax({
      type:'POST',
      async: false,
      url:'<?php echo SURL ?>admin/buy_orders/get_min_notation',
      data: {symbol:symbol},
      success:function(response){
       $('#quantity_check_min').val(response);
      }
    });
  }


  function calculate_max_notation()
  {
    var symbol = $('#coin').val();
    var sss = "1";
    $.ajax({
      type:'POST',
      async: false,
      url:'<?php echo SURL ?>admin/buy_orders/get_max_notation',
      data: {symbol:symbol},
      success:function(response){
       $('#quantity_check_max').val(response);
      }
    });
  }

  function calculate_price_in_usd()
  {
    var quantity = $('#quantity').val();
    var symbol = $('#coin').val();
    $.ajax({
      type:'POST',
      async: false,
      url:'<?php echo SURL ?>admin/buy_orders/calculate_amount_in_usd',
      data: {quantity:quantity,symbol:symbol},
      success:function(response){
       $('#usd').html(response);
      }
    });
  }
  $("body").on('change','#quantity',function() {
     calculate_price_in_usd();
   });
   $("body").on('keyup','#quantity',function() {
      var checked_min = parseFloat($('#quantity_check_min').val());
      var checked_max = parseFloat($('#quantity_check_max').val());
      var quantity = parseFloat($('#quantity').val());

      if (quantity < checked_min) {
        $('#quantitydv').html('<div class="alert alert-danger">Minimum Quantity Should Be '+checked_min+' Please Enter Valid Quantity');
        $("#add_order_p").prop("disabled","true");
         // var quantity = $('#quantity').val(checked);
      }else if(quantity > checked_max){
        $('#quantitydv').html('<div class="alert alert-danger">Maximum Quantity Should Be '+checked_max+' Please Enter Valid Quantity');
        $("#add_order_p").prop("disabled","true");
      }else{
         $('#quantitydv').html('');
         $("#add_order_p").removeAttr("disabled");
      }
    });

   $(document).on('change','.order_type',function(){
		var order_type = $(this).val();

		if(order_type == 'limit_order'){
			$('.show_hide_tip').show();
		console.log('in limit order'+order_type);
		}else{
			console.log('in market order'+order_type);
			$('.show_hide_tip').show();
		}
   })


	$(document).on('change','#checkbox_animated_1',function(){

		var ckbox = $(this);
		if(ckbox.is(':checked')){
			$('.percentage_tip').show();
		}else{
			$('.percentage_tip').hide();
		}
   })

   $(document).on('change','#defined_sell_percentage',function(){
     var sell = parseFloat($('#defined_sell_percentage').val());
     var dec = parseFloat($('#decimal').val());

     var total = sell + dec;
   if ($(this).val() != '1000') {
     $('#sell_per').val(parseFloat(total));
     $('.decimal').show();
   }else{
     $('#sell_per').val(parseFloat(total));
     $('.decimal').hide();
   }
   })

   $(document).on('change','#decimal',function(){
   var sell = parseFloat($('#defined_sell_percentage').val());
   var dec = parseFloat($('#decimal').val());

   var total = sell + dec;

   $('#sell_per').val(parseFloat(total));


   })

    $(document).ready(function(){
      $(document).on('change','#stop_loss',function(){
        var stop_loss = $(this).val();
        if(stop_loss == 'custom_stop_loss'){
           $('.show_hide_cls').show();
           $('.loss_decimal').show();
        }else{
          $('.show_hide_cls').hide();
          $('.loss_decimal').hide();
        }
      })
    })


</script>
