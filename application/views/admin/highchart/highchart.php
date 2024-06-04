<?php
$page_post_data = $this->session->userdata('page_post_data');
$box_session_data = $this->session->userdata('page_post_data');
if ($box_session_data == '') {$checboxValue = 'checked="checked"';}

   
$dataArr  = explode(',', $chart_arr[0]);
$finalArr = array_chunk($chart_arr[0]);
//echo "<pre>";   print_r($chart_arr[0]); exit;
 
//echo "<prE>";  print_r($box_session_data); exit;	
?>
<script src="<?php echo ASSETS; ?>js/highcharts/highcharts.js"></script>
<script src="<?php
echo ASSETS;
?>js/highcharts/series-label.js"></script>
<script src="<?php
echo ASSETS;
?>js/highcharts/exporting.js"></script>
<script src="<?php
echo ASSETS;
?>js/highcharts/export-data.js"></script>
<link href="<?php
echo ASSETS;
?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="<?php
echo ASSETS;
?>buyer_order/moment-with-locales.js"></script>
<script src="<?php
echo ASSETS;
?>buyer_order/bootstrap-datetimepicker.js"></script>
<style>
.sidebar.sidebar-inverse {
	z-index: 9999;
}
.highcharts-credits {
	display: none;
}
.highcharts-legend {
/*visibility:hidden;*/
}
.loader_parent {
	position: relative;
}
.loader_image_main {
	bottom: 0;
	height: 50px;
	left: 0;
	margin: auto;
	position: absolute;
	right: 0;
	top: 0;
	width: 50px;
	z-index: 9999;
}
.loader_overlay_main {
	background: rgba(255, 255, 255, 0.8) none repeat scroll 0 0;
	height: 100%;
	position: absolute;
	width: 100%;
	z-index: 99;
	display: none;
}

/*------------------------------------------------- Check box -------*/
.fncy_check_label {
    float: left;
    width: calc(100% - 45px);
    margin-left: 10px;
    font-size: 11px;
    font-weight: normal;
}
.fncy_check_box {
    float: left;
    height: 18px;
    margin-right: 0;
    margin-top: 0;
    width: 35px;
}
.fncy_check_box > label {
    background: #b3b3b3 none repeat scroll 0 0;
    border-radius: 10px;
    box-shadow: 0 1px 3px 3px rgba(0, 0, 0, 0.1) inset;
    float: left;
    height: 14px;
    position: relative;
    width: 100%;
}
.fncy_check_box > input {
    height: 100%;
    left: 0;
    margin: 0;
    opacity: 0;
    position: absolute;
    width: 100%;
    z-index: 1;
}
.fncy_check_box > label > span {
    background: #fff none repeat scroll 0 0;
    border-radius: 50%;
    box-shadow: 1px 1px 5px #bcbcbc;
    height: 21px;
    left: -5px;
    position: absolute;
    top: -4px;
    transition: 0.3s;
    width: 21px;
}
.fncy_check_box > input[type="checkbox"]:checked+label > span{
	left:20px;
	background:#5cb85c;
	box-shadow: -2px 1px 3px rgba(0, 0, 0, 0.4);
}
.fncy_check_box > input[type="checkbox"]:checked+label{
	background:#addaad;
}
/*---------------------------------------------------------*/
</style>
<div id="content">
  <div class="innerAll bg-white border-bottom"> </div>
  <div class="innerAll spacing-x2">
    <?php
