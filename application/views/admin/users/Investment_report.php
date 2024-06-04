
  <link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
  <script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
  <script type="text/javascript" src="<?php  echo SURL ?>assets/dist/jquery-asPieProgress.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>       
  <!--  for pie chart script   -->

  <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
  <link rel='stylesheet' href='https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css'>
  <link rel='stylesheet' href='https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css'>
  <script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-de7e2ef6bfefd24b79a3f68b414b87b8db5b08439cac3f1012092b2290c719cd.js"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
  <script src='https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js'></script>
  <script src='https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js'></script>
  <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js'></script>
  <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js'></script>
  <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js'></script>
  <script src='https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js'></script>
  <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js'></script>
  <script src='https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js'></script>
  <script src='https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js'></script>


  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
  
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <style>
  .bg-danger {

background: #e16d6d94 !important; 
background: -moz-linear-gradient(left, rgba(26,207,196,1) 0%, rgba(255,82,82,1) 0%, rgba(255,19,24,1) 100%)!important; 
background: -webkit-linear-gradient(left, rgba(26,207,196,1) 0%,rgba(255,82,82,1) 0%,rgba(255,19,24,1) 100%)!important; 
background: linear-gradient(to right, rgba(26,207,196,1) 0%,rgba(255,82,82,1) 0%,rgba(255,19,24,1) 100%)!important; 
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1acfc4', endColorstr='#ff1318',GradientType=1 )!important; 
}
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
    .ax_2, .ax_3, .ax_4, .ax_5, .ax_6, .ax_7, .ax_8, .ax_9, .ax_10, .ax_11, .ax_12, .ax_13, .ax_14, .ax_15, .ax_16, .ax_17, .ax_18, .ax_19, .ax_20, .ax_21, .ax_22, .ax_23, .ax_24, .ax_25, .ax_26, .ax_27, .ax_28, .ax_29 , .ax_30, .ax_30{
      padding-bottom: 35px !important;
    }

    .ax_31, .ax_32, .ax_33 , .ax_34, .ax_35{
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

<div id="content">
  <h1 class="content-heading bg-white border-bottom">Reports</h1>
  <div class="innerAll bg-white border-bottom">
    <div class="pull-right" style="padding-right: 12px; padding-top: 8px;">
      <div class=" pull-right alert alert-warning" style=" margin-top: -10px; background: #5c678a;color: white;"> <?php echo date("F j, Y, g:i a").'&nbsp;&nbsp;  <b>'.date_default_timezone_get().' (GMT + 0)'.'<b />' ?></div>     

    </div>
    <ul class="menubar">
      <li class=""><a href="<?php echo SURL; ?>/admin/users_list/investment_report">Reports</a></li>
    </ul>
  </div>
  <div class="innerAll spacing-x2">

   
  <?php if ($this->session->flashdata('commentsMessage')) { ?>
    <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('commentsMessage'); ?></div>
  <?php }elseif($this->session->flashdata('commentsError')){ ?>

    <div class="alert alert-danger alert-dismissable"><?php echo $this->session->flashdata('commentsError'); ?></div>
  <?php } ?>


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
  <?php $filter_data = $this->session->userdata('filter_investment_report');?>
  <div class="widget widget-inverse">
    <div class="widget-body">
      <form method="POST" action="<?php echo SURL; ?>admin/users_list/investment_report">
        <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 ax_1">

          <div class="col-xs-12 col-sm-12 col-md-3 ax_2">
            <div class="Input_text_s">
              <label>Exchange: </label>
              <select id="exchange" name="exchange" type="text" class="form-control filter_by_name_margin_bottom_sm">
                <option value="binance"<?=(($filter_data['exchange'] == "binance") ? "selected" : "")?>>Binance</option>
                <option value="kraken"<?=(($filter_data['exchange'] == "kraken") ? "selected" : "")?>>kraken</option>
              </select>
            </div>
          </div> 

          <div class="col-xs-12 col-sm-12 col-md-3 ax_3">
            <div class="Input_text_s">
              <label>Account Status: </label>
              <select id="status" name="status" type="text" class="form-control filter_by_name_margin_bottom_sm">
                <option value="">Select any</option>
                <option value="yes"<?=(($filter_data['status'] == "yes") ? "selected" : "")?>>Active</option>
                <option value="no"<?=(($filter_data['status'] == "no") ? "selected" : "")?>>In Active</option>
                <option value="blocked"<?=(($filter_data['status'] == "blocked") ? "selected" : "")?>>Blocked</option>
              </select>
            </div>
          </div> 

        <div class="col-xs-12 col-sm-12 col-md-3 ax_4">
          <div class="Input_text_s">
            <label>From Joining Date Range: </label>
            <input id="start_joining_date" name="start_joining_date" type="date" class="form-control datetime_picker filter_by_name_margin_bottom_sm" value="<?=(!empty($filter_data['start_joining_date']) ? $filter_data['start_joining_date'] : "")?>" autocomplete="off">
            
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 ax_5">
          <div class="Input_text_s">
            <label>To Joining Date Range: </label>
            <input id="end_joining_date" name="end_joining_date" type="date" class="form-control datetime_picker filter_by_name_margin_bottom_sm" value="<?=(!empty($filter_data['end_joining_date']) ? $filter_data['end_joining_date'] : "")?>" autocomplete="off">
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 ax_6">
          <div class="Input_text_s">   
            <label>Sorted by: </label>
            <select id="sort" name="sort" type="text" class="form-control filter_by_name_margin_bottom_sm">
              <option value="joining_date"<?=(($filter_data['sort'] == "joining_date") ? "selected" : "")?>>Joining Date</option>
              <option value="totalPointsApi"<?=(($filter_data['sort'] == "totalPointsApi") ? "selected" : "")?>>Total Points</option>
              <option value="dailyTradeableBTCLimit$"<?=(($filter_data['sort'] == "dailyTradeableBTCLimit$") ? "selected" : "")?>>Trade Limit BTC</option>
              <option value="dailyTradeableUSDTLimit"<?=(($filter_data['sort'] == "dailyTradeableUSDTLimit") ? "selected" : "")?>>Trade Limit USDT</option>
              <option value= "previousBuyPercentagebtc" <?php if ($filter_data['customFilters'] == "previousBuyPercentagebtc") {?> selected <?php }?>>Previous Buy Percentage BTC</option>
              <option value= "previousBuyPercentageusdt" <?php if ($filter_data['customFilters'] == "previousBuyPercentageusdt") {?> selected <?php }?>>Previous Buy Percentage USDT</option>
              <option value= "todayBuyPercentagebtc" <?php if ($filter_data['customFilters'] == "todayBuyPercentagebtc") {?> selected <?php }?>>Today Buy Percentage BTC</option>
              <option value= "todayBuyPercentageusdt" <?php if ($filter_data['customFilters'] == "todayBuyPercentageusdt") {?> selected <?php }?>>Today Buy Percentage USDT</option>
              <option value="lth_balance"<?=(($filter_data['sort'] == "lth_balance") ? "selected" : "")?>>LTH balance</option>
              <option value="lthBalancePercentage"<?=(($filter_data['sort'] == "lthBalancePercentage") ? "selected" : "")?>>LTH balance Percentage</option>
              <option value="openBalancePercentage"<?=(($filter_data['sort'] == "openBalancePercentage") ? "selected" : "")?>>Open Balance Percentage</option>
              <option value="open_balance"<?=(($filter_data['sort'] == "open_balance") ? "selected" : "")?>>Open Balance</option>
              <option value="username"<?=(($filter_data['sort'] == "username") ? "selected" : "")?>>Username</option>
              <option value="total_balance"<?=(($filter_data['sort'] == "total_balance") ? "selected" : "")?>>Avaliable Balance</option>
              <option value="actual_deposit"<?=(($filter_data['sort'] == "actual_deposit") ? "selected" : "")?>>Actual Deposit</option>
              <option value="sold_trades"<?=(($filter_data['sort'] == "sold_trades") ? "selected" : "")?>>Sold Orders</option>
              <option value="open_order"<?=(($filter_data['sort'] == "open_order") ? "selected" : "")?>>Open Orders</option>
              <option value="lth_order"<?=(($filter_data['sort'] == "lth_order") ? "selected" : "")?>>Lth Orders</option>
              <option value="avg_sold"<?=(($filter_data['sort'] == "avg_sold") ? "selected" : "")?>>Last Month Average</option>
              <option value="last_login_time"<?=(($filter_data['sort'] == "last_login_time") ? "selected" : "")?>>Last Login</option>
              <option value="dailyTradeBuyBTCIn$"<?=(($filter_data['sort'] == "dailyTradeBuyBTCIn$") ? "selected" : "")?>>Today Buy BTC</option>
              <option value="dailyTradeBuyUSDT"<?=(($filter_data['sort'] == "dailyTradeBuyUSDT") ? "selected" : "")?>>Today Buy USDT</option>
              <option value="previousDailyTradeBuyBTCIn$"<?=(($filter_data['sort'] == "previousDailyTradeBuyBTCIn$") ? "selected" : "")?>>Previous Day Buy BTC</option>
              <option value="previousDailyTradeBuyUSDT"<?=(($filter_data['sort'] == "previousDailyTradeBuyUSDT") ? "selected" : "")?>>Previous Day Buy USDT</option>
              <option value="avg_sold"<?=(($filter_data['sort'] == "avg_sold") ? "selected" : "")?>>Avg Sold Percentage</option>
               <option value="costAvgOrder"<?=(($filter_data['sort'] == "costAvgOrder") ? "selected" : "")?>>Cost Average Orders</option>
            </select>
          </div>
        </div> 
       
        <div class="col-xs-12 col-sm-12 col-md-3 ax_7">
          <div class="Input_text_s">
            <label>username: </label>
            <input id="user_name" name="user_name" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Search By Username" value="<?=(!empty($filter_data['user_name']) ? $filter_data['user_name'] : "")?>">
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 ax_8">
          <div class="Input_text_s">
            <label>Apply Filter: </label>
            <select id="filter_select" name="filter_select[]" multiple="multiple" type="text" class="form-control filter_by_name_margin_bottom_sm filter_select" >
              <option value="limit_exceed_btc"<?php if (isset($filter_data['filter_select']) && in_array('limit_exceed_btc', $filter_data['filter_select'])) {?> selected <?php }?>>Limit Exceed BTC</option>
              <option value="limit_exceed_usdt"<?php if (isset($filter_data['filter_select']) && in_array('limit_exceed_usdt', $filter_data['filter_select'])) {?> selected <?php }?>>Limit Exceed USDT</option>
              <option value="previous_limit_exceed_btc"<?php if (isset($filter_data['filter_select']) && in_array('previous_limit_exceed_btc', $filter_data['filter_select'])) {?> selected <?php }?>>Previous Day Limit Exceed BTC</option>
              <option value="previous_limit_exceed_usdt"<?php if (isset($filter_data['filter_select']) && in_array('previous_limit_exceed_usdt', $filter_data['filter_select'])) {?> selected <?php }?>>Previous Day Limit Exceed USDT</option>
              <option value="join_last_week"<?php if (isset($filter_data['filter_select']) && in_array('join_last_week', $filter_data['filter_select'])) {?> selected <?php }?>>Join Last Week</option>
              <option value="bnb"<?php if (isset($filter_data['filter_select']) && in_array('bnb', $filter_data['filter_select'])) {?> selected <?php }?>>BNB Exists</option>
              <option value="bnbNotExists"<?php if (isset($filter_data['filter_select']) && in_array('bnbNotExists', $filter_data['filter_select'])) {?> selected <?php }?>>BNB Not Exists</option>
              <option value="balance_exists"<?php if (isset($filter_data['filter_select']) && in_array('balance_exists', $filter_data['filter_select'])) {?> selected <?php }?>>Balance Exists</option>
              <option value="api_key"<?php if (isset($filter_data['filter_select']) && in_array('api_key', $filter_data['filter_select'])) {?> selected <?php }?>>Api Key Valid</option>
              <option value="block_user"<?php if (isset($filter_data['filter_select']) && in_array('block_user', $filter_data['filter_select'])) {?> selected <?php }?>>Block User</option>
              <option value="api_key_not"<?php if (isset($filter_data['filter_select']) && in_array('api_key_not', $filter_data['filter_select'])) {?> selected <?php }?>>Api Key Not Valid</option>
              <option value="atg_disable"<?php if (isset($filter_data['filter_select']) && in_array('atg_disable', $filter_data['filter_select'])) {?> selected <?php }?>>ATG Disable</option>
              <option value="atg_enable"<?php if (isset($filter_data['filter_select']) && in_array('atg_enable', $filter_data['filter_select'])) {?> selected <?php }?>>ATG Enable</option>
              <option value="todayZerobtc"<?php if (isset($filter_data['filter_select']) && in_array('todayZerobtc', $filter_data['filter_select'])) {?> selected <?php }?>>Today Buy Zero BTC</option>
              <option value="todayZerousdt"<?php if (isset($filter_data['filter_select']) && in_array('todayZerousdt', $filter_data['filter_select'])) {?> selected <?php }?>>Today Buy Zero USDT</option>
              <option value="predayZerobtc"<?php if (isset($filter_data['filter_select']) && in_array('predayZerobtc', $filter_data['filter_select'])) {?> selected <?php }?>>Previous day Buy Zero BTC</option>
              <option value="predayZerousdt"<?php if (isset($filter_data['filter_select']) && in_array('predayZerousdt', $filter_data['filter_select'])) {?> selected <?php }?>>Previous day Buy Zero USDT</option>
              <option value="pointsRemaningNegitive"<?php if (isset($filter_data['filter_select']) && in_array('pointsRemaningNegitive', $filter_data['filter_select'])) {?> selected <?php }?>>Remaning Points Negitive</option>
              <option value="pointsRemaningPositive"<?php if (isset($filter_data['filter_select']) && in_array('pointsRemaningPositive', $filter_data['filter_select'])) {?> selected <?php }?>>Remaning Points Positive</option>
              <option value="USDTbalance_exists"<?php if (isset($filter_data['filter_select']) && in_array('USDTbalance_exists', $filter_data['filter_select'])) {?> selected <?php }?>>USDT Balance Exists</option>
              <option value="BTCbalance_exists"<?php if (isset($filter_data['filter_select']) && in_array('BTCbalance_exists', $filter_data['filter_select'])) {?> selected <?php }?>>BTC Balance Exists</option>    
              <option value="takingOrderParents"<?php if(isset($filter_data['filter_select']) && in_array('takingOrderParents', $filter_data['filter_select'])){?> selected <?php } ?>>Taking Order Parents Exists</option>  
              <option value="newOrderParents"<?php if(isset($filter_data['filter_select']) && in_array('newOrderParents', $filter_data['filter_select'])){?> selected <?php } ?>>Parent Status New</option> 
              <option value="greaterOne"<?php if(isset($filter_data['filter_select']) && in_array('greaterOne', $filter_data['filter_select'])){?> selected <?php } ?>>Today Buy Both GreaterThan 1 Count</option> 
              <option value="preGreaterOne"<?php if(isset($filter_data['filter_select']) && in_array('preGreaterOne', $filter_data['filter_select'])){?> selected <?php } ?>>Previous Buy Both GreaterThan 1 Count</option> 
              <option value="only_balance"<?php if(isset($filter_data['filter_select']) && in_array('only_balance', $filter_data['filter_select'])){?> selected <?php } ?>>Permission for only balance</option>
              <option value="urgent_issue"<?php if(isset($filter_data['filter_select']) && in_array('urgent_issue', $filter_data['filter_select'])){?> selected <?php } ?>>Profiles with urgent label issues</option>  

            </select>
          </div>
        </div> 
        <div class="col-xs-12 col-sm-12 col-md-3 ax_9">
          <div class="Input_text_s">
            <label>Today Buy Percentage BTC: </label>
            <input name="startBuyPerbtc" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Enter Start Today Buy BTC %" value="<?=(!empty($filter_data['startBuyPerbtc']) ? $filter_data['startBuyPerbtc'] : "")?>">
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 ax_10">
          <div class="Input_text_s">
            <label>Today Buy percentage BTC: </label>
            <input name="endBuyPerbtc" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Enter End Today Buy BTC %" value="<?=(!empty($filter_data['endBuyPerbtc']) ? $filter_data['endBuyPerbtc'] : "")?>">
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 ax_11">
          <div class="Input_text_s">
            <label>Today Buy Percentage USDT: </label>
            <input name="startBuyPerusdt" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Enter Start Today Buy USDT %" value="<?=(!empty($filter_data['startBuyPerusdt']) ? $filter_data['startBuyPerusdt'] : "")?>">
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 ax_12">
          <div class="Input_text_s">
            <label>Today Buy percentage USDT: </label>
            <input name="endBuyPerusdt" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Enter End Today Buy USDT %" value="<?=(!empty($filter_data['endBuyPerusdt']) ? $filter_data['endBuyPerusdt'] : "")?>">
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 ax_13">
          <div class="Input_text_s">
            <label>Previous Buy Percentage BTC: </label>
            <input name="prestartBuyPerbtc" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Enter Start Previous day Buy BTC %" value="<?=(!empty($filter_data['prestartBuyPerbtc']) ? $filter_data['prestartBuyPerbtc'] : "")?>">
          </div>    
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 ax_14">
          <div class="Input_text_s">
            <label>Previous Buy percentage BTC: </label>
            <input name="preEndBuyPerbtc" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Enter End Previous day Buy BTC %" value="<?=(!empty($filter_data['preEndBuyPerbtc']) ? $filter_data['preEndBuyPerbtc'] : "")?>">
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 ax_15">
          <div class="Input_text_s">
            <label>Previous Buy Percentage USDT: </label>
            <input name="preStartBuyPerusdt" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Enter Start Previous Buy USDT %" value="<?=(!empty($filter_data['preStartBuyPerusdt']) ? $filter_data['preStartBuyPerusdt'] : "")?>">
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 ax_16">
          <div class="Input_text_s">
            <label>Previous Buy percentage USDT: </label>
            <input name="preEndBuyPerusdt" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Enter End Previous Buy USDT %" value="<?=(!empty($filter_data['preEndBuyPerusdt']) ? $filter_data['preEndBuyPerusdt'] : "")?>">
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 ax_17">
          <div class="Input_text_s">
            <label>Start Limit BTC: </label>
            <input name="startLimitbtc" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Enter Start In Limit $" value="<?=(!empty($filter_data['startLimitbtc']) ? $filter_data['startLimitbtc'] : "")?>">
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 ax_18">
          <div class="Input_text_s">
            <label>End Limit BTC: </label>
            <input name="endLimitbtc" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Enter End Limit In $" value="<?=(!empty($filter_data['endLimitbtc']) ? $filter_data['endLimitbtc'] : "")?>">
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 ax_19">
          <div class="Input_text_s">
            <label>Start Limit USDT: </label>
            <input name="startLimitusdt" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Enter Start In Limit $" value="<?=(!empty($filter_data['startLimitusdt']) ? $filter_data['startLimitusdt'] : "")?>">
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 ax_20">
          <div class="Input_text_s">
            <label>End Limit USDT: </label>
            <input name="endLimitusdt" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Enter End Limit In $" value="<?=(!empty($filter_data['endLimitusdt']) ? $filter_data['endLimitusdt'] : "")?>">
          </div>
        </div>

       
        <script type="text/javascript">
          $(document).ready(function() {
            $('#filter_select, #filter_by_select, #filter_by_level_select').multiselect({
              includeSelectAllOption: true,
              buttonWidth: 435.7,
              enableFiltering: true
            });
          });
        </script>

        <script type="text/javascript">
          $(document).ready(function() {
            $('#pakage, #pakage_select, #pakage_select').multiselect({
              includeSelectAllOption: true,
              buttonWidth: 435.7,
              enableFiltering: true
            }); 
          });
        </script>
        <div class="col-xs-12 col-sm-12 col-md-3 ax_19">   
          <div class="Input_text_s">
            <label>Select Field Name: </label>
            <select id="customFilters" name="customFilters" type="text" class="form-control filter_by_name_margin_bottom_sm customFilters"> 
              <option value= "">Select Any Field</option>                           
              <option value= "dailyBtcBuyTradeCount" <?php if($filter_data['customFilters'] == "dailyBtcBuyTradeCount") {?> selected <?php }?>>Today BTC Buy Count</option>
              <option value= "dailyUSDTBuyTradeCount" <?php if($filter_data['customFilters'] == "dailyUSDTBuyTradeCount") {?> selected <?php }?>>Today USDT Buy Count</option>
              <!-- <option value= "previousDailyUSDTBuyTradeCount" <?php //if($filter_data['customFilters'] == "previousDailyUSDTBuyTradeCount") {?> selected <?php //}?>>previousDay BTC Buy Count</option>
              <option value= "previousDailyBtcBuyTradeCount" <?php //if($filter_data['customFilters'] == "previousDailyBtcBuyTradeCount") {?> selected <?php //}?>>PreviousDay USDT Buy Count</option> -->
              <option value= "lthBalancePercentage" <?php if($filter_data['customFilters'] == "lthBalancePercentage") {?> selected <?php }?>>LTH Balance Percentage</option>
              <option value= "lthBalanceUsdtpercentage" <?php if($filter_data['customFilters'] == "lthBalanceUsdtpercentage") {?> selected <?php }?>>LTH Balance Percentage USDT</option>
              <option value= "lthBalanceBtcpercentage" <?php if($filter_data['customFilters'] == "lthBalanceBtcpercentage") {?> selected <?php }?>>LTH Balance Percentage BTC</option>
              <option value= "openBalancePercentage" <?php if ($filter_data['customFilters'] == "openBalancePercentage") {?> selected <?php }?>>Open Balance Percentage</option>
              <option value= "lth_balance" <?php if ($filter_data['customFilters'] == "lth_balance") {?> selected <?php }?>>LTH Balance</option>
              <option value= "open_balance" <?php if ($filter_data['customFilters'] == "open_balance") {?> selected <?php }?>>Open Balance</option>        
              <option value= "total_balance" <?php if ($filter_data['customFilters'] == "total_balance") {?> selected <?php }?>>Total Balance</option>
              <option value= "avg_sold" <?php if ($filter_data['customFilters'] == "avg_sold") {?> selected <?php }?>>Avg Sold</option>
              <option value= "dailyTradeableBTCLimit$" <?php if ($filter_data['customFilters'] == "dailyTradeableBTCLimit$") {?> selected <?php }?>>Per Day Limit BTC</option>
              <option value= "dailyTradeableUSDTLimit" <?php if ($filter_data['customFilters'] == "dailyTradeableUSDTLimit") {?> selected <?php }?>>Per Day Limit USDT</option>
              <option value= "pakageStatusUSDT" <?php if ($filter_data['customFilters'] == "pakageStatusUSDT") {?> selected <?php }?>>Pakage Status USDT</option>
              <option value= "pakageStatusBTC" <?php if ($filter_data['customFilters'] == "pakageStatusBTC") {?> selected <?php }?>>Pakage Status BTC</option>
              <option value= "sold_trades" <?php if ($filter_data['customFilters'] == "sold_trades") {?> selected <?php }?>>Sold Trades</option>        
              <option value= "trade_limit" <?php if ($filter_data['customFilters'] == "trade_limit") {?> selected <?php }?>>Pakage</option>
              <option value= "previousBuyPercentagebtc" <?php if ($filter_data['customFilters'] == "previousBuyPercentagebtc") {?> selected <?php }?>>Previous Buy Percentage BTC</option>
              <option value= "previousBuyPercentageusdt" <?php if ($filter_data['customFilters'] == "previousBuyPercentageusdt") {?> selected <?php }?>>Previous Buy Percentage USDT</option>
              <option value= "todayBuyPercentagebtc" <?php if ($filter_data['customFilters'] == "todayBuyPercentagebtc") {?> selected <?php }?>>Today Buy Percentage BTC</option>
              <option value= "todayBuyPercentageusdt" <?php if ($filter_data['customFilters'] == "todayBuyPercentageusdt") {?> selected <?php }?>>Today Buy Percentage USDT</option>
              <option value= "dailTradeAbleBalancePercentage" <?php if ($filter_data['customFilters'] == "dailTradeAbleBalancePercentage") {?> selected <?php }?>>Daily TradeAble Balance Percentage</option>
              <option value= "usdtInvestPercentage" <?php if ($filter_data['customFilters'] == "usdtInvestPercentage") {?> selected <?php }?>>USDT Invest Percentage</option>     
              <option value= "btcInvestPercentage" <?php if ($filter_data['customFilters'] == "btcInvestPercentage") {?> selected <?php }?>>BTC Invest Percentage</option>
              <option value= "dailyTradeBuyUSDT" <?php if ($filter_data['customFilters'] == "dailyTradeBuyUSDT") {?> selected <?php }?>>Daily Buy Worth USDT</option>
              <option value= "dailyTradeBuyBTCIn$" <?php if ($filter_data['customFilters'] == "dailyTradeBuyBTCIn$") {?> selected <?php }?>>Daily Buy Worth BTC</option>
              <option value= "dailyBtcBuyTradeCount" <?php if ($filter_data['customFilters'] == "dailyBtcBuyTradeCount") {?> selected <?php }?>>Daily Buy Trade Count BTC</option>
              <option value= "dailyUSDTBuyTradeCount" <?php if ($filter_data['customFilters'] == "dailyUSDTBuyTradeCount") {?> selected <?php }?>>Daily Buy Trade Count USDT</option>
              <option value= "remainingPoints" <?php if ($filter_data['customFilters'] == "remainingPoints") {?> selected <?php }?>>Remaining Points</option>
              <option value= "totalPointsApi" <?php if ($filter_data['customFilters'] == "totalPointsApi") {?> selected <?php }?>>Total Points</option> 
              <option value= "consumed_points" <?php if ($filter_data['customFilters'] == "consumed_points") {?> selected <?php }?>>Consumed Points</option>
              <option value= "invest_amount" <?php if ($filter_data['customFilters'] == "invest_amount") {?> selected <?php }?>>Invest Amount</option>
              <option value= "previousDailyTradeBuyBTCIn$" <?php if ($filter_data['customFilters'] == "previousDailyTradeBuyBTCIn$") {?> selected <?php }?>>Previous Day Buy Worth BTC</option>
              <option value= "previousDailyBtcBuyTradeCount" <?php if ($filter_data['customFilters'] == "previousDailyBtcBuyTradeCount") {?> selected <?php }?>>Previous day Buy Count BTC</option>
              <option value= "PreviousDailyTradeableBTCLimit$" <?php if ($filter_data['customFilters'] == "PreviousDailyTradeableBTCLimit$") {?> selected <?php }?>>Previous Day Limit BTC</option> 
              <option value= "previousDailyTradeBuyUSDT" <?php if ($filter_data['customFilters'] == "previousDailyTradeBuyUSDT") {?> selected <?php }?>>Previous Day Buy Worth USDT</option>
              <option value= "previousDailyUSDTBuyTradeCount" <?php if ($filter_data['customFilters'] == "previousDailyUSDTBuyTradeCount") {?> selected <?php }?>>Previous day Buy Count USDT</option>
              <option value= "PreviousDailyTradeableUSDTLimit" <?php if ($filter_data['customFilters'] == "PreviousDailyTradeableUSDTLimit") {?> selected <?php }?>>Previous Day Limit USDT</option> 
              <option value= "actual_deposit" <?php if ($filter_data['customFilters'] == "actual_deposit") {?> selected <?php }?>>Account Worth</option> 
              <option value= "savenCloseRatioBtc" <?php if ($filter_data['customFilters'] == "savenCloseRatioBtc") {?> selected <?php }?>>Last Week Close Ratio Btc percentage</option> 
              <option value= "savenCloseRatioUsdt" <?php if ($filter_data['customFilters'] == "savenCloseRatioUsdt") {?> selected <?php }?>>Last Week Close Ratio Usdt Percentage</option> 
              <option value= "todayCloseRatioBtc" <?php if ($filter_data['customFilters'] == "todayCloseRatioBtc") {?> selected <?php }?>>Today Close Ratio Btc Percentage</option> 
              <option value= "todayCloseRatioUsdt" <?php if ($filter_data['customFilters'] == "todayCloseRatioUsdt") {?> selected <?php }?>>Today Close Ratio Usdt Percentage</option> 
              <option value= "lth_cost_avg_balance_percentage" <?php if ($filter_data['customFilters'] == "lth_cost_avg_balance_percentage") {?> selected <?php }?>>LTH & CA Percentage</option> 
              <option value= "last_api_updated" <?php if ($filter_data['customFilters'] == "last_api_updated") {?> selected <?php }?>>Last API key updated</option> 
            </select>
          </div>
        </div> 

        <div class="col-xs-12 col-sm-12 col-md-3 ax_20">
          <div class="Input_text_s">
            <label>Enter Field Value: </label>
            <input id="customValue" name="customValue" type="number" class="form-control filter_by_name_margin_bottom_sm" placeholder="Enter Custom Filter Value" value="<?=(!empty($filter_data['customValue']) ? $filter_data['customValue'] : "")?>">
            <span class="text-muted">Results will be equal or greater than this value.</span><br>
            <span class="text-muted">If selected the last api update filter the numbers you enter will be consider as months ago.</span>
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 ax_21">
          <div class="Input_text_s">
            <label>Select Ip: </label>
            <select id="tradingIp" name="tradingIp" type="text" class="form-control filter_by_name_margin_bottom_sm">
            <option value="">Select Ip</option>
              <option value="3.227.143.115"<?=(($filter_data['tradingIp'] == "3.227.143.115") ? "selected" : "")?>>3.227.143.115</option>
              <option value="3.228.180.22"<?=(($filter_data['tradingIp'] == "3.228.180.22") ? "selected" : "")?>>3.228.180.22</option>
              <option value="3.226.226.217"<?=(($filter_data['tradingIp'] == "3.226.226.217") ? "selected" : "")?>>3.226.226.217</option>
              <option value="3.228.245.92"<?=(($filter_data['tradingIp'] == "3.228.245.92") ? "selected" : "")?>>3.228.245.92</option>
              <option value="35.153.9.225"<?=(($filter_data['tradingIp'] == "35.153.9.225") ? "selected" : "")?>>35.153.9.225</option>
            </select>
          </div>
        </div> 
         
        <div class="col-xs-12 col-sm-12 col-md-3 ax_22">
          <div class="Input_text_s">
            <label>Account Status Filters: </label>
            <select id="accountIssueFilter" name="accountIssueFilter" type="text" class="form-control filter_by_name_margin_bottom_sm">
              <option value="">Select Filter</option>
              <option value="activeTodayBtcIssueAccount"   <?=(($filter_data['accountIssueFilter'] == "activeTodayBtcIssueAccount") ? "selected" : "")?>>Active User Today BTC Buy Issue</option>
              <option value="activeTodayUsdtIssueAccount"  <?=(($filter_data['accountIssueFilter'] == "activeTodayUsdtIssueAccount")  ? "selected" : "")?>>Active User Today USDT Buy Issue</option>
              <option value="activePreviousDayBtcIssueAccount"  <?=(($filter_data['accountIssueFilter'] == "activePreviousDayBtcIssueAccount") ? "selected" : "")?>>Active User PreviousDay BTC Buy Issue</option>
              <option value="activePreviousDayUsdtIssueAccount" <?=(($filter_data['accountIssueFilter'] == "activePreviousDayUsdtIssueAccount")  ? "selected" : "")?>>Active User PreviousDay USDT Buy Issue</option>
              <option value="newActiveTodayBtcIssueAccount"  <?=(($filter_data['accountIssueFilter'] == "newActiveTodayBtcIssueAccount")  ? "selected" : "")?>>New Active User Today BTC Buy Issue</option>
              <option value="newActiveTodayUsdtIssueAccount" <?=(($filter_data['accountIssueFilter'] == "newActiveTodayUsdtIssueAccount")  ? "selected" : "")?>>New Active User Today USDT Buy Issue</option>
              <option value="newActivePreviousDayBtcIssueAccount"  <?=(($filter_data['accountIssueFilter'] == "newActivePreviousDayBtcIssueAccount")  ? "selected" : "")?>>New Active User PreviousDay BTC Buy Issue</option>
              <option value="newActivePreviousDayUsdtIssueAccount" <?=(($filter_data['accountIssueFilter'] == "newActivePreviousDayUsdtIssueAccount")  ? "selected" : "")?>>New Active User PreviousDay USDT Buy Issue</option>
              <option value="activeStuckAccount"    <?=(($filter_data['accountIssueFilter'] == "activeStuckAccount")  ? "selected" : "")?>>Active Stuck Account</option>
              <option value="newActiveStuckAccount" <?=(($filter_data['accountIssueFilter'] == "newActiveStuckAccount")  ? "selected" : "")?>>New Active Stuck Account</option>
            </select> 
          </div>
        </div> 


        <div class="col-xs-12 col-sm-12 col-md-3 ax_23">
          <div class="Input_text_s">
            <label>Admin Id: </label>
            <input name="admin_id" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Enter admin_id" value="<?=(!empty($filter_data['admin_id']) ? $filter_data['admin_id'] : "")?>">
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 ax_24">
          <div class="Input_text_s">
            <label></label>   
            <input name="sortedBy" type="radio" class="filter_by_name_margin_bottom_sm" value="1"<?php if($filter_data['sortedBy'] == 1){?> checked <?php }?>>ASC
            <input name="sortedBy" type="radio" class="filter_by_name_margin_bottom_sm" value="-1"<?php if($filter_data['sortedBy'] == -1){?> checked <?php }?>>DSC
          </div>
        </div>
        <br>

        <div class="col-xs-12 col-sm-12 col-md-2 ax_25" title="<?php echo "Atg: yes \r\n balance Greater than 0 \r\n api key valid\r\n total order Count within 7 days less than 14 orders \r\n joinind date older than 14 days"; ?>">
          <div class="Input_text_btn">
            <label></label>
            <a href="<?php echo SURL; ?>admin/users_list/investment_report?button=lowTrading"class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>7_Days_Low_Trading_Binance</a>
            </span>   
          </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-2 ax_26" title="<?php echo "Atg: yes \r\n balance Greater than 0 \r\n api key valid\r\n total order Count within 7 days less than 14 orders \r\n joinind date older than 14 days"; ?>">
          <div class="Input_text_btn">
            <label></label>
            <a href="<?php echo SURL; ?>admin/users_list/investment_report?button=lowTrading_kraken"class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>7_Days_Low_Trading_Kraken</a>
            </span>   
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-2 ax_27" title= "<?php echo "Atg : yes\r\n Api key valid\r\n Open,LTh,CA and sold = 0\r\n balance greater than 0\r\n joining date older than 2 days till 1 month"; ?>">
          <div class="Input_text_btn">
            <label></label>
            <a href="<?php echo SURL; ?>admin/users_list/investment_report?button=noTrade"class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>No_Trade_Fill_Binance</a>
            </span>   
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-2 ax_28" title= "<?php echo "Atg : yes\r\n Api key valid\r\n Open,LTh,CA and sold = 0\r\n balance greater than 0\r\n joining date older than 2 days till 1 month"; ?>">
          <div class="Input_text_btn">
            <label></label>
            <a href="<?php echo SURL; ?>admin/users_list/investment_report?button=noTrade_kraken"class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>No_Trade_Fill_Kraken</a>
            </span>   
          </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-2 ax_29" title="<?php echo "Extra balance greater 5% of total avaliable balance"; ?>">
          <div class="Input_text_btn">
            <label></label>
            <a href="<?php echo SURL; ?>admin/users_list/investment_report?button=extra_kraken"class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Extra_Balance_Kraken</a>
            </span>   
          </div>
        </div>  

        <div class="col-xs-12 col-sm-12 col-md-2 ax_30" title="<?php echo "Extra balance greater 5% of total avaliable balance"; ?>">
          <div class="Input_text_btn">
            <label></label>
            <a href="<?php echo SURL; ?>admin/users_list/investment_report?button=extra_binance"class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Extra_Balance_Binance</a>
            </span>   
          </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-2 ax_31" title="<?php echo "Total balance greater than 1000 \r\n buy/sell order count with 7 days less than 20"; ?>">
          <div class="Input_text_btn">
            <label></label>
            <a href="<?php echo SURL; ?>admin/users_list/investment_report?button=less_trade_kraken"class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Less_Trade_users_Kraken_20</a>
            </span>   
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-2 ax_32" title="<?php echo "Total balance greater than 1000 \r\n buy/sell order count with 7 days less than 20"; ?>">
          <div class="Input_text_btn">
            <label></label>
            <a href="<?php echo SURL; ?>admin/users_list/investment_report?button=less_trade_binance"class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Less_Trade_users_Binance_20</a>
            </span>   
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-2 ax_33" title= "<?php echo "balance greater than 10 and less than 1000\r\n buy/sell order count less than 14"; ?>">
          <div class="Input_text_btn">
            <label></label>
            <a href="<?php echo SURL; ?>admin/users_list/investment_report?button=less_trade_kraken_14"class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Less_Trade_users_Kraken_14</a>
            </span>   
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-2 ax_34" title= "<?php echo "balance greater than 10 and less than 1000\r\n buy/sell order count less than 14"; ?>">
          <div class="Input_text_btn">
            <label></label>
            <a href="<?php echo SURL; ?>admin/users_list/investment_report?button=less_trade_binance_14"class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Less_Trade_users_Binance_14</a>
            </span>   
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-2 ax_35">
          <div class="Input_text_btn">
            <label></label>
            <button id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
            <a href="<?php echo SURL; ?>admin/users_list/resetFilter"class="btn btn-danger">Reset</a>
            </span>   
          </div>
        </div>

      </div>
    </div>
  </form>
</div>
</div>

  <!-- <a href="#" class="button js-button" role="button">Show content</a> -->



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
  .circle{
    position: relative;
  }
  .circle strong {
    position: absolute;
    top: 50%;
    left: 50%;
    /* z-index: 2222; */
    transform: translate(-50%, -50%);
    font-size: 15px;
    color: black; 
  }
  
    /* Pie chart CSS start*/
  *, *::after, *::before {
    box-sizing: border-box;
  }

  .donut {
    --donut-size: 90px;
    --donut-border-width: 15px;
    --donut-spacing: 0;
    --donut-spacing-color: 255, 255, 255;
    --donut-spacing-deg: calc(1deg * var(--donut-spacing));
    border-radius: 25%;
    height: var(--donut-size);

    position: relative;
    width: var(--donut-size);
  }

  .donut__slice {
    height: 100%;
    position: absolute;
    width: 100%;
  }
  .donut__slice::before,
  .donut__slice::after {
    border: var(--donut-border-width) solid rgba(0, 0, 0, 0);
    border-radius: 50%;
    content: '';
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg);
    width: 100%;
  }

  .donut__slice::before {
    border-width: calc(var(--donut-border-width) + 1px);
    box-shadow: 0 0 1px 0 rgba(var(--donut-spacing-color), calc(100 * var(--donut-spacing)));
  }

  .donut__slice__first {
    --first-start: 0;
  }

  .donut__slice__first::before {
    border-top-color: rgba(var(--donut-spacing-color), calc(100 * var(--donut-spacing)));
    -webkit-transform: rotate(calc(360deg * var(--first-start) + 45deg));
            transform: rotate(calc(360deg * var(--first-start) + 45deg));
  }

  .donut__slice__first::after {
    border-top-color: #ff6838;
    border-right-color: rgba(255, 104, 56, calc(100 * (var(--first) - .25)));
    border-bottom-color: rgba(255, 104, 56, calc(100 * (var(--first) - .5)));
    border-left-color: rgba(255, 104, 56, calc(100 * (var(--first) - .75)));
    -webkit-transform: rotate(calc(360deg * var(--first-start) + 45deg + var(--donut-spacing-deg)));
            transform: rotate(calc(360deg * var(--first-start) + 45deg + var(--donut-spacing-deg)));
  }

  .donut__slice__second {
    --second-start: calc(var(--first));
    --second-check: max(calc(var(--second-start) - .5), 0);
    -webkit-clip-path: inset(0 calc(50% * (var(--second-check) / var(--second-check))) 0 0);
            clip-path: inset(0 calc(50% * (var(--second-check) / var(--second-check))) 0 0);
  }

  .donut__slice__second::before {
    border-top-color: rgba(var(--donut-spacing-color), calc(100 * var(--donut-spacing)));
    -webkit-transform: rotate(calc(360deg * var(--second-start) + 45deg));
            transform: rotate(calc(360deg * var(--second-start) + 45deg));
  }

  .donut__slice__second::after {
    border-top-color: #ffc820;
    border-right-color: rgba(255, 200, 32, calc(100 * (var(--second) - .25)));
    border-bottom-color: rgba(255, 200, 32, calc(100 * (var(--second) - .5)));
    border-left-color: rgba(255, 200, 32, calc(100 * (var(--second) - .75)));
    -webkit-transform: rotate(calc(360deg * var(--second-start) + 45deg + var(--donut-spacing-deg)));
            transform: rotate(calc(360deg * var(--second-start) + 45deg + var(--donut-spacing-deg)));
  }

  .donut__slice__third {
    --third-start: calc(var(--first) + var(--second));
    --third-check: max(calc(var(--third-start) - .5), 0);
    -webkit-clip-path: inset(0 calc(50% * (var(--third-check) / var(--third-check))) 0 0);
            clip-path: inset(0 calc(50% * (var(--third-check) / var(--third-check))) 0 0);
  }

  .donut__slice__third::before {
    border-top-color: rgba(var(--donut-spacing-color), calc(100 * var(--donut-spacing)));
    -webkit-transform: rotate(calc(360deg * var(--third-start) + 45deg));
            transform: rotate(calc(360deg * var(--third-start) + 45deg));
  }

  .donut__slice__third::after {
    border-top-color: #97c95c;
    border-right-color: rgba(151, 201, 92, calc(100 * (var(--third) - .25)));
    border-bottom-color: rgba(151, 201, 92, calc(100 * (var(--third) - .5)));
    border-left-color: rgba(151, 201, 92, calc(100 * (var(--third) - .75)));
    -webkit-transform: rotate(calc(360deg * var(--third-start) + 45deg + var(--donut-spacing-deg)));
            transform: rotate(calc(360deg * var(--third-start) + 45deg + var(--donut-spacing-deg)));
    }
  .donut__slice__fourth {
    --fourth-start: calc(var(--first) + var(--second) + var(--third));
    --fourth-check: max(calc(var(--fourth-start) - .5), 0);
    -webkit-clip-path: inset(0 calc(50% * (var(--fourth-check) / var(--fourth-check))) 0 0);
            clip-path: inset(0 calc(50% * (var(--fourth-check) / var(--fourth-check))) 0 0);
  }

  .donut__slice__fourth::before {
    border-top-color: rgba(var(--donut-spacing-color), calc(100 * var(--donut-spacing)));
    -webkit-transform: rotate(calc(360deg * var(--fourth-start) + 45deg));
            transform: rotate(calc(360deg * var(--fourth-start) + 45deg));
  }

  .donut__slice__fourth::after {
    border-top-color: #1cb2f6;
    border-right-color: rgba(28, 178, 246, calc(100 * (var(--fourth) - .25)));
    border-bottom-color: rgba(28, 178, 246, calc(100 * (var(--fourth) - .5)));
    border-left-color: rgba(28, 178, 246, calc(100 * (var(--fourth) - .75)));
    -webkit-transform: rotate(calc(360deg * var(--fourth-start) + 45deg + var(--donut-spacing-deg)));
    transform: rotate(calc(360deg * var(--fourth-start) + 45deg + var(--donut-spacing-deg)));
  }

  /* pie chart CSS end */

  .blinking{
    animation:blinkingText 3s infinite;
  }

  /* css for flashing  */
  @keyframes blinkingText{
    0%{	background-color: red;	}
    49%{	color: transparent;	}
    50%{	color: transparent;	}
    99%{	color:transparent;	}
    100%{	color: #000;	}
  }
      html,
      body {
        height: 100%;
      }
      .dataTables_filter{
        float:right;
      }
      .paginate_disabled_previous{
        margin-right: 10%;
      }
      .paginate_enabled_next{
        margin-left: 10%;
      }
      .dataTables_length{
        margin-bottom: -1%;
      }
      body {
        -webkit-box-align: center;
          align-items: center;
        /* display: -webkit-box;
        display: flex; */
        flex-wrap: wrap;
        -webkit-box-pack: center;
          justify-content: center;
      }
    /* end flashing TD */
     td{
      vertical-align: middle !important;
     }

    .size{
      width: 170px; 
    }

    .dot {
        height: 10px;
        width: 10px;
        /* background-color: red; */
        border-radius: 50%;
        display: inline-block;
    }

    /* // draw square */
    .square {
        height: 15px;
        width: 15px;
        margin-left: 15%;
    }
  </style>

  <script>
  $(".setsize").each(function() {
    $(this).height($(this).width());
  });
  $(window).on('resize', function(){
  $(".setsize").each(function() {
    $(this).height($(this).width());
  });
  });

  </script> 
    <!-- START MODEL -->
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">User Account Issue</h4>
          </div>
          <div class="modal-body">
            <p id="append">  
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- END MODEL -->

    <!-- START MODEL for comments-->
    <div class="modal fade" id="myModal2" role="dialog">
      <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Profile Comments</h4>
          </div>
          <div class="modal-body" >

            <form method="POST" action="<?php echo SURL;?>admin/users_list/addComments">
                <table id="myTable" class=" table order-list">
                <thead>
                  <tr>
                      <td>Add Prob Statement</td>
                      <td>Select Priority</td>
                  </tr>
                </thead>
                    <tbody id="prob_modal"><tr>
                      <td class="col-md-4">
                        <textarea id="comments" name="comments[]" rows="4" cols="25" placeholder="Add your problem statement here" class="form-control">
                          </textarea></td>
                      <td>
                        <select class="form-control change_color" name="priorityLabel[]" id="priorityLabel">
                          <option>Select Label</option>
                          <option value="#ff5252" style="background-color:#ff5252 ;">Urgent</option>
                          <option value="#e18644a8" style="background-color:#e18644a8 ;">High</option>
                          <option value="#28d63fad" style="background-color:#28d63fad ;">Normal</option>
                          <option value="#4ccdffb0" style="background-color:#4ccdffb0 ;">Low</option>
                          
                        </select></td><td class="col-md-2"></td></tr></tbody>
                    <tfoot>
                      <tr>
                          <td class="col-md-2">
                            <input type="button"  class="btn btn-success " id="add_row_user_problem" value="Add New" />
                          </td>
                      </tr>
                      <tr>
                      </tr>
                    </tfoot>
              </table>
               <input type="hidden" id="exchange1" name="exchange1" value=""> 
               <input type="hidden" id="admin_id1" name="admin_id1" value="">
             
              <br>
          </div>
          <div class="modal-footer">
              <button id="submit-form" class="btn btn-success">Save</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
             </form>
          </div>
        </div>
      </div>
    </div>
  <!-- END MODEL -->

  <div class="widget widget-inverse">
    <div class="widget-body padding-bottom-none col-xs-12 col-sm-12 col-md-12 ax_12" style="
    overflow-x: scroll !important;">
      <table class=" table table-bordered table" id="datatable" width="100%"> 
        <thead class="theadd">
          <th>#</th>
          <th><a href="#" data-toggle="tooltip" title="User Profile Picture">Profile</a></th>
          <th style="text-align:center; width:130px"><a href="#" data-toggle="tooltip" title="User Full Name and Exchanges Notification if exchange icon is checked it's mean this exchange is enabled for this user">Name</a></th>
          <th><a href="#" data-toggle="tooltip" title="Account Creation Date">Joining Date</a></th>
          <th><a href="#" data-toggle="tooltip" title="API key modified Date">Api key modified</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Account Worth</a></th>
          <th><a href="#" data-toggle="tooltip" title="Available Balance for new trades">Tradeable Avaliable Balance</a></th>
          <th><a href="#" data-toggle="tooltip" title="Sold orders from joining date to till now">Sold Order</a></th>
          <th><a href="#" data-toggle="tooltip" title="Current open Order">Open</a></th>
          <th><a href="#" data-toggle="tooltip" title="BNB">BNB</a></th>
          <th><a href="#" data-toggle="tooltip" title="Current LTH Order">LTH</a></th>
          <th><a href="#" data-toggle="tooltip" title="Current LTH Order">Cost Avg</a></th>
          <th><a href="#" data-toggle="tooltip" title="Open Orders Balance">Open Balance BTC</a></th>
          <th><a href="#" data-toggle="tooltip" title="Open Orders Balance">Open Balance USDT</a></th>
          <th><a href="#" data-toggle="tooltip" title="LTH Orders Balance">LTH Balance BTC</a></th>
          <th><a href="#" data-toggle="tooltip" title="LTH Orders Balance">LTH Balance USDT</a></th>
          <th><a href="#" data-toggle="tooltip" title="LTH Orders Balance">Cost Avg Balance BTC</a></th>
          <th><a href="#" data-toggle="tooltip" title="LTH Orders Balance">Cost Avg Balance USDT</a></th>
          <th><a href="#" data-toggle="tooltip" title="LTH Orders Balance">Lth CA Combine%</a></th>
          <th><a href="#" data-toggle="tooltip" title="LTH Orders Balance">Extra/Less Balance $</a></th>
          <th><a href="#" data-toggle="tooltip" title="Expected Trade Buy Count Accroding to ATG">Expected Trades</a></th>
          <th><a href="#" data-toggle="tooltip" title="Trade Limit Points">Points</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Current Month Consumed Points</a></th>
          <th><a href="#" data-toggle="tooltip" title="Daily trade details">Daily_Trade</a></th>
          <th><a href="#" data-toggle="tooltip" title="Auto trades investment % according to Pkg">Last 30 Days Auto Trades Investment%</a></th>
          <th><a href="#" data-toggle="tooltip" title="Completed Previous Day Limit">Previous_Day Limit</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Last Trade Buy/Sold Time</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Last 30 Days Profit/Loss</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Last 30 Days Invest</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Last 30 Days Profit Sold Orders</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Last Login Time</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Dotted Bar</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Today BTC Clossing Ratio</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Today USDT Clossing Ratio</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Week BTC Clossing Ratio</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Week USDT Clossing Ratio</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Parent Details</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Last week trades Count</a></th>
          <th><a href="#" data-toggle="tooltip" title="Record Update time">Last Modified</a></th>
          <th><a href="#" data-toggle="tooltip" title="Record Update time">Action</a></th>
          <th><a href="#" data-toggle="tooltip" title="">Action Button</a></th>
        </thead>
       <tbody>
  <?php if(isset($_COOKIE['sheraz']) && $_COOKIE['sheraz'] == 1){ //data print for debuggin for developer
          echo 'Array printing:';echo '<pre>';print_r($final_array);exit;
  } ?> 
  <?php  
    // echo "<pre>";print_r($final_array);
  $time_zone = date_default_timezone_get();
  $this->load->helper('common_helper');
  $count = 0;
  $admin_ids_arr = array_column($final_array, 'admin_id'); 

  foreach ($final_array as $key => $value) {
    $btcPkg  = 0;
    $usdtPkg = 0;
    if($value['customBtcPackage'] > 0 ){
      $btcPkg  = ($value['customBtcPackage']   >= $value['avaliableBtcBalance'])   ?  $value['avaliableBtcBalance']  : $value['customBtcPackage']  ;
    }
    if($value['customUsdtPackage'] > 0){
      $usdtPkg = ($value['customUsdtPackage']  >= $value['avaliableUsdtBalance'])  ?  $value['avaliableUsdtBalance'] : $value['customUsdtPackage'] ;
    }

    if(isset($value['urgent_issue']) && $value['urgent_issue'] == 1){
        $row_class = "background-color:#f13c3c63 !important;";
    }else{
        $row_class = "";
    }
    ?>

    <tr style="text-align:center;<?Php echo $row_class; ?>" >

        <td><?php echo $count; ?></td>

        <td>
          <?php if(isset($value['profile_pic']) && $value['profile_pic'] != ''){?>
            <img src="https://app.digiebot.com/assets/profile_images/<?php echo $value['profile_pic']; ?>" alt="" class="img-circle" width="39" height="39">
          <?php } else {?>
            <img src="<?php echo ASSETS; ?>images/empty_user.png" alt="" class="img-circle" width="39" height="39"/>
          <?php }?>
          <br><br>
          <span id="copy_username" class="text-center"><?php echo $value['username'] ;?></span>
        </td>

        <td style="font-weight:100" >
          <?php if($value['dailTradeAbleBalancePercentage'] == 5){ 
            $selectTypeAtg = 'Very Defensive';
          }elseif($value['dailTradeAbleBalancePercentage'] == 7.5){
            $selectTypeAtg = 'Defensive';
          }elseif($value['dailTradeAbleBalancePercentage'] == 10){
            $selectTypeAtg = 'Normal';
          }elseif($value['dailTradeAbleBalancePercentage'] == 15){
            $selectTypeAtg = 'Aggressive';
          }elseif($value['dailTradeAbleBalancePercentage'] == 20){
            $selectTypeAtg = 'Very Aggressive';
          } else{
            $selectTypeAtg = '';
          } ?>
          <input type="hidden" value="<?php echo $value['username']?>" id="username1">
          <span style="float:left" id="copy"><a href="#" data-toggle="tooltip" title="<?php echo $value['username'] ?>"><?php echo $value['first_name'].' '.$value['last_name'] ;?></a></span>
          <br>
          <br>
          <span style="float:left"><?php echo $value['exchange'];?></span><?php if(isset($value['is_api_key_valid']) && $value['is_api_key_valid'] == 'yes'){?>  <span class="fa fa-check" style="color:green; float:right"></span> <?php }else{?> <span class="fa fa-close" style="color:red; float:right"></span> <?php } ?>
          <br>
          <span style="float:left" title="<?php echo $selectTypeAtg;?>">ATG</span><?php if(isset($value['agt']) && $value['agt'] == 'yes'){?>  <span class="fa fa-check" style="color:green; float:right"></span> <?php }else{?> <span class="fa fa-close" style="color:red; float:right"></span> <?php } ?>
          <br>
          <?php if(isset($value['account_block']) && $value['account_block'] == 'yes'){?>
            <span style="float:left" >Blocked</span><?php if(isset($value['account_block']) && $value['account_block'] == 'yes'){?>  <span class="fa fa-check" style="color:red; float:right"></span> <?php } ?>
            <br>
          <?php } ?>
          <span title="<?php echo "Selected BTC pkg: ".$value['customBtcPackage']."\r\nCustom Btc Package in BTC: ".$btcPkg;?>"><span class="label label-success"  bg-secondary text-white><?php if(isset($value['dailyTradeableBTCLimit$'])){?><?php echo "BTC Limit $: ".number_format($value['dailyTradeableBTCLimit$'], 2);}else{echo "BTC Limit 0";}?></span></span>
          <br>
          <span title="<?php echo "Selected USDT pkg: ".$value['customUsdtPackage']."\r\nCustom Usdt Package $: ".$usdtPkg;?>"><span class="label label-success" ><?php if(isset($value['dailyTradeableUSDTLimit'])){?><?php echo "USDT Limit: ".number_format($value['dailyTradeableUSDTLimit'], 2);}else{echo "USDT Limit: 0";}?></span></span>
        </td>   

        <td>
          <?php   
            if(isset($value['modified_time'])){
              $join_date = $value['joining_date']->toDateTime()->format("Y-m-d H:i:s");
            }
            $time_zone = date_default_timezone_get();
            $this->load->helper('common_helper');
            $last_time_ago = time_elapsed_string($join_date , $time_zone);
          ?>
          <ahref="#" class="label label-info" data-toggle="tooltip" title="<?php echo $join_date;?>"><?php echo $last_time_ago;?></a>
        </td>
        <td>
          <?php   
            if(isset($value['last_api_updated']) && !empty($value['last_api_updated'])){
              $api_date = $value['last_api_updated']->toDateTime()->format("Y-m-d H:i:s");
              $time_zone = date_default_timezone_get();
              $this->load->helper('common_helper');
              $last_api_ago = time_elapsed_string($api_date , $time_zone);
            }
          ?>
          <?php if(isset($value['last_api_updated']) && !empty($value['last_api_updated'])){ ?>
            <a href="#" class="label label-info" data-toggle="tooltip" title="<?php echo $api_date;?>"><?php echo $last_api_ago;?></a>
          <?php }else{ echo "N/A"; }?>
        </td>
        <?php $account_worth = (float)$value['actual_deposit'] + (isset($value['totalAccountWorth_manual'])?(float)$value['totalAccountWorth_manual']:0.0); ?>
        <td><span title="<?php echo "tradeable balance Limit using pakage $:".$value['tradeAbleBalanceBaseOnPakge']."\r\nAccount worth(Auto Trades) $:".$value['actual_deposit']."\r\nAccount worth(Manual Trades) $:".$value['totalAccountWorth_manual'];?>" style="float:right"><?php echo number_format($account_worth, 2); ?><span></td>
        
        <?php if( ((float)$value['total_balance']) < 15){?>
        
          <td style="" class="bg-danger "><span style="float:right" title="<?php echo "BTC Avaliable Balance :".$value['avaliableBtcBalance']."\r\n USDT Avaliable Balance :".$value['avaliableUsdtBalance'];?>"><?php echo number_format($value['total_balance'], 0);?></span></td>
        <?php }else{?>
        
          <td><span style="float:right" title="<?php echo "BTC Avaliable Balance :".$value['avaliableBtcBalance']."\r\n USDT Avaliable Balance : ".$value['avaliableUsdtBalance']; ?>"><?php echo number_format($value['total_balance'], 0);?></span></td>
        <?php } ?>

        <td style ="text-align:right"><?php echo $value['sold_trades'];?></td>  

        <td style ="text-align:right"><?php echo $value['open_order'];?></td> 
        <?php if(isset($value['bnb_balance']) && $value['bnb_balance'] > 0){
          $class = "";}
          else{
            $class = "bg-danger";
          }?>
        <td style ="text-align:right" class="<?php echo $class; ?>">
          <?php if(isset($value['bnb_balance']) && $value['bnb_balance'] > 0){?>  <span class="fa fa-check" style="color:green; float:right"></span> <?php }else{?> <span class="fa fa-close" style="float:right" class="text-danger"></span> <?php } ?>
        </td>

        <td style ="text-align:right"><?php echo $value['lth_order'];?></td>

        <td style ="text-align:right"><?php echo $value['costAvgOrder'];?></td>
        
        <!-- open btc percentage  -->
        <td> 
          <div class="circle" id="circle-a" data-value="<?php echo $value['openBalanceBtcpercentage']/100;?>" data-fill="{
              &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">
              <strong><span title="<?php echo "Open BTC: ".$value['openTotalBtc'];?>"><?php echo number_format($value['openBalanceBtcpercentage'], 1);?>%</span></strong>
            </div>
            <br>
            <?php $convertIntoDollarsOpen = convert_btc_to_usdt($value['openTotalBtc']);  ?>
           <span class="label label-info"><?="$:".number_format($convertIntoDollarsOpen,3);?></span>
        </td> 

        <!-- open USDT percentage -->
        <td>   
          <div class="circle" id="circle-a" data-value="<?php echo $value['openBalanceUsdtpercentage']/100;?>" data-fill="{
              &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">
              <strong><span title="<?php echo "Open USDT: ".$value['open_usdt'];?>"><?php echo number_format($value['openBalanceUsdtpercentage'], 1);?>%</span></strong>
            </div>
            <br>
           <span class="label label-info"><?="$:".number_format($value['open_usdt'],3);?></span>
        </td> 

        <!-- lth balance BTC percentage  -->
        <td>    
          <?php if($value['lthBalanceBtcpercentage'] < 30){ ?>
            <div class="circle" id="circle-a" data-value="<?php echo $value['lthBalanceBtcpercentage']/100;?>" data-fill="{
              &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}"> 
              <strong><span title="<?php echo "LTH BTC: ".$value['lthBTCTotal'];?>"><?php echo number_format($value['lthBalanceBtcpercentage'], 1);?>%</span></strong>
            </div> 

            <?php } elseif($value['lthBalanceBtcpercentage'] >=30 && $value['lthBalanceBtcpercentage'] < 50 ){ ?>
            <div class="circle" id="circle-a" data-value="<?php echo $value['lthBalanceBtcpercentage']/100;?>" data-fill="{
              &quot;color&quot;: &quot;rgba(236, 179, 47) &quot;}"> 
              <strong><span title="<?php echo "LTH BTC".$value['lthBTCTotal'];?>"><?php echo number_format($value['lthBalanceBtcpercentage'], 1);?>%</span></strong>
            </div> 

            <?php } elseif($value['lthBalanceBtcpercentage'] >50 ){ ?>
              <div class="circle" id="circle-a" data-value="<?php echo $value['lthBalanceBtcpercentage']/100;?>" data-fill="{
                &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}"> 
                <strong><span title="<?php echo "LTH BTC".$value['lthBTCTotal'];?>"><?php echo number_format($value['lthBalanceBtcpercentage'], 1);?>%</span></strong>
              </div> 
            <?php } ?>
           <br>
           <?php $btcLTHBalance = convert_btc_to_usdt($value['lthBTCTotal']);  ?>
           <span class="label label-info"><?="$:".number_format($btcLTHBalance,6);?></span>
        </td>

        <!-- lth balance USDT percentage   -->
        <td>    
          
          <?php if($value['lthBalanceUsdtpercentage'] < 30){ ?>
            <div class="circle" id="circle-a" data-value="<?php echo $value['lthBalanceUsdtpercentage']/100;?>" data-fill="{
              &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}"> 
              <strong><span title="<?php echo "LTH USDT:".$value['lth_usdt']."\r\n LTH BTC".$value['lthBTCTotal'];?>"><?php echo number_format($value['lthBalanceUsdtpercentage'], 1);?>%</span></strong>
            </div> 

            <?php } elseif($value['lthBalanceUsdtpercentage'] >=30 && $value['lthBalanceUsdtpercentage'] < 50 ){ ?>
            <div class="circle" id="circle-a" data-value="<?php echo $value['lthBalanceUsdtpercentage']/100;?>" data-fill="{
              &quot;color&quot;: &quot;rgba(236, 179, 47) &quot;}"> 
              <strong><span title="<?php echo "LTH USDT:".$value['lth_usdt']."\r\n LTH BTC".$value['lthBTCTotal'];?>"><?php echo number_format($value['lthBalanceUsdtpercentage'], 1);?>%</span></strong>
            </div> 

            <?php } elseif($value['lthBalanceUsdtpercentage'] >50 ){ ?>
              <div class="circle" id="circle-a" data-value="<?php echo $value['lthBalanceUsdtpercentage']/100;?>" data-fill="{
                &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}"> 
                <strong><span title="<?php echo "LTH USDT:".$value['lth_usdt']."\r\n LTH BTC".$value['lthBTCTotal'];?>"><?php echo number_format($value['lthBalanceUsdtpercentage'], 1);?>%</span></strong>
              </div> 
            <?php } ?>
           <br>
           <span class="label label-info"><?="$:".number_format($value['lth_usdt'],3);?></span>
        </td>

          <!-- cost average btc balance percentage  -->
        <td><?php
          if(!empty($value['costAvgBtcBalance'])){
            $convertIntoDollarsCA = convert_btc_to_usdt($value['costAvgBtcBalance']); //helper function for btc to usdt amount 
          }else{
            $convertIntoDollarsCA =0;
          }      
          ?>
         <div class="circle" id="circle-a" data-value="<?php echo $value['costAvgBalanceBtcpercentageBinance']/100;?>" data-fill="{
            &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}"> 
            <strong><span title="<?php echo "Cost Avg BTC: ".$value['costAvgBtcBalance'];?>"><?php echo number_format($value['costAvgBalanceBtcpercentageBinance'], 1);?>%</span></strong>
           </div> 
           <br>
           <span class="label label-info"><?="$ :".number_format($convertIntoDollarsCA,3);?></span>
        </td>

          <!-- cost avg usdt balance percentage -->
        <td>
         <div class="circle" id="circle-a" data-value="<?php echo $value['costAvgBalanceUsdtpercentageBinance']/100;?>" data-fill="{
            &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}"> 
            <strong><span title="<?php echo "Cost Avg USDT:".$value['costAvgUsdtBalance'];?>"><?php echo number_format($value['costAvgBalanceUsdtpercentageBinance'], 1);?>%</span></strong>
           </div> 
           <br>
           <span class="label label-info"><?="$:".number_format($value['costAvgUsdtBalance'],3);?></span>
        </td>

        <!-- LTH and CA Percentage -->
        <td>
          <?php $lthcabtc = number_format($value['lthBalanceBtcpercentage'], 1)+number_format($value['costAvgBalanceBtcpercentageBinance'],1);?>
          <?php $lthcausdt = number_format($value['lthBalanceUsdtpercentage'], 1)+number_format($value['costAvgBalanceUsdtpercentageBinance'], 1);?>
         <div class="circle" id="circle-a" data-value="<?php echo $value['lth_cost_avg_balance_percentage']/100;?>" data-fill="{
            &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}"> 
            <strong><span title='<?php echo "lth Cost Avg BTC:  ".$lthcabtc."%\r\nlth Cost Avg USDT:  ".$lthcausdt."%";?>'><?php echo number_format($value['lth_cost_avg_balance_percentage'], 1);?>%</span></strong>
           </div> 
           <br>
           <!-- <span class="label label-info"><?="$:".number_format($value['costAvgUsdtBalance'],3);?></span> -->
        </td>

        <td>
          <span title= "Less in exchange"class="label label-danger"><?="$: ".number_format($value['lessBalance'],6);?></span>
            <br><br>
          <span title= "Extra in exchange" class="label label-success"><?="$: ".number_format($value['extraBalance'],6);?></span>
        </td>

        <!-- expected trade count -->
        <td>
          <span style="float:right"class="label label-success"><?php echo "BTC Expected: ".$value['dailyTradesExpectedBtc'];?></span>
            <br>
          <span style="float:right" class="label label-success"><?php echo "USDT Expected: ".$value['dailyTradesExpectedUsdt'];?></span>
        </td>

        <td>
          <span style="float:right"class="label label-success"><?php echo "Pakage: ".$value['trade_limit'];?></span>
            <br>
          <span style="float:right" class="label label-info"><?php echo "Total: ".$value['totalPointsApi'];?></span>  
            <br>
          <span style="float:right" class="label label-info"><?php echo "Consumed: ".$value['consumed_points'];?></span>
          <br>
          <?php if($value['remainingPoints'] <= 10){ ?>
            <span style="float:right" class= "label label-danger" ><?php echo "Remaning: ".$value['remainingPoints'];?></span>
          <?php }else{ ?>
            <span style="float:right" class= "label label-info" ><?php echo "Remaning: ".$value['remainingPoints'];?></span>
         <?php }?>
        </td>

        <td>
          <span class="label label-success" title="Current Month Consumed Points"><?php echo $value['thisMonthConsumedPoints']; ?> </span>
        </td>
        
        <td style="width:200%">
          <!-- btc completion bar display -->
          <?php
            if($value['dailyTradeBuyBTCIn$'] == 0 || !isset($value['dailyTradeBuyBTCIn$']) || !isset($value['dailyTradeableBTCLimit$'])){
              $compeletion_bar = 0;
            }elseif($value['dailyTradeBuyBTCIn$'] == $value['dailyTradeableBTCLimit$']){
              $compeletion_bar = 100;
            }else{
              $compeletion_bar = ($value['dailyTradeBuyBTCIn$'] / $value['dailyTradeableBTCLimit$'] )*100;
          }?>
          <?php if($compeletion_bar <= 0){?>
            <span style ="display: inline-block;" title="<?php echo "BTC Detail \r\n In $:".$value['dailyTradeBuyBTCIn$']."\r\nCount:".$value['dailyBtcBuyTradeCount'];?>">               
              <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:200%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php } elseif($compeletion_bar >0  && $compeletion_bar <= 50 ){?>
            <span style ="display: inline-block;" title="<?php echo "BTC Detail \r\n In $:".$value['dailyTradeBuyBTCIn$']."\r\nCount:".$value['dailyBtcBuyTradeCount'];?>">               
              <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: <?php echo $compeletion_bar;?>%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php }elseif($compeletion_bar > 50 && $compeletion_bar < 80){?>  
            <span style ="display: inline-block;" title="<?php echo "BTC Detail \r\n In $".$value['dailyTradeBuyBTCIn$']."\r\nCount:".$value['dailyBtcBuyTradeCount'];?>">
              <div class="progress-bar progress-bar-striped active progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: <?php echo $compeletion_bar;?>%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php } elseif($compeletion_bar >= 80 && $compeletion_bar <= 120){?>  
            <span style ="display: inline-block;" title="<?php echo "BTC Detail \r\n In $:".$value['dailyTradeBuyBTCIn$']."\r\nCount:".$value['dailyBtcBuyTradeCount'];?>">
              <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: <?php echo $compeletion_bar;?>%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php } elseif($compeletion_bar > 100){?> 
            <span style ="display: inline-block;" title="<?php echo "BTC Detail \r\n In $".$value['dailyTradeBuyBTCIn$']."\r\nCount:".$value['dailyBtcBuyTradeCount']."\r\n Percentage:".number_format($compeletion_bar,2)."%";?>"> 
              <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:180%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span> 
          <?php } ?> 
          
          <br>
                <!-- usdt completion bar display --> 
          <?php
            if($value['dailyTradeBuyUSDT'] == 0 || !isset($value['dailyTradeBuyUSDT']) || !isset($value['dailyTradeableUSDTLimit'])){
              $compeletion_bar = 0;
            }elseif($value['dailyTradeBuyUSDT'] == $value['dailyTradeableUSDTLimit']){
              $compeletion_bar = 100;
            }else{
              $compeletion_bar = ($value['dailyTradeBuyUSDT'] / $value['dailyTradeableUSDTLimit'] )*100;
          }?>
          <?php if($compeletion_bar <= 0){?>
            <span style ="display: inline-block;" title="<?php echo "USDT Detail \r\nIn $:".$value['dailyTradeBuyUSDT']."\r\nCount:".$value['dailyUSDTBuyTradeCount'];?>">               
              <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:200%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php } elseif($compeletion_bar > 0  && $compeletion_bar <= 50 ){?>
            <span style ="display: inline-block;" title="<?php echo "USDT Detail \r\nIn $:".$value['dailyTradeBuyUSDT']."\r\nCount:".$value['dailyUSDTBuyTradeCount'];?>">               
              <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: <?php echo $compeletion_bar;?>%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php }elseif($compeletion_bar > 50 && $compeletion_bar < 80){?>  
            <span style ="display: inline-block;" title="<?php echo "USDT Detail \r\n In $:".$value['dailyTradeBuyUSDT']."\r\nCount:".$value['dailyUSDTBuyTradeCount'];?>">
              <div class="progress-bar progress-bar-striped active progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: <?php echo $compeletion_bar;?>%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php } elseif($compeletion_bar >= 80 && $compeletion_bar <= 120){?>  
            <span style ="display: inline-block;" title="<?php echo "USDT Detail \r\n In $:".$value['dailyTradeBuyUSDT']."\r\nCount:".$value['dailyUSDTBuyTradeCount'];?>">
              <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: <?php echo $compeletion_bar;?>%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php } elseif($compeletion_bar > 100){?> 
            <span style ="display: inline-block;" title="<?php echo "USDT Detail \r\n In $:".$value['dailyTradeBuyUSDT']."\r\nCount:".$value['dailyUSDTBuyTradeCount']."\r\n Percentage:".number_format($compeletion_bar,2)."%";?>"> 
              <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:180%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span> 
          <?php } ?> 
        </td>

        <td title='New Perc 30 days is =<?php echo isset($value["new_thirty_days_perc"])?(float)$value['new_thirty_days_perc']:'N/A'; ?>'> 
         <?php    //last 30 days invest %
         if($value['customBtcPackage'] > 0 && $value['invest_amount'] > 0 ){ 
            // customBtcPackage
            $customBtcPackageDollar = 0;  
            if($btcPkg > 0){           
              $customBtcPackageDollar = convert_btc_to_usdt($btcPkg); //helper function for btc to usdt amount    245, 207, 67//yellow  237, 7, 49//red  31, 183, 79//green
            }
              //$investPercentage =  (($value['invest_amount'] / ($usdtPkg + $customBtcPackageDollar ))*100); 
              $investPercentage = isset($value["new_thirty_days_perc"])?(float)$value['new_thirty_days_perc']:'N/A';
             // echo 'amount';print_r($value['invest_amount']); 
             // echo 'usdt_pckg';print_r($usdtPkg); 
             // echo 'btc_pckg';print_r($customBtcPackageDollar); 
             // echo 'percentage';print_r($investPercentage);   exit;
            
          }else{
            $investPercentage = 0;
            $customBtcPackageDollar = 0;
          }

          if($investPercentage >= 0 && $investPercentage <= 100){ 
          
            $first    =  237;
            $secound  =  7;
            $third    =  49;
          }elseif($investPercentage > 100 && $investPercentage <= 200){

            $first    =  230; 
            $secound  =  138;
            $third    =  0;

          }elseif($investPercentage >= 201 && $investPercentage <= 350){

            $first    =  31;  
            $secound  =  183;
            $third    =  79;

          }elseif($investPercentage > 350){

            $first    =  230;   
            $secound  =  230;
            $third    =  0;
  
          }
          ?>
          
          <div class="circle" id="circle-a" data-value="<?php echo $investPercentage/300;?>" data-fill="{
            &quot;color&quot;: &quot;rgba(<?php echo $first.",".$secound.",".$third ?>) &quot;}">        
            <strong><?php echo number_format($investPercentage, 2);?>%</strong>
          </div> 
         
        </td>

        <td>
             <!-- BTC previous day limit detail -->
          <?php
            if($value['previousDailyTradeBuyBTCIn$'] == 0 || !isset($value['previousDailyTradeBuyBTCIn$']) && empty($value['PreviousDailyTradeableBTCLimit$'])){
              $compeletion_bar = 0;
            }elseif($value['PreviousDailyTradeableBTCLimit$'] == $value['previousDailyTradeBuyBTCIn$'] && $value['previousDailyTradeBuyBTCIn$'] !=0 && $value['PreviousDailyTradeableBTCLimit$'] !=0 && !empty($value['PreviousDailyTradeableBTCLimit$'])){
              $compeletion_bar = 100;
            }else{
              $compeletion_bar = ($value['previousDailyTradeBuyBTCIn$']/ $value['PreviousDailyTradeableBTCLimit$'] )*100;
            }
          ?>
          <?php if($compeletion_bar <= 0){?>
            <span style ="display: inline-block;" title="<?php echo "BTC Detail \r\n limit was:".$value['PreviousDailyTradeableBTCLimit$']."\r\nCount:".$value['previousDailyBtcBuyTradeCount']."\r\n Buy:".$value['previousDailyTradeBuyBTCIn$'];?>">
              <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: 200%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php } elseif($compeletion_bar > 0 && $compeletion_bar <= 50 ){?>
            <span style ="display: inline-block;" title="<?php echo "BTC Detail \r\n limit was:".$value['PreviousDailyTradeableBTCLimit$']."\r\nCount:".$value['previousDailyBtcBuyTradeCount']."\r\n Buy:".$value['previousDailyTradeBuyBTCIn$'];?>">
              <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: <?php echo $compeletion_bar;?>%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php } elseif($compeletion_bar > 50 && $compeletion_bar <= 80){?>  
            <span style ="display: inline-block;" title="<?php echo "BTC Detail \r\n Limit Was:".$value['PreviousDailyTradeableBTCLimit$']."\r\nCount:".$value['previousDailyBtcBuyTradeCount']."\r\n Buy:".$value['previousDailyTradeBuyBTCIn$'];?>">
              <div class="progress-bar progress-bar-striped active progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: <?php echo $compeletion_bar;?>%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php } elseif($compeletion_bar > 80 && $compeletion_bar <= 120){?>  
            <span style ="display: inline-block;"title="<?php echo "BTC Detail \r\n Limit Was:".$value['PreviousDailyTradeableBTCLimit$']."\r\nCount:".$value['previousDailyBtcBuyTradeCount']."\r\n Buy:".$value['previousDailyTradeBuyBTCIn$'];?>">
              <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: <?php echo $compeletion_bar;?>%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php } elseif($compeletion_bar > 100){?> 
            <span style ="display: inline-block;" title="<?php echo "BTC Detail \r\n Limit Was:".$value['PreviousDailyTradeableBTCLimit$']."\r\nCount:".$value['previousDailyBtcBuyTradeCount']."\r\n Buy:".$value['previousDailyTradeBuyBTCIn$']."\r\n Percentage:".number_format($compeletion_bar,2)."%";?>"> 
              <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:180%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span> 
          <?php } ?> 
            <br>
            <!-- USDT previous day limit detail -->  
          <?php
            if($value['previousDailyTradeBuyUSDT'] == 0 || !isset($value['previousDailyTradeBuyUSDT'])){
              $compeletion_bar = 0;
            }elseif($value['PreviousDailyTradeableUSDTLimit'] == $value['previousDailyTradeBuyUSDT'] && $value['previousDailyTradeBuyUSDT'] !=0 && $value['PreviousDailyTradeableUSDTLimit'] !=0){
              $compeletion_bar = 100;
            }else{
              $compeletion_bar = ($value['previousDailyTradeBuyUSDT']/ $value['PreviousDailyTradeableUSDTLimit'] )*100;
            }
          ?>
          <?php if($compeletion_bar <= 0){?>
            <span style ="display: inline-block;" title="<?php echo "USDT Detail \r\n limit was:".$value['PreviousDailyTradeableUSDTLimit']."\r\nCount:".$value['previousDailyUSDTBuyTradeCount']."\r\n Buy:".$value['previousDailyTradeBuyUSDT'];?>">
              <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: 200%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php } elseif($compeletion_bar > 0 && $compeletion_bar <= 50 ){?>
            <span style ="display: inline-block;"title="<?php echo "USDT Detail \r\n limit was:".$value['PreviousDailyTradeableUSDTLimit']."\r\nCount:".$value['previousDailyUSDTBuyTradeCount']."\r\n Buy:".$value['previousDailyTradeBuyUSDT'];?>">
              <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: <?php echo $compeletion_bar;?>%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php } elseif($compeletion_bar > 50 && $compeletion_bar <= 80){?>  
            <span style ="display: inline-block;"title="<?php echo "USDT Detail \r\n Limit Was:".$value['PreviousDailyTradeableUSDTLimit']."\r\nCount:".$value['previousDailyUSDTBuyTradeCount']."\r\n Buy:".$value['previousDailyTradeBuyUSDT'];?>">
              <div class="progress-bar progress-bar-striped active progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: <?php echo $compeletion_bar;?>%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php } elseif($compeletion_bar > 80 && $compeletion_bar <= 120){?>  
            <span style ="display: inline-block;"title="<?php echo "USDT Detail \r\n Limit Was:".$value['PreviousDailyTradeableUSDTLimit']."\r\nCount:".$value['previousDailyUSDTBuyTradeCount']."\r\n Buy:".$value['previousDailyTradeBuyUSDT'];?>">
              <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: <?php echo $compeletion_bar;?>%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span>
          <?php } elseif($compeletion_bar > 100){?> 
            <span style ="display: inline-block;" title="<?php echo "USDT Detail \r\n Limit Was:".$value['PreviousDailyTradeableUSDTLimit']."\r\nCount:".$value['previousDailyUSDTBuyTradeCount']."\r\n Buy:".$value['previousDailyTradeBuyUSDT']."\r\n Percentage:".number_format($compeletion_bar,2)."%";?>"> 
              <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:180%;border-radius:3px;color:black">
                <?php echo number_format($compeletion_bar, 2)."%";?>
              </div>
            </span> 
          <?php } ?> 
        </td>

        <td>
          <?php    
          if(!empty($value['last_trade_buy']) && isset($value['last_trade_buy'])){
            $buy_time = $value['last_trade_buy']->toDateTime()->format("Y-m-d H:i:s");
            $buy_time_ago = time_elapsed_string($buy_time , $time_zone); ?>
            <span class="label label-info" title="Buy time= <?php echo $buy_time; ?>"> <?php echo $buy_time_ago; ?></span> <br>
          <?php } else { ?>
            <span class="label label-info"><?="No Buy Found"; ?></span>
          <?php } 
          if(!empty($value['last_trade_sold']) && isset($value['last_trade_sold'])){
            $sell_time = $value['last_trade_sold']->toDateTime()->format("Y-m-d H:i:s");
            $sell_time_ago = time_elapsed_string($sell_time , $time_zone); ?>
            <span class="label label-info" title="Sell time= <?php echo $sell_time; ?>"> <?php echo $sell_time_ago; ?></span>
          <?php } else { ?>
            <span class="label label-info"><?="No Sell Found";?></span>
          <?php } ?>
        </td>

        <td>
          <?php 
          if(isset($value['avg_sold'])){
            $avg_sold = number_format($value['avg_sold'], 2);
            if($avg_sold < 0 ){ ?>
              <div class="circle" id="circle-a" data-value="<?php echo $avg_sold/-4;?>" data-fill="{
                &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                <strong><?php echo number_format($avg_sold, 2);?>%</strong>
              </div>
            <?php } elseif($avg_sold >= 0 && $avg_sold < 1 ){ 

              $firstColor   =   237;
              $secondColor  =   7;
              $thirdColor   =   49;

            }elseif($avg_sold >=1 && $avg_sold < 1.2){

              $firstColor   =   245;
              $secondColor  =   207;
              $thirdColor   =   67;

            }elseif($avg_sold >=1.2 && $avg_sold < 1.6){

              $firstColor   =   177;
              $secondColor  =   185;
              $thirdColor   =   2;

            }elseif($avg_sold >=1.2 && $avg_sold < 1.6){
              
              $firstColor   =   122;
              $secondColor  =   200;
              $thirdColor   =   123;

            }elseif($avg_sold >= 1.6){
              
              $firstColor   =   31;
              $secondColor  =   183;
              $thirdColor   =   79;
              
            }
            ?>
            <div class="circle" id="circle-a" data-value="<?php echo $avg_sold/4;?>" data-fill="{
              &quot;color&quot;: &quot;rgba(<?php echo $firstColor.",".$secondColor.",".$thirdColor ?>) &quot;}">      
              <strong><?php echo number_format($avg_sold, 2);?>%</strong>
            </div> 
            <?php  
          } ?>
        </td>

        <td>
          <?php if(isset($value['invest_amount'])){echo number_format($value['invest_amount'], 0);}?>
        </td>

        <td>
          <?php if(isset($value['lastMonthProfitSoldOrders'])){echo "$: ".number_format($value['lastMonthProfitSoldOrders'], 3);}?>
        </td>

        <td>
          <?php if(isset($value['last_login_time'])){ 
            $login_time = $value['last_login_time']->toDateTime()->format("Y-m-d H:i:s");
            $login_time_ago = time_elapsed_string($login_time , $time_zone);?>
            <span class="label label-info" title="Login Time= <?php echo $login_time; ?>"> <?php echo $login_time_ago; ?></span>
          <?php } ?>
        </td>


        <!-- draw saven dot -->
        <td style="text-align:center">
          <?php 
            if( $value['totalbuySellOrdersCountWithinSavenDays'] <= 10  ){

              $class =  'danger';
            } elseif( $value['totalbuySellOrdersCountWithinSavenDays'] > 10 &&   $value['totalbuySellOrdersCountWithinSavenDays'] < 20 ){
              
              $class =  'info';
            }elseif( $value['totalbuySellOrdersCountWithinSavenDays'] >= 20 ){
              
              $class =  'success';
            }else{
              
              $class = 'danger'; 
            }

          //lth    

            $lth_balance_per =  (float)($value['lthBalanceUsdtpercentage']   + $value['lthBalanceBtcpercentage']);
            if( $lth_balance_per <= 30){
              
              $classLTH =  'success';
            }elseif($lth_balance_per > 30 && $lth_balance_per <= 60){ 
            
              $classLTH =  'info';
            }else{

              $classLTH =  'danger';
            } 


            //last 30 days investment 
            if($investPercentage <= 100){
              
              $classInvest = 'danger';
            }elseif($investPercentage >= 100 && $investPercentage < 200){

              $classInvest = 'info';
            }else{

              $classInvest = 'success';
            }

             //balance
             if($value['total_balance'] <= 1000 ){

              $classBalance = 'danger';
             }elseif( $value['total_balance'] > 1000  && $value['total_balance'] < 2000){

              $classBalance = 'info';
             }else{

              $classBalance = 'success';
             }

             //extra less
            //  extraBalance    lessBalance  
            if($value['extraBalance'] <= 10 ){

              $classExtra = 'success';
            }elseif( $value['extraBalance'] > 10 && $value['extraBalance'] <= 100){
              
              $classExtra = 'info';
            }else{

              $classExtra = 'danger';
            }


            //avg
            if($value['avg_sold'] <= 0){

              $classAvgSold = 'danger';
            }elseif($value['avg_sold'] > 0 && $value['avg_sold'] < 2){

              $classAvgSold = 'info';
            }else{

              $classAvgSold = 'success';
            }

            // api key valid and balance 20
            if( $value['is_api_key_valid'] == 'yes' && $value['total_balance'] >= 20){

              $classApi = 'success';
            }else{

              $classApi = 'danger';
            }

         echo '<table>
            <tr>
              <td title="buy count trade"><div class="square bg-'.$class.'  ">              </div>  </td>  
              <td title ="lth balance Percentage"><div class="square bg-'.$classLTH.' ">   </div>  </td> 
              <td title= "Invest percentage"><div class="square  bg-'.$classInvest.' ">     </div>  </td> 
              <td title="Avaliale balance"><div class="square  bg-'.$classBalance.' ">      </div>  </td> 
              <td title="Extra Balance"><div class="square  bg-'.$classExtra.' ">           </div>  </td> 
              <td title="Avg sold"><div class="square  bg-'.$classAvgSold.' ">              </div>  </td> 
              <td title="Api key and balace"><div class="square  bg-'.$classApi.' ">  </div>  </td>
            </tr>
          </table>';
          ?>
        </td>

        <!-- today ratio BTC -->
        <td>
          <?php
            if(!isset($value['todaybuyWorth_BTC']) || !isset($value['soldTodaybuyWorth_BTC'])){  

              $bar = 0;
            }elseif($value['todaybuyWorth_BTC'] == $value['soldTodaybuyWorth_BTC']){

              $bar = 100;
            }else{

              $bar = ($value['soldTodaybuyWorth_BTC'] / $value['todaybuyWorth_BTC'] )*100;
            }
          ?>
            
          <span style ="display: inline-block;" title="<?php echo "Total BTC BUY: ".$value['todaybuyWorth_BTC']."\r\n Sold BTC:".$value['soldTodaybuyWorth_BTC'];?>">               
            <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:<?php echo $bar;?>%;border-radius:3px;color:black">
              <?php echo number_format($bar, 2)."%";?>
            </div>
          </span>
      </td>

      <!-- today ratio USDT -->
      <td>
        <?php
          if(!isset($value['todaybuyWorth_USDT']) || !isset($value['soldTodaybuyWorth_USDT']) ||  $value['todaybuyWorth_USDT'] == 0 || $value['soldTodaybuyWorth_USDT'] == 0){

            $bar = 0;
          }elseif($value['todaybuyWorth_USDT'] == $value['soldTodaybuyWorth_USDT']){
            
            $bar = 100;
          }else{

            $bar = ($value['todaybuyWorth_USDT'] / $value['soldTodaybuyWorth_USDT'] )*100;
          }

          if($bar > 100){

            $progress = 100;
          }else{

            $progress = $bar;
          }
        ?>
        <span style ="display: inline-block;" title="<?php echo "Total USDT BUY: ".$value['todaybuyWorth_USDT']."\r\n Sold USDT:".$value['soldTodaybuyWorth_USDT'];?>">               
          <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:<?php echo $progress;?>%;border-radius:3px;color:black">
            <?php echo number_format($bar, 2)."%";?>
          </div>
        </span>
      </td>

      <!-- last saven days ratio BTC-->
      <td>
        <?php
          if(!isset($value['savenDayBuyWorth_BTC']) || !isset($value['soldSavenDayBuyWorth_BTC'])){

            $bar = 0;
          }elseif($value['savenDayBuyWorth_BTC'] == $value['soldSavenDayBuyWorth_BTC']){

            $bar = 100;
          }else{

            $bar = ($value['soldSavenDayBuyWorth_BTC'] / $value['savenDayBuyWorth_BTC'] )*100;
          }

          if($bar > 100){

            $progress = 100;
          }else{

            $progress = $bar;
          }
        ?>
        <span style ="display: inline-block;" title="<?php echo "Total BTC BUY: ".$value['savenDayBuyWorth_BTC']."\r\n Sold BTC:".$value['soldSavenDayBuyWorth_BTC'];?>">               
          <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:<?php echo $progress;?>%;border-radius:3px;color:black">
            <?php echo number_format($bar, 2)."%";?>
          </div>
        </span>
      </td>
      
      <!-- last saven day ratio USDT-->
      <td>
        <?php
          if(!isset($value['savenDayBuyWorth_USDT']) || !isset($value['soldSavenDayBuyWorth_USDT'])){

            $bar = 0;
          }elseif($value['savenDayBuyWorth_USDT'] == $value['soldSavenDayBuyWorth_USDT']){

            $bar = 100;
          }else{

            $bar = ($value['soldSavenDayBuyWorth_USDT'] / $value['savenDayBuyWorth_USDT'] )*100;
          }
        ?>
        <span style ="display: inline-block;" title="<?php echo "Total USDT BUY: ".$value['savenDayBuyWorth_USDT']."\r\n Sold USDT:".$value['soldSavenDayBuyWorth_USDT'];?>">               
          <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:<?php echo $bar;?>%;border-radius:3px;color:black">
            <?php echo number_format($bar, 2)."%";?>
          </div>
        </span>
      </td>  

        <td>
          <span style="float:right"class="label label-info"><?php echo "New Status: ".$value['newOrderParents'];?></span>
          <br>
          <span style="float:right"class="label label-info"><?php echo "Taking Orders: ".$value['takingOrderParents'];?></span>    
        </td>

        <td>
            <?php echo $value['totalbuySellOrdersCountWithinSavenDays']; ?>
        </td>

        <td>
          <?php
         if(isset($value['modified_time'])){
            $last_modified_time = $value['modified_time']->toDateTime()->format("Y-m-d H:i:s");
            }
          $time_zone = date_default_timezone_get();
          $this->load->helper('common_helper');
          $last_time_ago = time_elapsed_string($last_modified_time , $time_zone);?>
          <a href="#" class="label label-info" data-toggle="tooltip" title="<?php echo $last_modified_time;?>"><?php echo $last_time_ago; ?></a>
        </td>

        <td>
          <span title="Grid View"><a href="https://trading.digiebot.com/bot-grid/<?php echo $value['admin_id'];?>" target="_blank"><i class="fa fa-th"></i></a></span>
          <br><br>
          <span title="Order Report"><a href="https://app.digiebot.com/admin/order_report/buy_limit_order_report/<?php echo $value['admin_id']."/".$value['exchange']."/live";?>" target="_blank"><i class="glyphicon glyphicon-send"></i></a></span>
          <br><br>
          <span title="
          <?php
            foreach($value['history'] as $history){
              // echo "\r\n created: ".$history['created']->toDateTime()->format("m-d")."\r\n daily_buy_usd_worth: ".$history['daily_buy_usd_worth']. "\r\n num_of_trades_buy_today: ".$history['num_of_trades_buy_today']."\r\n daily_buy_usd_limit: ".$history['daily_buy_usd_limit'];
              echo "\r\n created: ".$history['created']->toDateTime()->format("m-d")."\r\n Daily Buy worth BTC: ".$history['daily_bought_btc_usd_worth']. "\r\n Count Btc: ".$history['BTCTradesTodayCount']."\r\n Limit BTC: ".$history['dailyTradeableBTC_usd_worth']."\r\n daily Buy worth USDT: ".$history['daily_bought_usdt_usd_worth']."\r\n Count Usdt: ".$history['USDTTradesTodayCount']."\r\n Limit USDT:".$history['dailyTradeableUSDT_usd_worth'];
            }
          ?>"> <i class="fa fa-eye"></i></span>


          <?php if(!empty($value['exchange'])){ 
          $controllerName = ($value['exchange'] == 'binance') ? 'monthly_calculation_user': 'monthly_calculation_user_'.$value['exchange']; 
          } else { 
            $controllerName = 'monthly_calculation_user';
          }?>

          <br><br>
          <span title="Run Cron"><a href="https://admin.digiebot.com/admin/users_list/<?php echo $controllerName."?userName=".$value['username'];?>" target="_blank"><i class="fa fa-refresh"></i></a></span>
        
          <br><br>
          <span title="Open Profile"><a href="https://admin.digiebot.com/admin/users_list/profile<?php echo "?admin_id=".$value['admin_id']."&exchange=".$value['exchange'];?>" target="_blank"><i class="glyphicon glyphicon-new-window"></i></a></span>
        </td> 

      <td>
        <input type='hidden' class = 'exchangeName' value = '<?php echo $value['exchange']; ?>' >
        <input type='hidden' class = 'admin_id'     value = '<?php echo $value['admin_id']; ?>' >

        <input type="hidden" class= "hiddenvals" name="hiddenvals" value ='<?php echo json_encode((array)$value); ?>'>
          
          <button type="button" id= "contact"class="click btn btn-success" data-toggle="modal" data-target="#myModal">Contact</button>
          <br><br> 
       <!--    <a href="<?php //echo SURL; ?>admin/users_list/updateDate<?php //echo "?admin_id=".$value['admin_id']."&exchange=".$value['exchange'];?>"class="btn btn-success">Good Now</a> -->
          <br><br>
        <button type="button" class="open_remarks_box btn btn-success" data-toggle="modal" data-target="#myModal2">Mark Issues</button>
      </td>
    </tr>
    <?php $count++; }   ?>  
      </tbody>
    </table>
    <div><?php echo "Total: ".$total; ?> <div style="display:none;"><?= print_r($admin_ids_arr); ?></div></div>
    <div><?php echo $links; ?></div>
  </div>
  </div>
  </div>
  </div>
