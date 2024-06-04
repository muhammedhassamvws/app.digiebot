<?php
/**
 *
 */
class mod_chart3 extends CI_Model {

	function __construct() {
		# code...
	}

	//get_market_buy_depth_chart
	public function get_market_buy_depth_chart($global_symbol = '') {

		//$global_symbol = $this->session->userdata('global_symbol');

		if (isset($_GET['market_value']) && $_GET['market_value'] != "" && $market_value == "") {

			$market_value = $_GET['market_value'];

		} elseif ($market_value != "") {

			$market_value = $market_value;

		} else {
			//Get Market Prices
			$market_value = $this->get_market_value($global_symbol);
		}

		///////////////////////////////////////
		$db = $this->mongo_db->customQuery();
		$priceAsk = (float) $market_value;
		$pipeline = array(
			array(
				'$project' => array(
					"price" => 1,
					"quantity" => 1,
					"type" => 1,
					"coin" => 1,
					'created_date' => 1,
				),
			),
			array(
				'$match' => array(
					'coin' => $global_symbol,
					'type' => 'ask',
					'price' => array('$gte' => $priceAsk),
				),
			),
			array('$sort' => array('created_date' => -1)),
			array('$group' => array(
				'_id' => array('price' => '$price'),
				'quantity' => array('$first' => '$quantity'),
				'type' => array('$first' => '$type'),
				'coin' => array('$first' => '$coin'),
				'created_date' => array('$first' => '$created_date'),
				'price' => array('$first' => '$price'),
			),
			),
			array('$sort' => array('price' => 1)),
			array('$limit' => 50),
		);

		$allow = array('allowDiskUse' => true);
		$responseArr = $db->market_depth->aggregate($pipeline, $allow);

		$fullarray = array();
		$big_quantity = 0;
		$depth_big_quantity = 0;
		foreach ($responseArr as $valueArr) {

			$returArr = array();

			if (!empty($valueArr)) {
				$datetime = $valueArr['created_date']->toDateTime();
				$created_date = $datetime->format(DATE_RSS);

				$datetime = new DateTime($created_date);
				$datetime->format('Y-m-d g:i:s A');

				//$new_timezone = new DateTimeZone('Asia/Karachi');
				//$datetime->setTimezone($new_timezone);
				$formated_date_time = $datetime->format('Y-m-d g:i:s A');

				$returArr['_id'] = $valueArr['_id'];
				$returArr['price'] = $valueArr['price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['type'] = $valueArr['type'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['created_date'] = $formated_date_time;

				///////////////////////////////////////////////////////
				$depth_price = $valueArr['price'];
				$this->mongo_db->where(array('type' => 'ask', 'coin' => $global_symbol, 'price' => $depth_price));
				$this->mongo_db->limit(1);
				$this->mongo_db->order_by(array('created_date' => -1));
				$depth_responseArr = $this->mongo_db->get('market_depth');

				$depth_buy_quantity = 0;
				foreach ($depth_responseArr as $depth_valueArr) {
					if (!empty($depth_valueArr)) {

						$depth_buy_quantity += $depth_valueArr['quantity'];
					}
				}
				if ($depth_buy_quantity > $depth_big_quantity) {
					$depth_big_quantity = $depth_buy_quantity;
				}

				$returArr['depth_buy_quantity'] = $depth_buy_quantity;
				///////////////////////////////////////////////////////

				///////////////////////////////////////////////////////
				$depth_price = $valueArr['price'];
				$this->mongo_db->where(array('type' => 'bid', 'coin' => $global_symbol, 'price' => $depth_price));
				$this->mongo_db->limit(1);
				$this->mongo_db->order_by(array('created_date' => -1));
				$depth_responseArr = $this->mongo_db->get('market_depth');

				$depth_sell_quantity = 0;
				foreach ($depth_responseArr as $depth_valueArr) {
					if (!empty($depth_valueArr)) {

						$depth_sell_quantity += $depth_valueArr['quantity'];
					}
				}
				if ($depth_sell_quantity > $depth_big_quantity) {
					$depth_big_quantity = $depth_sell_quantity;
				}

				$returArr['depth_sell_quantity'] = $depth_sell_quantity;
				///////////////////////////////////////////////////////

				$priceee = $valueArr['price'];
				/*$search_arr['candle_type'] = "demand";
					$search_arr['coin'] = $global_symbol;
					$this->mongo_db->where($search_arr);
					// $this->mongo_db->sort(array('created_date' => -1));

					$this->mongo_db->sort(array('_id' => -1));
					$this->mongo_db->limit(1);
				*/

				$this->mongo_db->order_by(array('timestampDate' => -1));
				$this->mongo_db->where(array('coin' => $global_symbol));
				$this->mongo_db->where_in('global_swing_parent_status', array('LL', 'HH', 'LH', 'HL'));
				$this->mongo_db->limit(1);
				$depth_responseArr = $this->mongo_db->get('market_chart');
				//market_trade_hourly_history
				$arr = iterator_to_array($depth_responseArr);
				if (count($arr) > 0) {
					$datetime = $arr[0]['timestampDate']->toDateTime();
					$created_date = $datetime->format(DATE_RSS);

					$datetime = new DateTime($created_date);
					$datetime->format('Y-m-d H:i:s');

					// $new_timezone = new DateTimeZone('Asia/Karachi');
					//$datetime->setTimezone($new_timezone);
					$formated_date_time = $datetime->format('Y-m-d H:i:s');
					$end_date = date('Y-m-d H:i:s');

					$responseArr2222 = $this->get_price_volume_for_hour($global_symbol, $formated_date_time, $end_date, "bid", $priceee);
				} else {
					$this->mongo_db->where(array('maker' => 'true', 'coin' => $global_symbol, 'price' => $priceee));
					$responseArr2222 = $this->mongo_db->get('market_trades');
				}
				//////////////
				$buy_quantity = 0;
				foreach ($responseArr2222 as $valueArr222) {

					if (!empty($valueArr222)) {

						$buy_quantity += $valueArr222['volume'];
					}

				}
				$this->mongo_db->where(array('maker' => 'true', 'coin' => $global_symbol, 'price' => $priceee));
				$responseArr22224 = $this->mongo_db->get('market_trades');

				foreach ($responseArr22224 as $valueArr222) {

					if (!empty($valueArr222)) {

						$buy_quantity += $valueArr222['quantity'];
					}

				}
				if ($buy_quantity > $big_quantity) {
					$big_quantity = $buy_quantity;
				}

				$returArr['buy_quantity'] = $buy_quantity;

				$priceee = $valueArr['price'];
				if (count($arr) > 0) {
					$responseArr2222 = $this->get_price_volume_for_hour($global_symbol, $formated_date_time, $end_date, "ask", $priceee);
				} else {
					$this->mongo_db->where(array('maker' => 'false', 'coin' => $global_symbol, 'price' => $priceee));
					$responseArr2222 = $this->mongo_db->get('market_trades');
				}

				//////////////
				$sell_quantity = 0;
				foreach ($responseArr2222 as $valueArr222) {

					if (!empty($valueArr222)) {

						$sell_quantity += $valueArr222['volume'];
					}

				}
				$this->mongo_db->where(array('maker' => 'false', 'coin' => $global_symbol, 'price' => $priceee));
				$responseArr22223 = $this->mongo_db->get('market_trades');
				foreach ($responseArr22223 as $valueArr222) {

					if (!empty($valueArr222)) {

						$sell_quantity += $valueArr222['quantity'];
					}

				}
				if ($sell_quantity > $big_quantity) {
					$big_quantity = $sell_quantity;
				}

				$returArr['sell_quantity'] = $sell_quantity;
				////////////

			}
			unset($returArr['_id']);
			$fullarray[] = $returArr;
		}

		$sort = array();
		foreach ($fullarray as $k => $v) {
			$sort['price'][$k] = $v['price'];
		}
		array_multisort($sort['price'], SORT_DESC, $fullarray);

		$data['market_value'] = $market_value;
		$data['fullarray'] = $fullarray;
		$data['buy_big_quantity'] = $big_quantity;
		$data['depth_buy_big_quantity'] = $depth_big_quantity;

		return $data;

	} //end get_market_buy_depth_chart

	//get_market_sell_depth_chart
	public function get_market_sell_depth_chart($global_symbol = '') {

		//$global_symbol = $this->session->userdata('global_symbol');

		if (isset($_GET['market_value']) && $_GET['market_value'] != "" && $market_value == "") {

			$market_value = $_GET['market_value'];

		} elseif ($market_value != "") {

			$market_value = $market_value;

		} else {

			//Get Market Prices
			$market_value = $this->get_market_value($global_symbol);

		}

		///////////////////////////////////////
		$db = $this->mongo_db->customQuery();
		$priceAsk = (float) $market_value;

		$pipeline = array(
			array(
				'$project' => array(
					"price" => 1,
					"quantity" => 1,
					"type" => 1,
					"coin" => 1,
					'created_date' => 1,
				),
			),
			array(
				'$match' => array(
					'coin' => $global_symbol,
					'type' => 'bid',
					'price' => array('$lte' => $priceAsk),
				),
			),
			array('$sort' => array('created_date' => -1)),
			array('$group' => array(
				'_id' => array('price' => '$price'),
				'quantity' => array('$first' => '$quantity'),
				'type' => array('$first' => '$type'),
				'coin' => array('$first' => '$coin'),
				'created_date' => array('$first' => '$created_date'),
				'price' => array('$first' => '$price'),
			),

			),
			array('$sort' => array('price' => -1)),
			array('$limit' => 50),
		);

		$allow = array('allowDiskUse' => true);
		$responseArr = $db->market_depth->aggregate($pipeline, $allow);

		$fullarray = array();
		$big_quantity = 0;
		$depth_big_quantity = 0;
		foreach ($responseArr as $valueArr) {
			$returArr = array();

			if (!empty($valueArr)) {

				$datetime = $valueArr['created_date']->toDateTime();
				$created_date = $datetime->format(DATE_RSS);

				$datetime = new DateTime($created_date);
				$datetime->format('Y-m-d g:i:s A');

				//$new_timezone = new DateTimeZone('Asia/Karachi');
				//$datetime->setTimezone($new_timezone);
				$formated_date_time = $datetime->format('Y-m-d g:i:s A');

				$returArr['_id'] = $valueArr['_id'];
				$returArr['price'] = $valueArr['price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['type'] = $valueArr['type'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['created_date'] = $formated_date_time;

				///////////////////////////////////////////////////////
				$depth_price = $valueArr['price'];
				$this->mongo_db->where(array('type' => 'ask', 'coin' => $global_symbol, 'price' => $depth_price));
				$this->mongo_db->limit(1);
				$this->mongo_db->order_by(array('created_date' => -1));
				$depth_responseArr = $this->mongo_db->get('market_depth');

				$depth_buy_quantity = 0;
				foreach ($depth_responseArr as $depth_valueArr) {
					if (!empty($depth_valueArr)) {

						$depth_buy_quantity += $depth_valueArr['quantity'];
					}
				}
				if ($depth_buy_quantity > $depth_big_quantity) {
					$depth_big_quantity = $depth_buy_quantity;
				}

				$returArr['depth_buy_quantity'] = $depth_buy_quantity;
				///////////////////////////////////////////////////////

				///////////////////////////////////////////////////////
				$depth_price = $valueArr['price'];
				$this->mongo_db->where(array('type' => 'bid', 'coin' => $global_symbol, 'price' => $depth_price));
				$this->mongo_db->limit(1);
				$this->mongo_db->order_by(array('created_date' => -1));
				$depth_responseArr = $this->mongo_db->get('market_depth');

				$depth_sell_quantity = 0;
				foreach ($depth_responseArr as $depth_valueArr) {
					if (!empty($depth_valueArr)) {

						$depth_sell_quantity += $depth_valueArr['quantity'];
					}
				}
				if ($depth_sell_quantity > $depth_big_quantity) {
					$depth_big_quantity = $depth_sell_quantity;
				}

				$returArr['depth_sell_quantity'] = $depth_sell_quantity;
				///////////////////////////////////////////////////////

				/*$priceee = $valueArr['price'];
					$this->mongo_db->where(array('maker'=> 'true', 'coin'=> $global_symbol, 'price' => $priceee));
					$responseArr2222 = $this->mongo_db->get('market_trades');

					//////////////
					$buy_quantity = 0;
					foreach ($responseArr2222 as  $valueArr222) {

						if(!empty($valueArr222)){

							$buy_quantity += $valueArr222['quantity'];
						}

					}

					if($buy_quantity > $big_quantity){
						$big_quantity = $buy_quantity;
					}

					$returArr['buy_quantity'] = $buy_quantity;

					$priceee = $valueArr['price'];
					$this->mongo_db->where(array('maker'=> 'false', 'coin'=> $global_symbol, 'price' => $priceee));
					$responseArr2222 = $this->mongo_db->get('market_trades');

					//////////////
					$sell_quantity = 0;
					foreach ($responseArr2222 as  $valueArr222) {

						if(!empty($valueArr222)){

							$sell_quantity += $valueArr222['quantity'];
						}

				*/
				$priceee = $valueArr['price'];
				/*$search_arr['candle_type'] = "demand";
					$search_arr['coin'] = $global_symbol;
					$this->mongo_db->where($search_arr);
					$this->mongo_db->sort(array('created_date' => -1));
					$this->mongo_db->limit(1);
				*/

				$this->mongo_db->order_by(array('timestampDate' => -1));
				$this->mongo_db->where(array('coin' => $global_symbol));
				$this->mongo_db->where_in('global_swing_parent_status', array('LL', 'HH', 'LH', 'HL'));
				$this->mongo_db->limit(1);
				$depth_responseArr = $this->mongo_db->get('market_chart');
				//market_trade_hourly_history
				$arr = iterator_to_array($depth_responseArr);

				if (count($arr) > 0) {

					$datetime = $arr[0]['timestampDate']->toDateTime();
					$created_date = $datetime->format(DATE_RSS);

					$datetime = new DateTime($created_date);
					$datetime->format('Y-m-d H:i:s');

					//$new_timezone = new DateTimeZone('Asia/Karachi');
					//$datetime->setTimezone($new_timezone);
					$formated_date_time = $datetime->format('Y-m-d H:i:s');
					$end_date = date('Y-m-d H:i:s');

					$responseArr2222 = $this->get_price_volume_for_hour($global_symbol, $formated_date_time, $end_date, "bid", $priceee);
				} else {
					$this->mongo_db->where(array('maker' => 'true', 'coin' => $global_symbol, 'price' => $priceee));
					$responseArr2222 = $this->mongo_db->get('market_trades');
				}
				//////////////
				$buy_quantity = 0;
				foreach ($responseArr2222 as $valueArr222) {

					if (!empty($valueArr222)) {

						$buy_quantity += $valueArr222['volume'];
					}

				}
				$this->mongo_db->where(array('maker' => 'true', 'coin' => $global_symbol, 'price' => $priceee));
				$responseArr2222 = $this->mongo_db->get('market_trades');
				foreach ($responseArr2222 as $valueArr222) {

					if (!empty($valueArr222)) {

						$buy_quantity += $valueArr222['quantity'];
					}

				}
				if ($buy_quantity > $big_quantity) {
					$big_quantity = $buy_quantity;
				}

				$returArr['buy_quantity'] = $buy_quantity;

				$priceee = $valueArr['price'];
				if (count($arr) > 0) {

					$responseArr2222 = $this->get_price_volume_for_hour($global_symbol, $formated_date_time, $end_date, "ask", $priceee);
				} else {
					$this->mongo_db->where(array('maker' => 'false', 'coin' => $global_symbol, 'price' => $priceee));
					$responseArr222244 = $this->mongo_db->get('market_trades');
				}
				//////////////
				$sell_quantity = 0;
				foreach ($responseArr222244 as $valueArr222) {

					if (!empty($valueArr222)) {

						$sell_quantity += $valueArr222['volume'];
					}

				}
				$this->mongo_db->where(array('maker' => 'false', 'coin' => $global_symbol, 'price' => $priceee));
				$responseArr222255 = $this->mongo_db->get('market_trades');
				foreach ($responseArr222255 as $valueArr222) {

					if (!empty($valueArr222)) {

						$sell_quantity += $valueArr222['quantity'];
					}

				}
				if ($sell_quantity > $big_quantity) {
					$big_quantity = $sell_quantity;
				}

				$returArr['sell_quantity'] = $sell_quantity;
				////////////

			}
			unset($returArr['_id']);
			$fullarray[] = $returArr;
		}

		$sort = array();
		foreach ($fullarray as $k => $v) {
			$sort['price'][$k] = $v['price'];
		}
		array_multisort($sort['price'], SORT_DESC, $fullarray);

		$data['market_value'] = $market_value;
		$data['fullarray'] = $fullarray;
		$data['sell_big_quantity'] = $big_quantity;
		$data['depth_sell_big_quantity'] = $depth_big_quantity;

		return $data;

	} //end get_market_sell_depth_chart

	//get_market_value
	public function get_market_value($symbol = '') {

		if ($symbol != "") {
			$global_symbol = $symbol;
		} else {
			$global_symbol = $this->session->userdata('global_symbol');
		}

		//Get Market Prices
		$this->mongo_db->where(array('coin' => $global_symbol));
		$this->mongo_db->limit(1);
		$this->mongo_db->sort(array('_id' => 'desc'));
		$responseArr = $this->mongo_db->get('market_prices');

		foreach ($responseArr as $valueArr) {
			if (!empty($valueArr)) {
				$market_value = $valueArr['price'];
			}
		}

		return $market_value;

	} //End get_market_value

	public function insert_chart3($data) {

		$ins_id = $this->mongo_db->batch_insert('chart4', $data);

		if ($ins_id) {
			return true;
		} else {
			return false;
		}
	}

	public function get_bid_values_for_chart($symbol, $market_value, $limit = 0) {

		if ($limit == 0) {
			$limit = 50;
		} else {
			$limit = $limit;
		}
		$search_array['coin'] = $symbol;
		$search_array['type'] = 'bid';
		$search_array['price'] = array('$lte' => $market_value);

		$this->mongo_db->where($search_array);
		$this->mongo_db->limit($limit);
		$this->mongo_db->sort(array('price' => -1));
		$res = $this->mongo_db->get('chart4');

		$big_quantity = 0;
		foreach ($res as $valueArr) {
			$returArr = array();

			if (!empty($valueArr)) {

				$returArr['_id'] = $valueArr['_id'];
				$returArr['price'] = $valueArr['price'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['depth_buy_quantity'] = $valueArr['depth_buy_quantity'];
				$returArr['depth_sell_quantity'] = $valueArr['depth_sell_quantity'];
				$returArr['buy_quantity'] = $valueArr['buy_quantity'];

				if (!empty($valueArr222)) {

					$sell_quantity = $valueArr['sell_quantity'];
				}

				if ($sell_quantity > $big_quantity) {
					$big_quantity = $sell_quantity;
				}
				$returArr['sell_quantity'] = $valueArr['sell_quantity'];
				$returArr['type'] = $valueArr['type'];
			}
			$fullarray[] = $returArr;
		}
		array_multisort(array_column($fullarray, "price"), SORT_DESC, $fullarray);

		return $fullarray;
	}

	public function get_ask_values_for_chart($symbol, $market_value, $limit = 0) {
		if ($limit == 0) {
			$limit = 50;
		} else {
			$limit = $limit;
		}
		$connetct = $this->mongo_db->customQuery();
		$search_array['coin'] = $symbol;
		$search_array['type'] = 'ask';
		$search_array['price'] = array('$gte' => $market_value);

		$this->mongo_db->where($search_array);
		$this->mongo_db->limit($limit);
		$this->mongo_db->sort(array('price' => 1));
		$res = $this->mongo_db->get('chart4');

		foreach ($res as $valueArr) {
			$returArr = array();

			if (!empty($valueArr)) {

				$returArr['_id'] = $valueArr['_id'];
				$returArr['price'] = $valueArr['price'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['depth_buy_quantity'] = $valueArr['depth_buy_quantity'];
				$returArr['depth_sell_quantity'] = $valueArr['depth_sell_quantity'];
				$returArr['buy_quantity'] = $valueArr['buy_quantity'];
				$returArr['sell_quantity'] = $valueArr['sell_quantity'];
				$returArr['type'] = $valueArr['type'];
			}
			$fullarray[] = $returArr;
		}
		array_multisort(array_column($fullarray, "price"), SORT_DESC, $fullarray);
		return $fullarray;
	}

	//get_zone_values
	public function get_zone_values($market_value, $global_symbol = '') {

		//$global_symbol = $this->session->userdata('global_symbol');

		$priceAsk = num((float) $market_value);
		$db = $this->mongo_db->customQuery();

		$params = array(
			'start_value' => array('$gte' => $priceAsk),
			'end_value' => array('$lte' => $priceAsk),
			'coin' => $global_symbol,
		);

		$res = $db->chart_target_zones->find($params);

		foreach ($res as $valueArr) {
			if (!empty($valueArr)) {

				$start_value = num($valueArr['start_value']);
				$end_value = num($valueArr['end_value']);

				$zone_id = $valueArr['_id'];
				$zone_start_value = (float) $start_value;
				$zone_end_value = (float) $end_value;
				$zone_type = $valueArr['type'];
			}
		}

		if ($zone_type == 'sell') {
			$zone_type2 = 'bid';
		} else {
			$zone_type2 = 'ask';
		}

		$pipeline = array(
			array(
				'$project' => array(
					"price" => 1,
					"quantity" => 1,
					"type" => 1,
					"coin" => 1,
					'created_date' => 1,
				),
			),
			array(
				'$match' => array(
					'coin' => $global_symbol,
					'type' => $zone_type2,
					'price' => array('$lte' => $zone_start_value, '$gte' => $zone_end_value),

				),
			),
			array('$sort' => array('created_date' => -1)),
			array('$group' => array(
				'_id' => array('price' => '$price'),
				'quantity' => array('$first' => '$quantity'),
				'type' => array('$first' => '$type'),
				'coin' => array('$first' => '$coin'),
				'created_date' => array('$first' => '$created_date'),
				'price' => array('$first' => '$price'),
			),

			),
			array('$sort' => array('price' => 1)),
		);

		$allow = array('allowDiskUse' => true);
		$responseArr = $db->market_depth->aggregate($pipeline, $allow);

		$fullarray = array();
		$buy_quantity = 0;
		$sell_quantity = 0;
		foreach ($responseArr as $valueArr) {
			if (!empty($valueArr)) {

				$priceee = num($valueArr['price']);

				$created_datetime = date('Y-m-d G:i:s');
				$orig_date = new DateTime($created_datetime);
				$orig_date = $orig_date->getTimestamp();
				$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

				$created_datetime22 = date('Y-m-d G:i:s', strtotime("-1 hour"));
				$orig_date22 = new DateTime($created_datetime22);
				$orig_date22 = $orig_date22->getTimestamp();
				$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);

				$db = $this->mongo_db->customQuery();
				$params = array(
					'created_date' => array('$lte' => $start_date, '$gte' => $end_date),
					'maker' => 'true',
					'coin' => $global_symbol,
					'price' => $priceee,
				);

				$responseArr2222 = $db->market_trades->find($params);

				//////////////
				foreach ($responseArr2222 as $valueArr222) {
					if (!empty($valueArr222)) {
						$buy_quantity += $valueArr222['quantity'];
					}
				}

				$priceee22 = num($valueArr['price']);

				$created_datetime = date('Y-m-d G:i:s');
				$orig_date = new DateTime($created_datetime);
				$orig_date = $orig_date->getTimestamp();
				$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

				$created_datetime22 = date('Y-m-d G:i:s', strtotime("-1 hour"));
				$orig_date22 = new DateTime($created_datetime22);
				$orig_date22 = $orig_date22->getTimestamp();
				$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);

				$db = $this->mongo_db->customQuery();
				$params = array(
					'created_date' => array('$lte' => $start_date, '$gte' => $end_date),
					'maker' => 'false',
					'coin' => $global_symbol,
					'price' => $priceee22,
				);

				$responseArr3333 = $db->market_trades->find($params);

				/////////////
				foreach ($responseArr3333 as $valueArr3333) {
					if (!empty($valueArr3333)) {
						$sell_quantity += $valueArr3333['quantity'];
					}
				}

			}

		}
		////////////////////////////

		$total_quantity = $buy_quantity + $sell_quantity;

		$buy_percentage = round($buy_quantity * 100 / $total_quantity);
		$sell_percentage = round($sell_quantity * 100 / $total_quantity);

		$restttt['buy_quantity'] = $buy_quantity;
		$restttt['sell_quantity'] = $sell_quantity;
		$restttt['buy_percentage'] = $buy_percentage;
		$restttt['sell_percentage'] = $sell_percentage;
		$restttt['zone_id'] = $zone_id;

		return $restttt;

	} //end get_zone_values

	//get_chart_target_zones
	public function get_chart_target_zones() {

		$coin = $this->session->userdata('global_symbol');

		$this->mongo_db->where(array('coin' => $coin));
		$this->mongo_db->sort(array('_id' => 'desc'));
		$responseArr = $this->mongo_db->get('chart_target_zones');

		$fullarray = array();
		foreach ($responseArr as $valueArr) {

			if (!empty($valueArr)) {

				$datetime = $valueArr['created_date']->toDateTime();
				$created_date = $datetime->format(DATE_RSS);

				$datetime = new DateTime($created_date);
				$datetime->format('Y-m-d g:i:s A');

				$new_timezone = new DateTimeZone('Asia/Karachi');
				$datetime->setTimezone($new_timezone);
				$formated_date_time = $datetime->format('Y-m-d g:i:s A');

				$returArr['_id'] = $valueArr['_id'];
				$returArr['start_value'] = num($valueArr['start_value']);
				$returArr['end_value'] = num($valueArr['end_value']);
				$returArr['type'] = $valueArr['type'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['created_date'] = $formated_date_time;

				if ($valueArr['start_date'] != '') {
					$returArr['start_date'] = $this->mod_dashboard->change_time_stamp_to_human_readible($valueArr['start_date']);
				} else {
					$returArr['start_date'] = '';
				}

				if ($valueArr['end_date'] != '') {
					$returArr['end_date'] = $this->mod_dashboard->change_time_stamp_to_human_readible($valueArr['end_date']);
				} else {
					$returArr['end_date'] = '';
				}

			}

			$fullarray[] = $returArr;
		}

		return $fullarray;

	} //end get_chart_target_zones

	function get_price_volume_for_hour($symbol, $start_date, $end_date, $type, $price) {

		$search_array = array(
			'type' => $type,
			'coin' => $symbol,
			'price' => $price,
			'hour' => array('$gte' => $start_date, '$lte' => $end_date));

		$this->mongo_db->where($search_array);
		$res = $this->mongo_db->get('market_trade_hourly_history');
		$ask_volume_arr = iterator_to_array($res);
		return $ask_volume_arr;
	} /* End of get_ask_price_volume_for_hour */

	public function get_sell_active_orders() {

		$admin_id = $this->session->userdata('admin_id');
		$symbol = $this->session->userdata('global_symbol');
		$application_mode = $this->session->userdata('global_mode');
		//Check Filter Data
		//$session_post_data = $this->session->userdata('filter-data');

		//$search_array = array('admin_id'=> $admin_id, 'status' =>'new');

		$search_array = array('admin_id' => $admin_id, 'status' => 'FILLED', 'is_sell_order' => 'yes', 'application_mode' => $application_mode);
		$search_array['symbol'] = $symbol;

		$this->mongo_db->where($search_array);
		$this->mongo_db->limit(50);
		$this->mongo_db->sort(array('_id' => 'desc'));
		$responseArr22 = $this->mongo_db->get('buy_orders');



		$fullarray = array();
		foreach($responseArr22 as $valueArr22){
			$this->mongo_db->where(array('_id' => $valueArr22['sell_order_id']));
			$this->mongo_db->limit(50);
			$this->mongo_db->sort(array('_id' => 'desc'));
			$responseArr = $this->mongo_db->get('orders');
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
					$returArr['sell_price'] = number_format($valueArr['sell_price'], 8, '.', '');
					$returArr['market_value'] = $valueArr['market_value'];
					$returArr['trail_check'] = $valueArr['trail_check'];
					$returArr['trail_interval'] = $valueArr['trail_interval'];
					$returArr['sell_trail_price'] = $valueArr['sell_trail_price'];
					$returArr['stop_loss'] = $valueArr['stop_loss'];
					$returArr['loss_percentage'] = $valueArr['loss_percentage'];
					$returArr['status'] = $valueArr['status'];
					$returArr['admin_id'] = $valueArr['admin_id'];
					$returArr['application_mode'] = $valueArr['application_mode'];
					$returArr['buy_order_id'] = $valueArr['buy_order_id'];
					$returArr['buy_order_binance_id'] = $valueArr['buy_order_binance_id'];
					$returArr['created_date'] = $formated_date_time;
				}

				$fullarray[] = $returArr;
			}
		}
		return $fullarray;

	} //end get_sell_active_orders

	//get_buy_active_orders
	public function get_buy_active_orders() {

		$admin_id = $this->session->userdata('admin_id');
		$symbol = $this->session->userdata('global_symbol');

		//Check Filter Data
		$application_mode = $this->session->userdata('global_mode');
		$search_array = array('admin_id' => $admin_id, 'status' => 'new', 'application_mode' => $application_mode);
		//$search_array = array('admin_id'=> $admin_id, 'status' =>'new');

		$search_array['symbol'] = $symbol;

		$this->mongo_db->where($search_array);
		$this->mongo_db->limit(50);
		$this->mongo_db->sort(array('_id' => 'desc'));
		$responseArr = $this->mongo_db->get('buy_orders');

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
				$returArr['price'] = $valueArr['price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['trail_check'] = $valueArr['trail_check'];
				$returArr['trail_interval'] = $valueArr['trail_interval'];
				$returArr['buy_trail_price'] = $valueArr['buy_trail_price'];
				$returArr['status'] = $valueArr['status'];
				$returArr['admin_id'] = $valueArr['admin_id'];
				$returArr['application_mode'] = $valueArr['application_mode'];
				$returArr['created_date'] = $formated_date_time;
			}

			$fullarray[] = $returArr;
		}

		return $fullarray;

	} //end get_buy_active_orders

