<?php
/**
 *
 */
class mod_new_candle_chart extends CI_Model {

	public function get_candelstick_data_from_database($global_symbol, $periods, $from_date_object, $to_date_object, $previous_date, $forward_date) {
		$timezone = $this->session->userdata('timezone');
		$this->mongo_db->where(array('coin' => $global_symbol, 'periods' => $periods));

		if ($from_date_object && $to_date_object) {

			$this->mongo_db->where_gte('timestampDate', $from_date_object);

			$this->mongo_db->where_lte('timestampDate', $to_date_object);

		}

		if ($previous_date != '') {

			$previous_date = $previous_date / 1000;

			$previous_date = date("Y-m-d H:i:s", $previous_date);

			$previous_date_date_mongo = $this->mongo_db->converToMongodttime($previous_date);

			$this->mongo_db->where_lte('timestampDate', $previous_date_date_mongo);

		}

		if ($forward_date != '') {

			$forward_date = $forward_date / 1000;

			$forward_date = date("Y-m-d H:i:s", $forward_date);

			$forward_date_date_mongo = $this->mongo_db->converToMongodttime($forward_date);

			$this->mongo_db->where_gt('timestampDate', $forward_date_date_mongo);

			$this->mongo_db->sort(array('timestampDate' => 1)); //ASC/DESC

		} else {

			$this->mongo_db->sort(array('timestampDate' => -1)); //ASC/DESC

		}

		$this->mongo_db->limit(336);

		$responseArr = $this->mongo_db->get('market_chart_fifteen_minutes');

		$final_arr = array();

		foreach ($responseArr as $val_arr) {

			$final_arr[] = array(

				'_id' => $myText = (string) $val_arr['_id'],

				'timestampDate' => $val_arr['timestampDate'],

				'close' => $val_arr['close'],

				'open' => $val_arr['open'],

				'high' => $val_arr['high'],

				'low' => $val_arr['low'],

				'volume' => $val_arr['volume'],

				'openTime' => $val_arr['openTime'],

				'closeTime' => $val_arr['closeTime'],

				'coin' => $val_arr['coin'],

				'candel_status' => $val_arr['candel_status'],

				'candle_type' => $val_arr['candle_type'],

				'openTime_human_readible' => $val_arr['openTime_human_readible'],

				'closeTime_human_readible' => $val_arr['closeTime_human_readible'],

				'demand_base_candel' => $val_arr['demand_base_candel'],

				'supply_base_candel' => $val_arr['supply_base_candel'],

				'global_swing_status' => $val_arr['global_swing_status'],

				'global_swing_parent_status' => $val_arr['global_swing_parent_status'],

				'rejected_candle' => $val_arr['rejected_candle'],

			);

		}

		if ($forward_date == '') {

			$final_arr = array_reverse($final_arr);

		}

		return $final_arr;

	}/** End of get_candelstick_data_from_database **/

	public function get_market_history_for_candel($symbol) {

		$pipeline = array(

			'$group' => array('_id' => '$hour', 'volume' => array('$sum' => '$volume'),

				'type' => array('$first' => '$type'),

				'coin' => array('$first' => '$coin'),

				'timestamp' => array('$first' => '$timestamp'),

				'price' => array('$first' => '$price'),

				'hour' => array('$first' => '$hour'),

				'hour_timestamp' => array('$first' => '$hour_timestamp'),

			),

		);

		$project = array(

			'$project' => array(

				"_id" => 1,

				"price" => 1,

				"volume" => 1,

				"type" => 1,

				"coin" => 1,

				'timestamp' => 1,

				'hour' => 1,

				'hour_timestamp' => 1,

			),

		);

		$match = array(

			'$match' => array(

				'type' => 'ask',

				'coin' => $symbol,

			),

		);

		$sort = array('$sort' => array('hour' => -1));

		$limit = array('$limit' => 1000);

		$connect = $this->mongo_db->customQuery();

		$market_history_Arr_ask = $connect->market_trade_hourly_history->aggregate(array($project, $match, $pipeline, $sort, $limit));

		$market_history_Arr_ask = iterator_to_array($market_history_Arr_ask);

		$ask_price_volume_arr = array();

		if (count($market_history_Arr_ask) > 0) {

			foreach ($market_history_Arr_ask as $key => $value) {

				$ask_price_volume_arr[$value['price']] = $value['volume'];

			}

		}

		$match = array(

			'$match' => array(

				'type' => 'bid',

				'coin' => $symbol,

			),

		);

		$market_history_Arr_bit = $connect->market_trade_hourly_history->aggregate(array($project, $match, $pipeline, $sort, $limit));

		$market_history_Arr_bit = iterator_to_array($market_history_Arr_bit);

		$bid_volume_arr = array();

		$total_volume = array();

		if (count($market_history_Arr_bit) > 0) {

			foreach ($market_history_Arr_bit as $key => $value) {

				$total_volume[$value['hour']] = $ask_volume_arr[$value['hour']] + $value['volume'];

				$bid_volume_arr[$value['hour']] = $value['volume'];

			}

		}

		$response_arr['bid_volume_arr'] = array_reverse($bid_volume_arr);

		$response_arr['ask_volume_arr'] = array_reverse($ask_volume_arr);

		$response_arr['total_volume_arr'] = array_reverse($total_volume);

		$max_volumer = max($total_volume);

		return $response_arr;

	} //End of get_market_history_for_candel

