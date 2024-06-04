<link rel="stylesheet" href="<?php echo ASSETS;?>cdn_links/bootstrap-multiselect-master/dist/css/bootstrap-multiselect.css" />
<script src="<?php echo ASSETS;?>cdn_links/bootstrap-multiselect-master/dist/js/bootstrap-multiselect.js"></script>




<style type="text/css">
  .hide_every_thing {
    display:none;
  }
  .radio-group label {
   overflow: hidden;
} .radio-group input {
    /* This is on purpose for accessibility. Using display: hidden is evil.
    This makes things keyboard friendly right out tha box! */
   height: 1px;
   width: 1px;
   position: absolute;
   top: -20px;
} .radio-group .not-active  {
   color: #3276b1;
   background-color: #fff;
}


.buy_sell_box {
    float: left;
    width: 100%;
    background: #fff;
    margin-bottom: 30px;
    box-shadow: 0 0 27px 1px rgba(0,0,0,0.1);
    padding: 25px;
}

.buy_sell_box_heading {
    float: left;
    width: 100%;
    text-transform: uppercase;
    color: #000;
    padding-bottom: 15px;
    font-size: 18px;
    border-bottom: 1px solid #ccc;
    margin-bottom: 24px !important;
}


  .onoffswitch {
    position: relative; width: 90px;
    -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
}
.onoffswitch-checkbox {
    display: none;
}
.onoffswitch-label {
    display: block; overflow: hidden; cursor: pointer;
    border: 2px solid #999999; border-radius: 20px;
}
.onoffswitch-inner {
    display: block; width: 200%; margin-left: -100%;
    transition: margin 0.3s ease-in 0s;
}
.onoffswitch-inner:before, .onoffswitch-inner:after {
    display: block; float: left; width: 50%; height: 30px; padding: 0; line-height: 30px;
    font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
    box-sizing: border-box;
}
.onoffswitch-inner:before {
    content: "ON";
    padding-left: 10px;
    background-color: #34A7C1; color: #FFFFFF;
}
.onoffswitch-inner:after {
    content: "OFF";
    padding-right: 10px;
    background-color: #EEEEEE; color: #999999;
    text-align: right;
}
.onoffswitch-switch {
    display: block; width: 18px; margin: 6px;
    background: #FFFFFF;
    position: absolute; top: 0; bottom: 0;
    right: 56px;
    border: 2px solid #999999; border-radius: 20px;
    transition: all 0.3s ease-in 0s; 
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
    margin-left: 0;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
    right: 0px; 
}

