<?php

/**

 *

 */

class mod_custom_script extends CI_Model {

	function __construct() {

		parent::__construct();
		$this->load->model('admin/mod_login');
		$this->load->model('admin/mod_users');
		$this->load->model('admin/mod_dashboard');
		$this->load->model('admin/mod_market');
		$this->load->model('admin/mod_coins');
		$this->load->model('admin/mod_buy_orders');
		$this->load->model('admin/mod_candel');
		$this->load->model('admin/mod_realtime_candle_socket');
		$this->load->model('admin/mod_box_trigger_3');
		$this->load->model('admin/mod_barrier_trigger');
		$this->load->model('admin/mod_chart3');
		$this->load->model('admin/mod_custom_script');

	}

	public function insert_script($barrier_value, $created_date, $coin_symbol) {

		$created_date22 = $this->mongo_db->converToMongodttime($created_date);
		$new_Date222 = $created_date;
		$search_arr1['coin'] = $coin_symbol;
		$search_arr1['market_value'] = (float) $barrier_value;
		$search_arr1['time'] = array('$lte' => $created_date22);
		$this->mongo_db->where($search_arr1);
		$this->mongo_db->order_by(array('time' => -1));
		$this->mongo_db->limit(1);
		$get_arr = $this->mongo_db->get('market_price_history');
		$price_arr = iterator_to_array($get_arr);
		if (!empty($price_arr)) {
			$price_time = $price_arr[0]['time'];

			$new_Date = $price_time->toDateTime()->format("Y-m-d H:i:s");
			//////////////////////////////////////////////////////////////////
			$coin_meta = $this->get_coin_meta($coin_symbol, $new_Date);

			$res = array(
				'coin' => $coin_symbol,
				'barrier_value' => $barrier_value,
				'barrier_creation_time' => $new_Date222,
				'market_value_time' => $new_Date,
				'black_wall_pressure' => $coin_meta['black_wall_pressure'],
				'yellow_wall_pressure' => $coin_meta['yellow_wall_pressure'],
				'depth_pressure' => $coin_meta['pressure_diff'],
				'bid_contracts' => $coin_meta['bid_contracts'],
				'bid_percentage' => $coin_meta['bid_percentage'],
				'ask_contract' => $coin_meta['ask_contract'],
				'ask_percentage' => $coin_meta['ask_percentage'],
				'great_wall_quantity' => $coin_meta['great_wall_quantity'],
				'great_wall' => $coin_meta['great_wall'],
				'seven_level_depth' => $coin_meta['seven_level_depth'],
				'created_date' => $this->mongo_db->converToMongodttime($new_Date222),
			);
			$this->mongo_db->insert('barrier_test_collection', $res);
			$message = '';
			foreach ($res as $key => $value) {
				$message .= $key . '  -->' . $value . '<br>';
			}
			echo "Inserrted";
			echo "<pre>";
			mail('khan.waqar278@gmail.com', 'Insert Script Called For Coin' . $coin_symbol, $message);
			print_r($res);
		} else {
			echo "Price Not Found";
		}
	}
	public function get_coin_meta($coin_symbol, $datetime) {

		//=========================================

		// ======= Variable Listing ==============

		//=========================================

		/*$coin_symbol = "NCASHBTC";

			$datetime = "2018-09-14 12:00:00";

		*/

		//=================================== Buy Sell Array Creation ===================================//

		$biggest_value2 = $this->mod_coins->get_coin_base_order($coin_symbol);

		$biggest_value = $this->mod_coins->get_coin_base_history($coin_symbol);

		$final_array = $this->remainder_group($coin_symbol, $datetime);

		$ask_array = $final_array['asks'];

		$offset = $this->mod_coins->get_coin_offset_value($coin_symbol);

		$chunks = array_chunk($ask_array, $offset);

		$sumArray = array();

		$new_ask_arr = array();

		foreach ($chunks as $key => $valve) {

			$price = $valve[0]['price'];

			$sumArray = array();

			$sell_quantity = 0;

			$depth_buy_quantity = 0;

			$depth_sell_quantity = 0;

			$buy_quantity = 0;

			foreach ($valve as $k => $val) {

				$sell_quantity += $val['sell_quantity'];

				$sumArray['sell_quantity'] = $sell_quantity;

				$depth_buy_quantity += $val['depth_buy_quantity'];

				$sumArray['depth_buy_quantity'] = $depth_buy_quantity;

				$depth_sell_quantity += $val['depth_sell_quantity'];

				$sumArray['depth_sell_quantity'] = $depth_sell_quantity;

				$buy_quantity += $val['buy_quantity'];

				$sumArray['buy_quantity'] = $buy_quantity;

				$sumArray['price'] = $price;

				$sumArray['coin'] = $val['coin'];

				$sumArray['type'] = $val['type'];

				$sumArray['price'] = $price;

			}

			$new_ask_arr[] = $sumArray;

		}

		$market_buy_depth_arr = $new_ask_arr;

		array_multisort(array_column($market_buy_depth_arr, "price"), SORT_ASC, $market_buy_depth_arr);

		$bid_array = $final_array['bid'];

		$chunks = array_chunk($bid_array, $offset);

		$sumArray = array();

		$new_bid_arr = array();

		foreach ($chunks as $key => $valve) {

			$price = $valve[$offset - 1]['price'];

			$sumArray = array();

			$sell_quantity = 0;

			$depth_buy_quantity = 0;

			$depth_sell_quantity = 0;

			$buy_quantity = 0;

			foreach ($valve as $k => $val) {

				$sell_quantity += $val['sell_quantity'];

				$sumArray['sell_quantity'] = $sell_quantity;

				$depth_buy_quantity += $val['depth_buy_quantity'];

				$sumArray['depth_buy_quantity'] = $depth_buy_quantity;

				$depth_sell_quantity += $val['depth_sell_quantity'];

				$sumArray['depth_sell_quantity'] = $depth_sell_quantity;

				$buy_quantity += $val['buy_quantity'];

				$sumArray['buy_quantity'] = $buy_quantity;

				$sumArray['price'] = $price;

				$sumArray['coin'] = $val['coin'];

				$sumArray['type'] = $val['type'];

				$sumArray['price'] = $price;

			}

			$new_bid_arr[] = $sumArray;

		}

		$market_sell_depth_arr = $new_bid_arr;

		//==================================End Buy Sell Array Creation ===================================//

		//===============================Wall Calculation==============================//

		$temp_price_buy = $this->calculate_wall($coin_symbol, $market_buy_depth_arr, 'buy', $biggest_value2);

		$temp_price_buy22 = $this->calculate_yellow_wall($coin_symbol, $market_buy_depth_arr, 'buy', $biggest_value2);

		$temp_price_sell = $this->calculate_wall($coin_symbol, $market_sell_depth_arr, 'sell', $biggest_value2);

		$temp_price_sell22 = $this->calculate_yellow_wall($coin_symbol, $market_sell_depth_arr, 'sell', $biggest_value2);

		array_multisort(array_column($market_buy_depth_arr, "price"), SORT_ASC, $market_buy_depth_arr);

		$bid_diff = $key = array_search($temp_price_sell, array_column($market_sell_depth_arr, 'price'));

		$ask_diff = $key = array_search($temp_price_buy, array_column($market_buy_depth_arr, 'price'));

		$y_bid_diff = $key = array_search($temp_price_sell22, array_column($market_sell_depth_arr, 'price'));

		$y_ask_diff = $key = array_search($temp_price_buy22, array_column($market_buy_depth_arr, 'price'));

		if ($bid_diff >= $ask_diff) {

			$black_type = "negitive";

		} else {

			$black_type = 'positive';

		}

		$black_wall_difference = $ask_diff - $bid_diff;

		if ($y_bid_diff >= $y_ask_diff) {

			$yellow_type = 'negitive';

		} else {

			$yellow_type = 'positive';

		}

		$yellow_wall_differnce = $y_ask_diff - $y_bid_diff;

		$current_market_value = $final_array['market_value'];

		//===============================End Wall Calculation==============================//

		//===============================Contracts Info==============================//

		$bid_contract_info = $this->get_bid_contract_info($coin_symbol, $datetime);

		$bid_contract_avg = $bid_contract_info['avg'];

		if (is_nan($bid_contract_avg)) {

			$bid_contract_avg = 0;

		}

		$bid_contract_per = $bid_contract_info['per'];

		$ask_contract_info = $this->get_ask_contract_info($coin_symbol, $datetime);

		$ask_contract_avg = $ask_contract_info['avg'];

		if (is_nan($ask_contract_avg)) {

			$ask_contract_avg = 0;

		}

		$ask_contract_per = $ask_contract_info['per'];

		//==============================End Contracts Info==============================//

		//===============================Pressure Calculation==============================//

		$up_pressure = $this->calculate_pressure_up_and_down($datetime, $coin_symbol, 'up');

		$down_pressure = $this->calculate_pressure_up_and_down($datetime, $coin_symbol, 'down');

		/*$up_pressure = ($up_pressure / 5);

		$down_pressure = ($down_pressure / 5)*/;

		if ($up_pressure > $down_pressure) {

			$pressure_type = 'positive';

		} else {

			$pressure_type = 'negitive';

		}

		$pressure_difference = $up_pressure - $down_pressure;

		//===============================End Pressure Calculation==============================//

		//===============================Big Wall Calculation==============================//

		$pressure_wall = $this->calculate_big_wall($market_sell_depth_arr, $market_buy_depth_arr);

		$ask_max = $pressure_wall['up_big_wall'];

		$bid_max = $pressure_wall['down_big_wall'];

		$ask_price = $pressure_wall['up_big_price'];

		$bid_price = $pressure_wall['down_big_price'];

		$max_price = $pressure_wall['great_wall_price'];

		$max = $pressure_wall['great_wall_quantity'];

		$pressure = $pressure_wall['great_wall'];

		$color = $pressure_wall['great_wall_color'];

		//===============================End Big Wall Calculation==============================//

		//=================================7 Level Depth=======================================//

		$levels = $this->calculate_bid_ask_levels($market_sell_depth_arr, $market_buy_depth_arr);

		$val = $levels['new_x'];

		$t = $levels['p'];

		//==============================End 7 Level Depth=======================================//

		//===============================Buyers Calculation==============================//

		$current = $this->get_current_candle_volume($coin_symbol, $datetime);

		$curr_bid_per = $current['bid_per'];

		if (is_nan($curr_bid_per)) {

			$curr_bid_per = 0;

		}

		$curr_ask_per = $current['ask_per'];

		if (is_nan($curr_ask_per)) {

			$curr_ask_per = 0;

		}

		$curr_bid = $current['bid_vol'];

		if (is_nan($curr_bid)) {

			$curr_bid = 0;

		}

		$curr_ask = $current['ask_vol'];

		if (is_nan($curr_ask)) {

			$curr_ask = 0;

		}

		if ($curr_bid > $curr_ask) {
			if ($curr_ask == 0) {$curr_ask = 1;}
			$divide = $curr_bid / $curr_ask;
			$trade_type = 'blue';

		} else if ($curr_ask > $curr_bid) {
			if ($curr_bid == 0) {$curr_bid = 1;}
			$divide = $curr_ask / $curr_bid;
			$trade_type = 'red';

		}
		//===============================End Buyers Calculation==============================//

		$array_to_ins = array(

			'coin' => $coin_symbol,

			'current_market_value' => (float) $current_market_value,

			'ask_black_wall' => (float) $temp_price_buy,

			'ask_yellow_wall' => (float) $temp_price_buy22,

			'bid_black_wall' => (float) $temp_price_sell,

			'bid_yellow_wall' => (float) $temp_price_sell22,

			'black_wall_type' => $black_type,

			'black_wall_pressure' => $black_wall_difference,

			'yellow_wall_type' => $yellow_type,

			'yellow_wall_pressure' => $yellow_wall_differnce,

			'up_pressure' => (float) $up_pressure,

			'down_pressure' => (float) $down_pressure,

			'pressure_type' => $pressure_type,

			'pressure_diff' => (float) $pressure_difference,

			'bid_contracts' => $bid_contract_avg,

			'bid_percentage' => $bid_contract_per,

			'ask_contract' => $ask_contract_avg,

			'ask_percentage' => $ask_contract_per,

			'buyers' => $curr_ask,

			'sellers' => $curr_bid,

			'buyers_percentage' => $curr_ask_per,

			'sellers_percentage' => $curr_bid_per,

			'sellers_buyers_per' => $divide,

			'trade_type' => $trade_type,

			'up_big_wall' => $ask_max,

			'down_big_wall' => $bid_max,

			'up_big_price' => $ask_price,

			'down_big_price' => $bid_price,

			'great_wall_price' => $max_price,

			'great_wall_quantity' => $max,

			'great_wall' => $pressure,

			'great_wall_color' => $color,

			'seven_level_depth' => $val,

			'seven_level_type' => $t,

			'modified_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),

		);

		/*	echo "<pre>";

			print_r($array_to_ins);

			exit;

		*/

		return $array_to_ins;

	}

