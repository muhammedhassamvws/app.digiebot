<?php
$page_post_data   = $this->session->userdata('page_post_data');
$box_session_data = $this->session->userdata('page_post_data');
$form_session_session = $this->session->userdata('form_session_session');
if ($box_session_data == '') {$checboxValue = '';}

$checboxValue  = ($box_session_data=='') ? '' :  $checboxValue;
  
$dataArr  = explode(',', $chart_arr[0]);
$finalArr = array_chunk($chart_arr[0]);

$width = !empty($page_post_data['chart_width']) ? ($page_post_data['chart_width']) : 1600;
$height = !empty($page_post_data['chart_height']) ? ($page_post_data['chart_height']) : 800;






?>


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
.fncy_check_box > input[type="checkbox"]:checked+label > span {
	left: 20px;
	background: #5cb85c;
	box-shadow: -2px 1px 3px rgba(0, 0, 0, 0.4);
}
.fncy_check_box > input[type="checkbox"]:checked+label {
	background: #addaad;
}
/*---------------------------------------------------------*/
.widget-header {
	float: left;
	width: 100%;
	padding: 15px 20px;
	border-bottom: 1px solid #eee;
	margin-bottom: 35px;
}
.widget-header h2 {
	float: left;
}
.widget-header button {
	float: right;
}

.highcharts-title{
 display:none;	
}

.highcharts-button{
	display:none;
}
#container2 .highcharts-xaxis-labels{
	display:none;
} 
#container2 .highcharts-axis-title{
	display:none;
}
</style>

<div id="content">
  <div class="innerAll bg-white border-bottom"> </div>
  <br />
  
  
  
  <div class="innerAll spacing-x2"  style="">
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
      <div class="widget-header">
        <h2>Filters</h2>
            <div class="form-group col-md-1"> </div>
            <div class="form-group col-md-1"> 
              <strong style="color:#00F;">Buy</strong><br />
              <strong style="color:#F00;">Sell</strong> </div>
            <div class="form-group col-md-1">
              <label class="fncy_check_label"   style="color:#3CB371;">Level 1</label>
              <label class="fncy_check_label"   style="color:#FFA07A;">Level 1</label>
            </div>
            <div class="form-group col-md-1">
              <label class="fncy_check_label"   style="color:#20B2AA;">Level 2</label>
              <label class="fncy_check_label"   style="color:#F08080;">Level 2</label>
            </div>
            <div class="form-group col-md-1">
              <label class="fncy_check_label"   style="color:#3CB371;">Level 3</label>
              <label class="fncy_check_label"   style="color:#FF6347;">Level 3</label>
            </div>
            <div class="form-group col-md-1">
              <label class="fncy_check_label"   style="color:#6B8E23;">Level 4</label>
              <label class="fncy_check_label"   style="color:#DB7093;">Level 4</label>
            </div>
            <div class="form-group col-md-1">
              <label class="fncy_check_label"   style="color:#9ACD32;">Level 5</label>
              <label class="fncy_check_label"   style="color:#B22222;">Level 5</label>
            </div>
            
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input class="check_percentile" type="checkbox" name="perc_chart" id="perc_chart" checked="checked">
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"><b>Perc Chart</b></label>
            </div>
            
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input class="check_candle" type="checkbox" name="candle_chart" id="candle_chart" checked="checked">
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"><b>Candle Chart</b></label>
            </div>
            
            <div class="form-group col-md-1">
              &nbsp;&nbsp;&nbsp;.
            </div>
           
            
            <div class="form-group col-md-1">
              <button class="btn btn-default filter-colps">Hide</button>
            </div>
            
         
       
      </div>
      <div class="widget-body padding-bottom-none filter-colps-body"> 
        <!-- Table -->
        
        <div class="col-md-12 ">
          <form action="<?php echo SURL;?>admin/highchart/candle-report"  id="highchartform" enctype="multipart/form-data" method="post">
            <div class="form-group col-md-3"> Select the session
              <select class="form-control form_session appendSessionData" name="form_session" id="form_session" onchange="this.form.submit()">
                <option value="">Select Session</option>
                <?php foreach($session_data_array as $session){?>
                <option value="<?php echo $session->_id; ?>" <?php echo ($session->_id == $form_session_session) ? 'selected="selected"' : ''; ?>> <?php echo $session->session_name;?></option>
                <?php }?>
              </select>
              <div class="help-block with-errors sessionerror" style="display:none; color:red;">Please select the session</div>
            </div>
          </form>
          <?php  if($page_post_data!=''){ ?>
          <div class="form-group col-md-3 add_sessioninput"> Add new session
            <input type="text" class="form-control save_sesion_data"  name="save_sesion_data" id="save_sesion_data">
            <div class="help-block with-errors session_name_error" style="display:none; color:red;">Session name cannot be empty .</div>
          </div>
          <div class="form-group col-md-1"  style="padding-top: 20px;">
            <div class="fncy_check_box">
              <input type="checkbox" value="1" name="update_session" id="update_session" class="checkbox">
              <label><span></span></label>
            </div>
            <label class="fncy_check_label">Update</label>
          </div>
          <div class="form-group col-md-1  add_session"> .&nbsp; <a type="submit" class="btn btn-primary btn-block btn-sm savesession" > Save Session </a> <img class="loader_image_main abcdLoader" src="http://app.digiebot.com/assets/images/loader.gif" style="display:none; margin-top: 7px;"> </div>
          <div class="form-group col-md-1  upd_session"    style="display:none"> .&nbsp; <a type="submit" class="btn btn-primary btn-block btn-sm updsession" > Update Session </a> <img class="loader_image_main abcdLoader" src="http://app.digiebot.com/assets/images/loader.gif" style="display:none; margin-top: 7px;"> </div>
          <?php }?>
          <div class="form-group col-md-3"></div>
          <?php 
			  $tabNo =  ($tab_no=='') ? 1 : $tab_no + 1;
			?>
          <div class="form-group col-md-1 pull-right" style="background: #162450; color: white; border:hidden;"> <a href="<?php echo SURL ?>admin/highchart/next-session/<?php echo $tabNo; ?>" style="color:#FFF;" class="btn btn-block btn-sm"  target="_blank" >Session New Tab</a> </div>
      
        </div>
        <form action="<?php