</div>
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.1.3/circle-progress.min.js"></script> 
  <script>
    var progressBarOptions = {
      startAngle: -1,
      size: 60,
    };
    $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function (event, progress, value){
    });

    // throw data on model
    $('.click').click(function(){ 

      var hiddenInput = $(this).siblings('input.hiddenvals').val();
      var data = JSON.parse(hiddenInput);
      var content = '';
      
      if(data['lessBalance'] < -50  ){
                                                      
        content += '*. Fix Less Balace Issue';
      }
      
      if(data['lessBalance'] < -50  ){
                                                    
        content += '<br>*. Fix Less Balace Issue';
      }
      if(data['extraBalance'] > 50 ){

        content += '<br>*. Fix Extra Balace Issue';
      }
      
      if(data['avaliableUsdtBalance'] > 10 ){
                                                                  
        content += '<br>*. USDT Balance Exists but base currency not Selected';
      }
                                                      
      if(data['avaliableBtcBalance'] > 0 ){
          
        content += '<br>*. BTC Balance Exists but base currency not Selected';
      }

      if(data['tradingStatus'] == 'off' ){ 
          
        content += '<br>*. Trading Status is off';
      }
      if(data['dailyTradeableUSDTLimit'] <= 5 ){ 
  
        content += '<br>*. USDT Limit is less than $:5';
      } 

      if(data['dailyTradeableBTCLimit$'] <= 5 ){ 

        content += '<br>*. BTC Limit is less than $:5';
      }
      if(data['avaliableBtcBalance'] <= 0 ){ 

        content += '<br>*. BTC Balance less than 0';
      }

      if(data['avaliableUsdtBalance'] < 20 ){ 

        content += '<br>*. USDT Balance is less than $:20 ';
      }

      if(data['exchange_enabled'] == 'no'){ 

        content += '<br>*. API key Invalid';
      } 
      if(data['agt'] == 'no'){

        content += '<br>*. ATG Disable';
      }
      if(data['remainingPoints'] < 10){  

        content += '<br>*. Remaining Points are less than 10';
      }

      if(data['profile_pic'] == "undefined" ){ 

        content += '<br>*. Profile Picture Not Set';
      }
      
      if(data['open_order'] <= 0){   

        content += '<br>*. No Open Order Found';
      } 

      if(data['bnb_balance'] <= 0 && data['exchange'] == 'binance'){

        content += '<br>*. BNB Not Exists';
      }

      if(data['newOrderParents'] <= 0 ){

        content += '<br>*. Pick Parent yes 0 <br>';
      }
      $('#append').html(content);

      var exchange =  $(this).siblings('input.exchangeName').val();
      var admin_id =  $(this).siblings('input.admin_id').val(); 

      var r = confirm("Are you sure you will contact this person!");
      if (r == true) {
        $.ajax({
          url: "<?php echo SURL; ?>admin/users_list/updateContactDate",
          type: "POST",
          data: {exchange:exchange,  admin_id:admin_id},
          success: function(response){

            $(this).siblings('#contact').hide();
          }
        });
      }

    });
    // end 

    //model 2 code
    $('.open_remarks_box').click(function(){


      var exchange  =  $(this).siblings('input.exchangeName').val(); 
      var admin_id  =  $(this).siblings('input.admin_id').val(); 
      $.ajax({
          url: "<?php echo SURL; ?>admin/users_list/get_comments_by_id",
          type: "POST",
          data: {admin_id:admin_id},
          success: function(response){
            if(response == "False"){
              // do nothing just open the modal.
            }else{
            console.log(response);
            objJson = JSON.parse(response);
            var counter = 0;
            var cols  = "";
            var bg_color = '';
             $("#myTable tbody").html('');
            console.log(objJson[0].prob_statment);
            $.each(objJson[0].prob_statment,function(e,item) {
                console.log(e);
                console.log(item.comments);
                var r_check = '';
                var o_check = '';
                var g_check = '';
                var b_check = '';
                var bg_color = '';        
              cols += '<tr data-id="li'+counter+'"><td class="col-md-4"><textarea id="comments" name="comments[]" rows="4" cols="25" placeholder="Add your problem statement here" class="form-control">'+item.comments+'</textarea></td>';
              if(item.priorityLabel == "#ff5252")
              {
                r_check = "selected";
                bg_color='style="background-color:#ff5252 !important;"';
              }
              if(item.priorityLabel == "#e18644a8")
              {
                o_check = "selected";
                bg_color='style="background-color:#e18644a8 !important;"';
              }
              if(item.priorityLabel == "#4ccdffb0")
              {
                b_check = "selected";
                bg_color='style="background-color:#4ccdffb0 !important;"';
              }
              if(item.priorityLabel == "#28d63fad")
              {
                g_check = "selected";
                bg_color='style="background-color:#28d63fad !important;"';
              }
              cols += '<td><select class="form-control change_color" name="priorityLabel[]" id="priorityLabel" '+bg_color+'><option>Select Label</option><option value="#ff5252" style="background-color:#ff5252 ;"'+r_check+'>Urgent</option><option value="#e18644a8" style="background-color:#e18644a8 ;" '+o_check+'>High</option><option value="#28d63fad" style="background-color:#28d63fad ;" '+g_check+'>Normal</option><option value="#4ccdffb0" style="background-color:#4ccdffb0 ;" '+b_check+'>Low</option></select></td>';
              cols += '<td><input type="button"  data-id="li'+counter+'" class="ibtnDel btn btn-md btn-danger button3 "  value="Delete"></td><tr>';
              counter ++;
            });
            $("#myTable tbody").append(cols);
            $(".change_color").trigger('change');
          }}
        });
      $('#exchange1').val(exchange);
      $('#admin_id1').val(admin_id);
    
    });
    //end
  </script>


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

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
      $(function() {
        availableTags = [];
        $.ajax({
          'url': '<?php echo SURL ?>admin/users_list/get_all_usernames_ajax',
          'type': 'POST',
          'data': "",
          'success': function (response) {
            availableTags = JSON.parse(response);
            $("#user_name").autocomplete({
              source: availableTags
            });
          }
        });
      });
    </script>

     <!-- data table -->
    <!-- <script src="assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>  -->
    <script type="text/javascript">

      if(!localStorage.hidden_cols){
        localStorage.setItem('hidden_cols', JSON.stringify([3,4,6,7,8,9,14,15,21,33]))
      }

      $(document).ready(function() {
        $(".change_color").on('change', function(){
          console.log("i am here");
          $(this).css("background-color",$(this).find('option:selected').val());
        });
        var hCols = [3,4,6,7,8,9,14,15,21,33]; 
        $('#datatable').DataTable({
          
          "aLengthMenu": [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
          "iDisplayLength": 50,
          "dom": "<'row'<'col-sm-4'B><'col-sm-2'l><'col-sm-6'p<br/>i>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12'p<br/>i>>",
          "paging": false,
          "columnDefs": [ 
            {
                "targets": localStorage.hidden_cols ? JSON.parse(localStorage.getItem('hidden_cols')) : [3,4,6,7,8,9,14,15,21,33],
                "visible": false,
                "searchable": true
            },
        ],
          "buttons": [{
            extend: 'colvis',
            collectionLayout: 'three-column',
            text: function() {
              var totCols = $('#datatable thead th').length;
              var hiddenCols = hCols.length;
              var shownCols = totCols - hiddenCols;
              return 'Columns (' + shownCols + ' of ' + totCols + ')';
            },
            prefixButtons: [{
              extend: 'colvisGroup',
              text: 'Show all',
              show: ':hidden'
            }, {
              extend: 'colvisRestore',
              text: 'Restore'
            }]
          }, {
            extend: 'collection',
            text: 'Export',
            buttons: [{
                text: 'Excel',
                extend: 'excelHtml5',
                footer: false,
                exportOptions: {
                  columns: ':visible'
                }
              }, {
                text: 'CSV',
                extend: 'csvHtml5',
                fieldSeparator: ';',
                exportOptions: {
                  columns: ':visible'
                }
              }, {
                text: 'PDF Portrait',
                extend: 'pdfHtml5',
                message: '',
                exportOptions: {
                  columns: ':visible'
                }
              }, {
                text: 'PDF Landscape',
                extend: 'pdfHtml5',
                message: '',
                orientation: 'landscape',
                exportOptions: {
                  columns: ':visible'
                }
              }]
            }]
          ,oLanguage: {
              oPaginate: {
                  sNext: '<span class="pagination-default">&#x276f;</span>',
                  sPrevious: '<span class="pagination-default">&#x276e;</span>'
              }
          }
            ,"initComplete": function(settings, json) {
              // Adjust hidden columns counter text in button -->
              $('#datatable').on('column-visibility.dt', function(e, settings, column, state) {
                var visCols = $('#datatable thead tr:first th').length;
                //Below: The minus 2 because of the 2 extra buttons Show all and Restore
                var tblCols = $('.dt-button-collection li[aria-controls=datatable] a').length - 2;
                $('.buttons-colvis[aria-controls=datatable] span').html('Columns (' + visCols + ' of ' + tblCols + ')');
                e.stopPropagation();
              });
            }
          });
        
        });


        //set colum hide/show in local storage
        $('#datatable').on('column-visibility.dt', function ( e, settings, column, state ) {
            console.log('Column '+ column +' has changed to '+ (state ? 'visible' : 'hidden'));
            if(state){
             let hidden_cols = localStorage.hidden_cols ? JSON.parse(localStorage.getItem('hidden_cols')) : [3,4,6,7,8,9,14,15,21,33];
             hidden_cols = hidden_cols.filter(item =>item != column);
             localStorage.setItem('hidden_cols', JSON.stringify(hidden_cols))
            }else{
              let hidden_cols = localStorage.hidden_cols ? JSON.parse(localStorage.getItem('hidden_cols')) : [3,4,6,7,8,9,14,15,21,33];
              if(!hidden_cols.includes(column)){
                hidden_cols.push(column)
              }
              localStorage.setItem('hidden_cols', JSON.stringify(hidden_cols))
            }
        });
        //end code
       
    </script>
    <!-- end data table -->
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
  // Tooltips script
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
  });

  $(document).ready(function(){

    $(".paging_simple_numbers").hide();
    $(".dataTables_length").hide();
    $("#datatable_info").hide();
    
  });

  //disable inspect elements
  // $(document).bind("contextmenu",function(e) {
  //   e.preventDefault();
  // });
