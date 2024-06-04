<?php
/**
 *
 */
class mod_api extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	public function get_all_coin_meta($coin) {

		$this->mongo_db->where('coin', $coin);

		$res = $this->mongo_db->get('coin_meta');

		$result_arr = iterator_to_array($res);

		$new_result = $result_arr[0];

		unset($new_result['_id']);

		$modified_date = $new_result['modified_date'];
		$new_result['modified_date'] = $modified_date->toDatetime()->format('Y-m-d H:i:s');

		$new_result['current_market_value'] = num($new_result['current_market_value']);
		$new_result['ask_black_wall'] = num($new_result['ask_black_wall']);
		$new_result['ask_yellow_wall'] = num($new_result['ask_yellow_wall']);
		$new_result['bid_black_wall'] = num($new_result['bid_black_wall']);
		$new_result['bid_yellow_wall'] = num($new_result['bid_yellow_wall']);
		$new_result['up_big_price'] = num($new_result['up_big_price']);
		$new_result['down_big_price'] = num($new_result['down_big_price']);
		$new_result['great_wall_price'] = num($new_result['great_wall_price']);
		$new_result['bid_contracts'] = number_format_short($new_result['bid_contracts']);
		$new_result['ask_contract'] = number_format_short($new_result['ask_contract']);
		$new_result['buyers'] = number_format_short($new_result['buyers']);
		$new_result['sellers'] = number_format_short($new_result['sellers']);
		$new_result['up_big_wall'] = number_format_short($new_result['up_big_wall']);
		$new_result['down_big_wall'] = number_format_short($new_result['down_big_wall']);
		$new_result['great_wall_quantity'] = number_format_short($new_result['great_wall_quantity']);
		return $new_result;
	}

	public function get_all_user_orders($user_id, $start_date, $end_date, $status) {
		//Check Filter Data
		$session_post_data = $filter_array;

		$search_array = array('admin_id' => $user_id);
		//$search_array = array('admin_id'=> $admin_id);
		if ($start_date != "" && $end_date != "") {

			$created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
			$orig_date = new DateTime($created_datetime);
			$orig_date = $orig_date->getTimestamp();
			$start_date1 = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

			$created_datetime22 = date('Y-m-d G:i:s', strtotime($end_date));
			$orig_date22 = new DateTime($created_datetime22);
			$orig_date22 = $orig_date22->getTimestamp();
			$end_date1 = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
			$search_array['created_date'] = array('$gte' => $start_date1, '$lte' => $end_date1);
		}

		$connetct = $this->mongo_db->customQuery();

		if ($status == 'open' || $status == 'sold') {
			if ($status == 'open') {

				$search_array['status'] = 'FILLED';
				$search_array['is_sell_order'] = 'yes';
				$cursor = $connetct->buy_orders->find($search_array);

			} elseif ($status == 'sold') {

				$search_array['status'] = 'FILLED';
				$search_array['is_sell_order'] = 'sold';
				$cursor = $connetct->buy_orders->find($search_array);

			}
		} elseif ($status == 'all') {
			$search_array['status'] = array('$in' => array('error', 'canceled', 'submitted'));
			$cursor = $connetct->buy_orders->find($search_array);

		} else {

			$search_array['status'] = $status;
			$cursor = $connetct->buy_orders->find($search_array);

		}
		$responseArr = iterator_to_array($cursor);

		$fullarray = array();
		foreach ($responseArr as $valueArr) {

			$returArr = array();
			$profit = 0;
			if (!empty($valueArr)) {

				$datetime = $valueArr['created_date']->toDateTime();
				$created_date = $datetime->format(DATE_RSS);

				$datetime = new DateTime($created_date);
				$datetime->format('Y-m-d g:i:s A');

				$new_timezone = new DateTimeZone('Asia/Karachi');
				$datetime->setTimezone($new_timezone);
				$formated_date_time = $datetime->format('Y-m-d g:i:s A');

				$returArr['id'] = (string) $valueArr['_id'];
				$returArr['purchased_price'] = $valueArr['market_value'];
				$returArr['sold_price'] = $valueArr['market_sold_price'];
				$profit = ((($returArr['sold_price'] - $returArr['purchased_price']) / $returArr['purchased_price']) * 100);
				$returArr['profit_loss_percentage'] = number_format($profit, 2);
				$returArr['coin'] = $valueArr['symbol'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['order_type'] = $valueArr['order_type'];
				if ($valueArr['status'] == 'FILLED' && $valueArr['is_sell_order'] == 'yes') {
					$returArr['status'] = 'open';
				}
				if ($valueArr['status'] == 'FILLED' && $valueArr['is_sell_order'] == 'sold') {
					$returArr['status'] = 'sold';
				}
				$returArr['user_id'] = $valueArr['admin_id'];
				$returArr['created_date'] = $formated_date_time;

			}

			$fullarray[] = $returArr;
		}
		return $fullarray;

	}
}
?>