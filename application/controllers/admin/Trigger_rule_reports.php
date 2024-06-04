	<?php

use phpDocumentor\Reflection\Types\Null_;

/****/
	class Trigger_rule_reports extends CI_Controller
	{

	function __construct(){
		parent::__construct();
		$this->stencil->layout('admin_layout');
		//load required slices
		$this->stencil->slice('admin_header_script');
		$this->stencil->slice('admin_header');
		$this->stencil->slice('admin_left_sidebar');
		$this->stencil->slice('admin_footer_script');

		//load models
		$this->load->model('admin/mod_trigger_rule_report');
		$this->load->model('admin/mod_dashboard');
		$this->load->model('admin/mod_coins');
		$this->load->model('admin/mod_login');
		$this->load->model('admin/mod_buy_orders');

		$this->load->helper('common_helper');
		$this->load->helper('new_common_helper');   

	}

	public function index(){
		//Login Check
		//$this->mod_login->verify_is_admin_login();

		$this->stencil->paint('admin/reports/home', $data);
	}

	public function indicator_listing()
	{
		//Login Check
		$this->mod_login->verify_is_admin_login();

		/////////////////////////////////////////////////////////////

		if (!empty($this->input->post("coin_symbol"))) {
			$coin_symbol = $this->input->post("coin_symbol");
		} else {
			$coin_symbol = "NCASHBTC";
		}

		if (!empty($this->input->post("breakable_barrier"))) {
			$breakable_barrier = $this->input->post("breakable_barrier");
			$search_arr['breakable'] = $breakable_barrier;
		}

		if (!empty($this->input->post("barrier_swing"))) {
			$barrier_swing = $this->input->post("barrier_swing");
			$search_arr['global_swing_parent_status'] = $barrier_swing;
		} else {
			$barrier_swing = '';
		}

		if (!empty($this->input->post("filter_time"))) {
			$filter_time = $this->input->post("filter_time");
		} else {
			$filter_time = '-7 days';
		}

		if (!empty($this->input->post("barrier_status"))) {
			$barrier_status = $this->input->post("barrier_status");
		} else {
			$barrier_status = 'very_strong_barrier';
		}

		$data['coin'] = $coin_symbol;
		$data['barrier_type'] = $barrier_type;
		$data['barrier_swing'] = $barrier_swing;
		$data['break_barrier'] = $breakable_barrier;
		$data['barrier_status'] = $barrier_status;
		$data['filter_time'] = $filter_time;
		$contract_quantity = $this->mod_coins->get_coin_contract_size($coin_symbol);
		////////////////////////////////////////////////////////////
		$barrier_type = "up";
		$search_arr['coin'] = $coin_symbol;
		$search_arr['barrier_type'] = $barrier_type;
		$search_arr['barrier_status'] = $barrier_status;

		$datetime = date("Y-m-d H:i:s", strtotime($filter_time));

		$search_arr['created_date'] = array('$gte' => $this->mongo_db->converToMongodttime($datetime));

		$this->mongo_db->where($search_arr);
		$get = $this->mongo_db->get('barrier_values_collection');
		$pre_res = iterator_to_array($get);
		$up_breakable = 0;
		$up_non_breakable = 0;
		foreach ($pre_res as $key => $value) {
			$created_date = $value['created_date'];
			$barrier_value = $value['barier_value'];
			$barrier_id = $value['_id'];

			$new_Date = $created_date->toDateTime()->format("Y-m-d H:i:s");
			$search_arr2['barrier_id'] = $barrier_id;
			$search_arr2['coin'] = $coin_symbol;
			//$search_arr2['created_date'] = $created_date;

			$this->mongo_db->where($search_arr2);
			$this->mongo_db->limit(1);
			$gets = $this->mongo_db->get('barrier_test_collection');
			$coin_meta = iterator_to_array($gets);

			$this->mongo_db->where($search_arr2);
			$this->mongo_db->limit(1);
			$gets = $this->mongo_db->get('barrier_test_collection');
			$coin_meta = iterator_to_array($gets);

			if (!empty($coin_meta)) {
				$res['ups'][] = array(
					'coin' => $coin_symbol,
					'barrier_value' => $barrier_value,
					'barrier_creation_time' => $new_Date222,
					'market_value_time' => $new_Date,
					'quantity' => intval($coin_meta[0]['barrier_quantity']),
					'black_wall_pressure' => intval($coin_meta[0]['black_wall_pressure']),
					'yellow_wall_pressure' => intval($coin_meta[0]['yellow_wall_pressure']),
					'depth_pressure' => intval($coin_meta[0]['depth_pressure']),
					'bid_contracts' => intval($coin_meta[0]['bid_contracts']),
					'bid_percentage' => intval($coin_meta[0]['bid_percentage']),
					'ask_contract' => floatval($coin_meta[0]['ask_contract']),
					'ask_percentage' => floatval($coin_meta[0]['ask_percentage']),
					'buyers' => intval($coin_meta[0]['buyers']),

					'sellers' => intval($coin_meta[0]['sellers']),

					'buyers_percentage' => floatval($coin_meta[0]['buyers_percentage']),

					'sellers_percentage' => floatval($coin_meta[0]['sellers_percentage']),

					'sellers_buyers_per' => floatval($coin_meta[0]['sellers_buyers_per']),

					'trade_type' => $coin_meta['trade_type'],
					'great_wall_quantity' => intval($coin_meta[0]['great_wall_quantity']),
					'great_wall' => $coin_meta[0]['great_wall'],
					'seven_level_depth' => floatval($coin_meta[0]['seven_level_depth']),
					'score' => $coin_meta[0]['score'],
					'last_qty_buy_vs_sell' => $coin_meta[0]['last_qty_buy_vs_sell'],
					'last_qty_time_ago' => (int) filter_var($coin_meta[0]['last_qty_time_ago'], FILTER_SANITIZE_NUMBER_INT),
					'last_200_buy_vs_sell' => $coin_meta[0]['last_200_buy_vs_sell'],
					'last_200_time_ago' => (int) filter_var($coin_meta[0]['last_200_time_ago'], FILTER_SANITIZE_NUMBER_INT),

				);

				if ($coin_meta[0]['breakable'] == 'breakable') {
					$up_breakable++;
				}
				if ($coin_meta[0]['breakable'] == 'non_breakable') {
					$up_non_breakable++;
				}
			}
		}

		$returnArr = array();

		/*=========================Quantity Pressure=====================================*/
		$quantity_arr = array_column($res['ups'], 'quantity');
		$avg_quantity = array_sum($quantity_arr) / count($quantity_arr);
		$max_quantity = max($quantity_arr);
		$min_quantity = min($quantity_arr);
		$returnArr['ups']['barrier_quantity'] = array(
			'avg' => $avg_quantity,
			'max' => $max_quantity,
			'min' => $min_quantity,
		);
		/*=======================End Black Wall Pressure===============================*/
		/*==============================Black Wall Pressure==========================================*/
		$black_wall_array = array_column($res['ups'], 'black_wall_pressure');
		$average_black_wall = array_sum($black_wall_array) / count($black_wall_array);
		$max_black_wall_pressure = max($black_wall_array);
		$min_black_wall_pressure = min($black_wall_array);
		$returnArr['ups']['black_wall_pressure'] = array(
			'avg' => $average_black_wall,
			'max' => $max_black_wall_pressure,
			'min' => $min_black_wall_pressure,
		);
		/*==============================End Black Wall Pressure=======================================*/

		/*==============================Yellow Wall Pressure==========================================*/
		$yellow_wall_array = array_column($res['ups'], 'yellow_wall_pressure');
		$average_yellow_wall = array_sum($yellow_wall_array) / count($yellow_wall_array);
		$max_yellow_wall_pressure = max($yellow_wall_array);
		$min_yellow_wall_pressure = min($yellow_wall_array);

		$returnArr['ups']['yellow_wall_pressure'] = array(

			'avg' => $average_yellow_wall,
			'max' => $max_yellow_wall_pressure,
			'min' => $min_yellow_wall_pressure,
		);
		/*==============================End Yellow Wall Pressure=======================================*/

		/*================================Depth Pressure===============================================*/
		$depth_array = array_column($res['ups'], 'depth_pressure');
		$average_depth = array_sum($depth_array) / count($depth_array);
		$max_depth_pressure = max($depth_array);
		$min_depth_pressure = min($depth_array);

		$returnArr['ups']['depth_pressure'] = array(
			'avg' => $average_depth,
			'max' => $max_depth_pressure,
			'min' => $min_depth_pressure,
		);
		/*==============================End Depth Pressure=======================================*/

		/*================================Bid Contracts==========================================*/
		$bid_contracts_arr = array_column($res['ups'], 'bid_contracts');
		$average_bids = array_sum($bid_contracts_arr) / count($bid_contracts_arr);
		$max_bids = max($bid_contracts_arr);
		$min_bids = min($bid_contracts_arr);

		$returnArr['ups']['bid_contracts'] = array(
			'avg' => $average_bids,
			'max' => $max_bids,
			'min' => $min_bids,
		);
		/*==============================End Bid Contracts========================================*/

		/*================================Ask Contracts==========================================*/
		$ask_contracts_arr = array_column($res['ups'], 'ask_contract');
		$average_asks = array_sum($ask_contracts_arr) / count($ask_contracts_arr);
		$max_asks = max($ask_contracts_arr);
		$min_asks = min($ask_contracts_arr);

		$returnArr['ups']['ask_contract'] = array(
			'avg' => $average_asks,
			'max' => $max_asks,
			'min' => $min_asks,
		);
		/*==============================End Ask Contracts========================================*/

		/*================================Bid Contracts==========================================*/
		$bid_percentage_arr = array_column($res['ups'], 'bid_percentage');
		$average_bids_per = array_sum($bid_percentage_arr) / count($bid_percentage_arr);
		$max_bids_per = max($bid_percentage_arr);
		$min_bids_per = min($bid_percentage_arr);
		$returnArr['ups']['bid_percentage'] = array(
			'avg' => $average_bids_per,
			'max' => $max_bids_per,
			'min' => $min_bids_per,
		);
		/*==============================End Bid Contracts========================================*/

		/*================================Ask Contracts==========================================*/
		$ask_percentage_arr = array_column($res['ups'], 'ask_percentage');
		$average_asks_per = array_sum($ask_percentage_arr) / count($ask_percentage_arr);
		$max_asks_per = max($ask_percentage_arr);
		$min_asks_per = min($ask_percentage_arr);

		$returnArr['ups']['ask_percentage'] = array(
			'avg' => $average_asks_per,
			'max' => $max_asks_per,
			'min' => $min_asks_per,
		);
		/*==============================End Ask Contracts========================================*/

		/*================================Buyers==========================================*/
		$buyers_arr = array_column($res['ups'], 'buyers');
		$average_asks_per = array_sum($buyers_arr) / count($buyers_arr);
		$max_asks_per = max($buyers_arr);
		$min_asks_per = min($buyers_arr);

		$returnArr['ups']['buyers'] = array(
			'avg' => $average_asks_per,
			'max' => $max_asks_per,
			'min' => $min_asks_per,
		);
		/*==============================End Buyers========================================*/

		/*================================Sellers==========================================*/
		$seller_arr = array_column($res['ups'], 'sellers');
		$average_asks_per = array_sum($seller_arr) / count($seller_arr);
		$max_asks_per = max($seller_arr);
		$min_asks_per = min($seller_arr);

		$returnArr['ups']['sellers'] = array(
			'avg' => $average_asks_per,
			'max' => $max_asks_per,
			'min' => $min_asks_per,
		);
		/*==============================End Sellers========================================*/

		/*================================Sellers==========================================*/
		$buyers_percentage_arr = array_column($res['ups'], 'buyers_percentage');
		$average_asks_per = array_sum($buyers_percentage_arr) / count($buyers_percentage_arr);
		$max_asks_per = max($buyers_percentage_arr);
		$min_asks_per = min($buyers_percentage_arr);

		$returnArr['ups']['buyers_percentage'] = array(
			'avg' => $average_asks_per,
			'max' => $max_asks_per,
			'min' => $min_asks_per,
		);
		/*==============================End Sellers========================================*/

		/*================================Sellers==========================================*/
		$buyers_percentage_arr = array_column($res['ups'], 'sellers_percentage');
		$average_asks_per = array_sum($buyers_percentage_arr) / count($buyers_percentage_arr);
		$max_asks_per = max($buyers_percentage_arr);
		$min_asks_per = min($buyers_percentage_arr);

		$returnArr['ups']['sellers_percentage'] = array(
			'avg' => $average_asks_per,
			'max' => $max_asks_per,
			'min' => $min_asks_per,
		);
		/*==============================End Sellers========================================*/

		/*================================Sellers==========================================*/
		$sellers_buyers_percentage_arr = array_column($res['ups'], 'sellers_buyers_per');
		$average_asks_per = array_sum($sellers_buyers_percentage_arr) / count($sellers_buyers_percentage_arr);
		$max_asks_per = max($sellers_buyers_percentage_arr);
		$min_asks_per = min($sellers_buyers_percentage_arr);

		$returnArr['ups']['sellers_buyers_per'] = array(
			'avg' => $average_asks_per,
			'max' => $max_asks_per,
			'min' => $min_asks_per,
		);
		/*==============================End Sellers========================================*/

		/*================================Great Wall==========================================*/
		$great_wall_array = array_column($res['ups'], 'great_wall_quantity');
		$great_wall_avg = array_sum($great_wall_array) / count($great_wall_array);
		$max_great_wall = max($great_wall_array);
		$min_great_wall = min($great_wall_array);

		$returnArr['ups']['great_wall'] = array(
			'avg' => $great_wall_avg,
			'max' => $max_great_wall,
			'min' => $min_great_wall,
		);
		/*==============================End Great Wall========================================*/

		/*================================Sevenlevel==========================================*/
		$seven_level_array = array_column($res['ups'], 'seven_level_depth');
		$seven_level_avg = array_sum($seven_level_array) / count($seven_level_array);
		$max_seven_level = max($seven_level_array);
		$min_seven_level = min($seven_level_array);

		$returnArr['ups']['seven_level_depth'] = array(
			'avg' => $seven_level_avg,
			'max' => $max_seven_level,
			'min' => $min_seven_level,
		);

		/*==============================End Sevenlevel========================================*/

		/*=========================last_qty_buy_vs_sell====================================*/
		$last_qty_buy_vs_sell_arr = array_column($res['ups'], 'last_qty_buy_vs_sell');
		$avg_last_qty_buy_vs_sell = array_sum($last_qty_buy_vs_sell_arr) / count($last_qty_buy_vs_sell_arr);
		$max_last_qty_buy_vs_sell = max($last_qty_buy_vs_sell_arr);
		$min_last_qty_buy_vs_sell = min($last_qty_buy_vs_sell_arr);
		$returnArr['ups']['last_qty_buy_vs_sell (' . number_format_short($contract_quantity) . ')'] = array(
			'avg' => $avg_last_qty_buy_vs_sell,
			'max' => $max_last_qty_buy_vs_sell,
			'min' => $min_last_qty_buy_vs_sell,
		);
		/*=======================End last_qty_buy_vs_sell===============================*/

		/*=========================last_qty_time_ago====================================*/
		$last_qty_time_ago_arr = array_column($res['ups'], 'last_qty_time_ago');
		$avg_last_qty_time_ago = array_sum($last_qty_time_ago_arr) / count($last_qty_time_ago_arr);
		$max_last_qty_time_ago = max($last_qty_time_ago_arr);
		$min_last_qty_time_ago = min($last_qty_time_ago_arr);
		$returnArr['ups']['last_qty_time_ago (' . number_format_short($contract_quantity) . ')'] = array(
			'avg' => $avg_last_qty_time_ago,
			'max' => $max_last_qty_time_ago,
			'min' => $min_last_qty_time_ago,
		);
		/*=======================End last_qty_time_ago===============================*/

		/*=========================last_200_buy_vs_sell====================================*/
		$last_200_buy_vs_sell_arr = array_column($res['ups'], 'last_200_buy_vs_sell');
		$avg_last_200_buy_vs_sell = array_sum($last_200_buy_vs_sell_arr) / count($last_200_buy_vs_sell_arr);
		$max_last_200_buy_vs_sell = max($last_200_buy_vs_sell_arr);
		$min_last_200_buy_vs_sell = min($last_200_buy_vs_sell_arr);
		$returnArr['ups']['last_200_buy_vs_sell'] = array(
			'avg' => $avg_last_200_buy_vs_sell,
			'max' => $max_last_200_buy_vs_sell,
			'min' => $min_last_200_buy_vs_sell,
		);
		/*=======================End last_200_buy_vs_sell===============================*/

		/*=========================last_200_time_ago====================================*/
		$last_200_time_ago_arr = array_column($res['ups'], 'last_200_time_ago');
		$avg_last_200_time_ago = array_sum($last_200_time_ago_arr) / count($last_200_time_ago_arr);
		$max_last_200_time_ago = max($last_200_time_ago_arr);
		$min_last_200_time_ago = min($last_200_time_ago_arr);
		$returnArr['ups']['last_200_time_ago'] = array(
			'avg' => $avg_last_200_time_ago,
			'max' => $max_last_200_time_ago,
			'min' => $min_last_200_time_ago,
		);
		/*=======================End last_200_time_ago===============================*/

		//=========================================Downs=======================================

		$search_arr = array();
		$barrier_type = "down";
		$search_arr['coin'] = $coin_symbol;
		$search_arr['barrier_type'] = $barrier_type;
		$search_arr['barrier_status'] = $barrier_status;
		if (isset($barrier_swing) && $barrier_swing != '') {
			$search_arr['global_swing_parent_status'] = $barrier_swing;
		}
		if (isset($breakable_barrier) && $breakable_barrier != '') {
			$search_arr['breakable'] = $breakable_barrier;
		}
		$datetime = date("Y-m-d H:i:s", strtotime($filter_time));

		$search_arr['created_date'] = array('$gte' => $this->mongo_db->converToMongodttime($datetime));

		$this->mongo_db->where($search_arr);
		$get = $this->mongo_db->get('barrier_values_collection');
		$pre_res2 = iterator_to_array($get);
		$down_breakable = 0;
		$down_non_breakable = 0;
		foreach ($pre_res2 as $key => $value) {
			$created_date = $value['created_date'];
			$barrier_value = $value['barier_value'];
			$barrier_id = $value['_id'];

			$search_arr2['barrier_id'] = $barrier_id;
			$search_arr2['coin'] = $coin_symbol;
			//$search_arr2['created_date'] = $created_date;

			$this->mongo_db->where($search_arr2);
			$this->mongo_db->limit(1);
			$gets = $this->mongo_db->get('barrier_test_collection');
			$coin_meta = iterator_to_array($gets);

			if (!empty($coin_meta)) {
				$res['downs'][] = array(
					'coin' => $coin_symbol,
					'barrier_value' => $barrier_value,
					'barrier_creation_time' => $new_Date222,
					'market_value_time' => $new_Date,
					'quantity' => intval($coin_meta[0]['barrier_quantity']),
					'black_wall_pressure' => intval($coin_meta[0]['black_wall_pressure']),
					'yellow_wall_pressure' => intval($coin_meta[0]['yellow_wall_pressure']),
					'depth_pressure' => intval($coin_meta[0]['depth_pressure']),
					'bid_contracts' => intval($coin_meta[0]['bid_contracts']),
					'bid_percentage' => intval($coin_meta[0]['bid_percentage']),
					'ask_contract' => floatval($coin_meta[0]['ask_contract']),
					'ask_percentage' => floatval($coin_meta[0]['ask_percentage']),
					'buyers' => intval($coin_meta[0]['buyers']),

					'sellers' => intval($coin_meta[0]['sellers']),

					'buyers_percentage' => floatval($coin_meta[0]['buyers_percentage']),

					'sellers_percentage' => floatval($coin_meta[0]['sellers_percentage']),

					'sellers_buyers_per' => floatval($coin_meta[0]['sellers_buyers_per']),

					'trade_type' => $coin_meta['trade_type'],
					'great_wall_quantity' => intval($coin_meta[0]['great_wall_quantity']),
					'great_wall' => $coin_meta[0]['great_wall'],
					'seven_level_depth' => floatval($coin_meta[0]['seven_level_depth']),
					'profit' => floatval($coin_meta[0]['updated_profit']),
					'loss' => floatval($coin_meta[0]['updated_loss']),

					'score' => $coin_meta[0]['score'],
					'last_qty_buy_vs_sell' => $coin_meta[0]['last_qty_buy_vs_sell'],
					'last_qty_time_ago' => (int) filter_var($coin_meta[0]['last_qty_time_ago'], FILTER_SANITIZE_NUMBER_INT),
					'last_200_buy_vs_sell' => $coin_meta[0]['last_200_buy_vs_sell'],
					'last_200_time_ago' => (int) filter_var($coin_meta[0]['last_200_time_ago'], FILTER_SANITIZE_NUMBER_INT),

				);

				if ($coin_meta[0]['breakable'] == 'breakable') {
					$down_breakable++;
				}
				if ($coin_meta[0]['breakable'] == 'non_breakable') {
					$down_non_breakable++;
				}
			}
		}
		/*==============================Total Profit ==========================================*/
		$profit_arr = array_column($res['downs'], 'profit');
		$avg_profit = array_sum($profit_arr) / count($profit_arr);
		$max_profit = max($profit_arr);
		$min_profit = min($profit_arr);
		$profit_loss['profit'] = array(
			'avg' => $avg_profit,
			'max' => $max_profit,
			'min' => $min_profit,
		);
		/*==============================End Total Profit=======================================*/
		/*==============================Total Loss==========================================*/
		$loss_arr = array_column($res['downs'], 'loss');
		$avg_loss = array_sum($loss_arr) / count($loss_arr);
		$max_loss = max($loss_arr);
		$min_loss = min($loss_arr);
		$profit_loss['loss'] = array(
			'avg' => $avg_loss,
			'max' => $max_loss,
			'min' => $min_loss,
		);
		/*==============================End Total Loss=======================================*/

		/*==============================Quantity Pressure==========================================*/
		$quantity_arr = array_column($res['downs'], 'quantity');
		$avg_quantity = array_sum($quantity_arr) / count($quantity_arr);
		$max_quantity = max($quantity_arr);
		$min_quantity = min($quantity_arr);
		$returnArr['downs']['barrier_quantity'] = array(
			'avg' => $avg_quantity,
			'max' => $max_quantity,
			'min' => $min_quantity,
		);
		/*==============================End Quantity Pressure=======================================*/

		/*==============================Black Wall Pressure==========================================*/
		$black_wall_array = array_column($res['downs'], 'black_wall_pressure');
		$average_black_wall = array_sum($black_wall_array) / count($black_wall_array);
		$max_black_wall_pressure = max($black_wall_array);
		$min_black_wall_pressure = min($black_wall_array);
		$returnArr['downs']['black_wall_pressure'] = array(
			'avg' => $average_black_wall,
			'max' => $max_black_wall_pressure,
			'min' => $min_black_wall_pressure,
		);
		/*==============================End Black Wall Pressure=======================================*/

		/*==============================Yellow Wall Pressure==========================================*/
		$yellow_wall_array = array_column($res['downs'], 'yellow_wall_pressure');
		$average_yellow_wall = array_sum($yellow_wall_array) / count($yellow_wall_array);
		$max_yellow_wall_pressure = max($yellow_wall_array);
		$min_yellow_wall_pressure = min($yellow_wall_array);

		$returnArr['downs']['yellow_wall_pressure'] = array(

			'avg' => $average_yellow_wall,
			'max' => $max_yellow_wall_pressure,
			'min' => $min_yellow_wall_pressure,
		);
		/*==============================End Yellow Wall Pressure=======================================*/

		/*================================Depth Pressure===============================================*/
		$depth_array = array_column($res['downs'], 'depth_pressure');
		$average_depth = array_sum($depth_array) / count($depth_array);
		$max_depth_pressure = max($depth_array);
		$min_depth_pressure = min($depth_array);

		$returnArr['downs']['depth_pressure'] = array(
			'avg' => $average_depth,
			'max' => $max_depth_pressure,
			'min' => $min_depth_pressure,
		);
		/*==============================End Depth Pressure=======================================*/

		/*================================Bid Contracts==========================================*/
		$bid_contracts_arr = array_column($res['downs'], 'bid_contracts');
		$average_bids = array_sum($bid_contracts_arr) / count($bid_contracts_arr);
		$max_bids = max($bid_contracts_arr);
		$min_bids = min($bid_contracts_arr);

		$returnArr['downs']['bid_contracts'] = array(
			'avg' => $average_bids,
			'max' => $max_bids,
			'min' => $min_bids,
		);
		/*==============================End Bid Contracts========================================*/

		/*================================Ask Contracts==========================================*/
		$ask_contracts_arr = array_column($res['downs'], 'ask_contract');
		$average_asks = array_sum($ask_contracts_arr) / count($ask_contracts_arr);
		$max_asks = max($ask_contracts_arr);
		$min_asks = min($ask_contracts_arr);

		$returnArr['downs']['ask_contract'] = array(
			'avg' => $average_asks,
			'max' => $max_asks,
			'min' => $min_asks,
		);
		/*==============================End Ask Contracts========================================*/

		/*================================Bid Contracts==========================================*/
		$bid_percentage_arr = array_column($res['downs'], 'bid_percentage');
		$average_bids_per = array_sum($bid_percentage_arr) / count($bid_percentage_arr);
		$max_bids_per = max($bid_percentage_arr);
		$min_bids_per = min($bid_percentage_arr);
		$returnArr['downs']['bid_percentage'] = array(
			'avg' => $average_bids_per,
			'max' => $max_bids_per,
			'min' => $min_bids_per,
		);
		/*==============================End Bid Contracts========================================*/

		/*================================Ask Contracts==========================================*/
		$ask_percentage_arr = array_column($res['downs'], 'ask_percentage');
		$average_asks_per = array_sum($ask_percentage_arr) / count($ask_percentage_arr);
		$max_asks_per = max($ask_percentage_arr);
		$min_asks_per = min($ask_percentage_arr);

		$returnArr['downs']['ask_percentage'] = array(
			'avg' => $average_asks_per,
			'max' => $max_asks_per,
			'min' => $min_asks_per,
		);
		/*==============================End Ask Contracts========================================*/

		/*================================Buyers==========================================*/
		$buyers_arr = array_column($res['downs'], 'buyers');
		$average_asks_per = array_sum($buyers_arr) / count($buyers_arr);
		$max_asks_per = max($buyers_arr);
		$min_asks_per = min($buyers_arr);

		$returnArr['downs']['buyers'] = array(
			'avg' => $average_asks_per,
			'max' => $max_asks_per,
			'min' => $min_asks_per,
		);
		/*==============================End Buyers========================================*/

		/*================================Sellers==========================================*/
		$seller_arr = array_column($res['downs'], 'sellers');
		$average_asks_per = array_sum($seller_arr) / count($seller_arr);
		$max_asks_per = max($seller_arr);
		$min_asks_per = min($seller_arr);

		$returnArr['downs']['sellers'] = array(
			'avg' => $average_asks_per,
			'max' => $max_asks_per,
			'min' => $min_asks_per,
		);
		/*==============================End Sellers========================================*/

		/*================================Sellers==========================================*/
		$buyers_percentage_arr = array_column($res['downs'], 'buyers_percentage');
		$average_asks_per = array_sum($buyers_percentage_arr) / count($buyers_percentage_arr);
		$max_asks_per = max($buyers_percentage_arr);
		$min_asks_per = min($buyers_percentage_arr);

		$returnArr['downs']['buyers_percentage'] = array(
			'avg' => $average_asks_per,
			'max' => $max_asks_per,
			'min' => $min_asks_per,
		);
		/*==============================End Sellers========================================*/

		/*================================Sellers==========================================*/
		$buyers_percentage_arr = array_column($res['downs'], 'sellers_percentage');
		$average_asks_per = array_sum($buyers_percentage_arr) / count($buyers_percentage_arr);
		$max_asks_per = max($buyers_percentage_arr);
		$min_asks_per = min($buyers_percentage_arr);

		$returnArr['downs']['sellers_percentage'] = array(
			'avg' => $average_asks_per,
			'max' => $max_asks_per,
			'min' => $min_asks_per,
		);
		/*==============================End Sellers========================================*/

		/*================================Sellers==========================================*/
		$sellers_buyers_percentage_arr = array_column($res['downs'], 'sellers_buyers_per');
		$average_asks_per = array_sum($sellers_buyers_percentage_arr) / count($sellers_buyers_percentage_arr);
		$max_asks_per = max($sellers_buyers_percentage_arr);
		$min_asks_per = min($sellers_buyers_percentage_arr);

		$returnArr['downs']['sellers_buyers_per'] = array(
			'avg' => $average_asks_per,
			'max' => $max_asks_per,
			'min' => $min_asks_per,
		);
		/*==============================End Sellers========================================*/

		/*================================Great Wall==========================================*/
		$great_wall_array = array_column($res['downs'], 'great_wall_quantity');
		$great_wall_avg = array_sum($great_wall_array) / count($great_wall_array);
		$max_great_wall = max($great_wall_array);
		$min_great_wall = min($great_wall_array);

		$returnArr['downs']['great_wall'] = array(
			'avg' => $great_wall_avg,
			'max' => $max_great_wall,
			'min' => $min_great_wall,
		);
		/*==============================End Great Wall========================================*/

		/*================================Great Wall==========================================*/
		$seven_level_array = array_column($res['downs'], 'seven_level_depth');
		$seven_level_avg = array_sum($seven_level_array) / count($seven_level_array);
		$max_seven_level = max($seven_level_array);
		$min_seven_level = min($seven_level_array);

		$returnArr['downs']['seven_level_depth'] = array(
			'avg' => $seven_level_avg,
			'max' => $max_seven_level,
			'min' => $min_seven_level,
		);

		/*=========================last_qty_buy_vs_sell====================================*/
		$last_qty_buy_vs_sell_arr = array_column($res['downs'], 'last_qty_buy_vs_sell');
		$avg_last_qty_buy_vs_sell = array_sum($last_qty_buy_vs_sell_arr) / count($last_qty_buy_vs_sell_arr);
		$max_last_qty_buy_vs_sell = max($last_qty_buy_vs_sell_arr);
		$min_last_qty_buy_vs_sell = min($last_qty_buy_vs_sell_arr);
		$returnArr['downs']['last_qty_buy_vs_sell (' . number_format_short($contract_quantity) . ')'] = array(
			'avg' => $avg_last_qty_buy_vs_sell,
			'max' => $max_last_qty_buy_vs_sell,
			'min' => $min_last_qty_buy_vs_sell,
		);
		/*=======================End last_qty_buy_vs_sell===============================*/

		/*=========================last_qty_time_ago====================================*/
		$last_qty_time_ago_arr = array_column($res['downs'], 'last_qty_time_ago');
		$avg_last_qty_time_ago = array_sum($last_qty_time_ago_arr) / count($last_qty_time_ago_arr);
		$max_last_qty_time_ago = max($last_qty_time_ago_arr);
		$min_last_qty_time_ago = min($last_qty_time_ago_arr);
		$returnArr['downs']['last_qty_time_ago (' . number_format_short($contract_quantity) . ')'] = array(
			'avg' => $avg_last_qty_time_ago,
			'max' => $max_last_qty_time_ago,
			'min' => $min_last_qty_time_ago,
		);
		/*=======================End last_qty_time_ago===============================*/

		/*=========================last_200_buy_vs_sell====================================*/
		$last_200_buy_vs_sell_arr = array_column($res['downs'], 'last_200_buy_vs_sell');
		$avg_last_200_buy_vs_sell = array_sum($last_200_buy_vs_sell_arr) / count($last_200_buy_vs_sell_arr);
		$max_last_200_buy_vs_sell = max($last_200_buy_vs_sell_arr);
		$min_last_200_buy_vs_sell = min($last_200_buy_vs_sell_arr);
		$returnArr['downs']['last_200_buy_vs_sell'] = array(
			'avg' => $avg_last_200_buy_vs_sell,
			'max' => $max_last_200_buy_vs_sell,
			'min' => $min_last_200_buy_vs_sell,
		);
		/*=======================End last_200_buy_vs_sell===============================*/

		/*=========================last_200_time_ago====================================*/
		$last_200_time_ago_arr = array_column($res['downs'], 'last_200_time_ago');
		$avg_last_200_time_ago = array_sum($last_200_time_ago_arr) / count($last_200_time_ago_arr);
		$max_last_200_time_ago = max($last_200_time_ago_arr);
		$min_last_200_time_ago = min($last_200_time_ago_arr);
		$returnArr['downs']['last_200_time_ago'] = array(
			'avg' => $avg_last_200_time_ago,
			'max' => $max_last_200_time_ago,
			'min' => $min_last_200_time_ago,
		);
		/*=======================End last_200_time_ago===============================*/

		//////////////////////////////////////////////////////////////////////////////////
		/*                    Calculate Up/Down Percentage                                */
		//////////////////////////////////////////////////////////////////////////////////
		/*        $search_arr3['coin'] = $coin_symbol;
			$datetime = date("Y-m-d H:i:s", strtotime("-7 days"));
			$search_arr3['created_date'] = array('$gte' => $this->mongo_db->converToMongodttime($datetime));
			$search_arr3['barrier_type'] = array('$in' => array('up', 'down'));
			$this->mongo_db->where($search_arr3);
			$this->mongo_db->order_by(array('_id' => 1));
			$get3 = $this->mongo_db->get('barrier_values_collection');
			$pre_res3 = iterator_to_array($get3);

			for ($i = 0; $i < count($pre_res3); $i++) {
			$barrier_1 = $pre_res3[$i]['barier_value'];
			$barrier_2 = $pre_res3[$i + 1]['barier_value'];
			$breakable_barrier = $pre_res3[$i]['breakable'];
			if ($breakable_barrier == 'breakable') {
			$breakable++;
			}
			if ($breakable_barrier == 'non_breakable') {
			$non_breakable++;
			}
			$barrier_1_type = $pre_res3[$i]['barrier_type'];
			$barrier_2_type = $pre_res3[$i + 1]['barrier_type'];
			if ($barrier_1_type != $barrier_2_type && ($i < (count($pre_res3) - 1))) {
			$barrier_per[] = number_format(((($barrier_1 - $barrier_2) / $barrier_1) * 100), 2);

			}
			}
	 */

		// echo "<pre>";
		// print_r($returnArr);
		// exit;
		$data['up_indicators'] = $returnArr['ups'];
		$data['down_indicators'] = $returnArr['downs'];
		$data['profit'] = $profit_loss['profit'];
		$data['loss'] = $profit_loss['loss'];

		$data['up_breakable'] = $up_breakable;
		$data['up_non_breakable'] = $up_non_breakable;
		$data['down_breakable'] = $down_breakable;
		$data['down_non_breakable'] = $down_non_breakable;
		$data['coins'] = $this->mod_coins->get_all_coins();
		$this->stencil->paint('admin/coin_meta/listing_indicator', $data);
	}
	public function reset_filters_report($type)
	{
		$this->session->unset_userdata('filter_order_data');
		if ($type == 'coin') {
			redirect(base_url() . 'admin/reports/coin_report');
		}
		if ($type == 'meta') {
			redirect(base_url() . 'admin/reports/meta_coin_report');
		}
		if ($type == 'percentile') {
			redirect(base_url() . 'admin/reports/meta_coin_report_percentile');
		}
		redirect(base_url() . 'admin/reports/order_reports');
	}

	public function order_reports()
	{
		//Login Check
		// ini_set("display_errors", E_ALL);
		// error_reporting(E_ALL);
		$this->mod_login->verify_is_admin_login();
		if ($this->input->post()) {
			$data_arr['filter_order_data'] = $this->input->post();
			$this->session->set_userdata($data_arr);
		}

		$session_data = $this->session->userdata('filter_order_data');
		if (isset($session_data)) {

			$collection = "buy_orders";
			if ($session_data['filter_by_coin']) {
				$search['symbol'] = $session_data['filter_by_coin'];
			}
			if ($session_data['filter_by_mode']) {
				$search['application_mode'] = $session_data['filter_by_mode'];
			}
			if ($session_data['filter_by_trigger']) {
				$search['trigger_type'] = $session_data['filter_by_trigger'];
			}
			if ($session_data['filter_by_rule'] != "") {
				$filter_by_rule = $session_data['filter_by_rule'];
				//$search['$or'] = array("buy_rule_number" => $filter_by_rule, "sell_rule_number" => $filter_by_rule);
				$search['$or'] = array(
					array("buy_rule_number" => intval($filter_by_rule)),
					array("sell_rule_number" => intval($filter_by_rule)),
				);
			}
			if ($session_data['filter_level'] != "") {
				$order_level = $session_data['filter_level'];
				$search['order_level'] = $order_level;
			}
			if ($session_data['filter_username'] != "") {
				$username = $session_data['filter_username'];
				$admin_id = $this->get_admin_id($username);
				$search['admin_id'] = (string) $admin_id;
			}
			if ($session_data['optradio'] != "") {
				if ($session_data['optradio'] == 'created_date') {
					$oder_arr['created_date'] = -1;
				} elseif ($session_data['optradio'] == 'modified_date') {
					$oder_arr['modified_date'] = -1;
				}
			}
			if ($session_data['filter_by_status'] != "") {
				$order_status = $session_data['filter_by_status'];
				if ($order_status == 'new') {
					$search['status'] = 'new';
				} elseif ($order_status == 'error') {
					$search['status'] = 'error';
				} elseif ($order_status == 'open') {
					$search['status'] = array('$in' => array('submitted', 'FILLED'));
					$search['is_sell_order'] = 'yes';
				} elseif ($order_status == 'sold') {
					$search['status'] = 'FILLED';
					$search['is_sell_order'] = 'sold';
					$collection = "sold_buy_orders";
				}
			}
			if ($session_data['filter_by_start_date'] != "" && $session_data['filter_by_end_date'] != "") {

				$created_datetime = date('Y-m-d G:i:s', strtotime($session_data['filter_by_start_date']));
				$orig_date = new DateTime($created_datetime);
				$orig_date = $orig_date->getTimestamp();
				$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

				$created_datetime22 = date('Y-m-d G:i:s', strtotime($session_data['filter_by_end_date']));
				$orig_date22 = new DateTime($created_datetime22);
				$orig_date22 = $orig_date22->getTimestamp();
				$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
				$search['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
			}

			$search['parent_status'] = array('$ne' => 'parent');
			//$search['status'] = array('$ne' => 'canceled');
			// echo "<pre>";
			// print_r($search);
			// exit;

			$connetct = $this->mongo_db->customQuery();

			$sold1_count = $connetct->sold_buy_orders->count($search);
			$pending1_count = $connetct->buy_orders->count($search);

			$total1_count = $sold1_count + $pending1_count;

			$qr_sold = array('skip' => $skip_sold, 'sort' => array('modified_date' => -1), 'limit' => $limit);
			$qr_pending = array('skip' => $skip_pending, 'sort' => array('modified_date' => -1), 'limit' => $limit);

			$sold_count = $connetct->sold_buy_orders->count($search, $qr_sold);
			$pending_count = $connetct->buy_orders->count($search, $qr_pending);

			$total_count = $sold_count + $pending_count;

			/////////////////////// PAGINATION CODE START HERE /////////////////////////////////////
			$this->load->library("pagination");
			$config = array();
			$config["base_url"] = SURL . "admin/reports/order_reports";
			$config["total_rows"] = $total1_count;
			$config['per_page'] = 250;
			$config['num_links'] = 3;
			$config['use_page_numbers'] = TRUE;
			$config['uri_segment'] = 4;
			$config['reuse_query_string'] = TRUE;
			$config["first_tag_open"] = '<li>';
			$config["first_tag_close"] = '</li>';
			$config["last_tag_open"] = '<li>';
			$config["last_tag_close"] = '</li>';
			$config['next_link'] = '&raquo;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['prev_link'] = '&laquo;';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['first_link'] = 'First';
			$config['last_link'] = 'Last';
			$config['full_tag_open'] = '<ul class="pagination">';
			$config['full_tag_close'] = '</ul>';
			$config['cur_tag_open'] = '<li class="active"><a href="#"><b>';
			$config['cur_tag_close'] = '</b></a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$this->pagination->initialize($config);
			$page = $this->uri->segment(4);

			if (!isset($page)) {
				$page = 1;
			}
			$start = ($page - 1) * $config["per_page"];
			$skip = $start;
			$skip_sold = $skip;
			$skip_pending = $skip;
			$limit = $config["per_page"];
			////////////////////////////End Pagination Code///////////////////////////////////////

			$data['pagination'] = $this->pagination->create_links();

			/////////////////////// PAGINATION CODE END HERE /////////////////////////////////////
			$sold_percentage = ($sold_count / $total_count) * 100;
			$pending_percentage = ($pending_count / $total_count) * 100;

			$pending_limit = (500 / 100) * $pending_percentage;
			$sold_limit = (500 / 100) * $sold_percentage;

			$pending_options = array('skip' => $skip_pending, 'sort' => array('modified_date' => -1), 'limit' => intval($pending_limit));

			$sold_options = array('skip' => $skip_sold, 'sort' => array('modified_date' => -1), 'limit' => intval($sold_limit));

			// $skip_sold = $skip_sold +(int)$sold_limit;
			// $skip_pending = $skip_pending +(int)$pending_limit;
			// $this->session->set_userdata(array('skip_sold'=>$skip_sold,'skip_pending'=>$skip_pending));

			$pending_curser = $connetct->buy_orders->find($search, $pending_options);
			$sold_curser = $connetct->sold_buy_orders->find($search, $sold_options);

			$pending_arr = iterator_to_array($pending_curser);
			$sold_arr = iterator_to_array($sold_curser);
			$orders = array_merge_recursive($pending_arr, $sold_arr);

			foreach ($orders as $key => $part) {
				$sort[$key] = (string) $part['modified_date'];
			}

			array_multisort($sort, SORT_DESC, $orders);

			$new_order_arrray = array();
			foreach ($orders as $order) {
				$id = $order['admin_id'];
				$data_user = $this->get_username_from_user($id);
				$order['admin'] = $data_user;
				$_id = $order['_id'];

				$error = $this->get_error_type($_id);
				$order['log'] = $error;
				array_push($new_order_arrray, $order);
			}
			// echo "<pre>";
			// print_r($new_order_arrray);exit;
			// $new_order_arrray['average'] = $test_arr;
			$data['orders'] = $new_order_arrray;
		}
		$coins = $this->mod_coins->get_all_coins();
		$data['coins'] = $coins;
		$this->stencil->paint('admin/reports/my_custom_order_report', $data);
	} //End of order_reports

	// working on order_reports_admin

	public function order_reports_admin()
	{

		//Login Check
		$this->mod_login->verify_is_admin_login();
		if ($this->input->post()) {

			$data_arr['filter_order_data'] = $this->input->post();
			$this->session->set_userdata($data_arr);
			$collection = "buy_orders";
			if ($this->input->post('filter_by_coin')) {
				$search['symbol'] = $this->input->post('filter_by_coin');
			}
			if ($this->input->post('filter_by_mode')) {
				$search['application_mode'] = $this->input->post('filter_by_mode');
			}
			if ($this->input->post('filter_by_trigger')) {
				$search['trigger_type'] = $this->input->post('filter_by_trigger');
			}
			if ($this->input->post('filter_level') != "") {
				$order_level = $this->input->post('filter_level');
				$search['order_level'] = $order_level;
			}
			if ($this->input->post('filter_username') != "") {
				$username = $this->input->post('filter_username');
				$admin_id = $this->get_admin_id($username);
				$search['admin_id'] = (string) $admin_id;
			}
			if ($this->input->post('optradio') != "") {
				if ($this->input->post('optradio') == 'created_date') {
					$oder_arr['created_date'] = -1;
				} elseif ($this->input->post('optradio') == 'modified_date') {
					$oder_arr['modified_date'] = -1;
				}
			}
			if ($this->input->post('filter_by_status') != "") {
				$order_status = $this->input->post('filter_by_status');
				if ($order_status == 'new') {
					$search['status'] = 'new';
				} elseif ($order_status == 'error') {
					$search['status'] = 'error';
				} elseif ($order_status == 'open') {
					$search['status'] = array('$in' => array('submitted', 'FILLED'));
					$search['is_sell_order'] = 'yes';
				} elseif ($order_status == 'sold') {
					$search['status'] = 'FILLED';
					$search['is_sell_order'] = 'sold';
					$collection = "sold_buy_orders";
				}
			}
			if ($_POST['filter_by_start_date'] != "" && $_POST['filter_by_end_date'] != "") {

				$created_datetime = date('Y-m-d G:i:s', strtotime($_POST['filter_by_start_date']));
				$orig_date = new DateTime($created_datetime);
				$orig_date = $orig_date->getTimestamp();
				$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

				$created_datetime22 = date('Y-m-d G:i:s', strtotime($_POST['filter_by_end_date']));
				$orig_date22 = new DateTime($created_datetime22);
				$orig_date22 = $orig_date22->getTimestamp();
				$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
				$search['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
			}

			$search['parent_status'] = array('$ne' => 'parent');
			//$search['status'] = array('$ne' => 'canceled');

			$connetct = $this->mongo_db->customQuery();
			$pending_curser = $connetct->buy_orders->find($search);
			$sold_curser = $connetct->sold_buy_orders->find($search);

			$pending_arr = iterator_to_array($pending_curser);
			$sold_arr = iterator_to_array($sold_curser);

			$orders = array_merge_recursive($pending_arr, $sold_arr);

			foreach ($orders as $key => $part) {
				$sort[$key] = (string) $part['modified_date'];
			}

			array_multisort($sort, SORT_DESC, $orders);

			foreach ($orders as $key => $value) {

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

			$test_arr['total_sold_orders'] = $total_sold_orders;
			$test_arr['avg_profit'] = number_format($avg_profit, 2, '.', '');

			$new_order_arrray = array();
			foreach ($orders as $order) {
				$id = $order['admin_id'];
				$data_user = $this->get_username_from_user($id);
				$order['admin'] = $data_user;
				$_id = $order['_id'];

				$error = $this->get_error_type($_id);
				$order['log'] = $error;
				array_push($new_order_arrray, $order);
			}
			// echo "<pre>";
			// print_r($new_order_arrray);exit;

			$order_arr['new_order_arr'] = $new_order_arrray;
			$order_arr['average'] = $test_arr;
			$data['full_arr'] = $order_arr;
		}

		$coins = $this->mod_coins->get_all_coins();
		$data['coins'] = $coins;

		$this->stencil->paint('admin/reports/my_custom_order_report2', $data);
	}

	// end of order_book_admin

	public function get_username_from_user($id)
	{

		// echo $id;
		// echo "<br>";
		if (preg_match('/^[a-f\d]{24}$/i', $id)) {
			$customer = $this->mod_report->get_customer($this->mongo_db->mongoId($id));
		}
		// $customer_name = ucfirst($customer['first_name']).' '.ucfirst($customer['last_name']);
		// $customer_username = $customer['username'];

		return $customer;
	}

	public function get_admin_id($username)
	{
		$customer = $this->mod_report->get_customer_by_username($username);
		return $customer['_id'];
	}

	function get_all_usernames_ajax()
	{
		$this->mongo_db->sort(array('_id' => -1));
		$get_users = $this->mongo_db->get('users');

		$users_arr = iterator_to_array($get_users);

		$user_name_array = array_column($users_arr, 'username');

		echo json_encode($user_name_array);
		exit;
	}
	public function get_user_info()
	{
		$id = $this->input->post('user_id');
		$customer = $this->mod_report->get_customer($id);

		$response = '<div class="col-12 col-sm-6 col-md-4 col-lg-12">
								<div class="our-team">
								<div class="picture">
									<img class="img-fluid" src="' . SURL . "assets/profile_images/" . (!empty($customer['profile_image']) ? $customer['profile_image'] : "user.png") . '">
								</div>
								<div class="team-content">
									<h3 class="name">' . ucfirst($customer['first_name']) . ' ' . ucfirst($customer['last_name']) . '</h3>
											<h5><span class="label label-info">@' . $customer['username'] . '</span></h5>
									<h4 class="title">Last Login: ' . date("jS F Y H:i:s", strtotime($customer['last_login_datetime'])) . '</h4>
								</div>
								</div>
							</div>
								<div class="table-responsive">
										<table class="table">
											<tr>
												<th>User Id</td>
												<td>' . $customer['_id'] . '</td>
											<tr>

											<tr>
												<th>Email Address</td>
												<td>' . $customer['email_address'] . '</td>
											<tr>

											<tr>
												<th>Trading Ip</td>
												<td>' . $customer['trading_ip'] . '</td>
											<tr>
											<tr>
												<th>Application Mode</td>
												<td>' . $customer['application_mode'] . '</td>
											<tr>
											<tr>
												<th></td>
												<td>' . (($customer['special_role'] == 1) ? "<label class='label label-success'>Special User</label>" : "<label class='label label-warning'>Normal User</label>") . '</td>
											<tr>
										</table>
									</div>';

		echo $response;
		exit;
	}
	
	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////


	
	public function csv_export_oppertunity(){
		$coin_array_all = $this->mod_coins->get_all_coins();
		
		$previous_5_day_time = date('Y-m-d H:i:s', strtotime('-30 days'));
		$starting_date =  $this->mongo_db->converToMongodttime($previous_5_day_time);
			
		$coin_array = array_column($coin_array_all, 'symbol');
		$where['coin']['$in'] =  $coin_array;
		$where['level'] = array('$ne'=>'level_15');
		$where['created_date'] = array('$gte'=>$starting_date);
		
		$this->mongo_db->sort(array('created_date' => -1));
		$this->mongo_db->where($where);
		$object_return = $this->mongo_db->get('opportunity_logs_binance');
		$log_arry = iterator_to_array($object_return);

		$this->download_send_headers("oppertunity_report". date("Y-m-d_ Gisa") . ".csv");
		echo $this->array2csv($log_arry);
	}
	
	public function oppertunity_reports(){
		$this->mod_login->verify_is_admin_login();
		$db =  $this->mongo_db->customQuery();
		
		$coin_array_all = $this->mod_coins->get_all_coins();
		if ($this->input->post()){
			$data_arr['filter_order_data'] = $this->input->post();
			$this->session->set_userdata($data_arr);
			$coin_array = array();

			if(!empty($this->input->post('coinPair')) && $this->input->post('coinPair')!= 'both'){
				if($this->input->post('coinPair') == "btc"){
					$coin_array  = ['XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC'];
				}else{
					$coin_array = ['EOSUSDT', 'LTCUSDT','XRPUSDT','NEOUSDT', 'QTUMUSDT','BTCUSDT'];
				}
			}elseif (!empty($this->input->post('filter_by_coin'))) {
				$coin_array = $this->input->post('filter_by_coin');
			} else {
				$coin_array = array_column($coin_array_all, 'symbol');
			}
			

			if ($this->input->post('filter_by_mode')) {
				$search['mode'] = $this->input->post('filter_by_mode');
			}
			if ($this->input->post('filter_by_trigger') != "") {
				$filter_by_trigger = $this->input->post('filter_by_trigger');
			} else {
				$filter_by_trigger = array('barrier_percentile_trigger', 'barrier_trigger', 'no');
			}

			if ($this->input->post('filter_by_level') != "" && $filter_by_trigger == 'barrier_percentile_trigger') {
				$filter_by_level = $this->input->post('filter_by_level');
				$search['level']['$in'] = $filter_by_level;
			} else {
				$search['level']['$in'] = array('level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18');
			}
			if ($this->input->post('filter_by_rule') != "" && $filter_by_trigger == 'barrier_trigger') {
				$filter_by_rule = $this->input->post('filter_by_rule');
				
			} else {
			}
			if ($_POST['oppertunity_Id'] != "") {
				$filter_by_oppertunity_id		= $this->input->post('oppertunity_Id');
				$search['opportunity_id'] =  $filter_by_oppertunity_id;
			}
			
			if ($_POST['oppertunity_Id'] != "") {
				$filter_by_oppertunity_id		= $this->input->post('oppertunity_Id');
				$search['opportunity_id'] =  $filter_by_oppertunity_id;
			}
			$exchange = (!empty($_POST['exchange']))? $this->input->post('exchange'): 'binance';
			
			$collection = ($this->input->post('filter_by_mode') == 'live') ? "opportunity_logs_".$exchange : "opportunity_logs_test_".$exchange;

			if ($_POST['filter_by_start_date'] != "" &&  $_POST['filter_by_end_date'] != ""){

				$dayssss=((strtotime($_POST['filter_by_end_date'])-strtotime($_POST['filter_by_start_date']))/3600)/24;
				// echo "\r\n days".$dayssss;

				$time = '7:49:00';
				$newtime =  date('H:i:s', strtotime($time));
				

				for($i = 0; $i < $dayssss ; $i++){

					$startingTime      =  date('Y-m-d',   strtotime($_POST['filter_by_start_date'] . + $i .'days'));
					$combine123 = date($startingTime .' H:i:s',     strtotime($newtime));
					$starting = date('Y-m-d H:i:s', strtotime($combine123));
								
				  
					$endTime      =  date('Y-m-d',   strtotime($startingTime . + 1 .'days'));
					$combineEnd = date($endTime .' H:i:s',     strtotime($newtime));
					$end = date('Y-m-d H:i:s', strtotime($combineEnd));

					$getCountTradeTime   = date('Y-m-d', strtotime($starting. '+1 days'));
					// expected_trade_buy_count_history
					$getTradeNumberCount = [
						'exchange'  	=>  $exchange,
						'created_date'	=>  $this->mongo_db->converToMongodttime($getCountTradeTime)
					];

					$getTradeCount 		= 	$db->expected_trade_buy_count_history->find($getTradeNumberCount);
					$countResponse[]	= 	iterator_to_array($getTradeCount);


					$search['coin']['$in'] = $coin_array;
					$starting_date =  $this->mongo_db->converToMongodttime($starting);
					$ending_date =  $this->mongo_db->converToMongodttime($end);

					
					$search['created_date'] = array('$gte'=>$starting_date, '$lte'=> $ending_date);
					$this->mongo_db->sort(array('created_date' => -1));
					$this->mongo_db->where($search);
					$object_return = $this->mongo_db->get($collection);
					$log_arry[] = iterator_to_array($object_return);


					//get trade count
					$countTradeLookupBTC = [
						[
							'$match' =>[ 
								'coin'			=> 	['$in' 	=>  ['XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC']],
								'level' 		=> 	['$in' 	=> 	['level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18']],  
								'created_date' 	=> 	['$gte'	=>	$starting_date, '$lte'=> $ending_date]
							]
						],
						[
							'$group' => [
								'_id' => 1, 
								'sold_orders' 		=> 	['$sum' => '$sold'],
								'open_lth_orders' 	=> 	['$sum' => '$open_lth'],
								'otherStatus'    	=>	['$sum' => '$other_status']
							]
						],
						[
							'$sort' => ['created_date' => -1]
						]	
					];
	
					$countTradeLookupUSDT = [
						[
							'$match' =>[ 
								'coin'			=> 	['$in' 	=>  ['EOSUSDT', 'LTCUSDT','XRPUSDT','NEOUSDT', 'QTUMUSDT','BTCUSDT']],
								'level' 		=> 	['$in' 	=> 	['level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18']],  
								'created_date' 	=> 	['$gte'	=>	$starting_date, '$lte'=> $ending_date]
							]
						],
	
						[
							'$group' => [
								'_id' => 1, 
								'sold_orders' 		=> 	['$sum' => '$sold'],
								'open_lth_orders' 	=> 	['$sum' => '$open_lth'],
								'otherStatus'    	=>	['$sum' => '$other_status']
							]
						],
						[
							'$sort' => ['created_date' => -1]
						]	
					];
	
					$getCountTradesBTC   = $db->$collection->aggregate($countTradeLookupBTC);
					$getCountTradesBTC1[] = iterator_to_array($getCountTradesBTC);
	
					$getCountTradesUSDT   = $db->$collection->aggregate($countTradeLookupUSDT);
					$getCountTradesUSDT1[] = iterator_to_array($getCountTradesUSDT);
					// $days++;
				}
				
				$data['final_array'] 			= 		$log_arry;
				$data['expectedTrades'] 		= 		$countResponse;
				$data['tradeCountDaily']    	=     	$getCountTradesBTC1;
				$data['tradeCountDailyUSDT']  	= 		$getCountTradesUSDT1;

			}else{

				for($i = 0; $i < 11 ; $i++){
					$days = 10 -$i;
					$startTime = date('Y-m-d 07:59', strtotime(- $days.'days'));
					$endTime   = date('Y-m-d 07:59', strtotime($startTime. '+1 days'));


					$getCountTradeTime   = date('Y-m-d', strtotime($startTime. '+1 days'));
					// expected_trade_buy_count_history
					$getTradeNumberCount = [
						'exchange'  	=>  $exchange,
						'created_date'	=>  $this->mongo_db->converToMongodttime($getCountTradeTime)
					];

					$getTradeCount 		= 	$db->expected_trade_buy_count_history->find($getTradeNumberCount);
					$countResponse[]	= 	iterator_to_array($getTradeCount);


					$starting_date =  $this->mongo_db->converToMongodttime($startTime);
					$ending_date =  $this->mongo_db->converToMongodttime($endTime);

					$search['coin']['$in'] = $coin_array;
					$search['created_date'] = array('$gte'=>$starting_date, '$lte'=> $ending_date);
					$this->mongo_db->sort(array('created_date' => -1));
					$this->mongo_db->where($search);
					$object_return = $this->mongo_db->get($collection);
					$log_arry[] = iterator_to_array($object_return);


					$countTradeLookupBTC = [
						[
							'$match' =>[ 
								'coin'			=> 	['$in' 	=>  ['XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC']],
								'level' 		=> 	['$in' 	=> 	['level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18']],  
								'created_date' 	=> 	['$gte'	=>	$starting_date, '$lte'=> $ending_date]
							]
						],
						[
							'$group' => [
								'_id' => 1, 
								'sold_orders' 		=> 	['$sum' => '$sold'],
								'open_lth_orders' 	=> 	['$sum' => '$open_lth'],
								'otherStatus'    	=>	['$sum' => '$other_status']
							]
						],
						[
							'$sort' => ['created_date' => -1]
						]	
					];
	
					$countTradeLookupUSDT = [
						[
							'$match' =>[ 
								'coin'			=> 	['$in' 	=>  ['EOSUSDT', 'LTCUSDT','XRPUSDT','NEOUSDT', 'QTUMUSDT','BTCUSDT']],
								'level' 		=> 	['$in' 	=> 	['level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18']],  
								'created_date' 	=> 	['$gte'	=>	$starting_date, '$lte'=> $ending_date]
							]
						],
	
						[
							'$group' => [
								'_id' => 1, 
								'sold_orders' 		=> 	['$sum' => '$sold'],
								'open_lth_orders' 	=> 	['$sum' => '$open_lth'],
								'otherStatus'    	=>	['$sum' => '$other_status']
							]
						],
						[
							'$sort' => ['created_date' => -1]
						]	
					];
	
					$getCountTradesBTC   = $db->$collection->aggregate($countTradeLookupBTC);
					$getCountTradesBTC1[] = iterator_to_array($getCountTradesBTC);
	
					$getCountTradesUSDT   = $db->$collection->aggregate($countTradeLookupUSDT);
					$getCountTradesUSDT1[] = iterator_to_array($getCountTradesUSDT);
	
				}
			}
			$data['tradeCountDaily']    	=     	$getCountTradesBTC1;
			$data['tradeCountDailyUSDT']  	= 		$getCountTradesUSDT1;
			$data['final_array'] 			= 		$log_arry;
			$data['expectedTrades'] 		= 		$countResponse;
		}
		////////////////////////////////////////////////////////////////////////////
		else{
			$coin_array = array_column($coin_array_all, 'symbol');
			$this->session->unset_userdata('filter_order_data');

			
			for($i = 0; $i < 11 ; $i++){
				$days = 10 -$i;
				$startTime = date('Y-m-d 07:59', strtotime(- $days.'days'));
				$endTime   = date('Y-m-d 07:59', strtotime($startTime. '+1 days'));
				$starting_date =  $this->mongo_db->converToMongodttime($startTime);
				$ending_date =  $this->mongo_db->converToMongodttime($endTime);


				$getCountTradeTime   = date('Y-m-d', strtotime($startTime. '+1 days'));
					// expected_trade_buy_count_history
				$getTradeNumberCount = [
					'exchange'  	=>  'kraken',
					'created_date'	=>  $this->mongo_db->converToMongodttime($getCountTradeTime)
				];

				$getTradeCount 		= 	$db->expected_trade_buy_count_history->find($getTradeNumberCount);
				$countResponse[]	= 	iterator_to_array($getTradeCount);


				$where['coin']['$in'] =  $coin_array;
				$where['level']['$in'] = array('level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18');  
				$where['created_date'] = array('$gte'=>$starting_date, '$lte'=> $ending_date);
				$this->mongo_db->sort(array('created_date' => -1));
				$this->mongo_db->where($where);
				$object_return = $this->mongo_db->get('opportunity_logs_kraken');
				$log_arry[] = iterator_to_array($object_return);

				//trade count today buy

				$countTradeLookupBTC = [
					[
						'$match' =>[ 
							'coin'			=> 	['$in' 	=>  ['XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC']],
							'level' 		=> 	['$in' 	=> 	['level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18']],  
							'created_date' 	=> 	['$gte'	=>	$starting_date, '$lte'=> $ending_date]
						]
					],
					[
						'$group' => [
							'_id' => 1, 
							'sold_orders' 		=> 	['$sum' => '$sold'],
							'open_lth_orders' 	=> 	['$sum' => '$open_lth'],
							'otherStatus'    	=>	['$sum' => '$other_status']
						]
					],
					[
						'$sort' => ['created_date' => -1]
					]	
				];

				$countTradeLookupUSDT = [
					[
						'$match' =>[ 
							'coin'			=> 	['$in' 	=>  ['EOSUSDT', 'LTCUSDT','XRPUSDT','NEOUSDT', 'QTUMUSDT','BTCUSDT']],
							'level' 		=> 	['$in' 	=> 	['level_5','level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18']],  
							'created_date' 	=> 	['$gte'	=>	$starting_date, '$lte'=> $ending_date]
						]
					],

					[
						'$group' => [
							'_id' => 1, 
							'sold_orders' 		=> 	['$sum' => '$sold'],
							'open_lth_orders' 	=> 	['$sum' => '$open_lth'],
							'otherStatus'    	=>	['$sum' => '$other_status']
						]
					],
					[
						'$sort' => ['created_date' => -1]
					]	
				];

				$getCountTradesBTC   = $db->opportunity_logs_kraken->aggregate($countTradeLookupBTC);
				$getCountTradesBTC1[] = iterator_to_array($getCountTradesBTC);

				$getCountTradesUSDT   = $db->opportunity_logs_kraken->aggregate($countTradeLookupUSDT);
				$getCountTradesUSDT1[] = iterator_to_array($getCountTradesUSDT);

			}
			$data['final_array'] 			= 		$log_arry;
			$data['expectedTrades'] 		= 		$countResponse;
			$data['tradeCountDaily']    	=     	$getCountTradesBTC1;
			$data['tradeCountDailyUSDT']  	= 		$getCountTradesUSDT1;
		}

		$symbol = array_column($coin_array_all, 'symbol');
		array_multisort($symbol, SORT_ASC, $coin_array_all);
		$data['coins'] = $coin_array_all;
		$this->stencil->paint('admin/trigger_rule_report/oppertunity_report', $data);
	}

	/////////////////////////////////////////////////////////////////////////////
	///////////////                ASIM CRONE BINANCE             ///////////////
	/////////////////////////////////////////////////////////////////////////////

	// inserting latest opportunity in the log collection binance
	public function insert_latest_oppertunity_into_log_collection_binance(){
		$marketPrices = marketPrices('binance');
		$this->load->helper('new_common_helper');
		foreach($marketPrices as $price){
			if($price['_id'] == 'ETHBTC'){
				$ethbtc = (float)$price['price'];
			}elseif($price['_id'] == 'BTCUSDT'){
				$btcusdt = (float)$price['price'];
			}elseif($price['_id'] == 'XRPBTC'){
				$xrpbtc = (float)$price['price'];
			}elseif($price['_id'] == 'XRPUSDT'){
				$xrpusdt = (float)$price['price'];
			}elseif($price['_id'] == 'NEOBTC'){
				$neobtc = (float)$price['price'];
			}elseif($price['_id'] == 'NEOUSDT'){
				$neousdt = (float)$price['price'];
			}elseif($price['_id'] == 'QTUMBTC'){
				$qtumbtc = (float)$price['price'];
			}elseif($price['_id'] == 'QTUMUSDT'){
				$qtumusdt = (float)$price['price'];
			}elseif($price['_id'] == 'XLMBTC'){
				$xml = (float)$price['price'];
			}elseif($price['_id'] == 'XEMBTC'){
				$xem = (float)$price['price'];
			}elseif($price['_id'] == 'POEBTC'){
				$poe = (float)$price['price'];
			}elseif($price['_id'] == 'TRXBTC'){
				$trx = (float)$price['price'];
			}elseif($price['_id'] == 'ZENBTC'){
				$zen = (float)$price['price'];
			}elseif($price['_id'] == 'ETCBTC'){
				$etcbtc = (float)$price['price'];
			}elseif($price['_id'] =='EOSBTC'){
				$eosbtc = (float)$price['price'];
			}elseif($price['_id'] =='LINKBTC'){
				$linkbtc = (float)$price['price'];
			}elseif($price['_id'] =='DASHBTC'){
				$dashbtc = (float)$price['price'];
			}elseif($price['_id'] =='XMRBTC'){
				$xmrbtc = (float)$price['price'];
			}elseif($price['_id'] =='ADABTC'){
				$adabtc = (float)$price['price'];
			}elseif($price['_id'] =='LTCUSDT'){
				$ltcusdt = (float)$price['price'];
			}elseif($price['_id'] =='EOSUSDT'){
				$eosusdt = (float)$price['price'];
			}				
		}//end inner loop 
		$current_date_time =  date('Y-m-d H:i:s');
		$current_time_date =  $this->mongo_db->converToMongodttime($current_date_time);

		$current_hour =  date('Y-m-d H:i:s', strtotime('-40 minutes'));
		$orig_date1 = $this->mongo_db->converToMongodttime($current_hour);

		$previous_one_month_date_time = date('Y-m-d H:i:s', strtotime(' - 1 month'));
		$pre_date_1 =  $this->mongo_db->converToMongodttime($previous_one_month_date_time);

		$connection = $this->mongo_db->customQuery();      
		$condition = array('sort' => array('created_date' => -1), 'limit'=>3);
		 if(!empty($this->input->get())){
			$where['opportunity_id'] = $this->input->get('opportunityId');
		}else{
			$where['mode'] ='live';
			$where['created_date'] = array('$gte'=>$pre_date_1);
			$where['level'] = array('$ne'=>'level_15');
			$where['is_modified'] = array('$exists'=>false);
			$where['modified_date'] = array('$lte'=>$orig_date1);
		} 
                 
		$find_rec = $connection->opportunity_logs_binance->find($where,  $condition);
		$response = iterator_to_array($find_rec);
		foreach ($response as $value){
			$coin= $value['coin'];
			if(isset($value['sendHitTime']) && !empty($value['sendHitTime'])){
				$sendHitTime     	= strtotime($value['sendHitTime']->toDateTime()->format('Y-m-d H:i:s'));   

			}else{
				$sendHitTime     	= strtotime($value['created_date']->toDateTime()->format('Y-m-d H:i:s'));
			}
			$start_date = $value['created_date']->toDateTime()->format("Y-m-d H:i:s");
			$timestamp = strtotime($start_date);
			$time = $timestamp + (5 * 60 * 60);
			$end_date = date("Y-m-d H:i:s", $time);

			$hours_10 = $timestamp + (10 * 60 * 60);
			$time_10_hours = date("Y-m-d H:i:s", $hours_10);

			$cidition_check = $this->mongo_db->converToMongodttime($end_date);
			$cidition_check_10 = $this->mongo_db->converToMongodttime($time_10_hours);
			$params = [];
			$params = [   
				'coin'       => $value['coin'],
				'start_date' => (string)$start_date,
				'end_date'   => (string)$end_date,
			];	

			if($cidition_check <= $current_time_date){
				$jsondata = json_encode($params);
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => "http://35.171.172.15:3000/api/minMaxMarketPrices",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS =>$jsondata,
					CURLOPT_HTTPHEADER => array("Content-Type: application/json"), 
				));
				$response_price = curl_exec($curl);	
				curl_close($curl);                                
				$api_response = json_decode($response_price);
				echo "<pre>";
				print_r($api_response);
			}// main if check for time comapire
			$params_10_hours = [];         
			$params_10_hours = [
				'coin'       => $value['coin'],
				'start_date' => (string)$start_date,
				'end_date'   => (string)$time_10_hours,
			];
			if($cidition_check_10 <= $current_time_date){
				$jsondata = json_encode($params_10_hours);
				$curl_10 = curl_init();
				curl_setopt_array($curl_10, array(
					CURLOPT_URL => "http://35.171.172.15:3000/api/minMaxMarketPrices",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS =>$jsondata,
					CURLOPT_HTTPHEADER => array("Content-Type: application/json"), 
				));
				$response_price_10 = curl_exec($curl_10);	
				curl_close($curl_10);
				$api_response_10 = json_decode($response_price_10);
				echo "<pre>";
				print_r($api_response_10);
			}
			if($value['level'] != 'level_15' ) {
				$open_lth_avg_per_trade = 0;
				$open_lth_avg = 0;
				$avg_sold = 0;
				$parents_executed = 0;
				$parents_executed = $value['parents_executed'];
				
				$search_update['opportunity_id'] = $value['opportunity_id'];
				$search_update['mode']= 'live';
				
				$other['application_mode']= 'live';
				$other['opportunityId'] =  $value['opportunity_id'];
				$other['status'] = array('$nin' => array('LTH', 'FILLED','canceled','new_ERROR'));

				$buyOther = $connection->buy_orders->count($other);
				/////////////////////////////////////////////////////////

				$search_open_lth['application_mode']		= 	'live';
				$search_open_lth['opportunityId'] 			= 	$value['opportunity_id'];
				$search_open_lth['status'] 					= 	array('$in' => array('LTH', 'FILLED'));
				$search_open_lth['is_sell_order']         	=   'yes';
				$search_open_lth['cavg_parent'] 			= 	['$exists' => false];
				$search_open_lth['cost_avg']				=	['$nin' => ['yes', 'completed', '']];


				print_r("<br>oppertunity_id=".$value['opportunity_id']);
				/////
				$search_cancel['application_mode']		= 	'live';
				$search_cancel['opportunityId'] 		= 	$value['opportunity_id'];
				$search_cancel['status'] 				= 	array('$in' => array('canceled'));
				$search_cancel['cavg_parent'] 			= 	['$exists' => false];
				$search_cancel['cost_avg']				=	['$nin' => ['yes', 'completed', '']];
				//////
				$search_new_error['application_mode']	= 'live';
				$search_new_error['opportunityId'] 		= $value['opportunity_id'];
				$search_new_error['status'] 			= array('$in' => array('new_ERROR'));
				$search_new_error['cavg_parent'] 		= 	['$exists' => false];
				$search_new_error['cost_avg']			=	['$nin' => ['yes', 'completed', '']];
				////////
				$search_sold['application_mode']	= 'live';
				$search_sold['opportunityId'] 		= $value['opportunity_id'];
				$search_sold['is_sell_order'] 		= 'sold';
				$search_sold['cavg_parent'] 	= 	['$exists' => false];
				$search_sold['cost_avg']		=	['$nin' => ['yes', 'completed', '']];
				// $search_sold['resume_status']['$ne'] = 'resume';
				// $search_sold['cost_avg']['$ne'] = 'yes';

				$otherSold['application_mode'] 	= 	'live';
				$otherSold['opportunityId'] 	=  	$value['opportunity_id'];
				$otherSold['is_sell_order'] 	= 	array('$nin' => array('sold'));
				$searchSold['cavg_parent'] 		= 	['$exists' => false];
				$searchSold['cost_avg']			=	['$nin' => ['yes', 'completed', '']];

				$otherStatusSold = $connection->sold_buy_orders->count($otherSold);
				$totalOther = $buyOther + $otherStatusSold;

				$minPriceLookUp = [
					[
						'$match' => [
							'application_mode' => 'live',
							'opportunityId'    =>  $value['opportunity_id'],
							'is_sell_order'    =>  'sold'
 						]
					],

					[
						'$group' =>[
							'_id' => '$symbol',
							'minPrice' => ['$min' => '$market_sold_price']
						]
					],

				];

				$minSoldPrice = $connection->sold_buy_orders->aggregate($minPriceLookUp);
				$soldMinPrice  = iterator_to_array($minSoldPrice);

				$maxPriceLookUp = [
					[
						'$match' => [
							'application_mode' => 'live',
							'opportunityId'    =>  $value['opportunity_id'],
							'is_sell_order'    =>  'sold'
 						]
					],

					[
						'$group' =>[
							'_id' => '$symbol',
							'maxPrice' => ['$max' => '$market_sold_price']
						]
					],

				];

				$maxSoldPrice = $connection->sold_buy_orders->aggregate($maxPriceLookUp);
				$soldMaxPrice  = iterator_to_array($maxSoldPrice);
				
				///////////////////////////////////////////////////////////////////
		
				$search_resumed['application_mode']	= 	'live';
				$search_resumed['opportunityId'] 	= 	$value['opportunity_id'];
				$search_resumed['resume_status'] 	= 	array('$in' => array('resume'));
				$search_resumed['cavg_parent'] 		= 	['$exists' => false];
				$search_resumed['cost_avg']			=	['$nin' => ['yes', 'completed', '']];
				
				/////////////////////////////////////////////////////////////// 
				$cosAvg['application_mode']	= 	'live';
				$cosAvg['opportunityId'] 	= 	$value['opportunity_id'];
				$cosAvg['cost_avg']['$ne'] 	= 	'completed';
				$cosAvg['is_sell_order']	= 	'yes';
				$cosAvg['cavg_parent'] 		= 	'yes';

				$cosAvgSold['application_mode']	= 'live';
				$cosAvgSold['opportunityId'] 	= $value['opportunity_id'];
				$cosAvgSold['is_sell_order'] 	= 'sold';
				$cosAvgSold['cost_avg'] 		= 'completed';
				$cosAvgSold['cavg_parent'] 		= 'yes';
				
				$costAvgReturn = $connection->buy_orders->count($cosAvg);
				$soldCostAvgReturn = $connection->sold_buy_orders->count($cosAvgSold);  
				/////////////////////////////////////////////////////////////// 
				/////////////////////////////////////////////////////////////// 
				$cosAvg_parent['application_mode']	= 	'live';
				$cosAvg_parent['symbol'] 	= 	$value['coin'];
				$cosAvg_parent['cost_avg']['$ne'] 	= 	'completed';
				$cosAvg_parent['is_sell_order']	= 	'yes';
				$cosAvg_parent['cavg_parent'] 		= 	'yes';
				
				$costAvgparent = $connection->buy_orders->count($cosAvg_parent);
				/////////////////////////////////////////////////////////////// 
				/////////////////////////////////////////////////////////////// 
				$cosAvg_child['application_mode']	= 'live';
				$cosAvg_child['opportunityId'] 	    = $value['opportunity_id'];
				$cosAvg_child['cost_avg']     = ['$in' => ['yes','taking_child']];
				$cosAvg_child['is_sell_order']	    = ['$ne'=>'sold'];
				$cosAvg_child['status']	    = ['$ne'=>'canceled'];
				$cosAvg_child['cavg_parent'] 		= ['$exists' => false];
				$cosAvg_child['parent_status'] 		= ['$exists' => false];

				$cosAvg_child_canceled['application_mode']	= 'live';
				$cosAvg_child_canceled['opportunityId'] 	    = $value['opportunity_id'];
				$cosAvg_child_canceled['cost_avg']     = ['$in' => ['yes','taking_child']];
				$cosAvg_child_canceled['is_sell_order']	    = ['$ne'=>'sold'];
				$cosAvg_child_canceled['status']	    = ['$eq'=>'canceled'];
				$cosAvg_child_canceled['cavg_parent'] 		= ['$exists' => false];
				$cosAvg_child_canceled['parent_status'] 		= ['$exists' => false];

				$cosAvgSold_child['application_mode']	= 'live';
				$cosAvgSold_child['opportunityId'] 	= $value['opportunity_id'];
				$cosAvgSold_child['is_sell_order'] 	= 'sold';
				$cosAvgSold_child['cost_avg'] 		= ['$in' => ['yes', 'completed', 'taking_child']];
				$cosAvgSold_child['cavg_parent']    = ['$exists' => false];
				
				$costAvgReturn_child = $connection->buy_orders->count($cosAvg_child);
				$costAvgReturn_child_canceled = $connection->buy_orders->count($cosAvg_child_canceled);
				$soldCostAvgReturn_child = $connection->sold_buy_orders->count($cosAvgSold_child);  
				/////////////////////////////////////////////////////////////// 

				$this->mongo_db->where($search_resumed);
				$total_resumed_sold = $this->mongo_db->get('sold_buy_orders');
				$total_reumed_sold   = iterator_to_array($total_resumed_sold);
				
				$this->mongo_db->where($search_resumed);
				$total_resumed = $this->mongo_db->get('buy_orders');
				$total_reumed   = iterator_to_array($total_resumed);

				$this->mongo_db->where($search_open_lth);
				$total_open = $this->mongo_db->get('buy_orders');
				$total_open_lth_rec   = iterator_to_array($total_open);

				$this->mongo_db->where($search_cancel);
				$total_cancel = $this->mongo_db->get('buy_orders');
				$total_cancel_rec   = iterator_to_array($total_cancel);

				///////////////////////////////////////////////////////////////////////////////

				$this->mongo_db->where($search_new_error);
				$total_new_error = $this->mongo_db->get('buy_orders');
				$total_new_error_rec   = iterator_to_array($total_new_error);

				// 	/////////////////////////////////////////////////////////////////////////////

				$this->mongo_db->where($search_sold);
				$total_sold_total = $this->mongo_db->get('sold_buy_orders');
				$total_sold_rec   = iterator_to_array($total_sold_total);
				
				// ////////////////////////////////////////////////CALCULATE LTH AND OPEN ORDERS AVG
				$open_lth_puchase_price = 0;
				$open_lth_avg = 0;
				$open_lth_avg_per_trade= 0;
				$btc = 0;
				$usdt = 0;

				$buySumTimeDelayRange1 = 0;
				$buySumTimeDelayRange2 = 0;
				$buySumTimeDelayRange3 = 0;
				$buySumTimeDelayRange4 = 0;
				$buySumTimeDelayRange5 = 0;
				$buySumTimeDelayRange6 = 0;
				$buySumTimeDelayRange7 = 0;
				$buy_commision_bnb = 0;    
				$buy_fee_respected_coin = 0;

				echo"<br>Total open lth = ".count($total_open_lth_rec);
				if (count($total_open_lth_rec) > 0){
					echo "<br> Open/lth Calculation";
					foreach ($total_open_lth_rec as $key => $value2) {
						$commission_array = $value2['buy_fraction_filled_order_arr'];
						if($value2['symbol'] == 'ETHBTC'){
							$open_lth_puchase_price += (float) ($ethbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'LINKBTC'){
							$open_lth_puchase_price += (float) ($linkbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							$btc +=(float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'DASHBTC'){
							$open_lth_puchase_price += (float) ($dashbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							$btc +=(float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'XMRBTC'){
							$open_lth_puchase_price += (float) ($xmrbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							$btc +=(float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'ADABTC'){
							$open_lth_puchase_price += (float) ($adabtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							$btc +=(float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'LTCUSDT'){
							$open_lth_puchase_price += (float) ($ltcusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							$usdt += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'BTCUSDT'){
							$open_lth_puchase_price += (float) ($btcusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							$usdt += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'XRPBTC'){
							$open_lth_puchase_price += (float) ($xrpbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'XRPUSDT'){
							$open_lth_puchase_price += (float) ($xrpusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							$usdt += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'NEOBTC'){
							$open_lth_puchase_price += (float) ($neobtc - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'NEOUSDT'){
							$open_lth_puchase_price += (float) ($neousdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							$usdt += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'QTUMBTC'){
							$open_lth_puchase_price += (float) ($qtumbtc - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'QTUMUSDT'){
							$open_lth_puchase_price += (float) ($qtumusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							$usdt += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'XLMBTC'){
							$open_lth_puchase_price += (float) ($xml - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'XEMBTC'){
							$open_lth_puchase_price += (float) ($xem - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'POEBTC'){
							$open_lth_puchase_price += (float) ($poe - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'TRXBTC'){
							$open_lth_puchase_price += (float) ($trx - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'ZENBTC'){
							$open_lth_puchase_price += (float) ($zen - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'ETCBTC'){
							$open_lth_puchase_price += (float) ($etcbtc - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'EOSBTC'){
							$open_lth_puchase_price += (float) ($eosbtc - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'EOSUSDT'){
							$open_lth_puchase_price += (float) ($eosusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							$usdt += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}		
						echo "<br>open_lth_puchase_price +=";
						print_r($open_lth_puchase_price);
						echo "<br> order_id = ".$value2['_id'];

						if( isset($value2['created_date']) && !empty($value2['created_date']) ){

							$orderBUyTime  		= strtotime($value2['created_date']->toDateTime()->format('Y-m-d H:i:s'));

							$differenceBuyInSec = ($orderBUyTime - $sendHitTime);
						}else{
							$differenceBuyInSec = 0;
						}

						if($differenceBuyInSec < 0){
							$differenceBuyInSec = 0;
						}
						if($differenceBuyInSec >= 0 && $differenceBuyInSec < 15 ){
                          	$buySumTimeDelayRange1++; 
						}elseif($differenceBuyInSec >= 15 && $differenceBuyInSec < 30){
							$buySumTimeDelayRange2++;
						}elseif($differenceBuyInSec >= 30 && $differenceBuyInSec < 45){
							$buySumTimeDelayRange3++;
						}elseif($differenceBuyInSec >= 45 && $differenceBuyInSec < 60){
							$buySumTimeDelayRange4++;
						}elseif($differenceBuyInSec >= 60 && $differenceBuyInSec < 75){
							$buySumTimeDelayRange5++;
						}elseif($differenceBuyInSec >= 75 && $differenceBuyInSec < 90 ){
							$buySumTimeDelayRange6++;
						}elseif($differenceBuyInSec >= 90){
							$buySumTimeDelayRange7++;
						}
					}//end loop
					$open_lth_avg_per_trade = (float) $open_lth_puchase_price * 100;
					$open_lth_avg = (float) ($open_lth_avg_per_trade / count($total_open_lth_rec));
				
					echo "<br>avg_sold = ";
					print_r($open_lth_avg);
				}//end if
				// /////////////////////////////////////////////////////////////////END OPEN LTH AVG
			
				// ////////////////////////////////////////////////////////////////CALCULATE SOLD AVG
				$sold_puchase_price = 0;
				$sell_fee_respected_coin = 0;
				$avg_sold_CSL = 0;
				$CSL_per_trade_sold = 0;
				$CSL_sold_purchase_price = 0 ;
				$avg_manul = 0;
				$per_trade_sold_manul = 0;
				$manul_sold_purchase_price = 0;
				$avg_sold = 0;
				$per_trade_sold = 0;
				// $sold_profit_btc = 0;
				$sumTimeDelayRange1 = 0;
				$sumTimeDelayRange2 = 0;
				$sumTimeDelayRange3 = 0;
				$sumTimeDelayRange4 = 0;
				$sumTimeDelayRange5 = 0;
				$sumTimeDelayRange6 = 0;
				$sumTimeDelayRange7 = 0;

				$sumPLSllipageRange1 = 0;
				$sumPLSllipageRange2 = 0;
				$sumPLSllipageRange3 = 0;
				$sumPLSllipageRange4 = 0;
				$sumPLSllipageRange5 = 0;
				// $sold_profit_usdt = 0;
				$btc_sell  = 0;
				$usdt_sell = 0;
				$sell_comssion_bnb = 0;
			
				if(count($total_sold_rec) > 0){
					echo "<br> sold calculation";
					foreach ($total_sold_rec as $key => $value1) {
						$commission_sold_array = $value1['buy_fraction_filled_order_arr'];
						$sell_commission_sold_array = $value1['sell_fraction_filled_order_arr'];
						if($value1['symbol'] == 'ETHBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}
						}elseif($value1['symbol'] == 'XRPBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'NEOBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);
							
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'QTUMBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);
							
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'XLMBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'XEMBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'POEBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'TRXBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'ZENBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'ETCBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'EOSBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'DASHBTC'){     
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);							
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'LINKBTC'){  
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'XMRBTC'){  
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'ADABTC'){       
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'LTCUSDT'){        
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'BTCUSDT'){    
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'XRPUSDT'){
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'NEOUSDT'){
							$usdt += $value1['purchased_price'] * $value1['quantity'];
							$usdt_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'QTUMUSDT'){
							$usdt += $value1['purchased_price']* $value1['quantity'];;
							$usdt_sell +=(float)($value1['market_sold_price'] * $value1['quantity']);

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}	
						if(isset($value1['is_manual_sold'])){
							$manul_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];											
							
						}elseif(isset($value1['csl_sold'])){
							$CSL_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];											
							
						}elseif(isset($value1['trade_history_issue']) && $value1['trade_history_issue'] == "yes"){
							// $CSL_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];											
						}else{
							$sold_puchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
							
						}

						//Sell time delapy and % delay calculate
 						if(isset($value1['order_send_time']) && isset($value1['sell_date']) && !empty($value1['order_send_time']) && !empty($value1['sell_date']) && $value1['is_sell_order'] == "sold"){

							$filledTime     = strtotime($value1['sell_date']->toDateTime()->format('Y-m-d H:i:s'));
							$orderSendTime  = strtotime($value1['order_send_time']->toDateTime()->format('Y-m-d H:i:s'));

							$differenceInSec = ($filledTime - $orderSendTime);
						}else{
							$differenceInSec = 0;
						}
						if($differenceInSec >= 0 && $differenceInSec < 15 ){
                          	$sumTimeDelayRange1++; 
						}elseif($differenceInSec >= 15 && $differenceInSec < 30){
							$sumTimeDelayRange2++;
						}elseif($differenceInSec >= 30 && $differenceInSec < 45){
							$sumTimeDelayRange3++;
						}elseif($differenceInSec >= 45 && $differenceInSec < 60){
							$sumTimeDelayRange4++;
						}elseif($differenceInSec >= 60 && $differenceInSec < 75){
							$sumTimeDelayRange5++;
						}elseif($differenceInSec >= 75 && $differenceInSec < 90 ){
							$sumTimeDelayRange6++;
						}elseif($differenceInSec >= 90){
							$sumTimeDelayRange7++;
						}

						// Buy time delapy and % delay calculate
						if(  isset($value1['created_date']) && !empty($value1['created_date']) ){
							$orderBUyTime  		= strtotime($value1['created_date']->toDateTime()->format('Y-m-d H:i:s'));

							$differenceBuyInSec = ($orderBUyTime - $sendHitTime);
						}else{
							$differenceBuyInSec = 0;
						}
						if($differenceBuyInSec < 0){
							$differenceBuyInSec = 0;
						}
						if($differenceBuyInSec >= 0 && $differenceBuyInSec < 15 ){
                          	$buySumTimeDelayRange1++; 
						}elseif($differenceBuyInSec >= 15 && $differenceBuyInSec < 30){
							$buySumTimeDelayRange2++;
						}elseif($differenceBuyInSec >= 30 && $differenceBuyInSec < 45){
							$buySumTimeDelayRange3++;
						}elseif($differenceBuyInSec >= 45 && $differenceBuyInSec < 60){
							$buySumTimeDelayRange4++;
						}elseif($differenceBuyInSec >= 60 && $differenceBuyInSec < 75){
							$buySumTimeDelayRange5++;
						}elseif($differenceBuyInSec >= 75 && $differenceBuyInSec < 90 ){
							$buySumTimeDelayRange6++;
						}elseif($differenceBuyInSec >= 90){
							$buySumTimeDelayRange7++;
						}

						// sold Pl slippage calculate
						if(isset($value1['sell_market_price']) && $value1['is_sell_order'] == 'sold' && $value1['sell_market_price'] !="" && !is_string($value1['sell_market_price'])){
							$val1 = $value1['market_sold_price'] - $value1['sell_market_price']; 
							$val2 = ($value1['market_sold_price'] + $value1['sell_market_price'])/ 2;
							$slippageOrignalPercentage = ($val1/ $val2) * 100;
							$slippageOrignalPercentage = round($slippageOrignalPercentage, 3) . '%';
						}else{
							$slippageOrignalPercentage = 0;
						}

						if($slippageOrignalPercentage > 0){
							$slippageOrignalPercentage = 0;
						}

						if($slippageOrignalPercentage <= 0 && $slippageOrignalPercentage > -0.2 ){

                          	$sumPLSllipageRange1++; 
						}elseif($slippageOrignalPercentage <= -0.2 && $slippageOrignalPercentage > -0.3){
							
							$sumPLSllipageRange2++;
						}elseif($slippageOrignalPercentage <= -0.3 && $slippageOrignalPercentage > -0.5){
							
							$sumPLSllipageRange3++;
						}elseif($slippageOrignalPercentage <= -0.5 && $slippageOrignalPercentage > -0.75){
							
							$sumPLSllipageRange4++;
						}elseif($slippageOrignalPercentage <= -1 ){
							
							$sumPLSllipageRange5++;
						}
						
					} //end sold foreach

					// if manul sold greater than 0 add in avg parofit 
					if($manul_sold_purchase_price > 0)
					{
						$sold_puchase_price += $manul_sold_purchase_price;
						$manul_sold_purchase_price = 0;
					}

					// if CSL sold greater than 0 add in avg parofit 
					if($CSL_sold_purchase_price > 0)
					{
						$sold_puchase_price += $CSL_sold_purchase_price;
						$CSL_sold_purchase_price = 0;
					}
					if($manul_sold_purchase_price != "0"){
						$per_trade_sold_manul = (float) $manul_sold_purchase_price * 100;
						echo "<br>per tarde manul = ".$per_trade_sold_manul;
						$avg_manul = (float) ($per_trade_sold_manul / (count($total_sold_rec)));
						echo "<br>avg_sold manul = ";
						print_r($avg_manul);
						print_r("<br>sold count = ".count($total_sold_rec));
					}
					if($sold_puchase_price !="0"){
						$per_trade_sold = (float) $sold_puchase_price * 100;
						echo "<br>per tarde = ".$per_trade_sold;
						$avg_sold = (float) ($per_trade_sold / count($total_sold_rec));     
						echo "<br>avg_sold = ";
						print_r($avg_sold);
						print_r("<br>sold count = ".count($total_sold_rec));
					}
					if($CSL_sold_purchase_price !="0"){
						$CSL_per_trade_sold = (float) $CSL_sold_purchase_price * 100;
						echo "<br>per tarde CSL = ".$CSL_per_trade_sold;
						$avg_sold_CSL = (float) ($CSL_per_trade_sold / count($total_sold_rec));
						echo "<br>avg_sold CSL = ";
						print_r($avg_sold_CSL);
						print_r("<br>sold count = ".count($total_sold_rec));
					}
				}//end response > 0 check 

				
				print_r("<br>oppertunity_id=".$value['opportunity_id']."<br>"); 	
				// /////////////////////////////////////////////////////////////////////////END SOLD AVG

				$total_orders = count($total_open_lth_rec) + count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $totalOther;
				$disappear = $parents_executed -  $total_orders;
				$total = count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $disappear;
				if($total == $parents_executed ) {

					$sell_commision_qty_USDT =   ($sell_fee_respected_coin > 0)  ? convertCoinBalanceIntoUSDT($value['coin'], $sell_fee_respected_coin, 'binance') : 0;
					$update_fields = array(
						'open_lth'     			=> 	count($total_open_lth_rec),
						'new_error'    			=> 	count($total_new_error_rec),
						'minOrderSoldPrice' 	=> 	$soldMinPrice[0]['minPrice'],
						'maxOrderSoldPrice' 	=> 	$soldMaxPrice[0]['maxPrice'],
						'reumed_child' 			=> 	count($total_reumed) + count($total_reumed_sold),       
						'costAvgCount' 			=> 	($costAvgReturn + $soldCostAvgReturn),
						'costAvgCount_child' 	=> 	($costAvgReturn_child + $soldCostAvgReturn_child),
						'costAvgCount_child_buy' 	=> 	$costAvgReturn_child,
						'costAvgCount_child_sold' 	=> 	$soldCostAvgReturn_child,
						'cost_avg_active_parents' 	=> 	$costAvgparent,
						'costAvgCount_child_canceled' 	=> 	$costAvgReturn_child_canceled,
						'cancelled'    			=> 	count($total_cancel_rec),
						'sold'         			=> 	count($total_sold_rec),         
						'avg_open_lth' 			=> 	$open_lth_avg,
						'other_status' 			=> 	$totalOther,
						'sellTimeDiffRange1' 	=>	$sumTimeDelayRange1 ,
						'sellTimeDiffRange2' 	=>	$sumTimeDelayRange2 ,
						'sellTimeDiffRange3' 	=> 	$sumTimeDelayRange3 ,
						'sellTimeDiffRange4' 	=>	$sumTimeDelayRange4 ,
						'sellTimeDiffRange5' 	=> 	$sumTimeDelayRange5 ,
						'sellTimeDiffRange6' 	=>	$sumTimeDelayRange6 ,
						'sellTimeDiffRange7' 	=>	$sumTimeDelayRange7 ,
						
						'buySumTimeDelayRange1' => $buySumTimeDelayRange1 ,
						'buySumTimeDelayRange2' => $buySumTimeDelayRange2 ,
						'buySumTimeDelayRange3' => $buySumTimeDelayRange3 ,
						'buySumTimeDelayRange4' => $buySumTimeDelayRange4 ,
						'buySumTimeDelayRange5' => $buySumTimeDelayRange5 ,
						'buySumTimeDelayRange6' => $buySumTimeDelayRange6 ,
						'buySumTimeDelayRange7' => $buySumTimeDelayRange7 ,

						'sumPLSllipageRange1'	=> $sumPLSllipageRange1 ,
						'sumPLSllipageRange2' 	=> $sumPLSllipageRange2 ,
						'sumPLSllipageRange3'	=> $sumPLSllipageRange3 ,
						'sumPLSllipageRange4' 	=> $sumPLSllipageRange4 ,
						'sumPLSllipageRange5' 	=> $sumPLSllipageRange5 ,

						'avg_sold'     			=> 	$avg_sold,
						'per_trade_sold' 		=> 	$per_trade_sold,
						'avg_manul'    			=>	$avg_manul,
						'avg_sold_CSL' 			=> 	$avg_sold_CSL,
						'sell_commission' 		=> $sell_comssion_bnb,
						'sell_fee_respected_coin' => $sell_fee_respected_coin,
						'sell_commision_qty_USDT' => $sell_commision_qty_USDT,
						'modified_date' 		=> $current_time_date  
						
					);
					if(isset($value['10_max_value']) && isset($value['5_max_value'])){
						$update_fields['is_modified']  = true;
					}
					$cost_avg_child_sum = $costAvgReturn_child+$soldCostAvgReturn_child+$costAvgReturn_child_canceled;
					if(count($total_open_lth_rec)== 0 && count($total_sold_rec) == 0 &&  $totalOther == 0 && $cost_avg_child_sum == 0){
						$update_fields['oppertunity_missed'] = true;
					}
				}else { 
					$update_fields = array(
						'open_lth'     			=> 	count($total_open_lth_rec),
						'new_error'    			=> 	count($total_new_error_rec),
						'cancelled'    			=> 	count($total_cancel_rec),
						'costAvgCount' 			=> 	($costAvgReturn + $soldCostAvgReturn),
						'costAvgCount_child' 	=> 	($costAvgReturn_child + $soldCostAvgReturn_child),
						'costAvgCount_child_buy' 	=> 	$costAvgReturn_child,
						'costAvgCount_child_sold' 	=> 	$soldCostAvgReturn_child,
						'cost_avg_active_parents' 	=> 	$costAvgparent,
						'costAvgCount_child_canceled' 	=> 	$costAvgReturn_child_canceled,
						'reumed_child' 			=> 	count($total_reumed) + count($total_reumed_sold) ,
						'sold'         			=> 	count($total_sold_rec),
						'avg_open_lth' 			=> 	$open_lth_avg,
						'sellTimeDiffRange1' 	=>	$sumTimeDelayRange1 ,
						'sellTimeDiffRange2' 	=>	$sumTimeDelayRange2 ,
						'sellTimeDiffRange3' 	=> 	$sumTimeDelayRange3 ,
						'sellTimeDiffRange4' 	=>	$sumTimeDelayRange4 ,
						'sellTimeDiffRange5' 	=> 	$sumTimeDelayRange5 ,
						'sellTimeDiffRange6' 	=>	$sumTimeDelayRange6 ,
						'sellTimeDiffRange7' 	=>	$sumTimeDelayRange7 ,

						'buySumTimeDelayRange1' => $buySumTimeDelayRange1 ,
						'buySumTimeDelayRange2' => $buySumTimeDelayRange2 ,
						'buySumTimeDelayRange3' => $buySumTimeDelayRange3 ,
						'buySumTimeDelayRange4' => $buySumTimeDelayRange4 ,
						'buySumTimeDelayRange5' => $buySumTimeDelayRange5 ,
						'buySumTimeDelayRange6' => $buySumTimeDelayRange6 ,
						'buySumTimeDelayRange7' => $buySumTimeDelayRange7 ,

						'sumPLSllipageRange1' 	=> $sumPLSllipageRange1 ,
						'sumPLSllipageRange2' 	=> $sumPLSllipageRange2 ,
						'sumPLSllipageRange3'	=> $sumPLSllipageRange3 ,
						'sumPLSllipageRange4' 	=> $sumPLSllipageRange4 ,
						'sumPLSllipageRange5' 	=> $sumPLSllipageRange5 ,

						'avg_sold'     			=> 	$avg_sold,
						'per_trade_sold' 		=> 	$per_trade_sold,
						'other_status' 			=> 	$totalOther, 
						'minOrderSoldPrice' 	=> 	$soldMinPrice[0]['minPrice'],
						'maxOrderSoldPrice' 	=> 	$soldMaxPrice[0]['maxPrice'],  
						'avg_manul'    			=>	$avg_manul,
						'avg_sold_CSL' 			=> 	$avg_sold_CSL,
						'modified_date'			=>	$current_time_date
					);
				}

				// btc_sell  usdt_sell


				$sell_btc_converted = ($btc_sell > 0)  ? convertCoinBalanceIntoUSDT($value['coin'], $btc_sell, 'binance') : 0; 
				$update_fields['sell_btc_in_$'] =   (float)$sell_btc_converted;
				$update_fields['sell_usdt']   	=   (float)$usdt_sell;
				$update_fields['total_sell_in_usdt'] = (float)($sell_btc_converted + $usdt_sell );


				if($buy_fee_respected_coin > 0 && !isset($value['buy_commision_qty']) && !isset($value['is_modified'])){
					$update_fields['buy_commision_qty'] = $buy_fee_respected_coin;

					$update_fields['buy_commision_qty_USDT'] =   ($buy_fee_respected_coin > 0)  ? convertCoinBalanceIntoUSDT($value['coin'], $buy_fee_respected_coin, 'binance') : 0;
				}
				if($buy_commision_bnb > 0 && !isset($value['buy_commision']) && !isset($value['is_modified'])){
					$update_fields['buy_commision'] = $buy_commision_bnb;
				}

				echo "<pre>";print_r($update_fields);
				$db = $this->mongo_db->customQuery();

				$pipeline = [
					[
						'$match' =>[
							'application_mode' => 'live',
							'parent_status' => ['$exists' => false ],
							'opportunityId' => $value['opportunity_id'],
							'status' => ['$in'=>['LTH','FILLED']],
						],
					],
					[
						'$sort' =>['created_date'=> -1],
					],
					['$limit'=>1]
				];
				$result_buy = $db->buy_orders->aggregate($pipeline);
				$res = iterator_to_array($result_buy);

				$pipeline1 = [
					[
						'$match' =>[
							'application_mode' => 'live',
							'parent_status' => ['$exists' => false ],
							'opportunityId' => $value['opportunity_id'],
							'status' => ['$in'=>['LTH','FILLED']],
						],
					],
					[
					'$sort' =>['created_date'=> 1],
					],
					['$limit'=>1]
				];
				$result_buy1 = $db->buy_orders->aggregate($pipeline1);
				$res1 = iterator_to_array($result_buy1);
				if(!isset($value['first_order_buy']) && !isset($value['last_order_buy'])){
					echo "<br> created_date first =".$res[0]['created_date'];
					echo "<br>created_date last = ".$res1[0]['created_date'];
					$update_fields['first_order_buy'] =  $res[0]['created_date'];
					$update_fields['last_order_buy'] =  $res1[0]['created_date'];
				}

				if(!isset($value['opp_came_binance']) && !isset($value['opp_came_kraken']) && !isset($value['opp_came_bam'])){	
					$opper_search['application_mode']= 'live';
					$opper_search['opportunityId'] = $value['opportunity_id'];	
					$connetct= $this->mongo_db->customQuery();
					$pending_curser = $connetct->buy_orders->find($opper_search);
					$buy_order = iterator_to_array($pending_curser);
					echo "<br>result binance=".count($buy_order);
					$pending_curser_buy = $connetct->sold_buy_order->find($opper_search);
					$sold_bbuy_order = iterator_to_array($pending_curser_buy);
					echo "<br>result binance sold=".count($sold_bbuy_order);

					if(count($buy_order) > 0 || count($sold_bbuy_order) > 0 ){
						$update_fields['opp_came_binance'] = '1';
					}else{
						$update_fields['opp_came_binance'] = '0';
					}
					
					$this->mongo_db->where($opper_search);
					$response_kraken = $this->mongo_db->get('buy_orders_kraken');
					$data_kraken = iterator_to_array($response_kraken);
					echo "<br>result kraken=". count($data_kraken);

					$this->mongo_db->where($opper_search);
					$response_kraken_sold = $this->mongo_db->get('sold_buy_orders_kraken');
					$data_kraken_sold = iterator_to_array($response_kraken_sold);
					echo "<br>result kraken sold=". count($data_kraken_sold);
					if(count($data_kraken) > 0 || count($data_kraken_sold) > 0){
						$update_fields['opp_came_kraken'] = '1';
					}else{
						$update_fields['opp_came_kraken'] = '0';
					}
					
					$this->mongo_db->where($opper_search);
					$response_bam = $this->mongo_db->get('buy_orders_bam');
					$data_bam = iterator_to_array($response_bam);
					echo "<br>result bam=". count($data_bam );

					$this->mongo_db->where($opper_search);
					$response_bam_sold = $this->mongo_db->get('sold_buy_orders_bam');
					$data_bam_sold = iterator_to_array($response_bam_sold);
					echo "<br>result bam sold =". count($data_bam_sold);

					if(count($data_bam) > 0 || count($data_bam_sold) > 0){
						$update_fields['opp_came_bam'] = '1';
					}else{
						$update_fields['opp_came_bam'] = '0';
					}
				}

				if($btc > 0 && $usdt == 0 && !isset($value['usdt_invest_amount']) &&  !isset($value['btc_invest_amount'])){
					$update_fields['usdt_invest_amount'] = $btcusdt * $btc;//(float)$btc;
					$update_fields['btc_invest_amount']  = $btc;  //for chart view 
				}
				elseif($usdt > 0 && $btc == 0 && !isset($value['usdt_invest_amount']) && !isset($value['only_usdt_invest_amount'])) {
					$update_fields['usdt_invest_amount'] = $usdt;
					$update_fields['only_usdt_invest_amount'] = $usdt;  //for chart view
				} //end if ($total == $parents_executed ) 

				
				foreach($api_response as $as_1){
					if($as_1->max_price !='' && $as_1->min_price !='' && $as_1->min_price != 0 && $as_1->max_price != 0){
						$update_fields['5_max_value'] = $as_1->max_price;
						echo "<br>max =". $update_fields['5_max_value'];
						$update_fields['5_min_value'] = $as_1->min_price;  
						echo "<br> min =". $update_fields['5_min_value'];
					} //loop inner check				
				} // foreach loop end


				foreach($api_response_10 as $as){
					if($as->max_price !='' && $as->min_price !='' && $as->min_price !=0 && $as->max_price !=0){
						echo "<br>max 10 = ".$as->max_price;
						$update_fields['10_max_value'] = $as->max_price; 
						echo "<br>min 10=".$as->min_price;
						$update_fields['10_min_value'] = $as->min_price;
					} // if inner check	
				} //end foreach loop

				echo"<br><pre>";
				print_r($update_fields);
				$collection_name = 'opportunity_logs_binance';
				$this->mongo_db->where($search_update);
				$this->mongo_db->set($update_fields);
				$query = $this->mongo_db->update($collection_name);	
			}
		} //end foreach
		echo "<br>current time".$current_date_time;
		echo "<br>Total Picked Oppertunities Ids= " . count($response);

		//Save last Cron Executioon
		$this->last_cron_execution_time('Binance live opportunity', '1m', 'run binance live opportunity logs (* * * * *)', 'reports');


	} //end cron

	//insert latest opportunity for old opportunities binance
	public function insert_latest_oppertunity_into_log_collection_binance_for_old_opportunities(){
		// ini_set("display_errors", E_ALL);
		// error_reporting(E_ALL);

		$marketPrices = marketPrices('binance');
		$this->load->helper('new_common_helper');
		foreach($marketPrices as $price){
			if($price['_id'] == 'ETHBTC'){
				$ethbtc = (float)$price['price'];
			}elseif($price['_id'] == 'BTCUSDT'){
				$btcusdt = (float)$price['price'];
			}elseif($price['_id'] == 'XRPBTC'){
				$xrpbtc = (float)$price['price'];
			}elseif($price['_id'] == 'XRPUSDT'){
				$xrpusdt = (float)$price['price'];
			}elseif($price['_id'] == 'NEOBTC'){
				$neobtc = (float)$price['price'];
			}elseif($price['_id'] == 'NEOUSDT'){
				$neousdt = (float)$price['price'];
			}elseif($price['_id'] == 'QTUMBTC'){
				$qtumbtc = (float)$price['price'];
			}elseif($price['_id'] == 'QTUMUSDT'){
				$qtumusdt = (float)$price['price'];
			}elseif($price['_id'] == 'XLMBTC'){
				$xml = (float)$price['price'];
			}elseif($price['_id'] == 'XEMBTC'){
				$xem = (float)$price['price'];
			}elseif($price['_id'] == 'POEBTC'){
				$poe = (float)$price['price'];
			}elseif($price['_id'] == 'TRXBTC'){
				$trx = (float)$price['price'];
			}elseif($price['_id'] == 'ZENBTC'){
				$zen = (float)$price['price'];
			}elseif($price['_id'] == 'ETCBTC'){
				$etcbtc = (float)$price['price'];
			}elseif($price['_id'] =='EOSBTC'){
				$eosbtc = (float)$price['price'];
			}elseif($price['_id'] =='LINKBTC'){
				$linkbtc = (float)$price['price'];
			}elseif($price['_id'] =='DASHBTC'){
				$dashbtc = (float)$price['price'];
			}elseif($price['_id'] =='XMRBTC'){
				$xmrbtc = (float)$price['price'];
			}elseif($price['_id'] =='ADABTC'){
				$adabtc = (float)$price['price'];
			}elseif($price['_id'] =='LTCUSDT'){
				$ltcusdt = (float)$price['price'];
			}elseif($price['_id'] =='EOSUSDT'){
				$eosusdt = (float)$price['price'];
			}				
		}//end inner loop 
		$startDate =  date('Y-01-d 00:00:00');
		$startTime =  $this->mongo_db->converToMongodttime($startDate);

		$current_date_time =  date('Y-m-d 00:00:00');
		$current_time_date =  $this->mongo_db->converToMongodttime($current_date_time);

		$current_hour =  date('Y-m-d H:i:s', strtotime('-1 month'));
		$orig_date1 = $this->mongo_db->converToMongodttime($current_hour);

		$previous_one_month_date_time = date('Y-m-d H:i:s', strtotime('-1 month'));
		$pre_date_1 =  $this->mongo_db->converToMongodttime($previous_one_month_date_time);

		$connection = $this->mongo_db->customQuery();      
		$condition = array('sort' => array('modified_date' => -1), 'limit'=> 15);
		 
		$where['mode'] 			=	'live';
		$where['created_date'] 	= 	array('$gte'=> $startTime, '$lte'  =>  $pre_date_1);
		$where['level'] 		= 	array('$ne'=>'level_15');
		$where['modified_date'] = 	array('$lte' => $orig_date1);
		$where['is_modified']	=	['$exists' => false];

		$find_rec = $connection->opportunity_logs_binance->find($where,  $condition);
		$response = iterator_to_array($find_rec);
		echo "<br>Count: ". count($response);

		foreach ($response as $value){
			if(isset($value['sendHitTime']) && !empty($value['sendHitTime'])){
				$sendHitTime     	= strtotime($value['sendHitTime']->toDateTime()->format('Y-m-d H:i:s'));   

			}else{
				$sendHitTime     	= strtotime($value['created_date']->toDateTime()->format('Y-m-d H:i:s'));
			}

			if($value['level'] != 'level_15' ) {
				$open_lth_avg_per_trade = 0;
				$open_lth_avg = 0;
				$avg_sold = 0;
				$parents_executed = 0;
				$parents_executed = $value['parents_executed'];
				
				$search_update['opportunity_id'] = (string)$value['opportunity_id'];
				// $search_update['mode']= 'live';
				
				$other['application_mode']= 'live';
				$other['opportunityId'] =  $value['opportunity_id'];
				$other['status'] = array('$nin' => array('LTH', 'FILLED','canceled','new_ERROR'));

				$buyOther = $connection->buy_orders->count($other);
				/////////////////////////////////////////////////////////

				$search_open_lth['application_mode']		= 	'live';
				$search_open_lth['opportunityId'] 			= 	$value['opportunity_id'];
				$search_open_lth['status'] 					= 	array('$in' => array('LTH', 'FILLED'));
				$search_open_lth['is_sell_order']         	=   'yes';
				$search_open_lth['cavg_parent'] 			= 	['$exists' => false];
				$search_open_lth['cost_avg']				=	['$nin' => ['yes', 'completed', '']];

				/////
				$search_cancel['application_mode']		= 	'live';
				$search_cancel['opportunityId'] 		= 	$value['opportunity_id'];
				$search_cancel['status'] 				= 	array('$in' => array('canceled'));
				$search_cancel['cavg_parent'] 			= 	['$exists' => false];
				$search_cancel['cost_avg']				=	['$nin' => ['yes', 'completed', '']];
				//////
				$search_new_error['application_mode']	= 'live';
				$search_new_error['opportunityId'] 		= $value['opportunity_id'];
				$search_new_error['status'] 			= array('$in' => array('new_ERROR'));
				$search_new_error['cavg_parent'] 		= 	['$exists' => false];
				$search_new_error['cost_avg']			=	['$nin' => ['yes', 'completed', '']];
				////////
				$search_sold['application_mode']	= 'live';
				$search_sold['opportunityId'] 		= $value['opportunity_id'];
				$search_sold['is_sell_order'] 		= 'sold';
				$search_sold['cavg_parent'] 		= ['$exists' => false];
				$search_sold['cost_avg']			= ['$nin' => ['yes', 'completed', '']];


				$otherSold['application_mode'] 	= 	'live';
				$otherSold['opportunityId'] 	=  	$value['opportunity_id'];
				$otherSold['is_sell_order'] 	= 	array('$nin' => array('sold'));
				$searchSold['cavg_parent'] 		= 	['$exists' => false];
				$searchSold['cost_avg']			=	['$nin' => ['yes', 'completed', '']];

				$otherStatusSold = $connection->sold_buy_orders->count($otherSold);
				$totalOther = $buyOther + $otherStatusSold;

				$minPriceLookUp = [
					[
						'$match' => [
							'application_mode' => 'live',
							'opportunityId'    =>  $value['opportunity_id'],
							'is_sell_order'    =>  'sold'
 						]
					],

					[
						'$group' =>[
							'_id' => '$symbol',
							'minPrice' => ['$min' => '$market_sold_price']
						]
					],

				];

				$minSoldPrice = $connection->sold_buy_orders->aggregate($minPriceLookUp);
				$soldMinPrice  = iterator_to_array($minSoldPrice);

				$maxPriceLookUp = [
					[
						'$match' => [
							'application_mode' => 'live',
							'opportunityId'    =>  $value['opportunity_id'],
							'is_sell_order'    =>  'sold'
 						]
					],

					[
						'$group' =>[
							'_id' => '$symbol',
							'maxPrice' => ['$max' => '$market_sold_price']
						]
					],

				];

				$maxSoldPrice = $connection->sold_buy_orders->aggregate($maxPriceLookUp);
				$soldMaxPrice  = iterator_to_array($maxSoldPrice);
				
				///////////////////////////////////////////////////////////////////
		
				$search_resumed['application_mode']	= 	'live';
				$search_resumed['opportunityId'] 	= 	$value['opportunity_id'];
				$search_resumed['resume_status'] 	= 	array('$in' => array('resume'));
				$search_resumed['cavg_parent'] 		= 	['$exists' => false];
				$search_resumed['cost_avg']			=	['$nin' => ['yes', 'completed', '']];
				
				/////////////////////////////////////////////////////////////// 
				$cosAvg['application_mode']	= 	'live';
				$cosAvg['opportunityId'] 	= 	$value['opportunity_id'];
				$cosAvg['cost_avg']['$ne'] 	= 	'completed';
				$cosAvg['is_sell_order']	= 	'yes';
				$cosAvg['cavg_parent'] 		= 	'yes';

				$cosAvgSold['application_mode']	= 'live';
				$cosAvgSold['opportunityId'] 	= $value['opportunity_id'];
				$cosAvgSold['is_sell_order'] 	= 'sold';
				$cosAvgSold['cost_avg'] 		= 'completed';
				$cosAvgSold['cavg_parent'] 		= 'yes';
				
				$costAvgReturn 		= $connection->buy_orders->count($cosAvg);
				$soldCostAvgReturn 	= $connection->sold_buy_orders->count($cosAvgSold);  
				///////////////////////////////////////////////////////////////
				$cosAvg_parent['application_mode']	= 	'live';
				$cosAvg_parent['symbol'] 	= 	$value['coin'];
				$cosAvg_parent['cost_avg']['$ne'] 	= 	'completed';
				$cosAvg_parent['is_sell_order']	= 	'yes';
				$cosAvg_parent['cavg_parent'] 		= 	'yes';
				$cost_avg_parent = $connection->buy_orders->count($cosAvg_parent);
				///////////////////////////////////////////////////////////////
					// cost avg child
				/////////////////////////////////////////////////////////////// 
				$cosAvg_child['application_mode']	= 	'live';
				$cosAvg_child['opportunityId'] 	= 	$value['opportunity_id'];
				$cosAvg_child['cost_avg']     = ['$in' => ['yes','taking_child']];
				$cosAvg_child['is_sell_order']	= 	['$ne'=>'sold'];
				$cosAvg_child['status']	= 	['$ne'=>'canceled'];
				$cosAvg_child['cavg_parent'] 		= 	['$exists' => false];
				$cosAvg_child['parent_status']    = ['$exists' => false];

				$cosAvg_child_canceled['application_mode']	= 	'live';
				$cosAvg_child_canceled['opportunityId'] 	= 	$value['opportunity_id'];
				$cosAvg_child_canceled['cost_avg']     = ['$in' => ['yes','taking_child']];
				$cosAvg_child_canceled['is_sell_order']	= 	['$ne'=>'sold'];
				$cosAvg_child_canceled['status']	= 	['$eq'=>'canceled'];
				$cosAvg_child_canceled['cavg_parent'] 		= 	['$exists' => false];
				$cosAvg_child_canceled['parent_status']    = ['$exists' => false];

				$cosAvgSold_child['application_mode']	= 'live';
				$cosAvgSold_child['opportunityId'] 	= $value['opportunity_id'];
				$cosAvgSold_child['is_sell_order'] 	= 'sold';
				$cosAvgSold_child['cost_avg'] 		= ['$in' => ['yes', 'completed', 'taking_child']];
				$cosAvgSold_child['cavg_parent'] 		= ['$exists' => false];
				
				$costAvgReturn_child 		= $connection->buy_orders->count($cosAvg_child);
				$costAvgReturn_child_canceled 		= $connection->buy_orders->count($cosAvg_child_canceled);
				$soldCostAvgReturn_child 	= $connection->sold_buy_orders->count($cosAvgSold_child);  
				///////////////////////////////////////////////////////////////  

				$this->mongo_db->where($search_resumed);
				$total_resumed_sold = $this->mongo_db->get('sold_buy_orders');
				$total_reumed_sold   = iterator_to_array($total_resumed_sold);
				
				$this->mongo_db->where($search_resumed);
				$total_resumed = $this->mongo_db->get('buy_orders');
				$total_reumed   = iterator_to_array($total_resumed);

				$this->mongo_db->where($search_open_lth);
				$total_open = $this->mongo_db->get('buy_orders');
				$total_open_lth_rec   = iterator_to_array($total_open);

				$this->mongo_db->where($search_cancel);
				$total_cancel = $this->mongo_db->get('buy_orders');
				$total_cancel_rec   = iterator_to_array($total_cancel);

				///////////////////////////////////////////////////////////////////////////////

				$this->mongo_db->where($search_new_error);
				$total_new_error = $this->mongo_db->get('buy_orders');
				$total_new_error_rec   = iterator_to_array($total_new_error);

				// 	/////////////////////////////////////////////////////////////////////////////

				$this->mongo_db->where($search_sold);
				$total_sold_total = $this->mongo_db->get('sold_buy_orders');
				$total_sold_rec   = iterator_to_array($total_sold_total);
				
				// ////////////////////////////////////////////////CALCULATE LTH AND OPEN ORDERS AVG
				$open_lth_puchase_price = 0;
				$open_lth_avg = 0;
				$open_lth_avg_per_trade= 0;
				$btc = 0;
				$usdt = 0;

				$buySumTimeDelayRange1 = 0;
				$buySumTimeDelayRange2 = 0;
				$buySumTimeDelayRange3 = 0;
				$buySumTimeDelayRange4 = 0;
				$buySumTimeDelayRange5 = 0;
				$buySumTimeDelayRange6 = 0;
				$buySumTimeDelayRange7 = 0;
				$buy_commision_bnb = 0;    
				$buy_fee_respected_coin = 0;

				echo"<br>Total open lth = ".count($total_open_lth_rec);
				if (count($total_open_lth_rec) > 0){
					foreach ($total_open_lth_rec as $key => $value2) {
						$commission_array = $value2['buy_fraction_filled_order_arr'];
						if($value2['symbol'] == 'ETHBTC'){
							$open_lth_puchase_price += (float) ($ethbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'LINKBTC'){
							$open_lth_puchase_price += (float) ($linkbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							$btc +=(float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'DASHBTC'){
							$open_lth_puchase_price += (float) ($dashbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							$btc +=(float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'XMRBTC'){
							$open_lth_puchase_price += (float) ($xmrbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							$btc +=(float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'ADABTC'){
							$open_lth_puchase_price += (float) ($adabtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							$btc +=(float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'LTCUSDT'){
							$open_lth_puchase_price += (float) ($ltcusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							$usdt += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'BTCUSDT'){
							$open_lth_puchase_price += (float) ($btcusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							$usdt += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'XRPBTC'){
							$open_lth_puchase_price += (float) ($xrpbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'XRPUSDT'){
							$open_lth_puchase_price += (float) ($xrpusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							$usdt += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'NEOBTC'){
							$open_lth_puchase_price += (float) ($neobtc - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'NEOUSDT'){
							$open_lth_puchase_price += (float) ($neousdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							$usdt += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'QTUMBTC'){
							$open_lth_puchase_price += (float) ($qtumbtc - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'QTUMUSDT'){
							$open_lth_puchase_price += (float) ($qtumusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							$usdt += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'XLMBTC'){
							$open_lth_puchase_price += (float) ($xml - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'XEMBTC'){
							$open_lth_puchase_price += (float) ($xem - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'POEBTC'){
							$open_lth_puchase_price += (float) ($poe - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'TRXBTC'){
							$open_lth_puchase_price += (float) ($trx - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'ZENBTC'){
							$open_lth_puchase_price += (float) ($zen - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'ETCBTC'){
							$open_lth_puchase_price += (float) ($etcbtc - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'EOSBTC'){
							$open_lth_puchase_price += (float) ($eosbtc - $value2['purchased_price']) / $value2['purchased_price'] ;
							$btc += (float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'EOSUSDT'){
							$open_lth_puchase_price += (float) ($eosusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							$usdt += (float)$value2['purchased_price']  * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}		
						if( isset($value2['created_date']) && !empty($value2['created_date']) ){

							$orderBUyTime  		= strtotime($value2['created_date']->toDateTime()->format('Y-m-d H:i:s'));

							$differenceBuyInSec = ($orderBUyTime - $sendHitTime);
						}else{
							$differenceBuyInSec = 0;
						}

						if($differenceBuyInSec < 0){
							$differenceBuyInSec = 0;
						}
						if($differenceBuyInSec >= 0 && $differenceBuyInSec < 15 ){
                          	$buySumTimeDelayRange1++; 
						}elseif($differenceBuyInSec >= 15 && $differenceBuyInSec < 30){
							$buySumTimeDelayRange2++;
						}elseif($differenceBuyInSec >= 30 && $differenceBuyInSec < 45){
							$buySumTimeDelayRange3++;
						}elseif($differenceBuyInSec >= 45 && $differenceBuyInSec < 60){
							$buySumTimeDelayRange4++;
						}elseif($differenceBuyInSec >= 60 && $differenceBuyInSec < 75){
							$buySumTimeDelayRange5++;
						}elseif($differenceBuyInSec >= 75 && $differenceBuyInSec < 90 ){
							$buySumTimeDelayRange6++;
						}elseif($differenceBuyInSec >= 90){
							$buySumTimeDelayRange7++;
						}
					}//end loop
					$open_lth_avg_per_trade = (float) $open_lth_puchase_price * 100;
					$open_lth_avg = (float) ($open_lth_avg_per_trade / count($total_open_lth_rec));
				
				}//end if
				// /////////////////////////////////////////////////////////////////END OPEN LTH AVG
			
				// ////////////////////////////////////////////////////////////////CALCULATE SOLD AVG
				$sold_puchase_price = 0;
				$sell_fee_respected_coin = 0;
				$avg_sold_CSL = 0;
				$CSL_per_trade_sold = 0;
				$CSL_sold_purchase_price = 0 ;
				$avg_manul = 0;
				$per_trade_sold_manul = 0;
				$manul_sold_purchase_price = 0;
				$avg_sold = 0;
				$per_trade_sold = 0;
				// $sold_profit_btc = 0;
				$sumTimeDelayRange1 = 0;
				$sumTimeDelayRange2 = 0;
				$sumTimeDelayRange3 = 0;
				$sumTimeDelayRange4 = 0;
				$sumTimeDelayRange5 = 0;
				$sumTimeDelayRange6 = 0;
				$sumTimeDelayRange7 = 0;

				$sumPLSllipageRange1 = 0;
				$sumPLSllipageRange2 = 0;
				$sumPLSllipageRange3 = 0;
				$sumPLSllipageRange4 = 0;
				$sumPLSllipageRange5 = 0;
				// $sold_profit_usdt = 0;
				$sell_comssion_bnb = 0;
				$btc_sell 	= 0;
				$usdt_sell 	= 0 ;
			
				if(count($total_sold_rec) > 0){
					foreach ($total_sold_rec as $key => $value1) {
						$commission_sold_array = $value1['buy_fraction_filled_order_arr'];
						if(!empty($value1['sell_fraction_filled_order_arr'])){
							$sell_commission_sold_array = $value1['sell_fraction_filled_order_arr'];
						}else{
							$sell_commission_sold_array = [];
						}
						if($value1['symbol'] == 'ETHBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];
							
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}
						}elseif($value1['symbol'] == 'XRPBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'NEOBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'QTUMBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'XLMBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'XEMBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'POEBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'TRXBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'ZENBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'ETCBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'EOSBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'DASHBTC'){     
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'LINKBTC'){  
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'XMRBTC'){  
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];
							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'ADABTC'){       
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'LTCUSDT'){        
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'BTCUSDT'){    
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'XRPUSDT'){
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'NEOUSDT'){
							$usdt += $value1['purchased_price'] * $value1['quantity'];
							$usdt_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'QTUMUSDT'){
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}	
						if(isset($value1['is_manual_sold'])){
							$manul_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];											
							
						}elseif(isset($value1['csl_sold'])){
							$CSL_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];											
							
						}elseif(isset($value1['trade_history_issue']) && $value1['trade_history_issue'] == "yes"){
							// $CSL_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];											
						}else{
							$sold_puchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
							
						}

						//Sell time delapy and % delay calculate
 						if(isset($value1['order_send_time']) && isset($value1['sell_date']) && !empty($value1['order_send_time']) && !empty($value1['sell_date']) && $value1['is_sell_order'] == "sold"){

							$filledTime     = strtotime($value1['sell_date']->toDateTime()->format('Y-m-d H:i:s'));
							$orderSendTime  = strtotime($value1['order_send_time']->toDateTime()->format('Y-m-d H:i:s'));

							$differenceInSec = ($filledTime - $orderSendTime);
						}else{
							$differenceInSec = 0;
						}
						if($differenceInSec >= 0 && $differenceInSec < 15 ){
                          	$sumTimeDelayRange1++; 
						}elseif($differenceInSec >= 15 && $differenceInSec < 30){
							$sumTimeDelayRange2++;
						}elseif($differenceInSec >= 30 && $differenceInSec < 45){
							$sumTimeDelayRange3++;
						}elseif($differenceInSec >= 45 && $differenceInSec < 60){
							$sumTimeDelayRange4++;
						}elseif($differenceInSec >= 60 && $differenceInSec < 75){
							$sumTimeDelayRange5++;
						}elseif($differenceInSec >= 75 && $differenceInSec < 90 ){
							$sumTimeDelayRange6++;
						}elseif($differenceInSec >= 90){
							$sumTimeDelayRange7++;
						}

						// Buy time delapy and % delay calculate
						if(  isset($value1['created_date']) && !empty($value1['created_date']) ){
							$orderBUyTime  		= strtotime($value1['created_date']->toDateTime()->format('Y-m-d H:i:s'));

							$differenceBuyInSec = ($orderBUyTime - $sendHitTime);
						}else{
							$differenceBuyInSec = 0;
						}
						if($differenceBuyInSec < 0){
							$differenceBuyInSec = 0;
						}
						if($differenceBuyInSec >= 0 && $differenceBuyInSec < 15 ){
                          	$buySumTimeDelayRange1++; 
						}elseif($differenceBuyInSec >= 15 && $differenceBuyInSec < 30){
							$buySumTimeDelayRange2++;
						}elseif($differenceBuyInSec >= 30 && $differenceBuyInSec < 45){
							$buySumTimeDelayRange3++;
						}elseif($differenceBuyInSec >= 45 && $differenceBuyInSec < 60){
							$buySumTimeDelayRange4++;
						}elseif($differenceBuyInSec >= 60 && $differenceBuyInSec < 75){
							$buySumTimeDelayRange5++;
						}elseif($differenceBuyInSec >= 75 && $differenceBuyInSec < 90 ){
							$buySumTimeDelayRange6++;
						}elseif($differenceBuyInSec >= 90){
							$buySumTimeDelayRange7++;
						}

						// sold Pl slippage calculate
						if(isset($value1['sell_market_price']) && $value1['is_sell_order'] == 'sold' && $value1['sell_market_price'] !="" && !is_string($value1['sell_market_price'])){
							$val1 = $value1['market_sold_price'] - $value1['sell_market_price']; 
							$val2 = ($value1['market_sold_price'] + $value1['sell_market_price'])/ 2;
							$slippageOrignalPercentage = ($val1/ $val2) * 100;
							$slippageOrignalPercentage = round($slippageOrignalPercentage, 3) . '%';
						}else{
							$slippageOrignalPercentage = 0;
						}

						if($slippageOrignalPercentage > 0){
							$slippageOrignalPercentage = 0;
						}

						if($slippageOrignalPercentage <= 0 && $slippageOrignalPercentage > -0.2 ){

                          	$sumPLSllipageRange1++; 
						}elseif($slippageOrignalPercentage <= -0.2 && $slippageOrignalPercentage > -0.3){
							
							$sumPLSllipageRange2++;
						}elseif($slippageOrignalPercentage <= -0.3 && $slippageOrignalPercentage > -0.5){
							
							$sumPLSllipageRange3++;
						}elseif($slippageOrignalPercentage <= -0.5 && $slippageOrignalPercentage > -0.75){
							
							$sumPLSllipageRange4++;
						}elseif($slippageOrignalPercentage <= -1 ){
							
							$sumPLSllipageRange5++;
						}
						
					} //end sold foreach

					// if manul sold greater than 0 add in avg parofit 
					if($manul_sold_purchase_price > 0)
					{
						$sold_puchase_price += $manul_sold_purchase_price;
						$manul_sold_purchase_price = 0;
					}

					// if CSL sold greater than 0 add in avg parofit 
					if($CSL_sold_purchase_price > 0)
					{
						$sold_puchase_price += $CSL_sold_purchase_price;
						$CSL_sold_purchase_price = 0;
					}
					if($manul_sold_purchase_price != "0"){

						$per_trade_sold_manul = (float) $manul_sold_purchase_price * 100;
						$avg_manul = (float) ($per_trade_sold_manul / (count($total_sold_rec)));
					}
					if($sold_puchase_price !="0"){

						$per_trade_sold = (float) $sold_puchase_price * 100;
						$avg_sold = (float) ($per_trade_sold / count($total_sold_rec));    
					}
					if($CSL_sold_purchase_price !="0"){

						$CSL_per_trade_sold = (float) $CSL_sold_purchase_price * 100;
						$avg_sold_CSL = (float) ($CSL_per_trade_sold / count($total_sold_rec));
					}
				}//end response > 0 check 

				
				print_r("<br>oppertunity_id=".$value['opportunity_id']."<br>"); 	
				// /////////////////////////////////////////////////////////////////////////END SOLD AVG

				$total_orders = count($total_open_lth_rec) + count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $totalOther;
				$disappear = $parents_executed -  $total_orders;
				$total = count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $disappear;
				if($total == $parents_executed ) {
					$sell_commision_qty_USDT =   ($sell_fee_respected_coin > 0)  ? convertCoinBalanceIntoUSDT($value['coin'], $sell_fee_respected_coin, 'binance') : 0;
					$update_fields = array(
						'open_lth'     			=> 	count($total_open_lth_rec),
						'new_error'    			=> 	count($total_new_error_rec),
						'minOrderSoldPrice' 	=> 	$soldMinPrice[0]['minPrice'],
						'maxOrderSoldPrice' 	=> 	$soldMaxPrice[0]['maxPrice'],
						'reumed_child' 			=> 	count($total_reumed) + count($total_reumed_sold),       
						'costAvgCount' 			=> 	($costAvgReturn + $soldCostAvgReturn),
						'costAvgCount_child' 	=> 	($costAvgReturn_child + $soldCostAvgReturn_child),
						'costAvgCount_child_buy' 	=> 	$costAvgReturn_child,
						'costAvgCount_child_sold' 	=> 	$soldCostAvgReturn_child,
						'cost_avg_active_parents' 	=> 	$cost_avg_parent,
						'costAvgCount_child_canceled' => $costAvgReturn_child_canceled,
						'cancelled'    			=> 	count($total_cancel_rec),
						'sold'         			=> 	count($total_sold_rec),         
						'avg_open_lth' 			=> 	$open_lth_avg,
						'other_status' 			=> 	$totalOther,
						'sellTimeDiffRange1' 	=>	$sumTimeDelayRange1 ,
						'sellTimeDiffRange2' 	=>	$sumTimeDelayRange2 ,
						'sellTimeDiffRange3' 	=> 	$sumTimeDelayRange3 ,
						'sellTimeDiffRange4' 	=>	$sumTimeDelayRange4 ,
						'sellTimeDiffRange5' 	=> 	$sumTimeDelayRange5 ,
						'sellTimeDiffRange6' 	=>	$sumTimeDelayRange6 ,
						'sellTimeDiffRange7' 	=>	$sumTimeDelayRange7 ,
						'buySumTimeDelayRange1' => $buySumTimeDelayRange1 ,
						'buySumTimeDelayRange2' => $buySumTimeDelayRange2 ,
						'buySumTimeDelayRange3' => $buySumTimeDelayRange3 ,
						'buySumTimeDelayRange4' => $buySumTimeDelayRange4 ,
						'buySumTimeDelayRange5' => $buySumTimeDelayRange5 ,
						'buySumTimeDelayRange6' => $buySumTimeDelayRange6 ,
						'buySumTimeDelayRange7' => $buySumTimeDelayRange7 ,
						'sumPLSllipageRange1'	=> $sumPLSllipageRange1 ,
						'sumPLSllipageRange2' 	=> $sumPLSllipageRange2 ,
						'sumPLSllipageRange3'	=> $sumPLSllipageRange3 ,
						'sumPLSllipageRange4' 	=> $sumPLSllipageRange4 ,
						'sumPLSllipageRange5' 	=> $sumPLSllipageRange5 ,
						'avg_sold'     			=> 	$avg_sold,
						'per_trade_sold' 		=> 	$per_trade_sold,
						'avg_manul'    			=>	$avg_manul,
						'avg_sold_CSL' 			=> 	$avg_sold_CSL,
						'sell_commission' 		=> $sell_comssion_bnb,
						'sell_commision_qty_USDT' => $sell_commision_qty_USDT,
						'sell_fee_respected_coin' => $sell_fee_respected_coin,
						'modified_date' 		=> $current_time_date  
						
					);
					if(isset($value['10_max_value']) && isset($value['5_max_value'])){
						$update_fields['is_modified']  = true;
					}
					$cost_avg_child_sum = $costAvgReturn_child+$soldCostAvgReturn_child + $costAvgReturn_child_canceled;
					if(count($total_open_lth_rec)== 0 && count($total_sold_rec) == 0 &&  $totalOther == 0 && $cost_avg_child_sum == 0){
						$update_fields['oppertunity_missed'] = true;
					}
				}else { 
					$update_fields = array(
						'open_lth'     			=> 	count($total_open_lth_rec),
						'new_error'    			=> 	count($total_new_error_rec),
						'cancelled'    			=> 	count($total_cancel_rec),
						'costAvgCount' 			=> 	($costAvgReturn + $soldCostAvgReturn),
						'reumed_child' 			=> 	count($total_reumed) + count($total_reumed_sold) ,
						'costAvgCount_child' 	=> 	($costAvgReturn_child + $soldCostAvgReturn_child),
						'costAvgCount_child_buy' 	=> 	$costAvgReturn_child,
						'costAvgCount_child_sold' 	=> 	$soldCostAvgReturn_child,
						'cost_avg_active_parents' 	=> 	$cost_avg_parent,
						'costAvgCount_child_canceled' => $costAvgReturn_child_canceled,
						'sold'         			=> 	count($total_sold_rec),
						'avg_open_lth' 			=> 	$open_lth_avg,
						'sellTimeDiffRange1' 	=>	$sumTimeDelayRange1 ,
						'sellTimeDiffRange2' 	=>	$sumTimeDelayRange2 ,
						'sellTimeDiffRange3' 	=> 	$sumTimeDelayRange3 ,
						'sellTimeDiffRange4' 	=>	$sumTimeDelayRange4 ,
						'sellTimeDiffRange5' 	=> 	$sumTimeDelayRange5 ,
						'sellTimeDiffRange6' 	=>	$sumTimeDelayRange6 ,
						'sellTimeDiffRange7' 	=>	$sumTimeDelayRange7 ,
						'buySumTimeDelayRange1' => $buySumTimeDelayRange1 ,
						'buySumTimeDelayRange2' => $buySumTimeDelayRange2 ,
						'buySumTimeDelayRange3' => $buySumTimeDelayRange3 ,
						'buySumTimeDelayRange4' => $buySumTimeDelayRange4 ,
						'buySumTimeDelayRange5' => $buySumTimeDelayRange5 ,
						'buySumTimeDelayRange6' => $buySumTimeDelayRange6 ,
						'buySumTimeDelayRange7' => $buySumTimeDelayRange7 ,
						'sumPLSllipageRange1' 	=> $sumPLSllipageRange1 ,
						'sumPLSllipageRange2' 	=> $sumPLSllipageRange2 ,
						'sumPLSllipageRange3'	=> $sumPLSllipageRange3 ,
						'sumPLSllipageRange4' 	=> $sumPLSllipageRange4 ,
						'sumPLSllipageRange5' 	=> $sumPLSllipageRange5 ,
						'avg_sold'     			=> 	$avg_sold,
						'per_trade_sold' 		=> 	$per_trade_sold,
						'other_status' 			=> 	$totalOther, 
						'minOrderSoldPrice' 	=> 	$soldMinPrice[0]['minPrice'],
						'maxOrderSoldPrice' 	=> 	$soldMaxPrice[0]['maxPrice'],  
						'avg_manul'    			=>	$avg_manul,
						'avg_sold_CSL' 			=> 	$avg_sold_CSL,
						'modified_date'			=>	$current_time_date
					);
				}



				$sell_btc_converted = ($btc_sell > 0)  ? convertCoinBalanceIntoUSDT($value['coin'], $btc_sell, 'binance') : 0;
				$update_fields['sell_btc_in_$'] =   (float)$sell_btc_converted;
				$update_fields['sell_usdt']   	=   (float)$usdt_sell;
				$update_fields['total_sell_in_usdt'] = (float)($sell_btc_converted + $usdt_sell );

				if($buy_fee_respected_coin > 0 && !isset($value['buy_commision_qty']) && !isset($value['is_modified'])){

					$update_fields['buy_commision_qty']      =   $buy_fee_respected_coin;
					$update_fields['buy_commision_qty_USDT'] =   ($buy_fee_respected_coin > 0)  ? convertCoinBalanceIntoUSDT($value['coin'], $buy_fee_respected_coin, 'binance') : 0;

				}
				if($buy_commision_bnb > 0 && !isset($value['buy_commision']) && !isset($value['is_modified'])){
					
					$update_fields['buy_commision'] = $buy_commision_bnb;
				}
				$db = $this->mongo_db->customQuery();

				$pipeline = [
					[
						'$match' =>[
							'application_mode' => 'live',
							'parent_status' => ['$exists' => false ],
							'opportunityId' => $value['opportunity_id'],
							'status' => ['$in'=>['LTH','FILLED']],
						],
					],
					[
						'$sort' =>['created_date'=> -1],
					],
					['$limit'=>1]
				];
				$result_buy = $db->buy_orders->aggregate($pipeline);
				$res = iterator_to_array($result_buy);

				$pipeline1 = [
					[
						'$match' =>[
							'application_mode' => 'live',
							'parent_status' => ['$exists' => false ],
							'opportunityId' => $value['opportunity_id'],
							'status' => ['$in'=>['LTH','FILLED']],
						],
					],
					[
					'$sort' =>['created_date'=> 1],
					],
					['$limit'=>1]
				];
				$result_buy1 = $db->buy_orders->aggregate($pipeline1);
				$res1 = iterator_to_array($result_buy1);
				if(!isset($value['first_order_buy']) && !isset($value['last_order_buy'])){
					
					$update_fields['first_order_buy'] =  $res[0]['created_date'];
					$update_fields['last_order_buy'] =  $res1[0]['created_date'];
				}

				if(!isset($value['opp_came_binance']) && !isset($value['opp_came_kraken']) && !isset($value['opp_came_bam'])){	
					$opper_search['application_mode']= 'live';
					$opper_search['opportunityId'] = $value['opportunity_id'];	
					$connetct= $this->mongo_db->customQuery();
					$pending_curser = $connetct->buy_orders->find($opper_search);
					$buy_order = iterator_to_array($pending_curser);
					$pending_curser_buy = $connetct->sold_buy_order->find($opper_search);
					$sold_bbuy_order = iterator_to_array($pending_curser_buy);

					if(count($buy_order) > 0 || count($sold_bbuy_order) > 0 ){
						$update_fields['opp_came_binance'] = '1';
					}else{
						$update_fields['opp_came_binance'] = '0';
					}
					
					$this->mongo_db->where($opper_search);
					$response_kraken = $this->mongo_db->get('buy_orders_kraken');
					$data_kraken = iterator_to_array($response_kraken);

					$this->mongo_db->where($opper_search);
					$response_kraken_sold = $this->mongo_db->get('sold_buy_orders_kraken');
					$data_kraken_sold = iterator_to_array($response_kraken_sold);
					if(count($data_kraken) > 0 || count($data_kraken_sold) > 0){
						$update_fields['opp_came_kraken'] = '1';
					}else{
						$update_fields['opp_came_kraken'] = '0';
					}
					
					$this->mongo_db->where($opper_search);
					$response_bam = $this->mongo_db->get('buy_orders_bam');
					$data_bam = iterator_to_array($response_bam);

					$this->mongo_db->where($opper_search);
					$response_bam_sold = $this->mongo_db->get('sold_buy_orders_bam');
					$data_bam_sold = iterator_to_array($response_bam_sold);

					if(count($data_bam) > 0 || count($data_bam_sold) > 0){
						$update_fields['opp_came_bam'] = '1';
					}else{
						$update_fields['opp_came_bam'] = '0';
					}
				}

				if($btc > 0 && $usdt == 0 && !isset($value['usdt_invest_amount']) &&  !isset($value['btc_invest_amount'])){
					$update_fields['usdt_invest_amount'] = $btcusdt * $btc;//(float)$btc;
					$update_fields['btc_invest_amount']  = $btc;  //for chart view 
				}
				elseif($usdt > 0 && $btc == 0 && !isset($value['usdt_invest_amount']) && !isset($value['only_usdt_invest_amount'])) {
					$update_fields['usdt_invest_amount'] = $usdt;
					$update_fields['only_usdt_invest_amount'] = $usdt;  //for chart view
				} //end if ($total == $parents_executed ) 
			
				echo"<br><pre>";
				print_r($update_fields);

				$collection_name = 'opportunity_logs_binance';
				$countcheck = $db->$collection_name->count($search_update);

				echo "<br>count check =====>>>>>..".$countcheck;
				$check = $db->$collection_name->updateOne($search_update,  ['$set' => $update_fields]);
				echo "<br>modified count: ".$check->getModifiedCount();
				

				// echo "<br>modified count: ". $upateCheck->getModifiedCount();
			}
		} //end foreach
		echo "<br>current time".$current_date_time;
		echo "<br>SuccessFully Done!!!";

	} //end cron

	/////////////////////////////////////////////////////////////////////////////
	//////////////////////            ASIM CRONE KRAKEN          ////////////////
	/////////////////////////////////////////////////////////////////////////////
	// insertion of latest opportunity into log_collection kraken						
	public function insert_latest_oppertunity_into_log_collection_kraken(){
		$marketPrices = marketPrices('kraken');					
		$this->load->helper('new_common_helper');    
		foreach($marketPrices as $price){
			if($price['_id'] == 'XRPBTC'){          
				$xrpbtc = (float)$price['price'];
			}elseif($price['_id'] == 'BTCUSDT'){
				$btcusdt = (float)$price['price'];
			}elseif($price['_id'] == 'LINKBTC'){
				$linkbtc = (float)$price['price'];
			}elseif($price['_id'] == 'XLMBTC'){
				$xlmbtc = (float)$price['price'];
			}elseif($price['_id'] == 'ETHBTC'){
				$ethbtc = (float)$price['price'];
			}elseif($price['_id'] == 'XMRBTC'){
				$xmrbtc = (float)$price['price'];
			}elseif($price['_id'] == 'ADABTC'){
				$adabtc = (float)$price['price'];
			}elseif($price['_id'] == 'QTUMBTC'){
				$qtumbtc = (float)$price['price'];
			}elseif($price['_id'] == 'TRXBTC'){
				$trxbtc = (float)$price['price'];
			}elseif($price['_id'] == 'XRPUSDT'){
				$xrpusdt = (float)$price['price'];
			}elseif($price['_id'] == 'LTCUSDT'){
				$ltcusdt = (float)$price['price'];
			}elseif($price['_id'] == 'EOSBTC'){      
				$eosbtc = (float)$price['price'];
			}elseif($price['_id'] == 'EOSUSDT'){      
				$eosusdt = (float)$price['price'];
			}elseif($price['_id'] == 'ETCBTC'){       
				$etcbtc = (float)$price['price'];
			}elseif($price['_id'] == 'DASHBTC'){       
				$dashbtc = (float)$price['price'];
			}elseif($price['_id'] == 'DOTUSDT'){       
				$dotusdt = (float)$price['price'];
			}elseif($price['_id'] == 'ETHUSDT'){       
				$ethusdt = (float)$price['price'];
			}

		}  
		$current_date_time =  date('Y-m-d H:i:s');
		$current_time_date =  $this->mongo_db->converToMongodttime($current_date_time);
		
		$current_hour =  date('Y-m-d H:i:s', strtotime('-40 minutes'));
		$orig_date1 = $this->mongo_db->converToMongodttime($current_hour);

		$previous_one_month_date_time = date('Y-m-d H:i:s', strtotime(' - 1 month'));
		// $previous_one_month_date_time = date('Y-01-1 00:00:00');
		$pre_date_1 =  $this->mongo_db->converToMongodttime($previous_one_month_date_time);

		$connection = $this->mongo_db->customQuery();      
		$condition = array('sort' => array('created_date' => -1), 'limit'=>3);

		if(!empty($this->input->get())){
			$where['opportunity_id'] = $this->input->get('opportunityId');
		}else{
			$where['mode'] ='live';
			$where['created_date'] = array('$gte'=>$pre_date_1);
			$where['level'] = array('$ne'=>'level_15');
			$where['is_modified'] = array('$exists'=>false);
			$where['modified_date'] = array('$lte'=>$orig_date1);
		}  
		
		$find_rec = $connection->opportunity_logs_kraken->find($where,  $condition);
		$response = iterator_to_array($find_rec);

		foreach ($response as $value){
			$coin= $value['coin'];
			if(isset($value['sendHitTime']) && !empty($value['sendHitTime'])){
				// $sendHitTime = $value['sendHitTime'];
				$sendHitTime    = strtotime($value['sendHitTime']->toDateTime()->format('Y-m-d H:i:s'));

			}else{
				// $sendHitTime = $value['created_date'];
				$sendHitTime    = strtotime($value['created_date']->toDateTime()->format('Y-m-d H:i:s'));
			}
			$start_date = $value['created_date']->toDateTime()->format("Y-m-d H:i:s");
			$timestamp = strtotime($start_date);
			$time = $timestamp + (5 * 60 * 60);
			$end_date = date("Y-m-d H:i:s", $time);

			$hours_10 = $timestamp + (10 * 60 * 60);
			$time_10_hours = date("Y-m-d H:i:s", $hours_10);

			$cidition_check = $this->mongo_db->converToMongodttime($end_date);
			$cidition_check_10 = $this->mongo_db->converToMongodttime($time_10_hours);
			$params =[];
			$params = [
			'coin'       => $value['coin'],
			'start_date' => (string)$start_date,
			'end_date'   => (string)$end_date,
			];
			
			if($cidition_check <= $current_time_date){
				$jsondata = json_encode($params);
				$curl = curl_init();
				curl_setopt_array($curl, array(	
					CURLOPT_URL => "http://35.171.172.15:3000/api/minMaxMarketPrices",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS =>$jsondata,
					CURLOPT_HTTPHEADER => array("Content-Type: application/json"), 
				));
				$response_price = curl_exec($curl);	
				curl_close($curl);                                
				$api_response = json_decode($response_price);
			} // main if check for time comapire
			$params_10_hours = [];
			$params_10_hours = [
				'coin'       => $value['coin'],
				'start_date' => (string)$start_date,
				'end_date'   => (string)$time_10_hours,
			];
			if($cidition_check_10 <= $current_time_date){
				$jsondata = json_encode($params_10_hours);
					$curl_10 = curl_init();
					curl_setopt_array($curl_10, array(
					CURLOPT_URL => "http://35.171.172.15:3000/api/minMaxMarketPrices",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS =>$jsondata,
					CURLOPT_HTTPHEADER => array(
						"Content-Type: application/json"
					), 
					));
					$response_price_10 = curl_exec($curl_10);	
					curl_close($curl_10);
					$api_response_10 = json_decode($response_price_10);
					//echo "<pre>";print_r($api_response_10);
			}
			if ($value['level'] != 'level_15' ){
				$open_lth_avg_per_trade = 0;
				$open_lth_avg = 0;
				$avg_sold = 0;
				$parents_executed = 0;
				$parents_executed = $value['parents_executed'];
				
				$search_update['opportunity_id'] = $value['opportunity_id'];
				$search_update['mode']= 'live';
				//////////////////////////////////////////////////////////////
				$other['application_mode']= 'live';
				$other['opportunityId'] =  $value['opportunity_id'];
				$other['status'] = array('$nin' => array('LTH', 'FILLED','canceled','new_ERROR'));
				$buyOther = $connection->buy_orders_kraken->count($other);

				$otherSold['application_mode']= 'live';
				$otherSold['opportunityId'] =  $value['opportunity_id'];
				$otherSold['is_sell_order'] = array('$nin' => array('sold'));
				$otherStatusSold = $connection->sold_buy_orders_kraken->count($otherSold);
				$totalOther = $buyOther + $otherStatusSold;
				/////////////////////////////////////////////////////////

				$search_open_lth['application_mode']		= 	'live';
				$search_open_lth['opportunityId'] 			= 	$value['opportunity_id'];
				$search_open_lth['status']					= 	array('$in' => array('LTH', 'FILLED'));
				$search_open_lth['resume_status']['$ne'] 	= 	'resume';
				$search_open_lth['cost_avg']['$ne'] 		= 	'yes';
				$search_open_lth['cavg_parent']['$ne'] 		= 	'yes';
				$search_open_lth['cavg_parent'] 			= 	['$exists' => false];
				$search_open_lth['cost_avg']				=	['$nin' => ['yes', 'completed', '']];

				print_r("<br>oppertunity_id=".$value['opportunity_id']);

				/////
				$search_cancel['application_mode']	= 'live';
				$search_cancel['opportunityId'] 	= $value['opportunity_id'];
				$search_cancel['status'] 			= array('$in' => array('canceled'));
				$search_cancel['cavg_parent'] 		= 	['$exists' => false];
				$search_cancel['cost_avg']			=	['$nin' => ['yes', 'completed', '']];
				//////
				$search_new_error['application_mode']		= 	'live';
				$search_new_error['opportunityId'] 			= 	$value['opportunity_id'];
				$search_new_error['status'] 				= 	array('$in' => array('new_ERROR'));
				$search_new_error['cavg_parent'] 			= 	['$exists' => false];
				$search_new_error['cost_avg']				=	['$nin' => ['yes', 'completed', '']];
				////////
				$search_sold['application_mode']		= 	'live';
				$search_sold['opportunityId'] 			= 	$value['opportunity_id'];
				$search_sold['is_sell_order'] 			= 	'sold';
				$search_sold['cavg_parent'] 			= 	['$exists' => false];
				$search_sold['cost_avg']				=	['$nin' => ['yes', 'completed', '']];

				$search_resumed['application_mode']		= 	'live';
				$search_resumed['opportunityId'] 		= 	$value['opportunity_id'];
				$search_resumed['resume_status'] 		= 	array('$in' => array('resume'));
				$search_resumed['cavg_parent'] 			= 	['$exists' => false];
				$search_resumed['cost_avg']				=	['$nin' => ['yes', 'completed', '']];

				$this->mongo_db->where($search_resumed);
				$total_reumed = $this->mongo_db->get('buy_orders_kraken');
				$total_reumed_order   = iterator_to_array($total_reumed);   

				$this->mongo_db->where($search_resumed);
				$total_reumed_sold = $this->mongo_db->get('sold_buy_orders_kraken');
				$total_reumed_sold_orders   = iterator_to_array($total_reumed_sold);

				$minPriceLookUp = [
					[
						'$match' => [
							'application_mode' => 'live',
							'opportunityId'    =>  $value['opportunity_id'],
							'is_sell_order'    =>  'sold'
							]
					],

					[
						'$group' =>[
							'_id' => '$symbol',
							'minPrice' => ['$min' => '$market_sold_price']
						]
					],

				];
	
				$minSoldPrice = $connection->sold_buy_orders_kraken->aggregate($minPriceLookUp);
				$soldMinPrice  = iterator_to_array($minSoldPrice);

				$maxPriceLookUp = [
					[
						'$match' => [
							'application_mode' => 'live',
							'opportunityId'    =>  $value['opportunity_id'],
							'is_sell_order'    =>  'sold'
						]
					],

					[
						'$group' =>[
							'_id' => '$symbol',
							'maxPrice' => ['$max' => '$market_sold_price']
						]
					],

				];

				$maxSoldPrice = $connection->sold_buy_orders_kraken->aggregate($maxPriceLookUp);
				$soldMaxPrice  = iterator_to_array($maxSoldPrice);

				/////////////////////////////////////////////////////////////// 
				$cosAvg['application_mode'] = 'live';
				$cosAvg['opportunityId']    = $value['opportunity_id'];
				$cosAvg['cost_avg']['$ne']  = 'completed';
				$cosAvg['cavg_parent']      = 'yes';

				$cosAvgSold['application_mode']   =  'live';
				$cosAvgSold['opportunityId']      =  $value['opportunity_id'];
				$cosAvgSold['is_sell_order']      =  'sold';
				$cosAvgSold['cost_avg']           =  'completed';
				$cosAvgSold['cavg_parent']        =  'yes';

				$costAvgReturn = $connection->buy_orders_kraken->count($cosAvg);
				$soldCostAvgReturn = $connection->sold_buy_orders_kraken->count($cosAvgSold); 
				///////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////
				$cosAvg_parent['application_mode']	= 	'live';
				$cosAvg_parent['symbol'] 	= 	$value['coin'];
				$cosAvg_parent['cost_avg']['$ne'] 	= 	'completed';
				$cosAvg_parent['is_sell_order']	= 	'yes';
				$cosAvg_parent['cavg_parent'] 		= 	'yes';
				$cost_avg_parent = $connection->buy_orders_kraken->count($cosAvg_parent);
				///////////////////////////////////////////////////////////////
				/////////////////////////////////////////////////////////////// 
				$cosAvg_child['application_mode']	= 'live';
				$cosAvg_child['opportunityId'] 	    = $value['opportunity_id'];
				$cosAvg_child['cost_avg']     = ['$in' => ['yes','taking_child']];
				$cosAvg_child['is_sell_order']	    = ['$ne'=>'sold'];
				$cosAvg_child['status']	    = ['$ne'=>'canceled'];
				$cosAvg_child['cavg_parent'] 		= ['$exists' => false];
				$cosAvg_child['parent_status'] 		= ['$exists' => false];

				$cosAvg_child_canceled['application_mode']	= 'live';
				$cosAvg_child_canceled['opportunityId'] 	    = $value['opportunity_id'];
				$cosAvg_child_canceled['cost_avg']     = ['$in' => ['yes','taking_child']];
				$cosAvg_child_canceled['is_sell_order']	    = ['$ne'=>'sold'];
				$cosAvg_child_canceled['status']	    = ['$eq'=>'canceled'];
				$cosAvg_child_canceled['cavg_parent'] 		= ['$exists' => false];
				$cosAvg_child_canceled['parent_status'] 		= ['$exists' => false];

				$cosAvgSold_child['application_mode']	= 'live';
				$cosAvgSold_child['opportunityId'] 	= $value['opportunity_id'];
				$cosAvgSold_child['is_sell_order'] 	= 'sold';
				$cosAvgSold_child['cost_avg'] 		= ['$in' => ['yes', 'completed', 'taking_child']];
				$cosAvgSold_child['cavg_parent']    = ['$exists' => false];
				
				$costAvgReturn_child = $connection->buy_orders_kraken->count($cosAvg_child);
				$costAvgReturn_child_canceled = $connection->buy_orders_kraken->count($cosAvg_child_canceled);
				$soldCostAvgReturn_child = $connection->sold_buy_orders_kraken->count($cosAvgSold_child);  
				/////////////////////////////////////////////////////////////// 


				$this->mongo_db->where($search_open_lth);
				$total_open = $this->mongo_db->get('buy_orders_kraken');
				$total_open_lth_rec   = iterator_to_array($total_open);

				$this->mongo_db->where($search_cancel);
				$total_cancel = $this->mongo_db->get('buy_orders_kraken');
				$total_cancel_rec   = iterator_to_array($total_cancel);

				$this->mongo_db->where($search_new_error);
				$total_new_error = $this->mongo_db->get('buy_orders_kraken');
				$total_new_error_rec   = iterator_to_array($total_new_error);

				$this->mongo_db->where($search_sold);
				$total_sold_total = $this->mongo_db->get('sold_buy_orders_kraken');
				$total_sold_rec   = iterator_to_array($total_sold_total);
				
				$open_lth_puchase_price = 0;
				$open_lth_avg = 0;
				$btc = 0;
				$usdt = 0;

				$buySumTimeDelayRange1 = 0;
				$buySumTimeDelayRange2 = 0;
				$buySumTimeDelayRange3 = 0;
				$buySumTimeDelayRange4 = 0;
				$buySumTimeDelayRange5 = 0;
				$buySumTimeDelayRange6 = 0;
				$buySumTimeDelayRange7 = 0;
				$buy_commision_bnb = 0;
				$buy_fee_respected_coin = 0;

				$open_lth_avg_per_trade= 0;
				echo"<br>Total open lth = ".count($total_open_lth_rec);
				if (count($total_open_lth_rec) > 0){
					echo "<br> Open/lth Calculation";
					foreach ($total_open_lth_rec as $key => $value2){
						$commission_array = $value2['buy_fraction_filled_order_arr'];
						if($value2['symbol'] == 'ETHBTC'){    
							$open_lth_puchase_price += (float) ($ethbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];
							echo "<br> btc = ".$btc;

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'BTCUSDT'){
							$open_lth_puchase_price += (float) ($btcusdt - $value2['purchased_price']) / $value2['purchased_price'] ;  
							$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];
							echo "<br> usdt = ".$usdt;
							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}
						}elseif($value2['symbol'] == 'XRPBTC'){
							$open_lth_puchase_price += (float) ($xrpbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];
							echo "<br> btc = ".$btc;

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}
						}elseif($value2['symbol'] == 'XRPUSDT'){
							$open_lth_puchase_price += (float) ($xrpusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];
							echo "<br> usdt = ".$usdt;

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'LINKBTC'){
							$open_lth_puchase_price += (float) ($linkbtc - $value2['purchased_price']) / $value2['purchased_price'];;
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];


							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'XLMBTC'){
							$open_lth_puchase_price += (float) ($xlmbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'XMRBTC'){
							$open_lth_puchase_price += (float) ($xmrbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'ADABTC'){
							$open_lth_puchase_price += (float) ($adabtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'QTUMBTC'){
							$open_lth_puchase_price += (float) ($qtumbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'TRXBTC'){
							$open_lth_puchase_price += (float) ($trxbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'LTCUSDT'){
							$open_lth_puchase_price += (float) ($ltcusdt - $value2['purchased_price']) / $value2['purchased_price'];
							$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'ETHUSDT'){
							$open_lth_puchase_price += (float) ($ethusdt - $value2['purchased_price']) / $value2['purchased_price'];
							$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'DOTUSDT'){
							$open_lth_puchase_price += (float) ($dotusdt - $value2['purchased_price']) / $value2['purchased_price'];
							$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'EOSBTC'){
							$open_lth_puchase_price += (float) ($eosbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'ETCBTC'){
							$open_lth_puchase_price += (float) ($etcbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'EOSUSDT'){
							$open_lth_puchase_price += (float) ($eosusdt - $value2['purchased_price']) / $value2['purchased_price'];
							$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'DASHBTC'){  
							$open_lth_puchase_price += (float) ($dashbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}			    

						if( isset($value2['created_date']) && !empty($value2['created_date']) ){

							$orderBUyTime  		= strtotime($value2['created_date']->toDateTime()->format('Y-m-d H:i:s'));

							$differenceBuyInSec = ($orderBUyTime - $sendHitTime);
						}else{
							$differenceBuyInSec = 0;
						}
						if($differenceBuyInSec >= 0 && $differenceBuyInSec < 15 ){
                          	$buySumTimeDelayRange1++; 
						}elseif($differenceBuyInSec >= 15 && $differenceBuyInSec < 30){
							$buySumTimeDelayRange2++;
						}elseif($differenceBuyInSec >= 30 && $differenceBuyInSec < 45){
							$buySumTimeDelayRange3++;
						}elseif($differenceBuyInSec >= 45 && $differenceBuyInSec < 60){
							$buySumTimeDelayRange4++;
						}elseif($differenceBuyInSec >= 60 && $differenceBuyInSec < 75){
							$buySumTimeDelayRange5++;
						}elseif($differenceBuyInSec >= 75 && $differenceBuyInSec < 90 ){
							$buySumTimeDelayRange6++;
						}elseif($differenceBuyInSec >= 90){
							$buySumTimeDelayRange7++;
						}

					}//end loop
					$open_lth_avg_per_trade = (float) $open_lth_puchase_price * 100;
					$open_lth_avg = (float) ($open_lth_avg_per_trade / count($total_open_lth_rec));
				}//end if
		
				$sold_puchase_price = 0;
				$avg_sold_CSL = 0;
				$CSL_per_trade_sold = 0;
				$CSL_sold_purchase_price = 0 ;
				$avg_manul = 0;
				$per_trade_sold_manul = 0;
				$manul_sold_purchase_price = 0;
				$avg_sold = 0;
				$per_trade_sold = 0;

				$sumTimeDelayRange1 = 0;
				$sumTimeDelayRange2 = 0;
				$sumTimeDelayRange3 = 0;
				$sumTimeDelayRange4 = 0;
				$sumTimeDelayRange5 = 0;
				$sumTimeDelayRange6 = 0;
				$sumTimeDelayRange7 = 0;

				$sumPLSllipageRange1 = 0;
				$sumPLSllipageRange2 = 0;
				$sumPLSllipageRange3 = 0;
				$sumPLSllipageRange4 = 0;
				$sumPLSllipageRange5 = 0;
				$sell_fee_respected_coin = 0;
				$sell_comssion_bnb = 0;
				$btc_sell  = 0;
				$usdt_sell = 0;

				if (count($total_sold_rec) > 0){
					echo "<br> sold calculation";
					foreach ($total_sold_rec as $key => $value1){
						$commission_sold_array = $value1['buy_fraction_filled_order_arr'];
						$sell_commission_sold_array = $value1['sell_fraction_filled_order_arr'];
						if($value1['symbol'] == 'ETHBTC'){ 
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}
						}elseif($value1['symbol'] == 'XRPBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'NEOBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];


							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'QTUMBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];


							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'XLMBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'TRXBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'ETCBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'EOSBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'LINKBTC'){  
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'XMRBTC'){  
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'ADABTC'){       
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'LTCUSDT'){        
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'ETHUSDT'){        
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'DOTUSDT'){        
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'BTCUSDT'){    
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'DASHBTC'){  
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sold_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
									echo "<br>sell commission BTC = ".$sell_comssion_bnb;
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
									echo "<br>buy commission BTC = ".$buy_commision_bnb;	
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}
						if(isset($value1['is_manual_sold'])){
							$manul_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];

						}elseif(isset($value1['csl_sold'])){
							$CSL_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
						}else{
							$sold_puchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];

						}

						if(isset($value1['order_send_time']) && isset($value1['sell_date']) && !empty($value1['order_send_time']) && !empty($value1['sell_date']) && $value1['is_sell_order'] == "sold"){

							$filledTime     = strtotime($value1['sell_date']->toDateTime()->format('Y-m-d H:i:s'));
							$orderSendTime  = strtotime($value1['order_send_time']->toDateTime()->format('Y-m-d H:i:s'));

							$differenceInSec = ($filledTime - $orderSendTime);
						}else{
							$differenceInSec = 0;
						}
						if($differenceInSec >= 0 && $differenceInSec < 15 ){
                          	$sumTimeDelayRange1++; 
						}elseif($differenceInSec >= 15 && $differenceInSec < 30){
							$sumTimeDelayRange2++;
						}elseif($differenceInSec >= 30 && $differenceInSec < 45){
							$sumTimeDelayRange3++;
						}elseif($differenceInSec >= 45 && $differenceInSec < 60){
							$sumTimeDelayRange4++;
						}elseif($differenceInSec >= 60 && $differenceInSec < 75){
							$sumTimeDelayRange5++;
						}elseif($differenceInSec >= 75 && $differenceInSec < 90 ){
							$sumTimeDelayRange6++;
						}elseif($differenceInSec >= 90){
							$sumTimeDelayRange7++;
						}


						if( isset($value1['created_date']) && !empty($value1['created_date']) ){

							$orderBUyTime  		= strtotime($value1['created_date']->toDateTime()->format('Y-m-d H:i:s'));

							$differenceBuyInSec = ($orderBUyTime - $sendHitTime);
						}else{
							$differenceBuyInSec = 0;
						}
						if($differenceBuyInSec >= 0 && $differenceBuyInSec < 15 ){
                          	$buySumTimeDelayRange1++; 
						}elseif($differenceBuyInSec >= 15 && $differenceBuyInSec < 30){
							$buySumTimeDelayRange2++;
						}elseif($differenceBuyInSec >= 30 && $differenceBuyInSec < 45){
							$buySumTimeDelayRange3++;
						}elseif($differenceBuyInSec >= 45 && $differenceBuyInSec < 60){
							$buySumTimeDelayRange4++;
						}elseif($differenceBuyInSec >= 60 && $differenceBuyInSec < 75){
							$buySumTimeDelayRange5++;
						}elseif($differenceBuyInSec >= 75 && $differenceBuyInSec < 90 ){
							$buySumTimeDelayRange6++;
						}elseif($differenceBuyInSec >= 90){
							$buySumTimeDelayRange7++;
						}

						// sold Pl slippage calculate
						if(isset($value1['sell_market_price']) && $value1['is_sell_order'] == 'sold' && $value1['sell_market_price'] !="" && !is_string($value1['sell_market_price'])){
							$val1 = $value1['market_sold_price'] - $value1['sell_market_price']; 
							$val2 = ($value1['market_sold_price'] + $value1['sell_market_price'])/ 2;
							$slippageOrignalPercentage = ($val1/ $val2) * 100;
							$slippageOrignalPercentage = round($slippageOrignalPercentage, 3) . '%';
						}else{
							$slippageOrignalPercentage = 0;
						}

						if($slippageOrignalPercentage > 0){
							$slippageOrignalPercentage = 0;
						}

						if($slippageOrignalPercentage <= 0 && $slippageOrignalPercentage > -0.2 ){

                          	$sumPLSllipageRange1++; 
						}elseif($slippageOrignalPercentage <= -0.2 && $slippageOrignalPercentage > -0.3){
							
							$sumPLSllipageRange2++;
						}elseif($slippageOrignalPercentage <= -0.3 && $slippageOrignalPercentage > -0.5){
							
							$sumPLSllipageRange3++;
						}elseif($slippageOrignalPercentage <= -0.5 && $slippageOrignalPercentage > -0.75){
							
							$sumPLSllipageRange4++;
						}elseif($slippageOrignalPercentage <= -1 ){
							
							$sumPLSllipageRange5++;
						}
						
					} //end sold foreach
					if($manul_sold_purchase_price > 0){
						$sold_puchase_price += $manul_sold_purchase_price;
						$manul_sold_purchase_price = 0;
					}
					if($CSL_sold_purchase_price > 0)
					{
						$sold_puchase_price += $CSL_sold_purchase_price;
						$CSL_sold_purchase_price = 0;
					}
					if($manul_sold_purchase_price != "0"){
						$per_trade_sold_manul = (float) $manul_sold_purchase_price * 100;
						$avg_manul = (float) ($per_trade_sold_manul / count($total_sold_rec));;
					}
					if($sold_puchase_price !="0"){
						$per_trade_sold = (float) $sold_puchase_price * 100;
						$avg_sold = (float) ($per_trade_sold / count($total_sold_rec)); 
					}
					if($CSL_sold_purchase_price !="0"){
						$CSL_per_trade_sold = (float) $CSL_sold_purchase_price * 100;
						$avg_sold_CSL = (float) ($CSL_per_trade_sold / count($total_sold_rec));
					}
				}// End check >0
					
				$total_orders = count($total_open_lth_rec) + count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $totalOther;
				$disappear = $parents_executed -  $total_orders;
				$total = count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $disappear;
				if ($total == $parents_executed){

					$sell_commision_qty_USDT =   ($sell_fee_respected_coin > 0)  ? convertCoinBalanceIntoUSDT($value['coin'], $sell_fee_respected_coin, 'kraken') : 0;
					$update_fields = array(
						'open_lth'     => count($total_open_lth_rec),
						'new_error'    => count($total_new_error_rec),
						'cancelled'    => count($total_cancel_rec),
						'costAvgCount' => ($costAvgReturn + $soldCostAvgReturn),
						'costAvgCount_child' 	=> 	($costAvgReturn_child + $soldCostAvgReturn_child),
						'costAvgCount_child_buy' 	=> 	$costAvgReturn_child,
						'costAvgCount_child_sold' 	=> 	$soldCostAvgReturn_child,
						'cost_avg_active_parents' 	=> 	$cost_avg_parent,
						'costAvgCount_child_canceled' => $costAvgReturn_child_canceled,
						'sold'         => count($total_sold_rec),
						'reumed_child' => count($total_reumed_order) + count($total_reumed_sold_orders),
						'avg_open_lth' => $open_lth_avg,
						'other_status' => $totalOther,
						'avg_sold'     => $avg_sold,
						'per_trade_sold' => $per_trade_sold,
						'minOrderSoldPrice' => $soldMinPrice[0]['minPrice'],
						'maxOrderSoldPrice' => $soldMaxPrice[0]['maxPrice'],
						'avg_manul'    =>$avg_manul,
						'sellTimeDiffRange1' 	=>	$sumTimeDelayRange1 ,
						'sellTimeDiffRange2' 	=>	$sumTimeDelayRange2 ,
						'sellTimeDiffRange3' 	=> 	$sumTimeDelayRange3 ,
						'sellTimeDiffRange4' 	=>	$sumTimeDelayRange4 ,
						'sellTimeDiffRange5' 	=> 	$sumTimeDelayRange5 ,
						'sellTimeDiffRange6' 	=>	$sumTimeDelayRange6 ,
						'sellTimeDiffRange7' 	=>	$sumTimeDelayRange7 ,

						'buySumTimeDelayRange1' => $buySumTimeDelayRange1 ,
						'buySumTimeDelayRange2' => $buySumTimeDelayRange2 ,
						'buySumTimeDelayRange3' => $buySumTimeDelayRange3 ,
						'buySumTimeDelayRange4' => $buySumTimeDelayRange4 ,
						'buySumTimeDelayRange5' => $buySumTimeDelayRange5 ,
						'buySumTimeDelayRange6' => $buySumTimeDelayRange6 ,
						'buySumTimeDelayRange7' => $buySumTimeDelayRange7 ,

						'sumPLSllipageRange1' 	=> $sumPLSllipageRange1 ,
						'sumPLSllipageRange2' 	=> $sumPLSllipageRange2 ,
						'sumPLSllipageRange3'	=> $sumPLSllipageRange3 ,
						'sumPLSllipageRange4' 	=> $sumPLSllipageRange4 ,
						'sumPLSllipageRange5' 	=> $sumPLSllipageRange5 ,
						'sell_commission' 		=> $sell_comssion_bnb,
						'sell_commision_qty_USDT' => $sell_commision_qty_USDT,
						'sell_fee_respected_coin' => $sell_fee_respected_coin,

						'avg_sold_CSL' 			=> $avg_sold_CSL,
						'modified_date' 		=> $current_time_date  
					);

					if(isset($value['10_max_value']) && isset($value['5_max_value'])){
						$update_fields['is_modified']  = true;
					}
					$cost_avg_child_sum = $costAvgReturn_child+$soldCostAvgReturn_child+$costAvgReturn_child_canceled;
					if(count($total_open_lth_rec)== 0 && count($total_sold_rec) == 0 &&  $totalOther == 0 && $cost_avg_child_sum == 0){
						$update_fields['oppertunity_missed'] = true;
					}
				}else{ 
					$update_fields = array(
						'open_lth'     => count($total_open_lth_rec),
						'new_error'    => count($total_new_error_rec),
						'cancelled'    => count($total_cancel_rec),
						'sold'         => count($total_sold_rec),
						'avg_open_lth' => $open_lth_avg,
						'costAvgCount' => ($costAvgReturn + $soldCostAvgReturn),
						'costAvgCount_child' 	=> 	($costAvgReturn_child + $soldCostAvgReturn_child),
						'costAvgCount_child_buy' 	=> 	$costAvgReturn_child,
						'costAvgCount_child_canceled' 	=> 	$costAvgReturn_child_canceled,
						'costAvgCount_child_sold' 	=> 	$soldCostAvgReturn_child,
						'cost_avg_active_parents' 	=> 	$cost_avg_parent,
						'avg_sold'     => $avg_sold,
						'per_trade_sold' => $per_trade_sold,
						'minOrderSoldPrice' => $soldMinPrice[0]['minPrice'],
						'maxOrderSoldPrice' => $soldMaxPrice[0]['maxPrice'],
						'reumed_child' => count($total_reumed_order) + count($total_reumed_sold_orders),
						'other_status' => $totalOther,  
						'sellTimeDiffRange1' 	=>	$sumTimeDelayRange1 ,
						'sellTimeDiffRange2' 	=>	$sumTimeDelayRange2 ,
						'sellTimeDiffRange3' 	=> 	$sumTimeDelayRange3 ,
						'sellTimeDiffRange4' 	=>	$sumTimeDelayRange4 ,
						'sellTimeDiffRange5' 	=> 	$sumTimeDelayRange5 ,
						'sellTimeDiffRange6' 	=>	$sumTimeDelayRange6 ,
						'sellTimeDiffRange7' 	=>	$sumTimeDelayRange7 , 

						'buySumTimeDelayRange1' => $buySumTimeDelayRange1 ,
						'buySumTimeDelayRange2' => $buySumTimeDelayRange2 ,
						'buySumTimeDelayRange3' => $buySumTimeDelayRange3 ,
						'buySumTimeDelayRange4' => $buySumTimeDelayRange4 ,
						'buySumTimeDelayRange5' => $buySumTimeDelayRange5 ,
						'buySumTimeDelayRange6' => $buySumTimeDelayRange6 ,
						'buySumTimeDelayRange7' => $buySumTimeDelayRange7 ,

						'sumPLSllipageRange1' 	=> $sumPLSllipageRange1 ,
						'sumPLSllipageRange2' 	=> $sumPLSllipageRange2 ,
						'sumPLSllipageRange3'	=> $sumPLSllipageRange3 ,
						'sumPLSllipageRange4' 	=> $sumPLSllipageRange4 ,
						'sumPLSllipageRange5' 	=> $sumPLSllipageRange5 ,

						'avg_manul'    =>$avg_manul,
						'avg_sold_CSL' => $avg_sold_CSL,
						'modified_date'=>$current_time_date
					);
				}

				$sell_btc_converted = ($btc_sell > 0)  ? convertCoinBalanceIntoUSDT($value['coin'], $btc_sell, 'kraken') : 0;
				$update_fields['sell_btc_in_$'] =   (float)$sell_btc_converted;
				$update_fields['sell_usdt']   	=   (float)$usdt_sell;
				$update_fields['total_sell_in_usdt'] = (float)($sell_btc_converted + $usdt_sell );

				if($buy_fee_respected_coin > 0 && !isset($value['buy_commision_qty']) && !isset($value['is_modified'])){
					$update_fields['buy_commision_qty'] = $buy_fee_respected_coin;
					$update_fields['buy_commision_qty_USDT'] =   ($buy_fee_respected_coin > 0)  ? convertCoinBalanceIntoUSDT($value['coin'], $buy_fee_respected_coin, 'kraken') : 0;
				}
				if($buy_commision_bnb > 0 && !isset($value['buy_commision']) && !isset($value['is_modified'])){
					$update_fields['buy_commision'] = $buy_commision_bnb;
					echo"total commision = BNB ".$buy_commision_bnb;
				}

				$db = $this->mongo_db->customQuery();
				$pipeline = [
					[
						'$match' =>[
						'application_mode' => 'live',
						'parent_status' => ['$exists' => false ],
						'opportunityId' => $value['opportunity_id'],
						'status' => ['$in'=>['LTH','FILLED']],
						],
					],
					[
					'$sort' =>['created_date'=> -1],
					],
					['$limit'=>1]
				];
				$result_buy = $db->buy_orders_kraken->aggregate($pipeline);
				$res = iterator_to_array($result_buy);

				$pipeline1 = [
					[
						'$match' =>[
						'application_mode' => 'live',
						'parent_status' => ['$exists' => false ],
						'opportunityId' => $value['opportunity_id'],
						'status' => ['$in'=>['LTH','FILLED']],
						],
					],
					[
					'$sort' =>['created_date'=> 1],
					],
					['$limit'=>1]
				];
				$result_buy1 = $db->buy_orders_kraken->aggregate($pipeline1);
				$res1 = iterator_to_array($result_buy1);
				if(!isset($value['first_order_buy']) && !isset($value['last_order_buy'])){
					$update_fields['first_order_buy'] =  $res[0]['created_date'];
					$update_fields['last_order_buy'] =  $res1[0]['created_date'];
				}
				if(!isset($value['opp_came_binance']) && !isset($value['opp_came_kraken']) && !isset($value['opp_came_bam'])){	
					$opper_search['application_mode']= 'live';
					$opper_search['opportunityId'] = $value['opportunity_id'];
					
					$connetct= $this->mongo_db->customQuery();

					$pending_curser = $connetct->buy_orders->find($opper_search);
					$buy_order = iterator_to_array($pending_curser);
					echo "<br>result binance=".count($buy_order);

					$pending_curser_buy = $connetct->sold_buy_order->find($opper_search);
					$sold_bbuy_order = iterator_to_array($pending_curser_buy);
					echo "<br>result binance sold=".count($sold_bbuy_order);

					if(count($buy_order) > 0 || count($sold_bbuy_order) > 0 ){
						$update_fields['opp_came_binance'] = '1';
					}else{
						$update_fields['opp_came_binance'] = '0';
					}
					
					$this->mongo_db->where($opper_search);
					$response_kraken = $this->mongo_db->get('buy_orders_kraken');
					$data_kraken = iterator_to_array($response_kraken);

					$this->mongo_db->where($opper_search);
					$response_kraken_sold = $this->mongo_db->get('sold_buy_orders_kraken');
					$data_kraken_sold = iterator_to_array($response_kraken_sold);
					if(count($data_kraken) > 0 || count($data_kraken_sold) > 0){
						$update_fields['opp_came_kraken'] = '1';
					}else{
						$update_fields['opp_came_kraken'] = '0';
					}
					
					$this->mongo_db->where($opper_search);
					$response_bam = $this->mongo_db->get('buy_orders_bam');
					$data_bam = iterator_to_array($response_bam);

					$this->mongo_db->where($opper_search);
					$response_bam_sold = $this->mongo_db->get('sold_buy_orders_bam');
					$data_bam_sold = iterator_to_array($response_bam_sold);

					if(count($data_bam) > 0 || count($data_bam_sold) > 0){
						$update_fields['opp_came_bam'] = '1';
					}else{
						$update_fields['opp_came_bam'] = '0';
					}
				}

				if($btc > "0" && $usdt == "0" && !isset($value['usdt_invest_amount']) &&  !isset($value['btc_invest_amount'])){
					$update_fields['usdt_invest_amount'] = $btcusdt * $btc;
					$update_fields['btc_invest_amount']  = $btc;  //for chart view 
				}
				elseif($usdt > "0" && $btc == "0" && !isset($value['usdt_invest_amount']) && !isset($value['only_usdt_invest_amount'])) {
					$update_fields['usdt_invest_amount'] = $usdt;
					$update_fields['only_usdt_invest_amount'] = $usdt;  //for chart view
				} 

				foreach($api_response as $as_1){
					echo "testing".$as_1;
					if($as_1->max_price !='' && $as_1->min_price !='' && $as_1->min_price != 0 && $as_1->max_price != 0){
						$update_fields['5_max_value'] = $as_1->max_price;
						$update_fields['5_min_value'] = $as_1->min_price;  
					} //loop inner check				
				} // foreach loop end
				foreach($api_response_10 as $as){
					if($as->max_price !='' && $as->min_price !='' && $as->min_price !=0 && $as->max_price !=0){
						$update_fields['10_max_value'] = $as->max_price; 
						$update_fields['10_min_value'] = $as->min_price;
					} // if inner check	
				} //end foreach loop
				echo"<br><pre>";
				print_r($update_fields);
				$collection_name = 'opportunity_logs_kraken';
				$this->mongo_db->where($search_update);
				$this->mongo_db->set($update_fields);
				$this->mongo_db->update($collection_name);
			}
		} //end foreach
		echo "<br>Total Picked Oppertunities Ids= ".count($response);
		//Save last Cron Executioon
		$this->last_cron_execution_time('kraken live opportunity', '1m', 'run kraken live opportunity logs (* * * * *)','reports');
	} //end cron

	// insertion of latest opportunity into kraken log colletion supporting script for old opportunity logs
	public function insert_latest_oppertunity_into_log_collection_kraken_for_old_opportunities(){
		$marketPrices = marketPrices('kraken');					
		$this->load->helper('new_common_helper');    
		foreach($marketPrices as $price){
			if($price['_id'] == 'XRPBTC'){          
				$xrpbtc = (float)$price['price'];
			}elseif($price['_id'] == 'BTCUSDT'){
				$btcusdt = (float)$price['price'];
			}elseif($price['_id'] == 'LINKBTC'){
				$linkbtc = (float)$price['price'];
			}elseif($price['_id'] == 'XLMBTC'){
				$xlmbtc = (float)$price['price'];
			}elseif($price['_id'] == 'ETHBTC'){
				$ethbtc = (float)$price['price'];
			}elseif($price['_id'] == 'XMRBTC'){
				$xmrbtc = (float)$price['price'];
			}elseif($price['_id'] == 'ADABTC'){
				$adabtc = (float)$price['price'];
			}elseif($price['_id'] == 'QTUMBTC'){
				$qtumbtc = (float)$price['price'];
			}elseif($price['_id'] == 'TRXBTC'){
				$trxbtc = (float)$price['price'];
			}elseif($price['_id'] == 'XRPUSDT'){
				$xrpusdt = (float)$price['price'];
			}elseif($price['_id'] == 'LTCUSDT'){
				$ltcusdt = (float)$price['price'];
			}elseif($price['_id'] == 'EOSBTC'){      
				$eosbtc = (float)$price['price'];
			}elseif($price['_id'] == 'EOSUSDT'){      
				$eosusdt = (float)$price['price'];
			}elseif($price['_id'] == 'ETCBTC'){       
				$etcbtc = (float)$price['price'];
			}elseif($price['_id'] == 'DASHBTC'){       
				$dashbtc = (float)$price['price'];
			}elseif($price['_id'] == 'DOTUSDT'){       
				$dotusdt = (float)$price['price'];
			}elseif($price['_id'] == 'ETHUSDT'){       
				$ethusdt = (float)$price['price'];
			}

		}  
		$current_date_time =  date('Y-m-d H:i:s');
		$current_time_date =  $this->mongo_db->converToMongodttime($current_date_time);

		$startTime =  date('Y-01-d H:i:s');
		$startTime =  $this->mongo_db->converToMongodttime($startTime);

		$current_hour =  date('Y-m-d H:i:s', strtotime('-1 month'));
		$orig_date1 = $this->mongo_db->converToMongodttime($current_hour);

		$previous_one_month_date_time = date('Y-m-d H:i:s', strtotime(' -1 month'));
		$endTime =  $this->mongo_db->converToMongodttime($previous_one_month_date_time);

		$connection = $this->mongo_db->customQuery();      
		$condition = array('sort' => array('modified_date' => -1), 'limit' => 15);

		$where['mode'] 			=	'live';
		$where['created_date'] 	= 	array( '$gte' => $startTime ,'$lte'=>$endTime);
		$where['level'] 		= 	array('$ne'=>'level_15');
		$where['is_modified'] 	= 	array('$exists'=>false);
		$where['modified_date'] = 	array('$lte'=>$orig_date1);
		 
		$find_rec = $connection->opportunity_logs_kraken->find($where,  $condition);
		$response = iterator_to_array($find_rec);

		foreach ($response as $value){
			$coin= $value['coin'];
			if(isset($value['sendHitTime']) && !empty($value['sendHitTime'])){
				// $sendHitTime = $value['sendHitTime'];
				$sendHitTime    = strtotime($value['sendHitTime']->toDateTime()->format('Y-m-d H:i:s'));

			}else{
				// $sendHitTime = $value['created_date'];
				$sendHitTime    = strtotime($value['created_date']->toDateTime()->format('Y-m-d H:i:s'));
			}

			if ($value['level'] != 'level_15' ){
				$open_lth_avg_per_trade = 0;
				$open_lth_avg = 0;
				$avg_sold = 0;
				$parents_executed = 0;
				$parents_executed = $value['parents_executed'];
				
				$search_update['opportunity_id'] = $value['opportunity_id'];
				$search_update['mode']= 'live';
				//////////////////////////////////////////////////////////////
				$other['application_mode']= 'live';
				$other['opportunityId'] =  $value['opportunity_id'];
				$other['status'] = array('$nin' => array('LTH', 'FILLED','canceled','new_ERROR'));
				$buyOther = $connection->buy_orders_kraken->count($other);

				$otherSold['application_mode']= 'live';
				$otherSold['opportunityId'] =  $value['opportunity_id'];
				$otherSold['is_sell_order'] = array('$nin' => array('sold'));
				$otherStatusSold = $connection->sold_buy_orders_kraken->count($otherSold);
				$totalOther = $buyOther + $otherStatusSold;
				/////////////////////////////////////////////////////////

				$search_open_lth['application_mode']		= 	'live';
				$search_open_lth['opportunityId'] 			= 	$value['opportunity_id'];
				$search_open_lth['status']					= 	array('$in' => array('LTH', 'FILLED'));
				$search_open_lth['resume_status']['$ne'] 	= 	'resume';
				$search_open_lth['cost_avg']['$ne'] 		= 	'yes';
				$search_open_lth['cavg_parent']['$ne'] 		= 	'yes';
				$search_open_lth['cavg_parent'] 			= 	['$exists' => false];
				$search_open_lth['cost_avg']				=	['$nin' => ['yes', 'completed', '']];

				echo"<br>oppertunity_id=".$value['opportunity_id'];

				/////
				$search_cancel['application_mode']	= 'live';
				$search_cancel['opportunityId'] 	= $value['opportunity_id'];
				$search_cancel['status'] 			= array('$in' => array('canceled'));
				$search_cancel['cavg_parent'] 		= 	['$exists' => false];
				$search_cancel['cost_avg']			=	['$nin' => ['yes', 'completed', '']];
				//////
				$search_new_error['application_mode']		= 	'live';
				$search_new_error['opportunityId'] 			= 	$value['opportunity_id'];
				$search_new_error['status'] 				= 	array('$in' => array('new_ERROR'));
				$search_new_error['cavg_parent'] 			= 	['$exists' => false];
				$search_new_error['cost_avg']				=	['$nin' => ['yes', 'completed', '']];
				////////
				$search_sold['application_mode']		= 	'live';
				$search_sold['opportunityId'] 			= 	$value['opportunity_id'];
				$search_sold['is_sell_order'] 			= 	'sold';
				$search_sold['cavg_parent'] 			= 	['$exists' => false];
				$search_sold['cost_avg']				=	['$nin' => ['yes', 'completed', '']];

				$search_resumed['application_mode']		= 	'live';
				$search_resumed['opportunityId'] 		= 	$value['opportunity_id'];
				$search_resumed['resume_status'] 		= 	array('$in' => array('resume'));
				$search_resumed['cavg_parent'] 			= 	['$exists' => false];
				$search_resumed['cost_avg']				=	['$nin' => ['yes', 'completed', '']];

				$this->mongo_db->where($search_resumed);
				$total_reumed = $this->mongo_db->get('buy_orders_kraken');
				$total_reumed_order   = iterator_to_array($total_reumed);   

				$this->mongo_db->where($search_resumed);
				$total_reumed_sold = $this->mongo_db->get('sold_buy_orders_kraken');
				$total_reumed_sold_orders   = iterator_to_array($total_reumed_sold);

				$minPriceLookUp = [
					[
						'$match' => [
							'application_mode' => 'live',
							'opportunityId'    =>  $value['opportunity_id'],
							'is_sell_order'    =>  'sold'
							]
					],

					[
						'$group' =>[
							'_id' => '$symbol',
							'minPrice' => ['$min' => '$market_sold_price']
						]
					],

				];
	
				$minSoldPrice = $connection->sold_buy_orders_kraken->aggregate($minPriceLookUp);
				$soldMinPrice  = iterator_to_array($minSoldPrice);

				$maxPriceLookUp = [
					[
						'$match' => [
							'application_mode' => 'live',
							'opportunityId'    =>  $value['opportunity_id'],
							'is_sell_order'    =>  'sold'
						]
					],

					[
						'$group' =>[
							'_id' => '$symbol',
							'maxPrice' => ['$max' => '$market_sold_price']
						]
					],

				];

				$maxSoldPrice = $connection->sold_buy_orders_kraken->aggregate($maxPriceLookUp);
				$soldMaxPrice  = iterator_to_array($maxSoldPrice);

				/////////////////////////////////////////////////////////////// 
				$cosAvg['application_mode'] = 'live';
				$cosAvg['opportunityId']    = $value['opportunity_id'];
				$cosAvg['cost_avg']['$ne']  = 'completed';
				$cosAvg['cavg_parent']      = 'yes';

				$cosAvgSold['application_mode']   =  'live';
				$cosAvgSold['opportunityId']      =  $value['opportunity_id'];
				$cosAvgSold['is_sell_order']      =  'sold';
				$cosAvgSold['cost_avg']           =  'completed';
				$cosAvgSold['cavg_parent']        =  'yes';

				$costAvgReturn = $connection->buy_orders_kraken->count($cosAvg);
				$soldCostAvgReturn = $connection->sold_buy_orders_kraken->count($cosAvgSold); 
				///////////////////////////////////////////////////////////////
				$cosAvg_parent['application_mode'] = 'live';
				$cosAvg_parent['symbol']    = $value['coin'];
				$cosAvg_parent['cost_avg']['$ne']  = 'completed';
				$cosAvg_parent['cavg_parent']      = 'yes';
				$costAvgparent= $connection->buy_orders_kraken->count($cosAvg_parent);
				// cost avg child
				/////////////////////////////////////////////////////////////// 
				$cosAvg_child['application_mode']	= 'live';
				$cosAvg_child['opportunityId'] 	    = $value['opportunity_id'];
				$cosAvg_child['cost_avg']     = ['$in' => ['yes','taking_child']];
				$cosAvg_child['is_sell_order']	    = ['$ne'=>'sold'];
				$cosAvg_child['status']	    = ['$ne'=>'canceled'];
				$cosAvg_child['cavg_parent'] 		= ['$exists' => false];
				$cosAvg_child['parent_status'] 		= ['$exists' => false];

				$cosAvg_child_canceled['application_mode']	= 'live';
				$cosAvg_child_canceled['opportunityId'] 	    = $value['opportunity_id'];
				$cosAvg_child_canceled['cost_avg']     = ['$in' => ['yes','taking_child']];
				$cosAvg_child_canceled['is_sell_order']	    = ['$ne'=>'sold'];
				$cosAvg_child_canceled['status']	    = ['$eq'=>'canceled'];
				$cosAvg_child_canceled['cavg_parent'] 		= ['$exists' => false];
				$cosAvg_child_canceled['parent_status'] 		= ['$exists' => false];

				$cosAvgSold_child['application_mode']	= 'live';
				$cosAvgSold_child['opportunityId'] 	= $value['opportunity_id'];
				$cosAvgSold_child['is_sell_order'] 	= 'sold';
				$cosAvgSold_child['cost_avg'] 		= ['$in' => ['yes', 'completed', 'taking_child']];
				$cosAvgSold_child['cavg_parent']    = ['$exists' => false];
				
				$costAvgReturn_child = $connection->buy_orders_kraken->count($cosAvg_child);
				$costAvgReturn_child_canceled = $connection->buy_orders_kraken->count($cosAvg_child_canceled);
				$soldCostAvgReturn_child = $connection->sold_buy_orders_kraken->count($cosAvgSold_child);  
				/////////////////////////////////////////////////////////////// 


				$this->mongo_db->where($search_open_lth);
				$total_open = $this->mongo_db->get('buy_orders_kraken');
				$total_open_lth_rec   = iterator_to_array($total_open);

				$this->mongo_db->where($search_cancel);
				$total_cancel = $this->mongo_db->get('buy_orders_kraken');
				$total_cancel_rec   = iterator_to_array($total_cancel);

				$this->mongo_db->where($search_new_error);
				$total_new_error = $this->mongo_db->get('buy_orders_kraken');
				$total_new_error_rec   = iterator_to_array($total_new_error);

				$this->mongo_db->where($search_sold);
				$total_sold_total = $this->mongo_db->get('sold_buy_orders_kraken');
				$total_sold_rec   = iterator_to_array($total_sold_total);
				
				$open_lth_puchase_price = 0;
				$open_lth_avg = 0;
				$btc = 0;
				$usdt = 0;

				$buySumTimeDelayRange1 = 0;
				$buySumTimeDelayRange2 = 0;
				$buySumTimeDelayRange3 = 0;
				$buySumTimeDelayRange4 = 0;
				$buySumTimeDelayRange5 = 0;
				$buySumTimeDelayRange6 = 0;
				$buySumTimeDelayRange7 = 0;
				$buy_commision_bnb = 0;
				$buy_fee_respected_coin = 0;

				$open_lth_avg_per_trade= 0;
				if (count($total_open_lth_rec) > 0){
					foreach ($total_open_lth_rec as $key => $value2){
						$commission_array = $value2['buy_fraction_filled_order_arr'];
						if($value2['symbol'] == 'ETHBTC'){    
							$open_lth_puchase_price += (float) ($ethbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'BTCUSDT'){
							$open_lth_puchase_price += (float) ($btcusdt - $value2['purchased_price']) / $value2['purchased_price'] ;  
							$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];
							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}
						}elseif($value2['symbol'] == 'XRPBTC'){
							$open_lth_puchase_price += (float) ($xrpbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}
						}elseif($value2['symbol'] == 'XRPUSDT'){
							$open_lth_puchase_price += (float) ($xrpusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'LINKBTC'){
							$open_lth_puchase_price += (float) ($linkbtc - $value2['purchased_price']) / $value2['purchased_price'];;
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];


							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'XLMBTC'){
							$open_lth_puchase_price += (float) ($xlmbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'XMRBTC'){
							$open_lth_puchase_price += (float) ($xmrbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'ADABTC'){
							$open_lth_puchase_price += (float) ($adabtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'QTUMBTC'){
							$open_lth_puchase_price += (float) ($qtumbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'TRXBTC'){
							$open_lth_puchase_price += (float) ($trxbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'LTCUSDT'){
							$open_lth_puchase_price += (float) ($ltcusdt - $value2['purchased_price']) / $value2['purchased_price'];
							$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'ETHUSDT'){
							$open_lth_puchase_price += (float) ($ethusdt - $value2['purchased_price']) / $value2['purchased_price'];
							$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'DOTUSDT'){
							$open_lth_puchase_price += (float) ($dotusdt - $value2['purchased_price']) / $value2['purchased_price'];
							$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'EOSBTC'){
							$open_lth_puchase_price += (float) ($eosbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'ETCBTC'){
							$open_lth_puchase_price += (float) ($etcbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'EOSUSDT'){
							$open_lth_puchase_price += (float) ($eosusdt - $value2['purchased_price']) / $value2['purchased_price'];
							$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'DASHBTC'){  
							$open_lth_puchase_price += (float) ($dashbtc - $value2['purchased_price']) / $value2['purchased_price'];
							$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

							foreach($commission_array as $commBuy){
								if($commBuy['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $commBuy['commission'];
								}else{
									$buy_fee_respected_coin += (float) $commBuy['commission'];
								}  
							}

						}			    

						if( isset($value2['created_date']) && !empty($value2['created_date']) ){

							$orderBUyTime  		= strtotime($value2['created_date']->toDateTime()->format('Y-m-d H:i:s'));

							$differenceBuyInSec = ($orderBUyTime - $sendHitTime);
						}else{
							$differenceBuyInSec = 0;
						}
						if($differenceBuyInSec >= 0 && $differenceBuyInSec < 15 ){
                          	$buySumTimeDelayRange1++; 
						}elseif($differenceBuyInSec >= 15 && $differenceBuyInSec < 30){
							$buySumTimeDelayRange2++;
						}elseif($differenceBuyInSec >= 30 && $differenceBuyInSec < 45){
							$buySumTimeDelayRange3++;
						}elseif($differenceBuyInSec >= 45 && $differenceBuyInSec < 60){
							$buySumTimeDelayRange4++;
						}elseif($differenceBuyInSec >= 60 && $differenceBuyInSec < 75){
							$buySumTimeDelayRange5++;
						}elseif($differenceBuyInSec >= 75 && $differenceBuyInSec < 90 ){
							$buySumTimeDelayRange6++;
						}elseif($differenceBuyInSec >= 90){
							$buySumTimeDelayRange7++;
						}

					}//end loop
					$open_lth_avg_per_trade = (float) $open_lth_puchase_price * 100;
					$open_lth_avg = (float) ($open_lth_avg_per_trade / count($total_open_lth_rec));
				}//end if
		
				$sold_puchase_price = 0;
				$avg_sold_CSL = 0;
				$CSL_per_trade_sold = 0;
				$CSL_sold_purchase_price = 0 ;
				$avg_manul = 0;
				$per_trade_sold_manul = 0;
				$manul_sold_purchase_price = 0;
				$avg_sold = 0;
				$per_trade_sold = 0;

				$sumTimeDelayRange1 = 0;
				$sumTimeDelayRange2 = 0;
				$sumTimeDelayRange3 = 0;
				$sumTimeDelayRange4 = 0;
				$sumTimeDelayRange5 = 0;
				$sumTimeDelayRange6 = 0;
				$sumTimeDelayRange7 = 0;

				$sumPLSllipageRange1 = 0;
				$sumPLSllipageRange2 = 0;
				$sumPLSllipageRange3 = 0;
				$sumPLSllipageRange4 = 0;
				$sumPLSllipageRange5 = 0;
				$sell_fee_respected_coin = 0;
				$sell_comssion_bnb = 0;
				$btc_sell 	= 0 ;
				$usdt_sell 	= 0 ;


				if (count($total_sold_rec) > 0){
					foreach ($total_sold_rec as $key => $value1){
						$commission_sold_array = $value1['buy_fraction_filled_order_arr'];
						$sell_commission_sold_array = $value1['sell_fraction_filled_order_arr'];
						if($value1['symbol'] == 'ETHBTC'){ 
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}
						}elseif($value1['symbol'] == 'XRPBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'NEOBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'QTUMBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'XLMBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'TRXBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'ETCBTC'){
							$btc += $value1['purchased_price'] * $value1['quantity'];
							$btc_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'EOSBTC'){
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'LINKBTC'){  
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'XMRBTC'){  
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'ADABTC'){       
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'LTCUSDT'){        
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'ETHUSDT'){        
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'DOTUSDT'){        
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value1['symbol'] == 'BTCUSDT'){    
							$usdt += $value1['purchased_price']  * $value1['quantity'];
							$usdt_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}elseif($value2['symbol'] == 'DASHBTC'){  
							$btc += $value1['purchased_price']  * $value1['quantity'];
							$btc_sell += $value1['market_sell_price']  * $value1['quantity'];

							foreach($sell_commission_sold_array as $sell_comm){
								if($sell_comm['commissionAsset'] =='BNB'){
									$sell_comssion_bnb += (float)$sell_comm['commission'];
								}else{
									$sell_fee_respected_coin += (float) $sell_comm['commission'];
								}
							}
							foreach($commission_sold_array as $comm_1){
								if($comm_1['commissionAsset'] =='BNB'){
									$buy_commision_bnb +=(float) $comm_1['commission'];
								}else{
									$buy_fee_respected_coin += (float) $comm_1['commission'];
								}  
							}

						}
						if(isset($value1['is_manual_sold'])){
							
							$manul_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
						}elseif(isset($value1['csl_sold'])){
							
							$CSL_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
						}else{
						
							$sold_puchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
						}
						if(isset($value1['order_send_time']) && isset($value1['sell_date']) && !empty($value1['order_send_time']) && !empty($value1['sell_date']) && $value1['is_sell_order'] == "sold"){

							$filledTime     = strtotime($value1['sell_date']->toDateTime()->format('Y-m-d H:i:s'));
							$orderSendTime  = strtotime($value1['order_send_time']->toDateTime()->format('Y-m-d H:i:s'));

							$differenceInSec = ($filledTime - $orderSendTime);
						}else{
							$differenceInSec = 0;
						}
						if($differenceInSec >= 0 && $differenceInSec < 15 ){
                          	$sumTimeDelayRange1++; 
						}elseif($differenceInSec >= 15 && $differenceInSec < 30){
							$sumTimeDelayRange2++;
						}elseif($differenceInSec >= 30 && $differenceInSec < 45){
							$sumTimeDelayRange3++;
						}elseif($differenceInSec >= 45 && $differenceInSec < 60){
							$sumTimeDelayRange4++;
						}elseif($differenceInSec >= 60 && $differenceInSec < 75){
							$sumTimeDelayRange5++;
						}elseif($differenceInSec >= 75 && $differenceInSec < 90 ){
							$sumTimeDelayRange6++;
						}elseif($differenceInSec >= 90){
							$sumTimeDelayRange7++;
						}
						if( isset($value1['created_date']) && !empty($value1['created_date']) ){

							$orderBUyTime  		= strtotime($value1['created_date']->toDateTime()->format('Y-m-d H:i:s'));

							$differenceBuyInSec = ($orderBUyTime - $sendHitTime);
						}else{
							$differenceBuyInSec = 0;
						}
						if($differenceBuyInSec >= 0 && $differenceBuyInSec < 15 ){
                          	$buySumTimeDelayRange1++; 
						}elseif($differenceBuyInSec >= 15 && $differenceBuyInSec < 30){
							$buySumTimeDelayRange2++;
						}elseif($differenceBuyInSec >= 30 && $differenceBuyInSec < 45){
							$buySumTimeDelayRange3++;
						}elseif($differenceBuyInSec >= 45 && $differenceBuyInSec < 60){
							$buySumTimeDelayRange4++;
						}elseif($differenceBuyInSec >= 60 && $differenceBuyInSec < 75){
							$buySumTimeDelayRange5++;
						}elseif($differenceBuyInSec >= 75 && $differenceBuyInSec < 90 ){
							$buySumTimeDelayRange6++;
						}elseif($differenceBuyInSec >= 90){
							$buySumTimeDelayRange7++;
						}

						// sold Pl slippage calculate
						if(isset($value1['sell_market_price']) && $value1['is_sell_order'] == 'sold' && $value1['sell_market_price'] !="" && !is_string($value1['sell_market_price'])){
							$val1 = $value1['market_sold_price'] - $value1['sell_market_price']; 
							$val2 = ($value1['market_sold_price'] + $value1['sell_market_price'])/ 2;
							$slippageOrignalPercentage = ($val1/ $val2) * 100;
							$slippageOrignalPercentage = round($slippageOrignalPercentage, 3) . '%';
						}else{
							$slippageOrignalPercentage = 0;
						}

						if($slippageOrignalPercentage > 0){
							$slippageOrignalPercentage = 0;
						}

						if($slippageOrignalPercentage <= 0 && $slippageOrignalPercentage > -0.2 ){

                          	$sumPLSllipageRange1++; 
						}elseif($slippageOrignalPercentage <= -0.2 && $slippageOrignalPercentage > -0.3){
							
							$sumPLSllipageRange2++;
						}elseif($slippageOrignalPercentage <= -0.3 && $slippageOrignalPercentage > -0.5){
							
							$sumPLSllipageRange3++;
						}elseif($slippageOrignalPercentage <= -0.5 && $slippageOrignalPercentage > -0.75){
							
							$sumPLSllipageRange4++;
						}elseif($slippageOrignalPercentage <= -1 ){
							
							$sumPLSllipageRange5++;
						}
						
					} //end sold foreach
					if($manul_sold_purchase_price > 0){
						$sold_puchase_price += $manul_sold_purchase_price;
						$manul_sold_purchase_price = 0;
					}
					if($CSL_sold_purchase_price > 0)
					{
						$sold_puchase_price += $CSL_sold_purchase_price;
						$CSL_sold_purchase_price = 0;
					}
					if($manul_sold_purchase_price != "0"){
						$per_trade_sold_manul = (float) $manul_sold_purchase_price * 100;
						$avg_manul = (float) ($per_trade_sold_manul / count($total_sold_rec));;
					}
					if($sold_puchase_price !="0"){
						$per_trade_sold = (float) $sold_puchase_price * 100;
						$avg_sold = (float) ($per_trade_sold / count($total_sold_rec)); 
					}
					if($CSL_sold_purchase_price !="0"){
						$CSL_per_trade_sold = (float) $CSL_sold_purchase_price * 100;
						$avg_sold_CSL = (float) ($CSL_per_trade_sold / count($total_sold_rec));
					}
				}// End check >0
					
				$total_orders = count($total_open_lth_rec) + count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $totalOther;
				$disappear = $parents_executed -  $total_orders;
				$total = count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $disappear;
				if ($total == $parents_executed){

					$sell_commision_qty_USDT =   ($sell_fee_respected_coin > 0)  ? convertCoinBalanceIntoUSDT($value['coin'], $sell_fee_respected_coin, 'kraken') : 0;
					$update_fields = array(
						'open_lth'     => count($total_open_lth_rec),
						'new_error'    => count($total_new_error_rec),
						'cancelled'    => count($total_cancel_rec),
						'costAvgCount' => ($costAvgReturn + $soldCostAvgReturn),
						'costAvgCount_child' 	=> 	($costAvgReturn_child + $soldCostAvgReturn_child),
						'costAvgCount_child_buy' 	=> 	$costAvgReturn_child,
						'costAvgCount_child_canceled' 	=> 	$costAvgReturn_child_canceled,
						'costAvgCount_child_sold' 	=> 	$soldCostAvgReturn_child,
						'cost_avg_active_parents' 	=> 	$costAvgparent,
						'sold'         => count($total_sold_rec),
						'reumed_child' => count($total_reumed_order) + count($total_reumed_sold_orders),
						'avg_open_lth' => $open_lth_avg,
						'other_status' => $totalOther,
						'avg_sold'     => $avg_sold,
						'per_trade_sold' => $per_trade_sold,
						'minOrderSoldPrice' => $soldMinPrice[0]['minPrice'],
						'maxOrderSoldPrice' => $soldMaxPrice[0]['maxPrice'],
						'avg_manul'    =>$avg_manul,
						'sellTimeDiffRange1' 	=>	$sumTimeDelayRange1 ,
						'sellTimeDiffRange2' 	=>	$sumTimeDelayRange2 ,
						'sellTimeDiffRange3' 	=> 	$sumTimeDelayRange3 ,
						'sellTimeDiffRange4' 	=>	$sumTimeDelayRange4 ,
						'sellTimeDiffRange5' 	=> 	$sumTimeDelayRange5 ,
						'sellTimeDiffRange6' 	=>	$sumTimeDelayRange6 ,
						'sellTimeDiffRange7' 	=>	$sumTimeDelayRange7 ,
						'buySumTimeDelayRange1' => $buySumTimeDelayRange1 ,
						'buySumTimeDelayRange2' => $buySumTimeDelayRange2 ,
						'buySumTimeDelayRange3' => $buySumTimeDelayRange3 ,
						'buySumTimeDelayRange4' => $buySumTimeDelayRange4 ,
						'buySumTimeDelayRange5' => $buySumTimeDelayRange5 ,
						'buySumTimeDelayRange6' => $buySumTimeDelayRange6 ,
						'buySumTimeDelayRange7' => $buySumTimeDelayRange7 ,
						'sumPLSllipageRange1' 	=> $sumPLSllipageRange1 ,
						'sumPLSllipageRange2' 	=> $sumPLSllipageRange2 ,
						'sumPLSllipageRange3'	=> $sumPLSllipageRange3 ,
						'sumPLSllipageRange4' 	=> $sumPLSllipageRange4 ,
						'sumPLSllipageRange5' 	=> $sumPLSllipageRange5 ,
						'sell_commission' 		=> $sell_comssion_bnb,
						'sell_fee_respected_coin' => $sell_fee_respected_coin,
						'sell_commision_qty_USDT' => $sell_commision_qty_USDT,
						'avg_sold_CSL' 			=> $avg_sold_CSL,
						'modified_date' 		=> $current_time_date  
					);

					if(isset($value['10_max_value']) && isset($value['5_max_value'])){
						$update_fields['is_modified']  = true;
					}
					$cost_avg_child_sum = $costAvgReturn_child+$soldCostAvgReturn_child+$costAvgReturn_child_canceled;
					if(count($total_open_lth_rec)== 0 && count($total_sold_rec) == 0 &&  $totalOther == 0 && $cost_avg_child_sum == 0){
						$update_fields['oppertunity_missed'] = true;
					}
				}else{ 
					$update_fields = array(
						'open_lth'     => count($total_open_lth_rec),
						'new_error'    => count($total_new_error_rec),
						'cancelled'    => count($total_cancel_rec),
						'sold'         => count($total_sold_rec),
						'avg_open_lth' => $open_lth_avg,
						'costAvgCount' => ($costAvgReturn + $soldCostAvgReturn),
						'costAvgCount_child' 	=> 	($costAvgReturn_child + $soldCostAvgReturn_child),
						'costAvgCount_child_buy' 	=> 	$costAvgReturn_child,
						'costAvgCount_child_sold' 	=> 	$soldCostAvgReturn_child,
						'cost_avg_active_parents' 	=> 	$costAvgparent,
						'costAvgCount_child_canceled' 	=> 	$costAvgReturn_child_canceled,
						'avg_sold'     => $avg_sold,
						'per_trade_sold' => $per_trade_sold,
						'minOrderSoldPrice' => $soldMinPrice[0]['minPrice'],
						'maxOrderSoldPrice' => $soldMaxPrice[0]['maxPrice'],
						'reumed_child' => count($total_reumed_order) + count($total_reumed_sold_orders),
						'other_status' => $totalOther,  
						'sellTimeDiffRange1' 	=>	$sumTimeDelayRange1 ,
						'sellTimeDiffRange2' 	=>	$sumTimeDelayRange2 ,
						'sellTimeDiffRange3' 	=> 	$sumTimeDelayRange3 ,
						'sellTimeDiffRange4' 	=>	$sumTimeDelayRange4 ,
						'sellTimeDiffRange5' 	=> 	$sumTimeDelayRange5 ,
						'sellTimeDiffRange6' 	=>	$sumTimeDelayRange6 ,
						'sellTimeDiffRange7' 	=>	$sumTimeDelayRange7 , 

						'buySumTimeDelayRange1' => $buySumTimeDelayRange1 ,
						'buySumTimeDelayRange2' => $buySumTimeDelayRange2 ,
						'buySumTimeDelayRange3' => $buySumTimeDelayRange3 ,
						'buySumTimeDelayRange4' => $buySumTimeDelayRange4 ,
						'buySumTimeDelayRange5' => $buySumTimeDelayRange5 ,
						'buySumTimeDelayRange6' => $buySumTimeDelayRange6 ,
						'buySumTimeDelayRange7' => $buySumTimeDelayRange7 ,

						'sumPLSllipageRange1' 	=> $sumPLSllipageRange1 ,
						'sumPLSllipageRange2' 	=> $sumPLSllipageRange2 ,
						'sumPLSllipageRange3'	=> $sumPLSllipageRange3 ,
						'sumPLSllipageRange4' 	=> $sumPLSllipageRange4 ,
						'sumPLSllipageRange5' 	=> $sumPLSllipageRange5 ,

						'avg_manul'    =>$avg_manul,
						'avg_sold_CSL' => $avg_sold_CSL,
						'modified_date'=>$current_time_date
					);
				}

				$sell_btc_converted = ($btc_sell > 0)  ? convertCoinBalanceIntoUSDT($value['coin'], $btc_sell, 'kraken') : 0;   
				$update_fields['sell_btc_in_$'] =   (float)$sell_btc_converted;
				$update_fields['sell_usdt']   	=   (float)$usdt_sell;
				$update_fields['total_sell_in_usdt'] = (float)($sell_btc_converted + $usdt_sell );

				if($buy_fee_respected_coin > 0 && !isset($value['buy_commision_qty']) && !isset($value['is_modified'])){
					$update_fields['buy_commision_qty'] = $buy_fee_respected_coin;
					$update_fields['buy_commision_qty_USDT'] =   ($buy_fee_respected_coin > 0)  ? convertCoinBalanceIntoUSDT($value['coin'], $buy_fee_respected_coin, 'kraken') : 0;

				}
				if($buy_commision_bnb > 0 && !isset($value['buy_commision']) && !isset($value['is_modified'])){
					$update_fields['buy_commision'] = $buy_commision_bnb;
				}

				$db = $this->mongo_db->customQuery();
				$pipeline = [
					[
						'$match' =>[
						'application_mode' => 'live',
						'parent_status' => ['$exists' => false ],
						'opportunityId' => $value['opportunity_id'],
						'status' => ['$in'=>['LTH','FILLED']],
						],
					],
					[
					'$sort' =>['created_date'=> -1],
					],
					['$limit'=>1]
				];
				$result_buy = $db->buy_orders_kraken->aggregate($pipeline);
				$res = iterator_to_array($result_buy);

				$pipeline1 = [
					[
						'$match' =>[
						'application_mode' => 'live',
						'parent_status' => ['$exists' => false ],
						'opportunityId' => $value['opportunity_id'],
						'status' => ['$in'=>['LTH','FILLED']],
						],
					],
					[
					'$sort' =>['created_date'=> 1],
					],
					['$limit'=>1]
				];
				$result_buy1 = $db->buy_orders_kraken->aggregate($pipeline1);
				$res1 = iterator_to_array($result_buy1);
				if(!isset($value['first_order_buy']) && !isset($value['last_order_buy'])){
					$update_fields['first_order_buy'] =  $res[0]['created_date'];
					$update_fields['last_order_buy'] =  $res1[0]['created_date'];
				}
				if(!isset($value['opp_came_binance']) && !isset($value['opp_came_kraken']) && !isset($value['opp_came_bam'])){	
					$opper_search['application_mode']= 'live';
					$opper_search['opportunityId'] = $value['opportunity_id'];
					
					$connetct= $this->mongo_db->customQuery();

					$pending_curser = $connetct->buy_orders->find($opper_search);
					$buy_order = iterator_to_array($pending_curser);

					$pending_curser_buy = $connetct->sold_buy_order->find($opper_search);
					$sold_bbuy_order = iterator_to_array($pending_curser_buy);

					if(count($buy_order) > 0 || count($sold_bbuy_order) > 0 ){
						$update_fields['opp_came_binance'] = '1';
					}else{
						$update_fields['opp_came_binance'] = '0';
					}
					
					$this->mongo_db->where($opper_search);
					$response_kraken = $this->mongo_db->get('buy_orders_kraken');
					$data_kraken = iterator_to_array($response_kraken);

					$this->mongo_db->where($opper_search);
					$response_kraken_sold = $this->mongo_db->get('sold_buy_orders_kraken');
					$data_kraken_sold = iterator_to_array($response_kraken_sold);
					if(count($data_kraken) > 0 || count($data_kraken_sold) > 0){
						$update_fields['opp_came_kraken'] = '1';
					}else{
						$update_fields['opp_came_kraken'] = '0';
					}
					
					$this->mongo_db->where($opper_search);
					$response_bam = $this->mongo_db->get('buy_orders_bam');
					$data_bam = iterator_to_array($response_bam);

					$this->mongo_db->where($opper_search);
					$response_bam_sold = $this->mongo_db->get('sold_buy_orders_bam');
					$data_bam_sold = iterator_to_array($response_bam_sold);

					if(count($data_bam) > 0 || count($data_bam_sold) > 0){
						$update_fields['opp_came_bam'] = '1';
					}else{
						$update_fields['opp_came_bam'] = '0';
					}
				}

				if($btc > "0" && $usdt == "0" && !isset($value['usdt_invest_amount']) &&  !isset($value['btc_invest_amount'])){
					$update_fields['usdt_invest_amount'] = $btcusdt * $btc;
					$update_fields['btc_invest_amount']  = $btc;  //for chart view 
				}
				elseif($usdt > "0" && $btc == "0" && !isset($value['usdt_invest_amount']) && !isset($value['only_usdt_invest_amount'])) {
					$update_fields['usdt_invest_amount'] = $usdt;
					$update_fields['only_usdt_invest_amount'] = $usdt;  //for chart view
				} 

				echo"<br><pre>";
				print_r($update_fields);
				$collection_name = 'opportunity_logs_kraken';
				$this->mongo_db->where($search_update);
				$this->mongo_db->set($update_fields);
				$this->mongo_db->update($collection_name);
			}
		} //end foreach
		echo "<br>Total Picked Oppertunities Ids= ".count($response);
	} //end cron


	/////////////////////////////////////////////////////////////////////////////
	//////////////////            BAM CRON ASIM          ////////////////////////
	/////////////////////////////////////////////////////////////////////////////
 // insertion of latest opportunity into bam log collection
	// the below method was commented by MUHAMMAD SHERAZ on(31-august-2021) on behalf of Shehzad.
	// public function insert_latest_oppertunity_into_log_collection_bam(){
	// 	// ini_set("error reporting", E_ALL);
	// 	// error_reporting(E_ALL);

	// 	$marketPrices = marketPrices('bam');
	// 	$this->load->helper('new_common_helper');
	// 	foreach($marketPrices as $price){
	// 		if($price['_id'] == 'XRPBTC'){   
	// 			$xrpbtc = (float)$price['price'];
	// 		}elseif($price['_id'] == 'ETHBTC'){
	// 			$ethbtc = (float)$price['price'];
	// 		}elseif($price['_id'] == 'XRPUSDT'){
	// 			$xrpusdt = (float)$price['price'];
	// 		}elseif($price['_id'] == 'BTCUSDT'){       
	// 			$btcusdt = (float)$price['price'];
	// 		}elseif($price['_id'] == 'NEOUSDT'){
	// 			$neousdt = (float)$price['price'];
	// 		}elseif($price['_id'] == 'QTUMUSDT'){
	// 			$qtumusdt = (float)$price['price'];
	// 		}
	// 	}//end loop 
	// 	$current_date_time =  date('Y-m-d H:i:s');
	// 	$current_time_date =  $this->mongo_db->converToMongodttime($current_date_time);
		
	// 	$current_hour =  date('Y-m-d H:i:s', strtotime('-35 minutes'));
	// 	$orig_date1 = $this->mongo_db->converToMongodttime($current_hour);

	// 	$previous_one_month_date_time = date('Y-m-d H:i:s', strtotime(' - 1 month'));
	// 	$pre_date_1 =  $this->mongo_db->converToMongodttime($previous_one_month_date_time);

	// 	$connection = $this->mongo_db->customQuery();      
	// 	$condition = array('sort' => array('created_date' => -1), 'limit'=>3);

	// 	if(!empty($this->input->get())){
	// 		$where['opportunity_id'] = $this->input->get('opportunityId');
	// 	}else{
	// 		$where['mode'] ='live';
	// 		$where['created_date'] = array('$gte'=>$pre_date_1);
	// 		$where['level'] = array('$ne'=>'level_15');
	// 		$where['is_modified'] = array('$exists'=>false);
	// 		$where['modified_date'] = array('$lte'=>$orig_date1);
	// 	} 
	// 	// $where['mode'] ='live'; 
	// 	// $where['created_date'] = array('$gte'=>$pre_date_1);
	// 	// $where['level'] = array('$ne'=>'level_15');
	// 	// $where['is_modified'] = array('$exists'=>false);
	// 	// $where['modified_date'] = array('$lte'=>$orig_date1);
		
	// 	$find_rec = $connection->opportunity_logs_bam->find($where,  $condition);
	// 	$response = iterator_to_array($find_rec);

	// 	foreach ($response as $value){
	// 		$coin= $value['coin'];
	// 		if(isset($value['sendHitTime']) && !empty($value['sendHitTime'])){
	// 			$sendHitTime     	= strtotime($value['sendHitTime']->toDateTime()->format('Y-m-d H:i:s'));				
	// 		}else{
	// 			$sendHitTime     	= strtotime($value['created_date']->toDateTime()->format('Y-m-d H:i:s'));

	// 		}
	// 		$start_date = $value['created_date']->toDateTime()->format("Y-m-d H:i:s");
	// 		$timestamp = strtotime($start_date);
	// 		$time = $timestamp + (5 * 60 * 60);
	// 		$end_date = date("Y-m-d H:i:s", $time);

	// 		$hours_10 = $timestamp + (10 * 60 * 60);
	// 		$time_10_hours = date("Y-m-d H:i:s", $hours_10);
	// 		$cidition_check = $this->mongo_db->converToMongodttime($end_date);
	// 		$cidition_check_10 = $this->mongo_db->converToMongodttime($time_10_hours);
	// 			$params = array(
	// 			'coin'       => $value['coin'],
	// 			'start_date' => (string)$start_date,
	// 			'end_date'   => (string)$end_date,
	// 			);
	// 			echo "<br>current time=".$current_date_time;
	// 			echo"<br>created_date =".$start_date;
	// 			echo"<br>start date +5 =".$end_date;
	// 			echo"<br>start date +10 =".$time_10_hours;

	// 			if($cidition_check <= $current_time_date){
	// 				$jsondata = json_encode($params);
	// 				$curl = curl_init();
	// 				curl_setopt_array($curl, array(	
	// 					CURLOPT_URL => "http://35.171.172.15:3000/api/minMaxMarketPrices",
	// 					CURLOPT_RETURNTRANSFER => true,
	// 					CURLOPT_ENCODING => "",
	// 					CURLOPT_MAXREDIRS => 10,
	// 					CURLOPT_TIMEOUT => 0,
	// 					CURLOPT_FOLLOWLOCATION => true,
	// 					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 					CURLOPT_CUSTOMREQUEST => "POST",
	// 					CURLOPT_POSTFIELDS =>$jsondata,
	// 					CURLOPT_HTTPHEADER => array("Content-Type: application/json"), 
	// 				));
	// 				$response_price = curl_exec($curl);	
	// 				curl_close($curl);                                
	// 				$api_response = json_decode($response_price);
	// 			} // main if check for time comapire

	// 		$params_10_hours = array(
	// 			'coin'       => $value['coin'],
	// 			'start_date' => (string)$start_date,
	// 			'end_date'   => (string)$time_10_hours,
	// 		);
	// 		if($cidition_check_10 <= $current_time_date){
	// 			$jsondata = json_encode($params_10_hours);
	// 				$curl_10 = curl_init();
	// 				curl_setopt_array($curl_10, array(
	// 				CURLOPT_URL => "http://35.171.172.15:3000/api/minMaxMarketPrices",
	// 				CURLOPT_RETURNTRANSFER => true,
	// 				CURLOPT_ENCODING => "",
	// 				CURLOPT_MAXREDIRS => 10,
	// 				CURLOPT_TIMEOUT => 0,
	// 				CURLOPT_FOLLOWLOCATION => true,
	// 				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 				CURLOPT_CUSTOMREQUEST => "POST",
	// 				CURLOPT_POSTFIELDS =>$jsondata,
	// 				CURLOPT_HTTPHEADER => array(
	// 					"Content-Type: application/json"
	// 				), 
	// 				));
	// 				$response_price_10 = curl_exec($curl_10);	
	// 				curl_close($curl_10);
	// 				$api_response_10 = json_decode($response_price_10);
	// 			}
	// 		if ($value['level'] != 'level_15' ){
	// 			$open_lth_avg_per_trade = 0;
	// 			$open_lth_avg = 0;
	// 			$avg_sold = 0;
	// 			$parents_executed = 0;
	// 			$parents_executed = $value['parents_executed'];
				
	// 			$search_update['opportunity_id'] = $value['opportunity_id'];
	// 			$search_update['mode']= 'live';
	// 			////////////////////////////////////////////////////////////////
	// 			$other['application_mode']= 'live';
	// 			$other['opportunityId'] =  $value['opportunity_id'];
	// 			$other['status'] = array('$nin' => array('LTH', 'FILLED','canceled','new_ERROR'));
	// 			$buyOther = $connection->buy_orders_bam->count($other);
	// 			// $total_other_rec   = iterator_to_array($total_other);

	// 			$otherSold['application_mode']= 'live';
	// 			$otherSold['opportunityId'] =  $value['opportunity_id'];
	// 			$otherSold['is_sell_order'] = array('$nin' => array('sold'));
	// 			$otherStatusSold = $connection->sold_buy_orders_bam->count($otherSold);
	// 			$totalOther = $buyOther + $otherStatusSold;
	// 			//////////////////////////////////////////////////////////////

	// 			$search_open_lth['application_mode']		= 	'live';
	// 			$search_open_lth['opportunityId'] 			= 	$value['opportunity_id'];
	// 			$search_open_lth['status'] 					= 	array('$in' => array('LTH', 'FILLED'));
	// 			$search_open_lth['resume_status']['$ne'] 	= 	'resume';
	// 			$search_open_lth['cost_avg']['$ne'] 		= 	'yes';
	// 			$search_open_lth['cavg_parent']['$ne'] 		= 	'yes';
	// 			$search_open_lth['cavg_parent'] 			= 	['$exists' => false];
	// 			$search_open_lth['cost_avg']				=	['$nin' => ['yes', 'completed', '']];

	// 			print_r("<br>oppertunity_id=".$value['opportunity_id']);
	// 			/////
	// 			$search_cancel['application_mode']		= 	'live';
	// 			$search_cancel['opportunityId'] 		= 	$value['opportunity_id'];
	// 			$search_cancel['status'] 				= 	array('$in' => array('canceled'));
	// 			$search_cancel['cavg_parent'] 			= 	['$exists' => false];
	// 			$search_cancel['cost_avg']				=	['$nin' => ['yes', 'completed', '']];
	// 			//////
	// 			$search_new_error['application_mode']	= 	'live';
	// 			$search_new_error['opportunityId'] 		= 	$value['opportunity_id'];
	// 			$search_new_error['status'] 			= 	array('$in' => array('new_ERROR'));
	// 			$search_new_error['cavg_parent'] 		= 	['$exists' => false];
	// 			$search_new_error['cost_avg']			=	['$nin' => ['yes', 'completed', '']];
	// 			////////
	// 			$search_sold['application_mode']	= 	'live';
	// 			$search_sold['opportunityId'] 		= 	$value['opportunity_id'];
	// 			$search_sold['is_sell_order'] 		= 	array('$in' => array('sold'));
	// 			$search_sold['cavg_parent'] 		= 	['$exists' => false];
	// 			$search_sold['cost_avg']			=	['$nin' => ['yes', 'completed', '']];

	// 			$search_reumed['application_mode']	= 'live';
	// 			$search_reumed['opportunityId'] 	= $value['opportunity_id'];
	// 			$search_reumed['resume_status'] 	= array('$in' => array('resume'));  
	// 			$search_resumed['cavg_parent'] 		= 	['$exists' => false];
	// 			$search_resumed['cost_avg']			=	['$nin' => ['yes', 'completed', '']];

	// 			$this->mongo_db->where($search_reumed);
	// 			$total_reumed = $this->mongo_db->get('buy_orders_bam');
	// 			$total_reumed_order   = iterator_to_array($total_reumed);

	// 			$this->mongo_db->where($search_reumed);
	// 			$total_reumed_sold = $this->mongo_db->get('sold_buy_orders_bam');
	// 			$total_reumed_sold_order   = iterator_to_array($total_reumed_sold);   



	// 			$minPriceLookUp = [
	// 				[
	// 					'$match' => [
	// 						'application_mode' => 'live',
	// 						'opportunityId'    =>  $value['opportunity_id'],
	// 						'is_sell_order'    =>  'sold'
	// 					 ]
	// 				],

	// 				[
	// 					'$group' =>[
	// 						'_id' => '$symbol',
	// 						'minPrice' => ['$min' => '$market_sold_price']
	// 					]
	// 				],

	// 			];

	// 			$minSoldPrice = $connection->sold_buy_orders_bam->aggregate($minPriceLookUp);
	// 			$soldMinPrice  = iterator_to_array($minSoldPrice);

	// 			$maxPriceLookUp = [
	// 				[
	// 					'$match' => [
	// 						'application_mode' => 'live',
	// 						'opportunityId'    =>  $value['opportunity_id'],
	// 						'is_sell_order'    =>  'sold'
	// 					 ]
	// 				],

	// 				[
	// 					'$group' =>[
	// 						'_id' => '$symbol',
	// 						'maxPrice' => ['$max' => '$market_sold_price']
	// 					]
	// 				],

	// 			];

	// 			$maxSoldPrice = $connection->sold_buy_orders_bam->aggregate($maxPriceLookUp);
	// 			$soldMaxPrice  = iterator_to_array($maxSoldPrice);

	// 			/////////////////////////////////////////////////////////////// 
	// 			$cosAvg['application_mode'] = 'live';
	// 			$cosAvg['opportunityId']    = $value['opportunity_id'];
	// 			$cosAvg['cost_avg']['$ne']  = 'completed';
 //                $costAvg['cavg_parent']     =  'yes';


	// 			$cosAvgSold['application_mode']  =  'live';
	// 			$cosAvgSold['opportunityId']     =  $value['opportunity_id'];
	// 			$cosAvgSold['is_sell_order']     =  'sold';
	// 			$cosAvgSold['cost_avg']          =  'complete';
	// 			$cosAvgSold['cavg_parent']       =  'yes';

	// 			$costAvgReturn = $connection->buy_orders_bam->count($cosAvg);
	// 			$soldCostAvgReturn = $connection->sold_buy_orders_bam->count($cosAvgSold);  
	// 			///////////////////////////////////////////////////////////////

	// 			$this->mongo_db->where($search_open_lth);
	// 			$total_open = $this->mongo_db->get('buy_orders_bam');
	// 			$total_open_lth_rec   = iterator_to_array($total_open);
				

	// 			$this->mongo_db->where($search_cancel);
	// 			$total_cancel = $this->mongo_db->get('buy_orders_bam');
	// 			$total_cancel_rec   = iterator_to_array($total_cancel);

	// 			$this->mongo_db->where($search_new_error);
	// 			$total_new_error = $this->mongo_db->get('buy_orders_bam');
	// 			$total_new_error_rec   = iterator_to_array($total_new_error);

	// 			$this->mongo_db->where($search_sold);
	// 			$total_sold_total = $this->mongo_db->get('sold_buy_orders_bam');
	// 			$total_sold_rec   = iterator_to_array($total_sold_total);
	// 			echo"<br>total sold count = ".count($total_sold_rec);
				
	// 			$open_lth_puchase_price = 0;
	// 			$open_lth_avg = 0;
	// 			$btc = 0;
	// 			$usdt = 0;

	// 			$buySumTimeDelayRange1 = 0;
	// 			$buySumTimeDelayRange2 = 0;
	// 			$buySumTimeDelayRange3 = 0;
	// 			$buySumTimeDelayRange4 = 0;
	// 			$buySumTimeDelayRange5 = 0;
	// 			$buySumTimeDelayRange6 = 0;
	// 			$buySumTimeDelayRange7 = 0;
	// 			$buy_commision_bnb = 0;
	// 			$buy_fee_respected_coin = 0;

	// 			$open_lth_avg_per_trade= 0;  
	// 			echo"<br>Total open lth = ".count($total_open_lth_rec);
	// 			if (count($total_open_lth_rec) > 0) {
	// 				echo "<br> Open/lth Calculation";
	// 				foreach ($total_open_lth_rec as $key => $value2){
	// 					$commission_array = $value2['buy_fraction_filled_order_arr'];
	// 					if($value2['symbol'] == 'ETHBTC'){
	// 						$open_lth_puchase_price += (float) ($ethbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
	// 						$btc +=(float)$value2['purchased_price'] * $value2['quantity'];

	// 						foreach($commission_array as $commBuy){
	// 							if($commBuy['commissionAsset'] =='BNB'){
	// 								$buy_commision_bnb +=(float) $commBuy['commission'];
	// 								echo "<br>buy commission BTC = ".$buy_commision_bnb;	
	// 							}else{
	// 								$buy_fee_respected_coin += (float) $commBuy['commission'];
	// 							}  
	// 						}

	// 					}elseif($value2['symbol'] == 'BTCUSDT'){
	// 						$open_lth_puchase_price += (float) ($btcusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
	// 						$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];
	// 						foreach($commission_array as $commBuy){
	// 							if($commBuy['commissionAsset'] =='BNB'){
	// 								$buy_commision_bnb +=(float) $commBuy['commission'];
	// 								echo "<br>buy commission BTC = ".$buy_commision_bnb;	
	// 							}else{
	// 								$buy_fee_respected_coin += (float) $commBuy['commission'];
	// 							}  
	// 						}

	// 					}elseif($value2['symbol'] == 'XRPBTC'){
	// 						$open_lth_puchase_price += (float) ($xrpbtc - $value2['purchased_price']) / $value2['purchased_price'];
	// 						$btc +=(float)$value2['purchased_price'] * $value2['quantity'];
	// 						foreach($commission_array as $commBuy){
	// 							if($commBuy['commissionAsset'] =='BNB'){
	// 								$buy_commision_bnb +=(float) $commBuy['commission'];
	// 								echo "<br>buy commission BTC = ".$buy_commision_bnb;	
	// 							}else{
	// 								$buy_fee_respected_coin += (float) $commBuy['commission'];
	// 							}  
	// 						}

	// 					}elseif($value2['symbol'] == 'XRPUSDT'){
	// 						$open_lth_puchase_price += (float) ($xrpusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
	// 						$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];
	// 						foreach($commission_array as $commBuy){
	// 							if($commBuy['commissionAsset'] =='BNB'){
	// 								$buy_commision_bnb +=(float) $commBuy['commission'];
	// 								echo "<br>buy commission BTC = ".$buy_commision_bnb;	
	// 							}else{
	// 								$buy_fee_respected_coin += (float) $commBuy['commission'];
	// 							}  
	// 						}

	// 					}elseif($value2['symbol'] == 'NEOUSDT'){
	// 						$open_lth_puchase_price += (float) ($neousdt - $value2['purchased_price']) / $value2['purchased_price'] ;
	// 						$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];
	// 						foreach($commission_array as $commBuy){
	// 							if($commBuy['commissionAsset'] =='BNB'){
	// 								$buy_commision_bnb +=(float) $commBuy['commission'];
	// 								echo "<br>buy commission BTC = ".$buy_commision_bnb;	
	// 							}else{
	// 								$buy_fee_respected_coin += (float) $commBuy['commission'];
	// 							}  
	// 						}

	// 					}elseif($value2['symbol'] == 'QTUMUSDT'){
	// 						$open_lth_puchase_price += (float) ($qtumusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
	// 						$usdt +=(float)$value2['purchased_price'] * $value2['quantity'];
	// 						foreach($commission_array as $commBuy){
	// 							if($commBuy['commissionAsset'] =='BNB'){
	// 								$buy_commision_bnb +=(float) $commBuy['commission'];
	// 								echo "<br>buy commission BTC = ".$buy_commision_bnb;	
	// 							}else{
	// 								$buy_fee_respected_coin += (float) $commBuy['commission'];
	// 							}  
	// 						}

	// 					}	
	// 					echo "<br>open_lth_puchase_price +=";
	// 					print_r($open_lth_puchase_price);
	// 					echo "<br> order_id = ".$value2['_id'];

	// 					if( isset($value2['created_date']) && !empty($value2['created_date']) ){

	// 						$orderBUyTime  		= strtotime($value2['created_date']->toDateTime()->format('Y-m-d H:i:s'));

	// 						$differenceBuyInSec = ($orderBUyTime - $sendHitTime);
	// 					}else{
	// 						$differenceBuyInSec = 0;
	// 					}
	// 					if($differenceBuyInSec >= 0 && $differenceBuyInSec < 15 ){
 //                          	$buySumTimeDelayRange1++; 
	// 					}elseif($differenceBuyInSec >= 15 && $differenceBuyInSec < 30){
	// 						$buySumTimeDelayRange2++;
	// 					}elseif($differenceBuyInSec >= 30 && $differenceBuyInSec < 45){
	// 						$buySumTimeDelayRange3++;
	// 					}elseif($differenceBuyInSec >= 45 && $differenceBuyInSec < 60){
	// 						$buySumTimeDelayRange4++;
	// 					}elseif($differenceBuyInSec >= 60 && $differenceBuyInSec < 75){
	// 						$buySumTimeDelayRange5++;
	// 					}elseif($differenceBuyInSec >= 75 && $differenceBuyInSec < 90 ){
	// 						$buySumTimeDelayRange6++;
	// 					}elseif($differenceBuyInSec >= 90){
	// 						$buySumTimeDelayRange7++;
	// 					}


	// 				}//end loop
	// 					$open_lth_avg_per_trade = (float) $open_lth_puchase_price * 100;
	// 					$open_lth_avg = (float) ($open_lth_avg_per_trade / count($total_open_lth_rec));
				
	// 					echo "<br>avg_open-lth = ";
	// 					print_r($open_lth_avg);
	// 			}//end if

	// 			$sold_puchase_price = 0;
	// 			$avg_sold_CSL = 0;
	// 			$CSL_per_trade_sold = 0;
	// 			$CSL_sold_purchase_price = 0 ;
	// 			$avg_manul = 0;
	// 			$per_trade_sold_manul = 0;
	// 			$manul_sold_purchase_price = 0;
	// 			$avg_sold = 0;

	// 			$sumTimeDelayRange1 = 0;
	// 			$sumTimeDelayRange2 = 0;
	// 			$sumTimeDelayRange3 = 0;
	// 			$sumTimeDelayRange4 = 0;
	// 			$sumTimeDelayRange5 = 0;
	// 			$sumTimeDelayRange6 = 0;
	// 			$sumTimeDelayRange7 = 0;

	// 			$sumPLSllipageRange1 = 0;
	// 			$sumPLSllipageRange2 = 0;
	// 			$sumPLSllipageRange3 = 0;
	// 			$sumPLSllipageRange4 = 0;
	// 			$sumPLSllipageRange5 = 0;
	// 			$sell_fee_respected_coin = 0;
	// 			$sell_comssion_bnb =0;

	// 			$per_trade_sold = 0;
	// 			if (count($total_sold_rec) > 0){
	// 				echo "<br> sold calculation";
	// 				foreach ($total_sold_rec as $key => $value1){
	// 					$commission_sold_array = $value1['buy_fraction_filled_order_arr'];
	// 					$sell_commission_sold_array = $value1['sell_fraction_filled_order_arr'];
	// 					if($value1['symbol'] == 'XRPBTC'){     
	// 						$btc += $value1['purchased_price']  * $value1['quantity'];
	// 						foreach($sell_commission_sold_array as $sell_comm){
	// 							if($sell_comm['commissionAsset'] =='BNB'){
	// 								$sell_comssion_bnb += (float)$sell_comm['commission'];
	// 								echo "<br>sell commission BTC = ".$sell_comssion_bnb;
	// 							}else{
	// 								$sell_fee_respected_coin += (float) $sell_comm['commission'];
	// 							}
	// 						}
	// 						foreach($commission_sold_array as $comm_1){
	// 							if($comm_1['commissionAsset'] =='BNB'){
	// 								$buy_commision_bnb +=(float) $comm_1['commission'];
	// 								echo "<br>buy commission BTC = ".$buy_commision_bnb;	
	// 							}else{
	// 								$buy_fee_respected_coin += (float) $comm_1['commission'];
	// 							}  
	// 						}

	// 					}elseif($value1['symbol'] == 'ETHBTC'){
	// 						$btc += $value1['purchased_price'] * $value1['quantity'];
	// 						foreach($sell_commission_sold_array as $sell_comm){
	// 							if($sell_comm['commissionAsset'] =='BNB'){
	// 								$sell_comssion_bnb += (float)$sell_comm['commission'];
	// 								echo "<br>sell commission BTC = ".$sell_comssion_bnb;
	// 							}else{
	// 								$sell_fee_respected_coin += (float) $sell_comm['commission'];
	// 							}
	// 						}
	// 						foreach($commission_sold_array as $comm_1){
	// 							if($comm_1['commissionAsset'] =='BNB'){
	// 								$buy_commision_bnb +=(float) $comm_1['commission'];
	// 								echo "<br>buy commission BTC = ".$buy_commision_bnb;	
	// 							}else{
	// 								$buy_fee_respected_coin += (float) $comm_1['commission'];
	// 							}  
	// 						}
	// 					}elseif($value1['symbol'] == 'XRPUSDT'){
	// 						$usdt += $value1['purchased_price'] * $value1['quantity'];
	// 						foreach($sell_commission_sold_array as $sell_comm){
	// 							if($sell_comm['commissionAsset'] =='BNB'){
	// 								$sell_comssion_bnb += (float)$sell_comm['commission'];
	// 								echo "<br>sell commission BTC = ".$sell_comssion_bnb;
	// 							}else{
	// 								$sell_fee_respected_coin += (float) $sell_comm['commission'];
	// 							}
	// 						}
	// 						foreach($commission_sold_array as $comm_1){
	// 							if($comm_1['commissionAsset'] =='BNB'){
	// 								$buy_commision_bnb +=(float) $comm_1['commission'];
	// 								echo "<br>buy commission BTC = ".$buy_commision_bnb;	
	// 							}else{
	// 								$buy_fee_respected_coin += (float) $comm_1['commission'];
	// 							}  
	// 						}
	// 					}elseif($value1['symbol'] == 'BTCUSDT'){
	// 						$usdt += $value1['purchased_price'] * $value1['quantity'];
	// 						foreach($sell_commission_sold_array as $sell_comm){
	// 							if($sell_comm['commissionAsset'] =='BNB'){
	// 								$sell_comssion_bnb += (float)$sell_comm['commission'];
	// 								echo "<br>sell commission BTC = ".$sell_comssion_bnb;
	// 							}else{
	// 								$sell_fee_respected_coin += (float) $sell_comm['commission'];
	// 							}
	// 						}
	// 						foreach($commission_sold_array as $comm_1){
	// 							if($comm_1['commissionAsset'] =='BNB'){
	// 								$buy_commision_bnb +=(float) $comm_1['commission'];
	// 								echo "<br>buy commission BTC = ".$buy_commision_bnb;	
	// 							}else{
	// 								$buy_fee_respected_coin += (float) $comm_1['commission'];
	// 							}  
	// 						}
	// 					}elseif($value1['symbol'] == 'QTUMUSDT'){
	// 						$usdt += $value1['purchased_price']  * $value1['quantity'];
	// 						foreach($sell_commission_sold_array as $sell_comm){
	// 							if($sell_comm['commissionAsset'] =='BNB'){
	// 								$sell_comssion_bnb += (float)$sell_comm['commission'];
	// 								echo "<br>sell commission BTC = ".$sell_comssion_bnb;
	// 							}else{
	// 								$sell_fee_respected_coin += (float) $sell_comm['commission'];
	// 							}
	// 						}
	// 						foreach($commission_sold_array as $comm_1){
	// 							if($comm_1['commissionAsset'] =='BNB'){
	// 								$buy_commision_bnb +=(float) $comm_1['commission'];
	// 								echo "<br>buy commission BTC = ".$buy_commision_bnb;	
	// 							}else{
	// 								$buy_fee_respected_coin += (float) $comm_1['commission'];
	// 							}  
	// 						}
	// 					}elseif($value1['symbol'] == 'NEOUSDT'){
	// 						$usdt += $value1['purchased_price']  * $value1['quantity'];
	// 						foreach($sell_commission_sold_array as $sell_comm){
	// 							if($sell_comm['commissionAsset'] =='BNB'){
	// 								$sell_comssion_bnb += (float)$sell_comm['commission'];
	// 								echo "<br>sell commission BTC = ".$sell_comssion_bnb;
	// 							}else{
	// 								$sell_fee_respected_coin += (float) $sell_comm['commission'];
	// 							}
	// 						}
	// 						foreach($commission_sold_array as $comm_1){
	// 							if($comm_1['commissionAsset'] =='BNB'){
	// 								$buy_commision_bnb +=(float) $comm_1['commission'];
	// 								echo "<br>buy commission BTC = ".$buy_commision_bnb;	
	// 							}else{
	// 								$buy_fee_respected_coin += (float) $comm_1['commission'];
	// 							}  
	// 						}
	// 					}
	// 					if(isset($value1['is_manual_sold'])){
	// 						$manul_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
	// 						print_r("<br> Market sold price manul = ".$value1['market_sold_price']);
	// 						print_r("<br>purchase price manul =".$value1['purchased_price']);
	// 						print_r("<br> sold_puchase_price manul + =".$manul_sold_purchase_price);
	// 						echo '<br>order_id manul ='.$value1['_id'];	
	// 					}elseif(isset($value1['csl_sold'])){
	// 						$CSL_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
	// 						print_r("<br> Market sold price = ".$value1['market_sold_price']);
	// 						print_r("<br>purchase price =".$value1['purchased_price']);
	// 						print_r("<br> CSL sold_puchase_price + =".$CSL_sold_purchase_price);
	// 						echo '<br>order_id ='.$value1['_id'];
	// 					}else{
	// 						$sold_puchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
	// 						print_r("<br> Market sold price = ".$value1['market_sold_price']);
	// 						print_r("<br>purchase price =".$value1['purchased_price']);
	// 						print_r("<br> sold_puchase_price + =".$sold_puchase_price);
	// 						echo '<br>order_id ='.$value1['_id'];
	// 					}


	// 					if(isset($value1['order_send_time']) && isset($value1['sell_date']) && !empty($value1['order_send_time']) && !empty($value1['sell_date']) && $value1['is_sell_order'] == "sold"){

	// 						$filledTime     = strtotime($value1['sell_date']->toDateTime()->format('Y-m-d H:i:s'));
	// 						$orderSendTime  = strtotime($value1['order_send_time']->toDateTime()->format('Y-m-d H:i:s'));

	// 						$differenceInSec = ($filledTime - $orderSendTime);
	// 					}else{
	// 						$differenceInSec = 0;
	// 					}
	// 					if($differenceInSec >= 0 && $differenceInSec < 15 ){
 //                          	$sumTimeDelayRange1++; 
	// 					}elseif($differenceInSec >= 15 && $differenceInSec < 30){
	// 						$sumTimeDelayRange2++;
	// 					}elseif($differenceInSec >= 30 && $differenceInSec < 45){
	// 						$sumTimeDelayRange3++;
	// 					}elseif($differenceInSec >= 45 && $differenceInSec < 60){
	// 						$sumTimeDelayRange4++;
	// 					}elseif($differenceInSec >= 60 && $differenceInSec < 75){
	// 						$sumTimeDelayRange5++;
	// 					}elseif($differenceInSec >= 75 && $differenceInSec < 90 ){
	// 						$sumTimeDelayRange6++;
	// 					}elseif($differenceInSec >= 90){
	// 						$sumTimeDelayRange7++;
	// 					}


	// 					if( isset($value1['created_date']) && !empty($value1['created_date']) ){

	// 						$orderBUyTime  		= strtotime($value1['created_date']->toDateTime()->format('Y-m-d H:i:s'));

	// 						$differenceBuyInSec = ($orderBUyTime - $sendHitTime);
	// 					}else{
	// 						$differenceBuyInSec = 0;
	// 					}
	// 					if($differenceBuyInSec >= 0 && $differenceBuyInSec < 15 ){
 //                          	$buySumTimeDelayRange1++; 
	// 					}elseif($differenceBuyInSec >= 15 && $differenceBuyInSec < 30){
	// 						$buySumTimeDelayRange2++;
	// 					}elseif($differenceBuyInSec >= 30 && $differenceBuyInSec < 45){
	// 						$buySumTimeDelayRange3++;
	// 					}elseif($differenceBuyInSec >= 45 && $differenceBuyInSec < 60){
	// 						$buySumTimeDelayRange4++;
	// 					}elseif($differenceBuyInSec >= 60 && $differenceBuyInSec < 75){
	// 						$buySumTimeDelayRange5++;
	// 					}elseif($differenceBuyInSec >= 75 && $differenceBuyInSec < 90 ){
	// 						$buySumTimeDelayRange6++;
	// 					}elseif($differenceBuyInSec >= 90){
	// 						$buySumTimeDelayRange7++;
	// 					}

	// 					// sold Pl slippage calculate
	// 					if(isset($value1['sell_market_price']) && $value1['is_sell_order'] == 'sold' && $value1['sell_market_price'] !="" && !is_string($value1['sell_market_price'])){
	// 						$val1 = $value1['market_sold_price'] - $value1['sell_market_price']; 
	// 						$val2 = ($value1['market_sold_price'] + $value1['sell_market_price'])/ 2;
	// 						$slippageOrignalPercentage = ($val1/ $val2) * 100;
	// 						$slippageOrignalPercentage = round($slippageOrignalPercentage, 3) . '%';
	// 					}else{
	// 						$slippageOrignalPercentage = 0;
	// 					}

	// 					if($slippageOrignalPercentage > 0){
	// 						$slippageOrignalPercentage = 0;
	// 					}

	// 					if($slippageOrignalPercentage <= 0 && $slippageOrignalPercentage > -0.2 ){

 //                          	$sumPLSllipageRange1++; 
	// 					}elseif($slippageOrignalPercentage <= -0.2 && $slippageOrignalPercentage > -0.3){
							
	// 						$sumPLSllipageRange2++;
	// 					}elseif($slippageOrignalPercentage <= -0.3 && $slippageOrignalPercentage > -0.5){
							
	// 						$sumPLSllipageRange3++;
	// 					}elseif($slippageOrignalPercentage <= -0.5 && $slippageOrignalPercentage > -0.75){
							
	// 						$sumPLSllipageRange4++;
	// 					}elseif($slippageOrignalPercentage <= -1 ){
							
	// 						$sumPLSllipageRange5++;
	// 					}
				

	// 				} //end sold foreach
	// 				if($manul_sold_purchase_price > 0){
	// 					$sold_puchase_price += $manul_sold_purchase_price;
	// 					$manul_sold_purchase_price = 0;
	// 				}
	// 				if($CSL_sold_purchase_price > 0){
	// 					$sold_puchase_price += $CSL_sold_purchase_price;
	// 					$CSL_sold_purchase_price = 0;
	// 				}
	// 				if($manul_sold_purchase_price != "0"){
	// 					$per_trade_sold_manul = (float) $manul_sold_purchase_price * 100;
	// 					echo "<br>per tarde manul = ".$per_trade_sold_manul;
	// 					$avg_manul = (float) ($per_trade_sold_manul / count($total_sold_rec));
	// 					echo "<br>avg_sold manul = ";
	// 					print_r($avg_manul);
	// 					print_r("<br>sold count = ".count($total_sold_rec));
	// 				}
	// 				if($sold_puchase_price !="0"){
	// 					$per_trade_sold = (float) $sold_puchase_price * 100;
	// 					echo "<br>per tarde = ".$per_trade_sold;
	// 					$avg_sold = (float) ($per_trade_sold / count($total_sold_rec));
	// 					echo "<br>avg_sold = ";
	// 					print_r($avg_sold);
	// 					print_r("<br>sold count = ".count($total_sold_rec));
	// 				}
	// 				if($CSL_sold_purchase_price !="0"){
	// 					$CSL_per_trade_sold = (float) $CSL_sold_purchase_price * 100;
	// 					echo "<br>per tarde CSL = ".$CSL_per_trade_sold;
	// 					$avg_sold_CSL = (float) ($CSL_per_trade_sold / count($total_sold_rec));
	// 					echo "<br>avg_sold CSL = ";
	// 					print_r($avg_sold_CSL);
	// 					print_r("<br>sold count = ".count($total_sold_rec));
	// 				}
	// 			}// End check >0
	// 			print_r("<br>oppertunity_id=".$value['opportunity_id']."<br>");
	// 			$total_orders = count($total_open_lth_rec) + count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $totalOther;
	// 			$disappear = $parents_executed -  $total_orders;
	// 			$total = count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $disappear;
	// 			if ($total == $parents_executed ){
	// 				$update_fields = array(
	// 					'open_lth'     => count($total_open_lth_rec),
	// 					'new_error'    => count($total_new_error_rec),
	// 					'cancelled'    => count($total_cancel_rec),
	// 					'costAvgCount' => ($costAvgReturn + $soldCostAvgReturn),
	// 					'sold'         => count($total_sold_rec),
	// 					'avg_open_lth' => $open_lth_avg,
	// 					'other_status' => $totalOther,
	// 					'minOrderSoldPrice' => $soldMinPrice[0]['minPrice'],
	// 					'maxOrderSoldPrice' => $soldMaxPrice[0]['maxPrice'],
	// 					'reumed_child'  => count($total_reumed_sold_order) + count($total_reumed_order),
	// 					'avg_sold'     => $avg_sold,
	// 					'sellTimeDiffRange1' 	=>	$sumTimeDelayRange1 ,
	// 					'sellTimeDiffRange2' 	=>	$sumTimeDelayRange2 ,
	// 					'sellTimeDiffRange3' 	=> 	$sumTimeDelayRange3 ,
	// 					'sellTimeDiffRange4' 	=>	$sumTimeDelayRange4 ,
	// 					'sellTimeDiffRange5' 	=> 	$sumTimeDelayRange5 ,
	// 					'sellTimeDiffRange6' 	=>	$sumTimeDelayRange6 ,
	// 					'sellTimeDiffRange7' 	=>	$sumTimeDelayRange7 ,

	// 					'buySumTimeDelayRange1' => $buySumTimeDelayRange1 ,
	// 					'buySumTimeDelayRange2' => $buySumTimeDelayRange2 ,
	// 					'buySumTimeDelayRange3' => $buySumTimeDelayRange3 ,
	// 					'buySumTimeDelayRange4' => $buySumTimeDelayRange4 ,
	// 					'buySumTimeDelayRange5' => $buySumTimeDelayRange5 ,
	// 					'buySumTimeDelayRange6' => $buySumTimeDelayRange6 ,
	// 					'buySumTimeDelayRange7' => $buySumTimeDelayRange7 ,

	// 					'sumPLSllipageRange1' 	=> $sumPLSllipageRange1 ,
	// 					'sumPLSllipageRange2' 	=> $sumPLSllipageRange2 ,
	// 					'sumPLSllipageRange3'	=> $sumPLSllipageRange3 ,
	// 					'sumPLSllipageRange4' 	=> $sumPLSllipageRange4 ,
	// 					'sumPLSllipageRange5' 	=> $sumPLSllipageRange5 ,

	// 					'per_trade_sold'     	=> $per_trade_sold,
	// 					'avg_manul'    			=> $avg_manul,
	// 					'avg_sold_CSL' 			=> $avg_sold_CSL,
	// 					'modified_date' 		=> $current_time_date,  
	// 					'sell_commission' 		=> $sell_comssion_bnb,
	// 					'sell_fee_respected_coin' => $sell_fee_respected_coin,
	// 				);
	// 				if(isset($value['10_max_value']) && isset($value['5_max_value'])){
	// 					$update_fields['is_modified']  = true;
	// 				}
	// 				if(count($total_open_lth_rec)== 0 && count($total_sold_rec) == 0 &&  $totalOther == 0){
	// 					$update_fields['oppertunity_missed'] = true;
	// 				}
	// 				}else{ 
	// 					$update_fields = array(
	// 						'open_lth'     => count($total_open_lth_rec),
	// 						'new_error'    => count($total_new_error_rec),
	// 						'cancelled'    => count($total_cancel_rec),
	// 						'costAvgCount' => ($costAvgReturn + $soldCostAvgReturn),
	// 						'sold'         => count($total_sold_rec),
	// 						'reumed_child' => count($total_reumed_sold_order) + count($total_reumed_order),
	// 						'avg_open_lth' => $open_lth_avg,
	// 						'avg_sold'     => $avg_sold,
	// 						'per_trade_sold' => $per_trade_sold,
	// 						'minOrderSoldPrice' => $soldMinPrice[0]['minPrice'],
	// 						'maxOrderSoldPrice' => $soldMaxPrice[0]['maxPrice'],
	// 						'other_status' => $totalOther,   
	// 						'sellTimeDiffRange1' 	=>	$sumTimeDelayRange1 ,
	// 						'sellTimeDiffRange2' 	=>	$sumTimeDelayRange2 ,
	// 						'sellTimeDiffRange3' 	=> 	$sumTimeDelayRange3 ,
	// 						'sellTimeDiffRange4' 	=>	$sumTimeDelayRange4 ,
	// 						'sellTimeDiffRange5' 	=> 	$sumTimeDelayRange5 ,
	// 						'sellTimeDiffRange6' 	=>	$sumTimeDelayRange6 ,
	// 						'sellTimeDiffRange7' 	=>	$sumTimeDelayRange7 ,

	// 						'buySumTimeDelayRange1' => $buySumTimeDelayRange1 ,
	// 						'buySumTimeDelayRange2' => $buySumTimeDelayRange2 ,
	// 						'buySumTimeDelayRange3' => $buySumTimeDelayRange3 ,
	// 						'buySumTimeDelayRange4' => $buySumTimeDelayRange4 ,
	// 						'buySumTimeDelayRange5' => $buySumTimeDelayRange5 ,
	// 						'buySumTimeDelayRange6' => $buySumTimeDelayRange6 ,
	// 						'buySumTimeDelayRange7' => $buySumTimeDelayRange7 ,

	// 						'sumPLSllipageRange1' 	=> $sumPLSllipageRange1 ,
	// 						'sumPLSllipageRange2' 	=> $sumPLSllipageRange2 ,
	// 						'sumPLSllipageRange3'	=> $sumPLSllipageRange3 ,
	// 						'sumPLSllipageRange4' 	=> $sumPLSllipageRange4 ,
	// 						'sumPLSllipageRange5' 	=> $sumPLSllipageRange5 ,

	// 						'avg_manul'    =>$avg_manul,
	// 						'avg_sold_CSL' => $avg_sold_CSL,
	// 						'modified_date'=>$current_time_date
	// 					);
	// 				}


	// 				if($buy_fee_respected_coin > 0 && !isset($value['buy_commision_qty']) && !isset($value['is_modified'])){
	// 					$update_fields['buy_commision_qty'] = $buy_fee_respected_coin;
	// 					echo"<br> qty buy feee = ".$buy_fee_respected_coin;
	// 				}
	// 				if($buy_commision_bnb > 0 && !isset($value['buy_commision']) && !isset($value['is_modified'])){
	// 					$update_fields['buy_commision'] = $buy_commision_bnb;
	// 					echo"total commision = BNB ".$buy_commision_bnb;
	// 				}
	// 				$db = $this->mongo_db->customQuery();
	// 				$pipeline = [
	// 				[
	// 					'$match' =>[
	// 					'application_mode' => 'live',
	// 					'parent_status' => ['$exists' => false ],
	// 					'opportunityId' => $value['opportunity_id'],
	// 					'status' => ['$in'=>['LTH','FILLED']],
	// 					],
	// 				],
	// 					[
	// 					'$sort' =>['created_date'=> -1],
	// 					],
	// 					['$limit'=>1]
	// 				];
	// 				$result_buy = $db->buy_orders_bam->aggregate($pipeline);
	// 				$res = iterator_to_array($result_buy);

	// 				$pipeline1 = [
	// 				[
	// 					'$match' =>[
	// 					'application_mode' => 'live',
	// 					'parent_status' => ['$exists' => false ],
	// 					'opportunityId' => $value['opportunity_id'],
	// 					'status' => ['$in'=>['LTH','FILLED']],
	// 					],
	// 				],
	// 					[
	// 					'$sort' =>['created_date'=> 1],
	// 					],
	// 					['$limit'=>1]
	// 				];
	// 				$result_buy1 = $db->buy_orders_bam->aggregate($pipeline1);
	// 				$res1 = iterator_to_array($result_buy1);
	// 				if(!isset($value['first_order_buy']) && !isset($value['last_order_buy'])){
	// 					echo "<br> created_date first =".$res[0]['created_date'];
	// 					echo "<br>created_date last = ".$res1[0]['created_date'];
	// 					$update_fields['first_order_buy'] =  $res[0]['created_date'];
	// 					$update_fields['last_order_buy'] =  $res1[0]['created_date'];
	// 				}
	// 				if(!isset($value['opp_came_binance']) && !isset($value['opp_came_kraken']) && !isset($value['opp_came_bam'])){	
	// 					$opper_search['application_mode']= 'live';
	// 					$opper_search['opportunityId'] = $value['opportunity_id'];
						
	// 					$connetct= $this->mongo_db->customQuery();

	// 					$pending_curser = $connetct->buy_orders->find($opper_search);
	// 					$buy_order = iterator_to_array($pending_curser);
	// 					echo "<br>result binance=".count($buy_order);

	// 					$pending_curser_buy = $connetct->sold_buy_order->find($opper_search);
	// 					$sold_bbuy_order = iterator_to_array($pending_curser_buy);
	// 					echo "<br>result binance sold=".count($sold_bbuy_order);

	// 					if(count($buy_order) > 0 || count($sold_bbuy_order) > 0 ){
	// 						$update_fields['opp_came_binance'] = '1';
	// 					}else{
	// 						$update_fields['opp_came_binance'] = '0';
	// 					}
						
	// 					$this->mongo_db->where($opper_search);
	// 					$response_kraken = $this->mongo_db->get('buy_orders_kraken');
	// 					$data_kraken = iterator_to_array($response_kraken);
	// 					echo "<br>result kraken=". count($data_kraken);

	// 					$this->mongo_db->where($opper_search);
	// 					$response_kraken_sold = $this->mongo_db->get('sold_buy_orders_kraken');
	// 					$data_kraken_sold = iterator_to_array($response_kraken_sold);
	// 					echo "<br>result kraken sold=". count($data_kraken_sold);
	// 					if(count($data_kraken) > 0 || count($data_kraken_sold) > 0){
	// 						$update_fields['opp_came_kraken'] = '1';
	// 					}else{
	// 						$update_fields['opp_came_kraken'] = '0';
	// 					}
						
	// 					$this->mongo_db->where($opper_search);
	// 					$response_bam = $this->mongo_db->get('buy_orders_bam');
	// 					$data_bam = iterator_to_array($response_bam);
	// 					echo "<br>result bam=". count($data_bam );

	// 					$this->mongo_db->where($opper_search);
	// 					$response_bam_sold = $this->mongo_db->get('sold_buy_orders_bam');
	// 					$data_bam_sold = iterator_to_array($response_bam_sold);
	// 					echo "<br>result bam sold =". count($data_bam_sold);

	// 					if(count($data_bam) > 0 || count($data_bam_sold) > 0){
	// 						$update_fields['opp_came_bam'] = '1';
	// 					}else{
	// 						$update_fields['opp_came_bam'] = '0';
	// 					}
	// 				}


	// 				if($btc > "0" && $usdt == "0" && !isset($value['usdt_invest_amount']) &&  !isset($value['btc_invest_amount'])){
	// 					$update_fields['usdt_invest_amount'] = $btcusdt * $btc;//(float)$btc;
	// 					$update_fields['btc_invest_amount']  = $btc;  //for chart view 

	// 				}
	// 				elseif($usdt > "0" && $btc == "0" && !isset($value['usdt_invest_amount']) && !isset($value['only_usdt_invest_amount'])) {
	// 					$update_fields['usdt_invest_amount'] = $usdt;
	// 					$update_fields['only_usdt_invest_amount'] = $usdt;  //for chart view
	// 				}

	// 				foreach($api_response as $as_1){
	// 					if($as_1->max_price !='' && $as_1->min_price !='' && $as_1->min_price != 0 && $as_1->max_price != 0){
	// 						$update_fields['5_max_value'] = $as_1->max_price;
	// 						echo "<br>max =". $update_fields['5_max_value'];
	// 						$update_fields['5_min_value'] = $as_1->min_price;  
	// 						echo "<br> min =". $update_fields['5_min_value'];
	// 					} //loop inner check				
	// 				} // foreach loop end


	// 				foreach($api_response_10 as $as){
	// 					if($as->max_price !='' && $as->min_price !='' && $as->min_price !=0 && $as->max_price !=0){
	// 						echo "<br>max 10 = ".$as->max_price;
	// 						$update_fields['10_max_value'] = $as->max_price; 
	// 						echo "<br>min 10=".$as->min_price;
	// 						$update_fields['10_min_value'] = $as->min_price;
	// 					} // if inner check	
	// 				} //end foreach loop
			
	// 				echo"<br><pre>";
	// 				print_r($update_fields);
	// 					$collection_name = 'opportunity_logs_bam';
	// 					$this->mongo_db->where($search_update);
	// 					$this->mongo_db->set($update_fields);
	// 					$query = $this->mongo_db->update($collection_name);
	// 		}
	// 	} //end foreach
	// 	echo "<br>current time".$current_date_time;
	// 	echo "<br>Total Picked Oppertunities Ids= " . count($response);
	// 	//Save last Cron Executioon
	// 	$this->last_cron_execution_time('Bam live opportunity', '1m', 'run bam live opportunity logs (* * * * *)', 'reports');
	// } //end cron


	/////////////////////////////////////////////////////////////////////////////
	///////////////          ASIM CRONE TEST BINANCE           /////////////////
	/////////////////////////////////////////////////////////////////////////////
	public function insert_latest_oppertunity_into_log_collection_test_binance(){
		$this->load->helper('new_common_helper');
		$marketPrices = marketPrices('binance');
		foreach($marketPrices as $price){
			if($price['_id'] == 'ETHBTC'){
				$ethbtc = (float)$price['price'];
			}elseif($price['_id'] == 'BTCUSDT'){
				$btcusdt = (float)$price['price'];
			}elseif($price['_id'] == 'XRPBTC'){
				$xrpbtc = (float)$price['price'];
			}elseif($price['_id'] == 'XRPUSDT'){
				$xrpusdt = (float)$price['price'];
			}elseif($price['_id'] == 'NEOBTC'){
				$neobtc = (float)$price['price'];
			}elseif($price['_id'] == 'NEOUSDT'){
				$neousdt = (float)$price['price'];
			}elseif($price['_id'] == 'QTUMBTC'){
				$qtumbtc = (float)$price['price'];
			}elseif($price['_id'] == 'QTUMUSDT'){
				$qtumusdt = (float)$price['price'];
			}elseif($price['_id'] == 'XLMBTC'){
				$xml = (float)$price['price'];
			}elseif($price['_id'] == 'XEMBTC'){
				$xem = (float)$price['price'];
			}elseif($price['_id'] == 'POEBTC'){
				$poe = (float)$price['price'];
			}elseif($price['_id'] == 'TRXBTC'){
				$trx = (float)$price['price'];
			}elseif($price['_id'] == 'ZENBTC'){
				$zen = (float)$price['price'];
			}elseif($price['_id'] == 'ETCBTC'){
				$etcbtc = (float)$price['price'];
			}elseif($price['_id'] =='EOSBTC'){
				$eosbtc = (float)$price['price'];
			}elseif($price['_id'] =='LINKBTC'){
				$linkbtc = (float)$price['price'];
			}elseif($price['_id'] =='DASHBTC'){
				$dashbtc = (float)$price['price'];
			}elseif($price['_id'] =='XMRBTC'){
				$xmrbtc = (float)$price['price'];
			}elseif($price['_id'] =='ADABTC'){
				$adabtc = (float)$price['price'];
			}elseif($price['_id'] =='LTCUSDT'){
				$ltcusdt = (float)$price['price'];
			}elseif($price['_id'] =='EOSUSDT'){
				$eosusdt = (float)$price['price'];
			}				
		} //end loop 
		$current_date_time =  date('Y-m-d H:i:s');
		$current_time_date =  $this->mongo_db->converToMongodttime($current_date_time);

		$current_hour =  date('Y-m-d H:i:s', strtotime('-40 minutes'));
		$orig_date1 = $this->mongo_db->converToMongodttime($current_hour);

		$previous_one_month_date_time = date('Y-m-d H:i:s', strtotime('- 1 month'));
		$pre_date_1 =  $this->mongo_db->converToMongodttime($previous_one_month_date_time);

		// $startDate = $this->mongo_db->converToMongodttime(date('Y-10-1 00:00:00'));
		// $endDate = $this->mongo_db->converToMongodttime(date('Y-10-31 23:59:59'));

		$connection = $this->mongo_db->customQuery();      
		$condition = array('sort' => array('created_date' => -1), 'limit'=> 3);
		if(!empty($this->input->get())){
			$where['opportunity_id'] = $this->input->get('opportunityId');
		}else{
			$where['mode'] ='live';
			$where['created_date'] = array('$gte'=>$pre_date_1);
			$where['level'] = array('$ne'=>'level_15');
			$where['is_modified'] = array('$exists'=>false);
			$where['modified_date'] = array('$lte'=>$orig_date1);
		}

		// $where['mode'] ='test';
		// $where['created_date'] = array('$gte'=>$pre_date_1);
		// $where['level'] = array('$ne'=>'level_15');
		// $where['is_modified'] = array('$exists'=>false);
		// $where['modified_date'] = array('$lte'=>$orig_date1);
		// $where['modified_date'] = array('$gte'=>$startDate, '$lte' => $endDate);
		//$where['opportunity_id']['$in'] = array('5f8d91cc410eaf18a7496996');
		$find_rec = $connection->opportunity_logs_test_binance->find($where,  $condition);
		$response = iterator_to_array($find_rec);
		foreach ($response as $value){
			$coin= $value['coin'];
			$start_date = $value['created_date']->toDateTime()->format("Y-m-d H:i:s");
			$timestamp = strtotime($start_date);
			$time = $timestamp + (5 * 60 * 60);
			$end_date = date("Y-m-d H:i:s", $time);

			$hours_10 = $timestamp + (10 * 60 * 60);
			$time_10_hours = date("Y-m-d H:i:s", $hours_10);

			$cidition_check = $this->mongo_db->converToMongodttime($end_date);
			$cidition_check_10 = $this->mongo_db->converToMongodttime($time_10_hours);
				$params = array(   
					'coin'       => $value['coin'],
					'start_date' => (string)$start_date,
					'end_date'   => (string)$end_date,
				);	
				echo "<br>current time=".$current_date_time;
				echo"<br>created_date =".$start_date;
				echo"<br>start date +5 =".$end_date;
				echo"<br>start date +10 =".$time_10_hours;

				if($cidition_check <= $current_time_date){
					$jsondata = json_encode($params);
					$curl = curl_init();
					curl_setopt_array($curl, array(
					CURLOPT_URL => "http://35.171.172.15:3000/api/minMaxMarketPrices",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS =>$jsondata,
					CURLOPT_HTTPHEADER => array("Content-Type: application/json"), 
					));
					$response_price = curl_exec($curl);	
					curl_close($curl);                                
					$api_response = json_decode($response_price);
					echo "<pre>";print_r($api_response);
				}// main if check for time comapire

				$params_10_hours = array(
					'coin'       => $value['coin'],
					'start_date' => (string)$start_date,
					'end_date'   => (string)$time_10_hours,
				);
				if($cidition_check_10 <= $current_time_date){
					$jsondata = json_encode($params_10_hours);
					$curl_10 = curl_init();
					curl_setopt_array($curl_10, array(
						CURLOPT_URL => "http://35.171.172.15:3000/api/minMaxMarketPrices",
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "POST",
						CURLOPT_POSTFIELDS =>$jsondata,
						CURLOPT_HTTPHEADER => array("Content-Type: application/json"), 
					));
					$response_price_10 = curl_exec($curl_10);	
					curl_close($curl_10);
					$api_response_10 = json_decode($response_price_10);
					echo "<pre>";print_r($api_response_10);
				}
			if ($value['level'] != 'level_15' ) {
				$open_lth_avg_per_trade = 0;
				$open_lth_avg = 0;
				$avg_sold = 0;
				$parents_executed = 0;
				$parents_executed = $value['parents_executed'];
				
				$search_update['opportunity_id'] = $value['opportunity_id'];
				$search_update['mode']= 'test';
				
				$other['application_mode']= 'test';
				$other['opportunityId'] =  $value['opportunity_id'];
				$other['status'] = array('$nin' => array('LTH', 'FILLED','canceled','new_ERROR'));
				$buyOther = $connection->buy_orders->count($other);
				/////////////////////////////////////////////////////////

				$otherSold['application_mode']= 'test';
				$otherSold['opportunityId'] =  $value['opportunity_id'];
				$otherSold['is_sell_order'] = array('$nin' => array('sold'));
				$otherSold = $connection->sold_buy_orders->count($otherSold);
				$totalOther = $buyOther+ $otherSold;
				///////////////////////////////////////////////////////////
				$search_open_lth['application_mode']= 'test';
				$search_open_lth['opportunityId'] = $value['opportunity_id'];
				$search_open_lth['status'] = array('$in' => array('LTH', 'FILLED'));

				print_r("<br>oppertunity_id=".$value['opportunity_id']);
				/////
				$search_cancel['application_mode']= 'test';
				$search_cancel['opportunityId'] = $value['opportunity_id'];
				$search_cancel['status'] = array('$in' => array('canceled'));
				//////
				$search_new_error['application_mode']= 'test';
				$search_new_error['opportunityId'] = $value['opportunity_id'];
				$search_new_error['status'] = array('$in' => array('new_ERROR'));
				////////
				$search_sold['application_mode']= 'test';
				$search_sold['opportunityId'] = $value['opportunity_id'];
				$search_sold['is_sell_order'] = array('$in' => array('sold'));

				$search_resumed['application_mode']= 'test';
				$search_resumed['opportunityId'] = $value['opportunity_id'];
				$search_resumed['resume_status'] = array('$in' => array('resume'));
				
				///////////////////////////////////////////////////////////////
				$this->mongo_db->where($search_resumed);
				$total_resumed_sold = $this->mongo_db->get('sold_buy_orders');
				$total_reumed_sold   = iterator_to_array($total_resumed_sold);
				
				foreach($total_reumed_sold as $value){
					echo "<br>Resume sold order_id = ".$value['_id'];
				}
				$this->mongo_db->where($search_resumed);
				$total_resumed = $this->mongo_db->get('buy_orders');
				$total_reumed   = iterator_to_array($total_resumed);
				foreach($total_reumed as $value){
					echo "<br>Resume open order_id = ".$value['_id'];
				}
				$this->mongo_db->where($search_open_lth);
				$total_open = $this->mongo_db->get('buy_orders');
				$total_open_lth_rec   = iterator_to_array($total_open);

				$this->mongo_db->where($search_cancel);
				$total_cancel = $this->mongo_db->get('buy_orders');
				$total_cancel_rec   = iterator_to_array($total_cancel);

				///////////////////////////////////////////////////////////////////////////////

				$this->mongo_db->where($search_new_error);
				$total_new_error = $this->mongo_db->get('buy_orders');
				$total_new_error_rec   = iterator_to_array($total_new_error);

				// 	/////////////////////////////////////////////////////////////////////////////

				$this->mongo_db->where($search_sold);
				$total_sold_total = $this->mongo_db->get('sold_buy_orders');
				$total_sold_rec   = iterator_to_array($total_sold_total);
				
				// ////////////////////////////////////////////////CALCULATE LTH AND OPEN ORDERS AVG
				$open_lth_puchase_price = 0;
				$open_lth_avg = 0;
				$open_lth_avg_per_trade= 0;
				
				echo"<br>Total open lth = ".count($total_open_lth_rec);
				if (count($total_open_lth_rec) > 0){
					echo "<br> Open/lth Calculation";
					foreach ($total_open_lth_rec as $key => $value2) {
						// $commission_array = $value2['buy_fraction_filled_order_arr'];
						if($value2['symbol'] == 'ETHBTC'){
							$open_lth_puchase_price += (float) ($ethbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$ethbtc;
						}elseif($value2['symbol'] == 'LINKBTC'){
							$open_lth_puchase_price += (float) ($linkbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$linkbtc;
						}elseif($value2['symbol'] == 'DASHBTC'){
							$open_lth_puchase_price += (float) ($dashbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$dashbtc;
						}elseif($value2['symbol'] == 'XMRBTC'){
							$open_lth_puchase_price += (float) ($xmrbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$xmrbtc;
						}elseif($value2['symbol'] == 'ADABTC'){
							$open_lth_puchase_price += (float) ($adabtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$adabtc;
						}elseif($value2['symbol'] == 'LTCUSDT'){
							$open_lth_puchase_price += (float) ($ltcusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "btc current price =".$ltcusdt."<br>";
							echo "purchase price =".$value2['purchased_price']."<br>";
						}elseif($value2['symbol'] == 'BTCUSDT'){
							$open_lth_puchase_price += (float) ($btcusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "btc current price =".$btcusdt."<br>";
							echo "purchase price =".$value2['purchased_price']."<br>";
						}elseif($value2['symbol'] == 'XRPBTC'){
							$open_lth_puchase_price += (float) ($xrpbtc - $value2['purchased_price']) / $value2['purchased_price'];
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$xrpbtc;
						}elseif($value2['symbol'] == 'XRPUSDT'){
							$open_lth_puchase_price += (float) ($xrpusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$xrpusdt;
						}elseif($value2['symbol'] == 'NEOBTC'){
							$open_lth_puchase_price += (float) ($neobtc - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$neobtc;
						}elseif($value2['symbol'] == 'NEOUSDT'){
							$open_lth_puchase_price += (float) ($neousdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$neousdt;
						}elseif($value2['symbol'] == 'QTUMBTC'){
							$open_lth_puchase_price += (float) ($qtumbtc - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$qtumbtc;
						}elseif($value2['symbol'] == 'QTUMUSDT'){
							$open_lth_puchase_price += (float) ($qtumusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$qtumusdt;
						}elseif($value2['symbol'] == 'XLMBTC'){
							$open_lth_puchase_price += (float) ($xml - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$xml;		
						}elseif($value2['symbol'] == 'XEMBTC'){
							$open_lth_puchase_price += (float) ($xem - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$xem;								
						}elseif($value2['symbol'] == 'POEBTC'){
							$open_lth_puchase_price += (float) ($poe - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$poe;										
						}elseif($value2['symbol'] == 'TRXBTC'){
							$open_lth_puchase_price += (float) ($trx - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$trx;				
						}elseif($value2['symbol'] == 'ZENBTC'){
							$open_lth_puchase_price += (float) ($zen - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$zen;										
						}elseif($value2['symbol'] == 'ETCBTC'){
							$open_lth_puchase_price += (float) ($etcbtc - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$etcbtc;				
						}elseif($value2['symbol'] == 'EOSBTC'){
							$open_lth_puchase_price += (float) ($eosbtc - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$eosbtc;										
						}elseif($value2['symbol'] == 'EOSUSDT'){
							$open_lth_puchase_price += (float) ($eosusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$eosusdt;
						}		
						echo "<br>open_lth_puchase_price +=";
						print_r($open_lth_puchase_price);
						//	array_sum($value2['sell_price']);
						echo "<br> order_id = ".$value2['_id'];
					}//end loop
					$open_lth_avg_per_trade = (float) $open_lth_puchase_price * 100;
					$open_lth_avg = (float) ($open_lth_avg_per_trade / count($total_open_lth_rec));
				
					echo "<br>avg_sold = ";
					print_r($open_lth_avg);
				}//end if
				// /////////////////////////////////////////////////////////////////END OPEN LTH AVG
			
				// ////////////////////////////////////////////////////////////////CALCULATE SOLD AVG
				$sold_puchase_price = 0;
				$avg_sold_CSL = 0;
				$CSL_per_trade_sold = 0;
				$CSL_sold_purchase_price = 0 ;
				$avg_manul = 0;
				$per_trade_sold_manul = 0;
				$manul_sold_purchase_price = 0;
				$avg_sold = 0;
				$per_trade_sold = 0;
			
				if(count($total_sold_rec) > 0){
					echo "<br> sold calculation";
					foreach ($total_sold_rec as $key => $value1) {
						if(isset($value1['is_manual_sold'])){
							$manul_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];											
							print_r("<br> Market sold price manul = ".$value1['market_sold_price']);
							print_r("<br>purchase price manul =".$value1['purchased_price']);
							print_r("<br> sold_puchase_price manul + =".$manul_sold_purchase_price);
							echo '<br>order_id manul ='.$value1['_id'];	
						}elseif(isset($value1['csl_sold'])){
							$CSL_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];											
							print_r("<br> Market sold price = ".$value1['market_sold_price']);
							print_r("<br>purchase price =".$value1['purchased_price']);
							print_r("<br> CSL sold_puchase_price + =".$CSL_sold_purchase_price);
							echo '<br>order_id ='.$value1['_id'];
						}else{
							$sold_puchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
							print_r("<br> Market sold price = ".$value1['market_sold_price']);
							print_r("<br>purchase price =".$value1['purchased_price']);
							print_r("<br> sold_puchase_price + =".$sold_puchase_price);
							echo '<br>order_id ='.$value1['_id'];
						}
					} //end sold foreach

					// if manul sold greater than 0 add in avg parofit 
					if($manul_sold_purchase_price > 0)
					{
						$sold_puchase_price += $manul_sold_purchase_price;
						$manul_sold_purchase_price = 0;
					}

					// if CSL sold greater than 0 add in avg parofit 
					if($CSL_sold_purchase_price > 0)
					{
						$sold_puchase_price += $CSL_sold_purchase_price;
						$CSL_sold_purchase_price = 0;
					}
					if($manul_sold_purchase_price != "0"){
						$per_trade_sold_manul = (float) $manul_sold_purchase_price * 100;
						echo "<br>per tarde manul = ".$per_trade_sold_manul;
						$avg_manul = (float) ($per_trade_sold_manul / (count($total_sold_rec)));
						echo "<br>avg_sold manul = ";
						print_r($avg_manul);
						print_r("<br>sold count = ".count($total_sold_rec));
					}
					if($sold_puchase_price !="0"){
						$per_trade_sold = (float) $sold_puchase_price * 100;
						echo "<br>per tarde = ".$per_trade_sold;
						$avg_sold = (float) ($per_trade_sold / count($total_sold_rec)); 
						// $per_trade_sold1 = $avg_sold * count($total_sold_rec);    
						echo "<br>avg_sold = ";
						// echo "<br> per_trade_sold1 ".$per_trade_sold1 ;
						print_r($avg_sold);
						print_r("<br>sold count = ".count($total_sold_rec));
					}
					if($CSL_sold_purchase_price !="0"){
						$CSL_per_trade_sold = (float) $CSL_sold_purchase_price * 100;
						echo "<br>per tarde CSL = ".$CSL_per_trade_sold;
						$avg_sold_CSL = (float) ($CSL_per_trade_sold / count($total_sold_rec));
						echo "<br>avg_sold CSL = ";
						print_r($avg_sold_CSL);
						print_r("<br>sold count = ".count($total_sold_rec));
					}
				}//end response > 0 check 
				print_r("<br>oppertunity_id=".$value['opportunity_id']."<br>"); 	
			
				// /////////////////////////////////////////////////////////////////////////END SOLD AVG

				$total_orders = count($total_open_lth_rec) + count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $totalOther;
				$disappear = $parents_executed -  $total_orders;
				$total = count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $disappear;
				if($total == $parents_executed ) {
					$update_fields = array(
						'open_lth'     => count($total_open_lth_rec),
						'new_error'    => count($total_new_error_rec),
						'reumed_child' => count($total_reumed) + count($total_reumed_sold),       
						'costAvgCount' => ($costAvgReturn + $soldCostAvgReturn),
						'cancelled'    => count($total_cancel_rec),
						'sold'         => count($total_sold_rec),         
						'avg_open_lth' => $open_lth_avg,
						'other_status' => $totalOther,
						// 'per_trade_sold' => $per_trade_sold1,
						'avg_sold'     => $avg_sold,
						'avg_manul'    =>$avg_manul,
						'avg_sold_CSL' => $avg_sold_CSL,
						'modified_date' =>$current_time_date  
					);
					if(isset($value['10_max_value']) && isset($value['5_max_value'])){
						$update_fields['is_modified']  = true;
					}
					if(count($total_open_lth_rec)== 0 && count($total_sold_rec) == 0 &&  $totalOther == 0 ){
						$update_fields['oppertunity_missed'] = true;
					}
					}else { 
						$update_fields = array(
							'open_lth'     => count($total_open_lth_rec),
							'new_error'    => count($total_new_error_rec),
							'cancelled'    => count($total_cancel_rec),
							'costAvgCount' => ($costAvgReturn + $soldCostAvgReturn),
							'reumed_child' => count($total_reumed) + count($total_reumed_sold) ,
							'sold'         => count($total_sold_rec),
							'avg_open_lth' => $open_lth_avg,
							// 'per_trade_sold' => $per_trade_sold1,
							'avg_sold'     => $avg_sold,
							'other_status' => $totalOther,   
							'avg_manul'    =>$avg_manul,
							'avg_sold_CSL' => $avg_sold_CSL,
							'modified_date'=>$current_time_date
						);
					}
					$db = $this->mongo_db->customQuery();
					$pipeline = [
					[
						'$match' =>[
						'application_mode' => 'test',
						'parent_status' => ['$exists' => false ],
						'opportunityId' => $value['opportunity_id'],
						'status' => ['$in'=>['LTH','FILLED']],
						],
					],
						[
						'$sort' =>['created_date'=> -1],
						],
						['$limit'=>1]
					];
					$result_buy = $db->buy_orders->aggregate($pipeline);
					$res = iterator_to_array($result_buy);

					$pipeline1 = [
					[
						'$match' =>[
						'application_mode' => 'test',
						'parent_status' => ['$exists' => false ],
						'opportunityId' => $value['opportunity_id'],
						'status' => ['$in'=>['LTH','FILLED']],
						],
					],
						[
						'$sort' =>['created_date'=> 1],
						],
						['$limit'=>1]
					];
					$result_buy1 = $db->buy_orders->aggregate($pipeline1);
					$res1 = iterator_to_array($result_buy1);
					if(!isset($value['first_order_buy']) && !isset($value['last_order_buy'])){
						echo "<br> created_date first =".$res[0]['created_date'];
						echo "<br>created_date last = ".$res1[0]['created_date'];
						$update_fields['first_order_buy'] =  $res[0]['created_date'];
						$update_fields['last_order_buy'] =  $res1[0]['created_date'];
					}
					foreach($api_response as $as_1){
						if($as_1->max_price !='' && $as_1->min_price !='' && $as_1->min_price != 0 && $as_1->max_price != 0){
							$update_fields['5_max_value'] = $as_1->max_price;
							echo "<br>max =". $update_fields['5_max_value'];
							$update_fields['5_min_value'] = $as_1->min_price;  
							echo "<br> min =". $update_fields['5_min_value'];
						} //loop inner check				
					} // foreach loop end

					foreach($api_response_10 as $as){
						if($as->max_price !='' && $as->min_price !='' && $as->min_price !=0 && $as->max_price !=0){
							echo "<br>max 10 = ".$as->max_price;
							$update_fields['10_max_value'] = $as->max_price; 
							echo "<br>min 10=".$as->min_price;
							$update_fields['10_min_value'] = $as->min_price;
						} // if inner check	
					} //end foreach loop

				echo"<br><pre>";
				print_r($update_fields);
				$collection_name = 'opportunity_logs_test_binance';
				$this->mongo_db->where($search_update);
				$this->mongo_db->set($update_fields);
				$query = $this->mongo_db->update($collection_name);	
			}
		} //end foreach
		echo "<br>current time".$current_date_time;
		echo "<br>Total Picked Oppertunities Ids= " . count($response);
		//Save last Cron Executioon
		$this->last_cron_execution_time('Binance test opportunity', '9m', 'start time.'.$current_date_time.'run binance test opportunity calculation (9 * * * *)end time'.date('Y-m-d H:i:s'), 'reports');
	} //end cron

	/////////////////////////////////////////////////////////////////////////////
	///////////////           ASIM CRONE TEST KRAKEN            /////////////////
	/////////////////////////////////////////////////////////////////////////////

	public function insert_latest_oppertunity_into_log_collection_test_kraken(){
		$collection_name = 'opportunity_logs_test_kraken';
		$marketPrices = marketPrices('kraken');
		$this->load->helper('new_common_helper');    
		foreach($marketPrices as $price){
			if($price['_id'] == 'XRPBTC'){   
				$xrpbtc = (float)$price['price'];
			}elseif($price['_id'] == 'BTCUSDT'){
				$btcusdt = (float)$price['price'];
			}elseif($price['_id'] == 'LINKBTC'){
				$linkbtc = (float)$price['price'];
			}elseif($price['_id'] == 'XLMBTC'){
				$xlmbtc = (float)$price['price'];
			}elseif($price['_id'] == 'ETHBTC'){
				$ethbtc = (float)$price['price'];
			}elseif($price['_id'] == 'XMRBTC'){
				$xmrbtc = (float)$price['price'];
			}elseif($price['_id'] == 'ADABTC'){
				$adabtc = (float)$price['price'];
			}elseif($price['_id'] == 'QTUMBTC'){
				$qtumbtc = (float)$price['price'];
			}elseif($price['_id'] == 'TRXBTC'){
				$trxbtc = (float)$price['price'];
			}elseif($price['_id'] == 'XRPUSDT'){
				$xrpusdt = (float)$price['price'];
			}elseif($price['_id'] == 'LTCUSDT'){
				$ltcusdt = (float)$price['price'];
			}elseif($price['_id'] == 'EOSBTC'){      
				$eosbtc = (float)$price['price'];
			}elseif($price['_id'] == 'EOSUSDT'){      
				$eosusdt = (float)$price['price'];
			}elseif($price['_id'] == 'ETCBTC'){       
				$etcbtc = (float)$price['price'];
			}elseif($price['_id'] == 'DASHBTC'){       
				$dashbtc = (float)$price['price'];
			}				
		}//end loop
		$current_date_time =  date('Y-m-d H:i:s');
		$current_time_date =  $this->mongo_db->converToMongodttime($current_date_time);
		
		$current_hour =  date('Y-m-d H:i:s', strtotime('-59 minutes'));
		$orig_date1 = $this->mongo_db->converToMongodttime($current_hour);

		$previous_one_month_date_time = date('Y-m-d H:i:s', strtotime(' - 1 month'));
		$pre_date_1 =  $this->mongo_db->converToMongodttime($previous_one_month_date_time);

		$connection = $this->mongo_db->customQuery();      
		$condition = array('sort' => array('created_date' => -1), 'limit'=>3);

		if(!empty($this->input->get())){
			$where['opportunity_id'] = $this->input->get('opportunityId');
		}else{
			$where['mode'] ='live';
			$where['created_date'] = array('$gte'=>$pre_date_1);
			$where['level'] = array('$ne'=>'level_15');
			$where['is_modified'] = array('$exists'=>false);
			$where['modified_date'] = array('$lte'=>$orig_date1);
		}
		// $where['mode'] ='test';
		// $where['created_date'] = array('$gte'=>$pre_date_1);
		// $where['level'] = array('$ne'=>'level_15');
		// $where['is_modified'] = array('$exists'=>false);
		// $where['modified_date'] = array('$lte'=>$orig_date1);
		
		// $where['opportunity_id']['$in'] = array('5f20d870e17fd050105ea813', '5f204bdbe17fd050105ea7c7');
		$find_rec = $connection->$collection_name->find($where,  $condition);
		$response = iterator_to_array($find_rec);

		foreach ($response as $value){
			$coin= $value['coin'];
			$start_date = $value['created_date']->toDateTime()->format("Y-m-d H:i:s");
			$timestamp = strtotime($start_date);
			$time = $timestamp + (5 * 60 * 60);
			$end_date = date("Y-m-d H:i:s", $time);

			$hours_10 = $timestamp + (10 * 60 * 60);
			$time_10_hours = date("Y-m-d H:i:s", $hours_10);

			$cidition_check = $this->mongo_db->converToMongodttime($end_date);
			$cidition_check_10 = $this->mongo_db->converToMongodttime($time_10_hours);
				$params = array(
				'coin'       => $value['coin'],
				'start_date' => (string)$start_date,
				'end_date'   => (string)$end_date,
				);
				echo "<br>current time=".$current_date_time;
				echo"<br>created_date =".$start_date;
				echo"<br>start date +5 =".$end_date;
				echo"<br>start date +10 =".$time_10_hours;

				if($cidition_check <= $current_time_date){
					$jsondata = json_encode($params);
					$curl = curl_init();
					curl_setopt_array($curl, array(	
						CURLOPT_URL => "http://35.171.172.15:3000/api/minMaxMarketPrices",
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "POST",
						CURLOPT_POSTFIELDS =>$jsondata,
						CURLOPT_HTTPHEADER => array("Content-Type: application/json"), 
					));
					$response_price = curl_exec($curl);	
					curl_close($curl);                                
					$api_response = json_decode($response_price);
				} // main if check for time comapire

				$params_10_hours = array(
					'coin'       => $value['coin'],
					'start_date' => (string)$start_date,
					'end_date'   => (string)$time_10_hours,
				);
				if($cidition_check_10 <= $current_time_date){
					$jsondata = json_encode($params_10_hours);
						$curl_10 = curl_init();
						curl_setopt_array($curl_10, array(
						CURLOPT_URL => "http://35.171.172.15:3000/api/minMaxMarketPrices",
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "POST",
						CURLOPT_POSTFIELDS =>$jsondata,
						CURLOPT_HTTPHEADER => array(
							"Content-Type: application/json"
						), 
						));
						$response_price_10 = curl_exec($curl_10);	
						curl_close($curl_10);
						$api_response_10 = json_decode($response_price_10);
						//echo "<pre>";print_r($api_response_10);
				}
			if ($value['level'] != 'level_15' ){
				$open_lth_avg_per_trade = 0;
				$open_lth_avg = 0;
				$avg_sold = 0;
				$parents_executed = 0;
				$parents_executed = $value['parents_executed'];
				
				$search_update['opportunity_id'] = $value['opportunity_id'];
				$search_update['mode']= 'test';
				
				$other['application_mode']= 'test';
				$other['opportunityId'] =  $value['opportunity_id'];
				$other['status'] = array('$nin' => array('LTH', 'FILLED','canceled','new_ERROR'));
				$buyOther = $connection->buy_orders_kraken->count($other);

				$otherSold['application_mode']= 'test';
				$otherSold['opportunityId'] =  $value['opportunity_id'];
				$otherSold['is_sell_order'] = array('$nin' => array('sold'));
				$otherSold = $connection->sold_buy_orders_kraken->count($otherSold);
				$totalOther = $buyOther+ $otherSold;
				///////////////////////////////////////////////////////////
				$search_open_lth['application_mode']= 'test';
				$search_open_lth['opportunityId'] = $value['opportunity_id'];
				$search_open_lth['status'] = array('$in' => array('LTH', 'FILLED'));
				/////
				$search_cancel['application_mode']= 'test';
				$search_cancel['opportunityId'] = $value['opportunity_id'];
				$search_cancel['status'] = array('$in' => array('canceled'));
				//////
				$search_new_error['application_mode']= 'test';
				$search_new_error['opportunityId'] = $value['opportunity_id'];
				$search_new_error['status'] = array('$in' => array('new_ERROR'));
				////////
				$search_sold['application_mode']= 'test';
				$search_sold['opportunityId'] = $value['opportunity_id'];
				$search_sold['is_sell_order'] = array('$in' => array('sold'));

				$search_resumed['application_mode']= 'test';
				$search_resumed['opportunityId'] = $value['opportunity_id'];
				$search_resumed['resume_status'] = array('$in' => array('resume'));

				///////////////////////////////////////////////////////////////

				$this->mongo_db->where($search_resumed);
				$total_reumed = $this->mongo_db->get('buy_orders_kraken');
				$total_reumed_order   = iterator_to_array($total_reumed);   

				$this->mongo_db->where($search_resumed);
				$total_reumed_sold = $this->mongo_db->get('sold_buy_orders_kraken');
				$total_reumed_sold_orders   = iterator_to_array($total_reumed_sold);

				$this->mongo_db->where($search_open_lth);
				$total_open = $this->mongo_db->get('buy_orders_kraken');
				$total_open_lth_rec   = iterator_to_array($total_open);

				$this->mongo_db->where($search_cancel);
				$total_cancel = $this->mongo_db->get('buy_orders_kraken');
				$total_cancel_rec   = iterator_to_array($total_cancel);

				$this->mongo_db->where($search_new_error);
				$total_new_error = $this->mongo_db->get('buy_orders_kraken');
				$total_new_error_rec   = iterator_to_array($total_new_error);

				$this->mongo_db->where($search_sold);
				$total_sold_total = $this->mongo_db->get('sold_buy_orders_kraken');
				$total_sold_rec   = iterator_to_array($total_sold_total);
				
				$open_lth_puchase_price = 0;
				$open_lth_avg = 0;
				$open_lth_avg_per_trade= 0;
				echo"<br>Total open lth = ".count($total_open_lth_rec);
				if (count($total_open_lth_rec) > 0){
					$puschasePrice = $value2['purchased_price'];
					echo "<br> Open/lth Calculation";
					foreach ($total_open_lth_rec as $key => $value2){
						if($value2['symbol'] == 'ETHBTC'){    
							$open_lth_puchase_price += (float) ($ethbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$ethbtc;
						}elseif($value2['symbol'] == 'BTCUSDT'){
							$open_lth_puchase_price += (float) ($btcusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "btc current price =".$btcusdt."<br>";
							echo "purchase price =".$value2['purchased_price']."<br>";
						}elseif($value2['symbol'] == 'XRPBTC'){
							$open_lth_puchase_price += (float) ($xrpbtc - $value2['purchased_price']) / $value2['purchased_price'];
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$xrpbtc;
						}elseif($value2['symbol'] == 'XRPUSDT'){
							$open_lth_puchase_price += (float) ($xrpusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$xrpusdt;
						}elseif($value2['symbol'] == 'LINKBTC'){
							$open_lth_puchase_price += (float) ($linkbtc - $value2['purchased_price']) / $value2['purchased_price'];
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$linkbtc;
						}elseif($value2['symbol'] == 'XLMBTC'){
							$open_lth_puchase_price += (float) ($xlmbtc - $value2['purchased_price']) / $value2['purchased_price'];
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$xlmbtc;
						}elseif($value2['symbol'] == 'XMRBTC'){
							$open_lth_puchase_price += (float) ($xmrbtc - $value2['purchased_price']) / $value2['purchased_price'];
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$xmrbtc;
						}elseif($value2['symbol'] == 'ADABTC'){
							$open_lth_puchase_price += (float) ($adabtc - $value2['purchased_price']) / $value2['purchased_price'];
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$adabtc;
						}elseif($value2['symbol'] == 'QTUMBTC'){
							$open_lth_puchase_price += (float) ($qtumbtc - $value2['purchased_price']) / $value2['purchased_price'];
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$qtumbtc;   
						}elseif($value2['symbol'] == 'TRXBTC'){
							$open_lth_puchase_price += (float) ($trxbtc - $value2['purchased_price']) / $value2['purchased_price'];
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$trxbtc;
						}elseif($value2['symbol'] == 'LTCUSDT'){
							$open_lth_puchase_price += (float) ($ltcusdt - $value2['purchased_price']) / $value2['purchased_price'];
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$ltcusdt;
						}elseif($value2['symbol'] == 'EOSBTC'){
							$open_lth_puchase_price += (float) ($eosbtc - $value2['purchased_price']) / $value2['purchased_price'];
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$eosbtc;
						}elseif($value2['symbol'] == 'ETCBTC'){
							$open_lth_puchase_price += (float) ($etcbtc - $value2['purchased_price']) / $value2['purchased_price'];
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$etcbtc;
						}elseif($value2['symbol'] == 'EOSUSDT'){
							$open_lth_puchase_price += (float) ($eosusdt - $value2['purchased_price']) / $value2['purchased_price'];
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$eosusdt;
						}elseif($value2['symbol'] == 'DASHBTC'){
							$open_lth_puchase_price += (float) ($dashbtc - $value2['purchased_price']) / $value2['purchased_price'];
							echo "<br>purchase price = ".$value2['purchased_price'];
							echo "<br> current market value = ".$dashbtc;
						}		    
						echo "<br>open_lth_puchase_price +=";
						print_r($open_lth_puchase_price);
					//	array_sum($value2['sell_price']);
						echo "<br> order_id = ".$value2['_id'];
					}//end loop
						$open_lth_avg_per_trade = (float) $open_lth_puchase_price * 100;
						$open_lth_avg = (float) ($open_lth_avg_per_trade / count($total_open_lth_rec));
				
						echo "<br>avg_sold = ";
						print_r($open_lth_avg);
				}//end if

				$sold_puchase_price = 0;
				$avg_sold_CSL = 0;
				$CSL_per_trade_sold = 0;
				$CSL_sold_purchase_price = 0 ;
				$avg_manul = 0;
				$per_trade_sold_manul = 0;
				$manul_sold_purchase_price = 0;
				$avg_sold = 0;
				$per_trade_sold = 0;
				if (count($total_sold_rec) > 0){
					echo "<br> sold calculation";
					foreach ($total_sold_rec as $key => $value1){
						$puschasePrice = $value1['purchased_price'];
						if(isset($value1['is_manual_sold'])){
							$manul_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
							print_r("<br> Market sold price manul = ".$value1['market_sold_price']);
							print_r("<br>purchase price manul =".$value1['purchased_price']);
							print_r("<br> sold_puchase_price manul + =".$manul_sold_purchase_price);
							echo '<br>order_id manul ='.$value1['_id'];	
						}elseif(isset($value1['csl_sold'])){
							$CSL_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
							print_r("<br> Market sold price = ".$value1['market_sold_price']);
							print_r("<br>purchase price =".$value1['purchased_price']);
							print_r("<br> CSL sold_puchase_price + =".$CSL_sold_purchase_price);
							echo '<br>order_id ='.$value1['_id'];
						}else{
							$sold_puchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
							print_r("<br> Market sold price = ".$value1['market_sold_price']);
							print_r("<br>purchase price =".$value1['purchased_price']);
							print_r("<br> sold_puchase_price + =".$sold_puchase_price);
							echo '<br>order_id ='.$value1['_id'];
						}
					} //end sold foreach
					if($manul_sold_purchase_price > 0){
						$sold_puchase_price += $manul_sold_purchase_price;
						$manul_sold_purchase_price = 0;
					}
					if($CSL_sold_purchase_price > 0)
					{
						$sold_puchase_price += $CSL_sold_purchase_price;
						$CSL_sold_purchase_price = 0;
					}
					if($manul_sold_purchase_price != "0"){
						$per_trade_sold_manul = (float) $manul_sold_purchase_price * 100;
						echo "<br>per tarde manul = ".$per_trade_sold_manul;
						$avg_manul = (float) ($per_trade_sold_manul / count($total_sold_rec));
						echo "<br>avg_sold manul = ";
						print_r($avg_manul);
						print_r("<br>sold count = ".count($total_sold_rec));
					}
					if($sold_puchase_price !="0"){
						$per_trade_sold = (float) $sold_puchase_price * 100;
						echo "<br>per tarde = ".$per_trade_sold;
						$avg_sold = (float) ($per_trade_sold / count($total_sold_rec)); 
						echo "<br>avg_sold = ";
						print_r($avg_sold);
						print_r("<br>sold count = ".count($total_sold_rec));
					}
					if($CSL_sold_purchase_price !="0"){
						$CSL_per_trade_sold = (float) $CSL_sold_purchase_price * 100;
						echo "<br>per tarde CSL = ".$CSL_per_trade_sold;
						$avg_sold_CSL = (float) ($CSL_per_trade_sold / count($total_sold_rec));
						echo "<br>avg_sold CSL = ";
						print_r($avg_sold_CSL);
						print_r("<br>sold count = ".count($total_sold_rec));
					}
				}// End check >0
				print_r("<br>oppertunity_id=".$value['opportunity_id']."<br>");
				$total_orders = count($total_open_lth_rec) + count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $totalOther;
				$disappear = $parents_executed -  $total_orders;
				$total = count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $disappear;
				if ($total == $parents_executed){
					$update_fields = array(
						'open_lth'        => count($total_open_lth_rec),
						'new_error'       => count($total_new_error_rec),
						'cancelled'       => count($total_cancel_rec),
						'sold'            => count($total_sold_rec),
						'reumed_child'    => count($total_reumed_order) + count($total_reumed_sold_orders),
						'avg_open_lth'    => $open_lth_avg,
						'other_status'    => $totalOther,
						'per_trade_sold' => $per_trade_sold,
						'avg_sold'        => $avg_sold,
						'avg_manul'       =>$avg_manul,
						'avg_sold_CSL'    => $avg_sold_CSL,
						'modified_date'   =>$current_time_date  
					);

					if(isset($value['10_min_value']) && isset($value['10_max_value']) && isset($value['5_max_value']) && isset($value['5_min_value'])){
						$update_fields['is_modified']  = true;
					}
					if(count($total_open_lth_rec)== 0 && count($total_sold_rec) == 0 &&  $totalOther == 0 ){
						$update_fields['oppertunity_missed'] = true;
					}
					}else{ 
						$update_fields = array(
							'open_lth'     => count($total_open_lth_rec),
							'new_error'    => count($total_new_error_rec),
							'cancelled'    => count($total_cancel_rec),
							'sold'         => count($total_sold_rec),
							'avg_open_lth' => $open_lth_avg,
							'avg_sold'     => $avg_sold,
							'per_trade_sold' => $per_trade_sold,
							'reumed_child' => count($total_reumed_order) + count($total_reumed_sold_orders),
							'other_status' => $totalOther,   
							'avg_manul'    =>$avg_manul,
							'avg_sold_CSL' => $avg_sold_CSL,
							'modified_date'=>$current_time_date
						);
					}
					$db = $this->mongo_db->customQuery();
					$pipeline = [
					[
						'$match' =>[
						'application_mode' => 'test',
						'parent_status' => ['$exists' => false ],
						'opportunityId' => $value['opportunity_id'],
						'status' => ['$in'=>['LTH','FILLED']],
						],
					],
						[
						'$sort' =>['created_date'=> -1],
						],
						['$limit'=>1]
					];
					$result_buy = $db->buy_orders_kraken->aggregate($pipeline);
					$res = iterator_to_array($result_buy);

					$pipeline1 = [
					[
						'$match' =>[
						'application_mode' => 'test',
						'parent_status' => ['$exists' => false ],
						'opportunityId' => $value['opportunity_id'],
						'status' => ['$in'=>['LTH','FILLED']],
						],
					],
						[
						'$sort' =>['created_date'=> 1],
						],
						['$limit'=>1]
					];
					$result_buy1 = $db->buy_orders_kraken->aggregate($pipeline1);
					$res1 = iterator_to_array($result_buy1);
					if(!isset($value['first_order_buy']) && !isset($value['last_order_buy'])){
						echo "<br> created_date first =".$res[0]['created_date'];
						echo "<br>created_date last = ".$res1[0]['created_date'];
						$update_fields['first_order_buy'] =  $res[0]['created_date'];
						$update_fields['last_order_buy'] =  $res1[0]['created_date'];
					}
					if(!isset($value['opp_came_binance']) && !isset($value['opp_came_kraken']) && !isset($value['opp_came_bam'])){	
						$opper_search['application_mode']= 'test';
						$opper_search['opportunityId'] = $value['opportunity_id'];
						
						$connetct= $this->mongo_db->customQuery();
						$pending_curser = $connetct->buy_orders->find($opper_search);
						$buy_order = iterator_to_array($pending_curser);
						echo "<br>result binance=".count($buy_order);

						$pending_curser_buy = $connetct->sold_buy_order->find($opper_search);
						$sold_bbuy_order = iterator_to_array($pending_curser_buy);
						echo "<br>result binance sold=".count($sold_bbuy_order);

						if(count($buy_order) > 0 || count($sold_bbuy_order) > 0 ){
							$update_fields['opp_came_binance'] = '1';
						}else{
							$update_fields['opp_came_binance'] = '0';
						}
						
						$this->mongo_db->where($opper_search);
						$response_kraken = $this->mongo_db->get('buy_orders_kraken');
						$data_kraken = iterator_to_array($response_kraken);
						echo "<br>result kraken=". count($data_kraken);

						$this->mongo_db->where($opper_search);
						$response_kraken_sold = $this->mongo_db->get('sold_buy_orders_kraken');
						$data_kraken_sold = iterator_to_array($response_kraken_sold);
						echo "<br>result kraken sold=". count($data_kraken_sold);
						if(count($data_kraken) > 0 || count($data_kraken_sold) > 0){
							$update_fields['opp_came_kraken'] = '1';
						}else{
							$update_fields['opp_came_kraken'] = '0';
						}
						
						$this->mongo_db->where($opper_search);
						$response_bam = $this->mongo_db->get('buy_orders_bam');
						$data_bam = iterator_to_array($response_bam);
						echo "<br>result bam=". count($data_bam );

						$this->mongo_db->where($opper_search);
						$response_bam_sold = $this->mongo_db->get('sold_buy_orders_bam');
						$data_bam_sold = iterator_to_array($response_bam_sold);
						echo "<br>result bam sold =". count($data_bam_sold);

						if(count($data_bam) > 0 || count($data_bam_sold) > 0){
							$update_fields['opp_came_bam'] = '1';
						}else{
							$update_fields['opp_came_bam'] = '0';
						}
					}
					foreach($api_response as $as_1){
						echo "testing".$as_1;
						if($as_1->max_price !='' && $as_1->min_price !='' && $as_1->min_price != 0 && $as_1->max_price != 0){
							$update_fields['5_max_value'] = $as_1->max_price;
							echo "<br>max =". $update_fields['5_max_value'];
							$update_fields['5_min_value'] = $as_1->min_price;  
							echo "<br> min =". $update_fields['5_min_value'];
						} //loop inner check				
					} // foreach loop end

					foreach($api_response_10 as $as){
						if($as->max_price !='' && $as->min_price !='' && $as->min_price !=0 && $as->max_price !=0){
							echo "<br>max 10 = ".$as->max_price;
							$update_fields['10_max_value'] = $as->max_price; 
							echo "<br>min 10=".$as->min_price;
							$update_fields['10_min_value'] = $as->min_price;
						} // if inner check	
					} //end foreach loop
			
				echo"<br><pre>";
				print_r($update_fields);
				$this->mongo_db->where($search_update);
				$this->mongo_db->set($update_fields);
				$this->mongo_db->update($collection_name);
			}
		} //end foreach
		echo "<br>Total Picked Oppertunities Ids= " . count($response);
		//Save last Cron Executioon
		$this->last_cron_execution_time('Kraken test opportunity', '7m', 'run kraken test opportunity calculation (7 * * * *)', 'reports');
	} //end cron

	/////////////////////////////////////////////////////////////////////////////
	///////////////           ASIM CRONE TEST BAM          ///////////////////////
	/////////////////////////////////////////////////////////////////////////////
	//the below method was commented by MUHAMMAD SHERAZ on(31-august-2021) on behalf of Shehzad.
	// public function insert_latest_oppertunity_into_log_collection_test_bam(){
	// 	$collection_name = 'opportunity_logs_test_bam';
	// 	$marketPrices = marketPrices('bam');
	// 	$this->load->helper('new_common_helper');
	// 	foreach($marketPrices as $price){
	// 		if($price['_id'] == 'XRPBTC'){
	// 			$xrpbtc = (float)$price['price'];
	// 		}elseif($price['_id'] == 'ETHBTC'){
	// 			$ethbtc = (float)$price['price'];
	// 		}elseif($price['_id'] == 'XRPUSDT'){
	// 			$xrpusdt = (float)$price['price'];
	// 		}elseif($price['_id'] == 'BTCUSDT'){       
	// 			$btcusdt = (float)$price['price'];
	// 		}elseif($price['_id'] == 'NEOUSDT'){
	// 			$neousdt = (float)$price['price'];
	// 		}elseif($price['_id'] == 'QTUMUSDT'){
	// 			$qtumusdt = (float)$price['price'];
	// 		}
	// 	}//end loop  
	// 	$current_date_time =  date('Y-m-d H:i:s');
	// 	$current_time_date =  $this->mongo_db->converToMongodttime($current_date_time);
		
	// 	$current_hour =  date('Y-m-d H:i:s', strtotime('-40 minutes'));
	// 	$orig_date1 = $this->mongo_db->converToMongodttime($current_hour);

	// 	$previous_one_month_date_time = date('Y-m-d H:i:s', strtotime(' - 1 month'));
	// 	$pre_date_1 =  $this->mongo_db->converToMongodttime($previous_one_month_date_time);

	// 	$connection = $this->mongo_db->customQuery();      
	// 	$condition = array('sort' => array('created_date' => -1),'limit'=>3);
		
	// 	if(!empty($this->input->get())){
	// 		$where['opportunity_id'] = $this->input->get('opportunityId');
	// 	}else{
	// 		$where['mode'] ='live';
	// 		$where['created_date'] = array('$gte'=>$pre_date_1);
	// 		$where['level'] = array('$ne'=>'level_15');
	// 		$where['is_modified'] = array('$exists'=>false);
	// 		$where['modified_date'] = array('$lte'=>$orig_date1);
	// 	}
	// 	// $where['mode'] ='test';
	// 	// $where['created_date'] = array('$gte'=>$pre_date_1);
	// 	// $where['level'] = array('$ne'=>'level_15');
	// 	// $where['is_modified'] = array('$exists'=>false);
	// 	// $where['modified_date'] = array('$lte'=>$orig_date1);
		
	// 	$find_rec = $connection->$collection_name->find($where,  $condition);
	// 	$response = iterator_to_array($find_rec);

	// 	foreach ($response as $value){
	// 		$coin= $value['coin'];
	// 		$start_date = $value['created_date']->toDateTime()->format("Y-m-d H:i:s");
	// 		$timestamp = strtotime($start_date);
	// 		$time = $timestamp + (5 * 60 * 60);
	// 		$end_date = date("Y-m-d H:i:s", $time);

	// 		$hours_10 = $timestamp + (10 * 60 * 60);
	// 		$time_10_hours = date("Y-m-d H:i:s", $hours_10);

	// 		$cidition_check = $this->mongo_db->converToMongodttime($end_date);
	// 		$cidition_check_10 = $this->mongo_db->converToMongodttime($time_10_hours);
	// 			$params = array(
	// 			'coin'       => $value['coin'],
	// 			'start_date' => (string)$start_date,
	// 			'end_date'   => (string)$end_date,
	// 			);
	// 			echo "<br>current time=".$current_date_time;
	// 			echo"<br>created_date =".$start_date;
	// 			echo"<br>start date +5 =".$end_date;
	// 			echo"<br>start date +10 =".$time_10_hours;

	// 			if($cidition_check <= $current_time_date){
	// 				$jsondata = json_encode($params);
	// 				$curl = curl_init();
	// 				curl_setopt_array($curl, array(	
	// 					CURLOPT_URL => "http://35.171.172.15:3000/api/minMaxMarketPrices",
	// 					CURLOPT_RETURNTRANSFER => true,
	// 					CURLOPT_ENCODING => "",
	// 					CURLOPT_MAXREDIRS => 10,
	// 					CURLOPT_TIMEOUT => 0,
	// 					CURLOPT_FOLLOWLOCATION => true,
	// 					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 					CURLOPT_CUSTOMREQUEST => "POST",
	// 					CURLOPT_POSTFIELDS =>$jsondata,
	// 					CURLOPT_HTTPHEADER => array("Content-Type: application/json"), 
	// 				));
	// 				$response_price = curl_exec($curl);	
	// 				curl_close($curl);                                
	// 				$api_response = json_decode($response_price);
	// 			} // main if check for time comapire

	// 		$params_10_hours = array(
	// 			'coin'       => $value['coin'],
	// 			'start_date' => (string)$start_date,
	// 			'end_date'   => (string)$time_10_hours,
	// 		);
	// 		if($cidition_check_10 <= $current_time_date){
	// 			$jsondata = json_encode($params_10_hours);
	// 				$curl_10 = curl_init();
	// 				curl_setopt_array($curl_10, array(
	// 				CURLOPT_URL => "http://35.171.172.15:3000/api/minMaxMarketPrices",
	// 				CURLOPT_RETURNTRANSFER => true,
	// 				CURLOPT_ENCODING => "",
	// 				CURLOPT_MAXREDIRS => 10,
	// 				CURLOPT_TIMEOUT => 0,
	// 				CURLOPT_FOLLOWLOCATION => true,
	// 				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 				CURLOPT_CUSTOMREQUEST => "POST",
	// 				CURLOPT_POSTFIELDS =>$jsondata,
	// 				CURLOPT_HTTPHEADER => array(
	// 					"Content-Type: application/json"
	// 				), 
	// 				));
	// 			$response_price_10 = curl_exec($curl_10);	
	// 			curl_close($curl_10);
	// 			$api_response_10 = json_decode($response_price_10);
	// 		}
	// 		if ($value['level'] != 'level_15' ){
	// 			$open_lth_avg_per_trade = 0;
	// 			$open_lth_avg = 0;
	// 			$avg_sold = 0;
	// 			$parents_executed = 0;
	// 			$parents_executed = $value['parents_executed'];
				
	// 			$search_update['opportunity_id'] = $value['opportunity_id'];
	// 			$search_update['mode']= 'test';
	// 			//////////////////////////////////////////////////////////////
	// 			$other['application_mode']= 'test';
	// 			$other['opportunityId'] =  $value['opportunity_id'];
	// 			$other['status'] = array('$nin' => array('LTH', 'FILLED','canceled','new_ERROR'));
	// 			// $this->mongo_db->where($other);
	// 			$buyOther = $connection->buy_orders_bam->count($other);
	// 			// $total_other_rec   = iterator_to_array($total_other);

	// 			$otherSold['application_mode']= 'test';
	// 			$otherSold['opportunityId'] =  $value['opportunity_id'];
	// 			$otherSold['is_sell_order'] = array('$nin' => array('sold'));
	// 			$otherSold = $connection->sold_buy_orders_bam->count($otherSold);
	// 			$totalOther = $buyOther+ $otherSold;
	// 			//////////////////////////////////////////////////////////////
	// 			$search_open_lth['application_mode']= 'test';
	// 			$search_open_lth['opportunityId'] = $value['opportunity_id'];
	// 			$search_open_lth['status'] = array('$in' => array('LTH', 'FILLED'));

	// 			print_r("<br>oppertunity_id=".$value['opportunity_id']);
	// 			/////
	// 			$search_cancel['application_mode']= 'test';
	// 			$search_cancel['opportunityId'] = $value['opportunity_id'];
	// 			$search_cancel['status'] = array('$in' => array('canceled'));
	// 			//////
	// 			$search_new_error['application_mode']= 'test';
	// 			$search_new_error['opportunityId'] = $value['opportunity_id'];
	// 			$search_new_error['status'] = array('$in' => array('new_ERROR'));
	// 			////////
	// 			$search_sold['application_mode']= 'test';
	// 			$search_sold['opportunityId'] = $value['opportunity_id'];
	// 			$search_sold['is_sell_order'] = array('$in' => array('sold'));

	// 			$search_reumed['application_mode']= 'test';
	// 			$search_reumed['opportunityId'] = $value['opportunity_id'];
	// 			$search_reumed['resume_status'] = array('$in' => array('resume'));  

	// 			///////////////////////////////////////////////////////////////

	// 			$this->mongo_db->where($search_reumed);
	// 			$total_reumed = $this->mongo_db->get('buy_orders_bam');
	// 			$total_reumed_order   = iterator_to_array($total_reumed);

	// 			$this->mongo_db->where($search_reumed);
	// 			$total_reumed_sold = $this->mongo_db->get('sold_buy_orders_bam');
	// 			$total_reumed_sold_order   = iterator_to_array($total_reumed_sold);   

	// 			$this->mongo_db->where($search_open_lth);
	// 			$total_open = $this->mongo_db->get('buy_orders_bam');
	// 			$total_open_lth_rec   = iterator_to_array($total_open);
				
	// 			$this->mongo_db->where($search_cancel);
	// 			$total_cancel = $this->mongo_db->get('buy_orders_bam');
	// 			$total_cancel_rec   = iterator_to_array($total_cancel);

	// 			$this->mongo_db->where($search_new_error);
	// 			$total_new_error = $this->mongo_db->get('buy_orders_bam');
	// 			$total_new_error_rec   = iterator_to_array($total_new_error);

	// 			$this->mongo_db->where($search_sold);
	// 			$total_sold_total = $this->mongo_db->get('sold_buy_orders_bam');
	// 			$total_sold_rec   = iterator_to_array($total_sold_total);
	// 			echo"<br>total sold count = ".count($total_sold_rec);
				
	// 			$open_lth_puchase_price = 0;
	// 			$open_lth_avg = 0;
	// 			$open_lth_avg_per_trade= 0;
	// 			echo"<br>Total open lth = ".count($total_open_lth_rec);
	// 			if (count($total_open_lth_rec) > 0) {
	// 				echo "<br> Open/lth Calculation";
	// 				foreach ($total_open_lth_rec as $key => $value2){
	// 					if($value2['symbol'] == 'ETHBTC'){
	// 						$open_lth_puchase_price += (float) ($ethbtc - $value2['purchased_price'])/ $value2['purchased_price'] ;
	// 						echo "<br>purchase price = ".$value2['purchased_price'];
	// 						echo "<br> current market value = ".$ethbtc;
	// 					}elseif($value2['symbol'] == 'BTCUSDT'){
	// 						$open_lth_puchase_price += (float) ($btcusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
	// 						echo "btc current price =".$btcusdt."<br>";
	// 						echo "purchase price =".$value2['purchased_price']."<br>";
	// 					}elseif($value2['symbol'] == 'XRPBTC'){
	// 						$open_lth_puchase_price += (float) ($xrpbtc - $value2['purchased_price']) / $value2['purchased_price'];
	// 						echo "<br>purchase price = ".$value2['purchased_price'];
	// 						echo "<br> current market value = ".$xrpbtc;
	// 					}elseif($value2['symbol'] == 'XRPUSDT'){
	// 						$open_lth_puchase_price += (float) ($xrpusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
	// 						echo "<br>purchase price = ".$value2['purchased_price'];
	// 						echo "<br> current market value = ".$xrpusdt;
	// 					}elseif($value2['symbol'] == 'NEOUSDT'){
	// 						$open_lth_puchase_price += (float) ($neousdt - $value2['purchased_price']) / $value2['purchased_price'] ;
	// 						echo "btc current price =".$neousdt."<br>";
	// 						echo "purchase price =".$value2['purchased_price']."<br>"; 
	// 					}elseif($value2['symbol'] == 'QTUMUSDT'){
	// 						$open_lth_puchase_price += (float) ($qtumusdt - $value2['purchased_price']) / $value2['purchased_price'] ;
	// 						echo "<br>purchase price = ".$value2['purchased_price'];
	// 						echo "<br> current market value = ".$qtumusdt;
	// 					}	
	// 					echo "<br>open_lth_puchase_price +=";
	// 					print_r($open_lth_puchase_price);
	// 				//	array_sum($value2['sell_price']);
	// 					echo "<br> order_id = ".$value2['_id'];
	// 				}//end loop
	// 					$open_lth_avg_per_trade = (float) $open_lth_puchase_price * 100;
	// 					$open_lth_avg = (float) ($open_lth_avg_per_trade / count($total_open_lth_rec));
				
	// 					echo "<br>avg_open-lth = ";
	// 					print_r($open_lth_avg);
	// 			}//end if

	// 			$sold_puchase_price = 0;
	// 			$avg_sold_CSL = 0;
	// 			$CSL_per_trade_sold = 0;
	// 			$CSL_sold_purchase_price = 0 ;
	// 			$avg_manul = 0;
	// 			$per_trade_sold_manul = 0;
	// 			$manul_sold_purchase_price = 0;
	// 			$avg_sold = 0;
	// 			$per_trade_sold = 0;
	// 			if (count($total_sold_rec) > 0){
	// 				echo "<br> sold calculation";
	// 				foreach ($total_sold_rec as $key => $value1){
	// 					if(isset($value1['is_manual_sold'])){
	// 						$manul_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
	// 						print_r("<br> Market sold price manul = ".$value1['market_sold_price']);
	// 						print_r("<br>purchase price manul =".$value1['purchased_price']);
	// 						print_r("<br> sold_puchase_price manul + =".$manul_sold_purchase_price);
	// 						echo '<br>order_id manul ='.$value1['_id'];	
	// 					}elseif(isset($value1['csl_sold'])){
	// 						$CSL_sold_purchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
	// 						print_r("<br> Market sold price = ".$value1['market_sold_price']);
	// 						print_r("<br>purchase price =".$value1['purchased_price']);
	// 						print_r("<br> CSL sold_puchase_price + =".$CSL_sold_purchase_price);
	// 						echo '<br>order_id ='.$value1['_id'];
	// 					}else{
	// 						$sold_puchase_price += (float) ($value1['market_sold_price'] - $value1['purchased_price']) / $value1['purchased_price'];
	// 						print_r("<br> Market sold price = ".$value1['market_sold_price']);
	// 						print_r("<br>purchase price =".$value1['purchased_price']);
	// 						print_r("<br> sold_puchase_price + =".$sold_puchase_price);
	// 						echo '<br>order_id ='.$value1['_id'];
	// 					}
	// 				} //end sold foreach
	// 				if($manul_sold_purchase_price > 0){
	// 					$sold_puchase_price += $manul_sold_purchase_price;
	// 					$manul_sold_purchase_price = 0;
	// 				}
	// 				if($CSL_sold_purchase_price > 0){
	// 					$sold_puchase_price += $CSL_sold_purchase_price;
	// 					$CSL_sold_purchase_price = 0;
	// 				}
	// 				if($manul_sold_purchase_price != "0"){
	// 					$per_trade_sold_manul = (float) $manul_sold_purchase_price * 100;
	// 					echo "<br>per tarde manul = ".$per_trade_sold_manul;
	// 					$avg_manul = (float) ($per_trade_sold_manul / count($total_sold_rec));
	// 					echo "<br>avg_sold manul = ";
	// 					print_r($avg_manul);
	// 					print_r("<br>sold count = ".count($total_sold_rec));
	// 				}
	// 				if($sold_puchase_price !="0"){
	// 					$per_trade_sold = (float) $sold_puchase_price * 100;
	// 					echo "<br>per tarde = ".$per_trade_sold;
	// 					$avg_sold = (float) ($per_trade_sold / count($total_sold_rec));
	// 					echo "<br>avg_sold = ";
	// 					print_r($avg_sold);
	// 					print_r("<br>sold count = ".count($total_sold_rec));
	// 				}
	// 				if($CSL_sold_purchase_price !="0"){
	// 					$CSL_per_trade_sold = (float) $CSL_sold_purchase_price * 100;
	// 					echo "<br>per tarde CSL = ".$CSL_per_trade_sold;
	// 					$avg_sold_CSL = (float) ($CSL_per_trade_sold / count($total_sold_rec));
	// 					echo "<br>avg_sold CSL = ";
	// 					print_r($avg_sold_CSL);
	// 					print_r("<br>sold count = ".count($total_sold_rec));
	// 				}
	// 			}// End check >0
	// 			print_r("<br>oppertunity_id=".$value['opportunity_id']."<br>");
	// 			$total_orders = count($total_open_lth_rec) + count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $totalOther;
	// 			$disappear = $parents_executed -  $total_orders;
	// 			$total = count($total_new_error_rec) + count($total_cancel_rec) + count($total_sold_rec) + $disappear;
	// 			if ($total == $parents_executed ){
	// 				$update_fields = array(
	// 					'open_lth'     => count($total_open_lth_rec),
	// 					'new_error'    => count($total_new_error_rec),
	// 					'cancelled'    => count($total_cancel_rec),
	// 					'sold'         => count($total_sold_rec),
	// 					'avg_open_lth' => $open_lth_avg,
	// 					'other_status' => $totalOther,
	// 					'per_trade_sold' => $per_trade_sold,
	// 					'reumed_child'  => count($total_reumed_sold_order) + count($total_reumed_order),
	// 					'avg_sold'     => $avg_sold,
	// 					'avg_manul'    =>$avg_manul,
	// 					'avg_sold_CSL' => $avg_sold_CSL,
	// 					'modified_date' =>$current_time_date  
	// 				);
	// 				if(isset($value['10_max_value']) && isset($value['5_max_value'])){
	// 					$update_fields['is_modified']  = true;
	// 				}
	// 				if(count($total_open_lth_rec)== 0 && count($total_sold_rec) == 0 &&  $totalOther == 0 ){
	// 					$update_fields['oppertunity_missed'] = true;
	// 				}
	// 				}else{ 
	// 					$update_fields = array(
	// 						'open_lth'     => count($total_open_lth_rec),
	// 						'new_error'    => count($total_new_error_rec),
	// 						'cancelled'    => count($total_cancel_rec),
	// 						'sold'         => count($total_sold_rec),
	// 						'reumed_child' => count($total_reumed_sold_order) + count($total_reumed_order),
	// 						'avg_open_lth' => $open_lth_avg,
	// 						'avg_sold'     => $avg_sold,
	// 						'per_trade_sold' => $per_trade_sold,
	// 						'other_status' => $totalOther,   
	// 						'avg_manul'    =>$avg_manul,
	// 						'avg_sold_CSL' => $avg_sold_CSL,
	// 						'modified_date'=>$current_time_date
	// 					);
	// 				}
	// 				$db = $this->mongo_db->customQuery();
	// 				$pipeline = [
	// 				[
	// 					'$match' =>[
	// 					'application_mode' => 'test',
	// 					'parent_status' => ['$exists' => false ],
	// 					'opportunityId' => $value['opportunity_id'],
	// 					'status' => ['$in'=>['LTH','FILLED']],
	// 					],
	// 				],
	// 					[
	// 					'$sort' =>['created_date'=> -1],
	// 					],
	// 					['$limit'=>1]
	// 				];
	// 				$result_buy = $db->buy_orders_bam->aggregate($pipeline);
	// 				$res = iterator_to_array($result_buy);

	// 				$pipeline1 = [
	// 				[
	// 					'$match' =>[
	// 					'application_mode' => 'test',
	// 					'parent_status' => ['$exists' => false ],
	// 					'opportunityId' => $value['opportunity_id'],
	// 					'status' => ['$in'=>['LTH','FILLED']],
	// 					],
	// 				],
	// 					[
	// 					'$sort' =>['created_date'=> 1],
	// 					],
	// 					['$limit'=>1]
	// 				];
	// 				$result_buy1 = $db->buy_orders_bam->aggregate($pipeline1);
	// 				$res1 = iterator_to_array($result_buy1);
	// 				if(!isset($value['first_order_buy']) && !isset($value['last_order_buy'])){
	// 					echo "<br> created_date first =".$res[0]['created_date'];
	// 					echo "<br>created_date last = ".$res1[0]['created_date'];
	// 					$update_fields['first_order_buy'] =  $res[0]['created_date'];
	// 					$update_fields['last_order_buy'] =  $res1[0]['created_date'];
	// 				}
	// 				if(!isset($value['opp_came_binance']) && !isset($value['opp_came_kraken']) && !isset($value['opp_came_bam'])){	
	// 					$opper_search['application_mode']= 'test';
	// 					$opper_search['opportunityId'] = $value['opportunity_id'];
						
	// 					$connetct= $this->mongo_db->customQuery();

	// 					$pending_curser = $connetct->buy_orders->find($opper_search);
	// 					$buy_order = iterator_to_array($pending_curser);
	// 					echo "<br>result binance=".count($buy_order);

	// 					$pending_curser_buy = $connetct->sold_buy_order->find($opper_search);
	// 					$sold_bbuy_order = iterator_to_array($pending_curser_buy);
	// 					echo "<br>result binance sold=".count($sold_bbuy_order);

	// 					if(count($buy_order) > 0 || count($sold_bbuy_order) > 0 ){
	// 						$update_fields['opp_came_binance'] = '1';
	// 					}else{
	// 						$update_fields['opp_came_binance'] = '0';
	// 					}
						
	// 					$this->mongo_db->where($opper_search);
	// 					$response_kraken = $this->mongo_db->get('buy_orders_kraken');
	// 					$data_kraken = iterator_to_array($response_kraken);
	// 					echo "<br>result kraken=". count($data_kraken);

	// 					$this->mongo_db->where($opper_search);
	// 					$response_kraken_sold = $this->mongo_db->get('sold_buy_orders_kraken');
	// 					$data_kraken_sold = iterator_to_array($response_kraken_sold);
	// 					echo "<br>result kraken sold=". count($data_kraken_sold);
	// 					if(count($data_kraken) > 0 || count($data_kraken_sold) > 0){
	// 						$update_fields['opp_came_kraken'] = '1';
	// 					}else{
	// 						$update_fields['opp_came_kraken'] = '0';
	// 					}
						
	// 					$this->mongo_db->where($opper_search);
	// 					$response_bam = $this->mongo_db->get('buy_orders_bam');
	// 					$data_bam = iterator_to_array($response_bam);
	// 					echo "<br>result bam=". count($data_bam );

	// 					$this->mongo_db->where($opper_search);
	// 					$response_bam_sold = $this->mongo_db->get('sold_buy_orders_bam');
	// 					$data_bam_sold = iterator_to_array($response_bam_sold);
	// 					echo "<br>result bam sold =". count($data_bam_sold);

	// 					if(count($data_bam) > 0 || count($data_bam_sold) > 0){
	// 						$update_fields['opp_came_bam'] = '1';
	// 					}else{
	// 						$update_fields['opp_came_bam'] = '0';
	// 					}
	// 				}
	// 					foreach($api_response as $as_1){
	// 							if($as_1->max_price !='' && $as_1->min_price !='' && $as_1->min_price != 0 && $as_1->max_price != 0){
	// 									$update_fields['5_max_value'] = $as_1->max_price;
	// 									echo "<br>max =". $update_fields['5_max_value'];
	// 									$update_fields['5_min_value'] = $as_1->min_price;  
	// 									echo "<br> min =". $update_fields['5_min_value'];
	// 									} //loop inner check				
	// 					} // foreach loop end
	// 					foreach($api_response_10 as $as){
	// 							if($as->max_price !='' && $as->min_price !='' && $as->min_price !=0 && $as->max_price !=0){
	// 									echo "<br>max 10 = ".$as->max_price;
	// 									$update_fields['10_max_value'] = $as->max_price; 
	// 									echo "<br>min 10=".$as->min_price;
	// 									$update_fields['10_min_value'] = $as->min_price;
	// 							} // if inner check	
	// 					} //end foreach loop
	// 				echo"<br><pre>";
	// 				print_r($update_fields);
	// 				$this->mongo_db->where($search_update);
	// 				$this->mongo_db->set($update_fields);
	// 				$query = $this->mongo_db->update($collection_name);
	// 		}
	// 	} //end foreach
	// 	echo "<br>current time".$current_date_time;
	// 	echo "<br>Total Picked Oppertunities Ids= " . count($response);
	// 	//Save last Cron Executioon
	// 	$this->last_cron_execution_time('Bam test opportunity', '5m', 'start time ='.$current_date_time.'run bam test opportunity calculation (5 * * * *)end time'. date('Y-m-d H:i:s'), 'reports');
	// } //end cron

	////////////////////////////////////////////////////////////////////////////
	////////////////////       ASIM MONTH OPPERTUNITY REPORT      //////////////
	///////////////////////////////////////////////////////////////////////////

	public function oppertunity_monthly(){
		 //echo "<pre>";print_r("inthe function");
		header("Access-Control-Allow-Origin: https://digiebot.com");
		header('Content-type: application/json');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: GET, POST");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
		
		//ini_set("display_errors", E_ALL);
		//error_reporting(E_ALL);
		
		$month = $this->input->get('month');
		if($month ==''){
			$month = date('Y-m');
		}
		$exchange = $this->input->get('exchange'); 
        if($exchange ==''){
			$exchange = 'binance';
		}
		// echo "<br>exchange===>  ",$exchange;
		// echo "<br>month===>  ",$month;

		$sorting = ['sort' => ['total_oppertunities' => -1]];
		$collection_name = 'opportunity_logs_monthly_'.$exchange;		
		$search['month'] = $month; 

		$db = $this->mongo_db->customQuery();
		$object3 = $db->$collection_name->find($search, $sorting); 
		
		//echo "<pre>";print_r(json_encode($search));
		if ($object3) {
		  $log_arry = iterator_to_array($object3);
		} else {
		    $log_arry = [];
		}
		
		$result = json_encode($log_arry, JSON_NUMERIC_CHECK | JSON_PARTIAL_OUTPUT_ON_ERROR);
		//print_r(json_last_error_msg());
		echo $result;exit;
		print_r(json_last_error_msg());
		echo "<pre>";print_r($log_arry);
		//$data['final_array'] = $log_arry;
		//$this->session->set_userdata('data_session',$log_arry);
// 		echo json_encode($log_arry, true); 
		
		exit;

	}
	public function oppertunity_month(){
		$year = date('Y');
		$curent = date('Y-m');
		$coin_array_all = $this->mod_coins->get_all_coins();
		if($this->input->post()){
				if($this->input->post('filter_by_coin')){
					$filter['coin']['$in'] = $this->input->post('filter_by_coin');
				}else{
					$filter['coin']['$in'] = array_column($coin_array_all, 'symbol');
				}
				if($this->input->post('exchange')){
					$collection_name = 'opportunity_logs_monthly_'.$this->input->post('exchange'); 
				}else{
					$collection_name = 'opportunity_logs_monthly_binance';
				}
				if($this->input->post('filter_by_month')){
					$filter['month'] = $year.'-'.$this->input->post('filter_by_month');
					}
					$this->mongo_db->sort(array('created_date' => -1));
					$this->mongo_db->where($filter);
					$object3 = $this->mongo_db->get($collection_name);
					$log_arry = iterator_to_array($object3);
					$data['final_array'] = $log_arry;
					
			}else{
			$this->mongo_db->sort(array('created_date' => -1));
			$search['month'] = $curent; 
			$this->mongo_db->where($search);
			$object3 = $this->mongo_db->get('opportunity_logs_monthly_binance');
			$log_arry = iterator_to_array($object3);
			$data['final_array'] = $log_arry;
		}
		$data['coins'] = $coin_array_all;
		$this->stencil->paint('admin/trigger_rule_report/oppertunity_monthly', $data);
	}

	/////////////////////////////////////////////////////////////////////////////
	///////////////            ASIM CRONE BINANCE MONTHLY          //////////////
	/////////////////////////////////////////////////////////////////////////////
 	// adding latest opportunity into monthly log collection for binance
	public function insert_latest_oppertunity_into_log_collection_monthly(){
		//Save last Cron Executioon
		$this->last_cron_execution_time('Binance live opportunity monthly', '55m', 'current ='.date('Y-m-d H:i:s').'run binance live opportunity avg calculation monthly end time(55 * * * *)'.date('Y-m-d H:i:s'), 'reports');

		$compaire_month = date('Y-m');
		$month = date('Y-m');
		$start_date_time =  date('Y-m-01 00:00:00');
		$time_date =  $this->mongo_db->converToMongodttime($start_date_time);

		$end = date('Y-m-d 23:59:59');
		$enddate = $this->mongo_db->converToMongodttime($end);
		
		$current_date = date('Y-m-d H:i:s');
		$current_time_date =  $this->mongo_db->converToMongodttime($current_date);

		// $current_hour1 =  date('Y-m-d H:i:s', strtotime('-2 minutes'));
		// $pre_time_date =  $this->mongo_db->converToMongodttime($current_hour1);

		$coin_array_all = $this->mod_coins->get_all_coins();
		$coin_count = 0;
		foreach($coin_array_all as $value){
			echo "<br>count check= ".$coin_count;
			$custom = $this->mongo_db->customQuery(); 
			$condtn = array('sort'=>array('created_date'=> -1));
			// $coin = $value['symbol'];
			$coin = $coin_array_all[$coin_count]['symbol'];
			$where['coin'] = $coin; 
			$where['mode'] = 'live';
			$where['oppertunity_missed'] = array('$exists'=>false);
			$where['created_date'] = array('$gte' => $time_date, '$lte'=>$enddate);
			// $where['month_modified_time'] =array('$lte'=>$pre_time_date);
			$where['level'] = array('$in'=>array('level_5', 'level_6', 'level_8', 'level_10','level_11','level_12','level_13', 'level_17', 'level_18'));
			
			$resps = $custom->opportunity_logs_binance->find($where, $condtn);
			$result_1 = iterator_to_array($resps);  
			echo"<br>coin name =".$coin;
			$sold = 0;
			$open_lth =0;
			$avg_sold_manul = 0;
			$avg_sold = 0;
			$avg_open_lth =0;
			$picket_parent = 0;
			$execuated_parent = 0;
			$other_status = 0;
			$cancelled = 0;        
			$new_error = 0;
			$created_date ='';
			$sum_all = 0;
			$total_investment_btc = 0;						
			$total_profit_btc = 0;
			$total_buy_comission_btc = 0;
			$total_sell_comission_btc = 0;
			$buy_comission_BNB =0;
			$sell_comission_BNB =0;
			$total_gain_btc = 0;

			$sellTimeDiffRange1 = 0 ;
			$sellTimeDiffRange2 = 0 ;	
			$sellTimeDiffRange3 = 0 ;
			$sellTimeDiffRange4 = 0 ;
			$sellTimeDiffRange5 = 0 ;
			$sellTimeDiffRange6 = 0 ;
			$sellTimeDiffRange7 = 0 ;

			$buyTimeDiffRange1 = 0 ;
			$buyTimeDiffRange2 = 0 ;	
			$buyTimeDiffRange3 = 0 ;
			$buyTimeDiffRange4 = 0 ;
			$buyTimeDiffRange5 = 0 ;
			$buyTimeDiffRange6 = 0 ;
			$buyTimeDiffRange7 = 0 ;

			$sumPLSllipageRange1 = 0 ;
			$sumPLSllipageRange2 = 0 ; 
			$sumPLSllipageRange3 = 0 ;
			$sumPLSllipageRange4 = 0 ;
			$sumPLSllipageRange5 = 0 ;

			if(count($result_1) > 0){
				foreach($result_1 as $value_1){  
					$total_investment_btc += $value_1['btc_investment'];
					$total_profit_btc += $value_1['total_btc_profit'];
					$total_buy_comission_btc += $value_1['buy_commission'];  
					$total_sell_comission_btc += $value_1['sell_commission'];  
					$buy_comission_BNB  += $value_1['buy_commision_qty'];
					$sell_comission_BNB += $value_1['sell_fee_respected_coin'];
					$total_gain_btc +=   $value_1['sold_btc_profit'];      
					echo"<br>oppertunity Id = ".$value_1['opportunity_id'];
					$opp['opportunity_id'] = $value_1['opportunity_id']; 
					$opp['mode'] = 'live';			
					$created_date = $value_1['created_date'];
					$avg_sold_manul += $value_1['avg_manul'];
					$sold +=  $value_1['sold'];
					$open_lth += $value_1['open_lth'];
					$avg_sold += $value_1['avg_sold'];
					$avg_open_lth += $value_1['avg_open_lth'];
					$picket_parent += $value_1['parents_picked'];
					$execuated_parent +=  $value_1['parents_executed'];
					$other_status += $value_1['other_status'];
					$cancelled += $value_1['cancelled'];        
					$new_error += $value_1['new_error'];
					$sum_all = $sold + $cancelled + $new_error;

					$sellTimeDiffRange1 += 	$value_1['sellTimeDiffRange1']; 	
					$sellTimeDiffRange2 +=	$value_1['sellTimeDiffRange2']; 	
					$sellTimeDiffRange3 +=	$value_1['sellTimeDiffRange3']; 	
					$sellTimeDiffRange4 +=	$value_1['sellTimeDiffRange4']; 	
					$sellTimeDiffRange5 +=	$value_1['sellTimeDiffRange5']; 	
					$sellTimeDiffRange6 +=	$value_1['sellTimeDiffRange6']; 	
					$sellTimeDiffRange7 +=	$value_1['sellTimeDiffRange7']; 
					
					$buyTimeDiffRange1 += 	$value_1['buySumTimeDelayRange1']; 	
					$buyTimeDiffRange2 +=	$value_1['buySumTimeDelayRange2']; 	
					$buyTimeDiffRange3 +=	$value_1['buySumTimeDelayRange3']; 	
					$buyTimeDiffRange4 +=	$value_1['buySumTimeDelayRange4']; 	
					$buyTimeDiffRange5 +=	$value_1['buySumTimeDelayRange5']; 	
					$buyTimeDiffRange6 +=	$value_1['buySumTimeDelayRange6']; 	
					$buyTimeDiffRange7 +=	$value_1['buySumTimeDelayRange7']; 

					$sumPLSllipageRange1 += $value_1['sumPLSllipageRange1']; 	
					$sumPLSllipageRange2 += $value_1['sumPLSllipageRange2']; 	 
					$sumPLSllipageRange3 +=	$value_1['sumPLSllipageRange3']; 	
					$sumPLSllipageRange4 += $value_1['sumPLSllipageRange4']; 	
					$sumPLSllipageRange5 += $value_1['sumPLSllipageRange5']; 	

				}
				$time = $created_date->toDateTime()->format("Y-m-d H:i:s");
				$created_date_mnth_year = date("Y-m",strtotime($time));
				$created_month_year =  $this->mongo_db->converToMongodttime($created_date_mnth_year);
				$current_month_year = $this->mongo_db->converToMongodttime($compaire_month);
			
				$final_open_avg = 0;
				$final_avg_manul_sold = 0;
				$final_sold_avg = 0;
				$final_open_avg = $avg_open_lth / count($result_1);
				$final_sold_avg = $avg_sold / count($result_1);
				$final_avg_manul_sold = $avg_sold_manul/ count($result_1);
				if($final_open_avg == '' || $final_open_avg == null || is_infinite($final_open_avg)){
					$final_open_avg = 0;
				}
				if($final_sold_avg == '' || $final_sold_avg == null || is_infinite($final_sold_avg)){
					$final_sold_avg = 0;
				}
				if($final_avg_manul_sold == '' || $final_avg_manul_sold == null || is_infinite($final_avg_manul_sold)){
					$final_avg_manul_sold = 0;
				}
				$new_array = array(
					'sold' 					=> 	$sold,
					'month'        			=> 	$month,
					'coin'					=> 	$coin,
					'mode' 					=> 	'live',
					'sellTimeDiffRange1'	=>	($sellTimeDiffRange1 / $sold) * 100,
					'sellTimeDiffRange2'	=>	($sellTimeDiffRange2 / $sold) * 100,	
					'sellTimeDiffRange3'	=>	($sellTimeDiffRange3 / $sold) * 100,
					'sellTimeDiffRange4'	=>	($sellTimeDiffRange4 / $sold) * 100,
					'sellTimeDiffRange5'	=>	($sellTimeDiffRange5 / $sold) * 100,
					'sellTimeDiffRange6'	=>	($sellTimeDiffRange6 / $sold) * 100,
					'sellTimeDiffRange7'	=>	($sellTimeDiffRange7 / $sold) * 100,

					'buyTimeDiffRange1'		=> 	($buyTimeDiffRange1 / ($sold + $open_lth)) * 100 , 		 	
					'buyTimeDiffRange2' 	=> 	($buyTimeDiffRange2 / ($sold + $open_lth)) * 100 ,	
					'buyTimeDiffRange3' 	=> 	($buyTimeDiffRange3 / ($sold + $open_lth)) * 100 , 	
					'buyTimeDiffRange4' 	=> 	($buyTimeDiffRange4 / ($sold + $open_lth)) * 100 , 	
					'buyTimeDiffRange5' 	=> 	($buyTimeDiffRange5 / ($sold + $open_lth)) * 100 , 	
					'buyTimeDiffRange6' 	=> 	($buyTimeDiffRange6 / ($sold + $open_lth)) * 100 ,  	
					'buyTimeDiffRange7' 	=> 	($buyTimeDiffRange7 / ($sold + $open_lth)) * 100 ,  

					'sumPLSllipageRange1'	=>	($sumPLSllipageRange1 / $sold) * 100,
					'sumPLSllipageRange2'	=>	($sumPLSllipageRange2 / $sold) * 100,	
					'sumPLSllipageRange3'	=>	($sumPLSllipageRange3 / $sold) * 100,
					'sumPLSllipageRange4'	=>	($sumPLSllipageRange4 / $sold) * 100,
					'sumPLSllipageRange5'	=>	($sumPLSllipageRange5 / $sold) * 100,

					'open_lth' 				=> 	$open_lth,
					'avg_sold'	    		=> 	$final_sold_avg,
					'avg_open_lth'     		=> 	$final_open_avg,
					'execuated_parent' 		=> 	$execuated_parent,
					'other_status' 	   		=> 	$other_status,
					'last_modified_time'	=> 	$current_time_date,
					'total_oppertunities'	=>	count($result_1),
					'avg_sold_manul'  		=> 	$final_avg_manul_sold,
					'total_investment' 		=> 	$total_investment_btc,
					'buy_comission_BNB'		=> 	$buy_comission_BNB,
					'buy_comission' 		=> 	$total_buy_comission_btc
				);

				if($execuated_parent == $sum_all && $created_month_year != $current_month_year && ($open_lth + $other_status) == 0){
					echo"<br>asasas";
					$new_array['total_profit'] =$total_profit_btc;
					$new_array['total_gain'] = $total_gain_btc; 
					$new_array['sell_comission'] =  $total_sell_comission_btc;
					$new_array['sell_comission_BNB'] = $sell_comission_BNB;
				}
				echo "<pre>";
				print_r($new_array);
				$search_find['month'] = $month;
				$search_find['coin']  = $coin;
				$search_find['mode'] = 'live';
				$upsert['upsert'] = true;
				$res = $custom->opportunity_logs_monthly_binance->updateOne($search_find, ['$set'=> $new_array], $upsert);
			}
			$coin_count++;
		}//end loop	
		echo "<br>total picked records = ".count($result_1);
	} //end cron

	// insertion of latest opportuniy into mothly log collection for binance
	public function insert_latest_oppertunity_into_log_collection_monthly_old_opportunity_scan(){
		$monthStart = date('m');
		$monthStart = $monthStart - 1 ;
		$custom = $this->mongo_db->customQuery(); 

		$coin_array  = ['EOSUSDT', 'LTCUSDT','XRPUSDT','NEOUSDT', 'QTUMUSDT','BTCUSDT', 'XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC'];

		for($i = 1; $i <= $monthStart; $i++){
			for($coin = 1; $coin < count($coin_array); $coin++){
				if($i <10){
				$month           =  date('Y-0'.$i);
				$compaire_month  =  date('Y-0'.$i);
				$startDate = date('Y-0'.$i.'-01 00:00:00');
				$endDate   = date('t', strtotime($startDate));
				$endDate = date('Y-0'.$i.'-'.$endDate .' 23:59:59');
				}else{
				$month           =  date('Y-'.$i);
				$compaire_month  =  date('Y-'.$i);
				$startDate = date('Y-'.$i.'-01 00:00:00');
				$endDate   = date('t', strtotime($startDate));
				$endDate = date('Y-'.$i.'-'.$endDate .' 23:59:59');	
				}
				echo "<br>start Time: ".$startDate;
				echo "<br>End Time: ".$endDate;
				$startDate = $this->mongo_db->converToMongodttime($startDate);
				$endDate   = $this->mongo_db->converToMongodttime(date($endDate));

				$lookUpQuery = [
					[
						'$match' => [
							'coin' 				 => 	$coin_array[$coin], 
							'mode' 				 => 	'live',
							'oppertunity_missed' => 	['$exists' => false ],
							'created_date' 		 => 	['$gte' => $startDate, '$lte'=>$endDate ],
							'level' 			 => 	array('$in'=>array('level_5', 'level_6', 'level_8', 'level_10','level_11','level_12','level_13', 'level_17', 'level_18'))
						]
					],

					[
						'$group' =>[
							'_id'  => '$coin',
							'total_investment_btc' 		=>  ['$sum' => '$btc_investment'],
							'total_profit_btc'	   		=>  ['$sum' => '$total_btc_profit'],
							'total_buy_comission_btc' 	=>  ['$sum' => '$buy_commission'],
							'total_sell_comission_btc' 	=>  ['$sum' => '$sell_commission'],
							'buy_comission_BNB'			=> 	['$sum' => '$buy_commision_qty'],
							'sell_comission_BNB'		=> 	['$sum' => '$sell_fee_respected_coin'],
							'total_gain_btc'			=>	['$sum' => '$sold_btc_profit'],
							'opportunity_id'			=> 	['$sum' => '$opportunity_id'],
							'sold'		 				=>  ['$sum' => '$sold'],
							'avg_sold_manul'	        =>  ['$sum' => '$avg_manul'],
							'open_lth'					=>  ['$sum' => '$open_lth'],
							'avg_sold'					=>	['$sum' => '$avg_sold'],
							'avg_open_lth'				=> 	['$sum' => '$avg_open_lth'],
							'picket_parent'				=>	['$sum' => '$parents_picked'],
							'execuated_parent'			=> 	['$sum' => '$parents_executed'],
							'other_status'				=> 	['$sum' => '$other_status'],
							'cancelled'                 =>  ['$sum' => '$cancelled'],
							'new_error'					=>	['$sum' => '$new_error'],
							'sellTimeDiffRange1'        =>  ['$sum' => '$sellTimeDiffRange1'],
							'sellTimeDiffRange2'		=>	['$sum' => '$sellTimeDiffRange2'],
							'sellTimeDiffRange3'		=>	['$sum' => '$sellTimeDiffRange3'],
							'sellTimeDiffRange4'		=>	['$sum' => '$sellTimeDiffRange4'],
							'sellTimeDiffRange5'		=>	['$sum' => '$sellTimeDiffRange5'],
							'sellTimeDiffRange6'		=>	['$sum' => '$sellTimeDiffRange6'],
							'sellTimeDiffRange7'		=>	['$sum' => '$sellTimeDiffRange7'],
							'buyTimeDiffRange1'			=> 	['$sum' => '$buySumTimeDelayRange1'],
							'buyTimeDiffRange2'			=>  ['$sum' => '$buySumTimeDelayRange2'],
							'buyTimeDiffRange3'			=>	['$sum'  => '$buySumTimeDelayRange3'],
							'buyTimeDiffRange4'			=>	['$sum' => '$buySumTimeDelayRange4'],
							'buyTimeDiffRange5'			=> 	['$sum' => '$buySumTimeDelayRange5'],
							'buyTimeDiffRange6'			=> 	['$sum' => '$buySumTimeDelayRange6'],
							'buyTimeDiffRange7'			=> 	['$sum' => '$buySumTimeDelayRange7'],
							'sumPLSllipageRange1'		=> 	['$sum' => '$sumPLSllipageRange1'],
							'sumPLSllipageRange2'		=>	['$sum' => '$sumPLSllipageRange2'],
							'sumPLSllipageRange3'		=>	['$sum' => '$sumPLSllipageRange3'],
							'sumPLSllipageRange4'		=>	['$sum' => '$sumPLSllipageRange4'],
							'sumPLSllipageRange5'		=>	['$sum' => '$sumPLSllipageRange5'],
							'count'						=>	['$sum' => 1],
						]
					],


					[
						'$project' => [
							'_id' => null,
							'final_open_avg'  		=>  ['$cond'=> [[ '$gt'=> [ '$avg_open_lth', 0 ]], ['$divide' => ['$avg_open_lth' , '$count']], 0 ]],
							'final_sold_avg'  		=>  ['$cond'=> [[ '$gt'=> [ '$avg_sold', 0 ]],['$divide' => ['$avg_sold' , '$count']], 0 ]],
							'final_avg_manul_sold'	=>	['$cond'=> [[ '$gt'=> [ '$avg_sold_manul', 0 ]],['$divide' => ['$avg_sold_manul' , '$count']], 0 ]],
							'sold' 					=> 	'$sold',
							'month'        			=> 	$month,
							'coin'					=> 	$coin_array[$coin],
							'mode' 					=> 	'live',
							'sum_all' 				=>	['$sum' => [ '$sold', '$cancelled' , '$new_error']],
							'created_date'			=>  '$created_date',
							'sellTimeDiffRange1'	=>	['$multiply' => [['$cond'=> [['$gt' => ['$sellTimeDiffRange1', 0]],['$divide'  => [ '$sellTimeDiffRange1', '$sold']], 0]] , 100]],
							'sellTimeDiffRange2'	=>	['$multiply' => [['$cond'=> [['$gt' => ['$sellTimeDiffRange2', 0]],['$divide'  => [ '$sellTimeDiffRange2', '$sold']], 0]] , 100]],	
							'sellTimeDiffRange3'	=>	['$multiply' => [['$cond'=> [['$gt' => ['$sellTimeDiffRange3', 0]],['$divide'  => [ '$sellTimeDiffRange3', '$sold']], 0]] , 100]],
							'sellTimeDiffRange4'	=>	['$multiply' => [['$cond'=> [['$gt' => ['$sellTimeDiffRange4', 0]],['$divide'  => [ '$sellTimeDiffRange4', '$sold']], 0]] , 100]],
							'sellTimeDiffRange5'	=>	['$multiply' => [['$cond'=> [['$gt' => ['$sellTimeDiffRange5', 0]],['$divide'  => [ '$sellTimeDiffRange5', '$sold']], 0]] , 100]],
							'sellTimeDiffRange6'	=>	['$multiply' => [['$cond'=> [['$gt' => ['$sellTimeDiffRange6', 0]],['$divide'  => [ '$sellTimeDiffRange6', '$sold']], 0]] , 100]],
							'sellTimeDiffRange7'	=>	['$multiply' => [['$cond'=> [['$gt' => ['$sellTimeDiffRange7', 0]],['$divide'  => ['$sellTimeDiffRange7' , '$sold']], 0]] , 100]],
							'buyTimeDiffRange1'		=>  ['$multiply' => [['$cond'=> [['$gt' => ['$buyTimeDiffRange1', 0]],['$divide' => [ '$buyTimeDiffRange1' , ['$sum' => [ '$sold' , '$open_lth']] ]], 0]] , 100]], 		 	
							'buyTimeDiffRange2'		=>  ['$multiply' => [['$cond'=> [['$gt' => ['$buyTimeDiffRange2', 0]],['$divide' => [ '$buyTimeDiffRange2' , ['$sum' => [ '$sold' , '$open_lth']] ]], 0]] , 100]],
							'buyTimeDiffRange3'		=>  ['$multiply' => [['$cond'=> [['$gt' => ['$buyTimeDiffRange3', 0]],['$divide' => [ '$buyTimeDiffRange3' , ['$sum' => [ '$sold' , '$open_lth']] ]], 0]] , 100]],
							'buyTimeDiffRange4'		=>  ['$multiply' => [['$cond'=> [['$gt' => ['$buyTimeDiffRange4', 0]],['$divide' => [ '$buyTimeDiffRange4' , ['$sum' => [ '$sold' , '$open_lth']] ]], 0]] , 100]],
							'buyTimeDiffRange5'		=>  ['$multiply' => [['$cond'=> [['$gt' => ['$buyTimeDiffRange5', 0]],['$divide' => [ '$buyTimeDiffRange5' , ['$sum' => [ '$sold' , '$open_lth']] ]], 0]] , 100]],
							'buyTimeDiffRange6'		=>  ['$multiply' => [['$cond'=> [['$gt' => ['$buyTimeDiffRange6', 0]],['$divide' => [ '$buyTimeDiffRange6' , ['$sum' => [ '$sold' , '$open_lth']] ]], 0]] , 100]],
							'buyTimeDiffRange7'		=>  ['$multiply' => [['$cond'=> [['$gt' => ['$buyTimeDiffRange7', 0]],['$divide' => [ '$buyTimeDiffRange7' , ['$sum' => [ '$sold' , '$open_lth']] ]], 0]] , 100]],
 							'sumPLSllipageRange1'	=> 	['$multiply' => [['$cond'=> [['$gt' => ['$sumPLSllipageRange1', 0]],['$divide' => ['$sumPLSllipageRange1', '$sold' ]], 0]], 100]],    
							'sumPLSllipageRange2'	=> 	['$multiply' => [['$cond'=> [['$gt' => ['$sumPLSllipageRange2', 0]],['$divide' => ['$sumPLSllipageRange2', '$sold' ]], 0]], 100]],
							'sumPLSllipageRange3'	=> 	['$multiply' => [['$cond'=> [['$gt' => ['$sumPLSllipageRange3', 0]],['$divide' => ['$sumPLSllipageRange3', '$sold' ]], 0]], 100]],
							'sumPLSllipageRange4'	=> 	['$multiply' => [['$cond'=> [['$gt' => ['$sumPLSllipageRange4', 0]],['$divide' => ['$sumPLSllipageRange4', '$sold' ]], 0]], 100]],
							'sumPLSllipageRange5'	=> 	['$multiply' => [['$cond'=> [['$gt' => ['$sumPLSllipageRange5', 0]],['$divide' => ['$sumPLSllipageRange5', '$sold' ]], 0]], 100]],
							'open_lth' 				=> 	'$open_lth',
							'avg_sold'	    		=> 	'$final_sold_avg',
							'avg_open_lth'     		=> 	'$final_open_avg',
							'execuated_parent' 		=> 	'$execuated_parent',
							'other_status' 	   		=> 	'$other_status',
							'last_modified_time'	=> 	'$current_time_date',
							'total_oppertunities'	=>	'$count',
							'avg_sold_manul'  		=> 	'$final_avg_manul_sold',
							'total_investment' 		=> 	'$total_investment_btc',
							'buy_comission_BNB'		=> 	'$buy_comission_BNB',
							'buy_comission' 		=> 	'$total_buy_comission_btc',
							'total_profit' 			=>  '$total_profit_btc',
							'total_gain'			=>	'$total_gain_btc',
							'sell_comission'        =>  '$total_sell_comission_btc',
							'sell_comission_BNB'    =>  '$sell_comission_BNB',
						]
					],

					[
						'$sort' => ['created_date'=> -1]
					]
				];

				$resps = $custom->opportunity_logs_binance->aggregate($lookUpQuery);
				$result_1 = iterator_to_array($resps);
				if(count($result_1) > 0 ){
					$updateArray = [

						'final_open_avg'  		=>  $result_1[0]['final_open_avg'],
						'final_sold_avg'  		=>  $result_1[0]['final_sold_avg'],
						'final_avg_manul_sold'	=>	$result_1[0]['final_avg_manul_sold'],
						'sold' 					=> 	$result_1[0]['sold'],
						'month'        			=> 	$result_1[0]['month'],
						'coin'					=> 	$result_1[0]['coin'],
						'mode' 					=> 	$result_1[0]['mode'],
						'sum_all' 				=>	$result_1[0]['sum_all'],
						'created_date'			=>  $result_1[0]['created_date'],
						'sellTimeDiffRange1'	=>	$result_1[0]['sellTimeDiffRange1'],
						'sellTimeDiffRange2'	=>	$result_1[0]['sellTimeDiffRange2'],	
						'sellTimeDiffRange3'	=>	$result_1[0]['sellTimeDiffRange3'],
						'sellTimeDiffRange4'	=>	$result_1[0]['sellTimeDiffRange4'],
						'sellTimeDiffRange5'	=>	$result_1[0]['sellTimeDiffRange5'],
						'sellTimeDiffRange6'	=>	$result_1[0]['sellTimeDiffRange6'],
						'sellTimeDiffRange7'	=>	$result_1[0]['sellTimeDiffRange7'],
						'buyTimeDiffRange1'		=>  $result_1[0]['buyTimeDiffRange1'], 		 	
						'buyTimeDiffRange2'		=>  $result_1[0]['buyTimeDiffRange2'],
						'buyTimeDiffRange3'		=>  $result_1[0]['buyTimeDiffRange3'],
						'buyTimeDiffRange4'		=>  $result_1[0]['buyTimeDiffRange4'],
						'buyTimeDiffRange5'		=>  $result_1[0]['buyTimeDiffRange5'],
						'buyTimeDiffRange6'		=>  $result_1[0]['buyTimeDiffRange6'],
						'buyTimeDiffRange7'		=>  $result_1[0]['buyTimeDiffRange7'],
						'sumPLSllipageRange1'	=> 	$result_1[0]['sumPLSllipageRange1'],    
						'sumPLSllipageRange2'	=> 	$result_1[0]['sumPLSllipageRange2'],
						'sumPLSllipageRange3'	=> 	$result_1[0]['sumPLSllipageRange3'],
						'sumPLSllipageRange4'	=> 	$result_1[0]['sumPLSllipageRange4'],
						'sumPLSllipageRange5'	=> 	$result_1[0]['sumPLSllipageRange5'],
						'open_lth' 				=> 	$result_1[0]['open_lth'],
						'avg_sold'	    		=> 	$result_1[0]['final_sold_avg'],
						'avg_open_lth'     		=> 	$result_1[0]['final_open_avg'],
						'execuated_parent' 		=> 	$result_1[0]['execuated_parent'],
						'other_status' 	   		=> 	$result_1[0]['other_status'],
						'last_modified_time'	=> 	$result_1[0]['last_modified_time'],
						'total_oppertunities'	=>	$result_1[0]['total_oppertunities'],
						'avg_sold_manul'  		=> 	$result_1[0]['final_avg_manul_sold'],
						'total_investment' 		=> 	$result_1[0]['total_investment'],
						'buy_comission_BNB'		=> 	$result_1[0]['buy_comission_BNB'],
						'buy_comission' 		=> 	$result_1[0]['buy_comission'],
						'total_profit' 			=>  $result_1[0]['total_profit'],
						'total_gain'			=>	$result_1[0]['total_gain'],
						'sell_comission'        =>  $result_1[0]['sell_comission'],
						'sell_comission_BNB'    =>  $result_1[0]['sell_comission_BNB'],
						'last_modified_time'	=> 	$this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
					];

					$search_find['month'] = $month;
					$search_find['coin']  = $coin_array[$coin];
					$search_find['mode']  = 'live';
					echo "<pre>";print_r($search_find);
					$res = $custom->opportunity_logs_monthly_binance->updateOne($search_find, ['$set'=> $updateArray]);
				}
			}
		}
		
		echo "<br>All Done!!!";
	} //end cron


	/////////////////////////////////////////////////////////////////////////////
	///////////////         ASIM CRONE KRAKEN MONTHLY        ////////////////////
	/////////////////////////////////////////////////////////////////////////////

	// insertion of latest opportunity into monthly collection log of kraken
	public function insert_latest_oppertunity_into_log_collection_monthly_kraken(){

		//Save last Cron Executioon
		$this->last_cron_execution_time('Kraken live opportunity monthly', '55m', 'run kraken live opportunity avg calculation monthly (55 * * * *)', 'reports');

		$compaire_month = date('Y-m');
		$month = date('Y-m');
		$start_date_time =  date('Y-m-01 00:00:00');
		$time_date =  $this->mongo_db->converToMongodttime($start_date_time);

		$end = date('Y-m-d 23:59:59');
		$enddate = $this->mongo_db->converToMongodttime($end);

		$current_date = date('Y-m-d H:i:s');
		$current_time_date =  $this->mongo_db->converToMongodttime($current_date);

		$coin_array_all = $this->mod_coins->get_all_coins_kraken();
		$coin_count = 0;
		foreach($coin_array_all as $value){
			echo "<br>count coin = ".$coin_count;
			$custom = $this->mongo_db->customQuery(); 
			$condtn = array('sort'=>array('created_date'=> -1));
			$coin = $coin_array_all[$coin_count]['symbol'];
			echo"<br>coin name= ".$coin_array_all[$coin_count]['symbol'];
			$where['coin'] = $coin; 
			$where['mode'] = 'live';
			$where['oppertunity_missed'] = array('$exists'=>false);
			$where['created_date'] = array('$gte' => $time_date, '$lte'=>$enddate);
			$where['level'] = array('$in'=>array('level_5', 'level_6', 'level_8', 'level_10','level_11','level_12','level_13', 'level_17', 'level_18'));

			$resps = $custom->opportunity_logs_kraken->find($where, $condtn);
			$result_1 = iterator_to_array($resps);  
			echo "<br>count= ".count($result_1);
			
			$sold = 0;
			$open_lth =0;
			$avg_sold_manul = 0;
			$avg_sold = 0;
			$avg_open_lth =0;
			$picket_parent = 0;
			$execuated_parent = 0;
			$other_status = 0;
			$cancelled = 0;        
			$new_error = 0;
			$created_date ='';
			$sum_all = 0;
			$total_investment_btc = 0;						
			$total_profit_btc = 0;
			$total_buy_comission_btc = 0;
			$total_sell_comission_btc = 0;
			$buy_comission_BNB =0;
			$sell_comission_BNB =0;
			$total_gain_btc = 0;

			$sellTimeDiffRange1 = 0 ;
			$sellTimeDiffRange2 = 0 ;	
			$sellTimeDiffRange3 = 0 ;
			$sellTimeDiffRange4 = 0 ;
			$sellTimeDiffRange5 = 0 ;
			$sellTimeDiffRange6 = 0 ;
			$sellTimeDiffRange7 = 0 ;

			$buyTimeDiffRange1 = 0 ;
			$buyTimeDiffRange2 = 0 ;	
			$buyTimeDiffRange3 = 0 ;
			$buyTimeDiffRange4 = 0 ;
			$buyTimeDiffRange5 = 0 ;
			$buyTimeDiffRange6 = 0 ;
			$buyTimeDiffRange7 = 0 ;

			$sumPLSllipageRange1 = 0 ;
			$sumPLSllipageRange2 = 0 ; 
			$sumPLSllipageRange3 = 0 ;
			$sumPLSllipageRange4 = 0 ;
			$sumPLSllipageRange5 = 0 ;

			if(count($result_1) > 0){
				foreach($result_1 as $value_1){  
					$total_investment_btc += $value_1['btc_investment'];
					$total_profit_btc += $value_1['total_btc_profit'];
					$total_buy_comission_btc += $value_1['buy_commission'];  
					$total_sell_comission_btc += $value_1['sell_commission'];  
					$buy_comission_BNB  += $value_1['buy_commision_qty'];
					$sell_comission_BNB += $value_1['sell_fee_respected_coin'];
					$total_gain_btc +=   $value_1['sold_btc_profit'];      
					echo"<br>oppertunity Id = ".$value_1['opportunity_id'];
					$opp['opportunity_id'] = $value_1['opportunity_id']; 
					$opp['mode'] = 'live';			
					$created_date = $value_1['created_date'];
					$avg_sold_manul += $value_1['avg_manul'];
					$sold +=  $value_1['sold'];
					$open_lth += $value_1['open_lth'];
					$avg_sold += $value_1['avg_sold'];
					$avg_open_lth += $value_1['avg_open_lth'];
					$picket_parent += $value_1['parents_picked'];
					$execuated_parent +=  $value_1['parents_executed'];
					$other_status += $value_1['other_status'];
					$cancelled += $value_1['cancelled'];        
					$new_error += $value_1['new_error'];
					$sum_all = $sold + $cancelled + $new_error;


					$sellTimeDiffRange1 += 	$value_1['sellTimeDiffRange1']; 	
					$sellTimeDiffRange2 +=	$value_1['sellTimeDiffRange2']; 	
					$sellTimeDiffRange3 +=	$value_1['sellTimeDiffRange3']; 	
					$sellTimeDiffRange4 +=	$value_1['sellTimeDiffRange4']; 	
					$sellTimeDiffRange5 +=	$value_1['sellTimeDiffRange5']; 	
					$sellTimeDiffRange6 +=	$value_1['sellTimeDiffRange6']; 	
					$sellTimeDiffRange7 +=	$value_1['sellTimeDiffRange7']; 

					$buyTimeDiffRange1 += 	$value_1['buySumTimeDelayRange1']; 	
					$buyTimeDiffRange2 +=	$value_1['buySumTimeDelayRange2']; 	
					$buyTimeDiffRange3 +=	$value_1['buySumTimeDelayRange3']; 	
					$buyTimeDiffRange4 +=	$value_1['buySumTimeDelayRange4']; 	
					$buyTimeDiffRange5 +=	$value_1['buySumTimeDelayRange5']; 	
					$buyTimeDiffRange6 +=	$value_1['buySumTimeDelayRange6']; 	
					$buyTimeDiffRange7 +=	$value_1['buySumTimeDelayRange7'];

					$sumPLSllipageRange1 += $value_1['sumPLSllipageRange1']; 	
					$sumPLSllipageRange2 += $value_1['sumPLSllipageRange2']; 	 
					$sumPLSllipageRange3 +=	$value_1['sumPLSllipageRange3']; 	
					$sumPLSllipageRange4 += $value_1['sumPLSllipageRange4']; 	
					$sumPLSllipageRange5 += $value_1['sumPLSllipageRange5']; 	


				}
				$time = $created_date->toDateTime()->format("Y-m-d H:i:s");
				$created_date_mnth_year = date("Y-m",strtotime($time));
				$created_month_year =  $this->mongo_db->converToMongodttime($created_date_mnth_year);
				$current_month_year = $this->mongo_db->converToMongodttime($compaire_month);
				
				echo "<br>datetime month year = ". date("Y-m",strtotime($time));
				echo "<br>execuated =".$execuated_parent;
				echo "<br>Sum of all = ".$sum_all;
				echo"<br>open lth =".$open_lth;
				echo"<br> if check value = ".$created_month_year."!=".$current_month_year;
				
				$final_open_avg = 0;
				$final_avg_manul_sold = 0;
				$final_sold_avg = 0;
				$final_open_avg = $avg_open_lth / count($result_1);
				$final_sold_avg = $avg_sold / count($result_1);
				$final_avg_manul_sold = $avg_sold_manul/ count($result_1);
				if($final_open_avg == '' || $final_open_avg == null || is_infinite($final_open_avg)){
					$final_open_avg = 0;
				}
				if($final_sold_avg == '' || $final_sold_avg == null || is_infinite($final_sold_avg)){
					$final_sold_avg = 0;
				}
				if($final_avg_manul_sold == '' || $final_avg_manul_sold == null || is_infinite($final_avg_manul_sold)){
					$final_avg_manul_sold = 0;
				}
				$new_array = array(
					'sold' 				=> $sold,
					'month'        		=> $month,
					'coin'				=> $coin,
					'mode' 				=> 'live',
					'open_lth' 			=> $open_lth,
					'avg_sold'	    	=> $final_sold_avg,
					'avg_open_lth'     	=> $final_open_avg,
					'execuated_parent' 	=> $execuated_parent,
					'other_status' 	   	=> $other_status,
					'last_modified_time'=> $current_time_date,

					'sellTimeDiffRange1'	=>	($sellTimeDiffRange1 / $sold) * 100,
					'sellTimeDiffRange2'	=>	($sellTimeDiffRange2 / $sold) * 100,	
					'sellTimeDiffRange3'	=>	($sellTimeDiffRange3 / $sold) * 100,
					'sellTimeDiffRange4'	=>	($sellTimeDiffRange4 / $sold) * 100,
					'sellTimeDiffRange5'	=>	($sellTimeDiffRange5 / $sold) * 100,
					'sellTimeDiffRange6'	=>	($sellTimeDiffRange6 / $sold) * 100,
					'sellTimeDiffRange7'	=>	($sellTimeDiffRange7 / $sold) * 100,

					'buyTimeDiffRange1'		=> 	($buyTimeDiffRange1 / ($sold + $open_lth)) * 100 , 		 	
					'buyTimeDiffRange2' 	=> 	($buyTimeDiffRange2 / ($sold + $open_lth)) * 100 ,	
					'buyTimeDiffRange3' 	=> 	($buyTimeDiffRange3 / ($sold + $open_lth)) * 100 , 	
					'buyTimeDiffRange4' 	=> 	($buyTimeDiffRange4 / ($sold + $open_lth)) * 100 , 	
					'buyTimeDiffRange5' 	=> 	($buyTimeDiffRange5 / ($sold + $open_lth)) * 100 , 	
					'buyTimeDiffRange6' 	=> 	($buyTimeDiffRange6 / ($sold + $open_lth)) * 100 ,  	
					'buyTimeDiffRange7' 	=> 	($buyTimeDiffRange7 / ($sold + $open_lth)) * 100 ,  

					'sumPLSllipageRange1'	=>	($sumPLSllipageRange1 / $sold) * 100,
					'sumPLSllipageRange2'	=>	($sumPLSllipageRange2 / $sold) * 100,	
					'sumPLSllipageRange3'	=>	($sumPLSllipageRange3 / $sold) * 100,
					'sumPLSllipageRange4'	=>	($sumPLSllipageRange4 / $sold) * 100,
					'sumPLSllipageRange5'	=>	($sumPLSllipageRange5 / $sold) * 100,

					'total_oppertunities'=>count($result_1),
					'avg_sold_manul'  	=> $final_avg_manul_sold,
					'total_investment' 	=> $total_investment_btc,
					'buy_comission_BNB'=> $buy_comission_BNB,
					'buy_comission' 	=> $total_buy_comission_btc
				);
				if($execuated_parent == $sum_all && $created_month_year != $current_month_year && ($open_lth + $other_status) == 0){
					echo"<br>asasas";
					$new_array['total_profit'] =$total_profit_btc;
					$new_array['total_gain'] = $total_gain_btc; 
					$new_array['sell_comission'] =  $total_sell_comission_btc;
					$new_array['sell_comission_BNB'] = $sell_comission_BNB;
				}
				echo "<pre>";
				print_r($new_array);
				$search_find['month'] = $month;
				$search_find['coin']  = $coin;
				$search_find['mode'] = 'live';
				$upsert['upsert'] = true;
				$res = $custom->opportunity_logs_monthly_kraken->updateOne($search_find, ['$set'=> $new_array], $upsert);
			}
			$coin_count++;
			echo "<br>total picked records = ".count($result_1);
			
		}// end coin loop			
	} //end cron

	// insertion of lastest opportuniy into monthly collection log for kraken
	public function insert_latest_oppertunity_into_log_collection_monthly_kraken_old_opportunity_scan(){
		$monthStart = date('m');
		print_r($monthStart);
		$monthStart = $monthStart - 1 ;
		$custom = $this->mongo_db->customQuery(); 

		$coin_array  = ['EOSUSDT','LTCBTC','LTCUSDT','XRPUSDT','NEOUSDT', 'QTUMUSDT','BTCUSDT', 'XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC'];
		for($i = 1; $i <= $monthStart; $i++){
			echo $i.' month';

			for($coin = 1; $coin < count($coin_array); $coin++){
				if($i <10){

				$month           =  date('Y-0'.$i);
				$compaire_month  =  date('Y-0'.$i);
				$startDate = date('Y-0'.$i.'-01 00:00:00');
				$endDate   = date('t', strtotime($startDate));
				$endDate = date('Y-0'.$i.'-'.$endDate .' 23:59:59');
				}else{

				$month           =  date('Y-'.$i);
				$compaire_month  =  date('Y-'.$i);
				$startDate = date('Y-'.$i.'-01 00:00:00');
				$endDate   = date('t', strtotime($startDate));
				$endDate = date('Y-'.$i.'-'.$endDate .' 23:59:59');	
				}
				
				echo "<br>start Time: ".$startDate;
				echo "<br>End Time: ".$endDate;
				$startDate = $this->mongo_db->converToMongodttime($startDate);
				$endDate   = $this->mongo_db->converToMongodttime(date($endDate));

				$lookUpQuery = [
					[
						'$match' => [
							'coin' 				 => 	$coin_array[$coin], 
							'mode' 				 => 	'live',
							'oppertunity_missed' => 	['$exists' => false ],
							'created_date' 		 => 	['$gte' => $startDate, '$lte'=>$endDate ],
							'level' 			 => 	array('$in'=>array('level_5', 'level_6', 'level_8', 'level_10','level_11','level_12','level_13', 'level_17', 'level_18'))
						]
					],

					[
						'$group' =>[
							'_id'  => '$coin',
							'total_investment_btc' 		=>  ['$sum' => '$btc_investment'],
							'total_profit_btc'	   		=>  ['$sum' => '$total_btc_profit'],
							'total_buy_comission_btc' 	=>  ['$sum' => '$buy_commission'],
							'total_sell_comission_btc' 	=>  ['$sum' => '$sell_commission'],
							'buy_comission_BNB'			=> 	['$sum' => '$buy_commision_qty'],
							'sell_comission_BNB'		=> 	['$sum' => '$sell_fee_respected_coin'],
							'total_gain_btc'			=>	['$sum' => '$sold_btc_profit'],
							'opportunity_id'			=> 	['$sum' => '$opportunity_id'],
							'sold'		 				=>  ['$sum' => '$sold'],
							'avg_sold_manul'	        =>  ['$sum' => '$avg_manul'],
							'open_lth'					=>  ['$sum' => '$open_lth'],
							'avg_sold'					=>	['$sum' => '$avg_sold'],
							'avg_open_lth'				=> 	['$sum' => '$avg_open_lth'],
							'picket_parent'				=>	['$sum' => '$parents_picked'],
							'execuated_parent'			=> 	['$sum' => '$parents_executed'],
							'other_status'				=> 	['$sum' => '$other_status'],
							'cancelled'                 =>  ['$sum' => '$cancelled'],
							'new_error'					=>	['$sum' => '$new_error'],
							'sellTimeDiffRange1'        =>  ['$sum' => '$sellTimeDiffRange1'],
							'sellTimeDiffRange2'		=>	['$sum' => '$sellTimeDiffRange2'],
							'sellTimeDiffRange3'		=>	['$sum' => '$sellTimeDiffRange3'],
							'sellTimeDiffRange4'		=>	['$sum' => '$sellTimeDiffRange4'],
							'sellTimeDiffRange5'		=>	['$sum' => '$sellTimeDiffRange5'],
							'sellTimeDiffRange6'		=>	['$sum' => '$sellTimeDiffRange6'],
							'sellTimeDiffRange7'		=>	['$sum' => '$sellTimeDiffRange7'],
							'buyTimeDiffRange1'			=> 	['$sum' => '$buySumTimeDelayRange1'],
							'buyTimeDiffRange2'			=>  ['$sum' => '$buySumTimeDelayRange2'],
							'buyTimeDiffRange3'			=>	['$sum'  => '$buySumTimeDelayRange3'],
							'buyTimeDiffRange4'			=>	['$sum' => '$buySumTimeDelayRange4'],
							'buyTimeDiffRange5'			=> 	['$sum' => '$buySumTimeDelayRange5'],
							'buyTimeDiffRange6'			=> 	['$sum' => '$buySumTimeDelayRange6'],
							'buyTimeDiffRange7'			=> 	['$sum' => '$buySumTimeDelayRange7'],
							'sumPLSllipageRange1'		=> 	['$sum' => '$sumPLSllipageRange1'],
							'sumPLSllipageRange2'		=>	['$sum' => '$sumPLSllipageRange2'],
							'sumPLSllipageRange3'		=>	['$sum' => '$sumPLSllipageRange3'],
							'sumPLSllipageRange4'		=>	['$sum' => '$sumPLSllipageRange4'],
							'sumPLSllipageRange5'		=>	['$sum' => '$sumPLSllipageRange5'],
							'count'						=>	['$sum' => 1],
						]
					],


					[
						'$project' => [
							'_id' => null,
							'final_open_avg'  		=>  ['$cond'=> [[ '$gt'=> [ '$avg_open_lth', 0 ]], ['$divide' => ['$avg_open_lth' , '$count']], 0 ]],
							'final_sold_avg'  		=>  ['$cond'=> [[ '$gt'=> [ '$avg_sold', 0 ]],['$divide' => ['$avg_sold' , '$count']], 0 ]],
							'final_avg_manul_sold'	=>	['$cond'=> [[ '$gt'=> [ '$avg_sold_manul', 0 ]],['$divide' => ['$avg_sold_manul' , '$count']], 0 ]],
							'sold' 					=> 	'$sold',
							'month'        			=> 	$month,
							'coin'					=> 	$coin_array[$coin],
							'mode' 					=> 	'live',
							'sum_all' 				=>	['$sum' => [ '$sold', '$cancelled' , '$new_error']],
							'created_date'			=>  '$created_date',
							'sellTimeDiffRange1'	=>	['$multiply' => [['$cond'=> [['$gt' => ['$sellTimeDiffRange1', 0]],['$divide'  => [ '$sellTimeDiffRange1', '$sold']], 0]] , 100]],
							'sellTimeDiffRange2'	=>	['$multiply' => [['$cond'=> [['$gt' => ['$sellTimeDiffRange2', 0]],['$divide'  => [ '$sellTimeDiffRange2', '$sold']], 0]] , 100]],	
							'sellTimeDiffRange3'	=>	['$multiply' => [['$cond'=> [['$gt' => ['$sellTimeDiffRange3', 0]],['$divide'  => [ '$sellTimeDiffRange3', '$sold']], 0]] , 100]],
							'sellTimeDiffRange4'	=>	['$multiply' => [['$cond'=> [['$gt' => ['$sellTimeDiffRange4', 0]],['$divide'  => [ '$sellTimeDiffRange4', '$sold']], 0]] , 100]],
							'sellTimeDiffRange5'	=>	['$multiply' => [['$cond'=> [['$gt' => ['$sellTimeDiffRange5', 0]],['$divide'  => [ '$sellTimeDiffRange5', '$sold']], 0]] , 100]],
							'sellTimeDiffRange6'	=>	['$multiply' => [['$cond'=> [['$gt' => ['$sellTimeDiffRange6', 0]],['$divide'  => [ '$sellTimeDiffRange6', '$sold']], 0]] , 100]],
							'sellTimeDiffRange7'	=>	['$multiply' => [['$cond'=> [['$gt' => ['$sellTimeDiffRange7', 0]],['$divide'  => ['$sellTimeDiffRange7' , '$sold']], 0]] , 100]],
							'buyTimeDiffRange1'		=>  ['$multiply' => [['$cond'=> [['$gt' => ['$buyTimeDiffRange1', 0]],['$divide' => [ '$buyTimeDiffRange1' , ['$sum' => [ '$sold' , '$open_lth']] ]], 0]] , 100]], 		 	
							'buyTimeDiffRange2'		=>  ['$multiply' => [['$cond'=> [['$gt' => ['$buyTimeDiffRange2', 0]],['$divide' => [ '$buyTimeDiffRange2' , ['$sum' => [ '$sold' , '$open_lth']] ]], 0]] , 100]],
							'buyTimeDiffRange3'		=>  ['$multiply' => [['$cond'=> [['$gt' => ['$buyTimeDiffRange3', 0]],['$divide' => [ '$buyTimeDiffRange3' , ['$sum' => [ '$sold' , '$open_lth']] ]], 0]] , 100]],
							'buyTimeDiffRange4'		=>  ['$multiply' => [['$cond'=> [['$gt' => ['$buyTimeDiffRange4', 0]],['$divide' => [ '$buyTimeDiffRange4' , ['$sum' => [ '$sold' , '$open_lth']] ]], 0]] , 100]],
							'buyTimeDiffRange5'		=>  ['$multiply' => [['$cond'=> [['$gt' => ['$buyTimeDiffRange5', 0]],['$divide' => [ '$buyTimeDiffRange5' , ['$sum' => [ '$sold' , '$open_lth']] ]], 0]] , 100]],
							'buyTimeDiffRange6'		=>  ['$multiply' => [['$cond'=> [['$gt' => ['$buyTimeDiffRange6', 0]],['$divide' => [ '$buyTimeDiffRange6' , ['$sum' => [ '$sold' , '$open_lth']] ]], 0]] , 100]],
							'buyTimeDiffRange7'		=>  ['$multiply' => [['$cond'=> [['$gt' => ['$buyTimeDiffRange7', 0]],['$divide' => [ '$buyTimeDiffRange7' , ['$sum' => [ '$sold' , '$open_lth']] ]], 0]] , 100]],
 							'sumPLSllipageRange1'	=> 	['$multiply' => [['$cond'=> [['$gt' => ['$sumPLSllipageRange1', 0]],['$divide' => ['$sumPLSllipageRange1', '$sold' ]], 0]], 100]],    
							'sumPLSllipageRange2'	=> 	['$multiply' => [['$cond'=> [['$gt' => ['$sumPLSllipageRange2', 0]],['$divide' => ['$sumPLSllipageRange2', '$sold' ]], 0]], 100]],
							'sumPLSllipageRange3'	=> 	['$multiply' => [['$cond'=> [['$gt' => ['$sumPLSllipageRange3', 0]],['$divide' => ['$sumPLSllipageRange3', '$sold' ]], 0]], 100]],
							'sumPLSllipageRange4'	=> 	['$multiply' => [['$cond'=> [['$gt' => ['$sumPLSllipageRange4', 0]],['$divide' => ['$sumPLSllipageRange4', '$sold' ]], 0]], 100]],
							'sumPLSllipageRange5'	=> 	['$multiply' => [['$cond'=> [['$gt' => ['$sumPLSllipageRange5', 0]],['$divide' => ['$sumPLSllipageRange5', '$sold' ]], 0]], 100]],
							'open_lth' 				=> 	'$open_lth',
							'avg_sold'	    		=> 	'$final_sold_avg',
							'avg_open_lth'     		=> 	'$final_open_avg',
							'execuated_parent' 		=> 	'$execuated_parent',
							'other_status' 	   		=> 	'$other_status',
							'last_modified_time'	=> 	'$current_time_date',
							'total_oppertunities'	=>	'$count',
							'avg_sold_manul'  		=> 	'$final_avg_manul_sold',
							'total_investment' 		=> 	'$total_investment_btc',
							'buy_comission_BNB'		=> 	'$buy_comission_BNB',
							'buy_comission' 		=> 	'$total_buy_comission_btc',
							'total_profit' 			=>  '$total_profit_btc',
							'total_gain'			=>	'$total_gain_btc',
							'sell_comission'        =>  '$total_sell_comission_btc',
							'sell_comission_BNB'    =>  '$sell_comission_BNB',
						]
					],

					[
						'$sort' => ['created_date'=> -1]
					]
				];

				$resps = $custom->opportunity_logs_kraken->aggregate($lookUpQuery);
				$result_1 = iterator_to_array($resps);
				//echo '<pre>';print_r(json_encode($lookUpQuery));
				echo '<pre> logs data for oppertunity'; print_r($result_1);
				if(count($result_1) > 0 ){
					$updateArray = [
						'final_open_avg'  		=>  $result_1[0]['final_open_avg'],
						'final_sold_avg'  		=>  $result_1[0]['final_sold_avg'],
						'final_avg_manul_sold'	=>	$result_1[0]['final_avg_manul_sold'],
						'sold' 					=> 	$result_1[0]['sold'],
						'month'        			=> 	$result_1[0]['month'],
						'coin'					=> 	$result_1[0]['coin'],
						'mode' 					=> 	$result_1[0]['mode'],
						'sum_all' 				=>	$result_1[0]['sum_all'],
						'created_date'			=>  $result_1[0]['created_date'],
						'sellTimeDiffRange1'	=>	$result_1[0]['sellTimeDiffRange1'],
						'sellTimeDiffRange2'	=>	$result_1[0]['sellTimeDiffRange2'],	
						'sellTimeDiffRange3'	=>	$result_1[0]['sellTimeDiffRange3'],
						'sellTimeDiffRange4'	=>	$result_1[0]['sellTimeDiffRange4'],
						'sellTimeDiffRange5'	=>	$result_1[0]['sellTimeDiffRange5'],
						'sellTimeDiffRange6'	=>	$result_1[0]['sellTimeDiffRange6'],
						'sellTimeDiffRange7'	=>	$result_1[0]['sellTimeDiffRange7'],
						'buyTimeDiffRange1'		=>  $result_1[0]['buyTimeDiffRange1'], 		 	
						'buyTimeDiffRange2'		=>  $result_1[0]['buyTimeDiffRange2'],
						'buyTimeDiffRange3'		=>  $result_1[0]['buyTimeDiffRange3'],
						'buyTimeDiffRange4'		=>  $result_1[0]['buyTimeDiffRange4'],
						'buyTimeDiffRange5'		=>  $result_1[0]['buyTimeDiffRange5'],
						'buyTimeDiffRange6'		=>  $result_1[0]['buyTimeDiffRange6'],
						'buyTimeDiffRange7'		=>  $result_1[0]['buyTimeDiffRange7'],
						'sumPLSllipageRange1'	=> 	$result_1[0]['sumPLSllipageRange1'],    
						'sumPLSllipageRange2'	=> 	$result_1[0]['sumPLSllipageRange2'],
						'sumPLSllipageRange3'	=> 	$result_1[0]['sumPLSllipageRange3'],
						'sumPLSllipageRange4'	=> 	$result_1[0]['sumPLSllipageRange4'],
						'sumPLSllipageRange5'	=> 	$result_1[0]['sumPLSllipageRange5'],
						'open_lth' 				=> 	$result_1[0]['open_lth'],
						'avg_sold'	    		=> 	$result_1[0]['final_sold_avg'],
						'avg_open_lth'     		=> 	$result_1[0]['final_open_avg'],
						'execuated_parent' 		=> 	$result_1[0]['execuated_parent'],
						'other_status' 	   		=> 	$result_1[0]['other_status'],
						'last_modified_time'	=> 	$result_1[0]['last_modified_time'],
						'total_oppertunities'	=>	$result_1[0]['total_oppertunities'],
						'avg_sold_manul'  		=> 	$result_1[0]['final_avg_manul_sold'],
						'total_investment' 		=> 	$result_1[0]['total_investment'],
						'buy_comission_BNB'		=> 	$result_1[0]['buy_comission_BNB'],
						'buy_comission' 		=> 	$result_1[0]['buy_comission'],
						'total_profit' 			=>  $result_1[0]['total_profit'],
						'total_gain'			=>	$result_1[0]['total_gain'],
						'sell_comission'        =>  $result_1[0]['sell_comission'],
						'sell_comission_BNB'    =>  $result_1[0]['sell_comission_BNB'],
						'last_modified_time'	=> 	$this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
					];

					$search_find['month'] = $month;
					$search_find['coin']  = $coin_array[$coin];
					$search_find['mode']  = 'live';
					echo "<pre> heloooo";print_r($updateArray);
					$res = $custom->opportunity_logs_monthly_kraken->updateOne($search_find, ['$set'=> $updateArray]);
				}
			}
		}
		
		echo "<br>All Done!!!";		
	} //end cron

	/////////////////////////////////////////////////////////////////////////////
	///////////////          ASIM CRONE BSM MONTHLY            //////////////////
	/////////////////////////////////////////////////////////////////////////////
	// inserting latest opportuniy into bam monthly log collection.
	public function insert_latest_oppertunity_into_log_collection_monthly_bam(){

		//Save last Cron Executioon
		$this->last_cron_execution_time('Bam live opportunity monthly', '55m', 'run Bam live opportunity avg calculation monthly (55 * * * *)', 'reports');

		$compaire_month = date('Y-m');
		$month = date('Y-m');
		$start_date_time =  date('Y-m-01 00:00:00');
		$time_date =  $this->mongo_db->converToMongodttime($start_date_time);

		$end = date('Y-m-d 23:59:59');
		$enddate = $this->mongo_db->converToMongodttime($end);

		$current_date = date('Y-m-d H:i:s');
		$current_time_date =  $this->mongo_db->converToMongodttime($current_date);
		$coin_array_all = $this->mod_coins->get_all_coins_bam();
		$coin_count = 0;
		foreach($coin_array_all as $value){
			echo"<br>coin count = ".$coin_count;
			$custom = $this->mongo_db->customQuery(); 
			$condtn = array('sort'=>array('created_date'=> -1));
			$coin = $coin_array_all[$coin_count]['symbol'];
			$where['coin'] = $coin; 
			$where['mode'] = 'live';
			$where['oppertunity_missed'] = array('$exists'=>false);
			$where['created_date'] = array('$gte' => $time_date, '$lte'=>$enddate);
			$where['level'] = array('$in'=>array('level_5','level_6', 'level_8', 'level_10','level_11','level_12','level_13', 'level_17', 'level_17'));

			$resps = $custom->opportunity_logs_bam->find($where, $condtn);
			$result_1 = iterator_to_array($resps);  
			echo "<br>count= ".count($result_1);
			
			$sold = 0;
			$open_lth =0;
			$avg_sold_manul = 0;
			$avg_sold = 0;
			$avg_open_lth =0;
			$picket_parent = 0;
			$execuated_parent = 0;
			$other_status = 0;
			$cancelled = 0;        
			$new_error = 0;
			$created_date ='';
			$sum_all = 0;
			$total_investment_btc = 0;						
			$total_profit_btc = 0;
			$total_buy_comission_btc = 0;
			$total_sell_comission_btc = 0;
			$buy_comission_BNB =0;
			$sell_comission_BNB =0;
			$total_gain_btc = 0;


			$sellTimeDiffRange1 = 0 ;
			$sellTimeDiffRange2 = 0 ;	
			$sellTimeDiffRange3 = 0 ;
			$sellTimeDiffRange4 = 0 ;
			$sellTimeDiffRange5 = 0 ;
			$sellTimeDiffRange6 = 0 ;
			$sellTimeDiffRange7 = 0 ;

			$buyTimeDiffRange1 = 0 ;
			$buyTimeDiffRange2 = 0 ;	
			$buyTimeDiffRange3 = 0 ;
			$buyTimeDiffRange4 = 0 ;
			$buyTimeDiffRange5 = 0 ;
			$buyTimeDiffRange6 = 0 ;
			$buyTimeDiffRange7 = 0 ;

			$sumPLSllipageRange1 = 0 ;
			$sumPLSllipageRange2 = 0 ; 
			$sumPLSllipageRange3 = 0 ;
			$sumPLSllipageRange4 = 0 ;
			$sumPLSllipageRange5 = 0 ;

			if(count($result_1) > 0){
				foreach($result_1 as $value_1){  
					$total_investment_btc += $value_1['btc_investment'];
					$total_profit_btc += $value_1['total_btc_profit'];
					$total_buy_comission_btc += $value_1['buy_commission'];  
					$total_sell_comission_btc += $value_1['sell_commission'];  
					$buy_comission_BNB  += $value_1['buy_commision_qty'];
					$sell_comission_BNB += $value_1['sell_fee_respected_coin'];
					$total_gain_btc +=   $value_1['sold_btc_profit'];      
					echo"<br>oppertunity Id = ".$value_1['opportunity_id'];
					$opp['opportunity_id'] = $value_1['opportunity_id']; 
					$opp['mode'] = 'live';			
					$created_date = $value_1['created_date'];
					$avg_sold_manul += $value_1['avg_manul'];
					$sold +=  $value_1['sold'];
					$open_lth += $value_1['open_lth'];
					$avg_sold += $value_1['avg_sold'];
					$avg_open_lth += $value_1['avg_open_lth'];
					$picket_parent += $value_1['parents_picked'];
					$execuated_parent +=  $value_1['parents_executed'];
					$other_status += $value_1['other_status'];
					$cancelled += $value_1['cancelled'];        
					$new_error += $value_1['new_error'];
					$sum_all = $sold + $cancelled + $new_error;

					$sellTimeDiffRange1 += 	$value_1['sellTimeDiffRange1']; 	
					$sellTimeDiffRange2 +=	$value_1['sellTimeDiffRange2']; 	
					$sellTimeDiffRange3 +=	$value_1['sellTimeDiffRange3']; 	
					$sellTimeDiffRange4 +=	$value_1['sellTimeDiffRange4']; 	
					$sellTimeDiffRange5 +=	$value_1['sellTimeDiffRange5']; 	
					$sellTimeDiffRange6 +=	$value_1['sellTimeDiffRange6']; 	
					$sellTimeDiffRange7 +=	$value_1['sellTimeDiffRange7']; 

					$buyTimeDiffRange1 += 	$value_1['buySumTimeDelayRange1']; 	
					$buyTimeDiffRange2 +=	$value_1['buySumTimeDelayRange2']; 	
					$buyTimeDiffRange3 +=	$value_1['buySumTimeDelayRange3']; 	
					$buyTimeDiffRange4 +=	$value_1['buySumTimeDelayRange4']; 	
					$buyTimeDiffRange5 +=	$value_1['buySumTimeDelayRange5']; 	
					$buyTimeDiffRange6 +=	$value_1['buySumTimeDelayRange6']; 	
					$buyTimeDiffRange7 +=	$value_1['buySumTimeDelayRange7'];

					$sumPLSllipageRange1 += $value_1['sumPLSllipageRange1']; 	
					$sumPLSllipageRange2 += $value_1['sumPLSllipageRange2']; 	 
					$sumPLSllipageRange3 +=	$value_1['sumPLSllipageRange3']; 	
					$sumPLSllipageRange4 += $value_1['sumPLSllipageRange4']; 	
					$sumPLSllipageRange5 += $value_1['sumPLSllipageRange5']; 	


				}
				$time = $created_date->toDateTime()->format("Y-m-d H:i:s");
				$created_date_mnth_year = date("Y-m",strtotime($time));
				$created_month_year =  $this->mongo_db->converToMongodttime($created_date_mnth_year);
				$current_month_year = $this->mongo_db->converToMongodttime($compaire_month);
				
				echo "<br>datetime month year = ". date("Y-m",strtotime($time));
				echo "<br>execuated =".$execuated_parent;
				echo "<br>Sum of all = ".$sum_all;
				echo"<br>open lth =".$open_lth;
				echo"<br> if check value = ".$created_month_year."!=".$current_month_year;
				
				$final_open_avg = 0;
				$final_avg_manul_sold = 0;
				$final_sold_avg = 0;
				$final_open_avg = $avg_open_lth / count($result_1);
				$final_sold_avg = $avg_sold / count($result_1);
				$final_avg_manul_sold = $avg_sold_manul/ count($result_1);
				if($final_open_avg == '' || $final_open_avg == null || is_infinite($final_open_avg)){
					$final_open_avg = 0;
				}
				if($final_sold_avg == '' || $final_sold_avg == null || is_infinite($final_sold_avg)){
					$final_sold_avg = 0;
				}
				if($final_avg_manul_sold == '' || $final_avg_manul_sold == null || is_infinite($final_avg_manul_sold)){
					$final_avg_manul_sold = 0;
				}
				$new_array = array(
					'sold' 				=> $sold,
					'month'        		=> $month,
					'coin'				=> $coin,
					'mode' 				=> 'live',
					'open_lth' 			=> $open_lth,
					'avg_sold'	    	=> $final_sold_avg,
					'avg_open_lth'     	=> $final_open_avg,

					'sellTimeDiffRange1'	=>	($sellTimeDiffRange1 / $sold) * 100,
					'sellTimeDiffRange2'	=>	($sellTimeDiffRange2 / $sold) * 100,	
					'sellTimeDiffRange3'	=>	($sellTimeDiffRange3 / $sold) * 100,
					'sellTimeDiffRange4'	=>	($sellTimeDiffRange4 / $sold) * 100,
					'sellTimeDiffRange5'	=>	($sellTimeDiffRange5 / $sold) * 100,
					'sellTimeDiffRange6'	=>	($sellTimeDiffRange6 / $sold) * 100,
					'sellTimeDiffRange7'	=>	($sellTimeDiffRange7 / $sold) * 100,

					'buyTimeDiffRange1'		=> 	($buyTimeDiffRange1 / ($sold + $open_lth)) * 100 , 		 	
					'buyTimeDiffRange2' 	=> 	($buyTimeDiffRange2 / ($sold + $open_lth)) * 100 ,	
					'buyTimeDiffRange3' 	=> 	($buyTimeDiffRange3 / ($sold + $open_lth)) * 100 , 	
					'buyTimeDiffRange4' 	=> 	($buyTimeDiffRange4 / ($sold + $open_lth)) * 100 , 	
					'buyTimeDiffRange5' 	=> 	($buyTimeDiffRange5 / ($sold + $open_lth)) * 100 , 	
					'buyTimeDiffRange6' 	=> 	($buyTimeDiffRange6 / ($sold + $open_lth)) * 100 ,  	
					'buyTimeDiffRange7' 	=> 	($buyTimeDiffRange7 / ($sold + $open_lth)) * 100 , 
					
					'sumPLSllipageRange1'	=>	($sumPLSllipageRange1 / $sold) * 100,
					'sumPLSllipageRange2'	=>	($sumPLSllipageRange2 / $sold) * 100,	
					'sumPLSllipageRange3'	=>	($sumPLSllipageRange3 / $sold) * 100,
					'sumPLSllipageRange4'	=>	($sumPLSllipageRange4 / $sold) * 100,
					'sumPLSllipageRange5'	=>	($sumPLSllipageRange5 / $sold) * 100,

					'execuated_parent' 	=> $execuated_parent,
					'other_status' 	   	=> $other_status,
					'last_modified_time'=> $current_time_date,
					'total_oppertunities'=>count($result_1),
					'avg_sold_manul'  	=> $final_avg_manul_sold,
					'total_investment' 	=> $total_investment_btc,
					'buy_comission_BNB'=> $buy_comission_BNB,
					'buy_comission' 	=> $total_buy_comission_btc
				);
				if($execuated_parent == $sum_all && $created_month_year != $current_month_year && ($open_lth + $other_status) == 0){
					echo"<br>asasas";
					$new_array['total_profit'] =$total_profit_btc;
					$new_array['total_gain'] = $total_gain_btc; 
					$new_array['sell_comission'] =  $total_sell_comission_btc;
					$new_array['sell_comission_BNB'] = $sell_comission_BNB;
				}
				echo "<pre>";
				print_r($new_array);
				$search_find['month'] = $month;
				$search_find['coin']  = $coin;
				$search_find['mode'] = 'live';
				$upsert['upsert'] = true;
				$custom->opportunity_logs_monthly_bam->updateOne($search_find, ['$set'=> $new_array], $upsert);
			}
			$coin_count++;
			echo "<br>total picked records = ".count($result_1)."coin = ".$value['symbol'];
			
		}//end loop			
	} //end cron

	//  add for crontab details
	public function last_cron_execution_time($name, $duration, $summary, $type){
	
		$this->load->library('mongo_db_3');
		$db_3 = $this->mongo_db_3->customQuery();
		$params = [
			'name' => $name,
			'cron_duration' 					=> 	$duration,
			'cron_summary'  					=> 	$summary,
			'type'          					=> 	$type,
			'last_updated_time_human_readible'	=> 	date('Y-m-d H:i:s')
		];
		echo "<pre>";print_r($params);
		$whereUpdate['name'] = $name;
		$upsert['upsert'] = true;
		$db_3->cronjob_execution_logs->updateOne($whereUpdate ,['$set' => $params], $upsert);
		echo "<br>update done";
	}//End last_cron_execution_time

    // counting daily trade expected for kraken and binance both
	public function countDailyExpectedTrade(){
		$db = $this->mongo_db->customQuery();

		$collectionGetTradeCountBinance =  'auto_trade_settings'; 
		$collectionGetTradeCountKraken  =  'auto_trade_settings_kraken';
		
		$getTradeNumberCount = [
			[
				'$group' => [
					'_id' => 1,
					'total_usdt_count' => ['$sum' => '$step_4.dailyTradesExpectedUsdt'],
					'total_btc_count'  => ['$sum' => '$step_4.dailyTradesExpectedBtc']   
				]
			],
		];

		$binanceTradeCountResponse 		= 	$db->$collectionGetTradeCountBinance->aggregate($getTradeNumberCount);  //binance trade count
		$binanceTradeCountResponse 		= 	iterator_to_array($binanceTradeCountResponse);

		$krakenTradeCountResponse 		= 	$db->$collectionGetTradeCountKraken->aggregate($getTradeNumberCount);  //kraken trade count
		$krakenTradeCountResponse       = 	iterator_to_array($krakenTradeCountResponse);



		//getting active users count
		$search_criteria =[
			'avaliableBtcBalance'       => ['$gt' => 0 ],
			'exchange_enabled'          => 'yes',
			'remainingPoints'           => ['$gt' => 0 ],
		];
		$binanceActiveUserCount  	=  	$db->user_investment_binance->count($search_criteria);
		$krakenActiveUserCount  	=  	$db->user_investment_kraken->count($search_criteria);
		//end get user count



		$arrayInsertBinance = [
			'total_usdt_count'  	=>  $binanceTradeCountResponse[0]['total_usdt_count'],
			'total_btc_count'   	=>  $binanceTradeCountResponse[0]['total_btc_count'],
			'exchange'				=>	'binance',	
			'activeUserCount' 		=>	$binanceActiveUserCount
		];

		$arrayInsertKraken = [
			'total_usdt_count'  	=>  $krakenTradeCountResponse[0]['total_usdt_count'],
			'total_btc_count'   	=>  $krakenTradeCountResponse[0]['total_btc_count'],
			'exchange' 				=>	'kraken',	
			'activeUserCount' 		=>	$krakenActiveUserCount
		];


		$upsert_criteria['created_date']  =  $this->mongo_db->converToMongodttime(date('Y-m-d'));
		$upsert_criteria['exchange']     =  'binance';
		$db->expected_trade_buy_count_history->updateOne($upsert_criteria, ['$set' => $arrayInsertBinance], ['upsert' => true]);

		$upsert_criteria_kraken['created_date']  =  $this->mongo_db->converToMongodttime(date('Y-m-d'));
		$upsert_criteria_kraken['exchange']     =  'kraken';
		$db->expected_trade_buy_count_history->updateOne($upsert_criteria_kraken, ['$set' => $arrayInsertKraken], ['upsert' => true]);

		echo"<pre>";
		print_r($arrayInsertBinance);

		echo "<br>Kraken";
		print_r($arrayInsertKraken);


	}//end function

}//end controller