if ($this->session->flashdata('err_message')) {
    ?>
    <div class="alert alert-danger">
      <?php
echo $this->session->flashdata('err_message');
    ?>
    </div>
    <?php
}
if ($this->session->flashdata('ok_message')) {
    ?>
    <div class="alert alert-success alert-dismissable">
      <?php
echo $this->session->flashdata('ok_message');
    ?>
    </div>
    <?php
}
?>
    <div class="alert alert-success alert-dismissable savesessionmessaage" style="display:none;">Successfully Save session against the coin .</div>
    
    <!-- Widget -->
    <div class="widget widget-inverse">
      <div class="widget-body padding-bottom-none"> 
        <!-- Table -->
        
        <div class="col-md-12 ">
          <form action="<?php echo SURL;?>admin/highchart/report"  id="highchartform" enctype="multipart/form-data" method="post">
            <div class="form-group col-md-3"> Select the session
              <select class="form-control form_session appendSessionData" name="form_session" id="form_session" onchange="this.form.submit()">
                <option value="">Select Session</option>
                <?php foreach($session_data_array as $session){?>
                <option value="<?php echo $session->_id; ?>" <?php echo ($session->_id == $page_post_data['form_session']) ? 'selected="selected"' : ''; ?>> <?php echo $session->session_name;?></option>
                <?php }?>
              </select>
            </div>
          </form>
          <?php  if($page_post_data!=''){ ?>
          <div class="form-group col-md-3 "> Add new session
            <input type="text" class="form-control save_sesion_data"  name="save_sesion_data" id="save_sesion_data">
          </div>
          <div class="form-group col-md-1"> .&nbsp; <a type="submit" class="btn btn-primary btn-block btn-sm savesession" > Save Session </a> </div>
          <?php }?>
          <div class="form-group col-md-3"></div>
          <?php 
			  $tabNo =  ($page_post_data['tab_no']=='') ? 1 : $tab_no;
			  if($tabNo==1){ }else{?>
          <div class="form-group col-md-1 pull-right" style="background: #162450; color: white; border:hidden;"> <a href="<?php echo SURL ?>admin/highchart/next-session/<?php echo $page_post_data['tab_no']; ?>" style="color:#FFF;" class="btn btn-block btn-sm"  target="_blank" >Session New Tab</a> </div>
          <?php } ?>
        </div>
        <form action="<?php
echo SURL;
?>admin/highchart/report"  id="highchartform" enctype="multipart/form-data" method="post">
          <div class="col-md-12 ">
            <div class="form-group col-md-2">
              <label class="control-label">Start Date</label>
              <input type='text' class="form-control datetime_picker" name="start_date" placeholder="Search By Start Date" value="<?php
echo $startDate;
?>" />
            </div>
            <div class="form-group col-md-2">
              <label class="control-label">End Date</label>
              <input type='text' class="form-control datetime_picker" name="end_date" placeholder="Search By End Date" value="<?php
echo $endDate;
?>" />
            </div>
            <div class="form-group col-md-2">
              <label class="control-label" for="hour">Select Coin </label>
              <select class="form-control coin" name="coin" id="coin">
                <?php
if ($page_post_data['coin'] == '') {
    $coinval = $global_symbol;
} else {
    $coinval = $page_post_data['coin'];
}
?>
                <?php
foreach ($coins_arr as $coin) {
    ?>
                <option value="<?php
echo $coin['symbol'];
    ?>" <?php
echo ($coinval == $coin['symbol']) ? 'selected="selected"' : '';
    ?>>
                <?php
echo $coin['symbol'];
    ?>
                </option>
                <?php
}
?>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label class="control-label" for="trigger">Select Trigger</label>
              <select class="form-control trigger" name="trigger" id="trigger">
                <option value="" <?php echo ($page_post_data['trigger'] == '') ? 'selected="selected"' : '';?>> No Triggers</option>
                <option value="all" <?php echo ($page_post_data['trigger'] == 'all') ? 'selected="selected"' : '';?>> All Triggers</option>
                <option value="barrier_trigger" <?php echo ($page_post_data['trigger'] == 'barrier_trigger') ? 'selected="selected"' : '';?>> Barrier Trigger</option>
                <option value="box_trigger_3" <?php echo ($page_post_data['trigger'] == 'box_trigger_3') ? 'selected="selected"' : '';?>> Barrier Trigger 3</option>
                <option value="barrier_trigger_simulator" <?php echo ($page_post_data['trigger'] == 'barrier_trigger_simulator') ? 'selected="selected"' : '';?>> Barrier Trigger simulator</option>
                <option value="barrier_percentile_trigger" <?php echo ($page_post_data['trigger'] == 'barrier_percentile_trigger') ? 'selected="selected"' : '';?>> Barrier Percentile </option>
              </select>
            </div>
            <div class="form-group col-md-1">
              <label class="control-label" for="coin">Time Duration</label>
              <select class="form-control time" name="time" id="time">
                <option value="minut" <?php
echo ($time == 'minut') ? 'selected="selected"' : '';
?>> Minut</option>
                <option value="hour" <?php
echo ($time == 'hour') ? 'selected="selected"' : '';
?>> Hour</option>
              </select>
            </div>
            <div class="form-group col-md-1">
              <label class="control-label" for="hour"> No. Market </label>
              <input type='text' class="form-control mutiply_no_market" name="mutiply_no_market"  value="<?php
echo $page_post_data['mutiply_no_market'];
?>" />
            </div>
            <div class="form-group col-md-1">
              <label class="control-label" for="hour"> - No. Price</label>
              <input type='text' class="form-control minus_no_score" name="minus_no_score"  value="<?php