	public function get_trigger_market_value() {
		$global_symbol = $this->session->userdata("global_symbol");
		$this->mongo_db->where(array("coin" => $global_symbol));
		$ins = $this->mongo_db->get("buy_trigger_2_process_log");

		$market_value_arr = iterator_to_array($ins);
		$market_value = $market_value_arr[0]['current_market_value'];

		return $market_value;
	}

	public function get_historical_market_value($symbol = '', $datetime = '') {

		if ($datetime == '') {
			$datetime = date("Y-m-d G:i:s", strtotime('-5 minutes'));
		}
		$datetime = date("Y-m-d G:i:s", strtotime($datetime));
		$datetime2 = $this->mongo_db->converToMongodttime($datetime);
		$this->mongo_db->where(array("coin" => $symbol, 'time' => array('$lte' => $datetime2)));
		$this->mongo_db->limit(1);
		$this->mongo_db->order_by(array('time' => -1));
		$ins = $this->mongo_db->get("market_price_history");

		$market_value_arr = iterator_to_array($ins);
		/*echo "<pre>";
			print_r($market_value_arr);
		*/
		$market_value = $market_value_arr[0]['market_value'];

		return $market_value;
	}

	public function convert_price($sell_profit_percent, $purchased_price) {

		/*$sell_profit_percent = $this->input->post('sell_profit_percent');
		$purchased_price = $this->input->post('purchased_price');*/

		$sell_price = $purchased_price * $sell_profit_percent;
		$sell_price = $sell_price / 100;
		$sell_price = $sell_price + $purchased_price;

		return number_format($sell_price, 8, '.', '');

	} //End convert_price

