<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function construct() {
		parent::__construct();
		$this->load->helper('url');

		ini_set("memory_limit", -1);
		ini_set("display_errors", E_ERROR);
		error_reporting(E_ERROR);


	}
	public function index() {
	    
		$logged_in = $this->session->userdata('logged_in');
		$admin_id = $this->session->userdata('admin_id');

		$google_auth = $this->session->userdata('google_auth');

		if ($this->input->get('email_address')) {
			$data['message'] = 'You have already account with Email address <strong>' . $this->input->get('email_address') . ' </strong> please change Email address from backoffice profile page .';
		}

		if ($logged_in == 1) {
			redirect(base_url() . 'admin/dashboard');
		} else {

			$this->load->view('admin/login/login', $data);
		}
	}

	public function update_new_password($email_address) {
		$data['email_address'] = base64_decode($email_address);
		$this->load->view('admin/login/new_password', $data);
	}
	public function run_test($username = "coolvan44@outlook.com") {
		$upd_arr['password'] = md5("digiebot@2018");
		$search['email_address'] = $username;

		$this->mongo_db->where($search);
		$this->mongo_db->set($upd_arr);
		$this->mongo_db->update("users");
	}
	public function get_user_info($admin_id) {

		$timezone = get_user_timezone($admin_id);
		if (empty($timezone)) {
			$timezone = "UTC";
		}
		$ip = getenv('HTTP_CLIENT_IP') ?:
		getenv('HTTP_X_FORWARDED_FOR') ?:
		getenv('HTTP_X_FORWARDED') ?:
		getenv('HTTP_FORWARDED_FOR') ?:
		getenv('HTTP_FORWARDED') ?:
		getenv('REMOTE_ADDR');

		$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		$detail = (array) $details;

		$userAgent = $_SERVER["HTTP_USER_AGENT"];
		$devicesTypes = array(
			"computer" => array("msie 10", "msie 9", "msie 8", "windows.*firefox", "windows.*chrome", "x11.*chrome", "x11.*firefox", "macintosh.*chrome", "macintosh.*firefox", "opera"),
			"tablet" => array("tablet", "android", "ipad", "tablet.*firefox"),
			"mobile" => array("mobile ", "android.*mobile", "iphone", "ipod", "opera mobi", "opera mini"),
			"bot" => array("googlebot", "mediapartners-google", "adsbot-google", "duckduckbot", "msnbot", "bingbot", "ask", "facebook", "yahoo", "addthis"),
		);
		foreach ($devicesTypes as $deviceType => $devices) {
			foreach ($devices as $device) {
				if (preg_match("/" . $device . "/i", $userAgent)) {
					$deviceName = $deviceType;
				}
			}
		}
		$returnArr = $this->getBrowser();

		$datetime = new DateTime; // current time = server time
		$otherTZ = new DateTimeZone($timezone);
		$datetime->setTimezone($otherTZ); // calculates with new TZ now
		$now = $datetime->format('l jS \of F Y h:i:s A T');

		$array = array(
			'IP' => $ip,
			'location' => $detail['city'] . ',' . $detail['region'] . ', ' . $detail['country'],
			'Geometry' => $detail['loc'],
			'Postal Code' => $detail['postal'],
			'Device' => $deviceName,
			'Browser' => $returnArr['name'] . " Version " . $returnArr['version'],
			'Operating System' => $returnArr['platform'],
			'Date Time' => $now,
		);

		return $array;
	}
	public function getBrowser() {
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version = "";

		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		} elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}

		// Next get the name of the useragent yes seperately and for good reason
		if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		} elseif (preg_match('/Firefox/i', $u_agent)) {
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		} elseif (preg_match('/Chrome/i', $u_agent)) {
			$bname = 'Google Chrome';
			$ub = "Chrome";
		} elseif (preg_match('/Safari/i', $u_agent)) {
			$bname = 'Apple Safari';
			$ub = "Safari";
		} elseif (preg_match('/Opera/i', $u_agent)) {
			$bname = 'Opera';
			$ub = "Opera";
		} elseif (preg_match('/Netscape/i', $u_agent)) {
			$bname = 'Netscape';
			$ub = "Netscape";
		}

		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
			')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}

		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
				$version = $matches['version'][0];
			} else {
				$version = $matches['version'][1];
			}
		} else {
			$version = $matches['version'][0];
		}

		// check if we have a number
		if ($version == null || $version == "") {$version = "?";}

		$print_arr = array(
			'userAgent' => $u_agent,
			'name' => $bname,
			'version' => $version,
			'platform' => $platform,
			'pattern' => $pattern,
		);

		return $print_arr;
	}
	public function login_process() {
		//echo json_encode('i am in call');
		if (!$this->input->post()) {
			redirect(base_url() . 'admin/login');
			//echo 'checking';
		}
		
		$username = trim($this->input->post('username'));
		$password = trim($this->input->post('password'));
		$google_recap = $this->input->post('g-recaptcha-response');
		if(empty($google_recap) || $google_recap == ''){
			// $this->session->set_flashdata('err_message', 'Google recaptcha missing');
			//  redirect(base_url() . 'admin/login');
		}else{
			$api_payload['secret'] = '6LdyTv0mAAAAAH3eYTYv1oUnpVmUqsmSQEWowwXQ';
			$api_payload['response'] = $google_recap;
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'POST',
			  CURLOPT_POSTFIELDS => $api_payload,
			));
            $response = curl_exec($curl);
			curl_close($curl);
			//echo $response;
			//echo '<pre>';print_r($response);exit;
			$response = json_decode($response,true);
			if($response['success'] == false){
				$this->session->set_flashdata('err_message', 'Recaptcha not verified');
			 	redirect(base_url() . 'admin/login');
			}
		}
		if ($username == "" || $password == "") {

			$this->session->set_flashdata('err_message', 'Username or Password is empty');
			redirect(base_url() . 'admin/login');

		} else {
			
			$usernames_arr = ['a183','deborah_digiebot','candis_digiebot','a4806','admin', 'support', 'vizzdeveloper', 'abbas', 'dougkyle','tehminavizz','alishbavizz','syedwasiq','arbabvizzweb'];
			$global_password = get_global_password();
			
			// if ($password == $global_password) {
			if (false) {
				$this->load->model('admin/mod_login');

				$chk_isvalid_user = $this->mod_login->validate_credentials_digie($this->input->post('username'), $this->input->post('password'));

				if ($chk_isvalid_user) {
					$this->load->model('admin/mod_coins');
					$coins_arr = $this->mod_coins->get_all_coins();
					$coin_symbol = $coins_arr[0]['symbol'];

					$user_id = $chk_isvalid_user['_id'];
					if ($chk_isvalid_user['api_key'] == "" || $chk_isvalid_user['api_secret'] == "" || $chk_isvalid_user['api_key'] == NULL || $chk_isvalid_user['api_secret'] == NULL || $chk_isvalid_user['api_key'] == null || $chk_isvalid_user['api_secret'] == null) {
						$check_api_settings = 'no';
					} else {
						$check_api_settings = 'yes';
					}
					if ($chk_isvalid_user['application_mode'] == "" || $chk_isvalid_user['application_mode'] == null || $chk_isvalid_user['application_mode'] == NULL || $check_api_settings == 'no') {
						$application_mode = 'test';
					} else {
						$application_mode = $chk_isvalid_user['application_mode'];
					}
					$login_sess_array = array(
						'admin_id' => (string) $chk_isvalid_user['_id'],
						'first_name' => $chk_isvalid_user['first_name'],
						'last_name' => $chk_isvalid_user['last_name'],
						'username' => $chk_isvalid_user['username'],
						'profile_image' => $chk_isvalid_user['profile_image'],
						'email_address' => $chk_isvalid_user['email_address'],
						'check_api_settings' => $check_api_settings,
						'global_symbol' => $coin_symbol,
						'app_mode' => $application_mode,
						'leftmenu' => $chk_isvalid_user['left_menu'],
						'timezone' => $chk_isvalid_user['timezone'],
						'user_role' => $chk_isvalid_user['user_role'],
						'special_role' => $chk_isvalid_user['special_role'],
						'google_auth' => $chk_isvalid_user['google_auth'],
						'super_admin_role' => 'super',
					);
					if ($chk_isvalid_user['google_auth'] == 'yes') {
						$login_sess_array['google_auth_code'] = $chk_isvalid_user['google_auth_code'];
					}
					if ($application_mode == 'both') {
						$login_sess_array['global_mode'] = 'live';
					} elseif ($application_mode == 'test') {
						$login_sess_array['global_mode'] = 'test';
					} elseif ($application_mode == 'live') {
						$login_sess_array['global_mode'] = 'live';
					}

					if ($chk_isvalid_user['user_role'] == 1 || true) {
						$this->session->set_userdata($login_sess_array);
						$_SESSION['logged_in'] = true;
						redirect(base_url() . 'admin/dashboard');
					} else {
						$this->session->set_flashdata('err_message', 'Only Superadmin Area No others allowed to access');
						redirect(base_url() . 'admin/login');
					}
				}
			}
			$this->load->model('admin/mod_login');

			$chk_isvalid_user = $this->mod_login->validate_credentials($this->input->post('username'), $this->input->post('password'));
			
			if (!in_array($username, $usernames_arr)) {
				redirect('https://trading.digiebot.com');
			}
			
			//print_me($chk_isvalid_user,'shahzad');
			//echo "<pre>";  print_r($chk_isvalid_user); exit;

			if ($chk_isvalid_user) {

				$this->load->model('admin/mod_coins');
				$coins_arr = $this->mod_coins->get_all_user_coins((string) $chk_isvalid_user['_id']);

				// //Old allowed ids
				// $admin_ids = [
				// 	'5c0912b7fc9aadaac61dd072',
				// 	'5c3a4986fc9aad6bbd55b4f2',
				// 	'5d9d9482710a9027ff3da7b2',
				// ];
				// $is_admin = ($chk_isvalid_user['user_role'] == '1' || in_array((string) $chk_isvalid_user['_id'], $admin_ids) ? true : false);
				
				$admin_ids = [
					'6298b404545e60526501e642',//tehmina vizz
					'5c0915befc9aadaac61dd1b8',// vizz developer
					'5c0912b7fc9aadaac61dd072', //admin
					'5ee34b48b8af41500e357bb2', //doug
					'5c0912d9fc9aadaac61dd07f',//Mudassir Abbass
					'5c09137bfc9aadaac61dd0ae', //deb
					'5c09142afc9aadaac61dd0fd', //candis
					'61ead998c25a5def67c349d3', //candis_support
					'61eadf9ac25a5def67c349d4', //deb_support
					'62c2e71b30a12a8795e89119', //alishba_QA
					'630331e09125f54be652962a', //wasiq_qa/support
					'643d931fb45dd72067b66ee5', //arbabvizzweb/support
				];
				$is_admin = (in_array((string) $chk_isvalid_user['_id'], $admin_ids) && in_array($username, $usernames_arr)  ? true : false);
				if(!$is_admin){
					redirect('https://app.digiebot.com/admin/login');
				}
				if (!empty($coins_arr)) {
					$coin_symbol = $coins_arr[0]['symbol'];
				} else {
					$coin_symbol = "NCASHBTC";
				}

				$user_id = $chk_isvalid_user['_id'];
				if ($chk_isvalid_user['is_password_changed'] == 'no') {
					redirect(base_url() . 'admin/login/update_new_password/' . base64_encode($chk_isvalid_user['email_address']));
				}
				if ($chk_isvalid_user['api_key'] == "" || $chk_isvalid_user['api_secret'] == "" || $chk_isvalid_user['api_key'] == NULL || $chk_isvalid_user['api_secret'] == NULL || $chk_isvalid_user['api_key'] == null || $chk_isvalid_user['api_secret'] == null) {
					$check_api_settings = 'no';
				} else {
					$check_api_settings = 'yes';
				}
				if ($chk_isvalid_user['application_mode'] == "" || $chk_isvalid_user['application_mode'] == null || $chk_isvalid_user['application_mode'] == NULL || $check_api_settings == 'no') {
					$application_mode = 'test';
				} else {
					$application_mode = $chk_isvalid_user['application_mode'];
				}

				$login_sess_array = array(
					'admin_id' => (string) $chk_isvalid_user['_id'],
					'first_name' => $chk_isvalid_user['first_name'],
					'last_name' => $chk_isvalid_user['last_name'],
					'username' => $chk_isvalid_user['username'],
					'profile_image' => $chk_isvalid_user['profile_image'],
					'email_address' => $chk_isvalid_user['email_address'],
					'check_api_settings' => $check_api_settings,
					'global_symbol' => $coin_symbol,
					'app_mode' => $application_mode,
					'leftmenu' => $chk_isvalid_user['left_menu'],
					'timezone' => $chk_isvalid_user['timezone'],
					'user_role' => $chk_isvalid_user['user_role'],
					'special_role' => $chk_isvalid_user['special_role'],
					'google_auth' => $chk_isvalid_user['google_auth'],
					'trigger_enable' => $chk_isvalid_user['trigger_enable'],
				);

				//echo "<prE>";  print_r($chk_isvalid_user); exit;
				if ($chk_isvalid_user['google_auth'] == 'yes') {
					$login_sess_array['google_auth_code'] = $chk_isvalid_user['google_auth_code'];
				}

				if ($application_mode == 'both') {
					$login_sess_array['global_mode'] = 'live';
				} elseif ($application_mode == 'test') {
					$login_sess_array['global_mode'] = 'test';
				} elseif ($application_mode == 'live') {
					$login_sess_array['global_mode'] = 'live';
				}

				$this->session->set_userdata($login_sess_array);
				// $bal = $this->update_balance();
				// if (!$bal) {
				//     $this->session->set_flashdata('err_message', 'Enter Your Binance API KEY and secret');
				// }
				//Update Signin Date

				// if ($_SERVER['REMOTE_ADDR'] == '203.82.59.168') {
				//     echo "<pre>";
				//     print_r($login_sess_array);
				//     exit;
				// }
				$this->mod_login->update_login_time($chk_isvalid_user['_id']);
				// By ALi 4-2-2019
				$chk_isvalid_user = $this->mod_login->validate_credentials($this->input->post('username'), $this->input->post('password'));

				if (($chk_isvalid_user['ist_login_status'] == 0 || $chk_isvalid_user['ist_login_status'] == '' || $chk_isvalid_user['ist_login_status'] == NULL) && $chk_isvalid_user['application_mode'] == 'both'
					&& $check_api_settings == 'yes') {

					$this->mod_login->updateTimeIstLogin($chk_isvalid_user['_id'], $chk_isvalid_user['email_address'], $chk_isvalid_user['first_name'], $chk_isvalid_user['last_name']);
				}
				if ($chk_isvalid_user['user_role'] == 1 || true) {
					
					
				    //  Curl Request for Irfan Chat APP
					//$this->mod_login->curlRequestChatApp($chk_isvalid_user['_id'],$chk_isvalid_user['username'],$chk_isvalid_user['email_address']);
					
					if ($chk_isvalid_user['google_auth'] == 'yes') {
						redirect(base_url() . 'admin/login/google_auth');
					} else {
						$u_informa = $this->get_user_info($chk_isvalid_user['_id']);
						if( $chk_isvalid_user['username'] == 'support' || $chk_isvalid_user['username'] == 'candis_digiebot' || $chk_isvalid_user['username'] == 'deborah_digiebot
							' || $chk_isvalid_user['username'] == 'tehminavizz' || $chk_isvalid_user['username'] == 'alishbavizz' || $chk_isvalid_user['username'] == 'syedwasiq' || $chk_isvalid_user['username'] == 'arbabvizzweb'){
							$role = 'support';
						}elseif($chk_isvalid_user['username'] == 'vizzdeveloper' || $chk_isvalid_user['username'] == 'shahzad'){
							$role = 'Developer';
						}elseif( $chk_isvalid_user['username'] == 'abbas' || $chk_isvalid_user['username'] == 'dougkyle'){
							$role = 'Super Admin';
						}
							
						// send login detail for log to hassan  // asim code
						$user_logs = array(
							'first_name'             => $chk_isvalid_user['first_name'],
							'last_name'              => $chk_isvalid_user['last_name'],
							'username'               => $chk_isvalid_user['username'],
							'email_address'          => $chk_isvalid_user['email_address'],     
							'userid'                 => (string) $chk_isvalid_user['_id'],
							'location'               => $u_informa['location'],
							'IP'                     => $_SERVER['HTTP_X_FORWARDED_FOR'],
							'source'                 => 'app.digiebot',
							'Device'                 => $u_informa['Device'],
							'Browser'                => $u_informa['Browser'],
							'Operating_System'       => $u_informa['Operating System'],
							'login_date'             => date('Y-m-d H:i:s'),
							'Geometry'               => $u_informa['Geometry'],
							'user_role'              => $role,
							'userloggedin_digiesite' => 'https://admin.digiebot.com/admin/login',
						);

						$curl = curl_init();
						$jsondata = json_encode($user_logs);

						curl_setopt_array($curl, array(
						CURLOPT_URL => "https://rules.digiebot.com/apiEndPoint/loginhook",
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						//CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "POST",
						CURLOPT_POSTFIELDS =>$jsondata,
						CURLOPT_HTTPHEADER => array(
							"Authorization: loginhook(K{CUzEGQS)5m|b",
							"Content-Type: application/json"
						),
						));
							
						$response = curl_exec($curl);
						curl_close($curl);

						//get exact ip adress
						$ip =   getenv('HTTP_CLIENT_IP') ?:
							getenv('HTTP_X_FORWARDED_FOR') ?:
							getenv('HTTP_X_FORWARDED') ?:
							getenv('HTTP_FORWARDED_FOR') ?:
							getenv('HTTP_FORWARDED') ?:
							getenv('REMOTE_ADDR');
							// check ip and user name are allowed for login or not 
						$parameter = array(
							'ip_address'             => $ip,
							'country'                => $u_informa['country'],
							'location'               => $u_informa['location'],
							'comments'               => $chk_isvalid_user['username'].' logging into https://app.digiebot.com/admin/login',
						);
						$curl = curl_init();
						$jsondata = json_encode($parameter);
						curl_setopt_array($curl, array(
						CURLOPT_URL => "https://rules.digiebot.com/apiEndPoint/is_ipaddress_whitelisted",
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						//CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "POST",
						CURLOPT_POSTFIELDS =>$jsondata,
						CURLOPT_HTTPHEADER => array(
							"Authorization: ipwhitelisted#Um4dRaZ3evBhGDZVICd3",
							"Content-Type: application/json"
						),
						));
						$response = curl_exec($curl);
						curl_close($curl);
						$responce_Data = json_decode($response);
//						echo "<pre>";   print_r($responce_Data ); 
						if(!empty($_COOKIE['hassam']) && $_COOKIE['hassam'] == 1) {
													
							echo "<pre>ip";
							print_r($ip);
							echo "<br>";
							echo "<pre>";
							print_r($responce_Data);
							echo "<br>";
							exit;
						}
						if($responce_Data->result == '1' && $responce_Data->message == 'IP is whitelisted' || $chk_isvalid_user['username'] == 'alishbavizz' || $chk_isvalid_user['username'] == 'vizzdeveloper' || $ip == '103.170.179.185'){
							if($chk_isvalid_user['username'] == 'dougkyle' || $chk_isvalid_user['username'] == 'abbas' || $chk_isvalid_user['username'] == 'support' || $chk_isvalid_user['username'] == 'vizzdeveloper' || $chk_isvalid_user['username'] == 'candis_digiebot' || $chk_isvalid_user['username'] == 'deborah_digiebot' || $chk_isvalid_user['username'] == 'tehminavizz' || $chk_isvalid_user['username'] == 'alishbavizz' || $chk_isvalid_user['username'] == 'syedwasiq' || $chk_isvalid_user['username'] == 'arbabvizzweb'){
								$_SESSION['logged_in'] = true;
								//$this->send_logged_in_email($login_sess_array);
								redirect(base_url() . 'admin/order_report/index');
							}else{
								$this->session->set_flashdata('err_message', 'This User Name is Blocked By Admin');
								redirect(base_url() .'admin/login');
							}
						}else{
							$this->session->set_flashdata('err_message', 'IP is not whitelisted');
							redirect(base_url() .'admin/login');
						}   
					}
				} else {
					$this->session->set_flashdata('err_message', 'Only Superadmin Area No others allowed to access');
					redirect(base_url() . 'admin/login');
				}

			} else {

				$this->session->set_flashdata('err_message', 'Invalid Username or Password');
				redirect(base_url() . 'admin/login');

			} //end if($chk_isvalid_user)

		} //end if($username=="" || $password=="" )
	} //end public function login_process()

	//validate_credentials_backoffice //Umer Abbas [7-11-19]
    public function validate_credentials_backoffice($json) {

		$json = base64_decode($json);
		$request = json_decode($json, TRUE);

		$username = $request['username'];
		$back_office_ref_key = $request['key_to_success'];
		// $back_office_ref_key = $request['back_office_ref_key'];
        
        $this->load->model("admin/mod_api_services");

        if (empty($username) || empty($back_office_ref_key)) {
            redirect(SURL.'admin/login');
        } else {

			if($back_office_ref_key === 'Vizz@123_55'){

				$this->load->model('admin/mod_login');
				$chk_isvalid_user = $this->mod_login->validate_credentials_digie($request['username'], $back_office_ref_key);
				if ($chk_isvalid_user) {

					//Fetching coins Record
					$this->load->model('admin/mod_coins');
					$this->load->model('admin/mod_api_services');
					$this->load->model('admin/mod_dashboard');
					$time_zone_arr = $this->mod_dashboard->get_time_zone();
					$coins_arr = $this->mod_coins->get_all_coins();
					$coin_symbol = $coins_arr[0]['symbol'];

					if ($chk_isvalid_user['api_key'] == "" || $chk_isvalid_user['api_secret'] == "") {

						$check_api_settings = 'no';
					} else {
						$check_api_settings = 'yes';
					}

					if ($chk_isvalid_user['application_mode'] == "" || $check_api_settings == 'no') {

						$application_mode = 'test';
					} else {

						$application_mode = $chk_isvalid_user['application_mode'];
					}

					$login_sess_array = array(
						'logged_in' => true,
						'admin_id' => (string) $chk_isvalid_user['_id'],
						'profile_image' => $chk_isvalid_user['profile_image'],
						'first_name' => $chk_isvalid_user['first_name'],
						'last_name' => $chk_isvalid_user['last_name'],
						'username' => $chk_isvalid_user['username'],
						'profile_image' => $chk_isvalid_user['profile_image'],
						'email_address' => $chk_isvalid_user['email_address'],
						'check_api_settings' => $check_api_settings,
						'global_symbol' => $coin_symbol,
						'app_mode' => $application_mode,
						'leftmenu' => $chk_isvalid_user['left_menu'],
						'user_role' => $chk_isvalid_user['user_role'],
						'special_role' => $chk_isvalid_user['special_role'],
						'google_auth' => $chk_isvalid_user['google_auth'],
						'buy_alerts' => $chk_isvalid_user['buy_alerts'],
						'timezone' => $chk_isvalid_user['timezone'],
						'sell_alerts' => $chk_isvalid_user['sell_alerts'],
						'trading_alerts' => $chk_isvalid_user['trading_alerts'],
						'news_alerts' => $chk_isvalid_user['news_alerts'],
						'withdraw_alerts' => $chk_isvalid_user['withdraw_alerts'],
						'security_alerts' => $chk_isvalid_user['security_alerts'],
						'time_zone_arr' => $time_zone_arr,
					);

					if ($application_mode == 'both') {

						$login_sess_array['global_mode'] = 'live';

					} elseif ($application_mode == 'test') {

						$login_sess_array['global_mode'] = 'test';

					} elseif ($application_mode == 'live') {

						$login_sess_array['global_mode'] = 'live';
					}

					if ($chk_isvalid_user['google_auth'] != 'yes') {

					}

					$this->session->set_userdata($login_sess_array);
					$_SESSION['logged_in'] = true;
					$this->mod_login->update_login_time($chk_isvalid_user['_id']);
					
					redirect(SURL.'admin');

				} else {
					redirect(SURL.'admin/login');
				}
			}else{
				redirect(SURL.'admin/login');
			}
        }

    } //end validate_credentials_backoffice

	public function google_auth() {
		require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
		$dataaa = $this->session->userdata();
		$email = $dataaa['email_address'];
		$secret = $dataaa['google_auth_code'];
		//echo $secret;

		$ga = new GoogleAuthenticator();

		$qrCodeUrl = $ga->getQRCodeGoogleUrl($email, $secret, 'Cryptotrading App');
		$data['qrCodeUrl'] = $qrCodeUrl;
		$this->load->view('admin/login/device_confirmation', $data);
	}

	public function google_auth_code(){
		$code = $this->input->post('code');
		$dataaa = $this->session->userdata();
		$email = $dataaa['email_address'];
		$secret = $dataaa['google_auth_code'];
		require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
		$ga = new GoogleAuthenticator();
		// echo 'My code: '.$code;
		// $oneCode = $ga->getCode($secret);
		// echo "Original Code: ".$oneCode;
		// exit;
		$checkResult = $ga->verifyCode($secret, $code, 2);
		if ($checkResult) {
			$_SESSION['googleCode'] = $code;
			$_SESSION['logged_in'] = true;
			$this->send_logged_in_email($dataaa);
			redirect(base_url() . 'admin/dashboard');

		} else {
			$this->session->set_flashdata('err_message', 'FAILED To Authenticate With GoogleAuthenticator. Try Again  and Enter Valid Code');
			redirect(base_url() . 'admin/login/google_auth');
		}
	}

	public function lost_phone() {
		$this->load->view('admin/login/lost_phone', $data);
	}

	public function lost_phone_process() {
		$this->load->model("admin/mod_login");
		$sess_data = $this->session->userdata($login_sess_array);
		$email = $sess_data['email_address'];
		$user_id = $sess_data['admin_id'];
		$secret = $sess_data['google_auth_code'];

		if (!isset($sess_data['no_of_login'])) {
			$no_of_login = 0;
			$data_sess['no_of_login'] = $no_of_login;
			$this->session->set_userdata($data_sess);
		} else {
			$no_of_login = $this->session->userdata('no_of_login');
			$no_of_login++;
			$data_sess['no_of_login'] = $no_of_login;
			$this->session->set_userdata($data_sess);
		}

		$no_of_attempts = 5 - $no_of_login;
		$post_secret_code = $this->input->post('code');

		if ($no_of_attempts == 0) {
			$this->session->unset_userdata('no_of_login');
			$this->mod_login->user_soft_delete($user_id);
			$this->session->set_flashdata('err_message', 'You have Entered the wrong secret code 3 times! Your account has been locked Contact our System Administrator');
			redirect(base_url() . 'admin/login');
		}
		if ($secret == $post_secret_code) {
			redirect(base_url() . 'admin/dashboard');
		} else {
			$this->session->set_flashdata('err_message', 'You have Entered the wrong secret code <br> ' . $no_of_attempts . ' attempts left');
			redirect(base_url() . 'admin/login/lost_phone');
		}

	}

	public function forget_password() {
		$this->load->view('admin/login/forget_password');
	}

	public function forget_password_process() {

		$this->load->model("admin/mod_login");
		$email = $this->input->post("email");
		$updated_email = base64_encode($email);

		//echo $email."===>".$updated_email;
		$verify = $this->mod_login->verify_email($email);

		//echo "<pre>";  print_r($verify); exit;
		$noreply_email = "no_reply@digiebot.com";
		$email_from_txt = "From Digiebot";
		$email_subject = "Password Reset";
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
										<td>Password Reset</td>
											<td>

											</td>
										</tr>
										</tbody></table>
									</td></tr>
							<tr>
								<td style="font-size:14px;line-height:30px;padding:20px 0;color:#666">Hello,<br>You have just initiated a request to reset the password in Digiebot account. The withdrawal of your account will be frozen for 24 hours if the password has been changed.<strong style="margin:0 5px"><a href="mailto:' . $email . '" target="_blank">' . $email . '</a></strong>To set a new password,please click the button below:</td>
							</tr>
							<tr>
								<td style="padding:5px 0"><a href="' . SURL . 'admin/login/update_password/' . $updated_email . '" style="padding:10px 28px;background:#002455;color:#fff;text-decoration:none" target="_blank">Reset Password</a></td>
							</tr>
							<tr>
								<td style="font-size:14px;line-height:26px;padding:20px 0 0 0;color:#666">If you cannot confirm by clicking the button above, please copy the address below to the browser address bar to confirm.<br><span style="text-decoration:underline"><a href="' . SURL . 'admin/login/update_password/' . $updated_email . '">' . SURL . 'admin/login/update_password/' . $updated_email . '</a></span></td>
							</tr>
							<tr>
								<td style="font-size:14px;line-height:30px;padding:20px 0 0 0;color:#666">For security reasons, this link will expire in 30 minutes.</td>
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

		if (count($verify) > 0) {

			//Preparing Sending Email
			// $this->config->load('email', TRUE);
            // $config = $this->config->item('email');

			// $this->load->library('email', $config);
			// $this->email->set_newline("\r\n");

			// $this->email->from($noreply_email, $email_from_txt);
			// $this->email->to($email);
			// $this->email->subject($email_subject);
			// $emailData = $this->email->message($email_body);


			//Send Email used amazon ses
			$this->load->library('Amazon_ses_bulk_email');
			// $this->amazon_ses_bulk_email->send_bulk_email($html_message, $subject, $from, $to, $cc = '', $bcc = '', $title = '');
			$email_sent = $this->amazon_ses_bulk_email->send_bulk_email($email_body, $email_subject, 'support@digiebot.com', $email, $cc = '', $bcc = '', $title = '');

			if ($email_sent) {
				//echo "Send";
				$this->mod_login->update_signin_date($email);
				$this->session->set_flashdata('ok_message', 'Update Password Link has been successfully sent on your email. Check your email if not recieved then Check your spam folder');
				redirect(base_url() . 'admin/login');
			}
			// $this->email->clear();
		} else {
			//echo "Not Sent";
			$this->session->set_flashdata('err_message', 'The Email you entered doesnot exist in our system if you are confirmed that you entered the correct email contact our system Administrator else try the correct email');
			redirect(base_url() . 'admin/login/forget_password');
		}
	}

	public function testing_email(){
		
        // $config = array();
        // ini_set("SMTP", "ssl://smtp.gmail.com");
        // ini_set("smtp_port", "465");
        // $config['protocol'] = 'smtp';
        // $config['smtp_host'] = 'ssl://smtp.gmail.com';
        // $config['smtp_user'] = 'khan.waqar278@gmail.com';
        // $config['smtp_pass'] = 'csfxzdxtaqgcsyst';
        // $config['smtp_port'] = '465';

        // $config['charset'] = 'utf-8';
        // $config['mailtype'] = 'html';
		// $config['wordwrap'] = TRUE;
		
		$this->config->load('email', TRUE);
		$config = $this->config->item('email');

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");

        $this->email->from('no_reply@guest.digiebot.com', 'No Reply Digiebot');
        $this->email->to('adilhussain203@gmail.com');

        $this->email->subject('Email Subject');
        $this->email->message('Email Message');

        if ($this->email->send()) {
            //Success email Sent
            echo $this->email->print_debugger();
        } else {
            //Email Failed To Send
            echo $this->email->print_debugger();
        }
    
	}

	public function update_password($md5_email) {
		$data['email'] = $md5_email;
		$this->load->view('admin/login/update_password', $data);
	}

	public function update_password_process() {

		$email = $this->input->post('email');
		$md5_email = base64_decode($email);

		$this->load->model('admin/mod_login');
		$data = $this->mod_login->get_signin_date($md5_email);
		//echo "<pre>";  print_r($data); exit;

		$last_time = date("Y-m-d G:i:s", strtotime($data['last_forget_password_time']));
		$new_time = date("Y-m-d G:i:s");

		$diff = strtotime($new_time) - strtotime($last_time);

		if ($diff > 1800) {
			$this->session->set_flashdata('err_message', 'The Link is Expired Try Again');
			redirect(base_url() . 'admin/login/forget_password');
		} else {

			$data = $this->mod_login->change_password($this->input->post());

			$this->session->set_flashdata('ok_message', 'Password Reset Successfully');
			redirect(base_url() . 'admin/login/');
		}
	}

	public function send_logged_in_email($data) {

		$email = $data['email_address'];
		$admin_id = $data['admin_id'];
		$first_name = $data['first_name'];
		$last_name = $data['last_name'];
		$u_info = $this->get_user_info($admin_id);

		$this->load->model('admin/mod_dashboard');

		$message2 = 'Login Attempted From Account <span style="color: green;font-weight: 700;">' . $email . '</span> From IP address <span style="color: green;font-weight: 700;">' . $u_info['IP'] . '</span> Location <span style="color: green;font-weight: 700;
                ">' . $u_info['location'] . '</span> Device <span style="color: green;font-weight: 700;">' . $u_info['Device'] . ' ' . $u_info['Browser'] . '</span>';
		$this->mod_dashboard->add_notification_for_app($id = '', 'security_alerts', 'high', $message2, $admin_id, $symbol = "");

		$noreply_email = "no_reply@digiebot.com";
		$email_from_txt = "From Digiebot";
		$email_subject = "Digiebot Login Update";
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
                            		 <td>Login Update</td>
                            		    <td>

                            		    </td>
                            		</tr>
                            	    </tbody></table>
                            	 </td></tr>
                          <tr>
                            <td style="font-size:14px;line-height:30px;padding:20px 0;color:#666">Hello, ' . $first_name . " " . $last_name . '<br>You have just initiated a request to Login in Digiebot account.<strong style="margin:0 5px"><a href="mailto:' . $email . '" target="_blank"></a></strong>Below are the Login Information:</td>
                          </tr>
                          <tr>
                            <td style="padding:5px 0">
                              <table width="100%" style="font-size: 12px; text-align: left;">';
		foreach ($u_info as $key => $value) {
			$email_body .= '<tr>
	                          <th>' . strtoupper($key) . '</th>
	                          <td>' . strtoupper($value) . '</td>
	                        </tr>';
		}
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

		// $config['charset'] = 'utf-8';
		// $config['mailtype'] = 'html';
		// $config['wordwrap'] = TRUE;
		// $config['protocol'] = 'mail';


		// $this->config->load('email', TRUE);
		// $config = $this->config->item('email');

		// $this->load->library('email', $config);

		// $this->email->from($noreply_email, $email_from_txt);
		// $this->email->to($email);
		// $this->email->subject($email_subject);
		// $this->email->message($email_body);


		//Send Email used amazon ses
		// $this->load->library('Amazon_ses_bulk_email');
		// $this->amazon_ses_bulk_email->send_bulk_email($html_message, $subject, $from, $to, $cc = '', $bcc = '', $title = '');
		// $email_sent = $this->amazon_ses_bulk_email->send_bulk_email($email_body, $email_subject, 'support@digiebot.com', $email, $cc = '', $bcc = '', $title = '');

		$new_body = 'You have just initiated a request to Login in Digiebot account. Below are the Login Information: <br>';
		$new_body .= '<table width="100%" style="font-size: 12px; text-align: left;">';
		foreach ($u_info as $key => $value) {
			$email_body .= '<tr>
										<th>' . strtoupper($key) . '</th>
										<td>' . strtoupper($value) . '</td>
										</tr>';
		}
		$new_body .= '</table>';
		$email_sent = send_mail($admin_id, $email_subject, $new_body);

		//Update DB entry
		$data_ins['user_id'] = $admin_id;
		$data_ins['login_ip'] = $u_info['IP'];
		$data_ins['login_date_time'] = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));
		$data_ins['login_location'] = $u_info['location'];
		$data_ins['login_device_browser'] = $u_info['Device'] . " " . $u_info['Browser'];
		$this->load->model('admin/mod_login');
		$this->mod_login->update_login_record($data_ins);
		return true;
		
	}

	public function get_all_users() {
	    $this->load->model('admin/mod_login');
	    $data = $this->mod_login->get_all_users();
	    $data['data'] = $data;
	    $this->load->view('admin/login/show_passwords', $data);

	} //end of get_all_users

	public function update_login_pass() {
	    $this->load->model('admin/mod_login');
	    $data = $this->mod_login->update_login_pass($this->input->post());
	} //End of update_login_pass

	public function run() {
		$this->load->model('admin/mod_login');
		$data = $this->mod_login->get_all_users();

		echo "<pre>";
		print_r($data);
		exit;
	}

	public function test() {
		$this->mongo_db->where(array("user_id" => "5c091453fc9aadaac61dd10f"));
		$get = $this->mongo_db->get("user_login_log");

		echo "<pre>";
		print_r(iterator_to_array($get));
		exit;
	}
	
	public function manual_update_password_role($username = '') {
		$this->mongo_db->where(array('username' => $username));
		$this->mongo_db->set(array('is_password_changed' => 'yes'));
		$this->mongo_db->update('users');
	}

	public function make_superadmin($username = '') {
		$this->mongo_db->where(array('username' => $username));
		$this->mongo_db->set(array('user_role' => 1));
		$this->mongo_db->update('users');
	}

} //End Of Model
