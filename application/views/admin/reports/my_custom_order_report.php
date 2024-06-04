<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>

<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

<style>
.hi_error{
    background-color: #fde7e7;
    
}
.Input_text_s {

    /* display: inline; */

    position: relative;

}



.Input_text_s i {

    position: absolute;

    top: 33px;

    right: 10px;

}



.our-team {

  padding: 30px 0 40px;

  margin-bottom: 30px;

  background-color: #f7f5ec;

  text-align: center;

  overflow: hidden;

  position: relative;

}



.our-team .picture {

  display: inline-block;

  height: 230px;

  width: 230px;

  margin-bottom: 50px;

  z-index: 1;

  position: relative;

}



.our-team .picture::before {

  content: "";

  width: 100%;

  height: 0;

  border-radius: 50%;

  background-color: #1369ce;

  position: absolute;

  bottom: 135%;

  right: 0;

  left: 0;

  opacity: 0.9;

  transform: scale(3);

  transition: all 0.3s linear 0s;

}



.our-team:hover .picture::before {

  height: 100%;

}



.our-team .picture::after {

  content: "";

  width: 100%;

  height: 100%;

  border-radius: 50%;

  background-color: #1369ce;

  position: absolute;

  top: 0;

  left: 0;

  z-index: -1;

}



.our-team .picture img {

  width: 100%;

  height: auto;

  border-radius: 50%;

  transform: scale(1);

  transition: all 0.9s ease 0s;

}



.our-team:hover .picture img {

  box-shadow: 0 0 0 14px #f7f5ec;

  transform: scale(0.7);

}



.our-team .title {

  display: block;

  font-size: 15px;

  color: #4e5052;

  text-transform: capitalize;

}



.our-team .social {

  width: 100%;

  padding: 0;

  margin: 0;

  background-color: #1369ce;

  position: absolute;

  bottom: -100px;

  left: 0;

  transition: all 0.5s ease 0s;

}



.our-team:hover .social {

  bottom: 0;

}



.our-team .social li {

  display: inline-block;

}



.our-team .social li a {

  display: block;

  padding: 10px;

  font-size: 17px;

  color: white;

  transition: all 0.3s ease 0s;

  text-decoration: none;

}



.our-team .social li a:hover {

  color: #1369ce;

  background-color: #f7f5ec;

}



/*** custom checkboxes ***/

@import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);

input[type=checkbox] { display:none; } /* to hide the checkbox itself */

input[type=checkbox] + label:before {

  font-family: FontAwesome;

  display: inline-block;

}

.custom_label {

    font-size: 25px;

    width: 100%;

    text-align: center;

}

input[type=checkbox] + label:before { content: "\f096"; } /* unchecked icon */

input[type=checkbox] + label:before { letter-spacing: 10px; } /* space between checkbox and label */



input[type=checkbox]:checked + label:before { content: "\f046"; } /* checked icon */

input[type=checkbox]:checked + label:before { letter-spacing: 5px; } /* allow space for check mark */









/*Custom Switcher by Afzal*/



.af-form-group-created {

    display: none;

}

.af-form-group-created.active {

    display: block;

}

.af-switcher {

	background-color: #0f1c42;

	box-shadow: #0f1c42 0px 0px 0px 11px inset;

	transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s, background-color 1.2s ease 0s;

    border-radius: 20px;

    cursor: pointer;

    display: inline-block;

    height: 25px;

    position: relative;

    vertical-align: middle;

    width: 60px;

    box-sizing: content-box;

    background-clip: content-box;

	bottom: 4px;

    margin-top: 5px;

    border: 1px solid #EEEEEE;

}



.af-switcher > small {

    border-radius: 50px;

    box-shadow: 0 1px 3px rgba(0,0,0,0.4);

    height: 15px;

    position: absolute;

    top: 5px;

    width: 25px;

	left:30px;

	background-color: rgb(255, 255, 255);

	transition: background-color 0.4s ease 0s, left 0.2s ease 0s;

}

.af-switcher.af-switcher-small.af-switcher-default.active small {

    left: 4px;

}

.af-cust-radio {

  position: absolute;

  visibility: hidden;

  display: none;

}



.af-custom-radio-label{

  color: #0f1c42;

  display: inline-block;

  cursor: pointer;

  font-weight: bold;

  padding: 5px 20px;

  margin: 0;

  width:50%;

  float:left;

}



input[type=radio].af-cust-radio:checked + .af-custom-radio-label{

  color: #fff;

  background: #0f1c42;

}



.af-custom-radio-label + input[type=radio].af-cust-radio + .af-custom-radio-label {

  border-left:3px solid #0f1c42;

}

.af-radio-group {

  border:3px solid #0f1c42;

  display: block;

  margin: 5px 0 10px;

  border-radius: 10px;

  overflow: hidden;

  width: 290px;

}

.widget.widget-inverse {

    width: 100%;

    float: left;

    overflow-x: auto;

}

.af-table-body td:last-child {

    min-width: 250px !important;

}

.af-table-body td:nth-child(3) {

    min-width: 140px;

}

.af-table-body td:nth-child(13) {

    min-width: 200px;

}

.af-table-body td:nth-child(15) {

    min-width: 200px;

}

.af-table-body td:nth-child(14) {

    min-width: 200px;

}

.af-table-body {

    text-align: center;

}

/*---End-----*/

</style>

<div id="content">

  <h1 class="content-heading bg-white border-bottom">Reports</h1>



  <div class="innerAll bg-white border-bottom">

  <ul class="menubar">

    <li class=""><a href="<?php echo SURL; ?>admin/reports">Reports</a></li>

    <li class="active"><a href="#">Custom Report</a></li>

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

            <form method="POST" action="<?php echo SURL; ?>admin/reports/order_reports">

              <div class="row">

               <div class="col-xs-12 col-sm-12 col-md-12" style="padding-bottom: 6px;">

                <div class="alert alert-info"><?php echo "Server Time: " . date("d-m-y H:i:s a"); ?></label>

                </div>

               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">

                  <div class="Input_text_s">

                     <label>Filter Coin: </label>

                     <select id="filter_by_coin" name="filter_by_coin" type="text" class="form-control filter_by_name_margin_bottom_sm">

                        <option value ="" <?=(($filter_user_data['filter_by_coin'] == "") ? "selected" : "")?>>Search By Coin Symbol</option>

                        <?php

for ($i = 0; $i < count($coins); $i++) {

    $selected = ($coins[$i]['symbol'] == $filter_user_data['filter_by_coin']) ? "selected" : "";

    echo "<option value='" . $coins[$i]['symbol'] . "' $selected>" . $coins[$i]['symbol'] . "</option>";

}