	public function get_current_candle_volume($symbol, $datetime) {

		$new_datetime = date("Y-m-d H:00:00", strtotime($datetime));

		$orig_date22 = new DateTime($new_datetime);

		$orig_date22 = $orig_date22->getTimestamp();

		$start_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);

		$datetime2 = date("Y-m-d H:i:s", strtotime($datetime));

		$orig_date322 = new DateTime($datetime2);

		$orig_date322 = $orig_date322->getTimestamp();

		$end_date = new MongoDB\BSON\UTCDateTime($orig_date322 * 1000);

		$search['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);

		$search['coin'] = $symbol;

		$search['maker'] = 'true';

		$this->mongo_db->where($search);

		$get = $this->mongo_db->get("market_trade_history");

		$res = iterator_to_array($get);

		$bid_vol = 0;

		foreach ($res as $key => $value) {

			$vol = $value['quantity'];

			$bid_vol += $vol;

		}

		$search['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);

		$search['coin'] = $symbol;

		$search['maker'] = 'false';

		$this->mongo_db->where($search);

		$get = $this->mongo_db->get("market_trade_history");

		$res = iterator_to_array($get);

		$ask_vol = 0;

		foreach ($res as $key => $value) {

			$vol = $value['quantity'];

			$ask_vol += $vol;

		}

		$total_volume = $bid_vol + $ask_vol;

		$bid_per = ($bid_vol * 100) / $total_volume;

		$ask_per = ($ask_vol * 100) / $total_volume;

		return array(

			'bid_per' => $bid_per,

			'ask_per' => $ask_per,

			'bid_vol' => $bid_vol,

			'ask_vol' => $ask_vol,

		);

	}

	public function remainder_group($symbol, $datetime) {

		//echo $datetime;

		//$symbol = $this->session->userdata('global_symbol');

		$datetime2 = $this->mongo_db->converToMongodttime($datetime);

		$market_value = $this->mod_chart3->get_historical_market_value($symbol, $datetime);

		$valasss = $market_value;

		$offset = $this->mod_coins->get_coin_offset_value($symbol);

		$limit = 7 * $offset;

		$fullarray = array();

		$final_array = array();

		$coin_value = $this->mod_coins->get_coin_unit_value($symbol);

		$mvalue = num($market_value);

		$divider = num($offset * $coin_value);

		$remainder = fmod($mvalue, $divider);

		$remainder_tes = num($remainder);

		$resultant = num($divider - $remainder);

		if ($offset != 1) {

			$num_Val = (float) ($mvalue + ($resultant));

			$val = $num_Val;

		} else {

			$num_Val = (float) $mvalue;

			$val = $num_Val + $coin_value;

		}

		if ($remainder_tes != '0.00000000' && $resultant == '0.00000000') {

			$val = $num_Val + $remainder;

		}

		for ($i = 0; $i < $limit; $i++) {

			$depth_buy_quantity1 = 0;

			$depth_buy_quantity2 = 0;

			$depth_buy_quantity3 = 0;

			$depth_buy_quantity4 = 0;

			if ($i == 0) {

				if ($offset != 1 && $resultant != '0.00000000') {$num_Val = $num_Val - $divider;}

			}

			if ($i != 0) {

				$num_Val += $coin_value;

			}

			$num_Val = trim($num_Val);

			$num_Val = (float) $num_Val;

			if ($num_Val < 0) {

				continue;

			}

			$this->mongo_db->where(array('type' => 'ask', 'coin' => $symbol, 'price' => $num_Val, 'modified_date' => array('$gte' => $datetime2)));

			$this->mongo_db->limit(1);

			//$this->mongo_db->order_by(array('_id' => 1));

			$depth_responseArr5 = $this->mongo_db->get('market_depth_history');

			foreach ($depth_responseArr5 as $depth_valueArr5) {

				if (!empty($depth_valueArr5)) {

					$depth_buy_quantity1 += $depth_valueArr5['quantity'];

				}

			}

			$this->mongo_db->where(array('type' => 'bid', 'coin' => $symbol, 'price' => $num_Val, 'modified_date' => array('$gte' => $datetime2)));

			$this->mongo_db->limit(1);

			//$this->mongo_db->order_by(array('_id' => 1));

			$depth_responseArr6 = $this->mongo_db->get('market_depth_history');

			foreach ($depth_responseArr6 as $depth_valueArr6) {

				if (!empty($depth_valueArr6)) {

					$depth_buy_quantity2 += $depth_valueArr6['quantity'];

				}

			}

			$returArr['price'] = $num_Val;

			$returArr['quantity'] = $depth_buy_quantity1;

			$returArr['type'] = 'ask';

			$returArr['coin'] = $symbol;

			$returArr['depth_buy_quantity'] = $depth_buy_quantity1;

			$returArr['depth_sell_quantity'] = $depth_buy_quantity2;

			$returArr['buy_quantity'] = $depth_buy_quantity3;

			$returArr['sell_quantity'] = $depth_buy_quantity4;

			$fullarray[] = $returArr;

		}

		//$val = $market_value;

		$final_array['asks'] = $fullarray;

		$fullarray = array();

		for ($i = 0; $i < $limit; $i++) {

			$depth_buy_quantity5 = 0;

			$depth_buy_quantity6 = 0;

			$depth_buy_quantity7 = 0;

			$depth_buy_quantity8 = 0;

			$val -= $coin_value;

			$val = trim($val);

			$val = (float) $val;

			if ($val < 0) {

				continue;

			}

			$this->mongo_db->where(array('type' => 'ask', 'coin' => $symbol, 'price' => $val, 'modified_date' => array('$gte' => $datetime2)));

			$this->mongo_db->limit(1);

			//$this->mongo_db->order_by(array('_id' => 1));

			$depth_responseArr5 = $this->mongo_db->get('market_depth_history');

			foreach ($depth_responseArr5 as $depth_valueArr5) {

				if (!empty($depth_valueArr5)) {

					$depth_buy_quantity5 += $depth_valueArr5['quantity'];

				}

			}

			$this->mongo_db->where(array('type' => 'bid', 'coin' => $symbol, 'price' => $val, 'modified_date' => array('$gte' => $datetime2)));

			$this->mongo_db->limit(1);

			//$this->mongo_db->order_by(array('_id' => 1));

			$depth_responseArr6 = $this->mongo_db->get('market_depth_history');

			foreach ($depth_responseArr6 as $depth_valueArr6) {

				if (!empty($depth_valueArr6)) {

					$depth_buy_quantity6 += $depth_valueArr6['quantity'];

				}

			}

			$returArr['price'] = $val;

			$returArr['quantity'] = $depth_buy_quantity5;

			$returArr['type'] = 'bid';

			$returArr['coin'] = $symbol;

			$returArr['depth_buy_quantity'] = $depth_buy_quantity5;

			$returArr['depth_sell_quantity'] = $depth_buy_quantity6;

			$returArr['buy_quantity'] = $depth_buy_quantity7;

			$returArr['sell_quantity'] = $depth_buy_quantity8;

			$fullarray[] = $returArr;

		}

		$final_array['bid'] = $fullarray;

		$final_array['market_value'] = $market_value;

		return $final_array;

	}

	public function get_contract_info($symbol, $datetime) {

		$info = $this->mod_coins->get_coin_contract_value($symbol);

		$contract_per = $info['contract_percentage'];

		$contract_time = $info['contract_time'];

		if ($contract_time == 0) {

			$contract_time = 2;

		}

		if ($contract_per == 0) {

			$contract_per = 10;

		}

		$nowtime = date('Y-m-d H:i:s', strtotime('-' . $contract_time . 'minutes', strtotime($datetime)));

		$search_array['coin'] = $symbol;

		$search_array['created_date'] = array('$gte' => $this->mongo_db->converToMongodttime($nowtime), '$lte' => $this->mongo_db->converToMongodttime($datetime));

		$this->mongo_db->where($search_array);

		$this->mongo_db->order_by(array('created_date' => -1));

		$get_arr = $this->mongo_db->get('market_trade_history');

		$quantity = array();

		foreach ($get_arr as $key => $value) {

			if (!empty($value)) {

				$quantity[] = $value['quantity'];

			}

		}

		rsort($quantity);

		$q_sum = 0;

		$index = round((count($quantity) / 100) * $contract_per);

		for ($i = 0; $i < $index; $i++) {

			$q_sum += $quantity[$i];

		}

		for ($i = 0; $i < count($quantity); $i++) {

			$t_sum += $quantity[$i];

		}

		$q_avg = $q_sum / $index;

		$cal_per = ($q_sum / $t_sum) * 100;

		$ret_arr['avg'] = round($q_avg);

		$ret_arr['per'] = round($cal_per);

		return $ret_arr;

	}

	public function get_bid_contract_info($symbol, $date) {

		$info = $this->mod_coins->get_coin_contract_value($symbol);

		$contract_per = $info['contract_percentage'];

		$contract_time = $info['contract_time'];

		if ($contract_time == 0) {

			$contract_time = 2;

		}

		if ($contract_per == 0) {

			$contract_per = 10;

		}

		$nowtime = date('Y-m-d H:i:s', strtotime('-' . $contract_time . 'minutes', strtotime($date)));

		$search_array['coin'] = $symbol;

		$search_array['created_date'] = array('$gte' => $this->mongo_db->converToMongodttime($nowtime), '$lte' => $this->mongo_db->converToMongodttime($date));

		$this->mongo_db->where($search_array);

		$this->mongo_db->order_by(array('created_date' => -1));
		$get_arr = $this->mongo_db->get('market_trade_history');

		$get_arr_1 = iterator_to_array($get_arr);

		array_multisort(array_column($get_arr_1, "quantity"), SORT_DESC, $get_arr_1);

		$index = round((count($get_arr_1) / 100) * $contract_per);

		for ($i = 0; $i < $index; $i++) {

			if ($get_arr_1[$i]['maker'] == 'true') {

				$q_sum += $get_arr_1[$i]['quantity'];

			}

		}

		$q2 = 0;

		$max_price = 0;

		for ($i = 0; $i < $index; $i++) {

			if ($get_arr_1[$i]['maker'] == 'true') {

				$q = $get_arr_1[$i]['quantity'];

				if ($q2 < $q) {

					$q2 = $q;

					$max_price = $get_arr_1[$i]['price'];

				}

			}

		}

		for ($i = 0; $i < count($get_arr_1); $i++) {

			$t_sum += $get_arr_1[$i]['quantity'];

		}

		$q_avg = $q_sum / $index;

		$cal_per = ($q_sum / $t_sum) * 100;

		$ret_arr['avg'] = round($q_avg);

		$ret_arr['per'] = round($cal_per);

		$ret_arr['max'] = num($max_price);

		return $ret_arr;

	}

	public function get_ask_contract_info($symbol, $date) {

		$info = $this->mod_coins->get_coin_contract_value($symbol);

		$contract_per = $info['contract_percentage'];

		$contract_time = $info['contract_time'];

		if ($contract_time == 0) {

			$contract_time = 2;

		}

		if ($contract_per == 0) {

			$contract_per = 10;

		}

		$nowtime = date('Y-m-d H:i:s', strtotime('-' . $contract_time . 'minutes', strtotime($date)));

		$search_array['coin'] = $symbol;

		$search_array['created_date'] = array('$gte' => $this->mongo_db->converToMongodttime($nowtime), '$lte' => $this->mongo_db->converToMongodttime($date));

		$this->mongo_db->where($search_array);

		$this->mongo_db->order_by(array('created_date' => -1));

		$get_arr = $this->mongo_db->get('market_trade_history');

		$get_arr_1 = iterator_to_array($get_arr);

		array_multisort(array_column($get_arr_1, "quantity"), SORT_DESC, $get_arr_1);

		$index = round((count($get_arr_1) / 100) * $contract_per);

		$index = round((count($get_arr_1) / 100) * $contract_per);

		for ($i = 0; $i < $index; $i++) {

			if ($get_arr_1[$i]['maker'] == 'false') {

				$q_sum += $get_arr_1[$i]['quantity'];

			}

		}

		$q2 = 0;

		$max_price = 0;

		for ($i = 0; $i < $index; $i++) {

			if ($get_arr_1[$i]['maker'] == 'false') {

				$q = $get_arr_1[$i]['quantity'];

				if ($q2 < $q) {

					$q2 = $q;

					$max_price = $get_arr_1[$i]['price'];

				}

			}

		}

		for ($i = 0; $i < count($get_arr_1); $i++) {

			$t_sum += $get_arr_1[$i]['quantity'];

		}

		$q_avg = $q_sum / $index;

		$cal_per = ($q_sum / $t_sum) * 100;

		$ret_arr['avg'] = round($q_avg);

		$ret_arr['per'] = round($cal_per);

		$ret_arr['max'] = num($max_price);

		return $ret_arr;

	}

	public function calculate_wall($coin_symbol, $depth_arr, $type, $biggest_value) {

		if ($type == 'sell') {

			array_multisort(array_column($depth_arr, "price"), SORT_ASC, $depth_arr);

		} elseif ($type == 'buy') {

			array_multisort(array_column($depth_arr, "price"), SORT_DESC, $depth_arr);

		}

		$setting = $this->mod_coins->get_coin_depth_wall_setting($coin_symbol);

		$temp_price = 0.00;

		///////////////////////////////////////////////////////////////////////////////////

		if ($setting == 'percentage') {

			$depth_wall_val = $this->mod_coins->get_coin_depth_wall_percentage($coin_symbol);

			if (count($depth_arr) > 0) {

				$buy_depth_wall2 = 0;

				$temp_price = 0;

				$temps = count($depth_arr);

				for ($temp = $temps - 1; $temp >= 0; $temp--) {

					if ($depth_arr[$temp]['type'] == 'bid') {

						$sell_percentage22 = round($depth_arr[$temp]['depth_sell_quantity'] * 100 / $biggest_value);

						if ($sell_percentage22 > $depth_wall_val && $buy_depth_wall2 == 0) {

							$temp_price = num($depth_arr[$temp]['price']);

							$buy_depth_wall2 = 1;

							break;

						}

					} elseif ($depth_arr[$temp]['type'] == 'ask') {

						$buy_percentage22 = round($depth_arr[$temp]['depth_buy_quantity'] * 100 / $biggest_value);

						if ($buy_percentage22 >= $depth_wall_val && $buy_depth_wall2 == 0) {

							$temp_price = num($depth_arr[$temp]['price']);

							$buy_depth_wall2 = 1;

							break;

						}

					}

				}

			}

		} elseif ($setting == 'amount') {

			$depth_wall_val = $this->mod_coins->get_coin_depth_wall_value($coin_symbol);

			if (count($depth_arr) > 0) {

				$quantity_t = 0;

				$buy_depth_wall2 = 0;

				$temp_price = 0;

				$temps = count($depth_arr);

				for ($temp = $temps - 1; $temp >= 0; $temp--) {

					if ($depth_arr[$temp]['type'] == 'bid') {

						$quantity_t += $depth_arr[$temp]['depth_sell_quantity'];

						if ($quantity_t >= $depth_wall_val && $buy_depth_wall2 == 0) {

							$temp_price = num($depth_arr[$temp]['price']);

							$buy_depth_wall2 = 1;

						}

					} elseif ($depth_arr[$temp]['type'] == 'ask') {

						$quantity_t += $depth_arr[$temp]['depth_buy_quantity'];

						if ($quantity_t >= $depth_wall_val && $buy_depth_wall2 == 0) {

							$temp_price = num($depth_arr[$temp]['price']);

							$buy_depth_wall2 = 1;

						}

					}

				}

			}

		}

		return $temp_price;

		///////////////////////////////////////////////////////////////////////////////////

	}

	public function calculate_yellow_wall($coin_symbol, $depth_arr, $type, $biggest_value) {

		if ($type == 'sell') {

			array_multisort(array_column($depth_arr, "price"), SORT_ASC, $depth_arr);

		} elseif ($type == 'buy') {

			array_multisort(array_column($depth_arr, "price"), SORT_DESC, $depth_arr);

		}

		$setting = $this->mod_coins->get_coin_yellow_wall_setting($coin_symbol);

		$temp_price = 0.00;

		///////////////////////////////////////////////////////////////////////////////////

		if ($setting == 'percentage') {

			$depth_wall_val = $this->mod_coins->get_coin_yellow_wall_percentage($coin_symbol);

			if (count($depth_arr) > 0) {

				$buy_depth_wall2 = 0;

				$temp_price = 0;

				$temps = count($depth_arr);

				for ($temp = $temps - 1; $temp >= 0; $temp--) {

					if ($depth_arr[$temp]['type'] == 'bid') {

						$sell_percentage22 = round($depth_arr[$temp]['depth_sell_quantity'] * 100 / $biggest_value);

						if ($sell_percentage22 > $depth_wall_val && $buy_depth_wall2 == 0) {

							$temp_price = num($depth_arr[$temp]['price']);

							$buy_depth_wall2 = 1;

							break;

						}

					} elseif ($depth_arr[$temp]['type'] == 'ask') {

						$buy_percentage22 = round($depth_arr[$temp]['depth_buy_quantity'] * 100 / $biggest_value);

						if ($buy_percentage22 >= $depth_wall_val && $buy_depth_wall2 == 0) {

							$temp_price = num($depth_arr[$temp]['price']);

							$buy_depth_wall2 = 1;

							break;

						}

					}

				}

			}

		} elseif ($setting == 'amount') {

			$depth_wall_val = $this->mod_coins->get_coin_yellow_wall_value($coin_symbol);

			if (count($depth_arr) > 0) {

				$quantity_t = 0;

				$buy_depth_wall2 = 0;

				$temp_price = 0;

				$temps = count($depth_arr);

				for ($temp = $temps - 1; $temp >= 0; $temp--) {

					if ($depth_arr[$temp]['type'] == 'bid') {

						$quantity_t += $depth_arr[$temp]['depth_sell_quantity'];

						if ($quantity_t >= $depth_wall_val && $buy_depth_wall2 == 0) {

							$temp_price = num($depth_arr[$temp]['price']);

							$buy_depth_wall2 = 1;

						}

					} elseif ($depth_arr[$temp]['type'] == 'ask') {

						$quantity_t += $depth_arr[$temp]['depth_buy_quantity'];

						if ($quantity_t >= $depth_wall_val && $buy_depth_wall2 == 0) {

							$temp_price = num($depth_arr[$temp]['price']);

							$buy_depth_wall2 = 1;

						}

					}

				}

			}

		}

		return $temp_price;

		///////////////////////////////////////////////////////////////////////////////////

	}

	public function calculate_big_wall($sell_arr, $buy_arr) {

		$pressure_up = 0;

		$pressure_down = 0;

		array_multisort(array_column($buy_arr, "price"), SORT_ASC, $buy_arr);

		array_multisort(array_column($sell_arr, "price"), SORT_DESC, $sell_arr);

		$bid_max = $sell_arr[0]['depth_sell_quantity'];

		$ask_max = $buy_arr[0]['depth_buy_quantity'];

		$bid_price = $sell_arr[0]['price'];

		$ask_price = $buy_arr[0]['price'];

		for ($i = 0; $i < 5; $i++) {

			if ($bid_max < $sell_arr[$i]['depth_sell_quantity']) {

				$bid_max = $sell_arr[$i]['depth_sell_quantity'];

				$bid_price = $sell_arr[$i]['price'];

			}

			if ($ask_max < $buy_arr[$i]['depth_buy_quantity']) {

				$ask_max = $buy_arr[$i]['depth_buy_quantity'];

				$ask_price = $buy_arr[$i]['price'];

			}

		}

		if ($bid_max > $ask_max) {

			$pressure = 'downside';

			$color = 'blue';

			$max_price = $bid_price;

			$max = $bid_max;

		} elseif ($ask_max > $bid_max) {

			$pressure = 'upside';

			$color = 'red';

			$max_price = $ask_price;

			$max = $ask_max;

		}

		$pressure_arr['up_big_wall'] = $ask_max;

		$pressure_arr['down_big_wall'] = $bid_max;

		$pressure_arr['up_big_price'] = $ask_price;

		$pressure_arr['down_big_price'] = $bid_price;

		$pressure_arr['great_wall_price'] = $max_price;

		$pressure_arr['great_wall_quantity'] = $max;

		$pressure_arr['great_wall'] = $pressure;

		$pressure_arr['great_wall_color'] = $color;

		return $pressure_arr;

	}

	public function calculate_bid_ask_levels($sell_arr, $buy_arr) {

		$pressure_up = 0;

		$pressure_down = 0;

		array_multisort(array_column($buy_arr, "price"), SORT_ASC, $buy_arr);

		array_multisort(array_column($sell_arr, "price"), SORT_DESC, $sell_arr);

		$bid_max = 0;

		$ask_max = 0;

		for ($i = 0; $i < 7; $i++) {

			$bid_max += $sell_arr[$i]['depth_sell_quantity'];

			$ask_max += $buy_arr[$i]['depth_buy_quantity'];

		}

		if ($bid_max > $ask_max) {

			$x = $bid_max / $ask_max;

			$p = 'up';

		} elseif ($ask_max > $bid_max) {

			$x = $ask_max / $bid_max;

			$p = 'down';

			$x = $x * (-1);

		}

		$new_x = $x - 1;

		$new_p = ($new_x / 2) * 100;

		return array('new_x' => number_format($new_x, 2), 'new_p' => $new_p, 'p' => $p);

	}

	public function calculate_pressure_up_and_down($start_date, $coin, $pressure_type) {

		$end_date = date('Y-m-d H:i:s', strtotime("+59 seconds", strtotime($start_date)));

		$this->mongo_db->where_gte('created_date', $this->mongo_db->converToMongodttime($start_date));

		$this->mongo_db->where_lt('created_date', $this->mongo_db->converToMongodttime($end_date));

		$this->mongo_db->where(array('coin' => $coin, 'pressure' => $pressure_type));

		$res = $this->mongo_db->get('order_book_pressure');

		$res_arr = iterator_to_array($res);

		return $total_pressure = count($res_arr);

	} //End calculate_pressure_up_and_down

}

?>