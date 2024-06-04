<?php
class mod_balance extends CI_Model {

	function __construct() {

		parent::__construct();
	}

	public function update_coin_balance_sql($coin, $balance, $user_id) {
		$this->db->dbprefix('coin_balance');
		$this->db->where('coin_symbol', $coin);
		$this->db->where('user_id', $user_id);
		$bal_arr = $this->db->get('coin_balance');
		$bal = $bal_arr->result_array();

		$upd_data = array(
			'coin_symbol' => $coin,
			'user_id' => $user_id,
			'coin_balance' => $balance,
		);
		if (count($bal) == 0) {
			$this->db->dbprefix('coin_balance');
			$ins = $this->db->insert('coin_balance', $upd_data);
		} else {
			$this->db->dbprefix('coin_balance');
			$this->db->where('user_id', $user_id);
			$this->db->where('coin_symbol', $coin);
			$ins = $this->db->update('coin_balance', $upd_data);
		}

		return $ins;
	}

	public function update_coin_balance($coin, $balance, $user_id) {
		$this->mongo_db->where(array('symbol' => $coin, 'user_id' => $user_id));
		$bal_arr = $this->mongo_db->get('coins');
		$bal = iterator_to_array($bal_arr);

		$upd_data = array(
			'symbol' => $coin,
			'user_id' => $user_id,
			'coin_balance' => $balance,
		);
		if (count($bal) == 0) {
			$ins = $this->mongo_db->insert('coins', $upd_data);
		} else {
			$this->mongo_db->where(array('symbol' => $coin, 'user_id' => $user_id));
			$this->mongo_db->set($upd_data);
			$ins = $this->mongo_db->update('coins', $upd_data);
		}

		return $ins;
	}
}
?>