?>

                     </select>

                  </div>

               </div>

               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">

                  <div class="Input_text_s">

                     <label>Filter Mode: </label>

                     <select id="filter_by_mode" name="filter_by_mode" type="text" class="form-control filter_by_name_margin_bottom_sm">

                        <option value="">Search By Mode</option>

                        <option value="live"<?=(($filter_user_data['filter_by_mode'] == "live") ? "selected" : "")?>>Live</option>

                        <option value="test"<?=(($filter_user_data['filter_by_mode'] == "test") ? "selected" : "")?>>Test</option>

                     </select>

                  </div>

               </div>

               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">

                  <div class="Input_text_s">

                     <label>From Date Range: </label>

                     <input id="filter_by_start_date" name="filter_by_start_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_start_date']) ? $filter_user_data['filter_by_start_date'] : "")?>">

                     <i class="glyphicon glyphicon-calendar"></i>

                  </div>

               </div>

               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">

                  <div class="Input_text_s">

                     <label>To Date Range: </label>

                     <input id="filter_by_end_date" name="filter_by_end_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_end_date']) ? $filter_user_data['filter_by_end_date'] : "")?>">

                     <i class="glyphicon glyphicon-calendar"></i>

                  </div>

               </div>

               <script type="text/javascript">

                   $(function () {

                       $('.datetime_picker').datetimepicker();

                   });

               </script>

               <style>

                  .Input_text_btn {padding: 25px 0 0;}

               </style>

               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">

                  <div class="Input_text_s">

                     <label>Filter Trigger: </label>

                     <select id="filter_by_trigger" name="filter_by_trigger" type="text" class="form-control filter_by_name_margin_bottom_sm">

                       <option value="">Search By Trigger</option>

                       <option value="trigger_1" <?php if ($filter_user_data['filter_by_trigger'] == 'trigger_1') {?> selected <?php }?>>Trigger 1</option>

                       <option value="trigger_2" <?php if ($filter_user_data['filter_by_trigger'] == 'trigger_2') {?> selected <?php }?>>Trigger 2</option>

                       <option value="box_trigger_3" <?php if ($filter_user_data['filter_by_trigger'] == 'box_trigger_3') {?> selected <?php }?>>Box Trigger 3</option>

                       <option value="barrier_trigger" <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_trigger') {?> selected <?php }?>>BARRIER TRIGGER</option>

                       <option value="rg_15" <?php if ($filter_user_data['filter_by_trigger'] == 'rg_15') {?> selected <?php }?>>RG 15</option>

                        <option value="barrier_percentile_trigger" <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_percentile_trigger') {?> selected <?php }?>>Barrier Percentile Trigger</option>

                       <option value="no" <?php if ($filter_user_data['filter_by_trigger'] == 'no') {?> selected <?php }?>>Manual Order</option>

                     </select>

                  </div>

               </div>

               <!-- Hidden Searches -->

               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px; display: none;" id="barrier_t">

                  <div class="Input_text_s">

                     <label>Filter Rule: </label>

                     <select id="filter_by_rule" name="filter_by_rule" type="text" class="form-control filter_by_name_margin_bottom_sm">

                        <option value="">Filter By Rule</option>

                        <?php for ($i = 1; $i <= 10; $i++) {?>

                            <option value="<?=$i;?>" <?php if ($filter_user_data['filter_by_rule'] == $i) {?> selected <?php }?>>Rule No <?=$i;?></option>

                          <?php }?>

                     </select>

                  </div>

               </div>

               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px; display: none;" id="barrier_p_t">

                  <div class="Input_text_s">

                     <label>Filter Level: </label>

                     <select name="filter_level" class="form-control">

                       <?php $filter_level = $filter_user_data['filter_level'];?>

                       <option value="">Search By Level</option>

                       <option value="level_1" <?php if ($filter_level == 'level_1') {?> selected <?php }?>>level_1</option>

                       <option value="level_2" <?php if ($filter_level == 'level_2') {?> selected <?php }?>>level_2</option>

                      <option value="level_3" <?php if ($filter_level == 'level_3') {?> selected <?php }?>>level_3</option>

                      <option value="level_4" <?php if ($filter_level == 'level_4') {?> selected <?php }?>>level_4</option>

                      <option value="level_5" <?php if ($filter_level == 'level_5') {?> selected <?php }?>>level_5</option>



                       <option value="level_6" <?php if ($filter_level == 'level_6') {?> selected <?php }?>>level_6</option>

                       <option value="level_7" <?php if ($filter_level == 'level_7') {?> selected <?php }?>>level_7</option>

                      <option value="level_8" <?php if ($filter_level == 'level_8') {?> selected <?php }?>>level_8</option>

                      <option value="level_9" <?php if ($filter_level == 'level_9') {?> selected <?php }?>>level_9</option>

                      <option value="level_10" <?php if ($filter_level == 'level_10') {?> selected <?php }?>>level_10</option>
                        

                        <option value="level_11" <?php if ($filter_level == 'level_11') {?> selected <?php }?>>level_11</option>


                        <option value="level_12" <?php if ($filter_level == 'level_12') {?> selected <?php }?>>level_12</option>


                        <option value="level_13" <?php if ($filter_level == 'level_13') {?> selected <?php }?>>level_13</option>


                        <option value="level_14" <?php if ($filter_level == 'level_14') {?> selected <?php }?>>level_14</option>


                        <option value="level_15" <?php if ($filter_level == 'level_15') {?> selected <?php }?>>level_15</option>
                     </select>

                  </div>

               </div>
               <!-- End Hidden Searches -->

                <!-- Exchange auto/manual order  //Umer Abbas [4-11-19] -->
                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                    <label>Filter Auto/Manual Order: </label>
                    <select id="filter_by_nature" name="filter_by_nature" type="text" class="form-control filter_by_name_margin_bottom_sm">
                       <option value="">Search By Order Type</option>
                       <option value="auto" <?php if ($filter_user_data['filter_by_nature'] == 'auto') {?> selected <?php }?>>Auto Order</option>
                       <option value="manual" <?php if ($filter_user_data['filter_by_nature'] == 'manual') {?> selected <?php }?>>Manual Order</option>
                       <option value="parent" <?php if ($filter_user_data['filter_by_nature'] == 'parent') {?> selected <?php }?>>Parent Order</option>
                    </select>
                  </div>
                </div>

               <!-- Exchange Filter //Umer Abbas [1-11-19] -->
               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter Exchange: </label>
                     <select id="filter_by_exchange" name="filter_by_exchange" type="text" class="form-control filter_by_name_margin_bottom_sm">
                        <option value="">Search By Exchange</option>
                        <option value="bam"<?php if ($filter_user_data['filter_by_exchange'] == 'bam') {?> selected <?php }?>>Bam</option>
                        <option value="binance"<?php if ($filter_user_data['filter_by_exchange'] == 'binance') {?> selected <?php }?>>Binance</option>
                        <option value="coinbasepro"<?php if ($filter_user_data['filter_by_exchange'] == 'coinbasepro') {?> selected <?php }?>>Coinbase pro</option>
                     </select>
                  </div>
               </div>

               
               <!-- Multi-select status filter //Umer Abbas [12-11-19] -->
               <?php $temp_filter_by_status = (!empty($filter_user_data['filter_by_status']) ? $filter_user_data['filter_by_status'] : array() );?>
               <?php if(empty($temp_filter_by_status)){ array_push($temp_filter_by_status, "LTH");}?>
               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter Status: </label>
                     <select id="filter_by_status" name="filter_by_status[]" multiple type="text" class="form-control filter_by_name_margin_bottom_sm">
                       <option value="">Search By Status</option>
                        <option value="new"<?php echo (in_array('new', $filter_user_data['filter_by_status']) ? ' selected ' : ''); ?>>New</option>
                        <option value="open"<?php echo (in_array('open', $filter_user_data['filter_by_status']) ? ' selected ' : ''); ?>>Open</option>
                        <option value="error"<?php echo (in_array('error', $filter_user_data['filter_by_status']) ? ' selected ' : ''); ?>>Error</option>
                        <option value="sold"<?php echo (in_array('sold', $filter_user_data['filter_by_status']) ? ' selected ' : ''); ?>>Sold</option>
                        <option value="LTH"<?php echo (in_array('LTH', $filter_user_data['filter_by_status']) ? ' selected ' : ''); ?>>LTH</option>
                        
                        <option value="submitted_for_sell"<?php echo (in_array('submitted_for_sell', $filter_user_data['filter_by_status']) ? ' selected ' : ''); ?>>submitted_for_sell</option>
                        <option value="FILLED_submitted_for_sell"<?php echo (in_array('FILLED_submitted_for_sell', $filter_user_data['filter_by_status']) ? ' selected ' : ''); ?>>FILLED_submitted_for_sell</option>
                        <option value="submitted_error"<?php echo (in_array('submitted_error', $filter_user_data['filter_by_status']) ? ' selected ' : ''); ?>>submitted_error</option>
                        <option value="submitted"<?php echo (in_array('submitted', $filter_user_data['filter_by_status']) ? ' selected ' : ''); ?>>submitted</option>

                     </select>
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">

                  <div class="Input_text_s">

                     <label>Filter Username: </label>

                     <input type="text" class="form-control" name="filter_username" id="username" value="<?=(!empty($filter_user_data['filter_username']) ? $filter_user_data['filter_username'] : "")?>">

                  </div>

               </div>

               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">

                  <div class="Input_text_s">

                     <label>Sort Field: </label>

                     <?php /*?><label class="radio-inline"><input type="radio" value="created_date" name="optradio" <?php if ($filter_user_data['optradio'] == 'created_date') {?> checked <?php }?>>Created Date</label>

<label class="radio-inline"><input type="radio" value="modified_date" name="optradio" <?php if ($filter_user_data['optradio'] == 'modified_date') {?> checked <?php }?>>Modified Date</label><?php */?>

                     <div class="af-radio-group">

                        <input class="af-cust-radio" value="created_date" name="optradio" type="radio" id="option-one" <?php if ($filter_user_data['optradio'] == 'created_date') {?> checked <?php }?> <?=(empty($filter_user_data['optradio']) ? ' checked ' : '')?>>

                        <label class="af-custom-radio-label" for="option-one">Created Date</label>

                        <input class="af-cust-radio" value="modified_date" name="optradio" type="radio" id="option-two" <?php if ($filter_user_data['optradio'] == 'modified_date') {?> checked <?php }?>>

                        <label class="af-custom-radio-label" for="option-two">Modified Date</label>

                     </div>

                     <div class="af-radio-group af-form-group-created">

                         <input class="af-cust-radio" value="ASC" type="radio" id="option-one2" name="selector" <?php if ($filter_user_data['selector'] == 'ASC') {?> checked <?php }?>>

                        <label class="af-custom-radio-label" for="option-one2">ASC</label>

                        <input class="af-cust-radio" value="DESC" type="radio" id="option-two2" name="selector" <?php if ($filter_user_data['selector'] == 'DESC') {?> checked <?php }?> <?=(empty($filter_user_data['selector']) ? ' checked ' : '')?>>

                        <label class="af-custom-radio-label" for="option-two2">DESC</label>

                     </div>

                  </div>

               </div>

               <div class="col-xs-12 col-sm-12 col-md-12" style="padding-bottom: 6px;">

                  <div class="Input_text_btn">

                     <label></label>

                     <button id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>

                     <a href="<?php echo SURL; ?>admin/reports/reset_filters_report/all" class="btn btn-danger"><i class="fa fa-times-circle"></i>Reset</a>

                     <!-- <span style="float:right;"><button class="btn btn-info" onclick="exportTableToCSV('report.csv')">Export To CSV File</button></span> -->

                     <span style="float:right;"><a href="<?php echo SURL; ?>admin/reports/csv_export_trades" class="btn btn-info">Export To CSV File</a></span>

                  </div>

               </div>

            </div>

            </form>

          </div>

      </div>

    <!-- Widget -->

    <div class="widget widget-inverse">



      <div class="widget-body padding-bottom-none">

      	<div class="table-responsive">

        <!-- Table -->

          <table class="table table-condensed">

           <thead>

              <tr>

                 <th class="text-center"><strong>Coin</strong></th>
                 <th class="text-center"><strong>Price</strong></th>
                 <th class="text-center"><strong>Order Type</strong></th>
                 <th class="text-center"><strong>Level</strong></th>
                 <th class="text-center"><strong>Created Date</strong></th>
                 <th class="text-center"><strong>P/L</strong></th>
                 <th class="text-center"><strong>Market(%)</strong></th>
                 <th class="text-center"><strong>Status</strong></th>
                 <th class="text-center"><strong>Profit(%)</strong></th>
                 <th class="text-center"><strong>Target Profit(%)</strong></th>
                 <th class="text-center"><strong>LTH</strong></th>
                 <th class="text-center"><strong>Stop Loss</strong></th>
                 <th class="text-center"><strong>Sub Status</strong></th>
                 <th class="text-center"><strong>Max(%)/Min(%)</strong></th>
                 <th class="text-center"><strong>5hMax(%)/5hMin(%)</strong></th>
                 <th class="text-center"><strong>User Info</strong></th>
                 <th class="text-center"><strong>User IP</strong></th>
                 <th class="text-center" style="min-width: 120px"><strong>Buy Rules</strong></th>
                 <th class="text-center" style="min-width: 120px"><strong>Sell Rules</strong></th>
                 <th class="text-center"><strong>Log</strong></th>               
                 <th class="text-center"><strong>Quantity</strong></th>
                 <th class="text-center"><strong>Last Modified Date</strong></th>
                 <th class="text-center"><strong>Actions</strong></th>

              </tr>

           </thead>

           <tbody class="af-table-body">

      <?php

