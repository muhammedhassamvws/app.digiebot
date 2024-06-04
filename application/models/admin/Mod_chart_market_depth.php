<?php 
class mod_chart_market_depth extends CI_Model {
	
	public function get_market_ask_depth($symbol)
	{
		$this->mongo_db->where(array('type'=> 'ask', 'coin'=> $symbol));
		//$this->mongo_db->limit(20);
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('market_depth');
		$depth_ask_arr = array();
		foreach ($responseArr as  $valueArr) {
			$returArr = array();

			if(!empty($valueArr)){
				
				$depth_ask_arr[] = array(
					'price' => $valueArr['price'],
					'volume' => $valueArr['quantity'] 
				);
			}
		}
		return $depth_ask_arr;
	}

	public function get_market_bid_depth($symbol)
	{
		$this->mongo_db->where(array('type'=> 'bid', 'coin'=> $symbol));
		//$this->mongo_db->limit(20);
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('market_depth');
		$depth_ask_arr = array();
		foreach ($responseArr as  $valueArr) {
			$returArr = array();

			if(!empty($valueArr)){
				
				$depth_ask_arr[] = array(
					'price' => $valueArr['price'],
					'volume' => $valueArr['quantity'] 
				);
			}
		}
		return $depth_ask_arr;
	}
}
?>