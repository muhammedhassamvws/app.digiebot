<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct() {

        parent::__construct();

        //load main template
        $this->stencil->layout('admin_layout');

        //load required slices
        $this->stencil->slice('admin_header_script');
        $this->stencil->slice('admin_header');
        $this->stencil->slice('admin_left_sidebar');
        $this->stencil->slice('admin_footer_script');

        // Load Library Goes here
        // $this->load->library('binance_api');

        // $_SESSION['logged_in'] = false;

        // Load Modal
        $this->load->model('admin/mod_login');
        $this->load->model('admin/mod_coins');
        $this->load->model('admin/mod_settings');
        $this->load->model('admin/mod_users');

    }

    public function index() {
        //Login Check
        $this->mod_login->verify_is_admin_login();
        $id = $this->session->userdata('admin_id');
        //Fetching users Record
        $settings_arr = $this->mod_settings->get_settings_by_id($id);
        $data['settings_arr'] = $settings_arr;
        $data['admin_id'] = $id;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/settings/add_settings', $data);

    } //End index

    public function updateKeySettingsProcess() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        $api_key_tr = $this->input->post('api_key_tr');
        $api_secret_tr = $this->input->post('api_secret_tr');
        /*
        $api_key_tr     =  '0012323rmrpH8YDuTAujVZkSrUEr2NmtOkQTVMXRRg86d4InQBIQxiIlOKWTJ2uSPeT6TQb00';
        $api_secret_tr  =  '00123233Jb7YjdfpOqZqMaKc9QbpOs6tjYrXWMekvlcWvs9QNu32n3jbOgVAGkM8ulY5LkgQ00';

        $checkBinanceApi = $this->binance_api->check_master_api($api_key_tr,$api_secret_tr);
        $checkBinanceApi1 = $this->binance_api->checkExchangeInfo($api_key_tr,$api_secret_tr);
        //$Info = $checkBinanceApi->accountStatus();
        echo "<pre>";  print_r($checkBinanceApi1);   exit;
         */
        $json_array = array();
        if ($api_key_tr == '') {
            $json_array['success'] = true;
            $json_array['message'] = 'Master Api key field cannot be empty .';
            echo json_encode($json_array);
            exit;
        }
        if ($api_secret_tr == '') {
            $json_array['success'] = false;
            $json_array['message'] = 'Master Api Secret field cannot be empty .';
            echo json_encode($json_array);
            exit;
        }
        $updateKeySettings = $this->mod_settings->updateKeySettingsProcess($this->input->post());

        if ($updateKeySettings) {
            $json_array['success'] = true;
            $json_array['message'] = 'Api key credentials successfully updated.';
            echo json_encode($json_array);
            exit;
        } //$updateKeySettings
    } //end updateKeySettingsProcess

    public function add_settings_process() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Adding add_user
        $user_id = $this->mod_settings->add_settings($this->input->post());

        if ($user_id) {

            $this->session->set_flashdata('ok_message', 'Settings added successfully.');
            redirect(base_url() . 'admin/settings/');

        } else {

            $this->session->set_flashdata('err_message', 'Settings cannot added. Something went wrong, please try again.');
            redirect(base_url() . 'admin/settings/');

        } //end if

    } //end add_settings_process

    public function edit_settings($setting_id) {
        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Fetching user Record
        $settings_arr = $this->mod_settings->get_setings($setting_id);
        $data['settings_arr'] = $settings_arr;
        $data['setting_id'] = $setting_id;

        $this->stencil->paint('admin/settings/edit_settings', $data);

    } //End edit_settings

    public function edit_settings_process() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //edit_user
        $sett_id = $this->mod_settings->edit_settings($this->input->post());

        if ($sett_id) {

            redirect(base_url() . 'admin/settings');

        } else {

            redirect(base_url() . 'admin/settings/');

        } //end if

    } //end edit_settings_process

    public function enable_google_auth() {
        // $this->mod_login->verify_is_admin_login();
        $data['request'] = 1;
        $data['admin_id'] = $this->session->userdata('admin_id');
        
        $this->session->unset_userdata('admin_id');
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('no_of_login');
        $this->session->sess_destroy();

        $this->stencil->paint('admin/settings/google_auth_enable', $data);
    }

    public function get_the_secret_code() {
        require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
        $ga = new GoogleAuthenticator();
        $secret = $ga->createSecret();

        $respone = '<div class="row">
						<div class="col-md-12">
							<div class="image reddd"  style="float:right;">
			                    <img src="' . IMG . 'g_a/n16.png" width="100%">
			                    <span class="text reddtext" style="font-weight: bold;">' . $secret . '</span>
			                </div>
						</div>
					</div>
                  <div class="control-group col-md-12">
                    <label class="control control-checkbox">
                       <span> I have Written The Code in safe Place Continue now </span>
                        <input type="checkbox" name = "secret" id="check_secret" value="' . $secret . '" />
                        <div class="control_indicator"></div>
                    </label>
                  </div>';
        echo $respone;
        exit;
    }
    public function add_google_auth() {
        // $this->mod_login->verify_is_admin_login();

        $is_enable = $this->input->post('auth');
        $admin_id = $this->input->post('admin_id');
        $secret = $this->input->post('secret');
        $this->mod_settings->update_user_auth($admin_id, $is_enable, $secret);
        if ($is_enable == 'yes') {

            redirect(base_url() . 'admin/settings/enable_google_auth2');
        } else {
            redirect(base_url() . 'admin/settings/enable_google_auth');
        }        
    }

    public function enable_google_auth2() {
        // $this->mod_login->verify_is_admin_login();

        $secret = $this->session->userdata('google_auth_code');
        $email = $this->session->userdata('email_address');
        $data['admin_id'] = $this->session->userdata('admin_id');
        require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
        $ga = new GoogleAuthenticator();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($email, $secret, 'app.digiebot.com');
        $data['qrCodeUrl'] = $qrCodeUrl;
        $data['request'] = 2;
        print_r($data);
        $this->stencil->paint('admin/settings/google_auth_enable', $data);
    }

    public function verify_code() {
        $this->mod_login->verify_is_admin_login();
        $code = $this->input->post('code');
        $secret = $this->session->userdata('google_auth_code');
        require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
        $ga = new GoogleAuthenticator();
        $checkResult = $ga->verifyCode($secret, $code, 2); // 2 = 2*30sec clock tolerance
        if ($checkResult) {
            $_SESSION['googleCode'] = $code;
            redirect(base_url() . 'admin/settings/enable_google_auth');
        } else {
            echo "Failed";
            exit;
        }
    }

    public function password_change() {
        $data['admin_id'] = $this->session->userdata('admin_id');
        $this->stencil->paint('admin/settings/change_password', $data);

    }

    public function change_password() {
        $this->mod_login->verify_is_admin_login();

        //echo "<pre>";   print_r($this->input->post()); exit;

        if ($_POST['code']) {
            $code = $this->input->post('code');
            $secret = $this->session->userdata('google_auth_code');
            require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
            $ga = new GoogleAuthenticator();
            $checkResult = $ga->verifyCode($secret, $code, 2); // 2 = 2*30sec clock tolerance
            if ($checkResult) {

                $data1 = $this->mod_settings->change_password($this->input->post());
            } else {
                $this->session->set_flashdata('err_message', 'Some Issue Occured in auth.');
                redirect(base_url() . 'admin/settings/password_change');
            }
        } else {
            $data1 = $this->mod_settings->change_password($this->input->post());
        }

        if ($data1) {
            $this->session->set_flashdata('ok_message', 'Password Changed Successfully.');
            redirect(base_url() . 'admin/settings/password_change');
        } else {
            $this->session->set_flashdata('err_message', 'Some Issue Occured.');
            redirect(base_url() . 'admin/settings/password_change');
        }
    }

    public function get_global_trigger_setting_ajax() {
        $triggers_type = $this->input->post('triggers_type');
        $order_mode = $this->input->post('order_mode');
        $coin = $this->input->post('coin');
        $trigger_level = $this->input->post('trigger_level');

  

  
        $where['triggers_type'] = $triggers_type;
        $where['order_mode'] = $order_mode;
        $where['coin'] = $coin;

        if (($triggers_type == 'barrier_percentile_trigger' || $triggers_type == 'box_trigger_3' || $triggers_type == 'market_trend_trigger') && ($trigger_level != '')) {
            $where['trigger_level'] = $trigger_level;
        }


        $this->mongo_db->where($where);
        $response_obj = $this->mongo_db->get('trigger_global_setting');
        $response_arr = iterator_to_array($response_obj);
        $row_data = array();
        if (!empty($response_arr)) {
            $row_data = (array) $response_arr[0];
        }
        echo json_encode($row_data);
        exit();

    } //End  get_global_trigger_setting_ajax


    public function get_trigger_setting_by_id() {
        ;
        $setting_id = $this->input->post('setting_id');
        $this->mongo_db->where(array('_id' => $setting_id));
        $response_obj = $this->mongo_db->get('barrier_trigger_setting_changed_log');
        $response_arr = iterator_to_array($response_obj);
        $row_data = array();
        if (!empty($response_arr)) {
            $row_data = (array) $response_arr[0];
        }
        echo json_encode($row_data);
        exit();

    } //End  get_trigger_setting_by_id

    public function validate_password() {
        $pass = $this->input->post('password');

        $this->mongo_db->where(array('_id' => $this->session->userdata('admin_id'), 'password' => md5($pass)));
        $get_arr = $this->mongo_db->get('users');
        $user = iterator_to_array($get_arr);

        if (count($user) > 0) {
            echo "Password Accepted! Validated";
        } else {
            echo "Sorry! Your Password is not matched! Please Enter the valid Password";
        }
        exit;
    }

    public function make_password(){
 
        $chars = 6;
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $password = substr(str_shuffle($data), 0, $chars);

        $ins_arr = array("type" => "system","updated_system_password" => $password, "subtype" => 'superadmin_password');
        $filter_arr['subtype'] = "superadmin_password";
        $db = $this->mongo_db->customQuery();

        $upd['$set'] = $ins_arr;
        $upsert['upsert'] = true;
        $ypd = $db->superadmin_settings->updateOne($filter_arr,$upd,$upsert);
        $name = "make_global_password";
        $duration = "1h";
        $summary = "Cronjob to make Global Password";
        save_cronjob_description($name, $duration, $summary);
        echo "<pre>";
        print_r($ypd);


        $tokenData = [

            'token' => $password, 
        ];
        $curl = curl_init();
        $jsondata = json_encode($tokenData);
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://rules.digiebot.com/apiEndPoint/updateTokenKey",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>$jsondata,
          CURLOPT_HTTPHEADER => array(
            "Authorization: adminToken_>O!k-HM_008",
            "Content-Type: application/json"
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $responce_Data = json_decode($response);


        // die('code not valid');
        // $this->load->library('Mongo_db_2');
        // $db_2 = $this->mongo_db_2->customQuery();

        // $upd['$set'] = $ins_arr;
        // $upsert['upsert'] = true;
        // $pass = $db_2->superadmin_settings->updateOne($filter_arr,$upd,$upsert);
        // echo "<pre>";
        // print_r($pass);
        exit;
    }

    public function get_password(){
 
        $parameter = array(
            'ip_address'=> $_SERVER['HTTP_X_FORWARDED_FOR'],
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
        
        if($responce_Data->result == '1' && $responce_Data->message == 'IP is whitelisted'){
            $this->mod_login->verify_is_admin_login();
            $filter_arr['subtype'] = "superadmin_password";
            $this->mongo_db->where($filter_arr);
            $get = $this->mongo_db->get("superadmin_settings");
            $arr = iterator_to_array($get);
            $passs = $arr[0]['updated_system_password'];
            $data['password'] = $passs;
            $this->stencil->paint('admin/settings/device',$data);
        }else{
            redirect(base_url() . 'admin/dashboard-support');
        }
    }

} //%%%%%%%%%%%%%%%%%%%% End of Controller %%%%%%%%%%%%%%%%%%%
