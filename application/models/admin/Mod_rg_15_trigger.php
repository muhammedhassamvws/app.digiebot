<?php
class mod_rg_15_trigger extends CI_Model {

	function __construct() {
		# code...
	}

   

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////                           /////////////////
	////////////////////                           /////////////////////////////////
	////////////////////  Trigger_rg_15   Live          /////////////////////////////////
	////////////////////                           ////////////////////////////////
	////////////////////                           /////////////////
	////////////////////                           /////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////



	public function create_new_orders_by_Trigger_rg_15_live($date = '') {
	

		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		//Check of Parent Order  Exist
		$order_mode = array('test_live', 'live');
		$parent_orders_arr = $this->get_parent_orders_rg_15($order_mode);



		$response_arr['message'] = 'parent order not found';
		//if parent order exist then creat buy orders
		if (count($parent_orders_arr) > 0) {
			foreach ($parent_orders_arr as $buy_orders) {
				$buy_parent_id = $buy_orders['_id'];
				$coin_symbol = $buy_orders['symbol'];
				$buy_quantity = $buy_orders['quantity'];
				$buy_trigger_type = $buy_orders['trigger_type'];
				$admin_id = $buy_orders['admin_id'];
				$application_mode = $buy_orders['application_mode'];
				$order_mode = $buy_orders['order_mode'];

		

				//Check of previous candel is Demond Candel
				$previouse_candel_arr = $this->get_bottom_demand_rejection_candel_live($prevouse_date, $coin_symbol);

			

					//Get TRigger setting
				$res_coin_setting_arr = $this->get_trigger_setting($coin_symbol,$buy_trigger_type,'live');
				$buy_price_percentage = 30;
				$stop_loss_percent = 4;
				$sell_price_percent = 3;
				if (count($res_coin_setting_arr) > 0) {
					foreach ($res_coin_setting_arr as $res_coin_setting) {
						$buy_price_percentage = $res_coin_setting['buy_price'];
						$stop_loss_percent = $res_coin_setting['stop_loss'];
						$sell_price_percent = $res_coin_setting['sell_price'];

					}
				}

				if (count($previouse_candel_arr) > 0) {

					foreach ($previouse_candel_arr as $candel_data) {
						$demand_candel_high_value = $candel_data['high'];
						$demand_candel_low_value = $candel_data['low'];
						$demand_close_value = $candel_data['close'];
						$box_progress_status = $candel_data['box_progress_status'];
						$box_progress_setting_id = $candel_data['_id'];
					}


					

					$differenc_value = $demand_candel_high_value - $demand_candel_low_value;
			   
					$buy_price = $demand_candel_low_value + ($differenc_value * ($buy_price_percentage / 100));
					//Initial Stop Loss
					$iniatial_trail_stop = $buy_price - ($buy_price / 100) * $stop_loss_percent;

					$sell_price = $buy_price + ($buy_price / 100) * $sell_price_percent;


					

					$update_prices_arr = array('price' => $buy_price, 'iniatial_trail_stop' => $iniatial_trail_stop, 'sell_price' => $sell_price, 'unique_time_id_to_check_update' => strtotime($date));

					///////////////////////////////////////////////////////////
					////////////////////////                /////////////////////////////
					///////////////////////  Create Order  /////////////////////////////
					//////////////////////                 ////////////////////////////
					///////////////////////////////////////////////////////////

					$ins_data = array(
						'price' =>(float) ($buy_price),
						'quantity' =>(float) $buy_quantity,
						'symbol' => $coin_symbol,
						'order_type' => 'MARKET_ORDER',
						'admin_id' => $admin_id,
						'trigger_type' => 'rg_15',
						'sell_price' => (float)($sell_price),
						'created_date' => $this->mongo_db->converToMongodttime($date),
						'modified_date' => $this->mongo_db->converToMongodttime($date),
					);
					$ins_data['trail_check'] = 'no';
					$ins_data['trail_interval'] = '0';
					$ins_data['buy_trail_price'] = '0';
					$ins_data['auto_sell'] = 'no';
					$ins_data['buy_parent_id'] = $buy_parent_id;
					$ins_data['iniatial_trail_stop'] = (float)$iniatial_trail_stop;
					$ins_data['buy_order_status_new_filled'] = 'wait_for_buyed';
					$ins_data['application_mode'] = $application_mode;
					$ins_data['order_mode'] = $order_mode;
					$ins_data['order_mode_lock_for_update'] = 0;
					$ins_data['parent_aggrive_stop_loss_compare_value'] = $demand_close_value;

					$ins_data['demand_close_value'] = $demand_close_value;
					$trade_status = 'new';
				
					$ins_data['status'] = $trade_status;
					$ins_data['box_progress_setting_id'] = $box_progress_setting_id;



						///////////////////////////////////////////////
						//////////////////////
						/////////
						//Insert data in mongoTable
						$buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);

						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////

						
							if ($application_mode == 'live') {
								$order_mode = 'Live';
							} else {
								$order_mode = 'Test_live';
							}
							$log_msg = "Buy (" . $order_mode . ") Order was Created at Price " . number_format($buy_price, 8);
							$created_date = date('Y-m-d G:i:s');
							$this->insert_order_history_log($buy_order_id, $log_msg, 'buy_created', $admin_id, $created_date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = "Buy Market Order is <b>Created</b> as status new";
						$this->add_notification($buy_order_id, 'buy', $message, $admin_id);
						/////////
						/////////////////////
						//////////////////////////////////////////////
				}
			} //End Of  parent order exist
			$upd_status = array(
				'trigger_status_rg_15' => 1,
			);

			$conn = $this->mongo_db->customQuery();
			$res = $conn->market_chart->updateMany(array('openTime_human_readible' => $prevouse_date), array('$set' => $upd_status));
		}	
	} //End of create_new_orders_by_Trigger_rg_15_live



	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////                           /////////////////
	////////////////////                           /////////////////////////////////
	//////////////////// Trigger_rg_15 simulator  /////////////////////////////////
	////////////////////                           ////////////////////////////////
	////////////////////                           /////////////////
	////////////////////                           /////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////


