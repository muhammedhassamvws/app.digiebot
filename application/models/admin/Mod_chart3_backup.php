<?php
/**
* 
*/
class mod_chart3 extends CI_Model
{
	
	function __construct()
	{
		# code...
	}

	//get_market_buy_depth_chart
	public function get_market_buy_depth_chart($global_symbol=''){

		//$global_symbol = $this->session->userdata('global_symbol');

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


				$priceee = $valueArr['price'];
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


				$priceee = $valueArr['price'];
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
	public function get_market_sell_depth_chart($global_symbol=''){

		//$global_symbol = $this->session->userdata('global_symbol');

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



				$priceee = $valueArr['price'];
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


				$priceee = $valueArr['price'];
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

	public function insert_chart3($data){
		$ins_id = $this->mongo_db->insert('chart4',$data);

		if ($ins_id) {
			return true;
		}
		else
		{
			return false;
		}
	}

	public function get_bid_values_for_chart($symbol)
	{

		$connetct = $this->mongo_db->customQuery();
		$this->mongo_db->where(array("coin" => $symbol, "type" => 'bid'));
		$this->mongo_db->limit(50);
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$res = $this->mongo_db->get('chart4');
		
		$big_quantity = 0;
		foreach ($res as  $valueArr) {
			$returArr = array();
			
			if(!empty($valueArr)){
				
				$returArr['_id'] = $valueArr['_id'];
				$returArr['price'] = $valueArr['market_value'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['depth_buy_quantity'] = $valueArr['depth_buy_quantity'];
				$returArr['depth_sell_quantity'] = $valueArr['depth_sell_quantity'];
				$returArr['buy_quantity'] = $valueArr['buy_quantity'];
				
				if(!empty($valueArr222)){
						
						$sell_quantity = $valueArr['sell_quantity'];
					}
					
				if($sell_quantity > $big_quantity){
					$big_quantity = $sell_quantity;
				}
				$returArr['sell_quantity'] = $valueArr['sell_quantity'];
				$returArr['type'] = $valueArr['type'];
			}
				$fullarray[]= $returArr;
		}
		array_multisort( array_column($fullarray, "price"), SORT_DESC, $fullarray );
		return $fullarray;
	}

	public function get_ask_values_for_chart($symbol)
	{

		$connetct = $this->mongo_db->customQuery();
		$this->mongo_db->where(array("coin" => $symbol, "type" => 'ask'));
		$this->mongo_db->limit(50);
		$this->mongo_db->sort(array('_id'=> 'desc'));
		$res = $this->mongo_db->get('chart4');
		
		
		foreach ($res as  $valueArr) {
			$returArr = array();
			
			if(!empty($valueArr)){
				
				$returArr['_id'] = $valueArr['_id'];
				$returArr['price'] = $valueArr['market_value'];
				$returArr['coin'] = $valueArr['coin'];
				$returArr['quantity'] = $valueArr['quantity'];
				$returArr['depth_buy_quantity'] = $valueArr['depth_buy_quantity'];
				$returArr['depth_sell_quantity'] = $valueArr['depth_sell_quantity'];
				$returArr['buy_quantity'] = $valueArr['buy_quantity'];
				$returArr['sell_quantity'] = $valueArr['sell_quantity'];
				$returArr['type'] = $valueArr['type'];
			}
				$fullarray[]= $returArr;
		}
		array_multisort( array_column($fullarray, "price"), SORT_DESC, $fullarray );
		return $fullarray;
	}


	//get_zone_values
	public function get_zone_values($market_value,$global_symbol=''){

		//$global_symbol = $this->session->userdata('global_symbol');

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

	//get_chart_target_zones
	public function get_chart_target_zones(){
		
		//$admin_id = $this->session->userdata('admin_id');

		//$this->mongo_db->where(array('admin_id'=> $admin_id));
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
					$returArr['start_date'] = $this->mod_dashboard->change_time_stamp_to_human_readible($valueArr['start_date'] );
				}else{
					$returArr['start_date'] = '';
				}


				if($valueArr['end_date'] !=''){
					$returArr['end_date'] = $this->mod_dashboard->change_time_stamp_to_human_readible($valueArr['end_date']);
				}else{
					$returArr['end_date'] = '';
				}

				
				
			}
			
			$fullarray[]= $returArr;
		}

		return $fullarray;

	}//end get_chart_target_zones
}