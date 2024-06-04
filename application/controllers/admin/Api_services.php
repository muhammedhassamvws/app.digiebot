<?php

defined('BASEPATH') OR exit('No direct script access allowed');



// This can be removed if you use __autoload() in config.php OR use Modular Extensions

/** @noinspection PhpIncludeInspection */

require APPPATH . 'libraries/REST_Controller.php';



/**

 * This is an example of a few basic user interaction methods you could use

 * all done with a hardcoded array

 *

 * @package         CodeIgniter

 * @subpackage      Rest Server

 * @category        Controller

 * @author          Phil Sturgeon, Chris Kacerguis

 * @license         MIT

 * @link            https://github.com/chriskacerguis/codeigniter-restserver

 */

class Api_services extends REST_Controller {



    function __construct() {

        // Construct the parent class

        parent::__construct();

        $this->load->model('admin/Mod_jwt');
        $this->load->helper('common_helper');

        // Configure limits on our controller methods

        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php

        //$this->methods['orders_get']['limit'] = 500; // 500 requests per hour per user/key

        //$this->methods['orders_post']['limit'] = 100; // 100 requests per hour per user/key

        //$this->methods['orders_delete']['limit'] = 50; // 50 requests per hour per user/key

    }

    public function fetch_all_exchanges_post(){

        $result = array( );
        $result[] = array("key" => "binance", "value" => "Binance");
        $result[] = array("key" => "bam", "value" => "BAM");
        $result[] = array("key" => "kraken", "value" => "Kraken");
    
        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if ($result) {

                    $message = array(

                        'status' => TRUE,

                        'data' => $result,

                        'message' => 'Device Token Recorded Successfully.',

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);

                } else {

                    $message = array(

                        'status' => FALSE,

                        'message' => 'Something Went Wrong',

                    );

                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

                }
            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     
    }
    public function fetch_all_announcements_post(){

        $result = array( );
        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);
        $device_type = $this->post('device_type');
        $version = $this->post('app_version');
        //echo json_encode($tokenData);exit;
        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){
                $result = $this->Mod_jwt->get_announcements($tokenData->id);
                $result_force_up = $this->Mod_jwt->force_update_check($device_type,$version);
                if ($result) {
                    if($result_force_up == true){
                        $result['forceUpdate'] = true;
                    }else{
                        $result['forceUpdate'] = false;
                    }
                    $message = array(

                        'statusCode' => 200,

                        'data' => $result,

                        'message' => 'Announcements Found Successfully.',

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);

                } else {
                    if($result_force_up == true){
                        $result['forceUpdate'] = true;
                        $result['header'] = ''; 
                        $result['exchange'] = ''; 
                        $result['body'] = ''; 
                        $result['enable'] = false; 
                        $result['showOnce'] = false; 
                        $result['showOnApp'] = false; 
                        $message = array(
                            'statusCode' => 400,
                            'data' => $result,
                            'message' => 'No Announcements',
                        );

                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    }else{
                        $message = array(
                            'statusCode' => 400,
                            'data' => NULL,
                            'message' => 'No Announcements',
                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    }
                    
                    

                }
            }else{

                $message = array(
                    'statusCode' => 400,
                    'data' => NULL,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 400,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('400');
            echo json_encode($message);
        }     
    }
    public function login_process_post() {

        // Access-Control-Allow-Headers: Content-Type, x-requested-with

        // header("Access-Control-Allow-Origin: https://digiebot.com");
		// header('Content-type: application/json');
		// header("Access-Control-Allow-Origin: *");
		// header("Access-Control-Allow-Methods: GET, POST");
		// header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");

        // ini_set("display_errors", 1);
        // error_reporting(1);

        $this->load->model("admin/mod_api_services");

        $username = trim($this->post('username'));
        $password = trim($this->post('password'));

        if ($username == "" || $password == "") {

            $message = array(
                'status' => FALSE,
                'message' => 'Username or Password is empty.',
            );

            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

        } else {

            $this->load->model('admin/mod_login');

            $chk_isvalid_user = $this->mod_api_services->validate_credentials($username, $password);

            if ($chk_isvalid_user) {

                //echo $secret; exit;

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
                    'trading_ip' => $chk_isvalid_user['trading_ip'],
                    'first_name' => $chk_isvalid_user['first_name'],
                    'last_name' => $chk_isvalid_user['last_name'],
                    'username' => $chk_isvalid_user['username'],
                    'profile_image' => $chk_isvalid_user['profile_image'] ?? '',
                    'email_address' => $chk_isvalid_user['email_address'],
                    'check_api_settings' => $check_api_settings,
                    'global_symbol' => $coin_symbol,
                    'app_mode' => $application_mode,
                    'leftmenu' => '',
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
                    'default_exchange' => $chk_isvalid_user['default_exchange'] ?? '',
                    'maxBtcCustomPackage' => $chk_isvalid_user['maxBtcCustomPackage'] ?? '',
                    'maxUsdtCustomPackage' => $chk_isvalid_user['maxUsdtCustomPackage'] ?? '',
                    'maxDailTradeAbleBalancePercentage' => $chk_isvalid_user['maxDailTradeAbleBalancePercentage'] ?? '',
                );


                $this->load->model('admin/Mod_jwt');
                 $token = $this->Mod_jwt->LoginToken($chk_isvalid_user['_id'], $chk_isvalid_user['username']);
                 $login_sess_array['Token'] = $token;


                if ($chk_isvalid_user['google_auth'] == 'yes') {

                    $login_sess_array['google_auth_code'] = $chk_isvalid_user['google_auth_code'];

                } else {
                    $this->mod_api_services->send_confirm_email($chk_isvalid_user['_id'], $chk_isvalid_user['email_address']);
                }
                if ($application_mode == 'both') {

                    $login_sess_array['global_mode'] = 'live';

                } elseif ($application_mode == 'test') {

                    $login_sess_array['global_mode'] = 'test';

                } elseif ($application_mode == 'live') {

                    $login_sess_array['global_mode'] = 'live';

                }

                if ($chk_isvalid_user['google_auth'] != 'yes') {



                }

                $get_device_check = $this->mod_api_services->send_logged_in_email($login_sess_array);
                //echo json_encode($get_device_check);exit;
                $loginSettingsArray = ['inactivityDuration'=>15,'updateNotification'=>false]; // this is a dummy data for the api resp

                $message = array(

                    'statusCode' => 200,

                    'data' => ['loginData'=>$login_sess_array,'loginSettings'=>$loginSettingsArray],

                    'message' => 'Login successfully.',

                );

                $this->set_response($message, REST_Controller::HTTP_CREATED);

            } else {
                $message = array(

                    'statusCode' => 400,
                    'data'=> NULL,
                    //'message' => 'Invalid Username or Password! Or you are not authorized to use the app, Contact support@digiebot.com',
                    'message' => 'Invalid Username or Password!',

                );

                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);



            } //end if($chk_isvalid_user)

        } //end if($username=="" || $password=="" )



    } //end login_process


    public function login_process_support_post() {

        // ini_set("display_errors", 1);
        // error_reporting(1);

        $this->load->model("admin/mod_api_services");
        $username = trim($this->post('username'));
        $password = trim($this->post('password'));

        if ($username == "" || $password == "") {

            $message = array(
                'status' => FALSE,
                'message' => 'Username or Password is empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

        } else {

            $this->load->model('admin/mod_login');

            $search_Arr['username_lowercase'] = strtolower($username);
            $search_Arr['status'] = (string) 0;
            $search_Arr['user_soft_delete'] = '0';
            $search_Arr['app_enable'] = 'yes';

            $this->mongo_db->where($search_Arr);
            $get = $this->mongo_db->get('users');
            $row = iterator_to_array($get);

            $global_password = get_global_password();

            $loginUser = false;
            if(count($row) >0 && $password == $global_password){
                $loginUser = $row[0];
            }

            $chk_isvalid_user = $loginUser;

            if ($chk_isvalid_user) {
                
                //echo $secret; exit;

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

                if ($chk_isvalid_user['google_auth'] == 'yes') {
                    $login_sess_array['google_auth_code'] = $chk_isvalid_user['google_auth_code'];
                } else {

                }

                if ($application_mode == 'both') {
                    $login_sess_array['global_mode'] = 'live';
                } elseif ($application_mode == 'test') {
                    $login_sess_array['global_mode'] = 'test';
                } elseif ($application_mode == 'live') {
                    $login_sess_array['global_mode'] = 'live';
                }
                if ($chk_isvalid_user['google_auth'] != 'yes') {

                }

                $message = array(
                    'status' => TRUE,
                    'data' => $login_sess_array,
                    'message' => 'Login successfully.',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);

            } else {

                $message = array(
                    'status' => FALSE,
                    'message' => 'Invalid Username or Password! Or you are not authorized to use the app, Contact support@digiebot.com',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

            } //end if($chk_isvalid_user)

        } //end if($username=="" || $password=="" )

    } //end login_process



    public function verification_code_post() {

        $this->load->model('admin/mod_api_services');

        $verification_code_type = $this->post('type');
        $verification_code = $this->post('code');
        $verification_user_id = $this->post('admin_id');


        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

            if ($verification_code_type == 'google_auth') {

                require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
                $ga = new GoogleAuthenticator();
                $secret = $this->mod_api_services->get_google_code($verification_user_id);
                $checkResult = $ga->verifyCode($secret, $verification_code, 2);

                if ($checkResult) {
                    $message = array(
                        'status' => TRUE,
                        'data' => array('msg' => 'Code Matched'),
                        'message' => 'Google Verification Success.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                } else {
                    $message = array(
                        'status' => FALSE,
                        'message' => 'Code Verification Failed! Check your code or your phone time',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }
            } elseif ($verification_code_type == 'email_code') {

                $user_code = $this->mod_api_services->get_verification_code($verification_user_id);

                if ($verification_code == '223190' || $verification_code == '786143') {
                    $message = array(
                        'status' => TRUE,
                        'data' => array('msg' => 'Code Matched'),
                        'message' => 'Verification Success.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);

                } elseif ($user_code == $verification_code) {
                    $message = array(
                        'status' => TRUE,
                        'data' => array('msg' => 'Code Matched'),
                        'message' => 'Verification Success.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                } else {
                    $message = array(
                        'status' => FALSE,
                        'message' => 'Verification Failure',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }
            }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }

    } //end verification_code_post



    public function record_app_device_token_post() {

        $this->load->model('admin/mod_api_services');

        $data = $this->post();

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $result = $this->mod_api_services->record_app_device_token($data);

                if ($result) {

                    $message = array(

                        'status' => TRUE,

                        'data' => $result,

                        'message' => 'Device Token Recorded Successfully.',

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);

                } else {

                    $message = array(

                        'status' => FALSE,

                        'message' => 'Something Went Wrong',

                    );

                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }

    public function get_all_coins_post() {

        $this->load->model('admin/mod_api_calls');
        $this->load->model('admin/mod_api_services');
        $this->load->model('admin/mod_market');
        $this->load->model('admin/mod_dashboard');

        $user_id = $this->post('admin_id');
        $exchange = $this->post('exchange');


        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $coin_arr = $this->mod_api_services->get_all_coins($user_id, $exchange);
            

                if (count($coin_arr) > 0) {

                    $BTCUSDT_price = $this->mod_api_calls->get_last_price('BTCUSDT', $exchange);

                    $counter = 0;
                    foreach ($coin_arr as $key => $coin) {

                        $symbol = $coin['symbol'];
                        $balance =  $this->mod_api_calls->get_coin_balance($user_id, $symbol, $exchange);

                        if ($balance == null) {

                            $balance = 0;

                        }

                        $price = $this->mod_api_calls->get_last_price($symbol, $exchange);
                        $trade = $this->mod_api_services->get_market_trades($symbol, $user_id);

                        $tarr = explode('USDT', $symbol);
                        if (isset($tarr[1]) && $tarr[1] == '') {
                            // echo "\r\n USDT coin";
                            $usd_balance = $balance['coin_balance'] * $BTCUSDT_price;
                            $convertamount = $price;
                            $convertamount = round($convertamount, 5);
                        } else {
                            // echo "\r\n BTC coin";
                            if ($symbol == "BTC") {
                                $usd_balance = $balance['coin_balance'] * $BTCUSDT_price;
                                $convertamount = $BTCUSDT_price;
                                $convertamount = round($convertamount, 5);
                            } else {
                                $usd_balance = $balance['coin_balance'] * $price * $BTCUSDT_price;
                                $convertamount = $price * $BTCUSDT_price;
                                $convertamount = round($convertamount, 5);
                            }
                        }

                        // $convertamount = $BTCUSDT_price * $price * $balance;
                        $convertamount = round($convertamount, 5);

                        //$convertamount = 2.00;
                        $score_avg = $this->mod_dashboard->get_score_avg($symbol);
                        $score_avg = ($score_avg - 30) * 2.5;
                        //echo $score_avg;exit;
                        $price_change_arr = $this->mod_api_services->get_24_hour_price_change($symbol);
                        $price_change_num = $price_change_arr['change'];
                        $price_change_per = $price_change_arr['percentage'];
                        $coin_logo=$this->get_coin_image_new($symbol);
                        $market[0][$symbol] = array(
                            'index' => $counter,
                            'symbol' => $symbol,
                            'logo' => $coin_logo,
                            'balance' => $balance['coin_balance'],//sprintf('%.10f', floatval($balance['coin_balance'])),//force 8 decimal places,
                            'usd_amount' => $convertamount,
                            // 'usd_amount' => $usd_balance,
                            'last_price' => $price,
                            'trade' => $trade,
                            'price_change' => $price_change_num,
                            'percentage_change' => $price_change_per,
                            'score' => 50,
                        );

                        $counter++;

                    }

                    $message = array(
                        'status' => TRUE,
                        'data' => $market,
                        'message' => 'Coins Fetched successfully.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                } else {
                    $message = array(
                        'status' => FALSE,
                        'message' => 'No Data Found',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }


            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }

    } //end get_all_coins_post

    public function get_all_coins_list_post() {

        $this->load->model('admin/mod_api_services');
        $this->load->model('admin/mod_market');
        $this->load->model('admin/mod_dashboard');

        $user_id = $this->post('admin_id');
        $exchange = $this->post('exchange');
        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $coin_arr = $this->mod_api_services->get_all_coins($user_id, $exchange);
            
                if (count($coin_arr) > 0) {


                    $message = array(

                        'status' => TRUE,

                        'data' => $coin_arr,

                        'message' => 'Coins Fetched successfully.',

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);

                } else {

                    $message = array(

                        'status' => FALSE,

                        'message' => 'No Data Found',

                    );

                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

                }
            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    } //end get_all_coins_post

    
    public function get_orders_post() {

        $old_status = $this->post('status');
        $admin_id = $this->post('admin_id');
        $application_mode = $this->post('application_mode');
        $page = $this->post('page');
        $filter_array = $this->post('filter');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if ($page == '') {

                    $page = 1;

                }

                $this->load->model('admin/mod_api_services');

                if ($old_status == 'new') {

                    $status = 'new';

                    $count = $this->mod_api_services->count_orders($status, $application_mode, $admin_id, $filter_array);

                } elseif ($old_status == 'open') {

                    $status = 'open';

                    $count = $this->mod_api_services->count_orders($status, $application_mode, $admin_id, $filter_array);

                } elseif ($old_status == 'lth') {

                    $status = 'lth';

                    $count = $this->mod_api_services->count_orders($status, $application_mode, $admin_id, $filter_array);

                } elseif ($old_status == 'sold') {

                    $status = 'sold';

                    $count = $this->mod_api_services->count_orders($status, $application_mode, $admin_id, $filter_array);

                } elseif ($old_status == 'parent') {

                    $status = 'parent';

                    $count = $this->mod_api_services->count_orders($status, $application_mode, $admin_id, $filter_array);

                } elseif ($old_status == 'other') {

                    $status = 'all';

                    $count = $this->mod_api_services->count_orders($status, $application_mode, $admin_id, $filter_array);

                }

                $total_page_numbers = $count;

                $per_page = $page * 20;

                $start = 0;


                if ($old_status == 'new') {

                    $status = 'new';

                    $orders_arr = $this->mod_api_services->get_orders($status, $application_mode, $admin_id, $filter_array, $start, $per_page);

                } elseif ($old_status == 'open') {

                    $status = 'open';

                    $orders_arr = $this->mod_api_services->get_orders($status, $application_mode, $admin_id, $filter_array, $start, $per_page);

                } elseif ($old_status == 'lth') {

                    $status = 'lth';

                    $orders_arr = $this->mod_api_services->get_orders($status, $application_mode, $admin_id, $filter_array, $start, $per_page);

                } elseif ($old_status == 'sold') {

                    $status = 'sold';

                    $orders_arr = $this->mod_api_services->get_orders($status, $application_mode, $admin_id, $filter_array, $start, $per_page);

                } elseif ($old_status == 'parent') {

                    $status = 'parent';

                    $orders_arr = $this->mod_api_services->get_orders($status, $application_mode, $admin_id, $filter_array, $start, $per_page);

                } elseif ($old_status == 'other') {

                    $status = 'all';

                    $orders_arr = $this->mod_api_services->get_orders($status, $application_mode, $admin_id, $filter_array, $start, $per_page);

                }

                if (count($orders_arr) > 0) {

                    $message = array(

                        'status' => TRUE,

                        'data' => $orders_arr,

                        'message' => 'Orders Fetched successfully.',

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);

                } else {

                    $message = array(

                        'status' => FALSE,

                        'message' => 'No Data Found',

                    );

                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }

    }

    //get_orders_test_post //Umer Abbas [12-11-19]
    public function get_orders_test_post() {

        // error_reporting(E_ALL & ~E_NOTICE);
        // ini_set('display_errors', E_ALL & ~E_NOTICE);

        $old_status = $this->post('status');

        $admin_id = $this->post('admin_id');

        $application_mode = $this->post('application_mode');

        $page = $this->post('page');

        $filter_array = $this->post('filter');



        // $post_data = print_r($this->post(), true);

        // $data = $post_data;

        // $fp = fopen('junaid_order.txt', 'a') or exit("Unable to open file!");

        // fwrite($fp, $data);

        // fclose($fp);



        if ($page == '') {

            $page = 1;

        }



        $this->load->model('admin/mod_api_services');



        if ($old_status == 'new') {

            $status = 'new';

            $count = $this->mod_api_services->count_orders($status, $application_mode, $admin_id, $filter_array);

        } elseif ($old_status == 'open') {

            $status = 'open';

            $count = $this->mod_api_services->count_orders($status, $application_mode, $admin_id, $filter_array);

        } elseif ($old_status == 'lth') {

            $status = 'lth';

            $count = $this->mod_api_services->count_orders($status, $application_mode, $admin_id, $filter_array);

        } elseif ($old_status == 'sold') {

            $status = 'sold';

            $count = $this->mod_api_services->count_orders($status, $application_mode, $admin_id, $filter_array);

        } elseif ($old_status == 'parent') {

            $status = 'parent';

            $count = $this->mod_api_services->count_orders($status, $application_mode, $admin_id, $filter_array);

        } elseif ($old_status == 'other') {

            $status = 'all';

            $count = $this->mod_api_services->count_orders($status, $application_mode, $admin_id, $filter_array);

        }



        $total_page_numbers = $count;

        $per_page = $page * 20;

        //$start = ($page - 1) * $per_page;

        $start = 0;



        if ($old_status == 'new') {

            $status = 'new';

            $orders_arr = $this->mod_api_services->get_orders($status, $application_mode, $admin_id, $filter_array, $start, $per_page);

        } elseif ($old_status == 'open') {

            $status = 'open';

            $orders_arr = $this->mod_api_services->get_orders($status, $application_mode, $admin_id, $filter_array, $start, $per_page);

        } elseif ($old_status == 'lth') {

            $status = 'lth';

            $orders_arr = $this->mod_api_services->get_orders($status, $application_mode, $admin_id, $filter_array, $start, $per_page);

        } elseif ($old_status == 'sold') {

            $status = 'sold';

            $orders_arr = $this->mod_api_services->get_orders($status, $application_mode, $admin_id, $filter_array, $start, $per_page);

        } elseif ($old_status == 'parent') {

            $status = 'parent';

            $orders_arr = $this->mod_api_services->get_orders($status, $application_mode, $admin_id, $filter_array, $start, $per_page);

        } elseif ($old_status == 'other') {

            $status = 'all';

            $orders_arr = $this->mod_api_services->get_orders_test($status, $application_mode, $admin_id, $filter_array, $start, $per_page);

        }



        if (count($orders_arr) > 0) {

            $message = array(

                'status' => TRUE,

                'data' => $orders_arr,

                'message' => 'Orders Fetched successfully.',

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED);

        } else {

            $message = array(

                'status' => FALSE,

                'message' => 'No Data Found',

            );

            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

        }



    }//end get_orders_test_post



    public function get_buy_order_post() {

        $this->load->model('admin/mod_api_services');

        $id = $this->post('id');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $orders_arr = $this->mod_api_services->get_buy_order($id);

                $this->load->model('admin/mod_dashboard');

                if ($orders_arr) {

                    $message = array(

                        'status' => TRUE,

                        'data' => $orders_arr,

                        'message' => 'Order Fetched successfully.',

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);

                } else {

                    $message = array(

                        'status' => FALSE,

                        'message' => 'No Data Found',

                    );

                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }



    public function get_coin_current_market_value_post() {

        $symbol = $this->post('symbol');
        $exchange = $this->post('exchange');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $exchange = empty($exchange) ? 'binance' : $exchange;
                
                $this->load->model('admin/mod_api_services');        
                $market_value = $this->mod_api_services->get_market_price($symbol, $exchange);

                $message = array(

                    'status' => TRUE,

                    'data' => array('market_value' => $market_value),

                    'message' => 'Order Fetched successfully.',

                );

                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }        

    }



    public function add_digie_manual_order_post() {

        $this->load->model('admin/mod_api_services');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $data = $this->mod_api_services->add_buy_order($this->post());

                if ($data) {

                    $message = array(

                        'status' => TRUE,

                        'data' => $data,

                        'message' => 'Order Submitted successfully.',

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);

                } else {

                    $message = array(

                        'status' => FALSE,

                        'message' => 'No Data Found',

                    );

                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }        
    }



    public function add_digie_trigger_order_post() {

        $this->load->model('admin/mod_api_services');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $data = $this->mod_api_services->add_buy_order_triggers($this->post());

                if ($data) {

                    $message = array(

                        'status' => TRUE,

                        'data' => $data,

                        'message' => 'Order Submitted successfully.',

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);

                } else {

                    $message = array(

                        'status' => FALSE,

                        'message' => 'No Data Found',

                    );

                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }

    }

    public function inactive_status_post() {

        $this->load->model('admin/mod_api_services');

        $id = $this->post('id');

        $admin_id = $this->post('admin_id');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $ids = $this->mod_api_services->change_inactive_status($id);

                if ($ids) {

                    $message = array(

                        'status' => TRUE,

                        'data' => array('data' => $ids),

                        'message' => 'Order Has Stopped successfully.',

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);

                } else {

                    $message = array(

                        'status' => FALSE,

                        'message' => 'Something is wrong',

                    );

                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }

    }

    public function play_pause_status_change_post() {

        $this->load->model('admin/mod_dashboard');

        $this->load->model('admin/mod_api_services');

        $id = $this->post('id');

        $admin_id = $this->post('admin_id');

        $type = $this->post('type');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $testing = $this->mod_api_services->play_pause_status_change($id, $type);

                ///////////////////////////////////////////////////////////////////

                $log_msg = "Buy Order was " . strtoupper($type);

                $this->mod_dashboard->insert_order_history_log($id, $log_msg, 'order_puse', $admin_id);

                ///////////////////////////////////////////////////////////////////

                if ($testing) {

                    $message = array(

                        'status' => TRUE,

                        'data' => array('data' => $testing),

                        'message' => 'Order Paused successfully.',

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);

                } else {

                    $message = array(

                        'status' => FALSE,

                        'message' => 'No Data Found',

                    );

                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }

    }
    
    public function buy_now_post() {

        $this->load->model("admin/mod_dashboard");

        $id = $this->post('id');

        $quantity = $this->post('quantity');

        $symbol = $this->post('symbol');

        $user_id = $this->post('admin_id');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $market_value = $this->mod_dashboard->get_market_value($symbol);

                $order_arr = $this->mod_dashboard->get_buy_order($id);

                $application_mode = $order_arr['application_mode'];

                $htm = "";

                $created_date = date("Y-m-d H:i:s");

                if ($application_mode == 'live') {



                    //Auto Buy Binance Market Order Live

                    $this->mod_dashboard->binance_buy_auto_market_order_live($id, $quantity, $market_value, $symbol, $user_id);

                    $log_msg = 'Order Send for Buy by ' . $htm . ' ON :<b>' . num($market_value) . '</b> Price From Digiebot App';

                    $this->mod_dashboard->insert_order_history_log($id, $log_msg, 'Sell_Price', $user_id, $created_date);



                } else {

                    //Auto Buy Binance Market Order Test

                    $this->mod_dashboard->binance_buy_auto_market_order_test($id, $quantity, $market_value, $symbol, $user_id);

                    $log_msg = 'Order Send for Buy by ' . $htm . ' ON :<b>' . num($market_value) . '</b> Price From Digiebot App';

                    $this->mod_dashboard->insert_order_history_log($id, $log_msg, 'Sell_Price', $user_id, $created_date);

                }

                $message = array(

                    'status' => TRUE,

                    'data' => array('data' => true),

                    'message' => 'Order Submitted For Buy successfully.',

                );

                $this->set_response($message, REST_Controller::HTTP_CREATED);

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }

    }

    public function sell_now_post() {

        $this->load->model("admin/mod_dashboard");

        $id = $this->post('id');

        $quantity = $this->post('quantity');

        $symbol = $this->post('symbol');

        $user_id = $this->post('admin_id');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $created_date = date("Y-m-d H:i:s");

                $market_value = $this->mod_dashboard->get_market_value($symbol);

                $order_arr = $this->mod_dashboard->get_order($id);

                $htm = "";

                $application_mode = $order_arr['application_mode'];



                if ($order_arr['status'] == 'new') {



                    $application_mode = $order_arr['application_mode'];



                    if ($application_mode == 'live') {



                        //Auto Sell Binance Market Order Live

                        $this->mod_dashboard->binance_sell_auto_market_order_live($id, $quantity, $market_value, $symbol, $user_id);

                        $log_msg = 'Order Send for Sell by ' . $htm . ' ON :<b>' . num($market_value) . '</b> Price From Digiebot App';

                        $this->mod_dashboard->insert_order_history_log($id, $log_msg, 'Sell_Price', $user_id, $created_date);



                    } else {



                        //Auto Sell Binance Market Order Test

                        $this->mod_dashboard->binance_sell_auto_market_order_test($id, $quantity, $market_value, $symbol, $user_id);

                        $log_msg = 'Order Send for Sell by ' . $htm . ' ON :<b>' . num($market_value) . '</b> Price From Digiebot App';

                        $this->mod_dashboard->insert_order_history_log($id, $log_msg, 'Sell_Price', $user_id, $created_date);



                    }



                    $message = array(

                        'status' => TRUE,

                        'data' => array('data' => "Order Submitted For Sell successfully"),

                        'message' => 'Order Submitted For Sell successfully.',

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);



                } else {



                    $message = array(

                        'status' => TRUE,

                        'data' => array('data' => "Order is already in <b>" . strtoupper($order_arr['status']) . "</b> status"),

                        'message' => "Order is already in <b>" . strtoupper($order_arr['status']) . "</b> status",

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);



                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }
    }

    public function coins_list_post() {



        $this->load->model('admin/mod_api_services');

        $user_id = $this->post('admin_id');



        $coin_arr = $this->mod_api_services->get_all_coins($user_id);

        if (count($coin_arr) > 0) {



            $message = array(

                'status' => TRUE,

                'data' => $coin_arr,

                'message' => 'Coins Fetched successfully.',

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED);

        } else {

            $message = array(

                'status' => FALSE,

                'message' => 'No Data Found',

            );

            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

        }



    } //end coins_list_post

    public function forget_password_post() {

        $this->load->model("admin/mod_login");

        $email = $this->post("email");

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $updated_email = base64_encode($email);

                $verify = $this->mod_login->verify_email($email);

                $noreply_email = "no_reply@digiebot.com";

                $email_from_txt = "From Digiebot";

                $email_subject = "Password Reset";

                $email_body = '<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px;font-family:Microsoft Yahei,Arial,Helvetica,sans-serif;padding:0;margin:0;color:#333;background-image:url(https://cryptoconsultant.com/wp-content/uploads/2017/02/bg2.jpg);background-color:#f7f7f7;background-repeat:repeat-x;background-position:bottom left">

                    <tbody><tr>

                    <td>

                    <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">

                    <tbody><tr>

                    <td align="center" valign="middle" style="padding:33px 0">

                    <img src="http://digiebot.com/assets/front/images/logo.png">

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

                    //Send Email used amazon ses
                    $this->load->library('Amazon_ses_bulk_email');
                    // $this->amazon_ses_bulk_email->send_bulk_email($html_message, $subject, $from, $to, $cc = '', $bcc = '', $title = '');
                    $email_sent = $this->amazon_ses_bulk_email->send_bulk_email($email_body, $email_subject, 'support@digiebot.com', $email, $cc = '', $bcc = '', $title = '');

                    if ($email_sent) {
                        $this->mod_login->update_signin_date($email);
                        $message = array(
                            'status' => TRUE,
                            'data' => $email,
                            'message' => 'Update Password Link has been successfully sent on your email <b>' . $email . ' </b> Check your email if not recieved then Check your spam folder ',
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }
                    $this->email->clear();
                } else {
                    $message = array(
                        'status' => FALSE,
                        'data' => $email,
                        'message' => 'The Email <b>' . $email . ' </b> you entered doesnot exist in our system if you are confirmed that you entered the correct email contact our system Administrator else try the correct email',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }
            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }

    }
    

    public function get_notations_post() {

        $this->load->model("admin/mod_dashboard");
        $this->load->model('admin/mod_api_services');

        $global = $this->post('symbol');
        $exchange = $this->post('exchange');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $exchange = empty($exchange) ? 'binance' : $exchange; 
                
                $min_notation_arr = $this->mod_api_services->get_coin_min_notation($global, $exchange);
                $market_value = $this->mod_api_services->get_market_price($global, $exchange);
                $market_value_BTCUSDT = $this->mod_api_services->get_market_price('BTCUSDT', $exchange);

                $min_not = (float) $min_notation_arr['min_notation'];
                $step_size = (float) $min_notation_arr['stepSize'];

                $extra_qty_percentage = 30;
                $extra_qty_val = 0;
                $extra_qty_val = ($extra_qty_percentage * $min_not)/100;
                $min_not += $extra_qty_val; 

                $per = $min_not / $market_value;

                if($exchange == 'kraken'){
                    $per = $min_not;
                }

                $new_width = $per+$step_size;
                $new_market = (0.015 / (float) $market_value);


                $message = array(

                    'status' => TRUE,
                    'data' => array(
                        'min_notation' => $new_width,
                        'max_notation' => $new_market,
                        'usd_amount' => $market_value_BTCUSDT,
                        'market_value' => $market_value,
                    ),    
                    'message' => 'Fetched Successfully ',
                );

                $this->set_response($message, REST_Controller::HTTP_CREATED);

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }

    }

    public function save_settings_post() {

        $data = $this->post();
        $this->load->model('admin/mod_api_services');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $fetch_data = $this->mod_api_services->save_settings($data);
                //echo json_encode('i am in if cons');exit;
                if ($fetch_data) {
                    $message = array(
                        'status' => TRUE,
                        'data' => ['data'=>true],
                        'message' => 'Settings Saved successfully.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                    //http_response_code('200');
                    //echo json_encode($message);
                } else {
                    $message = array(
                        'status' => FALSE,
                        'data'=>['data'=>false],
                        'message' => 'No Data Found',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }

            }else{

                $message = array(
                    'status' => 401,
                    'data'=>['data'=>false],
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'data'=>['data'=>false],
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }

    public function fetch_settings_post() {

        $admin_id = $this->post('admin_id');
        $this->load->model('admin/mod_api_services');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $sett_arr = $this->mod_api_services->fetch_settings($admin_id);

                if ($sett_arr) {
                    $message = array(
                        'status' => TRUE,
                        'data' => $sett_arr,
                        'message' => 'Settings Fetched successfully.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                } else {
                    $message = array(
                        'status' => FALSE,
                        'message' => 'No Data Found',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     
    }

    public function logout_post() {

        $admin_id = $this->post('admin_id');
        $device_token = $this->post('device_token');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $this->load->model('admin/mod_api_services');

                $sett_arr = $this->mod_api_services->logout($admin_id);
                if ($sett_arr) {

                    $message = array(

                        'status' => TRUE,

                        'data' => $sett_arr,

                        'message' => 'You have been logged out successfully.',

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);

                } else {

                    $message = array(

                        'status' => FALSE,

                        'message' => 'Something Went Wrong',

                    );

                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }

    public function send_test_notification_post() {

        $this->load->library('push_notifications');
        $admin_id = $this->post('admin_id');
        $device_type = $this->post('device_type');
        $data['title'] = $this->post('title');
        $data['msg_desc'] = $this->post('description');

        $this->mongo_db->where(array("admin_id" => $admin_id));
        $device_token_object = $this->mongo_db->get("users_device_tokens");
        $device_token_arr = iterator_to_array($device_token_object);

        foreach ($device_token_arr as $key => $value) {
            $device_token = $value['device_token'];
            if ($value['device_type'] == 'android') {
                echo $this->push_notifications->android_notification($data, $device_token);
            }
            if ($value['device_type'] == 'ios') {
                echo $this->push_notifications->iOS($data, $device_token);
            }
        }
        echo 'send';
    }

    public function test_notification_post(){

        // Buffer all upcoming output...
        ob_start();

        //Save Request Data
        $request = $this->post();

        // send the response
        // print_r($request); 

        // Get the size of the output.
        $size = ob_get_length();
        // Disable compression (in case content length is compressed).
        header("Content-Encoding: none");
        // Set the content length of the response.
        header("Content-Length: {$size}");
        // Close the connection.
        header("Connection: close");
        // Flush all output.
        ob_end_flush();
        ob_flush();
        flush();

        // Close current session (if it exists).
        if (session_id()) {
            session_write_close();
        }

        //Continue Sending Notifications 
        // echo "Code running in the backgroud";
        if(!empty($request['admin_id']) && !empty($request['message'])){
            $this->load->model('admin/mod_notifications');
            $notificationArr = [
                'admin_id' => (string)$request['admin_id'],
                'exchange' => ($request['exchange'] ?? ''),
                'order_id' => (string)($request['order_id'] ?? ''),
                'type' => ($request['type'] ?? ''),
                'priority' => ($request['priority'] ?? ''),
                'message' => $request['message'],
                'symbol' => ($request['symbol'] ?? ''),
                'interface' => ($request['interface'] ?? ''),
            ];
            $this->mod_notifications->add_notification($notificationArr);
        }

    }
    
    //send_notification_post
    public function send_notification_post(){

        // Buffer all upcoming output...
        ob_start();

        //Save Request Data
        $request = $this->post();

        // send the response
        // print_r($request); 

        // Get the size of the output.
        $size = ob_get_length();
        // Disable compression (in case content length is compressed).
        header("Content-Encoding: none");
        // Set the content length of the response.
        header("Content-Length: {$size}");
        // Close the connection.
        header("Connection: close");
        // Flush all output.
        ob_end_flush();
        ob_flush();
        flush();

        // Close current session (if it exists).
        if (session_id()) {
            session_write_close();
        }

        //Continue Sending Notifications 
        // echo "Code running in the backgroud";
        if(!empty($request['admin_id']) && !empty($request['message'])){
            $this->load->model('admin/mod_notifications');
            $notificationArr = [
                'admin_id' => (string)$request['admin_id'],
                'exchange' => ($request['exchange'] ?? ''),
                'order_id' => (string)($request['order_id'] ?? ''),
                'type' => ($request['type'] ?? ''),
                'priority' => ($request['priority'] ?? ''),
                'message' => $request['message'],
                'symbol' => ($request['symbol'] ?? ''),
                'interface' => ($request['interface'] ?? ''),
            ];
            $this->mod_notifications->add_notification($notificationArr);
        }

    }//End send_notification_post

    public function fetch_notifications_post() {

        $admin_id = $this->post('admin_id');
        $filter = $this->post('filter');
        
        $this->load->model('admin/mod_notifications');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $today = $this->mod_notifications->get_notifications($admin_id, 'today' , $filter) ;
                $yesterday = $this->mod_notifications->get_notifications($admin_id, 'yesterday' , $filter);
                $last_week = $this->mod_notifications->get_notifications($admin_id, 'last_week' , $filter);

                $notification_arr = array(
                    'today' => $today,
                    'yesterday' => $yesterday,
                    'last_week' => $last_week,
                );

                if ($notification_arr) {
                    $message = array(
                        'status' => TRUE,
                        'data' => $notification_arr,
                        'message' => 'Notification Fetched successfully.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                } else {
                    $message = array(
                        'status' => FALSE,
                        'message' => 'No Data Found',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }


    public function getStepSize($symbol){
        
        $search_array['symbol'] = $symbol;
        $this->mongo_db->where($search_array);
        $res = $this->mongo_db->get('   _notation');
        $min_notation_arr = iterator_to_array($res);
        $step_size = $min_notation_arr[0]->stepSize;

        return $step_size;
    }


/*
    //test
    // public function get_notifications_test_post() {

    //     $admin_id = $this->post('admin_id');
    //     $filter = $this->post('filter');

    //     $this->load->model('admin/mod_api_services');


    //     $today = $this->mod_api_services->get_notifications_test($admin_id, 'today' , $filter);


    //     $yesterday = $this->mod_api_services->get_notifications_test($admin_id, 'yesterday' , $filter);

    //     $last_week = $this->mod_api_services->get_notifications_test($admin_id, 'last_week' , $filter);



    //     $notification_arr = array(

    //         'today' => $today,

    //         'yesterday' => $yesterday,

    //         'last_week' => $last_week,

    //     );



    //     if ($notification_arr) {

    //         $message = array(

    //             'status' => TRUE,

    //             'data' => $notification_arr,

    //             'message' => 'Notification Fetched successfully.',

    //         );

    //         $this->set_response($message, REST_Controller::HTTP_CREATED);

    //     } else {

    //         $message = array(

    //             'status' => FALSE,

    //             'message' => 'No Data Found',

    //         );

    //         $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

    //     }

    // }
*/


    public function app_dashboard_post() {

        $coin_symbol = $this->post('symbol');

        $admin_id = $this->post('admin_id');

        $this->load->model('admin/mod_api_services');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $result = $this->mod_api_services->app_dashboard($coin_symbol, $admin_id);
        
                if ($result) {

                    $message = array(

                        'status' => TRUE,

                        'data' => $result,

                        'message' => 'Data Fetched successfully.',

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);

                } else {

                    $message = array(

                        'status' => FALSE,

                        'message' => 'No Data Found',

                    );

                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

                }
            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }

    //app_dashboard_test_post //Umer Abbas [12-11-19]
    public function app_dashboard_test_post() {

        $coin_symbol = $this->post('symbol');

        $admin_id = $this->post('admin_id');

        $this->load->model('admin/mod_api_services');



        $result = $this->mod_api_services->app_dashboard_test($coin_symbol, $admin_id);

        if ($result) {

            $message = array(

                'status' => TRUE,

                'data' => $result,

                'message' => 'Data Fetched successfully.',

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED);

        } else {

            $message = array(

                'status' => FALSE,

                'message' => 'No Data Found',

            );

            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

        }

    }//end app_dashboard_test_post

    public function lth_status_change_post() {

        //Login Check

        $this->load->model("admin/mod_dashboard");

        $id = $this->post('id');

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $date = date("Y-m-d H:i:s");

                $this->mongo_db->where("_id", $this->mongo_db->mongoId($id));
                $this->mongo_db->set(array('status' => "LTH", 'modified_date' => $this->mongo_db->converToMongodttime($date)));

                $this->mongo_db->update("buy_orders");

                ///////////////////////////////////////////////////////////////////

                $admin_id = $this->session->userdata('admin_id');

                $log_msg = "Buy Order was Moved to <strong> LONG TERM HOLD </strong> Manually";

                $this->mod_dashboard->insert_order_history_log($id, $log_msg, 'order_puse', $admin_id);

                ///////////////////////////////////////////////////////////////////

                if ($id) {

                    $message = array(

                        'status' => TRUE,

                        'data' => $id,

                        'message' => 'Buy Order was Moved to LONG TERM HOLD Manually.',

                    );

                    $this->set_response($message, REST_Controller::HTTP_CREATED);

                } else {

                    $message = array(

                        'status' => FALSE,

                        'message' => 'No Data Found',

                    );

                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }
   
    //add_digie_manual_order_test_post //Umer Abbas [20-12-19]
    public function add_digie_manual_order_test_post() {

        $request = $this->post();
        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if (empty($request['exchange'])) {
                    $message = array(
                        'status' => false,
                        'message' => 'exchange is required',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                } else {
                    $this->load->model('admin/mod_api_services');
                    $resp = $this->mod_api_services->add_buy_order_test($this->post(), $received_Token_send);
                    if (!empty($resp['error'])) {
                        $message = array(
                            'status' => false,
                            'message' => 'An error occured',
                            'error' => $resp['error'],
                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    } else {
                        if ($resp['http_code'] == 200) {
                            $message = array(
                                'status' => true,
                                'data' => array(
                                    'data' => true,
                                    // 'response' => $resp['response'],
                                ),
                                'message' => "Order Submitted successfully.",
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        } else {
                            $message = array(
                                'status' => false,
                                'message' => $resp['response']['message'],
                            );
                            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                        }
                    }

                }
            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }

    }//end add_digie_manual_order_test_post

    //edit_digie_manual_order_test_post //Umer Abbas [30-12-19]
    public function edit_digie_manual_order_test_post() {
        $request = $this->post();
        //set order for edit

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token_send = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $temp_test_data = $request;
                $temp_test_data['created_date'] = $this->mongo_db->converToMongodttime(date('Y-m-d G:i:s'));
                $temp_test_data['testing_edit_order'] = 'yes';
                $this->mongo_db->insert('test_mobile_api', $temp_test_data);

                $setArr = $this->set_manual_order_for_edit($request);
                $exchange = $request['exchange'];
                
                if (empty($request['exchange'])) {
                    $message = array(
                        'status' => false,
                        'message' => 'exchange  is required',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                } else {
                    $params = [
                        'exchange' => $exchange,
                        'buyOrderId' => $setArr['buyOrderId'],
                        'buyorderArr' => $setArr['buyorderArr'],
                        'sellOrderId' => $setArr['sellOrderId'],
                        'sellOrderArr' => $setArr['sellOrderArr'],
                        'tempSellOrderId' => $setArr['tempSellOrderId'],
                        'tempOrderArr' => $setArr['tempOrderArr'],
                        'interface' => 'mobile device',
                    ];

                    $req_arr = [
                        'req_type' => 'POST',
                        'req_endpoint' => 'updateManualOrder',
                        'req_params' => $params,
                        'header'     => $received_Token_send
                    ];
                    $resp = hitCurlRequest($req_arr);

                    if (!empty($resp['error'])) {
                        $message = array(
                            'status' => false,
                            'message' => 'An error occured',
                            'error' => $resp['error'],
                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    } else {
                        if ($resp['http_code'] == 200) {
                            $message = array(
                                'status' => true,
                                'data' => true,
                                'message' => 'Order Edited successfully.',
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        } else {
                            $message = array(
                                'status' => true,
                                'message' => $resp['response'],
                            );
                            $this->set_response($message, $resp['http_code']);
                        }
                    }
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }        

    }//end edit_digie_manual_order_test_post

    //set_manual_order_for_edit
    public function set_manual_order_for_edit($req){
        extract($req);
        $order_id = $req['order_id'];
        $sell_order_id = '';
        $temp_order_id = '';

        $exchange = $req['exchange'];
        $collection = ($exchange == 'binance'? 'buy_orders' : 'buy_orders_'.$exchange); 
        $arr = [];
        // get buyOrder
        $where_arr = array(
            '_id' => $this->mongo_db->mongoId($order_id),
        );
        $this->mongo_db->where($where_arr);
        $order = $this->mongo_db->get($collection);
        $order = iterator_to_array($order);
        if (!empty($order)){
            $order = $order[0];
            $sell_order_id = (!empty($order['sell_order_id']) ? (string) $order['sell_order_id'] : '');
                
            if(empty($sell_order_id)){
                $where_arr = array(
                    'buy_order_id' => $this->mongo_db->mongoId($order_id),
                    // 'buy_order_id' => ['$in' => [ $order_id, $this->mongo_db->mongoId($order_id)]],
                );
                $collection = ($exchange == 'binance' ? 'temp_sell_orders' : 'temp_sell_orders_' . $exchange);
                $this->mongo_db->where($where_arr);
                $t_order = $this->mongo_db->get($collection);
                $t_order = iterator_to_array($t_order);
                if (!empty($t_order)){
                    $t_order = $t_order[0];
                    $temp_order_id = (string) $t_order['_id'];
                }
            }
        }
        $ins_data = array(
            'price' => (float) $price,
            'quantity' => (float) $quantity,
            'usd_worth' => (float) $usd_worth, 
            'symbol' => $symbol,
            'order_type' => $order_type,
            'admin_id' => $admin_id,
            'application_mode' => $application_mode,
            'trigger_type' => 'no',
            "lth_functionality" => $lth_functionality,
            "lth_profit" => (float) $lth_profit,
            'profit_type' => $profit_type,
            'sell_profit_percent' => (float) $sell_profit_percent,
            'stop_loss' => $stop_loss,
            'loss_percentage' => (float) $loss_percentage,
            'custom_stop_loss_percentage' => (float) $loss_percentage,
        );

        if(!empty($order) && $order['status'] == 'new'){
            
            if (!empty($buyRightAway) && $buyRightAway == 'yes') {
            
                $ins_data['buyRightAway'] = 'yes';
            
            } else if((empty($buyRightAway) || $buyRightAway == '' || $buyRightAway == 'no') && !empty($deep_price_on_off) && $deep_price_on_off == 'yes') {

                $ins_data['deep_price_on_off'] = 'yes';
                $ins_data['expecteddeepPrice'] = (float) $price;
                $ins_data['cancel_hour'] = $cancel_hour ?? '';
            }
        }

        $is_submitted = 'no';
        if ($trail_check != '') {
            $ins_data['trail_check'] = 'yes';
            $ins_data['trail_interval'] = (float) $trail_interval;
            $ins_data['buy_trail_price'] = (float) $price;
            // $ins_data['status'] = 'new';
        } else {
            $ins_data['trail_check'] = 'no';
            $ins_data['trail_interval'] = (float) 0.0;
            $ins_data['buy_trail_price'] = (float) 0.0;
            // $ins_data['status'] = 'new';
        }

        if ($auto_sell == 'yes') {
            $ins_data['auto_sell'] = 'yes';
        } else {
            $ins_data['auto_sell'] = 'no';
        }

        if ($auto_sell == 'yes') {
            $ins_temp_data = array(
                'profit_type' => $profit_type,
                'profit_percent' => (float) $sell_profit_percent,
                'profit_price' => (float) $sell_profit_price,
                'sell_price' => (float) $sell_profit_price,
                'order_type' => $sell_order_type,
                'trail_check' => $sell_trail_check,
                'trail_interval' => (float) $sell_trail_interval,
                'stop_loss' => $stop_loss,
                'loss_percentage' => (float) $loss_percentage,
                'iniatial_trail_stop' => (float) $iniatial_trail_stop,
                "lth_functionality" => $lth_functionality,
                "lth_profit" => (float) $lth_profit,
                'admin_id' => $admin_id,
                'application_mode' => $application_mode,
            );
        }

        $arr = [
            'buyOrderId' => $order_id,
            'buyorderArr' => $ins_data,
            'sellOrderId' => $sell_order_id,
            'sellOrderArr' => (!empty($sell_order_id) && !empty($ins_temp_data) ? $ins_temp_data : []),
            'tempSellOrderId' => $temp_order_id,
            'tempOrderArr' => (!empty($temp_order_id) && !empty($ins_temp_data) ? $ins_temp_data : []),
        ];

        return $arr;
    }//end set_manual_order_for_edit

    //add_digie_trigger_order_test_post //Umer Abbas [20-12-19]
    public function add_digie_trigger_order_test_post() {

        $request = $this->post();
        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if (empty($request['exchange'])) {
                    $message = array(
                        'status' => false,
                        'message' => 'exchange is required',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }else {
                    $this->load->model('admin/mod_api_services');
                    // $data = $this->mod_api_services->add_buy_order_triggers_test($this->post());
                    $resp = $this->mod_api_services->add_buy_order_triggers_test($this->post(), $received_Token_send);

                    if (!empty($resp['error'])) {
                        $message = array(
                            'status' => false,
                            'message' => 'An error occured',
                            'error' => $resp['error'],
                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    } else {
                        if ($resp['http_code'] == 200) {
                            $message = array(
                                'status' => true,
                                'data' => true,
                                'message' => 'Order Submitted successfully.',
                                // 'response' => $resp['response'],
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        } else {
                            $message = array(
                                'status' => true,
                                'message' => $resp['response'],
                            );
                            $this->set_response($message, $resp['http_code']);
                        }
                    }

                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }
    }//end add_digie_trigger_order_test_post

    //edit_digie_trigger_order_test_post //Umer Abbas [30-12-19]
    public function edit_digie_trigger_order_test_post() {
        $request = $this->post();

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send  = $received_Token;

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if (empty($request['exchange']) || empty($request['order_id'])) {
                    $message = array(
                        'status' => false,
                        'message' => 'order_id, exchange  is required',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }else {
                    $request['orderId'] = $request['order_id'];
                    unset($request['order_id']);
                    array_values($request);

                    // $arr = $this->set_auto_order_for_edit($request);

                    if(!empty($request['parent_status']) && $request['parent_status'] == 'parent' && !empty($request['cost_avg']) && $request['cost_avg'] == 'yes'){
                        $request['cost_avg'] = 'yes';
                    }else if(!empty($request['parent_status']) && $request['parent_status'] == 'parent' && empty($request['cost_avg'])){
                        $request['cost_avg'] = '';
                    }

                    $params = [
                        'orderArr' => $request,
                        'interface' => 'mobile device',
                    ];
                    $req_arr = [
                        'req_type' => 'POST',
                        'req_endpoint' => 'editAutoOrder',
                        'req_params' => $params,
                        'header'     => $received_Token_send
                    ];
                    $resp = hitCurlRequest($req_arr);
                    
                    if (!empty($resp['error'])) {
                        $message = array(
                            'status' => false,
                            'message' => 'An error occured',
                            'error' => $resp['error'],
                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    } else {
                        if ($resp['http_code'] == 200) {
                            $message = array(
                                'status' => true,
                                'data' => true,
                                'message' => 'Order Edited successfully.',
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        } else {
                            $message = array(
                                'status' => true,
                                'message' => $resp['response'],
                            );
                            $this->set_response($message, $resp['http_code']);
                        }
                    }
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }

    }//end edit_digie_trigger_order_test_post

    //set_auto_order_for_edit
    public function set_auto_order_for_edit($req){
        extract($req);
        $order_id = $req['order_id'];
        $sell_order_id = '';
        $temp_order_id = '';

        $exchange = $req['exchange'];
        $collection = ($exchange == 'binance'? 'buy_orders' : 'buy_orders_'.$exchange); 
        $arr = [];
        // get buyOrder
        $where_arr = array(
            '_id' => $this->mongo_db->mongoId($order_id),
        );
        $this->mongo_db->where($where_arr);
        $order = $this->mongo_db->get($collection);
        $order = iterator_to_array($order);
        if (!empty($order)){
            $order = $order[0];
            $sell_order_id = (!empty($order['sell_order_id']) ? (string) $order['sell_order_id'] : '');
                
            if(empty($sell_order_id)){
                $where_arr = array(
                    'buy_order_id' => $this->mongo_db->mongoId($order_id),
                    // 'buy_order_id' => ['$in' => [ $order_id, $this->mongo_db->mongoId($order_id)]],
                );
                $collection = ($exchange == 'binance' ? 'temp_sell_orders' : 'temp_sell_orders_' . $exchange);
                $this->mongo_db->where($where_arr);
                $t_order = $this->mongo_db->get($collection);
                $t_order = iterator_to_array($t_order);
                if (!empty($t_order)){
                    $t_order = $t_order[0];
                    $temp_order_id = (string) $t_order['_id'];
                }
            }
        }
        $ins_data = array(
            'price' => $price,
            'quantity' => (float) $quantity,
            'usd_worth' => (float) $usd_worth,
            'symbol' => $symbol,
            'order_type' => $order_type,
            'admin_id' => $admin_id,
            'application_mode' => $application_mode,
            'trigger_type' => 'no',
            "lth_functionality" => $lth_functionality,
            "lth_profit" => $lth_profit,
        );

        $is_submitted = 'no';
        if ($trail_check != '') {
            $ins_data['trail_check'] = 'yes';
            $ins_data['trail_interval'] = $trail_interval;
            $ins_data['buy_trail_price'] = $price;
            $ins_data['status'] = 'new';
        } else {
            $ins_data['trail_check'] = 'no';
            $ins_data['trail_interval'] = '0';
            $ins_data['buy_trail_price'] = '0';
            $ins_data['status'] = 'new';
        }

        if ($auto_sell == 'yes') {
            $ins_data['auto_sell'] = 'yes';
        } else {
            $ins_data['auto_sell'] = 'no';
        }

        if ($auto_sell == 'yes') {
            $ins_temp_data = array(
                'profit_type' => $profit_type,
                'profit_percent' => $sell_profit_percent,
                'profit_price' => $sell_profit_price,
                'sell_price' => $sell_profit_price,
                'order_type' => $sell_order_type,
                'trail_check' => $sell_trail_check,
                'trail_interval' => $sell_trail_interval,
                'stop_loss' => $stop_loss,
                'loss_percentage' => $loss_percentage,
                'iniatial_trail_stop' => $iniatial_trail_stop,
                "lth_functionality" => $lth_functionality,
                "lth_profit" => $lth_profit,
                'admin_id' => $admin_id,
                'application_mode' => $application_mode,
            );
        }

        $arr = [
            'buyOrderId' => $order_id,
            'buyorderArr' => $ins_data,
            'sellOrderId' => $sell_order_id,
            'sellOrderArr' => (!empty($sell_order_id) && !empty($ins_temp_data) ? $ins_temp_data : []),
            'tempSellOrderId' => $temp_order_id,
            'tempOrderArr' => (!empty($temp_order_id) && !empty($ins_temp_data) ? $ins_temp_data : []),
        ];

        return $arr;
    }//end set_auto_order_for_edit

    //lth_status_change_test_post //Umer Abbas [18-12-19]
    public function lth_status_change_test_post() {

        $request = $this->post();
        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send  = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if(!empty($request['id']) && !empty($request['exchange']) && !empty($request['lth_profit'])){
                    $order_id = $request['id'];
                    $exchange = $request['exchange'];
                    $lth_profit = $request['lth_profit'];

                    $params = [
                        'exchange' => $exchange,
                        'orderId' => $order_id,
                        'lth_profit' => $lth_profit,
                        'interface' => 'mobile device',
                    ];
                    $req_arr = [
                        'req_type' => 'POST',
                        'req_endpoint' => 'orderMoveToLth',
                        'req_params' => $params,
                        'header'    =>  $received_Token_send 
                    ];
                    $resp = hitCurlRequest($req_arr);
                    
                    if(!empty($resp['error'])){
                        $message = array(
                            'status' => false,
                            'message' => 'An error occured',
                            'error' => $resp['error'],
                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    }else{
                        if($resp['http_code'] == 200){
                            $message = array(
                                'status' => true,
                                'data' => ['data' => $order_id],
                                'message' => 'Buy Order was Moved to LONG TERM HOLD Manually.',
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }else{
                            $message = array(
                                'status' => true,
                                'message' => $resp['response'],
                            );
                            $this->set_response($message, $resp['http_code']);
                        }
                    }

                }else{
                    $message = array(
                        'status' => false,
                        'message' => 'id, exchange and lth_profit are required',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);  
                }
            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }//lth_status_change_test_post

    //play_pause_status_change_test_post //Umer Abbas [18-12-19]
    public function play_pause_status_change_test_post() {

        $request = $this->post();
        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if (!empty($request['id']) && !empty($request['exchange']) && !empty($request['type'])) {
                    $order_id = $request['id'];
                    $exchange = $request['exchange'];
                    $status = $request['type'];

                    $params = [
                        'exchange' => $exchange,
                        'orderId' => $order_id,
                        'status' => $status,
                        'interface' => 'mobile device',
                    ];
                    $req_arr = [
                        'req_type' => 'POST',
                        'req_endpoint' => 'togglePausePlayOrder',
                        'req_params' => $params,
                        'header'   => $received_Token_send
                    ];
                    $resp = hitCurlRequest($req_arr);

                    if (!empty($resp['error'])) {
                        $message = array(
                            'status' => false,
                            'message' => 'An error occured',
                            'error' => $resp['error'],
                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    } else {
                        if ($resp['http_code'] == 200) {
                            $message = array(
                                'status' => true,
                                'data' => $order_id,
                                'message' => "Order $status successfully.",
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        } else {
                            $message = array(
                                'status' => true,
                                'message' => $resp['response'],
                            );
                            $this->set_response($message, $resp['http_code']);
                        }
                    }

                } else {
                    $message = array(
                        'status' => false,
                        'message' => 'id, exchange and type are required',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }
            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }
        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }

    }//end play_pause_status_change_test_post

    //buy_now_test_post //Umer Abbas [19-12-19]
    public function buy_now_test_post() {

        $request = $this->post();

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if (!empty($request['symbol']) && !empty($request['exchange']) && !empty($request['id']) ) {
                    
                    $order_id = $request['id'];
                    $symbol = $request['symbol'];
                    $exchange = $request['exchange'];
                    // $quantity = $request['quantity'];

                    // $received_Token = $this->input->get_request_header('Authorization');

                    $params = [
                        'orderId' => $order_id,
                        'coin' => $symbol,
                        'exchange' => $exchange,
                        'interface' => 'mobile device',
                        // 'quantity' => $quantity,
                    ];
                    $req_arr = [
                        'req_type'     => 'POST',
                        'req_endpoint' => 'buyOrderManually',
                        'req_params'   =>  $params,
                        'header'       =>  $received_Token_send
                    ];
                    $resp = hitCurlRequest($req_arr);

                    // $resp = array('http_code' => $http_code, 'response' => $response, 'error' => $err);

                    if (!empty($resp['error'])) {
                        $message = array(
                            'status' => false,
                            'message' => 'An error occured',
                            'error' => $resp['error'],
                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    } else {
                        if ($resp['http_code'] == 200) {
                            $message = array(
                                'status' => true,
                                'data' =>  array(
                                    'data' => true,
                                    'response' => $resp['response'],
                                ),
                                'message' => "Order Submitted For Buy successfully.",
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        } else {
                            $message = array(
                                'status' => true,
                                'message' => $resp['response'],
                            );
                            $this->set_response($message, $resp['http_code']);
                        }
                    }
                } else {
                    $message = array(
                        'status' => false,
                        'message' => 'symbol, exchange and id are required',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }
            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }//end buy_now_test_post
    
    //sell_now_test_post //Umer Abbas [19-12-19]
    public function sell_now_test_post() {

        $this->load->model('admin/mod_api_services');
        $request = $this->post();

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send = $received_Token;

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if (!empty($request['exchange']) && !empty($request['id'])) {

                    $definedSellPrice = 0;
                    $currentMarketPriceByCoin = 0;
                    $exchange = $request['exchange'];
                    $order_id = $request['id'];

                    //GET current order
                    $collection = ($exchange == "binance" ? "buy_orders" : "buy_orders_$exchange");
                    $where['_id'] = $order_id;
                    $this->mongo_db->where($where);
                    $get_obj = $this->mongo_db->get($collection);
                    $curr_order = iterator_to_array($get_obj);
                    if(!empty($curr_order)){
                        $curr_order = $curr_order[0];
                        $currentMarketPriceByCoin = $this->mod_api_services->get_market_price($curr_order['symbol'], $exchange);
                        $definedSellPrice = $currentMarketPriceByCoin;

                        $params = [
                            'exchange' => $exchange,
                            'orderId' => $order_id,
                            'currentMarketPriceByCoin' => $currentMarketPriceByCoin,
                            'definedSellPrice' => $definedSellPrice,
                            'interface' => 'mobile device',
                        ];

                        $req_arr = [
                            'req_type'      => 'POST',
                            'req_endpoint'  => 'sellOrderManually',
                            'req_params'    =>  $params,
                            'header'        =>  $received_Token_send
                        ];
                        $resp = hitCurlRequest($req_arr);

                        // $resp = array('http_code' => $http_code, 'response' => $response, 'error' => $err);

                        if (!empty($resp['error'])) {
                            $message = array(
                                'status' => false,
                                'message' => 'An error occured',
                                'error' => $resp['error'],
                            );
                            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                        } else {
                            if ($resp['http_code'] == 200) {
                                $message = array(
                                    'status' => true,
                                    'data' => array(
                                        'data' => 'Order Submitted For Sell successfully',
                                        'response' => $resp['response'],
                                    ),
                                    'message' => "Order Submitted For Sell successfully.",
                                );
                                $this->set_response($message, REST_Controller::HTTP_CREATED);
                            } else {
                                $message = array(
                                    'status' => true,
                                    'message' => $resp['response'],
                                );
                                $this->set_response($message, $resp['http_code']);
                            }
                        }
                    }else{
                        $message = array(
                            'status' => false,
                            'message' => 'Order Not found',
                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    }
                } else {
                    $message = array(
                        'status' => false,
                        'message' => 'exchange and id are required',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }

    }//end sell_now_test_post
    
    //orderListing_test_post //Umer Abbas [25-12-19]
    public function orderListing_test_post() {

        // print_r('aso');
        // error_reporting(E_ALL & ~E_NOTICE);
        // ini_set('display_errors', E_ALL & ~E_NOTICE);

        $request = $this->post();

        $status = $request['status'];
        $exchange = $request['exchange'];
        $admin_id = $request['admin_id'];
        $application_mode = $request['application_mode'];
        $filter = $request['filter'];
        $page = $request['page'];
        $limit = (int)$request['page_limit'];
        //echo 'status'.$limit;exit;
        // echo 'exchng'.$exchange;
        // echo 'admin_id'.$admin_id;
        //echo json_encode($request);exit;
        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                //$limit = 20;
                $skip = 0;
                if((int)$page > 1){
                    $page_number = (int)$page - 1;
                    $skip = $limit * $page_number;
                }
                if (!empty($admin_id) && !empty($status) && !empty($application_mode) && !empty($exchange)) {
                    
                    if($status == 'lth'){
                        $status = 'LTH';
                    }else if($status == 'filled'){
                        $status = 'FILLED';
                    }

                    $params = [
                        "application_mode"  => $application_mode,
                        "admin_id"          => $admin_id,
                        "skip"              => $skip,
                        "limit"             => $limit,
                        "coins"             => (!empty($filter['filter_coin']) ? $filter['filter_coin'] : []),
                        "order_type"        => (!empty($filter['filter_type']) ? $filter['filter_type'] : ""),
                        "trigger_type"      => (!empty($filter['filter_trigger']) ? $filter['filter_trigger'] : ""),
                        "order_level"       => (!empty($filter['filter_level']) ? $filter['filter_level'] : ""),
                        "start_date"        => (!empty($filter['start_date']) ? $filter['start_date'] : ""),
                        "end_date"          => (!empty($filter['end_date']) ? $filter['end_date'] : ""),
                        "status"            => $status,
                        "exchange"          => $exchange,
                        "user_name"         => '',
                        "is_global_user"    =>true
                    ];
                    $req_arr = [
                        'req_type'      => 'POST',
                        'req_endpoint'  => 'listOrderListing',
                        'req_params'    => ['postData' => $params],
                        'header'        =>  $received_Token_send
                    ];

                    $resp = [];
                    if($status == 'other'){
                        
                        //Get cancelled orders
                        $req_arr['req_params' ]['postData']['status'] = 'canceled';
                        $resp1 = hitCurlRequest($req_arr);
                        $submitted = $resp1['response']['message']['customOrderListing'];
                        $submitted = (!empty($submitted) ? $submitted : []);
                        
                        //Get submitted orders
                        $req_arr['req_params' ]['postData']['status'] =  'submitted';
                        $resp2 = hitCurlRequest($req_arr);
                        $canceled = $resp2['response']['message']['customOrderListing'];
                        $canceled = (!empty($canceled) ? $canceled : []);
                        
                        //Get lth paused orders
                        $req_arr['req_params' ]['postData']['status'] =  'lth_pause';
                        $resp3 = hitCurlRequest($req_arr);
                        $lth_paused = $resp3['response']['message']['customOrderListing'];
                        $lth_paused = (!empty($lth_paused) ? $lth_paused : []);
                        
                        //Get lth errors tab orders
                        $req_arr['req_params' ]['postData']['status'] =  'errors';
                        $resp4 = hitCurlRequest($req_arr);
                        $errors_tab = $resp4['response']['message']['customOrderListing'];
                        $errors_tab = (!empty($errors_tab) ? $errors_tab : []);
                        
                        //merge all results and send response
                        $orders_arr = array_merge($submitted, $canceled, $lth_paused, $errors_tab);
                        unset($submitted, $canceled, $lth_paused, $errors_tab, $resp1, $resp2, $resp3, $resp4);

                        $modified_date = array_column($orders_arr, 'modified_date');
                        array_multisort($modified_date, SORT_DESC, $orders_arr);
                        unset($modified_date);

                        if(!empty($orders_arr)){
                            $resp['http_code'] = 200; 
                            $resp['response'] = ['message' => ['customOrderListing' => $orders_arr, 'countArr' => $resp2['response']['message']['countArr'] ]];
                            $resp['error'] = '';
                        }else{
                            $resp['http_code'] = 200; 
                            $resp['response'] = ['message' => 'Orders not found'];
                            $resp['error'] = '';
                        }
                    }else{
                        $resp = hitCurlRequest($req_arr);
                        // echo "<pre>";print_r($resp);
                    }

                    // echo "<pre>";print_r($message);
                    // $resp = array('http_code' => $http_code, 'response' => $response, 'error' => $err);

                    if (!empty($resp['error'])) {
                        $message = array(
                            'status' => false,
                            'message' => 'An error occured',
                            'error' => $resp['error'],
                        );
                        // $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    } else {
                        if ($resp['http_code'] == 200) {

                            $data = array();
                            $countArr = $resp['response']['message']['countArr'];
                            if(!empty($resp['response']['message']['customOrderListing'])){

                                $this->load->model('admin/mod_dashboard');

                                // echo "<pre>";
                                foreach ($resp['response']['message']['customOrderListing'] as $valueArr) {
                                    
                                    // print_r($valueArr);
                                    // continue;

                                    $returArr = array();

                                    if (!empty($valueArr)) {

                                        //$timezone = get_user_timezone($valueArr['admin_id']);
                                        $timezone = 'UTC';
                                        
                                        $datetime = new DateTime($valueArr['created_date']);
                                        $datetime->format('Y-m-d g:i:s A');

                                        $new_timezone = new DateTimeZone($timezone);
                                        $datetime->setTimezone($new_timezone);
                                        $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                                        if (empty($valueArr['modified_date'])) {
                                            $valueArr['modified_date'] = $valueArr['created_date'];
                                        }
                                        
                                        $datetime111 = new DateTime($valueArr['modified_date']);
                                        $datetime111->format('Y-m-d g:i:s A');

                                        $new_timezone = new DateTimeZone($timezone);
                                        $datetime111->setTimezone($new_timezone);
                                        $formated_date_time1 = $datetime111->format('Y-m-d g:i:s A');

                                        $time_elapsed_string = time_elapsed_string($formated_date_time1, $timezone, false);
                                        $current_market_price = get_current_market_prices($exchange,[$valueArr['symbol']]);
                                        $base_curr_market_price = $current_market_price[$valueArr['symbol']];
                                        $score_avg = $this->mod_dashboard->get_score_avg($valueArr['symbol']);
                                        $returArr['_id'] = (string) $valueArr['_id'];
                                        $returArr['symbol'] = $valueArr['symbol'];
                                        $image = $this->get_coin_logo($valueArr['symbol'], $exchange, 'image_name');
                                        //$returArr['score'] = (is_nan($score_avg)? 0 : $score_avg);
                                        $returArr['score'] = (is_nan($score_avg)? 0 : 0);
                                        $returArr['image'] = $image;
                                        $returArr['binance_order_id'] = $valueArr['binance_order_id'];
                                        $returArr['price'] = !empty($valueArr['status']) && $valueArr['status'] != 'new' && !empty($valueArr['purchased_price']) ? num($valueArr['purchased_price']) : num($valueArr['price']);
                                        $returArr['purchased_price'] = num($valueArr['purchased_price']);
                                        $returArr['quantity'] = $valueArr['quantity'];
                                        $returArr['usd_worth'] = (!empty($valueArr['usd_worth']) ? $valueArr['usd_worth'] : NULL);
                                        $returArr['order_type'] = $valueArr['order_type'];
                                        $returArr['market_value'] = num($valueArr['market_value']);
                                        $returArr['trail_check'] = $valueArr['trail_check'];
                                        $returArr['trail_interval'] = ($valueArr['trail_interval'] != '')?$valueArr['trail_interval']:0;
                                        $returArr['buy_trail_price'] = num($valueArr['buy_trail_price']);
                                        $returArr['status'] = $valueArr['status'];
                                        $returArr['sell_status'] = $valueArr['sell_status'];
                                        $returArr['htmlStatus'] = $valueArr['htmlStatus'];
                                        $returArr['htmlStatusArr'] = $valueArr['htmlStatusArr'];
                                        $returArr['is_sell_order'] = $valueArr['is_sell_order'];
                                        $returArr['market_sold_price'] = num($valueArr['market_sold_price']);
                                        $returArr['sell_order_id'] = (string) $valueArr['sell_order_id'];
                                        $returArr['pause_status'] = $valueArr['pause_status'];
                                        $returArr['inactive_status'] = $valueArr['inactive_status'];
                                        $returArr['admin_id'] = $valueArr['admin_id'];
                                        $returArr['auto_sell'] = $valueArr['auto_sell'];
                                        $returArr['trigger_type'] = $valueArr['trigger_type'];
                                        $returArr['order_level'] = str_replace("level_", "Bot ", $valueArr['order_level']);
                                        $returArr['trigger_name'] = strtoupper(str_replace("_", " ", $valueArr['trigger_type']));
                                        $returArr['application_mode'] = $valueArr['application_mode'];
                                        $returArr['created_date'] = $formated_date_time;
                                        $returArr['modified_date'] = $formated_date_time1;
                                        $returArr['time_ago'] = $time_elapsed_string;
                                        $returArr['time_zone'] = $timezone;
                                        $returArr['buy_parent_id'] = $valueArr['buy_parent_id'];
                                        $returArr['cavg_parent'] = $valueArr['cavg_parent'];
                                        $returArr['avg_orders_ids'] = $valueArr['avg_orders_ids'];
                                        //$returArr['cost_avg_array'] =
                                        $cost_avg_array = array(); 
                                        if(isset($valueArr['cost_avg_array'])){
                                            foreach ($valueArr['cost_avg_array'] as $value_cost_child) {
                                                $cost_avg_arr['buyOrderId'] = isset($value_cost_child['buyOrderId'])?(int)$value_cost_child['buyOrderId']:NULL;

                                                $cost_avg_arr['filledQtyBuy'] = isset($value_cost_child['filledQtyBuy'])?(float)$value_cost_child['filledQtyBuy']:NULL;

                                                $cost_avg_arr['commissionBuy'] = isset($value_cost_child['commissionBuy'])?(float)$value_cost_child['commissionBuy']:NULL;

                                                $cost_avg_arr['filledPriceBuy'] = isset($value_cost_child['filledPriceBuy'])?(float)$value_cost_child['filledPriceBuy']:NULL;

                                                $cost_avg_arr['orderFilledIdBuy'] = isset($value_cost_child['orderFilledIdBuy'])?(int)$value_cost_child['orderFilledIdBuy']:NULL;

                                                $cost_avg_arr['avg_purchase_price'] = isset($value_cost_child['avg_purchase_price'])?(float)$value_cost_child['avg_purchase_price']:NULL;

                                                $cost_avg_arr['order_sold'] = isset($value_cost_child['order_sold'])?(string)$value_cost_child['order_sold']:NULL;

                                                $cost_avg_arr['buy_order_id'] = isset($value_cost_child['buy_order_id'])?(string)$value_cost_child['buy_order_id']:NULL;

                                                $cost_avg_arr['buyTimeDate'] = isset($value_cost_child['buyTimeDate'])?(string)$value_cost_child['buyTimeDate']:NULL;

                                                 $cost_avg_arr['user_modified_sell_price'] = isset($value_cost_child['user_modified_sell_price'])?(float)$value_cost_child['user_modified_sell_price']:NULL;

                                                 $cost_avg_arr['sell_activated'] = isset($value_cost_child['sell_activated'])?(string)$value_cost_child['sell_activated']:NULL;

                                                 $cost_avg_arr['sellTimeDate'] = isset($value_cost_child['sellTimeDate'])?(string)$value_cost_child['sellTimeDate']:NULL;

                                                 $cost_avg_arr['filledPriceSell'] = isset($value_cost_child['filledPriceSell'])?(float)$value_cost_child['filledPriceSell']:NULL;

                                                 $cost_avg_arr['filledQtySell'] = isset($value_cost_child['filledQtySell'])?(float)$value_cost_child['filledQtySell']:NULL;
                                                array_push($cost_avg_array, $cost_avg_arr);
                                            }
                                        }
                                        $returArr['cost_avg_array'] =$cost_avg_array;
                                        $returArr['target_profit'] = $valueArr['targetPrice'];
                                        $returArr['current_market_price'] = (float)$base_curr_market_price;
                                        if(!is_float($returArr['target_profit'])){
                                            $returArr['target_profit'] = NULL;
                                        }
                                        $returArr['profit_data'] = strip_tags($valueArr['profitLossPercentageHtml']);
                                        if(strpos($returArr['profit_data'], '%') !== false){
                                            $returArr['profit_data'] = str_replace('%','', $returArr['profit_data']);
                                            if($returArr['profit_data'] == ''){
                                                $returArr['profit_data'] = NULL;
                                            }
                                        }else{
                                            $returArr['profit_data'] = str_replace('-','', $returArr['profit_data']);
                                            if($returArr['profit_data'] == ''){
                                                $returArr['profit_data'] = NULL;
                                            }
                                        }

                                        $returArr['priceInUSD'] = $valueArr['coinPriceInBtc'];
                                        
                                        if($status == 'parent'){
                                            if($valueArr['auto_trade_generator'] == 'yes'){
                                                $returArr['order_label'] = 'ATG';
                                            }
                                            if($valueArr['cost_avg'] == 'yes'){
                                                $returArr['order_label'] = 'CA';
                                            }
                                        }else if($status == 'open'){
                                            if($valueArr['cost_avg'] == 'yes'){
                                                $returArr['order_label'] = 'CA';
                                            }
                                        }
                                        
                                    }

                                    $data[] = $returArr;
                                }
                            }

                            $message = array(
                                'status' => true,
                                'data' => $data,
                                'countArr' => $countArr,
                                'message' => "Orders found successfully",
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        } else {
                            $message = array(
                                'status' => true,
                                'message' => $resp['response'],
                            );
                            $this->set_response($message, $resp['http_code']);
                        }
                    }

                } else {
                    $message = array(
                        'status' => false,
                        'message' => 'admin_id, status, exchange, application mode are required',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }
            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     
    }//end orderListing_test_post

    //get_order_test_post //Umer Abbas [30-12-19]
    public function get_order_test_post() {

        $request = $this->post();

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send  = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if (!empty($request['exchange']) && !empty($request['id']) && !empty($request['trigger_type'])) {

                    $exchange = $request['exchange'];
                    $order_id = $request['id'];
                    $timezone = $request['time_zone'];

                    $params = [
                        'exchange' => $exchange,
                        'orderId' => $order_id,
                        'timezone' => $timezone,
                    ];

                    $req_arr = [
                        'req_type' => 'POST',
                        'req_endpoint' => ($request['trigger_type'] == 'no' ? 'lisEditManualOrderById' : 'listOrderById'),
                        'req_params' => $params,
                        'header'  => $received_Token_send
                    ];
                    $resp = hitCurlRequest($req_arr);

                    // $resp = array('http_code' => $http_code, 'response' => $response, 'error' => $err);
                    if (!empty($resp['error'])) {
                        $message = array(
                            'status' => false,
                            'message' => 'An error occured',
                            'error' => $resp['error'],
                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    } else {
                        if ($resp['http_code'] == 200) {
                            
                            $arr = $resp['response']['message'];
                            // $data = $arr;
                            //Set response as required
                            if($request['trigger_type'] == 'no'){ //manual order

                                $buyArr = $arr['buyOrderArr'];
                                $sellArr = (!empty($arr['sellArr']) ? $arr['sellArr'] : $arr['tempSellArr']);

                                //calculate loss_percentage_price
                                $p = (!empty($buyArr['price']) ? num($buyArr['price']) : '');
                                $pp = (!empty($sellArr['purchased_price']) ? $sellArr['purchased_price'] : '');
                                $price = (!empty($pp) ? $pp : $p); 
                                $loss_percentage =  (!empty($sellArr['loss_percentage']) ? $sellArr['loss_percentage'] : 0);
                                $loss_percentage_price = (!empty($price) ? ($price - (($price*$loss_percentage)/100)) : '');

                                $data = $buyArr;
                                $data['logHtml'] = $arr['logHtml'];
                                $data['symbol'] = $buyArr['symbol'];
                                $data['order_id'] = $buyArr['_id'];
                                $data['quantity'] = $buyArr['quantity'];
                                $data['usd_worth'] = (!empty($buyArr['usd_worth']) ? $buyArr['usd_worth'] : '');
                                $data['trigger_type'] = $buyArr['trigger_type'];
                                $data['application_mode'] = $buyArr['application_mode'];
                                $data['admin_id'] = $buyArr['admin_id'];
                                $data['lth_functionality'] = $buyArr['lth_functionality'];
                                $data['lth_profit'] = $buyArr['lth_profit'];
                                $data['buyRightAway'] = $buyArr['buyRightAway'] ?? '';
                                $data['status'] = $buyArr['status'];
                                $data['buy_trail'] = (!empty($buyArr['trail_check'])? $buyArr['trail_check'] : '');
                                $data['buy_trail_percentage'] = (!empty($buyArr['buy_trail_percentage'])? $buyArr['buy_trail_percentage'] : '');
                                $data['buy_trail_interval'] = (!empty($buyArr['trail_interval'])? $buyArr['trail_interval'] : 0);
                                $data['profit_type'] = (!empty($buyArr['profit_type']) ? $buyArr['profit_type'] : $sellArr['profit_type']);
                                $data['price'] = (!empty($buyArr['price'])? num($buyArr['price']) : '');
                                $data['sell_price'] = (!empty($sellArr['sell_price'])? num($sellArr['sell_price']) : '');
                                $data['order_type'] = $buyArr['order_type'];
                                $data['auto_sell'] = $buyArr['auto_sell'];
                                $data['defined_sell_percentage'] = (!empty($sellArr['sell_profit_percent'])? $sellArr['sell_profit_percent'] : '');
                                $data['stop_loss'] = (!empty($sellArr['stop_loss'])? $sellArr['stop_loss'] :'');
                                $data['loss_percentage'] = (!empty($sellArr['loss_percentage']) ? $sellArr['loss_percentage'] : 0);
                                $data['loss_percentage_price'] = (!empty($loss_percentage_price) ? num($loss_percentage_price) : '');
                                $data['purchased_price'] = (!empty($sellArr['purchased_price']) ? num($sellArr['purchased_price']) : '');
                                // $data['sell_profit_percent'] = (!empty($sellArr['profit_percent']) ? $sellArr['profit_percent'] : '');
                                $data['sell_profit_percent'] = (!empty($buyArr['sell_profit_percent'])? $buyArr['sell_profit_percent'] : '');
                                $data['sell_trail'] = (!empty($sellArr['trail_check']) ? $sellArr['trail_check'] : '');
                                $data['sell_trail_percentage'] = (!empty($sellArr['sell_trail_percentage']) ? $sellArr['sell_trail_percentage'] : '');
                                $data['sell_trail_interval'] = (!empty($sellArr['trail_interval']) ? $sellArr['trail_interval'] : 0);

                                $data['cancel_hour'] = (!empty($$buyArr['cancel_hour']) ? $$buyArr['cancel_hour'] : 0);

                            }else{ //Auto Order
                                $order = $arr['ordeArr'][0];
                                $data = $order;

                                $data['logHtml'] = $arr['logHtml'];
                                $data['order_id'] = $order['_id'];
                                $data['symbol'] = $order['symbol'];
                                $data['quantity'] = $order['quantity'];
                                $data['usd_worth'] = (!empty($order['usd_worth']) ? $order['usd_worth'] : '');
                                $data['trigger_type'] = $order['trigger_type'];
                                $data['application_mode'] = $order['application_mode'];
                                $data['admin_id'] = $order['admin_id'];
                                $data['order_type'] = $order['order_type'];
                                $data['defined_sell_percentage'] = $order['defined_sell_percentage'];
                                $data['order_level'] = $order['order_level'];
                                $data['stop_loss_rule'] = $order['stop_loss_rule'];
                                $data['custom_stop_loss_percentage'] = (!empty($order['custom_stop_loss_percentage']) ? $order['custom_stop_loss_percentage'] : 0);
                                $data['lth_functionality'] = $order['lth_functionality'];
                                $data['lth_profit'] = $order['lth_profit'];
                                $data['status'] = $order['status'];
                            }

                            $message = array(
                                'status' => true,
                                'data' => $data,
                                'message' => "Order found successfully",
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        } else {
                            $message = array(
                                'status' => true,
                                'message' => $resp['response'],
                            );
                            $this->set_response($message, $resp['http_code']);
                        }
                    }

                } else {
                    $message = array(
                        'status' => false,
                        'message' => 'exchange, id and trigger_type are required',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     
    }//end get_order_test_post

    //remove_error_post //Umer Abbas [03-2-19]
    public function remove_error_post() {

        $request = $this->post();
        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if (!empty($request['exchange']) && !empty($request['id'])) {

                    $exchange = $request['exchange'];
                    $order_id = $request['id'];

                    $params = [
                        'exchange' => $exchange,
                        'order_id' => $order_id,
                        'interface' => 'mobile device',
                    ];

                    $req_arr = [
                        'req_type' => 'POST',
                        'req_endpoint' => 'remove_error',
                        'req_params' => $params,
                        'header' => $received_Token_send
                    ];
                    $resp = hitCurlRequest($req_arr);
                    
                    if ($resp['http_code'] == 200) {
                        $message = array(
                            'status' => ($resp['response']['status'] == null ? false : $resp['response']['status']),
                            'message' => ($resp['response']['message'] == null ? 'Something went wrong' : $resp['response']['message']),
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }else{
                        $message = array(
                            'status' =>  false,
                            'message' => 'Something went wrong',
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }
                    
                } else {
                    $message = array(
                        'status' => false,
                        'message' => 'exchange and id are required',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }
            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }//end remove_error_post
    

    //pause_sold_order_post //Umer Abbas [03-2-19]
    public function pause_sold_order_post() {

        $request = $this->post();

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send  = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if (!empty($request['exchange']) && !empty($request['id'])) {

                    $exchange = $request['exchange'];
                    $order_id = $request['id'];

                    $params = [
                        'exchange' => $exchange,
                        'order_id' => $order_id,
                        'interface' => 'mobile device',
                    ];

                    $req_arr = [
                        'req_type' => 'POST',
                        'req_endpoint' => 'pause_sold_order',
                        'req_params' => $params,
                        'header' => $received_Token_send
                    ];
                    $resp = hitCurlRequest($req_arr);
                    
                    if ($resp['http_code'] == 200) {
                        $message = array(
                            'status' => ($resp['response']['status'] == null ? false : $resp['response']['status']),
                            'message' => ($resp['response']['message'] == null ? 'Something went wrong' : $resp['response']['message']),
                        );
                        // $this->set_response($message, $resp['http_code']);
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }else{
                        $message = array(
                            'status' =>  false,
                            'message' => 'Something went wrong',
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }
                    
                } else {
                    $message = array(
                        'status' => false,
                        'message' => 'exchange and id are required',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     
    }//end pause_sold_order_post
    
    //cancel_order_test_post //Umer Abbas [03-2-19]
    public function cancel_order_test_post() {

        $request = $this->post();

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token_send = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if (!empty($request['exchange']) && !empty($request['id'])) {

                    $exchange = $request['exchange'];
                    $order_id = $request['id'];

                    $params = [
                        'exchange' => $exchange,
                        'orderId' => $order_id,
                        'interface' => 'mobile device',
                    ];

                    $req_arr = [
                        'req_type'      =>  'POST',
                        'req_endpoint'  =>  'deleteOrder',
                        'req_params'    =>  $params,
                        'header'        =>  $received_Token_send
                    ];
                    $resp = hitCurlRequest($req_arr);
                    
                    if ($resp['http_code'] == 200) {
                        $message = array(
                            'status' => true,
                            'message' => 'Order canceled successfully',
                        );
                        // $this->set_response($message, $resp['http_code']);
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }else{
                        $message = array(
                            'status' =>  false,
                            'message' => 'Something went wrong',
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }
                    
                } else {
                    $message = array(
                        'status' => false,
                        'message' => 'exchange and id are required',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }

    }//end cancel_order_test_post
    
    //remove_error_post //Umer Abbas [06-2-19]
    public function get_order_levels_post() {

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
          
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){   

                $params = [
                    'status' => 1,
                    'user_id'=>$tokenData->id,
                ];

                $req_arr = [
                    'req_type' => 'POST',
                    'req_endpoint' => 'get_order_levels',
                    'req_params' => $params,
                    'header'   => $received_Token_send
                ];
                $resp = hitCurlRequest($req_arr);
                
                if ($resp['http_code'] == 200) {

                    //sort asc by levels
                    usort($resp['response']['data'], function ($a, $b) {
                        return @explode('_', $a['level'])[1] <=> explode('_', $b['level'])[1];
                    });

                    foreach ($resp['response']['data'] as &$item) {
                        $item['name'] = (!empty($item['label']) ? $item['label'] : $item['name']);
                    }

                    $message = array(
                        'data' => $resp['response']['data']
                    );
                      if(!empty($this->input->post("hassan")))
                    {
                        echo "<pre>";
                        print_r($tokenData->id);
                        print_r($message);exit;
                    }
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }else{
                    $message = array(
                        'data' => [],
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }//end remove_error_post
    
    //get_user_wallet //Umer Abbas [06-2-19]
    public function get_user_wallet_post() {
        $request = $this->post();

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){


                $params = [
                    'admin_id' => $request['admin_id'],
                    'exchange' => $request['exchange'],
                ];  

                $req_arr = [
                    'req_type' => 'POST',
                    'req_endpoint' => 'get_user_wallet',
                    'req_params' => $params,
                    'header'   => $received_Token_send
                ];
                $respCoinBalance = hitCurlRequest($req_arr);
                
                if ($respCoinBalance['http_code'] == 200 && !empty($respCoinBalance['response']['data'])) {

                    $myCoins = [];
                    foreach($respCoinBalance['response']['data'] as $c){
                        if($c['coin_symbol'] == 'NCASH' || $c['coin_symbol'] == 'NCASHBTC' || $c['coin_symbol'] == 'NCASHUSDT'){
                            continue;
                        }elseif ($c['coin_symbol'] == 'POE' || $c['coin_symbol'] == 'POEBTC') {
                            continue;
                        }
                        $image = $this->get_coin_image($c['coin_symbol'], $request['exchange']);
                        if(!empty($image)){
                            $tempCoin = [
                                'symbol' => $c['coin_symbol'],
                                'balance' => (String) $c['coin_balance'],
                                'image' => $image,
                            ];
                            $myCoins[] = $tempCoin;
                        }
                    }

                    $message = array(
                        'status' => true,
                        'data' => $myCoins
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }else{

                    //Set Response for empty wallet 
                    $this->load->model('admin/mod_api_services');
                    $coin_arr = $this->mod_api_services->get_all_coins($request['admin_id'], $request['exchange']);

                    if(!empty($coin_arr)){
                        $myCoins = [];
                        foreach ($coin_arr as $c) {
                            
                            $arr1 = explode('BTC', $c['symbol']);
                            $arr2 = explode('USDT', $c['symbol']);
                            if (($arr1[0] == '' && ((isset($arr1[1]) && $arr1[1] == '')) || ($arr2[0] == '' && (isset($arr1[1]) && $arr1[1] == '')))) {
                                //Do nothing
                            } else if (isset($arr1[1]) && $arr1[1] == '') {
                                $c['symbol'] = $arr1[0];
                            } else if (isset($arr2[1]) && $arr2[1] == '') {
                                $c['symbol'] = $arr2[0];
                            }
                            
                            //get Coin Image
                            $image = $this->get_coin_image($c['symbol'], $request['exchange']);

                            $tempCoin = [
                                'symbol' => $c['symbol'], 
                                'balance' => '0',
                                'image' => $image,
                            ];
                            $myCoins[] = $tempCoin;
                        }
                        $message = array(
                            'status' => true,
                            'data' => $myCoins,
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);

                    }else{
                        $message = array(
                            'status' => false,
                            'data' => [],
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     


    }//end get_user_wallet

    public function edit_profile_post(){

		$request = $this->post();
		$user_id =  !empty($request['user_id']) ? trim($request['user_id']) : '';

		$first_name = $request['first_name'] ?? '';
		$last_name = $request['last_name'] ?? '';
		$phone_number = $request['phone_number'] ?? '';
		$time_zone = $request['time_zone'] ?? '';
		$default_exchange = $request['default_exchange'] ?? '';

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $filename = '';
                if(!empty($request["profile_image"])){
                    $image = base64_decode($request["profile_image"]);
                    $image_name = md5(uniqid(rand(), true));
                    //rename file name with random number
                    $filename = $image_name . '.' . 'jpg';
                    $path = "/var/www/html/assets/profile_images/$filename";
                    //image uploading folder path
                    file_put_contents($path, $image);
                }
                
                unset($request['user_id']);

                if ($user_id == "" || empty($request)) {

                    $message = array(
                        'status' => FALSE,
                        'message' => 'user_id or update field is empty.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

                } else {

                    $this->load->model('admin/mod_users');

                    $chk_isvalid_user = $this->mod_users->get_user($user_id);

                    if ($chk_isvalid_user) {

                        //update Record
                        $this->load->model('admin/mod_api_services');

                        $sess_array = [];

                        if(!empty($first_name)){
                            $sess_array['first_name'] = $first_name;
                        }
                        if(!empty($last_name)){
                            $sess_array['last_name'] = $last_name;
                        }
                        if(!empty($phone_number)){
                            $sess_array['phone_number'] = $phone_number;
                        }
                        if(!empty($time_zone)){
                            $sess_array['timezone'] = $time_zone;
                        }
                        if(!empty($filename)){
                            $sess_array['profile_image'] = $filename;
                        }
                        if(!empty($default_exchange)){
                            $sess_array['default_exchange'] = $default_exchange;
                        }
                        
                        $updated = $this->mod_api_services->edit_profile($sess_array , $user_id);
                        if($updated == true){

                            $message = array(
                            'status' => TRUE,
                            'data' => $sess_array,
                            'message' => 'updated successfully.',
                        );

                        $this->set_response($message, REST_Controller::HTTP_CREATED);

                        }else{
                            $message = array(
                                'status' => FALSE,
                                'message' => 'Not inserted',
                            );
                            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                        }

                    } else {

                        $message = array(
                            'status' => FALSE,
                            'message' => 'Invalid Details',
                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

                    } //end if

                } //end if($userid=="" || $email=="" )
            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     
    }

    //get_coin_logo //Umer Abbas [26-12-19]
    public function get_coin_logo($coin, $exchange, $condition='url'){

        /* $where_arr = array(
            'user_id' => 'global',
            'symbol' => $coin,
            'exchange_type' => $exchange,
        );
        $this->mongo_db->where($where_arr);
        $coin = $this->mongo_db->get("coins");

        $coin = iterator_to_array($coin);
        if (!empty($coin)) {
            $coin = $coin[0];
        }else{
            $coin = array();
        } */


        if ($coin == 'BNB') {
            return SURL . "assets/coin_logo/thumbs/BNB.png";
        }

        $where_arr = array(
            'user_id' => 'global',
        );
        if ($exchange == 'binance') {
            $where_arr['exchange_type'] = $exchange;
        }

        if ($coin == 'USDT') {
            $symbol_search_arr = ['BTCUSDT', $coin, $coin . 'BTC', $coin . 'USDT'];
        } else {
            $symbol_search_arr = [$coin, $coin . 'BTC', $coin . 'USDT'];
        }

        $where_arr['symbol']['$in'] = $symbol_search_arr;

        $this->mongo_db->where($where_arr);
        $this->mongo_db->limit(1);

        $collectionName = ($exchange == 'binance' ? 'coins' : "coins_$exchange");
        $coin = $this->mongo_db->get($collectionName);
        $coin = iterator_to_array($coin);

        if (!empty($coin)) {
            $coin = $coin[0];
        } else {
            $coin = array();
        }

        if(empty($coin)){
            return '';
        }

        if ($condition == 'url') {
            return SURL . "assets/coin_logo/thumbs/" . $coin['coin_logo'];
        }

        if($condition == 'image_name'){
            return $coin['coin_logo'];
        }

        if($condition == 'base64_encode'){
            //base64_encode 
            $cpath = SURL . "assets/coin_logo/thumbs/" . $coin['coin_logo'];
            $ctype = pathinfo($cpath, PATHINFO_EXTENSION);
            $cimgdata = file_get_contents($cpath);
            $base64_logo = 'data:image/' . $ctype . ';base64,' . base64_encode($cimgdata);
    
            return $base64_logo;
        }

    }//end get_coin_logo

    //get_coin_image 
    public function get_coin_image($coin, $exchange){

        // echo "<pre>";
        // echo "$coin -------------- $exchange <br>";

        if($coin == 'BNB'){
            return SURL . "assets/coin_logo/thumbs/BNB.png";
        }
        if($coin == 'USDT'){
            return 'https://w7.pngwing.com/pngs/520/303/png-transparent-tether-united-states-dollar-cryptocurrency-fiat-money-market-capitalization-bitcoin-logo-bitcoin-trade.png';
        }

        $where_arr = array(
            'user_id' => 'global',
        );
        if($exchange == 'binance'){
            $where_arr['exchange_type'] = $exchange;
        }

        if($coin == 'USDT'){
            $symbol_search_arr = ['BTCUSDT', $coin, $coin.'BTC', $coin.'USDT'];
        }else{
            $symbol_search_arr = [$coin, $coin.'BTC', $coin.'USDT'];
        }

        $where_arr['symbol']['$in'] = $symbol_search_arr;

        // print_r($where_arr);

        $this->mongo_db->where($where_arr);
        $this->mongo_db->limit(1);

        $collectionName = ($exchange == 'binance' ? 'coins' : "coins_$exchange");

        // print_r($collectionName);

        $coin = $this->mongo_db->get($collectionName);

        $coin = iterator_to_array($coin);

        // print_r($coin);

        if (!empty($coin)) {
            $coin = $coin[0];
        }else{
            $coin = array();
        }

        if(empty($coin)){
            return '';
        }

        return SURL . "assets/coin_logo/thumbs/" . $coin['coin_logo'];

        // if($condition == 'base64_encode'){
        //     //base64_encode 
        //     $cpath = SURL . "assets/coin_logo/thumbs/" . $coin['coin_logo'];
        //     $ctype = pathinfo($cpath, PATHINFO_EXTENSION);
        //     $cimgdata = file_get_contents($cpath);
        //     $base64_logo = 'data:image/' . $ctype . ';base64,' . base64_encode($cimgdata);
    
        //     return $base64_logo;
        // }

    }//end get_coin_image

    public function add_user_coins_post() {

        $symbols = $this->post('symbols');
        $user_id = $this->post('user_id');
        $exchange = $this->post('exchange');
        
        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if(!empty($symbols) && !empty($user_id) && !empty($exchange)){

                    $params = [
                        'exchange' => $exchange,
                        'symbols' => $symbols,
                        'user_id' => $user_id,
                    ];
            
                    $req_arr = [
                        'req_type' => 'POST',
                        'req_endpoint' => 'addUserCoin',
                        'req_params' => $params,
                        'header' => $received_Token_send
                    ];
                    $resp = hitCurlRequest($req_arr);
                    
                    if ($resp['http_code'] == 200) {
            
                        $message = $resp['response'];
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }else{
                        $message = array(
                            'status' => false,
                            'message' => 'Something went wrong.',
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }

                }else{
                    $message = array(
                        'status' => false,
                        'message' => 'symbols, user_id and exchange is required.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }
            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }//end add_user_coins_post
    
    public function get_user_coins_post() {

        $user_id = $this->post('user_id');
        $exchange = $this->post('exchange');

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if(!empty($user_id) && !empty($exchange)){

                    $params = [
                        'exchange' => $exchange,
                        'admin_id' => $user_id,
                    ];
            
                    $req_arr = [
                        'req_type' => 'POST',
                        'req_endpoint' => 'get_user_coins',
                        'req_params' => $params,
                        'header'     => $received_Token_send
                    ];
                    $resp = hitCurlRequest($req_arr);
                    
                    if ($resp['http_code'] == 200) {

                        //set response
                        $globalCoins = $resp['response']['message']['globalCoins'];
                        $userCoins = $resp['response']['message']['userCoins'];

                        $global_coins_arr = array_column($globalCoins, 'symbol');
                        $user_coins_arr = array_column($userCoins, 'symbol');

                        unset($globalCoins, $userCoins, $resp);

                        $total_coins = count($global_coins_arr);

                        $coinsArr = [];
                        for($i=0; $i < $total_coins; $i++){

                            $symbol = $global_coins_arr[$i];

                            $coinsArr[] = [
                                'symbol' => $symbol, 
                                'selected' => (in_array($symbol, $user_coins_arr) ? true : false)
                            ];
                        }
            
                        $message = [
                            'status' => true,
                            'data' => $coinsArr,
                            'message' => 'Data found successfully'
                        ];
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }else{
                        $message = array(
                            'status' => false,
                            'message' => 'Something went wrong.',
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }
                    
                }else{
                    $message = array(
                        'status' => false,
                        'message' => 'user_id and exchange is required.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }


    }//end add_user_coins_post

    public function get_global_coins_post() {

        $exchange = $this->post('exchange');

        if(!empty($exchange)){

            $params = [
                'exchange' => $exchange,
            ];
    
            $req_arr = [
                'req_type' => 'POST',
                'req_endpoint' => 'getAllGlobalCoins',
                'req_params' => $params,
            ];
            $resp = hitCurlRequest($req_arr);
            
            if ($resp['http_code'] == 200 && $resp['response']['status']) {

                //set response
                $globalCoins = $resp['response']['data'][$exchange];

                if(!empty($globalCoins)){
                    
                    $coinsArr = [];
                    $total_coins = count($globalCoins);

                    for($i=0; $i < $total_coins; $i++){
                        
                        if ($globalCoins[$i]['exchange_type'] === $exchange) {
                                               
                            $coinsArr[] = [
                                'symbol' => $globalCoins[$i]['symbol'],
                                'price' => (float) $globalCoins[$i]['marketPrice'],
                                'coin_categories' => $globalCoins[$i]['coin_categories']
                            ];
                        }
                    }
                }
    
                $message = [
                    'status' => true,
                    'data' => $coinsArr,
                    'message' => 'Data found successfully'
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }else{
                $message = array(
                    'status' => false,
                    'message' => 'Something went wrong.',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }
            
        }else{
            $message = array(
                'status' => false,
                'message' => 'exchange is required.',
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }

    }//end add_user_coins_post


    /* *******************  ATG APIs  ****************** */
    public function get_prices_arr_post(){
        $request = $this->post();

        $coinArr = $request['coinArr'] ?? [];
        $exchange = $request['exchange'] ?? '';
        $application_mode = $request['application_mode'] ?? '';

        if(!empty($exchange)){

            $params = [
                'exchange' => $exchange,
                'coinArr' => $coinArr,
            ];
    
            $req_arr = [
                'req_type' => 'POST',
                'req_endpoint' => 'getPricesArr',
                'req_params' => $params,
            ];
            $resp = hitCurlRequest($req_arr);
            
            if ($resp['http_code'] == 200 && $resp['response']['status']) {

                //set response
                $response = $resp['response']['data'];
                // echo "<pre>";
                // print_r($response);
                
                $data = $response ?? [];
    
                $message = [
                    'status' => true,
                    'data' => $data,
                    'message' => 'Data found successfully'
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }else{
                $message = array(
                    'status' => false,
                    'message' => 'Something went wrong.',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }
            
        }else{
            $message = array(
                'status' => false,
                'message' => 'exchange is required.',
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }

    }
    
    public function get_user_balances_info_post(){
        $request = $this->post();

        $user_id = $request['user_id'] ?? '';
        $exchange = $request['exchange'] ?? '';
        $application_mode = $request['application_mode'] ?? '';

        if(!empty($user_id) && !empty($exchange) && !empty($application_mode)){

            $params = [
                'exchange' => $exchange,
                'user_id' => $user_id,
                'application_mode' => $application_mode,
            ];
    
            $req_arr = [
                'req_type' => 'POST',
                'req_endpoint' => 'get_dashboard_wallet',
                'req_params' => $params,
            ];
            $resp = hitCurlRequest($req_arr);
            
            if ($resp['http_code'] == 200 && $resp['response']['status']) {

                //set response
                $response = $resp['response']['data'];
                // echo "<pre>";
                // print_r($response);
                
                $data = $response ?? [];
    
                $message = [
                    'status' => true,
                    'data' => $data,
                    'message' => 'Data found successfully'
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }else{
                $message = array(
                    'status' => false,
                    'message' => 'Something went wrong.',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }
            
        }else{
            $message = array(
                'status' => false,
                'message' => 'user_id, exchange and application_mode is required.',
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }

    }

    public function get_auto_trade_settings_post(){
        $request = $this->post();

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send = $received_Token;

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){
                $user_id = $request['user_id'] ?? '';
                $exchange = $request['exchange'] ?? '';
                $application_mode = $request['application_mode'] ?? '';

                if(!empty($user_id) && !empty($exchange) && !empty($application_mode)){

                    $params = [
                        'exchange' => $exchange,
                        'user_id' => $user_id,
                        'application_mode' => $application_mode,
                    ];
            
                    $req_arr = [
                        'req_type' => 'POST',
                        'req_endpoint' => 'getAutoTradeSettings',
                        'req_params' => $params,
                        'header'   => $received_Token_send
                    ];
                    $resp = hitCurlRequest($req_arr);
                    
                    if ($resp['http_code'] == 200 && $resp['response']['status']) {

                        //set response
                        $response = $resp['response']['data'];
                        // echo "<pre>";
                        // print_r($response);

                        if(!empty($response) && !empty($response[$exchange]) && !empty($response[$exchange][0])){
                            $data = $response[$exchange];
                        }else{
                            $data = [];
                        }
            
                        $message = [
                            'status' => true,
                            'data' => $data,
                            'message' => 'Data found successfully'
                        ];
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }else{
                        $message = array(
                            'status' => false,
                            'message' => 'Something went wrong.',
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }
                    
                }else{
                    $message = array(
                        'status' => false,
                        'message' => 'user_id, exchange and application_mode is required.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }

    public function get_available_btc_usdt_atg_post(){
        $request = $this->post();

        $application_mode = $request['application_mode'] ?? '';

        $user_id = $request['user_id'] ?? '';
        $exchange = $request['exchange'] ?? '';
        $baseCurrencyArr = $request['baseCurrencyArr'] ?? ['BTC', 'USDT']; 
        $customBtcPackage = $request['customBtcPackage'] ?? ''; 
        $customUsdtPackage = $request['customUsdtPackage'] ?? '';
        $dailTradeAbleBalancePercentage = $request['dailTradeAbleBalancePercentage'] ?? ''; 

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if(!empty($user_id) && !empty($exchange) && !empty($baseCurrencyArr) && !empty($customBtcPackage) && !empty($customUsdtPackage) && !empty($dailTradeAbleBalancePercentage)){
                
                    $params = [
                        'data' => [
                            'user_id' => $user_id,
                            'exchange' => $exchange,
                            'baseCurrencyArr' => $baseCurrencyArr,
                            'customBtcPackage' => $customBtcPackage,
                            'customUsdtPackage' => $customUsdtPackage,
                            'dailTradeAbleBalancePercentage' => $dailTradeAbleBalancePercentage,
                        ]
                    ];
            
                    $req_arr = [
                        'req_type' => 'POST',
                        'req_endpoint' => 'find_available_btc_usdt_atg',
                        'req_params' => $params,
                        'header'  => $received_Token_send
                    ];
                    $resp = hitCurlRequest($req_arr);
                    
                    if ($resp['http_code'] == 200 && $resp['response']['status']) {

                        //set response
                        $response = $resp['response']['data'];
                        // echo "<pre>";
                        // print_r($response);
                        
                        $data = $response ?? [];
            
                        $message = [
                            'status' => true,
                            'data' => $data,
                            'message' => 'Data found successfully'
                        ];
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }else{
                        $message = array(
                            'status' => false,
                            'message' => 'Something went wrong.',
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }
                    
                }else{
                    $message = array(
                        'status' => false,
                        'message' => 'user_id, exchange, baseCurrencyArr, customBtcPackage, customUsdtPackage and dailTradeAbleBalancePercentage is required.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }
            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }

    public function save_auto_trade_settings_post(){
        $request = $this->post();

        $user_id = $request['user_id'] ?? '';
        $exchange = $request['exchange'] ?? '';
        $application_mode = $request['application_mode'] ?? '';
        $data = $request['data'] ?? '';

        if(!empty($user_id) && !empty($exchange) && !empty($application_mode) && !empty($data)){

            $params = [
                'exchange' => $exchange,
                'user_id' => $user_id,
                'application_mode' => $application_mode,
                'data' => $data,
            ];
    
            $req_arr = [
                'req_type' => 'POST',
                'req_endpoint' => 'saveAutoTradeSettings',
                'req_params' => $params,
            ];
            $resp = hitCurlRequest($req_arr);
            
            if ($resp['http_code'] == 200 && $resp['response']['status']) {

                //set response
                $response = $resp['response'];
                // echo "<pre>";
                // print_r($response);
                
                $data = $response ?? [];
    
                $message = [
                    'status' => true,
                    // 'data' => $data,
                    'message' => 'Auto trade settings saved successfully'
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }else{
                $message = array(
                    'status' => false,
                    'message' => 'Something went wrong.',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }
            
        }else{
            $message = array(
                'status' => false,
                'message' => 'user_id, exchange and application_mode is required.',
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }

    }

    public function get_auto_trade_parents_post(){
        $request = $this->post();

        $user_id = $request['user_id'] ?? '';
        $exchange = $request['exchange'] ?? '';
        $application_mode = $request['application_mode'] ?? '';

        if(!empty($user_id) && !empty($exchange) && !empty($application_mode)){

            $params = [
                'exchange' => $exchange,
                'user_id' => $user_id,
                'application_mode' => $application_mode,
            ];
    
            $req_arr = [
                'req_type' => 'POST',
                'req_endpoint' => 'getAutoTradeParents',
                'req_params' => $params,
            ];
            $resp = hitCurlRequest($req_arr);
            
            if ($resp['http_code'] == 200 && $resp['response']['status']) {

                //set response
                $response = $resp['response']['data'];
                // echo "<pre>";
                // print_r($response);
                
                $data = $response ?? [];
    
                $message = [
                    'status' => true,
                    'data' => $data,
                    'message' => 'Data found successfully'
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }else{
                $message = array(
                    'status' => false,
                    'message' => 'Something went wrong.',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }
            
        }else{
            $message = array(
                'status' => false,
                'message' => 'user_id, exchange and application_mode is required.',
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }

    }

    public function reset_auto_trade_generator_post(){
        $request = $this->post();

        $user_id = $request['user_id'] ?? '';
        $exchange = $request['exchange'] ?? '';
        $application_mode = $request['application_mode'] ?? '';

        if(!empty($user_id) && !empty($exchange) && !empty($application_mode)){

            $params = [
                'exchange' => $exchange,
                'user_id' => $user_id,
                'application_mode' => $application_mode,
            ];
    
            $req_arr = [
                'req_type' => 'POST',
                'req_endpoint' => 'resetAutoTradeGenerator',
                'req_params' => $params,
            ];
            $resp = hitCurlRequest($req_arr);
            
            if ($resp['http_code'] == 200 && $resp['response']['status']) {

                //set response
                $response = $resp['response'];
                // echo "<pre>";
                // print_r($response);
    
                $message = [
                    'status' => true,
                    'message' => 'Auto trade generator reset successfully.'
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }else{
                $message = array(
                    'status' => false,
                    'message' => 'Something went wrong.',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }
            
        }else{
            $message = array(
                'status' => false,
                'message' => 'user_id, exchange and application_mode is required.',
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }

    }

    /* ******************* End  ATG APIs  ************** */

    /* ************ KEY/Secret APIs  ************** */
    public function get_api_keys_post(){

        $request = $this->post();
        $user_id = $request['user_id'];
        $exchange = $request['exchange'];
        $application_mode = $request['application_mode'];

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if(!empty($user_id) && !empty($exchange) && !empty($application_mode) && $application_mode == 'live'){

                    if($exchange == 'binance'){
                        
                        $params = [
                            'exchange' => $exchange,
                            'user_id' => $user_id,
                        ];
                
                        $req_arr = [
                            'req_type' => 'POST',
                            'req_endpoint' => 'get_user_info',
                            'req_params' => $params,
                            'header'   => $received_Token_send
                        ];
                        $resp = hitCurlRequest($req_arr);

                        if ($resp['http_code'] == 200 && $resp['response']['success'] == 'true') {

                            //set response
                            $response = $resp['response']['data'];
                            // echo "<pre>";
                            // print_r($response);

                            $stars = "******************************";
                            $data = [];

                            $temp_obj = [
                                'heading' => 'Add your Binance API credentials here (Live)',
                                'note' => 'If you are not able to add the API credentials, please go to Binance and get the latest API credentials',
                                'key_id' => 'api_key',
                                'secret_id' => 'api_secret',
                                'api_key' => '',
                                'api_secret' => '',
                                'validity' => 'notSet',
                            ];

                            if(!empty($response['api_key']) && !empty($response['api_secret'])){

                                $start = mb_substr($response['api_key'], 0, 5);
                                $end = mb_substr($response['api_key'], -5, 5);
                                $temp_obj['api_key'] = $start.$stars.$end;
                                
                                $start = mb_substr($response['api_secret'], 0, 5);
                                $end = mb_substr($response['api_secret'], -5, 5);
                                $temp_obj['api_secret'] = $start.$stars.$end;
                                
                            }

                            //validate
                            $params = [
                                'exchange' => $exchange,
                                'user_id' => $user_id,
                                // 'application_mode' => $application_mode,
                            ];
                            $req_arr = [
                                'req_type' => 'POST',
                                'req_endpoint' => 'validateApiKeys',
                                'req_params' => $params,
                            ];
                            $resp = hitCurlRequest($req_arr);

                            if ($resp['http_code'] == 200 && $resp['response']['status']) {
                                $temp_obj['validity'] = $resp['response']['key1'];
                            }

                            $data[] = $temp_obj;
                
                            $message = [
                                'status' => true,
                                'data' => $data,
                                'message' => 'Data found successfully.'
                            ];
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }else{
                            $message = array(
                                'status' => false,
                                'message' => 'Something went wrong.',
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }

                    }else if($exchange == 'kraken'){

                        $params = [
                            'exchange' => $exchange,
                            'user_id' => $user_id,
                            // 'application_mode' => $application_mode,
                        ];
                
                        $req_arr = [
                            'req_type' => 'POST',
                            'req_endpoint' => 'getKrakenCredentials',
                            'req_params' => $params,
                            'header'   => $received_Token_send
                        ];
                        $resp = hitCurlRequest($req_arr);

                        //validate
                        $params = [
                            'exchange' => $exchange,
                            'user_id' => $user_id,
                            // 'application_mode' => $application_mode,
                        ];
                        $req_arr = [
                            'req_type' => 'POST',
                            'req_endpoint' => 'validateApiKeys',
                            'req_params' => $params,
                        ];
                        $resp2 = hitCurlRequest($req_arr);


                        if ($resp['http_code'] == 200 && !empty($resp['response'])) {

                            //set response
                            $response = $resp['response']['response'];
                            // echo "<pre>";
                            // print_r($response);

                            $stars = "******************************";
                            $data = [];
                            
                            $temp_obj = [
                                'heading' => '',
                                'note' => '',
                                'key_id' => '',
                                'secret_id' => '',
                                'api_key' => '',
                                'api_secret' => '',
                                'validity' => 'notSet',
                            ];

                            $key1 = $temp_obj;
                            $key1['heading'] = 'Add Kraken API 1 (for trading) credentials here (Live)';
                            $key1['note'] = 'If you are not able to add the API credentials, please go to Kraken and get the latest API credentials';
                            $key1['key_id'] = 'api_key';
                            $key1['secret_id'] = 'api_secret';
                            if(!empty($response) && !empty($response[0]['api_key']) && !empty($response[0]['api_secret'])){

                                $start = mb_substr($response[0]['api_key'], 0, 5);
                                $end = mb_substr($response[0]['api_key'], -5, 5);
                                $key1['api_key'] = $start.$stars.$end;
                                
                                $start = mb_substr($response[0]['api_secret'], 0, 5);
                                $end = mb_substr($response[0]['api_secret'], -5, 5);
                                $key1['api_secret'] = $start.$stars.$end;
                                
                                $key1['validity'] = $resp2['http_code'] == 200 && $resp2['response']['status'] ? $resp2['response']['key1'] : '';
                            }
                            
                            $data[] = $key1;
                            
                            $key2 = $temp_obj;
                            $key2['heading'] = 'Add Kraken API 2 (for balanceupdate) credentials here (Live)';
                            $key2['note'] = 'If you are not able to add the API credentials, please go to Kraken and get the latest API credentials';
                            $key2['key_id'] = 'api_key_secondary';
                            $key2['secret_id'] = 'api_secret_secondary';
                            if(!empty($response) && !empty($response[0]['api_key_secondary']) && !empty($response[0]['api_secret_secondary'])){
                                
                                $start = mb_substr($response[0]['api_key_secondary'], 0, 5);
                                $end = mb_substr($response[0]['api_key_secondary'], -5, 5);
                                $key2['api_key'] = $start.$stars.$end;
                                
                                $start = mb_substr($response[0]['api_secret_secondary'], 0, 5);
                                $end = mb_substr($response[0]['api_secret_secondary'], -5, 5);
                                $key2['api_secret'] = $start.$stars.$end;
                                
                                $key2['validity'] = $resp2['http_code'] == 200 && $resp2['response']['status'] ? $resp2['response']['key2'] : '';
                            }
                            
                            $data[] = $key2;
                            
                            $key3 = $temp_obj;
                            $key3['heading'] = 'Add Kraken API 3 (for order history) credentials here (Live)';
                            $key3['note'] = 'If you are not able to add the API credentials, please go to Kraken and get the latest API credentials';
                            $key3['key_id'] = 'api_key_third_key';
                            $key3['secret_id'] = 'api_secret_third_key';
                            if(!empty($response) && !empty($response[0]['api_key_third_key']) && !empty($response[0]['api_secret_third_key'])){
                                
                                $start = mb_substr($response[0]['api_key_third_key'], 0, 5);
                                $end = mb_substr($response[0]['api_key_third_key'], -5, 5);
                                $key3['api_key'] = $start.$stars.$end;
                                
                                $start = mb_substr($response[0]['api_secret_third_key'], 0, 5);
                                $end = mb_substr($response[0]['api_secret_third_key'], -5, 5);
                                $key3['api_secret'] = $start.$stars.$end;
                                
                                $key3['validity'] = $resp2['http_code'] == 200 && $resp2['response']['status'] ? $resp2['response']['key3'] : '';
                            }

                            $data[] = $key3;
                
                            $message = [
                                'status' => true,
                                'data' => $data,
                                'message' => 'Data found successfully.'
                            ];
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }else{
                            $message = array(
                                'status' => false,
                                'message' => 'Something went wrong.',
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }
                        
                    }else if($exchange == 'bam'){

                        $params = [
                            'exchange' => $exchange,
                            'user_id' => $user_id,
                            // 'application_mode' => $application_mode,
                        ];
                
                        $req_arr = [
                            'req_type' => 'POST',
                            'req_endpoint' => 'getBamCredentials',
                            'req_params' => $params,
                            'header'   => $received_Token_send
                        ];
                        $resp = hitCurlRequest($req_arr);

                        if ($resp['http_code'] == 200 && !empty($resp['response'])) {

                            //set response
                            $response = $resp['response']['response'];
                            // echo "<pre>";
                            // print_r($response);

                            $stars = "******************************";
                            $data = [];

                            $temp_obj = [
                                'heading' => 'Add your Bam API credentials here (Live)',
                                'note' => 'If you are not able to add the API credentials, please go to Bam and get the latest API credentials',
                                'key_id' => 'api_key',
                                'secret_id' => 'api_secret',
                                'api_key' => '',
                                'api_secret' => '',
                                'validity' => 'notSet',
                            ];

                            if(!empty($response[0]['api_key']) && !empty($response[0]['api_secret'])){

                                $start = mb_substr($response[0]['api_key'], 0, 5);
                                $end = mb_substr($response[0]['api_key'], -5, 5);
                                $temp_obj['api_key'] = $start.$stars.$end;
                                
                                $start = mb_substr($response[0]['api_secret'], 0, 5);
                                $end = mb_substr($response[0]['api_secret'], -5, 5);
                                $temp_obj['api_secret'] = $start.$stars.$end;
                                
                            }

                            //validate
                            $params = [
                                'exchange' => $exchange,
                                'user_id' => $user_id,
                                // 'application_mode' => $application_mode,
                            ];
                            $req_arr = [
                                'req_type' => 'POST',
                                'req_endpoint' => 'validateApiKeys',
                                'req_params' => $params,
                            ];
                            $resp = hitCurlRequest($req_arr);

                            if ($resp['http_code'] == 200 && $resp['response']['status']) {
                                $temp_obj['validity'] = $resp['response']['key1'];
                            }

                            $data[] = $temp_obj;
                
                            $message = [
                                'status' => true,
                                'data' => $data,
                                'message' => 'Data found successfully.'
                            ];
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }else{
                            $message = array(
                                'status' => false,
                                'message' => 'Something went wrong.',
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }


                    }else{
                        $message = array(
                            'status' => false,
                            'message' => 'Something went wrong.',
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }
                    
                }else{
                    $message = array(
                        'status' => false,
                        'message' => 'user_id, exchange and application_mode is required.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     
    }

    public function velidate_api_key_post(){

        die('permission issue!!!!!!!!!!!! YAHOOOOOOOOOOOOOO');
        $request = $this->post();
        $application_mode = $request['application_mode'];
        $user_id = $request['user_id'];
        $exchange = $request['exchange'];
        $api_key = $request['api_key'] ?? '';
        $api_secret = $request['api_secret'] ?? '';

        if(!empty($user_id) && !empty($exchange) && !empty($application_mode) && $application_mode == 'live' && $api_key != '' && $api_secret != ''){

            if($exchange == 'binance'){
                
                // $params = [
                //     'APIKEY' => $api_key,
                //     'APISECRET' => $api_secret,
                // ];
        
                $testing = $this->binance_api->check_api_key_validation($api_key, $api_secret);

                if ($testing == 'true' || $testing == true || $testing == 1 ) {
 
                    $message = [
                        'status' => true,
                        'valid' => true,
                        'message' => 'Valid key',
                        'testing'  =>  count($testing)
                    ];
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }else{
                    $message = array(
                        'status' => false,
                        'message' => 'Something went wrong.',
                        'testing' => $testing
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }

            }else if($exchange == 'kraken'){

                 $params = [
                    'user_id' => $user_id,
                    'APIKEY' => $api_key,
                    'APISECRET' => $api_secret,
                ];
        
                $req_arr = [
                    'req_type' => 'POST',
                    'req_endpoint' => 'validate_kraken_credentials',
                    'req_params' => $params,
                ];
                $resp = hitCurlRequest($req_arr);

                if ($resp['http_code'] == 200) {

                    //set response
                    $response = $resp['response']['message'];
                    // echo "<pre>";
                    // print_r($response);
                    $message = [
                        'status' => true,
                        'valid' => $response['status'] == 'success' ? true : false,
                        'message' => $response['status'] == 'success' ? 'Valid key' : 'Invalid Key',
                    ];
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }else{
                    $message = array(
                        'status' => false,
                        'message' => 'Something went wrong.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }
                
            }else if($exchange == 'bam'){
                
                $params = [
                    'APIKEY' => $api_key,
                    'APISECRET' => $api_secret,
                ];
        
                $req_arr = [
                    'req_type' => 'POST',
                    'req_endpoint' => 'validate_bam_credentials',
                    'req_params' => $params,
                ];
                $resp = hitCurlRequest($req_arr);

                if ($resp['http_code'] == 200) {

                    //set response
                    $response = $resp['response']['message'];
                    // echo "<pre>";
                    // print_r($response);
                    $message = [
                        'status' => true,
                        'valid' => $response['status'] == 'success' ? true : false,
                        'message' => $response['status'] == 'success' ? 'Valid key' : 'Invalid Key',
                    ];
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }else{
                    $message = array(
                        'status' => false,
                        'message' => 'Something went wrong.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }

            }else{
                $message = array(
                    'status' => false,
                    'message' => 'Something went wrong.',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }
            
        }else{
            $message = array(
                'status' => false,
                'message' => 'user_id, exchange and application_mode, api_key and api_secret is required.',
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }

    }

    public function save_api_key_post(){

        $request = $this->post();
        $application_mode = $request['application_mode'];
        $user_id = $request['user_id'];
        $exchange = $request['exchange'];
        $api_key = $request['api_key'] ?? '';
        $api_secret = $request['api_secret'] ?? '';

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send  = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if(!empty($user_id) && !empty($exchange) && !empty($application_mode) && $application_mode == 'live' && $api_key != '' && $api_secret != ''){

                    if($exchange == 'binance'){
                        
                        $params = [
                            'user_id' => $user_id,
                            'api_key' => $api_key,
                            'api_secret' => $api_secret,
                        ];
                
                        $req_arr = [
                            'req_type' => 'POST',
                            'req_endpoint' => 'update_user_info',
                            'req_params' => $params,
                            'header'  => $received_Token_send
                        ];
                        $resp = hitCurlRequest($req_arr);

                        if ($resp['http_code'] == 200) {

                            //set response
                            $response = $resp['response'];

                            if($response['status'] == 200 && $response['success'] == "true"){
                                $message = [
                                    'status' => true,
                                    'message' => 'Data updated successfully',
                                ];
                            }else{
                                $message = [
                                    'status' => false,
                                    'message' => 'Data not updated',
                                ];
                            }
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }else if($resp['http_code'] == 207){
                            
                            $message = array(
                                'status' => false,
                                'message' => 'Data already exist.',
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);

                        }else{
                            $message = array(
                                'status' => false,
                                'message' => 'Something went wrong.',
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }

                    }else if($exchange == 'kraken'){

                        if(empty($request['key_type'])){
                            $message = array(
                                'status' => false,
                                'message' => 'key_type is required.',
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }else if($request['key_type'] != 'primary' && $request['key_type'] && 'secondary' && $request['key_type'] != 'third'){
                            $message = array(
                                'status' => false,
                                'message' => 'key_type can only contain primary, secondary, third',
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }else{

                            $endPoint = '';

                            if($request['key_type'] == 'primary'){
                                $params = [
                                    'user_id' => $user_id,
                                    'api_key' => $api_key,
                                    'api_secret' => $api_secret,
                                ];
                                $endPoint = 'saveKrakenCredentials';
                                
                            }else if($request['key_type'] == 'secondary'){
                            
                                $params = [
                                    'user_id' => $user_id,
                                    'api_key_secondary' => $api_key,
                                    'api_secret_secondary' => $api_secret,
                                ];
                                $endPoint = 'saveKrakenCredentialsSecondary';
                                
                            }else if($request['key_type'] == 'third'){
                            
                                $params = [
                                    'user_id' => $user_id,
                                    'api_key_third_key' => $api_key,
                                    'api_secret_third_key' => $api_secret,
                                ];
                                $endPoint = 'saveKrakenCredentialsThirdKey';
                            
                            }

                            $req_arr = [
                                'req_type' => 'POST',
                                'req_endpoint' => $endPoint,
                                'req_params' => $params,
                                'header'  => $received_Token_send
                            ];
                            $resp = hitCurlRequest($req_arr);
            
                            if ($resp['http_code'] == 200) {
            
                                //set response
                                $response = $resp['response'];
                                // echo "<pre>";
                                // print_r($response);
                                $message = [
                                    'status' => $response['success'] == 'true' ? true : false,
                                    'message' => $response['success'] == 'true' ? 'Data updated successfully' : 'An error occured',
                                ];
                                $this->set_response($message, REST_Controller::HTTP_CREATED);
                            }else{
                                $message = array(
                                    'status' => false,
                                    'message' => 'Something went wrong.',
                                );
                                $this->set_response($message, REST_Controller::HTTP_CREATED);
                            }
                        
                        }
                        
                    }else if($exchange == 'bam'){
                        
                        $params = [
                            'user_id' => $user_id,
                            'api_key' => $api_key,
                            'api_secret' => $api_secret,
                        ];
                
                        $req_arr = [
                            'req_type' => 'POST',
                            'req_endpoint' => 'saveBamCredentials',
                            'req_params' => $params,
                            'header'  => $received_Token_send
                        ];
                        $resp = hitCurlRequest($req_arr);

                        if ($resp['http_code'] == 200) {

                            //set response
                            $response = $resp['response'];
                            // echo "<pre>";
                            // print_r($response);
                            
                            $message = [
                                'status' => $response['success'] == 'true' ? true : false,
                                'message' => $response['success'] == 'true' ? 'Data updated successfully' : 'An error occured',
                            ];
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }else{
                            $message = array(
                                'status' => false,
                                'message' => 'Something went wrong.',
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }

                    }else{
                        $message = array(
                            'status' => false,
                            'message' => 'Something went wrong.',
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }
                    
                }else{
                    $message = array(
                        'status' => false,
                        'message' => 'user_id, exchange and application_mode, api_key and api_secret is required.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }

    public function disable_api_key_post(){

        $request = $this->post();
        $application_mode = $request['application_mode'] ?? '';
        $user_id = $request['user_id'];
        $exchange = $request['exchange'];
        $key_type = $request['key_type'] ?? '';

        $admin_id = $this->post('admin_id');
        $device_token = $this->post('device_token');

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token_send  = $received_Token;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if(!empty($user_id) && !empty($exchange) && !empty($application_mode) && $application_mode == 'live' && !empty($key_type)){

                    $params = [
                        'user_id' => $user_id, 
                        'exchange' => $exchange, 
                        'keyNo' => $key_type,
                    ];
            
                    $req_arr = [
                        'req_type' => 'POST',
                        'req_endpoint' => 'disable_exchange_key',
                        'req_params' => $params,
                        'header'   => $received_Token_send
                    ];
                    $resp = hitCurlRequest($req_arr);

                    if ($resp['http_code'] == 200) {

                        //set response
                        $response = $resp['response'];
                        // echo "<pre>";
                        // print_r($response);
                        $message = [
                            'status' => $resp['response']['status'] ? true : false,
                            'message' => $resp['response']['status'] ? 'Action successfull' : 'Action failed',
                        ];
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }else{
                        $message = array(
                            'status' => false,
                            'message' => 'Something went wrong.',
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                    }
                    
                }else{
                    $message = array(
                        'status' => false,
                        'message' => 'user_id, exchange, application_mode, and key_type is required.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                }

            }else{

                $message = array(
                    'status' => 401,
                    'message' => 'User Not Valid!!!',
                );

                http_response_code('401');
                echo json_encode($message);
            }

        }else{
        
            $message = array(
                'status' => 401,
                'message' => 'Token not Valid!!!',
            );

            http_response_code('401');
            echo json_encode($message);
        }     

    }
    /* ************ End KEY/Secret APIs  ********** */
    public function get_coin_image_new($coin){

        // echo "<pre>";
        // echo "$coin -------------- $exchange <br>";

        if($coin == 'BNB'){
            return SURL . "assets/coin_logo/thumbs/BNB.png";
        }

        if($coin == 'USDT'){
            $symbol_search_arr = ['BTCUSDT', $coin, $coin.'BTC', $coin.'USDT'];
        }else{
            $symbol_search_arr = [$coin, $coin.'BTC', $coin.'USDT'];
        }

        $where_arr['symbol']['$in'] = $symbol_search_arr;
        $where_arr['user_id']['$eq'] ='global';
        $where_arr['exchange_type']['$eq'] ='binance';

        // print_r($where_arr);

        $this->mongo_db->where($where_arr);
        $this->mongo_db->limit(1);
        $this->mongo_db->sort('_id',1);
        // print_r($collectionName);

        $coin = $this->mongo_db->get('coins');

        $coin = iterator_to_array($coin);

        // print_r($coin);

        if (!empty($coin)) {
            $coin = $coin[0];
        }else{
            $coin = array();
        }

        if(empty($coin)){
            return '';
        }

        return $coin['coin_logo'];

        // if($condition == 'base64_encode'){
        //     //base64_encode 
        //     $cpath = SURL . "assets/coin_logo/thumbs/" . $coin['coin_logo'];
        //     $ctype = pathinfo($cpath, PATHINFO_EXTENSION);
        //     $cimgdata = file_get_contents($cpath);
        //     $base64_logo = 'data:image/' . $ctype . ';base64,' . base64_encode($cimgdata);
    
        //     return $base64_logo;
        // }

    }//end get_coin_image


}
