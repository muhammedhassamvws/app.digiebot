<?php
class mod_dashboard extends CI_Model {
	
	function __construct(){
		
        parent::__construct();
    }

	public function get_market_data(){

		//$now_date = date('Y-m-d G:i:s', strtotime('-5 minute'));
	 //	$this->mongo_db->where(array('type'=> 'ask', 'coin'=> 'BNBBTC', 'created_date' => $now_date));

		$price = (float)$_GET['price'];
		$this->mongo_db->where(array('type'=> 'ask', 'coin'=> 'BNBBTC', 'price' => $price));
		$this->mongo_db->limit(500);
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('market_depth');


		$fullarray = array();
		foreach ($responseArr as  $valueArr) {
			$returArr = array();

			if(!empty($valueArr)){

				$datetime = $valueArr['created_date']->toDateTime();
		        $created_date = $datetime->format(DATE_RSS);

		        $datetime = new DateTime($created_date);
		        $datetime->format('Y-m-d g:i:s A');

		        $new_timezone = new DateTimeZone('Asia/Karachi');
		        $datetime->setTimezone($new_timezone);
		        $formated_date_time =  $datetime->format('Y-m-d g:i:s A');
				 
				$returArr['_id'] = $valueArr['_id'];
				$returArr['price'] = $valueArr['price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['type'] = $valueArr['type'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['created_date'] = $formated_date_time;
				
			}
			
			$fullarray[]= $returArr;
		}


		
		// $sort = array();
		// foreach($fullarray as $k=>$v) {
		//     $sort['price'][$k] = $v['price'];
		// }
		// array_multisort($sort['price'], SORT_ASC, $fullarray);

		echo "<pre>";
		print_r($fullarray);
		exit;
	}


	//get_market_buy_depth
	public function get_market_buy_depth(){

		//Get Market Data
		$market_prices_arr = $this->binance_api->get_market_prices();
		$market_value = $market_prices_arr['BNBBTC'];

		//$now_date = date('Y-m-d G:i:s', strtotime('-15 minute'));
	 	//$this->mongo_db->where(array('type'=> 'ask', 'coin'=> 'BNBBTC', 'created_date' => $now_date));

		///////////////////////////////////////
		$db = $this->mongo_db->customQuery();

		// $res = $db->market_depth->find(array(),array('limit'=>10));
		
		// foreach ($res as $key => $value) {
		// 	echo '<pre>';
		// 	print_r($value);
		// }

		
		// exit;


	

		$priceAsk = (float)$market_value;
		$pipeline = array(

		 array(
		        '$project' => array(
		            "price" => 1,
		            "quantity"=>1,
		            "type"=>1,
		            "coin"=>1,
		            'created_date'=>1
		        )
		    ),

		    array(
		        '$match' => array(
		        	'type'=>'ask',
		            'price' => array('$gte'=>$priceAsk)
		        )
		       ),
		      
		     // array('$sort'=>array('created_date'=>1)),
		    array('$sort'=>array('price'=>1)),
		    array('$group' => array(
		       '_id' => array('price' => '$price'),
		       'quantity'    => array('$first' => '$quantity'),
		       'type'    => array('$first' => '$type'),
		       'coin'    => array('$first' => '$coin'),
		       'created_date'    => array('$first' => '$created_date'),
		       'price'    => array('$first' => '$price'),
		       ),
		      
		      ),
		   	array('$sort'=>array('price'=>1)),
		   	array('$limit'=>50),
		   
		    );



		$allow = array('allowDiskUse'=>true);
		$responseArr = $db->market_depth->aggregate($pipeline,$allow);

		//$responseArr = $db->market_depth->find(array(),array('limit'=>10));

		$fullarray = array();
		foreach ($responseArr as  $valueArr) {
			$returArr = array();

			if(!empty($valueArr)){

			    $datetime = $valueArr['created_date']->toDateTime();
		        $created_date = $datetime->format(DATE_RSS);

		        $datetime = new DateTime($created_date);
		        $datetime->format('Y-m-d g:i:s A');

		        $new_timezone = new DateTimeZone('Asia/Karachi');
		        $datetime->setTimezone($new_timezone);
		        $formated_date_time =  $datetime->format('Y-m-d g:i:s A');
				 
				$returArr['_id'] = $valueArr['_id'];
				$returArr['price'] = $valueArr['price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['type'] = $valueArr['type'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['created_date'] = $formated_date_time;

				$priceee = $valueArr['price'];

				$this->mongo_db->where(array('type'=> 'ask', 'coin'=> 'BNBBTC', 'price' => $priceee));
				$this->mongo_db->limit(1);
				$this->mongo_db->sort(array('created_date'=> -1));
				$responseArr2222 = $this->mongo_db->get('market_depth');

				// echo '<pre>';
				// print_r($responseArr2222);
				// exit;


				//////////////
				foreach ($responseArr2222 as  $valueArr222) {

					if(!empty($valueArr222)){
						
						$returArr['quantity'] = $valueArr222['quantity'];
					}
					
				}
				////////////
				
			}
			
			$fullarray[]= $returArr;

			
		}
		///////////////////////////////////////
		// echo '<pre>';
		// print_r($fullarray);
		// exit;

		

		
		//Drop Table
        //$response = $this->mongo_db->drop_collection('market_depth');

	 // 	$now_date = date('Y-m-d G:i:s', strtotime('-5 minute'));
	 // 	$this->mongo_db->where(array('type'=> 'ask', 'coin'=> 'BNBBTC', 'created_date' => $now_date));
		// //$this->mongo_db->where(array('type'=> 'ask', 'coin'=> 'BNBBTC', 'price' => $_GET['price']));
		// $this->mongo_db->limit(20);
		// $this->mongo_db->sort(array('_id'=> 'desc'));
		// $responseArr = $this->mongo_db->get('market_depth');

		// echo "<pre>";
		// print_r($responseArr);
		// exit;

		// $fullarray = array();
		// foreach ($responseArr as  $valueArr) {
		// 	$returArr = array();

		// 	if(!empty($valueArr)){

		// 		$datetime = new DateTime($valueArr['created_date']);
		// 	    $datetime->format('d, M Y g:i:s A');

		// 	    $new_timezone = new DateTimeZone('Asia/Karachi');
		// 	    $datetime->setTimezone($new_timezone);
		// 	    $formated_date_time = $datetime->format('d, M Y g:i:s A');
				 
		// 		$returArr['_id'] = $valueArr['_id'];
		// 		$returArr['price'] = $valueArr['price'];
		// 		$returArr['quantity'] = $valueArr['quantity'];
		// 		$returArr['type'] = $valueArr['type'];
		// 		$returArr['coin'] = $valueArr['coin'];
		// 		$returArr['created_date'] = $formated_date_time;
				
		// 	}
			
		// 	$fullarray[]= $returArr;
		// }


		
		// $sort = array();
		// foreach($fullarray as $k=>$v) {
		//     $sort['price'][$k] = $v['price'];
		// }
		// array_multisort($sort['price'], SORT_DESC, $fullarray);

		// echo "<pre>";
		// print_r($fullarray);
		// exit;

		$data['market_value'] = $market_value;
		$data['fullarray'] = $fullarray;

		return $data;
		
	}//end get_market_buy_depth

	
	//get_market_sell_depth 
	public function get_market_sell_depth(){

		$now_date = date('Y-m-d G:i:s', strtotime('-2 second'));
		$this->mongo_db->where(array('type'=> 'bid', 'coin'=> 'BNBBTC', 'created_date' => $now_date));
	
		$this->mongo_db->limit(20);
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('testAutomation');


		$fullarray = array();
		foreach ($responseArr as  $valueArr) {
			$returArr = array();

			if(!empty($valueArr)){

				$datetime = new DateTime($valueArr['created_date']);
			    $datetime->format('d, M Y g:i:s A');

			    $new_timezone = new DateTimeZone('Asia/Karachi');
			    $datetime->setTimezone($new_timezone);
			    $formated_date_time = $datetime->format('d, M Y g:i:s A');
				 
				$returArr['_id'] = $valueArr['_id'];
				$returArr['price'] = $valueArr['price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['type'] = $valueArr['type'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['created_date'] = $formated_date_time;
				
			}
			
			$fullarray[]= $returArr;
		}

		$sort = array();
		foreach($fullarray as $k=>$v) {
		    $sort['price'][$k] = $v['price'];
		}
		array_multisort($sort['price'], SORT_DESC, $fullarray);

		// echo "<pre>";
		// print_r($fullarray);
		// exit;


		return $fullarray;
		
	}//end get_market_sell_depth


	//get_market_history
	public function get_market_history(){
		
		$this->mongo_db->where(array('coin'=> 'BNBBTC'));
		
		$this->mongo_db->limit(20);
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('market_trades');


		$fullarray = array();
		foreach ($responseArr as  $valueArr) {
			$returArr = array();

			if(!empty($valueArr)){

				$created_date = date('Y-m-d G:i:s', $valueArr['created_date']);

		        $datetime = new DateTime($created_date);
		        $datetime->format('Y-m-d g:i:s A');

		        $new_timezone = new DateTimeZone('Asia/Karachi');
		        $datetime->setTimezone($new_timezone);
		        $formated_date_time =  $datetime->format('Y-m-d g:i:s A');
					 
				$returArr['_id'] = $valueArr['_id'];
				$returArr['price'] = $valueArr['price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['maker'] = $valueArr['maker'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['created_date'] = $formated_date_time;
				
			}
			
			$fullarray[]= $returArr;
		}

		
		return $fullarray;
		
	}//end get_market_history
	

	
}
?>