echo SURL;
?>admin/highchart/candle-report"  id="highchartform_data" enctype="multipart/form-data" method="post">
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
                
                <option value="1" <?php echo ($page_post_data['price_format'] == '1') ? 'selected="selected"' : '';?>> Default </option>
                
                
                <option value="100" <?php
echo ($page_post_data['price_format'] == '100') ? 'selected="selected"' : '';
?>> IN 100</option>
                <option value="1000" <?php
echo ($page_post_data['price_format'] == '1000') ? 'selected="selected"' : '';
?>> IN K+</option>
                <option value="10000" <?php
echo ($page_post_data['price_format'] == '10000') ? 'selected="selected"' : '';
?>> IN 10 K</option>
                <option value="100000" <?php
echo ($page_post_data['price_format'] == '100000') ? 'selected="selected"' : '';
?> > IN 100 K</option>
                <option value="1000000" <?php
echo ($page_post_data['price_format'] == '1000000') ? 'selected="selected"' : '';
?> > IN M+</option>
                <option value="10000000" <?php
echo ($page_post_data['price_format'] == '10000000') ? 'selected="selected"' : '';
?> > IN 10 M</option>
              </select>
            </div>
            <div class="form-group col-md-1">
              <label class="control-label" for=""> Format T COT</label>
              <select class="form-control time" name="time_cot" id="time_cot">
                <option value="1" <?php
echo ($page_post_data['time_cot'] == '1') ? 'selected="selected"' : '';
?>> Default </option>
               
               
                <option value="100" <?php
echo ($page_post_data['time_cot'] == '100') ? 'selected="selected"' : '';
?>> IN 100</option>
                <option value="1000" <?php
echo ($page_post_data['time_cot'] == '1000') ? 'selected="selected"' : '';
?>> IN K+</option>
                <option value="10000" <?php
echo ($page_post_data['time_cot'] == '10000') ? 'selected="selected"' : '';
?>> IN 10 K</option>
                <option value="100000" <?php
echo ($page_post_data['time_cot'] == '100000') ? 'selected="selected"' : '';
?> > IN 100 K</option>
                <option value="1000000" <?php
echo ($page_post_data['time_cot'] == '1000000') ? 'selected="selected"' : '';
?> > IN M+</option>
                <option value="10000000" <?php
echo ($page_post_data['time_cot'] == '10000000') ? 'selected="selected"' : '';
?> > IN 10 M</option>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label class="control-label" for="">Big Buyers And Sellers</label>
              <select class="form-control time" name="m_buyers_sellers" id="m_buyers_sellers">
               
                <option value="1" <?php
echo ($page_post_data['m_buyers_sellers'] == '1') ? 'selected="selected"' : '';
?>> Default </option>
               
                <option value="100" <?php
echo ($page_post_data['m_buyers_sellers'] == '100') ? 'selected="selected"' : '';
?>> IN 100</option>
                <option value="1000" <?php
echo ($page_post_data['m_buyers_sellers'] == '1000') ? 'selected="selected"' : '';
?>> IN K+</option>
                <option value="10000" <?php
echo ($page_post_data['m_buyers_sellers'] == '10000') ? 'selected="selected"' : '';
?>> IN 10 K</option>
                <option value="100000" <?php
echo ($page_post_data['m_buyers_sellers'] == '100000') ? 'selected="selected"' : '';
?> > IN 100 K</option>
                <option value="1000000" <?php
echo ($page_post_data['m_buyers_sellers'] == '1000000') ? 'selected="selected"' : '';
?> > IN M+</option>
                <option value="10000000" <?php
echo ($page_post_data['m_buyers_sellers'] == '10000000') ? 'selected="selected"' : '';
?> > IN 10 M</option>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label class="control-label" for=""> Sellers And Buyers 15</label>
              <select class="form-control " name="s_b_15" id="s_b_15">
                
                <option value="1" <?php
echo ($page_post_data['s_b_15'] == '1') ? 'selected="selected"' : '';
?>> Default </option>
                
                <option value="100" <?php
echo ($page_post_data['s_b_15'] == '100') ? 'selected="selected"' : '';
?>> IN 100</option>
                <option value="1000" <?php
echo ($page_post_data['s_b_15'] == '1000') ? 'selected="selected"' : '';
?>> IN K+</option>
                <option value="10000" <?php
echo ($page_post_data['s_b_15'] == '10000') ? 'selected="selected"' : '';
?>> IN 10 K</option>
                <option value="100000" <?php
echo ($page_post_data['s_b_15'] == '100000') ? 'selected="selected"' : '';
?> > IN 100 K</option>
                <option value="1000000" <?php
echo ($page_post_data['s_b_15'] == '1000000') ? 'selected="selected"' : '';
?> > IN M+</option>
                <option value="10000000" <?php
