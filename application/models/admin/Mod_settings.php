<?php
class mod_settings extends CI_Model {

	function __construct() {
		parent::__construct();
	}


    public function updateKeySettingsProcess($data){

    	    extract($data);

			$created_date = date('Y-m-d G:i:s');
			$created_date_obj = $this->mongo_db->converToMongodttime($created_date);
			$admin_id    = $this->session->userdata('admin_id');

			//$this->mongo_db->where(array('user_id' => $admin_id));
		    $get_settings = $this->mongo_db->get('master_api_key');
			$settings_arr = iterator_to_array($get_settings);

			$dataArr = array('user_id'=>$admin_id,'api_key_tr'=>$api_key_tr,'api_secret_tr'=>$api_secret_tr,'status'=>$status,'created_date'=>$created_date_obj);

			if (count($settings_arr) > 0) {
				$this->mongo_db->where(array('user_id'=>$admin_id));
				$this->mongo_db->set($dataArr);
				$update  =  $this->mongo_db->update('master_api_key');

			}else{
				$ins = $this->mongo_db->insert('master_api_key', $dataArr);
				//echo "<pre>";  print_r($ins);   exit;
			}

			return true;
	}// End of updateKeySettingsProcess



   //getMasterApikeyCredentials
	public function getMasterApikeyCredentials() {

		$admin_id    = $this->session->userdata('admin_id');
		//$this->mongo_db->where(array('user_id' => $admin_id));
		$get_settings = $this->mongo_db->get('master_api_key');
		$settings_arr = iterator_to_array($get_settings);

		return $settings_arr;
	} //end getMasterApikeyCredentials




   //fucntionTimeLog
	public function fucntionTimeLog() {

		$admin_id    = $this->session->userdata('admin_id');
		//$this->mongo_db->where(array('user_id' => $admin_id));
		$this->mongo_db->order_by(array('created_date'=>-1));
		$this->mongo_db->limit(200);
		$get_track_execution_of_fucntion_time = $this->mongo_db->get('track_execution_of_function_time');
		$track_execution_time_arr = iterator_to_array($get_track_execution_of_fucntion_time);

		return $track_execution_time_arr;
	} //end fucntionTimeLog


	//get_settings_by_id
	public function get_settings_by_id($id) {

		// $this->db->dbprefix('settings');
		// $this->db->where('user_id', $id);
		// $get_settings = $this->db->get('settings');
		//
		// //echo $this->db->last_query();
		// $settings_arr = $get_settings->row_array();

		$this->mongo_db->where(array("_id" => $id));
		$get_arr = $this->mongo_db->get('users');

		$settings_arr = iterator_to_array($get_arr);

		return $settings_arr[0];

	} //end get_settings_by_id

	//get_settings_by_mongoId
	public function get_settings_by_mongoId($id) {

		$this->mongo_db->where(array('user_id' => $id));
		$get_settings = $this->mongo_db->get('settings');

		//echo $this->db->last_query();
		$settings_arr = iterator_to_array($get_settings);

		return $settings_arr;

	} //end get_settings_by_mongoId

	public function add_settings($data) {
		$admin_id = $this->session->userdata('admin_id');

		
	
		extract($data);

		// $this->db->dbprefix('settings');
		// $this->db->where('user_id', $admin_id);
		// $get_settings = $this->db->get('settings');
		//
		// //echo $this->db->last_query();
		// $settings_arr = $get_settings->row_array();

		$created_date = date('Y-m-d H:i:s');

		$ins_array = array(
			'user_id' => $admin_id,
			'api_key' => $api_key,
			'api_secret' => $api_secret,
		);
		// if (count($settings_arr) > 0) {
		// 	$this->db->dbprefix('settings');
		// 	$this->db->where('user_id', $admin_id);
		// 	$ins = $this->db->update('settings', $ins_array);
		// } else {
		// 	$this->db->dbprefix('settings');
		// 	$ins = $this->db->insert('settings', $ins_array);
		// }


		$this->mongo_db->where(array('_id' => $admin_id));
		$this->mongo_db->set($ins_array);
		$get_settings = $this->mongo_db->update('users');

		$upd_sess_array = array(
			'check_api_settings' => 'yes',
		);
		 $this->session->set_userdata($upd_sess_array);

		if ($get_settings) {
			return true;
		} else {
			return false;
		}

	}
	//end add_settings

