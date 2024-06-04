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
	public function get_market_buy_depth($market_value=''){


		if(isset($_GET['market_value']) && $_GET['market_value'] !="" && $market_value ==""){

			$market_value = $_GET['market_value'];

		}elseif($market_value !=""){

			$market_value = $market_value;

		}else{

			//Get Market Prices
			$this->mongo_db->where(array('coin'=> 'BNBBTC'));
			$this->mongo_db->limit(1);
			$this->mongo_db->sort(array('_id'=> 'desc'));
			$responseArr = $this->mongo_db->get('market_prices');

			foreach ($responseArr as  $valueArr) {
				if(!empty($valueArr)){
					$market_value = $valueArr['price'];
				}
			}

		}
		

		///////////////////////////////////////
		$db = $this->mongo_db->customQuery();
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
		      
		    array('$sort'=>array('created_date'=>-1)),
		    // array('$sort'=>array('price'=>1)),
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
		    array('$limit'=>20),
		);

		$allow = array('allowDiskUse'=>true);
		$responseArr = $db->market_depth->aggregate($pipeline,$allow);
		
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

		$sort = array();
		foreach($fullarray as $k=>$v) {
		    $sort['price'][$k] = $v['price'];
		}
		array_multisort($sort['price'], SORT_DESC, $fullarray);
		

		$data['market_value'] = $market_value;
		$data['fullarray'] = $fullarray;

		return $data;
		
	}//end get_market_buy_depth



	//get_market_sell_depth
	public function get_market_sell_depth($market_value=''){

		if(isset($_GET['market_value']) && $_GET['market_value'] !="" && $market_value ==""){

			$market_value = $_GET['market_value'];

		}elseif($market_value !=""){

			$market_value = $market_value;

		}else{

			//Get Market Prices
			$this->mongo_db->where(array('coin'=> 'BNBBTC'));
			$this->mongo_db->limit(1);
			$this->mongo_db->sort(array('_id'=> 'desc'));
			$responseArr = $this->mongo_db->get('market_prices');

			foreach ($responseArr as  $valueArr) {
				if(!empty($valueArr)){
					$market_value = $valueArr['price'];
				}
			}

		}
		

		///////////////////////////////////////
		$db = $this->mongo_db->customQuery();
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
		        	'type'=>'bid',
		            'price' => array('$lte'=>$priceAsk)
		        )
		       ),
		      
		    array('$sort'=>array('created_date'=>-1)),
		    // array('$sort'=>array('price'=>1)),
		    array('$group' => array(
		       '_id' => array('price' => '$price'),
		       'quantity'    => array('$first' => '$quantity'),
		       'type'    => array('$first' => '$type'),
		       'coin'    => array('$first' => '$coin'),
		       'created_date'    => array('$first' => '$created_date'),
		       'price'    => array('$first' => '$price'),
		       ),
		      
		      ),
		    array('$sort'=>array('price'=>-1)),
		    array('$limit'=>20),
		);

		$allow = array('allowDiskUse'=>true);
		$responseArr = $db->market_depth->aggregate($pipeline,$allow);
		
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
		

		$data['market_value'] = $market_value;
		$data['fullarray'] = $fullarray;

		return $data;
		
	}//end get_market_sell_depth





	//get_market_buy_depth_chart
	public function get_market_buy_depth_chart($market_value=''){


		if(isset($_GET['market_value']) && $_GET['market_value'] !="" && $market_value ==""){

			$market_value = $_GET['market_value'];

		}elseif($market_value !=""){

			$market_value = $market_value;

		}else{

			//Get Market Prices
			$this->mongo_db->where(array('coin'=> 'BNBBTC'));
			$this->mongo_db->limit(1);
			$this->mongo_db->sort(array('_id'=> 'desc'));
			$responseArr = $this->mongo_db->get('market_prices');

			foreach ($responseArr as  $valueArr) {
				if(!empty($valueArr)){
					$market_value = $valueArr['price'];
				}
			}

		}
		

		///////////////////////////////////////
		$db = $this->mongo_db->customQuery();
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
		      
		    array('$sort'=>array('created_date'=>-1)),
		    // array('$sort'=>array('price'=>1)),
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
		
		$fullarray = array();
		$big_quantity = 0;
		$depth_big_quantity =0;
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


				///////////////////////////////////////////////////////
				$depth_price = $valueArr['price'];
				$this->mongo_db->where(array('type'=>'ask','coin'=>'BNBBTC','price' =>$depth_price));
				$depth_responseArr = $this->mongo_db->get('market_depth');
				
				$depth_buy_quantity = 0;
				foreach ($depth_responseArr as  $depth_valueArr) {
					if(!empty($depth_valueArr)){
						
						$depth_buy_quantity += $depth_valueArr['quantity'];
					}
				}
				if($depth_buy_quantity > $depth_big_quantity){
					$depth_big_quantity = $depth_buy_quantity;
				}

				$returArr['depth_buy_quantity'] = $depth_buy_quantity;
				///////////////////////////////////////////////////////


				///////////////////////////////////////////////////////
				$depth_price = $valueArr['price'];
				$this->mongo_db->where(array('type'=>'bid','coin'=>'BNBBTC','price' =>$depth_price));
				$depth_responseArr = $this->mongo_db->get('market_depth');
				
				$depth_sell_quantity = 0;
				foreach ($depth_responseArr as  $depth_valueArr) {
					if(!empty($depth_valueArr)){
						
						$depth_sell_quantity += $depth_valueArr['quantity'];
					}
				}
				if($depth_sell_quantity > $depth_big_quantity){
					$depth_big_quantity = $depth_sell_quantity;
				}

				$returArr['depth_sell_quantity'] = $depth_sell_quantity;
				///////////////////////////////////////////////////////


				$priceee = $valueArr['price'].'0';
				$this->mongo_db->where(array('maker'=> 'true', 'coin'=> 'BNBBTC', 'price' => $priceee));
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


				$priceee = $valueArr['price'].'0';
				$this->mongo_db->where(array('maker'=> 'false', 'coin'=> 'BNBBTC', 'price' => $priceee));
				$responseArr2222 = $this->mongo_db->get('market_trades');

				//////////////
				$sell_quantity = 0;
				foreach ($responseArr2222 as  $valueArr222) {

					if(!empty($valueArr222)){
						
						$sell_quantity += $valueArr222['quantity'];
					}
					
				}

				if($sell_quantity > $big_quantity){
					$big_quantity = $sell_quantity;
				}

				$returArr['sell_quantity'] = $sell_quantity;
				////////////

			}
			
			$fullarray[]= $returArr;
		}

		$sort = array();
		foreach($fullarray as $k=>$v) {
		    $sort['price'][$k] = $v['price'];
		}
		array_multisort($sort['price'], SORT_DESC, $fullarray);
		 

		$data['market_value'] = $market_value;
		$data['fullarray'] = $fullarray;
		$data['buy_big_quantity'] = $big_quantity;
		$data['depth_buy_big_quantity'] = $depth_big_quantity;

		return $data;
		
	}//end get_market_buy_depth_chart



	//get_market_sell_depth_chart
	public function get_market_sell_depth_chart($market_value=''){

		if(isset($_GET['market_value']) && $_GET['market_value'] !="" && $market_value ==""){

			$market_value = $_GET['market_value'];

		}elseif($market_value !=""){

			$market_value = $market_value;

		}else{

			//Get Market Prices
			$this->mongo_db->where(array('coin'=> 'BNBBTC'));
			$this->mongo_db->limit(1);
			$this->mongo_db->sort(array('_id'=> 'desc'));
			$responseArr = $this->mongo_db->get('market_prices');

			foreach ($responseArr as  $valueArr) {
				if(!empty($valueArr)){
					$market_value = $valueArr['price'];
				}
			}

		}
		

		///////////////////////////////////////
		$db = $this->mongo_db->customQuery();
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
		        	'type'=>'bid',
		            'price' => array('$lte'=>$priceAsk)
		        )
		       ),
		      
		    array('$sort'=>array('created_date'=>-1)),
		    // array('$sort'=>array('price'=>1)),
		    array('$group' => array(
		       '_id' => array('price' => '$price'),
		       'quantity'    => array('$first' => '$quantity'),
		       'type'    => array('$first' => '$type'),
		       'coin'    => array('$first' => '$coin'),
		       'created_date'    => array('$first' => '$created_date'),
		       'price'    => array('$first' => '$price'),
		       ),
		      
		      ),
		    array('$sort'=>array('price'=>-1)),
		    array('$limit'=>50),
		);

		$allow = array('allowDiskUse'=>true);
		$responseArr = $db->market_depth->aggregate($pipeline,$allow);
		
		$fullarray = array();
		$big_quantity = 0;
		$depth_big_quantity = 0;
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


				///////////////////////////////////////////////////////
				$depth_price = $valueArr['price'];
				$this->mongo_db->where(array('type'=>'ask','coin'=>'BNBBTC','price' =>$depth_price));
				$depth_responseArr = $this->mongo_db->get('market_depth');
				
				$depth_buy_quantity = 0;
				foreach ($depth_responseArr as  $depth_valueArr) {
					if(!empty($depth_valueArr)){
						
						$depth_buy_quantity += $depth_valueArr['quantity'];
					}
				}
				if($depth_buy_quantity > $depth_big_quantity){
					$depth_big_quantity = $depth_buy_quantity;
				}

				$returArr['depth_buy_quantity'] = $depth_buy_quantity;
				///////////////////////////////////////////////////////


				///////////////////////////////////////////////////////
				$depth_price = $valueArr['price'];
				$this->mongo_db->where(array('type'=>'bid','coin'=>'BNBBTC','price' =>$depth_price));
				$depth_responseArr = $this->mongo_db->get('market_depth');
				
				$depth_sell_quantity = 0;
				foreach ($depth_responseArr as  $depth_valueArr) {
					if(!empty($depth_valueArr)){
						
						$depth_sell_quantity += $depth_valueArr['quantity'];
					}
				}
				if($depth_sell_quantity > $depth_big_quantity){
					$depth_big_quantity = $depth_sell_quantity;
				}

				$returArr['depth_sell_quantity'] = $depth_sell_quantity;
				///////////////////////////////////////////////////////



				$priceee = $valueArr['price'].'0';
				$this->mongo_db->where(array('maker'=> 'true', 'coin'=> 'BNBBTC', 'price' => $priceee));
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


				$priceee = $valueArr['price'].'0';
				$this->mongo_db->where(array('maker'=> 'false', 'coin'=> 'BNBBTC', 'price' => $priceee));
				$responseArr2222 = $this->mongo_db->get('market_trades');

				//////////////
				$sell_quantity = 0;
				foreach ($responseArr2222 as  $valueArr222) {

					if(!empty($valueArr222)){
						
						$sell_quantity += $valueArr222['quantity'];
					}
					
				}

				if($sell_quantity > $big_quantity){
					$big_quantity = $sell_quantity;
				}

				$returArr['sell_quantity'] = $sell_quantity;
				////////////

			}
			
			$fullarray[]= $returArr;
		}


		$sort = array();
		foreach($fullarray as $k=>$v) {
		    $sort['price'][$k] = $v['price'];
		}
		array_multisort($sort['price'], SORT_DESC, $fullarray);


		$data['market_value'] = $market_value;
		$data['fullarray'] = $fullarray;
		$data['sell_big_quantity'] = $big_quantity;
		$data['depth_sell_big_quantity'] = $depth_big_quantity;

		return $data;
		
	}//end get_market_sell_depth_chart

	

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



	//add_zone
	public function add_zone($data){

		extract($data);

		$created_date = date('Y-m-d G:i:s');
		$admin_id = $this->session->userdata('admin_id');

		$ins_data = array(
				'start_value' => (float)$start_value,
				'end_value' => (float)$end_value,
				'type' => $type,
				'admin_id' => $admin_id,
				'created_date' => $this->mongo_db->converToMongodttime($created_date)
			);
		
		//Insert data in mongoTable 
	    $this->mongo_db->insert('chart_target_zones',$ins_data);

	    return true;

	}//end add_zone


	//edit_zone
	public function edit_zone($data){

		extract($data);

		$created_date = date('Y-m-d G:i:s');
		$admin_id = $this->session->userdata('admin_id');

		$upd_data = array(
				'start_value' => (float)$start_value,
				'end_value' => (float)$end_value,
				'type' => $type,
				'admin_id' => $admin_id,
			);

		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->set($upd_data);

		//Update data in mongoTable 
	    $this->mongo_db->update('chart_target_zones');

	    return true;

	}//end edit_zone


	//delete_zone
	public function delete_zone($id){

		$this->mongo_db->where(array('_id'=> $id));
		
		//Delete data in mongoTable 
	    $this->mongo_db->delete('chart_target_zones');

	    return true;

	}//end delete_zone


	//get_chart_target_zones
	public function get_chart_target_zones(){
		
		$admin_id = $this->session->userdata('admin_id');

		$this->mongo_db->where(array('admin_id'=> $admin_id));
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('chart_target_zones');


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
				$returArr['start_value'] = $valueArr['start_value'];
				$returArr['end_value'] = $valueArr['end_value'];
				$returArr['type'] = $valueArr['type'];
				$returArr['created_date'] = $formated_date_time;
				
			}
			
			$fullarray[]= $returArr;
		}


		return $fullarray;

	}//end get_chart_target_zones



	//get_zone
	public function get_zone($id){
		
		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('chart_target_zones');

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

		        $lenth =  strlen(substr(strrchr($valueArr['start_value'], "."), 1));
                if($lenth==6){
                    $start_value = $valueArr['start_value'].'0';
                }else{

                    $start_value = $valueArr['start_value'];
                }

                $lenth22 =  strlen(substr(strrchr($valueArr['end_value'], "."), 1));
                if($lenth22==6){
                    $end_value = $valueArr['end_value'].'0';
                }else{

                    $end_value = $valueArr['end_value'];
                }
				 
				$returArr['_id'] = $valueArr['_id'];
				$returArr['start_value'] = $start_value;
				$returArr['end_value'] = $end_value;
				$returArr['type'] = $valueArr['type'];
				$returArr['created_date'] = $formated_date_time;
				
			}
		}

		return $returArr;

	}//end get_zone



	//get_zone_values
	public function get_zone_values($market_value){

        $priceAsk = (float)$market_value;
        $db = $this->mongo_db->customQuery();

        $params = array(
        			'start_value'=>array(
        							'$gte'=> $priceAsk
        							),
        			'end_value'=>array(
        							'$lte'=> $priceAsk
        							)
        		);

		$res = $db->chart_target_zones->find($params);
		
		foreach ($res as  $valueArr) {
			if(!empty($valueArr)){

				$lenth =  strlen(substr(strrchr($valueArr['start_value'], "."), 1));
                if($lenth==6){
                    $start_value = $valueArr['start_value'].'0';
                }else{

                    $start_value = $valueArr['start_value'];
                }

                $lenth22 =  strlen(substr(strrchr($valueArr['end_value'], "."), 1));
                if($lenth22==6){
                    $end_value = $valueArr['end_value'].'0';
                }else{

                    $end_value = $valueArr['end_value'];
                }
				
				$zone_id = $valueArr['_id'];
				$zone_start_value = (float)$start_value;
				$zone_end_value = (float)$end_value;
				$zone_type = $valueArr['type'];
			}
		}

		if($zone_type =='sell'){
			$zone_type2 = 'bid';
		}else{
			$zone_type2 = 'ask';
		}


		//////////////////////////
		///////////////////////////////////////
	
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
		        	'type'=>$zone_type2,
		            'price' => array('$lte'=> $zone_start_value,'$gte'=> $zone_end_value),
		            
		        )
		       ),
		      
		    array('$sort'=>array('created_date'=>-1)),
		    // array('$sort'=>array('price'=>1)),
		    array('$group' => array(
		       '_id' => array('price' => '$price'),
		       'quantity'    => array('$first' => '$quantity'),
		       'type'    => array('$first' => '$type'),
		       'coin'    => array('$first' => '$coin'),
		       'created_date'    => array('$first' => '$created_date'),
		       'price'    => array('$first' => '$price'),
		       ),
		      
		      ),
		    array('$sort'=>array('price'=>1))
		);

		$allow = array('allowDiskUse'=>true);
		$responseArr = $db->market_depth->aggregate($pipeline,$allow);
		
		$fullarray = array();
		$buy_quantity = 0;
		$sell_quantity = 0;
		foreach ($responseArr as  $valueArr) {
			if(!empty($valueArr)){

				$priceee = $valueArr['price'].'0';
				// $this->mongo_db->where(array('maker'=> 'true', 'coin'=> 'BNBBTC', 'price' => $priceee));
				// $responseArr2222 = $this->mongo_db->get('market_trades');


				$created_datetime = date('Y-m-d G:i:s');
		        $orig_date = new DateTime($created_datetime);
		        $orig_date = $orig_date->getTimestamp(); 
		        $start_date = new MongoDB\BSON\UTCDateTime($orig_date*1000);


		        $created_datetime22 = date('Y-m-d G:i:s', strtotime("-1 min"));
		        $orig_date22 = new DateTime($created_datetime22);
		        $orig_date22 = $orig_date22->getTimestamp(); 
		        $end_date = new MongoDB\BSON\UTCDateTime($orig_date22*1000);


				//$priceAsk = (float)$market_value;
		        $db = $this->mongo_db->customQuery();

		        $params = array(
		        			'created_date' => array('$lte'=> $start_date,'$gte'=> $end_date),
		        			'maker'=> 'true',
		        			'coin'=> 'BNBBTC',
		        			'price' => $priceee
		        			);
		       
				$responseArr2222 = $db->market_trades->find($params);


				//////////////
				foreach ($responseArr2222 as  $valueArr222) {
					if(!empty($valueArr222)){
						$buy_quantity += $valueArr222['quantity'];
					}
				}


				$priceee22 = $valueArr['price'].'0';
				// $this->mongo_db->where(array('maker'=> 'false', 'coin'=> 'BNBBTC', 'price' => $priceee22));
				// $responseArr3333 = $this->mongo_db->get('market_trades');


				$created_datetime = date('Y-m-d G:i:s');
		        $orig_date = new DateTime($created_datetime);
		        $orig_date = $orig_date->getTimestamp(); 
		        $start_date = new MongoDB\BSON\UTCDateTime($orig_date*1000);


		        $created_datetime22 = date('Y-m-d G:i:s', strtotime("-1 min"));
		        $orig_date22 = new DateTime($created_datetime22);
		        $orig_date22 = $orig_date22->getTimestamp(); 
		        $end_date = new MongoDB\BSON\UTCDateTime($orig_date22*1000);


				//$priceAsk = (float)$market_value;
		        $db = $this->mongo_db->customQuery();

		        $params = array(
		        			'created_date' => array('$lte'=> $start_date,'$gte'=> $end_date),
		        			'maker'=> 'false',
		        			'coin'=> 'BNBBTC',
		        			'price' => $priceee22
		        			);
		       
				$responseArr3333 = $db->market_trades->find($params);


				/////////////
				foreach ($responseArr3333 as  $valueArr3333) {
					if(!empty($valueArr3333)){
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

	}//end get_zone_values



	//add_order
	public function add_order($data){

		extract($data);

		$created_date = date('Y-m-d G:i:s');
		$admin_id = $this->session->userdata('admin_id');

		if($profit_type =='percentage'){
			$sell_price = $purchased_price * $sell_profit_percent;
			$sell_price = $sell_price / 100;
			$sell_price = $sell_price + $purchased_price;
		}else{
			$sell_price = $sell_profit_price;
		}

		$ins_data = array(
				'purchased_price' => $purchased_price,
				'quantity' => $quantity,
				'profit_type' => $profit_type,
				'sell_profit_percent' => $sell_profit_percent,
				'sell_profit_price' => $sell_profit_price,
				'sell_price' => $sell_price,
				'status' => 'new',
				'admin_id' => $admin_id,
				'created_date' => $this->mongo_db->converToMongodttime($created_date)
			);
		
		//Insert data in mongoTable 
	    $this->mongo_db->insert('orders',$ins_data);

	    return true;

	}//end add_order


	//edit_order
	public function edit_order($data){

		extract($data);

		$created_date = date('Y-m-d G:i:s');
		$admin_id = $this->session->userdata('admin_id');

		if($profit_type =='percentage'){
			$sell_price = $purchased_price * $sell_profit_percent;
			$sell_price = $sell_price / 100;
			$sell_price = $sell_price + $purchased_price;
		}else{
			$sell_price = $sell_profit_price;
		}
		

		$upd_data = array(
				'purchased_price' => $purchased_price,
				'quantity' => $quantity,
				'profit_type' => $profit_type,
				'sell_profit_percent' => $sell_profit_percent,
				'sell_profit_price' => $sell_profit_price,
				'sell_price' => $sell_price,
				'admin_id' => $admin_id,
			);

		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->set($upd_data);

		//Update data in mongoTable 
	    $this->mongo_db->update('orders');

	    return true;

	}//end edit_order


	//delete_order
	public function delete_order($id){

		$this->mongo_db->where(array('_id'=> $id));
		
		//Delete data in mongoTable 
	    $this->mongo_db->delete('orders');

	    return true;

	}//end delete_order


	//get_order
	public function get_order($id){
		
		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('orders');

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
				$returArr['purchased_price'] = $valueArr['purchased_price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['profit_type'] = $valueArr['profit_type'];
				$returArr['sell_profit_percent'] = $valueArr['sell_profit_percent'];
				$returArr['sell_profit_price'] = $valueArr['sell_profit_price'];
				$returArr['sell_price'] = number_format($valueArr['sell_price'], 7, '.', '');
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['status'] = $valueArr['status'];
				$returArr['created_date'] = $formated_date_time;
			}
		}

		return $returArr;

	}//end get_order


	//get_orders
	public function get_orders(){

		$admin_id = $this->session->userdata('admin_id');

		$this->mongo_db->where(array('admin_id'=> $admin_id));
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('orders');

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
				$returArr['purchased_price'] = $valueArr['purchased_price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['profit_type'] = $valueArr['profit_type'];
				$returArr['sell_profit_percent'] = $valueArr['sell_profit_percent'];
				$returArr['sell_profit_price'] = $valueArr['sell_profit_price'];
				$returArr['sell_price'] = number_format($valueArr['sell_price'], 7, '.', '');
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['status'] = $valueArr['status'];
				$returArr['created_date'] = $formated_date_time;
			}
			
			$fullarray[]= $returArr;
		}

		return $fullarray;

	}//end get_orders


	//get_sell_active_orders
	public function get_sell_active_orders(){

		$admin_id = $this->session->userdata('admin_id');
		
		$this->mongo_db->where(array('admin_id'=> $admin_id, 'status'=> 'new'));
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('orders');

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
				$returArr['purchased_price'] = $valueArr['purchased_price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['profit_type'] = $valueArr['profit_type'];
				$returArr['sell_profit_percent'] = $valueArr['sell_profit_percent'];
				$returArr['sell_profit_price'] = $valueArr['sell_profit_price'];
				$returArr['sell_price'] = number_format($valueArr['sell_price'], 7, '.', '');
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['status'] = $valueArr['status'];
				$returArr['created_date'] = $formated_date_time;
			}
			
			$fullarray[]= $returArr;
		}

		return $fullarray;

	}//end get_sell_active_orders


	//add_buy_order
	public function add_buy_order($data){

		extract($data);

		$created_date = date('Y-m-d G:i:s');
		$admin_id = $this->session->userdata('admin_id');

		$ins_data = array(
				'price' => $price,
				'quantity' => $quantity,
				'status' => 'new',
				'admin_id' => $admin_id,
				'created_date' => $this->mongo_db->converToMongodttime($created_date)
			);
		
		//Insert data in mongoTable 
	    $this->mongo_db->insert('buy_orders',$ins_data);

	    return true;

	}//end add_buy_order


	//edit_buy_order
	public function edit_buy_order($data){

		extract($data);

		$created_date = date('Y-m-d G:i:s');
		$admin_id = $this->session->userdata('admin_id');

		$upd_data = array(
				'price' => $price,
				'quantity' => $quantity,
				'admin_id' => $admin_id,
			);

		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->set($upd_data);

		//Update data in mongoTable 
	    $this->mongo_db->update('buy_orders');

	    return true;

	}//end edit_buy_order


	//delete_buy_order
	public function delete_buy_order($id){

		$this->mongo_db->where(array('_id'=> $id));
		
		//Delete data in mongoTable 
	    $this->mongo_db->delete('buy_orders');

	    return true;

	}//end delete_buy_order


	//get_buy_order
	public function get_buy_order($id){
		
		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('buy_orders');

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
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['status'] = $valueArr['status'];
				$returArr['created_date'] = $formated_date_time;
			}
		}

		return $returArr;

	}//end get_buy_order


	//get_buy_orders
	public function get_buy_orders(){

		$admin_id = $this->session->userdata('admin_id');
		
		$this->mongo_db->where(array('admin_id'=> $admin_id));
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('buy_orders');

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
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['status'] = $valueArr['status'];
				$returArr['created_date'] = $formated_date_time;
			}
			
			$fullarray[]= $returArr;
		}

		return $fullarray;

	}//end get_buy_orders


	//get_buy_active_orders
	public function get_buy_active_orders(){

		$admin_id = $this->session->userdata('admin_id');
		
		$this->mongo_db->where(array('admin_id'=> $admin_id, 'status'=> 'new'));
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('buy_orders');

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
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['status'] = $valueArr['status'];
				$returArr['created_date'] = $formated_date_time;
			}
			
			$fullarray[]= $returArr;
		}

		return $fullarray;

	}//end get_buy_active_orders


	//sell_order
	public function sell_order($id,$market_value){
		
		$upd_data = array(
				'status' => 'sell',
				'market_value' => $market_value,
			);

		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->set($upd_data);

		//Update data in mongoTable 
	    $this->mongo_db->update('orders');

	    return true;

	}//end sell_order


	//sell_all_orders
	public function sell_all_orders(){

		//Get Market Price
		$this->mongo_db->where(array('coin'=> 'BNBBTC'));
		$this->mongo_db->limit(1);
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('market_prices');

		foreach ($responseArr as  $valueArr) {
			if(!empty($valueArr)){
				$market_value = $valueArr['price'];
			}
		}


		$orders_arr = $this->get_sell_active_orders();
		if(count($orders_arr)>0){
            foreach ($orders_arr as $key=>$value) {   

            	$id = $value['_id'];

            	//Update Order
            	$upd_data = array(
						'status' => 'sell',
						'market_value' => $market_value,
					);

				$this->mongo_db->where(array('_id'=> $id));
				$this->mongo_db->set($upd_data);

				//Update data in mongoTable 
			    $this->mongo_db->update('orders');
            }
        }
		
	    return true;

	}//end sell_all_orders



	//buy_order
	public function buy_order($id,$market_value){
		
		$upd_data = array(
				'status' => 'buy',
				'market_value' => $market_value,
			);

		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->set($upd_data);

		//Update data in mongoTable 
	    $this->mongo_db->update('buy_orders');

	    return true;

	}//end buy_order


	//buy_all_orders
	public function buy_all_orders(){

		//Get Market Price
		$this->mongo_db->where(array('coin'=> 'BNBBTC'));
		$this->mongo_db->limit(1);
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('market_prices');

		foreach ($responseArr as  $valueArr) {
			if(!empty($valueArr)){
				$market_value = $valueArr['price'];
			}
		}


		$orders_arr = $this->get_buy_active_orders();
		if(count($orders_arr)>0){
            foreach ($orders_arr as $key=>$value) {   

            	$id = $value['_id'];
            	
            	//Update Order
            	$upd_data = array(
						'status' => 'buy',
						'market_value' => $market_value,
					);

				$this->mongo_db->where(array('_id'=> $id));
				$this->mongo_db->set($upd_data);

				//Update data in mongoTable 
			    $this->mongo_db->update('buy_orders');
            }
        }
		
	    return true;

	}//end buy_all_orders
	

	
}
?>