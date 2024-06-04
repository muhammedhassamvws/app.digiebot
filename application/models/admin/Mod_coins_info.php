<?php
class mod_coins_info extends CI_Model {
	
	
	function __construct(){
		
        parent::__construct();
        
    }
	
	
	//Update Last Sigin Date in Admin
	public function save_coins_info($keyword,$coin,$News,$Score,$source,$factor){
		$created_date = date('Y-m-d G:i:s');
		$ins_data = array(
		'keyword' => explode(',', $keyword),
		'coin' => $coin,
		'News' => $News,
		'Date' => $this->mongo_db->converToMongodttime($created_date),
		'Score'=> $Score,
		'source'=> $source,
		'factor' => $factor
		);
		//Insert data in mongoTable 
		$res  = $this->mongo_db->insert('coins_news',$ins_data);
		if($res){
			return true;
		}else{
			return false;
		}

	}//end function validate

	public function fetch_coins()
	{
		$this->db->dbprefix('coins');
		$this->db->select('symbol as coin,coin_keywords as keyword',FALSE);
		$coins_arr = $this->db->get('coins');
		$coins = $coins_arr->result_array();
		return $coins;
	}	
	/**End of function fetch_coins */
	public function get_coins_info(){

		$res = $this->mongo_db->get('coins_news');
		foreach ($res as $key) {
			
			echo '<pre>';
			print_r($key);
		
		}
		
	}/*** End of get_coins_info***/

	public function get_coin_listing()
	{

		$res = $this->mongo_db->get('coins_news');
		foreach ($res as  $valueArr) {
			$returArr = array();

			if(!empty($valueArr)){
				/*$datetime = $valueArr['Date']->toDateTime();
		        $created_date = $datetime->format(DATE_RSS);

		        $datetime = new DateTime($created_date);
		        $datetime->format('Y-m-d g:i:s A');

		        $new_timezone = new DateTimeZone('Asia/Karachi');
		        $datetime->setTimezone($new_timezone);
		        $formated_date_time =  $datetime->format('Y-m-d g:i:s A');*/

				$returArr['_id'] = $valueArr['_id'];
				$returArr['keyword'] = $valueArr['keyword'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['news'] = $valueArr['News'];
				//$returArr['date'] = $formated_date_time;
				$returArr['score'] = $valueArr['score'];
				$returArr['source'] = $valueArr['source'];				

			}
			
			$fullarray[]= $returArr;
		}
		
		return $fullarray;
	}
}




?>