echo $page_post_data['minus_no_score'];
?>" />
            </div>
            <div class="form-group col-md-1">
              <label class="control-label">Defined Width</label>
              <select class="form-control time" name="chart_width" id="chart_width">
                <option value="1600" <?php
echo ($page_post_data['chart_width'] == '1600') ? 'selected="selected"' : '';
?>> Normal</option>
                <option value="3000" <?php
echo ($page_post_data['chart_width'] == '3000') ? 'selected="selected"' : '';
?>> Large </option>
                <option value="5000" <?php
echo ($page_post_data['chart_width'] == '5000') ? 'selected="selected"' : '';
?>> Extra Large </option>
              </select>
            </div>
          </div>
          <div class="col-md-12 ">
            <div class="form-group col-md-1">
              <label class="control-label" for="">Market Depth</label>
              <select class="form-control time" name="price_format" id="price_format">
                <option value="100" <?php
echo ($page_post_data['price_format'] == '100') ? 'selected="selected"' : '';
?>> Show in 100</option>
                <option value="1000" <?php
echo ($page_post_data['price_format'] == '1000') ? 'selected="selected"' : '';
?>> Show in K</option>
                <option value="10000" <?php
echo ($page_post_data['price_format'] == '10000') ? 'selected="selected"' : '';
?>> Show in 10 K</option>
                <option value="100000" <?php
echo ($page_post_data['price_format'] == '100000') ? 'selected="selected"' : '';
?> > Show in 100 K</option>
                <option value="1000000" <?php
echo ($page_post_data['price_format'] == '1000000') ? 'selected="selected"' : '';
?> > Show in M</option>
                <option value="10000000" <?php
echo ($page_post_data['price_format'] == '10000000') ? 'selected="selected"' : '';
?> > Show in 10 M</option>
              </select>
            </div>
            <div class="form-group col-md-1">
              <label class="control-label" for=""> Format T COT</label>
              <select class="form-control time" name="time_cot" id="time_cot">
                <option value="100" <?php
echo ($page_post_data['time_cot'] == '100') ? 'selected="selected"' : '';
?>> Show in 100</option>
                <option value="1000" <?php
echo ($page_post_data['time_cot'] == '1000') ? 'selected="selected"' : '';
?>> Show in K</option>
                <option value="10000" <?php
echo ($page_post_data['time_cot'] == '10000') ? 'selected="selected"' : '';
?>> Show in 10 K</option>
                <option value="100000" <?php
echo ($page_post_data['time_cot'] == '100000') ? 'selected="selected"' : '';
?> > Show in 100 K</option>
                <option value="1000000" <?php
echo ($page_post_data['time_cot'] == '1000000') ? 'selected="selected"' : '';
?> > Show in M</option>
                <option value="10000000" <?php
echo ($page_post_data['time_cot'] == '10000000') ? 'selected="selected"' : '';
?> > Show in 10 M</option>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label class="control-label" for="">Big Buyers And Sellers</label>
              <select class="form-control time" name="m_buyers_sellers" id="m_buyers_sellers">
                <option value="100" <?php
echo ($page_post_data['m_buyers_sellers'] == '100') ? 'selected="selected"' : '';
?>> Show in 100</option>
                <option value="1000" <?php
echo ($page_post_data['m_buyers_sellers'] == '1000') ? 'selected="selected"' : '';
?>> Show in K</option>
                <option value="10000" <?php
echo ($page_post_data['m_buyers_sellers'] == '10000') ? 'selected="selected"' : '';
?>> Show in 10 K</option>
                <option value="100000" <?php
echo ($page_post_data['m_buyers_sellers'] == '100000') ? 'selected="selected"' : '';
?> > Show in 100 K</option>
                <option value="1000000" <?php
echo ($page_post_data['m_buyers_sellers'] == '1000000') ? 'selected="selected"' : '';
?> > Show in M</option>
                <option value="10000000" <?php
echo ($page_post_data['m_buyers_sellers'] == '10000000') ? 'selected="selected"' : '';
?> > Show in 10 M</option>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label class="control-label" for=""> Sellers And Buyers 15</label>
              <select class="form-control " name="s_b_15" id="s_b_15">
                <option value="100" <?php
echo ($page_post_data['s_b_15'] == '100') ? 'selected="selected"' : '';
?>> Show in 100</option>
                <option value="1000" <?php