echo ($page_post_data['s_b_15'] == '10000000') ? 'selected="selected"' : '';
?> > IN 10 M</option>
              </select>
            </div>
            <div class="form-group col-md-1">
              <label class="control-label" for=""> Last QTY 15</label>
              <select class="form-control " name="l_quantity_15" id="l_quantity_15">
                <option value="1" <?php
echo ($page_post_data['l_quantity_15'] == '1') ? 'selected="selected"' : '';
?>> Default</option>
                <option value="100" <?php
echo ($page_post_data['l_quantity_15'] == '100') ? 'selected="selected"' : '';
?>> IN 100</option>
                <option value="1000" <?php
echo ($page_post_data['l_quantity_15'] == '1000') ? 'selected="selected"' : '';
?>> IN K+</option>
                <option value="10000" <?php
echo ($page_post_data['l_quantity_15'] == '10000') ? 'selected="selected"' : '';
?>> IN 10K</option>
                <option value="100000" <?php
echo ($page_post_data['l_quantity_15'] == '100000') ? 'selected="selected"' : '';
?> > IN 100K</option>
                <option value="1000000" <?php
echo ($page_post_data['l_quantity_15'] == '1000000') ? 'selected="selected"' : '';
?> > IN M+</option>
                <option value="10000000" <?php
echo ($page_post_data['l_quantity_15'] == '10000000') ? 'selected="selected"' : '';
?> > IN 10M</option>
              </select>
            </div>
            <div class="form-group col-md-1">
              <label class="control-label" for=""> Ask And Bid</label>
              <select class="form-control " name="ask_buy_for" id="ask_buy_for">
                <option value="1" <?php
echo ($page_post_data['ask_buy_for'] == '1') ? 'selected="selected"' : '';
?>> Default</option>
                <option value="100" <?php
echo ($page_post_data['ask_buy_for'] == '100') ? 'selected="selected"' : '';
?>> IN 100</option>
                <option value="1000" <?php
echo ($page_post_data['ask_buy_for'] == '1000') ? 'selected="selected"' : '';
?>> IN K+</option>
                <option value="10000" <?php
echo ($page_post_data['ask_buy_for'] == '10000') ? 'selected="selected"' : '';
?>> IN 10 K</option>
                <option value="100000" <?php
echo ($page_post_data['ask_buy_for'] == '100000') ? 'selected="selected"' : '';
?> > IN 100 K</option>
                <option value="1000000" <?php
echo ($page_post_data['ask_buy_for'] == '1000000') ? 'selected="selected"' : '';
?> > IN M+</option>
                <option value="10000000" <?php
echo ($page_post_data['ask_buy_for'] == '10000000') ? 'selected="selected"' : '';
?> > IN 10 M</option>
              </select>
            </div>
            <div class="form-group col-md-1">
              <label class="control-label" for=""> Buy And Sell</label>
              <select class="form-control " name="bid_sell_for" id="bid_sell_for">
                <option value="1" <?php
echo ($page_post_data['bid_sell_for'] == '1') ? 'selected="selected"' : '';
?>> Default</option>
                <option value="100" <?php
echo ($page_post_data['bid_sell_for'] == '100') ? 'selected="selected"' : '';
?>> IN 100</option>
                <option value="1000" <?php
echo ($page_post_data['bid_sell_for'] == '1000') ? 'selected="selected"' : '';
?>> IN K+</option>
                <option value="10000" <?php
echo ($page_post_data['bid_sell_for'] == '10000') ? 'selected="selected"' : '';
?>> IN 10 K</option>
                <option value="100000" <?php
echo ($page_post_data['bid_sell_for'] == '100000') ? 'selected="selected"' : '';
?> > IN 100 K</option>
                <option value="1000000" <?php
echo ($page_post_data['bid_sell_for'] == '1000000') ? 'selected="selected"' : '';
?> > IN M+</option>
                <option value="10000000" <?php
echo ($page_post_data['bid_sell_for'] == '10000000') ? 'selected="selected"' : '';
?> > IN 10M</option>
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
 <option value="10" <?php
