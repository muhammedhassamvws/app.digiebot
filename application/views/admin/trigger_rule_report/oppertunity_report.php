    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
    <link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
    <script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="<?php  echo SURL ?>assets/dist/jquery-asPieProgress.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    .ax_1, .ax_2, .ax_3, .ax_4, .ax_5, .ax_6, .ax_7, .ax_8, .ax_9, .ax_10, .ax_11, .ax_12, .ax_13 {
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
    </style>
    <?php //echo "<pre>";  print_r($full_arr); exit; ?>
    <div id="content">
      <h1 class="content-heading bg-white border-bottom">Reports</h1>
      <div class="innerAll bg-white border-bottom">
        <div class="pull-right" style="padding-right: 12px; padding-top: 8px;">
          <div class=" pull-right alert alert-warning" style=" margin-top: -10px; background: #5c678a;color: white;"> <?php echo date("F j, Y, g:i a").'&nbsp;&nbsp;  <b>'.date_default_timezone_get().' (GMT + 0)'.'<b />' ?></div>
        </div>
        <ul class="menubar">
          <li class=""><a href="<?php echo SURL; ?>/admin/trigger_rule_reports/oppertunity_reports">Reports</a></li>
        </ul>
      </div>
      <div class="innerAll spacing-x2">
        <?php
          if ($this->session->flashdata('err_message')) {
        ?>
      <div class="alert alert-danger"><?php echo $this->session->flashdata('err_message'); ?></div>
      <?php }
        if ($this->session->flashdata('ok_message')){?>
          <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('ok_message'); ?></div>
      <?php }?>
      <?php $filter_user_data = $this->session->userdata('filter_order_data');?>
      <div class="widget widget-inverse">
        <div class="widget-body">
          <form method="POST" action="<?php echo SURL; ?>admin/trigger_rule_reports/oppertunity_reports">
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
                    <option value="live"<?=(($filter_user_data['filter_by_mode'] == "live") ? "selected" : "")?>>Live</option>
                    <option value="test"<?=(($filter_user_data['filter_by_mode'] == "test") ? "selected" : "")?>>Test</option>
                  </select>
                </div>
              </div>


              <div class="col-xs-12 col-sm-12 col-md-3 ax_4">
                <div class="Input_text_s">
                  <label>Oppertunity Id: </label>
                  <input id="oppertunity_Id" name="oppertunity_Id" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Search By Oppertunity Id" value="<?=(!empty($filter_user_data['oppertunity_Id']) ? $filter_user_data['oppertunity_Id'] : "")?>" autocomplete="off">
                  </div>
              </div>
        
            <div class="col-xs-12 col-sm-12 col-md-3 ax_5">
              <div class="Input_text_s">
                <label>Exchange: </label>
                <select id="exchange" name="exchange" type="text" class="form-control filter_by_name_margin_bottom_sm">
                  <option value="kraken"<?=(($filter_user_data['exchange'] == "kraken") ? "selected" : "")?>>kraken</option>
                  <option value="binance"<?=(($filter_user_data['exchange'] == "binance") ? "selected" : "")?>>Binance</option>
                </select>
              </div>
            </div>


          <div class="col-xs-12 col-sm-12 col-md-3 ax_6">
            <div class="Input_text_s">
              <label>From Date Range: </label>
              <input id="filter_by_start_date" name="filter_by_start_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_start_date']) ? $filter_user_data['filter_by_start_date'] : "")?>" autocomplete="off">
              <i class="glyphicon glyphicon-calendar"></i> 
            </div>
          </div>

          <div class="col-xs-12 col-sm-12 col-md-3 ax_7">
            <div class="Input_text_s">
              <label>To Date Range: </label>
              <input id="filter_by_end_date" name="filter_by_end_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_end_date']) ? $filter_user_data['filter_by_end_date'] : "")?>" autocomplete="off">
              <i class="glyphicon glyphicon-calendar"></i> 
            </div>
          </div>

          <script type="text/javascript">
            $(function () {
              $('.datetime_picker').datepicker();
            });
          </script>
      
          <div class="col-xs-12 col-sm-12 col-md-3  ax_8" style=" min-height: 60px;">
            <div class="Input_text_s" id="triggerFirst" <?php if ($filter_user_data['group_filter'] == 'rule_group') {?>style="display:block;" <?php } else {?><?php }?>>
              <label>Filter Trigger: </label>
              <select id="filter_by_trigger"  name="filter_by_trigger" type="text" class="form-control  filter_by_trigger">
                <option value="barrier_percentile_trigger" <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_percentile_trigger') {?> selected <?php }?>>Barrier Percentile Trigger</option>
                <option value="barrier_trigger" <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_trigger') {?> selected <?php }?>>BARRIER TRIGGER</option>
              </select>
            </div>
          </div>
          
          <!-- End Hidden Searches -->
          <div class="col-xs-12 col-sm-12 col-md-3  ax_9 filter_by_level"  style=" min-height: 60px;" id="filter_by_level">
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
                <option value="">Level 15</option>
                <option value="level_16"<?php if (in_array('level_16', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 16</option>
                <option value="level_17"<?php if (in_array('level_17', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 17</option>
                <option value="level_18"<?php if (in_array('level_18', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 18</option>
                <option value="level_19"<?php if (in_array('level_19', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 19</option>
                <option value="level_20"<?php if (in_array('level_20', $filter_user_data['filter_by_level'])) {?> selected <?php }?>>Level 20</option>
              </select>
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-3  ax_10 filter_by_rule" style=" min-height: 60px;" id="filter_by_rule">
            <div class="Input_text_s filter_by_rule"  <?php if ($filter_user_data['group_filter'] == 'rule_group') {?> <?php } else {?>  <?php }?>>
              <label>Filter Level: </label>
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

          <div class="col-xs-12 col-sm-12 col-md-2 ax_11" style="padding-top:25px">
            <div class="Input_text_s custom">
              <label></label>   
              <input name="coinPair" type="radio" class="filter_by_name_margin_bottom_sm" value="both"<?php if($filter_user_data['coinPair'] == 'both'){?> checked <?php }?>>Both
              <input name="coinPair" type="radio" class="filter_by_name_margin_bottom_sm" value= "btc"<?php if($filter_user_data['coinPair']== 'btc'){?> checked <?php }?>>BTC
              <input name="coinPair" type="radio" class="filter_by_name_margin_bottom_sm" value="usdt"<?php if($filter_user_data['coinPair'] == 'usdt'){?> checked <?php }?>>USDT 
            </div>
          </div>


          <div class="col-xs-12 col-sm-12 col-md-3 ax_12">
            <div class="Input_text_btn">
              <label></label>
              <span class="ax_10"><a href="<?php echo SURL; ?>admin/trigger_rule_reports/oppertunity_reports"  class="btn btn-danger">Reset</a>  </span>
              <button id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
              <a href="<?php echo SURL; ?>admin/trigger_rule_reports/csv_export_oppertunity"  class="btn btn-info">Export To CSV File</a>
              </span>           
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
    input[type="checkbox"][readonly] {
      pointer-events: none;
    }
  </style>
    <script>
    $(".setsize").each(function() {
        $(this).height($(this).width());
    });
    // });
    $(window).on('resize', function(){
      $(".setsize").each(function() {
        $(this).height($(this).width());
      });
    });
  </script> 
      <div class="widget widget-inverse">
        <div class="widget-body padding-bottom-none table-responsive">
          <table class=" table table-bordered">
            <tr class="theadd">
              <th>Coin</th>
              <th>Level</th>
              <th>Created Date (UTC)</th>
              <th>Compeletion %</th> 
              <th>Sold</th>
              <th>Open/LTH</th>
              <th>Average Sold</th>
              <th>Average Open/lth</th>
              <th>Active parent</th>
              <th>Cost Avg Active parents</th>
              <th title="parent who created child">Executed parent</th>
              <th>Sell Price Signal</th>
              <th>Cancelled</th>
              <th title="35 mints check">Ignored parent</th>
              <th title="range_ignore_check">Range Ignored Parent</th>
              <th title="user have balance issue or parent have less than min quantity">New Error</th>
              <th title="status= submitted, lth_error, submitted_for_sell, fraction_submitted_for_sell etc">Others Status</th>
              <th>Disappear</th>
              <th>Market Sold Price</th>
              <th>Investment</th>
              <th>Within 5 Hour Min Market Bahaviour</th>
              <th>Within 5 Hour Max Market Bahaviour</th>
              <th>Within 10 Hour Min Market Bahaviour</th>
              <th>Within 10 Hour Max Market Bahaviour</th>
              <th>Time Difference</th>
              <th>Last Modified</th>
              <th>Parent Count</th>
              <th>Pick Parent Yes</th>
              <th>Hit Send Time and Buy Time Difference</th>
              <th>Action</th>
           
          <?php

              $coin_arrayBtc  = ['XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC'];
              $coin_arrayUSDT = ['EOSUSDT', 'LTCUSDT','XRPUSDT','NEOUSDT', 'QTUMUSDT','BTCUSDT'];
                          
              if(count($final_array) > 0){
                $avg_open_lth_count   = 0;
                $totalInvertInDollars = 0;
                $avg_sold_count       = 0;
                $total_oops_missed    = 0;
                $total_max5_hour      = 0;
                $total_min5_hour      = 0;
                $total_max10_hour     = 0;
                $total_min10_hour     = 0;
                $total_sold           = 0;
                $total_open_lth       = 0;
                $total_sold_avg       = 0;
                $total_open_avg       = 0;
                $active_parent_total  =0;
                $execuated_parent_total = 0;
                $canceled_total       = 0;
                $ignore_total         = 0;
                $new_error_total      = 0;
                $other_status_total   = 0 ;
                $disappear_total      = 0;
                $countOpportunity     = 0;
                
                $count = 1;
                $final_array          =   array_reverse($final_array);
                $expectedTrades       =   array_reverse($expectedTrades);
                $tradeCountDaily      =   array_reverse($tradeCountDaily);
                $tradeCountDailyUSDT  =   array_reverse($tradeCountDailyUSDT);

            foreach($final_array as $key => $value1){ 
              $countOpportunity += count($value1);
              $btcBuyCount  = 0;
              $usdtBuyCount = 0;
              foreach ($value1 as $value){
                if($value['coin'] =='NCASHBTC'){
                    $coinImage  =  'ncashhhhhhh.png';
                }elseif($value['coin'] =='ETHBTC'){
                  $coinImage = 'ethereum-black-symbol-chrystal-vector-20393411.jpg';
                }elseif($value['coin'] =='TRXBTC'){
                    $coinImage  =  'aaaaaw.jpg'; 
                }elseif($value['coin'] =='EOSBTC'){
                  $coinImage  =  'EOS.jpg';
                }elseif($value['coin'] =='POEBTC'){
                  $coinImage  =  'original.jpg';
                }elseif($value['coin'] =='NEOBTC'){
                  $coinImage  =  'NEO.jpg';
                }elseif($value['coin'] =='ETCBTC'){
                  $coinImage  =  'etc.jpg';
                }elseif($value['coin'] =='XRPBTC'){
                  $coinImage  =  'ripple.png';
                }elseif($value['coin'] =='XEMBTC'){
                  $coinImage  =  'nem.png';
                }elseif($value['coin'] =='XLMBTC'){
                  $coinImage  =  'xlm.png';
                }elseif($value['coin'] =='QTUMBTC'){
                  $coinImage  =  'QTUMBTC.jpg';
                }elseif($value['coin'] =='ZENBTC'){
                  $coinImage  =  'ZENBTC.png';
                }elseif($value['coin'] == 'NEOUSDT'){
                  $coinImage = 'neousdt.png';
                }elseif($value['coin'] == 'BTCUSDT'){
                  $coinImage = 'btc.png';
                }elseif($value['coin'] == 'XRPUSDT'){
                  $coinImage  =  'ripple.png';
                }elseif($value['coin'] == 'QTUMUSDT'){      
                  $coinImage  =  'QTUMBTC.jpg';
                }elseif($value['coin'] == 'ADABTC'){              
                  $coinImage = 'adabtc.png';
                }elseif($value['coin'] == 'LINKBTC'){              
                  $coinImage = 'linkbtc.png';
                }elseif($value['coin'] == 'XMRBTC'){              
                  $coinImage = 'xmrbtc.png';
                }elseif($value['coin'] == 'DASHBTC'){              
                  $coinImage = 'dashbtc.jpg';
                }elseif($value['coin'] == 'LTCUSDT'){                 
                  $coinImage = 'ltcusdt.png';
                }elseif($value['coin'] == 'EOSUSDT'){                 
                  $coinImage = 'EOS.jpg';
                }
                $active_parent_total    += $value['parents_picked'];
                $execuated_parent_total += $value['parents_executed'];
                $canceled_total         += $value['cancelled'];
                $ignore_total           += $value['parents_ignored'];
                $new_error_total        += $value['new_error'] ;
                $other_status_total     += $value['other_status'] ;

                // if(in_array($value['coin'], $coin_arrayBtc)){

                //   $btcBuyCount +=  $value['sold'] + $value['open_lth']  + $value['other_status']; 
                // }elseif(in_array($value['coin'] ,$coin_arrayUSDT)){

                //   $usdtBuyCount +=  $value['sold'] + $value['open_lth']  + $value['other_status']; 
                // }

                $total_sold           += $value['sold'];
                $total_open_lth       += $value['open_lth'];
                $totalInvertInDollars += $value['usdt_invest_amount'];

                $compeletion = 0;
                $total       = 0;
                $others      = 0;
                
                $total       = $value['new_error'] +  $value['sold'] + $value['open_lth'] + $value['cancelled'] + $value['other_status'];
                $others      = $value['parents_executed'] - $total;
                
                if( $others > 0){
                  $disappear_total += $others;
                }
                $completion_total = $value['open_lth']+ $value['other_status'] + $value['sold'];
                $time = $value['created_date']->toDateTime()->format("Y-m-d H:i:s");
                if( isset($value['modified_date'])){
                $last_modified_time = $value['modified_date']->toDateTime()->format("Y-m-d H:i:s");
                }
                $time_zone = date_default_timezone_get();

                if($value['sold'] == 0){
                  $compeletion = 0;
                }elseif( ($value['open_lth']+$value['other_status']+$value['costAvgCount_child']) == 0){
                  $compeletion = 100;
                }
                else{
                  $compeletion = ($value['sold'] / $completion_total )*100;
                }
                ?> 
                <?php
                $orderCreatedDate = $value['created_date']->toDateTime()->format("d 07:59");
                ?>
                  <tr style="text-align:center;"> 
                    <td>
                      <img class="img img-circle" src="https://app.digiebot.com/assets/coin_logo/thumbs/<?php echo get_coin_icon_by_symbol($value['coin']); ?>" data-toggle="tooltip" data-placement="top" alt="<?php echo $value['coin'];?>" title="<?php echo $value['coin']; ?>">
                       <br><br>
                       <?php echo $value['coin']; ?>
                    </td>
                    <td><span class="label label-warning" title="Oppertunity Id = <?php echo $value['opportunity_id']; ?>"><?php echo $value['level']; ?></span></td>
                    <td><?php
                      $this->load->helper('common_helper');
                      $time_ago = time_elapsed_string($time , $time_zone);?>
                      <span class="label label-info" title="<?php echo $time; ?>"> <?php echo $time_ago; ?></span>
                      <br>
                      <?php if(isset($value['is_manual']) && $value['is_manual'] == 'yes'): ?> 
                        <span class="label label-info"> <?php echo "Manul"; ?></span>
                      <?php endif; ?> 
                    </td>
                    
                    <?php if(number_format($compeletion, 2) == 0): ?>
                      <?php if(isset($value['is_modified']) || (isset($value['oppertunity_missed']) && (int)$value['costAvgCount_child'] < 1)):
                        $total_oops_missed++; ?>
                          <td>
                            <span title = "purchase price = <?php echo number_format($value['purchase_price'], 10); ?>"> 
                              <div class="progress-bar progress-bar-striped active progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:100%;border-radius:3px;color:black">
                                oops Missed
                              </div>
                              <br><br>
                              <?php if($value['costAvgCount_child'] > 0){?>
                                <span class="label label-info">Total Child :<?php echo $value['costAvgCount_child'];?></span>  
                              <?php } ?>
                              <?php if($value['costAvgCount_child_canceled'] > 0){?>
                                <span class="label label-info">Cancelled Child :<?php echo $value['costAvgCount_child_canceled'];?></span>  
                              <?php } ?>
                              
                              <?php if($value['costAvgCount'] > 0){?>
                                <span class="label label-info">Cost Avg <?php echo $value['costAvgCount'];?></span>  
                              <?php } ?>
                            </span>
                          </td>
                      <?php endif; ?>
                      <?php if(!isset($value['oppertunity_missed']) || (int)$value['costAvgCount_child'] > 0):?>
                          <td>
                              <span title = "purchase price = <?php echo number_format($value['purchase_price'], 10); ?>"> 
                                <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:100%;border-radius:3px;color:black">
                                <?php echo number_format($compeletion, 2)."%";?>
                              </div>
                            <br><br>
                            <?php if($value['costAvgCount_child'] > 0){?>
                              <span class="label label-info">Total Child :<?php echo $value['costAvgCount_child'];?></span>  
                            <?php } ?>
                            <?php if($value['costAvgCount_child_canceled'] > 0){?>
                                <span class="label label-info">Cancelled Child :<?php echo $value['costAvgCount_child_canceled'];?></span>  
                              <?php } ?>
                            <?php if($value['costAvgCount'] > 0){?>
                              <span class="label label-info">Cost Avg <?php echo $value['costAvgCount'];?></span>  
                              <?php } ?>
                            </span>
                          </td>
                        <?php endif; ?>
                    <?php endif; ?> 
                    
                    <?php if(number_format($compeletion, 2) != 0): ?>  
                      <td> 
                        <span title ="First order purchase price = <?php echo number_format($value['purchase_price'], 10); ?>">
                          <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: <?php echo $compeletion;?>%;border-radius:3px;color:black">
                            <?php echo number_format($compeletion, 2)."%";?>
                          </div>
                          <br><br>
                          <?php if($value['costAvgCount_child'] > 0){?>
                            <span class="label label-info">Total Child :<?php echo $value['costAvgCount_child'];?></span>
                          <?php } ?>
                        </span>
                      </td>
                    <?php endif; ?>
                    <td><?php if(isset($value['sold'])){echo $value['sold'];}?><br><br>
                          <?php if($value['costAvgCount_child_sold'] > 0){?>
                            <span class="label label-info">Child sold :<?php echo $value['costAvgCount_child_sold'];?></span>
                          <?php } ?></td>
                    <td><?php if(isset($value['open_lth'])){echo $value['open_lth'];} ?><br><br>
                          <?php if($value['costAvgCount_child_buy'] > 0){?>
                            <span class="label label-info">Child buy :<?php echo $value['costAvgCount_child_buy'];?></span>
                          <?php } ?></td>
                            <!-- Average sold td Start-->

                    <td>
                      <?php  if($value['avg_sold'] =="" || $value['avg_sold'] =="undefined" || $value['avg_sold'] =="NAN" || $value['avg_sold'] == "nan" || $value['avg_sold'] == "null" || $value['avg_sold'] =="NULL" ){
                        $avg_sold_orders = 0;
                        }else{
                          $avg_sold_orders = number_format($value['avg_sold'], 2);
                        } 
                        
                        if( $avg_sold_orders == 0 && !isset($value['oppertunity_missed'])){
                          $avg_sold_count++;
                        }
                        ?>
                      <?php if($value['avg_sold'] >=1.1){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $value['avg_sold']/4;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">
                          <strong><?php echo number_format($value['avg_sold'], 2);?>%</strong>
                        </div> <?php } elseif($value['avg_sold'] >= 0 && $value['avg_sold']< 1.1 ){ ?>
                          <div class="circle" id="circle-a" data-value="<?php echo $value['avg_sold']/4;?>" data-fill="{
                            &quot;color&quot;: &quot;rgba(255, 195, 0) &quot;}">      
                            <strong><?php echo number_format($value['avg_sold'], 2);?>%</strong>
                          </div> <?php } elseif($value['avg_sold'] < 0){ ?>
                          <div class="circle" id="circle-a" data-value="<?php echo $value['avg_sold']/-4;?>" data-fill="{
                            &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                            <strong><?php echo number_format($value['avg_sold'], 2);?>%</strong>
                          </div><?php } ?>
                          <!-- <br> -->
                          <?php 
                          // echo number_format($value['per_trade_sold'], 2);
                          $total_sold_avg += $avg_sold_orders;
                          $perTradeAvgSoldSum += $value['avg_sold'] * $value['sold'];
                          ?>
                    </td> 
                    <!-- average sold Td end -->  
        
                    <td>
                      <?php   if($value['avg_open_lth']=="" || $value['avg_open_lth'] =="undefined" || $value['avg_open_lth'] =="NAN" || $value['avg_open_lth'] == "nan" || $value['avg_open_lth'] == "null" || $value['avg_open_lth'] =="NULL" ){
                                $avg_open_lth_value = 0;
                              }else{
                          $avg_open_lth_value   = number_format($value['avg_open_lth'], 2);
                        } 
                        
                        if( $avg_open_lth_value == 0 && !isset($value['oppertunity_missed'])){
                          $avg_open_lth_count++;
                        }
                        ?>
                      <?php if($avg_open_lth_value >=0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $value['avg_open_lth']/4;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">
                          <strong><?php echo number_format($value['avg_open_lth'], 2);?>%</strong>
                      </div>
                      <?php } elseif($avg_open_lth_value < 0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $value['avg_open_lth']/-4;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                          <strong><?php echo number_format($value['avg_open_lth'], 2);?>%</strong>
                        </div><?php } ?>
                        <?php $total_open_avg += $avg_open_lth_value;?>
                    </td>

                    <td><span title="pause parent = <?php echo $value['pause_parents_count'];?> taking orders parent = <?php echo $value['parents_taking_order_count']; ?>"><?php echo $value['parents_picked'];?></span></td>
                    <td><?php echo $value['cost_avg_active_parents']; ?></td>
                    <td><?php echo $value['parents_executed']; ?></td>

                    <?php
                      $hours = 0  ;
                      if(isset($value['sell_signal_time']) && isset($value['sendHitTime'])){
                        $hitSendTime    = $value['sendHitTime']->toDateTime()->format('Y-m-d H:i:s') ;  
                        $sellSignalTime = $value['sell_signal_time']->toDateTime()->format('Y-m-d H:i:s') ; 

                        $hours=(strtotime($sellSignalTime)-strtotime($hitSendTime))/3600;
                      } 
                    ?>
                    <!-- sell signal percetage show -->
                    <td title= "<?php echo "Hit send Price:". $value['current_price']. "\r\n Sell Signal Price: ".$value['sell_signal_price']."\r\n Hit Send Time: ".$hitSendTime."\r\n Sell Signal Recivied Time: ".$sellSignalTime."\r\n Difference in H: ".$hours. "\r\n Order Level: ".$value['orderLevel']."\r\nType: ".$value['type']."\r\nRecomended Price: ".$value['recomended_price']; ?>">
                      <?php 
                        if(isset($value['sell_signal_price']) && isset($value['current_price']) ){
                          $percentage  = ((($value['sell_signal_price'] - $value['current_price']) *100) / $value['current_price']);

                          if($percentage >=1.1){ ?>
                          <div class="circle" id="circle-a" data-value="<?php echo $percentage/4;?>" data-fill="{
                            &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">
                            <strong><?php echo number_format($percentage, 2);?>%</strong>
                          </div> <?php } elseif($percentage >= 0 && $percentage< 1.1 ){ ?>
                          <div class="circle" id="circle-a" data-value="<?php echo $percentage/4;?>" data-fill="{
                            &quot;color&quot;: &quot;rgba(255, 195, 0) &quot;}">      
                            <strong><?php echo number_format($percentage, 2);?>%</strong>
                          </div> <?php } elseif($percentage < 0){ ?>
                          <div class="circle" id="circle-a" data-value="<?php echo $percentage/-4;?>" data-fill="{
                            &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                            <strong><?php echo number_format($percentage, 2);?>%</strong>
                          </div><?php } 
                        }else{
                          echo "---";
                        }
                      ?>
                    </td>
                    <!-- end sell signal percetage show -->

                    <td><?php echo $value['cancelled']; ?></td>
                    <td><?php echo $value['parents_ignored']; ?></td>
                    <td><?php echo $value['range_ignored_parent_count']; ?></td>
                    <td><?php echo $value['new_error']; ?></td>
                    <td><?php echo $value['other_status'];?></td>
                    <td><?php 
                      if($others < 0 ){
                        $others = 0;
                      }
                      echo $others; ?>
                    </td>
                    <td>
                      <span class="label label-info" title="Min market Sold Price"> <?php echo "Min: ".$value['minOrderSoldPrice']; ?></span>
                      <br>
                      <span class="label label-info" title="Max market Sold Price"> <?php echo "Max: ".$value['maxOrderSoldPrice']; ?></span> 
                    </td>
                        <td><span class="label label-info"><?php echo "$ ".number_format($value['usdt_invest_amount'],9);?></span></td>
                        <?php 
                              //  5 hours min calculation
                          $min_cal_1 = 0;
                          $min_cal_2 = 0;
                          $per_5_hour_min = 0;
                          $max_cal_1  = 0;
                          $max_cal_2 = 0;
                          $per_5_hour_max = 0;
                          if(isset($value['5_min_value']) && isset($value['5_max_value']) && $value['5_max_value']>0 ){
                            $per_5_hour_min = 0;
                            $min_cal_1 = (float)$value['5_min_value'] - $value['purchase_price'];
                            $min_cal_2 = ($value['5_min_value'] + $value['purchase_price']) / 2;
                            $per_5_hour_min =  ($min_cal_1/ $min_cal_2)*100;
                            $total_min5_hour += $per_5_hour_min;
                            //  5 hours max calculation
                            $max_cal_1 = (float)$value['5_max_value'] - $value['purchase_price'];
                            $max_cal_2 = ($value['5_max_value'] + $value['purchase_price']) / 2;
                            $per_5_hour_max =  ($max_cal_1/ $max_cal_2)*100;
                            $total_max5_hour += $per_5_hour_max;
                          }
                          $min_cal_10 = 0 ;
                          $min_cal_10_2 = 0;
                          $per_10_hour_min = 0;
                          $max_cal_10 = 0;
                          $max_cal_10_2 = 0;
                          $per_10_hour_max = 0;
                          if(isset($value['10_min_value']) && isset($value['10_max_value']) && $value['10_min_value']>0){
                            // 10 hours min calculation
                            $min_cal_10 = (float)$value['10_min_value'] - $value['purchase_price'];
                            $min_cal_10_2 = ($value['10_min_value'] + $value['purchase_price']) / 2;
                            $per_10_hour_min =  ($min_cal_10/ $min_cal_10_2)*100;
                            $total_min10_hour += $per_10_hour_min;
                              // 10 hours max calculation
                            $max_cal_10 = (float)$value['10_max_value'] - $value['purchase_price'];
                            $max_cal_10_2 = ($value['10_max_value'] + $value['purchase_price']) / 2;
                            $per_10_hour_max =  ($max_cal_10/ $max_cal_10_2)*100;
                            $total_max10_hour += $per_10_hour_max;
                          }
                        ?>  
                    <!-- start five hour min -->
                    <td>
                      <?php    $five_hour_min   = number_format($per_5_hour_min, 2); ?>
                      <?php if($five_hour_min >=0){ ?>
                      <div class="circle" id="circle-a" data-value="<?php echo $five_hour_min/5;?>" data-fill="{
                        &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">
                        <strong><span title="<?php echo "price =".$value['5_min_value']; ?>"><?php echo number_format($five_hour_min, 2);?>%</span></strong>
                      </div> <?php } elseif($five_hour_min < 0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $five_hour_min/-5;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                          <strong><span title="<?php echo "price =".$value['5_min_value']; ?>"><?php echo number_format($five_hour_min, 2);?>%</span></strong>
                        </div><?php } ?>
                    </td>

                      <td>
                        <?php  $five_hour_max   = number_format($per_5_hour_max, 2); ?>
                        <?php if($five_hour_max >=0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $five_hour_max/5;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">
                          <strong><span title="<?php echo "price =".$value['5_max_value']; ?>"><?php echo number_format($five_hour_max, 2);?>%</span></strong>
                        </div> <?php } elseif($five_hour_max < 0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $five_hour_max/-5;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                          <strong><span title="<?php echo "price =".$value['5_max_value']; ?>"><?php echo number_format($five_hour_max, 2);?>%</span></strong>
                        </div><?php } ?>
                      </td>
                          <!-- End five hours max profit -->   
                    <td>
                      <?php  $ten_hour_min   = number_format($per_10_hour_min, 2); ?>
                      <?php if($ten_hour_min >=0){ ?>
                      <div class="circle" id="circle-a" data-value="<?php echo $ten_hour_min/5;?>" data-fill="{
                        &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">
                        <strong><span title="<?php echo "price =".$value['10_min_value']; ?>"><?php echo number_format($ten_hour_min, 2);?>%</span></strong>
                      </div> <?php } elseif($ten_hour_min < 0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $ten_hour_min/-5;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                          <strong><span title="<?php echo "price =".$value['10_min_value']; ?>"><?php echo number_format($ten_hour_min, 2);?>%</span></strong>
                        </div><?php } ?>
                    </td>
                              <!-- end 10 hours min profit  -->
                    <td>
                      <?php   $ten_hour_max   = number_format($per_10_hour_max, 2); ?>
                      <?php if($ten_hour_max >=0){ ?>
                      <div class="circle" id="circle-a" data-value="<?php echo $ten_hour_max/5;?>" data-fill="{
                        &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">
                        <strong><span title="<?php echo "price =".$value['10_max_value']; ?>"><?php echo number_format($ten_hour_max, 2);?>%</span></strong>
                      </div> <?php } elseif($ten_hour_max < 0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $ten_hour_max/-5;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                          <strong><span title="<?php echo "price =".$value['10_max_value']; ?>"><?php echo number_format($ten_hour_max, 2);?>%</span></strong>
                        </div><?php } ?>
                    </td>
                      <td>
                        <?php 
                        $first_order = 0;
                        $last_order = 0;
                          if(isset($value['first_order_buy']) && isset($value['last_order_buy'])){
                            $first_order = $value['first_order_buy']->toDateTime()->format("Y-m-d H:i:s");
                            $last_order = $value['last_order_buy']->toDateTime()->format("Y-m-d H:i:s");
                            }?>
                          <span class="label label-info" title="last order Created Time"> <?php echo $first_order; ?></span>
                          <br>
                          <span class="label label-info" title="first Order Created Time"> <?php echo $last_order; ?></span>   
                      </td>
                      <td><?php
                        $this->load->helper('common_helper');
                        $last_time_ago = time_elapsed_string($last_modified_time , $time_zone);?>
                        <span class="label label-info" title="<?php echo $last_modified_time; ?>"> <?php echo $last_time_ago; ?></span>
                      </td>  
                    <td><span title="Current Price Was = <?php echo $value['current_price']." and Limit: ".$value['trade_limit']."\r\n T.P: ".$value['traget_profit']."\r\n First SL Update: ".$value['first_stop_loss_update'];?>"><?php echo $value['parent_active_count'];?></span></td> 
                    <td><?php echo $value['pickParentYes'];?></td>
                    <td>     
                      <?php 
                        if(isset($value['sendHitTime']) && !empty($value['sendHitTime']) ){   
                          $sendHitTime  		          = strtotime($value['sendHitTime']->toDateTime()->format('Y-m-d H:i:s'));
                          $opportunituCreatedTime  		= strtotime($value['created_date']->toDateTime()->format('Y-m-d H:i:s')); 
                          $differenceBuyInSec = ($opportunituCreatedTime - $sendHitTime);
                        }else{
                          $differenceBuyInSec = 0;
                        } ?>
                      <span title="Hit Send Time: <?php echo $sendHitTime ;?>"><?php echo $differenceBuyInSec.' Sec';?> </span>
                    </td>

                    <td><span title="order report"><a href="https://app.digiebot.com/admin/order_report/opportunity_order_report/<?php echo $value['opportunity_id']."/".$filter_user_data['exchange']."/".$filter_user_data['filter_by_mode'];?>" target="_blank"><i class="glyphicon glyphicon-send"></i></a></span>
                      <br> 
                      <?php $created_time = $value['created_date']->toDateTime()->format("Y-m-d H:00:00"); ?>
                      <span title="chart"><a href="https://candles.digiebot.com/mainchart2/<?php echo $value['coin'].'?date='.$created_time."&tradetime=".$created_time."&tradeprice=".$value['current_price'];?>" target="_blank"><i class="fa fa-bar-chart" style="color:red"></i></a></span>              
                      <?php
                        $in_arr = ['XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC'];
                        if(in_array($value['coin'], $in_arr)){
                          $first=   substr($value['coin'], 0, -3); 
                          $second=   substr($value['coin'], -3 );
                          $coin =  $first."_".$second; 
                        }else{
                          $first=   substr($value['coin'], 0, -4); 
                          $second=   substr($value['coin'], -4 );
                          $coin = $first."_".$second; 
                        }
                      ?>
                      <br>
                      <span title="Binance Chart"><a href="https://www.binance.com/en/trade/<?php echo $coin;?>" target="_blank"><i class="fa fa-line-chart" style="color:#F76A03 "></i></a></span>
                      <br>
                      <?php if($filter_user_data['exchange'] == 'kraken'){
                        $ignore_id = [];
                        foreach($value['LimitIgnoredParentsIDsArr'] as $id){
                          $ignore_id['LimitIgnoredParentsIDsArr'][] = (string)$id;
                        }
                      ?>
                      <input type="hidden" name="hiddenvals" value='<?php echo json_encode((array)$ignore_id['LimitIgnoredParentsIDsArr']);?>'id="ignore"/>
                      <?php } else{?>
                        <input type="hidden" name="hiddenvals" value='<?php echo json_encode((array)$value['LimitIgnoredParentsIDsArr']);?>'id="ignore"/>
                      <?php } ?> 
                      <button type="button" class="click" data-toggle="modal" data-target="#myModal"><i class="fa fa-eye" aria-hidden="true"></i></button>

                      <br><br>   
                      <?php 
                        $mode =   ($filter_user_data['filter_by_mode'] == 'live') ?'':'test_';
                        $exchange = 'insert_latest_oppertunity_into_log_collection_'.$mode.$filter_user_data['exchange'];
                      ?>
                      <span title="Run Cron"><a href="https://admin.digiebot.com/admin/Trigger_rule_reports/<?php echo $exchange."?opportunityId=".$value['opportunity_id'];?>" target="_blank"><i class="fa fa-refresh"></i></a></span>
                    </td>
                  </tr>
              
              <?php } 
              
                ?>
               <tr>
                  <td style="background-color:grey; padding-top:inherit"colspan="26">

                    <?php

                      $buycountTotal        = ($tradeCountDaily[$count][0]['sold_orders']+ $tradeCountDaily[$count][0]['open_lth_orders'] + $tradeCountDaily[$count][0]['otherStatus']);
                      $buycountTotalUSDT    = ($tradeCountDailyUSDT[$count][0]['sold_orders']+ $tradeCountDailyUSDT[$count][0]['open_lth_orders'] + $tradeCountDailyUSDT[$count][0]['otherStatus']);

                      $expectedBtcBuyCount    = $expectedTrades[$count][0]['total_btc_count'];
                      $expectedUSDTBuyCount   = $expectedTrades[$count][0]['total_usdt_count'];
                      
                      //btc trade count
                      if($buycountTotal == 0){
                        $compeletion_barBTC = 0;

                      }elseif( $buycountTotal  ==  $expectedBtcBuyCount){
                        $compeletion_barBTC = 100;

                      }else{

                        $compeletion_barBTC  = ($buycountTotal / $expectedBtcBuyCount )*100;
                      }

                      //usdt trade count
                      if($buycountTotalUSDT == 0){
                        $compeletion_barUSDT = 0;

                      }elseif( $buycountTotalUSDT  ==  $expectedUSDTBuyCount){
                        $compeletion_barUSDT = 100;

                      }else{

                        $compeletion_barUSDT  = ($buycountTotalUSDT / $expectedUSDTBuyCount )*100;
                      }
                      echo "Expected Buy BTC Count: ".$expectedTrades[$count][0]['total_btc_count']."<br>";  
                    ?>
                    <!-- btc bar -->
                    <span title= "Successful Buy Count BTC:<?php echo $buycountTotal; ?>">
                      <?php  if($compeletion_barBTC >= 0 && $compeletion_barBTC <= 40 ){?>
                        <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:10%;border-radius:3px;color:black">
                          <?php echo number_format($compeletion_barBTC, 2)."%";?>
                        </div>
                      <?php } elseif($compeletion_barBTC > 40 && $compeletion_barBTC <= 80){?>  
                        <div class="progress-bar progress-bar-striped active progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:10%;border-radius:3px;color:black">
                          <?php echo number_format($compeletion_barBTC, 2)."%";?>
                        </div>
                      <?php } elseif($compeletion_barBTC > 80 && $compeletion_barBTC <= 90){?>  
                        <div class="progress-bar progress-bar-striped active progress-bar-primary" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:10%;border-radius:3px;color:black">
                          <?php echo number_format($compeletion_barBTC, 2)."%";?>
                        </div>
                      <?php } elseif($compeletion_barBTC > 90){?>  
                        <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:10%;border-radius:3px;color:black">
                          <?php echo number_format($compeletion_barBTC, 2)."%";?>
                        </div> 
                      <?php } ?>
                    </span> 


                    <!-- usdt Bar -->
                    <span title= "Successful Buy Count USDT:<?php echo $buycountTotalUSDT;?>">
                      <?php
                        echo "<br>Expected Buy USDT Count: ".$expectedTrades[$count][0]['total_usdt_count']."<br>";
                        if($compeletion_barUSDT >= 0 && $compeletion_barUSDT <= 40 ){?>
                        <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:10%;border-radius:3px;color:black">
                          <?php echo number_format($compeletion_barUSDT, 2)."%";?>
                        </div>
                      <?php } elseif($compeletion_barUSDT > 40 && $compeletion_barUSDT <= 80){?>  
                        <div class="progress-bar progress-bar-striped active progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:10%;border-radius:3px;color:black">
                          <?php echo number_format($compeletion_barUSDT, 2)."%";?>
                        </div>
                      <?php } elseif($compeletion_barUSDT > 80 && $compeletion_barUSDT <= 90){?>  
                        <div class="progress-bar progress-bar-striped active progress-bar-primary" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:10%;border-radius:3px;color:black">
                          <?php echo number_format($compeletion_barUSDT, 2)."%";?>
                        </div>
                      <?php } elseif($compeletion_barUSDT > 90){?>  
                        <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:10%;border-radius:3px;color:black">
                          <?php echo number_format($compeletion_barUSDT, 2)."%";?>
                        </div> 
                      <?php } 
                      // echo "<br>Active users: ".$expectedTrades[$count][0]['activeUserCount']; 
                      $count++; ?>
                    </span>
                  </td>
                </tr>
            <?php 
          } 
          ?>  
        </table>
        </div>
        </div>
      
              <!-- START MODEL -->
          <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Ignore Prent Id</h4>
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

        <div class="widget widget-inverse">
              <div class="widget-body padding-bottom-none">
                <table class="table">
                  <tr style="text-align:center;">
                    <td class="float: left"><?php echo "Total Count: ".$countOpportunity;?>
                      <br>
                      <?php echo "Oops Missed: ".$total_oops_missed; ?>
                      <br>
                      <?php echo "Other: ".($countOpportunity - $total_oops_missed);?>
                    </td>
                  <?php
                    $completion_total_new = $total_open_lth + $other_status_total + $total_sold;
                    if($total_sold == 0){
                      $compeletion_bar = 0;
                    }elseif( ($total_open_lth + $other_status_total) == 0){
                      $compeletion_bar = 100;
                    }else{
                      $compeletion_bar = ($total_sold / $completion_total_new )*100;
                    }?>
                    <td colspan="2">
                      <span class="label label-info"><?php echo "Sold:".$total_sold;?></span>
                      <br><br>
                      <?php if($total_open_lth == 0 && $other_status_total == 0 &&  $total_sold == 0){?>
                        <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:100%;border-radius:3px;color:black">
                          oops Missed
                        </div>
                      <?php } elseif($compeletion_bar > 0 && $compeletion_bar <= 70 ){?>
                        <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:100%;border-radius:3px;color:black">
                          <?php echo number_format($compeletion_bar, 2)."%";?>
                        </div>
                      <?php } elseif($compeletion_bar > 70 && $compeletion_bar <= 85){?>  
                        <div class="progress-bar progress-bar-striped active progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:100%;border-radius:3px;color:black">
                          <?php echo number_format($compeletion_bar, 2)."%";?>
                        </div>
                      <?php } elseif($compeletion_bar > 85 && $compeletion_bar <= 90){?>  
                        <div class="progress-bar progress-bar-striped active progress-bar-primary" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:100%;border-radius:3px;color:black">
                          <?php echo number_format($compeletion_bar, 2)."%";?>
                        </div>
                      <?php } elseif($compeletion_bar > 90){?>  
                        <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:100%;border-radius:3px;color:black">
                          <?php echo number_format($compeletion_bar, 2)."%";?>
                        </div> 
                      <?php } ?> 
                    </td>
                    <td style="vertical-align: middle;"><span class="label label-info"><?php echo "Open/LTH:".$total_open_lth;?></span></td>
                    <td style="vertical-align: middle;"><span class="label label-danger"><?php echo "Error/Cancelled/other:".$other_status_total;?></span></td>
                  
                    <td>
                      <?php    
                        $avg = number_format($total_sold_avg/ ($countOpportunity - $total_oops_missed - $avg_sold_count ), 2);    
                      if($avg >= 0 ){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $avg/4;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">      
                          <strong><?php echo number_format($avg, 2);?>%</strong>
                        </div> <?php } elseif($avg < 0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $avg/-4;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                          <strong><?php echo number_format($avg, 2);?>%</strong>   
                        </div><?php } ?>
                        <br>
                    </td>
                     <!-- // per Trade  -->
                    <td>
                      <?php $perTrade = number_format(($perTradeAvgSoldSum / $total_sold), 2); 
                      if($perTrade >= 0 ){ ?>
                      <span title="Per Trade" >
                        <div class="circle" id="circle-a" data-value="<?php echo $perTrade/4;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">      
                          <strong><?php echo number_format($perTrade, 2);?>%</strong>
                        </div> 
                      </span>
                      <?php } elseif($perTrade < 0){ ?>
                        <span title="Per Trade" >
                          <div class="circle" id="circle-a" data-value="<?php echo $perTrade/-4;?>" data-fill="{
                            &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                            <strong><?php echo number_format($perTrade, 2);?>%</strong>   
                          </div>
                        </span>
                      <?php } ?> </span>
                    </td> 
                    <td>                
                      <?php 
                      $avg = number_format($total_open_avg/($countOpportunity - $total_oops_missed - $avg_open_lth_count), 2);
                      if($avg >= 0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $avg/4;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">      
                          <strong><?php echo number_format($avg, 2);?>%</strong>
                        </div> <?php } elseif($avg < 0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $avg/-4;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                          <strong><?php echo number_format($avg, 2);?>%</strong>
                        </div><?php } ?>
                    </td> 
                    <td style="vertical-align: middle;"><span class="label label-info"><?php echo "parent:".$active_parent_total;?></span></td>
                    <td style="vertical-align: middle;"><span class="label label-info"><?php echo "Execuated:".$execuated_parent_total;?></span></td>
                    <td style="vertical-align: middle;"><span class="label label-info"><?php echo "Cancel:".$canceled_total;?></span></td>
                    <td style="vertical-align: middle;"><span class="label label-info"><?php echo "Ignore:".$ignore_total;?></span></td>
                    <td style="vertical-align: middle;"><span class="label label-info"><?php echo "New_Error:".$new_error_total;?></span></td>
                    <td style="vertical-align: middle;"><span class="label label-info"><?php echo "Other:".$other_status_total;?></span></td>
                    <td style="vertical-align: middle;"><span class="label label-info"><?php echo "Disappear:".$disappear_total;?></span></td>
                    <td style="vertical-align: middle;"><span class="label label-info"><?php echo "Invest in $:".$totalInvertInDollars;?></span></td>
                    <td>                
                      <?php 
                      $avg = number_format($total_min5_hour/($countOpportunity - $total_oops_missed), 2);
                      if($avg >= 0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $avg/4;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">      
                          <strong><?php echo number_format($avg, 2);?>%</strong>
                        </div> <?php } elseif($avg < 0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $avg/-4;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                          <strong><?php echo number_format($avg, 2);?>%</strong>
                        </div><?php } ?>
                    </td>
                    <td>                
                      <?php 
                      $avg = number_format($total_max5_hour/($countOpportunity - $total_oops_missed), 2);
                      if($avg >= 0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $avg/4;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">      
                          <strong><?php echo number_format($avg, 2);?>%</strong>
                      </div> <?php } elseif($avg < 0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $avg/-4;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                          <strong><?php echo number_format($avg, 2);?>%</strong>
                      </div><?php } ?>
                    </td>
                    <td>                
                      <?php 
                      $avg = number_format($total_min10_hour/($countOpportunity - $total_oops_missed), 2);
                      if($avg >= 0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $avg/4;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">      
                          <strong><?php echo number_format($avg, 2);?>%</strong>
                      </div> <?php } elseif($avg < 0){ ?>
                        <div class="circle" id="circle-a" data-value="<?php echo $avg/-4;?>" data-fill="{
                          &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                          <strong><?php echo number_format($avg, 2);?>%</strong>
                      </div><?php } ?>
                    </td>
                    <td>              
                      <?php 
                      $avg = number_format($total_max10_hour/($countOpportunity - $total_oops_missed), 2);
                      if($avg >= 0 ){ ?>
                      <div class="circle" id="circle-a" data-value="<?php echo $avg/4;?>" data-fill="{
                        &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">      
                        <strong><?php echo number_format($avg, 2);?>%</strong>
                      </div> <?php } elseif($avg < 0){ ?>
                      <div class="circle" id="circle-a" data-value="<?php echo $avg/-4;?>" data-fill="{
                        &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                        <strong><?php echo number_format($avg, 2);?>%</strong>
                      </div><?php } ?>
                    </td> 
                  </tr>  
                <?php } ?>
              </table>
            </div>
          </div>
      </div>
      </div>
      </div>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.1.3/circle-progress.min.js"></script> 
          <script>
            var progressBarOptions = {
                startAngle: -1,
                size: 60,
            };
            $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function (event, progress, value) {
            });

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


      $('.click').click(function(){
        var hiddenInput = $(this).siblings('input').val();
        var data = JSON.parse(hiddenInput);
        var content = "<table class='table table-bordered'>"
        for(i=0; i< data.length; i++){
          content += '<tr><td>' +  data[i] + '</td><td><a href="https://trading.digiebot.com/edit-parent-order/' +  data[i] + '" target="_blank"><i class="fa fa-edit"></i></a>   </td></tr>';
        }
        content += '<tr><td colspan= "2">Total Count:' +  data.length + '</td></tr>';
        content += "</table>";
        $('#append').html(content);
      });

    </script> 
    <script type="text/javascript">
      $(document).ready(function() {
        $('#filter_by_coin, #filter_by_rule_select, #filter_by_level_select').multiselect({
          includeSelectAllOption: true,
          buttonWidth: 435.7,
          // enableFiltering: true
          enableCaseInsensitiveFiltering: true,
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