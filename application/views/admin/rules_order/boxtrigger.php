<?php //echo "<pre>";   print_r($rules_set_arr); exit; ?>
<style>
.loaderImage {
	float: left;
	width: 100%;
	position: relative;
}
.loaderimagbox {
	background: rgba(255, 255, 255, 0.78);
	position: absolute;
	z-index: 9;
	width: 100%;
	height: 100%;
	left: 0;
	top: 0;
	bottom: 0;
}
.loaderimagbox img {
	position: absolute;
	margin: auto;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	border-radius: 50%;
}
</style>
<style>
.loaderImage {
	float: left;
	width: 100%;
	position: relative;
}
.loaderimagbox {
	background: rgba(255, 255, 255, 0.78);
	position: absolute;
	z-index: 9;
	width: 100%;
	height: 100%;
	left: 0;
	top: 0;
	bottom: 0;
}
.loaderimagbox img {
	position: absolute;
	margin: auto;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	border-radius: 50%;
}
table.table.table-bordered.zama_th tr:hover {
    background: #ececec;
}
.headerclass {
    width: 160px;
}
</style>
<style>
.my-tab-scrl .table-scroll {
	position:relative;
	max-width:100%;
	margin:auto;
	overflow:hidden;
}
.my-tab-scrl .table-wrap {
	width:100%;
	overflow:auto;
	position:relative;
	height:calc(100vh - 140px);
}
.my-tab-scrl .table-scroll table {
	width:100%;
	margin:auto;
	border-collapse:separate;
	border-spacing:0;
}
.my-tab-scrl .table-scroll th, .my-tab-scrl .table-scroll td {
	padding:5px 10px;
	background:#fff;
	/*white-space:nowrap;*/
	vertical-align:top;
}
.my-tab-scrl .table-scroll thead, .my-tab-scrl .table-scroll tfoot {
	background:#f9f9f9;
}
.my-tab-scrl .clone {
	position:absolute;
	top:0;
	left:0;
	pointer-events:none;
}
.my-tab-scrl .clone th, .my-tab-scrl .clone td {
	visibility:hidden
}
.my-tab-scrl .clone td, .my-tab-scrl .clone th {
	border-color:transparent
}
.my-tab-scrl .clone tbody th {
	visibility:visible;
	color:#000;
}
.my-tab-scrl .clone .fixcol_th { 
	border:1px solid #ccc;
	background:#f7f7f7;
	visibility:visible;
}
.my-tab-scrl .clone thead, .my-tab-scrl .clone tfoot{background:transparent;}
.tab-hed-foxed {
    position: sticky;
    top: 0;
}








