<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {


	public function __construct()
     {

		parent::__construct();
		$_REQUEST = array_merge($_GET, $_POST, $_COOKIE);

		//load main template
		$this->stencil->layout('admin_layout');

		//load required slices
		$this->stencil->slice('admin_header_script');
		$this->stencil->slice('admin_header');
		$this->stencil->slice('admin_left_sidebar');
		$this->stencil->slice('admin_footer_script');

		// Load Modal
		$this->load->model('admin/mod_login');
		$this->load->model('admin/mod_users');

	}


	// public function index()
	// {
	// 	//Login Check
	// 	$this->mod_login->verify_is_admin_login();
	// 	//Fetching users Record
	// 	$users_arr = $this->mod_users->get_all_users();
	// 	$data['users_arr'] = $users_arr;

	// 	//stencil is our templating library. Simply call view via it
	// 	$this->stencil->paint('admin/users/users',$data);

	// }//End index


	// public function add_user()
	// {
	// 	//Login Check
	// 	$this->mod_login->verify_is_admin_login();

	// 	//stencil is our templating library. Simply call view via it
	// 	$this->stencil->paint('admin/users/add_user');

	// }//End add_user


	// public function add_user_process(){

	// 	//Login Check
	// 	$this->mod_login->verify_is_admin_login();

	// 	//Adding add_user
	// 	$user_id = $this->mod_users->add_user($this->input->post());

	// 	if($user_id){

	// 		$this->session->set_flashdata('ok_message', 'User added successfully.');
	// 		redirect(base_url().'admin/users/add-user');

	// 	}else{

	// 		$this->session->set_flashdata('err_message', 'User cannot added. Something went wrong, please try again.');
	// 		redirect(base_url().'admin/users/add-user');

	// 	}//end if

	// }//end add_user_process


	// public function edit_user($user_id)
	// {
	// 	//Login Check
	// 	$this->mod_login->verify_is_admin_login();

	// 	//Fetching user Record
	// 	$user_arr = $this->mod_users->get_user($user_id);
	// 	$data['user_arr'] = $user_arr;
	// 	$data['user_id'] = $user_id;


	// 	$this->stencil->paint('admin/users/edit_user',$data);

	// }//End edit_user


	// public function edit_user_process(){

	// 	//Login Check
	// 	$this->mod_login->verify_is_admin_login();
	// 	//edit_user
	// 	$user_id = $this->mod_users->edit_user($this->input->post());
	// 	if($user_id){
	// 		redirect(base_url().'admin/users/edit-user/'.$user_id);
	// 	}else{
	// 		redirect(base_url().'admin/users/edit-user/'.$user_id);
	// 	}//end if

	// }//end edit_user_process

	// public function get_all_users(){

	// 	//$this->mongo_db->where(array('_id' => $user_id, 'email_address' => $email_address));
	// 	$get_users = $this->mongo_db->get('users');
	// 	$user_arr = iterator_to_array($get_users);

	// 	 if($user_arr) {
    //         $json_array['success']  = true;
	// 		$json_array['userData'] = $user_arr;
    //      }else {
    //         $json_array['success']  = false;
	// 		$json_array['userData'] = 'Error';
    //     }
    //     echo json_encode($json_array);
    //     exit;
	// 	;
	// }//get_all_users



	// public function delete_user($user_id){

	// 	//Login Check
	// 	$this->mod_login->verify_is_admin_login();

	// 	//Delete user
	// 	$delete_user = $this->mod_users->delete_user($user_id);

	// 	if($delete_user){

	// 		$this->session->set_flashdata('ok_message', 'User deleted successfully.');
	// 		redirect(base_url().'admin/users');

	// 	}else{

	// 		$this->session->set_flashdata('err_message', 'User can not deleted. Something went wrong, please try again.');
	// 		redirect(base_url().'admin/users');

	// 	}//end if

	// }//end delete_user

	// *********************    Ali khan work goes here ******************* //
	// public function checkDgUserRefferAllinKB(){

	// 	$checkUserArr = $this->mod_users->checkDgUserRefferAllinKB();
	// 	echo "<pre>";   print_r($checkUserArr); exit;

	// 	foreach($checkUserArr as $row){

	// 		$dgUserId  =  (string)$row['_id'];
	// 		$this->db->dbprefix('users_details');
	// 		$this->db->order_by('id DESC');
	// 		$this->db->where('email_address',$row['email_address']);
	// 		$get_users = $this->db->get('users_details');

	// 		//echo $this->db->last_query();
	// 		$users_arr = $get_users->row_array();
	// 		// *********  Old Code will be use next time  ********** //
	// 		$upd_data = array(
	// 			   'report_id' => $this->db->escape_str(trim($users_arr['id'])),
	// 			   'kula_member_id' => $this->db->escape_str(trim($users_arr['member_id'])),
	// 			   'refferal_id' => $this->db->escape_str(trim($users_arr['referral_member_id'])),

	// 			 );
	// 		// *********  Old Code will be use next time  ********** //

	// 	    if($users_arr!=''){

	// 				if($users_arr['referral_member_id']==0 || $users_arr['referral_member_id']==''){
	// 					$upd_data = array(
	// 					   'user_type' => $this->db->escape_str(trim('direct_n_kula')),
	// 					   'pool_term' => $this->db->escape_str(trim('long'))
	// 					);

	// 					$this->mongo_db->where(array('_id' => $dgUserId));
	// 					$this->mongo_db->set($upd_data);
	// 					$upd_into_db = $this->mongo_db->update('users', $upd_data);
	// 				}else{
	// 					$upd_data = array(
	// 					   'user_type' => $this->db->escape_str(trim('indirect_n_kula')),
	// 					   'pool_term' => $this->db->escape_str(trim('short'))
	// 					);

	// 					$this->mongo_db->where(array('_id' => $dgUserId));
	// 					$this->mongo_db->set($upd_data);
	// 					$upd_into_db = $this->mongo_db->update('users', $upd_data);

	// 				}

	// 		}else{

	// 			$upd_data = array(
	// 			   'user_type' => $this->db->escape_str(trim('direct_n_digie')),
	// 			   'pool_term' => $this->db->escape_str(trim('short'))
	// 			);

	// 			$this->mongo_db->where(array('_id' => $dgUserId));
	// 			$this->mongo_db->set($upd_data);
	// 			$upd_into_db = $this->mongo_db->update('users', $upd_data);

	// 		}
	// 	}

	// }

	// public function cronJobUpdateUsersWith(){

	// 	$checkUserArr = $this->mod_users->checkDgUserRefferAllinKB();
	// 	echo "<pre>";   print_r($checkUserArr); exit;

	// 	foreach($checkUserArr as $row){

	// 		$dgUserId  =  (string)$row['_id'];
	// 		$this->db->dbprefix('users_details');
	// 		$this->db->order_by('id DESC');
	// 		$this->db->where('email_address',$row['email_address']);
	// 		$get_users = $this->db->get('users_details');

	// 		//echo $this->db->last_query();
	// 		$users_arr = $get_users->row_array();
	// 		// *********  Old Code will be use next time  ********** //
	// 		$upd_data = array(
	// 			   'report_id' => $this->db->escape_str(trim($users_arr['id'])),
	// 			   'kula_member_id' => $this->db->escape_str(trim($users_arr['member_id'])),
	// 			   'refferal_id' => $this->db->escape_str(trim($users_arr['referral_member_id'])),

	// 			 );
	// 		// *********  Old Code will be use next time  ********** //

	// 	    if($users_arr!=''){

	// 				if($users_arr['referral_member_id']==0 || $users_arr['referral_member_id']==''){
	// 					$upd_data = array(
	// 					   'user_type' => $this->db->escape_str(trim('direct_n_kula')),
	// 					   'pool_term' => $this->db->escape_str(trim('long'))
	// 					);

	// 					$this->mongo_db->where(array('_id' => $dgUserId));
	// 					$this->mongo_db->set($upd_data);
	// 					$upd_into_db = $this->mongo_db->update('users', $upd_data);
	// 				}else{
	// 					$upd_data = array(
	// 					   'user_type' => $this->db->escape_str(trim('indirect_n_kula')),
	// 					   'pool_term' => $this->db->escape_str(trim('short'))
	// 					);

	// 					$this->mongo_db->where(array('_id' => $dgUserId));
	// 					$this->mongo_db->set($upd_data);
	// 					$upd_into_db = $this->mongo_db->update('users', $upd_data);

	// 				}

	// 		}else{

	// 			$upd_data = array(
	// 			   'user_type' => $this->db->escape_str(trim('direct_n_digie')),
	// 			   'pool_term' => $this->db->escape_str(trim('short'))
	// 			);

	// 			$this->mongo_db->where(array('_id' => $dgUserId));
	// 			$this->mongo_db->set($upd_data);
	// 			$upd_into_db = $this->mongo_db->update('users', $upd_data);

	// 		}
	// 	}

	// }



	// public function UpdateUserEmailBackOffice(){

	// 	$rawData = file_get_contents("php://input");
    //     $data 	 = (array) json_decode($rawData);
	// 	$dg_id        =  $data['dg_id'];
	// 	$new_email    =  $data['email'];
	// 	$checkUserArr =  $this->mod_users->UpdateUserEmail($dg_id,$new_email);
	// 	if($checkUserArr) {
	// 		echo "good";
    //         $json_array['success']  = true;
    //     }else {
    //      	echo "not good";
    //         $json_array['success']  = false;
    //     }
    //     echo json_encode($json_array);
    //     exit;

	// }//end UpdateUserEmailBackOffice
	


	// public function checkUserExist(){

	// 	 $dg_id         =  $this->input->post('dg_id');
	// 	 $email_address =  $this->input->post('email_address');
	// 	 $checkUserArr  = $this->mod_users->checkUserExist($dg_id,$email_address);
	// 	 if($checkUserArr) {
    //         $json_array['success']  = true;
	// 		$json_array['userData'] = $checkUserArr;
    //      }else {
    //         $json_array['success']  = false;
	// 		$json_array['userData'] = 'Error';
    //     }
    //     echo json_encode($json_array);
    //     exit;

	// }//end checkUserExist
	
	
    // public function changeUserEmailBackoffice(){

	// 	 $dg_id         =  $this->input->post('dg_id');
	// 	 $email_address =  $this->input->post('email_address');
	// 	 $checkUserArr  = $this->mod_users->checkUserExist($dg_id,$email_address);
	// 	 if($checkUserArr) {
    //         $json_array['success']  = true;
	// 		$json_array['userData'] = $checkUserArr;
    //      }else {
    //         $json_array['success']  = false;
	// 		$json_array['userData'] = 'Error';
    //     }
    //     echo json_encode($json_array);
    //     exit;

	// }//end change_user_email
	

	// public function checkUserExistThroughEmail(){

	// 	 $email_address =  $this->input->post('email_address');

	// 	 $checkUserArr  = $this->mod_users->checkUserExistThroughEmail($email_address);
	// 	 if($checkUserArr) {
    //         $json_array['success']  = true;
	// 		$json_array['userData'] = $checkUserArr;
    //      }else {
    //         $json_array['success']  = false;
	// 		$json_array['userData'] = 'Error';
    //     }
    //     echo json_encode($json_array);
    //     exit;

	// }//end checkUserExist


	// public function getUser(){
	// 	echo "sadfsdaf";
	// 	$rawData = file_get_contents("php://input");
	// 	$data    = json_decode($rawData);
    //     extract($data);
	// 	echo "<pre>";   print_r($data); exit;

	// 	 $userArr = $this->mod_users->getUser();

	// 	 $finalrr  =  array();
	// 	 foreach($userArr as $row){

	// 		    $finalrr['id'] = (string)$row->_id;
	// 			$finalrr['first_name'] = $row->first_name;
	// 			$finalrr['last_name'] = $row->last_name;
	// 			$finalrr['username'] = $row->username;
	// 			$finalrr['email_address'] = $row->email_address;
	// 			$finalrr['phone_number'] = $row->phone_number;
	// 			$finalrr['password'] = $row->password;
	// 			$finalrr['activation_code'] = $row->activation_code;
	// 			$finalrr['status'] = $row->status;
	// 			$finalrr['user_role'] = $row->user_role;
	// 			$finalrr['user_soft_delete'] = $row->user_soft_delete;
	// 			$finalrr['special_role'] = $row->special_role;
	// 			$finalrr['google_auth'] = $row->google_auth;
	// 			$finalrr['created_date_human'] = $row->created_date_human;
	// 			$finalrr['created_date'] = $row->created_date;
	// 			$finalrr['timezone'] = $row->timezone;
	// 			$finalrr['trading_ip'] = $row->trading_ip;
	// 			$fullarray[] = $finalrr;
	// 	 }
	// 	 if($userArr) {

    //         $json_array['success']  = true;
	// 		$json_array['userData'] = $fullarray;
    //      }else {
    //         $json_array['success']  = false;
	// 		$json_array['userData'] = 'Error';
    //     }
    //     echo json_encode($json_array);
    //     exit;

	// }//end getAllUsers
	
	
	
	// public function getAllUsers(){

	// 	 $userArr = $this->mod_users->get_all_users();

	// 	 $finalrr  =  array();
	// 	 foreach($userArr as $row){
			 
	// 		     if($row->reason!='testing'){

	// 					$finalrr['id'] = (string)$row->_id;
	// 					$finalrr['first_name'] = $row->first_name;
	// 					$finalrr['last_name'] = $row->last_name;
	// 					$finalrr['username'] = $row->username;
	// 					$finalrr['email_address'] = $row->email_address;
	// 					$finalrr['phone_number'] = $row->phone_number;
	// 					$finalrr['password'] = $row->password;
	// 					$finalrr['activation_code'] = $row->activation_code;
	// 					$finalrr['status'] = $row->status;
	// 					$finalrr['user_role'] = $row->user_role;
	// 					$finalrr['user_soft_delete'] = $row->user_soft_delete;
	// 					$finalrr['special_role'] = $row->special_role;
	// 					$finalrr['google_auth'] = $row->google_auth;
	// 					$finalrr['created_date_human'] = $row->created_date_human;
	// 					$finalrr['created_date'] = $row->created_date;
	// 					$finalrr['timezone'] = $row->timezone;
	// 					$finalrr['trading_ip'] = $row->trading_ip;
	// 					$fullarray[] = $finalrr;
	// 			 }
				
	// 	 }
	// 	 if($userArr) {

    //         $json_array['success']  = true;
	// 		$json_array['userData'] = $fullarray;
    //      }else {
    //         $json_array['success']  = false;
	// 		$json_array['userData'] = 'Error';
    //     }
    //     echo json_encode($json_array);
    //     exit;

	// }//end getAllUsers
	
	


	// public function getAllUsersWithMemberId(){

	// 	 $userArr = $this->mod_users->getAllUsersWithMemberId();


	// 	 $finalrr  =  array();
	// 	 foreach($userArr as $row){
	// 		    $finalrr['id'] = (string)$row->_id;
	// 			$finalrr['report_id'] = $row->report_id;
	// 			$finalrr['kula_member_id'] = $row->kula_member_id;
	// 			$finalrr['first_name'] = $row->first_name;
	// 			$finalrr['last_name'] = $row->last_name;
	// 			$finalrr['username'] = $row->username;
	// 			$finalrr['email_address'] = $row->email_address;
	// 			$finalrr['phone_number'] = $row->phone_number;
	// 			$finalrr['password'] = $row->password;
	// 			$finalrr['activation_code'] = $row->activation_code;
	// 			$finalrr['status'] = $row->status;
	// 			$finalrr['user_role'] = $row->user_role;
	// 			$finalrr['user_soft_delete'] = $row->user_soft_delete;
	// 			$finalrr['special_role'] = $row->special_role;
	// 			$finalrr['google_auth'] = $row->google_auth;
	// 			$finalrr['created_date_human'] = $row->created_date_human;
	// 			$finalrr['created_date'] = $row->created_date;
	// 			$finalrr['timezone'] = $row->timezone;
	// 			$finalrr['trading_ip'] = $row->trading_ip;
	// 			$fullarray[] = $finalrr;
	// 	 }


	// 	 if($userArr) {
    //         $json_array['success']  = true;
	// 		$json_array['userData'] = $fullarray;
    //      }else {
    //         $json_array['success']  = false;
	// 		$json_array['userData'] = 'Error';
    //     }
    //     echo json_encode($json_array);
    //     exit;

	// }//end getAllUsersWithMemberId



	// public function updateUserReposrtId(){

	// 	 $report_id  =  $this->input->post('report_id');
	// 	 $member_id  =  $this->input->post('member_id');
	// 	 $dg_id      =  $this->input->post('dg_id');
	// 	 $userArr = $this->mod_users->updateUserReposrtId($report_id,$member_id,$dg_id);
	// 	 if($userArr) {
    //         $json_array['success']  = true;
	// 		$json_array['userData'] = $userArr;
    //      }else {
    //         $json_array['success']  = false;
	// 		$json_array['userData'] = 'Error';
    //     }
    //     echo json_encode($json_array);
    //     exit;

	// }//end updateUserReposrtId
	//$user_id ='',$user_id ='',$user_id ='',$user_id =''
    // public function get_all_user_orders() {

	// 	 $rawData = file_get_contents("php://input");
	// 	 $data    = json_decode($rawData);

	// 	 extract($data);

	// 	 $user_id     = $data->user_id;
	// 	 $start_date  = $data->start_date;
	// 	 $end_date    = $data->end_date;
	// 	 $status      = $data->status;
	// 	//Check Filter Data
	// 	$session_post_data = $filter_array;
	// 	$search_array = array('admin_id' => $user_id,'order_mode' => 'live');
	// 	$connetct = $this->mongo_db->customQuery();
	// 	if ($start_date != "" && $end_date != "") {
	// 		$created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
	// 		$orig_date = new DateTime($created_datetime);
	// 		$orig_date = $orig_date->getTimestamp();
	// 		$start_date1 = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
	// 		$created_datetime22 = date('Y-m-d G:i:s', strtotime($end_date));
	// 		$orig_date22 = new DateTime($created_datetime22);
	// 		$orig_date22 = $orig_date22->getTimestamp();
	// 		$end_date1 = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
	// 		$search_array['created_date'] = array('$gte' => $start_date1, '$lte' => $end_date1);
	// 	}

	// 	if ($status == 'open' || $status == 'sold') {
	// 		if ($status == 'open') {
			
	// 			$search_array['status'] = 'FILLED';
	// 			$search_array['is_sell_order'] = 'yes';
	// 			$cursor = $connetct->sold_buy_orders->find($search_array);

	// 		} elseif ($status == 'sold') {
	// 			$search_array['status'] = 'FILLED';
	// 			$search_array['is_sell_order'] = 'sold';
	// 			//echo "<pre>";  print_r($search_array); 
	// 			$cursor = $connetct->sold_buy_orders->find($search_array);
	// 		}

	// 	} elseif ($status == 'all') {

	// 		$search_array['status'] = array('$in' => array('error', 'canceled', 'submitted'));
	// 		$cursor = $connetct->sold_buy_orders->find($search_array);
	// 	} else {
	// 		$search_array['status'] = $status;
	// 		$cursor = $connetct->sold_buy_orders->find($search_array);
	// 	}

	// 	$responseArr = iterator_to_array($cursor);
		
	// 	$fullarray = array();
	// 	foreach ($responseArr as $valueArr) {
	// 		$returArr = array();
	// 		$profit   = 0;

	// 		if (!empty($valueArr)) {

	// 			$datetime = $valueArr['created_date']->toDateTime();
	// 			$created_date = $datetime->format(DATE_RSS);
	// 			$datetime = new DateTime($created_date);
	// 			$datetime->format('Y-m-d g:i:s A');
	// 			$new_timezone = new DateTimeZone('Asia/Karachi');
	// 			$datetime->setTimezone($new_timezone);
	// 			$formated_date_time = $datetime->format('Y-m-d g:i:s A');
	// 			$returArr['id'] = (string) $valueArr['_id'];
	// 			$returArr['purchased_price'] = !empty($valueArr['purchased_price']) ? $valueArr['purchased_price'] :$valueArr['market_value'];
	// 			$returArr['sold_price'] = $valueArr['market_sold_price'];
	// 			$profit = ((($returArr['sold_price'] - $returArr['purchased_price']) / $returArr['purchased_price']) * 100);
	// 			$returArr['profit_loss_percentage'] = number_format($profit, 2);
	// 			$returArr['coin'] = $valueArr['symbol'];
	// 			$returArr['quantity'] = $valueArr['quantity'];
	// 			$returArr['order_type'] = $valueArr['order_type'];
	// 			if ($valueArr['status'] == 'FILLED' && $valueArr['is_sell_order'] == 'yes') {
	// 				$returArr['status'] = 'open';
	// 			}
	// 			if ($valueArr['status'] == 'FILLED' && $valueArr['is_sell_order'] == 'sold') {
	// 				$returArr['status'] = 'sold';
	// 			}
	// 			$returArr['user_id'] = $valueArr['admin_id'];
	// 			$returArr['created_date'] = $formated_date_time;
	// 		}
	// 		$fullarray[] = $returArr;
	// 	}
	// 	$array  =  json_encode($fullarray);
    //     echo  $array; exit;
	// }

	public function check_user_dg(){
		
		$rawData = file_get_contents("php://input");
		$data 	  = (array) json_decode($rawData);

		$dg_id         = $data['dg_id'];
		$checkUserArr  = $this->mod_users->check_user_dg($dg_id);
		if($checkUserArr) {
		   $json_array['success']  = true;
		   $json_array['userData'] = $checkUserArr;
		}else {
		   $json_array['success']  = false;
		   $json_array['userData'] = 'Error';
	   }
	   echo json_encode($json_array);
	   exit;

    }//end checkUserExist


    public function get_user_orders($search, $exchange){
	   
    }

		

}
