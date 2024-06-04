
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
                <form class="form-horizontal margin-none" method="post" action="<?php echo SURL ?>admin/buy_orders/edit_buy_order_process_trigers" class="form-horizontal margin-none" id="validateSubmitForm">
                <div class="widget-body">

                  <!-- Row -->
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Coin</label>
                        <input type="text" class="form-control" id="coin" value="<?php echo $order_arr['symbol'] ?>" readonly>
                      </div>
                    </div>
                  </div>

                    <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Select Triggers</label>
                        <select name="trigger_type" id="trigger_type" class="form-control" required="required">
                              <option value="">Select Triggers</option>
                              <?php if ($this->session->userdata('special_role') == 1) {
    ?>
                                <option value="trigger_1"<?php if ($order_arr['trigger_type'] == 'trigger_1') {echo "selected";}?>>trigger_1</option>
                                <option value="trigger_2"<?php if ($order_arr['trigger_type'] == 'trigger_2') {echo "selected";}?> >trigger_2</option>
                                <option value="box_trigger_3"<?php if ($order_arr['trigger_type'] == 'box_trigger_3') {echo "selected";}?> >Box Trigger_3</option>
                                 <option value="rg_15"<?php if ($order_arr['trigger_type'] == 'rg_15') {echo "selected";}?> >Trigger rg_15</option>
                                 <option value="barrier_trigger"<?php if ($order_arr['trigger_type'] == 'barrier_trigger') {echo "selected";}?> >barrier_trigger</option>
                                <option value="barrier_trigger"<?php if ($order_arr['trigger_type'] == 'barrier_trigger') {echo "selected";}?> >barrier_trigger</option>
                                <option value="barrier_percentile_trigger"<?php if ($order_arr['trigger_type'] == 'barrier_percentile_trigger') {echo "selected";}?> >barrier_percentile_trigger</option>
                              <?php } else {?>
                                <option value="barrier_trigger"<?php if ($order_arr['trigger_type'] == 'barrier_trigger') {echo "selected";}?> >barrier_trigger</option>
                                <option value="barrier_percentile_trigger"<?php if ($order_arr['trigger_type'] == 'barrier_percentile_trigger') {echo "selected";}?> >barrier_percentile_trigger</option>
                                <?php }?>

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
    ?>
                            	<option value="live" <?php if ($order_arr['order_mode'] == 'live') {echo "selected";}?>>RealTime Live</option>
                            <?php } else {
    ?>
                            	<option value="test_simulator" <?php if ($order_arr['order_mode'] == 'test_simulator') {echo "selected";}?> >Simulator Test</option>
                              <option value="test_live" <?php if ($order_arr['order_mode'] == 'test_live') {echo "selected";}?>>RealTime Test</option>
                            <?php }?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                  <div class="col-md-12">
                    <div class="form-group col-md-12">
                      <label class="control-label">Select Order Rule</label>
                      <select name="order_level" id="order_level" class="form-control" required="required">
                          <option value="level_1" <?php if ($order_arr['order_level'] == 'level_1') {echo "selected";}?>>Rule 1</option>
                          <option value="level_2" <?php if ($order_arr['order_level'] == 'level_2') {echo "selected";}?>>Rule 2</option>
                          <option value="level_3" <?php if ($order_arr['order_level'] == 'level_3') {echo "selected";}?>>Rule 3</option>
                          <option value="level_4" <?php if ($order_arr['order_level'] == 'level_4') {echo "selected";}?>>Rule 4</option>
                          <option value="level_5" <?php if ($order_arr['order_level'] == 'level_5') {echo "selected";}?>>Rule 5</option>
                          <option value="level_6" <?php if ($order_arr['order_level'] == 'level_6') {echo "selected";}?>>Rule 6</option>
                          <option value="level_7" <?php if ($order_arr['order_level'] == 'level_7') {echo "selected";}?>>Rule 7</option>
                          <option value="level_8" <?php if ($order_arr['order_level'] == 'level_8') {echo "selected";}?>>Rule 8</option>
                          <option value="level_9" <?php if ($order_arr['order_level'] == 'level_9') {echo "selected";}?>>Rule 9</option>
                          <option value="level_10" <?php if ($order_arr['order_level'] == 'level_10') {echo "selected";}?>>Rule 10</option>
                      </select>
                    </div>
                  </div>
                </div>

                  <div class="row">
                   <div class="col-md-12">
                     <div class="form-group col-md-12">
                       <label class="control-label">Order Type</label>
                       <select name="order_type" class="form-control order_type">
                         <option value="market_order" <?php if ($order_arr['order_type'] == 'market_order') {echo "selected";}?>>Market Order</option>
                       >
                       </select>
                     </div>
                   </div>
                 </div>



                 <div class="row">
                    <div class="col-md-12">
                      <div class="checkbox-animated">
                          <input id="checkbox_animated_1" name="lth_functionality" value="yes" type="checkbox" <?php if ($order_arr['lth_functionality'] == 'yes') {echo "checked";}?>>
                          <label for="checkbox_animated_1">
                              <span class="check"></span>
                              <span class="box"></span>
                              Enable Longterm Hold Functionality (If Stoploss the make the trade LTH)
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
                          <input id="checkbox_animated_2" name="un_limit_child_orders" value="yes" type="checkbox" <?php if ($order_arr['un_limit_child_orders'] == 'yes') {echo "checked";}?>>
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
                          <input type="number" id="lth_profit" name="lth_profit" step="0.25" min="0" max="100" class="form-control" value="<?php echo $order_arr['lth_profit']; ?>">
                      </div>
                  </div>


                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Select Stop Loss</label>
                          <select name="stop_loss_rule" id="stop_loss" class="form-control" required="required">

                          <?php if ($order_arr['trigger_type'] == 'barrier_percentile_trigger'){
                            ?>
                            <option value="">select stop loss</option>
                            <option value="custom_stop_loss" <?php if ($order_arr['stop_loss_rule'] == 'custom_stop_loss') {echo "selected";}?>>Custom Stop Loss</option>
                            
                          <select name="stop_loss_rule" id="stop_loss" class="form-control" required="required">
                            <option value="percentile_stop_loss" <?php if ($order_arr['secondary_stop_loss_rule'] == 'percentile_stop_loss') {echo "selected";}?> >percentile_stop_loss</option>
                            <?php
                          }else{
                            ?>
                            <option value="">select stop loss</option><option value="stop_loss_rule_big_wall" <?php if ($order_arr['stop_loss_rule'] == 'stop_loss_rule_big_wall') {echo "selected";}?>>Stop Loss Rule Big Wall </option>\
                            <option value="custom_stop_loss" <?php if ($order_arr['stop_loss_rule'] == 'custom_stop_loss') {echo "selected";}?>>Custom Stop Loss</option>\
                            <option value="aggrisive_define_percentage_followup" <?php if ($order_arr['stop_loss_rule'] == 'aggrisive_define_percentage_followup') {echo "selected";}?>>Aggrisive Define Percentage Followup </option>
                            <?php
                          } ?>

                          </select>

                      </div>
                    </div>
                  </div>
                  <?php