	public function get_candle_price_volume_detail($symbol, $start_date, $end_date, $unit_value) {

		$bid_arr_volume = $this->get_bid_price_volume($symbol, $start_date, $end_date);

		$ask_arr_volume = $this->get_ask_price_volume($symbol, $start_date, $end_date);

		$total_volume_arr = array();

		$total_volume = 0;

		if (count($ask_arr_volume) > 0) {

			foreach ($ask_arr_volume as $price => $valume) {

				$total_volume_arr[$price] = $bid_arr_volume[$price] + $valume;

			}

		}

		foreach ($bid_arr_volume as $bid_price => $bid_volume) {

			if (!array_key_exists($bid_price, $total_volume_arr)) {

				$total_volume_arr[$bid_price] = $bid_volume;

			}

		}

		$max_volume = max($total_volume_arr);

		$retur_data['bid_arr_volume'] = $bid_arr_volume;

		$retur_data['ask_arr_volume'] = $ask_arr_volume;

		$retur_data['total_volume_arr'] = $total_volume_arr;

		$retur_data['max_volume'] = $max_volume;

		$retur_data['unit_value'] = $unit_value;

		return $retur_data;

	}/** End of get_candle_price_volume_detail **/

	public function get_hour_volume_array_detail($symbol, $start_date, $end_date) {

		$ask_arr_volume_hourly = $this->get_ask_price_volume_for_hour($symbol, $start_date, $end_date, 'ask');

		$bid_arr_volume_hourly = $this->get_ask_price_volume_for_hour($symbol, $start_date, $end_date, 'bid');

		$total_volume_arr = array();

		$total_volume = 0;

		if (count($ask_arr_volume_hourly) > 0) {

			foreach ($ask_arr_volume_hourly as $date => $valume) {

				$total_volume_arr[$date] = $bid_arr_volume_hourly[$date] + $valume;

			}

		}

		foreach ($bid_arr_volume_hourly as $bid_date => $bid_volume) {

			if (!array_key_exists($bid_date, $total_volume_arr)) {

				$total_volume_arr[$bid_date] = $bid_volume;

			}

		}

		$max_volume = max($total_volume_arr);

		$retur_data['bid_hour_arr_volume'] = $bid_arr_volume_hourly;

		$retur_data['ask_hour_arr_volume'] = $ask_arr_volume_hourly;

		$retur_data['total_hour_volume_arr'] = $total_volume_arr;

		$retur_data['max_volume_hourly'] = $max_volume;

		return $retur_data;

	} /*** End of get_hour_volume_array_detail ***/

