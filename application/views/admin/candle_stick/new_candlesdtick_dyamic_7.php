<?php //echo "Hi";   exit;?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/candle_css/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/candel_graph/candel_graph.css">
<link rel="stylesheet" href="<?php echo ASSETS ?>candel_stick/font-awesome.min.css">

<link href="<?php echo ASSETS ?>candel_stickbootstrap-datetimepicker.css" rel="stylesheet">
<script src="<?php echo ASSETS ?>candel_stickmoment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>candel_stickbootstrap-datetimepicker.js"></script>

<?php

$candlesdtickArr = $candlesdtickArr;
$compare_val = $compare_val;
$candle_period = $candle_period;
$get_market_history_for_candel = $get_market_history_for_candel;
$draw_target_zone_arr = $draw_target_zone_arr;
$bid_volume_arr = $bid_volume_arr;
$ask_volume_arr = $ask_volume_arr;
$max_volumer = $max_volumer;
$unit_value = $unit_value;
if (isset($_GET['DemandTrigger'])) {
	$DemandTrigger = $_GET['DemandTrigger'];
} else {
	$DemandTrigger = 90000;
}
if (isset($_GET['SupplyTrigger'])) {
	$SupplyTrigger = $_GET['SupplyTrigger'];
} else {
	$SupplyTrigger = 90000;
}

?>