</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Settings</h1>
  <div class="bg-white innerAll border-bottom">
   <ul class="menubar">

  </ul>
  </div>
  <div class="innerAll spacing-x2">


      <!-- Widget -->
    <div class="widget widget-inverse">
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
  
    <div class="widget widget-inverse">
        <div class="widget-body bg-white">
          <h4>Trigger Global settings</h4>
            

            <form action="<?php echo SURL; ?>admin/settings/triggers_global_setting" class="formhorizontal" id="trigger_setting_id" method="post" >
        <div class="widget-body">
                      <div class="row">
                          
        
        
                      
                            <!-- Score for box trigger -->
                            <div class="col-md-12 score_show_hide" style="display: none;">
                              <div class="form-group col-md-12">
                                <label class="control-label" for="hour">Score</label>
                                 <input type="text" class="form-control" name="box_trigger_score" id="box_trigger_score">
                              </div>
                            </div>
                            <!-- End of box trigger score --->

                            <!-- -->
                            <!--  Cancel trade -->
                             <div class="col-md-12 factor_show_hide" style="display: none;">
                              <div class="form-group col-md-12">
                                <label class="control-label" for="hour">stop loss rule_2 apply factor</label>
                                 <input type="text" class="form-control" name="apply_factor" id="apply_factor">
                              </div>
                            </div>
                            <!--  End of cancel trade-->
        
                            <div class="show_hide_for_barrier_trigger"> 
                            <div class="col-md-12 aggressive_stop_3_rule_show_hide" style="display: none;">
                              <div class="form-group col-md-12">
                                <label class="control-label" for="hour">Aggressive_stop_3 Rules</label>
                                <select class="form-control stop_loss_rule_3" name="stop_loss_rule_3">
                                   <option value="">select aggressive Rule</option>
                                   <option value="2_percent_loss">2% Loss</option>
                                   <option value="3_percent_loss">3% Loss</option>
                                   <option value="4_percent_loss">4% Loss</option>
                                </select>
                              </div>
                            </div>
        
        
        
               
        
                            <div class="hide_show_for_rg_15">
                            <!--  Cancel trade -->
                            <div class="col-md-12">
                              <div class="form-group col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" class="checkbox" value="cancel" id="cancel_trade" name="cancel_trade">Cancel Trade</label>
                                </div>
                              </div>
                            </div>
        
        
        
                            
        
                             <div class="col-md-12 look_back_hour_show_hide" style="display: none;">
                              <div class="form-group col-md-12">
                                <label class="control-label" for="hour">look back hour to cancel trade</label>
                                 <input type="text" class="form-control" name="look_back_hour" id="look_back_hour">
                              </div>
                            </div>
                            <!--  End of cancel trade-->
        
                               <!--  Cancel trade -->
                            <div class="col-md-12">
                              <div class="form-group col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="yes" id="bottom_demand_rejection" name="bottom_demand_rejection">Bottom Demand Rejection</label>
                                </div>
                              </div>
                            </div>
        
                             <div class="col-md-12">
                              <div class="form-group col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="yes" id="bottom_supply_rejection" name="bottom_supply_rejection">Bottom Supply Rejection</label>
                                </div>
                              </div>
                            </div>
        
                            <div class="col-md-12">
                              <div class="form-group col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="yes" id="check_high_open" name="check_high_open">Check Heigh Open</label>
                                </div>
                              </div>
                            </div>
        
        
                            <div class="col-md-12">
                              <div class="form-group col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="yes" id="is_previous_blue_candle" name="is_previous_blue_candle">is_previous_blue_candle</label>
                                </div>
                              </div>
                            </div>
                            <!--  End of cancel trade-->
                          </div><!--  End of hide and show-->
        
        
                          </div>
                          <!--  End  Trigger_1 -->
        
        
                          </div>
                  <!--  End  -->

                </div> <!--  End of if not barrier trigger-->

              <!-- Barrier Trigger -->
              <div class="show_barrier_trigger" style="display: none;">
               


             

                <div class="global_setting buy_sell_box ">
                  <div class="col-md-12">
                      <h2 class="buy_sell_box_heading">Global Setting</h2>
                    </div> 
                  <div class="col-md-12 hide_every_thing">
                      <div class="form-group col-md-12">
                        <label class="control-label" for="hour">Select Status </label>
                          <select class="form-control" id="status" name="status[]" multiple>
                            <option value="LL" >LL</option>
                             <option value="LH">LH</option>
                             <option value="HH">HH</option>
                             <option value="HL">HL</option>
                        </select>
                      </div>
                  </div>



                  <div class="form-group col-md-12 hide_every_thing">
                    <label class="control-label" for="hour">Quantity for very strong Barrier (The Quantity of the last barrier price should be greater form the defined quantity)</label>
                    <input type="text" step="any" class="form-control" name="very_strong_barrier" id="very_strong_barrier" value="">
                  </div> 


                  <div class="form-group col-md-12 hide_every_thing">
                    <label class="control-label" for="hour">Quantity for  strong Barrier (The Quantity of the last barrier price should be greater form the defined quantity)</label>
                    <input type="text" step="any" class="form-control" name="strong_barrier" id="strong_barrier" value="">
                  </div> 

                  <div class="form-group col-md-12 hide_every_thing" >
                    <label class="control-label" for="hour">Quantity for  Weak Barrier (The Quantity of the last barrier price should be greater form the defined quantity)</label>
                    <input type="text" step="any" class="form-control" name="weak_barrier" id="weak_barrier" value="">
                  </div> 

                </div>


                <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#home">Buy</a></li>
                  <li><a data-toggle="tab" href="#menu1">Sell</a></li>
                </ul>

                <div class="tab-content">
                  <div id="home" class="tab-pane fade in active">
                  <!-- Buy part -->

                      <div class="buy_part buy_sell_box">
                        <div class="col-md-12">
                          <h2 class="buy_sell_box_heading">Buy Setting</h2>
                        </div>  


                        <div class="col-md-12 hide_every_thing">
                            <div class="form-group col-md-12">
                              <div class="checkbox">
                                  <label><input type="checkbox" value="yes" id="is_endble_trigger_for_buy" name="is_endble_trigger_for_buy">Enble Trigger for buy </label>
                              </div>
                            </div>
                        </div>


                        <div class="col-md-12 hide_every_thing">
                            <div class="form-group col-md-12">
                              <div class="checkbox">
                                  <label><input type="checkbox" value="yes" id="is_closest_black_bottom_wall" name="is_closest_black_bottom_wall">is_closest_black_bottom_wall</label>
                              </div>
                            </div>
                        </div>




                        <div class="col-md-12 hide_every_thing">
                            <div class="form-group col-md-12">
                              <div class="checkbox">
                                  <label><input type="checkbox" value="yes" id="is_closest_yellow_bottom_wall" name="is_closest_yellow_bottom_wall">is_closest_yellow_bottom_wall</label>
                              </div>
                            </div>
                        </div>


                        <div class="col-md-12 hide_every_thing">
                            <div class="form-group col-md-12">
                              <div class="checkbox">
                                  <label><input type="checkbox" value="yes" id="is_big_buyers" name="is_big_buyers">is buyers greater then seller</label>
                              </div>
                            </div>
                        </div>

                        <div class="col-md-12 hide_every_thing">
                            <div class="form-group col-md-12">
                              <div class="checkbox">
                                  <label><input type="checkbox" value="yes" id="is_big_trade" name="is_big_trade">is big buyers greater then big seller </label>
                              </div>
                            </div>
                        </div> 



                          
                        <div class="col-md-12 hide_every_thing">
                            <div class="form-group col-md-12">
                              <div class="checkbox">
                                  <label><input type="checkbox" value="yes" id="is_up_pressure" name="is_up_pressure">is up pressure for 5 level</label>
                              </div>
                            </div>
                        </div>   


                        <div class="col-md-12 hide_every_thing">
                            <div class="form-group col-md-12">
                              <div class="checkbox">
                                  <label><input type="checkbox" value="yes" id="is_big_pressure_up" name="is_big_pressure_up">is big wall up</label>
                              </div>
                            </div>
                        </div> 

                        <div class="col-md-12 hide_every_thing">
                            <div class="form-group col-md-12">
                              <div class="checkbox">
                                  <label><input type="checkbox" value="yes" id="seven_level_up_down_rule_for_buy" name="seven_level_up_down_rule_for_buy">7 Level up down rule for buy</label>
                              </div>
                            </div>
                        </div> 

                        <div class="form-group col-md-12 hide_every_thing">
                            <div class="checkbox">
                                  <label><input type="checkbox" value="yes" id="is_big_ask_percent" name="is_big_ask_percent">is_big_ask_percent</label>
                          </div>

                       
                        </div>

                        <div class="form-group col-md-12">
                          <label class="control-label" for="hour">buy range % </label>
                          <input type="text" class="form-control" name="buy_range_percet" id="buy_range_percet" value="0" step="any">
                        </div>

                        <div class="form-group col-md-12">
                          <label class="control-label" for="hour">(When the % between the buy price and current market price Greater then the Definded % Then Update the stopLoss wall) </label>
                          <input type="text" class="form-control" name="sell_profit_percet" id="sell_profit_percet" value="1" step="any">
                        </div>


                        <div class="form-group col-md-12">
                          <label class="control-label" for="hour">Stop loss% ( the percentage on which you want to sell the order in case of loss) </label>
                          <input type="text" class="form-control" name="stop_loss_percet" id="stop_loss_percet" value="1" step="any">
                        </div>
                       </div>  

                      
                       <!-- Nav pills -->
                      <ul class="nav nav-pills">
                        <li class="nav-item">
                          <a class="nav-link active" data-toggle="pill" href="#home_enable">Enable Rules</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="pill" href="#home_disable">Disable Rules</a>
                        </li>
                      </ul>

                     


                     

                      <!-- ***********************************************- -->
                      <!-- Buy Rule from 5 to 8-->

                      <!-- Tab panes -->
                      <div class="tab-content"><!--start of tab content -->
                        <div class="tab-pane container active" id="home_enable">
                          <?php for($rule_number = 1;$rule_number<=10;$rule_number++){ ?>
                          <div class="global_setting buy_sell_box buy_sell_box_<?php echo $rule_number; ?>">
                            <div class="row">
                               <div class="col-md-6">
                                  <h2 class="buy_sell_box_heading"> 
                                    <dd class="pull-left">
                                    Buy Rule_<?php echo $rule_number; ?> setting
                                    </dd>
                                  <label class="pull-right" style="margin-top: 11px; margin-bottom: 0;">
                                    <strong>
                                    Enable Disable Rule_<?php echo $rule_number; ?> setting
                                    </strong>
                                  </label>  
                                  </h2> 
                               </div>    
                              
                                 <div class="col-md-6">
                                     <div class="onoffswitch">
                                              <input type="checkbox" name="enable_buy_rule_no_<?php echo $rule_number; ?>" class="onoffswitch-checkbox" id="enable_buy_rule_no_<?php echo $rule_number; ?>" value="yes" >
                                              <label class="onoffswitch-label" for="enable_buy_rule_no_<?php echo $rule_number; ?>">
                                              <span class="onoffswitch-inner"></span>
                                              <span class="onoffswitch-switch"></span>
                                              </label>
                                     </div>
                                 </div> 
                            </div>
                          
                            <div class="row">
                              <div class="form-group col-md-6">
                                <label class="control-label" for="hour">Select Status </label>
                                  <select class="form-control buy_status_rule" id="buy_status_rule_<?php echo $rule_number; ?>" name="buy_status_rule_<?php echo $rule_number; ?>[]" multiple>
                                  <option value="LL" >LL</option>
                                  <option value="LH">LH</option>
                                  <option value="HH">HH</option>
                                  <option value="HL">HL</option>
                                  </select>
                              </div>
                              <div class="form-group col-md-6">
                                    <label>&nbsp;</label>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="buy_status_rule_<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="buy_status_rule_<?php echo $rule_number; ?>_enable" value="yes" >
                                        <label class="onoffswitch-label" for="buy_status_rule_<?php echo $rule_number; ?>_enable">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                              </div> 
                            </div>    
                          

                             <div class="row">
                                <div class="form-group col-md-6">
                                  <label class="control-label" for="hour">Select Trigger Status </label>
                                    <select class="form-control  buy_trigger_type" id="buy_trigger_type_rule_<?php echo $rule_number; ?>" name="buy_trigger_type_rule_<?php echo $rule_number; ?>[]" multiple>
                                    <option value="very_strong_barrier">very_strong_barrier</option>
                                    <option value="weak_barrier" >weak_barrier</option>
                                    <option value="strong_barrier">strong_barrier</option>
                                    </select>
                                </div>
                                  <div class="form-group col-md-6">
                                     <label>&nbsp;</label>
                                      <div class="onoffswitch">
                                        <input type="checkbox" name="buy_trigger_type_rule_<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="buy_trigger_type_rule_<?php echo $rule_number; ?>_enable" value="yes" >
                                        <label class="onoffswitch-label" for="buy_trigger_type_rule_<?php echo $rule_number; ?>_enable">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                      </div>
                                  </div>
                             </div> 

            

                            <!--Order Book Barrier  -->
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label" for="hour">(Virtual Order Book Barrier Range)</label>
                                    <input type="text" step="any" class="form-control" name="buy_virtural_rule_<?php echo $rule_number; ?>" id="buy_virtural_rule_<?php echo $rule_number; ?>" value="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>&nbsp;</label>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="buy_virtual_barrier_rule_<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="buy_virtual_barrier_rule_<?php echo $rule_number; ?>_enable" value="yes" >
                                        <label class="onoffswitch-label" for="buy_virtual_barrier_rule_<?php echo $rule_number; ?>_enable">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div> 
                              </div>
                            <!-- End of book barrier -->


                              <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label" for="hour">(If Volume greater then the defined value)</label>
                                    <input type="text" step="any" class="form-control" name="buy_volume_rule_<?php echo $rule_number; ?>" id="buy_volume_rule_<?php echo $rule_number; ?>" value="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>&nbsp;</label>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="buy_check_volume_rule_<?php echo $rule_number; ?>" class="onoffswitch-checkbox" id="buy_check_volume_rule_<?php echo $rule_number; ?>" value="yes" >
                                        <label class="onoffswitch-label" for="buy_check_volume_rule_<?php echo $rule_number; ?>">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div> 
                              </div>  


                                <div class="row">
                                  <div class="form-group col-md-6">
                                    <label class="control-label" for="hour">Down pressure ( Down pressure is Greater or equal to the defined pressure)</label>
                                    <input type="text" step="any" class="form-control" name="done_pressure_rule_<?php echo $rule_number; ?>_buy" id="done_pressure_rule_<?php echo $rule_number; ?>_buy" value="">
                                  </div> 

                                 <div class="form-group col-md-6">
                                    <label>&nbsp;</label>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="done_pressure_rule_<?php echo $rule_number; ?>_buy_enable" class="onoffswitch-checkbox" id="done_pressure_rule_<?php echo $rule_number; ?>_buy_enable" value="yes" >
                                        <label class="onoffswitch-label" for="done_pressure_rule_<?php echo $rule_number; ?>_buy_enable">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                  </div> 
                                </div>  

                                <div class="row">
                                  <div class="form-group col-md-6">
                                    <label class="control-label" for="hour">Big Buyers% (percent greater then the defined %)</label>
                                    <input type="text" step="any" class="form-control" name="big_seller_percent_compare_rule_<?php echo $rule_number; ?>_buy" id="big_seller_percent_compare_rule_<?php echo $rule_number; ?>_buy" value="">
                                  </div> 

                                  <div class="form-group col-md-6">
                                    <label>&nbsp;</label>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="big_seller_percent_compare_rule_<?php echo $rule_number; ?>_buy_enable" class="onoffswitch-checkbox" id="big_seller_percent_compare_rule_<?php echo $rule_number; ?>_buy_enable" value="yes" >
                                        <label class="onoffswitch-label" for="big_seller_percent_compare_rule_<?php echo $rule_number; ?>_buy_enable">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                  </div>
                                </div>


                                <div class="row">
                                  <div class="form-group col-md-6">
                                    <label class="control-label" for="hour">Closest black wall(Greater then or equal to the defined value)</label>
                                    <input type="text" step="any" class="form-control" name="closest_black_wall_rule_<?php echo $rule_number; ?>_buy" id="closest_black_wall_rule_<?php echo $rule_number; ?>_buy" value="">
                                  </div> 

                                  <div class="form-group col-md-6">
                                    <label>&nbsp;</label>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="closest_black_wall_rule_<?php echo $rule_number; ?>_buy_enable" class="onoffswitch-checkbox" id="closest_black_wall_rule_<?php echo $rule_number; ?>_buy_enable" value="yes" >
                                        <label class="onoffswitch-label" for="closest_black_wall_rule_<?php echo $rule_number; ?>_buy_enable">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                  </div>
                                </div>  


                                <div class="row">
                                  <div class="form-group col-md-6">
                                    <label class="control-label" for="hour">Closest yellow wall (Greater then or equal to the defined value)</label>
                                    <input type="text" step="any" class="form-control" name="closest_yellow_wall_rule_<?php echo $rule_number; ?>_buy" id="closest_yellow_wall_rule_<?php echo $rule_number; ?>_buy" value="">
                                  </div> 

                                  <div class="form-group col-md-6">
                                    <label>&nbsp;</label>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="closest_yellow_wall_rule_<?php echo $rule_number; ?>_buy_enable" class="onoffswitch-checkbox" id="closest_yellow_wall_rule_<?php echo $rule_number; ?>_buy_enable" value="yes" >
                                        <label class="onoffswitch-label" for="closest_yellow_wall_rule_<?php echo $rule_number; ?>_buy_enable">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                  </div>
                                </div> 


                                <div class="row">
                                  <div class="form-group col-md-6">
                                    <label class="control-label" for="hour">Seven level pressue (Greater then or equal to the defined value)</label>
                                    <input type="text" step="any" class="form-control" name="seven_level_pressure_rule_<?php echo $rule_number; ?>_buy" id="seven_level_pressure_rule_<?php echo $rule_number; ?>_buy" value="">
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label>&nbsp;</label>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="seven_level_pressure_rule_<?php echo $rule_number; ?>_buy_enable" class="onoffswitch-checkbox" id="seven_level_pressure_rule_<?php echo $rule_number; ?>_buy_enable" value="yes" >
                                        <label class="onoffswitch-label" for="seven_level_pressure_rule_<?php echo $rule_number; ?>_buy_enable">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                  </div>
                                </div>   



                                <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Buyers Vs Seller</label>
                                      <input type="text" step="any" class="form-control" name="buyer_vs_seller_rule_<?php echo $rule_number; ?>_buy" id="buyer_vs_seller_rule_<?php echo $rule_number; ?>_buy" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="buyer_vs_seller_rule_<?php echo $rule_number; ?>_buy_enable" class="onoffswitch-checkbox" id="buyer_vs_seller_rule_<?php echo $rule_number; ?>_buy_enable" value="yes" >
                                            <label class="onoffswitch-label" for="buyer_vs_seller_rule_<?php echo $rule_number; ?>_buy_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>

                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                              <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Last Candle Type</label>
                                      <select class="form-control" name="last_candle_type<?php echo $rule_number; ?>_buy" id="last_candle_type<?php echo $rule_number; ?>_buy">
                                        
                                        <option value="normal">
                                        Normal
                                        </option>

                                        <option value="demand">
                                        Demand
                                        </option>

                                        <option value="supply">
                                        Supply
                                        </option>

                                      </select>
                                      
                                      
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="buy_last_candle_type<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="buy_last_candle_type<?php echo $rule_number; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="buy_last_candle_type<?php echo $rule_number; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                               </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

                                <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Rejection Candle Type</label>
                                      <select  name="rejection_candle_type<?php echo $rule_number; ?>_buy" id="rejection_candle_type<?php echo $rule_number; ?>_buy" class="form-control">
                                        <option value="top_demand_rejection">
                                          Top Demand Rejection
                                        </option> 
                                        <option value="bottom_demand_rejection">
                                          Bottom Demand Rejection
                                        </option>

                                        <option value="top_supply_rejection">
                                          Top Supply Rejection
                                        </option>

                                        <option value="bottom_supply_rejection">
                                          Bottom Supply Rejection
                                        </option>

                                        <option value="no_rejection">
                                          No Rejection
                                        </option>  
                                      </select >

                                      
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="buy_rejection_candle_type<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="buy_rejection_candle_type<?php echo $rule_number; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="buy_rejection_candle_type<?php echo $rule_number; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->


                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Last 200 Contract Buyers Vs Sellers</label>
                                      <input type="text" step="any" class="form-control" name="last_200_contracts_buy_vs_sell<?php echo $rule_number; ?>_buy" id="last_200_contracts_buy_vs_sell<?php echo $rule_number; ?>_buy" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="buy_last_200_contracts_buy_vs_sell<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="buy_last_200_contracts_buy_vs_sell<?php echo $rule_number; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="buy_last_200_contracts_buy_vs_sell<?php echo $rule_number; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->



                                <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Last 200 Contract Time(Less then)</label>
                                      <input type="text" step="any" class="form-control " name="last_200_contracts_time<?php echo $rule_number; ?>_buy" id="last_200_contracts_time<?php echo $rule_number; ?>_buy" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="buy_last_200_contracts_time<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="buy_last_200_contracts_time<?php echo $rule_number; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="buy_last_200_contracts_time<?php echo $rule_number; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->



                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Last qty Contract Buyes Vs seller</label>
                                      <input type="text" step="any" class="form-control" name="last_qty_buyers_vs_seller<?php echo $rule_number; ?>_buy" id="last_qty_buyers_vs_seller<?php echo $rule_number; ?>_buy" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="buy_last_qty_buyers_vs_seller<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="buy_last_qty_buyers_vs_seller<?php echo $rule_number; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="buy_last_qty_buyers_vs_seller<?php echo $rule_number; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->



                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Last qty Contract time(Less then)</label>
                                      <input type="text" step="any" class="form-control " name="last_qty_time<?php echo $rule_number; ?>_buy" id="last_qty_time<?php echo $rule_number; ?>_buy" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="buy_last_qty_time<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="buy_last_qty_time<?php echo $rule_number; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="buy_last_qty_time<?php echo $rule_number; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->


                       
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Score</label>
                                      <input type="text" step="any" class="form-control" name="score<?php echo $rule_number; ?>_buy" id="score<?php echo $rule_number; ?>_buy" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="buy_score<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="buy_score<?php echo $rule_number; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="buy_score<?php echo $rule_number; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                              <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Comment</label>

                                       <textarea name="comment<?php echo $rule_number; ?>_buy" class="form-control" rows="5"  id="comment<?php echo $rule_number; ?>_buy"></textarea>

                                    


                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="buy_comment<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="buy_comment<?php echo $rule_number; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="buy_comment<?php echo $rule_number; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->



                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                              <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Order Level</label>
                                        <select class="form-control  buy_order_level" id="buy_order_level_<?php echo $rule_number; ?>" name="buy_order_level_<?php echo $rule_number; ?>[]" multiple>
                                          <option value="">Select Level </option>
                                          <option value="level_1">Level 1</option>
                                          <option value="level_2">Level 2</option>
                                          <option value="level_3">Level 3</option>
                                          <option value="level_4">Level 4</option>
                                          <option value="level_5">Level 5</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="buy_order_level_<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="buy_order_level_<?php echo $rule_number; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="buy_order_level_<?php echo $rule_number; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                               <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Las Candle Status</label>
                                      <select class="form-control" name="last_candle_status<?php echo $rule_number; ?>_buy" id="last_candle_status<?php echo $rule_number; ?>_buy">
                                        
                                        <option value="">
                                        Select Candle Status
                                        </option>

                                        <option value="demand">
                                        Demand
                                        </option>

                                        <option value="supply">
                                        Supply
                                        </option>

                                      </select>
                                      
                                      
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="buy_last_candle_status<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="buy_last_candle_status<?php echo $rule_number; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="buy_last_candle_status<?php echo $rule_number; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                               </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

                          </div>
                         <?php } ?>
                        </div>
                        <!--End of home anable-->

                        <!---start of home disable -->

                         <div class="tab-pane container fade" id="home_disable">
                          <div class="disable_buy_div">
                            
                          </div>
                         </div>

                        <!---  End of homeDisable-->
                      </div> 
                       <!--End of tab content --> 

                          <!-- End of buy Rule  from 5 yo 8-->
                          <!-- ************************************************* -->
                  </div><!--End of Buy part -->



                  <div id="menu1" class="tab-pane fade">
                  <!-- Sell part -->
                      <div class="sell_part buy_sell_box">
                        <div class="col-md-12">
                          <h2 class="buy_sell_box_heading">Sell Setting</h2>
                        </div> 


                        <div class="col-md-12 hide_every_thing">
                            <div class="form-group col-md-12">
                              <div class="checkbox">
                                  <label><input type="checkbox" value="yes" id="is_endble_trigger_for_sell" name="is_endble_trigger_for_sell">Enble Trigger for sell </label>
                              </div>
                            </div>
                        </div>


                        <div class="col-md-12 hide_every_thing">
                          <div class="form-group col-md-12">
                            <div class="checkbox">
                                <label><input type="checkbox" value="yes" id="is_down_pressure_for_sell" name="is_down_pressure_for_sell">is down pressure for sell 5 levels</label>
                            </div>
                          </div>
                        </div> 

           

                        <div class="col-md-12 hide_every_thing">
                          <div class="form-group col-md-12">
                            <div class="checkbox">
                                <label><input type="checkbox" value="yes" id="is_black_closest_wall_for_sell" name="is_black_closest_wall_for_sell">is_black_closest_wall_for_sell</label>
                            </div>
                          </div>
                        </div> 


                        <div class="col-md-12 hide_every_thing">
                          <div class="form-group col-md-12">
                            <div class="checkbox">
                                <label><input type="checkbox" value="yes" id="is_yellow_closest_wall_for_sell" name="is_yellow_closest_wall_for_sell">is_yellow_closest_wall_for_sell</label>
                            </div>
                          </div>
                        </div> 




                        <div class="col-md-12 hide_every_thing">
                          <div class="form-group col-md-12">
                            <div class="checkbox">
                                <label><input type="checkbox" value="yes" id="seven_level_up_down_rule_for_sell" name="seven_level_up_down_rule_for_sell">7 Level up down rule for sell</label>
                            </div>
                          </div>
                        </div> 
                      <div class="form-group col-md-12 hide_every_thing">
                        <div class="checkbox">
                                <label><input type="checkbox" value="yes" id="is_big_bid_percent" name="is_big_bid_percent">is_big_bid_percent</label>
                        </div>
                      </div>


                      <div class="form-group col-md-12 hide_every_thing">
                        <div class="checkbox">
                              <label class="control-label" for="hour">Sell range % For previous barrier values </label>
                                <input type="number" step="any" class="form-control" name="range_previous_barrier_values" id="range_previous_barrier_values" value="">
                        </div>
                      </div>
                    </div>

                      <!-- Nav pills -->
                    <ul class="nav nav-pills">
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#anable_sell">Enable Rule Listing</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#disable_sell">Disable Rule Listing</a>
                      </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                       <div class="tab-pane container active" id="anable_sell">
                      <?php for($sell_r_n =1;$sell_r_n<=10;$sell_r_n++){ ?>
                      <div class="global_setting buy_sell_box sell_box_<?php echo $sell_r_n; ?>">

                        <div class="row">
                          <div class="col-md-6">

                        

                              <h2 class="buy_sell_box_heading">
                                <dd class="pull-left">
                                Sell Rule_<?php echo $sell_r_n; ?> setting
                              </dd>

                               <label class="pull-right" >
                                    <strong>
                                      Enable Disable Rule_<?php echo $sell_r_n; ?> setting
                                    </strong>
                                </label> 


                              </h2>
                          </div>
                          <div class="col-md-6">
                             <div class="onoffswitch">
                                      <input type="checkbox" name="enable_sell_rule_no_<?php echo $sell_r_n; ?>" class="onoffswitch-checkbox" id="enable_sell_rule_no_<?php echo $sell_r_n; ?>" value="yes" >
                                      <label class="onoffswitch-label" for="enable_sell_rule_no_<?php echo $sell_r_n; ?>">
                                      <span class="onoffswitch-inner"></span>
                                      <span class="onoffswitch-switch"></span>
                                      </label>
                              </div> 
                          </div> 
                        </div>

                        <div class="row">
                          <div class="form-group col-md-6">
                            <label class="control-label" for="hour">Select Status </label>
                              <select class="form-control sell_status_rule" id="sell_status_rule_<?php echo $sell_r_n; ?>" name="sell_status_rule_<?php echo $sell_r_n; ?>[]" multiple>
                              <option value="LL" >LL</option>
                              <option value="LH">LH</option>
                              <option value="HH">HH</option>
                              <option value="HL">HL</option>
                              </select>
                          </div>

                            <div class="form-group col-md-6">
                              <label>&nbsp;</label>
                              <div class="onoffswitch">
                                <input type="checkbox" name="sell_status_rule_<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="sell_status_rule_<?php echo $sell_r_n; ?>_enable" value="yes" >
                                <label class="onoffswitch-label" for="sell_status_rule_<?php echo $sell_r_n; ?>_enable">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                                </label>
                              </div>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="form-group col-md-6">
                              <label class="control-label" for="hour">Select Trigger Status </label>
                                <select class="form-control  buy_trigger_type" id="sell_trigger_type_rule_<?php echo $sell_r_n; ?>" name="sell_trigger_type_rule_<?php echo $sell_r_n; ?>[]" multiple>
                                <option value="very_strong_barrier">very_strong_barrier</option>
                                <option value="weak_barrier" >weak_barrier</option>
                                <option value="strong_barrier">strong_barrier</option>
                                </select>
                            </div>
                              <div class="form-group col-md-6">
                                 <label>&nbsp;</label>
                                  <div class="onoffswitch">
                                    <input type="checkbox" name="sell_trigger_type_rule_<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="sell_trigger_type_rule_<?php echo $sell_r_n; ?>_enable" value="yes" >
                                    <label class="onoffswitch-label" for="sell_trigger_type_rule_<?php echo $sell_r_n; ?>_enable">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                  </div>
                              </div>
                        </div> 


                              <!--Order Book Barrier  -->
                              <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label" for="hour">(Virtual Order Book Barrier Range)</label>
                                    <input type="text" step="any" class="form-control" name="sell_virtural_rule_<?php echo $sell_r_n; ?>" id="sell_virtural_rule_<?php echo $sell_r_n; ?>" value="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>&nbsp;</label>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="sell_virtual_barrier_rule_<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="sell_virtual_barrier_rule_<?php echo $sell_r_n; ?>_enable" value="yes" >
                                        <label class="onoffswitch-label" for="sell_virtual_barrier_rule_<?php echo $sell_r_n; ?>_enable">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div> 
                              </div>
                            <!-- End of book barrier -->





                        <div class="row">
                          <div class="form-group col-md-6">
                              <label class="control-label" for="hour">(If Volume greater then the defined value)</label>
                              <input type="text" step="any" class="form-control" name="sell_volume_rule_<?php echo $sell_r_n; ?>" id="sell_volume_rule_<?php echo $sell_r_n; ?>" value="">
                          </div> 

                          <div class="form-group col-md-6">
                              <label>&nbsp;</label>
                              <div class="onoffswitch">
                                <input type="checkbox" name="sell_check_volume_rule_<?php echo $sell_r_n; ?>" class="onoffswitch-checkbox" id="sell_check_volume_rule_<?php echo $sell_r_n; ?>" value="yes" >
                                <label class="onoffswitch-label" for="sell_check_volume_rule_<?php echo $sell_r_n; ?>">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                                </label>
                              </div>
                            </div>
                        </div>  

                        <div class="row">
                          <div class="form-group col-md-6">
                            <label class="control-label" for="hour">Down pressure (Sell: Down pressure is Less or equal to the defined pressure)</label>
                            <input type="number" step="any" class="form-control" name="done_pressure_rule_<?php echo $sell_r_n; ?>" id="done_pressure_rule_<?php echo $sell_r_n; ?>" value="">
                          </div> 

                          <div class="form-group col-md-6">
                              <label>&nbsp;</label>
                              <div class="onoffswitch">
                                <input type="checkbox" name="done_pressure_rule_<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="done_pressure_rule_<?php echo $sell_r_n; ?>_enable" value="yes" >
                                <label class="onoffswitch-label" for="done_pressure_rule_<?php echo $sell_r_n; ?>_enable">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                                </label>
                              </div>
                          </div>
                        </div> 

                        <div class="row"> 
                          <div class="form-group col-md-6">
                            <label class="control-label" for="hour">Big Seller% (percent greater then the defined %)</label>
                            <input type="text" step="any" class="form-control" name="big_seller_percent_compare_rule_<?php echo $sell_r_n; ?>" id="big_seller_percent_compare_rule_<?php echo $sell_r_n; ?>" value="">
                          </div>

                          <div class="form-group col-md-6">
                              <label>&nbsp;</label>
                              <div class="onoffswitch">
                                <input type="checkbox" name="big_seller_percent_compare_rule_<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="big_seller_percent_compare_rule_<?php echo $sell_r_n; ?>_enable" value="yes" >
                                <label class="onoffswitch-label" for="big_seller_percent_compare_rule_<?php echo $sell_r_n; ?>_enable">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                                </label>
                              </div>
                          </div>
                        </div>  

                        <div class="row">
                          <div class="form-group col-md-6">
                            <label class="control-label" for="hour">Closest black wall(Less then or equal to the defined value)</label>
                            <input type="text" step="any" class="form-control" name="closest_black_wall_rule_<?php echo $sell_r_n; ?>" id="closest_black_wall_rule_<?php echo $sell_r_n; ?>" value="">
                          </div> 

                          <div class="form-group col-md-6">
                              <label>&nbsp;</label>
                              <div class="onoffswitch">
                                <input type="checkbox" name="closest_black_wall_rule_<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="closest_black_wall_rule_<?php echo $sell_r_n; ?>_enable" value="yes" >
                                <label class="onoffswitch-label" for="closest_black_wall_rule_<?php echo $sell_r_n; ?>_enable">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                                </label>
                              </div>
                          </div>
                        </div>  

                        <div class="row">
                          <div class="form-group col-md-6">
                            <label class="control-label" for="hour">Closest yellow wall (Less then or equal to the defined value)</label>
                            <input type="text" step="any" class="form-control" name="closest_yellow_wall_rule_<?php echo $sell_r_n; ?>" id="closest_yellow_wall_rule_<?php echo $sell_r_n; ?>" value="">
                          </div> 

                          <div class="form-group col-md-6">
                              <label>&nbsp;</label>
                              <div class="onoffswitch">
                                <input type="checkbox" name="closest_yellow_wall_rule_<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="closest_yellow_wall_rule_<?php echo $sell_r_n; ?>_enable" value="yes" >
                                <label class="onoffswitch-label" for="closest_yellow_wall_rule_<?php echo $sell_r_n; ?>_enable">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                                </label>
                              </div>
                          </div>
                        </div>  

                        <div class="row">
                          <div class="form-group col-md-6">
                            <label class="control-label" for="hour">Seven level pressue (Less then or equal to the defined value)</label>
                            <input type="text" step="any" class="form-control" name="seven_level_pressure_rule_<?php echo $sell_r_n; ?>" id="seven_level_pressure_rule_<?php echo $sell_r_n; ?>" value="">
                          </div> 

                          <div class="form-group col-md-6">
                              <label>&nbsp;</label>
                              <div class="onoffswitch">
                                <input type="checkbox" name="seven_level_pressure_rule_<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="seven_level_pressure_rule_<?php echo $sell_r_n; ?>_enable" value="yes" >
                                <label class="onoffswitch-label" for="seven_level_pressure_rule_<?php echo $sell_r_n; ?>_enable">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                                </label>
                              </div>
                          </div>
                        </div>  

                        <div class="row">
                          <div class="form-group col-md-6">
                            <label class="control-label" for="hour">Sell % (when we sell the order we check if the defined percenage is meet)</label>
                            <input type="text" step="any" class="form-control" name="sell_percent_rule_<?php echo $sell_r_n; ?>" id="sell_percent_rule_<?php echo $sell_r_n; ?>" value="">
                          </div>

                          <div class="form-group col-md-6">
                              <label>&nbsp;</label>
                              <div class="onoffswitch">
                                <input type="checkbox" name="sell_percent_rule_<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="sell_percent_rule_<?php echo $sell_r_n; ?>_enable" value="yes" >
                                <label class="onoffswitch-label" for="sell_percent_rule_<?php echo $sell_r_n; ?>_enable">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                                </label>
                              </div>
                          </div>
                        </div> 


                        <div class="row">
                              <div class="form-group col-md-6">
                                <label class="control-label" for="hour">Seller Vs Buyers  </label>
                                <input type="text" step="any" class="form-control" name="seller_vs_buyer_rule_<?php echo $sell_r_n; ?>_sell" id="seller_vs_buyer_rule_<?php echo $sell_r_n; ?>_sell" value="">
                              </div>
                              <div class="form-group col-md-6">
                                   <label>&nbsp;</label>
                                    <div class="onoffswitch">
                                      <input type="checkbox" name="seller_vs_buyer_rule_<?php echo $sell_r_n; ?>_sell_enable" class="onoffswitch-checkbox" id="seller_vs_buyer_rule_<?php echo $sell_r_n; ?>_sell_enable" value="yes" >
                                      <label class="onoffswitch-label" for="seller_vs_buyer_rule_<?php echo $sell_r_n; ?>_sell_enable">
                                          <span class="onoffswitch-inner"></span>
                                          <span class="onoffswitch-switch"></span>
                                      </label>
                                    </div>
                               </div> 
                        </div>

                        <!--&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
                        <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                              <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Last Candle Type</label>
                                      <select class="form-control" name="last_candle_type<?php echo $sell_r_n; ?>_sell" id="last_candle_type<?php echo $sell_r_n; ?>_sell">
                                        
                                        <option value="normal">
                                        Normal
                                        </option>

                                        <option value="demand">
                                        Demand
                                        </option>

                                        <option value="supply">
                                        Supply
                                        </option>

                                      </select>
                                      
                                      
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="sell_last_candle_type<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="sell_last_candle_type<?php echo $sell_r_n; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="sell_last_candle_type<?php echo $sell_r_n; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                               </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

                                <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Rejection Candle Type</label>
                                      <select  name="rejection_candle_type<?php echo $sell_r_n; ?>_sell" id="rejection_candle_type<?php echo $sell_r_n; ?>_sell" class="form-control">
                                        <option value="top_demand_rejection">
                                          Top Demand Rejection
                                        </option> 
                                        <option value="bottom_demand_rejection">
                                          Bottom Demand Rejection
                                        </option>

                                        <option value="top_supply_rejection">
                                          Top Supply Rejection
                                        </option>

                                        <option value="bottom_supply_rejection">
                                          Bottom Supply Rejection
                                        </option>

                                        <option value="no_rejection">
                                          No Rejection
                                        </option>  
                                      </select >

                                      
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="sell_rejection_candle_type<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="sell_rejection_candle_type<?php echo $sell_r_n; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="sell_rejection_candle_type<?php echo $sell_r_n; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->


                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Last 200 Contract Buyers Vs Sellers</label>
                                      <input type="text" step="any" class="form-control" name="last_200_contracts_buy_vs_sell<?php echo $sell_r_n; ?>_sell" id="last_200_contracts_buy_vs_sell<?php echo $sell_r_n; ?>_sell" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="sell_last_200_contracts_buy_vs_sell<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="sell_last_200_contracts_buy_vs_sell<?php echo $sell_r_n; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="sell_last_200_contracts_buy_vs_sell<?php echo $sell_r_n; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->



                                <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Last 200 Contract Time(Less then)</label>
                                      <input type="text" step="any" class="form-control " name="last_200_contracts_time<?php echo $sell_r_n; ?>_sell" id="last_200_contracts_time<?php echo $sell_r_n; ?>_sell" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="sell_last_200_contracts_time<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="sell_last_200_contracts_time<?php echo $sell_r_n; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="sell_last_200_contracts_time<?php echo $sell_r_n; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->



                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Last qty Contract seller Vs Buyes</label>
                                      <input type="text" step="any" class="form-control" name="last_qty_buyers_vs_seller<?php echo $sell_r_n; ?>_sell" id="last_qty_buyers_vs_seller<?php echo $sell_r_n; ?>_sell" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="sell_last_qty_buyers_vs_seller<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="sell_last_qty_buyers_vs_seller<?php echo $sell_r_n; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="sell_last_qty_buyers_vs_seller<?php echo $sell_r_n; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->



                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Last qty Contract time(Less then)</label>
                                      <input type="text" step="any" class="form-control " name="last_qty_time<?php echo $sell_r_n; ?>_sell" id="last_qty_time<?php echo $sell_r_n; ?>_sell" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="sell_last_qty_time<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="sell_last_qty_time<?php echo $sell_r_n; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="sell_last_qty_time<?php echo $sell_r_n; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->


                          



                                <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Score</label>
                                      <input type="text" step="any" class="form-control" name="score<?php echo $sell_r_n; ?>_sell" id="score<?php echo $sell_r_n; ?>_sell" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="sell_score<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="sell_score<?php echo $sell_r_n; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="sell_score<?php echo $sell_r_n; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->



                                  <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                  <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Comment</label>

                                      <textarea name="comment<?php echo $sell_r_n; ?>_sell"  class="form-control" rows="5" id="comment<?php echo $sell_r_n; ?>_sell"></textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="sell_comment<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="sell_comment<?php echo $sell_r_n; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="sell_comment<?php echo $sell_r_n; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

                         <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                         <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Order Level</label>
                                        <select class="form-control  sell_order_level" id="sell_order_level_<?php echo $sell_r_n; ?>" name="sell_order_level_<?php echo $sell_r_n; ?>[]" multiple>
                                          <option value="">Select Level </option>
                                          <option value="level_1">Level 1</option>
                                          <option value="level_2">Level 2</option>
                                          <option value="level_3">Level 3</option>
                                          <option value="level_4">Level 4</option>
                                          <option value="level_5">Level 5</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="sell_order_level_<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="sell_order_level_<?php echo $sell_r_n; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="sell_order_level_<?php echo $sell_r_n; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                                </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                        <!--&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
                        
                        <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                        <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Last Candle Status</label>
                                      <select class="form-control" name="last_candle_status<?php echo $sell_r_n; ?>_sell" id="last_candle_status<?php echo $sell_r_n; ?>_sell">
                                        
                                        <option value="">
                                        Select Status
                                        </option>

                                        <option value="demand">
                                        Demand
                                        </option>

                                        <option value="supply">
                                        Supply
                                        </option>

                                      </select>
                                      
                                      
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="sell_last_candle_status<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="sell_last_candle_status<?php echo $sell_r_n; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="sell_last_candle_status<?php echo $sell_r_n; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div> 
                               </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

                      </div><!--End of Sell Setting 1 -->
                     <?php } ?>
                      </div>
                      <!--- End of anable_sell-->
                      <!-- Start disable_sell -->
                      <div class="tab-pane container fade" id="disable_sell">
                        <div class="disable_sell_append"></div>
                      </div>
                      <!-- End of disable_sell -->
                    </div>
                    <!--End of tabcontent  -->
                  <!--End of Sell part -->
                  </div>
                </div>

               


              </div>

          <hr class="separator" />

          <!-- Form actions -->
          <div class="form-actions">
            <!-- <button type="button" id="clint_info_btn" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button> -->
          </div>
          <!-- // Form actions END -->
          </form>
        </div>
        </div>
      </div>
  </div>