if (count($orders) > 0) {
    $abci = 1;
    foreach ($orders as $key => $value) {

        // Get Market Price

        // By ali 9-17  why this fucntion call

        $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

        if ($value['status'] != 'new') {

            if($value['status'] == 'FILLED'){
                $market_value333 = num($value['purchased_price']);
            }else{
                $market_value333 = num($value['market_value']);
            }

        } else {

            $market_value333 = num($market_value);

        }

        if ($value['status'] == 'new') {

            $current_order_price = num($value['price']);

        } else {

            if ($value['status'] == 'FILLED') {
                $current_order_price = num($value['purchased_price']);
            } else {
                $current_order_price = num($value['market_value']);
            }

        }

        $current_data = $market_value333 - $current_order_price;

        $market_data = ($current_data * 100 / $market_value333);

        $market_data = number_format((float) $market_data, 2, '.', '');

        if ($market_value333 > $current_order_price) {

            $class = 'success';

        } else {

            $class = 'danger';

        }

        ?>

      <?php $logo = $this->mod_coins->get_coin_logo($value['symbol']);?>

      <tr class="<?php echo ($value['status'] == 'error')?'hi_error':'' ?>">
        

         <td><img src="<?php echo ASSETS; ?>coin_logo/thumbs/<?php echo $logo; ?>" class="img img-circle" data-toggle="tooltip" data-placement="top" title="<?php echo $value['symbol'] ?>"></td>

         <?php

        if ($value['trigger_type'] != 'no' && $value['price'] == '') {?>

         <td><?php echo strtoupper(str_replace('_', ' ', $value['trigger_type'])); ?></td>

         <?php } else {?>

         <td><?php echo num($value['price']); ?></td>

         <td><?php echo strtoupper(str_replace('_', ' ', $value['trigger_type'])) ?></td>

         <td><?php echo $value['order_level'] ?></td>

         <?php }?>


            <!-- added by 1/8/19 -->
         <td><a data-toggle="tooltip" data-placement="top" title="<?=(!empty($value['created_date']) ? $value['created_date']->toDatetime()->format("d-M, Y H:i:s") : ""); ?>"><?=(!empty($value['created_date']) ? "" : "");
            
             echo "<span class='label label-success'>" . time_elapsed_string($value['created_date']->toDatetime()->format("Y-m-d H:i:s")) . "</span>"?>
             </a>
        </td>

         <td class="center"><b><?php echo num($market_value333); ?></b></td>

         <?php

        if ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') {?>

         <td class="center"><span class="text-<?php echo $class; ?>"><b><?php echo $market_data; ?>%</b></span></td>

         <?php } else {?>

         <td class="center"><span class="text-default"><b>-</b></span></td>

         <?php }?>

         <td class="center">

            <!--  <span class="label label-inverse"><?php echo strtoupper($value['application_mode']); ?></span> -->
            
            <!-- added by 15/8/19 -->

            <span class="label label-success">
            <?php 

               if($value['is_sell_order'] == 'yes'){

                    if ($value['status'] == 'LTH') {

                        echo strtoupper("LTH OPEN"); 
                    }
               
                    else{

                        echo strtoupper('OPEN');
                        
                    } 

               }else{

                    echo strtoupper($value['status']);
                }
            
            ?>
            </span> 
            <span class="custom_refresh" data-id="<?php echo $value['_id']; ?>" order_id="<?php echo $value['binance_order_id']; ?>"> <i class="fa fa-refresh" aria-hidden="true"></i> </span>

         </td>

         <td class="center"><?php

        if ($value['market_sold_price'] != "") {

            $market_sold_price = $value['market_sold_price'];

            $current_data2222 = $market_sold_price - $current_order_price;

            $profit_data = ($current_data2222 * 100 / $market_sold_price);

            $profit_data = number_format((float) $profit_data, 2, '.', '');





            if ($market_sold_price > $current_order_price) {

                $class222 = 'success';

            } else {

                $class222 = 'danger';

            }?>

            <span class="text-<?php echo $class222; ?>"> <b><?php echo $profit_data; ?>%</b> </span>

            <?php

        } else {

            if ($value['status'] == 'FILLED') {

                if ($value['is_sell_order'] == 'yes') {

                    $current_data = num((float) $market_value) - num((float) $value['purchased_price']);

                    $market_data = ($current_data * 100 / $market_value);

                    $market_data = number_format((float) $market_data, 2, '.', '');

                    if ($market_value > $value['purchased_price']) {

                        $class = 'success';

                    } else {

                        $class = 'danger';

                    }

                    echo '<span class="text-' . $class . '"><b>' . $market_data . '%</b></span>';

                } else {

                    echo '<span class="text-default"><b>-</b></span>';

                }
                //<!-- added by 15/8/19 -->
                
            } elseif($value['status'] == 'LTH') {

                if ($value['is_sell_order'] == 'yes') {

                    $current_data = num((float) $market_value) - num((float) $current_order_price);

                    $market_data = ($current_data * 100 / $market_value);

                    $market_data = number_format((float) $market_data, 2, '.', '');

                    if ($market_value > $current_order_price) {

                        $class = 'success';

                    } else {

                        $class = 'danger';

                    }

                    echo '<span class="text-' . $class . '"><b>' . $market_data . '%</b></span>';

                } else {

                    echo '<span class="text-default"><b>-</b></span>';

                }

            }
             else {

                $response .= '<span class="text-default"><b>-</b></span>';

            }

        }?>

         </td>
         
         
        <td class="center">
            <?php if($value['status'] == 'LTH') { ?>
                <span class="text-default"><?=(!empty($value['lth_profit']) ? "<b>{$value['lth_profit']}%</b>" : "<b>-</b>" );?></span>
            <?php }else{ ?>
                <span class="text-default"><?=(!empty($value['sell_profit_percent']) ? "<b>{$value['sell_profit_percent']}%</b>" : "<b>-</b>" );?></span>
            <?php } ?>
        </td>

         <td>

            <?php
                if ($value['is_lth_order'] == 'yes') {
                            ?>
                            <button class="btn btn-warning" style="font-size:12px">
                                LTH
                        </button>
                        <?php
                } else {
                            ?>
                            <button class="btn btn-success" style="font-size:12px">
                                Normal
                        </button>
                        <?php
                }
            ?>

         </td>

        <!-- stop_loss //Umer Abbas [19-11-19] -->
        <td class="center"><?=get_stop_loss($value['_id'], $value['trigger_type']);?></td>

         <td class="center">

            <div class="btn-group btn-group-xs ">

               <?php

        $sell_status = $this->mod_buy_orders->is_sell_order_in_error_status($value['sell_order_id']);

        $sell_status_submit = $this->mod_buy_orders->is_sell_order_in_submitted_status($value['sell_order_id']);

        if ($value['status'] == 'FILLED') {

            if ($value['is_sell_order'] == 'yes') {

                ?>

                      <?php

                if ($value['status'] == 'error') {echo '<span class="label label-danger">ERROR IN BUY</span>';}

                if ($sell_status) {

                    echo '<span class="label label-danger">ERROR IN SELL</span>';

                    echo '<button class="btn btn-sm btn-warning change_error_status" title="Update Error" data-id="' . $value['_id'] . '">Remove Error</button>';

                } elseif ($sell_status_submit) {

                    echo '<span class="label label-success">SUBMITTED FOR SELL</span>';

                } else {

                    echo '<span class="label label-info">WAITING FOR SELL</span>';

                    echo '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num((float) $market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '" order_type="' . $value['order_type'] . '"  buy_order_id="' . $value['_id'] . '">Sell Now</button>';

                }

                ?>

               <!-- <button class="btn btn-info">Submited For Sell</button> -->

               <?php } elseif ($value['is_sell_order'] == 'sold') {?>

               <button class="btn btn-success">Sold</button>

               <?php }

        } elseif ($value['status'] == 'LTH') {

            if ($sell_status) {

                echo '<span class="label label-danger">ERROR IN SELL' . $sell_status . '</span>';

                echo '<button class="btn btn-sm btn-warning change_error_status" title="Update Error" data-id="' . $value['_id'] . '">Remove Error</button>';

            } elseif ($sell_status_submit) {

                echo '<span class="label label-success">SUBMITTED FOR SELL</span>';

            } else {

                echo '<span class="label label-info">WAITING FOR SELL</span>';

                echo '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num((float) $market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '" order_type="' . $value['order_type'] . '"  buy_order_id="' . $value['_id'] . '">Sell Now</button>';

            }

        }?>

            </div>

         </td>







                <td class="center">

                              <?php

        if (isset($value['market_heighest_value']) && $value['market_heighest_value'] != '') {

            $five_hour_max_market_price = $value['market_heighest_value'];

            $purchased_price = (float) $value['purchased_price'];

            $profit = $five_hour_max_market_price - $purchased_price;

            $profit_margin = ($profit / $five_hour_max_market_price) * 100;

            $profit_per = ($profit) * (100 / $purchased_price);

            if ($profit > 0) {

                $color = 'success';

                $word = "Profit";

            } elseif ($profit < 0) {

                $color = 'danger';

                $word = 'Loss';

            }

            ?>

            <span style="font-size: 10px" class="text-<?php echo $color; ?>"><b><?php echo number_format($profit_per, 2); ?>%</b></span>



            <?php

        } else {echo "-";}

        ?>



             |





             <?php

        if (isset($value['market_lowest_value']) && $value['market_lowest_value'] != '') {

            $market_lowest_value = $value['market_lowest_value'];

            $purchased_price = (float) $value['purchased_price'];

            $profit = $market_lowest_value - $purchased_price;

            $profit_margin = ($profit / $market_lowest_value) * 100;

            $profit_per = ($profit) * (100 / $purchased_price);

            if ($profit > 0) {

                $color = 'success';

                $word = "Profit";

            } elseif ($profit < 0) {

                $color = 'danger';

                $word = 'Loss';

            }

            ?>

            <span style="font-size: 10px" class="text-<?php echo $color; ?>"><b><?php echo number_format($profit_per, 2); ?>%</b></span>



            <?php

        } else {echo "-";}

        ?>

                </td>







                 <td class="center">



            <?php

        if (isset($value['5_hour_max_market_price']) && $value['5_hour_max_market_price'] != '') {

            $five_hour_max_market_price = $value['5_hour_max_market_price'];

            $purchased_price = (float) $value['purchased_price'];

            $profit = $five_hour_max_market_price - $purchased_price;

            $profit_margin = ($profit / $five_hour_max_market_price) * 100;

            $profit_per = ($profit) * (100 / $purchased_price);

            if ($profit > 0) {

                $color = 'success';

                $word = "Profit";

            } elseif ($profit < 0) {

                $color = 'danger';

                $word = 'Loss';

            }

            ?>

            <span style="font-size: 10px" class="text-<?php echo $color; ?>"><b><?php echo number_format($profit_per, 2); ?>%</b></span>



            <?php

        } else {echo "-";}

        ?>



             |





             <?php

        if (isset($value['5_hour_min_market_price']) && $value['5_hour_min_market_price'] != '') {

            $market_lowest_value = $value['5_hour_min_market_price'];

            $purchased_price = (float) $value['purchased_price'];

            $profit = $market_lowest_value - $purchased_price;

            $profit_margin = ($profit / $market_lowest_value) * 100;

            $profit_per = ($profit) * (100 / $purchased_price);

            if ($profit > 0) {

                $color = 'success';

                $word = "Profit";

            } elseif ($profit < 0) {

                $color = 'danger';

                $word = 'Loss';

            }

            ?>

            <span style="font-size: 10px" class="text-<?php echo $color; ?>"><b><?php echo number_format($profit_per, 2); ?>%</b></span>



            <?php

        } else {echo "-";}

        ?>





        </td>

        <td><?php echo ($value['admin']['username']) ?></td>
        <td><?php echo ($value['admin']['trading_ip']) ?></td>

              <td><?="Buy Rule: <span class='badge badge-info'>" . $value['buy_rule_number'] . "</span> <br> Buy Via : <span class='badge badge-danger'>" . (($value['is_manual_buy'] == 'yes') ? "Manual" : "Auto") . " </span>";?></td>



              <td><?="Sell Rule: <span class='badge badge-info'>" . $value['sell_rule_number'] . "</span> <br> Sell Via: <span class='badge badge-danger'>" . (($value['is_manual_sold'] == 'yes') ? "Manual" : "Auto") . " </span>";?><br>

                <small>if Sell Rule is 0 the its sold by Stop Loss</small>

              </td>

            <?php

        if ($value['status'] == 'error' || $sell_status) {

            ?>
<!-- // added by 19/8/19 -->
        <td>
            <a data-toggle="collapse" data-target="#abc<?php echo $abci; ?>" > Error</a>
            <div id="abc<?php echo $abci; ?>" class="collapse">
            <?php
                $abci++;
            echo "<strong style='color:red'>" . $value['log']['type'] . "</strong>: " . $value['log']['log_msg'];
            ?>

            </div>
        </td>

        
          <?php } //elseif($value['is_sell_order'] == 'sold') {

            //echo "<td>Sold by admin</td>";

            //}
            else{
                echo "<td>--</td>";
            }

            ?>
        <!-- // added by 1/8/19 -->
        <td><?php echo $value['quantity']; ?></td>
        <!-- // added by 1/8/19 -->

        <td><a data-toggle="tooltip" data-placement="top" title="<?=(!empty($value['modified_date']) ? $value['modified_date']->toDatetime()->format("d-M, Y H:i:s") : ""); ?>">

            <?=(!empty($value['modified_date']) ? "" : "");

            echo "<span class='label label-success'>" . time_elapsed_string($value['modified_date']->toDatetime()->format("Y-m-d H:i:s")) . "</span>"?>
            </a>
        </td>

        <td class="center"><button class="btn btn-xs btn-warning <?php echo ($filter_user_data['filter_by_exchange'] != 'binance' ? 'view_order_details_exchange' : 'view_order_details'); ?>  title="View Order Details" data-id="<?php echo $value['_id']; ?>"><i class="fa fa-eye"></i></button>

        <button type="button" class="btn btn-xs btn-primary viewadmininfo" data-toggle="modal" data-target="#largeShoes" id="<?php echo $value['admin_id']; ?>"><i class="fa fa-eye"></i></button>

            <?php 
            if( $value['status'] == 'error'){
                ?>
                <a href="<?=SURL;?>admin/buy_orders/edit-buy-order/<?=$value['_id'];?>" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-link"></i></a>
                <?php
            }else{
                ?>
                    <a href="<?=SURL;?>admin/sell_orders/edit-order/<?=$value['sell_order_id'];?>" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-link"></i></a>
                <?php
            }
            ?>

        

        <a href="<?=SURL;?>admin/buy_orders/edit-buy-trigger-order/<?=$value['buy_parent_id'];?>" target="_blank" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a>

        <!-- // added by 1/8/19 -->
        <a href="#" target="_blank" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a>
        <!-- // added by 1/8/19 -->
        </td>

      </tr>

      <?php }

}?>

   </tbody>

