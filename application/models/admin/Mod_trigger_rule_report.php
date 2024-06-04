<?php
/****/
class mod_trigger_rule_report extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    public function get_all_customers() {
        // $this->db->dbprefix('users');
        //
        // $this->db->order_by('id DESC');
        //
        // $get_users = $this->db->get('users');
        //
        // //echo $this->db->last_query();
        //
        // $users_arr = $get_users->result_array();
        $this->mongo_db->sort(array('_id' => -1));
        $get_users = $this->mongo_db->get('users');

        $users_arr = iterator_to_array($get_users);

        return $users_arr;

    }

    public function get_customer($user_id = '') {

        if ($user_id == '') {$user_id = $this->session->userdata('cust_id');}

        $this->mongo_db->where(array('_id' => $user_id));
        $get_users = $this->mongo_db->get('users');

        //echo $this->db->last_query();
        $user_arr = iterator_to_array($get_users);
        return $user_arr[0];

    } //end get_user_mongo

    public function get_customer_by_username($user_id = '') {

        if ($user_id == '') {$user_id = $this->session->userdata('cust_id');}

        $this->mongo_db->where(array('username' => $user_id));
        $get_users = $this->mongo_db->get('users');

        //echo $this->db->last_query();
        $user_arr = iterator_to_array($get_users);
        return $user_arr[0];

    } //end get_user_mongo

    public function get_coins() {

        //$user_id = $this->session->userdata('cust_id');

        $this->db->dbprefix('coins');

        //$this->db->where('id', $user_id);

        $get_user = $this->db->get('coins');

        //echo $this->db->last_query(); exit;

        $user_arr = $get_user->result_array();

        return $user_arr;

    } //end get_user_mongo

    public function get_user_orders($skip, $limit) {

        $admin_id = $this->session->userdata('cust_id');
        $search_array = array('admin_id' => $admin_id);
        //Check Filter Data
        $session_post_data = $this->session->userdata('filter_data');
        if ($session_post_data['coin_filter'] != "") {
            $symbol = $session_post_data['coin_filter'];
            $search_array['symbol'] = $symbol;
        }
        if ($session_post_data['type_filter'] != "") {
            $order_type = $session_post_data['type_filter'];
            $search_array['order_type'] = $order_type;
        }
        if ($session_post_data['start_date'] != "" && $session_post_data['end_date'] != "") {
            $created_datetime = date('Y-m-d G:i:s', strtotime($session_post_data['start_date']));
            $orig_date = new DateTime($created_datetime);
            $orig_date = $orig_date->getTimestamp();
            $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
            $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_post_data['end_date']));
            $orig_date22 = new DateTime($created_datetime22);
            $orig_date22 = $orig_date22->getTimestamp();
            $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
            $order_type = $session_post_data['filter_type'];
            $search_array['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
        }
        $connetct = $this->mongo_db->customQuery();
        $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
        $cursor = $connetct->buy_orders->find($search_array, $qr);
        $responseArr = iterator_to_array($cursor);
        //echo "<pre>";   print_r($responseArr );    exit;

        $fullarray = array();
        $total_sold_orders = 0;
        $total_buy_amount = 0;
        $total_sell_amount = 0;
        $total_profit = 0;
        $total_quantity = 0;

        $avg_profit = $total_profit / $total_quantity;
        $return_data['fullarray'] = $responseArr;
        $return_data['total_buy_amount'] = num($total_buy_amount);
        $return_data['error'] = $error;
        $return_data['total_sell_amount'] = num($total_sell_amount);
        $return_data['total_sold_orders'] = $total_sold_orders;

        return $return_data;
    }

    public function count_all() {

        //$list_indexes  =  $this->mongo_db->list_indexes('orders_history_log');

        if ($_SERVER['REMOTE_ADDR'] == '101.50.127.131') {
            //echo "<pre>";   print_r($list_indexes); exit;
        }

        $admin_id = $this->session->userdata('cust_id');
        $search_array = array('admin_id' => $admin_id);
        //Check Filter Data
        $session_post_data = $this->session->userdata('filter_data');
        if ($session_post_data['coin_filter'] != "") {
            $symbol = $session_post_data['coin_filter'];
            $search_array['symbol'] = $symbol;
        }
        if ($session_post_data['type_filter'] != "") {
            $order_type = $session_post_data['type_filter'];
            $search_array['order_type'] = $order_type;
        }

        if ($session_post_data['start_date'] != "" && $session_post_data['end_date'] != "") {

            $created_datetime = date('Y-m-d G:i:s', strtotime($session_post_data['start_date']));
            $orig_date = new DateTime($created_datetime);
            $orig_date = $orig_date->getTimestamp();
            $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

            $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_post_data['end_date']));
            $orig_date22 = new DateTime($created_datetime22);
            $orig_date22 = $orig_date22->getTimestamp();
            $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
            $order_type = $session_post_data['filter_type'];
            $search_array['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
        }
        $connetct = $this->mongo_db->customQuery();
        $qr = array('sort' => array('modified_date' => -1));
        $cursor = $connetct->buy_orders->find($search_array, $qr);
        $responseArr = iterator_to_array($cursor);

        foreach ($responseArr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {
                // To get of sell order record
                if ($valueArr['is_sell_order'] == 'sold') {

                    $market_sold_price = num($valueArr['market_sold_price']);
                    $current_order_price = num($valueArr['market_value']);
                    $quantity = num($valueArr['quantity']);
                    $current_data2222 = $market_sold_price - $current_order_price;
                    $profit_data = ($current_data2222 * 100 / $market_sold_price);
                    $profit_data = number_format((float) $profit_data, 2, '.', '');

                    $total_profit += $quantity * $profit_data;
                    $total_quantity += $quantity;
                }
                // to get order of error
                $id = $valueArr['_id'];
                $this->mongo_db->where(array('order_id' => $id));
                $this->mongo_db->order_by(array('_id' => -1));
                $get = $this->mongo_db->get('orders_history_log');
                $record = iterator_to_array($get);

                foreach ($record as $key => $value222) {

                    if (!empty($value222)) {
                        if ($value222['type'] == 'buy_error' || $value222['type'] == 'sell_error') {
                            $error[] = $value222;
                        }
                    }
                }
            }
        }
        // To get data from
        $avg_profit = $total_profit / $total_quantity;
        $cursor = $connetct->buy_orders->count($search_array);
        $output = array(
            'count' => $cursor,
            'error' => $error,
            'quantity_avg' => $quantity_avg,
            'avg_profit' => $avg_profit,
            'market_sold_price_avg' => $market_sold_price_avg,
            'current_order_price_avg' => $current_order_price_avg,
        );
        return $output;
    }

    public function get_trade_history($coin, $start, $end) {

        $start_date = $this->mongo_db->converToMongodttime($start);

        $end_date = $this->mongo_db->converToMongodttime($end);

        $search_array = array('coin' => $coin,

            'created_date' => array(

                '$gte' => $start_date,

                '$lte' => $end_date,

            ),

        );

        $this->mongo_db->where($search_array);

        //$this->mongo_db->limit(10);

        $get = $this->mongo_db->get('market_trade_history');

        $fullarray = array();

        foreach ($get as $key => $value) {

            $ret_arr = array();

            if (!empty($value)) {

                if ($value['maker'] == 'true') {

                    $type = 'bid';

                } else {

                    $type = 'ask';

                }

                $datetime = $value['created_date']->toDateTime();

                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);

                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');

                $datetime->setTimezone($new_timezone);

                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $ret_arr['price'] = num($value['price']);

                $ret_arr['quantity'] = number_format_short($value['quantity']);

                $ret_arr['market_type'] = $type;

                $ret_arr['type'] = $value['type'];

                $ret_arr['maker'] = $value['maker'];

                $ret_arr['created_date'] = $formated_date_time;

                $ret_arr['coin'] = $value['coin'];

                $ret_arr['status'] = $value['status'];

                $fullarray[] = $ret_arr;

            }

        }

        return $fullarray;

    }

    public function get_price_history($coin_symbol, $start_time, $end_time) {

        $datetime2 = $this->mongo_db->converToMongodttime($start_time);

        $datetime4 = $this->mongo_db->converToMongodttime($end_time);

        $this->mongo_db->where(array("coin" => $coin_symbol, 'time' => array('$lte' => $datetime4, '$gte' => $datetime2)));

        $this->mongo_db->order_by(array('time' => -1));

        $ins = $this->mongo_db->get("market_price_history");

        $market_value_arr = iterator_to_array($ins);

        $market_value = array();

        foreach ($market_value_arr as $key => $value) {

            $returArr = array();

            if (!empty($value)) {

                $datetime = $value['time']->toDateTime();

                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);

                $datetime->format('Y-m-d H:i:s');

                $new_timezone = new DateTimeZone('Asia/Karachi');

                $datetime->setTimezone($new_timezone);

                $formated_date_time = $datetime->format('Y-m-d H:i:s');

                $returArr['time'] = $formated_date_time;

                $returArr['market_price'] = num($value['market_value']);

                $market_value[] = $returArr;

            }

        }

        return $market_value;

    }

    public function get_parent_orders() {

        $search_array = array('parent_status' => 'parent', 'inactive_status' => array('$ne' => 'inactive'), 'trigger_type' => 'barrier_trigger');

        $connetct = $this->mongo_db->customQuery();

        $cursor = $connetct->buy_orders->find($search_array);

        $responseArr = iterator_to_array($cursor);

        foreach ($responseArr as $valueArr) {

            $returArr = array();

            if (!empty($valueArr)) {

                $datetime = $valueArr['created_date']->toDateTime();

                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);

                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');

                $datetime->setTimezone($new_timezone);

                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];

                $returArr['symbol'] = $valueArr['symbol'];

                $returArr['quantity'] = $valueArr['quantity'];

                $returArr['trigger_type'] = $valueArr['trigger_type'];

            }

            $fullarray[] = $returArr;

        }

        return $fullarray;

    }

    public function get_order_log($order_id, $start_date, $end_date) {

        $this->mongo_db->where(array('order_id' => $order_id));

        //$start_date11 = date('Y-m-d H:i:s', strtotime($start_date));

        $start_date1 = $this->mongo_db->converToMongodttime($start_date);

        //$end_date11 = date('Y-m-d H:i:s', strtotime($end_date));

        $end_date1 = $this->mongo_db->converToMongodttime($end_date);

        //$this->mongo_db->where('coin', 'NCASHBTC');

        $this->mongo_db->where_gte('created_date', $start_date1);

        $this->mongo_db->where_lte('created_date', $end_date1);

        $this->mongo_db->sort(array('_id' => 1));

        $responseArr = $this->mongo_db->get('orders_history_log');

        $timezone = $this->session->userdata('timezone');

        $fullarray = array();

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

                $returArr['order_id'] = $valueArr['order_id'];

                $returArr['type'] = $valueArr['type'];

                $returArr['log_msg'] = $valueArr['log_msg'];

                $returArr['created_date'] = $formated_date_time;

            }

            $fullarray[] = $returArr;

        }

        return $fullarray;

    }

}

?>
