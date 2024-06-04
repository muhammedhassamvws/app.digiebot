<?php
class mod_barrier_trigger_test extends CI_Model {

	function __construct() {
		# code...
	}

    public function is_triggers_qualify_to_buy_orders($coin_symbol, $rule_number,$global_setting_arr,$current_market_price,$coin_meta_arr,$coin_meta_hourly_arr,$one_m_rolling_volume) {
	

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
		
		$enable_disable_rule = $global_setting_arr['enable_buy_rule_no_'.$rule_number];

        if ($enable_disable_rule == 'not' || $enable_disable_rule == '') {
            $log_arr['Rule NO # '.$rule_number] = '<span style="color:red">OFF</span>';
            return $log_arr;
        }//End of 


        $log_arr['-'] = '<span style="color: green;font-size: 27px;">Buy Rules</span><br>';
		$log_arr['Order_Is_Buyed_By_Rule'] = $rule_number.'<br>';
		
		//%%%%%%%%%%%%%%%%%%% -- ORDER lEVEL lOG -- %%%%%%%%%%%%%%%%%%%%%%

		$buy_order_level_on_off = $global_setting_arr['buy_order_level_' . $rule_number . '_enable'];
		$recommended_order_level = (array) $global_setting_arr['buy_order_level_' . $rule_number];

    
		 //%%%%%%%%%%%%%%%%% Recommended  buy levels%%%%%%%%%%%%%%%%%%%%%%%%%
		 if ($buy_order_level_on_off == 'not' || $buy_order_level_on_off == '') {
			 $log_arr['order_level_status'] = '<span style="color:red">OFF</span>';
			 $data['recommended_order_level_on_off'] = 'OFF';
		 } else {
			 //%%%%%%%%%%%%%%%%%%%%%%% On %%%%%%%%%%%%%%%%%%%%%%%%%
			 $log_arr['order_level_status'] = '<span style="color:green">ON</span>';
			 $log_arr['recommended_order_level'] = implode(',',$recommended_order_level); 
		 }
		 //%%%%%%%%%%%%%%%%% Recommended  buy levels%%%%%%%%%%%%%%%%%%%%%%%%%

		 $enable_disable_rule = $global_setting_arr['buy_status_rule_' . $rule_number . '_enable'];
		 $recommended_value = (array) $global_setting_arr['buy_status_rule_' . $rule_number];


        $is_swing_status = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($enable_disable_rule == 'yes') {

            $swing_status = $this->triggers_trades->prededding_candle_status($coin_symbol,$recommended_value);
            if ($swing_status) {
                $is_swing_status = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_swing_status = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['precedding_swing_status'] = $rule_on_off;
        $log_arr['swing_status_recommended_value'] = implode($recommended_value,'--'). '<br>'; 
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

 

         //%%%%%%%%%%%%%%%%%%% -- Barrier Status --%%%%%%%%%%%%%%%%%%%%%%%

        $last_barrrier_value = $this->triggers_trades->list_barrier_status($coin_symbol,'very_strong_barrier',$current_market_price,'down');

        $barrier_range_percentage = $global_setting_arr['buy_range_percet'];
        $barrier_value_range_upside = $last_barrrier_value + ($last_barrrier_value / 100) * $barrier_range_percentage;

        $high_value_range =  $current_market_price + ($current_market_price / 100) * $barrier_range_percentage;

        $barrier_value_range_down_side = $last_barrrier_value - ($last_barrrier_value / 100) * $barrier_range_percentage;

        $low_value_range =  $current_market_price - ($current_market_price / 100) * $barrier_range_percentage;

        $meet_condition_for_buy = true;
        

        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if($barrier_range_percentage !='' || $barrier_range_percentage !=0){
            if ((num($current_market_price) >= num($barrier_value_range_down_side)) && (num($current_market_price) <= num($barrier_value_range_upside))) {
                $meet_condition_for_buy = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            }else{
                $meet_condition_for_buy = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        
        $log_arr['is_Barrier_Meet'] = $rule_on_off;
        $log_arr['Last_Barrier_price'] = num($last_barrrier_value);
        $log_arr['Barrier_Range_percentage'] = $barrier_range_percentage;
        $log_arr['Barrier_Range'] = 'Barrir From <b>(' . num($barrier_value_range_down_side) . ')</b> To  <b>(' . num($barrier_value_range_upside) . ')</b>';
        $log_arr['check_equation_range'] = ' <b>(Current Market Price Between price Range up and down side of last strong barrier)</b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Buyers  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


         //%%%%%%%%%%%%%%%%%%%%%%%%%% Bid Part %%%%%%%%%%%%%%%%%%%%%%%%%%

         $recommended_volume = $global_setting_arr['buy_volume_rule_'.$rule_number];
         $percentile_trigger_virtual_barrier_apply = $global_setting_arr['buy_check_volume_rule_'.$rule_number];

        //%%%%%%%%%%%%%%%% -- Coin Unit Detail --%%%%%%%%%%%%%%%%%%%%%
        $coin_detail = $this->triggers_trades->get_coin_detail($coin_symbol);
        $coin_offset_value = $coin_detail['offset_value'];
        $coin_unit_value = $coin_detail['unit_value'];

        $upside_prices_array = array();
        $i = 0; 
        do {
            $new_last_barrrier_value =(float) trim($current_market_price + ($coin_unit_value * $i));
            array_push($upside_prices_array,$new_last_barrrier_value);
        $i++;
        } while ($new_last_barrrier_value <= $high_value_range);


        $downside_prices_array = array();
        $i = 0; 
        do {
            $new_last_barrrier_value =(float) trim($current_market_price - ($coin_unit_value * $i));
            array_push($downside_prices_array,$new_last_barrrier_value);
        $i++;
        } while ($new_last_barrrier_value <= $low_value_range);
        
        $value_range_arr = array_merge($downside_prices_array,$upside_prices_array);
        $value_range_arr = array_unique($value_range_arr);

        $total_bid_quantity = 0;
        foreach ($value_range_arr as $price) {
            $bid = $this->triggers_trades->list_market_volume((float)$price, $coin_symbol,'bid');
            $total_bid_quantity += $bid;
        }

         //*********************************************************************/
         $volume_yes_no = true;
         $rule_on_off = '<span style="background-color:yellow">OFF</span>';
         if ($percentile_trigger_virtual_barrier_apply == 'yes') {
             if ($total_bid_quantity >= $recommended_volume) {
                 $volume_yes_no = true;
                 $rule_on_off = '<span style="color:green">YES</span>';
             } else {
                 $volume_yes_no = false;
                 $rule_on_off = '<span style="color:red">NO</span>';
             }
         }
 
         $log_arr['volume_status'] = $rule_on_off;
         $log_arr['volume_recommended_value'] = $recommended_volume;
         $log_arr['volume_calculated_value'] = $total_bid_quantity;
         $log_arr['check_equation_volume'] = '<b> (total_bid_quantity >= recommended_volume)</b>' . '<br>';
         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
         



        //%%%%%%%%%%%%%%%%%%%%%%%%%% Bid Part %%%%%%%%%%%%%%%%%%%%%%%%%%

                                /** -- Buy form binance -- */
        $recommended_virtual_barrier = $global_setting_arr['buy_virtural_rule_'.$rule_number];
        $percentile_trigger_virtual_barrier_apply = $global_setting_arr['buy_virtual_barrier_rule_'.$rule_number.'_enable'];

        $total_bid_quantity = 0;
        for ($i = 0; $i < $coin_offset_value; $i++) {
            $new_last_barrrier_value =(float) trim($current_market_price - ($coin_unit_value * $i));
			$bid = $this->triggers_trades->list_market_volume($new_last_barrrier_value, $coin_symbol,'bid');
            $total_bid_quantity += $bid;
        } //End of Coin off Set
	
        //*********************************************************************/
        $virtual_barrier_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_virtual_barrier_apply == 'yes') {
            if ($total_bid_quantity >= $recommended_virtual_barrier) {
                $virtual_barrier_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $virtual_barrier_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['Bid_virtual_barrier_status'] = $rule_on_off;
        $log_arr['Bid_virtual_barrier_recommended_value'] = $recommended_virtual_barrier;
        $log_arr['Bid_virtual_barrier_current_value'] = $total_bid_quantity;
        $log_arr['check_equation_virtual'] = ' <b>(total_bid_quantity >= recommended_virtual_barrier)</b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%% -- End of Bid Part --  %%%%%%%%%%%%%%%%%%%%%%%%%%%




        //%%%%%%%%%%%%%%%%%%%%%%%%%% Ask Part %%%%%%%%%%%%%%%%%%%%%%%%%%
                        /** Sell form Binance  */
                       

        $recommended_virtual_barrier = $global_setting_arr['sell_virtural_for_buy_rule_'.$rule_number];
        $percentile_trigger_virtual_barrier_apply = $global_setting_arr['sell_virtural_for_buy_rule_'.$rule_number.'_enable'];
        

        $total_ask_quantity = 0;
        for ($i = 0; $i < $coin_offset_value; $i++) {
            $new_last_barrrier_value =(float) trim($current_market_price + ($coin_unit_value * $i));
			$ask = $this->triggers_trades->list_market_volume($new_last_barrrier_value, $coin_symbol,'ask');
            $total_ask_quantity += $ask;
        } //End of Coin off Set
	
        //*********************************************************************/
        $virtual_barrier_ask_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_virtual_barrier_apply == 'yes') {
            if ($total_ask_quantity <= $recommended_virtual_barrier) {
                $virtual_barrier_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $virtual_barrier_ask_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['ask_virtual_barrier_status'] = $rule_on_off;
        $log_arr['ask_virtual_barrier_recommended_value'] = $recommended_virtual_barrier;
        $log_arr['ask_virtual_barrier_current_value'] = $total_ask_quantity;
        $log_arr['check_equation_virtual'] = ' <b>(total_ask_quantity <= recommended_value)</b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%% -- End of Ask Part --  %%%%%%%%%%%%%%%%%%%%%%%%%%%

     

        //%%%%%%%%%%%%%%%%% --  Down pressure -- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $is_rule_on = $global_setting_arr['done_pressure_rule_' . $rule_number . '_buy_enable'];
        $recommended_pressure = $global_setting_arr['done_pressure_rule_' . $rule_number . '_buy'];
        $current_down_pressure = $coin_meta_arr['pressure_diff'];

        $down_pressure_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($current_down_pressure >= $recommended_pressure) {
                $down_pressure_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $down_pressure_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
    
         $log_arr['Down_pressue_status'] = $rule_on_off;
         $log_arr['Down_pressure_recommended_value'] = $recommended_pressure;
         $log_arr['Down_pressure_current_value'] = $current_down_pressure;
         $log_arr['check_equation_D_P'] = ' <b>(Down_pressure_current_value >= Down_pressure_recommended_value )</b>' . '<br>';

         //%%%%%%%%%%%%%%%%%%% -- End of down pressure --%%%%%%%%%%%%%%%%%%%%%%



         //%%%%%%%%%%%%%%%%% --  Big Seller -- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $is_rule_on = $global_setting_arr['big_seller_percent_compare_rule_' . $rule_number . '_buy_enable'];
        $recommended_big_sellers = $global_setting_arr['big_seller_percent_compare_rule_' . $rule_number . '_buy'];
        $current_big_sellers = $coin_meta_arr['ask_percentage'];

        $big_seller_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($current_big_sellers >= $recommended_big_sellers) {
                $big_seller_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $big_seller_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
    
         $log_arr['big_buyers_status'] = $rule_on_off;
         $log_arr['big_buyers_recommended_value'] = $recommended_big_sellers;
         $log_arr['big_buyers_current_value'] = $current_big_sellers;
         $log_arr['check_equation_B_S'] = '<b> (Bid percentage  >= recommended_big_buyers) </b>' . '<br>';

         //%%%%%%%%%%%%%%%%%%% -- End of down pressure --%%%%%%%%%%%%%%%%%%%%%%
    
    
 
		 //%%%%%%%%%%%%%%%%% check candle procedding status %%%%%%%%%%%%%%%%%%%
 
		 $is_rule_on = $global_setting_arr['buy_last_candle_status' . $rule_number . '_enable'];
         $recommende_candle_type = $global_setting_arr['last_candle_status' . $rule_number . '_buy']; 
         $current_type = $this->triggers_trades->last_procedding_candle_status($coin_symbol);
 
        $candle_type_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($recommende_candle_type == $current_type) {
                $candle_type_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $candle_type_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
    
         $log_arr['current_candle_status'] = $rule_on_off;
         $log_arr['recommended_candle_type'] = $recommende_candle_type;
         $log_arr['current_candle_type'] = $current_type;
         $log_arr['check_equation_C_T'] = '<b>(Last Hour Candle Type  == Recommended Candle Type)</b>' . '<br>';

		 //%%%%%%%%%%%%%%%% End of candle Procedding status %%%%%%%%%%%%%%%%%%%
        

         //%%%%%%%%%%%%%%%%%%% -- Black Wall --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%
         $is_rule_on = $global_setting_arr['closest_black_wall_rule_' . $rule_number . '_buy_enable'];
         $recommende_black_wall_pressure = $global_setting_arr['closest_black_wall_rule_' . $rule_number . '_buy']; 
         $current_black_wall_pressure = $coin_meta_arr['black_wall_pressure'];
 
        $black_wall_pressure_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($current_black_wall_pressure >= $recommende_black_wall_pressure) {
                $black_wall_pressure_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $black_wall_pressure_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
    
        $log_arr['black_wall_pressure_status'] = $rule_on_off;
        $log_arr['black_wall_pressure_recommended_value'] = $recommende_black_wall_pressure;
        $log_arr['black_wall_pressure_current_value'] = $current_black_wall_pressure;
        $log_arr['check_equation_B_W'] = ' <b>( current_black_wall_pressuree  >= Recommended value </b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End  Black Wall --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%
     
 
        
         //%%%%%%%%%%%%%%%%%%% -- Black Wall --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

         $is_rule_on = $global_setting_arr['closest_yellow_wall_rule_' . $rule_number . '_buy_enable'];
         $recommende_yellow_wall_pressure = $global_setting_arr['closest_yellow_wall_rule_' . $rule_number . '_buy']; 
         $current_yellow_wall_pressure = $coin_meta_arr['yellow_wall_pressure'];
 
        $yellow_wall_pressure_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($current_yellow_wall_pressure >= $recommende_yellow_wall_pressure) {
                $yellow_wall_pressure_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $yellow_wall_pressure_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
    
        $log_arr['yellow_wall_pressure_status'] = $rule_on_off;
        $log_arr['yellow_wall_pressure_recommended_value'] = $recommende_yellow_wall_pressure;
        $log_arr['yellow_wall_pressure_current_value'] = $current_yellow_wall_pressure;
        $log_arr['check_equation_Y_W'] = '<b>( current_yellow_wall_pressure  >= Recommended value)</b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End  Yellow Wall --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%



        //%%%%%%%%%%%%%%%%%%% -- Seven Leve Prressure --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

            $is_rule_on = $global_setting_arr['seven_level_pressure_rule_' . $rule_number . '_buy_enable'];
            $recommende_seven_level_pressure = $global_setting_arr['seven_level_pressure_rule_' . $rule_number . '_buy']; 
            $current_seven_level_pressure = $coin_meta_arr['seven_level_depth'];

            $seven_level_pressure_yes_no = true;
            $rule_on_off = '<span style="background-color:yellow">OFF</span>';
            if ($is_rule_on == 'yes') {
                if($current_seven_level_pressure >= $recommende_seven_level_pressure) {
                    $seven_level_pressure_yes_no = true;
                    $rule_on_off = '<span style="color:green">YES</span>';
                } else {
                    $seven_level_pressure_yes_no = false;
                    $rule_on_off = '<span style="color:red">NO</span>';
                }
            }

            $log_arr['seven_level_pressure_status'] = $rule_on_off;
            $log_arr['seven_level_pressure_recommended_value'] = $recommende_seven_level_pressure;
            $log_arr['seven_level_pressure_current_value'] = $current_seven_level_pressure;
            $log_arr['check_equation_S_P'] = '<b>( current_seven_level_pressure  >= Recommended value)</b> ' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End  Seven Leve Prressure --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        
        //%%%%%%%%%%%%%%%%%%% -- Buyers Vs Sellers --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

         $is_rule_on = $global_setting_arr['buyer_vs_seller_rule_' . $rule_number . '_buy_enable'];
         $recommended_buyers_vs_sellers = $global_setting_arr['buyer_vs_seller_rule_' . $rule_number . '_buy']; 
         $current_buyers_vs_sellers = $coin_meta_arr['sellers_buyers_per'];

         $sellers_buyers_per_yes_no = true;
         $rule_on_off = '<span style="background-color:yellow">OFF</span>';
         if ($is_rule_on == 'yes') {
             if($current_buyers_vs_sellers >= $recommended_buyers_vs_sellers) {
                 $sellers_buyers_per_yes_no = true;
                 $rule_on_off = '<span style="color:green">YES</span>';
             } else {
                 $sellers_buyers_per_yes_no = false;
                 $rule_on_off = '<span style="color:red">NO</span>';
             }
         }

         $log_arr['buyers_vs_sellers_status'] = $rule_on_off;
         $log_arr['buyers_vs_sellers_recommended_value'] = $recommended_buyers_vs_sellers;
         $log_arr['buyers_vs_sellers_current_value'] = $current_buyers_vs_sellers;
         $log_arr['check_equation_B_V_S'] = ' <b>(5 min buyers/sellers  >= Recommended value)</b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End   Buyers Vs Sellers --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%
 

            //%%%%%%%%%%%%%%%%%%% -- Rejection Candle --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%
            $is_rule_on = $global_setting_arr['buy_rejection_candle_type' . $rule_number . '_enable'];
            $recommended_rejection_candle = $global_setting_arr['rejection_candle_type' . $rule_number . '_buy']; 
            $last_candle_rejection_status = $coin_meta_arr['last_candle_rejection_status'];

            $rejection_candle_per_yes_no = true;
            $rule_on_off = '<span style="background-color:yellow">OFF</span>';
            if ($is_rule_on == 'yes') {
                if($last_candle_rejection_status == $recommended_rejection_candle) {
                    $rejection_candle_per_yes_no = true;
                    $rule_on_off = '<span style="color:green">YES</span>';
                } else {
                    $rejection_candle_per_yes_no = false;
                    $rule_on_off = '<span style="color:red">NO</span>';
                }
            }

            $log_arr['last_candle_rejection_status'] = $rule_on_off;
            $log_arr['last_candle_rejection_recommended_value'] = $recommended_rejection_candle;
            $log_arr['last_candle_rejection_current_value'] = $last_candle_rejection_status;
            $log_arr['check_equation_L_C_R'] = '<b> (last candle rejection status  == Recommended value)</b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End   Rejection Candle --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%



        //%%%%%%%%%%%%%%%%%%% -- Last 200 Contract Buyers Vs Sellere --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $is_rule_on = $global_setting_arr['buy_last_200_contracts_buy_vs_sell' . $rule_number . '_enable'];
        $recommended_last_200_buyer_vs_sellers = $global_setting_arr['last_200_contracts_buy_vs_sell' . $rule_number . '_buy']; 
        $current_last_200_buyer_vs_sellers = $coin_meta_arr['last_200_buy_vs_sell'];

        $last_200_buyer_vs_sellers_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($current_last_200_buyer_vs_sellers >= $recommended_last_200_buyer_vs_sellers) {
                $last_200_buyer_vs_sellers_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_200_buyer_vs_sellers_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['last_200_buyer_vs_sellers_status'] = $rule_on_off;
        $log_arr['last_200_buyer_vs_sellers_recommended_value'] = $recommended_last_200_buyer_vs_sellers;
        $log_arr['last_200_buyer_vs_sellers_current_value'] = $current_last_200_buyer_vs_sellers;
        $log_arr['check_equation_L_C_1'] = '<b>( current_last_200_buyer_vs_sellers  >= Recommended value) </b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End   Last 200 Contract Buyers Vs Sellere --  %%%%%%%%%%%%%%%%%%%%



        //%%%%%%%%%%%%%%%%%%% -- Last 200 Contract Time --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $is_rule_on = $global_setting_arr['buy_last_200_contracts_time' . $rule_number . '_enable'];
        $recommended_last_200_contracts_time = $global_setting_arr['last_200_contracts_time' . $rule_number . '_buy']; 
        $current_last_200_contracts_time = (float) $coin_meta_arr['last_200_time_ago'];
        
        $last_200_contracts_time_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($current_last_200_contracts_time <= $recommended_last_200_contracts_time) {
                $last_200_contracts_time_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_200_contracts_time_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['last_200_contracts_time_status'] = $rule_on_off;
        $log_arr['last_200_contracts_time_recommended_value'] = $recommended_last_200_contracts_time;
        $log_arr['last_200_contracts_time_current_value'] = $current_last_200_contracts_time;
        $log_arr['check_equation_L_T_A'] = '<b> (last_200_time_ago in minute  <= Recommended value) </b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End   Last 200 Contract Time --  %%%%%%%%%%%%%%%%%%%%



         //%%%%%%%%%%%%%%%%%%% -- Last Qty Buyers vs Seller --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

         $is_rule_on = $global_setting_arr['buy_last_qty_buyers_vs_seller'.$rule_number.'_enable'];
         $recommended_last_qty_buyers_vs_seller_value = $global_setting_arr['last_qty_buyers_vs_seller'.$rule_number.'_buy']; 

         $last_qty_buyers_vs_seller_current_value =  $coin_meta_arr['last_qty_buy_vs_sell'];
         
         $last_qty_buyers_vs_seller_yes_no = true;
         $rule_on_off = '<span style="background-color:yellow">OFF</span>';
         if ($is_rule_on == 'yes') {
             if($last_qty_buyers_vs_seller_current_value >= $recommended_last_qty_buyers_vs_seller_value) {
                 $last_qty_buyers_vs_seller_yes_no = true;
                 $rule_on_off = '<span style="color:green">YES</span>';
             } else {
                 $last_qty_buyers_vs_seller_yes_no = false;
                 $rule_on_off = '<span style="color:red">NO</span>';
             }
         }
 
         $log_arr['last_qty_buy_vs_sell_status'] = $rule_on_off;
         $log_arr['last_qty_buy_vs_sell_recommended_value'] = $recommended_last_qty_buyers_vs_seller_value;
         $log_arr['last_qty_buy_vs_sell_current_value'] = $last_qty_buyers_vs_seller_current_value;
         $log_arr['check_equation_L_Q_B_S'] = ' <b>(last_qty_buy_vs_sell >= Recommended value)</b>' . '<br>';
         //%%%%%%%%%%%%%%%%%%% -- End Last Qty Buyers vs Seller --  %%%%%%%%%%%%%%%%%%%%
 
         
        //%%%%%%%%%%%%%%%%%%% -- Last Qty Time --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%
         
          $is_rule_on = $global_setting_arr['buy_last_qty_time' . $rule_number . '_enable'];
          $recommended_last_qty_time_value = $global_setting_arr['last_qty_time' . $rule_number . '_buy']; 
          $last_qty_time_ago_current_value = (float) $coin_meta_arr['last_qty_time_ago'];
          
          $last_qty_time_ago_yes_no = true;
          $rule_on_off = '<span style="background-color:yellow">OFF</span>';
          if ($is_rule_on == 'yes') {
              if($last_qty_time_ago_current_value <= $recommended_last_qty_time_value) {
                  $last_qty_time_ago_yes_no = true;
                  $rule_on_off = '<span style="color:green">YES</span>';
              } else {
                  $last_qty_time_ago_yes_no = false;
                  $rule_on_off = '<span style="color:red">NO</span>';
              }
          }
  
          $log_arr['last_qty_time_status'] = $rule_on_off;
          $log_arr['last_qty_time_recommended_value'] = $recommended_last_qty_time_value;
          $log_arr['last_qty_time_current_value'] = $last_qty_time_ago_current_value;
          $log_arr['check_equation_L_q_t_ago'] = '<b> (last_qty_time_ago <= Recommended value) </b>' . '<br>';
          //%%%%%%%%%%%%%%%%%%% -- End Qty Time --  %%%%%%%%%%%%%%%%%%%%
    
          

          //%%%%%%%%%%%%%%%%%%% -- SCORE --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%
          
          $is_rule_on = $global_setting_arr['buy_score' . $rule_number . '_enable'];
          $recommended_score_value = $global_setting_arr['score' . $rule_number . '_buy']; 
          $score_current_value = (float) $coin_meta_arr['score'];
          
          $score_yes_no = true;
          $rule_on_off = '<span style="background-color:yellow">OFF</span>';
          if ($is_rule_on == 'yes') {
              if($score_current_value >= $recommended_score_value) {
                  $score_yes_no = true;
                  $rule_on_off = '<span style="color:green">YES</span>';
              } else {
                  $score_yes_no = false;
                  $rule_on_off = '<span style="color:red">NO</span>';
              }
          }
  
          $log_arr['score_status'] = $rule_on_off;
          $log_arr['score_recommended_value'] = $recommended_score_value;
          $log_arr['score_current_value'] = $score_current_value;
          $log_arr['check_equation_score'] = '<b> (current score >= Recommended value )</b>' . '<br>';

          //%%%%%%%%%%%%%%%%%%% -- End OF SCORE--  %%%%%%%%%%%%%%%%%%%%

          

          //%%%%%%%%%%%%%%%%%%% -- BUYERS VS SELLERS 15--  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%
       
          $is_rule_on = $global_setting_arr['buyers_vs_sellers_buy' . $rule_number . '_enable'];
          $recommended_buyers_vs_sellers_value = $global_setting_arr['buyers_vs_sellers' . $rule_number . '_buy']; 

          $buyers_vs_sellers_current_value = (float) $coin_meta_arr['sellers_buyers_per_fifteen'];
          
          $buyers_vs_sellers_yes_no = true;
          $rule_on_off = '<span style="background-color:yellow">OFF</span>';
          if ($is_rule_on == 'yes') {
              if($buyers_vs_sellers_current_value >= $recommended_buyers_vs_sellers_value) {
                  $buyers_vs_sellers_yes_no = true;
                  $rule_on_off = '<span style="color:green">YES</span>';
              } else {
                  $buyers_vs_sellers_yes_no = false;
                  $rule_on_off = '<span style="color:red">NO</span>';
              }
          }
  
          $log_arr['15_minute_buyers_vs_sellers_status'] = $rule_on_off;
          $log_arr['15_minute_buyers_vs_sellers_recommended_value'] = $recommended_buyers_vs_sellers_value;
          $log_arr['15_minute_buyers_vs_sellers_current_value'] = $buyers_vs_sellers_current_value;
          $log_arr['check_equation_b_vs_se'] = ' <b>(15 min buyers/sellers >= Recommended value )</b>' . '<br>';

          //%%%%%%%%%%%%%%%%%%% -- End OF BUYERS VS SELLERS 15--  %%%%%%%%%%%%%%%%%%%%
          

          $buy = $one_m_rolling_volume['buy'];
          $bid = $one_m_rolling_volume['bid'];
          $ask = $one_m_rolling_volume['ask'];
          $sell = $one_m_rolling_volume['sell'];

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Buy Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        
        $buy_percentile_apply = $global_setting_arr['buy_percentile_'.$rule_number.'_apply_buy'];
        $buy_percentile_recommended_value = $global_setting_arr['buy_percentile_'.$rule_number.'_buy'];
          

      

        $buy_percentile_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($buy_percentile_apply == 'yes') {
            if ($buy >= $buy_percentile_recommended_value) {
                $buy_percentile_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $buy_percentile_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['Digie_Buy_one_minute_rolling_status'] = $rule_on_off;
        $log_arr['Digie_Buy_one_minute_rolling_recommended_value'] = $buy_percentile_recommended_value;
  
        $log_arr['Digie_Buy_one_minute_rolling_current_value'] = $buy;
        $log_arr['check_equation_score_Digie_Buy'] = '<b>Current value Greater then recommended value</b> <br>';
      
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Ask Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

         $ask_percentile_apply = $global_setting_arr['ask_percentile_'.$rule_number.'_apply_buy'];
         $ask_percentile_recommended_value = $global_setting_arr['ask_percentile_'.$rule_number.'_buy'];
        

         $ask_percentile_yes_no = true;
         $rule_on_off = '<span style="background-color:yellow">OFF</span>';
         if ($ask_percentile_apply == 'yes') {
             if ($ask >= $ask_percentile_recommended_value) {
                 $ask_percentile_yes_no = true;
                 $rule_on_off = '<span style="color:green">YES</span>';
             } else {
                 $ask_percentile_yes_no = false;
                 $rule_on_off = '<span style="color:red">NO</span>';
             }
         }
         
         $log_arr['Binance_Buy_one_minute_rolling_status'] = $rule_on_off;
         $log_arr['Binance_Buy_one_minute_rolling_recommended_value'] = $ask_percentile_recommended_value;
         $log_arr['Binance_Buy_one_minute_rolling_current_value'] = $ask;
         $log_arr['check_equation_score_binance_buy'] = '<b>Current value Greater then recommended value</b> <br>';
         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%




         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Sell Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
         $sell_percentile_apply = $global_setting_arr['sell_percentile_'.$rule_number.'_apply_buy'];
         $sell_percentile_recommended_value = $global_setting_arr['sell_percentile_'.$rule_number.'_buy'];
         

         $sell_percentile_yes_no = true;
         $rule_on_off = '<span style="background-color:yellow">OFF</span>';
         if ($sell_percentile_apply == 'yes') {
             if ($sell <= $sell_percentile_recommended_value) {
                 $sell_percentile_yes_no = true;
                 $rule_on_off = '<span style="color:green">YES</span>';
             } else {
                 $sell_percentile_yes_no = false;
                 $rule_on_off = '<span style="color:red">NO</span>';
             }
         }
         
         $log_arr['Digie_Sell_one_minute_rolling_status'] = $rule_on_off;
         $log_arr['Digie_Sell_one_minute_rolling_recommended_value'] = $sell_percentile_recommended_value;
         $log_arr['Digie_Sell_one_minute_rolling_current_value'] = $sell;
         $log_arr['check_equation_score_digie_sell'] = '<b>Current value Less then recommended value</b> <br>';
         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%



          //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Bid Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
          $bid_percentile_apply = $global_setting_arr['bid_percentile_'.$rule_number.'_apply_buy'];
          $bid_percentile_recommended_value = $global_setting_arr['bid_percentile_'.$rule_number.'_buy'];

          
 
          $bid_percentile_yes_no = true;
          $rule_on_off = '<span style="background-color:yellow">OFF</span>';
          if ($bid_percentile_apply == 'yes') {
              if ($bid <= $bid_percentile_recommended_value) {
                  $bid_percentile_yes_no = true;
                  $rule_on_off = '<span style="color:green">YES</span>';
              } else {
                  $bid_percentile_yes_no = false;
                  $rule_on_off = '<span style="color:red">NO</span>';
              }
          }
          
          $log_arr['Binance_Sell_one_minute_rolling_status'] = $rule_on_off;
          $log_arr['Binance_Sell_one_minute_rolling_recommended_value'] = $bid_percentile_recommended_value;
          $log_arr['Binance_Sell_one_minute_rolling_current_value'] = $bid;
          $log_arr['check_equation_score_binance_sell'] = '<b>Current value Less then recommended value></b> <br>';
          //%%%%%%%%%%%%%%%%%%%%% End of Bid %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

         $response_message = 'NO';

       

       
         if($is_swing_status && $volume_yes_no && $meet_condition_for_buy && $virtual_barrier_yes_no && $big_seller_yes_no && $candle_type_yes_no && $black_wall_pressure_yes_no && $yellow_wall_pressure_yes_no && $seven_level_pressure_yes_no && $sellers_buyers_per_yes_no && $rejection_candle_per_yes_no && $last_200_buyer_vs_sellers_yes_no && $last_200_contracts_time_yes_no && $last_qty_buyers_vs_seller_yes_no && $last_qty_time_ago_yes_no &&  $score_yes_no && $buyers_vs_sellers_yes_no && $down_pressure_yes_no && $buy_percentile_yes_no && $ask_percentile_yes_no && $sell_percentile_yes_no && $bid_percentile_yes_no && $virtual_barrier_ask_yes_no){
            $response_message = 'YES';
         }

         $data['log_message'] = $log_arr;
		 $data['success_message'] = $response_message; 
		 return $data;
    } //End of is_triggers_qualify_to_buy_orders



    public function is_triggers_qualify_to_sell_orders($coin_symbol, $rule_number,$global_setting_arr,$current_market_price,$coin_meta_arr,$coin_meta_hourly_arr,$one_m_rolling_volume) {

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
		

        $enable_disable_rule = $global_setting_arr['enable_sell_rule_no_' . $rule_number];

        if ($enable_disable_rule == 'not' || $enable_disable_rule == '') {
            $log_arr['Rule NO # '.$rule_number] = '<span style="color:red">OFF</span>';
            return $log_arr;
        }//End of 


        $log_arr['-'] = '<span style="color: green;font-size: 27px;">Sell Rules</span><br>';
		$log_arr['Order_Is_sell_By_Rule'] = $rule_number.'<br>';
		
		//%%%%%%%%%%%%%%%%%%% -- ORDER lEVEL lOG -- %%%%%%%%%%%%%%%%%%%%%%

		$buy_order_level_on_off = $global_setting_arr['sell_order_level_' . $rule_number . '_enable'];
		$recommended_order_level = (array) $global_setting_arr['sell_order_level_' . $rule_number];


    
		 //%%%%%%%%%%%%%%%%% Recommended  buy levels%%%%%%%%%%%%%%%%%%%%%%%%%
		 if ($buy_order_level_on_off == 'not' || $buy_order_level_on_off == '') {
			 $log_arr['order_level_status'] = '<span style="color:red">OFF</span><br>';
		 } else {
			 //%%%%%%%%%%%%%%%%%%%%%%% On %%%%%%%%%%%%%%%%%%%%%%%%%
			 $log_arr['order_level_status'] = '<span style="color:green">ON</span>';
			 $log_arr['recommended_order_level'] = implode(',',$recommended_order_level).'<br>'; 
		 }
		 //%%%%%%%%%%%%%%%%% Recommended  buy levels%%%%%%%%%%%%%%%%%%%%%%%%%




        //%%%%%%%%%%%% -- Rule Status -- %%%%%%%%%%%%%%%

		 $enable_disable_rule = $global_setting_arr['sell_status_rule_' . $rule_number . '_enable'];
		 $recommended_value = (array) $global_setting_arr['sell_status_rule_' . $rule_number];


        $is_swing_status = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($enable_disable_rule == 'yes') {

            $swing_status = $this->triggers_trades->prededding_candle_status($coin_symbol,$recommended_value);
            if ($swing_status) {
                $is_swing_status = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_swing_status = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['precedding_swing_status'] = $rule_on_off;
        $log_arr['swing_status_recommended_value'] = implode($recommended_value,'--'). '<br>'; 
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

 

        //%%%%%%%%%%%%%%%%%%% -- Barrier Status --%%%%%%%%%%%%%%%%%%%%%%%
		$last_barrrier_value = $this->triggers_trades->list_barrier_status($coin_symbol,'very_strong_barrier',$current_market_price,'up');

        $barrier_range_percentage = $global_setting_arr['buy_range_percet'];
        $barrier_value_range_upside = $last_barrrier_value + ($last_barrrier_value / 100) * $barrier_range_percentage;

        $high_value_range =  $current_market_price + ($current_market_price / 100) * $barrier_range_percentage;

        $barrier_value_range_down_side = $last_barrrier_value - ($last_barrrier_value / 100) * $barrier_range_percentage;

        $low_value_range =  $current_market_price - ($current_market_price / 100) * $barrier_range_percentage;


        
         //%%%%%%%%%%%%%%%%%%%%%%%%%% Bid Part %%%%%%%%%%%%%%%%%%%%%%%%%%
         
         $recommended_volume = $global_setting_arr['sell_volume_rule_'.$rule_number];
         $percentile_trigger_virtual_barrier_apply = $global_setting_arr['sell_check_volume_rule_'.$rule_number];

        //%%%%%%%%%%%%%%%% -- Coin Unit Detail --%%%%%%%%%%%%%%%%%%%%%
        $coin_detail = $this->triggers_trades->get_coin_detail($coin_symbol);
        $coin_offset_value = $coin_detail['offset_value'];
        $coin_unit_value = $coin_detail['unit_value'];

        $upside_prices_array = array();
        $i = 0; 
        do {
            $new_last_barrrier_value =(float) trim($current_market_price + ($coin_unit_value * $i));
            array_push($upside_prices_array,$new_last_barrrier_value);
        $i++;
        } while ($new_last_barrrier_value <= $high_value_range);


        $downside_prices_array = array();
        $i = 0; 
        do {
            $new_last_barrrier_value =(float) trim($current_market_price - ($coin_unit_value * $i));
            array_push($downside_prices_array,$new_last_barrrier_value);
        $i++;
        } while ($new_last_barrrier_value <= $low_value_range);
        
        $value_range_arr = array_merge($downside_prices_array,$upside_prices_array);
        $value_range_arr = array_unique($value_range_arr);

        $total_bid_quantity = 0;
        foreach ($value_range_arr as $price) {
            $ask = $this->triggers_trades->list_market_volume((float)$price, $coin_symbol,'ask');
            $total_bid_quantity += $ask;
        }

         //*********************************************************************/
         $volume_yes_no = true;
         $rule_on_off = '<span style="background-color:yellow">OFF</span>';
         if ($percentile_trigger_virtual_barrier_apply == 'yes') {
             if ($total_bid_quantity >= $recommended_volume) {
                 $volume_yes_no = true;
                 $rule_on_off = '<span style="color:green">YES</span>';
             } else {
                 $volume_yes_no = false;
                 $rule_on_off = '<span style="color:red">NO</span>';
             }
         }
 
         $log_arr['volume_status'] = $rule_on_off;
         $log_arr['volume_recommended_value'] = $recommended_volume;
         $log_arr['volume_calculated_value'] = $total_bid_quantity;
         $log_arr['check_equation_volume'] = '<b> (total_bid_quantity >= recommended_volume)</b>' . '<br>';
         //%%%%%%%%%%%%%% -- End of volume  -- %%%%%%%%%%%%%%%%%%%%%%%%
         

        //%%%%%%%%%%%%%%%%%%%%%%%%%% Ask Part %%%%%%%%%%%%%%%%%%%%%%%%%%
    
        $recommended_virtual_barrier = $global_setting_arr['sell_virtural_rule_'.$rule_number];
        $percentile_trigger_virtual_barrier_apply = $global_setting_arr['sell_virtual_barrier_rule_'.$rule_number.'_enable'];
        

        $total_bid_quantity = 0;
        for ($i = 0; $i < $coin_offset_value; $i++) {
            $new_last_barrrier_value =(float) trim($current_market_price + ($coin_unit_value * $i));
			$ask = $this->triggers_trades->list_market_volume($new_last_barrrier_value, $coin_symbol,'ask');
            $total_ask_quantity += $ask;
        } //End of Coin off Set
	
        //*********************************************************************/
        $virtual_barrier_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_virtual_barrier_apply == 'yes') {
            if ($total_ask_quantity >= $recommended_virtual_barrier) {
                $virtual_barrier_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $virtual_barrier_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['ask_virtual_barrier_status'] = $rule_on_off;
        $log_arr['ask_virtual_barrier_recommended_value'] = $recommended_virtual_barrier;
        $log_arr['ask_virtual_barrier_current_value'] = $total_ask_quantity;
        $log_arr['check_equation_virtual'] = ' <b>(total_ask_quantity >= recommended_value)</b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%% -- End of Ask Part --  %%%%%%%%%%%%%%%%%%%%%%%%%%%


        

        //%%%%%%%%%%%%%%%%%%%%%%%%%% Start of  Bid Part %%%%%%%%%%%%%%%%%%%%%%%%%%
                                    /** Buy form binance  */
        $recommended_virtual_barrier = $global_setting_arr['buy_virtural_rule_for_sell_'.$rule_number];
        $percentile_trigger_virtual_barrier_apply = $global_setting_arr['buy_virtural_rule_for_sell_'.$rule_number.'_enable'];


        $total_bid_quantity = 0;
        for ($i = 0; $i < $coin_offset_value; $i++) {
            $new_last_barrrier_value =(float) trim($current_market_price - ($coin_unit_value * $i));
			$bid = $this->triggers_trades->list_market_volume($new_last_barrrier_value, $coin_symbol,'bid');
            $total_bid_quantity += $bid;
        } //End of Coin off Set
	
        //*********************************************************************/
        $virtual_barrier_bid_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_virtual_barrier_apply == 'yes') {
            if ($total_bid_quantity <= $recommended_virtual_barrier) {
                $virtual_barrier_bid_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $virtual_barrier_bid_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['Bid_virtual_barrier_status'] = $rule_on_off;
        $log_arr['Bid_virtual_barrier_recommended_value'] = $recommended_virtual_barrier;
        $log_arr['Bid_virtual_barrier_current_value'] = $total_bid_quantity;
        $log_arr['check_equation_virtual'] = ' <b>(total_bid_quantity <= recommended_virtual_barrier)</b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%% -- End of Bid Part --  %%%%%%%%%%%%%%%%%%%%%%%%%%%

        



        //%%%%%%%%%%%%%%%%% --  Down pressure -- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
 
        $is_rule_on = $global_setting_arr['done_pressure_rule_' . $rule_number . '_enable'];
        $recommended_pressure = $global_setting_arr['done_pressure_rule_' . $rule_number];
        $current_down_pressure = $coin_meta_arr['pressure_diff'];

        $down_pressure_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($current_down_pressure <= $recommended_pressure) {
                $down_pressure_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $down_pressure_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
    
         $log_arr['Down_pressue_status'] = $rule_on_off;
         $log_arr['Down_pressure_recommended_value'] = $recommended_pressure;
         $log_arr['Down_pressure_current_value'] = $current_down_pressure;
         $log_arr['check_equation_D_P'] = ' <b>(Down_pressure_current_value <= Down_pressure_recommended_value )</b>' . '<br>';

         //%%%%%%%%%%%%%%%%%%% -- End of down pressure --%%%%%%%%%%%%%%%%%%%%%%


        //%%%%%%%%%%%%%%%%% --  Big Seller -- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%    

        $is_rule_on = $global_setting_arr['big_seller_percent_compare_rule_' . $rule_number . '_enable'];
        $recommended_big_sellers = $global_setting_arr['big_seller_percent_compare_rule_' . $rule_number];
        $current_big_sellers = $coin_meta_arr['bid_percentage'];

        $big_seller_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($current_big_sellers >= $recommended_big_sellers) {
                $big_seller_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $big_seller_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
    
         $log_arr['big_sellerstatus'] = $rule_on_off;
         $log_arr['big_seller_recommended_value'] = $recommended_big_sellers;
         $log_arr['big_seller_current_value'] = $current_big_sellers;
         $log_arr['check_equation_B_S'] = '<b> (Ask percentage  >= recommended_big_sellers) </b>' . '<br>';

         //%%%%%%%%%%%%%%%%%%% -- End of down pressure --%%%%%%%%%%%%%%%%%%%%%%
    
     
 
		 //%%%%%%%%%%%%%%%%% check candle procedding status %%%%%%%%%%%%%%%%%%%
        
		 $is_rule_on = $global_setting_arr['sell_last_candle_type'.$rule_number.'_enable'];
         $recommende_candle_type = $global_setting_arr['last_candle_type'.$rule_number.'_sell']; 
         $current_type = $this->triggers_trades->last_procedding_candle_status($coin_symbol);
 
        $candle_type_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($recommende_candle_type == $current_type) {
                $candle_type_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $candle_type_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
    
         $log_arr['current_candle_status'] = $rule_on_off;
         $log_arr['recommended_candle_type'] = $recommende_candle_type;
         $log_arr['current_candle_type'] = $current_type;
         $log_arr['check_equation_C_T'] = '<b>(Last Hour Candle Type  == Recommended Candle Type)</b>' . '<br>';

		 //%%%%%%%%%%%%%%%% End of candle Procedding status %%%%%%%%%%%%%%%%%%%
        
       

         //%%%%%%%%%%%%%%%%%%% -- Black Wall --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

         $is_rule_on = $global_setting_arr['closest_black_wall_rule_' . $rule_number . '_enable'];
         $recommende_black_wall_pressure = $global_setting_arr['closest_black_wall_rule_' . $rule_number]; 
         $current_black_wall_pressure = $coin_meta_arr['black_wall_pressure'];
 
        $black_wall_pressure_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($current_black_wall_pressure <= $recommende_black_wall_pressure) {
                $black_wall_pressure_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $black_wall_pressure_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
    
        $log_arr['black_wall_pressure_status'] = $rule_on_off;
        $log_arr['black_wall_pressure_recommended_value'] = $recommende_black_wall_pressure;
        $log_arr['black_wall_pressure_current_value'] = $current_black_wall_pressure;
        $log_arr['check_equation_B_W'] = ' <b>( current_black_wall_pressuree  <= Recommended value </b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End  Black Wall --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%
     
       
        
         //%%%%%%%%%%%%%%%%%%% -- Black Wall --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

         $rule_on_off = 'closest_yellow_wall_rule_' . $rule_number . '_enable';
         $rule_name = 'closest_yellow_wall_rule_' . $rule_number;



         $is_rule_on = $global_setting_arr['closest_yellow_wall_rule_' . $rule_number . '_enable'];
         $recommende_yellow_wall_pressure = $global_setting_arr['closest_yellow_wall_rule_' . $rule_number]; 
         $current_yellow_wall_pressure = $coin_meta_arr['yellow_wall_pressure'];
 
        $yellow_wall_pressure_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($current_yellow_wall_pressure <= $recommende_yellow_wall_pressure) {
                $yellow_wall_pressure_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $yellow_wall_pressure_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
    
        $log_arr['yellow_wall_pressure_status'] = $rule_on_off;
        $log_arr['yellow_wall_pressure_recommended_value'] = $recommende_yellow_wall_pressure;
        $log_arr['yellow_wall_pressure_current_value'] = $current_yellow_wall_pressure;
        $log_arr['check_equation_Y_W'] = '<b>( current_yellow_wall_pressure  <= Recommended value)</b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End  Yellow Wall --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

      

        //%%%%%%%%%%%%%%%%%%% -- Seven Leve Prressure --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

            $is_rule_on = $global_setting_arr['seven_level_pressure_rule_' . $rule_number . '_enable'];
            $recommende_seven_level_pressure = $global_setting_arr['seven_level_pressure_rule_' . $rule_number]; 
            $current_seven_level_pressure = $coin_meta_arr['seven_level_depth'];

            $seven_level_pressure_yes_no = true;
            $rule_on_off = '<span style="background-color:yellow">OFF</span>';
            if ($is_rule_on == 'yes') {
                if($current_seven_level_pressure >= $recommende_seven_level_pressure) {
                    $seven_level_pressure_yes_no = true;
                    $rule_on_off = '<span style="color:green">YES</span>';
                } else {
                    $seven_level_pressure_yes_no = false;
                    $rule_on_off = '<span style="color:red">NO</span>';
                }
            }

            $log_arr['seven_level_pressure_status'] = $rule_on_off;
            $log_arr['seven_level_pressure_recommended_value'] = $recommende_seven_level_pressure;
            $log_arr['seven_level_pressure_current_value'] = $current_seven_level_pressure;
            $log_arr['check_equation_S_P'] = '<b>( current_seven_level_pressure  <= Recommended value)</b> ' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End  Seven Leve Prressure --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%% -- Buyers Vs Sellers --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%
            
         $is_rule_on = $global_setting_arr['seller_vs_buyer_rule_' . $rule_number . '_sell_enable'];
         $recommended_buyers_vs_sellers = $global_setting_arr['seller_vs_buyer_rule_' . $rule_number . '_sell']; 
         $current_buyers_vs_sellers = $coin_meta_arr['sellers_buyers_per'];

         $sellers_buyers_per_yes_no = true;
         $rule_on_off = '<span style="background-color:yellow">OFF</span>';
         if ($is_rule_on == 'yes') {
             if($current_buyers_vs_sellers <= $recommended_buyers_vs_sellers) {
                 $sellers_buyers_per_yes_no = true;
                 $rule_on_off = '<span style="color:green">YES</span>';
             } else {
                 $sellers_buyers_per_yes_no = false;
                 $rule_on_off = '<span style="color:red">NO</span>';
             }
         }

         $log_arr['buyers_vs_sellers_status'] = $rule_on_off;
         $log_arr['buyers_vs_sellers_recommended_value'] = $recommended_buyers_vs_sellers;
         $log_arr['buyers_vs_sellers_current_value'] = $current_buyers_vs_sellers;
         $log_arr['check_equation_B_V_S'] = ' <b>(5 min buyers/sellers  <= Recommended value)</b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End   Buyers Vs Sellers --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%
         

        //%%%%%%%%%%%%%%%%%%% -- Rejection Candle --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $is_rule_on = $global_setting_arr['sell_rejection_candle_type' . $rule_number . '_enable'];
        $recommended_rejection_candle = $global_setting_arr['rejection_candle_type' . $rule_number . '_sell']; 
        $last_candle_rejection_status = $coin_meta_arr['last_candle_rejection_status'];

        $rejection_candle_per_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($last_candle_rejection_status == $recommended_rejection_candle) {
                $rejection_candle_per_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $rejection_candle_per_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['last_candle_rejection_status'] = $rule_on_off;
        $log_arr['last_candle_rejection_recommended_value'] = $recommended_rejection_candle;
        $log_arr['last_candle_rejection_current_value'] = $last_candle_rejection_status;
        $log_arr['check_equation_L_C_R'] = '<b> (last candle rejection status  == Recommended value)</b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End   Rejection Candle --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%


        //%%%%%%%%%%%%%%%%%%% -- Last 200 Contract Buyers Vs Sellere --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $is_rule_on = $global_setting_arr['sell_last_200_contracts_buy_vs_sell' . $rule_number . '_enable'];
        $recommended_last_200_buyer_vs_sellers = $global_setting_arr['last_200_contracts_buy_vs_sell' . $rule_number . '_sell']; 
        $current_last_200_buyer_vs_sellers = $coin_meta_arr['last_200_buy_vs_sell'];

        $last_200_buyer_vs_sellers_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($current_last_200_buyer_vs_sellers <= $recommended_last_200_buyer_vs_sellers) {
                $last_200_buyer_vs_sellers_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_200_buyer_vs_sellers_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['last_200_buyer_vs_sellers_status'] = $rule_on_off;
        $log_arr['last_200_buyer_vs_sellers_recommended_value'] = $recommended_last_200_buyer_vs_sellers;
        $log_arr['last_200_buyer_vs_sellers_current_value'] = $current_last_200_buyer_vs_sellers;
        $log_arr['check_equation_L_C_1'] = '<b>( current_last_200_buyer_vs_sellers  <= Recommended value) </b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End   Last 200 Contract Buyers Vs Sellere --  %%%%%%%%%%%%%%%%%%%%


        //%%%%%%%%%%%%%%%%%%% -- Last 200 Contract Time --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $is_rule_on = $global_setting_arr['sell_last_200_contracts_time' . $rule_number . '_enable'];
        $recommended_last_200_contracts_time = $global_setting_arr['last_200_contracts_time' . $rule_number . '_sell']; 
        $current_last_200_contracts_time = (float) $coin_meta_arr['last_200_time_ago'];
        
        $last_200_contracts_time_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($current_last_200_contracts_time <= $recommended_last_200_contracts_time) {
                $last_200_contracts_time_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_200_contracts_time_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['last_200_contracts_time_status'] = $rule_on_off;
        $log_arr['last_200_contracts_time_recommended_value'] = $recommended_last_200_contracts_time;
        $log_arr['last_200_contracts_time_current_value'] = $current_last_200_contracts_time;
        $log_arr['check_equation_L_T_A'] = '<b> (last_200_time_ago in minute  <= Recommended value) </b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End   Last 200 Contract Time --  %%%%%%%%%%%%%%%%%%%%

       
         //%%%%%%%%%%%%%%%%%%% -- Last Qty Buyers vs Seller --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

         $is_rule_on = $global_setting_arr['sell_last_qty_buyers_vs_seller' . $rule_number . '_enable'];
         $recommended_last_qty_buyers_vs_seller_value = $global_setting_arr['last_qty_buyers_vs_seller' . $rule_number . '_sell']; 

         $last_qty_buyers_vs_seller_current_value =  $coin_meta_arr['last_qty_buy_vs_sell'];
         
         $last_qty_buyers_vs_seller_yes_no = true;
         $rule_on_off = '<span style="background-color:yellow">OFF</span>';
         if ($is_rule_on == 'yes') {
             if($last_qty_buyers_vs_seller_current_value <= $recommended_last_qty_buyers_vs_seller_value) {
                 $last_qty_buyers_vs_seller_yes_no = true;
                 $rule_on_off = '<span style="color:green">YES</span>';
             } else {
                 $last_qty_buyers_vs_seller_yes_no = false;
                 $rule_on_off = '<span style="color:red">NO</span>';
             }
         }
 
         $log_arr['last_qty_buy_vs_sell_status'] = $rule_on_off;
         $log_arr['last_qty_buy_vs_sell_recommended_value'] = $recommended_last_qty_buyers_vs_seller_value;
         $log_arr['last_qty_buy_vs_sell_current_value'] = $last_qty_buyers_vs_seller_current_value;
         $log_arr['check_equation_L_Q_B_S'] = ' <b>(last_qty_buy_vs_sell <= Recommended value)</b>' . '<br>';
         //%%%%%%%%%%%%%%%%%%% -- End Last Qty Buyers vs Seller --  %%%%%%%%%%%%%%%%%%%%
         
      
         
        //%%%%%%%%%%%%%%%%%%% -- Last Qty Time --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        
        $is_rule_on = $global_setting_arr['sell_last_qty_time' . $rule_number . '_enable'];
        $recommended_last_qty_time_value = $global_setting_arr['last_qty_time' . $rule_number . '_sell']; 
        $last_qty_time_ago_current_value = (float) $coin_meta_arr['last_qty_time_ago'];
        
        $last_qty_time_ago_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($last_qty_time_ago_current_value <= $recommended_last_qty_time_value) {
                $last_qty_time_ago_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_qty_time_ago_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['last_qty_time_status'] = $rule_on_off;
        $log_arr['last_qty_time_recommended_value'] = $recommended_last_qty_time_value;
        $log_arr['last_qty_time_current_value'] = $last_qty_time_ago_current_value;
        $log_arr['check_equation_L_q_t_ago'] = '<b> (last_qty_time_ago <= Recommended value) </b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End Qty Time --  %%%%%%%%%%%%%%%%%%%%

       
        //%%%%%%%%%%%%%%%%%%% -- SCORE --  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        
        $is_rule_on = $global_setting_arr['sell_score' . $rule_number . '_enable'];
        $recommended_score_value = $global_setting_arr['score' . $rule_number . '_sell'];

        $score_current_value = (float) $coin_meta_arr['score'];
        
        $score_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($score_current_value <= $recommended_score_value) {
                $score_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $score_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['score_status'] = $rule_on_off;
        $log_arr['score_recommended_value'] = $recommended_score_value;
        $log_arr['score_current_value'] = $score_current_value;
        $log_arr['check_equation_score'] = '<b> (current score <= Recommended value )</b>' . '<br>';

        //%%%%%%%%%%%%%%%%%%% -- End OF SCORE--  %%%%%%%%%%%%%%%%%%%%

         

        //%%%%%%%%%%%%%%%%%%% -- BUYERS VS SELLERS 15--  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    
        $is_rule_on = $global_setting_arr['buyers_vs_sellers_sell' . $rule_number . '_enable'];
        $recommended_buyers_vs_sellers_value = $global_setting_arr['buyers_vs_sellers' . $rule_number . '_sell']; 

        $buyers_vs_sellers_current_value = (float) $coin_meta_arr['sellers_buyers_per_fifteen'];
        
        $buyers_vs_sellers_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_rule_on == 'yes') {
            if($buyers_vs_sellers_current_value <= $recommended_buyers_vs_sellers_value) {
                $buyers_vs_sellers_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $buyers_vs_sellers_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['15_minute_buyers_vs_sellers_status'] = $rule_on_off;
        $log_arr['15_minute_buyers_vs_sellers_recommended_value'] = $recommended_buyers_vs_sellers_value;
        $log_arr['15_minute_buyers_vs_sellers_current_value'] = $buyers_vs_sellers_current_value;
        $log_arr['check_equation_b_vs_se'] = ' <b>(15 min buyers/sellers <= Recommended value )</b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%% -- End OF BUYERS VS SELLERS 15--  %%%%%%%%%%%%%%%%%%%%




         //%%%%%%%%%%%%%%%%%%% -- End OF BUYERS VS SELLERS 15--  %%%%%%%%%%%%%%%%%%%%
        

         $buy = $one_m_rolling_volume['buy'];
         $bid = $one_m_rolling_volume['bid'];
         $ask = $one_m_rolling_volume['ask'];
         $sell = $one_m_rolling_volume['sell'];

       //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Buy Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
       
       $buy_percentile_apply = $global_setting_arr['buy_percentile_'.$rule_number.'_apply_buy'];
       $buy_percentile_recommended_value = $global_setting_arr['buy_percentile_'.$rule_number.'_buy'];
      

       $buy_percentile_yes_no = true;
       $rule_on_off = '<span style="background-color:yellow">OFF</span>';
       if ($buy_percentile_apply == 'yes') {
           if ($buy <= $buy_percentile_recommended_value) {
               $buy_percentile_yes_no = true;
               $rule_on_off = '<span style="color:green">YES</span>';
           } else {
               $buy_percentile_yes_no = false;
               $rule_on_off = '<span style="color:red">NO</span>';
           }
       }

       

       $log_arr['Digie_Buy_One_minute_Rooling_candle_status'] = $rule_on_off;
       $log_arr['Digie_Buy_One_minute_Rooling_recommended_value'] = $buy_percentile_recommended_value;
       $log_arr['Digie_Buy_One_minute_Rooling_current_value'] = $buy;
       $log_arr['check_equation_Digie_Buy'] = ' <b>(current value  <= Recommended value )</b>' . '<br>';
       //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Ask Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $ask_percentile_apply = $global_setting_arr['ask_percentile_'.$rule_number.'_apply_buy'];
        $ask_percentile_recommended_value = $global_setting_arr['ask_percentile_'.$rule_number.'_buy'];
 


        $ask_percentile_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($ask_percentile_apply == 'yes') {
            if ($ask <= $ask_percentile_recommended_value) {
                $ask_percentile_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $ask_percentile_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['binance_Buy_One_minute_Rooling_candle_status'] = $rule_on_off;
        $log_arr['binance_Buy_One_minute_Rooling_recommended_value'] = $ask_percentile_recommended_value;
        $log_arr['binance_Buy_One_minute_Rooling_current_value'] = $ask . '<br>';
        $log_arr['check_equation_binance_Buy'] = ' <b>(current value  <= Recommended value )</b>' . '<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%




        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Sell Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $sell_percentile_apply = $global_setting_arr['sell_percentile_'.$rule_number.'_apply_buy'];
        $sell_percentile_recommended_value = $global_setting_arr['sell_percentile_'.$rule_number.'_buy'];
      

        $sell_percentile_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($sell_percentile_apply == 'yes') {
            if ($sell >= $sell_percentile_recommended_value) {
                $sell_percentile_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $sell_percentile_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        
        $log_arr['digie_sell_One_minute_Rooling_candle_status'] = $rule_on_off;
        $log_arr['digie_sell_One_minute_Rooling_candle_recommended_value'] = $sell_percentile_recommended_value;
        $log_arr['digie_sell_One_minute_Rooling_candle_current_value'] = $sell;
        $log_arr['check_equation_digie_sell'] = ' <b>(current value  >= Recommended value )</b>' . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%



         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Bid Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
         $bid_percentile_apply = $global_setting_arr['bid_percentile_'.$rule_number.'_apply_buy'];
         $bid_percentile_recommended_value = $global_setting_arr['bid_percentile_'.$rule_number.'_buy'];


         $bid_percentile_yes_no = true;
         $rule_on_off = '<span style="background-color:yellow">OFF</span>';
         if ($bid_percentile_apply == 'yes') {
             if ($bid >= $bid_percentile_recommended_value) {
                 $bid_percentile_yes_no = true;
                 $rule_on_off = '<span style="color:green">YES</span>';
             } else {
                 $bid_percentile_yes_no = false;
                 $rule_on_off = '<span style="color:red">NO</span>';
             }
         }
 
         $log_arr['binance_sell_One_minute_Rooling_candle_status'] = $rule_on_off;
         $log_arr['binance_sell_One_minute_Rooling_candle_recommended_value'] = $bid_percentile_recommended_value;
         $log_arr['binance_sell_One_minute_Rooling_candle_current_value'] = $bid;
         $log_arr['check_equation_binance_sell'] = ' <b>(current value  >= Recommended value )</b>' . '<br>';
         //%%%%%%%%%%%%%%%%%%%%% End of Bid %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

         
        $response_message = 'NO';

        if($is_swing_status && $volume_yes_no  && $virtual_barrier_yes_no && $big_seller_yes_no && $candle_type_yes_no && $black_wall_pressure_yes_no && $yellow_wall_pressure_yes_no && $seven_level_pressure_yes_no && $sellers_buyers_per_yes_no && $rejection_candle_per_yes_no && $last_200_buyer_vs_sellers_yes_no && $last_200_contracts_time_yes_no && $last_qty_buyers_vs_seller_yes_no && $last_qty_time_ago_yes_no &&  $score_yes_no && $buyers_vs_sellers_yes_no && $down_pressure_yes_no && $buy_percentile_yes_no && $ask_percentile_yes_no && $sell_percentile_yes_no && $bid_percentile_yes_no && $virtual_barrier_bid_yes_no){
            $response_message = 'YES';
        }

        $data['log_message'] = $log_arr;
        $data['success_message'] = $response_message; 

        return $data;
    
     } //End of is_triggers_qualify_to_sell_orders



    




    

} //End of mod_Barrier_trigger
?>