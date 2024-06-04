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

		$global_symbol = $this->session->userdata('global_symbol');

		if(isset($_GET['market_value']) && $_GET['market_value'] !="" && $market_value ==""){

			$market_value = $_GET['market_value'];

		}elseif($market_value !=""){

			$market_value = $market_value;

		}else{

			//Get Market Prices
			$market_value = $this->get_market_value($global_symbol);
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
		        	'coin'=> $global_symbol,
		        	'type'=> 'ask',
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

		$global_symbol = $this->session->userdata('global_symbol');

		if(isset($_GET['market_value']) && $_GET['market_value'] !="" && $market_value ==""){

			$market_value = $_GET['market_value'];

		}elseif($market_value !=""){

			$market_value = $market_value;

		}else{

			//Get Market Prices
			$market_value = $this->get_market_value($global_symbol);
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
		        	'coin'=> $global_symbol,
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


		$data['market_value'] = $market_value;
		$data['fullarray'] = $fullarray;

		return $data;
		
	}//end get_market_sell_depth





	//get_market_buy_depth_chart
	public function get_market_buy_depth_chart($market_value=''){

		$global_symbol = $this->session->userdata('global_symbol');

		if(isset($_GET['market_value']) && $_GET['market_value'] !="" && $market_value ==""){

			$market_value = $_GET['market_value'];

		}elseif($market_value !=""){

			$market_value = $market_value;

		}else{

			//Get Market Prices
			$market_value = $this->get_market_value($global_symbol);
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
		        	'coin'=> $global_symbol,
		        	'type'=>'ask',
		            'price' => array('$gte'=>$priceAsk)
		        )
		    ),
		    array('$sort'=>array('created_date'=>-1)),
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
				$this->mongo_db->where(array('type'=>'ask','coin'=>$global_symbol,'price' =>$depth_price));
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
				$this->mongo_db->where(array('type'=>'bid','coin'=>$global_symbol,'price' =>$depth_price));
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


				$priceee = num($valueArr['price']);
				$this->mongo_db->where(array('maker'=> 'true', 'coin'=> $global_symbol, 'price' => $priceee));
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


				$priceee = num($valueArr['price']);
				$this->mongo_db->where(array('maker'=> 'false', 'coin'=> $global_symbol, 'price' => $priceee));
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

		$global_symbol = $this->session->userdata('global_symbol');

		if(isset($_GET['market_value']) && $_GET['market_value'] !="" && $market_value ==""){

			$market_value = $_GET['market_value'];

		}elseif($market_value !=""){

			$market_value = $market_value;

		}else{

			//Get Market Prices
			$market_value = $this->get_market_value($global_symbol);

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
		        	'coin'=> $global_symbol,
		        	'type'=>'bid',
		            'price' => array('$lte'=>$priceAsk)
		        )
		    ),
		    array('$sort'=>array('created_date'=>-1)),
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
				$this->mongo_db->where(array('type'=>'ask','coin'=>$global_symbol,'price' =>$depth_price));
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
				$this->mongo_db->where(array('type'=>'bid','coin'=>$global_symbol,'price' =>$depth_price));
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



				$priceee = num($valueArr['price']);
				$this->mongo_db->where(array('maker'=> 'true', 'coin'=> $global_symbol, 'price' => $priceee));
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


				$priceee = num($valueArr['price']);
				$this->mongo_db->where(array('maker'=> 'false', 'coin'=> $global_symbol, 'price' => $priceee));
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

		$global_symbol = $this->session->userdata('global_symbol');
		
		$this->mongo_db->where(array('coin'=> $global_symbol));
		
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
		
		$start_date_arr = (explode("-",$start_date));
		$full_start_date = '';
		if(count($start_date_arr)>0){
			$full_start_date = $start_date_arr[0].' '.$start_date_arr[1];
		}
    	$str_date = $this->make_universak_date_time($full_start_date);


		$end_date_arr = (explode("-",$end_date));
		$full_end_date = '';
		if(count($end_date_arr)>0){
		 	$full_end_date = $end_date_arr[0].' '.$end_date_arr[1];
		}
	    $end_date = $this->make_universak_date_time($full_end_date);

		$ins_data = array(
				'start_value' => num((float)$start_value),
				'end_value' => num((float)$end_value),
				'type' => $type,
				'admin_id' => $admin_id,
				'start_date'=> $str_date,
				'end_date'=> $end_date,
				'coin' => $coin,
				'created_date' => $this->mongo_db->converToMongodttime($created_date)
			);
		
		//Insert data in mongoTable 
	    $this->mongo_db->insert('chart_target_zones',$ins_data);

	    return true;

	}//end add_zone

	
	public function make_universak_date_time($date){
		
		$orig_date = new DateTime($date);
		$orig_date->format('Y-m-d H:i:s');

		$orig_date->sub(new DateInterval('PT9H00M'));
		$orig_date->format('Y-m-d H:i:s');
		$orig_date = $orig_date->getTimestamp();

		return 	$str_date = new MongoDB\BSON\UTCDateTime($orig_date*1000);
	}


	//edit_zone
	public function edit_zone($data){

		extract($data);

		$created_date = date('Y-m-d G:i:s');
		$admin_id = $this->session->userdata('admin_id');
		
		$start_date_arr = (explode("-",$start_date));
		$full_start_date = '';
		if(count($start_date_arr)>0){
			$full_start_date = $start_date_arr[0].' '.$start_date_arr[1];
		}
    	$str_date =$this->make_universak_date_time($full_start_date);


		$end_date_arr = (explode("-",$end_date));
		$full_end_date = '';
		if(count($end_date_arr)>0){
		 	$full_end_date = $end_date_arr[0].' '.$end_date_arr[1];
		}
	    $end_date=$this->make_universak_date_time($full_end_date);


		$upd_data = array(
				'start_value' => num((float)$start_value),
				'end_value' => num((float)$end_value),
				'type' => $type,
				'admin_id' => $admin_id,
				'start_date'=> $str_date,
				'end_date'=> $end_date,
				'coin' => $coin,
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
			
	
			if(!empty($valueArr)){

				$datetime = $valueArr['created_date']->toDateTime();
		        $created_date = $datetime->format(DATE_RSS);

		        $datetime = new DateTime($created_date);
		        $datetime->format('Y-m-d g:i:s A');

		        $new_timezone = new DateTimeZone('Asia/Karachi');
		        $datetime->setTimezone($new_timezone);
		        $formated_date_time =  $datetime->format('Y-m-d g:i:s A');
				 
				$returArr['_id'] = $valueArr['_id'];
				$returArr['start_value'] = num($valueArr['start_value']);
				$returArr['end_value'] = num($valueArr['end_value']);
				$returArr['type'] = $valueArr['type'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['created_date'] = $formated_date_time;

				if($valueArr['start_date'] !=''){
					$returArr['start_date'] = $this->change_time_stamp_to_human_readible($valueArr['start_date'] );
				}else{
					$returArr['start_date'] = '';
				}


				if($valueArr['end_date'] !=''){
					$returArr['end_date'] = $this->change_time_stamp_to_human_readible($valueArr['end_date']);
				}else{
					$returArr['end_date'] = '';
				}

				
				
			}
			
			$fullarray[]= $returArr;
		}

		return $fullarray;

	}//end get_chart_target_zones


	public function  change_time_stamp_to_human_readible($send_date){

		if($send_date!=0){


		$datetime = $send_date->toDateTime();
		$created_date = $datetime->format(DATE_RSS);

		 $datetime = new DateTime($created_date);
		 $datetime->format('Y-m-d g:i:s A');
		 $new_timezone = new DateTimeZone('Asia/Karachi');
		 $datetime->setTimezone($new_timezone);
		 return $formated_date_time =  $datetime->format('Y-m-d g:i:s A');
		}else{
			return '';
		}
	}



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

                $start_value = num($valueArr['start_value']);
                $end_value = num($valueArr['end_value']);
				 
				$returArr['_id'] = $valueArr['_id'];
				$returArr['start_value'] = num($start_value);
				$returArr['end_value'] = num($end_value);
				$returArr['type'] = $valueArr['type'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['start_date'] = $this->change_time_stamp_to_specific_format($valueArr['start_date']);

				$returArr['end_date'] = $this->change_time_stamp_to_specific_format($valueArr['end_date']);
				$returArr['created_date'] = $formated_date_time;
			}
		}

		return $returArr;

	}//end get_zone



	public function  change_time_stamp_to_specific_format($send_date){

		if($send_date!=0){


		$datetime = $send_date->toDateTime();
		$created_date = $datetime->format(DATE_RSS);

		 $datetime = new DateTime($created_date);
		 $datetime->format('Y-m-d g:i:s A');
		 $new_timezone = new DateTimeZone('Asia/Karachi');
		 $datetime->setTimezone($new_timezone);
		 return $formated_date_time =  $datetime->format('d F Y - g:i A');
		}else{
			return '';
		}
	}



	//get_zone_values
	public function get_zone_values($market_value){

		$global_symbol = $this->session->userdata('global_symbol');

        $priceAsk = num((float)$market_value);
        $db = $this->mongo_db->customQuery();
      
        $params = array(
        			'start_value' => array('$gte'=> $priceAsk),
        			'end_value' => array('$lte'=> $priceAsk),
        			'coin'=> $global_symbol
        		   );

		$res = $db->chart_target_zones->find($params);
		
		foreach ($res as  $valueArr) {
			if(!empty($valueArr)){

                $start_value = num($valueArr['start_value']);
                $end_value = num($valueArr['end_value']);
				
				$zone_id = $valueArr['_id'];
				$zone_start_value = (float)$start_value;
				$zone_end_value =  (float)$end_value;
				$zone_type = $valueArr['type'];
			}
		}

		if($zone_type =='sell'){
			$zone_type2 = 'bid';
		}else{
			$zone_type2 = 'ask';
		}


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
		        	'coin'=>$global_symbol,
		        	'type'=>$zone_type2,
		            'price' => array('$lte'=> $zone_start_value,'$gte'=> $zone_end_value),
		            
		        )
		    ),
		    array('$sort'=>array('created_date'=>-1)),
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

				$priceee = num($valueArr['price']);

				$created_datetime = date('Y-m-d G:i:s');
		        $orig_date = new DateTime($created_datetime);
		        $orig_date = $orig_date->getTimestamp(); 
		        $start_date = new MongoDB\BSON\UTCDateTime($orig_date*1000);


		        $created_datetime22 = date('Y-m-d G:i:s', strtotime("-1 hour"));
		        $orig_date22 = new DateTime($created_datetime22);
		        $orig_date22 = $orig_date22->getTimestamp(); 
		        $end_date = new MongoDB\BSON\UTCDateTime($orig_date22*1000);

		        $db = $this->mongo_db->customQuery();
		        $params = array(
		        			'created_date' => array('$lte'=> $start_date,'$gte'=> $end_date),
		        			'maker'=> 'true',
		        			'coin'=> $global_symbol,
		        			'price' => $priceee
		        			);
		       
				$responseArr2222 = $db->market_trades->find($params);


				//////////////
				foreach ($responseArr2222 as  $valueArr222) {
					if(!empty($valueArr222)){
						$buy_quantity += $valueArr222['quantity'];
					}
				}


				$priceee22 = num($valueArr['price']);

				$created_datetime = date('Y-m-d G:i:s');
		        $orig_date = new DateTime($created_datetime);
		        $orig_date = $orig_date->getTimestamp(); 
		        $start_date = new MongoDB\BSON\UTCDateTime($orig_date*1000);


		        $created_datetime22 = date('Y-m-d G:i:s', strtotime("-1 hour"));
		        $orig_date22 = new DateTime($created_datetime22);
		        $orig_date22 = $orig_date22->getTimestamp(); 
		        $end_date = new MongoDB\BSON\UTCDateTime($orig_date22*1000);

		        $db = $this->mongo_db->customQuery();
		        $params = array(
		        			'created_date' => array('$lte'=> $start_date,'$gte'=> $end_date),
		        			'maker'=> 'false',
		        			'coin'=> $global_symbol,
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
		$global_symbol = $this->session->userdata('global_symbol');

		$is_submitted = 'no';
		$ins_data = array(
				'symbol' => $coin,
				'purchased_price' => $purchased_price,
				'quantity' => $quantity,
				'profit_type' => $profit_type,
				'order_type' => $order_type,
				'admin_id' => $admin_id,
				'buy_order_check' => $buy_order_check,
				'buy_order_id' => $buy_order_id,
				'buy_order_binance_id' => $buy_order_binance_id,
				'stop_loss' => $stop_loss,
				'loss_percentage' => $loss_percentage,
				'created_date' => $this->mongo_db->converToMongodttime($created_date)
			);

		if($profit_type =='percentage'){

			$sell_price = $purchased_price * $sell_profit_percent;
			$sell_price = $sell_price / 100;
			$sell_price = $sell_price + $purchased_price;
			$sell_price = number_format($sell_price, 8, '.', '');

			$ins_data['sell_profit_percent'] = $sell_profit_percent;
			$ins_data['sell_price'] = $sell_price;

		}else{

			$sell_price = $sell_profit_price;

			$ins_data['sell_profit_price'] = $sell_profit_price;
			$ins_data['sell_price'] = $sell_price;
		}
		

		if($trail_check !='')
		{
			$ins_data['trail_check'] = 'yes';
			$ins_data['trail_interval'] = $trail_interval;
			$ins_data['sell_trail_price'] = $sell_price;
			$ins_data['status'] = 'new';
		
		}else{

			$ins_data['trail_check'] = 'no';
			$ins_data['trail_interval'] = '0';
			$ins_data['sell_trail_price'] = '0';

			if($order_type =='limit_order'){

				//Submit Sell Limit Order to Binance
				$order = $this->binance_api->place_sell_limit_order($global_symbol,$quantity,$sell_price);

				if($order['orderId'] ==""){

					$order_arr = json_encode($order);
					$order_arr2 = json_decode($order_arr);

					$error_msg = $order_arr2->msg;
					return array('error' => $error_msg);

				}else{
					
					$ins_data['market_value'] = $sell_price;
					$ins_data['status'] = 'submitted';
					$ins_data['binance_order_id'] = $order['orderId'];
					$is_submitted = 'yes';
				}

			}else{

				$ins_data['status'] = 'new';
			}

		}
	
		//Insert data in mongoTable 
	    $order_id = $this->mongo_db->insert('orders',$ins_data);


	    if($buy_order_check =='yes'){

	    	//Update Buy Order
	    	$upd_data = array(
				'is_sell_order' => 'yes',
				'sell_order_id' => $order_id
			);

	    	$this->mongo_db->where(array('_id'=> $buy_order_id));
			$this->mongo_db->set($upd_data);

			//Update data in mongoTable 
		    $this->mongo_db->update('buy_orders');
	    }


	    //////////////////////////////////////////////////////////////////////////////
		////////////////////////////// Order History Log /////////////////////////////
		$log_msg = "Sell Order was Created";
    	$this->insert_order_history_log($buy_order_id,$log_msg,'sell_created',$admin_id);
    	////////////////////////////// End Order History Log /////////////////////////
    	//////////////////////////////////////////////////////////////////////////////


    	
    	//////////////////////////////////////////////////////////////////////////////
		////////////////////////////// Order History Log /////////////////////////////
		if($is_submitted == 'yes'){
			$log_msg = "Sell Order was Submitted to Binance";
	    	$this->insert_order_history_log($buy_order_id,$log_msg,'sell_submitted',$admin_id);
    	}
    	////////////////////////////// End Order History Log /////////////////////////
    	//////////////////////////////////////////////////////////////////////////////
	    

	    return true;

	}//end add_order


	//edit_order
	public function edit_order($data){

		extract($data);

		$order_arr = $this->get_order($id);
		

		if($order_arr['status'] =='new' || $order_arr['status'] =='error'){

			$created_date = date('Y-m-d G:i:s');
			$admin_id = $this->session->userdata('admin_id');

			$is_submitted = 'no';
			$upd_data = array(
					'symbol' => $coin,
					'purchased_price' => $purchased_price,
					'quantity' => $quantity,
					'profit_type' => $profit_type,
					'order_type' => $order_type,
					'admin_id' => $admin_id,
					'stop_loss' => $stop_loss,
					'loss_percentage' => $loss_percentage,
				);

			if($profit_type =='percentage'){

				$sell_price = $purchased_price * $sell_profit_percent;
				$sell_price = $sell_price / 100;
				$sell_price = $sell_price + $purchased_price;
				$sell_price = number_format($sell_price, 8, '.', '');

				$upd_data['sell_profit_percent'] = $sell_profit_percent;
				$upd_data['sell_price'] = $sell_price;

			}else{

				$sell_price = $sell_profit_price;

				$upd_data['sell_profit_percent'] = $sell_profit_percent;
				$upd_data['sell_price'] = $sell_price;
			}
			

			if($trail_check !='')
			{
				$upd_data['trail_check'] = 'yes';
				$upd_data['trail_interval'] = $trail_interval;
				$upd_data['sell_trail_price'] = $sell_price;
				$upd_data['status'] = 'new';
			}
			else
			{
				$upd_data['trail_check'] = 'no';
				$upd_data['trail_interval'] = '0';
				$upd_data['sell_trail_price'] = '0';

				if($order_type =='limit_order'){

					//Submit Sell Limit Order to Binance
					$order = $this->binance_api->place_sell_limit_order($global_symbol,$quantity,$sell_price);

					if($order['orderId'] ==""){

						$order_arr = json_encode($order);
						$order_arr2 = json_decode($order_arr);

						$error_msg = $order_arr2->msg;
						return array('error' => $error_msg);

					}else{
						
						$upd_data['market_value'] = $sell_price;
						$upd_data['status'] = 'submitted';
						$upd_data['binance_order_id'] = $order['orderId'];
						$is_submitted = 'yes';
					}

				}else{

					$upd_data['status'] = 'new';
				}

			}
			 

			$this->mongo_db->where(array('_id'=> $id));
			$this->mongo_db->set($upd_data);

			//Update data in mongoTable 
		    $this->mongo_db->update('orders');


		    ////////////////////////////// Update Auto Sell////////////////////////////
		    if($buy_order_id !=''){

		    	//Get Temp Sell Order Record
				$temp_sell_arr = $this->get_temp_sell_data($buy_order_id);

		    	if(count($temp_sell_arr)>0){

		    		$temp_sell_id = $temp_sell_arr['_id'];

		    		$upd_temp_data = array(
						'profit_type' => $profit_type,
						'profit_percent' => $sell_profit_percent,
						'profit_price' => $sell_price,
						'order_type' => $order_type,
						'trail_check' => $trail_check,
						'trail_interval' => $trail_interval,
						'stop_loss' => $stop_loss,
						'loss_percentage' => $loss_percentage,
					);

					$this->mongo_db->where(array('_id'=> $temp_sell_id));
					$this->mongo_db->set($upd_temp_data);

					//Update data in mongoTable 
				    $this->mongo_db->update('temp_sell_orders');

		    	}//End if temp data found

		    }
		    //////////////////////////////// End Update Auto Sell//////////////////////



		    //////////////////////////////////////////////////////////////////////////////
			////////////////////////////// Order History Log /////////////////////////////
			$log_msg = "Sell Order was Updated";
	    	$this->insert_order_history_log($buy_order_id,$log_msg,'sell_updated',$admin_id);
	    	////////////////////////////// End Order History Log /////////////////////////
	    	//////////////////////////////////////////////////////////////////////////////


	    	
	    	//////////////////////////////////////////////////////////////////////////////
			////////////////////////////// Order History Log /////////////////////////////
			if($is_submitted == 'yes'){
				$log_msg = "Sell Order was Submitted to Binance";
		    	$this->insert_order_history_log($buy_order_id,$log_msg,'sell_submitted',$admin_id);
	    	}
	    	////////////////////////////// End Order History Log /////////////////////////
	    	//////////////////////////////////////////////////////////////////////////////



		 	return true;
		     
		}else{

			return false;

		}//End if Order in New

	   

	}//end edit_order


	//update_trail_price
	public function update_trail_price($id,$sell_trail_price,$buy_order_id,$admin_id){

		$upd_data = array(
				'sell_trail_price' => $sell_trail_price
			);

		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->set($upd_data);

		//Update data in mongoTable 
	    $this->mongo_db->update('orders');


	    //////////////////////////////////////////////////////////////////////////////
		////////////////////////////// Order History Log /////////////////////////////
		$log_msg = "Sell Order Trail was Updated to <b>(".$sell_trail_price.")</b>";
    	$this->insert_order_history_log($buy_order_id,$log_msg,'sell_trail_updated',$admin_id);
    	////////////////////////////// End Order History Log /////////////////////////
    	//////////////////////////////////////////////////////////////////////////////


	    return true;

	}//end update_trail_price


	//update_trail_buy_price
	public function update_trail_buy_price($id,$buy_trail_price,$admin_id){

		$upd_data = array(
				'buy_trail_price' => $buy_trail_price
			);

		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->set($upd_data);

		//Update data in mongoTable 
	    $this->mongo_db->update('buy_orders');

	    //////////////////////////////////////////////////////////////////////////////
		////////////////////////////// Order History Log /////////////////////////////
		$log_msg = "Buy Order Trail was Updated to <b>(".$buy_trail_price.")</b>";
    	$this->insert_order_history_log($id,$log_msg,'buy_trail_updated',$admin_id);
    	////////////////////////////// End Order History Log /////////////////////////
    	//////////////////////////////////////////////////////////////////////////////

	    return true;

	}//end update_trail_buy_price


	//update_hightest_price
	public function update_hightest_price($id,$hightest_price){

		$upd_data = array(
				'hightest_price' => $hightest_price
			);

		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->set($upd_data);

		//Update data in mongoTable 
	    $this->mongo_db->update('orders');

	    return true;

	}//end update_hightest_price


	//delete_order
	public function delete_order($id,$order_id){

		// $this->mongo_db->where(array('_id'=> $id));
		
		// //Delete data in mongoTable 
	 	// $this->mongo_db->delete('orders');

	 	$admin_id = $this->session->userdata('admin_id');
	 	$global_symbol = $this->session->userdata('global_symbol');
		
		if($order_id !=""){

			//Binance Cancel Order
	 		$order = $this->binance_api->cancel_order($global_symbol,$order_id);
		}
	 	

		$upd_data = array(
				'status' => 'canceled'
			);


		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->set($upd_data);

		//Update data in mongoTable 
	    $this->mongo_db->update('orders');


	    //////////////////////////////////////////////////////////////////////////////
		////////////////////////////// Order History Log /////////////////////////////
		$log_msg = "Sell Order was Canceled";
    	$this->insert_order_history_log($id,$log_msg,'sell_canceled',$admin_id);
    	////////////////////////////// End Order History Log /////////////////////////
    	//////////////////////////////////////////////////////////////////////////////


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
				$returArr['symbol'] = $valueArr['symbol'];
				$returArr['binance_order_id'] = $valueArr['binance_order_id'];
				$returArr['purchased_price'] = $valueArr['purchased_price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['profit_type'] = $valueArr['profit_type'];
				$returArr['sell_profit_percent'] = $valueArr['sell_profit_percent'];
				$returArr['sell_profit_price'] = $valueArr['sell_profit_price'];
				$returArr['sell_price'] = $valueArr['sell_price'];
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['order_type'] = $valueArr['order_type'];
				$returArr['status'] = $valueArr['status'];
				$returArr['admin_id'] = $valueArr['admin_id'];
				$returArr['created_date'] = $formated_date_time;
				$returArr['trail_check'] = $valueArr['trail_check'];
				$returArr['trail_interval'] = $valueArr['trail_interval'];
				$returArr['sell_trail_price'] = $valueArr['sell_trail_price'];
				$returArr['buy_order_check'] = $valueArr['buy_order_check'];
				$returArr['buy_order_id'] = $valueArr['buy_order_id'];
				$returArr['buy_order_binance_id'] = $valueArr['buy_order_binance_id'];
				$returArr['stop_loss'] = $valueArr['stop_loss'];
				$returArr['loss_percentage'] = $valueArr['loss_percentage'];
			}
		}

		return $returArr;

	}//end get_order


	//get_orders
	public function get_orders(){

		$admin_id = $this->session->userdata('admin_id');

		//Check Filter Data
		$session_post_data = $this->session->userdata('filter-data');

		$search_array = array('admin_id'=> $admin_id);
		if($session_post_data['filter_coin'] !=""){

			$symbol = $session_post_data['filter_coin'];
			$search_array['symbol'] = $symbol;
		}
		if($session_post_data['filter_type'] !=""){

			$order_type = $session_post_data['filter_type'];
			$search_array['order_type'] = $order_type;
		}
		if($session_post_data['start_date'] !="" && $session_post_data['end_date'] !=""){

			$created_datetime = date('Y-m-d G:i:s', strtotime($session_post_data['start_date']));
	        $orig_date = new DateTime($created_datetime);
	        $orig_date = $orig_date->getTimestamp(); 
	        $start_date = new MongoDB\BSON\UTCDateTime($orig_date*1000);


	        $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_post_data['end_date']));
	        $orig_date22 = new DateTime($created_datetime22);
	        $orig_date22 = $orig_date22->getTimestamp(); 
	        $end_date = new MongoDB\BSON\UTCDateTime($orig_date22*1000);


			$order_type = $session_post_data['filter_type'];
			$search_array['created_date'] = array('$gte'=> $start_date,'$lte'=> $end_date);
		}


		$this->mongo_db->where($search_array);
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
				$returArr['symbol'] = $valueArr['symbol'];
				$returArr['binance_order_id'] = $valueArr['binance_order_id'];
				$returArr['purchased_price'] = $valueArr['purchased_price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['profit_type'] = $valueArr['profit_type'];
				$returArr['sell_profit_percent'] = $valueArr['sell_profit_percent'];
				$returArr['sell_profit_price'] = $valueArr['sell_profit_price'];
				$returArr['sell_price'] = $valueArr['sell_price'];
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['trail_check'] = $valueArr['trail_check'];
				$returArr['trail_interval'] = $valueArr['trail_interval'];
				$returArr['sell_trail_price'] = $valueArr['sell_trail_price'];
				$returArr['order_type'] = $valueArr['order_type'];
				$returArr['stop_loss'] = $valueArr['stop_loss'];
				$returArr['loss_percentage'] = $valueArr['loss_percentage'];
				$returArr['status'] = $valueArr['status'];
				$returArr['admin_id'] = $valueArr['admin_id'];
				$returArr['buy_order_id'] = $valueArr['buy_order_id'];
				$returArr['buy_order_binance_id'] = $valueArr['buy_order_binance_id'];
				$returArr['created_date'] = $formated_date_time;
			}
			
			$fullarray[]= $returArr;
		}


		return $fullarray;

	}//end get_orders


	//get_sell_active_orders
	public function get_sell_active_orders(){

		$admin_id = $this->session->userdata('admin_id');

		//Check Filter Data
		$session_post_data = $this->session->userdata('filter-data');

		$search_array = array('admin_id'=> $admin_id, 'status' =>'new');
		
		if($session_post_data['filter_coin'] !=""){

			$symbol = $session_post_data['filter_coin'];
			$search_array['symbol'] = $symbol;
		}
		if($session_post_data['filter_type'] !=""){

			$order_type = $session_post_data['filter_type'];
			$search_array['order_type'] = $order_type;
		}
		if($session_post_data['start_date'] !="" && $session_post_data['end_date'] !=""){

			$created_datetime = date('Y-m-d G:i:s', strtotime($session_post_data['start_date']));
	        $orig_date = new DateTime($created_datetime);
	        $orig_date = $orig_date->getTimestamp(); 
	        $start_date = new MongoDB\BSON\UTCDateTime($orig_date*1000);


	        $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_post_data['end_date']));
	        $orig_date22 = new DateTime($created_datetime22);
	        $orig_date22 = $orig_date22->getTimestamp(); 
	        $end_date = new MongoDB\BSON\UTCDateTime($orig_date22*1000);


			$order_type = $session_post_data['filter_type'];
			$search_array['created_date'] = array('$gte'=> $start_date,'$lte'=> $end_date);
		}


		$this->mongo_db->where($search_array);
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
				$returArr['symbol'] = $valueArr['symbol'];
				$returArr['binance_order_id'] = $valueArr['binance_order_id'];
				$returArr['purchased_price'] = $valueArr['purchased_price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['profit_type'] = $valueArr['profit_type'];
				$returArr['sell_profit_percent'] = $valueArr['sell_profit_percent'];
				$returArr['sell_profit_price'] = $valueArr['sell_profit_price'];
				$returArr['sell_price'] = number_format($valueArr['sell_price'], 8, '.', '');
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['trail_check'] = $valueArr['trail_check'];
				$returArr['trail_interval'] = $valueArr['trail_interval'];
				$returArr['sell_trail_price'] = $valueArr['sell_trail_price'];
				$returArr['stop_loss'] = $valueArr['stop_loss'];
				$returArr['loss_percentage'] = $valueArr['loss_percentage'];
				$returArr['status'] = $valueArr['status'];
				$returArr['admin_id'] = $valueArr['admin_id'];
				$returArr['buy_order_id'] = $valueArr['buy_order_id'];
				$returArr['buy_order_binance_id'] = $valueArr['buy_order_binance_id'];
				$returArr['created_date'] = $formated_date_time;
			}
			
			$fullarray[]= $returArr;
		}

		return $fullarray;

	}//end get_sell_active_orders


	//binance_buy_auto_limit_order
	public function binance_buy_auto_limit_order($id,$quantity,$price,$symbol,$user_id){
		
		//Submit Limit Order to Binance
		$order = $this->binance_api->place_buy_limit_order($symbol,$quantity,$price,$user_id);

		if($order['orderId'] ==""){

			$order_arr = json_encode($order);
			$order_arr2 = json_decode($order_arr);

			$error_msg = $order_arr2->msg;
			
			//////////////////////////////////////////////////////////////////////////////
			////////////////////////////// Order History Log /////////////////////////////
			$log_msg = "Buy Order was got Error (".$error_msg.")";
	    	$this->insert_order_history_log($id,$log_msg,'buy_error',$user_id);
	    	////////////////////////////// End Order History Log /////////////////////////
	    	//////////////////////////////////////////////////////////////////////////////


	    	/////////////Insert Binance Error Record/////////////////////////////////////
			$this->insert_binance_errors($id,$error_msg,'buy',$user_id);
			/////////////End Insert Binance Error Record/////////////////////////////////


			return array('error' => $error_msg);

		}else{

			$upd_data = array(
				'market_value' => $price,
				'status' => 'submitted',
				'binance_order_id' => $order['orderId']
			);

			$this->mongo_db->where(array('_id'=> $id));
			$this->mongo_db->set($upd_data);

			//Update data in mongoTable 
		    $this->mongo_db->update('buy_orders');
		    

    		////////////////////// Set Notification //////////////////
    		$message = "Buy Limit Order is <b>SUBMITTED</b>";
    		$this->add_notification($id,'buy',$message,$user_id);
    		//////////////////////////////////////////////////////////


    		//////////////////////////////////////////////////////////////////////////////
			////////////////////////////// Order History Log /////////////////////////////
			$log_msg = "Buy Limit Order was <b>SUBMITTED</b>";
	    	$this->insert_order_history_log($id,$log_msg,'buy_submitted',$user_id);
	    	////////////////////////////// End Order History Log /////////////////////////
	    	//////////////////////////////////////////////////////////////////////////////

		}

		return true;

	}//end binance_buy_auto_limit_order


	//binance_buy_auto_market_order
	public function binance_buy_auto_market_order($id,$quantity,$market_value,$symbol,$user_id){
		
		//Submit Market Order to Binance
		$order = $this->binance_api->place_buy_market_order($symbol,$quantity,$user_id);

		if($order['orderId'] ==""){

			$order_arr = json_encode($order);
			$order_arr2 = json_decode($order_arr);

			$error_msg = $order_arr2->msg;

			//////////////////////////////////////////////////////////////////////////////
			////////////////////////////// Order History Log /////////////////////////////
			$log_msg = "Buy Order was got Error (".$error_msg.")";
	    	$this->insert_order_history_log($id,$log_msg,'buy_error',$user_id);
	    	////////////////////////////// End Order History Log /////////////////////////
	    	//////////////////////////////////////////////////////////////////////////////


	    	/////////////Insert Binance Error Record//////////////////////////////////////
			$this->insert_binance_errors($id,$error_msg,'buy',$user_id);
			/////////////End Insert Binance Error Record//////////////////////////////////

			return array('error' => $error_msg);

		}else{

			$upd_data = array(
				'market_value' => $market_value,
				'status' => 'submitted',
				'binance_order_id' => $order['orderId']
			);

			$this->mongo_db->where(array('_id'=> $id));
			$this->mongo_db->set($upd_data);

			//Update data in mongoTable 
		    $this->mongo_db->update('buy_orders');

		   

    		////////////////////// Set Notification //////////////////
    		$message = "Buy Market Order is <b>SUBMITTED</b>";
    		$this->add_notification($id,'buy',$message,$user_id);
    		//////////////////////////////////////////////////////////

    		//////////////////////////////////////////////////////////////////////////////
			////////////////////////////// Order History Log /////////////////////////////
			$log_msg = "Buy Market Order was <b>SUBMITTED</b>";
	    	$this->insert_order_history_log($id,$log_msg,'buy_submitted',$user_id);
	    	////////////////////////////// End Order History Log /////////////////////////
	    	//////////////////////////////////////////////////////////////////////////////
	    	
		}

		return true;

	}//end binance_buy_auto_market_order


	//binance_sell_auto_limit_order
	public function binance_sell_auto_limit_order($id,$quantity,$price,$symbol,$user_id,$buy_order_id){
		
		//Check Order Quantity
		$coin_quantity = $this->check_order_quantity($id,$buy_order_id,$user_id,$symbol,$quantity);

		if($coin_quantity !='no'){
			$quantity = $coin_quantity;
		}


		//Submit Limit Order to Binance
		$order = $this->binance_api->place_sell_limit_order($symbol,$quantity,$price,$user_id);

		if($order['orderId'] ==""){

			$order_arr = json_encode($order);
			$order_arr2 = json_decode($order_arr);

			$error_msg = $order_arr2->msg;

			//////////////////////////////////////////////////////////////////////////////
			////////////////////////////// Order History Log /////////////////////////////
			$log_msg = "Sell Order was got Error (".$error_msg.")";
	    	$this->insert_order_history_log($buy_order_id,$log_msg,'sell_error',$user_id);
	    	////////////////////////////// End Order History Log /////////////////////////
	    	//////////////////////////////////////////////////////////////////////////////


	    	/////////////Insert Binance Error Record//////////////////////////////////////
			$this->insert_binance_errors($id,$error_msg,'sell',$user_id);
			/////////////End Insert Binance Error Record//////////////////////////////////

			return array('error' => $error_msg);

		}else{

			$upd_data = array(
				'market_value' => $price,
				'status' => 'submitted',
				'binance_order_id' => $order['orderId']
			);

			$this->mongo_db->where(array('_id'=> $id));
			$this->mongo_db->set($upd_data);

			//Update data in mongoTable 
		    $this->mongo_db->update('orders');

		    ////////////////////// Set Notification //////////////////
    		$message = "Sell Limit Order is <b>SUBMITTED</b>";
    		$this->add_notification($id,'sell',$message,$user_id);
    		//////////////////////////////////////////////////////////

    		//////////////////////////////////////////////////////////////////////////////
			////////////////////////////// Order History Log /////////////////////////////
			$log_msg = "Sell Limit Order was <b>SUBMITTED</b>";
	    	$this->insert_order_history_log($buy_order_id,$log_msg,'sell_submitted',$user_id);
	    	////////////////////////////// End Order History Log /////////////////////////
	    	//////////////////////////////////////////////////////////////////////////////
		}

		return true;

	}//end binance_sell_auto_limit_order


	//binance_sell_auto_market_order
	public function binance_sell_auto_market_order($id,$quantity,$market_value,$symbol,$user_id,$buy_order_id){

		//Check Order Quantity
		$coin_quantity = $this->check_order_quantity($id,$buy_order_id,$user_id,$symbol,$quantity);

		if($coin_quantity !='no'){
			$quantity = $coin_quantity;
		}
		

		//Submit Limit Order to Binance
		$order = $this->binance_api->place_sell_market_order($symbol,$quantity,$user_id);

		if($order['orderId'] ==""){

			$order_arr = json_encode($order);
			$order_arr2 = json_decode($order_arr);

			$error_msg = $order_arr2->msg;
			
			//////////////////////////////////////////////////////////////////////////////
			////////////////////////////// Order History Log /////////////////////////////
			$log_msg = "Sell Order was got Error (".$error_msg.")";
	    	$this->insert_order_history_log($buy_order_id,$log_msg,'sell_error',$user_id);
	    	////////////////////////////// End Order History Log /////////////////////////
	    	//////////////////////////////////////////////////////////////////////////////


	    	/////////////Insert Binance Error Record/////////////////////////////////////
			$this->insert_binance_errors($id,$error_msg,'sell',$user_id);
			/////////////End Insert Binance Error Record/////////////////////////////////

			return array('error' => $error_msg);

		}else{

			$upd_data = array(
				'market_value' => $market_value,
				'status' => 'submitted',
				'binance_order_id' => $order['orderId']
			);

			$this->mongo_db->where(array('_id'=> $id));
			$this->mongo_db->set($upd_data);

			//Update data in mongoTable 
		    $this->mongo_db->update('orders');

		    ////////////////////// Set Notification //////////////////
    		$message = "Sell Market Order is <b>SUBMITTED</b>";
    		$this->add_notification($id,'sell',$message,$user_id);
    		//////////////////////////////////////////////////////////

    		//////////////////////////////////////////////////////////////////////////////
			////////////////////////////// Order History Log /////////////////////////////
			$log_msg = "Sell Market Order was <b>SUBMITTED</b>";
	    	$this->insert_order_history_log($buy_order_id,$log_msg,'sell_submitted',$user_id);
	    	////////////////////////////// End Order History Log /////////////////////////
	    	//////////////////////////////////////////////////////////////////////////////
		}

		return true;

	}//end binance_sell_auto_market_order


	//check_order_quantity
	public function check_order_quantity($id,$buy_order_id,$admin_id,$symbol,$quantity){

		//Get user Details
      	$this->db->dbprefix('settings');
      	$this->db->where('user_id',$admin_id);
      	$get_settings = $this->db->get('settings');
      	$setting_arr = $get_settings->row_array();

      	if($setting_arr['api_key'] !="" && $setting_arr['api_secret'] !="" && $setting_arr['auto_sell_enable'] =="yes"){

	        //Get user Balance
	        $this->db->dbprefix('coin_balance');
	        $this->db->where('coin_symbol',$symbol);
	        $this->db->where('user_id',$admin_id);
	        $get_coin_record = $this->db->get('coin_balance');
	        $coin_record_arr = $get_coin_record->row_array();

	        $coin_balance = $coin_record_arr['coin_balance'];

	        if($quantity > $coin_balance){
	        	
	        	//Update Order Record
                $upd_data = array(
                    'quantity' => $coin_balance,
                    'last_quantity_updated' => 'yes'
                  );

                $this->mongo_db->where(array('_id'=> $id));
                $this->mongo_db->set($upd_data);
                $this->mongo_db->update('orders');

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = "Sell Order Quantity Updated from <b>".$quantity."</b> to <b>".$coin_balance."</b> as per your Last Order Settings";
                $this->insert_order_history_log($buy_order_id,$log_msg,'auto_update_quantity',$admin_id);
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////

                return $coin_balance;
	        
	        }else{

	        	return "no";
	        }

   		}else{

   			return "no";
   		}


	}//end check_order_quantity


	//insert_binance_errors
	public function insert_binance_errors($id,$error_msg,$type,$user_id){

		$created_date = date('Y-m-d G:i:s');

		//Update Order Record
		$upd_data = array(
			'status' => 'error'
		);

		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->set($upd_data);


		//Update data in mongoTable 
		if($type =="sell"){
			$this->mongo_db->update('orders');
		}else{
			$this->mongo_db->update('buy_orders');
		}


		////////////////////// Set Notification //////////////////
		$message = ucfirst($type)." Order got <b>ERROR</b>";
		$this->add_notification($id,$type,$message,$user_id);
		//////////////////////////////////////////////////////////


		return true;

	}//end insert_binance_errors


	//insert_order_history_log
	public function insert_order_history_log($id,$log_msg,$type,$user_id){

		$created_date = date('Y-m-d G:i:s');

		$ins_error = array(
			'order_id' => $this->mongo_db->mongoId($id),
			'log_msg' => $log_msg,
			'type' => $type,
			'created_date' => $this->mongo_db->converToMongodttime($created_date)
		);

		$this->mongo_db->insert('orders_history_log',$ins_error);

		return true;

	}//end insert_order_history_log


	//get_binance_errors
	public function get_binance_errors($id,$type){

		$this->mongo_db->where(array('order_id'=> $id, 'type'=> $type));
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('binance_errors');
		  
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

				$returArr['order_id'] = $valueArr['order_id'];
				$returArr['type'] = $valueArr['type'];
				$returArr['error_msg'] = $valueArr['error_msg'];
				$returArr['created_date'] = $formated_date_time;
			}

			$fullarray[]= $returArr;
		}

		return $fullarray;

	}//End get_binance_errors


	//get_order_history_log
	public function get_order_history_log($id){

		$this->mongo_db->where(array('order_id'=> $id));
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('orders_history_log');
		  
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

				$returArr['order_id'] = $valueArr['order_id'];
				$returArr['type'] = $valueArr['type'];
				$returArr['log_msg'] = $valueArr['log_msg'];
				$returArr['created_date'] = $formated_date_time;
			}

			$fullarray[]= $returArr;
		}
		 

		return $fullarray;

	}//End get_order_history_log


	//add_buy_order
	public function add_buy_order($data){

		extract($data);

		$created_date = date('Y-m-d G:i:s');
		$admin_id = $this->session->userdata('admin_id');
		$global_symbol = $this->session->userdata('global_symbol');

		$ins_data = array(
				'price' => $price,
				'quantity' => $quantity,
				'symbol' => $coin,
				'order_type' => $order_type,
				'admin_id' => $admin_id,
				'created_date' => $this->mongo_db->converToMongodttime($created_date)
		);


		$is_submitted = 'no';
		if($trail_check !='')
		{
			$ins_data['trail_check'] = 'yes';
			$ins_data['trail_interval'] = $trail_interval;
			$ins_data['buy_trail_price'] = $price;
			$ins_data['status'] = 'new';

		}else{

			$ins_data['trail_check'] = 'no';
			$ins_data['trail_interval'] = '0';
			$ins_data['buy_trail_price'] = '0';

			if($order_type =='limit_order'){

				//Submit Limit Order to Binance
				$order = $this->binance_api->place_buy_limit_order($coin,$quantity,$price);

				if($order['orderId'] ==""){

					$order_arr = json_encode($order);
					$order_arr2 = json_decode($order_arr);

					$error_msg = $order_arr2->msg;
					return array('error' => $error_msg);

				}else{
					
					$ins_data['market_value'] = $price;
					$ins_data['status'] = 'submitted';
					$ins_data['binance_order_id'] = $order['orderId'];
					$is_submitted = 'yes';
				}

			}else{

				$ins_data['status'] = 'new';
			}
			
		}

		if($auto_sell =='yes'){
			$ins_data['auto_sell'] = 'yes';
		}else{
			$ins_data['auto_sell'] = 'no';
		}

		//Insert data in mongoTable 
	    $buy_order_id = $this->mongo_db->insert('buy_orders',$ins_data);


	    ////////////////////////////// Auto Sell////////////////////////////
	    if($auto_sell =='yes'){

	    	$ins_temp_data = array(
				'buy_order_id' => $this->mongo_db->mongoId($buy_order_id),
				'profit_type' => $profit_type,
				'profit_percent' => $sell_profit_percent,
				'profit_price' => $sell_profit_price,
				'order_type' => $sell_order_type,
				'trail_check' => $sell_trail_check,
				'trail_interval' => $sell_trail_interval,
				'stop_loss' => $stop_loss,
				'loss_percentage' => $loss_percentage,
				'admin_id' => $admin_id,
				'created_date' => $this->mongo_db->converToMongodttime($created_date)
			);

			//Insert data in mongoTable 
	    	$this->mongo_db->insert('temp_sell_orders',$ins_temp_data);

	    }
	    //////////////////////////////// End Auto Sell/////////////////////////



		//////////////////////////////////////////////////////////////////////////////
		////////////////////////////// Order History Log /////////////////////////////
		$log_msg = "Buy Order was Created";
    	$this->insert_order_history_log($buy_order_id,$log_msg,'buy_created',$admin_id);
    	////////////////////////////// End Order History Log /////////////////////////
    	//////////////////////////////////////////////////////////////////////////////


    	
    	//////////////////////////////////////////////////////////////////////////////
		////////////////////////////// Order History Log /////////////////////////////
		if($is_submitted == 'yes'){
			$log_msg = "Buy Order was Submitted to Binance";
	    	$this->insert_order_history_log($buy_order_id,$log_msg,'buy_submitted',$admin_id);
    	}
    	////////////////////////////// End Order History Log /////////////////////////
    	//////////////////////////////////////////////////////////////////////////////
    	

	    return true;

	}//end add_buy_order


	//auto_sell_now
	public function auto_sell_now($buy_order_id){

		$order_arr = $this->get_buy_order($buy_order_id);
		$auto_sell = $order_arr['auto_sell']; 
		$admin_id = $order_arr['admin_id']; 
		$symbol = $order_arr['symbol']; 

		$is_submitted = 'no';
		if($auto_sell =='yes'){

			$created_date = date('Y-m-d G:i:s');
			
			$purchased_price = $order_arr['market_value'];
			$quantity = $order_arr['quantity'];
			$binance_order_id = $order_arr['binance_order_id'];
			$buy_order_check = 'yes';

			//Get Sell Temp Data
			$sell_data_arr = $this->get_temp_sell_data($buy_order_id);
			$profit_type = $sell_data_arr['profit_type'];
			$sell_profit_percent = $sell_data_arr['profit_percent'];
			$sell_profit_price = $sell_data_arr['profit_price'];
			$order_type = $sell_data_arr['order_type'];
			$trail_check = $sell_data_arr['trail_check'];
			$trail_interval = $sell_data_arr['trail_interval'];
			$stop_loss = $sell_data_arr['stop_loss'];
			$loss_percentage = $sell_data_arr['loss_percentage'];

			$ins_data = array(
				'symbol' => $symbol,
				'purchased_price' => $purchased_price,
				'quantity' => $quantity,
				'profit_type' => $profit_type,
				'order_type' => $order_type,
				'admin_id' => $admin_id,
				'buy_order_check' => $buy_order_check,
				'buy_order_id' => $buy_order_id,
				'buy_order_binance_id' => $binance_order_id,
				'stop_loss' => $stop_loss,
				'loss_percentage' => $loss_percentage,
				'created_date' => $this->mongo_db->converToMongodttime($created_date)
			);

			if($profit_type =='percentage'){

				$sell_price = $purchased_price * $sell_profit_percent;
				$sell_price = $sell_price / 100;
				$sell_price = $sell_price + $purchased_price;
				$sell_price = number_format($sell_price, 8, '.', '');

				$ins_data['sell_profit_percent'] = $sell_profit_percent;
				$ins_data['sell_price'] = $sell_price;

			}else{

				$sell_price = $sell_profit_price;

				$ins_data['sell_profit_price'] = $sell_profit_price;
				$ins_data['sell_price'] = $sell_price;
			}


			if($trail_check !='')
			{
				$ins_data['trail_check'] = 'yes';
				$ins_data['trail_interval'] = $trail_interval;
				$ins_data['sell_trail_price'] = $sell_price;
				$ins_data['status'] = 'new';
			
			}else{

				$ins_data['trail_check'] = 'no';
				$ins_data['trail_interval'] = '0';
				$ins_data['sell_trail_price'] = '0';

				if($order_type =='limit_order'){

					//Submit Sell Limit Order to Binance
					$order = $this->binance_api->place_sell_limit_order($symbol,$quantity,$sell_price);

					if($order['orderId'] ==""){

						$order_arr = json_encode($order);
						$order_arr2 = json_decode($order_arr);

						$error_msg = $order_arr2->msg;
						return array('error' => $error_msg);

					}else{
						
						$ins_data['market_value'] = $sell_price;
						$ins_data['status'] = 'submitted';
						$ins_data['binance_order_id'] = $order['orderId'];
						$is_submitted = 'yes';
					}

				}else{

					$ins_data['status'] = 'new';
				}

			}

		
			//Insert data in mongoTable 
		    $order_id = $this->mongo_db->insert('orders',$ins_data);

		    if($buy_order_check =='yes'){

		    	//Update Buy Order
		    	$upd_data = array(
					'is_sell_order' => 'yes',
					'sell_order_id' => $order_id
				);

		    	$this->mongo_db->where(array('_id'=> $buy_order_id));
				$this->mongo_db->set($upd_data);

				//Update data in mongoTable 
			    $this->mongo_db->update('buy_orders');
		    }


		    //////////////////////////////////////////////////////////////////////////////
			////////////////////////////// Order History Log /////////////////////////////
			$log_msg = "Sell Order was Created from Auto Sell";
	    	$this->insert_order_history_log($buy_order_id,$log_msg,'sell_created',$admin_id);
	    	////////////////////////////// End Order History Log /////////////////////////
	    	//////////////////////////////////////////////////////////////////////////////


	    	
	    	//////////////////////////////////////////////////////////////////////////////
			////////////////////////////// Order History Log /////////////////////////////
			if($is_submitted == 'yes'){
				$log_msg = "Sell Order was Submitted to Binance";
		    	$this->insert_order_history_log($buy_order_id,$log_msg,'sell_submitted',$admin_id);
	    	}
	    	////////////////////////////// End Order History Log /////////////////////////
	    	//////////////////////////////////////////////////////////////////////////////



		}// if($auto_sell =='yes')


		return true;

	}//End auto_sell_now


	//get_temp_sell_data
	public function get_temp_sell_data($buy_order_id){
		
		$this->mongo_db->where(array('buy_order_id'=> $buy_order_id));
		$responseArr = $this->mongo_db->get('temp_sell_orders');

		foreach ($responseArr as  $valueArr) {
			$returArr = array();
			if(!empty($valueArr)){
				 
				$returArr['_id'] = $valueArr['_id'];
				$returArr['buy_order_id'] = $valueArr['buy_order_id'];
				$returArr['profit_type'] = $valueArr['profit_type'];
				$returArr['profit_percent'] = $valueArr['profit_percent'];
				$returArr['profit_price'] = $valueArr['profit_price'];
				$returArr['order_type'] = $valueArr['order_type'];
				$returArr['trail_check'] = $valueArr['trail_check'];
				$returArr['trail_interval'] = $valueArr['trail_interval'];
				$returArr['stop_loss'] = $valueArr['stop_loss'];
				$returArr['loss_percentage'] = $valueArr['loss_percentage'];
			}
		}
		
		return $returArr;

	}//End get_temp_sell_data


	//edit_buy_order
	public function edit_buy_order($data){

		extract($data);
		 
		$order_arr = $this->get_buy_order($id);

		if($order_arr['status'] =='new' || $order_arr['status'] =='error'){

			$created_date = date('Y-m-d G:i:s');
			$admin_id = $this->session->userdata('admin_id');

			$is_submitted = 'no';
			$upd_data = array(
					'symbol' => $coin,
					'price' => $price,
					'quantity' => $quantity,
					'order_type' => $order_type,
					'admin_id' => $admin_id,
				);

			if($trail_check !='')
			{
				$upd_data['trail_check'] = 'yes';
				$upd_data['trail_interval'] = $trail_interval;
				$upd_data['buy_trail_price'] = $price;
				$upd_data['status'] = 'new';
			}
			else
			{
				$upd_data['trail_check'] = 'no';
				$upd_data['trail_interval'] = '0';
				$upd_data['buy_trail_price'] = '0';

				if($order_type =='limit_order'){

					//Submit Order to Binance
					$order = $this->binance_api->place_buy_order($coin,$quantity,$price);

					if($order['orderId'] ==""){

						$order_arr = json_encode($order);
						$order_arr2 = json_decode($order_arr);

						$error_msg = $order_arr2->msg;
						return array('error' => $error_msg);

					}else{

						$upd_data['market_value'] = $price;
						$upd_data['status'] = 'submitted';
						$upd_data['binance_order_id'] = $order['orderId'];
						$is_submitted = 'yes';
					}

				}else{

					$upd_data['status'] = 'new';
				}

			}

			if($auto_sell =='yes'){
				$upd_data['auto_sell'] = 'yes';
			}else{
				$upd_data['auto_sell'] = 'no';
			}


			$this->mongo_db->where(array('_id'=> $id));
			$this->mongo_db->set($upd_data);

			//Update data in mongoTable 
		    $this->mongo_db->update('buy_orders');


		    ////////////////////////////// Auto Sell////////////////////////////
		    if($auto_sell =='yes'){

		    	if($temp_sell_id !=""){

		    		$upd_temp_data = array(
						'profit_type' => $profit_type,
						'profit_percent' => $sell_profit_percent,
						'profit_price' => $sell_profit_price,
						'order_type' => $sell_order_type,
						'trail_check' => $sell_trail_check,
						'trail_interval' => $sell_trail_interval,
						'stop_loss' => $stop_loss,
						'loss_percentage' => $loss_percentage,
					);

					$this->mongo_db->where(array('_id'=> $temp_sell_id));
					$this->mongo_db->set($upd_temp_data);

					//Update data in mongoTable 
				    $this->mongo_db->update('temp_sell_orders');


		    	}else{

		    		$ins_temp_data = array(
						'buy_order_id' => $this->mongo_db->mongoId($id),
						'profit_type' => $profit_type,
						'profit_percent' => $sell_profit_percent,
						'profit_price' => $sell_profit_price,
						'order_type' => $sell_order_type,
						'trail_check' => $sell_trail_check,
						'trail_interval' => $sell_trail_interval,
						'stop_loss' => $stop_loss,
						'loss_percentage' => $loss_percentage,
						'admin_id' => $admin_id,
						'created_date' => $this->mongo_db->converToMongodttime($created_date)
					);


					//Insert data in mongoTable 
			    	$this->mongo_db->insert('temp_sell_orders',$ins_temp_data);
		    	}

		    }
		    //////////////////////////////// End Auto Sell//////////////////////


	    	
	    	//////////////////////////////////////////////////////////////////////////////
			////////////////////////////// Order History Log /////////////////////////////
			$log_msg = "Buy Order was Updated";
	    	$this->insert_order_history_log($id,$log_msg,'buy_updated',$admin_id);
	    	////////////////////////////// End Order History Log /////////////////////////
	    	//////////////////////////////////////////////////////////////////////////////


	    	
	    	//////////////////////////////////////////////////////////////////////////////
			////////////////////////////// Order History Log /////////////////////////////
			if($is_submitted == 'yes'){
				$log_msg = "Buy Order was Submitted to Binance";
		    	$this->insert_order_history_log($id,$log_msg,'buy_submitted',$admin_id);
	    	}
	    	////////////////////////////// End Order History Log /////////////////////////
	    	//////////////////////////////////////////////////////////////////////////////


	    	return true;
	    		
    	}else{


    		return false;

    	}//End if Order is New

	    

	}//end edit_buy_order


	//delete_buy_order
	public function delete_buy_order($id,$order_id){

		$admin_id = $this->session->userdata('admin_id');
		$global_symbol = $this->session->userdata('global_symbol');

		// $this->mongo_db->where(array('_id'=> $id));
		
		// //Delete data in mongoTable 
	 	//  $this->mongo_db->delete('buy_orders');

		if($order_id !=""){

			//Binance Cancel Order
	 		$order = $this->binance_api->cancel_order($global_symbol,$order_id);
		}
	 	

		$upd_data = array(
				'status' => 'canceled'
			);


		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->set($upd_data);

		//Update data in mongoTable 
	    $this->mongo_db->update('buy_orders');


	    //////////////////////////////////////////////////////////////////////////////
		////////////////////////////// Order History Log /////////////////////////////
		$log_msg = "Buy Order was Canceled";
    	$this->insert_order_history_log($id,$log_msg,'buy_canceled',$admin_id);
    	////////////////////////////// End Order History Log /////////////////////////
    	//////////////////////////////////////////////////////////////////////////////


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
				$returArr['symbol'] = $valueArr['symbol'];
				$returArr['binance_order_id'] = $valueArr['binance_order_id'];
				$returArr['price'] = $valueArr['price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['order_type'] = $valueArr['order_type'];
				$returArr['status'] = $valueArr['status'];
				$returArr['admin_id'] = $valueArr['admin_id'];
				$returArr['trail_check'] = $valueArr['trail_check'];
				$returArr['trail_interval'] = $valueArr['trail_interval'];
				$returArr['is_sell_order'] = $valueArr['is_sell_order'];
				$returArr['sell_order_id'] = $valueArr['sell_order_id'];
				$returArr['auto_sell'] = $valueArr['auto_sell'];
				$returArr['created_date'] = $formated_date_time;
			}
		}

		
		return $returArr;

	}//end get_buy_order


	//get_buy_orders
	public function get_buy_orders(){

		$admin_id = $this->session->userdata('admin_id');

		//Check Filter Data
		$session_post_data = $this->session->userdata('filter-data-buy');
		
		$search_array = array('admin_id'=> $admin_id);
		if($session_post_data['filter_coin'] !=""){

			$symbol = $session_post_data['filter_coin'];
			$search_array['symbol'] = $symbol;
		}
		if($session_post_data['filter_type'] !=""){

			$order_type = $session_post_data['filter_type'];
			$search_array['order_type'] = $order_type;
		}
		if($session_post_data['start_date'] !="" && $session_post_data['end_date'] !=""){

			$created_datetime = date('Y-m-d G:i:s', strtotime($session_post_data['start_date']));
	        $orig_date = new DateTime($created_datetime);
	        $orig_date = $orig_date->getTimestamp(); 
	        $start_date = new MongoDB\BSON\UTCDateTime($orig_date*1000);


	        $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_post_data['end_date']));
	        $orig_date22 = new DateTime($created_datetime22);
	        $orig_date22 = $orig_date22->getTimestamp(); 
	        $end_date = new MongoDB\BSON\UTCDateTime($orig_date22*1000);


			$order_type = $session_post_data['filter_type'];
			$search_array['created_date'] = array('$gte'=> $start_date,'$lte'=> $end_date);
		}


		$this->mongo_db->where($search_array);
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('buy_orders');

		$fullarray = array();
		$total_sold_orders =0;
		$total_buy_amount =0;
		$total_sell_amount =0;
		$total_profit = 0;
		$total_quantity = 0;
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
				$returArr['symbol'] = $valueArr['symbol'];
				$returArr['binance_order_id'] = $valueArr['binance_order_id'];
				$returArr['price'] = $valueArr['price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['order_type'] = $valueArr['order_type'];
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['trail_check'] = $valueArr['trail_check'];
				$returArr['trail_interval'] = $valueArr['trail_interval'];
				$returArr['buy_trail_price'] = $valueArr['buy_trail_price'];
				$returArr['status'] = $valueArr['status'];
				$returArr['is_sell_order'] = $valueArr['is_sell_order'];
				$returArr['market_sold_price'] = $valueArr['market_sold_price'];
				$returArr['sell_order_id'] = $valueArr['sell_order_id'];
				$returArr['admin_id'] = $valueArr['admin_id'];
				$returArr['created_date'] = $formated_date_time;

				if($valueArr['status'] =='FILLED'){
					$total_buy_amount += num($valueArr['market_value']);
				}

				if($valueArr['is_sell_order'] =='sold'){
					$total_sold_orders += 1;
					$total_sell_amount += num($valueArr['market_sold_price']);
				}

				if($valueArr['is_sell_order'] =='sold'){

					$market_sold_price = $valueArr['market_sold_price'];
					$current_order_price = $valueArr['market_value'];
					$quantity = $valueArr['quantity'];

	                $current_data2222 = $market_sold_price - $current_order_price;  
	                $profit_data = ($current_data2222 * 100 / $market_sold_price);

	                $profit_data = number_format((float)$profit_data, 2, '.', '');

	                $total_profit += $quantity * $profit_data;
	                $total_quantity += $quantity;
	            }

			}
			
			$fullarray[]= $returArr;
		}


		$avg_profit = $total_profit /  $total_quantity;

		$return_data['fullarray'] = $fullarray;
		$return_data['total_buy_amount'] = num($total_buy_amount);
		$return_data['total_sell_amount'] = num($total_sell_amount);
		$return_data['total_sold_orders'] = $total_sold_orders;
		$return_data['avg_profit'] = number_format($avg_profit, 2, '.', '');;

		return $return_data;

	}//end get_buy_orders


	//get_buy_active_orders
	public function get_buy_active_orders(){

		$admin_id = $this->session->userdata('admin_id');

		//Check Filter Data
		$session_post_data = $this->session->userdata('filter-data-buy');
		
		$search_array = array('admin_id'=> $admin_id, 'status' =>'new');

		if($session_post_data['filter_coin'] !=""){

			$symbol = $session_post_data['filter_coin'];
			$search_array['symbol'] = $symbol;
		}
		if($session_post_data['filter_type'] !=""){

			$order_type = $session_post_data['filter_type'];
			$search_array['order_type'] = $order_type;
		}
		if($session_post_data['start_date'] !="" && $session_post_data['end_date'] !=""){

			$created_datetime = date('Y-m-d G:i:s', strtotime($session_post_data['start_date']));
	        $orig_date = new DateTime($created_datetime);
	        $orig_date = $orig_date->getTimestamp(); 
	        $start_date = new MongoDB\BSON\UTCDateTime($orig_date*1000);


	        $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_post_data['end_date']));
	        $orig_date22 = new DateTime($created_datetime22);
	        $orig_date22 = $orig_date22->getTimestamp(); 
	        $end_date = new MongoDB\BSON\UTCDateTime($orig_date22*1000);


			$order_type = $session_post_data['filter_type'];
			$search_array['created_date'] = array('$gte'=> $start_date,'$lte'=> $end_date);
		}


		$this->mongo_db->where($search_array);
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
				$returArr['symbol'] = $valueArr['symbol'];
				$returArr['price'] = $valueArr['price'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['market_value'] = $valueArr['market_value'];
				$returArr['trail_check'] = $valueArr['trail_check'];
				$returArr['trail_interval'] = $valueArr['trail_interval'];
				$returArr['buy_trail_price'] = $valueArr['buy_trail_price'];
				$returArr['status'] = $valueArr['status'];
				$returArr['admin_id'] = $valueArr['admin_id'];
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
		
		//Get all Sell Orders
		$orders_arr = $this->get_sell_active_orders();

		if(count($orders_arr)>0){
            foreach ($orders_arr as $key=>$value) {   

            	$id = $value['_id'];
            	$quantity = $value['quantity'];
            	$symbol = $value['symbol'];
            	$user_id = $value['admin_id'];
            	$buy_order_id = $value['buy_order_id'];
            	

            	//Get Market Price
				$market_value = $this->mod_dashboard->get_market_value($symbol);

				
				//////////////////Auto Sell Binance Market Order////////////////////
				$this->binance_sell_auto_market_order($id,$quantity,$market_value,$symbol,$user_id,$buy_order_id);
				///////////////////////////////////////////////////////////////////
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

		//Get All Buy Orders
		$orders_arr = $this->get_buy_active_orders();

		if(count($orders_arr)>0){
            foreach ($orders_arr as $key=>$value) {   

            	$id = $value['_id'];
            	$quantity = $value['quantity'];
            	$symbol = $value['symbol'];
            	$user_id = $value['admin_id'];

            	//Get Market Price
				$market_value = $this->mod_dashboard->get_market_value($symbol);

            	//////////////////Auto Buy Binance Market Order///////////////////
				$this->binance_buy_auto_market_order($id,$quantity,$market_value,$symbol,$user_id);
            	//////////////////////////////////////////////////////////////////
            }
        }
		
	    return true;

	}//end buy_all_orders

	
	public function get_candelstick_data()
	{
		


		$this->mongo_db->limit(25);
		$this->mongo_db->sort(array('created_date'=> 'desc'));
		$responseArr = $this->mongo_db->get('market_chart');

		foreach($responseArr as $val_arr){
		$final_arr[] = array(
			'close' => $val_arr['close'],
			'open' => $val_arr['open'],
			'high' => $val_arr['high'],
			'low' => $val_arr['low'],
			'volume' => $val_arr['volume'],
			'openTime' => $val_arr['openTime'],
			'closeTime' => $val_arr['closeTime']
		);
		}
		return $final_arr;
		exit;



		$global_symbol = $this->session->userdata('global_symbol');

		$this->mongo_db->where(array('coin'=> $global_symbol ,'periods'=>'1h'));
		$this->mongo_db->limit(90);
		$this->mongo_db->sort(array('created_date'=> 'desc'));
		$responseArr = $this->mongo_db->get('market_chart');
		$final_arr = array();
		foreach($responseArr as $val_arr){
		$final_arr[] = array(
			'close' => $val_arr['close'],
			'open' => $val_arr['open'],
			'high' => $val_arr['high'],
			'low' => $val_arr['low'],
			'volume' => $val_arr['volume'],
			'openTime' => $val_arr['openTime'],
			'closeTime' => $val_arr['closeTime']
		);
		}
		// echo "<pre>";
		// print_r($final_arr);
		// exit;
		return $final_arr;

	}//end get_candelstick_data


	public function get_all_orders()
	{
		
		$global_symbol = $this->session->userdata('global_symbol');

		$all_orders = $this->binance_api->get_all_orders($global_symbol);

		echo "<pre>";
	   	print_r($all_orders);
	  	exit;
		
		for ($i=0; $i < count($all_orders); $i++) { 

			$binance_order_id = $all_orders[$i]['orderId'];
			$price = $all_orders[$i]['price'];
			$status = $all_orders[$i]['status'];

			$upd_data = array(
				'market_value' => $price,
				'status' => $status
			);

			$this->mongo_db->where(array('binance_order_id'=> $binance_order_id));
			$this->mongo_db->set($upd_data);

			//Update data in mongoTable 
	    	$this->mongo_db->update('buy_orders');
		}

	  	

		return true;

	}//end get_all_orders


	public function get_sell_order_status($id, $order_id)
	{	
		//Check Orders
		$order_arr = $this->get_order($id);
		$symbol = $order_arr['symbol'];
		
		$order_data = $this->binance_api->order_status($symbol,$order_id);

		if($order_data['status'] =='NEW'){
			$status = 'submitted';
		}else{
			$status = $order_data['status'];
		}	

		$upd_data = array(
			'status' => $status
		);

		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->set($upd_data);

		//Update data in mongoTable 
	    $this->mongo_db->update('orders');


	    if($order_data['status'] =='FILLED'){

		    $buy_order_check = $order_arr['buy_order_check'];
		    $buy_order_id = $order_arr['buy_order_id'];
		    $market_sold_price = $order_arr['market_value'];
		    
		    if($buy_order_id !=''){

		    	//Update Buy Order
		    	$upd_data22 = array(
					'is_sell_order' => 'sold',
					'market_sold_price' => $market_sold_price
				);

		    	$this->mongo_db->where(array('_id'=> $buy_order_id));
				$this->mongo_db->set($upd_data22);

				//Update data in mongoTable 
			    $this->mongo_db->update('buy_orders');
		    }
	    }

	  
	    return true;

	}//end get_sell_order_status


	public function get_buy_order_status($id, $order_id)
	{
		//Check Orders
		$order_arr = $this->get_buy_order($id);
		$symbol = $order_arr['symbol'];
		
		$order_data = $this->binance_api->order_status($symbol,$order_id);

		if($order_data['status'] =='NEW'){
			$status = 'submitted';
		}else{
			$status = $order_data['status'];
		}	

		$upd_data = array(
			'status' => $status
		);

		$this->mongo_db->where(array('_id'=> $id));
		$this->mongo_db->set($upd_data);

		//Update data in mongoTable 
	    $this->mongo_db->update('buy_orders');

	  
	    return true;

	}//end get_buy_order_status



	//get_market_value
	public function get_market_value($symbol =''){

		if($symbol !=""){
			$global_symbol = $symbol;
		}else{
			$global_symbol = $this->session->userdata('global_symbol');
		}

		
		//Get Market Prices
		$this->mongo_db->where(array('coin'=> $global_symbol));
		$this->mongo_db->limit(1);
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$responseArr = $this->mongo_db->get('market_prices');

		foreach ($responseArr as  $valueArr) {
			if(!empty($valueArr)){
				$market_value = $valueArr['price'];
			}
		}

		return num($market_value);

	}//End get_market_value


	//check_buy_zones
	public function check_buy_zones($market_value){

		$global_symbol = $this->session->userdata('global_symbol');

        $priceAsk = num((float)$market_value);
       
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
		
		$in_zone = 'no';
		foreach ($res as  $valueArr) {
			if(!empty($valueArr)){
				$in_zone = 'yes';
				$type = $valueArr['type'];
				$start_value = $valueArr['start_value'];
				$end_value = $valueArr['end_value'];
			}else{
				$in_zone = 'no';
				$type = '';
				$start_value = '';
				$end_value = '';
			}
		}

		$data['in_zone'] = $in_zone; 
		$data['type'] = $type; 
		$data['start_value'] = $start_value; 
		$data['end_value'] = $end_value; 

		return $data;

	}//End check_buy_zones


	//get_coin_balance
	public function get_coin_balance(){

		$global_symbol = $this->session->userdata('global_symbol');
		$admin_id = $this->session->userdata('admin_id');

		$this->db->where('coin_symbol',$global_symbol);
		$this->db->where('user_id',$admin_id);
		$coin = $this->db->get('coin_balance');
		$coin_arr = $coin->row_array();
		   
		return $coin_arr['coin_balance'];

	}//End get_coin_balance


	//get_notifications
	public function get_notifications(){

		$admin_id = $this->session->userdata('admin_id');

		$this->db->dbprefix('notification');
		$this->db->where('status',0);
		$this->db->where('admin_id',$admin_id);
		$this->db->order_by('id DESC');
		$get_notification = $this->db->get('notification');

		//echo $this->db->last_query();
		$notification_arr = $get_notification->row_array();

		if(count($notification_arr)>0){

			$id = $notification_arr['id'];
			$upd_data = array('status' => 1);

			//Update the record into the database.
			$this->db->dbprefix('notification');
			$this->db->where('id', $id);
			$this->db->update('notification', $upd_data);
		}
		
		return $notification_arr;

	}//End get_notifications



	//add_notification
	public function add_notification($order_id,$type,$message,$admin_id){
		
		extract($data);
		
		$created_date = date('Y-m-d G:i:s');
		
		$ins_data = array(
		   'admin_id' => $this->db->escape_str(trim($admin_id)),
		   'order_id' => $this->db->escape_str(trim($order_id)),
		   'type' => $this->db->escape_str(trim($type)),
		   'message' => $this->db->escape_str(trim($message)),
		   'created_date' => $this->db->escape_str(trim($created_date)),
		);
		
		//Insert the record into the database.
		$this->db->dbprefix('notification');
		$this->db->insert('notification', $ins_data);
		
		return true;

	}//end add_notification()


	public function test(){
		$this->mongo_db->limt(1);
		$res = $this->mongo_db->get('market_depth');
		echo '<pre>';
		print_r($re);
		exit;
	}

	
	
}
?>