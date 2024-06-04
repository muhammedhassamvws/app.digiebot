<?php
class mod_coins extends CI_Model {

	function __construct() {

		parent::__construct();
	}

	//get_all_coins
	public function get_all_coins_sql() {

		$this->db->dbprefix('coins');
		$this->db->order_by('id ASC');
		$get_coins = $this->db->get('coins');

		//echo $this->db->last_query();
		$coins_arr = $get_coins->result_array();

		return $coins_arr;

	} //end get_all_coins

	public function get_all_coins($exchange='') {
		if ($exchange == 'kraken') {
			$exchange_type = 'kraken';
			$coins_collection = 'coins_kraken';
		}elseif($exchange == 'dg'){
			$exchange_type = 'dg';
			$coins_collection = 'coins_dg';
		}elseif($exchange == 'okex'){
			$exchange_type = 'okex';
			$coins_collection = 'coins_okex';
		}else{
			$exchange_type = 'binance';
			$coins_collection = 'coins';
		}
		$where_arr = array(
			'user_id' => 'global',
			'exchange_type' => $exchange_type,
		);
// echo "<pre>"; print_r($coins_collection);
// echo "<pre>"; print_r($where_arr); exit;
		$this->mongo_db->sort(array('symbol' => 1));
		// $this->mongo_db->where(array('user_id' => 'global'));
		$this->mongo_db->where($where_arr);
		$get_coins = $this->mongo_db->get($coins_collection);
		$coins_arr = iterator_to_array($get_coins);
		return $coins_arr;
	} //end get_all_coins

	//get_all_coins
	public function get_all_user_coins_sql($user_id) {
		$this->db->select('*');
		$this->db->where('user_id', $user_id);
		$this->db->from('coins');
		$this->db->join('user_coins', 'coins.id = user_coins.coin_id');
		$query = $this->db->get();
		$coins_arr = $query->result_array();
		return $coins_arr;
	} //end get_all_coins


	public function get_all_user_coins($user_id) {
		$this->mongo_db->sort(array('_id' => 1));
		$this->mongo_db->where(array('user_id' => ($user_id),'symbol' => array('$nin' => array('',null,'BTC','BNBBTC'))));
		$get_coins = $this->mongo_db->get('coins');
		$coins_arr = iterator_to_array($get_coins);
		return $coins_arr;
	} //end get_all_coins

	//get_coin
	public function get_coin_sql($coin_id) {
		$this->db->dbprefix('coins');
		$this->db->where('id', $coin_id);
		$get_coin = $this->db->get('coins');
		$coin_arr = $get_coin->row_array();
		return $coin_arr;
	} //end get_coin


	public function get_coin($coin_id, $exchange) {
		if ($exchange == 'kraken') {
			$coins_collection = 'coins_kraken';
		}else{
			$coins_collection = 'coins';
		}
		$this->mongo_db->where(array('_id' => $coin_id));
		$get_coin = $this->mongo_db->get($coins_collection);
		$coins_arr = iterator_to_array($get_coin);
		return $coins_arr[0];
	} //end get_coin


	//add_coin
	public function add_coin_sql($data) {
		extract($data);
		$created_date = date('Y-m-d G:i:s');

		$ins_data = array(
			'coin_name' => (trim($coin_name)),
			'symbol' => (trim($symbol)),
			'coin_keywords' => (trim($keywords)),
			'unit_value' => $unit_value,
			'offset_value' => $offset,
			'base_order' => $base_order,
			'base_history' => $base_history,
			'rejection' => $rejection,
			'depth_wall_percentage' => $depth_wall_percentage,
			'depth_wall_amount' => $depth_wall_amount,
			'wall_setting' => (trim($wall_setting)),

			'yellow_wall_percentage' => (trim($yellow_wall_percentage)),
			'yellow_wall_amount' => (trim($yellow_wall_amount)),
			'yellow_wall_setting' => (trim($yellow_wall_setting)),

			'contract_percentage' => (trim($contract_percentage)),
			'contract_time' => $contract_time,
			'contract_size' => $contract_size,
			'created_date' => (trim($created_date)),
		);

		if ($logo1 != '') {
			$ins_data['coin_logo'] = (trim($logo1));
		}

		//Insert the record into the database.
		$this->db->dbprefix('coins');
		$ins_into_db = $this->db->insert('coins', $ins_data);
		//echo $this->db->last_query();exit;
		if ($ins_into_db) {
			$ins_data = array(
				'coin_id' => ($ins_into_db),
				'user_id' => (trim($this->session->userdata('admin_id'))),
			);

			$this->db->dbprefix('user_coins');
			$ins_into_db3 = $this->db->insert('user_coins', $ins_data);
		}
		$coin_id = $this->db->insert_id();

		if ($ins_into_db) {
			return $coin_id;
		}

	} //end add_coin()



