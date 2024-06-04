<?php
/**
 *
 */
class mod_script extends CI_Model {

	//get_market_buy_depth
	public function get_market_buy_depth($market_value = '', $limit = 1) {

		$global_symbol = $this->session->userdata('global_symbol');

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
			// array('$sort'=>array('price'=>1)),
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
			array('$limit' => $limit),
		);

		$allow = array('allowDiskUse' => true);
		$responseArr = $db->market_depth->aggregate($pipeline, $allow);

		$fullarray = array();
		foreach ($responseArr as $valueArr) {

			$returArr = array();

			if (!empty($valueArr)) {

				$returArr['price'] = num($valueArr['price']);
				$returArr['quantity'] = $valueArr['quantity'];

			}

			$fullarray[] = $returArr;
		}

		$sort = array();
		foreach ($fullarray as $k => $v) {
			$sort['price'][$k] = $v['price'];
		}
		array_multisort($sort['price'], SORT_DESC, $fullarray);

		/*$data['market_value'] = $market_value;
		$data['fullarray'] = $fullarray;*/

		return $fullarray;

	} //end get_market_buy_depth

	//get_market_sell_depth
	public function get_market_sell_depth($market_value = '', $limit = 1) {

		$global_symbol = $this->session->userdata('global_symbol');

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
			// array('$sort'=>array('price'=>1)),
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
			array('$limit' => $limit),
		);

		$allow = array('allowDiskUse' => true);
		$responseArr = $db->market_depth->aggregate($pipeline, $allow);

		$fullarray = array();
		foreach ($responseArr as $valueArr) {
			$returArr = array();

			if (!empty($valueArr)) {

				$returArr['price'] = num($valueArr['price']);
				$returArr['quantity'] = $valueArr['quantity'];

				$priceee = $valueArr['price'];
			}

			$fullarray[] = $returArr;
		}

		$sort = array();
		foreach ($fullarray as $k => $v) {
			$sort['price'][$k] = $v['price'];
		}
		array_multisort($sort['price'], SORT_ASC, $fullarray);

		/*$data['market_value'] = $market_value;
		$data['fullarray'] = $fullarray;*/

		return $fullarray;

	} //end get_market_sell_depth

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

		return num($market_value);

	} //End get_market_value

}
?>