</table>

</div>

        <!-- // Table END -->


        <?php echo $pagination; ?>



      </div>

    </div>

    <!-- // Widget END -->



  </div>

</div>



<!-- The modal -->

<div class="modal" id="largeShoes" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">

   <div class="modal-dialog modal-sm">

      <div class="modal-content">

         <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

            </button>

            <h4 class="modal-title" id="modalLabelLarge">User Information</h4>

         </div>

         <div class="modal-body" id="mymodalresp">

            Modal content...

         </div>

      </div>

   </div>

</div>

<!-- Start Model -->

<div class="modal fade in" id="modal-order_details" aria-hidden="false">



    <div class="modal-dialog">

        <div class="modal-content">



            <!-- Modal heading -->

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h3 class="modal-title">Order Details</h3>

            </div>

            <!-- // Modal heading END -->



            <!-- Modal body -->

            <div class="modal-body">

                <div class="innerAll">

                    <div class="innerLR" id="response_order_details">

                    </div>

                </div>

            </div>

            <!-- // Modal body END -->



        </div>

    </div>



</div>

<!-- End Model -->



<!-- Start Model -->

<div class="modal fade in" id="modal-order_update" aria-hidden="false">

  <div class="modal-dialog">

    <div class="modal-content">



      <!-- Modal heading -->

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

        <h3 class="modal-title">Order Update</h3>

      </div>

      <!-- // Modal heading END -->



      <!-- Modal body -->

      <div class="modal-body">

        <div class="innerAll">

          <div class="innerLR" id="response_order_update"> </div>

        </div>

      </div>

      <!-- // Modal body END -->



    </div>

  </div>