	//add_coin
	public function add_coin($data) {
		extract($data);
		$created_date = date('Y-m-d G:i:s');
		$ins_data = array(
			
			'coin_name' => (trim($coin_name)),
			'symbol' => (trim($symbol)),
			'coin_keywords' => (trim($keywords)),
			'unit_value' => $unit_value,
			'offset_value' => $offset,
			'base_order' => $base_order,
			'base_history' => $base_history,
			'rejection' => $rejection,
			'depth_wall_percentage' => $depth_wall_percentage,
			'depth_wall_amount' => $depth_wall_amount,
			'wall_setting' => (trim($wall_setting)),
			'yellow_wall_percentage' => (trim($yellow_wall_percentage)),
			'yellow_wall_amount' => (trim($yellow_wall_amount)),
			'yellow_wall_setting' => (trim($yellow_wall_setting)),
			'contract_percentage' => (trim($contract_percentage)),
			'contract_time' => $contract_time,
			'contract_size' => $contract_size,
			'user_id' => 'global',
			'created_date' => (trim($created_date)),
			'exchange_type' => $exchange,
		);

		if ($logo1 != '') {
			$ins_data['coin_logo'] = (trim($logo1));
		}
		//Insert the record into the database.
		$ins_into_db = $this->mongo_db->insert('coins', $ins_data);

			return $coin_id;

	} //end add_coin()


	//edit_coin
	public function edit_coin_sql($data) {

		extract($data);

		$upd_data = array(
			'coin_name' => (trim($coin_name)),
			'coin_keywords' => (trim($keywords)),
			'unit_value' => $unit_value,
			'offset_value' => $offset,
			'base_order' => $base_order,
			'base_history' => $base_history,
			'rejection' => $rejection,
			'depth_wall_percentage' => $depth_wall_percentage,
			'depth_wall_amount' => $depth_wall_amount,
			'wall_setting' => $wall_setting,

			'yellow_wall_percentage' => $yellow_wall_percentage,
			'yellow_wall_amount' => $yellow_wall_amount,
			'yellow_wall_setting' => $yellow_wall_setting,

			'contract_percentage' => $contract_percentage,
			'contract_time' => $contract_time,
			'contract_size' => $contract_size,
			'symbol' => (trim($symbol)),
			'modified_date' => date('Y-m-d H:i:s'),
		);

		if ($logo1 != '') {
			$upd_data['coin_logo'] = (trim($logo1));
		}

		//Update the record into the database.
		$this->db->dbprefix('coins');
		$this->db->where('id', $coin_id);
		$upd_into_db = $this->db->update('coins', $upd_data);

		if ($upd_into_db) {
			return $coin_id;
		}

	} //end edit_coin()


	public function edit_coin($data) {
		extract($data);
		// echo '<pre>';print_r($data);
		// exit;
		$upd_data = array(
			'coin_name' => (trim($coin_name)),
			'coin_keywords' => (trim($keywords)),
			'unit_value' => $unit_value,
			'offset_value' => $offset,
			'base_order' => $base_order,
			'base_history' => $base_history,
			'rejection' => $rejection,
			'depth_wall_percentage' => $depth_wall_percentage,
			'depth_wall_amount' => $depth_wall_amount,
			'wall_setting' => $wall_setting,
			'yellow_wall_percentage' => $yellow_wall_percentage,
			'yellow_wall_amount' => $yellow_wall_amount,
			'yellow_wall_setting' => $yellow_wall_setting,
			'contract_percentage' => $contract_percentage,
			'contract_time' => $contract_time,
			'contract_size' => $contract_size,
			'contract_period' => $contract_period,
			'symbol' => (trim($symbol)),
			'modified_date' => date('Y-m-d H:i:s'),
			'exchange_type' => $exchange,
			'blocked_country_array' => $country_multi
		);

		if ($logo1 != '') {
			$upd_data['coin_logo'] = (trim($logo1));
		}
		$this->mongo_db->where(array('_id' => $coin_id));
		$this->mongo_db->set($upd_data);

		if ($exchange == 'kraken') {
			$coins_collection = 'coins_kraken'; 
		} else {
			$coins_collection = 'coins'; 
		}
		// echo $coins_collection; 
		// echo "<pre>"; print_r($coin_id); exit;
		$upd_into_db = $this->mongo_db->update($coins_collection, $upd_data);
		
			return $coin_id;

	} //end edit_coin()


	//delete_coin
	public function delete_coin_sql($coin_id) {

		//Delete coin Record
		$this->db->dbprefix('coins');
		$this->db->where('id', $coin_id);
		$this->db->delete('coins');

		return true;

	} //end delete_coin()

	//delete_coin
	public function delete_coin($coin_id) {
		$this->mongo_db->where(array('_id' => $coin_id));
		$this->mongo_db->delete('coins');
		return true;
	} //end delete_coin()

	public function get_coin_logo_sql($symbol) {
		$this->db->dbprefix('coins');
		$this->db->select('coin_logo');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();
		return $get_arr['coin_logo'];
	} //end get_coin_logo()


	public function get_coin_logo($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['coin_logo'];
	} //end get_coin_logo()

	public function get_coin_unit_value_sql($symbol) {
		$this->db->dbprefix('coins');
		$this->db->select('unit_value');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();
		return $get_arr['unit_value'];
	} //end get_coin_unit_value()