	public function edit_order($data) {

		extract($data);
		$created_date = date('Y-m-d G:i:s');
		$upd_data = array(
			'purchased_price' => $purchased_price,
			'quantity' => $quantity,
			'profit_type' => $profit_type,
			'trigger_type' => 'no',
			'modified_date' => $this->mongo_db->converToMongodttime($created_date),
		);


		if ($profit_type == 'fixed_price') {
			$upd_data['sell_price'] = $sell_profit_price;
			$upd_data['sell_profit_price'] = $sell_profit_price;
		}elseif($profit_type == 'percentage'){
			$sell_profit_price = $this->convert_price($sell_profit_percent, $purchased_price);
			$upd_data['sell_profit_percent'] = $sell_profit_percent;
			$upd_data['sell_price'] = $sell_profit_price;
			$upd_data['sell_profit_price'] = $sell_profit_price;
		}

		// if ($sell_profit_percent != ' ') {
		// 	$sell_profit_price = $this->convert_price($sell_profit_percent, $purchased_price);
		// 	$upd_data['sell_profit_percent'] = $sell_profit_percent;
		// 	$upd_data['sell_price'] = $sell_profit_price;
		// }

		 $this->mongo_db->where(array('_id' => $id));
		 $this->mongo_db->set($upd_data);
		// //Update data in mongoTable
		 $this->mongo_db->update('orders');


		$upd_data222 = array(
			'purchased_price' => $purchased_price,
			'market_value' => $purchased_price,
			'quantity' => $quantity,
			'profit_type' => $profit_type,
			'trigger_type' => 'no',
			'modified_date' => $this->mongo_db->converToMongodttime($created_date),
		);
		$upd_data['sell_price'] = $sell_profit_price;
	  $this->mongo_db->where(array('sell_order_id' => $this->mongo_db->mongoId($id)));
		$this->mongo_db->set($upd_data);
		//
		//Update data in mongoTable
		$this->mongo_db->update('buy_orders');

		$log_msg = "Sell Order was Updated From Chart Manually";
		$this->insert_order_history_log($id, $log_msg, 'sell_updated', $admin_id);

	}