</div>


<script type="text/javascript">



    $(document).ready(function() {

      var triggers_type = 'barrier_trigger';
      var setting_id = '<?php echo  $_GET['id']; ?>';
     

      if(triggers_type =='rg_15'){
        $('.hide_show_for_rg_15').hide();
      }else{
        $('.hide_show_for_rg_15').show();
      }
        
      
      if(triggers_type == 'box_trigger_3'){
        $('.score_show_hide').show();
      }else{
        $('.score_show_hide').hide();
      }


      if(triggers_type =='barrier_trigger'){
        $('.show_hide_for_barrier_trigger').hide();
        $('.show_barrier_trigger').show();
      }else{
        $('.show_hide_for_barrier_trigger').show();
        $('.show_barrier_trigger').hide();
      }  

      var coin = $('#coin').val();

      $.ajax({
        'url': '<?php echo base_url(); ?>admin/settings/get_trigger_setting_by_id',
        'data': {setting_id:setting_id},
        'type': 'POST',
        success : function(data){
            var res_obj =  JSON.parse(data);
            $('.aggressive_stop_rule').val(res_obj.aggressive_stop_rule);

              if(res_obj.aggressive_stop_rule == 'stop_loss_rule_2' || triggers_type == 'rg_15'){
                $('.aggressive_stop_rule').val('stop_loss_rule_2');
                 $('.factor_show_hide').show();
              }else{
                $('.factor_show_hide').hide();
              }


              if(triggers_type =='barrier_trigger'){
                $('.show_hide_for_barrier_trigger').hide();
                $('.show_barrier_trigger').show();

                

                      if(res_obj.is_big_ask_percent == 'yes'){
                        $('#is_big_ask_percent').prop('checked',true);
                      }else{
                        $('#is_big_ask_percent').prop('checked',false);
                      } 


                      if(res_obj.is_big_bid_percent == 'yes'){
                        $('#is_big_bid_percent').prop('checked',true);
                      }else{
                        $('#is_big_bid_percent').prop('checked',false);
                      }


                      $('#buy_range_percet').val(res_obj.buy_range_percet);

                      $('#range_previous_barrier_values').val(res_obj.range_previous_barrier_values);

                      $('#sell_profit_percet').val(res_obj.sell_profit_percet);
                      $('#stop_loss_percet').val(res_obj.stop_loss_percet);
                    
                   
                      $('#status').val(res_obj.status);
                      $('#very_strong_barrier').val(res_obj.very_strong_barrier);
                      $('#strong_barrier').val(res_obj.strong_barrier); 
                      $('#weak_barrier').val(res_obj.weak_barrier);

                      ///////////////////////////////////
                      ///////////////////////////////////

                      if(res_obj.is_closest_black_bottom_wall == 'yes'){
                      $('#is_closest_black_bottom_wall').prop('checked',true);
                      }else{
                      $('#is_closest_black_bottom_wall').prop('checked',false);
                      }


                      if(res_obj.is_closest_yellow_bottom_wall == 'yes'){
                        $('#is_closest_yellow_bottom_wall').prop('checked',true);
                      }else{
                       $('#is_closest_yellow_bottom_wall').prop('checked',false);
                      }


                      if(res_obj.is_big_pressure_up == 'yes'){
                      $('#is_big_pressure_up').prop('checked',true);
                      }else{
                      $('#is_big_pressure_up').prop('checked',false);
                      }

                      

                      if(res_obj.is_big_buyers == 'yes'){
                      $('#is_big_buyers').prop('checked',true);
                      }else{
                      $('#is_big_buyers').prop('checked',false);
                      }

                      if(res_obj.is_big_trade == 'yes'){
                      $('#is_big_trade').prop('checked',true);
                      }else{
                      $('#is_big_trade').prop('checked',false);
                      }


                      if(res_obj.is_up_pressure == 'yes'){
                      $('#is_up_pressure').prop('checked',true);
                      }else{
                      $('#is_up_pressure').prop('checked',false);
                      }


                      

                      if(res_obj.is_down_pressure_for_sell == 'yes'){
                       $('#is_down_pressure_for_sell').prop('checked',true);
                      }else{
                       $('#is_down_pressure_for_sell').prop('checked',false);
                      }

                      if(res_obj.is_endble_trigger_for_sell == 'yes'){
                         $('#is_endble_trigger_for_sell').prop('checked',true);
                      }else{
                        $('#is_endble_trigger_for_sell').prop('checked',false);
                      }


                      if(res_obj.is_endble_trigger_for_buy == 'yes'){
                       $('#is_endble_trigger_for_buy').prop('checked',true);
                      }else{
                       $('#is_endble_trigger_for_buy').prop('checked',false);
                      }

                      if(res_obj.is_black_closest_wall_for_sell == 'yes'){
                        $('#is_black_closest_wall_for_sell').prop('checked',true);
                      }else{
                       $('#is_black_closest_wall_for_sell').prop('checked',false);
                      }

                      if(res_obj.is_yellow_closest_wall_for_sell == 'yes'){
                        $('#is_yellow_closest_wall_for_sell').prop('checked',true);
                      }else{
                       $('#is_yellow_closest_wall_for_sell').prop('checked',false);
                      }


                      if(res_obj.big_seller_percent_compare_rule_4_buy_enable == 'yes'){
                        $('#big_seller_percent_compare_rule_4_buy_enable').prop('checked',true);
                      }else{
                       $('#big_seller_percent_compare_rule_4_buy_enable').prop('checked',false);
                      }


                      

                      if(res_obj.seven_level_up_down_rule_for_sell == 'yes'){
                        $('#seven_level_up_down_rule_for_sell').prop('checked',true);
                      }else{
                       $('#seven_level_up_down_rule_for_sell').prop('checked',false);
                      }

                     if(res_obj.seven_level_up_down_rule_for_buy == 'yes'){
                        $('#seven_level_up_down_rule_for_buy').prop('checked',true);
                      }else{
                       $('#seven_level_up_down_rule_for_buy').prop('checked',false);
                      }

                      
                      for(var buy_num_rule = 1;buy_num_rule<=10;buy_num_rule++){

                        /********************************/
                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                          if(res_obj['buy_last_candle_type'+buy_num_rule+'_enable'] == 'yes'){
                           $('#buy_last_candle_type'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#buy_last_candle_type'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                    
                        

                        if(res_obj['buy_last_candle_status'+buy_num_rule+'_enable'] == 'yes'){
                           $('#buy_last_candle_status'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#buy_last_candle_status'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['buy_rejection_candle_type'+buy_num_rule+'_enable'] == 'yes'){
                           $('#buy_rejection_candle_type'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#buy_rejection_candle_type'+buy_num_rule+'_enable').prop('checked',false);
                        }

                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                           $('#last_candle_status'+buy_num_rule+'_buy').val(res_obj['last_candle_status'+buy_num_rule+'_buy']);


                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['buy_last_200_contracts_buy_vs_sell'+buy_num_rule+'_enable'] == 'yes'){
                           $('#buy_last_200_contracts_buy_vs_sell'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#buy_last_200_contracts_buy_vs_sell'+buy_num_rule+'_enable').prop('checked',false);
                        }

                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/




                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['buy_last_200_contracts_time'+buy_num_rule+'_enable'] == 'yes'){
                           $('#buy_last_200_contracts_time'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#buy_last_200_contracts_time'+buy_num_rule+'_enable').prop('checked',false);
                        }

                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/



                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                          if(res_obj['buy_last_qty_buyers_vs_seller'+buy_num_rule+'_enable'] == 'yes'){
                             $('#buy_last_qty_buyers_vs_seller'+buy_num_rule+'_enable').prop('checked',true);
                          }else{
                           $('#buy_last_qty_buyers_vs_seller'+buy_num_rule+'_enable').prop('checked',false);
                          }

                          /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['buy_last_qty_time'+buy_num_rule+'_enable'] == 'yes'){
                           $('#buy_last_qty_time'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#buy_last_qty_time'+buy_num_rule+'_enable').prop('checked',false);
                        }

                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['buy_last_5_minute_candle_buys_vs_seller'+buy_num_rule+'_enable'] == 'yes'){
                           $('#buy_last_5_minute_candle_buys_vs_seller'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#buy_last_5_minute_candle_buys_vs_seller'+buy_num_rule+'_enable').prop('checked',false);
                        }

                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['buy_score'+buy_num_rule+'_enable'] == 'yes'){
                           $('#buy_score'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#buy_score'+buy_num_rule+'_enable').prop('checked',false);
                        }

                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/



                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['buy_comment'+buy_num_rule+'_enable'] == 'yes'){
                           $('#buy_comment'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#buy_comment'+buy_num_rule+'_enable').prop('checked',false);
                        }

                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                         /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['buy_order_level_'+buy_num_rule+'_enable'] == 'yes'){
                           $('#buy_order_level_'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#buy_order_level_'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/



                        /**********Buy part for setting Enable**********/
                        if(res_obj['buy_status_rule_'+buy_num_rule+'_enable'] == 'yes'){
                           $('#buy_status_rule_'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#buy_status_rule_'+buy_num_rule+'_enable').prop('checked',false);
                        }


                         if(res_obj['sell_status_rule_'+buy_num_rule+'_enable'] == 'yes'){
                           $('#sell_status_rule_'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#sell_status_rule_'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*********************************/

                        /*******************************/
                        if(res_obj['buy_trigger_type_rule_'+buy_num_rule+'_enable'] == 'yes'){
                          $('#buy_trigger_type_rule_'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#buy_trigger_type_rule_'+buy_num_rule+'_enable').prop('checked',false);
                        }


                         if(res_obj['sell_trigger_type_rule_'+buy_num_rule+'_enable'] == 'yes'){
                          $('#sell_trigger_type_rule_'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#sell_trigger_type_rule_'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*******************************/


                        /*******************************/
                        if(res_obj['buy_check_volume_rule_'+buy_num_rule] == 'yes'){
                          $('#buy_check_volume_rule_'+buy_num_rule).prop('checked',true);
                        }else{
                         $('#buy_check_volume_rule_'+buy_num_rule).prop('checked',false);
                        }
                        

                        if(res_obj['buy_virtual_barrier_rule_'+buy_num_rule+'_enable'] == 'yes'){
                          $('#buy_virtual_barrier_rule_'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#buy_virtual_barrier_rule_'+buy_num_rule+'_enable').prop('checked',false);
                        }


                        if(res_obj['sell_check_volume_rule_'+buy_num_rule] == 'yes'){
                          $('#sell_check_volume_rule_'+buy_num_rule).prop('checked',true);
                        }else{
                           $('#sell_check_volume_rule_'+buy_num_rule).prop('checked',false);
                        }

                        if(res_obj['sell_virtual_barrier_rule_'+buy_num_rule+'_enable'] == 'yes'){
                          $('#sell_virtual_barrier_rule_'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                           $('#sell_virtual_barrier_rule_'+buy_num_rule+'_enable').prop('checked',false);
                        }


                        
                        
                        /*******************************/


                        /*******************************/
                        

                        if(res_obj['done_pressure_rule_'+buy_num_rule+'_buy_enable'] == 'yes'){
                          $('#done_pressure_rule_'+buy_num_rule+'_buy_enable').prop('checked',true);
                        }else{
                         $('#done_pressure_rule_'+buy_num_rule+'_buy_enable').prop('checked',false);
                        }

                        if(res_obj['done_pressure_rule_'+buy_num_rule+'_enable'] == 'yes'){
                          $('#done_pressure_rule_'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#done_pressure_rule_'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*******************************/




                        /*******************************/
                        if(res_obj['big_seller_percent_compare_rule_'+buy_num_rule+'_buy_enable'] == 'yes'){
                          $('#big_seller_percent_compare_rule_'+buy_num_rule+'_buy_enable').prop('checked',true);
                        }else{
                         $('#big_seller_percent_compare_rule_'+buy_num_rule+'_buy_enable').prop('checked',false);
                        }


                        if(res_obj['big_seller_percent_compare_rule_'+buy_num_rule+'_enable'] == 'yes'){
                          $('#big_seller_percent_compare_rule_'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#big_seller_percent_compare_rule_'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*******************************/



                        /*******************************/
                        if(res_obj['closest_black_wall_rule_'+buy_num_rule+'_buy_enable'] == 'yes'){
                          $('#closest_black_wall_rule_'+buy_num_rule+'_buy_enable').prop('checked',true);
                        }else{
                         $('#closest_black_wall_rule_'+buy_num_rule+'_buy_enable').prop('checked',false);
                        }

         
                        if(res_obj['closest_black_wall_rule_'+buy_num_rule+'_enable'] == 'yes'){
                          $('#closest_black_wall_rule_'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#closest_black_wall_rule_'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*******************************/



                        /*******************************/
                        if(res_obj['closest_yellow_wall_rule_'+buy_num_rule+'_buy_enable'] == 'yes'){
                          $('#closest_yellow_wall_rule_'+buy_num_rule+'_buy_enable').prop('checked',true);
                        }else{
                         $('#closest_yellow_wall_rule_'+buy_num_rule+'_buy_enable').prop('checked',false);
                        }

                         if(res_obj['closest_yellow_wall_rule_'+buy_num_rule+'_enable'] == 'yes'){
                          $('#closest_yellow_wall_rule_'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#closest_yellow_wall_rule_'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*******************************/



                        /*******************************/
                        if(res_obj['seven_level_pressure_rule_'+buy_num_rule+'_buy_enable'] == 'yes'){
                          $('#seven_level_pressure_rule_'+buy_num_rule+'_buy_enable').prop('checked',true);
                        }else{
                         $('#seven_level_pressure_rule_'+buy_num_rule+'_buy_enable').prop('checked',false);
                        }

                        if(res_obj['seven_level_pressure_rule_'+buy_num_rule+'_enable'] == 'yes'){
                          $('#seven_level_pressure_rule_'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#seven_level_pressure_rule_'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*******************************/



                        /*******************************/

                        if(res_obj['buyer_vs_seller_rule_'+buy_num_rule+'_buy_enable'] == 'yes'){
                          $('#buyer_vs_seller_rule_'+buy_num_rule+'_buy_enable').prop('checked',true);
                        }else{
                         $('#buyer_vs_seller_rule_'+buy_num_rule+'_buy_enable').prop('checked',false);
                        }

                        

                        if(res_obj['seller_vs_buyer_rule_'+buy_num_rule+'_sell_enable'] == 'yes'){
                          $('#seller_vs_buyer_rule_'+buy_num_rule+'_sell_enable').prop('checked',true);
                        }else{
                         $('#seller_vs_buyer_rule_'+buy_num_rule+'_sell_enable').prop('checked',false);
                        }
                        /*******************************/
                          
                        if(res_obj['sell_order_level_'+buy_num_rule+'_enable'] == 'yes'){
                          $('#sell_order_level_'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#sell_order_level_'+buy_num_rule+'_enable').prop('checked',false);
                        }


                        /*******************************/
                        if(res_obj['enable_buy_rule_no_'+buy_num_rule] == 'yes'){
                          $('#enable_buy_rule_no_'+buy_num_rule).prop('checked',true);
                        }else{

                          var keep_data = $( ".buy_sell_box_"+buy_num_rule).detach();
                          keep_data.appendTo(".disable_buy_div");

                         $('#enable_buy_rule_no_'+buy_num_rule).prop('checked',false);

                        }

                        /*********Sell Part **********/
                        if(res_obj['enable_sell_rule_no_'+buy_num_rule] == 'yes'){
                          $('#enable_sell_rule_no_'+buy_num_rule).prop('checked',true);
                        }else{

                          var keep_sell_data = $( ".sell_box_"+buy_num_rule).detach();
                          keep_sell_data.appendTo(".disable_sell_append");

                         $('#enable_sell_rule_no_'+buy_num_rule).prop('checked',false);

                        }
                        /*********End of Sell part****/

                        

                        


                        if(res_obj['sell_percent_rule_'+buy_num_rule+'_enable'] == 'yes'){
                          $('#sell_percent_rule_'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#sell_percent_rule_'+buy_num_rule+'_enable').prop('checked',false);
                        }


                        if(res_obj['enable_sell_rule_no_'+buy_num_rule] == 'yes'){
                          $('#enable_sell_rule_no_'+buy_num_rule).prop('checked',true);
                        }else{
                         $('#enable_sell_rule_no_'+buy_num_rule).prop('checked',false);
                        }



                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                        if(res_obj['sell_last_candle_type'+buy_num_rule+'_enable'] == 'yes'){
                          $('#sell_last_candle_type'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#sell_last_candle_type'+buy_num_rule+'_enable').prop('checked',false);
                        }

                        if(res_obj['sell_last_candle_status'+buy_num_rule+'_enable'] == 'yes'){
                          $('#sell_last_candle_status'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#sell_last_candle_status'+buy_num_rule+'_enable').prop('checked',false);
                        }


                    
                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['sell_rejection_candle_type'+buy_num_rule+'_enable'] == 'yes'){
                          $('#sell_rejection_candle_type'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#sell_rejection_candle_type'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['sell_last_200_contracts_buy_vs_sell'+buy_num_rule+'_enable'] == 'yes'){
                          $('#sell_last_200_contracts_buy_vs_sell'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#sell_last_200_contracts_buy_vs_sell'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['sell_last_200_contracts_time'+buy_num_rule+'_enable'] == 'yes'){
                          $('#sell_last_200_contracts_time'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#sell_last_200_contracts_time'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['sell_last_qty_buyers_vs_seller'+buy_num_rule+'_enable'] == 'yes'){
                          $('#sell_last_qty_buyers_vs_seller'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#sell_last_qty_buyers_vs_seller'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                         /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['sell_last_qty_time'+buy_num_rule+'_enable'] == 'yes'){
                          $('#sell_last_qty_time'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#sell_last_qty_time'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                         /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['sell_last_5_minute_candle_buys_vs_seller'+buy_num_rule+'_enable'] == 'yes'){
                          $('#sell_last_5_minute_candle_buys_vs_seller'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#sell_last_5_minute_candle_buys_vs_seller'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                         /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                        if(res_obj['sell_score'+buy_num_rule+'_enable'] == 'yes'){
                          $('#sell_score'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#sell_score'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/



                         /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                         if(res_obj['sell_comment'+buy_num_rule+'_enable'] == 'yes'){
                          $('#sell_comment'+buy_num_rule+'_enable').prop('checked',true);
                        }else{
                         $('#sell_comment'+buy_num_rule+'_enable').prop('checked',false);
                        }
                        /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/



                        /*******************************/
                         /**********End of buy part for setting****/

                         /********** Multi select part********/

                         $('#buy_status_rule_'+buy_num_rule).multiselect('select', res_obj['buy_status_rule_'+buy_num_rule]);

                         $('#buy_trigger_type_rule_'+buy_num_rule).multiselect('select', res_obj['buy_trigger_type_rule_'+buy_num_rule]);


                          $('#buy_order_level_'+buy_num_rule).multiselect('select', res_obj['buy_order_level_'+buy_num_rule]);


                         $('#sell_status_rule_'+buy_num_rule).multiselect('select', res_obj['sell_status_rule_'+buy_num_rule]);

                         $('#sell_trigger_type_rule_'+buy_num_rule).multiselect('select', res_obj['sell_trigger_type_rule_'+buy_num_rule]);



                        $('#sell_order_level_'+buy_num_rule).multiselect('select', res_obj['sell_order_level_'+buy_num_rule]);

                         /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                         $('#last_candle_type'+buy_num_rule+'_sell').val(res_obj['last_candle_type'+buy_num_rule+'_sell']);
                         /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                         $('#last_candle_status'+buy_num_rule+'_sell').val(res_obj['last_candle_status'+buy_num_rule+'_sell']);

                         /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                         $('#rejection_candle_type'+buy_num_rule+'_sell').val(res_obj['rejection_candle_type'+buy_num_rule+'_sell']);
                         /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/


                         /***%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                         $('#last_200_contracts_buy_vs_sell'+buy_num_rule+'_sell').val(res_obj['last_200_contracts_buy_vs_sell'+buy_num_rule+'_sell']);
                         /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/


                         /***%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                         $('#last_200_contracts_time'+buy_num_rule+'_sell').val(res_obj['last_200_contracts_time'+buy_num_rule+'_sell']);
                         /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/


                          /***%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                         $('#last_qty_buyers_vs_seller'+buy_num_rule+'_sell').val(res_obj['last_qty_buyers_vs_seller'+buy_num_rule+'_sell']);
                         /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/



                         /***%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                         $('#last_qty_time'+buy_num_rule+'_sell').val(res_obj['last_qty_time'+buy_num_rule+'_sell']);
                         /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/


                         /***%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                         $('#last_5_minute_candle_buys_vs_seller'+buy_num_rule+'_sell').val(res_obj['last_5_minute_candle_buys_vs_seller'+buy_num_rule+'_sell']);
                         /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/

                         /***%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                         $('#score'+buy_num_rule+'_sell').val(res_obj['score'+buy_num_rule+'_sell']);
                         /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/


                          /***%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                          $('#comment'+buy_num_rule+'_sell').val(res_obj['comment'+buy_num_rule+'_sell']);
                         /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/


                          /**********End of Multi select part********/
                                        
										
										
										
                          /***************************/
                          $('#buy_volume_rule_'+buy_num_rule).val( (res_obj['buy_volume_rule_'+buy_num_rule]));
                          /***************************/

                          

                             /***************************/
                          $('#buy_virtural_rule_'+buy_num_rule).val((res_obj['buy_virtural_rule_'+buy_num_rule]));
                          /***************************/
                            
                           /************************/
                            $('#done_pressure_rule_'+buy_num_rule+'_buy').val(res_obj['done_pressure_rule_'+buy_num_rule+'_buy']);
                           /***********************/ 


                           /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                             $('#last_candle_type'+buy_num_rule+'_buy').val(res_obj['last_candle_type'+buy_num_rule+'_buy']);
                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                             $('#rejection_candle_type'+buy_num_rule+'_buy').val(res_obj['rejection_candle_type'+buy_num_rule+'_buy']);
                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                             $('#last_200_contracts_buy_vs_sell'+buy_num_rule+'_buy').val(res_obj['last_200_contracts_buy_vs_sell'+buy_num_rule+'_buy']);
                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/



                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                             $('#last_200_contracts_time'+buy_num_rule+'_buy').val(res_obj['last_200_contracts_time'+buy_num_rule+'_buy']);
                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                             $('#last_qty_buyers_vs_seller'+buy_num_rule+'_buy').val(res_obj['last_qty_buyers_vs_seller'+buy_num_rule+'_buy']);
                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                             $('#last_qty_time'+buy_num_rule+'_buy').val(res_obj['last_qty_time'+buy_num_rule+'_buy']);
                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                             $('#last_5_minute_candle_buys_vs_seller'+buy_num_rule+'_buy').val(res_obj['last_5_minute_candle_buys_vs_seller'+buy_num_rule+'_buy']);
                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                             $('#score'+buy_num_rule+'_buy').val(res_obj['score'+buy_num_rule+'_buy']);
                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              $('#comment'+buy_num_rule+'_buy').val(res_obj['comment'+buy_num_rule+'_buy']);
                             /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                           /************************/
                            $('#big_seller_percent_compare_rule_'+buy_num_rule+'_buy').val(res_obj['big_seller_percent_compare_rule_'+buy_num_rule+'_buy']);
                           /***********************/ 


                           /************************/
                            $('#closest_black_wall_rule_'+buy_num_rule+'_buy').val(res_obj['closest_black_wall_rule_'+buy_num_rule+'_buy']);
                           /***********************/ 


                           /************************/
                            $('#closest_yellow_wall_rule_'+buy_num_rule+'_buy').val(res_obj['closest_yellow_wall_rule_'+buy_num_rule+'_buy']);
                           /***********************/ 


                           /************************/
                            $('#seven_level_pressure_rule_'+buy_num_rule+'_buy').val(res_obj['seven_level_pressure_rule_'+buy_num_rule+'_buy']);
                           /***********************/


                           /************************/
                           
                           $('#buyer_vs_seller_rule_'+buy_num_rule+'_buy').val(res_obj['buyer_vs_seller_rule_'+buy_num_rule+'_buy']);
                            
                           /***********************/ 


                           /************* Sell part ****************/
                           $('#sell_volume_rule_'+buy_num_rule).val((res_obj['sell_volume_rule_'+buy_num_rule]));


                          
                          $('#sell_virtural_rule_'+buy_num_rule).val((res_obj['sell_virtural_rule_'+buy_num_rule]));                              

                            $('#done_pressure_rule_'+buy_num_rule).val((res_obj['done_pressure_rule_'+buy_num_rule]));


                            $('#big_seller_percent_compare_rule_'+buy_num_rule).val(res_obj['big_seller_percent_compare_rule_'+buy_num_rule]);


                            $('#closest_black_wall_rule_'+buy_num_rule).val(res_obj['closest_black_wall_rule_'+buy_num_rule]);


                            $('#closest_yellow_wall_rule_'+buy_num_rule).val(res_obj['closest_yellow_wall_rule_'+buy_num_rule]);

                            $('#seven_level_pressure_rule_'+buy_num_rule).val(res_obj['seven_level_pressure_rule_'+buy_num_rule]);
                              

                            $('#sell_percent_rule_'+buy_num_rule).val(res_obj['sell_percent_rule_'+buy_num_rule]);

                            $('#seller_vs_buyer_rule_'+buy_num_rule+'_sell').val(res_obj['seller_vs_buyer_rule_'+buy_num_rule+'_sell']);

                            
                           /****************************/


                      }//End of for loop
                     
                      
                    
              }else{
                $('.show_hide_for_barrier_trigger').show();
                $('.show_barrier_trigger').hide();
              }  






            $('#apply_factor').val(res_obj.apply_factor);
            $('#box_trigger_score').val(res_obj.box_trigger_score);

            

            if(res_obj.cancel_trade == 'cancel'){
                $('#cancel_trade').prop('checked',true);
                $('.look_back_hour_show_hide').show();
                $('#look_back_hour').val(res_obj.look_back_hour);
                
            }else{
              $('#cancel_trade').prop('checked',false);
               $('.look_back_hour_show_hide').hide();
            }


            if(res_obj.bottom_demand_rejection == 'yes'){
                $('#bottom_demand_rejection').prop('checked',true);
            }else{
              $('#bottom_demand_rejection').prop('checked',false);
            }



            if(res_obj.bottom_supply_rejection == 'yes'){
                $('#bottom_supply_rejection').prop('checked',true);
            }else{
              $('#bottom_supply_rejection').prop('checked',false);
            }


          if(res_obj.check_high_open == 'yes'){
             $('#check_high_open').prop('checked',true);
          }else{
             $('#check_high_open').prop('checked',false);
          }

          if(res_obj.is_previous_blue_candle == 'yes'){
             $('#is_previous_blue_candle').prop('checked',true);
          }else{
             $('#is_previous_blue_candle').prop('checked',false);
          }
          

        }
      })

      $(document).on('change','.aggressive_stop_rule',function(){

        if($(this).val() == 'stop_loss_rule_3'){
          $('.aggressive_stop_3_rule_show_hide').show();
        }else{
           $('.aggressive_stop_3_rule_show_hide').hide();
        }

        if($(this).val() == 'stop_loss_rule_2'){
          $('.factor_show_hide').show();
        }else{
          $('.factor_show_hide').hide();
        }
      })//End agggressove rule changed
  })



    $(".checkbox").change(function() {
      if($('#cancel_trade').prop('checked')){
        $('.look_back_hour_show_hide').show();
      }else{
        $('.look_back_hour_show_hide').hide();
      }
    });

    $(document).ready(function(){

      $(document).on('click','#clint_info_btn',function(){
        $('#trigger_setting_id').submit();
        
      })



      $('#status').multiselect({
        nonSelectedText: 'Select Status',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth:'100%'
      });

      $('.buy_status_rule').multiselect({
        nonSelectedText: 'Select Status',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth:'100%'
      });


      $('.sell_status_rule').multiselect({
        nonSelectedText: 'Select Status',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth:'100%'
      });


      $('.buy_trigger_type').multiselect({
        nonSelectedText: 'Select Trigger Status',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth:'100%'
      });

       $('.buy_order_level').multiselect({
        nonSelectedText: 'Select Order Level',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth:'100%'
      });



      $('.sell_order_level').multiselect({
        nonSelectedText: 'Select Order Level',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth:'100%'
      });


  
      

    //
    $('form').on('focus', 'input[type=number]', function (e) {
    $(this).on('mousewheel.disableScroll', function (e) {
      e.preventDefault()
    })
    })
    $('form').on('blur', 'input[type=number]', function (e) {
     $(this).off('mousewheel.disableScroll')
    })
    
 
   
                

    });
	
	
	
	


</script>