<?php
class mod_barrier_trigger extends CI_Model
{
    public function __construct()
    {
        # code...
    }

    public function get_coin_meta_data($symbol)
    {
        $this->mongo_db->where('coin', $symbol);
        $res = $this->mongo_db->get('coin_meta');
        return iterator_to_array($res);
    } //End of get_coin_meta_data

    public function is_big_trade($symbol)
    {
        $this->mongo_db->where('coin', $symbol);
        $res = $this->mongo_db->get('coin_meta');
        $res_arr = iterator_to_array($res);
        $ask_trades = $res_arr[0]['ask_contract'];
        $bid_trades = $res_arr[0]['bid_contracts'];
        $is_big = (($ask_trades > $bid_trades) ? 'YES' : 'NO');
        return $is_big;
    }

    public function is_up_pressure($symbol)
    {
        $this->mongo_db->where('coin', $symbol);
        $res = $this->mongo_db->get('coin_meta');
        $res_arr = iterator_to_array($res);
        $up_pressure = $res_arr[0]['up_pressure'];
        $down_pressure = $res_arr[0]['down_pressure'];
        $is_up = (($up_pressure > $down_pressure) ? 'up' : 'down');
        return $is_up;
    }

    public function pressure_calculate_from_coin_meta($symbol)
    {
        $this->mongo_db->where('coin', $symbol);
        $res = $this->mongo_db->get('coin_meta');
        $res_arr = iterator_to_array($res);
        $pressure = $res_arr[0]['pressure_diff'];
        return $pressure;
    } //End of pressure_calculate_from_coin_meta

    public function is_big_buyers($symbol)
    {
        $this->mongo_db->where('coin', $symbol);
        $res = $this->mongo_db->get('coin_meta');
        $res_arr = iterator_to_array($res);
        $buyers = $res_arr[0]['buyers'];
        $sellers = $res_arr[0]['sellers'];
        $is_big = (($buyers > $sellers) ? 'YES' : 'NO');
        return $is_big;
    }

    public function is_closest_bottom_wall($symbol)
    {
        $this->mongo_db->where('coin', $symbol);
        $res = $this->mongo_db->get('coin_meta');
        $res_arr = iterator_to_array($res);
        $ask_black_wall = $res_arr[0]['ask_black_wall'];
        $bid_black_wall = $res_arr[0]['bid_black_wall'];
        $market_value = $res_arr[0]['current_market_value'];
        $ask_diff = $ask_black_wall - $market_value;
        $bid_diff = $market_value - $bid_black_wall;
        $is_big = (($bid_diff < $ask_diff) ? 'YES' : 'NO');
        return $is_big;
    }

    public function is_big_pressure_up($symbol)
    {
        $this->mongo_db->where('coin', $symbol);
        $res = $this->mongo_db->get('coin_meta');
        $res_arr = iterator_to_array($res);
        $big_pressure = $res_arr[0]['great_wall'];
        $is_big = (($big_pressure == 'downside') ? 'YES' : 'NO');
        return $is_big;
    } //End Of is_big_pressure_up

    public function get_last_bottom_barrrier_value($coin, $current_market_price)
    {
        $this->mongo_db->order_by(array('created_date' => -1));
        $this->mongo_db->limit(1);
        $this->mongo_db->where_lte('barier_value', (float) ($current_market_price));
        $this->mongo_db->where(array('coin' => $coin, 'barrier_type' => 'down', 'barrier_status' => 'very_strong_barrier'));
        $responseobj = $this->mongo_db->get('barrier_values_collection');
        $responseArr = iterator_to_array($responseobj);
        $barrier_value = '';
        if (count($responseArr) > 0) {
            $barrier_value = $responseArr[0]['barier_value'];
        }
        return $barrier_value;
    } //End o f get_last_bottom_barrrier_value

    public function get_last_top_barrier_value($current_market_price, $coin_symbol)
    {
        $this->mongo_db->order_by(array('created_date' => -1));
        $this->mongo_db->limit(1);
        $this->mongo_db->where_gte('barier_value', (float) $current_market_price);
        $this->mongo_db->where(array('coin' => $coin_symbol, 'barrier_type' => 'up', 'barrier_status' => 'very_strong_barrier'));
        $responseobj = $this->mongo_db->get('barrier_values_collection');
        $responseArr = iterator_to_array($responseobj);
        $barrier_value = '';
        if (count($responseArr) > 0) {
            $barrier_value = $responseArr[0]['barier_value'];
        }

        return $barrier_value;
    } //End of get_last_top_barrier_value

    public function check_barrier_is_greater($defined_quantity, $current_market_price, $coin)
    {
        $this->mongo_db->order_by(array('created_date' => -1));
        $this->mongo_db->limit(1);
        $this->mongo_db->where(array('coin' => $coin, 'price' => $current_market_price, 'type' => 'bid'));
        //$this->mongo_db->where_gte('quantity',$defined_quantity);
        $res_obj = $this->mongo_db->get('market_depth');
        $res_arr = iterator_to_array($res_obj);
        $respone = false;

        if (count($res_arr) > 0) {
            if ($res_arr[0]['quantity'] >= $defined_quantity) {
                $respone = $res_arr[0]['quantity'];
            }
        }

        return $respone;
    } //End check_barrier_is_greater

    public function barrier_trade_tracker($coin, $date)
    {
        $ins_arr = array('coin' => $coin, 'created_date' => $this->mongo_db->converToMongodttime($date), 'order_make' => 'yes', 'date_human_readible' => $date);
        $this->mongo_db->insert('barrier_trade_tracker', $ins_arr);
        return true;
    }

    public function check_barrier_trade_tracker($coin, $date)
    {
        $where = array('coin' => $coin, 'created_date' => $this->mongo_db->converToMongodttime($date), 'order_make' => 'yes');
        $this->mongo_db->where($where);
        $res_obj = $this->mongo_db->get('barrier_trade_tracker');
        $res_arr = iterator_to_array($res_obj);
        $response = true;
        if (count($res_arr) > 0) {
            $response = false;
        }
        return $response;
    } //End of