	public function edit_buy_order($data) {
		extract($data);
		$created_date = date('Y-m-d G:i:s');
		$upd_data = array(
			'price' => $price,
			'quantity' => $quantity,
			'modified_date' => $this->mongo_db->converToMongodttime($created_date),
		);
		$this->mongo_db->where(array('_id' => $id));
		$this->mongo_db->set($upd_data);
		//Update data in mongoTable
		$this->mongo_db->update('buy_orders');
		$log_msg = "Buy Order was Updated From Chart Manually";
		$this->insert_order_history_log($id, $log_msg, 'buy_updated', $admin_id);
	}

	public function add_buy_order($data) {
		extract($data);

		$created_date = date('Y-m-d G:i:s');
		$admin_id = $this->session->userdata('admin_id');
		$application_mode = $this->session->userdata('global_mode');
		$ins_data = array(
			'price' => $price,
			'quantity' => $quantity,
			'symbol' => $coin,
			'order_type' => $order_type,
			'admin_id' => $admin_id,
			'created_date' => $this->mongo_db->converToMongodttime($created_date),
			'trail_check' => '',
			'trail_interval' => '',
			'trigger_type' => 'no',
			'buy_trail_price' => '',
			'status' => 'new',
			'auto_sell' => '',
			'market_value' => '',
			'binance_order_id' => '',
			'is_sell_order' => '',
			'sell_order_id' => '',
			'application_mode' => $application_mode,
		);

		$buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);

