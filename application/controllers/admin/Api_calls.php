<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*Umer Abbas*/

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

class Api_calls extends REST_Controller {

    function __construct() {

        parent::__construct();

        // ini_set("display_errors", E_ALL);
        // error_reporting(E_ALL);

        // error_reporting(E_ALL & ~E_NOTICE);
        // ini_set('display_errors', E_ALL & ~E_NOTICE);

        // Load Library Goes here
        // $this->load->library('binance_api');

        // Load Modal
        $this->load->model('admin/mod_api_calls');
        $this->load->model('admin/mod_api_services');
        $this->load->model('admin/mod_settings');
        $this->load->model('admin/mod_users');
        $this->load->model('admin/Mod_jwt');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['orders_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['orders_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['orders_delete']['limit'] = 50; // 50 requests per hour per user/key

    }

    public function generate_jwt_token_post() {

        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', E_ALL & ~E_NOTICE);
        $this->load->model('admin/Mod_jwt');
    
        $user_id = '654e12e8d02ba947d652c112';
        $username = 'JohnDoe';
    
        $jwt_token = $this->Mod_jwt->LoginToken($user_id, $username);
        
            

        $this->response([
            'status' => true,
            'token' => $jwt_token
        ], REST_Controller::HTTP_OK);
    }

    public function test_post(){

        $test = "umer git clone test on linux";

        $data = $this->post();
        $head = $this->head();

        $username = $this->input->server('PHP_AUTH_USER');
        $password = $this->input->server('PHP_AUTH_PW');

        // $_SERVER["PHP_AUTH_USER"]: "pointSupply",
        // $_SERVER["PHP_AUTH_PW"]: "4e46d99ac22a4b0abe5768a78ee87b8c",
        // "PHP_AUTH_PW": "4e46d99ac22a4b0abe5768a78ee87b8c",


        $coins = $this->mod_api_calls->get_global_coins();
        
        // $required_keys = array("coin_name", "symbol", "coin_logo", "exchange_type");
        
        $coins_arr = array();
        if(!empty($coins)){
            foreach($coins as $coin){
                $coin_arr = array(
                    'coin_name' => $coin['coin_name'],
                    'symbol' => $coin['symbol'],
                    'coin_logo' => $coin['coin_logo'],
                    'exchange_type' => $coin['exchange_type'],
                );
                $coins_arr[] = $coin_arr;
            }
        }

        $result = array(
            'items' => [
                'abc',
                'def', 
                'ghi'
            ],
            'coins' => $coins_arr,
        ); 

        if(!empty($result)){

            $message = array(
                'server' => $_SERVER,
                'status' => TRUE,
                'data' => $result,
                'head' => $head,
                'message' => 'Scuccessful',
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);

        } else {

            $message = array(
                'status' => FALSE,
                'message' => 'Something Went Wrong',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

        }
    }

    public function test_email_post(){

        //Send Email used amazon ses
        $this->load->library('Amazon_ses_bulk_email');
        // $this->amazon_ses_bulk_email->send_bulk_email($html_message, $subject, $from, $to, $cc = '', $bcc = '', $title = '');
        $email_sent = $this->amazon_ses_bulk_email->send_bulk_email('test Email Message body', 'test Email Subject', 'support@digiebot.com', 'vizzdev@outlook.com', $cc = '', $bcc = '', $title = 'Asad test mail');

        if ($email_sent) {
            //Success email Sent
            $message = array(
                'status' => TRUE,
                'data' => '',
                'message' => 'Email Successful',
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'Email failed',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        
    }

    // validate_binance_api
    public function validate_binance_api($binance_api_key, $binance_api_secret) {
        
        if(!empty($binance_api_key) && !empty($binance_api_secret)){
            
            $testing = $this->binance_api->accountStatusNew($binance_api_key, $binance_api_secret);
            if (count($testing) > 0) {
                return true;
            }
        }
        return false;
    }// end validate_binance_api
    
    // validate_binance_credentials
    public function validate_binance_credentials_post() {

        die('Permission issue!!!!!!!!YAHOOOOOOOOOO');
        $request = $this->post();
        // $request = json_decode($request);

        if(empty($request['APIKEY']) || empty($request['APISECRET'])){
            $message = array(
                'status' => false,
                'message' => 'key or secret empty',
            );
            $this->set_response($message, REST_Controller::HTTP_OK);
        }else{
            $testing = $this->binance_api->accountStatusNew($request['APIKEY'], $request['APISECRET']);

            if (count($testing) > 0) {
                $message = array(
                    // 'testing' => count($testing),
                    'status' => true,
                    'message' => 'valid key secret',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
            }else{
                $message = array(
                    // 'testing' => count($testing),
                    'status' => false,
                    'message' => 'invalid key secret',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
        }
    }// end validate_binance_credentials
    
    // validate_bam_credentials
    public function validate_bam_credentials_post() {
        die('Permission issue!!!!!!!!YAHOOOOOOOOOO');
        $request = $this->post();

        if(empty($request['APIKEY']) || empty($request['APISECRET'])){
            $message = array(
                'status' => false,
                'message' => 'key or secret empty',
            );
            $this->set_response($message, REST_Controller::HTTP_OK);
        }else{

            require APPPATH . 'libraries/php-bam-api.php';

            $api = new Bam\API($request['APIKEY'], $request['APISECRET']);
            $balance_arr = $api->balances();

            if (!empty($balance_arr)) {

                $message = array(
                    'status' => true,
                    'balance_arr' => $balance_arr,
                    'message' => 'valid key secret',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
            }else{
                $message = array(
                    'status' => false,
                    'message' => 'invalid key secret',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
        }
    }// end validate_bam_credentials
    
    // verify_api_key_secret_post
    public function verify_api_key_secret_post() {
        die('This Api was Depricated on  Aug 1947');
        $request = $this->post();
        if(empty($request['user_id']) || empty($request['exchange'])){
            $message = array(
                'status' => false,
                'message' => 'user_id and exchange can not be empty',
            );
            $this->set_response($message, REST_Controller::HTTP_OK);
        }else{

            $user_id = $request['user_id'];
            $exchange = $request['exchange'];

            $keySecret = false; 

            //get user API KEY Secret
            if($exchange == 'binance'){

                $this->mongo_db->where(array('_id' => $user_id));
                $user = $this->mongo_db->get('users');
                $user = iterator_to_array($user);
                if (count($user) > 0) {
                    if(!empty($user[0]['api_key']) && !empty($user[0]['api_secret'])){
                        $testing = $this->binance_api->accountStatusNew($user[0]['api_key'], $user[0]['api_secret']);
                        if (count($testing) > 0) {

                            //curl to AppDigiebot
                            $req_arr = [
                                'req_type' => 'GET',
                                // 'req_endpoint' => 'validate_bam_credentials',
                                'req_params' => [],
                                'req_url' => 'https://app.digiebot.com/admin/Updatebalance/update_user_vallet/'.(string)$user_id,
                            ];
                            $resp = hitCurlRequest($req_arr);

                            $keySecret = true;
                        }
                    }
                }
            }else if($exchange == 'bam'){
                $this->mongo_db->where(array('user_id' => $user_id));
                $user = $this->mongo_db->get('bam_credentials');
                $user = iterator_to_array($user);
                if (count($user) > 0) {
                    if(!empty($user[0]['api_key']) && !empty($user[0]['api_secret'])){

                        //curl to DigieApis
                        $req_arr = [
                            'req_type' => 'POST',
                            'req_endpoint' => 'validate_bam_credentials',
                            'req_params' => [
                                'APIKEY' => $user[0]['api_key'],
                                'APISECRET' => $user[0]['api_secret'],
                            ],
                            // 'req_url' => "localhost:3010/apiEndPoint/validate_bam_credentials",
                        ];
                        $resp = hitCurlRequest($req_arr);
                        if(!empty($resp['response']['message']['status'])){
                            if($resp['response']['message']['status'] == 'success'){
                                   $keySecret = true;
                            }
                        }

                    }
                }
            }else if($exchange == 'kraken'){

                $this->mongo_db->where(array('user_id' => $user_id));
                $user = $this->mongo_db->get('kraken_credentials');
                $user = iterator_to_array($user);

                if (count($user) > 0) {
                    if(!empty($user[0]['api_key']) && !empty($user[0]['api_secret'])){
        
                        $params = [
                            'user_id' => (string)$user_id,
                            "validating" => true,
                            "user_id" => $user[0]['user_id'],
                            "api_key"  => $user[0]['api_key'],
                            "api_secret"  => $user[0]['api_secret'],
                        ];
                        $req_arr = [
                            'req_type' => 'POST',
                            'req_params' => $params,
                            'req_url' => 'http://35.153.9.225:3006/updateUserBalance',
                        ];
                        $resp = hitCurlRequest($req_arr);

                        if ($resp['response']['success'] === true || $resp['response']['success'] === 'true') {
                            $keySecret = true;
                        }
                        
                    }
                }
            }

            if ($keySecret) {
                $message = array(
                    'status' => true,
                    'message' => 'valid key secret',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
            }else{
                $message = array(
                    'status' => false,
                    'message' => 'invalid key secret',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
        }
    }// end verify_api_key_secret_post

    // verifyUser_post
    public function verifyUser_post() {

        $this->load->model('admin/mod_api_services');

        $verification_code_type = $this->post('type');
        $verification_code = $this->post('code');
        $verification_user_id = $this->post('user_id');

        if(empty($verification_code_type) || empty($verification_code) || empty($verification_user_id)){
            $message = array(
                'status' => FALSE,
                'message' => 'type, code and user_id can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
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

            }else{
                $message = array(
                    'status' => FALSE,
                    'message' => 'something went wrong.',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }

        }

    } // end verifyUser_post

    // resetPassword_post
    public function resetPassword_post() {

        $request = $this->post();

        $user_id = $request['user_id'];
        $password = $request['password'];

        if(empty($user_id) || empty($password)){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id and password can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
                
            $result = $this->mod_api_calls->reset_password($user_id, $password);
    
            if(!empty($result)){
    
                $message = array(
                    'status' => TRUE,
                    'message' => 'Scuccessful',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
    
            } else {
    
                $message = array(
                    'status' => FALSE,
                    'message' => 'Something Went Wrong',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }

    } // end resetPassword_post

    // sendKeySecret_post
    public function sendKeySecret_post(){

        $request = $this->post();

        $user_id = $request['user_id'];
        $api_key = $request['apiKey'];
        $api_secret = $request['apiSecret'];
        $exchange = $request['exchange'];

        if(empty($user_id) || empty($api_key) || empty($api_secret) || empty($exchange)){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id, apiKey, apiSecret can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{

            // if($this->validate_binance_api($resquest['apiKey'], $resquest['apiSecret'])){
    
                // $result = $this->mod_api_calls->save_binance_api_key($user_id, $api_key, $api_secret, $exchange);
                
                $result = $this->mod_api_calls->save_api_key($user_id, $api_key, $api_secret, $exchange);
        
                if(!empty($result)){
        
                    $message = array(
                        'status' => TRUE,
                        'data' => $request,
                        'message' => 'Scuccessful',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
        
                } else {
        
                    $message = array(
                        'status' => FALSE,
                        'message' => 'Something Went Wrong',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }

            // }else{

            //     $message = array(
            //         'status' => FALSE,
            //         'message' => 'Binance Api key or secret is not valid.',
            //     );
            //     $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            // } 
        
        }

    }// end sendKeySecret_post

    // getKeySecret_post
    public function getKeySecret_post(){

        $request = $this->post();
        $user_id = $request['user_id'];

        if(empty($user_id)){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{

            $result = $this->mod_api_calls->get_user_api_key($user_id);
    
            if(!empty($result)){

                $response_result = array(
                    'user_id' => $user_id,
                    'api_key_binance' => $result['api_key'],
                    'api_secret_binance' => $result['api_secret'],
                    'api_key_bam' => (!empty($result['api_key_bam']) ? $result['api_key_bam'] : ''),
                    'api_secret_bam' => (!empty($result['api_secret_bam']) ? $result['api_secret_bam'] : ''),
                    'api_key_coinbasepro' => (!empty($result['api_key_coinbasepro']) ? $result['api_key_coinbasepro'] : ''),
                    'api_secret_coinbasepro' => (!empty($result['api_secret_coinbasepro']) ? $result['api_secret_coinbasepro'] : ''),
                ); 
    
                $message = array(
                    'status' => TRUE,
                    'data' => $response_result,
                    'message' => 'Scuccessful',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
    
            } else {
    
                $message = array(
                    'status' => FALSE,
                    'message' => 'key not found.',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        
        }

    }// end getKeySecret_post
    
    // getBamKeySecret_post
    public function getBamKeySecret_post(){

        $request = $this->post();
        $user_id = $request['user_id'];

        if(empty($user_id)){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{

            $this->mongo_db->where(array('user_id' => $user_id));
            $get_settings = $this->mongo_db->get('bam_credentials');
            $settings_arr = iterator_to_array($get_settings);

            $result = [];
            if (count($settings_arr) > 0) {
                $result =  $settings_arr[0];
            }

            if(!empty($result)){
                $response_result = $result;
                $message = array(
                    'status' => TRUE,
                    'data' => $response_result,
                    'message' => 'Scuccessful',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            } else {
                $message = array(
                    'status' => FALSE,
                    'message' => 'key not found.',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        
        }

    }// end getBamKeySecret_post
    
    // add_user_coin_post //Umer Abbas [7-11-19]
    public function add_user_coin_post(){

        $request = $this->post();

        if(empty($request['user_id']) || empty($request['coin_symbol']) || empty($request['exchange'])){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id, coin, exchange can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
            $user_id = $request['user_id'];
            $coins = $request['coin_symbol'];
            $exchange = $request['exchange'];

            // print_r($request);die('request');

            $result = $this->mod_api_calls->add_user_coins($user_id, $coins, $exchange);

            if(!empty($result)){

                $response_data = array(
                    'coins' => $result,
                );

                $message = array(
                    'status' => TRUE,
                    'data' => $response_data,
                    'message' => 'Coins added scuccessfully',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
    
            } else {
    
                $message = array(
                    'status' => FALSE,
                    'message' => 'An error occured',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        
        }

    }// end add_user_coin_post

    //manage_coins_post
    public function manage_coins_orgnal_request_post(){

        // error_reporting(E_ALL & ~E_NOTICE);
        // ini_set('display_errors', E_ALL & ~E_NOTICE);

        $request = $this->post();

        if(empty($request['user_id']) || empty($request['exchange'])){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id, exchange can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
            $user_id = $request['user_id'];
            $exchange = $request['exchange'];
            $application_mode = (!empty($request['application_mode'])?$request['application_mode'] : 'live');

            $coins = $this->mod_api_calls->get_all_user_coins($user_id, $exchange);


            $temp_coin_balance_data = [];

            $data = [];
            if(!empty($coins)){

                $btc_arr = array('coin_name' => 'Bitcoin',
                    'symbol' => "BTC",
                    'coin_name' => "bitcoin",
                    'coin_logo' => "btc11.jpg",
                    'coin_keywords' => '#btc,#bitcoin,#BTC',
                );
    
                array_push($coins, $btc_arr);
    
                end($coins);
    
                $last_key = key($coins);
                $last_value = array_pop($coins);
                $coins = array_merge(array($last_key => $last_value), $coins);
    
                $currency = 'bitcoin';
                $market = array();

                $BTCUSDT_price = $this->mod_api_calls->get_last_price('BTCUSDT', $exchange);

                foreach ($coins as $coin) {
                    $coin_name = $coin['coin_name'];
                    $symbol = $coin['symbol'];
                    // $exchange = $coin['exchange_type'];
                    $response_coin_id = (string)$coin['_id'];
                    $coin_id = $coin->_id;
                    $balance = $this->mod_api_calls->get_coin_balance($user_id, $symbol, $exchange);
                    // if ($balance == null) {
                    //     $balance = "<p style='color:red;'>Set Your Api Key</p>";
                    // }
    
                    $amount = null;
                    $amount_status = null;
                    if ($symbol != "BTC") {
                        $price = $this->mod_api_calls->get_last_price($symbol, $exchange);
                        $open_trades = $this->mod_api_calls->get_open_trades($user_id, $symbol, $exchange, $application_mode);
                        $change = $this->mod_api_calls->get_24_hour_price_change($symbol);
    
                        $per = $change['percentage'];
                        $per = number_format($per, 2);
                        $amount1 = $change['change'];
                        // if ($per > 0) {
                        //     $amount = num($amount1) . '(' . number_format($per, 2) . '%)';
                        //     $amount_status = 'green';
                        // } elseif ($per < 0) {
                        //     $amount = num($amount1) . '(' . number_format($per, 2) . '%)';
                        //     $amount_status = 'red';
                        // } else {
                        //     $amount = num($amount1) . '(' . number_format($per, 2) . '%)';
                        //     $amount_status = 'dark-grey';
                        // }
                        if ($per > 0) {
                            $amount = '<div class="text-success">'.number_format($per, 2). '% </div>';
                            $amount_status = 'green';
                        } elseif ($per < 0) {
                            $amount = '<div class="text-danger">'.number_format($per, 2) . '% </div>';
                            $amount_status = 'red';
                        } else {
                            $amount = '<div class="text-default">' . number_format($per, 2) . '% </div>';
                            $amount_status = 'dark-grey';
                        }
                        // $trade = $this->mod_api_calls->get_market_trades($user_id, $symbol, $exchange);
                    } else {
                        $price = 1;
                        $trade = 'N/A';
                    }
                    
                    $tarr = explode('USDT', $symbol);
                    if (isset($tarr[1]) && $tarr[1] == '') {
                        // echo "\r\n USDT coin";
                        $usd_balance =  $balance['coin_balance']*$BTCUSDT_price;
                        $convertamount =  $price;
                        $convertamount = round($convertamount, 5);
                    } else {
                        // echo "\r\n BTC coin";
                        if($symbol == "BTC"){
                            $usd_balance =  $balance['coin_balance']*$BTCUSDT_price;
                            $convertamount =  $BTCUSDT_price;
                            $convertamount = round($convertamount, 5);
                        }else{
                            $usd_balance =  $balance['coin_balance']*$price*$BTCUSDT_price;
                            $convertamount =  $price*$BTCUSDT_price;
                            $convertamount = round($convertamount, 5);
                        }
                    }

                    $coin_logo = $this->get_coin_image($symbol, $exchange);
    
                    // $cpath = SURL."assets/coin_logo/thumbs/".$coin['coin_logo'];
                    // $ctype = pathinfo($cpath, PATHINFO_EXTENSION);
                    // $cimgdata = file_get_contents($cpath);
                    // $base64_logo = 'data:image/' . $ctype . ';base64,' . base64_encode($cimgdata);
                    $base64_logo = '';

                    $total_trade_qty = 0;
                    if(!empty($open_trades)){
                        foreach($open_trades as $tradee){
                            $total_trade_qty += $tradee['quantity'];
                        }
                    }
    
                    $coinData1 = array(
                        'symbol' => $symbol,
                        'coin_name' => $coin_name,
                        'logo' => $base64_logo,
                        'coin_logo' => $coin_logo, 
                        'balance' => $balance['coin_balance'],
                        'usd_amount' => $convertamount,
                        'change' => $amount,
                        'change_status' => $amount_status,
                        'last_price' => $price,
                        // 'trade' => $trade,
                        'trade' => count($open_trades),
                        'coin_id' => $response_coin_id,
                        'usd_balance' => $usd_balance,
                        'balance_error' => ($balance['coin_balance'] < $total_trade_qty ? true : false),
                        'comitted_balance' => $total_trade_qty,
                    );


                    //save coin according to pair
                    $temp_coin_balance_data = [];
                    $arr1 = explode('BTC', $symbol);
                    $arr2 = explode('USDT', $symbol);
                    if (($arr1[0] == '' && ((isset($arr1[1]) && $arr1[1] == '')) || ($arr2[0] == '' && (isset($arr1[1]) && $arr1[1] == '')))) {
                        //Do nothing
                    } else if (isset($arr1[1]) && $arr1[1] == '') {
                        $sym = $arr1[0];
                    } else if (isset($arr2[1]) && $arr2[1] == '') {
                        $sym = $arr2[0];
                    }
                    $tempNewArr = [];

                    if($balance['coin_balance'] < $total_trade_qty){
                        $coinData1['required_balance'] = $total_trade_qty - $balance['coin_balance'];
                        
                        //required balance usd worth
                        $tarr = explode('USDT', $symbol);
                        if (isset($tarr[1]) && $tarr[1] == '') {
                            // echo "\r\n USDT coin";
                            
                            if($symbol == 'BTCUSDT'){
                                $coinData1['required_balance_usd_worth'] = number_format($coinData1['required_balance'], 2);
                                $tempNewArr['required_balance_usd_worth'] = $coinData1['required_balance_usd_worth'];
                            }else{
                                $coinData1['required_balance_usd_worth'] = number_format(($coinData1['required_balance'] * $price), 2);
                                $tempNewArr['required_balance_usd_worth'] = $coinData1['required_balance_usd_worth'];
                            }
                            
                        } else {
                            // echo "\r\n BTC coin";
                            if ($symbol == "BTC") {
                                $coinData1['required_balance_usd_worth'] = number_format(($coinData1['required_balance'] * $BTCUSDT_price), 2);
                                $tempNewArr['required_balance_usd_worth'] = $coinData1['required_balance_usd_worth'];
                            } else {
                                $coinData1['required_balance_usd_worth'] = number_format(($coinData1['required_balance'] * $price * $BTCUSDT_price), 2);
                                $tempNewArr['required_balance_usd_worth'] = $coinData1['required_balance_usd_worth'];
                            }
                        }
                        
                        $coinData1['required_balance'] = number_format($coinData1['required_balance'], 8);
                        $tempNewArr['required_balance'] = $coinData1['required_balance'];

                    }else if($balance['coin_balance'] > $total_trade_qty){
                        $coinData1['extra_balance'] = $balance['coin_balance'] - $total_trade_qty;
                        
                        //extra balance usd worth
                        $tarr = explode('USDT', $symbol);
                        if (isset($tarr[1]) && $tarr[1] == '') {
                            // echo "\r\n USDT coin";
                            if ($symbol == 'BTCUSDT') {
                                $coinData1['extra_balance_usd_worth'] = number_format($coinData1['extra_balance'], 2);
                                $tempNewArr['extra_balance_usd_worth'] = $coinData1['extra_balance_usd_worth'];
                            } else {
                                $coinData1['extra_balance_usd_worth'] = number_format(($coinData1['extra_balance'] * $price), 2);
                                $tempNewArr['extra_balance_usd_worth'] = $coinData1['extra_balance_usd_worth'];
                            }
                        } else {
                            // echo "\r\n BTC coin";
                            if ($symbol == "BTC") {
                                $coinData1['extra_balance_usd_worth'] = number_format(($coinData1['extra_balance'] * $BTCUSDT_price), 2);
                                $tempNewArr['extra_balance_usd_worth'] = $coinData1['extra_balance_usd_worth'];
                            } else {
                                $coinData1['extra_balance_usd_worth'] = number_format(($coinData1['extra_balance'] * $price * $BTCUSDT_price), 2);
                                $tempNewArr['extra_balance_usd_worth'] = $coinData1['extra_balance_usd_worth'];
                            }
                        }

                        $coinData1['extra_balance'] = number_format($coinData1['extra_balance'], 8);
                        $tempNewArr['extra_balance'] = $coinData1['extra_balance'];
                    }
                    
                    //comitted balance usd worth
                    $tarr = explode('USDT', $symbol);
                    if (isset($tarr[1]) && $tarr[1] == '') {
                        // echo "\r\n USDT coin";
                        if ($symbol == 'BTCUSDT') {
                            $coinData1['comitted_balance_usd_worth'] = number_format($coinData1['comitted_balance'], 2);
                            $coinData1['balance_usd_worth'] = number_format($coinData1['balance'], 2);

                            $tempNewArr['comitted_balance_usd_worth'] = $coinData1['comitted_balance_usd_worth'];
                            $tempNewArr['balance_usd_worth'] = $coinData1['balance_usd_worth'];
                        } else {
                            $coinData1['comitted_balance_usd_worth'] = number_format(($coinData1['comitted_balance'] * $price), 2);
                            $coinData1['balance_usd_worth'] = number_format(($coinData1['balance'] * $price), 2);

                            $tempNewArr['comitted_balance_usd_worth'] = $coinData1['comitted_balance_usd_worth'];
                            $tempNewArr['balance_usd_worth'] = $coinData1['balance_usd_worth'];
                        }
                    } else {
                        // echo "\r\n BTC coin";
                        if ($symbol == "BTC") {
                            $coinData1['comitted_balance_usd_worth'] = number_format(($coinData1['comitted_balance'] * $BTCUSDT_price), 2);
                            $coinData1['balance_usd_worth'] = number_format(($coinData1['balance'] * $BTCUSDT_price), 2);

                            $tempNewArr['comitted_balance_usd_worth'] = $coinData1['comitted_balance_usd_worth'];
                            $tempNewArr['balance_usd_worth'] = $coinData1['balance_usd_worth'];
                        } else {
                            $coinData1['comitted_balance_usd_worth'] = number_format(($coinData1['comitted_balance'] * $price * $BTCUSDT_price), 2);
                            $coinData1['balance_usd_worth'] = number_format(($coinData1['balance'] * $price * $BTCUSDT_price), 2);

                            $tempNewArr['comitted_balance_usd_worth'] = $coinData1['comitted_balance_usd_worth'];
                            $tempNewArr['balance_usd_worth'] = $coinData1['balance_usd_worth'];
                        }
                    }

                    $coinData1['comitted_balance'] = number_format($coinData1['comitted_balance'], 8);

                    $tempNewArr['comitted_balance'] = $coinData1['comitted_balance'];
                    
                    $tempNewArr['balance'] = $coinData1['balance'];
                    $tempNewArr['balance_usd_worth'] = $coinData1['balance_usd_worth'];

                    if(!empty($sym)){
                        $newArr[$sym][] = $tempNewArr;
                    }
                    
                    $market[] = $coinData1;
            
                }

                foreach ($market as $key => $value) {

                    //save coin according to pair
                    $temp_coin_balance_data = [];
                    $arr1 = explode('BTC', $value['symbol']);
                    $arr2 = explode('USDT', $value['symbol']);
                    if (($arr1[0] == '' && ((isset($arr1[1]) && $arr1[1] == '')) || ($arr2[0] == '' && (isset($arr1[1]) && $arr1[1] == '')))) {
                        //Do nothing
                    } else if (isset($arr1[1]) && $arr1[1] == '') {
                        $sym = $arr1[0];
                    } else if (isset($arr2[1]) && $arr2[1] == '') {
                        $sym = $arr2[0];
                    }

                    if(empty($sym)){
                        continue;
                    }
                    $currPairsArr = $newArr[$sym];

                    
                    if(count($currPairsArr) > 1){

                        $total_comitted_balance = $currPairsArr[0]['comitted_balance'] + $currPairsArr[1]['comitted_balance'];
                        $total_comitted_balance = number_format($total_comitted_balance, 8);
                        
                        $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'] +  $currPairsArr[1]['comitted_balance_usd_worth'];
                        $total_comitted_balance_usd_worth = number_format($total_comitted_balance_usd_worth, 2);
                        
                        $total_balance = $currPairsArr[0]['balance'];
                        $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];
                        
                        if($total_balance < $total_comitted_balance){

                            $total_required = $total_comitted_balance - $total_balance;
                            $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;

                            $market[$key]['required_balance'] = number_format($total_required, 8);
                            $market[$key]['required_balance_usd_worth'] = number_format($total_required_usd_worth, 2);

                        }else if($total_balance > $total_comitted_balance){
 
                            $total_extra = $total_balance - $total_comitted_balance;
                            $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;

                            $market[$key]['extra_balance'] = number_format($total_extra, 8);
                            $market[$key]['extra_balance_usd_worth'] = number_format($total_extra_usd_worth, 2);

                        }
                        
                    }


                }

                $data['coin_market'] = $market;
            }
            
            if(!empty($data)){
    
                $message = array(
                    'status' => TRUE,
                    'data' => $data['coin_market'],
                    'message' => 'Data found scuccessfully',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
    
            } else {
    
                $message = array(
                    'status' => FALSE,
                    'message' => 'Data not found',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        
        }

    }//end manage_coins_post

    //manage_coins_post
    public function manage_coins_purani_wali_post(){

        // error_reporting(E_ALL & ~E_NOTICE);
        // ini_set('display_errors', E_ALL & ~E_NOTICE);

        $request = $this->post();

        if(empty($request['user_id']) || empty($request['exchange'])){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id, exchange can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
            $user_id = $request['user_id'];
            $exchange = $request['exchange'];
            $application_mode = (!empty($request['application_mode'])?$request['application_mode'] : 'live');

            $coins = $this->mod_api_calls->get_all_user_coins($user_id, $exchange);


            $temp_coin_balance_data = [];

            $data = [];
            if(!empty($coins)){

                $btc_arr = array('coin_name' => 'Bitcoin',
                    'symbol' => "BTC",
                    'coin_name' => "bitcoin",
                    'coin_logo' => "btc11.jpg",
                    'coin_keywords' => '#btc,#bitcoin,#BTC',
                );
    
                array_push($coins, $btc_arr);
    
                end($coins);
    
                $last_key = key($coins);
                $last_value = array_pop($coins);
                $coins = array_merge(array($last_key => $last_value), $coins);
    
                $currency = 'bitcoin';
                $market = array();

                $BTCUSDT_price = $this->mod_api_calls->get_last_price('BTCUSDT', $exchange);

                foreach ($coins as $coin) {
                    $coin_name = $coin['coin_name'];
                    $symbol = $coin['symbol'];
                    // $exchange = $coin['exchange_type'];
                    $response_coin_id = (string)$coin['_id'];
                    $coin_id = $coin->_id;
                    $balance = $this->mod_api_calls->get_coin_balance($user_id, $symbol, $exchange);
                    // if ($balance == null) {
                    //     $balance = "<p style='color:red;'>Set Your Api Key</p>";
                    // }
    
                    $amount = null;
                    $amount_status = null;
                    if ($symbol != "BTC") {
                        $price = $this->mod_api_calls->get_last_price($symbol, $exchange);
                        $open_trades = $this->mod_api_calls->get_open_trades($user_id, $symbol, $exchange, $application_mode);
                        $change = $this->mod_api_calls->get_24_hour_price_change($symbol);
    
                        $per = $change['percentage'];
                        $per = number_format($per, 2);
                        $amount1 = $change['change'];
                        // if ($per > 0) {
                        //     $amount = num($amount1) . '(' . number_format($per, 2) . '%)';
                        //     $amount_status = 'green';
                        // } elseif ($per < 0) {
                        //     $amount = num($amount1) . '(' . number_format($per, 2) . '%)';
                        //     $amount_status = 'red';
                        // } else {
                        //     $amount = num($amount1) . '(' . number_format($per, 2) . '%)';
                        //     $amount_status = 'dark-grey';
                        // }
                        if ($per > 0) {
                            $amount = '<div class="text-success">'.number_format($per, 2). '% </div>';
                            $amount_status = 'green';
                        } elseif ($per < 0) {
                            $amount = '<div class="text-danger">'.number_format($per, 2) . '% </div>';
                            $amount_status = 'red';
                        } else {
                            $amount = '<div class="text-default">' . number_format($per, 2) . '% </div>';
                            $amount_status = 'dark-grey';
                        }
                        // $trade = $this->mod_api_calls->get_market_trades($user_id, $symbol, $exchange);
                    } else {
                        $price = 1;
                        $trade = 'N/A';
                    }
                    
                    $tarr = explode('USDT', $symbol);
                    if (isset($tarr[1]) && $tarr[1] == '') {
                        // echo "\r\n USDT coin";
                        $usd_balance =  $balance['coin_balance']*$BTCUSDT_price;
                        $convertamount =  $price;
                        $convertamount = round($convertamount, 5);
                    } else {
                        // echo "\r\n BTC coin";
                        if($symbol == "BTC"){
                            $usd_balance =  $balance['coin_balance']*$BTCUSDT_price;
                            $convertamount =  $BTCUSDT_price;
                            $convertamount = round($convertamount, 5);
                        }else{
                            $usd_balance =  $balance['coin_balance']*$price*$BTCUSDT_price;
                            $convertamount =  $price*$BTCUSDT_price;
                            $convertamount = round($convertamount, 5);
                        }
                    }

                    $coin_logo = $this->get_coin_image($symbol, $exchange);
    
                    // $cpath = SURL."assets/coin_logo/thumbs/".$coin['coin_logo'];
                    // $ctype = pathinfo($cpath, PATHINFO_EXTENSION);
                    // $cimgdata = file_get_contents($cpath);
                    // $base64_logo = 'data:image/' . $ctype . ';base64,' . base64_encode($cimgdata);
                    $base64_logo = '';

                    $total_trade_qty = 0;
                    if(!empty($open_trades)){
                        foreach($open_trades as $tradee){
                            $total_trade_qty += $tradee['quantity'];
                        }
                    }


                    if ($symbol == 'BTCUSDT') {
                        $balance['coin_balance'] = number_format($balance['coin_balance'], 2);
                    } else {
                        $balance['coin_balance'] = number_format($balance['coin_balance'], 6);
                    }
    
                    $coinData1 = array(
                        'symbol' => $symbol,
                        'coin_name' => $coin_name,
                        'logo' => $base64_logo,
                        'coin_logo' => $coin_logo, 
                        'balance' => $balance['coin_balance'],
                        'usd_amount' => $convertamount,
                        'change' => $amount,
                        'change_status' => $amount_status,
                        'last_price' => $price,
                        // 'trade' => $trade,
                        'trade' => count($open_trades),
                        'coin_id' => $response_coin_id,
                        'usd_balance' => $usd_balance,
                        'balance_error' => ($balance['coin_balance'] < $total_trade_qty ? true : false),
                        'comitted_balance' => $total_trade_qty,
                    );


                    //save coin according to pair
                    $temp_coin_balance_data = [];
                    $arr1 = explode('BTC', $symbol);
                    $arr2 = explode('USDT', $symbol);
                    if (($arr1[0] == '' && ((isset($arr1[1]) && $arr1[1] == '')) || ($arr2[0] == '' && (isset($arr1[1]) && $arr1[1] == '')))) {
                        //Do nothing
                    } else if (isset($arr1[1]) && $arr1[1] == '') {
                        $sym = $arr1[0];
                    } else if (isset($arr2[1]) && $arr2[1] == '') {
                        $sym = $arr2[0];
                    }
                    $tempNewArr = [];

                    if($balance['coin_balance'] < $total_trade_qty){

                        if ($symbol == "BTCUSDT") {
                            $coinData1['required_balance'] = ($total_trade_qty - ($balance['coin_balance'] / $BTCUSDT_price)) < 0 ? 0 : ($total_trade_qty - ($balance['coin_balance'] / $BTCUSDT_price));
                        } else {
                            $coinData1['required_balance'] = ($total_trade_qty - $balance['coin_balance']) < 0 ? 0 : ($total_trade_qty - $balance['coin_balance']);
                        }
                        
                        //required balance usd worth
                        $tarr = explode('USDT', $symbol);
                        if (isset($tarr[1]) && $tarr[1] == '') {
                            // echo "\r\n USDT coin";
                            
                            if($symbol == 'BTCUSDT'){
                                // $coinData1['required_balance_usd_worth'] = number_format($coinData1['required_balance'], 2);
                                $coinData1['required_balance_usd_worth'] = $coinData1['required_balance'];
                                $tempNewArr['required_balance_usd_worth'] = $coinData1['required_balance_usd_worth'];
                            }else{
                                $coinData1['required_balance_usd_worth'] = number_format(($coinData1['required_balance'] * $price), 2);
                                $tempNewArr['required_balance_usd_worth'] = $coinData1['required_balance_usd_worth'];
                            }
                            
                        } else {
                            // echo "\r\n BTC coin";
                            if ($symbol == "BTC") {
                                $coinData1['required_balance_usd_worth'] = number_format(($coinData1['required_balance'] * $BTCUSDT_price), 2);
                                $tempNewArr['required_balance_usd_worth'] = $coinData1['required_balance_usd_worth'];
                            } else {
                                $coinData1['required_balance_usd_worth'] = number_format(($coinData1['required_balance'] * $price * $BTCUSDT_price), 2);
                                $tempNewArr['required_balance_usd_worth'] = $coinData1['required_balance_usd_worth'];
                            }
                        }
                        
                        $coinData1['required_balance'] = number_format($coinData1['required_balance'], 8);
                        $tempNewArr['required_balance'] = $coinData1['required_balance'];

                    }else if($balance['coin_balance'] > $total_trade_qty){

                        if($symbol == "BTCUSDT"){
                            $coinData1['extra_balance'] = (($balance['coin_balance']/$BTCUSDT_price) - $total_trade_qty) < 0 ? 0 : (($balance['coin_balance']/$BTCUSDT_price) - $total_trade_qty);
                        }else {
                            $coinData1['extra_balance'] = ($balance['coin_balance'] - $total_trade_qty) < 0 ? 0 : ($balance['coin_balance'] - $total_trade_qty);
                        }
                        
                        //extra balance usd worth
                        $tarr = explode('USDT', $symbol);
                        if (isset($tarr[1]) && $tarr[1] == '') {
                            // echo "\r\n USDT coin";
                            if ($symbol == 'BTCUSDT') {
                                // $coinData1['extra_balance_usd_worth'] = number_format($coinData1['extra_balance'], 2);
                                $coinData1['extra_balance_usd_worth'] = $coinData1['extra_balance'];
                                $tempNewArr['extra_balance_usd_worth'] = $coinData1['extra_balance_usd_worth'];
                            } else {
                                $coinData1['extra_balance_usd_worth'] = number_format(($coinData1['extra_balance'] * $price), 2);
                                $tempNewArr['extra_balance_usd_worth'] = $coinData1['extra_balance_usd_worth'];
                            }
                        } else {
                            // echo "\r\n BTC coin";
                            if ($symbol == "BTC") {
                                $coinData1['extra_balance_usd_worth'] = number_format(($coinData1['extra_balance'] * $BTCUSDT_price), 2);
                                $tempNewArr['extra_balance_usd_worth'] = $coinData1['extra_balance_usd_worth'];
                            } else {
                                $coinData1['extra_balance_usd_worth'] = number_format(($coinData1['extra_balance'] * $price * $BTCUSDT_price), 2);
                                $tempNewArr['extra_balance_usd_worth'] = $coinData1['extra_balance_usd_worth'];
                            }
                        }

                        $coinData1['extra_balance'] = number_format($coinData1['extra_balance'], 8);
                        $tempNewArr['extra_balance'] = $coinData1['extra_balance'];
                    }

                    // echo "<pre>";
                    // print_r($coinData1);
                    // die('***********testing***************');
                    
                    //comitted balance usd worth
                    $tarr = explode('USDT', $symbol);
                    if (isset($tarr[1]) && $tarr[1] == '') {
                        // echo "\r\n USDT coin";
                        if ($symbol == 'BTCUSDT') {
                            $coinData1['comitted_balance_usd_worth'] = number_format($coinData1['comitted_balance'] * $price, 2);
                            $coinData1['comitted_balance'] = number_format($coinData1['comitted_balance'] * $price, 2);
                            $coinData1['balance_usd_worth'] = $coinData1['balance'];
                            // $coinData1['balance_usd_worth'] = number_format($coinData1['balance'], 2);

                            $tempNewArr['comitted_balance_usd_worth'] = $coinData1['comitted_balance_usd_worth'];
                            $tempNewArr['balance_usd_worth'] = $coinData1['balance_usd_worth'];
                        } else {
                            $coinData1['comitted_balance_usd_worth'] = number_format(($coinData1['comitted_balance'] * $price), 2);
                            $coinData1['balance_usd_worth'] = number_format(($coinData1['balance'] * $price), 2);

                            $tempNewArr['comitted_balance_usd_worth'] = $coinData1['comitted_balance_usd_worth'];
                            $tempNewArr['balance_usd_worth'] = $coinData1['balance_usd_worth'];
                        }
                    } else {
                        // echo "\r\n BTC coin";
                        if ($symbol == "BTC") {
                            $coinData1['comitted_balance_usd_worth'] = number_format(($coinData1['comitted_balance'] * $BTCUSDT_price), 2);
                            $coinData1['balance_usd_worth'] = number_format(($coinData1['balance'] * $BTCUSDT_price), 2);

                            $tempNewArr['comitted_balance_usd_worth'] = $coinData1['comitted_balance_usd_worth'];
                            $tempNewArr['balance_usd_worth'] = $coinData1['balance_usd_worth'];
                        } else {
                            $coinData1['comitted_balance_usd_worth'] = number_format(($coinData1['comitted_balance'] * $price * $BTCUSDT_price), 2);
                            $coinData1['balance_usd_worth'] = number_format(($coinData1['balance'] * $price * $BTCUSDT_price), 2);

                            $tempNewArr['comitted_balance_usd_worth'] = $coinData1['comitted_balance_usd_worth'];
                            $tempNewArr['balance_usd_worth'] = $coinData1['balance_usd_worth'];
                        }
                    }

                    $coinData1['comitted_balance'] = number_format($coinData1['comitted_balance'], 8);

                    $tempNewArr['comitted_balance'] = $coinData1['comitted_balance'];
                    
                    $tempNewArr['balance'] = $coinData1['balance'];
                    $tempNewArr['balance_usd_worth'] = $coinData1['balance_usd_worth'];

                    if(!empty($sym)){
                        $newArr[$sym][] = $tempNewArr;
                    }
                    
                    $market[] = $coinData1;
            
                }


                // echo "<Pre>";
                // print_r($newArr);
                // die('********************  <debug></debug> *******************');

                foreach ($market as $key => $value) {

                    //save coin according to pair
                    $temp_coin_balance_data = [];
                    $arr1 = explode('BTC', $value['symbol']);
                    $arr2 = explode('USDT', $value['symbol']);
                    if (($arr1[0] == '' && ((isset($arr1[1]) && $arr1[1] == '')) || ($arr2[0] == '' && (isset($arr1[1]) && $arr1[1] == '')))) {
                        //Do nothing
                    } else if (isset($arr1[1]) && $arr1[1] == '') {
                        $sym = $arr1[0];
                    } else if (isset($arr2[1]) && $arr2[1] == '') {
                        $sym = $arr2[0];
                    }

                    $currPairsArr = $newArr[$sym];

                    if($value['symbol'] == 'BTC'){
                        continue;
                    }else if($value['symbol'] == 'BTCUSDT'){
                        $total_comitted_balance = $currPairsArr[0]['comitted_balance'];
                        $total_comitted_balance = $total_comitted_balance;

                        $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'];
                        $total_comitted_balance_usd_worth = $total_comitted_balance_usd_worth;

                        // echo "<pre>";
                        // echo $currPairsArr[0]['balance'];
                        // echo "<br>";
                        // echo (float) str_replace(',', '',$currPairsArr[0]['balance']);
                        // die('sadfasdfasdfas');
                        $currPairsArr[0]['balance'] = (float) str_replace(',', '',$currPairsArr[0]['balance']);
                        $currPairsArr[0]['balance_usd_worth'] = (float) str_replace(',', '',$currPairsArr[0]['balance_usd_worth']);
                        
                        $total_balance = $currPairsArr[0]['balance'];
                        $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];

                        if ($total_balance < $total_comitted_balance) {

                            $total_required = $total_comitted_balance - $total_balance;
                            $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;

                            $market[$key]['required_balance'] = $total_required;
                            $market[$key]['required_balance_usd_worth'] = $total_required_usd_worth;

                        } else if ($total_balance > $total_comitted_balance) {

                            $total_extra = $total_balance - $total_comitted_balance;
                            $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;

                            $market[$key]['extra_balance'] = $total_extra;
                            $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;

                        }

                    }else{
                        
                        if(count($currPairsArr) > 1){

                            // $currPairsArr[0]['balance'] = (float) str_replace(',', '', $currPairsArr[0]['balance']);
                            // $currPairsArr[0]['balance_usd_worth'] = (float) str_replace(',', '', $currPairsArr[0]['balance_usd_worth']);
                            
                            // $currPairsArr[1]['balance'] = (float) str_replace(',', '', $currPairsArr[1]['balance']);
                            // $currPairsArr[1]['balance_usd_worth'] = (float) str_replace(',', '', $currPairsArr[1]['balance_usd_worth']);
                            
                            

                            // $currPairsArr[0]['comitted_balance'] = (float) str_replace(',', '', $currPairsArr[0]['comitted_balance']);
                            // $currPairsArr[0]['balance_usd_worth'] = (float) str_replace(',', '', $currPairsArr[0]['balance_usd_worth']);
                            
                            // $currPairsArr[1]['comitted_balance'] = (float) str_replace(',', '', $currPairsArr[1]['comitted_balance']);
                            // $currPairsArr[1]['balance_usd_worth'] = (float) str_replace(',', '', $currPairsArr[1]['balance_usd_worth']);


    
                            $total_comitted_balance = $currPairsArr[0]['comitted_balance'] + $currPairsArr[1]['comitted_balance'];
                            $total_comitted_balance = number_format($total_comitted_balance, 8);
                            
                            $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'] +  $currPairsArr[1]['comitted_balance_usd_worth'];
                            $total_comitted_balance_usd_worth = number_format($total_comitted_balance_usd_worth, 2);
                            
                            $total_balance = $currPairsArr[0]['balance'];
                            $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];
                            
                            if($total_balance < $total_comitted_balance){
    
                                $total_required = $total_comitted_balance - $total_balance;
                                $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;
    
                                $market[$key]['required_balance'] = number_format($total_required, 8);
                                $market[$key]['required_balance_usd_worth'] = number_format($total_required_usd_worth, 2);
    
                            }else if($total_balance > $total_comitted_balance){
     
                                $total_extra = $total_balance - $total_comitted_balance;
                                $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;
    
                                $market[$key]['extra_balance'] = number_format($total_extra, 8);
                                $market[$key]['extra_balance_usd_worth'] = number_format($total_extra_usd_worth, 2);
    
                            }
                            
                        }

                    }
                }

                $data['coin_market'] = $market;
            }
            
            if(!empty($data)){
    
                $message = array(
                    'status' => TRUE,
                    'data' => $data['coin_market'],
                    'message' => 'Data found scuccessfully',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
    
            } else {
    
                $message = array(
                    'status' => FALSE,
                    'message' => 'Data not found',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        
        }

    }//end manage_coins_post

    //manage_coins_test_post
    public function manage_coins_test_post(){

        // error_reporting(E_ALL & ~E_NOTICE);
        // ini_set('display_errors', E_ALL & ~E_NOTICE);

        $request = $this->post();

        if(empty($request['user_id']) || empty($request['exchange'])){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id, exchange can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
            $user_id = $request['user_id'];
            $exchange = $request['exchange'];
            $application_mode = (!empty($request['application_mode'])?$request['application_mode'] : 'live');

            $coins = $this->mod_api_calls->get_all_user_coins($user_id, $exchange);

            $minQtyArr = get_min_quantity($exchange);

            $temp_coin_balance_data = [];

            $data = [];
            if(!empty($coins)){

                $btc_arr = array('coin_name' => 'Bitcoin',
                    'symbol' => "BTC",
                    'coin_name' => "bitcoin",
                    'coin_logo' => "btc11.jpg",
                    'coin_keywords' => '#btc,#bitcoin,#BTC',
                );
    
                array_push($coins, $btc_arr);
    
                end($coins);
    
                $last_key = key($coins);
                $last_value = array_pop($coins);
                $coins = array_merge(array($last_key => $last_value), $coins);
    
                $currency = 'bitcoin';
                $market = array();

                $pricesArr = get_current_market_prices($exchange, []);

                // echo "<pre>";
                // echo "eschange $exchange <br>";
                // print_r($pricesArr);
                // die('ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss');

                $BTCUSDT_price = $pricesArr['BTCUSDT'];
                
                // $BTCUSDT_price = $this->mod_api_calls->get_last_price('BTCUSDT', $exchange);

                foreach ($coins as $coin) {
                    $coin_name = $coin['coin_name'];
                    $symbol = $coin['symbol'];
                    $response_coin_id = (string)$coin['_id'];
                    $coin_id = $coin->_id;
                    $balance = $this->mod_api_calls->get_coin_balance($user_id, $symbol, $exchange);
    
                    $amount = null;
                    $amount_status = null;
                    if ($symbol != "BTC") {
                        $price = $pricesArr[$symbol];

                        // $price = $this->mod_api_calls->get_last_price($symbol, $exchange);
                        
                        $open_trades = $this->mod_api_calls->get_open_trades($user_id, $symbol, $exchange, $application_mode);
                        $open_trades_count = count($open_trades);
                        $costAvg_trades = $this->mod_api_calls->get_costAvg_trades($user_id, $symbol, $exchange, $application_mode);
                        $costAvg_trades_count = count($costAvg_trades);
                        $open_trades = array_merge($open_trades, $costAvg_trades);
                        unset($costAvg_trades);

                        $change = $this->mod_api_calls->get_24_hour_price_change($symbol);
    
                        $per = $change['percentage'];
                        $per = number_format($per, 2);
                        $amount1 = $change['change'];

                        if ($per > 0) {
                            $amount = '<div class="text-success">'.number_format($per, 2). '% </div>';
                            $amount_status = 'green';
                        } elseif ($per < 0) {
                            $amount = '<div class="text-danger">'.number_format($per, 2) . '% </div>';
                            $amount_status = 'red';
                        } else {
                            $amount = '<div class="text-default">' . number_format($per, 2) . '% </div>';
                            $amount_status = 'dark-grey';
                        }
                    } else {
                        $price = 1;
                        $trade = 'N/A';
                    }
                    

                    $coin_balance = $balance['coin_balance'];
                    $total_trade_qty = 0;
                    $balance_error = false;
                    $usd_balance = 0;
                    $balance = 0; 
                    $balance_usd_worth = 0;
                    $comitted_balance = 0;
                    $comitted_balance_usd_worth = 0;
                    $extra_balance = 0;
                    $extra_balance_usd_worth = 0;
                    $required_balance = 0;
                    $required_balance_usd_worth = 0;

                    if (!empty($open_trades)) {
                        foreach ($open_trades as $tradee) {
                            $total_trade_qty += (float) $tradee['quantity'];
                        }
                    }

                    $tarr = explode('USDT', $symbol);
                    if (isset($tarr[1]) && $tarr[1] == '') {
                        // echo "\r\n USDT coin";
                        if($symbol == 'BTCUSDT'){
                            $usd_balance =  $coin_balance;

                            $coin_balance = $coin_balance * (1/$price);

                            $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                            $comitted_balance = $total_trade_qty;
                            $comitted_balance_usd_worth = $comitted_balance * $price;

                            //required
                            if ($balance_error) {
                                $required_balance = $total_trade_qty - $coin_balance;
                                $required_balance_usd_worth = $required_balance * $price;
                            } else { //extra
                                $extra_balance = $coin_balance - $total_trade_qty;
                                $extra_balance_usd_worth = $extra_balance * $price;
                            }

                        }else{
                            $usd_balance =  $coin_balance*$price;

                            $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                            $comitted_balance = $total_trade_qty;
                            $comitted_balance_usd_worth = $comitted_balance * $price;

                            //required
                            if ($balance_error) {
                                $required_balance = $total_trade_qty - $coin_balance;
                                $required_balance_usd_worth = $required_balance * $price;
                            } else { //extra
                                $extra_balance = $coin_balance - $total_trade_qty;
                                $extra_balance_usd_worth = $extra_balance * $price;
                            }
                        }

                        $convertamount = round($price, 5);
                    
                    } else {
                        // echo "\r\n BTC coin";
                        if($symbol == "BTC"){
                            
                            $usd_balance =  $coin_balance*$BTCUSDT_price;

                            $convertamount = round($BTCUSDT_price, 5);

                            $balance_error = false;
                            $comitted_balance = 0;
                            $comitted_balance_usd_worth = 0;
                            //required
                            if ($balance_error) {
                                $required_balance = 0;
                                $required_balance_usd_worth = 0;
                            } else { //extra
                                $extra_balance = 0;
                                $extra_balance_usd_worth = 0;
                            }
                        }else{

                            $convertamount = round($price*$BTCUSDT_price, 5);
                            
                            $usd_balance =  $coin_balance*$price*$BTCUSDT_price;

                            $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                            $comitted_balance = $total_trade_qty;
                            $comitted_balance_usd_worth = $comitted_balance*$price*$BTCUSDT_price;

                            //required
                            if ($balance_error) {
                                $required_balance = $total_trade_qty - $coin_balance;
                                $required_balance_usd_worth = $required_balance*$price*$BTCUSDT_price;
                            } else { //extra
                                $extra_balance = $coin_balance - $total_trade_qty;
                                $extra_balance_usd_worth = $extra_balance*$price*$BTCUSDT_price;
                            }

                        }
                    }

                    $balance_usd_worth = $usd_balance;
                    $coin_logo = $this->get_coin_image($symbol, $exchange);
                    $base64_logo = '';

                    $coinData1 = array(
                        'symbol' => $symbol,
                        'coin_name' => $coin_name,
                        'logo' => $base64_logo,
                        'coin_logo' => $coin_logo, 
                        'change' => $amount,
                        'change_status' => $amount_status,
                        'last_price' => $price,
                        'usd_amount' => $convertamount,
                        'trade' => $open_trades_count,
                        'costAvgTrade' => $costAvg_trades_count,
                        'coin_id' => $response_coin_id,
                        'balance' => num($coin_balance),
                        'balance_usd_worth' => num($balance_usd_worth),
                        'usd_balance' => num($usd_balance),
                        'balance_error' => $balance_error,
                        'comitted_balance' => num($comitted_balance),
                        'comitted_balance_usd_worth' => num($comitted_balance_usd_worth),
                        'base_currency_comitted_balance' => num($comitted_balance),
                        'base_currency_comitted_balance_usd_worth' => num($comitted_balance_usd_worth),
                    );

                    if ($balance_error) {
                        $coinData1['required_balance'] = num($required_balance);
                        $coinData1['required_balance_usd_worth'] = num($required_balance_usd_worth);
                    } else { //extra
                        $coinData1['extra_balance'] = num($extra_balance);
                        $coinData1['extra_balance_usd_worth'] = num($extra_balance_usd_worth);
                    }
                    //save coin according to pair
                    $temp_coin_balance_data = [];
                    $sym = $this->get_sym($symbol);
                    if(!empty($sym)){
                        $newArr[$sym][] = $coinData1;
                    }
                    
                    $market[] = $coinData1;

                }

                // die('-aaaaaaaaaaaaa');

                // echo "<pre>";
                foreach ($market as $key => $value) {

                    //save coin according to pair
                    $temp_coin_balance_data = [];
                    $sym = $this->get_sym($value['symbol']);
                    $currPairsArr = $newArr[$sym];
                    
                    if ($value['symbol'] == 'BTC') {

                        $market[$key]['balance'] = num($market[$key]['balance']);
                        $market[$key]['balance_usd_worth'] = number_format((float) $market[$key]['balance_usd_worth'], 2);
                        $market[$key]['extra_balance'] = num($market[$key]['extra_balance']);
                        $market[$key]['extra_balance_usd_worth'] = number_format((float) $market[$key]['extra_balance_usd_worth'], 2);
                        $market[$key]['comitted_balance'] = num($market[$key]['comitted_balance']);
                        $market[$key]['comitted_balance_usd_worth'] = number_format((float) $market[$key]['comitted_balance_usd_worth'], 2);

                        continue;
                    } else if ($value['symbol'] == 'BTCUSDT') {
                        $total_comitted_balance = $currPairsArr[0]['comitted_balance'];
                        $total_comitted_balance = $total_comitted_balance;

                        $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'];
                        $total_comitted_balance_usd_worth = $total_comitted_balance_usd_worth;

                        $total_balance = $currPairsArr[0]['balance'];
                        $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];

                        if ($total_balance < $total_comitted_balance) {

                            $total_required = $total_comitted_balance - $total_balance;
                            $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;

                            $market[$key]['required_balance'] = $total_required;
                            $market[$key]['required_balance_usd_worth'] = $total_required_usd_worth;

                        } else if ($total_balance > $total_comitted_balance) {

                            $total_extra = $total_balance - $total_comitted_balance;
                            $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;

                            $market[$key]['extra_balance'] = $total_extra;
                            $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;

                        }

                    } else {

                        if (count($currPairsArr) > 1) {

                            // if($value['symbol'] == 'XRPBTC' || $value['symbol'] == 'XRPUSDT'){
                            //     print_r($currPairsArr);
                            // }

                            $total_comitted_balance = $currPairsArr[0]['comitted_balance'] + $currPairsArr[1]['comitted_balance'];
                            $total_comitted_balance = $total_comitted_balance;

                            $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'] + $currPairsArr[1]['comitted_balance_usd_worth'];
                            $total_comitted_balance_usd_worth = $total_comitted_balance_usd_worth;

                            $total_balance = $currPairsArr[0]['balance'];
                            $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];

                            if ($total_balance < $total_comitted_balance) {

                                $total_required = $total_comitted_balance - $total_balance;
                                $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;

                                $market[$key]['required_balance'] = $total_required;
                                $market[$key]['required_balance_usd_worth'] = $total_required_usd_worth;

                            } else if ($total_balance >= $total_comitted_balance) {

                                $total_extra = $total_balance - $total_comitted_balance;
                                $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;

                                $market[$key]['extra_balance'] = $total_extra;
                                $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;

                            }

                            $market[$key]['comitted_balance'] = $total_comitted_balance;
                            $market[$key]['comitted_balance_usd_worth'] = $total_comitted_balance_usd_worth; 
                        }else{
                            $total_comitted_balance = $currPairsArr[0]['comitted_balance'];
                            $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'];

                            $total_balance = $currPairsArr[0]['balance'];
                            $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];

                            if ($total_balance < $total_comitted_balance) {

                                $total_required = $total_comitted_balance - $total_balance;
                                $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;

                                $market[$key]['required_balance'] = $total_required;
                                $market[$key]['required_balance_usd_worth'] = $total_required_usd_worth;

                            } else if ($total_balance > $total_comitted_balance) {

                                $total_extra = $total_balance - $total_comitted_balance;
                                $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;

                                $market[$key]['extra_balance'] = $total_extra;
                                $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;

                            }

                            $market[$key]['comitted_balance'] = $total_comitted_balance;
                            $market[$key]['comitted_balance_usd_worth'] = $total_comitted_balance_usd_worth;

                        }
                    }
                    
                    $market[$key]['balance'] = num($market[$key]['balance']);
                    $market[$key]['balance_usd_worth'] = number_format((float) $market[$key]['balance_usd_worth'], 2);
                    
                    if(isset($market[$key]['required_balance']) && $market[$key]['required_balance'] != 0){
                        $market[$key]['required_balance'] = num($market[$key]['required_balance']);
                        $market[$key]['required_balance_usd_worth'] = number_format((float) $market[$key]['required_balance_usd_worth'], 2);
                    }else{
                        unset($market[$key]['required_balance']);
                        unset($market[$key]['required_balance_usd_worth']);
                    }
                    
                    if(isset($market[$key]['required_balance']) && $market[$key]['required_balance'] != 0 && isset($market[$key]['extra_balance']) && $market[$key]['extra_balance'] != 0){
                        unset($market[$key]['extra_balance']);
                        unset($market[$key]['extra_balance_usd_worth']);
                    }else if(isset($market[$key]['extra_balance']) && $market[$key]['extra_balance'] != 0){
                        $market[$key]['extra_balance'] = num($market[$key]['extra_balance']);
                        $market[$key]['extra_balance_usd_worth'] = number_format((float) $market[$key]['extra_balance_usd_worth'], 2);
                    }else{
                        unset($market[$key]['extra_balance']);
                        unset($market[$key]['extra_balance_usd_worth']);
                    }

                    if(isset($market[$key]['comitted_balance'])){
                        $market[$key]['comitted_balance'] = num($market[$key]['comitted_balance']);
                        $market[$key]['comitted_balance_usd_worth'] = number_format((float) $market[$key]['comitted_balance_usd_worth'], 2);
                    }

                    $market[$key]['balance_error'] = $market[$key]['comitted_balance_usd_worth'] == $market[$key]['balance_usd_worth'] ? false : $market[$key]['balance_error'];

                    $symbol111 = $market[$key]['symbol']; 
                    $market[$key]['display_sell_btn'] = (!empty($market[$key]['extra_balance']) && !empty($minQtyArr[$symbol111]) && (float) $market[$key]['extra_balance'] >= $minQtyArr[$symbol111]['min_qty']) ? true : false;
                    
                    // $market[$key]['display_buy_btn'] = (!empty($market[$key]['required_balance'])  && !empty($minQtyArr[$symbol111]) && (float) $market[$key]['required_balance'] >= $minQtyArr[$symbol111]['min_qty']) ? true : false;
                    
                }


                $data['coin_market'] = $market;
            }            
            if(!empty($data)){
    
                $message = array(
                    'status' => TRUE,
                    'data' => $data['coin_market'],
                    // 'minQtyArr' => $minQtyArr,
                    'message' => 'Data found scuccessfully',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
    
            } else {
    
                $message = array(
                    'status' => FALSE,
                    'message' => 'Data not found',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        
        }

    }//end manage_coins_test_post
    
    //manage_coins_post
    public function manage_coins_post_06_21_2023(){

        // header('Content-type: application/json');
        // header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        // header("Access-Control-Allow-Origin: *");
		// header("Access-Control-Allow-Headers: *");
        // header("Access-Control-Allow-Credentials: true");
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *");

        $request = $this->post();

        if(empty($request['user_id']) || empty($request['exchange'])){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id, exchange can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
            $received_Token = $this->input->get_request_header('Authorization');
            $received_Token = str_replace("Bearer ", "", $received_Token);
            $received_Token = str_replace("Token ", "", $received_Token);
            $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
            $tokenData = json_decode($tokenData);
            //echo json_encode($tokenData->id);
            if($tokenData != false ){
                $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
                
                if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){
                
                    $user_id = $request['user_id'];
                    $exchange = $request['exchange'];
                    $application_mode = (!empty($request['application_mode'])?$request['application_mode'] : 'live');

                    $coins = $this->mod_api_calls->get_all_user_coins($user_id, $exchange);

                    $minQtyArr = get_min_quantity($exchange);

                    $temp_coin_balance_data = [];

                    $data = [];
                    if(!empty($coins)){

                        $btc_arr = array('coin_name' => 'Bitcoin',
                            'symbol' => "BTC",
                            'coin_name' => "bitcoin",
                            'coin_logo' => "btc11.jpg",
                            'coin_keywords' => '#btc,#bitcoin,#BTC',
                        );
            
                        array_push($coins, $btc_arr);
            
                        end($coins);
            
                        $last_key = key($coins);
                        $last_value = array_pop($coins);
                        $coins = array_merge(array($last_key => $last_value), $coins);
            
                        $currency = 'bitcoin';
                        $market = array();

                        $pricesArr = get_current_market_prices($exchange, []);

                        // echo "<pre>";
                        // echo "eschange $exchange <br>";
                        // print_r($pricesArr);
                        // die('ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss');

                        $BTCUSDT_price = $pricesArr['BTCUSDT'];
                        
                        // $BTCUSDT_price = $this->mod_api_calls->get_last_price('BTCUSDT', $exchange);

                        foreach ($coins as $coin) {
                            $coin_name = $coin['coin_name'];
                            $symbol = $coin['symbol'];
                            $response_coin_id = (string)$coin['_id'];
                            $coin_id = $coin->_id;
                            $balance = $this->mod_api_calls->get_coin_balance($user_id, $symbol, $exchange);
            
                            $amount = null;
                            $amount_status = null;
                            if ($symbol != "BTC") {
                                $price = $pricesArr[$symbol];

                                // $price = $this->mod_api_calls->get_last_price($symbol, $exchange);
                                
                                $open_trades = $this->mod_api_calls->get_open_trades($user_id, $symbol, $exchange, $application_mode);
                                $open_trades_count = count($open_trades);
                                $costAvg_trades = $this->mod_api_calls->get_costAvg_trades($user_id, $symbol, $exchange, $application_mode);
                                $costAvg_trades_count = count($costAvg_trades);
                                $open_trades = array_merge($open_trades, $costAvg_trades);

                                unset($costAvg_trades);

                                $change = $this->mod_api_calls->get_24_hour_price_change($symbol);
            
                                $per = $change['percentage'];
                                $per = number_format($per, 2);
                                //$amount1 = $change['change'];

                                if ($per > 0) {
                                    $amount = '<div class="text-success badge-success-24H ">'.number_format($per, 2). '% </div>';
                                    $amount_status = 'green';
                                } elseif ($per < 0) {
                                    $amount = '<div class="text-danger badge-danger-24H ">'.number_format($per, 2) . '% </div>';
                                    $amount_status = 'red';
                                } else {
                                    $amount = '<div class="text-default badge-success-24H ">' . number_format($per, 2) . '% </div>';
                                    $amount_status = 'dark-grey';
                                }
                            } else {
                                $price = 1;
                                $trade = 'N/A';
                            }
                            

                            $coin_balance = $balance['coin_balance'];
                            $total_trade_qty = 0;
                            $balance_error = false;
                            $usd_balance = 0;
                            $balance = 0; 
                            $balance_usd_worth = 0;
                            $comitted_balance = 0;
                            $comitted_balance_usd_worth = 0;
                            $extra_balance = 0;
                            $extra_balance_usd_worth = 0;
                            $required_balance = 0;
                            $required_balance_usd_worth = 0;

                            // if (!empty($open_trades)) { i have done this code according to new logic so the errors etc get resolved in any case errors are increased then comment the below code and uncomment this one (sheraz)..
                            //     foreach ($open_trades as $tradee) {
                            //         if(isset($tradee['cost_avg_array'])){
                            //             $total_trade_qty += (float) isset($tradee['quantity_all'])?$tradee['quantity_all']:$tradee['quantity'];    
                            //         }else{
                            //             $total_trade_qty += (float) $tradee['quantity'];
                            //         }
                            //     }
                            // }
                            if (!empty($open_trades)) {
                                foreach ($open_trades as $tradee) {
                                    if(isset($tradee['cost_avg_array'])){
                                        // if(empty($tradee['quantity_all'])){
                                            foreach($tradee['cost_avg_array'] as $value_cost_avg_array){
                                                if($value_cost_avg_array['order_sold'] != 'yes'){
                                                    $total_trade_qty += $value_cost_avg_array['filledQtyBuy'];        
                                                }
                                            }
                                        // }else{
                                        //     $total_trade_qty += (float) isset($tradee['quantity_all'])?$tradee['quantity_all']:$tradee['quantity'];        
                                        // }
                                    }else{
                                        $total_trade_qty += (float) $tradee['quantity'];
                                    }
                                }
                            }

                            $tarr = explode('USDT', $symbol);
                            if (isset($tarr[1]) && $tarr[1] == '') {
                                // echo "\r\n USDT coin";
                                if($symbol == 'BTCUSDT'){
                                    $usd_balance =  $coin_balance;

                                    $coin_balance = $coin_balance * (1/$price);

                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance * $price;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance * $price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance * $price;
                                    }

                                }else{
                                    $usd_balance =  $coin_balance*$price;

                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance * $price;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance * $price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance * $price;
                                    }
                                }

                                $convertamount = round($price, 5);
                            
                            } else {
                                // echo "\r\n BTC coin";
                                if($symbol == "BTC"){
                                    
                                    $usd_balance =  $coin_balance*$BTCUSDT_price;

                                    $convertamount = round($BTCUSDT_price, 5);

                                    $balance_error = false;
                                    $comitted_balance = 0;
                                    $comitted_balance_usd_worth = 0;
                                    //required
                                    if ($balance_error) {
                                        $required_balance = 0;
                                        $required_balance_usd_worth = 0;
                                    } else { //extra
                                        $extra_balance = 0;
                                        $extra_balance_usd_worth = 0;
                                    }
                                }else{

                                    $convertamount = round($price*$BTCUSDT_price, 5);
                                    
                                    $usd_balance =  $coin_balance*$price*$BTCUSDT_price;

                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance*$price*$BTCUSDT_price;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance*$price*$BTCUSDT_price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance*$price*$BTCUSDT_price;
                                    }

                                }
                            }

                            $balance_usd_worth = $usd_balance;
                            $coin_logo = $this->get_coin_image_new($symbol, $exchange);
                            $base64_logo = '';
                            
                            $coinData1 = array(
                                'symbol' => $symbol,
                                'coin_name' => $coin_name,
                                'logo' => $base64_logo,
                                'coin_logo' => $coin_logo, 
                                'change' => $amount,
                                'change_status' => $amount_status,
                                'last_price' => $price,
                                'usd_amount' => $convertamount,
                                'trade' => $open_trades_count,
                                'costAvgTrade' => $costAvg_trades_count,
                                'coin_id' => $response_coin_id,
                                'balance' => num($coin_balance),
                                'balance_usd_worth' => num($balance_usd_worth),
                                'usd_balance' => num($usd_balance),
                                'balance_error' => $balance_error,
                                'comitted_balance' => num($comitted_balance),
                                'comitted_balance_usd_worth' => num($comitted_balance_usd_worth),
                                'base_currency_comitted_balance' => num($comitted_balance),
                                'base_currency_comitted_balance_usd_worth' => num($comitted_balance_usd_worth),
                            );

                            if ($balance_error) {
                                $coinData1['required_balance'] = num($required_balance);
                                $coinData1['required_balance_usd_worth'] = num($required_balance_usd_worth);
                            } else { //extra
                                $coinData1['extra_balance'] = num($extra_balance);
                                $coinData1['extra_balance_usd_worth'] = num($extra_balance_usd_worth);
                            }
                            //save coin according to pair
                            $temp_coin_balance_data = [];
                            $sym = $this->get_sym($symbol);
                            if(!empty($sym)){
                                $newArr[$sym][] = $coinData1;
                            }
                            
                            $market[] = $coinData1;

                        }

                        // die('-aaaaaaaaaaaaa');

                        // echo "<pre>";
                        foreach ($market as $key => $value) {

                            //save coin according to pair
                            $temp_coin_balance_data = [];
                            $sym = $this->get_sym($value['symbol']);
                            $currPairsArr = $newArr[$sym];
                            
                            if ($value['symbol'] == 'BTC') {

                                $market[$key]['balance'] = num($market[$key]['balance']);
                                $market[$key]['balance_usd_worth'] = number_format((float) $market[$key]['balance_usd_worth'], 2);
                                $market[$key]['extra_balance'] = num($market[$key]['extra_balance']);
                                $market[$key]['extra_balance_usd_worth'] = number_format((float) $market[$key]['extra_balance_usd_worth'], 2);
                                $market[$key]['comitted_balance'] = num($market[$key]['comitted_balance']);
                                $market[$key]['comitted_balance_usd_worth'] = number_format((float) $market[$key]['comitted_balance_usd_worth'], 2);
                                continue;

                            } else if ($value['symbol'] == 'BTCUSDT') {

                                $total_comitted_balance = $currPairsArr[0]['comitted_balance'];
                                $total_comitted_balance = $total_comitted_balance;

                                $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'];
                                $total_comitted_balance_usd_worth = $total_comitted_balance_usd_worth;

                                $total_balance = $currPairsArr[0]['balance'];
                                $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];

                                if ($total_balance < $total_comitted_balance) {

                                    $total_required = $total_comitted_balance - $total_balance;
                                    $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;

                                    $market[$key]['required_balance'] = $total_required;
                                    $market[$key]['required_balance_usd_worth'] = str_replace('-','',$total_required_usd_worth);

                                } else if ($total_balance > $total_comitted_balance) {

                                    $total_extra = $total_balance - $total_comitted_balance;
                                    $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;

                                    $market[$key]['extra_balance'] = $total_extra;
                                    $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;

                                }

                            } else {

                                if (count($currPairsArr) > 1) {

                                    // if($value['symbol'] == 'XRPBTC' || $value['symbol'] == 'XRPUSDT'){
                                    //     print_r($currPairsArr);
                                    // }
                                   
                                    $total_comitted_balance = $currPairsArr[0]['comitted_balance'] + $currPairsArr[1]['comitted_balance'];
                                    $total_comitted_balance = $total_comitted_balance;

                                    $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'] + $currPairsArr[1]['comitted_balance_usd_worth'];
                                    $total_comitted_balance_usd_worth = $total_comitted_balance_usd_worth;

                                    $total_balance = $currPairsArr[0]['balance'];
                                    $worth1 = 0;
                                    $worth2 = 0;
                                    if(isset($currPairsArr[0]['balance_usd_worth']) && $currPairsArr[0]['balance_usd_worth'] > 0){
                                        $worth1 = $currPairsArr[0]['balance_usd_worth'];    
                                    }
                                    if(isset($currPairsArr[1]['balance_usd_worth']) && $currPairsArr[1]['balance_usd_worth'] > 0){
                                        $worth2 = $currPairsArr[1]['balance_usd_worth'];
                                    }
                                    
                                    $total_balance_usd_worth = 0.0;
                                    if($total_balance <= 0){
                                        $total_balance_usd_worth = 0;
                                    }else{
                                        if($worth1 > $worth2){
                                            $total_balance_usd_worth = (float)$worth1;
                                        }else if($worth2 > $worth1){
                                            $total_balance_usd_worth = (float)$worth2;
                                        }    
                                    }

                                    // if($currPairsArr[0]['symbol'] == 'XRPUSDT'){
                                    //         echo 'worth 1 usdt '.$total_balance_usd_worth;
                                    //         //echo 'worth 2 usdt '.$worth2;    
                                    // }   
                                    // if($currPairsArr[1]['symbol'] == 'XRPBTC'){
                                    //         echo 'worth 1 btc '.$total_balance_usd_worth;
                                    //         //echo 'worth 2 btc '.$worth2;    
                                    // }

                                    if ($total_balance < $total_comitted_balance) {

                                        $total_required = (float)$total_comitted_balance - (float)$total_balance;
                                        $total_required_usd_worth = (float)$total_comitted_balance_usd_worth - (float)$total_balance_usd_worth;

                                        $market[$key]['required_balance'] = $total_required;
                                        //$market[$key]['required_balance_usd_worth'] = $total_required_usd_worth;
                                        $market[$key]['required_balance_usd_worth'] = str_replace('-','',$total_required_usd_worth);
                                        unset($market[$key]['extra_balance']);
                                        unset($market[$key]['extra_balance_usd_worth']);
                                        // if($currPairsArr[0]['symbol'] == 'XRPUSDT'){
                                        //     echo '<pre>committed balance ';print_r($total_comitted_balance_usd_worth);
                                        //     echo '<pre>total usdt worth ';print_r($total_balance_usd_worth);
                                        //     //echo '<pre>tcb';print_r($market[$key]['required_balance_usd_worth']);
                                        //     // echo '<pre>tcbusd';print_r($total_comitted_balance_usd_worth);
                                        //     // echo '<pre>tbusdt';print_r($total_balance_usd_worth);   
                                        // }
                                        //  if($currPairsArr[0]['symbol'] == 'XRPBTC'){
                                        //     echo '<pre>Commited btc ';print_r($total_comitted_balance_usd_worth);
                                        //     echo '<pre>total Usdt worth';print_r($total_balance_usd_worth);
                                        //     //echo '<pre>tcb';print_r($market[$key]['required_balance_usd_worth']);exit;
                                        //     // echo '<pre>tcbusd';print_r($total_comitted_balance_usd_worth);
                                        //     // echo '<pre>tbusdt';print_r($total_balance_usd_worth);   
                                        // }

                                    } else if ($total_balance >= $total_comitted_balance) {
                                        $total_extra = $total_balance - $total_comitted_balance;
                                        $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;
                                        $market[$key]['extra_balance'] = $total_extra;
                                        $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;
                                        unset($market[$key]['required_balance']);
                                        unset($market[$key]['required_balance_usd_worth']);
                                    }
                                    $market[$key]['comitted_balance'] = $total_comitted_balance;
                                    $market[$key]['comitted_balance_usd_worth'] = $total_comitted_balance_usd_worth; 
                                }else{
                                    $total_comitted_balance = $currPairsArr[0]['comitted_balance'];
                                    $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'];

                                    $total_balance = $currPairsArr[0]['balance'];
                                    $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];

                                    if ($total_balance < $total_comitted_balance) {

                                        $total_required = $total_comitted_balance - $total_balance;
                                        $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;

                                        $market[$key]['required_balance'] = $total_required;
                                        $market[$key]['required_balance_usd_worth'] = $total_required_usd_worth;

                                    } else if ($total_balance > $total_comitted_balance) {

                                        $total_extra = $total_balance - $total_comitted_balance;
                                        $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;

                                        $market[$key]['extra_balance'] = $total_extra;
                                        $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;

                                    }

                                    $market[$key]['comitted_balance'] = $total_comitted_balance;
                                    $market[$key]['comitted_balance_usd_worth'] = $total_comitted_balance_usd_worth;

                                }
                            }
                            // echo "symbol ".$market[$key]['symbol']."\r\n";
                            // echo '<pre> commited balance : ';print_r($total_comitted_balance_usd_worth);
                            // echo '<pre> total balance USDT worth: ';print_r($total_balance_usd_worth);
                            $market[$key]['balance'] = num($market[$key]['balance']);
                            $market[$key]['balance_usd_worth'] = number_format((float) $market[$key]['balance_usd_worth'], 2);
                            
                            if(isset($market[$key]['required_balance']) && $market[$key]['required_balance'] != 0){
                                $market[$key]['required_balance'] = num($market[$key]['required_balance']);
                                $market[$key]['required_balance_usd_worth'] = number_format((float) $market[$key]['required_balance_usd_worth'], 2);
                            }else{
                                unset($market[$key]['required_balance']);
                                unset($market[$key]['required_balance_usd_worth']);
                            }
                            
                            if(isset($market[$key]['required_balance']) && $market[$key]['required_balance'] != 0 && isset($market[$key]['extra_balance']) && $market[$key]['extra_balance'] != 0){
                                unset($market[$key]['extra_balance']);
                                unset($market[$key]['extra_balance_usd_worth']);
                            }else if(isset($market[$key]['extra_balance']) && $market[$key]['extra_balance'] != 0){
                                $market[$key]['extra_balance'] = num($market[$key]['extra_balance']);
                                $market[$key]['extra_balance_usd_worth'] = number_format((float) $market[$key]['extra_balance_usd_worth'], 2);
                            }else{
                                unset($market[$key]['extra_balance']);
                                unset($market[$key]['extra_balance_usd_worth']);
                            }

                            if(isset($market[$key]['comitted_balance'])){
                                $market[$key]['comitted_balance'] = num($market[$key]['comitted_balance']);
                                $market[$key]['comitted_balance_usd_worth'] = number_format((float) $market[$key]['comitted_balance_usd_worth'], 2);
                            }

                            $market[$key]['balance_error'] = $market[$key]['comitted_balance_usd_worth'] == $market[$key]['balance_usd_worth'] ? false : $market[$key]['balance_error'];
                            
                            // echo "<pre>";
                            // echo "symbol ".$market[$key]['symbol']."\r\n";
                            // echo "balance ".$market[$key]['balance']."\r\n";
                            // echo "balance_usd_worth ".$market[$key]['balance_usd_worth']."\r\n";
                            // echo "comitted_balance ".$market[$key]['comitted_balance']."\r\n";
                            // echo "comitted_balance_usd_worth ".$market[$key]['comitted_balance_usd_worth']."\r\n";
                            // echo "required_balance ".$market[$key]['required_balance']."\r\n";
                            // echo "required_balance_usd_worth ".$market[$key]['required_balance_usd_worth']."\r\n";
                            // echo "extra_balance ".$market[$key]['extra_balance']."\r\n";
                            // echo "extra_balance_usd_worth ".$market[$key]['extra_balance_usd_worth']."\r\n";
                            // echo "balance_error ".$market[$key]['balance_error']."\r\n";
                            // echo "--------------------------------------------------------------------- \r\n";
                            $symbol111 = $market[$key]['symbol']; 
                            $market[$key]['display_sell_btn'] = (!empty($market[$key]['extra_balance']) && !empty($minQtyArr[$symbol111]) && (float) $market[$key]['extra_balance'] >= $minQtyArr[$symbol111]['min_qty']) ? true : false;
                            
                            // $market[$key]['display_buy_btn'] = (!empty($market[$key]['required_balance'])  && !empty($minQtyArr[$symbol111]) && (float) $market[$key]['required_balance'] >= $minQtyArr[$symbol111]['min_qty']) ? true : false;
                            
                        }


                        $data['coin_market'] = $market;
                    }
                    
                    if(!empty($data)){
            
                        $message = array(
                            'status' => TRUE,
                            'data' => $data['coin_market'],
                            // 'minQtyArr' => $minQtyArr,
                            'message' => 'Data found scuccessfully',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
            
                    } else {
            
                        $message = array(
                            'status' => FALSE,
                            'message' => 'Data not found',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
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

    }//end manage_coins_post

    public function manage_coins_post(){

        // header('Content-type: application/json');
        // header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        // header("Access-Control-Allow-Origin: *");
		// header("Access-Control-Allow-Headers: *");
        // header("Access-Control-Allow-Credentials: true");
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *");

        $request = $this->post();

        if(empty($request['user_id']) || empty($request['exchange'])){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id, exchange can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
            $received_Token = $this->input->get_request_header('Authorization');
            $received_Token = str_replace("Bearer ", "", $received_Token);
            $received_Token = str_replace("Token ", "", $received_Token);
            // echo '<pre>received_Token '; print_r($received_Token); exit;
            $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
            $tokenData = json_decode($tokenData);
            if ($tokenData === false) {
                $this->return_json_error_response('Invalid or Expired Token', REST_Controller::HTTP_UNAUTHORIZED);
            }
            //echo json_encode($tokenData->id);
            if($tokenData != false ){
                if (isset($tokenData->id)) {
                    $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
                }else{
                    $this->return_json_error_response('Invalid or Expired Token', REST_Controller::HTTP_UNAUTHORIZED);
                }

                // $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
                
                if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){
                
                    // $user_id = $request['user_id'];
                    // $exchange = $request['exchange'];
                    // $application_mode = (!empty($request['application_mode'])?$request['application_mode'] : 'live');

                    $user_id = $request['user_id'];
                    if (!preg_match('/^[0-9a-f]{24}$/', $user_id)) {
       
                        echo "Invalid MongoDB ObjectID: $user_id\n";
                        exit;
                    }
                    $exchange = $this->sanitize_and_validate_exchange($request['exchange']);
                    $application_mode = (!empty($request['application_mode']) ? $this->sanitize_and_validate_application_mode($request['application_mode']) : 'live');

                    $coins = $this->mod_api_calls->get_all_user_coins($user_id, $exchange);
                    // echo '<pre>'; print_r($coins); exit;

                    $minQtyArr = get_min_quantity($exchange);

                    $temp_coin_balance_data = [];

                    $data = [];
                    if(!empty($coins)){

                        $btc_arr = array('coin_name' => 'Bitcoin',
                            'symbol' => "BTC",
                            'coin_name' => "bitcoin",
                            'coin_logo' => "btc11.jpg",
                            'coin_keywords' => '#btc,#bitcoin,#BTC',
                        );
            
                        array_push($coins, $btc_arr);
            
                        end($coins);
            
                        $last_key = key($coins);
                        $last_value = array_pop($coins);
                        $coins = array_merge(array($last_key => $last_value), $coins);
            
                        $currency = 'bitcoin';
                        $market = array();

                        $pricesArr = get_current_market_prices($exchange, []);

                        // echo "<pre>";
                        // echo "eschange $exchange <br>";
                        // print_r($pricesArr);
                        // die('ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss');

                        $BTCUSDT_price = $pricesArr['BTCUSDT'];
                        
                        // $BTCUSDT_price = $this->mod_api_calls->get_last_price('BTCUSDT', $exchange);

                        foreach ($coins as $coin) {
                            $coin_name = $coin['coin_name'];
                            $symbol = $coin['symbol'];
                            $response_coin_id = (string)$coin['_id'];
                            $coin_id = $coin->_id;
                            $balance = $this->mod_api_calls->get_coin_balance($user_id, $symbol, $exchange);
            
                            $amount = null;
                            $amount_status = null;
                            if ($symbol != "BTC") {
                                
                                $price = $pricesArr[$symbol];
                                $marketPrice;
                                // echo "<pre>"; print_r($symbol);
                                // echo "<pre>"; print_r($pricesArr); exit;
                                $trigger_type_auto = "barrier_percentile_trigger";
                                $trigger_type_manual = "no";
                            
                                // $price = $this->mod_api_calls->get_last_price($symbol, $exchange);
                                //echo $user_id. "\r\n", $symbol. "\r\n", $application_mode. "\r\n"; exit;
                                // if ($symbol == 'AAVEUSDT') {
                                //     echo "<pre> symbol => "; print_r($symbol);
                                //     echo "<pre> user_id => "; print_r($user_id);
                                //     echo "<pre> exchange => "; print_r($exchange);
                                //     echo "<pre> application_mode => "; print_r($application_mode);
                                    
                                //     exit;
                                // }
                                $open_trades = $this->mod_api_calls->get_open_trades($user_id, $symbol, $exchange, $application_mode);
                                $open_trades_count = count($open_trades);
                                
                                $costAvg_trades = $this->mod_api_calls->get_costAvg_trades($user_id, $symbol, $exchange, $application_mode);
                                $costAvg_child_trades = $this->mod_api_calls->get_costAvg_child_trades($user_id, $symbol, $exchange, $application_mode);
                                //echo "<pre>", print_r($costAvg_trades); exit;
                                $costAvg_trades_count = count($costAvg_trades);
                                $open_trades = array_merge($open_trades, $costAvg_trades);
                              
                                //Open auto trades
                                $open_trades_auto = $this->mod_api_calls->get_open_trades($user_id, $symbol, $exchange, $application_mode, $trigger_type_auto);
                                $open_trades_auto_count = count($open_trades_auto);
                                $costAvg_trades_auto = $this->mod_api_calls->get_costAvg_trades($user_id, $symbol, $exchange, $application_mode, $trigger_type_auto);
                                $costAvg_trades_count_auto = count($costAvg_trades_auto);
                                $open_trades_auto = array_merge($open_trades_auto, $costAvg_trades_auto);
                           
                                //Open manual trades
                                $open_trades_manual = $this->mod_api_calls->get_open_trades($user_id, $symbol, $exchange, $application_mode, $trigger_type_manual);
                                $open_trades_manual_count = count($open_trades_manual);
                                $costAvg_trades_manual = $this->mod_api_calls->get_costAvg_trades($user_id, $symbol, $exchange,$application_mode, $trigger_type_manual);
                                $costAvg_trades_count_manual = count($costAvg_trades_manual);
                                $open_trades_manual = array_merge($open_trades_manual, $costAvg_trades_manual);

                                // echo $open_trades_manual_count. "open trades manual count\r\n";
                                // echo $costAvg_trades_count. "cost avg trades count\r\n"; exit;

                                unset($costAvg_trades);
                                unset($costAvg_trades_auto);
                                unset($costAvg_trades_manual);

                                $change = $this->mod_api_calls->get_24_hour_price_change($symbol);
            
                                $per = $change['percentage'];
                                $per = number_format($per, 2);
                                //$amount1 = $change['change'];

                                if ($per > 0) {
                                    $amount = '<div class="text-success badge-success-24H ">'.number_format($per, 2). '% </div>';
                                    $amount_status = 'green';
                                } elseif ($per < 0) {
                                    $amount = '<div class="text-danger badge-danger-24H ">'.number_format($per, 2) . '% </div>';
                                    $amount_status = 'red';
                                } else {
                                    $amount = '<div class="text-default badge-success-24H ">' . number_format($per, 2) . '% </div>';
                                    $amount_status = 'dark-grey';
                                }
                            } else {
                                $price = 1;
                                $trade = 'N/A';
                            }
                            

                            $coin_balance = $balance['coin_balance'];
                            $total_trade_qty = 0;
                            $market_price = 0;
                            $total_trade_qty_auto = 0;
                            $total_trade_qty_manual = 0;

                            $balance_error = false;
                            $usd_balance = 0;
                            $balance = 0; 
                            $balance_usd_worth = 0;
                            $comitted_balance = 0;
                            $comitted_balance_usd_worth = 0;
                            $market_comitted_balance_usd_worth = 0;
                            $twenty_four_hour_high_price_usd_worth = 0;
                            $extra_balance = 0;
                            $extra_balance_usd_worth = 0;
                            $required_balance = 0;
                            $required_balance_usd_worth = 0;

                            $comitted_balance_auto = 0;
                            $comitted_balance_usd_worth_auto = 0;

                            $comitted_balance_manual = 0;
                            $comitted_balance_usd_worth_manual = 0;
                            $cost_avg_trades_child_count = 0;
                            $test = '';
                            // if (!empty($open_trades)) { i have done this code according to new logic so the errors etc get resolved in any case errors are increased then comment the below code and uncomment this one (sheraz)..
                            //     foreach ($open_trades as $tradee) {
                            //         if(isset($tradee['cost_avg_array'])){
                            //             $total_trade_qty += (float) isset($tradee['quantity_all'])?$tradee['quantity_all']:$tradee['quantity'];    
                            //         }else{
                            //             $total_trade_qty += (float) $tradee['quantity'];
                            //         }
                            //     }
                            // }
                          
                            if (!empty($open_trades)) {
                                foreach ($open_trades as $tradee) {
                                    if ($tradee['trigger_type'] == 'no') {
                                        $market_price += $BTCUSDT_price * $tradee['price'] * $tradee['quantity'];
                                    }
                                    // $market_price += $tradee['quantity'] * $tradee['purchased_price'] * $tradee['buy_time_btc_price'];
                                    if(isset($tradee['cost_avg_array'])){
                                        // if(empty($tradee['quantity_all'])){
                                            foreach($tradee['cost_avg_array'] as $value_cost_avg_array){
                                                if($value_cost_avg_array['order_sold'] != 'yes'){
                                            
                                                    // $current_price = getCurrentPrice($symbol);
                                                    $market_price += $BTCUSDT_price * $value_cost_avg_array['filledQtyBuy'] * $value_cost_avg_array['filledPriceBuy']; 
                                                    // abs($current_price - $value_cost_avg_array['filledPriceBuy']);

                                                    $cost_avg_trades_child_count += 1;
                                                    $total_trade_qty += $value_cost_avg_array['filledQtyBuy']; 

                                                }
                                            }
                                        // }else{
                                        //     $total_trade_qty += (float) isset($tradee['quantity_all'])?$tradee['quantity_all']:$tradee['quantity'];        
                                        // }
                                    }else{
                                        
                                        $total_trade_qty += (float) $tradee['quantity'];
                                    }
                                }

                            }
                            
                            if (!empty($open_trades_auto)) {
                                foreach ($open_trades_auto as $tradee_auto) {
                                    if(isset($tradee_auto['cost_avg_array'])){
                                        // if(empty($tradee_auto['quantity_all'])){
                                            foreach($tradee_auto['cost_avg_array'] as $value_cost_avg_array_auto){
                                                if($value_cost_avg_array_auto['order_sold'] != 'yes'){
                                                    $total_trade_qty_auto += $value_cost_avg_array_auto['filledQtyBuy'];        
                                                }
                                            }
                                        // }else{
                                        //     $total_trade_qty_auto += (float) isset($tradee_auto['quantity_all'])?$tradee_auto['quantity_all']:$tradee_auto['quantity'];        
                                        // }
                                    }else{
                                        $total_trade_qty_auto += (float) $tradee_auto['quantity'];
                                    }
                                }
                            }

                            if (!empty($open_trades_manual)) {
                                foreach ($open_trades_manual as $tradee_manual) {
                                    if(isset($tradee_manual['cost_avg_array'])){
                                        // if(empty($tradee_manual['quantity_all'])){
                                            foreach($tradee_manual['cost_avg_array'] as $value_cost_avg_array_manual){
                                                if($value_cost_avg_array_manual['order_sold'] != 'yes'){
                                                    $total_trade_qty_manual += $value_cost_avg_array_manual['filledQtyBuy'];        
                                                }
                                            }
                                        // }else{
                                        //     $total_trade_qty_manual += (float) isset($tradee_manual['quantity_all'])?$tradee_manual['quantity_all']:$tradee_manual['quantity'];        
                                        // }
                                    }else{
                                        $total_trade_qty_manual += (float) $tradee_manual['quantity'];
                                    }
                                }
                            }

                            $tarr = explode('USDT', $symbol);
                            $tarr2 = explode('BNB', $symbol);
                            $tarr3 = explode('ETH', $symbol);
                            if (isset($tarr[1]) && $tarr[1] == '') {
                                //getting total accumulations of a user coin
                                $total_accumulations_USDT = 0.0;
                                $total_accumulations = $this->getTotalAccumulationsUserCoin($user_id, $exchange, $symbol);
                                $total_accumulations_USDT = (float) $total_accumulations['usdt_accumulation'];
                                // echo "<pre>"; print_r($total_accumulations); exit;
                                // echo "\r\n USDT coin";
                                if($symbol == 'BTCUSDT'){
                                    $usd_balance =  $coin_balance;

                                    $coin_balance = $coin_balance * (1/$price);

                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance * $price;
                                    $twenty_four_ath_price_get = getTwentyFourHourPrice($symbol);
                                    // $test = 'BTCUSDT '.$twenty_four_ath_price_get;
                                    $twenty_four_hour_high_price_usd_worth = $comitted_balance*(float)$twenty_four_ath_price_get;
                                    $market_comitted_balance_usd_worth = $market_price* (1/$BTCUSDT_price);

                                    $comitted_balance_auto = $total_trade_qty_auto;
                                    $comitted_balance_usd_worth_auto = $comitted_balance_auto * $price;

                                    $comitted_balance_manual = $total_trade_qty_manual;
                                    $comitted_balance_usd_worth_manual = $comitted_balance_manual * $price;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance * $price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance * $price;
                                    }

                                }else{
                              
                                    //old code
                                    $usd_balance =  $coin_balance*$price;
                                   
                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance * $price;
                                    $twenty_four_ath_price_get = getTwentyFourHourPrice($symbol);

                                    // $test = 'USDT coins'.$twenty_four_ath_price_get;
                                    $twenty_four_hour_high_price_usd_worth = $comitted_balance*(float)$twenty_four_ath_price_get;
                                    // echo "<pre> commited balance"; $comitted_balance;
                                    // echo $symbol;
                                    // echo "<pre>"; print_r($twenty_four_hour_high_price_usd_worth); exit;
                                    $market_comitted_balance_usd_worth = $market_price * (1/$BTCUSDT_price); //test change
                                    
                                    $comitted_balance_auto = $total_trade_qty_auto;
                                    $comitted_balance_usd_worth_auto = $comitted_balance_auto * $price;

                                    $comitted_balance_manual = $total_trade_qty_manual;
                                    $comitted_balance_usd_worth_manual = $comitted_balance_manual * $price;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance * $price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance * $price;
                                    }
                                }

                                $convertamount = round($price, 5);
                            
                            }elseif (isset($tarr2[1]) && $tarr2[1] == '') {
                                //getting total accumulations of a user coin
                                $total_accumulations_BNB = 0.0;
                                $total_accumulations = $this->getTotalAccumulationsUserCoin($user_id, $exchange, $symbol);
                                $total_accumulations_BNB = (float) $total_accumulations['usdt_accumulation'];
                                // echo "<pre>"; print_r($total_accumulations); exit;
                                // echo "\r\n USDT coin";
                                if($symbol == 'BNB'){
                                    $usd_balance =  $coin_balance;

                                    $coin_balance = $coin_balance * (1/$price);

                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance * $price;
                                    $twenty_four_ath_price_get = getTwentyFourHourPrice($symbol);
                                    // $test = 'BTCUSDT '.$twenty_four_ath_price_get;
                                    $twenty_four_hour_high_price_usd_worth = $comitted_balance*(float)$twenty_four_ath_price_get;
                                    $market_comitted_balance_usd_worth = $market_price* (1/$BTCUSDT_price);

                                    $comitted_balance_auto = $total_trade_qty_auto;
                                    $comitted_balance_usd_worth_auto = $comitted_balance_auto * $price;

                                    $comitted_balance_manual = $total_trade_qty_manual;
                                    $comitted_balance_usd_worth_manual = $comitted_balance_manual * $price;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance * $price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance * $price;
                                    }

                                }else{
                                    //old code
                                    $usd_balance =  $coin_balance*$price;
                                   
                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance * $price;
                                    $twenty_four_ath_price_get = getTwentyFourHourPrice($symbol);
                                    // echo "<pre>"; print_r($twenty_four_ath_price_get); exit;
                                    // $test = 'USDT coins'.$twenty_four_ath_price_get;
                                    $twenty_four_hour_high_price_usd_worth = $comitted_balance*(float)$twenty_four_ath_price_get;
                                    // echo "<pre> commited balance"; $comitted_balance;
                                    // echo $symbol;
                                    // echo "<pre>"; print_r($twenty_four_hour_high_price_usd_worth); exit;
                                    $market_comitted_balance_usd_worth = $market_price * (1/$BTCUSDT_price); //test change
                                    
                                    $comitted_balance_auto = $total_trade_qty_auto;
                                    $comitted_balance_usd_worth_auto = $comitted_balance_auto * $price;

                                    $comitted_balance_manual = $total_trade_qty_manual;
                                    $comitted_balance_usd_worth_manual = $comitted_balance_manual * $price;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance * $price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance * $price;
                                    }
                                }

                                $convertamount = round($price, 5);
                            
                            }elseif (isset($tarr3[1]) && $tarr3[1] == '') {
                                //getting total accumulations of a user coin
                                $total_accumulations_BNB = 0.0;
                                $total_accumulations = $this->getTotalAccumulationsUserCoin($user_id, $exchange, $symbol);
                                $total_accumulations_BNB = (float) $total_accumulations['usdt_accumulation'];
                                // echo "<pre>"; print_r($total_accumulations); exit;
                                // echo "\r\n USDT coin";
                                if($symbol == 'ETH'){
                                    $usd_balance =  $coin_balance;

                                    $coin_balance = $coin_balance * (1/$price);

                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance * $price;
                                    $twenty_four_ath_price_get = getTwentyFourHourPrice($symbol);
                                    // $test = 'BTCUSDT '.$twenty_four_ath_price_get;
                                    $twenty_four_hour_high_price_usd_worth = $comitted_balance*(float)$twenty_four_ath_price_get;
                                    $market_comitted_balance_usd_worth = $market_price* (1/$BTCUSDT_price);

                                    $comitted_balance_auto = $total_trade_qty_auto;
                                    $comitted_balance_usd_worth_auto = $comitted_balance_auto * $price;

                                    $comitted_balance_manual = $total_trade_qty_manual;
                                    $comitted_balance_usd_worth_manual = $comitted_balance_manual * $price;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance * $price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance * $price;
                                    }

                                }else{
                                    //old code
                                    $usd_balance =  $coin_balance*$price;
                                   
                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance * $price;
                                    $twenty_four_ath_price_get = getTwentyFourHourPrice($symbol);
                                    // echo "<pre>"; print_r($twenty_four_ath_price_get); exit;
                                    // $test = 'USDT coins'.$twenty_four_ath_price_get;
                                    $twenty_four_hour_high_price_usd_worth = $comitted_balance*(float)$twenty_four_ath_price_get;
                                    // echo "<pre> commited balance"; $comitted_balance;
                                    // echo $symbol;
                                    // echo "<pre>"; print_r($twenty_four_hour_high_price_usd_worth); exit;
                                    $market_comitted_balance_usd_worth = $market_price * (1/$BTCUSDT_price); //test change
                                    
                                    $comitted_balance_auto = $total_trade_qty_auto;
                                    $comitted_balance_usd_worth_auto = $comitted_balance_auto * $price;

                                    $comitted_balance_manual = $total_trade_qty_manual;
                                    $comitted_balance_usd_worth_manual = $comitted_balance_manual * $price;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance * $price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance * $price;
                                    }
                                }

                                $convertamount = round($price, 5);
                            
                            }else {
                                 //getting total accumulations of a user coin
                                 $total_accumulations_BTC = 0.0;
                                 $total_accumulations = $this->getTotalAccumulationsUserCoin($user_id, $exchange, $symbol);
                                 $total_accumulations_BTC = (float) $total_accumulations['btc_accumulation'];
                                 // echo "<pre>"; print_r($total_accumulations); exit;
                                // echo "\r\n BTC coin";
                                if($symbol == "BTC"){
                                    
                                    $usd_balance =  $coin_balance*$BTCUSDT_price;

                                    $convertamount = round($BTCUSDT_price, 5);

                                    $balance_error = false;
                                    $comitted_balance = 0;
                                    $comitted_balance_usd_worth = 0;
                                    $test = 'BTC ';
                                    // $balance_error_auto = false;
                                    $comitted_balance_auto = 0;
                                    $comitted_balance_usd_worth_auto = 0;

                                    // $balance_error_manual = false;
                                    $comitted_balance_manual = 0;
                                    $comitted_balance_usd_worth_manual = 0;
                                    //required
                                    if ($balance_error) {
                                        $required_balance = 0;
                                        $required_balance_usd_worth = 0;
                                    } else { //extra
                                        $extra_balance = 0;
                                        $extra_balance_usd_worth = 0;
                                    }
                                }else{

                                    $convertamount = round($price*$BTCUSDT_price, 5);
                                    
                                    $usd_balance =  $coin_balance*$price*$BTCUSDT_price;
                                    // if ($market_price != '') {
                                    //     echo "<pre>"; print_r($symbol);
                                    //     echo "<pre>"; print_r($market_price); exit;
                                    // }
                                    
                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance*$price*$BTCUSDT_price;
                                    $market_comitted_balance_usd_worth = $market_price;
                                    $twenty_four_ath_price_get = getTwentyFourHourPrice($symbol);
                                    // echo "<pre>"; print_r($twenty_four_ath_price_get); exit;
                                    // $test = 'BTC coins '. $twenty_four_ath_price_get;
                                    // if ($comitted_balance_usd_worth > 0) {
                                    //     echo '<pre>BTCUSDT price :: '; print_r($BTCUSDT_price);
                                    //     echo '<pre>comitted_balance_usd_worth :: '; print_r($comitted_balance_usd_worth);
                                    //     echo '<pre>Symbol :: '; print_r($symbol);
                                    //     echo "<pre>twenty_four_ath_price_get :: "; print_r($twenty_four_ath_price_get); exit;
                                    // }

                                    // if ($comitted_balance_usd_worth > 0) {
                                    //     echo '<pre>BTCUSDT price :: '; print_r($BTCUSDT_price);
                                    //     echo '<pre>comitted_balance_usd_worth :: '; print_r($comitted_balance_usd_worth);
                                    //     echo '<pre>Symbol :: '; print_r($symbol);
                                    //     echo "<pre>market_comitted_balance_usd_worth :: "; print_r($market_comitted_balance_usd_worth); exit;
                                    // }
                                    
                                    $twenty_four_hour_high_price_usd_worth = $comitted_balance*(float)$twenty_four_ath_price_get*$BTCUSDT_price;
                                    
                                    // if ($twenty_four_ath_price_get > 0 && $symbol != 'TRXBTC') {
                                    //     echo $symbol;
                                    //     echo "<br>";
                                    //     echo $comitted_balance;
                                    //     echo "<pre>"; print_r($twenty_four_ath_price_get); exit;
                                    // }
                                    // echo "<pre>"; print_r($market_comitted_balance_usd_worth); exit;

                                    $comitted_balance_auto = $total_trade_qty_auto;
                                    $comitted_balance_usd_worth_auto = $comitted_balance_auto*$price*$BTCUSDT_price;

                                    $comitted_balance_manual = $total_trade_qty_manual;
                                    $comitted_balance_usd_worth_manual = $comitted_balance_manual*$price*$BTCUSDT_price;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance*$price*$BTCUSDT_price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance*$price*$BTCUSDT_price;
                                    }

                                }
                            }

                            $balance_usd_worth = $usd_balance;
                            $coin_logo = $this->get_coin_image_new($symbol, $exchange);
                            $base64_logo = '';
                            //getting total trades of that coin
                            $total_trades = $this->totalTradeWorth($symbol, $exchange, $user_id);
                            //getting open trades of that coin
                            $open_trades_worth = $this->openTradesWorth($symbol, $exchange, $user_id);
                            
                            

                            $coinData1 = array(
                                'symbol' => $symbol,
                                'coin_name' => $coin_name,
                                'logo' => $base64_logo,
                                'coin_logo' => $coin_logo, 
                                'total_trades' => isset($total_trades) ? (float) $total_trades : 0,
                                'change' => $amount,
                                'change_status' => $amount_status,
                                'last_price' => $price,
                                'usd_amount' => $convertamount,
                                'open_trades' => $open_trades_worth,
                                'trade' => $open_trades_count,
                                'costAvgTrade' => $costAvg_trades_count,
                                'costAvgChildTrade' => ($cost_avg_trades_child_count -1),
                                'coin_id' => $response_coin_id,
                                'balance' => num($coin_balance),
                                'balance_usd_worth' => num($balance_usd_worth),
                                'usd_balance' => num($usd_balance),
                                'balance_error' => $balance_error,
                                'comitted_balance' => num($comitted_balance),
                                'comitted_balance_usd_worth' => num($comitted_balance_usd_worth),
                                'base_currency_comitted_balance' => num($comitted_balance),
                                'base_currency_comitted_balance_usd_worth' => num($comitted_balance_usd_worth),
                                'total_accumulations_USDT' => isset($total_accumulations_USDT) ? (float) $total_accumulations_USDT : 0,
                                'total_accumulations_BTC' => isset($total_accumulations_BTC) ? (float) $total_accumulations_BTC : 0,

                                //adding new fields for auto and manual trades
                                'comitted_balance_manual' => num($comitted_balance_usd_worth_manual),
                                // 'comitted_balance_usd_worth_manual' => num($comitted_balance_usd_worth_manual),
                                'comitted_balance_auto' => num($comitted_balance_usd_worth_auto),
                                'market_comitted_balance_usd_worth' => num($market_comitted_balance_usd_worth),
                                'twenty_four_hour_high_price_usd_worth' => num($twenty_four_hour_high_price_usd_worth),
                                'total_trade_qty' => $total_trade_qty,
                            );
                            // if ($symbol != 'BTC' && $symbol != 'TRXBTC' && $symbol != 'EOSBTC' && $symbol != 'POEBTC' ) {
                            //     echo "<pre>"; print_r($coinData1); exit;    
                            // }

                            if ($balance_error) {
                                $coinData1['required_balance'] = num($required_balance);
                                $coinData1['required_balance_usd_worth'] = num($required_balance_usd_worth);
                            } else { //extra
                                $coinData1['extra_balance'] = num($extra_balance);
                                $coinData1['extra_balance_usd_worth'] = num($extra_balance_usd_worth);
                            }
                            //save coin according to pair
                            $temp_coin_balance_data = [];
                            $sym = $this->get_sym($symbol);
                            if(!empty($sym)){
                                $newArr[$sym][] = $coinData1;
                            }
                            
                            $market[] = $coinData1;

                        }

                        // die('-aaaaaaaaaaaaa');

                        // echo "<pre>";
                        foreach ($market as $key => $value) {

                            //save coin according to pair
                            $temp_coin_balance_data = [];
                            $sym = $this->get_sym($value['symbol']);
                            // echo '<pre>';print_r($);
                            $currPairsArr = $newArr[$sym];
                            
                            if ($value['symbol'] == 'BTC') {

                                $market[$key]['balance'] = num($market[$key]['balance']);
                                $market[$key]['balance_usd_worth'] = number_format((float) $market[$key]['balance_usd_worth'], 2);
                                $market[$key]['extra_balance'] = num($market[$key]['extra_balance']);
                                $market[$key]['extra_balance_usd_worth'] = number_format((float) $market[$key]['extra_balance_usd_worth'], 2);
                                $market[$key]['comitted_balance'] = num($market[$key]['comitted_balance']);
                                $market[$key]['comitted_balance_usd_worth'] = number_format((float) $market[$key]['comitted_balance_usd_worth'], 2);
                                //for auto
                                $market[$key]['comitted_balance_auto'] = num($market[$key]['comitted_balance_auto']);
                                $market[$key]['comitted_balance_usd_worth_auto'] = number_format((float) $market[$key]['comitted_balance_usd_worth_auto'], 2);
                                 //for manual
                                 $market[$key]['comitted_balance_manual'] = num($market[$key]['comitted_balance_manual']);
                                 $market[$key]['comitted_balance_usd_worth_manual'] = number_format((float) $market[$key]['comitted_balance_usd_worth_manual'], 2);
                                continue;

                            } else if ($value['symbol'] == 'BTCUSDT') {

                                $total_comitted_balance = $currPairsArr[0]['comitted_balance'];
                                $total_comitted_balance = $total_comitted_balance;

                                $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'];
                                $total_comitted_balance_usd_worth = $total_comitted_balance_usd_worth;

                                $total_balance = $currPairsArr[0]['balance'];
                                $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];

                                if ($total_balance < $total_comitted_balance) {

                                    $total_required = $total_comitted_balance - $total_balance;
                                    $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;

                                    $market[$key]['required_balance'] = $total_required;
                                    $market[$key]['required_balance_usd_worth'] = str_replace('-','',$total_required_usd_worth);

                                } else if ($total_balance > $total_comitted_balance) {

                                    $total_extra = $total_balance - $total_comitted_balance;
                                    $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;

                                    $market[$key]['extra_balance'] = $total_extra;
                                    $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;

                                }

                            } else {

                                if (count($currPairsArr) > 1) {

                                    // if($value['symbol'] == 'XRPBTC' || $value['symbol'] == 'XRPUSDT'){
                                    //     print_r($currPairsArr);
                                    // }
                                   
                                    $total_comitted_balance = $currPairsArr[0]['comitted_balance'] + $currPairsArr[1]['comitted_balance'];
                                    $total_comitted_balance = $total_comitted_balance;

                                    $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'] + $currPairsArr[1]['comitted_balance_usd_worth'];
                                    $total_comitted_balance_usd_worth = $total_comitted_balance_usd_worth;

                                    $total_balance = $currPairsArr[0]['balance'];
                                    $worth1 = 0;
                                    $worth2 = 0;
                                    if(isset($currPairsArr[0]['balance_usd_worth']) && $currPairsArr[0]['balance_usd_worth'] > 0){
                                        $worth1 = $currPairsArr[0]['balance_usd_worth'];    
                                    }
                                    if(isset($currPairsArr[1]['balance_usd_worth']) && $currPairsArr[1]['balance_usd_worth'] > 0){
                                        $worth2 = $currPairsArr[1]['balance_usd_worth'];
                                    }
                                    
                                    $total_balance_usd_worth = 0.0;
                                    if($total_balance <= 0){
                                        $total_balance_usd_worth = 0;
                                    }else{
                                        if($worth1 > $worth2){
                                            $total_balance_usd_worth = (float)$worth1;
                                        }else if($worth2 > $worth1){
                                            $total_balance_usd_worth = (float)$worth2;
                                        }    
                                    }

                                    // if($currPairsArr[0]['symbol'] == 'XRPUSDT'){
                                    //         echo 'worth 1 usdt '.$total_balance_usd_worth;
                                    //         //echo 'worth 2 usdt '.$worth2;    
                                    // }   
                                    // if($currPairsArr[1]['symbol'] == 'XRPBTC'){
                                    //         echo 'worth 1 btc '.$total_balance_usd_worth;
                                    //         //echo 'worth 2 btc '.$worth2;    
                                    // }

                                    if ($total_balance < $total_comitted_balance) {

                                        $total_required = (float)$total_comitted_balance - (float)$total_balance;
                                        $total_required_usd_worth = (float)$total_comitted_balance_usd_worth - (float)$total_balance_usd_worth;

                                        $market[$key]['required_balance'] = $total_required;
                                        //$market[$key]['required_balance_usd_worth'] = $total_required_usd_worth;
                                        $market[$key]['required_balance_usd_worth'] = str_replace('-','',$total_required_usd_worth);
                                        unset($market[$key]['extra_balance']);
                                        unset($market[$key]['extra_balance_usd_worth']);
                                        // if($currPairsArr[0]['symbol'] == 'XRPUSDT'){
                                        //     echo '<pre>committed balance ';print_r($total_comitted_balance_usd_worth);
                                        //     echo '<pre>total usdt worth ';print_r($total_balance_usd_worth);
                                        //     //echo '<pre>tcb';print_r($market[$key]['required_balance_usd_worth']);
                                        //     // echo '<pre>tcbusd';print_r($total_comitted_balance_usd_worth);
                                        //     // echo '<pre>tbusdt';print_r($total_balance_usd_worth);   
                                        // }
                                        //  if($currPairsArr[0]['symbol'] == 'XRPBTC'){
                                        //     echo '<pre>Commited btc ';print_r($total_comitted_balance_usd_worth);
                                        //     echo '<pre>total Usdt worth';print_r($total_balance_usd_worth);
                                        //     //echo '<pre>tcb';print_r($market[$key]['required_balance_usd_worth']);exit;
                                        //     // echo '<pre>tcbusd';print_r($total_comitted_balance_usd_worth);
                                        //     // echo '<pre>tbusdt';print_r($total_balance_usd_worth);   
                                        // }

                                    } else if ($total_balance >= $total_comitted_balance) {
                                        $total_extra = $total_balance - $total_comitted_balance;
                                        $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;
                                        $market[$key]['extra_balance'] = $total_extra;
                                        $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;
                                        unset($market[$key]['required_balance']);
                                        unset($market[$key]['required_balance_usd_worth']);
                                    }
                                    $market[$key]['comitted_balance'] = $total_comitted_balance;
                                    $market[$key]['comitted_balance_usd_worth'] = $total_comitted_balance_usd_worth; 
                                }else{
                                    $total_comitted_balance = $currPairsArr[0]['comitted_balance'];
                                    $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'];

                                    $total_balance = $currPairsArr[0]['balance'];
                                    $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];

                                    if ($total_balance < $total_comitted_balance) {

                                        $total_required = $total_comitted_balance - $total_balance;
                                        $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;

                                        $market[$key]['required_balance'] = $total_required;
                                        $market[$key]['required_balance_usd_worth'] = $total_required_usd_worth;

                                    } else if ($total_balance > $total_comitted_balance) {

                                        $total_extra = $total_balance - $total_comitted_balance;
                                        $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;

                                        $market[$key]['extra_balance'] = $total_extra;
                                        $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;

                                    }

                                    $market[$key]['comitted_balance'] = $total_comitted_balance;
                                    $market[$key]['comitted_balance_usd_worth'] = $total_comitted_balance_usd_worth;

                                }
                            }
                            // echo "symbol ".$market[$key]['symbol']."\r\n";
                            // echo '<pre> commited balance : ';print_r($total_comitted_balance_usd_worth);
                            // echo '<pre> total balance USDT worth: ';print_r($total_balance_usd_worth);
                            $market[$key]['balance'] = num($market[$key]['balance']);
                            $market[$key]['balance_usd_worth'] = number_format((float) $market[$key]['balance_usd_worth'], 2);
                            
                            if(isset($market[$key]['required_balance']) && $market[$key]['required_balance'] != 0){
                                $market[$key]['required_balance'] = num($market[$key]['required_balance']);
                                $market[$key]['required_balance_usd_worth'] = number_format((float) $market[$key]['required_balance_usd_worth'], 2);
                            }else{
                                unset($market[$key]['required_balance']);
                                unset($market[$key]['required_balance_usd_worth']);
                            }
                            
                            if(isset($market[$key]['required_balance']) && $market[$key]['required_balance'] != 0 && isset($market[$key]['extra_balance']) && $market[$key]['extra_balance'] != 0){
                                unset($market[$key]['extra_balance']);
                                unset($market[$key]['extra_balance_usd_worth']);
                            }else if(isset($market[$key]['extra_balance']) && $market[$key]['extra_balance'] != 0){
                                $market[$key]['extra_balance'] = num($market[$key]['extra_balance']);
                                $market[$key]['extra_balance_usd_worth'] = number_format((float) $market[$key]['extra_balance_usd_worth'], 2);
                            }else{
                                unset($market[$key]['extra_balance']);
                                unset($market[$key]['extra_balance_usd_worth']);
                            }

                            if(isset($market[$key]['comitted_balance'])){
                                $market[$key]['comitted_balance'] = num($market[$key]['comitted_balance']);
                                $market[$key]['comitted_balance_usd_worth'] = number_format((float) $market[$key]['comitted_balance_usd_worth'], 2);
                            }

                            $market[$key]['balance_error'] = $market[$key]['comitted_balance_usd_worth'] == $market[$key]['balance_usd_worth'] ? false : $market[$key]['balance_error'];
                            
                            // echo "<pre>";
                            // echo "symbol ".$market[$key]['symbol']."\r\n";
                            // echo "balance ".$market[$key]['balance']."\r\n";
                            // echo "balance_usd_worth ".$market[$key]['balance_usd_worth']."\r\n";
                            // echo "comitted_balance ".$market[$key]['comitted_balance']."\r\n";
                            // echo "comitted_balance_usd_worth ".$market[$key]['comitted_balance_usd_worth']."\r\n";
                            // echo "required_balance ".$market[$key]['required_balance']."\r\n";
                            // echo "required_balance_usd_worth ".$market[$key]['required_balance_usd_worth']."\r\n";
                            // echo "extra_balance ".$market[$key]['extra_balance']."\r\n";
                            // echo "extra_balance_usd_worth ".$market[$key]['extra_balance_usd_worth']."\r\n";
                            // echo "balance_error ".$market[$key]['balance_error']."\r\n";
                            // echo "--------------------------------------------------------------------- \r\n";
                            $symbol111 = $market[$key]['symbol']; 
                            $market[$key]['display_sell_btn'] = (!empty($market[$key]['extra_balance']) && !empty($minQtyArr[$symbol111]) && (float) $market[$key]['extra_balance'] >= $minQtyArr[$symbol111]['min_qty']) ? true : false;
                            
                            // $market[$key]['display_buy_btn'] = (!empty($market[$key]['required_balance'])  && !empty($minQtyArr[$symbol111]) && (float) $market[$key]['required_balance'] >= $minQtyArr[$symbol111]['min_qty']) ? true : false;
                            
                        }


                        $data['coin_market'] = $market;
                    }
                    
                    if(!empty($data)){
            
                        $message = array(
                            'status' => TRUE,
                            'data' => $data['coin_market'],
                            // 'minQtyArr' => $minQtyArr,
                            'message' => 'Data found scuccessfully',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
            
                    } else {
            
                        $message = array(
                            'status' => FALSE,
                            'message' => 'Data not found',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
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

    }//end manage_coins_post

    public function calculate_monthly_accumulations_post(){
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *");

        $request = $this->post();

        if(empty($request['user_id']) || empty($request['exchange'])){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id, exchange can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
            $received_Token = $this->input->get_request_header('Authorization');
            $received_Token = str_replace("Bearer ", "", $received_Token);
            $received_Token = str_replace("Token ", "", $received_Token);
            // echo '<pre>received_Token '; print_r($received_Token); exit;
            $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
            $tokenData = json_decode($tokenData);
            if ($tokenData === false) {
                $this->return_json_error_response('Invalid or Expired Token', REST_Controller::HTTP_UNAUTHORIZED);
            }
            //echo json_encode($tokenData->id);
            if($tokenData != false ){
                if (isset($tokenData->id)) {
                    $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
                }else{
                    $this->return_json_error_response('Invalid or Expired Token', REST_Controller::HTTP_UNAUTHORIZED);
                }
                
                if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                    $user_id = $request['user_id'];
                    if (!preg_match('/^[0-9a-f]{24}$/', $user_id)) {
       
                        echo "Invalid MongoDB ObjectID: $user_id\n";
                        exit;
                    }
                    $exchange = $this->sanitize_and_validate_exchange($request['exchange']);
                   
                    //echo '<pre>exchange :: '; print_r($exchange); exit;
                    $week = date('Y-m-01',strtotime('-11 months'));
                    //$week = date('Y-m-d H:i:s',strtotime('-84 days'));
                    $start_mongo_date_new = $this->mongo_db->converToMongodttime($week);
                    $start_mongo_date_end_new = $this->mongo_db->converToMongodttime(date('2021-10-30'));
                    $weekly_arr = array();
                    $final_array = array();
                    $update_arr = array();   
                    array_push($weekly_arr,$week);

                    //$month = date('m');
                    $btc_acc = 0;
                    $usdt_acc = 0;
                    $eth_acc = 0;
                    $bnb_acc = 0;
                    $btc_auto = 0;
                    $btc_manual = 0;
                    $eth_auto = 0;
                    $eth_manual = 0;
                    $bnb_auto = 0;
                    $bnb_manual = 0;
                    $usdt_auto = 0;
                    $usdt_manual = 0;
                    if ($exchange == 'binance') {
                        $collection_order = 'sold_buy_orders';
                        $collection_store = 'accumulation_monthly_binance';
                    }elseif ($exchange == 'kraken') {
                        $collection_order = 'sold_buy_orders_kraken';
                        $collection_store = 'accumulation_monthly_kraken';
                    }elseif ($exchange == 'dg') {
                        $collection_order = 'sold_buy_orders_dg';
                        $collection_store = 'accumulation_monthly_dg';
                    }

                    $db = $this->mongo_db->customQuery();
                    $order_arr_obj = $db->$collection_order->find(['admin_id'=>$user_id,'accumulations'=>['$exists'=>true],'sell_date'=>['$lt'=>$start_mongo_date_new,'$gte'=>$start_mongo_date_end_new]]);
                    $order_arr_old = iterator_to_array($order_arr_obj);
                    if(count($order_arr_old) > 0){
                            foreach ($order_arr_old as $acc_value_row) {
                                $coin_category = substr($acc_value_row['symbol'],-3);
                                if($coin_category == 'BTC'){
                                    $btc_acc += $acc_value_row['accumulations']['profit'];
                                    if ($acc_value_row['trigger_type'] == 'no') {
                                        $btc_manual += $acc_value_row['accumulations']['profit'];
                                    }else{
                                        $btc_auto += $acc_value_row['accumulations']['profit'];
                                    }
                    
                                }elseif($coin_category == 'SDT'){
                                    $usdt_acc += $acc_value_row['accumulations']['profit'];
                                    if ($acc_value_row['trigger_type'] == 'no') {
                                        $usdt_manual += $acc_value_row['accumulations']['profit'];
                                    }else{
                                        $usdt_auto += $acc_value_row['accumulations']['profit'];
                                    }
                                }elseif($coin_category == 'ETH'){
                                    $eth_acc += $acc_value['accumulations']['profit'];
                                    $eth_acc_this += $acc_value['accumulations']['profit'];
                                    if ($acc_value['trigger_type'] == 'no') {
                                        $eth_manual += $acc_value['accumulations']['profit'];
                                    }else {
                                        $eth_auto += $acc_value['accumulations']['profit'];
                                    }
                                }elseif($coin_category == 'BNB'){
                                    $bnb_acc += $acc_value['accumulations']['profit'];
                                    $bnb_acc_this += $acc_value['accumulations']['profit'];
                                    if ($acc_value['trigger_type'] == 'no') {
                                        $bnb_manual += $acc_value['accumulations']['profit'];
                                    }else {
                                        $bnb_auto += $acc_value['accumulations']['profit'];
                                    }
                                }

                            }
                    }       
                    $month = 12;
                    for ($i=1; $i < $month; $i++) {
                        $month_check  = date('m',strtotime($week)); 
                        if($month_check == 1){
                            $week = date('Y-m-d',strtotime('+31 days',strtotime($week)));
                            array_push($weekly_arr, $week);
                        }else if((int)$month_check == 2){
                            $week = date('Y-m-d',strtotime('+29 days',strtotime($week)));
                            array_push($weekly_arr, $week);
                        }else if((int)$month_check == 3){
                            $week = date('Y-m-d',strtotime('+31 days',strtotime($week)));
                            array_push($weekly_arr, $week);
                        }else if((int)$month_check == 4){
                            $week = date('Y-m-d',strtotime('+30 days',strtotime($week)));
                            array_push($weekly_arr, $week);
                        }else if((int)$month_check == 5){
                            $week = date('Y-m-d',strtotime('+31 days',strtotime($week)));
                            array_push($weekly_arr, $week);
                        }else if((int)$month_check == 6){
                            $week = date('Y-m-d',strtotime('+30 days',strtotime($week)));
                            array_push($weekly_arr, $week);
                        }else if((int)$month_check == 7){
                            $week = date('Y-m-d',strtotime('+31 days',strtotime($week)));
                            array_push($weekly_arr, $week);
                        }else if((int)$month_check == 8){
                            $week = date('Y-m-d',strtotime('+31 days',strtotime($week)));
                            array_push($weekly_arr, $week);
                        }else if((int)$month_check == 9){
                            $week = date('Y-m-d',strtotime('+30 days',strtotime($week)));
                            array_push($weekly_arr, $week); 
                        }else if((int)$month_check == 10){
                            $week = date('Y-m-d',strtotime('+31 days',strtotime($week)));
                            array_push($weekly_arr, $week);
                        }else if((int)$month_check ==11){
                            $week = date('Y-m-d',strtotime('+30 days',strtotime($week)));
                            array_push($weekly_arr, $week);
                        }else if((int)$month_check == 12){
                            $week = date('Y-m-d',strtotime('+31 days',strtotime($week)));
                            array_push($weekly_arr, $week);
                        }
                        
                    }
                    // echo '<pre>weekly array :: ';print_r($weekly_arr);
                    $new_reverse_array = $weekly_arr;
                
                    foreach ($new_reverse_array as $key => $weekly_loop) {
                        $start_mongo_date = $this->mongo_db->converToMongodttime($weekly_loop);
                        if($key == (count($new_reverse_array)-1)){
                        
                            $date_new = date('Y-m-d',strtotime('+30 days',strtotime($weekly_loop)));
                            $end_mongo_date = $this->mongo_db->converToMongodttime($date_new);
                        }else{
                        
                            $end_mongo_date = $this->mongo_db->converToMongodttime($new_reverse_array[$key+1]);    
                        }
                        
                        // if ($key === array_key_last($new_reverse_array)) {
                        if ($key === count($new_reverse_array) - 1) {
                            $last_date = date('Y-m-d');
                            $end_mongo_date = $this->mongo_db->converToMongodttime($last_date);
                        }

                        //echo '<pre>end mongo date final :: '; print_r($end_mongo_date); exit;
                        $order_arr = $db->$collection_order->find(['admin_id'=>$user_id,'accumulations'=>['$exists'=>true],'sell_date'=>['$gte'=>$start_mongo_date,'$lte'=>$end_mongo_date]]);
                        $order_arr_convert = iterator_to_array($order_arr);
                        $btc_acc_this = 0;
                        $usdt_acc_this = 0;
                    
                        if(count($order_arr_convert) > 0){
                            foreach ($order_arr_convert as $acc_value) {
                                $coin_category = substr($acc_value['symbol'],-3);
                                if($coin_category == 'BTC'){
                                    $btc_acc += $acc_value['accumulations']['profit'];
                                    $btc_acc_this += $acc_value['accumulations']['profit'];  
                                    if ($acc_value['trigger_type'] == 'no') {
                                        $btc_manual += $acc_value['accumulations']['profit'];
                                    }else{
                                        $btc_auto += $acc_value['accumulations']['profit'];
                                    }
                                }elseif($coin_category == 'SDT'){
                                    $usdt_acc += $acc_value['accumulations']['profit'];
                                    $usdt_acc_this += $acc_value['accumulations']['profit'];
                                    if ($acc_value['trigger_type'] == 'no') {
                                        $usdt_manual += $acc_value['accumulations']['profit'];
                                    }else {
                                        $usdt_auto += $acc_value['accumulations']['profit'];
                                    }
                                }elseif($coin_category == 'ETH'){
                                    $eth_acc += $acc_value['accumulations']['profit'];
                                    $eth_acc_this += $acc_value['accumulations']['profit'];
                                    if ($acc_value['trigger_type'] == 'no') {
                                        $eth_manual += $acc_value['accumulations']['profit'];
                                    }else {
                                        $eth_auto += $acc_value['accumulations']['profit'];
                                    }
                                }elseif($coin_category == 'BNB'){
                                    $bnb_acc += $acc_value['accumulations']['profit'];
                                    $bnb_acc_this += $acc_value['accumulations']['profit'];
                                    if ($acc_value['trigger_type'] == 'no') {
                                        $bnb_manual += $acc_value['accumulations']['profit'];
                                    }else {
                                        $bnb_auto += $acc_value['accumulations']['profit'];
                                    }
                                }
                            }
                        }

                        $update_arr['month_'.($key+1)]['BTC'] = !empty($btc_acc) ? $btc_acc : 0;
                        $update_arr['month_'.($key+1)]['SDT'] = !empty($usdt_acc) ? $usdt_acc : 0;
                        $update_arr['month_'.($key+1)]['ETH'] = !empty($eth_acc) ? $eth_acc : 0;
                        $update_arr['month_'.($key+1)]['BNB'] = !empty($bnb_acc) ? $bnb_acc : 0;
                        $update_arr['month_'.($key+1)]['USDT_AUTO'] = !empty($usdt_auto) ? $usdt_auto : 0;
                        $update_arr['month_'.($key+1)]['USDT_MANUAL'] = !empty($usdt_manual) ? $usdt_manual : 0;
                        $update_arr['month_'.($key+1)]['BTC_AUTO'] = !empty($btc_auto) ? $btc_auto : 0;
                        $update_arr['month_'.($key+1)]['BTC_MANUAL'] = !empty($btc_manual) ? $btc_manual : 0;
                        $update_arr['month_'.($key+1)]['BNB_AUTO'] = !empty($bnb_auto) ? $bnb_auto : 0;
                        $update_arr['month_'.($key+1)]['BNB_MANUAL'] = !empty($bnb_manual) ? $bnb_manual : 0;
                        $update_arr['month_'.($key+1)]['ETH_AUTO'] = !empty($eth_auto) ? $eth_auto : 0;
                        $update_arr['month_'.($key+1)]['ETH_MANUAL'] = !empty($eth_manual) ? $eth_manual : 0;

                        if(!isset($new_reverse_array[$key])){
                            $date_weekly = date('Y-m-d',strtotime('+30 days',strtotime($new_reverse_array[$key])));
                            $update_arr['month_'.($key+1)]['month_date'] = $date_weekly;
                        }else{
                            $update_arr['month_'.($key+1)]['month_date'] = $new_reverse_array[$key];
                        }
                        array_push($final_array,$update_arr);
                    }

                    // $db->$collection_store->updateOne(['user_id'=>$user_id],['$set'=>['monthly_accumulations'=>$update_arr]],['upsert'=>true]);
                    // $db->users->updateOne(['_id'=>$this->mongo_db->mongoId($user_id)],['$set'=>['sheraz_monthly'=>1]]);

                    if(!empty($update_arr)){
            
                        $message = array(
                            'status' => TRUE,
                            'data' => $update_arr,
                            'message' => 'Data found scuccessfully',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
            
                    } else {
            
                        $message = array(
                            'status' => FALSE,
                            'message' => 'Data not found',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
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

    }//end

    public function calculate_weekly_accumulations_post(){
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *");

        $request = $this->post();

        if(empty($request['user_id']) || empty($request['exchange'])){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id, exchange can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
            $received_Token = $this->input->get_request_header('Authorization');
            $received_Token = str_replace("Bearer ", "", $received_Token);
            $received_Token = str_replace("Token ", "", $received_Token);
            // echo '<pre>received_Token '; print_r($received_Token); exit;
            $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
            $tokenData = json_decode($tokenData);
            if ($tokenData === false) {
                $this->return_json_error_response('Invalid or Expired Token', REST_Controller::HTTP_UNAUTHORIZED);
            }
            //echo json_encode($tokenData->id);
            if($tokenData != false ){
                if (isset($tokenData->id)) {
                    $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
                }else{
                    $this->return_json_error_response('Invalid or Expired Token', REST_Controller::HTTP_UNAUTHORIZED);
                }
                
                if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                    $user_id = $request['user_id'];
                    if (!preg_match('/^[0-9a-f]{24}$/', $user_id)) {
       
                        echo "Invalid MongoDB ObjectID: $user_id\n";
                        exit;
                    }
                    $exchange = $this->sanitize_and_validate_exchange($request['exchange']);
                   
                    $week = date('Y-m-d H:i:s',strtotime('-84 days'));
                    $start_mongo_date_new = $this->mongo_db->converToMongodttime($week);
                    $start_mongo_date_end_new = $this->mongo_db->converToMongodttime(date('2021-10-30'));
                    $weekly_arr = array();
                    $final_array = array();
                    $update_arr = array();   
                    array_push($weekly_arr,$week);
                    for ($i=1; $i < 12; $i++) { 
                        $week = date('Y-m-d',strtotime('+7 days',strtotime($week)));
                        array_push($weekly_arr, $week);
                    }
                  
                    if ($exchange == 'binance') {
                        $collection_order = 'sold_buy_orders';
                        $collection_store = 'accumulation_weekly_binance';
                    }elseif ($exchange == 'kraken') {
                        $collection_order = 'sold_buy_orders_kraken';
                        $collection_store = 'accumulation_weekly_kraken';
                    }elseif ($exchange == 'dg') {
                        $collection_order = 'sold_buy_orders_dg';
                        $collection_store = 'accumulation_weekly_dg';
                    }

                    $db = $this->mongo_db->customQuery();
                    $new_reverse_array = $weekly_arr;
                    $btc_acc = 0;
                    $usdt_acc = 0;
                    $btc_auto = 0;
                    $btc_manual = 0;
                    $usdt_auto = 0;
                    $usdt_manual = 0;
                    $order_arr_obj = $db->$collection_order->find(['admin_id'=>$user_id,'accumulations'=>['$exists'=>true],'sell_date'=>['$lt'=>$start_mongo_date_new,'$gte'=>$start_mongo_date_end_new]]);
                    $order_arr_old = iterator_to_array($order_arr_obj);
                    if(count($order_arr_old) > 0){
                        foreach ($order_arr_old as $acc_value_row) {
                            $coin_category = substr($acc_value_row['symbol'],-3);
                            if($coin_category == 'BTC'){
                                $btc_acc += $acc_value_row['accumulations']['profit'];
                                if ($acc_value_row['trigger_type'] == 'no') {
                                    $btc_manual += $acc_value_row['accumulations']['profit'];
                                }else{
                                    $btc_auto += $acc_value_row['accumulations']['profit'];
                                }
                            }else{
                                $usdt_acc += $acc_value_row['accumulations']['profit'];
                                if ($acc_value_row['trigger_type'] == 'no') {
                                    $usdt_manual += $acc_value_row['accumulations']['profit'];
                                }else{
                                    $usdt_auto += $acc_value_row['accumulations']['profit'];
                                }
                            }
                        }
                    }
                    foreach ($new_reverse_array as $key => $weekly_loop) {
                        $start_mongo_date = $this->mongo_db->converToMongodttime($weekly_loop);
                        if($key == (count($new_reverse_array)-1)){
                            $date_new = date('Y-m-d',strtotime('+7 days',strtotime($weekly_loop)));
                            $end_mongo_date = $this->mongo_db->converToMongodttime($date_new);
                        }else{
                            $end_mongo_date = $this->mongo_db->converToMongodttime($new_reverse_array[$key+1]);    
                        }
                        $order_arr = $db->$collection_order->find(['admin_id'=>$user_id,'accumulations'=>['$exists'=>true],'sell_date'=>['$gte'=>$start_mongo_date,'$lte'=>$end_mongo_date]]);
                        $order_arr_convert = iterator_to_array($order_arr);
                        $btc_acc_this = 0;
                        $usdt_acc_this = 0;
                        if(count($order_arr_convert) > 0){
                            foreach ($order_arr_convert as $acc_value) {
                                $coin_category = substr($acc_value['symbol'],-3);
                                if($coin_category == 'BTC'){
                                    $btc_acc += $acc_value['accumulations']['profit'];
                                    $btc_acc_this += $acc_value['accumulations']['profit'];
                                    if ($acc_value['trigger_type'] == 'no') {
                                        $btc_manual += $acc_value['accumulations']['profit'];
                                    }else{
                                        $btc_auto += $acc_value['accumulations']['profit'];
                                    }
                                }elseif($coin_category == 'SDT'){
                                    $usdt_acc += $acc_value['accumulations']['profit'];
                                    $usdt_acc_this += $acc_value['accumulations']['profit'];
                                    if ($acc_value['trigger_type'] == 'no') {
                                        $usdt_manual += $acc_value['accumulations']['profit'];
                                    }else {
                                        $usdt_auto += $acc_value['accumulations']['profit'];
                                    }
                                }elseif($coin_category == 'ETH'){
                                    $eth_acc += $acc_value['accumulations']['profit'];
                                    $eth_acc_this += $acc_value['accumulations']['profit'];
                                    if ($acc_value['trigger_type'] == 'no') {
                                        $eth_manual += $acc_value['accumulations']['profit'];
                                    }else {
                                        $eth_auto += $acc_value['accumulations']['profit'];
                                    }
                                }elseif($coin_category == 'BNB'){
                                    $bnb_acc += $acc_value['accumulations']['profit'];
                                    $bnb_acc_this += $acc_value['accumulations']['profit'];
                                    if ($acc_value['trigger_type'] == 'no') {
                                        $bnb_manual += $acc_value['accumulations']['profit'];
                                    }else {
                                        $bnb_auto += $acc_value['accumulations']['profit'];
                                    }
                                }
                            }
                        }

                        $update_arr['week_'.($key+1)]['BTC'] = !empty($btc_acc) ? $btc_acc : 0;
                        $update_arr['week_'.($key+1)]['SDT'] = !empty($usdt_acc) ? $usdt_acc : 0;
                        $update_arr['week_'.($key+1)]['ETH'] = !empty($eth_acc) ? $eth_acc : 0;
                        $update_arr['week_'.($key+1)]['BNB'] = !empty($bnb_acc) ? $bnb_acc : 0;
                        $update_arr['week_'.($key+1)]['USDT_AUTO'] = !empty($usdt_auto) ? $usdt_auto : 0;
                        $update_arr['week_'.($key+1)]['USDT_MANUAL'] = !empty($usdt_manual) ? $usdt_manual : 0;
                        $update_arr['week_'.($key+1)]['BTC_AUTO'] = !empty($btc_auto) ? $btc_auto : 0;
                        $update_arr['week_'.($key+1)]['BTC_MANUAL'] = !empty($btc_manual) ? $btc_manual : 0;
                        $update_arr['week_'.($key+1)]['BNB_AUTO'] = !empty($bnb_auto) ? $bnb_auto : 0;
                        $update_arr['week_'.($key+1)]['BNB_MANUAL'] = !empty($bnb_manual) ? $bnb_manual : 0;
                        $update_arr['week_'.($key+1)]['ETH_AUTO'] = !empty($eth_auto) ? $eth_auto : 0;
                        $update_arr['week_'.($key+1)]['ETH_MANUAL'] = !empty($eth_manual) ? $eth_manual : 0;
                        $update_arr['week_'.($key+1)]['BTC_week'.($key+1)] = !empty($btc_acc_this) ? $btc_acc_this : 0;
                        $update_arr['week_'.($key+1)]['SDT_week'.($key+1)] = !empty($usdt_acc_this) ? $usdt_acc_this : 0;
                        $update_arr['week_'.($key+1)]['ETH_week'.($key+1)] = !empty($eth_acc_this) ? $eth_acc_this : 0;
                        $update_arr['week_'.($key+1)]['ETH_week'.($key+1)] = !empty($bnb_acc_this) ? $bnb_acc_this : 0;
                        if(!isset($new_reverse_array[$key+1])){
                            $date_weekly = date('Y-m-d',strtotime('+7 days',strtotime($new_reverse_array[$key])));
                            $update_arr['week_'.($key+1)]['week_date'] = $date_weekly;
                        }else{
                            $update_arr['week_'.($key+1)]['week_date'] = $new_reverse_array[$key+1];
                        }
                        
                    }
                    // $db->$collection_store->updateOne(['user_id'=>$user_id],['$set'=>['weekly_accumulations'=>$update_arr]],['upsert'=>true]);
                    // $db->users->updateOne(['_id'=>$this->mongo_db->mongoId($user_id)],['$set'=>['sheraz_weekly'=>1]]);

                    if(!empty($update_arr)){
            
                        $message = array(
                            'status' => TRUE,
                            'data' => $update_arr,
                            'message' => 'Data found scuccessfully',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
            
                    } else {
            
                        $message = array(
                            'status' => FALSE,
                            'message' => 'Data not found',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
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

    }//end

    private function sanitize_and_validate_object_id($input) {
        $sanitized_input = filter_var($input, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[a-f\d]{24}$/i']]);
        return ($sanitized_input !== false) ? $sanitized_input : $this->return_error_response('Invalid object ID format');
    }
    
    private function sanitize_and_validate_exchange($input) {
        $allowed_exchanges = array('binance', 'kraken', 'dg', 'okex');
        return in_array($input, $allowed_exchanges) ? $input : $this->return_error_response('Invalid exchange value');
    }
    
    private function sanitize_and_validate_application_mode($input) {
        $allowed_modes = array('live', 'test', 'both');
        return in_array($input, $allowed_modes) ? $input : $this->return_error_response('Invalid application mode value');
    }
    
    private function return_error_response($message) {
        $resp['success'] = false;
        $resp['code'] = 400;
        $resp['data'] = $message;
        echo json_encode($resp);
        exit;
    }

    
    function openTradesWorth($symbol, $exchange, $user_id){

        $collection = ($exchange == 'binance' ? 'buy_orders' : 'buy_orders_'.$exchange);

        $where = [
            'admin_id' => $user_id,
            'application_mode' => 'live',
            'symbol' => $symbol,
        ];

		$where['$or'][] = [
			'status'=> [ '$in'=> ['FILLED', 'FILLED_ERROR', 'SELL_ID_ERROR']],
			'is_sell_order'=> ['$ne'=>'sold'],
			'cost_avg'=> [ '$nin'=> ['yes', 'taking_child', 'completed']],
			
		];
		
		$where['$or'][] = [
			'status' => ['$in' => ['LTH', 'LTH_ERROR']],
        	'is_sell_order' => ['$ne'=>'sold'],
			'cost_avg' => ['$nin' => ['taking_child', 'yes', 'completed']]
		];

		$where['$or'][] = [
			'status' => ['$in' => ['submitted', 'submitted_for_sell', 'fraction_submitted_sell', 'submitted_ERROR'] ],
			'cost_avg' => [ '$nin' => ['taking_child', 'yes', 'completed'] ],
		];
		
        $db = $this->mongo_db->customQuery();  
        $responseArr = $db->$collection->find($where);
        // echo "<pre>"; print_r($responseArr); exit;
        $trades = iterator_to_array($responseArr);
        if (count($trades) > 0) {
            if (!empty($trades)) {
                $totalWorthOpenTrades = 0;
                foreach ($trades as $open_t) { // Changed the variable name to $open_t
                    $quantity = (float)$open_t['buy_fraction_filled_order_arr'][0]['filledQty']; 
                    $price = (float)$open_t['buy_fraction_filled_order_arr'][0]['filledPrice'];
                    $calculatedPrice = $quantity * $price;
                    $totalWorthOpenTrades += $calculatedPrice;
                    // echo $totalWorthOpenTrades;
                }
            }
           return $totalWorthOpenTrades;
        }else{
            return 0;
        }
       
       
      
               
    }
    function totalTradeWorth($symbol, $exchange, $user_id){

        if($exchange == 'binance'){
            $collection_name = 'buy_orders';
            $collection_name_sold = 'sold_buy_orders';
        }else{
            $collection_name = 'buy_orders_kraken';
            $collection_name_sold = 'sold_buy_orders_kraken';
        }


        $db = $this->mongo_db->customQuery();  
        // for testing on sir account -> 5eb5a5a628914a45246bacc6
        // $parents = $db->$collection_name->count(['admin_id' => $user_id, 'status'=>['$in'=>['new','NEW']],'application_mode'=>'live','symbol'=>$symbol,'parent_status'=>'parent','pick_parent'=>'yes']);
        // if($parents > 0){
        //     // echo "here"; exit;
            
        
            $buy_orders = $db->$collection_name->find(['admin_id' => $user_id, 'status'=>['$in'=>['FILLED','LTH','COST_AVG','CA_TAKING_CHILD']],'trigger_type'=>'barrier_percentile_trigger','application_mode'=>'live','symbol'=>$symbol,'parent_status'=>['$ne'=>'parent'],'is_sell_order'=>['$ne'=>'sold']]);
            $buy_orders_arr = iterator_to_array($buy_orders);
            // echo "<pre>"; print_r($buy_orders_arr); exit;
            if (!empty($buy_orders_arr)) {
                $totalWorthBuy = 0;
                foreach ($buy_orders_arr as $buy_order) { // Changed the variable name to $buy_order
                    $quantity = (float)$buy_order['buy_fraction_filled_order_arr'][0]['filledQty']; // Corrected the variable name
                    $price = (float)$buy_order['buy_fraction_filled_order_arr'][0]['filledPrice']; // Corrected the variable name
                    $calculatedPrice = $quantity * $price;
                    $totalWorthBuy += $calculatedPrice;
                    // echo $totalWorthBuy;
                }
            }
        
            //for sold orders check
            $sold_orders = $db->$collection_name_sold->find(['admin_id' => $user_id, 'status'=>['$in'=>['FILLED','LTH','COST_AVG','CA_TAKING_CHILD']],'trigger_type'=>'barrier_percentile_trigger','application_mode'=>'live','symbol'=>$symbol,'is_sell_order'=>'sold']);
            $sold_orders_arr = iterator_to_array($sold_orders);
            if (!empty($sold_orders_arr)) {
                $totalWorthSold = 0; // Initialize the variable before the loop
                foreach ($sold_orders_arr as $sold_order) { 
                    $quantity = (float)$sold_order['sell_fraction_filled_order_arr'][0]['filledQty']; // Corrected the variable name
                    $price = (float)$sold_order['sell_fraction_filled_order_arr'][0]['filledPrice']; // Corrected the variable name
                    $calculatedPrice = $quantity * $price;
                    $totalWorthSold += $calculatedPrice;
                    // echo $totalWorthSold;
                }
            }
        
            $total = (float)$totalWorthBuy + (float)$totalWorthSold;
            if ($total > 0) {
                return $total;
            } else {
                return 0;
            }
        // }else{
        //     return 0; // returning 0 to show there is no parent found with pick parent yes
        // }
               
    }

    public function getTotalAccumulationsUserCoin($user_id,$exchange,$symbol){
        if($user_id == ''){
            return 0;
        }
        if($exchange == 'binance'){
            $order_collection = 'sold_buy_orders';
        }else{
            $order_collection = 'sold_buy_orders_kraken';
        }
      
        $db = $this->mongo_db->customQuery();
        $pipeline = [
                        ['$match'=>[
                            'admin_id'=>$user_id,
                            'symbol'=>$symbol,
                            'accumulations'=>['$exists'=>true]
                            ]
                        ]
        ];
        $user_orders = $db->$order_collection->aggregate($pipeline);
        $user_orders_arr = iterator_to_array($user_orders);
        $accumulaion_btc = 0.0;
        $accumulaion_usdt = 0.0;
        foreach ($user_orders_arr as $value_row) {
            $symbol_type = substr($value_row['symbol'],-3);
            if($symbol_type == 'BTC'){
            $accumulaion_btc += (float)$value_row['accumulations']['profit']; 
            }else{
            $accumulaion_usdt += $value_row['accumulations']['profit']; 
            }
        }
        $resp['btc_accumulation'] = $accumulaion_btc;
        $resp['usdt_accumulation'] = $accumulaion_usdt;
       
        return $resp;
    }

    public function manage_coins_post_bkp(){

        // header('Content-type: application/json');
        // header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        // header("Access-Control-Allow-Origin: *");
		// header("Access-Control-Allow-Headers: *");
        // header("Access-Control-Allow-Credentials: true");
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *");

        $request = $this->post();

        if(empty($request['user_id']) || empty($request['exchange'])){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id, exchange can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
            $received_Token = $this->input->get_request_header('Authorization');
            $received_Token = str_replace("Bearer ", "", $received_Token);
            $received_Token = str_replace("Token ", "", $received_Token);
            $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
            $tokenData = json_decode($tokenData);
            //echo json_encode($tokenData->id);
            if($tokenData != false ){
                $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
                
                if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){
                
                    $user_id = $request['user_id'];
                    $exchange = $request['exchange'];
                    $application_mode = (!empty($request['application_mode'])?$request['application_mode'] : 'live');

                    $coins = $this->mod_api_calls->get_all_user_coins($user_id, $exchange);

                    $minQtyArr = get_min_quantity($exchange);

                    $temp_coin_balance_data = [];

                    $data = [];
                    if(!empty($coins)){

                        $btc_arr = array('coin_name' => 'Bitcoin',
                            'symbol' => "BTC",
                            'coin_name' => "bitcoin",
                            'coin_logo' => "btc11.jpg",
                            'coin_keywords' => '#btc,#bitcoin,#BTC',
                        );
            
                        array_push($coins, $btc_arr);
            
                        end($coins);
            
                        $last_key = key($coins);
                        $last_value = array_pop($coins);
                        $coins = array_merge(array($last_key => $last_value), $coins);
            
                        $currency = 'bitcoin';
                        $market = array();

                        $pricesArr = get_current_market_prices($exchange, []);

                        // echo "<pre>";
                        // echo "eschange $exchange <br>";
                        // print_r($pricesArr);
                        // die('ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss');

                        $BTCUSDT_price = $pricesArr['BTCUSDT'];
                        
                        // $BTCUSDT_price = $this->mod_api_calls->get_last_price('BTCUSDT', $exchange);

                        foreach ($coins as $coin) {
                            $coin_name = $coin['coin_name'];
                            $symbol = $coin['symbol'];
                            $response_coin_id = (string)$coin['_id'];
                            $coin_id = $coin->_id;
                            $balance = $this->mod_api_calls->get_coin_balance($user_id, $symbol, $exchange);
                            $costAvg_trades = $this->mod_api_calls->get_costAvg_trades($user_id, $symbol, $exchange, $application_mode);
                            // echo "<pre>", print_r($costAvg_trades); exit;
                            $amount = null;
                            $amount_status = null;
                            if ($symbol != "BTC") {
                                $price = $pricesArr[$symbol];
                                $trigger_type_auto = "barrier_percentile_trigger";
                                $trigger_type_manual = "No";
                            
                                // $price = $this->mod_api_calls->get_last_price($symbol, $exchange);
                                //echo $user_id. "\r\n", $symbol. "\r\n", $application_mode. "\r\n"; exit;
                                $open_trades = $this->mod_api_calls->get_open_trades($user_id, $symbol, $exchange, $application_mode);
                                $open_trades_count = count($open_trades);
                                $costAvg_trades = $this->mod_api_calls->get_costAvg_trades($user_id, $symbol, $exchange, $application_mode);
                                //  echo "<pre>", print_r($costAvg_trades); exit;
                                $costAvg_trades_count = count($costAvg_trades);
                                $open_trades = array_merge($open_trades, $costAvg_trades);
                              
                                //Open auto trades
                                $open_trades_auto = $this->mod_api_calls->get_open_trades($user_id, $symbol, $exchange, $application_mode, $trigger_type_auto);
                                $open_trades_auto_count = count($open_trades_auto);
                                $costAvg_trades_auto = $this->mod_api_calls->get_costAvg_trades($user_id, $symbol, $exchange, $application_mode, $trigger_type_auto);
                                $costAvg_trades_count_auto = count($costAvg_trades_auto);
                                $open_trades_auto = array_merge($open_trades_auto, $costAvg_trades_auto);
                                
                                //Open manual trades
                                $open_trades_manual = $this->mod_api_calls->get_open_trades($user_id, $symbol, $exchange, $application_mode, $trigger_type_manual);
                                $open_trades_manual_count = count($open_trades_manual);
                                $costAvg_trades_manual = $this->mod_api_calls->get_costAvg_trades($user_id, $symbol, $exchange, $trigger_type_manual);
                                $costAvg_trades_count = count($costAvg_trades_manual);
                                $open_trades_manual = array_merge($open_trades_manual, $costAvg_trades_manual);
                              

                                // echo "Symbol" .$symbol ."User ID". $user_id . "<pre> ***************costavg trades*******************", print_r($costAvg_trades). "\r\n", "<pre> ***************costavg trades auto*******************", print_r($costAvg_trades_auto). "\r\n", "<pre> ***************costavg manual*******************", print_r($costAvg_trades_manual). "\r\n";  exit;
                                unset($costAvg_trades);
                                unset($costAvg_trades_auto);
                                unset($costAvg_trades_manual);

                                $change = $this->mod_api_calls->get_24_hour_price_change($symbol);
            
                                $per = $change['percentage'];
                                $per = number_format($per, 2);
                                //$amount1 = $change['change'];

                                if ($per > 0) {
                                    $amount = '<div class="text-success badge-success-24H ">'.number_format($per, 2). '% </div>';
                                    $amount_status = 'green';
                                } elseif ($per < 0) {
                                    $amount = '<div class="text-danger badge-danger-24H ">'.number_format($per, 2) . '% </div>';
                                    $amount_status = 'red';
                                } else {
                                    $amount = '<div class="text-default badge-success-24H ">' . number_format($per, 2) . '% </div>';
                                    $amount_status = 'dark-grey';
                                }
                            } else {
                                $price = 1;
                                $trade = 'N/A';
                            }
                       
                            $coin_balance = $balance['coin_balance'];
                            $total_trade_qty = 0;
                            $total_trade_qty_auto = 0;
                            $total_trade_qty_manual = 0;

                            $balance_error = false;
                            $usd_balance = 0;
                            $balance = 0; 
                            $balance_usd_worth = 0;
                            $comitted_balance = 0;
                            $comitted_balance_usd_worth = 0;
                            $extra_balance = 0;
                            $extra_balance_usd_worth = 0;
                            $required_balance = 0;
                            $required_balance_usd_worth = 0;

                            $comitted_balance_auto = 0;
                            $comitted_balance_usd_worth_auto = 0;

                            $comitted_balance_manual = 0;
                            $comitted_balance_usd_worth_manual = 0;
                            // if (!empty($open_trades)) { i have done this code according to new logic so the errors etc get resolved in any case errors are increased then comment the below code and uncomment this one (sheraz)..
                            //     foreach ($open_trades as $tradee) {
                            //         if(isset($tradee['cost_avg_array'])){
                            //             $total_trade_qty += (float) isset($tradee['quantity_all'])?$tradee['quantity_all']:$tradee['quantity'];    
                            //         }else{
                            //             $total_trade_qty += (float) $tradee['quantity'];
                            //         }
                            //     }
                            // }
                            if (!empty($open_trades)) {
                                foreach ($open_trades as $tradee) {
                                    if(isset($tradee['cost_avg_array'])){
                                        // if(empty($tradee['quantity_all'])){
                                            foreach($tradee['cost_avg_array'] as $value_cost_avg_array){
                                                if($value_cost_avg_array['order_sold'] != 'yes'){
                                                    $total_trade_qty += $value_cost_avg_array['filledQtyBuy'];        
                                                }
                                            }
                                        // }else{
                                        //     $total_trade_qty += (float) isset($tradee['quantity_all'])?$tradee['quantity_all']:$tradee['quantity'];        
                                        // }
                                    }else{
                                        $total_trade_qty += (float) $tradee['quantity'];
                                    }
                                }
                            }

                            if (!empty($open_trades_auto)) {
                                foreach ($open_trades_auto as $tradee_auto) {
                                    if(isset($tradee_auto['cost_avg_array'])){
                                        // if(empty($tradee_auto['quantity_all'])){
                                            foreach($tradee_auto['cost_avg_array'] as $value_cost_avg_array_auto){
                                                if($value_cost_avg_array_auto['order_sold'] != 'yes'){
                                                    $total_trade_qty_auto += $value_cost_avg_array_auto['filledQtyBuy'];        
                                                }
                                            }
                                        // }else{
                                        //     $total_trade_qty_auto += (float) isset($tradee_auto['quantity_all'])?$tradee_auto['quantity_all']:$tradee_auto['quantity'];        
                                        // }
                                    }else{
                                        $total_trade_qty_auto += (float) $tradee_auto['quantity'];
                                    }
                                }
                            }

                            if (!empty($open_trades_manual)) {
                                foreach ($open_trades_manual as $tradee_manual) {
                                    if(isset($tradee_manual['cost_avg_array'])){
                                        // if(empty($tradee_manual['quantity_all'])){
                                            foreach($tradee_manual['cost_avg_array'] as $value_cost_avg_array_manual){
                                                if($value_cost_avg_array_manual['order_sold'] != 'yes'){
                                                    $total_trade_qty_manual += $value_cost_avg_array_manual['filledQtyBuy'];        
                                                }
                                            }
                                        // }else{
                                        //     $total_trade_qty_manual += (float) isset($tradee_manual['quantity_all'])?$tradee_manual['quantity_all']:$tradee_manual['quantity'];        
                                        // }
                                    }else{
                                        $total_trade_qty_manual += (float) $tradee_manual['quantity'];
                                    }
                                }
                            }
                            // echo $user_id. "\r\n", $symbol. "\r\n", $application_mode. "\r\n"; exit;

                            $tarr = explode('USDT', $symbol);
                            if (isset($tarr[1]) && $tarr[1] == '') {
                                // echo "\r\n USDT coin";
                                if($symbol == 'BTCUSDT'){
                                    $usd_balance =  $coin_balance;

                                    $coin_balance = $coin_balance * (1/$price);

                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance * $price;

                                    // $balance_error_auto = ($total_trade_qty_auto > $coin_balance) ? true : false;
                                    $comitted_balance_auto = $total_trade_qty_auto;
                                    $comitted_balance_usd_worth_auto = $comitted_balance_auto * $price;

                                    // $balance_error_manual = ($total_trade_qty_manual > $coin_balance) ? true : false;
                                    $comitted_balance_manual = $total_trade_qty_manual;
                                    $comitted_balance_usd_worth_manual = $comitted_balance_manual * $price;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance * $price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance * $price;
                                    }

                                }else{
                                    $usd_balance =  $coin_balance*$price;

                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance * $price;

                                    // $balance_error_auto = ($total_trade_qty_auto > $coin_balance) ? true : false;
                                    $comitted_balance_auto = $total_trade_qty_auto;
                                    $comitted_balance_usd_worth_auto = $comitted_balance_auto * $price;
                                  
                                    // $balance_error_manual = ($total_trade_qty_manual > $coin_balance) ? true : false;
                                    $comitted_balance_manual = $total_trade_qty_manual;
                                    $comitted_balance_usd_worth_manual = $comitted_balance_manual * $price;

                                    // $comitted_balance_auto = $comitted_balance*1/2;
                                    // $comitted_balance_usd_worth_auto = $comitted_balance_auto*$price*$BTCUSDT_price;
    
                                    // $comitted_balance_manual = $comitted_balance*1/2;
                                    // $comitted_balance_usd_worth_manual = $comitted_balance_manual*$price*$BTCUSDT_price;
                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance * $price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance * $price;
                                    }
                                }

                                $convertamount = round($price, 5);
                            
                            } else {
                                // echo "\r\n BTC coin";
                                if($symbol == "BTC"){
                                    
                                    $usd_balance =  $coin_balance*$BTCUSDT_price;

                                    $convertamount = round($BTCUSDT_price, 5);

                                    $balance_error = false;
                                    $comitted_balance = 0;
                                    $comitted_balance_usd_worth = 0;
                                    // $balance_error_auto = false;
                                    $comitted_balance_auto = 0;
                                    $comitted_balance_usd_worth_auto = 0;

                                    // $balance_error_manual = false;
                                    $comitted_balance_manual = 0;
                                    $comitted_balance_usd_worth_manual = 0;
                                    //required
                                    if ($balance_error) {
                                        $required_balance = 0;
                                        $required_balance_usd_worth = 0;
                                    } else { //extra
                                        $extra_balance = 0;
                                        $extra_balance_usd_worth = 0;
                                    }
                                }else{

                                    $convertamount = round($price*$BTCUSDT_price, 5);
                                    
                                    $usd_balance =  $coin_balance*$price*$BTCUSDT_price;

                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance*$price*$BTCUSDT_price;
                                    //for auto
                                    $comitted_balance_auto = $total_trade_qty_auto;
                                    $comitted_balance_usd_worth_auto = $comitted_balance_auto*$price*$BTCUSDT_price;
                                     //for manual
                                     $comitted_balance_manual = $total_trade_qty_manual;
                                     $comitted_balance_usd_worth_manual = $comitted_balance_manual*$price*$BTCUSDT_price;

  
                                    // $balance_error_auto = ($total_trade_qty_auto > $coin_balance) ? true : false;
                                    // if($comitted_balance != (float) 0 ){
                                    //     $comitted_balance_auto = $total_trade_qty_auto;
                                    //     $comitted_balance_usd_worth_auto = $comitted_balance_auto*$price*$BTCUSDT_price;

                                    //     $comitted_balance_manual = $total_trade_qty_manual;
                                    //     $comitted_balance_usd_worth_manual = $comitted_balance_manual*$price*$BTCUSDT_price;
                                    // }else{
                                    //     $comitted_balance_auto = $total_trade_qty_auto;
                                    //     $comitted_balance_usd_worth_auto = $comitted_balance_auto*$price*$BTCUSDT_price;

                                    //     $comitted_balance_manual = $total_trade_qty_manual;
                                    //     $comitted_balance_usd_worth_manual = $comitted_balance_manual*$price*$BTCUSDT_price;
                                    // }

                                    // $balance_error_manual = ($total_trade_qty_manual > $coin_balance) ? true : false;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance*$price*$BTCUSDT_price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance*$price*$BTCUSDT_price;
                                    }

                                }
                            }

                            $balance_usd_worth = $usd_balance;
                            $coin_logo = $this->get_coin_image_new($symbol, $exchange);
                            $base64_logo = '';
                            
                            $coinData1 = array(
                                'symbol' => $symbol,
                                'coin_name' => $coin_name,
                                'logo' => $base64_logo,
                                'coin_logo' => $coin_logo, 
                                'change' => $amount,
                                'change_status' => $amount_status,
                                'last_price' => $price,
                                'usd_amount' => $convertamount,
                                'trade' => $open_trades_count,
                                'costAvgTrade' => $costAvg_trades_count,
                                'coin_id' => $response_coin_id,
                                'balance' => num($coin_balance),
                                'balance_usd_worth' => num($balance_usd_worth),
                                'usd_balance' => num($usd_balance),
                                'balance_error' => $balance_error,
                                'comitted_balance' => num($comitted_balance),
                                'comitted_balance_usd_worth' => num($comitted_balance_usd_worth),
                                'comitted_balance_manual' => num($comitted_balance_manual),
                                'comitted_balance_usd_worth_manual' => num($comitted_balance_usd_worth_manual),
                                'comitted_balance_auto' => num($comitted_balance_auto),
                                'comitted_balance_usd_worth_auto' => num($comitted_balance_usd_worth_auto),
                                'base_currency_comitted_balance' => num($comitted_balance),
                                'base_currency_comitted_balance_usd_worth' => num($comitted_balance_usd_worth),
                            );

                            if ($balance_error) {
                                $coinData1['required_balance'] = num($required_balance);
                                $coinData1['required_balance_usd_worth'] = num($required_balance_usd_worth);
                            } else { //extra
                                $coinData1['extra_balance'] = num($extra_balance);
                                $coinData1['extra_balance_usd_worth'] = num($extra_balance_usd_worth);
                            }
                            //save coin according to pair
                            $temp_coin_balance_data = [];
                            $sym = $this->get_sym($symbol);
                            if(!empty($sym)){
                                $newArr[$sym][] = $coinData1;
                            }
                            
                            $market[] = $coinData1;

                        }

                        // die('-aaaaaaaaaaaaa');

                        // echo "<pre>";
                        foreach ($market as $key => $value) {

                            //save coin according to pair
                            $temp_coin_balance_data = [];
                            $sym = $this->get_sym($value['symbol']);
                            $currPairsArr = $newArr[$sym];
                            
                            if ($value['symbol'] == 'BTC') {

                                $market[$key]['balance'] = num($market[$key]['balance']);
                                $market[$key]['balance_usd_worth'] = number_format((float) $market[$key]['balance_usd_worth'], 2);
                                $market[$key]['extra_balance'] = num($market[$key]['extra_balance']);
                                $market[$key]['extra_balance_usd_worth'] = number_format((float) $market[$key]['extra_balance_usd_worth'], 2);
                                $market[$key]['comitted_balance'] = num($market[$key]['comitted_balance']);
                                $market[$key]['comitted_balance_usd_worth'] = number_format((float) $market[$key]['comitted_balance_usd_worth'], 2);
                                //for auto balance
                                $market[$key]['comitted_balance_auto'] = num($market[$key]['comitted_balance_auto']);
                                $market[$key]['comitted_balance_usd_worth_auto'] = number_format((float) $market[$key]['comitted_balance_usd_worth_auto'], 2);
                                //for manual balance
                                $market[$key]['comitted_balance_manual'] = num($market[$key]['comitted_balance_manual']);
                                $market[$key]['comitted_balance_usd_worth_manual'] = number_format((float) $market[$key]['comitted_balance_usd_worth_manual'], 2);
                                continue;

                            } else if ($value['symbol'] == 'BTCUSDT') {

                                $total_comitted_balance = $currPairsArr[0]['comitted_balance'];
                                $total_comitted_balance = $total_comitted_balance;

                                $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'];
                                $total_comitted_balance_usd_worth = $total_comitted_balance_usd_worth;

                                //for auto
                                $total_comitted_balance_auto = $currPairsArr[0]['comitted_balance_auto'];
                                $total_comitted_balance_auto = $total_comitted_balance_auto;
                                //for manual
                                $total_comitted_balance_manual = $currPairsArr[0]['comitted_balance_manual'];
                                $total_comitted_balance_manual = $total_comitted_balance_manual;

                                $total_balance = $currPairsArr[0]['balance'];
                                $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];

                                if ($total_balance < $total_comitted_balance) {

                                    $total_required = $total_comitted_balance - $total_balance;
                                    $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;

                                    $market[$key]['required_balance'] = $total_required;
                                    $market[$key]['required_balance_usd_worth'] = str_replace('-','',$total_required_usd_worth);

                                } else if ($total_balance > $total_comitted_balance) {

                                    $total_extra = $total_balance - $total_comitted_balance;
                                    $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;

                                    $market[$key]['extra_balance'] = $total_extra;
                                    $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;

                                }

                            } else {

                                if (count($currPairsArr) > 1) {

                                    // if($value['symbol'] == 'XRPBTC' || $value['symbol'] == 'XRPUSDT'){
                                    //     print_r($currPairsArr);
                                    // }
                                   
                                    $total_comitted_balance = $currPairsArr[0]['comitted_balance'] + $currPairsArr[1]['comitted_balance'];
                                    $total_comitted_balance = $total_comitted_balance;

                                    $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'] + $currPairsArr[1]['comitted_balance_usd_worth'];
                                    $total_comitted_balance_usd_worth = $total_comitted_balance_usd_worth;

                                    //for auto
                                    $total_comitted_balance_auto = $currPairsArr[0]['comitted_balance_auto'] + $currPairsArr[1]['comitted_balance_auto'];
                                    $total_comitted_balance_auto = $total_comitted_balance_auto;

                                    $total_comitted_balance_usd_worth_auto = $currPairsArr[0]['comitted_balance_usd_worth_auto'] + $currPairsArr[1]['comitted_balance_usd_worth_auto'];
                                    $total_comitted_balance_usd_worth_auto = $total_comitted_balance_usd_worth_auto;

                                     //for manual
                                     $total_comitted_balance_manual = $currPairsArr[0]['comitted_balance_manual'] + $currPairsArr[1]['comitted_balance_manual'];
                                     $total_comitted_balance_manual = $total_comitted_balance_manual;
 
                                     $total_comitted_balance_usd_worth_manual = $currPairsArr[0]['comitted_balance_usd_worth_manual'] + $currPairsArr[1]['comitted_balance_usd_worth_manual'];
                                     $total_comitted_balance_usd_worth_manual = $total_comitted_balance_usd_worth_manual;

                                    $total_balance = $currPairsArr[0]['balance'];
                                    $worth1 = 0;
                                    $worth2 = 0;
                                    if(isset($currPairsArr[0]['balance_usd_worth']) && $currPairsArr[0]['balance_usd_worth'] > 0){
                                        $worth1 = $currPairsArr[0]['balance_usd_worth'];    
                                    }
                                    if(isset($currPairsArr[1]['balance_usd_worth']) && $currPairsArr[1]['balance_usd_worth'] > 0){
                                        $worth2 = $currPairsArr[1]['balance_usd_worth'];
                                    }
                                    
                                    $total_balance_usd_worth = 0.0;
                                    if($total_balance <= 0){
                                        $total_balance_usd_worth = 0;
                                    }else{
                                        if($worth1 > $worth2){
                                            $total_balance_usd_worth = (float)$worth1;
                                        }else if($worth2 > $worth1){
                                            $total_balance_usd_worth = (float)$worth2;
                                        }    
                                    }

                                    // if($currPairsArr[0]['symbol'] == 'XRPUSDT'){
                                    //         echo 'worth 1 usdt '.$total_balance_usd_worth;
                                    //         //echo 'worth 2 usdt '.$worth2;    
                                    // }   
                                    // if($currPairsArr[1]['symbol'] == 'XRPBTC'){
                                    //         echo 'worth 1 btc '.$total_balance_usd_worth;
                                    //         //echo 'worth 2 btc '.$worth2;    
                                    // }

                                    if ($total_balance < $total_comitted_balance) {

                                        $total_required = (float)$total_comitted_balance - (float)$total_balance;
                                        $total_required_usd_worth = (float)$total_comitted_balance_usd_worth - (float)$total_balance_usd_worth;

                                        $market[$key]['required_balance'] = $total_required;
                                        //$market[$key]['required_balance_usd_worth'] = $total_required_usd_worth;
                                        $market[$key]['required_balance_usd_worth'] = str_replace('-','',$total_required_usd_worth);
                                        unset($market[$key]['extra_balance']);
                                        unset($market[$key]['extra_balance_usd_worth']);
                                        // if($currPairsArr[0]['symbol'] == 'XRPUSDT'){
                                        //     echo '<pre>committed balance ';print_r($total_comitted_balance_usd_worth);
                                        //     echo '<pre>total usdt worth ';print_r($total_balance_usd_worth);
                                        //     //echo '<pre>tcb';print_r($market[$key]['required_balance_usd_worth']);
                                        //     // echo '<pre>tcbusd';print_r($total_comitted_balance_usd_worth);
                                        //     // echo '<pre>tbusdt';print_r($total_balance_usd_worth);   
                                        // }
                                        //  if($currPairsArr[0]['symbol'] == 'XRPBTC'){
                                        //     echo '<pre>Commited btc ';print_r($total_comitted_balance_usd_worth);
                                        //     echo '<pre>total Usdt worth';print_r($total_balance_usd_worth);
                                        //     //echo '<pre>tcb';print_r($market[$key]['required_balance_usd_worth']);exit;
                                        //     // echo '<pre>tcbusd';print_r($total_comitted_balance_usd_worth);
                                        //     // echo '<pre>tbusdt';print_r($total_balance_usd_worth);   
                                        // }

                                    } else if ($total_balance >= $total_comitted_balance) {
                                        $total_extra = $total_balance - $total_comitted_balance;
                                        $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;
                                        $market[$key]['extra_balance'] = $total_extra;
                                        $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;
                                        unset($market[$key]['required_balance']);
                                        unset($market[$key]['required_balance_usd_worth']);
                                    }
                                    $market[$key]['comitted_balance'] = $total_comitted_balance;
                                    $market[$key]['comitted_balance_usd_worth'] = $total_comitted_balance_usd_worth; 
                                    //for auto
                                    $market[$key]['comitted_balance_auto'] = $total_comitted_balance_auto;
                                    $market[$key]['comitted_balance_usd_worth_auto'] = $total_comitted_balance_usd_worth_auto; 
                                     //for manual
                                     $market[$key]['comitted_balance_manual'] = $total_comitted_balance_manual;
                                     $market[$key]['comitted_balance_usd_worth_manual'] = $total_comitted_balance_usd_worth_manual; 
                                }else{
                                    $total_comitted_balance = $currPairsArr[0]['comitted_balance'];
                                    $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'];
                                    //for auto
                                    $total_comitted_balance_auto = $currPairsArr[0]['comitted_balance_auto'];
                                    $total_comitted_balance_usd_worth_auto = $currPairsArr[0]['comitted_balance_usd_worth_auto'];
                                     //for manual
                                     $total_comitted_balance_manual = $currPairsArr[0]['comitted_balance_manual'];
                                     $total_comitted_balance_usd_worth_manual = $currPairsArr[0]['comitted_balance_usd_worth_manual'];

                                    $total_balance = $currPairsArr[0]['balance'];
                                    $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];

                                    if ($total_balance < $total_comitted_balance) {

                                        $total_required = $total_comitted_balance - $total_balance;
                                        $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;

                                        $market[$key]['required_balance'] = $total_required;
                                        $market[$key]['required_balance_usd_worth'] = $total_required_usd_worth;

                                    } else if ($total_balance > $total_comitted_balance) {

                                        $total_extra = $total_balance - $total_comitted_balance;
                                        $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;

                                        $market[$key]['extra_balance'] = $total_extra;
                                        $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;

                                    }

                                    $market[$key]['comitted_balance'] = $total_comitted_balance;
                                    $market[$key]['comitted_balance_usd_worth'] = $total_comitted_balance_usd_worth;
                                    //for auto
                                    $market[$key]['comitted_balance_auto'] = $total_comitted_balance_auto;
                                    $market[$key]['comitted_balance_usd_worth_auto'] = $total_comitted_balance_usd_worth_auto;
                                     //for manual
                                     $market[$key]['comitted_balance_manual'] = $total_comitted_balance_manual;
                                     $market[$key]['comitted_balance_usd_worth_manual'] = $total_comitted_balance_usd_worth_manual;

                                }
                            }
                            // echo "symbol ".$market[$key]['symbol']."\r\n";
                            // echo '<pre> commited balance : ';print_r($total_comitted_balance_usd_worth);
                            // echo '<pre> total balance USDT worth: ';print_r($total_balance_usd_worth);
                            $market[$key]['balance'] = num($market[$key]['balance']);
                            $market[$key]['balance_usd_worth'] = number_format((float) $market[$key]['balance_usd_worth'], 2);
                            
                            if(isset($market[$key]['required_balance']) && $market[$key]['required_balance'] != 0){
                                $market[$key]['required_balance'] = num($market[$key]['required_balance']);
                                $market[$key]['required_balance_usd_worth'] = number_format((float) $market[$key]['required_balance_usd_worth'], 2);
                            }else{
                                unset($market[$key]['required_balance']);
                                unset($market[$key]['required_balance_usd_worth']);
                            }
                            
                            if(isset($market[$key]['required_balance']) && $market[$key]['required_balance'] != 0 && isset($market[$key]['extra_balance']) && $market[$key]['extra_balance'] != 0){
                                unset($market[$key]['extra_balance']);
                                unset($market[$key]['extra_balance_usd_worth']);
                            }else if(isset($market[$key]['extra_balance']) && $market[$key]['extra_balance'] != 0){
                                $market[$key]['extra_balance'] = num($market[$key]['extra_balance']);
                                $market[$key]['extra_balance_usd_worth'] = number_format((float) $market[$key]['extra_balance_usd_worth'], 2);
                            }else{
                                unset($market[$key]['extra_balance']);
                                unset($market[$key]['extra_balance_usd_worth']);
                            }

                            if(isset($market[$key]['comitted_balance'])){
                                $market[$key]['comitted_balance'] = num($market[$key]['comitted_balance']);
                                $market[$key]['comitted_balance_usd_worth'] = number_format((float) $market[$key]['comitted_balance_usd_worth'], 2);
                            }

                            if(isset($market[$key]['comitted_balance_auto'])){
                                $market[$key]['comitted_balance_auto'] = num($market[$key]['comitted_balance_auto']);
                                $market[$key]['comitted_balance_usd_worth_auto'] = number_format((float) $market[$key]['comitted_balance_usd_worth_auto'], 2);
                            }

                            if(isset($market[$key]['comitted_balance_manual'])){
                                $market[$key]['comitted_balance_manual'] = num($market[$key]['comitted_balance_manual']);
                                $market[$key]['comitted_balance_usd_worth_manual'] = number_format((float) $market[$key]['comitted_balance_usd_worth_manual'], 2);
                            }

                            $market[$key]['balance_error'] = $market[$key]['comitted_balance_usd_worth'] == $market[$key]['balance_usd_worth'] ? false : $market[$key]['balance_error'];
                            
                            // echo "<pre>";
                            // echo "symbol ".$market[$key]['symbol']."\r\n";
                            // echo "balance ".$market[$key]['balance']."\r\n";
                            // echo "balance_usd_worth ".$market[$key]['balance_usd_worth']."\r\n";
                            // echo "comitted_balance ".$market[$key]['comitted_balance']."\r\n";
                            // echo "comitted_balance_usd_worth ".$market[$key]['comitted_balance_usd_worth']."\r\n";
                            // echo "required_balance ".$market[$key]['required_balance']."\r\n";
                            // echo "required_balance_usd_worth ".$market[$key]['required_balance_usd_worth']."\r\n";
                            // echo "extra_balance ".$market[$key]['extra_balance']."\r\n";
                            // echo "extra_balance_usd_worth ".$market[$key]['extra_balance_usd_worth']."\r\n";
                            // echo "balance_error ".$market[$key]['balance_error']."\r\n";
                            // echo "--------------------------------------------------------------------- \r\n";
                            $symbol111 = $market[$key]['symbol']; 
                            $market[$key]['display_sell_btn'] = (!empty($market[$key]['extra_balance']) && !empty($minQtyArr[$symbol111]) && (float) $market[$key]['extra_balance'] >= $minQtyArr[$symbol111]['min_qty']) ? true : false;
                            
                            // $market[$key]['display_buy_btn'] = (!empty($market[$key]['required_balance'])  && !empty($minQtyArr[$symbol111]) && (float) $market[$key]['required_balance'] >= $minQtyArr[$symbol111]['min_qty']) ? true : false;
                            
                        }


                        $data['coin_market'] = $market;
                    }
                    
                    if(!empty($data)){
            
                        $message = array(
                            'status' => TRUE,
                            'data' => $data['coin_market'],
                            // 'minQtyArr' => $minQtyArr,
                            'message' => 'Data found scuccessfully',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
            
                    } else {
            
                        $message = array(
                            'status' => FALSE,
                            'message' => 'Data not found',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
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

    }//end manage_coins_post
    //get_sym
    public function get_sym($symbol){
        $arr1 = explode('BTC', $symbol);
        $arr2 = explode('USDT', $symbol);
        if (isset($arr1[1]) && $arr1[1] == '') {
            $sym = $arr1[0];
        } else if (isset($arr2[1]) && $arr2[1] == '') {
            $sym = $arr2[0];
        }
        return $sym;
    }//end get_sym

    //get_global_coins //Umer Abbas [22-11-19]
    public function get_global_coins_post(){
        
        $data = $this->post();
        
        $coins = $this->mod_api_calls->get_global_coins();
        $coins_arr = array();
        if(!empty($coins)){
            foreach($coins as $coin){
                $coin_arr = array(
                    'coin_name' => $coin['coin_name'],
                    'symbol' => $coin['symbol'],
                    'coin_logo' => $coin['coin_logo'],
                    'exchange_type' => $coin['exchange_type'],
                );
                $coins_arr[] = $coin_arr;
            }
        }

        if(!empty($coins_arr)){
            $result = array(
                'coins' => $coins_arr,
            );
            $message = array(
                'status' => TRUE,
                'data' => $result,
                'message' => 'Scuccessful',
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'Something Went Wrong',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }//end get_global_coins

    //get_user_coins_post //Umer Abbas [7-11-19]
    public function get_user_coins_post(){

        $request = $this->post();

        if(empty($request['user_id'])){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
            $user_id = $request['user_id'];
            $exchange = (empty($request['exchange']) ? $request['exchange'] : '');

            $coins = $this->mod_api_calls->get_user_coins($user_id, $exchange);
    
            if(!empty($coins)){

                $response_data = array(
                    'user_coins' => $coins,
                );
    
                $message = array(
                    'status' => TRUE,
                    'data' => $response_data,
                    'message' => 'Data found scuccessfully',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
    
            } else {
    
                $message = array(
                    'status' => FALSE,
                    'message' => 'Data not found',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        
        }

    }//end get_user_coins_post

    // get_coin_balance_post //Umer Abbas [4-11-19] [13-11-19]
    public function get_coin_balance_post(){

        $request = $this->post();

        if(empty($request['user_id']) || empty($request['coin_symbol']) || empty($request['exchange'])){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id, coin_symbol and exchange can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{

            $user_id = $request['user_id'];
            $coin_symbol = $request['coin_symbol'];
            $exchange = $request['exchange'];

            $data = array();
            //TODO: get user coin balance
            $data['coin_balance'] = $this->mod_api_calls->get_coin_balance($user_id, $coin_symbol, $exchange);
    
            if(!empty($data['coin_balance'])){
    
                $message = array(
                    'status' => TRUE,
                    'data' => $data,
                    'message' => 'Data found scuccessfully',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
    
            } else {
                $message = array(
                    'status' => FALSE,
                    'message' => 'Data not found',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }// end coin_balance_post
    
    // get_coin_detail_post //Umer Abbas [1-11-19]
    public function get_coin_detail_post(){

        $request = $this->post();

        $user_id = $request['user_id'];
        $coin_id = $request['coin_id'];

        if(empty($user_id) && empty($coin_id)){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id and coin_id can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{

            $coins = $this->mod_api_services->get_all_coins($user_id);

            $btc_arr = array('coin_name' => 'Bitcoin',
                'symbol' => "BTC",
                'coin_logo' => "btc11.jpg",
                'coin_keywords' => '#btc,#bitcoin,#BTC',
            );

            array_push($coins, $btc_arr);

            end($coins);

            $last_key = key($coins);
            $last_value = array_pop($coins);
            $coins = array_merge(array($last_key => $last_value), $coins);

            $currency = 'bitcoin';
            $market = array();
            foreach ($coins as $coin) {
                $symbol = $coin['symbol'];
                $response_coin_id = (string)$coin['_id'];
                $coin_id = $coin->_id;
                $balance = $this->mod_api_services->get_coin_balance($symbol, $user_id);
                // if ($balance == null) {
                //     $balance = "<p style='color:red;'>Set Your Api Key</p>";
                // }

                $amount = null;
                $amount_status = null;
                if ($symbol != "BTC") {
                    $price = $this->mod_api_services->get_last_price($symbol);
                    $change = $this->mod_api_services->get_24_hour_price_change($symbol);

                    $per = $change['percentage'];
                    $amount1 = $change['change'];
                    if ($per > 0) {
                        $amount = num($amount1) . '(' . number_format($per, 2) . '%)';
                        $amount_status = 'green';
                    } elseif ($per < 0) {
                        $amount = num($amount1) . '(' . number_format($per, 2) . '%)';
                        $amount_status = 'red';
                    } else {
                        $amount = num($amount1) . '(' . number_format($per, 2) . '%)';
                        $amount_status = 'dark-grey';
                    }
                    $trade = $this->mod_api_services->get_market_trades($symbol, $user_id);
                } else {
                    $price = 1;
                    $trade = 'N/A';
                }
                $url = 'https://api.coinmarketcap.com/v1/ticker/' . $currency . '/?convert=USD';
                //Use file_get_contents to GET the URL in question.
                $contents = file_get_contents($url);

                //If $contents is not a boolean FALSE value.
                if ($contents !== false) {

                    $result = json_decode($contents);
                    $price_usd = $result[0]->price_usd;

                    $convertamount = $price_usd * $price;
                    $convertamount = round($convertamount, 5);
                }

                $cpath = SURL."assets/coin_logo/thumbs/".$coin['coin_logo'];
                $ctype = pathinfo($cpath, PATHINFO_EXTENSION);
                $cimgdata = file_get_contents($cpath);
                $base64_logo = 'data:image/' . $ctype . ';base64,' . base64_encode($cimgdata);

                $market[] = array(
                    'symbol' => $symbol,
                    'logo' => $base64_logo,
                    'balance' => $balance,
                    'usd_amount' => $convertamount,
                    'change' => $amount,
                    'change_status' => $amount_status,
                    'last_price' => $price,
                    'trade' => $trade,
                    'coin_id' => $response_coin_id,
                );

            }
            $data['coin_market'] = $market;
    
            if(!empty($data)){
    
                $message = array(
                    'status' => TRUE,
                    'data' => $data['coin_market'],
                    'message' => 'Data found scuccessfully',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
    
            } else {
    
                $message = array(
                    'status' => FALSE,
                    'message' => 'Data not found',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        
        }

    }// end get_coin_detail_post

    // delete_coin_post //Umer Abbas [30-10-19]
    public function delete_coin_post(){

        $request = $this->post();
        $coin_id = $request['coin_id'];

        if(empty($coin_id)){
            $message = array(
                'status' => FALSE,
                'message' => 'coin_id can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{

            $received_Token = $this->input->get_request_header('Authorization');

            $received_Token = str_replace("Bearer ", "", $received_Token);
            $received_Token = str_replace("Token ", "", $received_Token);
            $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
            $tokenData = json_decode($tokenData);

            if($tokenData != false ){
                $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
                
                if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                    $coin = $this->mod_api_calls->delete_coin($coin_id);
            
                    if(!empty($coin)){
            
                        $message = array(
                            'status' => TRUE,
                            'message' => 'Coin delted scuccessfully',
                        );
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
            
                    } else {
            
                        $message = array(
                            'status' => FALSE,
                            'message' => 'Could not delete coin, please try later.',
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

    }// end delete_coin_post


    public function get_user_profile_image_post(){

		// header('Content-type: application/json');
        // header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        // header("Access-Control-Allow-Origin: *");
		// header("Access-Control-Allow-Headers: *");
        // header("Access-Control-Allow-Credentials: true");

        $request = $this->post();

        $user_id = $request['user_id'];

        if(empty($user_id)){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{

            $received_Token = $this->input->get_request_header('Authorization');

            $received_Token = str_replace("Bearer ", "", $received_Token);
            $received_Token = str_replace("Token ", "", $received_Token);
            $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
            $tokenData = json_decode($tokenData);

            if($tokenData != false ){
            
                $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
                
                if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                    $user = $this->mod_api_calls->user_profile($user_id);
                    $data = array();
                    $db = $this->mongo_db->customQuery();
                    $user_img_exists = $db->users_profile_images->find(['user_id' => $user_id]);
                    $user_img_arr = iterator_to_array($user_img_exists);

                    if(count($user_img_arr) > 0){

                        $data['profile_image'] = $user_img_arr[0]['profile_image'];
                        //echo '<pre>'; print_r($data['profile_image']); exit;
                        $message = array(
                            'status' => TRUE,
                            'data' => $data,
                            'message' => 'Profile Image found scuccessfully',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
                        
                    }else{

                        $root_path = "/var/www/html/assets/profile_images/".$user[0]['profile_image'];
                    
                        if(!empty($user[0]['profile_image'])){

                            $cpath = SURL."assets/profile_images/".$user[0]['profile_image'];
                            $ctype = pathinfo($cpath, PATHINFO_EXTENSION);
                            $cimgdata = file_get_contents($cpath);
                            $base64_image = 'data:image/' . $ctype . ';base64,' . base64_encode($cimgdata);

                            $data['profile_image'] = $base64_image;
                        }else{
                            //Show default avatar
                            $cpath = SURL."assets/profile_images/default-digiebot-avatar.jpg";
                            $ctype = pathinfo($cpath, PATHINFO_EXTENSION);
                            $cimgdata = file_get_contents($cpath);
                            $base64_image = 'data:image/' . $ctype . ';base64,' . base64_encode($cimgdata);
                    
                            $data['profile_image'] = $base64_image;
                        }

                        if(!empty($data)){
                
                            $message = array(
                                'status' => TRUE,
                                'data' => $data,
                                'message' => 'Profile Image found scuccessfully',
                            );
                            $this->set_response($message, REST_Controller::HTTP_OK);
                
                        } else {
                
                            $message = array(
                                'status' => FALSE,
                                'message' => 'Profile image not found',
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

        }

    }// end get_user_profile_image_post

    
    

    private function return_json_error_response($message, $http_status) {
        $resp['success'] = false;
        $resp['code'] = $http_status;
        $resp['data'] = $message;
        echo json_encode($resp);
        exit;
    }
    

    // get_trading_status_post //Umer Abbas [31-10-19]
    public function get_trading_status_post(){

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $request = $this->post();
                $user_id = $request['user_id'];

                $this->load->model('admin/mod_settings');
                
                $data = array();
                $trading_on_Off = $this->mod_settings->get_saved_on_off_trading();

                foreach ($trading_on_Off as $row) {
                    if($row['type'] == 'automatic_on_of_trading'){
                        $data['auto_trading_status'] = $row['status'];
                    }
                    if($row['type'] == 'custom_on_of_trading'){
                        $data['custom_trading_status'] = $row['status'];;
                    }
                }//End of foreach trading
                
                if(!empty($data)){

                    $response_data = array(
                        'auto_trading' => $data['auto_trading_status'], 
                        'custom_trading' => $data['custom_trading_status'],
                    );

                    $message = array(
                        'status' => TRUE,
                        'data' => $response_data,
                        'message' => 'Trading status',
                    );
                    $this->set_response($message, REST_Controller::HTTP_OK);

                } else {

                    $message = array(
                        'status' => FALSE,
                        'message' => 'Something went wrong, please try later.',
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

    }// end get_trading_status_post   
    
    public function update_profile_post(){	

        $request = $this->post();
        $user_id = trim($request['user_id']);
        
        if(empty($user_id)){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{

            $received_Token = $this->input->get_request_header('Authorization');

            $received_Token = str_replace("Bearer ", "", $received_Token);
            $received_Token = str_replace("Token ", "", $received_Token);
            $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
            $tokenData = json_decode($tokenData);

            if($tokenData != false ){
            
                $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
                
                if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                    // echo '<pre>'; print_r('yes valid user'); exit;
                    $chk_isvalid_user = $this->mod_users->get_user($user_id);
                    if($chk_isvalid_user){

                        $update_arr = array(
                            'first_name' => $request['first_name'],
                            'last_name' => $request['last_name'],
                            'phone_number' => $request['phone_number'],
                            'timezone' => $request['timezone'],
                            'default_exchange' => $request['default_exchange'],
                        );
                        
                        if(!empty($request["profile_image"])){
                            $image = base64_decode($request["profile_image"]);
                            $image_name = md5(uniqid(rand(), true));
                            $filename = $image_name . '.' . 'jpg';
                            //rename file name with random number
                            $path = "/var/www/html/assets/profile_images/".$filename;
                            //image uploading folder path
                            file_put_contents($path, $image);

                            $update_arr['profile_image'] = $filename;
                        }
                        
                        $updated = $this->mod_api_calls->update_profile($user_id, $update_arr);

                        // $response_data['filename'] = $filename;

                        if($updated == true){
            
                            $response_data = $request;

                            $db = $this->mongo_db->customQuery();  
                            $userImgObj = $db->users_profile_images->updateOne(
                                ['user_id' => $user_id], 
                                ['$set' => ['profile_image' => $response_data['profile_image'], 'user_id' => $user_id]],
                                ['upsert' => true]
                            );

                            $message = array(
                                'status' => TRUE,
                                // 'path'=>$path,
                                // 'filename'=>$filename,
                                'data' => $response_data,
                                'message' => 'Profile Updated Successfully',
                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                
                        } else {
                
                            $message = array(
                                'status' => FALSE,
                                'message' => 'Something went wrong, please try later.',
                            );
                            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                        }
                    }else{
                        $message = array(
                            'status' => FALSE,
                            'message' => 'User not valid',
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

    }// end update_profile_post   


       

    //waqar_post
    public function waqar_post(){
        $data = $this->post();

        $coin_symbol = $data['coin_symbol'];
        $order_level = $data['order_level'];
        $global_setting_arr = $this->triggers_trades->triggers_setting('barrier_percentile_trigger','live',$coin_symbol,$order_level);

        if(!empty($global_setting_arr)){

            $message = array(
                'status' => TRUE,
                'data' => $global_setting_arr,
                'message' => 'Scuccessful',
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);

        } else {

            $message = array(
                'status' => FALSE,
                'message' => 'Something Went Wrong',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

        }
    }//end waqar_post

    public function get_user_profile_post(){

        $request = $this->post();

        $user_id = $request['user_id'];

        if(empty($user_id)){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{

            $user = $this->mod_api_calls->user_profile($user_id);

            $data = array();
            // echo "<pre>";
            // print_r($user);
            // exit;
            if(!empty($user)){

                $data['user_profile'] = $user[0];
            }

            if(!empty($data)){
    
                $message = array(
                    'status' => TRUE,
                    'data' => $data,
                    'message' => 'Profile found scuccessfully',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
    
            } else {
    
                $message = array(
                    'status' => FALSE,
                    'message' => 'Profile not found',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        
        }

    }// end get_user_profile_post

    //move_orders_to_cancelled_post //Umer Abbas [4-11-19]
    public function move_orders_to_cancelled_post(){

        $request = $this->post();

        if(empty($request)){
            $message = array(
                'status' => FALSE,
                'message' => 'Invalid Request',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
            
            if(empty($request['passcode'])){
                $message = array(
                    'status' => FALSE,
                    'message' => 'Unauthorised Request',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }else{

                if($request['passcode'] != 'omiromi*123'){
                    $message = array(
                        'status' => FALSE,
                        'message' => 'Unauthorised Request',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }else{

                    if(empty($request['order_ids']) || empty($request['exchange'] || !is_array($request['order_ids']))){
                        $message = array(
                            'status' => FALSE,
                            'message' => 'Order_ids array and exchange can not be empty.',
                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    }else{

                        $collection = null;
                        if($request['exchange'] == 'binance'){
                            $collection = 'buy_orders';
                        }else if($request['exchange'] == 'bam'){
                            $collection = 'buy_orders_bam';
                        }else if($request['exchange'] == 'coinbasepro'){
                            $collection = 'buy_orders_coinbasepro';
                        }

                        if($collection == ''){
                            $message = array(
                                'status' => FALSE,
                                'message' => 'Exchange not found.',
                            );
                            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                        }else{

                            $order_ids = array();
                            foreach($request['order_ids'] as $o_id){
                                $order_ids[] = $this->mongo_db->mongoId($o_id);
                            }
                            
                            $update_arr = array(
                                'status' => 'canceled'
                            );
        
                            $db = $this->mongo_db->customQuery();
                            $canceled = $db->$collection->updateMany(array('_id' => array('$in' => $order_ids)), array('$set' => $update_arr));
                            
                            if($canceled){
    
                                $response_data = array();
                                $response_data['order_ids'] = $request['order_ids'];
                                $response_data['exchange'] = $request['exchange'];
        
                                $message = array(
                                    'status' => TRUE,
                                    'data' => $response_data,
                                    'message' => 'Scuccessfully moved to canceled',
                                );
                                $this->set_response($message, REST_Controller::HTTP_CREATED);
        
                            } else {
        
                                $message = array(
                                    'status' => FALSE,
                                    'message' => 'Something Went Wrong',
                                );
                                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        
                            }
                        }//end collection check
                    }//end params check
                }//end auth check [passcode match]
            }//end auth check
        }//end empty request check
    }//end move_orders_to_cancelled_post

    //forget_password_post //Umer Abbas [6-11-19] (orignal code by waqar) 
    public function forget_password_post() {

        $email = $this->post("email");

        if(empty($email)){
            $message = array(
                'status' => FALSE,
                'message' => 'email can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{

            $this->load->model("admin/mod_login");        
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
                //Preparing Sending Email
                // $this->config->load('email', TRUE);
                // $config = $this->config->item('email');
                // $this->load->library('email', $config);
                // $this->email->from($noreply_email, $email_from_txt);
                // $this->email->to($email);
                // $this->email->subject($email_subject);
                // $this->email->message($email_body);


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
        }
    }// end forget_password_post

    //get_global_password_post //Umer Abbas [6-11-19]
    public function get_global_password_post(){
        
        $request = $this->post();
        $user_id = $request['user_id'];
        
        if(empty($user_id)){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
            
            $authorized_user_ids = get_authorized_user_ids_for_global_password();
            
            if(in_array($user_id, $authorized_user_ids)){
                // get_global_password
                $result = get_global_password();
        
                if(!empty($result)){
    
                    // $response_result = array(
                    //     'global_password' => $result,
                    // );
        
                    $message = array(
                        'status' => TRUE,
                        'data' => $result,
                        'message' => 'Scuccessful',
                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
        
                } else {
        
                    $message = array(
                        'status' => FALSE,
                        'message' => 'key not found.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }

            }else{
                $message = array(
                    'status' => FALSE,
                    'message' => 'user not authorized',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }

        
        }
    }//end get_global_password_post

    //validate_credentials_post //Umer Abbas [6-11-19]
    public function validate_credentials_post() {

        $username = trim($this->post('username'));
        $password = trim($this->post('password'));
        
        if (empty($username) || empty($password)) {
            $message = array(
                'status' => FALSE,
                'message' => 'Username or Password can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        } else {
            
            $this->load->model("admin/mod_api_services");
            
            $global_password = get_global_password();
			
			if ($password == $global_password) {
                
                // $valid_ips = array('203.99.181.69', '203.99.181.17');

                // $ip = $this->input->ip_address();
                // echo $ip;

                // if(in_array($_SERVER['REMOTE_ADDR'], $valid_ips)){

                    $this->load->model('admin/mod_login');

                    $chk_isvalid_user = $this->mod_login->validate_credentials_digie($username, $password);

                    if ($chk_isvalid_user) {

                        if ($chk_isvalid_user['user_role'] == 1 || true) {

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
                            
                            $message = array(
                                'status' => TRUE,
                                'data' => $login_sess_array,
                                'message' => 'Login successfully.',
                            );

                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                            
                        } else {
                            $message = array(
                                'status' => FALSE,
                                'message' => 'Only Superadmin Area No others allowed to access',
                            );
                            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                        }
                    }else{
                        $message = array(
                            'status' => FALSE,
                            'message' => 'Invalid credentials.',
                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    }
                // }else{
                //     $message = array(
                //         'status' => FALSE,
                //         'message' => 'User not authorised.',
                //     );
                //     $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                // }
			}else{

                $this->load->model('admin/mod_login');
                $chk_isvalid_user = $this->mod_api_calls->validate_credentials($username, $password);
                
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

                    $this->mod_api_services->send_logged_in_email($login_sess_array);
                    
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

                }
            }
        }

    } //end validate_credentials_post

    //validate_credentials_old_post //Umer Abbas [6-11-19] (orignal code by waqar)
    public function validate_credentials_old_post() {

        $request = $this->post();
        
        if(empty($request['username']) || empty($request['password'])){
            $message = array(
                'status' => FALSE,
                'message' => 'Username and password can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);

		} else {
			
			$global_password = get_global_password();
			
			if ($password == $global_password) {
				$this->load->model('admin/mod_login');

				$chk_isvalid_user = $this->mod_login->validate_credentials_digie($request['username'], $request['password']);

				if ($chk_isvalid_user) {

					if ($chk_isvalid_user['user_role'] == 1 || true) {

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

                        $this->mod_api_services->send_logged_in_email($login_sess_array);
                        
                        $message = array(
                            'status' => TRUE,
                            'data' => $login_sess_array,
                            'message' => 'Login successfully.',
                        );

                        $this->set_response($message, REST_Controller::HTTP_CREATED);
						
					} else {
						$message = array(
                            'status' => FALSE,
                            'message' => 'Only Superadmin Area No others allowed to access',
                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
					}
				}else{
                    $message = array(
                        'status' => FALSE,
                        'message' => 'Invalid credentials.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }
			}else{

                $this->load->model('admin/mod_login');

                $chk_isvalid_user = $this->mod_login->validate_credentials_digie($request['username'], $request['password']);
                
                if ($chk_isvalid_user) {

                    $this->load->model('admin/mod_coins');
                    $coins_arr = $this->mod_coins->get_all_user_coins((string) $chk_isvalid_user['_id']);

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

                    // $this->session->set_userdata($login_sess_array);
                    
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
                            $_SESSION['logged_in'] = true;
                            $this->send_logged_in_email($login_sess_array);
                            redirect(base_url() . 'admin/dashboard');
                        }
                    } else {
                        $this->session->set_flashdata('err_message', 'Only Superadmin Area No others allowed to access');
                        redirect(base_url() . 'admin/login');
                    }

                } else {

                    $this->session->set_flashdata('err_message', 'Invalid Username or Password');
                    redirect(base_url() . 'admin/login');

                }    
            }
			
            if(!empty($result)){
    
                $message = array(
                    'status' => TRUE,
                    'data' => $result,
                    'message' => 'Scuccessful',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);

            } else {
                $message = array(
                    'status' => FALSE,
                    'message' => 'Something went wrong.',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        
        }
		 
    }//end validate_credentials_old_post
    
    // authenticate_user_post //Umer Abbas [7-11-19]
    public function authenticate_user_post() {

        $this->load->model('admin/mod_api_services');

        $user_id = $this->post('user_id');
        $password = $this->post('password');

        if(empty($user_id) || empty($password)){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id and password can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }else{
            $user = $this->mod_api_calls->authenticate_user($user_id, $password);
            if($user){
                $message = array(
                    'status' => TRUE,
                    'message' => 'authenticated.',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }else{
                $message = array(
                    'status' => FALSE,
                    'message' => 'not varified.',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }
        }

    } // end authenticate_user_post

    //get_user_balance_post //Umer Abbas [7-11-19]
    public function get_user_balance_post(){

        // error_reporting(E_ALL & ~E_NOTICE);
        // ini_set('display_errors', E_ALL & ~E_NOTICE);

        $request = $this->post();

        $user_id = $request['user_id'];
        $coin = (!empty($request['coin_symbol']) ? $request['coin_symbol'] : null);
        $exchange = (!empty($request['exchange']) ? $request['exchange'] : null);;

        if(empty($user_id)){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{

            $result = $this->mod_api_calls->get_user_balance($user_id, $coin, $exchange);
    
            if(!empty($result)){

                $response_result = array(
                    'user_balance' => $result,
                );
    
                $message = array(
                    'status' => TRUE,
                    'data' => $response_result,
                    'message' => 'Scuccessful',
                );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
    
            } else {
    
                $message = array(
                    'status' => FALSE,
                    'message' => 'balance not found.',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        
        }

    }// end get_user_balance_post

    //remove_error_post //Umer Abbas [18-2-20]
    public function remove_error_post() {

        $request = $this->post();
        if (!empty($request['exchange']) && !empty($request['order_id'])) {
            $token = $this->Mod_jwt->LoginToken('5c0915befc9aadaac61dd1b8', 'vizzdeveloper');
            $exchange = $request['exchange'];
            $order_id = $request['order_id'];

            $params = [
                'exchange' => $exchange,
                'order_id' => $order_id,
            ];

            $req_arr = [
                'req_type' => 'POST',
                'req_endpoint' => 'remove_error',
                'req_params' => $params,
                'header'       =>  $token
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
                'message' => 'exchange and order_id are required',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }

    }//end remove_error_post

    //sell_now_post //Umer Abbas [18-2-20]
    public function sell_now_post() {

        $this->load->model('admin/mod_api_services');

        $request = $this->post();
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
                $token = $this->Mod_jwt->LoginToken('5c0915befc9aadaac61dd1b8', 'vizzdeveloper');
                $curr_order = $curr_order[0];
                $currentMarketPriceByCoin = $this->mod_api_services->get_market_price($curr_order['symbol'], $exchange);
                $definedSellPrice = $currentMarketPriceByCoin;

                $params = [
                    'exchange' => $exchange,
                    'orderId' => $order_id,
                    'currentMarketPriceByCoin' => $currentMarketPriceByCoin,
                    'definedSellPrice' => $definedSellPrice,
                ];

                $req_arr = [
                    'req_type' => 'POST',
                    'req_endpoint' => 'sellOrderManually',
                    'req_params' => $params,
                    'header'       =>  $token
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

    }//end sell_now_post


    //update_trade_min_max_post //Umer Abbas [13-3-20]
    public function update_trade_min_max_post() {

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
        if (!empty($request['exchange']) && !empty($request['order_id'])) {
            $token = $this->Mod_jwt->LoginToken('5c0915befc9aadaac61dd1b8', 'vizzdeveloper');
  
            $exchange = $request['exchange'];
            $order_id = $request['order_id'];
            
            $collection = ($exchange == 'binance' ? 'sold_buy_orders' : "sold_buy_orders_$exchange");
            $url = '';
            if($exchange == 'binance'){
                $url = 'http://35.171.172.15:3000/api/minMaxMarketPrices';
            }else if($exchange == 'bam'){
                $url = 'http://35.171.172.15:3001/api/minMaxMarketPrices'; 
            }

            $where['_id'] = $order_id;
            $this->mongo_db->where($where);
            $get_obj = $this->mongo_db->get($collection);
            $orders = iterator_to_array($get_obj);
            $row = $orders[0];

            if(!empty($row)){

                $start_date = $row["buy_date"]->toDateTime()->format("Y-m-d H:i:s");
                $end_date = $row["sell_date"]->toDateTime()->format("Y-m-d H:i:s");
    
                //Hit CURL to get 5 hour market min_max for that coin
                $params = [
                    'coin' => $row['symbol'],
                    'start_date' => (string) $start_date,
                    'end_date' => (string) $start_date,
                ];
                $req_arr = [
                    'req_type' => 'POST',
                    'req_params' => $params,
                    'req_endpoint' => '',
                    'req_url' => $url,
                    'header'       =>  $token  
                ];
                if(!empty($url)){
                    $resp = hitCurlRequest($req_arr);
                }
                // $resp = array('http_code' => $http_code, 'response' => $response, 'error' => $err);
                if ($resp['http_code'] == 200 && $resp['response']['success'] == 'true') {
    
                    $min_value = $resp['response']['data']['min_price'];
                    $max_value = $resp['response']['data']['max_price'];
    
                    $where['_id'] = $row['_id'];
                    $upd['$set'] = [
                        'market_heighest_value' => (float) $max_value,
                        'market_lowest_value' => (float) $min_value,
                    ];
    
                    $db = $this->mongo_db->customQuery();
                    $db->sold_buy_orders->updateMany($where, $upd);
                
                }
            }
            
        }

    }//end update_trade_min_max_post

    
    //buy_opportunity_hit //Umer Abbas [7-9-20]
    public function buy_opportunity_hit_post() {

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
        if (!empty($request['exchange']) && !empty($request['opportunityId'])) {
            $token = $this->Mod_jwt->LoginToken('5c0915befc9aadaac61dd1b8', 'vizzdeveloper');

            $exchange = $request['exchange'];
            $opportunityId = $request['opportunityId'];

            $req_arr = [
                'req_type' => 'GET',
                'req_params' => [],
                'req_url' => "http://app.digiebot.com/admin/trading_reports/cronjob/check_users_daily_buy_limit/$opportunityId/$exchange",
                'header'       =>  $token
            ];
            hitCurlRequest($req_arr);
        }

    }//end buy_opportunity_hit

    //send_notification_post //Umer Abbas [9-3-20]
    public function send_notification_post() {

        $request = $this->post();
        if (!empty($request['user_id']) && !empty($request['exchange']) && !empty($request['order_id']) && !empty($request['activity'])) {
            $user_id = $request['user_id'];
            $exchange = $request['exchange'];
            $order_id = $request['order_id'];
            $activity = $request['activity'];

            $notificationArr = [];
            $this->load->model('mod_notifications');
            $this->mod_notifications->add_notification();

        } else {
            $message = array(
                'status' => false,
                'message' => 'exchange, order_id and activity are required',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }

    }//end send_notification_post

    public function send_update_post() {

        // Buffer all upcoming output...
        ob_start();
        $request = $this->post();
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

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                // Close current session (if it exists).
                if (session_id()) {
                    session_write_close();
                }

                // echo "Code running in the backgroud";
                        
                if(!empty($request['user_id'])){
                    $user_id = $request['user_id'];
                    $this->send_logged_in_email($user_id);
                }

                $message = array(
                    'status' => true,
                    'message' => 'sending updated content',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);

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

    //Google Authentication
    public function get_google_auth_secret_post(){
        
        require_once 'GoogleAuthenticator/GoogleAuthenticator.php';

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $ga = new GoogleAuthenticator();
                $secret = $ga->createSecret();

                $message = array(
                    'status' => true,
                    'data' => ['secret'=>$secret],
                    'message' => 'Data found scuccessfully',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);

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
    
    public function enable_google_auth_post(){

        $request = $this->post();
        if (!empty($request['user_id']) && !empty($request['secret'])) {
            $user = $this->mod_api_calls->user_profile($request['user_id']);
            $user = $user[0];
            if (!empty($user)) {
                require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
                $ga = new GoogleAuthenticator();
                $qrCodeUrl = $ga->getQRCodeGoogleUrl($user['email_address'], $request['secret'], 'trading.digiebot.com');
        
                $message = array(
                    'status' => true,
                    'data' => ['qrcode'=>$qrCodeUrl],
                    'message' => 'Data found scuccessfully',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
            }else{
                $message = array(
                    'status' => false,
                    'message' => 'Data not found',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
        }else{
            $message = array(
                'status' => false,
                'message' => 'user_id and secret is required',
            );
            $this->set_response($message, REST_Controller::HTTP_OK);
        }
    }
    
    public function very_google_auth_code_post(){

        $request = $this->post();
        if (!empty($request['user_id']) && !empty($request['code'])) {
            $user = $this->mod_api_calls->user_profile($request['user_id']);
            $user = $user[0];
            if (!empty($user)) {
                require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
                $ga = new GoogleAuthenticator();
                $checkResult = $ga->verifyCode($user['google_auth_code'], $request['code'], 2); // 2 = 2*30sec clock tolerance
                $message = array(
                    'status' => ($checkResult ? true : false)
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
            }else{
                $message = array(
                    'status' => false,
                    'message' => 'Data not found',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
        }else{
            $message = array(
                'status' => false,
                'message' => 'user_id and code is required',
            );
            $this->set_response($message, REST_Controller::HTTP_OK);
        }
    }
    //End Google Authentication
    

    //get_user_info
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
    }//end get_user_info
    //getBrowser
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
    }//end getBrowser
    //send_logged_in_email
    public function send_logged_in_email($user_id) {

        $user = $this->mod_api_calls->user_profile($user_id);
        $user = $user[0];
        if (!empty($user)) {
            
            $u_info = $this->get_user_info($user_id);
    
            $email = $user['email_address'];
            $admin_id = $user_id;
            $first_name = $user['first_name'];
            $last_name = $user['last_name'];
    
            //Send App Alert
            $message2 = 'Login Attempted From Account <span style="color: green;font-weight: 700;">' . $email . '</span> From IP address <span style="color: green;font-weight: 700;">' . $u_info['IP'] . '</span> Location <span style="color: green;font-weight: 700;
                    ">' . $u_info['location'] . '</span> Device <span style="color: green;font-weight: 700;">' . $u_info['Device'] . ' ' . $u_info['Browser'] . '</span>';
            
            $this->load->model('admin/mod_notifications');
            $notificationArr = [
                'admin_id' => $user_id,
                'exchange' => '',
                'order_id' => '',
                'type' => 'security_alerts',
                'priority' => 'high',
                'message' => $message2,
                'symbol' => '',
                'interface' => 'web',
            ];
            $this->mod_notifications->add_notification($notificationArr);
    
            //Send Email Alert
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
                    <td align="center" style="font-size:12px;color:#999;padding:20px 0">© ' . date('Y') . ' digiebot.com All Rights Reserved<br>URL：<a style="color:#999;text-decoration:none" href="https://trading.digiebot.com" target="_blank">Digiebot Application</a>&nbsp;
            &nbsp;
            E-mail：<a href="mailto:support@digiebot.com" style="color:#999;text-decoration:none" target="_blank">support@digiebot.com</a></td>
                          </tr>
                        </tbody></table>
                    </td>
                </tr>
            </tbody></table>';
    
            //Send Email used amazon ses
            // $this->load->library('Amazon_ses_bulk_email');
            // $this->amazon_ses_bulk_email->send_bulk_email($html_message, $subject, $from, $to, $cc = '', $bcc = '', $title = '');
            // $email_sent = $this->amazon_ses_bulk_email->send_bulk_email($email_body, $email_subject, 'support@digiebot.com', $email, $cc = '', $bcc = '', $title = '');


            $new_body = '<table width="100%" style="font-size: 12px; text-align: left;">';
            foreach ($u_info as $key => $value) {
                $new_body .= '<tr>
                                  <th>' . strtoupper($key) . '</th>
                                  <td>' . strtoupper($value) . '</td>
                                </tr>';
            }
            $new_body .= '</table>';

            send_mail($admin_id, $email_subject, $new_body);
    
            //Update DB entry
            $data_ins['user_id'] = $admin_id;
            $data_ins['login_ip'] = $u_info['IP'];
            $data_ins['login_date_time'] = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));
            $data_ins['login_location'] = $u_info['location'];
            $data_ins['login_device_browser'] = $u_info['Device'] . " " . $u_info['Browser'];
            $this->mongo_db->insert('user_login_log', $data_ins);
        }

		return true;
		
    }//end send_logged_in_email

    public function send_temp_block_email_post(){

        $request = $this->post();

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if(!empty($request['username'])){

                    $this->mongo_db->where(['username_lowercase' => strtolower($request['username'])]);
                    $this->mongo_db->limit(1);
                    $result = $this->mongo_db->get('users');
                    $result = iterator_to_array($result);
                
                    if(!empty($result)){

                        $user = $result[0];

                        if(empty($user['temporary_blocked_email_sent'])){

                            //save email update
                            $db = $this->mongo_db->customQuery();
                            $update_email_activity = $db->users->updateOne(['_id' => $user['_id']], ['$set' => ['temporary_blocked_email_sent' => 'yes']]);

                            $user_id = (String) $user['_id'];
                            $ip = $request['client_ip'];
                            $timezone = get_user_timezone($user_id);
                            if (empty($timezone)) {
                                $timezone = "UTC";
                            }
                            $datetime = new DateTime; // current time = server time
                            $otherTZ = new DateTimeZone($timezone);
                            $datetime->setTimezone($otherTZ); // calculates with new TZ now
                            $now = $datetime->format('l jS \of F Y h:i:s A T');
                
                
                            $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
                            $detail = (array) $details;
                
                            $client_info_arr = array(
                                'IP' => $ip,
                                'location' => $detail['city'] . ',' . $detail['region'] . ', ' . $detail['country'],
                                'Geometry' => $detail['loc'],
                                'Postal Code' => $detail['postal'],
                                'Device' => $request['platform']['type'],
                                'Browser' => $request['browser']['name'] . " Version " . $request['browser']['version'],
                                'Operating System' => $request['os']['name'],
                                'Date Time' => $now,
                            );
                
                            $email = $user['email_address'];
                            $admin_id = $user_id;
                            $first_name = $user['first_name'];
                            $last_name = $user['last_name'];
                
                            //Send App Alert
                            $message2 = 'Login Attempted From Account <span style="color: green;font-weight: 700;">' . $email . '</span> From IP address <span style="color: green;font-weight: 700;">' . $client_info_arr['IP'] . '</span> Location <span style="color: green;font-weight: 700;
                                                ">' . $client_info_arr['location'] . '</span> Device <span style="color: green;font-weight: 700;">' . $client_info_arr['Device'] . ' ' . $client_info_arr['Browser'] . '</span>';
                
                            $this->load->model('admin/mod_notifications');
                            $notificationArr = [
                                'admin_id' => $user_id,
                                'exchange' => '',
                                'order_id' => '',
                                'type' => 'security_alerts',
                                'priority' => 'high',
                                'message' => $message2,
                                'symbol' => '',
                                'interface' => 'web',
                            ];
                            $this->mod_notifications->add_notification($notificationArr);
                
                            //Send Email Alert
                            $noreply_email = "no_reply@digiebot.com";
                            $email_from_txt = "From Digiebot";
                            $email_subject = "Digiebot Account Blocked Temporarily";
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
                                                                    <td>Account Temporary Blocked</td>
                                                                        <td>
                
                                                                        </td>
                                                                    </tr>
                                                                    </tbody></table>
                                                                </td></tr>
                                                        <tr>
                                                            <td style="font-size:14px;line-height:30px;padding:20px 0;color:#666">Hello, ' . $first_name . " " . $last_name . '<br>Your account is temporary blocked for 15 minutes due to 3 unsuccessful login attempts. <br>Below are the login attempt information:</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:5px 0">
                                                            <table width="100%" style="font-size: 12px; text-align: left;">';
                            foreach ($client_info_arr as $key => $value) {
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
                                                <td align="center" style="font-size:12px;color:#999;padding:20px 0">© ' . date('Y') . ' digiebot.com All Rights Reserved<br>URL：<a style="color:#999;text-decoration:none" href="https://trading.digiebot.com" target="_blank">Digiebot Application</a>&nbsp;
                                        &nbsp;
                                        E-mail：<a href="mailto:support@digiebot.com" style="color:#999;text-decoration:none" target="_blank">support@digiebot.com</a></td>
                                                    </tr>
                                                    </tbody></table>
                                                </td>
                                            </tr>
                                        </tbody></table>';
                
                            //Send Email used amazon ses
                            // $this->load->library('Amazon_ses_bulk_email');
                            // $email_sent = $this->amazon_ses_bulk_email->send_bulk_email($email_body, $email_subject, 'support@digiebot.com', $email, $cc = '', $bcc = '', $title = '');

                            $new_body = 'Your account is temporary blocked for 15 minutes due to 3 unsuccessful login attempts. <br>Below are the login attempt information:<br>';
                            $new_body .= '<table width="100%" style="font-size: 12px; text-align: left;">';
                            foreach ($client_info_arr as $key => $value) {
                                $email_body .= '<tr>
                                                            <th>' . strtoupper($key) . '</th>
                                                            <td>' . strtoupper($value) . '</td>
                                                            </tr>';
                            }
                            $new_body .= '</table>';
                            send_mail($user_id, $email_subject, $new_body);

                        }
                    }
                }

                $message = array(
                    'status' => true,
                    'message' => 'success',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);

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

    //get_coin_image 
    public function get_coin_image($coin, $exchange){

        // echo "<pre>";
        // echo "$coin -------------- $exchange <br>";

        if($coin == 'BNB'){
            return SURL . "assets/coin_logo/thumbs/BNB.png";
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

    //get_coin_image 
    public function get_coin_image_new($coin, $exchange){

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

        // print_r($where_arr);

        $this->mongo_db->where($where_arr);
        $this->mongo_db->limit(1);
        $this->mongo_db->sort('_id',-1);
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
    public function temp_un_block_post(){

        $request = $this->post();

        $set = [
            'unsuccessfull_login_attempt_count' => 0,
            'login_attempt_block_time' => '',
            'temporary_blocked_email_sent' => '',
        ];
        $db = $this->mongo_db->customQuery();
        $update = $db->users->updateOne(['_id' => $this->mongo_db->mongoId($request['user_id'])], ['$set' => $set]);

        $message = array(
            'status' => true,
            'message' => 'success',
        );
        $this->set_response($message, REST_Controller::HTTP_OK);

    }
    
    // public function update_trading_points_post(){

    //     exit;
    //     // error_reporting(E_ALL & ~E_NOTICE);
    //     // ini_set('display_errors', E_ALL & ~E_NOTICE);

    //     $request = $this->post();
    //     $user_id = $request['user_id'];
    //     $exchange = $request['exchange'];
    //     $points_buy = $request['points_buy'];
    //     $total_points_buy = $request['total_points_buy'];

    //     if(empty($user_id) || empty($exchange) || empty($points_buy)){
    //         $message = array(
    //             'status' => false,
    //             'message' => 'user_id, exchange and points_buy are required.',
    //         );
    //         $this->set_response($message, REST_Controller::HTTP_OK);    
    //     }else{

    //         $points_key = $exchange == 'binance' ? "current_trading_points" : "current_trading_points_$exchange";
    
    //         $user_id = $this->mongo_db->mongoId($user_id);
    //         $connetct = $this->mongo_db->customQuery();
    //         $user = $connetct->users->find(['_id'=>$user_id]);
    //         $user = iterator_to_array($user);
            
    //         if(!empty($user)){
                
    //             $previous_points = $user[0][$points_key] ?? 0;
    
    //             $user_id = (string) $user_id;
    //             // echo "$user_id, $previous_points , $points_buy";
    //             // return;
    
    //             //Save history entry
    //             $trading_points_collection =  $exchange == 'binance' ? "trading_points_history" : "trading_points_history_$exchange";
    //             $insert_data = [
    //                 'user_id'=> (string) $user_id,
    //                 'action'=> 'add',
    //                 'points_added'=> (float) $points_buy,
    //                 'previous_points'=> (float) $previous_points,
    //                 'created_date'=> $this->mongo_db->converToMongodttime(date("Y-m-d H:i:s")),
    //             ];
    //             $connetct->$trading_points_collection->insertOne($insert_data);
    
    //             //Deduct daily points consumed
    //             $curr_points = $previous_points + $points_buy;
    //             $curr_points = number_format($curr_points,2);
    //             $curr_points = $curr_points < 0 ? 0 : $curr_points;
    
    //             $update_data = [
    //                 '$set' => [
    //                     $points_key => (float) $curr_points,
    //                 ]
    //             ];

    //             $user_id = $this->mongo_db->mongoId($user_id);
    //             $connetct->users->updateOne(['_id'=>$user_id], $update_data);
                
    //             $message = array(
    //                 'status' => true,
    //                 'message' => 'points added successfully',
    //             );
    //             $this->set_response($message, REST_Controller::HTTP_OK);
    //         }else{
    //             $message = array(
    //                 'status' => false,
    //                 'message' => 'user not found',
    //             );
    //             $this->set_response($message, REST_Controller::HTTP_OK);
    //         }
    //     }

    // }

    // public function get_total_consumed_trading_points($user_id, $exchange){

    //     exit;
    //     $points_key = $exchange == 'binance' ? "current_trading_points" : "current_trading_points_$exchange";

    //     $user_id = $this->mongo_db->mongoId($user_id);
    //     $connetct = $this->mongo_db->customQuery();
    //     $user = $connetct->users->find(['_id'=>$user_id]);
    //     $user = iterator_to_array($user);
        
    //     $total_points_consumed = 0;

    //     if(!empty($user)){
    //         $points_key = $exchange == 'binance' ? "current_trading_points" : "current_trading_points_$exchange";
    //         $trading_points_collection = $exchange == 'binance' ? "trading_points_history" : "trading_points_history_$exchange";

    //         $pipeline = [
    //             [
    //                 '$match' => [
    //                     'user_id' => (string) $user_id,
    //                     'action' => 'deduct',
    //                 ],
    //             ],
    //             [
    //                 '$group' => [
    //                     '_id' => null,
    //                     'total_consumed' => ['$sum' => '$points_consumed'],
    //                 ],
    //             ],
    //             [
    //                 '$project' => [
    //                     'total_consumed' => 1,
    //                     '_id' => 0,
    //                 ],
    //             ],
    //         ];
    //         $document = $this->mongo_db->customQuery();
    //         $atg_users = $document->$trading_points_collection->aggregate($pipeline);
    //         $atg_users = iterator_to_array($atg_users);

    //         if (!empty($atg_users)) {
    //             $total_points_consumed = $atg_users[0]['total_consumed'];
    //         }
    //     }

    //     return $total_points_consumed;

    // }

    // public function get_total_buy_trading_points($user_id, $exchange){

    //     return get_total_buy_trading_points_db($user_id, $exchange);

    //     // $data = ['user_id'=>$user_id];
    //     // $json = json_encode($data);

    //     // $curl = curl_init();
    //     // curl_setopt_array($curl, array(
    //     //     CURLOPT_URL => "https://users.digiebot.com/cronjob/GetUserTotalPoints",
    //     //     CURLOPT_RETURNTRANSFER => true,
    //     //     CURLOPT_ENCODING => "",
    //     //     CURLOPT_MAXREDIRS => 10,
    //     //     CURLOPT_TIMEOUT => 0,
    //     //     CURLOPT_FOLLOWLOCATION => true,
    //     //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     //     CURLOPT_CUSTOMREQUEST => "POST",
    //     //     CURLOPT_POSTFIELDS => $json,
    //     //     CURLOPT_HTTPHEADER => array(
    //     //         "Authorization: Basic cG9pbnRTdXBwbHk6NGU0NmQ5OWFjMjJhNGIwYWJlNTc2OGE3OGVlODdiOGM=",
    //     //         "Content-Type: application/json",
    //     //         "Cookie: ci_session=npsmcmv6uii63tumiusq5om7po5v00g2",
    //     //     ),
    //     // ));

    //     // $response = curl_exec($curl);
    //     // curl_close($curl);

    //     // $response = json_decode($response, true);

    //     // if(!empty($response['status']) && $response['status'] == 200){
    //     //     return $response['points']; 
    //     // }
    //     // return 0;
    // }
    
    // public function get_total_consumed_points_post(){

    //     $request = $this->post();
    //     $user_id = $request['user_id'];
    //     // $exchange = $request['exchange'];
    //     $exchange = 'binance';

    //     if (!empty($_SERVER["PHP_AUTH_USER"]) && $_SERVER["PHP_AUTH_USER"] == 'pointSupply' && !empty($_SERVER["PHP_AUTH_PW"]) && $_SERVER["PHP_AUTH_PW"] == md5('users.digiebot.com')) {

    //         if (empty($user_id) || empty($exchange)) {
    //             $message = array(
    //                 'status' => false,
    //                 'message' => 'user_id and exchange are required.',
    //             );
    //             $this->set_response($message, REST_Controller::HTTP_OK);
    //         } else {

    //             $total_points_consumed = $this->get_total_consumed_trading_points($user_id, $exchange);

    //             $message = array(
    //                 'status' => true,
    //                 'total_points_consumed' => $total_points_consumed,
    //                 'message' => 'data foun successfully',
    //             );
    //             $this->set_response($message, REST_Controller::HTTP_OK);
    //         }
    
    //     } else {
    //         $message = array(
    //             'status' => false,
    //             'message' => 'not authorized',
    //         );
    //         $this->set_response($message, REST_Controller::HTTP_OK);
    //     }

    // }

    public function update_user_application_mode_post(){

        $request = $this->post();
        $user_id = $request['user_id'];
        $application_mode = $request['application_mode'];

        if (!empty($_SERVER["PHP_AUTH_USER"]) && $_SERVER["PHP_AUTH_USER"] == 'pointSupply' && !empty($_SERVER["PHP_AUTH_PW"]) && $_SERVER["PHP_AUTH_PW"] == md5('users.digiebot.com')) {

            if (empty($user_id) || empty($application_mode)) {
                $message = array(
                    'status' => false,
                    'message' => 'user_id and application_mode are required.',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
            } else {

                $user_id = $this->mongo_db->mongoId($user_id);
                $connetct = $this->mongo_db->customQuery();
                $connetct->users->updateOne(['_id' => $user_id], ['$set' => ['application_mode' => $application_mode]]);

                if($application_mode == 'both'){
                    $message = "Application mode was set to $application_mode from users.digiebot.com";
                    $params = [
                        'user_id' => $user_id,
                        'type' => 'application_mode_updated',
                        'log' => $message,
                    ];
                    $req_arr = [
                        'req_type' => 'POST',
                        'req_params' => $params,
                        'req_url' => 'https://app.digiebot.com/admin/Api_calls/important_user_activity_logs',
                    ];
                    $resp = hitCurlRequest($req_arr);
                }

                $message = array(
                    'status' => true,
                    'message' => 'data foun successfully',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
    
        } else {
            $message = array(
                'status' => false,
                'message' => 'not authorized',
            );
            $this->set_response($message, REST_Controller::HTTP_OK);
        }

    }
    
    // public function update_user_buy_trading_points_post(){

    //     $request = $this->post();
    //     $user_id = $request['user_id'];
    //     $trading_points_buy = $request['trading_points_buy'] ?? '';

    //     if (!empty($_SERVER["PHP_AUTH_USER"]) && $_SERVER["PHP_AUTH_USER"] == 'pointSupply' && !empty($_SERVER["PHP_AUTH_PW"]) && $_SERVER["PHP_AUTH_PW"] == md5('users.digiebot.com')) {

    //         if (empty($user_id) || $trading_points_buy === '') {
    //             $message = array(
    //                 'status' => false,
    //                 'message' => 'user_id and application_mode are required.',
    //             );
    //             $this->set_response($message, REST_Controller::HTTP_OK);
    //         } else {

    //             $user_id = $this->mongo_db->mongoId($user_id);
    //             $db = $this->mongo_db->customQuery();
    //             $user_exists = $db->users->find(['_id' => $user_id]);
    //             $user_exists = iterator_to_array($user_exists);
    //             if(!empty($user_exists)){
                    
    //                 $db->users->updateOne(['_id' => $user_id], ['$set' => ['trading_points_buy' => $trading_points_buy]]);
                    
    //                 $message = array(
    //                     'status' => true,
    //                     'message' => 'trading points updated successfully',
    //                 );
    //             }else{

    //                 $message = array(
    //                     'status' => false,
    //                     'message' => (string) $user_id ." user_id not found",
    //                 );
    //             }

    //             $this->set_response($message, REST_Controller::HTTP_OK);
    //         }
    
    //     } else {
    //         $message = array(
    //             'status' => false,
    //             'message' => 'not authorized',
    //         );
    //         $this->set_response($message, REST_Controller::HTTP_OK);
    //     }

    // }

    // public function get_current_trading_points($user_id, $exchange){

    //     $total_points_buy = $this ->get_total_buy_trading_points($user_id, $exchange);
    //     $total_points_consumed = $this ->get_total_consumed_trading_points($user_id, $exchange);
    //     $curr_points = $total_points_buy - $total_points_consumed; 
    //     // echo "<pre>";
    //     // echo "$curr_points = $total_points_buy - $total_points_consumed";
    //     return (!is_nan($curr_points) ? $curr_points : 0);
    // }

    // public function get_user_current_trading_points_post(){

    //     $request = $this->post();
    //     $user_id = $request['user_id'];
    //     // $exchange = $request['exchange'];
    //     $exchange = 'binance';

    //     $received_Token = $this->input->get_request_header('Authorization');

    //     $received_Token = str_replace("Bearer ", "", $received_Token);
    //     $received_Token = str_replace("Token ", "", $received_Token);
    //     $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
    //     $tokenData = json_decode($tokenData);

    //     if($tokenData != false ){
        
    //         $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            

    //         if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

    //             if (empty($user_id) || empty($exchange)) {
    //                 $message = array(
    //                     'status' => false,
    //                     'message' => 'user_id and exchange are required.',
    //                 );
    //                 $this->set_response($message, REST_Controller::HTTP_OK);
    //             } else {
        
    //                 $current_trading_points = $this->get_current_trading_points($user_id, $exchange);
    //                 $message = array(
    //                     'status' => true,
    //                     'current_trading_points' => $current_trading_points,
    //                     'message' => 'data foun successfully',
    //                 );
    //                 $this->set_response($message, REST_Controller::HTTP_OK);
    //             }


    //         }else{

    //             $message = array(
    //                 'status' => 401,
    //                 'message' => 'This is not Valid user!!!',
    //             );
    //             http_response_code('401');
    //             echo json_encode($message);
    //         }
    //     }else{
            
    //         $message = array(
    //             'status' => 401,
    //             'message' => 'Token not Valid!!!',
    //         );

    //         http_response_code('401');
    //         echo json_encode($message);
    //     }

    // }

    public function important_user_activity_logs_post(){

        $request = $this->post();
        $user_id = $request['user_id'];
        $type = $request['type'];
        $log = $request['log'];

        $received_Token = $this->input->get_request_header('Authorization');

        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
        
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                if (empty($user_id) || empty($type) || empty($log)) {
                    $message = array(
                        'status' => false,
                        'message' => 'user_id, type and log are required.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_OK);
                } else {

                    $collection_name = 'user_important_activity_logs';

                    $created_date = date('Y-m-d G:i:s');
                    
                    $log = [
                        'user_id' => $user_id,
                        'type' => $type,
                        'log' => $log,
                        'created_date' => $this->mongo_db->converToMongodttime($created_date)
                    ];

                    $db = $this->mongo_db->customQuery();
                    $result = $db->$collection_name->insertOne($log);

                    $message = array(
                        'status' => true,
                        'message' => 'log added successfully',
                    );
                    $this->set_response($message, REST_Controller::HTTP_OK);
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

    /* ******** Waleeda G user update API   ****************** */

    public function update_user_data_post(){

        // error_reporting(E_ALL & ~E_NOTICE);
        // ini_set('display_errors', E_ALL & ~E_NOTICE);

        if (!empty($_SERVER["PHP_AUTH_USER"]) && $_SERVER["PHP_AUTH_USER"] == 'pointSupply' && !empty($_SERVER["PHP_AUTH_PW"]) && $_SERVER["PHP_AUTH_PW"] == md5('users.digiebot.com')) {
            $request = $this->post();
            $user_id = $request['user_id'];
            $exchange = $request['exchange'];
            $data = $request['data'];

            if(empty($user_id) || empty($data)){
                $message = array(
                    'status' => false,
                    'message' => 'user_id and data is required.',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);    
            }else{
                
                $set = $data['set'] ?? [];
                $unset = $data['unset'] ?? [];

                $restrict_keys = [
                    "trading_ip",
                    "timezone",
                    "profile_image",
                    "username",
                    "username_lowercase",
                    "email_address",
                    "phone_number",
                    "password",
                    "activation_code",
                    "status",
                    "user_role",
                    "user_soft_delete",
                    "special_role",
                    "google_auth",
                    "org_password",
                ];

                $newSetArr = [];
                if(!empty($set)){
                    $newSetArr = array_diff_key($set, array_flip($restrict_keys));
                }
                
                $newUnsetArr = [];
                if(!empty($unset)){
                    $newUnsetArr = array_diff_key($unset, array_flip($restrict_keys));
                }

                if(!empty($newSetArr) || !empty($newUnsetArr)){
                    
                    $keys_to_update = [
                        "maxBtcCustomPackage",
                        "maxDailTradeAbleBalancePercentage",
                        "maxUsdtCustomPackage",
                    ];
            
                    $collectionName = 'users';

                    $user_id = $this->mongo_db->mongoId($user_id);
                    $db = $this->mongo_db->customQuery();
                    $user = $db->$collectionName->find(['_id'=>$user_id]);
                    $user = iterator_to_array($user);
                    
                    if(!empty($user)){

                        $updateArr = [];
                        if(!empty($newSetArr)){
                            $updateArr['$set'] = $newSetArr;
                        }
                        if(!empty($newUnsetArr)){
                            $updateArr['$unset'] = $newUnsetArr;
                        }

                        //update user Arr
                        $db->$collectionName->updateOne(['_id'=>$user_id], $updateArr);
                        
                        $message = array(
                            'status' => true,
                            'message' => 'data updated successfully',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);

                    }else{
                        $message = array(
                            'status' => false,
                            'message' => 'User not found',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
                    }

                }else{
                    $message = array(
                        'status' => false,
                        'message' => 'no data to update',
                    );
                    $this->set_response($message, REST_Controller::HTTP_OK);
                }

            }
        } else {
            $message = array(
                'status' => false,
                'message' => 'not authorized',
            );
            $this->set_response($message, REST_Controller::HTTP_OK);
        }
        
    }

    public function update_username_post(){

        // error_reporting(E_ALL & ~E_NOTICE);
        // ini_set('display_errors', E_ALL & ~E_NOTICE);

        // if (!empty($_SERVER["PHP_AUTH_USER"]) && $_SERVER["PHP_AUTH_USER"] == 'pointSupply' && !empty($_SERVER["PHP_AUTH_PW"]) && $_SERVER["PHP_AUTH_PW"] == md5('users.digiebot.com')) {
            $request = $this->post();
            $user_id = $request['user_id'];
            $username = $request['username'];

            if(empty($user_id) || empty($username)){
                $message = array(
                    'status' => false,
                    'message' => 'user_id and username is required.',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);    
            }else{
                
                $collectionName = 'users';

                //check if username already exists
                $db = $this->mongo_db->customQuery();
                $user = $db->$collectionName->find(['$or'=>[['username' => ['$eq' => trim($username)] ], ['username_lowercase' => ['$eq' => strtolower(trim($username))] ] ]]);
                $user = iterator_to_array($user);
                if(!empty($user)){
                    $message = array(
                        'status' => false,
                        'data' => "username already attached to user_id ::: {$user[0]['_id']}    Requested userId $user_id",
                        'message' => 'username already exists.',
                    );
                    $this->set_response($message, REST_Controller::HTTP_OK);   
                }else{

                    $user_id = $this->mongo_db->mongoId($user_id);
                    $user = $db->$collectionName->find(['_id'=>$user_id]);
                    $user = iterator_to_array($user);
                    
                    if(!empty($user)){
    
                        $updateArr = [
                            '$set' => [
                                'username' => (trim($username)),
                                'username_lowercase' => strtolower(trim($username)),
                            ]
                        ];
                        //update user Arr
                        $db->$collectionName->updateOne(['_id'=>$user_id], $updateArr);
                        
                        $message = array(
                            'status' => true,
                            'data' => "username of  user_id ::: $user_id changed from {$user[0]['username']}   to   $username",
                            'message' => 'data updated successfully',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
    
                    }else{
                        $message = array(
                            'status' => false,
                            'data' => $user_id,
                            'message' => 'User not found',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
                    }

                }

            }

        // } else {
        //     $message = array(
        //         'status' => false,
        //         'message' => 'not authorized',
        //     );
        //     $this->set_response($message, REST_Controller::HTTP_OK);
        // }
        
    }

    //Send LTH Percentages to Hassan
    public function getUserLthBalancePercentage_post(){

        if($this->post('exchange')){
            $users[]    = $this->post('users');
            $exchange   = $this->post('exchange');

            $collection_name  = 'user_investment_'.$exchange;
            $lookup = [
                [
                    '$match' => [
                        'admin_id'  => ['$in' => $users[0]]
                    ]
                ],

                [
                    '$project' => [
                        '_id'                         =>  '$admin_id',
                        'total_lth_balance_percentage'=> ['$sum' => ['$costAvgBalanceBtcpercentageBinance', '$costAvgBalanceUsdtpercentageBinance', '$lthBalancePercentage'] ],
                        'exchange_enabled'            => '$exchange_enabled',
                        // 'username'    => '$username'
                    ]
                ],
                [
                    '$sort' => ['modified_time' => 1]
                ]
            ];

            $db =   $this->mongo_db->customQuery();
            $getData = $db->$collection_name->aggregate($lookup);
            $response = iterator_to_array($getData);

            $responseArray = [

                'data'      => $response,
                'status'    => 200
            ];

            $this->set_response($responseArray, REST_Controller::HTTP_CREATED);
        }else{


            $responseArray = [

                'message'   => 'Payload Missing',
                'status'    => 200
            ];

            $this->set_response($responseArray, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /* ******** End Waleeda G user update API   ************** */
        //manage_coins_post

    //manage_coins_post
    public function manage_coins_sheraz_post(){

        // header('Content-type: application/json');
        // header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        // header("Access-Control-Allow-Origin: *");
        // header("Access-Control-Allow-Headers: *");
        // header("Access-Control-Allow-Credentials: true");
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *");

        $request = $this->post();

        if(empty($request['user_id']) || empty($request['exchange'])){
            $message = array(
                'status' => FALSE,
                'message' => 'user_id, exchange can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
            $received_Token = $this->input->get_request_header('Authorization');
            $received_Token = str_replace("Bearer ", "", $received_Token);
            $received_Token = str_replace("Token ", "", $received_Token);
            $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
            $tokenData = json_decode($tokenData);
            $tokenData = true;
            if($tokenData != false ){
                $isUserValid = $this->Mod_jwt->isValidUser($request['user_id']);
                
                if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){
                
                    $user_id = $request['user_id'];
                    $exchange = $request['exchange'];
                    $application_mode = (!empty($request['application_mode'])?$request['application_mode'] : 'live');

                    $coins = $this->mod_api_calls->get_all_user_coins($user_id, $exchange);

                    $minQtyArr = get_min_quantity($exchange);

                    $temp_coin_balance_data = [];

                    $data = [];
                    if(!empty($coins)){

                        $btc_arr = array('coin_name' => 'Bitcoin',
                            'symbol' => "BTC",
                            'coin_name' => "bitcoin",
                            'coin_logo' => "btc11.jpg",
                            'coin_keywords' => '#btc,#bitcoin,#BTC',
                        );
            
                        array_push($coins, $btc_arr);
            
                        end($coins);
            
                        $last_key = key($coins);
                        $last_value = array_pop($coins);
                        $coins = array_merge(array($last_key => $last_value), $coins);
            
                        $currency = 'bitcoin';
                        $market = array();

                        $pricesArr = get_current_market_prices($exchange, []);

                        // echo "<pre>";
                        // echo "eschange $exchange <br>";
                        // print_r($pricesArr);
                        // die('ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss');

                        $BTCUSDT_price = $pricesArr['BTCUSDT'];
                        
                        // $BTCUSDT_price = $this->mod_api_calls->get_last_price('BTCUSDT', $exchange);

                        foreach ($coins as $coin) {
                            $coin_name = $coin['coin_name'];
                            $symbol = $coin['symbol'];
                            echo json_encode($coin_name);
                            $response_coin_id = (string)$coin['_id'];
                            $coin_id = $coin->_id;
                            $balance = $this->mod_api_calls->get_coin_balance($user_id, $symbol, $exchange);
            
                            $amount = null;
                            $amount_status = null;
                            if ($symbol != "BTC") {
                                $price = $pricesArr[$symbol];

                                // $price = $this->mod_api_calls->get_last_price($symbol, $exchange);
                                
                                $open_trades = $this->mod_api_calls->get_open_trades($user_id,$symbol, $exchange, $application_mode);
                                $open_trades_count = count($open_trades);
                                
                                $costAvg_trades = $this->mod_api_calls->get_costAvg_trades($user_id,$symbol, $exchange, $application_mode);
                                $costAvg_trades_count = count($costAvg_trades);
                                $open_trades = array_merge($open_trades, $costAvg_trades);
                                echo json_encode($coin_name);
                                echo json_encode($open_trades);
                                unset($costAvg_trades);

                                $change = $this->mod_api_calls->get_24_hour_price_change($symbol);
            
                                $per = $change['percentage'];
                                $per = number_format($per, 2);
                                $amount1 = $change['change'];

                                if ($per > 0) {
                                    $amount = '<div class="text-success badge-success-24H ">'.number_format($per, 2). '% </div>';
                                    $amount_status = 'green';
                                } elseif ($per < 0) {
                                    $amount = '<div class="text-danger badge-danger-24H ">'.number_format($per, 2) . '% </div>';
                                    $amount_status = 'red';
                                } else {
                                    $amount = '<div class="text-default badge-success-24H ">' . number_format($per, 2) . '% </div>';
                                    $amount_status = 'dark-grey';
                                }
                            } else {
                                $price = 1;
                                $trade = 'N/A';
                            }
                            

                            $coin_balance = $balance['coin_balance'];
                            $total_trade_qty = 0;
                            $balance_error = false;
                            $usd_balance = 0;
                            $balance = 0; 
                            $balance_usd_worth = 0;
                            $comitted_balance = 0;
                            $comitted_balance_usd_worth = 0;
                            $extra_balance = 0;
                            $extra_balance_usd_worth = 0;
                            $required_balance = 0;
                            $required_balance_usd_worth = 0;

                            if (!empty($open_trades)) {
                                foreach ($open_trades as $tradee) {
                                    if(isset($tradee['cost_avg_array'])){
                                        if(empty($tradee['quantity_all'])){
                                            foreach($tradee['cost_avg_array'] as $value_cost_avg_array){
                                                if($value_cost_avg_array['order_sold'] != 'yes'){
                                                    $total_trade_qty += $value_cost_avg_array['filledQtyBuy'];        
                                                }
                                            }
                                        }else{
                                            $total_trade_qty += (float) isset($tradee['quantity_all'])?$tradee['quantity_all']:$tradee['quantity'];        
                                        }
                                    }else{
                                        $total_trade_qty += (float) $tradee['quantity'];
                                    }
                                }
                            }

                            $tarr = explode('USDT', $symbol);
                            if (isset($tarr[1]) && $tarr[1] == '') {
                                 //echo "\r\n USDT coin";
                                if($symbol == 'BTCUSDT'){
                                    $usd_balance =  $coin_balance;

                                    $coin_balance = $coin_balance * (1/$price);

                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance * $price;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance * $price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance * $price;
                                    }

                                }else{
                                    $usd_balance =  $coin_balance*$price;

                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance * $price;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance * $price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance * $price;
                                    }
                                }

                                $convertamount = round($price, 5);
                            
                            } else {
                                if($symbol == "BTC"){
                                    echo json_encode($coin_name);
                                    echo json_encode('i am in btc wali cheaz');
                                    echo json_encode($total_trade_qty);
                                    $usd_balance =  $coin_balance*$BTCUSDT_price;
                                    $convertamount = round($BTCUSDT_price, 5);

                                    $balance_error = false;
                                    $comitted_balance = 0;
                                    $comitted_balance_usd_worth = 0;
                                    //required
                                    if ($balance_error) {
                                        $required_balance = 0;
                                        $required_balance_usd_worth = 0;
                                    } else { //extra
                                        $extra_balance = 0;
                                        $extra_balance_usd_worth = 0;
                                    }
                                }else{

                                    $convertamount = round($price*$BTCUSDT_price, 5);
                                    
                                    $usd_balance =  $coin_balance*$price*$BTCUSDT_price;

                                    $balance_error = ($total_trade_qty > $coin_balance) ? true : false;
                                    $comitted_balance = $total_trade_qty;
                                    $comitted_balance_usd_worth = $comitted_balance*$price*$BTCUSDT_price;

                                    //required
                                    if ($balance_error) {
                                        $required_balance = $total_trade_qty - $coin_balance;
                                        $required_balance_usd_worth = $required_balance*$price*$BTCUSDT_price;
                                    } else { //extra
                                        $extra_balance = $coin_balance - $total_trade_qty;
                                        $extra_balance_usd_worth = $extra_balance*$price*$BTCUSDT_price;
                                    }

                                }
                            }
                            //echo json_encode($extra_balance);
                             echo json_encode($coin_name);
                             echo json_encode('oo i am in final stage checking commited');
                            echo json_encode($comitted_balance);
                            $balance_usd_worth = $usd_balance;
                            $coin_logo = $this->get_coin_image_new($symbol, $exchange);
                            $base64_logo = '';
                            
                            $coinData1 = array(
                                'symbol' => $symbol,
                                'coin_name' => $coin_name,
                                'logo' => $base64_logo,
                                'coin_logo' => $coin_logo, 
                                'change' => $amount,
                                'change_status' => $amount_status,
                                'last_price' => $price,
                                'usd_amount' => $convertamount,
                                'trade' => $open_trades_count,
                                'costAvgTrade' => $costAvg_trades_count,
                                'coin_id' => $response_coin_id,
                                'balance' => num($coin_balance),
                                'balance_usd_worth' => num($balance_usd_worth),
                                'usd_balance' => num($usd_balance),
                                'balance_error' => $balance_error,
                                'comitted_balance' => num($comitted_balance),
                                'comitted_balance_usd_worth' => num($comitted_balance_usd_worth),
                                'base_currency_comitted_balance' => num($comitted_balance),
                                'base_currency_comitted_balance_usd_worth' => num($comitted_balance_usd_worth),
                            );

                            if ($balance_error) {
                                $coinData1['required_balance'] = num($required_balance);
                                $coinData1['required_balance_usd_worth'] = num($required_balance_usd_worth);
                            } else { //extra
                                $coinData1['extra_balance'] = num($extra_balance);
                                $coinData1['extra_balance_usd_worth'] = num($extra_balance_usd_worth);
                            }
                            //save coin according to pair
                            $temp_coin_balance_data = [];
                            $sym = $this->get_sym($symbol);
                            if(!empty($sym)){
                                $newArr[$sym][] = $coinData1;
                            }
                            
                            $market[] = $coinData1;

                        }

                        // die('-aaaaaaaaaaaaa');

                        // echo "<pre>";
                        foreach ($market as $key => $value) {

                            //save coin according to pair
                            $temp_coin_balance_data = [];
                            $sym = $this->get_sym($value['symbol']);
                            $currPairsArr = $newArr[$sym];
                            
                            if ($value['symbol'] == 'BTC') {

                                $market[$key]['balance'] = num($market[$key]['balance']);
                                $market[$key]['balance_usd_worth'] = number_format((float) $market[$key]['balance_usd_worth'], 2);
                                $market[$key]['extra_balance'] = num($market[$key]['extra_balance']);
                                $market[$key]['extra_balance_usd_worth'] = number_format((float) $market[$key]['extra_balance_usd_worth'], 2);
                                $market[$key]['comitted_balance'] = num($market[$key]['comitted_balance']);
                                $market[$key]['comitted_balance_usd_worth'] = number_format((float) $market[$key]['comitted_balance_usd_worth'], 2);

                                continue;
                            } else if ($value['symbol'] == 'BTCUSDT') {
                                $total_comitted_balance = $currPairsArr[0]['comitted_balance'];
                                $total_comitted_balance = $total_comitted_balance;

                                $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'];
                                $total_comitted_balance_usd_worth = $total_comitted_balance_usd_worth;

                                $total_balance = $currPairsArr[0]['balance'];
                                $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];

                                if ($total_balance < $total_comitted_balance) {

                                    $total_required = $total_comitted_balance - $total_balance;
                                    $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;

                                    $market[$key]['required_balance'] = $total_required;
                                    $market[$key]['required_balance_usd_worth'] = $total_required_usd_worth;

                                } else if ($total_balance > $total_comitted_balance) {

                                    $total_extra = $total_balance - $total_comitted_balance;
                                    $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;

                                    $market[$key]['extra_balance'] = $total_extra;
                                    $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;

                                }

                            } else {

                                if (count($currPairsArr) > 1) {

                                    // if($value['symbol'] == 'XRPBTC' || $value['symbol'] == 'XRPUSDT'){
                                    //     print_r($currPairsArr);
                                    // }

                                    $total_comitted_balance = $currPairsArr[0]['comitted_balance'] + $currPairsArr[1]['comitted_balance'];
                                    $total_comitted_balance = $total_comitted_balance;

                                    $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'] + $currPairsArr[1]['comitted_balance_usd_worth'];
                                    $total_comitted_balance_usd_worth = $total_comitted_balance_usd_worth;

                                    $total_balance = $currPairsArr[0]['balance'];
                                    $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];

                                    if ($total_balance < $total_comitted_balance) {

                                        $total_required = $total_comitted_balance - $total_balance;
                                        $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;

                                        $market[$key]['required_balance'] = $total_required;
                                        $market[$key]['required_balance_usd_worth'] = $total_required_usd_worth;

                                    } else if ($total_balance >= $total_comitted_balance) {

                                        $total_extra = $total_balance - $total_comitted_balance;
                                        $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;

                                        $market[$key]['extra_balance'] = $total_extra;
                                        $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;

                                    }

                                    $market[$key]['comitted_balance'] = $total_comitted_balance;
                                    $market[$key]['comitted_balance_usd_worth'] = $total_comitted_balance_usd_worth; 
                                }else{
                                    $total_comitted_balance = $currPairsArr[0]['comitted_balance'];
                                    $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'];

                                    $total_balance = $currPairsArr[0]['balance'];
                                    $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];

                                    if ($total_balance < $total_comitted_balance) {

                                        $total_required = $total_comitted_balance - $total_balance;
                                        $total_required_usd_worth = $total_comitted_balance_usd_worth - $total_balance_usd_worth;

                                        $market[$key]['required_balance'] = $total_required;
                                        $market[$key]['required_balance_usd_worth'] = $total_required_usd_worth;

                                    } else if ($total_balance > $total_comitted_balance) {

                                        $total_extra = $total_balance - $total_comitted_balance;
                                        $total_extra_usd_worth = $total_balance_usd_worth - $total_comitted_balance_usd_worth;

                                        $market[$key]['extra_balance'] = $total_extra;
                                        $market[$key]['extra_balance_usd_worth'] = $total_extra_usd_worth;

                                    }

                                    $market[$key]['comitted_balance'] = $total_comitted_balance;
                                    $market[$key]['comitted_balance_usd_worth'] = $total_comitted_balance_usd_worth;

                                }
                            }
                            
                            $market[$key]['balance'] = num($market[$key]['balance']);
                            $market[$key]['balance_usd_worth'] = number_format((float) $market[$key]['balance_usd_worth'], 2);
                            
                            if(isset($market[$key]['required_balance']) && $market[$key]['required_balance'] != 0){
                                $market[$key]['required_balance'] = num($market[$key]['required_balance']);
                                $market[$key]['required_balance_usd_worth'] = number_format((float) $market[$key]['required_balance_usd_worth'], 2);
                            }else{
                                unset($market[$key]['required_balance']);
                                unset($market[$key]['required_balance_usd_worth']);
                            }
                            
                            if(isset($market[$key]['required_balance']) && $market[$key]['required_balance'] != 0 && isset($market[$key]['extra_balance']) && $market[$key]['extra_balance'] != 0){
                                unset($market[$key]['extra_balance']);
                                unset($market[$key]['extra_balance_usd_worth']);
                            }else if(isset($market[$key]['extra_balance']) && $market[$key]['extra_balance'] != 0){
                                $market[$key]['extra_balance'] = num($market[$key]['extra_balance']);
                                $market[$key]['extra_balance_usd_worth'] = number_format((float) $market[$key]['extra_balance_usd_worth'], 2);
                            }else{
                                unset($market[$key]['extra_balance']);
                                unset($market[$key]['extra_balance_usd_worth']);
                            }

                            if(isset($market[$key]['comitted_balance'])){
                                $market[$key]['comitted_balance'] = num($market[$key]['comitted_balance']);
                                $market[$key]['comitted_balance_usd_worth'] = number_format((float) $market[$key]['comitted_balance_usd_worth'], 2);
                            }

                            $market[$key]['balance_error'] = $market[$key]['comitted_balance_usd_worth'] == $market[$key]['balance_usd_worth'] ? false : $market[$key]['balance_error'];
                            
                            // echo "<pre>";
                            // echo "symbol ".$market[$key]['symbol']."\r\n";
                            // echo "balance ".$market[$key]['balance']."\r\n";
                            // echo "balance_usd_worth ".$market[$key]['balance_usd_worth']."\r\n";
                            // echo "comitted_balance ".$market[$key]['comitted_balance']."\r\n";
                            // echo "comitted_balance_usd_worth ".$market[$key]['comitted_balance_usd_worth']."\r\n";
                            // echo "required_balance ".$market[$key]['required_balance']."\r\n";
                            // echo "required_balance_usd_worth ".$market[$key]['required_balance_usd_worth']."\r\n";
                            // echo "extra_balance ".$market[$key]['extra_balance']."\r\n";
                            // echo "extra_balance_usd_worth ".$market[$key]['extra_balance_usd_worth']."\r\n";
            
                            // echo "--------------------------------------------------------------------- \r\n";


                            $symbol111 = $market[$key]['symbol']; 
                            $market[$key]['display_sell_btn'] = (!empty($market[$key]['extra_balance']) && !empty($minQtyArr[$symbol111]) && (float) $market[$key]['extra_balance'] >= $minQtyArr[$symbol111]['min_qty']) ? true : false;
                            
                            // $market[$key]['display_buy_btn'] = (!empty($market[$key]['required_balance'])  && !empty($minQtyArr[$symbol111]) && (float) $market[$key]['required_balance'] >= $minQtyArr[$symbol111]['min_qty']) ? true : false;
                            
                        }


                        $data['coin_market'] = $market;
                    }
                    
                    if(!empty($data)){
            
                        $message = array(
                            'status' => TRUE,
                            'data' => $data['coin_market'],
                            // 'minQtyArr' => $minQtyArr,
                            'message' => 'Data found scuccessfully',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
            
                    } else {
            
                        $message = array(
                            'status' => FALSE,
                            'message' => 'Data not found',
                        );
                        $this->set_response($message, REST_Controller::HTTP_OK);
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

    }//end manage_coins_post
    public function get_coin_price_post() {
        $request = $this->post();
        //echo json_encode($request);
        $symbol = isset($request['coin_symbol'])?array($request['coin_symbol']):array();
        $exchange = isset($request['exchange'])?$request['exchange']:'binance';
        $result = get_current_market_prices($exchange,$symbol);
            if(!empty($result)){
                if(!empty($symbol)){
                    $message = array(
                        'status' => TRUE,
                        'message' => 'Scuccessful',
                        'coin_symbol' =>$symbol[0],
                        'market_price'=>$result[$symbol[0]]
                    );
                }else{
                    $message = array(
                        'status' => TRUE,
                        'message' => 'Scuccessful',
                        'market_price'=>$result
                    );
                }
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            } else {
                $message = array(
                    'status' => FALSE,
                    'message' => 'Something Went Wrong',
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
    } // end resetPassword_post
    //commenting this code and adding new one 
    // public function get_trade_size_app_post(){

    //     // header('Content-type: application/json');
    //     // header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    //     // header("Access-Control-Allow-Origin: *");
    //     // header("Access-Control-Allow-Headers: *");
    //     // header("Access-Control-Allow-Credentials: true");
    //     header('Content-type: application/json');
    //     header("Access-Control-Allow-Origin: *");
    //     header("Access-Control-Allow-Methods: POST");
    //     header("Access-Control-Allow-Headers: *");

    //     $request = $this->post();

    //     if(empty($request['user_id']) || empty($request['exchange'])){
    //         $message = array(
    //             'status' => FALSE,
    //             'message' => 'user id or exchange can not be empty.',
    //         );
    //         $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
    //     }else{
    //         $received_Token = $this->input->get_request_header('Authorization');
    //         $received_Token = str_replace("Bearer ", "", $received_Token);
    //         $received_Token = str_replace("Token ", "", $received_Token);
    //         $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
    //         $tokenData = json_decode($tokenData,true);
    //         //echo '<pre>';print_r($tokenData);exit;
    //         // $tokenData = true;
    //         if($tokenData['id'] == $request['user_id']){
    //             $user_id = $request['user_id'];
    //             $exchange = $request['exchange'];
    //             $allocated_balance = $request['allocated_balance'];
    //             $base_currency = $request['base_currency'];
    //             $daily_balance_percentage_approach = $request['daily_balance_percentage_approach'];
    //             $db = $this->mongo_db->customQuery();
    //             $user_data = $db->users->findOne(['_id'=>$this->mongo_db->mongoId($user_id)]);
    //             if($exchange == 'binance'){
    //                 $collection_atg = 'auto_trade_settings';
    //             }elseif($exchange == 'kraken'){
    //                 $collection_atg = 'auto_trade_settings_kraken';
    //             }elseif ($exchange == 'okex') {
    //                 $collection_atg = 'auto_trade_settings_okex';
    //             }elseif ($exchange == 'dg') {
    //                 $collection_atg = 'auto_trade_settings_dg';
    //             }
    //             $user_array = iterator_to_array($user_data);
    //             $user_data_atg = $db->$collection_atg->findOne(['user_id'=>$user_id,'application_mode'=>'live']);
    //             // $user_array_atg = iterator_to_array($user_data);
    //             $package = isset($user_array['signup_package_selected'])?$user_array['signup_package_selected']:'';
    //             if($package == '' || $package == 'D1'){
    //                 $no_of_coins = 7;
    //             }else if($package == 'S1'){
    //                 $no_of_coins = 14;
    //             }else if($package == 'W1'){
    //                 if($exchange == 'binance' || $exchange == 'dg'){
    //                     if($base_currency == 'BTC'){
    //                         $no_of_coins = 22;
    //                     }else{
    //                         $no_of_coins = 12;
    //                     }
    //                 }else{
    //                    if($base_currency == 'BTC'){
    //                         $no_of_coins = 17;
    //                     }else{
    //                         $no_of_coins = 8;
    //                     }
    //                 }
    //             }
    //             $trade_side = (int)$allocated_balance/((int)$no_of_coins * (float)$daily_balance_percentage_approach);
    //             $resp['success'] = true;
    //             $resp['code'] = 200;
    //             $resp['trade_size'] = "$trade_side";
    //             echo json_encode($resp);
    //         }else{
    //             $resp['success'] = false;
    //             $resp['code'] = 400;
    //             $resp['data'] = 'Token Data not matched';
    //             echo json_encode($resp);
    //         }
    //     }
    // }

    public function get_user_balance_info($user_id,$exchange){
      $token = $this->Mod_jwt->custom_token_bearer($user_id);
      $curl = curl_init();
      $post_values = http_build_query(array('exchange' => $exchange));
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://digiapis.digiebot.com/apiEndPoint/get_balance_stats/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $post_values,
        CURLOPT_HTTPHEADER => array(
          'Authorization:'.$token,
        ),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      $response = json_decode($response,true);
      if($response['status'] == 1){
        return $response['data'];
      }else{
        return 0;
      }
    }

    public function get_trade_size_app_post() {
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *");
    
        $request = $this->post();
    
        if (empty($request['user_id']) || empty($request['exchange'])) {
            $message = array(
                'status' => FALSE,
                'message' => 'user id or exchange cannot be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        } else {
            $received_Token = $this->input->get_request_header('Authorization');
            $received_Token = str_replace("Bearer ", "", $received_Token);
            $received_Token = str_replace("Token ", "", $received_Token);
            $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
            $tokenData = json_decode($tokenData, true);
    
            if ($tokenData['id'] == $request['user_id']) {
                $user_id = $this->mongo_db->mongoId($request['user_id']);
                if (!preg_match('/^[0-9a-f]{24}$/', $user_id)) {
       
                    echo "Invalid MongoDB ObjectID: $user_id\n";
                    exit;
                }
                $exchange = $this->sanitize_and_validate_exchange($request['exchange']);
                // $exchange = $request['exchange'];
                $allocated_balance = $request['allocated_balance'];
                $base_currency = $request['base_currency'];
                $daily_balance_percentage_approach = $request['daily_balance_percentage_approach'];
    
                $db = $this->mongo_db->customQuery();  
                $user_data = $db->users->findOne(['_id' => $user_id]);
                
                $collection_atg = $this->get_collection_name($exchange);
                
                $user_data_atg = $db->$collection_atg->findOne([
                    'user_id' => $user_id,
                    'application_mode' => 'live'
                ]);

                $atg_object = $db->$collection_atg->find(['user_id'=> (string)$user_id,'application_mode'=>'live']);
                $atg_array = iterator_to_array($atg_object); // getting atg settings of a user
             
                $user_allocated_balance_btc = convert_btc_to_usdt($atg_array[0]['step_4']['allocatedBTC']); // allocated // usdt
                $user_allocated_balance_usdt = $atg_array[0]['step_4']['allocatedUSDT']; // allocated usdt

                $balance_info = $this->get_user_balance_info((string)$user_id,$exchange);

                if($base_currency == 'BTC'){
                 
                    $remaining_balance =$user_allocated_balance_btc - convert_btc_to_usdt($balance_info['used_btc_balance']); 
      
                }else{
                    
                    $remaining_balance = $user_allocated_balance_usdt - $balance_info['used_usdt_balance']; 
                }

    
                $package = isset($user_data['signup_package_selected']) ? $user_data['signup_package_selected'] : 'D1';
                if ($base_currency == 'BTC') {
                    $no_of_coins = $this->get_number_of_coins_btc($package, $exchange, $base_currency, $allocated_balance);
                }
                if ($base_currency == 'USDT') {
                    $no_of_coins = $this->get_number_of_coins_usdt($package, $exchange, $base_currency, $allocated_balance);
                }
                $trade_side = (int)$allocated_balance / ((int)$no_of_coins * (float)$daily_balance_percentage_approach);
    
                // if ($trade_side > $remaining_balance) {
                //     $trade_side = 20;
                // }

                $resp['success'] = true;
                $resp['code'] = 200;
                $resp['trade_size'] = "$trade_side";
                echo json_encode($resp);
            } else {
                $resp['success'] = false;
                $resp['code'] = 400;
                $resp['data'] = 'Token Data not matched';
                echo json_encode($resp);
            }
        }
    }
    
    private function get_collection_name($exchange) {
        switch ($exchange) {
            case 'binance':
                return 'auto_trade_settings';
            case 'kraken':
                return 'auto_trade_settings_kraken';
            case 'okex':
                return 'auto_trade_settings_okex';
            case 'dg':
                return 'auto_trade_settings_dg';
            default:
                return '';
        }
    }

    private function get_number_of_coins_btc($package, $exchange, $base_currency, $user_allocated_balance_btc){

        // echo '<pre>package ::'; print_r($package);
        // echo '<pre>base_currency :: '; print_r($base_currency);
        // echo '<pre>user_allocated_balance_btc :: '; print_r($user_allocated_balance_btc);
        // // echo '<pre>'; print_r();
        // exit;

        $allowed_coins_btc = 0;
        // echo '<pre>user_allocated_balance_btc '; print_r($user_allocated_balance_btc); exit;
        if($package == '' || $package == 'D1'){
            if ($base_currency == 'BTC') {
                if((int)$user_allocated_balance_btc > 0 && (int)$user_allocated_balance_btc <= 1000){ // 3 coins
                    $allowed_coins_btc = 3;
                }elseif((int)$user_allocated_balance_btc > 1000 && (int)$user_allocated_balance_btc <= 2000){ // 4 coins
                    $allowed_coins_btc = 4;
                }elseif((int)$user_allocated_balance_btc > 2000 && (int)$user_allocated_balance_btc <= 5000){ // 6 coins
                    $allowed_coins_btc = 6;
                }elseif((int)$user_allocated_balance_btc > 5000 && (int)$user_allocated_balance_btc <= 10000){ // 6 coins
                    $allowed_coins_btc = 6;
                }elseif((int)$user_allocated_balance_btc > 10000 ){ // 6 coins
                    $allowed_coins_btc = 6;
                }
            }

        }else if($package == 'S1'){
            if ($base_currency == 'BTC') {
                if((int)$user_allocated_balance_btc > 0 && (int)$user_allocated_balance_btc <= 1000){ // 3 coins
                    $allowed_coins_btc = 3;
                }elseif((int)$user_allocated_balance_btc > 1000 && (int)$user_allocated_balance_btc <= 2000){ // 4 coins
                    $allowed_coins_btc = 4;
                }elseif((int)$user_allocated_balance_btc > 2000 && (int)$user_allocated_balance_btc <= 5000){ // 6 coins
                    $allowed_coins_btc = 6;
                }elseif((int)$user_allocated_balance_btc > 5000 && (int)$user_allocated_balance_btc <= 10000){ // 8 coins
                    $allowed_coins_btc = 8;
                }elseif((int)$user_allocated_balance_btc > 10000 ){ // 10 coins
                    $allowed_coins_btc = 10;
                }
            }

        }else if($package == 'W1'){
            if($exchange == 'binance' || $exchange == 'dg'){
                if($base_currency == 'BTC'){
                    if((int)$user_allocated_balance_btc > 0 && (int)$user_allocated_balance_btc <= 1000){ // 3 coins
                        $allowed_coins_btc = 3;
                    }elseif((int)$user_allocated_balance_btc > 1000 && (int)$user_allocated_balance_btc <= 2000){ // 4 coins
                        $allowed_coins_btc = 4;
                    }elseif((int)$user_allocated_balance_btc > 2000 && (int)$user_allocated_balance_btc <= 5000){ // 6 coins
                        $allowed_coins_btc = 6;
                    }elseif((int)$user_allocated_balance_btc > 5000 && (int)$user_allocated_balance_btc <= 10000){ // 8 coins
                        $allowed_coins_btc = 8;
                    }elseif((int)$user_allocated_balance_btc > 10000 ){ // 13 coins
                        $allowed_coins_btc = 13;
                    }
                    // $no_of_coins = 22;
                }
            }else{
               if($base_currency == 'BTC'){
                    if((int)$user_allocated_balance_btc > 0 && (int)$user_allocated_balance_btc <= 1000){ // 3 coins
                        $allowed_coins_btc = 3;
                    }elseif((int)$user_allocated_balance_btc > 1000 && (int)$user_allocated_balance_btc <= 2000){ // 4 coins
                        $allowed_coins_btc = 4;
                    }elseif((int)$user_allocated_balance_btc > 2000 && (int)$user_allocated_balance_btc <= 5000){ // 6 coins
                        $allowed_coins_btc = 6;
                    }elseif((int)$user_allocated_balance_btc > 5000 && (int)$user_allocated_balance_btc <= 10000){ // 8 coins
                        $allowed_coins_btc = 8;
                    }elseif((int)$user_allocated_balance_btc > 10000 ){ // 13 coins
                        $allowed_coins_btc = 13;
                    }
                    // $no_of_coins = 17;
                }
            }
        }
        return $allowed_coins_btc;
    }

    private function get_number_of_coins_usdt($package, $exchange, $base_currency, $user_allocated_balance_usdt){
        $allowed_coins_usdt = 0;

        if($package == '' || $package == 'D1'){
            
            if ($base_currency == "USDT") {
                if((int)$user_allocated_balance_usdt > 0 && (int)$user_allocated_balance_usdt <= 1000){ // 3 coins
                    $allowed_coins_usdt = 3;
                }elseif((int)$user_allocated_balance_usdt > 1000 && (int)$user_allocated_balance_usdt <= 2000){ // 4 coins
                    $allowed_coins_usdt = 4;
                }elseif((int)$user_allocated_balance_usdt > 2000 && (int)$user_allocated_balance_usdt <= 5000){ // 6 coins
                    $allowed_coins_usdt = 6;
                }elseif((int)$user_allocated_balance_usdt > 5000 && (int)$user_allocated_balance_usdt <= 10000){ // 6 coins
                    $allowed_coins_usdt = 6;
                }elseif((int)$user_allocated_balance_usdt > 10000 ){ // 6 coins
                    $allowed_coins_usdt = 6;
                }
            }

        }else if($package == 'S1'){
            if ($base_currency == 'USDT') {
                if($exchange == 'binance' || $exchange == 'dg'){
                    if((int)$user_allocated_balance_usdt > 0 && (int)$user_allocated_balance_usdt <= 1000){ // 3 coins
                        $allowed_coins_usdt = 3;
                    }elseif((int)$user_allocated_balance_usdt > 1000 && (int)$user_allocated_balance_usdt <= 2000){ // 4 coins
                        $allowed_coins_usdt = 4;
                    }elseif((int)$user_allocated_balance_usdt > 2000 && (int)$user_allocated_balance_usdt <= 5000){ // 6 coins
                        $allowed_coins_usdt = 6;
                    }elseif((int)$user_allocated_balance_usdt > 5000 && (int)$user_allocated_balance_usdt <= 10000){ // 8 coins
                        $allowed_coins_usdt = 8;
                    }elseif((int)$user_allocated_balance_usdt > 10000 ){ // 6 coins
                        $allowed_coins_usdt = 10;
                    }
                }else{
                    if((int)$user_allocated_balance_usdt > 0 && (int)$user_allocated_balance_usdt <= 1000){ // 3 coins
                        $allowed_coins_usdt = 3;
                    }elseif((int)$user_allocated_balance_usdt > 1000 && (int)$user_allocated_balance_usdt <= 2000){ // 4 coins
                        $allowed_coins_usdt = 4;
                    }elseif((int)$user_allocated_balance_usdt > 2000 && (int)$user_allocated_balance_usdt <= 5000){ // 6 coins
                        $allowed_coins_usdt = 6;
                    }elseif((int)$user_allocated_balance_usdt > 5000 && (int)$user_allocated_balance_usdt <= 10000){ // 8 coins
                        $allowed_coins_usdt = 8;
                    }elseif((int)$user_allocated_balance_usdt > 10000 ){ // 6 coins
                        $allowed_coins_usdt = 8;
                    }
                }
            }

        }else if($package == 'W1'){
            if($exchange == 'binance' || $exchange == 'dg'){
                if($base_currency == 'USDT'){
                    if((int)$user_allocated_balance_usdt > 0 && (int)$user_allocated_balance_usdt <= 1000){ // 3 coins
                        $allowed_coins_usdt = 3;
                    }elseif((int)$user_allocated_balance_usdt > 1000 && (int)$user_allocated_balance_usdt <= 2000){ // 4 coins
                        $allowed_coins_usdt = 4;
                    }elseif((int)$user_allocated_balance_usdt > 2000 && (int)$user_allocated_balance_usdt <= 5000){ // 6 coins
                        $allowed_coins_usdt = 6;
                    }elseif((int)$user_allocated_balance_usdt > 5000 && (int)$user_allocated_balance_usdt <= 10000){ // 8 coins
                        $allowed_coins_usdt = 8;
                    }elseif((int)$user_allocated_balance_usdt > 10000 ){ // 13 coins
                        $allowed_coins_usdt = 13;
                    }
                    // $no_of_coins = 22;
                }
            }else{
             
                if((int)$user_allocated_balance_usdt > 0 && (int)$user_allocated_balance_usdt <= 1000){ // 3 coins
                    $allowed_coins_usdt = 3;
                }elseif((int)$user_allocated_balance_usdt > 1000 && (int)$user_allocated_balance_usdt <= 2000){ // 4 coins
                    $allowed_coins_usdt = 4;
                }elseif((int)$user_allocated_balance_usdt > 2000 && (int)$user_allocated_balance_usdt <= 5000){ // 6 coins
                    $allowed_coins_usdt = 6;
                }elseif((int)$user_allocated_balance_usdt > 5000 && (int)$user_allocated_balance_usdt <= 10000){ // 8 coins
                    $allowed_coins_usdt = 8;
                }elseif((int)$user_allocated_balance_usdt > 10000 ){ // 13 coins
                    $allowed_coins_usdt = 13;
                }
                // $no_of_coins = 8;
               
            }
        }
        return $allowed_coins_usdt;
    }
    
    public function get_accumulation_post() {
        $request = $this->post();
        //echo json_encode($request);
        $user_id = isset($request['user_id'])?$request['user_id']:'';
        $exchange = isset($request['exchange'])?$request['exchange']:'binance';
        if($user_id == ''|| empty($user_id)){
            $message = array(
                        'status' => False,
                        'message' => 'User id cannot be empty',
                        //'data'=>$result
                        'code' => 400,
                    );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }
        $result = get_user_accumulations($exchange,$user_id);
            if(!empty($result)){
                    $message = array(
                        'status' => TRUE,
                        'message' => 'Successfully found accumulations',
                        'statusCode'=>200,
                        'data'=>$result
                    );
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }else{
                $message = array(
                    'status' => FALSE,
                    'message' => 'Something Went Wrong',
                    'statusCode'=>200,
                    'data'=>''
                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
    } // end resetPassword_post    
    public function profile_completion_info_post(){
       // $inputJSON = $this->input->raw_input_stream;
       
        //$user_id = json_decode($request['user_id'], true);
        
        // if ($this->mongo_db->isValid($user_id)) {
        //     echo 'yes valid userID'; exit;
        // } else {
        //     echo 'not valid userID'; exit;
        // }
        // echo print_r($postData); exit; 
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *");
        $request = $this->input->raw_input_stream;
        //$this->set_response($request, REST_Controller::HTTP_CREATED);
        //echo json_encode($request);exit;
        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        // echo json_encode($received_Token); exit;
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);
        //$this->set_response($tokenData, REST_Controller::HTTP_CREATED);
        // echo json_encode($tokenData); exit;
        if($tokenData != false ){
           
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            // echo json_encode($isUserValid);exit;
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){
               
                $db = $this->mongo_db->customQuery();
                //check user api key valid in exchange binance
                $user_data = $db->users->findOne(['_id'=>$this->mongo_db->mongoId($request)]);
                $user_array_binance = iterator_to_array($user_data);
                //user_array_binance
               
                if ($user_array_binance['is_api_key_valid'] == 'yes') {
                    $data['api_keys_added'] = 'yes';
                    $data['ip_whitelisted'] = 'yes';
                }
                // echo json_encode($user_array_binance);exit;
                //check user api key valid in exchange Kraken
                $user_data_kr = $db->kraken_credentials->findOne(['user_id'=>$request]);
                $user_array_kraken = iterator_to_array($user_data_kr);
                if ($user_array_kraken['is_api_key_valid'] == 'yes') {
                    $data['api_keys_added_kraken'] = 'yes';
                    $data['ip_whitelisted_kraken'] = 'yes';
                    $data['kraken_user'] = 'yes';
                }

                //check user order history binance
                $user_trade_data = $db->user_trade_history->find(['user_id'=>$request]);
                $user_trade_data_binance = iterator_to_array($user_trade_data);
                if (!empty($user_trade_data_binance) && $user_trade_data_binance > 0) {
                    $data['user_trade_history'] = 'yes';
                }

                // // for active coins binance
                $coins_search['pick_parent'] = 'yes';
                $coins_search['status'] = ['$ne' => 'cancelled'];
                $coins_search['application_mode'] = 'live';
                $coins_search['admin_id'] = $request;
                $coins_search['trigger_type'] = 'barrier_percentile_trigger';

                $coins_search_data = $db->buy_orders->count($coins_search);
                if ($coins_search_data > 0) {
                    $data['active_coins'] = $coins_search_data;
                }else{
                    $data['active_coins'] = 0;
                }

                // for active coins kraken

                $coins_search_data_kraken = $db->buy_orders_kraken->count($coins_search);
                // echo json_encode($coins_search_data_kraken); exit;
                if ($coins_search_data_kraken > 0) {
                    $data['active_coins_kraken'] = $coins_search_data_kraken;
                }else{
                    $data['active_coins_kraken'] = 0;
                }

                //  cost average ledger binance

                $cavg_search['cavg_parent'] = 'yes';
                $cavg_search['parent_status'] = ['$ne' => 'parent'];
                $cavg_search['application_mode'] = 'live';
                $cavg_search['admin_id'] = $request;
                $cavg_search['trigger_type'] = 'barrier_percentile_trigger';

                $cavg_search_data = $db->buy_orders->count($cavg_search);
                if ($cavg_search_data > 0) {
                    $data['cavg_ledgers'] = $cavg_search_data;
                }else{
                    $data['cavg_ledgers'] = 0;
                }

                //  cost average ledger kraken

                $cavg_search_data_kraken = $db->buy_orders_kraken->count($cavg_search);
                if ($cavg_search_data_kraken > 0) {
                    $data['cavg_ledgers_kraken'] = $cavg_search_data_kraken;
                }else{
                    $data['cavg_ledgers_kraken'] = 0;
                }

                // =-==============buying==================

                $previous3_week_ago_date = date('Y-m-d H:i:s' , strtotime('-3 weeks'));
                $previous3_week_ago_date = $this->mongo_db->converToMongodttime($previous3_week_ago_date);

                $buying_data = $db->user_investment_binance->find([
                    'admin_id' => $request ,            
                    '$or' =>[
                        ['last_btc_buy' =>['$gt' => $previous3_week_ago_date]] ,
                        ['last_usdt_buy' =>['$gt' => $previous3_week_ago_date]]
                        ]]);

                $buying_data_array = iterator_to_array($buying_data);    
                if(count($buying_data_array) > 0)
                {
                    $data['buying'] = 'yes';
                }
                        // ==============buying end==================
                        // ==============buying kraken===============

                $buying_data_kraken = $db->user_investment_kraken->find([
                    'admin_id' => $request ,
                    '$or' =>[
                        ['last_btc_buy' =>['$gt' => $previous3_week_ago_date]] ,
                        ['last_usdt_buy' =>['$gt' => $previous3_week_ago_date]]
                        ]]);

                $buying_data_kraken_array = iterator_to_array($buying_data_kraken);    
                if(count($buying_data_kraken_array) > 0)
                {
                    $data['buying_kraken'] = 'yes';
                }
                        // ==============buying kraken end===============

                        // ==================silling================
                $selling_data = $db->sold_buy_orders->find([
                    'admin_id' =>  $request,
                    'modified_date' =>['$gt' => $previous3_week_ago_date] ,
                ]);
                
                $selling_data_array = iterator_to_array($selling_data); 
                if(count($selling_data_array) > 0){
                    $data['selling'] = 'yes';
                }

                        // ==================silling end================

                        // =================silling kraken==============

                $selling_data_kraken = $db->sold_buy_orders_kraken->find([
                    'admin_id' =>  $request,
                    'modified_date' =>['$gt' => $previous3_week_ago_date] ,
                ]);

                
                $selling_data_array_kraken = iterator_to_array($selling_data_kraken); 
                if(count($selling_data_array_kraken) > 0){
                    $data['selling_kraken'] = 'yes';
                }
       
                $pipline_for_total_balance =[
                    [
                    '$match' => ['admin_id' => $request] 
                    ],
                    ['$sort' => ['_id' => -1]]
                ];
                
                $account_worth = $db->user_investment_binance->aggregate($pipline_for_total_balance);

                $account_worth_array = iterator_to_array($account_worth);
                if($account_worth_array[0]['account_worth'] > 500)
                {
                    $data['total_balance_gt_500'] = 'yes';
                }
                if($account_worth_array[0]['1y_deposit_perc'] > 10)
                {
                    $data['accumulation_gt_10'] = 'yes';
                }

                // account worth and accumulation kraken

                $account_worth_kr = $db->user_investment_kraken->aggregate($pipline_for_total_balance);
                $account_worth_array_kr = iterator_to_array($account_worth_kr);
                if($account_worth_array_kr[0]['account_worth'] > 500)
                {
                    $data['total_balance_gt_500_kraken'] = 'yes';
                }
                if($account_worth_array_kr[0]['1y_deposit_perc'] > 10)
                {
                    $data['accumulation_gt_10_kraken'] = 'yes';
                }

                //check user order history kraken
                $user_trade_data_kr = $db->user_trade_history_kraken->find(['user_id'=>$request]);
                $user_trade_data_kraken = iterator_to_array($user_trade_data_kr);
                if (!empty($user_trade_data_kraken) && $user_trade_data_kraken > 0) {
                    $data['user_trade_history_kraken'] = 'yes';
                }

                //check user balance available/allocated in auto trade setting collection for binance
                $user_atg_check = $db->auto_trade_settings->find(['user_id'=>$request, 'application_mode' => 'live']);
                $user_atg_check_binance = iterator_to_array($user_atg_check);
                foreach($user_atg_check_binance as $user_atg_check_bin){
                    if (!empty($user_atg_check_bin) && ($user_atg_check_bin['step_4']['availableUSDT'] > 0 || $user_atg_check_bin['step_4']['availableBTC'] > 0 || $user_atg_check_bin['step_4']['allocatedBTC'] > 0 || $user_atg_check_bin['step_4']['allocatedUSDT'] > 0)) {
                        $data['available_balance_gt_zero'] = 'yes';
                        //if avaialbe/allocated greater than zero set ATG yes too
                        $data['auto_trade_generator'] = 'yes';
                    }
                }
                
                //check user balance available/allocated in auto trade setting collection for krken
                $user_atg_check_kr = $db->auto_trade_settings_kraken->find(['user_id'=>$request, 'application_mode' => 'live']);
                $user_atg_check_kraken = iterator_to_array($user_atg_check_kr);
                foreach($user_atg_check_kraken as $user_atg_check_kr){
                    if (!empty($user_atg_check_kr) && ($user_atg_check_kr['step_4']['availableUSDT'] > 0 || $user_atg_check_kr['step_4']['availableBTC'] > 0 || $user_atg_check_kr['step_4']['allocatedBTC'] > 0 || $user_atg_check_kr['step_4']['allocatedUSDT'] > 0)) {
                        $data['available_balance_gt_zero_kraken'] = 'yes';
                        //if avaialbe/allocated greater than zero set ATG yes too
                        $data['auto_trade_generator_kraken'] = 'yes';
                    }
                }
                
                //check BNB exists for binance
                $user_bnb_check = $db->user_wallet->findOne(['user_id'=>$request, 'coin_symbol' => 'BNBBTC']);
                if ($user_bnb_check != null || $user_bnb_check != '' || $user_bnb_check != false) {
                    $user_bnb_check_binance = iterator_to_array($user_bnb_check);
                    if (!empty($user_bnb_check_binance) && $user_bnb_check_binance > 0 && $user_bnb_check_binance['coin_balance'] > 0) {
                        $data['bnb_exists'] = 'yes';
                    }
                }
                
                echo json_encode($data);
                
            }
        }
    }

    public function disable_user_api_key_post(){
       
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *");
        $request = json_decode($this->input->raw_input_stream, true);

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);
        if($tokenData != false ){
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

            $today = date('Y-m-d H:i:s');
            $today_date = $this->mongo_db->converToMongodttime($today);

            if($request['exchange'] == 'binance'){
                // $user_collection = 'users';
                $userID = $this->mongo_db->mongoId($request['user_id']);
                if($request['key_no'] == 1 || $request['key_no'] == '1'){
                    $api_key_valid = 'is_api_key_valid';
                    $api_key_no = 'api_key_1';
                    $api_key_secret = 'secret_1';
                    
                }elseif($request['key_no'] == 2 || $request['key_no'] == '2'){
                    $api_key_valid = 'is_api_key_valid_secondary';
                    $api_key_no = 'api_key_2';
                    $api_key_secret = 'secret_2';
                }
            }else{
                // $user_collection = 'kraken_credentials';
                $userID = $request['user_id'];
                if($request['key_no'] == 1 || $request['key_no'] == '1'){
                    $api_key_valid = 'is_api_key_valid';
                    $api_key_no = 'api_key_1';
                    $api_key_secret = 'secret_1';
                }elseif($request['key_no'] == 2 || $request['key_no'] == '2'){
                    $api_key_valid = 'is_api_key_valid_secondary';
                    $api_key_no = 'api_key_2';
                    $api_key_secret = 'secret_2';
                }
            }
            // echo print_r($request); exit;
            $db = $this->mongo_db->customQuery();
            //update user api key data 
            if($request['exchange'] == 'binance'){
                if ($request['key_no'] == 1 || $request['key_no'] == '1') {
                    $user_data = $db->users->updateOne(['_id' => $userID], ['$set' => [$api_key_valid => 'no', $api_key_no => 'no', $api_key_secret => 'no', 'last_key_disabled_date' => $today_date]]);
                }else{
                    $user_data = $db->users->updateOne(['_id' => $userID], ['$set' => [$api_key_valid => 'no', $api_key_no => 'no', $api_key_secret => 'no', 'last_key_disabled_date_2' => $today_date]]);
                }
                
            }else{
                if ($request['key_no'] == 1 || $request['key_no'] == '1') {
                    $user_data = $db->kraken_credentials->updateOne(['user_id' => $userID], ['$set' => [$api_key_valid => 'no', $api_key_no => 'no', $api_key_secret => 'no', 'last_key_disabled_date_kraken' => $today_date]]);
                }else{
                    $user_data = $db->kraken_credentials->updateOne(['user_id' => $userID], ['$set' => [$api_key_valid => 'no', $api_key_no => 'no', $api_key_secret => 'no', 'last_key_disabled_date_kraken_2' => $today_date]]);
                }
                
            }
            
                // Send success response
            $response = array(
                'success' => true,
                'message' => 'API key disabled successfully.',
                'code' => 200,
                'data' => $user_data
            );
            echo json_encode($response);
            exit;

            }
        }
        // Send error response
        $response = array(
            'success' => false,
            'message' => 'Invalid token or user not authorized.',
            'code' => 401
        );
        echo json_encode($response);
        exit;
     }

         //API call for total user wallet balance by hassam 05-01-2024
    public function user_wallet_balance_post(){ 
       
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *");
        $request = json_decode($this->input->raw_input_stream, true);

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $exchange = $request['exchange'];
                $user_id = $request['user_id'];

                if ($exchange == 'kraken') {
                    $collection_wallets = 'user_wallet_kraken';  
                } else {
                    $collection_wallets = 'user_wallet';
                }

                $collection_market = 'market_prices';
                $db = $this->mongo_db->customQuery();
                $where['user_id'] = $user_id;
                $user_data = $db->$collection_wallets->find($where);
                $user_return_arr = iterator_to_array($user_data);
                $total_usd_worth_usdt = 0;
                $total_usd_worth_btc = 0;
                $usdt_pair_coins = array();
                $btc_pair_coins = array();
                
                $pipeline['user_id'] = $user_id;
                $pipeline['coin_symbol'] = 'USDT';
                $fetch_usdt_balance = $db->$collection_wallets->findOne($pipeline);
                $usdtOnOrderBalance = isset($fetch_usdt_balance['onOrder']) ? $fetch_usdt_balance['onOrder'] : 0;
                $usdt_balance = $fetch_usdt_balance['coin_balance'] + $usdtOnOrderBalance;
                // echo '<pre> usdt_balance '; print_r($usdt_balance); exit;
                if (count($user_return_arr) > 0) {
                    foreach ($user_return_arr as $user_wallet_coin) {
                    if ($user_wallet_coin['coin_balance'] > 0) {
                        
                        $coin_symbol = $user_wallet_coin['coin_symbol'];
                
                        $condition['coin'] = $coin_symbol . 'USDT';
                        $coin_data_usdt = $db->$collection_market->findOne($condition);
                
                        if (!$coin_data_usdt) {
                            $condition['coin'] = $coin_symbol . 'BTC';
                            $coin_data_btc = $db->$collection_market->findOne($condition);
                
                            if ($coin_data_btc) {
                            $market_price_btc = $coin_data_btc['price'];
                            $coin_balance_btc = $user_wallet_coin['coin_balance'] + $user_wallet_coin['onOrder'];
                            $usd_worth_btc = $coin_balance_btc * $market_price_btc;
                            $total_usd_worth_btc += $usd_worth_btc;
                
                            $btc_pair_coins[] = array(
                                'coin_symbol' => $coin_symbol,
                                'coin_balance' => $coin_balance_btc,
                                'usd_worth' => $usd_worth_btc
                            );
                            } else {
                            // echo '<pre>'; print_r("Market price not found for $coin_symbol\n");
                            continue;
                            }
                        }
                
                        $market_price_usdt = $coin_data_usdt['price'];
                        $coin_balance_usdt = $user_wallet_coin['coin_balance'] + $user_wallet_coin['onOrder'];
                        $usd_worth_usdt = $coin_balance_usdt * $market_price_usdt;
                        $total_usd_worth_usdt += $usd_worth_usdt;
                
                        $usdt_pair_coins[] = array(
                            'coin_symbol' => $coin_symbol,
                            'coin_balance' => $coin_balance_usdt,
                            'usd_worth' => $usd_worth_usdt
                        );
                    }
                    }
                }
                $btc_converted_usd_worth = convert_btc_to_usdt($total_usd_worth_btc);
                $total_balance = $total_usd_worth_usdt + $usdt_balance +  $btc_converted_usd_worth;
            //    echo '<pre>'; print_r($total_balance); exit;

                $response = array(
                    'success' => true,
                    'message' => 'Total Wallet Balance',
                    'code' => 200,
                    'total_balance' => $total_balance
                );
                echo json_encode($response);
                exit;
            }
        }else{
            $response = array(
                'success' => false,
                'message' => 'Invalid token or user not authorized.',
                'code' => 401,
            );
            echo json_encode($response);
            exit;
        }
        
    }


    public function active_users_subs_expiry_greater_than_six_month_post(){ 
       
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *");
        $request = json_decode($this->input->raw_input_stream, true);

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);

        if($tokenData != false ){
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $exchange = 'binance';

                $db = $this->mongo_db->customQuery();
      
                $collection_users = ($exchange == 'kraken') ? 'user_investment_kraken' : 'user_investment_binance';
                $pipeline_active_users = [
                [
                    '$match' => [
                        'is_api_key_valid' => ['$eq' => 'yes'],
                        'account_block' => 'no',
                        'agt' => 'yes',
                        'account_worth' => ['$gt' => 0]
                    ]
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'admin_id' => 1,
                        'username' => 1,
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'users', 
                        'localField' => 'username',
                        'foreignField' => 'username',
                        'as' => 'user_data'
                    ]
                ],
                [
                    '$unwind' => '$user_data'
                ],
                [
                    '$project' => [
                        'admin_id' => 1,
                        'username' => 1,
                        'subscription_expiry_date' => '$user_data.subscription_expiry_date'
                    ]
                ],
                [
                    '$match' => [
                        'subscription_expiry_date' => ['$gt' => '2024-06-06']
                    ]
                ]
            ];
            
            $active_users = $db->$collection_users->aggregate($pipeline_active_users);
            $res_active_users = iterator_to_array($active_users);

                $response = array(
                    'success' => true,
                    'code' => 200,
                    'active_users' => $res_active_users
                );
                echo json_encode($response);
                exit;
            }
        }else{
            $response = array(
                'success' => false,
                'message' => 'Invalid token or user not authorized.',
                'code' => 401,
            );
            echo json_encode($response);
            exit;
        }
        
    }
    

    public function getActiveUsersSubscriptionDateExpiryAfterSixMonths($exchange=''){

        $db = $this->mongo_db->customQuery();
      
        $collection_users = ($exchange == 'kraken') ? 'user_investment_kraken' : 'user_investment_binance';
        $pipeline_active_users = [
          [
              '$match' => [
                  'is_api_key_valid' => ['$eq' => 'yes'],
                  'account_block' => 'no',
                  'agt' => 'yes',
                  'account_worth' => ['$gt' => 0]
              ]
          ],
          [
              '$project' => [
                  '_id' => 0,
                  'admin_id' => 1,
                  'username' => 1,
              ]
          ],
          [
              '$lookup' => [
                  'from' => 'users', 
                  'localField' => 'username',
                  'foreignField' => 'username',
                  'as' => 'user_data'
              ]
          ],
          [
              '$unwind' => '$user_data'
          ],
          [
              '$project' => [
                  'admin_id' => 1,
                  'username' => 1,
                  'subscription_expiry_date' => '$user_data.subscription_expiry_date'
              ]
          ],
          [
              '$match' => [
                  'subscription_expiry_date' => ['$gt' => '2024-06-06']
              ]
          ]
      ];
      
      $active_users = $db->$collection_users->aggregate($pipeline_active_users);
      $res_active_users = iterator_to_array($active_users);
    //   echo '<pre>'; print_r($res_active_users); exit;
    }

    public function cost_avg_ledger_pause_post(){ 
       
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *");
        $request = json_decode(file_get_contents('php://input'), true);

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);
       
        if($tokenData != false ){
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $user_id = $request['user_id'];
                $exchange = $request['exchange'];
                $symbol_coin = $request['symbol'];
                // echo '<pre>'; print_r($user_id); 
                // echo "<br>";
                if($exchange == '' || $exchange == 'binance'){
                    $collection_users = 'users';
                    $collection_atg = 'auto_trade_settings';
                    $collection_orders = 'buy_orders';
                }else{
                    $collection_users = 'kraken_credentials';
                    $collection_atg = 'auto_trade_settings_kraken';
                    $collection_orders = 'buy_orders_kraken';
                }
                
                $CI = &get_instance();
                $db = $this->mongo_db->customQuery();
                $collectionName = ($exchange == 'binance') ? 'buy_orders' : 'buy_orders_' . $exchange;
                // echo '<pre>'; print_r($collectionName); exit;
                $pipeline = [
                    [
                        '$match' => [
                            'admin_id' => $user_id,
                            'cavg_parent' => 'yes',
                            'cost_avg_array' => ['$exists' => true],
                            'symbol' => $symbol_coin,
                            'status' => ['$in' => ['CA_TAKING_CHILD', 'FILLED', 'cost_submitted_all_for_sell', 'ca_taking_child', 'LTH', 'COST_AVG', 'cost_avg']]
                        ],
                    ],
                    [
                        '$project' => [
                            'closing_perc_cost_avg' => 1,
                            'symbol' => 1,
                            'admin_id' => 1,
                            'pause_by_user' => 1,
                            'status' => 1,
                            'trigger_type' => 1,
                        ],
                    ]
                ];

                $get_orders_object = $db->$collectionName->aggregate($pipeline);
                $get_array_orders = iterator_to_array($get_orders_object);
                // echo '<pre> get_array_orders '; print_r($get_array_orders); exit;
                if (count($get_array_orders) > 0) {
                    foreach ($get_array_orders as $order) {
                        if (isset($order['_id']) && isset($order['pause_by_user']) && $order['pause_by_user'] == 'yes') {
                            $db->$collectionName->updateOne(
                                ['_id' => $this->mongo_db->mongoId($order['_id'])], 
                                ['$set' => ['pick_parent' => 'no']]
                            );
                        }
                    }
                } else {
                    $response = array(
                        'success' => true,
                        'code' => 404,
                        'message' => 'No ledger found'
                    );
                    echo json_encode($response);
                    exit;
                }

                // // sending updated documents oid
                $updated_documents_cursor = $db->$collectionName->find(
                    ['_id' => ['$in' => array_map(function ($order) {
                        return $this->mongo_db->mongoId($order['_id']);
                    }, $get_array_orders)]],
                    ['projection' => [
                        '_id' => 1,
                        'pause_by_user'=>1,
                        ]]
                );
                $updated_documents = iterator_to_array($updated_documents_cursor);

                $response = array(
                    'success' => true,
                    'code' => 200,
                    'message' => $updated_documents
                );
                echo json_encode($response);
                exit;

            }
        }else{
            $response = array(
                'success' => false,
                'message' => 'Invalid token or user not authorized.',
                'code' => 401,
            );
            echo json_encode($response);
            exit;
        }
        
    }

    public function parent_pause_post(){ 
       
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *");
        $request = json_decode(file_get_contents('php://input'), true);

        $received_Token = $this->input->get_request_header('Authorization');
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
        $tokenData = json_decode($tokenData);
       
        if($tokenData != false ){
            $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
            
            if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

                $order_id = $request['order_id'];
                $exchange = $request['exchange']; 
                // echo '<pre>'; print_r($order_id);exit; 
                // echo "<br>";
                if($exchange == '' || $exchange == 'binance'){
                    $collection_users = 'users';
                    $collection_atg = 'auto_trade_settings';
                    $collection_orders = 'buy_orders';
                }else{
                    $collection_users = 'kraken_credentials';
                    $collection_atg = 'auto_trade_settings_kraken';
                    $collection_orders = 'buy_orders_kraken';
                }
                
                $CI = &get_instance();
                $db = $this->mongo_db->customQuery();
                $collectionName = ($exchange == 'binance') ? 'buy_orders' : 'buy_orders_' . $exchange;
                // echo '<pre>'; print_r($collectionName); 
                $pipeline = [
                    [
                        '$match' => [
                            '_id' => $this->mongo_db->mongoId($order_id),

                        ],
                    ],
                    [
                        '$project' => [
                            'symbol' => 1,
                            'admin_id' => 1,
                            'status' => 1,
                            'trigger_type' => 1,
                        ],
                    ]
                ];

                // echo '<pre>pipeline :: '; print_r($pipeline); exit;
                $get_orders_object = $db->$collectionName->aggregate($pipeline);
                $get_array_orders = iterator_to_array($get_orders_object);
                // echo '<pre> get_array_orders '; print_r($get_array_orders); exit;
                if (count($get_array_orders) > 0) {
                    foreach ($get_array_orders as $order) {
                        if (isset($order['_id'])) {
                            $db->$collectionName->updateOne(
                                ['_id' => $this->mongo_db->mongoId($order['_id'])], 
                                ['$set' => ['pick_parent' => 'no']]
                            );
                        }
                    }
                } else {
                    $response = array(
                        'success' => true,
                        'code' => 404,
                        'message' => 'No Order found'
                    );
                    echo json_encode($response);
                    exit;
                }

                // // sending updated documents oid
                $updated_documents_cursor = $db->$collectionName->find(
                    ['_id' => ['$in' => array_map(function ($order) {
                        return $this->mongo_db->mongoId($order_id);
                    }, $get_array_orders)]],
                    ['projection' => [
                        '_id' => 1,
                        'symbol'=>1,
                        ]]
                );
                $updated_documents = iterator_to_array($updated_documents_cursor);

                $response = array(
                    'success' => true,
                    'code' => 200,
                    'message' => $updated_documents
                );
                echo json_encode($response);
                exit;

            }
        }else{
            $response = array(
                'success' => false,
                'message' => 'Invalid token or user not authorized.',
                'code' => 401,
            );
            echo json_encode($response);
            exit;
        }
        
    }

    public function get_user_info_post(){
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *");

        $post = json_decode(file_get_contents('php://input'), true);
        if (count($post) > 0) {
            $posted_data = $post;
        } elseif (count($this->input->post()) > 0) {
            $posted_data = $this->input->post();
        }
    
        // Receiving the token and then matching the token with data
        $received_Token = $this->input->get_request_header('Authorization');
        //echo '<pre>'; print_r($received_Token); exit;
        $received_Token = str_replace("Bearer ", "", $received_Token);
        $received_Token = str_replace("Token ", "", $received_Token);
        $received_Token = str_replace("Basic ", "", $received_Token);
        $received_Token_encoded_string = base64_decode($received_Token);
        $received_Token_array = explode(':', $received_Token_encoded_string);
        $username = $received_Token_array[0];
        $key = $received_Token_array[1];
        
        if(empty($posted_data['username'])){
            $message = array(
                'status' => FALSE,
                'message' => 'username can not be empty.',
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }

        if ($username == 'DigienSights' && $key == 'Digie@cZChVN9lMCuykPMsBYPs') {

            $CI = &get_instance();
            $db = $this->mongo_db->customQuery();
            $collectionName = 'users';
            
            $get_user_object = $db->$collectionName->find(['username' => $posted_data['username']]);
            $get_array_user = iterator_to_array($get_user_object);


            if(count($get_array_user) > 0 ){
                $message = array(
                    'status' => TRUE,
                    'ID' => (string)$get_array_user[0]['_id'],
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
            }else{
                $message = array(
                    'status' => FALSE,
                    'message' => 'Username not found',
                );
                $this->set_response($message, REST_Controller::HTTP_OK);
            }

        }else{
            $message = array(
                'status' => FALSE,
                'message' => 'Something went wrong',
            );
            $this->set_response($message, REST_Controller::HTTP_OK);
        }

    }//end
}
