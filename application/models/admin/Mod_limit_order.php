<?php 
/**
* 
*/
class Mod_limit_order extends CI_Model
{
	
	function __construct()
	{
		# code...
	}


    ///////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////                           /////////////////
	////////////////////                           ////////////////////////////////
	////////////////////  Buy order Part           /////////////////////////////////
	////////////////////                           ////////////////////////////////
	////////////////////                           /////////////////
	////////////////////                           /////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function save_follow_up_of_limit_order($order_id,$type)
	{
		$response  = $this->get_buy_order($order_id);
		extract($response);

		$created_date = date('Y-m-d H:i:s');
		$created_date_obj = $this->mongo_db->converToMongodttime($created_date);

		$save_arr = array('buy_order_id'=>(string)$_id,'binance_order_id'=>$binance_order_id,'type'=>$type,'created_date'=>$created_date_obj,'updated_date'=>$created_date_obj,'human_readible_date'=>$created_date,'status'=>'new','user_id'=>$admin_id,'coin_symbol'=>$symbol);

		$this->mongo_db->insert('followup_of_limit_orders_collections',$save_arr);
	}//save_follow_up_of_limit_order
	
	public function get_buy_order($order_id)
	{
		$this->mongo_db->where(array('_id'=>(string)$order_id));
		$data = $this->mongo_db->get('buy_orders');
		$row = iterator_to_array($data);
		return (array)$row[0];
	}//End of get_buy_order

	public function cancel_buy_trade($type='buy')
	{
		$prevouse_date = date('Y-m-d H:i:s', strtotime('-60 minute'));
		$date = $this->mongo_db->converToMongodttime($prevouse_date);
		$where['type'] = $type;
		$where['status'] = 'new';
		$this->mongo_db->where_lte('created_date', $date);
		$data = $this->mongo_db->get('followup_of_limit_orders_collections');
		$data_row = iterator_to_array($data);


		if(!empty($data_row)){
			foreach ($data_row as $row) {
				$follow_up_id =(string) $row['_id'];
				$binance_order_id = $row['binance_order_id'];
				$user_id = $row['user_id'];
				$buy_order_id = $row['buy_order_id'];
				$coin_symbol = $row['coin_symbol']; 
				if(!empty($binance_order_id)){

					//GET order status
					$order_detail = $this->binance_api->order_status($coin_symbol, $binance_order_id, $user_id);

					/* %%%%%%%%%%%%%%%%%%%%%%%%%% */
						$this->mongo_db->where(array('_id'=>$follow_up_id));
						$upd = array('status'=>'cancelled');
						$this->mongo_db->set($upd);
						//Update data in mongoTable
						$this->mongo_db->update('followup_of_limit_orders_collections');
					/* %%%%%%%%%%%%%%%%%%%%%%%%%% */
					
					
					if(!empty($order_detail)){
						$order_status = $order_detail['status'];
						if($order_status == 'NEW'){
							/********************* */

							//Cancel the Order On Binance 
							   $cancel_order = $this->binance_api->cancel_order($coin_symbol, $binance_order_id, $user_id);


							   /** %%%%%%%%% cancel orders tables %%%%%%%%% */

							   $this->move_sell_order_from_new_to_cancel_status($buy_order_id);

							   /** %%%%%%%%%%%%%%%%%%%%%%%%%%% */


								$update_arr = array('is_sell_order'=>'','status'=>'canceled');

								/* %%%%%%%%% Move Buy order to canceled %%%%%%%%%%%%%  */
									$this->mongo_db->where(array('_id' => $buy_order_id));

									$this->mongo_db->set($update_arr);

									//Update data in mongoTable
									$this->mongo_db->update('buy_orders');
								/* %%%%%%%%%%%%%%%%%%%%%%  */


								$log_msg = " Order  was Cancelled binance id :".$binance_order_id;
								$created_date = date('Y-m-d H:i:s');

								$this->mod_box_trigger_3->insert_order_history_log($buy_order_id, $log_msg, 'order Cancelled', $user_id, $created_date);

							/* ************* */


						}//check if order status is new 
					}//End of check empty
				}//if Binance Order is Not Empty
				
			}//End of if foreach
		}//End of if not empty
	}//End of cancel_buy_trade