.my-tab-scrl-sell .table-scroll {
	position:relative;
	max-width:100%;
	margin:auto;
	overflow:hidden;
}
.my-tab-scrl-sell .table-wrap {
	width:100%;
	overflow:auto;
	position:relative;
	height:calc(100vh - 140px);
}
.my-tab-scrl-sell .table-scroll table {
	width:100%;
	margin:auto;
	border-collapse:separate;
	border-spacing:0;
}
.my-tab-scrl-sell .table-scroll th, .my-tab-scrl-sell .table-scroll td {
	padding:5px 10px;
	background:#fff;
	/*white-space:nowrap;*/
	vertical-align:top;
}
.my-tab-scrl-sell .table-scroll thead, .my-tab-scrl-sell .table-scroll tfoot {
	background:#f9f9f9;
}
.my-tab-scrl-sell .clone {
	position:absolute;
	top:0;
	left:0;
	pointer-events:none;
}
.my-tab-scrl-sell .clone th, .my-tab-scrl-sell .clone td {
	visibility:hidden
}
.my-tab-scrl-sell .clone td, .my-tab-scrl-sell .clone th {
	border-color:transparent
}
.my-tab-scrl-sell .clone tbody th {
	visibility:visible;
	color:#000;
}
.my-tab-scrl-sell .clone .fixcol_th { 
	border:1px solid #ccc;
	background:#f7f7f7;
	visibility:visible;
}
.my-tab-scrl-sell .clone thead, .my-tab-scrl-sell .clone tfoot{background:transparent;}
.tab-hed-foxed {
    position: sticky;
    top: 0;
}
</style>
<div id="content">
  <div class="heading-buttons bg-white border-bottom innerAll">
    <h1 class="content-heading padding-none pull-left">Rules Order</h1>
    <div class="clearfix"></div>
  </div>
  <div class="innerAll spacing-x2">
    <div class="widget-body"> 
      
      <!-- Table -->
      <div class="row">
        <div class="col-md-12 ">
          <div class="form-group col-md-4">
            <label class="control-label" for="hour">Select Trigger </label>
            <select class="form-control triggers_type" name="triggers_type">
              <option value="">Select Trigger</option>
              <option value="barrier_trigger">Barrier Trigger</option>
             <!-- <option value="trigger_1">Trigger 1</option>
              <option value="trigger_2">Trigger 2</option>-->
              <option value="box_trigger_3">Box Trigger 3</option>
              <option value="barrier_percentile_trigger">Barrier Percentile Trigger</option>
               <option value="market_trend_trigger">Market Trend Trigger</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label class="control-label" for="coin">Select Coin</label>
            <select class="form-control coin" name="coin" id="coin">
              <?php   foreach($coinArrList as $coin){ ?>
              <option value="<?php echo $coin['symbol']; ?>" <?php  echo ($global_symbol==$coin['symbol']) ? 'selected="selected"' : ''; ?>><?php echo $coin['symbol']; ?></option>
              <?php }?>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label class="control-label" for="hour">Select Order Mode </label>
            <select class="form-control order_mode" name="order_mode">
              <option value="live">(Real time and test live)</option>
              <option value="test">Simulator Test</option>
            </select>
          </div>
            
       <!--<div class="form-group col-md-3">
                                <label class="control-label" for="hour">Aggressive stop rule</label>
                                <select class="form-control aggressive_stop_rule" name="aggressive_stop_rule">
                                   <option value="">select aggressive Rule</option>
                                   <option value="stop_loss_rule_1">stop_loss_rule_1</option>
                                   <option value="stop_loss_rule_2">stop_loss_rule_2</option>
                                   <option value="stop_loss_rule_3">stop_loss_rule_3</option>
                                   <option value="stop_loss_rule_big_wall">stop_loss_rule_big_wall</option>
                                </select>
                              </div>-->
                          
          
        </div>
      </div>
      
      <!--  End of cancel trade--> 
      
      <!--  End  Trigger_1 -->
      
      <div class="col-md-12 ">
        <div class="alert alert-danger fade in alert-dismissible errodiv" style="display:none;"> <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> <span class="showerrormessage"></span> </div>
        <br />
      </div>
      <div class="loaderImage">
        <div class="loaderimagbox" style="display:none;"><img src="<?php echo SURL ?>assets/images/loader.gif" /></div>
        <div class="triggercls show_trigger" >
          <div class="col-md-12 appnedAjax" >
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#buy">Buy Rules</a></li>
              <li><a data-toggle="tab" href="#sell">Sell Rules</a></li>
              <li><a data-toggle="tab" href="#stoploss">Stop Loss Rules</a></li>
            </ul>
            <div class="tab-content ">
              <div id="buy" class="tab-pane fade in active"> 
                <!-- Buy part -->
                
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col" style="background:#4267b2; color: #FFF;">Aggressive Stop Rule</th>
                      <th scope="col" style="background:#4267b2; color: #FFF;">Buy Range %</th>
                      <th scope="col" style="background:#4267b2; color: #FFF;">Deep % For Active</th>
                      <th scope="col" style="background:#4267b2; color: #FFF;">Initial Stop Loss</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="centre"><?php
	  
	  if($rules_set_arr->aggressive_stop_rule=='stop_loss_rule_1'){
		  $aggressive_stop_ruleA  =  'Stop Loss Rule One';
	  }else if($rules_set_arr->aggressive_stop_rule=='stop_loss_rule_2'){
		  $aggressive_stop_ruleA  =  'Stop Loss Rule Two'; 
	  }else if($rules_set_arr->aggressive_stop_rule=='stop_loss_rule_3'){
		  $aggressive_stop_ruleA  =  'Stop Loss Rule Three';    
	  }else if($rules_set_arr->aggressive_stop_rule=='stop_loss_rule_big_wall'){
	      $aggressive_stop_ruleA  =  'Stop Loss Rule Big Wall';    
	  }
	   echo $aggressive_stop_ruleA;  ?></td>
                      <td class="centre"><?php echo $rules_set_arr->buy_range_percet .' %';  ?></td>
                      <td class="centre"><?php echo $rules_set_arr->sell_profit_percet .' %';  ?></td>
                      <td class="centre"><?php echo $rules_set_arr->stop_loss_percet;  ?></td>
                    </tr>
                  </tbody>
                </table>
                <table class="table table-bordered zama_th">
                  <thead>
                    <tr>
                      <th class="" style="background:#4267b2; color: #FFF;"><?php echo $global_symbol;  ?></th>
                      <?php
    for ($rule_number = 1; $rule_number <= 10; $rule_number++) {
	    if  ($rules_set_arr['enable_buy_rule_no_' . $rule_number . ''] == 'yes') { ?>
                      <th class="center" style="background:#4267b2; color: #FFF;">
					  <?php  echo $rule_number; ?></th>
                      <?php
        }
    }
    ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
    $buy_status_rule_                 = '';
    $big_seller_percent_compare_rule_ = '';
    $closest_black_wall_rule_         = '';
    $closest_yellow_wall_rule_        = '';
    $seven_level_pressure_rule_       = '';
    $buyer_vs_seller_rule_            = '';
    $last_candle_type                 = '';
    $rejection_candle_type            = '';
    $last_200_contracts_buy_vs_sell   = '';
    $last_200_contracts_time          = '';
    $last_qty_buyers_vs_seller        = '';
    $last_qty_time                    = '';
    $score                            = '';
    $comment                          = '';
    $buy_trigger_type_ruleAll         = '';
    $buy_virtural_rule_               = '';
	$last_candle_status               = '';
    

    for ($rule_numbernn = 1; $rule_numbernn <= 10; $rule_numbernn++) {
        if ($rules_set_arr['enable_buy_rule_no_' . $rule_numbernn . ''] == 'yes') {
            
            
            $buyrulerecordAll = '';
            foreach ($rules_set_arr['buy_order_level_' . $rule_numbernn] as $buyrulerecord) {
				
				if ($buyrulerecord == 'level_1') {
                    $value1 = '<span class="label label-warning">L 1</span>';
                } else if ($buyrulerecord == 'level_2') {
                    $value1 = '<span class="label label-warning">L 2</span>';
                } else if ($buyrulerecord == 'level_3') {
                    $value1 = '<span class="label label-warning">L 3</span>';
				} else if ($buyrulerecord == 'level_4') {
                    $value1 = '<span class="label label-warning">L 4</span>';
				} else if ($buyrulerecord == 'level_5') {
                    $value1 = '<span class="label label-warning">L 5</span>'; 
				} else if ($buyrulerecord == 'level_6') {
                    $value1 = '<span class="label label-warning">L 6</span>';
				} 
                $buyrulerecordAll .= '&nbsp;' . $value1;
            }
			
			$rulerecordaaa = '';
            foreach ($rules_set_arr['buy_status_rule_' . $rule_numbernn] as $rulerecord) {
                $rulerecordaaa .= '&nbsp;' . $rulerecord;
            }
            
            $buy_trigger_type_rule = '';
            foreach ($rules_set_arr['buy_trigger_type_rule_' . $rule_numbernn . ''] as $rulerecord) {
                if ($rulerecord == 'very_strong_barrier') {
                    $value = '<span class="label label-success">VSB</span>';
                } else if ($rulerecord == 'weak_barrier') {
                    $value = '<span class="label label-warning">WB</span>';
                } else if ($rulerecord == 'strong_barrier') {
                    $value = '<span class="label label-info">SB</span>';
                }
                $buy_trigger_type_rule .= '&nbsp;' . $value;
            }
            
			
			if ($rules_set_arr['buyer_vs_seller_rule_' . $rule_numbernn . '_buy_enable'] == 'yes') {
                $buyer_vs_seller_rule .= '<td>' . $rules_set_arr['buyer_vs_seller_rule_' . $rule_numbernn . '_buy'] . '</td>';
            } else {
                $buyer_vs_seller_rule .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
			
            
            if ($rules_set_arr['buy_order_level_' . $rule_numbernn . '_enable'] == 'yes') {
                $buy_order_level .= '<td>' . $buyrulerecordAll . '</td>';
            } else {
                $buy_order_level .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
			
			if ($rules_set_arr['buy_status_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $buy_status_rule_ .= '<td>' . $rulerecordaaa . '</td>';
            } else {
                $buy_status_rule_ .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
			
            
            if ($rules_set_arr['done_pressure_rule_' . $rule_numbernn . '_buy_enable'] == 'yes') {
                $done_pressure_rulebuy .= '<td>' . $rules_set_arr['done_pressure_rule_' . $rule_numbernn . '_buy'] . '</td>';
            } else {
                $done_pressure_rulebuy .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            
            if ($rules_set_arr['buy_virtual_barrier_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $buy_virtural_rule_ .= '<td>' . number_format($rules_set_arr['buy_virtural_rule_' . $rule_numbernn . '']) . '</td>';
            } else {
                $buy_virtural_rule_ .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            
            
            if ($rules_set_arr['buy_trigger_type_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $buy_trigger_type_ruleAll .= '<td>' . $buy_trigger_type_rule . '</td>';
            } else {
                $buy_trigger_type_ruleAll .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['buy_check_volume_rule_' . $rule_numbernn . ''] == 'yes') {
                $buy_volume_rule_ .= '<td>' . number_format($rules_set_arr['buy_volume_rule_' . $rule_numbernn . '']) . '</td>';
            } else {
                $buy_volume_rule_ .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['buy_trigger_type_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $buy_trigger_type_ruleArr .= '<td>' . $buy_trigger_type_rule . '</td>';
            } else {
                $buy_trigger_type_ruleArr .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['big_seller_percent_compare_rule_' . $rule_numbernn . '_buy_enable'] == 'yes') {
                $big_seller_percent_compare_rule_ .= ' <td class="center">' . $rules_set_arr['big_seller_percent_compare_rule_' . $rule_numbernn . '_buy'] . ' %' . '</td>';
            } else {
                $big_seller_percent_compare_rule_ .= ' <td class="center">' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['closest_black_wall_rule_' . $rule_numbernn . '_buy_enable'] == 'yes') {
                $closest_black_wall_rule_ .= ' <td class="center">' . $rules_set_arr['closest_black_wall_rule_' . $rule_numbernn . '_buy'] . '</td>';
            } else {
                $closest_black_wall_rule_ .= ' <td class="center">' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['closest_yellow_wall_rule_' . $rule_numbernn . '_buy_enable'] == 'yes') {
                $closest_yellow_wall_rule_ .= '<td>' . $rules_set_arr['closest_yellow_wall_rule_' . $rule_numbernn . '_buy'] . '</td>';
            } else {
                $closest_yellow_wall_rule_ .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['seven_level_pressure_rule_' . $rule_numbernn . '_buy_enable'] == 'yes') {
                $seven_level_pressure_rule_ .= '<td>' . $rules_set_arr['seven_level_pressure_rule_' . $rule_numbernn . '_buy'] . '</td>';
            } else {
                $seven_level_pressure_rule_ .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['buy_last_candle_type' . $rule_numbernn . '_enable'] == 'yes') {
                $last_candle_type .= '<td>' . $rules_set_arr['last_candle_type' . $rule_numbernn . '_buy'] . '</td>';
            } else {
                $last_candle_type .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['buy_rejection_candle_type' . $rule_numbernn . '_enable'] == 'yes') {
                if($rules_set_arr['rejection_candle_type' . $rule_numbernn . '_buy']=='top_demand_rejection'){
                         $rejection_candle_type .= '<td>' . '<span class="label label-primary">T D R</span>' . '</td>';
                }else if($rules_set_arr['rejection_candle_type' . $rule_numbernn . '_buy']=='top_supply_rejection'){
                         $rejection_candle_type .= '<td>' . '<span class="label label-primary">T S R</span>' . '</td>';
                }else if($rules_set_arr['rejection_candle_type' . $rule_numbernn . '_buy']=='bottom_supply_rejection'){
                         $rejection_candle_type .= '<td>' . '<span class="label label-primary">B S R</span>' . '</td>';
                }else if($rules_set_arr['rejection_candle_type' . $rule_numbernn . '_buy']=='no_rejection'){
                         $rejection_candle_type .= '<td>' . '<span class="label label-primary">NO R</span>' . '</td>';
                }
            } else {
                $rejection_candle_type .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['buy_last_200_contracts_buy_vs_sell' . $rule_numbernn . '_enable'] == 'yes') {
                $last_200_contracts_buy_vs_sell .= '<td>' . $rules_set_arr['last_200_contracts_buy_vs_sell' . $rule_numbernn . '_buy'] . '</td>';
            } else {
                $last_200_contracts_buy_vs_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['buy_last_200_contracts_time' . $rule_numbernn . '_enable'] == 'yes') {
                $last_200_contracts_time .= '<td>' . $rules_set_arr['last_200_contracts_time' . $rule_numbernn . '_buy'] . '</td>';
            } else {
                $last_200_contracts_time .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['buy_last_qty_buyers_vs_seller' . $rule_numbernn . '_enable'] == 'yes') {
                $last_qty_buyers_vs_seller .= '<td>' . $rules_set_arr['last_qty_buyers_vs_seller' . $rule_numbernn . '_buy'] . '</td>';
            } else {
                $last_qty_buyers_vs_seller .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['buy_last_qty_time' . $rule_numbernn . '_enable'] == 'yes') {
                $last_qty_time .= '<td>' . $rules_set_arr['last_qty_time' . $rule_numbernn . '_buy'] . '</td>';
            } else {
                $last_qty_time .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['buy_score' . $rule_numbernn . '_enable'] == 'yes') {
                $score .= '<td>' . $rules_set_arr['score' . $rule_numbernn . '_buy'] . '</td>';
            } else {
                $score .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
          //'.$rules_set_arr['comment' . $rule_numbernn . '_buy'].'
		  //  . substr($rules_set_arr['comment' . $rule_numbernn . '_buy'],0,10) . 
			if ($rules_set_arr['buy_comment' . $rule_numbernn . '_enable'] == 'yes') {
                    $comment .= '<td class="parent"><a class="parent" data-toggle="tooltip" data-placement="top" title="'.$rules_set_arr['comment' . $rule_numbernn . '_buy'].'"> '. substr($rules_set_arr['comment' . $rule_numbernn . '_buy'],0,10).'</a></td>';
            } else {
                    $comment .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
			
			if ($rules_set_arr['buy_last_candle_status' . $rule_numbernn . '_enable'] == 'yes') {
                $last_candle_status .= '<td>' . ucfirst($rules_set_arr['last_candle_status' . $rule_numbernn . '_buy']) . '</td>';
            } else {
                $last_candle_status .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
			
			// Total Order Goes here 
            if($testing!='' && $testing=='testing'){
				 $htmlDataTotalBuyOrder   = '';
				 //$avgProfit  = getAvgPrice($global_symbol,$rule_numbernn,$trigger_type );
				 $responseArr  = getBuyorderData($global_symbol,$rule_numbernn,$trigger_type);
	             
				 $emptyTD .= '<td></td>';
				 $htmlDataTotalBuyOrderEmpty = '<tr class="center" style=""><th style=""></th>'.$emptyTD.'</tr>';
				
				
				 if($responseArr['buyorder']!=''){
				   $buyOrder .= '<td>' . ($responseArr['buyorder']). '</td>';
				 }else{
				   $buyOrder .= '<td>' . '<span class="label label-warning">No Order</span>' . '</td>';	 
				 }
				 $htmlDataTotalBuyOrder  .='<tr class="center" style=""><th style=""><b>Buy Order</b></th> '. $buyOrder.'</tr>';	 
				 
				  
				 if($responseArr['total_sold_orders']!=''){
				   $total_sold_orders .= '<td>' . ($responseArr['total_sold_orders']). '</td>';
				 }else{
				   $total_sold_orders .= '<td>' . '<span class="label label-warning">No Sold Order</span>' . '</td>';	 
				 }
				 $htmlDataTotalBuyOrder  .='<tr class="center" style=""><th style=""><b>Sell Orders</b></th> '. $total_sold_orders.'</tr>';	 
				 
				 
				 
				 
				 if($responseArr['avg_profit']!=''){
				   $avg_profit .= '<td>' . ($responseArr['avg_profit']).' %' . '</td>';
				 }else{
				   $avg_profit .= '<td>' . '<span class="label label-warning">No Sold Order</span>' . '</td>';	 
				 }
				 $htmlDataTotalBuyOrder  .='<tr class="center" style=""><th style=""><b>Avg Profit</b></th> '. $avg_profit.'</tr>';	 
				  
				  
				 if($responseArr['total_buy_amount']!=''){
				   $total_buy_amount .= '<td>' . ($responseArr['total_buy_amount']). '</td>';
				 }else{
				   $total_buy_amount .= '<td>' . '<span class="label label-warning">No Buy Amount</span>' . '</td>';	 
				 }
				 //$htmlDataTotalBuyOrder  .='<tr class="center" style=""><th style=""><b>Buy Amount</b></th> '. $total_buy_amount.'</tr>';
				 
				 	 
				 
				 if($responseArr['total_sell_amount']!=''){
				   $total_sell_amount .= '<td>' . ($responseArr['total_sell_amount']) . '</td>';
				 }else{
				   $total_sell_amount .= '<td>' . '<span class="label label-warning">No Sell Amount</span>' . '</td>';	 
				 }
				 //$htmlDataTotalBuyOrder  .='<tr class="center" style=""><th style=""><b>Sell Amount</b></th> '. $total_sell_amount.'</tr>';	 
				 
				
				 
				 
            }
        }
    }
    ?>
                    <?php  //echo  $rules_set_arr['buy_status_rule_4']; exit; ?>
                    <tr class="center">
                      <th style="width:350px; border-right:3px solid #ddd;">Status</th>
                      <?php
    echo $buy_status_rule_;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Trigger Rule</th>
                      <?php
    echo $buy_trigger_type_ruleAll;
    ?>
                    </tr>
                    <tr class="center">
                      <th>(Virtual Order Book Barrier Range)</th>
                      <?php
    echo $buy_virtural_rule_;
    ?>
                    </tr>
                    <tr class="center">
                      <th>(If Volume greater then the defined value)</th>
                      <?php
    echo $buy_volume_rule_;
    ?>
                    </tr>
                    <tr class="center">
                      <th> Down pressure ( Down pressure is Greater or equal to the defined pressure)</th>
                      <?php echo  $done_pressure_rulebuy ?> </tr>
                    <tr class="center">
                      <th>Big Buyers % ( percent greater then the defined %)</th>
                      <?php
    echo $big_seller_percent_compare_rule_;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Closest black wall( Greater then or equal to the defined value)</th>
                      <?php
    echo $closest_black_wall_rule_;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Closest yellow wall (Greater then or equal to the defined value)</th>
                      <?php
    echo $closest_yellow_wall_rule_;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Seven level pressue (Greater then or equal to the defined value)</th>
                      <?php
    echo $seven_level_pressure_rule_;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Buys vs Seller</th>
                      <?php
    echo $buyer_vs_seller_rule;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Last Candle Type</th>
                      <?php
    echo $last_candle_type;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Rejection Candle Type</th>
                      <?php
    echo $rejection_candle_type;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Last 200 Contract Buyers Vs Sellers</th>
                      <?php
    echo $last_200_contracts_buy_vs_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Last 200 Contract Time(Less then)</th>
                      <?php
    echo $last_200_contracts_time;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Last qty Contract Buyes Vs seller</th>
                      <?php
    echo $last_qty_buyers_vs_seller;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Last qty Contract time(Less then)</th>
                      <?php
    echo $last_qty_time;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Score</th>
                      <?php
    echo $score;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Comment</th>
                      <?php
    echo $comment;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Order Level</th>
                      <?php
    echo $buy_order_level;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Last Candle Status</th>
                      <?php
    echo $last_candle_status;
    ?>
                    </tr>
                    <?php echo $htmlDataTotalBuyOrderEmpty; ?>
                    <?php  echo $htmlDataTotalBuyOrder; ?>
                  </tbody>
                </table>
              </div>
              <!--End of Buy part -->
              <div class="clearfix"></div>
              <div id="sell" class="tab-pane fade">
                <table class="table table-bordered zama_th">
                  <thead>
                    <tr>
                      <th class="" style="background:#4267b2; color: #FFF;"><?php echo $global_symbol;  ?></th>
                      <?php
    for ($rule_number = 1; $rule_number <= 10; $rule_number++) {
    ?>
                      <?php
        if ($rules_set_arr['enable_sell_rule_no_' . $rule_number . ''] == 'yes') {
    ?>
                      <th class="center" style="background:#4267b2; color: #FFF;"><?php
            echo $rule_number;
    ?></th>
                      <?php
        }
    ?>
                      <?php
    }
    ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
    $buy_status_rule_sell             = '';
    $big_seller_percent_compare_rule_ = '';
    $closest_black_wall_rule_         = '';
    $closest_yellow_wall_rule_        = '';
    $seven_level_pressure_rule_       = '';
    $buyer_vs_seller_rule_            = '';
    $last_candle_type                 = '';
    $rejection_candle_type            = '';
    $last_200_contracts_buy_vs_sell   = '';
    $last_200_contracts_time          = '';
    $last_qty_buyers_vs_seller        = '';
    $last_qty_time                    = '';
    $score                            = '';
    $comment                          = '';
    $done_pressure_rule               = '';
    $sell_trigger_type_ruleArr        = '';
    $sell_virtural_rule_              = '';
	$last_candle_status_sell          = '';
    
    for ($rule_numbernn = 1; $rule_numbernn <= 10; $rule_numbernn++) {
        if ($rules_set_arr['enable_sell_rule_no_' . $rule_numbernn . ''] == 'yes') {
            
			$sell_order_level = '';
            foreach ($rules_set_arr['sell_order_level_' . $rule_numbernn . ''] as $rulerecordOrderlevel) {
				
				if ($rulerecordOrderlevel == 'level_1') {
                    $value1 = '<span class="label label-warning">L 1</span>';
                } else if ($rulerecordOrderlevel == 'level_2') {
                    $value1 = '<span class="label label-warning">L 2</span>';
                } else if ($rulerecordOrderlevel == 'level_3') {
                    $value1 = '<span class="label label-warning">L 3</span>';
				} else if ($rulerecordOrderlevel == 'level_4') {
                    $value1 = '<span class="label label-warning">L 4</span>';
				} else if ($rulerecordOrderlevel == 'level_5') {
                    $value1 = '<span class="label label-warning">L 5</span>'; 
				} else if ($rulerecordOrderlevel == 'level_6') {
                    $value1 = '<span class="label label-warning">L 6</span>';
				} 
                $sell_order_level .= '&nbsp;' . $value1;
            }
			
			
			$rulerecordsell = '';
            foreach ($rules_set_arr['sell_status_rule_' . $rule_numbernn . ''] as $rulerecord) {
                $rulerecordsell .= '&nbsp;' . $rulerecord;
            }
			
			
            $sell_trigger_type_rule = '';
            if ($rules_set_arr['sell_trigger_type_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                foreach ($rules_set_arr['sell_trigger_type_rule_' . $rule_numbernn . ''] as $rulerecord) {
                    if ($rulerecord == 'very_strong_barrier') {
                        $value = '<span class="label label-success">VSB</span>';
                    } else if ($rulerecord == 'weak_barrier') {
                        $value = '<span class="label label-warning">WB</span>';
                    } else if ($rulerecord == 'strong_barrier') {
                        $value = '<span class="label label-info">SB</span>';
                    }
                    $sell_trigger_type_rule .= '&nbsp;' . $value;
                }
            }
			
            
            if ($rules_set_arr['done_pressure_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $done_pressure_rule .= '<td>' . $rules_set_arr['done_pressure_rule_' . $rule_numbernn . ''] . '</td>';
            } else {
                $done_pressure_rule .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            
            if ($rules_set_arr['sell_virtual_barrier_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $sell_virtural_rule_ .= '<td>' . $rules_set_arr['sell_virtural_rule_' . $rule_numbernn . ''] . '</td>';
            } else {
                $sell_virtural_rule_ .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            
            
            if ($rules_set_arr['sell_order_level_' . $rule_numbernn . '_enable'] == 'yes') {
                $sell_order_levelAll .= '<td>' . $sell_order_level . '</td>';
            } else {
                $sell_order_levelAll .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
			
            
            
            if ($rules_set_arr['sell_status_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $sell_status_rule .= '<td>' . $rulerecordsell . '</td>';
            } else {
                $sell_status_rule .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_check_volume_rule_' . $rule_numbernn . ''] == 'yes') {
                $sell_volume_rule_ .= '<td>' . number_format($rules_set_arr['sell_volume_rule_' . $rule_numbernn . '']) . '</td>';
            } else {
                $sell_volume_rule_ .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_trigger_type_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $sell_trigger_type_ruleArr .= '<td>' . $sell_trigger_type_rule . '</td>';
            } else {
                $sell_trigger_type_ruleArr .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['big_seller_percent_compare_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $big_seller_percent_compare_rule_sell .= ' <td class="center">' . $rules_set_arr['big_seller_percent_compare_rule_' . $rule_numbernn . ''] . ' %' . '</td>';
            } else {
                $big_seller_percent_compare_rule_sell .= ' <td class="center">' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['closest_black_wall_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $closest_black_wall_rule_sell .= ' <td class="center">' . $rules_set_arr['closest_black_wall_rule_' . $rule_numbernn . ''] . '</td>';
            } else {
                $closest_black_wall_rule_sell .= ' <td class="center">' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_percent_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $sell_percent_rule .= ' <td class="center">' . $rules_set_arr['sell_percent_rule_' . $rule_numbernn . ''] . '</td>';
            } else {
                $sell_percent_rule .= ' <td class="center">' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['closest_yellow_wall_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $closest_yellow_wall_rule_sell .= '<td>' . $rules_set_arr['closest_yellow_wall_rule_' . $rule_numbernn . ''] . '</td>';
            } else {
                $closest_yellow_wall_rule_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['seven_level_pressure_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $seven_level_pressure_rule_sell .= '<td>' . $rules_set_arr['seven_level_pressure_rule_' . $rule_numbernn . ''] . '</td>';
            } else {
                $seven_level_pressure_rule_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_last_candle_type' . $rule_numbernn . '_enable'] == 'yes') {
                $last_candle_type_sell .= '<td>' . $rules_set_arr['last_candle_type' . $rule_numbernn . '_sell'] . '</td>';
            } else {
                $last_candle_type_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            
            if ($rules_set_arr['sell_rejection_candle_type' . $rule_numbernn . '_enable'] == 'yes') {
                
                    if($rules_set_arr['rejection_candle_type' . $rule_numbernn . '_sell'] =='top_supply_rejection'){
                      $rejection_candle_type_sell .= '<td>' .  '<span class="label label-warning">T S R</span>'. '</td>';	 
                    }else{
                      $rejection_candle_type_sell .= '<td>' . $rules_set_arr['rejection_candle_type' . $rule_numbernn . '_sell'] . '</td>';
                    }
                
            } else {
                $rejection_candle_type_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            
            if ($rules_set_arr['seller_vs_buyer_rule_' . $rule_numbernn . '_sell_enable'] == 'yes') {
                $seller_vs_buyer_rule .= '<td>' . $rules_set_arr['seller_vs_buyer_rule_' . $rule_numbernn . '_sell'] . '</td>';
            } else {
                $seller_vs_buyer_rule .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_last_200_contracts_buy_vs_sell' . $rule_numbernn . '_enable'] == 'yes') {
                $last_200_contracts_buy_vs_sell .= '<td>' . $rules_set_arr['last_200_contracts_buy_vs_sell' . $rule_numbernn . '_sell'] . '</td>';
            } else {
                $last_200_contracts_buy_vs_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_last_200_contracts_time' . $rule_numbernn . '_enable'] == 'yes') {
                $last_200_contracts_time_sell .= '<td>' . $rules_set_arr['last_200_contracts_time' . $rule_numbernn . '_sell'] . '</td>';
            } else {
                $last_200_contracts_time_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_last_qty_buyers_vs_seller' . $rule_numbernn . '_enable'] == 'yes') {
                $sell_last_qty_buyers_vs_seller .= '<td>' . $rules_set_arr['last_qty_buyers_vs_seller' . $rule_numbernn . '_sell'] . '</td>';
            } else {
                $sell_last_qty_buyers_vs_seller .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_last_qty_time' . $rule_numbernn . '_enable'] == 'yes') {
                $last_qty_time_sell .= '<td>' . $rules_set_arr['last_qty_time' . $rule_numbernn . '_sell'] . '</td>';
            } else {
                $last_qty_time_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_score' . $rule_numbernn . '_enable'] == 'yes') {
                $score_sell .= '<td>' . $rules_set_arr['score' . $rule_numbernn . '_sell'] . '</td>';
            } else {
                $score_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            
			if ($rules_set_arr['sell_comment' . $rule_numbernn . '_enable'] == 'yes') {
                    $comment_sell .= '<td class="parent"><a class="parent" data-toggle="tooltip" data-placement="top" title="'.$rules_set_arr['comment' . $rule_numbernn . '_sell'].'"> 
					'. substr($rules_set_arr['comment' . $rule_numbernn . '_sell'],0,10).'</a></td>';
            } else {
                    $comment_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
			
			
			if ($rules_set_arr['sell_last_candle_status' . $rule_numbernn . '_enable'] == 'yes') {
                $last_candle_status_sell .= '<td>' . ucfirst($rules_set_arr['last_candle_status' . $rule_numbernn . '_sell']) . '</td>';
            } else {
                $last_candle_status_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
			
			
             if($testing!='' && $testing=='testing'){
				 $avgProfit  = '';
				 $htmlData   = '';
				 //$avgProfit  = getAvgPrice($global_symbol,$rule_numbernn,$trigger_type );
				 $responseArr  = getAvgProfitLoss($global_symbol,$rule_numbernn,$trigger_type);
				 
				 $emptyTD .= '<td></td>';
				 $htmlDataTotalBuyOrderEmpty = '<tr class="center" style=""><th style=""></th>'.$emptyTD.'</tr>';
		
				 if($responseArr['avg_profit']!=''){
				   $avgProfitAll .= '<td>' . number_format($responseArr['avg_profit'],2).' %' . '</td>';
				 }else{
				   $avgProfitAll .= '<td>' . '<span class="label label-warning">No Profit</span>' . '</td>';	 
				 }
				 $htmlData     ='<tr class="center" style=" "><th tyle="">Avg Profit</th> '. $avgProfitAll.'</tr>';	 
				 
				 if($responseArr['total_sold_orders']!=''){
				   $totalSoldOrder .= '<td>' . ($responseArr['total_sold_orders']) . '</td>';
				 }else{
				   $totalSoldOrder .= '<td>' . '<span class="label label-warning">No Order</span>' . '</td>';	 
				 }
				 $htmlDataSoldOrder ='<tr class="center" style=""><th style="">Total Sold Order</th> '. $totalSoldOrder.'</tr>';	 
			 }
        }
    }
    ?>
                    <tr class="center">
                      <th style="width:350px; border-right:3px solid #ddd;">Status</th>
                      <?php
    echo $sell_status_rule;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Trigger Rule</th>
                      <?php
    echo $sell_trigger_type_ruleArr;
    ?>
                    </tr>
                    <tr class="center">
                      <th>(Virtual Order Book Barrier Range)</th>
                      <?php
    echo $sell_virtural_rule_;
    ?>
                    </tr>
                    <tr class="center">
                      <th>(If Volume greater then the defined value)</th>
                      <?php
    echo $sell_volume_rule_;
    ?>
                    </tr>
                    <tr class="center">
                      <th> Down pressure (Sell: Down pressure is Less or equal to the defined pressure)</th>
                      <?php echo $done_pressure_rule; ?> </tr>
                    <tr class="center">
                      <th>Big Buyers % ( percent greater then the defined %)</th>
                      <?php
    echo $big_seller_percent_compare_rule_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Closest black wall( Greater then or equal to the defined value)</th>
                      <?php
    echo $closest_black_wall_rule_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Closest yellow wall (Greater then or equal to the defined value)</th>
                      <?php
    echo $closest_yellow_wall_rule_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Seven level pressue (Greater then or equal to the defined value)</th>
                      <?php
    echo $seven_level_pressure_rule_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th> Sell % (when we sell the order we check if the defined percenage is meet)</th>
                      <?php
    echo $sell_percent_rule;
    ?>
                    </tr>
                    <tr class="center">
                      <th> Seller vs Buys</th>
                      <?php
    echo $seller_vs_buyer_rule;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Last Candle Type</th>
                      <?php
    echo $last_candle_type_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Rejection Candle Type</th>
                      <?php
    echo $rejection_candle_type_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Last 200 Contract Sellers Vs Buyers</th>
                      <?php
    echo $last_200_contracts_buy_vs_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Last 200 Contract Time(Less then)</th>
                      <?php
    echo $last_200_contracts_time_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Last qty Contract seller Vs Buyes</th>
                      <?php
    echo $sell_last_qty_buyers_vs_seller;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Last qty Contract time(Less then)</th>
                      <?php
    echo $last_qty_time_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Score</th>
                      <?php
    echo $score_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Comment</th>
                      <?php
    echo $comment_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Order Level</th>
                      <?php
    echo $sell_order_levelAll;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Last Candle Status</th>
                      <?php
    echo $last_candle_status_sell;
    ?>
                    </tr>
                    <?php   echo $htmlDataTotalBuyOrderEmpty; ?>
                    <?php echo $htmlData; ?> <?php echo $htmlDataSoldOrder; ?>
                  </tbody>
                </table>
                
                <!--End of Sell part --> 
              </div>
              
              <div class="clearfix"></div>
              <div id="stoploss" class="tab-pane fade">
                <table class="table table-bordered zama_th">
                  <thead>
                    <tr>
                      <th class="" style="background:#4267b2; color: #FFF;"><?php echo $global_symbol;  ?></th>
                      <?php
    for ($rule_number = 1; $rule_number <= 10; $rule_number++) {
    ?>
                      <?php
        if ($rules_set_arr['enable_percentile_trigger_stop_loss' . $rule_number . ''] == 'yes') {
    ?>
                      <th class="center" style="background:#4267b2; color: #FFF;"><?php
            echo $rule_number;
    ?></th>
                      <?php
        }
    ?>
                      <?php
    }
    ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
    $buy_status_rule_sell             = '';
    $big_seller_percent_compare_rule_ = '';
    $closest_black_wall_rule_         = '';
    $closest_yellow_wall_rule_        = '';
    $seven_level_pressure_rule_       = '';
    $buyer_vs_seller_rule_            = '';
    $last_candle_type                 = '';
    $rejection_candle_type            = '';
    $last_200_contracts_buy_vs_sell   = '';
    $last_200_contracts_time          = '';
    $last_qty_buyers_vs_seller        = '';
    $last_qty_time                    = '';
    $score                            = '';
    $comment                          = '';
    $done_pressure_rule               = '';
    $sell_trigger_type_ruleArr        = '';
    $sell_virtural_rule_              = '';
	$last_candle_status_sell          = '';
    
    for ($rule_numbernn = 1; $rule_numbernn <= 10; $rule_numbernn++) {
        if ($rules_set_arr['enable_sell_rule_no_' . $rule_numbernn . ''] == 'yes') {
            
			$sell_order_level = '';
            foreach ($rules_set_arr['sell_order_level_' . $rule_numbernn . ''] as $rulerecordOrderlevel) {
				
				if ($rulerecordOrderlevel == 'level_1') {
                    $value1 = '<span class="label label-warning">L 1</span>';
                } else if ($rulerecordOrderlevel == 'level_2') {
                    $value1 = '<span class="label label-warning">L 2</span>';
                } else if ($rulerecordOrderlevel == 'level_3') {
                    $value1 = '<span class="label label-warning">L 3</span>';
				} else if ($rulerecordOrderlevel == 'level_4') {
                    $value1 = '<span class="label label-warning">L 4</span>';
				} else if ($rulerecordOrderlevel == 'level_5') {
                    $value1 = '<span class="label label-warning">L 5</span>'; 
				} else if ($rulerecordOrderlevel == 'level_6') {
                    $value1 = '<span class="label label-warning">L 6</span>';
				} 
                $sell_order_level .= '&nbsp;' . $value1;
            }
			
			
			$rulerecordsell = '';
            foreach ($rules_set_arr['sell_status_rule_' . $rule_numbernn . ''] as $rulerecord) {
                $rulerecordsell .= '&nbsp;' . $rulerecord;
            }
			
			
            $sell_trigger_type_rule = '';
            if ($rules_set_arr['sell_trigger_type_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                foreach ($rules_set_arr['sell_trigger_type_rule_' . $rule_numbernn . ''] as $rulerecord) {
                    if ($rulerecord == 'very_strong_barrier') {
                        $value = '<span class="label label-success">VSB</span>';
                    } else if ($rulerecord == 'weak_barrier') {
                        $value = '<span class="label label-warning">WB</span>';
                    } else if ($rulerecord == 'strong_barrier') {
                        $value = '<span class="label label-info">SB</span>';
                    }
                    $sell_trigger_type_rule .= '&nbsp;' . $value;
                }
            }
			
            
            if ($rules_set_arr['done_pressure_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $done_pressure_rule .= '<td>' . $rules_set_arr['done_pressure_rule_' . $rule_numbernn . ''] . '</td>';
            } else {
                $done_pressure_rule .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            
            if ($rules_set_arr['sell_virtual_barrier_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $sell_virtural_rule_ .= '<td>' . $rules_set_arr['sell_virtural_rule_' . $rule_numbernn . ''] . '</td>';
            } else {
                $sell_virtural_rule_ .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            
            
            if ($rules_set_arr['sell_order_level_' . $rule_numbernn . '_enable'] == 'yes') {
                $sell_order_levelAll .= '<td>' . $sell_order_level . '</td>';
            } else {
                $sell_order_levelAll .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
			
            
            
            if ($rules_set_arr['sell_status_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $sell_status_rule .= '<td>' . $rulerecordsell . '</td>';
            } else {
                $sell_status_rule .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_check_volume_rule_' . $rule_numbernn . ''] == 'yes') {
                $sell_volume_rule_ .= '<td>' . number_format($rules_set_arr['sell_volume_rule_' . $rule_numbernn . '']) . '</td>';
            } else {
                $sell_volume_rule_ .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_trigger_type_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $sell_trigger_type_ruleArr .= '<td>' . $sell_trigger_type_rule . '</td>';
            } else {
                $sell_trigger_type_ruleArr .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['big_seller_percent_compare_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $big_seller_percent_compare_rule_sell .= ' <td class="center">' . $rules_set_arr['big_seller_percent_compare_rule_' . $rule_numbernn . ''] . ' %' . '</td>';
            } else {
                $big_seller_percent_compare_rule_sell .= ' <td class="center">' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['closest_black_wall_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $closest_black_wall_rule_sell .= ' <td class="center">' . $rules_set_arr['closest_black_wall_rule_' . $rule_numbernn . ''] . '</td>';
            } else {
                $closest_black_wall_rule_sell .= ' <td class="center">' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_percent_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $sell_percent_rule .= ' <td class="center">' . $rules_set_arr['sell_percent_rule_' . $rule_numbernn . ''] . '</td>';
            } else {
                $sell_percent_rule .= ' <td class="center">' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['closest_yellow_wall_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $closest_yellow_wall_rule_sell .= '<td>' . $rules_set_arr['closest_yellow_wall_rule_' . $rule_numbernn . ''] . '</td>';
            } else {
                $closest_yellow_wall_rule_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['seven_level_pressure_rule_' . $rule_numbernn . '_enable'] == 'yes') {
                $seven_level_pressure_rule_sell .= '<td>' . $rules_set_arr['seven_level_pressure_rule_' . $rule_numbernn . ''] . '</td>';
            } else {
                $seven_level_pressure_rule_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_last_candle_type' . $rule_numbernn . '_enable'] == 'yes') {
                $last_candle_type_sell .= '<td>' . $rules_set_arr['last_candle_type' . $rule_numbernn . '_sell'] . '</td>';
            } else {
                $last_candle_type_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            
            if ($rules_set_arr['sell_rejection_candle_type' . $rule_numbernn . '_enable'] == 'yes') {
                
                    if($rules_set_arr['rejection_candle_type' . $rule_numbernn . '_sell'] =='top_supply_rejection'){
                      $rejection_candle_type_sell .= '<td>' .  '<span class="label label-warning">T S R</span>'. '</td>';	 
                    }else{
                      $rejection_candle_type_sell .= '<td>' . $rules_set_arr['rejection_candle_type' . $rule_numbernn . '_sell'] . '</td>';
                    }
                
            } else {
                $rejection_candle_type_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            
            if ($rules_set_arr['seller_vs_buyer_rule_' . $rule_numbernn . '_sell_enable'] == 'yes') {
                $seller_vs_buyer_rule .= '<td>' . $rules_set_arr['seller_vs_buyer_rule_' . $rule_numbernn . '_sell'] . '</td>';
            } else {
                $seller_vs_buyer_rule .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_last_200_contracts_buy_vs_sell' . $rule_numbernn . '_enable'] == 'yes') {
                $last_200_contracts_buy_vs_sell .= '<td>' . $rules_set_arr['last_200_contracts_buy_vs_sell' . $rule_numbernn . '_sell'] . '</td>';
            } else {
                $last_200_contracts_buy_vs_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_last_200_contracts_time' . $rule_numbernn . '_enable'] == 'yes') {
                $last_200_contracts_time_sell .= '<td>' . $rules_set_arr['last_200_contracts_time' . $rule_numbernn . '_sell'] . '</td>';
            } else {
                $last_200_contracts_time_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_last_qty_buyers_vs_seller' . $rule_numbernn . '_enable'] == 'yes') {
                $sell_last_qty_buyers_vs_seller .= '<td>' . $rules_set_arr['last_qty_buyers_vs_seller' . $rule_numbernn . '_sell'] . '</td>';
            } else {
                $sell_last_qty_buyers_vs_seller .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_last_qty_time' . $rule_numbernn . '_enable'] == 'yes') {
                $last_qty_time_sell .= '<td>' . $rules_set_arr['last_qty_time' . $rule_numbernn . '_sell'] . '</td>';
            } else {
                $last_qty_time_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            if ($rules_set_arr['sell_score' . $rule_numbernn . '_enable'] == 'yes') {
                $score_sell .= '<td>' . $rules_set_arr['score' . $rule_numbernn . '_sell'] . '</td>';
            } else {
                $score_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
            
			if ($rules_set_arr['sell_comment' . $rule_numbernn . '_enable'] == 'yes') {
                    $comment_sell .= '<td class="parent"><a class="parent" data-toggle="tooltip" data-placement="top" title="'.$rules_set_arr['comment' . $rule_numbernn . '_sell'].'"> 
					'. substr($rules_set_arr['comment' . $rule_numbernn . '_sell'],0,10).'</a></td>';
            } else {
                    $comment_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
			
			
			if ($rules_set_arr['sell_last_candle_status' . $rule_numbernn . '_enable'] == 'yes') {
                $last_candle_status_sell .= '<td>' . ucfirst($rules_set_arr['last_candle_status' . $rule_numbernn . '_sell']) . '</td>';
            } else {
                $last_candle_status_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
            }
			
			
             if($testing!='' && $testing=='testing'){
				 $avgProfit  = '';
				 $htmlData   = '';
				 //$avgProfit  = getAvgPrice($global_symbol,$rule_numbernn,$trigger_type );
				 $responseArr  = getAvgProfitLoss($global_symbol,$rule_numbernn,$trigger_type);
				 
				 $emptyTD .= '<td></td>';
				 $htmlDataTotalBuyOrderEmpty = '<tr class="center" style=""><th style=""></th>'.$emptyTD.'</tr>';
		
				 if($responseArr['avg_profit']!=''){
				   $avgProfitAll .= '<td>' . number_format($responseArr['avg_profit'],2).' %' . '</td>';
				 }else{
				   $avgProfitAll .= '<td>' . '<span class="label label-warning">No Profit</span>' . '</td>';	 
				 }
				 $htmlData     ='<tr class="center" style=" "><th tyle="">Avg Profit</th> '. $avgProfitAll.'</tr>';	 
				 
				 if($responseArr['total_sold_orders']!=''){
				   $totalSoldOrder .= '<td>' . ($responseArr['total_sold_orders']) . '</td>';
				 }else{
				   $totalSoldOrder .= '<td>' . '<span class="label label-warning">No Order</span>' . '</td>';	 
				 }
				 $htmlDataSoldOrder ='<tr class="center" style=""><th style="">Total Sold Order</th> '. $totalSoldOrder.'</tr>';	 
			 }
        }
    }
    ?>
                    <tr class="center">
                      <th style="width:350px; border-right:3px solid #ddd;">Black Wall (Less Then) From defined percentile</th>
                      <?php
    echo $sell_status_rule;
    ?>
                    </tr>
                    <tr class="center">
                      <th>
Virtual Barrier (Greater Then) From defined percentile (Ask volume)</th>
                      <?php
    echo $sell_trigger_type_ruleArr;
    ?>
                    </tr>
                    <tr class="center">
                      <th>(Virtual Barrier Less From defined percentile(Bid Volume))</th>
                      <?php
    echo $sell_virtural_rule_;
    ?>
                    </tr>
                    <tr class="center">
                      <th>(Seven Level (Less Then) From defined percentile)</th>
                      <?php
    echo $sell_volume_rule_;
    ?>
                    </tr>
                    <tr class="center">
                      <th> Last 200 Contract Buyers Vs Sellers (Less then Recommended)</th>
                      <?php echo $done_pressure_rule; ?> </tr>
                    <tr class="center">
                      <th>Last 200 Contract Time (Less Then Recommended)</th>
                      <?php
    echo $big_seller_percent_compare_rule_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Last Qty Contract Buyers Vs Sellers(Less Then Recommended)</th>
                      <?php
    echo $closest_black_wall_rule_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>Last qty Contract time(Less then Recommended)</th>
                      <?php
    echo $closest_yellow_wall_rule_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>(5 Minute Rolling Candel (Less Then ) From defined percentile)</th>
                      <?php
    echo $seven_level_pressure_rule_sell;
    ?>
                    </tr>
                    <tr class="center">
                      <th>15 Minute Rolling Candel (Less Then) From defined percentile</th>
                      <?php
    echo $sell_percent_rule;
    ?>
                    </tr>
                    <tr class="center">
                      <th> (Buyer Should (Less Then) Then Bottom percentile)</th>
                      <?php
    echo $seller_vs_buyer_rule;
    ?>
                    </tr>
                    <tr class="center">
                      <th>(Sellers Should be (Greater From ) Top percentile)</th>
                      <?php
    echo $last_candle_type_sell;
    ?>
                    </tr>
                  
                  </tbody>
                </table>
                
                <!--End of Sell part --> 
              </div>
            </div>
            
            <!--  <div class="pull-right" style="     background: #4267b2; color: #FFF; padding-top: 6px; margin: 0px; padding: 8px 9px 0px 7px;">
              <h4>AVG Profit : <?php echo number_format($avgProfit,2).'%'; ?></h4></div>--> 
            
            <!-- // Table END --> 
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <!-- END ROW -->
    <div class="clearfix"></div>
    <div class="clearfix"></div>
    <div class="clearfix"></div>
    <div class="clearfix"></div>
  </div>
</div>
</div>
<br />
<script type="text/javascript">
$(document).ready(function(e) {
    $("body").on("change",".form-control.triggers_type",function(){
		/*setTimeout(function(){
			$(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');
			$(".table-wrap").scroll(function(){
				var fromTop = $('.table-wrap').scrollTop();
			   $(".my-tab-scrl .clone").css("top",fromTop*(-1));
			});
		},2500);*/ 
	});
});
  $(document).on('click','.getAjaxData',function(){
	  
	  $(".loadprofitIMg").show();    
      var start_date     = $('#start_date').val();
	  var end_date       = $('#end_date').val();
	  var coin           = $('.coin').val();
	  var triggers_type  = $('.triggers_type').val();
	  var userID         = 169;
	  var order_mode     = $('.order_mode').val();
	 
	  
      $.ajax({
        'url': '<?php echo base_url(); ?>admin/rules_order/get_rulesOrderProfit_ajax',
        'data': {start_date:start_date,end_date:end_date,coin:coin,triggers_type:triggers_type,userID:userID,order_mode:order_mode},
        'type': 'POST',
        success : function(data){
            var res_obj =  JSON.parse(data);
			
			if(res_obj.success==true){
			  $('.triggercls').hide();
			  $('.trg_'+triggers_type).show();
              $('.appnedAjax').html('');
			  $('.trg_'+triggers_type).html('');
			  $('.trg_'+triggers_type).html(res_obj.html);
			  $(".loaderimagbox").hide();
			}else{
				
			   $('.triggercls').hide();
			   $('.errodiv').show();
			   $('.showerrormessage').html(res_obj.html);	
			   $(".loaderimagbox").hide();  
			}
			//alert('ttutututu');
			
         }
     });            
    });

</script> 
<script type="text/javascript">

  $(document).on('change','.order_mode, .coin, .triggers_type',function(){
	  
	  $(".loaderimagbox").show();    
      var order_mode     = $('.order_mode').val();
	  var testing        = '<?php echo $testing ?>';
      var coin           = $('.coin').val();
	 
	  var triggers_type  = $('.triggers_type').val();
      $('.errodiv').hide();
	  
      $.ajax({
        'url': '<?php echo base_url(); ?>admin/rules_order/get_global_boxtrigger_ajax',
        'data': {order_mode:order_mode,coin:coin,triggers_type:triggers_type,testing:testing},
        'type': 'POST',
        success : function(data){
            var res_obj =  JSON.parse(data);
			if(res_obj.success==true){
				
              $('.appnedAjax').html('');
			  $('.show_trigger').html('');
			  $('.show_trigger').html(res_obj.html);
			  $(".loaderimagbox").hide();
			}else{
				
			   $('.show_trigger').html('');
			   $('.errodiv').show();
			   $('.showerrormessage').html(res_obj.html);	
			   $(".loaderimagbox").hide();  
			}
			// buyer
			$(".my-tab-scrl .main-table").clone(true).appendTo('.my-tab-scrl #table-scroll').addClass('clone');
			$(".my-tab-scrl .table-wrap").scroll(function(){
				var fromTop = $('.my-tab-scrl .table-wrap').scrollTop();
			   $(".my-tab-scrl .clone").css("top",fromTop*(-1));
			});
			
			// sell
			$(".my-tab-scrl-sell .main-table").clone(true).appendTo('.my-tab-scrl-sell #table-scroll-sell').addClass('clone');
			$(".my-tab-scrl-sell .table-wrap").scroll(function(){
				var fromTop = $('.my-tab-scrl-sell .table-wrap').scrollTop();
			   $(".my-tab-scrl-sell .clone").css("top",fromTop*(-1));
			});
         }
     });            
    });

</script> 
