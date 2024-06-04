<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Triggers_trades
{
    public $CI;
    public function __construct($params = array())
    {
        $this->CI = &get_instance();
    }

    public function get_parent_orders($coin_symbol, $order_level, $trigger_type, $order_mode)
    {
        $where['order_mode'] = $order_mode;
        $where['pause_status'] = 'play';
        $where['trigger_type'] = $trigger_type;
        $where['symbol'] = $coin_symbol;
        $where['status'] = 'new';
        $where['parent_status'] = 'parent';
        if (!empty($order_level)) {
            if (is_array($order_level)) {
                $where['order_level'] = array('$in' => $order_level);
            } else {
                $where['order_level'] = $order_level;
            }
        }
        $this->CI->mongo_db->order_by(array('modified_date' => -1));
        $this->CI->mongo_db->where($where);
        $parent_orders_object = $this->CI->mongo_db->get('buy_orders');
        $data = iterator_to_array($parent_orders_object);
        return $data;
    } //End of get_parent_orders

    public function list_filled_buyed_orders($coin_symbol, $order_level, $trigger_type, $order_mode, $market_price)
    {
        $where['order_mode'] = $order_mode;
        $where['is_sell_order'] = 'yes';
        $where['status'] = 'FILLED';
        $where['symbol'] = $coin_symbol;
        $where['trigger_type'] = $trigger_type;
        //$where['market_value'] = array('$lte'=>$market_price);
        if ($trigger_type == 'barrier_percentile_trigger') {
            $this->CI->mongo_db->where_ne('is_profit_updated_as_stop_loss', 'yes');
        }
        $this->CI->mongo_db->where($where);
        $buy_orders_result = $this->CI->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End of list_buy_filled_orders

    public function list_stop_loss_orders($coin_symbol, $market_value, $trigger_type, $order_mode)
    {
        $where['order_mode'] = $order_mode;
        $where['trigger_type'] = $trigger_type;
        $where['status'] = 'FILLED';
        $where['symbol'] = $coin_symbol;
        $where['is_sell_order'] = 'yes';
        $where['iniatial_trail_stop'] = array('$gte' => $market_value);
        $this->CI->mongo_db->where($where);
        $buy_orders_result = $this->CI->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End of list_stop_loss_orders

    public function get_deep_price_created_orders($coin_symbol, $market_value, $trigger_type, $order_mode, $order_level, $deep_price_percentage_buy)
    {
        $compare_market_price = $market_value - ($market_value / 100) * $deep_price_percentage_buy;
        $compare_market_price = (float) $compare_market_price;
        $where['order_mode'] = $order_mode;
        $where['trigger_type'] = $trigger_type;
        $where['status'] = 'new';
        $where['symbol'] = $coin_symbol;
        $where['deep_price_on_off'] = 'yes';
        $where['order_level'] = $order_level;
        $where['expecteddeepPrice'] = array('$gte' => $market_value);
        $this->CI->mongo_db->where($where);
        $buy_orders_result = $this->CI->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End of get_deep_price_created_orders

    public function list_orders_for_update_stop_loss($coin_symbol, $market_price, $trigger_type, $order_mode, $order_level)
    {
        $market_price_less_percentage = (float) $market_price - ($market_price / 100) * .2;
        $where['order_mode'] = $order_mode;
        $where['trigger_type'] = $trigger_type;
        $where['status'] = 'FILLED';
        $where['symbol'] = $coin_symbol;
        $where['stop_loss_rule'] = 'percentile_stop_loss';

        $where['is_sell_order'] = 'yes';
        //$where['market_value'] = array('$gte'=>$market_price);
        $where['iniatial_trail_stop'] = array('$lte' => $market_price_less_percentage);

        if (!empty($order_level)) {
            if (is_array($order_level)) {
                $where['order_level'] = array('$in' => $order_level);
            } else {
                $where['order_level'] = $order_level;
            }
        }

        $this->CI->mongo_db->where($where);
        $buy_orders_result = $this->CI->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End of list_orders_for_update_stop_loss

    public function list_orders_for_custom_update_stop_loss($coin_symbol, $market_price, $trigger_type, $order_mode, $order_level)
    {
        $market_price_less_percentage = (float) $market_price - ($market_price / 100) * .2;
        $where['order_mode'] = $order_mode;
        $where['trigger_type'] = $trigger_type;
        $where['status'] = 'FILLED';
        $where['symbol'] = $coin_symbol;
        $where['is_sell_order'] = 'yes';
        //$where['market_value'] = array('$gte'=>$market_price);
        $where['iniatial_trail_stop'] = array('$lte' => $market_price_less_percentage);

        if (!empty($order_level)) {
            if (is_array($order_level)) {
                $where['order_level'] = array('$in' => $order_level);
            } else {
                $where['order_level'] = $order_level;
            }
        }

        $this->CI->mongo_db->where($where);
        $buy_orders_result = $this->CI->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End of list_orders_for_custom_update_stop_loss

    public function get_profit_defined_lth_orders($coin_symbol, $market_value, $trigger_type, $order_mode)
    {

        $where['order_mode'] = $order_mode;
        $where['trigger_type'] = $trigger_type;
        $where['status'] = 'LTH';
        $where['symbol'] = $coin_symbol;
        $where['market_value'] = array('$lte' => $market_value);
        //$where['lth_profit'] = array('$gt'=>0);
        $where['is_sell_order'] = 'yes';
        $this->CI->mongo_db->where($where);
        $orders_obj = $this->CI->mongo_db->get('buy_orders');
        $order_arr = iterator_to_array($orders_obj);
        return $order_arr;

    } //End of get_profit_defined_lth_orders

    public function market_price($symbol = '')
    {
        $this->CI->mongo_db->where(array('coin' => $symbol));
        $this->CI->mongo_db->limit(1);
        $this->CI->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->CI->mongo_db->get('market_prices');
        $price = iterator_to_array($responseArr);
        $resp = 0;
        if (!empty($price)) {$resp = (float) $price[0]['price'];}
        return $resp;
    } //End of market_price

    public function coins_list(){
        
        $where_arr = array(
            'user_id' => 'global',
            'exchange_type' => 'binance',
        );

        $this->CI->mongo_db->sort(array('_id' => -1));
        // $this->CI->mongo_db->where(array('user_id' => 'global'));
        $this->CI->mongo_db->where($where_arr);
        $get_coins = $this->CI->mongo_db->get('coins');
        $coins_arr = iterator_to_array($get_coins);
        return $coins_arr;
    } //End of coins_list

    public function get_coin_detail($coin_symbol = '')
    {
        $where = array();
        if ($coin_symbol != '') {$where['symbol'] = $coin_symbol;}
        $where['user_id'] = 'global';
        $this->CI->mongo_db->where($where);
        $get_coin = $this->CI->mongo_db->get('coins');
        $coin_arr = iterator_to_array($get_coin);
        return $coin_arr[0];
    } //End of get_coin_detail

    public function is_child_order_in_progress($parent_id)
    {
        $ID = $this->CI->mongo_db->mongoId($parent_id);
        $where['buy_parent_id'] = $ID;
        //$where['is_sell_order'] = 'yes';
        //$where['parent_status'] = null;
        $where['status'] = array('$in' => array('new', 'submitted', 'FILLED'));
        $this->CI->mongo_db->where($where);
        $this->CI->mongo_db->limit(1);
        $orders_object = $this->CI->mongo_db->get('buy_orders');
        $orders_arr = iterator_to_array($orders_object);
        $response = true;
        if (!empty($orders_arr)) {$response = false;}
        return $response;
    } //End of is_child_order_in_progress

    public function is_previous_trading_status_completed($parent_id, $trading_status)
    {
        $ID = $this->CI->mongo_db->mongoId($parent_id);
        $where['buy_parent_id'] = $ID;
        $where['trading_status'] = $trading_status;
        $this->CI->mongo_db->where($where);
        $this->CI->mongo_db->limit(1);
        $orders_object = $this->CI->mongo_db->get('buy_orders');
        $orders_arr = iterator_to_array($orders_object);
        $response = true;
        if (!empty($orders_arr)) {$response = false;}
        return $response;
    } //End of is_previous_trading_status_completed

    public function triggers_setting($triggers_type, $order_mode, $coin_symbol, $order_level = '')
    {
        $where['triggers_type'] = $triggers_type;
        $where['order_mode'] = $order_mode;
        $where['coin'] = $coin_symbol;
        if ($order_level != '') {$where['trigger_level'] = $order_level;}
        $this->CI->mongo_db->where($where);
        $response_obj = $this->CI->mongo_db->get('trigger_global_setting');
        $response_arr = iterator_to_array($response_obj);
        return $response_arr[0];
    } //End of triggers_setting

    public function getUserIp($userId)
    {
        $this->CI->mongo_db->where(array('_id' => $userId));
        $get_users = $this->CI->mongo_db->get('users');
        $users_arr = iterator_to_array($get_users);
        $users_arr = $users_arr[0];
        return $users_arr['trading_ip'];
    } //End of getUserIp

    public function record_order_log($order_id, $log_msg, $type, $show_hide_log)
    {
        $created_date = date('Y-m-d G:i:s');
        $ins_error = array(
            'order_id' => $this->CI->mongo_db->mongoId($order_id),
            'log_msg' => $log_msg,
            'type' => $type,
            'show_error_log' => $show_hide_log,
            'created_date' => $this->CI->mongo_db->converToMongodttime($created_date),
        );
        $this->CI->mongo_db->insert('orders_history_log', $ins_error);
        return true;
    } //End of record_order_log

    public function record_order_log_simulator($order_id, $log_msg, $type, $show_hide_log, $created_date)
    {

        $ins_error = array(
            'order_id' => $this->CI->mongo_db->mongoId($order_id),
            'log_msg' => $log_msg,
            'type' => $type,
            'show_error_log' => $show_hide_log,
            'created_date' => $this->CI->mongo_db->converToMongodttime($created_date),
        );
        $this->CI->mongo_db->insert('orders_history_log', $ins_error);
        return true;
    } //End of record_order_log_simulator

    public function list_hourly_percentile_coin_meta($symbol)
    {
        $this->CI->mongo_db->where('coin', $symbol);
        $response_obj = $this->CI->mongo_db->get('coin_meta_hourly_percentile');
        $response_arr = iterator_to_array($response_obj);
        return $response_arr[0];
    } //End of list_hourly_percentile_coin_meta

    public function list_hourly_percentile_coin_meta_history($symbol, $simulator_date)
    {
        $search['coin'] = $symbol;
        $simulator_date = date('Y-m-d H:00:00', strtotime($simulator_date));
        $simulator_date = $this->CI->mongo_db->converToMongodttime($simulator_date);
        $search['modified_time'] = $simulator_date;
        $this->CI->mongo_db->where($search);
        $response_obj = $this->CI->mongo_db->get('coin_meta_hourly_percentile_history');
        $response_arr = iterator_to_array($response_obj);
        return $response_arr[0];
    } //End of list_hourly_percentile_coin_meta_history

    public function list_coin_meta($symbol)
    {
        $this->CI->mongo_db->where('coin', $symbol);
        $response_obj = $this->CI->mongo_db->get('coin_meta');
        $response_arr = iterator_to_array($response_obj);
        return $response_arr[0];
    } //End of list_coin_meta

    public function list_historical_coin_meta($symbol, $start_date, $end_date)
    {
        $search['coin'] = $symbol;
        $search['modified_date'] = array('$gte' => $this->CI->mongo_db->converToMongodttime($start_date), '$lte' => $this->CI->mongo_db->converToMongodttime($end_date));
        $this->CI->mongo_db->where($search);
        $this->CI->mongo_db->limit(1);
        $this->CI->mongo_db->where($search);
        $response_obj = $this->CI->mongo_db->get('coin_meta_history');
        $response_arr = iterator_to_array($response_obj);
        return $response_arr[0];
    } //End of list_historical_coin_meta

    public function lock_ture_rules_for_triggers($coin_symbol, $rule_number, $type, $market_price, $log, $order_level, $trigger_type)
    {

        $created_date = date('Y-m-d H:i:s');
        $created_date = $this->CI->mongo_db->converToMongodttime($created_date);
        $rule_no = 'rule_no_' . $rule_number;
        $market_price = (float) $market_price;
        $insert_arr = array('coin_symbol' => $coin_symbol, 'rule_number' => $rule_no, 'type' => $type, 'market_price' => $market_price, 'log' => $log, 'created_date' => $created_date, 'trigger_type' => $trigger_type, 'order_level' => $order_level);

        $resp = $this->is_trigger_rules_already_log($type, $coin_symbol, $rule_no, $order_level);

        $collectionByDate = 'barrier_trigger_true_rules_collection_' . date('y-m-d');
        if ($resp) {
            $id = (string) $resp;
            $this->CI->mongo_db->where(array('_id' => $id));
            $this->CI->mongo_db->set($insert_arr);
            $res = $this->CI->mongo_db->update($collectionByDate);
        } else {
            $this->CI->mongo_db->insert($collectionByDate, $insert_arr);
        }
        return true;
    } //End of lock_ture_rules_for_triggers

    public function is_trigger_rules_already_log($type, $coin_symbol, $rule_no, $order_level)
    {

        $db = $this->CI->mongo_db->customQuery();
        $start_date = date('Y-m-d H:i:00');
        $start_date = $this->CI->mongo_db->converToMongodttime($start_date);
        $where['coin_symbol'] = $coin_symbol;
        $where['type'] = $type;
        $where['created_date'] = array('$gte' => $start_date);
        $where['trigger_type'] = 'barrier_percentile_trigger';
        $where['order_level'] = $order_level;
        $limit['limit'] = 1;
        $collectionByDate = 'barrier_trigger_true_rules_collection_' . date('y-m-d');
        $rules_object = $db->$collectionByDate->find($where, $limit);
        $rules_arr = iterator_to_array($rules_object);

        $response = false;
        if (count($rules_arr) > 0) {
            foreach ($rules_arr as $row) {
                $response = $row['_id'];
                break;
            }
        }
        return $response;
    } //End of is_trigger_rules_already_log

    public function binance_buy_auto_market_order_test($id, $quantity, $market_value, $symbol, $user_id)
    {

        $buy_order_arr = $this->get_buy_order($id);
        $created_date = date("Y-m-d G:i:s");
        if ($buy_order_arr['status'] == 'new') {
            $upd_data = array(
                'market_value' => $market_value,
                'status' => 'submitted',
                'modified_date' => $this->CI->mongo_db->converToMongodttime($created_date),
                'buy_date' => $this->CI->mongo_db->converToMongodttime($created_date),
                'binance_order_id' => '111000',
            );

            $this->CI->mongo_db->where(array('_id' => $id));
            $this->CI->mongo_db->set($upd_data);

            //Update data in mongoTable
            $this->CI->mongo_db->update('buy_orders');

            ////////////////////// Set Notification //////////////////
            $message = "Buy Market Order is <b>SUBMITTED</b>";
            $message2 = "<strong>" . $symbol . "</strong> Market Trade is <b style='color:#2ca634'>SUBMITTED</b> for Buy";
            $this->add_notification($id, 'buy', $message, $user_id);
            $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
            //////////////////////////////////////////////////////////
            $log_msg = "Buy Market Order was <b>SUBMITTED</b>";
            $this->record_order_log($id, $log_msg, 'buy_submitted', 'no');
        } //End if Order is New

        return true;

    } //end binance_buy_auto_market_order_test

    public function binance_buy_auto_market_order_test_simulator($id, $quantity, $market_value, $symbol, $user_id, $created_date)
    {

        $buy_order_arr = $this->get_buy_order($id);
        if ($buy_order_arr['status'] == 'new') {
            $upd_data = array(
                'market_value' => $market_value,
                'status' => 'FILLED',
                'modified_date' => $this->CI->mongo_db->converToMongodttime($created_date),
                'buy_date' => $this->CI->mongo_db->converToMongodttime($created_date),
                'binance_order_id' => '111000',
            );

            $this->CI->mongo_db->where(array('_id' => $id));
            $this->CI->mongo_db->set($upd_data);

            //Update data in mongoTable
            $this->CI->mongo_db->update('buy_orders');
            //////////////////////////////////////////////////////////
            $log_msg = "Buy Market Order was <b>SUBMITTED</b>";
            $this->record_order_log_simulator($id, $log_msg, 'buy_submitted', 'yes', $created_date);
        } //End if Order is New
        return true;
    } //end binance_buy_auto_market_order_test_simulator

    //binance_sell_auto_market_order_test     ****** TEST ORDERS ********
    public function binance_sell_auto_market_order_test($id, $quantity, $market_value, $symbol, $user_id, $buy_order_id)
    {

        $sell_order_arr = $this->get_sell_order($id);
        $created_date = date("Y-m-d G:i:s");
        if ($sell_order_arr['status'] == 'new' || $sell_order_arr['status'] == 'LTH') {

            $upd_data = array(
                'market_value' => $market_value,
                'status' => 'submitted',
                'modified_date' => $this->CI->mongo_db->converToMongodttime($created_date),
                'sell_date' => $this->CI->mongo_db->converToMongodttime($created_date),
                'binance_order_id' => '111000',
            );

            $this->CI->mongo_db->where(array('_id' => $id));
            $this->CI->mongo_db->set($upd_data);
            //Update data in mongoTable
            $this->CI->mongo_db->update('orders');

            $update_arr['modified_date'] = $this->CI->mongo_db->converToMongodttime($created_date);
            $this->CI->mongo_db->where(array('sell_order_id' => $this->CI->mongo_db->mongoId($id)));
            $this->CI->mongo_db->set($update_arr);
            $this->CI->mongo_db->update('buy_orders');

            ////////////////////// Set Notification //////////////////
            $message = "Sell Market Order is <b>SUBMITTED</b>";
            $message2 = "<strong>" . $symbol . "</strong> Market Trade is <b style='color:#2ca634'>SUBMITTED</b> for Buy";
            $this->add_notification($id, 'buy', $message, $user_id);
            $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
            //////////////////////////////////////////////////////////
            $log_msg = "Sell Market Order was <b>SUBMITTED</b>";
            $this->record_order_log($buy_order_id, $log_msg, 'buy_submitted', 'no');
        } //End if Order is New
        return true;
    } //end binance_sell_auto_market_order_test

    public function binance_sell_auto_market_order_test_simulator($id, $quantity, $market_value, $symbol, $user_id, $buy_order_id, $created_date)
    {

        $sell_order_arr = $this->get_sell_order($id);
        if ($sell_order_arr['status'] == 'new' || $sell_order_arr['status'] == 'LTH') {

            $upd_data = array(
                'market_value' => $market_value,
                'status' => 'FILLED',
                'modified_date' => $this->CI->mongo_db->converToMongodttime($created_date),
                'sell_date' => $this->CI->mongo_db->converToMongodttime($created_date),
                'binance_order_id' => '111000',
            );

            $this->CI->mongo_db->where(array('_id' => $id));
            $this->CI->mongo_db->set($upd_data);
            //Update data in mongoTable
            $this->CI->mongo_db->update('orders');

            $update_arr['modified_date'] = $this->CI->mongo_db->converToMongodttime($created_date);
            $this->CI->mongo_db->where(array('sell_order_id' => $this->CI->mongo_db->mongoId($id)));
            $this->CI->mongo_db->set($update_arr);
            $this->CI->mongo_db->update('buy_orders');

            //////////////////////////////////////////////////////////
            $log_msg = "Sell Market Order was <b>SUBMITTED</b>";
            $this->record_order_log_simulator($buy_order_id, $log_msg, 'buy_submitted', 'yes', $created_date);
        } //End if Order is New
        return true;
    } //end binance_sell_auto_market_order_test_simulator

    public function add_notification($order_id, $type, $message, $admin_id)
    {
        $created_date = date('Y-m-d G:i:s');
        $ins_data = array(
            'admin_id' => trim($admin_id),
            'order_id' => trim($order_id),
            'type' => trim($type),
            'message' => trim($message),
            'status' => trim('0'),
            'created_date' => trim($created_date),
        );
        //Insert the record into the database.
        //$this->CI->mongo_db->insert('notification', $ins_data);
        return true;
    } //end add_notification()

    //add_notification_for_app
    public function add_notification_for_app($order_id = "", $type = "", $priority = "", $message = "", $admin_id = "", $symbol = "")
    {

        if ($symbol != '') {
            $coin_logo = $this->get_coin_detail($symbol);
            $coin_logo = $coin_logo['coin_logo'];
        } elseif ($type == "security_alerts" || $type == "security_alert") {
            $coin_logo = "security_alert.png";
        } else {
            $coin_logo = "";
        }
        $created_date = date('Y-m-d G:i:s');
        $ins_data = array(
            'admin_id' => trim($admin_id),
            'order_id' => trim($order_id),
            'type' => trim($type),
            'priority' => trim($priority),
            'message' => trim($message),
            'symbol' => trim($symbol),
            'coin_logo' => trim($coin_logo),
            'created_date_human_readable' => trim($created_date),
            'created_date' => $this->CI->mongo_db->converToMongodttime($created_date),
        );

        //Insert the record into the database.
        //$this->CI->mongo_db->insert('app_notification', $ins_data);
        //$this->send_push_notification($admin_id, $type, $message);
        return true;

    } //end add_notification_for_app()

    public function send_push_notification($admin_id, $type, $message)
    {

        $this->CI->load->library('push_notifications');
        $tokens = $this->get_device_token($admin_id);
        //$user_info = $this->get_user_settings($admin_id);
        foreach ($tokens as $value) {
            if (!empty($value)) {
                if ($value['device_type'] == 'android') {
                    $device_token = $value['device_token'];
                    $data['title'] = ucfirst(str_replace("_", " ", $type)) . " Notification";
                    $data['msg_desc'] = strip_tags($message);
                    $push = $this->CI->push_notifications->android_notification($data, $device_token);
                } elseif ($value['device_type'] == 'ios') {
                    $device_token = $value['device_token'];
                    $data['title'] = ucfirst(str_replace("_", " ", $type)) . " Notification";
                    $data['msg_desc'] = strip_tags($message);
                    $push = $this->CI->push_notifications->iOS($data, $device_token);
                }
            } //End of not empty value
        } //End of foreach token
    } //End of send_push_notification

    public function get_device_token($admin_id)
    {
        $this->CI->mongo_db->where(array('admin_id' => $admin_id));
        $get = $this->CI->mongo_db->get('users_device_tokens');
        return iterator_to_array($get);
    } //End of get_device_token

    public function get_buy_order($id)
    {

        $timezone = $this->CI->session->userdata('timezone');
        if (empty($timezone)) {
            $timezone = 'ASIA/KARACHI';
        }
        $this->CI->mongo_db->where(array('_id' => $id));
        $this->CI->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->CI->mongo_db->get('buy_orders');
        $final_resp_arr = iterator_to_array($responseArr);
        if (count($final_resp_arr) == 0) {
            $this->CI->mongo_db->where(array('_id' => $id));
            $this->CI->mongo_db->sort(array('_id' => 'desc'));
            $responseArr = $this->CI->mongo_db->get('sold_buy_orders');
            $final_resp_arr = iterator_to_array($responseArr);
        }

        foreach ($final_resp_arr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {

                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone($timezone);
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');
                if (empty($valueArr['modified_date'])) {
                    $valueArr['modified_date'] = $valueArr['created_date'];
                }
                $datetime2 = $valueArr['modified_date']->toDateTime();
                $created_date2 = $datetime2->format(DATE_RSS);

                $datetime2 = new DateTime($created_date2);
                $datetime2->format('Y-m-d g:i:s A');
                $datetime2->setTimezone($new_timezone);
                $formated_date_time2 = $datetime2->format('Y-m-d g:i:s A');

                $returArr = $valueArr;
                $returArr['market_value'] = num($valueArr['market_value']);
                $returArr['market_sold_price'] = num($valueArr['market_sold_price']);
                $returArr['modified_date'] = $formated_date_time2;
                $returArr['created_date'] = $formated_date_time;
            } //End of not empty array
        } //End of foreach
        return $returArr;
    } //end get_buy_order

    public function get_sell_order($id)
    {
        $timezone = $this->CI->session->userdata('timezone');
        if (empty($timezone)) {$timezone = 'ASIA/KARACHI';}
        $this->CI->mongo_db->where(array('_id' => $id));
        $this->CI->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->CI->mongo_db->get('orders');

        foreach ($responseArr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);
                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');
                $new_timezone = new DateTimeZone($timezone);
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');
                $returArr = $valueArr;
                $returArr['created_date'] = $formated_date_time;
            } //End of if
        } //End of foreach
        return $returArr;
    } //End get_sell_order

    public function list_barrier_status($coin_symbol, $barrier_status, $market_price, $type)
    {
        $this->CI->mongo_db->limit(1);
        $where = array();
        if ($type == 'down') {
            // $where['barier_value'] = array('$lte'=>(float) $market_price);
        }
        $where['coin'] = $coin_symbol;
        $where['barrier_status'] = $barrier_status;
        $where['barrier_type'] = $type;

        $this->CI->mongo_db->order_by(array('created_date' => -1));
        $this->CI->mongo_db->where($where);
        $res_obj = $this->CI->mongo_db->get('barrier_values_collection');
        $res_arr = iterator_to_array($res_obj);
        $barier_value = 0;

        $data = array();
        if (count($res_arr) > 0) {
            $row = $res_arr[0];
            $barier_value = $row['barier_value'];
        } //End of Count
        return $barier_value;
    } //End of barrier_status

    public function list_barrier_status_simulator($coin_symbol, $barrier_status, $market_price, $type, $simulator_date)
    {
        $this->CI->mongo_db->limit(1);
        $where = array();
        if ($type == 'down') {
            $where['barier_value'] = array('$lte' => (float) $market_price);
        }

        $simulator_date = $this->CI->mongo_db->converToMongodttime($simulator_date);
        $where['created_date'] = array('$lte' => $simulator_date);

        $where['coin'] = $coin_symbol;
        $where['original_barrier_status'] = $barrier_status;
        $where['barrier_type'] = $type;

        $this->CI->mongo_db->order_by(array('created_date' => -1));
        $this->CI->mongo_db->where($where);
        $res_obj = $this->CI->mongo_db->get('barrier_values_collection');
        $res_arr = iterator_to_array($res_obj);
        $barier_value = 0;

        $data = array();
        if (count($res_arr) > 0) {
            $row = $res_arr[0];
            $barier_value = $row['barier_value'];
        } //End of Count
        return $barier_value;
    } //End of barrier_status

    public function list_market_volume($market_price, $coin_symbol, $type)
    {
        $where['coin'] = $coin_symbol;
        $where['type'] = $type;
        $where['price'] = $market_price;
        $this->CI->mongo_db->where($where);
        $responseobj = $this->CI->mongo_db->get('market_depth');
        $responseArr = iterator_to_array($responseobj);
        $global_quantity = '';
        if (count($responseArr) > 0) {$global_quantity = $responseArr[0]['quantity'];}
        return $global_quantity;
    } //End of list_market_volume

    public function list_market_volume_history($market_price, $coin_symbol, $type, $simulator_date)
    {
        $where['coin'] = $coin_symbol;
        $where['type'] = $type;
        $where['price'] = $market_price;
        $simulator_date = $this->CI->mongo_db->converToMongodttime($simulator_date);
        $where['created_date'] = array('$lte' => $simulator_date);
        $this->CI->mongo_db->where($where);
        $responseobj = $this->CI->mongo_db->get('market_depth_history');
        $responseArr = iterator_to_array($responseobj);
        $global_quantity = '';
        if (count($responseArr) > 0) {$global_quantity = $responseArr[0]['quantity'];}
        return $global_quantity;
    } //End of list_market_volume

    public function last_procedding_candle_status($coin)
    {
        $this->CI->mongo_db->limit(1);
        $this->CI->mongo_db->order_by(array('timestampDate' => -1));
        $this->CI->mongo_db->where_in('candle_type', array('demand', 'supply'));
        $this->CI->mongo_db->where(array('coin' => $coin));
        $response = $this->CI->mongo_db->get('market_chart');
        $response = iterator_to_array($response);
        $candle_type = '';
        if (!empty($response)) {$candle_type = $response[0]['candle_type'];}
        return $candle_type;
    } //%%%%%%%%%%%%%   End of last_procedding_candle_status %%%%%%%%%%%%%5

    public function last_procedding_candle_status_history($coin, $simulator_date)
    {
        $this->CI->mongo_db->limit(1);
        $this->CI->mongo_db->order_by(array('timestampDate' => -1));
        $simulator_date = $this->CI->mongo_db->converToMongodttime($simulator_date);

        $where['timestampDate'] = array('$lte' => $simulator_date);
        $where['coin'] = $coin;
        $this->CI->mongo_db->where_in('candle_type', array('demand', 'supply'));
        $this->CI->mongo_db->where($where);
        $response = $this->CI->mongo_db->get('market_chart');
        $response = iterator_to_array($response);
        $candle_type = '';
        if (!empty($response)) {$candle_type = $response[0]['candle_type'];}
        return $candle_type;
    } //%%%%%%%%%%%%%   End of last_procedding_candle_status_history %%%%%%%%%%%%%5

    public function is_order_not_send_for_sell($order_id)
    {
        $where['_id'] = $order_id;
        $where['status'] = 'new';
        $this->CI->mongo_db->where($where);
        $data = $this->CI->mongo_db->get('orders');
        $resp = false;
        $row = iterator_to_array($data);
        if (count($row) > 0) {$resp = true;}
        return $resp;
    } //End of is_order_not_send_for_sell

    public function market_heighest_value_for_current_order($buy_orders_id, $market_price)
    {
        $where['_id'] = $buy_orders_id;
        $this->CI->mongo_db->where($where);
        $orders = $this->CI->mongo_db->get('buy_orders');
        $orders = iterator_to_array($orders);
        if (count($orders) > 0) {

            if ($orders[0]['market_heighest_value'] < $market_price) {
                $heigh_market_value_arr = array('market_heighest_value' => $market_price);
                $this->CI->mongo_db->where(array('_id' => $buy_orders_id));
                $this->CI->mongo_db->set($heigh_market_value_arr);
                //Update data in mongoTable
                $this->CI->mongo_db->update('buy_orders');
            } else if (!isset($orders[0]['market_lowest_value'])) {

                $upd_arr = array('market_lowest_value' => $market_price);
                $this->CI->mongo_db->where(array('_id' => $buy_orders_id));
                $this->CI->mongo_db->set($upd_arr);
                //Update data in mongoTable
                $this->CI->mongo_db->update('buy_orders');

            } else if (($market_price < $orders[0]['market_lowest_value'])) {
                $upd_arr = array('market_lowest_value' => $market_price);
                $this->CI->mongo_db->where(array('_id' => $buy_orders_id));
                $this->CI->mongo_db->set($upd_arr);
                //Update data in mongoTable
                $this->CI->mongo_db->update('buy_orders');
            }
            //if heigst value is less then
        } //End of if orders Count Greater
        //%%%%%%%%%%%%%%%%%%% Market Heigh value %%%%%%%%%%%%%%%%%
    } //End of market_heighest_value_for_current_order

    public function make_order_long_time_hold($order_id, $buy_orders_id, $admin_id, $lth_profit)
    {
        $upd_lth['status'] = 'LTH';
        $upd_lth['sell_profit_percent'] = $lth_profit;
        $upd_lth['is_lth_order'] = 'yes';
        $this->CI->mongo_db->where(array('_id' => $buy_orders_id));
        $this->CI->mongo_db->set($upd_lth);
        //Update data in mongoTable
        $this->CI->mongo_db->update('buy_orders');
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $log_msg = 'Order Goes to <span style="color:orange;font-size: 14px;"><b>Long Term Hold</b> By System</span>';
        $this->record_order_log($buy_orders_id, $log_msg, 'sell_created', 'yes');
    } //End of make_order_long_time_hold

    public function is_trades_on_of()
    {
        $automatic_selected = false;
        $custom_selected = false;
        $trading = $this->CI->mongo_db->get('trading_on_off_collection');
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
            return true;
        } else {
            return false;
        }
    } //is_trades_on_of

    public function send_order_to_buy_by_specific_user_ip($buy_order_id, $buy_quantity, $market_value, $coin_symbol, $admin_id, $trading_ip, $trigger_type, $type)
    {
        $created_date = date('Y-m-d H:i:s');

        $this->CI->mongo_db->where(array('_id' => $buy_order_id));
        $dataArr = $this->CI->mongo_db->get('buy_orders');
        $dataArr = iterator_to_array($dataArr);
        if (!empty($dataArr)) {
            $trigger_type = $dataArr[0]['trigger_type'];
        }

        $created_date = $this->CI->mongo_db->converToMongodttime($created_date);
        $insert_arr['buy_order_id'] = $buy_order_id;
        $insert_arr['buy_quantity'] = $buy_quantity;
        $insert_arr['market_value'] = $market_value;
        $insert_arr['coin_symbol'] = $coin_symbol;
        $insert_arr['admin_id'] = $admin_id;
        $insert_arr['trading_ip'] = $trading_ip;
        $insert_arr['trigger_type'] = $trigger_type;
        $insert_arr['order_type'] = $type;
        $insert_arr['order_status'] = 'ready';
        $insert_arr['created_date'] = $created_date;
        $insert_arr['global'] = 'global';
        return $this->CI->mongo_db->insert('ready_orders_for_buy_ip_based', $insert_arr);
    } //End of send_order_to_buy_by_specific_user_ip

    public function send_order_to_buy_by_specific_user_ip_bam($buy_order_id, $buy_quantity, $market_value, $coin_symbol, $admin_id, $trading_ip, $trigger_type, $type)
    {
        $created_date = date('Y-m-d H:i:s');

        $this->CI->mongo_db->where(array('_id' => $buy_order_id));
        $dataArr = $this->CI->mongo_db->get('buy_orders_bam');
        $dataArr = iterator_to_array($dataArr);
        if (!empty($dataArr)) {
            $trigger_type = $dataArr[0]['trigger_type'];
        }

        $created_date = $this->CI->mongo_db->converToMongodttime($created_date);
        $insert_arr['buy_order_id'] = $buy_order_id;
        $insert_arr['buy_quantity'] = $buy_quantity;
        $insert_arr['market_value'] = $market_value;
        $insert_arr['coin_symbol'] = $coin_symbol;
        $insert_arr['admin_id'] = $admin_id;
        $insert_arr['trading_ip'] = $trading_ip;
        $insert_arr['trigger_type'] = $trigger_type;
        $insert_arr['order_type'] = $type;
        $insert_arr['order_status'] = 'ready';
        $insert_arr['created_date'] = $created_date;
        $insert_arr['global'] = 'global';
        return $this->CI->mongo_db->insert('ready_orders_for_buy_ip_based_bam', $insert_arr);
    } //End of send_order_to_buy_by_specific_user_ip_bam

    public function is_already_send_for_buy($buy_order_id)
    {
        $where['buy_order_id'] = $buy_order_id;
        $this->CI->mongo_db->where($where);
        $respArr = $this->CI->mongo_db->get('ready_orders_for_buy_ip_based');
        $respArr = iterator_to_array($respArr);

        $resp = false;
        if (empty($respArr)) {
            $resp = true;
        }
        return $resp;
    } //End of is_already_send_for_buy

    public function is_already_send_for_buy_bam($buy_order_id)
    {
        $where['buy_order_id'] = $buy_order_id;
        $this->CI->mongo_db->where($where);
        $respArr = $this->CI->mongo_db->get('ready_orders_for_buy_ip_based_bam');
        $respArr = iterator_to_array($respArr);

        $resp = false;
        if (empty($respArr)) {
            $resp = true;
        }
        return $resp;
    } //End of is_already_send_for_buy_bam

    public function send_order_to_sell_by_specific_user_ip($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id, $trading_ip, $trigger_type, $type)
    {
        $created_date = date('Y-m-d G:00:00');
        $created_date = $this->CI->mongo_db->converToMongodttime($created_date);
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
        $insert_arr['created_date'] = $created_date;
        return $this->CI->mongo_db->insert('ready_orders_for_sell_ip_based', $insert_arr);
    } //End of send_order_to_sell_by_specific_user_ip




    public function send_order_to_sell_by_specific_user_ip_bam($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id, $trading_ip, $trigger_type, $type)
    {
        $created_date = date('Y-m-d G:00:00');
        $created_date = $this->CI->mongo_db->converToMongodttime($created_date);
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
        $insert_arr['created_date'] = $created_date;
        return $this->CI->mongo_db->insert('ready_orders_for_sell_ip_based_bam', $insert_arr);
    } //End of send_order_to_sell_by_specific_user_ip_bam

    public function is_order_alreay_send_for_sell_to_specific_ip($order_id)
    {
        $where['order_id'] = $order_id;
        $this->CI->mongo_db->where($where);
        $data = $this->CI->mongo_db->get('ready_orders_for_sell_ip_based');
        $row = iterator_to_array($data);
        $resp = false;
        if (!empty($row)) {
            $resp = true;
        }
        return $resp;
    } //End of is_order_alreay_not_send_for_sell_to_specific_ip

    public function list_last_candle_detail($coin_symbol)
    {
        $where['coin'] = $coin_symbol;
        $this->CI->mongo_db->limit(1);
        $this->CI->mongo_db->order_by(array('timestampDate' => -1));
        $this->CI->mongo_db->where($where);
        $candle_data = $this->CI->mongo_db->get('market_chart');
        $candle_data = iterator_to_array($candle_data);
        return (array) $candle_data[0];
    } //End last_candel_detail

    public function get_last_low_swing_candle_open_time($date, $coin_symbol)
    {
        $Date = $this->CI->mongo_db->converToMongodttime($date);
        $where['open_time_object'] = array('$lte' => $Date);
        $this->CI->mongo_db->order_by(array('open_time_object' => -1));
        $where['global_swing_parent_status'] = array('$in' => array('LL', 'HL'));
        $where['coin'] = $coin_symbol;
        $this->CI->mongo_db->where($where);
        $this->CI->mongo_db->limit(1);
        $res_object = $this->CI->mongo_db->get('box_trigger_3_setting');
        $res_arr = iterator_to_array($res_object);
        $data = $res_arr[0]['openTime_human_readible'];
        return $data;
    } //End of get_last_low_swing_candle_open_time

    public function is_order_already_created($start_date, $end_date, $coin_symbol)
    {
        $where['open_time_object'] = array('$gte' => $this->CI->mongo_db->converToMongodttime($start_date), '$lte' => $this->CI->mongo_db->converToMongodttime($end_date));
        $where['coin'] = $coin_symbol;
        $where['box_progress_status'] = 'created';
        $this->CI->mongo_db->where($where);
        $this->CI->mongo_db->limit(1);
        $res_obj = $this->CI->mongo_db->get('box_trigger_3_setting');
        $resp_arr = iterator_to_array($res_obj);
        return $resp_arr[0]['high'];
    } //End of is_order_already_created

    public function is_current_heigh_value_greater_from_previous_demand_candle_heigh_value_between_swing_point($start_date, $end_date, $coin, $high_value)
    {
        $this->CI->mongo_db->limit(1);
        $where['open_time_object'] = array('$gte' => $this->CI->mongo_db->converToMongodttime($start_date), '$lte' => $this->CI->mongo_db->converToMongodttime($end_date));
        $where['coin'] = $coin;
        $where['candle_type'] = 'demand';
        $where['high'] = array('$gte' => $high_value);
        $this->CI->mongo_db->where($where);
        $res_obj = $this->CI->mongo_db->get('box_trigger_3_setting');
        $resp_arr = iterator_to_array($res_obj);
        $resp = true;
        if (!empty($resp_arr)) {$resp = false;}
        return $resp;
    } //End of is_current_heigh_value_greater_from_previous_demand_candle_heigh_value_between_swing_point

    public function create_box_order_setting($coin_symbol)
    {
        //%%%%%%%%%%%%% -- last candle --%%%%%%%%%%%%%%%%%%%%%%
        $candle_arr = $this->list_last_candle_detail($coin_symbol);
        extract($candle_arr);
        $box_progress_status = '';
        /************Check if candle is demand candle*******/
        if ($candle_type == 'demand') {
            //%%%%%%%%%%%%% -- candle open time --%%%%%%%%%%%%%%%%%%%%%%
            $low_swing_candle_date = $this->get_last_low_swing_candle_open_time($openTime_human_readible, $coin_symbol);
            if (!empty($low_swing_candle_date)) {
                //%%%%%%%%%%%%% -- Created state Heigh value --%%%%%%%%%%%%%%%%%%%%%%
                $created_state_candle_heigh_value = $this->is_order_already_created($low_swing_candle_date, $openTime_human_readible, $coin_symbol);
                if (!empty($created_state_candle_heigh_value)) {
                    if ($high > $created_state_candle_heigh_value) {

                        /*Check if current demand high value is greater from previos Demand candle*/
                        $is_high_value_greater = $this->is_current_heigh_value_greater_from_previous_demand_candle_heigh_value_between_swing_point($low_swing_candle_date, $openTime_human_readible, $coin_symbol, $high);
                        if ($is_high_value_greater) {
                            $box_progress_status = 'updated';
                        } else {
                            $box_progress_status = 'ignored';
                        }

                    } else {
                        $box_progress_status = 'ignored';
                    }
                } else { //End of created_candle_detail
                    $box_progress_status = 'created';
                } //End of elsle created_candle_detail
            } //End of swing_point_candle_detail
        } //End of check of demand candl

        $insert_arr = array(
            'global_swing_parent_status' => $global_swing_parent_status,
            'open' => (float) $open,
            'high' => (float) $high,
            'low' => (float) $low,
            'close' => (float) $close,
            'openTime_human_readible' => $openTime_human_readible,
            'open_time_object' => $timestampDate,
            'coin' => $coin,
            'candle_type' => $candle_type,
            'box_progress_status' => $box_progress_status,
        );

        $this->CI->mongo_db->where(array('open_time_object' => $this->CI->mongo_db->converToMongodttime($openTime_human_readible), 'coin' => $coin));
        $response_obj = $this->CI->mongo_db->get('box_trigger_3_setting');
        $response_arr = iterator_to_array($response_obj);

        if (count($response_arr) > 0) {
            $this->CI->mongo_db->where(array('open_time_object' => $this->CI->mongo_db->converToMongodttime($openTime_human_readible), 'coin' => $coin));
            $this->CI->mongo_db->set($insert_arr);
            $this->CI->mongo_db->update('box_trigger_3_setting');
        } else {
            $this->CI->mongo_db->insert('box_trigger_3_setting', $insert_arr);
        }

    } //End of create_box_order_setting

    public function get_last_hour_demand_candle($coin_symbol)
    {
        $prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
        $open_time_object = $this->CI->mongo_db->converToMongodttime($prevouse_date);
        $where['open_time_object'] = $open_time_object;
        $where['coin'] = $coin_symbol;
        $where['candle_type'] = 'demand';
        $this->CI->mongo_db->where($where);
        $previouse_candel_result = $this->CI->mongo_db->get('box_trigger_3_setting');
        $data = iterator_to_array($previouse_candel_result);
        $resp = array();
        if (!empty($data)) {
            $resp = $data[0];
        }
        return $resp;
    } // -- %%%%%% End of get_last_hour_demand_candle %%%%%%%% --

    public function get_last_low_swing_point_candle($coin_symbol)
    {

        $prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
        $where['coin'] = $coin_symbol;
        $where['open_time_object'] = array('$lte' => $this->CI->mongo_db->converToMongodttime($prevouse_date));
        $where['global_swing_parent_status'] = array('$in' => array('LL', 'HL'));
        $this->CI->mongo_db->order_by(array('open_time_object' => -1));
        $this->CI->mongo_db->limit(1);
        $this->CI->mongo_db->where($where);
        $resp_obj = $this->CI->mongo_db->get('box_trigger_3_setting');
        $resp_arr = iterator_to_array($resp_obj);
        $res = array();
        if (!empty($resp_arr)) {
            $res['low_value'] = $resp_arr[0]['low'];
            $res['swing_candle_date'] = $resp_arr[0]['openTime_human_readible'];
        }
        return $res;

    } // -- %%%%% End of get_last_low_swing_point_candle  %%%%%% --

    public function at_least_one_candle_close_above_from_demand_candle($start_date, $coin_symbol, $demand_close_value)
    {
        $end_date = date('Y-m-d H:00:00');
        $this->CI->mongo_db->limit(1);
        $where['coin'] = $coin_symbol;
        $where['timestampDate'] = array('$gt' => $start_date, '$lt' => $this->CI->mongo_db->converToMongodttime($end_date));
        $where['close'] = array('$gt' => (float) $demand_close_value);
        $this->CI->mongo_db->where($where);
        $data_row = $this->CI->mongo_db->get('market_chart');
        $data_arr = iterator_to_array($data_row);
        $resp = false;
        if (!empty($data_arr)) {$resp = true;}
        return $resp;
    } //End of at_least_one_candle_close_above_from_demand_candle

    public function list_new_orders($coin_symbol, $trigger_type, $order_mode, $current_price, $order_level)
    {
        $where['order_mode'] = $order_mode;
        $where['is_sell_order'] = 'no';
        $where['status'] = 'new';
        $where['symbol'] = $coin_symbol;
        $where['trigger_type'] = $trigger_type;
        // $where['price'] =  array('$gte'=> (float)$current_price);
        if ($order_level != '') {$where['order_level'] = $order_level;}
        $this->CI->mongo_db->where($where);
        $buy_orders_result = $this->CI->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End of list_new_orders

    public function list_new_orders_ready_to_update($coin_symbol, $trigger_type, $order_mode, $buy_price)
    {
        $where['order_mode'] = $order_mode;
        $where['is_sell_order'] = 'no';
        $where['status'] = 'new';
        $where['symbol'] = $coin_symbol;
        $where['trigger_type'] = $trigger_type;
        $where['price'] = array('$lt' => (float) $buy_price);
        $this->CI->mongo_db->where($where);
        $buy_orders_result = $this->CI->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End of list_new_orders_ready_to_update

    public function update_status_to_ignore($id)
    {
        $this->CI->mongo_db->where(array('_id' => $id));
        $upd_arr = array('box_progress_status' => 'ignored', 'update_reason' => 'status update to ignore from created because the created status is ignored due to the close less then rule');
        $this->CI->mongo_db->set($upd_arr);
        $this->CI->mongo_db->update('box_trigger_3_setting');
        return true;
    } //End of update_status_to_ignore

    public function is_previous_candle_is_blue($coin_symbol)
    {
        $prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
        $timestampDate = $this->CI->mongo_db->converToMongodttime($prevouse_date);
        $this->CI->mongo_db->where(array('timestampDate' => $timestampDate, 'coin' => $coin_symbol));
        $current_candel_result = $this->CI->mongo_db->get('market_chart');
        $current_candel_arr = iterator_to_array($current_candel_result);

        $response = false;
        if (!empty($current_candel_arr)) {
            $current_candel_arr = $current_candel_arr[0];
            $current_open = $current_candel_arr['open'];
            $current_close = $current_candel_arr['close'];

            if ($current_open < $current_close) {
                $response = true;
            } else if ($current_open == $current_close) {
                $prevouse_date = date('Y-m-d H:00:00', strtotime('-2 hour'));
                $timestampDate = $this->CI->mongo_db->converToMongodttime($prevouse_date);
                $this->CI->mongo_db->where(array('timestampDate' => $timestampDate, 'coin' => $coin_symbol));
                $prevouse_candel_result = $this->CI->mongo_db->get('market_chart');
                $prevouse_candel_arr = iterator_to_array($prevouse_candel_result);

                if (!empty($prevouse_candel_arr)) {
                    $prevouse_candel_arr = $prevouse_candel_arr[0];
                    $prevouse_close = $prevouse_candel_arr['close'];
                    if ($current_open > $prevouse_close) {
                        $response = true;
                    }
                } //End of previous not empty
            } //End of else
        } //if array not empty
        return $response;
    } //End of is_previous_candle_is_blue

    public function prededding_candle_status($coin_symbol, $recommended_statuses)
    {
        $this->CI->mongo_db->order_by(array('timestampDate' => -1));
        $this->CI->mongo_db->limit(1);
        $where['coin'] = $coin_symbol;
        $where['global_swing_status'] = array('$in' => $recommended_statuses);
        $this->CI->mongo_db->where($where);
        $responseobj = $this->CI->mongo_db->get('market_chart');
        $responseArr = iterator_to_array($responseobj);
        $resp = false;
        if (!empty($responseArr)) {
            $resp = true;
        }
        return $resp;
    } //End of prededding_candle_status

    public function get_max_marker_value_in_last_five_hour($coin_symbol)
    {
        $previous_date = date('Y-m-d H:i:s', strtotime('-5 hour'));
        $previous_date = $this->CI->mongo_db->converToMongodttime($previous_date);
        $current_date = $this->CI->mongo_db->converToMongodttime($current_date);
        $this->CI->mongo_db->limit(1);
        $this->CI->mongo_db->order_by(array('market_value' => -1));
        $where['coin'] = $coin_symbol;
        $where['time'] = array('$gte' => $previous_date);
        $this->CI->mongo_db->where($where);
        $data = $this->CI->mongo_db->get('market_price_history');
        $data = iterator_to_array($data);
        $max_market_price = 0;
        if (!empty($data)) {
            $max_market_price = $data[0]['market_value'];
        }
        return $max_market_price;
    } //End of get_max_marker_value_in_last_five_hour

    public function get_min_marker_value_in_last_five_hour($coin_symbol)
    {
        $previous_date = date('Y-m-d H:i:s', strtotime('-5 hour'));
        $previous_date = $this->CI->mongo_db->converToMongodttime($previous_date);
        $current_date = $this->CI->mongo_db->converToMongodttime($current_date);
        $this->CI->mongo_db->limit(1);
        $this->CI->mongo_db->order_by(array('market_value' => 1));
        $where['coin'] = $coin_symbol;
        $where['time'] = array('$gte' => $previous_date);
        $this->CI->mongo_db->where($where);
        $data = $this->CI->mongo_db->get('market_price_history');
        $data = iterator_to_array($data);
        $min_market_price = 0;
        if (!empty($data)) {
            $min_market_price = $data[0]['market_value'];
        }
        return $min_market_price;
    } //End of get_min_marker_value_in_last_five_hour

    public function check_time_of_last_trading_activity($coin_symbol, $trigger_type, $order_type, $trade_type)
    {

        $prevouse_date = date('Y-m-d H:i:s', strtotime('-3 minute'));
        $date = $this->CI->mongo_db->converToMongodttime($prevouse_date);
        $this->CI->mongo_db->order_by(array('created_time_obj' => -1));

        $where['created_time_obj'] = array('$gte' => $date);
        $where['coin'] = $coin_symbol;
        $where['trigger_type'] = $trigger_type;
        $where['order_type'] = $order_type;
        $where['trade_type'] = $trade_type;

        $this->CI->mongo_db->where($where);
        $this->CI->mongo_db->limit(1);

        $res = $this->CI->mongo_db->get('order_time_track_collection');
        $res_arr = iterator_to_array($res);
        $result = false;
        if (!empty($res_arr)) {
            $result = true;
        }
        return $result;

    } //End of is_order_is_created_just_now

    public function is_order_already_not_send($buy_orders_id)
    {
        $buy_orders_id_obj = $this->CI->mongo_db->mongoId($buy_orders_id);
        $this->CI->mongo_db->where(array('buy_orders_id' => $buy_orders_id_obj));
        $response = $this->CI->mongo_db->get('ready_orders_for_sell_ip_based');
        $result = iterator_to_array($response);
        $resp = true;
        if (!empty($result)) {$resp = false;}
        return $resp;
    } //End of is_order_already_not_send




    public function is_order_already_not_send_bam($buy_orders_id)
    {
        $buy_orders_id_obj = $this->CI->mongo_db->mongoId($buy_orders_id);
        $this->CI->mongo_db->where(array('buy_orders_id' => $buy_orders_id_obj));
        $response = $this->CI->mongo_db->get('ready_orders_for_sell_ip_based_bam');
        $result = iterator_to_array($response);
        $resp = true;
        if (!empty($result)) {$resp = false;}
        return $resp;
    } //End of is_order_already_not_send_bam

    public function update_order_trading_status($order_id)
    {
        $upd_data['trading_status'] = 'inprogress';
        $this->CI->mongo_db->where(array('_id' => $order_id));
        $this->CI->mongo_db->set($upd_data);
        //Update data in mongoTable
        $this->CI->mongo_db->update('orders');
    } //End of update_order_trading_status

    public function is_sell_trading_not_in_progress($order_id)
    {
        $where['trading_status'] = 'inprogress';
        $where['_id'] = $order_id;
        $this->CI->mongo_db->where($where);
        //Update data in mongoTable
        $response = $this->CI->mongo_db->get('orders');
        $result = iterator_to_array($response);
        $resp = true;
        if (!empty($result)) {$resp = false;}
        return $resp;
    } ///End of  is_sell_trading_not_in_progress

    public function lock_last_trigger_true_time($coin_symbol, $trading_type, $trigger_type, $account_type)
    {
        $updated_date = date('Y-m-d H:i:s');
        $updated_date = $this->CI->mongo_db->converToMongodttime($updated_date);

        $arr['coin'] = $coin_symbol;
        $arr['trading_type'] = $trading_type;
        $arr['trigger_type'] = $trigger_type;
        $arr['account_type'] = $account_type;

        $this->CI->mongo_db->where($arr);
        $this->CI->mongo_db->limit(1);
        $data_row = $this->CI->mongo_db->get('order_time_track_collection');

        $data_arr = iterator_to_array($data_row);
        $arr['updated_date'] = $account_type;
        if (empty($data_arr)) {
            $this->CI->mongo_db->insert('order_time_track_collection', $arr);
        } else {
            $where['coin'] = $coin_symbol;
            $where['trading_type'] = $trading_type;
            $where['trigger_type'] = $trigger_type;
            $where['account_type'] = $account_type;
            $this->CI->mongo_db->where($where);
            $this->CI->mongo_db->set(array('updated_date' => $updated_date));
            $this->CI->mongo_db->update('order_time_track_collection');
        }
    } //End of lock_last_trigger_true_time

    public function is_last_trigger_true_time($coin_symbol, $trading_type, $trigger_type, $account_type)
    {
        $updated_date = date('Y-m-d H:i:s', strtotime('-180 seconds'));
        if ($trading_type == 'sell') {$updated_date = date('Y-m-d H:i:s', strtotime('-1 minutes'));}
        $updated_date = $this->CI->mongo_db->converToMongodttime($updated_date);
        $where['coin'] = $coin_symbol;
        $where['trading_type'] = $trading_type;
        $where['trigger_type'] = $trigger_type;
        $where['account_type'] = $account_type;
        $where['updated_date'] = array('$gte' => $updated_date);
        $this->CI->mongo_db->where($where);
        $this->CI->mongo_db->limit(1);
        $data_row = $this->CI->mongo_db->get('order_time_track_collection');
        $data_row = iterator_to_array($data_row);
        $resp = false;
        if (!empty($data_row)) {$resp = true;}
        return $resp;
    } //End of is_last_trigger_true_time

    public function get_every_5_second_in_an_hour($date)
    {
        $minute = 0;
        $minutes_arr = array();
        for ($index = 0; $index <= 0; $index++) {
            $start = 5 * $index;
            $end = $start + 5;
            $start_minute = date('Y-m-d H:i:s', strtotime('+' . $start . ' seconds', strtotime($date)));
            $end_minute = date('Y-m-d H:i:s', strtotime('+' . $end . ' seconds', strtotime($date)));
            $minutes_arr[$start_minute] = $end_minute;
        } //%%%%%%%%%%%%% -- End of for loop -- %%%%%%%%%%%%%
        return $minutes_arr;
    } //End of get_every_minute_in_an_hour

    public function list_last_candle($coin_symbol)
    {
        $this->CI->mongo_db->limit(1);
        $this->CI->mongo_db->where(array('coin' => $coin_symbol));
        $this->CI->mongo_db->order_by(array('timestampDate' => -1));
        $data = $this->CI->mongo_db->get('market_chart');
        $data = iterator_to_array($data);
        return $data[0];
    } //End of list_last_candle

    public function calculate_one_minute_rolling_volume($coin_symbol)
    {

        $created_date = date("Y-m-d H:i:00", strtotime("-1 minute"));
        $created_date_mongo = $this->CI->mongo_db->converToMongodttime($created_date);
        $search_array['coin'] = $coin_symbol;
        $search_array['created_date']['$gte'] = $created_date_mongo;
        $this->CI->mongo_db->where($search_array);
        $iterator = $this->CI->mongo_db->get('market_trades');

        $bid = 0;
        $ask = 0;
        $buy = 0;
        $sell = 0;
        foreach ($iterator as $key => $value) {
            $quantity = $value['quantity'];
            if ($value['maker'] == 'true') {
                $bid += $quantity;
            }
            if ($value['maker'] == 'false') {
                $ask += $quantity;
            }
            if ($value['type'] == 'buy') {
                $buy += $quantity;
            }
            if ($value['type'] == 'sell') {
                $sell += $quantity;
            }
        }
        $retArr['bid'] = $bid;
        $retArr['ask'] = $ask;
        $retArr['buy'] = $buy;
        $retArr['sell'] = $sell;
        return $retArr;
    } //End of calculate_one_minute_rolling_volume

    public function get_user_porfitable_lth_order($admin_id, $coin_symbol)
    {

        if (!empty($coin_symbol)) {
            if (is_array($coin_symbol)) {
                $search_schema['symbol'] = array('$in' => $coin_symbol);
            } else {
                $search_schema['symbol'] = $coin_symbol;
            }
        } // %%%% -- End of if Coin not empty -- %%%%%%%%%%

        $search_schema['admin_id'] = $admin_id;
        $search_schema['status'] = 'LTH';
        $search_schema['is_sell_order'] = 'yes';
        $this->CI->mongo_db->where($search_schema);
        $buy_orders_arr = $this->CI->mongo_db->get('buy_orders');
        $buy_orders_arr = iterator_to_array($buy_orders_arr);
        return $buy_orders_arr;
    } //%%%%%%% -- End of get_user_porfitable_lth_order -- %%%%%

    public function fetch_lth_settings()
    {
        $resp = $this->CI->mongo_db->get("lth_user_setting");
        return iterator_to_array($resp);
    } //End of fetch_lth_settings

    public function fetch_lth_comulative_percentage_settings()
    {
        $where['comulative_percentage'] = array('$gt' => 0);
        $this->CI->mongo_db->where($where);
        $resp = $this->CI->mongo_db->get("lth_user_setting");
        return iterator_to_array($resp);
    } //End of fetch_lth_comulative_percentage_settings

    public function list_market_trends($coin)
    {
        $search_schema['coin'] = $coin;
        $this->CI->mongo_db->where($search_schema);
        $data_row = $this->CI->mongo_db->get('market_trending');
        $row = iterator_to_array($data_row);
        $resp = array();
        if (!empty($row)) {
            $resp = $row[0];
        }
        return $resp;
    } // End of list_market_trends

    public function grep_rules($pattern, $input, $flags = 0, $type)
    {
        $keys = preg_grep($pattern, array_keys($input), $flags);
        $vals = array();
        foreach ($keys as $key) {
            $exp_arr = explode("_", $key);
            unset($exp_arr[0]);
            unset($exp_arr[1]);

            $last = end($exp_arr);
            $second_last = prev($exp_arr);
            $exp_arr = array_values($exp_arr);
            unset($exp_arr[count($exp_arr) - 1]);
            $new_key = implode('_', $exp_arr);
            if ($last == $type) {
                $vals[$new_key] = $input[$key];
            } else if ($last == 'apply') {
                if ($second_last == $type) {
                    $vals[$new_key] = $input[$key];
                }
            }
        } //End of foreach
        return $vals;
    } //End of grep_rules

    public function get_rule($pattern, $input, $flags = 0)
    {
        $keys = preg_grep($pattern, array_keys($input), $flags);
        $vals = array();
        foreach ($keys as $key) {
            $vals[$key] = $input[$key];
        } //End of foreach
        return $vals;
    } //End of grep_rules

    public function is_last_order_time_difference_greater_then_thirty_minute($admin_id, $coin_symbol)
    {

        $time_for_last_trade = date('Y-m-d H:i:s', strtotime('-30 minutes'));
        $time_for_last_trade = $this->CI->mongo_db->converToMongodttime($time_for_last_trade);
        $this->CI->mongo_db->limit(1);
        $search_criteria['admin_id'] = $admin_id;
        $search_criteria['created_date'] = array('$gte' => $time_for_last_trade);
        $search_criteria['symbol'] = $coin_symbol;
        $search_criteria['status'] = 'FILLED';
        $this->CI->mongo_db->where($search_criteria);
        $data = $this->CI->mongo_db->get('buy_orders');
        $data = iterator_to_array($data);
        $resp = true;

        if (!empty($data)) {
            $resp = false;
        }
        return $resp;
    } //End of is_last_order_time_difference_greater_then_thirty_minute

    public function list_candles($coin_symbol, $limit)
    {
        $this->CI->mongo_db->limit($limit);
        $this->CI->mongo_db->order_by(array('timestampDate' => -1));
        $where['coin'] = $coin_symbol;
        $this->CI->mongo_db->where($where);
        $row = $this->CI->mongo_db->get('market_chart');
        return iterator_to_array($row);
    } //End of list_candles

    public function list_system_global_coin()
    {
        $where_arr = array(
            'user_id' => 'global',
            'exchange_type' => 'binance',
        );

        $this->CI->mongo_db->sort(array('_id' => 1));
        // $this->CI->mongo_db->where(array('user_id' => 'global'));
        $this->CI->mongo_db->where($where_arr);
        $get_coins = $this->CI->mongo_db->get('coins');
        $row_data = iterator_to_array($get_coins);
        $coin_arr = array();

        foreach ($row_data as $row) {
            array_push($coin_arr, $row['symbol']);
        } //End of foreach
        return $coin_arr;
    } //End of list_system_global_coin

    //%%%%%%%%%%%%%%% -- Binance Buy Part -- %%%%%%%%%

    //binance_buy_auto_market_order_live
    public function binance_buy_auto_market_order_live($id, $quantity, $market_value, $symbol, $user_id)
    {
        // --- %%% call function %%%% ----
        $this->update_user_coin_balance($user_id, $symbol);
        $quantity = $this->remove_min_step_size_notational_error($symbol, $quantity);

        $balance = $this->CI->binance_api->get_account_balance($symbol, $user_id);

        $log_msg = "Avaliable Balance of <bold>" . $symbol . "</bold>  is :" . $balance;

        $this->record_order_log($id, $log_msg, 'avaliable_balance', 'yes');

        $min_notational_quantity = $this->is_min_quantity_notational_error($symbol, 'buy');
        $buy_order_arr = $this->get_buy_order($id);
        $buy_parent_id = $buy_order_arr['buy_parent_id'];

        if (($quantity < $min_notational_quantity) && ($balance < $min_notational_quantity)) {
            $log_msg = "Buy Order got Custom  Min notational <span style='color:red;    font-size: 14px;'><b>ERROR</b></span>";

            $this->record_order_log($id, $log_msg, 'buy_error', 'yes');

            $log_msg = "Order Quantity " . $quantity;
            $this->record_order_log($id, $log_msg, 'buy_error', 'yes');

            $log_msg = "Min Required Order Quantity " . $min_notational_quantity;
            $this->record_order_log($id, $log_msg, 'buy_error', 'yes');

            $created_date = date('Y-m-d G:i:s');
            $upd_data22 = array(
                'status' => 'error',
                'modified_date' => $this->CI->mongo_db->converToMongodttime($created_date),
            );
            $this->CI->mongo_db->where(array('_id' => $id));
            $this->CI->mongo_db->set($upd_data22);
            //Update data in mongoTable
            $this->CI->mongo_db->update('buy_orders');

            $buy_parent_id = $buy_order_arr['buy_parent_id'];
            $admin_id = $buy_order_arr['admin_id'];
            if ($buy_parent_id) {
                $this->pause_parent_order($buy_parent_id, $admin_id);
            }

            return false;
        }

        $created_date = date("Y-m-d G:i:s");
        $api_key_check = $this->check_user_api_key_set($user_id);
        if ($buy_order_arr['status'] == 'new' && $buy_order_arr['status'] != 'canceled' && $api_key_check) {

            ///////////////////////Update Status Before Submitted to Binance///////////////
            $upd_status = array(
                'status' => 'submitted',
                'modified_date' => $this->CI->mongo_db->converToMongodttime($created_date),
                'buy_date' => $this->CI->mongo_db->converToMongodttime($created_date),
            );
            $this->CI->mongo_db->where(array('_id' => $id));
            $this->CI->mongo_db->set($upd_status);
            $this->CI->mongo_db->update('buy_orders');
            ///////////////////////Update Status Before Submitted to Binance///////////////

            //Submit Market Order to Binance
            $order = $this->CI->binance_api->place_buy_market_order($symbol, $quantity, $user_id);
            $balance = $this->get_balance($symbol, $user_id);

            // --- %%% call function %%%% ----
            $this->update_user_coin_balance($user_id, $symbol);

            if ($order['orderId'] == "") {

                $order_arr = json_encode($order);
                $order_arr2 = json_decode($order_arr);

                $error_msg = $order_arr2->msg;

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = "Buy Order was got Error (" . $error_msg . ")";
                $this->record_order_log($id, $log_msg, 'buy_error', 'yes');
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////

                /////////////Insert Binance Error Record//////////////////////////////////////
                $this->insert_binance_errors($id, $error_msg, 'buy', $user_id, $symbol);
                /////////////End Insert Binance Error Record//////////////////////////////////
                $this->pause_parent_order($buy_parent_id, $user_id);
                return array('error' => $error_msg);

            } else {

                $upd_data = array(
                    'market_value' => (float) $market_value,
                    'status' => 'submitted',
                    'modified_date' => $this->CI->mongo_db->converToMongodttime($created_date),
                    'buy_date' => $this->CI->mongo_db->converToMongodttime($created_date),
                    'binance_order_id' => $order['orderId'],
                );

                $this->CI->mongo_db->where(array('_id' => $id));
                $this->CI->mongo_db->set($upd_data);

                //Update data in mongoTable
                $this->CI->mongo_db->update('buy_orders');

                ////////////////////// Set Notification //////////////////
                $message = "Buy Market Order is <b>SUBMITTED</b>";
                $message2 = "<strong>" . $symbol . "</strong> Market Trade is <b style='color:#2ca634'>SUBMITTED</b> for Buy";
                $this->add_notification($id, 'buy', $message, $user_id);
                $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
                //////////////////////////////////////////////////////////

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = "Buy Market Order was <b>SUBMITTED</b>";
                $this->record_order_log($id, $log_msg, 'buy_submitted', 'yes');
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////

            }

        } //End if Order is New

        return true;

    } //end binance_buy_auto_market_order_live

    public function update_user_coin_balance($user_id, $symbol)
    {
        $balance = $this->CI->binance_api->get_account_balance($symbol, $user_id);
        $upd = $this->update_coin_balance($symbol, $balance, $user_id);
        $symbol = "BTC";
        $balance = $this->CI->binance_api->get_bitcoin_balance($symbol, $user_id);
        $upd = $this->update_coin_balance($symbol, $balance, $user_id);

        $symbol = "BNBBTC";
        $balance = $this->CI->binance_api->get_account_balance($symbol, $user_id);
        $upd = $this->update_coin_balance($symbol, $balance, $user_id);

    } // %%% -- End of update_user_coin_balance -- %%%

    public function update_coin_balance($coin, $balance, $user_id)
    {

        $this->CI->mongo_db->where(array('symbol' => $coin, 'user_id' => $user_id));
        $bal_arr = $this->CI->mongo_db->get('coins');
        $bal = iterator_to_array($bal_arr);

        $upd_data = array(
            'symbol' => $coin,
            'user_id' => $user_id,
            'coin_balance' => $balance,
        );
        if (count($bal) == 0) {
            $ins = $this->CI->mongo_db->insert('coins', $upd_data);
        } else {
            $this->CI->mongo_db->where(array('symbol' => $coin, 'user_id' => $user_id));
            $this->CI->mongo_db->set($upd_data);
            $ins = $this->CI->mongo_db->update('coins', $upd_data);
        }

        return $ins;
    } // %%%%%%%%%% -- End of update_coin_balance -- %%%%%%%%

    public function remove_min_step_size_notational_error($symbol, $quantity)
    {
        $search_array['symbol'] = $symbol;
        $this->CI->mongo_db->where($search_array);
        $res = $this->CI->mongo_db->get('market_min_notation');
        $min_notation_arr = iterator_to_array($res);

        $stepSize = $min_notation_arr[0]->stepSize;
        $flaot_point = $this->numberOfDecimals($stepSize + 0);

        if ($stepSize >= 1) {
            $quantity = floor($quantity);
        } else {
            $quantity = floor($quantity);
            //$quantity  = round($quantity,$flaot_point,PHP_ROUND_HALF_DOWN);
        }
        return $quantity;
    } //End of is_min_step_size_notational_error

    public function numberOfDecimals($value)
    {
        if ((int) $value == $value) {
            return 0;
        } else if (!is_numeric($value)) {
            return false;
        }
        return strlen($value) - strrpos($value, '.') - 1;
    } //End of numberOfDecimals

    public function is_min_quantity_notational_error($symbol, $type)
    {

        $search_array['symbol'] = $symbol;
        $this->CI->mongo_db->where($search_array);
        $res = $this->CI->mongo_db->get('market_min_notation');
        $min_notation_arr = iterator_to_array($res);
        $min_notation = $min_notation_arr[0]->min_notation;

        $market_value = $this->market_price($symbol);
        $min_quantity = $min_notation / $market_value;

        $percentage = 1.1;
        if ($type == 'sell') {
            $percentage = 1.02;
        }
        return $min_required_quantity = $min_quantity * $percentage;
    } //End of is_min_notational_error

    public function pause_parent_order($buy_parent_id, $admin_id)
    {
        $created_date = date('Y-m-d G:i:s');
        $upd_data22 = array(
            'pause_status' => 'pause',
            'modified_date' => $this->CI->mongo_db->converToMongodttime($created_date),
        );
        $this->CI->mongo_db->where(array('_id' => $buy_parent_id));
        $this->CI->mongo_db->set($upd_data22);
        //Update data in mongoTable
        $this->CI->mongo_db->update('buy_orders');

        $log_msg = 'Order <span style="color:orange;    font-size: 14px;"><b>PAUSED</b></span> Due to Low Quantity By System';
        $this->record_order_log($buy_parent_id, $log_msg, 'buy_error', 'yes');

        $message2 = "<strong>" . $symbol . "</strong> Order <span style='color:orange;    font-size: 14px;'><b>PAUSED</b></span> Due to Low Quantity By System";
        $this->add_notification_for_app($buy_parent_id, 'buy_alert', 'low', $message2, $admin_id, $symbol);

    } //%%%%%%%% End of pause_parent_order

    public function check_user_api_key_set($user_id)
    {
        $this->CI->mongo_db->where(array("_id" => $user_id));
        $get_arr = $this->CI->mongo_db->get('users');
        $settings_arr = iterator_to_array($get_arr);
        $settings_arr = $settings_arr[0];

        if ($settings_arr['api_key'] != '' && $settings_arr['api_key'] != '') {
            $check_api_settings = true;
        } else {
            $check_api_settings = false;
        }
        return $check_api_settings;

    } //End of check_user_api_key_set

    public function get_balance($global_symbol, $admin_id)
    {

        $this->CI->mongo_db->where(array('user_id' => $admin_id, 'symbol' => $global_symbol));
        $get_coins = $this->CI->mongo_db->get('coins');
        $coins_arr = iterator_to_array($get_coins);
        $coins_arr = $coins_arr[0];
        return $coins_arr['coin_balance'];

    } //%%%%%%%%%% -- End of get balance --

    //insert_binance_errors
    public function insert_binance_errors($id, $error_msg, $type, $user_id, $symbol)
    {
        $created_date = date('Y-m-d G:i:s');
        //Update Order Record
        $upd_data = array(
            'modified_date' => $this->CI->mongo_db->converToMongodttime($created_date),
            'status' => 'error',
        );

        $this->CI->mongo_db->where(array('_id' => $id));
        $this->CI->mongo_db->set($upd_data);

        //Update data in mongoTable
        if ($type == "sell") {
            $this->CI->mongo_db->update('orders');
        } else {
            $this->CI->mongo_db->update('buy_orders');
        }

        ////////////////////// Set Notification //////////////////
        $message = ucfirst($type) . " Order got <b>ERROR</b>";
        $message2 = "<strong>" . $symbol . "</strong> " . ucfirst($type) . " Trade got <b style='color:#fe7481'>ERROR</b> Because " . $error_msg;
        $this->add_notification($id, $type, $message, $user_id);
        $this->add_notification_for_app($id, 'trading_alerts', 'high', $message2, $user_id, $symbol);
        //////////////////////////////////////////////////////////

        return true;

    } //end insert_binance_errors

    //%%%%%%%%%%%%%%%%%% -- -- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

    //binance_sell_auto_market_order_live
    public function binance_sell_auto_market_order_live($id, $quantity, $market_value, $symbol, $user_id, $buy_order_id)
    {

        $this->update_user_coin_balance($user_id, $symbol);

        $balance = $this->CI->binance_api->get_account_balance($symbol, $user_id);

        $log_msg = "Send Qty before  <bold>" . $symbol . "</bold>  is :" . $quantity;
        $this->record_order_log($buy_order_id, $log_msg, 'avaliable_balance', 'yes');

        $log_msg = "Avaliable Balance of <bold>" . $symbol . "</bold>  is :" . $balance;
        $this->record_order_log($buy_order_id, $log_msg, 'avaliable_balance', 'yes');

        $quantity = $this->remove_min_step_size_notational_error($symbol, $quantity);

        $min_notational_quantity = $this->is_min_quantity_notational_error($symbol, 'sell');

        $buy_order_arr = $this->get_buy_order($buy_order_id);

        $log_msg = "Send Qty After <bold>" . $symbol . "</bold>  is :" . $quantity;
        $this->record_order_log($buy_order_id, $log_msg, 'avaliable_balance', 'yes');

        if (($quantity < $min_notational_quantity) || ($quantity > $balance)) {

            $log_msg = "Buy Order got  Custom  Min Notational <span style='color:red;    font-size: 14px;'><b>ERROR</b></span>";
            $this->record_order_log($buy_order_id, $log_msg, 'avaliable_balance', 'yes');

            $log_msg = "Order Quantity " . $quantity;
            $this->record_order_log($buy_order_id, $log_msg, 'avaliable_balance', 'yes');

            $log_msg = "Min Required Order Quantity " . $min_notational_quantity;
            $this->record_order_log($buy_order_id, $log_msg, 'avaliable_balance', 'yes');

            $created_date = date('Y-m-d G:i:s');
            $upd_data22 = array(
                'status' => 'error',
                'modified_date' => $this->CI->mongo_db->converToMongodttime($created_date),
            );
            $this->CI->mongo_db->where(array('_id' => $id));
            $this->CI->mongo_db->set($upd_data22);
            //Update data in mongoTable
            $this->CI->mongo_db->update('orders');

            $created_date = date('Y-m-d G:i:s');
            $upd_data22 = array(
                'modified_date' => $this->CI->mongo_db->converToMongodttime($created_date),
            );
            $this->CI->mongo_db->where(array('_id' => $buy_order_id));
            $this->CI->mongo_db->set($upd_data22);
            //Update data in mongoTable
            $this->CI->mongo_db->update('buy_orders');

            $buy_parent_id = $buy_order_arr['buy_parent_id'];
            $admin_id = $buy_order_arr['admin_id'];
            if ($buy_parent_id) {
                $this->pause_parent_order($buy_parent_id, $admin_id);
            }

            return false;
        }

        $sell_order_arr = $this->get_order($id);

        if ($this->is_stepSize($symbol) == 'true') {
            $quantity = floor($quantity);
        }
        $created_date = date("Y-m-d G:i:s");

        $api_key_check = $this->check_user_api_key_set($user_id);

        if ($sell_order_arr['status'] == 'new' && $api_key_check) {

            ///////////////////////Update Status Before Submitted to Binance///////////////
            $upd_status = array(
                'status' => 'submitted',
                'modified_date' => $this->CI->mongo_db->converToMongodttime($created_date),
                'sell_date' => $this->CI->mongo_db->converToMongodttime($created_date),
            );
            $this->CI->mongo_db->where(array('_id' => $id));
            $this->CI->mongo_db->set($upd_status);
            $this->CI->mongo_db->update('orders');

            $update_arr['modified_date'] = $this->CI->mongo_db->converToMongodttime($created_date);
            $this->CI->mongo_db->where(array('sell_order_id' => $id));
            $this->CI->mongo_db->set($update_arr);

            //Update data in mongoTable
            $this->CI->mongo_db->update('buy_orders');
            ///////////////////////Update Status Before Submitted to Binance///////////////

            //Submit Limit Order to Binance
            $order = $this->CI->binance_api->place_sell_market_order($symbol, $quantity, $user_id);
            // --- %%% call function %%%% ----
            $this->update_user_coin_balance($user_id, $symbol);

            if ($order['orderId'] == "") {

                $order_arr = json_encode($order);
                $order_arr2 = json_decode($order_arr);

                $error_msg = $order_arr2->msg;

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = "Sell Order was got Error (" . $error_msg . ")";
                $this->record_order_log($buy_order_id, $log_msg, 'sell_error', 'yes');
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////

                /////////////Insert Binance Error Record/////////////////////////////////////
                $this->insert_binance_errors($id, $error_msg, 'sell', $user_id, $symbol);
                /////////////End Insert Binance Error Record/////////////////////////////////
                return array('error' => $error_msg);
            } else {

                $upd_data = array(
                    'market_value' => $market_value,
                    'status' => 'submitted',
                    'modified_date' => $this->CI->mongo_db->converToMongodttime($created_date),
                    'sell_date' => $this->CI->mongo_db->converToMongodttime($created_date),
                    'binance_order_id' => $order['orderId'],
                );

                $this->CI->mongo_db->where(array('_id' => $id));
                $this->CI->mongo_db->set($upd_data);

                //Update data in mongoTable
                $this->CI->mongo_db->update('orders');

                ////////////////////// Set Notification //////////////////
                $message = "Sell Market Order is <b>SUBMITTED</b>";
                $message2 = "<strong>" . $symbol . "</strong> Market Trade is <b style='color:#2ca634'>SUBMITTED</b> for Sell";
                $this->add_notification($id, 'sell', $message, $user_id);
                $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
                //////////////////////////////////////////////////////////

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = "Sell Market Order was <b>SUBMITTED</b>";
                $this->record_order_log($buy_order_id, $log_msg, 'sell_submitted', 'yes');
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////
            }

        } //End if Order is New

        return true;

    } //end binance_sell_auto_market_order_live

    public function get_order($id)
    {
        $timezone = 'ASIA/KARACHI';

        $this->CI->mongo_db->where(array('_id' => $id));
        $this->CI->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->CI->mongo_db->get('orders');

        foreach ($responseArr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {

                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone($timezone);
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['symbol'] = $valueArr['symbol'];
                $returArr['binance_order_id'] = $valueArr['binance_order_id'];
                $returArr['purchased_price'] = $valueArr['purchased_price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['profit_type'] = $valueArr['profit_type'];
                $returArr['sell_profit_percent'] = $valueArr['sell_profit_percent'];
                $returArr['sell_profit_price'] = $valueArr['sell_profit_price'];
                $returArr['sell_price'] = $valueArr['sell_price'];
                $returArr['market_value'] = $valueArr['market_value'];
                $returArr['order_type'] = $valueArr['order_type'];
                $returArr['status'] = $valueArr['status'];
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['created_date'] = $formated_date_time;
                $returArr['trail_check'] = $valueArr['trail_check'];
                $returArr['trail_interval'] = $valueArr['trail_interval'];
                $returArr['sell_trail_price'] = $valueArr['sell_trail_price'];
                $returArr['buy_order_check'] = $valueArr['buy_order_check'];
                $returArr['buy_order_id'] = $valueArr['buy_order_id'];
                $returArr['buy_order_binance_id'] = $valueArr['buy_order_binance_id'];
                $returArr['stop_loss'] = $valueArr['stop_loss'];
                $returArr['modified_date'] = $valueArr['modified_date'];
                $returArr['application_mode'] = $valueArr['application_mode'];
                $returArr['loss_percentage'] = $valueArr['loss_percentage'];
                $returArr['trigger_type'] = $valueArr['trigger_type'];
            }
        }
        return $returArr;
    } //end get_order

    public function is_stepSize($symbol)
    {
        $this->CI->mongo_db->where(array('symbol' => $symbol));
        $resp = $this->CI->mongo_db->get('market_min_notation');
        $resp = iterator_to_array($resp);
        $resp = $resp[0];
        $stepSize = $resp['stepSize'];
        $resp2 = 'true';
        if ($stepSize < 1) {
            $resp2 = 'false';
        }
        return $resp2;
    } //%%%%%%% is_stepSize %%%%%%%%%%%%%

    public function list_all_profitable_orders($coin_symbol, $market_price, $order_mode, $trigger_type)
    {
        // $where['order_mode'] = $order_mode;
        $where['is_sell_order'] = 'yes';
        $where['status'] = 'FILLED';
        $where['symbol'] = $coin_symbol;
        $where['trigger_type'] = $trigger_type;
        $where['market_value'] = array('$lte' => $market_price);
        $this->CI->mongo_db->where($where);
        $buy_orders_result = $this->CI->mongo_db->get('buy_orders');
        return iterator_to_array($buy_orders_result);
    } //End of list_all_profitable_orders

    public function list_secondry_stop_loss_orders($coin_symbol, $order_mode, $trigger_type, $secondary_stop_loss_rule)
    {
        $where['secondary_stop_loss_rule'] = $secondary_stop_loss_rule;
        //$where['order_mode'] = $order_mode;
        $where['is_sell_order'] = 'yes';
        $where['status'] = 'FILLED';
        $where['symbol'] = $coin_symbol;
        $where['trigger_type'] = $trigger_type;
        $where['secondary_stop_loss_status'] = 'in-active';
        $this->CI->mongo_db->where($where);
        $data = $this->CI->mongo_db->get('buy_orders');
        $data = iterator_to_array($data);
        return $data;
    } //End of list_secondry_stop_loss_orders

    public function is_buy_trading_off($type)
    {
        $where['status'] = 'on';
        $where['type'] = $type;
        $this->CI->mongo_db->where($where);
        $data = $this->CI->mongo_db->get('trading_on_off_collection');
        $data = iterator_to_array($data);
        $resp = false;
        if (empty($data)) {
            $resp = true;
        }
        return $resp;
    } //End of is_buy_trading_on

    public function is_sell_trading_off()
    {
        $where['status'] = 'on';
        $where['type'] = 'sell_on_of_trading';
        $this->CI->mongo_db->where($where);

        $data = $this->CI->mongo_db->get('trading_on_off_collection');
        $data = iterator_to_array($data);
        $resp = false;
        if (empty($data)) {
            $resp = true;
        }
        return $resp;
    } //End of is_sell_trading_off

    public function is_manual_trading_off($type)
    {
        $where['status'] = 'on';
        if ($type == 'buy') {
            $type = 'buy_on_of_manual_trading';
        } else {
            $type = 'sell_on_of_manual_trading';
        }

        $where['type'] = $type;
        $this->CI->mongo_db->where($where);

        $data = $this->CI->mongo_db->get('trading_on_off_collection');
        $data = iterator_to_array($data);
        $resp = false;
        if (empty($data)) {
            $resp = true;
        }
        return $resp;
    } //End of is_buy_trading_on

    public function is_trading_off_by_order_mode($type)
    {
        $where['status'] = 'on';
        if ($type == 'live') {
            $type = 'on_of_live_trading';
        } else {
            $type = 'on_of_test_trading';
        }

        $where['type'] = $type;
        $this->CI->mongo_db->where($where);

        $data = $this->CI->mongo_db->get('trading_on_off_collection');
        $data = iterator_to_array($data);
        $resp = false;
        if (empty($data)) {
            $resp = true;
        }
        return $resp;
    } //End of is_trading_off_by_order_mode

    public function is_trading_off_by_coin($trigger, $coin, $type)
    {
        $filter['trigger'] = $trigger;
        $filter['coin'] = $coin;
        $filter['type'] = $type;
        $filter['status'] = 'yes';
        $this->CI->mongo_db->where($filter);
        $row = $this->CI->mongo_db->get('trading_on_off_collection');
        $row = iterator_to_array($row);
        $resp = true;
        if (!empty($row)) {
            $resp = false;
        }
        return $resp;
    } //End of is_trading_off_by_coin

    public function list_user_lth_setting($admin_id)
    {
        $filter['admin_id'] = $admin_id;
        $this->CI->mongo_db->where($filter);
        $row = $this->CI->mongo_db->get('lth_user_setting');
        $resp = array();
        $row = iterator_to_array($row);
        if (!empty($row)) {
            $resp = $row[0];
        }
        return $resp;
    } //End of list_user_lth_setting

    public function list_user_balance_detail($user_id, $coin_symbol)
    {
        $filter['user_id'] = $user_id;
        $filter['coin_symbol'] = $coin_symbol;
        $this->CI->mongo_db->where($filter);
        $data = $this->CI->mongo_db->get('user_wallet');
        $data = iterator_to_array($data);
        $resp = array();
        if (!empty($data)) {
            $resp = $data[0];
        }
        return $resp;
    } //End of allow_lth_order_percentage

    public function is_commulative_profit_is_on($admin_id, $symbol)
    {
        $where['admin_id'] = $admin_id;
        $where['coin'] = $symbol;
        $where['comulative_percentage'] = array('$gt' => 0);
        $this->CI->mongo_db->where($where);
        $data = $this->CI->mongo_db->get('lth_user_setting');
        $data = iterator_to_array($data);
        $resp = false;
        if (!empty($data)) {
            $resp = true;
        }
        return $resp;
    } //End of is_commulative_profit_is_on

    public function find($collection = 'ready_orders_for_buy_ip_based', $order_by = -1, $limt = 10)
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $this->mongo_db->order_by(array('_id' => $order_by));
        $this->mongo_db->limit($limt);
        $data1 = $this->mongo_db->get($collection);
        $data = iterator_to_array($data1);
        echo '<pre>';
        print_r($data);
    } //End of find

    public function delete_all($collection = '')
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        if ($collection == '') {
            echo 'collection name required';
        } else {
            $db = $this->mongo_db->customQuery();
            $data = $db->$collection->deleteMany(array());
            echo '<pre>';
            print_r($data);
        }

    } //End of find

} //End of class