echo ($page_post_data['bottom_height'] == '50') ? 'selected="selected"' : '';
?>> -50</option>
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
              <label class="fncy_check_label"><b>Check All</b></label>
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
              <label class="fncy_check_label">T<sub>1</sub> LTC TIme</label>
            </div>
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check6" name="check6" id="check6" class="checkbox" <?php
echo (in_array("check6", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label">T<sub>1</sub> LTC</label>
            </div>
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check7" name="check7"  id="check7" class="checkbox" <?php
echo (in_array("check7", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label">T<sub>2</sub> LTC Time</label>
            </div>
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check8" name="check8"  id="check8" class="checkbox" <?php
echo (in_array("check8", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label">T<sub>2</sub> LTC</label>
            </div>
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check9" name="check9" id="check9" class="checkbox" <?php
echo (in_array("check9", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label">T<sub>1</sub> COT Buyers</label>
            </div>
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check10" name="check10" id="check10" class="checkbox" <?php
echo (in_array("check10", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label">T<sub>1</sub> COT Sellers</label>
            </div>
            
             </div>
          <div class="col-md-12 ">
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
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check27" name="check27" id="check27" class="checkbox" <?php
echo (in_array("check27", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> Buyer / Seller</label>
            </div>
            
            
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check28" name="check28" id="check28" class="checkbox" <?php
echo (in_array("check28", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> C M P</label>
            </div>
            
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check29" name="check29" id="check29" class="checkbox" <?php
echo (in_array("check29", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> B W %</label>
            </div>
            
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check30" name="check30" id="check30" class="checkbox" <?php
echo (in_array("check30", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> S L %</label>
            </div>
            
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check31" name="check31" id="check31" class="checkbox" <?php
echo (in_array("check31", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> R 5 Bid</label>
            </div>
            
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check32" name="check32" id="check32" class="checkbox" <?php
echo (in_array("check32", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> R 5 Ask</label>
            </div>
            
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check33" name="check33" id="check33" class="checkbox" <?php
echo (in_array("check33", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> 5 B S</label>
            </div>
            
            
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check34" name="check34" id="check34" class="checkbox" <?php
echo (in_array("check34", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> 15 B S</label>
            </div>
            
            </div>
            
            <div class="col-md-12 ">
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check35" name="check35" id="check35" class="checkbox" <?php
echo (in_array("check35", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> L Q B S</label>
            </div>
            
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check36" name="check36" id="check36" class="checkbox" <?php
echo (in_array("check36", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> V B %</label>
            </div>
            
            <!--   New work Goes here -->
            
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check37" name="check37" id="check37" class="checkbox" <?php
echo (in_array("check37", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label">  V B Ask %</label>
            </div>
            
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check38" name="check38" id="check38" class="checkbox" <?php
echo (in_array("check38", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> L Q T 15 %</label>
            </div>
            
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check39" name="check39" id="check39" class="checkbox" <?php
echo (in_array("check39", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> Big Buyers %</label>
            </div>
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check40" name="check40" id="check40" class="checkbox" <?php
echo (in_array("check40", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> Big Sellers %</label>
            </div>
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check41" name="check41" id="check41" class="checkbox" <?php
echo (in_array("check41", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> Buy %</label>
            </div>
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check42" name="check42" id="check42" class="checkbox" <?php
echo (in_array("check42", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> Sell %</label>
            </div>
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check43" name="check43" id="check43" class="checkbox" <?php
echo (in_array("check43", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> Ask %</label>
            </div>
            <div class="form-group col-md-1">
              <div class="fncy_check_box">
                <input type="checkbox" value="check44" name="check44" id="check44" class="checkbox" <?php
echo (in_array("check44", $box_session_data)) ? 'checked="checked"' : '';
echo $checboxValue;
?>>
                <label><span></span></label>
              </div>
              <label class="fncy_check_label"> Bid %</label>
            </div>
            
            
            
            
            
            
            
            <div class="form-group col-md-1">
              <input type="hidden" name="tab_no" id="tab_no" value="1" />
              <input type="submit" class="btn btn-success btn-block btn-sm " name="submitbtn" value="Submit">
            </div>
            <div class="form-group col-md-1"> <a type="submit" class="btn btn-primary btn-block btn-sm " name="clear" id="clear"  href="<?php echo SURL ?>admin/highchart/candle-report/clear">clear</a> </div>
            
           
            
          </div>
          
        </form>
      </div>
      <div class="clearfix"></div>
      <div class="loader_parent">
        <div class="loader_overlay_main"> <img class="loader_image_main" src="http://app.digiebot.com/assets/images/loader.gif"> </div>
        <div id="container2"></div>
        
        <br />
        <div id="container"></div> 
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
</div>


<?php
$width = !empty($page_post_data['chart_width']) ? ($page_post_data['chart_width']) : 1600;
$height = !empty($page_post_data['chart_height']) ? ($page_post_data['chart_height']) : 800;
?>
<script type="text/javascript">


$(document).ready(function(e) {
		
        $("body").on("click",".check_percentile",function(){
			
			if($(this).is(":checked")){
				$("#container2").show();	
			}else{
				$("#container2").hide();	
			}
		});
		
		
		  $("body").on("click",".check_candle",function(){
			
				if($(this).is(":checked")){
					$("#container").show();	
				}else{
					$("#container").hide();	
				}
		  });
		
		
    });




$("body").on("click",".filter-colps",function(){
    //$(this).html('Show');	
    $(".filter-colps-body").slideToggle('slow');
    $(this).text( $(this).text() == 'Show' ? "Hide" : "Show"); // using ternary operator.
});


$(function () {
      $('.datetime_picker').datetimepicker();
 });


$(document).ready(function(e) {
		
		/*$("#checkAll").click(function(){
		$('input:checkbox').not(this).prop('checked', this.checked);
		});*/
		
        $("body").on("click",".check_alll",function(){
			
			if($(this).is(":checked")){
				$("#highchartform_data .fncy_check_box > input:not(.check_alll)").attr("checked","checked");	
			}else{
				$("#highchartform_data .fncy_check_box > input:not(.check_alll)").removeAttr("checked")	;
			}
		});
		
    });

  $(document).on('click','#update_session',function(){
	 
	 if(document.getElementById('update_session').checked) {
        
		 $(".upd_session").show(); 
		 $(".add_session").hide(); 
		 $(".add_sessioninput").hide(); 
		 
     } else {
         $(".upd_session").hide(); 
		 $(".add_session").show(); 
		 $(".add_sessioninput").show(); 
	 }
	  
    });

</script> 


<script type="text/javascript">

  $(document).on('click','.updsession',function(){
	  
	  
	  $(this).hide();
	  $this  =  $(this);
	  $('.updsession').hide();
	  $(".loaderimagbox").show();  
	  $(".abcdLoader").show(); 
	 
	  
	  var formData = $('#highchartform_data').serialize();  
	  var update_session_id = $(".form_session").val(); 
	  
	  if(update_session_id=='' || update_session_id==0){
		 $('.sessionerror').show();
		 $(this).show();
		 return true;
	  }else{
		 $('.sessionerror').hide();  
		 //$(this).show();
	  }
	  
      $.ajax({
        'url': '<?php echo base_url(); ?>admin/highchart/update_session',
        'data': {formData:formData,update_session_id:update_session_id},
        'type': 'POST',
        success : function(data){
            var res_obj =  JSON.parse(data);
			
			if(res_obj.success==true){
			  $('.save_sesion_data').val('');	
			  //$('.appendSessionData').html(res_obj.html);
			  $('.savesessionmessaage').html('<strong>Updated ! </strong>Session Successfully updated .');
			  $('.savesessionmessaage').show();
			}else{
			  $('.savesessionmessaage').hide();
			}
			$this.show();
			$(".abcdLoader").hide(); 
         }
     });            
    });

</script> 
<script type="text/javascript">

  $(document).on('click','.savesession',function(){
	  
	  var save_sesion_data = $(".save_sesion_data").val(); 
	  
	  if(save_sesion_data=='' || save_sesion_data==0){
		 $('.session_name_error').show();
		 $(this).show();
		 return true;
	  }else{
		 $('.session_name_error').hide();  
		 //$(this).show();
	  }
	  
	  
	  
	  $(".loaderimagbox").show(); 
	  $(this).hide();
	  
	  var update_session = $(".update_session").val(); 
	  
	  $(".abcdLoader").show(); 
	  $this  =  $(this);   
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
			$this.show();
			$(".abcdLoader").hide(); 
         }
     });            
    });

</script> 


<?php if($hide_chart!=1){ ?>
<script src="https://code.highcharts.com/modules/boost.js"></script>
<script type="text/javascript">

$(window).on('load', function() {
  //loadcandle2();
});

jQuery(document).ready(function() {
    loadcandle();
});
/*****   On load body will open graph  *****/
function loadcandle(){

      // the button action
      var chart  = $('#container').highcharts();
	 // var chart2 = $('#container2').highcharts();

      for(var i = 0; i < $('#highchartform_data input[type=checkbox]').length; i++) {
        var series = chart.series[i];
		//var series2 = chart2.series[i];
		//alert(series);
        if($('#highchartform_data #check'+i).is(':checked')){
           series.show();
		   //series2.show();
        //}
        //else {
	
          //chart.series[i].hide();
		  //chart2.series[i].hide();
        }
      }
}
/*****  On load body will open graph *****/


 
 


function load_hight_chart(){

    Highcharts.setOptions({
    time: {
        timezone: 'America/New_York'
    }
});



    function addChartCrosshairs() {
        var chart = this;
        //initialize the X and Y component of the crosshairs (you can adjust the color and size of the crosshair lines here)
        var crosshairX = chart.renderer.path(['M', chart.plotLeft, 2, 'L', chart.plotLeft + chart.plotWidth, 2]).attr({
            stroke: '#686868',
                'stroke-width': 0.5,
				dashStyle: 'shortdot',
            zIndex: 0
        }).add()
            .toFront()
            .hide();

        var crosshairY = chart.renderer.path(['M', 2, chart.plotTop, 'L', 2, chart.plotTop + chart.plotHeight]).attr({
            stroke: '#686868',
                'stroke-width': 0.5,
				dashStyle: 'shortdot',
            zIndex: 0
        }).add()
            .toFront()
            .hide();

        $(chart.container).mousemove(function (event) {
            //onmousemove move our crosshair lines to the current mouse postion
            xpos = (event.offsetX==undefined)?event.originalEvent.layerX:event.offsetX;
            ypos = (event.offsetY==undefined)?event.originalEvent.layerY:event.offsetY;
            
            crosshairX.translate(0, ypos);
            crosshairY.translate(xpos, 0);

            //only show the crosshairs if we are inside of the plot area (the area within the x and y axis)
            if (xpos > chart.plotLeft && xpos < chart.plotLeft + chart.plotWidth && 
                ypos > chart.plotTop && ypos < chart.plotTop + chart.plotHeight) {
                crosshairX.show();
                crosshairY.show();
            } else { //if we are here then we are inside of the container, but outside of the plot area
                crosshairX.hide();
                crosshairY.hide();
            }
        });
    }

    Highcharts.chart('container', {

        time: {
             timezone: 'America/New_York'
        },
       /* title: {
            text: 'Show the data of last <b> <?php echo $totalHours;?> </b> <?php echo ucfirst($time) . 's'; ?> over the graph .'
          },*/
        width: 500,
            height: 200,
        subtitle: {
            text: ''
        },
        /* tooltip: {
            xDateFormat: '%Y-%m-%d %H:%M',
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.2f}</b><br/>'
        },*/
		
		mapNavigation: {
            enabled: true,
            enableDoubleClickZoomTo: true
        },
		
		
		
		
        xAxis: {
                            gridLineColor: '#e5eaee',
                            lineColor: '#e5eaee',
							gridLineWidth: 0.5,
							crosshair: {
									width: 0.5,
									color: '#686868',
									dashStyle: 'shortdot'
							},
                            tickColor: '#e5eaee',
							
                            title:{ text:'Current Time Zone : <?php echo $timezone; ?>     Current Chart : <?php echo ucfirst($time) . 's';?> '},
                            categories: [<?php echo $timView; ?>]
                        },

        yAxis: {
            title: {text: '' },
			 gridLineWidth: 0.5,
			 crosshair: {
						  width: 0.5,
						  color: '#686868',
						  dashStyle: 'shortdot'
						},
             min: -<?php echo $bottom_height; ?>,
                 minRange: 1,
                 tickInterval: 1,

                 plotLines: [{
                            color: 'black', // Color value
                            dashStyle: 'longdashdot', // Style of the plot line. Default to solid
                            value: 0, // Value of where the line will appear
                            width: 1 // Width of the line
                          }]
        },
		
		
		
		
		
		
		
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
			
	
	
	tooltip: {
        shared: true,
        crosshairs: true
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
			
			events: {
                load: addChartCrosshairs
            },

            width:  <?php echo $width; ?>,
            height: <?php echo $height; ?>,
            marginRight: 180,
			
			zoomType: 'x',

            resetZoomButton: {
                position: {
                    align: 'right', // by default
                    verticalAlign: 'top', // by default
                    x: -10,
                    y: 10
                },
                relativeTo: 'chart'
            }
            
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
		
		<?php if (!empty(in_array("check0", $box_session_data))){?>
        {
           name: 'S Vs B 15',
            color: 'green',
			crosshair: true,
            data: [<?php echo $sellers_buyers_per_fifteen; ?>],
            negativeColor: 'red'
        },
		<?php }?>
		<?php if (!empty(in_array("check1", $box_session_data))){?>
		{
            name: 'Buyers 15',
            type: 'column',
            color: '#009900',
			crosshair: true,
            negativeColor: '#009900',
            data: [<?php echo $buyers_fifteen; ?>],
            tooltip: {
               valueSuffix: '  <?php echo $price_format;?>'
             }

        },
		<?php }?>
		<?php if (!empty(in_array("check2", $box_session_data))){?>
		{
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

        },
		<?php }?>
		<?php if (!empty(in_array("check3", $box_session_data))){?>
		{
           name: 'Last Qty 15',
            color: '#003399',
            data: [<?php echo $last_qty_buy_vs_sell_15; ?>],
            negativeColor: '#003399'
        },
		<?php }?>
		<?php if (!empty(in_array("check4", $box_session_data))){?>
		{
           name: 'Last Time 15 ',
            color: '#ff6600',
            data: [<?php
echo $last_qty_time_ago_15;
?>],
            negativeColor: '#ff6600'
        },
		<?php }?>
		<?php if (!empty(in_array("check5", $box_session_data))){?>
		{
           name: 'T<sub>1</sub> LTC time ',
            color: '#cc9900',
            data: [<?php
echo $last_qty_time_ago;
?>],
            negativeColor: '#cc9900'
        },
		<?php }?>
		<?php if (!empty(in_array("check6", $box_session_data))){?>
		{
           name: 'T<sub>1</sub> LTC ',
           color: '#333300',
            data: [<?php
echo $last_qty_buy_vs_sell;
?>],
            negativeColor: '#999900'
        },
		<?php }?>
		<?php if (!empty(in_array("check7", $box_session_data))){?>
		{
           name: 'T<sub>2</sub> LTC time  ',
           color: '#c0c0c0',
            data: [<?php
echo $last_200_time_ago;
?>],
            negativeColor: '#c0c0c0'
        },
		<?php }?>
		<?php if (!empty(in_array("check8", $box_session_data))){?>
		{
           name: 'T<sub>2</sub> LTC ',
            color: '#ff8080',
            data: [<?php
echo $last_200_buy_vs_sell;
?>],
            negativeColor: '#ffe6e6'
        },
		<?php }?>
		<?php if (!empty(in_array("check9", $box_session_data))){?>
		{
           name: 'T<sub>1</sub> COT Buyers ',
            color: '#009900',
             negativeColor: ' #009900',
            data: [<?php
echo $buyers;
?>]
        },
		
		<?php }?>
		<?php if (!empty(in_array("check10", $box_session_data))){?>
        {
           name: 'T<sub>1</sub> COT Sellers ',
            color: '#ff3300',
             negativeColor: ' #ff3300',
            data: [<?php
echo $sellers;
?>]
        },
		
		<?php }?>
		<?php if (!empty(in_array("check11", $box_session_data))){?>
		{
           name: 'Score ',
            data: [<?php
echo $score;
?>]
        },
		<?php }?>
		<?php if (!empty(in_array("check12", $box_session_data))){?>
		{
            name: 'Black Wall Pressure',
              color: '#000000',
            data: [<?php
echo $black_wall_pressure;
?>],
            negativeColor: '#000000'

        }, 
		
		<?php }?>
		<?php if (!empty(in_array("check13", $box_session_data))){?>
		{
            name: 'Pressure Difference',
             color: ' #cc33ff',
            data: [<?php
echo $pressure_diff;
?>],
            negativeColor: '#cc33ff'
        },  
		
		<?php }?>
		<?php if (!empty(in_array("check14", $box_session_data))){?>
		{
           name: 'Seven Level Pressure',
             color: '#9370DB',
            data: [<?php
echo $seven_level_depth;
?>],
            negativeColor: '#9370DB'
        },
		
		<?php }?>
		<?php if (!empty(in_array("check15", $box_session_data))){?>
		 {
           name: 'Yellow Wall Pressure',
             color: '#ffbf00',
            data: [<?php
echo $yellow_wall_pressure;
?>],
            negativeColor: '#ffff00'
        },
		<?php }?>
		<?php if (!empty(in_array("check16", $box_session_data))){?>
		 {
			
			tooltip: {
				formatter: function () {
					return 'The value for <b>' + this.x +
						'</b> is <b>' + this.y + '</b>';
				}
           },
		   
           name: 'Current Market Value',
           color: '#00e600',
           data: [<?php echo $current_market_value; ?>],
		   
		   
		   
		   
           negativeColor: '#00e600'
        },
		<?php }?>
		<?php if (!empty(in_array("check17", $box_session_data))){?>
		
		{
            name: 'Market Depth Qty',
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

        },
		<?php }?>
		<?php if (!empty(in_array("check18", $box_session_data))){?>
		
		{
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

        },
		<?php }?>
		<?php if (!empty(in_array("check19", $box_session_data))){?>
		{
           name: 'Big Buyers ',
            color: '#006655',
             negativeColor: '#006655',
            data: [<?php
echo $ask_contract;
?>]
        },
		<?php }?>
		<?php if (!empty(in_array("check20", $box_session_data))){?>
		{
           name: 'Big Sellers ',
            color: '#600000',
             negativeColor: '#600000',
            data: [<?php
echo $bid_contracts;
?>]
        },
		
		<?php }?>
		<?php if (!empty(in_array("check21", $box_session_data))){?>
		{
            name: 'Buy Rule',
            type: 'column',
            color: 'blue',
            negativeColor: 'blue',
            data: [<?php echo $buySum; ?>],
            tooltip: {
               valueSuffix: '<?php //echo $price_format; ?>'
             }

        },
		<?php }?>
		<?php if (!empty(in_array("check22", $box_session_data))){?>
		{
            name: 'Sell Rule',
            type: 'column',
            color: 'red',
            negativeColor: 'red',
            data: [<?php echo $sellSum; ?>],
            tooltip: {
               valueSuffix: '  <?php //echo $price_format; ?>'
             }

        },
		<?php }?>
		<?php if (!empty(in_array("check23", $box_session_data))){?>
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
echo $askFormate;
?>'
             }

        },
		<?php }?>
		<?php if (!empty(in_array("check24", $box_session_data))){?>
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
echo $bidFormate;
?>'
             }

        }, 
		<?php }?>
		<?php if (!empty(in_array("check25", $box_session_data))){?>
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
echo $buyFormate;
?>'
             }

        },
		<?php }?>
		<?php if (!empty(in_array("check26", $box_session_data))){?>
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
echo $sellFormate;
?>'
             }

        },
		<?php }?>
		<?php if (!empty(in_array("check27", $box_session_data))){?>
		{
            name: 'Buyer/Seller ',
           
             color: 'blue',
             negativeColor: '#FF9999',
            data: [<?php
echo $sellers_buyers_per;
?>],
             tooltip: {
               valueSuffix: '  <?php
echo $time_cot;
?>'
             }
		}
		<?php }?>
		
		],

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
	
	 //$('#container').highcharts().xAxis[0].setExtremes(0,9);
}

load_hight_chart();


</script>
<!--Container 2 data Goes here -->
<script>


/*****   On load body will open graph Perceintile *****/
function loadcandle2(){
      // the button action
      var chart2 = $('#container2').highcharts();

      for(var i = 0; i < $('#highchartform_data input[type=checkbox]').length; i++) {
        var series2 = chart2.series[i];
		//alert(series);
        if($('#highchartform_data #check'+i).is(':checked')){
           series2.show();
        }else {
           series2.hide();
        }
      }
	  
}
/*****  On load body will open graph Perceintile*****/

function load_hight_chart_container2(){

    Highcharts.setOptions({
    time: {
        timezone: 'America/New_York'
    }
});



    function addChartCrosshairs() {
        var chart = this;
        //initialize the X and Y component of the crosshairs (you can adjust the color and size of the crosshair lines here)
        var crosshairX = chart.renderer.path(['M', chart.plotLeft, 2, 'L', chart.plotLeft + chart.plotWidth, 2]).attr({
            stroke: '#686868',
                'stroke-width': 0.5,
				dashStyle: 'shortdot',
            zIndex: 0
        }).add()
            .toFront()
            .hide();

        var crosshairY = chart.renderer.path(['M', 2, chart.plotTop, 'L', 2, chart.plotTop + chart.plotHeight]).attr({
            stroke: '#686868',
                'stroke-width': 0.5,
				dashStyle: 'shortdot',
            zIndex: 0
        }).add()
            .toFront()
            .hide();

        $(chart.container).mousemove(function (event) {
            //onmousemove move our crosshair lines to the current mouse postion
            xpos = (event.offsetX==undefined)?event.originalEvent.layerX:event.offsetX;
            ypos = (event.offsetY==undefined)?event.originalEvent.layerY:event.offsetY;
            
            crosshairX.translate(0, ypos);
            crosshairY.translate(xpos, 0);

            //only show the crosshairs if we are inside of the plot area (the area within the x and y axis)
            if (xpos > chart.plotLeft && xpos < chart.plotLeft + chart.plotWidth && 
                ypos > chart.plotTop && ypos < chart.plotTop + chart.plotHeight) {
                crosshairX.show();
                crosshairY.show();
            } else { //if we are here then we are inside of the container, but outside of the plot area
                crosshairX.hide();
                crosshairY.hide();
            }
        });
    }

    Highcharts.chart('container2', {

        time: {
             timezone: 'America/New_York'
        },
        title: {
            text: 'Show the data of last <b> <?php echo $totalHours;?> </b> <?php echo ucfirst($time) . 's'; ?> over the graph .'
          },
        width: 500,
            height: 200,
        subtitle: {
            text: ''
        },
		mapNavigation: {
            enabled: true,
            enableDoubleClickZoomTo: true
        },
		
        xAxis: {
                            gridLineColor: '#e5eaee',
                            lineColor: '#e5eaee',
							gridLineWidth: 0.5,
							crosshair: {
									width: 0.5,
									color: '#686868',
									dashStyle: 'shortdot'
							},
                            tickColor: '#e5eaee',
							
                            title:{ text:'Current Time Zone : <?php echo $timezone; ?>     Current Chart : <?php echo ucfirst($time) . 's';?> '},
                            categories: [<?php echo $timView; ?>]
                        },

        yAxis: {
            title: {text: '' },
			 gridLineWidth: 0.5,
			 crosshair: {
						  width: 0.5,
						  color: '#686868',
						  dashStyle: 'shortdot'
						},
             min: -<?php echo 5;//$bottom_height; ?>,
                 minRange: 1,
                 tickInterval: 1,

                 plotLines: [{
                            color: 'black', // Color value
                            dashStyle: 'longdashdot', // Style of the plot line. Default to solid
                            value: 0, // Value of where the line will appear
                            width: 1 // Width of the line
                          }]
        },
		
		
		
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
			
	
	
	tooltip: {
        shared: true,
        crosshairs: true
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
			
			events: {
                load: addChartCrosshairs
            },

            width:  <?php echo $width; ?>,
            height: 400,
            marginRight: 180,
			
			zoomType: 'x',
            resetZoomButton: {
                position: {
                    align: 'right', // by default
                    verticalAlign: 'top', // by default
                    x: -10,
                    y: 10
                },
                relativeTo: 'chart'
            }  
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
		
	
		<?php if (!empty(in_array("check28", $box_session_data))){?>
		{
             name: 'Current Market Price',
             color: 'green',
             //negativeColor: '#FF9999',
             data: [<?php echo $current_market_value; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check29", $box_session_data))){?>
	    {
             name: 'Black Wall Percentile ',
             color: 'black',
             //negativeColor: '#FF9999',
             data: [<?php echo $black_wall_percentile; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check30", $box_session_data))){?>
	    {
             name: 'Seven Level Percentile ',
             color: '#cc9900',
             //negativeColor: '#FF9999',
             data: [<?php echo $sevenlevel_percentile; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check31", $box_session_data))){?>
		{
             name: 'Rolling Five Bid',
             color: '#333300',
             //negativeColor: '#FF9999',
             data: [<?php echo $rolling_five_bid_percentile; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check32", $box_session_data))){?>
		{
             name: 'Rolling Five Ask ',
             color: '#b30000',
             //negativeColor: '#FF9999',
             data: [<?php echo $rolling_five_ask_percentile; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check33", $box_session_data))){?>
		{
             name: 'Five Buy Sell ',
             color: '#00e600',
             //negativeColor: '#FF9999',
             data: [<?php echo $five_buy_sell_percentile; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check34", $box_session_data))){?>
		{
             name: 'Fifteen Buy Sell ',
             color: '#003300',
             //negativeColor: '#FF9999',
             data: [<?php echo $fifteen_buy_sell_percentile; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check35", $box_session_data))){?>
		{
             name: 'Last Qty Buy Sell ',
             color: '#ffcc00',
             negativeColor: '#FF9999',
             data: [<?php echo $last_qty_buy_sell_percentile; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check36", $box_session_data))){?>
		{
             name: 'Last Qty Time ',
             color: '#000099',
             negativeColor: '#FF9999',
             data: [<?php echo $last_qty_time_percentile; ?>],
        },
		<?php }?>
		
		<!------ New task goes here --->
		
		<?php if (!empty(in_array("check36", $box_session_data))){?>
		{
             name: 'Virtual Barrier %',
             color: '#003300',
             negativeColor: '#66ff66',
             data: [<?php echo $virtual_barrier_percentile; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check37", $box_session_data))){?>
		{
             name: 'Virtual Barrier Ask % ',
             color: '#800000',
             negativeColor: '#ff8080',
             data: [<?php echo $virtual_barrier_percentile_ask; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check38", $box_session_data))){?>
		{
             name: 'Last Qty Time 15 %',
             color: '#ffbb33',
             negativeColor: '#ffe6b3',
             data: [<?php echo $last_qty_time_fif_percentile; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check39", $box_session_data))){?>
		{
             name: 'Big Buyers Percentile ',
             color: '#d98cd9',
             negativeColor: '#993399',
             data: [<?php echo $big_buyers_percentile; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check40", $box_session_data))){?>
		{
             name: 'Big Sellers Percentile',
             color: '#9fdf9f',
             negativeColor: '#339933',
             data: [<?php echo $big_sellers_percentile; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check41", $box_session_data))){?>
		{
             name: 'Buy Percentile ',
             color: '#0099cc',
             negativeColor: '#66d9ff',
             data: [<?php echo $buy_percentile; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check42", $box_session_data))){?>
		{
             name: 'Sell Percentile ',
             color: '#dfbf9f',
             negativeColor: '#996633',
             data: [<?php echo $sell_percentile; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check43", $box_session_data))){?>
		{
             name: 'Ask Percentile ',
             color: '#990033',
             negativeColor: '#ff6699',
             data: [<?php echo $ask_percentile; ?>],
        },
		<?php }?>
		<?php if (!empty(in_array("check44", $box_session_data))){?>
		{
             name: 'Bid Percentile ',
             color: '#336600',
             negativeColor: '#bfff80',
             data: [<?php echo $bid_percentile; ?>],
        },
		<?php }?>
		
		
		
		<!--- New taskt Goes end here -->
		],

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
	 //$('#container').highcharts().xAxis[0].setExtremes(0,9);
}
load_hight_chart_container2();

</script>
<?php }?>