</div>

<!-- End Model -->


<script>

$(document).ready(function(){
 $('#filter_by_status').multiselect({
  nonSelectedText: 'Select Status',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'100%'
 });
});


$(document).ready(function(e){

   var query = $("#filter_by_trigger").val();

    if (query == 'barrier_trigger') {

        $("#barrier_t").show();

        $("#barrier_p_t").hide();

    }else if(query == 'barrier_percentile_trigger'){

        $("#barrier_t").hide();

        $("#barrier_p_t").show();

    }else{

      $("#barrier_t").hide();

        $("#barrier_p_t").hide();

    }

});

$("body").on("click",".glassflter",function(e){

    var query = $("#filter_by_name").val();

    window.location.href = "<?php echo SURL; ?>/admin/users/?query="+query;

});





$("body").on("change","#filter_by_trigger",function(e){

    var query = $("#filter_by_trigger").val();

    if (query == 'barrier_trigger') {

        $("#barrier_t").show();

        $("#barrier_p_t").hide();

    }else if(query == 'barrier_percentile_trigger'){

        $("#barrier_t").hide();

        $("#barrier_p_t").show();

    }else{

      $("#barrier_t").hide();

      $("#barrier_p_t").hide();

    }

});



$("body").on("click",".viewadmininfo",function(e){

    var user_id = $(this).attr('id');

    $.ajax({

      url: "<?php echo SURL; ?>admin/reports/get_user_info",

      data: {user_id:user_id},

      type: "POST",

      success: function(response){

          $("#mymodalresp").html(response);

      }

    });

});



$("body").on("click",".view_order_details",function(e){

    var order_id = $(this).attr("data-id");
    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/get_buy_order_details_ajax',
        'type': 'POST',
        'data': {order_id:order_id},
        'success': function (response) {
            $('#response_order_details').html(response);
            $("#modal-order_details").modal('show');
        }
    });

});

$("body").on("click",".view_order_details_exchange",function(e){

    var order_id = $(this).attr("data-id");
    var exchange = "<?php echo ($filter_user_data['filter_by_exchange'] != 'binance' ? $filter_user_data['filter_by_exchange'] : ''); ?>";
    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/get_buy_order_details_exchange_ajax',
        'type': 'POST',
        'data': {
            order_id:order_id,
            exchange:exchange
        },
        'success': function (response) {

            console.log(response);
            $('#response_order_details').html(response);
            $("#modal-order_details").modal('show');
        }
    });

});

</script>

<script>

  $( function() {

    availableTags = [];

    $.ajax({

       'url': '<?php echo SURL ?>admin/reports/get_all_usernames_ajax',

       'type': 'POST',

       'data': "",

       'success': function (response) {

          availableTags = JSON.parse(response);



          $( "#username" ).autocomplete({

            source: availableTags

          });

       }

   });



  });





  //Custom switcher by Afzal

  jQuery("body").on("change","#af-swith-asc",function(){

	if(jQuery(".af-switcher-default").hasClass("active")){

		jQuery(".af-switcher-default").removeClass("active");
    jQuery(this).val("ASC");

	}

	else{

		jQuery(".af-switcher-default").addClass("active");
    jQuery(this).val("DESC");
	}

	});

	jQuery("body").on("click",".af-switcher-default",function(){

		if(jQuery(".af-switcher-default").hasClass("active")){

			jQuery(".af-switcher-default").removeClass("active");

		}

		else{

			jQuery(".af-switcher-default").addClass("active");

		}

	});

  	jQuery("body").on("change",".af-cust-radio",function(){

		jQuery(".af-form-group-created").addClass("active");

	});

  //----End--------

  </script>

<!-- <script>

// $("body").on("click",".sell_now_btn",function(e){



//     var id = $(this).attr('data-id');

//     var market_value = $(this).attr('market_value');

//     var quantity = $(this).attr('quantity');

//     var symbol = $(this).attr('symbol');

//     var order_type = $(this).attr('order_type');

//     var buy_order_id = $(this).attr('buy_order_id');



//     $("#"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');



//         if(order_type !='limit_order'){

//         // sell_order(id,market_value,quantity,symbol);



//           sell_market_order(id,market_value,quantity,symbol,buy_order_id);



//         }else{

//          limit_order_cancel(id,market_value,quantity,symbol,buy_order_id);

//         }



// });





//%%%%%%%%%%%%%%%%%%%%%%%5 Sell limit order %%%%%%%%%%%%%%%%%%%%%%%%%%

