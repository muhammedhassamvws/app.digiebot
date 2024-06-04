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

<style>
	/* New CSS Classes for Labels and Boxes */
.label-box-pending{
  display:inline-block;
  width: 22px;
  float:left;
  height: 22px;
  border-radius: 0.7rem;
  background: #f0ad4e;
}
.label-box-approved{
  display:inline-block;
  width: 22px;
  float:left;
  height: 22px;
  border-radius: 0.7rem;
  background: #c3e6cb;
}
.label-box-rejected{
  display:inline-block;
  width: 22px;
  float:left;
  height: 22px;
  border-radius: 0.7rem;
  background: #d9534f;
}
.label-box-requested{
  display:inline-block;
  width: 22px;
  float:left;
  height: 22px;
  border-radius: 0.7rem;
  background: #5bc0de;
}
.status-box-pending{
  color: white !important;
  text-align:center;
  background-color:#f0ad4e;
}
.status-box-approved{
  color: white !important;
  text-align:center;
  background-color:#72af46;
}
.status-box-rejected{
  color: white !important;
  text-align:center;
  background-color:#d9534f;
}
.status-box-requested{
  color: white !important;
  text-align:center;
  background-color:#5bc0de;
}
.table_align_head{
	text-align: left !important;
}
.badge {
    background-color: black!important;
}
</style>