echo ($page_post_data['s_b_15'] == '1000') ? 'selected="selected"' : '';
?>> Show in K</option>
                <option value="10000" <?php
echo ($page_post_data['s_b_15'] == '10000') ? 'selected="selected"' : '';
?>> Show in 10 K</option>
                <option value="100000" <?php
echo ($page_post_data['s_b_15'] == '100000') ? 'selected="selected"' : '';
?> > Show in 100 K</option>
                <option value="1000000" <?php
echo ($page_post_data['s_b_15'] == '1000000') ? 'selected="selected"' : '';
?> > Show in M</option>
                <option value="10000000" <?php
echo ($page_post_data['s_b_15'] == '10000000') ? 'selected="selected"' : '';
?> > Show in 10 M</option>
              </select>
            </div>
            <div class="form-group col-md-1">
              <label class="control-label" for=""> Last Quantity 15</label>
              <select class="form-control " name="l_quantity_15" id="l_quantity_15">
                <option value="100" <?php
echo ($page_post_data['l_quantity_15'] == '100') ? 'selected="selected"' : '';
?>> Show 100</option>
                <option value="1000" <?php
echo ($page_post_data['l_quantity_15'] == '1000') ? 'selected="selected"' : '';
?>> Show K</option>
                <option value="10000" <?php
echo ($page_post_data['l_quantity_15'] == '10000') ? 'selected="selected"' : '';
?>> Show 10K</option>
                <option value="100000" <?php
echo ($page_post_data['l_quantity_15'] == '100000') ? 'selected="selected"' : '';
?> > Show 100K</option>
                <option value="1000000" <?php
echo ($page_post_data['l_quantity_15'] == '1000000') ? 'selected="selected"' : '';
?> > Show M</option>
                <option value="10000000" <?php
echo ($page_post_data['l_quantity_15'] == '10000000') ? 'selected="selected"' : '';
?> > Show 10M</option>
              </select>
            </div>
            <div class="form-group col-md-1">
              <label class="control-label" for=""> Ask And Buy</label>
              <select class="form-control " name="ask_buy_for" id="ask_buy_for">
                <option value="100" <?php
echo ($page_post_data['ask_buy_for'] == '100') ? 'selected="selected"' : '';
?>> Show 100</option>
                <option value="1000" <?php
echo ($page_post_data['ask_buy_for'] == '1000') ? 'selected="selected"' : '';
?>> Show K</option>
                <option value="10000" <?php
echo ($page_post_data['ask_buy_for'] == '10000') ? 'selected="selected"' : '';
?>> Show 10K</option>
                <option value="100000" <?php
echo ($page_post_data['ask_buy_for'] == '100000') ? 'selected="selected"' : '';
?> > Show 100K</option>
                <option value="1000000" <?php
echo ($page_post_data['ask_buy_for'] == '1000000') ? 'selected="selected"' : '';
?> > Show M</option>
                <option value="10000000" <?php
echo ($page_post_data['ask_buy_for'] == '10000000') ? 'selected="selected"' : '';
?> > Show 10M</option>
              </select>
            </div>
            <div class="form-group col-md-1">
              <label class="control-label" for=""> Bid And Sell</label>
              <select class="form-control " name="bid_sell_for" id="bid_sell_for">
                <option value="100" <?php
echo ($page_post_data['bid_sell_for'] == '100') ? 'selected="selected"' : '';
?>> Show 100</option>
                <option value="1000" <?php
echo ($page_post_data['bid_sell_for'] == '1000') ? 'selected="selected"' : '';
?>> Show K</option>
                <option value="10000" <?php
echo ($page_post_data['bid_sell_for'] == '10000') ? 'selected="selected"' : '';
?>> Show 10K</option>
                <option value="100000" <?php
echo ($page_post_data['bid_sell_for'] == '100000') ? 'selected="selected"' : '';
?> > Show 100K</option>
                <option value="1000000" <?php
echo ($page_post_data['bid_sell_for'] == '1000000') ? 'selected="selected"' : '';
?> > Show M</option>
                <option value="10000000" <?php
echo ($page_post_data['bid_sell_for'] == '10000000') ? 'selected="selected"' : '';
?> > Show 10M</option>
              </select>
            </div>
            <?php
$rule_buy_sellVal = $page_post_data['rule_buy_sell'];
$rule_buy_sellVal = ($rule_buy_sellVal == '') ? 3 : $page_post_data['rule_buy_sell']
?>
            <div class="form-group col-md-1">
              <label class="control-label" for="hour"> Per Rule Height</label>
              <input type='text' class="form-control rule_buy_sell" name="rule_buy_sell"  value="<?php echo $rule_buy_sellVal; ?>" />
            </div>
            <div class="form-group col-md-1">
              <label class="control-label">Defined Height</label>
              <select class="form-control time" name="chart_height" id="chart_height">
                <option value="600" <?php