	public function create_new_orders_by_Trigger_rg_15_simulator($date = '') {
	

		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		//Check of Parent Order  Exist
		$order_mode = array('test_simulator');
		$parent_orders_arr = $this->get_parent_orders_rg_15($order_mode);

	

		$response_arr['message'] = 'parent order not found';
		//if parent order exist then creat buy orders
		if (count($parent_orders_arr) > 0) {
			foreach ($parent_orders_arr as $buy_orders) {
				$buy_parent_id = $buy_orders['_id'];
				$coin_symbol = $buy_orders['symbol'];
				$buy_quantity = $buy_orders['quantity'];
				$buy_trigger_type = $buy_orders['trigger_type'];
				$admin_id = $buy_orders['admin_id'];
				$application_mode = $buy_orders['application_mode'];
				$order_mode = $buy_orders['order_mode'];

		

				//Check of previous candel is Demond Candel
				$previouse_candel_arr = $this->get_bottom_demand_rejection_candel($prevouse_date, $coin_symbol);

					//Get TRigger setting
				$res_coin_setting_arr = $this->get_trigger_setting($coin_symbol,'rg_15','test');
				$buy_price_percentage = 30;
				$stop_loss_percent = 4;
				$sell_price_percent = 3;
				if (count($res_coin_setting_arr) > 0) {
					foreach ($res_coin_setting_arr as $res_coin_setting) {
						$buy_price_percentage = $res_coin_setting['buy_price'];
						$stop_loss_percent = $res_coin_setting['stop_loss'];
						$sell_price_percent = $res_coin_setting['sell_price'];

					}
				}

				if (count($previouse_candel_arr) > 0) {

					foreach ($previouse_candel_arr as $candel_data) {
						$demand_candel_high_value = $candel_data['high'];
						$demand_candel_low_value = $candel_data['low'];
						$demand_close_value = $candel_data['close'];
						$box_progress_status = $candel_data['box_progress_status'];
						$box_progress_setting_id = $candel_data['_id'];
					}


					

					$differenc_value = $demand_candel_high_value - $demand_candel_low_value;
			   
					$buy_price = $demand_candel_low_value + ($differenc_value * ($buy_price_percentage / 100));
					//Initial Stop Loss
					$iniatial_trail_stop = $buy_price - ($buy_price / 100) * $stop_loss_percent;

					$sell_price = $buy_price + ($buy_price / 100) * $sell_price_percent;


					

					$update_prices_arr = array('price' => $buy_price, 'iniatial_trail_stop' => $iniatial_trail_stop, 'sell_price' => $sell_price, 'unique_time_id_to_check_update' => strtotime($date));

					///////////////////////////////////////////////////////////
					////////////////////////                /////////////////////////////
					///////////////////////  Create Order  /////////////////////////////
					//////////////////////                 ////////////////////////////
					///////////////////////////////////////////////////////////

					$ins_data = array(
						'price' => num($buy_price),
						'quantity' => $buy_quantity,
						'symbol' => $coin_symbol,
						'order_type' => 'MARKET_ORDER',
						'admin_id' => $admin_id,
						'trigger_type' => 'rg_15',
						'sell_price' => num($sell_price),
						'created_date' => $this->mongo_db->converToMongodttime($date),
					);
					$ins_data['trail_check'] = 'no';
					$ins_data['trail_interval'] = '0';
					$ins_data['buy_trail_price'] = '0';
					$ins_data['auto_sell'] = 'no';
					$ins_data['buy_parent_id'] = $buy_parent_id;
					$ins_data['iniatial_trail_stop'] = $iniatial_trail_stop;
					$ins_data['buy_order_status_new_filled'] = 'wait_for_buyed';
					$ins_data['application_mode'] = $application_mode;
					$ins_data['order_mode'] = $order_mode;
					$ins_data['order_mode_lock_for_update'] = 0;
					$ins_data['parent_aggrive_stop_loss_compare_value'] = $demand_close_value;

					$ins_data['demand_close_value'] = $demand_close_value;
					$trade_status = 'new';
				
					$ins_data['status'] = $trade_status;
					$ins_data['box_progress_setting_id'] = $box_progress_setting_id;

					

						///////////////////////////////////////////////
						//////////////////////
						/////////
						//Insert data in mongoTable
						$buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);

						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////

						$log_msg = "Buy Test Order was Created at Price " . num($buy_price);
						$this->insert_order_history_log($buy_order_id, $log_msg, 'buy_created', $admin_id, $date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = "Buy Market Order is <b>Created</b> as status new";
						$this->add_notification($buy_order_id, 'buy', $message, $admin_id);
						/////////
						/////////////////////
						//////////////////////////////////////////////
			
				
				}


				$response_arr['message'] = $this->buy_order_trigger_rg_15_samulater($date, $coin_symbol, $stop_loss_percent);
			} //End Of  parent order exist
		}
	} //End of create_new_orders_by_Trigger_rg_15_simulator