	//add_settings
	public function add_settings_mongo($data) {
		extract($data);

		$this->mongo_db->where(array('user_id' => $admin_id));
		$get_settings = $this->mongo_db->get('settings');

		//echo $this->db->last_query();
		$settings_arr = iterator_to_array($get_settings);

		$created_date = date('Y-m-d H:i:s');

		$ins_array = array(
			'user_id' => $admin_id,
			'api_key' => $api_key,
			'api_secret' => $api_secret,
			'created_date' => $created_date,
		);
		if (count($settings_arr) > 0) {
			$this->mongo_db->where(array('user_id' => $admin_id));
			$this->mongo_db->set($ins_array);
			$ins = $this->mongo_db->update('settings', $ins_array);
		} else {
			$ins = $this->mongo_db->insert('settings', $ins_array);
		}

		$upd_sess_array = array(
			'check_api_settings' => 'yes',
		);
		$this->session->set_userdata($upd_sess_array);

		if ($ins) {
			return true;
		} else {
			return false;
		}

	}
	//end add_settings

	public function get_setings($id) {
		$this->db->dbprefix('settings');
		$this->db->where('id', $id);
		$get_settings = $this->db->get('settings');

		//echo $this->db->last_query();
		$settings_arr = $get_settings->row_array();
		return $settings_arr;
	}

	/*public function get_setings_mongo($id) {
		        $this->db->dbprefix('settings');
		        $this->db->where('id', $id);
		        $get_settings = $this->db->get('settings');

		        //echo $this->db->last_query();
		        $settings_arr = $get_settings->row_array();
		        return $settings_arr;
	*/

	/*public function edit_settings($data) {
		extract($data);
		$upd_arr = array(
			'api_key' => $api_key,
			'api_secret' => $api_secret,
		);
		$this->db->dbprefix('settings');
		$this->db->where('id', $setting_id);
		$this->db->update('settings', $upd_arr);

		return true;
	}*/

	public function delete_settings($id) {
		$this->db->dbprefix('settings');
		$this->db->where('id', $id);
		$this->db->delete('settings');

		return true;
	}

	public function change_password_sql($data) {
		extract($data);
		$this->db->where('id', $admin_id);
		$this->db->where('password', md5($old_password));
		$get_user = $this->db->get('users');
		$count_Arr = $get_user->row_array();
		$count = count($count_Arr);

		$upd_arr = array('password' => md5($new_password));

		$this->db->where('id', $admin_id);
		$this->db->where('password', md5($old_password));
		$upd = $this->db->update('users', $upd_arr);
		//UPDATE `tr_users` SET `password` = '121212' WHERE `id` = '6' AND `password` = '123123'
		if ($count) {
			return true;
		} else {
			return false;
		}

	}

	public function change_password($data) {
		extract($data);
		$this->mongo_db->where(array('_id' => $admin_id, 'password' => md5($old_password)));
		$get_user = $this->mongo_db->get('users');
		$count_Arr = iterator_to_array($get_user);
		$count = count($count_Arr);

		$upd_arr = array('password' => md5($new_password));

		$this->mongo_db->where(array('_id' => $admin_id, 'password' => md5($old_password)));
		$this->mongo_db->set($upd_arr);
		$upd = $this->mongo_db->update('users');
		if ($count) {
			return true;
		} else {
			return false;
		}

	}

	public function auto_sell_enable_sql($value) {
		extract($value);

		$this->db->dbprefix('settings');
		$this->db->where('user_id', $admin);
		$get_settings = $this->db->get('settings');

		//echo $this->db->last_query();
		$settings_arr = $get_settings->row_array();

		$data_to_insert = array(
			'user_id' => $admin,
			'auto_sell_enable' => $enable,
		);

		if (count($settings_arr) > 0) {
			$this->db->set($data_to_insert);
			$this->db->dbprefix('settings');
			$this->db->where('user_id', $admin);
			$ins = $this->db->update('settings', $data_to_insert);
		} else {
			$this->db->dbprefix('settings');
			$ins = $this->db->insert('settings', $data_to_insert);

		}
		if ($ins) {
			return true;
		}
	}

