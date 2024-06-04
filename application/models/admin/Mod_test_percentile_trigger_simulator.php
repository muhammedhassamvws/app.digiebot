<?php
class mod_test_percentile_trigger_simulator extends CI_Model {

	function __construct() {
		# code...
	}


	public function is_triggers_qualify_to_buy_orders($coin_symbol, $order_level,$global_setting_arr,$current_market_price,$coin_meta_arr,$coin_meta_hourly_arr,$simulator_date) {
		$log_arr = array();
			
        $barrier_range_percentage = $global_setting_arr['barrier_percentile_trigger_barrier_range_percentage'];
       

        $five_minute_rolling_candel = $coin_meta_arr['sellers_buyers_per'];
        $fifteen_minute_rolling_candel = $coin_meta_arr['sellers_buyers_per_fifteen'];

        $buyers_fifteen = $coin_meta_arr['buyers_fifteen'];
        $sellers_fifteen = $coin_meta_arr['sellers_fifteen'];

        $black_wall_pressure = $coin_meta_arr['black_wall_pressure'];
        $seven_level_depth = $coin_meta_arr['seven_level_depth'];

        $last_200_buy_vs_sell = $coin_meta_arr['last_200_buy_vs_sell'];
        $last_200_time_ago = (float) $coin_meta_arr['last_200_time_ago'];
        $last_qty_buy_vs_sell = $coin_meta_arr['last_qty_buy_vs_sell'];
        $last_qty_time_ago = (float) $coin_meta_arr['last_qty_time_ago'];
        
        $enable_buy_barrier_percentile = $global_setting_arr['enable_buy_barrier_percentile'];

        //%%%%%%%%%%% -- Check if rule is Enable -- %%%%%%%%%%%%%%%%%
        if ($enable_buy_barrier_percentile == 'not' || $enable_buy_barrier_percentile == '') {
            $log_arr['Percentile_Level_'.$order_level.'_status'] = '<span style="color:red">OFF</span>';
            return $log_arr;
        }//%%%%%%%%%%%% -- End of rule is Enable -- %%%%%%%%%%%%%%%%%%


        $log_arr['-'] = '<span style="color: green;font-size: 27px;">Buy Rules</span><br>';
        $log_arr['Order_Is_Buyed_By_Level'] = $order_level . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Buyers  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    
        $barrier_percentile_trigger_buyers_buy = $global_setting_arr['barrier_percentile_trigger_buyers_buy'];
        $barrier_percentile_trigger_buyers_buy_apply = $global_setting_arr['barrier_percentile_trigger_buyers_buy_apply'];

        $buyers_recommended_percentile_value = $coin_meta_hourly_arr['buyers_fifteen_' . $barrier_percentile_trigger_buyers_buy];

        $buyers_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($barrier_percentile_trigger_buyers_buy_apply == 'yes') {
            if ($buyers_fifteen >= $buyers_recommended_percentile_value) {
                $buyers_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $buyers_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['Buyers_status'] = $rule_on_off;
        $log_arr['Buyers_recommended_percentile'] = $barrier_percentile_trigger_buyers_buy;
        $log_arr['Buyers_recommended_percentile_value'] = $buyers_recommended_percentile_value;
        $log_arr['Buyer_current_value'] = $buyers_fifteen . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $seller_percentile = $global_setting_arr['barrier_percentile_trigger_sellers_buy'];
        $barrier_percentile_trigger_sellers_buy_apply = $global_setting_arr['barrier_percentile_trigger_sellers_buy_apply'];

        $sellers_recommended_percentile_value = $coin_meta_hourly_arr['sellers_fifteen_b_' . $seller_percentile];

        $sellers_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($barrier_percentile_trigger_sellers_buy_apply == 'yes') {
            if ($sellers_fifteen <= $sellers_recommended_percentile_value) {
                $sellers_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $sellers_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['Sellers_status'] = $rule_on_off;
        $log_arr['Sellers_recommended_percentile'] = $seller_percentile;
        $log_arr['Sellers_recommended_percentile_value'] = $sellers_recommended_percentile_value;
        $log_arr['Sellers_current_value'] = $sellers_fifteen . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

		//%%%%%%%%%%%%%%%%%%% -- Barrier Status --%%%%%%%%%%%%%%%%%%%%%%%
        $last_barrrier_value = $this->triggers_trades->list_barrier_status_simulator($coin_symbol,'very_strong_barrier',$current_market_price,'down',$simulator_date);
       

        $barrier_value_range_upside = $last_barrrier_value + ($last_barrrier_value / 100) * $barrier_range_percentage;
        $barrier_value_range_down_side = $last_barrrier_value - ($last_barrrier_value / 100) * $barrier_range_percentage;

        $meet_condition_for_buy = false;

        if($barrier_range_percentage == ''){
            $rule_on_off = '<span style="color:black">OFF</span>';
            $meet_condition_for_buy = true;
        }else{
            $rule_on_off = '<span style="color:red">NO</span>';
            if ((num($current_market_price) >= num($barrier_value_range_down_side)) && (num($current_market_price) <= num($barrier_value_range_upside))) {
                $meet_condition_for_buy = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            }
        }

       
        $log_arr['is_Barrier_Meet'] = $rule_on_off;
        $log_arr['Last_Barrier_price'] = num($last_barrrier_value);
        $log_arr['Barrier_Range_percentage'] = $barrier_range_percentage;
        $log_arr['Barrier_Range'] = 'Barrir From <b>(' . num($barrier_value_range_down_side) . ')</b> To  <b>(' . num($barrier_value_range_upside) . ')</b><br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_15_minute_rolling_candel = $global_setting_arr['barrier_percentile_trigger_15_minute_rolling_candel'];
        $percentile_trigger_15_minute_rolling_candel_apply = $global_setting_arr['barrier_percentile_trigger_15_minute_rolling_candel_apply'];

        $percentile_trigger_15_minute_rolling_candel_actual_value = $coin_meta_hourly_arr['fifteen_min_' . $percentile_trigger_15_minute_rolling_candel];

        $fifteen_minute_rolling_candel_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_15_minute_rolling_candel_apply == 'yes') {
            if ($percentile_trigger_15_minute_rolling_candel_actual_value <= $fifteen_minute_rolling_candel) {
                $fifteen_minute_rolling_candel_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $fifteen_minute_rolling_candel_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['15_minute_rolling_candel_status'] = $rule_on_off;
        $log_arr['15_minute_rolling_candel_recommended_percentile'] = $percentile_trigger_15_minute_rolling_candel;
        $log_arr['15_minute_rolling_candel_recommended_percentile_value'] = $percentile_trigger_15_minute_rolling_candel_actual_value;
        $log_arr['15_minute_rolling_candel_current_value'] = $fifteen_minute_rolling_candel . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_5_minute_rolling_candel = $global_setting_arr['barrier_percentile_trigger_5_minute_rolling_candel'];
        $percentile_trigger_5_minute_rolling_candel_apply = $global_setting_arr['barrier_percentile_trigger_5_minute_rolling_candel_apply'];

        $percentile_trigger_5_minute_rolling_candel_actual_value = $coin_meta_hourly_arr['five_min_' . $percentile_trigger_5_minute_rolling_candel];

        $five_minute_rolling_candel_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_5_minute_rolling_candel_apply == 'yes') {
            if ($percentile_trigger_5_minute_rolling_candel_actual_value <= $five_minute_rolling_candel) {
                $five_minute_rolling_candel_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $five_minute_rolling_candel_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['5_minute_rolling_candel_status'] = $rule_on_off;
        $log_arr['5_minute_rolling_candel_recommended_percentile'] = $percentile_trigger_5_minute_rolling_candel;
        $log_arr['5_minute_rolling_candel_recommended_percentile_value'] = $percentile_trigger_5_minute_rolling_candel_actual_value;
        $log_arr['5_minute_rolling_candel_current_value'] = $five_minute_rolling_candel . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_black_wall = $global_setting_arr['barrier_percentile_trigger_buy_black_wall'];
        $percentile_trigger_black_wall_apply = $global_setting_arr['barrier_percentile_trigger_buy_black_wall_apply'];
        $percentile_trigger_black_wall_actual_value = $coin_meta_hourly_arr['blackwall_' . $percentile_trigger_black_wall];

        $black_wall_yes_no = true;

        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_black_wall_apply == 'yes') {
            if ($percentile_trigger_black_wall_actual_value <= $black_wall_pressure) {
                $black_wall_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $black_wall_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['black_wall_status'] = $rule_on_off;
        $log_arr['black_wall_recommended_percentile'] = $percentile_trigger_black_wall;
        $log_arr['black_wall_recommended_percentile_value'] = $percentile_trigger_black_wall_actual_value;
        $log_arr['black_wall_current_value'] = $black_wall_pressure . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_virtual_barrier = $global_setting_arr['barrier_percentile_trigger_buy_virtual_barrier'];
        $percentile_trigger_virtual_barrier_apply = $global_setting_arr['barrier_percentile_trigger_buy_virtual_barrier_apply'];
        $barrrier_actual_value = $coin_meta_hourly_arr['bid_quantity_' . $percentile_trigger_virtual_barrier];

		//*********************************************************************/

        $total_bid_quantity = $coin_meta_arr['market_depth_quantity'];
        
	
        //*********************************************************************/
        $virtual_barrier_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_virtual_barrier_apply == 'yes') {
            if ($barrrier_actual_value <= $total_bid_quantity) {
                $virtual_barrier_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $virtual_barrier_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['virtual_barrier_status'] = $rule_on_off;
        $log_arr['virtual_barrier_recommended_percentile'] = $percentile_trigger_virtual_barrier;
        $log_arr['virtual_barrier_recommended_percentile_value'] = $barrrier_actual_value;
        $log_arr['virtual_barrier_current_value'] = $total_bid_quantity . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_seven_level_pressure = $global_setting_arr['barrier_percentile_trigger_buy_seven_level_pressure'];
        $percentile_trigger_seven_level_pressure_apply = $global_setting_arr['barrier_percentile_trigger_buy_seven_level_pressure_apply'];

        $seven_level_pressure_actual_value = $coin_meta_hourly_arr['sevenlevel_' . $percentile_trigger_seven_level_pressure];

        $seven_level_pressure_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_seven_level_pressure_apply == 'yes') {
            if ($seven_level_pressure_actual_value <= $seven_level_depth) {
                $seven_level_pressure_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $seven_level_pressure_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['seven_level_pressure_status'] = $rule_on_off;
        $log_arr['seven_level_pressure_recommended_percentile'] = $percentile_trigger_seven_level_pressure;
        $log_arr['seven_level_pressure_recommended_percentile_value'] = $seven_level_pressure_actual_value;
        $log_arr['seven_level_pressure_current_value'] = $seven_level_depth . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $last_200_contracts_buy_vs_sell_percentile_trigger = $global_setting_arr['barrier_percentile_trigger_buy_last_200_contracts_buy_vs_sell'];
        $last_200_contracts_buy_vs_sell_percentile_trigger_apply = $global_setting_arr['barrier_percentile_trigger_buy_last_200_contracts_buy_vs_sell_apply'];

        $last_200_contracts_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($last_200_contracts_buy_vs_sell_percentile_trigger_apply == 'yes') {
            if ($last_200_buy_vs_sell >= $last_200_contracts_buy_vs_sell_percentile_trigger) {
                $last_200_contracts_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_200_contracts_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['last_200_buy_vs_sell_status'] = $rule_on_off;
        $log_arr['last_200_buy_vs_sell_recommended_value'] = $last_200_contracts_buy_vs_sell_percentile_trigger;
        $log_arr['last_200_buy_vs_sell_current_value'] = $last_200_buy_vs_sell . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $last_200_contracts_time_percentile_trigger = $global_setting_arr['barrier_percentile_trigger_buy_last_200_contracts_time'];
        $last_200_contracts_time_percentile_trigger_apply = $global_setting_arr['barrier_percentile_trigger_buy_last_200_contracts_time_apply'];

        $last_200_contracts_time_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($last_200_contracts_time_percentile_trigger_apply == 'yes') {
            if ($last_200_time_ago <= $last_200_contracts_time_percentile_trigger) {
                $last_200_contracts_time_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_200_contracts_time_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['last_200_time_ago_status'] = $rule_on_off;
        $log_arr['last_200_time_ago_recommended_value'] = $last_200_contracts_time_percentile_trigger;
        $log_arr['last_200_time_ago_current_value'] = $last_200_time_ago . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $last_qty_contracts_buyer_vs_seller_percentile_trigger = $global_setting_arr['barrier_percentile_trigger_buy_last_qty_contracts_buyer_vs_seller'];
        $last_qty_contracts_buyer_vs_seller_percentile_trigger_apply = $global_setting_arr['barrier_percentile_trigger_buy_last_qty_contracts_buyer_vs_seller_apply'];

        $last_200_contracts_qty_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($last_qty_contracts_buyer_vs_seller_percentile_trigger_apply == 'yes') {
            if ($last_qty_buy_vs_sell >= $last_qty_contracts_buyer_vs_seller_percentile_trigger) {
                $last_200_contracts_qty_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_200_contracts_qty_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['last_qty_buy_vs_sell_status'] = $rule_on_off;
        $log_arr['last_qty_buy_vs_sell_recommended_value'] = $last_qty_contracts_buyer_vs_seller_percentile_trigger;
        $log_arr['last_qty_buy_vs_sell_current_value'] = $last_qty_buy_vs_sell . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $last_qty_contracts_time_percentile_trigger = $global_setting_arr['barrier_percentile_trigger_buy_last_qty_contracts_time'];
        $last_qty_contracts_time_percentile_trigger_apply = $global_setting_arr['barrier_percentile_trigger_buy_last_qty_contracts_time_apply'];

        $last_qty_contracts_time_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($last_qty_contracts_time_percentile_trigger_apply == 'yes') {
            if ($last_qty_time_ago <= $last_qty_contracts_time_percentile_trigger) {
                $last_qty_contracts_time_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_qty_contracts_time_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['last_qty_contracts_time_status'] = $rule_on_off;
        $log_arr['last_qty_contracts_time_recommended_value'] = $last_qty_contracts_time_percentile_trigger;
        $log_arr['last_qty_contracts_time_current_value'] = $last_qty_time_ago . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

		 //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Last Procedding Orders %%%%%%%%%%%%%%%%%%%%%%%%%%%%
		 $last_procedding_status = $this->triggers_trades->last_procedding_candle_status_history($coin_symbol,$simulator_date);
		 $current_candel_status = $global_setting_arr['percentile_trigger_last_candle_type'];
		 $candel_status_meet = true;
		 $rule_on_off = '<span style="background-color:yellow">OFF</span>';
		 if ($global_setting_arr['barrier_percentile_is_previous_blue_candel'] == 'yes') {
			 if ($last_procedding_status == $current_candel_status) {
				 $candel_status_meet = true;
				 $rule_on_off = '<span style="color:green">YES</span>';
			 } else {
				 $candel_status_meet = false;
				 $rule_on_off = '<span style="color:red">NO</span>';
			 }
		 }
 
		 $log_arr['last_candel_status'] = $rule_on_off;
		 $log_arr['recommended_candel_status'] = $current_candel_status;
		 $log_arr['Last_procedding_candel_status'] = $last_procedding_status . '<br>';

		//%%%%%%%%%%%%%%%%%%%%%%%%-- -- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

		//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $log_msg = '';
        foreach ($log_arr as $key => $value) {
            $log_msg .= $key . '=>' . $value . '<br>';
        }

        $is_buy_rule_on = false;
        if ($global_setting_arr['enable_buy_barrier_percentile'] == 'yes') {
            $is_buy_rule_on = true;
        }
		//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $is_rules_true = 'NO';
        if ($last_200_contracts_qty_yes_no && $last_200_contracts_time_yes_no && $last_200_contracts_yes_no && $black_wall_yes_no && $virtual_barrier_yes_no && $seven_level_pressure_yes_no && $last_qty_contracts_time_yes_no && $is_buy_rule_on && $five_minute_rolling_candel_yes_no && $fifteen_minute_rolling_candel_yes_no && $meet_condition_for_buy && $buyers_yes_no && $sellers_yes_no && $candel_status_meet) {
            $is_rules_true = 'YES';
        }
	
        $response['success_message'] = $is_rules_true;
		$response['log_message'] = $log_msg;
        return $response;
    } //End of is_triggers_qualify_to_buy_orders

    
	public function is_triggers_qualify_to_sell_orders($coin_symbol, $order_level,$global_setting_arr,$current_market_price,$coin_meta_arr,$coin_meta_hourly_arr,$simulator_date) {

        $black_wall_pressure = $coin_meta_arr['black_wall_pressure'];
        $seven_level_depth = $coin_meta_arr['seven_level_depth'];

        $five_minute_rolling_candel = $coin_meta_arr['sellers_buyers_per'];
        $fifteen_minute_rolling_candel = $coin_meta_arr['sellers_buyers_per_fifteen'];

        $last_200_buy_vs_sell = $coin_meta_arr['last_200_buy_vs_sell'];
        $last_200_time_ago = (float) $coin_meta_arr['last_200_time_ago'];
        $last_qty_buy_vs_sell = $coin_meta_arr['last_qty_buy_vs_sell'];
        $last_qty_time_ago = (float) $coin_meta_arr['last_qty_time_ago'];


        $buyers_fifteen = $coin_meta_arr['buyers_fifteen'];
        $sellers_fifteen = $coin_meta_arr['sellers_fifteen'];

        $log_arr['-'] = '<span style="color: green;font-size: 27px;">Sell Rules</span><br>';

        $enable_sell_barrier_percentile = $global_setting_arr['enable_sell_barrier_percentile'];
        if ($enable_sell_barrier_percentile == 'not' || $enable_sell_barrier_percentile == '') {
            $log_arr['Percentile_Level_'.$order_level.'_status'] = '<span style="color:red">OFF</span>';
            return $log_arr;
        }


        //%%%%%%%%%%%%%%%%%%% -- Barrier Status --%%%%%%%%%%%%%%%%%%%%%%%
        $last_barrrier_value = $this->triggers_trades->list_barrier_status_simulator($coin_symbol,'very_strong_barrier',$current_market_price,'up',$simulator_date);
       
        $barrier_range_percentage = $global_setting_arr['barrier_percentile_trigger_barrier_range_percentage_sell'];
        $barrier_value_range_upside = $last_barrrier_value + ($last_barrrier_value / 100) * $barrier_range_percentage;
        $barrier_value_range_down_side = $last_barrrier_value - ($last_barrrier_value / 100) * $barrier_range_percentage;
        
        $meet_condition_for_buy = false;
        $rule_on_off = '<span style="color:red">NO</span>';

        if($barrier_range_percentage !='' || $barrier_range_percentage !=0){
            if ((num($current_market_price) >= num($barrier_value_range_down_side)) && (num($current_market_price) <= num($barrier_value_range_upside))) {
                $meet_condition_for_buy = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            }
        }else{
            $meet_condition_for_buy = true;
            $rule_on_off = '<span style="color:Black">OFF</span>';
        }
        
        $log_arr['is_Barrier_Meet'] = $rule_on_off;
        $log_arr['Last_Barrier_price'] = num($last_barrrier_value);
        $log_arr['Barrier_Range_percentage'] = $barrier_range_percentage;
        $log_arr['Barrier_Range'] = 'Barrir From <b>(' . num($barrier_value_range_down_side) . ')</b> To  <b>(' . num($barrier_value_range_upside) . ')</b><br>';
        

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Buyers  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $barrier_percentile_trigger_buyers_buy = $global_setting_arr['barrier_percentile_trigger_buyers_sell'];
        $barrier_percentile_trigger_buyers_buy_apply = $global_setting_arr['barrier_percentile_trigger_buyers_sell_apply'];

        $buyers_recommended_percentile_value = $coin_meta_hourly_arr['buyers_fifteen_b_' . $barrier_percentile_trigger_buyers_buy];

        $buyers_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($barrier_percentile_trigger_buyers_buy_apply == 'yes') {
            if ($buyers_fifteen <= $buyers_recommended_percentile_value) {
                $buyers_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $buyers_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['Buyers_status'] = $rule_on_off;
        $log_arr['Buyers_recommended_percentile'] = $barrier_percentile_trigger_buyers_buy;
        $log_arr['Buyers_recommended_percentile_value'] = $buyers_recommended_percentile_value;
        $log_arr['Buyer_current_value'] = $buyers_fifteen . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Sellers  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $seller_percentile = $global_setting_arr['barrier_percentile_trigger_sellers_sell'];
        $barrier_percentile_trigger_sellers_buy_apply = $global_setting_arr['barrier_percentile_trigger_sellers_sell_apply'];

        $sellers_recommended_percentile_value = $coin_meta_hourly_arr['sellers_fifteen_' . $seller_percentile];

        $sellers_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($barrier_percentile_trigger_sellers_buy_apply == 'yes') {
            if ($sellers_fifteen >= $sellers_recommended_percentile_value) {
                $sellers_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $sellers_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['Sellers_status'] = $rule_on_off;
        $log_arr['Sellers_recommended_percentile'] = $seller_percentile;
        $log_arr['Sellers_recommended_percentile_value'] = $sellers_recommended_percentile_value;
        $log_arr['Sellers_current_value'] = $sellers_fifteen . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_15_minute_rolling_candel = $global_setting_arr['barrier_percentile_trigger_15_minute_rolling_candel'];
        $percentile_trigger_15_minute_rolling_candel_apply = $global_setting_arr['barrier_percentile_trigger_15_minute_rolling_candel_apply'];

        $percentile_trigger_15_minute_rolling_candel_actual_value = $coin_meta_hourly_arr['fifteen_min_b_' . $percentile_trigger_15_minute_rolling_candel];

        $fifteen_minute_rolling_candel_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_15_minute_rolling_candel_apply == 'yes') {
            if ($percentile_trigger_15_minute_rolling_candel_actual_value >= $fifteen_minute_rolling_candel) {
                $fifteen_minute_rolling_candel_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $fifteen_minute_rolling_candel_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['15_minute_rolling_candel_status'] = $rule_on_off;
        $log_arr['15_minute_rolling_candel_recommended_percentile'] = $percentile_trigger_15_minute_rolling_candel;
        $log_arr['15_minute_rolling_candel_recommended_percentile_value'] = $percentile_trigger_15_minute_rolling_candel_actual_value;
        $log_arr['15_minute_rolling_candel_current_value'] = $fifteen_minute_rolling_candel . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_5_minute_rolling_candel = $global_setting_arr['barrier_percentile_trigger_5_minute_rolling_candel'];
        $percentile_trigger_5_minute_rolling_candel_apply = $global_setting_arr['barrier_percentile_trigger_5_minute_rolling_candel_apply'];

        $percentile_trigger_5_minute_rolling_candel_actual_value = $coin_meta_hourly_arr['five_min_b_' . $percentile_trigger_5_minute_rolling_candel];

        $five_minute_rolling_candel_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_5_minute_rolling_candel_apply == 'yes') {
            if ($percentile_trigger_5_minute_rolling_candel_actual_value >= $five_minute_rolling_candel) {
                $five_minute_rolling_candel_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $five_minute_rolling_candel_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['5_minute_rolling_candel_status'] = $rule_on_off;
        $log_arr['5_minute_rolling_candel_recommended_percentile'] = $percentile_trigger_5_minute_rolling_candel;
        $log_arr['5_minute_rolling_candel_recommended_percentile_value'] = $percentile_trigger_5_minute_rolling_candel_actual_value;
        $log_arr['5_minute_rolling_candel_current_value'] = $five_minute_rolling_candel . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_black_wall = $global_setting_arr['barrier_percentile_trigger_sell_black_wall'];

        $percentile_trigger_black_wall_apply = $global_setting_arr['barrier_percentile_trigger_sell_black_wall_apply'];

        $percentile_trigger_black_wall_actual_value = $coin_meta_hourly_arr['blackwall_b_' . $percentile_trigger_black_wall];

        $black_wall_yes_no = true;

        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_black_wall_apply == 'yes') {
            if ($percentile_trigger_black_wall_actual_value >= $black_wall_pressure) {
                $black_wall_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $black_wall_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['black_wall_status'] = $rule_on_off;
        $log_arr['black_wall_recommended_percentile'] = $percentile_trigger_black_wall;
        $log_arr['black_wall_recommended_percentile_value'] = $percentile_trigger_black_wall_actual_value;
        $log_arr['black_wall_current_value'] = $black_wall_pressure . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_virtual_barrier = $global_setting_arr['barrier_percentile_trigger_sell_virtual_barrier'];
        $percentile_trigger_virtual_barrier_apply = $global_setting_arr['barrier_percentile_trigger_sell_virtual_barrier_apply'];
        $barrrier_actual_value = $coin_meta_hourly_arr['bid_quantity_b_' . $percentile_trigger_virtual_barrier];
		
        
        $total_ask_quantity = $coin_meta_arr['market_depth_ask'];
        //*********************************************************************/
        $virtual_barrier_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_virtual_barrier_apply == 'yes') {
            if ($barrrier_actual_value <= $total_ask_quantity) {
                $virtual_barrier_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $virtual_barrier_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['virtual_barrier_status'] = $rule_on_off;
        $log_arr['virtual_barrier_recommended_percentile'] = $percentile_trigger_virtual_barrier;
        $log_arr['virtual_barrier_recommended_percentile_value'] = $barrrier_actual_value;
        $log_arr['virtual_barrier_current_value'] = $total_ask_quantity . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_seven_level_pressure = $global_setting_arr['barrier_percentile_trigger_sell_seven_level_pressure'];
        $percentile_trigger_seven_level_pressure_apply = $global_setting_arr['barrier_percentile_trigger_sell_seven_level_pressure_apply'];

        $seven_level_pressure_actual_value = $coin_meta_hourly_arr['sevenlevel_b_' . $percentile_trigger_seven_level_pressure];

        $seven_level_pressure_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_seven_level_pressure_apply == 'yes') {
            if ($seven_level_pressure_actual_value >= $seven_level_depth) {
                $seven_level_pressure_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $seven_level_pressure_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['seven_level_pressure_status'] = $rule_on_off;
        $log_arr['seven_level_pressure_recommended_percentile'] = $percentile_trigger_seven_level_pressure;
        $log_arr['seven_level_pressure_recommended_percentile_value'] = $seven_level_pressure_actual_value;
        $log_arr['seven_level_pressure_current_value'] = $seven_level_depth . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $last_200_contracts_buy_vs_sell_percentile_trigger = $global_setting_arr['barrier_percentile_trigger_sell_last_200_contracts_buy_vs_sell'];
        $last_200_contracts_buy_vs_sell_percentile_trigger_apply = $global_setting_arr['barrier_percentile_trigger_sell_last_200_contracts_buy_vs_sell_apply'];

        $last_200_contracts_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($last_200_contracts_buy_vs_sell_percentile_trigger_apply == 'yes') {
            if ($last_200_buy_vs_sell <= $last_200_contracts_buy_vs_sell_percentile_trigger) {
                $last_200_contracts_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_200_contracts_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['last_200_buy_vs_sell_status'] = $rule_on_off;
        $log_arr['last_200_buy_vs_sell_recommended_value'] = $last_200_contracts_buy_vs_sell_percentile_trigger;
        $log_arr['last_200_buy_vs_sell_current_value'] = $last_200_buy_vs_sell . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $last_200_contracts_time_percentile_trigger = $global_setting_arr['barrier_percentile_trigger_sell_last_200_contracts_time'];
        $last_200_contracts_time_percentile_trigger_apply = $global_setting_arr['barrier_percentile_trigger_sell_last_200_contracts_time_apply'];

        $last_200_contracts_time_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($last_200_contracts_time_percentile_trigger_apply == 'yes') {
            if ($last_200_time_ago <= $last_200_contracts_time_percentile_trigger) {
                $last_200_contracts_time_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_200_contracts_time_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['last_200_time_ago_status'] = $rule_on_off;
        $log_arr['last_200_time_ago_recommended_value'] = $last_200_contracts_time_percentile_trigger;
        $log_arr['last_200_time_ago_current_value'] = $last_200_time_ago . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $last_qty_contracts_buyer_vs_seller_percentile_trigger = $global_setting_arr['barrier_percentile_trigger_sell_last_qty_contracts_buyer_vs_seller'];
        $last_qty_contracts_buyer_vs_seller_percentile_trigger_apply = $global_setting_arr['barrier_percentile_trigger_sell_last_qty_contracts_buyer_vs_seller_apply'];

        $last_200_contracts_qty_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($last_qty_contracts_buyer_vs_seller_percentile_trigger_apply == 'yes') {
            if ($last_qty_buy_vs_sell <= $last_qty_contracts_buyer_vs_seller_percentile_trigger) {
                $last_200_contracts_qty_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_200_contracts_qty_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['last_qty_buy_vs_sell_status'] = $rule_on_off;
        $log_arr['last_qty_buy_vs_sell_recommended_value'] = $last_qty_contracts_buyer_vs_seller_percentile_trigger;
        $log_arr['last_qty_buy_vs_sell_current_value'] = $last_qty_buy_vs_sell . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $last_qty_contracts_time_percentile_trigger = $global_setting_arr['barrier_percentile_trigger_sell_last_qty_contracts_time'];
        $last_qty_contracts_time_percentile_trigger_apply = $global_setting_arr['barrier_percentile_trigger_sell_last_qty_contracts_time_apply'];

        $last_qty_contracts_time_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($last_qty_contracts_time_percentile_trigger_apply == 'yes') {
            if ($last_qty_time_ago <= $last_qty_contracts_time_percentile_trigger) {
                $last_qty_contracts_time_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_qty_contracts_time_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['last_qty_contracts_time_status'] = $rule_on_off;
        $log_arr['last_qty_contracts_time_recommended_value'] = $last_qty_contracts_time_percentile_trigger;
        $log_arr['last_qty_contracts_time_current_value'] = $last_qty_time_ago . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $log_msg = '';
        foreach ($log_arr as $key => $value) {
            $log_msg .= $key . '=>' . $value . '<br>';
        }
        $is_rules_true = 'NO';
        if ($last_200_contracts_qty_yes_no && $last_200_contracts_time_yes_no && $last_200_contracts_yes_no && $black_wall_yes_no && $virtual_barrier_yes_no && $seven_level_pressure_yes_no && $last_qty_contracts_time_yes_no && $fifteen_minute_rolling_candel_yes_no && $five_minute_rolling_candel_yes_no && $buyers_yes_no && $sellers_yes_no) {
            $is_rules_true = 'YES';
        }

        $response['success_message'] = $is_rules_true;
        $response['log_message'] = $log_msg;
        return $response;
    } //End of is_triggers_qualify_to_sell_orders


    public function is_triggers_qualify_to_update_stop_loss($coin_symbol, $order_level,$global_setting_arr,$current_market_price,$coin_meta_arr,$coin_meta_hourly_arr,$simulator_date) {

        $black_wall_pressure = $coin_meta_arr['black_wall_pressure'];
        $seven_level_depth = $coin_meta_arr['seven_level_depth'];

        $five_minute_rolling_candel = $coin_meta_arr['sellers_buyers_per'];
        $fifteen_minute_rolling_candel = $coin_meta_arr['sellers_buyers_per_fifteen'];

        $last_200_buy_vs_sell = $coin_meta_arr['last_200_buy_vs_sell'];
        $last_200_time_ago = (float) $coin_meta_arr['last_200_time_ago'];
        $last_qty_buy_vs_sell = $coin_meta_arr['last_qty_buy_vs_sell'];
        $last_qty_time_ago = (float) $coin_meta_arr['last_qty_time_ago'];

       
        

        $buyers_fifteen = $coin_meta_arr['buyers_fifteen'];
        $sellers_fifteen = $coin_meta_arr['sellers_fifteen'];


        $enable_sell_barrier_percentile = $global_setting_arr['enable_percentile_trigger_stop_loss'];
        
        $log_arr['-'] = '<span style="color: green;font-size: 27px;">Stoploss Rules</span><br>';


        if ($enable_sell_barrier_percentile == 'not' || $enable_sell_barrier_percentile == '') {
            $log_arr['Stop Loss By Level No '.$order_level] = '<span style="color:red">OFF</span>';
            return $log_arr;
        }

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Buyers Done  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $barrier_percentile_trigger_buyers_buy = $global_setting_arr['barrier_percentile_trigger_buyers_stop_loss'];
        $barrier_percentile_trigger_buyers_buy_apply = $global_setting_arr['barrier_percentile_trigger_buyers_stop_loss_apply'];
        
        $buyers_recommended_percentile_value = $coin_meta_hourly_arr['buyers_fifteen_b_' . $barrier_percentile_trigger_buyers_buy];

        $buyers_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($barrier_percentile_trigger_buyers_buy_apply == 'yes') {
            if ($buyers_fifteen <= $buyers_recommended_percentile_value) {
                $buyers_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $buyers_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['Buyers_status'] = $rule_on_off;
        $log_arr['Buyers_recommended_percentile'] = $barrier_percentile_trigger_buyers_buy;
        $log_arr['Buyers_recommended_percentile_value'] = $buyers_recommended_percentile_value;
        $log_arr['Buyer_current_value'] = $buyers_fifteen . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%



         //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Buyers 1 minute Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

         $barrier_percentile_trigger_buyers_buy = $global_setting_arr['barrier_percentile_trigger_buyers1_minute_stop_loss'];
         $barrier_percentile_trigger_buyers_buy_apply = $global_setting_arr['barrier_percentile_trigger_buyers1_minute_stop_loss_apply'];
         
         $buyers_recommended_percentile_value = $coin_meta_hourly_arr['ask_b_' . $barrier_percentile_trigger_buyers_buy];
 
         $buyers_yes_no = true;
         $rule_on_off = '<span style="background-color:yellow">OFF</span>';
         if ($barrier_percentile_trigger_buyers_buy_apply == 'yes') {
             if ($buyers_fifteen <= $buyers_recommended_percentile_value) {
                 $buyers_yes_no = true;
                 $rule_on_off = '<span style="color:green">YES</span>';
             } else {
                 $buyers_yes_no = false;
                 $rule_on_off = '<span style="color:red">NO</span>';
             }
         }
 
         $log_arr['Buyers_1_minute_status'] = $rule_on_off;
         $log_arr['Buyers_1_minute_recommended_percentile'] = $barrier_percentile_trigger_buyers_buy;
         $log_arr['Buyers_1_minute_recommended_percentile_value'] = $buyers_recommended_percentile_value;
         $log_arr['Buyer_1_minute_current_value'] = $buyers_fifteen . '<br>';
 
         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%



        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Sellers   Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $seller_percentile = $global_setting_arr['barrier_percentile_trigger_sellers_stop_loss'];
        $barrier_percentile_trigger_sellers_buy_apply = $global_setting_arr['barrier_percentile_trigger_sellers_stop_loss_apply'];

        $sellers_recommended_percentile_value = $coin_meta_hourly_arr['sellers_fifteen_' . $seller_percentile];

        $sellers_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($barrier_percentile_trigger_sellers_buy_apply == 'yes') {
            if ($sellers_fifteen >= $sellers_recommended_percentile_value) {
                $sellers_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $sellers_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['Sellers_status'] = $rule_on_off;
        $log_arr['Sellers_recommended_percentile'] = $seller_percentile;
        $log_arr['Sellers_recommended_percentile_value'] = $sellers_recommended_percentile_value;
        $log_arr['Sellers_current_value'] = $sellers_fifteen . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%




        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Sellers  1 minute  Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $seller_percentile = $global_setting_arr['barrier_percentile_trigger_sellers_1_minute_stop_loss'];
        $barrier_percentile_trigger_sellers_buy_apply = $global_setting_arr['barrier_percentile_trigger_sellers_1_minute_stop_loss_apply'];

        $sellers_recommended_percentile_value = $coin_meta_hourly_arr['bid_' . $seller_percentile];

        $sellers_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($barrier_percentile_trigger_sellers_buy_apply == 'yes') {
            if ($sellers_fifteen >= $sellers_recommended_percentile_value) {
                $sellers_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $sellers_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['Sellers_1_minute_status'] = $rule_on_off;
        $log_arr['Sellers_1_minute_recommended_percentile'] = $seller_percentile;
        $log_arr['Sellers_1_minute_recommended_percentile_value'] = $sellers_recommended_percentile_value;
        $log_arr['Sellers_1_minute_current_value'] = $sellers_fifteen . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_15_minute_rolling_candel = $global_setting_arr['barrier_percentile_stop_loss_15_minute_rolling_candel_sell'];
        $percentile_trigger_15_minute_rolling_candel_apply = $global_setting_arr['barrier_percentile_stop_loss_15_minute_rolling_candel_sell_apply'];

        $percentile_trigger_15_minute_rolling_candel_actual_value = $coin_meta_hourly_arr['fifteen_min_b_' . $percentile_trigger_15_minute_rolling_candel];

        $fifteen_minute_rolling_candel_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_15_minute_rolling_candel_apply == 'yes') {
            if ($percentile_trigger_15_minute_rolling_candel_actual_value >= $fifteen_minute_rolling_candel) {
                $fifteen_minute_rolling_candel_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $fifteen_minute_rolling_candel_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['15_minute_rolling_candel_status'] = $rule_on_off;
        $log_arr['15_minute_rolling_candel_recommended_percentile'] = $percentile_trigger_15_minute_rolling_candel;
        $log_arr['15_minute_rolling_candel_recommended_percentile_value'] = $percentile_trigger_15_minute_rolling_candel_actual_value;
        $log_arr['15_minute_rolling_candel_current_value'] = $fifteen_minute_rolling_candel . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_5_minute_rolling_candel = $global_setting_arr['barrier_percentile_stop_loss_5_minute_rolling_candel_sell'];

        
        $percentile_trigger_5_minute_rolling_candel_apply = $global_setting_arr['barrier_percentile_stop_loss_5_minute_rolling_candel_sell_apply'];



        $percentile_trigger_5_minute_rolling_candel_actual_value = $coin_meta_hourly_arr['five_min_b_' . $percentile_trigger_5_minute_rolling_candel];



        $five_minute_rolling_candel_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_5_minute_rolling_candel_apply == 'yes') {
            if ($percentile_trigger_5_minute_rolling_candel_actual_value >= $five_minute_rolling_candel) {
                $five_minute_rolling_candel_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $five_minute_rolling_candel_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['5_minute_rolling_candel_status'] = $rule_on_off;
        $log_arr['5_minute_rolling_candel_recommended_percentile'] = $percentile_trigger_5_minute_rolling_candel;
        $log_arr['5_minute_rolling_candel_recommended_percentile_value'] = $percentile_trigger_5_minute_rolling_candel_actual_value;
        $log_arr['5_minute_rolling_candel_current_value'] = $five_minute_rolling_candel . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_black_wall = $global_setting_arr['barrier_percentile_stop_loss_black_wall'];
        $percentile_trigger_black_wall_apply = $global_setting_arr['barrier_percentile_stop_loss_black_wall_apply'];

        $percentile_trigger_black_wall_actual_value = $coin_meta_hourly_arr['blackwall_b_' . $percentile_trigger_black_wall];

        $black_wall_yes_no = true;

        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_black_wall_apply == 'yes') {
            if ($percentile_trigger_black_wall_actual_value >= $black_wall_pressure) {
                $black_wall_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $black_wall_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['black_wall_status'] = $rule_on_off;
        $log_arr['black_wall_recommended_percentile'] = $percentile_trigger_black_wall;
        $log_arr['black_wall_recommended_percentile_value'] = $percentile_trigger_black_wall_actual_value;
        $log_arr['black_wall_current_value'] = $black_wall_pressure . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_virtual_barrier = $global_setting_arr['barrier_percentile_trigger_stop_loss_virtual_barrier'];
        $percentile_trigger_virtual_barrier_apply = $global_setting_arr['barrier_percentile_trigger_stop_loss_virtual_barrier_apply'];
        $barrrier_actual_value = $coin_meta_hourly_arr['bid_quantity_' . $percentile_trigger_virtual_barrier];
		
        //%%%%%%%%%%%%%%%% -- Coin Unit Detail --%%%%%%%%%%%%%%%%%%%%%
     
		


        $total_ask_quantity = $coin_meta_arr['market_depth_ask'];

        //*********************************************************************/
        $virtual_barrier_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_virtual_barrier_apply == 'yes') {
            if ($barrrier_actual_value <= $total_ask_quantity) {
                $virtual_barrier_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $virtual_barrier_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['Ask_virtual_barrier_status'] = $rule_on_off;
        $log_arr['Ask_virtual_barrier_recommended_percentile'] = $percentile_trigger_virtual_barrier;
        $log_arr['Ask_virtual_barrier_recommended_percentile_value'] = $barrrier_actual_value;
        $log_arr['Ask_virtual_barrier_current_value'] = $total_ask_quantity . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


        //%%%%%%%%%%%%%%%%%%%%%%%%%% Bid Part %%%%%%%%%%%%%%%%%%%%%%%%%%

        $percentile_trigger_virtual_barrier = $global_setting_arr['barrier_percentile_trigger_stop_loss_virtual_barrier_bid'];
        $percentile_trigger_virtual_barrier_apply = $global_setting_arr['barrier_percentile_trigger_stop_loss_virtual_barrier_bid_apply'];
        $barrrier_actual_value = $coin_meta_hourly_arr['bid_quantity_b_' . $percentile_trigger_virtual_barrier];

  
        
        $total_bid_quantity = $coin_meta_arr['market_depth_quantity'];
        //*********************************************************************/
        $virtual_barrier_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_virtual_barrier_apply == 'yes') {
            if ($barrrier_actual_value >= $total_bid_quantity) {
                $virtual_barrier_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $virtual_barrier_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['Bid_virtual_barrier_status'] = $rule_on_off;
        $log_arr['Bid_virtual_barrier_recommended_percentile'] = $percentile_trigger_virtual_barrier;
        $log_arr['Bid_virtual_barrier_recommended_percentile_value'] = $barrrier_actual_value;
        $log_arr['Bid_virtual_barrier_current_value'] = $total_bid_quantity . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%% End of bit part %%%%%%%%%%%%%%%%%%%



        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Done  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_seven_level_pressure = $global_setting_arr['barrier_percentile_trigger_stop_loss_seven_level_pressure'];
        $percentile_trigger_seven_level_pressure_apply = $global_setting_arr['barrier_percentile_trigger_stop_loss_seven_level_pressure_apply'];

        $seven_level_pressure_actual_value = $coin_meta_hourly_arr['sevenlevel_b_' . $percentile_trigger_seven_level_pressure];

        $seven_level_pressure_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_seven_level_pressure_apply == 'yes') {
            if ($seven_level_pressure_actual_value >= $seven_level_depth) {
                $seven_level_pressure_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $seven_level_pressure_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['seven_level_pressure_status'] = $rule_on_off;
        $log_arr['seven_level_pressure_recommended_percentile'] = $percentile_trigger_seven_level_pressure;
        $log_arr['seven_level_pressure_recommended_percentile_value'] = $seven_level_pressure_actual_value;
        $log_arr['seven_level_pressure_current_value'] = $seven_level_depth . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%  Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $last_200_contracts_buy_vs_sell_percentile_trigger = $global_setting_arr['barrier_percentile_trigger_stop_loss_last_200_contracts_buy_vs_sell'];
        $last_200_contracts_buy_vs_sell_percentile_trigger_apply = $global_setting_arr['barrier_percentile_trigger_stop_loss_last_200_contracts_buy_vs_sell_apply'];

        $last_200_contracts_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($last_200_contracts_buy_vs_sell_percentile_trigger_apply == 'yes') {
            if ($last_200_buy_vs_sell <= $last_200_contracts_buy_vs_sell_percentile_trigger) {
                $last_200_contracts_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_200_contracts_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['last_200_buy_vs_sell_status'] = $rule_on_off;
        $log_arr['last_200_buy_vs_sell_recommended_value'] = $last_200_contracts_buy_vs_sell_percentile_trigger;
        $log_arr['last_200_buy_vs_sell_current_value'] = $last_200_buy_vs_sell . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $last_200_contracts_time_percentile_trigger = $global_setting_arr['barrier_percentile_trigger_stop_loss_last_200_contracts_time'];
        $last_200_contracts_time_percentile_trigger_apply = $global_setting_arr['barrier_percentile_trigger_stop_loss_last_200_contracts_time_apply'];

        $last_200_contracts_time_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($last_200_contracts_time_percentile_trigger_apply == 'yes') {
            if ($last_200_time_ago <= $last_200_contracts_time_percentile_trigger) {
                $last_200_contracts_time_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_200_contracts_time_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['last_200_time_ago_status'] = $rule_on_off;
        $log_arr['last_200_time_ago_recommended_value'] = $last_200_contracts_time_percentile_trigger;
        $log_arr['last_200_time_ago_current_value'] = $last_200_time_ago . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $last_qty_contracts_buyer_vs_seller_percentile_trigger = $global_setting_arr['barrier_percentile_trigger_stop_loss_last_qty_contracts_buyer_vs_seller'];
        $last_qty_contracts_buyer_vs_seller_percentile_trigger_apply = $global_setting_arr['barrier_percentile_trigger_stop_loss_last_qty_contracts_buyer_vs_seller_apply'];

        $last_200_contracts_qty_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($last_qty_contracts_buyer_vs_seller_percentile_trigger_apply == 'yes') {
            if ($last_qty_buy_vs_sell <= $last_qty_contracts_buyer_vs_seller_percentile_trigger) {
                $last_200_contracts_qty_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_200_contracts_qty_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['last_qty_buy_vs_sell_status'] = $rule_on_off;
        $log_arr['last_qty_buy_vs_sell_recommended_value'] = $last_qty_contracts_buyer_vs_seller_percentile_trigger;
        $log_arr['last_qty_buy_vs_sell_current_value'] = $last_qty_buy_vs_sell . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%  Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $last_qty_contracts_time_percentile_trigger = $global_setting_arr['barrier_percentile_trigger_stop_loss_last_qty_contracts_time'];
        $last_qty_contracts_time_percentile_trigger_apply = $global_setting_arr['barrier_percentile_trigger_stop_loss_last_qty_contracts_time_apply'];

        $last_qty_contracts_time_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($last_qty_contracts_time_percentile_trigger_apply == 'yes') {
            if ($last_qty_time_ago <= $last_qty_contracts_time_percentile_trigger) {
                $last_qty_contracts_time_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_qty_contracts_time_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['last_qty_contracts_time_status'] = $rule_on_off;
        $log_arr['last_qty_contracts_time_recommended_value'] = $last_qty_contracts_time_percentile_trigger;
        $log_arr['last_qty_contracts_time_current_value'] = $last_qty_time_ago . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $log_msg = '';
        foreach ($log_arr as $key => $value) {
            $log_msg .= $key . '=>' . $value . '<br>';
        }
        $is_rules_true = 'NO';
        if ($last_200_contracts_qty_yes_no && $last_200_contracts_time_yes_no && $last_200_contracts_yes_no && $black_wall_yes_no && $virtual_barrier_yes_no && $seven_level_pressure_yes_no && $last_qty_contracts_time_yes_no && $fifteen_minute_rolling_candel_yes_no && $five_minute_rolling_candel_yes_no && $buyers_yes_no && $sellers_yes_no) {
            $is_rules_true = 'YES';
        }

        $response['success_message'] = $is_rules_true;
        $response['log_message'] = $log_msg;


        return $response;
    } //End of is_triggers_qualify_to_update_stop_loss

    

} //End of mod_TEST_Barrier_trigger
?>