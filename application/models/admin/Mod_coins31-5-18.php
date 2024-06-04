<?php
class mod_coins extends CI_Model {
	
	function __construct(){
		
        parent::__construct();
    }

	//get_all_coins
	public function get_all_coins(){
		
		$this->db->dbprefix('coins');
		$this->db->order_by('id ASC');
		$get_coins = $this->db->get('coins');

		//echo $this->db->last_query();
		$coins_arr = $get_coins->result_array();
		
		return $coins_arr;
		
	}//end get_all_coins

	//get_all_coins
	public function get_all_user_coins($user_id){
		
		$this->db->select('*');
		$this->db->where('user_id',$user_id);
		$this->db->from('coins');
		$this->db->join('user_coins','coins.id = user_coins.coin_id');
		$query=$this->db->get();
		$coins_arr = $query->result_array();
		
		return $coins_arr;
		
	}//end get_all_coins
	

	//get_coin
	public function get_coin($coin_id){
		
		$this->db->dbprefix('coins');
		$this->db->where('id',$coin_id);
		$get_coin = $this->db->get('coins');

		//echo $this->db->last_query(); exit;
		$coin_arr = $get_coin->row_array();
		
		return $coin_arr;
		
	}//end get_coin
	
	
	
	//add_coin
	public function add_coin($data){
		
		extract($data);
		
		$created_date = date('Y-m-d G:i:s');
		
		$ins_data = array(
		   'coin_name' => $this->db->escape_str(trim($coin_name)),
		   'symbol' => $this->db->escape_str(trim($symbol)),
		   'coin_keywords' => $this->db->escape_str(trim($keywords)),
		   'created_date' => $this->db->escape_str(trim($created_date)),
		);

		if ($logo1 != '') {
			$ins_data['coin_logo'] = $this->db->escape_str(trim($logo1));
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
				$status = 'error';
				$msg = 'Uploading Failed';
			}
			else
			{
				$ins_data['coin_logo'] = SURL.$path_to_store.$new_file;
			}
		}*/
		//Insert the record into the database.
		$this->db->dbprefix('coins');
		$ins_into_db = $this->db->insert('coins', $ins_data);
		//echo $this->db->last_query();exit;
		
		$coin_id = $this->db->insert_id(); 
		
		if($ins_into_db) return $coin_id;

	}//end add_coin()
	
	
	//edit_coin
	public function edit_coin($data){
		
		extract($data);
		
		$upd_data = array(
		   'coin_name' => $this->db->escape_str(trim($coin_name)),
		   'coin_keywords' => $this->db->escape_str(trim($keywords)),
		   'symbol' => $this->db->escape_str(trim($symbol))
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
		}*/
		//Update the record into the database.
		$this->db->dbprefix('coins');
		$this->db->where('id', $coin_id);
		$upd_into_db = $this->db->update('coins', $upd_data);
		
		if($upd_into_db) return $coin_id;

	}//end edit_coin()
	
	
	//delete_coin
	public function delete_coin($coin_id){
		
		//Delete coin Record
		$this->db->dbprefix('coins');
		$this->db->where('id',$coin_id);
		$this->db->delete('coins');
		
		return true;

	}//end delete_coin()

	public function get_coin_logo($symbol)
	{
		$this->db->dbprefix('coins');
		$this->db->select('coin_logo');
		$this->db->where('symbol',$symbol);
		$get = $this->db->get('coins');
		$get_arr =  $get->row_array();
		return $get_arr['coin_logo'];
	}//end get_coin_logo()
}
?>