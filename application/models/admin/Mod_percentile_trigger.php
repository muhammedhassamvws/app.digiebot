<?php
class mod_percentile_trigger extends CI_Model {

	function __construct() {
		# code...
	}


	public function get_coin_meta_data($symbol){
		$this->mongo_db->where('coin', $symbol);
		$res = $this->mongo_db->get('coin_meta');
		return  iterator_to_array($res);
	}//End of get_coin_meta_data


	public function get_parent_orders($coin_symbol,$order_level) {
		$order_mode = array('live');
		$this->mongo_db->where_ne('inactive_status', 'inactive');
		$this->mongo_db->where_ne('pause_status', 'pause');
		$this->mongo_db->where_in('order_mode', $order_mode);
		$this->mongo_db->where(array('status' => 'new', 'trigger_type' => 'barrier_percentile_trigger', 'symbol' => $coin_symbol, 'parent_status' => 'parent','order_level'=>$order_level));
		$parent_orders_object = $this->mongo_db->get('buy_orders');
		return iterator_to_array($parent_orders_object);
	} //End of get_parent_orders



	//insert_order_history_log
	public function insert_order_history_log($id, $log_msg, $type, $user_id, $created_date, $show_error_log = 'no') {
		$created_date = date('Y-m-d G:i:s');
		$ins_error = array(
			'order_id' => $this->mongo_db->mongoId($id),
			'log_msg' => $log_msg,
			'type' => $type,
			'show_error_log' => $show_error_log,
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




	public function get_stop_loss_updating_orders($trigger_type, $coin_symbol) {
		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where_in('order_mode', array('live'));
		$this->mongo_db->where(array('status' => 'FILLED', 'trigger_type' => $trigger_type, 'symbol' => $coin_symbol,'is_order_ready_for_stop_loss'=>'yes'));
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		return iterator_to_array($buy_orders_result);
	} //End of get_stop_loss_updating_orders

	public function is_market_deep_price_order($market_price, $coin_symbol){

		$where['status'] = 'FILLED';
		$where['symbol'] = $coin_symbol;
		$where['trigger_type'] = 'barrier_trigger';
		$where['is_sell_order'] = array('$ne'=>'sold');
		$where['order_mode'] = array('$in'=>array('live'));
		$where['$or'] = array(array('price'=>array('$gt'=>$market_price)),array('market_deep_price'=>array('$gt'=>$market_price)));
		$this->mongo_db->where($where);
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$order_arr  = iterator_to_array($buy_orders_result);

		$upd_data = array(
			'market_deep_price' =>(float) $market_price,
		);
		
		$created_date = date('Y-m-d G:i:s');
		if(!empty($order_arr)){
			foreach ($order_arr as $row) {
				$id = $row['_id'];
				$admin_id = $row['admin_id'];
				$market_deep_price = $row['market_deep_price'];

				if($market_price < $market_deep_price || empty($market_deep_price)){
					$this->mongo_db->where(array('_id' => $id));
					$this->mongo_db->set($upd_data);
					$this->mongo_db->update('buy_orders');

					$from = '';
					if(!empty($market_deep_price)){
						$from = num($market_deep_price);
					}

					$log_msg = " Market Deep Price Updated From " . $from. " To " . num($market_price);
					$this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $created_date);
				}//if market price less then deeper price
			}//End of foreach
		}//End of check empty
	

	}//End of is_market_deep_price_order




	public function update_stop_loss_close_to_deep_price($update_price,$iniatial_trail_stop,$id,$admin_id){
			$created_date = date('Y-m-d G:i:s');
			
			if($iniatial_trail_stop < $update_price){
				/*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
				$upd_data22 = array(
					'iniatial_trail_stop' => $update_price,
				);

				$this->mongo_db->where(array('_id' => $id));
				$this->mongo_db->set($upd_data22);
				$this->mongo_db->update('buy_orders');

				$log_msg = "Stop Loss Update From <b>".num($iniatial_trail_stop)."</b>   To <b>".num($update_price)."</b> As  <span style='color:green'> <b>0.2% profit  </b></span>of Buy Price ";
				$this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $created_date);
				/*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
			}
			return true;
	}//End of update_stop_loss_close_to_deep_price

	

	public function stop_loss_big_wall_barrier_trigger($coin_symbol, $current_market_price) {

		$trigger_type = 'barrier_percentile_trigger';
		$big_wall_data = $this->get_down_big_price($coin_symbol);
		$level_five_big_wall_price = $big_wall_data['level_five_big_wall_price'];
		$level_three_big_wall_price = $big_wall_data['level_three_big_wall_price'];
		$level_two_big_wall_price = $big_wall_data['level_two_big_wall_price'];

		$orders_arr = $this->get_stop_loss_updating_orders($trigger_type, $coin_symbol);

		if (count($orders_arr) > 0) {
			foreach ($orders_arr as $data) {

				$id = $data['_id'];
				$admin_id = $data['admin_id'];
				$buy_price = $data['market_value'];
				$iniatial_trail_stop = $data['iniatial_trail_stop'];
				$iniatial_trail_stop_copy = $data['iniatial_trail_stop_copy'];
				$sell_price  = $data['sell_price'];
				$coin_symbol = $data['symbol'];
				$trigger_type = $data['trigger_type'];
				$created_date = date('Y-m-d G:i:s');
				$is_market_deep_range = $data['is_order_ready_for_stop_loss'];

				$market_deep_true = false;
				if($is_market_deep_range =='yes'){
					$market_deep_true = true;
				}

				$market_deep_price = 0;
				if(isset($data['iniatial_trail_stop']) && $data['iniatial_trail_stop'] !=''){
					$market_deep_price = $data['iniatial_trail_stop'];
				}

				$one_percent_profit   = (float) $buy_price + (($buy_price / 100) * 1);
				$two_percent_profit   = (float) $buy_price + (($buy_price / 100) * 2);
				$three_percent_profit = (float) $buy_price + (($buy_price / 100) * 3);

				//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

				if($market_deep_true){
					
					if($current_market_price >= $three_percent_profit){
							//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
							if (($level_two_big_wall_price > $iniatial_trail_stop)) {
								///////////////////End of update trail stop
								///////////////////////////////////////////
								$upd_data22 = array(
									'iniatial_trail_stop' => (float)$level_two_big_wall_price,
								);
								$this->mongo_db->where(array('_id' => $id));
								$this->mongo_db->set($upd_data22);
								$this->mongo_db->update('buy_orders');
								$log_msg = "  Order Stop Loss Big Wall Updated By <b>DC 2</b> From  " . num($iniatial_trail_stop) . " To " . num($level_two_big_wall_price);
								$this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $created_date);
							} //End of if trail stop greater
							//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
					}else if($current_market_price >= $two_percent_profit){
								//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
						if (($level_three_big_wall_price > $iniatial_trail_stop)) {

							///////////////////End of update trail stop
							///////////////////////////////////////////
							$upd_data22 = array(
								'iniatial_trail_stop' => (float)$level_three_big_wall_price,
							);
		
							$this->mongo_db->where(array('_id' => $id));
							$this->mongo_db->set($upd_data22);
							$this->mongo_db->update('buy_orders');
		
							$log_msg = "  Order Stop Loss Big Wall Updated By <b>DC 3</b> From  " . num($iniatial_trail_stop) . " To " . num($level_three_big_wall_price);
							$this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $created_date);
						} //End of if trail stop greater
					}else if($current_market_price >= $one_percent_profit){
						//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
						if (($level_five_big_wall_price > $iniatial_trail_stop)) {

							///////////////////End of update trail stop
							///////////////////////////////////////////
							$upd_data22 = array(
								'iniatial_trail_stop' => (float)$level_five_big_wall_price,
							);
		
							$this->mongo_db->where(array('_id' => $id));
							$this->mongo_db->set($upd_data22);
							$this->mongo_db->update('buy_orders');
		
							$log_msg = "  Order Stop Loss Big Wall Updated By <b>DC 5</b> From  " . num($iniatial_trail_stop) . " To " . num($level_five_big_wall_price);
							$this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $created_date);
						} //End of if trail stop greater
					}
					
				}//End of is ready update

				 //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

			} //End of order for each
		} //End of if order exist
	} //End of aggrisive_define_percentage_followup

	public function get_down_big_price($coin_symbol) {
		$this->mongo_db->where(array('coin' => $coin_symbol));
		$res_coin_meta_obj = $this->mongo_db->get('coin_meta');
		$res_coin_meta_arr = iterator_to_array($res_coin_meta_obj);


		$level_five_big_wall_price = 0;
		$level_two_big_wall_price = 0;
		$level_three_big_wall_price = 0;
		if (count($res_coin_meta_arr) > 0) {
			$level_five_big_wall_price = $res_coin_meta_arr[0]['down_big_price'];
			$level_two_big_wall_price = $res_coin_meta_arr[0]['level_two_big_wall_price'];
			$level_three_big_wall_price = $res_coin_meta_arr[0]['level_three_big_wall_price'];
		}

		$response['level_five_big_wall_price'] = $level_five_big_wall_price;
		$response['level_three_big_wall_price'] = $level_three_big_wall_price;
		$response['level_two_big_wall_price'] = $level_two_big_wall_price;
		return $response;
	} //End of get_down_big_price


	public function level_five_big_wall_price($coin_symbol) {
		$this->mongo_db->where(array('coin' => $coin_symbol));
		$res_coin_meta_obj = $this->mongo_db->get('coin_meta');
		$res_coin_meta_arr = iterator_to_array($res_coin_meta_obj);


		$level_five_big_wall_price = 0;
		$level_two_big_wall_price = 0;
		$level_three_big_wall_price = 0;
		if (count($res_coin_meta_arr) > 0) {
			$level_five_big_wall_price = $res_coin_meta_arr[0]['down_big_price'];
		}

		return $level_five_big_wall_price;
	} //End of level_five_big_wall_price




	public function barrier_status_down($coin_symbol,$barrier_status,$c_price) {
		$this->mongo_db->limit(1);
		$this->mongo_db->where_lte('barier_value', (float) $c_price);
		$this->mongo_db->order_by(array('created_date' => -1));
		$this->mongo_db->where(array('coin' => $coin_symbol, 'barrier_status' => $barrier_status, 'barrier_type' => 'down'));
		$res_obj = $this->mongo_db->get('barrier_values_collection');
		$res_arr = iterator_to_array($res_obj);

		
		$barier_value = '';

		$data = array();
		if (count($res_arr) > 0) {
			$row = $res_arr[0];
			$barier_value = $row['barier_value'];
			$data['barrier_status'] = $row['barrier_status'];
			$data['barier_value'] = $row['barier_value'];
			$data['human_readible_created_date'] = $row['human_readible_created_date'];
		} //End of Count
		return $barier_value;
	} //End of barrier_status

	public function barrier_status_up($coin_symbol, $barrier_status, $current_market_price) {
		$this->mongo_db->limit(1);
		$this->mongo_db->where_gte('barier_value', (float) $current_market_price);
		$this->mongo_db->order_by(array('created_date' => -1));
		$this->mongo_db->where(array('coin' => $coin_symbol, 'barrier_status' => $barrier_status, 'barrier_type' => 'up'));
		$res_obj = $this->mongo_db->get('barrier_values_collection');
		$res_arr = iterator_to_array($res_obj);

		$data = array();
		if (count($res_arr) > 0) {
			$row = $res_arr[0];
			$data['barrier_status'] = $row['barrier_status'];
			$data['barier_value'] = $row['barier_value'];
			$data['human_readible_created_date'] = $row['human_readible_created_date'];
		} //End of Count
		return $data;
	} //End of barrier_status

	

	public function get_current_barrier_status_up($coin_symbol, $current_market_price) {
		$barrier_status_arr = array('weak_barrier', 'very_strong_barrier', 'strong_barrier');
		$full_arr = array();
		foreach ($barrier_status_arr as $barrier_status) {
			$res = $this->barrier_status_up($coin_symbol, $barrier_status, $current_market_price);
			$full_arr[] = $res;
		}
		foreach ($full_arr as $key => $part) {
			$sort[$key] = strtotime($part['human_readible_created_date']);
		}
		array_multisort($sort, SORT_DESC, $full_arr);
		return $full_arr;
	} //End get_current_swing_point

	public function get_market_volume($market_price, $coin_symbol, $type) {
		var_dump($market_price);
		$price = trim($market_price);
		$price_r = (float) $price;
		$res = array('coin' => $coin_symbol, 'type' => $type, 'price' => $price_r);
		$this->mongo_db->where(array('coin' => $coin_symbol, 'type' => $type, 'price' => $price_r));
		$responseobj = $this->mongo_db->get('market_depth');
		$responseArr = iterator_to_array($responseobj);
		$global_quantity = '';
		if (count($responseArr) > 0) {
			$global_quantity = $responseArr[0]['quantity'];
		}
		return $global_quantity;
	}// --%%%%%%%%%%%%%%%%% End of get_market_volume -- %%%%%%%%%%%%%%%

	
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

	public function get_trigger_global_setting($triggers_type, $order_mode, $coin,$order_level) {
		$this->mongo_db->where(array('triggers_type' => $triggers_type, 'order_mode' => $order_mode, 'coin' => $coin,'trigger_level'=>$order_level));
		$response_obj = $this->mongo_db->get('trigger_global_setting');
		return iterator_to_array($response_obj);
	} //End of get_trigger_global_setting



	public function check_of_previous_buy_order_exist_for_current_user($admin_id, $buy_parent_id) {
		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where_ne('parent_status', 'parent');
		$this->mongo_db->where_in('order_mode', array('live'));
		$this->mongo_db->where_in('status', array('new', 'FILLED','submitted'));
		$this->mongo_db->where(array('admin_id' => $admin_id, 'buy_parent_id' => $buy_parent_id));
		$this->mongo_db->limit(1);
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);

		$response = true;
		if (count($buy_orders_arr) > 0) {
			$response = false;
		}
		return $response;
	} //End of check_of_previous_buy_order_exist_for_current_user

	public function order_setting($coin_symbol, $triggers_type, $order_mode) {
		$this->mongo_db->where(array('coins' => $coin_symbol, 'triggers_type' => $triggers_type, 'order_mode' => $order_mode));
		$res_coin_setting = $this->mongo_db->get('setting_triggers_collections');
		return iterator_to_array($res_coin_setting);
	} //End of order_setting

	public function save_rules_for_orders($buy_order_id, $coin_symbol, $order_type, $rule, $mode) {
		$_date = date('Y-m-d H:i:s');
		$date = $this->mongo_db->converToMongodttime($_date);
		$arr = array('buy_order_id' => $buy_order_id, 'coin_symbol' => $coin_symbol, 'order_type' => $order_type, 'rule' => $rule, 'mode' => $mode,'created_date'=>$date,'human_readible_created_date'=>$_date);
		$this->mongo_db->insert('record_of_rules_for_orders', $arr);
	} //End of save_rules_for_orders

	public function save_order_time_track($coin) {
		$_date = date('Y-m-d H:i:s');
		$date = $this->mongo_db->converToMongodttime($_date);
		$insert_arr = array('coin' => $coin, 'created_time_obj' => $date, 'human_readible_created_time' => $_date);
		$this->mongo_db->insert('order_time_track_collection', $insert_arr);
	} //End of save_order_time_track

	public function is_order_is_created_just_now($coin) {
		$prevouse_date = date('Y-m-d H:i:s', strtotime('-3 minute'));
		$date = $this->mongo_db->converToMongodttime($prevouse_date);
		$this->mongo_db->order_by(array('created_time_obj' => -1));
		$this->mongo_db->where_gte('created_time_obj', $date);
		$this->mongo_db->where(array('coin' => $coin));
		$this->mongo_db->limit(1);
		$res = $this->mongo_db->get('order_time_track_collection');
		$res_arr = iterator_to_array($res);
		$result = true;
		if (!empty($res_arr)) {
			$result = false;
		}
		return $result;
	} //End of is_order_is_created_just_now



	 public function is_market_price_empty($market_price)
	{
		if($market_price == ''){
			exit('remove exit to continue due to empty price');
		}
	}//End of is_market_price_empty

	public function is_sell_order_status_new($order_id){
		$this->mongo_db->where(array('_id'=>$order_id,'status'=>'new'));
		$data = $this->mongo_db->get('orders');
		$resp = false;
		$row = iterator_to_array($data);
		if(count($row)>0){
			$resp = true;
		}
		return $resp;
	}//End of is_sell_order_status_new($order_id)

	public function is_trades_on_of(){
		$automatic_selected = false;
		$custom_selected = false;

		$trading = $this->mongo_db->get('trading_on_off_collection');
		$trading = iterator_to_array($trading);

		foreach ($trading as $row) {
			if($row['type'] == 'automatic_on_of_trading'){
				if($row['status'] == 'on'){
					$automatic_selected = true;
				}
			}

			if($row['type'] == 'custom_on_of_trading'){
				if($row['status'] == 'on'){
					$custom_selected = true;
				}
			}
		}//End of foreach trading

		$is_trades_on_of = false;

		if($automatic_selected && $custom_selected){
			$is_trades_on_of = true;
		}
		return $is_trades_on_of;
	} //-- %%%%%%%%%%%%%%%%%% is_trades_on_of - %%%%%%%%%%%%%%%%%%%%%%%555


	public function last_procedding_candle_status($coin){
		$this->mongo_db->limit(1);
		$this->mongo_db->order_by(array('timestampDate'=>-1));
		$this->mongo_db->where_in('candle_type', array('demand', 'supply'));
		$this->mongo_db->where(array('coin'=>$coin));
		$response = $this->mongo_db->get('market_chart');
		$response = iterator_to_array($response);
		$candle_type = '';
		if(!empty($response)){
			$candle_type = $response[0]['candle_type'];
		}
		return $candle_type;
	}//%%%%%%%%%%%%%   End of last_procedding_candle_status %%%%%%%%%%%%%5
	
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
	}// %%%%%%%%%%%%--- End of order_ready_for_buy_by_ip --%%%%%%%%%%%


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
		$created_date = date('Y-m-d H:i:s');
		$created_date = $this->mongo_db->converToMongodttime($created_date);
		$rule_no = 'rule_no_'.$rule_number;
		$market_price = (float)$market_price;
		$insert_arr = array('coin_symbol'=>$coin_symbol,'rule_number'=>$rule_no,'type'=>$type,'market_price'=>$market_price,'log'=>$log,'created_date'=>$created_date,'trigger_type'=>'barrier_percentile_trigger','order_level'=>$order_level);
	
		$resp = $this->update_lock_barrier_trigger_rules($type,$coin_symbol,$rule_no,$order_level);

		if($resp){
			$id  = (string)$resp;
			$this->mongo_db->where(array('_id'=>$id));
			$this->mongo_db->set($insert_arr);
			$res = $this->mongo_db->update('barrier_trigger_true_rules_collection');
		}else{
			$this->mongo_db->insert('barrier_trigger_true_rules_collection', $insert_arr);
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
		$where['trigger_type'] = 'barrier_percentile_trigger';	
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



	public function get_profit_sell_orders($coin_symbol,$order_level) {

		$this->mongo_db->where_in('order_mode', array('live'));
		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where_ne('is_profit_updated_as_stop_loss', 'yes');
		$this->mongo_db->where_ne('parent_status', 'parent');

		$where['status'] = 'FILLED';
		$where['trigger_type'] = 'barrier_percentile_trigger';
		$where['symbol'] = $coin_symbol;
		if($order_level !=''){
			//$where['order_level'] = $order_level;
		}
		$this->mongo_db->where($where);

		$buy_orders_result = $this->mongo_db->get('buy_orders');
		return iterator_to_array($buy_orders_result);
	} //End of get_profit_sell_orders


	public function get_stop_loss_orders($coin_symbol){
		$this->mongo_db->where_in('order_mode', array('live'));
		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where_ne('parent_status', 'parent');
		$where['status'] = 'FILLED';
		$where['trigger_type'] = 'barrier_percentile_trigger';
		$where['symbol'] = $coin_symbol;
		$this->mongo_db->where($where);
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		return iterator_to_array($buy_orders_result);
	}//End of  get_stop_loss_orders


	
	public function is_order_ready_to_update_stop_loss($coin_symbol, $stop_loss_update_percentage, $current_market_price)
	{
		$stop_loss_active_percentage = $current_market_price - ($current_market_price / 100) * $stop_loss_update_percentage;
		$trigger_type = 'barrier_percentile_trigger';
	

		$orders_arr = $this->get_order_which_not_ready_for_stop_loss($trigger_type, $stop_loss_active_percentage, $coin_symbol);



		$created_date = date('Y-m-d G:i:s');
		if(!empty($orders_arr)){
			foreach ($orders_arr as $data) {
				$id = (string) $data['_id'];
				$admin_id = $data['admin_id'];
				$iniatial_trail_stop = (float)$data['iniatial_trail_stop'];
				$market_value = (float)$data['market_value'];
				$update_price = $market_value + ($market_value / 100) * 0.2;

				$stop_loss_rule = $data['stop_loss_rule'];
				$activate_stop_loss_profit_percentage = $data['activate_stop_loss_profit_percentage'];

				if($stop_loss_rule == 'custom_stop_loss'){
					if($activate_stop_loss_profit_percentage !=''){
						
						$profit_percentage =  $current_market_price - ($current_market_price / 100) * $activate_stop_loss_profit_percentage;

						if($market_value >= $profit_percentage){
							$log_msg = "Order is  Active big wall stop loss after selected profit percentage :<b>".$activate_stop_loss_profit_percentage."</b>" ;
							$this->insert_order_history_log($id, $log_msg, 'stop_loss_ready', $admin_id, $created_date);
						}else{
							continue;
						}

					}else{
						continue;
					}
				}//End of stop loss

				/*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
				$upd_data22 = array(
					'is_order_ready_for_stop_loss' => 'yes',
				);

				$this->mongo_db->where(array('_id' => $id));
				$this->mongo_db->set($upd_data22);
				$this->mongo_db->update('buy_orders');

				$log_msg = "Order is <span style='color:green'>Ready</span> to update stop Loss";
				$this->insert_order_history_log($id, $log_msg, 'stop_loss_ready', $admin_id, $created_date);
				/*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

			//$this->update_stop_loss_close_to_deep_price($update_price,$iniatial_trail_stop,$id,$admin_id);			

			}//End of for Each
		}//End of if condition

	}//End of is_market_deep_ready_stop_loss_update

	public function get_order_which_not_ready_for_stop_loss($trigger_type, $diff_price, $coin_symbol) {
		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where_ne('is_order_ready_for_stop_loss', 'yes');
		$this->mongo_db->where_in('order_mode', array('live'));
		$this->mongo_db->where(array('status' => 'FILLED', 'trigger_type' => $trigger_type, 'symbol' => $coin_symbol));
		$this->mongo_db->where_lte('market_value', $diff_price);
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		return iterator_to_array($buy_orders_result);
	} //End of get_order_which_not_ready_for_stop_loss


	public function aggrisive_define_percentage_followup($trigger_type,$coin_symbol,$current_market_price,$apply_factor_value){

		if(!$apply_factor_value){
			$apply_factor_value = 0.5;
		}

		$orders_arr = $this->get_orders_profit_updated_as_stop_loss($trigger_type,$coin_symbol);

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
	

	public function get_orders_profit_updated_as_stop_loss($trigger_type,$coin_symbol) {
		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where_in('order_mode', array('live'));
		$where['status'] = 'FILLED';
		$where['trigger_type'] = $trigger_type;
		$where['symbol'] = $coin_symbol;
		$where['is_profit_updated_as_stop_loss'] = 'yes';
		$this->mongo_db->where($where);
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		return iterator_to_array($buy_orders_result);
	} //End OF get_filled_orders_live


	public function seven_level_bottom_5_percentile_trailing_stop($trigger_type,$coin_symbol,$current_market_price,$apply_factor_value){

		$is_seven_level_true = $this->is_seven_level_pressure_5_bottom_percentile($coin_symbol);

		if($is_seven_level_true){
			$orders_arr = $this->get_stop_loss_updating_orders($trigger_type, $coin_symbol);
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
							$log_msg = " Order Stop Loss Updated From <b>(".num($iniatial_trail_stop).")</b> To  <b>(" .num($updated_stop_loss).') </b> By Seven Level Bottom 5 Percentile ';
							$this->insert_order_history_log($id, $log_msg, 'stop_loss', $admin_id, $created_date);

						}// %%%%%%%%%% -- End of Updated Stop Loss -- %%%%%%%%%%
						
					}//End Of If Profit is Greater .5 percent				
				}//End of orders Loop
			}//End of check of Orders Exist

		}//End of is_seven_level_true

		
	} //End of aggrisive_define_percentage_followup


	public function is_seven_level_pressure_5_bottom_percentile($coin_symbol){
        $coin_meta_hourly_arr = $this->mod_box_trigger_3->get_coin_meta_hourly_percentile($coin_symbol);
        $coin_meta_hourly_arr = (array) $coin_meta_hourly_arr[0];
        $seven_level_bottom_percentile = $coin_meta_hourly_arr['sevenlevel_b_5'];

        $coin_meta_arr = $this->mod_barrier_trigger->get_coin_meta_data($coin_symbol);
        $coin_meta_arr = (array) $coin_meta_arr[0];

        $seven_level_depth = $coin_meta_arr['seven_level_depth'];
		$response = false;
        if($seven_level_depth <= $seven_level_bottom_percentile){
            $response = true;
		}
	
		return $response;
	}//End of is_seven_level_pressure_5_bottom_percentile

	public function get_profit_defined_lth_orders($coin_symbol){

		$this->mongo_db->where_in('order_mode', array('live'));
		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where_ne('is_profit_updated_as_stop_loss', 'yes');
        $this->mongo_db->where_ne('parent_status', 'parent');
        $where['lth_profit'] = array('$gt'=>0);   
		$where['status'] = 'LTH';
		$where['trigger_type'] = 'barrier_percentile_trigger';
        $where['symbol'] = $coin_symbol;
		$this->mongo_db->where($where);
        $orders_obj = $this->mongo_db->get('buy_orders');
        $order_arr  = iterator_to_array($orders_obj);
		return $order_arr;
		 
	}//End of get_profit_defined_lth_orders



} //End of mod_Barrier_trigger
?>