function sell_market_order(sell_id,market_value,quantity,symbol,buy_order_id){



     $.ajax({

        'url':'<?php echo SURL ?>admin/dashboard/check_status_of_limit_order',

        'type':'POST',

        'data':{sell_id:sell_id,symbol:symbol},

        'success':function(response){

            var rp = JSON.parse(response);

            var resp =rp['status'];

            var current_market_price =rp['market_price'];





            $("#"+sell_id).html('Sell Now');



                if(resp == 'error'){

                    //%%%%%%%%%%%%%%% if order is an error status%%%%%%%%%%%

                        $.confirm({

                            title: 'Attention!',

                            content: 'The order is an error status. Please Remove the error to sell this order',

                            type: 'red',

                            typeAnimated: true,

                            buttons: {

                                tryAgain: {

                                    text: 'Ok',

                                    btnClass: 'btn-red',

                                    action: function(){

                                    }

                                },

                                    close: function () {

                                }

                            }

                        });

                   //%%%%%%%%%%%%%%%End of  error status%%%%%%%%%%%

                }else if (resp == 'submitted'){

                    //%%%%%%%%%%%%%%%%%%% submitted status %%%%%%%%%%%%%%%%%%%%%



                     $.confirm({

                        title: 'Order Status',

                        content: 'Order Already Send for sell',

                        type: 'red',

                        typeAnimated: true,

                        buttons: {

                            tryAgain: {

                                text: 'Ok',

                                btnClass: 'btn-red',

                                action: function(){



                                }

                            },

                                close: function () {

                            }

                        }

                    });

                     //%%%%%%%%%%%%%%%%%%% End of submitted status %%%%%%%%%%%%%%%%%%%%%

                }else if (resp == 'new'){



                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%5  New Status%%%%%%%%%%%%%%%%%%%%%%%%%

                    var content_html ='Select from below to sell this order\

                        <div class="">\

                        <hr>\

                        <form>\

                        <div class="form-group">\

                        <div class="row"><div class="col-xs-6">\

                        <label>Current Market Price</label>\

                        <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\

                        </div><div class="col-xs-6">\

                        <label>Sell Price</label>\

                        <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\

                        </div>\

                        </div>'



                        // <div class="radio">\

                        // <label><input type="radio" name="typ_new_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\

                        // </div>\

                        // <div class="radio">\

                        // <label><input type="radio" name="typ_new_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\

                        // </div>



                        content_html +='<div class="radio ">\

                        <label><input type="radio" name="typ_new_'+sell_id+'" value="m_current">Fire market order on current market price</label>\

                        </div>\

                        </form>\

                        </div>';



                     $.confirm({

                        title: 'Sell order conformation',

                        content: content_html,

                        type: 'red',

                        typeAnimated: true,

                        buttons: {

                            tryAgain: {

                                text: 'Ok',

                                btnClass: 'btn-red',

                                action: function(){



                                    var order_type = $("input[name='typ_new_"+sell_id+"']:checked").val();

                                    ;

                                    var sell_price = $('#sell_price_'+sell_id).val();

                                   sell_market_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);

                                }

                            },

                                close: function () {

                            }

                        }

                    });

                    //%%%%%%%%%%%%%%%%%%%%%%%End of new status %%%%%%%%%%%%%%%%%%%%%%%%

                }





        }

    })



}//End of sell_market_order





function sell_market_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){



    //%%%%%%%%%%%%%%%%%%%%%%%%%%
    

    $.ajax({

        'url': '<?php echo SURL ?>admin/dashboard/sell_market_order_by_user',

        'type': 'POST', //the way you want to send data to your URL

        'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},

        'success': function (response) {

            $("#"+sell_id).html('Sell Now');

            if(response ==''){

                $.alert({

                  title: 'Success!',

                  content: "Order Has been submitted to sell Successfully",

                });

            }else{

                $.confirm({

                    title: 'Encountered an error!',

                    content: response,

                    type: 'red',

                    typeAnimated: true,

                    buttons: {

                        tryAgain: {

                            text: 'Ok',

                            btnClass: 'btn-red',

                            action: function(){

                            }

                        },

                        close: function () {

                        }

                    }

                });

            }



        }

    });

    //%%%%%%%%%%%%%%%%%%%%%%%%%%%

}//End of sell_market_order_by_user





function  limit_order_cancel(sell_id,market_value,quantity,symbol,buy_order_id){



     $.ajax({

        'url':'<?php echo SURL ?>admin/dashboard/check_status_of_limit_order',

        'type':'POST',

        'data':{sell_id:sell_id,symbol:symbol},

        'success':function(response){



            var rp = JSON.parse(response);

            var resp =rp['status'];

            var current_market_price =rp['market_price'];



            $("#"+sell_id).html('Sell Now');



                if(resp == 'error'){

                    //%%%%%%%%%%%%%%% if order is an error status%%%%%%%%%%%

                        $.confirm({

                            title: 'Attention!',

                            content: 'The order is an error status. Please Remove the error to sell this order',

                            type: 'red',

                            typeAnimated: true,

                            buttons: {

                                tryAgain: {

                                    text: 'Ok',

                                    btnClass: 'btn-red',

                                    action: function(){

                                    }

                                },

                                    close: function () {

                                }

                            }

                        });

                   //%%%%%%%%%%%%%%%End of  error status%%%%%%%%%%%

                }else if (resp == 'submitted'){

                    //%%%%%%%%%%%%%%%%%%% submitted status %%%%%%%%%%%%%%%%%%%%%



                        var content_html =' Order is already in <span style="color:orange;    font-size: 14px;"><b>SUBMIT</b></span> status for sell as limit order.'+

                        ' Are you want to  <span style="color:red;    font-size: 14px;"><b>Cancel</b></span>  it ?  And submit to sell agin!\

                        <div class="">\

                        <hr>\

                        <form>\

                        <div class="form-group">\

                        <div class="row"><div class="col-xs-6">\

                        <label>Current Market Price</label>\

                        <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\

                        </div><div class="col-xs-6">\

                        <label>Sell Price</label>\

                        <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\

                        </div>\

                        </div>'



                        // <div class="radio">\

                        // <label><input type="radio" name="typ_submit_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\

                        // </div>\

                        // <div class="radio">\

                        // <label><input type="radio" name="typ_submit_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\

                        // </div>\





                       content_html +=' <div class="radio ">\

                        <label><input type="radio" name="typ_submit_'+sell_id+'" value="m_current">Fire market order on current market price</label>\

                        </div>\

                        </form>\

                        </div>';



                     $.confirm({

                        title: 'Limit order Cancel  and resend order conformation',

                        content: content_html,

                        type: 'red',

                        typeAnimated: true,

                        buttons: {

                            tryAgain: {

                                text: 'Ok',

                                btnClass: 'btn-red',

                                action: function(){



                                    var order_type = $("input[name='typ_submit_"+sell_id+"']:checked").val();

                                    var sell_price = $('#sell_price_'+sell_id).val();



                                   cancel_and_place_new_limit_order_for_sell(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);

                                }

                            },

                                close: function () {

                            }

                        }

                    });

                     //%%%%%%%%%%%%%%%%%%% End of submitted status %%%%%%%%%%%%%%%%%%%%%

                }else if (resp == 'new'){



                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%5  New Status%%%%%%%%%%%%%%%%%%%%%%%%%

                    var content_html ='Select from below to sell this order\

                        <div class="">\

                        <hr>\

                        <form>\

                        <div class="form-group">\

                        <div class="row"><div class="col-xs-6">\

                        <label>Current Market Price</label>\

                        <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\

                        </div><div class="col-xs-6">\

                        <label>Sell Price</label>\

                        <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\

                        </div>\

                        </div>'



                        // <div class="radio">\

                        // <label><input type="radio" name="typ_new_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\

                        // </div>\

                        // <div class="radio">\

                        // <label><input type="radio" name="typ_new_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\

                        // </div>\





                       content_html +=' <div class="radio ">\

                        <label><input type="radio" name="typ_new_'+sell_id+'" value="m_current">Fire market order on current market price</label>\

                        </div>\

                        </form>\

                        </div>';



                     $.confirm({

                        title: 'Sell order conformation',

                        content: content_html,

                        type: 'red',

                        typeAnimated: true,

                        buttons: {

                            tryAgain: {

                                text: 'Ok',

                                btnClass: 'btn-red',

                                action: function(){



                                    var order_type = $("input[name='typ_new_"+sell_id+"']:checked").val();

                                    var sell_price = $('#sell_price_'+sell_id).val();



                                   sell_lmit_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);

                                }

                            },

                                close: function () {

                            }

                        }

                    });

                    //%%%%%%%%%%%%%%%%%%%%%%%End of new status %%%%%%%%%%%%%%%%%%%%%%%%

                }





        }

    })



}//End of limit_order_cancel