$percentage1 = $order_arr['custom_stop_loss_percentage'];
$intpart1 = floor($percentage1);
$fraction1 = (float) $percentage1 - $intpart1;
?>

                  <div class="row show_hide_cls"  style="display:none">
                    <div class="col-md-12">
                      <div class="form-group col-md-8">
                        <label class="control-label">(Set custom stop loss percentage behind buy price)</label>
                          <select name="custom_stop_loss_percentage_frst" id="custom_stop_loss_percentage" class="form-control" required="required">
                            <?php
for ($i = 1; $i <= 100; $i++) {
    ?>
                                <option value="<?php echo $i; ?>" <?php if ($intpart1 == $i) {echo "selected";}?>><?php echo $i; ?></option>
                                <?php
}
?>


                          </select>
                      </div>

                      <div class="col-md-4 loss_decimal">
                        <label class="control-label">Decimal</label>
                        <select class="form-control" id="loss_decimal" name="ldecimal">
                         <option value="0.00" <?php if ($fraction1 == 0) {echo "selected";}?>>0.00</option>
                         <option value="0.25" <?php if ($fraction1 == 0.25) {echo "selected";}?>>0.25</option>
                         <option value="0.50" <?php if ($fraction1 == 0.5) {echo "selected";}?>>0.50</option>
                         <option value="0.75" <?php if ($fraction1 == 0.75) {echo "selected";}?>>0.75</option>
                       </select>
                      </div>
                      <input type="hidden" id="loss_per" name="custom_stop_loss_percentage" value="<?=$order_arr['custom_stop_loss_percentage'];?>">
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
                            <option value="">Select percentage</option>
                          <?php
                            for ($i = 1; $i <= 100; $i++) {
                              ?>
                              <option value="<?php echo $i; ?>"  <?php if ($order_arr['activate_stop_loss_profit_percentage'] == $i) {echo "selected";}?>><?php echo $i; ?></option>
                              <?php
                            }
                            ?>

                          </select>
                      </div>
                    </div>


                           <!-- %%%%%%%%%%% secondary stop loss  %%%-->
                            
                       
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Select secondary stop loss rules</label>
                        <select name="secondary_stop_loss_rule" id="secondary_stop_loss_rule" class="form-control" >
                          <?php if ($order_arr['trigger_type'] == 'barrier_percentile_trigger'){
                            ?>
                            <option value="">select stop loss</option><option value="percentile_stop_loss" <?php if ($order_arr['secondary_stop_loss_rule'] == 'percentile_stop_loss') {echo "selected";}?> >percentile_stop_loss</option>
                            <?php
                          }else{
                            ?>
                            <option value="">select stop loss</option>
                            <option value="stop_loss_rule_big_wall" <?php if ($order_arr['secondary_stop_loss_rule'] == 'stop_loss_rule_big_wall') {echo "selected";}?>>Stop Loss Rule Big Wall </option>\
                            <option value="custom_stop_loss" <?php if ($order_arr['secondary_stop_loss_rule'] == 'custom_stop_loss') {echo "selected";}?>>Custom Stop Loss</option>\
                            <option value="aggrisive_define_percentage_followup" <?php if ($order_arr['secondary_stop_loss_rule'] == 'aggrisive_define_percentage_followup') {echo "selected";}?>>Aggrisive Define Percentage Followup </option>
                            <?php
                          } ?>
              
                        </select>
                      </div>
                    </div>
                  </div>
                  <!-- %%%%%%%%%%% End of secondary stop loss %%% -->

                  </div>

                 <div class="row show_hide_tip" style="display: none;">
                     <div class="checkbox-animated">
                         <input id="checkbox_animated_4" name="sell_one_tip_below" value="yes" type="checkbox" <?php if ($order_arr['sell_one_tip_below'] == 'yes') {echo "checked";}?>>
                         <label for="checkbox_animated_4">
                             <span class="check"></span>
                             <span class="box"></span>
                             Send order for sell one tick below
                         </label>
                     </div>
                     <div class="checkbox-animated-inline">
                         <input id="checkbox_animated_5" name="buy_one_tip_above" value="yes" type="checkbox" <?php if ($order_arr['buy_one_tip_above'] == 'yes') {echo "checked";}?>>
                         <label for="checkbox_animated_5">
                             <span class="check"></span>
                             <span class="box"></span>
                             Send order for buy one tick above
                         </label>
                     </div>
                 </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-8">
                        <label class="control-label">Select Desired Profit Percentage</label>
                        <select name="defined_sell_percentage" id="defined_sell_percentage" class="form-control">
                        <option value="1000">Select Percentage</option>
                        <?php