	public function auto_sell_enable($value) {
		extract($value);

		$this->mongo_db->where(array('_id' => $admin));
		$get_settings = $this->mongo_db->get('settings');

		//echo $this->mongo_db->last_query();
		$settings_arr = iterator_to_array($get_settings);

		$data_to_insert = array(
			'auto_sell_enable' => $enable,
		);

		$this->mongo_db->where(array('_id' => $admin));
		$this->mongo_db->set($data_to_insert);
		$ins = $this->mongo_db->update('users');

		if ($ins) {
			return true;
		}
	}

	public function update_user_auth_sql($admin_id, $is_check, $secret) {
		if ($is_check == 'yes') {
			$upd_arr = array(
				'google_auth' => $is_check,
				'google_auth_code' => $secret,
			);
		} else {
			$upd_arr = array(
				'google_auth' => $is_check,
				'google_auth_code' => NULL,
			);
		}

		$this->db->dbprefix('users');
		$this->db->where('id', $admin_id);
		$upd = $this->db->update('users', $upd_arr);

		if ($upd) {
			$_SESSION['google_auth'] = $is_check;
			$_SESSION['google_auth_code'] = $secret;
			return true;
		}

	}

	public function update_user_auth($admin_id, $is_check, $secret) {
		if ($is_check == 'yes') {
			$upd_arr = array(
				'google_auth' => $is_check,
				'google_auth_code' => $secret,
			);
		} else {
			$upd_arr = array(
				'google_auth' => $is_check,
				'google_auth_code' => NULL,
			);
		}

		$this->mongo_db->where(array('_id' => $admin_id));
		$this->mongo_db->set($upd_arr);
		$upd = $this->mongo_db->update('users');

		if ($upd) {
			$_SESSION['google_auth'] = $is_check;
			$_SESSION['google_auth_code'] = $secret;
			return true;
		}

	}

	public function get_candle_info($coin, $start_date, $end_date) {
		$start_date1 = $this->mongo_db->converToMongodttime($start_date);
		$end_date1 = $this->mongo_db->converToMongodttime($end_date);

		$search_array = array(
			'coin' => $coin,
			'timestampDate' => array('$gte' => $start_date1, '$lte' => $end_date1));

		$this->mongo_db->where($search_array);
		$this->mongo_db->sort(array("_id" => -1));
		$this->mongo_db->limit(1);
		$res = $this->mongo_db->get('market_chart');

		$ask_volume_arr = iterator_to_array($res);
		return $ask_volume_arr;
	}

	public function update_candle_process($data) {

		extract($data);

		$id = $candle_id;

		$upd_arr = array(
			'coin' => $coin,
			'high' => $high,
			'low' => $low,
			'open' => $open,
			'close' => $close,
			'volume' => $volume,
			'candel_status' => $candel_status,
			'candle_type' => $candle_type,
			'bid_volume' => $bid_volume,
			'ask_volume' => $ask_volume,
			'total_volume' => $total_volume,
			'rejected_candle' => $rejection,
		);

		$this->mongo_db->where(array('_id' => $id));
		$this->mongo_db->set($upd_arr);
		$upd = $this->mongo_db->update('market_chart', $upd_arr);

		if ($upd) {
			return true;
		} else {
			return false;
		}
	} //End of update_candle_process

	public function save_triggers_global_setting($data) {
		
		extract($data);
		if ($triggers_type) {

			$this->create_order_setting_log($coin,$triggers_type,$order_mode,$data);
			$where['triggers_type'] = $triggers_type;
			$where['order_mode'] = $order_mode;
			$where['coin'] = $coin;

			if(($triggers_type == 'barrier_percentile_trigger' || $triggers_type == 'box_trigger_3' || $triggers_type == 'market_trend_trigger') && ($trigger_level !='')){
				$where['trigger_level'] = $trigger_level;
			}

			$this->mongo_db->where($where);
			$response_obj = $this->mongo_db->get('trigger_global_setting');
			$response_arr = iterator_to_array($response_obj);

			$response = false;
			if (count($response_arr) > 0) {
				$id = $response_arr[0]['_id'];
				$this->mongo_db->where(array('_id' => $id));
				$this->mongo_db->set($data);
				$upd = $this->mongo_db->update('trigger_global_setting', $data);
				if ($upd) {
					$response = true;
				}
			}else {
				$res = $this->mongo_db->insert('trigger_global_setting', $data);
				if ($res) {
					$response = true;
				}
			}
		} else {
			$response = false;
		}
		return $response;
	} //End of save_triggers_global_setting



