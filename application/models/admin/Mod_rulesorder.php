<?php
/** Mod Rules Order Goes here**/
class mod_rulesorder extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
	
	public function getAllCoin(){
	
	  	$this->mongo_db->sort(array('_id' => 1));
		$this->mongo_db->where(array('user_id' => 'global', 'exchange_type' => 'binance'));
		$get_coins = $this->mongo_db->get('coins');
		$coins_arr = iterator_to_array($get_coins);
		return $coins_arr;
		
	}
	
    public function rulesOrderRecord($skip, $limit)
    {
        $pipeline                                = array(
            '$group' => array(
                '_id' => '$order_type',
                "order_type" => array(
                    '$sum' => 1
                )
            )
        );
        $project                                 = array(
            '$project' => array(
                "_id" => 1,
                "coin_symbol" => 1,
                "order_type" => 1,
                "rule" => 1,
                'mode' => 1
            )
        );
        $limit                                   = array(
            '$limit' => 1000
        );
        $connect                                 = $this->mongo_db->customQuery();
        $record_of_rules_for_orders_type         = $connect->record_of_rules_for_orders->aggregate(array(
            $project,
            $pipeline
        ));
        $data['record_of_rules_for_orders_type'] = iterator_to_array($record_of_rules_for_orders_type);
		
		
        $pipeline                                = array(
            '$group' => array(
                '_id' => '$rule',
                'rule' => array(
                    '$sum' => '$rule'
                ),
                'coin_symbol' => array(
                    '$first' => '$coin_symbol'
                ),
                'order_type' => array(
                    '$first' => '$order_type'
                ),
                'rule' => array(
                    '$first' => '$rule'
                ),
                'mode' => array(
                    '$first' => '$mode'
                ),
                'count' => array(
                    '$sum' => 1
                )
            )
        );
        $project                                 = array(
            '$project' => array(
                "_id" => 1,
                "coin_symbol" => 1,
                "order_type" => 1,
                "rule" => 1,
                'mode' => 1
            )
        );
        $match                                   = array();
        $sort                                    = array(
            '$sort' => array()
        );
        $limit                                   = array(
            '$limit' => 1000
        );
        $connect                                 = $this->mongo_db->customQuery();
        $record_of_rules_for_orders_rule         = $connect->record_of_rules_for_orders->aggregate(array(
            $project,
            $pipeline,
            $limit
        ));
        $data['record_of_rules_for_orders_rule'] = iterator_to_array($record_of_rules_for_orders_rule);
		
        return $data;
    }
	
	

	
	
    public function showOrderRecord($skip, $limit, $rule)
    {
        if ($rule == 2) {
            $search_array = array(
                'rule' => (int) $rule
            );
        } else if ($rule == 1) {
            $search_array = array(
                'rule' => (int) $rule
            );
        } else if ($rule == 5) {
            $search_array = array(
                'rule' => (int) $rule
            );
        } else if ($rule == '') {
            $search_array = array(
                'rule' => (int) $rule
            );
        } else {
            $search_array = array(
                'rule' => $rule
            );
        }
        //Check Filter Data
        $connetct    = $this->mongo_db->customQuery();
        //$qr = array('skip' => $skip, 'sort' => array('buy_order_id' => -1), 'limit' => $limit);
        $cursor      = $connetct->record_of_rules_for_orders->find($search_array);
        $responseArr = iterator_to_array($cursor);
        return $responseArr;
    }
    public function show_count_all($rule)
    {
        if ($rule == 2) {
            $search_array = array(
                'rule' => (int) $rule
            );
        } else if ($rule == 1) {
            $search_array = array(
                'rule' => (int) $rule
            );
        } else if ($rule == 5) {
            $search_array = array(
                'rule' => (int) $rule
            );
        } else if ($rule == '') {
            $search_array = array(
                'rule' => (int) $rule  
            );
        } else {
            $search_array = array(
                'rule' => $rule
            );
        }
        //Check Filter Data
        $connetct    = $this->mongo_db->customQuery();
        //$qr = array('sort' => array('modified_date' => -1));
        $cursor      = $connetct->record_of_rules_for_orders->find($search_array);
        $responseArr = iterator_to_array($cursor);
        $count       = count($responseArr);
        return $count;
    }
	
	 public function rulesSet($global_symbol,$global_mode)
    {
        $this->mongo_db->where(array('triggers_type' => 'barrier_trigger','coin' => $global_symbol,'order_mode' => $global_mode));
		$rulesSet_result = $this->mongo_db->get('trigger_global_setting');
		$rulesSet_arr    = iterator_to_array($rulesSet_result);
		return $rulesSet_arr[0];
        
    }
	
	 public function box_trigger($global_symbol,$global_mode,$trigger_type)
    {
		//$trigger_type  = 'box_trigger_3';
        $this->mongo_db->where(array('triggers_type' => $trigger_type,'coin' => $global_symbol,'order_mode' => $global_mode,));
		$this->mongo_db->order_by(array('numeric_level'=>1));
		$rulesSet_result = $this->mongo_db->get('trigger_global_setting');
		
		$rulesSet_arr    = iterator_to_array($rulesSet_result);
		if($trigger_type=='barrier_percentile_trigger' || $trigger_type=='box_trigger_3' || $trigger_type=='market_trend_trigger'){
		    return $rulesSet_arr;    	
		}else{
	    	return $rulesSet_arr[0];    
		}
    }
	
	 public function box_trigger_rule($global_symbol,$global_mode,$trigger_type)
    {
		

        $this->mongo_db->where(array('triggers_type' => $trigger_type,'order_mode' => $global_mode));
		$rulesSet_result = $this->mongo_db->get('trigger_global_setting');
		$rulesSet_arr    = iterator_to_array($rulesSet_result);
		if($trigger_type=='barrier_percentile_trigger' || $trigger_type=='box_trigger_3'){
		    return $rulesSet_arr;    	
		}else{
	    	return $rulesSet_arr;    
		}
    }
	
	
	 public function rulesOrderProfitLoss($coin, $order_mode,$triggers_type,$userID,$start_date,$end_date)
    {
		
        //$this->mongo_db->where(array('triggers_type' => $trigger_type,'coin' => $global_symbol,'order_mode' => $global_mode));
		$this->mongo_db->limit(100);
		$rulesSet_result = $this->mongo_db->get('trigger_global_setting');
		$rulesSet_arr    = iterator_to_array($rulesSet_result);
		echo "<pre>";  print_r($rulesSet_arr); exit;
		return $rulesSet_arr[0];    
    }
	
	
	
	public function orders_history($skip, $limit)
    {
       
        $pipeline                                = array(
            '$group' => array(
                '_id' => '$rule',
                'rule' => array(
                    '$sum' => '$rule'
                ),
                'coin_symbol' => array(
                    '$first' => '$coin_symbol'
                ),
                'order_type' => array(
                    '$first' => '$order_type'
                ),
                'rule' => array(
                    '$first' => '$rule'
                ),
                'mode' => array(
                    '$first' => '$mode'
                ),
                'count' => array(
                    '$sum' => 1
                )
            )
        );
        $project                                 = array(
            '$project' => array(
                "_id" => 1,
                "coin_symbol" => 1,
                "order_type" => 1,
                "rule" => 1,
                'mode' => 1
            )
        );
        $match                                   = array();
        $sort                                    = array(
            '$sort' => array()
        );
        $limit                                   = array(
            '$limit' => 1000
        );
        $connect                                 = $this->mongo_db->customQuery();
        $record_of_rules_for_orders_rule         = $connect->record_of_rules_for_orders->aggregate(array(
            $project,
            $pipeline,
            $limit
        ));
        $data['record_of_rules_for_orders_rule'] = iterator_to_array($record_of_rules_for_orders_rule);
        return $data;
    }
	
	public function avgProfit($symbol,$rule_number, $trigger_type){
		
		$project = array(
			'$project' => array(
				"buy_order_id" => 1,		
				"coin_symbol"  => 1,
				"order_type"   => 1,
				"rule"         => $rule_number,
			),
		);
		$match = array(

			'$match' => array(
				'order_type'  => 'sell',
				'coin_symbol' => $symbol,
				"rule"        => 5,

			),
		);

		$sort = array('$sort' => array('hour' => -1));
		$limit = array('$limit' => 1000);
		$connect = $this->mongo_db->customQuery();

		$record_of_rules_for_orders = $connect->record_of_rules_for_orders->aggregate(array($project, $match, $sort, $limit));
		$rulesSet_arr    = iterator_to_array($record_of_rules_for_orders);
		$buy_order_IDS   = array_column($rulesSet_arr, 'buy_order_id');
        //$buy_order_IDS2   = array_column($buy_order_IDS, 'oid');
		//echo "<prE>";  print_r($buy_order_IDS); exit;
		$purachased_price  = '';	
		$market_sold_price = '';	
		$market_value = '';	
		
		if(($buy_order_IDS)){
			foreach($buy_order_IDS as $buyerId){	
				
				$search_array['_id'] = $buyerId;
				$search_array['trigger_type'] = 'barrier_trigger';
				$this->mongo_db->where($search_array);
				/*$this->mongo_db->sort(array('_id' => 'desc'));*/
				$responseArr = $this->mongo_db->get('buy_orders');
				$getBuy_arr  = iterator_to_array($responseArr);
				$purachased_price  += num($getBuy_arr[0]['market_value']);
				$market_sold_price += num($getBuy_arr[0]['market_sold_price']);
			}
			
			$market_sold_priceAll  =   num($market_sold_price) ;
			$purachased_priceAll   =   num($purachased_price) ;
			
			$soldPurchase =   (num($market_sold_priceAll) - num($purachased_priceAll));
			$avgProfit    =   ($soldPurchase /  $purachased_priceAll) * 100;
			return 	$avgProfit;
		}else{
		  return 0;	
		}
	}
	
	public function child_buy_order($id) {
		
		$mongoID = $this->mongo_db->mongoId($id);
		$this->mongo_db->order_by(array('created_date'=>-1));
		$this->mongo_db->limit(50);
		$this->mongo_db->where(array('buy_parent_id' => $mongoID));
		$get     = $this->mongo_db->get('buy_orders');
		$get_arr = iterator_to_array($get);
		return $get_arr;
	}//child_buy_order
	
	public function percentile_coin_meta($coin) {
		
		//$mongoID = $this->mongo_db->mongoId($id);
		//$this->mongo_db->order_by(array('created_date'=>-1));
		$this->mongo_db->limit(50);
		$this->mongo_db->where(array('coin' => $coin));
		$get     = $this->mongo_db->get('coin_meta_hourly_percentile');
		$get_arr = iterator_to_array($get);
		return $get_arr;
	}//percentile_coin_meta
	
	
    public function barr_percentile_coin_meta($coin) {
		
		//$mongoID = $this->mongo_db->mongoId($id);
		//$this->mongo_db->order_by(array('created_date'=>-1));
		$this->mongo_db->limit(50);
		$this->mongo_db->where(array('coin' => $coin));
		$get     = $this->mongo_db->get('coin_meta');
		$get_arr = iterator_to_array($get);
		return $get_arr;
	}//barr_percentile_coin_meta
	
	
	//get_buy_order
	public function get_buy_order($id) {
		$timezone = $this->session->userdata('timezone');
		if (empty($timezone)) {
			$timezone = 'ASIA/KARACHI';
		}
		$this->mongo_db->where(array('_id' => $id));
		$this->mongo_db->sort(array('_id'  => 'desc'));
		$responseArr = $this->mongo_db->get('buy_orders');

		foreach ($responseArr as $valueArr) {
			$returArr = array();
			if (($valueArr)) {

				$datetime     = $valueArr['created_date']->toDateTime();
				$created_date = $datetime->format(DATE_RSS);

				$datetime = new DateTime($created_date);
				$datetime->format('Y-m-d g:i:s A');

				$new_timezone = new DateTimeZone($timezone);
				$datetime->setTimezone($new_timezone);
				$formated_date_time = $datetime->format('Y-m-d g:i:s A');

				$datetime2     = $valueArr['modified_date']->toDateTime();
				$created_date2 = $datetime2->format(DATE_RSS);

				$datetime2 = new DateTime($created_date2);
				$datetime2->format('Y-m-d g:i:s A');
				$datetime2->setTimezone($new_timezone);
				$formated_date_time2 = $datetime2->format('Y-m-d g:i:s A');

				$returArr['_id']               = $valueArr['_id'];
				$returArr['symbol']            = $valueArr['symbol'];
				$returArr['binance_order_id']  = $valueArr['binance_order_id'];
				$returArr['price']             = $valueArr['price'];
				$returArr['quantity']          = $valueArr['quantity'];
				$returArr['market_value']      = num($valueArr['market_value']);
				$returArr['order_type']        = $valueArr['order_type'];
				$returArr['status']            = $valueArr['status'];
				$returArr['admin_id']          = $valueArr['admin_id'];
				$returArr['trail_check']       = $valueArr['trail_check'];
				$returArr['buy_trail_price']   = $valueArr['buy_trail_price'];
				$returArr['trail_interval']    = $valueArr['trail_interval'];
				$returArr['is_sell_order']     = $valueArr['is_sell_order'];
				$returArr['market_sold_price'] = num($valueArr['market_sold_price']);
				$returArr['sell_order_id']     = $valueArr['sell_order_id'];
				$returArr['auto_sell']         = $valueArr['auto_sell'];
				$returArr['trigger_type']      = $valueArr['trigger_type'];
				$returArr['modified_date']     = $formated_date_time2;
				$returArr['application_mode']  = $valueArr['application_mode'];
				$returArr['inactive_status']   = $valueArr['inactive_status'];
				$returArr['inactive_time']     = $valueArr['inactive_time'];
				$returArr['parent_status']     = $valueArr['parent_status'];
				$returArr['buy_parent_id']     = $valueArr['buy_parent_id'];
				$returArr['order_mode']        = $valueArr['order_mode'];
				$returArr['updated_quantity']        = $valueArr['update_quantity_after_commision_deducted'];
				$returArr['created_date']      = $formated_date_time;
			}
		}

		return $returArr;

	}//end get_buy_order

	
	public function getOrderLog($id,$parent_id) {
		
		
		/*$this->mongo_db->where(array('buy_order_id' => $id));
		//$this->mongo_db->sort(array('_id' => 'DESC'));
		$this->mongo_db->limit(1000);
		$rulesSet_result = $this->mongo_db->get('record_of_rules_for_orders');
		$rulesSet_arr    = iterator_to_array($rulesSet_result);
		echo "<pre>"; print_r($rulesSet_arr); exit;*/
		
		$skip  = 0;
		$limit = 20;
		$mongoID          = $this->mongo_db->mongoId($id);
		$admin_id         = $this->session->userdata('admin_id');
		$application_mode = $this->session->userdata('global_mode');
		$search_array     = array('_id' => $mongoID);
		
		$connetct = $this->mongo_db->customQuery();
		// $cursor = $connetct->buy_orders->find($search_array)->skip($skip)->limit($limit)->sort(array('_id'=>-1));
		$qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
		$cursor = $connetct->buy_orders->find($search_array, $qr);
		$res = iterator_to_array($cursor);
		
		//echo "<pre>";   print_r($res);
		
		
		//echo "****************buy_orders*****************";
		
	
		if($res[0]!=''){
			$market_sold_price   = $res[0]['market_sold_price'];
			$current_order_price = $res[0]['market_value'];
			$current_data2222 = num($market_sold_price) - num($current_order_price);
			
			$profit_data = ($current_data2222 * 100 / $market_sold_price);
			$profit_data = number_format((float) $profit_data, 2, '.', '');		   	 
		}
		// Profit Data Goes herer ************************************* //
		
		// $this->mongo_db->where(array('buy_order_id' => $id));
		// $this->mongo_db->limit(100);
		// $rulesSet_result = $this->mongo_db->get('record_of_rules_for_orders');
		// $rulesSet_arr    = iterator_to_array($rulesSet_result);
		
		// Message Log  Data Goes here ************************************* //
		$mongoID = $this->mongo_db->mongoId($id);
		$this->mongo_db->where(array('order_id' => $id));
		$this->mongo_db->limit(20);
		$this->mongo_db->where('type', 'Message');
		$this->mongo_db->sort(array('_id' => 'DESC'));
		$get     = $this->mongo_db->get('orders_history_log');
		$get_arr = iterator_to_array($get);
		
		
		foreach($get_arr as $record ){
		
		$fullTextBuy   =  explode('<br>',$record['log_msg']);
        $newMessageArr =  preg_replace('/<[^>]*>/', '', $fullTextBuy);
        //$newMessageArr = preg_replace('/<span[^>]+\>/i', '', $fullTextBuy);
		
				if(count($newMessageArr) > 5){
					
					    
						$newMessag      =  $newMessageArr;		
						foreach($newMessageArr as $newItem){
							   		
								$splitArray  =  explode(':',$newItem);
								
								if (strpos($splitArray[0], 'is_swing_status_buy_Rule_1_yes') !== false) {
										$rukle  = 1;
										break;
								}else if (strpos($splitArray[0], 'is_swing_status_buy_Rule_2_yes') !== false) {
										$rukle  = 2;
										break;
								}else if (strpos($splitArray[0], 'is_swing_status_buy_Rule_3_yes') !== false) {
										$rukle  = 3;
										break;
								}else if (strpos($splitArray[0], 'is_swing_status_buy_Rule_4_yes') !== false) {
										$rukle  = 4;
										break;
								}else if (strpos($splitArray[0], 'is_swing_status_buy_Rule_5_yes') !== false) {
										$rukle  = 5;
										break;
								}else if (strpos($splitArray[0], 'is_swing_status_buy_Rule_6_yes') !== false) {
										$rukle  = 6;
										break;
								}else if (strpos($splitArray[0], 'is_swing_status_buy_Rule_7_yes') !== false) {
										$rukle  = 7;
										break;
								}else if (strpos($splitArray[0], 'is_swing_status_buy_Rule_8_yes') !== false) {
										$rukle  = 8;
										break;
								}else if (strpos($splitArray[0], 'is_swing_status_buy_Rule_9_yes') !== false) {
										$rukle  = 9;
										break;
								}else if (strpos($splitArray[0], 'is_swing_status_buy_Rule_10_yes') !== false) {
										$rukle  = 10;
										break;
								}
						}
				}//if(count($newMessageArr) > 5){
		}
		//echo "<pre>"; print_r($newMessag); exit;
		if(count($newMessag) > 5){
			
						$step = 0;
						$last = count($data);
						$last--;
						$abcd = array();
						$fianlArr  = array();
						$returArr  = array();
						foreach($newMessag as $key=>$item){
							
						         $arrayEachindex  =  explode(':',$item);
							
								 if($arrayEachindex!=''){  
									  $ist     = (strip_tags(trim($arrayEachindex[0]))); 	 
									  $second  = (strip_tags(trim($arrayEachindex[1]))); 	
									  
										    if($ist=='is_swing_status_buy_Rule_'.$rukle.'_yes'){
											$returArr['Swing_status_rule'] = !empty($second) ? $second : 'null';  
											}else if($ist=='current_swing_point'){
											$returArr['Current_swing_point'] = !empty($second) ? $second : 'null';
											}else if($ist=='Recommended_swing_status'){
											$returArr['Recommended_swing_status'] = !empty($second) ? $second : 'null';
											}else if($ist=='is_trigger_status_buy_Rule_'.$rukle.'_yes'){
											$returArr['is_trigger_status_buy_Rule'] = !empty($second) ? $second : 'null';
											}else if($ist=='Recommended_trigger_status'){
											$returArr['Recommended_trigger_status'] = !empty($second) ? $second : 'null';
											}else if($ist=='last_barrier_value'){
											$returArr['last_barrier_value'] = !empty($second) ? $second : 'null';
											}else if($ist=='total_bid_quantity_for_barrier_range'){
											$returArr['total_bid_quantity_for_barrier_range'] = !empty($second) ? $second : '';
											}else if($ist=='Recommended_bid_quantity'){
											$returArr['Recommended_bid_quantity'] = !empty($second) ? $second : 'null';
											}else if($ist=='current_market_price'){
											$returArr['current_market_price'] =  !empty($second) ? $second : 'null';
											}else if($ist=='last_barrrier_value'){
											$returArr['last_barrrier_value'] =  !empty($second) ? $second : 'null';   
											}else if($ist=='barrier_value_range'){
											$returArr['barrier_value_range'] =  !empty($second) ? $second : 'null';
											}else if($ist=='bid_quantity'){
											$returArr['bid_quantity'] =  !empty($second) ? $second : 'null';
											}else if($ist=='is_bid_quantity_buy_Rule_'.$rukle.'_yes'){
											$returArr['Is_bid_quantity_buy_Rule_'] =  ($second) ? $second : 'null';
											}else if($ist=='is_down_pressure_buy_Rule_'.$rukle.'_yes'){
											$returArr['Is_down_pressure_buy_Rule'] =  !empty($second) ? $second : 'null';
											}else if($ist=='current_down_pressure'){
											$returArr['current_down_pressure'] =  !empty($second) ? $second : 'null';
											}else if($ist=='recommended_down_pressure'){
											$returArr['recommended_down_pressure'] =  !empty($second) ? $second : 'null';
											}else if($ist=='is_big_buyers_Rule_'.$rukle.''){
											$returArr['is_big_buyers_Rule_'] =  !empty($second) ? $second : 'null';
											}else if($ist=='big_buyers_percentage'){
											$returArr['big_buyers_percentage'] =  !empty($second) ? $second : 'null';
											}else if($ist=='recommended_percentage'){
											$returArr['recommended_percentage'] = !empty($second) ? $second : 'null';
											}else if($ist=='is_big_black_closest_wall_buy_Rule_'.$rukle.'_yes'){
											$returArr['is_big_black_closest_wall_buy_Rule_'] =  !empty($second) ? $second : 'null'; 
											}else if($ist=='closest_black_bottom_wall_value'){
											$returArr['closest_black_bottom_wall_value'] =  !empty($second) ? $second : 'null';
											}else if($ist=='recommended_closest_black_wall'){
											$returArr['recommended_closest_black_wall'] =  !empty($second) ? $second : 'null';
											}else if($ist=='is_big_yellow_closest_wall_buy_Rule_'.$rukle.'_yes'){
											$returArr['is_big_yellow_closest_wall_buy_Rule_'] =  !empty($second) ? $second : 'null';
											}else if($ist=='closest_yellow_bottom_wall_value'){
											$returArr['closest_yellow_bottom_wall_value'] =  !empty($second) ? $second : 'null';
											}else if($ist=='recommended_closest_yellow_bottom_wall_value'){
											$returArr['recommended_closest_yellow_bottom_wall_value'] =  !empty($second) ? $second : 'null';
											}else if($ist=='is_seven_levele_pressure_buy_Rule_'.$rukle.'_yes'){
											$returArr['is_seven_levele_pressure_buy_Rule_'] =  !empty($second) ? $second : 'null';  
											}else if($ist=='seven_levele_pressure_value'){
											$returArr['seven_levele_pressure_value'] =  !empty($second) ? $second : 'null';  
											}else if($ist=='recommended_seven_levele_pressure_value'){
											$returArr['recommended_seven_levele_pressure_value'] =  !empty($second) ? $second : 'null';  
											}else if($ist=='buyer_vs_seller_rule_Rule_'.$rukle.'_buy_enable'){
											$returArr['buyer_vs_seller_rule_Rule_'] = ($second) ? $second : 'null';
											}else if($ist=='current_buyer_vs_seller'){
											$returArr['current_buyer_vs_seller'] =  !empty($second) ? $second : 'null';  
											}else if($ist=='recommended_buyer_vs_seller'){
											$returArr['recommended_buyer_vs_seller'] =  !empty($second) ? $second : 'null';  
											}else if($ist=='last_candle_type'){
											$returArr['last_candle_type'] =  !empty($second) ? $second : 'null';  
											}else if($ist=='recommended_candle_type'){
											$returArr['recommended_candle_type'] =  !empty($second) ? $second : 'null';  
											}else if($ist=='is_last_rejection_candle_Rule_'.$rukle.'_buy_enable'){
											$returArr['is_last_rejection_candle_Rule_'] =  ($second) ? $second : 'null';
											}else if($ist=='last_rejection_candle_type'){
											$returArr['last_rejection_candle_type'] =  ($second) ? $second : 'null';
											}else if($ist=='recommended_rejection_candle_type'){
											$returArr['recommended_rejection_candle_type'] =  ($second) ? $second : 'null';
											}else if($ist=='is_last_200_contracts_buy_vs_sell_Rule_'.$rukle.'_buy_enable'){
											$returArr['is_last_200_contracts_buy_vs_sell_Rule_'] = ($second) ? $second : 'null';  
											}else if($ist=='last_200_buy_vs_sell'){
											$returArr['last_200_buy_vs_sell'] =  ($second) ? $second : '';
											}else if($ist=='recommended_last_200_contracts_buy_vs_sell'){
											$returArr['recommended_last_200_contracts_buy_vs_sell'] =  ($second) ? $second : 'null';
											}else if($ist=='is_last_200_contracts_timeRule_'.$rukle.'_buy_enable'){
											$returArr['is_last_200_contracts_timeRule_'] =  ($second) ? $second : 'null';
											}else if($ist=='last_200_contracts_time'){
											$returArr['last_200_contracts_time'] =  ($second) ? $second : '';
											}else if($ist=='recommended_last_200_contracts_time'){
											$returArr['recommended_last_200_contracts_time'] =  ($second) ? $second : 'null';
											}else if($ist=='is_last_qty_buyers_vs_sellerRule_'.$rukle.'_buy_enable'){
											$returArr['is_last_qty_buyers_vs_sellerRule_'] =  ($second) ? $second : 'null';
											}else if($ist=='last_qty_buyers_vs_seller'){
											$returArr['last_qty_buyers_vs_seller'] =  ($second) ? $second : 'null';
											}else if($ist=='recommended_is_last_qty_buyers_vs_seller'){
											$returArr['recommended_is_last_qty_buyers_vs_seller'] =  ($second) ? $second : 'null';
											}else if($ist=='is_last_qty_timeRule_'.$rukle.'_buy_enable'){
											$returArr['is_last_qty_timeRule_'] =  ($second) ? $second : 'null';
											}else if($ist=='recommended_last_qty_time'){
											$returArr['recommended_last_qty_time'] =  ($second) ? $second : 'null';
											}else if($ist=='is_scoreRule_'.$rukle.'_buy_enable'){
											$returArr['is_scoreRule_'] =  ($second) ? $second : 'null';    
											}else if($ist=='score'){
											$returArr['score'] =  ($second) ? $second : 'null';   
											}else if($ist=='recommended_score'){
											$returArr['recommended_score'] =  ($second) ? $second : 'null';   
								            }
						   } 
						}
						$returArr['profit_data'] =  ($profit_data) ? $profit_data : '';   
						//$returArr['rule'] =  ($rulesSet_arr[0]->rule) ? $rulesSet_arr[0]->rule : '';   	
						$returArr['rule'] =  ($rukle) ? $rukle : '';   	
		}
		
            $newarry['Swing_status_rule'] = !empty($returArr['Swing_status_rule']) ? $returArr['Swing_status_rule'] : '';   ; 
            $newarry['Current_swing_point'] =  !empty($returArr['Current_swing_point']) ? $returArr['Current_swing_point'] : '';   ; 
            $newarry['Recommended_swing_status'] =  !empty($returArr['Recommended_swing_status']) ? $returArr['Recommended_swing_status'] : '--';   ; 
            $newarry['Recommended_trigger_status'] = !empty($returArr['Recommended_trigger_status']) ? $returArr['Recommended_trigger_status'] : 'null';   ; 
            $newarry['last_barrier_value'] =  !empty($returArr['last_barrier_value']) ? $returArr['last_barrier_value'] : 'null';   ; 
            $newarry['total_bid_quantity_for_barrier_range'] =  !empty($returArr['total_bid_quantity_for_barrier_range']) ? $returArr['total_bid_quantity_for_barrier_range'] : 'null';   ; 
            $newarry['Recommended_bid_quantity'] =  !empty($returArr['Recommended_bid_quantity']) ? $returArr['Recommended_bid_quantity'] : 'null';   ; 
            $newarry['current_market_price'] = !empty($returArr['current_market_price']) ? $returArr['current_market_price'] : 'null';   ; 
            $newarry['last_barrrier_value'] =  !empty($returArr['last_barrrier_value']) ? $returArr['last_barrrier_value'] : 'null';   ; 
            
			$newarry['barrier_value_range'] =  !empty($returArr['barrier_value_range']) ? $returArr['barrier_value_range'] : 'null';   ; 
            $newarry['bid_quantity'] =  !empty($returArr['bid_quantity']) ? $returArr['bid_quantity'] : 'null';   ; 
            $newarry['Is_bid_quantity_buy_Rule_'] =  !empty($returArr['Is_bid_quantity_buy_Rule_']) ? $returArr['Is_bid_quantity_buy_Rule_'] : 'null';   ; 
			
            $newarry['Is_down_pressure_buy_Rule'] = !empty($returArr['Is_down_pressure_buy_Rule']) ? $returArr['Is_down_pressure_buy_Rule'] : 'null';   ; 
            $newarry['is_big_buyers_Rule_'] =  !empty($returArr['is_big_buyers_Rule_']) ? $returArr['is_big_buyers_Rule_'] : 'null';   ;  
            $newarry['big_buyers_percentage'] =  !empty($returArr['big_buyers_percentage']) ? $returArr['big_buyers_percentage'] : 'null';   ; 
            $newarry['recommended_percentage'] =  !empty($returArr['recommended_percentage']) ? $returArr['recommended_percentage'] : 'null';   ; 
			
            $newarry['is_big_black_closest_wall_buy_Rule_'] = !empty($returArr['is_big_black_closest_wall_buy_Rule_']) ? $returArr['is_big_black_closest_wall_buy_Rule_'] : 'null';   ; 
			
			$newarry['closest_black_bottom_wall_value'] = !empty($returArr['closest_black_bottom_wall_value']) ? $returArr['closest_black_bottom_wall_value'] : 'null';   ; 
			$newarry['recommended_closest_black_wall'] = !empty($returArr['recommended_closest_black_wall']) ? $returArr['recommended_closest_black_wall'] : 'null';   ; 
			
            $newarry['is_big_yellow_closest_wall_buy_Rule_'] =  !empty($returArr['is_big_yellow_closest_wall_buy_Rule_']) ? $returArr['is_big_yellow_closest_wall_buy_Rule_'] : 'null';   ; 
            $newarry['is_seven_levele_pressure_buy_Rule_'] =  !empty($returArr['is_seven_levele_pressure_buy_Rule_']) ? $returArr['is_seven_levele_pressure_buy_Rule_'] : 'null';   ; 
            $newarry['seven_levele_pressure_value'] =  !empty($returArr['seven_levele_pressure_value']) ? $returArr['seven_levele_pressure_value'] : 'null';   ; 
			
            $newarry['recommended_seven_levele_pressure_value'] =  !empty($returArr['recommended_seven_levele_pressure_value']) ? $returArr['recommended_seven_levele_pressure_value'] : 'null';   ; 
            $newarry['buyer_vs_seller_rule_Rule_'] =  !empty($returArr['buyer_vs_seller_rule_Rule_']) ? $returArr['buyer_vs_seller_rule_Rule_'] : 'null';   ; 
            $newarry['last_candle_type'] = !empty($returArr['last_candle_type']) ? $returArr['last_candle_type'] : 'null';   ; 
            $newarry['is_last_rejection_candle_Rule_'] =  !empty($returArr['is_last_rejection_candle_Rule_']) ? $returArr['is_last_rejection_candle_Rule_'] : 'null';   ;  
			
            $newarry['last_rejection_candle_type'] =  !empty($returArr['last_rejection_candle_type']) ? $returArr['last_rejection_candle_type'] : 'null';   ; 
            $newarry['is_last_200_contracts_buy_vs_sell_Rule_'] =  !empty($returArr['is_last_200_contracts_buy_vs_sell_Rule_']) ? $returArr['is_last_200_contracts_buy_vs_sell_Rule_'] : 'null';   ; 
            $newarry['last_200_buy_vs_sell'] =   !empty($returArr['last_200_buy_vs_sell']) ? $returArr['last_200_buy_vs_sell'] : 'null';   ; 
            $newarry['recommended_last_200_contracts_buy_vs_sell'] =  !empty($returArr['recommended_last_200_contracts_buy_vs_sell']) ? $returArr['recommended_last_200_contracts_buy_vs_sell'] : 'null';   ; 
			
            $newarry['is_last_200_contracts_timeRule_'] =  !empty($returArr['is_last_200_contracts_timeRule_']) ? $returArr['is_last_200_contracts_timeRule_'] : 'null';   ; 
            $newarry['last_200_contracts_time'] =  !empty($returArr['last_200_contracts_time']) ? $returArr['last_200_contracts_time'] : 'null';   ; 
            $newarry['is_last_qty_buyers_vs_sellerRule_'] =  !empty($returArr['is_last_qty_buyers_vs_sellerRule_']) ? $returArr['is_last_qty_buyers_vs_sellerRule_'] : 'null';   ; 
            $newarry['last_qty_buyers_vs_seller'] =  !empty($returArr['last_qty_buyers_vs_seller']) ? $returArr['last_qty_buyers_vs_seller'] : 'null';   ; 
			
			
			$created_date   =  $get_arr[0]['created_date'];
			$created_date_obj = $created_date->toDateTime();
			$created_date_h = $created_date_obj->format("Y-m-d H:i:s a");
			
            $newarry['Is_last_qty_timeRule'] =  !empty($returArr['Is_last_qty_timeRule']) ? $returArr['Is_last_qty_timeRule'] : 'null';   ; 
			$newarry['created_date'] =  !empty($created_date_h) ? $created_date_h : 'null';   ; 
            $newarry['Is_scoreRule'] =  !empty($returArr['Is_scoreRule']) ? $returArr['Is_scoreRule'] : 'null';   ; 
            $newarry['score'] =  !empty($returArr['score']) ? $returArr['score'] : 'null';   ; 
            $newarry['profit_data'] = !empty($returArr['profit_data']) ? $returArr['profit_data'] : 'null';   ; 
			$newarry['rules_no'] = !empty($returArr['rule']) ? $returArr['rule'] : 'null';   ; 
			//echo "<pre>";  print_r($newarry); exit;
			
		return $newarry;
	}
	
	
	
	public function getOrderLog222($id) {
		
		$mongoID = $this->mongo_db->mongoId($id);
		$this->mongo_db->where(array('order_id' => $id));
		$this->mongo_db->where('show_error_log', 'yes');
		$this->mongo_db->limit(10000);
		$this->mongo_db->where('type', 'Message');
		$this->mongo_db->sort(array('_id' => 'DESC'));
		$get     = $this->mongo_db->get('orders_history_log');
		$get_arr = iterator_to_array($get);
		//////////////////////////////////   second
		/*$mongoID = $this->mongo_db->mongoId($id);
		$this->mongo_db->where(array('order_id' => $id));
		$this->mongo_db->where('show_error_log', 'yes');
		$this->mongo_db->where('type', 'Message Sell');
		$this->mongo_db->limit(10000);
		$this->mongo_db->sort(array('_id' => 'ASC'));
		$get     = $this->mongo_db->get('orders_history_log');
		$get_arrSell = iterator_to_array($get);*/
		
		
		echo "<pre>";  print_r($get_arr); exit;
		
		
		//$finaleArray  =  array_merge($get_arr[0],$get_arrSell[0]);  
		$fullTextBuy  =  explode('<br>',$get_arr[0]['log_msg']);
		//$fullTextSel  =  explode('<br>',$get_arrSell[0]['log_msg']);
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		if($fullTextSel!=''){
		  foreach($fullTextSel as $row){
			$newDataRow =  explode('=>',$row);	
			$finalRow   =  implode(':',$newDataRow);
		    $fullTextSelArray[] = htmlspecialchars(trim(strip_tags($finalRow)));  	
		  }
		}
				$step = 0;
				$last = count($data);
				$last--;
				$abcd = array();
				$fianlArr  = array();
				
				foreach($fullTextBuy as $key=>$item){
                    $out = array();
				   foreach(explode(':',$item) as $keyss => $value){
						 if($value!=''){  
							if($keyss==0){
							  $ist  = htmlspecialchars(strip_tags(trim($value))); 	
							  
							}  
							if($keyss==1){
							  $second  =  htmlspecialchars(strip_tags(trim($value))); 	
							  
							}
						 }
				   }
				 if(($ist) && ($second)){
				  $fianlArr[$ist] = $second;  
				 }
				}
				
				
			if($fullTextSelArray!=''){
				$step = 0;
				$last = count($data);
				$last--;
				$abcd = array();
				
				foreach($fullTextSelArray as $key=>$item){
                    $out = array();
				   foreach(explode(':',$item) as $keyss => $value){
						if($value!=''){
							if($keyss==0){
							  $ist     = htmlspecialchars(strip_tags(trim($value))); 
							 
							}  
							if($keyss==1){
							  $second  =  htmlspecialchars(strip_tags(trim($value))); 	
							}
						}
				   }
				 if(($ist) && ($second)){
				   $fianlArr[$ist] = $second;  
				  }
				}
			}
				
				
		return $fianlArr;
	}
	
	
	
	public function testfunction(){
		
		 //Check Filter Data
        $connetct    = $this->mongo_db->customQuery();
        $responseArr = $this->mongo_db->get('orders');
        $responseArr = iterator_to_array($responseArr);
		echo "<prE>";  print_r($responseArr); exit;
		
	} 
	
	
}
?>