<div id="content" style="margin-top: 111px">
<div class="right_filter_box">
  <div class="rfb_openclose">
    <p>TASK MANAGER</p>
    <span><i class="fa fa-chevron-right" aria-hidden="true"></i></span> </div>
  <div class="right_filter_iner">
    <div class="col-xs-12 col-md-3">
      <div class="rfi_fieldset">
        <div class="row">
          <div class="col-xs-3">
            <div class="rfi_row">
              <label for="comment">Warning limit:</label>
              <input type="number"  id="limit_id" class=" form-control"  step="0.1" value="<?php echo (($task_manager_setting_arr[0]['limit_id'] != '') ? $task_manager_setting_arr[0]['limit_id'] : 0.1) ?>">
            </div>
          </div>
          <div class="col-xs-3">
            <div class="rfi_row">
              <label for="comment">candle sec:</label>
              <input type="number" id="candlePeriod_id" class=" form-control"   value="<?php echo (($task_manager_setting_arr[0]['candlePeriod_id'] != '') ? $task_manager_setting_arr[0]['candlePeriod_id'] : 3600) ?>">
            </div>
          </div>
          <div class="col-xs-3">
            <div class="rfi_row">
              <label for="comment">vol MA size:</label>
              <input type="number" id="sizeMAVol_id" class=" form-control" step="10"   value="<?php echo (($task_manager_setting_arr[0]['sizeMAVol_id'] != '') ? $task_manager_setting_arr[0]['sizeMAVol_id'] : 10) ?>">
            </div>
          </div>
          <div class="col-xs-3">
            <div class="rfi_row">
              <label for="comment">Lookback:</label>
              <input type="number" id="Lookback" class=" form-control"    value="<?php echo (($task_manager_setting_arr[0]['Lookback'] != '') ? $task_manager_setting_arr[0]['Lookback'] : 7) ?>">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-md-9"> 
      
        <!--  <div class="rfi_fieldset">

                    <div class="rfi_row">

                        <label>Climax Down:</label>

                        <input type="checkbox" value="" id="climaxDown"  <?php// echo  (($task_manager_setting_arr[0]['climaxDown']==1)?'checked':'')?>>

                    </div>



                    <div class="rfi_row">

                        <label>churn:</label>

                        <input type="checkbox" value=""  id="churn" <?php// echo  (($task_manager_setting_arr[0]['churn']==1)?'checked':'')?> >

                    </div>



                    <div class="rfi_row">

                        <label>Climax Churn:</label>

                        <input type="checkbox" value=""  id="climaxChurn" <?php// echo  (($task_manager_setting_arr[0]['climaxChurn']==1)?'checked':'')?>>

                    </div>

                </div> --> 
      
      <!-- Swing point  -->
      
      <div class="rfi_fieldset">
        <div class="row">
          <div class="col-xs-2">
            <div class="rfi_row">
              <label for="comment">Pivot Length Left Hand Side</label>
              <input type="number" id="pvtLenL" class=" form-control" step="1"   value="<?php echo (($task_manager_setting_arr[0]['pvtLenL'] != '') ? $task_manager_setting_arr[0]['pvtLenL'] : 5) ?>">
            </div>
          </div>
          <div class="col-xs-2">
            <div class="rfi_row">
              <label for="comment">Pivot Length Right Hand Side</label>
              <input type="number" id="pvtLenR" class=" form-control" step="1"   value="<?php echo (($task_manager_setting_arr[0]['pvtLenR'] != '') ? $task_manager_setting_arr[0]['pvtLenR'] : 3) ?>">
            </div>
          </div>
          <div class="col-xs-2">
            <div class="rfi_row">
              <label for="comment">Maximum Extension Length</label>
              <input type="number" id="maxLvlLen" class=" form-control" step="1"   value="<?php echo (($task_manager_setting_arr[0]['maxLvlLen'] != '') ? $task_manager_setting_arr[0]['maxLvlLen'] : 0) ?>">
            </div>
          </div>
          <div class="col-xs-3">
            <div class="rfi_row">
              <label for="comment">&nbsp;</label>
              <div class="rfb_checkbox">
                <label>Show HH,LL,LH,HL Markers On Pivots Points</label>
                <input type="checkbox" value="<?php echo (($task_manager_setting_arr[0]['ShowHHLL'] == 1) ? 'checked' : '') ?>"  id="ShowHHLL">
                <span> <i class="fa fa-square-o" aria-hidden="true"></i> <i class="fa fa-check-square-o" aria-hidden="true"></i> </span> </div>
            </div>
          </div>
          <div class="col-xs-3">
            <div class="rfi_row">
              <label for="comment">&nbsp;</label>
              <div class="rfb_checkbox">
                <label>Wait For Candle Close Before Printing Pivot</label>
                <input type="checkbox" value=""  id="WaitForClose" <?php echo (($task_manager_setting_arr[0]['WaitForClose'] == 1) ? 'checked' : '') ?>>
                <span> <i class="fa fa-square-o" aria-hidden="true"></i> <i class="fa fa-check-square-o" aria-hidden="true"></i> </span> </div>
            </div>
          </div>
        </div>
      </div>
      
      <!--  End of swing point--> 
      
    </div>
    <div class="clearfix"></div>
    <div class="col-xs-12 col-md-9">
      <div class="rfi_fieldset">
        <div class="row">
          <div class="col-xs-2">
            <div class="rfi_row supply_cls">
              <label for="comment">Current Down Percentile S:</label>
              <input type="number" value="<?php echo (($task_manager_setting_arr[0]['Current_Down_Percentile_supply'] != '') ? $task_manager_setting_arr[0]['Current_Down_Percentile_supply'] : 25) ?>" id="Current_Down_Percentile_supply" class=" form-control">
            </div>
          </div>
          <div class="col-xs-2">
            <div class="rfi_row supply_cls">
              <label for="comment">Continuationi Down Percentile S:</label>
              <input type="number" value="<?php echo (($task_manager_setting_arr[0]['Continuation_Down_Percentile_supply'] != '') ? $task_manager_setting_arr[0]['Continuation_Down_Percentile_supply'] : 20) ?>" id="Continuation_Down_Percentile_supply" class=" form-control">
            </div>
          </div>
          <div class="col-xs-2">
            <div class="rfi_row supply_cls">
              <label for="comment">Current Up Percentile S:</label>
              <input type="number" value="<?php echo (($task_manager_setting_arr[0]['Current_up_Percentile_supply'] != '') ? $task_manager_setting_arr[0]['Current_up_Percentile_supply'] : 25) ?>" id="Current_up_Percentile_supply" class=" form-control">
            </div>
          </div>
          <div class="col-xs-2">
            <div class="rfi_row supply_cls">
              <label for="comment">Continuation Up Percentile S:</label>
              <input type="number" value="<?php echo (($task_manager_setting_arr[0]['Continuation_up_Percentile_supply'] != '') ? $task_manager_setting_arr[0]['Continuation_up_Percentile_supply'] : 30) ?>" id="Continuation_up_Percentile_supply" class=" form-control">
            </div>
          </div>
          <div class="col-xs-1">
            <div class="rfi_row supply_cls">
              <label for="comment">LH Percentile S:</label>
              <input type="number" value="<?php echo (($task_manager_setting_arr[0]['LH_Percentile_supply'] != '') ? $task_manager_setting_arr[0]['LH_Percentile_supply'] : 10) ?>" id="LH_Percentile_supply" class=" form-control">
            </div>
          </div>
          <div class="col-xs-1">
            <div class="rfi_row supply_cls">
              <label for="comment">HL Percentile S:</label>
              <input type="number" value="<?php echo (($task_manager_setting_arr[0]['HL_Percentile_supply'] != '') ? $task_manager_setting_arr[0]['HL_Percentile_supply'] : 10) ?>" id="HL_Percentile_supply" class=" form-control">
            </div>
          </div>
          <div class="col-xs-2">
            <div class="rfi_row">
              <label for="comment">PercentileTrigger:</label>
              <input type="number" value="<?php echo (($task_manager_setting_arr[0]['PercentileTrigger'] != '') ? $task_manager_setting_arr[0]['PercentileTrigger'] : 90) ?>" id="PercentileTrigger" class=" form-control">
            </div>
          </div>
          <div class="col-xs-2">
            <div class="rfi_row supply_cls">
              <label for="comment">Current Down Percentile D:</label>
              <input type="number" value="<?php echo (($task_manager_setting_arr[0]['Current_Down_Percentile'] != '') ? $task_manager_setting_arr[0]['Current_Down_Percentile'] : 25) ?>" id="Current_Down_Percentile" class=" form-control">
            </div>
          </div>
          <div class="col-xs-2">
            <div class="rfi_row supply_cls">
              <label for="comment">Continuationi Down Percentile D:</label>
              <input type="number" value="<?php echo (($task_manager_setting_arr[0]['Continuation_Down_Percentile'] != '') ? $task_manager_setting_arr[0]['Continuation_Down_Percentile'] : 20) ?>" id="Continuation_Down_Percentile" class=" form-control">
            </div>
          </div>
          <div class="col-xs-2">
            <div class="rfi_row supply_cls">
              <label for="comment">Current Up Percentile D:</label>
              <input type="number" value="<?php echo (($task_manager_setting_arr[0]['Current_up_Percentile'] != '') ? $task_manager_setting_arr[0]['Current_up_Percentile'] : 25) ?>" id="Current_up_Percentile" class=" form-control">
            </div>
          </div>
          <div class="col-xs-2">
            <div class="rfi_row supply_cls">
              <label for="comment">Continuation Up Percentile D:</label>
              <input type="number" value="<?php echo (($task_manager_setting_arr[0]['Continuation_up_Percentile'] != '') ? $task_manager_setting_arr[0]['Continuation_up_Percentile'] : 30) ?>" id="Continuation_up_Percentile" class=" form-control">
            </div>
          </div>
          <div class="col-xs-1">
            <div class="rfi_row supply_cls">
              <label for="comment">LH Percentile D:</label>
              <input type="number" value="<?php echo (($task_manager_setting_arr[0]['LH_Percentile'] != '') ? $task_manager_setting_arr[0]['LH_Percentile'] : 10) ?>" id="LH_Percentile" class=" form-control">
            </div>
          </div>
          <div class="col-xs-1">
            <div class="rfi_row supply_cls">
              <label for="comment">HL Percentile D:</label>
              <input type="number" value="<?php echo (($task_manager_setting_arr[0]['HL_Percentile'] != '') ? $task_manager_setting_arr[0]['HL_Percentile'] : 10) ?>" id="HL_Percentile" class=" form-control">
            </div>
          </div>
          <div class="col-xs-2">
            <div class="rfi_row">
              <label for="comment">BarsBack:</label>
              <input type="number" value="<?php echo (($task_manager_setting_arr[0]['BarsBack'] != '') ? $task_manager_setting_arr[0]['BarsBack'] : 8) ?>"  id="BarsBack" class=" form-control">
            </div>
          </div>
          
          <!-- _______________________________  --> 
          
          <!-- <div class="rfi_row">

                        <div class="rfb_checkbox">

                            <label>Custom Base Candel:</label>

                            <input type="checkbox" value=""  id="custom_base_trigers" <?php //echo  (($task_manager_setting_arr[0]['custom_base_trigers']==1)?'checked':'')?>>

                            <span>

                            <i class="fa fa-square-o" aria-hidden="true"></i>

                            <i class="fa fa-check-square-o" aria-hidden="true"></i>

                            </span>

                        </div>

                    </div> --> 
          
          <!-- _______________________________  --> 
          
          <!--   <div class="rfi_row ">

                        <label for="comment">DemandTrigger:</label>

                        <input type="number" value="90000"  id="DemandTrigger" class=" form-control">

                    </div> --> 
          
          <!-- <div class="rfi_row">

                        <label for="comment">SupplyTrigger:</label>

                        <input type="number" value="<?php //echo  (($task_manager_setting_arr[0]['SupplyTrigger']!='')?$task_manager_setting_arr[0]['SupplyTrigger']:90000)?> "  id="SupplyTrigger" class=" form-control">

                    </div> --> 
          
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-md-3">
      <div class="rfi_fieldset">
        <div class="row">
          <div class="col-xs-4">
            <div class="rfi_row">
              <div class="rfb_checkbox">
                <label>Enable Color Bars</label>
                <input type="checkbox" value="" id="enableBarColors" <?php echo (($task_manager_setting_arr[0]['enableBarColors'] == 1) ? 'checked' : '') ?> >
                <span> <i class="fa fa-square-o" aria-hidden="true"></i> <i class="fa fa-check-square-o" aria-hidden="true"></i> </span> </div>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="rfi_row">
              <div class="rfb_checkbox">
                <label>Use 2 Bars:</label>
                <input type="checkbox" value="" id="use2Bars"  <?php echo (($task_manager_setting_arr[0]['use2Bars'] == 1) ? 'checked' : '') ?> >
                <span> <i class="fa fa-square-o" aria-hidden="true"></i> <i class="fa fa-check-square-o" aria-hidden="true"></i> </span> </div>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="rfi_row">
              <div class="rfb_checkbox">
                <label>Low Vol:</label>
                <input type="checkbox" value=""  id="lowVol" <?php echo (($task_manager_setting_arr[0]['lowVol'] == 1) ? 'checked' : '') ?>>
                <span> <i class="fa fa-square-o" aria-hidden="true"></i> <i class="fa fa-check-square-o" aria-hidden="true"></i> </span> </div>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="rfi_row">
              <div class="rfb_checkbox">
                <label>Climax Up:</label>
                <input type="checkbox" value=""  id="climaxUp" <?php echo (($task_manager_setting_arr[0]['climaxUp'] == 1) ? 'checked' : '') ?>>
                <span> <i class="fa fa-square-o" aria-hidden="true"></i> <i class="fa fa-check-square-o" aria-hidden="true"></i> </span> </div>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="rfi_row">
              <div class="rfb_checkbox">
                <label>Climax Down:</label>
                <input type="checkbox" value="" id="climaxDown"  <?php echo (($task_manager_setting_arr[0]['climaxDown'] == 1) ? 'checked' : '') ?>>
                <span> <i class="fa fa-square-o" aria-hidden="true"></i> <i class="fa fa-check-square-o" aria-hidden="true"></i> </span> </div>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="rfi_row">
              <div class="rfb_checkbox">
                <label>Churn:</label>
                <input type="checkbox" value=""  id="churn" <?php echo (($task_manager_setting_arr[0]['churn'] == 1) ? 'checked' : '') ?>>
                <span> <i class="fa fa-square-o" aria-hidden="true"></i> <i class="fa fa-check-square-o" aria-hidden="true"></i> </span> </div>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="rfi_row">
              <div class="rfb_checkbox">
                <label>Cimax Churn:</label>
                <input type="checkbox" value=""  id="climaxChurn" <?php echo (($task_manager_setting_arr[0]['climaxChurn'] == 1) ? 'checked' : '') ?> >
                <span> <i class="fa fa-square-o" aria-hidden="true"></i> <i class="fa fa-check-square-o" aria-hidden="true"></i> </span> </div>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="rfi_row">
              <div class="rfb_checkbox">
                <label>First chart color white:</label>
                <input type="checkbox" value="" id="chck_white" <?php echo (($task_manager_setting_arr[0]['chck_white'] == 1) ? 'checked' : '') ?>>
                <span> <i class="fa fa-square-o" aria-hidden="true"></i> <i class="fa fa-check-square-o" aria-hidden="true"></i> </span> </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-xs-12 col-md-12">
      <div class="rfi_fieldset">
        <div class="row">
          <div class="col-xs-3">
            <div class="row">
              <div class="col-xs-6">
                <div class="rfi_row">
                  <div class="slidecontainer">
                    <label>Pline<span class="pl">100</span></label>
                    <input class="Pline slider" type="range" min="10" max="200" value="100" >
                  </div>
                </div>
              </div>
              <div class="col-xs-6">
                <div class="rfi_row">
                  <div class="slidecontainer">
                    <label>Tline<span class="tl" id="demo">7</span></label>
                    <input type="range" min="1" max="500" value="60" class="slider Tline" id="myRange">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xs-2">
            <div class="rfi_row">
              <div class="form-group">
                <label for="comment" class="col-md-1">Candel By Date Range:</label>
                <div class="col-md-8 loadinput">
                  <input type='text' class="form-control datetime_picker " name="start_date" placeholder="Search By Start Date" value="" />
                </div>
                <div class="col-md-4 loadinput">
                  <button type="button" class="btn btn-success btn-block btn-sm search_candel_by_date" >Search</button>
                  <button type="button" class="btn btn-success btn-block btn-sm wait_search_candel_by_date" style="display: none;"> <i class="fa fa-spinner fa-spin" style="font-size:24px"></i> </button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xs-3">
            <div class="rfi_row">
              <div class="col-md-6 pull-left">
                <label for="comment" class="col-md-1">&nbsp;</label>
                <button href="#" class="btn btn-info btn-sm btn-block Backward"> <span class="glyphicon glyphicon-backward back_word_sh_hide"> </span> Backward <i class="fa fa-spinner fa-spin span_hd_show" style="font-size:24px;display: none;"></i> </button>
              </div>
              <div class="col-md-6 pull-righ">
                <label for="comment" class="col-md-1">&nbsp;</label>
                <button class="btn btn-info btn-sm btn-block Forward"> <span class="glyphicon glyphicon-forward forward_sh_hide"></span> Forward <i class="fa fa-spinner fa-spin f_span_hd_show " style="font-size:24px;display: none;"></i> </button>
              </div>
            </div>
          </div>
          <div class="col-xs-2">
            <div class="rfi_row">
              <div class="row">
                <div class="col-xs-5">
                  <label for="comment" class="col-md-1">&nbsp;</label>
                  <button type="button" class="btn btn-success btn-block btn-sm run_ajax">Refresh</button>
                  <button type="button" class="btn btn-success btn-block btn-sm wait_run_ajax" style="display: none;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></button>
                </div>
                <div class="col-xs-7">
                  <label for="comment" class="col-md-1">&nbsp;</label>
                  <button type="button" class="btn btn-success btn-block btn-sm save_candle_stick_detail">Save Canle</button>
                  <button type="button" class="btn btn-success btn-block btn-sm wait_candle_stick_detail" style="display: none;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></button>
                </div>
              </div>
            </div>
          </div>
          
          <!--  Save Task Manager -->
          
          <div class="col-xs-2">
            <div class="rfi_row">
              <div class="row">
                <div class="col-xs-12">
                  <label for="comment" class="col-md-1">&nbsp;</label>
                  <button type="button" class="btn btn-success btn-block btn-sm save_task_manager_setting">Save Setting</button>
                  <button type="button" class="btn btn-success btn-block btn-sm wait_save_task_manager_setting" style="display: none;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></button>
                </div>
              </div>
            </div>
          </div>
          
          <!--  End of Save Task Manager--> 
          
        </div>
      </div>
    </div>
  </div>
