<?php
/**
 *
 */
class Mod_sell_orders extends CI_Model {

	function __construct() {
		# code...
	}

	//get_orders
	public function get_orders($skip, $limit) {

		$admin_id = $this->session->userdata('admin_id');
		$application_mode = $this->session->userdata('global_mode');

		/*echo "<pre>";
			print_r($this->session->userdata());
		*/
		//Check Filter Data
		$session_post_data = $this->session->userdata('filter-data');

		$search_array = array('admin_id' => $admin_id, 'application_mode' => $application_mode);
		if ($session_post_data['filter_coin'] != "") {

			$symbol = $session_post_data['filter_coin'];
			$search_array['symbol'] = $symbol;
		}
		if ($session_post_data['filter_type'] != "") {

			$order_type = $session_post_data['filter_type'];
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
		$cursor = $connetct->orders->find($search_array, $qr);

		$responseArr = iterator_to_array($cursor);
		/*$this->mongo_db->where($search_array);
			$this->mongo_db->sort(array('_id'=> 'desc'));
		*/

		$fullarray = array();
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
				$returArr['binance_order_id'] = $valueArr['binance_order_id'];
				$returArr['purchased_price'] = $valueArr['purchased_price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['profit_type'] = $valueArr['profit_type'];
				$returArr['sell_profit_percent'] = $valueArr['sell_profit_percent'];
				$returArr['sell_profit_price'] = $valueArr['sell_profit_price'];
				$returArr['sell_price'] = $valueArr['sell_price'];
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['trail_check'] = $valueArr['trail_check'];
				$returArr['trail_interval'] = $valueArr['trail_interval'];
				$returArr['sell_trail_price'] = $valueArr['sell_trail_price'];
				$returArr['order_type'] = $valueArr['order_type'];
				$returArr['stop_loss'] = $valueArr['stop_loss'];
				$returArr['loss_percentage'] = $valueArr['loss_percentage'];
				$returArr['status'] = $valueArr['status'];
				$returArr['admin_id'] = $valueArr['admin_id'];
				$returArr['buy_order_id'] = $valueArr['buy_order_id'];
				$returArr['buy_order_binance_id'] = $valueArr['buy_order_binance_id'];
				$returArr['application_mode'] = $valueArr['application_mode'];
				$returArr['trigger_type'] = $valueArr['trigger_type'];
				$returArr['created_date'] = $formated_date_time;
			}

			$fullarray[] = $returArr;
		}

		return $fullarray;

	} //end get_orders

	public function get_orders_by_status($status, $skip, $limit) {

		$admin_id = $this->session->userdata('admin_id');
		$application_mode = $this->session->userdata('global_mode');

		//Check Filter Data
		$session_post_data = $this->session->userdata('filter-data');

		$search_array = array('admin_id' => $admin_id, 'application_mode' => $application_mode);
		if ($session_post_data['filter_coin'] != "") {

			$symbol = $session_post_data['filter_coin'];
			$search_array['symbol'] = $symbol;
		}
		if ($session_post_data['filter_type'] != "") {

			$order_type = $session_post_data['filter_type'];
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
		$search_array['status'] = $status;
		$qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
		$cursor = $connetct->orders->find($search_array, $qr);

		$responseArr = iterator_to_array($cursor);
		/*$this->mongo_db->where($search_array);
			$this->mongo_db->sort(array('_id'=> 'desc'));
		*/

		$fullarray = array();
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
				$returArr['binance_order_id'] = $valueArr['binance_order_id'];
				$returArr['purchased_price'] = $valueArr['purchased_price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['profit_type'] = $valueArr['profit_type'];
				$returArr['sell_profit_percent'] = $valueArr['sell_profit_percent'];
				$returArr['sell_profit_price'] = $valueArr['sell_profit_price'];
				$returArr['sell_price'] = $valueArr['sell_price'];
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['trail_check'] = $valueArr['trail_check'];
				$returArr['trail_interval'] = $valueArr['trail_interval'];
				$returArr['sell_trail_price'] = $valueArr['sell_trail_price'];
				$returArr['order_type'] = $valueArr['order_type'];
				$returArr['stop_loss'] = $valueArr['stop_loss'];
				$returArr['loss_percentage'] = $valueArr['loss_percentage'];
				$returArr['status'] = $valueArr['status'];
				$returArr['admin_id'] = $valueArr['admin_id'];
				$returArr['buy_order_id'] = $valueArr['buy_order_id'];
				$returArr['buy_order_binance_id'] = $valueArr['buy_order_binance_id'];
				$returArr['application_mode'] = $valueArr['application_mode'];
				$returArr['created_date'] = $formated_date_time;
			}

			$fullarray[] = $returArr;
		}

		return $fullarray;

	} //end get_orders_by_status

	public function get_order($id) {

		$user_id = $this->session->userdata('admin_id');
		if ($user_id != 5) {
			$search_array['admin_id'] = $user_id;
		}
		$search_array['_id'] = $id;
		$this->mongo_db->where($search_array);
		$responseArr = $this->mongo_db->get('orders');

		foreach ($responseArr as $valueArr) {
			$returArr = array();
			if (!empty($valueArr)) {

				$datetime = $valueArr['created_date']->toDateTime();
				$created_date = $datetime->format(DATE_RSS);

				$datetime = new DateTime($created_date);
				$datetime->format('Y-m-d g:i:s A e');

				$new_timezone = new DateTimeZone('Asia/Karachi');
				$datetime->setTimezone($new_timezone);
				$formated_date_time = $datetime->format('Y-m-d g:i:s A e');

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
				$returArr['loss_percentage'] = $valueArr['loss_percentage'];
				$returArr['application_mode'] = $valueArr['application_mode'];
				$returArr['trigger_type'] = $valueArr['trigger_type'];
			}
		}

		return $returArr;

	} //end get_order

	public function count_all() {
		$admin_id = $this->session->userdata('admin_id');
		$application_mode = $this->session->userdata('global_mode');

		//Check Filter Data
		$session_post_data = $this->session->userdata('filter-data');

		$search_array = array('admin_id' => $admin_id, 'application_mode' => $application_mode);
		if ($session_post_data['filter_coin'] != "") {

			$symbol = $session_post_data['filter_coin'];
			$search_array['symbol'] = $symbol;
		}
		if ($session_post_data['filter_type'] != "") {

			$order_type = $session_post_data['filter_type'];
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
		$cursor = $connetct->orders->count($search_array);

		return $cursor;
	}

	public function count_by_status($status) {
		$admin_id = $this->session->userdata('admin_id');
		$application_mode = $this->session->userdata('global_mode');

		//Check Filter Data
		$session_post_data = $this->session->userdata('filter-data');

		$search_array = array('admin_id' => $admin_id, 'application_mode' => $application_mode);
		if ($session_post_data['filter_coin'] != "") {

			$symbol = $session_post_data['filter_coin'];
			$search_array['symbol'] = $symbol;
		}
		if ($session_post_data['filter_type'] != "") {

			$order_type = $session_post_data['filter_type'];
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

		$search_array['status'] = $status;
		$cursor = $connetct->orders->count($search_array);

		/*echo "<pre>";
			print_r($search_array);
		*/

		return $cursor;
	}

	public function get_balance($symbol, $user_id) {

		$this->mongo_db->where(array('user_id' => $admin_id,'symbol'=>$symbol));
		$get_coins = $this->mongo_db->get('coins');
		$coins_arr = iterator_to_array($get_coins);
		$coins_arr = $coins_arr[0];
		return $coins_arr['coin_balance'];

	}//End of get_balance
}
?>
