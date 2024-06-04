<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends CI_Controller {

    public function __construct() {

        parent::__construct();

        // Load Modal
        $this->load->model('admin/mod_users');
        $this->load->model('admin/mod_dashboard');
        $this->load->model('admin/Mod_jwt');
    }

    public function index() {
        $time_zone_arr = $this->mod_dashboard->get_time_zone();
        $data['time_zone_arr'] = $time_zone_arr;
        $this->load->view('admin/signup/signup', $data);
    }

    public function run_test($username = "coolvan44@outlook.com") {
        $upd_arr['password'] = md5("digiebot@2018");
        $search['email_address'] = $username;

        $this->mongo_db->where($search);
        $this->mongo_db->set($upd_arr);
        $this->mongo_db->update("users");
    }

    public function signup_process() {

        //Check User
        $security_code = $this->input->post('security_code');

        if ($security_code != 'crypto_trading@2019') {

            $data_arr['form-data'] = $this->input->post();
            $this->session->set_userdata($data_arr);

            $this->session->set_flashdata('err_message', 'Security Code is wrong, please try again.');
            redirect(base_url() . 'admin/signup');

        } else {

            $this->session->unset_userdata('form-data');

            //User Signup
            $user_signup = $this->mod_users->user_signup($this->input->post());

            if ($user_signup) {

                $this->session->set_flashdata('ok_message', 'Your account has been created successfully. pleaes login below');
                redirect(base_url() . 'admin/login');

            } else {

                $this->session->set_flashdata('err_message', 'You are not registered. Something went wrong, please try again.');
                redirect(base_url() . 'admin/signup');

            } //end if

        }

    } //end public function login_process()

    public function add_users_digiebot() {

        $rawData = file_get_contents("php://input");
        $data = (array) json_decode($rawData);

        // // Response return from ongage
        // $post_data = print_r($data, true);
        // $data1 = $post_data;
        // $fp = fopen('shahzaddddd.txt', 'a') or exit("Unable to open file!");
        // fwrite($fp, $data1);
        // fclose($fp);
        // //User Signup
        // exit;
        if (empty($data['username'])) {
            echo false;
            exit;    
        }
        
        $user_signup = $this->mod_users->add_users_digiebot_from_report($data);
        echo  $user_signup;  exit;

    } //end public function add_users_digiebot()

    public function add_users_country_from_digiebot() {

        $rawData = file_get_contents("php://input");
        $data = (array) json_decode($rawData);

      
        if (empty($data['country'])) {
            echo false;
            exit;    
        }
        $country = isset($data['country']) ? $data['country'] : "";
        $db = $this->mongo_db->customQuery();
        $dg_id = $this->mongo_db->mongoId($data['dg_id']);
        $user = $db->users->updateOne(['_id' => $dg_id], ['$set' => ['country' => $country]]);
        $user_data = $db->users->findOne(['_id' => $dg_id]);
    
        echo json_encode($user_data['username']);
        exit;
    } //end public function add_users_digiebot()
	
	
	 public function get_users_exists() {
	
        $rawData = file_get_contents("php://input");
        $data    = (array) json_decode($rawData);
		
        // Response return from ongage
        // $post_data = print_r($data, true);
        // $data1 = $post_data;
        // $fp = fopen('shahzaddddd.txt', 'a') or exit("Unable to open file!");
        // fwrite($fp, $data1);
        // fclose($fp);
        // exit;
        $user_signup = $this->mod_users->report_user_email_exists($data);
		
		if($user_signup){
			echo $user_signup ;  exit;
		}else{
		    echo 0;  exit;	
		}
    } //end public function add_users_digiebot()
	

    public function testCurlCall($symbol='BTCUSDT'){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.kraken.com/0/public/Ticker?pair=".$symbol,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
        ));
      
        $response = curl_exec($curl);
        curl_close($curl);
        
        $returnRes = json_decode($response, true); 
        $highPrice = null;
		if ($returnRes['result']) {
            $price = array_column($returnRes['result'], 'h');
            $highPrice = $price[0][1];
		}
        echo "<pre>"; print_r($highPrice);
    }

    public function activate($user_id, $activation_code) {

        //Account Activation
        $account_activation = $this->mod_users->account_activation($user_id, $activation_code);

        if ($account_activation) {

            $this->session->set_flashdata('ok_message', 'Your account has been Activated, please login here');
            redirect(base_url() . 'admin/login');

        } else {

            $this->session->set_flashdata('err_message', 'Your not Activated. Something went wrong, please try again.');
            redirect(base_url() . 'admin/login');

        } //end if

    }

    public function check_user_info() {
        if ($this->input->post('user_name')) {
            $name = $this->input->post('user_name');

            //$this->db->dbprefix('users');
            $this->mongo_db->where(array('username' => $name));
            $res = $this->mongo_db->get('users');
            $row = iterator_to_array($res);

            if (count($row) > 0) {
                echo "<div class='alert alert-danger alert-dismissable'>Username already Exist</div>" . "@@" . "0";
                exit;
            } else {
                echo "" . "@@" . "1";
                exit;
            }
        }

        if ($this->input->post('user_email')) {
            $email = $this->input->post('user_email');

            $this->mongo_db->where(array('email_address' => $email));
            $res = $this->mongo_db->get('users');
            $row = iterator_to_array($res);

            if (count($row) > 0) {
                echo "<div class='alert alert-danger alert-dismissable'>Email already Exist</div>" . "@@" . "0";
                exit;
            } else {
                echo "" . "@@" . "1";
                exit;
            }
        }
    }
    public function update_user_digiebot() {
        $rawData = file_get_contents("php://input");
        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);
        if($tokenData != false ){   // checking if the token was original or not
            $data = (array) json_decode($rawData);
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id); // checking if the user exists on token
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){
            if (empty($data['dg_id'])) {
                echo false;
                exit;    
            }
            $user_signup = $this->mod_users->update_user_digiebot_from_backoffice($data);
            $message = array(
                        'status' => true,
                        'message' => 'data Updated successfully',
                        'data'=>$data,
                    );
                http_response_code('200');
                echo json_encode($message);
            //$this->set_response($message, REST_Controller::HTTP_OK);
        }else{
             $message = array(
                    'status' => 401,
                    'message' => 'This is not Valid user!!!',
                );
                //$this->set_response($message, REST_Controller::HTTP_OK);
                http_response_code('401');
                echo json_encode($message);
            }
        }else{
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );
            //$this->set_response($message, REST_Controller::HTTP_OK);
             http_response_code('401');
             echo json_encode($message);
        }

    } //end public function update_user_digiebot() 
    public function update_user_expiry_date_backoffice() {
        //$rawData = file_get_contents("php://input");
        //$request = (array) json_decode($rawData);
        $request = $this->input->post();
        $user_id = $request['dg_id'];
        //echo json_encode($user_id);exit;
        $subscription_expiry_date = $request['subscription_expiry_date'] ?? '';
        $signup_package_selected = $request['signup_package_selected'] ?? '';
        if (!empty($_SERVER["PHP_AUTH_USER"]) && $_SERVER["PHP_AUTH_USER"] == 'pointSupply' && !empty($_SERVER["PHP_AUTH_PW"]) && $_SERVER["PHP_AUTH_PW"] == md5('users.digiebot.com')) {
                $user_id = $this->mongo_db->mongoId($user_id);
                $db = $this->mongo_db->customQuery();
                $user_exists = $db->users->find(['_id' => $user_id]);
                $user_exists = iterator_to_array($user_exists);
                if(!empty($user_exists)){
                    $db->users->updateOne(['_id' => $user_id], ['$set' => ['subscription_expiry_date' => $subscription_expiry_date, 'signup_package_selected'=> $signup_package_selected]]);
                    $message = array(
                        'status' => true,
                        'message' => 'Subscription expiry updated successfully',
                    );
                }else{
                    $message = array(
                        'status' => false,
                        'message' => (string) $user_id ." user_id not found",
                    );
                }
                echo json_encode($message);
        } else {
            $message = array(
                'status' => false,
                'message' => 'not authorized',
            );
            echo json_encode($message);
        }
    } //end public function update_user_digiebot()   

}