	public function get_ask_price_volume_for_hour($symbol, $start_date, $end_date, $type) {

		$connect = $this->mongo_db->customQuery();

		$pipeline = array(

			'$group' => array('_id' => '$time',

				'bid_quantity' => array('$sum' => '$bid_quantity'),

				'ask_quantity' => array('$sum' => '$ask_quantity'),

				'coin' => array('$first' => '$coin'),

				'time' => array('$first' => '$time'),

			),

		);

		$project = array(

			'$project' => array(

				"_id" => 1,

				"bid_quantity" => 1,

				"ask_quantity" => 1,

				"coin" => 1,

				'time' => 1,

			),

		);

		$match = array(

			'$match' => array(

				'coin' => $symbol,

				'time' => array('$gte' => strtotime($start_date), '$lte' => strtotime($end_date)),

			),

		);

		$sort = array('$sort' => array('time' => 1));

		$limit = array('$limit' => 1000);

		$connect = $this->mongo_db->customQuery();

		$market_history_Arr_ask = $connect->fifteen_min_market_history->aggregate(array($project, $match, $pipeline, $sort, $limit));

		$market_history_Arr_ask = iterator_to_array($market_history_Arr_ask);

		$ask_volume_arr = array();
		$bid_volume_arr = array();

		if (count($market_history_Arr_ask) > 0) {

			foreach ($market_history_Arr_ask as $key => $value) {

				$bid_volume_arr[$value['time']] = $value['bid_quantity'];
				$ask_volume_arr[$value['time']] = $value['ask_quantity'];

			}

		}

		if ($type == 'bid') {
			return $bid_volume_arr;
		} else if ($type == 'ask') {
			return $ask_volume_arr;
		}

	}/** End of get_ask_price_volume_for_hour **/

	public function get_ask_price_volume($symbol, $start_date, $end_date) {

		$connect = $this->mongo_db->customQuery();

		//$this->mongo_db->where('type', 'ask');

		$this->mongo_db->where('coin', $symbol);

		$this->mongo_db->where_gte('time', strtotime($start_date));

		$this->mongo_db->where_lt('time', strtotime($end_date));

		$this->mongo_db->sort(array('time' => 'desc'));

		$responseArr = $this->mongo_db->get('fifteen_min_market_history');

		$responseArr = iterator_to_array($responseArr);

		$ask_price_volume_arr = array();

		$full_arr = array();

		if (count($responseArr) > 0) {

			foreach ($responseArr as $value) {

				$ask_price_volume_arr[$value['time']] = $value['ask_quantity'];

			}

		}

		ksort($ask_price_volume_arr);

		return $ask_price_volume_arr;

	}/** End of get_bid_price_volume**/

	public function get_bid_price_volume($symbol, $start_date, $end_date) {
		$connect = $this->mongo_db->customQuery();

		//$this->mongo_db->where('type', 'ask');

		$this->mongo_db->where('coin', $symbol);

		$this->mongo_db->where_gte('time', strtotime($start_date));

		$this->mongo_db->where_lt('time', strtotime($end_date));

		$this->mongo_db->sort(array('time' => 'desc'));

		$responseArr = $this->mongo_db->get('fifteen_min_market_history');

		$responseArr = iterator_to_array($responseArr);

		$ask_price_volume_arr = array();

		$full_arr = array();

		if (count($responseArr) > 0) {

			foreach ($responseArr as $value) {

				$ask_price_volume_arr[$value['time']] = $value['bid_quantity'];

			}

		}

		ksort($ask_price_volume_arr);

		return $ask_price_volume_arr;

	}/** End of get_bid_price_volume**/

	public function get_date_range_for_history($symbol) {

		$connect = $this->mongo_db->customQuery();

		$pipeline = array(

			'$group' => array('_id' => '$time',

				'bid_quantity' => array('$sum' => '$bid_quantity'),

				'ask_quantity' => array('$sum' => '$ask_quantity'),

				'coin' => array('$first' => '$coin'),

				'time' => array('$first' => '$time'),

			),

		);

		$project = array(

			'$project' => array(

				"_id" => 1,

				"bid_quantity" => 1,

				"ask_quantity" => 1,

				"coin" => 1,

				'time' => 1,

			),

		);

		$match = array(

			'$match' => array(

				'coin' => $symbol,
			),

		);

		$sort = array('$sort' => array('time' => -1));

		$limit = array('$limit' => 8);

		$connect = $this->mongo_db->customQuery();

		$market_history_Arr_ask = $connect->fifteen_min_market_history->aggregate(array($project, $match, $pipeline, $sort, $limit));

		$market_history_Arr_ask = iterator_to_array($market_history_Arr_ask);

		$var_total_hour_array = array();

		$ask_volume_arr = array();

		if (count($market_history_Arr_ask) > 0) {

			foreach ($market_history_Arr_ask as $key => $value) {

				$ask_volume_arr[$value['time']] = $value['quantity'];

				array_push($var_total_hour_array, $value['time']);

			}

		}

		return $var_total_hour_array;

	}/** End of get_date_range_for_history**/