    public function get_parent_orders($coin_symbol)
    {
        $order_mode = array('live');
        $this->mongo_db->where_ne('inactive_status', 'inactive');
        $this->mongo_db->where_ne('pause_status', 'pause');
        $this->mongo_db->where_in('order_mode', $order_mode);
        $this->mongo_db->where(array('status' => 'new', 'trigger_type' => 'barrier_trigger', 'symbol' => $coin_symbol, 'parent_status' => 'parent'));
        $parent_orders_object = $this->mongo_db->get('buy_orders');
        return iterator_to_array($parent_orders_object);
    } //End of get_parent_orders

    //insert_order_history_log
    public function insert_order_history_log($id, $log_msg, $type, $user_id, $created_date, $show_error_log = 'no')
    {
        $created_date = date('Y-m-d G:i:s');
        $ins_error = array(
            'order_id' => $this->mongo_db->mongoId($id),
            'log_msg' => $log_msg,
            'type' => $type,
            'show_error_log' => $show_error_log,
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
        );
        $this->mongo_db->insert('orders_history_log', $ins_error);

        return true;
    } //end insert_order_history_log

    public function insert_order_history_log_bam($id, $log_msg, $type, $user_id, $created_date, $show_error_log = 'no')
    {
        $created_date = date('Y-m-d G:i:s');
        $ins_error = array(
            'order_id' => $this->mongo_db->mongoId($id),
            'log_msg' => $log_msg,
            'type' => $type,
            'show_error_log' => $show_error_log,
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
        );
        $this->mongo_db->insert('orders_history_log_bam', $ins_error);

        return true;
    } //end insert_order_history_log_bam

    //add_notification
    public function add_notification($order_id, $type, $message, $admin_id)
    {
        extract($data);

        $created_date = date('Y-m-d G:i:s');

        $ins_data = array(
            'admin_id' => $this->db->escape_str(trim($admin_id)),
            'order_id' => $this->db->escape_str(trim($order_id)),
            'type' => $this->db->escape_str(trim($type)),
            'message' => $this->db->escape_str(trim($message)),
            'created_date' => $this->db->escape_str(trim($created_date)),
        );

        //Insert the record into the database.

        $this->mongo_db->insert('notification', $ins_data);

        return true;
    } //end add_notification()

    public function is_market_direction_down($barrier_value, $global_symbol)
    {
        $this->mongo_db->where_ne('price', (float) $barrier_value);
        $this->mongo_db->where(array('coin' => $global_symbol));
        $this->mongo_db->limit(1);
        $this->mongo_db->sort(array('_id' => -1));
        $responseObj = $this->mongo_db->get('market_prices');
        $res_arr = iterator_to_array($responseObj);

        $previous_price = 0;
        if (count($res_arr) > 0) {
            $previous_price = $res_arr[0]['price'];
        }

        $response = false;
        if ($previous_price > $barrier_value) {
            $response = true;
        }
        return $response;
    } //End of get_market_direction

    public function get_previous_market_value($global_symbol, $current_market_value)
    {
        $this->mongo_db->where_gt('market_value', (float) $current_market_value);
        $this->mongo_db->where(array('coin' => $global_symbol));
        $this->mongo_db->limit(1);
        $this->mongo_db->order_by(array('time' => -1));
        $responseObj = $this->mongo_db->get('market_price_history');
        $res_arr = iterator_to_array($responseObj);
        $previous_price = 0;
        if (count($res_arr) > 0) {
            $previous_price = $res_arr[0]['market_value'];
        }
        return $previous_price;
    } //End of get_previous_market_value