	public function move_sell_order_from_new_to_cancel_status($buy_order_id){
		$this->mongo_db->where(array('_id'=>$buy_order_id));
		$res = $this->mongo_db->get('buy_orders');
		$data  = iterator_to_array($res);
		if(!empty($data)){
			$data = $data[0];
			$sell_order_id = $data['sell_order_id'];
			/* %%%%%%%%% Move Sell order to canceled %%%%%%%%%%%%%  */
			$this->mongo_db->where(array('_id'=>$sell_order_id));
			$this->mongo_db->set(array('status'=>'canceled'));
			$this->mongo_db->update('orders');
			/** %%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */
		}//End of if buy_orders not empty
	}//End of Function

	///////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////                           /////////////////
	////////////////////                           ////////////////////////////////
	////////////////////  Sell rder Part           /////////////////////////////////
	////////////////////                           ////////////////////////////////
	////////////////////                           /////////////////
	////////////////////                           /////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////


	public function save_follow_up_of_limit_sell_order($sell_order_id,$buy_orders_id,$type){
		$response  = $this->get_sell_order($sell_order_id);
		extract($response);

		$created_date = date('Y-m-d H:i:s');
		$created_date_obj = $this->mongo_db->converToMongodttime($created_date);

		$save_arr = array('sell_order_id'=>(string)$_id,'buy_orders_id'=>$buy_orders_id,'binance_order_id'=>$binance_order_id,'type'=>$type,'created_date'=>$created_date_obj,'updated_date'=>$created_date_obj,'human_readible_date'=>$created_date,'status'=>'new','user_id'=>$admin_id,'coin_symbol'=>$symbol);

		$this->mongo_db->insert('followup_of_limit_orders_collections',$save_arr);
	}//End of save_follow_up_of_limit_sell_order

	public function get_sell_order($order_id)
	{
		$this->mongo_db->where(array('_id'=>(string)$order_id));
		$data = $this->mongo_db->get('orders');
		$row = iterator_to_array($data);
		return (array)$row[0];
	}//End of get_buy_order


	public function cancel_sell_trade($type='sell')
	{
		$prevouse_date = date('Y-m-d H:i:s', strtotime('-60 minute'));
		$date = $this->mongo_db->converToMongodttime($prevouse_date);
		$where['type'] = $type;
		$where['status'] = 'new';
		$this->mongo_db->where_lte('created_date', $date);
		$data = $this->mongo_db->get('followup_of_limit_orders_collections');
		$data_row = iterator_to_array($data);


		if(!empty($data_row)){
			foreach ($data_row as $row) {
				$follow_up_id =(string) $row['_id'];
				$binance_order_id = $row['binance_order_id'];
				$user_id = $row['user_id'];
				$buy_order_id = $row['buy_order_id'];
				$sell_order_id = $row['sell_order_id'];
				$coin_symbol = $row['coin_symbol']; 
				if(!empty($binance_order_id)){

					//GET order status
					$order_detail = $this->binance_api->order_status($coin_symbol, $binance_order_id, $user_id);

					/* %%%%%%%%%%%%%%%%%%%%%%%%%% */
						$this->mongo_db->where(array('_id'=>$follow_up_id));
						$upd = array('status'=>'cancelled');
						$this->mongo_db->set($upd);
						//Update data in mongoTable
						$this->mongo_db->update('followup_of_limit_orders_collections');
					/* %%%%%%%%%%%%%%%%%%%%%%%%%% */
					
					
					if(!empty($order_detail)){
						$order_status = $order_detail['status'];
						if($order_status == 'NEW'){
							/********************* */

							//Cancel the Order On Binance 
							   $cancel_order = $this->binance_api->cancel_order($coin_symbol, $binance_order_id, $user_id);


							   /** %%%%%%%%% cancel orders tables %%%%%%%%% */

							   $this->move_sell_order_from_new_to_cancel_status($buy_order_id);

							   /** %%%%%%%%%%%%%%%%%%%%%%%%%%% */


								$update_arr = array('is_sell_order'=>'yes');

								/* %%%%%%%%% Move Buy order to canceled %%%%%%%%%%%%%  */
									$this->mongo_db->where(array('_id' => $buy_order_id));

									$this->mongo_db->set($update_arr);

									//Update data in mongoTable
									$this->mongo_db->update('buy_orders');
								/* %%%%%%%%%%%%%%%%%%%%%%  */

								$log_msg = " Order  was Cancelled binance id :".$binance_order_id;
								$created_date = date('Y-m-d H:i:s');

								$this->mod_box_trigger_3->insert_order_history_log($buy_order_id, $log_msg, 'order Cancelled', $user_id, $created_date);

							/* ************* */
						}//check if order status is new 
					}//End of check empty
				}//if Binance Order is Not Empty
				
			}//End of if foreach
		}//End of if not empty
	}//End of cancel_buy_trade


}//End of Mod_limit_order
?>