	public function delete_data_from_data_base() {

		$removeTime = date('Y-m-d G:i:s', strtotime('-1 hour', strtotime(date("Y-m-d G:i:s"))));

		$orig_date = new DateTime($removeTime);

		$orig_date = $orig_date->getTimestamp();

		$created_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

		$db = $this->mongo_db->customQuery();

		///////////////////////////////////////////////////////////////

		$delectmarket_prices = $db->market_prices->deleteMany(array('created_date' => array('$lte' => $created_date)));

		$delectmarket_trades = $db->market_trades->deleteMany(array('created_date' => array('$lte' => $created_date)));

	} //delete_data_from_data_base

	public function update_candel_for_formul_values($data_arr, $global_symbol) {

		$response = 'no data found';

		$db = $this->mongo_db->customQuery();

		if (count($data_arr) > 0) {

			foreach ($data_arr as $arr) {

				$where = array('openTime_human_readible' => $arr->openTime_human_readible, 'coin' => $global_symbol);

				$set = array('$set' => array('candel_status' => $arr->candel_status, 'candle_type' => $arr->candle_type, 'ask_volume' => (float) $arr->ask_volume, 'bid_volume' => (float) $arr->bid_volume, 'total_volume' => (float) $arr->total_volume));

				$response = $db->market_chart_fifteen_minutes->updateMany($where, $set);

				$response = 'ok';

			}

		}

		return $response;

	} //End o fupdate_candel_for_formul_values

	//get_order_array

	public function get_order_array($symbol, $admin_id, $global_mode) {

		$search_Array = array(

			'symbol' => $symbol,

			'is_sell_order' => 'sold',

			'admin_id' => $admin_id,

			'application_mode' => $global_mode,

		);

		$this->mongo_db->where($search_Array);

		$res = $this->mongo_db->get('buy_orders');

		$final_arr = array();

		$buy_orders_arr = iterator_to_array($res);

		foreach ($buy_orders_arr as $key => $arr) {

			$buy_order_id = $arr['_id'];

			$buy_date = $arr['created_date'];

			if ($arr['buy_date']) {

				$buy_date = $arr['buy_date'];

			}

			$datetime = $buy_date->toDateTime();

			$created_date = $datetime->format(DATE_RSS);

			$market_sold_price = $arr['market_sold_price'];

			$datetime = new DateTime($created_date);

			$datetime->format('Y-m-d H:00:00');

			$formated_date_time = $datetime->format('Y-m-d H:00:00');

			$buy_order_date = $formated_date_time;

			$search['buy_order_id'] = $buy_order_id;

			$this->mongo_db->where($search);

			$res_sold = $this->mongo_db->get('orders');

			$sold_order_arr = iterator_to_array($res_sold);

			foreach ($sold_order_arr as $key => $value) {

				$buy_order_price = $value['purchased_price'];

				$sell_order_price = $value['sell_price'];

				$sell_date = $value['created_date'];

				if ($value['sell_date']) {

					$sell_date = $value['sell_date'];

				}

				$datetime = $sell_date->toDateTime();

				$created_date = $datetime->format(DATE_RSS);

				$datetime = new DateTime($created_date);

				$datetime->format('Y-m-d H:00:00');

				$formated_date_time = $datetime->format('Y-m-d H:00:00');

				$sell_date = $formated_date_time;

				$final_arr[] = array(

					'buy_date' => $buy_order_date,

					'sell_date' => $sell_date,

					'buy_price' => $buy_order_price,

					'sell_price' => $market_sold_price,

				);

			}

		}

		return $final_arr;

	} //end get_order_array

	public function save_task_manager_setting($data, $global_symbol) {

		$this->mongo_db->where(array('coin' => $global_symbol));

		$result_object = $this->mongo_db->get('task_manager_setting');

		$result_arr = iterator_to_array($result_object);

		if (count($result_arr) > 0) {

			$this->mongo_db->set($data);

			//Update data in mongoTable

			$this->mongo_db->where(array('coin' => $global_symbol));

			$this->mongo_db->update('task_manager_setting');

		} else {

			$data['coin'] = $global_symbol;

			$this->mongo_db->insert('task_manager_setting', $data);

		}

		return true;

	} //End of save_task_manager_setting