    public function get_stop_loss_orders($market_price, $coin_symbol)
    {
        $this->mongo_db->where_ne('is_sell_order', 'sold');
        $this->mongo_db->where_in('order_mode', array('live'));
        $this->mongo_db->where(array('status' => 'FILLED', 'trigger_type' => 'barrier_trigger', 'symbol' => $coin_symbol));
        $this->mongo_db->where_gte('iniatial_trail_stop', $market_price);
        $buy_orders_result = $this->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End of get_stop_loss_orders

    public function get_profit_sell_orders($target_sell_price, $coin_symbol)
    {
        $this->mongo_db->where_ne('is_sell_order', 'sold');
        $this->mongo_db->where_ne('parent_status', 'parent');
        $this->mongo_db->where_in('order_mode', array('live'));
        $this->mongo_db->where(array('status' => 'FILLED', 'trigger_type' => 'barrier_trigger', 'symbol' => $coin_symbol));
        // $this->mongo_db->where_lte('market_value',$target_sell_price);
        $buy_orders_result = $this->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End of get_profit_sell_orders

    public function get_trigger_setting()
    {
        $this->mongo_db->where(array('triggers_type' => 'barrier_trigger', 'order_mode' => 'live'));
        $response_obj = $this->mongo_db->get('trigger_global_setting');
        return iterator_to_array($response_obj);
    } //End of get_trigger_setting

    public function sell_contract_percentage($symbol)
    {
        $this->mongo_db->where('coin', $symbol);
        $res = $this->mongo_db->get('coin_meta');
        $res_arr = iterator_to_array($res);
        $bid_trades = $res_arr[0]['bid_percentage'];
        return $bid_trades;
    } //End of sell_contract_percentage

    public function is_down_pressure_for_sell($symbol)
    {
        $this->mongo_db->where('coin', $symbol);
        $res = $this->mongo_db->get('coin_meta');
        $res_arr = iterator_to_array($res);
        $up_pressure = $res_arr[0]['up_pressure'];
        $down_pressure = $res_arr[0]['down_pressure'];
        $is_up = (($up_pressure == 1) ? 'down' : 'up');
        return $is_up;
    } //End of

    public function is_closest_top_wall($symbol)
    {
        $this->mongo_db->where(array('coin' => $symbol));
        $res_coin_meta_obj = $this->mongo_db->get('coin_meta');
        $res_coin_meta_arr = iterator_to_array($res_coin_meta_obj);
        $black_wall_pressure = 0;
        if (count($res_coin_meta_arr) > 0) {
            $black_wall_pressure = $res_coin_meta_arr[0]['black_wall_pressure'];
        }
        return $black_wall_pressure;
    } //End of is_closest_top_wall

    public function buy_contract_percentage($symbol)
    {
        $this->mongo_db->where('coin', $symbol);
        $res = $this->mongo_db->get('coin_meta');
        $res_arr = iterator_to_array($res);
        $ask_trades = $res_arr[0]['ask_percentage'];
        return $ask_trades;
    } //End of buy_contract_percentage

    public function previous_market_price_for_top_barrier($current_market_price, $coin_symbol)
    {
        $this->mongo_db->where_lt('market_value', (float) $current_market_price);
        $this->mongo_db->where(array('coin' => $coin_symbol));
        $this->mongo_db->limit(1);
        $this->mongo_db->order_by(array('market_value' => -1));
        $responseObj = $this->mongo_db->get('market_price_history');
        $res_arr = iterator_to_array($responseObj);

        $previous_price = 0;
        if (count($res_arr) > 0) {
            $previous_price = $res_arr[0]['market_value'];
        }
        return $previous_price;
    } //End of previous_market_price_for_top_barrier

    public function seven_level_pressure_sell($symbol)
    {
        $this->mongo_db->where('coin', $symbol);
        $res = $this->mongo_db->get('coin_meta');
        $res_arr = iterator_to_array($res);
        $seven_level_depth = $res_arr[0]['seven_level_depth'];
        $seven_level_type = $res_arr[0]['seven_level_type'];
        $value = (float) $seven_level_depth;
        return $value;
    } //End of seven_level_pressure_sell

    public function get_stop_loss_updating_orders($trigger_type, $coin_symbol)
    {
        $this->mongo_db->where_ne('is_sell_order', 'sold');
        $this->mongo_db->where_in('order_mode', array('test_live', 'live'));
        $where['status'] = 'FILLED';
        $where['trigger_type'] = $trigger_type;
        $where['symbol'] = $coin_symbol;
        $where['is_order_ready_for_stop_loss'] = 'yes';
        $this->mongo_db->where($where);
        $buy_orders_result = $this->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End of get_stop_loss_updating_orders

    public function is_market_deep_price_order($market_price, $coin_symbol, $type)
    {
        if ($type == 'user_select') {
            $where['stop_loss_rule'] = 'stop_loss_rule_big_wall';
        }
        $where['status'] = 'FILLED';
        $where['symbol'] = $coin_symbol;
        $where['trigger_type'] = 'barrier_trigger';
        $where['is_sell_order'] = array('$ne' => 'sold');
        $where['order_mode'] = array('$in' => array('test_live', 'live'));

        $where['$or'] = array(array('price' => array('$gt' => $market_price)), array('market_deep_price' => array('$gt' => $market_price)));

        $this->mongo_db->where($where);
        $buy_orders_result = $this->mongo_db->get('buy_orders');
        $order_arr = iterator_to_array($buy_orders_result);

        $upd_data = array(
            'market_deep_price' => (float) $market_price,
        );

        $created_date = date('Y-m-d G:i:s');
        if (!empty($order_arr)) {
            foreach ($order_arr as $row) {
                $id = $row['_id'];
                $admin_id = $row['admin_id'];
                $market_deep_price = $row['market_deep_price'];
                //Check of Custom stop loss Then do not Update it
                if ($row['stop_loss_rule'] == 'custom_stop_loss') {
                    return false;
                }

                if ($market_price < $market_deep_price || empty($market_deep_price)) {
                    $this->mongo_db->where(array('_id' => $id));
                    $this->mongo_db->set($upd_data);
                    $this->mongo_db->update('buy_orders');

                    $from = '';
                    if (!empty($market_deep_price)) {
                        $from = num($market_deep_price);
                    }

                    $log_msg = " Market Deep Price Updated From " . $from . " To " . num($market_price);
                    $this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $created_date);
                } //if market price less then deeper price
            } //End of foreach
        } //End of check empty
    } //End of is_market_deep_price_order

    public function stop_loss_big_wall_barrier_trigger($coin_symbol, $sell_profit_percet, $current_market_price, $type)
    {
        $trigger_type = 'barrier_trigger';
        $big_wall_data = $this->get_down_big_price($coin_symbol);
        $level_five_big_wall_price = $big_wall_data['level_five_big_wall_price'];
        $level_three_big_wall_price = $big_wall_data['level_three_big_wall_price'];
        $level_two_big_wall_price = $big_wall_data['level_two_big_wall_price'];

        $orders_arr = $this->get_stop_loss_updating_orders($trigger_type, $coin_symbol);

        if (count($orders_arr) > 0) {
            foreach ($orders_arr as $data) {
                $id = $data['_id'];
                $admin_id = $data['admin_id'];
                $buy_price = $data['market_value'];
                $iniatial_trail_stop = $data['iniatial_trail_stop'];
                $iniatial_trail_stop_copy = $data['iniatial_trail_stop_copy'];
                $sell_price = $data['sell_price'];
                $coin_symbol = $data['symbol'];
                $trigger_type = $data['trigger_type'];
                $created_date = date('Y-m-d G:i:s');
                $is_market_deep_range = $data['is_order_ready_for_stop_loss'];

                $sell_order_id = (string) $data['sell_order_id'];

                $is_sell_order_is_submitted = $this->is_sell_order_is_submitted($sell_order_id);

                if ($is_sell_order_is_submitted) {
                    return false;
                }

                $market_deep_true = false;
                if ($is_market_deep_range == 'yes') {
                    $market_deep_true = true;
                }

                $market_deep_price = 0;
                if (isset($data['iniatial_trail_stop']) && $data['iniatial_trail_stop'] != '') {
                    $market_deep_price = $data['iniatial_trail_stop'];
                }

                $one_percent_profit = (float) $buy_price + (($buy_price / 100) * 1);
                $two_percent_profit = (float) $buy_price + (($buy_price / 100) * 2);
                $three_percent_profit = (float) $buy_price + (($buy_price / 100) * 3);

                //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                if ($market_deep_true) {
                    if ($current_market_price >= $three_percent_profit) {
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        if (($level_two_big_wall_price > $iniatial_trail_stop)) {
                            ///////////////////End of update trail stop
                            ///////////////////////////////////////////
                            $upd_data22 = array(
                                'iniatial_trail_stop' => (float) $level_two_big_wall_price,
                            );
                            $this->mongo_db->where(array('_id' => $id));
                            $this->mongo_db->set($upd_data22);
                            $this->mongo_db->update('buy_orders');
                            $log_msg = "  Order Stop Loss Big Wall Updated By <b>DC 2</b> From  " . num($iniatial_trail_stop) . " To " . num($level_two_big_wall_price);
                            $this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $created_date);
                        } //End of if trail stop greater
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    } elseif ($current_market_price >= $two_percent_profit) {
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        if (($level_three_big_wall_price > $iniatial_trail_stop)) {

                            ///////////////////End of update trail stop
                            ///////////////////////////////////////////
                            $upd_data22 = array(
                                'iniatial_trail_stop' => (float) $level_three_big_wall_price,
                            );

                            $this->mongo_db->where(array('_id' => $id));
                            $this->mongo_db->set($upd_data22);
                            $this->mongo_db->update('buy_orders');

                            $log_msg = "  Order Stop Loss Big Wall Updated By <b>DC 3</b> From  " . num($iniatial_trail_stop) . " To " . num($level_three_big_wall_price);
                            $this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $created_date);
                        } //End of if trail stop greater
                    } elseif ($current_market_price >= $one_percent_profit) {
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        if (($level_five_big_wall_price > $iniatial_trail_stop)) {

                            ///////////////////End of update trail stop
                            ///////////////////////////////////////////
                            $upd_data22 = array(
                                'iniatial_trail_stop' => (float) $level_five_big_wall_price,
                            );

                            $this->mongo_db->where(array('_id' => $id));
                            $this->mongo_db->set($upd_data22);
                            $this->mongo_db->update('buy_orders');

                            $log_msg = "  Order Stop Loss Big Wall Updated By <b>DC 5</b> From  " . num($iniatial_trail_stop) . " To " . num($level_five_big_wall_price);
                            $this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $created_date);
                        } //End of if trail stop greater
                    }
                } //%%%%%%%%%%%%%%%%% -- End of is Market Deep True -- %%%%%%%%%%%

                //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
            } //End of order for each
        } //End of if order exist
    } //End of aggrisive_define_percentage_followup

    public function is_sell_order_is_submitted($sell_order_id)
    {
        $this->mongo_db->where(array('_id' => $sell_order_id, 'status' => 'submitted'));
        $data = $this->mongo_db->get('orders');
        $resp = false;
        $row = iterator_to_array($data);
        if (count($row) > 0) {
            $resp = true;
        }
        return $resp;
    } //End of is_sell_order_is_submitted

    public function get_down_big_price($coin_symbol)
    {
        $this->mongo_db->where(array('coin' => $coin_symbol));
        $res_coin_meta_obj = $this->mongo_db->get('coin_meta');
        $res_coin_meta_arr = iterator_to_array($res_coin_meta_obj);

        $level_five_big_wall_price = 0;
        $level_two_big_wall_price = 0;
        $level_three_big_wall_price = 0;
        if (count($res_coin_meta_arr) > 0) {
            $level_five_big_wall_price = $res_coin_meta_arr[0]['down_big_price'];
            $level_two_big_wall_price = $res_coin_meta_arr[0]['level_two_big_wall_price'];
            $level_three_big_wall_price = $res_coin_meta_arr[0]['level_three_big_wall_price'];
        }

        $response['level_five_big_wall_price'] = $level_five_big_wall_price;
        $response['level_three_big_wall_price'] = $level_three_big_wall_price;
        $response['level_two_big_wall_price'] = $level_two_big_wall_price;
        return $response;
    } //End of get_down_big_price

    public function level_five_big_wall_price($coin_symbol)
    {
        $this->mongo_db->where(array('coin' => $coin_symbol));
        $res_coin_meta_obj = $this->mongo_db->get('coin_meta');
        $res_coin_meta_arr = iterator_to_array($res_coin_meta_obj);

        $level_five_big_wall_price = 0;
        $level_two_big_wall_price = 0;
        $level_three_big_wall_price = 0;
        if (count($res_coin_meta_arr) > 0) {
            $level_five_big_wall_price = $res_coin_meta_arr[0]['down_big_price'];
        }

        return $level_five_big_wall_price;
    } //End of level_five_big_wall_price

    public function get_filled_orders_live($trigger_type)
    {
        $this->mongo_db->where_ne('is_sell_order', 'sold');
        $this->mongo_db->where_in('order_mode', array('test_live', 'live'));
        $this->mongo_db->where(array('status' => 'FILLED', 'trigger_type' => $trigger_type));
        $buy_orders_result = $this->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End OF get_filled_orders_live

    public function get_black_closet_wall($coin_symbol)
    {
        $this->mongo_db->where(array('coin' => $coin_symbol));
        $res_coin_meta_obj = $this->mongo_db->get('coin_meta');
        $res_coin_meta_arr = iterator_to_array($res_coin_meta_obj);
        $black_closet_wall = 0;
        if (count($res_coin_meta_arr) > 0) {
            $black_closet_wall = $res_coin_meta_arr[0]['black_wall_pressure'];
        }
        return $black_closet_wall;
    } //get_black_closet_wall

    public function get_yellow_closet_wall($coin_symbol)
    {
        $this->mongo_db->where(array('coin' => $coin_symbol));
        $res_coin_meta_obj = $this->mongo_db->get('coin_meta');
        $res_coin_meta_arr = iterator_to_array($res_coin_meta_obj);
        $yellow_wall_pressure = 0;
        if (count($res_coin_meta_arr) > 0) {
            $yellow_wall_pressure = $res_coin_meta_arr[0]['yellow_wall_pressure'];
        }
        return $yellow_wall_pressure;
    } //get_black_closet_wall

    public function get_current_swing_point($coin_symbol)
    {
        $this->mongo_db->order_by(array('timestampDate' => -1));
        $this->mongo_db->limit(1);
        $this->mongo_db->where(array('coin' => $coin_symbol));
        $responseobj = $this->mongo_db->get('market_chart');
        $responseArr = iterator_to_array($responseobj);

        $global_swing_status = '';
        if (count($responseArr) > 0) {
            $global_swing_status = $responseArr[0]['global_swing_status'];
        }

        return $global_swing_status;
    } //End get_current_swing_point

    public function barrier_status_down($coin_symbol, $barrier_status, $c_price)
    {
        $this->mongo_db->limit(1);
        $this->mongo_db->where_lte('barier_value', (float) $c_price);
        $this->mongo_db->order_by(array('created_date' => -1));
        $this->mongo_db->where(array('coin' => $coin_symbol, 'barrier_status' => $barrier_status, 'barrier_type' => 'down'));
        $res_obj = $this->mongo_db->get('barrier_values_collection');
        $res_arr = iterator_to_array($res_obj);

        $data = array();
        if (count($res_arr) > 0) {
            $row = $res_arr[0];
            $data['barrier_status'] = $row['barrier_status'];
            $data['barier_value'] = $row['barier_value'];
            $data['human_readible_created_date'] = $row['human_readible_created_date'];
        } //End of Count
        return $data;
    } //End of barrier_status

    public function barrier_status_up($coin_symbol, $barrier_status, $current_market_price)
    {
        $this->mongo_db->limit(1);
        $this->mongo_db->where_gte('barier_value', (float) $current_market_price);
        $this->mongo_db->order_by(array('created_date' => -1));
        $this->mongo_db->where(array('coin' => $coin_symbol, 'barrier_status' => $barrier_status, 'barrier_type' => 'up'));
        $res_obj = $this->mongo_db->get('barrier_values_collection');
        $res_arr = iterator_to_array($res_obj);

        $data = array();
        if (count($res_arr) > 0) {
            $row = $res_arr[0];
            $data['barrier_status'] = $row['barrier_status'];
            $data['barier_value'] = $row['barier_value'];
            $data['human_readible_created_date'] = $row['human_readible_created_date'];
        } //End of Count
        return $data;
    } //End of barrier_status

    public function get_current_barrier_status($coin_symbol, $c_price)
    {
        $barrier_status_arr = array('weak_barrier', 'very_strong_barrier', 'strong_barrier');
        $full_arr = array();
        foreach ($barrier_status_arr as $barrier_status) {
            $res = $this->barrier_status_down($coin_symbol, $barrier_status, $c_price);
            $full_arr[] = $res;
        }
        foreach ($full_arr as $key => $part) {
            $sort[$key] = strtotime($part['human_readible_created_date']);
        }
        array_multisort($sort, SORT_DESC, $full_arr);
        return $full_arr;
    } //End get_current_swing_point

    public function get_current_barrier_status_up($coin_symbol, $current_market_price)
    {
        $barrier_status_arr = array('weak_barrier', 'very_strong_barrier', 'strong_barrier');
        $full_arr = array();
        foreach ($barrier_status_arr as $barrier_status) {
            $res = $this->barrier_status_up($coin_symbol, $barrier_status, $current_market_price);
            $full_arr[] = $res;
        }
        foreach ($full_arr as $key => $part) {
            $sort[$key] = strtotime($part['human_readible_created_date']);
        }
        array_multisort($sort, SORT_DESC, $full_arr);
        return $full_arr;
    } //End get_current_swing_point

    public function get_market_volume($market_price, $coin_symbol, $type)
    {
        $price = trim($market_price);
        $price_r = (float) $price;
        $res = array('coin' => $coin_symbol, 'type' => $type, 'price' => $price_r);
        $this->mongo_db->where(array('coin' => $coin_symbol, 'type' => $type, 'price' => $price_r));
        $responseobj = $this->mongo_db->get('market_depth');
        $responseArr = iterator_to_array($responseobj);
        $global_quantity = '';
        if (count($responseArr) > 0) {
            $global_quantity = $responseArr[0]['quantity'];
        }
        return $global_quantity;
    }

    public function get_bottom_closet_wall($coin_symbol)
    {
        $this->mongo_db->where(array('coin' => $coin_symbol));
        $res_coin_meta_obj = $this->mongo_db->get('coin_meta');
        $res_coin_meta_arr = iterator_to_array($res_coin_meta_obj);
        $black_wall_pressure = 0;
        if (count($res_coin_meta_arr) > 0) {
            $black_wall_pressure = $res_coin_meta_arr[0]['black_wall_pressure'];
        }
        return $black_wall_pressure;
    } //get_black_closet_wall

    public function get_buyer_vs_seller_rule($coin_symbol)
    {
        $this->mongo_db->where(array('coin' => $coin_symbol));
        $res_coin_meta_obj = $this->mongo_db->get('coin_meta');
        $res_coin_meta_arr = iterator_to_array($res_coin_meta_obj);
        $buyer_vs_seller = 0;
        if (count($res_coin_meta_arr) > 0) {
            $res_coin_meta_arr = $res_coin_meta_arr[0];
            $sellers_buyers_per = $res_coin_meta_arr['sellers_buyers_per'];
            $trade_type = $res_coin_meta_arr['trade_type'];
        }
        return $sellers_buyers_per;
    } //get_buyer_vs_seller_rule

    public function insert_developer_log($id, $log_msg, $type, $created_date, $show_error_log)
    {
        $ins_error = array(
            'order_id' => $this->mongo_db->mongoId($id),
            'log_msg' => $log_msg,
            'type' => $type,
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
            'show_error_log' => $show_error_log,
        );
        $this->mongo_db->insert('orders_history_log', $ins_error);
        return true;
    } //End of insert_developer_log

    public function get_trigger_global_setting($triggers_type, $order_mode, $coin)
    {
        $this->mongo_db->where(array('triggers_type' => $triggers_type, 'order_mode' => $order_mode, 'coin' => $coin));
        $response_obj = $this->mongo_db->get('trigger_global_setting');
        return iterator_to_array($response_obj);
    } //End of get_trigger_global_setting

    public function calculate_pressure($market_sell_depth_arr, $market_buy_depth_arr)
    {
        $pressure_up = 0;
        $pressure_down = 0;
        array_multisort(array_column($market_buy_depth_arr, "price"), SORT_ASC, $market_buy_depth_arr);
        array_multisort(array_column($market_sell_depth_arr, "price"), SORT_DESC, $market_sell_depth_arr);
        for ($i = 0; $i < 5; $i++) {
            $ret_Arr = array();
            if ($market_sell_depth_arr[$i]['depth_sell_quantity'] > $market_buy_depth_arr[$i]['depth_buy_quantity']) {
                $pressure_up++;
            } elseif ($market_sell_depth_arr[$i]['depth_sell_quantity'] < $market_buy_depth_arr[$i]['depth_buy_quantity']) {
                $pressure_down++;
            }
        }

        $result = array("up" => $pressure_up, 'down' => $pressure_down);

        return $result;
    } //End of calculate_pressure

    public function check_of_previous_buy_order_exist_for_current_user($admin_id, $buy_parent_id)
    {
        $this->mongo_db->where_ne('is_sell_order', 'sold');
        $this->mongo_db->where_ne('parent_status', 'parent');
        $this->mongo_db->where_in('order_mode', array('test_live', 'live'));
        $this->mongo_db->where_in('status', array('new', 'FILLED', 'submitted'));
        $this->mongo_db->where(array('trigger_type' => 'barrier_trigger', 'admin_id' => $admin_id, 'buy_parent_id' => $buy_parent_id));
        $this->mongo_db->limit(1);
        $buy_orders_result = $this->mongo_db->get('buy_orders');
        $buy_orders_arr = iterator_to_array($buy_orders_result);

        $response = true;
        if (count($buy_orders_arr) > 0) {
            $response = false;
        }
        return $response;
    } //End of check_of_previous_buy_order_exist_for_current_user

    public function order_setting($coin_symbol, $triggers_type, $order_mode)
    {
        $this->mongo_db->where(array('coins' => $coin_symbol, 'triggers_type' => $triggers_type, 'order_mode' => $order_mode));
        $res_coin_setting = $this->mongo_db->get('setting_triggers_collections');
        return iterator_to_array($res_coin_setting);
    } //End of order_setting

    public function save_rules_for_orders($buy_order_id, $coin_symbol, $order_type, $rule, $mode)
    {
        $_date = date('Y-m-d H:i:s');
        $date = $this->mongo_db->converToMongodttime($_date);
        $arr = array('buy_order_id' => $buy_order_id, 'coin_symbol' => $coin_symbol, 'order_type' => $order_type, 'rule' => $rule, 'mode' => $mode, 'created_date' => $date, 'human_readible_created_date' => $_date);
        $this->mongo_db->insert('record_of_rules_for_orders', $arr);
    } //End of save_rules_for_orders

    public function save_order_time_track($coin, $type)
    {
        $_date = date('Y-m-d H:i:s');
        $date = $this->mongo_db->converToMongodttime($_date);
        $insert_arr = array('coin' => $coin, 'created_time_obj' => $date, 'human_readible_created_time' => $_date, 'type' => $type);
        $this->mongo_db->insert('order_time_track_collection', $insert_arr);
    } //End of save_order_time_track

    public function is_order_is_created_just_now($coin, $type)
    {
        $prevouse_date = date('Y-m-d H:i:s', strtotime('-3 minute'));
        $date = $this->mongo_db->converToMongodttime($prevouse_date);
        $this->mongo_db->order_by(array('created_time_obj' => -1));
        $this->mongo_db->where_gte('created_time_obj', $date);
        $this->mongo_db->where(array('coin' => $coin, 'type' => $type));
        $this->mongo_db->limit(1);
        $res = $this->mongo_db->get('order_time_track_collection');
        $res_arr = iterator_to_array($res);
        $result = true;
        if (!empty($res_arr)) {
            $result = false;
        }
        return $result;
    } //End of is_order_is_created_just_now

    public function lock_barrier_trigger_true_rules($coin_symbol, $rule_number, $type, $market_price, $log)
    {
        $new_log = $log['Rule_' . $rule_number];

        $new_message = '';
        if (!empty($log['Rule_' . $rule_number])) {
            foreach ($log['Rule_' . $rule_number] as $key => $value) {
                $new_message .= '[' . $key . '] => ' . $value . '<br>';
            }
        }

        $created_date = date('Y-m-d G:i:s');
        $created_date = $this->mongo_db->converToMongodttime($created_date);
        $rule_no = 'rule_no_' . $rule_number;
        $market_price = (float) $market_price;
        $insert_arr = array('coin_symbol' => $coin_symbol, 'rule_number' => $rule_no, 'type' => $type, 'market_price' => $market_price, 'log' => $new_message, 'created_date' => $created_date, 'trigger_type' => 'barrier_trigger');

        $resp = $this->update_lock_barrier_trigger_rules($type, $coin_symbol, $rule_no);

        if ($resp) {
            $id = (string) $resp;
            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($insert_arr);
            $res = $this->mongo_db->update('barrier_trigger_true_rules_collection');

            echo 'updated';
        } else {
            $this->mongo_db->insert('barrier_trigger_true_rules_collection', $insert_arr);
            echo 'inserted';
        }
        return true;
    } //End lock_barrier_trigger_true_rules

    public function update_lock_barrier_trigger_rules($type, $coin_symbol, $rule_no)
    {
        $db = $this->mongo_db->customQuery();
        $start_date = date('Y-m-d H:i:00');
        $start_date = $this->mongo_db->converToMongodttime($start_date);
        $where['coin_symbol'] = $coin_symbol;
        $where['type'] = $type;
        $where['created_date'] = array('$gte' => $start_date);
        $where['rule_number'] = $rule_no;
        $where['trigger_type'] = 'barrier_trigger';

        $response = $db->barrier_trigger_true_rules_collection->find($where);
        $response_1 = iterator_to_array($response);

        $response = false;
        if (count($response_1) > 0) {
            foreach ($response_1 as $row) {
                $response = $row['_id'];
                break;
            }
        }
        return $response;
    } //update_lock_barrier_trigger_rules

    public function is_market_price_empty($market_price)
    {
        if ($market_price == '') {
            exit('remove exit to continue due to empty price');
        }
    } //End of is_market_price_empty

    public function is_sell_order_status_new($order_id)
    {
        $this->mongo_db->where(array('_id' => $order_id, 'status' => 'new'));
        $data = $this->mongo_db->get('orders');
        $resp = false;
        $row = iterator_to_array($data);
        if (count($row) > 0) {
            $resp = true;
        }
        return $resp;
    } //End of is_sell_order_status_new($order_id)

    public function is_trades_on_of()
    {
        $automatic_selected = false;
        $custom_selected = false;

        $trading = $this->mongo_db->get('trading_on_off_collection');
        $trading = iterator_to_array($trading);

        foreach ($trading as $row) {
            if ($row['type'] == 'automatic_on_of_trading') {
                if ($row['status'] == 'on') {
                    $automatic_selected = true;
                }
            }

            if ($row['type'] == 'custom_on_of_trading') {
                if ($row['status'] == 'on') {
                    $custom_selected = true;
                }
            }
        } //End of foreach trading

        $is_trades_on_of = false;

        if ($automatic_selected && $custom_selected) {
            $is_trades_on_of = true;
        }
        return $is_trades_on_of;
    } //is_trades_on_of

    public function last_procedding_candle_status($coin)
    {
        $this->mongo_db->limit(1);
        $this->mongo_db->order_by(array('timestampDate' => -1));
        $this->mongo_db->where_in('candle_type', array('demand', 'supply'));
        $this->mongo_db->where(array('coin' => $coin));
        $response = $this->mongo_db->get('market_chart');
        $response = iterator_to_array($response);
        $candle_type = '';
        if (!empty($response)) {
            $candle_type = $response[0]['candle_type'];
        }
        return $candle_type;
    } //%%%%%%%%%%%%%   End of last_procedding_candle_status

    public function get_user_trading_ip($id)
    {
        $this->mongo_db->where(array('_id' => $id));
        $get_users = $this->mongo_db->get('users');
        $users_arr = iterator_to_array($get_users);
        $users_arr = $users_arr[0];
        return $users_arr['trading_ip'];
    } // %%%%%%%%% End of get_user_trading_ip %%%%%%%%%%%%%

    public function order_ready_for_buy_by_ip($buy_order_id, $buy_quantity, $market_value, $coin_symbol, $admin_id, $trading_ip, $trigger_type, $type)
    {
        $this->mongo_db->where(array('_id' => $buy_order_id));
        $dataArr = $this->mongo_db->get('buy_orders');
        $dataArr = iterator_to_array($dataArr);

        if (!empty($dataArr)) {
            $trigger_type = $dataArr[0]['trigger_type'];
        }

        $created_date = date('Y-m-d H:i:s');
        $created_date = $this->mongo_db->converToMongodttime($created_date);
        $insert_arr = array('buy_order_id' => $buy_order_id, 'buy_quantity' => $buy_quantity, 'market_value' => $market_value, 'coin_symbol' => $coin_symbol, 'admin_id' => $admin_id, 'trading_ip' => $trading_ip, 'trigger_type' => $trigger_type, 'order_type' => $type, 'order_status' => 'ready', 'created_date' => $created_date, 'global' => 'global');
        return $this->mongo_db->insert('ready_orders_for_buy_ip_based', $insert_arr);
    } //End of order_ready_for_buy_by_ip

    public function order_ready_for_sell_by_ip($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id, $trading_ip, $trigger_type, $type)
    {
        $this->mongo_db->where(array('_id' => $buy_orders_id));
        $dataArr = $this->mongo_db->get('buy_orders');
        $dataArr = iterator_to_array($dataArr);

        if (!empty($dataArr)) {
            $trigger_type = $dataArr[0]['trigger_type'];
        }

        $created_date = date('Y-m-d G:00:00');
        $created_date = $this->mongo_db->converToMongodttime($created_date);
        $insert_arr['order_id'] = $order_id;
        $insert_arr['quantity'] = $quantity;
        $insert_arr['market_price'] = $market_price;
        $insert_arr['coin_symbol'] = $coin_symbol;
        $insert_arr['admin_id'] = $admin_id;
        $insert_arr['buy_orders_id'] = $buy_orders_id;
        $insert_arr['trading_ip'] = $trading_ip;
        $insert_arr['trigger_type'] = $trigger_type;
        $insert_arr['order_type'] = $type;
        $insert_arr['order_status'] = 'ready';
        $insert_arr['global'] = 'global';
        return $this->mongo_db->insert('ready_orders_for_sell_ip_based', $insert_arr);
    } // %%%%%%%%%%%%%% End of %%%%%%%%%%%%%%%%

    //%%%%%%%%%%%%%%%%%%%%%%% Function stop by user selected
    public function aggrisive_define_percentage_followup_stop_loss_user_selected($date, $type = '', $trigger_type)
    {
        $orders_arr = $this->get_filled_orders_live_have_stop_loss_rule($trigger_type);
        $date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
        $created_date = date('Y-m-d G:i:s');
        $high_value = 0;
        if (count($orders_arr) > 0) {
            foreach ($orders_arr as $data) {
                $id = $data['_id'];
                $admin_id = $data['admin_id'];
                $buy_price = $data['market_value'];
                $iniatial_trail_stop = $data['iniatial_trail_stop'];
                $iniatial_trail_stop_copy = $data['iniatial_trail_stop_copy'];
                $sell_price = $data['sell_price'];
                $coin_symbol = $data['symbol'];
                if (!$iniatial_trail_stop_copy) {
                    $iniatial_trail_stop_copy = $iniatial_trail_stop;
                }

                $order_mode = 'test';
                if ($type == 'live') {
                    $order_mode = 'live';
                }
                $market_price = $this->get_market_value($coin_symbol);
                $market_price = (float) $market_price;
                $differenc_value = $market_price - $buy_price;
                $difference_percentage = ($differenc_value * 100) / $buy_price;
                $market_up_value_percentage = floor($difference_percentage);
                $this->mongo_db->where(array('triggers_type' => $trigger_type, 'order_mode' => $order_mode));
                $response_obj = $this->mongo_db->get('trigger_global_setting');
                $response_arr = iterator_to_array($response_obj);
                $response = array();
                $apply_factor = 1.5;
                if (count($response_arr) > 0) {
                    $apply_factor = $response_arr[0]['apply_factor'];
                } //End of
                $market_up_value_percentage = ($market_up_value_percentage * $apply_factor);
                $total_initail_trail_stop = $iniatial_trail_stop_copy + ($iniatial_trail_stop_copy / 100) * $market_up_value_percentage;
                $one_percent_less_from_buy_price = $market_price - ($market_price / 100) * 0.5;
                if (($total_initail_trail_stop > $iniatial_trail_stop)) {

                    ///////////////////End of update trail stop
                    ///////////////////////////////////////////
                    $upd_data22 = array(
                        'iniatial_trail_stop' => $total_initail_trail_stop,
                    );
                    if ($total_initail_trail_stop <= $one_percent_less_from_buy_price) {
                        $this->mongo_db->where(array('_id' => $id));
                        $this->mongo_db->set($upd_data22);
                        //Update data in mongoTable
                        $this->mongo_db->update('buy_orders');
                        $log_msg = " Order Stop Loss Updated From " . num($iniatial_trail_stop) . " To " . number_format($total_initail_trail_stop, 8) . ' By aggrisive_define_percentage_followup_stop_loss';
                        $this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $created_date);
                    }
                }
            }
        }
    } //End of aggrisive_define_percentage_followup_stop_loss_user_selected

    public function get_filled_orders_live_have_stop_loss_rule($trigger_type)
    {
        $this->mongo_db->where_ne('is_sell_order', 'sold');
        $this->mongo_db->where_in('order_mode', array('test_live', 'live'));
        $this->mongo_db->where(array('status' => 'FILLED', 'trigger_type' => $trigger_type, 'stop_loss_rule' => 'aggrisive_define_percentage_followup'));
        $buy_orders_result = $this->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End OF get_filled_orders_live_have_stop_loss_rule

    public function ready_custom_stop_loss_order_for_update($coin_symbol)
    {
        $where['stop_loss_rule'] = 'custom_stop_loss';
        $where['status'] = 'FILLED';
        $where['trigger_type'] = 'barrier_trigger';
        $where['symbol'] = $coin_symbol;
        $this->mongo_db->where_ne('is_sell_order', 'sold');
        $this->mongo_db->where_in('order_mode', array('test_live', 'live'));
        $this->mongo_db->where_gte('activate_stop_loss_profit_percentage', 0);
        $this->mongo_db->where($where);
        $buy_orders_result = $this->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End of ready_custom_stop_loss_order_for_update

    public function get_precedding_candel_swing_status($coin_symbol)
    {
        $this->mongo_db->where(array('coin' => '$coin_symbol', 'candel_lowest_swing_status' => 'LL'));
        $this->mongo_db->limit(1);
        $this->mongo_db->order_by(array('timestampDate' => -1));
        $response = $this->mongo_db->get('market_chart');
        $response = iterator_to_array($response);
        $resp = false;
        if (count($response) > 0) {
            $resp = true;
        }
        return $resp;
    } //End of get_precedding_candel_swing_status

    public function is_order_ready_to_update_stop_loss($coin_symbol, $stop_loss_update_percentage, $current_market_price)
    {
        $stop_loss_active_percentage = $current_market_price - ($current_market_price / 100) * $stop_loss_update_percentage;
        $trigger_type = 'barrier_trigger';

        $orders_arr = $this->get_order_which_not_ready_for_stop_loss($trigger_type, $stop_loss_active_percentage, $coin_symbol);

        $created_date = date('Y-m-d G:i:s');
        if (!empty($orders_arr)) {
            foreach ($orders_arr as $data) {
                $id = (string) $data['_id'];
                $admin_id = $data['admin_id'];
                $iniatial_trail_stop = (float) $data['iniatial_trail_stop'];
                $market_value = (float) $data['market_value'];
                $update_price = $market_value + ($market_value / 100) * 0.2;

                $stop_loss_rule = $data['stop_loss_rule'];
                $activate_stop_loss_profit_percentage = $data['activate_stop_loss_profit_percentage'];

                if ($stop_loss_rule == 'custom_stop_loss') {
                    if ($activate_stop_loss_profit_percentage != '') {
                        $profit_percentage = $current_market_price - ($current_market_price / 100) * $activate_stop_loss_profit_percentage;

                        if ($market_value >= $profit_percentage) {
                            $log_msg = "Order is  Active big wall stop loss after selected profit percentage :<b>" . $activate_stop_loss_profit_percentage . "</b>";
                            $this->insert_order_history_log($id, $log_msg, 'stop_loss_ready', $admin_id, $created_date);
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                } //End of stop loss

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                $upd_data22 = array(
                    'is_order_ready_for_stop_loss' => 'yes',
                );

                $this->mongo_db->where(array('_id' => $id));
                $this->mongo_db->set($upd_data22);
                $this->mongo_db->update('buy_orders');

                $log_msg = "Order is <span style='color:green'>Ready</span> to update stop Loss";
                $this->insert_order_history_log($id, $log_msg, 'stop_loss_ready', $admin_id, $created_date);
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                //$this->update_stop_loss_close_to_deep_price($update_price,$iniatial_trail_stop,$id,$admin_id);
            } //End of for Each
        } //End of if condition
    } //End of is_order_ready_to_update_stop_loss

    public function get_order_which_not_ready_for_stop_loss($trigger_type, $diff_price, $coin_symbol)
    {
        $this->mongo_db->where_ne('is_sell_order', 'sold');
        $this->mongo_db->where_ne('is_order_ready_for_stop_loss', 'yes');
        $this->mongo_db->where_in('order_mode', array('test_live', 'live'));
        $this->mongo_db->where(array('status' => 'FILLED', 'trigger_type' => $trigger_type, 'symbol' => $coin_symbol));
        $this->mongo_db->where_lte('market_value', $diff_price);
        $buy_orders_result = $this->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End of get_order_which_not_ready_for_stop_loss

    public function update_stop_loss_close_to_deep_price($update_price, $iniatial_trail_stop, $id, $admin_id)
    {
        $created_date = date('Y-m-d G:i:s');

        if ($iniatial_trail_stop < $update_price) {
            /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
            $upd_data22 = array(
                'iniatial_trail_stop' => $update_price,
            );

            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_data22);
            $this->mongo_db->update('buy_orders');

            $log_msg = "Stop Loss Update From <b>" . num($iniatial_trail_stop) . "</b>   To <b>" . num($update_price) . "</b> As  <span style='color:green'> <b>0.2% profit  </b></span>of Buy Price ";
            $this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $created_date);
            /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
        }
        return true;
    } //End of update_stop_loss_close_to_deep_price

    public function get_profit_defined_lth_orders($coin_symbol)
    {
        $this->mongo_db->where_in('order_mode', array('live'));
        $this->mongo_db->where_ne('is_sell_order', 'sold');
        $this->mongo_db->where_ne('is_profit_updated_as_stop_loss', 'yes');
        $this->mongo_db->where_ne('parent_status', 'parent');
        $where['lth_profit'] = array('$gt' => 0);
        $where['status'] = 'LTH';
        $where['trigger_type'] = 'barrier_trigger';
        $where['symbol'] = $coin_symbol;
        $this->mongo_db->where($where);
        $orders_obj = $this->mongo_db->get('buy_orders');
        $order_arr = iterator_to_array($orders_obj);
        return $order_arr;
    } //End of get_profit_defined_lth_orders
} //End of mod_Barrier_trigger
