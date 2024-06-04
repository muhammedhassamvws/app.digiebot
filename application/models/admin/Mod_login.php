<?php
class mod_login extends CI_Model {

	function __construct() {

		parent::__construct();

	}

	//Verify If User is Login on the authorized Pages.
	public function verify_is_admin_login() {


		//echo "<pre>";   print_r($this->session->userdata()); exit;

		if ($this->session->userdata('logged_in') != 1) {

			$this->session->set_flashdata('err_message', 'You have to login to access this page.');
			redirect(base_url().'admin/login');

		}//if(!$this->session->userdata('id'))

	}//end verify_is_user_login()

	public function user_soft_delete_sql($user_id) {
		$data = array(
			'user_soft_delete' => 1,
		);

		$this->mongo_db->dbprefix('users');
		$this->mongo_db->where('id', strip_quotes($user_id));
		$update_st = $this->mongo_db->update('users', $data);
		return $update_st;
	}

	public function curlRequestChatApp($id, $username,$email) {
		
		        extract($data);
				// Data in JSON format
                $data                  = array(
                    'id'    => $id,
                    'username' => $username,
                    'email'   => $email,
                );
                $payload               = json_encode($data);
                 /* _,.-'~'-.,__,.-'~'-.,__,.-'~'-.,__,.-'~'-.,__,.-'~'-.,__,.-'~'-.._ */
                 ## Get users orders of last week Now test orders from digiebot        ##
                $ch                    = curl_init('https://chat.updatemedaily.com/digiebot/addUser/');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                // Set HTTP Header for POST request 
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($payload)
                ));
                // Submit the POST request
                $result = curl_exec($ch);
                // Close cURL session handle
                curl_close($ch);
                $ordersArr           = json_decode($result);
				return true;
	}

	//Validation of Login
	public function validate_credentials_sql($username, $password) {

		$this->db->dbprefix('users');
		$this->db->where('username', strip_quotes($username));
		$this->db->where('password', strip_quotes(md5($password)));
		$this->db->where('status', 0);
		$this->db->where('user_soft_delete', 0);
		$get = $this->db->get('users');
		$row = $get->row_array();

		if (count($row) > 0) {
			$this->update_login_time($row['id']);
			return $row;

		}

	}//end function validate

	//Validation of Login
	public function validate_credentials($username, $password) {

		$search_Arr['username'] = $username;
		$search_Arr['password'] = md5($password);
		$search_Arr['status'] = (string)0;
		$search_Arr['user_soft_delete'] = '0';

		$this->mongo_db->where($search_Arr);
		$get = $this->mongo_db->get('users');
		$row = iterator_to_array($get);
		
		
		
	
	    if (count($row) > 0) {
			
			$this->update_login_time($row['_id']);
			
			return $row[0];
		}

	}//end function validate
	//Validation of Login
	public function validate_credentials_digie($username, $password) {

		$search_Arr['username'] = $username;
		$this->mongo_db->where($search_Arr);
		$get = $this->mongo_db->get('users');
		$row = iterator_to_array($get);

	if (count($row) > 0) {
			$this->update_login_time($row['_id']);
			return $row[0];
		}

	}//end function validate

	public function update_login_time_sql($id) {
		$login_time = date("Y-m-d G:i:s");
		$upd_arr    = array('last_login_datetime' => $this->mongo_db->converToMongodttime($login_time));

		$this->db->where("id", $id);
		$this->db->update("users", $upd_arr);

		return true;
	}//update_login_time

	public function update_login_time($id) {
		$login_time = date("Y-m-d G:i:s");
		$upd_arr    = array('last_login_datetime' => $this->mongo_db->converToMongodttime($login_time));

		$this->mongo_db->where(array("_id" => $id));
		$this->mongo_db->set($upd_arr);
		$this->mongo_db->update("users");

		return true;
	}//update_login_time
	
	public function updateTimeIstLogin($id,$email,$first_name, $last_name) {
		         
				
		// Send a reuest to users.digiebot.com
		// Data in JSON format
		$data                  = array(
					'id'       => (string)($id),
					'email'    => $email,
		);
		$payload               = json_encode($data);
		 /* _,.-'~'-.,__,.-'~'-.,__,.-'~'-.,__,.-'~'-.,__,.-'~'-.,__,.-'~'-.._ */
		 ## Get users orders of last week Now test orders from digiebot        ##
		$ch                    = curl_init('http://users.digiebot.com/cronjob/startSubscriptionFromDigieBot');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		// Set HTTP Header for POST request 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($payload)
		));
		// Submit the POST request
		$result = curl_exec($ch);
		// Close cURL session handle
		curl_close($ch);
		$userStatusArr           = json_decode($result);
		
		if($result){
		
		$login_time = date("Y-m-d G:i:s");
		
		$login_time2 = date('d F, Y (l)');
		$upd_arr    = array('isttime_login_datetime' => $login_time,'ist_login_status' => 1);

		$this->mongo_db->where(array("_id" => $id));
		$this->mongo_db->set($upd_arr);
		$this->mongo_db->update("users");
		
		$noreply_email  = "no_reply@digiebot.com";
        $email_from_txt = "From DigieBot";
        $email_subject  = "DigieBot Subscription Started";
		
		 $email_body = '<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px;font-family:Microsoft Yahei,Arial,Helvetica,sans-serif;padding:0;margin:0;color:#333;background-image:url(https://cryptoconsultant.com/wp-content/uploads/2017/02/bg2.jpg);background-color:#f7f7f7;background-repeat:repeat-x;background-position:bottom left">
		<tbody><tr>
    	<td>
        	<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
              <tbody><tr>
                <td align="center" valign="middle" style="padding:33px 0">
                  <img src="https://app.digiebot.com/assets/images/digiebot_logo.png">
                </td>
              </tr>
              <tr>
                <td>
                    <div style="padding:0 30px;background:#fff">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tbody><tr>
                            <td style="border-bottom:1px solid #e6e6e6;font-size:18px;padding:20px 0">
                            	  <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            	     <tbody><tr>
                            		 <td>Digiebot Subscription </td>
                            		    <td>

                            		    </td>
                            		</tr>
                            	    </tbody></table>
                            	 </td></tr>
                          <tr>
                            <td style="font-size:14px;line-height:30px;padding:20px 0;color:#666">Hello, ' . $first_name . " " . $last_name . '<br>You have just initiated a request your DigieBot subscription is started.<strong style="margin:0 5px"><a href="mailto:' . $email . '" target="_blank"></a></strong></td>
                          </tr>
                          <tr>
                            <td style="padding:5px 0">
                              <table width="100%" style="font-size: 12px; text-align: left;">';
         
		 $email_body .= 'Your DigieBot subscription started from today  <strong>'.$login_time2.'</strong>';
		 
        $email_body .= '</table>
                            </td>
                          </tr>

                          <tr>
                            <td style="padding:20px 0 10px 0;line-height:26px;color:#666">If this activity is not your own operation, please contact us immediately. </td>
                          </tr>
						  <tr>
						  </tr>
							<tr>
                            <td style="padding:30px 0 15px 0;font-size:12px;color:#999;line-height:20px">Digiebot Team<br>Automated message.please do not reply</td>
                          </tr>
                        </tbody></table>
                    </div>
                </td>
              </tr>

              <tr>
                <td align="center" style="font-size:12px;color:#999;padding:20px 0">© ' . date('Y') . ' digiebot.com All Rights Reserved<br>URL：<a style="color:#999;text-decoration:none" href="https://app.digiebot.com/admin" target="_blank">Digiebot Application</a>&nbsp;
		&nbsp;
		E-mail：<a href="mailto:support@digiebot.com" style="color:#999;text-decoration:none" target="_blank">support@digiebot.com</a></td>
		              </tr>
		            </tbody></table>
		        </td>
		    </tr>
		</tbody></table>';

            //Preparing Sending Email
			// $this->config->load('email', TRUE);
            // $config = $this->config->item('email');

            // $this->load->library('email', $config);

            // $this->email->from($noreply_email, $email_from_txt);
            // $this->email->to($email);
            // $this->email->subject($email_subject);
			// $emailData = $this->email->message($email_body);	

			//Send Email used amazon ses
			$this->load->library('Amazon_ses_bulk_email');
			// $this->amazon_ses_bulk_email->send_bulk_email($html_message, $subject, $from, $to, $cc = '', $bcc = '', $title = '');
			$email_sent = $this->amazon_ses_bulk_email->send_bulk_email($email_body, $email_subject, 'support@digiebot.com', $email, $cc = '', $bcc = '', $title = '');

			
		}
			
		return true;
	}//updateTimeIstLogin

	//Email Address Validation
	public function verify_email_sql($email_address) {

		$this->db->dbprefix('users');
		$this->db->where('email_address', strip_quotes($email_address));
		$this->db->where('status', 0);
		$this->db->where('user_soft_delete', 0);
		$get = $this->db->get('users');
		//echo $email_address;
		//echo $this->db->last_query();

		if (count($get) > 0) {
			return $get->row_array();
		}

	}//end function verify_email

	//Email Address Validation
	public function verify_email($email_address) {

		$search_Arr  =  array();

     	$search_array['email_address'] = $email_address;
		//$search_array['status'] = (string)0;
		//$search_array['user_soft_delete'] = (string)0;
		$this->mongo_db->where($search_array);
		$responseArr = $this->mongo_db->get('users');
		$row = iterator_to_array($responseArr);

		if (count($row) > 0) {
			return $row[0];
		}

	}//end function verify_email

	public function change_password_sql($data) {
		extract($data);

		$upd_arr = array(
			'password'                  => md5($new_password),
			'last_forget_password_time' => NULL,
		);

		$this->db->where('email_address', base64_decode($email));
		$upd = $this->db->update('users', $upd_arr);

		//UPDATE `tr_users` SET `password` = '121212' WHERE `id` = '6' AND `password` = '123123'
		return true;
	}

	public function change_password($data) {
		extract($data);

		$upd_arr = array(
			'password'                  => md5($new_password),
			'last_forget_password_time' => NULL,
			'is_password_changed' => 'yes'
			//'user_soft_delete' => '0',
			
		);
		
		$this->mongo_db->where(array('email_address' => base64_decode($email)));
		$this->mongo_db->set($upd_arr);
		$update = $this->mongo_db->update('users');

        if($update){
		  return true;
		}
	}

	//Send New password
	public function send_new_password($admin_id) {

		//User data
		$get_user_data = $this->mod_admin->get_admin_user_data($admin_id);

		$user_first_last_name = ucwords(strtolower(stripslashes($get_user_data['admin_user_arr']['first_name'].' '.$get_user_data['admin_user_arr']['last_name'])));
		$username             = stripslashes($get_user_data['admin_user_arr']['username']);
		$new_password         = $this->mod_common->random_number_generator(6);
		$email_address        = stripslashes(trim($get_user_data['admin_user_arr']['email_address']));

		//Updating New Password into the database

		$last_modified_date = date('Y-m-d G:i:s');
		$last_modified_ip   = $this->input->ip_address();
		$last_modified_by   = $this->session->userdata('admin_id');

		$upd_data = array(
			'password'           => $this->db->escape_str(trim(md5($new_password))),
			'last_modified_by'   => $this->db->escape_str(trim($last_modified_by)),
			'last_modified_date' => $this->db->escape_str(trim($last_modified_date)),
			'last_modified_ip'   => $this->db->escape_str(trim($last_modified_ip)),
		);

		//Update the record into the database.
		$this->db->dbprefix('admin');
		$this->db->where('id', $admin_id);
		$upd_into_db = $this->db->update('admin', $upd_data);

		if ($upd_into_db) {

			$email_from_txt_arr = $this->mod_preferences->get_preferences_setting('email_from_txt');
			$email_from_txt     = $email_from_txt_arr['setting_value'];

			$noreply_email_arr = $this->mod_preferences->get_preferences_setting('noreply_email');
			$noreply_email     = $noreply_email_arr['setting_value'];

			$sitename_arr = $this->mod_preferences->get_preferences_setting('site_name');
			$site_name    = $sitename_arr['setting_value'];

			$sitelogo_arr = $this->mod_preferences->get_preferences_setting('site logo');
			$site_logo    = $sitelogo_arr['setting_value'];

			//Email Contents
			$get_email_data = $this->mod_email->get_email(1);

			$email_subject = $get_email_data['email_arr']['email_subject'];
			$email_body    = $get_email_data['email_arr']['email_body'];

			$search_arr  = array('[SITE_URL]', '[SITE_LOGO]', '[USER_FIRST_LAST_NAME]', '[USER_USERNAME]', '[USER_NEWPASSWORD]');
			$replace_arr = array(FRONT_SURL, $site_logo, $user_first_last_name, $username, $new_password);
			$email_body  = str_replace($search_arr, $replace_arr, $email_body);

			//Preparing Sending Email
			// $this->config->load('email', TRUE);
            // $config = $this->config->item('email');
			// $this->load->library('email', $config);
			// $this->email->from($noreply_email, $email_from_txt);
			// $this->email->to($email_address);
			// $this->email->subject($email_subject);
			// $this->email->message($email_body);
			// //echo $this->email->print_debugger();
			// $this->email->clear();


			//Send Email used amazon ses
			$this->load->library('Amazon_ses_bulk_email');
			// $this->amazon_ses_bulk_email->send_bulk_email($html_message, $subject, $from, $to, $cc = '', $bcc = '', $title = '');
			$email_sent = $this->amazon_ses_bulk_email->send_bulk_email($email_body, $email_subject, 'support@digiebot.com', $email_address, $cc = '', $bcc = '', $title = '');


			return true;

		} else {
			return false;
		}//end if($upd_into_db)

	}//end function verify_email

	//Update Last Sigin Date in Admin
	public function update_signin_date_sql($user_id) {

		$data = array(
			'last_forget_password_time' => date('Y-m-d G:i:s'),
		);

		$this->db->dbprefix('users');
		$this->db->where('email_address', strip_quotes($user_id));
		$update_st = $this->db->update('users', $data);

	}//end function validate

	public function update_signin_date($email) {

		$data = array(
			'last_forget_password_time' => date('Y-m-d G:i:s'),
		);

		$this->mongo_db->where(array('email_address' => strip_quotes($email)));
		$this->mongo_db->set($data);
		$upd = $this->mongo_db->update('users');

		//echo "<pre>";  print_r($upd); exit;


	}//end function validate

	//Update Last Sigin Date in Admin
	public function get_signin_date($user_id) {

		$this->mongo_db->where('email_address', strip_quotes($user_id));
		$update_st = $this->mongo_db->get('users');

		$user_arr = iterator_to_array($update_st);
		return $user_arr[0];

	}//end function validate

	public function update_login_record($data) {
		$this->mongo_db->insert('user_login_log', $data);
	}

	public function get_all_users() {
		$this->db->dbprefix('users');
		$this->db->select(array('username', 'password', 'id'));
		$get = $this->db->get('users');
		return $get->result_array();
	}//End of get_all_users

	public function update_login_pass($data) {
		extract($data);

		$data = array(
			'password' => $password,
		);

		$this->db->dbprefix('users');
		$this->db->where('id', $id);
		return $update_st = $this->db->update('users', $data);
	}//end of update_login_pass

}

?>