echo ($page_post_data['chart_height'] == '600') ? 'selected="selected"' : '';
?>> Normal</option>
                <option value="900" <?php
echo ($page_post_data['chart_height'] == '900') ? 'selected="selected"' : '';
?>> Large </option>
                <option value="1200" <?php
echo ($page_post_data['chart_height'] == '1200') ? 'selected="selected"' : '';
?>> Extra Large </option>
              </select>
            </div>
            <div class="form-group col-md-1">
              <label class="control-label">Bottom Height</label>
              <select class="form-control time" name="bottom_height" id="bottom_height">
                <option value="10" <?php
echo ($page_post_data['bottom_height'] == '10') ? 'selected="selected"' : '';
?>> -10</option>
                <option value="100" <?php
echo ($page_post_data['bottom_height'] == '100') ? 'selected="selected"' : '';
?>> -100</option>
                <option value="200" <?php
echo ($page_post_data['bottom_height'] == '200') ? 'selected="selected"' : '';
?>> -200 </option>
                <option value="300" <?php
echo ($page_post_data['bottom_height'] == '300') ? 'selected="selected"' : '';
?>> -300 </option>
                <option value="500" <?php
echo ($page_post_data['bottom_height'] == '500') ? 'selected="selected"' : '';
?>> -500 </option>
                <option value="1000" <?php
echo ($page_post_data['bottom_height'] == '1000') ? 'selected="selected"' : '';
?>> -1000 </option>
              </select>
            </div>
          </div>
          <div class="col-md-12 ">
          	<div class="form-group col-md-1">
            	<div class="fncy_check_box">
                  <input class="check_alll" type="checkbox">
                    <label><span></span></label>
                </div>
              <label class="fncy_check_label">All</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check0" name="check0" id="check0" class="checkbox" <?php