</div>
<span class="dddd"></span>
<div id="toptip"></div>
<div id="canvasbox_outer">
  <div id="tooltipbox">
    <div class="toletip"></div>
  </div>
  <div id="canvasbox_X">
    <div id="main_price_line" class="c_main_price_line">
      <div id="pdgn" class="c_pdgn">0.000525</div>
    </div>
  </div>
  <div id="canvasbox_Y">
    <div id="main_time_line">
      <div id="tdgn">0.000525</div>
    </div>
  </div>
</div>
<canvas id="myCanvas" style="border:1px solid #000;"></canvas>
<div class="append_table"></div>

<script>

        SURL = '<?php echo SURL; ?>';
        TempArray = <?php echo json_encode($candlesdtickArr); ?>;
        get_market_history_for_candel = <?php echo json_encode($get_market_history_for_candel); ?>;
        draw_target_zone_arr = <?php echo json_encode($draw_target_zone_arr); ?>;
        bid_volume_arr = <?php echo json_encode($bid_volume_arr); ?>;
        ask_volume_arr = <?php echo json_encode($ask_volume_arr); ?>;
        max_volumer = <?php echo json_encode($max_volumer); ?>;
        order_data = <?php echo json_encode($order_data); ?>;
        unit_value = <?php echo json_encode($unit_value); ?>;
        DemandTrigger_global = <?php echo json_encode($DemandTrigger); ?>;
        SupplyTrigger_global = <?php echo json_encode($SupplyTrigger); ?>;
        all_Hour_candle_volume_detail = <?php echo json_encode($all_Hour_candle_volume_detail); ?>;
</script> 
<script src="<?php echo ASSETS; ?>candel_graph/new_candel_graph_quarter.js"></script> 
