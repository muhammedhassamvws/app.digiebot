<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php  echo SURL ?>assets/dist/jquery-asPieProgress.js"></script>
<style>
.Input_text_s {
	float: left;
	width: 100%;
	position: relative;
}
.Input_text_s > label {
	float: left;
	width: 100%;
	color: #000;
	font-size: 14px;
}
.multiselect-native-select .btn-group {
	float: left;
	width: 100% !important;
}
.multiselect-native-select .btn-group button {
	background: #fff;
	border: 1px solid #ccc;
	border-radius: 5px !important;
}
.Input_text_s > i {
	position: absolute;
	right: 8px;
	bottom: 4px !important;
	height: 20px;
	top: auto;
}
.ax_2, .ax_3, .ax_4, .ax_5 {
	padding-bottom: 35px !important;
}
.col-radio {
	float: left;
	width: 100%;
	position: relative;
	padding-left: 30px;
	height: 30px;
}
.col-radio span {
	position: absolute;
	left: 0;
	width: 30px;
	height: 30px;
	top: 0;
	font-size: 23px;
	line-height: 0;
}
.col-radio input[type="radio"] {
	position: absolute;
	left: 0;
	opacity: 0;
}
.col-radio input[type="radio"]:checked + span i.fa.fa-dot-circle-o {
	display: block;
	color: #72af46;
}
.col-radio input[type="radio"]:checked + span i.fa.fa-circle-o {
	display: none;
}
.col-radio span i.fa.fa-dot-circle-o {
	display: none;
}
.col-radio label {
	color: #000;
	font-size: 15px;
	padding-top: 1px;
}
.Input_text_btn > a > i, .Input_text_btn > button > i {
	margin-right: 10px;
}
.coin_symbol {
}
.coin_symbol {
	color: #fff;
	font-weight: bold;
	font-size: 14px;
	float: left;
	width: 100%;
	padding: 12px 20px;
	background: #31708f;
	border-radius: 7px 7px 0 0;
	margin-top: 25px;
}
.coin_symbol:first-child {
	margin-top: 0;
}
table.table.table-stripped {
	border: 1px solid #2d4c5a;
}
table.table.table-stripped tr.theadd {
	background: #ccc;
	color: #000;
}
table.table.table-stripped tr.theadd td {
	border: 1px solid #2d4c5a;
	font-weight: bold;
	font-size: 13px;
}
table.table.table-stripped tr td {
	border: 1px solid #2d4c5a;
	vertical-align: middle;
}
table.table.table-stripped tr td.heading {
	background: #ccc;
	color: #000;
	font-size: 13px;
	font-weight: bold;
}
table.table.table-stripped tr:hover {
	background: rgba(0,0,0,0.04);
}
table.table.table-stripped tr.theadd:hover {
	background: rgba(204,204,204,1);
}
tr.coin_symbol td {
	border: none !important;
}
table.table.table-stripped tr td .table-stripped-column tr td {
	border: none;
	padding-bottom: 0;
	padding-top: 15px;
	background: #ccc;
	color: black;
}
.modal-dialog {
	overflow-y: initial !important
}
.Opp {
	height: 550px;
	padding-left: 10px;
	overflow-y: auto;
	overflow-x: hidden;
}
.totalAvg {
	padding-top: 44px;
}
.Input_text_btn {
	padding: 25px 0 0;
}
</style>