echo (in_array("check0", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">S VS B 15</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check1" name="check1" id="check1"  class="checkbox" <?php
echo (in_array("check1", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Buyer 15</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check2" name="check2"  id="check2" class="checkbox" <?php
echo (in_array("check2", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Seller 15</label>
            </div>            
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check3" name="check3" id="check3" class="checkbox"  <?php
echo (in_array("check3", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Last Qty 15</label>
            </div>            
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check4" name="check4" id="check4"  class="checkbox" <?php
echo (in_array("check4", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Last Time 15</label>
            </div>            
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check5" name="check5" id="check5" class="checkbox" <?php
echo (in_array("check5", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">T1 LTC TIme</label>
            </div>            
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check6" name="check6" id="check6" class="checkbox" <?php
echo (in_array("check6", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">T1 LTC</label>
            </div>            
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check7" name="check7"  id="check7" class="checkbox" <?php
echo (in_array("check7", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">T2 LTC Time</label>
            </div>            
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check8" name="check8"  id="check8" class="checkbox" <?php
echo (in_array("check8", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">T2 LTC</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check9" name="check9" id="check9" class="checkbox" <?php
echo (in_array("check9", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">T1 COT Buyers</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check10" name="check10" id="check10" class="checkbox" <?php
echo (in_array("check10", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">T1 COT Sellers</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check11" name="check11" id="check11" class="checkbox" <?php
echo (in_array("check11", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Score</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check12" name="check12" id="check12" class="checkbox" <?php
echo (in_array("check12", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Black Wall Pressure</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check13" name="check13" id="check13" class="checkbox" <?php
echo (in_array("check13", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Pressure Difference</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check14" name="check14" id="check14" class="checkbox" <?php
echo (in_array("check14", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Seven Level Pressure</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check15" name="check15"  id="check15" class="checkbox" <?php
echo (in_array("check15", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Yellow Wall pressure</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check16" name="check16" id="check16"  class="checkbox" <?php
echo (in_array("check16", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Current Market Value</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check17" name="check17" id="check17"  class="checkbox" <?php
echo (in_array("check17", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Market Depth Quantity</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check18" name="check18" id="check18" class="checkbox"  <?php
echo (in_array("check18", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Market Depth ASK</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check19" name="check19" id="check19" class="checkbox"  <?php
echo (in_array("check19", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Big Buyers</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check20" name="check20" id="check20" class="checkbox"  <?php
echo (in_array("check20", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Big Sellers</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check21" name="check21" id="check21" class="checkbox" <?php
echo (in_array("check21", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Buy Rule</label>
            </div>
            
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check22" name="check22" id="check22" class="checkbox" <?php
echo (in_array("check22", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Sell Rule</label>
            </div>
            
            
          </div>
          <div class="col-md-12 ">
          	<div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check23" name="check23" id="check23" class="checkbox" <?php
echo (in_array("check23", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Ask</label>
            </div>
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check24" name="check24" id="check24" class="checkbox" <?php
echo (in_array("check24", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Bid</label>
            </div>
            
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check25" name="check25" id="check25" class="checkbox" <?php
echo (in_array("check25", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Buy</label>
            </div>
            
            <div class="form-group col-md-1">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check26" name="check26" id="check26" class="checkbox" <?php
echo (in_array("check26", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Sell</label>
            </div>
            
            <!--<div class="form-group col-md-2">
            	<div class="fncy_check_box">
                    <input type="checkbox" value="check27" name="check27" id="check27" class="checkbox" <?php
echo (in_array("check27", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                    <label><span></span></label>
                </div>
                <label class="fncy_check_label">Big Buyer / Seller</label>
            </div>-->
            
            
            
          </div>
          <div class="col-md-12 ">
            <div class="form-group col-md-1">
              <input type="hidden" name="tab_no" id="tab_no" value="1" />
              <input type="submit" class="btn btn-success btn-block btn-sm " name="submitbtn" value="Submit">
            </div>
            <div class="form-group col-md-1"> <a type="submit" class="btn btn-primary btn-block btn-sm " name="clear" id="clear"  href="<?php echo SURL ?>admin/highchart/report/clear">clear</a> </div>
            <div class="form-group col-md-1"></div>
          </div>
        </form>
      </div>
      <div class="clearfix"></div>
      <div class="loader_parent">
        <div class="loader_overlay_main"> <img class="loader_image_main" src="http://app.digiebot.com/assets/images/loader.gif"> </div>
        <div id="container"></div>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
<!-- // Widget END -->

</div>
</div>
<?php
$width  = !empty($page_post_data['chart_width']) ? ($page_post_data['chart_width']) : 1600;
$height = !empty($page_post_data['chart_height']) ? ($page_post_data['chart_height']) : 700;
?>
<script type="text/javascript">
	$(document).ready(function(e) {
		
		/*$("#checkAll").click(function(){
		$('input:checkbox').not(this).prop('checked', this.checked);
		});*/
		
        $("body").on("click",".check_alll",function(){
			
			if($(this).is(":checked")){
				$(".fncy_check_box > input:not(.check_alll)").attr("checked","checked");	
			}else{
				$(".fncy_check_box > input:not(.check_alll)").removeAttr("checked")	;
				}
		});
		
    });
  $(document).on('click','.savesession',function(){
	  
	  $(".loaderimagbox").show();    
      var save_sesion_data     = $('.save_sesion_data').val();
	  var coin                 = $('#coin').val();
	  
	  
      $.ajax({
        'url': '<?php echo base_url(); ?>admin/highchart/save_session',
        'data': {save_sesion_data:save_sesion_data,coin:coin},
        'type': 'POST',
        success : function(data){
            var res_obj =  JSON.parse(data);
			
			if(res_obj.success==true){
			  $('.save_sesion_data').val('');	
			  $('.appendSessionData').html(res_obj.html);
			  $('.savesessionmessaage').show();
			}else{
			  $('.savesessionmessaage').hide();
			}
         }
     });            
    });

</script>



<script type="text/javascript">

jQuery(document).ready(function() {
   loadcandle();
});
/*****   On load body will open graph  *****/
function loadcandle(){

    // the button action
    var chart = $('#container').highcharts();

      for(var i = 0; i < $('input[type=checkbox]').length; i++) {
        var series = chart.series[i];
        if($('#check'+i).is(':checked')){
           series.show();
        }
        else {
          series.hide();
        }
      }
}
/*****  On load body will open graph *****/


 $(function () {
      $('.datetime_picker').datetimepicker();
 });

function load_hight_chart(black_wall_pressure,yellow_wall_pressure,pressure_diff,great_wall_price,seven_level_depth,score,last_qty_time_ago,last_200_time_ago,current_market_value){

    Highcharts.setOptions({
    time: {
        timezone: 'America/New_York'
    }
});

    <?php
if ($time == 'minut') {
    $MmnutHour = 60;
    $formate = 'h:i A';
} else {
    $MmnutHour = 3600;
    $formate = 'd M Y H:i:s';
}
?>

    Highcharts.chart('container', {

        time: {
             timezone: 'America/New_York'
        },
        title: {
            text: 'Show the data of last <b> <?php
echo $totalHours;
?> </b> <?php
echo ucfirst($time) . 's';
?> over the graph .'
        },
        width: 500,
            height: 200,
        subtitle: {
            text: ''
        },
        /* tooltip: {
            xDateFormat: '%Y-%m-%d %H:%M',
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.2f}</b><br/>'
        },*/
        xAxis: {
                            gridLineColor: '#e5eaee',
                            lineColor: '#e5eaee',
                            tickColor: '#e5eaee',
                            title:{ text:'Current Time Zone : <?php
echo date_default_timezone_get();
?>     Current Chart : <?php
echo ucfirst($time) . 's';
?> '},
                            categories: [
                            <?php
$i = 0;
$recent_count = $totalHours;
for ($x = 1; $x <= $totalHours; $x++) {
    $currentDateTime = $startDate;
    $end_dateA = date('m/d/Y h:i A', strtotime($currentDateTime));
    $dt = new DateTime($currentDateTime, new DateTimeZone($timezone));
    $dt->setTimezone(new DateTimeZone('PKT'));
    $pre_time = $dt->format('Y-m-d H:i:s');
    $second = strtotime($pre_time) + ($x * $MmnutHour);
    $end_dateB = date($formate, ($second));
    ?>
                            '<?php
echo $end_dateB;
    ?>'
                            <?php
if (++$i === $recent_count) {} else {?>,<?php }}?>
                            ]
                        },

        yAxis: {
            title: {
                text: ''
            },
             min: -<?php echo $bottom_height; ?>,
                minRange: 1,
                 tickInterval: 1,

                 plotLines: [{
                            color: 'black', // Color value
                            dashStyle: 'longdashdot', // Style of the plot line. Default to solid
                            value: 0, // Value of where the line will appear
                            width: 2 // Width of the line
                          }]
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                pointStart: 0
            }
        },
         chart: {
            renderTo: 'chart',

            width:  <?php
echo $width;
?>,
            height: <?php
echo $height;
?>,
             marginRight: 180,

        },



    legend: {
        layout: 'vertical',
         align: 'right',
        x: 0,
        verticalAlign: 'right',
        y: 30,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(255,255,255,0.25)'
    },

        series: [

        {
           name: 'S Vs B 15',
            color: 'green',
            data: [<?php echo $sellers_buyers_per_fifteen; ?>],
            negativeColor: 'red'
        },{
            name: 'Buyers 15',
            type: 'column',
             color: '#009900',
             negativeColor: '#009900',
            data: [<?php
echo $buyers_fifteen;
?>],
             tooltip: {
               valueSuffix: '  <?php
echo $price_format;
?>'
             }

        },{
            name: 'Sellers 15',
            type: 'column',
             color: ' #cc9900',
             negativeColor: ' #cc9900',
            data: [<?php
echo $sellers_fifteen;
?>],
             tooltip: {
               valueSuffix: '  <?php
echo $price_format;
?>'
             }

        },{
           name: 'Last Qty 15',
            color: '#003399',
            data: [<?php echo $last_qty_buy_vs_sell_15; ?>],
            negativeColor: '#003399'
        },{
           name: 'Last Time 15 ',
            color: '#ff6600',
            data: [<?php
echo $last_qty_time_ago_15;
?>],
            negativeColor: '#ff6600'
        },{
           name: 'T1 LTC time ',
            color: '#cc9900',
            data: [<?php
echo $last_qty_time_ago;
?>],
            negativeColor: '#cc9900'
        },{
           name: 'T1 LTC ',
           color: '#333300',
            data: [<?php
echo $last_qty_buy_vs_sell;
?>],
            negativeColor: '#999900'
        },{
           name: 'T2 LTC time  ',
           color: '#c0c0c0',
            data: [<?php
echo $last_200_time_ago;
?>],
            negativeColor: '#c0c0c0'
        },{
           name: 'T2 LTC ',
            color: '#ff8080',
            data: [<?php
echo $last_200_buy_vs_sell;
?>],
            negativeColor: '#ffe6e6'
        },{
           name: 'T1 COT Buyers ',
            color: '#009900',
             negativeColor: ' #009900',
            data: [<?php
echo $buyers;
?>]
        },
        {
           name: 'T1 COT Sellers ',
            color: '#ff3300',
             negativeColor: ' #ff3300',
            data: [<?php
echo $sellers;
?>]
        },{
           name: 'Score ',
            data: [<?php
echo $score;
?>]
        },{
            name: 'Black Wall Pressure',
              color: '#000000',
            data: [<?php
echo $black_wall_pressure;
?>],
            negativeColor: '#000000'

        }, {
            name: 'Pressure Difference',
             color: ' #cc33ff',
            data: [<?php
echo $pressure_diff;
?>],
            negativeColor: '#cc33ff'
        },  {
           name: 'Seven Level Pressure',
             color: '#9370DB',
            data: [<?php
echo $seven_level_depth;
?>],
            negativeColor: '#9370DB'
        }, {
           name: 'Yellow Wall Pressure',
             color: '#ffbf00',
            data: [<?php
echo $yellow_wall_pressure;
?>],
            negativeColor: '#ffff00'
        }, {
           name: 'Current Market Value',
           color: '#00e600',
            data: [<?php
echo $current_market_value;
?>],
            negativeColor: '#00e600'
        },{
            name: 'Market Depth Quantity',
            type: 'column',
             color: '#003399',
             negativeColor: '#003399',
            data: [<?php
echo $market_depth_quantity;
?>],
             tooltip: {
               valueSuffix: '  <?php
echo $price_format;
?>'
             }

        },{
            name: 'Market Depth Ask',
            type: 'column',
             color: '#cc0000',
             negativeColor: '#cc0000',
            data: [<?php
echo $market_depth_ask;
?>],
             tooltip: {
               valueSuffix: '  <?php
echo $price_format;
?>'
             }

        },{
           name: 'Big Buyers ',
            color: '#006655',
             negativeColor: '#006655',
            data: [<?php
echo $ask_contract;
?>]
        },{
           name: 'Big Sellers ',
            color: '#600000',
             negativeColor: '#600000',
            data: [<?php
echo $bid_contracts;
?>]
        },
		{
            name: 'Buy Rule',
            type: 'column',
             color: 'blue',
             negativeColor: 'blue',
            data: [<?php
echo $buySum;
?>],
             tooltip: {
               valueSuffix: '  <?php
//echo $price_format;
?>'
             }

        },
		{
            name: 'Sell Rule',
            type: 'column',
             color: 'red',
             negativeColor: 'red',
            data: [<?php
echo $sellSum;
?>],
             tooltip: {
               valueSuffix: '  <?php
//echo $price_format;
?>'
             }

        },
		
		{
            name: 'ASK',
            type: 'column',
             color: '#000099',
             negativeColor: 'blue',
            data: [<?php
echo $ask;
?>],
             tooltip: {
               valueSuffix: '  <?php
//echo $price_format;
?>'
             }

        },
		{
            name: 'Bid',
            type: 'column',
             color: '#ff0000',
             negativeColor: 'red',
            data: [<?php
echo $bid;
?>],
             tooltip: {
               valueSuffix: '  <?php
//echo $price_format;
?>'
             }

        }, 
		{
            name: 'Buy ',
            type: 'column',
             color: '#336699',
             negativeColor: 'blue',
            data: [<?php
echo $buy;
?>],
             tooltip: {
               valueSuffix: '  <?php
//echo $price_format;
?>'
             }

        },
		{
            name: 'Sell ',
            type: 'column',
             color: '#ff0066',
             negativeColor: 'red',
            data: [<?php
echo $sell;
?>],
             tooltip: {
               valueSuffix: '  <?php
//echo $price_format;
?>'
             }

        }/*,{
            name: 'Big Buyer / Seller ',
            type: 'column',
             color: '#990000',
             negativeColor: '#FF9999',
            data: [<?php
echo $bigBuyDivideSell;
?>],
             tooltip: {
               valueSuffix: '  <?php
//echo $price_format;
?>'
             }

        }*/],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 700
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
}

black_wall_pressure  ='';
yellow_wall_pressure ='';
pressure_diff        ='';
great_wall_price     ='';
seven_level_depth    ='';
score                ='';
last_qty_time_ago    ='';
last_200_time_ago    ='';
current_market_value ='';
market_depth_quantity='';

load_hight_chart(black_wall_pressure,yellow_wall_pressure,pressure_diff,great_wall_price,seven_level_depth,score,last_qty_time_ago,last_200_time_ago,current_market_value);
//var chart = new Highcharts.Chart(options);
</script>