<link rel="stylesheet" href="<?php echo ASSETS; ?>cdn_links/bootstrap-multiselect-master/dist/css/bootstrap-multiselect.css" />
<script src="<?php echo ASSETS; ?>cdn_links/bootstrap-multiselect-master/dist/js/bootstrap-multiselect.js"></script>




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
      <li><a href="<?php echo SURL; ?>/admin/settings">Settings</a></li>
      <li><a href="<?php echo SURL; ?>admin/settings/enable_google_auth">Google Authentication</a></li>
      <li><a href="<?php echo SURL; ?>admin/settings/password_change">Change Password</a></li>
      <?php if ($this->session->userdata('user_role') == 1 || $this->session->userdata('admin_id') == 1) {
    ?>
         <li><a href="<?php echo SURL; ?>admin/settings/update_candle">Update Candle</a></li>
         <li><a href="<?php echo SURL; ?>admin/candle_base">Base Candle Settings</a></li>
         <li><a href="<?php echo SURL; ?>admin/buy_orders/buy_sell_trigger_log">Buy Order Trigger</a></li>
         <li><a href="<?php echo SURL; ?>admin/settings/trigger_setting">Trigger Setting</a></li>
         <li class="active"><a href="<?php echo SURL; ?>admin/settings/triggers_global_setting">Trigger_3 Setting</a></li>
        <?php
}
?>



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
                          <div class="col-md-12 ">
                              <div class="form-group col-md-12">
                                <label class="control-label" for="coin">Select Coin</label>
                                <select class="form-control" name="coin" id="coin" >
                                  <?php
                                      foreach ($coins as $coin) {
                                      ?>
                                      <option value="<?php echo $coin['symbol'] ?>"><?php echo $coin['symbol']; ?></option>
                                      <?php }
                                      ?>
                                </select>
                              </div>
                          </div>

                             <input type="hidden"  id="trigger_level" name="trigger_level" value="level_1">

                            <div class="col-md-12">
                              <div class="form-group col-md-12">
                                <label class="control-label" for="hour">Select Order Mode </label>
                                <select class="form-control order_mode" name="order_mode">
                                      <option value="live">(Real time and test live)</option>
                                     <option value="test">Simulator Test</option>
                                </select>
                              </div>
                            </div>


                            <div class="col-md-12">
                              <div class="form-group col-md-12">
                                <label class="control-label" for="hour">Aggressive stop rule</label>
                                <select class="form-control aggressive_stop_rule" name="aggressive_stop_rule">
                                   <option value="">select aggressive Rule</option>
                                   <option value="stop_loss_rule_1">stop_loss_rule_1</option>
                                   <option value="stop_loss_rule_2">stop_loss_rule_2</option>
                                   <option value="stop_loss_rule_3">stop_loss_rule_3</option>
                                   <option value="stop_loss_rule_big_wall">stop_loss_rule_big_wall</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-12">
                              <div class="form-group col-md-12">
                                <label class="control-label" for="hour">Select Trigger </label>
                                <select class="form-control triggers_type" name="triggers_type">
                                   <option value="">select trigger</option>
                                   
                                   <option value="trigger_1">trigger_1</option>
                                   <option value="trigger_2">trigger_2</option>
                                   <option value="box_trigger_3">box_trigger_3</option>
                                   <option value="rg_15">Trigger_rg_15</option>
                                  <option value="barrier_trigger">barrier_trigger</option>
                                  <option value="barrier_percentile_trigger">barrier_percentile_trigger</option>
                                  <option value="market_trend_trigger">market_trend_trigger</option>
                                </select>
                                
                              </div>
                            </div>



                            <!-- -->


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







                            <!--  End of cancel trade-->


                            <!--  End of cancel trade-->
                          </div><!--  End of hide and show-->


                          </div>
                          <!--  End  Trigger_1 -->


                          </div>
                  <!--  End  -->

                </div> <!--  End of if not barrier trigger-->


                <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%  -->

                             <div class="box_trigger_3_hide_show" style="display:none">

                                <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%  -->
                                <ul class="nav nav-tabs box_trigger_tr_tab" id="#tabs">
                                    <li class=" active" id="box_level_tab_1"><a data-toggle="tab" href="#box_trigger_level_1">Leve 1</a></li>

                                    <li class="" id="box_level_tab_2"><a data-toggle="tab" href="#box_trigger_level_2">Level 2</a></li>

                                    <li class="" id="box_level_tab_3"><a data-toggle="tab" href="#box_trigger_level_3">Level 3</a></li>

                                    <li class="" id="box_level_tab_4"><a data-toggle="tab" href="#box_trigger_level_4">Level 4</a></li>

                                    <li class="" id="box_level_tab_5"><a data-toggle="tab" href="#box_trigger_level_5">Level 5</a></li>

                                    <li class="" id="box_level_tab_6"><a data-toggle="tab" href="#box_trigger_level_6">Level 6</a></li>

                                    <li class="" id="box_level_tab_7"><a data-toggle="tab" href="#box_trigger_level_7">Level 7</a></li>

                                    <li class="" id="box_level_tab_8"><a data-toggle="tab" href="#box_trigger_level_8">Level 8</a></li>
                                    <li class="" id="box_level_tab_9"><a data-toggle="tab" href="#box_trigger_level_9">Level 9</a></li>
                                    <li class="" id="box_level_tab_10"><a data-toggle="tab" href="#box_trigger_level_10">Level 10</a></li>

                                </ul>

                                <!-- %%%%%%%%%%%%  start of tab content %%%%%%%% -->
                                <div class="tab-content ">

                                  <!-- Append Box Trigger One -->
                                  <div id="box_trigger_level_1" class="tab-pane fade in active">
                                    <div id="append_box_level_1">


                                        <!-- Score for box trigger -->
                                        <div class="col-md-12 " >
                                          <div class="form-group col-md-12">
                                            <label class="control-label" for="hour">Score</label>
                                            <input type="text" class="form-control" name="box_trigger_score" id="box_trigger_score">
                                          </div>
                                        </div>
                                        <!-- End of box trigger score --->

                                        <!--  Cancel trade -->
                                        <div class="col-md-12 " >
                                          <div class="form-group col-md-12">
                                            <label class="control-label" for="hour">stop loss rule_2 apply factor</label>
                                            <input type="text" class="form-control" name="apply_factor" id="apply_factor">
                                          </div>
                                        </div>
                                        <!--  End of cancel trade-->

                                        <div class="form-group col-md-12">
                                          <label class="control-label" for="hour">look back hour to cancel trade</label>
                                          <input type="text" class="form-control" name="look_back_hour" id="look_back_hour">
                                        </div>

                                        <!-- set buy price percentage from last swing low value -->
                                        <div class="form-group col-md-12">
                                          <label class="control-label" for="hour">Percentage to calculate buy price from last swing candle low value (Recommended 30%)</label>
                                          <input type="text" class="form-control" name="box_trigger_buy_price_percentage" id="box_trigger_buy_price_percentage">
                                        </div>

                                        <div class="form-group col-md-12">
                                          <label class="control-label" for="hour">(Sell order when define profit % meet)</label>
                                          <input type="text" class="form-control" name="box_trigger_buy_sell_percentage" id="box_trigger_buy_sell_percentage">
                                        </div>

                                        <div class="form-group col-md-12">
                                          <label class="control-label" for="hour">(set initial trail stop with defined percentage from buy price)</label>
                                          <input type="text" class="form-control" name="box_trigger_buy_stop_loss_percentage" id="box_trigger_buy_stop_loss_percentage">
                                        </div>
                                        <!-- Bottom Demand Rejection -->
                                        <div class="form-group col-md-12">
                                          <div class="checkbox">
                                             <label><input type="checkbox" value="yes" id="bottom_demand_rejection" name="bottom_demand_rejection">Bottom Demand Rejection</label>
                                          </div>
                                        </div>

                                       <!-- Bottom Supply Rejection -->
                                      <div class="form-group col-md-12">
                                          <div class="checkbox">
                                              <label><input type="checkbox" value="yes" id="bottom_supply_rejection" name="bottom_supply_rejection">Bottom Supply Rejection</label>
                                          </div>
                                      </div>
                                        <!-- %%%%%%%%   Cancelled Trades %%%%%%  -->
                                        <div class="col-md-12">
                                          <div class="form-group col-md-12">
                                            <div class="checkbox">
                                                <label><input type="checkbox" class="checkbox" value="cancel" id="cancel_trade" name="cancel_trade">Cancel Trade</label>
                                            </div>
                                          </div>
                                        </div>

                                        <!-- Check Heigh Open  -->
                                        <div class="form-group col-md-12">
                                          <div class="checkbox">
                                              <label><input type="checkbox" value="yes" id="check_high_open" name="check_high_open">Check Heigh Open</label>
                                          </div>
                                        </div>

                                        <!-- %%%%%%%% Check of previous is Blue candel %%%% -->
                                        <div class="form-group col-md-12">
                                          <div class="checkbox">
                                               <label><input type="checkbox" value="yes" id="is_previous_blue_candle" name="is_previous_blue_candle">is_previous_blue_candle</label>
                                          </div>
                                        </div>

                                        

                                    </div> <!-- End of append Level 1-->
                                  </div><!-- End of box trigger 1 -->


                                  <!-- Second Level box tab Content  -->
                                  <div id="box_trigger_level_2" class="tab-pane fade">
                                    <div id="append_box_level_2">
                                    </div>
                                  </div>
                                  <!-- End of Second Level Box Content -->


                                  <!-- Third Level box tab Content  -->
                                  <div id="box_trigger_level_3" class="tab-pane fade">
                                    <div id="append_box_level_3">
                                    </div>
                                  </div>
                                  <!-- End of Third Level Box Content -->


                                  <!-- Forth Level box tab Content  -->
                                  <div id="box_trigger_level_4" class="tab-pane fade">
                                    <div id="append_box_level_4">
                                    </div>
                                  </div>
                                  <!-- End of Forth Level Box Content -->


                                  <!-- Five Level box tab Content  -->
                                  <div id="box_trigger_level_5" class="tab-pane fade">
                                    <div id="append_box_level_5">
                                    </div>
                                  </div>
                                  <!-- End of Five Level Box Content -->


                                  <!-- six Level box tab Content  -->
                                  <div id="box_trigger_level_6" class="tab-pane fade">
                                    <div id="append_box_level_6">
                                    </div>
                                  </div>
                                  <!-- End of six Level Box Content -->


                                  <!-- seven Level box tab Content  -->
                                  <div id="box_trigger_level_7" class="tab-pane fade">
                                    <div id="append_box_level_7">
                                    </div>
                                  </div>
                                  <!-- End of seven Level Box Content -->


                                  <!-- Eight Level box tab Content  -->
                                  <div id="box_trigger_level_8" class="tab-pane fade">
                                    <div id="append_box_level_8">
                                    </div>
                                  </div>
                                  <!-- End of Eight Level Box Content -->

                                  <!-- Nine Level box tab Content  -->
                                  <div id="box_trigger_level_9" class="tab-pane fade">
                                    <div id="append_box_level_9">
                                    </div>
                                  </div>
                                  <!-- End of Nine Level Box Content -->

                                  <!-- ten Level box tab Content  -->
                                  <div id="box_trigger_level_10" class="tab-pane fade">
                                    <div id="append_box_level_10">
                                    </div>
                                  </div>
                                  <!-- End of ten Level Box Content -->

                                </div><!-- End of Tab Content -->
                             </div> <!-- End of Box TRIGGER 3 -->
                             <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%  -->



                        <!-- %%%%%%%%%%%%%%%%%%%% Market Trends %%%%%%%%%%%%%%%%%%%%%%%%%  -->

                                

                         <!-- %%%%%%%%%%%%%%%%%%%% End  Market Trends %%%%%%%%%%%%%%%%%%%%%%%%%  -->




                        <!-- ////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////
                        ////////////////////                                   /////////////////
                        ////////////////////                                  /////////////////////////////////
                        //////////////////// BARIER TRIGGER PERCENTILE       /////////////////////////////////
                        ////////////////////                                ////////////////////////////////
                        ////////////////////                               /////////////////
                        ////////////////////                               /////////////////
                        ////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////
                        //////////////////////////////////////////////////////////////// -->
                        <div class="show_hide_barrier_percentile global_setting buy_sell_box" style="display:none">

                        <!-- %%%%%%%%%%%%%%%%  Leves Headings %%%%%%%% -->
                            <ul class="nav nav-tabs percentile_tr_tab" id="#tabs">
                                 <li class="active_inactive_percentile_level active" id="level_tab_1"><a data-toggle="tab" href="#barrier_percentile_level_1">Level 1</a></li>

                                <li class="active_inactive_percentile_level" id="level_tab_2"><a data-toggle="tab" href="#barrier_percentile_level_2">Level 2</a></li>

                                <li class="active_inactive_percentile_level" id="level_tab_3"><a data-toggle="tab" href="#barrier_percentile_level_3">Level 3</a></li>

                                <li class="active_inactive_percentile_level" id="level_tab_4"><a data-toggle="tab" href="#barrier_percentile_level_4">Level 4</a></li>

                                <li class="active_inactive_percentile_level" id="level_tab_5"><a data-toggle="tab" href="#barrier_percentile_level_5">Level 5</a></li>

                                <li class="active_inactive_percentile_level" id="level_tab_6"><a data-toggle="tab" href="#barrier_percentile_level_6">Level 6</a></li>

                                <li class="active_inactive_percentile_level" id="level_tab_7"><a data-toggle="tab" href="#barrier_percentile_level_7">Level 7</a></li>

                                <li class="active_inactive_percentile_level" id="level_tab_8"><a data-toggle="tab" href="#barrier_percentile_level_8">Level 8</a></li>


                                <li class="active_inactive_percentile_level" id="level_tab_9"><a data-toggle="tab" href="#barrier_percentile_level_9">Level 9</a></li>

                                <li class="active_inactive_percentile_level" id="level_tab_10"><a data-toggle="tab" href="#barrier_percentile_level_10">Level 10</a></li>
                            </ul>


                             <!-- Percentile Trigger Leves -->


                             <div class="tab-content ">
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 1 Tabs -->

                                <div id="barrier_percentile_level_1" class="tab-pane fade in active">
                                  <div id="append_percentile_level_1">
                                    <h4>Barrier percentile trigger <span style="color:yellowgreen;font-size:16px" id="percentile_level_num"> 1</span></h4>
                                    <ul class="nav nav-tabs">
                                      <li class="active"><a data-toggle="tab" href="#barrier_percentile_buy">Buy Rules</a></li>
                                      <li><a data-toggle="tab" href="#barrier_percentile_sell">Sell</a></li>
                                      <li><a data-toggle="tab" href="#barrier_percentile_stop_loss">Stop Loss Rules</a></li>
                                    </ul>

                                    <div class="tab-content ">
                                      <!--barrier percentile   Buy part Start -->
                                      <div id="barrier_percentile_buy" class="tab-pane fade in active">
                                            <!--  ************* Buy Part *********** -->

                                            <!-- %%%%%%%%%%% Enabe Buy Rules  %%%%%%%%%%%%%%%%%%  -->
                                              <div class="row" style="margin-top: 30px;">
                                                <div class="col-md-6">
                                                    <h2 class="buy_sell_box_heading">
                                                    <dd class="pull-left">
                                                    Buy Percentile setting
                                                    </dd>
                                                    <label class="pull-right" style="margin-top: 11px; margin-bottom: 0;">
                                                    <strong>
                                                    Enable Disable Buy percentile Trigger
                                                    </strong>
                                                    </label>
                                                    </h2>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="onoffswitch">
                                                            <input type="checkbox" name="enable_buy_barrier_percentile" class="onoffswitch-checkbox" id="enable_buy_barrier_percentile" value="yes">
                                                            <label class="onoffswitch-label" for="enable_buy_barrier_percentile">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                            </label>
                                                    </div>
                                                </div>
                                              </div>

                                            <!-- %%%%% Enable if previous candel is blue %%%% -->
                                            <div class="row" >
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">Previous Candel Status</label>
                                                <select class="form-control" name="percentile_trigger_last_candle_type" id="percentile_trigger_last_candle_type">
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

                                              <div class="col-md-6">
                                                <div class="onoffswitch">
                                                  <input type="checkbox" name="barrier_percentile_is_previous_blue_candel" class="onoffswitch-checkbox" id="barrier_percentile_is_previous_blue_candel" value="yes">
                                                  <label class="onoffswitch-label" for="barrier_percentile_is_previous_blue_candel">
                                                  <span class="onoffswitch-inner"></span>
                                                  <span class="onoffswitch-switch"></span>
                                                  </label>
                                                </div>
                                              </div>
                                            </div>





                                             <!-- %%%%%%%%% By default stop loss in percentage -->
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">By Default Stop Loss in Percentage (Equal To Recommended)</label>
                                                  <input type="text" step="any" class="form-control" name="barrier_percentile_trigger_default_stop_loss_percenage" id="barrier_percentile_trigger_default_stop_loss_percenage" value="">
                                                </div>
                                            </div>


                                            <!-- %%%%%%%%%%% Barrier Range percentage %%%% -->

                                             <div class="row">
                                                <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">
                                                   if  current market value between in the range of percentage from low swing point (Then buy trade) 
                                                  </label>
                                                  <input type="text" step="any" class="form-control" name="barrier_percentile_trigger_barrier_range_percentage" id="barrier_percentile_trigger_barrier_range_percentage" value="">
                                                </div>
                                            </div>


                                            <!--  %%%%%%%%%% End of Buy Rules %%%%%%%%%%%%%%%%%%  -->

                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(Black Wall Greater From defined percentile)</label>

                                                  <select class="form-control" id="barrier_percentile_trigger_buy_black_wall" name="barrier_percentile_trigger_buy_black_wall">
                                                  <option value="1">top 1% </option>
                                                  <option value="2">top 2% </option>
                                                  <option value="3">top 3% </option>
                                                  <option value="4">top 4% </option>
                                                  <option value="5">top 5% </option>
                                                  <option value="10">top 10%</option>
                                                  <option value="15">top 15%</option>
                                                  <option value="20">top 20%</option>
                                                  <option value="25">top 25%</option>
                                                  </select>


                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_buy_black_wall_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_buy_black_wall_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_buy_black_wall_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>
                                            <!--  virtual barrir -->
                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(Virtual Barrier Greater From defined percentile)(Support  Barrier)</label>

                                                  <select class="form-control" id="barrier_percentile_trigger_buy_virtual_barrier" name="barrier_percentile_trigger_buy_virtual_barrier">
                                                  
                                                  <option value="1">top 1% </option>
                                                  <option value="2">top 2% </option>
                                                  <option value="3">top 3% </option>
                                                  <option value="4">top 4% </option>
                                                  <option value="5">top 5% </option>
                                                  <option value="10">top 10%</option>
                                                  <option value="15">top 15%</option>
                                                  <option value="20">top 20%</option>
                                                  <option value="25">top 25%</option>
                                                  </select>


                                              </div>
                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_buy_virtual_barrier_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_buy_virtual_barrier_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_buy_virtual_barrier_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="vb_p" style="display: inline-block;float: right;margin-top: -40px;">Virtual Barrier Percentile</span>
                                              </div>
                                            </div>



                                            <!-- %%%%% -- Virtual Barrier Resistance -- %%%%%% -->
                                              

                                              <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">Virtual Barrier (Less Then) From defined percentile (Resistance Barrier)</label>

                                                  <select class="form-control" id="barrier_percentile_trigger_sell_virtual_barrier_for_buy" name="barrier_percentile_trigger_sell_virtual_barrier_for_buy">
                                                  <option value="1">Bottom 1% </option>
                                                  <option value="2">Bottom 2% </option>
                                                  <option value="3">Bottom 3% </option>
                                                  <option value="4">Bottom 4% </option>
                                                  <option value="5">Bottom 5% </option>
                                                  <option value="10">Bottom 10%</option>
                                                  <option value="15">Bottom 15%</option>
                                                  <option value="20">Bottom 20%</option>
                                                  <option value="25">Bottom 25%</option>
                                                  </select>


                                              </div>
                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_sell_virtual_barrier_for_buy_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_sell_virtual_barrier_for_buy_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_sell_virtual_barrier_for_buy_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>

                                            <!-- %%%%% -- End of Virtual Barrier Resistance -- %%%%%% -->

                                            <!--%%%%%%%%%%%%%%% & LEVEL PRESSUE -->
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="control-label" for="hour">(Seven Level Greater From defined percentile)</label>

                                                    <select class="form-control" id="barrier_percentile_trigger_buy_seven_level_pressure" name="barrier_percentile_trigger_buy_seven_level_pressure">
                                                    <option value="1">top 1% </option>
                                                    <option value="2">top 2% </option>
                                                    <option value="3">top 3% </option>
                                                    <option value="4">top 4% </option>
                                                    <option value="5">top 5% </option>
                                                    <option value="10">top 10%</option>
                                                    <option value="15">top 15%</option>
                                                    <option value="20">top 20%</option>
                                                    <option value="25">top 25%</option>
                                                    </select>


                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>&nbsp;</label>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="barrier_percentile_trigger_buy_seven_level_pressure_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_buy_seven_level_pressure_apply" value="yes" >
                                                        <label class="onoffswitch-label" for="barrier_percentile_trigger_buy_seven_level_pressure_apply">
                                                        <span class="onoffswitch-inner"></span>
                                                        <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                    <span class="alert alert-success" id="sl_p" style="display: inline-block;float: right;margin-top: -40px;">Seven Level Percentile</span>
                                                </div>
                                            </div>



                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">Last 200 Contract Buyers Vs Sellers <b>Greater</b> then recommended </label>
                                                  <input type="text" step="any" class="form-control" name="barrier_percentile_trigger_buy_last_200_contracts_buy_vs_sell" id="barrier_percentile_trigger_buy_last_200_contracts_buy_vs_sell" value="">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>&nbsp;</label>
                                                      <div class="onoffswitch">
                                                        <input type="checkbox" name="barrier_percentile_trigger_buy_last_200_contracts_buy_vs_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_buy_last_200_contracts_buy_vs_sell_apply" value="yes" >
                                                        <label class="onoffswitch-label" for="barrier_percentile_trigger_buy_last_200_contracts_buy_vs_sell_apply">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                      </div>

                                                </div>
                                            </div>




                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">Last 200 Contract Time <b> Less Then</b> recommended Value </label>
                                                  <input type="text" step="any" class="form-control" name="barrier_percentile_trigger_buy_last_200_contracts_time" id="barrier_percentile_trigger_buy_last_200_contracts_time" value="">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>&nbsp;</label>
                                                      <div class="onoffswitch">
                                                        <input type="checkbox" name="barrier_percentile_trigger_buy_last_200_contracts_time_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_buy_last_200_contracts_time_apply" value="yes" >
                                                        <label class="onoffswitch-label" for="barrier_percentile_trigger_buy_last_200_contracts_time_apply">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                      </div>

                                                </div>
                                            </div>



                                          <div class="row">
                                              <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">Last Qty Contract Buyers Vs Sellers (Greater Then Recommended) (T1 LTC Buyers Vs Sellers)</label>
                                                <input type="text" step="any" class="form-control" name="barrier_percentile_trigger_buy_last_qty_contracts_buyer_vs_seller" id="barrier_percentile_trigger_buy_last_qty_contracts_buyer_vs_seller" value="">
                                              </div>
                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                    <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_buy_last_qty_contracts_buyer_vs_seller_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_buy_last_qty_contracts_buyer_vs_seller_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_buy_last_qty_contracts_buyer_vs_seller_apply">
                                                          <span class="onoffswitch-inner"></span>
                                                          <span class="onoffswitch-switch"></span>
                                                      </label>
                                                    </div>

                                              </div>
                                          </div>

                                          <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                          <div class="row">
                                              <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">Last qty Contract time(Less then)(T3 Ltc Time)</label>
                                                <input type="text" step="any" class="form-control " name="barrier_percentile_trigger_buy_last_qty_contracts_time" id="barrier_percentile_trigger_buy_last_qty_contracts_time" value="">
                                              </div>
                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                    <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_buy_last_qty_contracts_time_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_buy_last_qty_contracts_time_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_buy_last_qty_contracts_time_apply">
                                                          <span class="onoffswitch-inner"></span>
                                                          <span class="onoffswitch-switch"></span>
                                                      </label>
                                                    </div>

                                              </div>
                                          </div>
                                        <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->


                                        <!-- %%%%%%%%%%%%% 5 Minute Rolling Candel %%%%%%%%%%%%% -->
                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(5 Minute Rolling Candel Greater From defined percentile)(T1Cot)(</label>
                                                  <select class="form-control" id="barrier_percentile_trigger_5_minute_rolling_candel" name="barrier_percentile_trigger_5_minute_rolling_candel">
                                                  <option value="1">top 1% </option>
                                                  <option value="2">top 2% </option>
                                                  <option value="3">top 3% </option>
                                                  <option value="4">top 4% </option> 
                                                  <option value="5">top 5% </option>
                                                  <option value="10">top 10%</option>
                                                  <option value="15">top 15%</option>
                                                  <option value="20">top 20%</option>
                                                  <option value="25">top 25%</option>
                                                  </select>
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_5_minute_rolling_candel_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_5_minute_rolling_candel_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_5_minute_rolling_candel_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="fv_m_p" style="display: inline-block;float: right;margin-top: -40px;">Buy Vs Sell (5 min) Percentile</span>
                                              </div>
                                            </div>
                                        <!-- %%%%%%%%%%%%% End if 5 Minute Rolling Candel %%%%%% -->


                                        <!-- %%%%%%%%%%%%% 15 Minute Rolling Candel %%%%%%%%%%%%% -->
                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(15 Minute Rolling Candel Greater From defined percentile)(T4 Cot)</label>
                                                  <select class="form-control" id="barrier_percentile_trigger_15_minute_rolling_candel" name="barrier_percentile_trigger_15_minute_rolling_candel">
                                                  <option value="1">top 1% </option>
                                                  <option value="2">top 2% </option>
                                                  <option value="3">top 3% </option>
                                                  <option value="4">top 4% </option>
                                                  <option value="5">top 5% </option>
                                                  <option value="10">top 10%</option>
                                                  <option value="15">top 15%</option>
                                                  <option value="20">top 20%</option>
                                                  <option value="25">top 25%</option>
                                                  </select>
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_15_minute_rolling_candel_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_15_minute_rolling_candel_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_15_minute_rolling_candel_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="fif_m_p" style="display: inline-block;float: right;margin-top: -40px;">Buy Vs Sell (15 min) Percentile</span>
                                              </div>
                                            </div>
                                        <!-- %%%%%%%%%%%%% End if 5 Minute Rolling Candel %%%%%% -->




                                        <!-- %%%%%%%%%%%%% Buyers %%%%%%%%%%%%% -->
                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(15 Minute)(Buyers Should be Greater Then Top percentile)(T4 Cot Buyers)</label>
                                                  <select class="form-control" id="barrier_percentile_trigger_buyers_buy" name="barrier_percentile_trigger_buyers_buy">
                                                  <option value="1">top 1% </option>
                                                  <option value="2">top 2% </option>
                                                  <option value="3">top 3% </option>
                                                  <option value="4">top 4% </option>
                                                  <option value="5">top 5% </option>
                                                  <option value="10">top 10%</option>
                                                  <option value="15">top 15%</option>
                                                  <option value="20">top 20%</option>
                                                  <option value="25">top 25%</option>
                                                  </select>
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_buyers_buy_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_buyers_buy_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_buyers_buy_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="b_p" style="display: inline-block;float: right;margin-top: -40px;">Buyers (5 min) Percentile</span>
                                              </div>

                                            </div>
                                        <!-- %%%%%%%%%%%%% End of Buyers %%%%%% -->


                                        <!-- %%%%%%%%%%%%% SELLERS %%%%%%%%%%%%% -->
                                             <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(15 Minute)(Sellers Should be Less Then Bottom percentile)(T4 Cot Sellers)</label>
                                                  <select class="form-control" id="barrier_percentile_trigger_sellers_buy" name="barrier_percentile_trigger_sellers_buy">
                                                  <option value="1">  Bottom 1% </option>
                                                  <option value="2">  Bottom 2% </option>
                                                  <option value="3">  Bottom 3% </option>
                                                  <option value="4">  Bottom 4% </option>  
                                                  <option value="5">  Bottom 5% </option>
                                                  <option value="10"> Bottom 10%</option>
                                                  <option value="15"> Bottom 15%</option>
                                                  <option value="20"> Bottom 20%</option>
                                                  <option value="25"> Bottom 25%</option>
                                                  </select>
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_sellers_buy_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_sellers_buy_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_sellers_buy_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="s_p" style="display: inline-block;float: right;margin-top: -40px;">Sellers (5 min) Percentile</span>
                                              </div>
                                            </div>
                                        <!-- %%%%%%%%%%%%% End of SELLERS %%%%%% -->


                                        <!-- %%%%%%%%%%%%-- 15 Minute Time ago-- %% -->
                                          
                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">(15 Minute last time ago Should be Less Then Bottom percentile)(T3 LTC Time)</label>
                                                <select class="form-control" id="barrier_percentile_trigger_15_minute_last_time_ago"name="barrier_percentile_trigger_15_minute_last_time_ago">
                                                <option value="1">Bottom 1% </option>
                                                <option value="2">Bottom 2% </option>
                                                <option value="3">Bottom 3% </option>
                                                <option value="4">Bottom 4% </option>  
                                                <option value="5">Bottom 5% </option>
                                                <option value="10">Bottom 10%</option>
                                                <option value="15">Bottom 15%</option>
                                                <option value="20">Bottom 20%</option>
                                                <option value="25">Bottom 25%</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="barrier_percentile_trigger_15_minute_last_time_ago_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_15_minute_last_time_ago_apply" value="yes" >
                                                    <label class="onoffswitch-label" for="barrier_percentile_trigger_15_minute_last_time_ago_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                                <span class="alert alert-success" id="min_15_tm" style="display: inline-block;float: right;margin-top: -40px;"></span>
                                            </div>
                                          </div>

                                        <!-- %%%%%%%%%%%%-- End of 15 Minute Time Ago --%%%%%% -->
                                        
                                      <!--- %%%%%%%%%%%%%%%  Ask %%%%%%%%%% -->
                                      <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">( (Binance buy) ASk Should be greater from Top percentile) (One minute Rooling candle)</label>
                                                <select class="form-control" id="barrier_percentile_trigger_ask"name="barrier_percentile_trigger_ask">
                                                <option value="1">Top 1% </option>
                                                <option value="2">Top 2% </option>
                                                <option value="3">Top 3% </option>
                                                <option value="4">Top 4% </option>  
                                                <option value="5">Top 5% </option>
                                                <option value="10">Top 10%</option>
                                                <option value="15">Top 15%</option>
                                                <option value="20">Top 20%</option>
                                                <option value="25">Top 25%</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="barrier_percentile_trigger_ask_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_ask_apply" value="yes" >
                                                    <label class="onoffswitch-label" for="barrier_percentile_trigger_ask_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                                <span class="alert alert-success" id="bin_buy_ask" style="display: inline-block;float: right;margin-top: -40px;">Sellers (5 min) Percentile</span>
                                            </div>
                                          </div>
                                        <!-- %%%%%%%%%%%%% End of Ask %%%%%% -->



                                        <!--- %%%%%%%%%%%%%%%  Bid %%%%%%%%%% -->
                                          
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">((Binance Sell)Bid Should be Less Then Bottom percentile) (One minute Rooling candle)</label>
                                                <select class="form-control" id="barrier_percentile_trigger_bid"name="barrier_percentile_trigger_bid">
                                                <option value="1">Bottom 1% </option>
                                                <option value="2">Bottom 2% </option>
                                                <option value="3">Bottom 3% </option>
                                                <option value="4">Bottom 4% </option>  
                                                <option value="5">Bottom 5% </option>
                                                <option value="10">Bottom 10%</option>
                                                <option value="15">Bottom 15%</option>
                                                <option value="20">Bottom 20%</option>
                                                <option value="25">Bottom 25%</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="barrier_percentile_trigger_bid_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_bid_apply" value="yes" >
                                                    <label class="onoffswitch-label" for="barrier_percentile_trigger_bid_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                                <span class="alert alert-success" id="bin_bid_per" style="display: inline-block;float: right;margin-top: -40px;">Sellers (5 min) Percentile</span>
                                            </div>
                                          </div>
                                        <!-- %%%%%%%%%%%%% End of Bid From   %%%%%% -->

                                        <!--- %%%%%%%%%%%%%%%  Buy From binance %%%%%%%%%% -->
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">((Digie Buy) Buy Should be greater from Top percentile) (One minute Rooling candle)</label>
                                                <select class="form-control" id="barrier_percentile_trigger_buy"name="barrier_percentile_trigger_buy">
                                                <option value="1">Top 1% </option>
                                                <option value="2">Top 2% </option>
                                                <option value="3">Top 3% </option>
                                                <option value="4">Top 4% </option>  
                                                <option value="5">Top 5% </option>
                                                <option value="10">Top 10%</option>
                                                <option value="15">Top 15%</option>
                                                <option value="20">Top 20%</option>
                                                <option value="25">Top 25%</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="barrier_percentile_trigger_buy_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_buy_apply" value="yes" >
                                                    <label class="onoffswitch-label" for="barrier_percentile_trigger_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                                <span class="alert alert-success" id="digie_buy" style="display: inline-block;float: right;margin-top: -40px;">Sellers (5 min) Percentile</span>
                                            </div>
                                          </div>
                                        <!-- %%%%%%%%%%%%% End of Buy From  Binance %%%%%% -->

                                        <!--- %%%%%%%%%%%%%%%  Sell From binance %%%%%%%%%% -->
                                          
                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">((Digie Sell) Sell Should be Less Then Bottom percentile) (One minute Rooling candle) </label>
                                                <select class="form-control" id="barrier_percentile_trigger_sell"name="barrier_percentile_trigger_sell">
                                                <option value="1"> Bottom 1%  </option>
                                                <option value="2"> Bottom 2%  </option>
                                                <option value="3"> Bottom 3%  </option>
                                                <option value="4"> Bottom 4%  </option>  
                                                <option value="5"> Bottom 5%  </option>
                                                <option value="10">Bottom 10% </option>
                                                <option value="15">Bottom 15% </option>
                                                <option value="20">Bottom 20% </option>
                                                <option value="25">Bottom 25% </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="barrier_percentile_trigger_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_sell_apply" value="yes" >
                                                    <label class="onoffswitch-label" for="barrier_percentile_trigger_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                                <span class="alert alert-success" id="digie_sell" style="display: inline-block;float: right;margin-top: -40px;">Sellers (5 min) Percentile</span>
                                            </div>
                                          </div>
                                        <!-- %%%%%%%%%%%%% End of Sell From  Binance %%%%%% -->

                                          

                                        <!--- %%%%%%%%%%%%%%%  Ask Contracts %%%%%%%%%% -->
                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">((Big Buyers) Ask Contracts   Should be Greater from Top percentile)))</label>
                                                <select class="form-control" id="barrier_percentile_trigger_ask_contracts"name="barrier_percentile_trigger_ask_contracts">
                                                <option value="1">Top 1% </option>
                                                <option value="2">Top 2% </option>
                                                <option value="3">Top 3% </option>
                                                <option value="4">Top 4% </option>  
                                                <option value="5">Top 5% </option>
                                                <option value="10">Top 10%</option>
                                                <option value="15">Top 15%</option>
                                                <option value="20">Top 20%</option>
                                                <option value="25">Top 25%</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="barrier_percentile_trigger_ask_contracts_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_ask_contracts_apply" value="yes" >
                                                    <label class="onoffswitch-label" for="barrier_percentile_trigger_ask_contracts_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                                <span class="alert alert-success" id="bg_buy_ask_con" style="display: inline-block;float: right;margin-top: -40px;">Sellers (5 min) Percentile</span>
                                            </div>
                                          </div>
                                        <!-- %%%%%%%%%%%%% End of Ask Contracts %%%%%% -->


                                        <!--- %%%%%%%%%%%%%%%  Bid Contracts %%%%%%%%%% -->
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">((Big Sellers) Bid Contracts   Should be Les then Bottom percentile)))</label>
                                                <select class="form-control" id="barrier_percentile_trigger_bid_contracts"name="barrier_percentile_trigger_bid_contracts">
                                                <option value="1">Bottom 1% </option>
                                                <option value="2">Bottom 2% </option>
                                                <option value="3">Bottom 3% </option>
                                                <option value="4">Bottom 4% </option>  
                                                <option value="5">Bottom 5% </option>
                                                <option value="10">Bottom 10%</option>
                                                <option value="15">Bottom 15%</option>
                                                <option value="20">Bottom 20%</option>
                                                <option value="25">Bottom 25%</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="barrier_percentile_trigger_bid_contracts_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_bid_contracts_apply" value="yes" >
                                                    <label class="onoffswitch-label" for="barrier_percentile_trigger_bid_contracts_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                                <span class="alert alert-success" id="bg_sell_cont" style="display: inline-block;float: right;margin-top: -40px;">Sellers (5 min) Percentile</span>
                                            </div>
                                          </div>
                                        <!-- %%%%%%%%%%%%% End of Ask Contracts %%%%%% -->

                                        <!-- Market trends rule in percentile start -->
                                         
                                        

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Caption Option
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_caption_option_buy" id="percentile_trigger_caption_option_buy" value="">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_caption_option_buy_apply" class="onoffswitch-checkbox" id="percentile_trigger_caption_option_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_caption_option_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_caption_option" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div> <!-- End of caption  -->
                                          

                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Caption score
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_caption_score_buy" id="percentile_trigger_caption_score_buy" value="">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_caption_score_buy_apply" class="onoffswitch-checkbox" id="percentile_trigger_caption_score_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_caption_score_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_caption_score" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of caption score -->


                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Buy
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_buy_trend_buy" id="percentile_trigger_buy_trend_buy" value="">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_buy_trend_buy_apply" class="onoffswitch-checkbox" id="percentile_trigger_buy_trend_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_buy_trend_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_buy" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of buy  -->



                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Sell
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_sell_buy" id="percentile_trigger_sell_buy" value="">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_sell_buy_apply" class="onoffswitch-checkbox" id="percentile_trigger_sell_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_sell_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_sell" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of sell  -->


                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Demand
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_demand_buy" id="percentile_trigger_demand_buy" value="">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_demand_buy_apply" class="onoffswitch-checkbox" id="percentile_trigger_demand_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_demand_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_demand" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of demamd  -->


                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Supply
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_supply_buy" id="percentile_trigger_supply_buy" value="">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_supply_buy_apply" class="onoffswitch-checkbox" id="percentile_trigger_supply_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_supply_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_supply" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of supply  -->



                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Market Trend
                                                </label>
                                                <select class="form-control" name="percentile_trigger_market_trend_buy" id="percentile_trigger_market_trend_buy">
                                                    <option value="POSITIVE">POSITIVE</option>
                                                    <option value="NEGATIVE">NEGATIVE</option>
                                                </select>
                                               
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_market_trend_buy_apply" class="onoffswitch-checkbox" id="percentile_trigger_market_trend_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_market_trend_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <span class="alert alert-success mt_market_trend" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of trend_operator  -->


                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Meta Tranding
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_meta_trading_buy" id="percentile_trigger_meta_trading_buy" value="">
                                            </div>


                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_meta_trading_buy_apply" class="onoffswitch-checkbox" id="percentile_trigger_meta_trading_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_meta_trading_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_meta_trading" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of meta_trading  -->

                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Resk per Share
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_riskpershare_buy" id="percentile_trigger_riskpershare_buy" value="">
                                            </div>

                                           

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_riskpershare_buy_apply" class="onoffswitch-checkbox" id="percentile_trigger_riskpershare_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_riskpershare_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_riskpershare" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of meta_trading  -->


                                          <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">
                                                      RL
                                                  </label>
                                                  <input type="text" step="any" class="form-control" name="percentile_trigger_RL_buy" id="percentile_trigger_RL_buy" value="">
                                              </div>

                                             

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="percentile_trigger_RL_buy_apply" class="onoffswitch-checkbox" id="percentile_trigger_RL_buy_apply" value="yes">
                                                      <label class="onoffswitch-label" for="percentile_trigger_RL_buy_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                              </div>
                                              <span class="alert alert-success mt_rl" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End RL  -->




                                          <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">
                                                      Long term intension
                                                  </label>
                                                  <input type="text" step="any" class="form-control" name="percentile_trigger_long_term_intension_buy" id="percentile_trigger_long_term_intension_buy" value="">
                                              </div>

                                             

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="percentile_trigger_long_term_intension_buy_apply" class="onoffswitch-checkbox" id="percentile_trigger_long_term_intension_buy_apply" value="yes">
                                                      <label class="onoffswitch-label" for="percentile_trigger_long_term_intension_buy_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                              </div>
                                              <span class="alert alert-success long_term_intension" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End long term intension  -->



                                        <!-- Market trends rule in percentile End -->


                                            <!--  ************* Buy Part *********** -->
                                      </div>
                                      <!-- barrier percentile   Buy part End -->


                                      <!-- barrier End   Sell part Start -->
                                      <div id="barrier_percentile_sell" class="tab-pane fade">
                                            <!-- %%%%%%%%%%%%%%% -->
                                            <!-- %%%%%%%%%%% Enabe Buy Rules  %%%%%%%%%%%%%%%%%%  -->
                                            <div class="row" style="margin-top: 30px;">
                                                <div class="col-md-6">
                                                    <h2 class="buy_sell_box_heading">
                                                    <dd class="pull-left">
                                                    Sell Percentile setting
                                                    </dd>
                                                    <label class="pull-right" style="margin-top: 11px; margin-bottom: 0;">
                                                    <strong>
                                                    Enable Disable Sell percentile Trigger
                                                    </strong>
                                                    </label>
                                                    </h2>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="onoffswitch">
                                                            <input type="checkbox" name="enable_sell_barrier_percentile" class="onoffswitch-checkbox" id="enable_sell_barrier_percentile" value="yes">
                                                            <label class="onoffswitch-label" for="enable_sell_barrier_percentile">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                            </label>
                                                    </div>
                                                </div>
                                              </div>
                                            <!--  %%%%%%%%%% End of Buy Rules %%%%%%%%%%%%%%%%%%  -->
                                            <!-- %%%%%%%%%% Profit percentage  -->

                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">By Default Profit  in Percentage(Equal To Recommended)</label>
                                                  <input type="text" step="any" class="form-control" name="barrier_percentile_trigger_default_profit_percenage" id="barrier_percentile_trigger_default_profit_percenage" value="">
                                                </div>
                                            </div>

                                            <!-- Trailing Difference from current market in stop loss updation -->

                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">Trailing Difference between stop loss and current Market in Percentage(Eqaual To Recommended)</label>
                                                  <input type="text" step="any" class="form-control" name="barrier_percentile_trigger_trailing_difference_between_stoploss_and_current_market_percentage" id="barrier_percentile_trigger_trailing_difference_between_stoploss_and_current_market_percentage" value="">
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">
                                                   if  current market value between in the range of percentage from high swing point (Then sell trade)
                                                   Note(if valule is 0 or empty it mean rule is off) 
                                                  </label>
                                                  <input type="text" step="any" class="form-control" name="barrier_percentile_trigger_barrier_range_percentage_sell" id="barrier_percentile_trigger_barrier_range_percentage_sell" value="">
                                                </div>
                                            </div>


                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">Black Wall (Less Then) From defined percentile</label>

                                                  <select class="form-control" id="barrier_percentile_trigger_sell_black_wall" name="barrier_percentile_trigger_sell_black_wall">
                                                  <option value="1">Bottom 1% </option>
                                                  <option value="2">Bottom 2% </option>
                                                  <option value="3">Bottom 3% </option>
                                                  <option value="4">Bottom 4% </option>
                                                  <option value="5">Bottom 5% </option>
                                                  <option value="10">Bottom 10%</option>
                                                  <option value="15">Bottom 15%</option>
                                                  <option value="20">Bottom 20%</option>
                                                  <option value="25">Bottom 25%</option>
                                                  </select>


                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_sell_black_wall_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_sell_black_wall_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_sell_black_wall_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="sbw_p" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>


                                            <!-- %%%%% --- Support Barrier  -- %%%%% -->

                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(Virtual Barrier less then Bottom percentile)(Support  Barrier)</label>

                                                  <select class="form-control" id="barrier_percentile_trigger_buy_virtual_barrier_for_sell" name="barrier_percentile_trigger_buy_virtual_barrier_for_sell">
                                                  
                                                  <option value="1">Bottom 1% </option>
                                                  <option value="2">Bottom 2% </option>
                                                  <option value="3">Bottom 3% </option>
                                                  <option value="4">Bottom 4% </option>
                                                  <option value="5">Bottom 5% </option>
                                                  <option value="10">Bottom 10%</option>
                                                  <option value="15">Bottom 15%</option>
                                                  <option value="20">Bottom 20%</option>
                                                  <option value="25">Bottom 25%</option>
                                                  </select>


                                              </div>
                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_buy_virtual_barrier_for_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_buy_virtual_barrier_for_sell_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_buy_virtual_barrier_for_sell_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="vb_p" style="display: inline-block;float: right;margin-top: -40px;">Virtual Barrier Percentile</span>
                                              </div>
                                            </div>

                                            <!-- %%%%% --- Support Barrier  -- %%%%% -->

                                            <!--  virtual barrir -->
                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">Virtual Barrier (Greater Then) From defined percentile (Resistance Barrier)</label>

                                                  <select class="form-control" id="barrier_percentile_trigger_sell_virtual_barrier" name="barrier_percentile_trigger_sell_virtual_barrier">
                                                  <option value="1"> Top 1% </option>
                                                  <option value="2"> Top 2% </option>
                                                  <option value="3"> Top 3% </option>
                                                  <option value="4"> Top 4% </option>
                                                  <option value="5"> Top 5% </option>
                                                  <option value="10"> Top 10%</option>
                                                  <option value="15"> Top 15%</option>
                                                  <option value="20"> Top 20%</option>
                                                  <option value="25"> Top 25%</option>
                                                  </select>


                                              </div>
                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_sell_virtual_barrier_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_sell_virtual_barrier_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_sell_virtual_barrier_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="svb" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>


                                            <!--%%%%%%%%%%%%%%% & LEVEL PRESSUE -->
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="control-label" for="hour">(Seven Level (Less Then) From defined percentile)</label>

                                                    <select class="form-control" id="barrier_percentile_trigger_sell_seven_level_pressure" name="barrier_percentile_trigger_sell_seven_level_pressure">
                                                    <option value="1">Bottom 1% </option>
                                                    <option value="2">Bottom 2% </option>
                                                    <option value="3">Bottom 3% </option>
                                                    <option value="4">Bottom 4% </option>
                                                    <option value="5">Bottom 5% </option>
                                                    <option value="10">Bottom 10%</option>
                                                    <option value="15">Bottom 15%</option>
                                                    <option value="20">Bottom 20%</option>
                                                    <option value="25">Bottom 25%</option>
                                                    </select>


                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>&nbsp;</label>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="barrier_percentile_trigger_sell_seven_level_pressure_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_sell_seven_level_pressure_apply" value="yes" >
                                                        <label class="onoffswitch-label" for="barrier_percentile_trigger_sell_seven_level_pressure_apply">
                                                        <span class="onoffswitch-inner"></span>
                                                        <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                    <span class="alert alert-success" id="ssp" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                                </div>

                                            </div>



                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">Last 200 Contract Buyers Vs Sellers (Less then Recommended)</label>
                                                  <input type="text" step="any" class="form-control" name="barrier_percentile_trigger_sell_last_200_contracts_buy_vs_sell" id="barrier_percentile_trigger_sell_last_200_contracts_buy_vs_sell" value="">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>&nbsp;</label>
                                                      <div class="onoffswitch">
                                                        <input type="checkbox" name="barrier_percentile_trigger_sell_last_200_contracts_buy_vs_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_sell_last_200_contracts_buy_vs_sell_apply" value="yes" >
                                                        <label class="onoffswitch-label" for="barrier_percentile_trigger_sell_last_200_contracts_buy_vs_sell_apply">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                      </div>
                                                </div>
                                            </div>




                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">Last 200 Contract Time (Less Then Recommended)</label>
                                                  <input type="text" step="any" class="form-control" name="barrier_percentile_trigger_sell_last_200_contracts_time" id="barrier_percentile_trigger_sell_last_200_contracts_time" value="">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>&nbsp;</label>
                                                      <div class="onoffswitch">
                                                        <input type="checkbox" name="barrier_percentile_trigger_sell_last_200_contracts_time_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_sell_last_200_contracts_time_apply" value="yes" >
                                                        <label class="onoffswitch-label" for="barrier_percentile_trigger_sell_last_200_contracts_time_apply">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                      </div>
                                                </div>
                                            </div>



                                          <div class="row">
                                              <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">Last Qty Contract Buyers Vs Sellers(Less Then Recommended) T1 LTC Buyers Vs Sellers)</label>
                                                <input type="text" step="any" class="form-control" name="barrier_percentile_trigger_sell_last_qty_contracts_buyer_vs_seller" id="barrier_percentile_trigger_sell_last_qty_contracts_buyer_vs_seller" value="">
                                              </div>
                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                    <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_sell_last_qty_contracts_buyer_vs_seller_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_sell_last_qty_contracts_buyer_vs_seller_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_sell_last_qty_contracts_buyer_vs_seller_apply">
                                                          <span class="onoffswitch-inner"></span>
                                                          <span class="onoffswitch-switch"></span>
                                                      </label>
                                                    </div>
                                              </div>
                                          </div>

                                          <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                          <div class="row">
                                              <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">Last qty Contract time(Less then Recommended) (T3 Ltc Time)</label>
                                                <input type="text" step="any" class="form-control " name="barrier_percentile_trigger_sell_last_qty_contracts_time" id="barrier_percentile_trigger_sell_last_qty_contracts_time" value="">
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                    <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_sell_last_qty_contracts_time_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_sell_last_qty_contracts_time_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_sell_last_qty_contracts_time_apply">
                                                          <span class="onoffswitch-inner"></span>
                                                          <span class="onoffswitch-switch"></span>
                                                      </label>
                                                    </div>
                                              </div>
                                          </div>
                                        <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

                                          <!-- %%%%%%%%%%%%% 5 Minute Rolling Candel %%%%%%%%%%%%% -->
                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(5 Minute Rolling Candel Less Then defined percentile)(T1Cot)</label>
                                                  <select class="form-control" id="barrier_percentile_trigger_5_minute_rolling_candel_sell" name="barrier_percentile_trigger_5_minute_rolling_candel_sell">
                                                  <option value="1">Bottom 1% </option>
                                                  <option value="2">Bottom 2% </option>
                                                  <option value="3">Bottom 3% </option>
                                                  <option value="4">Bottom 4% </option>  
                                                  <option value="5">Bottom 5% </option>
                                                  <option value="10">Bottom 10%</option>
                                                  <option value="15">Bottom 15%</option>
                                                  <option value="20">Bottom 20%</option>
                                                  <option value="25">Bottom 25%</option>
                                                  </select>
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_5_minute_rolling_candel_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_5_minute_rolling_candel_sell_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_5_minute_rolling_candel_sell_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="sfvm" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>
                                        <!-- %%%%%%%%%%%%% End if 5 Minute Rolling Candel %%%%%% -->


                                        <!-- %%%%%%%%%%%%% 15 Minute Rolling Candel %%%%%%%%%%%%% -->
                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">15 Minute Rolling Candel (Less Then)  From defined percentile</label>
                                                  <select class="form-control" id="barrier_percentile_trigger_15_minute_rolling_candel_sell" name="barrier_percentile_trigger_15_minute_rolling_candel_sell">
                                                  <option value="1">Bottom 1% </option>
                                                  <option value="2">Bottom 2% </option>
                                                  <option value="3">Bottom 3% </option>
                                                  <option value="4">Bottom 4% </option>  
                                                  <option value="5">Bottom 5% </option>
                                                  <option value="10">Bottom 10%</option>
                                                  <option value="15">Bottom 15%</option>
                                                  <option value="20">Bottom 20%</option>
                                                  <option value="25">Bottom 25%</option>
                                                  </select>
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_15_minute_rolling_candel_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_15_minute_rolling_candel_sell_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_15_minute_rolling_candel_sell_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="sffm" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>
                                        <!-- %%%%%%%%%%%%% End if 5 Minute Rolling Candel %%%%%% -->

                                          <!-- %%%%%%%%%%%%% Buyers %%%%%%%%%%%%% -->
                                          <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(15 Minute)(Buyers Should Less  Then Bottom percentile)(T4 Cot Buyers)</label>
                                                  <select class="form-control" id="barrier_percentile_trigger_buyers_sell" name="barrier_percentile_trigger_buyers_sell">
                                                  <option value="1">  Bottom 1% </option>
                                                  <option value="2">  Bottom 2% </option>
                                                  <option value="3">  Bottom 3% </option>
                                                  <option value="4">  Bottom 4% </option>  
                                                  <option value="5">  Bottom 5% </option>
                                                  <option value="10"> Bottom 10%</option>
                                                  <option value="15"> Bottom 15%</option>
                                                  <option value="20"> Bottom 20%</option>
                                                  <option value="25"> Bottom 25%</option>
                                                  </select>
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_buyers_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_buyers_sell_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_buyers_sell_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="slb" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>
                                        <!-- %%%%%%%%%%%%% End of Buyers %%%%%% -->


                                        <!-- %%%%%%%%%%%%% SELLERS %%%%%%%%%%%%% -->
                                             <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(15 Minute)(Sellers Should be Greater Then Top percentile)(T4 Cot Sellers)</label>
                                                  <select class="form-control" id="barrier_percentile_trigger_sellers_sell" name="barrier_percentile_trigger_sellers_sell">
                                                  <option value="1">Top 1% </option>
                                                  <option value="2">Top 2% </option>
                                                  <option value="3">Top 3% </option>
                                                  <option value="4">Top 4% </option>  
                                                  <option value="5">Top 5% </option>
                                                  <option value="10">Top 10%</option>
                                                  <option value="15">Top 15%</option>
                                                  <option value="20">Top 20%</option>
                                                  <option value="25">Top 25%</option>
                                                  </select>
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_sellers_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_sellers_sell_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_sellers_sell_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="sls" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>
                                        <!-- %%%%%%%%%%%%% End of SELLERS %%%%%% -->





                                      <!-- %%%%%%%%%%%%-- 15 Minute Time ago-- %% -->
    
                                        <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label" for="hour">(15 Minute last time ago Should be Less Then Bottom percentile)(T3 LTC Time)</label>
                                            <select class="form-control" id="barrier_percentile_trigger_15_minute_last_time_ago_sell"name="barrier_percentile_trigger_15_minute_last_time_ago_sell">
                                            <option value="1">Bottom 1% </option>
                                            <option value="2">Bottom 2% </option>
                                            <option value="3">Bottom 3% </option>
                                            <option value="4">Bottom 4% </option>  
                                            <option value="5">Bottom 5% </option>
                                            <option value="10">Bottom 10%</option>
                                            <option value="15">Bottom 15%</option>
                                            <option value="20">Bottom 20%</option>
                                            <option value="25">Bottom 25%</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>&nbsp;</label>
                                            <div class="onoffswitch">
                                                <input type="checkbox" name="barrier_percentile_trigger_15_minute_last_time_ago_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_15_minute_last_time_ago_sell_apply" value="yes" >
                                                <label class="onoffswitch-label" for="barrier_percentile_trigger_15_minute_last_time_ago_sell_apply">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                                </label>
                                            </div>
                                            <span class="alert alert-success" id="s_p" style="display: inline-block;float: right;margin-top: -40px;">Sellers (5 min) Percentile</span>
                                        </div>
                                        </div>

                                    <!-- %%%%%%%%%%%%-- End of 15 Minute Time Ago --%%%%%% -->

                                    <!--- %%%%%%%%%%%%%%%  Ask %%%%%%%%%% -->
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">( (Binance buy) ASk Should be Less Then Top percentile) (One minute Rooling candle)</label>
                                                <select class="form-control" id="barrier_percentile_trigger_ask_sell"name="barrier_percentile_trigger_ask_sell">
                                                <option value="1">Bottom 1% </option>
                                                <option value="2">Bottom 2% </option>
                                                <option value="3">Bottom 3% </option>
                                                <option value="4">Bottom 4% </option>  
                                                <option value="5">Bottom 5% </option>
                                                <option value="10">Bottom 10%</option>
                                                <option value="15">Bottom 15%</option>
                                                <option value="20">Bottom 20%</option>
                                                <option value="25">Bottom 25%</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="barrier_percentile_trigger_ask_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_ask_sell_apply" value="yes" >
                                                    <label class="onoffswitch-label" for="barrier_percentile_trigger_ask_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                                <span class="alert alert-success" id="s_p" style="display: inline-block;float: right;margin-top: -40px;">Sellers (5 min) Percentile</span>
                                            </div>
                                        </div>
                                    <!-- %%%%%%%%%%%%% End of Ask %%%%%% -->

                                      <!--- %%%%%%%%%%%%%%%  Bid %%%%%%%%%% -->
                                        
                                      <div class="row">
                                          <div class="form-group col-md-6">
                                              <label class="control-label" for="hour">((Binance Sell)Bid Should be Greater Then Top percentile) (One minute Rooling candle)</label>
                                              <select class="form-control" id="barrier_percentile_trigger_bid_sell"name="barrier_percentile_trigger_bid_sell">
                                              <option value="1">Top 1% </option>
                                              <option value="2">Top 2% </option>
                                              <option value="3">Top 3% </option>
                                              <option value="4">Top 4% </option>  
                                              <option value="5">Top 5% </option>
                                              <option value="10">Top 10%</option>
                                              <option value="15">Top 15%</option>
                                              <option value="20">Top 20%</option>
                                              <option value="25">Top 25%</option>
                                              </select>
                                          </div>

                                          <div class="form-group col-md-6">
                                              <label>&nbsp;</label>
                                              <div class="onoffswitch">
                                                  <input type="checkbox" name="barrier_percentile_trigger_bid_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_bid_sell_apply" value="yes" >
                                                  <label class="onoffswitch-label" for="barrier_percentile_trigger_bid_sell_apply">
                                                  <span class="onoffswitch-inner"></span>
                                                  <span class="onoffswitch-switch"></span>
                                                  </label>
                                              </div>
                                              <span class="alert alert-success" id="s_p" style="display: inline-block;float: right;margin-top: -40px;">Sellers (5 min) Percentile</span>
                                          </div>
                                        </div>
                                      <!-- %%%%%%%%%%%%% End of Bid From   %%%%%% -->

                                    <!--- %%%%%%%%%%%%%%%  Buy From binance %%%%%%%%%% -->
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label" for="hour">((Digie Buy) Buy Should be Less from Bottom percentile) (One minute Rooling candle)</label>
                                            <select class="form-control" id="barrier_percentile_trigger_buy_sell"name="barrier_percentile_trigger_buy_sell">
                                            <option value="1">Bottom 1% </option>
                                            <option value="2">Bottom 2% </option>
                                            <option value="3">Bottom 3% </option>
                                            <option value="4">Bottom 4% </option>  
                                            <option value="5">Bottom 5% </option>
                                            <option value="10">Bottom 10%</option>
                                            <option value="15">Bottom 15%</option>
                                            <option value="20">Bottom 20%</option>
                                            <option value="25">Bottom 25%</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>&nbsp;</label>
                                            <div class="onoffswitch">
                                                <input type="checkbox" name="barrier_percentile_trigger_buy_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_buy_sell_apply" value="yes" >
                                                <label class="onoffswitch-label" for="barrier_percentile_trigger_buy_sell_apply">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                                </label>
                                            </div>
                                            <span class="alert alert-success" id="s_p" style="display: inline-block;float: right;margin-top: -40px;">Sellers (5 min) Percentile</span>
                                        </div>
                                        </div>
                                    <!-- %%%%%%%%%%%%% End of Buy From  Binance %%%%%% -->

                                    <!--- %%%%%%%%%%%%%%%  Sell From binance %%%%%%%%%% -->
                                        
                                        <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label" for="hour">Digie Sell) Sell Should be Greater Then top percentile) (One minute Rooling candle)</label>
                                            <select class="form-control" id="barrier_percentile_trigger_sell_rule_sell"name="barrier_percentile_trigger_sell_rule_sell">
                                            <option value="1">Top 1% </option>
                                            <option value="2">Top 2% </option>
                                            <option value="3">Top 3% </option>
                                            <option value="4">Top 4% </option>  
                                            <option value="5">Top 5% </option>
                                            <option value="10">Top 10%</option>
                                            <option value="15">Top 15%</option>
                                            <option value="20">Top 20%</option>
                                            <option value="25">Top 25%</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>&nbsp;</label>
                                            <div class="onoffswitch">
                                                <input type="checkbox" name="barrier_percentile_trigger_sell_rule_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_sell_rule_sell_apply" value="yes" >
                                                <label class="onoffswitch-label" for="barrier_percentile_trigger_sell_rule_sell_apply">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                                </label>
                                            </div>
                                            <span class="alert alert-success" id="s_p" style="display: inline-block;float: right;margin-top: -40px;">Sellers (5 min) Percentile</span>
                                        </div>
                                        </div>
                                    <!-- %%%%%%%%%%%%% End of Sell From  Binance %%%%%% -->

                                      

                                    
                                    <!--- %%%%%%%%%%%%%%%  Ask Contracts %%%%%%%%%% -->
                                        <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label" for="hour">((Big Buyers) Ask Contracts Should be Less then Bottom percentile)))</label>
                                            <select class="form-control" id="barrier_percentile_trigger_ask_contracts_sell"name="barrier_percentile_trigger_ask_contracts_sell">
                                            <option value="1"> Bottom 1% </option>
                                            <option value="2"> Bottom 2% </option>
                                            <option value="3"> Bottom 3% </option>
                                            <option value="4"> Bottom 4% </option>  
                                            <option value="5"> Bottom 5% </option>
                                            <option value="10"> Bottom 10%</option>
                                            <option value="15"> Bottom 15%</option>
                                            <option value="20"> Bottom 20%</option>
                                            <option value="25"> Bottom 25%</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>&nbsp;</label>
                                            <div class="onoffswitch">
                                                <input type="checkbox" name="barrier_percentile_trigger_ask_contracts_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_ask_contracts_sell_apply" value="yes" >
                                                <label class="onoffswitch-label" for="barrier_percentile_trigger_ask_contracts_sell_apply">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                                </label>
                                            </div>
                                            <span class="alert alert-success" id="s_p" style="display: inline-block;float: right;margin-top: -40px;">Sellers (5 min) Percentile</span>
                                        </div>
                                        </div>
                                    <!-- %%%%%%%%%%%%% End of Ask Contracts %%%%%% -->


                                    <!--- %%%%%%%%%%%%%%%  Bid Contracts %%%%%%%%%% -->
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label" for="hour">((Big Sellers) Bid Contracts Should be Greater then Top percentile)))</label>
                                            <select class="form-control" id="barrier_percentile_trigger_bid_contracts_sell"name="barrier_percentile_trigger_bid_contracts_sell">
                                            <option value="1">  Top 1% </option>
                                            <option value="2">  Top 2% </option>
                                            <option value="3">  Top 3% </option>
                                            <option value="4">  Top 4% </option>  
                                            <option value="5">  Top 5% </option>
                                            <option value="10"> Top 10%</option>
                                            <option value="15"> Top 15%</option>
                                            <option value="20"> Top 20%</option>
                                            <option value="25"> Top 25%</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>&nbsp;</label>
                                            <div class="onoffswitch">
                                                <input type="checkbox" name="barrier_percentile_trigger_bid_contracts_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_bid_contracts_sell_apply" value="yes" >
                                                <label class="onoffswitch-label" for="barrier_percentile_trigger_bid_contracts_sell_apply">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                                </label>
                                            </div>
                                            <span class="alert alert-success" id="bin_bid_per" style="display: inline-block;float: right;margin-top: -40px;">Sellers (5 min) Percentile</span>
                                        </div>
                                        </div>
                                    <!-- %%%%%%%%%%%%% End of Ask Contracts %%%%%% -->

                                      <!-- %%%%%%%%%%%%%% -- market trend -- %%%%%%%%%%%%%%%%%%%% -->
                                      
                                      <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Caption Option
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_caption_option_sell" id="percentile_trigger_caption_option_sell" value="">
                                            </div>


                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_caption_option_sell_apply" class="onoffswitch-checkbox" id="percentile_trigger_caption_option_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_caption_option_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_caption_option" id="" style="display: inline-block;float: right;margin-top: -40px;">3</span>
                                          </div> <!-- End of caption  -->
                                          

                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Caption score
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_caption_score_sell" id="percentile_trigger_caption_score_sell" value="">
                                            </div>

                                           

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_caption_score_sell_apply" class="onoffswitch-checkbox" id="percentile_trigger_caption_score_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_caption_score_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_caption_score" id="" style="display: inline-block;float: right;margin-top: -40px;">3</span>
                                          </div><!-- End of caption score -->


                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Buy
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_buy_sell" id="percentile_trigger_buy_sell" value="">
                                            </div>

                                            

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_buy_operator_sell_apply" class="onoffswitch-checkbox" id="percentile_trigger_buy_operator_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_buy_operator_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_buy" id="" style="display: inline-block;float: right;margin-top: -40px;">21</span>
                                          </div><!-- End of buy  -->



                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Sell
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_sell_trend_sell" id="percentile_trigger_sell_trend_sell" value="">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_sell_trend_sell_apply" class="onoffswitch-checkbox" id="percentile_trigger_sell_trend_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_sell_trend_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_sell" id="" style="display: inline-block;float: right;margin-top: -40px;">5</span>
                                          </div><!-- End of sell  -->


                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Demand
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_demand_sell" id="percentile_trigger_demand_sell" value="">
                                            </div>


                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_demand_sell_apply" class="onoffswitch-checkbox" id="percentile_trigger_demand_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_demand_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_demand" id="" style="display: inline-block;float: right;margin-top: -40px;">3</span>
                                          </div><!-- End of demamd  -->


                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Supply
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_supply_sell" id="percentile_trigger_supply_sell" value="">
                                            </div>

                                         

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_supply_sell_apply" class="onoffswitch-checkbox" id="percentile_trigger_supply_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_supply_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_supply" id="" style="display: inline-block;float: right;margin-top: -40px;">3</span>
                                          </div><!-- End of supply  -->



                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Market Trend
                                                </label>

                                                <select class="form-control" name="percentile_trigger_market_trend_sell" id="percentile_trigger_market_trend_sell">
                                                    <option value="POSITIVE">POSITIVE</option>
                                                    <option value="NEGATIVE">NEGATIVE</option>
                                                </select>

                                            </div>

                                         

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_market_trend_operator_sell_apply" class="onoffswitch-checkbox" id="percentile_trigger_market_trend_operator_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_market_trend_operator_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_market_trend" id="" style="display: inline-block;float: right;margin-top: -40px;">POSITIVE</span>
                                          </div><!-- End of trend_operator  -->


                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Meta Tranding
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_meta_trading_sell" id="percentile_trigger_meta_trading_sell" value="">
                                            </div>

                                            

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_meta_trading_sell_apply" class="onoffswitch-checkbox" id="percentile_trigger_meta_trading_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_meta_trading_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_meta_trading" id="" style="display: inline-block;float: right;margin-top: -40px;">3</span>
                                          </div><!-- End of meta_trading  -->

                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  Resk per Share
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_riskpershare_sell" id="percentile_trigger_riskpershare_sell" value="">
                                            </div>


                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_riskpershare_sell_apply" class="onoffswitch-checkbox" id="percentile_trigger_riskpershare_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_riskpershare_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_riskpershare" id="" style="display: inline-block;float: right;margin-top: -40px;">3</span>
                                          </div><!-- End of meta_trading  -->


                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="hour">
                                                  RL
                                                </label>
                                                <input type="text" step="any" class="form-control" name="percentile_trigger_RL_sell" id="percentile_trigger_RL_sell" value="">
                                            </div>

                                            

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="percentile_trigger_RL_sell_apply" class="onoffswitch-checkbox" id="percentile_trigger_RL_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="percentile_trigger_RL_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_rl" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End RL  -->

                                      <!-- %%%%%%%%%%%%%% --End of  market trend -- %%%%%%%%%%%%%%%%%%%% -->
                                        

                                        <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">
                                                      Long term intension
                                                  </label>
                                                  <input type="text" step="any" class="form-control" name="percentile_trigger_long_term_intension_sell" id="percentile_trigger_long_term_intension_sell" value="">
                                              </div>

                                             

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="percentile_trigger_long_term_intension_sell_apply" class="onoffswitch-checkbox" id="percentile_trigger_long_term_intension_sell_apply" value="yes">
                                                      <label class="onoffswitch-label" for="percentile_trigger_long_term_intension_sell_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                              </div>
                                              <span class="alert alert-success long_term_intension" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                            </div><!-- End long term intension  -->



                                        <!--- %%%%%%%%%%%  End of Sell Part %%%%%%%%%%%%%%%% -->

                                            <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                      </div>
                                      <!-- barrier End   Sell part Start -->
                                       

                                        <!-- Part of stop Loss -->
                                        <div id="barrier_percentile_stop_loss" class="tab-pane fade">
                                            <!-- %%%%%%%%%%%%%%% -->
                                            <!-- %%%%%%%%%%% Enabe Buy Rules  %%%%%%%%%%%%%%%%%%  -->
                                            <div class="row" style="margin-top: 30px;">
                                                <div class="col-md-6">
                                                  <h2 class="buy_sell_box_heading">
                                                  <dd class="pull-left">
                                                  Sell Percentile setting
                                                  </dd>
                                                  <label class="pull-right" style="margin-top: 11px; margin-bottom: 0;">
                                                  <strong>
                                                  Enable Disable Sell percentile Trigger
                                                  </strong>
                                                  </label>
                                                  </h2>
                                                </div>

                                                <div class="col-md-6">
                                                  <div class="onoffswitch">
                                                  <input type="checkbox" name="enable_percentile_trigger_stop_loss" class="onoffswitch-checkbox" id="enable_percentile_trigger_stop_loss" value="yes">
                                                  <label class="onoffswitch-label" for="enable_percentile_trigger_stop_loss">
                                                  <span class="onoffswitch-inner"></span>
                                                  <span class="onoffswitch-switch"></span>
                                                  </label>
                                                  </div>
                                                </div>
                                            </div>
                                            <!--  %%%%%%%%%% End of Buy Rules %%%%%%%%%%%%%%%%%%  -->
                                            

                                         


                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">Black Wall (Less Then) From defined percentile</label>

                                                  <select class="form-control" id="barrier_percentile_stop_loss_black_wall" name="barrier_percentile_stop_loss_black_wall">
                                                  <option value="1">Bottom 1% </option>
                                                  <option value="2">Bottom 2% </option>
                                                  <option value="3">Bottom 3% </option>
                                                  <option value="4">Bottom 4% </option>
                                                  <option value="5">Bottom 5% </option>
                                                  <option value="10">Bottom 10%</option>
                                                  <option value="15">Bottom 15%</option>
                                                  <option value="20">Bottom 20%</option>
                                                  <option value="25">Bottom 25%</option>
                                                  </select>


                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_stop_loss_black_wall_apply" class="onoffswitch-checkbox" id="barrier_percentile_stop_loss_black_wall_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_stop_loss_black_wall_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="sbw_p" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>

                                            <!--  virtual barrir -->
                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">Virtual Barrier (Greater Then) From defined percentile (Ask volume)</label>

                                                  <select class="form-control" id="barrier_percentile_trigger_stop_loss_virtual_barrier" name="barrier_percentile_trigger_stop_loss_virtual_barrier">
                                                  <option value="1">Bottom 1% </option>
                                                  <option value="2">Bottom 2% </option>
                                                  <option value="3">Bottom 3% </option>
                                                  <option value="4">Bottom 4% </option>
                                                  <option value="5">top 5% </option>
                                                  <option value="10">top 10%</option>
                                                  <option value="15">top 15%</option>
                                                  <option value="20">top 20%</option>
                                                  <option value="25">top 25%</option>
                                                  </select>


                                              </div>
                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_stop_loss_virtual_barrier_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_stop_loss_virtual_barrier_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_stop_loss_virtual_barrier_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="svb" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>


                                            <!-- %%%%%%%%%%%%%%%%% -- -- %%%%%%%%%%%%%%%%% -->
                                            <!--  virtual barrir -->
                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(Virtual Barrier Less From defined percentile(Bid Volume))</label>

                                                  <select class="form-control" id="barrier_percentile_trigger_stop_loss_virtual_barrier_bid" name="barrier_percentile_trigger_stop_loss_virtual_barrier_bid">
                                                  <option value="1">Bottom 1% </option>
                                                  <option value="2">Bottom 2% </option>
                                                  <option value="3">Bottom 3% </option>
                                                  <option value="4">Bottom 4% </option>
                                                  <option value="5">Bottom 5% </option>
                                                  <option value="10">Bottom 10%</option>
                                                  <option value="15">Bottom 15%</option>
                                                  <option value="20">Bottom 20%</option>
                                                  <option value="25">Bottom 25%</option>

                                                  
                                                  </select>


                                              </div>
                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_stop_loss_virtual_barrier_bid_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_stop_loss_virtual_barrier_bid_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_stop_loss_virtual_barrier_bid_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="vb_p" style="display: inline-block;float: right;margin-top: -40px;">Virtual Barrier Percentile</span>
                                              </div>
                                            </div>


                                            <!-- %%%%%%%%%%%%%%%%% -- -- %%%%%%%%%%%%%%%%% -->

                                            <!--%%%%%%%%%%%%%%% & LEVEL PRESSUE -->
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="control-label" for="hour">(Seven Level (Less Then) From defined percentile)</label>

                                                    <select class="form-control" id="barrier_percentile_trigger_stop_loss_seven_level_pressure" name="barrier_percentile_trigger_stop_loss_seven_level_pressure">
                                                    <option value="1">Bottom 1% </option>
                                                    <option value="2">Bottom 2% </option>
                                                    <option value="3">Bottom 3% </option>
                                                    <option value="4">Bottom 4% </option>
                                                    <option value="5">Bottom 5% </option>
                                                    <option value="10">Bottom 10%</option>
                                                    <option value="15">Bottom 15%</option>
                                                    <option value="20">Bottom 20%</option>
                                                    <option value="25">Bottom 25%</option>
                                                    </select>


                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>&nbsp;</label>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="barrier_percentile_trigger_stop_loss_seven_level_pressure_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_stop_loss_seven_level_pressure_apply" value="yes" >
                                                        <label class="onoffswitch-label" for="barrier_percentile_trigger_stop_loss_seven_level_pressure_apply">
                                                        <span class="onoffswitch-inner"></span>
                                                        <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                    <span class="alert alert-success" id="ssp" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                                </div>

                                            </div>



                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">Last 200 Contract Buyers Vs Sellers (Less then Recommended)</label>
                                                  <input type="text" step="any" class="form-control" name="barrier_percentile_trigger_stop_loss_last_200_contracts_buy_vs_sell" id="barrier_percentile_trigger_stop_loss_last_200_contracts_buy_vs_sell" value="">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>&nbsp;</label>
                                                      <div class="onoffswitch">
                                                        <input type="checkbox" name="barrier_percentile_trigger_stop_loss_last_200_contracts_buy_vs_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_stop_loss_last_200_contracts_buy_vs_sell_apply" value="yes" >
                                                        <label class="onoffswitch-label" for="barrier_percentile_trigger_stop_loss_last_200_contracts_buy_vs_sell_apply">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                      </div>
                                                </div>
                                            </div>




                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">Last 200 Contract Time (Less Then Recommended)</label>
                                                  <input type="text" step="any" class="form-control" name="barrier_percentile_trigger_stop_loss_last_200_contracts_time" id="barrier_percentile_trigger_stop_loss_last_200_contracts_time" value="">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>&nbsp;</label>
                                                      <div class="onoffswitch">
                                                        <input type="checkbox" name="barrier_percentile_trigger_stop_loss_last_200_contracts_time_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_stop_loss_last_200_contracts_time_apply" value="yes" >
                                                        <label class="onoffswitch-label" for="barrier_percentile_trigger_stop_loss_last_200_contracts_time_apply">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                      </div>
                                                </div>
                                            </div>



                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">Last Qty Contract Buyers Vs Sellers(Less Then Recommended) </label>
                                                  <input type="text" step="any" class="form-control" name="barrier_percentile_trigger_stop_loss_last_qty_contracts_buyer_vs_seller" id="barrier_percentile_trigger_stop_loss_last_qty_contracts_buyer_vs_seller" value="">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>&nbsp;</label>
                                                      <div class="onoffswitch">
                                                        <input type="checkbox" name="barrier_percentile_trigger_stop_loss_last_qty_contracts_buyer_vs_seller_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_stop_loss_last_qty_contracts_buyer_vs_seller_apply" value="yes" >
                                                        <label class="onoffswitch-label" for="barrier_percentile_trigger_stop_loss_last_qty_contracts_buyer_vs_seller_apply">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                      </div>
                                                </div>
                                            </div>

                                            <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">Last qty Contract time(Less then Recommended)</label>
                                                  <input type="text" step="any" class="form-control " name="barrier_percentile_trigger_stop_loss_last_qty_contracts_time" id="barrier_percentile_trigger_stop_loss_last_qty_contracts_time" value="">
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>&nbsp;</label>
                                                      <div class="onoffswitch">
                                                        <input type="checkbox" name="barrier_percentile_trigger_stop_loss_last_qty_contracts_time_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_stop_loss_last_qty_contracts_time_apply" value="yes" >
                                                        <label class="onoffswitch-label" for="barrier_percentile_trigger_stop_loss_last_qty_contracts_time_apply">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                      </div>
                                                </div>
                                            </div>
                                            <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

                                            <!-- %%%%%%%%%%%%% 5 Minute Rolling Candel %%%%%%%%%%%%% -->
                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(5 Minute Rolling Candel (Less Then ) From defined percentile)</label>
                                                  <select class="form-control" id="barrier_percentile_stop_loss_5_minute_rolling_candel_sell" name="barrier_percentile_stop_loss_5_minute_rolling_candel_sell">
                                                  <option value="1">Bottom 1% </option>
                                                  <option value="2">Bottom 2% </option>
                                                  <option value="3">Bottom 3% </option>
                                                  <option value="4">Bottom 4% </option>
                                                  <option value="5">Bottom 5% </option>
                                                  <option value="10">Bottom 10%</option>
                                                  <option value="15">Bottom 15%</option>
                                                  <option value="20">Bottom 20%</option>
                                                  <option value="25">Bottom 25%</option>
                                                  </select>
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_stop_loss_5_minute_rolling_candel_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_stop_loss_5_minute_rolling_candel_sell_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_stop_loss_5_minute_rolling_candel_sell_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="sfvm" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>
                                            <!-- %%%%%%%%%%%%% End if 5 Minute Rolling Candel %%%%%% -->


                                            <!-- %%%%%%%%%%%%% 15 Minute Rolling Candel %%%%%%%%%%%%% -->
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="control-label" for="hour">15 Minute Rolling Candel (Less Then)  From defined percentile</label>
                                                    <select class="form-control" id="barrier_percentile_stop_loss_15_minute_rolling_candel_sell" name="barrier_percentile_stop_loss_15_minute_rolling_candel_sell">
                                                    <option value="1">Bottom 1% </option>
                                                    <option value="2">Bottom 2% </option>
                                                    <option value="3">Bottom 3% </option>
                                                    <option value="4">Bottom 4% </option>
                                                    <option value="5">Bottom 5% </option>
                                                    <option value="10">Bottom 10%</option>
                                                    <option value="15">Bottom 15%</option>
                                                    <option value="20">Bottom 20%</option>
                                                    <option value="25">Bottom 25%</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>&nbsp;</label>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="barrier_percentile_stop_loss_15_minute_rolling_candel_sell_apply" class="onoffswitch-checkbox" id="barrier_percentile_stop_loss_15_minute_rolling_candel_sell_apply" value="yes" >
                                                        <label class="onoffswitch-label" for="barrier_percentile_stop_loss_15_minute_rolling_candel_sell_apply">
                                                        <span class="onoffswitch-inner"></span>
                                                        <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                    <span class="alert alert-success" id="sffm" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                                </div>
                                            </div>
                                            <!-- %%%%%%%%%%%%% End if 5 Minute Rolling Candel %%%%%% -->

                                            <!-- %%%%%%%%%%%%% Buyers %%%%%%%%%%%%% -->
                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(Buyer Should (Less Then) Then Bottom percentile)</label>
                                                  <select class="form-control" id="barrier_percentile_trigger_buyers_stop_loss" name="barrier_percentile_trigger_buyers_stop_loss">
                                                  <option value="1">Bottom 1% </option>
                                                  <option value="2">Bottom 2% </option>
                                                  <option value="3">Bottom 3% </option>
                                                  <option value="4">Bottom 4% </option>  
                                                  <option value="5">Bottom 5% </option>
                                                  <option value="10">Bottom 10%</option>
                                                  <option value="15">Bottom 15%</option>
                                                  <option value="20">Bottom 20%</option>
                                                  <option value="25">Bottom 25%</option>
                                                  </select>
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_buyers_stop_loss_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_buyers_stop_loss_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_buyers_stop_loss_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="slb" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>
                                             <!-- %%%%%%%%%%%%% End of Buyers %%%%%% -->


                                              <!-- %%%%%%%%%%%%% SELLERS %%%%%%%%%%%%% -->
                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(Sellers Should be (Greater From ) Top percentile)</label>
                                                  <select class="form-control" id="barrier_percentile_trigger_sellers_stop_loss" name="barrier_percentile_trigger_sellers_stop_loss">
                                                  <option value="1">Top 1% </option>
                                                  <option value="2">Top 2% </option>
                                                  <option value="3">Top 3% </option>
                                                  <option value="4">Top 4% </option>  
                                                  <option value="5">Top 5% </option>
                                                  <option value="10">Top 10%</option>
                                                  <option value="15">Top 15%</option>
                                                  <option value="20">Top 20%</option>
                                                  <option value="25">Top 25%</option>
                                                  </select>
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_sellers_stop_loss_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_sellers_stop_loss_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_sellers_stop_loss_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="sls" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>
                                             <!-- %%%%%%%%%%%%% End of SELLERS %%%%%% -->



                                          <!-- %%%%%%%%%%%%% Buyers 1 Minute Percentile %%%%%%%%%%%%% -->
                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(Buyer 1 minute Should (Less Then) Then Bottom percentile)</label>
                                                  <select class="form-control" id="barrier_percentile_trigger_buyers1_minute_stop_loss" name="barrier_percentile_trigger_buyers1_minute_stop_loss">
                                                  <option value="1">Bottom 1% </option>
                                                  <option value="2">Bottom 2% </option>
                                                  <option value="3">Bottom 3% </option>
                                                  <option value="4">Bottom 4% </option>
                                                  <option value="5">Bottom 5% </option>
                                                  <option value="10">Bottom 10%</option>
                                                  <option value="15">Bottom 15%</option>
                                                  <option value="20">Bottom 20%</option>
                                                  <option value="25">Bottom 25%</option>
                                                  </select>
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_buyers1_minute_stop_loss_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_buyers1_minute_stop_loss_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_buyers1_minute_stop_loss_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="slb" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>
                                             <!-- %%%%%%%%%%%%% End of Buyers 1 Minute Percentile %%%%%% -->


                                               <!-- %%%%%%%%%%%%% SELLERS %%%%%%%%%%%%% -->
                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                  <label class="control-label" for="hour">(Sellers 1 Minute  Should be (Greater From ) Top percentile)</label>
                                                  <select class="form-control" id="barrier_percentile_trigger_sellers_1_minute_stop_loss" name="barrier_percentile_trigger_sellers_1_minute_stop_loss">
                                                  <option value="1">Top 1% </option>
                                                  <option value="2">Top 2% </option>
                                                  <option value="3">Top 3% </option>
                                                  <option value="4">Top 4% </option>  
                                                  <option value="5">Top 5% </option>
                                                  <option value="10">Top 10%</option>
                                                  <option value="15">Top 15%</option>
                                                  <option value="20">Top 20%</option>
                                                  <option value="25">Top 25%</option>
                                                  </select>
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="barrier_percentile_trigger_sellers_1_minute_stop_loss_apply" class="onoffswitch-checkbox" id="barrier_percentile_trigger_sellers_1_minute_stop_loss_apply" value="yes" >
                                                      <label class="onoffswitch-label" for="barrier_percentile_trigger_sellers_1_minute_stop_loss_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                                  <span class="alert alert-success" id="sls" style="display: inline-block;float: right;margin-top: -40px;">Black Wall Percentile</span>
                                              </div>
                                            </div>
                                             <!-- %%%%%%%%%%%%% End of SELLERS %%%%%% -->


                                            <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                        </div>
                                        <!-- End of stop lOss -->


                                    </div>
                                  </div>
                                </div>

                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 1 Tabs -->

                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 2 Tabs -->
                               <div id="barrier_percentile_level_2" class="tab-pane fade ">
                                    <div id="append_percentile_level_2"></div>
                               </div>
                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 2 Tabs -->

                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 3 Tabs -->
                               <div id="barrier_percentile_level_3" class="tab-pane fade">
                                   <div id="append_percentile_level_3"></div>
                               </div>
                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 3 Tabs -->

                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 4 Tabs -->
                               <div id="barrier_percentile_level_4" class="tab-pane fade ">
                                  <div id="append_percentile_level_4"></div>
                               </div>
                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 4 Tabs -->

                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 5 Tabs -->
                               <div id="barrier_percentile_level_5" class="tab-pane fade">
                                  <div id="append_percentile_level_5"></div>
                               </div>
                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 5 Tabs -->

                                <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 6 Tabs -->
                                <div id="barrier_percentile_level_6" class="tab-pane fade">
                                 <div id="append_percentile_level_6"></div>
                               </div>
                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 6 Tabs -->

                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 7 Tabs -->
                               <div id="barrier_percentile_level_7" class="tab-pane fade">
                                 <div id="append_percentile_level_7"></div>
                               </div>
                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 7 Tabs -->


                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 8 Tabs -->
                               <div id="barrier_percentile_level_8" class="tab-pane fade">
                                 <div id="append_percentile_level_8"></div>
                               </div>
                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 6 Tabs -->


                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 8 Tabs -->
                               <div id="barrier_percentile_level_9" class="tab-pane fade">
                                 <div id="append_percentile_level_9"></div>
                               </div>
                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 9 Tabs -->

                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 10 Tabs -->
                               <div id="barrier_percentile_level_10" class="tab-pane fade">
                                 <div id="append_percentile_level_10"></div>
                               </div>
                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 10 Tabs -->

                             </div>


                        </div><!-- End of Barrier percentile trigger -->







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
                          <?php for ($rule_number = 1; $rule_number <= 10; $rule_number++) {
    ?>
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
                                    <label class="control-label" for="hour">(Support Barrier)(Virtual Barrier) (Shoul be Greater or Eqaual from Defined value)</label>
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
                            
                            <!--Order Book Barrier  -->
                              <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label" for="hour">(Resistance Barrier)(Should be less or Equal from Defined value)</label>
                                    <input type="text" step="any" class="form-control" name="sell_virtural_for_buy_rule_<?php echo $rule_number; ?>" id="sell_virtural_for_buy_rule_<?php echo $rule_number; ?>" value="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>&nbsp;</label>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="sell_virtural_for_buy_rule_<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="sell_virtural_for_buy_rule_<?php echo $rule_number; ?>_enable" value="yes" >
                                        <label class="onoffswitch-label" for="sell_virtural_for_buy_rule_<?php echo $rule_number; ?>_enable">
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


                               <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                               <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Rule Sorting</label>
                                      <select class="form-control" name="order_status<?php echo $rule_number; ?>_buy" id="order_status<?php echo $rule_number; ?>_buy">

                                        <option value="">
                                        Select Rule Sorting
                                        </option>
                                        <?php
                                        for ($index = 1; $index <= 10; $index++) {

                                        for ($index = 1; $index <= 10; $index++) {
                                        ?>
                                        <option value="<?php echo $index; ?>"><?php echo $index; ?></option>
                                        <?php
                                        }

                                        }
                                        ?>

                                      </select>


                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="order_status<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="order_status<?php echo $rule_number; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="order_status<?php echo $rule_number; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div>
                               </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

                              <!-- Buyers vs sellers 15 minutes  -->
                                           <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                               <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Buyers Vs Sellers 15 Minute</label>
                                      <input type="text" step="any" class="form-control" name="buyers_vs_sellers<?php echo $rule_number; ?>_buy" id="buyers_vs_sellers<?php echo $rule_number; ?>_buy" value="">


                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="buyers_vs_sellers_buy<?php echo $rule_number; ?>_enable" class="onoffswitch-checkbox" id="buyers_vs_sellers_buy<?php echo $rule_number; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="buyers_vs_sellers_buy<?php echo $rule_number; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div>
                               </div>
                              <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->


                              <!-- %%%%%%%%%%%%%%%% start of Ask Percentile %%%%%%%%%%%%%%%%%%% -->

                              <div class="row">
                                  <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">( (Binance buy) ASk Should be greater or Equal  from Defined value) (One minute Rooling candle)</label>
                                      <input type="text" class="form-control" id="ask_percentile_<?php echo $rule_number; ?>_buy" name="ask_percentile_<?php echo $rule_number; ?>_buy">
                                  </div>

                                  <div class="form-group col-md-6">
                                      <label>&nbsp;</label>
                                      <div class="onoffswitch">
                                          <input type="checkbox" name="ask_percentile_<?php echo $rule_number; ?>_apply_buy" class="onoffswitch-checkbox" id="ask_percentile_<?php echo $rule_number; ?>_apply_buy" value="yes" >
                                          <label class="onoffswitch-label" for="ask_percentile_<?php echo $rule_number; ?>_apply_buy">
                                          <span class="onoffswitch-inner"></span>
                                          <span class="onoffswitch-switch"></span>
                                          </label>
                                      </div>
                                      
                                  </div>
                              </div>

                              <!-- %%%%%%%%%%%%%%%% End of buy Percentile %%%%%%%%%%%%%%%%%%% -->

                               <!--%%%%%%%%%%%% -- Bid Percentile -- %%%%%%%%%%%%%%%%%   -->
                               <div class="row">
                                  <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">((Binance Sell)Bid Should be Less or Equal from Defined value) (One minute Rooling candle)</label>
                                      <input type="text" class="form-control" id="bid_percentile_<?php echo $rule_number; ?>_buy" name="bid_percentile_<?php echo $rule_number; ?>_buy">
                                  </div>

                                  <div class="form-group col-md-6">
                                      <label>&nbsp;</label>
                                      <div class="onoffswitch">
                                          <input type="checkbox" name="bid_percentile_<?php echo $rule_number; ?>_apply_buy" class="onoffswitch-checkbox" id="bid_percentile_<?php echo $rule_number; ?>_apply_buy" value="yes" >
                                          <label class="onoffswitch-label" for="bid_percentile_<?php echo $rule_number; ?>_apply_buy">
                                          <span class="onoffswitch-inner"></span>
                                          <span class="onoffswitch-switch"></span>
                                          </label>
                                      </div>
                                      
                                  </div>
                              </div>
                                <!--%%%%%%%%%%%% End  Sell Percentile %%%%%%%%%%%%%%%%%  -->


                              <!-- %%%%%%%%%%%%%%%% start of Buy Percentile %%%%%%%%%%%%%%%%%%% -->

                              <div class="row">
                                  <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">((Digie Buy) Buy Should be greater or Eqaual from defined value) (One minute Rooling candle)</label>

                                      <input type="text" class="form-control" id="buy_percentile_<?php echo $rule_number; ?>_buy" name="buy_percentile_<?php echo $rule_number; ?>_buy">
                                  </div>

                                  <div class="form-group col-md-6">
                                      <label>&nbsp;</label>
                                      <div class="onoffswitch">
                                          <input type="checkbox" name="buy_percentile_<?php echo $rule_number; ?>_apply_buy" class="onoffswitch-checkbox" id="buy_percentile_<?php echo $rule_number; ?>_apply_buy" value="yes" >
                                          <label class="onoffswitch-label" for="buy_percentile_<?php echo $rule_number; ?>_apply_buy">
                                          <span class="onoffswitch-inner"></span>
                                          <span class="onoffswitch-switch"></span>
                                          </label>
                                      </div>
                                   
                                  </div>
                              </div>

                              <!-- %%%%%%%%%%%%%%%% End of buy Percentile %%%%%%%%%%%%%%%%%%% -->

                              <!--%%%%%%%%%%%% -- Sell Percentile -- %%%%%%%%%%%%%%%%%   -->
                              <div class="row">
                                  <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">((Digie Sell) Sell Should be Less or Equal from Defined value) (One minute Rooling candle)</label>
                                      <input type="text" class="form-control" id="sell_percentile_<?php echo $rule_number; ?>_buy" name="sell_percentile_<?php echo $rule_number; ?>_buy">
                                  </div>

                                  <div class="form-group col-md-6">
                                      <label>&nbsp;</label>
                                      <div class="onoffswitch">
                                          <input type="checkbox" name="sell_percentile_<?php echo $rule_number; ?>_apply_buy" class="onoffswitch-checkbox" id="sell_percentile_<?php echo $rule_number; ?>_apply_buy" value="yes" >
                                          <label class="onoffswitch-label" for="sell_percentile_<?php echo $rule_number; ?>_apply_buy">
                                          <span class="onoffswitch-inner"></span>
                                          <span class="onoffswitch-switch"></span>
                                          </label>
                                      </div>
                                    
                                  </div>
                              </div>
                              <!--%%%%%%%%%%%% End  Sell Percentile %%%%%%%%%%%%%%%%%  -->

                              

                            <!-- End of buyser vs sellers -->




                          </div>
                         <?php }?>
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
                      <?php for ($sell_r_n = 1; $sell_r_n <= 10; $sell_r_n++) {
                        ?>
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
                                <label class="control-label" for="hour">(Support Barrier)(Virtual Barrier) (less or Equal From defined value)</label>
                                <input type="text" step="any" class="form-control" name="buy_virtural_rule_for_sell_<?php echo $sell_r_n; ?>" id="buy_virtural_rule_for_sell_<?php echo $sell_r_n; ?>" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <label>&nbsp;</label>
                                <div class="onoffswitch">
                                    <input type="checkbox" name="buy_virtural_rule_for_sell_<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="buy_virtural_rule_for_sell_<?php echo $sell_r_n; ?>_enable" value="yes" >
                                    <label class="onoffswitch-label" for="buy_virtural_rule_for_sell_<?php echo $sell_r_n; ?>_enable">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- End of book barrier -->

                        <!--Order Book Barrier  -->
                        <div class="row">
                          <div class="form-group col-md-6">
                              <label class="control-label" for="hour">(Resistance Barrier)(Resistance Should be Greater or Equal then Defined value)</label>
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

                            <!-- %%%%%%%%%%% Order Listing %%%%%%%%%%%%%%%% -->
                                  <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Rule Sortng</label>
                                      <select class="form-control" name="rule_sort<?php echo $sell_r_n; ?>_sell" id="rule_sort<?php echo $sell_r_n; ?>_sell">

                                        <option value="">
                                        Select Rule Sorting
                                        </option>

                                        <?php
                                            for ($index = 1; $index <= 10; $index++) {
                                            ?>
                                            <option value="<?php echo $index; ?>"><?php echo $index; ?></option>
                                            <?php
                                            }
                                            ?>
                                      </select>


                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="rule_sorting<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="rule_sorting<?php echo $sell_r_n; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="rule_sorting<?php echo $sell_r_n; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div>
                                </div>
                            <!-- %%%%%%%%%%%%%% End of order Listing %%%%%% -->


                            <!-- %%%%%%%%%%%%%%%%%% Buyers Vs Sellers %%%%%%%%%%%%% -->
                            <div class="row">
                                    <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">Buyers Vs Sellers 15 Minute</label>

                                      <input type="text" step="any" class="form-control" name="buyers_vs_sellers<?php echo $sell_r_n; ?>_sell" id="buyers_vs_sellers<?php echo $sell_r_n; ?>_sell" value="">


                                    </div>
                                    <div class="form-group col-md-6">
                                         <label>&nbsp;</label>
                                          <div class="onoffswitch">
                                            <input type="checkbox" name="buyers_vs_sellers_sell<?php echo $sell_r_n; ?>_enable" class="onoffswitch-checkbox" id="buyers_vs_sellers_sell<?php echo $sell_r_n; ?>_enable" value="yes" >
                                            <label class="onoffswitch-label" for="buyers_vs_sellers_sell<?php echo $sell_r_n; ?>_enable">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                          </div>
                                    </div>
                                </div>
                            <!-- %%%%%%%%%%%%%%%%%%%% End of Vs Sellers %%%%%%%%%%%  -->



                            <!-- %%%%%%%%%%%%%%% BArrier Sell percentile %%%%%%%%%%%%%%% -->
                              
                              
                              <!-- %%%%%%%%%%%%%%%% start of Buy Percentile %%%%%%%%%%%%%%%%%%% -->
                              <div class="row">
                                  <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">((Digie Buy) Buy Should be Less from Bottom percentile)(One minute rolling)</label>
                                      <input type="text" class="form-control" id="buy_percentile_<?php echo $sell_r_n; ?>_sell" name="buy_percentile_<?php echo $sell_r_n; ?>_sell">
                
                                  </div>

                                  <div class="form-group col-md-6">
                                      <label>&nbsp;</label>
                                      <div class="onoffswitch">
                                          <input type="checkbox" name="buy_percentile_<?php echo $sell_r_n; ?>_apply_sell" class="onoffswitch-checkbox" id="buy_percentile_<?php echo $sell_r_n; ?>_apply_sell" value="yes" >
                                          <label class="onoffswitch-label" for="buy_percentile_<?php echo $sell_r_n; ?>_apply_sell">
                                          <span class="onoffswitch-inner"></span>
                                          <span class="onoffswitch-switch"></span>
                                          </label>
                                      </div>
                                   
                                  </div>
                              </div>
                              <!-- %%%%%%%%%%%%%%%% End of buy Percentile %%%%%%%%%%%%%%%%%%% -->


                            <!-- %%%%%%%%%%%% ASk Percentile %%%%%%%%%%%%%%%%%%%%% -->
                            <div class="row">
                                  <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">((Binance Buy) ASk Should be Less from Bottom percentile)(One minute rolling)</label>
                                      <input type="text" class="form-control" id="ask_percentile_<?php echo $sell_r_n; ?>_sell" name="ask_percentile_<?php echo $sell_r_n; ?>_sell">
                                      
                                  </div>

                                  <div class="form-group col-md-6">
                                      <label>&nbsp;</label>
                                      <div class="onoffswitch">
                                          <input type="checkbox" name="ask_percentile_<?php echo $sell_r_n; ?>_apply_sell" class="onoffswitch-checkbox" id="ask_percentile_<?php echo $sell_r_n; ?>_apply_sell" value="yes" >
                                          <label class="onoffswitch-label" for="ask_percentile_<?php echo $sell_r_n; ?>_apply_sell">
                                          <span class="onoffswitch-inner"></span>
                                          <span class="onoffswitch-switch"></span>
                                          </label>
                                      </div>
                                      
                                  </div>
                              </div>
                            <!-- %%%%%%%%%%%% End ASk Percentile %%%%%%%%%%%%%%%%%%%%% -->


                            <!--%%%%%%%%%%%% -- Sell Percentile -- %%%%%%%%%%%%%%%%%   -->
                            <div class="row">
                                  <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">(Digie Sell)(Sell Should be Greater Then Top percentile)(One minute rolling)</label>
                                      <input type="text" class="form-control" id="sell_percentile_<?php echo $sell_r_n; ?>_sell" name="sell_percentile_<?php echo $sell_r_n; ?>_sell">
                                  </div>

                                  <div class="form-group col-md-6">
                                      <label>&nbsp;</label>
                                      <div class="onoffswitch">
                                          <input type="checkbox" name="sell_percentile_<?php echo $sell_r_n; ?>_apply_sell" class="onoffswitch-checkbox" id="sell_percentile_<?php echo $sell_r_n; ?>_apply_sell" value="yes" >
                                          <label class="onoffswitch-label" for="sell_percentile_<?php echo $sell_r_n; ?>_apply_sell">
                                          <span class="onoffswitch-inner"></span>
                                          <span class="onoffswitch-switch"></span>
                                          </label>
                                      </div>
                                    
                                  </div>
                              </div>
                              <!--%%%%%%%%%%%% End  Sell Percentile %%%%%%%%%%%%%%%%%  -->

                            

                            <!--%%%%%%%%%%%% -- Bid Percentile -- %%%%%%%%%%%%%%%%%   -->
                            <div class="row">
                                  <div class="form-group col-md-6">
                                      <label class="control-label" for="hour">((Binance Sell) Bid Should be Greater Then Top percentile)(One minute rolling)</label>
                                      <input type="text" class="form-control" id="bid_percentile_<?php echo $sell_r_n; ?>_sell" name="bid_percentile_<?php echo $sell_r_n; ?>_sell">
                                  </div>

                                  <div class="form-group col-md-6">
                                      <label>&nbsp;</label>
                                      <div class="onoffswitch">
                                          <input type="checkbox" name="bid_percentile_<?php echo $sell_r_n; ?>_apply_sell" class="onoffswitch-checkbox" id="bid_percentile_<?php echo $sell_r_n; ?>_apply_sell" value="yes" >
                                          <label class="onoffswitch-label" for="bid_percentile_<?php echo $sell_r_n; ?>_apply_sell">
                                          <span class="onoffswitch-inner"></span>
                                          <span class="onoffswitch-switch"></span>
                                          </label>
                                      </div>
                                      
                                  </div>
                              </div>
                                <!--%%%%%%%%%%%% End  Sell Percentile %%%%%%%%%%%%%%%%%  -->


                            <!-- %%%%%%%%%%% End of Barrier Sell Percentile %%%%%%%%%%%%%% -->



                      </div><!--End of Sell Setting 1 -->
                     <?php }?>
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




              <!-- %%%%%%%%%%%%%%%%%%% Market  Trending %%%%%%%%%%%%%%%%%%%%%%% -->
                                            
              <div class="show_hide_market_trend global_setting buy_sell_box" style="display:none">

                  <!-- %%%%%%%%%%%%%%%%  Leves Headings %%%%%%%% -->
                  <ul class="nav nav-tabs market_trend_tr_tab" id="#tabs">
                      <li class="active_inactive_market_trend_level active" id="level_tab_1"><a data-toggle="tab" href="#market_trend_level_1">Level 1</a></li>

                      <li class="active_inactive_market_trend_level" id="level_tab_2"><a data-toggle="tab" href="#market_trend_level_2">Level 2</a></li>

                      <li class="active_inactive_market_trend_level" id="level_tab_3"><a data-toggle="tab" href="#market_trend_level_3">Level 3</a></li>

                      <li class="active_inactive_market_trend_level" id="level_tab_4"><a data-toggle="tab" href="#market_trend_level_4">Level 4</a></li>

                      <li class="active_inactive_market_trend_level" id="level_tab_5"><a data-toggle="tab" href="#market_trend_level_5">Level 5</a></li>

                      <li class="active_inactive_market_trend_level" id="level_tab_6"><a data-toggle="tab" href="#market_trend_level_6">Level 6</a></li>

                      <li class="active_inactive_market_trend_level" id="level_tab_7"><a data-toggle="tab" href="#market_trend_level_7">Level 7</a></li>

                      <li class="active_inactive_market_trend_level" id="level_tab_8"><a data-toggle="tab" href="#market_trend_level_8">Level 8</a></li>


                      <li class="active_inactive_market_trend_level" id="level_tab_9"><a data-toggle="tab" href="#market_trend_level_9">Level 9</a></li>

                      <li class="active_inactive_market_trend_level" id="level_tab_10"><a data-toggle="tab" href="#market_trend_level_10">Level 10</a></li>
                  </ul>


                  <!-- Percentile Trigger Leves -->


                  <div class="tab-content ">
                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 1 Tabs -->

                      <div id="market_trend_level_1" class="tab-pane fade in active">
                        <div id="market_trend_level_1">
                          <h4>Barrier percentile trigger <span style="color:yellowgreen;font-size:16px" id="market_trend_level_num"> 1</span></h4>
                          <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#market_trend_buy">Buy Rules</a></li>
                            <li><a data-toggle="tab" href="#market_trend_sell">Sell</a></li>
                            
                          </ul>

                          <div class="tab-content ">

                                <!--barrier percentile   Buy part Start -->
                                <div id="market_trend_buy" class="tab-pane fade in active">
                                      <!--  ************* Buy Part *********** -->
                                          

                                       <!-- %%%%%%%%%%%%%%%% --  On off part -- %%%%%%%%%%%%%  -->
                                      <div class="row" style="margin-top: 30px;">
                                          <div class="col-md-6">
                                              <h2 class="buy_sell_box_heading">
                                              <dd class="pull-left">
                                              Buy Market Trends setting
                                              </dd>
                                              <label class="pull-right" style="margin-top: 11px; margin-bottom: 0;">
                                              <strong>
                                              Enable Disable Buy Market Trend Trigger
                                              </strong>
                                              </label>
                                              </h2>
                                          </div>

                                          <div class="col-md-6">
                                              <div class="onoffswitch">
                                                      <input type="checkbox" name="enable_buy_market_trends_trigger" class="onoffswitch-checkbox" id="enable_buy_market_trends_trigger" value="yes">
                                                      <label class="onoffswitch-label" for="enable_buy_market_trends_trigger">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                              </div>
                                          </div>
                                        
                                      </div>
                                      <!-- %%%%%%%%%%%%%%%% Start of buy part %%%%%%%%%  -->
                                            
                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Caption Option
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_caption_option_buy" id="market_trend_caption_option_buy" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_caption_option_operator_buy" id="market_trend_caption_option_operator_buy" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_caption_option_buy_apply" class="onoffswitch-checkbox" id="market_trend_caption_option_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_caption_option_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_caption_option" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div> <!-- End of caption  -->
                                          

                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Caption score
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_caption_score_buy" id="market_trend_caption_score_buy" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_caption_score_operator_buy" id="market_trend_caption_score_operator_buy" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_caption_score_buy_apply" class="onoffswitch-checkbox" id="market_trend_caption_score_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_caption_score_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_caption_score" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of caption score -->


                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Buy
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_buy_trend_buy" id="market_trend_buy_trend_buy" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_buy_trend_operator_buy" id="market_trend_buy_trend_operator_buy" class="form-control"> 
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_buy_trend_buy_apply" class="onoffswitch-checkbox" id="market_trend_buy_trend_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_buy_trend_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_buy" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of buy  -->



                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Sell
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_sell_buy" id="market_trend_sell_buy" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_sell_operator_buy" id="market_trend_sell_operator_buy" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_sell_buy_apply" class="onoffswitch-checkbox" id="market_trend_sell_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_sell_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_sell" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of sell  -->


                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Demand
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_demand_buy" id="market_trend_demand_buy" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_demand_operator_buy" id="market_trend_demand_operator_buy" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_demand_buy_apply" class="onoffswitch-checkbox" id="market_trend_demand_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_demand_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_demand" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of demamd  -->


                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Supply
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_supply_buy" id="market_trend_supply_buy" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_supply_operator_buy" id="market_trend_supply_operator_buy" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_supply_buy_apply" class="onoffswitch-checkbox" id="market_trend_supply_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_supply_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_supply" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of supply  -->



                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Market Trend
                                                </label>
                                                <select class="form-control" name="market_trend_market_trend_buy" id="market_trend_market_trend_buy">
                                                    <option value="POSITIVE">POSITIVE</option>
                                                    <option value="NEGATIVE">NEGATIVE</option>
                                                </select>
                                               
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_market_trend_operator_buy" id="market_trend_market_trend_operator_buy" class="form-control">
                                                  <option value="==">Equal (==) </option>
                                                  <option value="!=">Not Equal (!=)</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_market_trend_buy_apply" class="onoffswitch-checkbox" id="market_trend_market_trend_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_market_trend_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <span class="alert alert-success mt_market_trend" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of trend_operator  -->


                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Meta Tranding
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_meta_trading_buy" id="market_trend_meta_trading_buy" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_meta_trading_operator_buy" id="market_trend_meta_trading_operator_buy" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_meta_trading_buy_apply" class="onoffswitch-checkbox" id="market_trend_meta_trading_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_meta_trading_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_meta_trading" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of meta_trading  -->

                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Resk per Share
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_riskpershare_buy" id="market_trend_riskpershare_buy" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_riskpershare_operator_buy" id="market_trend_riskpershare_operator_buy" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_riskpershare_buy_apply" class="onoffswitch-checkbox" id="market_trend_riskpershare_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_riskpershare_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_riskpershare" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of meta_trading  -->

                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Balack Wall
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_black_wall_buy" id="market_trend_black_wall_buy" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_black_wall_operator_buy" id="market_trend_black_wall_operator_buy" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_black_wall_buy_apply" class="onoffswitch-checkbox" id="market_trend_black_wall_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_black_wall_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_black_wall" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of black wall  -->


                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Seven level pressure
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_seven_level_pressure_buy" id="market_trend_seven_level_pressure_buy" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_seven_level_pressure_operator_buy" id="market_trend_seven_level_pressure_operator_buy" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_seven_level_pressure_buy_apply" class="onoffswitch-checkbox" id="market_trend_seven_level_pressure_buy_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_seven_level_pressure_buy_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="alert alert-success mt_seven_level_pressure" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End of black wall  -->



                                        <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                              
                                          <div class="row">
                                              <div class="form-group col-md-3">
                                                  <label class="control-label" for="hour">
                                                      RL
                                                  </label>
                                                  <input type="text" step="any" class="form-control" name="market_trend_RL_buy" id="market_trend_RL_buy" value="">
                                              </div>

                                              <div class="form-group col-md-3">
                                                  <label class="control-label" for="hour">Select Operator </label>
                                                  <select name="market_trend_RL_operator_buy" id="market_trend_RL_operator_buy" class="form-control">
                                                      <option value=">=">Greater or equal (>=) </option>
                                                      <option value="<=">Less then or equal (<=) </option>
                                                  </select>
                                              </div>

                                              <div class="form-group col-md-6">
                                                  <label>&nbsp;</label>
                                                  <div class="onoffswitch">
                                                      <input type="checkbox" name="market_trend_RL_buy_apply" class="onoffswitch-checkbox" id="market_trend_RL_buy_apply" value="yes">
                                                      <label class="onoffswitch-label" for="market_trend_RL_buy_apply">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                  </div>
                                              </div>
                                              <span class="alert alert-success mt_rl" id="" style="display: inline-block;float: right;margin-top: -40px;">0</span>
                                          </div><!-- End RL  -->

                                        <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

                                      <!-- %%%%%%%%%%%%%%%%%%% End of buy part %%%%%%%%%%% -->



                                </div> <!-- End of buy par -->





                                <!-- barrier End   Sell part Start -->
                                <div id="market_trend_sell" class="tab-pane fade">

                                    <!-- %%%%%%%%%%%%%%%% --  On off part -- %%%%%%%%%%%%%  -->
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col-md-6">
                                            <h2 class="buy_sell_box_heading">
                                            <dd class="pull-left">
                                            Sell Market Trends setting
                                            </dd>
                                            <label class="pull-right" style="margin-top: 11px; margin-bottom: 0;">
                                            <strong>
                                            Enable Disable Sell Market Trend Trigger
                                            </strong>
                                            </label>
                                            </h2>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="onoffswitch">
                                                    <input type="checkbox" name="enable_sell_market_trends_trigger" class="onoffswitch-checkbox" id="enable_sell_market_trends_trigger" value="yes">
                                                    <label class="onoffswitch-label" for="enable_sell_market_trends_trigger">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <!---- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Caption Option
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_caption_option_sell" id="market_trend_caption_option_sell" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_caption_option_operator_sell" id="market_trend_caption_option_operator_sell" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_caption_option_sell_apply" class="onoffswitch-checkbox" id="market_trend_caption_option_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_caption_option_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                          </div> <!-- End of caption  -->
                                          

                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Caption score
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_caption_score_sell" id="market_trend_caption_score_sell" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_caption_score_operator_sell" id="market_trend_caption_score_operator_sell" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_caption_score_sell_apply" class="onoffswitch-checkbox" id="market_trend_caption_score_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_caption_score_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                          </div><!-- End of caption score -->


                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Buy
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_buy_sell" id="market_trend_buy_sell" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_buy_operator_sell" id="market_trend_buy_operator_sell" class="form-control"> 
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_buy_operator_sell_apply" class="onoffswitch-checkbox" id="market_trend_buy_operator_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_buy_operator_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                          </div><!-- End of buy  -->



                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Sell
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_sell_trend_sell" id="market_trend_sell_trend_sell" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_sell_trend_operator_sell" id="market_trend_sell_trend_operator_sell" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_sell_trend_sell_apply" class="onoffswitch-checkbox" id="market_trend_sell_trend_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_sell_trend_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                          </div><!-- End of sell  -->


                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Demand
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_demand_sell" id="market_trend_demand_sell" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_demand_operator_sell" id="market_trend_demand_operator_sell" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_demand_sell_apply" class="onoffswitch-checkbox" id="market_trend_demand_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_demand_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                          </div><!-- End of demamd  -->


                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Supply
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_supply_sell" id="market_trend_supply_sell" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_supply_operator_sell" id="market_trend_supply_operator_sell" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_supply_sell_apply" class="onoffswitch-checkbox" id="market_trend_supply_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_supply_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                          </div><!-- End of supply  -->



                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Market Trend
                                                </label>

                                                <select class="form-control" name="market_trend_market_trend_sell" id="market_trend_market_trend_sell">
                                                    <option value="POSITIVE">POSITIVE</option>
                                                    <option value="NEGATIVE">NEGATIVE</option>
                                                </select>

                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_market_trend_operator_sell" id="market_trend_market_trend_operator_sell" class="form-control">
                                                <option value="==">Equal (==) </option>
                                                <option value="!=">Not Equal (!=)</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_market_trend_operator_sell_apply" class="onoffswitch-checkbox" id="market_trend_market_trend_operator_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_market_trend_operator_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                          </div><!-- End of trend_operator  -->


                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Meta Tranding
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_meta_trading_sell" id="market_trend_meta_trading_sell" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_meta_trading_operator_sell" id="market_trend_meta_trading_operator_sell" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_meta_trading_sell_apply" class="onoffswitch-checkbox" id="market_trend_meta_trading_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_meta_trading_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                          </div><!-- End of meta_trading  -->

                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Resk per Share
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_riskpershare_sell" id="market_trend_riskpershare_sell" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_riskpershare_operator_sell" id="market_trend_riskpershare_operator_sell" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_riskpershare_sell_apply" class="onoffswitch-checkbox" id="market_trend_riskpershare_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_riskpershare_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                          </div><!-- End of meta_trading  -->

                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Balack Wall
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_black_wall_sell" id="market_trend_black_wall_sell" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_black_wall_operator_sell" id="market_trend_black_wall_operator_sell" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_black_wall_sell_apply" class="onoffswitch-checkbox" id="market_trend_black_wall_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_black_wall_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                          </div><!-- End of black wall  -->


                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  Seven level pressure
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_seven_level_pressure_sell" id="market_trend_seven_level_pressure_sell" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_seven_level_pressure_operator_sell" id="market_trend_seven_level_pressure_operator_sell" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_seven_level_pressure_sell_apply" class="onoffswitch-checkbox" id="market_trend_seven_level_pressure_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_seven_level_pressure_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                          </div><!-- End of black wall  -->



                                          <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                                            
                                          <div class="row">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">
                                                  RL
                                                </label>
                                                <input type="text" step="any" class="form-control" name="market_trend_RL_sell" id="market_trend_RL_sell" value="">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="hour">Select Operator </label>
                                                <select name="market_trend_RL_operator_sell" id="market_trend_RL_operator_sell" class="form-control">
                                                  <option value=">=">Greater or equal (>=) </option>
                                                  <option value="<=">Less then or equal (<=) </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" name="market_trend_RL_sell_apply" class="onoffswitch-checkbox" id="market_trend_RL_sell_apply" value="yes">
                                                    <label class="onoffswitch-label" for="market_trend_RL_sell_apply">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                          </div><!-- End RL  -->

                                          <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->


                                          
                                    <!-- %%%%%%%%%%%%%%%% End of sell part %%%%%%%%%% -->
                                            
                                </div><!-- %%%%%%%%%%%% End of sell part %%%%%%%%%%% -->

                                <!-- barrier End   Sell part Start -->
                            
                          </div> <!-- End of Internal Tab  -->
                      </div>

                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 1 Tabs -->

                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 2 Tabs -->
                    <div id="market_trend_level_2" class="tab-pane fade ">
                          <div id="append_market_trend_level_2"></div>
                    </div>
                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 2 Tabs -->

                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 3 Tabs -->
                    <div id="market_trend_level_3" class="tab-pane fade ">
                          <div id="append_market_trend_level_3"></div>
                    </div>
                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 3 Tabs -->

                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 4 Tabs -->
                    <div id="market_trend_level_4" class="tab-pane fade ">
                          <div id="append_market_trend_level_4"></div>
                    </div>
                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 4 Tabs -->

                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 5 Tabs -->
                    <div id="market_trend_level_5" class="tab-pane fade ">
                          <div id="append_market_trend_level_5"></div>
                    </div>
                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 5 Tabs -->

                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 6 Tabs -->
                    <div id="market_trend_level_6" class="tab-pane fade ">
                          <div id="append_market_trend_level_6"></div>
                    </div>
                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 6 Tabs -->

                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 7 Tabs -->
                    <div id="market_trend_level_7" class="tab-pane fade ">
                          <div id="append_market_trend_level_7"></div>
                    </div>
                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 7 Tabs -->


                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 8 Tabs -->
                    <div id="market_trend_level_8" class="tab-pane fade ">
                          <div id="append_market_trend_level_8"></div>
                    </div>
                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 6 Tabs -->


                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 8 Tabs -->
                    <div id="market_trend_level_9" class="tab-pane fade ">
                          <div id="append_market_trend_level_9"></div>
                    </div>
                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 9 Tabs -->

                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Level 10 Tabs -->
                    <div id="market_trend_level_10" class="tab-pane fade ">
                          <div id="append_market_trend_level_10"></div>
                    </div>
                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End of Level 10 Tabs -->

                  </div> <!-- End of outer tab content -->


              </div><!-- %%%%%%%%%% End of Barrier percentile trigger %%%%%% -->
              </div>
              <!-- %%%%%%%%%%%%%%%%% End of  markert trending %%%%%%%%%%%%%% -->

          <hr class="separator" />

          <!-- Form actions -->
          <div class="form-actions">
            <button type="button" id="clint_info_btn" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
          </div>
          <!-- // Form actions END -->
          </form>
        </div>
        </div>
      </div>
  </div>

</div>


<script type="text/javascript">

  $(document).on('change','.triggers_type',function(){
      $('#trigger_level').val('level_1');

      $tr = $(this).val();

      //$('.active_inactive_percentile_level').removeClass('active');
      $( "#level_tab_1" ).addClass( "active" );
      // $( "#level_tab_2" ).removeClass( "active" );
      // $( "#level_tab_3" ).removeClass( "active" );
      // $( "#level_tab_4" ).removeClass( "active" );
      // $( "#level_tab_5" ).removeClass( "active" );

      $( "#barrier_percentile_level_1" ).addClass( "active in" );

      get_trigger_setting();
      get_percentiles();

      if($tr =='market_trend_trigger' || $tr =='barrier_percentile_trigger'){
        get_market_trend();
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
    

    //%%%%%%%%%%%%%%%%%%%%%%%%% --- -- %%%%%%%%%%%%%%%%%%%%%%%%%%
    $('.market_trend_tr_tab a').click(function(){
      $(this).tab('show');
    });
    

      // The on tab shown event
    $('.market_trend_tr_tab a').on('shown.bs.tab', function (e) {
      var current_tab =  $(e.target).attr("href");
      var previous_tab =  $(e.relatedTarget).attr("href");
      var current_tab = current_tab.match(/\d+/);
      var previous_tab = previous_tab.match(/\d+/);
      $('#trigger_level').val('level_'+current_tab);
      $('#market_trend_level_num').html(current_tab);

      var keep_data = $( "#append_market_trend_level_"+previous_tab+" > *").detach();


      keep_data.appendTo("#append_market_trend_level_"+current_tab);

        get_trigger_setting();
        get_market_trend();

    });

    //%%%%%%%%%%%%%%%%%%%%%%%%%%% -- -- %%%%%%%%%%%%%%%%%%%%%%

   //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    $('.percentile_tr_tab a').click(function(){
      $(this).tab('show');
    });
    // The on tab shown event
    $('.percentile_tr_tab a').on('shown.bs.tab', function (e) {
      var current_tab =  $(e.target).attr("href");
      var previous_tab =  $(e.relatedTarget).attr("href");
      var current_tab = current_tab.match(/\d+/);
      var previous_tab = previous_tab.match(/\d+/);
      $('#trigger_level').val('level_'+current_tab);
      $('#percentile_level_num').html(current_tab);

      var keep_data = $( "#append_percentile_level_"+previous_tab+" > *").detach();

      keep_data.appendTo("#append_percentile_level_"+current_tab);

        get_trigger_setting();
        get_percentiles();
        get_market_trend();

    });

    //%%%%%%%%%%%%%%%%%%%%%%%%%% Box Trigger Part %%%%%%%%%%%%%%%%%%%%%%%%%%%
    $('.box_trigger_tr_tab a').click(function(){
      $(this).tab('show');
    });
    // The on tab shown event
    $('.box_trigger_tr_tab a').on('shown.bs.tab', function (e) {
      var current_tab =  $(e.target).attr("href");
      var previous_tab =  $(e.relatedTarget).attr("href");
      var current_tab = current_tab.match(/\d+/);
      var previous_tab = previous_tab.match(/\d+/);
      $('#trigger_level').val('level_'+current_tab);

      var keep_data = $( "#append_box_level_"+previous_tab+" > *").detach();

      keep_data.appendTo("#append_box_level_"+current_tab);

        get_trigger_setting();
        get_percentiles();

    });
    //%%%%%%%%%%%%%%%%%%%% End of Box Trigger Part %%%%%%%%%%%%%%%%%%%%%%%%%%

    });//End of Document .Ready


    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -- Function -- getting Trigger Data

    function get_trigger_setting(){
      var triggers_type = $('.triggers_type').val();
      var order_mode = $('.order_mode').val();
      var coin = $('#coin').val();

      var trigger_level = '';
      if( (triggers_type == 'barrier_percentile_trigger') || (triggers_type == 'box_trigger_3') ){
         trigger_level = $('#trigger_level').val();
      }

      if( (triggers_type == 'market_trend_trigger') ){
         trigger_level = $('#trigger_level').val();
         $('.show_hide_market_trend').show();
      }else{
          $('.show_hide_market_trend').hide();
      }
      

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



      if(triggers_type == 'barrier_percentile_trigger'){
        $('.show_hide_barrier_percentile').show();
        $('.hide_show_for_rg_15').hide();
      }else{
        $('.show_hide_barrier_percentile').hide();
        $('.hide_show_for_rg_15').show();

      }





      if(triggers_type =='barrier_trigger'){
        $('.show_hide_for_barrier_trigger').hide();
        $('.show_barrier_trigger').show();
      }else{
        $('.show_hide_for_barrier_trigger').show();
        $('.show_barrier_trigger').hide();
      }



      $.ajax({
        'url': '<?php echo base_url(); ?>admin/settings/get_global_trigger_setting_ajax',
        'data': {triggers_type:triggers_type,order_mode:order_mode,coin:coin,trigger_level:trigger_level},
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


               if(triggers_type == 'box_trigger_3'){

                    //%%%%%%%%%%%%%%%%%%%%%%% Box trigger Part &&&&&&&&&&&&&&&&&&&&
                    $('.box_trigger_3_hide_show').show();

                    $('#box_trigger_black_wall').val(res_obj['box_trigger_black_wall']);


                    $('#box_trigger_virtual_barrier').val(res_obj['box_trigger_virtual_barrier']);


                    $('#box_trigger_seven_level_pressure').val(res_obj['box_trigger_seven_level_pressure']);

                    $('#box_trigger_buyer_vs_seller_rolling_candel').val(res_obj['box_trigger_buyer_vs_seller_rolling_candel']);

                    $('#last_qty_contracts_buyer_vs_seller_box_trigger').val(res_obj['last_qty_contracts_buyer_vs_seller_box_trigger']);


                    $('#last_qty_contracts_time_box_trigger').val(res_obj['last_qty_contracts_time_box_trigger']);

                    
                    $('#box_trigger_bid_contracts').val(res_obj['box_trigger_bid_contracts']);
                  
                    $('#box_trigger_ask_contracts').val(res_obj['box_trigger_ask_contracts']);

                    $('#box_trigger_sell').val(res_obj['box_trigger_sell']);

                    $('#box_trigger_buy').val(res_obj['box_trigger_buy']);


                     $('#box_trigger_bid').val(res_obj['box_trigger_bid']);
                    

                     $('#box_trigger_ask').val(res_obj['box_trigger_ask']);

                  
                     $('#box_trigger_15_minute_last_time_ago').val(res_obj['box_trigger_15_minute_last_time_ago']);
                    


                    $('#box_trigger_sellers_buy').val(res_obj['box_trigger_sellers_buy']);
                    

                    $('#box_trigger_buyers_buy').val(res_obj['box_trigger_buyers_buy']);
                

                    $('#box_trigger_15_minute_rolling_candel').val(res_obj['box_trigger_15_minute_rolling_candel']);
                  

                     $('#box_trigger_5_minute_rolling_candel').val(res_obj['box_trigger_5_minute_rolling_candel']);


                    $('#last_200_contracts_buy_vs_sell_box_trigger').val(res_obj['last_200_contracts_buy_vs_sell_box_trigger']);

                    $('#last_200_contracts_time_box_trigger').val(res_obj['last_200_contracts_time_box_trigger']);


                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['box_trigger_black_wall_apply'] == 'yes'){
                    $('#box_trigger_black_wall_apply').prop('checked',true);
                    }else{
                    $('#box_trigger_black_wall_apply').prop('checked',false);
                    }

                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['box_trigger_virtual_barrier_apply'] == 'yes'){
                    $('#box_trigger_virtual_barrier_apply').prop('checked',true);
                    }else{
                    $('#box_trigger_virtual_barrier_apply').prop('checked',false);
                    }


                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['box_trigger_seven_level_pressure_apply'] == 'yes'){
                    $('#box_trigger_seven_level_pressure_apply').prop('checked',true);
                    }else{
                    $('#box_trigger_seven_level_pressure_apply').prop('checked',false);
                    }



                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['box_trigger_buyer_vs_seller_rolling_candel_apply'] == 'yes'){
                    $('#box_trigger_buyer_vs_seller_rolling_candel_apply').prop('checked',true);
                    }else{
                    $('#box_trigger_buyer_vs_seller_rolling_candel_apply').prop('checked',false);
                    }


                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['last_200_contracts_buy_vs_sell_box_trigger_apply'] == 'yes'){
                    $('#last_200_contracts_buy_vs_sell_box_trigger_apply').prop('checked',true);
                    }else{
                    $('#last_200_contracts_buy_vs_sell_box_trigger_apply').prop('checked',false);
                    }



                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['last_200_contracts_time_box_trigger_apply'] == 'yes'){
                    $('#last_200_contracts_time_box_trigger_apply').prop('checked',true);
                    }else{
                    $('#last_200_contracts_time_box_trigger_apply').prop('checked',false);
                    }


                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['last_qty_contracts_time_box_trigger_apply'] == 'yes'){
                    $('#last_qty_contracts_time_box_trigger_apply').prop('checked',true);
                    }else{
                    $('#last_qty_contracts_time_box_trigger_apply').prop('checked',false);
                    }
                    

                      

                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['box_trigger_bid_contracts_apply'] == 'yes'){
                      $('#box_trigger_bid_contracts_apply').prop('checked',true);
                    }else{
                      $('#box_trigger_bid_contracts_apply').prop('checked',false);
                    }
                    


                     //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['box_trigger_ask_contracts_apply'] == 'yes'){
                      $('#box_trigger_ask_contracts_apply').prop('checked',true);
                    }else{
                      $('#box_trigger_ask_contracts_apply').prop('checked',false);
                    }


                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['box_trigger_sell_apply'] == 'yes'){
                      $('#box_trigger_sell_apply').prop('checked',true);
                    }else{
                      $('#box_trigger_sell_apply').prop('checked',false);
                    }

                    

                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['box_trigger_buy_apply'] == 'yes'){
                      $('#box_trigger_buy_apply').prop('checked',true);
                    }else{
                      $('#box_trigger_buy_apply').prop('checked',false);
                    }
                    


                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['box_trigger_bid_apply'] == 'yes'){
                      $('#box_trigger_bid_apply').prop('checked',true);
                    }else{
                      $('#box_trigger_bid_apply').prop('checked',false);
                    }

                    
                    

                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['box_trigger_ask_apply'] == 'yes'){
                      $('#box_trigger_ask_apply').prop('checked',true);
                    }else{
                      $('#box_trigger_ask_apply').prop('checked',false);
                    }

                      

                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['box_trigger_15_minute_last_time_ago_apply'] == 'yes'){
                     $('#box_trigger_15_minute_last_time_ago_apply').prop('checked',true);
                    }else{
                     $('#box_trigger_15_minute_last_time_ago_apply').prop('checked',false);
                    }


                    
                    
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['box_trigger_sellers_buy_apply'] == 'yes'){
                      $('#box_trigger_sellers_buy_apply').prop('checked',true);
                    }else{
                      $('#box_trigger_sellers_buy_apply').prop('checked',false);
                    }


                    
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      if(res_obj['box_trigger_buyers_buy_apply'] == 'yes'){
                    $('#box_trigger_buyers_buy_apply').prop('checked',true);
                    }else{
                    $('#box_trigger_buyers_buy_apply').prop('checked',false);
                    }
                    

                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['box_trigger_5_minute_rolling_candel_apply'] == 'yes'){
                      $('#box_trigger_5_minute_rolling_candel_apply').prop('checked',true);
                    }else{
                      $('#box_trigger_5_minute_rolling_candel_apply').prop('checked',false);
                    }
                    

                     //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['box_trigger_15_minute_rolling_candel_apply'] == 'yes'){
                      $('#box_trigger_15_minute_rolling_candel_apply').prop('checked',true);
                    }else{
                      $('#box_trigger_15_minute_rolling_candel_apply').prop('checked',false);
                    }


                    

                    
                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    if(res_obj['last_qty_contracts_buyer_vs_seller_box_trigger_apply'] == 'yes'){
                    $('#last_qty_contracts_buyer_vs_seller_box_trigger_apply').prop('checked',true);
                    }else{
                    $('#last_qty_contracts_buyer_vs_seller_box_trigger_apply').prop('checked',false);
                    }



                    }else{
                    $('.box_trigger_3_hide_show').hide();
                    }
                    //%%%%%%%%%%%% End of Box Trigger Part %%%%%%%%%%%%%%%%%%%



                  /**********Barrier percentile buy part**********/

                  //%%%%%%%%%%%%%%% Enable Disable Barrier percentile trigger

                  if(res_obj['enable_buy_barrier_percentile'] == 'yes'){
                  $('#enable_buy_barrier_percentile').prop('checked',true);
                  }else{
                  $('#enable_buy_barrier_percentile').prop('checked',false);
                  }

                  if(res_obj['enable_sell_barrier_percentile'] == 'yes'){
                  $('#enable_sell_barrier_percentile').prop('checked',true);
                  }else{
                  $('#enable_sell_barrier_percentile').prop('checked',false);
                  }


                  if(res_obj['enable_percentile_trigger_stop_loss'] == 'yes'){
                      $('#enable_percentile_trigger_stop_loss').prop('checked',true);
                  }else{
                      $('#enable_percentile_trigger_stop_loss').prop('checked',false);
                  }

                  

                  if(res_obj['barrier_percentile_trigger_buy_black_wall_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_buy_black_wall_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_buy_black_wall_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_is_previous_blue_candel'] == 'yes'){
                    $('#barrier_percentile_is_previous_blue_candel').prop('checked',true);
                  }else{
                    $('#barrier_percentile_is_previous_blue_candel').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_bottom_demond_rejection'] == 'yes'){
                    $('#barrier_percentile_bottom_demond_rejection').prop('checked',true);
                  }else{
                    $('#barrier_percentile_bottom_demond_rejection').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_bottom_supply_rejection'] == 'yes'){
                    $('#barrier_percentile_bottom_supply_rejection').prop('checked',true);
                  }else{
                    $('#barrier_percentile_bottom_supply_rejection').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_buy_virtual_barrier_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_buy_virtual_barrier_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_buy_virtual_barrier_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_sell_virtual_barrier_for_buy_apply'] == 'yes'){
                      $('#barrier_percentile_trigger_sell_virtual_barrier_for_buy_apply').prop('checked',true);
                  }else{
                     $('#barrier_percentile_trigger_sell_virtual_barrier_for_buy_apply').prop('checked',false);
                  }
                  

                   if(res_obj['barrier_percentile_trigger_stop_loss_virtual_barrier_bid_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_stop_loss_virtual_barrier_bid_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_stop_loss_virtual_barrier_bid_apply').prop('checked',false);
                  }

                  

                  if(res_obj['barrier_percentile_trigger_buy_seven_level_pressure_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_buy_seven_level_pressure_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_buy_seven_level_pressure_apply').prop('checked',false);
                  }




                  if(res_obj['barrier_percentile_trigger_buy_last_200_contracts_buy_vs_sell_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_buy_last_200_contracts_buy_vs_sell_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_buy_last_200_contracts_buy_vs_sell_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_buy_last_200_contracts_time_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_buy_last_200_contracts_time_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_buy_last_200_contracts_time_apply').prop('checked',false);
                  }



                  if(res_obj['barrier_percentile_trigger_buy_last_qty_contracts_buyer_vs_seller_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_buy_last_qty_contracts_buyer_vs_seller_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_buy_last_qty_contracts_buyer_vs_seller_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_buy_last_qty_contracts_time_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_buy_last_qty_contracts_time_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_buy_last_qty_contracts_time_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_5_minute_rolling_candel_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_5_minute_rolling_candel_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_5_minute_rolling_candel_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_15_minute_rolling_candel_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_15_minute_rolling_candel_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_15_minute_rolling_candel_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_buyers_buy_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_buyers_buy_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_buyers_buy_apply').prop('checked',false);
                  }


                  
                  if(res_obj['barrier_percentile_trigger_sellers_buy_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_sellers_buy_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_sellers_buy_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_15_minute_last_time_ago_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_15_minute_last_time_ago_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_15_minute_last_time_ago_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_15_minute_last_time_ago_sell_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_15_minute_last_time_ago_sell_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_15_minute_last_time_ago_sell_apply').prop('checked',false);
                  }
                  
                  


                  if(res_obj['barrier_percentile_trigger_sell_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_sell_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_sell_apply').prop('checked',false);
                  }

                  if(res_obj['barrier_percentile_trigger_sell_rule_sell_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_sell_rule_sell_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_sell_rule_sell_apply').prop('checked',false);
                  }

                  

                  if(res_obj['barrier_percentile_trigger_bid_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_bid_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_bid_apply').prop('checked',false);
                  }


                   if(res_obj['barrier_percentile_trigger_bid_sell_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_bid_sell_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_bid_sell_apply').prop('checked',false);
                  }
                  

                  if(res_obj['barrier_percentile_trigger_buy_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_buy_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_buy_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_buy_sell_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_buy_sell_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_buy_sell_apply').prop('checked',false);
                  }
                  
                  
                  if(res_obj['barrier_percentile_trigger_ask_sell_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_ask_sell_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_ask_sell_apply').prop('checked',false);
                  }


                   if(res_obj['barrier_percentile_trigger_ask_contracts_sell_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_ask_contracts_sell_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_ask_contracts_sell_apply').prop('checked',false);
                  }



                   if(res_obj['barrier_percentile_trigger_bid_contracts_sell_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_bid_contracts_sell_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_bid_contracts_sell_apply').prop('checked',false);
                  }
                  
                  

                  
                  if(res_obj['barrier_percentile_trigger_ask_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_ask_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_ask_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_ask_contracts_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_ask_contracts_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_ask_contracts_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_bid_contracts_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_bid_contracts_apply').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_bid_contracts_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_ask_contracts'] == 'yes'){
                    $('#barrier_percentile_trigger_ask_contracts').prop('checked',true);
                  }else{
                      $('#barrier_percentile_trigger_ask_contracts').prop('checked',false);
                  }

                  

                
                  if(res_obj['box_trigger_15_minute_rolling_candel_apply'] == 'yes'){
                    $('#box_trigger_15_minute_rolling_candel_apply').prop('checked',true);
                  }else{
                    $('#box_trigger_15_minute_rolling_candel_apply').prop('checked',false);
                  }
                  

                  //%%%%%%%%%%%%%%%% market trend in  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  
                  if(res_obj['percentile_trigger_caption_option_buy_apply'] == 'yes'){
                    $('#percentile_trigger_caption_option_buy_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_caption_option_buy_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_caption_option_buy').val(res_obj.percentile_trigger_caption_option_buy);
                  //%%%%%%%%%%%%%%%%%%% End of market trend in percentiel %%%%%%%%%%%%%%%%

                  //%%%%%%%%%%%%%%%% market trend in  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  
                  if(res_obj['percentile_trigger_caption_score_buy_apply'] == 'yes'){
                    $('#percentile_trigger_caption_score_buy_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_caption_score_buy_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_caption_score_buy').val(res_obj.percentile_trigger_caption_score_buy);
                  //%%%%%%%%%%%%%%%%%%% End of market trend in percentiel %%%%%%%%%%%%%%%%


                  //%%%%%%%%%%%%%%%% market trend in  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_buy_trend_buy_apply'] == 'yes'){
                    $('#percentile_trigger_buy_trend_buy_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_buy_trend_buy_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_buy_trend_buy').val(res_obj.percentile_trigger_buy_trend_buy);
                  //%%%%%%%%%%%%%%%%%%% End of market trend in percentiel %%%%%%%%%%%%%%%%




                  //%%%%%%%%%%%%%%%% market trend in  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_sell_buy_apply'] == 'yes'){
                    $('#percentile_trigger_sell_buy_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_sell_buy_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_sell_buy').val(res_obj.percentile_trigger_sell_buy);
                  //%%%%%%%%%%%%%%%%%%% End of market trend in percentiel %%%%%%%%%%%%%%%%




                   //%%%%%%%%%%%%%%%% market trend in  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                   if(res_obj['percentile_trigger_demand_buy_apply'] == 'yes'){
                    $('#percentile_trigger_demand_buy_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_demand_buy_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_demand_buy').val(res_obj.percentile_trigger_demand_buy);
                  //%%%%%%%%%%%%%%%%%%% End of market trend in percentiel %%%%%%%%%%%%%%%%
                  


                  //%%%%%%%%%%%%%%%% market trend in  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_supply_buy_apply'] == 'yes'){
                    $('#percentile_trigger_supply_buy_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_supply_buy_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_supply_buy').val(res_obj.percentile_trigger_supply_buy);
                  //%%%%%%%%%%%%%%%%%%% End of market trend in percentiel %%%%%%%%%%%%%%%%



                  //%%%%%%%%%%%%%%%% market trend in  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_market_trend_buy_apply'] == 'yes'){
                    $('#percentile_trigger_market_trend_buy_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_market_trend_buy_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_market_trend_buy').val(res_obj.percentile_trigger_market_trend_buy);
                  //%%%%%%%%%%%%%%%%%%% End of market trend in percentiel %%%%%%%%%%%%%%%%



                  //%%%%%%%%%%%%%%%% market trend in  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_meta_trading_buy_apply'] == 'yes'){
                    $('#percentile_trigger_meta_trading_buy_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_meta_trading_buy_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_meta_trading_buy').val(res_obj.percentile_trigger_meta_trading_buy);
                  //%%%%%%%%%%%%%%%%%%% End of market trend in percentiel %%%%%%%%%%%%%%%%


                   //%%%%%%%%%%%%%%%% market trend in  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                   if(res_obj['percentile_trigger_riskpershare_buy_apply'] == 'yes'){
                    $('#percentile_trigger_riskpershare_buy_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_riskpershare_buy_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_riskpershare_buy').val(res_obj.percentile_trigger_riskpershare_buy);
                  //%%%%%%%%%%%%%%%%%%% End of market trend in percentiel %%%%%%%%%%%%%%%
                  
                  
                  
                   //%%%%%%%%%%%%%%%% market trend in  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                   if(res_obj['percentile_trigger_RL_buy_apply'] == 'yes'){
                    $('#percentile_trigger_RL_buy_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_RL_buy_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_RL_buy').val(res_obj.percentile_trigger_RL_buy);
                  //%%%%%%%%%%%%%%%%%%% End of market trend in percentiel %%%%%%%%%%%%%%%%



                  //%%%%%%%%%%%%%%%% market trend in  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_long_term_intension_buy_apply'] == 'yes'){
                    $('#percentile_trigger_long_term_intension_buy_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_long_term_intension_buy_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_long_term_intension_buy').val(res_obj.percentile_trigger_long_term_intension_buy);
                  //%%%%%%%%%%%%%%%%%%% End of market trend in percentiel %%%%%%%%%%%%%%%%



                  



                  //%%%%%%%%%%%%%%%% market trend in sell part  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_caption_option_sell_apply'] == 'yes'){
                    $('#percentile_trigger_caption_option_sell_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_caption_option_sell_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_caption_option_sell').val(res_obj.percentile_trigger_caption_option_sell);
                  //%%%%%%%%%%%%%%%%%%% End of market trend sell par in percentiel %%%%%%%%%%%%%%%%

                  //%%%%%%%%%%%%%%%% market trend in sell part  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_caption_score_sell_apply'] == 'yes'){
                    $('#percentile_trigger_caption_score_sell_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_caption_score_sell_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_caption_score_sell').val(res_obj.percentile_trigger_caption_score_sell);
                  //%%%%%%%%%%%%%%%%%%% End of market trend sell par in percentiel %%%%%%%%%%%%%%%%


                  //%%%%%%%%%%%%%%%% market trend in sell part  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_buy_operator_sell_apply'] == 'yes'){
                    $('#percentile_trigger_buy_operator_sell_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_buy_operator_sell_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_buy_sell').val(res_obj.percentile_trigger_buy_sell);
                  //%%%%%%%%%%%%%%%%%%% End of market trend sell par in percentiel %%%%%%%%%%%%%%%%


                  //%%%%%%%%%%%%%%%% market trend in sell part  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_sell_trend_sell_apply'] == 'yes'){
                    $('#percentile_trigger_sell_trend_sell_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_sell_trend_sell_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_sell_trend_sell').val(res_obj.percentile_trigger_sell_trend_sell);
                  //%%%%%%%%%%%%%%%%%%% End of market trend sell par in percentiel %%%%%%%%%%%%%%%%


                  //%%%%%%%%%%%%%%%% market trend in sell part  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_demand_sell_apply'] == 'yes'){
                    $('#percentile_trigger_demand_sell_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_demand_sell_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_demand_sell').val(res_obj.percentile_trigger_demand_sell);
                  //%%%%%%%%%%%%%%%%%%% End of market trend sell par in percentiel %%%%%%%%%%%%%%%%




                  //%%%%%%%%%%%%%%%% market trend in sell part  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_supply_sell_apply'] == 'yes'){
                    $('#percentile_trigger_supply_sell_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_supply_sell_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_supply_sell').val(res_obj.percentile_trigger_supply_sell);
                  //%%%%%%%%%%%%%%%%%%% End of market trend sell par in percentiel %%%%%%%%%%%%%%%%


                  //%%%%%%%%%%%%%%%% market trend in sell part  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_market_trend_operator_sell_apply'] == 'yes'){
                    $('#percentile_trigger_market_trend_operator_sell_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_market_trend_operator_sell_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_market_trend_sell').val(res_obj.percentile_trigger_market_trend_sell);
                  //%%%%%%%%%%%%%%%%%%% End of market trend sell par in percentiel %%%%%%%%%%%%%%%%



                  //%%%%%%%%%%%%%%%% market trend in sell part  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_meta_trading_sell_apply'] == 'yes'){
                    $('#percentile_trigger_meta_trading_sell_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_meta_trading_sell_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_meta_trading_sell').val(res_obj.percentile_trigger_meta_trading_sell);
                  //%%%%%%%%%%%%%%%%%%% End of market trend sell par in percentiel %%%%%%%%%%%%%%%%



                   //%%%%%%%%%%%%%%%% market trend in sell part  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                   if(res_obj['percentile_trigger_riskpershare_sell_apply'] == 'yes'){
                    $('#percentile_trigger_riskpershare_sell_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_riskpershare_sell_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_riskpershare_sell').val(res_obj.percentile_trigger_riskpershare_sell);
                  //%%%%%%%%%%%%%%%%%%% End of market trend sell par in percentiel %%%%%%%%%%%%%%%%



                  //%%%%%%%%%%%%%%%% market trend in sell part  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_RL_sell_apply'] == 'yes'){
                    $('#percentile_trigger_RL_sell_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_RL_sell_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_RL_sell').val(res_obj.percentile_trigger_RL_sell);
                  //%%%%%%%%%%%%%%%%%%% End of market trend sell par in percentiel %%%%%%%%%%%%%%%%



                  //%%%%%%%%%%%%%%%% market trend in sell part  percentile  %%%%%%%%%%%%%%%%%%%%%%%
                  if(res_obj['percentile_trigger_long_term_intension_sell_apply'] == 'yes'){
                    $('#percentile_trigger_long_term_intension_sell_apply').prop('checked',true);
                  }else{
                    $('#percentile_trigger_long_term_intension_sell_apply').prop('checked',false);
                  }

                  $('#percentile_trigger_long_term_intension_sell').val(res_obj.percentile_trigger_long_term_intension_sell);
                  //%%%%%%%%%%%%%%%%%%% End of market trend sell par in percentiel %%%%%%%%%%%%%%%%
                  
                  
                  














                  $('#barrier_percentile_trigger_buy_black_wall').val(res_obj.barrier_percentile_trigger_buy_black_wall);

                  $('#barrier_percentile_trigger_buy_virtual_barrier').val(res_obj.barrier_percentile_trigger_buy_virtual_barrier);


                  $('#barrier_percentile_trigger_sell_virtual_barrier_for_buy').val(res_obj.barrier_percentile_trigger_sell_virtual_barrier_for_buy);

                  $('#barrier_percentile_trigger_buy_seven_level_pressure').val(res_obj.barrier_percentile_trigger_buy_seven_level_pressure);


                  $('#barrier_percentile_trigger_5_minute_rolling_candel').val(res_obj.barrier_percentile_trigger_5_minute_rolling_candel);

                  $('#barrier_percentile_trigger_15_minute_rolling_candel').val(res_obj.barrier_percentile_trigger_15_minute_rolling_candel);


                  $('#barrier_percentile_trigger_buyers_buy').val(res_obj.barrier_percentile_trigger_buyers_buy);


                    $('#percentile_trigger_last_candle_type').val(res_obj.percentile_trigger_last_candle_type);



                  $('#barrier_percentile_trigger_sellers_buy').val(res_obj.barrier_percentile_trigger_sellers_buy);

                  $('#barrier_percentile_trigger_15_minute_last_time_ago').val(res_obj.barrier_percentile_trigger_15_minute_last_time_ago);



                  $('#barrier_percentile_trigger_15_minute_last_time_ago_sell').val(res_obj.barrier_percentile_trigger_15_minute_last_time_ago_sell);

                    


                  $('#barrier_percentile_trigger_sell').val(res_obj.barrier_percentile_trigger_sell);
                  $('#barrier_percentile_trigger_bid').val(res_obj.barrier_percentile_trigger_bid);


                  $('#barrier_percentile_trigger_bid_sell').val(res_obj.barrier_percentile_trigger_bid_sell);
                  
                  

                  
                  $('#barrier_percentile_trigger_sell_rule_sell').val(res_obj.barrier_percentile_trigger_sell_rule_sell);

                  

                  $('#barrier_percentile_trigger_buy').val(res_obj.barrier_percentile_trigger_buy);
                  $('#barrier_percentile_trigger_ask').val(res_obj.barrier_percentile_trigger_ask);


                  $('#barrier_percentile_trigger_buy_sell').val(res_obj.barrier_percentile_trigger_buy_sell);
                  


                  $('#barrier_percentile_trigger_ask_sell').val(res_obj.barrier_percentile_trigger_ask_sell);
                  


                  $('#barrier_percentile_trigger_ask_contracts_sell').val(res_obj.barrier_percentile_trigger_ask_contracts_sell);
                  
                  $('#barrier_percentile_trigger_bid_contracts_sell').val(res_obj.barrier_percentile_trigger_bid_contracts_sell);

                    
                    


                  $('#barrier_percentile_trigger_ask_contracts').val(res_obj.barrier_percentile_trigger_ask_contracts);
                  
                  $('#barrier_percentile_trigger_bid_contracts').val(res_obj.barrier_percentile_trigger_bid_contracts)
                  
                  

                  $('#box_trigger_15_minute_rolling_candel').val(res_obj.box_trigger_15_minute_rolling_candel);



                  $('#barrier_percentile_trigger_buy_last_200_contracts_buy_vs_sell').val(res_obj.barrier_percentile_trigger_buy_last_200_contracts_buy_vs_sell);

                  $('#barrier_percentile_trigger_default_stop_loss_percenage').val(res_obj.barrier_percentile_trigger_default_stop_loss_percenage);


                  $('#barrier_percentile_trigger_barrier_range_percentage').val(res_obj.barrier_percentile_trigger_barrier_range_percentage);


                    $('#barrier_percentile_trigger_barrier_range_percentage_sell').val(res_obj.barrier_percentile_trigger_barrier_range_percentage_sell);

                  

                  $('#barrier_percentile_trigger_buy_last_200_contracts_time').val(res_obj.barrier_percentile_trigger_buy_last_200_contracts_time);

                  $('#barrier_percentile_trigger_buy_last_qty_contracts_buyer_vs_seller').val(res_obj.barrier_percentile_trigger_buy_last_qty_contracts_buyer_vs_seller);



                  $('#barrier_percentile_trigger_buy_last_qty_contracts_time').val(res_obj.barrier_percentile_trigger_buy_last_qty_contracts_time);

                  //%%%%%%%%%%%%%%%%%%% End of barrier percentile buy part


                  //%%%%%%%%%%%%%%%%%%% start of sell percentile sell part %%%%%%%%%%%%%%%
                  if(res_obj['barrier_percentile_trigger_sell_black_wall_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_sell_black_wall_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_sell_black_wall_apply').prop('checked',false);
                  }


                  //%%%%%%%%%%%%%%%%%%% start of sell percentile sell part %%%%%%%%%%%%%%%
                  if(res_obj['barrier_percentile_stop_loss_black_wall_apply'] == 'yes'){
                    $('#barrier_percentile_stop_loss_black_wall_apply').prop('checked',true);
                  }else{
                    $('#barrier_percentile_stop_loss_black_wall_apply').prop('checked',false);
                  }  

                    

                  if(res_obj['barrier_percentile_trigger_buy_virtual_barrier_for_sell_apply'] == 'yes'){
                      $('#barrier_percentile_trigger_buy_virtual_barrier_for_sell_apply').prop('checked',true);
                  }else{
                    $('#barrier_percentile_trigger_buy_virtual_barrier_for_sell_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_sell_virtual_barrier_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_sell_virtual_barrier_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_sell_virtual_barrier_apply').prop('checked',false);
                  }


                   if(res_obj['barrier_percentile_trigger_stop_loss_virtual_barrier_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_stop_loss_virtual_barrier_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_stop_loss_virtual_barrier_apply').prop('checked',false);
                  }

                  

                  if(res_obj['barrier_percentile_trigger_sell_seven_level_pressure_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_sell_seven_level_pressure_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_sell_seven_level_pressure_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_stop_loss_seven_level_pressure_apply'] == 'yes'){
                     $('#barrier_percentile_trigger_stop_loss_seven_level_pressure_apply').prop('checked',true);
                  }else{
                    $('#barrier_percentile_trigger_stop_loss_seven_level_pressure_apply').prop('checked',false);
                  }

                  


                  if(res_obj['barrier_percentile_trigger_sell_last_200_contracts_buy_vs_sell_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_sell_last_200_contracts_buy_vs_sell_apply').prop('checked',true);
                  }else{
                     $('#barrier_percentile_trigger_sell_last_200_contracts_buy_vs_sell_apply').prop('checked',false);
                  }



                  if(res_obj['barrier_percentile_trigger_stop_loss_last_200_contracts_buy_vs_sell_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_stop_loss_last_200_contracts_buy_vs_sell_apply').prop('checked',true);
                  }else{
                     $('#barrier_percentile_trigger_stop_loss_last_200_contracts_buy_vs_sell_apply').prop('checked',false);
                  }
                  
                  


                  

                  if(res_obj['barrier_percentile_trigger_sell_last_200_contracts_time_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_sell_last_200_contracts_time_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_sell_last_200_contracts_time_apply').prop('checked',false);
                  }



                  if(res_obj['barrier_percentile_trigger_stop_loss_last_200_contracts_time_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_stop_loss_last_200_contracts_time_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_stop_loss_last_200_contracts_time_apply').prop('checked',false);
                  }
  

                    


                  if(res_obj['barrier_percentile_trigger_sell_last_qty_contracts_buyer_vs_seller_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_sell_last_qty_contracts_buyer_vs_seller_apply').prop('checked',true);
                  }else{
                    $('#barrier_percentile_trigger_sell_last_qty_contracts_buyer_vs_seller_apply').prop('checked',false);
                  } 



                  if(res_obj['barrier_percentile_trigger_stop_loss_last_qty_contracts_buyer_vs_seller_apply'] == 'yes'){
                    $('#barrier_percentile_trigger_stop_loss_last_qty_contracts_buyer_vs_seller_apply').prop('checked',true);
                  }else{
                    $('#barrier_percentile_trigger_stop_loss_last_qty_contracts_buyer_vs_seller_apply').prop('checked',false);
                  }
                  

                  

                  if(res_obj['barrier_percentile_trigger_sell_last_qty_contracts_time_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_sell_last_qty_contracts_time_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_sell_last_qty_contracts_time_apply').prop('checked',false);
                  }


                  if(res_obj['barrier_percentile_trigger_stop_loss_last_qty_contracts_time_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_stop_loss_last_qty_contracts_time_apply').prop('checked',true);
                  }else{
                  $('#barrier_percentile_trigger_stop_loss_last_qty_contracts_time_apply').prop('checked',false);
                  }
                  

                  

                if(res_obj['barrier_percentile_trigger_5_minute_rolling_candel_sell_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_5_minute_rolling_candel_sell_apply').prop('checked',true);
                }else{
                  $('#barrier_percentile_trigger_5_minute_rolling_candel_sell_apply').prop('checked',false);
                }


                if(res_obj['barrier_percentile_stop_loss_5_minute_rolling_candel_sell_apply'] == 'yes'){
                  $('#barrier_percentile_stop_loss_5_minute_rolling_candel_sell_apply').prop('checked',true);
                }else{
                  $('#barrier_percentile_stop_loss_5_minute_rolling_candel_sell_apply').prop('checked',false);
                }


                
                if(res_obj['barrier_percentile_trigger_15_minute_rolling_candel_sell_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_15_minute_rolling_candel_sell_apply').prop('checked',true);
                }else{
                  $('#barrier_percentile_trigger_15_minute_rolling_candel_sell_apply').prop('checked',false);
                }


                if(res_obj['barrier_percentile_stop_loss_15_minute_rolling_candel_sell_apply'] == 'yes'){
                  $('#barrier_percentile_stop_loss_15_minute_rolling_candel_sell_apply').prop('checked',true);
                }else{
                  $('#barrier_percentile_stop_loss_15_minute_rolling_candel_sell_apply').prop('checked',false);
                }


                  


                if(res_obj['barrier_percentile_trigger_buyers_sell_apply'] == 'yes'){
                $('#barrier_percentile_trigger_buyers_sell_apply').prop('checked',true);
                }else{
                $('#barrier_percentile_trigger_buyers_sell_apply').prop('checked',false);
                } 


                if(res_obj['barrier_percentile_trigger_buyers_stop_loss_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_buyers_stop_loss_apply').prop('checked',true);
                }else{
                  $('#barrier_percentile_trigger_buyers_stop_loss_apply').prop('checked',false);
                }

                  


                if(res_obj['barrier_percentile_trigger_sellers_sell_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_sellers_sell_apply').prop('checked',true);
                }else{
                  $('#barrier_percentile_trigger_sellers_sell_apply').prop('checked',false);
                }


                if(res_obj['barrier_percentile_trigger_sellers_stop_loss_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_sellers_stop_loss_apply').prop('checked',true);
                }else{
                  $('#barrier_percentile_trigger_sellers_stop_loss_apply').prop('checked',false);
                }  


                if(res_obj['barrier_percentile_trigger_buyers1_minute_stop_loss_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_buyers1_minute_stop_loss_apply').prop('checked',true);
                }else{
                  $('#barrier_percentile_trigger_buyers1_minute_stop_loss_apply').prop('checked',false);
                }  



                if(res_obj['barrier_percentile_trigger_sellers_1_minute_stop_loss_apply'] == 'yes'){
                  $('#barrier_percentile_trigger_sellers_1_minute_stop_loss_apply').prop('checked',true);
                }else{
                  $('#barrier_percentile_trigger_sellers_1_minute_stop_loss_apply').prop('checked',false);
                }  

                  $('#barrier_percentile_trigger_sell_black_wall').val(res_obj.barrier_percentile_trigger_sell_black_wall);


                  $('#barrier_percentile_trigger_buy_virtual_barrier_for_sell').val(res_obj.barrier_percentile_trigger_buy_virtual_barrier_for_sell);

                  $('#barrier_percentile_stop_loss_black_wall').val(res_obj.barrier_percentile_stop_loss_black_wall);                    

                  


                  $('#barrier_percentile_trigger_sell_virtual_barrier').val(res_obj.barrier_percentile_trigger_sell_virtual_barrier);



                  $('#barrier_percentile_trigger_stop_loss_virtual_barrier').val(res_obj.barrier_percentile_trigger_stop_loss_virtual_barrier);  

                    


                  $('#barrier_percentile_trigger_sell_seven_level_pressure').val(res_obj.barrier_percentile_trigger_sell_seven_level_pressure);


                  $('#barrier_percentile_trigger_stop_loss_seven_level_pressure').val(res_obj.barrier_percentile_trigger_stop_loss_seven_level_pressure);

                  


                  $('#barrier_percentile_trigger_15_minute_rolling_candel_sell').val(res_obj.barrier_percentile_trigger_15_minute_rolling_candel_sell);


                  $('#barrier_percentile_stop_loss_15_minute_rolling_candel_sell').val(res_obj.barrier_percentile_stop_loss_15_minute_rolling_candel_sell);                  


                  

                  $('#barrier_percentile_trigger_buyers_sell').val(res_obj.barrier_percentile_trigger_buyers_sell);
                  

                  $('#barrier_percentile_trigger_buyers_stop_loss').val(res_obj.barrier_percentile_trigger_buyers_stop_loss);

                  


                  $('#barrier_percentile_trigger_sellers_sell').val(res_obj.barrier_percentile_trigger_sellers_sell);


                  $('#barrier_percentile_trigger_sellers_stop_loss').val(res_obj.barrier_percentile_trigger_sellers_stop_loss);


                   $('#barrier_percentile_trigger_buyers1_minute_stop_loss').val(res_obj.barrier_percentile_trigger_buyers1_minute_stop_loss);

                    $('#barrier_percentile_trigger_sellers_1_minute_stop_loss').val(res_obj.barrier_percentile_trigger_sellers_1_minute_stop_loss);
                  

                  $('#barrier_percentile_trigger_5_minute_rolling_candel_sell').val(res_obj.barrier_percentile_trigger_5_minute_rolling_candel_sell);


                  $('#barrier_percentile_stop_loss_5_minute_rolling_candel_sell').val(res_obj.barrier_percentile_stop_loss_5_minute_rolling_candel_sell);

                  


                  $('#barrier_percentile_trigger_sell_last_200_contracts_buy_vs_sell').val(res_obj.barrier_percentile_trigger_sell_last_200_contracts_buy_vs_sell);


                
                  $('#barrier_percentile_trigger_stop_loss_last_200_contracts_buy_vs_sell').val(res_obj.barrier_percentile_trigger_stop_loss_last_200_contracts_buy_vs_sell);


                   $('#barrier_percentile_trigger_default_profit_percenage').val(res_obj.barrier_percentile_trigger_default_profit_percenage);


                  $('#barrier_percentile_trigger_trailing_difference_between_stoploss_and_current_market_percentage').val(res_obj.barrier_percentile_trigger_trailing_difference_between_stoploss_and_current_market_percentage);


                  $('#barrier_percentile_trigger_sell_last_200_contracts_time').val(res_obj.barrier_percentile_trigger_sell_last_200_contracts_time);


                  $('#barrier_percentile_trigger_stop_loss_last_200_contracts_time').val(res_obj.barrier_percentile_trigger_stop_loss_last_200_contracts_time);


                  

                  $('#barrier_percentile_trigger_sell_last_qty_contracts_buyer_vs_seller').val(res_obj.barrier_percentile_trigger_sell_last_qty_contracts_buyer_vs_seller);
                  

                  $('#barrier_percentile_trigger_stop_loss_last_qty_contracts_buyer_vs_seller').val(res_obj.barrier_percentile_trigger_stop_loss_last_qty_contracts_buyer_vs_seller);

                  

                  $('#barrier_percentile_trigger_sell_last_qty_contracts_time').val(res_obj.barrier_percentile_trigger_sell_last_qty_contracts_time);
                  

                  $('#barrier_percentile_trigger_stop_loss_last_qty_contracts_time').val(res_obj.barrier_percentile_trigger_stop_loss_last_qty_contracts_time);

                  
                  //%%%%%%%%%%%%%% End of barrier percentile trigger sell part %%%%%%%%%%%



                  //%%%%%%%%%%%%%%%%%%%%%%%%% Market  Trending %%%%%%%%%%%%%%%%%%%%

                      if(res_obj['market_trend_caption_option_buy_apply'] == 'yes'){
                        $('#market_trend_caption_option_buy_apply').prop('checked',true);
                        }else{
                        $('#market_trend_caption_option_buy_apply').prop('checked',false);
                      }  


                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      
                      if(res_obj['enable_buy_market_trends_trigger'] == 'yes'){
                        $('#enable_buy_market_trends_trigger').prop('checked',true);
                        }else{
                        $('#enable_buy_market_trends_trigger').prop('checked',false);
                      }


                      if(res_obj['enable_sell_market_trends_trigger'] == 'yes'){
                        $('#enable_sell_market_trends_trigger').prop('checked',true);
                        }else{
                        $('#enable_sell_market_trends_trigger').prop('checked',false);
                      }
                      
                      

                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                      $('#market_trend_caption_option_operator_buy').val(res_obj.market_trend_caption_option_operator_buy);


                      $('#market_trend_caption_option_buy').val(res_obj.market_trend_caption_option_buy);



                      if(res_obj['market_trend_caption_option_sell_apply'] == 'yes'){
                        $('#market_trend_caption_option_sell_apply').prop('checked',true);
                        }else{
                        $('#market_trend_caption_option_sell_apply').prop('checked',false);
                      }  

                      $('#market_trend_caption_option_operator_sell').val(res_obj.market_trend_caption_option_operator_sell);


                      $('#market_trend_caption_option_sell').val(res_obj.market_trend_caption_option_sell);





                      if(res_obj['market_trend_caption_score_buy_apply'] == 'yes'){
                        $('#market_trend_caption_score_buy_apply').prop('checked',true);
                        }else{
                        $('#market_trend_caption_score_buy_apply').prop('checked',false);
                      }  

                      $('#market_trend_caption_score_operator_buy').val(res_obj.market_trend_caption_score_operator_buy);


                      $('#market_trend_caption_score_buy').val(res_obj.market_trend_caption_score_buy);




                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                      if(res_obj['market_trend_caption_score_sell_apply'] == 'yes'){
                        $('#market_trend_caption_score_sell_apply').prop('checked',true);
                        }else{
                        $('#market_trend_caption_score_sell_apply').prop('checked',false);
                      }  

                      $('#market_trend_caption_score_operator_sell').val(res_obj.market_trend_caption_score_operator_sell);


                      $('#market_trend_caption_score_sell').val(res_obj.market_trend_caption_score_sell);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%




                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                      if(res_obj['market_trend_buy_trend_buy_apply'] == 'yes'){
                        $('#market_trend_buy_trend_buy_apply').prop('checked',true);
                        }else{
                        $('#market_trend_buy_trend_buy_apply').prop('checked',false);
                      }  

                      $('#market_trend_buy_trend_operator_buy').val(res_obj.market_trend_buy_trend_operator_buy);


                      $('#market_trend_buy_trend_buy').val(res_obj.market_trend_buy_trend_buy);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      



                       //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                      if(res_obj['market_trend_buy_operator_sell_apply'] == 'yes'){
                        $('#market_trend_buy_operator_sell_apply').prop('checked',true);
                        }else{
                        $('#market_trend_buy_operator_sell_apply').prop('checked',false);
                      }  

                      $('#market_trend_buy_operator_sell').val(res_obj.market_trend_buy_operator_sell);


                      $('#market_trend_buy_sell').val(res_obj.market_trend_buy_sell);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      



                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                      if(res_obj['market_trend_sell_buy_apply'] == 'yes'){
                        $('#market_trend_sell_buy_apply').prop('checked',true);
                        }else{
                        $('#market_trend_sell_buy_apply').prop('checked',false);
                      }  

                      $('#market_trend_sell_operator_buy').val(res_obj.market_trend_sell_operator_buy);


                      $('#market_trend_sell_buy').val(res_obj.market_trend_sell_buy);

                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                      if(res_obj['market_trend_sell_trend_sell_apply'] == 'yes'){
                        $('#market_trend_sell_trend_sell_apply').prop('checked',true);
                        }else{
                        $('#market_trend_sell_trend_sell_apply').prop('checked',false);
                      }  

                      $('#market_trend_sell_trend_operator_sell').val(res_obj.market_trend_sell_trend_operator_sell);
                      

                     

                      $('#market_trend_sell_trend_sell').val(res_obj.market_trend_sell_trend_sell);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%




                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                      if(res_obj['market_trend_demand_buy_apply'] == 'yes'){
                        $('#market_trend_demand_buy_apply').prop('checked',true);
                        }else{
                        $('#market_trend_demand_buy_apply').prop('checked',false);
                      }  

                      $('#market_trend_demand_operator_buy').val(res_obj.market_trend_demand_operator_buy);


                      $('#market_trend_demand_buy').val(res_obj.market_trend_demand_buy);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%




                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      if(res_obj['market_trend_demand_sell_apply'] == 'yes'){
                        $('#market_trend_demand_sell_apply').prop('checked',true);
                        }else{
                        $('#market_trend_demand_sell_apply').prop('checked',false);
                      }  

                      $('#market_trend_demand_operator_sell').val(res_obj.market_trend_demand_operator_sell);


                      $('#market_trend_demand_sell').val(res_obj.market_trend_demand_sell);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      


                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      if(res_obj['market_trend_supply_buy_apply'] == 'yes'){
                        $('#market_trend_supply_buy_apply').prop('checked',true);
                        }else{
                        $('#market_trend_supply_buy_apply').prop('checked',false);
                      }  

                      $('#market_trend_supply_operator_buy').val(res_obj.market_trend_supply_operator_buy);


                      $('#market_trend_supply_buy').val(res_obj.market_trend_supply_buy);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%



                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      if(res_obj['market_trend_supply_sell_apply'] == 'yes'){
                        $('#market_trend_supply_sell_apply').prop('checked',true);
                        }else{
                        $('#market_trend_supply_sell_apply').prop('checked',false);
                      } 
                      $('#market_trend_supply_operator_sell').val(res_obj.market_trend_supply_operator_sell);
                      $('#market_trend_market_trend_sell').val(res_obj.market_trend_market_trend_sell);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%



                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        if(res_obj['market_trend_market_trend_operator_sell_apply'] == 'yes'){
                          $('#market_trend_market_trend_operator_sell_apply').prop('checked',true);
                          }else{
                          $('#market_trend_market_trend_operator_sell_apply').prop('checked',false);
                        }  


                        $('#market_trend_market_trend_operator_sell').val(res_obj.market_trend_market_trend_operator_sell);

                        $('#market_trend_market_trend_sell').val(res_obj.market_trend_market_trend_sell);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%



                      


                       //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                       if(res_obj['market_trend_meta_trading_buy_apply'] == 'yes'){
                        $('#market_trend_meta_trading_buy_apply').prop('checked',true);
                        }else{
                        $('#market_trend_meta_trading_buy_apply').prop('checked',false);
                      }  

                      $('#market_trend_meta_trading_operator_buy').val(res_obj.market_trend_meta_trading_operator_buy);


                      $('#market_trend_meta_trading_buy').val(res_obj.market_trend_meta_trading_buy);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%




                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      if(res_obj['market_trend_meta_trading_sell_apply'] == 'yes'){
                        $('#market_trend_meta_trading_sell_apply').prop('checked',true);
                        }else{
                        $('#market_trend_meta_trading_sell_apply').prop('checked',false);
                      }  

                      $('#market_trend_meta_trading_operator_sell').val(res_obj.market_trend_meta_trading_operator_sell);


                      $('#market_trend_meta_trading_sell').val(res_obj.market_trend_meta_trading_sell);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%




                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      if(res_obj['market_trend_riskpershare_buy_apply'] == 'yes'){
                        $('#market_trend_riskpershare_buy_apply').prop('checked',true);
                        }else{
                        $('#market_trend_riskpershare_buy_apply').prop('checked',false);
                      }  

                      $('#market_trend_riskpershare_operator_buy').val(res_obj.market_trend_riskpershare_operator_buy);


                      $('#market_trend_riskpershare_buy').val(res_obj.market_trend_riskpershare_buy);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


                      

                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      if(res_obj['market_trend_riskpershare_sell_apply'] == 'yes'){
                        $('#market_trend_riskpershare_sell_apply').prop('checked',true);
                        }else{
                        $('#market_trend_riskpershare_sell_apply').prop('checked',false);
                      }  

                      $('#market_trend_riskpershare_operator_sell').val(res_obj.market_trend_riskpershare_operator_sell);


                      $('#market_trend_riskpershare_sell').val(res_obj.market_trend_riskpershare_sell);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      if(res_obj['market_trend_black_wall_buy_apply'] == 'yes'){
                        $('#market_trend_black_wall_buy_apply').prop('checked',true);
                        }else{
                        $('#market_trend_black_wall_buy_apply').prop('checked',false);
                      }  

                      $('#market_trend_black_wall_operator_buy').val(res_obj.market_trend_black_wall_operator_buy);


                      $('#market_trend_black_wall_buy').val(res_obj.market_trend_black_wall_buy);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


                       //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                       if(res_obj['market_trend_black_wall_sell_apply'] == 'yes'){
                        $('#market_trend_black_wall_sell_apply').prop('checked',true);
                        }else{
                        $('#market_trend_black_wall_sell_apply').prop('checked',false);
                      }  

                      $('#market_trend_black_wall_operator_sell').val(res_obj.market_trend_black_wall_operator_sell);


                      $('#market_trend_black_wall_sell').val(res_obj.market_trend_black_wall_sell);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%



                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      if(res_obj['market_trend_seven_level_pressure_buy_apply'] == 'yes'){
                        $('#market_trend_seven_level_pressure_buy_apply').prop('checked',true);
                        }else{
                        $('#market_trend_seven_level_pressure_buy_apply').prop('checked',false);
                      }  

                      $('#market_trend_seven_level_pressure_operator_buy').val(res_obj.market_trend_seven_level_pressure_operator_buy);


                      $('#market_trend_seven_level_pressure_buy').val(res_obj.market_trend_seven_level_pressure_buy);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%



                       //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                       if(res_obj['market_trend_RL_buy_apply'] == 'yes'){
                        $('#market_trend_RL_buy_apply').prop('checked',true);
                        }else{
                        $('#market_trend_RL_buy_apply').prop('checked',false);
                      }  

                      $('#market_trend_RL_operator_buy').val(res_obj.market_trend_RL_operator_buy);

                      $('#market_trend_RL_buy').val(res_obj.market_trend_RL_buy);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%



                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      if(res_obj['market_trend_seven_level_pressure_sell_apply'] == 'yes'){
                        $('#market_trend_seven_level_pressure_sell_apply').prop('checked',true);
                        }else{
                        $('#market_trend_seven_level_pressure_sell_apply').prop('checked',false);
                      }  

                      $('#market_trend_seven_level_pressure_operator_sell').val(res_obj.market_trend_seven_level_pressure_operator_sell);


                      $('#market_trend_seven_level_pressure_sell').val(res_obj.market_trend_seven_level_pressure_sell);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%




                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      if(res_obj['market_trend_RL_sell_apply'] == 'yes'){
                        $('#market_trend_RL_sell_apply').prop('checked',true);
                        }else{
                        $('#market_trend_RL_sell_apply').prop('checked',false);
                      }  

                      $('#market_trend_RL_operator_sell').val(res_obj.market_trend_RL_operator_sell);


                      $('#market_trend_RL_sell').val(res_obj.market_trend_RL_sell);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


                         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      if(res_obj['market_trend_market_trend_buy_apply'] == 'yes'){
                        $('#market_trend_market_trend_buy_apply').prop('checked',true);
                        }else{
                        $('#market_trend_market_trend_buy_apply').prop('checked',false);
                      }  

                      $('#market_trend_market_trend_operator_buy').val(res_obj.market_trend_market_trend_operator_buy);


                      $('#market_trend_market_trend_buy').val(res_obj.market_trend_market_trend_buy);
                      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                          

                      $('#market_trend_supply_buy').val(res_obj.market_trend_supply_buy);    
                      
                      
                   //%%%%%%%%%%%%%%%%%%%%%%%%%  End  Market  Trending %%%%%%%%%%%%%%%%%%%%


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
                              if(res_obj['order_status'+buy_num_rule+'_enable'] == 'yes'){
                                $('#order_status'+buy_num_rule+'_enable').prop('checked',true);
                              }else{
                              $('#order_status'+buy_num_rule+'_enable').prop('checked',false);
                              }
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              if(res_obj['buyers_vs_sellers_buy'+buy_num_rule+'_enable'] == 'yes'){
                                $('#buyers_vs_sellers_buy'+buy_num_rule+'_enable').prop('checked',true);
                              }else{
                              $('#buyers_vs_sellers_buy'+buy_num_rule+'_enable').prop('checked',false);
                              }
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                              

                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              if(res_obj['buy_percentile_'+buy_num_rule+'_apply_buy'] == 'yes'){
                                $('#buy_percentile_'+buy_num_rule+'_apply_buy').prop('checked',true);
                              }else{
                                $('#buy_percentile_'+buy_num_rule+'_apply_buy').prop('checked',false);
                              }
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                              

                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              if(res_obj['ask_percentile_'+buy_num_rule+'_apply_buy'] == 'yes'){
                                $('#ask_percentile_'+buy_num_rule+'_apply_buy').prop('checked',true);
                              }else{
                              $('#ask_percentile_'+buy_num_rule+'_apply_buy').prop('checked',false);
                              }
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              
                              

                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              if(res_obj['sell_percentile_'+buy_num_rule+'_apply_buy'] == 'yes'){
                                $('#sell_percentile_'+buy_num_rule+'_apply_buy').prop('checked',true);
                              }else{
                                $('#sell_percentile_'+buy_num_rule+'_apply_buy').prop('checked',false);
                              }
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              if(res_obj['bid_percentile_'+buy_num_rule+'_apply_buy'] == 'yes'){
                                $('#bid_percentile_'+buy_num_rule+'_apply_buy').prop('checked',true);
                              }else{
                                $('#bid_percentile_'+buy_num_rule+'_apply_buy').prop('checked',false);
                              }
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/




                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              if(res_obj['buyers_vs_sellers_sell'+buy_num_rule+'_enable'] == 'yes'){
                                $('#buyers_vs_sellers_sell'+buy_num_rule+'_enable').prop('checked',true);
                              }else{
                              $('#buyers_vs_sellers_sell'+buy_num_rule+'_enable').prop('checked',false);
                              }
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/




                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              if(res_obj['buy_percentile_'+buy_num_rule+'_apply_sell'] == 'yes'){
                                $('#buy_percentile_'+buy_num_rule+'_apply_sell').prop('checked',true);
                              }else{
                              $('#buy_percentile_'+buy_num_rule+'_apply_sell').prop('checked',false);
                              }
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              
                              

                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              if(res_obj['ask_percentile_'+buy_num_rule+'_apply_sell'] == 'yes'){
                                $('#ask_percentile_'+buy_num_rule+'_apply_sell').prop('checked',true);
                              }else{
                                $('#ask_percentile_'+buy_num_rule+'_apply_sell').prop('checked',false);
                              }
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                              
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              if(res_obj['sell_percentile_'+buy_num_rule+'_apply_sell'] == 'yes'){
                                $('#sell_percentile_'+buy_num_rule+'_apply_sell').prop('checked',true);
                              }else{
                                $('#sell_percentile_'+buy_num_rule+'_apply_sell').prop('checked',false);
                              }
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                              
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              if(res_obj['bid_percentile_'+buy_num_rule+'_apply_sell'] == 'yes'){
                                $('#bid_percentile_'+buy_num_rule+'_apply_sell').prop('checked',true);
                              }else{
                                $('#bid_percentile_'+buy_num_rule+'_apply_sell').prop('checked',false);
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
                              $('#order_status'+buy_num_rule+'_buy').val(res_obj['order_status'+buy_num_rule+'_buy']);

                              
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              $('#buy_percentile_'+buy_num_rule+'_buy').val(res_obj['buy_percentile_'+buy_num_rule+'_buy']);

                              
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              $('#ask_percentile_'+buy_num_rule+'_buy').val(res_obj['ask_percentile_'+buy_num_rule+'_buy']);

                              
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              $('#sell_percentile_'+buy_num_rule+'_buy').val(res_obj['sell_percentile_'+buy_num_rule+'_buy']);

                              
                              /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                              $('#bid_percentile_'+buy_num_rule+'_buy').val(res_obj['bid_percentile_'+buy_num_rule+'_buy']);


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
                              
                              

                              if(res_obj['sell_virtural_for_buy_rule_'+buy_num_rule+'_enable'] == 'yes'){
                                $('#sell_virtural_for_buy_rule_'+buy_num_rule+'_enable').prop('checked',true);
                              }else{
                              $('#sell_virtural_for_buy_rule_'+buy_num_rule+'_enable').prop('checked',false);
                              }


                              if(res_obj['sell_check_volume_rule_'+buy_num_rule] == 'yes'){
                                $('#sell_check_volume_rule_'+buy_num_rule).prop('checked',true);
                              }else{
                                $('#sell_check_volume_rule_'+buy_num_rule).prop('checked',false);
                              }

                              
                              

                              //%%%%%%%%%%%%%% -- -- %%%%%%%%%%%%%%%%%%%%%%%
                              if(res_obj['buy_virtural_rule_for_sell_'+buy_num_rule+'_enable'] == 'yes'){
                                $('#buy_virtural_rule_for_sell_'+buy_num_rule+'_enable').prop('checked',true);
                              }else{
                                $('#buy_virtural_rule_for_sell_'+buy_num_rule+'_enable').prop('checked',false);
                              }
                              
                              //%%%%%%%%%%%%%% -- -- %%%%%%%%%%%%%%%%%%%%%%%



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






                              if(res_obj['rule_sorting'+buy_num_rule+'_enable'] == 'yes'){
                                $('#rule_sorting'+buy_num_rule+'_enable').prop('checked',true);
                              }else{
                              $('#rule_sorting'+buy_num_rule+'_enable').prop('checked',false);
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
                              $('#rule_sort'+buy_num_rule+'_sell').val(res_obj['rule_sort'+buy_num_rule+'_sell']);


                              

                              /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                              $('#buy_percentile_'+buy_num_rule+'_sell').val(res_obj['buy_percentile_'+buy_num_rule+'_sell']);


                                

                              /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                              $('#ask_percentile_'+buy_num_rule+'_sell').val(res_obj['ask_percentile_'+buy_num_rule+'_sell']);

                              
                              /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                              $('#sell_percentile_'+buy_num_rule+'_sell').val(res_obj['sell_percentile_'+buy_num_rule+'_sell']);


                                

                              /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                              $('#bid_percentile_'+buy_num_rule+'_sell').val(res_obj['bid_percentile_'+buy_num_rule+'_sell']);


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
                              $('#buyers_vs_sellers'+buy_num_rule+'_buy').val(res_obj['buyers_vs_sellers'+buy_num_rule+'_buy']);
                              /****%%%%%%%%%%%%%%%%%%%%%%%%%*****/



                                /***%%%%%%%%%%%%%%%%%%%%%%%%%*****/
                                $('#buyers_vs_sellers'+buy_num_rule+'_sell').val(res_obj['buyers_vs_sellers'+buy_num_rule+'_sell']);
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

                              
                                /***************************/
                                $('#sell_virtural_for_buy_rule_'+buy_num_rule).val((res_obj['sell_virtural_for_buy_rule_'+buy_num_rule]));
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
                                
                                //%%%%%%%%%%%%%%%%%% -- -- %%%%%%%%%%%%%%%%%%%
                                $('#buy_virtural_rule_for_sell_'+buy_num_rule).val((res_obj['buy_virtural_rule_for_sell_'+buy_num_rule]));
                                //%%%%%%%%%%%%%%%%%% -- -- %%%%%%%%%%%%%%%%%%%



                                  $('#done_pressure_rule_'+buy_num_rule).val((res_obj['done_pressure_rule_'+buy_num_rule]));


                                  $('#big_seller_percent_compare_rule_'+buy_num_rule).val(res_obj['big_seller_percent_compare_rule_'+buy_num_rule]);


                                  $('#closest_black_wall_rule_'+buy_num_rule).val(res_obj['closest_black_wall_rule_'+buy_num_rule]);


                                  $('#closest_yellow_wall_rule_'+buy_num_rule).val(res_obj['closest_yellow_wall_rule_'+buy_num_rule]);

                                  $('#seven_level_pressure_rule_'+buy_num_rule).val(res_obj['seven_level_pressure_rule_'+buy_num_rule]);


                                  $('#sell_percent_rule_'+buy_num_rule).val(res_obj['sell_percent_rule_'+buy_num_rule]);

                                  $('#seller_vs_buyer_rule_'+buy_num_rule+'_sell').val(res_obj['seller_vs_buyer_rule_'+buy_num_rule+'_sell']);

                            }//End of for loop



                    }else{
                      $('.show_hide_for_barrier_trigger').show();
                      $('.show_barrier_trigger').hide();
                    }



            $('#box_trigger_buy_price_percentage').val(res_obj.box_trigger_buy_price_percentage);
            $('#box_trigger_buy_sell_percentage').val(res_obj.box_trigger_buy_sell_percentage);
            $('#box_trigger_buy_stop_loss_percentage').val(res_obj.box_trigger_buy_stop_loss_percentage);


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
    }//End of get_trigger_setting


    function get_percentiles(){
      var coin = $('#coin').val();

      var barrier_percentile_trigger_buy_black_wall = $("#barrier_percentile_trigger_buy_black_wall").children("option:selected").val();
      var barrier_percentile_trigger_buy_virtual_barrier = $("#barrier_percentile_trigger_buy_virtual_barrier").children("option:selected").val();
      var barrier_percentile_trigger_buy_seven_level_pressure = $("#barrier_percentile_trigger_buy_seven_level_pressure").children("option:selected").val();
      var barrier_percentile_trigger_5_minute_rolling_candel = $("#barrier_percentile_trigger_5_minute_rolling_candel").children("option:selected").val();
      var barrier_percentile_trigger_15_minute_rolling_candel = $("#barrier_percentile_trigger_15_minute_rolling_candel").children("option:selected").val();
      var barrier_percentile_trigger_buyers_buy = $("#barrier_percentile_trigger_buyers_buy").children("option:selected").val();
      var percentile_trigger_last_candle_type = $("#percentile_trigger_last_candle_type").children("option:selected").val();
      var barrier_percentile_trigger_sellers_buy = $("#barrier_percentile_trigger_sellers_buy").children("option:selected").val();

      barrier_percentile_trigger_sell_black_wall = $("#barrier_percentile_trigger_sell_black_wall").children("option:selected").val();
      barrier_percentile_trigger_sell_virtual_barrier = $("#barrier_percentile_trigger_sell_virtual_barrier").children("option:selected").val();
      barrier_percentile_trigger_sell_seven_level_pressure = $("#barrier_percentile_trigger_sell_seven_level_pressure").children("option:selected").val();
      barrier_percentile_trigger_5_minute_rolling_candel_sell = $("#barrier_percentile_trigger_5_minute_rolling_candel_sell").children("option:selected").val();
      barrier_percentile_trigger_15_minute_rolling_candel_sell = $("#barrier_percentile_trigger_15_minute_rolling_candel_sell").children("option:selected").val();
      barrier_percentile_trigger_buyers_sell = $("#barrier_percentile_trigger_buyers_sell").children("option:selected").val();

      barrier_percentile_trigger_sellers_sell = $("#barrier_percentile_trigger_sellers_sell").children("option:selected").val();


      barrier_percentile_trigger_15_minute_last_time_ago = $("#barrier_percentile_trigger_15_minute_last_time_ago").children("option:selected").val();



      barrier_percentile_trigger_ask_contracts = $("#barrier_percentile_trigger_ask_contracts").children("option:selected").val();
      


      barrier_percentile_trigger_bid_contracts = $("#barrier_percentile_trigger_bid_contracts").children("option:selected").val();

      
      barrier_percentile_trigger_bid = $("#barrier_percentile_trigger_bid").children("option:selected").val();
    


      barrier_percentile_trigger_buy = $("#barrier_percentile_trigger_buy").children("option:selected").val();
        
        
      barrier_percentile_trigger_sell = $("#barrier_percentile_trigger_sell").children("option:selected").val();
      
    
      
  
      $.ajax({
        url: "<?=SURL;?>admin/settings/calculate_percentile_for_trading",
        data:{coin:coin},
        type: "POST",
        success: function(data){
          var res_obj_per =  JSON.parse(data);
          $('#bw_p').html(res_obj_per['blackwall_'+barrier_percentile_trigger_buy_black_wall]);
          $('#vb_p').html(res_obj_per['bid_quantity_'+barrier_percentile_trigger_buy_virtual_barrier]);
          $('#sl_p').html(res_obj_per['sevenlevel_'+barrier_percentile_trigger_buy_seven_level_pressure]);
          $('#fv_m_p').html(res_obj_per['five_min_'+barrier_percentile_trigger_5_minute_rolling_candel]);
          $('#fif_m_p').html(res_obj_per['fifteen_min_'+barrier_percentile_trigger_15_minute_rolling_candel]);
          $('#b_p').html(res_obj_per['buyers_fifteen_'+barrier_percentile_trigger_buyers_buy]);
          $('#s_p').html(res_obj_per['sellers_fifteen_b_'+barrier_percentile_trigger_sellers_buy]);

          $('#sbw_p').html(res_obj_per['blackwall_b_'+barrier_percentile_trigger_sell_black_wall]);
          $('#svb').html(res_obj_per['bid_quantity_b_'+barrier_percentile_trigger_sell_virtual_barrier]);
          $('#ssp').html(res_obj_per['sevenlevel_b_'+barrier_percentile_trigger_sell_seven_level_pressure]);
          $('#sfvm').html(res_obj_per['five_min_b_'+barrier_percentile_trigger_5_minute_rolling_candel_sell]);
          $('#sffm').html(res_obj_per['fifteen_min_b_'+barrier_percentile_trigger_15_minute_rolling_candel_sell]);
          $('#slb').html(res_obj_per['buyers_fifteen_b_'+barrier_percentile_trigger_buyers_sell]);
          $('#sls').html(res_obj_per['sellers_fifteen_'+barrier_percentile_trigger_sellers_sell]);

          $('#min_15_tm').html(res_obj_per['last_qty_time_ago_fif_'+barrier_percentile_trigger_15_minute_last_time_ago]);

          $('#bg_buy_ask_con').html(res_obj_per['ask_contract_'+barrier_percentile_trigger_ask_contracts]);

          $('#bg_sell_cont').html(res_obj_per['bid_contracts_b_'+barrier_percentile_trigger_bid_contracts]);
    
    
          $('#bin_buy_ask').html(res_obj_per['ask_'+barrier_percentile_trigger_bid_contracts]);


          $('#bin_bid_per').html(res_obj_per['bid_b_'+barrier_percentile_trigger_bid]);

          $('#digie_buy').html(res_obj_per['buy_'+barrier_percentile_trigger_buy]);


          $('#digie_sell').html(res_obj_per['sell_b_'+barrier_percentile_trigger_sell]);

        }
      });

    }

    $("body").on("change","#barrier_percentile_buy select",function(e){
        get_percentiles();
        get_market_trend()
    });

    $("body").on("change","#barrier_percentile_sell select",function(e){
        get_percentiles();
        get_market_trend()
        
    });






    function get_market_trend(){
      var coin = $('#coin').val();

     
  
      $.ajax({
        url: "<?=SURL;?>admin/settings/get_market_trend",
        data:{coin:coin},
        type: "POST",
        success: function(data){
          var res_obj_per =  JSON.parse(data);
          $('.mt_buy').html(res_obj_per['buy']);

          $('.mt_caption_option').html(res_obj_per['caption_option']);
          $('.mt_caption_score').html(res_obj_per['caption_score']);
          $('.mt_demand').html(res_obj_per['demand']);
          
          $('.mt_rl').html(res_obj_per['RL']);
          $('.long_term_intension').html(res_obj_per['long_term_intension']);
          
          

           $('.mt_market_trend').html(res_obj_per['market_trend']);
          $('.mt_meta_trading').html(res_obj_per['meta_trading']);
          $('.mt_riskpershare').html(res_obj_per['riskpershare']);
          
          $('.mt_sell').html(res_obj_per['sell']);
          $('.mt_supply').html(res_obj_per['supply']);
        
        }
      });

    }



</script>