function sell_lmit_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){

    //%%%%%%%%%%%%%%%%%%%%%%%%%%

    $.ajax({

        'url': '<?php echo SURL ?>admin/dashboard/sell_lmit_order_by_user',

        'type': 'POST', //the way you want to send data to your URL

        'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},

        'success': function (response) {

            $("#"+sell_id).html('Sell Now');

            if(response ==''){



            }else{

                $.confirm({

                    title: 'Encountered an error!',

                    content: response,

                    type: 'red',

                    typeAnimated: true,

                    buttons: {

                        tryAgain: {

                            text: 'Ok',

                            btnClass: 'btn-red',

                            action: function(){

                            }

                        },

                        close: function () {

                        }

                    }

                });

            }



        }

    });

    //%%%%%%%%%%%%%%%%%%%%%%%%%%%

}//End of sell_lmit_order_by_user



function cancel_and_place_new_limit_order_for_sell(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){

    $.ajax({

        'url': '<?php echo SURL ?>admin/dashboard/cancel_and_place_new_limit_order_for_sell',

        'type': 'POST', //the way you want to send data to your URL

        'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},

        'success': function (response) {

            $("#"+sell_id).html('Sell Now');

            if(response ==''){



            }else{

                $.confirm({

                    title: 'Encountered an error!',

                    content: response,

                    type: 'red',

                    typeAnimated: true,

                    buttons: {

                        tryAgain: {

                            text: 'Ok',

                            btnClass: 'btn-red',

                            action: function(){

                            }

                        },

                        close: function () {

                        }

                    }

                });

            }



        }

    });

}//End of cancel_and_place_new_limit_order_for_sell