<?php //echo "<pre>";  print_r($full_arr); exit; ?>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Reports</h1>
  <div class="innerAll bg-white border-bottom">
    <div class="pull-right" style="padding-right: 12px; padding-top: 8px;">
      <div class=" pull-right alert alert-warning" style=" margin-top: -10px; background: #5c678a;color: white;"> <?php echo date("F j, Y, g:i a").'&nbsp;&nbsp;  <b>'.date_default_timezone_get().' (GMT + 0)'.'<b />' ?></div>     
    
    </div>
    <ul class="menubar">
      <li class=""><a href="<?php echo SURL; ?>/admin/trigger_rule_reports/coin_report_listing">Reports</a></li>
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
    <?php $filter_user_data = $this->session->userdata('filter_order_data');?>
    <div class="widget widget-inverse">
      <div class="widget-body">
        <form method="POST" action="<?php echo SURL; ?>admin/trigger_rule_reports/coin_report_listing">
          <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 ax_1">
            <div class="col-xs-12 col-sm-12 col-md-3 ax_2">
              <div class="Input_text_s">
                <label>Filter Coin: </label>
                <select id="filter_by_coin" multiple="multiple" name="filter_by_coin[]" type="text" class=" filter_by_name_margin_bottom_sm">
                  <?php foreach($coins as $coinRow){  ?>      
                  <option value="<?php echo $coinRow['symbol'] ?>" <?php if (in_array($coinRow['symbol'], $filter_user_data['filter_by_coin'])) {?> selected <?php }?>><?php echo $coinRow['symbol'] ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 ax_3">
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


            <div class="col-xs-12 col-sm-12 col-md-3 ax_4">
              <div class="Input_text_s">
                <label>Oppertunity Id: </label>
                <input id="filter_by_oppertunity_Id" name="filter_by_oppertunity_Id" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Search By Oppertunity Id" value="<?=(!empty($filter_user_data['filter_by_oppertunity_Id']) ? $filter_user_data['filter_by_oppertunity_Id'] : "")?>" autocomplete="off" required>
               </div>
            </div>
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
            <div class="col-xs-12 col-sm-12 col-md-2 ax_3">
              <div class="Input_text_s">
                <label>Oppertunity Status: </label>
                <select id="opp_status" name="opp_status" type="text" class="form-control filter_by_name_margin_bottom_sm">
                  <option value="sold"<?=(($filter_user_data['opp_status'] == "sold") ? "selected" : "")?>>Sold</option>
                  <option value="open"<?=(($filter_user_data['opp_status'] == "open") ? "selected" : "")?>>Open</option>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3  ax_8" style=" min-height: 60px;">
              <div class="Input_text_s" id="triggerFirst" <?php if ($filter_user_data['group_filter'] == 'rule_group') {?>style="display:block;" <?php } else {?><?php }?>>
                <label>Filter Trigger: </label>
                <select id="filter_by_trigger"  name="filter_by_trigger" type="text" class="form-control  filter_by_trigger">
                  <option value="barrier_trigger" <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_trigger') {?> selected <?php }?>>BARRIER TRIGGER</option>
                  <option value="barrier_percentile_trigger" <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_percentile_trigger') {?> selected <?php }?>>Barrier Percentile Trigger</option>
                </select>
              </div>
            </div>
            
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
                  <option value="level_11"<?php if (in_array('level_11', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 11</option>
                  <option value="level_12"<?php if (in_array('level_12', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 12</option>
                  <option value="level_13"<?php if (in_array('level_13', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 13</option>
                  <option value="level_14"<?php if (in_array('level_14', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 14</option>
                 
                  <option value="level_16"<?php if (in_array('level_16', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 16</option>
                  <option value="level_17"<?php if (in_array('level_17', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 17</option>
                  <option value="level_18"<?php if (in_array('level_18', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 18</option>
                  <option value="level_19"<?php if (in_array('level_19', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 19</option>
                  <option value="level_20"<?php if (in_array('level_20', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 20</option>
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
            <div class="col-xs-12 col-sm-12 col-md-3 ax_9">
              <div class="Input_text_btn">
                <label></label>
                <button id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
                <!--<a href="<?php echo SURL; ?>admin/reports/reset_filters_report/coin" class="btn btn-danger"><i class="fa fa-times-circle"></i>Reset</a>--> <span class="ax_10">
                <button class="btn btn-info" onclick="exportTableToCSV('report.csv')">Export To CSV File</button>
                </span> 
                <!-- <span style="float:right;"><a href="<?php //echo SURL; ?>admin/reports/csv_export_trades" class="btn btn-info">Export To CSV File</a></span> --> 
              </div>
            </div>
          </div>
          </div>
        </form>
      </div>
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

</style>
    <script>
$( document ).ready(function() {
    $(".setsize").each(function() {
        $(this).height($(this).width());
    });
});
$(window).on('resize', function(){
    $(".setsize").each(function() {
        $(this).height($(this).width());
    });
});
</script>
    <div class="widget widget-inverse">
      <div class="widget-body padding-bottom-none">
        <table class=" table table-bordered">
          <tr class="theadd">
            <th></th>
            <th>Coin</th>
            <th>Time</th>
            <th>Profit Progress</th>
            <th>Average Profit</th>
            <th>Rule Or Level</th>
            <th>Total Count</th>
            <th>Five Hour High</th>
            <th>Five Hour Low</th>
            <th>Max High</th>
            <th>Max Low</th>
          </tr>
          <?php
        
        $counter=0;
        foreach ($full_arr as $key1 => $full_arr1) { 
          $counter++;
					foreach ($full_arr1['opp']['avg'] as $key => $value) { 
            if($value['coin'] =='NCASHBTC'){
                $coinImage  =  'ncashhhhhhh.png';
            }else if($value['coin'] =='TRXBTC'){
                $coinImage  =  'aaaaaw.jpg'; 
            }else if($value['coin'] =='EOSBTC'){
              $coinImage  =  'EOS.jpg';
            }else if($value['coin'] =='POEBTC'){
              $coinImage  =  'original.jpg';
            }else if($value['coin'] =='NEOBTC'){
              $coinImage  =  'NEO.jpg';
            }else if($value['coin'] =='ETCBTC'){
              $coinImage  =  'etc.jpg';
            }else if($value['coin'] =='XRPBTC'){
              $coinImage  =  'ripple.png';
            }else if($value['coin'] =='XEMBTC'){
              $coinImage  =  'nem.png';
            }else if($value['coin'] =='XLMBTC'){
              $coinImage  =  'xlm.png';
            }else if($value['coin'] =='QTUMBTC'){
              $coinImage  =  'QTUMBTC.jpg';
            }else if($value['coin'] =='ZENBTC'){
              $coinImage  =  'ZENBTC.png';
            }?>
        <tr>
        <td></td>
        <td><img class="img img-circle" src="https://admin.digiebot.com/assets/coin_logo/thumbs/<?php echo $coinImage ; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $value['coin']; ?>"></td>
        <td><?php echo $key; ?></td>
        <td class="tdmypi">
        <?php 
			                $avg   =  (number_format($value['avg'], 2)!='nan') ? number_format($value['avg'], 2) : '0' ; 
                    
                      if( $avg >= 0){
								  $color  = 'data-barcolor="#3daf2c"';	 
								 
							 }else  if( $avg < 0){
								  $color  = 'data-barcolor="#d60606"';	
								  	  	 	 
							 }
		 	?>     
                             <script type="text/javascript">
							  
								  $( document ).ready(function() {
									$('.pie_progress').asPieProgress({
                                        namespace: 'pie_progress'
                                    });
									$('.pie_progress<?php echo $counter; ?>').asPieProgress('go', <?php echo $profit;?>);
                  });
							 </script>
              <div class="pie_progress pie_progress<?php echo $counter; ?>" role="progressbar" <?php echo  $color ;?> data-goal="100" aria-valuemin="<?php echo $minumu; ?>" aria-valuemax="<?php echo $maximu; ?>">
                <div class="pie_progress__label"> <?php echo number_format($value['avg'], 2);?>%</div>
              </div></td>
        <td><?php echo number_format($value['avg'], 2); ?></td>
        <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_trigger') {?>
            <td><span class="label label-warning"><?php echo $value['buy_rule_number']; ?></span></td>
            <?php }else{?>
            <td><span class="label label-warning"><?php echo $value['order_level']; ?></span></td>
            <?php }?>

        <td><?php echo $value['count']; ?> </td>

        <td> <?php 
			                $max_profit_5h   =  (number_format($value['max_profit_5h'], 2)!='nan') ? number_format($value['max_profit_5h'], 2) : '0' ; 
                    
                   
                    
                if( $max_profit_5h >= 0){
								  $color  = 'data-barcolor="#3daf2c"';	 
								  $minumu = '0';	
								  $maximu = '5';
								  $max_profit_5h = $max_profit_5h; 
								  $max_profit_5hOg = $max_profit_5h; 	 
								  $class  = 'status-box-approved'; 	 
							 }else  if( $max_profit_5h < 0){
								  $color  = 'data-barcolor="#d60606"';	
								  $minumu = '-5';
								  $maximu = '0';
								  $max_profit_5h = $max_profit_5h; 	
								  $max_profit_5hOg    = -5-$max_profit_5h;
								  $class  = 'status-box-rejected'; 	  	  	 	 
							 }
		 	?>     
			<script type="text/javascript">
				$( document ).ready(function() {
					$('.pie_progress').asPieProgress({
					   namespace: 'pie_progress'
					});
				    $('.pie_progress_mx_pro_5<?php echo $counter; ?>').asPieProgress('go', <?php echo $max_profit_5h;?>);
				});
            </script>
            <div class="pie_progress pie_progress_mx_pro_5<?php echo $counter; ?>" role="progressbar" <?php echo  $color ;?> data-goal="100" aria-valuemin="<?php echo $minumu; ?>" aria-valuemax="<?php echo $maximu; ?>">
            <div class="pie_progress__label"> <?php echo $max_profit_5hOg;?>%</div>
            </div>
      </td>


      <td>
            
            <?php            
                      $min_profit_5h   =  (number_format($value['min_profit_5h'], 2)!='nan') ? number_format($value['min_profit_5h'], 2) : '0' ; 
                      if( $max_profit_5h >= 0){
                 $color  = 'data-barcolor="#3daf2c"';	 
                 $minumu = '0';	
                 $maximu = '5';
                 $min_profit_5h = $min_profit_5h; 
                 $min_profit_5hOg = $min_profit_5h; 	 
                 $class  = 'status-box-approved'; 	 
               }else  if( $max_profit_5h < 0){
                 $color  = 'data-barcolor="#d60606"';	
                 $minumu = '-5';
                 $maximu = '0';
                 $min_profit_5h = $min_profit_5h; 	
                 $min_profit_5hOg    = -5-$min_profit_5h;
                 $class  = 'status-box-rejected'; 	  	  	 	 
              }
      ?>
     
           <script type="text/javascript">
       $( document ).ready(function() {
         $('.pie_progress').asPieProgress({
            namespace: 'pie_progress'
         });
           $('.pie_progress_min_pro_5<?php echo $counter; ?>').asPieProgress('go', <?php echo $min_profit_5h;?>);
       });
           </script>
           <div class="pie_progress pie_progress_min_pro_5<?php echo $counter; ?>" role="progressbar" <?php echo  $color ;?> data-goal="100" aria-valuemin="<?php echo $minumu; ?>" aria-valuemax="<?php echo $maximu; ?>">
           <div class="pie_progress__label"> <?php echo $min_profit_5hOg;?>%</div>
           </div>
       </td>


       <td>
            <?php   
             $max_profit_high =  (number_format($value['max_profit_high'], 2)!='nan') ? number_format($value['max_profit_high'], 2) : '0' ;
             if( $max_profit_high >= 0){
                 $color  = 'data-barcolor="#3daf2c"';	 
                 $minumu = '0';	
                 $maximu = '5';
                 $max_profit_high = $max_profit_high; 
                 $max_profit_highOg = $max_profit_high; 	 
                 $class  = 'status-box-approved'; 	 
                }else  if( $max_profit_high < 0){
                 $color  = 'data-barcolor="#d60606"';	
                 $minumu = '-5';
                 $maximu = '0';
                 $max_profit_high = $max_profit_high; 	
                 $max_profit_highOg    = -5-$max_profit_high;
                 $class  = 'status-box-rejected'; 	  	  	 	 
              }
     
      ?>
     <script type="text/javascript">
       $( document ).ready(function() {
         $('.pie_progress').asPieProgress({
            namespace: 'pie_progress'
         });
           $('.max_profit_high<?php echo $counter; ?>').asPieProgress('go', <?php echo $max_profit_high;?>);
       });
           </script>
           <div class="pie_progress max_profit_high<?php echo $counter; ?>" role="progressbar" <?php echo  $color ;?> data-goal="100" aria-valuemin="<?php echo $minumu; ?>" aria-valuemax="<?php echo $maximu; ?>">
           <div class="pie_progress__label"> <?php echo $max_profit_highOg;?>%</div>
           </div>
     
     </td>
       
       
     <td>
            
            <?php 
           
             $min_profit_low  =  (number_format($value['min_profit_low'], 2)!='nan') ? number_format($value['min_profit_low'], 2) : '0' ;
             if( $min_profit_low >= 0){
                 $color  = 'data-barcolor="#3daf2c"';	 
                 $minumu = '0';	
                 $maximu = '5';
                 $min_profit_low   = $min_profit_low; 
                 $min_profit_lowOg = $min_profit_low; 	 
                 $class  = 'status-box-approved'; 	 
                }else  if( $min_profit_low < 0){
                 $color  = 'data-barcolor="#d60606"';	
                 $minumu = '-5';
                 $maximu = '0';
                 $min_profit_low   = $min_profit_low; 	
                 $min_profit_lowOg = -5-$min_profit_low;
                 $class  = 'status-box-rejected'; 	  	  	 	 
              }
      ?>
           
     <script type="text/javascript">
       $( document ).ready(function() {
         $('.pie_progress').asPieProgress({
            namespace: 'pie_progress'
         });
           $('.min_profit_low<?php echo $counter; ?>').asPieProgress('go', <?php echo $min_profit_low;?>);
       });
           </script>
           <div class="pie_progress min_profit_low<?php echo $counter; ?>" role="progressbar" <?php echo  $color ;?> data-goal="100" aria-valuemin="<?php echo $minumu; ?>" aria-valuemax="<?php echo $maximu; ?>">
           <div class="pie_progress__label"> <?php echo $min_profit_lowOg;?>%</div>
           </div>
     
     
     
     </td>
    
        </tr>
     <?php  } } ?>
        
          
          <?php //} elseif ($trigger_key == 'coin_meta') { ?>
          <!--<tr>
                <td colspan="17" style="background: #ccc;">
                  <table class="table table-stripped-column table-hover">
                    <tr>
                      <td><span style="font-weight: bolder;">Total Coin Counts:</span> <?=$trigger_value['total']?></td>
                      <td><span style="font-weight: bolder;">Highest Price:</span> <?=num($trigger_value['high'])?></td>
                      <td><span style="font-weight: bolder;">Lowest Price: </span><?=num($trigger_value['low'])?></td>
                      <td><span style="font-weight: bolder;">Coin Average Move: </span><?=$trigger_value['coin_avg_move']?></td>
                    </tr>
                  </table>
                </td>
              </tr>-->
          <?php //}
			//}
    //}?>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
$('.pie_progress').asPieProgress({
    size: 100,
    ringWidth: 100,
    strokeWidth: 100,
    ringEndsRounded: true,
    valueSelector: "span.value",
    color: "navy"
});
</script> 
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



$("body").on("click","input[name=group_filter]",function(e){
    var query = $(this).attr('id');
    if (query == 'trigger_group') {
        $("#trigger1").hide();
		
		$("#triggerFirst").show();
    }else if(query == 'rule_group'){
        $("#trigger1").show();
		$("#triggerFirst").hide();
    }else{
      $("#trigger1").hide();
    }
});
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

</script> 