		$log_msg = "Sell Order was Created";
		$this->insert_order_history_log($buy_order_id, $log_msg, 'buy_created', $admin_id);

		return $buy_order_id;
	}

		//insert_order_history_log
	public function insert_order_history_log($id, $log_msg, $type, $user_id) {

		$created_date = date('Y-m-d G:i:s');

		$ins_error = array(
			'order_id'     => $this->mongo_db->mongoId($id),
			'log_msg'      => $log_msg,
			'type'         => $type,
			'created_date' => $this->mongo_db->converToMongodttime($created_date),
		);

		$this->mongo_db->insert('orders_history_log', $ins_error);

		return true;

	}//end insert_order_history_log

	public function calculate_pressure_up_and_down($datetime, $coin, $pressure_type) {
		$start_date = $datetime;
		$end_date = date('Y-m-d H:i:s', strtotime("+59 seconds", strtotime($datetime)));
		$this->mongo_db->where_gte('created_date', $this->mongo_db->converToMongodttime($start_date));
		$this->mongo_db->where_lt('created_date', $this->mongo_db->converToMongodttime($end_date));
		$this->mongo_db->where(array('coin' => $coin, 'pressure' => $pressure_type));
		$this->mongo_db->limit(100);
		$this->mongo_db->order_by(array('created_date' => -1));
		$res = $this->mongo_db->get('order_book_pressure');
		$res_arr = iterator_to_array($res);

		return $total_pressure = count($res_arr);
	} //End of calculate_pressure_up_and_down

	public function calculate_score($score_array) {

		$depth_pressure = $score_array['depth_pressure'];
		$depth_pressure_side = $score_array['depth_pressure_side'];

		$black_pressure = $score_array['black_pressure'];
		$black_color_side = $score_array['black_color_side'];

		$yellow_pressure = $score_array['yellow_pressure'];
		$yellow_color_side = $score_array['yellow_color_side'];

		$seven_level = $score_array['seven_level'];
		$seven_level_side = $score_array['seven_level_side'];

		$big_pressure = $score_array['big_pressure'];

		$buyers = $score_array['buyers'];

		$barrier = $score_array['barrier_diff'];
		$barrier_side = $score_array['barrier_side'];

		////////////////////////// DEPTH SCORE //////////////////////////////////
		if ($barrier_side == 'down') {
			$score_depth = $depth_pressure * -1;
		} elseif ($barrier_side == 'up') {
			$score_depth = $depth_pressure * 1;
		}
		///////////////////End Depth Score /////////////////////////////////////

		////////////////////////// Barrier SCORE //////////////////////////////////
		if ($depth_pressure_side == 'down') {
			$score_barrier = 2;
		} elseif ($depth_pressure_side == 'up') {
			$score_barrier = -2;
		}
		///////////////////End Barrier Score /////////////////////////////////////

		////////////////////////// Black Score //////////////////////////////////
		if ($black_pressure >= 5) {
			$score_black = 5;
		} else {
			$score_black = $black_pressure;
		}

		if ($black_color_side == 'down') {
			$score_black = $score_black * -1;
		} elseif ($black_color_side == 'up') {
			$score_black = $score_black * 1;
		}
		///////////////////End Black Score /////////////////////////////////////

		////////////////////////// Yellow Score //////////////////////////////////
		if ($yellow_pressure >= 3) {
			$score_yellow = 3;
		} else {
			$score_yellow = $yellow_pressure;
		}

		if ($yellow_color_side == 'down') {
			$score_yellow = $score_yellow * -1;
		} elseif ($yellow_color_side == 'up') {
			$score_yellow = $score_yellow * 1;
		}
		///////////////////End Yellow Score /////////////////////////////////////

		////////////////////////// Seven Level Score //////////////////////////////////
		if ($seven_level <= 0.5) {
			$score_seven = 1;
		} elseif ($seven_level <= 1) {
			$score_seven = 2;
		} elseif ($seven_level <= 2) {
			$score_seven = 3;
		} else {
			$score_seven = 4;
		}

		if ($seven_level_side == 'down') {
			$score_seven = $score_seven * -1;
		} elseif ($seven_level_side == 'up') {
			$score_seven = $score_seven * 1;
		}
		///////////////////End Seven Level Score /////////////////////////////////////

		////////////////////////// Buyers Level Score //////////////////////////////////
		if ($buyers <= 10) {
			$score_buyers = 0;
		} elseif ($buyers <= 25) {
			$score_buyers = 1;
		} elseif ($buyers <= 50) {
			$score_buyers = 2;
		} elseif ($buyers <= 75) {
			$score_buyers = 3;
		} elseif ($buyers <= 90) {
			$score_buyers = 4;
		} elseif ($buyers <= 100) {
			$score_buyers = 5;
		}
		///////////////////End Buyers Level Score /////////////////////////////////////

		/////////////////////////Big Pressure ///////////////////////////////////////

		if ($big_pressure == 'down') {
			$score_big = 2;
		} elseif ($big_pressure == 'up') {
			$score_big = -2;
		}
		///////////////////////////////////End Big Prssure ////////////////////////
		$total_score_prev = $score_depth + $score_black + $score_yellow + $score_seven + $score_buyers + $score_big + $score_barrier;
		$total_score = $total_score_prev + 50;

		return $total_score;
	}
}