$percentage = $order_arr['defined_sell_percentage'];
$intpart = floor($percentage);
$fraction = (float) $percentage - $intpart;
?>                      <option value="1"<?php if ($intpart == 1) {echo "selected";}?>>1</option>
                        <option value="2"<?php if ($intpart == 2) {echo "selected";}?>>2</option>
                        <option value="3"<?php if ($intpart == 3) {echo "selected";}?>>3</option>
                        <option value="4"<?php if ($intpart == 4) {echo "selected";}?>>4</option>
                        <option value="5"<?php if ($intpart == 5) {echo "selected";}?>>5</option>
                        <option value="6"<?php if ($intpart == 6) {echo "selected";}?>>6</option>
                        <option value="7"<?php if ($intpart == 7) {echo "selected";}?>>7</option>
                        <option value="8"<?php if ($intpart == 8) {echo "selected";}?>>8</option>
                        <option value="9"<?php if ($intpart == 9) {echo "selected";}?>>9</option>
                        <option value="10"<?php if ($intpart == 10) {echo "selected";}?>>10</option>
                        <option value="15"<?php if ($intpart == 15) {echo "selected";}?>>15</option>
                        <option value="20"<?php if ($intpart == 20) {echo "selected";}?>>20</option>
                        <option value="25"<?php if ($intpart == 25) {echo "selected";}?>>25</option>
                        <option value="50"<?php if ($intpart == 50) {echo "selected";}?>>50</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4 decimal" style="padding-left:35px;">
                        <label class="control-label">Decimal</label>
                         <select class="form-control" id="decimal" name="decimal">
                         <option value="0.00" <?php if ($fraction == 0) {echo "selected";}?>>0.00</option>
                         <option value="0.25" <?php if ($fraction == 0.25) {echo "selected";}?>>0.25</option>
                         <option value="0.50" <?php if ($fraction == 0.5) {echo "selected";}?>>0.50</option>
                         <option value="0.75" <?php if ($fraction == 0.75) {echo "selected";}?>>0.75</option>
                       </select>
                        <input type="hidden" id="sell_per" name="defined_sell_percentage" value="<?php echo $order_arr['defined_sell_percentage']; ?>">
                        <script type="text/javascript">
                          function setTwoNumberDecimal22(event) {
                              $('#decimal').val(parseFloat($('#decimal').val()).toFixed(2));
                          }
                        </script>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-8">
                        <label class="control-label">Quantity</label>
                        <input type="number" id="quantity" name="quantity" required="required" class="form-control" onchange="setTwoNumberDecimal()" step="any" value="<?php echo $order_arr['quantity']; ?>" />
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
                          <div class="label label-success" id="usd" style="height: 33px;padding-top: 9px;font-size: 15px;">$ 0.000</div>
                      </div>

                    </div>
                  </div>
                  <div class="col-md-12" id="quantitydv">

                    </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Inactive Date</label>
                        <input type="text" id="inactive_time" value="<?php if (!empty($order_arr['inactive_time'])) {echo $order_arr['inactive_time']->toDatetime()->format("Y-m-d H:i:s");}?>" name="inactive_time" class="form-control datetime_picker">
                      </div>
                    </div>
                  </div>
                  <script type="text/javascript">
                        $(function () {
                            //$('.datetime_picker').datetimepicker();
                        });
                    </script>
                  <!-- Form actions -->
                  <div class="form-actions">
                    <input type="hidden" name="order_id" value="<?php echo $order_arr['_id'] ?>">
                    <input type="hidden" name="old_quantity" value="<?php echo $order_arr['quantity'] ?>">
                    <input type="hidden" name="old_profit" value="<?php echo $order_arr['defined_sell_percentage'] ?>">
                    <button class="btn btn-success" id="add_order_p" type="submit"><i class="fa fa-check-circle"></i> Update Order Trigger </button>
                  </div>
                  <!-- // Form actions END -->

                </div>
                </form>
            </div>
          </div>

                    <?php if (count($order_history_arr) > 0) {
    ?>
          <div class="col-md-6">
            <div class="widget">
              <div class="widget widget-inverse widget-scroll">
                        <div class="widget-head" style="height:46px;">
                          <h4 class="heading" style=" padding-top: 3px;">Order History Log</h4>
                        </div>
                        <div class="widget-body padding-none">
                            <div id="response_market_trading" style="overflow: hidden; outline: none;" tabindex="5000">
                              <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><strong>Message</strong></th>
                                            <th><strong>Created Date</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
$i = 0;
    foreach ($order_history_arr as $value) {
        $i++;
        ?>
                                      <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><!-- <?php echo $value['log_msg']; ?> -->
                                           <?php
//str_ireplace($word, "<span class='hilight'>{$word}</span>", $text)
        if (strpos($value['log_msg'], '<b>SUBMITTED</b>') !== false) {

            echo str_replace('<b>SUBMITTED</b>', "<span style='color:orange;    font-size: 14px;'><b>SUBMITTED</b></span>", $value['log_msg']);
        } elseif (strpos($value['log_msg'], '<b>FILLED</b>') !== false) {

            echo str_replace('<b>FILLED</b>', "<span style='color:green;    font-size: 14px;'><b>FILLED</b></span>", $value['log_msg']);
        } elseif (stripos($value['log_msg'], 'Error') !== false) {

            echo str_replace('Error', "<span style='color:red;font-size: 14px;'><b>ERROR</b></span>", $value['log_msg']);
        } else {
            echo $value['log_msg'];
        }

        //echo $value['log_msg']; ?>
                                        </td>
                                        <td><?php echo $value['created_date']; ?></td>
                                      </tr>
                                      <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
          </div>
          <?php }?>



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

    
    //%%%%%%%%%%%%% -- Dynamic trigger type -- %%%%%%%%%%%%%%%%%
    
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

    //%%%%%%%%%%%%%%%%%% -- End of dynamic trigger type --%%%%%%%


    $('#trigger_typeComment').change(function(e){
      if ($(this).val() == 'trigger_1') {
        $('#modal_p').html('Coming Soon');
         $('#myModal').modal('show');
      }else if($(this).val() == 'trigger_2'){
        $('#modal_p').html('In Trigger 2, System Checks the last demand candle and calculate the x percentage of that value, then it compares the value with the market price, if the market price is less then or equal to the x value, it will buy your order. after that it will check the market buy price and calculate the y percentage of that value if market crosses that percentage it will automatically sell your order');
         $('#myModal').modal('show');
      }
    })

  });//%%%%%%%%% -- End of ready function -- %%%%%%%%%5 