function sell_order(id,market_value,quantity,symbol){

    $("#"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');



    $.ajax({

        'url': '<?php echo SURL ?>admin/dashboard/sell_order',

        'type': 'POST', //the way you want to send data to your URL

        'data': {id: id,market_value:market_value,quantity:quantity,symbol:symbol},

        'success': function (response) {



            if(response ==1){

                $("#"+id).html('Sell Now');

            }else{

                $.confirm({

                    title: 'Encountered an error!',

                    content: response,

                    type: 'red',

                    typeAnimated: true,

                    buttons: {

                        tryAgain: {

                            text: 'Ok',

                            btnClass: 'btn-red',

                            action: function(){

                            }

                        },

                        close: function () {

                        }

                    }

                });

            }



        }

    });



}//End of sell_order



$("body").on("click",".change_error_status",function(e){



      var order_id = $(this).attr("data-id");







       $.ajax({

          'url': '<?php echo SURL ?>admin/reports/get_buy_order_error_ajax',

          'type': 'POST',

          'data': {order_id:order_id},

          'success': function (response) {



              $('#response_order_update').html(response);

              $("#modal-order_update").modal('show');

          }

      });



});

</script> -->


<!-- added by 19/8/19 -->
<script type="text/javascript">

    $("body").on("click",".sell_now_btn",function(e){

        var id = $(this).attr('data-id');
        var market_value = $(this).attr('market_value');
        var quantity = $(this).attr('quantity');
        var symbol = $(this).attr('symbol');
        var order_type = $(this).attr('order_type');
        var buy_order_id = $(this).attr('buy_order_id');

        $("#"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

        if(order_type !='limit_order'){
        // sell_order(id,market_value,quantity,symbol);

        sell_market_order(id,market_value,quantity,symbol,buy_order_id);

        }else{
        limit_order_cancel(id,market_value,quantity,symbol,buy_order_id);
        }

    });


    //%%%%%%%%%%%%%%%%%%%%%%%5 Sell limit order %%%%%%%%%%%%%%%%%%%%%%%%%%
    function sell_market_order(sell_id,market_value,quantity,symbol,buy_order_id){

    $.ajax({
        'url':'<?php echo SURL ?>admin/dashboard/check_status_of_limit_order',
        'type':'POST',
        'data':{sell_id:sell_id,symbol:symbol},
        'success':function(response){
            var rp = JSON.parse(response);
            var resp =rp['status'];
            var current_market_price =rp['market_price'];


            $("#"+sell_id).html('Sell Now');

                if(resp == 'error'){
                    //%%%%%%%%%%%%%%% if order is an error status%%%%%%%%%%%
                        $.confirm({
                            title: 'Attention!',
                            content: 'The order is an error status. Please Remove the error to sell this order',
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'Ok',
                                    btnClass: 'btn-red',
                                    action: function(){
                                    }
                                },
                                    close: function () {
                                }
                            }
                        });
                //%%%%%%%%%%%%%%%End of  error status%%%%%%%%%%%
                }else if (resp == 'submitted'){
                    //%%%%%%%%%%%%%%%%%%% submitted status %%%%%%%%%%%%%%%%%%%%%

                    $.confirm({
                        title: 'Order Status',
                        content: 'Order Already Send for sell',
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'Ok',
                                btnClass: 'btn-red',
                                action: function(){

                                }
                            },
                                close: function () {
                            }
                        }
                    });
                    //%%%%%%%%%%%%%%%%%%% End of submitted status %%%%%%%%%%%%%%%%%%%%%
                }else if (resp == 'new' || resp == 'LTH'){

                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%5  New Status%%%%%%%%%%%%%%%%%%%%%%%%%
                    var content_html ='Select from below to sell this order\
                        <div class="">\
                        <hr>\
                        <form>\
                        <div class="form-group">\
                        <div class="row"><div class="col-xs-6">\
                        <label>Current Market Price</label>\
                        <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\
                        </div><div class="col-xs-6">\
                        <label>Sell Price</label>\
                        <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\
                        </div>\
                        </div>'

                        // <div class="radio">\
                        // <label><input type="radio" name="typ_new_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                        // </div>\
                        // <div class="radio">\
                        // <label><input type="radio" name="typ_new_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                        // </div>

                        content_html +='<div class="radio ">\
                        <label><input type="radio" name="typ_new_'+sell_id+'" value="m_current" checked>Fire market order on current market price</label>\
                        </div>\
                        </form>\
                        </div>';

                    $.confirm({
                        title: 'Sell order conformation',
                        content: content_html,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'Ok',
                                btnClass: 'btn-red',
                                action: function(){

                                    var order_type = $("input[name='typ_new_"+sell_id+"']:checked").val();
                                    ;
                                    var sell_price = $('#sell_price_'+sell_id).val();
                                sell_market_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);
                                }
                            },
                                close: function () {
                            }
                        }
                    });
                    //%%%%%%%%%%%%%%%%%%%%%%%End of new status %%%%%%%%%%%%%%%%%%%%%%%%
                }


        }
    })

    }//End of sell_market_order


    function sell_market_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){

    //%%%%%%%%%%%%%%%%%%%%%%%%%%
    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/sell_market_order_by_user',
        'type': 'POST', //the way you want to send data to your URL
        'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},
        'success': function (response) {
            $("#"+sell_id).html('Sell Now');
            if(response ==''){

            }else{
                $.confirm({
                    title: 'Encountered an error!',
                    content: response,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Ok',
                            btnClass: 'btn-red',
                            action: function(){
                            }
                        },
                        close: function () {
                        }
                    }
                });
            }

        }
    });
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%
    }//End of sell_market_order_by_user


    function  limit_order_cancel(sell_id,market_value,quantity,symbol,buy_order_id){

    $.ajax({
        'url':'<?php echo SURL ?>admin/dashboard/check_status_of_limit_order',
        'type':'POST',
        'data':{sell_id:sell_id,symbol:symbol},
        'success':function(response){

            var rp = JSON.parse(response);
            var resp =rp['status'];
            var current_market_price =rp['market_price'];

            $("#"+sell_id).html('Sell Now');

                if(resp == 'error'){
                    //%%%%%%%%%%%%%%% if order is an error status%%%%%%%%%%%
                        $.confirm({
                            title: 'Attention!',
                            content: 'The order is an error status. Please Remove the error to sell this order',
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'Ok',
                                    btnClass: 'btn-red',
                                    action: function(){
                                    }
                                },
                                    close: function () {
                                }
                            }
                        });
                //%%%%%%%%%%%%%%%End of  error status%%%%%%%%%%%
                }else if (resp == 'submitted'){
                    //%%%%%%%%%%%%%%%%%%% submitted status %%%%%%%%%%%%%%%%%%%%%

                        var content_html =' Order is already in <span style="color:orange;    font-size: 14px;"><b>SUBMIT</b></span> status for sell as limit order.'+
                        ' Are you want to  <span style="color:red;    font-size: 14px;"><b>Cancel</b></span>  it ?  And submit to sell agin!\
                        <div class="">\
                        <hr>\
                        <form>\
                        <div class="form-group">\
                        <div class="row"><div class="col-xs-6">\
                        <label>Current Market Price</label>\
                        <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\
                        </div><div class="col-xs-6">\
                        <label>Sell Price</label>\
                        <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\
                        </div>\
                        </div>'

                        // <div class="radio">\
                        // <label><input type="radio" name="typ_submit_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                        // </div>\
                        // <div class="radio">\
                        // <label><input type="radio" name="typ_submit_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                        // </div>\


                    content_html +=' <div class="radio ">\
                        <label><input type="radio" name="typ_submit_'+sell_id+'" value="m_current" checked>Fire market order on current market price</label>\
                        </div>\
                        </form>\
                        </div>';

                    $.confirm({
                        title: 'Limit order Cancel  and resend order conformation',
                        content: content_html,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'Ok',
                                btnClass: 'btn-red',
                                action: function(){

                                    var order_type = $("input[name='typ_submit_"+sell_id+"']:checked").val();
                                    var sell_price = $('#sell_price_'+sell_id).val();

                                cancel_and_place_new_limit_order_for_sell(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);
                                }
                            },
                                close: function () {
                            }
                        }
                    });
                    //%%%%%%%%%%%%%%%%%%% End of submitted status %%%%%%%%%%%%%%%%%%%%%
                }else if (resp == 'new'){

                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%5  New Status%%%%%%%%%%%%%%%%%%%%%%%%%
                    var content_html ='Select from below to sell this order\
                        <div class="">\
                        <hr>\
                        <form>\
                        <div class="form-group">\
                        <div class="row"><div class="col-xs-6">\
                        <label>Current Market Price</label>\
                        <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\
                        </div><div class="col-xs-6">\
                        <label>Sell Price</label>\
                        <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\
                        </div>\
                        </div>'

                        // <div class="radio">\
                        // <label><input type="radio" name="typ_new_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                        // </div>\
                        // <div class="radio">\
                        // <label><input type="radio" name="typ_new_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                        // </div>\


                    content_html +=' <div class="radio ">\
                        <label><input type="radio" name="typ_new_'+sell_id+'" value="m_current" checked>Fire market order on current market price</label>\
                        </div>\
                        </form>\
                        </div>';

                    $.confirm({
                        title: 'Sell order conformation',
                        content: content_html,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'Ok',
                                btnClass: 'btn-red',
                                action: function(){

                                    var order_type = $("input[name='typ_new_"+sell_id+"']:checked").val();
                                    var sell_price = $('#sell_price_'+sell_id).val();

                                sell_lmit_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);
                                }
                            },
                                close: function () {
                            }
                        }
                    });
                    //%%%%%%%%%%%%%%%%%%%%%%%End of new status %%%%%%%%%%%%%%%%%%%%%%%%
                }


        }
    })

    }//End of limit_order_cancel





    function sell_lmit_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){
    //%%%%%%%%%%%%%%%%%%%%%%%%%%
    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/sell_lmit_order_by_user',
        'type': 'POST', //the way you want to send data to your URL
        'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},
        'success': function (response) {
            $("#"+sell_id).html('Sell Now');
            if(response ==''){

            }else{
                $.confirm({
                    title: 'Encountered an error!',
                    content: response,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Ok',
                            btnClass: 'btn-red',
                            action: function(){
                            }
                        },
                        close: function () {
                        }
                    }
                });
            }

        }
    });
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%
    }//End of sell_lmit_order_by_user

    function cancel_and_place_new_limit_order_for_sell(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){
    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/cancel_and_place_new_limit_order_for_sell',
        'type': 'POST', //the way you want to send data to your URL
        'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},
        'success': function (response) {
            $("#"+sell_id).html('Sell Now');
            if(response ==''){

            }else{
                $.confirm({
                    title: 'Encountered an error!',
                    content: response,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Ok',
                            btnClass: 'btn-red',
                            action: function(){
                            }
                        },
                        close: function () {
                        }
                    }
                });
            }

        }
    });
    }//End of cancel_and_place_new_limit_order_for_sell

    function sell_order(id,market_value,quantity,symbol){
    $("#"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/sell_order',
        'type': 'POST', //the way you want to send data to your URL
        'data': {id: id,market_value:market_value,quantity:quantity,symbol:symbol},
        'success': function (response) {

            if(response ==1){
                $("#"+id).html('Sell Now');
            }else{
                $.confirm({
                    title: 'Encountered an error!',
                    content: response,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Ok',
                            btnClass: 'btn-red',
                            action: function(){
                            }
                        },
                        close: function () {
                        }
                    }
                });
            }

        }
    });

    }//End of sell_order
        
        // $("body").on("click",".sell_now_btn",function(e){

        //     var id = $(this).attr('data-id');

        //     var market_value = $(this).attr('market_value');

        //     var quantity = $(this).attr('quantity');

        //     var symbol = $(this).attr('symbol');

        //     var order_type = $(this).attr('order_type');

        //     var buy_order_id = $(this).attr('buy_order_id');

        //     var admin = "admin";

        //     $.confirm({
        //             title: 'Sell Confirmation',
        //             content: 'Are you sure you want to Sell Now?',
        //             icon: 'fa fa-warning',
        //             animation: 'zoom',
        //             closeAnimation: 'zoom',
        //             opacity: 0.5,
        //             buttons: {
        //             confirm: {
        //                 text: 'Yes, sure!',
        //                 btnClass: 'btn-red',
        //                 action: function ()
        //                 {

        //                     $("#"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');
                            
        //                     $.ajax({
        //                         'url': '<?php echo SURL ?>admin/dashboard/check_status_of_limit_order',
        //                         'type': 'POST', //the way you want to send data to your URL
        //                         'data': {id: id,market_value:market_value,quantity:quantity,symbol:symbol,admin:admin},
        //                         'success': function (response) {

        //                             if(response ==1){
        //                                 $("#"+id).html('Sell Now');
        //                             }else{
        //                                 $.confirm({
        //                                     title: 'Encountered an error!',
        //                                     content: response,
        //                                     type: 'red',
        //                                     typeAnimated: true,
        //                                     buttons: {
        //                                         tryAgain: {
        //                                             text: 'Ok',
        //                                             btnClass: 'btn-red',
        //                                             action: function(){
        //                                             }
        //                                         },
        //                                         close: function () {
        //                                         }
        //                                     }
        //                                 });
        //                             }

        //                         }
        //                     });
        //                 }
        //             },
        //             cancel: function () {

        //             }
        //         }

        //     });
        // });




        function downloadCSV(csv, filename) {

        var csvFile;

        var downloadLink;



        // CSV file

        csvFile = new Blob([csv], {type: "text/csv"});



        // Download link

        downloadLink = document.createElement("a");



        // File name

        downloadLink.download = filename;



        // Create a link to the file

        downloadLink.href = window.URL.createObjectURL(csvFile);



        // Hide download link

        downloadLink.style.display = "none";



        // Add the link to DOM

        document.body.appendChild(downloadLink);



        // Click download link

        downloadLink.click();

    }



    function exportTableToCSV(filename) {

        var csv = [];

        var rows = document.querySelectorAll("table tr");



        for (var i = 0; i < rows.length; i++) {

            var row = [], cols = rows[i].querySelectorAll("td, th");



            for (var j = 0; j < cols.length; j++)

                row.push(cols[j].innerText);



            csv.push(row.join(","));

        }



        // Download CSV file

        downloadCSV(csv.join("\n"), filename);

    }

</script>

<script>


</script>