	public function get_coin_trigger_setting($triggers_type){
	  	$this->mongo_db->where_in('order_mode', array('test', 'live'));
		$this->mongo_db->where(array('triggers_type' => $triggers_type));
		$this->mongo_db->order_by(array('coins'=>1));
		$res = $this->mongo_db->get('setting_triggers_collections');
		 $result = iterator_to_array($res);
		$full_arr = array();
		if(count($result)>0){
			foreach ($result as $data) {
					$full_arr[$data['coins']][$data['order_mode']] =  (array)$data;
			}
		}
		return $full_arr;
	}//End of get_coin_trigger_setting

	public function save_on_off_trading($data){
			$created_date = date('Y-m-d G:i:s');
			$created_date_obj = $this->mongo_db->converToMongodttime($created_date);
			$types_arr = array('custom_on_of_trading','automatic_on_of_trading','buy_on_of_trading','sell_on_of_trading','buy_on_of_manual_trading','sell_on_of_manual_trading','on_of_live_trading','on_of_test_trading');
			if(!empty($data)){
				$upsert['upsert'] = true;
				foreach($types_arr as $type){
					$status = $data[$type];
					$db = $this->mongo_db->customQuery();
					$updArr['type'] = $type;
					$updArr['status'] = $status;
					$updArr['created_date'] = $created_date_obj;
					$set['$set'] = $updArr; 
					$where['type'] = $type;
					$db->trading_on_off_collection->updateOne($where,$set,$upsert);
				}//End of foreach status
			}//End of data Not Empty
			return true;
	}// End of save_on_off_trading

	public  function check_on_of_trading_exist($type)
	{
		$resp = false;
		$this->mongo_db->where(array('type'=>$type));
		$is_exist = $this->mongo_db->get('trading_on_off_collection');
		$is_exist = iterator_to_array($is_exist);
		if(!empty($is_exist)){
			$resp = true;
		}
		return $resp;
	}//End of check_on_of_trading_exist

	public function get_saved_on_off_trading(){
		$data = $this->mongo_db->get('trading_on_off_collection');
		return iterator_to_array($data);
	}//End of get_saved_on_off_trading


	public function create_order_setting_log($symbol,$triggers_type,$order_mode,$new_arr){

		$this->mongo_db->where(array('triggers_type'=>$triggers_type,'coin'=>$symbol,'order_mode'=>$order_mode));
		$response = $this->mongo_db->get('trigger_global_setting');
		$response = iterator_to_array($response);

		$final_arr =(array) $response[0];
		unset($final_arr['_id']);

		$final_arr = $this->convert_object_arr_to_simple_arr($final_arr,'status');
		;



		for($index = 1;$index<=10;$index++){
			$final_arr = $this->convert_object_arr_to_simple_arr($final_arr,'buy_status_rule_'.$index);
			$final_arr = $this->convert_object_arr_to_simple_arr($final_arr,'sell_status_rule_'.$index);
			$final_arr = $this->convert_object_arr_to_simple_arr($final_arr,'buy_trigger_type_rule_'.$index);
			$resp_arfinal_arrr = $this->convert_object_arr_to_simple_arr($final_arr,'sell_status_rule_'.$index);
			$final_arr = $this->convert_object_arr_to_simple_arr($final_arr,'sell_trigger_type_rule_'.$index);
			$final_arr = $this->convert_object_arr_to_simple_arr($final_arr,'buy_status_rule_'.$index);
			$final_arr = $this->convert_object_arr_to_simple_arr($final_arr,'sell_order_level_'.$index);
			$final_arr = $this->convert_object_arr_to_simple_arr($final_arr,'buy_order_level_'.$index);
		}


		$old_arr = $final_arr;

		$different_arr = array();

			foreach($new_arr as $key =>$value){
				if(is_array($value)){

					if(count($value)>0){

						$new_sub_arr = $value;
						$old_sub_arr = $old_arr[$key];
						$sub_diff = array_diff_assoc($new_sub_arr, $old_sub_arr);
						if(!empty($sub_diff)){
							$different_arr[$key] = $sub_diff;
						}

					}

				}
			}

			$log_message = '';
			$diff_associative_arr = array_diff_assoc($new_arr, $old_arr);
			$log_arr = array_merge($diff_associative_arr,$different_arr);


			if(!empty($log_arr)){
				foreach ($log_arr as $key => $value) {
					if(is_array($value)){

						$log_message .=$key.' Change From <strong>'.implode(',',$old_arr[$key]).'</strong> To <strong>'.implode(',',$old_arr[$key]).'</strong><br>';
					}else{
						$log_message .=$key.' Change From <strong>'.$old_arr[$key].'</strong> To <strong> '.$value.'   </strong><br>';
					}
				}
			}


		$created_date = date('Y-m-d G:i:s');
		$arr['changed_by'] = $this->session->userdata('admin_id');
		$arr['modified_date'] = $this->mongo_db->converToMongodttime($created_date);
		$arr['created_date'] = $created_date;

		if($log_message == ''){
			$log_message = 'NO Changes Occure';
		}
		$arr['log_message'] = $log_message;

		$this->mongo_db->insert('barrier_trigger_setting_changed_log',$arr);


	}//End of create_order_setting_log function


