<?php

class Mod_buy_orders extends CI_Model {

	public $global_candel_status = array();

	function __construct() {
		parent::__construct();
	}

	//get_buy_orders
    public function get_buy_orders($skip, $limit) {

        $admin_id = $this->session->userdata('admin_id');
        $application_mode = $this->session->userdata('global_mode');
        $timezone = $this->session->userdata('timezone');

        if ($timezone == '') {
            $timezone = 'ASIA/KARACHI';
        }
        //Check Filter Data
        $session_post_data = $this->session->userdata('filter-data-buy');

        $search_array = array('admin_id' => $admin_id, 'application_mode' => $application_mode);
        //$search_array = array('admin_id'=> $admin_id);

        if ($session_post_data['filter_coin'] != "") {

            $symbol = $session_post_data['filter_coin'];
            $search_array['symbol'] = array('$in' => $symbol);
        }
        if ($session_post_data['filter_type'] != "") {

            $order_type = $session_post_data['filter_type'];
            $search_array['order_type'] = $order_type;
        }

        if ($session_post_data['filter_level'] != "") {
            $order_level = $session_post_data['filter_level'];
            $search_array['order_level'] = $order_level;
        }

        if ($session_post_data['filter_trigger'] != "") {

            $trigger_type = $session_post_data['filter_trigger'];
            $search_array['trigger_type'] = $trigger_type;
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

        $skip_sold = $skip;
        $skip_pending = $skip;
        // if($skip ==0){
        //     $this->session->set_userdata(array('skip_sold'=>0,'skip_pending'=>0));
        // }

        // $skip_sold = $this->session->userdata('skip_sold');
        // $skip_pending = $this->session->userdata('skip_pending');

        $qr_sold = array('skip' => $skip_sold, 'sort' => array('modified_date' => -1), 'limit' => $limit);
        $qr_pending = array('skip' => $skip_pending, 'sort' => array('modified_date' => -1), 'limit' => $limit);

        $sold_count = $connetct->sold_buy_orders->count($search_array, $qr_sold);
        $pending_count = $connetct->buy_orders->count($search_array, $qr_pending);

        $total_count = $sold_count + $pending_count;

        $sold_percentage = ($sold_count / $total_count) * 100;
        $pending_percentage = ($pending_count / $total_count) * 100;

        $pending_limit = (20 / 100) * $pending_percentage;
        $sold_limit = (20 / 100) * $sold_percentage;

        $pending_options = array('skip' => $skip_pending, 'sort' => array('modified_date' => -1), 'limit' => intval($pending_limit));

        $sold_options = array('skip' => $skip_sold, 'sort' => array('modified_date' => -1), 'limit' => intval($sold_limit));

        // $skip_sold = $skip_sold +(int)$sold_limit;
        // $skip_pending = $skip_pending +(int)$pending_limit;
        // $this->session->set_userdata(array('skip_sold'=>$skip_sold,'skip_pending'=>$skip_pending));

        $pending_curser = $connetct->buy_orders->find($search_array, $pending_options);
        $sold_curser = $connetct->sold_buy_orders->find($search_array, $sold_options);

        $pending_arr = iterator_to_array($pending_curser);
        $sold_arr = iterator_to_array($sold_curser);

        $originalArray = array_merge_recursive($pending_arr, $sold_arr);

        foreach ($originalArray as $key => $part) {
            $sort[$key] = (string) $part['modified_date'];
        }

        array_multisort($sort, SORT_DESC, $originalArray);

        $fullarray = array();
        $total_sold_orders = 0;
        $total_buy_amount = 0;
        $total_sell_amount = 0;
        $total_profit = 0;
        $total_quantity = 0;
        foreach ($originalArray as $valueArr) {
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
                $datetime111 = $valueArr['modified_date']->toDateTime();
                $created_date111 = $datetime111->format(DATE_RSS);

                $datetime111 = new DateTime($created_date111);
                $datetime111->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone($timezone);
                $datetime111->setTimezone($new_timezone);
                $formated_date_time111 = $datetime111->format('Y-m-d g:i:s A');
                $returArr['order_level'] = isset($valueArr['order_level']) ? $valueArr['order_level'] : '--';
                $returArr['_id'] = $valueArr['_id'];
                $returArr['symbol'] = $valueArr['symbol'];
                $returArr['binance_order_id'] = isset($valueArr['binance_order_id']) ? $valueArr['binance_order_id'] : 0;
                $returArr['price'] = isset($valueArr['price']) ? $valueArr['price'] : 0;
                $returArr['quantity'] = isset($valueArr['quantity']) ? $valueArr['quantity'] : 0;
                $returArr['order_type'] = isset($valueArr['order_type']) ? $valueArr['order_type'] : 0;
                $returArr['market_value'] = isset($valueArr['market_value']) ? $valueArr['market_value'] : 0;
                $returArr['trail_check'] = isset($valueArr['trail_check']) ? $valueArr['trail_check'] : 0;
                $returArr['trail_interval'] = isset($valueArr['trail_interval']) ? $valueArr['trail_interval'] : 0;
                $returArr['buy_trail_price'] = isset($valueArr['buy_trail_price']) ? $valueArr['buy_trail_price'] : 0;
                $returArr['status'] = isset($valueArr['status']) ? $valueArr['status'] : '';
                $returArr['is_sell_order'] = isset($valueArr['is_sell_order']) ? $valueArr['is_sell_order'] : '';
                $returArr['market_sold_price'] = isset($valueArr['market_sold_price']) ? $valueArr['market_sold_price'] : '';
                $returArr['sell_order_id'] = isset($valueArr['sell_order_id']) ? $valueArr['sell_order_id'] : '';
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['trigger_type'] = isset($valueArr['trigger_type']) ? $valueArr['trigger_type'] : '';
                $returArr['buy_parent_id'] = isset($valueArr['buy_parent_id']) ? $valueArr['buy_parent_id'] : '';
                $returArr['inactive_status'] = isset($valueArr['inactive_status']) ? $valueArr['inactive_status'] : '';

                $returArr['pause_status'] = isset($valueArr['pause_status']) ? $valueArr['pause_status'] : '';
                $returArr['parent_status'] = isset($valueArr['parent_status']) ? $valueArr['parent_status'] : '';
                $returArr['application_mode'] = isset($valueArr['application_mode']) ? $valueArr['application_mode'] : '';
                $returArr['lth_profit'] = isset($valueArr['lth_profit']) ? $valueArr['lth_profit'] : '';
                $returArr['created_date'] = $formated_date_time;
                $returArr['modified_date'] = $formated_date_time111;


                $returArr['is_lth_order'] = isset($valueArr['is_lth_order']) ? $valueArr['is_lth_order'] : '';

                $returArr['sell_profit_percent'] = isset($valueArr['sell_profit_percent']) ? $valueArr['sell_profit_percent'] : '';

                
                if ($valueArr['status'] == 'FILLED') {
                    $total_buy_amount += num($returArr['market_value']);
                }

                if ($returArr['is_sell_order'] == 'sold') {
                    $total_sold_orders += 1;
                    $total_sell_amount += num($returArr['market_sold_price']);
                }

                if ($returArr['is_sell_order'] == 'sold') {

                    $market_sold_price = $returArr['market_sold_price'];
                    $current_order_price = $returArr['market_value'];
                    $quantity = $returArr['quantity'];

                    $current_data2222 = $market_sold_price - $current_order_price;
                    $profit_data = ($current_data2222 * 100 / $market_sold_price);

                    $profit_data = number_format((float) $profit_data, 2, '.', '');

                    // $total_profit += $quantity * $profit_data;
                    // $total_quantity += $quantity;

                    $total_btc = $quantity * (float) $current_order_price;
                    $total_profit += $total_btc * $profit_data;
                    $total_quantity += $total_btc;
                }

            }

            $fullarray[] = $returArr;
        }
        if ($total_quantity == 0) {
            $total_quantity = 1;
        }
        $avg_profit = $total_profit / $total_quantity;

        $return_data['fullarray'] = $fullarray;
        $return_data['total_buy_amount'] = num($total_buy_amount);
        $return_data['total_sell_amount'] = num($total_sell_amount);
        $return_data['total_sold_orders'] = $total_sold_orders;
        $return_data['avg_profit'] = number_format($avg_profit, 2, '.', '');

        return $return_data;
    } //end get_buy_orders

	 public function get_all_user_orders() {
        $admin_id = $this->session->userdata('admin_id');
        $application_mode = $this->session->userdata('global_mode');
        $session_post_data = $this->session->userdata('filter-data-buy');
        $timezone = $this->session->userdata('timezone');
        $search_array = array('admin_id' => $admin_id, 'application_mode' => $application_mode);
        //$search_array = array('admin_id'=> $admin_id);

        if ($session_post_data['filter_coin'] != "") {

            $symbol = $session_post_data['filter_coin'];
            $search_array['symbol'] = array('$in' => $symbol);
        }
        if ($session_post_data['filter_type'] != "") {

            $order_type = $session_post_data['filter_type'];
            $search_array['order_type'] = $order_type;
        }

        if ($session_post_data['filter_level'] != "") {
            $order_level = $session_post_data['filter_level'];
            $search_array['order_level'] = $order_level;
        }

        if ($session_post_data['filter_trigger'] != "") {

            $trigger_type = $session_post_data['filter_trigger'];
            $search_array['trigger_type'] = $trigger_type;
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

//        $connetct = $this->mongo_db->customQuery();
        //        $cursor = $connetct->buy_orders->find($search_array, $qr);

        $connetct = $this->mongo_db->customQuery();

        // $skip_sold = $skip_sold +(int)$sold_limit;
        // $skip_pending = $skip_pending +(int)$pending_limit;
        // $this->session->set_userdata(array('skip_sold'=>$skip_sold,'skip_pending'=>$skip_pending));
        $qr = array('sort' => array('modified_date' => -1));

        $pending_curser = $connetct->buy_orders->find($search_array, $qr);
        $sold_curser = $connetct->sold_buy_orders->find($search_array, $qr);

        $pending_arr = iterator_to_array($pending_curser);
        $sold_arr = iterator_to_array($sold_curser);

        $originalArray = array_merge_recursive($pending_arr, $sold_arr);

//        $responseArr = iterator_to_array($cursor);
        foreach ($originalArray as $valueArr) {
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
                $datetime111 = $valueArr['modified_date']->toDateTime();
                $created_date111 = $datetime111->format(DATE_RSS);

                $datetime111 = new DateTime($created_date111);
                $datetime111->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone($timezone);
                $datetime111->setTimezone($new_timezone);
                $formated_date_time111 = $datetime111->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['symbol'] = $valueArr['symbol'];
                $returArr['binance_order_id'] = isset($valueArr['binance_order_id']) ? $valueArr['binance_order_id'] : 0;
                $returArr['price'] = isset($valueArr['price']) ? $valueArr['price'] : 0;
                $returArr['quantity'] = isset($valueArr['quantity']) ? $valueArr['quantity'] : 0;
                $returArr['order_type'] = isset($valueArr['order_type']) ? $valueArr['order_type'] : 0;
                $returArr['market_value'] = isset($valueArr['market_value']) ? $valueArr['market_value'] : 0;
                $returArr['trail_check'] = isset($valueArr['trail_check']) ? $valueArr['trail_check'] : 0;
                $returArr['trail_interval'] = isset($valueArr['trail_interval']) ? $valueArr['trail_interval'] : 0;
                $returArr['buy_trail_price'] = isset($valueArr['buy_trail_price']) ? $valueArr['buy_trail_price'] : 0;
                $returArr['status'] = isset($valueArr['status']) ? $valueArr['status'] : '';
                $returArr['is_sell_order'] = isset($valueArr['is_sell_order']) ? $valueArr['is_sell_order'] : '';
                $returArr['market_sold_price'] = isset($valueArr['market_sold_price']) ? $valueArr['market_sold_price'] : '';
                $returArr['sell_order_id'] = isset($valueArr['sell_order_id']) ? $valueArr['sell_order_id'] : '';
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['trigger_type'] = isset($valueArr['trigger_type']) ? $valueArr['trigger_type'] : '';
                $returArr['buy_parent_id'] = isset($valueArr['buy_parent_id']) ? $valueArr['buy_parent_id'] : '';
                $returArr['inactive_status'] = isset($valueArr['inactive_status']) ? $valueArr['inactive_status'] : '';
                $returArr['application_mode'] = isset($valueArr['application_mode']) ? $valueArr['application_mode'] : '';
                $returArr['pause_status'] = isset($valueArr['pause_status']) ? $valueArr['pause_status'] : '';
                $returArr['parent_status'] = isset($valueArr['parent_status']) ? $valueArr['parent_status'] : '';
                $returArr['lth_profit'] = isset($valueArr['lth_profit']) ? $valueArr['lth_profit'] : '';

                $returArr['fraction_sell_type'] = isset($valueArr['fraction_sell_type']) ? $valueArr['fraction_sell_type'] : '';

                if ($valueArr['market_value'] != "" && $valueArr['market_sold_price'] != "") {
                    $profit_margin = (($valueArr['market_sold_price'] - $valueArr['market_value']) / $valueArr['market_sold_price']) * 100;
                    $returArr['profit'] = number_format($profit_margin, 3);
                } else {
                    $profit_margin = "-";
                    $returArr['profit'] = $profit_margin;
                }

                $returArr['created_date'] = $formated_date_time;
                $returArr['modified_date'] = $formated_date_time111;

            }
            $fullarray[] = $returArr;
        }

        return $fullarray;

    }