</script>
<script>
$(document).ready(function(){
  var order_type = $('.order_type').val();
  if(order_type == 'limit_order'){
    $('.show_hide_tip').show();
  console.log('in limit order'+order_type);
  }else{
    console.log('in market order'+order_type);
    $('.show_hide_tip').hide();
  }


  var ckbox = $('#checkbox_animated_1');
    if(ckbox.is(':checked')){
      $('.percentage_tip').show();
    }else{
      $('.percentage_tip').hide();
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
});
$(document).on('change','#checkbox_animated_1',function(){

    var ckbox = $(this);
    if(ckbox.is(':checked')){
      $('.percentage_tip').show();
    }else{
      $('.percentage_tip').hide();
    }
   })
$(document).on('change','#defined_sell_percentage',function(){
if ($(this).val() != '1000') {

  var sell = parseFloat($(this).val());
  var dec = parseFloat($('#decimal').val());
  var total = sell + dec;
  $('#sell_per').val(parseFloat(total));
  $('.decimal').show();
}else{
  $('#sell_per').val(parseFloat($(this).val()));
  $('.decimal').hide();
}
});

$(document).on('change','#decimal',function(){
var sell = parseFloat($('#defined_sell_percentage').val());
var dec = parseFloat($('#decimal').val());

var total = sell + dec;

$('#sell_per').val(parseFloat(total));


})

calculate_min_notation();
calculate_max_notation();
calculate_price_in_usd();
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

 $("body").on('change','#quantity',function() {
    calculate_price_in_usd();
  });


   $(document).ready(function(){
      var stop_loss = $('#stop_loss').val();
      if(stop_loss == 'custom_stop_loss'){
        $('.show_hide_cls').show();
      }else{
        $('.show_hide_cls').hide();
      }

      $(document).on('change','#stop_loss',function(){
        var stop_loss = $(this).val();
        if(stop_loss == 'custom_stop_loss'){
           $('.show_hide_cls').show();
        }else{
          $('.show_hide_cls').hide();
        }
      })
    })



</script>