	public function get_coin_unit_value($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['unit_value'];
	} //end get_coin_unit_value()

	public function get_coin_offset_value_sql($symbol) {
		$this->db->dbprefix('coins');
		$this->db->select('offset_value');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();
		return $get_arr['offset_value'];
	} //end get_coin_offset_value()

	public function get_coin_rejection_value($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['rejection'];
	} //end get_coin_rejection_value()

	public function get_coin_offset_value($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['offset_value'];
	} //end get_coin_offset_value()

	public function get_coin_depth_wall_setting_sql($symbol) {
		$this->db->dbprefix('coins');
		$this->db->select('wall_setting');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();

		return $get_arr['wall_setting'];
	} //end get_coin_depth_wall_setting()

	public function get_coin_depth_wall_setting($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['wall_setting'];
	} //end get_coin_depth_wall_setting()

	public function get_coin_depth_wall_value_sql($symbol) {
		$this->db->dbprefix('coins');
		$this->db->select('depth_wall_amount');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();
		return $get_arr['depth_wall_amount'];
	} //end get_coin_depth_wall_value()

	public function get_coin_depth_wall_value($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['depth_wall_amount'];
	} //end get_coin_depth_wall_value()

	public function get_coin_depth_wall_percentage_sql($symbol) {
		$this->db->dbprefix('coins');
		$this->db->select('depth_wall_percentage');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();

		return $get_arr['depth_wall_percentage'];
	} //end get_coin_depth_wall_percentage()

	public function get_coin_depth_wall_percentage($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['depth_wall_percentage'];
	} //end get_coin_depth_wall_percentage()

	public function get_coin_yellow_wall_setting_sql($symbol) {
		$this->db->dbprefix('coins');
		$this->db->select('yellow_wall_setting');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();

		return $get_arr['yellow_wall_setting'];
	} //end get_coin_yellow_wall_setting()

	public function get_coin_yellow_wall_setting($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['yellow_wall_setting'];
	} //end get_coin_yellow_wall_setting()

	public function get_coin_yellow_wall_value_sql($symbol) {
		$this->db->dbprefix('coins');
		$this->db->select('yellow_wall_amount');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();
		return $get_arr['yellow_wall_amount'];
	} //end get_coin_yellow_wall_value()

	public function get_coin_yellow_wall_value($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['yellow_wall_amount'];
	} //end get_coin_yellow_wall_value()
	public function get_coin_yellow_wall_percentage_sql($symbol) {
		$this->db->dbprefix('coins');
		$this->db->select('yellow_wall_percentage');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();

		return $get_arr['yellow_wall_percentage'];
	} //end get_coin_yellow_wall_percentage()


	public function get_coin_yellow_wall_percentage($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['yellow_wall_percentage'];
	} //end get_coin_yellow_wall_percentage()

	public function get_coin_base_order_sql($symbol) {
		$this->db->dbprefix('coins');
		$this->db->select('base_order');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();

		return $get_arr['base_order'];
	} //end get_coin_base_order()

	public function get_coin_base_order($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['base_order'];
	} //end get_coin_base_order()

	public function get_coin_base_history_sql($symbol) {
		$this->db->dbprefix('coins');
		$this->db->select('base_history');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();

		return $get_arr['base_history'];
	} //end get_coin_base_history()

	public function get_coin_base_history($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['base_history'];
	} //end get_coin_base_history()

	public function get_coin_contract_value_sql($symbol) {
		$this->db->dbprefix('coins');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();
		return array('contract_percentage' => $get_arr['contract_percentage'], 'contract_time' => $get_arr['contract_time']);
	} //end get_coin_contract_value()

	public function get_coin_contract_value($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return array('contract_percentage' => $get_arr['contract_percentage'], 'contract_time' => $get_arr['contract_time']);
	} //end get_coin_contract_value()

	//contract_size
	public function get_coin_contract_size_sql($symbol) {
		$this->db->dbprefix('coins');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();
		return $get_arr['contract_size'];
	} //end get_coin_contract_size()

	public function get_coin_contract_size($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['contract_size'];
	} //end get_coin_contract_size()

	public function get_coin_contract_period($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['contract_period'];
	} //end get_coin_contract_size()

	public function get_big_contract_base($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['big_contract_base'];
	} //end get_big_contract_base()

	public function get_big_contract_delta_base($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		$big_contract_delta_base = $get_arr['big_contract_delta_base'];
		$big_contract_delta_base = ($big_contract_delta_base == '')?0:$big_contract_delta_base;
		return $big_contract_delta_base;
	} //end get_big_contract_base()


	public function get_coin_from_symbol($symbol) {
		$this->mongo_db->where(array('symbol' => $symbol,'user_id' => 'global', 'exchange_type' => 'binance'));
		$get_coin = $this->mongo_db->get('coins');
		$coin_arr = iterator_to_array($get_coin);
		return $coin_arr[0];
	}// End of get_coin_from_symbol
}
?>
