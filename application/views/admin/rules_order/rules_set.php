
<div id="content">
  <div class="heading-buttons bg-white border-bottom innerAll">
    <h1 class="content-heading padding-none pull-left">Rules Order</h1>
    <div class="clearfix"></div>
  </div>
  <div class="innerAll spacing-x2">
    <div class="">
      <div class="widget-body"> 
        
        <!-- Table -->
        <div class="row">
                          <div class="col-md-12 ">
                              <div class="form-group col-md-6">
                                <label class="control-label" for="coin">Select Coin</label>
                                <select class="form-control coin" name="coin" id="coin">
                                                                    <option value="NCASHBTC">NCASHBTC</option>
                                                                    <option value="TRXBTC">TRXBTC</option>
                                                                    <option value="EOSBTC">EOSBTC</option>
                                                                    <option value="POEBTC">POEBTC</option>
                                                                    <option value="NEOBTC">NEOBTC</option>
                                                                    <option value="BCCBTC">BCCBTC</option>
                                                                    <option value="ETCBTC">ETCBTC</option>
                                                                    <option value="XRPBTC">XRPBTC</option>
                                                                    <option value="XEMBTC">XEMBTC</option>
                                                                  </select>
                              </div>
                              
                              <div class="form-group col-md-6">
                                <label class="control-label" for="hour">Select Order Mode </label>
                                <select class="form-control order_mode" name="order_mode">
                                      <option value="live">(Real time and test live)</option>
                                     <option value="test">Simulator Test</option>
                                </select>
                              </div>
                          </div>
                          
                        
        
                        
                         
                            <!--  End of cancel trade-->
        
                            
                          <!--  End  Trigger_1 -->
        
        
                          </div>
        
        <div class="col-md-12 appnedAjax">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#buy">Buy Rules</a></li>
            <li><a data-toggle="tab" href="#sell">Sell Rules</a></li>
          </ul>
          <div class="tab-content ">
           
           
            <div id="buy" class="tab-pane fade in active"> 
              <!-- Buy part -->
              
              
              
              
              <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center checkboxs">
                <thead>
                  <tr>
                    <th class="center"></th>
                    <?php
for ($rule_number = 1; $rule_number <= 10; $rule_number++) {
?>
                       <?php
    if ($rules_set_arr['enable_buy_rule_no_' . $rule_number . ''] == 'yes') {
?>
                       <th class="center"><?php
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
for ($rule_numbernn = 1; $rule_numbernn <= 10; $rule_numbernn++) {
    if ($rules_set_arr['enable_buy_rule_no_' . $rule_numbernn . ''] == 'yes') {
        $rulerecordaaa = '';
        foreach ($rules_set_arr['buy_status_rule_' . $rule_numbernn . ''] as $rulerecord) {
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
		
		
		
		
        if ($rules_set_arr['buy_trigger_type_rule_' . $rule_numbernn . '_enable'] == 'yes') {
            $buy_trigger_type_ruleAll .= '<td>' . $buy_trigger_type_rule . '</td>';
        } else {
            $buy_trigger_type_ruleAll .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
        }
        if ($rules_set_arr['buy_check_volume_rule_' . $rule_numbernn . ''] == 'yes') {
            $buy_volume_rule_ .= '<td>' . $rules_set_arr['buy_volume_rule_' . $rule_numbernn . ''] . '</td>';
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
            $big_seller_percent_compare_rule_ .= ' <td class="center">' . 'OFF' . '</td>';
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
            $rejection_candle_type .= '<td>' . $rules_set_arr['rejection_candle_type' . $rule_numbernn . '_buy'] . '</td>';
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
        if ($rules_set_arr['comment' . $rule_numbernn . '_buy_enable'] == 'yes') {
            $comment .= '<td>' . $rules_set_arr['comment' . $rule_numbernn . '_buy'] . '</td>';
        } else {
            $comment .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
        }
    }
}
?>
              
                
                
                  <tr class="center">
                    <th>Status</th>
                    
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
                    <th>(If Volume greater then the defined value)</th>
                    
                   <?php
echo $buy_volume_rule_;
?>
                 </tr>
                  <tr class="center">
                    <th> Down pressure (when we sell the order we check if the defined percenage is meet) <br /> pressure ( Down pressure is Greater or equal to the defined pressure)</th>
                    <?php echo  $done_pressure_rulebuy ?>
                  </tr>
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
echo $closest_black_wall_rule_;
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
                </tbody>
              </table>
             
            </div>
            <!--End of Buy part -->
            
            <div id="sell" class="tab-pane fade">
             
              <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center checkboxs">
                <thead>
                  <tr>
                    <th class="center"></th>
                    <?php
for ($rule_number = 1; $rule_number <= 10; $rule_number++) {
?>
                       <?php
    if ($rules_set_arr['enable_sell_rule_no_' . $rule_number . ''] == 'yes') {
?>
                       <th class="center"><?php
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

for ($rule_numbernn = 1; $rule_numbernn <= 10; $rule_numbernn++) {
    if ($rules_set_arr['enable_sell_rule_no_' . $rule_numbernn . ''] == 'yes') {
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
		
		
        if ($rules_set_arr['sell_status_rule_' . $rule_numbernn . '_enable'] == 'yes') {
            $sell_status_rule .= '<td>' . $rulerecordsell . '</td>';
        } else {
            $sell_status_rule .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
        }
        if ($rules_set_arr['sell_check_volume_rule_' . $rule_numbernn . ''] == 'yes') {
            $sell_volume_rule_ .= '<td>' . $rules_set_arr['sell_volume_rule_' . $rule_numbernn . ''] . '</td>';
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
            $rejection_candle_type_sell .= '<td>' . $rules_set_arr['rejection_candle_type' . $rule_numbernn . '_sell'] . '</td>';
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
            $comment_sell .= '<td>' . $rules_set_arr['comment' . $rule_numbernn . '_sell'] . '</td>';
        } else {
            $comment_sell .= '<td>' . '<span class="label label-danger">OFF</span>' . '</td>';
        }
    }
}
?>
              
                
                
                  <tr class="center">
                    <th>Status</th>
                    
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
                    <th>(If Volume greater then the defined value)</th>
                    
                   <?php
echo $sell_volume_rule_;
?>
                 </tr>
                  <tr class="center">
                    <th> Down pressure (when we sell the order we check if the defined percenage is meet) <br /> pressure ( Down pressure is Greater or equal to the defined pressure)</th>
                    <?php echo $done_pressure_rule; ?>
                  </tr>
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
                </tbody>
              </table>
               
              <!--End of Sell part --> 
            </div>
            
          </div>
          
          <!-- // Table END --> 
        </div>
        <!-- END ROW --> 
        
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $(document).on('change','.order_mode, .coin',function(){
    
      var order_mode = $('.order_mode').val();
      var coin = $('#coin').val();

      $.ajax({
        'url': '<?php echo base_url(); ?>admin/rules_order/get_global_rulesset_ajax',
        'data': {order_mode:order_mode,coin:coin},
        'type': 'POST',
        success : function(data){
            var res_obj =  JSON.parse(data);
			
			if(res_obj.success==true){
				
				
              $('.appnedAjax').html('');
			  $('.appnedAjax').html(res_obj.html);
			}else{
			   $('.appnedAjax').html(res_obj.html);	
			}
         }
   
     });            

    });

</script>