<?php //echo "<pre>";  print_r($htmlViewTimeAll); exit; ?>
<!--<canvas id="canvas" width="100" height="100" style=""></canvas>-->
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Reports</h1>
  <div class=" pull-right alert alert-warning" style=" margin-top: -56px; background: #5c678a;color: white;"> <?php echo date("F j, Y, g:i a").'&nbsp;&nbsp;  <b>'.date_default_timezone_get().' (GMT + 0)'.'<b />' ?></div>
  <div class="innerAll bg-white border-bottom">
    <div class="pull-right" style="padding-right: 12px; padding-top: 0px;">
           
    </div>
    
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
    <?php $filter_user_data = $this->session->userdata('filter_order_data');?>
    <div class="widget widget-inverse">
      <div class="widget-body">
        <form method="POST" action="<?php echo SURL; ?>admin/trigger_rule_reports/chart_report_listing">
          <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 ax_1">
            <div class="col-xs-12 col-sm-12 col-md-3 ax_2">
              <div class="Input_text_s">
                <label>Filter Coin: </label>
                <select id="filter_by_coin" multiple="multiple" name="filter_by_coin[]" type="text" class=" filter_by_name_margin_bottom_sm">
                  <option value="NCASHBTC" <?php if (in_array('NCASHBTC', $filter_user_data['filter_by_coin'])) {?> selected <?php }?>>NCASHBTC</option>
                  <option value="TRXBTC" <?php if (in_array('TRXBTC', $filter_user_data['filter_by_coin'])) {?> selected <?php }?>>TRXBTC</option>
                  <option value="EOSBTC" <?php if (in_array('EOSBTC', $filter_user_data['filter_by_coin'])) {?> selected <?php }?>>EOSBTC</option>
                  <option value="POEBTC" <?php if (in_array('POEBTC', $filter_user_data['filter_by_coin'])) {?> selected <?php }?>>POEBTC</option>
                  <option value="NEOBTC" <?php if (in_array('NEOBTC', $filter_user_data['filter_by_coin'])) {?> selected <?php }?>>NEOBTC</option>
                  <option value="ETCBTC" <?php if (in_array('ETCBTC', $filter_user_data['filter_by_coin'])) {?> selected <?php }?>>ETCBTC</option>
                  <option value="XRPBTC" <?php if (in_array('XRPBTC', $filter_user_data['filter_by_coin'])) {?> selected <?php }?>>XRPBTC</option>
                  <option value="XEMBTC" <?php if (in_array('XEMBTC', $filter_user_data['filter_by_coin'])) {?> selected <?php }?>>XEMBTC</option>
                  <option value="XLMBTC" <?php if (in_array('XLMBTC', $filter_user_data['filter_by_coin'])) {?> selected <?php }?>>XLMBTC</option>
                  <option value="QTUMBTC" <?php if (in_array('QTUMBTC', $filter_user_data['filter_by_coin'])) {?> selected <?php }?>>QTUMBTC</option>
                  <option value="ZENBTC" <?php if (in_array('ZENBTC', $filter_user_data['filter_by_coin'])) {?> selected <?php }?>>ZENBTC</option>
                  <option value="BTCUSDT" <?php if (in_array('BTCUSDT', $filter_user_data['filter_by_coin'])) {?> selected <?php }?>>BTCUSDT</option>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-2 ax_3">
              <div class="Input_text_s">
                <label>Filter Mode: </label>
                <select id="filter_by_mode" name="filter_by_mode" type="text" class="form-control filter_by_name_margin_bottom_sm">
                  <option value="">Search By Mode</option>
                  <option value="live"<?=(($filter_user_data['filter_by_mode'] == "live") ? "selected" : "")?>>Live</option>
                  <option value="test_live"<?=(($filter_user_data['filter_by_mode'] == "test_live") ? "selected" : "")?>>Test</option>
                  <option value="test_simulator"<?=(($filter_user_data['filter_by_mode'] == "test_simulator") ? "selected" : "")?>>Simulator</option>
                </select>
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-2 ax_3">
              <div class="Input_text_s">
                <label>Filter Days/Hours: </label>
                <select id="filter_hour_day" name="filter_hour_day" type="text" class="form-control filter_by_name_margin_bottom_sm">
                  <option value="">Search By Mode</option>
                  <option value="hours"<?=(($filter_user_data['filter_hour_day'] == "hours") ? "selected" : "")?>>Hours</option>
                  <option value="days"<?=(($filter_user_data['filter_hour_day'] == "days") ? "selected" : "")?>>Days</option>
                 
                </select>
              </div>
            </div>
            
              <div class="col-xs-12 col-sm-12 col-md-2 ax_3">
              <div class="Input_text_s">
                <label>Oppertunity Status: </label>
                <select id="opp_status" name="opp_status" type="text" class="form-control filter_by_name_margin_bottom_sm">
                  <option value="sold"<?=(($filter_user_data['opp_status'] == "sold") ? "selected" : "")?>>Sold</option>
                  <option value="open"<?=(($filter_user_data['opp_status'] == "open") ? "selected" : "")?>>Open</option>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-2  ax_8" style=" min-height: 60px;">
              <div class="Input_text_s" id="triggerFirst" <?php if ($filter_user_data['group_filter'] == 'rule_group') {?>style="display:block;" <?php } else {?><?php }?>>
                <label>Filter Trigger: </label>
                <select id="filter_by_trigger"  name="filter_by_trigger" type="text" class="form-control  filter_by_trigger">
                  <option value="barrier_trigger" <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_trigger') {?> selected <?php }?>>BARRIER TRIGGER</option>
                  <option value="barrier_percentile_trigger" <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_percentile_trigger') {?> selected <?php }?>>Barrier Percentile Trigger</option>
                </select>
              </div>
            </div>
            </div>
 <div class="row">
   <div class="col-xs-12 col-sm-12 col-md-12 ax_1">
            
            <div class="col-xs-12 col-sm-12 col-md-3 ax_4">
              <div class="Input_text_s">
                <label>From Date Range: </label>
                <input id="filter_by_start_date" name="filter_by_start_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_start_date']) ? $filter_user_data['filter_by_start_date'] : "")?>" autocomplete="off">
                <i class="glyphicon glyphicon-calendar"></i> </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 ax_5">
              <div class="Input_text_s">
                <label>To Date Range: </label>
                <input id="filter_by_end_date" name="filter_by_end_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_end_date']) ? $filter_user_data['filter_by_end_date'] : "")?>" autocomplete="off">
                <i class="glyphicon glyphicon-calendar"></i> </div>
            </div>
            <script type="text/javascript">
                   $(function () {
                       $('.datetime_picker').datetimepicker();
                   });
               </script>
          
            
            <!-- End Hidden Searches -->
            <div class="col-xs-12 col-sm-12 col-md-3  ax_8 filter_by_level"  style=" min-height: 60px;" id="filter_by_level">
              <div class="Input_text_s filter_by_level"   <?php if ($filter_user_data['group_filter'] == 'rule_group') {?> <?php  } else {?>  <?php  }?>>
                <label>Filter Level: </label>
                <select id="filter_by_level_select" name="filter_by_level[]" multiple="multiple" type="text" class="form-control filter_by_name_margin_bottom_sm filter_by_level">
                  <option value="level_1" <?php if (in_array('level_1', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 1</option>
                  <option value="level_2" <?php if (in_array('level_2', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 2</option>
                  <option value="level_3" <?php if (in_array('level_3', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 3</option>
                  <option value="level_4" <?php if (in_array('level_4', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 4</option>
                  <option value="level_5" <?php if (in_array('level_5', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 5</option>
                  <option value="level_6" <?php if (in_array('level_6', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 6</option>
                  <option value="level_7" <?php if (in_array('level_7', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 7</option>
                  <option value="level_8" <?php if (in_array('level_8', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 8</option>
                  <option value="level_9" <?php if (in_array('level_9', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 9</option>
                  <option value="level_10"<?php if (in_array('level_10', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 10</option>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3  ax_8 filter_by_rule" style=" min-height: 60px;" id="filter_by_rule">
              <div class="Input_text_s filter_by_rule"  <?php if ($filter_user_data['group_filter'] == 'rule_group') {?> <?php } else {?>  <?php }?>>
                <label>Filter Rule: </label>
                <select id="filter_by_rule_select" name="filter_by_rule[]"  multiple="multiple" type="text" class="form-control filter_by_name_margin_bottom_sm filter_by_rule">
                  <option value="1" <?php if (in_array(1, $filter_user_data['filter_by_rule'])) {?> selected <?php }?>>Rule 1</option>
                  <option value="2" <?php if (in_array(2, $filter_user_data['filter_by_rule'])) {?> selected <?php }?>>Rule 2</option>
                  <option value="3" <?php if (in_array(3, $filter_user_data['filter_by_rule'])) {?> selected <?php }?>>Rule 3</option>
                  <option value="4" <?php if (in_array(4, $filter_user_data['filter_by_rule'])) {?> selected <?php }?>>Rule 4</option>
                  <option value="5" <?php if (in_array(5, $filter_user_data['filter_by_rule'])) {?> selected <?php }?>>Rule 5</option>
                  <option value="6" <?php if (in_array(6, $filter_user_data['filter_by_rule'])) {?> selected <?php }?>>Rule 6</option>
                  <option value="7" <?php if (in_array(7, $filter_user_data['filter_by_rule'])) {?> selected <?php }?>>Rule 7</option>
                  <option value="8" <?php if (in_array(8, $filter_user_data['filter_by_rule'])) {?> selected <?php }?>>Rule 8</option>
                  <option value="9" <?php if (in_array(9, $filter_user_data['filter_by_rule'])) {?> selected <?php }?>>Rule 9</option>
                  <option value="10"<?php if (in_array(10, $filter_user_data['filter_by_rule'])) {?> selected <?php }?>>Rule 10</option>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-1 ax_9">
              <div class="Input_text_btn">
                <label></label>
                <button id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
                 <span class="ax_10"></span>
              </div>
            </div>
          </div>
          </div>
        </form>
      </div>
    
    <!-- Widget -->
    <style>
/* STYLES FOR PROGRESSBARS */

.progress-radial, .progress-radial * {
	-webkit-box-sizing: content-box;
	-moz-box-sizing: content-box;
	box-sizing: content-box;
}

/* -------------------------------------
 * Bar container
 * ------------------------------------- */
.progress-radial {
  float: left;
  margin-right: 4%;
  position: relative;
  width: 55px;
  border-radius: 50%;
}
.progress-radial:first-child {
  margin-left: 4%;
}
/* -------------------------------------
 * Optional centered circle w/text
 * ------------------------------------- */
.progress-radial .overlay {
  position: absolute;
  width: 80%;
  background-color: #f0f0f0;
  border-radius: 50%;
  font-size: 14px;
    top:50%;
    left:50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
}

.progress-radial .overlay p{
    position: absolute;
    line-height: 40px;
    text-align: center;
    width: 100%;
    top:50%;
    margin-top: -20px;
}

/* -------------------------------------
 * Mixin for progress-% class
 * ------------------------------------- */
.progress-green-0 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(0deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(90deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-5 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(342deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(108deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-10 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(324deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(126deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-15 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(306deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(144deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-20 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(288deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(162deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-25 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(270deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(180deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-30 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(252deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(198deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-35 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(234deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(216deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-40 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(216deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(234deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-45 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(198deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(252deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-50 {
  background-image: -webkit-linear-gradient(180deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(-90deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-55 {
  background-image: -webkit-linear-gradient(162deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(-72deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-60 {
  background-image: -webkit-linear-gradient(144deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(-54deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-65 {
  background-image: -webkit-linear-gradient(126deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(-36deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-70 {
  background-image: -webkit-linear-gradient(108deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(-18deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-75 {
  background-image: -webkit-linear-gradient(90deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(0deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-80 {
  background-image: -webkit-linear-gradient(72deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(18deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-85 {
  background-image: -webkit-linear-gradient(54deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(36deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-90 {
  background-image: -webkit-linear-gradient(36deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(54deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-95 {
  background-image: -webkit-linear-gradient(18deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(72deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-green-100 {
  background-image: -webkit-linear-gradient(0deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #4caf50 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #4caf50 50%, #f0f0f0 50%, #f0f0f0);
}





.progress-red-0 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(0deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(90deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-5 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(342deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(108deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-10 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(324deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(126deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-15 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(306deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(144deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-20 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(288deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(162deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-25 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(270deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(180deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-30 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(252deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(198deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-35 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(234deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(216deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-40 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(216deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(234deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-45 {
  background-image: -webkit-linear-gradient(0deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(198deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f0f0f0 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(252deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-50 {
  background-image: -webkit-linear-gradient(180deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(-90deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-55 {
  background-image: -webkit-linear-gradient(162deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(-72deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-60 {
  background-image: -webkit-linear-gradient(144deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(-54deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-65 {
  background-image: -webkit-linear-gradient(126deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(-36deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-70 {
  background-image: -webkit-linear-gradient(108deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(-18deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-75 {
  background-image: -webkit-linear-gradient(90deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(0deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-80 {
  background-image: -webkit-linear-gradient(72deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(18deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-85 {
  background-image: -webkit-linear-gradient(54deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(36deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-90 {
  background-image: -webkit-linear-gradient(36deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(54deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-95 {
  background-image: -webkit-linear-gradient(18deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(72deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}

.progress-red-100 {
  background-image: -webkit-linear-gradient(0deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), -webkit-linear-gradient(180deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
  background-image: linear-gradient(90deg, #f44336 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, #f44336 50%, #f0f0f0 50%, #f0f0f0);
}


.mypai_prog {
    display: inline-block;
    padding: 2px;
}
.tdmypi{
	padding:2px;
	text-align:center;
}

div.pie_progress__label {
    position: absolute;
    top: 20px;
    left: 8px;
}

.pie_progress {
    position: relative;
    width: 60px;
    text-align: center;
    margin-left: 39px;
}
.highcharts-credits{
	display:none;
}
.highcharts-title{
	display:none;
}
</style>

<style>

g.highcharts-axis-labels.highcharts-xaxis-labels tspan.trade {
    display: none !important;
}

g.highcharts-axis-labels.highcharts-xaxis-labels tspan.order {
    display: none!important;
}

g.highcharts-axis-labels.highcharts-xaxis-labels tspan.coinname {
    display: none!important;
}
g.highcharts-axis-labels highcharts-xaxis-labels tspan.utc{
    display: none!important;
} 

</style>

    
    <div class="widget widget-inverse">
      <div class="widget-body padding-bottom-none">
      <div class="loader_parent">
        <!--<div class="loader_overlay_main"> <img class="loader_image_main" src="https://app.digiebot.com/assets/images/loader.gif"> </div>-->
        <!--<div id="container2"></div>-->
        
        <br />
        <div id="container"></div> 
        
         <div id="container2"></div> 
      </div>
  </div>
  </div>
  </div>
</div>
</div>
<script src="https://code.highcharts.com/modules/boost.js"></script>
<script src="https://app.digiebot.com/assets/js/highcharts/highcharts.js"></script>
<script src="https://app.digiebot.com/assets/js/highcharts/series-label.js"></script>
<script src="https://app.digiebot.com/assets/js/highcharts/exporting.js"></script>
<script src="https://app.digiebot.com/assets/js/highcharts/export-data.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#filter_by_coin, #filter_by_rule_select, #filter_by_level_select').multiselect({
          includeSelectAllOption: true,
          buttonWidth: 435.7,
          enableFiltering: true
        });
    });
</script> 
<script>
$(document).ready(function(e){
   var filter_by_trigger = $("#filter_by_trigger").val();
   
  
   
    if (filter_by_trigger == 'barrier_trigger') {
        $(".filter_by_level").hide();
        $(".filter_by_rule").show();
    }else if(filter_by_trigger == 'barrier_percentile_trigger'){
        $(".filter_by_level").show();
        $(".filter_by_rule").hide();
    }else{
        $(".filter_by_level").hide();
        $(".filter_by_rule").hide();
    }
});


$("body").on("change","#filter_by_trigger",function(e){
   var filter_by_trigger = $("#filter_by_trigger").val();
   
   $(".filter_by_level").hide();
   $(".filter_by_rule").hide();
   
   if(filter_by_trigger =='barrier_percentile_trigger'){
	    $(".filter_by_level").show();
		$(".filter_by_rule").hide();
   }
  
   if(filter_by_trigger =='barrier_trigger'){
	    $(".filter_by_rule").show();
		$(".filter_by_level").hide();
   }
   if(filter_by_trigger =='no'){
	    $(".filter_by_level").hide();
        $(".filter_by_rule").hide();
    }
});
</script>




<script type="text/javascript">

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
        //if($('#highchartform_data #check'+i).is(':checked')){
           series.show();
		   //series2.show();
        //}
        //else {
	
          //chart.series[i].hide();
		  //chart2.series[i].hide();
        //}
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
        /*title: {
            text: 'Show the data of last <b> <?php echo $totalHours;?> </b> <?php echo ucfirst($time) . 's'; ?> over the graph .'
          },*/
        width: 500,
            height: 300,
        subtitle: {
            text: 'Oppertunity Report'
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
                            title:{ text:'Current Date Time  : <?php echo  date("F j, Y, g:i a"); ?>  '},
                            categories: [<?php echo $htmlViewTimeAll; ?>]
                        },

        yAxis: {
            title: {text: '' },
			 gridLineWidth: 0.5,
			 crosshair: {
						  width: 0.5,
						  color: '#686868',
						  dashStyle: 'shortdot'
						},
             min: - 15 <?php echo $bottom_height; ?>,
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
	
	<?php $explodeArr=  explode(',',$htmlViewTimeAll); ?>
	
	
	 /*tooltip: {
                formatter: function() {
				  <?php foreach($explodeArr as $row){?>	
                       return   <?php echo $row; ?>;
				  <?php }?>
                }
				
				
            },*/
			
	   
	  /* tooltip: {
                formatter: function() {
                    alert(  '<b>' + this.series.name +'</b><br/>' +
                        Highcharts.dateFormat('%e - %b - %Y',
                                              new Date(this.x))
                    + ' date, ' + this.y + ' Kg.');
                }
            },*/
			
			///https://www.highcharts.com/forum/viewtopic.php?t=37130
			
	 /* tooltip: {   
		 useHTML: true,
			formatter: function() {
			  const points = this.points;
			  // Get content for each tooltip
			  let tooltips = points.map(v => {
				return `
				  <span style="color: ${v.color}">•</span> ${v.series.name}: <b>${v.y}°C</b>
				`
			  });
			  tooltips = tooltips.concat('');
			  // Return array of tooltip content html strings
			  return tooltips;
			}
	  }
		 */
		 		
			
			
	

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

            width:  1550<?php echo $width; ?>,
            height: 500<?php echo $height; ?>,
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
		
		
        {
            name: 'Oppertunity',
            type: 'column',
            color: '#009900',
			crosshair: true,
            negativeColor: '#B22222',
            data: [<?php echo $column_arr; ?>],
            tooltip: {
               valueSuffix: '  <?php echo $price_format;?>'
             }
        },
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
	
	 Highcharts.chart('container2', {

        time: {
             timezone: 'America/New_York'
        },
        width: 500,
            height: 300,
        subtitle: {
            text: 'Trades Profit'
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
                            title:{ text:'Current Date Time  : <?php echo  date("F j, Y, g:i a"); ?>  '},
                            categories: [<?php echo $htmlViewTimeAllForSecond; ?>]
               },

        yAxis: {
             title: {text: '' },
			 gridLineWidth: 0.5,
			 labels:{
                        enabled:false//default is true
                    },
			 crosshair: {
						  width: 0.5,
						  color: '#686868',
						  dashStyle: 'shortdot'
						},
                 min: - <?php echo $lowestval; ?> <?php //echo $bottom_height; ?>,
                 minRange: '',
                 tickInterval: '',
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

            width:  1550<?php echo $width; ?>,
            height: 500<?php echo $height; ?>,
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
		
		
        {
            name: 'Trades Profit',
            //type: 'column',
            color: '#009900',
			crosshair: true,
            negativeColor: '#B22222',
            data: [<?php echo $finalValArr; ?>],
            tooltip: {
               valueSuffix: '  <?php echo $price_format;?>'
             }
        },
		
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