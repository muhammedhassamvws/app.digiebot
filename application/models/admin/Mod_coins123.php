<?php
class mod_coins extends CI_Model {

	function __construct() {

		parent::__construct();
	}

	//get_all_coins
	public function get_all_coins() {

		$this->mongo_db->sort(array('_id' => -1));
		$get_coins = $this->mongo_db->get('coins');

		//echo $this->db->last_query();
		$coins_arr = iterator_to_array($get_coins);

		return $coins_arr;

	} //end get_all_coins

	//get_all_coins
	public function get_all_user_coins($user_id) {

		$this->db->select('*');
		$this->db->where('user_id', $user_id);
		$this->db->from('coins');
		$this->db->join('user_coins', 'coins.id = user_coins.coin_id');
		$query = $this->db->get();
		$coins_arr = $query->result_array();

		return $coins_arr;

	} //end get_all_coins

	//get_coin
	public function get_coin($coin_id) {

		$this->mongo_db->where(array('_id' => $coin_id));
		$get_coin = $this->mongo_db->get('coins');

		//echo $this->db->last_query(); exit;
		$coins_arr = iterator_to_array($get_coin);

		return $coins_arr[0];

	} //end get_coin

	//add_coin
	public function add_coin($data) {

		extract($data);

		$created_date = date('Y-m-d G:i:s');

		$ins_data = array(
			'coin_name' => $this->db->escape_str(trim($coin_name)),
			'symbol' => $this->db->escape_str(trim($symbol)),
			'coin_keywords' => $this->db->escape_str(trim($keywords)),
			'unit_value' => $unit_value,
			'offset_value' => $offset,
			'created_date' => $this->db->escape_str(trim($created_date)),
		);

		if ($logo1 != '') {
			$ins_data['coin_logo'] = $this->db->escape_str(trim($logo1));
		}

		$ins_into_db = $this->mongo_db->insert('coins', $ins_data);
		//echo $this->db->last_query();exit;
		if ($ins_into_db) {
			$ins_data11 = array(
				'coin_id' => $this->db->escape_str($ins_into_db),
				'user_id' => $this->db->escape_str(trim($this->session->userdata('admin_id'))),
			);

			$this->db->dbprefix('user_coins');
			$ins_into_db3 = $this->db->insert('user_coins', $ins_data11);
		}
		$coin_id = $this->db->insert_id();

		if ($ins_into_db) {
			return $coin_id;
		}

	} //end add_coin()

	//edit_coin
	public function edit_coin($data) {

		extract($data);

		$upd_data = array(
			'coin_name' => $this->db->escape_str(trim($coin_name)),
			'coin_keywords' => $this->db->escape_str(trim($keywords)),
			'unit_value' => $unit_value,
			'offset_value' => $offset,
			'symbol' => $this->db->escape_str(trim($symbol)),
		);

		if ($logo1 != '') {
			$upd_data['coin_logo'] = $this->db->escape_str(trim($logo1));
		}
		/*if($_FILES)
			{
				$path_to_store = 'assets/coin_logo/';
				$file_name = $_FILES['logo']['name'];
				$temp_name = $_FILES['logo']['tmp_name'];
				$size   = $_FILES['logo']['size'];
				$ext 	=	pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
				$new_file = 'attachment-'.time().uniqid(rand()).'.'.$ext;
				if (!(move_uploaded_file($temp_name, $path_to_store.$new_file)))
				{
					echo "Uploading Failed"; exit;
				}
				else
				{
					$upd_data['coin_logo'] = SURL.$path_to_store.$new_file;
				}
		*/
		//Update the record into the database.
		//$this->db->dbprefix('coins');
		$this->mongo_db->where(array('_id' => $coin_id));
		$this->mongo_db->set($upd_data);
		$upd_into_db = $this->mongo_db->update('coins', $upd_data);

		if ($upd_into_db) {
			return $coin_id;
		}

	} //end edit_coin()

	//delete_coin
	public function delete_coin($coin_id) {

		//Delete coin Record
		//$this->db->dbprefix('coins');
		$this->mongo_db->where(array('_id' => $coin_id));
		$this->db->delete('coins');

		return true;

	} //end delete_coin()

	public function get_coin_logo($symbol) {
		$this->db->dbprefix('coins');
		$this->db->select('coin_logo');
		$this->db->where('symbol', $symbol);
		$get = $this->db->get('coins');
		$get_arr = $get->row_array();
		return $get_arr['coin_logo'];
	} //end get_coin_logo()

	public function get_coin_unit_value($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['unit_value'];
	} //end get_coin_unit_value()

	public function get_coin_offset_value($symbol) {
		$get_arr = $this->get_coin_from_symbol($symbol);
		return $get_arr['offset_value'];
	} //end get_coin_offset_value()

	public function get_coin_from_symbol($symbol) {
		$this->mongo_db->where(array('symbol' => $symbol));
		$get_coin = $this->mongo_db->get('coins');

		//echo $this->db->last_query(); exit;
		$coins_arr = iterator_to_array($get_coins);

		return $coin_arr[0];
	}
}
?>