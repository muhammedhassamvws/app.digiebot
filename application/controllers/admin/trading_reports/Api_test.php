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

class Api_test extends REST_Controller {

    function __construct() {

        parent::__construct();

        ini_set("display_errors", E_ALL);
        error_reporting(E_ALL);

        // ini_set('display_errors', E_ALL & ~E_NOTICE);
        // error_reporting(E_ALL & ~E_NOTICE);

        // Load Library Goes here
        $this->load->library('binance_api');

        // Load Modal
        $this->load->model('admin/mod_api_calls');
        $this->load->model('admin/mod_api_services');
        $this->load->model('admin/mod_settings');
        $this->load->model('admin/mod_users');

    }

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

    public function add_user_coins_post() {

        $symbols = $this->post('symbols');
        $user_id = $this->post('user_id');
        $exchange = $this->post('exchange');

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

    }//end add_user_coins_post
    
    public function get_user_coins_post() {

        $user_id = $this->post('user_id');
        $exchange = $this->post('exchange');

        if(!empty($user_id) && !empty($exchange)){

            $params = [
                'exchange' => $exchange,
                'admin_id' => $user_id,
            ];
    
            $req_arr = [
                'req_type' => 'POST',
                'req_endpoint' => 'get_user_coins',
                'req_params' => $params,
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
    
                        $coinsArr[] = [
                            'symbol' => $globalCoins[$i]['symbol'],
                            'coin_categories' => $globalCoins[$i]['coin_categories']
                        ];
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

    public function get_auto_trade_settings_post(){
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
                'req_endpoint' => 'getAutoTradeSettings',
                'req_params' => $params,
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

    public function test_code_get(){

        $image = base64_decode("");
        $image_name = md5(uniqid(rand(), true));
        //rename file name with random number
        $filename = $image_name . '.' . 'jpg';
        $path = "/var/www/html/assets/profile_images/".$filename;
        //image uploading folder path
        $data = file_put_contents($path, $image);
        
        echo "<pre>";
        echo "<br> $filename <br>";
        var_dump($data);

        // $this->mod_api_services->send_confirm_email('5d9d9482710a9027ff3da7b2', 'omi123.developer@gmail.com');
    }
    
    /* ******************* End  ATG APIs  ************** */

    /* ************ KEY/Secret APIs  ************** */
    public function get_api_keys_post(){

        $request = $this->post();
        $user_id = $request['user_id'];
        $exchange = $request['exchange'];
        $application_mode = $request['application_mode'];

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

    }

    public function velidate_api_key_post(){

        $request = $this->post();
        $application_mode = $request['application_mode'];
        $user_id = $request['user_id'];
        $exchange = $request['exchange'];
        $api_key = $request['api_key'] ?? '';
        $api_secret = $request['api_secret'] ?? '';

        if(!empty($user_id) && !empty($exchange) && !empty($application_mode) && $application_mode == 'live' && $api_key != '' && $api_secret != ''){

            if($exchange == 'binance'){
                
                $params = [
                    'APIKEY' => $api_key,
                    'APISECRET' => $api_secret,
                ];
        
                $req_arr = [
                    'req_type' => 'POST',
                    'req_endpoint' => '',
                    'req_url' => 'https://app.digiebot.com/admin/api_calls/validate_binance_credentials',
                    'req_params' => $params,
                ];
                $resp = hitCurlRequest($req_arr);

                if ($resp['http_code'] == 200) {

                    //set response
                    $response = $resp['response'];
                    // echo "<pre>";
                    // print_r($response);
                    $message = [
                        'status' => true,
                        'valid' => $resp['response']['status'] ? true : false,
                        'message' => $resp['response']['status'] ? 'Valid key' : 'Invalid Key',
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
                ];
                $resp = hitCurlRequest($req_arr);

                if ($resp['http_code'] == 200) {

                    //set response
                    $response = $resp['response'];
                    // echo "<pre>";
                    // print_r($response);

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

    }

    public function disable_api_key_post(){

        $request = $this->post();
        $application_mode = $request['application_mode'] ?? '';
        $user_id = $request['user_id'];
        $exchange = $request['exchange'];
        $key_type = $request['key_type'] ?? '';

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

    }
    /* ******** End  KEY/Secret APIs  ************** */
    
    
    /* ******** Waleed user update API   ****************** */

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

    /* ******** End Waleed user update API   ************** */

}