	public function buy_order_trigger_rg_15_samulater($date, $coin_symbol, $stop_loss_percent) {

		$order_mode = array('test_simulator');	
		$buy_orders_arr = $this->get_new_orders_rg_15($coin_symbol,$order_mode);

		$return_response = '';
		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		$this->mongo_db->where(array('openTime_human_readible' => $current_date, 'coin' => $coin_symbol));
		$current_candel_result = $this->mongo_db->get('market_chart');
		$current_candel_arr = iterator_to_array($current_candel_result);

		$arr_response = array();
		if (count($buy_orders_arr)) {
			foreach ($buy_orders_arr as $buy_orders) {
				$id = $buy_orders['_id'];
				$buy_price = $buy_orders['price'];
				$admin_id = $buy_orders['admin_id'];
				$quantity = $buy_orders['quantity'];
				$application_mode = $buy_orders['application_mode'];
				$coin_symbol = $buy_orders['symbol'];
				$parent_aggrive_stop_loss_compare_value = $buy_orders['parent_aggrive_stop_loss_compare_value'];
				$iniatial_trail_stop_parent = $buy_orders['iniatial_trail_stop'];
				$demand_close_value = $buy_orders['demand_close_value'];
				$order_date  = $buy_orders['created_date'];

				if (count($current_candel_arr) > 0) {

					$high_value = $current_candel_arr[0]['high'];
					$low_value = $current_candel_arr[0]['low'];
					$open = $current_candel_arr[0]['open'];
					$market_price = $low_value;

					
					$created_date = date("Y-m-d G:i:s");
	

				
					$prices_arr = $this->get_current_prices_for_samulater($current_date,$coin_symbol);
					//Append Value To Last Of The Array
					array_push($prices_arr,$low_value,$high_value);


					$market_price = 0; 
					if(count($prices_arr)>0){
						foreach ($prices_arr as $market_price_1) {
							
							if($market_price_1 <= $buy_price){
								$market_price = $market_price_1;
								break;
							}
						}
					}

					

					if($market_price !=0){

						$update_trail_stop = $market_price - ($market_price / 100) * $stop_loss_percent;

						

						///////////////////End of update trail stop
						///////////////////////////////////////////
						$upd_data22 = array(
							'status' => 'FILLED',
							'market_value' => $market_price,
							'iniatial_trail_stop' => $update_trail_stop,
							'buy_date' => $this->mongo_db->converToMongodttime($current_date),
							'iniatial_trail_stop_copy' => $update_trail_stop,
						);
						$this->mongo_db->where(array('_id' => $id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						$log_msg = " Order was Buyed at Price " . number_format($market_price, 8);
						$this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = "Buy Market Order is <b>buyed</b> as status Filled market_price=" . number_format($market_price, 8) . "  buy_price  " . number_format($buy_price, 8) . '  high_value' . number_format($high_value, 8);
						$this->add_notification($id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////
						//Check Market History
						$commission = $quantity * (0.001);
						$commissionAsset = str_replace('BTC', '', $symbol);
						//Check Market History
						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($id, $log_msg, 'buy_commision', $admin_id, $date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
						$arr_response['message'] = $message . '---->log_msg' . $log_msg;
					}//if market price match



				}//current array candel is greater then 0			
			}
		} //If Current candel  Exist

		return $arr_response;
	} //End of buy_order_trigger_rg_15_samulater

	public function sell_order_trigger_rg_15_samulater($date = '') {

		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where(array('status' => 'FILLED', 'trigger_type' => 'rg_15', 'order_mode' => 'test_simulator'));
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		$buy_orders_arr = iterator_to_array($buy_orders_result);
		$return_response = '';
		$current_date = date('Y-m-d H:00:00');
		$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour'));
		if ($date) {
			$current_date = date('Y-m-d H:00:00', strtotime($date));
			$prevouse_date = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($date)));
		}

		$arr_response = array();
		if (count($buy_orders_arr) > 0) {
			foreach ($buy_orders_arr as $buy_orders) {
				$buy_orders_id = $buy_orders['_id'];
				$coin_symbol = $buy_orders['symbol'];
				$sell_price = $buy_orders['sell_price'];
				$admin_id = $buy_orders['admin_id'];
				$purchased_price = $buy_orders['price'];
				$buy_purchased_price = $buy_orders['market_value'];
				$iniatial_trail_stop = $buy_orders['iniatial_trail_stop'];
				$application_mode = $buy_orders['application_mode'];
				$quantity = $buy_orders['quantity'];
				$order_type = $buy_orders['order_type'];
				$trigger_type = $buy_orders['trigger_type'];

				$order_mode = $buy_orders['order_mode'];

				$where = array('openTime_human_readible' => $current_date, 'coin' => $coin_symbol);
				$result_arr = array();
				$this->mongo_db->where($where);
				$current_candel_result = $this->mongo_db->get('market_chart');
				$current_candel_arr = iterator_to_array($current_candel_result);

				//////////////// Test Mode//////////////////////////
				///////////////////////////////////////////////////

				if (count($current_candel_arr) > 0) {
					$high_value = $current_candel_arr[0]['high'];
					$low_value = $current_candel_arr[0]['low'];
					$open = $current_candel_arr[0]['open'];
					$market_price = $low_value;
					$created_date = date("Y-m-d G:i:s");


					$prices_arr = $this->get_current_prices_for_samulater($current_date,$coin_symbol);

					array_push($prices_arr,$low_value,$high_value);

					$market_price = 0; 
					if(count($prices_arr)>0){
						foreach ($prices_arr as $market_price_1) {
							if($market_price_1 <=
							 $iniatial_trail_stop){
								$market_price = $market_price_1;
								break;
							}
						}
					}


						//$market_price = $low_value;
					//Sell with Stop Loss
					if ( ($market_price !=0)  && ($iniatial_trail_stop != '')) {
						$sell_price = $iniatial_trail_stop;
						$upd_data22 = array(
							'is_sell_order' => 'sold',
							'market_sold_price' => $market_price,
						);
						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data22);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						//////////////////////////////
						//////////////////////////////
						$ins_data = array(
							'symbol' => $coin_symbol,
							'purchased_price' => num($buy_purchased_price),
							'quantity' => $quantity,
							'profit_type' => 'percentage',
							'order_type' => 'MARKET_ORDER',
							'admin_id' => $admin_id,
							'buy_order_check' => 'yes',
							'buy_order_id' => $buy_orders_id,
							'buy_order_binance_id' => '',
							'stop_loss' => 'no',
							'loss_percentage' => '',
							'created_date' => $this->mongo_db->converToMongodttime($current_date),
							'market_value' => $market_price,
							'application_mode' => $application_mode,
							'order_mode' => $order_mode,
						);

						$ins_data['sell_profit_percent'] = 2;
						$ins_data['sell_price'] = $sell_price;

						$ins_data['trail_check'] = 'no';
						$ins_data['trail_interval'] = '0';
						$ins_data['sell_trail_price'] = '0';
						$ins_data['status'] = 'FILLED';
						$ins_data['sell_date'] = $this->mongo_db->converToMongodttime($current_date);
						$ins_data['trigger_type'] = $trigger_type;

						//Insert data in mongoTable
						$order_id = $this->mongo_db->insert('orders', $ins_data);

						$upd_data = array(
							'sell_order_id' => $order_id,
						);
						$this->mongo_db->where(array('_id' => $buy_orders_id));
						$this->mongo_db->set($upd_data);
						//Update data in mongoTable
						$this->mongo_db->update('buy_orders');
						//////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////
						$message = 'Sell Order was Sold With Loss';
						$log_msg = $message . " " . number_format($sell_price, 8);
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $current_date);
						////////////////////////////// End Order History Log /////////////////////////
						//////////////////////////////////////////////////////////////////////////////
						////////////////////// Set Notification //////////////////
						$message = $message . " <b>Sold</b>";
						$this->add_notification($buy_orders_id, 'buy', $message, $admin_id);
						//////////////////////////////////////////////////////////
						//Check Market History
						$commission_value = $quantity * (0.001);
						$commission = $commission_value * $market_value;
						$commissionAsset = 'BTC';
						//Check Market History
						//////////////////////////////////////////////////////////////////////////////////////////////
						////////////////////////////// Order History Log /////////////////////////////////////////////
						$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
						$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $current_date);
						////////////////////////////// End Order History Log /////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////
						$response['message'] = '$log_msg  ' . $log_msg . '  $message ' . $message;
					}else{ 


							$market_price = 0; 
							if(count($prices_arr)>0){
								foreach ($prices_arr as $market_price_1) {
									if($market_price_1 >= $sell_price){
										$market_price = $market_price_1;
										break;
									}
								}
							}

						if ($market_price !=0) {
							//Sell With Normal Value
							$upd_data22 = array(
								'is_sell_order' => 'sold',
								'market_sold_price' => $market_price,
							);
							$this->mongo_db->where(array('_id' => $buy_orders_id));
							$this->mongo_db->set($upd_data22);
							//Update data in mongoTable
							$this->mongo_db->update('buy_orders');
							/////////////////////////////////////
							///////////////////////////////////
							$ins_data = array(
								'symbol' => $coin_symbol,
								'purchased_price' => num($buy_purchased_price),
								'quantity' => $quantity,
								'profit_type' => 'percentage',
								'order_type' => 'MARKET_ORDER',
								'admin_id' => $admin_id,
								'buy_order_check' => '',
								'buy_order_id' => $buy_orders_id,
								'buy_order_binance_id' => '',
								'stop_loss' => 'yes',
								'loss_percentage' => '',
								'created_date' => $this->mongo_db->converToMongodttime($current_date),
								'market_value' => $market_price,
								'application_mode' => $application_mode,
								'order_mode' => $order_mode,
							);
							$ins_data['sell_profit_percent'] = 2;
							$ins_data['sell_price'] = $sell_price;
							$ins_data['trail_check'] = 'no';
							$ins_data['trail_interval'] = '0';
							$ins_data['sell_trail_price'] = '0';
							$ins_data['status'] = 'FILLED';
							$ins_data['sell_date'] = $this->mongo_db->converToMongodttime($current_date);
							$ins_data['trigger_type'] = $trigger_type;
							//Insert data in mongoTable
							$order_id = $this->mongo_db->insert('orders', $ins_data);

							$upd_data = array(
								'sell_order_id' => $order_id,
							);

							$this->mongo_db->where(array('_id' => $buy_orders_id));
							$this->mongo_db->set($upd_data);

							//Update data in mongoTable
							$this->mongo_db->update('buy_orders');
							$message = 'Sell Order was Sold With profit';

							//////////////////////////////////////////////////////////////////////////////
							////////////////////////////// Order History Log /////////////////////////////
							$log_msg = $message . " " . number_format($sell_price, 8);
							$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $current_date);
							////////////////////////////// End Order History Log /////////////////////////
							//////////////////////////////////////////////////////////////////////////////
							////////////////////// Set Notification //////////////////
							$message = $message . " <b>Sold</b>";
							$this->add_notification($buy_orders_id, 'buy', $message, $admin_id);
							//////////////////////////////////////////////////////////
							//Check Market History
							$commission_value = $quantity * (0.001);
							$commission = $commission_value * $market_value;
							$commissionAsset = 'BTC';
							//////////////////////////////////////////////////////////////////////////////////////////////
							////////////////////////////// Order History Log /////////////////////////////////////////////
							$log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
							$this->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $current_date);
							////////////////////////////// End Order History Log /////////////////////////////////////////
							//////////////////////////////////////////////////////////////////////////////////////////////
							$response['message'] = '$log_msg  ' . $log_msg . '  $message ' . $message;
						} else {
							$arr_response['message'] = 'NO trigger match for sell $low_value  ' . number_format($low_value, 8) . '  sell_price ' . number_format($sell_price, 8) . '  high_value' . number_format($high_value, 8);
						}

				}	

				} else {
					$arr_response['message'] = 'Order is Not Sold';
				}
				///////////////////////////////////////////////
				///////////////////////////////////////////////

			} //End of for each order
		} //End of End Condition
		return $arr_response['message'];
	} //End of sell_order_trigger_rg_15_samulater


















	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////                           /////////////////
	////////////////////                           /////////////////////////////////
	////////////////////    Trigger Setting        ////////////////////////////////
	////////////////////                           ////////////////////////////////
	////////////////////                           /////////////////
	////////////////////                           /////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function get_parent_orders_rg_15($order_mode) {
		$this->mongo_db->where_ne('buy_order_status_new_filled', 'wait_for_buyed');
		$this->mongo_db->where_in('order_mode',$order_mode);
		$this->mongo_db->where(array('status' => 'new', 'trigger_type' => 'rg_15'));
		$parent_orders_object = $this->mongo_db->get('buy_orders');
		return iterator_to_array($parent_orders_object);
	} //End of get_parent_orders_rg_15

	
	public function get_bottom_demand_rejection_candel($prevouse_date, $coin_symbol) {
		$this->mongo_db->where(array('openTime_human_readible' => $prevouse_date, 'coin' => $coin_symbol, 'rejected_candle' => 'bottom_demand_rejection'));
		$previouse_candel_result = $this->mongo_db->get('market_chart');
		return iterator_to_array($previouse_candel_result);
	} //End of get_bottom_demand_rejection_candel

	public function get_bottom_demand_rejection_candel_live($prevouse_date, $coin_symbol) {
		$this->mongo_db->where_ne('trigger_status_rg_15',1);
		$this->mongo_db->where(array('openTime_human_readible' => $prevouse_date, 'coin' => $coin_symbol, 'rejected_candle' => 'bottom_demand_rejection'));
		$previouse_candel_result = $this->mongo_db->get('market_chart');
		return iterator_to_array($previouse_candel_result);
	} //End of get_bottom_demand_rejection_candel_live


	public function get_trigger_setting($coin_symbol,$triggers_type ='box_trigger_3',$order_mode) {
		$this->mongo_db->where(array('coins' => $coin_symbol, 'triggers_type' =>$triggers_type,'order_mode'=>$order_mode));
		$res_coin_setting = $this->mongo_db->get('setting_triggers_collections');
		return iterator_to_array($res_coin_setting);
	} //End of get_trigger_setting


	public function get_new_orders_rg_15($coin_symbol,$order_mode,$current_market_price) {
		$this->mongo_db->where_in('order_mode',$order_mode);
		$this->mongo_db->where_gte('price', $current_market_price);
		$this->mongo_db->where(array('status' => 'new', 'trigger_type' => 'rg_15', 'buy_order_status_new_filled' => 'wait_for_buyed', 'symbol' => $coin_symbol,'new_ready_status'=>'new_ready_status'));
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		return iterator_to_array($buy_orders_result);
	} //End of get_new_orders_rg_15

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
		$this->db->dbprefix('notification');
		$this->db->insert('notification', $ins_data);

		return true;
	} //end add_notification()


	public function get_stop_loss_orders($market_price,$coin_symbol){
		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where_in('order_mode', array('test_live', 'live'));
		$this->mongo_db->where(array('status' => 'FILLED', 'trigger_type' => 'rg_15','symbol'=>$coin_symbol));
		$this->mongo_db->where_gte('iniatial_trail_stop',$market_price);
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		return iterator_to_array($buy_orders_result);
	}//End of get_stop_loss_orders


	public function get_profit_sell_orders($market_price,$coin_symbol){
		$this->mongo_db->where_ne('is_sell_order', 'sold');
		$this->mongo_db->where_in('order_mode', array('test_live', 'live'));
		$this->mongo_db->where(array('status' => 'FILLED', 'trigger_type' => 'rg_15','symbol'=>$coin_symbol));
		$this->mongo_db->where_lte('sell_price',$market_price);
		$buy_orders_result = $this->mongo_db->get('buy_orders');
		return iterator_to_array($buy_orders_result);
	}//End of get_profit_sell_orders
}
?>