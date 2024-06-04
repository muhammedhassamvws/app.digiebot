<?php
class mod_box_trigger_3 extends CI_Model {

	function __construct() {
		# code...
	}

	
	//////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////
	///////////                           /////////////////////////////
	//////////                           /////////////////////////////
	///////// Box_Trigger_3 Setting     /////////////////////////////
	////////                           /////////////////////////////
	///////                           /////////////////////////////
	//////                           /////////////////////////////
	/////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////

	public function create_box_trigger_3_setting($date = '',$order_mode='live') {
		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}
		//Call Function For Getting coins
		$all_coins_arr = $this->get_all_coins();
		if (count($all_coins_arr) > 0) {
			foreach ($all_coins_arr as $data) {
				$coin_symbol = $data['symbol'];
				//Call Function to get
				$current_candel = $this->get_current_candel($prevouse_date, $coin_symbol);
				if (count($current_candel) > 0) {
					foreach ($current_candel as $candel_data) {
						$candel_data = (array) $candel_data;
						$this->save_trigger_3_setting($candel_data,$order_mode);
					}
				} //End of check of Candel Exist
			}
		} //End of if Coin Exist
	} //End of create_box_trigger_3_setting

	public function save_trigger_3_setting($candel_data,$order_mode) {
		extract($candel_data);
		///////////////////////////////////
		//////////////////////////////////
		/////////////////////////////////
		$box_progress_status = '';
		/************Check if candle is demand candle*******/
		if ($candle_type == 'demand') {
			//get Last  Lowest Swing Point Candle Detail
			$swing_point_candle_detail = $this->get_last_low_swing_point_candle_detail($coin,$openTime_human_readible);
			if(!empty($swing_point_candle_detail)){
				$swing_point_candle_date = $swing_point_candle_detail[0]['openTime_human_readible'];
				//Call Function To Find created state
				$created_candle_detail = $this->find_created_state_between_current_candle_and_low_swing_candle($swing_point_candle_date,$openTime_human_readible,$coin);
				if(!empty($created_candle_detail)){
					$high_value_of_created_candle = $created_candle_detail[0]['high'];
					if($high > $high_value_of_created_candle){
						/*Check if current demand high value is greater from previos
						Demand candle*/
					$is_high_value_greater	= $this->is_current_high_value_greater_from_previous_high_value($swing_point_candle_date,$openTime_human_readible,$coin,$high);
						if($is_high_value_greater){
							$box_progress_status = 'updated';
						}else{
							$box_progress_status = 'ignored';
						}
					}else{
						$box_progress_status = 'ignored';
					}
				}else{//End of created_candle_detail
					$box_progress_status = 'created';
				}//End of elsle created_candle_detail
			}//End of swing_point_candle_detail
		}//End of check of demand candle


		$insert_arr = array(
			'global_swing_parent_status' => $global_swing_parent_status,
			'open' => (float)$open,
			'high' => (float)$high,
			'low' =>(float)$low,
			'close' => (float)$close,
			'openTime_human_readible' => $openTime_human_readible,
			'open_time_object' => $timestampDate,
			'coin' => $coin,
			'candle_type' => $candle_type,
			'box_progress_status' => $box_progress_status,
		);
		$this->mongo_db->where(array('open_time_object' => $this->mongo_db->converToMongodttime($openTime_human_readible), 'coin' => $coin));
		$response_obj = $this->mongo_db->get('box_trigger_3_setting');
		$response_arr = iterator_to_array($response_obj);

		if (count($response_arr) > 0) {
			$this->mongo_db->where(array('open_time_object' => $this->mongo_db->converToMongodttime($openTime_human_readible), 'coin' => $coin));
			$this->mongo_db->set($insert_arr);
			$this->mongo_db->update('box_trigger_3_setting');
		} else {
			$this->mongo_db->insert('box_trigger_3_setting', $insert_arr);

		}
	} //End of save_trigger_3_setting

	public function get_current_candel($curretn_date, $coin_symbol) {
		$curretn_date = $this->mongo_db->converToMongodttime($curretn_date);
		$this->mongo_db->where(array('timestampDate' => $curretn_date, 'coin' => $coin_symbol));
		$previouse_candel_result = $this->mongo_db->get('market_chart');
		$previouse_candel_arr = iterator_to_array($previouse_candel_result);
		return $previouse_candel_arr;
	} //End of get_current_candel

	public function get_last_low_swing_point_candle_detail($coin,$date){
		$this->mongo_db->where_lt('open_time_object', $this->mongo_db->converToMongodttime($date));
		$this->mongo_db->order_by(array('open_time_object' => 'DESC'));
		$this->mongo_db->where_in('global_swing_parent_status', array('LL', 'HL'));
		$this->mongo_db->where(array('coin'=>$coin));
		$this->mongo_db->limit(1);
		$res_object = $this->mongo_db->get('box_trigger_3_setting');
		$res_arr = iterator_to_array($res_object);
		return $res_arr;
	}//End of get_last_low_swing_point_candle_detail

	public function find_created_state_between_current_candle_and_low_swing_candle($start_date,$end_date,$coin){
			$this->mongo_db->where_lt('open_time_object', $this->mongo_db->converToMongodttime($end_date));
			$this->mongo_db->where_gte('open_time_object', $this->mongo_db->converToMongodttime($start_date));
			$this->mongo_db->where(array('coin' => $coin,'box_progress_status'=>'created'));
			$this->mongo_db->limit(1);
			$res_obj = $this->mongo_db->get('box_trigger_3_setting');	
			return  iterator_to_array($res_obj);
	}//End of find_created_state_between_current_candle_and_low_swing_candle

	public function is_current_high_value_greater_from_previous_high_value($start_date,$end_date,$coin,$high_value){
			$this->mongo_db->where_lt('open_time_object', $this->mongo_db->converToMongodttime($end_date));
			$this->mongo_db->where_gte('open_time_object', $this->mongo_db->converToMongodttime($start_date));
			$this->mongo_db->where(array('coin' => $coin,'candle_type'=>'demand'));
			$res_obj = $this->mongo_db->get('box_trigger_3_setting');	
			$res_arr = iterator_to_array($res_obj);

			$high_value_arr = array();
			if(!empty($res_arr)){
				foreach ($res_arr as $row) {
					array_push($high_value_arr, $row['high']);
				}//End of Foreach
			}//if array not empty

			$max_high_value = max($high_value_arr);
			$response = false;
			if($high_value>$max_high_value){
				$response = true;
			}
			return $response;  
	}//End of is_current_high_value_greater_from_previous_high_value

					/*************************
						******************
						  ************
						     *****/						
					


	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	//////////////                                       ///////////
	/////////////                                       ////////////
	////////////   Box_Trigger_3 Orders Ready for Buy  /////////////
	///////////                                       //////////////
	//////////                                       ///////////////
	//////////                                      ////////////////	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////


	public function make_orders_ready_for_box_trigger_3($order_level){
		//Call Function For Getting coins
		$all_coins_arr = $this->get_all_coins();
		$order_mode = 'live';
		$trigger_type = 'box_trigger_3';
		if (count($all_coins_arr) > 0) {
			$order_mode = array('test_live', 'live');
			foreach ($all_coins_arr as $data) {
				$coin_symbol = $data['symbol'];
		
				$trigger_setting = $this->get_trigger_global_setting($coin_symbol,$trigger_type,$order_mode,$order_level);

				$log_arr = array('Coin'=>$coin_symbol);
				$cancel_trade_rule_on_of = '<span style="color:green">OFF</span>';
				if(!empty($trigger_setting)){
					$trigger_setting = $trigger_setting[0];
					$cancel_trade = $trigger_setting['cancel_trade']; 
			        $look_back_hour = $trigger_setting['look_back_hour'];
			        $date = date('Y-m-d H:00:00');
					if ($cancel_trade == 'cancel') {
						$cancel_trade_rule_on_of = '<span style="color:green">YES</span>';
						$this->cancel_wait_for_buy_orders($date,$look_back_hour);
					}

					//Function check high open rule
					$check_high_open_rule_on_off  = 'OFF';
					$check_high_open = $trigger_setting['check_high_open'];
					if($check_high_open  == 'yes'){   
					  $check_high_open_rule_on_off  = 'YES';
					}

					if(true){
							   $current_market_price = '';
							   $orders_arr  = $this->get_orders_for_which_market_price_reached($order_mode,$current_market_price,$coin_symbol);
							   if(!empty($orders_arr)){
							   		foreach ($orders_arr as $row) {
							   			$start_date  = $row['created_date'];
							   			$order_id = $row['_id'];
							   			
							   			// echo $check_high_open_rule_on_off;

							   			if($check_high_open_rule_on_off =='YES'){

							   				$is_high_close_rule = $this->is_high_close_rule($start_date,$order_id,$coin_symbol);

							   				if($is_high_close_rule){
							   					$this->update_order_status_as_ready($order_id);
							   				}
							   			}else{
							   				$this->update_order_status_as_ready($order_id);
							   			}//if high open off
							   		}//End of order for each
							   }//End of Orders Not Empty
					}//End of Rejection and Blue Candle and blue candle
				}//End of trigger setting
			
			}//End of coin forech
		}//End of all Coins
	}//End of make_orders_ready_for_box_trigger_3

	public function is_high_close_rule($start_date,$order_id,$coin){
		$end_date = date('Y-m-d H:00:00');
		$seconds = (string) $start_date / 1000;
		$str_date = date("Y-m-d H:i:s", $seconds);
		$str_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($str_date)));		
		$start_date_obj = $this->mongo_db->converToMongodttime($str_date);
		$to_date_object = $this->mongo_db->converToMongodttime($end_date);
		$this->mongo_db->where_gte('timestampDate', $start_date_obj);
		$this->mongo_db->where_lt('timestampDate', $to_date_object);
		$this->mongo_db->where('coin',$coin);
		$this->mongo_db->order_by(array('timestampDate'=>1));
		$current_candel_result = $this->mongo_db->get('market_chart');
		$current_candel_arr = iterator_to_array($current_candel_result);


		$index = 0;
		$response = false;
		if(!empty($current_candel_arr)){
			$order_created_candle = (array)$current_candel_arr[0];
			$order_created_close_value = $order_created_candle['close'];
			foreach ($current_candel_arr as $row) {
				if($index == 0){
					$index++;
					continue;
				}
				$previous_index = $index-1;
				if($row['open'] < $row['close']){
					if($row['close'] > $order_created_close_value){
						$response = true;
						break;
					}
				}else if($row['open'] == $row['close']){
					if($row['open']>= $current_candel_arr[$previous_index]['close']){
						if($row['close'] > $order_created_close_value){
							$response = true;
							break;	
						}
					}
				}//End of else if 
			 $index++;
			}//End of For Each
		}//End of if array is not Empty
		return $response;
	}//End of is_orders_ready_for_buy

	public function update_order_status_as_ready($order_id){
		$upd_arr =  array('new_ready_status'=>'new_ready_status');
		$this->mongo_db->where(array('_id' =>$order_id));
		$this->mongo_db->set($upd_arr);
		$res = $this->mongo_db->update('buy_orders');
	}//End of update_order_status_as_ready

	public function get_orders_for_which_market_price_reached($order_mode,$current_market_price,$coin_symbol){
		$this->mongo_db->where_in('order_mode',$order_mode);
		//$this->mongo_db->where_gte('price', $current_market_price);
		$this->mongo_db->where_ne('new_ready_status','new_ready_status');
		$this->mongo_db->where(array('status' => 'new', 'trigger_type' => 'box_trigger_3', 'buy_order_status_new_filled' => 'wait_for_buyed', 'symbol' => $coin_symbol));
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		return iterator_to_array($buy_orders_result);
	}//End of get_orders_for_which_market_price_reached

	public function is_previous_candle_is_blue($coin_symbol){
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		$timestampDate = $this->mongo_db->converToMongodttime($prevouse_date);
		$this->mongo_db->where(array('timestampDate' => $timestampDate, 'coin'=>$coin_symbol));
		$current_candel_result = $this->mongo_db->get('market_chart');
		$current_candel_arr = iterator_to_array($current_candel_result);

		$response = false;
		if(!empty($current_candel_arr)){
			$current_candel_arr = $current_candel_arr[0];
			$current_open = $current_candel_arr['open'];
			$current_close = $current_candel_arr['close'];

			if($current_open<$current_close){
				$response = true;
			}else if($current_open == $current_close){
				$prevouse_date = date('Y-m-d H:00:00', strtotime('-2 hour'));
				$timestampDate = $this->mongo_db->converToMongodttime($prevouse_date);
				$this->mongo_db->where(array('timestampDate' => $timestampDate, 'coin'=>$coin_symbol));
				$prevouse_candel_result = $this->mongo_db->get('market_chart');
				$prevouse_candel_arr = iterator_to_array($prevouse_candel_result);

				if(!empty($prevouse_candel_arr)){
					$prevouse_candel_arr = $prevouse_candel_arr[0];
					$prevouse_close = $prevouse_candel_arr['close'];
					if($current_open>$prevouse_close){
						$response = true;
					}
				}//End of previous not empty
			}//End of else
		}//if array not empty
		return $response;
	}//End of is_previous_candle_is_blue

	public function get_trigger_global_setting($coin,$trigger_type,$order_mode,$order_level){
		$this->mongo_db->where(array('triggers_type'=>$trigger_type,'order_mode'=>$order_mode,'coin'=>$coin,'trigger_level'=>$order_level));
		$response_obj = $this->mongo_db->get('trigger_global_setting');
		return iterator_to_array($response_obj);
	}//End of get_trigger_global_setting



	/*************************************************/
	/************************************************/
	/***********************************************/
	public function aggrisive_define_percentage_followup($date,$type='',$trigger_type,$coin_symbol,$current_market_price,$apply_factor_value){

		if(!$apply_factor_value){
			$apply_factor_value = 0.5;
		}

		$orders_arr = $this->get_filled_orders_live($trigger_type,$coin_symbol);
		$date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		$created_date = date('Y-m-d G:i:s');
		$high_value = 0;
		if (count($orders_arr) > 0) {
			foreach ($orders_arr as $data) {
				$id = $data['_id'];
				$admin_id = $data['admin_id'];
				$buy_price = $data['market_value'];
				$iniatial_trail_stop = $data['iniatial_trail_stop'];
			
				$profit_in_percentage_price = $buy_price+ (($buy_price/100)*.5);
				$current_market_trailing_percentage = $current_market_price - (($current_market_price/100)*.5);

				if($current_market_price >= $profit_in_percentage_price){					
					$updated_stop_loss = $iniatial_trail_stop + (($current_market_price/100)*$apply_factor_value);
					
					if($updated_stop_loss > $current_market_trailing_percentage){
						$updated_stop_loss = $current_market_trailing_percentage;
					}

					if($updated_stop_loss > $iniatial_trail_stop){

						$upd_data22 = array('iniatial_trail_stop'=>$updated_stop_loss);
						
						$this->mongo_db->where(array('_id' => $id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						$log_msg = " Order Stop Loss Updated From <b>(".num($iniatial_trail_stop).")</b> To  <b>(" .num($updated_stop_loss).') </b> By Aggrisive Define Percentage Followup';
						$this->insert_order_history_log($id, $log_msg, 'stop_loss', $admin_id, $created_date);

					}// %%%%%%%%%% -- End of Updated Stop Loss -- %%%%%%%%%%
					
				}//End Of If Profit is Greater .5 percent				
			}//End of orders Loop
		}//End of check of Orders Exist
	} //End of aggrisive_define_percentage_followup

	public function get_filled_orders_live($trigger_type,$coin_symbol) {
		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where_in('order_mode', array('test_live', 'live'));
		$where['status'] = 'FILLED';
		$where['trigger_type'] = $trigger_type;
		$where['symbol'] = $coin_symbol;
		$this->mongo_db->where($where);
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		return iterator_to_array($buy_orders_result);
	} //End OF get_filled_orders_live

    //get_market_value
	public function get_market_value($symbol = '') {
		if ($symbol != "") {
			$global_symbol = $symbol;
		} else {
			$global_symbol = $this->session->userdata('global_symbol');
		}
		//Get Market Prices
		$this->mongo_db->where(array('coin' => $global_symbol));
		$this->mongo_db->limit(1);
		$this->mongo_db->sort(array('_id' => 'desc'));
		$responseArr = $this->mongo_db->get('market_prices');
		foreach ($responseArr as $valueArr) {
			if (!empty($valueArr)) {
				$market_value = $valueArr['price'];
			}
		}
		return num($market_value);
	} //End get_market_value

	//insert_order_history_log
	public function insert_order_history_log($id, $log_msg, $type, $user_id, $created_date) {
		$ins_error = array(
			'order_id' => $this->mongo_db->mongoId($id),
			'log_msg' => $log_msg,
			'type' => $type,
			'created_date' => $this->mongo_db->converToMongodttime($created_date),
		);
		 $this->mongo_db->insert('orders_history_log', $ins_error);
		
		return true;
	} //end insert_order_history_log

	//add_notification
	public function add_notification($order_id, $type, $message, $admin_id) {
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
		
		$this->mongo_db->insert('notification', $ins_data);
		return true;
	} //end add_notification()

	  //get_all_coins
    public function get_all_coins(){

		$where_arr = array(
            'user_id' => 'global',
            'exchange_type' => 'binance',
		);
		
		$this->mongo_db->sort(array('_id' => -1));
		// $this->mongo_db->where(array('user_id' => 'global'));
		$this->mongo_db->where($where_arr);
		$get_coins = $this->mongo_db->get('coins');
		$coins_arr = iterator_to_array($get_coins);
		return $coins_arr;
    }//end get_all_coins


    public function get_new_orders($coin_symbol,$order_mode,$current_market_price,$order_level) {
		$this->mongo_db->where_in('order_mode',$order_mode);
		$this->mongo_db->where_gte('price', $current_market_price);
		$this->mongo_db->where_ne('is_sell_order','yes');

		$where['status'] = 'new';
		$where['trigger_type'] = 'box_trigger_3';
		$where['buy_order_status_new_filled'] = 'wait_for_buyed';
		$where['symbol'] = $coin_symbol;
		//$where['new_ready_status'] = 'new_ready_status';
		$where['order_level'] = $order_level;

		$this->mongo_db->where($where);
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		return iterator_to_array($buy_orders_result);
	} //End of get_new_orders


	public function get_stop_loss_orders($market_price,$coin_symbol){
		$where = array('is_sell_order'=>array('$ne'=>'sold'),'order_mode'=>array('$in'=>array('test_live','live')),'trigger_type'=>'box_trigger_3','symbol'=>$coin_symbol,'iniatial_trail_stop'=>array('$gte'=>$market_price),'status'=>'FILLED');
		$db = $this->mongo_db->customQuery();
		$buy_orders_result = $db->buy_orders->find($where);
		return iterator_to_array($buy_orders_result);
	}//End of get_stop_loss_orders

	public function get_profit_sell_orders($target_sell_price,$coin_symbol){
		$where = array('is_sell_order'=>array('$ne'=>'sold'),'order_mode'=>array('$in'=>array('test_live','live')),'trigger_type'=>'box_trigger_3','symbol'=>$coin_symbol,'market_value'=>array('$lte'=>$target_sell_price),'status'=>'FILLED');
		$db = $this->mongo_db->customQuery();
		$buy_orders_result = $db->buy_orders->find($where);
		return iterator_to_array($buy_orders_result);
	}//End of get_profit_sell_orders

 	public function create_new_orders_by_Box_Trigger_3_live($date = '') {

		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		$order_mode = array('test_live', 'live');
		$all_coins_arr = $this->get_all_coins();
		if (!empty($all_coins_arr)) {
			foreach ($all_coins_arr as $data) {
				$coin_symbol = $data['symbol'];

				$res_coin_setting_arr = $this->get_trigger_setting($coin_symbol,$triggers_type ='box_trigger_3','live');
			
				$buy_price_percentage = 30;
				$stop_loss_percent = 4;
				$sell_price_percent = 3;
				if (!empty($res_coin_setting_arr)) {
					foreach ($res_coin_setting_arr as $res_coin_setting) {
						$buy_price_percentage = $res_coin_setting['buy_price'];
						$stop_loss_percent = $res_coin_setting['stop_loss'];
						$sell_price_percent = $res_coin_setting['sell_price'];
					}//End of Foreach res_coin_setting_arr
				}//End of coin setting not empty
				
				//Call function to Get Lowest Value
				$resp_data = $this->find_previous_lowest_value($prevouse_date, $coin_symbol);
				$lowest_value = $resp_data['global_swing_status'];
				$swing_low_candle_date = $resp_data['candle_date'];

				$candle_setting_for_orders = $this->get_demand_candel($prevouse_date, $coin_symbol);
				$box_progress_status = '';
				$is_close_greater = false;
				if(!empty($candle_setting_for_orders)){
					$candle_setting_for_orders = $candle_setting_for_orders[0];
					$box_progress_status = $candle_setting_for_orders['box_progress_status'];
					$demand_candel_high_value = $candle_setting_for_orders['high'];
					$demand_candel_low_value = $candle_setting_for_orders['low'];
					$demand_close_value = $candle_setting_for_orders['close'];
					$trigger_3_setting_id = $candle_setting_for_orders['_id'];

					$is_close_greater = $this->is_close_greater_before_order_creating($swing_low_candle_date,$coin_symbol,$demand_close_value);
				}//End of Not Empty
				

				/*************** Calculate values****************/
				$differenc_value = $demand_candel_high_value - $lowest_value;
				$is_demand_high_less = true;
				if($differenc_value <0){
					$is_demand_high_less = false;
				}

				$buy_price = $lowest_value + ($differenc_value * ($buy_price_percentage / 100));
				//Initial Stop Loss
				$iniatial_trail_stop = $buy_price - ($buy_price / 100) * $stop_loss_percent;

				$sell_price = $buy_price + ($buy_price / 100) * $sell_price_percent;
				$update_prices_arr = array('price' => $buy_price, 'iniatial_trail_stop' => $iniatial_trail_stop, 'sell_price' => $sell_price);
				/***********************End of calculate values**/

				/******* Update Orders ********/
				if($box_progress_status == 'updated'){
					$this->is_order_update($coin_symbol,$update_prices_arr);
				}//End of update order 
				/********Update Orders *******/

				if($box_progress_status == 'created'){
					//if created mode come lock order updation
					$this->order_mode_lock_for_update($coin_symbol);

					//If order creation ignored
					if(!$is_close_greater){
						$this->update_ignore_status($trigger_3_setting_id);
					}
					
				}

				$parent_orders_arr = $this->get_parent_orders($coin_symbol,$order_mode);

				if(!empty($parent_orders_arr) && $box_progress_status == 'created' && $is_demand_high_less && $is_close_greater){

					foreach ($parent_orders_arr as $row) {
						$buy_parent_id = $row['_id'];
						$coin_symbol = $row['symbol'];
						$buy_quantity = $row['quantity'];
						$buy_trigger_type = $row['trigger_type'];
						$admin_id = $row['admin_id'];
						$application_mode = $row['application_mode'];
						$order_mode = $row['order_mode'];
						$order_level = $row['order_level'];
						/******* Order Creation Array********/
							$ins_data = array(
								'price' => (float)$buy_price,
								'quantity' => (float)$buy_quantity,
								'symbol' => $coin_symbol,
								'order_type' => 'MARKET_ORDER',
								'admin_id' => $admin_id,
								'trigger_type' => 'box_trigger_3',
								'sell_price' => (float)$sell_price,
								'created_date' => $this->mongo_db->converToMongodttime($date),
								'modified_date' => $this->mongo_db->converToMongodttime($date),
								'trail_check'=>'no',
								'trail_interval'=>'0',
								'buy_trail_price'=>'0',
								'status'=>'new',
								'auto_sell'=>'no',
								'buy_parent_id'=>$buy_parent_id,
								'iniatial_trail_stop'=>$iniatial_trail_stop,
								'buy_order_status_new_filled'=>'wait_for_buyed',
								'application_mode'=>$application_mode,
								'order_mode'=>$order_mode,
								'order_mode_lock_for_update'=>0,
								'parent_aggrive_stop_loss_compare_value'=>$demand_close_value,
								'demand_close_value'=>$demand_close_value,
								'order_level'=>$order_level
							);
						/********End Order Creation array***/


						/********* Create Orders*****/
						$buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);
						if ($application_mode == 'live') {
							$order_mode = 'Live';
						} else {
							$order_mode = 'Test_live';
						}
						$log_msg = "Buy (" . $order_mode . ") Order was Created at Price " . number_format($buy_price, 8);
						$created_date = date('Y-m-d G:i:s');
						$this->insert_order_history_log($buy_order_id, $log_msg, 'buy_created', $admin_id, $created_date);
						$message = "Buy Market Order is <b>Created</b> as status new";
						$this->add_notification($buy_order_id, 'buy', $message, $admin_id);
						/***********End of Create Order***/
					}//End of foreach parent_orders_arr 
				}//End of if orders is not empty
			}//End of foreach coin
		}//End of if coin Exist

	} //End of create_new_orders_by_Box_Trigger_3_live


	public function update_ignore_status($id){
		$this->mongo_db->where(array('_id'=>$id));
		$upd_arr = array('box_progress_status'=>'ignored','update_reason'=>'status update to ignore from created because the created statsu is ignored due to the stairs rule');
		$this->mongo_db->set($upd_arr);
		$this->mongo_db->update('box_trigger_3_setting');
		return true;
	}//End of update_ignore_status

	public function is_order_update($coin_symbol,$update_prices_arr){
		$created_date = date('Y-m-d G:i:s');

		$order_mode = array('test_live', 'live');
		$this->mongo_db->where_in('order_mode',$order_mode);
		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where(array('symbol' => $coin_symbol,'order_mode_lock_for_update'=>0,'trigger_type' => 'box_trigger_3','status'=>'new'));
		$responese_obj = $this->mongo_db->get('buy_orders');
		$responese_arr = iterator_to_array($responese_obj);

		if(!empty($responese_arr)){
			foreach ($responese_arr as $row) {
				$order_id = $row['_id'];
				$admin_id = $row['admin_id'];
				$application_mode = $row['application_mode'];
				$old_order_price = $row['price'];
				if($price>$old_order_price){
					$this->mongo_db->where(array('_id' => $order_id));
					$this->mongo_db->set($update_prices_arr);
					//Update data in mongoTable
					$this->mongo_db->update('buy_orders');
					////////////////////////////// Order History Log /////////////////////////////
					extract($update_prices_arr);
					
					$log_msg = "Buy  Order was Updated To Price " . num($price);
					$this->insert_order_history_log($order_id, $log_msg, 'buy_created', $admin_id, $created_date);
					$message = "Buy Market Order is <b>Updated</b> as status new";
					$this->add_notification($order_id, 'buy', $message, $admin_id);
				}//End of new price is greater Form Old Price
			}//For each of orders
		}//End of if order not empty
	}//End of is_order_update

	public function order_mode_lock_for_update($symbol){
		$upd_status = array(
		'order_mode_lock_for_update' => 1,
		);
		$conn = $this->mongo_db->customQuery();
		$res = $conn->buy_orders->updateMany(array('order_mode_lock_for_update' =>0,'symbol'=>$symbol), array('$set' => $upd_status));
	}//End of order_mode_lock_for_update

	public function find_previous_lowest_value($date, $coin_symbol) {
		$this->mongo_db->where(array('coin' => $coin_symbol));
		$this->mongo_db->where_lte('open_time_object', $this->mongo_db->converToMongodttime($date));
		$this->mongo_db->where_in('global_swing_parent_status', array('LL', 'HL'));
		$this->mongo_db->order_by(array('open_time_object' => -1));
		$this->mongo_db->limit(1);
		$previouse_candel_result = $this->mongo_db->get('box_trigger_3_setting');
		$previouse_candel_arr = iterator_to_array($previouse_candel_result);

		$global_swing_status = 0;
		$data = array();
		if (!empty($previouse_candel_arr)) {
			$global_swing_status = $previouse_candel_arr[0]['low'];
			$data['global_swing_status'] = $global_swing_status;
			$data['candle_date'] = $previouse_candel_arr[0]['openTime_human_readible'];
		}
		return $data;
	} //End of find_previous_lowest_status


	public function get_trigger_setting($coin_symbol,$triggers_type ='box_trigger_3',$order_mode) {
		$this->mongo_db->where(array('coins' => $coin_symbol, 'triggers_type' =>$triggers_type,'order_mode'=>$order_mode));
		$res_coin_setting = $this->mongo_db->get('setting_triggers_collections');
		return iterator_to_array($res_coin_setting);
	} //End of get_trigger_setting


	public function get_demand_candel($prevouse_date, $coin_symbol) {
		$open_time_object = $this->mongo_db->converToMongodttime($prevouse_date);
		  $this->mongo_db->where(array('open_time_object' => $open_time_object, 'coin' => $coin_symbol,'candle_type'=>'demand'));
		$previouse_candel_result = $this->mongo_db->get('box_trigger_3_setting');
		return iterator_to_array($previouse_candel_result);
	} //End of get_demand_candel

	public function get_parent_orders($coin_symbol,$order_mode) {
		$order_mode = array('test_live', 'live');
		$this->mongo_db->where_ne('inactive_status', 'inactive');
		$this->mongo_db->where_ne('pause_status', 'pause');
		$this->mongo_db->where_in('order_mode',$order_mode);
		$this->mongo_db->where(array('parent_status' => 'parent', 'trigger_type' => 'box_trigger_3','symbol'=>$coin_symbol,'status'=>'new'));
		$parent_orders_object = $this->mongo_db->get('buy_orders');
		return iterator_to_array($parent_orders_object);
	} //End of get_parent_orders

	///////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////
	///////////////////                                       //////
	//////////////////                                       //////
	///////////////// Box_Trigger_3_aggressive_trail_stop   //////   ////////////////                        				//////
	///////////////                                       //////
	//////////////                                       //////
	//////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////

	public function Box_Trigger_3_aggressive_trail_stop($date = '', $coin_symbol = '', $stop_loss_percent = '') {
		$current_date = date('Y-m-d H:00:00', strtotime($date));

		$orders_arr = $this->get_filled_orders();
		if(count($orders_arr)>0){
			foreach ($orders_arr as $buy_orders) {
			    $id = $buy_orders['_id'];
				$parent_aggrive_stop_loss_compare_value = $buy_orders['parent_aggrive_stop_loss_compare_value'];
				$iniatial_trail_stop_parent = $buy_orders['iniatial_trail_stop'];
				$admin_id = $buy_orders['admin_id'];

				///////////////////////////////
				/////////////////////////////



						//Check of previous candel is Demond Candel
						$this->mongo_db->where(array('openTime_human_readible' => $current_date, 'coin' => $coin_symbol));
						$current_candel_object = $this->mongo_db->get('box_trigger_3_setting');
						$current_candel_arr = iterator_to_array($current_candel_object);
						$iniatial_trail_stop = '';

						$updated = '';
						if (count($current_candel_arr) > 0) {
							$current_close_value = $current_candel_arr[0]['close'];
							if ($current_close_value > $parent_aggrive_stop_loss_compare_value) {
								$iniatial_trail_stop = $current_close_value - ($current_close_value / 100) * $stop_loss_percent;
								$updated = 'updated';
							} else {
								$iniatial_trail_stop = $iniatial_trail_stop_parent;
							}
						}


					        $upd_data22 = array(
								'iniatial_trail_stop' => $iniatial_trail_stop,
								'iniatial_trail_stop_copy' => $iniatial_trail_stop,
								'parent_aggrive_stop_loss_compare_value' => $iniatial_trail_stop,
							);
							
							if($updated == 'updated'){

								if($iniatial_trail_stop > $parent_aggrive_stop_loss_compare_value){
									$this->mongo_db->where(array('_id' => $id));
									//Update data in mongoTable
								    $this->mongo_db->set($upd_data22);
									$this->mongo_db->update('buy_orders');
									$log_msg = " stop loss updated with(stop_loss_rule_1) from   ".num($parent_aggrive_stop_loss_compare_value)." To ".num($iniatial_trail_stop);
									$this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $date);
							   }
							}




				///////////////////////////////
				//////////////////////////////
			}
		}
	} //End of Box_Trigger_3_aggressive_trail_stop

	public function cancel_wait_for_buy_orders($date,$look_back_hour) {
		$datetime = date("Y-m-d H:i:s");
		$look_back_hour = ($look_back_hour)?$look_back_hour:20;
		$date = date('Y-m-d H:00:00', strtotime('-'.$look_back_hour.' hour', strtotime($date)));
		$obj_date = $this->mongo_db->converToMongodttime($date);
		$this->mongo_db->where_lte('created_date', $obj_date);
		$this->mongo_db->where_in('order_mode', array('test_live', 'live'));
		$this->mongo_db->where_ne('parent_status','parent');
		$this->mongo_db->where(array('trigger_type' => 'box_trigger_3', 'status' => 'new'));
		$result_object = $this->mongo_db->get('buy_orders');
		$order_arr = iterator_to_array($result_object);

		if (count($order_arr) > 0) {
			foreach ($order_arr as $data) {
				$id = $data['_id'];
				$admin_id = $data['admin_id'];
				$upd_data22 = array(
					'status' => 'canceled',
					'modified_date' => $this->mongo_db->converToMongodttime($datetime),
				);
				$this->mongo_db->where(array('_id' => $id));
				$this->mongo_db->set($upd_data22);
				//Update data in mongoTable
				$this->mongo_db->update('buy_orders');
				$log_msg = " Order Has been canceled Due to look_back_hour Rule:".$look_back_hour;

				$this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $datetime);

			}
		}
	} //End of cancel_wait_for_buy_orders

	public function check_rejection_candel($prevouse_date,$coin_symbol,$number_or_rule){

		

		if($number_or_rule =='B'){
			$search_arr = array('bottom_demand_rejection','bottom_supply_rejection');
		}else if($number_or_rule =='S'){
			$search_arr = array('bottom_supply_rejection');
		}else if('D'){
			$search_arr = array('bottom_demand_rejection');
		} 

		

		$this->mongo_db->where_in('rejected_candle',$search_arr);	
		
		$timestampDate = $this->mongo_db->converToMongodttime($prevouse_date);
		$this->mongo_db->where(array('timestampDate' => $timestampDate, 'coin'=>$coin_symbol));
		$current_candel_result = $this->mongo_db->get('market_chart');
		$current_candel_arr = iterator_to_array($current_candel_result);
		$response = false;
		if(count($current_candel_arr)>0){
			$response  = true;
		}

	
		return $response;
	}//End of check_rejection_candel




	public function is_close_greater_before_order_creating($start_date,$coin,$demand_close_value){
		$end_date = date('Y-m-d H:00:00');

		$start_date_obj = $this->mongo_db->converToMongodttime($start_date);
		$to_date_object = $this->mongo_db->converToMongodttime($end_date);
		$this->mongo_db->where_gte('timestampDate', $start_date_obj);
		$this->mongo_db->where_lt('timestampDate', $to_date_object);
		$this->mongo_db->where('coin',$coin);
		$this->mongo_db->order_by(array('timestampDate'=>1));
		$current_candel_result = $this->mongo_db->get('market_chart');
		$current_candel_arr = iterator_to_array($current_candel_result);

		$index = 0;
		$response = false;
		if(!empty($current_candel_arr)){
			
			$order_created_candle = (array)$current_candel_arr[0];
			$order_created_close_value = $order_created_candle['close'];

			foreach ($current_candel_arr as $row) {

				if($row['close'] > $demand_close_value){
					$response = false;
					break;
				}else{
					$response = true;
				}
				$index++;
			}//End of For Each
		}//End of if array is not Empty
		return $response;
	}//End of is_orders_ready_for_buy


	public function get_coin_meta_data($symbol){
		$this->mongo_db->where('coin', $symbol);
		$res = $this->mongo_db->get('coin_meta');
		return  iterator_to_array($res);
	}//End of get_coin_meta_data


	public function get_coin_meta_hourly_percentile($symbol){
		$this->mongo_db->where('coin', $symbol);
		$res = $this->mongo_db->get('coin_meta_hourly_percentile');
		return  iterator_to_array($res);
	}//End of get_coin_meta_hourly_percentile


	public function insert_developer_log($id, $log_msg, $type, $created_date, $show_error_log) {
		$ins_error = array(
			'order_id' => $this->mongo_db->mongoId($id),
			'log_msg' => $log_msg,
			'type' => $type,
			'created_date' => $this->mongo_db->converToMongodttime($created_date),
			'show_error_log' => $show_error_log,
		);
		$this->mongo_db->insert('orders_history_log', $ins_error);
		return true;
	} //End of insert_developer_log


	public function get_user_trading_ip($id){
		$this->mongo_db->where(array('_id' => $id));
		$get_users = $this->mongo_db->get('users');
		$users_arr = iterator_to_array($get_users);
		$users_arr = $users_arr[0];
		return $users_arr['trading_ip'];
	}// %%%%%%%%% End of get_user_trading_ip %%%%%%%%%%%%%


	public function order_ready_for_buy_by_ip($buy_order_id, $buy_quantity, $market_value, $coin_symbol,$admin_id,$trading_ip,$trigger_type,$type){
		$created_date = date('Y-m-d H:i:s');
		$created_date = $this->mongo_db->converToMongodttime($created_date);
		$insert_arr = array('buy_order_id'=>$buy_order_id,'buy_quantity'=>$buy_quantity,'market_value'=>$market_value,'coin_symbol'=>$coin_symbol,'admin_id'=>$admin_id,'trading_ip'=>$trading_ip,'trigger_type'=>$trigger_type,'order_type'=>$type,'order_status'=>'ready','created_date'=>$created_date);
		return $this->mongo_db->insert('ready_orders_for_buy_ip_based',$insert_arr);
	}//End of order_ready_for_buy_by_ip


	public function order_ready_for_sell_by_ip($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id,$trading_ip,$trigger_type,$type){

		$created_date = date('Y-m-d G:00:00');
		$created_date = $this->mongo_db->converToMongodttime($created_date);
		$insert_arr['order_id'] = $order_id;
		$insert_arr['quantity'] = $quantity;
		$insert_arr['market_price'] = $market_price;
		$insert_arr['coin_symbol'] = $coin_symbol;
		$insert_arr['admin_id'] = $admin_id;
		$insert_arr['buy_orders_id'] = $buy_orders_id;
		$insert_arr['trading_ip'] = $trading_ip;
		$insert_arr['trigger_type'] = $trigger_type;
		$insert_arr['order_type'] = $type;
		$insert_arr['order_status'] = 'ready';
	
	    return $this->mongo_db->insert('ready_orders_for_sell_ip_based',$insert_arr);
	}// %%%%%%%%%%%%%% End of %%%%%%%%%%%%%%%%

	public function lock_barrier_trigger_true_rules($coin_symbol,$rule_number,$type,$market_price,$log,$order_level)
	{	
		$new_log = $log['Rule_'.$rule_number];

		$new_message = '';
		if(!empty($log['Rule_'.$rule_number])){
			foreach ($log['Rule_'.$rule_number] as $key => $value) {
				$new_message .='['.$key.'] => '.$value.'<br>'; 
			}
		}

		$created_date = date('Y-m-d G:i:s');
		$created_date = $this->mongo_db->converToMongodttime($created_date);
		$rule_no = 'rule_no_'.$rule_number;
		$market_price = (float)$market_price;
		$insert_arr = array('coin_symbol'=>$coin_symbol,'rule_number'=>$rule_no,'type'=>$type,'market_price'=>$market_price,'log'=>$new_message,'created_date'=>$created_date,'trigger_type'=>'box_trigger_3','order_level'=>$order_level);
			
		$resp = $this->update_lock_barrier_trigger_rules($type,$coin_symbol,$rule_no,$order_level);
		if($resp){
			$id  = (string)$resp;
			$this->mongo_db->where(array('_id'=>$id));
			$this->mongo_db->set($insert_arr);
			$res = $this->mongo_db->update('barrier_trigger_true_rules_collection');

			echo 'updated';
		}else{
			$this->mongo_db->insert('barrier_trigger_true_rules_collection', $insert_arr);
			echo 'inserted';
		}
		return true;
	}//End lock_barrier_trigger_true_rules

	public function update_lock_barrier_trigger_rules($type,$coin_symbol,$rule_no,$order_level){
		
		$db = $this->mongo_db->customQuery();
		$start_date = date('Y-m-d H:i:00');
		$start_date = $this->mongo_db->converToMongodttime($start_date);
		$where['coin_symbol'] = $coin_symbol;
		$where['type'] = $type;
		$where['created_date'] = array('$gte'=>$start_date);
		$where['rule_number'] = $rule_no;	
		$where['trigger_type'] = 'box_trigger_3';	
		$where['order_level'] = $order_level;	
		
		$response = $db->barrier_trigger_true_rules_collection->find($where);
		$response_1 = iterator_to_array($response);

		$response = false;
		if(count($response_1)>0){
			foreach ($response_1 as $row) {
				$response = $row['_id'];
				break;
			}
		}
		return $response;
	}//update_lock_barrier_trigger_rules
}//-- End Of Box Model --
?>