</script> 
    <script type="text/javascript">
      //script for adding new row in modal.
        $("#add_row_user_problem").on("click", function () { // for prob modal inputs addresses
          console.log("will be available soon.");
        var cols = "";
        var counter = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5)
        var newRow = $('<tr data-id="li'+counter+'">');
        cols += '<td><textarea id="comments" name="comments[]" rows="4" cols="25" placeholder="Add your problem statement here" class="form-control"></textarea></td>';
        cols += '<td><select class="form-control change_color"  name="priorityLabel[]" id="priorityLabel"><option>Select Label</option><option value="#ff5252" style="background-color:#ff5252 ;">Urgent</option><option value="#e18644a8" style="background-color:#e18644a8 ;">High</option><option value="#28d63fad" style="background-color:#28d63fad ;">Normal</option><option value="#4ccdffb0" style="background-color:#4ccdffb0 ;">Low</option></select></td>';
        cols += '<td><input type="button"  data-id="li'+counter+'" class="ibtnDel btn btn-md btn-danger button3 "  value="Delete"></td>';
        newRow.append(cols);
        $('#myTable tbody tr:last').after(newRow);
        //$("table.order-list").append(newRow);
        //counter++;
    });
    $("#myTable").on("click", ".ibtnDel", function (event) {
      var delete_index = $(this).attr("data-id");
      console.log(delete_index);
      $('#myTable tr[data-id="'+delete_index+'"]').remove();
    });
  </script>