    public function user_order_info() {

        $admin_id = $this->session->userdata('admin_id');
        $application_mode = $this->session->userdata('global_mode');

        $session_post_data = $this->session->userdata('filter-data-buy');

        $search_array = array('admin_id' => $admin_id, 'application_mode' => $application_mode);
        //$search_array = array('admin_id'=> $admin_id);

        if ($session_post_data['filter_coin'] != "") {

            $symbol = $session_post_data['filter_coin'];
            $search_array['symbol'] = array('$in' => $symbol);
        }
        if ($session_post_data['filter_type'] != "") {

            $order_type = $session_post_data['filter_type'];
            $search_array['order_type'] = $order_type;
        }

        if ($session_post_data['filter_level'] != "") {
            $order_level = $session_post_data['filter_level'];
            $search_array['order_level'] = $order_level;
        }

        if ($session_post_data['filter_trigger'] != "") {

            $trigger_type = $session_post_data['filter_trigger'];
            $search_array['trigger_type'] = $trigger_type;
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

        $search_array['status'] = 'FILLED';
        $search_array['is_sell_order'] = 'sold';

        $connetct = $this->mongo_db->customQuery();
        $cursor = $connetct->sold_buy_orders->find($search_array);
        $total_sold_orders = 0;
        $total_profit = 0;
        $total_quantity = 0;
        foreach ($cursor as $key => $value) {

            $total_sold_orders++;
            $market_sold_price = $value['market_sold_price'];
            $current_order_price = $value['market_value'];
            $quantity = $value['quantity'];

            $current_data2222 = $market_sold_price - $current_order_price;
            $profit_data = ($current_data2222 * 100 / $market_sold_price);

            $profit_data = number_format((float) $profit_data, 2, '.', '');
            $total_btc = $quantity * (float) $current_order_price;
            $total_profit += $total_btc * $profit_data;
            $total_quantity += $total_btc;

        }
        if ($total_quantity == 0) {
            $total_quantity = 1;
        }
        $avg_profit = $total_profit / $total_quantity;
        $return_data['total_sold_orders'] = $total_sold_orders;
        $return_data['avg_profit'] = number_format($avg_profit, 2, '.', '');

        return $return_data;
    }

	public function get_buy_orders_by_status($status, $skip, $limit) {

        $admin_id = $this->session->userdata('admin_id');
        $timezone = $this->session->userdata('timezone');
        if ($timezone == '') {
            $timezone = 'ASIA/KARACHI';
        }
        $application_mode = $this->session->userdata('global_mode');

        //Check Filter Data
        $session_post_data = $this->session->userdata('filter-data-buy');

        $search_array = array('admin_id' => $admin_id, 'application_mode' => $application_mode);
        //$search_array = array('admin_id'=> $admin_id);

        if ($session_post_data['filter_coin'] != "") {

            $symbol = $session_post_data['filter_coin'];
            $search_array['symbol'] = array('$in' => $symbol);
        }
        if ($session_post_data['filter_type'] != "") {

            $order_type = $session_post_data['filter_type'];
            $search_array['order_type'] = $order_type;
        }

        if ($session_post_data['filter_trigger'] != "") {

            $trigger_type = $session_post_data['filter_trigger'];
            $search_array['trigger_type'] = $trigger_type;
        }

        if ($session_post_data['filter_level'] != "") {
            $order_level = $session_post_data['filter_level'];
            $search_array['order_level'] = $order_level;
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

        // $cursor = $connetct->buy_orders->find($search_array)->skip($skip)->limit($limit)->sort(array('_id'=>-1));
        if ($status == 'open' || $status == 'sold') {
            if ($status == 'open') {

                $search_array['status'] = array('$in' => array('submitted', 'FILLED'));
                $search_array['is_sell_order'] = 'yes';
                $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
                $cursor = $connetct->buy_orders->find($search_array, $qr);

                /*
                $cursor = $collection->find(array(
                'name' => array('$in' => array('Joe', 'Wendy'))
                 */

                $responseArr = iterator_to_array($cursor);
            } elseif ($status == 'sold') {

                $search_array['status'] = 'FILLED';
                $search_array['is_sell_order'] = 'sold';
                $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
                $cursor = $connetct->sold_buy_orders->find($search_array, $qr);

                $responseArr = iterator_to_array($cursor);
            }
        } elseif ($status == 'parent') {

            $search_array['parent_status'] = 'parent';
            $search_array['status'] = 'new';
            /*$search_array['buy_order_status_new_filled'] = array('$ne' => 'wait_for_buyed');
            $search_array['order_mode'] = array('$in' => array('test_live', 'live', 'test'));*/
            $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            $cursor = $connetct->buy_orders->find($search_array, $qr);

            $responseArr = iterator_to_array($cursor);
        } elseif ($status == 'lth') {
            $search_array['status'] = 'LTH';
            $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            $cursor = $connetct->buy_orders->find($search_array, $qr);

            $responseArr = iterator_to_array($cursor);
        } elseif ($status == 'new') {

            $search_array['status'] = 'new';
            if (($this->session->userdata('admin_id') == 1 || $this->session->userdata('admin_id') == 5) || (isset($_GET['testing']) && $_GET['testing'] == 'testing')) {
            } else {
                $search_array['trigger_type'] = 'no';
            }
            $search_array['price'] = array('$ne' => '');
            $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            $cursor = $connetct->buy_orders->find($search_array, $qr);

            $responseArr = iterator_to_array($cursor);
        } else {
            $search_array['status'] = $status;
            $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            $cursor = $connetct->buy_orders->find($search_array, $qr);

            $responseArr = iterator_to_array($cursor);
        }

        $fullarray = array();
        $total_sold_orders = 0;
        $total_buy_amount = 0;
        $total_sell_amount = 0;
        $total_profit = 0;
        $total_quantity = 0;
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

                if (empty($valueArr['modified_date'])) {
                    $valueArr['modified_date'] = $valueArr['created_date'];
                }
                $datetime111 = $valueArr['modified_date']->toDateTime();
                $created_date111 = $datetime111->format(DATE_RSS);

                $datetime111 = new DateTime($created_date111);
                $datetime111->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone($timezone);
                $datetime111->setTimezone($new_timezone);
                $formated_date_time111 = $datetime111->format('Y-m-d g:i:s A');
                $returArr['order_level'] = isset($valueArr['order_level']) ? $valueArr['order_level'] : '--';
                $returArr['_id'] = $valueArr['_id'];
                $returArr['symbol'] = $valueArr['symbol'];
                $returArr['binance_order_id'] = isset($valueArr['binance_order_id']) ? $valueArr['binance_order_id'] : 0;
                $returArr['price'] = isset($valueArr['price']) ? $valueArr['price'] : 0;
                $returArr['quantity'] = isset($valueArr['quantity']) ? $valueArr['quantity'] : 0;
                $returArr['order_type'] = isset($valueArr['order_type']) ? $valueArr['order_type'] : 0;
                $returArr['market_value'] = isset($valueArr['market_value']) ? $valueArr['market_value'] : 0;
                $returArr['trail_check'] = isset($valueArr['trail_check']) ? $valueArr['trail_check'] : 0;
                $returArr['trail_interval'] = isset($valueArr['trail_interval']) ? $valueArr['trail_interval'] : 0;
                $returArr['buy_trail_price'] = isset($valueArr['buy_trail_price']) ? $valueArr['buy_trail_price'] : 0;
                $returArr['status'] = isset($valueArr['status']) ? $valueArr['status'] : '';
                $returArr['is_sell_order'] = isset($valueArr['is_sell_order']) ? $valueArr['is_sell_order'] : '';
                $returArr['market_sold_price'] = isset($valueArr['market_sold_price']) ? $valueArr['market_sold_price'] : '';
                $returArr['sell_order_id'] = isset($valueArr['sell_order_id']) ? $valueArr['sell_order_id'] : '';
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['trigger_type'] = isset($valueArr['trigger_type']) ? $valueArr['trigger_type'] : '';
                $returArr['buy_parent_id'] = isset($valueArr['buy_parent_id']) ? $valueArr['buy_parent_id'] : '';
                $returArr['inactive_status'] = isset($valueArr['inactive_status']) ? $valueArr['inactive_status'] : '';
                $returArr['application_mode'] = isset($valueArr['application_mode']) ? $valueArr['application_mode'] : '';
                $returArr['pause_status'] = isset($valueArr['pause_status']) ? $valueArr['pause_status'] : '';
                $returArr['parent_status'] = isset($valueArr['parent_status']) ? $valueArr['parent_status'] : '';
                $returArr['lth_profit'] = isset($valueArr['lth_profit']) ? $valueArr['lth_profit'] : '';
                $returArr['fraction_sell_type'] = isset($valueArr['fraction_sell_type']) ? $valueArr['fraction_sell_type'] : '';

                $returArr['created_date'] = $formated_date_time;
                $returArr['modified_date'] = $formated_date_time111;
                $returArr['is_lth_order'] = isset($valueArr['is_lth_order']) ? $valueArr['is_lth_order'] : '';
                $returArr['sell_profit_percent'] = isset($valueArr['sell_profit_percent']) ? $valueArr['sell_profit_percent'] : '';
                
                if ($returArr['status'] == 'FILLED') {
                    $total_buy_amount += num($returArr['market_value']);
                }

                if ($returArr['is_sell_order'] == 'sold') {
                    $total_sold_orders += 1;
                    $total_sell_amount += num($returArr['market_sold_price']);
                }

                if ($returArr['is_sell_order'] == 'sold') {

                    $market_sold_price = $returArr['market_sold_price'];
                    $current_order_price = $returArr['market_value'];
                    $quantity = $returArr['quantity'];

                    $current_data2222 = $market_sold_price - $current_order_price;
                    $profit_data = ($current_data2222 * 100 / $market_sold_price);

                    $profit_data = number_format((float) $profit_data, 2, '.', '');

                    $total_profit += $quantity * $profit_data;
                    $total_quantity += $quantity;
                }

            }

            $fullarray[] = $returArr;
        }
        if ($total_quantity == 0) {
            $total_quantity = 1;
        }
        $avg_profit = $total_profit / $total_quantity;

        $return_data['fullarray'] = $fullarray;
        $return_data['total_buy_amount'] = num($total_buy_amount);
        $return_data['total_sell_amount'] = num($total_sell_amount);
        $return_data['total_sold_orders'] = $total_sold_orders;
        $return_data['avg_profit'] = number_format($avg_profit, 2, '.', '');
        return $return_data;

    } //end get_buy_orders_by_status
	//get_buy_order
	  public function get_buy_order($id) {

        $user_id = $this->session->userdata('admin_id');
        $timezone = $this->session->userdata('timezone');
        if (empty($timezone)) {
            $timezone = "Europe/London";
        }

        $search_array['_id'] = $id;
        // $search_array['admin_id'] = $user_id;
        $this->mongo_db->where($search_array);
        /*$this->mongo_db->sort(array('_id' => 'desc'));*/
        $responseArr = $this->mongo_db->get('buy_orders');
        if ($_SERVER['REMOTE_ADDR'] == '101.50.127.131') {
            $responseArr = iterator_to_array($responseArr);
            //echo "<pre>";  print_r($responseArr); exit;
        }

        // I think no need of this foreach loop here

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
                $returArr['binance_order_id'] = isset($valueArr['binance_order_id']) ? $valueArr['binance_order_id'] : 0;
                $returArr['price'] = isset($valueArr['price']) ? $valueArr['price'] : 0;
                $returArr['quantity'] = isset($valueArr['quantity']) ? $valueArr['quantity'] : 0;
                $returArr['order_type'] = isset($valueArr['order_type']) ? $valueArr['order_type'] : 0;
                $returArr['market_value'] = isset($valueArr['market_value']) ? $valueArr['market_value'] : 0;
                $returArr['trail_check'] = isset($valueArr['trail_check']) ? $valueArr['trail_check'] : 0;
                $returArr['trail_interval'] = isset($valueArr['trail_interval']) ? $valueArr['trail_interval'] : 0;
                $returArr['buy_trail_price'] = isset($valueArr['buy_trail_price']) ? $valueArr['buy_trail_price'] : 0;
                $returArr['status'] = isset($valueArr['status']) ? $valueArr['status'] : '';
                $returArr['is_sell_order'] = isset($valueArr['is_sell_order']) ? $valueArr['is_sell_order'] : '';
                $returArr['market_sold_price'] = isset($valueArr['market_sold_price']) ? $valueArr['market_sold_price'] : '';
                $returArr['sell_order_id'] = isset($valueArr['sell_order_id']) ? $valueArr['sell_order_id'] : '';
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['trigger_type'] = isset($valueArr['trigger_type']) ? $valueArr['trigger_type'] : '';
                $returArr['order_level'] = isset($valueArr['order_level']) ? $valueArr['order_level'] : '';
                $returArr['buy_parent_id'] = isset($valueArr['buy_parent_id']) ? $valueArr['buy_parent_id'] : '';
                $returArr['inactive_status'] = isset($valueArr['inactive_status']) ? $valueArr['inactive_status'] : '';
                $returArr['application_mode'] = isset($valueArr['application_mode']) ? $valueArr['application_mode'] : '';
                $returArr['order_mode'] = isset($valueArr['order_mode']) ? $valueArr['order_mode'] : '';
                $returArr['buy_one_tip_above'] = isset($valueArr['buy_one_tip_above']) ? $valueArr['buy_one_tip_above'] : '';
                $returArr['sell_one_tip_below'] = isset($valueArr['sell_one_tip_below']) ? $valueArr['sell_one_tip_below'] : '';
                $returArr['defined_sell_percentage'] = isset($valueArr['defined_sell_percentage']) ? $valueArr['defined_sell_percentage'] : '';
                $returArr['created_date'] = $formated_date_time;
                $returArr['modified_date'] = $formated_date_time111;
                $returArr['inactive_time'] = isset($valueArr['inactive_time']) ? $valueArr['inactive_time'] : '';
                $returArr['custom_stop_loss_percentage'] = $valueArr['custom_stop_loss_percentage'];
                $returArr['stop_loss_rule'] = $valueArr['stop_loss_rule'];
                $returArr['activate_stop_loss_profit_percentage'] = $valueArr['activate_stop_loss_profit_percentage'];

                $returArr['lth_functionality'] = $valueArr['lth_functionality'];
                $returArr['lth_profit'] = $valueArr['lth_profit'];


                $returArr['un_limit_child_orders'] = $valueArr['un_limit_child_orders'];
                $returArr['secondary_stop_loss_rule'] = $valueArr['secondary_stop_loss_rule'];
                $returArr['secondary_stop_loss_status'] = $valueArr['secondary_stop_loss_status'];
            }
        }

        return $returArr;

    } //end get_buy_order
    public function edit_buy_order_triggers($post_data) {
        extract($post_data);
        $un_limit_child_orders = (isset($post_data['un_limit_child_orders'])) ? $post_data['un_limit_child_orders'] : 'no';
        $admin_id = $this->session->userdata('admin_id');
        $update_array = array(
            'trigger_type' => $trigger_type,
            'order_mode' => $order_mode,
            'quantity' => $quantity,
            'order_type' => $order_type,
            'buy_one_tip_above' => $buy_one_tip_above,
            'sell_one_tip_below' => $sell_one_tip_below,
            'defined_sell_percentage' => $defined_sell_percentage,
            'order_level' => $order_level,
            'inactive_time' => $inactive_time,
            'custom_stop_loss_percentage' => $custom_stop_loss_percentage,
            'stop_loss_rule' => $stop_loss_rule,
            'activate_stop_loss_profit_percentage' => (float) $activate_stop_loss_profit_percentage,
            'lth_functionality' => $lth_functionality,
            'lth_profit' => $lth_profit,
            'un_limit_child_orders' => $un_limit_child_orders,
            'secondary_stop_loss_rule' => $secondary_stop_loss_rule,
            'secondary_stop_loss_status' => 'in-active',
        );

 

        $this->mongo_db->where(array("_id" => $this->mongo_db->mongoId($order_id)));
        $this->mongo_db->set($update_array);
        $this->mongo_db->update("buy_orders");

        $log_msg = "Parent Order is updated quantity changed from " . $old_quantity . " to " . $quantity . " defined sell percentage from " . $old_profit . " to " . $defined_sell_percentage . " and Order Type is Updated to " . strtoupper(str_replace("_", " ", $order_type));

        if (isset($buy_one_tip_above)) {
            $log_msg .= " Buy One Tip Above is YES";
        } else {
            $log_msg .= " Buy One Tip Above is No";
        }

        if (isset($sell_one_tip_below)) {
            $log_msg .= " Sell One Tip Below is YES";
        } else {
            $log_msg .= " Sell One Tip Below is No";
        }

        $this->insert_order_history_log($order_id, $log_msg, 'parent_updated', $admin_id);

        return true;
    }


    public function count_all() {
        $admin_id = $this->session->userdata('admin_id');
        $application_mode = $this->session->userdata('global_mode');

        //Check Filter Data
        $session_post_data = $this->session->userdata('filter-data-buy');

        $search_array = array('admin_id' => $admin_id, 'application_mode' => $application_mode);
        //$search_array = array('admin_id'=> $admin_id);

        if ($session_post_data['filter_coin'] != "") {

            $symbol = $session_post_data['filter_coin'];
            $search_array['symbol'] = array('$in' => $symbol);
        }
        if ($session_post_data['filter_type'] != "") {

            $order_type = $session_post_data['filter_type'];
            $search_array['order_type'] = $order_type;
        }
        if ($session_post_data['filter_trigger'] != "") {

            $trigger_type = $session_post_data['filter_trigger'];
            $search_array['trigger_type'] = $trigger_type;
        }
        if ($session_post_data['filter_level'] != "") {
            $order_level = $session_post_data['filter_level'];
            $search_array['order_level'] = $order_level;
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
        $cursor = $connetct->buy_orders->count($search_array);
        $cursor2 = $connetct->sold_buy_orders->count($search_array);

        // if($cursor2 >$cursor){
        //     $cursor = $cursor2;
        // }
        return $cursor + $cursor2;
    }

	public function count_by_status($status) {
		$admin_id = $this->session->userdata('admin_id');
		$application_mode = $this->session->userdata('global_mode');

		//Check Filter Data
		$session_post_data = $this->session->userdata('filter-data-buy');

		$search_array = array('admin_id' => $admin_id, 'application_mode' => $application_mode);
		//$search_array = array('admin_id'=> $admin_id);

		if ($session_post_data['filter_coin'] != "") {

			$symbol = $session_post_data['filter_coin'];
			$search_array['symbol'] = array('$in' => $symbol);
		}
		if ($session_post_data['filter_type'] != "") {

			$order_type = $session_post_data['filter_type'];
			$search_array['order_type'] = $order_type;
		}
		if ($session_post_data['filter_trigger'] != "") {

			$trigger_type = $session_post_data['filter_trigger'];
			$search_array['trigger_type'] = $trigger_type;
		}
		if ($session_post_data['filter_level'] != "") {
			$order_level = $session_post_data['filter_level'];
			$search_array['order_level'] = $order_level;
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

		// $cursor = $connetct->buy_orders->find($search_array)->skip($skip)->limit($limit)->sort(array('_id'=>-1));
		if ($status == 'open' || $status == 'sold') {
			if ($status == 'open') {

				$search_array['status'] = array('$in' => array('submitted', 'FILLED'));
				$search_array['is_sell_order'] = 'yes';
				$cursor = $connetct->buy_orders->count($search_array);

			} elseif ($status == 'sold') {

				$search_array['status'] = 'FILLED';
				$search_array['is_sell_order'] = 'sold';
				$cursor = $connetct->sold_buy_orders->count($search_array);

			}
		} elseif ($status == 'parent') {
			$search_array['parent_status'] = 'parent';
			$search_array['status'] = 'new';
			$cursor = $connetct->buy_orders->count($search_array);

		} elseif ($status == 'lth') {
			$search_array['status'] = 'LTH';
			$cursor = $connetct->buy_orders->count($search_array);

		} elseif ($status == 'new') {
			$search_array['status'] = 'new';
			if (($this->session->userdata('admin_id') == 1 || $this->session->userdata('admin_id') == 5) || (isset($_GET['testing']) && $_GET['testing'] == 'testing')) {
			} else {
				$search_array['trigger_type'] = 'no';
			}
			$search_array['parent_status'] = array('$ne' => 'parent');
			$cursor = $connetct->buy_orders->count($search_array);

		} else {

			$search_array['status'] = $status;
			$cursor = $connetct->buy_orders->count($search_array);

		}
		// echo "<pre>";
		// print_r($search_array);
		// exit;

		return $cursor;
	}

	public function get_balance($symbol, $user_id) {

		$this->mongo_db->where(array('symbol' => $symbol, 'user_id' => $user_id));
		$get_coin = $this->mongo_db->get('coins');
		$coin_arr = iterator_to_array($get_coin);
		$coin_arr = $coin_arr[0];
		$bal = $coin_arr['coin_balance'];
		return $bal;
	}

	//insert_order_history_log
	public function insert_order_history_log($id, $log_msg, $type, $user_id, $created_date = '') {
		$ins_error = array(
			'order_id' => $this->mongo_db->mongoId($id),
			'log_msg' => $log_msg,
			'type' => $type,
			'created_date' => $this->mongo_db->converToMongodttime($created_date),
		);

		$orders_arr = $this->mongo_db->insert('orders_history_log', $ins_error);

		return true;
	} //end insert_order_history_log

	//add_notification
	public function add_notification($order_id, $type, $message, $admin_id) {

		extract($data);

		$created_date = date('Y-m-d G:i:s');

		$ins_data = array(
			'admin_id' => (trim($admin_id)),
			'order_id' => (trim($order_id)),
			'type' => (trim($type)),
			'message' => (trim($message)),
			'created_date' => (trim($created_date)),
			'status' => 0,
		);

		//Insert the record into the database.

		$this->mongo_db->insert('notification', $ins_data);

		return true;

	} //end add_notification()

	public function change_inactive_status($id) {
		$upd_arr = array(
			'pause_status' => 'pause',
		);

		$this->mongo_db->where(array('_id' => $id));
		$this->mongo_db->set($upd_arr);
		$this->mongo_db->update('buy_orders', $upd_arr);

		$log_msg = "The Order Expiry Date has reached Order Has been Paused";
		$this->insert_order_history_log($buy_orders_id, $log_msg, 'order_paused', $admin_id, $current_date);
		return true;
	} //End of  change_inactive_status

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////                         //////////////////////
	////////////////////                        ////////////////////////////////
	//////////////////// Trigger_1            ////////////////////////////////
	////////////////////                      ////////////////////////////////
	////////////////////                         //////////////////////
	////////////////////                        //////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function create_auto_buy_order_using_trigger_1($date = '', $samulater) {

		$this->mongo_db->where('status', 'new');
		$this->mongo_db->where('trigger_type', 'trigger_1');
		$this->mongo_db->where_ne('buy_order_status_new_filled', 'wait_for_buyed');

		if ($samulater == 'samulater') {
			$this->mongo_db->where('application_mode', 'test');
		} else {
			$this->mongo_db->where('application_mode', 'live');
		}

		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);

		$response_arr = array();
		$response_arr['message'] = 'parent order not found';
		//if parent order exist then creat buy orders
		if (count($buy_orders_arr) > 0) {

			foreach ($buy_orders_arr as $buy_orders) {
				$buy_parent_id = $buy_orders['_id'];
				$coin_symbol = $buy_orders['symbol'];
				$buy_quantity = $buy_orders['quantity'];
				$buy_trigger_type = $buy_orders['trigger_type'];
				$admin_id = $buy_orders['admin_id'];
				$application_mode = $buy_orders['application_mode'];

				//Call function to create orders
				$stop_loss_percent = $this->create_new_order_by_trigger_1($date, $coin_symbol, $buy_orders, $application_mode);

				//Order Filled
				$response_arr['message'] = $this->filled_order_by_trigger_1($date, $coin_symbol, $stop_loss_percent);
			}

		} else {
			$response_arr['message'] = 'No parent Order Found';
		}

		//$this->buy_trigger_2($coin='',$date);
		return $response_arr['message'];
	} //End of create_auto_buy_order_using_trigger_1

	public function create_new_order_by_trigger_1($date, $coin_symbol, $buy_orders, $application_mode) {

		$return_response = '';
		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}
		//////////////////////////////////////////////////////////
		////////////////////// Get current market value///////////
		$where_condition = array('openTime_human_readible' => $current_date, 'coin' => $coin_symbol);
		$this->mongo_db->where($where_condition);
		$current_candel_result = $this->mongo_db->get('market_chart');
		$current_candel_arr = iterator_to_array($current_candel_result);
		$current_low_value = 0;
		if (count($current_candel_arr) > 0) {
			$current_low_value = $current_candel_arr[0]['low'];
		}

		$created_date = date('Y-m-d G:i:s');
		$upd_array = array('created_date' => $created_date, 'current_market_value' => $current_low_value, 'coin' => $coin_symbol);
		$this->mongo_db->set($upd_array);
		$this->mongo_db->where('trigger_type', 'trigger_1');
		$this->mongo_db->update('buy_trigger_process_log');

		////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////
		$where = array('openTime_human_readible' => $prevouse_date, 'coin' => $coin_symbol, 'candle_type' => 'demand');
		$result_arr = array();
		$this->mongo_db->where($where);
		$previouse_candel_result = $this->mongo_db->get('market_chart');
		$previouse_candel_arr = iterator_to_array($previouse_candel_result);

		$this->mongo_db->where(array('coins' => $coin_symbol, 'triggers_type' => 'trigger_1'));
		$res_coin_setting = $this->mongo_db->get('setting_triggers_collections');
		$res_coin_setting_arr = iterator_to_array($res_coin_setting);

		$buy_part_1_price_percent = 22;
		$buy_part_2_price_percent = 52;
		$buy_part_3_price_percent = 87;
		$stop_loss_percent = 40;

		if (count($res_coin_setting_arr) > 0) {
			foreach ($res_coin_setting_arr as $res_coin_setting) {
				$buy_part_1_price_percent = $res_coin_setting['buy_part_1_price_percent'];
				$buy_part_2_price_percent = $res_coin_setting['buy_part_2_price_percent'];
				$buy_part_3_price_percent = $res_coin_setting['buy_part_3_price_percent'];
				$stop_loss_percent = $res_coin_setting['Initail_trail_stop_trigger_1'];
			}
		}

		$parts_arr = array('buy_part_1_price_percent' => $buy_part_1_price_percent, 'buy_part_2_price_percent' => $buy_part_2_price_percent, 'buy_part_3_price_percent' => $buy_part_3_price_percent);

		if (count($previouse_candel_arr) > 0) {

			$high_value = $previouse_candel_arr[0]['high'];
			$low_value = $previouse_candel_arr[0]['low'];
			$differenc_value = $high_value - $low_value;

			////////////////////// Create new buy orders///////////////////////////
			//////////////////////////////////////////////////////////////////////
			/////////////////////////////////////////////////////////////////////
			$index = 1;
			$admin_id = $buy_orders['admin_id'];
			foreach ($parts_arr as $buy_percentage) {

				$buy_price = num($high_value - ($differenc_value * ($buy_percentage / 100)));
				$iniatial_trail_stop = num($buy_price - ($buy_price / 100) * $stop_loss_percent);

				$data_arr = array(
					'price' => $buy_price,
					'quantity' => $buy_orders['quantity'],
					'symbol' => $coin_symbol,
					'order_type' => 'MARKET_ORDER',
					'admin_id' => $admin_id,
					'trigger_type' => 'trigger_1',
					'buy_parent_id' => $buy_orders['_id'],
					'date' => $date,
					'sell_price' => '',
					'iniatial_trail_stop' => $iniatial_trail_stop,
					'status' => 'new',
					'stop_loss_percent' => $stop_loss_percent,
					'created_date' => $this->mongo_db->converToMongodttime($date),
					'trail_check' => 'no',
					'trail_interval' => 0,
					'buy_trail_price' => 0,
					'auto_sell' => 'no',
					'buy_order_status_new_filled' => 'wait_for_buyed',
					'buy_part' => 'part_' . $index,
					'application_mode' => $application_mode,
				);

				//Insert data in mongoTable
				$buy_order_id = $this->mongo_db->insert('buy_orders', $data_arr);
				//////////////////////////////////////////////////////////////////////////////
				////////////////////////////// Order History Log /////////////////////////////
				$log_msg = "Buy part_" . $index . " Order was Created at Price " . $buy_price;

				$this->insert_order_history_log($buy_order_id, $log_msg, 'buy_created', $admin_id, $date);
				////////////////////////////// End Order History Log /////////////////////////
				//////////////////////////////////////////////////////////////////////////////

				////////////////////// Set Notification //////////////////
				$message = "Buy Market Order is <b>Created</b> as status new";
				$this->add_notification($buy_order_id, 'buy', $message, $admin_id);
				//////////////////////////////////////////////////////////
				$index++;

			}

			////////////////////////////// End of create new buy orders/////////
			////////////////////////////////////////////////////////////////////
			///////////////////////////////////////////////////////////////////
		}

		return $stop_loss_percent;
	} //End of  buy_trigger_1_condition

	function filled_order_by_trigger_1($date, $coin_symbol, $stop_loss_percent) {

		$this->mongo_db->where('status', 'new');
		$this->mongo_db->where('trigger_type', 'trigger_1');
		$this->mongo_db->where('buy_order_status_new_filled', 'wait_for_buyed');
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);

		$return_response = '';
		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		$where = array('openTime_human_readible' => $current_date, 'coin' => $coin_symbol);
		$result_arr = array();
		$this->mongo_db->where($where);
		$current_candel_result = $this->mongo_db->get('market_chart');
		$current_candel_arr = iterator_to_array($current_candel_result);
		$arr_response = array();

		if (count($buy_orders_arr)) {
			foreach ($buy_orders_arr as $buy_orders) {
				$id = $buy_orders['_id'];
				$buy_price = $buy_orders['price'];
				$admin_id = $buy_orders['admin_id'];
				$quantity = $buy_orders['quantity'];
				$application_mode = $buy_orders['application_mode'];

				if (count($current_candel_arr) > 0) {
					$high_value = $current_candel_arr[0]['high'];
					$low_value = $current_candel_arr[0]['low'];
					$open = $current_candel_arr[0]['open'];

					if ($application_mode == 'live') {
						$current_market_value = $this->mod_dashboard->get_market_value($coin_symbol);
						$market_price = $current_market_value;
						$low_value = $current_market_value;
					} else {

						$market_price = $low_value;
					}

					if ($low_value <= $buy_price && $high_value >= $buy_price) {

						//Call binance
						if ($application_mode == 'live') {
							$this->mod_dashboard->binance_buy_auto_market_order($id, $quantity, $market_price, $coin_symbol, $admin_id);
						}
						////////////////update trail stop
						///////////////
						$update_trail_stop = $low_value - ($low_value / 100) * $stop_loss_percent;
						///////////////////End of update trail stop
						///////////////////
						$upd_data22 = array(
							'status' => 'FILLED',
							'market_value' => $market_price,
							'iniatial_trail_stop' => $update_trail_stop,
						);
						$this->mongo_db->where(array('_id' => $id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						$log_msg = " Order was Buyed at Price " . number_format($market_price, 8);
						$this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = "Buy Market Order is <b>buyed</b> as status Filled market_price=" . number_format($market_price, 8) . "  buy_price  " . number_format($buy_price, 8) . '  high_value' . number_format($high_value, 8);
						$this->add_notification($id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////

						//Check Market History
						$commission = $quantity * (0.001);
						$commissionAsset = str_replace('BTC', '', $symbol);
						//Check Market History

						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($id, $log_msg, 'buy_commision', $admin_id, $date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////

						$arr_response['message'] = $message . '---->log_msg' . $log_msg;
					}

				} else {
					$arr_response['message'] = 'NO Order Buyed';
				}
			}
		}

		return $arr_response;
	} //End of filled_order_by_trigger_1

	public function set_for_sell_order_by_trigger_1($date) {

		$return_response = '';
		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where('buy_order_status_new_filled', 'wait_for_buyed');
		$this->mongo_db->where('trigger_type', 'trigger_1');
		$this->mongo_db->where('sell_price', '');

		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);

		if (count($buy_orders_arr) > 0) {

			foreach ($buy_orders_arr as $buy_orders_data) {

				$coin_symbol = $buy_orders_data['symbol'];
				$buyed_price = $buy_orders_data['market_value'];
				$order_id = $buy_orders_data['_id'];
				$admin_id = $buy_orders_data['admin_id'];
				$quantity = $buy_orders_data['quantity'];
				$buy_part = $buy_orders_data['buy_part'];
				$application_mode = $buy_orders['application_mode'];
				$parts_arr = $this->get_trigger_1_sell_parts_percentage($coin_symbol);

				$index = 1;
				$sell_price = '';
				foreach ($parts_arr as $sell_percentage) {

					if ('part_1' == $buy_part) {
						$sell_price = $buyed_price + ($differenc_value * ($sell_percentage['sell_part_1_price_percent'] / 100));
					} else if ('part_2' == $buy_part) {
						$sell_price = $buyed_price + ($differenc_value * ($sell_percentage['sell_part_2_price_percent'] / 100));
					} else if ('part_3' == $buy_part) {
						$sell_price = $buyed_price + ($differenc_value * ($sell_percentage['sell_part_3_price_percent'] / 100));
					}

					$upd_data22 = array(
						'sell_price' => $sell_price,
					);
					$this->mongo_db->where(array('_id' => $order_id));
					$this->mongo_db->set($upd_data22);
					//Update data in mongoTable
					$this->mongo_db->update('buy_orders');

					/////////////////////////////////////////////////////
					/////////////////////////////////////////////////////

					$ins_data = array(

						'symbol' => $coin_symbol,
						'purchased_price' => $buyed_price,
						'quantity' => $quantity,
						'profit_type' => 'percentage',
						'order_type' => 'MARKET_ORDER',
						'admin_id' => $admin_id,
						'buy_order_check' => 'yes',
						'buy_order_id' => $order_id,
						'buy_order_binance_id' => '',
						'stop_loss' => 'no',
						'loss_percentage' => '',
						'created_date' => $this->mongo_db->converToMongodttime($date),
						'market_value' => $sell_price,
					);

					$ins_data['sell_profit_percent'] = 2;
					$ins_data['sell_price'] = $sell_price;

					$ins_data['trail_check'] = 'no';
					$ins_data['trail_interval'] = '0';
					$ins_data['sell_trail_price'] = '0';
					$ins_data['status'] = 'new';
					$ins_data['application_mode'] = ['application_mode'];

					//Insert data in mongoTable
					$sell_order_id = $this->mongo_db->insert('orders', $ins_data);

					$upd_data = array(
						'sell_order_id' => $sell_order_id,
					);

					$this->mongo_db->where(array('_id' => $order_id));
					$this->mongo_db->set($upd_data);

					//Update data in mongoTable
					$this->mongo_db->update('buy_orders');

					//////////////////////////////////////////////////////////////////////////////
					////////////////////////////// Order History Log /////////////////////////////

					$message = 'Sell Order was created as new';
					$log_msg = $message . " " . number_format($sell_price, 8);
					$this->insert_order_history_log($order_id, $log_msg, 'sell_created ', $admin_id, $date);
					////////////////////////////// End Order History Log /////////////////////////
					//////////////////////////////////////////////////////////////////////////////

					////////////////////// Set Notification //////////////////
					$message = " <b>Sell created as new</b>";
					$this->add_notification($order_id, 'buy', $message, $admin_id);
					//////////////////////////////////////////////////////////

					////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////
					///////////////////////////////////////////////////////

					$index++;
				}

			}

		} //End Of Buy Order array

		$this->sold_order_by_trigger_1($date);
	} //End of set_for_sell_order_by_trigger_1

	public function sold_order_by_trigger_1($date) {
		$return_response = '';
		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {

			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where('buy_order_status_new_filled', 'wait_for_buyed');
		$this->mongo_db->where('trigger_type', 'trigger_1');
		$this->mongo_db->where('status', 'FILLED');
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);
		if (count($buy_orders_arr) > 0) {
			foreach ($buy_orders_arr as $buy_orders) {
				$buy_orders_id = $buy_orders['_id'];
				$coin_symbol = $buy_orders['symbol'];
				$sell_price = $buy_orders['sell_price'];
				$admin_id = $buy_orders['admin_id'];
				$purchased_price = $buy_orders['price'];
				$buy_purchased_price = $buy_orders['market_value'];
				$iniatial_trail_stop = $buy_orders['iniatial_trail_stop'];
				$quantity = $buy_orders['quantity'];
				$application_mode = $buy_orders['application_mode'];
				$sell_order_id = $buy_orders['sell_order_id'];
				$where = array('openTime_human_readible' => $current_date, 'coin' => $coin_symbol);
				$result_arr = array();
				$this->mongo_db->where($where);
				$current_candel_result = $this->mongo_db->get('market_chart');
				$current_candel_arr = iterator_to_array($current_candel_result);
				if (count($current_candel_arr) > 0) {

					$high_value = $current_candel_arr[0]['high'];
					$low_value = $current_candel_arr[0]['low'];
					$open = $current_candel_arr[0]['open'];

					if ($application_mode == 'live') {
						$current_market_value = $this->mod_dashboard->get_market_value($coin_symbol);
						$market_price = $current_market_value;
						$low_value = $current_market_value;
					} else {

						$market_price = num($low_value);
					}

					//Sell with Stop Loss
					if ($low_value < $iniatial_trail_stop && $iniatial_trail_stop != '') {

						if ($application_mode == 'live') {

							$this->mod_dashboard->binance_sell_auto_market_order($sell_order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id);
						}

						$upd_data22 = array(
							'is_sell_order' => 'sold',
							'market_sold_price' => $low_value,
						);
						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');

						$up_arr = array('status' => 'FILLED', 'market_value' => $low_value, 'sell_price' => $low_value);

						$this->mongo_db->where(array('buy_order_id' => $buy_orders_id));
						$this->mongo_db->set($up_arr);
						//Update data in mongoTable
						$this->mongo_db->update('orders');

						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////
						$message = 'Order was Sold With Loss At: ';
						$log_msg = $message . " " . number_format($low_value, 8);
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////

						////////////////////// Set Notification //////////////////
						$message = $message . " <b>Sold</b>";
						$this->add_notification($buy_orders_id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////

						//Check Market History
						$commission_value = $quantity * (0.001);
						$commission = $commission_value * $low_value;
						$commissionAsset = 'BTC';
						//Check Market History

						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $created_date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
					} //Sold with Loss
					else if ($low_value <= $sell_price && $high_value >= $sell_price) {

						if ($application_mode == 'live') {

							$this->mod_dashboard->binance_sell_auto_market_order($sell_order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id);
						}

						$upd_data22 = array(
							'is_sell_order' => 'sold',
							'market_sold_price' => $low_value,
						);
						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');

						$up_arr = array('status' => 'FILLED', 'market_value' => $low_value, 'sell_price' => $low_value);

						$this->mongo_db->where(array('buy_order_id' => $buy_orders_id));
						$this->mongo_db->set($up_arr);
						//Update data in mongoTable
						$this->mongo_db->update('orders');

						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////
						$message = 'Order was Sold With Profit At: ';
						$log_msg = $message . " " . number_format($low_value, 8);
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////

						////////////////////// Set Notification //////////////////
						$message = $log_msg . " <b>Sold</b>";
						$this->add_notification($buy_orders_id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////

						//Check Market History
						$commission_value = $quantity * (0.001);
						$commission = $commission_value * $low_value;
						$commissionAsset = 'BTC';
						//Check Market History

						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $date);
						////////////////////////////// End Order History Log /////////////////////////////////////////

					}

				}
			}
		}
	} //End of sold_order_by_trigger_1

	public function get_trigger_1_sell_parts_percentage($coin_symbol) {

		$this->mongo_db->where(array('coins' => $coin_symbol, 'triggers_type' => 'trigger_1'));
		$res_coin_setting = $this->mongo_db->get('setting_triggers_collections');
		$res_coin_setting_arr = iterator_to_array($res_coin_setting);

		$sell_part_1_price_percent = 25;
		$sell_part_2_price_percent = 60;
		$sell_part_3_price_percent = 98;

		if (count($res_coin_setting_arr) > 0) {
			foreach ($res_coin_setting_arr as $res_coin_setting) {
				$sell_part_1_price_percent = $res_coin_setting['sell_part_1_price_percent'];
				$sell_part_2_price_percent = $res_coin_setting['sell_part_2_price_percent'];
				$sell_part_3_price_percent = $res_coin_setting['sell_part_3_price_percent'];

			}
		}

		$parts_arr[] = array('sell_part_1_price_percent' => $sell_part_1_price_percent, 'sell_part_2_price_percent' => $sell_part_2_price_percent, 'sell_part_3_price_percent' => $sell_part_3_price_percent);

		return $parts_arr;
	} //End of get_trigger_1_sell_parts

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////                         //////////////////////
	////////////////////                        ////////////////////////////////
	//////////////////// Trigger_2_simulator  ////////////////////////////////
	////////////////////                      ////////////////////////////////
	////////////////////                         //////////////////////
	////////////////////                        //////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function buy_trigger_2_samulater($date = '', $coin_symbol) {

		$return_response = '';
		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		//////////////////////////////////////////////////////////
		////////////////////// Get current market value///////////
		$where_condition = array('openTime_human_readible' => $current_date, 'coin' => $coin_symbol, 'application_mode' => 'test');
		$this->mongo_db->where($where_condition);
		$market_price = 0;

		$current_candel_result = $this->mongo_db->get('market_chart');
		$current_candel_arr = iterator_to_array($current_candel_result);

		$current_low_value = 0;
		if (count($current_candel_arr) > 0) {
			$current_low_value = $current_candel_arr[0]['low'];
			$application_mode = $current_candel_arr[0]['application_mode'];
		}

		$created_date = date('Y-m-d G:i:s');
		$this->mongo_db->where('trigger_type', 'trigger_2');
		$upd_array = array('created_date' => $created_date, 'current_market_value' => $current_low_value, 'coin' => $coin_symbol);
		$this->mongo_db->set($upd_array);
		$this->mongo_db->update('buy_trigger_process_log');
		////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////
		$where = array('openTime_human_readible' => $prevouse_date, 'coin' => $coin_symbol, 'candle_type' => 'demand');
		$result_arr = array();
		$this->mongo_db->where($where);
		$previouse_candel_result = $this->mongo_db->get('market_chart');
		$previouse_candel_arr = iterator_to_array($previouse_candel_result);

		$this->mongo_db->where(array('coins' => $coin_symbol, 'triggers_type' => 'trigger_2'));
		$res_coin_setting = $this->mongo_db->get('setting_triggers_collections');
		$res_coin_setting_arr = iterator_to_array($res_coin_setting);

		$buy_price_percentage = 30;
		$stop_loss_percent = 4;
		$sell_price_percent = 1;
		if (count($res_coin_setting_arr) > 0) {
			foreach ($res_coin_setting_arr as $res_coin_setting) {
				$buy_price_percentage = $res_coin_setting['buy_price'];
				$stop_loss_percent = $res_coin_setting['stop_loss'];
				$sell_price_percent = $res_coin_setting['sell_price'];

			}
		}

		if (count($previouse_candel_arr) > 0) {
			$high_value = $previouse_candel_arr[0]['high'];
			$low_value = $previouse_candel_arr[0]['low'];
			$trigger_status = $previouse_candel_arr[0]['trigger_status'];
			$triggert_type = $previouse_candel_arr[0]['triggert_type'];
			$candel_id = $previouse_candel_arr[0]['_id'];
			$differenc_value = $high_value - $low_value;
			$buy_price = $low_value + ($differenc_value * ($buy_price_percentage / 100));
			$result_arr['buy_price'] = number_format($buy_price, 8);
			$update_trail_stop = $buy_price - ($buy_price / 100) * $stop_loss_percent;
			$result_arr['iniatial_trail_stop'] = number_format($update_trail_stop, 8);
			$sell_price = $buy_price + ($buy_price / 100) * $sell_price_percent;
			$result_arr['sell_price'] = $sell_price;
			$result_arr['reponse_result'] = true;
			$result_arr['message'] = 'Order created Successfully';
			$result_arr['stop_loss_percent'] = $stop_loss_percent;
			$result_arr['trigger_status'] = $trigger_status;
			$result_arr['triggert_type'] = $triggert_type;
			$result_arr['candel_id'] = $candel_id;

		}
		$response_arr['buy_trigger_data'] = $result_arr;
		$response_arr['stop_loss_percent'] = $stop_loss_percent;
		return $response_arr;
	} //End of  buy_trigger_2_samulater

	public function create_buy_orders_by_trigger_2_samulater($date = '') {
		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		////////////////////////////////////////////////////////////////
		///////////// Cancel Old Orders ///////////////////////
		$this->mongo_db->where(array('status' => 'new', 'trigger_type' => 'trigger_2', 'order_mode' => 'test_simulator'));
		$this->mongo_db->where('created_date', array('$lte' => $this->mongo_db->converToMongodttime(date('Y-m-d H:00:00', strtotime('-5 minutes', strtotime($date))))));
		$this->mongo_db->set(array('status' => 'canceled'));
		$this->mongo_db->update('buy_orders');
		$message = 'buy Order was Canceled: ';
		$log_msg = $message . " " . number_format($low_value, 8);
		$this->insert_order_history_log($buy_orders_id, $log_msg, 'buy_canceled', $admin_id, $date);
		////////////////////////////////////////////////////////////////

		$this->mongo_db->where('status', 'new');
		$this->mongo_db->where('trigger_type', 'trigger_2');
		$this->mongo_db->where_ne('buy_order_status_new_filled', 'wait_for_buyed');
		$this->mongo_db->where('order_mode', 'test_simulator');

		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);

		$response_arr = array();
		$response_arr['message'] = 'parent order not found';
		//if parent order exist then creat buy orders
		if (count($buy_orders_arr) > 0) {
			foreach ($buy_orders_arr as $buy_orders) {
				$buy_parent_id = $buy_orders['_id'];
				$coin_symbol = $buy_orders['symbol'];
				$buy_quantity = $buy_orders['quantity'];
				$buy_trigger_type = $buy_orders['trigger_type'];
				$admin_id = $buy_orders['admin_id'];
				$application_mode = $buy_orders['application_mode'];

				$order_mode = $buy_orders['order_mode'];

				$response_data = $this->buy_trigger_2_samulater($current_date, $coin_symbol);

				$stop_loss_percent = $response_data['stop_loss_percent'];
				$response_arr = $response_data['buy_trigger_data'];

				$trigger_status = $response_data['trigger_status'];
				$candel_id = $response_arr['candel_id'];

				//if Demand candel Found Then Create new Order
				if (count($response_arr) > 0) {
					$stop_loss_percent = $response_arr['stop_loss_percent'];

					$ins_data = array(
						'price' => number_format($response_arr['buy_price'], 8),
						'quantity' => $buy_quantity,
						'symbol' => $coin_symbol,
						'order_type' => 'MARKET_ORDER',
						'admin_id' => $admin_id,
						'trigger_type' => 'trigger_2',
						'sell_price' => number_format(num($response_arr['sell_price']), 8),
						'created_date' => $this->mongo_db->converToMongodttime($current_date),
					);
					$ins_data['trail_check'] = 'no';
					$ins_data['trail_interval'] = '0';
					$ins_data['buy_trail_price'] = '0';
					$ins_data['status'] = 'new';
					$ins_data['auto_sell'] = 'no';
					$ins_data['buy_parent_id'] = $buy_parent_id;
					$ins_data['iniatial_trail_stop'] = 0;
					$ins_data['buy_order_status_new_filled'] = 'wait_for_buyed';
					$ins_data['application_mode'] = $application_mode;
					$ins_data['order_mode'] = $order_mode;

					//Insert data in mongoTable
					$buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);
					//////////////////////////////////////////////////////////////////////////////
					////////////////////////////// Order History Log /////////////////////////////
					$log_msg = "Buy (Test) Order was Created at Price " . number_format($response_arr['buy_price'], 8);
					$this->insert_order_history_log($buy_order_id, $log_msg, 'buy_created', $admin_id, $current_date);
					////////////////////////////// End Order History Log /////////////////////////
					//////////////////////////////////////////////////////////////////////////////
					////////////////////// Set Notification //////////////////
					$message = "Buy Market Order is <b>Created</b> as status new ";
					$this->add_notification($buy_order_id, 'buy', $message, $admin_id);
					/////////////////////////////////////////////////////////
				}
				//Order Filled
				$response_arr['message'] = $this->buy_order_by_trigger_match_samulater($current_date, $coin_symbol, $stop_loss_percent);
			}

			//update  candel status
		} else {
			$response_arr['message'] = 'No parent Order Found';
		}

		return $response_arr['message'];
	} //End of create_buy_orders_by_trigger_2_samulater

	function buy_order_by_trigger_match_samulater($date, $coin_symbol, $stop_loss_percent) {

		$this->mongo_db->where('status', 'new');
		$this->mongo_db->where('trigger_type', 'trigger_2');
		$this->mongo_db->where('buy_order_status_new_filled', 'wait_for_buyed');
		$this->mongo_db->where('order_mode', 'test_simulator');

		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);
		$return_response = '';

		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		$where = array('openTime_human_readible' => $current_date, 'coin' => $coin_symbol);
		$result_arr = array();
		$this->mongo_db->where($where);
		$current_candel_result = $this->mongo_db->get('market_chart');
		$current_candel_arr = iterator_to_array($current_candel_result);

		$arr_response = array();
		if (count($buy_orders_arr)) {
			foreach ($buy_orders_arr as $buy_orders) {
				$id = $buy_orders['_id'];
				$buy_price = $buy_orders['price'];
				$admin_id = $buy_orders['admin_id'];
				$quantity = $buy_orders['quantity'];
				$application_mode = $buy_orders['application_mode'];
				$coin_symbol = $buy_orders['symbol'];

				//////////////////////////////////
				/////////////////////////////////

				if (count($current_candel_arr) > 0) {

					$high_value = $current_candel_arr[0]['high'];
					$low_value = $current_candel_arr[0]['low'];
					$open = $current_candel_arr[0]['open'];
					$market_price = $low_value;

					if ($market_price <= $buy_price && $high_value >= $buy_price) {

						////////////////update trail stop
						$update_trail_stop = $market_price - ($market_price / 100) * $stop_loss_percent;
						///////////////////End of update trail stop
						///////////////////////////////////////////

						$created_date = date("Y-m-d G:i:s");
						$upd_data22 = array(
							'status' => 'FILLED',
							'market_value' => $market_price,
							'iniatial_trail_stop' => $update_trail_stop,
							'buy_date' => $this->mongo_db->converToMongodttime($created_date),
						);
						$this->mongo_db->where(array('_id' => $id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						$log_msg = " Order was Buyed at Price " . number_format($market_price, 8);
						$this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = "Buy Market Order is <b>buyed</b> as status Filled market_price=" . number_format($market_price, 8) . "  buy_price  " . number_format($buy_price, 8) . '  high_value' . number_format($high_value, 8);
						$this->add_notification($id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////
						//Check Market History
						$commission = $quantity * (0.001);
						$commissionAsset = str_replace('BTC', '', $symbol);
						//Check Market History
						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($id, $log_msg, 'buy_commision', $admin_id, $date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
						$arr_response['message'] = $message . '---->log_msg' . $log_msg;
					}
				} else {
					$arr_response['message'] = 'NO Order Buyed';
				}

				////////////////////////////////
				///////////////////////////////

			}
		}

		return $arr_response;
	} //End of buy_order_by_trigger_match

	public function sell_order_trigger_2_samulater($date = '') {

		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where('status', 'FILLED');
		$this->mongo_db->where('trigger_type', 'trigger_2');
		$this->mongo_db->where('order_mode', 'test_simulator');

		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);
		$return_response = '';
		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		$arr_response = array();
		if (count($buy_orders_arr) > 0) {
			foreach ($buy_orders_arr as $buy_orders) {
				$buy_orders_id = $buy_orders['_id'];
				$coin_symbol = $buy_orders['symbol'];
				$sell_price = $buy_orders['sell_price'];
				$admin_id = $buy_orders['admin_id'];
				$purchased_price = $buy_orders['price'];
				$buy_purchased_price = $buy_orders['market_value'];
				$iniatial_trail_stop = $buy_orders['iniatial_trail_stop'];
				$application_mode = $buy_orders['application_mode'];
				$quantity = $buy_orders['quantity'];
				$order_type = $buy_orders['order_type'];

				$order_mode = $buy_orders['order_mode'];

				$where = array('openTime_human_readible' => $current_date, 'coin' => $coin_symbol);
				$result_arr = array();
				$this->mongo_db->where($where);
				$current_candel_result = $this->mongo_db->get('market_chart');
				$current_candel_arr = iterator_to_array($current_candel_result);

				//////////////// Test Mode//////////////////////////
				///////////////////////////////////////////////////

				if (count($current_candel_arr) > 0) {
					$high_value = $current_candel_arr[0]['high'];
					$low_value = $current_candel_arr[0]['low'];
					$open = $current_candel_arr[0]['open'];
					$market_price = $low_value;
					$created_date = date("Y-m-d G:i:s");
					//Sell with Stop Loss
					if ($market_price < $iniatial_trail_stop && $iniatial_trail_stop != '') {
						$sell_price = $iniatial_trail_stop;
						$upd_data22 = array(
							'is_sell_order' => 'sold',
							'market_sold_price' => $sell_price,
						);
						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						//////////////////////////////
						//////////////////////////////

						$ins_data = array(
							'symbol' => $coin_symbol,
							'purchased_price' => num($buy_purchased_price),
							'quantity' => $quantity,
							'profit_type' => 'percentage',
							'order_type' => 'MARKET_ORDER',
							'admin_id' => $admin_id,
							'buy_order_check' => 'yes',
							'buy_order_id' => $buy_orders_id,
							'buy_order_binance_id' => '',
							'stop_loss' => 'no',
							'loss_percentage' => '',
							'created_date' => $this->mongo_db->converToMongodttime($current_date),
							'market_value' => $market_price,
							'application_mode' => $application_mode,
							'order_mode' => $order_mode,
						);

						$ins_data['sell_profit_percent'] = 2;
						$ins_data['sell_price'] = $sell_price;

						$ins_data['trail_check'] = 'no';
						$ins_data['trail_interval'] = '0';
						$ins_data['sell_trail_price'] = '0';
						$ins_data['status'] = 'FILLED';
						$ins_data['sell_date'] = $this->mongo_db->converToMongodttime($created_date);

						//Insert data in mongoTable
						$order_id = $this->mongo_db->insert('orders', $ins_data);

						$upd_data = array(
							'sell_order_id' => $order_id,
						);
						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////
						$message = 'Sell Order was Sold With Loss';
						$log_msg = $message . " " . number_format($sell_price, 8);
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $current_date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = $message . " <b>Sold</b>";
						$this->add_notification($buy_orders_id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////
						//Check Market History
						$commission_value = $quantity * (0.001);
						$commission = $commission_value * $market_value;
						$commissionAsset = 'BTC';
						//Check Market History
						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $current_date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
						$response['message'] = '$log_msg  ' . $log_msg . '  $message ' . $message;

					} else if ($market_price <= $sell_price && $high_value >= $sell_price) {
						//Sell With Normal Value
						$upd_data22 = array(
							'is_sell_order' => 'sold',
							'market_sold_price' => $sell_price,
						);
						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						/////////////////////////////////////
						///////////////////////////////////
						$ins_data = array(
							'symbol' => $coin_symbol,
							'purchased_price' => num($buy_purchased_price),
							'quantity' => $quantity,
							'profit_type' => 'percentage',
							'order_type' => 'MARKET_ORDER',
							'admin_id' => $admin_id,
							'buy_order_check' => '',
							'buy_order_id' => $buy_orders_id,
							'buy_order_binance_id' => '',
							'stop_loss' => 'yes',
							'loss_percentage' => '',
							'created_date' => $this->mongo_db->converToMongodttime($current_date),
							'market_value' => $market_price,
							'application_mode' => $application_mode,
							'order_mode' => $order_mode,
						);
						$ins_data['sell_profit_percent'] = 2;
						$ins_data['sell_price'] = $sell_price;
						$ins_data['trail_check'] = 'no';
						$ins_data['trail_interval'] = '0';
						$ins_data['sell_trail_price'] = '0';
						$ins_data['status'] = 'FILLED';
						$ins_data['sell_date'] = $this->mongo_db->converToMongodttime($created_date);
						//Insert data in mongoTable
						$order_id = $this->mongo_db->insert('orders', $ins_data);

						$upd_data = array(
							'sell_order_id' => $order_id,
						);

						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data);

						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						$message = 'Sell Order was Sold With profit';

						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////
						$log_msg = $message . " " . number_format($sell_price, 8);
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $current_date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = $message . " <b>Sold</b>";
						$this->add_notification($buy_orders_id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////
						//Check Market History
						$commission_value = $quantity * (0.001);
						$commission = $commission_value * $market_value;
						$commissionAsset = 'BTC';
						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $current_date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
						$response['message'] = '$log_msg  ' . $log_msg . '  $message ' . $message;
					} else {
						$arr_response['message'] = 'NO trigger match for sell $low_value  ' . number_format($low_value, 8) . '  sell_price ' . number_format($sell_price, 8) . '  high_value' . number_format($high_value, 8);
					}
				} else {
					$arr_response['message'] = 'Order is Not Sold';
				}
				///////////////////////////////////////////////
				///////////////////////////////////////////////

			} //End of for each order
		} //End of End Condition

		return $arr_response['message'];
	} //End of sell_order_trigger_2_samulater

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////                         //////////////////////
	////////////////////                        ////////////////////////////////
	////////////////////      Trigger_2_live   ////////////////////////////////
	////////////////////                      ////////////////////////////////
	////////////////////                         //////////////////////
	////////////////////                        //////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function buy_trigger_2_live($date = '', $coin_symbol) {

		$return_response = '';
		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		//////////////////////////////////////////////////////////
		////////////////////// Get current market value///////////
		$market_price = $this->mod_dashboard->get_market_value($coin_symbol);

		$created_date = date('Y-m-d G:i:s');
		$this->mongo_db->where('trigger_type', 'trigger_2');
		$upd_array = array('created_date' => $created_date, 'current_market_value' => $current_low_value, 'coin' => $coin_symbol);
		$this->mongo_db->set($upd_array);
		$this->mongo_db->update('buy_trigger_process_log');
		////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////
		$this->mongo_db->where_ne('trigger_status_trigger_2', 1);

		$where = array('openTime_human_readible' => $prevouse_date, 'coin' => $coin_symbol, 'candle_type' => 'demand');

		$result_arr = array();
		$this->mongo_db->where($where);
		$previouse_candel_result = $this->mongo_db->get('market_chart');
		$previouse_candel_arr = iterator_to_array($previouse_candel_result);

		$this->mongo_db->where(array('coins' => $coin_symbol, 'triggers_type' => 'trigger_2'));
		$res_coin_setting = $this->mongo_db->get('setting_triggers_collections');
		$res_coin_setting_arr = iterator_to_array($res_coin_setting);

		$buy_price_percentage = 30;
		$stop_loss_percent = 4;
		$sell_price_percent = 3;
		if (count($res_coin_setting_arr) > 0) {
			foreach ($res_coin_setting_arr as $res_coin_setting) {
				$buy_price_percentage = $res_coin_setting['buy_price'];
				$stop_loss_percent = $res_coin_setting['stop_loss'];
				$sell_price_percent = $res_coin_setting['sell_price'];

			}
		}

		if (count($previouse_candel_arr) > 0) {
			$high_value = $previouse_candel_arr[0]['high'];
			$low_value = $previouse_candel_arr[0]['low'];
			$trigger_status = $previouse_candel_arr[0]['trigger_status'];
			$triggert_type = $previouse_candel_arr[0]['triggert_type'];
			$candel_id = $previouse_candel_arr[0]['_id'];
			$differenc_value = $high_value - $low_value;
			$buy_price = $low_value + ($differenc_value * ($buy_price_percentage / 100));
			$result_arr['buy_price'] = number_format($buy_price, 8);
			$update_trail_stop = $buy_price - ($buy_price / 100) * $stop_loss_percent;
			$result_arr['iniatial_trail_stop'] = number_format($update_trail_stop, 8);
			$sell_price = $buy_price + ($buy_price / 100) * $sell_price_percent;
			$result_arr['sell_price'] = $sell_price;
			$result_arr['reponse_result'] = true;
			$result_arr['message'] = 'Order created Successfully';
			$result_arr['stop_loss_percent'] = $stop_loss_percent;
			$result_arr['trigger_status'] = $trigger_status;
			$result_arr['triggert_type'] = $triggert_type;
			$result_arr['candel_id'] = $candel_id;

		}
		$response_arr['buy_trigger_data'] = $result_arr;
		$response_arr['stop_loss_percent'] = $stop_loss_percent;
		return $response_arr;
	} //End of  buy_trigger_2_live

	public function create_buy_orders_by_trigger_2_Live($date = '') {

		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		$this->mongo_db->where_in('order_mode', array('test_live', 'live'));

		$this->mongo_db->where_ne('inactive_status', 'inactive');
		$this->mongo_db->where_ne('buy_order_status_new_filled', 'wait_for_buyed');
		$this->mongo_db->where(array('status' => 'new', 'trigger_type' => 'trigger_2'));

		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);

		$response_arr = array();
		$response_arr['message'] = 'parent order not found';
		$candel_global_id = '';
		//if parent order exist then creat buy orders
		if (count($buy_orders_arr) > 0) {
			foreach ($buy_orders_arr as $buy_orders) {
				$buy_parent_id = $buy_orders['_id'];
				$coin_symbol = $buy_orders['symbol'];
				$buy_quantity = $buy_orders['quantity'];
				$buy_trigger_type = $buy_orders['trigger_type'];
				$admin_id = $buy_orders['admin_id'];

				$application_mode = $buy_orders['application_mode'];
				$order_mode = $buy_orders['order_mode'];

				$response_data = $this->buy_trigger_2_live($current_date, $coin_symbol);
				$stop_loss_percent = $response_data['stop_loss_percent'];
				$response_arr = $response_data['buy_trigger_data'];
				$trigger_status = $response_data['trigger_status'];
				$candel_id = $response_arr['candel_id'];

				if ($candel_id != '') {
					$candel_global_id = $candel_id;
				}

				//if Demand candel Found Then Create new Order
				if (count($response_arr) > 0) {
					$stop_loss_percent = $response_arr['stop_loss_percent'];

					$ins_data = array(
						'price' => number_format($response_arr['buy_price'], 8),
						'quantity' => $buy_quantity,
						'symbol' => $coin_symbol,
						'order_type' => 'MARKET_ORDER',
						'admin_id' => $admin_id,
						'trigger_type' => 'trigger_2',
						'sell_price' => number_format(num($response_arr['sell_price']), 8),
						'created_date' => $this->mongo_db->converToMongodttime($current_date),
					);
					$ins_data['trail_check'] = 'no';
					$ins_data['trail_interval'] = '0';
					$ins_data['buy_trail_price'] = '0';
					$ins_data['status'] = 'new';
					$ins_data['auto_sell'] = 'no';
					$ins_data['buy_parent_id'] = $buy_parent_id;
					$ins_data['iniatial_trail_stop'] = 0;
					$ins_data['buy_order_status_new_filled'] = 'wait_for_buyed';
					$ins_data['application_mode'] = $application_mode;
					$ins_data['order_mode'] = $order_mode;

					//Insert data in mongoTable
					$buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);
					//////////////////////////////////////////////////////////////////////////////
					////////////////////////////// Order History Log /////////////////////////////

					if ($application_mode == 'live') {
						$order_mode = 'Live';
					} else {
						$order_mode = 'Test';
					}
					$log_msg = "Buy (" . $order_mode . ") Order was Created at Price " . number_format($response_arr['buy_price'], 8);
					$created_date = date('Y-m-d G:i:s');
					$this->insert_order_history_log($buy_order_id, $log_msg, 'buy_created', $admin_id, $created_date);
					////////////////////////////// End Order History Log /////////////////////////
					//////////////////////////////////////////////////////////////////////////////
					////////////////////// Set Notification //////////////////
					$message = "Buy Market Order is <b>Created</b> as status new";
					$this->add_notification($buy_order_id, 'buy', $message, $admin_id);
					/////////////////////////////////////////////////////////
				}
				//Order Filled

				$response_arr['message'] = $this->buy_order_by_trigger_match_live($current_date, $coin_symbol, $stop_loss_percent);

			}

			//update  candel status

			$upd_status = array(
				'trigger_status_trigger_2' => 1,
			);

			$conn = $this->mongo_db->customQuery();
			$res = $conn->market_chart->updateMany(array('openTime_human_readible' => $prevouse_date), array('$set' => $upd_status));

		} else {
			$response_arr['message'] = 'No parent Order Found';
		}

		return $response_arr['message'];
	} //End of get_parent_buy_orders

	public function buy_order_by_trigger_match_live($date, $coin_symbol, $stop_loss_percent) {

		$this->mongo_db->where_in('order_mode', array('test_live', 'live'));
		$this->mongo_db->where(array('status' => 'new', 'trigger_type' => 'trigger_2', 'buy_order_status_new_filled' => 'wait_for_buyed'));

		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);
		$return_response = '';

		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		// $where = array('openTime_human_readible'=>$current_date,'coin'=>$coin_symbol);
		// $result_arr =array();
		// $this->mongo_db->where($where);
		// $current_candel_result = $this->mongo_db->get('market_chart');
		// $current_candel_arr = iterator_to_array($current_candel_result);

		$arr_response = array();
		if (count($buy_orders_arr)) {
			foreach ($buy_orders_arr as $buy_orders) {
				$id = $buy_orders['_id'];
				$buy_price = $buy_orders['price'];
				$admin_id = $buy_orders['admin_id'];
				$quantity = $buy_orders['quantity'];
				$application_mode = $buy_orders['application_mode'];
				$coin_symbol = $buy_orders['symbol'];
				//////////////////////////////////
				/////////////////////////////////
				$market_price = $this->mod_dashboard->get_market_value($coin_symbol);

				if ($market_price <= $buy_price) {

					if ($application_mode == 'live') {
						$this->mod_dashboard->binance_buy_auto_market_order_live($id, $quantity, $market_price, $coin_symbol, $admin_id);

						////////////////update trail stop
						$update_trail_stop = $market_price - ($market_price / 100) * $stop_loss_percent;
						///////////////////End of update trail stop
						///////////////////////////////////////////
						$upd_data22 = array(
							'iniatial_trail_stop' => $update_trail_stop,
						);
						$this->mongo_db->where(array('_id' => $id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
					} else {

						////////////////update trail stop
						$update_trail_stop = $market_price - ($market_price / 100) * $stop_loss_percent;
						///////////////////End of update trail stop
						///////////////////////////////////////////
						$upd_data22 = array(
							'status' => 'FILLED',
							'market_value' => $market_price,
							'iniatial_trail_stop' => $update_trail_stop,
						);
						$this->mongo_db->where(array('_id' => $id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						$log_msg = " Order was Buyed at Price " . number_format($market_price, 8);
						$created_date = date('Y-m-d G:i:s');
						$this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $created_date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = "Buy Market Order is <b>buyed</b> as status Filled market_price=" . number_format($market_price, 8) . "  buy_price  " . number_format($buy_price, 8) . '  high_value' . number_format($high_value, 8);
						$this->add_notification($id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////
						//Check Market History
						$commission = $quantity * (0.001);
						$commissionAsset = str_replace('BTC', '', $symbol);
						//Check Market History
						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($id, $log_msg, 'buy_commision', $admin_id, $created_date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
						$arr_response['message'] = $message . '---->log_msg' . $log_msg;

					}

				}
				////////////////////////////////
				///////////////////////////////
			}
		}

		return $arr_response;
	} //End of buy_order_by_trigger_match

	public function sell_order_trigger_2_live($date = '') {

		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where_in('order_mode', array('test_live', 'live'));
		$this->mongo_db->where(array('status' => 'FILLED', 'trigger_type' => 'trigger_2'));

		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);
		$return_response = '';
		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));

		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		$arr_response = array();
		if (count($buy_orders_arr) > 0) {
			foreach ($buy_orders_arr as $buy_orders) {
				$buy_orders_id = $buy_orders['_id'];
				$coin_symbol = $buy_orders['symbol'];
				$sell_price = $buy_orders['sell_price'];
				$admin_id = $buy_orders['admin_id'];
				$purchased_price = $buy_orders['price'];
				$buy_purchased_price = $buy_orders['market_value'];
				$iniatial_trail_stop = $buy_orders['iniatial_trail_stop'];
				$application_mode = $buy_orders['application_mode'];
				$quantity = $buy_orders['quantity'];
				$order_type = $buy_orders['order_type'];

				$order_mode = $buy_orders['order_mode'];
				$binance_order_id = $buy_orders['binance_order_id'];

				$trigger_type = $buy_orders['trigger_type'];

				$where = array('openTime_human_readible' => $current_date, 'coin' => $coin_symbol);
				$result_arr = array();
				$this->mongo_db->where($where);
				$current_candel_result = $this->mongo_db->get('market_chart');
				$current_candel_arr = iterator_to_array($current_candel_result);

				/////////////// Start of live mode///////////////////////////
				////////////////////////////////////////////////////////////
				$market_price = $this->mod_dashboard->get_market_value($coin_symbol);
				//Sell with Stop Loss
				if ($market_price < $iniatial_trail_stop && $iniatial_trail_stop != '') {

					//////////////////////////////
					//////////////////////////////
					$ins_data = array(
						'symbol' => $coin_symbol,
						'purchased_price' => num($buy_purchased_price),
						'quantity' => $quantity,
						'profit_type' => 'percentage',
						'order_type' => 'MARKET_ORDER',
						'admin_id' => $admin_id,
						'buy_order_check' => 'yes',
						'buy_order_id' => $buy_orders_id,
						'buy_order_binance_id' => $binance_order_id,
						'stop_loss' => 'no',
						'loss_percentage' => '',
						'created_date' => $this->mongo_db->converToMongodttime($current_date),
						'market_value' => $market_price,
						'application_mode' => $application_mode,
						'order_mode' => $order_mode,
						'trigger_type' => $trigger_type,
					);

					$ins_data['sell_profit_percent'] = 2;
					$ins_data['sell_price'] = $sell_price;

					$ins_data['trail_check'] = 'no';
					$ins_data['trail_interval'] = '0';
					$ins_data['sell_trail_price'] = '0';
					$ins_data['status'] = 'new';

					//Insert data in mongoTable
					$order_id = $this->mongo_db->insert('orders', $ins_data);

					if ($application_mode == 'live') {
						$this->mod_dashboard->binance_sell_auto_market_order_live($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id);

						$upd_data22 = array(
							'sell_order_id' => $order_id,
							// 'is_sell_order' => 'sold',
						);

						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');

					} else {

						$upd_data_1 = array(
							'status' => 'FILLED',
						);
						$this->mongo_db->where(array('_id' => $order_id));
						$this->mongo_db->set($upd_data_1);
						//Update data in mongoTable
						$this->mongo_db->update('orders');
						$sell_price = $iniatial_trail_stop;
						$upd_data22 = array(
							'is_sell_order' => 'sold',
							'market_sold_price' => $sell_price,
							'sell_order_id' => $order_id,
						);

						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////
						$message = 'Sell Order was Sold With Loss';
						$log_msg = $message . " " . number_format($sell_price, 8);
						$created_date = date('Y-m-d G:i:s');
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $created_date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = $message . " <b>Sold</b>";
						$this->add_notification($buy_orders_id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////
						//Check Market History
						$commission_value = $quantity * (0.001);
						$commission = $commission_value * $market_value;
						$commissionAsset = 'BTC';
						//Check Market History
						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $created_date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
						$response['message'] = '$log_msg  ' . $log_msg . '  $message ' . $message;
					}

				} else if ($market_price >= $sell_price) {

					/////////////////////////////////////
					///////////////////////////////////
					$ins_data = array(
						'symbol' => $coin_symbol,
						'purchased_price' => num($buy_purchased_price),
						'quantity' => $quantity,
						'profit_type' => 'percentage',
						'order_type' => 'MARKET_ORDER',
						'admin_id' => $admin_id,
						'buy_order_check' => '',
						'buy_order_id' => $buy_orders_id,
						'buy_order_binance_id' => $binance_order_id,
						'stop_loss' => 'yes',
						'loss_percentage' => '',
						'created_date' => $this->mongo_db->converToMongodttime($current_date),
						'market_value' => $market_price,
						'application_mode' => $application_mode,
						'order_mode' => $order_mode,
						'trigger_type' => $trigger_type,
					);
					$ins_data['sell_profit_percent'] = 2;
					$ins_data['sell_price'] = $sell_price;
					$ins_data['trail_check'] = 'no';
					$ins_data['trail_interval'] = '0';
					$ins_data['sell_trail_price'] = '0';
					$ins_data['status'] = 'new';
					//Insert data in mongoTable
					$order_id = $this->mongo_db->insert('orders', $ins_data);

					if ($application_mode == 'live') {

						$this->mod_dashboard->binance_sell_auto_market_order_live($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id);

						$upd_data22 = array(
							'sell_order_id' => $order_id,
							// 'is_sell_order' => 'sold',
						);

						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
					} else {

						//Sell With Normal Value

						$upd_data_1 = array(
							'status' => 'FILLED',
						);

						$this->mongo_db->where(array('_id' => $order_id));
						$this->mongo_db->set($upd_data_1);

						//Update data in mongoTable
						$this->mongo_db->update('orders');

						$upd_data = array(
							'sell_order_id' => $order_id,
							'is_sell_order' => 'sold',
							'market_sold_price' => $sell_price,
						);

						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data);

						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						$message = 'Sell Order was Sold With profit';

						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////
						$log_msg = $message . " " . number_format($sell_price, 8);
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $current_date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = $message . " <b>Sold</b>";
						$this->add_notification($buy_orders_id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////
						//Check Market History
						$commission_value = $quantity * (0.001);
						$commission = $commission_value * $with;
						$commissionAsset = 'BTC';
						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $current_date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
						$response['message'] = '$log_msg  ' . $log_msg . '  $message ' . $message;
					}

				} else {
					$arr_response['message'] = 'NO trigger match for sell $low_value  ' . number_format($low_value, 8) . '  sell_price ' . number_format($sell_price, 8) . '  high_value' . number_format($high_value, 8);
				}

				/////////////////////End of live Mode//////////////////////////
				///////////////////////////////////////////////

			} //End of for each order
		} //End of End Condition

		return $arr_response['message'];
	} //End of sell_order_trigger_2

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////                              /////////////////
	////////////////////                             ////////////////////////////////
	//////////////////// Box_Trigger_3  simulator  /////////////////////////////////
	////////////////////                           ////////////////////////////////
	////////////////////                              /////////////////
	////////////////////                             /////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function find_previous_lowest_status($date, $coin_symbol) {
		$this->mongo_db->where(array('coin' => $coin_symbol));
		$date = $this->mongo_db->converToMongodttime($date);
		$this->mongo_db->where_lte('timestampDate', $date);
		$this->mongo_db->where_in('global_swing_parent_status', array('LL', 'HL'));
		$this->mongo_db->order_by(array('timestampDate' => -1));
		$this->mongo_db->limit(1);
		$previouse_candel_result = $this->mongo_db->get('market_chart');
		$previouse_candel_arr = iterator_to_array($previouse_candel_result);
		$global_swing_status = 0;
		if (count($previouse_candel_arr) > 0) {
			$global_swing_status = $previouse_candel_arr[0]['low'];
		}
		return $global_swing_status;

	} //End of find_previous_lowest_status

	public function create_new_orders_by_Box_Trigger_3_simulator($date = '') {

		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));

		if ($date) {

			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		//Check of Parent Order  Exist
		$this->mongo_db->where_ne('buy_order_status_new_filled', 'wait_for_buyed');
		$this->mongo_db->where(array('status' => 'new', 'trigger_type' => 'box_trigger_3', 'order_mode' => 'test_simulator'));
		$parent_orders_object = $this->mongo_db->get('buy_orders');
		$parent_orders_arr = iterator_to_array($parent_orders_object);

		$response_arr['message'] = 'parent order not found';
		//if parent order exist then creat buy orders
		if (count($parent_orders_arr) > 0) {

			foreach ($parent_orders_arr as $buy_orders) {

				$buy_parent_id = $buy_orders['_id'];
				$coin_symbol = $buy_orders['symbol'];
				$buy_quantity = $buy_orders['quantity'];
				$buy_trigger_type = $buy_orders['trigger_type'];
				$admin_id = $buy_orders['admin_id'];
				$application_mode = $buy_orders['application_mode'];
				$order_mode = $buy_orders['order_mode'];

				//Get TRigger setting
				$this->mongo_db->where(array('coins' => $coin_symbol, 'triggers_type' => 'box_trigger_3'));
				$res_coin_setting = $this->mongo_db->get('setting_triggers_collections');
				$res_coin_setting_arr = iterator_to_array($res_coin_setting);

				$buy_price_percentage = 30;
				$stop_loss_percent = 4;
				$sell_price_percent = 3;
				if (count($res_coin_setting_arr) > 0) {
					foreach ($res_coin_setting_arr as $res_coin_setting) {
						$buy_price_percentage = $res_coin_setting['buy_price'];
						$stop_loss_percent = $res_coin_setting['stop_loss'];
						$sell_price_percent = $res_coin_setting['sell_price'];

					}
				}

				//Check of previous candel is Demond Candel
				$this->mongo_db->where(array('openTime_human_readible' => $prevouse_date, 'coin' => $coin_symbol, 'candle_type' => 'demand'));

				$previouse_candel_result = $this->mongo_db->get('market_chart');
				$previouse_candel_arr = iterator_to_array($previouse_candel_result);

				if (count($previouse_candel_arr) > 0) {

					foreach ($previouse_candel_arr as $candel_data) {
						$demand_candel_high_value = $candel_data['high'];
						$global_swing_status = $candel_data['global_swing_status'];
						$demand_candel_low_value = $candel_data['low'];
						$demand_close_value = $candel_data['close'];
					}
					//Find Lowest Low value
					$this->global_candel_status['global_swing_status'] = $global_swing_status;
					$this->global_candel_status['global_low_value'] = $demand_candel_low_value;

					//Call function to Get Lowest Value

					$lowest_value = $this->find_previous_lowest_status($prevouse_date, $coin_symbol);

					//Call Function to calculate Aggrisive_Stop Loss

					$iniatial_trail_stop = $this->Box_Trigger_3_aggressive_trail_stop($date, $coin_symbol, $demand_close_value, $stop_loss_percent);

					$differenc_value = $demand_candel_high_value - $lowest_value;
					$buy_price = $lowest_value + ($differenc_value * ($buy_price_percentage / 100));

					$sell_price = $buy_price + ($buy_price / 100) * $sell_price_percent;

					$update_prices_arr = array('price' => $buy_price, 'iniatial_trail_stop' => $iniatial_trail_stop, 'sell_price' => $sell_price, 'unique_time_id_to_check_update' => strtotime($date));

					///////////////////////////////////////////////////////////
					////////////////////////                /////////////////////////////
					///////////////////////  Create Order  /////////////////////////////
					//////////////////////                 ////////////////////////////
					///////////////////////////////////////////////////////////

					$ins_data = array(
						'price' => num($buy_price),
						'quantity' => $buy_quantity,
						'symbol' => $coin_symbol,
						'order_type' => 'MARKET_ORDER',
						'admin_id' => $admin_id,
						'trigger_type' => 'box_trigger_3',
						'sell_price' => num($sell_price),
						'created_date' => $this->mongo_db->converToMongodttime($date),
					);
					$ins_data['trail_check'] = 'no';
					$ins_data['trail_interval'] = '0';
					$ins_data['buy_trail_price'] = '0';
					$ins_data['status'] = 'new';
					$ins_data['auto_sell'] = 'no';
					$ins_data['buy_parent_id'] = $buy_parent_id;
					$ins_data['iniatial_trail_stop'] = $iniatial_trail_stop;
					$ins_data['buy_order_status_new_filled'] = 'wait_for_buyed';
					$ins_data['application_mode'] = $application_mode;
					$ins_data['order_mode'] = $order_mode;
					$ins_data['order_mode_lock_for_update'] = 0;
					$ins_data['parent_aggrive_stop_loss_compare_value'] = $demand_close_value;

					//Check if Status Is Not Lock Then Update it
					$this->mongo_db->where_ne('is_sell_order', 'sold');
					$this->mongo_db->where_ne('unique_time_id_to_check_update', strtotime($date));
					$this->mongo_db->where(array('symbol' => $coin_symbol, 'order_mode_lock_for_update' => 0, 'buy_order_status_new_filled' => 'wait_for_buyed', 'status' => 'new'));

					$responese_obj = $this->mongo_db->get('buy_orders');
					$responese_arr = iterator_to_array($responese_obj);

					if (count($responese_arr) > 0) {

						foreach ($responese_arr as $response_data) {
							$order_id = $response_data['_id'];
							$this->mongo_db->where(array('_id' => $order_id));
							$this->mongo_db->set($update_prices_arr);
							//Update data in mongoTable
							$this->mongo_db->update('buy_orders');
							////////////////////////////// Order History Log /////////////////////////////
							$log_msg = "Buy Test Order was Updated To Price " . num($buy_price);
							$this->insert_order_history_log($order_id, $log_msg, 'buy_created', $admin_id, $date);
							////////////////////////////// End Order History Log /////////////////////////
							//////////////////////////////////////////////////////////////////////////////
							////////////////////// Set Notification //////////////////
							$message = "Buy Market Order is <b>Updated</b> as status new";
							$this->add_notification($order_id, 'buy', $message, $admin_id);
						} //End Of Foreach

					} else {
//End Of Reponse Arr

						///////////////////////////////////////////////
						//////////////////////
						/////////
						//Insert data in mongoTable
						$buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);
						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////

						$log_msg = "Buy Test Order was Created at Price " . num($buy_price);
						$this->insert_order_history_log($buy_order_id, $log_msg, 'buy_created', $admin_id, $date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = "Buy Market Order is <b>Created</b> as status new";
						$this->add_notification($buy_order_id, 'buy', $message, $admin_id);
						/////////
						/////////////////////
						//////////////////////////////////////////////
					}

				} else {
					//End Of previouse_candel_arr

					$this->global_candel_status['global_swing_status'] = '';
					$this->global_candel_status['global_low_value'] = '';
				}

				//Check of Heigher Condition Come
				$this->mongo_db->where_in('global_swing_parent_status', array('HH', 'LH'));
				$this->mongo_db->where(array('openTime_human_readible' => $prevouse_date, 'coin' => $coin_symbol));
				$candel_object = $this->mongo_db->get('market_chart');
				$candel_arr = iterator_to_array($candel_object);

				if (count($candel_arr) > 0) {
					$upd_arr = array(
						'order_mode_lock_for_update' => 1,
					);

					$conn = $this->mongo_db->customQuery();
					$res = $conn->buy_orders->updateMany(array('trigger_type' => 'box_trigger_3', 'buy_order_status_new_filled' => 'wait_for_buyed', 'symbol' => $coin_symbol), array('$set' => $upd_arr));

				} //End of if Count Condition

			} //End Of Foreach
			$response_arr['message'] = $this->buy_order_box_trigger_3_samulater($date, $coin_symbol, $stop_loss_percent);
		} //End Of  parent order exist
	} //End of create_buy_orders_by_Box_Trigger_3_simulator

	function buy_order_box_trigger_3_samulater($date, $coin_symbol, $stop_loss_percent) {

		$this->mongo_db->where(array('status' => 'new', 'trigger_type' => 'box_trigger_3', 'buy_order_status_new_filled' => 'wait_for_buyed', 'order_mode' => 'test_simulator'));

		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);

		$return_response = '';

		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		$this->mongo_db->where(array('openTime_human_readible' => $current_date, 'coin' => $coin_symbol));
		$current_candel_result = $this->mongo_db->get('market_chart');
		$current_candel_arr = iterator_to_array($current_candel_result);

		$arr_response = array();
		if (count($buy_orders_arr)) {
			foreach ($buy_orders_arr as $buy_orders) {
				$id = $buy_orders['_id'];
				$buy_price = $buy_orders['price'];
				$admin_id = $buy_orders['admin_id'];
				$quantity = $buy_orders['quantity'];
				$application_mode = $buy_orders['application_mode'];
				$coin_symbol = $buy_orders['symbol'];
				$parent_aggrive_stop_loss_compare_value = $buy_orders['parent_aggrive_stop_loss_compare_value'];

				//////////////////////////////////
				/////////////////////////////////

				if (count($current_candel_arr) > 0) {

					$high_value = $current_candel_arr[0]['high'];
					$low_value = $current_candel_arr[0]['low'];
					$open = $current_candel_arr[0]['open'];
					$market_price = $low_value;
					$created_date = date("Y-m-d G:i:s");

					if ($market_price <= $buy_price && $high_value >= $buy_price) {

						////////////////update trail stop
						$update_trail_stop = $this->Box_Trigger_3_aggressive_trail_stop($date, $coin_symbol, $parent_aggrive_stop_loss_compare_value, $stop_loss_percent);

						///////////////////End of update trail stop
						///////////////////////////////////////////
						$upd_data22 = array(
							'status' => 'FILLED',
							'market_value' => $market_price,
							'iniatial_trail_stop' => $update_trail_stop,
							'buy_date' => $this->mongo_db->converToMongodttime($date),
						);
						$this->mongo_db->where(array('_id' => $id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						$log_msg = " Order was Buyed at Price " . number_format($market_price, 8);
						$this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = "Buy Market Order is <b>buyed</b> as status Filled market_price=" . number_format($market_price, 8) . "  buy_price  " . number_format($buy_price, 8) . '  high_value' . number_format($high_value, 8);
						$this->add_notification($id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////
						//Check Market History
						$commission = $quantity * (0.001);
						$commissionAsset = str_replace('BTC', '', $symbol);
						//Check Market History
						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($id, $log_msg, 'buy_commision', $admin_id, $date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
						$arr_response['message'] = $message . '---->log_msg' . $log_msg;
					} //If Market Price Match

				} //If Current candel  Exist
				else {
					$arr_response['message'] = 'NO Order Buyed';
				}

				////////////////////////////////
				///////////////////////////////

			} //End Of ForEach
		} //End Of if count

		return $arr_response;
	} //End of buy_order_box_trigger_3_samulater

	public function sell_order_box_trigger_3_samulater($date = '') {

		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where(array('status' => 'FILLED', 'trigger_type' => 'box_trigger_3', 'order_mode' => 'test_simulator'));
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);
		$return_response = '';
		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		$arr_response = array();
		if (count($buy_orders_arr) > 0) {
			foreach ($buy_orders_arr as $buy_orders) {
				$buy_orders_id = $buy_orders['_id'];
				$coin_symbol = $buy_orders['symbol'];
				$sell_price = $buy_orders['sell_price'];
				$admin_id = $buy_orders['admin_id'];
				$purchased_price = $buy_orders['price'];
				$buy_purchased_price = $buy_orders['market_value'];
				$iniatial_trail_stop = $buy_orders['iniatial_trail_stop'];
				$application_mode = $buy_orders['application_mode'];
				$quantity = $buy_orders['quantity'];
				$order_type = $buy_orders['order_type'];
				$trigger_type = $buy_orders['trigger_type'];

				$order_mode = $buy_orders['order_mode'];

				$where = array('openTime_human_readible' => $current_date, 'coin' => $coin_symbol);
				$result_arr = array();
				$this->mongo_db->where($where);
				$current_candel_result = $this->mongo_db->get('market_chart');
				$current_candel_arr = iterator_to_array($current_candel_result);

				//////////////// Test Mode//////////////////////////
				///////////////////////////////////////////////////

				if (count($current_candel_arr) > 0) {
					$high_value = $current_candel_arr[0]['high'];
					$low_value = $current_candel_arr[0]['low'];
					$open = $current_candel_arr[0]['open'];
					$market_price = $low_value;
					$created_date = date("Y-m-d G:i:s");

					//Sell with Stop Loss
					if ($market_price < $iniatial_trail_stop && $iniatial_trail_stop != '') {
						$sell_price = $iniatial_trail_stop;
						$upd_data22 = array(
							'is_sell_order' => 'sold',
							'market_sold_price' => $sell_price,
						);
						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						//////////////////////////////
						//////////////////////////////
						$ins_data = array(
							'symbol' => $coin_symbol,
							'purchased_price' => num($buy_purchased_price),
							'quantity' => $quantity,
							'profit_type' => 'percentage',
							'order_type' => 'MARKET_ORDER',
							'admin_id' => $admin_id,
							'buy_order_check' => 'yes',
							'buy_order_id' => $buy_orders_id,
							'buy_order_binance_id' => '',
							'stop_loss' => 'no',
							'loss_percentage' => '',
							'created_date' => $this->mongo_db->converToMongodttime($current_date),
							'market_value' => $market_price,
							'application_mode' => $application_mode,
							'order_mode' => $order_mode,
						);

						$ins_data['sell_profit_percent'] = 2;
						$ins_data['sell_price'] = $sell_price;

						$ins_data['trail_check'] = 'no';
						$ins_data['trail_interval'] = '0';
						$ins_data['sell_trail_price'] = '0';
						$ins_data['status'] = 'FILLED';
						$ins_data['sell_date'] = $this->mongo_db->converToMongodttime($date);
						$ins_data['trigger_type'] = $trigger_type;

						//Insert data in mongoTable
						$order_id = $this->mongo_db->insert('orders', $ins_data);

						$upd_data = array(
							'sell_order_id' => $order_id,
						);
						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////
						$message = 'Sell Order was Sold With Loss';
						$log_msg = $message . " " . number_format($sell_price, 8);
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $current_date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = $message . " <b>Sold</b>";
						$this->add_notification($buy_orders_id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////
						//Check Market History
						$commission_value = $quantity * (0.001);
						$commission = $commission_value * $market_value;
						$commissionAsset = 'BTC';
						//Check Market History
						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $current_date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
						$response['message'] = '$log_msg  ' . $log_msg . '  $message ' . $message;

					} else if ($market_price <= $sell_price && $high_value >= $sell_price) {
						//Sell With Normal Value
						$upd_data22 = array(
							'is_sell_order' => 'sold',
							'market_sold_price' => $sell_price,
						);
						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						/////////////////////////////////////
						///////////////////////////////////
						$ins_data = array(
							'symbol' => $coin_symbol,
							'purchased_price' => num($buy_purchased_price),
							'quantity' => $quantity,
							'profit_type' => 'percentage',
							'order_type' => 'MARKET_ORDER',
							'admin_id' => $admin_id,
							'buy_order_check' => '',
							'buy_order_id' => $buy_orders_id,
							'buy_order_binance_id' => '',
							'stop_loss' => 'yes',
							'loss_percentage' => '',
							'created_date' => $this->mongo_db->converToMongodttime($current_date),
							'market_value' => $market_price,
							'application_mode' => $application_mode,
							'order_mode' => $order_mode,
						);
						$ins_data['sell_profit_percent'] = 2;
						$ins_data['sell_price'] = $sell_price;
						$ins_data['trail_check'] = 'no';
						$ins_data['trail_interval'] = '0';
						$ins_data['sell_trail_price'] = '0';
						$ins_data['status'] = 'FILLED';
						$ins_data['sell_date'] = $this->mongo_db->converToMongodttime($date);
						$ins_data['trigger_type'] = $trigger_type;
						//Insert data in mongoTable
						$order_id = $this->mongo_db->insert('orders', $ins_data);

						$upd_data = array(
							'sell_order_id' => $order_id,
						);

						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data);

						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						$message = 'Sell Order was Sold With profit';

						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////
						$log_msg = $message . " " . number_format($sell_price, 8);
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $current_date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = $message . " <b>Sold</b>";
						$this->add_notification($buy_orders_id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////
						//Check Market History
						$commission_value = $quantity * (0.001);
						$commission = $commission_value * $market_value;
						$commissionAsset = 'BTC';
						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $current_date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
						$response['message'] = '$log_msg  ' . $log_msg . '  $message ' . $message;
					} else {
						$arr_response['message'] = 'NO trigger match for sell $low_value  ' . number_format($low_value, 8) . '  sell_price ' . number_format($sell_price, 8) . '  high_value' . number_format($high_value, 8);
					}
				} else {
					$arr_response['message'] = 'Order is Not Sold';
				}
				///////////////////////////////////////////////
				///////////////////////////////////////////////

			} //End of for each order
		} //End of End Condition
		return $arr_response['message'];
	} //End of sell_order_trigger_2_samulater

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////                              /////////////////
	////////////////////                             ////////////////////////////////
	//////////////////// Box_Trigger_3  Live       /////////////////////////////////
	////////////////////                           ////////////////////////////////
	////////////////////                              /////////////////
	////////////////////                             /////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function find_previous_lowest_status_live($date, $coin_symbol) {
		$this->mongo_db->where(array('coin' => $coin_symbol));
		$this->mongo_db->where_in('global_swing_parent_status', array('LL', 'HL'));
		$this->mongo_db->order_by(array('timestampDate' => -1));
		$this->mongo_db->limit(1);

		$previouse_candel_result = $this->mongo_db->get('market_chart');
		$previouse_candel_arr = iterator_to_array($previouse_candel_result);
		$global_swing_status = 0;
		if (count($previouse_candel_arr) > 0) {

			$global_swing_status = $previouse_candel_arr[0]['low'];
		}

		return $global_swing_status;
	} //End of find_previous_lowest_status

	public function create_new_orders_by_Box_Trigger_3_live($date = '') {

		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));

		if ($date) {

			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		//Check of Parent Order  Exist

		$this->mongo_db->where_ne('buy_order_status_new_filled', 'wait_for_buyed');
		$this->mongo_db->where_in('order_mode', array('test_live', 'live'));
		$this->mongo_db->where(array('status' => 'new', 'trigger_type' => 'box_trigger_3'));
		$parent_orders_object = $this->mongo_db->get('buy_orders');
		$parent_orders_arr = iterator_to_array($parent_orders_object);

		$response_arr['message'] = 'parent order not found';
		//if parent order exist then creat buy orders
		if (count($parent_orders_arr) > 0) {

			foreach ($parent_orders_arr as $buy_orders) {

				$buy_parent_id = $buy_orders['_id'];
				$coin_symbol = $buy_orders['symbol'];
				$buy_quantity = $buy_orders['quantity'];
				$buy_trigger_type = $buy_orders['trigger_type'];
				$admin_id = $buy_orders['admin_id'];
				$application_mode = $buy_orders['application_mode'];
				$order_mode = $buy_orders['order_mode'];

				//Get TRigger setting
				$this->mongo_db->where(array('coins' => $coin_symbol, 'triggers_type' => 'box_trigger_3'));
				$res_coin_setting = $this->mongo_db->get('setting_triggers_collections');
				$res_coin_setting_arr = iterator_to_array($res_coin_setting);

				$buy_price_percentage = 30;
				$stop_loss_percent = 4;
				$sell_price_percent = 3;
				if (count($res_coin_setting_arr) > 0) {
					foreach ($res_coin_setting_arr as $res_coin_setting) {
						$buy_price_percentage = $res_coin_setting['buy_price'];
						$stop_loss_percent = $res_coin_setting['stop_loss'];
						$sell_price_percent = $res_coin_setting['sell_price'];

					}
				}

				//Check of previous candel is Demond Candel
				$this->mongo_db->where(array('openTime_human_readible' => $prevouse_date, 'coin' => $coin_symbol, 'candle_type' => 'demand', 'trigger_status' => 0));

				$previouse_candel_result = $this->mongo_db->get('market_chart');
				$previouse_candel_arr = iterator_to_array($previouse_candel_result);

				if (count($previouse_candel_arr) > 0) {

					foreach ($previouse_candel_arr as $candel_data) {
						$demand_candel_high_value = $candel_data['high'];
						$global_swing_status = $candel_data['global_swing_status'];
						$demand_candel_low_value = $candel_data['low'];
					}
					//Find Lowest Low value
					$this->global_candel_status['global_swing_status'] = $global_swing_status;
					$this->global_candel_status['global_low_value'] = $demand_candel_low_value;

					//Call function to Get Lowest Value

					$lowest_value = $this->find_previous_lowest_status_live($prevouse_date, $coin_symbol);

					$differenc_value = $demand_candel_high_value - $lowest_value;
					$buy_price = $lowest_value + ($differenc_value * ($buy_price_percentage / 100));

					$iniatial_trail_stop = $buy_price - ($buy_price / 100) * $stop_loss_percent;
					$sell_price = $buy_price + ($buy_price / 100) * $sell_price_percent;

					$update_prices_arr = array('price' => $buy_price, 'iniatial_trail_stop' => $iniatial_trail_stop, 'sell_price' => $sell_price, 'unique_time_id_to_check_update' => strtotime($date));

					///////////////////////////////////////////////////////////
					////////////////////////                /////////////////////////////
					///////////////////////  Create Order  /////////////////////////////
					//////////////////////                 ////////////////////////////
					///////////////////////////////////////////////////////////

					$ins_data = array(
						'price' => num($buy_price),
						'quantity' => $buy_quantity,
						'symbol' => $coin_symbol,
						'order_type' => 'MARKET_ORDER',
						'admin_id' => $admin_id,
						'trigger_type' => 'box_trigger_3',
						'sell_price' => num($sell_price),
						'created_date' => $this->mongo_db->converToMongodttime($date),
					);
					$ins_data['trail_check'] = 'no';
					$ins_data['trail_interval'] = '0';
					$ins_data['buy_trail_price'] = '0';
					$ins_data['status'] = 'new';
					$ins_data['auto_sell'] = 'no';
					$ins_data['buy_parent_id'] = $buy_parent_id;
					$ins_data['iniatial_trail_stop'] = $iniatial_trail_stop;
					$ins_data['buy_order_status_new_filled'] = 'wait_for_buyed';
					$ins_data['application_mode'] = $application_mode;
					$ins_data['order_mode'] = $order_mode;
					$ins_data['order_mode_lock_for_update'] = 0;

					//Check if Status Is Not Lock Then Update it
					$this->mongo_db->where_ne('is_sell_order', 'sold');
					//$this->mongo_db->where_ne('unique_time_id_to_check_update',strtotime($date));
					$this->mongo_db->where(array('symbol' => $coin_symbol, 'order_mode_lock_for_update' => 0, 'buy_order_status_new_filled' => 'wait_for_buyed', 'status' => 'new'));

					$responese_obj = $this->mongo_db->get('buy_orders');
					$responese_arr = iterator_to_array($responese_obj);
					if (count($responese_arr) > 0) {

						foreach ($responese_arr as $response_data) {
							$order_id = $response_data['_id'];
							$this->mongo_db->where(array('_id' => $order_id));
							$this->mongo_db->set($update_prices_arr);
							//Update data in mongoTable
							$this->mongo_db->update('buy_orders');

							if ($order_mode == 'live') {
								$order_typ = 'Live';
							} else {
								$order_typ = 'Test Live';
							}

							////////////////////////////// Order History Log /////////////////////////////
							$log_msg = "Buy " . $order_typ . " Order was Updated To Price " . num($buy_price);
							$created_date = date('Y-m-d g:i:s A');
							$this->insert_order_history_log($order_id, $log_msg, 'buy_created', $admin_id, $created_date);
							////////////////////////////// End Order History Log /////////////////////////
							//////////////////////////////////////////////////////////////////////////////
							////////////////////// Set Notification //////////////////
							$message = "Buy Market Order is <b>Updated</b> as status new";
							$this->add_notification($order_id, 'buy', $message, $admin_id);
						} //End Of Foreach

					} else {
//End Of Reponse Arr

						///////////////////////////////////////////////
						//////////////////////
						/////////
						//Insert data in mongoTable
						$buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);
						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////

						if ($order_mode == 'live') {
							$order_typ = 'Live';
						} else {
							$order_typ = 'Test Live';
						}

						$log_msg = "Buy " . $order_typ . " Order was Created at Price " . num($buy_price);
						$created_date = date('Y-m-d g:i:s A');
						$this->insert_order_history_log($buy_order_id, $log_msg, 'buy_created', $admin_id, $created_date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = "Buy Market Order is <b>Created</b> as status new";
						$this->add_notification($buy_order_id, 'buy', $message, $admin_id);
						/////////
						/////////////////////
						//////////////////////////////////////////////
					}

				} else {
					//End Of previouse_candel_arr

					$this->global_candel_status['global_swing_status'] = '';
					$this->global_candel_status['global_low_value'] = '';
				}

				//Check of Heigher Condition Come

				$this->mongo_db->where(array('openTime_human_readible' => $prevouse_date, 'coin' => $coin_symbol));
				$candel_object = $this->mongo_db->get('market_chart');
				$candel_arr = iterator_to_array($candel_object);

				if (count($candel_arr) > 0) {
					$upd_arr = array(
						'order_mode_lock_for_update' => 1,
					);

					$conn = $this->mongo_db->customQuery();
					$res = $conn->buy_orders->updateMany(array('trigger_type' => 'box_trigger_3', 'buy_order_status_new_filled' => 'wait_for_buyed', 'symbol' => $coin_symbol), array('$set' => $upd_arr));

				} //End of if Count Condition

				/////////////////////////////////////////////
				////////////////////////////////////////////

				$upd_status = array(
					'trigger_status' => 1,
				);

				$conn = $this->mongo_db->customQuery();
				$res = $conn->market_chart->updateMany(array('openTime_human_readible' => $prevouse_date), array('$set' => $upd_status));

				/////////////////////////////////////////////
				////////////////////////////////////////////

			} //End Of Foreach
		} //End Of  parent order exist

		$response_arr['message'] = $this->buy_order_box_trigger_3_live($date, $coin_symbol, $stop_loss_percent);
	} //End of create_new_orders_by_Box_Trigger_3_live

	function buy_order_box_trigger_3_live($date, $coin_symbol, $stop_loss_percent) {
		$this->mongo_db->where_in('order_mode', array('test_live', 'live'));
		$this->mongo_db->where(array('status' => 'new', 'trigger_type' => 'box_trigger_3', 'buy_order_status_new_filled' => 'wait_for_buyed'));

		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);
		$return_response = '';
		//**************************************************************
		$arr_response = array();
		if (count($buy_orders_arr)) {
			foreach ($buy_orders_arr as $buy_orders) {
				$id = $buy_orders['_id'];
				$buy_price = $buy_orders['price'];
				$admin_id = $buy_orders['admin_id'];
				$quantity = $buy_orders['quantity'];
				$application_mode = $buy_orders['application_mode'];
				$coin_symbol = $buy_orders['symbol'];
				//////////////////////////////////
				/////////////////////////////////
				$market_price = $this->mod_dashboard->get_market_value($coin_symbol);

				if ($market_price <= $buy_price) {

					if ($application_mode == 'live') {
						$this->mod_dashboard->binance_buy_auto_market_order_live($id, $quantity, $market_price, $coin_symbol, $admin_id);

						////////////////update trail stop
						$update_trail_stop = $market_price - ($market_price / 100) * $stop_loss_percent;
						///////////////////End of update trail stop
						///////////////////////////////////////////
						$upd_data22 = array(
							'iniatial_trail_stop' => $update_trail_stop,
						);
						$this->mongo_db->where(array('_id' => $id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
					} else {

						////////////////update trail stop
						$update_trail_stop = $market_price - ($market_price / 100) * $stop_loss_percent;
						///////////////////End of update trail stop
						///////////////////////////////////////////
						$upd_data22 = array(
							'status' => 'FILLED',
							'market_value' => $market_price,
							'iniatial_trail_stop' => $update_trail_stop,
						);
						$this->mongo_db->where(array('_id' => $id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						$log_msg = " Order was Buyed at Price " . number_format($market_price, 8);
						$created_date = date('Y-m-d G:i:s');
						$this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $created_date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = "Buy Market Order is <b>buyed</b> as status Filled market_price=" . number_format($market_price, 8) . "  buy_price  " . number_format($buy_price, 8) . '  high_value' . number_format($high_value, 8);
						$this->add_notification($id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////
						//Check Market History
						$commission = $quantity * (0.001);
						$commissionAsset = str_replace('BTC', '', $symbol);
						//Check Market History
						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($id, $log_msg, 'buy_commision', $admin_id, $created_date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
						$arr_response['message'] = $message . '---->log_msg' . $log_msg;

					}

				}
				////////////////////////////////
				///////////////////////////////
			}
		}

		return $arr_response;

	} //End of buy_order_box_trigger_3_samulater

	public function sell_order_box_trigger_3_live($date = '') {

		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where_in('order_mode', array('test_live', 'live'));
		$this->mongo_db->where(array('status' => 'FILLED', 'trigger_type' => 'box_trigger_3'));
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);

		$arr_response = array();
		if (count($buy_orders_arr) > 0) {
			foreach ($buy_orders_arr as $buy_orders) {
				$buy_orders_id = $buy_orders['_id'];
				$coin_symbol = $buy_orders['symbol'];
				$sell_price = $buy_orders['sell_price'];
				$admin_id = $buy_orders['admin_id'];
				$purchased_price = $buy_orders['price'];
				$buy_purchased_price = $buy_orders['market_value'];
				$iniatial_trail_stop = $buy_orders['iniatial_trail_stop'];
				$application_mode = $buy_orders['application_mode'];
				$quantity = $buy_orders['quantity'];
				$order_type = $buy_orders['order_type'];
				$order_mode = $buy_orders['order_mode'];
				$binance_order_id = $buy_orders['binance_order_id'];
				$trigger_type = $buy_orders['trigger_type'];

				/////////////// Start of live mode///////////////////////////
				////////////////////////////////////////////////////////////
				$market_price = $this->mod_dashboard->get_market_value($coin_symbol);
				//Sell with Stop Loss
				if ($market_price < $iniatial_trail_stop && $iniatial_trail_stop != '') {

					//////////////////////////////
					//////////////////////////////
					$ins_data = array(
						'symbol' => $coin_symbol,
						'purchased_price' => num($buy_purchased_price),
						'quantity' => $quantity,
						'profit_type' => 'percentage',
						'order_type' => 'MARKET_ORDER',
						'admin_id' => $admin_id,
						'buy_order_check' => 'yes',
						'buy_order_id' => $buy_orders_id,
						'buy_order_binance_id' => $binance_order_id,
						'stop_loss' => 'no',
						'loss_percentage' => '',
						'created_date' => $this->mongo_db->converToMongodttime($current_date),
						'market_value' => $market_price,
						'application_mode' => $application_mode,
						'order_mode' => $order_mode,
						'trigger_type' => $trigger_type,
					);

					$ins_data['sell_profit_percent'] = 2;
					$ins_data['sell_price'] = $sell_price;

					$ins_data['trail_check'] = 'no';
					$ins_data['trail_interval'] = '0';
					$ins_data['sell_trail_price'] = '0';
					$ins_data['status'] = 'new';

					//Insert data in mongoTable
					$order_id = $this->mongo_db->insert('orders', $ins_data);

					if ($application_mode == 'live') {
						$this->mod_dashboard->binance_sell_auto_market_order_live($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id);

						$upd_data22 = array(
							'sell_order_id' => $order_id,
							'is_sell_order' => 'sold',
						);

						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');

					} else {

						$upd_data_1 = array(
							'status' => 'FILLED',
						);
						$this->mongo_db->where(array('_id' => $order_id));
						$this->mongo_db->set($upd_data_1);
						//Update data in mongoTable
						$this->mongo_db->update('orders');
						$sell_price = $iniatial_trail_stop;
						$upd_data22 = array(
							'is_sell_order' => 'sold',
							'market_sold_price' => $sell_price,
							'sell_order_id' => $order_id,
						);

						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////
						$message = 'Sell Order was Sold With Loss';
						$log_msg = $message . " " . number_format($sell_price, 8);
						$created_date = date('Y-m-d G:i:s');
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $created_date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = $message . " <b>Sold</b>";
						$this->add_notification($buy_orders_id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////
						//Check Market History
						$commission_value = $quantity * (0.001);
						$commission = $commission_value * $market_value;
						$commissionAsset = 'BTC';
						//Check Market History
						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $created_date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
						$response['message'] = '$log_msg  ' . $log_msg . '  $message ' . $message;
					}

				} else if ($market_price >= $sell_price) {

					/////////////////////////////////////
					///////////////////////////////////
					$ins_data = array(
						'symbol' => $coin_symbol,
						'purchased_price' => num($buy_purchased_price),
						'quantity' => $quantity,
						'profit_type' => 'percentage',
						'order_type' => 'MARKET_ORDER',
						'admin_id' => $admin_id,
						'buy_order_check' => '',
						'buy_order_id' => $buy_orders_id,
						'buy_order_binance_id' => $binance_order_id,
						'stop_loss' => 'yes',
						'loss_percentage' => '',
						'created_date' => $this->mongo_db->converToMongodttime($current_date),
						'market_value' => $market_price,
						'application_mode' => $application_mode,
						'order_mode' => $order_mode,
						'trigger_type' => $trigger_type,
					);
					$ins_data['sell_profit_percent'] = 2;
					$ins_data['sell_price'] = $sell_price;
					$ins_data['trail_check'] = 'no';
					$ins_data['trail_interval'] = '0';
					$ins_data['sell_trail_price'] = '0';
					$ins_data['status'] = 'new';
					//Insert data in mongoTable
					$order_id = $this->mongo_db->insert('orders', $ins_data);

					if ($application_mode == 'live') {

						$this->mod_dashboard->binance_sell_auto_market_order_live($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id);

						$upd_data22 = array(
							'sell_order_id' => $order_id,
							'is_sell_order' => 'sold',
						);

						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
					} else {

						//Sell With Normal Value

						$upd_data_1 = array(
							'status' => 'FILLED',
						);

						$this->mongo_db->where(array('_id' => $order_id));
						$this->mongo_db->set($upd_data_1);

						//Update data in mongoTable
						$this->mongo_db->update('orders');

						$upd_data = array(
							'sell_order_id' => $order_id,
							'is_sell_order' => 'sold',
							'market_sold_price' => $sell_price,
						);

						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data);

						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						$message = 'Sell Order was Sold With profit';

						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////
						$log_msg = $message . " " . number_format($sell_price, 8);
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $current_date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = $message . " <b>Sold</b>";
						$this->add_notification($buy_orders_id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////
						//Check Market History
						$commission_value = $quantity * (0.001);
						$commission = $commission_value * $with;
						$commissionAsset = 'BTC';
						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $current_date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
						$response['message'] = '$log_msg  ' . $log_msg . '  $message ' . $message;
					}

				} else {
					$arr_response['message'] = 'NO trigger match for sell $low_value  ' . number_format($low_value, 8) . '  sell_price ' . number_format($sell_price, 8) . '  high_value' . number_format($high_value, 8);
				}

				/////////////////////End of live Mode//////////////////////////
				///////////////////////////////////////////////

			} //End of for each order
		} //End of End Condition

		return $arr_response['message'];
	} //End of sell_order_trigger_2_samulater

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////                                        /////////////////
	////////////////////                                       ////////////////////////////////
	//////////////////// Box_Trigger_3_aggressive_trail_stop /////////////////////////////////
	////////////////////                                     ////////////////////////////////
	////////////////////                                        /////////////////
	////////////////////                                       /////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function Box_Trigger_3_aggressive_trail_stop($date = '', $coin_symbol = '', $parent_aggrisive_stop_loss_compate_value = '', $stop_loss_percent = '') {

		$current_date = date('Y-m-d H:00:00', strtotime($date));
		//Check of previous candel is Demond Candel
		$this->mongo_db->where(array('openTime_human_readible' => $current_date, 'coin' => $coin_symbol));
		$current_candel_object = $this->mongo_db->get('market_chart');
		$current_candel_arr = iterator_to_array($current_candel_object);
		$iniatial_trail_stop = '';

		if (count($current_candel_arr) > 0) {
			$current_close_value = $current_candel_arr[0]['close'];
			if ($current_close_value > $parent_aggrisive_stop_loss_compate_value) {
				$iniatial_trail_stop = $current_close_value - ($current_close_value / 100) * $stop_loss_percent;
			} else {
				$iniatial_trail_stop = $parent_aggrisive_stop_loss_compate_value - ($parent_aggrisive_stop_loss_compate_value / 100) * $stop_loss_percent;
			}
		}

		return $iniatial_trail_stop;

	} //End of Box_Trigger_3_aggressive_trail_stop

	public function is_sell_order_in_error_status($sell_order_id) {
		$this->mongo_db->where(array('_id' => $sell_order_id, 'status' => 'error'));
		$data = $this->mongo_db->get('orders');
		$row = iterator_to_array($data);
		$res = false;
		if (count($row) > 0) {
			$res = true;
		}
		return $res;
	} //End of is_sell_order_in_error_status

	public function is_sell_order_in_submitted_status($sell_order_id) {
		$this->mongo_db->where(array('_id' => $sell_order_id, 'status' => 'submitted'));
		$data = $this->mongo_db->get('orders');
		$row = iterator_to_array($data);
		$res = false;
		if (count($row) > 0) {
			$res = true;
		}
		return $res;

	} //End of is_sell_order_in_error_status
} //End of Model
?>
