<?php

class mod_test_percentile_trigger extends CI_Model
{
    public function __construct()
    {
        // code...
    }

    public function is_triggers_qualify_to_buy_orders($coin_symbol, $order_level, $global_setting_arr, $current_market_price, $coin_meta_arr, $coin_meta_hourly_arr, $one_m_rolling_volume, $list_market_trends_arr, $lastCandleArr)
    {
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

        $last_qty_time_ago_15 = (float) $coin_meta_arr['last_qty_time_ago_15'];

        $ask_contract = (float) $coin_meta_arr['ask_contract'];
        $bid_contracts = (float) $coin_meta_arr['bid_contracts'];

        $enable_test_buy_barrier_percentile = $global_setting_arr['enable_test_buy_barrier_percentile'];
        if ($enable_test_buy_barrier_percentile == 'not' || $enable_test_buy_barrier_percentile == '') {
            $log_arr['Percentile_Level_'.$order_level.'_status'] = '<span style="color:red">OFF</span>';

            return $log_arr;
        } //End of

        $log_arr['-'] = '<span style="color: green;font-size: 27px;">Buy Rules</span><br>';
        $log_arr['Order_Is_Buyed_By_Level'] = $order_level.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Buyers  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $barrier_percentile_trigger_buyers_buy = $global_setting_arr['barrier_percentile_trigger_buyers_buy'];
        $barrier_percentile_trigger_buyers_buy_apply = $global_setting_arr['barrier_percentile_trigger_buyers_buy_apply'];

        $buyers_recommended_percentile_value = $coin_meta_hourly_arr['buyers_fifteen_'.$barrier_percentile_trigger_buyers_buy];

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
        $log_arr['Buyer_current_value'] = $buyers_fifteen.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $is_buy_rule_on = false;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($global_setting_arr['enable_test_buy_barrier_percentile'] == 'yes') {
            $rule_on_off = '<span style="color:green">YES</span>';
            $is_buy_rule_on = true;
        }

        $log_arr['buy_test_tradding'] = $rule_on_off;

        $seller_percentile = $global_setting_arr['barrier_percentile_trigger_sellers_buy'];
        $barrier_percentile_trigger_sellers_buy_apply = $global_setting_arr['barrier_percentile_trigger_sellers_buy_apply'];

        $sellers_recommended_percentile_value = $coin_meta_hourly_arr['sellers_fifteen_b_'.$seller_percentile];

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
        $log_arr['Sellers_current_value'] = $sellers_fifteen.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%% -- Barrier Status --%%%%%%%%%%%%%%%%%%%%%%% Remaining in node
        $last_barrrier_value = $this->triggers_trades->list_barrier_status($coin_symbol, 'very_strong_barrier', $current_market_price, 'down');

        $barrier_value_range_upside = $last_barrrier_value + ($last_barrrier_value / 100) * $barrier_range_percentage;
        $barrier_value_range_down_side = $last_barrrier_value - ($last_barrrier_value / 100) * $barrier_range_percentage;

        $meet_condition_for_buy = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($barrier_range_percentage != '' || $barrier_range_percentage != 0) {
            if ((num($current_market_price) >= num($barrier_value_range_down_side)) && (num($current_market_price) <= num($barrier_value_range_upside))) {
                $meet_condition_for_buy = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $meet_condition_for_buy = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        } //%%%%%%%%%% --  End of barrier range percentage -- %%%%%%%%%%555

        $log_arr['is_Barrier_Meet'] = $rule_on_off;
        $log_arr['Last_Barrier_price'] = num($last_barrrier_value);
        $log_arr['Barrier_Range_percentage'] = $barrier_range_percentage;
        $log_arr['Barrier_Range'] = 'Barrir From <b>('.num($barrier_value_range_down_side).')</b> To  <b>('.num($barrier_value_range_upside).')</b><br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_15_minute_rolling_candel = $global_setting_arr['barrier_percentile_trigger_15_minute_rolling_candel'];
        $percentile_trigger_15_minute_rolling_candel_apply = $global_setting_arr['barrier_percentile_trigger_15_minute_rolling_candel_apply'];

        $percentile_trigger_15_minute_rolling_candel_actual_value = $coin_meta_hourly_arr['fifteen_min_'.$percentile_trigger_15_minute_rolling_candel];

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
        $log_arr['15_minute_rolling_candel_current_value'] = $fifteen_minute_rolling_candel.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_5_minute_rolling_candel = $global_setting_arr['barrier_percentile_trigger_5_minute_rolling_candel'];
        $percentile_trigger_5_minute_rolling_candel_apply = $global_setting_arr['barrier_percentile_trigger_5_minute_rolling_candel_apply'];

        $percentile_trigger_5_minute_rolling_candel_actual_value = $coin_meta_hourly_arr['five_min_'.$percentile_trigger_5_minute_rolling_candel];

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
        $log_arr['5_minute_rolling_candel_current_value'] = $five_minute_rolling_candel.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_black_wall = $global_setting_arr['barrier_percentile_trigger_buy_black_wall'];
        $percentile_trigger_black_wall_apply = $global_setting_arr['barrier_percentile_trigger_buy_black_wall_apply'];
        $percentile_trigger_black_wall_actual_value = $coin_meta_hourly_arr['blackwall_'.$percentile_trigger_black_wall];

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
        $log_arr['black_wall_current_value'] = $black_wall_pressure.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% not converted
        $percentile_trigger_virtual_barrier = $global_setting_arr['barrier_percentile_trigger_buy_virtual_barrier'];
        $percentile_trigger_virtual_barrier_apply = $global_setting_arr['barrier_percentile_trigger_buy_virtual_barrier_apply'];
        $barrrier_recommended_value = $coin_meta_hourly_arr['bid_quantity_'.$percentile_trigger_virtual_barrier];

        //*********************************************************************/

        //%%%%%%%%%%%%%%%% -- Coin Unit Detail --%%%%%%%%%%%%%%%%%%%%%
        $coin_detail = $this->triggers_trades->get_coin_detail($coin_symbol);
        $coin_offset_value = $coin_detail['offset_value'];
        $coin_unit_value = $coin_detail['unit_value'];

        $total_bid_quantity = 0;
        for ($i = 0; $i < $coin_offset_value; ++$i) {
            $new_last_barrrier_value = (float) trim($current_market_price - ($coin_unit_value * $i));
            $bid = $this->triggers_trades->list_market_volume($new_last_barrrier_value, $coin_symbol, 'bid');
            $total_bid_quantity += $bid;
        } //End of Coin off Set

        $virtual_barrier_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_virtual_barrier_apply == 'yes') {
            if ($total_bid_quantity >= $barrrier_recommended_value) {
                $virtual_barrier_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $virtual_barrier_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['virtual_bid_barrier_status'] = $rule_on_off;
        $log_arr['virtual_bid_barrier_recommended_percentile'] = $percentile_trigger_virtual_barrier;
        $log_arr['virtual_bid_barrier_recommended_percentile_value'] = $barrrier_recommended_value;
        $log_arr['virtual_bid_barrier_current_value'] = $total_bid_quantity.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%% virtual Ask barrier %%%%%%%%%%%%%%%%%%%%%% not converted
        $percentile_trigger_virtual_barrier = $global_setting_arr['barrier_percentile_trigger_sell_virtual_barrier_for_buy'];
        $percentile_trigger_virtual_barrier_apply = $global_setting_arr['barrier_percentile_trigger_sell_virtual_barrier_for_buy_apply'];
        $barrrier_recommended_value = $coin_meta_hourly_arr['ask_quantity_b_'.$percentile_trigger_virtual_barrier];

        $total_ask_quantity = 0;
        for ($i = 0; $i < $coin_offset_value; ++$i) {
            $new_last_barrrier_value = (float) trim($current_market_price - ($coin_unit_value * $i));
            $ask = $this->triggers_trades->list_market_volume($new_last_barrrier_value, $coin_symbol, 'ask');
            $total_ask_quantity += $ask;
        } //End of Coin off Set

        //*********************************************************************/
        $virtual_barrier_ask_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_virtual_barrier_apply == 'yes') {
            if ($total_ask_quantity <= $barrrier_recommended_value) {
                $virtual_barrier_ask_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $virtual_barrier_ask_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['virtual_ask_barrier_status'] = $rule_on_off;
        $log_arr['virtual_ask_barrier_recommended_percentile'] = $percentile_trigger_virtual_barrier;
        $log_arr['virtual_ask_barrier_recommended_percentile_value'] = $barrrier_recommended_value;
        $log_arr['virtual_ask_barrier_current_value--'] = $total_ask_quantity.'<br>';
        //%%%%%%%%%%%% End of ask barrier %%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_seven_level_pressure = $global_setting_arr['barrier_percentile_trigger_buy_seven_level_pressure'];
        $percentile_trigger_seven_level_pressure_apply = $global_setting_arr['barrier_percentile_trigger_buy_seven_level_pressure_apply'];

        $seven_level_pressure_actual_value = $coin_meta_hourly_arr['sevenlevel_'.$percentile_trigger_seven_level_pressure];

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
        $log_arr['seven_level_pressure_current_value'] = $seven_level_depth.'<br>';

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
        $log_arr['last_200_buy_vs_sell_current_value'] = $last_200_buy_vs_sell.'<br>';
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
        $log_arr['last_200_time_ago_current_value'] = $last_200_time_ago.'<br>';
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
        $log_arr['last_qty_buy_vs_sell_current_value'] = $last_qty_buy_vs_sell.'<br>';
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
        $log_arr['last_qty_contracts_time_current_value'] = $last_qty_time_ago.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Last Procedding Orders %%%%%%%%%%%%%%%%%%%%%%%%%%%% not done yet

        $last_procedding_status = $this->triggers_trades->last_procedding_candle_status($coin_symbol);
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
        $log_arr['Last_procedding_candel_status'] = $last_procedding_status.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%-- -- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%% --  One Minute Rolling Candle -- %%%%%%%%%%%%%%%%%%%%%%%%%

        $buy = $one_m_rolling_volume['buy'];
        $bid = $one_m_rolling_volume['bid'];
        $ask = $one_m_rolling_volume['ask'];
        $sell = $one_m_rolling_volume['sell'];

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Buy Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $buy_percentile = $global_setting_arr['barrier_percentile_trigger_buy'];
        $buy_percentile_apply = $global_setting_arr['barrier_percentile_trigger_buy_apply'];

        $buy_percentile_recommended_value = $coin_meta_hourly_arr['buy_'.$buy_percentile];

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

        $log_arr['Buy_percentile_status'] = $rule_on_off;
        $log_arr['Buy_percentile_recommended_percentile'] = $buy_percentile;
        $log_arr['Buy_percentile_recommended_percentile_value'] = $buy_percentile_recommended_value;
        $log_arr['Buy_percentile_current_value'] = $buy.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Ask Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $ask_percentile = $global_setting_arr['barrier_percentile_trigger_ask'];
        $ask_percentile_apply = $global_setting_arr['barrier_percentile_trigger_ask_apply'];
        $ask_percentile_recommended_value = $coin_meta_hourly_arr['ask_'.$ask_percentile];

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

        $log_arr['ask_percentile_status'] = $rule_on_off;
        $log_arr['ask_percentile_recommended_percentile'] = $ask_percentile;
        $log_arr['ask_percentile_recommended_percentile_value'] = $ask_percentile_recommended_value;
        $log_arr['ask_percentile_current_value'] = $ask.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Sell Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $sell_percentile_apply = $global_setting_arr['barrier_percentile_trigger_sell_apply'];
        $sell_percentile = $global_setting_arr['barrier_percentile_trigger_sell'];
        $sell_percentile_recommended_value = $coin_meta_hourly_arr['sell_b_'.$sell_percentile];

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

        $log_arr['sell_percentile_status'] = $rule_on_off;
        $log_arr['sell_percentile_recommended_percentile'] = $sell_percentile;
        $log_arr['sell_percentile_recommended_percentile_value'] = $sell_percentile_recommended_value;
        $log_arr['sell_percentile_current_value'] = $sell.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Bid Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $bid_percentile_apply = $global_setting_arr['barrier_percentile_trigger_bid_apply'];
        $bid_percentile = $global_setting_arr['barrier_percentile_trigger_bid'];

        $bid_percentile_recommended_value = $coin_meta_hourly_arr['bid_b_'.$bid_percentile];

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

        $log_arr['bid_percentile_status'] = $rule_on_off;
        $log_arr['bid_percentile_recommended_percentile'] = $bid_percentile;
        $log_arr['bid_percentile_recommended_percentile_value'] = $bid_percentile_recommended_value;
        $log_arr['bid_percentile_current_value'] = $bid.'<br>';

        //%%%%%%%%%%%%%%%%%%%%% End of Bid %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%% --  End of One minute Rolling Candle-- %%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Ask Contract  Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $ask_contract = (float) $coin_meta_arr['ask_contract'];
        $bid_contracts = (float) $coin_meta_arr['bid_contracts'];

        $ask_contrc_percentile_apply = $global_setting_arr['barrier_percentile_trigger_ask_contracts_apply'];
        $ask_contrc_percentile = $global_setting_arr['barrier_percentile_trigger_ask_contracts'];

        $ask_contrc_percentile_recommended_value = $coin_meta_hourly_arr['ask_contract_'.$ask_contrc_percentile];

        $ask_contrc_percentile_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($ask_contrc_percentile_apply == 'yes') {
            if ($ask_contract >= $ask_contrc_percentile_recommended_value) {
                $ask_contrc_percentile_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $ask_contrc_percentile_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['ask_contrc_percentile_status'] = $rule_on_off;
        $log_arr['ask_contrc_percentile_recommended_percentile'] = $ask_contrc_percentile;
        $log_arr['ask_contrc_percentile_recommended_percentile_value'] = $ask_contrc_percentile_recommended_value;
        $log_arr['ask_contrc_percentile_current_value'] = $ask_contract.'<br>';

        //%%%%%%%%%%%%%%%%%%%%% End of Ask Contracts %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Bid Contract  Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $ask_contract = (float) $coin_meta_arr['ask_contract'];
        $bid_contracts = (float) $coin_meta_arr['bid_contracts'];

        $bid_contrc_percentile_apply = $global_setting_arr['barrier_percentile_trigger_bid_contracts_apply'];
        $bid_contrc_percentile = $global_setting_arr['barrier_percentile_trigger_bid_contracts'];

        $bid_contrc_percentile_recommended_value = $coin_meta_hourly_arr['bid_contracts_b_'.$bid_contrc_percentile];

        $bid_contrc_percentile_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($bid_contrc_percentile_apply == 'yes') {
            if ($bid_contracts <= $bid_contrc_percentile_recommended_value) {
                $bid_contrc_percentile_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $bid_contrc_percentile_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['bid_contrc_percentile_status'] = $rule_on_off;
        $log_arr['bid_contrc_percentile_recommended_percentile'] = $bid_contrc_percentile;
        $log_arr['bid_contrc_percentile_recommended_percentile_value'] = $bid_contrc_percentile_recommended_value;
        $log_arr['bid_contrc_percentile_current_value'] = $bid_contracts.'<br>';

        //%%%%%%%%%%%%%%%%%%%%% End of Bid Contracts %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%% -- 15 minute Ago -- %%%%%%%%%%%%%%%%%%%%%%

        $barrier_percentile_trigger_15_minute_last_time_ago_apply = $global_setting_arr['barrier_percentile_trigger_15_minute_last_time_ago_apply'];
        $barrier_percentile_trigger_15_minute_last_time_ago = $global_setting_arr['barrier_percentile_trigger_15_minute_last_time_ago'];

        $recommended_value = $coin_meta_hourly_arr['last_qty_time_ago_fif_'.$barrier_percentile_trigger_15_minute_last_time_ago];

        $last_time_ago_15_m_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($barrier_percentile_trigger_15_minute_last_time_ago_apply == 'yes') {
            if ($last_qty_time_ago_15 <= $recommended_value) {
                $last_time_ago_15_m_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_time_ago_15_m_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['lat_15_minute_time_ago_percentile_status'] = $rule_on_off;
        $log_arr['lat_15_minute_time_ago_recommended_percentile'] = $barrier_percentile_trigger_15_minute_last_time_ago;
        $log_arr['lat_15_minute_time_ago_recommended_percentile_value'] = $recommended_value;
        $log_arr['lat_15_minute_time_ago_percentile_current_value'] = $last_qty_time_ago_15.'<br>';

        //%%%%%%%%%%%%%%%%%%% -- End 15 minute Ago -- %%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%% Market Trends %%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $caption_option = $list_market_trends_arr['caption_option'];
        $recommended_value = $global_setting_arr['percentile_trigger_caption_option_buy'];
        $is_on = $global_setting_arr['percentile_trigger_caption_option_buy_apply'];

        $is_on_off_caption = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($caption_option > $recommended_value) {
                $is_on_off_caption = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_caption = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['caption_option_status'] = $rule_on_off;
        $log_arr['caption_option_recommended_value'] = $recommended_value;
        $log_arr['caption_option_current_value'] = $caption_option.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $current_val = $list_market_trends_arr['caption_score'];
        $recommended_value = $global_setting_arr['percentile_trigger_caption_score_buy'];
        $is_on = $global_setting_arr['percentile_trigger_caption_score_buy_apply'];

        $is_on_off_caption_score = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val == $recommended_value) {
                $is_on_off_caption_score = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_caption_score = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['caption_score_status'] = $rule_on_off;
        $log_arr['caption_score_recommended_value'] = $recommended_value;
        $log_arr['caption_score_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $current_val = $list_market_trends_arr['buy'];
        $recommended_value = $global_setting_arr['percentile_trigger_buy_trend_buy'];
        $is_on = $global_setting_arr['percentile_trigger_buy_trend_buy_apply'];

        $is_on_off_buy = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val >= $recommended_value) {
                $is_on_off_buy = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_buy = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['buy_status'] = $rule_on_off;
        $log_arr['buy_recommended_value'] = $recommended_value;
        $log_arr['buy_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $current_val = $list_market_trends_arr['sell'];
        $recommended_value = $global_setting_arr['percentile_trigger_sell_buy'];
        $is_on = $global_setting_arr['percentile_trigger_sell_buy_apply'];

        $is_on_off_sell = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val < $recommended_value) {
                $is_on_off_sell = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_sell = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['sell_status'] = $rule_on_off;
        $log_arr['sell_recommended_value'] = $recommended_value;
        $log_arr['sell_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $current_val = $list_market_trends_arr['demand'];
        $recommended_value = $global_setting_arr['percentile_trigger_demand_buy'];
        $is_on = $global_setting_arr['percentile_trigger_demand_buy_apply'];

        $is_on_off_demand = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val > $recommended_value) {
                $is_on_off_demand = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_demand = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['demand_status'] = $rule_on_off;
        $log_arr['demand_recommended_value'] = $recommended_value;
        $log_arr['demand_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $current_val = $list_market_trends_arr['supply'];
        $recommended_value = $global_setting_arr['percentile_trigger_supply_buy'];
        $is_on = $global_setting_arr['percentile_trigger_supply_buy_apply'];

        $is_on_off_supply = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val < $recommended_value) {
                $is_on_off_supply = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_supply = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['supply_status'] = $rule_on_off;
        $log_arr['supply_recommended_value'] = $recommended_value;
        $log_arr['supply_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $current_val = $list_market_trends_arr['market_trend'];
        $recommended_value = $global_setting_arr['percentile_trigger_market_trend_buy'];
        $is_on = $global_setting_arr['percentile_trigger_market_trend_buy_apply'];

        $is_on_off_market_trende = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val == $recommended_value) {
                $is_on_off_market_trende = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_market_trende = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['market_trend_status'] = $rule_on_off;
        $log_arr['market_trend_recommended_value'] = $recommended_value;
        $log_arr['market_trend_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $current_val = $list_market_trends_arr['meta_trading'];
        $recommended_value = $global_setting_arr['percentile_trigger_meta_trading_buy'];
        $is_on = $global_setting_arr['percentile_trigger_meta_trading_buy_apply'];

        $is_on_off_meta_trading = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val > $recommended_value) {
                $is_on_off_meta_trading = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_meta_trading = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['market_meta_trading'] = $rule_on_off;
        $log_arr['meta_trading_recommended_value'] = $recommended_value;
        $log_arr['meta_trading_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $current_val = $list_market_trends_arr['riskpershare'];
        $recommended_value = $global_setting_arr['percentile_trigger_riskpershare_buy'];
        $is_on = $global_setting_arr['percentile_trigger_riskpershare_buy_apply'];

        $is_on_off_riskpershare = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val < $recommended_value) {
                $is_on_off_riskpershare = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_riskpershare = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['riskpershare_trading'] = $rule_on_off;
        $log_arr['riskpershare_recommended_value'] = $recommended_value;
        $log_arr['riskpershare_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $current_val = $list_market_trends_arr['RL'];
        $recommended_value = $global_setting_arr['percentile_trigger_RL_buy'];
        $is_on = $global_setting_arr['percentile_trigger_RL_buy_apply'];

        $is_on_off_RL = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val == $recommended_value) {
                $is_on_off_RL = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_RL = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['RL_trading'] = $rule_on_off;
        $log_arr['RL_recommended_value'] = $recommended_value;
        $log_arr['RL_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $current_val = $list_market_trends_arr['long_term_intension'];
        $recommended_value = $global_setting_arr['percentile_trigger_long_term_intension_buy'];
        $is_on = $global_setting_arr['percentile_trigger_long_term_intension_buy_apply'];

        $is_on_off_long_term_intension = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val >= $recommended_value) {
                $is_on_off_long_term_intension = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_long_term_intension = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['long_term_intension_trading'] = $rule_on_off;
        $log_arr['long_term_intension_recommended_value'] = $recommended_value;
        $log_arr['long_term_intension_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $current_val = $list_market_trends_arr['previous_state'];
        $recommended_value = $global_setting_arr['percentile_trigger_previous_state_buy'];
        $is_on = $global_setting_arr['percentile_trigger_previous_state_buy_apply'];

        $is_on_off_long_previous = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val == $recommended_value) {
                $is_on_off_long_previous = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_long_previous = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['market_trend_previous_state'] = $rule_on_off;
        $log_arr['market_trend_previous_state_recommanded_value'] = $recommended_value;
        $log_arr['market_trend_previous_state_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['percentile_trigger_range_buy'];
        $is_on = $global_setting_arr['percentile_trigger_range_buy_apply'];
        $current_val = $list_market_trends_arr['range_'.$recommended_value];
        $is_on_off_range = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val <= $recommended_value) {
                $is_on_off_range = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_range = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['market_trend_range'] = $rule_on_off;
        $log_arr['market_trend_range_recommanded_value'] = $recommended_value;
        $log_arr['market_trend_range_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['percentile_trigger_deep_value_1_buy'];
        $is_on = $global_setting_arr['percentile_trigger_deep_value_1_buy_apply'];
        $current_val = $list_market_trends_arr['deep_value'];
        $is_on_off_deep_value_1 = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val <= $recommended_value) {
                $is_on_off_deep_value_1 = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_deep_value_1 = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['market_trend_deep_value_1'] = $rule_on_off;
        $log_arr['market_trend_deep_value_1_recommanded_value'] = $recommended_value;
        $log_arr['market_trend_deep_value_1_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['last_1h_candle_compare_volume_value_buy'];
        $is_on = $global_setting_arr['last_1h_candle_compare_volume_value_buy_apply'];
        $current_val = $lastCandleArr['total_volume'];

        $is_on_off_last_candle_total_volume = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val >= $recommended_value) {
                $is_on_off_last_candle_total_volume = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_last_candle_total_volume = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['last_hour_candle_volume_status'] = $rule_on_off;
        $log_arr['last_hour_candle_volume_recommended_value'] = $recommended_value;
        $log_arr['last_hour_candle_volume_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%% Big Contracts %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['big_contractor_delta_value_buy'];
        $top_contractor = $global_setting_arr['big_contractor_top_percentage_buy'];
        $is_on = $global_setting_arr['big_contractor_top_percentage_buy_apply'];

        $is_on_off_big_contracts = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            //******* New Logic  */
            if ($top_contractor == 10) {
                $seller = $lastCandleArr['bigSellerContract1'] + $lastCandleArr['bigSellerContract2'] + $lastCandleArr['bigSellerContract3'] + $lastCandleArr['bigSellerContract4'] + $lastCandleArr['bigSellerContract5'] + $lastCandleArr['bigSellerContract10'];

                $buyer = $lastCandleArr['bigBuyerContract1'] + $lastCandleArr['bigBuyerContract2'] + $lastCandleArr['bigBuyerContract3'] + $lastCandleArr['bigBuyerContract4'] + $lastCandleArr['bigBuyerContract5'] + $lastCandleArr['bigSellerContract10'];
            } elseif ($top_contractor == 5) {
                $seller = $lastCandleArr['bigSellerContract1'] + $lastCandleArr['bigSellerContract2'] + $lastCandleArr['bigSellerContract3'] + $lastCandleArr['bigSellerContract4'] + $lastCandleArr['bigSellerContract5'];
                $buyer = $lastCandleArr['bigBuyerContract1'] + $lastCandleArr['bigBuyerContract2'] + $lastCandleArr['bigBuyerContract3'] + $lastCandleArr['bigBuyerContract4'] + $lastCandleArr['bigBuyerContract5'];
            } elseif ($top_contractor == 4) {
                $seller = $lastCandleArr['bigSellerContract1'] + $lastCandleArr['bigSellerContract2'] + $lastCandleArr['bigSellerContract3'] + $lastCandleArr['bigSellerContract4'];
                $buyer = $lastCandleArr['bigBuyerContract1'] + $lastCandleArr['bigBuyerContract2'] + $lastCandleArr['bigBuyerContract3'] + $lastCandleArr['bigBuyerContract4'];
            } elseif ($top_contractor == 3) {
                $seller = $lastCandleArr['bigSellerContract1'] + $lastCandleArr['bigSellerContract2'] + $lastCandleArr['bigSellerContract3'];

                $buyer = $lastCandleArr['bigBuyerContract1'] + $lastCandleArr['bigBuyerContract2'] + $lastCandleArr['bigBuyerContract3'];
            } elseif ($top_contractor == 2) {
                $seller = $lastCandleArr['bigSellerContract1'] + $lastCandleArr['bigSellerContract2'];
                $buyer = $lastCandleArr['bigBuyerContract1'] + $lastCandleArr['bigBuyerContract2'];
            } elseif ($top_contractor == 1) {
                $seller = $lastCandleArr['bigSellerContract1'];
                $buyer = $lastCandleArr['bigBuyerContract1'];
            }
            //*** End of new logic */
            $current_val = $buyer / $seller;

            if ($current_val >= $recommended_value) {
                $is_on_off_big_contracts = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_big_contracts = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['big_contracts_status'] = $rule_on_off;
        $log_arr['big_contracts_recommended_value'] = $recommended_value;
        $log_arr['big_contracts_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%% End of Big contracts %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%% Big buyers Contracts %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['last_1h_candle_big_contractor_buyers_value_buy'];
        $top_contractor = $global_setting_arr['last_1h_candle_big_contractor_buyers_top_percentage_value_buy'];
        $is_on = $global_setting_arr['last_1h_candle_big_contractor_buyers_value_buy_apply'];

        $is_on_off_big_buyers_contracts = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            //******* New Logic  */
            if ($top_contractor == 10) {
                $buyer = $lastCandleArr['bigBuyerContract1'] + $lastCandleArr['bigBuyerContract2'] + $lastCandleArr['bigBuyerContract3'] + $lastCandleArr['bigBuyerContract4'] + $lastCandleArr['bigBuyerContract5'] + $lastCandleArr['bigSellerContract10'];
            } elseif ($top_contractor == 5) {
                $buyer = $lastCandleArr['bigBuyerContract1'] + $lastCandleArr['bigBuyerContract2'] + $lastCandleArr['bigBuyerContract3'] + $lastCandleArr['bigBuyerContract4'] + $lastCandleArr['bigBuyerContract5'];
            } elseif ($top_contractor == 4) {
                $buyer = $lastCandleArr['bigBuyerContract1'] + $lastCandleArr['bigBuyerContract2'] + $lastCandleArr['bigBuyerContract3'] + $lastCandleArr['bigBuyerContract4'];
            } elseif ($top_contractor == 3) {
                $buyer = $lastCandleArr['bigBuyerContract1'] + $lastCandleArr['bigBuyerContract2'] + $lastCandleArr['bigBuyerContract3'];
            } elseif ($top_contractor == 2) {
                $buyer = $lastCandleArr['bigBuyerContract1'] + $lastCandleArr['bigBuyerContract2'];
            } elseif ($top_contractor == 1) {
                $buyer = $lastCandleArr['bigBuyerContract1'];
            }
            //*** End of new logic */
            $current_val = $buyer;

            if ($current_val >= $recommended_value) {
                $is_on_off_big_buyers_contracts = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_big_buyers_contracts = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['big_buyers_contracts_status'] = $rule_on_off;
        $log_arr['big_buyers_contracts_recommended_value'] = $recommended_value;
        $log_arr['big_buyers_contracts_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%% End of Big buyers contracts %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%% Big sellers Contracts %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['last_1h_candle_big_contractor_sellers_value_buy'];
        $top_contractor = $global_setting_arr['last_1h_candle_big_contractor_sellers_top_percentage_value_buy'];
        $is_on = $global_setting_arr['last_1h_candle_big_contractor_sellers_value_buy_apply'];

        $is_on_off_big_sellers_contracts = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            //******* New Logic  */
            if ($top_contractor == 10) {
                $seller = $lastCandleArr['bigSellerContract1'] + $lastCandleArr['bigSellerContract2'] + $lastCandleArr['bigSellerContract3'] + $lastCandleArr['bigSellerContract4'] + $lastCandleArr['bigSellerContract5'] + $lastCandleArr['bigSellerContract10'];
            } elseif ($top_contractor == 5) {
                $seller = $lastCandleArr['bigSellerContract1'] + $lastCandleArr['bigSellerContract2'] + $lastCandleArr['bigSellerContract3'] + $lastCandleArr['bigSellerContract4'] + $lastCandleArr['bigSellerContract5'];
            } elseif ($top_contractor == 4) {
                $seller = $lastCandleArr['bigSellerContract1'] + $lastCandleArr['bigSellerContract2'] + $lastCandleArr['bigSellerContract3'] + $lastCandleArr['bigSellerContract4'];
            } elseif ($top_contractor == 3) {
                $seller = $lastCandleArr['bigSellerContract1'] + $lastCandleArr['bigSellerContract2'] + $lastCandleArr['bigSellerContract3'];
            } elseif ($top_contractor == 2) {
                $seller = $lastCandleArr['bigSellerContract1'] + $lastCandleArr['bigSellerContract2'];
            } elseif ($top_contractor == 1) {
                $seller = $lastCandleArr['bigSellerContract1'];
            }
            //*** End of new logic */
            $current_val = $seller;

            if ($current_val <= $recommended_value) {
                $is_on_off_big_sellers_contracts = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_big_sellers_contracts = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['big_sellers_contracts_status'] = $rule_on_off;
        $log_arr['big_sellers_contracts_recommended_value'] = $recommended_value;
        $log_arr['big_sellers_contracts_current_value'] = $current_val.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%% End of Big sellers contracts %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%% candle move %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $colorArr = (array) $global_setting_arr['candle_move_color_buy'];

        $is_on = $global_setting_arr['candle_move_color_buy_apply'];
        $move = $lastCandleArr['per_move'];

        $is_on_off_candle_move = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            //************** */

            if ($move > 4) {
                $color == 'yellow';
            } elseif ($move > 3) {
                $color = 'white';
            } elseif ($move > 2) {
                $color = 'green';
            } elseif ($move > 1) {
                $color = 'blue';
            } else {
                $color = 'red';
            }

            if (in_array($color, $colorArr)) {
                $is_on_off_candle_move = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_candle_move = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }

            //***************** */
        }

        $log_arr['candle_move_status'] = $rule_on_off;
        $log_arr['candle_move_recommended_color'] = implode(',', $colorArr);
        $log_arr['candle_move_current_color'] = $color.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%% End of candle move %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%% 24-H Candel  %%%%%%%%%%%%%%%%%%%%
        $is_on = $global_setting_arr['candle_24_buy_apply'];
        $deep_range_percentage_24_h = $global_setting_arr['hour_24_deep_range_buy'];
        $candle_side_24_h = $global_setting_arr['hour_24_candle_side_buy'];
        $candle_range_24_h = $global_setting_arr['hour_24_candle_range_buy'];
        $formula = $global_setting_arr['hour_24_HLOC_buy'];

        $open = $lastCandleArr['last_24_hour_open'];
        $close = $lastCandleArr['last_24_hour_close'];
        $high = $lastCandleArr['last_24_hour_high'];
        $low = $lastCandleArr['last_24_hour_low'];

        $is_on_off_24_h_candle = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            $log_arr['last_24_h_formula'] = $formula;
            $log_arr['last_24_h_range_side'] = $candle_range_24_h;
            $log_arr['last_24_h_deep_range_percentage'] = $deep_range_percentage_24_h;
            //**************************** */
            if ($formula == 'high_low') {
                $distance = (($high - $low) / 100) * $deep_range_percentage_24_h;
                $upper_range = $high - $distance;
                $lower_range = $low + $distance;

                $log_arr['last_24_h_current_market_price'] = num($current_market_price);

                if ($candle_range_24_h == 'up') {
                    $log_arr['last_24_h_upper_range'] = num($upper_range);

                    if ($current_market_price >= $upper_range) {
                        $is_on_off_24_h_candle = true;
                        $rule_on_off = '<span style="color:green">YES</span>';
                    } else {
                        $is_on_off_24_h_candle = false;
                        $rule_on_off = '<span style="color:red">NO</span>';
                    }
                } else {
                    if ($current_market_price <= $lower_range) {
                        $log_arr['last_24_h_lower_range'] = num($lower_range);
                        $is_on_off_24_h_candle = true;
                        $rule_on_off = '<span style="color:green">YES</span>';
                    } else {
                        $is_on_off_24_h_candle = false;
                        $rule_on_off = '<span style="color:red">NO</span>';
                    }
                }
            } elseif ($formula == 'open_close') {
                if ($open > $close) {
                    $big = $open;
                    $small = $close;
                } else {
                    $big = $close;
                    $small = $open;
                }

                $distance = (($open - $close) / 100) * $deep_range_percentage_24_h;
                $upper_range = $big - $distance;
                $lower_range = $small + $distance;

                $log_arr['last_24_h_current_market_price'] = num($current_market_price);
                $log_arr['last_24_h_upper_range'] = num($upper_range);
                $log_arr['last_24_h_lower_range'] = num($lower_range);
                if ($current_market_price >= $upper_range) {
                    $is_on_off_24_h_candle = true;
                    $rule_on_off = '<span style="color:green">YES</span>';
                } else {
                    $is_on_off_24_h_candle = false;
                    $rule_on_off = '<span style="color:red">NO</span>';
                }
            } else {
                if ($current_market_price <= $lower_range) {
                    $is_on_off_24_h_candle = true;
                    $rule_on_off = '<span style="color:green">YES</span>';
                } else {
                    $is_on_off_24_h_candle = false;
                    $rule_on_off = '<span style="color:red">NO</span>';
                }
            }
        }

        $log_arr['last_24_hour_candle_status'] = $rule_on_off.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $candle_side = $global_setting_arr['hour_24_candle_side_buy'];
        $is_on_off_last_candle_side_24_h = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes' && $candle_side != 'non') {
            //*************** */
            if ($candle_side == 'above') {
                if ($close > $open) {
                    $is_on_off_last_candle_side_24_h = true;
                    $rule_on_off = '<span style="color:green">YES</span>';
                } else {
                    $is_on_off_last_candle_side_24_h = false;
                    $rule_on_off = '<span style="color:red">NO</span>';
                }
            } else {
                if ($open > $close) {
                    $is_on_off_last_candle_side_24_h = true;
                    $rule_on_off = '<span style="color:green">YES</span>';
                } else {
                    $is_on_off_last_candle_side_24_h = false;
                    $rule_on_off = '<span style="color:red">NO</span>';
                }
            }
            //**************** */
        }
        $log_arr['last_24_hour_candle_side_status'] = $rule_on_off;
        $log_arr['last_24_hour_candle_volume_recommended_value'] = $candle_side;
        $log_arr['last_24_hour_candle_open'] = num($open);
        $log_arr['last_24_hour_candle_close'] = num($close).'<br>';

        //%%%%%%%%%%%%%%%  End of 24-H candle %%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%% 1-H Candel  %%%%%%%%%%%%%%%%%%%%
        $is_on = $global_setting_arr['candle_1_buy_apply'];
        $deep_range_percentage_1_h = $global_setting_arr['hour_1_deep_range_buy'];
        $candle_side_1_h = $global_setting_arr['hour_1_candle_side_buy'];
        $candle_range_1_h = $global_setting_arr['hour_1_candle_range_buy'];
        $formula = $global_setting_arr['hour_1_HLOC_buy'];

        $open = $lastCandleArr['open'];
        $close = $lastCandleArr['close'];
        $high = $lastCandleArr['high'];
        $low = $lastCandleArr['low'];

        $is_on_off_1_h_candle = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            $log_arr['last_1_h_formula'] = $formula;
            $log_arr['last_1_h_range_side'] = $candle_range_1_h;
            $log_arr['last_1_h_deep_range_percentage'] = $deep_range_percentage_1_h;
            //**************************** */
            if ($formula == 'high_low') {
                $distance = (($high - $low) / 100) * $deep_range_percentage_1_h;
                $upper_range = $high - $distance;
                $lower_range = $low + $distance;

                $log_arr['last_1_h_current_market_price'] = num($current_market_price);

                if ($candle_range_1_h == 'up') {
                    $log_arr['last_1_h_upper_range'] = num($upper_range);

                    if ($current_market_price >= $upper_range) {
                        $is_on_off_1_h_candle = true;
                        $rule_on_off = '<span style="color:green">YES</span>';
                    } else {
                        $is_on_off_1_h_candle = false;
                        $rule_on_off = '<span style="color:red">NO</span>';
                    }
                } else {
                    if ($current_market_price <= $lower_range) {
                        $log_arr['last_1_h_lower_range'] = num($lower_range);
                        $is_on_off_1_h_candle = true;
                        $rule_on_off = '<span style="color:green">YES</span>';
                    } else {
                        $is_on_off_1_h_candle = false;
                        $rule_on_off = '<span style="color:red">NO</span>';
                    }
                }
            } elseif ($formula == 'open_close') {
                if ($open > $close) {
                    $big = $open;
                    $small = $close;
                } else {
                    $big = $close;
                    $small = $open;
                }

                $distance = (($open - $close) / 100) * $deep_range_percentage_1_h;
                $upper_range = $big - $distance;
                $lower_range = $small + $distance;

                $log_arr['last_1_h_current_market_price'] = num($current_market_price);
                $log_arr['last_1_h_upper_range'] = num($upper_range);
                $log_arr['last_1_h_lower_range'] = num($lower_range);
                if ($current_market_price >= $upper_range) {
                    $is_on_off_1_h_candle = true;
                    $rule_on_off = '<span style="color:green">YES</span>';
                } else {
                    $is_on_off_1_h_candle = false;
                    $rule_on_off = '<span style="color:red">NO</span>';
                }
            } else {
                if ($current_market_price <= $lower_range) {
                    $is_on_off_1_h_candle = true;
                    $rule_on_off = '<span style="color:green">YES</span>';
                } else {
                    $is_on_off_1_h_candle = false;
                    $rule_on_off = '<span style="color:red">NO</span>';
                }
            }
        }

        $log_arr['last_1_hour_candle_status'] = $rule_on_off.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $candle_side = $global_setting_arr['hour_1_candle_side_buy'];
        $is_on_off_last_candle_side_1_h = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes' && $candle_side != 'non') {
            //*************** */
            if ($candle_side == 'above') {
                if ($close > $open) {
                    $is_on_off_last_candle_side_1_h = true;
                    $rule_on_off = '<span style="color:green">YES</span>';
                } else {
                    $is_on_off_last_candle_side_1_h = false;
                    $rule_on_off = '<span style="color:red">NO</span>';
                }
            } else {
                if ($open > $close) {
                    $is_on_off_last_candle_side_1_h = true;
                    $rule_on_off = '<span style="color:green">YES</span>';
                } else {
                    $is_on_off_last_candle_side_1_h = false;
                    $rule_on_off = '<span style="color:red">NO</span>';
                }
            }
            //**************** */
        }
        $log_arr['last_1_hour_candle_side_status'] = $rule_on_off;
        $log_arr['last_1_hour_candle_volume_recommended_value'] = $candle_side;
        $log_arr['last_1_hour_candle_open'] = num($open);
        $log_arr['last_1_hour_candle_close'] = num($close).'<br>';

        //%%%%%%%%%%%%%%%  End of 1-H candle %%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['volume_increasing_buy'];
        $is_on = $global_setting_arr['volume_increasing_buy_apply'];
        $current_val = $list_market_trends_arr['increasing_volume'];
        $is_on_off_volume_increasing = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val >= $recommended_value) {
                $is_on_off_volume_increasing = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_volume_increasing = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['on_off_volume_increasing'] = $rule_on_off;
        $log_arr['volume_increasing_recommanded_value'] = $recommended_value;
        $log_arr['volume_increasing_current_value'] = $current_val.'<br>';

        $log_arr['tooltip_volume_increasing'] = 'Current Value  >= Recommended Value<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $is_on = $global_setting_arr['lh_tw_contracts_buy_apply'];

        $current_val = (array) $list_market_trends_arr['lh_tw_contracts'];
        $is_on_off_lh_tw_contracts = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if (empty($current_val) || (count($current_val) == 1 && $current_val[0] == 0)) {
                $is_on_off_lh_tw_contracts = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_lh_tw_contracts = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['lh_tw_contracts_on_off'] = $rule_on_off;
        $log_arr['lh_tw_contracts_current_val'] = implode(', ', $current_val).'<br>';

        $log_arr['tooltip_lh_tw_contracts'] = 'Current Value  == 0 || null<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['lh_lw_contracts_buy'];
        $is_on = $global_setting_arr['lh_lw_contracts_buy_apply'];
        $current_val = (array) $list_market_trends_arr['lh_lw_contracts'];
        $is_on_off_lh_lw_contracts = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';

        $recommended_arr = array();
        if ($recommended_value != '') {
            $x = 1;
            do {
                array_push($recommended_arr, $x);
                ++$x;
            } while ($x <= $recommended_value);
        }

        $check = array_intersect($recommended_arr, $current_val);

        if ($is_on == 'yes') {
            if (count($check) == count($recommended_arr) && count($recommended_value) > 0) {
                $is_on_off_lh_lw_contracts = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_lh_lw_contracts = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['lh_lw_contracts_on_off'] = $rule_on_off;
        $log_arr['lh_lw_contracts_recommended_value'] = $recommended_value;
        $log_arr['lh_lw_contracts_current_value'] = implode(', ', $current_val).'<br>';

        $log_arr['tooltip_lh_lw_contracts'] = ' Recommended Value Exist in Current Values<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['lh_lwb_contracts_buy'];
        $is_on = $global_setting_arr['lh_lwb_contracts_buy_apply'];
        $current_val = (array) $list_market_trends_arr['lh_lwb_contracts'];
        $is_on_off_lh_lwb_contracts = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';

        $recommended_arr = array();
        if ($recommended_value != '') {
            $x = 1;
            do {
                array_push($recommended_arr, $x);
                ++$x;
            } while ($x <= $recommended_value);
        }

        $check = array_intersect($recommended_arr, $current_val);

        if ($is_on == 'yes') {
            if (count($check) == count($recommended_arr) && count($recommended_arr) > 0) {
                $is_on_off_lh_lwb_contracts = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_lh_lwb_contracts = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['lh_lwb_contracts_on_off'] = $rule_on_off;
        $log_arr['lh_lwb_contracts_recommended_value'] = $recommended_value;
        $log_arr['lh_lwb_contracts_current_value'] = implode(', ', $current_val).'<br>';

        $log_arr['tooltip_lh_lwb_contracts'] = ' Recommended Value Exist in Current Values<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['five_min_btc_change_buy'];
        $is_on = $global_setting_arr['five_min_btc_change_buy_apply'];
        $current_val = $list_market_trends_arr['btc_price_change'];
        $is_on_off_five_min_btc_change = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val >= $recommended_value && $current_val != '' && $recommended_value != '') {
                $is_on_off_five_min_btc_change = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_five_min_btc_change = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['on_off_five_min_btc_change'] = $rule_on_off;
        $log_arr['five_min_btc_change_recommanded_value'] = $recommended_value;
        $log_arr['five_min_btc_change_current_value'] = $current_val.'<br>';

        $log_arr['tooltip_five_min_btc_change'] = 'Current Value  >= Recommended Value<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['lh_tv_percentile_buy'];
        $is_on = $global_setting_arr['lh_tv_percentile_buy_apply'];
        $current_val = $list_market_trends_arr['lh_tv_percentile'];
        $is_on_off_lh_tv_percentile = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val >= $recommended_value && $current_val != '' && $recommended_value != '') {
                $is_on_off_lh_tv_percentile = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_lh_tv_percentile = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['on_off_lh_tv_percentile'] = $rule_on_off;
        $log_arr['lh_tv_percentile_recommanded_value'] = $recommended_value;
        $log_arr['lh_tv_percentile_current_value'] = $current_val.'<br>';

        $log_arr['tooltip_lh_tv_percentile'] = 'Current Value  >= Recommended Value<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['curr_bc_percentile_buy'];
        $is_on = $global_setting_arr['curr_bc_percentile_buy_apply'];
        $current_val = $list_market_trends_arr['curr_bc_percentile'];
        $is_on_off_curr_bc_percentile = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if (($current_val <= $recommended_value) && $current_val != '' && $recommended_value != '') {
                $is_on_off_curr_bc_percentile = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_curr_bc_percentile = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['on_off_curr_bc_percentile'] = $rule_on_off;
        $log_arr['curr_bc_percentile_recommanded_value'] = $recommended_value;
        $log_arr['curr_bc_percentile_current_value'] = $current_val.'<br>';

        $log_arr['tooltip_curr_bc_percentile'] = 'Current Value  <= Recommended Value<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['lh_bc_qty_buy'];
        $recommended_percentage = $global_setting_arr['lh_bc_qty_buy_percent'];
        $is_on = $global_setting_arr['lh_bc_qty_buy_apply'];

        $current_val = $list_market_trends_arr['lh_bc_'.$recommended_percentage.'_qty'];

        $is_on_off_curr_lh_bc_qty = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if ($current_val > $recommended_value && $current_val != '' && $recommended_value != '') {
                $is_on_off_curr_lh_bc_qty = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_curr_lh_bc_qty = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['on_lh_bc_qty_on_off'] = $rule_on_off;
        $log_arr['lh_bc_qty_recommanded_value'] = $recommended_value;
        $log_arr['lh_bc_qty_recommanded_percentage'] = $recommended_percentage;
        $log_arr['lh_bc_qty_current_value'] = $current_val.'<br>';

        $log_arr['tooltip_lh_bc_qty'] = 'Current Value  > Recommended Value<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['lh_tw_contracts_percentile_buy'];
        $is_on = $global_setting_arr['lh_tw_contracts_percentile_buy_apply'];
        $current_val = (array) $list_market_trends_arr['lh_tw_contracts_percentile'];

        $rule_on_off = '<span style="background-color:yellow">OFF</span>';

        $recommended_arr = array();
        if ($recommended_value != '') {
            $x = 1;
            do {
                array_push($recommended_arr, $x);
                ++$x;
            } while ($x <= $recommended_value);
        }

        $check = array_intersect($recommended_arr, $current_val);

        $is_on_off_lh_tw_contracts_percentile = true;
        if ($is_on == 'yes') {
            if (count($check) == 0 && count($recommended_arr) > 0) {
                $is_on_off_lh_tw_contracts_percentile = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_lh_tw_contracts_percentile = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['lh_tw_contracts_percentile_on_off'] = $rule_on_off;
        $log_arr['lh_tw_contracts_percentile_recommended_value'] = $recommended_value;
        $log_arr['lh_tw_contracts_percentile_current_value'] = implode(', ', $current_val).'<br>';

        $log_arr['tooltip_lh_tw_contracts_percentile'] = 'less or equal values from recommended value should not be exist in current values <br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['lh_lwb_contracts_percentile_buy'];
        $is_on = $global_setting_arr['lh_lwb_contracts_percentile_buy_apply'];
        $current_val = (array) $list_market_trends_arr['lh_lwb_contracts_percentile'];

        $rule_on_off = '<span style="background-color:yellow">OFF</span>';

        $recommended_arr = array();
        if ($recommended_value != '') {
            $x = 1;
            do {
                array_push($recommended_arr, $x);
                ++$x;
            } while ($x <= $recommended_value);
        }

        $check = array_intersect($recommended_arr, $current_val);

        $is_on_off_lh_lwb_contracts_percentile = true;
        if ($is_on == 'yes') {
            if (count($check) > 0 && count($recommended_arr) > 0) {
                $is_on_off_lh_lwb_contracts_percentile = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_lh_lwb_contracts_percentile = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['lh_lwb_contracts_percentile_on_off'] = $rule_on_off;
        $log_arr['lh_lwb_contracts_percentile_recommended_value'] = $recommended_value;
        $log_arr['lh_lwb_contracts_percentile_current_value'] = implode(', ', $current_val).'<br>';

        $log_arr['tooltip_lh_lwb_contracts_percentile'] = 'less or equal values from recommended value should  be exist in current values <br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['lh_lw_contracts_percentile_buy'];
        $is_on = $global_setting_arr['lh_lw_contracts_percentile_buy_apply'];
        $current_val = (array) $list_market_trends_arr['lh_lw_contracts_percentile'];

        $rule_on_off = '<span style="background-color:yellow">OFF</span>';

        $recommended_arr = array();
        if ($recommended_value != '') {
            $x = 1;
            do {
                array_push($recommended_arr, $x);
                ++$x;
            } while ($x <= $recommended_value);
        }

        $check = array_intersect($recommended_arr, $current_val);

        $is_on_off_lh_lw_contracts_percentile = true;
        if ($is_on == 'yes') {
            if (count($check) > 0 && count($recommended_arr) > 0) {
                $is_on_off_lh_lw_contracts_percentile = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_lh_lw_contracts_percentile = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['lh_lw_contracts_percentile_on_off'] = $rule_on_off;
        $log_arr['lh_lw_contracts_percentile_recommended_value'] = $recommended_value;
        $log_arr['lh_lw_contracts_percentile_current_value'] = implode(', ', $current_val).'<br>';

        $log_arr['tooltip_lh_lwb_contracts_percentile'] = 'less or equal values from recommended value should  be exist in current values <br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['top_wick_aggregate_buy'];
        $is_on = $global_setting_arr['top_wick_aggregate_buy_apply'];
        $current_val = $list_market_trends_arr['top_wick_aggregate'];

        $current_val = ($current_val == 1) ? 1 : 0;
        $compareVal = ($recommended_value == 'YES') ? 1 : 0;
        $is_on_off_top_wick_aggregate = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if (($current_val == $compareVal)) {
                $is_on_off_top_wick_aggregate = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_top_wick_aggregate = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['on_off_top_wick_aggregate'] = $rule_on_off;
        $log_arr['top_wick_aggregate_recommanded_value'] = $recommended_value;
        $log_arr['top_wick_aggregate_current_value'] = $current_val.'<br>';

        $log_arr['tooltip_top_wick_aggregate'] = 'Current Value  == Recommended Value<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $response['deep_price_on_off'] = $global_setting_arr['deep_price_percentage_buy_apply'];
        $response['deep_price_percentage_buy'] = $global_setting_arr['deep_price_percentage_buy'];
        $response['cancel_order_on_off'] = $global_setting_arr['cancel_order_hours_range_buy_apply'];
        $response['cancel_order_hours_range_buy'] = $global_setting_arr['cancel_order_hours_range_buy'];

        //%%%%%%%%%%%%%%%%%%%  End of market Trends %%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $recommended_value = $global_setting_arr['total_volume_percentile_buy'];
        $is_on = $global_setting_arr['total_volume_percentile_buy_apply'];
        $current_val = $list_market_trends_arr['lh_tv_percentile'];

        $is_on_off_total_volume_percentile = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            if (($current_val <= $compareVal)) {
                $is_on_off_total_volume_percentile = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $is_on_off_total_volume_percentile = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }
        $log_arr['on_off_total_volume_percentile'] = $rule_on_off;
        $log_arr['total_volume_percentile_recommanded_value'] = $recommended_value;
        $log_arr['total_volume_percentile_current_value'] = $current_val.'<br>';

        $log_arr['tooltip_total_volume_percentilee'] = 'Current Value  <= Recommended Value<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $is_on = $global_setting_arr['external_indicator_buy_apply'];
        $external_indicator_sign = $list_market_trends_arr['hassan_returned'];
        $external_indicator_level = $list_market_trends_arr['level'];

        $is_on_off_external_indicator = true;
        $recommended_value = '';
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($is_on == 'yes') {
            $external_arr = $this->get_external_indicator($order_level, $coin_symbol);
            $this->update_external_indicator($order_level, $coin_symbol);
            if (count($external_arr) > 0) {
                $is_on_off_external_indicator = true;
                $rule_on_off = '<span style="color:green">YES</span>';
                $recommended_value = 'YES';
            } else {
                $is_on_off_external_indicator = false;
                $rule_on_off = '<span style="color:red">NO</span>';
                $recommended_value = 'NO';
            }
        }
        $log_arr['on_off_total_external_indicator'] = $rule_on_off;
        $log_arr['external_indicator_value'] = $recommended_value.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $is_rules_true = 'NO';
        if ($last_200_contracts_qty_yes_no && $last_200_contracts_time_yes_no && $last_200_contracts_yes_no && $black_wall_yes_no && $virtual_barrier_yes_no && $seven_level_pressure_yes_no && $last_qty_contracts_time_yes_no && $is_buy_rule_on && $five_minute_rolling_candel_yes_no && $fifteen_minute_rolling_candel_yes_no && $meet_condition_for_buy && $buyers_yes_no && $sellers_yes_no && $candel_status_meet && $buy_percentile_yes_no && $ask_percentile_yes_no && $sell_percentile_yes_no && $bid_percentile_yes_no && $ask_contrc_percentile_yes_no && $bid_contrc_percentile_yes_no && $last_time_ago_15_m_yes_no && $virtual_barrier_ask_yes_no && $is_on_off_caption && $is_on_off_caption_score && $is_on_off_buy && $is_on_off_sell && $is_on_off_demand && $is_on_off_supply && $is_on_off_market_trende && $is_on_off_meta_trading && $is_on_off_riskpershare && $is_on_off_RL && $is_on_off_long_term_intension && $is_on_off_long_previous && $is_on_off_range && $is_on_off_deep_value_1 && $is_on_off_last_candle_total_volume && $is_on_off_big_contracts && $is_on_off_candle_move && $is_on_off_24_h_candle && $is_on_off_last_candle_side_1_h && $is_on_off_last_candle_side_24_h && $is_on_off_big_buyers_contracts && $is_on_off_big_sellers_contracts && $is_on_off_1_h_candle && $is_on_off_volume_increasing && $is_on_off_lh_tw_contracts && $is_on_off_lh_lw_contracts && $is_on_off_lh_lwb_contracts && $is_on_off_five_min_btc_change && $is_on_off_lh_tv_percentile && $is_on_off_curr_bc_percentile && $is_on_off_curr_lh_bc_qty && $is_on_off_lh_tw_contracts_percentile && $is_on_off_lh_lwb_contracts_percentile && $is_on_off_lh_lw_contracts_percentile && $is_on_off_top_wick_aggregate && $is_on_off_total_volume_percentile && $is_on_off_external_indicator) {
            $is_rules_true = 'YES';
        }
        //%%%%%%%%%%%%% -- Log Message -- %%%%%%%%%%%%
        $log_msg = '';
        foreach ($log_arr as $key => $value) {
            $log_msg .= $key.'=>'.$value.'<br>';
        }

        $response['success_message'] = $is_rules_true;
        $response['log_message'] = $log_msg;

        return $response;
    }

    //End of is_triggers_qualify_to_buy_orders

    public function is_triggers_qualify_to_sell_orders($coin_symbol, $order_level, $global_setting_arr, $current_market_price, $coin_meta_arr, $coin_meta_hourly_arr, $one_m_rolling_volume)
    {
        $black_wall_pressure = $coin_meta_arr['black_wall_pressure'];
        $seven_level_depth = $coin_meta_arr['seven_level_depth'];

        $five_minute_rolling_candel = $coin_meta_arr['sellers_buyers_per'];
        $fifteen_minute_rolling_candel = $coin_meta_arr['sellers_buyers_per_fifteen'];

        $last_200_buy_vs_sell = $coin_meta_arr['last_200_buy_vs_sell'];
        $last_200_time_ago = (float) $coin_meta_arr['last_200_time_ago'];
        $last_qty_buy_vs_sell = $coin_meta_arr['last_qty_buy_vs_sell'];
        $last_qty_time_ago = (float) $coin_meta_arr['last_qty_time_ago'];

        $last_qty_time_ago_15 = (float) $coin_meta_arr['last_qty_time_ago_15'];

        $buyers_fifteen = $coin_meta_arr['buyers_fifteen'];
        $sellers_fifteen = $coin_meta_arr['sellers_fifteen'];

        $log_arr['-'] = '<span style="color: green;font-size: 27px;">Sell Rules</span><br>';

        $enable_sell_barrier_percentile = $global_setting_arr['enable_sell_barrier_percentile'];
        if ($enable_sell_barrier_percentile == 'not' || $enable_sell_barrier_percentile == '') {
            $log_arr['Percentile_Level_'.$order_level.'_status'] = '<span style="color:red">OFF</span>';

            return $log_arr;
        }

        //%%%%%%%%%%%%%%%%%%% -- Barrier Status --%%%%%%%%%%%%%%%%%%%%%%%
        $last_barrrier_value = $this->triggers_trades->list_barrier_status($coin_symbol, 'very_strong_barrier', $current_market_price, 'up');

        $barrier_range_percentage = $global_setting_arr['barrier_percentile_trigger_barrier_range_percentage_sell'];
        $barrier_value_range_upside = $last_barrrier_value + ($last_barrrier_value / 100) * $barrier_range_percentage;
        $barrier_value_range_down_side = $last_barrrier_value - ($last_barrrier_value / 100) * $barrier_range_percentage;

        $meet_condition_for_sell = true;

        $rule_on_off = '<span style="background-color:yellow">OFF</span>';

        if ($barrier_range_percentage != '' || $barrier_range_percentage != 0) {
            if ((num($current_market_price) >= num($barrier_value_range_down_side)) && (num($current_market_price) <= num($barrier_value_range_upside))) {
                $meet_condition_for_sell = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $meet_condition_for_sell = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['is_Barrier_Meet'] = $rule_on_off;
        $log_arr['Last_Barrier_price'] = num($last_barrrier_value);
        $log_arr['Barrier_Range_percentage'] = $barrier_range_percentage;
        $log_arr['Barrier_Range'] = 'Barrir From <b>('.num($barrier_value_range_down_side).')</b> To  <b>('.num($barrier_value_range_upside).')</b><br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Buyers  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $barrier_percentile_trigger_buyers_buy = $global_setting_arr['barrier_percentile_trigger_buyers_sell'];
        $barrier_percentile_trigger_buyers_buy_apply = $global_setting_arr['barrier_percentile_trigger_buyers_sell_apply'];

        $buyers_recommended_percentile_value = $coin_meta_hourly_arr['buyers_fifteen_b_'.$barrier_percentile_trigger_buyers_buy];

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
        $log_arr['Buyer_current_value'] = $buyers_fifteen.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Sellers  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $seller_percentile = $global_setting_arr['barrier_percentile_trigger_sellers_sell'];
        $barrier_percentile_trigger_sellers_buy_apply = $global_setting_arr['barrier_percentile_trigger_sellers_sell_apply'];

        $sellers_recommended_percentile_value = $coin_meta_hourly_arr['sellers_fifteen_'.$seller_percentile];

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
        $log_arr['Sellers_current_value'] = $sellers_fifteen.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_15_minute_rolling_candel = $global_setting_arr['barrier_percentile_trigger_15_minute_rolling_candel'];
        $percentile_trigger_15_minute_rolling_candel_apply = $global_setting_arr['barrier_percentile_trigger_15_minute_rolling_candel_apply'];

        $percentile_trigger_15_minute_rolling_candel_actual_value = $coin_meta_hourly_arr['fifteen_min_b_'.$percentile_trigger_15_minute_rolling_candel];

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
        $log_arr['15_minute_rolling_candel_current_value'] = $fifteen_minute_rolling_candel.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_5_minute_rolling_candel = $global_setting_arr['barrier_percentile_trigger_5_minute_rolling_candel'];
        $percentile_trigger_5_minute_rolling_candel_apply = $global_setting_arr['barrier_percentile_trigger_5_minute_rolling_candel_apply'];

        $percentile_trigger_5_minute_rolling_candel_actual_value = $coin_meta_hourly_arr['five_min_b_'.$percentile_trigger_5_minute_rolling_candel];

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
        $log_arr['5_minute_rolling_candel_current_value'] = $five_minute_rolling_candel.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_black_wall = $global_setting_arr['barrier_percentile_trigger_sell_black_wall'];

        $percentile_trigger_black_wall_apply = $global_setting_arr['barrier_percentile_trigger_sell_black_wall_apply'];

        $percentile_trigger_black_wall_actual_value = $coin_meta_hourly_arr['blackwall_b_'.$percentile_trigger_black_wall];

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
        $log_arr['black_wall_current_value'] = $black_wall_pressure.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_virtual_barrier = $global_setting_arr['barrier_percentile_trigger_sell_virtual_barrier'];
        $percentile_trigger_virtual_barrier_apply = $global_setting_arr['barrier_percentile_trigger_sell_virtual_barrier_apply'];
        $barrrier_recommended_value = $coin_meta_hourly_arr['ask_quantity_'.$percentile_trigger_virtual_barrier];

        //%%%%%%%%%%%%%%%% -- Coin Unit Detail --%%%%%%%%%%%%%%%%%%%%%
        $coin_detail = $this->triggers_trades->get_coin_detail($coin_symbol);
        $coin_offset_value = $coin_detail['offset_value'];
        $coin_unit_value = $coin_detail['unit_value'];

        $total_ask_quantity = 0;
        for ($i = 0; $i < $coin_offset_value; ++$i) {
            $new_last_barrrier_value = (float) trim($current_market_price + ($coin_unit_value * $i));
            $ask = $this->triggers_trades->list_market_volume($new_last_barrrier_value, $coin_symbol, 'ask');
            $total_ask_quantity += $ask;
        } //End of Coin off Set

        //*********************************************************************/
        $virtual_barrier_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_virtual_barrier_apply == 'yes') {
            if ($total_ask_quantity >= $barrrier_recommended_value) {
                $virtual_barrier_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $virtual_barrier_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['virtual_ask_barrier_status'] = $rule_on_off;
        $log_arr['virtual_ask_barrier_recommended_percentile'] = $percentile_trigger_virtual_barrier;
        $log_arr['virtual_ask_barrier_recommended_percentile_value'] = $barrrier_recommended_value;
        $log_arr['virtual_ask_barrier_current_value'] = $total_ask_quantity.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% And of ask value %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%% -- Start of Bid -- %%%%%%%%%%%%%%%%%%%%%%%%%%%

        $percentile_trigger_virtual_barrier = $global_setting_arr['barrier_percentile_trigger_buy_virtual_barrier_for_sell'];
        $percentile_trigger_virtual_barrier_apply = $global_setting_arr['barrier_percentile_trigger_buy_virtual_barrier_for_sell_apply'];
        $barrrier_recommended_value = $coin_meta_hourly_arr['bid_quantity_b_'.$percentile_trigger_virtual_barrier];

        $total_bid_quantity = 0;
        for ($i = 0; $i < $coin_offset_value; ++$i) {
            $new_last_barrrier_value = (float) trim($current_market_price + ($coin_unit_value * $i));
            $bid = $this->triggers_trades->list_market_volume($new_last_barrrier_value, $coin_symbol, 'bid');
            $total_bid_quantity += $bid;
        } //End of Coin off Set

        //*********************************************************************/
        $virtual_barrier_bid_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($percentile_trigger_virtual_barrier_apply == 'yes') {
            if ($total_bid_quantity <= $barrrier_recommended_value) {
                $virtual_barrier_bid_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $virtual_barrier_bid_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['virtual_bid_barrier_status'] = $rule_on_off;
        $log_arr['virtual_bid_ask_barrier_recommended_percentile'] = $percentile_trigger_virtual_barrier;
        $log_arr['virtual_bid_ask_barrier_recommended_percentile_value'] = $barrrier_recommended_value;
        $log_arr['virtual_bid_ask_barrier_current_value'] = $total_bid_quantity.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% And of Bid value %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_seven_level_pressure = $global_setting_arr['barrier_percentile_trigger_sell_seven_level_pressure'];
        $percentile_trigger_seven_level_pressure_apply = $global_setting_arr['barrier_percentile_trigger_sell_seven_level_pressure_apply'];

        $seven_level_pressure_actual_value = $coin_meta_hourly_arr['sevenlevel_b_'.$percentile_trigger_seven_level_pressure];

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
        $log_arr['seven_level_pressure_current_value'] = $seven_level_depth.'<br>';
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
        $log_arr['last_200_buy_vs_sell_current_value'] = $last_200_buy_vs_sell.'<br>';
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
        $log_arr['last_200_time_ago_current_value'] = $last_200_time_ago.'<br>';
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
        $log_arr['last_qty_buy_vs_sell_current_value'] = $last_qty_buy_vs_sell.'<br>';
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
        $log_arr['last_qty_contracts_time_current_value'] = $last_qty_time_ago.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%% --  One Minute Rolling Candle  -- %%%%%%%%%%%%%%%%%%%%%

        $buy = $one_m_rolling_volume['buy'];
        $bid = $one_m_rolling_volume['bid'];
        $ask = $one_m_rolling_volume['ask'];
        $sell = $one_m_rolling_volume['sell'];

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Buy Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $buy_percentile_apply = $global_setting_arr['barrier_percentile_trigger_buy_sell_apply'];
        $buy_percentile = $global_setting_arr['barrier_percentile_trigger_buy_sell'];

        $buy_percentile_recommended_value = $coin_meta_hourly_arr['buy_'.$buy_percentile];
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

        $log_arr['Buy_percentile_status'] = $rule_on_off;
        $log_arr['Buy_percentile_recommended_percentile'] = $buy_percentile;
        $log_arr['Buy_percentile_recommended_percentile_value'] = $buy_percentile_recommended_value;
        $log_arr['Buy_percentile_current_value'] = $buy.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Ask Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $ask_percentile_apply = $global_setting_arr['barrier_percentile_trigger_ask_sell_apply'];
        $ask_percentile = $global_setting_arr['barrier_percentile_trigger_ask_sell'];
        $ask_percentile_recommended_value = $coin_meta_hourly_arr['ask_'.$ask_percentile];

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

        $log_arr['ask_percentile_status'] = $rule_on_off;
        $log_arr['ask_percentile_recommended_percentile'] = $ask_percentile;
        $log_arr['ask_percentile_recommended_percentile_value'] = $ask_percentile_recommended_value;
        $log_arr['ask_percentile_current_value'] = $ask.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Sell Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $sell_percentile_apply = $global_setting_arr['barrier_percentile_trigger_sell_rule_sell_apply'];
        $sell_percentile = $global_setting_arr['barrier_percentile_trigger_sell_rule_sell'];
        $sell_percentile_recommended_value = $coin_meta_hourly_arr['sell_b_'.$sell_percentile];

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

        $log_arr['sell_percentile_status'] = $rule_on_off;
        $log_arr['sell_percentile_recommended_percentile'] = $sell_percentile;
        $log_arr['sell_percentile_recommended_percentile_value'] = $sell_percentile_recommended_value;
        $log_arr['sell_percentile_current_value'] = $sell.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Bid Percentile Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $bid_percentile_apply = $global_setting_arr['barrier_percentile_trigger_bid_sell_apply'];
        $bid_percentile = $global_setting_arr['barrier_percentile_trigger_bid_sell'];

        $bid_percentile_recommended_value = $coin_meta_hourly_arr['bid_b_'.$bid_percentile];

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

        $log_arr['bid_percentile_status'] = $rule_on_off;
        $log_arr['bid_percentile_recommended_percentile'] = $bid_percentile;
        $log_arr['bid_percentile_recommended_percentile_value'] = $bid_percentile_recommended_value;
        $log_arr['bid_percentile_current_value'] = $bid.'<br>';

        //%%%%%%%%%%%%%%%%%%%%% End of Bid %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%% -- End of one Minute Rolling Candle  -- %%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Ask Contract  Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $ask_contract = (float) $coin_meta_arr['ask_contract'];
        $bid_contracts = (float) $coin_meta_arr['bid_contracts'];

        $ask_contrc_percentile_apply = $global_setting_arr['barrier_percentile_trigger_ask_contracts_sell_apply'];
        $ask_contrc_percentile = $global_setting_arr['barrier_percentile_trigger_ask_contracts_sell'];

        $ask_contrc_percentile_recommended_value = $coin_meta_hourly_arr['ask_contract_b_'.$ask_contrc_percentile];

        $ask_contrc_percentile_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($ask_contrc_percentile_apply == 'yes') {
            if ($ask_contract <= $ask_contrc_percentile_recommended_value) {
                $ask_contrc_percentile_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $ask_contrc_percentile_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['ask_contrc_percentile_status'] = $rule_on_off;
        $log_arr['ask_contrc_percentile_recommended_percentile'] = $ask_contrc_percentile;
        $log_arr['ask_contrc_percentile_recommended_percentile_value'] = $ask_contrc_percentile_recommended_value;
        $log_arr['ask_contrc_percentile_current_value'] = $ask_contract.'<br>';

        //%%%%%%%%%%%%%%%%%%%%% End of Ask Contracts %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  Bid Contract  Part  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $ask_contract = (float) $coin_meta_arr['ask_contract'];
        $bid_contracts = (float) $coin_meta_arr['bid_contracts'];

        $bid_contrc_percentile_apply = $global_setting_arr['barrier_percentile_trigger_bid_contracts_sell_apply'];
        $bid_contrc_percentile = $global_setting_arr['barrier_percentile_trigger_bid_contracts_sell'];

        $bid_contrc_percentile_recommended_value = $coin_meta_hourly_arr['bid_contracts_'.$bid_contrc_percentile];

        $bid_contrc_percentile_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($bid_contrc_percentile_apply == 'yes') {
            if ($bid_contracts >= $bid_contrc_percentile_recommended_value) {
                $bid_contrc_percentile_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $bid_contrc_percentile_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['bid_contrc_percentile_status'] = $rule_on_off;
        $log_arr['bid_contrc_percentile_recommended_percentile'] = $bid_contrc_percentile;
        $log_arr['bid_contrc_percentile_recommended_percentile_value'] = $ask_contrc_percentile_recommended_value;
        $log_arr['bid_contrc_percentile_current_value'] = $bid_contracts.'<br>';

        //%%%%%%%%%%%%%%%%%%%%% End of Bid Contracts %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%% -- 15 minute Ago -- %%%%%%%%%%%%%%%%%%%%%%

        $barrier_percentile_trigger_15_minute_last_time_ago_apply = $global_setting_arr['barrier_percentile_trigger_15_minute_last_time_ago_sell_apply'];
        $barrier_percentile_trigger_15_minute_last_time_ago = $global_setting_arr['barrier_percentile_trigger_15_minute_last_time_ago_sell'];

        $recommended_value = $coin_meta_hourly_arr['last_qty_time_ago_fif_'.$barrier_percentile_trigger_15_minute_last_time_ago];

        $last_time_ago_15_m_yes_no = true;
        $rule_on_off = '<span style="background-color:yellow">OFF</span>';
        if ($barrier_percentile_trigger_15_minute_last_time_ago_apply == 'yes') {
            if ($last_qty_time_ago_15 <= $recommended_value) {
                $last_time_ago_15_m_yes_no = true;
                $rule_on_off = '<span style="color:green">YES</span>';
            } else {
                $last_time_ago_15_m_yes_no = false;
                $rule_on_off = '<span style="color:red">NO</span>';
            }
        }

        $log_arr['lat_15_minute_time_ago_percentile_status'] = $rule_on_off;
        $log_arr['lat_15_minute_time_ago_recommended_percentile'] = $barrier_percentile_trigger_15_minute_last_time_ago;
        $log_arr['lat_15_minute_time_ago_recommended_percentile_value'] = $recommended_value;
        $log_arr['lat_15_minute_time_ago_percentile_current_value'] = $last_qty_time_ago_15.'<br>';

        //%%%%%%%%%%%%%%%%%%% -- End 15 minute Ago -- %%%%%%%%%%%%%%%%%%%%%%

        $is_rules_true = 'NO';
        if ($last_200_contracts_qty_yes_no && $last_200_contracts_time_yes_no && $last_200_contracts_yes_no && $black_wall_yes_no && $virtual_barrier_yes_no && $seven_level_pressure_yes_no && $last_qty_contracts_time_yes_no && $fifteen_minute_rolling_candel_yes_no && $five_minute_rolling_candel_yes_no && $buyers_yes_no && $sellers_yes_no && $buy_percentile_yes_no && $ask_percentile_yes_no && $sell_percentile_yes_no && $bid_percentile_yes_no && $ask_contrc_percentile_yes_no && $bid_contrc_percentile_yes_no && $last_time_ago_15_m_yes_no && $virtual_barrier_bid_yes_no && $meet_condition_for_sell) {
            $is_rules_true = 'YES';
        }

        $log_msg = '';
        foreach ($log_arr as $key => $value) {
            $log_msg .= $key.'=>'.$value.'<br>';
        }

        $response['success_message'] = $is_rules_true;
        $response['log_message'] = $log_msg;

        return $response;
    }

    //End of is_triggers_qualify_to_sell_orders

    public function is_triggers_qualify_to_update_stop_loss($coin_symbol, $order_level, $global_setting_arr, $current_market_price, $coin_meta_arr, $coin_meta_hourly_arr)
    {
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

        $buyers_recommended_percentile_value = $coin_meta_hourly_arr['buyers_fifteen_b_'.$barrier_percentile_trigger_buyers_buy];

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
        $log_arr['Buyer_current_value'] = $buyers_fifteen.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Buyers 1 minute Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $barrier_percentile_trigger_buyers_buy = $global_setting_arr['barrier_percentile_trigger_buyers1_minute_stop_loss'];
        $barrier_percentile_trigger_buyers_buy_apply = $global_setting_arr['barrier_percentile_trigger_buyers1_minute_stop_loss_apply'];

        $buyers_recommended_percentile_value = $coin_meta_hourly_arr['ask_b_'.$barrier_percentile_trigger_buyers_buy];

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
        $log_arr['Buyer_1_minute_current_value'] = $buyers_fifteen.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Sellers   Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $seller_percentile = $global_setting_arr['barrier_percentile_trigger_sellers_stop_loss'];
        $barrier_percentile_trigger_sellers_buy_apply = $global_setting_arr['barrier_percentile_trigger_sellers_stop_loss_apply'];

        $sellers_recommended_percentile_value = $coin_meta_hourly_arr['sellers_fifteen_'.$seller_percentile];

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
        $log_arr['Sellers_current_value'] = $sellers_fifteen.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Sellers  1 minute  Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $seller_percentile = $global_setting_arr['barrier_percentile_trigger_sellers_1_minute_stop_loss'];
        $barrier_percentile_trigger_sellers_buy_apply = $global_setting_arr['barrier_percentile_trigger_sellers_1_minute_stop_loss_apply'];

        $sellers_recommended_percentile_value = $coin_meta_hourly_arr['bid_'.$seller_percentile];

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
        $log_arr['Sellers_1_minute_current_value'] = $sellers_fifteen.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_15_minute_rolling_candel = $global_setting_arr['barrier_percentile_stop_loss_15_minute_rolling_candel_sell'];
        $percentile_trigger_15_minute_rolling_candel_apply = $global_setting_arr['barrier_percentile_stop_loss_15_minute_rolling_candel_sell_apply'];

        $percentile_trigger_15_minute_rolling_candel_actual_value = $coin_meta_hourly_arr['fifteen_min_b_'.$percentile_trigger_15_minute_rolling_candel];

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
        $log_arr['15_minute_rolling_candel_current_value'] = $fifteen_minute_rolling_candel.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_5_minute_rolling_candel = $global_setting_arr['barrier_percentile_stop_loss_5_minute_rolling_candel_sell'];

        $percentile_trigger_5_minute_rolling_candel_apply = $global_setting_arr['barrier_percentile_stop_loss_5_minute_rolling_candel_sell_apply'];

        $percentile_trigger_5_minute_rolling_candel_actual_value = $coin_meta_hourly_arr['five_min_b_'.$percentile_trigger_5_minute_rolling_candel];

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
        $log_arr['5_minute_rolling_candel_current_value'] = $five_minute_rolling_candel.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_black_wall = $global_setting_arr['barrier_percentile_stop_loss_black_wall'];
        $percentile_trigger_black_wall_apply = $global_setting_arr['barrier_percentile_stop_loss_black_wall_apply'];

        $percentile_trigger_black_wall_actual_value = $coin_meta_hourly_arr['blackwall_b_'.$percentile_trigger_black_wall];

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
        $log_arr['black_wall_current_value'] = $black_wall_pressure.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Done %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_virtual_barrier = $global_setting_arr['barrier_percentile_trigger_stop_loss_virtual_barrier'];
        $percentile_trigger_virtual_barrier_apply = $global_setting_arr['barrier_percentile_trigger_stop_loss_virtual_barrier_apply'];
        $barrrier_actual_value = $coin_meta_hourly_arr['bid_quantity_'.$percentile_trigger_virtual_barrier];

        //%%%%%%%%%%%%%%%% -- Coin Unit Detail --%%%%%%%%%%%%%%%%%%%%%

        $coin_detail = $this->triggers_trades->get_coin_detail($coin_symbol);
        $coin_offset_value = $coin_detail['offset_value'];
        $coin_unit_value = $coin_detail['unit_value'];

        $total_ask_quantity = 0;
        for ($i = 0; $i < $coin_offset_value; ++$i) {
            $new_last_barrrier_value = (float) trim($current_market_price + ($coin_unit_value * $i));
            $bid = $this->triggers_trades->list_market_volume($new_last_barrrier_value, $coin_symbol, 'ask');
            $total_ask_quantity += $bid;
        } //End of Coin off Set

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
        $log_arr['Ask_virtual_barrier_current_value'] = $total_ask_quantity.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%% Bid Part %%%%%%%%%%%%%%%%%%%%%%%%%%

        $percentile_trigger_virtual_barrier = $global_setting_arr['barrier_percentile_trigger_stop_loss_virtual_barrier_bid'];
        $percentile_trigger_virtual_barrier_apply = $global_setting_arr['barrier_percentile_trigger_stop_loss_virtual_barrier_bid_apply'];
        $barrrier_actual_value = $coin_meta_hourly_arr['bid_quantity_b_'.$percentile_trigger_virtual_barrier];

        $total_bid_quantity = 0;
        for ($i = 0; $i < $coin_offset_value; ++$i) {
            $new_last_barrrier_value = (float) trim($current_market_price - ($coin_unit_value * $i));
            $bid = $this->triggers_trades->list_market_volume($new_last_barrrier_value, $coin_symbol, 'bid');
            $total_bid_quantity += $bid;
        } //End of Coin off Set

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
        $log_arr['Bid_virtual_barrier_current_value'] = $total_bid_quantity.'<br>';

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%% End of bit part %%%%%%%%%%%%%%%%%%%

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Done  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $percentile_trigger_seven_level_pressure = $global_setting_arr['barrier_percentile_trigger_stop_loss_seven_level_pressure'];
        $percentile_trigger_seven_level_pressure_apply = $global_setting_arr['barrier_percentile_trigger_stop_loss_seven_level_pressure_apply'];

        $seven_level_pressure_actual_value = $coin_meta_hourly_arr['sevenlevel_b_'.$percentile_trigger_seven_level_pressure];

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
        $log_arr['seven_level_pressure_current_value'] = $seven_level_depth.'<br>';
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
        $log_arr['last_200_buy_vs_sell_current_value'] = $last_200_buy_vs_sell.'<br>';
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
        $log_arr['last_200_time_ago_current_value'] = $last_200_time_ago.'<br>';
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
        $log_arr['last_qty_buy_vs_sell_current_value'] = $last_qty_buy_vs_sell.'<br>';
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
        $log_arr['last_qty_contracts_time_current_value'] = $last_qty_time_ago.'<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $log_msg = '';
        foreach ($log_arr as $key => $value) {
            $log_msg .= $key.'=>'.$value.'<br>';
        }
        $is_rules_true = 'NO';
        if ($last_200_contracts_qty_yes_no && $last_200_contracts_time_yes_no && $last_200_contracts_yes_no && $black_wall_yes_no && $virtual_barrier_yes_no && $seven_level_pressure_yes_no && $last_qty_contracts_time_yes_no && $fifteen_minute_rolling_candel_yes_no && $five_minute_rolling_candel_yes_no && $buyers_yes_no && $sellers_yes_no) {
            $is_rules_true = 'YES';
        }

        $response['success_message'] = $is_rules_true;
        $response['log_message'] = $log_msg;

        return $response;
    }

    //End of is_triggers_qualify_to_update_stop_loss

    public function get_external_indicator($orderLevel, $coinSymbol)
    {
        $where['orderLevel'] = $orderLevel;
        $where['coinSymbol'] = $coinSymbol;
        $where['status'] = 0;
        $this->mongo_db->where($where);
        $externale_indecator_obj = $this->mongo_db->get('buy_rules_trade_data');
        $externale_indecator_arr = iterator_to_array($externale_indecator_obj);

        return $externale_indecator_arr;
    }

    //End of get_external_indicator

    public function update_external_indicator($orderLevel, $coinSymbol)
    {
        $where['orderLevel'] = $orderLevel;
        $where['coinSymbol'] = $coinSymbol;
        $where['status'] = 0;
        $this->mongo_db->where($where);
        $this->mongo_db->set(array('status' => 1));
        $this->mongo_db->update('buy_rules_trade_data');
        return true;
    }

    //End of update_external_indicator
} //End of mod_TEST_Barrier_trigger