	public function get_task_manager_setting($global_symbol) {

		$this->mongo_db->where(array('coin' => $global_symbol));

		$res = $this->mongo_db->get('task_manager_setting');

		return iterator_to_array($res);

	} //End of get_task_manager_setting

	public function get_chart_target_zones($global_symbol) {

		$admin_id = $this->session->userdata('admin_id');

		$this->mongo_db->where(array('coin' => $global_symbol));

		$this->mongo_db->limit(5);

		$this->mongo_db->sort(array('_id' => 'desc'));

		$responseArr = $this->mongo_db->get('chart_target_zones');

		$fullarray = array();

		foreach ($responseArr as $valueArr) {

			$start_date = $valueArr['start_date'];

			$end_date = $valueArr['end_date'];

			if (!empty($valueArr)) {

				$returArr['start_value'] = (string) ($valueArr['start_value']);

				$returArr['end_value'] = (string) ($valueArr['end_value']);

				$returArr['unit_value'] = (string) ($valueArr['unit_value']);

				$returArr['start_date'] = (string) json_decode($start_date);

				$returArr['end_date'] = (string) json_decode($end_date);

				$returArr['type'] = $valueArr['type'];

			}

			$fullarray[] = $returArr;

		}

		return $fullarray;

	} //end get_chart_target_zones

	public function calculate_pressure_up_and_down($start_date, $end_date, $coin, $pressure_type) {
		
		/*echo 'start_date'.$start_date; 
		echo "<br />";
		echo 'start_date'.$end_date;
	  	echo "<br />";
		echo 'mongostatrrt Data'.$this->mongo_db->converToMongodttime($start_date); 
		echo "<br />";
		echo 'Mongo End sdaATE'.$this->mongo_db->converToMongodttime($end_date); 
		exit;*/
		
		$this->mongo_db->where_gte('created_date', $this->mongo_db->converToMongodttime($start_date));
		$this->mongo_db->where_lt('created_date', $this->mongo_db->converToMongodttime($end_date));
		$this->mongo_db->where(array('coin' => $coin, 'pressure' => $pressure_type));
		$res = $this->mongo_db->get('order_book_pressure');
		$res_arr = iterator_to_array($res);
		
		
		return $total_pressure = count($res_arr);
	} //End of calculate_pressure_up_and_down

	public function get_coin_unit_value($symbol) {
		$this->db->dbprefix('coins');
		$this->db->select('unit_value');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();
		return $get_arr['unit_value'];
	} //end get_coin_unit_value()

	public function calculate_up_down_wall($coin_symbol, $datetime) {

		$start_date = date("Y-m-d H:i:00", strtotime($datetime));
		$end_date = date("Y-m-d H:i:59", strtotime("+15 minutes", strtotime($datetime)));
		$where['coin'] = $coin_symbol;
		$where['created_date'] = array('$gte' => $this->mongo_db->converToMongodttime($start_date), '$lte' => $this->mongo_db->converToMongodttime($end_date));

		$order_by['created_date'] = -1;
		$this->mongo_db->where($where);
		$this->mongo_db->order_by($order_by);
		$get = $this->mongo_db->get('depth_wall_history');
		$array = iterator_to_array($get);
		$x = 1;
		for ($i = 0; $i < count($array); $i++) {
			if (!empty($array[$i]['current_market_value'])) {
				$current_val = $array[$i]['current_market_value'];
				$ask_wall = $array[$i]['ask_black_wall'];
				$bid_wall = $array[$i]['bid_black_wall'];
				$unit_val = $this->get_coin_unit_value($coin_symbol);

				$bid_diff[] = ($current_val - $bid_wall) / $unit_val;
				$ask_diff[] = ($ask_wall - $current_val) / $unit_val;
				$x++;
			}

		}
		$bid_diff_sum = array_sum($bid_diff);
		$bid = $bid_diff_sum / $x;
		$ask_diff_sum = array_sum($ask_diff);
		$ask = $ask_diff_sum / $x;

		return array("ask_diff" => $ask, 'bid_diff' => $bid);
	}
}
?>