	public function convert_object_arr_to_simple_arr($final_arr,$field){

		$convert = (array)$final_arr[$field];
		unset($final_arr[$field]);
		$new_arr[$field] = $convert;
		$resp =array_merge($final_arr,$new_arr);
		return $resp;
	}


	public function get_barrier_trigger_setting_changed_log(){
		$this->mongo_db->order_by(array('modified_date'=>-1));
		$response = $this->mongo_db->get('barrier_trigger_setting_changed_log');
		return iterator_to_array($response);
	}//End of get_barrier_trigger_setting_changed_log

	public function save_convert_trading_on_reserved_ips($data){
		extract($data);
		$created_date = date('Y-m-d G:i:s');
		$created_date_obj = $this->mongo_db->converToMongodttime($created_date);

		$insert_arr['trading_ip'] = trim($trading_ip);
		$insert_arr['reserved_ip'] = trim($reserved_ip);
		$insert_arr['is_actual_ip_tradding_on'] = 'OFF';
		$insert_arr['created_date'] = $created_date_obj; 

		$this->mongo_db->where(array('trading_ip'=>trim($trading_ip),'reserved_ip'=>trim($reserved_ip)));
		$response_row = $this->mongo_db->get('convert_trading_on_reserved_ips');
		$data_row = iterator_to_array($response_row);
		if(!empty($data_row)){
			//%%%%%%%%%%%%%%%%%%%%%%%%% Update Secton %%%%%%%%
			$this->mongo_db->where(array('trading_ip'=>trim($trading_ip),'reserved_ip'=>trim($reserved_ip)));
			$this->mongo_db->set($insert_arr);
			return $this->mongo_db->update('convert_trading_on_reserved_ips');
		}else{
			//%%%%%%%%%%%%%%%%% Insert Section %%%%%%%%%%%%%%%%
			return  $this->mongo_db->insert('convert_trading_on_reserved_ips',$insert_arr);
		}
		return true;
	}//end of save_convert_trading_on_reserved_ips

	public function get_convert_trading_on_reserved_ips(){

		$this->mongo_db->where(array('is_actual_ip_tradding_on'=>'OFF'));
		$response_row = $this->mongo_db->get('convert_trading_on_reserved_ips');
		$response_data = iterator_to_array($response_row);
		return $response_data;
	}// %%%%%%%%%%% End of convert_trading_on_reserved_ips %%%%%%%%%%

	public function unassign_convert_trading_on_reserved_ips($id){
		$this->mongo_db->where(array('_id'=>$id));
		$upd_arr = array('is_actual_ip_tradding_on'=>'ON');
		$this->mongo_db->set($upd_arr);
		return $this->mongo_db->update('convert_trading_on_reserved_ips');
	}//End of unassign_convert_trading_on_reserved_ips

	public function get_trading_on_off_setting_for_triggers($coin,$trigger)
	{
		$filter['trigger'] = $trigger;
		$filter['coin'] = $coin;

		$this->mongo_db->where($filter);
		$data = $this->mongo_db->get('trading_on_off_collection');
		$data = iterator_to_array($data);

		$respArr = array();
		foreach ($data as $row) {
			$respArr[$row['type']] =$row['status']; 
		}

		return $respArr;	
	}//end of get_trading_on_off_setting_for_triggers

} //End of Model
?>
