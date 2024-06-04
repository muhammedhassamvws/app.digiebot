<?php
/**
 *
 */
class Cronjob extends CI_Controller
{

    public function __construct(){

        parent::__construct();
        $this->load->helper('file');
        // error_reporting(E_ALL & ~E_NOTICE);
        // ini_set('display_errors', E_ALL & ~E_NOTICE);
        $this->load->model('admin/Mod_jwt');
        $this->load->model('admin/Mod_api_calls');
        //helper 
        $this->load->helper('common_helper');
        $this->load->helper('new_common_helper');
    }
    
    //clean_order_logs
    public function clean_order_logs(){
        ini_set("display_errors", E_ALL);
        error_reporting(E_ALL);

        $admin_ids = [
            '5c0912b7fc9aadaac61dd072',
            '5c0915befc9aadaac61dd1b8',
            '5c155879fc9aadace2428cb2',
            '5c0913c9fc9aadaac61dd0d0',
            '5c8482e1fc9aad8d69397c32',
            '5c86f33bfc9aad989b4ca8d2',
            '5c1432d6fc9aad2655292c82',
        ];

        //get user and move it's order logs to the new collection
        $where_get = [
            'admin_id' => ['$in' => $admin_ids],
            'updated_date' => ['$exists' => false]
        ];
        $this->mongo_db->where($where_get);
        $this->mongo_db->limit(1);
        $get_obj = $this->mongo_db->get('existing_all_order_ids');
        $orders = iterator_to_array($get_obj);

        if(!empty($orders[0])){
            $o = $orders[0];
            $update_id = $o['_id'];

            if(!empty($o['ids'])){
                foreach($o['ids'] as $oid){

                    $string_id = (string)$oid; 
                    $obj_id = $this->mongo_db->mongoId($oid);
                    $where_log = [
                        'order_id' => ['$in' => [$string_id, $obj_id] ]
                    ];

                    $query = [
                        ['$match' => $where_log]
                    ];

                    $orders_history_log_2019_backup = 'orders_history_log_2019_backup';
                    $db = $this->mongo_db->customQuery();
                    $logs = $db->$orders_history_log_2019_backup->aggregate($query);

                    $logs = iterator_to_array($logs);
                    
                    if(!empty($logs)){
                        $clean_order_logs = 'clean_order_logs';
                        $db = $this->mongo_db->customQuery();
                        $moved = $db->$clean_order_logs->insertMany($logs);
                    }
                    
                }

                //update in the old existing_all_oprder_ids
                $where = [
                    '_id' => $update_id,
                ];
                $update = [
                    '$set' => [
                        'updated_date' => date('d-m-Y h:i:s a'),
                    ],
                ];
                $db = $this->mongo_db->customQuery();
                $existing_all_order_ids = 'existing_all_order_ids';
                $response = $db->$existing_all_order_ids->updateOne($where, $update);

            }
        }

        echo '1';
    } //End clean_order_logs
    
    //calculate_5_hour_min_max_binance
    public function calculate_5_hour_min_max_binance(){
        
        //Current Date
        $curr_date =date('Y-m-d H:i:s');
        $curr_date = $this->mongo_db->converToMongodttime($curr_date);
        $curr_date_utc = gmdate('Y-m-d H:i:s');
        
        $start_date = date('Y-m-d H:i:s', strtotime('-6 hour'));
        $start_date = $this->mongo_db->converToMongodttime($start_date);
        $start_date_utc = gmdate('Y-m-d H:i:s', strtotime('-6 hour'));
        
        //End Date
        // $end_date = date('Y-m-d H:i:s');
        $end_date = date('Y-m-d H:i:s', strtotime('-5 hour'));
        $end_date = $this->mongo_db->converToMongodttime($end_date);
        $end_date_utc = gmdate('Y-m-d H:i:s', strtotime('-5 hour'));

        // Find last sold coins
        $where = [
            'application_mode' => 'live',
            'sell_date' => ['$gte' => $start_date, '$lte' => $end_date],
            '5_hour_max_market_price' => ['$exists' => false],
            '5_hour_min_market_price' => ['$exists' => false],
        ];

        $coins = $this->find_last_sold_coins($where, 'binance');

        if (!empty($coins)) {
            foreach ($coins as $symbol) {
                
                //Hit CURL to get 5 hour market min_max for that coin
                $params = [
                    'coin' => $symbol,
                    'start_date' => (string)$start_date_utc,
                    'end_date' => (string)$curr_date_utc,
                ];
                $req_arr = [
                    'req_type' => 'POST',
                    'req_params' => $params,
                    'req_endpoint' => '',
                    'req_url' => 'http://35.171.172.15:3000/api/minMaxMarketPrices',
                ];
                $resp = hitCurlRequest($req_arr);
                // $resp = array('http_code' => $http_code, 'response' => $response, 'error' => $err);
                if($resp['http_code'] == 200 && $resp['response']['success'] == 'true'){
                    $min_value = $resp['response']['data']['min_price'];
                    $max_value = $resp['response']['data']['max_price'];

                    $where['symbol'] = $symbol;
                    $upd['$set'] = [
                        '5_hour_max_market_price' => (float) $max_value, 
                        '5_hour_min_market_price' => (float) $min_value
                    ];

                    $db = $this->mongo_db->customQuery();
                    $db->sold_buy_orders->updateMany($where, $upd);

                    // $orders = $db->sold_buy_orders->find($where, ['limit' => 10]);    
                    // $orders = iterator_to_array($orders);
                }

            } //End of foreach
        } //End of not empty
        
        //Save last Cron Executioon
        $this->last_cron_execution_time('calculate_5_hour_min_max_binance', '1h', 'Cronjob to calculate 5 hour min and 5 hour max market price after Sold for Binance (3 * * * *)', 'soldOrderminmax');

    }//End calculate_5_hour_min_max_binance
    
    //calculate_5_hour_min_max_bam
    //this method was commented by Muhammad Sheraz on (31-august-2021) on behalf of shehzad.
    // public function calculate_5_hour_min_max_bam(){
        
    //     //Current Date
    //     $curr_date =date('Y-m-d H:i:s');
    //     $curr_date = $this->mongo_db->converToMongodttime($curr_date);
    //     $curr_date_utc = gmdate('Y-m-d H:i:s');
        
    //     $start_date = date('Y-m-d H:i:s', strtotime('-6 hour'));
    //     $start_date = $this->mongo_db->converToMongodttime($start_date);
    //     $start_date_utc = gmdate('Y-m-d H:i:s', strtotime('-6 hour'));
        
    //     //End Date
    //     // $end_date = date('Y-m-d H:i:s');
    //     $end_date = date('Y-m-d H:i:s', strtotime('-5 hour'));
    //     $end_date = $this->mongo_db->converToMongodttime($end_date);
    //     $end_date_utc = gmdate('Y-m-d H:i:s', strtotime('-5 hour'));

    //     // Find last sold coins
    //     $where = [
    //         'application_mode' => 'live',
    //         'sell_date' => ['$gte' => $start_date, '$lte' => $end_date],
    //         '5_hour_max_market_price' => ['$exists' => false],
    //         '5_hour_min_market_price' => ['$exists' => false],
    //     ];

    //     $coins = $this->find_last_sold_coins($where, 'bam');

    //     if (!empty($coins)) {
    //         foreach ($coins as $symbol) {
                
    //             //Hit CURL to get 5 hour market min_max for that coin
    //             $params = [
    //                 'coin' => $symbol,
    //                 'start_date' => (string)$start_date_utc,
    //                 'end_date' => (string)$curr_date_utc,
    //             ];
    //             $req_arr = [
    //                 'req_type' => 'POST',
    //                 'req_params' => $params,
    //                 'req_endpoint' => '',
    //                 'req_url' => 'http://35.171.172.15:3001/api/minMaxMarketPrices',
    //             ];
    //             $resp = hitCurlRequest($req_arr);
    //             // $resp = array('http_code' => $http_code, 'response' => $response, 'error' => $err);
    //             if($resp['http_code'] == 200 && $resp['response']['success'] == 'true'){
    //                 $min_value = $resp['response']['data']['min_price'];
    //                 $max_value = $resp['response']['data']['max_price'];

    //                 $where['symbol'] = $symbol;
    //                 $upd['$set'] = [
    //                     '5_hour_max_market_price' => (float) $max_value, 
    //                     '5_hour_min_market_price' => (float) $min_value
    //                 ];

    //                 $db = $this->mongo_db->customQuery();
    //                 $db->sold_buy_orders_bam->updateMany($where, $upd);

    //                 // $orders = $db->sold_buy_orders->find($where, ['limit' => 10]);    
    //                 // $orders = iterator_to_array($orders);
    //             }

    //         } //End of foreach
    //     } //End of not empty
        
    //     //Save last Cron Executioon
    //     $this->last_cron_execution_time('calculate_5_hour_min_max_bam', '1h', 'Cronjob to calculate 5 hour min and 5 hour max market price after Sold for Bam (3 * * * *)', 'soldOrderminmax');

    // }//End calculate_5_hour_min_max_bam

    public function calculate_trade_min_max_binance(){
      
        $start_date = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $start_date = $this->mongo_db->converToMongodttime($start_date);
        
        //End Date
        $end_date = date('Y-m-d H:i:s');
        // $end_date = date('Y-m-d H:i:s', strtotime('-5 hour'));
        $end_date = $this->mongo_db->converToMongodttime($end_date);
        
        // Find last sold coins
        $where = [
            'application_mode' => 'live',
            'is_sell_order' => 'sold',
            'sell_date' => ['$gte' => $start_date, '$lte' => $end_date],
            'market_heighest_value' => ['$exists' => false],
            'market_lowest_value' => ['$exists' => false],
        ];
        $trades = $this->find_last_sold_trades($where, 'binance');
        
        if (!empty($trades)) {
            foreach ($trades as $order) {
                
                if(!empty($order['buy_date']) && !empty($order['sell_date'])){

                    $start_date_utc = $order["buy_date"]->toDateTime()->format("Y-m-d H:i:s");
                    $end_date_utc = $order["sell_date"]->toDateTime()->format("Y-m-d H:i:s");
                    
                    //Hit CURL to get 5 hour market min_max for that coin
                    $params = [
                        'coin' => $order['symbol'],
                        'start_date' => (string) $start_date_utc,
                        'end_date' => (string) $end_date_utc,
                    ];
                    $req_arr = [
                        'req_type' => 'POST',
                        'req_params' => $params,
                        'req_endpoint' => '',
                        'req_url' => 'http://35.171.172.15:3000/api/minMaxMarketPrices',
                    ];
                    $resp = hitCurlRequest($req_arr);
                    // $resp = array('http_code' => $http_code, 'response' => $response, 'error' => $err);

                    if ($resp['http_code'] == 200 && $resp['response']['success'] == 'true') {
                        $min_value = $resp['response']['data']['min_price'];
                        $max_value = $resp['response']['data']['max_price'];
    
                        $where1 = [ '_id' => $order['_id'] ];
                        $upd['$set'] = [
                            'market_heighest_value' => (float) $max_value,
                            'market_lowest_value' => (float) $min_value,
                        ];
    
                        $db = $this->mongo_db->customQuery();
                        $db->sold_buy_orders->updateOne($where1, $upd);
    
                    }
                }

            } //End of foreach
        } //End of not empty

        //Save last Cron Executioon
        $this->last_cron_execution_time('calculate_trade_min_max_binance', '1h', 'Cronjob to calculate trade min and max market price between trade purchase and Sold for Binance (10 * * * *)', 'soldOrderminmax');

        echo 'end calculate_trade_min_max_binance';

    }//End calculate_trade_min_max_binance
    // this was commented by Muhammad Sheraz on (31-august-2021) on behalf of shehzad.
    // public function calculate_trade_min_max_bam(){
      
    //     $start_date = date('Y-m-d H:i:s', strtotime('-1 hour'));
    //     $start_date = $this->mongo_db->converToMongodttime($start_date);
        
    //     //End Date
    //     $end_date = date('Y-m-d H:i:s');
    //     // $end_date = date('Y-m-d H:i:s', strtotime('-5 hour'));
    //     $end_date = $this->mongo_db->converToMongodttime($end_date);
        
    //     // Find last sold coins
    //     $where = [
    //         'application_mode' => 'live',
    //         'is_sell_order' => 'sold',
    //         'sell_date' => ['$gte' => $start_date, '$lte' => $end_date],
    //         'market_heighest_value' => ['$exists' => false],
    //         'market_lowest_value' => ['$exists' => false],
    //     ];
    //     $trades = $this->find_last_sold_trades($where, 'bam');
        
    //     if (!empty($trades)) {
    //         foreach ($trades as $order) {
                
    //             if(!empty($order['buy_date']) && !empty($order['sell_date'])){

    //                 $start_date_utc = $order["buy_date"]->toDateTime()->format("Y-m-d H:i:s");
    //                 $end_date_utc = $order["sell_date"]->toDateTime()->format("Y-m-d H:i:s");
                    
    //                 //Hit CURL to get 5 hour market min_max for that coin
    //                 $params = [
    //                     'coin' => $order['symbol'],
    //                     'start_date' => (string) $start_date_utc,
    //                     'end_date' => (string) $end_date_utc,
    //                 ];
    //                 $req_arr = [
    //                     'req_type' => 'POST',
    //                     'req_params' => $params,
    //                     'req_endpoint' => '',
    //                     'req_url' => 'http://35.171.172.15:3001/api/minMaxMarketPrices',
    //                 ];
    //                 $resp = hitCurlRequest($req_arr);
    //                 // $resp = array('http_code' => $http_code, 'response' => $response, 'error' => $err);
    //                 if ($resp['http_code'] == 200 && $resp['response']['success'] == 'true') {
    //                     $min_value = $resp['response']['data']['min_price'];
    //                     $max_value = $resp['response']['data']['max_price'];
    
    //                     $where1 = ['_id' => $order['_id']];
    //                     $upd['$set'] = [
    //                         'market_heighest_value' => (float) $max_value,
    //                         'market_lowest_value' => (float) $min_value,
    //                     ];
    
    //                     $db = $this->mongo_db->customQuery();
    //                     $db->sold_buy_orders_bam->updateOne($where1, $upd);
    
    //                 }
    //             }

    //         } //End of foreach
    //     } //End of not empty

    //     //Save last Cron Executioon
    //     $this->last_cron_execution_time('calculate_trade_min_max_bam', '1h', 'Cronjob to calculate trade min and max market price between trade purchase and Sold for Bam (20 * * * *)', 'soldOrderminmax');

    //     echo 'end calculate_trade_min_max_bam';
    // }//End calculate_trade_min_max_bam

    public function find_last_sold_trades($where, $exchange){
        $pipeline = array(
            array(
                '$match' => $where,
            ), 
        );
        $allow = array('allowDiskUse' => true);
        $db = $this->mongo_db->customQuery();

        $collection = ($exchange == 'binance' ? 'sold_buy_orders' : "sold_buy_orders_$exchange");

        $response = $db->$collection->aggregate($pipeline, $allow);
        $records = iterator_to_array($response);

        return $records;
    }//End find_last_sold_trades

    public function find_last_sold_coins($where, $exchange){
        $pipeline = array(
            array(
                '$match' => $where,
            ),
            array(
                '$group' => array(
                    '_id' => array('symbol' => '$symbol'),
                    'symbol' => array('$first' => '$symbol'),
                    'symbols' => array('$addToSet' => '$symbol'),
                ),
            ),
            array(
                '$unwind' => '$symbols',
            ),
            array(
                '$project' => array(
                    "_id" => 0,
                    "symbols" => 1,
                ),
            ),
        );

        $allow = array('allowDiskUse' => true);
        $db = $this->mongo_db->customQuery();

        $collection = ($exchange == 'binance' ? 'sold_buy_orders' : "sold_buy_orders_$exchange");

        $response = $db->$collection->aggregate($pipeline, $allow);
        $records = iterator_to_array($response);
        $symbols = array_unique(array_column($records, 'symbols'));

        return $symbols;
    }//End find_last_sold_coins


    public function update_qty_from_usd_worth(){
        //All params are optional 
        $params = [
            // "user_id" => "5c0912b7fc9aadaac61dd072",
            // "exchange" => "binance",
            // "symbol" => "QTUMBTC",
        ];
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => 'update_qty_from_usd_worth',
            // 'req_url' => '',
        ];
        $resp = hitCurlRequest($req_arr);
        
        // // $resp = array('http_code' => $http_code, 'response' => $response, 'error' => $err);
        // if ($resp['http_code'] == 200 && $resp['response']['success'] == 'true') {
            
        // }

        //Save last Cron Executioon
        $this->last_cron_execution_time('update_qty_from_usd_worth', '5m', 'Cronjob to update quantity of parent orders from usd_worth (*/5 * * * *)', 'updateQtyUSDWorth');

    }

    public function auto_buy_bnb_binance($user_id = ''){
        //All params are optional 
        $params = [
            // "user_id" => "5c0912b7fc9aadaac61dd072",
            "user_id" => $user_id,
            "exchange" => "binance",
        ];
        // if(!empty($user_id))
        // {
        //   $params['user_id']  = $user_id; 
        // }
        $token = $this->Mod_jwt->LoginToken('5c0915befc9aadaac61dd1b8', 'vizzdeveloper');
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => 'hit_auto_buy_cron',
            'header'       =>  $token
            // 'req_url' => '',
        ];
        
        //5c0915befc9aadaac61dd1b8,vizzdeveloper
        $resp = hitCurlRequest($req_arr);
         if(!empty($user_id))
        {
            echo "<pre>";
            print_r($resp);
            //exit;
        }
        // // $resp = array('http_code' => $http_code, 'response' => $response, 'error' => $err);
        // if ($resp['http_code'] == 200 && $resp['response']['success'] == 'true') {
            
        // }

        //Save last Cron Executioon
        $this->last_cron_execution_time('auto_buy_bnb_binance', '20m', 'Cronjob to Auto buy BNB Binance (*/20 * * * *)', 'bnb');

    }//End auto_buy_bnb
    
    public function auto_buy_bnb_bam(){
        
        //All params are optional 
        $params = [
            // "user_id" => "5c0912b7fc9aadaac61dd072",
            "user_id" => "",
            "exchange" => "bam",
        ];
         $token = $this->Mod_jwt->LoginToken('5c0915befc9aadaac61dd1b8', 'vizzdeveloper');
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => 'hit_auto_buy_cron',
            // 'req_url' => '',
            'header'=>$token,
        ];
        $resp = hitCurlRequest($req_arr);
        
        // // $resp = array('http_code' => $http_code, 'response' => $response, 'error' => $err);
        // if ($resp['http_code'] == 200 && $resp['response']['success'] == 'true') {
            
        // }

        //Save last Cron Executioon
        $this->last_cron_execution_time('auto_buy_bnb_bam', '10m', 'Cronjob to Auto buy BNB Bam (*/15 * * * *)', 'bnb');

    }//End auto_buy_bnb_bam
    
    public function update_auto_trade_usd_worth_and_tradeable_balance_test(){
        echo "tghis is onwer";
    }

    //binance
    public function update_auto_trade_usd_worth_and_tradeable_balance(){

        $user_ids = [];
        $pipeline = [
            [
                '$match' => [
                    'application_mode' => 'live',
                    //'user_id'=>'5eb5a5a628914a45246bacc6',
                ],
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'user_id' => 1,
                ],
            ],
        ];
        $document = $this->mongo_db->customQuery();
        $atg_users = $document->auto_trade_settings->aggregate($pipeline);
        $atg_users = iterator_to_array($atg_users);
        if (!empty($atg_users)) {
            $total_atg_users = count($atg_users);
            for ($i = 0; $i < $total_atg_users; $i++) {
                $user_ids[] = $this->mongo_db->mongoId($atg_users[$i]['user_id']);
            }
        }

        $last_run = date('Y-m-d H:i:s', strtotime('-1 days'));
        $pipeline = [
            [
                '$match' => [
                    '_id' => ['$in' => $user_ids],
                    'application_mode' => 'both',
                    //'is_api_key_valid'=>'yes',
                    'account_block' => ['$ne' => 'yes'],
                    // HASSAN ALI
                     '$or' => [
                         ['atg_parents_update_cron_last_run'=> ['$exists' => false]],
                         ['atg_parents_update_cron_last_run' => ['$lte' => $this->mongo_db->converToMongodttime($last_run)]],
                     ],
                ],
            ],
            [
                '$sort' => [
                    'atg_parents_update_cron_last_run' => 1 
                ]
            ],
            [
                '$project' => [
                    '_id' => 1,
                    'username' => 1
                ],
            ],
            [
                '$limit' => 1,
            ],
        ];
        $document = $this->mongo_db->customQuery();
        $atg_users = $document->users->aggregate($pipeline);
        $atg_users = iterator_to_array($atg_users);
        echo "<pre>";print_r($atg_users);
        // echo 'data: '.count($atg_users);
        if(!empty($atg_users)){
            $total_users = count($atg_users);
            for($i=0; $i<$total_users; $i++){
                // var_dump((string) $atg_users[$i]['_id']);
                if(empty($atg_users[$i]['_id']) && $i != 0){
                    continue;
                }
                $userId = (string) $atg_users[$i]['_id'];
                $username = $atg_users[$i]['username'];

                $token = $this->Mod_jwt->LoginToken($userId, $username);
                echo 'token'.$token;
                $params = [
                    // "user_id" => "5c0912b7fc9aadaac61dd072",
                    "user_id" => (string) $atg_users[$i]['_id'],
                    "exchange" => "binance",
                    "application_mode" => "live",
                    // "symbol" => "QTUMBTC",
                ];

                $req_arr = [
                    'req_type' => 'POST',
                    'req_params' => $params,
                    // 'req_endpoint' => 'updateDailyTradeSettings',
                    'req_endpoint' => 'updateDailyTradeSettings_digie',
                    'header'       =>  $token
                ];
                $resp = hitCurlRequest($req_arr);
                echo "<pre>";
                print_r($resp);
                //update last cron run time
                $curr_time = date('Y-m-d H:i:s');
                $where1 = ['_id' => $atg_users[$i]['_id']];
                $upd['$set'] = [
                    'atg_parents_update_cron_last_run' => $this->mongo_db->converToMongodttime($curr_time),
                ];
                $db = $this->mongo_db->customQuery();
                $db->users->updateOne($where1, $upd);

                //sleep for 30 minutes and then execute for next user
                // sleep(30);
            }
        }

        //Save last Cron Executioon
        $this->last_cron_execution_time('update_auto_trade_usd_worth_and_tradeable_balance', '4m', 'Cronjob to update usd_worth and tradeable balance for Auto trade module for binance (*/4 * * * *)', 'updateQtyUSDWorth');

        echo "<br> ************** Script End *************** <br>";

    }//end update_auto_trade_usd_worth_and_tradeable_balance binance
    
    //bam
    public function update_auto_trade_usd_worth_and_tradeable_balance_bam(){

        $user_ids = [];
        $pipeline = [
            [
                '$match' => [
                    'application_mode' => 'live',
                ],
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'user_id' => 1,
                ],
            ],
        ];
        $document = $this->mongo_db->customQuery();
        $atg_users = $document->auto_trade_settings_bam->aggregate($pipeline);
        $atg_users = iterator_to_array($atg_users);
        if (!empty($atg_users)) {
            $total_atg_users = count($atg_users);
            for ($i = 0; $i < $total_atg_users; $i++) {
                $user_ids[] = $this->mongo_db->mongoId($atg_users[$i]['user_id']);
            }
        }

        $last_run = date('Y-m-d H:i:s', strtotime('-4 days'));
        $pipeline = [
            [
                '$match' => [
                    '_id' => ['$in' => $user_ids],
                    'application_mode' => 'both',
                    // 'atg_parents_update_cron_last_run_bam' => ['$lte' => $this->mongo_db->converToMongodttime($last_run)],
                    '$or' => [
                        ['atg_parents_update_cron_last_run_bam'=> ['$exists' => false]],
                        ['atg_parents_update_cron_last_run_bam' => ['$lte' => $this->mongo_db->converToMongodttime($last_run)]],
                    ],
                ],
            ],
            [
                '$sort' => [
                    'atg_parents_update_cron_last_run_bam' => 1 
                ]
            ],
            [
                '$project' => [
                    '_id' => 1,
                    'username' => 1
                ],
            ],
            [
                '$limit' => 1,
            ],
        ];
        $document = $this->mongo_db->customQuery();
        $atg_users = $document->users->aggregate($pipeline);
        $atg_users = iterator_to_array($atg_users);


        if (!empty($atg_users)) {
            $total_users = count($atg_users);
            for ($i = 0;$i < $total_users; $i++) {

                if(empty($atg_users[$i]['_id']) && $i != 0){
                    continue;
                }

                $userId = (string) $atg_users[$i]['_id'];
                $username = (string) $atg_users[$i]['username'];

                $token = $this->Mod_jwt->LoginToken($userId, $username);

                $params = [
                    // "user_id" => "5c0912b7fc9aadaac61dd072",
                    "user_id" => (string) $atg_users[$i]['_id'],
                    "exchange" => "bam",
                    "application_mode" => "live",
                    // "symbol" => "QTUMBTC",
                ];
                $req_arr = [
                    'req_type' => 'POST',
                    'req_params' => $params,
                    // 'req_endpoint' => 'updateDailyTradeSettings',
                    'req_endpoint' => 'updateDailyTradeSettings_digie',
                    'header'       => $token
                    // 'req_url' => '',
                ];
                $resp = hitCurlRequest($req_arr);

                echo "<pre>";print_r($resp);

                //update last cron run time
                $curr_time = date('Y-m-d H:i:s');
                $where1 = ['_id' => $atg_users[$i]['_id']];
                $upd['$set'] = [
                    'atg_parents_update_cron_last_run_bam' => $this->mongo_db->converToMongodttime($curr_time),
                ];
                $db = $this->mongo_db->customQuery();
                $db->users->updateOne($where1, $upd);

                //sleep for 30 minutes and then execute for next user
                // sleep(30);

            }
        }

        //Save last Cron Executioon
        $this->last_cron_execution_time('update_auto_trade_usd_worth_and_tradeable_balance_bam', '6m', 'Cronjob to update usd_worth and tradeable balance for Auto trade module for bam (*/6 * * * *)', 'updateQtyUSDWorth');


        echo "<br> ************** Script End *************** <br>";


    }//end update_auto_trade_usd_worth_and_tradeable_balance bam
    
    //kraken
    public function update_auto_trade_usd_worth_and_tradeable_balance_kraken(){

        $user_ids = [];
        $pipeline = [
            [
                '$match' => [
                    'application_mode' => 'live',
                ],
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'user_id' => 1,
                ],
            ],
        ];
        $document = $this->mongo_db->customQuery();
        $atg_users = $document->auto_trade_settings_kraken->aggregate($pipeline);
        $atg_users = iterator_to_array($atg_users);
        if (!empty($atg_users)) {
            $total_atg_users = count($atg_users);
            for ($i = 0; $i < $total_atg_users; $i++) {
                $user_ids[] = $this->mongo_db->mongoId($atg_users[$i]['user_id']);
            }
        }

        $last_run = date('Y-m-d H:i:s', strtotime('-1 days'));
        $pipeline = [
            [
                '$match' => [
                    '_id' => ['$in' => $user_ids],
                    'application_mode' => 'both',
                    //'is_api_key_valid'=>'yes',
                    'account_block' => ['$ne' => 'yes'],
                    // 'atg_parents_update_cron_last_run_kraken' => ['$lte' => $this->mongo_db->converToMongodttime($last_run)],
                    '$or' => [
                        ['atg_parents_update_cron_last_run_kraken'=> ['$exists' => false]],
                        ['atg_parents_update_cron_last_run_kraken' => ['$lte' => $this->mongo_db->converToMongodttime($last_run)]],
                    ],
                ],
            ],
            [
                '$sort' => [
                    'atg_parents_update_cron_last_run_kraken' => 1 
                ]
            ],
            [
                '$project' => [
                    '_id' => 1,
                    'username' => 1
                ],
            ],
            [
                '$limit' => 1,
            ],
        ];
        $document = $this->mongo_db->customQuery();
        $atg_users = $document->users->aggregate($pipeline);
        $atg_users = iterator_to_array($atg_users);


        if (!empty($atg_users)) {
            $total_users = count($atg_users);
            for ($i = 0;$i < $total_users; $i++) {

                if(empty($atg_users[$i]['_id']) && $i != 0){
                    continue;
                }

                $userId = (string) $atg_users[$i]['_id'];
                $username = (string) $atg_users[$i]['username'];
                $token = $this->Mod_jwt->LoginToken($userId, $username);

                $params = [
                    // "user_id" => "5c0912b7fc9aadaac61dd072",
                    "user_id" => (string) $atg_users[$i]['_id'],
                    "exchange" => "kraken",
                    "application_mode" => "live",
                    // "symbol" => "QTUMBTC",
                ];
                $req_arr = [
                    'req_type' => 'POST',
                    'req_params' => $params,
                    // 'req_endpoint' => 'updateDailyTradeSettings',
                    'req_endpoint' => 'updateDailyTradeSettings_digie',
                    'header' => $token
                    // 'req_url' => '',
                ];
                $resp = hitCurlRequest($req_arr);
                echo "<pre>";print_r($resp);

                //update last cron run time
                $curr_time = date('Y-m-d H:i:s');
                $where1 = ['_id' => $atg_users[$i]['_id']];
                $upd['$set'] = [
                    'atg_parents_update_cron_last_run_kraken' => $this->mongo_db->converToMongodttime($curr_time),
                ];
                $db = $this->mongo_db->customQuery();
                $db->users->updateOne($where1, $upd);

                //sleep for 30 minutes and then execute for next user
                // sleep(30);

            }
        }

        //Save last Cron Executioon
        $this->last_cron_execution_time('update_auto_trade_usd_worth_and_tradeable_balance_kraken', '7m', 'Cronjob to update usd_worth and tradeable balance for Auto trade module for kraken (*/7 * * * *)', 'updateQtyUSDWorth');


        echo "<br> ************** Script End *************** <br>";

    }//end update_auto_trade_usd_worth_and_tradeable_balance kraken

    //binance
    public function update_auto_trade_actual_tradeable_balance(){
       
        $user_ids = [];
        $pipeline = [
            [
                '$match' => [
                    'application_mode' => 'live',
                    //'user_id'=>'5eb5a5a628914a45246bacc6',
                ],
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'user_id' => 1,
                ],
            ],
        ];
        $document = $this->mongo_db->customQuery();
        $atg_users = $document->auto_trade_settings->aggregate($pipeline);
        $atg_users = iterator_to_array($atg_users);
       
        if (!empty($atg_users)) {
            $total_atg_users = count($atg_users);
            for ($i = 0; $i < $total_atg_users; $i++) {
                $user_ids[] = (string) $atg_users[$i]['user_id'];
            }
            unset($atg_users);
        }

        if (!empty($user_ids)) {
            $total_users = count($user_ids);
            for ($i = 0;$i < $total_users; $i++) {
                $params = [
                    // "user_id" => "5c0912b7fc9aadaac61dd072",
                    "user_id" => $user_ids[$i],
                    "exchange" => "binance",
                    "application_mode" => "live",
                    // "symbol" => "QTUMBTC",
                ];

                $tokenSend = $this->Mod_jwt->LoginToken($user_ids[$i], 'updateDailyActualTradeAbleAutoTradeGen');
                $req_arr = [
                    'req_type' => 'POST',
                    'req_params' => $params,
                    'req_endpoint' => 'updateDailyActualTradeAbleAutoTradeGen',
                    'header'    =>  $tokenSend
                    // 'req_url' => '',
                ];
                $resp = hitCurlRequest($req_arr);
                echo "<pre>";
                print_r($resp);
                // echo "<pre>";print_r($resp);
                if ($i == 0) {
                    //Save last Cron Executioon
                    $this->last_cron_execution_time('update_auto_trade_actual_tradeable_balance', '1d', 'Cronjob to update actual tradeable balance for Auto trade module for binance (0 5 * * *)' , 'updateQtyUSDWorth');
                }

                //sleep for 5 seconds and then execute for next user
                sleep(5);

            }
        }

    }//end update_auto_trade_actual_tradeable_balance binance
    
    //bam
    public function update_auto_trade_actual_tradeable_balance_bam(){
        
        $user_ids = [];
        $pipeline = [
            [
                '$match' => [
                    'application_mode' => 'live',
                ],
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'user_id' => 1,
                ],
            ],
        ];
        $document = $this->mongo_db->customQuery();
        $atg_users = $document->auto_trade_settings_bam->aggregate($pipeline);
        $atg_users = iterator_to_array($atg_users);
        if (!empty($atg_users)) {
            $total_atg_users = count($atg_users);
            for ($i = 0; $i < $total_atg_users; $i++) {
                $user_ids[] = (string) $atg_users[$i]['user_id'];
            }
            unset($atg_users);
        }   

        if (!empty($user_ids)) {
            $total_users = count($user_ids);
            for ($i = 0;$i < $total_users; $i++) {
                $params = [
                    // "user_id" => "5c0912b7fc9aadaac61dd072",
                    "user_id" => $user_ids[$i],
                    "exchange" => "bam",
                    "application_mode" => "live",
                    // "symbol" => "QTUMBTC",
                ];
                $tokenSend = $this->Mod_jwt->LoginToken($user_ids[$i], 'updateDailyActualTradeAbleAutoTradeGen');
                $req_arr = [
                    'req_type' => 'POST',
                    'req_params' => $params,
                    'req_endpoint' => 'updateDailyActualTradeAbleAutoTradeGen',
                    'header'   =>  $tokenSend
                ];
                $resp = hitCurlRequest($req_arr);

                if ($i == 0) {
                    //Save last Cron Executioon
                    $this->last_cron_execution_time('update_auto_trade_actual_tradeable_balance_bam', '1d', 'Cronjob to update actual tradeable balance for Auto trade module for bam (30 5 * * *)', 'updateQtyUSDWorth');
                }

                //sleep for 5 seconds and then execute for next user
                sleep(5);

            }
        }

    }//end update_auto_trade_actual_tradeable_balance bam

    //kraken
    public function update_auto_trade_actual_tradeable_balance_kraken(){
        
        $user_ids = [];
        $pipeline = [
            [
                '$match' => [
                    'application_mode' => 'live',
                ],
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'user_id' => 1,
                ],
            ],
        ];
        $document = $this->mongo_db->customQuery();
        $atg_users = $document->auto_trade_settings_kraken->aggregate($pipeline);
        $atg_users = iterator_to_array($atg_users);
        if (!empty($atg_users)) {
            $total_atg_users = count($atg_users);
            for ($i = 0; $i < $total_atg_users; $i++) {
                $user_ids[] = (string) $atg_users[$i]['user_id'];
            }
            unset($atg_users);
        }

        if (!empty($user_ids)) {
            $total_users = count($user_ids);
            for ($i = 0;$i < $total_users; $i++) {
                $params = [
                    // "user_id" => "5c0912b7fc9aadaac61dd072",
                    "user_id" => $user_ids[$i],
                    "exchange" => "kraken",
                    "application_mode" => "live",
                    // "symbol" => "QTUMBTC",
                ];
                $tokenSend = $this->Mod_jwt->LoginToken($user_ids[$i], 'updateDailyActualTradeAbleAutoTradeGen');
                $req_arr = [
                    'req_type' => 'POST',
                    'req_params' => $params,
                    'req_endpoint' => 'updateDailyActualTradeAbleAutoTradeGen',
                    // 'req_url' => '',
                    'header'   => $tokenSend
                ];
                $resp = hitCurlRequest($req_arr);

                if ($i == 0) {
                    //Save last Cron Executioon
                    $this->last_cron_execution_time('update_auto_trade_actual_tradeable_balance_kraken', '1d', 'Cronjob to update actual tradeable balance for Auto trade module for kraken (45 5 * * *)', 'updateQtyUSDWorth');
                }

                //sleep for 5 seconds and then execute for next user
                sleep(5);

            }
        }

    }//end update_auto_trade_actual_tradeable_balance kraken

    //get_live_user_ids
    public function get_live_user_ids(){

        $this->mongo_db->where(['status' => '0', 'user_soft_delete' => '0', 'application_mode' => 'both']);
        $result = $this->mongo_db->get('users');
        $users = iterator_to_array($result);
        if(!empty($users)){
            $user_ids = array_column($users, '_id');
            unset($result, $users);
            $total_users = count($user_ids); 
            for($i =0; $i<$total_users; $i++){
                $user_ids[$i] = (string) $user_ids[$i]; 
            }
            return $user_ids;
        }
        return [];
    }//end get_live_user_ids

    //get_user_daily_buy_limit_in_usd
    public function get_user_daily_buy_limit_in_usd($user_id, $exchange){
        /* Hassan Has Changed The Below Code  */
        $user_id = (string) $user_id;
        
        if($_SERVER['REMOTE_ADDR'] == '203.99.181.17' || $_SERVER['REMOTE_ADDR'] == '172.31.49.152')
        {
            echo 'heeeelo';
        }
        $atg_settings_collection = ($exchange == "binance" ? "auto_trade_settings" : "auto_trade_settings_$exchange");
        $this->mongo_db->where(['user_id' => $user_id, 'application_mode' => 'live']);
        $this->mongo_db->limit(1);
        $result2 = $this->mongo_db->get($atg_settings_collection);
        $settingsArr = iterator_to_array($result2);
        if(!empty($settingsArr)){
            if(!empty($settingsArr[0]['step_4']['baseCurrencyArr']) && !empty($settingsArr[0]['step_4']['customBtcPackage']) && !empty($settingsArr[0]['step_4']['customUsdtPackage'])  && !empty($settingsArr[0]['step_4']['dailTradeAbleBalancePercentage'])){
                $params = [
                    'user_id' => $user_id,
                    'exchange' => $exchange,
                    'baseCurrencyArr' => $settingsArr[0]['step_4']['baseCurrencyArr'],
                    'customBtcPackage' => $settingsArr[0]['step_4']['customBtcPackage'],
                    'customUsdtPackage' => $settingsArr[0]['step_4']['customUsdtPackage'],
                    'dailTradeAbleBalancePercentage' => $settingsArr[0]['step_4']['dailTradeAbleBalancePercentage'],  
                ];
                // echo '<pre>';
                $daily_limit_arr = $this->calculate_daily_tradeable_usd_new2($user_id, $exchange,1,$params);
                return $daily_limit_arr;
            }
            else
            {
                // settings exists but we have not found either of the one in comma separated values baseCurrencyArr,customBtcPackage,customUsdtPackage,dailTradeAbleBalancePercentage
                $daily_limit_arr = [
                   'daily_bought_btc_usd_worth' => 0,
                    'BTCTradesTodayCount' => 0,
                    'daily_bought_usdt_usd_worth' => 0,
                    'USDTTradesTodayCount' => 0,
                    'dailyTradeableBTC' => 0,
                    'dailyTradeableUSDT' => 0,
                    'dailyTradeableBTC_usd_worth' => 0,
                    'dailyTradeableUSDT_usd_worth' => 0,
                    'daily_available_usd' => 0,
                ];
                return $daily_limit_arr;

            }
            

        }
        else
        {
            $daily_limit_arr = $this->calculate_daily_tradeable_usd_new2($user_id, $exchange,0,null); // it means to use default package value 
            return $daily_limit_arr;      
        }

        return true;
        /* Hassan Code Ends here ....*/
        //get BTCUSDT price
        // $price_collection = ($exchange == "binance" ? "market_prices" : "market_prices_$exchange"); 
        // $this->mongo_db->where(['coin' => 'BTCUSDT']);
        // $this->mongo_db->sort(['created_date' => -1]);
        // $this->mongo_db->limit(1);
        // $result = $this->mongo_db->get($price_collection);
        // $priceArr = iterator_to_array($result);

        // if(!empty($priceArr)){
        //     $BTCUSDT_price = (float) $priceArr[0]['price'];

        //     //get user ATG settings
        //     $atg_settings_collection = ($exchange == "binance" ? "auto_trade_settings" : "auto_trade_settings_$exchange");
        //     $this->mongo_db->where(['user_id' => $user_id, 'application_mode' => 'live']);
        //     $this->mongo_db->limit(1);
        //     $result2 = $this->mongo_db->get($atg_settings_collection);
        //     $settingsArr = iterator_to_array($result2);
            
        //     $dail_buy_usd_worth = 0;
        //     $dailyTradeableBTC = 0;
        //     $dailyTradeableUSDT = 0;
        //     $dailyTradeableBTC_usd_worth = 0;
        //     $dailyTradeableUSDT_usd_worth = 0;
        //     echo "settings ARR  <pre>";
        //     print_r($settingsArr);
        //     if(!empty($settingsArr)){
        //         if(!empty($settingsArr[0]['step_4']['dailyTradeableUSDT'])){
        //             $dailyTradeableUSDT = (float) $settingsArr[0]['step_4']['dailyTradeableUSDT'];

        //             $dailyLimitArr = [
        //                 'dailyTradeableBTC' => $dailyTradeableBTC,
        //                 'dailyTradeableUSDT' => $dailyTradeableUSDT,
        //             ];
        //             echo 'daily Limit ARR<pre>';
        //             print_r($dailyLimitArr);
        //             $tempData = $this->dailyLimitAdjust($user_id, $dailyLimitArr, $exchange);
        //             $dailyTradeableUSDT = $tempData['dailyTradeableUSDT'];
        //              echo 'daily BTC Limit ARR<pre>';
        //             print_r($tempData);
        //             //set min daily trade if daily trade is less than $10
        //             if ($dailyTradeableUSDT != 0 && $dailyTradeableUSDT < 10) {
        //                 $dailyTradeableUSDT = 15;
        //             }
        //             $dailyTradeableUSDT_usd_worth = $dailyTradeableUSDT; 
        //             $dail_buy_usd_worth += $dailyTradeableUSDT;
        //         }
        //         if(!empty($settingsArr[0]['step_4']['dailyTradeableBTC'])){
        //             $dailyTradeableBTC = (float) $settingsArr[0]['step_4']['dailyTradeableBTC'];

        //              $dailyLimitArr = [
        //                 'dailyTradeableBTC' => $dailyTradeableBTC,
        //                 'dailyTradeableUSDT' => $dailyTradeableUSDT,
        //             ];
        //             $tempData = $this->dailyLimitAdjust($user_id, $dailyLimitArr, $exchange);
        //              echo 'daily USDT Limit ARR<pre>';
        //              print_r($dailyLimitArr);
        //             print_r($tempData);
        //             $dailyTradeableBTC = $tempData['dailyTradeableBTC'];

        //             //set min daily trade if daily trade is less than $10
        //             if ($dailyTradeableBTC != 0 && ($dailyTradeableBTC * $BTCUSDT_price) < 10) {
        //                 $dailyTradeableBTC = $exchange == 'kraken' ? 15 / $BTCUSDT_price : 10 / $BTCUSDT_price;
        //             }
        //             $dailyTradeableBTC_usd_worth = $dailyTradeableBTC * $BTCUSDT_price;
        //             $dail_buy_usd_worth += $dailyTradeableBTC*$BTCUSDT_price;
        //         }

        //         $dail_buy_usd_worth = $dailyTradeableBTC_usd_worth + $dailyTradeableUSDT_usd_worth;

        //         $resArr = [
        //             'daily_bought_btc_usd_worth' => 0,
        //             'BTCTradesTodayCount' => 0,
        //             'daily_bought_usdt_usd_worth' => 0,
        //             'USDTTradesTodayCount' => 0,
        //             'dailyTradeableBTC' => $dailyTradeableBTC,
        //             'dailyTradeableUSDT' => $dailyTradeableUSDT,
        //             'dailyTradeableBTC_usd_worth' => $dailyTradeableBTC_usd_worth,
        //             'dailyTradeableUSDT_usd_worth' => $dailyTradeableUSDT_usd_worth,
        //             'daily_available_usd' => $dail_buy_usd_worth,
        //         ];

        //         //add 10% extra
        //         // if($dail_buy_usd_worth != 0){
        //         //     $dail_buy_usd_worth= $dail_buy_usd_worth + ((10 * $dail_buy_usd_worth) / 100);
        //         // }
        //         unset($result2, $settingsArr);
        //         // return $dail_buy_usd_worth;

        //         return $resArr;
        //     }else{

        //         //call new function to calculate from new formula
        //         // $daily_available_usd = $this->calculate_daily_tradeable_usd($user_id, $exchange);
        //         // return $daily_available_usd;
                
        //         // $daily_limit_arr = $this->calculate_daily_tradeable_usd_new($user_id, $exchange);

        //         $daily_limit_arr = $this->calculate_daily_tradeable_usd_new2($user_id, $exchange);
        //         return $daily_limit_arr;
        //     }
        // }
        
        // unset($result, $priceArr);
        // return 200;
    }//end get_user_daily_buy_limit_in_usd

    //calculate_daily_tradeable_usd
    public function calculate_daily_tradeable_usd($user_id, $exchange){

        $pricesArr = get_current_market_prices($exchange, ['BTCUSDT']);
        $data = $this->get_dashboard_wallet($user_id, $exchange);

        //TODO: find default daily limit with 15% according to his trade package and available balance
        $btc_percent = 30;
        $usdt_percent = 70;
        $daily_tradeable = 10;

        $max_btc_tradeable = 0;
        $max_usdt_tradeable = 0;

        //TODO 1: find user package
        $usd_package_limit = $this->get_user_package_limit($user_id);
        $tradeLimit = $usd_package_limit;
        
        // echo "<pre>";
        
        if($data){

            $balanceArr = $data;
            unset($data);
            $walletBalanceArr = $balanceArr['avaiableBalance'];
            $tempBalanceObj = array_column($walletBalanceArr, 'coin_balance', 'coin_symbol');
    
            // print_r($tempBalanceObj);
            $availableBTC = $tempBalanceObj['BTC'];
            $availableUSDT = $tempBalanceObj['USDT'];
            $availableBNB = $tempBalanceObj['BNB'];
    
            $totalAvailableBalanceForPackageSelection = ((($tempBalanceObj['BTC'] * $pricesArr['BTCUSDT']) + $tempBalanceObj['USDT']) + $balanceArr['openBalance']['OpenUsdWorth'] + $balanceArr['lthBalance']['LthUsdWorth'] + $balanceArr['costAvgBalance']['costAvgUsdWorth']) - $balanceArr['openLthBTCUSDTBalance']['OpenLTHUsdWorth'];
    
    
            // $btcInTrades = ($balanceArr['openBalance']['onlyBtc'] + $balanceArr['lthBalance']['onlyBtc'] + $balanceArr['costAvgBalance']['onlyBtc']) - $balanceArr['openLthBTCUSDTBalance']['onlyBtc']
            // $usdtInTrades = ($balanceArr['openBalance']['onlyUsdt'] + $balanceArr['lthBalance']['onlyUsdt'] + $balanceArr['costAvgBalance']['onlyUsdt']) - $balanceArr['openLthBTCUSDTBalance']['onlyUsdt'];
    
    
            $usedUsdWorthInTrades = $balanceArr['openBalance']['OpenUsdWorth'] + $balanceArr['lthBalance']['LthUsdWorth'] + $balanceArr['costAvgBalance']['costAvgUsdWorth'] + $balanceArr['openLthBTCUSDTBalance']['OpenLTHUsdWorth'];
    
            $remainaingUsdWorthForTrading = (($tempBalanceObj['BTC'] * $pricesArr['BTCUSDT']) + $tempBalanceObj['USDT']) - $balanceArr['openLthBTCUSDTBalance']['OpenLTHUsdWorth'];
    
            //    echo '<br> wallet  '. ((($tempBalanceObj['BTC']) * $pricesArr['BTCUSDT']) + $tempBalanceObj['USDT']). ' wallet + used '. $totalAvailableBalanceForPackageSelection . ' only used '. $usedUsdWorthInTrades;
    
            if ($user_id == '5c0912b7fc9aadaac61dd072') {
                // $availableBTC = 0.5;
                // $availableUSDT = 2000;
            }
    
            if ($tradeLimit <= $totalAvailableBalanceForPackageSelection){
    
                // $totalTradeAbleInUSD = ($btcInTrades * $pricesArr['BTCUSDT']) >= $tradeLimit ? 0 : (($tradeLimit / $pricesArr['BTCUSDT']) - $btcInTrades) * $pricesArr['BTCUSDT'];
    
                $remainingTradddeeAble = $tradeLimit - $usedUsdWorthInTrades > 0 ? $tradeLimit - $usedUsdWorthInTrades : 0;
    
                // echo('<br> 11111111 remainingTradddeeAble '. $remainingTradddeeAble);
    
                $availableBTC = ($remainingTradddeeAble / $pricesArr['BTCUSDT']) > $tempBalanceObj['BTC'] ? $tempBalanceObj['BTC'] : ($remainingTradddeeAble / $pricesArr['BTCUSDT']);
    
                $availableUSDT = $remainingTradddeeAble > $tempBalanceObj['USDT'] ? $tempBalanceObj['USDT'] : $remainingTradddeeAble;
                
                // $totalTradeAbleInUSD = $tradeLimit - usedUsdWorthInTrades > 0 ? $tradeLimit - usedUsdWorthInTrades : 0;
                
                if($remainingTradddeeAble <= 0){
                    // this.toastr.error('Your package has been exceed please upgrade to a bigger package', 'ERROR');
                }
    
            }else{
    
                $trraadeable = $remainaingUsdWorthForTrading >= $tradeLimit ? $tradeLimit : $remainaingUsdWorthForTrading; 
    
                $remainingTradddeeAble = $trraadeable - $usedUsdWorthInTrades > 0 ? $trraadeable - $usedUsdWorthInTrades : 0;
    
                // echo('<br> 222222222 remainingTradddeeAble '. $remainingTradddeeAble);
    
                // $availableBTC = ($remainingTradddeeAble / $pricesArr['BTCUSDT']) > $tempBalanceObj['BTC'] ? $tempBalanceObj['BTC'] : ($remainingTradddeeAble / $pricesArr['BTCUSDT']);
    
                // echo('<br> ------------------------ '. $availableBTC);
    
                // $availableUSDT = $remainingTradddeeAble > $tempBalanceObj['USDT'] ? $tempBalanceObj['USDT'] : remainingTradddeeAble;
    
                // $totalTradeAbleInUSD =  
            }


            //  Calculate daily usd limit  
            $max_usdt_tradeable = (($usdt_percent * $usd_package_limit) / 100);
            $max_btc_tradeable_usd = (($btc_percent * $usd_package_limit) / 100);
            $max_btc_tradeable = (1 / $pricesArr['BTCUSDT']) * $max_btc_tradeable_usd;

            //TODO 2: get user balance
            // $btcUsdt_balance_arr = $this->get_user_current_available_balance($user_id, $exchange);
            $btcUsdt_balance_arr['BTC'] = $availableBTC;
            $btcUsdt_balance_arr['USDT'] = $availableUSDT;

            $available_tradeable_btc = $btcUsdt_balance_arr['BTC'] > $max_btc_tradeable ? (float) $max_btc_tradeable : (float) $btcUsdt_balance_arr['BTC'];
            $available_tradeable_usdt = $btcUsdt_balance_arr['USDT'] > $max_usdt_tradeable ? (float) $max_usdt_tradeable : (float) $btcUsdt_balance_arr['USDT'];

            //TODO 3: find daily tradeable balance
            $daily_btc = ($daily_tradeable * $available_tradeable_btc) / 100;
            $daily_usdt = ($daily_tradeable * $available_tradeable_usdt) / 100;

            $daily_available_usd = ($pricesArr['BTCUSDT'] * (float) $daily_btc) + (float) $daily_usdt;

            // echo "<br> $daily_btc + $daily_usdt  = $daily_available_usd ($usd_package_limit)";

            //add 10% extra
            if ($daily_available_usd != 0) {
                $daily_available_usd = $daily_available_usd + ((10 * $daily_available_usd) / 100);
            }

            // echo "<br> new code daily Limit is $daily_available_usd";
            return $daily_available_usd;

        }else{

            $max_usdt_tradeable = (($usdt_percent * $usd_package_limit) / 100);
    
            $max_btc_tradeable_usd = (($btc_percent * $usd_package_limit) / 100);
            $max_btc_tradeable = (1 / $pricesArr['BTCUSDT']) * $max_btc_tradeable_usd;
    
            //TODO 2: get user balance
            $btcUsdt_balance_arr = $this->get_user_current_available_balance($user_id, $exchange);
    
            $available_tradeable_btc = $btcUsdt_balance_arr['BTC'] > $max_btc_tradeable ? (float) $max_btc_tradeable : (float) $btcUsdt_balance_arr['BTC'];
            $available_tradeable_usdt = $btcUsdt_balance_arr['USDT'] > $max_usdt_tradeable ? (float) $max_usdt_tradeable : (float) $btcUsdt_balance_arr['USDT'];
    
            //TODO 3: find daily tradeable balance
            $daily_btc = ($daily_tradeable * $available_tradeable_btc) / 100;
            $daily_usdt = ($daily_tradeable * $available_tradeable_usdt) / 100;
    
            $daily_available_usd = ($pricesArr['BTCUSDT'] * (float) $daily_btc) + (float) $daily_usdt;
    
            // echo "<br> $daily_btc + $daily_usdt  = $daily_available_usd ($usd_package_limit)";
    
            //add 10% extra
            if ($daily_available_usd != 0) {
                $daily_available_usd = $daily_available_usd + ((10 * $daily_available_usd) / 100);
            }
    
            // echo "<br> old code daily Limit is $daily_available_usd";
            return $daily_available_usd;
            
        }

    } //end calculate_daily_tradeable_usd

    //calculate_daily_tradeable_usd_new
    public function calculate_daily_tradeable_usd_new($user_id, $exchange)
    {
        // echo "<pre>";
        $pricesArr = get_current_market_prices($exchange, ['BTCUSDT']);
        $data = $this->get_dashboard_wallet($user_id, $exchange);

        //TODO: find default daily limit with 15% according to his trade package and available balance
        $btc_percent = 30;
        $usdt_percent = 70;
        $daily_tradeable = 10;

        $max_btc_tradeable = 0;
        $max_usdt_tradeable = 0;

        //TODO 1: find user package
        $usd_package_limit = $this->get_user_package_limit($user_id);
        $tradeLimit = $usd_package_limit;

        // echo "<pre>";

        if ($data) {

            $balanceArr = $data;
            unset($data);
            $walletBalanceArr = $balanceArr['avaiableBalance'];
            $tempBalanceObj = array_column($walletBalanceArr, 'coin_balance', 'coin_symbol');

            // print_r($tempBalanceObj);
            $availableBTC = $tempBalanceObj['BTC'];
            $availableUSDT = $tempBalanceObj['USDT'];
            $availableBNB = $tempBalanceObj['BNB'];

            $totalAvailableBalanceForPackageSelection = ((($tempBalanceObj['BTC'] * $pricesArr['BTCUSDT']) + $tempBalanceObj['USDT']) + $balanceArr['openBalance']['OpenUsdWorth'] + $balanceArr['lthBalance']['LthUsdWorth'] + $balanceArr['costAvgBalance']['costAvgUsdWorth']) - $balanceArr['openLthBTCUSDTBalance']['OpenLTHUsdWorth'];


            // $btcInTrades = ($balanceArr['openBalance']['onlyBtc'] + $balanceArr['lthBalance']['onlyBtc'] + $balanceArr['costAvgBalance']['onlyBtc']) - $balanceArr['openLthBTCUSDTBalance']['onlyBtc']
            // $usdtInTrades = ($balanceArr['openBalance']['onlyUsdt'] + $balanceArr['lthBalance']['onlyUsdt'] + $balanceArr['costAvgBalance']['onlyUsdt']) - $balanceArr['openLthBTCUSDTBalance']['onlyUsdt'];


            $usedUsdWorthInTrades = $balanceArr['openBalance']['OpenUsdWorth'] + $balanceArr['lthBalance']['LthUsdWorth'] + $balanceArr['costAvgBalance']['costAvgUsdWorth'] + $balanceArr['openLthBTCUSDTBalance']['OpenLTHUsdWorth'];

            $remainaingUsdWorthForTrading = (($tempBalanceObj['BTC'] * $pricesArr['BTCUSDT']) + $tempBalanceObj['USDT']) - $balanceArr['openLthBTCUSDTBalance']['OpenLTHUsdWorth'];

            //    echo '<br> wallet  '. ((($tempBalanceObj['BTC']) * $pricesArr['BTCUSDT']) + $tempBalanceObj['USDT']). ' wallet + used '. $totalAvailableBalanceForPackageSelection . ' only used '. $usedUsdWorthInTrades;

            if ($user_id == '5c0912b7fc9aadaac61dd072') {
                // $availableBTC = 0.5;
                // $availableUSDT = 2000;
            }

            if ($tradeLimit <= $totalAvailableBalanceForPackageSelection) {

                // echo('Package is less than total balance  ------------------------------------ \r\n');
                $_70percentOfTotal = (70 * $tradeLimit) / 100;
                // echo('_70percentOfTotal   '. $_70percentOfTotal. ' -------------    usedUsdWorthInTrades '. $usedUsdWorthInTrades);

                if ($_70percentOfTotal > $usedUsdWorthInTrades) {
                    $remainingTradddeeAble = (($tradeLimit - $_70percentOfTotal) > 0) ? ($tradeLimit - $_70percentOfTotal) : 0;
                } else {
                    $remainingTradddeeAble = (($tradeLimit - $usedUsdWorthInTrades) > 0) ? ($tradeLimit - $usedUsdWorthInTrades) : 0;
                }
                // echo('after 70% check remainingTradddeeAble '. $remainingTradddeeAble);   

                // $totalTradeAbleInUSD = ($btcInTrades * $pricesArr['BTCUSDT']) >= $tradeLimit ? 0 : (($tradeLimit / $pricesArr['BTCUSDT']) - $btcInTrades) * $pricesArr['BTCUSDT'];

                // $remainingTradddeeAble = $tradeLimit - $usedUsdWorthInTrades > 0 ? $tradeLimit - $usedUsdWorthInTrades : 0;

                // echo('<br> 11111111 remainingTradddeeAble '. $remainingTradddeeAble);

                $availableBTC = ($remainingTradddeeAble / $pricesArr['BTCUSDT']) > $tempBalanceObj['BTC'] ? $tempBalanceObj['BTC'] : ($remainingTradddeeAble / $pricesArr['BTCUSDT']);

                $availableUSDT = $remainingTradddeeAble > $tempBalanceObj['USDT'] ? $tempBalanceObj['USDT'] : $remainingTradddeeAble;

                // $totalTradeAbleInUSD = $tradeLimit - usedUsdWorthInTrades > 0 ? $tradeLimit - usedUsdWorthInTrades : 0;

                if ($remainingTradddeeAble <= 0) {
                    // this.toastr.error('Your package has been exceed please upgrade to a bigger package', 'ERROR');
                }
            } else {

                // echo('Package is GREATER than total balance  ------------------------------------ ');

                $_70percentOfTotal = (70 * $tradeLimit) / 100;
                // echo('_70percentOfTotal   '. $_70percentOfTotal. ' -------------    usedUsdWorthInTrades ' .$usedUsdWorthInTrades);

                if ($_70percentOfTotal > $usedUsdWorthInTrades) {
                    $trraadeable = (($tradeLimit - $_70percentOfTotal) > 0) ? ($tradeLimit - $_70percentOfTotal) : 0 ;
                } else {
                    $trraadeable = (($tradeLimit - $usedUsdWorthInTrades) > 0) ? ($tradeLimit - $usedUsdWorthInTrades) : 0;
                }
                // echo('after 70% check remainingTradddeeAble'. $trraadeable);

                $trraadeable = $remainaingUsdWorthForTrading >= $tradeLimit ? $tradeLimit : $remainaingUsdWorthForTrading;

                $remainingTradddeeAble = $trraadeable - $usedUsdWorthInTrades > 0 ? $trraadeable - $usedUsdWorthInTrades : 0;

                // echo('<br> 222222222 remainingTradddeeAble '. $remainingTradddeeAble);

                // $availableBTC = ($remainingTradddeeAble / $pricesArr['BTCUSDT']) > $tempBalanceObj['BTC'] ? $tempBalanceObj['BTC'] : ($remainingTradddeeAble / $pricesArr['BTCUSDT']);

                // echo('<br> ------------------------ '. $availableBTC);

                // $availableUSDT = $remainingTradddeeAble > $tempBalanceObj['USDT'] ? $tempBalanceObj['USDT'] : remainingTradddeeAble;

                // $totalTradeAbleInUSD =  
            }


            //  Calculate daily usd limit  
            $max_usdt_tradeable = (($usdt_percent * $usd_package_limit) / 100);
            $max_btc_tradeable_usd = (($btc_percent * $usd_package_limit) / 100);
            $max_btc_tradeable = (1 / $pricesArr['BTCUSDT']) * $max_btc_tradeable_usd;

            //TODO 2: get user balance
            // $btcUsdt_balance_arr = $this->get_user_current_available_balance($user_id, $exchange);
            $btcUsdt_balance_arr['BTC'] = $availableBTC;
            $btcUsdt_balance_arr['USDT'] = $availableUSDT;

            $available_tradeable_btc = $btcUsdt_balance_arr['BTC'] > $max_btc_tradeable ? (float) $max_btc_tradeable : (float) $btcUsdt_balance_arr['BTC'];
            $available_tradeable_usdt = $btcUsdt_balance_arr['USDT'] > $max_usdt_tradeable ? (float) $max_usdt_tradeable : (float) $btcUsdt_balance_arr['USDT'];

            //TODO 3: find daily tradeable balance
            $daily_btc = ($daily_tradeable * $available_tradeable_btc) / 100;
            $daily_usdt = ($daily_tradeable * $available_tradeable_usdt) / 100;

            $daily_available_usd = ($pricesArr['BTCUSDT'] * (float) $daily_btc) + (float) $daily_usdt;

            // echo "<br> $daily_btc + $daily_usdt  = $daily_available_usd ($usd_package_limit)";

            // //add 10% extra
            // if ($daily_available_usd != 0) {
            //     $daily_available_usd = $daily_available_usd + ((10 * $daily_available_usd) / 100);
            // }

            // echo "<br> new code daily Limit is $daily_available_usd";
            // return $daily_available_usd;


            //************ New Algo ***************/
            $dailTradeAbleBalancePercentage = $daily_tradeable;
            $baseCurrencyArr = ['BTC', 'USDT'];
            //package exceed check for BTC
            if (($availableBTC * $pricesArr['BTCUSDT']) > $tradeLimit) {
                $availableBTC = $tradeLimit * (1 / $pricesArr['BTCUSDT']);
            }

            //package exceed check for USDT
            if ($availableUSDT > $tradeLimit) {
                $availableUSDT = $tradeLimit;
            }

            //check currency selection
            if (!in_array('BTC', $baseCurrencyArr) && !in_array('USDT', $baseCurrencyArr)) {
                // use default
            } else if (!in_array('BTC', $baseCurrencyArr) || !in_array('USDT', $baseCurrencyArr)) {
                if (!in_array('BTC', $baseCurrencyArr)) {
                    $availableBTC = 0;
                }
                if (!in_array('USDT', $baseCurrencyArr)) {
                    $availableUSDT = 0;
                }
            }

            $availableBtcUsdWorth = $availableBTC * $pricesArr['BTCUSDT'];
            $availableUsdtUsdWorth = $availableUSDT;

            // echo ("available BTC ::  $availableBTC  -------  available USDT ::  $availableUSDT");

            // echo ("availableBtcUsdWorth ::  $availableBtcUsdWorth  -------  availableUsdtUsdWorth ::  $availableUsdtUsdWorth");

            // when both BTC and USDT is greater or equal to package
            if ($availableBtcUsdWorth >= $tradeLimit && $availableUsdtUsdWorth >= $tradeLimit) {
                // echo ('// when both BTC and USDT is greater or equal to package');

                $btcPercentage = 50;
                $usdtPercentage = 50;

                $tradeableBtc = ($tradeLimit / 2) * (1 / $pricesArr['BTCUSDT']);
                $tradeableUsdt = ($tradeLimit / 2);

                // when combined BTC and USDT balance are greater than package 
            } else if (($availableBtcUsdWorth + $availableUsdtUsdWorth) >= $tradeLimit) {
                // echo ('// when combined BTC and USDT balance are greater than package');

                // BTC alone is greater than package
                if ($availableBtcUsdWorth >= $tradeLimit) {
                    // echo ('// BTC alone is greater than package');

                    //if smaller balance is greater than 50% then use 50 / 50 
                    if ((($availableUsdtUsdWorth / $tradeLimit) * 100) >= 50) {
                        // echo ('//if smaller balance is greater than 50% then use 50 / 50');

                        $btcPercentage = 50;
                        $usdtPercentage = 50;

                        $tradeableBtc = ($btcPercentage * $availableBTC) / 100;
                        $tradeableUsdt = ($usdtPercentage * $availableUSDT) / 100;

                        // find smaller percentage and assign that to that currency and assig the menaing to the other currency
                    } else {
                        // echo ('// find smaller percentage and assign that to that currency and assig the menaing to the other currency');

                        $usdtPercentage = (($availableUsdtUsdWorth / $tradeLimit) * 100);
                        $btcPercentage = 100 - $usdtPercentage;

                        $tradeableBtc = ($btcPercentage * $availableBTC) / 100;
                        $tradeableUsdt = ($usdtPercentage * $availableUSDT) / 100;
                    }

                    // USDT alone is greater than package
                } else if ($availableUsdtUsdWorth >= $tradeLimit) {
                    // echo ('// USDT alone is greater than package');

                    //if smaller balance is greater than 50% then use 50 / 50 
                    if ((($availableBtcUsdWorth / $tradeLimit) * 100) >= 50) {
                        // echo ('//if smaller balance is greater than 50% then use 50 / 50');

                        $btcPercentage = 50;
                        $usdtPercentage = 50;

                        $tradeableBtc = ($btcPercentage * $availableBTC) / 100;
                        $tradeableUsdt = ($usdtPercentage * $availableUSDT) / 100;

                        // find smaller percentage and assign that to that currency and assig the menaing to the other currency
                    } else {
                        // echo ('// find smaller percentage and assign that to that currency and assig the menaing to the other currency');

                        $btcPercentage = (($availableBtcUsdWorth / $tradeLimit) * 100);

                        // echo ("-----======== $btcPercentage");
                        $usdtPercentage = 100 - $btcPercentage;

                        $tradeableBtc = ($btcPercentage * $availableBTC) / 100;
                        $tradeableUsdt = ($usdtPercentage * $availableUSDT) / 100;
                    }

                    // BTC and USDT combined is greater than package
                } else {
                    // echo ('// BTC and USDT combined is greater than package');
                    //get percentage of one and assign the remaing percentage to the other
                    $btcPercentage = (($availableBtcUsdWorth / $tradeLimit) * 100);
                    $usdtPercentage = 100 - $btcPercentage;

                    $tradeableBtc = ($btcPercentage * $availableBTC) / 100;
                    $tradeableUsdt = ($usdtPercentage * $availableUSDT) / 100;
                }
            } else { // when combined BTC and USDT balance are less than package
                // echo ('// when combined BTC and USDT balance are less than package');

                // $availableBtcUsdWorth
                // $availableUsdtUsdWorth

                if (false && $availableBtcUsdWorth <= ($tradeLimit / 2) && $availableUsdtUsdWorth <= ($tradeLimit / 2)) {
                    // echo ('// when both BTC and USDT are less than or equal to 50% of the package');
                    //assign the percentage of the balances avalable for both BTC and USDT 
                    $btcPercentage = 100;
                    $usdtPercentage = 100;
                    $tradeableBtc = $availableBTC;
                    $tradeableUsdt = $availableUSDT;
                } else {
                    // echo ('// when both BTC and/or USDT are greater than 50% of the package');
                    //when BTC is greater than USDT balance
                    if ($availableBtcUsdWorth > $availableUsdtUsdWorth) {
                        // echo ('//when BTC is greater than USDT balance');

                        $usdtPercentage = (($availableUsdtUsdWorth / $tradeLimit) * 100);
                        $btcPercentage = 100 - $usdtPercentage;

                        $tradeableBtc = ($btcPercentage * $availableBTC) / 100;
                        $tradeableUsdt = ($usdtPercentage * $availableUSDT) / 100;
                    } else if ($availableBtcUsdWorth < $availableUsdtUsdWorth) {
                        // echo ('//when BTC is greater than USDT balance');

                        $btcPercentage = (($availableBtcUsdWorth / $tradeLimit) * 100);
                        $usdtPercentage = (100 - $btcPercentage);

                        $tradeableBtc = ($btcPercentage * $availableBTC) / 100;
                        $tradeableUsdt = ($usdtPercentage * $availableUSDT) / 100;
                    } else {
                        // echo ('//when BTC == USDT balance (usd worth)');

                        $btcPercentage = 100;
                        $usdtPercentage = 100;

                        $tradeableBtc = $availableBTC;
                        $tradeableUsdt = $availableUSDT;
                    }
                }
            }

            //check currency selection to set percentages
            if (!in_array('BTC', $baseCurrencyArr) || !in_array('USDT', $baseCurrencyArr)) {
                if (!in_array('BTC', $baseCurrencyArr)) {
                    $btcPercentage = 0;
                }
                if (!in_array('USDT', $baseCurrencyArr)) {
                    $usdtPercentage = 0;
                }
            }

            // echo ("btcPercentage $btcPercentage ------- usdtPercentage  $usdtPercentage");
            // echo ("tradeableBtc  $tradeableBtc ------- tradeableUsdt $tradeableUsdt");

            //set btc/usdt wrt daily percentage
            $dailyBtc = ($dailTradeAbleBalancePercentage * $tradeableBtc) / 100;
            $dailyUsdt = ($dailTradeAbleBalancePercentage * $tradeableUsdt) / 100;

            // //add 10% extra
            // if ($dailyBtc != 0) {
            //     $dailyBtc = $dailyBtc + ((10 * $dailyBtc) / 100);
            // }
            // if ($dailyUsdt != 0) {
            //     $dailyUsdt = $dailyUsdt + ((10 * $dailyUsdt) / 100);
            // }

            $dailyBtcUsdWorth = $dailyBtc * $pricesArr['BTCUSDT'];
            $dailyUsdtUsdWorth = $dailyUsdt;

            // echo ("dailTradeAbleBalancePercentage ', $dailTradeAbleBalancePercentage");
            // echo ("dailyBtc  $dailyBtc ------- dailyUsdt  $dailyUsdt");
            // echo ("dailyBtcUsdWorth  $dailyBtcUsdWorth ------- dailyUsdtUsdWorth  $dailyUsdtUsdWorth");

            $dailyTradeableBTC = $dailyBtc;
            $dailyTradeableUSDT = $dailyUsdt;

            $daily_available_usd = $dailyBtcUsdWorth + $dailyUsdtUsdWorth;

            $resArr = [
                'daily_bought_btc_usd_worth' => 0,
                'BTCTradesTodayCount' => 0,
                'daily_bought_usdt_usd_worth' => 0,
                'USDTTradesTodayCount' => 0,
                'dailyTradeableBTC' => $dailyTradeableBTC,
                'dailyTradeableUSDT' => $dailyTradeableUSDT,
                'dailyTradeableBTC_usd_worth' => $dailyBtcUsdWorth,
                'dailyTradeableUSDT_usd_worth' => $dailyUsdtUsdWorth,
                'daily_available_usd' => $daily_available_usd,
            ];

            // echo "<br><br>";
            // print_r($resArr);
            return $resArr;
        } else {

            $max_usdt_tradeable = (($usdt_percent * $usd_package_limit) / 100);

            $max_btc_tradeable_usd = (($btc_percent * $usd_package_limit) / 100);
            $max_btc_tradeable = (1 / $pricesArr['BTCUSDT']) * $max_btc_tradeable_usd;

            //TODO 2: get user balance
            $btcUsdt_balance_arr = $this->get_user_current_available_balance($user_id, $exchange);

            $available_tradeable_btc = $btcUsdt_balance_arr['BTC'] > $max_btc_tradeable ? (float) $max_btc_tradeable : (float) $btcUsdt_balance_arr['BTC'];
            $available_tradeable_usdt = $btcUsdt_balance_arr['USDT'] > $max_usdt_tradeable ? (float) $max_usdt_tradeable : (float) $btcUsdt_balance_arr['USDT'];

            //TODO 3: find daily tradeable balance
            $daily_btc = ($daily_tradeable * $available_tradeable_btc) / 100;
            $daily_usdt = ($daily_tradeable * $available_tradeable_usdt) / 100;

            // //add 10% extra
            // if ($daily_btc != 0) {
            //     $daily_btc = $daily_btc + ((10 * $daily_btc) / 100);
            // }
            // if ($daily_usdt != 0) {
            //     $daily_usdt = $daily_usdt + ((10 * $daily_usdt) / 100);
            // }

            $dailyTradeableBTC = $daily_btc;
            $dailyTradeableUSDT = $daily_usdt;

            $dailyBtcUsdWorth = $daily_btc * $pricesArr['BTCUSDT'];
            $dailyUsdtUsdWorth = $daily_usdt;


            // $daily_available_usd = ($pricesArr['BTCUSDT'] * (float) $daily_btc) + (float) $daily_usdt;

            // echo "<br> $daily_btc + $daily_usdt  = $daily_available_usd ($usd_package_limit)";

            //add 10% extra
            // if ($daily_available_usd != 0) {
            //     $daily_available_usd = $daily_available_usd + ((10 * $daily_available_usd) / 100);
            // }

            // echo "<br> old code daily Limit is $daily_available_usd";
            // return $daily_available_usd;

            $daily_available_usd = $dailyBtcUsdWorth + $dailyUsdtUsdWorth;

            $resArr = [
                'daily_bought_btc_usd_worth' => 0,
                'BTCTradesTodayCount' => 0,
                'daily_bought_usdt_usd_worth' => 0,
                'USDTTradesTodayCount' => 0,
                'dailyTradeableBTC' => $dailyTradeableBTC,
                'dailyTradeableUSDT' => $dailyTradeableUSDT,
                'dailyTradeableBTC_usd_worth' => $dailyBtcUsdWorth,
                'dailyTradeableUSDT_usd_worth' => $dailyUsdtUsdWorth,
                'daily_available_usd' => $daily_available_usd,
            ];

            // echo "<br><br>";
            // print_r($resArr);
            return $resArr;
        }
    }//end calculate_daily_tradeable_usd_new
  
    //calculate_daily_tradeable_usd_new2
    public function calculate_daily_tradeable_usd_new2($user_id, $exchange,$defaultCase,$existingdataArray)
    {

        //get btc and usdt balance
        if(!empty($defaultCase))
        {
            $params = $existingdataArray;
        }
        else
        {
          $params = [
                'user_id' => $user_id,
                'exchange' => $exchange,
                'baseCurrencyArr' => ['BTC', 'USDT'],
                'customBtcPackage' => 0.02,
                'customUsdtPackage' => 1000,
                'dailTradeAbleBalancePercentage' => 10,  
            ];      
        }
        
      
         $userId = (string) $user_id;
         $username = 'admin';

        $token = $this->Mod_jwt->LoginToken($userId, $username);
        $returnedData = $this->generateDailyTradeLimit($params,$token);
        echo "ss <pre>";
        print_r($returnedData);
        //exit;
        // $req_arr = [
        //     'req_type' => 'POST',
        //     'req_params' => $params,
        //     'req_endpoint' => '',
        //     'header' => $token,
        //     'req_url' => 'http://app.digiebot.com/admin/trading_reports/Atg/find_available_btc_usdt/'.$user_id.'/'.$exchange,
        // ];
        // echo '<pre>';
        // print_r($req_arr);
        // echo '<pre>';
        // $resp = hitCurlRequest($req_arr);
        // print_r($resp);exit;
        //   if($_SERVER['REMOTE_ADDR'] == '203.99.181.69' || $_SERVER['REMOTE_ADDR'] == '172.31.49.152')
        // {
        //     echo "<pre>";
        //     print_r( $resp);
        //     exit;
        // }   
        // echo "<pre>";
        // print_r($resp);
        // echo "<br>";
        
        if($returnedData['status'] == 200){
          
            $dailyTradeableBTC = $returnedData['data']['dailyTradeableBTC'];
            $dailyTradeableUSDT = $returnedData['data']['dailyTradeableUSDT'];
            $dailyBtcUsdWorth = $returnedData['data']['dailyBtcUsdWorth'];
            $dailyUsdtUsdWorth = $returnedData['data']['dailyUsdtUsdWorth'];
            $daily_available_usd = $dailyBtcUsdWorth + $dailyUsdtUsdWorth;

            $dailyLimitArr = [
                'dailyTradeableBTC' => $dailyTradeableBTC,
                'dailyTradeableUSDT' => $dailyTradeableUSDT,
            ];
            $tempData = $this->dailyLimitAdjust($user_id, $dailyLimitArr, $exchange);
            $dailyTradeableBTC = $tempData['dailyTradeableBTC'];
            $dailyTradeableUSDT = $tempData['dailyTradeableUSDT'];

            $resArr = [
                'daily_bought_btc_usd_worth' => 0,
                'BTCTradesTodayCount' => 0,
                'daily_bought_usdt_usd_worth' => 0,
                'USDTTradesTodayCount' => 0,
                'dailyTradeableBTC' => $dailyTradeableBTC,
                'dailyTradeableUSDT' => $dailyTradeableUSDT,
                'dailyTradeableBTC_usd_worth' => $dailyBtcUsdWorth,
                'dailyTradeableUSDT_usd_worth' => $dailyUsdtUsdWorth,
                'daily_available_usd' => $daily_available_usd,
            ];

            echo "if ran <br>";
            print_r($resArr);
            //exit;

            return $resArr;
        }else{
            $resArr = [
                'daily_bought_btc_usd_worth' => 0,
                'BTCTradesTodayCount' => 0,
                'daily_bought_usdt_usd_worth' => 0,
                'USDTTradesTodayCount' => 0,
                'dailyTradeableBTC' => 0,
                'dailyTradeableUSDT' => 0,
                'dailyTradeableBTC_usd_worth' => 0,
                'dailyTradeableUSDT_usd_worth' => 0,
                'daily_available_usd' => 0,
            ];

            echo "<pre> if ran 23 <br>";
            print_r($resArr);
            //exit;
            return $resArr;
        }

    }//end calculate_daily_tradeable_usd_new2
    
    // copied from ATG and running here, as we can't curl again and again from same server..
    public function generateDailyTradeLimit($params,$received_Token){

        $final_array = array();
        if(empty($params) || empty($received_Token))
        {
            $final_array['status'] = 201;
            return $final_array;
        }
        $user_id = $params['user_id'];
        $exchange = $params['exchange'];

        $baseCurrencyArr = (array) $params['baseCurrencyArr'];
        $customBtcPackage = $params['customBtcPackage'];
        $customUsdtPackage = $params['customUsdtPackage'];
        $dailTradeAbleBalancePercentage = $params['dailTradeAbleBalancePercentage'];
             if(!empty($user_id) && !empty($exchange)){
            
            //get user dashboard wallet data
            $marketPricesArr = get_current_market_prices($exchange, ['BTCUSDT']);
            // echo "hassan <pre>";
            

            $userBalancesInfo = $this->get_dashboard_wallet($user_id, $exchange, $received_Token);
            echo $user_id.'===='.$exchange.'===='.$received_Token;
            echo "<pre>";
            print_r($balanceArr);

            if(empty($userBalancesInfo)){
               $final_array['status'] = 302;
                return $final_array;
            }

            $balanceArr = $userBalancesInfo;
            //echo "<pre>";
            //print_r($balanceArr);
            

            $btcBalanceObj = [];
            $usdtBalanceObj = [];
            $bnbBalanceObj = [];

            $btcLimitExceeded = false;
            $usdtLimitExceeded = false;

            foreach($balanceArr['avaiableBalance'] as $val){

              if($val['coin_symbol'] == 'BTC' ){
                $btcBalanceObj = $val;
              }
              
              if($val['coin_symbol'] == 'USDT' ){
                $usdtBalanceObj = $val;
              }
              
              if($val['coin_symbol'] == 'BNB' ){
                $bnbBalanceObj = $val;
              }
                
            }

            $btcWallet = !empty($btcBalanceObj['coin_balance']) ? $btcBalanceObj['coin_balance'] : 0;
            $usdtWallet = !empty($usdtBalanceObj['coin_balance']) ? $usdtBalanceObj['coin_balance'] : 0;
            $bnbWallet = !empty($bnbBalanceObj['coin_balance']) ? $bnbBalanceObj['coin_balance'] : 0;
            
            // echo('btcWallet: ' .$btcWallet. ' ----- usdtWallet: '. $usdtWallet. ' ----- bnbWallet: '. $bnbWallet. '<br><br>');

            $btcPackage = $customBtcPackage;
            $usdtPackage = $customUsdtPackage;
            $dailyTradePercentage = $dailTradeAbleBalancePercentage; //default 10 %
            $btcAvailable = 0;
            $usdtAvailable = 0;

            $totalBtcForPackageSelection = ($btcWallet + $balanceArr['openBalance']['onlyBtc'] + $balanceArr['lthBalance']['onlyBtc'] + $balanceArr['costAvgBalance']['onlyBtc'] - ($balanceArr['openLthBTCUSDTBalance']['onlyUsdt'] / $marketPricesArr['BTCUSDT']));
            
            $totalUsdtForPackageSelection = ($usdtWallet + $balanceArr['openBalance']['onlyUsdt'] + $balanceArr['lthBalance']['onlyUsdt'] + $balanceArr['costAvgBalance']['onlyUsdt']);

            
            $btcUsed = ($balanceArr['openBalance']['onlyBtc'] + $balanceArr['lthBalance']['onlyBtc'] + $balanceArr['costAvgBalance']['onlyBtc']);
            
            $usdtUsed = ($balanceArr['openBalance']['onlyUsdt'] + $balanceArr['lthBalance']['onlyUsdt'] + $balanceArr['costAvgBalance']['onlyUsdt']);

            // echo $usdtUsed;
            // echo $balanceArr['openLthBTCUSDTBalance']['onlyUsdt'];

            // $usdtUsed = ($balanceArr['openBalance']['onlyUsdt'] + $balanceArr['lthBalance']['onlyUsdt'] + $balanceArr['costAvgBalance']['onlyUsdt']) - $balanceArr['openLthBTCUSDTBalance']['onlyUsdt'];

            //BTC package limit
            echo('BTC package limit '. $btcPackage. ' <= '. $totalBtcForPackageSelection.'<br><br>');

            if ($btcPackage <= $totalBtcForPackageSelection) {

              // echo('BTC Package is less than total balance  ------------------------------------ '.'<br><br>');

              $_70percentOfTotal = (70 * $btcPackage) / 100;
              $_30percentOfTotal = (30 * $btcPackage) / 100;
              //0.006+0.014
              //echo('_70percentOfTotal   '. $_70percentOfTotal. ' -------------    btcUsed '. $btcUsed.'<br><br>');
              //echo('btcPackage   '. $btcPackage. ' -------------    btcUsed '. $btcUsed.'<br><br>');

              if ($_30percentOfTotal > $btcUsed) {
                $remainingTradddeeAble = $btcPackage - $_30percentOfTotal > 0 ? $btcPackage - $_30percentOfTotal : 0;
              } else {
                $remainingTradddeeAble = $btcPackage - $btcUsed > 0 ? $btcPackage - $btcUsed : 0;
              }
              // echo('BTC after 70% check remainingTradddeeAble  '. $remainingTradddeeAble.'<br><br>');

              $btcAvailable = $remainingTradddeeAble > $btcWallet ? $btcWallet : $remainingTradddeeAble;

              // echo('availableBTC ------------------------22   '. $btcAvailable.'<br><br>');

              if ($remainingTradddeeAble <= 0) {
                //echo('Your BTC trade limit has been exceeded please upgrade to a bigger package    ----  ERROR'.'<br><br>');
                $btcLimitExceeded = true;
              }

            }else{

              // echo('BTC Package is GREATER than total balance  ------------------------------------ '.'<br><br>');

              // $_70percentOfTotal = (70 * $totalBtcForPackageSelection) / 100;
              $_30percentOfTotal = (30 * $totalBtcForPackageSelection) / 100;
              // echo('_70percentOfTotal   '. $_70percentOfTotal. ' -------------    btcUsed '. $btcUsed.'<br><br>');

              if ($_30percentOfTotal > $btcUsed) {
                $remainingTradddeeAble = $totalBtcForPackageSelection - $_30percentOfTotal > 0 ? $totalBtcForPackageSelection - $_30percentOfTotal : 0;
              } else {
                $remainingTradddeeAble = $totalBtcForPackageSelection - $btcUsed > 0 ? $totalBtcForPackageSelection - $btcUsed : 0;
              }
              
              // echo('BTC after 70% check remainingTradddeeAble      '. $remainingTradddeeAble.'<br><br>');

              $btcAvailable = $remainingTradddeeAble > $btcWallet ? $btcWallet : $remainingTradddeeAble;

              // echo('availableBTC ------------------------22     '. $btcAvailable.'<br><br>');
              
            }
            
            //USDT package limit
            // echo('USDT package limit '. $usdtPackage. ' <= '. $totalUsdtForPackageSelection.'<br><br>');

            if ($usdtPackage <= $totalUsdtForPackageSelection) {

              // echo('USDT Package is less than total balance  ------------------------------------ '.'<br><br>');

              // $_70percentOfTotal = (70 * $usdtPackage) / 100;
              $_30percentOfTotal = (30 * $usdtPackage) / 100;
              // echo('_70percentOfTotal   '. $_70percentOfTotal. ' -------------    usdtUsed '. $usdtUsed.'<br><br>');

              if ($_30percentOfTotal > $usdtUsed) {
                $remainingTradddeeAble = $usdtPackage - $_30percentOfTotal > 0 ? $usdtPackage - $_30percentOfTotal : 0;
              } else {
                $remainingTradddeeAble = $usdtPackage - $usdtUsed > 0 ? $usdtPackage - $usdtUsed : 0;
              }
              // echo('USDT after 70% check remainingTradddeeAble   '. $remainingTradddeeAble.' <br><br>');

              $usdtAvailable = $remainingTradddeeAble > $usdtWallet ? $usdtWallet : $remainingTradddeeAble;

              // echo('availableUSDT ------------------------22    '. $usdtAvailable.'<br><br>');

              if ($remainingTradddeeAble <= 0) {
                // echo('Your USDT trade limit has been exceeded please upgrade to a bigger package ---  ERROR'.'<br><br>');
                $usdtLimitExceeded = true;
              }

            } else {

              // echo('USDT Package is GREATER than total balance  ------------------------------------ '.'<br><br>');

              $_70percentOfTotal = (70 * $totalUsdtForPackageSelection) / 100;
              $_30percentOfTotal = (30 * $totalUsdtForPackageSelection) / 100;
              // echo('_70percentOfTotal   '. $_70percentOfTotal. ' -------------    usdtUsed '. $usdtUsed.'<br><br>');

              if ($_30percentOfTotal > $usdtUsed) {
                $remainingTradddeeAble = $totalUsdtForPackageSelection - $_30percentOfTotal > 0 ? $totalUsdtForPackageSelection - $_30percentOfTotal : 0;
              } else {
                $remainingTradddeeAble = $totalUsdtForPackageSelection - $usdtUsed > 0 ? $totalUsdtForPackageSelection - $usdtUsed : 0;
              }

              // echo('USDT after 70% check remainingTradddeeAble     '. $remainingTradddeeAble.'<br><br>');

              $usdtAvailable = $remainingTradddeeAble > $usdtWallet ? $usdtWallet : $remainingTradddeeAble;

              // echo('availableUSDT ------------------------22      '. $usdtAvailable.'<br><br>');

            }

            // $saveCurrSettings('step_4', 'availableBNB', $availableBNB)
            // $saveCurrSettings('step_4', 'openOnlyBtc', $balanceArr['openBalance']['onlyBtc'])
            // $saveCurrSettings('step_4', 'openOnlyUsdt', $balanceArr['openBalance']['onlyUsdt'])
            // $saveCurrSettings('step_4', 'lthOnlyBtc', $balanceArr['lthBalance']['onlyBtc'])
            // $saveCurrSettings('step_4', 'lthOnlyUsdt', $balanceArr['lthBalance']['onlyUsdt'])

            //package exceed check for BTC
            if ($btcAvailable > $btcPackage) {
              $btcAvailable = $btcPackage;
            }
            
            //package exceed check for USDT
            if ($usdtAvailable > $usdtPackage) {
              $usdtAvailable = $usdtPackage;
            }
            // echo 'usdtAvailable > usdtPackage'.$usdtAvailable.'--->'.$usdtPackage;
            // echo 'btcAvailable > btcPackage'.$btcAvailable.'--->'.$btcPackage;
            
           
            
            //check currency selection
            if (!in_array('BTC', $baseCurrencyArr) && !in_array('USDT', $baseCurrencyArr)) {
              // echo('Select curency to trade  ---  Error'.'<br><br>');
              // return false;

              //by default set both currencies

            } else if (!in_array('BTC', $baseCurrencyArr) || !in_array('USDT', $baseCurrencyArr)) {
              if (!in_array('BTC', $baseCurrencyArr)) {
                $btcAvailable = 0;
              }
              if (!in_array('USDT', $baseCurrencyArr)) {
                $usdtAvailable = 0;
              }
            }

            // echo('available BTC ::  '. $btcAvailable. '   -------  available USDT ::  '. $usdtAvailable.'<br><br>');

            $availableBTC = $btcAvailable;
            $availableUSDT = $usdtAvailable;

            //set btc/usdt wrt daily percentage
            $dailyBtc = ($dailyTradePercentage * $btcAvailable) / 100;
            $dailyUsdt = ($dailyTradePercentage * $usdtAvailable) / 100;

            $dailyBtcUsdWorth = $dailyBtc * $marketPricesArr['BTCUSDT'];
            $dailyUsdtUsdWorth = $dailyUsdt;

            $dailyBtcUsdWorth = $dailyBtcUsdWorth;
            $dailyUsdtUsdWorth = $dailyUsdtUsdWorth;

            // echo('dailTradeAbleBalancePercentage  '. $dailTradeAbleBalancePercentage.'<br><br>');
            // echo('dailyBtc  '. $dailyBtc. ' ------- dailyUsdt '. $dailyUsdt.'<br><br>');
            // echo('dailyBtcUsdWorth  '. $dailyBtcUsdWorth. '  ------- dailyUsdtUsdWorth  '. $dailyUsdtUsdWorth.'<br><br>');


            $dataArr = [
              'user_id' => $user_id,
              'exchange' => $exchange,

              // To Remove
              'usdtAvailable'=>$usdtAvailable,


              'baseCurrencyArr'=> $baseCurrencyArr,

              'availableBTC'=> (float) number_format($availableBTC, 6, '.', ''),
              'availableUSDT'=> (float) number_format($availableUSDT, 2, '.', ''),
              'tradeableBTC'=> (float) number_format($availableBTC, 6, '.', ''),
              'tradeableUSDT'=> (float) number_format($availableUSDT, 2, '.', ''),
              'actualTradeableBTC'=> (float) number_format($availableBTC, 6, '.', ''),
              'actualTradeableUSDT'=> (float) number_format($availableUSDT, 2, '.', ''),

              //new1 fields
              'btcInvestPercentage'=> 100,
              'usdtInvestPercentage'=> 100,

              // 'dailyBtc' => $dailyBtc, 
              // 'dailyUsdt' => $dailyUsdt, 
              
              // 'dailyBtc'=> (float) number_format($dailyBtc, 6, '.', ''),
              // 'dailyUsdt'=> (float) number_format($dailyUsdt, 2, '.', ''),
              'dailyTradeableBTC'=> (float) number_format($dailyBtc, 6, '.', ''),
              'dailyTradeableUSDT'=> (float) number_format($dailyUsdt, 2, '.', ''),
              'dailyBtcUsdWorth' => (float) number_format($dailyBtcUsdWorth, 2, '.', ''),
              'dailyUsdtUsdWorth' => (float) number_format($dailyUsdtUsdWorth, 2, '.', ''),

              'btcLimitExceeded' => $btcLimitExceeded,
              'usdtLimitExceeded' => $usdtLimitExceeded,

              'totalBtcForPackageSelection' => $totalBtcForPackageSelection,
              'totalUsdtForPackageSelection' => $totalUsdtForPackageSelection,
              // // new fields,
              // 'openOnlyBtc': balanceArr['openBalance']['onlyBtc'],
              // 'openOnlyUsdt': balanceArr['openBalance']['onlyUsdt'],

              // 'lthOnlyBtc': balanceArr['lthBalance']['onlyBtc'],
              // 'lthOnlyUsdt': balanceArr['lthBalance']['onlyUsdt'],

            ];

            $final_array['status'] = 200;
            $final_array['data'] = $dataArr;
            return $final_array;
            

        }
        else
        {
             $final_array['status'] = 400;
           
            return $final_array;
        }


    }
    public function dailyLimitAdjust($user_id, $dailyLimitArr, $exchange){

        if(!empty($user_id) && !empty($dailyLimitArr) && !empty($exchange)){

            $user_id = (string) $user_id;

            // echo "<pre>";
            // print_r($dailyLimitArr);

            $collection_name = $exchange == 'binance' ? 'daily_trade_buy_limit_history' : 'daily_trade_buy_limit_history_'.$exchange;
    
            $start_date = date('Y-m-d H:i:s', strtotime('-7 days'));
    
            $pipeline = [
                [
                    '$facet' => [
                        'btcBuy' => [
                            [
                                '$match'=> [
                                    'user_id' => $user_id,
                                    'created_date' => ['$gte' => $this->mongo_db->converToMongodttime($start_date)],
                                    'dailyTradeableBTC_usd_worth' => ['$gt' => 0],
                                ]
                            ],
                            [
                                '$group'=> [
                                    '_id' => null,
                                    'btcBuySum' => ['$sum' => '$daily_bought_btc_usd_worth'],
                                    'btcLimitSum' => ['$sum' => '$dailyTradeableBTC_usd_worth'],
                                ]
                            ],
                            [
                                '$addFields' => [
                                    'btcBuyPercent' => [
                                        '$multiply' => [ ['$divide' => ['$btcBuySum' , '$btcLimitSum'] ] , 100 ] 
                                    ],
                                ],
                            ]
                        ],
                        'usdtBuy' => [
                            [
                                '$match'=> [
                                    'user_id' => $user_id,
                                    'created_date' => ['$gte' => $this->mongo_db->converToMongodttime($start_date)],
                                    'dailyTradeableUSDT_usd_worth' => ['$gt' => 0],
                                ]
                            ],
                            [
                                '$group'=> [
                                    '_id' => null,
                                    'usdtBuySum' => ['$sum' => '$daily_bought_usdt_usd_worth'],
                                    'usdtLimitSum' => ['$sum' => '$dailyTradeableUSDT_usd_worth'],
                                ]
                            ],
                            [
                                '$addFields' => [
                                    'usdtBuyPercent' => [
                                        '$multiply' => [ ['$divide' => ['$usdtBuySum' , '$usdtLimitSum'] ] , 100 ] 
                                    ],
                                ],
                            ]
                        ]
                    ]
                ]
            ];
    
            $db = $this->mongo_db->customQuery();
            $result = $db->$collection_name->aggregate($pipeline);
            $result = iterator_to_array($result);
    
            $addBtcPercent = 0;
            $addBtcPercent = ((!empty($result[0]['btcBuy'][0]['btcBuyPercent']) && $result[0]['btcBuy'][0]['btcBuyPercent'] < 100) ? 30 : 0);
            
            $addUsdtPercent = 0;
            $addUsdtPercent = ((!empty($result[0]['usdtBuy'][0]['usdtBuyPercent']) && $result[0]['usdtBuy'][0]['usdtBuyPercent'] < 100) ? 30 : 0);
    
            // $dailyTradeableBTC = 0.1;
            // $dailyTradeableUSDT = 10;
            
            $dailyTradeableBTC = $dailyLimitArr['dailyTradeableBTC'];
            $dailyTradeableUSDT = $dailyLimitArr['dailyTradeableUSDT'];
            // echo "default daily BTC ::: $dailyTradeableBTC  --------------  default daily USDT :::  $dailyTradeableUSDT";
            // echo "<br>";
    
            // echo "BTC extra percent : $addBtcPercent  ---------- USDT extra percent $addUsdtPercent";
            // echo "<br>";
            $dailyTradeableBTC = $dailyTradeableBTC + (($addBtcPercent * $dailyTradeableBTC) / 100);
            // echo "added BTC :: $dailyTradeableBTC";
            // echo "<br>";
            $dailyTradeableUSDT = $dailyTradeableUSDT + (($addUsdtPercent * $dailyTradeableUSDT) / 100);
            // echo "added USDT :: $dailyTradeableUSDT";
            // echo "<br>";
    
            // print_r($result);
    
            $resposne = [
                'dailyTradeableBTC' => $dailyTradeableBTC,
                'dailyTradeableUSDT' => $dailyTradeableUSDT,
            ];
            
            // print_r($resposne);

            return $resposne;
        }

        return false;
    }

    //get_dashboard_wallet
    public function get_dashboard_wallet($user_id, $exchange,$received_Token = ''){

        //get btc and usdt balance
        $params = [
            'user_id' => $user_id,
            'exchange' => $exchange,
        ];
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => 'get_dashboard_wallet',
            'header'    => $received_Token
        ];
        
        $resp = hitCurlRequest($req_arr);
        echo "<pre>";
        print_r($resp);
        if($resp['http_code'] == 200 && $resp['response']['status']){
            return $resp['response']['data'];
        }
        return false;
    }//end get_dashboard_wallet

    //get_user_package_limit
    public function get_user_package_limit($user_id){

        $params = [
            'user_id' => $user_id,
        ];
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => 'getSubscription',
        ];
        $resp = hitCurlRequest($req_arr);
        
        if($resp['http_code'] == 200 && $resp['response']['status'] && !empty($resp['response']['trade_limit'])){
            
            return (float) $resp['response']['trade_limit'];

        }
        return 1000;
    }//end get_user_package_limit

    //get_user_current_available_balance
    public function get_user_current_available_balance($user_id, $exchange){

        //get btc and usdt balance
        $params = [
            'user_id' => $user_id,
            'exchange' => $exchange,
        ];
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => 'getBtcUsdtBalance',
        ];
        $resp = hitCurlRequest($req_arr);
        
        $availalbe_btc = 0;
        $availalbe_usdt = 0;
        if($resp['http_code'] == 200 && $resp['response']['status']){
            $i_count = count($resp['response']['data']);
            if($i_count > 0){
                for($i=0; $i < $i_count; $i++){
                    if($resp['response']['data'][$i]['coin_symbol'] == 'BTC'){
                        $availalbe_btc = (float) $resp['response']['data'][$i]['coin_balance'];
                    }elseif($resp['response']['data'][$i]['coin_symbol'] == 'USDT'){
                        $availalbe_usdt = (float) $resp['response']['data'][$i]['coin_balance'];
                    }
                }
            }
        }

        $params = [
            'user_id' => $user_id,
            'exchange' => $exchange,
        ];
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => 'getOpenBalance',
        ];
        $resp = hitCurlRequest($req_arr);

        if ($resp['http_code'] == 200 && $resp['response']['status']) {
            if (count($resp['response']['data'])> 0) {
                $availalbe_btc = $availalbe_btc + (float) $resp['response']['data']['onlyBtc'];
                $availalbe_usdt = $availalbe_usdt + (float) $resp['response']['data']['onlyUsdt'];
            }
        }

        $resArr = [
            'BTC'=> $availalbe_btc,
            'USDT'=> $availalbe_usdt,
        ];
    
        return $resArr;
    }//end get_user_current_available_balance

    // daily buy limit binance
    public function update_daily_buy_limit($user_id='', $save_history=''){

        //Get all live users
        if ($user_id != '') {
            $this->mongo_db->where(['_id' => $user_id]);
        } else {
            $this->mongo_db->where_in('application_mode', ['both', 'live']);
            // $this->mongo_db->where(['account_block' => ['$ne' => 'yes'], 'is_api_key_valid'=>'yes','api_secret' => ['$ne' => '']]);
            $this->mongo_db->where(['account_block' => ['$ne' => 'yes']]);
        }
        $result = $this->mongo_db->get('users');
        $users_arr = iterator_to_array($result);
        $user_ids = array_column($users_arr, '_id');
        unset($result, $users_arr);

        //TODO: save previous day record in history collection
        if ($save_history == 'no'){
            //Don't save history
        }else{
            $history_saved = $this->save_daily_trade_buy_limit_history('binance');
        }

        //Reset all user no of buy trades and buy_worth 
        $db = $this->mongo_db->customQuery();
        if ($user_id != '') {
            $where1111 = ['user_id' => (string) $user_id];
            $where_pick_parent = ['admin_id'=>(string) $user_id];
        } else {
            $where1111 = [];
            $where_pick_parent = ['application_mode' => 'live', 'parent_status' => 'parent', 'status'=> ['$ne'=>'canceled']];
        }

        $set11111 = [
            '$set' => [
                'daily_buy_usd_worth' => 0,
                'num_of_trades_buy_today' => 0,
                'BTCTradesTodayCount' => 0,
                'USDTTradesTodayCount' => 0,
                'daily_bought_btc_usd_worth' => 0,
                'daily_bought_usdt_usd_worth' => 0,
            ]
        ];
        $db->daily_trade_buy_limit->updateMany($where1111, $set11111);

        //set pick parents for all to 'yes'
        if($user_id != ''){
            $db->buy_orders->updateMany($where_pick_parent, ['$set' => ['pick_parent' => 'yes']]);
            $where_pick_parent_cost_avg = ['application_mode' => 'live','cavg_parent'=>'yes','cost_avg' => ['$ne'=>'completed'], 'status'=> ['$ne'=>'canceled']];
            $db->buy_orders->updateMany($where_pick_parent_cost_avg, ['$set' => ['pick_parent' => 'yes']]);
        }else{
            $pick = $this->make_pick_parent_yes('binance');
        }

        $users_count = count($user_ids);
        $user_id = '';

        for($i=0; $i < $users_count; $i++){
            $user_id = $user_ids[$i];

            //admin and vizzDeveloper check for limit
            if((string) $user_id == '5c0912b7fc9aadaac61dd072' || (string) $user_id == '5c0915befc9aadaac61dd1b8'){
                continue;
            }

            //Get user daily_trade_buy_limit
            $collection_name = 'daily_trade_buy_limit';
            $this->mongo_db->where([
                'user_id' => (string) $user_id,
            ]);
            $result = $this->mongo_db->get($collection_name);
            $user_buy_limit = iterator_to_array($result);
            unset($result);

            $limitArr = $this->get_user_daily_buy_limit_in_usd($user_id, 'binance');
            echo "<pre>";
            print_r($limitArr);
            if(count($user_buy_limit) > 0){
                unset($user_buy_limit);
                $upd_data = [
                    'daily_buy_usd_worth' => 0,
                    'num_of_trades_buy_today' => 0,
                    'BTCTradesTodayCount' => 0,
                    'USDTTradesTodayCount' => 0,
                    'daily_bought_btc_usd_worth' => 0,
                    'daily_bought_usdt_usd_worth' => 0,
                    'dailyTradeableBTC' => $limitArr['dailyTradeableBTC'],
                    'dailyTradeableUSDT' => $limitArr['dailyTradeableUSDT'],
                    'dailyTradeableBTC_usd_worth' => $limitArr['dailyTradeableBTC_usd_worth'],
                    'dailyTradeableUSDT_usd_worth' => $limitArr['dailyTradeableUSDT_usd_worth'],
                    'daily_buy_usd_limit' => $limitArr['daily_available_usd'],
                    'modified_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                ];
                $this->mongo_db->where("user_id", (string) $user_id);
                $this->mongo_db->set($upd_data);
                $this->mongo_db->update($collection_name);
            }else{
                $ins_data = [
                    'user_id' => (string) $user_id,
                    'daily_buy_usd_worth' => 0,
                    'num_of_trades_buy_today' => 0,
                    'BTCTradesTodayCount' => 0,
                    'USDTTradesTodayCount' => 0,
                    'daily_bought_btc_usd_worth' => 0,
                    'daily_bought_usdt_usd_worth' => 0,
                    'dailyTradeableBTC' => $limitArr['dailyTradeableBTC'],
                    'dailyTradeableUSDT' => $limitArr['dailyTradeableUSDT'],
                    'dailyTradeableBTC_usd_worth' => $limitArr['dailyTradeableBTC_usd_worth'],
                    'dailyTradeableUSDT_usd_worth' => $limitArr['dailyTradeableUSDT_usd_worth'],
                    'daily_buy_usd_limit' => $limitArr['daily_available_usd'],
                    'created_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                    'modified_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                ];
                $this->mongo_db->insert($collection_name, $ins_data);
            }
            unset($limitArr);

            // sleep(5);
        }

        // Save last Cron Executioon
        $this->last_cron_execution_time('update_daily_buy_limit_binance', '1d', 'Cronjob to update trades buy limit for binance users (59 7 * * *)', 'dailyLimit');
        echo "end script";

    }//end update_daily_buy_limit binance
    
    // daily buy limit bam
    public function update_daily_buy_limit_bam($user_id='', $save_history=''){

        //Get all live users
        if ($user_id != '') {
            $this->mongo_db->where(['user_id' => $user_id]);
        } else {
            $this->mongo_db->where(['api_key' => ['$ne' => ''], 'api_secret' => ['$ne' => '']]);
        }
        $result = $this->mongo_db->get('bam_credentials');
        $users_arr = iterator_to_array($result);
        $user_ids = array_column($users_arr, 'user_id');
        unset($result, $users_arr);

        //TODO: save previous day record in history collection
        if ($save_history == 'no'){
            //Don't save history
        }else{
            $history_saved = $this->save_daily_trade_buy_limit_history('bam');
        }

        //Reset all user no of buy trades and buy_worth 
        $db = $this->mongo_db->customQuery();
        if ($user_id != '') {
            $where1111 = ['user_id' => (string) $user_id];
            $where_pick_parent = ['admin_id'=>(string) $user_id];
        } else {
            $where1111 = [];
            $where_pick_parent = ['application_mode' => 'live', 'parent_status' => 'parent', 'status'=> ['$ne'=>'canceled']];
        }

        //Reset all user no of buy trades and buy_worth
        $db = $this->mongo_db->customQuery();
        $set11111 = [
            '$set' => [
                'daily_buy_usd_worth' => 0,
                'num_of_trades_buy_today' => 0,
                'BTCTradesTodayCount' => 0,
                'USDTTradesTodayCount' => 0,
                'daily_bought_btc_usd_worth' => 0,
                'daily_bought_usdt_usd_worth' => 0,
            ]
        ];
        $db->daily_trade_buy_limit_bam->updateMany($where1111, $set11111);

        //set pick parents for all to 'yes'
        if($user_id != ''){
            $db->buy_orders_kraken->updateMany($where_pick_parent, ['$set' => ['pick_parent' => 'yes']]);
        }else{
            $pick = $this->make_pick_parent_yes('bam');
        }

        $users_count = count($user_ids);
        $user_id = '';
        for($i=0; $i < $users_count; $i++){
            $user_id = $user_ids[$i];


            //admin and vizzDeveloper check for limit
            if((string) $user_id == '5c0912b7fc9aadaac61dd072' || (string) $user_id == '5c0915befc9aadaac61dd1b8'){
                continue;
            }

            //Get user daily_trade_buy_limit
            $collection_name = 'daily_trade_buy_limit_bam';
            $this->mongo_db->where([
                'user_id' => (string) $user_id,
            ]);
            $result = $this->mongo_db->get($collection_name);
            $user_buy_limit = iterator_to_array($result);
            unset($result);

            $limitArr = $this->get_user_daily_buy_limit_in_usd($user_id, 'bam');

            if(count($user_buy_limit) > 0){
                unset($user_buy_limit);
                $upd_data = [
                    'daily_buy_usd_worth' => 0,
                    'num_of_trades_buy_today' => 0,
                    'BTCTradesTodayCount' => 0,
                    'USDTTradesTodayCount' => 0,
                    'daily_bought_btc_usd_worth' => 0,
                    'daily_bought_usdt_usd_worth' => 0,
                    'dailyTradeableBTC' => $limitArr['dailyTradeableBTC'],
                    'dailyTradeableUSDT' => $limitArr['dailyTradeableUSDT'],
                    'dailyTradeableBTC_usd_worth' => $limitArr['dailyTradeableBTC_usd_worth'],
                    'dailyTradeableUSDT_usd_worth' => $limitArr['dailyTradeableUSDT_usd_worth'],
                    'daily_buy_usd_limit' => $limitArr['daily_available_usd'],
                    'modified_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                ];
                $this->mongo_db->where("user_id", (string) $user_id);
                $this->mongo_db->set($upd_data);
                $this->mongo_db->update($collection_name);
            }else{
                $ins_data = [
                    'user_id' => (string) $user_id,
                    'daily_buy_usd_worth' => 0,
                    'num_of_trades_buy_today' => 0,
                    'BTCTradesTodayCount' => 0,
                    'USDTTradesTodayCount' => 0,
                    'daily_bought_btc_usd_worth' => 0,
                    'daily_bought_usdt_usd_worth' => 0,
                    'dailyTradeableBTC' => $limitArr['dailyTradeableBTC'],
                    'dailyTradeableUSDT' => $limitArr['dailyTradeableUSDT'],
                    'dailyTradeableBTC_usd_worth' => $limitArr['dailyTradeableBTC_usd_worth'],
                    'dailyTradeableUSDT_usd_worth' => $limitArr['dailyTradeableUSDT_usd_worth'],
                    'daily_buy_usd_limit' => $limitArr['daily_available_usd'],
                    'created_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                    'modified_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                ];
                $this->mongo_db->insert($collection_name, $ins_data);
            }
            unset($limitArr);

            // sleep(5);
        }

        // Save last Cron Executioon
        $this->last_cron_execution_time('update_daily_buy_limit_bam', '1d', 'Cronjob to update trades buy limit for bam users (59 7 * * *)', 'dailyLimit');
        echo "end script";

    }//end update_daily_buy_limit bam
    
    // daily buy limit kraken
    public function update_daily_buy_limit_kraken($user_id='', $save_history=''){

        //Get all live users
        if ($user_id != '') {
            $this->mongo_db->where(['account_block' => ['$ne' => 'yes'],'user_id' => $user_id]);
        } else {
            //$this->mongo_db->where(['api_key' => ['$ne' => ''],'is_api_key_valid'=>'yes', 'api_secret' => ['$ne' => '']]);
            $this->mongo_db->where(['account_block' => ['$ne' => 'yes']]);
        }
      
        $result = $this->mongo_db->get('kraken_credentials');
        $users_arr = iterator_to_array($result);
        $user_ids = array_column($users_arr, 'user_id');
        unset($result, $users_arr);
        
         if(!empty($_COOKIE['hassan']))
        {
        }
        else
        {
        //TODO: save previous day record in history collection
        if ($save_history == 'no'){
            //Don't save history
        }else{
            $history_saved = $this->save_daily_trade_buy_limit_history('kraken');
        }
        
        //Reset all user no of buy trades and buy_worth
        $db = $this->mongo_db->customQuery();

        if ($user_id != '') {
            $where1111 = ['user_id' => (string) $user_id];
            $where_pick_parent = ['admin_id'=>(string) $user_id];
        } else {
            $where1111 = [];
            $where_pick_parent = ['application_mode' => 'live', 'parent_status' => 'parent', 'status'=> ['$ne'=>'canceled']];
        }

        //Reset all user no of buy trades and buy_worth
        $db = $this->mongo_db->customQuery();
        $set11111 = [
            '$set' => [
                'daily_buy_usd_worth' => 0,
                'num_of_trades_buy_today' => 0,
                'BTCTradesTodayCount' => 0,
                'USDTTradesTodayCount' => 0,
                'daily_bought_btc_usd_worth' => 0,
                'daily_bought_usdt_usd_worth' => 0,
            ]
        ];
        $db->daily_trade_buy_limit_kraken->updateMany($where1111, $set11111);

        //set pick parents for all to 'yes'
        if($user_id != ''){
            $db->buy_orders_kraken->updateMany($where_pick_parent, ['$set' => ['pick_parent' => 'yes']]);
        }else{
            $pick = $this->make_pick_parent_yes('kraken');
        }
        }
        $users_count = count($user_ids);
        $user_id = '';
        for($i=0; $i < $users_count; $i++){
            $user_id = $user_ids[$i];

            //admin and vizzDeveloper check for limit
            if((string) $user_id == '5c0912b7fc9aadaac61dd072' || (string) $user_id == '5c0915befc9aadaac61dd1b8'){
                continue;
            }

            //Get user daily_trade_buy_limit
            $collection_name = 'daily_trade_buy_limit_kraken';
            $this->mongo_db->where([
                'user_id' => (string) $user_id,
            ]);
            $result = $this->mongo_db->get($collection_name);
            $user_buy_limit = iterator_to_array($result);
            unset($result);

            $limitArr = $this->get_user_daily_buy_limit_in_usd($user_id, 'kraken');
               if(!empty($_COOKIE['hassan']))
                {
                    print_r($limitArr);exit;
                }    
            if(count($user_buy_limit) > 0){
                unset($user_buy_limit);
                $upd_data = [
                    'daily_buy_usd_worth' => 0,
                    'num_of_trades_buy_today' => 0,
                    'BTCTradesTodayCount' => 0,
                    'USDTTradesTodayCount' => 0,
                    'daily_bought_btc_usd_worth' => 0,
                    'daily_bought_usdt_usd_worth' => 0,
                    'dailyTradeableBTC' => $limitArr['dailyTradeableBTC'],
                    'dailyTradeableUSDT' => $limitArr['dailyTradeableUSDT'],
                    'dailyTradeableBTC_usd_worth' => $limitArr['dailyTradeableBTC_usd_worth'],
                    'dailyTradeableUSDT_usd_worth' => $limitArr['dailyTradeableUSDT_usd_worth'],
                    'daily_buy_usd_limit' => $limitArr['daily_available_usd'],
                    'modified_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                ];
                $this->mongo_db->where("user_id", (string) $user_id);
                $this->mongo_db->set($upd_data);
                $this->mongo_db->update($collection_name);
            }else{
                $ins_data = [
                    'user_id' => (string) $user_id,
                    'daily_buy_usd_worth' => 0,
                    'num_of_trades_buy_today' => 0,
                    'BTCTradesTodayCount' => 0,
                    'USDTTradesTodayCount' => 0,
                    'daily_bought_btc_usd_worth' => 0,
                    'daily_bought_usdt_usd_worth' => 0,
                    'dailyTradeableBTC' => $limitArr['dailyTradeableBTC'],
                    'dailyTradeableUSDT' => $limitArr['dailyTradeableUSDT'],
                    'dailyTradeableBTC_usd_worth' => $limitArr['dailyTradeableBTC_usd_worth'],
                    'dailyTradeableUSDT_usd_worth' => $limitArr['dailyTradeableUSDT_usd_worth'],
                    'daily_buy_usd_limit' => $limitArr['daily_available_usd'],
                    'created_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                    'modified_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                ];
                $this->mongo_db->insert($collection_name, $ins_data);
            }
            unset($limitArr);

            // sleep(5);

        }

        // Save last Cron Executioon
        $this->last_cron_execution_time('update_daily_buy_limit_kraken', '1d', 'Cronjob to update trades buy limit for kraken users (59 7 * * *)', 'dailyLimit');
        echo "end script";

    }//end update_daily_buy_limit kraken

    //save_daily_trade_buy_limit_history
    public function save_daily_trade_buy_limit_history($exchange){

        $collection_name1 = $exchange == 'binance' ? 'daily_trade_buy_limit' : 'daily_trade_buy_limit_'.$exchange;
        $collection_name2 = $exchange == 'binance' ? 'daily_trade_buy_limit_history' : 'daily_trade_buy_limit_history_'.$exchange;
        $pipeline = [
            [
                '$project' => [
                    '_id' => 0,
                    'user_id' => 1,
                    'daily_buy_usd_worth' => 1,
                    'num_of_trades_buy_today' => 1,
                    'dailyTradeableBTC' => 1,
                    'dailyTradeableUSDT' => 1,
                    'dailyTradeableBTC_usd_worth' => 1,
                    'dailyTradeableUSDT_usd_worth' => 1,
                    'daily_buy_usd_limit' => 1,
                    'daily_bought_btc_usd_worth' => 1,
                    'BTCTradesTodayCount' => 1,
                    'daily_bought_usdt_usd_worth' => 1,
                    'USDTTradesTodayCount' => 1,
                ],
            ],
            [
                '$addFields' => [
                    'created_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                ],
            ],
        ];
        $document = $this->mongo_db->customQuery();
        $data111 = $document->$collection_name1->aggregate($pipeline);
        $dd111 = iterator_to_array($data111);

        if (!empty($dd111)) {
            $db = $this->mongo_db->customQuery();
            $db->$collection_name2->insertMany($dd111);
        }
        return true;
    }//End save_daily_trade_buy_limit_history


    public function set_pick_parent_yes(){
        // $db = $this->mongo_db->customQuery();
        // $where = ['application_mode' => 'live', 'parent_status' => 'parent', 'status' => ['$ne' => 'canceled']];
        // $set = ['$set'=>['pick_parent' => 'yes']];
        // $db->buy_orders->updateMany($where, $set);
        // $db->buy_orders_kraken->updateMany($where, $set);
        // $db->buy_orders_bam->updateMany($where, $set);
        // exit;

        $this->make_pick_parent_yes('binance');
        $this->make_pick_parent_yes('kraken');
        $this->make_pick_parent_yes('bam');

    }

    public function make_pick_parent_yes($exchange){

        $collection_name = $exchange == 'binance' ? 'users' : $exchange."_credentials";
        
        $db = $this->mongo_db->customQuery();
        
        // echo "<pre>";

        $exceeded_user_ids = [];

        if($exchange == 'binance'){

            $pipeline = [
                [
                    '$match'=> [
                        'application_mode' => ['$in'=>['both', 'live']],
                        'trading_status' => 'off',
                        'is_api_key_valid' => 'no'
                    ]
                ],
                [
                    '$group'=> [
                        '_id' => null,
                        'user_ids' => ['$push'=>[ '$toString'=>'$$ROOT._id']],
                    ]
                ],
                [
                    '$project'=> [
                        'user_ids' => 1,
                    ]
                ],
            ];

            // echo "<pre>";
            // print_r($pipeline);

            $result = $db->$collection_name->aggregate($pipeline);

            $result = iterator_to_array($result);

            if(!empty($result)){
                // print_r($result[0]['user_ids']);

                $exceeded_user_ids = $result[0]['user_ids'];
                unset($result);
            }

        }else{

            $pipeline = [
                [
                    '$match'=> [
                        'trading_status' => 'off',
                        'is_api_key_valid' => 'no'
                    ]
                ],
                [
                    '$group'=> [
                        '_id' => null,
                        'user_ids' => ['$push'=>[ '$toString'=>'$$ROOT.user_id']],
                    ]
                ],
                [
                    '$project'=> [
                        'user_ids' => 1,
                    ]
                ],
            ];

            // echo "<pre>";
            // print_r($pipeline);

            $result = $db->$collection_name->aggregate($pipeline);

            $result = iterator_to_array($result);

            if(!empty($result)){
                // print_r($result[0]['user_ids']);
                
                $exceeded_user_ids = $result[0]['user_ids'];
                unset($result);
            }

        }

        if(!empty($exceeded_user_ids)){
            
            // print_r($exceeded_user_ids);
            
            //update pick_parent
            //binance
            $db->buy_orders->updateMany(['admin_id'=>['$nin'=>$exceeded_user_ids], 'application_mode'=> 'live', 'parent_status'=>'parent', 'status'=>['$ne'=>'canceled']], ['$set'=>['pick_parent'=>'yes']]);
             $db->buy_orders->updateMany(['admin_id'=>['$nin'=>$exceeded_user_ids], 'application_mode'=> 'live', 'cavg_parent'=>'yes','cost_avg'=>['$ne'=>'completed'],'status'=>['$ne'=>'canceled']], ['$set'=>['pick_parent'=>'yes']]);
            //bam
            $db->buy_orders_bam->updateMany(['admin_id'=>['$nin'=>$exceeded_user_ids], 'application_mode'=> 'live', 'parent_status'=>'parent', 'status'=>['$ne'=>'canceled']], ['$set'=>['pick_parent'=>'yes']]);
            $db->buy_orders_bam->updateMany(['admin_id'=>['$nin'=>$exceeded_user_ids], 'application_mode'=> 'live', 'cavg_parent'=>'yes','cost_avg'=>['$ne'=>'completed'],'status'=>['$ne'=>'canceled']], ['$set'=>['pick_parent'=>'yes']]);
            
            //kraken
            $db->buy_orders_kraken->updateMany(['admin_id'=>['$nin'=>$exceeded_user_ids], 'application_mode'=> 'live', 'parent_status'=>'parent', 'status'=>['$ne'=>'canceled']], ['$set'=>['pick_parent'=>'yes']]);
            $db->buy_orders_kraken->updateMany(['admin_id'=>['$nin'=>$exceeded_user_ids], 'application_mode'=> 'live', 'cavg_parent'=>'yes','cost_avg'=>['$ne'=>'completed'],'status'=>['$ne'=>'canceled']], ['$set'=>['pick_parent'=>'yes']]);
        }

        return true;
    }

    public function make_pick_parent_yes_manually($exchange='', $user_id=''){

        echo "<pre>";
        //print_r($user_id);
        if($exchange != ''){
            $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
            $db = $this->mongo_db->customQuery();

            $where = [
                'application_mode' => 'live', 
                'parent_status' => 'parent', 
                'status' => ['$ne' => 'canceled'],
            ];
            $where2 = [
                'application_mode' => 'live', 
                'cavg_parent' => 'yes', 
                'cost_avg' => ['$ne'=>'completed'], 
                'status' => ['$ne' => 'canceled'],
            ];
            $set = [
                '$set' => ['pick_parent' => 'yes']
            ];
            
            if($user_id != ''){
                echo 'hello';
                $where['admin_id'] = $user_id;
                $where2['admin_id'] = $user_id;
            }
            
            //$result = $db->$buy_collection->updateMany($where, $set);
            //$result2 = $db->$buy_collection->updateMany($where2, $set);

            print_r($where2);

        }else{
            echo 'Invalid API Hit <br><br>';
            echo 'endPoint :::   make_pick_parent_yes_manually/exchange/user_id <br><br>';
            echo 'exchange is required <br>';
            echo 'user_id is optional (if user_id is provided it will only run for that user else it will run for all users) <br>';
        }
    }

    //********************   calculate trading points cron   *********************** */


    /////////////////// recover previous points

    //populate_previous_points
    // public function populate_previous_points($user_id){
    //     // ini_set("display_errors", E_ALL);
    //     // error_reporting(E_ALL);
    //     if(empty($user_id))
    //     {
    //         echo "Stop Dangerous stuff ahead.<pre>";
    //         return;
    //     }
    //     $exchange = 'kraken';
    //      //return;
    //     //$exchange= 'binance';
    //   $connetct = $this->mongo_db->customQuery();
    //     for ($i = 0; $i < 120; $i++) {
    //         $startTime = date('Y-m-d H:i:s ', strtotime("2021-06-28 07:00:00 +$i days"));
    //         $endTime = date('Y-m-d H:i:s ', strtotime("2021-06-28 07:00:00 +" . ($i + 1) . " days"));

    //         echo "<br> $startTime --------- $endTime ::::::::::: $i";
            
    //         $startTime = $this->mongo_db->converToMongodttime($startTime);
    //         $endTime = $this->mongo_db->converToMongodttime($endTime);
    //         $buyCollection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
    //             $soldCollection = $exchange == "binance" ? "sold_buy_orders" : "sold_buy_orders_$exchange";

    //             $childsBuyPipeline = [
    //                 [
    //                     '$match'=> [
    //                         'application_mode'=> 'live',
    //                         'admin_id'=> $user_id,
    //                         'purchased_price'=> ['$exists'=> true],
    //                         'quantity'=> ['$exists'=> true],
    //                         'status'=> [
    //                             '$nin'=> [
    //                                 'canceled',
    //                                 'error',
    //                                 'new_ERROR',
    //                                 'FILLED_ERROR',
    //                                 'submitted_ERROR',
    //                                 'LTH_ERROR',
    //                                 'canceled_ERROR',
    //                                 'credentials_ERROR',
    //                             ]
    //                         ],
    //                         'resume_order_id' => ['$exists' => false],
    //                         'buy_date'=> [
    //                             '$gte'=> $startTime, 
    //                             '$lte'=> $endTime
    //                         ],
    //                     ]
    //                 ],
    //                 [ 
    //                     '$project'=> [ 
    //                         "symbol"=> 1,
    //                         "quantity"=> 1,
    //                         "purchased_price"=> 1,
    //                     ]
    //                 ]
    //             ];
    //             $buy_orders = $connetct->$buyCollection->aggregate($childsBuyPipeline);
    //             $buy_orders = iterator_to_array($buy_orders);
    
    //             $sold_orders = $connetct->$soldCollection->aggregate($childsBuyPipeline);
    //             $sold_orders = iterator_to_array($sold_orders);
    //             $orders = array_merge($buy_orders, $sold_orders);
    //             echo count($orders)."-----------".count($buy_orders)."---------------".count($sold_orders)."<br>";
    //             unset($buy_orders, $sold_orders);

    //             $BTCUSDT_price = $this->get_market_price('BTCUSDT', $exchange);
                
    //             if(!empty($orders) && !empty($BTCUSDT_price)){
    //                 //get current market_price for BTCUSDT
                    
    //                 $total_usd_buy_worth = 0;
    //                 $order_count = count($orders);
    //                 //for loop on orders to find total buy worth
    //                 for($j=0; $j<$order_count; $j++){
                        
    //                     $tarr = explode('USDT', $orders[$j]['symbol']);
    //                     if (isset($tarr[1]) && $tarr[1] == '') {
    //                         // echo "\r\n USDT coin";
    //                         $usd_balance = $orders[$j]['quantity'] * $orders[$j]['purchased_price'];
    //                         $usd_balance = number_format($usd_balance, 2);
    //                     } else {
    //                         // echo "\r\n BTC coin";
    //                         $usd_balance = $orders[$j]['quantity'] * $orders[$j]['purchased_price'] * $BTCUSDT_price;
    //                         $usd_balance = number_format($usd_balance, 2);
    //                     }
    //                     $total_usd_buy_worth += (!is_nan($usd_balance) ? $usd_balance : 0);
    //                 }

    //                 echo "today buy_usd_worth $ $total_usd_buy_worth <br>";

    //                 // find points consumed today $100 = 1 point
    //                 $pointUsdVal = 100;
    //                 $points_consumed = $total_usd_buy_worth / $pointUsdVal;
    //                 $points_consumed = number_format($points_consumed, 2);
    //                 $this->update_trading_points($user_id, $exchange, $points_consumed);
    //             }

           
    //     }
    // }//end populate_previous_points

    // public function calculate_current_trading_points_history($exchange, $startTime, $endTime){

    //     // echo "<pre> testing ";
    //     $connetct = $this->mongo_db->customQuery();

    //     //get binance users with API key secret
    //     $collection = $exchange == "binance" ? "users" : $exchange."_credentials";

    //     $pipeline = [
    //         [
    //             '$match'=>[
    //                 'api_key'=>['$exists'=>true],
    //                 'api_secret'=>['$exists'=>true],
    //                 'application_mode'=>'both'
    //             ]
    //         ],
    //         [
    //             '$group'=>[
    //                 '_id'=>null,
    //                 'user_ids'=>['$push'=>['$toString'=>'$_id']]
    //             ]
    //         ],
    //         [
    //             '$project'=>[
    //                 'user_ids'=>1,
    //                 '_id'=>0,
    //             ]
    //         ]
    //     ];

    //     //only pick user with api_secret credentials for exchanges
    //     if($exchange != 'binance' && $exchange != ''){
    //       $collection_t = $exchange . "_credentials";
    //         $pipeline_t = [
    //             [
    //                 '$match' => [
    //                     'api_key' => ['$exists' => true],
    //                     'api_secret' => ['$exists' => true],
    //                 ],
    //             ],
    //             [
    //                 '$group' => [
    //                     '_id' => null,
    //                     'user_ids' => ['$push' => ['$toObjectId' => '$user_id']],
    //                 ],
    //             ],
    //             [
    //                 '$project' => [
    //                     'user_ids' => 1,
    //                     '_id' => 0,
    //                 ],
    //             ],
    //         ];

    //         $match_users = $connetct->$collection_t->aggregate($pipeline_t);
    //         $match_users = iterator_to_array($match_users);
    //         if(!empty($match_users)){
    //             $match_user_ids = array_column($match_users, 'user_ids');

    //             $match_user_ids = (array)$match_user_ids;
    //             $match_user_ids = (array)$match_user_ids[0];

    //             $pipeline[0]['$match']['_id']['$in'] = $match_user_ids; 
    //             unset($pipeline[0]['$match']['api_key'], $pipeline[0]['$match']['api_secret']); 
    //         }
    //         unset($collection_t, $pipeline_t, $match_users, $match_user_ids);
    //     }

    //     $users = $connetct->users->aggregate($pipeline);
    //     $users = iterator_to_array($users);

    //     if(!empty($users)){
    //         $user_ids = $users[0]['user_ids']; 
    //         $user_ids = (array) $user_ids;

    //         if(empty($user_ids)){
    //             return;
    //         }

    //         $user_ids = array_unique($user_ids);
    //         // print_r($user_ids);
    //         // shuffle($user_ids);

    //         $total_users = count($user_ids);
    //         //for loop on users
    //         for($i=0; $i<$total_users; $i++){
    //             $user_id = $user_ids[$i];
                
    //             // $user_id = '5c0912b7fc9aadaac61dd072';//admin
    //             // $user_id = '5c867e3ffc9aad347e165d32';//live user

    //             $buyCollection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
    //             $soldCollection = $exchange == "binance" ? "sold_buy_orders" : "sold_buy_orders_$exchange";

    //             $childsBuyPipeline = [
    //                 [
    //                     '$match'=> [
    //                         'application_mode'=> 'live',
    //                         'admin_id'=> $user_id,
    //                         'purchased_price'=> ['$exists'=> true],
    //                         'quantity'=> ['$exists'=> true],
    //                         'status'=> [
    //                             '$nin'=> [
    //                                 'canceled',
    //                                 'error',
    //                                 'new_ERROR',
    //                                 'FILLED_ERROR',
    //                                 'submitted_ERROR',
    //                                 'LTH_ERROR',
    //                                 'canceled_ERROR',
    //                                 'credentials_ERROR',
    //                             ]
    //                         ],
    //                         'resume_order_id' => ['$exists' => false],
    //                         'buy_date'=> [
    //                             '$gte'=> $startTime, 
    //                             '$lte'=> $endTime
    //                         ],
    //                     ]
    //                 ],
    //                 [ 
    //                     '$project'=> [ 
    //                         "symbol"=> 1,
    //                         "quantity"=> 1,
    //                         "purchased_price"=> 1,
    //                     ]
    //                 ]
    //             ];
    //             $buy_orders = $connetct->$buyCollection->aggregate($childsBuyPipeline);
    //             $buy_orders = iterator_to_array($buy_orders);
    
    //             $sold_orders = $connetct->$soldCollection->aggregate($childsBuyPipeline);
    //             $sold_orders = iterator_to_array($sold_orders);
    //             $orders = array_merge($buy_orders, $sold_orders);
    //             // echo count($orders)."-----------".count($buy_orders)."---------------".count($sold_orders)."<br>";
    //             unset($buy_orders, $sold_orders);

    //             $BTCUSDT_price = $this->get_market_price('BTCUSDT', $exchange);
                
    //             if(!empty($orders) && !empty($BTCUSDT_price)){
    //                 //get current market_price for BTCUSDT
                    
    //                 $total_usd_buy_worth = 0;
    //                 $order_count = count($orders);
    //                 //for loop on orders to find total buy worth
    //                 for($j=0; $j<$order_count; $j++){
                        
    //                     $tarr = explode('USDT', $orders[$j]['symbol']);
    //                     if (isset($tarr[1]) && $tarr[1] == '') {
    //                         // echo "\r\n USDT coin";
    //                         $usd_balance = $orders[$j]['quantity'] * $orders[$j]['purchased_price'];
    //                         $usd_balance = number_format($usd_balance, 2);
    //                     } else {
    //                         // echo "\r\n BTC coin";
    //                         $usd_balance = $orders[$j]['quantity'] * $orders[$j]['purchased_price'] * $BTCUSDT_price;
    //                         $usd_balance = number_format($usd_balance, 2);
    //                     }
    //                     $total_usd_buy_worth += (!is_nan($usd_balance) ? $usd_balance : 0);
    //                 }

    //                 // echo "today buy_usd_worth $ $total_usd_buy_worth <br>";

    //                 // find points consumed today $100 = 1 point
    //                 $pointUsdVal = 100;
    //                 $points_consumed = $total_usd_buy_worth / $pointUsdVal;
    //                 $points_consumed = number_format($points_consumed, 2);
    //                 $this->update_trading_points($user_id, $exchange, $points_consumed);
    //                 //update current trading points 
    //             }
    //         }
    //     }

    //     // Save last Cron Executioon
    //     $this->last_cron_execution_time('calculate_current_trading_points', '1d', 'Binnace cronjob to calculate trading points (* 7 * * *)', 'tradingPoints');

    //     echo "<br><br> ********************** End Script **************************** <br>";
    //     return;
    // }//End calculate_current_trading_points_history

    /////////////////// End recover previous points


    //calculate current trading points
    // public function calculate_current_trading_points(){

    //     // echo "<pre> testing ";
    //     $connetct = $this->mongo_db->customQuery();

    //     //get binance users with API key secret
    //     $exchange = "binance";
    //     $collection = $exchange == "binance" ? "users" : $exchange."_credentials";

    //     $pipeline = [
    //         [
    //             '$match'=>[
    //                 'api_key'=>['$exists'=>true],
    //                 'api_secret'=>['$exists'=>true],
    //                 'application_mode'=>'both'
    //             ]
    //         ],
    //         [
    //             '$group'=>[
    //                 '_id'=>null,
    //                 'user_ids'=>['$push'=>['$toString'=>'$_id']]
    //             ]
    //         ],
    //         [
    //             '$project'=>[
    //                 'user_ids'=>1,
    //                 '_id'=>0,
    //             ]
    //         ]
    //     ];

    //     //only pick user with api_secret credentials for exchanges
    //     if($exchange != 'binance' && $exchange != ''){
    //       $collection_t = $exchange . "_credentials";
    //         $pipeline_t = [
    //             [
    //                 '$match' => [
    //                     'api_key' => ['$exists' => true],
    //                     'api_secret' => ['$exists' => true],
    //                 ],
    //             ],
    //             [
    //                 '$group' => [
    //                     '_id' => null,
    //                     'user_ids' => ['$push' => ['$toObjectId' => '$user_id']],
    //                 ],
    //             ],
    //             [
    //                 '$project' => [
    //                     'user_ids' => 1,
    //                     '_id' => 0,
    //                 ],
    //             ],
    //         ];

    //         $match_users = $connetct->$collection_t->aggregate($pipeline_t);
    //         $match_users = iterator_to_array($match_users);
    //         if(!empty($match_users)){
    //             $match_user_ids = array_column($match_users, 'user_ids');
    //             $pipeline[0]['$match']['_id']['$in'] = $match_user_ids; 
    //             unset($pipeline[0]['$match']['api_key'], $pipeline[0]['$match']['api_secret']); 
    //         }
    //         unset($collection_t, $pipeline_t, $match_users, $match_user_ids);
    //     }

    //     $users = $connetct->users->aggregate($pipeline);
    //     $users = iterator_to_array($users);

    //     if(!empty($users)){
    //         $user_ids = $users[0]['user_ids']; 
    //         $user_ids = (array) $user_ids;

    //         if(empty($user_ids)){
    //             return;
    //         }

    //         $user_ids = array_unique($user_ids);

    //         // print_r($user_ids);

    //         // shuffle($user_ids);

    //         $total_users = count($user_ids);
    //         //for loop on users
    //         for($i=0; $i<$total_users; $i++){
    //             $user_id = $user_ids[$i];
                
    //             // $user_id = '5c0912b7fc9aadaac61dd072';//admin
    //             // $user_id = '5c867e3ffc9aad347e165d32';//live user

    //             $buyCollection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
    //             $soldCollection = $exchange == "binance" ? "sold_buy_orders" : "sold_buy_orders_$exchange";

    //             $endTime = date('Y-m-d 07:00:00');
    //             $startTime = date('Y-m-d 07:00:00', strtotime('-24 hours'));
                
    //             $startTime = $this->mongo_db->converToMongodttime($startTime);
    //             $endTime =  $this->mongo_db->converToMongodttime($endTime);

    //             $childsBuyPipeline = [
    //                 [
    //                     '$match'=> [
    //                         'application_mode'=> 'live',
    //                         'admin_id'=> $user_id,
    //                         'purchased_price'=> ['$exists'=> true],
    //                         'quantity'=> ['$exists'=> true],
    //                         'status'=> [
    //                             '$nin'=> [
    //                                 'canceled',
    //                                 'error',
    //                                 'new_ERROR',
    //                                 'FILLED_ERROR',
    //                                 'submitted_ERROR',
    //                                 'LTH_ERROR',
    //                                 'canceled_ERROR',
    //                                 'credentials_ERROR',
    //                             ]
    //                         ],
    //                         'resume_order_id' => ['$exists' => false],
    //                         'buy_date'=> [
    //                             '$gte'=> $startTime, 
    //                             '$lte'=> $endTime
    //                         ],
    //                     ]
    //                 ],
    //                 [ 
    //                     '$project'=> [ 
    //                         "symbol"=> 1,
    //                         "quantity"=> 1,
    //                         "purchased_price"=> 1,
    //                     ]
    //                 ]
    //             ];
    //             $buy_orders = $connetct->$buyCollection->aggregate($childsBuyPipeline);
    //             $buy_orders = iterator_to_array($buy_orders);
    
    //             $sold_orders = $connetct->$soldCollection->aggregate($childsBuyPipeline);
    //             $sold_orders = iterator_to_array($sold_orders);
    //             $orders = array_merge($buy_orders, $sold_orders);
    //             // echo count($orders)."-----------".count($buy_orders)."---------------".count($sold_orders)."<br>";
    //             unset($buy_orders, $sold_orders);

    //             $BTCUSDT_price = $this->get_market_price('BTCUSDT', $exchange);
                
    //             if(!empty($orders) && !empty($BTCUSDT_price)){
    //                 //get current market_price for BTCUSDT
                    
    //                 $total_usd_buy_worth = 0;
    //                 $order_count = count($orders);
    //                 //for loop on orders to find total buy worth
    //                 for($j=0; $j<$order_count; $j++){
                        
    //                     $tarr = explode('USDT', $orders[$j]['symbol']);
    //                     if (isset($tarr[1]) && $tarr[1] == '') {
    //                         // echo "\r\n USDT coin";
    //                         $usd_balance = $orders[$j]['quantity'] * $orders[$j]['purchased_price'];
    //                         $usd_balance = number_format($usd_balance, 2);
    //                     } else {
    //                         // echo "\r\n BTC coin";
    //                         $usd_balance = $orders[$j]['quantity'] * $orders[$j]['purchased_price'] * $BTCUSDT_price;
    //                         $usd_balance = number_format($usd_balance, 2);
    //                     }
    //                     $total_usd_buy_worth += (!is_nan($usd_balance) ? $usd_balance : 0);
    //                 }

    //                 // echo "today buy_usd_worth $ $total_usd_buy_worth <br>";

    //                 // find points consumed today $100 = 1 point
    //                 $pointUsdVal = 100;
    //                 $points_consumed = $total_usd_buy_worth / $pointUsdVal;
    //                 $points_consumed = number_format($points_consumed, 2);
    //                 $this->update_trading_points($user_id, $exchange, $points_consumed);
    //                 //update current trading points 
    //             }
    //         }
    //     }

    //     // Save last Cron Executioon
    //     $this->last_cron_execution_time('calculate_current_trading_points', '1d', 'Binnace cronjob to calculate trading points (* 7 * * *)', 'tradingPoints');

    //     echo "<br><br> ********************** End Script **************************** <br>";
    //     return;
    // } //End calculate current trading points

    //calculate current trading points bam
    // public function calculate_current_trading_points_bam()
    // {

    //     // echo "<pre> testing ";
    //     $connetct = $this->mongo_db->customQuery();

    //     //get binance users with API key secret
    //     $exchange = "bam";
    //     $collection = $exchange == "binance" ? "users" : $exchange . "_credentials";

    //     $pipeline = [
    //         [
    //             '$match' => [
    //                 'api_key' => ['$exists' => true],
    //                 'api_secret' => ['$exists' => true],
    //                 'application_mode' => 'both'
    //             ]
    //         ],
    //         [
    //             '$group' => [
    //                 '_id' => null,
    //                 'user_ids' => ['$push' => ['$toString' => '$_id']]
    //             ]
    //         ],
    //         [
    //             '$project' => [
    //                 'user_ids' => 1,
    //                 '_id' => 0,
    //             ]
    //         ]
    //     ];

    //     //only pick user with api_secret credentials for exchanges
    //     if ($exchange != 'binance' && $exchange != '') {
    //         $collection_t = $exchange . "_credentials";
    //         $pipeline_t = [
    //             [
    //                 '$match' => [
    //                     'api_key' => ['$exists' => true],
    //                     'api_secret' => ['$exists' => true],
    //                 ],
    //             ],
    //             [
    //                 '$group' => [
    //                     '_id' => null,
    //                     'user_ids' => ['$push' => ['$toObjectId' => '$user_id']],
    //                 ],
    //             ],
    //             [
    //                 '$project' => [
    //                     'user_ids' => 1,
    //                     '_id' => 0,
    //                 ],
    //             ],
    //         ];

    //         $match_users = $connetct->$collection_t->aggregate($pipeline_t);
    //         $match_users = iterator_to_array($match_users);
    //         if (!empty($match_users)) {
    //             $match_user_ids = array_column($match_users, 'user_ids');
    //             $pipeline[0]['$match']['_id']['$in'] = $match_user_ids[0];
    //             unset($pipeline[0]['$match']['api_key'], $pipeline[0]['$match']['api_secret']);
    //         }
    //         unset($collection_t, $pipeline_t, $match_users, $match_user_ids);
    //     }

    //     $users = $connetct->users->aggregate($pipeline);
    //     $users = iterator_to_array($users);

    //     if (!empty($users)) {
    //         $user_ids = $users[0]['user_ids'];
    //         $user_ids = (array) $user_ids;

    //         if (empty($user_ids)) {
    //             return;
    //         }

    //         $user_ids = array_unique($user_ids);

    //         // print_r($user_ids);

    //         // shuffle($user_ids);

    //         $total_users = count($user_ids);
    //         //for loop on users
    //         for ($i = 0; $i < $total_users; $i++) {
    //             $user_id = $user_ids[$i];

    //             // $user_id = '5c0912b7fc9aadaac61dd072';//admin
    //             // $user_id = '5c867e3ffc9aad347e165d32';//live user

    //             $buyCollection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
    //             $soldCollection = $exchange == "binance" ? "sold_buy_orders" : "sold_buy_orders_$exchange";

    //             $endTime = date('Y-m-d 07:00:00');
    //             $startTime = date('Y-m-d 07:00:00', strtotime('-24 hours'));

    //             $startTime = $this->mongo_db->converToMongodttime($startTime);
    //             $endTime =  $this->mongo_db->converToMongodttime($endTime);

    //             $childsBuyPipeline = [
    //                 [
    //                     '$match' => [
    //                         'application_mode' => 'live',
    //                         'admin_id' => $user_id,
    //                         'purchased_price' => ['$exists' => true],
    //                         'quantity' => ['$exists' => true],
    //                         'status' => [
    //                             '$nin' => [
    //                                 'canceled',
    //                                 'error',
    //                                 'new_ERROR',
    //                                 'FILLED_ERROR',
    //                                 'submitted_ERROR',
    //                                 'LTH_ERROR',
    //                                 'canceled_ERROR',
    //                                 'credentials_ERROR',
    //                             ]
    //                         ],
    //                         'resume_order_id' => ['$exists' => false],
    //                         'buy_date' => [
    //                             '$gte' => $startTime,
    //                             '$lte' => $endTime
    //                         ],
    //                     ]
    //                 ],
    //                 [
    //                     '$project' => [
    //                         "symbol" => 1,
    //                         "quantity" => 1,
    //                         "purchased_price" => 1,
    //                     ]
    //                 ]
    //             ];
    //             $buy_orders = $connetct->$buyCollection->aggregate($childsBuyPipeline);
    //             $buy_orders = iterator_to_array($buy_orders);

    //             $sold_orders = $connetct->$soldCollection->aggregate($childsBuyPipeline);
    //             $sold_orders = iterator_to_array($sold_orders);
    //             $orders = array_merge($buy_orders, $sold_orders);
    //             // echo count($orders)."-----------".count($buy_orders)."---------------".count($sold_orders)."<br>";
    //             unset($buy_orders, $sold_orders);

    //             $BTCUSDT_price = $this->get_market_price('BTCUSDT', $exchange);

    //             if (!empty($orders) && !empty($BTCUSDT_price)) {
    //                 //get current market_price for BTCUSDT

    //                 $total_usd_buy_worth = 0;
    //                 $order_count = count($orders);
    //                 //for loop on orders to find total buy worth
    //                 for ($j = 0; $j < $order_count; $j++) {

    //                     $tarr = explode('USDT', $orders[$j]['symbol']);
    //                     if (isset($tarr[1]) && $tarr[1] == '') {
    //                         // echo "\r\n USDT coin";
    //                         $usd_balance = $orders[$j]['quantity'] * $orders[$j]['purchased_price'];
    //                         $usd_balance = number_format($usd_balance, 2);
    //                     } else {
    //                         // echo "\r\n BTC coin";
    //                         $usd_balance = $orders[$j]['quantity'] * $orders[$j]['purchased_price'] * $BTCUSDT_price;
    //                         $usd_balance = number_format($usd_balance, 2);
    //                     }
    //                     $total_usd_buy_worth += (!is_nan($usd_balance) ? $usd_balance : 0);
    //                 }

    //                 // echo "today buy_usd_worth $ $total_usd_buy_worth <br>";

    //                 // find points consumed today $100 = 1 point
    //                 $pointUsdVal = 100;
    //                 $points_consumed = $total_usd_buy_worth / $pointUsdVal;
    //                 $points_consumed = number_format($points_consumed, 2);
    //                 $this->update_trading_points($user_id, $exchange, $points_consumed);
    //                 //update current trading points 
    //             }
    //         }
    //     }

    //     // Save last Cron Executioon
    //     $this->last_cron_execution_time('calculate_current_trading_points_bam', '1d', 'Bam cronjob to calculate trading points (* 7 * * *)', 'tradingPoints');

    //     echo "<br><br> ********************** End Script **************************** <br>";
    //     return;
    // }//End calculate current trading points bam
    
    //calculate current trading points kraken
    // public function calculate_current_trading_points_kraken()
    // {

    //     // echo "<pre> testing ";
    //     $connetct = $this->mongo_db->customQuery();

    //     //get binance users with API key secret
    //     $exchange = "kraken";
    //     $collection = $exchange == "binance" ? "users" : $exchange . "_credentials";

    //     $pipeline = [
    //         [
    //             '$match' => [
    //                 'api_key' => ['$exists' => true],
    //                 'api_secret' => ['$exists' => true],
    //                 'application_mode' => 'both'
    //             ]
    //         ],
    //         [
    //             '$group' => [
    //                 '_id' => null,
    //                 'user_ids' => ['$push' => ['$toString' => '$_id']]
    //             ]
    //         ],
    //         [
    //             '$project' => [
    //                 'user_ids' => 1,
    //                 '_id' => 0,
    //             ]
    //         ]
    //     ];

    //     //only pick user with api_secret credentials for exchanges
    //     if ($exchange != 'binance' && $exchange != '') {
    //         $collection_t = $exchange . "_credentials";
    //         $pipeline_t = [
    //             [
    //                 '$match' => [
    //                     'api_key' => ['$exists' => true],
    //                     'api_secret' => ['$exists' => true],
    //                 ],
    //             ],
    //             [
    //                 '$group' => [
    //                     '_id' => null,
    //                     'user_ids' => ['$push' => ['$toObjectId' => '$user_id']],
    //                 ],
    //             ],
    //             [
    //                 '$project' => [
    //                     'user_ids' => 1,
    //                     '_id' => 0,
    //                 ],
    //             ],
    //         ];

    //         $match_users = $connetct->$collection_t->aggregate($pipeline_t);
    //         $match_users = iterator_to_array($match_users);
    //         if (!empty($match_users)) {
    //             $match_user_ids = array_column($match_users, 'user_ids');

    //             $pipeline[0]['$match']['_id']['$in'] = $match_user_ids[0];
    //             unset($pipeline[0]['$match']['api_key'], $pipeline[0]['$match']['api_secret']);
    //         }
    //         unset($collection_t, $pipeline_t, $match_users, $match_user_ids);
    //     }

    //     $users = $connetct->users->aggregate($pipeline);
    //     $users = iterator_to_array($users);

    //     if (!empty($users)) {
    //         $user_ids = $users[0]['user_ids'];
    //         $user_ids = (array) $user_ids;

    //         if (empty($user_ids)) {
    //             return;
    //         }

    //         $user_ids = array_unique($user_ids);

    //         // print_r($user_ids);

    //         // shuffle($user_ids);

    //         $total_users = count($user_ids);
    //         //for loop on users
    //         for ($i = 0; $i < $total_users; $i++) {
    //             $user_id = $user_ids[$i];

    //             // $user_id = '5c0912b7fc9aadaac61dd072';//admin
    //             // $user_id = '5c867e3ffc9aad347e165d32';//live user

    //             $buyCollection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
    //             $soldCollection = $exchange == "binance" ? "sold_buy_orders" : "sold_buy_orders_$exchange";

    //             $endTime = date('Y-m-d 07:00:00');
    //             $startTime = date('Y-m-d 07:00:00', strtotime('-24 hours'));

    //             $startTime = $this->mongo_db->converToMongodttime($startTime);
    //             $endTime =  $this->mongo_db->converToMongodttime($endTime);

    //             $childsBuyPipeline = [
    //                 [
    //                     '$match' => [
    //                         'application_mode' => 'live',
    //                         'admin_id' => $user_id,
    //                         'purchased_price' => ['$exists' => true],
    //                         'quantity' => ['$exists' => true],
    //                         'status' => [
    //                             '$nin' => [
    //                                 'canceled',
    //                                 'error',
    //                                 'new_ERROR',
    //                                 'FILLED_ERROR',
    //                                 'submitted_ERROR',
    //                                 'LTH_ERROR',
    //                                 'canceled_ERROR',
    //                                 'credentials_ERROR',
    //                             ]
    //                         ],
    //                         'resume_order_id' => ['$exists' => false],
    //                         'buy_date' => [
    //                             '$gte' => $startTime,
    //                             '$lte' => $endTime
    //                         ],
    //                     ]
    //                 ],
    //                 [
    //                     '$project' => [
    //                         "symbol" => 1,
    //                         "quantity" => 1,
    //                         "purchased_price" => 1,
    //                     ]
    //                 ]
    //             ];
    //             $buy_orders = $connetct->$buyCollection->aggregate($childsBuyPipeline);
    //             $buy_orders = iterator_to_array($buy_orders);

    //             $sold_orders = $connetct->$soldCollection->aggregate($childsBuyPipeline);
    //             $sold_orders = iterator_to_array($sold_orders);
    //             $orders = array_merge($buy_orders, $sold_orders);
    //             // echo count($orders)."-----------".count($buy_orders)."---------------".count($sold_orders)."<br>";
    //             unset($buy_orders, $sold_orders);

    //             $BTCUSDT_price = $this->get_market_price('BTCUSDT', $exchange);

    //             if (!empty($orders) && !empty($BTCUSDT_price)) {
    //                 //get current market_price for BTCUSDT

    //                 $total_usd_buy_worth = 0;
    //                 $order_count = count($orders);
    //                 //for loop on orders to find total buy worth
    //                 for ($j = 0; $j < $order_count; $j++) {

    //                     $tarr = explode('USDT', $orders[$j]['symbol']);
    //                     if (isset($tarr[1]) && $tarr[1] == '') {
    //                         // echo "\r\n USDT coin";
    //                         $usd_balance = $orders[$j]['quantity'] * $orders[$j]['purchased_price'];
    //                         $usd_balance = number_format($usd_balance, 2);
    //                     } else {
    //                         // echo "\r\n BTC coin";
    //                         $usd_balance = $orders[$j]['quantity'] * $orders[$j]['purchased_price'] * $BTCUSDT_price;
    //                         $usd_balance = number_format($usd_balance, 2);
    //                     }
    //                     $total_usd_buy_worth += (!is_nan($usd_balance) ? $usd_balance : 0);
    //                 }

    //                 // echo "<br> today buy_usd_worth $ $total_usd_buy_worth <br>";

    //                 // find points consumed today $100 = 1 point
    //                 $pointUsdVal = 100;
    //                 $points_consumed = $total_usd_buy_worth / $pointUsdVal;
    //                 $points_consumed = number_format($points_consumed, 2);
    //                 $this->update_trading_points($user_id, $exchange, $points_consumed);
    //                 //update current trading points 
    //             }
    //         }
    //     }

    //     // Save last Cron Executioon
    //     $this->last_cron_execution_time('calculate_current_trading_points_kraken', '1d', 'Kraken cronjob to calculate trading points (* 7 * * *)', 'tradingPoints');

    //     echo "<br><br> ********************** End Script **************************** <br>";
    //     return;
    // }//End calculate current trading points kraken

    //get_market_price 
    public function get_market_price($coin, $exchange){

        $collection = ($exchange == 'binance') ? 'market_prices' : 'market_prices_'.$exchange;

        $this->mongo_db->where(array('coin' => $coin));
        $this->mongo_db->limit(1);
        $this->mongo_db->sort(array('created_date' => 'desc'));
        $responseArr = $this->mongo_db->get($collection);
        $price = iterator_to_array($responseArr);
        if (!empty($price)) {
            return num($price[0]['price']);
        } else {
            return 0;
        }
    }//end get_market_price

    //update_trading_points
    // public function update_trading_points($user_id, $exchange, $points_consumed){

    //     exit;
    //     // echo "<br> update_trading_points  $user_id  , $exchange  , $points_consumed <br>";

    //     $exchange = 'binance';
    //     $points_key = $exchange == 'binance' ? "current_trading_points" : "current_trading_points_$exchange";

    //     $user_id = $this->mongo_db->mongoId($user_id);
    //     $connetct = $this->mongo_db->customQuery();
    //     // $user = $connetct->users->find(['_id'=>$user_id], [$points_key =>1]);
    //     $user = $connetct->users->find(['_id'=>$user_id]);
    //     $user = iterator_to_array($user);
        
    //     if(!empty($user)){
            
    //         $previous_points = $this->get_total_consumed_trading_points($user_id, $exchange);

    //         $user_id = (string) $user_id;
    //         // echo "<br> $user_id, previous points:  $previous_points , consumed points: $points_consumed <br>";
    //         // return;

    //         //Save history entry
    //         $trading_points_collection =  $exchange == 'binance' ? "trading_points_history" : "trading_points_history_$exchange";
    //         $insert_data = [
    //             'user_id'=> (string) $user_id,
    //             'action'=> 'deduct',
    //             'points_consumed'=> (float) $points_consumed,
    //             'previous_points'=> (float) $previous_points,
    //             'created_date'=> $this->mongo_db->converToMongodttime(date("Y-m-d H:i:s")),
    //             'hassan_updated'=>1,
    //         ];
    //         $connetct->$trading_points_collection->insertOne($insert_data);

    //         // //Deduct daily points consumed
    //         // $curr_points = $previous_points - $points_consumed;
    //         // $curr_points = number_format($curr_points,2);
    //         // $curr_points = $curr_points < 0 ? 0 : $curr_points;

    //         // $update_data = [
    //         //     '$set' => [
    //         //         $points_key => (float) $curr_points,
    //         //     ]
    //         // ];
    //         // $user_id = $this->mongo_db->mongoId($user_id);
    //         // $connetct->users->updateOne(['_id'=>$user_id], $update_data);
    //     }
    //     return;

    // }//End update_trading_points

    // public function get_total_consumed_trading_points($user_id, $exchange){
    //     exit;
    //     $exchange = 'binance';
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
    
    // public function get_current_trading_points($user_id, $exchange){

    //     $total_points_buy = $this ->get_total_buy_trading_points($user_id, $exchange);
    //     $total_points_consumed = $this ->get_total_consumed_trading_points($user_id, $exchange);
    //     $curr_points = $total_points_buy - $total_points_consumed; 
    //     // echo "<pre>";
    //     // echo "$curr_points = $total_points_buy - $total_points_consumed";
    //     return (!is_nan($curr_points) ? $curr_points : 0);
    // }

    // public function check_trading_points_exceed($user_id=''){

    //     exit;
    //     // get users with trading on
    //     $db = $this->mongo_db->customQuery();
    //     $pipeline = [];
    //     if($user_id != ''){
    //         $user_id_object = $this->mongo_db->mongoId($user_id);
    //         $pipeline[] = ['$match' => ['_id'=>$user_id_object]];
    //     }else{
    //         $pipeline[] = ['$match' => ['application_mode'=>['$in'=>['live', 'both', 'BOTH', 'LIVE']]]];
    //     }
    //     $pipeline[] = ['$project' => ['_id'=>1, 'username'=>1]];

    //     $users = $db->users->aggregate($pipeline);
    //     $users = iterator_to_array($users);
    //     $total_users = count($users);
        
    //     // echo "<pre>";

    //     for($i = 0; $i < $total_users; $i++){

    //         // $username = $users[$i]['username'] ?? '';
    //         $user_id = (string) $users[$i]['_id'];
            
    //         //skip admin and vizzDeveloper
    //         if($user_id == '5c0912b7fc9aadaac61dd072' || $user_id == '5c0915befc9aadaac61dd1b8'){
    //             continue;
    //         }

    //         $trading_points = $this->get_current_trading_points($user_id, 'binance');
            
    //         // echo "$username  ----  $user_id  ----  $trading_points<br>";

    //         //if trading points not exceeded
    //         if($trading_points > 0){
    //             //binance
    //             $db->users->updateOne(['_id' => $users[$i]['_id']], ['$set' => ['trading_status' => 'on']]);
    //             //bam
    //             $db->bam_credentials->updateOne(['user_id' => (string) $users[$i]['_id']], ['$set' => ['trading_status' => 'on']]);
    //             //kraken
    //             $db->kraken_credentials->updateOne(['user_id' => (string) $users[$i]['_id']], ['$set' => ['trading_status' => 'on']]);
    //         }else{
    //             //if trading points are exceeded
    //             //binance
    //             $db->users->updateOne(['_id' => $users[$i]['_id']], ['$set' => ['trading_status' => 'off']]);
    //             $db->buy_orders->updateMany(['admin_id' => (string) $users[$i]['_id'], 'parent_status' => 'parent', 'status' => ['$ne'=>'canceled']], ['$set' => ['pick_parent' => 'no','checking_points_exceed'=>1]]);
    //             $db->buy_orders->updateMany(['admin_id' => (string) $users[$i]['_id'],'cavg_parent' => 'yes','cost_avg' => ['$ne'=>'completed'], 'status' => ['$ne'=>'canceled']], ['$set' => ['pick_parent' => 'no']]);

    //             //bam
    //             $db->bam_credentials->updateOne(['user_id' => (string) $users[$i]['_id']], ['$set' => ['trading_status' => 'off']]);
    //             $db->buy_orders_bam->updateMany(['admin_id' => (string) $users[$i]['_id'], 'parent_status' => 'parent', 'status' => ['$ne'=>'canceled']], ['$set' => ['pick_parent' => 'no']]);
    //             $db->buy_orders_bam->updateMany(['admin_id' => (string) $users[$i]['_id'],'cavg_parent' => 'yes','cost_avg' => ['$ne'=>'completed'], 'status' => ['$ne'=>'canceled']], ['$set' => ['pick_parent' => 'no']]);

    //             //kraken
    //             $db->kraken_credentials->updateOne(['user_id' => (string) $users[$i]['_id']], ['$set' => ['trading_status' => 'off']]);
    //             $db->buy_orders_kraken->updateMany(['admin_id' => (string) $users[$i]['_id'], 'parent_status' => 'parent', 'status' => ['$ne'=>'canceled']], ['$set' => ['pick_parent' => 'no','checking_points_exceed'=>1]]);
    //             $db->buy_orders_kraken->updateMany(['admin_id' => (string) $users[$i]['_id'],'cavg_parent' => 'yes','cost_avg' => ['$ne'=>'completed'], 'status' => ['$ne'=>'canceled']], ['$set' => ['pick_parent' => 'no']]);


    //             $params = [
    //                 'user_id' => (string) $users[$i]['_id'],
    //                 'type' => 'tradeing_points',
    //                 'log' => "user trading points exceeded $trading_points",
    //             ];
    //             $req_arr = [
    //                 'req_type' => 'POST',
    //                 'req_params' => $params,
    //                 'req_url' => 'https://app.digiebot.com/admin/Api_calls/important_user_activity_logs',
    //             ];
    //             $resp = hitCurlRequest($req_arr);
    //         }

    //         $this->send_trading_points_email($user_id, 'binance');

    //         sleep(1);
    //     }

    //     //Save last Cron Executioon
    //     $last_cron_exchange = "check_trading_points_exceed";
    //     $this->last_cron_execution_time($last_cron_exchange, "1d", "Cronjob to turn of rading for users who's trading points exceed (0 4 * * *)", 'pickParent');

    //     // print_r($users);

    //     echo "<br><br> ***********  End Script ************";
        
    // }

    // public function send_trading_points_email($user_id, $exchange='binance'){

    //     $exchange = 'binance';

    //     $user_id = $this->mongo_db->mongoId((string) $user_id);
    //     $db = $this->mongo_db->customQuery();

    //     $pipeline = [
    //         [
    //             '$match'=> [ 
    //                 '_id' => $user_id,
    //                 'application_mode' => [ '$in' =>[ 'both', 'live' ] ],
    //             ]
    //         ],
    //         // [
    //         //     '$count' => 'total',
    //         // ],
    //         [
    //             '$limit' => 1,
    //         ]
    //     ];

    //     $user = $db->users->aggregate($pipeline);
    //     $user = iterator_to_array($user);

    //     // echo "<pre>";
    //     // print_r($user);
    //     // die('***************** testing ******************');

    //     if(!empty($user)){

    //         foreach($user as $uVal){
    //             // $user = $user[0];
    //             $user = $uVal;

    //             $user_id = (string) $uVal['_id'];
    
    //             $total_points_buy = $this ->get_total_buy_trading_points((string) $user_id, $exchange);
    //             $total_points_consumed = $this ->get_total_consumed_trading_points((string) $user_id, $exchange);
    //             $curr_points = $total_points_buy - $total_points_consumed; 
    //             $curr_points = (!is_nan($curr_points) ? $curr_points : 0);

    //             // echo "$user_id   =>>>>  $curr_points = $total_points_buy - $total_points_consumed <br>";
    
    //             $send_mail = false;
    //             $days_delay = 0;
    
    //             $email_subject = '';
    //             $email_body = '';
    
    //             if($curr_points <= 0){
                    
    //                 $send_mail = true;
    //                 $days_delay = 1;
    
    //                 $email_subject = 'Your Digie Trading points are too low for trading';
    //                 $email_body = "Your Digie Trading points are too low for trading. Your current trading points are $curr_points. <br>";
    //                 $email_body .= "Please buy more trading points to resume trading on DigieBot.";
    
    //             }else if($curr_points <= 10){
                    
    //                 $send_mail = true;
    //                 $days_delay = 2;
    
    //                 $email_subject = 'Your trading points are low';
    //                 $email_body = "Your trading points are low. Your current trading points are $curr_points. <br>";
    //                 $email_body .= "When all your trading points will be consumed your trading will be stopped automatically. <br>";
    //                 $email_body .= "So please make sure you have enough trading points available to continue uninterrupted trading.";
    
    //             }else if($curr_points <= 25){
    
    //                 $send_mail = true;
    //                 $days_delay = 5;
    
    //                 $email_subject = 'Your trading points are getting low';
    //                 $email_body = "Your trading points are getting low. Your current trading points are $curr_points. <br>";
    //                 $email_body .= "When all your trading points are consumed your trading will be stopped automatically. <br>";
    //                 $email_body .= "So please make sure you have enough trading points available to continue uninterrupted trading.";
    
    //             }else if($curr_points <= 50){
    
    //                 // $send_mail = true;
    //                 // $days_delay = 7;
    
    //                 // $email_subject = 'Your trading points are getting low';
    //                 // $email_body = "Your trading points are getting low. Your current trading points are $curr_points. <br>";
    //                 // $email_body .= "When all your trading points are consumed your trading will be stopped. <br>";
    //                 // $email_body .= "So please make sure you have enough trading points available to continue uninterrupted trading.";
    
    //             }else{
    //                 //do nothing
    //             }
    
    //             if($send_mail && $total_points_buy > 0){
    //                 $email_sent_at = date('Y-m-d H:m:s', strtotime("-$days_delay days"));
    //                 $email_sent_at = $this->mongo_db->converToMongodttime($email_sent_at);
        
    //                 $trading_points_remaining_email_notifications = 'trading_points_remaining_email_notifications';
        
    //                 $where = [
    //                     'user_id' => (string) $user_id,
    //                     'email_sent_at' => ['$gte' => $email_sent_at],
    //                 ];
        
    //                 $emails = $db->$trading_points_remaining_email_notifications->find($where);
    //                 $emails = iterator_to_array($emails); 
    //                 if(empty($emails)){
                        
    //                     //send email
    //                     send_mail((string) $user_id, $email_subject, $email_body); 
    
    //                     //update in email notifications collections
    //                     $email_sent_at = date('Y-m-d H:m:s');
    //                     $email_sent_at = $this->mongo_db->converToMongodttime($email_sent_at);
    
    //                     $insData = [
    //                         'user_id' => (string) $user_id,
    //                         'curr_points' => $curr_points,
    //                         'email_subject' => $email_subject,
    //                         'email_body' => $email_body,
    //                         'email_sent_at' => $email_sent_at, 
    //                     ];
    //                     $insResult = $db->$trading_points_remaining_email_notifications->insertOne($insData);
    
    //                     break;
    //                 }
    //             }
    //         }

    //     }

    //     echo "************ End **************";
    //     return true;
    // }

    //********************   End calculate trading points cron   *********************** */


    //********************   Check API key valid cron   *************************** */

    // ======================================================================================================//
    // I STOPPED THIS CRON ON 12 JAN 2022 NEED TO DISCUSS THIS WITH HASSAN BHAI SHEHZAD BHAI AND BOSS.(REASON BECAUSE 
    //UPDATING KRAKEN_CREDENTIALS API_KEY_VALID = NO EVERYTIME.)
    // =====================================================================================================//
    public function check_api_key_invalid($user_id=''){

        // get users with trading on
        $db = $this->mongo_db->customQuery();
        $pipeline = [];
        if($user_id != ''){
            $user_id_object = $this->mongo_db->mongoId($user_id);
            $pipeline[] = ['$match' => ['_id'=>$user_id_object]];
        }else{
            $pipeline[] = ['$match' => ['application_mode'=>['$in'=>['live', 'both', 'BOTH', 'LIVE']]]];
        }
        $pipeline[] = ['$project' => ['_id'=>1, 'username'=>1]];

        $users = $db->users->aggregate($pipeline);
        $users = iterator_to_array($users);
        $total_users = count($users);
        
        // echo "<pre>";

        for($i = 0; $i < $total_users; $i++){

            // $username = $users[$i]['username'] ?? '';
            $user_id = (string) $users[$i]['_id'];
            
            //skip admin and vizzDeveloper
            if($user_id == '5c0912b7fc9aadaac61dd072' || $user_id == '5c0915befc9aadaac61dd1b8'){
                continue;
            }

            $keyArr = [];

            //if check api key valid/invalid
            //binance
            if($this->is_api_key_valid($user_id, 'binance')){
                $db->users->updateOne(['_id' => $users[$i]['_id']], ['$set' => ['is_api_key_valid' => 'yes']]);
                $keyArr[] = 'binance';
            }else{
                $db->users->updateOne(['_id' => $users[$i]['_id']], ['$set' => ['is_api_key_valid' => 'no','api_line_number'=>'cronjob=>4253']]);
                $db->buy_orders->updateMany(['admin_id' => $user_id, 'parent_status' => 'parent', 'status' => ['$ne'=>'canceled']], ['$set' => ['pick_parent' => 'no']]);
            }

            //kraken
            if($this->is_api_key_valid($user_id, 'kraken')){
                $db->kraken_credentials->updateOne(['user_id' => $user_id], ['$set' => ['is_api_key_valid' => 'yes']]);
                $keyArr[] = 'kraken';
            }else{
                $db->kraken_credentials->updateOne(['user_id' => $user_id], ['$set' => ['is_api_key_valid' => 'no','api_line_number'=>'cronjob=>4262']]);
                $db->buy_orders_kraken->updateMany(['admin_id' => $user_id, 'parent_status' => 'parent', 'status' => ['$ne'=>'canceled']], ['$set' => ['pick_parent' => 'no','api_line_number'=>'cronjob=>4262']]);
            }
            
            //bam
            if($this->is_api_key_valid($user_id, 'bam')){
                $db->bam_credentials->updateOne(['user_id' => $user_id], ['$set' => ['is_api_key_valid' => 'yes']]);
                $keyArr[] = 'bam';
            }else{
                $db->bam_credentials->updateOne(['user_id' => $user_id], ['$set' => ['is_api_key_valid' => 'no','api_line_number'=>'cronjob=>4271']]);
                $db->buy_orders_bam->updateMany(['admin_id' => $user_id, 'parent_status' => 'parent', 'status' => ['$ne'=>'canceled']], ['$set' => ['pick_parent' => 'no']]);
            }

            $userExchanges = implode(' | ', $keyArr);
            unset($keyArr);
            // echo "$username  ----  $user_id  ----  $userExchanges<br>";
            
            // // sleep for 1 second
            // sleep(1);

            // //sleep for one second
            // usleep(1000000);

            //sleep for one second
            usleep(1000000);
            
            // //sleep for quarter of a second
            // usleep(250000);

        }
        
        $last_cron_exchange = "check_api_key_invalid";
        $this->last_cron_execution_time($last_cron_exchange, "1d", "Cronjob to turn of rading for users with invalid API Key (0 3 * * *)", 'pickParent');        
        
        echo "<br><br> ***********  End Script ************";
        
    }

    public function is_api_key_valid($user_id, $exchange){
        //Hit CURL to check api key valid/invalid
        $params = [
            'user_id' => (string) $user_id,
            'exchange' => $exchange,
        ];
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => '',
            'req_url' => 'https://app.digiebot.com/admin/api_calls/verify_api_key_secret',
        ];
        $resp = hitCurlRequest($req_arr);
        // $resp = array('http_code' => $http_code, 'response' => $response, 'error' => $err);
        if($resp['http_code'] == 200 && $resp['response']['status'] == true){
            unset($resp);
            return true;
        }else{
            unset($resp);
            return false;
        }
    }
    //********************   End Check API key valid cron   *********************** */
    
    
    //********************   Randomize parent sort cron   *********************** */

    //randomize_sort_number
    public function randomize_sort_number(){

        // echo "<pre>";
        // echo date('Y-m-d H:i:s');

        $exchange = 'binance';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";

        $last_run = date('Y-m-d H:i:s', strtotime('-2 days'));
        $curr_time = date('Y-m-d H:i:s');
        $curr_time = $this->mongo_db->converToMongodttime($curr_time);

        $pipeline = [
            [
                '$match' => [
                    'application_mode' => 'live',
                    'parent_status' => 'parent',
                    'status'=> ['$ne'=>'canceled'],
                    // 'pause_status' => 'play',
                    '$or' => [
                        ['randomize_sort_date' => ['$exists' => false]],
                        ['randomize_sort_date' => ['$lte' => $this->mongo_db->converToMongodttime($last_run)]],
                    ],
                ],
            ],
            [
                '$sort' => ['randomize_sort_date' => 1],
            ],
            [
                '$limit' => 200,
            ],
            [
                '$project' => [
                    '_id' => 1,
                ],
            ],
        ];
        $db = $this->mongo_db->customQuery();
        
        //uset cancelled parents so they don't get picked in crons
        $db->$buy_collection->updateMany(['parent_status' => 'parent', 'status' => 'canceled'], ['$unset' => ['randomize_sort'=>1, 'randomize_sort_date'=>1, 'pick_parent'=>1]]);
        $db->$buy_collection->updateMany(['cavg_parent'=>'yes','parent_status' => ['$ne'=>'parent'], 'status' => 'canceled','cost_avg'=>['$in'=>['yes','taking_child']]], ['$unset' => ['randomize_sort'=>1, 'randomize_sort_date'=>1, 'pick_parent'=>1]]);

        $parent_orders = $db->$buy_collection->aggregate($pipeline);
        $parent_orders = iterator_to_array($parent_orders);

        if(!empty($parent_orders)){
            $count = count($parent_orders);
            for($i=0; $i<$count; $i++){
                $db->$buy_collection->updateOne(['_id'=>$parent_orders[$i]['_id']], ['$set'=>['randomize_sort'=>rand(0,1000), 'randomize_sort_date'=>$curr_time]]);
                //********************************** Cost avg parent randomize ***********************************//
                $db->$buy_collection->updateMany(['buy_parent_id'=>$parent_orders[$i]['_id'],'cavg_parent'=>'yes','parent_status' => ['$ne'=>'parent'], 'status' => ['$ne'=>'canceled'],'cost_avg'=>['$in'=>['yes','taking_child']]], ['$set'=>['randomize_sort'=>rand(0,1000), 'randomize_sort_date'=>$curr_time]]);
            }
        }
        unset($parent_orders);

        //Save last Cron Executioon
        $this->last_cron_execution_time('randomize_sort_number_binance', '20m', 'Cronjob to randomly sort parents for binance (*/20 * * * *)', 'randomizeSort');

        // echo "<br>";
        // echo date('Y-m-d H:i:s');
        echo "<br> ***************** script end ********************** <br>";
        
    }//end randomize_sort_number
    
    //randomize_sort_number_kraken
    public function randomize_sort_number_kraken(){

        // echo "<pre>";
        // echo date('Y-m-d H:i:s');

        $exchange = 'kraken';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";

        $last_run = date('Y-m-d H:i:s', strtotime('-2 days'));
        $curr_time = date('Y-m-d H:i:s');
        $curr_time = $this->mongo_db->converToMongodttime($curr_time);

        $pipeline = [
            [
                '$match' => [
                    'application_mode' => 'live',
                    'parent_status' => 'parent',
                    'status'=> ['$ne'=>'canceled'],
                    // 'pause_status' => 'play',
                    '$or' => [
                        ['randomize_sort_date' => ['$exists' => false]],
                        ['randomize_sort_date' => ['$lte' => $this->mongo_db->converToMongodttime($last_run)]],
                    ],
                ],
            ],
            [
                '$sort' => ['randomize_sort_date' => 1],
            ],
            [
                '$limit' => 200,
            ],
            [
                '$project' => [
                    '_id' => 1,
                ],
            ],
        ];
        $db = $this->mongo_db->customQuery();
        
        //uset cancelled parents so they don't get picked in crons
        $db->$buy_collection->updateMany(['parent_status' => 'parent', 'status' => 'canceled'], ['$unset' => ['randomize_sort'=>1, 'randomize_sort_date'=>1, 'pick_parent'=>1]]);
        $db->$buy_collection->updateMany(['cavg_parent'=>'yes','parent_status' => ['$ne'=>'parent'], 'status' => 'canceled','cost_avg'=>['$in'=>['yes','taking_child']]], ['$unset' => ['randomize_sort'=>1, 'randomize_sort_date'=>1, 'pick_parent'=>1]]);
        $parent_orders = $db->$buy_collection->aggregate($pipeline);
        $parent_orders = iterator_to_array($parent_orders);

        if(!empty($parent_orders)){
            $count = count($parent_orders);
            for($i=0; $i<$count; $i++){
                $db->$buy_collection->updateOne(['_id'=>$parent_orders[$i]['_id']], ['$set'=>['randomize_sort'=>rand(0,1000), 'randomize_sort_date'=>$curr_time]]);
                //********************************** Cost avg parent randomize ***********************************//
                $db->$buy_collection->updateMany(['buy_parent_id'=>$parent_orders[$i]['_id'],'cavg_parent'=>'yes','parent_status' => ['$ne'=>'parent'], 'status' => ['$ne'=>'canceled'],'cost_avg'=>['$in'=>['yes','taking_child']]], ['$set'=>['randomize_sort'=>rand(0,1000), 'randomize_sort_date'=>$curr_time]]);
            }
        }
        unset($parent_orders);

        //Save last Cron Executioon
        $this->last_cron_execution_time('randomize_sort_number_kraken', '20m', 'Cronjob to randomly sort parents for kraken (*/20 * * * *)', 'randomizeSort');

        // echo "<br>";
        // echo date('Y-m-d H:i:s');
        echo "<br> ***************** script end ********************** <br>";
        
    }//end randomize_sort_number_kraken
    
    //randomize_sort_number_bam
    public function randomize_sort_number_bam(){

        // echo "<pre>";
        // echo date('Y-m-d H:i:s');

        $exchange = 'bam';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";

        $last_run = date('Y-m-d H:i:s', strtotime('-2 days'));
        $curr_time = date('Y-m-d H:i:s');
        $curr_time = $this->mongo_db->converToMongodttime($curr_time);

        $pipeline = [
            [
                '$match' => [
                    'application_mode' => 'live',
                    'parent_status' => 'parent',
                    'status'=> ['$ne'=>'canceled'],
                    // 'pause_status' => 'play',
                    '$or' => [
                        ['randomize_sort_date' => ['$exists' => false]],
                        ['randomize_sort_date' => ['$lte' => $this->mongo_db->converToMongodttime($last_run)]],
                    ],
                ],
            ],
            [
                '$sort' => ['randomize_sort_date' => 1],
            ],
            [
                '$limit' => 200,
            ],
            [
                '$project' => [
                    '_id' => 1,
                ],
            ],
        ];
        $db = $this->mongo_db->customQuery();
        
        //uset cancelled parents so they don't get picked in crons
        $db->$buy_collection->updateMany(['parent_status' => 'parent', 'status' => 'canceled'], ['$unset' => ['randomize_sort'=>1, 'randomize_sort_date'=>1, 'pick_parent'=>1]]);

        $parent_orders = $db->$buy_collection->aggregate($pipeline);
        $parent_orders = iterator_to_array($parent_orders);

        if(!empty($parent_orders)){
            $count = count($parent_orders);
            for($i=0; $i<$count; $i++){
                $db->$buy_collection->updateOne(['_id'=>$parent_orders[$i]['_id']], ['$set'=>['randomize_sort'=>rand(0,1000), 'randomize_sort_date'=>$curr_time]]);
            }
        }
        unset($parent_orders);

        //Save last Cron Executioon
        $this->last_cron_execution_time('randomize_sort_number_bam', '20m', 'Cronjob to randomly sort parents for bam (*/20 * * * *)', 'randomizeSort');

        // echo "<br>";
        // echo date('Y-m-d H:i:s');
        echo "<br> ***************** script end ********************** <br>";
        
    }//end randomize_sort_number_bam


    //********************   Randomize parent sort cron   *********************** */


    /* *****************  check users daily buy limit  ******************* */
    public function get_recent_buy_orders_users(){
        $exchange = 'binance';
        $connetct = $this->mongo_db->customQuery();

        // Chek parent that exceed limit only get users 
        $user_ids = $this->get_daily_limit_exceeded_users([], $exchange);

        // Update the users to make parent pick_parent = no 
        $this->restrict_parents_for_limit_exceeded_users($user_ids, $exchapick_parent);
        // Update all the parent whos worth exceeds Like 
        // E-g- When recently a trade buy his worth is 100$ after buy will check all the parent whos worth is above 100 will make pick_parent no 
        $this->unset_pick_parent_which_exceede_daily_limit($exchange);

        $buy_collection  = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $sold_collection = $exchange == "binance" ? "sold_buy_orders" : "sold_buy_orders_$exchange";

        $start_time = date('Y-m-d H:i:s', strtotime('-4 minutes'));
        $start_time = $this->mongo_db->converToMongodttime($start_time);

        $pipeline = [
            [
                '$match' => [
                    'application_mode' => 'live',
                    'status' => ['$in' => ['FILLED', 'LTH'] ],
                    'buy_date' => ['$exists' => true ],
                    'created_date' => ['$gte' => $start_time ],
                ],
            ],
            [
                '$group' => [
                    '_id' => null,
                    'user_ids' => ['$push' => '$admin_id'],
                ],
            ],
            [
                '$project' => [
                    'user_ids' => 1,
                    '_id' => 0,
                ],
            ],
        ];

        $buy_user_ids = $connetct->$buy_collection->aggregate($pipeline);
        $buy_user_ids = iterator_to_array($buy_user_ids);
        $buy_user_ids = !empty($buy_user_ids) ? (array) $buy_user_ids[0]['user_ids'] : [];

        $sold_user_ids = $connetct->$sold_collection->aggregate($pipeline);
        $sold_user_ids = iterator_to_array($sold_user_ids);
        $sold_user_ids = !empty($sold_user_ids) ? (array) $sold_user_ids[0]['user_ids'] : [];

        $user_ids = array_merge($buy_user_ids, $sold_user_ids);
        unset($buy_user_ids, $sold_user_ids);

        $user_ids = (array) $user_ids;
        if (!empty($user_ids)) {
            $user_ids = array_unique($user_ids);
            array_values($user_ids);

            $user_ids = $this->get_daily_limit_exceeded_users($user_ids, $exchange);
            $this->restrict_parents_for_limit_exceeded_users($user_ids, $exchange);
            unset($user_ids);
        }

        //Save last Cron Executioon
        $last_cron_exchange = "check_recent_buy_orders_users_$exchange";
        $this->last_cron_execution_time($last_cron_exchange, '3m', 'Cronjob to check user buy limit after 3 minutes binance (*/3 * * * *)', 'dailyLimit');

        echo "<br> **************** cron end **************** <br>";
        return;
    }

    public function get_recent_buy_orders_users_kraken(){
        $exchange = 'kraken';
        $connetct = $this->mongo_db->customQuery();

        $user_ids = $this->get_daily_limit_exceeded_users([], $exchange);
        $this->restrict_parents_for_limit_exceeded_users($user_ids, $exchange);
        $this->unset_pick_parent_which_exceede_daily_limit($exchange);

        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $sold_collection = $exchange == "binance" ? "sold_buy_orders" : "sold_buy_orders_$exchange";

        $start_time = date('Y-m-d H:i:s', strtotime('-3 minutes'));
        $start_time = $this->mongo_db->converToMongodttime($start_time);

        $pipeline = [
            [
                '$match' => [
                    'application_mode' => 'live',
                    'status' => ['$in' => ['FILLED', 'LTH'] ],
                    'buy_date' => ['$exists' => true ],
                    'created_date' => ['$gte' => $start_time ],
                ],
            ],
            [
                '$group' => [
                    '_id' => null,
                    'user_ids' => ['$push' => '$admin_id'],
                ],
            ],
            [
                '$project' => [
                    'user_ids' => 1,
                    '_id' => 0,
                ],
            ],
        ];

        $buy_user_ids = $connetct->$buy_collection->aggregate($pipeline);
        $buy_user_ids = iterator_to_array($buy_user_ids);
        $buy_user_ids = !empty($buy_user_ids) ? (array) $buy_user_ids[0]['user_ids'] : [];

        $sold_user_ids = $connetct->$sold_collection->aggregate($pipeline);
        $sold_user_ids = iterator_to_array($sold_user_ids);
        $sold_user_ids = !empty($sold_user_ids) ? (array) $sold_user_ids[0]['user_ids'] : [];

        $user_ids = array_merge($buy_user_ids, $sold_user_ids);
        unset($buy_user_ids, $sold_user_ids);

        $user_ids = (array) $user_ids;
        if (!empty($user_ids)) {
            $user_ids = array_unique($user_ids);
            array_values($user_ids);

            $user_ids = $this->get_daily_limit_exceeded_users($user_ids, $exchange);
            $this->restrict_parents_for_limit_exceeded_users($user_ids, $exchange);
            unset($user_ids);
        }

        //Save last Cron Executioon
        $last_cron_exchange = "check_recent_buy_orders_users_$exchange";
        $this->last_cron_execution_time($last_cron_exchange, '3m', 'Cronjob to check user buy limit after 3 minutes kraken (*/3 * * * *)', 'dailyLimit');

        echo "<br> **************** cron end **************** <br>";
        return;
    }
    
    public function get_recent_buy_orders_users_bam(){
        $exchange = 'bam';
        $connetct = $this->mongo_db->customQuery();

        $user_ids = $this->get_daily_limit_exceeded_users([], $exchange);
        $this->restrict_parents_for_limit_exceeded_users($user_ids, $exchange);
        $this->unset_pick_parent_which_exceede_daily_limit($exchange);

        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $sold_collection = $exchange == "binance" ? "sold_buy_orders" : "sold_buy_orders_$exchange";

        $start_time = date('Y-m-d H:i:s', strtotime('-3 minutes'));
        $start_time = $this->mongo_db->converToMongodttime($start_time);

        $pipeline = [
            [
                '$match' => [
                    'application_mode' => 'live',
                    'status' => ['$in' => ['FILLED', 'LTH'] ],
                    'buy_date' => ['$exists' => true ],
                    'created_date' => ['$gte' => $start_time ],
                ],
            ],
            [
                '$group' => [
                    '_id' => null,
                    'user_ids' => ['$push' => '$admin_id'],
                ],
            ],
            [
                '$project' => [
                    'user_ids' => 1,
                    '_id' => 0,
                ],
            ],
        ];

        $buy_user_ids = $connetct->$buy_collection->aggregate($pipeline);
        $buy_user_ids = iterator_to_array($buy_user_ids);
        $buy_user_ids = !empty($buy_user_ids) ? (array) $buy_user_ids[0]['user_ids'] : [];

        $sold_user_ids = $connetct->$sold_collection->aggregate($pipeline);
        $sold_user_ids = iterator_to_array($sold_user_ids);
        $sold_user_ids = !empty($sold_user_ids) ? (array) $sold_user_ids[0]['user_ids'] : [];

        $user_ids = array_merge($buy_user_ids, $sold_user_ids);
        unset($buy_user_ids, $sold_user_ids);

        $user_ids = (array) $user_ids;
        if (!empty($user_ids)) {
            $user_ids = array_unique($user_ids);
            array_values($user_ids);

            $user_ids = $this->get_daily_limit_exceeded_users($user_ids, $exchange);
            $this->restrict_parents_for_limit_exceeded_users($user_ids, $exchange);
            unset($user_ids);
        }

        //Save last Cron Executioon
        $last_cron_exchange = "check_recent_buy_orders_users_$exchange";
        $this->last_cron_execution_time($last_cron_exchange, '3m', 'Cronjob to check user buy limit after 3 minutes bam (*/3 * * * *)', 'dailyLimit');

        echo "<br> **************** cron end **************** <br>";
        return;
    }

    public function check_users_daily_buy_limit($opportunityId, $exchange){

        // $exchange = 'binance';
        // $opportunityId = '5f51ae2a59874054ec1858db';

        /*
        $user_ids = $this->get_executed_user_ids_by_opportunity_id($opportunityId, $exchange);
        $user_ids = $this->get_daily_limit_exceeded_users($user_ids, $exchange);
        $this->restrict_parents_for_limit_exceeded_users($user_ids, $exchange);
        unset($user_ids, $opportunityId);
        */

        //Save last Cron Executioon
        $last_cron_exchange = "check_users_daily_buy_limit_$exchange";
        $this->last_cron_execution_time($last_cron_exchange, '3d', 'Cronjob to check user buy limit after opportunity hit (when ever hit comes)', 'dailyLimit');

    }

    public function get_executed_user_ids_by_opportunity_id($opportunityId, $exchange){
        
        $connetct = $this->mongo_db->customQuery();

        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $sold_collection = $exchange == "binance" ? "sold_buy_orders" : "sold_buy_orders_$exchange";

        $pipeline = [
            [
                '$match' => [
                    'application_mode' => 'live',
                    'opportunityId' => $opportunityId,
                ],
            ],
            [
                '$group' => [
                    '_id' => null,
                    'user_ids' => ['$push' => '$admin_id'],
                ],
            ],
            [
                '$project' => [
                    'user_ids' => 1,
                    '_id' => 0,
                ],
            ],
        ];

        $buy_user_ids = $connetct->$buy_collection->aggregate($pipeline);
        $buy_user_ids = iterator_to_array($buy_user_ids);
        $buy_user_ids = !empty($buy_user_ids) ? (array)$buy_user_ids[0]['user_ids'] : [];
        
        $sold_user_ids = $connetct->$sold_collection->aggregate($pipeline);
        $sold_user_ids = iterator_to_array($sold_user_ids);
        $sold_user_ids = !empty($sold_user_ids) ? (array)$sold_user_ids[0]['user_ids'] : [];
        
        $user_ids = array_merge($buy_user_ids, $sold_user_ids);
        unset($buy_user_ids, $sold_user_ids);

        $user_ids = (array)$user_ids;
        if(!empty($user_ids)){
            $user_ids = array_unique($user_ids);
            array_values($user_ids);
            return $user_ids;
        }
        return [];
    }

    public function get_daily_limit_exceeded_users($user_ids, $exchange){

        return [];

        $limit_collection = $exchange == "binance" ? "daily_trade_buy_limit" : "daily_trade_buy_limit_$exchange"; 
        $connetct = $this->mongo_db->customQuery();
        
        $pipeline = [
            [
                '$match' => [
                    // 'user_id'=> ['$in'=>$user_ids],
                    '$expr' => ['$gt' => ['$daily_buy_usd_worth', '$daily_buy_usd_limit']],
                ],
            ],
            [
                '$group' => [
                    '_id' => null,
                    'user_ids' => ['$push' => '$user_id'],
                ],
            ],
            [
                '$project' => [
                    'user_ids' => 1,
                    '_id' => 0,
                ],
            ],
        ];

        if (!empty($user_ids)) {
            $pipeline[0]['$match']['user_id']['$in'] = $user_ids;
        }

        $user_ids = $connetct->$limit_collection->aggregate($pipeline);
        $user_ids = iterator_to_array($user_ids);
        $user_ids = !empty($user_ids) ? (array) $user_ids[0]['user_ids'] : [];
        return $user_ids;
    }


    public function get_daily_limit_exceeded_users_new($user_ids, $exchange){

        return [];

        $limit_collection = $exchange == "binance" ? "daily_trade_buy_limit" : "daily_trade_buy_limit_$exchange"; 
        $connetct = $this->mongo_db->customQuery();
        
        $pipeline = [
            [
                '$match' => [
                    // 'user_id'=> ['$in'=>$user_ids],
                    '$expr' => ['$gt' => ['$daily_buy_usd_worth', '$daily_buy_usd_limit']],
                ],
            ],
            [
                '$group' => [
                    '_id' => null,
                    'user_ids' => ['$push' => '$user_id'],
                ],
            ],
            [
                '$project' => [
                    'user_ids' => 1,
                    '_id' => 0,
                ],
            ],
        ];

        if (!empty($user_ids)) {
            $pipeline[0]['$match']['user_id']['$in'] = $user_ids;
        }

        $user_ids = $connetct->$limit_collection->aggregate($pipeline);
        $user_ids = iterator_to_array($user_ids);
        $user_ids = !empty($user_ids) ? (array) $user_ids[0]['user_ids'] : [];
        return $user_ids;
    }

    public function restrict_parents_for_limit_exceeded_users($user_ids, $exchange){
        
        return true;

        $connetct = $this->mongo_db->customQuery();
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";

        $user_ids = array_unique($user_ids);
        $user_ids = array_values($user_ids);
        // Remove admin user_id from array
        $pos = array_search('5c0912b7fc9aadaac61dd072', $user_ids);
        if($pos !== false){
            unset($user_ids[$pos]);
            $user_ids = array_values($user_ids);
        }

        $where = [
            'admin_id' => ['$in'=>$user_ids],
            'application_mode' => 'live',
            'parent_status' => 'parent',
        ];
        $set = [
            '$set' => [
                'pick_parent' => 'no',
            ],
        ];
        $connetct->$buy_collection->updateMany($where, $set);
        return true;
    }

    public function unset_pick_parent_which_exceede_daily_limit($exchange){

        return false;

        // $exchange = 'binance';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $daily_limit_collection = $exchange == "binance" ? "daily_trade_buy_limit" : "daily_trade_buy_limit_$exchange";
        $pick_parent_temp_collection = $exchange == "binance" ? "pick_parent_temp" : "pick_parent_temp_$exchange";
        
        $pipeline = [
            [
                '$match' => [
                    'user_id' => ['$ne' =>'5c0912b7fc9aadaac61dd072'],
                ],
            ],
            [
                '$addFields' => [
                    'diff' => ['$subtract' => [
                        '$daily_buy_usd_limit',
                        '$daily_buy_usd_worth'
                    ]]
                ]
            ], 
            [
                '$lookup' => [
                    'from' => $buy_collection,
                    'let' => [
                        'diff' => '$diff',
                        'user_id' => '$user_id'
                    ],
                    'pipeline' => [
                        [
                            '$match' => [
                                'parent_status' => 'parent',
                                'application_mode' => 'live',
                                'pick_parent' => 'yes',
                                '$expr' => [
                                    '$eq' => ['$admin_id', '$$user_id'],
                                ]
                            ]
                        ],
                        [
                            '$match' => [
                                '$expr' => [
                                    '$gt' => ['$usd_worth', '$$diff']
                                ]
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'ids' => [
                                    '$push' => '$_id'
                                ]
                            ]
                        ],
                        [
                            '$addFields' =>[
                                'count' => [ '$size' => '$ids' ]
                            ]
                        ],
                        [
                            '$redact' => [
                                '$cond' => [ 'if' => [ '$gte' => ['$count', 1] ], 'then' => '$$KEEP', 'else' => '$$PRUNE' ]
                            ]
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'ids' => 1,
                            ]
                        ]
                    ],
                    'as' => 'recordArr'
                ]
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'recordArr' => 1,
                ]
            ],
            [
                '$out' => $pick_parent_temp_collection
            ]
        ];
        $db = $this->mongo_db->customQuery();
        $db->$daily_limit_collection->aggregate($pipeline);

        $db->$pick_parent_temp_collection->deleteMany(['recordArr' => ['$size' => 0 ]]);
        $result = $db->$pick_parent_temp_collection->find([], ['_id'=>0, 'recordArr'=>1]);
        $result = iterator_to_array($result);

        if(!empty($result)){
            $total = count($result);
            for($i=0; $i<$total; $i++){
                $result[$i]['recordArr'][0]['ids'];
                $db->$buy_collection->updateMany(['_id' => ['$in' => $result[$i]['recordArr'][0]['ids'] ]], ['$set'=>['pick_parent'=>'no','pick_parent_checking_limit_exceeded'=>1]]);
                $db->$pick_parent_temp_collection->deleteOne(['_id' => $result[$i]['_id'] ]);
                unset($result[$i]);
            }
        }
        unset($result);
        // echo "<br> ***************** SCRIPT END unset pick parent which might exceed limit ********************** <br>";
        return true;
    }

    public function unset_pick_parent_based_on_base_currency_low_balance(){

        $exchange = 'binance';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $user_wallet_collection = $exchange == "binance" ? "user_wallet" : "user_wallet_$exchange";
        $pick_parent_temp_collection = $exchange == "binance" ? "balance_pick_parent_temp" : "balance_pick_parent_temp_$exchange";

        $base_currency_arr = ['BTC', 'USDT'];
        $BTCUSDT_price = $this->get_market_price('BTCUSDT', $exchange);
        $BTCUSDT_price = (float) $BTCUSDT_price;

        $pipeline = [
            [
                '$match' => [
                    'user_id' => ['$nin' => ['5c0912b7fc9aadaac61dd072', '5c0915befc9aadaac61dd1b8']],
                    'coin_symbol' => ['$in' => $base_currency_arr],
                ],
            ],
            [
                '$sort' => [
                    'coin_symbol' => 1,
                ],
            ],
            [
                '$group' => [
                    '_id' => '$user_id',
                    'BTC' => [
                        '$first' => [
                            '$cond' => [
                                'if' => ['$eq' => ['$coin_symbol', 'BTC']],
                                'then' => ['$toDouble' => '$coin_balance'],
                                'else' => '$$REMOVE',
                            ],
                        ],
                    ],
                    'USDT' => [
                        '$last' => [
                            '$cond' => [
                                'if' => ['$eq' => ['$coin_symbol', 'USDT']],
                                'then' => ['$toDouble' => '$coin_balance'],
                                'else' => '$$REMOVE',
                            ],
                        ],
                    ],
                ],
            ],
            // [
            //     '$limit'=> 500,
            // ],
            [
                '$project' => [
                    'user_id' => '$_id',
                    'BTC' => [
                        '$cond' => [
                            'if' => ['$gt' => ['$BTC', 0]],
                            'then' => '$BTC',
                            'else' => 0,
                        ],
                    ],
                    'btc_usd_worth' => [
                        '$cond' => [
                            'if' => ['$gt' => ['$BTC', 0]],
                            'then' => ['$multiply' => ['$BTC', $BTCUSDT_price]],
                            'else' => 0,
                        ],
                    ],
                    'usdt_usd_worth' => [
                        '$cond' => [
                            'if' => ['$gt' => ['$USDT', 0]],
                            'then' => '$USDT',
                            'else' => 0,
                        ],
                    ],
                    '_id' => 0,
                ],
            ],
            [
                '$lookup' => [
                    'from' => $buy_collection,
                    'let' => [
                        'user_id' => '$user_id',
                        'btc_usd_worth' => '$btc_usd_worth',
                        'usdt_usd_worth' => '$usdt_usd_worth',
                    ],
                    'pipeline' => [
                        [
                            '$match' => [
                                'parent_status' => 'parent',
                                'application_mode' => 'live',
                                'pick_parent' => 'yes',
                                '$expr' => [
                                    '$eq' => ['$admin_id', '$$user_id'],
                                ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'splitArr' => ['$split' => ['$symbol', 'USDT']],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'remainingBalance' => [
                                    '$cond' => ['if' => ['$eq' => [['$arrayElemAt' => ['$splitArr', 1]], '']], 'then' => '$$usdt_usd_worth', 'else' => '$$btc_usd_worth'],
                                ],
                            ],
                        ],
                        [
                            '$match' => [
                                '$expr' => [
                                    '$gt' => ['$usd_worth', '$remainingBalance'],
                                ],
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'ids' => [
                                    '$push' => '$_id',
                                ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'count' => ['$size' => '$ids'],
                            ],
                        ],
                        [
                            '$redact' => [
                                '$cond' => ['if' => ['$gte' => ['$count', 1]], 'then' => '$$KEEP', 'else' => '$$PRUNE'],
                            ],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'ids' => 1,
                            ],
                        ],
                    ],
                    'as' => 'recordArr',
                ],
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'recordArr' => 1,
                ],
            ],
            [
                '$out' => $pick_parent_temp_collection,
            ],
        ];

        $db = $this->mongo_db->customQuery();
        $db->$user_wallet_collection->aggregate($pipeline);

        $db->$pick_parent_temp_collection->deleteMany(['recordArr' => ['$size' => 0]]);

        $result = $db->$pick_parent_temp_collection->find([], ['_id' => 0, 'recordArr' => 1]);
        $result = iterator_to_array($result);

        if (!empty($result)) {
            $total = count($result);
            for ($i = 0; $i < $total; $i++) {
                $result[$i]['recordArr'][0]['ids'];
                $db->$buy_collection->updateMany(['_id' => ['$in' => $result[$i]['recordArr'][0]['ids']]], ['$set' => ['pick_parent' => 'no','pick_parent_checking'=>1]]);
                $db->$pick_parent_temp_collection->deleteOne(['_id' => $result[$i]['_id']]);
                unset($result[$i]);
            }
        }
        unset($result);

        //Save last Cron Executioon
        $last_cron_exchange = "unset_pick_parent_based_on_base_currency_low_balance_$exchange";
        $this->last_cron_execution_time($last_cron_exchange, "5m", "Cronjob to check user buy limit after 5 minutes $exchange (*/5 * * * *)", 'pickParent');

        echo "<br> **************** cron end **************** <br>";

    }

    public function unset_pick_parent_based_on_base_currency_low_balance_bam(){

        $exchange = 'bam';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $user_wallet_collection = $exchange == "binance" ? "user_wallet" : "user_wallet_$exchange";
        $pick_parent_temp_collection = $exchange == "binance" ? "balance_pick_parent_temp" : "balance_pick_parent_temp_$exchange";

        $base_currency_arr = ['BTC', 'USDT'];
        $BTCUSDT_price = $this->get_market_price('BTCUSDT', $exchange);
        $BTCUSDT_price = (float) $BTCUSDT_price;

        $pipeline = [
            [
                '$match' => [
                    'user_id' => ['$nin' => ['5c0912b7fc9aadaac61dd072', '5c0915befc9aadaac61dd1b8']],
                    'coin_symbol' => ['$in' => $base_currency_arr],
                ],
            ],
            [
                '$sort' => [
                    'coin_symbol' => 1,
                ],
            ],
            [
                '$group' => [
                    '_id' => '$user_id',
                    'BTC' => [
                        '$first' => [
                            '$cond' => [
                                'if' => ['$eq' => ['$coin_symbol', 'BTC']],
                                'then' => ['$toDouble' => '$coin_balance'],
                                'else' => '$$REMOVE',
                            ],
                        ],
                    ],
                    'USDT' => [
                        '$last' => [
                            '$cond' => [
                                'if' => ['$eq' => ['$coin_symbol', 'USDT']],
                                'then' => ['$toDouble' => '$coin_balance'],
                                'else' => '$$REMOVE',
                            ],
                        ],
                    ],
                ],
            ],
            // [
            //     '$limit'=> 500,
            // ],
            [
                '$project' => [
                    'user_id' => '$_id',
                    'BTC' => [
                        '$cond' => [
                            'if' => ['$gt' => ['$BTC', 0]],
                            'then' => '$BTC',
                            'else' => 0,
                        ],
                    ],
                    'btc_usd_worth' => [
                        '$cond' => [
                            'if' => ['$gt' => ['$BTC', 0]],
                            'then' => ['$multiply' => ['$BTC', $BTCUSDT_price]],
                            'else' => 0,
                        ],
                    ],
                    'usdt_usd_worth' => [
                        '$cond' => [
                            'if' => ['$gt' => ['$USDT', 0]],
                            'then' => '$USDT',
                            'else' => 0,
                        ],
                    ],
                    '_id' => 0,
                ],
            ],
            [
                '$lookup' => [
                    'from' => $buy_collection,
                    'let' => [
                        'user_id' => '$user_id',
                        'btc_usd_worth' => '$btc_usd_worth',
                        'usdt_usd_worth' => '$usdt_usd_worth',
                    ],
                    'pipeline' => [
                        [
                            '$match' => [
                                'parent_status' => 'parent',
                                'application_mode' => 'live',
                                'pick_parent' => 'yes',
                                '$expr' => [
                                    '$eq' => ['$admin_id', '$$user_id'],
                                ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'splitArr' => ['$split' => ['$symbol', 'USDT']],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'remainingBalance' => [
                                    '$cond' => ['if' => ['$eq' => [['$arrayElemAt' => ['$splitArr', 1]], '']], 'then' => '$$usdt_usd_worth', 'else' => '$$btc_usd_worth'],
                                ],
                            ],
                        ],
                        [
                            '$match' => [
                                '$expr' => [
                                    '$gt' => ['$usd_worth', '$remainingBalance'],
                                ],
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'ids' => [
                                    '$push' => '$_id',
                                ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'count' => ['$size' => '$ids'],
                            ],
                        ],
                        [
                            '$redact' => [
                                '$cond' => ['if' => ['$gte' => ['$count', 1]], 'then' => '$$KEEP', 'else' => '$$PRUNE'],
                            ],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'ids' => 1,
                            ],
                        ],
                    ],
                    'as' => 'recordArr',
                ],
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'recordArr' => 1,
                ],
            ],
            [
                '$out' => $pick_parent_temp_collection,
            ],
        ];

        $db = $this->mongo_db->customQuery();
        $db->$user_wallet_collection->aggregate($pipeline);

        $db->$pick_parent_temp_collection->deleteMany(['recordArr' => ['$size' => 0]]);

        $result = $db->$pick_parent_temp_collection->find([], ['_id' => 0, 'recordArr' => 1]);
        $result = iterator_to_array($result);

        if (!empty($result)) {
            $total = count($result);
            for ($i = 0; $i < $total; $i++) {
                $result[$i]['recordArr'][0]['ids'];
                $db->$buy_collection->updateMany(['_id' => ['$in' => $result[$i]['recordArr'][0]['ids']]], ['$set' => ['pick_parent' => 'no']]);
                $db->$pick_parent_temp_collection->deleteOne(['_id' => $result[$i]['_id']]);
                unset($result[$i]);
            }
        }
        unset($result);

        //Save last Cron Executioon
        $last_cron_exchange = "unset_pick_parent_based_on_base_currency_low_balance_$exchange";
        $this->last_cron_execution_time($last_cron_exchange, "5m", "Cronjob to check user buy limit after 5 minutes $exchange (*/5 * * * *)", 'pickParent');

        echo "<br> **************** cron end **************** <br>";

    }

    public function unset_pick_parent_based_on_base_currency_low_balance_kraken(){

        $exchange = 'kraken';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $user_wallet_collection = $exchange == "binance" ? "user_wallet" : "user_wallet_$exchange";
        $pick_parent_temp_collection = $exchange == "binance" ? "balance_pick_parent_temp" : "balance_pick_parent_temp_$exchange";

        $base_currency_arr = ['BTC', 'USDT'];
        $BTCUSDT_price = $this->get_market_price('BTCUSDT', $exchange);
        $BTCUSDT_price = (float) $BTCUSDT_price;

        $pipeline = [
            [
                '$match' => [
                    'user_id' => ['$nin' => ['5c0912b7fc9aadaac61dd072', '5c0915befc9aadaac61dd1b8']],
                    'coin_symbol' => ['$in' => $base_currency_arr],
                ],
            ],
            [
                '$sort' => [
                    'coin_symbol' => 1,
                ],
            ],
            [
                '$group' => [
                    '_id' => '$user_id',
                    'BTC' => [
                        '$first' => [
                            '$cond' => [
                                'if' => ['$eq' => ['$coin_symbol', 'BTC']],
                                'then' => ['$toDouble' => '$coin_balance'],
                                'else' => '$$REMOVE',
                            ],
                        ],
                    ],
                    'USDT' => [
                        '$last' => [
                            '$cond' => [
                                'if' => ['$eq' => ['$coin_symbol', 'USDT']],
                                'then' => ['$toDouble' => '$coin_balance'],
                                'else' => '$$REMOVE',
                            ],
                        ],
                    ],
                ],
            ],
            // [
            //     '$limit'=> 500,
            // ],
            [
                '$project' => [
                    'user_id' => '$_id',
                    'BTC' => [
                        '$cond' => [
                            'if' => ['$gt' => ['$BTC', 0]],
                            'then' => '$BTC',
                            'else' => 0,
                        ],
                    ],
                    'btc_usd_worth' => [
                        '$cond' => [
                            'if' => ['$gt' => ['$BTC', 0]],
                            'then' => ['$multiply' => ['$BTC', $BTCUSDT_price]],
                            'else' => 0,
                        ],
                    ],
                    'usdt_usd_worth' => [
                        '$cond' => [
                            'if' => ['$gt' => ['$USDT', 0]],
                            'then' => '$USDT',
                            'else' => 0,
                        ],
                    ],
                    '_id' => 0,
                ],
            ],
            [
                '$lookup' => [
                    'from' => $buy_collection,
                    'let' => [
                        'user_id' => '$user_id',
                        'btc_usd_worth' => '$btc_usd_worth',
                        'usdt_usd_worth' => '$usdt_usd_worth',
                    ],
                    'pipeline' => [
                        [
                            '$match' => [
                                'parent_status' => 'parent',
                                'application_mode' => 'live',
                                'pick_parent' => 'yes',
                                '$expr' => [
                                    '$eq' => ['$admin_id', '$$user_id'],
                                ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'splitArr' => ['$split' => ['$symbol', 'USDT']],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'remainingBalance' => [
                                    '$cond' => ['if' => ['$eq' => [['$arrayElemAt' => ['$splitArr', 1]], '']], 'then' => '$$usdt_usd_worth', 'else' => '$$btc_usd_worth'],
                                ],
                            ],
                        ],
                        [
                            '$match' => [
                                '$expr' => [
                                    '$gt' => ['$usd_worth', '$remainingBalance'],
                                ],
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'ids' => [
                                    '$push' => '$_id',
                                ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'count' => ['$size' => '$ids'],
                            ],
                        ],
                        [
                            '$redact' => [
                                '$cond' => ['if' => ['$gte' => ['$count', 1]], 'then' => '$$KEEP', 'else' => '$$PRUNE'],
                            ],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'ids' => 1,
                            ],
                        ],
                    ],
                    'as' => 'recordArr',
                ],
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'recordArr' => 1,
                ],
            ],
            [
                '$out' => $pick_parent_temp_collection,
            ],
        ];

        $db = $this->mongo_db->customQuery();
        $db->$user_wallet_collection->aggregate($pipeline);

        $db->$pick_parent_temp_collection->deleteMany(['recordArr' => ['$size' => 0]]);

        $result = $db->$pick_parent_temp_collection->find([], ['_id' => 0, 'recordArr' => 1]);
        $result = iterator_to_array($result);

        if (!empty($result)) {
            $total = count($result);
            for ($i = 0; $i < $total; $i++) {
                $result[$i]['recordArr'][0]['ids'];
                $db->$buy_collection->updateMany(['_id' => ['$in' => $result[$i]['recordArr'][0]['ids']]], ['$set' => ['pick_parent' => 'no','low_base_curr'=>1]]);
                $db->$pick_parent_temp_collection->deleteOne(['_id' => $result[$i]['_id']]);
                unset($result[$i]);
            }
        }
        unset($result);

        //Save last Cron Executioon
        $last_cron_exchange = "unset_pick_parent_based_on_base_currency_low_balance_$exchange";
        $this->last_cron_execution_time($last_cron_exchange, "5m", "Cronjob to check user buy limit after 5 minutes $exchange (*/5 * * * *)", 'pickParent');

        echo "<br> **************** cron end **************** <br>";

    }


    /* ****************  Base Currency limit seprate cronjobs  ************* */
    public function unset_pick_parent_based_on_base_currency_daily_limit(){

        $exchange = 'binance';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $daily_limit_collection = $exchange == "binance" ? "daily_trade_buy_limit" : "daily_trade_buy_limit_$exchange";
        $pick_parent_temp_collection = $exchange == "binance" ? "base_currency_daily_limit_pick_parent_temp" : "base_currency_daily_limit_pick_parent_temp_$exchange";

        $pipeline = [
            [
                '$match' => [
                    'user_id' => ['$nin' => ['5c0912b7fc9aadaac61dd072', '5c0915befc9aadaac61dd1b8']],
                ],
            ],
            [
                '$project' => [
                    'user_id' => '$user_id',
                    'btc_usd_worth' => ['$subtract' => [ '$dailyTradeableBTC_usd_worth', '$daily_bought_btc_usd_worth' ] ],
                    'usdt_usd_worth' => ['$subtract' => [ '$dailyTradeableUSDT_usd_worth', '$daily_bought_usdt_usd_worth' ] ],
                    '_id' => 0,
                ],
            ],
            [
                '$lookup' => [
                    'from' => $buy_collection,
                    'let' => [
                        'user_id' => '$user_id',
                        'btc_usd_worth' => '$btc_usd_worth',
                        'usdt_usd_worth' => '$usdt_usd_worth',
                    ],
                    'pipeline' => [
                        [
                            '$match' => [
                                'parent_status' => 'parent',
                                'application_mode' => 'live',
                                'pick_parent' => 'yes',
                                '$expr' => [
                                    '$eq' => ['$admin_id', '$$user_id'],
                                ],
                            ],
                        ],
                        // [
                        //     '$addFields' => [
                        //         'splitArr' => ['$split' => ['$symbol', 'USDT']],
                        //     ],
                        // ],
                        // [
                        //     '$addFields' => [
                        //         'remainingBalance' => [
                        //             '$cond' => ['if' => ['$eq' => [['$arrayElemAt' => ['$splitArr', 1]], '']], 'then' => '$$usdt_usd_worth', 'else' => '$$btc_usd_worth']
                        //         ],
                        //     ],
                        // ],
                        [
                            '$addFields' => [
                                'splitArr' => ['$split' => ['$symbol', 'USDT']],
                                
                                'btc_ten_dollar_more' => [ '$add' => [ '$$btc_usd_worth', 10 ] ],
                                // 'btc_ten_percent_more' => [ '$add' => [ '$$btc_usd_worth', [ '$divide' => [ [ '$multiply' => [ 10, '$$btc_usd_worth' ] ], 100 ] ] ] ],
                                
                                'usdt_ten_dollar_more' => [ '$add' => [ '$$usdt_usd_worth', 10 ] ],
                                // 'usdt_ten_percent_more' => [ '$add' => [ '$$usdt_usd_worth', [ '$divide' => [ [ '$multiply' => [ 10, '$$usdt_usd_worth' ] ], 100 ] ] ] ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'use_btc_val' => '$btc_ten_dollar_more',
                                'use_usdt_val' => '$usdt_ten_dollar_more',
                                
                                // 'use_btc_val' => [ '$cond' => [ 'if' => [ '$gte' => [ '$btc_ten_dollar_more', '$btc_ten_percent_more' ] ], 'then' => '$btc_ten_dollar_more', 'else' => '$btc_ten_percent_more' ] ],
                                // 'use_usdt_val' => [ '$cond' => [ 'if' => [ '$gte' => [ '$usdt_ten_dollar_more', '$usdt_ten_percent_more' ] ], 'then' => '$usdt_ten_dollar_more', 'else' => '$usdt_ten_percent_more' ] ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'remainingBalance' => [
                                    '$cond' => ['if' => ['$eq' => [['$arrayElemAt' => ['$splitArr', 1]], '']], 'then' => '$use_usdt_val', 'else' => '$use_btc_val']
                                ],
                            ],
                        ],
                        [ 
                            '$match' => [
                                '$expr' => [
                                    '$gt' => ['$usd_worth', '$remainingBalance'],
                                ],
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'ids' => [
                                    '$push' => '$_id',
                                ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'count' => ['$size' => '$ids'],
                            ],
                        ],
                        [
                            '$redact' => [
                                '$cond' => ['if' => ['$gte' => ['$count', 1]], 'then' => '$$KEEP', 'else' => '$$PRUNE'],
                            ],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'ids' => 1,
                            ],
                        ],
                    ],
                    'as' => 'recordArr',
                ],
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'recordArr' => 1,
                ],
            ],
            [
                '$out' => $pick_parent_temp_collection,
            ],
        ];

        $db = $this->mongo_db->customQuery();
        $db->$daily_limit_collection->aggregate($pipeline);

        $db->$pick_parent_temp_collection->deleteMany(['recordArr' => ['$size' => 0]]);

        $result = $db->$pick_parent_temp_collection->find([], ['_id' => 0, 'recordArr' => 1]);
        $result = iterator_to_array($result);

        if (!empty($result)) {
            $total = count($result);
            for ($i = 0; $i < $total; $i++) {
                $result[$i]['recordArr'][0]['ids'];
                $db->$buy_collection->updateMany(['_id' => ['$in' => $result[$i]['recordArr'][0]['ids']]], ['$set' => ['pick_parent' => 'no']]);
                $db->$pick_parent_temp_collection->deleteOne(['_id' => $result[$i]['_id']]);
                unset($result[$i]);
            }
        }
        unset($result);

        //Save last Cron Executioon
        $last_cron_exchange = "unset_pick_parent_based_on_base_currency_daily_limit_$exchange";
        $this->last_cron_execution_time($last_cron_exchange, "2m", "Cronjob to check user buy limit after 2 minutes $exchange (*/2 * * * *)", 'pickParent');

        echo "<br> **************** cron end **************** <br>";

    }

    public function unset_pick_parent_based_on_base_currency_daily_limit_bam(){

        $exchange = 'bam';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $daily_limit_collection = $exchange == "binance" ? "daily_trade_buy_limit" : "daily_trade_buy_limit_$exchange";
        $pick_parent_temp_collection = $exchange == "binance" ? "base_currency_daily_limit_pick_parent_temp" : "base_currency_daily_limit_pick_parent_temp_$exchange";

        $pipeline = [
            [
                '$match' => [
                    'user_id' => ['$nin' => ['5c0912b7fc9aadaac61dd072', '5c0915befc9aadaac61dd1b8']],
                ],
            ],
            [
                '$project' => [
                    'user_id' => '$user_id',
                    'btc_usd_worth' => ['$subtract' => [ '$dailyTradeableBTC_usd_worth', '$daily_bought_btc_usd_worth' ] ],
                    'usdt_usd_worth' => ['$subtract' => [ '$dailyTradeableUSDT_usd_worth', '$daily_bought_usdt_usd_worth' ] ],
                    '_id' => 0,
                ],
            ],
            [
                '$lookup' => [
                    'from' => $buy_collection,
                    'let' => [
                        'user_id' => '$user_id',
                        'btc_usd_worth' => '$btc_usd_worth',
                        'usdt_usd_worth' => '$usdt_usd_worth',
                    ],
                    'pipeline' => [
                        [
                            '$match' => [
                                'parent_status' => 'parent',
                                'application_mode' => 'live',
                                'pick_parent' => 'yes',
                                '$expr' => [
                                    '$eq' => ['$admin_id', '$$user_id'],
                                ],
                            ],
                        ],
                        // [
                        //     '$addFields' => [
                        //         'splitArr' => ['$split' => ['$symbol', 'USDT']],
                        //     ],
                        // ],
                        // [
                        //     '$addFields' => [
                        //         'remainingBalance' => [
                        //             '$cond' => ['if' => ['$eq' => [['$arrayElemAt' => ['$splitArr', 1]], '']], 'then' => '$$usdt_usd_worth', 'else' => '$$btc_usd_worth']
                        //         ],
                        //     ],
                        // ],
                        [
                            '$addFields' => [
                                'splitArr' => ['$split' => ['$symbol', 'USDT']],
                                
                                'btc_ten_dollar_more' => [ '$add' => [ '$$btc_usd_worth', 10 ] ],
                                // 'btc_ten_percent_more' => [ '$add' => [ '$$btc_usd_worth', [ '$divide' => [ [ '$multiply' => [ 10, '$$btc_usd_worth' ] ], 100 ] ] ] ],
                                
                                'usdt_ten_dollar_more' => [ '$add' => [ '$$usdt_usd_worth', 10 ] ],
                                // 'usdt_ten_percent_more' => [ '$add' => [ '$$usdt_usd_worth', [ '$divide' => [ [ '$multiply' => [ 10, '$$usdt_usd_worth' ] ], 100 ] ] ] ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'use_btc_val' => '$btc_ten_dollar_more',
                                'use_usdt_val' => '$usdt_ten_dollar_more',
                                
                                // 'use_btc_val' => [ '$cond' => [ 'if' => [ '$gte' => [ '$btc_ten_dollar_more', '$btc_ten_percent_more' ] ], 'then' => '$btc_ten_dollar_more', 'else' => '$btc_ten_percent_more' ] ],
                                // 'use_usdt_val' => [ '$cond' => [ 'if' => [ '$gte' => [ '$usdt_ten_dollar_more', '$usdt_ten_percent_more' ] ], 'then' => '$usdt_ten_dollar_more', 'else' => '$usdt_ten_percent_more' ] ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'remainingBalance' => [
                                    '$cond' => ['if' => ['$eq' => [['$arrayElemAt' => ['$splitArr', 1]], '']], 'then' => '$use_usdt_val', 'else' => '$use_btc_val']
                                ],
                            ],
                        ],
                        [
                            '$match' => [
                                '$expr' => [
                                    '$gt' => ['$usd_worth', '$remainingBalance'],
                                ],
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'ids' => [
                                    '$push' => '$_id',
                                ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'count' => ['$size' => '$ids'],
                            ],
                        ],
                        [
                            '$redact' => [
                                '$cond' => ['if' => ['$gte' => ['$count', 1]], 'then' => '$$KEEP', 'else' => '$$PRUNE'],
                            ],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'ids' => 1,
                            ],
                        ],
                    ],
                    'as' => 'recordArr',
                ],
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'recordArr' => 1,
                ],
            ],
            [
                '$out' => $pick_parent_temp_collection,
            ],
        ];

        $db = $this->mongo_db->customQuery();
        $db->$daily_limit_collection->aggregate($pipeline);

        $db->$pick_parent_temp_collection->deleteMany(['recordArr' => ['$size' => 0]]);

        $result = $db->$pick_parent_temp_collection->find([], ['_id' => 0, 'recordArr' => 1]);
        $result = iterator_to_array($result);

        if (!empty($result)) {
            $total = count($result);
            for ($i = 0; $i < $total; $i++) {
                $result[$i]['recordArr'][0]['ids'];
                $db->$buy_collection->updateMany(['_id' => ['$in' => $result[$i]['recordArr'][0]['ids']]], ['$set' => ['pick_parent' => 'no']]);
                $db->$pick_parent_temp_collection->deleteOne(['_id' => $result[$i]['_id']]);
                unset($result[$i]);
            }
        }
        unset($result);

        //Save last Cron Executioon
        $last_cron_exchange = "unset_pick_parent_based_on_base_currency_daily_limit_$exchange";
        $this->last_cron_execution_time($last_cron_exchange, "2m", "Cronjob to check user buy limit after 2 minutes $exchange (*/2 * * * *)", 'pickParent');

        echo "<br> **************** cron end **************** <br>";
    }

    public function unset_pick_parent_based_on_base_currency_daily_limit_kraken(){

        $exchange = 'kraken';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $daily_limit_collection = $exchange == "binance" ? "daily_trade_buy_limit" : "daily_trade_buy_limit_$exchange";
        $pick_parent_temp_collection = $exchange == "binance" ? "base_currency_daily_limit_pick_parent_temp" : "base_currency_daily_limit_pick_parent_temp_$exchange";

        $pipeline = [
            [
                '$match' => [
                    'user_id' => ['$nin' => ['5c0912b7fc9aadaac61dd072', '5c0915befc9aadaac61dd1b8']],
                ],
            ],
            [
                '$project' => [
                    'user_id' => '$user_id',
                    'btc_usd_worth' => ['$subtract' => [ '$dailyTradeableBTC_usd_worth', '$daily_bought_btc_usd_worth' ] ],
                    'usdt_usd_worth' => ['$subtract' => [ '$dailyTradeableUSDT_usd_worth', '$daily_bought_usdt_usd_worth' ] ],
                    '_id' => 0,
                ],
            ],
            [
                '$lookup' => [
                    'from' => $buy_collection,
                    'let' => [
                        'user_id' => '$user_id',
                        'btc_usd_worth' => '$btc_usd_worth',
                        'usdt_usd_worth' => '$usdt_usd_worth',
                    ],
                    'pipeline' => [
                        [
                            '$match' => [
                                'parent_status' => 'parent',
                                'application_mode' => 'live',
                                'pick_parent' => 'yes',
                                '$expr' => [
                                    '$eq' => ['$admin_id', '$$user_id'],
                                ],
                            ],
                        ],
                        // [
                        //     '$addFields' => [
                        //         'splitArr' => ['$split' => ['$symbol', 'USDT']],
                        //     ],
                        // ],
                        // [
                        //     '$addFields' => [
                        //         'remainingBalance' => [
                        //             '$cond' => ['if' => ['$eq' => [['$arrayElemAt' => ['$splitArr', 1]], '']], 'then' => '$$usdt_usd_worth', 'else' => '$$btc_usd_worth']
                        //         ],
                        //     ],
                        // ],
                        [
                            '$addFields' => [
                                'splitArr' => ['$split' => ['$symbol', 'USDT']],
                                
                                'btc_ten_dollar_more' => [ '$add' => [ '$$btc_usd_worth', 10 ] ],
                                // 'btc_ten_percent_more' => [ '$add' => [ '$$btc_usd_worth', [ '$divide' => [ [ '$multiply' => [ 10, '$$btc_usd_worth' ] ], 100 ] ] ] ],
                                
                                'usdt_ten_dollar_more' => [ '$add' => [ '$$usdt_usd_worth', 10 ] ],
                                // 'usdt_ten_percent_more' => [ '$add' => [ '$$usdt_usd_worth', [ '$divide' => [ [ '$multiply' => [ 10, '$$usdt_usd_worth' ] ], 100 ] ] ] ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'use_btc_val' => '$btc_ten_dollar_more',
                                'use_usdt_val' => '$usdt_ten_dollar_more',
                                
                                // 'use_btc_val' => [ '$cond' => [ 'if' => [ '$gte' => [ '$btc_ten_dollar_more', '$btc_ten_percent_more' ] ], 'then' => '$btc_ten_dollar_more', 'else' => '$btc_ten_percent_more' ] ],
                                // 'use_usdt_val' => [ '$cond' => [ 'if' => [ '$gte' => [ '$usdt_ten_dollar_more', '$usdt_ten_percent_more' ] ], 'then' => '$usdt_ten_dollar_more', 'else' => '$usdt_ten_percent_more' ] ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'remainingBalance' => [
                                    '$cond' => ['if' => ['$eq' => [['$arrayElemAt' => ['$splitArr', 1]], '']], 'then' => '$use_usdt_val', 'else' => '$use_btc_val']
                                ],
                            ],
                        ],
                        [
                            '$match' => [
                                '$expr' => [
                                    '$gt' => ['$usd_worth', '$remainingBalance'],
                                ],
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'ids' => [
                                    '$push' => '$_id',
                                ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'count' => ['$size' => '$ids'],
                            ],
                        ],
                        [
                            '$redact' => [
                                '$cond' => ['if' => ['$gte' => ['$count', 1]], 'then' => '$$KEEP', 'else' => '$$PRUNE'],
                            ],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'ids' => 1,
                            ],
                        ],
                    ],
                    'as' => 'recordArr',
                ],
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'recordArr' => 1,
                ],
            ],
            [
                '$out' => $pick_parent_temp_collection,
            ],
        ];

        $db = $this->mongo_db->customQuery();
        $db->$daily_limit_collection->aggregate($pipeline);

        $db->$pick_parent_temp_collection->deleteMany(['recordArr' => ['$size' => 0]]);

        $result = $db->$pick_parent_temp_collection->find([], ['_id' => 0, 'recordArr' => 1]);
        $result = iterator_to_array($result);

        if (!empty($result)) {
            $total = count($result);
            for ($i = 0; $i < $total; $i++) {
                $result[$i]['recordArr'][0]['ids'];
                $db->$buy_collection->updateMany(['_id' => ['$in' => $result[$i]['recordArr'][0]['ids']]], ['$set' => ['pick_parent' => 'no']]);
                $db->$pick_parent_temp_collection->deleteOne(['_id' => $result[$i]['_id']]);
                unset($result[$i]);
            }
        }
        unset($result);

        //Save last Cron Executioon
        $last_cron_exchange = "unset_pick_parent_based_on_base_currency_daily_limit_$exchange";
        $this->last_cron_execution_time($last_cron_exchange, "2m", "Cronjob to check user buy limit after 2 minutes $exchange (*/2 * * * *)", 'pickParent');

        echo "<br> **************** cron end **************** <br>";
    }
    /* ****************  End Base Currency limit seprate cronjobs  ************* */

    /* *****************  End check users daily buy limit  ******************* */


    public function update_stop_loss_of_orders_with_pl_negative($exchange=''){

        $exchange = 'binance';
        if($exchange !=''){

            $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
            
            $pl_value = -2.5;
            $stop_loss = 2.5;
            $modified_date = date('Y-m-d H:i:s', strtotime('-2 days'));
            $modified_date = $this->mongo_db->converToMongodttime($modified_date);

            $pricesObjArr = get_current_market_prices($exchange);
            $pricesJsonArr = [];
            // echo "<pre>";

            foreach($pricesObjArr as $key=>$val){
                $pricesJsonArr[] = [
                    'symbol' => $key,
                    'price' => $val,
                ];
            }
            unset($pricesObjArr);
            
            // print_r($pricesJsonArr);
            // $pricesJsonArr = Json_encode($pricesArr);
            // echo "$pricesJsonArr <br> <br>";

            $pipeline = [
                [
                    '$match' => [
                        'application_mode' => 'live',
                        'buy_parent_id' => ['$exists' => true],
                        'stop_loss_type' => 'positive',
                        '$or' => [
                            [   
                                'status' => [ '$in' => ['FILLED', 'FILLED_ERROR', 'SELL_ID_ERROR'] ], 
                                'is_sell_order' => 'yes', 
                                'is_lth_order' => [ '$ne' => 'yes' ], 
                                'cost_avg' => 'yes', 
                                'cavg_parent' => 'yes', 
                                'show_order' => 'yes', 
                                'avg_orders_ids.0' => [ '$exists' => false ], 
                                'move_to_cost_avg' => [ '$ne' => 'yes' ] 
                            ],
                            [ 
                                'status' => [ '$in' => ['FILLED', 'FILLED_ERROR', 'SELL_ID_ERROR'] ], 
                                'is_sell_order' => 'yes', 
                                'is_lth_order' => [ '$ne' => 'yes' ], 
                                'cost_avg' => [ '$nin' => ['yes', 'taking_child', 'completed'] ] 
                            ]  
                        ],
                        "modified_date" => [ '$lte' => $modified_date]
                    ]
                ],
                [ 
                    '$addFields' => [ 
                        'currPrice' => [
                            '$filter' => [
                                'input' => $pricesJsonArr,
                                'as' => 'item',
                                'cond' => [ '$eq' => [ '$$item.symbol', '$symbol' ] ]
                            ]
                        ]
                    ] 
                ],
                [ 
                    '$addFields' => [ 
                        'purchased_price' => [ '$toDouble' => '$purchased_price' ],
                        'currPrice' =>[ '$arrayElemAt' => [ '$currPrice', 0 ] ],
                    ] 
                ],
                [ 
                    '$addFields' => [ 
                        'currPrice' => [ '$toDouble' => '$currPrice.price' ]
                    ] 
                ],
                [ 
                    '$addFields' => [ 
                        'pl' => [ '$divide' => [ [ '$multiply' => [ [ '$subtract' => [ '$currPrice', '$purchased_price' ] ], 100 ] ], '$purchased_price' ] ]
                    ]
                ],
                [
                    '$match' => [
                        '$expr' => [ '$lte' => ['$pl', $pl_value] ]
                    ]
                ],
                [ 
                    '$addFields' => [ 
                        'custom_stop_loss_percentage' => $stop_loss,
                        'iniatial_trail_stop' => [ '$subtract' => [ '$purchased_price', [ '$divide' => [ [ '$multiply' => [ $stop_loss, '$purchased_price' ] ], 100 ] ] ] ],
                    ]
                ],
                [
                    '$project' => [
                        '_id' => 1,
                        // 'symbol' =>1,
                        // 'quantity' =>1,
                        // 'purchased_price' =>1,
                        // 'currPrice1' => 1,
                        // 'pl' =>1,
                        'custom_stop_loss_percentage' => 1,
                        'iniatial_trail_stop' => 1,
                        // 'modified_date' =>1
                    ]
                ],
            ];
    
            $db = $this->mongo_db->customQuery();
            $result = $db->$buy_collection->aggregate($pipeline);

            $result = iterator_to_array($result);
            // print_r($result);
            // var_dump($result);
    
            if (!empty($result)) {

                $total = count($result);
                for($i=0; $i<$total; $i++){

                    // print_r($result[$i]);
                    // echo "<br> processed ID: ".$result[$i]['_id'];
                    
                    // $modified_date = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));
                
                    $where = [
                        '_id' => $result[$i]['_id']
                    ];
                    $set = [
                        '$set' => [
                            'custom_stop_loss_percentage' => $result[$i]['custom_stop_loss_percentage'],
                            'iniatial_trail_stop' => $result[$i]['iniatial_trail_stop'],
                            // 'modified_date' => $modified_date, 
                        ],
                        '$unset' => [
                            'stop_loss_type' => ''
                        ],
                    ];
                    $db->$buy_collection->updateOne($where, $set);
                

                }
            }
            unset($result);

            //Save last Cron Executioon
            $last_cron_exchange = "update_stop_loss_of_orders_with_pl_negative_$exchange";
            $this->last_cron_execution_time($last_cron_exchange, "1d", "Cronjob to update stop loss of orders with PL -2.5 $exchange (0 10 * * *)", 'stop_loss');

        }else{
            // echo "exchange is required, <br><br> test_revert_parent_query/exchange_name";
        }

    }
    
    public function update_stop_loss_of_orders_with_pl_negative_kraken($exchange=''){

        $exchange = 'kraken';
        if($exchange !=''){

            $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
            
            $pl_value = -2.5;
            $stop_loss = 2.5;
            $modified_date = date('Y-m-d H:i:s', strtotime('-2 days'));
            $modified_date = $this->mongo_db->converToMongodttime($modified_date);

            $pricesObjArr = get_current_market_prices($exchange);
            $pricesJsonArr = [];
            // echo "<pre>";

            foreach($pricesObjArr as $key=>$val){
                $pricesJsonArr[] = [
                    'symbol' => $key,
                    'price' => $val,
                ];
            }
            unset($pricesObjArr);
            
            // print_r($pricesJsonArr);
            // $pricesJsonArr = Json_encode($pricesArr);
            // echo "$pricesJsonArr <br> <br>";

            $pipeline = [
                [
                    '$match' => [
                        'application_mode' => 'live',
                        'buy_parent_id' => ['$exists' => true],
                        'stop_loss_type' => 'positive',
                        '$or' => [
                            [   
                                'status' => [ '$in' => ['FILLED', 'FILLED_ERROR', 'SELL_ID_ERROR'] ], 
                                'is_sell_order' => 'yes', 
                                'is_lth_order' => [ '$ne' => 'yes' ], 
                                'cost_avg' => 'yes', 
                                'cavg_parent' => 'yes', 
                                'show_order' => 'yes', 
                                'avg_orders_ids.0' => [ '$exists' => false ], 
                                'move_to_cost_avg' => [ '$ne' => 'yes' ] 
                            ],
                            [ 
                                'status' => [ '$in' => ['FILLED', 'FILLED_ERROR', 'SELL_ID_ERROR'] ], 
                                'is_sell_order' => 'yes', 
                                'is_lth_order' => [ '$ne' => 'yes' ], 
                                'cost_avg' => [ '$nin' => ['yes', 'taking_child', 'completed'] ] 
                            ]  
                        ],
                        "modified_date" => [ '$lte' => $modified_date]
                    ]
                ],
                [ 
                    '$addFields' => [ 
                        'currPrice' => [
                            '$filter' => [
                                'input' => $pricesJsonArr,
                                'as' => 'item',
                                'cond' => [ '$eq' => [ '$$item.symbol', '$symbol' ] ]
                            ]
                        ]
                    ] 
                ],
                [ 
                    '$addFields' => [ 
                        'purchased_price' => [ '$toDouble' => '$purchased_price' ],
                        'currPrice' =>[ '$arrayElemAt' => [ '$currPrice', 0 ] ],
                    ] 
                ],
                [ 
                    '$addFields' => [ 
                        'currPrice' => [ '$toDouble' => '$currPrice.price' ]
                    ] 
                ],
                [ 
                    '$addFields' => [ 
                        'pl' => [ '$divide' => [ [ '$multiply' => [ [ '$subtract' => [ '$currPrice', '$purchased_price' ] ], 100 ] ], '$purchased_price' ] ]
                    ]
                ],
                [
                    '$match' => [
                        '$expr' => [ '$lte' => ['$pl', $pl_value] ]
                    ]
                ],
                [ 
                    '$addFields' => [ 
                        'custom_stop_loss_percentage' => $stop_loss,
                        'iniatial_trail_stop' => [ '$subtract' => [ '$purchased_price', [ '$divide' => [ [ '$multiply' => [ $stop_loss, '$purchased_price' ] ], 100 ] ] ] ],
                    ]
                ],
                [
                    '$project' => [
                        '_id' => 1,
                        // 'symbol' =>1,
                        // 'quantity' =>1,
                        // 'purchased_price' =>1,
                        // 'currPrice1' => 1,
                        // 'pl' =>1,
                        'custom_stop_loss_percentage' => 1,
                        'iniatial_trail_stop' => 1,
                        // 'modified_date' =>1
                    ]
                ],
            ];
    
            $db = $this->mongo_db->customQuery();
            $result = $db->$buy_collection->aggregate($pipeline);

            $result = iterator_to_array($result);
            // print_r($result);
            // var_dump($result);
    
            if (!empty($result)) {

                $total = count($result);
                for($i=0; $i<$total; $i++){

                    // print_r($result[$i]);
                    // echo "<br> processed ID: ".$result[$i]['_id'];
                    
                    // $modified_date = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));
                
                    $where = [
                        '_id' => $result[$i]['_id']
                    ];
                    $set = [
                        '$set' => [
                            'custom_stop_loss_percentage' => $result[$i]['custom_stop_loss_percentage'],
                            'iniatial_trail_stop' => $result[$i]['iniatial_trail_stop'],
                            // 'modified_date' => $modified_date, 
                        ],
                        '$unset' => [
                            'stop_loss_type' => ''
                        ],
                    ];
                    $db->$buy_collection->updateOne($where, $set);
                

                }
            }
            unset($result);

            //Save last Cron Executioon
            $last_cron_exchange = "update_stop_loss_of_orders_with_pl_negative_$exchange";
            $this->last_cron_execution_time($last_cron_exchange, "1d", "Cronjob to update stop loss of orders with PL -2.5 $exchange (0 10 * * *)", 'stop_loss');

        }else{
            // echo "exchange is required, <br><br> test_revert_parent_query/exchange_name";
        }

    }

    public function revert_parents_whith_child_pl_negative($exchange=''){

        $exchange = 'binance';
        if($exchange !=''){

            $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
            
            $pl_value = -5;
            $pricesObjArr = get_current_market_prices($exchange);
            $pricesJsonArr = [];
            echo "<pre>";

            foreach($pricesObjArr as $key=>$val){
                $pricesJsonArr[] = [
                    'symbol' => $key,
                    'price' => $val,
                ];
            }
            unset($pricesObjArr);
            
            // print_r($pricesJsonArr);
            // $pricesJsonArr = Json_encode($pricesArr);
            // echo "$pricesJsonArr <br> <br>";

           $pipeline = [
                [
                    '$match' => [
                        'application_mode' => 'live',
                        'buy_parent_id' => ['$exists' => true],
                        'status' => ['$in' => ['LTH', 'LTH_ERROR']],
                        'is_sell_order' => 'yes',
                        'cost_avg' => ['$nin' => ['taking_child', 'yes', 'completed']],
                    ]
                ],
                [ 
                    '$addFields' => [ 
                        'currPrice' => [
                            '$filter' => [
                                'input' => $pricesJsonArr,
                                'as' => 'item',
                                'cond' => [ '$eq' => [ '$$item.symbol', '$symbol' ] ]
                            ]
                        ]
                    ] 
                ],
                [ 
                    '$addFields' => [ 
                        'purchased_price' => [ '$toDouble' => '$purchased_price' ],
                        'currPrice' =>[ '$arrayElemAt' => [ '$currPrice', 0 ] ],
                    ] 
                ],
                [ 
                    '$addFields' => [ 
                        'currPrice' => [ '$toDouble' => '$currPrice.price' ]
                    ] 
                ],
                [ 
                    '$addFields' => [ 
                        'pl' => [ '$divide' => [ [ '$multiply' => [ [ '$subtract' => [ '$currPrice', '$purchased_price' ] ], 100 ] ], '$purchased_price' ] ]
                    ]
                ],
                [
                    '$match' => [
                        '$expr' => [ '$lte' => ['$pl', $pl_value] ]
                    ]
                ],
                [
                    '$project' => [
                        'buy_parent_id' => 1,
                    ]
                ],
                // [
                //     '$limit' => 5,
                // ],
                [
                    '$lookup' => [
                        'from' => $buy_collection,
                        'let' => [
                            'parent_id' => '$buy_parent_id',
                        ],
                        'pipeline' => [
                            [
                                '$match' => [
                                    'parent_status' => 'parent',
                                    'application_mode' => 'live',
                                    'status' => 'takingOrder',
                                    '$expr' => [
                                        '$eq' => ['$_id', '$$parent_id'],
                                    ],
                                ],
                            ],
                            [
                                '$project' => [
                                    '_id' => 1,
                                ]
                            ],
                        ],
                        'as' => 'recordArr',
                    ],
                ],
                [
                    '$addFields' => [
                        'itemsCount' => ['$size'=> '$recordArr'],
                    ]
                ],
                [
                    '$match' => [
                        '$expr' => ['$gte'=> ['$itemsCount', 1]]
                    ],
                ]
            ];
    
            $db = $this->mongo_db->customQuery();
            $result = $db->$buy_collection->aggregate($pipeline);
            $result = iterator_to_array($result);

            echo count($result);
            // echo "<br><br>";
            // print_r($result);
            // var_dump($result);
    
            if (!empty($result)) {

                $total = count($result);
                $ids = [];
                for($i=0; $i<$total; $i++){
                    $ids[] = $result[$i]['recordArr'][0]['_id'];
                }

                if(!empty($ids)){
                    $modified_date = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));
                    $where = [
                        '_id' => ['$in'=>$ids],
                        'status' => 'takingOrder',
                    ];
                    $set = [
                        '$set' => [
                            'status' => 'new',
                            'modified_date' => $modified_date, 
                        ],
                    ];
                    $db->$buy_collection->updateMany($where, $set);
                }
            }
            unset($result, $ids);

            //Save last Cron Executioon
            $last_cron_exchange = "revert_parents_whith_child_pl_negative_$exchange";
            $this->last_cron_execution_time($last_cron_exchange, "1d", "Cronjob to revert parents of orders with PL -5 $exchange (0 11 * * *)", 'parent_revert');

        }else{
            // echo "exchange is required, <br><br> test_revert_parent_query/exchange_name";
        }

    }
    
    public function revert_parents_whith_child_pl_negative_kraken($exchange=''){

        $exchange = 'kraken';
        if($exchange !=''){

            $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
            
            $pl_value = -5;
            $pricesObjArr = get_current_market_prices($exchange);
            $pricesJsonArr = [];
            echo "<pre>";

            foreach($pricesObjArr as $key=>$val){
                $pricesJsonArr[] = [
                    'symbol' => $key,
                    'price' => $val,
                ];
            }
            unset($pricesObjArr);
            
            // print_r($pricesJsonArr);
            // $pricesJsonArr = Json_encode($pricesArr);
            // echo "$pricesJsonArr <br> <br>";

           $pipeline = [
                [
                    '$match' => [
                        'application_mode' => 'live',
                        'buy_parent_id' => ['$exists' => true],
                        'status' => ['$in' => ['LTH', 'LTH_ERROR']],
                        'is_sell_order' => 'yes',
                        'cost_avg' => ['$nin' => ['taking_child', 'yes', 'completed']],
                    ]
                ],
                [ 
                    '$addFields' => [ 
                        'currPrice' => [
                            '$filter' => [
                                'input' => $pricesJsonArr,
                                'as' => 'item',
                                'cond' => [ '$eq' => [ '$$item.symbol', '$symbol' ] ]
                            ]
                        ]
                    ] 
                ],
                [ 
                    '$addFields' => [ 
                        'purchased_price' => [ '$toDouble' => '$purchased_price' ],
                        'currPrice' =>[ '$arrayElemAt' => [ '$currPrice', 0 ] ],
                    ] 
                ],
                [ 
                    '$addFields' => [ 
                        'currPrice' => [ '$toDouble' => '$currPrice.price' ]
                    ] 
                ],
                [ 
                    '$addFields' => [ 
                        'pl' => [ '$divide' => [ [ '$multiply' => [ [ '$subtract' => [ '$currPrice', '$purchased_price' ] ], 100 ] ], '$purchased_price' ] ]
                    ]
                ],
                [
                    '$match' => [
                        '$expr' => [ '$lte' => ['$pl', $pl_value] ]
                    ]
                ],
                [
                    '$project' => [
                        'buy_parent_id' => 1,
                    ]
                ],
                // [
                //     '$limit' => 5,
                // ],
                [
                    '$lookup' => [
                        'from' => $buy_collection,
                        'let' => [
                            'parent_id' => '$buy_parent_id',
                        ],
                        'pipeline' => [
                            [
                                '$match' => [
                                    'parent_status' => 'parent',
                                    'application_mode' => 'live',
                                    'status' => 'takingOrder',
                                    '$expr' => [
                                        '$eq' => ['$_id', '$$parent_id'],
                                    ],
                                ],
                            ],
                            [
                                '$project' => [
                                    '_id' => 1,
                                ]
                            ],
                        ],
                        'as' => 'recordArr',
                    ],
                ],
                [
                    '$addFields' => [
                        'itemsCount' => ['$size'=> '$recordArr'],
                    ]
                ],
                [
                    '$match' => [
                        '$expr' => ['$gte'=> ['$itemsCount', 1]]
                    ],
                ]
            ];
    
            $db = $this->mongo_db->customQuery();
            $result = $db->$buy_collection->aggregate($pipeline);
            $result = iterator_to_array($result);

            echo count($result);
            // echo "<br><br>";
            // print_r($result);
            // var_dump($result);
    
            if (!empty($result)) {

                $total = count($result);
                $ids = [];
                for($i=0; $i<$total; $i++){
                    $ids[] = $result[$i]['recordArr'][0]['_id'];
                }

                if(!empty($ids)){
                    $modified_date = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));
                    $where = [
                        '_id' => ['$in'=>$ids],
                        'status' => 'takingOrder',
                    ];
                    $set = [
                        '$set' => [
                            'status' => 'new',
                            'modified_date' => $modified_date, 
                        ],
                    ];
                    $db->$buy_collection->updateMany($where, $set);
                }
            }
            unset($result, $ids);

            //Save last Cron Executioon
            $last_cron_exchange = "revert_parents_whith_child_pl_negative_$exchange";
            $this->last_cron_execution_time($last_cron_exchange, "1d", "Cronjob to revert parents of orders with PL -5 $exchange (0 11 * * *)", 'parent_revert');

        }else{
            // echo "exchange is required, <br><br> test_revert_parent_query/exchange_name";
        }

    }

    public function revert_parent_with_no_child($exchange=''){

        $exchange = 'binance';
        if($exchange !=''){

            $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
            $temp_collection = $exchange == "binance" ? "temp_parent_ids_revert_issue" : "temp_parent_ids_revert_issue_$exchange";

            $json_pipeline = '[ 
                { 
                    "$match": { "application_mode": "live", "parent_status": "parent", "status": "takingOrder" } 
                }, 
                { 
                    "$project": { "parent_id": "$_id" } 
                }, 
                { 
                    "$lookup": { 
                        "from": "'.$buy_collection.'", 
                        "let": { "parent_id": "$parent_id" }, 
                        "pipeline": [ 
                            { 
                                "$match": { 
                                    "application_mode": "live", 
                                    "$expr": { "$eq": ["$buy_parent_id", "$$parent_id"] }, 
                                    "$or": [ 
                                        { "status": { "$in": ["new", "new_ERROR", "BUY_ID_ERROR"] }, "price": { "$ne": "" } }, { "status": { "$in": ["FILLED", "FILLED_ERROR", "SELL_ID_ERROR"] }, "is_sell_order": "yes", "is_lth_order": { "$ne": "yes" }, "cost_avg": "yes", "cavg_parent": "yes", "show_order": "yes", "avg_orders_ids.0": { "$exists": false }, "move_to_cost_avg": { "$ne": "yes" } }, { "status": { "$in": ["FILLED", "FILLED_ERROR", "SELL_ID_ERROR"] }, "is_sell_order": "yes", "is_lth_order": { "$ne": "yes" }, "cost_avg": { "$nin": ["yes", "taking_child", "completed"] } }, { "status": { "$in": ["submitted", "submitted_for_sell", "fraction_submitted_sell", "submitted_ERROR"] }, "cost_avg": { "$nin": ["taking_child", "yes", "completed"] } }
                                        ] 
                                } 
                            }, 
                            { 
                                "$project": { "_id": 1, "buy_parent_id": 1 } 
                            } 
                        ], 
                        "as": "recordArr" 
                    } 
                }, 
                { 
                    "$project": { "recordArr": 1 } 
                }, 
                { 
                    "$out": "'.$temp_collection.'" 
                }
            ]';
    
            $pipeline = json_decode($json_pipeline, true);
    
            $db = $this->mongo_db->customQuery();
            $db->$buy_collection->aggregate($pipeline);
    
            $db->$temp_collection->deleteMany(['recordArr.0' => ['$exists' => true]]);
            
            $result = $db->$temp_collection->find([], ['_id' => 1,]);
            $result = iterator_to_array($result);
            
            // echo "<pre>";
            // print_r($result);
            // return false;
    
            if (!empty($result)) {
                $ids = array_column($result, '_id'); 
                if(!empty($ids)){

                    $total = count($ids);
                    echo "<br> total parents processed: $total <br>";
    
                    $modified_date = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));
                    // $db->$buy_collection->updateMany(['_id' => ['$in' => $ids]], ['$set' => ['status'=>'new', 'modified_date' => $modified_date, 'revert_by_manual_query'=>'yes' ]]);
                    
                    $db->$buy_collection->updateMany(['_id' => ['$in' => $ids]], ['$set' => ['status'=>'new', 'modified_date' => $modified_date, 'pause_status' => 'play', 'revert_and_play_by_manual_query'=>'yes' ]]);
    
                    $db->$temp_collection->deleteMany([]);
                }
            }
            unset($result);

            //Save last Cron Executioon
            $last_cron_exchange = "revert_parent_with_no_child_$exchange";
            $this->last_cron_execution_time($last_cron_exchange, "1d", "Cronjob to revert parents with no childs $exchange (5 9 * * *)", 'parent_revert');

        }else{
            echo "exchange is required, <br><br> test_revert_parent_query/exchange_name";
        }

    }

    public function revert_parent_with_no_child_kraken($exchange=''){

        $exchange = 'kraken';
        if($exchange !=''){

            $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
            $temp_collection = $exchange == "binance" ? "temp_parent_ids_revert_issue" : "temp_parent_ids_revert_issue_$exchange";

            $json_pipeline = '[ 
                { 
                    "$match": { "application_mode": "live", "parent_status": "parent", "status": "takingOrder" } 
                }, 
                { 
                    "$project": { "parent_id": "$_id" } 
                }, 
                { 
                    "$lookup": { 
                        "from": "'.$buy_collection.'", 
                        "let": { "parent_id": "$parent_id" }, 
                        "pipeline": [ 
                            { 
                                "$match": { 
                                    "application_mode": "live", 
                                    "$expr": { "$eq": ["$buy_parent_id", "$$parent_id"] }, 
                                    "$or": [ 
                                        { "status": { "$in": ["new", "new_ERROR", "BUY_ID_ERROR"] }, "price": { "$ne": "" } }, { "status": { "$in": ["FILLED", "FILLED_ERROR", "SELL_ID_ERROR"] }, "is_sell_order": "yes", "is_lth_order": { "$ne": "yes" }, "cost_avg": "yes", "cavg_parent": "yes", "show_order": "yes", "avg_orders_ids.0": { "$exists": false }, "move_to_cost_avg": { "$ne": "yes" } }, { "status": { "$in": ["FILLED", "FILLED_ERROR", "SELL_ID_ERROR"] }, "is_sell_order": "yes", "is_lth_order": { "$ne": "yes" }, "cost_avg": { "$nin": ["yes", "taking_child", "completed"] } }, { "status": { "$in": ["submitted", "submitted_for_sell", "fraction_submitted_sell", "submitted_ERROR"] }, "cost_avg": { "$nin": ["taking_child", "yes", "completed"] } }
                                        ] 
                                } 
                            }, 
                            { 
                                "$project": { "_id": 1, "buy_parent_id": 1 } 
                            } 
                        ], 
                        "as": "recordArr" 
                    } 
                }, 
                { 
                    "$project": { "recordArr": 1 } 
                }, 
                { 
                    "$out": "'.$temp_collection.'" 
                }
            ]';
    
            $pipeline = json_decode($json_pipeline, true);
    
            $db = $this->mongo_db->customQuery();
            $db->$buy_collection->aggregate($pipeline);
    
            $db->$temp_collection->deleteMany(['recordArr.0' => ['$exists' => true]]);
            
            $result = $db->$temp_collection->find([], ['_id' => 1,]);
            $result = iterator_to_array($result);
            
            // echo "<pre>";
            // print_r($result);
            // return false;
    
            if (!empty($result)) {
                $ids = array_column($result, '_id'); 
                if(!empty($ids)){

                    $total = count($ids);
                    echo "<br> total parents processed: $total <br>";
    
                    $modified_date = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));
                    // $db->$buy_collection->updateMany(['_id' => ['$in' => $ids]], ['$set' => ['status'=>'new', 'modified_date' => $modified_date, 'revert_by_manual_query'=>'yes' ]]);
                    
                    $db->$buy_collection->updateMany(['_id' => ['$in' => $ids]], ['$set' => ['status'=>'new', 'modified_date' => $modified_date, 'pause_status' => 'play', 'revert_and_play_by_manual_query'=>'yes' ]]);
    
                    $db->$temp_collection->deleteMany([]);
                }
            }
            unset($result);

            //Save last Cron Executioon
            $last_cron_exchange = "revert_parent_with_no_child_$exchange";
            $this->last_cron_execution_time($last_cron_exchange, "1d", "Cronjob to revert parents with no childs $exchange (6 9 * * *)", 'parent_revert');

        }else{
            echo "exchange is required, <br><br> test_revert_parent_query/exchange_name";
        }

    }

    public function min_notation_kraken_update(){

         $req_arr = [
            'req_type' => 'GET',
            'req_params' => [],
            'req_endpoint' => '',
            'req_url' => 'https://api.kraken.com/0/public/AssetPairs',
        ];
        $resp = hitCurlRequest($req_arr);
        
        if($resp['http_code'] == 200 && !empty($resp['response']) && empty($resp['response']['error'])){
            // echo "<pre>";
            $pairsArr = [
                'XBTUSDT' => 'BTCUSDT',
                'LTCUSDT' => 'LTCUSDT',
                'LINKXBT' => 'LINKBTC',
                'EOSXBT' => 'EOSBTC',
                'ADAXBT' => 'ADABTC',
                'TRXXBT'  => 'TRXBTC',
                'XRPUSDT'  => 'XRPUSDT',
                'QTUMXBT'  => 'QTUMBTC',
                'DASHXBT'  => 'DASHBTC',
                'XXLMXXBT' => 'XLMBTC',
                'XXMRXXBT' => 'XMRBTC',
                'XETHXXBT'  => 'ETHBTC',
                'XETCXXBT'  => 'ETCBTC',
                'XXRPXXBT'  => 'XRPBTC',
                'COMPXBT'   => 'COMPBTC',
                'KSMXBT'   => 'KSMBTC',
                'ANTXBT'   => 'ANTBTC',
                'ADAUSDT'   => 'ADAUSDT',
                'DOTUSDT'   => 'DOTUSDT',
                'DOTXBT'   => 'DOTBTC',
                'AAVEXBT'   => 'AAVEBTC',
                'EOSUSDT'   => 'EOSUSDT',
                'ALGOXBT'   => 'ALGOBTC',
                'BCHXBT'   => 'BCHBTC',
                'XLTCXXBT'   => 'LTCBTC',
                'ALGOUSDT'   => 'ALGOUSDT',
                'BCHUSDT'   => 'BCHUSDT',
                'LINKUSDT'   => 'LINKUSDT',
                'LTCUSDT'   => 'LTCUSDT',
                'ETHUSDT'   => 'ETHUSDT',
            ];
            echo '<pre>';
            print_r($pairs);
            echo '<pre>';
            print_r($resp['response']);exit;

            $db = $this->mongo_db->customQuery();

            foreach($pairsArr as $key=>$val){
                // echo "$key  ===  $val  ===  ".$resp['response']['result'][$key]['ordermin']."<br>";
                $db->market_min_notation_kraken->updateOne(['symbol' => $val], ['$set' => ['min_notation' => (float) $resp['response']['result'][$key]['ordermin'] ]]);
            }

             //Save last Cron Executioon
            $last_cron_exchange = "min_notation_kraken_update";
            $this->last_cron_execution_time($last_cron_exchange, "7d", "Cronjob to update kraken min notation weekly  (0 20 * * 1)", 'min_notation');
        }
        echo "<br>Done!";
    }


    /*************  Clean DB crons  ***************** */
    public function delete_one_month_old_notifications(){
        
        $db = $this->mongo_db->customQuery();
        
        $end_date = date('Y-m-d H:i:s', strtotime('-30 days'));
        $end_date = $this->mongo_db->converToMongodttime($end_date);

        $where = [
            'status' => ['$in' => [1, '1'] ],
            'created_date' => ['$lt' => $end_date],
        ];

        // $total_rec = $db->notifications->count($where);
        // echo "$total_rec";

        $db->notifications->deleteMany($where);

        //Save last Cron Executioon
        $this->last_cron_execution_time("delete_one_month_old_notifications", "1d", "Delete 1 month old notifications that are read  (0 1 * * *)", 'cleanDB');

    } 

    /*************  End Clean DB crons  ************* */



    /**********  Cron to save binance trade history *************  */
    public function getUserAllTrades($user_id='', $symbol='', $limit=1000){

        echo "21234142142342431243123412 <pre>";

        $db = $this->mongo_db->customQuery();

        if(empty($user_id)){
            $pipeline = [
                [
                    '$match' => [
                        'application_mode' => [ '$in' =>[ 'both', 'live' ] ],
                        '$and' => [
                            [
                                'api_key' => ['$exists' => true],
                            ],
                            [
                                'api_key' => ['$nin' => ['', null] ],
                            ],
                            [
                                'api_secret' => ['$exists' => true],
                            ],
                            [
                                'api_secret' => ['$nin' => ['', null] ],
                            ],
                        ],
                        'binance_trade_hisoty_imported' => ['$ne' => 'yes']
                    ]
                ],
                [
                    '$limit' => 1,
                ]
            ];

            $user = $db->users->aggregate($pipeline);
            $user = iterator_to_array($user);
            // print_r($user);
            $user_id = (string) $user[0]['_id'];
            unset($user);
        }

        if(!empty($user_id)){

            $collectionName = 'binance_trade_history_import_status';
            $inProgress = $db->$collectionName->find([]);
            $inProgress = iterator_to_array($inProgress);
            if(!empty($inProgress)){
                return;
            }else{
                $insData = [
                    'binance_trade_hisoty_import_user_in_progress' => (string) $user_id,
                    'created_date' => $this->mongo_db->converToMongodttime(date('Y-m-d G:i:s')),
                ];
                $db->$collectionName->insertOne($insData);
            }
            
            if(!empty($symbol)){
                $symbolsArr = [$symbol];
            }else{
                $pricesArr = get_current_market_prices('binance', []);
                $symbolsArr = array_keys($pricesArr);
            }
    
            foreach($symbolsArr as $symbol){
    
                $endTime = '';
                $limit = 1000;
                
                do {
                    
                    $trades = $this->getTradesHistory($user_id, $symbol, $limit, $endTime);
        
                    $endTime = !empty($trades) && !empty($trades[0]) && !empty($trades[0]['time']) ? $trades[0]['time'] - 1 : '';
    
                    if(!empty($trades) && !empty($trades[0]) && !empty($trades[0]['time'])){
                        $this->saveTradesHistory($user_id, $trades);
                    }
    
                    unset($trades);
    
                    // break;
                    sleep(2);
        
                } while ($endTime != '');
         
                // break;
            }
    
            $collectionName = 'users';
            $updData = [
                '$set' => [
                    'binance_trade_hisoty_imported' => 'yes'
                ]
            ];
            $mongo_user_id = $this->mongo_db->mongoId((string) $user_id);
            $db->$collectionName->updateOne(['_id' => $mongo_user_id], $updData);

            $collectionName = 'binance_trade_history_import_status';
            $db->$collectionName->deleteMany([]);

        }

        echo('<br> ****************** End Script ****************** <br>');

    }

    public function getTradesHistory($user_id, $symbol, $limit, $endTime='', $startTime='', $fromId=''){
        $options = [
            'symbol' => $symbol,	// required	
            'limit' => $limit,  // optional
        ];
        
        if(!empty($endTime)){
            $options['endTime'] = $endTime;
        }

        if(!empty($startTime)){
            $options['startTime'] = $startTime;
        }
        
        if(!empty($fromId)){
            $options['fromId'] = (int) $fromId;
        }

        $tradeHistory = $this->binance_api->get_orders_custom($user_id, $options);
        return $tradeHistory;
    }

    public function updateUserTradeHistory($user_id='', $symbol='', $limit=1000){

        // echo "<pre>";
        
        $db = $this->mongo_db->customQuery();

        if(empty($user_id)){
            $pipeline = [
                [
                    '$match' => [
                        'application_mode' => [ '$in' =>[ 'both', 'live' ] ],
                        '$and' => [
                            [
                                'api_key' => ['$exists' => true],
                            ],
                            [
                                'api_key' => ['$nin' => ['', null] ],
                            ],
                            [
                                'api_secret' => ['$exists' => true],
                            ],
                            [
                                'api_secret' => ['$nin' => ['', null] ],
                            ],
                        ],
                        'binance_trade_hisoty_imported' => 'yes'
                    ]
                ],
                [
                    '$sort' => ['binance_trade_history_updated_date' => 1],
                ],
                [
                    '$limit' => 1,
                ]
            ];

            $user = $db->users->aggregate($pipeline);
            $user = iterator_to_array($user);
            // print_r($user);
            $user_id = (string) $user[0]['_id'];
            unset($user);
        }

        if(!empty($user_id)){

            $collectionName = 'binance_trade_history_update_status';
            $inProgress = $db->$collectionName->find([]);
            $inProgress = iterator_to_array($inProgress);
            if(!empty($inProgress)){
                // return;
            }else{
                $insData = [
                    'binance_trade_hisoty_import_user_in_progress' => (string) $user_id,
                    'created_date' => $this->mongo_db->converToMongodttime(date('Y-m-d G:i:s')),
                ];
                $db->$collectionName->insertOne($insData);
            }
            
            if(!empty($symbol)){
                $symbolsArr = [$symbol];
            }else{
                $pricesArr = get_current_market_prices('binance', []);
                $symbolsArr = array_keys($pricesArr);
            }
            // $symbolsArr = ['BTCUSDT'];
    
            foreach($symbolsArr as $symbol){

                // echo "<br>";
    
                $endTime = '';
                $startTime = '';
                $fromId = '';
                $limit = 1000;
                
                //get last trade from trade history in digie
                $collectionName = 'user_trade_history';
                $tempPipeline = [
                    [
                        '$match' => [
                            'user_id' => (string) $user_id,
                            'trades.value.pair' => $symbol,
                        ]
                    ],
                    [
                        '$sort' => [
                            'trades.value.time' => -1,
                        ]
                    ],
                    [
                        '$limit' => 1
                    ],
                ];
                $lastTradeSaved = $db->$collectionName->aggregate($tempPipeline);
                $lastTradeSaved = iterator_to_array($lastTradeSaved);

                if(!empty($lastTradeSaved)){
                    $startTime = $lastTradeSaved[0]['trades']['value']['time'] + 1;
                }
                
                // echo "$user_id, $symbol, $limit, $endTime, $startTime, $fromId <br>";
                $trades = $this->getTradesHistory($user_id, $symbol, $limit, $endTime, $startTime, $fromId);
                
                $endTime = '';
                $startTime = '';

                if(!empty($trades) && !empty($trades[0]) && !empty($trades[0]['time'])){
                    $this->saveTradesHistory($user_id, $trades);
                    // print_r($trades);
                }

                unset($trades);

                sleep(2);
                // break;
            }
    
            $collectionName = 'users';
            $updData = [
                '$set' => [
                    'binance_trade_history_updated_date' => $this->mongo_db->converToMongodttime(date('Y-m-d G:i:s'))
                ]
            ];
            $mongo_user_id = $this->mongo_db->mongoId((string) $user_id);
            $db->$collectionName->updateOne(['_id' => $mongo_user_id], $updData);

            $collectionName = 'binance_trade_history_update_status';
            $db->$collectionName->deleteMany([]);

        }

        echo('<br> ****************** End Script ****************** <br>');

    }

    public function saveTradesHistory($user_id, $tradesArr){
        if(!empty($user_id) && !empty($tradesArr)){
            
            $collectionName = 'user_trade_history';
            $db = $this->mongo_db->customQuery();

            $total = count($tradesArr);
            for($i=0; $i < $total; $i++){

                $currTrade = $tradesArr[$i];

                $currTrade['ordertxid'] = $currTrade['orderId'];
                $currTrade['postxid'] = '';
                $currTrade['pair'] = $currTrade['symbol'];
                $currTrade['time'] = (float) $currTrade['time'];
                $currTrade['type'] = (!$currTrade['isBuyer']) ? 'sell' : 'buy';
                $currTrade['ordertype'] = 'market';
                $currTrade['price'] = $currTrade['price'];
                $currTrade['cost'] = '';
                $currTrade['fee'] = '';
                $currTrade['vol'] = $currTrade['qty'];
                $currTrade['margin'] = '';
                $currTrade['misc'] = '';

                // [symbol] => QTUMBTC
                // [id] => 16100992
                // [orderId] => 184677015
                // [orderListId] => -1
                // [price] => 0.00013490
                // [qty] => 0.94000000
                // [quoteQty] => 0.00012680
                // [commission] => 0.00002589
                // [commissionAsset] => BNB
                // [time] => 1613653412205
                // [isBuyer] => 1
                // [isMaker] => 
                // [isBestMatch] => 1

                $insData = [
                    'user_id' => (string) $user_id,
                    'trades' => [
                        'number' => $currTrade['orderId'], 
                        'value' => $currTrade
                    ]
                ];
                $exists = $db->$collectionName->insertOne($insData);

            }
        }
        return true;
    }
    /**********  End Cron to save binance trade history ********** */


    /* ****** Supporting script pick parent yes for new users ********* */
    public function newUserPickParentYesIfNo($user_id=''){
        
        $db = $this->mongo_db->customQuery();

        $joining_date = $this->mongo_db->converToMongodttime(date('Y-m-d 00:00:00', strtotime('-1 days')));

		$pipeline = [
			[
                '$match' => [
                    'application_mode' => ['$in' => ['both', 'live'] ],
                    'created_date' => ['$gte' => $joining_date],
                ]
            ],
            [
                '$project' => [
                    'user_id' => ['$toString' => '$_id']
                ]
            ]
        ];

        if(!empty($user_id)){
            $pipeline[0]['$match']['_id'] = $this->mongo_db->mongoId((string) $user_id);
        }
        
        $users = $db->users->aggregate($pipeline);
        $users = iterator_to_array($users);

        $timezone = "Asia/Karachi";
        $new_timezone = new DateTimeZone($timezone);
        $created_date = $joining_date->toDateTime();
        $created_date = $created_date->format(DATE_RSS);
        $created_date = new DateTime($created_date);
        $created_date->setTimezone($new_timezone);
        $created_date = $created_date->format('Y-m-d g:i:s A');

        // echo "<pre>";
        // echo "joining_date: $created_date ($timezone) <br>";
        // echo "total user joined: ".(count($users)). "<br>";
        // echo "________________________________________________________________________________________ <br><br>";
        // print_r($users);

        foreach($users as $val){
            $is_binance_ApiKeySet =  $this->isApiKeySet($val['user_id'], 'binance');
            $is_kraken_ApiKeySet =  $this->isApiKeySet($val['user_id'], 'kraken');
            $is_bam_ApiKeySet =  $this->isApiKeySet($val['user_id'], 'bam');

            // echo $val['user_id']." is_binance_ApiKeySet $is_binance_ApiKeySet <br>";
            // echo $val['user_id']." is_kraken_ApiKeySet $is_kraken_ApiKeySet <br>";
            // echo $val['user_id']." is_bam_ApiKeySet $is_bam_ApiKeySet <br>";

            $exchanges = [];
            if($is_binance_ApiKeySet === true){
                $exchanges[] = 'binance';
            }
            if($is_kraken_ApiKeySet === true){
                $exchanges[] = 'kraken';
            }
            if($is_bam_ApiKeySet === true){
                $exchanges[] = 'bam';
            }

            if(!empty($exchanges)){
                foreach($exchanges as $exchange){

                    // echo $val['user_id']." check $exchange <br>";
                    
                    $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
                    $sold_collection = $exchange == "binance" ? "sold_buy_orders" : "sold_buy_orders_$exchange";

                    //count total parent
                    $parent_where = [
                        'admin_id' => $val['user_id'],
                        'application_mode' => 'live',
                        'parent_status' => 'parent',
                        'status' => ['$ne' => 'canceled'],
                    ];
                    $totalParentCount = $db->$buy_collection->count($parent_where);
                    
                    //count total pick_parent_no
                    $parent_where['pick_parent'] = 'no';
                    $totalParentNoCount = $db->$buy_collection->count($parent_where);

                    // echo $val['user_id']." check $exchange ::::::::: total parent: $totalParentCount   no parent = $totalParentNoCount    yes parent = ".( $totalParentCount - $totalParentNoCount )."<br>";

                    if($totalParentCount > 0 && $totalParentCount === $totalParentNoCount){
                        $childsBuyPipeline = [
                            [
                                '$match'=> [
                                    'admin_id' => $val['user_id'],
                                    'purchased_price'=> ['$exists'=> true],
                                    'quantity'=> ['$exists'=> true],
                                    'status'=> [
                                        '$nin'=> [
                                            'canceled',
                                            'error',
                                            'new_ERROR',
                                            'FILLED_ERROR',
                                            'submitted_ERROR',
                                            'LTH_ERROR',
                                            'canceled_ERROR',
                                            'credentials_ERROR',
                                        ]
                                    ],
                                    'buy_date'=> [
                                        '$gte'=> $joining_date,
                                    ],
                                ]
                            ],
                            [
                                '$limit' => 1,
                            ]
                        ];
                        $buy_orders = $db->$buy_collection->aggregate($childsBuyPipeline);
                        $buy_orders = iterator_to_array($buy_orders);
            
                        $sold_orders = $db->$sold_collection->aggregate($childsBuyPipeline);
                        $sold_orders = iterator_to_array($sold_orders);
                        $orders = array_merge($buy_orders, $sold_orders);
            
                        if(empty($orders)){
                            // echo " Trades not buy yet make $exchange pick parent to yes <br>";
                            $db->$buy_collection->updateMany($parent_where, ['$set' => ['pick_parent' => 'yes']]);
                        }
                    }
                    
                }
            }

            // echo "------------------------------------------------------------------------------------------- <br>";
        
        }

    }

    public function isApiKeySet($user_id, $exchange){

        $collection = $exchange == "binance" ? "users" : $exchange."_credentials";

        $db = $this->mongo_db->customQuery();

		$pipeline = [
			[
                '$match' => [
                    '$and' => [
                        [
                            'api_key' => ['$exists' => true],
                        ],
                        [
                            'api_key' => ['$ne' => null],
                        ],
                        [
                            'api_key' => ['$ne' => ''],
                        ],
                        [
                            'api_secret' => ['$exists' => true],
                        ],
                        [
                            'api_secret' => ['$ne' => null],
                        ],
                        [
                            'api_secret' => ['$ne' => ''],
                        ],
                    ],
                ]
            ],
            [
                '$project' => [
                    'user_id' => ['$toString' => ($exchange == 'binance' ? '$_id' : '$user_id') ]
                ]
            ],
            [
                '$limit' => 1,
            ]
        ];

        if($exchange == 'binance'){
            $pipeline[0]['$match']['_id'] = $this->mongo_db->mongoId((string) $user_id);
        }else{
            $pipeline[0]['$match']['user_id'] = (string) $user_id;
        }
        
        $users = $db->$collection->aggregate($pipeline);
        $users = iterator_to_array($users);

        return !empty($users) ? true : false ;
    }
    /* ****** End supporting script pick parent yes for new users ****** */
    
    
    /* ****** supporting script cost avg new buy ****** */
    public function costAvgCronBinance($exchange='binance'){
        
        // echo "<pre>";
        $exchange = 'binance';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $sold_collection = $exchange == "binance" ? "sold_buy_orders" : "sold_buy_orders_$exchange";

        $where = [
            'cost_avg' => ['$in' => ['taking_child', 'yes'] ],
            'cavg_parent' => 'yes',
            'show_order' => 'yes',
            'avg_orders_ids.0' => ['$exists' => true ],
            'status' => ['$ne' => 'canceled'],
            'last_three_sold' => 'yes',
        ];

        // echo "where  ==  ". json_encode($where);
        // echo "<br>";

        $db = $this->mongo_db->customQuery();

        $buy_orders = $db->$buy_collection->find($where);
        $buy_orders = iterator_to_array($buy_orders);
        $sold_orders = $db->$sold_collection->find($where); 
        $sold_orders = iterator_to_array($sold_orders);

        $orders = array_merge($buy_orders, $sold_orders);
        // print_r($orders);

        unset($buy_orders, $sold_orders);
        
        foreach($orders as $val){
            if(!empty($val['avg_orders_ids'])){

                $is_child_updated = false;

                foreach($val['avg_orders_ids'] as $childId){

                    $child_buy_order = $db->$buy_collection->find(['_id' => $childId, 'cost_avg' => 'taking_child']);
                    $child_buy_order = iterator_to_array($child_buy_order);
                    if(!empty($child_buy_order)){

                        $db->$buy_collection->updateOne(['_id' => $childId], ['$set' => ['cost_avg' => 'yes']]);
                        $is_child_updated = true;
                        // echo "child cost_avg to yes ". (string) $childId."<br>";
                        break;
                    }
                }

                if(!$is_child_updated){
                    
                    $buy_parent = $db->$buy_collection->find(['_id'=> $val['_id']]);
                    $buy_parent = iterator_to_array($buy_parent);
                    if(!empty($buy_parent)){
                        // echo "buy parent cost_avg to yes ". (string) $val['_id']."<br>";
                        $db->$buy_collection->updateOne(['_id' => $val['_id']], ['$set' => ['cost_avg' => 'yes']]);
                    }else{
                        $sold_parent = $db->$sold_collection->find(['_id'=> $val['_id']]); 
                        $sold_parent = iterator_to_array($sold_parent);
                        if(!empty($sold_parent)){
                            // echo "sold parent cost_avg to yes ". (string) $val['_id']."<br>";
                            $db->$sold_collection->updateOne(['_id' => $val['_id']], ['$set' => ['cost_avg' => 'yes']]);
                        }
                    }

                }
            }
        }

        $this->last_cron_execution_time("cost_avg_cron_binance", "1h", "check for cost avg order if need to change cost_avg to yes  (33 * * * *)", 'costAvg');

    }
    /* ****** End supporting script cost avg new buy ****** */


    public function last_cron_execution_time($name, $duration, $summary, $type=''){





        //Hit CURL to update last cron execution time
        $params = [
           'name' => $name,
           'cron_duration' => $duration, 
           'cron_summary' => $summary,
           'type' => $type,
        ];
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => '',
            'req_url' => 'http://35.171.172.15:3000/api/save_cronjob_execution',
        ];
        $resp = hitCurlRequest($req_arr);
    }//End last_cron_execution_time

    // public function testing_unset_pick_parent_based_on_base_currency_daily_limit(){
    //     $exchange = 'binance';
    //     $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
    //     $daily_limit_collection = $exchange == "binance" ? "daily_trade_buy_limit" : "daily_trade_buy_limit_$exchange";
    //     $pick_parent_temp_collection = $exchange == "binance" ? "base_currency_daily_limit_pick_parent_temp" : "base_currency_daily_limit_pick_parent_temp_$exchange";
    //     $pipeline = [
    //         [
    //             '$match' => [
    //                 'user_id' => ['$in' => ['614da255d8670166780610e2']],
    //             ],
    //         ],
    //         [
    //             '$project' => [
    //                 'user_id' => '$user_id',
    //                 'btc_usd_worth' => ['$subtract' => [ '$dailyTradeableBTC_usd_worth', '$daily_bought_btc_usd_worth' ] ],
    //                 'usdt_usd_worth' => ['$subtract' => [ '$dailyTradeableUSDT_usd_worth', '$daily_bought_usdt_usd_worth' ] ],
    //                 '_id' => 0,
    //             ],
    //         ],
    //         [
    //             '$lookup' => [
    //                 'from' => $buy_collection,
    //                 'let' => [
    //                     'user_id' => '$user_id',
    //                     'btc_usd_worth' => '$btc_usd_worth',
    //                     'usdt_usd_worth' => '$usdt_usd_worth',
    //                 ],
    //                 'pipeline' => [
    //                     [
    //                         '$match' => [
    //                             'parent_status' => 'parent',
    //                             'application_mode' => 'live',
    //                             'pick_parent' => 'yes',
    //                             '$expr' => [
    //                                 '$eq' => ['$admin_id', '$$user_id'],
    //                             ],
    //                         ],
    //                     ],
    //                     [
    //                         '$addFields' => [
    //                             'splitArr' => ['$split' => ['$symbol', 'USDT']],
    //                             'btc_ten_dollar_more' => [ '$add' => [ '$$btc_usd_worth', 10 ] ],
    //                             'usdt_ten_dollar_more' => [ '$add' => [ '$$usdt_usd_worth', 10 ] ],
    //                         ],
    //                     ],
    //                     [
    //                         '$addFields' => [
    //                             'use_btc_val' => '$btc_ten_dollar_more',
    //                             'use_usdt_val' => '$usdt_ten_dollar_more',
    //                         ],
    //                     ],
    //                     [
    //                         '$addFields' => [
    //                             'remainingBalance' => [
    //                                 '$cond' => ['if' => ['$eq' => [['$arrayElemAt' => ['$splitArr', 1]], '']], 'then' => '$use_usdt_val', 'else' => '$use_btc_val']
    //                             ],
    //                         ],
    //                     ],
    //                     [ 
    //                         '$match' => [
    //                             '$expr' => [
    //                                 '$gt' => ['$usd_worth', '$remainingBalance'],
    //                             ],
    //                         ],
    //                     ],
    //                     [
    //                         '$group' => [
    //                             '_id' => null,
    //                             'ids' => [
    //                                 '$push' => '$_id',
    //                             ],
    //                         ],
    //                     ],
    //                     [
    //                         '$addFields' => [
    //                             'count' => ['$size' => '$ids'],
    //                         ],
    //                     ],
    //                     [
    //                         '$redact' => [
    //                             '$cond' => ['if' => ['$gte' => ['$count', 1]], 'then' => '$$KEEP', 'else' => '$$PRUNE'],
    //                         ],
    //                     ],
    //                     [
    //                         '$project' => [
    //                             '_id' => 0,
    //                             'ids' => 1,
    //                         ],
    //                     ],
    //                 ],
    //                 'as' => 'recordArr',
    //             ],
    //         ],
    //         [
    //             '$project' => [
    //                 '_id' => 0,
    //                 'recordArr' => 1,
    //             ],
    //         ],
    //         [
    //             '$out' => $pick_parent_temp_collection,
    //         ],
    //     ];
    //     $db = $this->mongo_db->customQuery();
    //     $db->$daily_limit_collection->aggregate($pipeline);
    //     $db->$pick_parent_temp_collection->deleteMany(['recordArr' => ['$size' => 0]]);
    //     $result = $db->$pick_parent_temp_collection->find([], ['_id' => 0, 'recordArr' => 1]);
    //     $result = iterator_to_array($result);
    //     echo '<pre>';print_r($result);
    //     if (!empty($result)) {
    //         $total = count($result);
    //         for ($i = 0; $i < $total; $i++) {
    //             $result[$i]['recordArr'][0]['ids'];
    //             $db->$buy_collection->updateMany(['_id' => ['$in' => $result[$i]['recordArr'][0]['ids']]], ['$set' => ['pick_parent' => 'no']]);
    //             $db->$buy_collection->updateMany(['buy_parent_id' => ['$in' => $result[$i]['recordArr'][0]['ids']],'cavg_parent'=>'yes','cost_avg'=>['$ne'=>'completed']], ['$set' => ['pick_parent' => 'no']]);
    //             $db->$pick_parent_temp_collection->deleteOne(['_id' => $result[$i]['_id']]);
    //             unset($result[$i]);
    //         }
    //     }
    //     unset($result);
    //     echo "<br> **************** cron end ****************<br>";

    // }
    public function make_balance_updated_false(){
        $db = $this->mongo_db->customQuery();
        $result = $db->users->updateMany(['_id'=>['$ne'=>'']],['$set'=>['balance_updated'=>false]]);
        $result_kraken = $db->kraken_credentials->updateMany(['_id'=>['$ne'=>'']],['$set'=>['balance_updated'=>false]]);
        echo '********** cron to update balance_update => false in database.. for all users daily at 7:58 AM UST ************';
    }
    // public function set_order_accumulation_cron(){
    //     $exchange = 'kraken';
    //     $user_id = '61982363d9813c48f42df1de';
    //     if($user_id == ''){
    //         echo 'Users id is missing .. !';
    //         exit;
    //     }
    //     $endMongoTime = $this->mongo_db->converToMongodttime(date('2021-06-31 00:00:00'));
    //     $pipeline = [[
    //         '$match'=>[
    //             'admin_id'=>(string)$user_id,
    //             'status'=>"FILLED",
    //             'is_sell_order'=> "sold",
    //             '_id'=>$this->mongo_db->mongoId('62d800c6d3da9655438e1425'),
    //             'parent_status'=>[
    //                 '$ne'=>'parent'
    //             ],
    //             //'created_date'=>['$gte'=>$endMongoTime],
    //         ]
    //     ],['$limit'=>10]];
    //     if($exchange != ''){
    //         if($exchange == 'binance'){
    //             $collection_name = 'sold_buy_orders';
    //         }else{
    //             $collection_name = 'sold_buy_orders_kraken';
    //         }
    //     }else{
    //         echo 'Exchange is required';
    //         exit;
    //     }
    //     $db=$this->mongo_db->customQuery();
    //     $get_order = $db->$collection_name->aggregate($pipeline);
    //     $order_array = iterator_to_array($get_order);
    //     $accumulation = array();
    //     $invest_btc = (float)0.0;
    //     $return_btc = (float)0.0;
    //     $profit_btc = (float)0.0;
    //     if(count($order_array) > 0){
    //         foreach ($order_array as $value) {
    //            if(isset($value['buy_fraction_filled_order_arr'])){
    //                 $invest_btc = ((float)$value['buy_fraction_filled_order_arr'][0]['filledQty'] * (float)$value['buy_fraction_filled_order_arr'][0]['filledPrice']);
    //                 $return_btc = ((float)$value['buy_fraction_filled_order_arr'][0]['filledQty'] * (float)$value['market_sold_price']);
    //                 $profit_btc = (float)$return_btc-(float)$invest_btc;
    //                 $accumulation['invest'] = $invest_btc; 
    //                 $accumulation['return'] = $return_btc; 
    //                 $accumulation['profit'] = $profit_btc;
    //                 $data_update['accumulations'] = $accumulation;
    //                 $data_update['is_accumulated'] =1;
    //                 $db->$collection_name->updateOne(['_id'=>$this->mongo_db->mongoId($value['_id']),'admin_id'=>$user_id],['$set'=>$data_update]);
    //             }else{
    //                 $invest_btc = ((float)$value['purchased_price'] * (float)$value['quantity']);
    //                 $return_btc = ((float)$value['quantity'] * (float)$value['market_sold_price']);
    //                 $profit_btc = (float)$return_btc-(float)$invest_btc;
    //                 $accumulation['invest'] = $invest_btc; 
    //                 $accumulation['return'] = $return_btc; 
    //                 $accumulation['profit'] = $profit_btc;
    //                 $data_update['accumulations'] = $accumulation;
    //                 $data_update['is_accumulated'] =1;
    //                 $db->$collection_name->updateOne(['_id'=>$this->mongo_db->mongoId($value['_id']),'admin_id'=>$user_id],['$set'=>$data_update]);
    //             }
    //         }   
    //     }
    // }
    public function update_trade_history_field(){
        $db = $this->mongo_db->customQuery();
        $users = $db->users->updateMany(['is_api_key_valid'=>'yes'],['$set'=>['tradeHistory_updated'=>false]]);
        $kraken = $db->kraken_credentials->updateMany(['is_api_key_valid'=>'yes'],['$set'=>['tradeHistory_updated'=>false]]);
        $pipeline = [
            [
                '$match'=> ['is_api_key_valid'=>'yes']
            ],
            [
                '$project'=> [
                    'user_id'=> [
                        '$toObjectId'=>'$user_id'
                    ]
                ]
            ]  
        ];
        $get_users = $db->kraken_credentials->aggregate($pipeline);
        $get_users_kraken = iterator_to_array($get_users);
        $users_arr = array();
        foreach($get_users_kraken as $value){
            array_push($users_arr,$value['user_id']);
        }
        if(count($users_arr) > 0){
            $users = $db->users->updateMany(['_id'=>['$in'=>$users_arr]],['$set'=>['tradeHistory_updated_kraken'=>false]]);  
        }
    }
    public function cron_to_update_cost_avg_parent_status_pause_kraken(){
        $this->cost_avg_parents_pause_by_status_update('kraken');   
    }
    public function cron_to_update_cost_avg_parent_status_pause_binance(){
        $this->cost_avg_parents_pause_by_status_update('binance');   
    }
    public function cost_avg_parents_pause_by_status_update($exchange = ''){
        $pipeline = [
            [
                '$match'=> ['admin_id'=>['$ne'=>''],'cost_avg'=>['$in'=>['yes','taking_child','CA_TAKING_CHILD','taking_child']],'cavg_parent'=>"yes",'trigger_type'=>'barrier_percentile_trigger','status'=>['$ne'=>"canceled"]]
            ], 
            [
                '$project'=>[
                    'buy_parent_id'=>1,
                    '_id'=>0,
                    'admin_id'=>1,
                    'symbol'=>1
            ]], 
            [
                '$group'=>[
              '_id'=>['parent_id'=>'$buy_parent_id',
                'admin_id'=>'$admin_id',
                'symbol'=>'$symbol'
              ],
            ]]
        ];
        if($exchange == '' || $exchange == 'binance'){
            $buy_collection = 'buy_orders';
        }else{
            $buy_collection = 'buy_orders_kraken';
        }
        $db = $this->mongo_db->customQuery();
        $response = $db->$buy_collection->aggregate($pipeline);
        $response_array = iterator_to_array($response);
        foreach ($response_array as $value) {
            $admin_id =  $value['_id']['admin_id'];
            $symbol =  $value['_id']['symbol'];
          $resp = $db->$buy_collection->updateMany(['symbol'=>['$eq'=>$symbol],'admin_id'=>$admin_id,'parent_status'=>'parent'],['$set'=>['pause_by' => 'cost_avg_merge','parent_pause'=>'child_n_costavg','pick_parent'=>'no']]);  
        }
    }
    public function cost_avg_parents_pause_by_status_revert($exchange = ''){
        $pipeline = [
            [
                '$match'=> [
                        'pause_by'=>'cost_avg_merge','parent_pause'=>'child_n_costavg','status'=>['$ne'=>'canceled'],'application_mode'=>"live",'parent_status'=>"parent",'admin_id'=>['$exists'=>true],'symbol'=>['$exists'=>true],'admin_id'=>['$ne'=>'']
                ]
            ], [
                '$group'=> [
                    '_id'=>['admin_id'=>'$admin_id','symbol'=>'$symbol']
                ]
            ]
        ];

        if($exchange == '' || $exchange == 'binance'){
            $buy_collection = 'buy_orders';
        }else{
            $buy_collection = 'buy_orders_kraken';
        }
        $db = $this->mongo_db->customQuery();
        $response = $db->$buy_collection->aggregate($pipeline);
        $response_array = iterator_to_array($response);
        
        $count = 0;
        foreach ($response_array as $value) {
            $admin_id =  $value['_id']['admin_id'];
            $symbol =  $value['_id']['symbol'];
            $projection = ['_id' => 1];
            $filter = [
                'admin_id'=>$admin_id,'symbol'=>$symbol,'cost_avg'=>['$exists'=>true],'cost_avg'=>['$in'=>['yes','taking_child','CA_TAKING_CHILD','taking_child']],'status'=>['$in'=>['CA_TAKING_CHILD','FILLED','LTH','TAKING_CHILD','COST_AVG']],'cavg_parent'=>"yes",'trigger_type'=>'barrier_percentile_trigger'
                        ];
            $resp = $db->$buy_collection->count($filter);
            if($resp == 0){
                $resp2 = $db->$buy_collection->updateMany(['symbol'=>['$eq'=>$symbol],'admin_id'=>$admin_id,'parent_status'=>'parent'],['$set'=>['pause_by' => '','parent_pause'=>'']]);
                $count = $count + 1;
            }  
        }
        echo $count;
    }
    public function cron_to_reset_cost_avg_parent_status_pause_kraken(){
        $this->cost_avg_parents_pause_by_status_revert('kraken');   
    }
    public function cron_to_reset_cost_avg_parent_status_pause_binance(){
        $this->cost_avg_parents_pause_by_status_revert('binance');   
    }
    public function set_mirror_balance_binance(){
        // $exchangeCollectionBinance = "users";
        $exchangeCollectionBinance = "user_investment_binance";

        $db = $this->mongo_db->customQuery();
        $endMongoTime = $this->mongo_db->converToMongodttime(date('Y-m-d',strtotime('-1 day')));
        $pipeline2 = [
                        ['$match'=>['username'=>['$ne'=>''], 'is_api_key_valid' => 'yes', 'account_block' => 'no', '$or'=>[['last_time_balance_mirrored'=>['$lte'=>$endMongoTime]],['last_time_balance_mirrored'=>['$exists'=>false]]]
                        ]],
                        ['$limit'=>5]
                    ];
        $result = $db->$exchangeCollectionBinance->aggregate($pipeline2);
        $result_data = iterator_to_array($result);
        if(count($result_data) > 0){
            foreach($result_data as $value){
                $user_id = (string)$value['admin_id'];
                $this->manage_coins_mirror_cron($user_id,'binance');
                $db->$exchangeCollectionBinance->updateOne(['admin_id'=>$user_id],['$set'=>['last_time_balance_mirrored'=>$this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))]]);
            }
        }
    }
    public function set_mirror_balance_kraken(){
        // $exchangeCollectionKraken = "kraken_credentials";
        $exchangeCollectionKraken = "user_investment_kraken";
        $db = $this->mongo_db->customQuery();
        $endMongoTime = $this->mongo_db->converToMongodttime(date('Y-m-d',strtotime('-1 day')));
        $pipeline2 = [
                        ['$match'=>['username'=>['$ne'=>''],'is_api_key_valid' => 'yes', 'account_block' => 'no','$or'=>[['last_time_balance_mirrored'=>['$lte'=>$endMongoTime]],['last_time_balance_mirrored'=>['$exists'=>false]]]
                                ]
                        ],
                        ['$limit'=>5]
                    ];
        $result = $db->$exchangeCollectionKraken->aggregate($pipeline2);
        $result_data = iterator_to_array($result);
        if(count($result_data) > 0){
            foreach($result_data as $value){
                $user_id = (string)$value['admin_id'];
                $this->manage_coins_mirror_cron($user_id,'kraken');
                $db->$exchangeCollectionKraken->updateOne(['admin_id'=>$user_id],['$set'=>['last_time_balance_mirrored'=>$this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))]]);
            }
        }
    }    
    //manage_coins_post
    public function manage_coins_mirror_cron($user_id = '', $exchange = '',$span = ''){
        // echo $user_id. "     " . $exchange; exit;
        if($user_id == '' && $exchange == ''){
        }else{
            if(1 == 1){
                // echo "here"; exit;
                $isUserValid = 1;
                if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){
                    $user_id = $user_id;
                    $exchange = $exchange;
                    $application_mode = (!empty($request['application_mode'])?$request['application_mode'] : 'live');
                    $coins = $this->Mod_api_calls->get_all_user_coins($user_id, $exchange);
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
                        $BTCUSDT_price = $pricesArr['BTCUSDT'];
                        foreach ($coins as $coin) {
                            $coin_name = $coin['coin_name'];
                            $symbol = $coin['symbol'];
                            $response_coin_id = (string)$coin['_id'];
                            $coin_id = $coin->_id;
                            $balance = $this->Mod_api_calls->get_coin_balance($user_id, $symbol, $exchange);
                            $amount = null;
                            $amount_status = null;
                            if ($symbol != "BTC") {
                                $price = $pricesArr[$symbol];
                                $open_trades = $this->Mod_api_calls->get_open_trades($user_id, $symbol, $exchange, $application_mode);
        
                                
                                $open_trades_count = count($open_trades);
                                $costAvg_trades = $this->Mod_api_calls->get_costAvg_trades($user_id, $symbol, $exchange, $application_mode);
                                $costAvg_trades_count = count($costAvg_trades);
                                $open_trades = array_merge($open_trades, $costAvg_trades);

                                unset($costAvg_trades);
                                $change = $this->Mod_api_calls->get_24_hour_price_change($symbol);
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
                                        // if(empty($tradee['quantity_all'])){
                                            foreach($tradee['cost_avg_array'] as $value_cost_avg_array){
                                                if($value_cost_avg_array['order_sold'] != 'yes'){
                                                    $market_price += $BTCUSDT_price * $value_cost_avg_array['filledQtyBuy'] * $value_cost_avg_array['filledPriceBuy']; 
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
                    
                        foreach ($market as $key => $value){
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
                        }
                        $data['coin_market'] = $market;
                    }
                    if(!empty($data)){ // enter the coin page coins data along with userid in database kraken and binance separate collection will be used just to be sure they not update same user in same collection for different exchange.
                        $modified_date = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')); 
                        $database_array['user_id'] = $user_id; 
                        $database_array['created_date'] = $modified_date; 
                        $database_array['exchange'] = $exchange; 
                        $database_array['coin_balance_data'] = $data['coin_market'];
                        $collection_mirror = '';
                        if($exchange == 'binance'){
                            $collection_mirror = 'manage_coin_mirror';
                            $collection_investment = 'user_investment_binance';
                        }else{
                            $collection_investment = 'user_investment_kraken';
                            $collection_mirror = 'manage_coin_mirror_kraken';
                        }
                        $db = $this->mongo_db->customQuery();
                        $get_users_investment = $db->$collection_investment->find(['admin_id'=>$user_id]);
                        $array_user_data = iterator_to_array($get_users_investment);
                        if(count($array_user_data) > 0){
                            $total_acc_worth = (float)$array_user_data[0]['totalAccountWorth_manual'] + (float)$array_user_data[0]['actual_deposit'];
                            $database_array['account_worth'] = (float)$total_acc_worth; // worth history saving for future use in investment report etc.
                        } 
                        // exit;
                        // echo "<pre>"; print_r($database_array); exit;
                        if($span == 'daily'){
                            return $database_array;     
                            // echo "<pre>database  array :: "; print_r($database_array); exit;
                        }else{
                            $db->$collection_mirror->insertOne($database_array);
                        }
                    }
                }
            }
        }
    }//end manage_coin_mirror
   

      //get_coin_image 
    public function get_coin_image_new($coin, $exchange){
        if($coin == 'BNB'){
            return SURL . "assets/coin_logo/thumbs/BNB.png";
        }
        if($coin == 'USDT'){
            $symbol_search_arr = ['BTCUSDT', $coin, $coin.'BTC', $coin.'USDT'];
        }else{
            $symbol_search_arr = [$coin, $coin.'BTC', $coin.'USDT'];
        }
        $where_arr['symbol']['$in'] = $symbol_search_arr;
        $this->mongo_db->where($where_arr);
        $this->mongo_db->limit(1);
        $this->mongo_db->sort('_id',-1);
        $coin = $this->mongo_db->get('coins');
        $coin = iterator_to_array($coin);
        if (!empty($coin)) {
            $coin = $coin[0];
        }else{
            $coin = array();
        }
        if(empty($coin)){
            return '';
        }
        return SURL . "assets/coin_logo/thumbs/" . $coin['coin_logo'];
    }//end get_coin_image
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
    public function set_users_atg_trade_size_binance(){
        $this->get_user_set_limit('binance');
    }
    public function set_users_atg_trade_size_kraken(){
        $this->get_user_set_limit('kraken');
    }
    public function set_users_atg_trade_size_digie(){
        $this->get_user_set_limit('dg');
    }
    // public function get_user_set_limit($exchange = '',$user_id = ''){
    //     // echo $exchange; exit;
    //     if($exchange == 'binance'){
    //         $user_collection = 'users';
    //         $order_collection = 'buy_orders';
    //         $atg_collection = 'auto_trade_settings';
    //     }elseif ($exchange == 'dg') {
    //         $user_collection = 'dg_credentials';
    //         $order_collection = 'buy_orders_dg';
    //         $atg_collection = 'auto_trade_settings_dg';
    //     }elseif ($exchange == 'okex') {
    //         $user_collection = 'okex_credentials';
    //         $order_collection = 'buy_orders_okex';
    //         $atg_collection = 'auto_trade_settings_okex';
    //     }else{
    //         $user_collection = 'kraken_credentials';
    //         $order_collection = 'buy_orders_kraken';
    //         $atg_collection = 'auto_trade_settings_kraken';
    //     }
    //     $db = $this->mongo_db->customQuery();
    //     if($user_id != ''){
    //         if($exchange == 'binance'){
    //              $pipeline = [
    //                 ['$match'=>['_id'=>$this->mongo_db->mongoId($user_id),'is_api_key_valid'=>'yes','account_block'=>['$ne'=>'yes'],'set_parent_worth_script'=>['$ne'=>1]]],
    //                 ['$limit'=>1]
    //             ];
    //         }else{
    //              $pipeline = [
    //                 ['$match'=>['user_id'=>$user_id,'is_api_key_valid'=>'yes','account_block'=>['$ne'=>'yes'],'set_parent_worth_script'=>['$ne'=>1]]],
    //                 ['$limit'=>1]
    //             ];
    //         }
               
    //     }else{
    //         $pipeline = [
    //                 ['$match'=>['is_api_key_valid'=>'yes','account_block'=>['$ne'=>'yes'],'set_parent_worth_script'=>['$ne'=>1]]],
    //                 ['$limit'=>1]
    //         ];
    //     }
        
    //     $user_object = $db->$user_collection->aggregate($pipeline);
    //     $user_array = iterator_to_array($user_object); // getting user for setting trade size atg
    //     // echo '<pre>';print_r($user_array);exit;
    //     if(count($user_array) > 0){ // if query get any user then
    //         $user_id_mongo = ($exchange == 'binance')?(string)$user_array[0]['_id']:(string)$user_array[0]['user_id'];
    //         $atg_object = $db->$atg_collection->find(['user_id'=>$user_id_mongo,'application_mode'=>'live']);
    //         $atg_array = iterator_to_array($atg_object); // getting atg settings of a user
    //         $user_coins = $atg_array[0]['step_2']['coins'];
    //         $user_allocated_balance_btc = convert_btc_to_usdt($atg_array[0]['step_4']['allocatedBTC']); // allocated // usdt
    //         $user_allocated_balance_usdt = $atg_array[0]['step_4']['allocatedUSDT']; // allocated usdt
            
    //         echo '<pre>';print_r($user_allocated_balance_usdt);
    //         // getting remaining btc and usdt allocated
    //         echo "<br>";
    //         echo "user balance";
    //         //  exit;
    //         if ($exchange == 'dg') {
    //             $balance_info = $this->get_user_balance_info($user_id_mongo,'binance');
    //         }else{
    //             $balance_info = $this->get_user_balance_info($user_id_mongo,$exchange);
    //         }
        
    //         $user_atg_setting = $atg_array[0]['step_4']['dailTradeAbleBalancePercentage']; // e.g defensive normal etc etc
    //         //$allowed_coins_btc = 0;
           
    //         if($exchange == 'binance'){
    //             $match_user = ['_id'=>$this->mongo_db->mongoId($user_id_mongo)];
    //         }else{
    //             $match_user = ['user_id'=>$user_id_mongo];
    //         }
          
    //         if($exchange == 'kraken' || $exchange == 'dg' || $exchange == 'okex'){
    //             $user_data_main = $db->users->find(['_id'=>$this->mongo_db->mongoId($user_id_mongo)]);
    //             $user_data_main_arr = iterator_to_array($user_data_main);
    //             $package = isset($user_data_main_arr[0]['signup_package_selected'])?$user_data_main_arr[0]['signup_package_selected']:'';
    //         }else{
    //             $package = isset($user_array[0]['signup_package_selected'])?$user_array[0]['signup_package_selected']:'';
    //         }
            
    //         //    echo $package; exit;
    //         if(count($user_coins) > 0){
    //             foreach ($user_coins as $coins_value) { // foreach loop
    //                 $no_of_coins = 0;
    //                 $coin_category = substr($coins_value,-3); // for checking if the coin is BTC or USDT
    //                 // echo '<pre>CATE ::'; print_r($coin_category);
    //                 if ($exchange == 'dg') {
    //                     $price_of_coin = get_current_market_prices('binance',[$coins_value]);
    //                 }else{
    //                     $price_of_coin = get_current_market_prices($exchange,[$coins_value]);
    //                 }
    //                 // echo '<pre>price_of_coin :: '; print_r($price_of_coin); 
    //                 if($package == '' || $package == 'D1'){
    //                     $no_of_coins = 7;
    //                 }else if($package == 'S1'){
    //                     $no_of_coins = 13;
    //                 }else if($package == 'W1'){
    //                     if($exchange == 'binance' || $exchange == 'dg'){
    //                         if($coin_category == 'BTC'){
    //                             $no_of_coins = 22;
    //                         }else{
    //                             $no_of_coins = 12;
    //                         }
    //                     }else{
    //                        if($coin_category == 'BTC'){
    //                             $no_of_coins = 17;
    //                         }else{
    //                             $no_of_coins = 8;
    //                         }
    //                     }
    //                 }
                
    //                 if($exchange == 'binance' || $exchange == 'dg'){
    //                     $min_notation = 0.0001; // we have hardcoded binance min notation in our database that's why i uses this.
    //                     if($coin_category == 'SDT'){
    //                         $min_notation = 10;
    //                     }

    //                     // echo '<pre>'; print_r($price_of_coin[$coins_value]); exit;
    //                     $extraQtyVal = (40 * $min_notation) / 100;
    //                     $minReqQty = ($min_notation) + ($extraQtyVal);
    //                     $calculatedMinNotation = ($minReqQty/$price_of_coin[$coins_value]);
    //                     $calculatedMinNotation = $calculatedMinNotation + $min_notation_arr['stepSize'];
    //                     echo '<pre>calculatedMinNotation :: '; print_r($calculatedMinNotation);
    //                     if($coin_category == 'BTC'){
    //                         $min_notation = $calculatedMinNotation * $price_of_coin[$coins_value];
    //                         $min_notation_worth = convert_btc_to_usdt($min_notation);
    //                     }elseif($coin_category == 'BNB') {
    //                         $min_notation = $calculatedMinNotation * $price_of_coin[$coins_value];
    //                         $min_notation_worth = number_format(convert_bnb_to_usdt($min_notation), 8);
    //                         // echo '<pre>min_notation_worth:: '; print_r($min_notation_worth); exit;
    //                         // echo '<pre>'; print_r($price_of_coin); exit;
    //                     }else{
    //                         $min_notation = $calculatedMinNotation;
    //                         $min_notation_worth = $calculatedMinNotation * $price_of_coin[$coins_value];
    //                     }
    //                 }else{
    //                     // exit;
    //                     $min_notation_obj=$db->market_min_notation_kraken->find(['symbol'=>$coins_value]);
    //                     $min_notation_arr = iterator_to_array($min_notation_obj);
    //                     $min_notation = $min_notation_arr[0]['min_notation'];
    //                     $minnotation_issue = $min_notation;
    //                     //$min_notation = $min_notation * $price_of_coin[$coins_value];
    //                     $extraQtyVal = (40 * $minnotation_issue) / 100;
    //                     $calculatedMinNotation = ((float)$minnotation_issue + (float)$extraQtyVal);
    //                     $calculatedMinNotation = $calculatedMinNotation + $min_notation_arr['stepSize'];
    //                     $min_notation = $calculatedMinNotation * $price_of_coin[$coins_value];
    //                     if($coin_category == 'BTC'){
    //                         $min_notation = $calculatedMinNotation * $price_of_coin[$coins_value];
    //                         $min_notation_worth = convert_btc_to_usdt($min_notation);
    //                     }elseif ($coin_category == 'BNB') {
    //                         $min_notation = $calculatedMinNotation * $price_of_coin[$coins_value];
    //                         $min_notation_worth = convert_bnb_to_usdt($min_notation);
                            
    //                     }else{
    //                         $min_notation = $calculatedMinNotation;
    //                         $min_notation_worth = $calculatedMinNotation * $price_of_coin[$coins_value];
    //                     }
    //                 }
    //                 if($coin_category == 'BTC'){
                        
    //                     $parent_worth_allocated =  ($user_allocated_balance_btc > 10)?($user_allocated_balance_btc/$no_of_coins)/$user_atg_setting:0;
    //                     $final_coin_price = convert_btc_to_usdt($price_of_coin[$coins_value]);
                        
    //                 }elseif ($coin_category == 'BNB') {
    //                     $parent_worth_allocated =  ($user_allocated_balance_btc > 10)?($user_allocated_balance_btc/$no_of_coins)/$user_atg_setting:0;
    //                     $final_coin_price = convert_bnb_to_usdt($price_of_coin[$coins_value]);
    //                     echo '<pre>$parent_worth_allocated :: '; print_r($parent_worth_allocated); 
    //                     echo '<pre>$final_coin_price :: '; print_r($final_coin_price); 
    //                     echo '<pre>no of coins :: '; print_r($no_of_coins); 

    //                 }else{
                        
    //                     $parent_worth_allocated =  ($user_allocated_balance_usdt > 10)?($user_allocated_balance_usdt/$no_of_coins)/$user_atg_setting:0;
    //                     $final_coin_price = $price_of_coin[$coins_value];
                      
    //                 }
    //                 $trade_size = 20; // by default value 
    //                 $order_buy_type = '';
    //                 if($min_notation_worth > 20){
    //                     $trade_size = $min_notation_worth;
    //                 }
    //                 if($trade_size < $parent_worth_allocated){
    //                     $trade_size = $parent_worth_allocated;
    //                 }
    //                 if($trade_size >= 50){
    //                     $order_buy_type = 'double';
    //                 }else{
    //                     $order_buy_type = 'single';
    //                 }
                   
    //                 $quantity_to_update = $trade_size/$final_coin_price;
    //                 // echo '<pre>parent_worth_allocated :: '; print_r($parent_worth_allocated); 
    //                 // echo '<pre>min_notation_worth ::'; print_r($min_notation_worth);
    //                 // echo '<pre>trade_size:: '; print_r($trade_size);
    //                 // echo '<pre>final_coin_price :: '; print_r($final_coin_price);
    //                 // echo '<pre>Qty to update :: '; print_r($quantity_to_update);

    //                 //  exit;
    //                 // exit;
                    
    //                 $current_date = date('Y-m-d H:i:s');
    //                 $get_order_data_obj = $db->$order_collection->find(['symbol'=>$coins_value,'admin_id'=>$user_id_mongo,'parent_status'=>'parent','application_mode'=>'live','status'=>['$ne'=>'canceled']]);
    //                 $order_worth_get = iterator_to_array($get_order_data_obj);
    //                 if(count($order_worth_get) > 0){
    //                     $db->$order_collection->updateMany(['symbol'=>$coins_value,'admin_id'=>$user_id_mongo,'parent_status'=>'parent','status'=>['$ne'=>'canceled'],'application_mode'=>'live'],['$set'=>['quantity'=>(float)$quantity_to_update,'usd_worth'=>$trade_size,'modified_date'=>$this->mongo_db->converToMongodttime($current_date),'worth_set_by_sheraz_script'=>1,'order_buy_type'=>$order_buy_type,'before_quantity'=>$order_worth_get[0]['quantity'],'before_worth'=>$order_worth_get[0]['usd_worth']]]);
    //                     $db->$order_collection->updateMany(['symbol'=>$coins_value,'admin_id'=>$user_id_mongo,'parent_status'=>['$ne'=>'parent'],'status'=>['$ne'=>'canceled'],'application_mode'=>'live','cavg_parent'=>'yes'],['$set'=>['order_buy_type'=>$order_buy_type,]]);

    //                 }else{
    //                     $db->$order_collection->updateMany(['symbol'=>$coins_value,'admin_id'=>$user_id_mongo,'parent_status'=>'parent','status'=>['$ne'=>'canceled'],'application_mode'=>'live'],['$set'=>['quantity'=>(float)$quantity_to_update,'order_buy_type'=>$order_buy_type,'usd_worth'=>$trade_size,'modified_date'=>$this->mongo_db->converToMongodttime($current_date),'worth_set_by_sheraz_script'=>1]]);
    //                     $db->$order_collection->updateMany(['symbol'=>$coins_value,'admin_id'=>$user_id_mongo,'parent_status'=>['$ne'=>'parent'],'status'=>['$ne'=>'canceled'],'application_mode'=>'live','cavg_parent'=>'yes'],['$set'=>['order_buy_type'=>$order_buy_type,]]);    
    //                 }
                    
    //             } // end foreach
    //             // exit;
    //             $db->$user_collection->updateOne($match_user,['$set'=>['set_parent_worth_script'=>1]]);
    //         }else{
    //             $db->$user_collection->updateOne($match_user,['$set'=>['set_parent_worth_script'=>1]]);
    //         } // end if of the coin array count check
    //     }else{ // else refresh the users of that exchange 
    //         $db->$user_collection->updateMany(['set_parent_worth_script'=>1],['$set'=>['set_parent_worth_script'=>0]]);
    //     } // end of if and else
    // }   // end of the function  

    public function get_user_set_limit($exchange = '',$user_id = ''){

         //=====================DEBUG========================
         echo '<pre>===================================== NEW USER ============================================ '; print_r($user_id);
         //==================================================

        if($exchange == 'binance'){
            $user_collection = 'users';
            $order_collection = 'buy_orders';
            $atg_collection = 'auto_trade_settings';
        }elseif ($exchange == 'dg') {
            $user_collection = 'dg_credentials';
            $order_collection = 'buy_orders_dg';
            $atg_collection = 'auto_trade_settings_dg';
        }elseif ($exchange == 'okex') {
            $user_collection = 'okex_credentials';
            $order_collection = 'buy_orders_okex';
            $atg_collection = 'auto_trade_settings_okex';
        }else{
            $user_collection = 'kraken_credentials';
            $order_collection = 'buy_orders_kraken';
            $atg_collection = 'auto_trade_settings_kraken';
        }
        $db = $this->mongo_db->customQuery();
        if($user_id != ''){
            if($exchange == 'binance'){
                 $pipeline = [
                    ['$match'=>['_id'=>$this->mongo_db->mongoId($user_id),'is_api_key_valid'=>'yes','account_block'=>['$ne'=>'yes'],'set_parent_worth_script'=>['$ne'=>1]]],
                    ['$limit'=>1]
                ];
            }else{
                 $pipeline = [
                    ['$match'=>['user_id'=>$user_id,'is_api_key_valid'=>'yes','account_block'=>['$ne'=>'yes'],'set_parent_worth_script'=>['$ne'=>1]]],
                    ['$limit'=>1]
                ];
            }
               
        }else{
            $pipeline = [
                    ['$match'=>['is_api_key_valid'=>'yes','account_block'=>['$ne'=>'yes'],'set_parent_worth_script'=>['$ne'=>1]]],
                    ['$limit'=>1]
            ];
        }
        
        $user_object = $db->$user_collection->aggregate($pipeline);
        $user_array = iterator_to_array($user_object); 
       
        if(count($user_array) > 0){ 

            $user_id_mongo = ($exchange == 'binance')?(string)$user_array[0]['_id']:(string)$user_array[0]['user_id'];
            $atg_object = $db->$atg_collection->find(['user_id'=>$user_id_mongo,'application_mode'=>'live']);
            $atg_array = iterator_to_array($atg_object); 
         
            $get_all_main_parents = $db->$order_collection->find(['admin_id'=>$user_id_mongo,'parent_status'=>'parent','application_mode'=>'live','status'=>['$ne'=>'canceled']]);
            $get_all_main_parents_arr = iterator_to_array($get_all_main_parents);
            $user_coins = array_column($get_all_main_parents_arr, 'symbol');
        
            $user_allocated_balance_btc = convert_btc_to_usdt($atg_array[0]['step_4']['allocatedBTC']); 
            $user_allocated_balance_usdt = $atg_array[0]['step_4']['allocatedUSDT']; 
            
            //=====================COMMENT========================
            //     checking selected coins BTC/USDT count
            //====================================================

            $btc_symbol = 'BTC';
            $usdt_symbol = 'SDT';
        
            $btc_selected_coins_count = $db->$order_collection->count(
                [
                    '$and'=>[
                        ['symbol'=>new MongoDB\BSON\Regex(".*{$btc_symbol}.*", 'i')],
                        ['symbol'=>['$ne'=>'BTCUSDT']]
                    ],
                    'admin_id'=>$user_id_mongo,
                    'parent_status'=>'parent',
                    'application_mode'=>'live',
                    'status'=>['$ne'=>'canceled']
                ]
            );
    
            $usdt_selected_coins_count = $db->$order_collection->count(
                [
                    'symbol'=>new MongoDB\BSON\Regex(".*{$usdt_symbol}.*", 'i'),
                    'admin_id'=>$user_id_mongo,
                    'parent_status'=>'parent',
                    'application_mode'=>'live',
                    'status'=>['$ne'=>'canceled']
                ]
            );

            //=====================DEBUG========================
            echo '<pre> USER ALLOCATED BALANCE BTC :: '; print_r($user_allocated_balance_btc);
            //==================================================

            //=====================DEBUG========================
            echo '<pre> USER ALLOCATED BALANCE USDT :: '; print_r($user_allocated_balance_usdt);
            //==================================================

            if ($exchange == 'dg') {
                $balance_info = $this->get_user_balance_info($user_id_mongo,'binance');
            }else{
                $balance_info = $this->get_user_balance_info($user_id_mongo,$exchange);
            }

            $user_atg_setting = $atg_array[0]['step_4']['dailTradeAbleBalancePercentage']; 
            $allowed_coins_btc = 0;
            $allowed_coins_usdt = 0;
           
            if($exchange == 'binance'){
                $match_user = ['_id'=>$this->mongo_db->mongoId($user_id_mongo)];
            }else{
                $match_user = ['user_id'=>$user_id_mongo];
            }
            
            if($exchange == 'kraken' || $exchange == 'dg' || $exchange == 'okex'){
                $user_data_main = $db->users->find(['_id'=>$this->mongo_db->mongoId($user_id_mongo)]);
                $user_data_main_arr = iterator_to_array($user_data_main);
                $package = isset($user_data_main_arr[0]['signup_package_selected'])?$user_data_main_arr[0]['signup_package_selected']:'';
            }else{
                $package = isset($user_array[0]['signup_package_selected'])?$user_array[0]['signup_package_selected']:'';
            }
            
            if(count($user_coins) > 0){
                foreach ($user_coins as $coins_value) { 
                    $no_of_coins = 0;
                    $coin_category = substr($coins_value,-3); 
                    if ($exchange == 'dg') {
                        $price_of_coin = get_current_market_prices('binance',[$coins_value]);
                    }else{
                        $price_of_coin = get_current_market_prices($exchange,[$coins_value]);
                    }

                    if($package == '' || $package == 'D1'){
                        if((int)$user_allocated_balance_btc > 0 && (int)$user_allocated_balance_btc <= 1000){ 
                            $allowed_coins_btc = 3;
                        }elseif((int)$user_allocated_balance_btc > 1000 && (int)$user_allocated_balance_btc <= 2000){ 
                            if((int)$btc_selected_coins_count < 4){ 
                                $allowed_coins_btc = 3;
                            }else {
                                $allowed_coins_btc = 4;
                            }

                        }elseif((int)$user_allocated_balance_btc > 2000 && (int)$user_allocated_balance_btc <= 5000){ 
                            if((int)$btc_selected_coins_count > 3 && (int)$btc_selected_coins_count <= 6 ){ 
                                $allowed_coins_btc = $btc_selected_coins_count;
                            }elseif ((int)$btc_selected_coins_count > 6) {
                                $allowed_coins_btc = 6;
                            }elseif ((int)$btc_selected_coins_count < 3) {
                                $allowed_coins_btc = 3;
                            }
                        }elseif((int)$user_allocated_balance_btc > 5000 && (int)$user_allocated_balance_btc <= 10000){ 
                            if((int)$btc_selected_coins_count > 3 && (int)$btc_selected_coins_count <= 6 ){ 
                                $allowed_coins_btc = $btc_selected_coins_count;
                            }elseif ((int)$btc_selected_coins_count > 6) {
                                $allowed_coins_btc = 6;
                            }elseif ((int)$btc_selected_coins_count < 3) {
                                $allowed_coins_btc = 3;
                            }
                        }elseif((int)$user_allocated_balance_btc > 10000 ){ 
                            if((int)$btc_selected_coins_count > 3 && (int)$btc_selected_coins_count <= 6 ){ 
                                $allowed_coins_btc = $btc_selected_coins_count;
                            }elseif ((int)$btc_selected_coins_count > 6) {
                                $allowed_coins_btc = 6;
                            }elseif ((int)$btc_selected_coins_count < 3) {
                                $allowed_coins_btc = 3;
                            }
                        }

                        //Dolphin USDT coins trade size
                        if((int)$user_allocated_balance_usdt > 0 && (int)$user_allocated_balance_usdt <= 1000){ 
                            $allowed_coins_usdt = 3;
                        }elseif((int)$user_allocated_balance_usdt > 1000 && (int)$user_allocated_balance_usdt <= 2000){ 
                            if((int)$usdt_selected_coins_count < 4){ 
                                $allowed_coins_usdt = 3;
                            }else {
                                $allowed_coins_usdt = 4;
                            }
                        }elseif((int)$user_allocated_balance_usdt > 2000 && (int)$user_allocated_balance_usdt <= 5000){ 
                            if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 6 ){ 
                                $allowed_coins_usdt = $usdt_selected_coins_count;
                            }elseif ((int)$usdt_selected_coins_count > 6) {
                                $allowed_coins_usdt = 6;
                            }elseif ((int)$usdt_selected_coins_count < 3) {
                                $allowed_coins_usdt = 3;
                            }

                        }elseif((int)$user_allocated_balance_usdt > 5000 && (int)$user_allocated_balance_usdt <= 10000){ 
                            if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 6 ){ 
                                $allowed_coins_usdt = $usdt_selected_coins_count;
                            }elseif ((int)$usdt_selected_coins_count > 6) {
                                $allowed_coins_usdt = 6;
                            }elseif ((int)$usdt_selected_coins_count < 3) {
                                $allowed_coins_usdt = 3;
                            }

                        }elseif((int)$user_allocated_balance_usdt > 10000 ){ 
                            if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 6 ){ 
                                $allowed_coins_usdt = $usdt_selected_coins_count;
                            }elseif ((int)$usdt_selected_coins_count > 6) {
                                $allowed_coins_usdt = 6;
                            }elseif ((int)$usdt_selected_coins_count < 3) {
                                $allowed_coins_usdt = 3;
                            }

                        }

                    }
                    else if($package == 'S1'){
                        if((int)$user_allocated_balance_btc > 0 && (int)$user_allocated_balance_btc <= 1000){ 
                            $allowed_coins_btc = 3;
                        }elseif((int)$user_allocated_balance_btc > 1000 && (int)$user_allocated_balance_btc <= 2000){ 
                            $allowed_coins_btc = 4;
                        }elseif((int)$user_allocated_balance_btc > 2000 && (int)$user_allocated_balance_btc <= 5000){ 
                            if((int)$btc_selected_coins_count > 3 && (int)$btc_selected_coins_count <= 6 ){ 
                                $allowed_coins_btc = $btc_selected_coins_count;
                            }elseif ((int)$btc_selected_coins_count > 6) {
                                $allowed_coins_btc = 6;
                            }elseif ((int)$btc_selected_coins_count < 3) {
                                $allowed_coins_btc = 3;
                            }
                        }elseif((int)$user_allocated_balance_btc > 5000 && (int)$user_allocated_balance_btc <= 10000){ 
                            if((int)$btc_selected_coins_count > 3 && (int)$btc_selected_coins_count <= 8 ){ 
                                $allowed_coins_btc = $btc_selected_coins_count;
                            }elseif ((int)$btc_selected_coins_count > 8) {
                                $allowed_coins_btc = 8;
                            }elseif ((int)$btc_selected_coins_count < 3) {
                                $allowed_coins_btc = 3;
                            }

                        }elseif((int)$user_allocated_balance_btc > 10000 ){ 
                            if((int)$btc_selected_coins_count > 3 && (int)$btc_selected_coins_count <= 10 ){ 
                                $allowed_coins_btc = $btc_selected_coins_count;
                            }elseif ((int)$btc_selected_coins_count > 10) {
                                $allowed_coins_btc = 10;
                            }elseif ((int)$btc_selected_coins_count < 3) {
                                $allowed_coins_btc = 3;
                            }
                        }

                        if($exchange == 'binance' || $exchange == 'dg'){
                            if((int)$user_allocated_balance_usdt > 0 && (int)$user_allocated_balance_usdt <= 1000){ 
                                $allowed_coins_usdt = 3;
                            }elseif((int)$user_allocated_balance_usdt > 1000 && (int)$user_allocated_balance_usdt <= 2000){ 
                                $allowed_coins_usdt = 4;
                            }elseif((int)$user_allocated_balance_usdt > 2000 && (int)$user_allocated_balance_usdt <= 5000){ 
                                if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 6 ){ 
                                    $allowed_coins_usdt = $usdt_selected_coins_count;
                                }elseif ((int)$usdt_selected_coins_count > 6) {
                                    $allowed_coins_usdt = 6;
                                }elseif ((int)$usdt_selected_coins_count < 3) {
                                    $allowed_coins_usdt = 3;
                                }
                            }elseif((int)$user_allocated_balance_usdt > 5000 && (int)$user_allocated_balance_usdt <= 10000){ 
                                if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 8 ){ 
                                    $allowed_coins_usdt = $usdt_selected_coins_count;
                                }elseif ((int)$usdt_selected_coins_count > 8) {
                                    $allowed_coins_usdt = 8;
                                }elseif ((int)$usdt_selected_coins_count < 3) {
                                    $allowed_coins_usdt = 3;
                                }
                            }elseif((int)$user_allocated_balance_usdt > 10000 ){ 
                                if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 10 ){ 
                                    $allowed_coins_usdt = $usdt_selected_coins_count;
                                }elseif ((int)$usdt_selected_coins_count > 10) {
                                    $allowed_coins_usdt = 10;
                                }elseif ((int)$usdt_selected_coins_count < 3) {
                                    $allowed_coins_usdt = 3;
                                }
                            }
                        }else{
                            if((int)$user_allocated_balance_usdt > 0 && (int)$user_allocated_balance_usdt <= 1000){ 
                                $allowed_coins_usdt = 3;
                            }elseif((int)$user_allocated_balance_usdt > 1000 && (int)$user_allocated_balance_usdt <= 2000){ 
                                $allowed_coins_usdt = 4;
                            }elseif((int)$user_allocated_balance_usdt > 2000 && (int)$user_allocated_balance_usdt <= 5000){ 
                                if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 6 ){ 
                                    $allowed_coins_usdt = $usdt_selected_coins_count;
                                }elseif ((int)$usdt_selected_coins_count > 6) {
                                    $allowed_coins_usdt = 6;
                                }elseif ((int)$usdt_selected_coins_count < 3) {
                                    $allowed_coins_usdt = 3;
                                }

                            }elseif((int)$user_allocated_balance_usdt > 5000 && (int)$user_allocated_balance_usdt <= 10000){ 
                                if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 8 ){ 
                                    $allowed_coins_usdt = $usdt_selected_coins_count;
                                }elseif ((int)$usdt_selected_coins_count > 8) {
                                    $allowed_coins_usdt = 8;
                                }elseif ((int)$usdt_selected_coins_count < 3) {
                                    $allowed_coins_usdt = 3;
                                }
                            }elseif((int)$user_allocated_balance_usdt > 10000 ){ 
                                if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 8 ){ 
                                    $allowed_coins_usdt = $usdt_selected_coins_count;
                                }elseif ((int)$usdt_selected_coins_count > 8) {
                                    $allowed_coins_usdt = 8;
                                }elseif ((int)$usdt_selected_coins_count < 3) {
                                    $allowed_coins_usdt = 3;
                                }
                            }
                        }

                    }
                    else if($package == 'W1'){
                        if($exchange == 'binance' || $exchange == 'dg'){
                            if($coin_category == 'BTC'){
                                if((int)$user_allocated_balance_btc > 0 && (int)$user_allocated_balance_btc <= 1000){ 
                                    $allowed_coins_btc = 3;
                                }elseif((int)$user_allocated_balance_btc > 1000 && (int)$user_allocated_balance_btc <= 2000){ 
                                    $allowed_coins_btc = 4;
                                }elseif((int)$user_allocated_balance_btc > 2000 && (int)$user_allocated_balance_btc <= 5000){ 
                                    if((int)$btc_selected_coins_count > 3 && (int)$btc_selected_coins_count <= 6 ){ 
                                        $allowed_coins_btc = $btc_selected_coins_count;
                                    }elseif ((int)$btc_selected_coins_count > 6) {
                                        $allowed_coins_btc = 6;
                                    }elseif ((int)$btc_selected_coins_count < 3) {
                                        $allowed_coins_btc = 3;
                                    }
                                }elseif((int)$user_allocated_balance_btc > 5000 && (int)$user_allocated_balance_btc <= 10000){ 
                                    if((int)$btc_selected_coins_count > 3 && (int)$btc_selected_coins_count <= 8 ){ 
                                        $allowed_coins_btc = $btc_selected_coins_count;
                                    }elseif ((int)$btc_selected_coins_count > 8) {
                                        $allowed_coins_btc = 8;
                                    }elseif ((int)$btc_selected_coins_count < 3) {
                                        $allowed_coins_btc = 3;
                                    }
                                }elseif((int)$user_allocated_balance_btc > 10000 ){ 
                                    if((int)$btc_selected_coins_count > 3 && (int)$btc_selected_coins_count <= 13 ){ 
                                        $allowed_coins_btc = $btc_selected_coins_count;
                                    }elseif ((int)$btc_selected_coins_count > 13) {
                                        $allowed_coins_btc = 13;
                                    }elseif ((int)$btc_selected_coins_count < 3) {
                                        $allowed_coins_btc = 3;
                                    }
                                }
                            
                            }else{
                                if((int)$user_allocated_balance_usdt > 0 && (int)$user_allocated_balance_usdt <= 1000){ 
                                    $allowed_coins_usdt = 3;
                                }elseif((int)$user_allocated_balance_usdt > 1000 && (int)$user_allocated_balance_usdt <= 2000){ 
                                    $allowed_coins_usdt = 4;
                                }elseif((int)$user_allocated_balance_usdt > 2000 && (int)$user_allocated_balance_usdt <= 5000){ 
                                    if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 6 ){ 
                                        $allowed_coins_usdt = $usdt_selected_coins_count;
                                    }elseif ((int)$usdt_selected_coins_count > 6) {
                                        $allowed_coins_usdt = 6;
                                    }elseif ((int)$usdt_selected_coins_count < 3) {
                                        $allowed_coins_usdt = 3;
                                    }
                                }elseif((int)$user_allocated_balance_usdt > 5000 && (int)$user_allocated_balance_usdt <= 10000){ 
                                    if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 8 ){ 
                                        $allowed_coins_usdt = $usdt_selected_coins_count;
                                    }elseif ((int)$usdt_selected_coins_count > 8) {
                                        $allowed_coins_usdt = 8;
                                    }elseif ((int)$usdt_selected_coins_count < 3) {
                                        $allowed_coins_usdt = 3;
                                    }
                                }elseif((int)$user_allocated_balance_usdt > 10000 ){ 
                                    if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 13 ){ 
                                        $allowed_coins_usdt = $usdt_selected_coins_count;
                                    }elseif ((int)$usdt_selected_coins_count > 13) {
                                        $allowed_coins_usdt = 13;
                                    }elseif ((int)$usdt_selected_coins_count < 3) {
                                        $allowed_coins_usdt = 3;
                                    }
                                }
                            }
                        }else{
                           if($coin_category == 'BTC'){
                                if((int)$user_allocated_balance_btc > 0 && (int)$user_allocated_balance_btc <= 1000){ 
                                    $allowed_coins_btc = 3;
                                }elseif((int)$user_allocated_balance_btc > 1000 && (int)$user_allocated_balance_btc <= 2000){ 
                                    $allowed_coins_btc = 4;
                                }elseif((int)$user_allocated_balance_btc > 2000 && (int)$user_allocated_balance_btc <= 5000){
                                    if((int)$btc_selected_coins_count > 3 && (int)$btc_selected_coins_count <= 6 ){ 
                                        $allowed_coins_btc = $btc_selected_coins_count;
                                    }elseif ((int)$btc_selected_coins_count > 6) {
                                        $allowed_coins_btc = 6;
                                    }elseif ((int)$btc_selected_coins_count < 3) {
                                        $allowed_coins_btc = 3;
                                    }
                                }elseif((int)$user_allocated_balance_btc > 5000 && (int)$user_allocated_balance_btc <= 10000){ 
                                    if((int)$btc_selected_coins_count > 3 && (int)$btc_selected_coins_count <= 8 ){ 
                                        $allowed_coins_btc = $btc_selected_coins_count;
                                    }elseif ((int)$btc_selected_coins_count > 8) {
                                        $allowed_coins_btc = 8;
                                    }elseif ((int)$btc_selected_coins_count < 3) {
                                        $allowed_coins_btc = 3;
                                    }
                                }elseif((int)$user_allocated_balance_btc > 10000 ){ 
                                    if((int)$btc_selected_coins_count > 3 && (int)$btc_selected_coins_count <= 13 ){ 
                                        $allowed_coins_btc = $btc_selected_coins_count;
                                    }elseif ((int)$btc_selected_coins_count > 13) {
                                        $allowed_coins_btc = 13;
                                    }elseif ((int)$btc_selected_coins_count < 3) {
                                        $allowed_coins_btc = 3;
                                    }
                                }
                            }else{
                                if((int)$user_allocated_balance_usdt > 0 && (int)$user_allocated_balance_usdt <= 1000){ 
                                    $allowed_coins_usdt = 3;
                                }elseif((int)$user_allocated_balance_usdt > 1000 && (int)$user_allocated_balance_usdt <= 2000){ 
                                    $allowed_coins_usdt = 4;
                                }elseif((int)$user_allocated_balance_usdt > 2000 && (int)$user_allocated_balance_usdt <= 5000){ 
                                    if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 6 ){ 
                                        $allowed_coins_usdt = $usdt_selected_coins_count;
                                    }elseif ((int)$usdt_selected_coins_count > 6) {
                                        $allowed_coins_usdt = 6;
                                    }elseif ((int)$usdt_selected_coins_count < 3) {
                                        $allowed_coins_usdt = 3;
                                    }
                                }elseif((int)$user_allocated_balance_usdt > 5000 && (int)$user_allocated_balance_usdt <= 10000){ 
                                    if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 8 ){ 
                                        $allowed_coins_usdt = $usdt_selected_coins_count;
                                    }elseif ((int)$usdt_selected_coins_count > 8) {
                                        $allowed_coins_usdt = 8;
                                    }elseif ((int)$usdt_selected_coins_count < 3) {
                                        $allowed_coins_usdt = 3;
                                    }
                                }elseif((int)$user_allocated_balance_usdt > 10000 ){ 
                                    if((int)$usdt_selected_coins_count > 3 && (int)$usdt_selected_coins_count <= 13 ){ 
                                        $allowed_coins_usdt = $usdt_selected_coins_count;
                                    }elseif ((int)$usdt_selected_coins_count > 13) {
                                        $allowed_coins_usdt = 13;
                                    }elseif ((int)$usdt_selected_coins_count < 3) {
                                        $allowed_coins_usdt = 3;
                                    }
                                }
                            }
                        }
                    }
                   
                    if($exchange == 'binance' || $exchange == 'dg'){
                        $min_notation = 0.0001; // we have hardcoded binance min notation in our database that's why i uses this.
                        if($coin_category == 'SDT'){
                            $min_notation = 10;
                        }
                        $extraQtyVal = (40 * $min_notation) / 100;
                        $minReqQty = ($min_notation) + ($extraQtyVal);
                        $calculatedMinNotation = ($minReqQty/$price_of_coin[$coins_value]);
                        $calculatedMinNotation = $calculatedMinNotation + $min_notation_arr['stepSize'];
                        if($coin_category == 'BTC'){
                            $min_notation = $calculatedMinNotation * $price_of_coin[$coins_value];
                            $min_notation_worth = convert_btc_to_usdt($min_notation);
                        }else{
                            $min_notation = $calculatedMinNotation;
                            $min_notation_worth = $calculatedMinNotation * $price_of_coin[$coins_value];
                        }
                    }else{
                       
                        $min_notation_obj=$db->market_min_notation_kraken->find(['symbol'=>$coins_value]);
                        $min_notation_arr = iterator_to_array($min_notation_obj);
                        $min_notation = $min_notation_arr[0]['min_notation'];
                        $minnotation_issue = $min_notation;
                       
                        $extraQtyVal = (40 * $minnotation_issue) / 100;
                        $calculatedMinNotation = ((float)$minnotation_issue + (float)$extraQtyVal);
                        $calculatedMinNotation = $calculatedMinNotation + $min_notation_arr['stepSize'];
                        $min_notation = $calculatedMinNotation * $price_of_coin[$coins_value];
                        if($coin_category == 'BTC'){
                            $min_notation = $calculatedMinNotation * $price_of_coin[$coins_value];
                            $min_notation_worth = convert_btc_to_usdt($min_notation);
                        }else{
                            $min_notation = $calculatedMinNotation;
                            $min_notation_worth = $calculatedMinNotation * $price_of_coin[$coins_value];
                        }
                    }
                    if($coin_category == 'BTC'){
                        $parent_worth_allocated =  ($user_allocated_balance_btc > 10)?($user_allocated_balance_btc/$allowed_coins_btc)/$user_atg_setting:0;
                        $final_coin_price = convert_btc_to_usdt($price_of_coin[$coins_value]);
                        $remaining_balance = $user_allocated_balance_btc - convert_btc_to_usdt($balance_info['used_btc_balance']); 
          
                        
                    }else{
                        
                        $parent_worth_allocated =  ($user_allocated_balance_usdt > 10)?($user_allocated_balance_usdt/$allowed_coins_usdt)/$user_atg_setting:0;
                        $final_coin_price = $price_of_coin[$coins_value];
                        //calculating remaining allocated balance
                        $remaining_balance = $user_allocated_balance_usdt - $balance_info['used_usdt_balance']; 
                    }

                    $trade_size = 20; 
                    $order_buy_type = '';
                    if($min_notation_worth > 20){
                        $trade_size = $min_notation_worth;
                    }
                    if($trade_size < $parent_worth_allocated){
                        $trade_size = $parent_worth_allocated;
                    }
                    if ($trade_size > $remaining_balance) {
                        $trade_size = 20; 
                    }
                    if($trade_size >= 50){
                        $order_buy_type = 'double';
                    }else{
                        $order_buy_type = 'single';
                    }
                    $quantity_to_update = $trade_size/$final_coin_price;

                    // echo '<pre>parent_worth_allocated :: '; print_r($parent_worth_allocated);
                    // echo '<pre>user_atg_setting :: '; print_r($user_atg_setting);
                    // echo '<pre>allowed_coins_btc :: '; print_r($allowed_coins_btc);
                    // echo '<pre>allowed_coins_usdt :: '; print_r($allowed_coins_usdt);
                    // echo '<pre>remaining_balance :: '; print_r($remaining_balance);
                    // echo '<pre>TRade size :: '; print_r($trade_size);
                    // echo '<pre>Quantity to update :: '; print_r($quantity_to_update); exit;
                    $current_date = date('Y-m-d H:i:s');
                    $get_order_data_obj = $db->$order_collection->find(['symbol'=>$coins_value,'admin_id'=>$user_id_mongo,'parent_status'=>'parent','application_mode'=>'live','status'=>['$ne'=>'canceled']]);
                    $order_worth_get = iterator_to_array($get_order_data_obj);
                    if(count($order_worth_get) > 0){
                        $db->$order_collection->updateMany(['symbol'=>$coins_value,'admin_id'=>$user_id_mongo,'parent_status'=>'parent','status'=>['$ne'=>'canceled'],'application_mode'=>'live'],['$set'=>['quantity'=>(float)$quantity_to_update,'usd_worth'=>$trade_size,'modified_date'=>$this->mongo_db->converToMongodttime($current_date),'worth_set_by_sheraz_script'=>1,'order_buy_type'=>$order_buy_type,'before_quantity'=>$order_worth_get[0]['quantity'],'before_worth'=>$order_worth_get[0]['usd_worth']]]);
                        $db->$order_collection->updateMany(['symbol'=>$coins_value,'admin_id'=>$user_id_mongo,'parent_status'=>['$ne'=>'parent'],'status'=>['$ne'=>'canceled'],'application_mode'=>'live','cavg_parent'=>'yes'],['$set'=>['order_buy_type'=>$order_buy_type,]]);

                    }else{
                        $db->$order_collection->updateMany(['symbol'=>$coins_value,'admin_id'=>$user_id_mongo,'parent_status'=>'parent','status'=>['$ne'=>'canceled'],'application_mode'=>'live'],['$set'=>['quantity'=>(float)$quantity_to_update,'order_buy_type'=>$order_buy_type,'usd_worth'=>$trade_size,'modified_date'=>$this->mongo_db->converToMongodttime($current_date),'worth_set_by_sheraz_script'=>1]]);
                        $db->$order_collection->updateMany(['symbol'=>$coins_value,'admin_id'=>$user_id_mongo,'parent_status'=>['$ne'=>'parent'],'status'=>['$ne'=>'canceled'],'application_mode'=>'live','cavg_parent'=>'yes'],['$set'=>['order_buy_type'=>$order_buy_type,]]);    
                    }
                } 
                $db->$user_collection->updateOne($match_user,['$set'=>['set_parent_worth_script'=>1]]);
            }else{
                $db->$user_collection->updateOne($match_user,['$set'=>['set_parent_worth_script'=>1]]);
            } 
        }else{ 
            $db->$user_collection->updateMany(['set_parent_worth_script'=>1],['$set'=>['set_parent_worth_script'=>0]]);
        } 
    }  

    public function calculate_accumulation_weekly($user_id = '',$exchange = ''){
        //$user_id = '6045a381e157a73baf553786';
        //$exchange = 'binance';
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
        // echo '<pre>weekly arr :: '; print_r($weekly_arr); exit;
        // $collection_order = ($exchange == 'binance')?'sold_buy_orders':'sold_buy_orders_kraken';
        // $collection_store = ($exchange == 'binance')?'accumulation_weekly_binance':'accumulation_weekly_kraken';
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

        //$user_collection = ($exchange == 'binance')?'users':'kraken_credentials';
        $db = $this->mongo_db->customQuery();
        //$new_reverse_array = array_reverse($weekly_arr);
        $new_reverse_array = $weekly_arr;
        //echo '<pre>';print_r($new_reverse_array);exit;
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

            // echo "<pre>usdt "; print_r($usdt_acc); 
            // echo "<pre>manual "; print_r($usdt_manual); 
            // echo "<pre>auto "; print_r($usdt_auto); 
            // echo "<br>============================";
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
            
            //array_push($final_array,$update_arr);
        }
        $db->$collection_store->updateOne(['user_id'=>$user_id],['$set'=>['weekly_accumulations'=>$update_arr]],['upsert'=>true]);
        $db->users->updateOne(['_id'=>$this->mongo_db->mongoId($user_id)],['$set'=>['sheraz_weekly'=>1]]);
        //echo '<pre>';print_r($update_arr);
    }
    public function insert_accumulation_weekly(){
        $db = $this->mongo_db->customQuery();
        $pipeline = [
            ['$match'=>['sheraz_weekly'=>['$ne'=>1],'username'=>['$ne'=>''], 'is_api_key_valid'=>'yes', 'account_block'=>'no']],
            ['$project'=>['_id'=>1]],
            ['$limit'=>10]
        ];
        $user_obj = $db->users->aggregate($pipeline);
        $user_arr = iterator_to_array($user_obj);
        if(count($user_arr) > 0){
            foreach ($user_arr as $value) {
                $user_id = (string)$value['_id'];
                $this->calculate_accumulation_weekly($user_id,'binance');
                $this->calculate_accumulation_weekly($user_id,'dg');    
                sleep(2);   
            }           
        }else{
            $db->users->updateMany(['sheraz_weekly'=>1],['$set'=>['sheraz_weekly'=>0]]);
        }

        //for kraken
        $pipeline2 = [
            ['$match'=>['sheraz_weekly'=>['$ne'=>1],'user_id'=>['$ne'=>''], 'is_api_key_valid'=>'yes', 'account_block'=>'no']],
            ['$project'=>['user_id'=>1]],
            ['$limit'=>10]
        ];
        $user_obj_kraken = $db->kraken_credentials->aggregate($pipeline2);
        $user_arr_kraken = iterator_to_array($user_obj_kraken);
        if(count($user_arr_kraken) > 0){
            foreach ($user_arr_kraken as $value) {
                $user_id = $value['user_id'];
                $this->calculate_accumulation_weekly($user_id,'kraken');    
                sleep(2);   
            }           
        }else{
            $db->users->updateMany(['sheraz_weekly'=>1],['$set'=>['sheraz_weekly'=>0]]);
        }
    }
    // public function insert_accumulation_monthly(){
    //     $db = $this->mongo_db->customQuery();
    //     $pipeline = [
    //         ['$match'=>['sheraz_monthly'=>['$ne'=>1],'username'=>['$ne'=>'']]],
    //         ['$project'=>['_id'=>1]],
    //         ['$limit'=>10]
    //     ];
    //     $user_obj = $db->users->aggregate($pipeline);
    //     $user_arr = iterator_to_array($user_obj);
    //     if(count($user_arr) > 0){
    //         foreach ($user_arr as $value) {
    //             $user_id = (string)$value['_id'];
    //             $this->calculate_accumulation_monthly($user_id,'binance');
    //             $this->calculate_accumulation_monthly($user_id,'kraken');    
    //             $this->calculate_accumulation_monthly($user_id,'dg');    
    //             sleep(2);   
    //         }           
    //     }
    // }
    public function insert_accumulation_monthly(){
        $db = $this->mongo_db->customQuery();
        $pipeline = [
            ['$match'=>['sheraz_monthly'=>['$ne'=>1],'username'=>['$ne'=>''], 'is_api_key_valid'=>'yes', 'account_block'=>'no']],
            ['$project'=>['_id'=>1]],
            ['$limit'=>10]
        ];
        $user_obj = $db->users->aggregate($pipeline);
        $user_arr = iterator_to_array($user_obj);
        if(count($user_arr) > 0){
            foreach ($user_arr as $value) {
                $user_id = (string)$value['_id'];
                $this->calculate_accumulation_monthly($user_id,'binance');
                $this->calculate_accumulation_monthly($user_id,'dg');    
                sleep(2);   
            }           
        }

        //for kraken
        $pipeline2 = [
            ['$match'=>['sheraz_monthly'=>['$ne'=>1],'user_id'=>['$ne'=>''], 'is_api_key_valid'=>'yes', 'account_block'=>'no']],
            ['$project'=>['user_id'=>1]],
            ['$limit'=>10]
        ];
        $user_obj_kraken = $db->kraken_credentials->aggregate($pipeline2);
        $user_arr_kraken = iterator_to_array($user_obj_kraken);
        if(count($user_arr_kraken) > 0){
            foreach ($user_arr_kraken as $value) {
                $user_id = $value['user_id'];
                $this->calculate_accumulation_monthly($user_id,'kraken');    
                sleep(2);   
            }           
        }
    }
    public function reset_monthly_acc_check(){
        $db = $this->mongo_db->customQuery();
        $db->users->updateMany(['sheraz_monthly'=>['$eq'=>1],'username'=>['$ne'=>'']],['$set'=>['sheraz_monthly'=>0]]);
    }
    public function calculate_accumulation_monthly($user_id = '',$exchange = ''){
        // $user_id = '5eb5a5a628914a45246bacc6';
        // $exchange = 'binance';
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


        // $collection_order = ($exchange == 'binance')?'sold_buy_orders':'sold_buy_orders_kraken';
        // $collection_store = ($exchange == 'binance')?'accumulation_monthly_binance':'accumulation_monthly_kraken';
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
                if(!empty($_COOKIE['hassam'])) {
                    echo "<pre> in if ";
                    print_r($end_mongo_date);
                    echo "<br>";
                    exit;
                }
            }else{
               
                $end_mongo_date = $this->mongo_db->converToMongodttime($new_reverse_array[$key+1]);    
                if(!empty($_COOKIE['hassam'])) {
                    echo "<pre> in else ";
                    print_r($end_mongo_date);
                    echo "<br>";
                    exit;
                }
            }
            
            // if ($key === array_key_last($new_reverse_array)) {
            if ($key === count($new_reverse_array) - 1) {
                // echo $key;
                // echo '<pre>new_reverse_array :: '; print_r(array_key_last($new_reverse_array)); exit;
                $last_date = date('Y-m-d');
                $end_mongo_date = $this->mongo_db->converToMongodttime($last_date);

                // echo '<pre>start_mongo_date :: '; print_r($start_mongo_date); 
                echo '<pre>end mongo date :: '; print_r($end_mongo_date); 
            
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

        $db->$collection_store->updateOne(['user_id'=>$user_id],['$set'=>['monthly_accumulations'=>$update_arr]],['upsert'=>true]);
        $db->users->updateOne(['_id'=>$this->mongo_db->mongoId($user_id)],['$set'=>['sheraz_monthly'=>1]]);
        echo '<pre>';print_r($update_arr);
        // exit;
    } 

    public function set_daily_manage_coin_data_users_kraken($user_id=''){
        $db = $this->mongo_db->customQuery();
        $today = date('Y-m-d H:i:s');
        $today_date = $this->mongo_db->converToMongodttime($today);
        $time_check = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s', strtotime('-24 hours')));
        $two_months_ago_date = date('Y-m-d H:i:s', strtotime('-2 months'));
        $check_two_months_ago_date = $this->mongo_db->converToMongodttime($two_months_ago_date);

        $seven_days_ago_date = date('Y-m-d H:i:s', strtotime('-15 days'));
        $set_seven_days_ago_date = $this->mongo_db->converToMongodttime($seven_days_ago_date);
        if ($user_id != '') {
            $pipeline = [
                [
                    '$match'=> [
                        'user_id'=>['$ne'=>''],
                        'user_id' => (string) $user_id,
                        'account_block'=>['$ne'=>'yes'],
                        'is_api_key_valid'=> 'yes'
                    ]
                ],
                [
                    '$project'=> [
                        '_id'=>0,
                        'user_id'=>1,
                    ]
                ]
            ];
        }else{
            $pipeline = [
                [
                    '$match'=> [
                        'user_id'=>['$ne'=>''],
                        '$or'=>[['extra_less_backup_new'=>['$lte'=>$time_check]],['extra_less_backup_new'=>['$exists'=>false]]],
                        'account_block'=>['$ne'=>'yes'],
                        'is_api_key_valid'=> 'yes'
                    ]
                ],
                [
                    '$project'=> [
                        '_id'=>0,
                        'user_id'=>1,
                    ]
                ],
                [
                    '$limit' => 20
                ]
            ];
        }

        $users_obj = $db->kraken_credentials->aggregate($pipeline);
        $users_arr = iterator_to_array($users_obj);
        if(count($users_arr) > 0){
            foreach ($users_arr as $row_user){
                $data_coin_balance = $this->manage_coins_mirror_cron($row_user['user_id'],'kraken','daily');
             
                $user_obj = $db->users->find(['_id'=>$this->mongo_db->mongoId($row_user['user_id'])]);
                $user_arr = iterator_to_array($user_obj);
                $username = $user_arr[0]['username']; 
                $mirror_cron_data = $data_coin_balance['coin_balance_data'];
                foreach($mirror_cron_data as $data){

                    if ($data['symbol'] != 'BTC') {
                       
                        $check_cons_balance_change = checkPreviousRecordsIfSameFromThreeDays($row_user['user_id'], 'kraken', $data['symbol']);
                        
                        if (!empty($check_cons_balance_change)) {

                            $checkExistsInPreviousRecord = $db->extra_less_actual_data->find([
                                'user_id' => $row_user['user_id'],
                                'symbol' => $data['symbol'],
                                'exchange' => 'kraken'
                              
                            ]); 

                            $previous_record = iterator_to_array($checkExistsInPreviousRecord);

                            if (empty($previous_record)) {

                                $filter = [
                                    'user_id' => $row_user['user_id'],
                                    'symbol' => $data['symbol'],
                                    'exchange' => 'kraken',
                                ];
                        
                                $options = [
                                    'sort' => ['created_date' => -1],
                                    'limit' => 1, 
                                ];
                        
                                $results = $db->extra_less_backup_coinwise->find($filter, $options);
                                $response = iterator_to_array($results);

                                if (!empty($response)) {
                                    $newDocument = $response[0];
                                    $db->extra_less_actual_data->insertOne($newDocument);
                                }

                            }

                            if (count($previous_record) > 0 && $previous_record[0]['quantity'] != $check_cons_balance_change['last_three_quantities'][0]) {
                                $previousRecWorth = getMarketWothByQuantity($data['symbol'], $previous_record[0]['quantity'], 'kraken');
                                $consumedBalanceChangeWorth = getMarketWothByQuantity($data['symbol'], $check_cons_balance_change['last_three_quantities'][0], 'kraken');

                                $worthDifference = abs($previousRecWorth - $consumedBalanceChangeWorth);
                                if ($worthDifference >= 1) {
                                    $today = date('Y-m-d H:i:s');
                                    $today_date = $this->mongo_db->converToMongodttime($today);
                                    $collection = $db->extra_less_actual_data; 

                                    $filterCoin = [
                                        'user_id' => $row_user['user_id'],
                                        'symbol' => $data['symbol'],
                                        'exchange' => 'kraken',
                                    ];
                            
                                    $options = [
                                        'sort' => ['created_date' => -1],
                                        'limit' => 1, 
                                    ];

                                    $resultCoinExtraLess = $collection->findOne($filterCoin, $options);
                                    $resultCoinExtraLessArr = iterator_to_array($resultCoinExtraLess);

                                    if ($resultCoinExtraLessArr['quantity'] != $check_cons_balance_change['last_three_quantities'][0]) {
                                        $results = $db->extra_less_backup_coinwise->find($filterCoin, $options);
                                        $response = iterator_to_array($results);
        
                                        if (!empty($response)) {
                                            $newDocument = $response[0];
                                            $db->extra_less_actual_data->insertOne($newDocument);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $db->kraken_credentials->updateOne(['user_id'=>$row_user['user_id']],['$set'=>['extra_less_backup_new'=>$today_date]]); 
                $db->extra_less_actual_data->deleteMany(['exchange'=>'kraken','user_id'=>$row_user['user_id'], 'diff_date' => ['$lt' => $check_two_months_ago_date]]);
            } // end for each loop main 
        } // end count if 


    }
    public function set_daily_manage_coin_backup_users_kraken($user_id=''){
        $db = $this->mongo_db->customQuery();
        $today = date('Y-m-d H:i:s');
        $today_date = $this->mongo_db->converToMongodttime($today);
        $two_months_ago_date = date('Y-m-d H:i:s', strtotime('-2 months'));
        $check_two_months_ago_date = $this->mongo_db->converToMongodttime($two_months_ago_date);
      
        $time_check = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s', strtotime('-24 hours')));
        if ($user_id != '') {
            $pipeline = [
                [
                    '$match'=> [
                        'user_id'=>['$ne'=>''],
                        'user_id' => (string) $user_id,
                        'account_block'=>['$ne'=>'yes'],
                        'is_api_key_valid'=> 'yes'
                    ]
                ],
                [
                    '$project'=> [
                        '_id'=>0,
                        'user_id'=>1,
                    ]
                ]
            ];
        }else{
            $pipeline = [
                [
                    '$match'=> [
                        'user_id'=>['$ne'=>''],
                        '$or'=>[['extra_less_backup'=>['$lte'=>$time_check]],['extra_less_backup'=>['$exists'=>false]]],
                        'account_block'=>['$ne'=>'yes'],
                        'is_api_key_valid'=> 'yes'
                    ]
                ],
                [
                    '$project'=> [
                        '_id'=>0,
                        'user_id'=>1,
                    ]
                ],
                [
                    '$limit'=>20
                ]
            ];
        }
        
        $users_obj = $db->kraken_credentials->aggregate($pipeline);
        $users_arr = iterator_to_array($users_obj);
        // echo "<pre>"; print_r($users_arr); exit;
        if(count($users_arr) > 0){
            foreach ($users_arr as $row_user){
                $data_coin_balance = $this->manage_coins_mirror_cron($row_user['user_id'],'kraken','daily');
                if ($user_id != '') {
                    //for testing purpose
                    echo "<pre>"; print_r($data_coin_balance); exit;
                }
                 
                $user_obj = $db->users->find(['_id'=>$this->mongo_db->mongoId($row_user['user_id'])]);
                $user_arr = iterator_to_array($user_obj);
                $username = $user_arr[0]['username']; 
                foreach ($data_coin_balance['coin_balance_data'] as $key => $value){
           
                    if (isset($value['required_balance']) && $value['required_balance'] != 0) {
                        $yesterdayQTYBalance = yesterdayQTYExtraLessBalance($row_user['user_id'], 'kraken', $value['symbol'], $value['required_balance']);
                    }

                    if (isset($value['extra_balance']) && $value['extra_balance'] != 0) {
                        $yesterdayQTYBalance = yesterdayQTYExtraLessBalance($row_user['user_id'], 'kraken', $value['symbol'], $value['extra_balance']);
                    }
                    

                    if (isset($value['extra_balance_usd_worth']) && $value['extra_balance_usd_worth'] > 0 && !empty($yesterdayQTYBalance['extra']) && !empty($value['base_currency_comitted_balance_usd_worth'] > 0)) {

                       
                        $realValueExtra = (float) str_replace(',', '', $value['extra_balance_usd_worth']);
                        // Update today's extra_balance_usd_worth
                        $decimalPlaces = 6; 
                        $difference = round((float) $value['extra_balance'] - $yesterdayQTYBalance['value'], $decimalPlaces);
                        // $formattedDifference = number_format($difference, 6);

                        // Check if today's quantity is different than yesterday's quantity
                        if ( (float) $value['extra_balance'] != (float)$yesterdayQTYBalance['value']) {
                            $diff_date = $today_date;
                        } else {
                            $diff_date = null;
                            // Fetch yesterday's date from the collection
                            $yesterdayRecord = $db->extra_less_backup_coinwise->findOne([
                                'user_id' => $row_user['user_id'],
                                'symbol' => $value['symbol'],
                                'exchange' => 'kraken'
                            
                            ], ['sort' => ['created_date' => -1]]); 
                            if ($yesterdayRecord) {
                                $diff_date = $yesterdayRecord['created_date']; 
                            }
                        }

                        $newDocument = [
                            'symbol' => $value['symbol'],
                            'exchange' => 'kraken',
                            'user_id' => $row_user['user_id'],
                            'coin_logo' => $value['coin_logo'],
                            'extra_balance_usdt_worth' => (float)$realValueExtra,
                            'username' => $username,
                            'balance_error' => $value['balance_error'],
                            'quantity' => number_format($value['extra_balance'], 6),
                            'yesterday_qty' => number_format($yesterdayQTYBalance['value'], 6),
                            'created_date' => $today_date,
                            'difference' => (float)$difference,
                            'yesterday_extra' => $yesterdayQTYBalance['extra'],
                            'diff_date' => $diff_date,
                        ];
                        // if ($value['symbol'] != 'BTC') {
                        //     echo $user_arr[0]['username'];
                        //     echo "<br>";
                        //     echo "<pre>"; print_r($value);
                        //     echo "<br>";
                        //     echo "<pre>"; print_r($newDocument); exit;
                        // }
                    
                        $db->extra_less_backup_coinwise->insertOne($newDocument);

                    }

                    if (isset($value['required_balance_usd_worth']) && $value['required_balance_usd_worth'] > 0 && !empty($yesterdayQTYBalance['less']) && $value['base_currency_comitted_balance_usd_worth'] > 0) {

                        $realValueLess = (float) str_replace(',', '', $value['required_balance_usd_worth']);
                        $decimalPlaces = 6; // Number of decimal places you want to round to
                        $difference = round((float) $value['required_balance'] - $yesterdayQTYBalance['value'], $decimalPlaces);
                    
                        // Check if today's quantity is different than yesterday's quantity
                        if ( (float) $value['required_balance'] != (float)$yesterdayQTYBalance['value']) {
                            $diff_date = $today_date;
                        } else {
                            $diff_date = null;
                            // Fetch yesterday's date from the collection
                            $yesterdayRecord = $db->extra_less_backup_coinwise->findOne([
                                'user_id' => $row_user['user_id'],
                                'symbol' => $value['symbol'],
                                'exchange' => 'kraken'
                              
                            ], ['sort' => ['created_date' => -1]]); 
                            if ($yesterdayRecord) {
                                $diff_date = $yesterdayRecord['created_date']; 
                            }
                        }

                        $newDocument = [
                            'symbol' => $value['symbol'],
                            'user_id' => $row_user['user_id'],
                            'exchange' => 'kraken',
                            'coin_logo' => $value['coin_logo'],
                            'less_balance_usdt_worth' => (float)$realValueLess,
                            'username' => $username,
                            'balance_error' => $value['balance_error'],
                            'quantity' => number_format($value['required_balance'], 6),
                            'yesterday_qty' => number_format($yesterdayQTYBalance['value'], 6),
                            'created_date' => $today_date,
                            'difference' => (float)$difference,
                            'yesterday_less' => $yesterdayQTYBalance['less'],
                            'diff_date' => $diff_date,
                        ];
                    
                        // if ($value['symbol'] != 'BTC') {
                        //     echo $user_arr[0]['username'];
                        //     echo "<br>";
                        //     echo "<pre>"; print_r($value);
                        //     echo "<br>";
                        //     echo "<pre>"; print_r($newDocument); exit;
                        // }
                        $db->extra_less_backup_coinwise->insertOne($newDocument);

                    }
                  

                } // end foreach loop inner
                $db->kraken_credentials->updateOne(['user_id'=>$row_user['user_id']],['$set'=>['extra_less_backup'=>$today_date]]); 
                $db->extra_less_backup_coinwise->deleteMany(['exchange'=>'kraken','user_id'=>$row_user['user_id'], 'date' => ['$lt' => $check_two_months_ago_date]]); // 
            } // end for each loop main 
        } // end count if 
    }

    public function deleteRecordsContainingExtraLessBoth(){
        $db = $this->mongo_db->customQuery();
        $db->extra_less_backup_coinwise->deleteMany(['less_balance_usdt_worth' => ['$exists' => true ], 'extra_balance_usdt_worth' => ['$exists' => true ]]);
        
        echo "records deleted successfully";
        

    }

    public function set_daily_manage_coin_data_users_binance($username=''){
        $db = $this->mongo_db->customQuery();
        $today = date('Y-m-d H:i:s');
        $today_date = $this->mongo_db->converToMongodttime($today);
        $time_check = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s', strtotime('-24 hours')));
        $two_months_ago_date = date('Y-m-d H:i:s', strtotime('-2 months'));
        $check_two_months_ago_date = $this->mongo_db->converToMongodttime($two_months_ago_date);

        $seven_days_ago_date = date('Y-m-d H:i:s', strtotime('-15 days'));
        $set_seven_days_ago_date = $this->mongo_db->converToMongodttime($seven_days_ago_date);

        if ($username != '') {
            $pipeline = [
                [
                    '$match' => [
                        '_id' => ['$ne' => ''],
                        'username' => $username,
                        'account_block' => ['$ne' => 'yes'],
                        'is_api_key_valid'=> 'yes'
                    ]
                ],
                [
                    '$project' => [
                        '_id' => 1,
                        'username' => 1,
                    ]
                ]
            ];
        }else{
            $pipeline = [
                [
                    '$match' => [
                        '_id' => ['$ne' => ''],
                        '$or' => [['extra_less_backup_new' => ['$lte' => $time_check]], ['extra_less_backup_new' => ['$exists' => false]]],
                        'account_block' => ['$ne' => 'yes'],
                        'is_api_key_valid'=> 'yes'
                    ]
                ],
                [
                    '$project' => [
                        '_id' => 1,
                        'username' => 1,
                    ]
                ],
                [
                    '$limit' => 20
                ]
            ];
        }

        $users_obj = $db->users->aggregate($pipeline);
        $users_arr = iterator_to_array($users_obj);

        if(count($users_arr) > 0){
            foreach ($users_arr as $row_user){
                $data_coin_balance = $this->manage_coins_mirror_cron((string)$row_user['_id'],'binance','daily');
                if ($username != '') {
                    echo "<pre>"; print_r($data_coin_balance);
                }
            
                $user_obj = $db->users->find(['_id'=>$this->mongo_db->mongoId((string)$row_user['_id'])]);
                $user_arr = iterator_to_array($user_obj);
                $username = $user_arr[0]['username']; 
                $mirror_cron_data = $data_coin_balance['coin_balance_data'];
                foreach($mirror_cron_data as $data){

                    if ($data['symbol'] != 'BTC') {
                       
                        $check_cons_balance_change = checkPreviousRecordsIfSameFromThreeDays((string)$row_user['_id'], 'binance', $data['symbol']);
                        
                        if (!empty($check_cons_balance_change)) {

                            $checkExistsInPreviousRecord = $db->extra_less_actual_data->find([
                                'user_id' => (string)$row_user['_id'],
                                'symbol' => $data['symbol'],
                                'exchange' => 'binance'
                              
                            ]); 

                            $previous_record = iterator_to_array($checkExistsInPreviousRecord);

                            if (empty($previous_record)) {

                                $filter = [
                                    'user_id' => (string)$row_user['_id'],
                                    'symbol' => $data['symbol'],
                                    'exchange' => 'binance',
                                ];
                        
                                $options = [
                                    'sort' => ['created_date' => -1],
                                    'limit' => 1, 
                                ];
                        
                                $results = $db->extra_less_backup_coinwise->find($filter, $options);
                                $response = iterator_to_array($results);

                                if (!empty($response)) {
                                    $newDocument = $response[0];
                                    $db->extra_less_actual_data->insertOne($newDocument);
                                }

                            }

                            if (count($previous_record) > 0 && $previous_record[0]['quantity'] != $check_cons_balance_change['last_three_quantities'][0]) {
                                $previousRecWorth = getMarketWothByQuantity($data['symbol'], $previous_record[0]['quantity'], 'binance');
                                $consumedBalanceChangeWorth = getMarketWothByQuantity($data['symbol'], $check_cons_balance_change['last_three_quantities'][0], 'binance');

                                $worthDifference = abs($previousRecWorth - $consumedBalanceChangeWorth);
                                if ($worthDifference >= 1) {
                                    $today = date('Y-m-d H:i:s');
                                    $today_date = $this->mongo_db->converToMongodttime($today);
        
                                    $collection = $db->extra_less_actual_data; 

                                    $filterCoin = [
                                        'user_id' => (string)$row_user['_id'],
                                        'symbol' => $data['symbol'],
                                        'exchange' => 'binance',
                                    ];
                            
                                    $options = [
                                        'sort' => ['created_date' => -1],
                                        'limit' => 1, 
                                    ];

                                    $resultCoinExtraLess = $collection->findOne($filterCoin, $options);
                                    $resultCoinExtraLessArr = iterator_to_array($resultCoinExtraLess);

                                    if ($resultCoinExtraLessArr['quantity'] != $check_cons_balance_change['last_three_quantities'][0]) {
                                        $results = $db->extra_less_backup_coinwise->find($filterCoin, $options);
                                        $response = iterator_to_array($results);
        
                                        if (!empty($response)) {
                                            $newDocument = $response[0];
                                            $db->extra_less_actual_data->insertOne($newDocument);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $db->users->updateOne(['_id'=>$this->mongo_db->mongoId((string)$row_user['_id'])],['$set'=>['extra_less_backup_new'=>$today_date]]); 
                $db->extra_less_actual_data->deleteMany(['exchange'=>'binance','user_id'=>(string)$row_user['_id'], 'diff_date' => ['$lt' => $check_two_months_ago_date]]);
                   
                   
            } // end for each loop main 
        } // end count if 


    }

    public function set_daily_manage_coin_backup_users_binance($username=''){
        $db = $this->mongo_db->customQuery();
        $endMongoTime = $this->mongo_db->converToMongodttime(date('Y-m-d',strtotime('-1 day')));
        $today = date('Y-m-d H:i:s');
        $today_date = $this->mongo_db->converToMongodttime($today);
        $two_months_ago_date = date('Y-m-d H:i:s', strtotime('-2 months'));
        $check_two_months_ago_date = $this->mongo_db->converToMongodttime($two_months_ago_date);
      
        $time_check = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s', strtotime('-24 hours')));
        if ($username) {
            $pipeline = [
                [
                    '$match' => [
                        '_id' => ['$ne' => ''],
                        'username' => $username,
                        'account_block' => ['$ne' => 'yes'],
                        'is_api_key_valid'=> 'yes'
                    ]
                ],
                [
                    '$project' => [
                        '_id' => 1,
                        'username' => 1,
                    ]
                ]
            ];
        }else{
            $pipeline = [
                [
                    '$match' => [
                        '_id' => ['$ne' => ''],
                        '$or' => [['extra_less_backup' => ['$lte' => $time_check]], ['extra_less_backup' => ['$exists' => false]]],
                        'account_block' => ['$ne' => 'yes'],
                        'is_api_key_valid'=> 'yes'
                    ]
                ],
                [
                    '$project' => [
                        '_id' => 1,
                        'username' => 1,
                    ]
                ],
                [
                    '$limit' => 20
                ]
            ];
        }
        
        $users_obj = $db->users->aggregate($pipeline);
        $users_arr = iterator_to_array($users_obj);
        // echo "<pre>"; print_r( $users_arr); exit;
        if(count($users_arr) > 0){
            foreach ($users_arr as $row_user){
                // echo "<pre>"; print_r( (string) $row_user['_id']); exit;
                $data_coin_balance = $this->manage_coins_mirror_cron((string)$row_user['_id'],'binance','daily');
              
                echo '<pre>data coin balance :: '; print_r($data_coin_balance);
                $user_obj = $db->users->find(['_id'=>$this->mongo_db->mongoId($row_user['_id'])]);
                $user_arr = iterator_to_array($user_obj);
                $username = $user_arr[0]['username']; 
                foreach ($data_coin_balance['coin_balance_data'] as $key => $value){
               
                    // $yesterdayQTYBalance = yesterdayQTYExtraLessBalance((string)$row_user['_id'], 'binance', $value['symbol']);
                    if (isset($value['required_balance']) && $value['required_balance'] != 0) {
                        $yesterdayQTYBalance = yesterdayQTYExtraLessBalance((string)$row_user['_id'], 'binance', $value['symbol'], $value['required_balance']);
                    }

                    if (isset($value['extra_balance']) && $value['extra_balance'] != 0) {
                        $yesterdayQTYBalance = yesterdayQTYExtraLessBalance((string)$row_user['_id'], 'binance', $value['symbol'], $value['extra_balance']);
                    }

                    if (isset($value['extra_balance_usd_worth']) && $value['extra_balance_usd_worth'] > 0 && !empty($yesterdayQTYBalance['extra']) && $value['base_currency_comitted_balance_usd_worth'] > 0) {

                        // echo '<pre>symbol :: '; print_r($value['symbol']);
                        // echo '<pre>Extra balance :: '; print_r();
                        // echo '<pre>yesterdayQTYBalance :: '; print_r($yesterdayQTYBalance);
                        // echo '<pre> base_currency_comitted_balance_usd_worth :: '; print_r($base_currency_comitted_balance_usd_worth); exit;

                        // Update today's extra_balance_usd_worth
                        $realValueExtra = (float) str_replace(',', '', $value['extra_balance_usd_worth']);
                        $decimalPlaces = 6; // Number of decimal places you want to round to
                        $difference = round((float) $value['extra_balance'] - $yesterdayQTYBalance['value'], $decimalPlaces);
                    
                         // Check if today's quantity is different than yesterday's quantity
                        if ( (float) $value['extra_balance'] != (float)$yesterdayQTYBalance['value']) {
                            $diff_date = $today_date;
                        } else {
                            $diff_date = null;
                            // Fetch yesterday's date from the collection
                            $yesterdayRecord = $db->extra_less_backup_coinwise->findOne([
                                'user_id' => (string)$row_user['_id'],
                                'symbol' => $value['symbol'],
                                'exchange' => 'binance'
                              
                            ], ['sort' => ['diff_date' => -1]]); 
                            if ($yesterdayRecord) {
                                $diff_date = $yesterdayRecord['diff_date']; 
                            }
                        }
                        
                        $newDocument = [
                            'symbol' => $value['symbol'],
                            'exchange' => 'binance',
                            'user_id' => (string)$row_user['_id'],
                            'coin_logo' => $value['coin_logo'],
                            'extra_balance_usdt_worth' => (float)$realValueExtra,
                            'username' => $username,
                            'balance_error' => $value['balance_error'],
                            'quantity' => number_format($value['extra_balance'], 6),
                            'yesterday_qty' => number_format($yesterdayQTYBalance['value'], 6),
                            'created_date' => $today_date,
                            'difference' => (float)$difference,
                            'yesterday_extra' => $yesterdayQTYBalance['extra'],
                            'diff_date' => $diff_date,
                        ];
                        // echo "<pre>"; print_r($newDocument); exit;
                        $db->extra_less_backup_coinwise->insertOne($newDocument);
                    }
                    
                    if (isset($value['required_balance_usd_worth']) && $value['required_balance_usd_worth'] > 0 && !empty($yesterdayQTYBalance['less']) && $value['base_currency_comitted_balance_usd_worth'] > 0) {

                        // echo '<pre>symbol :: '; print_r($value['symbol']);
                        // echo '<pre>Less balance :: '; print_r();
                        // echo '<pre>yesterdayQTYBalance :: '; print_r($yesterdayQTYBalance);
                        // echo '<pre> base_currency_comitted_balance_usd_worth :: '; print_r($base_currency_comitted_balance_usd_worth); exit;
                        // Update today's required_balance_usd_worth
                        $realValueLess = (float) str_replace(',', '', $value['required_balance_usd_worth']);
                        $decimalPlaces = 6; // Number of decimal places you want to round to
                        $difference = round((float) $value['required_balance'] - $yesterdayQTYBalance['value'], $decimalPlaces);
                    
                        // Check if today's quantity is different than yesterday's quantity
                        if ( (float) $value['required_balance'] != (float)$yesterdayQTYBalance['value']) {
                            $diff_date = $today_date;
                        } else {
                            $diff_date = null;
                            // Fetch yesterday's date from the collection
                            $yesterdayRecord = $db->extra_less_backup_coinwise->findOne([
                                'user_id' => (string)$row_user['_id'],
                                'symbol' => $value['symbol'],
                                'exchange' => 'binance'
                              
                            ], ['sort' => ['diff_date' => -1]]); 
                            if ($yesterdayRecord) {
                                $diff_date = $yesterdayRecord['diff_date']; 
                            }
                        }

                        $newDocument = [
                            'symbol' => $value['symbol'],
                            'user_id' => (string)$row_user['_id'],
                            'exchange' => 'binance',
                            'coin_logo' => $value['coin_logo'],
                            'less_balance_usdt_worth' => (float)$realValueLess,
                            'username' => $username,
                            'balance_error' => $value['balance_error'],
                            'quantity' => number_format($value['required_balance'], 6),
                            'yesterday_qty' => number_format($yesterdayQTYBalance['value'], 6),
                            'created_date' => $today_date,
                            'difference' => (float)$difference,
                            'yesterday_less' => $yesterdayQTYBalance['less'],
                            'diff_date' => $diff_date,
                        ];
                    
                        $db->extra_less_backup_coinwise->insertOne($newDocument);
                    }

                } // end foreach loop inner
                $db->users->updateOne(['_id' => $this->mongo_db->mongoId($row_user['_id'])], ['$set' => ['extra_less_backup' => $today_date]]);
                $db->extra_less_backup_coinwise->deleteMany(['exchange'=>'binance', 'user_id'=>$row_user['_id'], 'date' => ['$lt' => $check_two_months_ago_date]]); // 
            } // end for each loop main 
        } // end count if 
    }
    public function set_trading_ip_for_binance(){
        $db = $this->mongo_db->customQuery();
        $pipeline = [
            ['$match'=>['is_api_key_valid'=>'yes','sheraz_binance_ip_updated'=>['$ne'=>1]]],
            ['$limit'=>1],
        ];
        $get_user = $db->users->aggregate($pipeline);
        $user_arr = iterator_to_array($get_user);
        if(count($user_arr) > 0){
            $trading_ip = $user_arr[0]['trading_ip'];
            $admin_id = (string)$user_arr[0]['_id'];
            $db->orders->updateMany(['admin_id'=>$admin_id,'trading_ip'=>['$ne'=>$trading_ip]],['$set'=>['trading_ip'=>$trading_ip]]);
            $db->sold_buy_orders->updateMany(['admin_id'=>$admin_id,'trading_ip'=>['$ne'=>$trading_ip]],['$set'=>['trading_ip'=>$trading_ip]]);
            $db->buy_orders->updateMany(['admin_id'=>$admin_id,'trading_ip'=>['$ne'=>$trading_ip]],['$set'=>['trading_ip'=>$trading_ip]]);
            $db->users->updateOne(['_id'=>$this->mongo_db->mongoId($admin_id)],['$set'=>['sheraz_binance_ip_updated'=>1]]);
        }
    }
    public function query_tester(){
        $db = $this->mongo_db->customQuery();
        $pipeline = [
                [
                  '$match'=> [
                    '$or'=>[['trading_status'=>'off'],['is_api_key_valid'=>'no']]
                  ]
                ],
                [
                  '$group'=> [
                    '_id' => null,
                    'user_ids' => ['$push'=>[ '$toString'=>'$$ROOT.user_id']],
                  ]
                ],
                [
                  '$project'=> [
                    'user_ids' => 1,
                  ]
                ],
            ];
        $result = $db->kraken_credentials->aggregate($pipeline);
        $result = iterator_to_array($result);
        echo '<pre>';print_r($result);
    }
    public function set_block_flag(){
        $db = $this->mongo_db->customQuery();
        $get_users_obj = $db->users->find(['country'=>'United States of America','is_api_key_valid'=>'yes','country_banned_check'=>['$ne'=>1]],['$limit'=>10]);
        $users_arr = iterator_to_array($get_users_obj);
        if(count($users_arr) > 0){
            foreach ($users_arr as $row_user) {
                $admin_id = (string)$row_user['_id'];
                $db->buy_orders->updateMany(['admin_id'=>$admin_id,'symbol'=>['$in'=>['XRPBTC','XRPUSDT']],'parent_status'=>['$ne'=>'parent']],['$set'=>['country_banned'=>1]]);
                $db->users->updateOne(['_id'=>$this->mongo_db->mongoId($admin_id)],['$set'=>['country_banned_check'=>1]]);
            }
        }else{
        $db->users->updateMany(['country_banned_check'=>1],['$set'=>['country_banned_check'=>0]]); 
        }
    }

    public function set_block_flag_kraken(){
        $db = $this->mongo_db->customQuery();
        $get_users_obj = $db->kraken_credentials->find(['country'=>'United States of America','is_api_key_valid'=>'yes','country_banned_check'=>['$ne'=>1]],['$limit'=>10]);
        $users_arr = iterator_to_array($get_users_obj);

        if(count($users_arr) > 0){
            foreach ($users_arr as $row_user) {
                $admin_id = (string)$row_user['user_id'];
                $db->buy_orders_kraken->updateMany(['admin_id'=>$admin_id,'symbol'=>['$in'=>['XRPBTC','XRPUSDT']],'parent_status'=>['$ne'=>'parent']],['$set'=>['country_banned'=>1]]);
                $db->kraken_credentials->updateOne(['user_id'=>$admin_id],['$set'=>['country_banned_check'=>1]]);
            }
        }else{
        $db->kraken_credentials->updateMany(['country_banned_check'=>1],['$set'=>['country_banned_check'=>0]]); 
        }
    }
  public function get_user_balance_info($user_id,$exchange){
        //print_r('hello i am in function');exit;
        //$POST = $this->input->post();
      $token = $this->Mod_jwt->custom_token_bearer($user_id);
      //echo '<pre>';print_r($token);exit;
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
  //making script to update the country in 
  public function updateCountryInKrakenCredentials() {
    $db = $this->mongo_db->customQuery();
    $updatedUsers = []; 
    $get_users_obj = $db->users->find(['country' => ['$ne' => '']]);
    $users_arr = iterator_to_array($get_users_obj);

    foreach ($users_arr as $row_user) {
        $admin_id = (string) $row_user['_id'];
        $user_country = $row_user['country'];
        $result = $db->kraken_credentials->updateOne(['user_id' => $admin_id], ['$set' => ['country' => $user_country]]);

        if ($result->getModifiedCount() > 0) {
            $updatedUsers[] = $admin_id; 
        }
    }

    echo "<pre>"; print_r( $updatedUsers); exit;
    }

    public function getTwentyFourHighPrice() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.binance.com/api/v3/ticker/24hr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
    
        $response = curl_exec($curl);
    
        if ($response === false) {
            $error = curl_errno($curl);
            $errorMessage = curl_strerror($error);
            echo "Curl error (Code $error): $errorMessage";
            curl_close($curl); 
            exit;
        }
    
        curl_close($curl);
        $returnRes = json_decode($response, true);
    
        $collection_name = '24hr_ticker_price_change_statistics';
        $db = $this->mongo_db->customQuery();
    
        foreach ($returnRes as $symbolData) {
            $symbol = $symbolData['symbol'];
    
            // Check if the symbol contains either "BTC" or "USDT"
            if (strpos($symbol, 'BTC') !== false || strpos($symbol, 'USDT') !== false) {
                $filter = ['symbol' => $symbol];
                $update = [
                    '$set' => [
                        'data' => $symbolData,
                        'last_updated' => new MongoDB\BSON\UTCDateTime(time() * 1000),
                    ],
                ];
    
                $result = $db->$collection_name->updateOne($filter, $update, ['upsert' => true]);
    
                if ($result->getModifiedCount() > 0 || $result->getUpsertedCount() > 0) {
                    echo "Data for $symbol updated successfully!<br>";
                } else {
                    echo "No changes made to the data for $symbol.<br>";
                }
            }
        }
    }

    public function insertNewCoinPairs() {
        $collection_name = 'coins';
        $user_id         = '63246dedaaca50474c28c9d2';
        $db = $this->mongo_db->customQuery();
    
        $coins_obj = $db->$collection_name->find(['user_id' => $user_id, 'exchange_type' => 'binance']);
        $coins_arr = iterator_to_array($coins_obj);
    
        foreach ($coins_arr as $coin) {
            $symbol = $coin['symbol'];
            if ($symbol != 'BTC') {
            
                $existing_pairs = $db->$collection_name->count(['user_id' => $user_id, 'symbol' => new MongoDB\BSON\Regex("^$symbol", 'i')]);
        
                if ($existing_pairs < 2) {
                    $base_currency = substr($symbol, 0, -3);

                    if (substr($symbol, -4) === 'USDT') {
                        $base_currency = substr($symbol, 0, -4);
                    }

                    $eth_pair = $base_currency . 'BNB';
                    echo "<br>";
                    echo '<pre> eth_pair :: '; print_r($eth_pair);
                    $pair_exists = $db->$collection_name->count(['user_id' => $user_id, 'symbol' => $eth_pair]);
        
                    if ($pair_exists == 0) {
                        $eth_pair_data = [
                            'user_id'       => $user_id,
                            'symbol'        => $eth_pair,
                            'coin_name'     => $coin['coin_name'] . '/BNB',
                            'coin_logo'     => $coin['coin_logo'],
                            'exchange_type' => 'binance',
                        ];
        
                        $db->$collection_name->insertOne($eth_pair_data);
                    }
                }
            }
        }
    }


    public function csvReportOfCommitedBalancesUsers($exchange){
        if ($exchange == 'binance') {
            $collectionCommitedBalances = 'commited_balances_users';
        }else{
            $collectionCommitedBalances = 'commited_balances_users_kraken';
        }

        $where = [
            '$expr' => [
                '$gt' => [
                    ['$toDouble' => ['$replaceOne' => ['input' => '$comitted_balance_usd_worth', 'find' => ',', 'replacement' => '']]],
                    0
                ]
            ]
        ];
        
        $balanceData = $this->mongo_db->customQuery()->$collectionCommitedBalances->find($where);
        
        $get_balance_data_array = [];
        foreach ($balanceData as $key => $document) {

            $is_ledger = checkCoinLedger($exchange, $document['admin_id'], $document['symbol']);

            $userData = getUserDetailsById($document['admin_id']);
            // echo '<pre> $userData :: '; print_r($userData); exit;
            $data['Sr'] = $key+1;
            $data['Exchange'] = $exchange;
            $data['Name']   = $userData[0]['first_name']. " " .$userData[0]['last_name'];
            $data['Username'] = $userData[0]['username'];
            $data['Coin'] = $document['symbol'];
            $data['Digie Commited Amount'] = $document['comitted_balance_usd_worth'];
            $data['Is Ledger'] =  $is_ledger;

            if ( $is_ledger == 'Yes') {
                array_push($get_balance_data_array, $data);
            }
            
        }
        $this->generate_csv_user_balances($get_balance_data_array);

    }

    public function generate_csv_user_balances($get_balance_data_array)
    {
        if ($get_balance_data_array) {
            $filename = ("Users Report " . date("Y-m-d Gis") . ".csv");
            // Set the Headers for csv
            $now = gmdate("D, d M Y H:i:s");

            header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
            header("Last-Modified: {$now} GMT");
            header('Content-Type: text/csv;');
            header("Pragma: no-cache");
            header("Expires: 0");
            // force download
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            // disposition / encoding on response body
            header("Content-Disposition: attachment;filename={$filename}");
            header("Content-Transfer-Encoding: binary");
            echo array2csv($get_balance_data_array);
            exit;
        }
    }

    public function activeUsersScript($exchange){
        if ($exchange == 'binance') {
            $collectionUsers = 'user_investment_binance';
        }else{
            $collectionUsers = 'user_investment_kraken';
        }

        $pipeline =  [['$match' => ['user_scanned_script_new' => ['$ne' => 1], 'is_api_key_valid' => 'yes', 'account_block' => 'no', 'user_wallet_balance' => ['$gt' => 20]]],
        ['$limit' => 30]];

        $db = $this->mongo_db->customQuery();
        $get_users_obj = $db->$collectionUsers->aggregate($pipeline);
        $get_users_array = iterator_to_array($get_users_obj);
        if(count($get_users_array) > 0){
            foreach ($get_users_array as $value) {
               
                $user = $this->insertUserCoinData($value['admin_id'], $exchange);
                $db->$collectionUsers->updateOne(['admin_id'=>$value['admin_id']],['$set'=>['user_scanned_script_new'=>1]]);
            } // end foreach
        }

    }

    public function insertUserCoinData($user_id, $exchange){
        $db = $this->mongo_db->customQuery();

        if ($exchange == 'binance') {
            $collectionCommitedBalances = 'commited_balances_users';
        }else{
            $collectionCommitedBalances = 'commited_balances_users_kraken';
        }
        $application_mode = 'live';
        $coins = $this->Mod_api_calls->get_all_user_coins($user_id, $exchange);
        //echo '<pre>coins :: '; print_r($coins); exit;
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
            $BTCUSDT_price = $pricesArr['BTCUSDT'];
            foreach ($coins as $coin) {
                $coin_name = $coin['coin_name'];
                $symbol = $coin['symbol'];
                // if ($symbol != 'BTC') {
                //     echo '<pre>symbol :: '; print_r($symbol); exit;
                // }
                
                $response_coin_id = (string)$coin['_id'];
                $coin_id = $coin->_id;
                $balance = $this->Mod_api_calls->get_coin_balance($user_id, $symbol, $exchange);
                $amount = null;
                $amount_status = null;
                if ($symbol != "BTC") {
                    $price = $pricesArr[$symbol];
                    $open_trades = $this->Mod_api_calls->get_open_trades($user_id, $symbol, $exchange, $application_mode);

                    
                    $open_trades_count = count($open_trades);
                    $costAvg_trades = $this->Mod_api_calls->get_costAvg_trades($user_id, $symbol, $exchange, $application_mode);
                    $costAvg_trades_count = count($costAvg_trades);
                    $open_trades = array_merge($open_trades, $costAvg_trades);

                    unset($costAvg_trades);
                    $change = $this->Mod_api_calls->get_24_hour_price_change($symbol);
                   
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
                            // if(empty($tradee['quantity_all'])){
                                foreach($tradee['cost_avg_array'] as $value_cost_avg_array){
                                    if($value_cost_avg_array['order_sold'] != 'yes'){
                                        $market_price += $BTCUSDT_price * $value_cost_avg_array['filledQtyBuy'] * $value_cost_avg_array['filledPriceBuy']; 
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
                        $comitted_balance = $total_trade_qty;
                        $comitted_balance_usd_worth = $comitted_balance * $price;
                      
                    }else{
                        $usd_balance =  $coin_balance*$price;
                        $comitted_balance = $total_trade_qty;
                        $comitted_balance_usd_worth = $comitted_balance * $price;
                       
                    }
                    $convertamount = round($price, 5);
                } else {
                    
                    if($symbol == "BTC"){
                        $usd_balance =  $coin_balance*$BTCUSDT_price;
                        $convertamount = round($BTCUSDT_price, 5);
                        $balance_error = false;
                        $comitted_balance = 0;
                        $comitted_balance_usd_worth = 0;
                     
                    }else{
                        $convertamount = round($price*$BTCUSDT_price, 5);
                        $usd_balance =  $coin_balance*$price*$BTCUSDT_price;
                        $comitted_balance = $total_trade_qty;
                        $comitted_balance_usd_worth = $comitted_balance*$price*$BTCUSDT_price;
                    }
                }
                $balance_usd_worth = $usd_balance;
                $coin_logo = $this->get_coin_image_new($symbol, $exchange);
                $base64_logo = '';
                $created_date = date('Y-m-d H:i:s');
                $created_date = $this->mongo_db->converToMongodttime($created_date);
                $coinData1 = array(
                    'symbol' => $symbol,
                    'admin_id'=> $user_id,
                    'last_price' => $price,
                    'usd_amount' => $convertamount,
                    'trade' => $open_trades_count,
                    'costAvgTrade' => $costAvg_trades_count,
                    'coin_id' => $response_coin_id,
                    'balance' => num($coin_balance),
                    'balance_usd_worth' => num($balance_usd_worth),
                    'usd_balance' => num($usd_balance),
                    'comitted_balance' => num($comitted_balance),
                    'comitted_balance_usd_worth' => num($comitted_balance_usd_worth),
                    'base_currency_comitted_balance' => num($comitted_balance),
                    'base_currency_comitted_balance_usd_worth' => num($comitted_balance_usd_worth),
                    'created_date'  => $created_date,
                );

                
                
                //save coin according to pair
                $temp_coin_balance_data = [];
                $sym = $this->get_sym($symbol);
                if(!empty($sym)){
                    $newArr[$sym][] = $coinData1;
                }
                $market[] = $coinData1;
            }
            foreach ($market as $key => $value){
                //save coin according to pair
                $temp_coin_balance_data = [];
                $sym = $this->get_sym($value['symbol']);
                $currPairsArr = $newArr[$sym];
                if ($value['symbol'] == 'BTC') {
                    $market[$key]['balance'] = num($market[$key]['balance']);
                    $market[$key]['balance_usd_worth'] = number_format((float) $market[$key]['balance_usd_worth'], 2);
                
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
                  
                } else {
                    if (count($currPairsArr) > 1) {
                        $total_comitted_balance = $currPairsArr[0]['comitted_balance'] + $currPairsArr[1]['comitted_balance'];
                        $total_comitted_balance = $total_comitted_balance;
                        $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'] + $currPairsArr[1]['comitted_balance_usd_worth'];
                        $total_comitted_balance_usd_worth = $total_comitted_balance_usd_worth;
                        $total_balance = $currPairsArr[0]['balance'];
                        $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];
                       
                        $market[$key]['comitted_balance'] = $total_comitted_balance;
                        $market[$key]['comitted_balance_usd_worth'] = $total_comitted_balance_usd_worth; 
                    }else{
                        $total_comitted_balance = $currPairsArr[0]['comitted_balance'];
                        $total_comitted_balance_usd_worth = $currPairsArr[0]['comitted_balance_usd_worth'];
                        $total_balance = $currPairsArr[0]['balance'];
                        $total_balance_usd_worth = $currPairsArr[0]['balance_usd_worth'];
                      
                        $market[$key]['comitted_balance'] = $total_comitted_balance;
                        $market[$key]['comitted_balance_usd_worth'] = $total_comitted_balance_usd_worth;
                    }
                }
                $market[$key]['balance'] = num($market[$key]['balance']);
                $market[$key]['balance_usd_worth'] = number_format((float) $market[$key]['balance_usd_worth'], 2);
               
                if(isset($market[$key]['comitted_balance'])){
                    $market[$key]['comitted_balance'] = num($market[$key]['comitted_balance']);
                    $market[$key]['comitted_balance_usd_worth'] = number_format((float) $market[$key]['comitted_balance_usd_worth'], 2);
                }
                $market[$key]['balance_error'] = $market[$key]['comitted_balance_usd_worth'] == $market[$key]['balance_usd_worth'] ? false : $market[$key]['balance_error'];
                $symbol111 = $market[$key]['symbol']; 
                $market[$key]['display_sell_btn'] = (!empty($market[$key]['extra_balance']) && !empty($minQtyArr[$symbol111]) && (float) $market[$key]['extra_balance'] >= $minQtyArr[$symbol111]['min_qty']) ? true : false;
            }
           $data['coin_market'] = $market;

            if (!empty($data['coin_market'])) {
                foreach ($data['coin_market'] as $key => $value) {
                    $db->$collectionCommitedBalances->insertOne($value);
                }
            }
        }
        echo "Done";
      }
    
    
    
      public function delete_session_folder() {
        // Define path to the session folder
        $session_folder_path = APPPATH . 'cache/session';
    
        // Check if the session folder exists
        if (is_dir($session_folder_path)) {
            // Recursively delete all files and subdirectories within the session folder
            $this->rrmdir($session_folder_path);
    
            // Attempt to delete the session folder
            if (rmdir($session_folder_path)) {
                echo "Session folder deleted successfully.";
            } else {
                echo "Failed to delete session folder.";
            }
        } else {
            echo "Session folder does not exist.";
        }
    }
    
    // Recursive function to delete files and directories
    private function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) {
                        $this->rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function reportOfAllUserTrades($exchange) {
        $application_mode = 'live';
        $db = $this->mongo_db->customQuery();
        $this->load->model('admin/Mod_api_calls');
    
        $collectionUsers = ($exchange == 'binance') ? 'user_investment_binance' : 'user_investment_kraken';
        $get_users_obj = $db->$collectionUsers->find(['is_api_key_valid' => 'yes', 'account_block' => 'no']);
        $get_users_array = iterator_to_array($get_users_obj);
    
        $pricesArr = get_current_market_prices($exchange, []);
        $BTCUSDT_price = $pricesArr['BTCUSDT'];
    
        $csv_data = [];
    
        foreach ($get_users_array as $user) {
            $user_id = $user['admin_id'];
            $username = $user['username'];
            $coins = $this->Mod_api_calls->get_all_user_coins_btc_only($user_id, $exchange);
    
            foreach ($coins as $key => $value) {
    
                $total_comitted_balance_usd_worth = 0;
                $total_sold_btc_value = 0;
                $total_purchased_btc_value = 0;
                $total_trade_qty = 0;
                $total_trade_purchased_price = 0;
                $total_trade_qty_sold = 0;
    
                $symbol = $value['symbol'];
                $price = $pricesArr[$symbol];
    
                $open_trades = $this->Mod_api_calls->get_open_trades($user_id, $symbol, $exchange, $application_mode);
                $costAvg_trades = $this->Mod_api_calls->get_costAvg_trades($user_id, $symbol, $exchange, $application_mode);
                $sold_trades = $this->Mod_api_calls->get_sold_trades($user_id, $symbol, $exchange, $application_mode);
                
                $open_trades = array_merge($open_trades, $costAvg_trades);
    
                if (!empty($open_trades)) {
                    foreach ($open_trades as $tradee) {
                        if (isset($tradee['cost_avg_array'])) {
                            foreach ($tradee['cost_avg_array'] as $value_cost_avg_array) {
                                if ($value_cost_avg_array['order_sold'] != 'yes') {
                                    $total_trade_qty += $value_cost_avg_array['filledQtyBuy'];
                                }
                            }
                        } else {
                            $total_trade_qty += (float)$tradee['quantity'];
                        }
                    }
    
                    foreach ($open_trades as $tradee) {
                        if (isset($tradee['cost_avg_array'])) {
                            foreach ($tradee['cost_avg_array'] as $value_cost_avg_array) {
                                if ($value_cost_avg_array['order_sold'] != 'yes') {
                                    $total_trade_purchased_price += $value_cost_avg_array['filledQtyBuy'] * $value_cost_avg_array['filledPriceBuy'];
                                }
                            }
                        } else {
                            $total_trade_purchased_price += (float)$tradee['quantity'] * $tradee['purchased_price'];
                        }
                    }
                
    
                    // if (!empty($sold_trades)) {
                    //     foreach ($sold_trades as $tradee) {
                    //         if (isset($tradee['cost_avg_array'])) {
                    //             foreach ($tradee['cost_avg_array'] as $value_cost_avg_array) {
                    //                 if ($value_cost_avg_array['order_sold'] == 'yes') {
                    //                     $total_trade_qty_sold += $value_cost_avg_array['filledQtyBuy'];
                    //                 }
                    //             }
                    //         } else {
                    //             $total_trade_qty_sold += (float)$tradee['quantity'];
                    //         }
                    //     }
                    // }
        
                    if ($symbol != '') {
                        $comitted_balance_usd_worth = $total_trade_qty * $price;
                        // $sold_balance_worth = $total_trade_qty_sold * $price;
        
                        $total_comitted_balance_usd_worth += $comitted_balance_usd_worth;
                        // $total_sold_btc_value += $sold_balance_worth;
                        $total_purchased_btc_value += $total_trade_purchased_price;
                        $profit_loss_on_ledger = $total_comitted_balance_usd_worth - $total_purchased_btc_value;
        
                        $open_investment_value = convert_btc_to_usdt($total_trade_purchased_price);
                        $open_investment_value_btc = $total_trade_purchased_price;

                        // $closed_investment_value = convert_btc_to_usdt($total_sold_btc_value);
                        // $total_investment = convert_btc_to_usdt($total_sold_btc_value + $total_comitted_balance_usd_worth);
        
                        if ($total_trade_purchased_price > 0) {
                            $open_investment_profit_loss_percentage = ($profit_loss_on_ledger / $total_trade_purchased_price) * 100;
                            $expected_receive_if_closed = $total_trade_purchased_price + $profit_loss_on_ledger;
                        } else {
                            $open_investment_profit_loss_percentage = 0;
                            $expected_receive_if_closed = 0;
                        }
        
                        $csv_data[] = [
                            'username' => (string)$username,
                            'symbol' => $symbol,
                            // 'total_investment' => $total_investment,
                            'open_investment_value' => $open_investment_value,
                            'open_investment_value_btc' => $open_investment_value_btc,
                            'open_investment_profit_loss_percentage' => $open_investment_profit_loss_percentage,
                            'expected_receive_if_closed' => convert_btc_to_usdt($expected_receive_if_closed),
                            'expected_receive_if_closed_btc' => $expected_receive_if_closed,
                        ];
                    }
                }else{
                    continue;
                }
            }
        }
    
        // echo '<pre>csv_data :: '; print_r($csv_data); exit;
        $this->generate_csv_user_report_ledgers($csv_data);
    }
    
    public function generate_csv_user_report_ledgers($get_balance_data_array)
    {
        if ($get_balance_data_array) {
            $filename = ("Users Report " . date("Y-m-d Gis") . ".csv");
            // Set the Headers for csv
            $now = gmdate("D, d M Y H:i:s");
    
            header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
            header("Last-Modified: {$now} GMT");
            header('Content-Type: text/csv;');
            header("Pragma: no-cache");
            header("Expires: 0");
            // force download
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            // disposition / encoding on response body
            header("Content-Disposition: attachment;filename={$filename}");
            header("Content-Transfer-Encoding: binary");
    
            $csv_output = fopen('php://output', 'w');
            fputcsv($csv_output, ['Username', 'Symbol', 'Open Investment Value ($)', 'Open Investment Value (₿)', 'Open Investment Profit/Loss (%)', 'Expected Amount If Closed ($)', 'Expected Amount If Closed (₿)']);
    
            foreach ($get_balance_data_array as $row) {
                fputcsv($csv_output, $row);
            }
    
            fclose($csv_output);
            exit;
        }
    }
    
    

    // public function reportOfAllUserTrades($exchange){

    //     $application_mode = 'live';
    //     $db = $this->mongo_db->customQuery();
    //     $this->load->model('admin/Mod_api_calls');
       
        // if ($exchange == 'binance') {
        //     $collectionUsers = 'user_investment_binance';
        // }else{
        //     $collectionUsers = "user_investment_binance";
        // }
        // $get_users_obj = $db->$collectionUsers->find(['is_api_key_valid' => 'yes', 'account_block' => 'no']);
        // $get_users_array = iterator_to_array($get_users_obj);

        // if (count($get_users_array) > 0 ) {
    //         echo '<pre>count :: '; print_r(count($get_users_array)); exit;
    //         $pricesArr = get_current_market_prices($exchange, []);
    //         $BTCUSDT_price = $pricesArr['BTCUSDT'];
        
    //         $total_all_users_investment_ledger = 0;
    //         $total_all_users_open_investment_value = 0;
    //         $total_all_users_expected_if_closed = 0;
    //         $total_all_users_profit_loss_percentage = 0;
    //         $total_all_users_open_trades = 0;
        
    //         foreach ($get_users_array as $user) {
    //             $user_id = $user['admin_id']; 
    //             $coins = $this->Mod_api_calls->get_all_user_coins_btc_only($user_id, $exchange);
                
    //             $total_user_investment_ledger = 0;
    //             $total_user_open_investment_value = 0;
    //             $total_user_expected_if_closed = 0;
    //             $total_user_open_trades = 0;
        
    //             foreach ($coins as $key => $value) {
                
    //                 $total_comitted_balance_usd_worth = 0;
    //                 $total_sold_btc_value = 0;
    //                 $total_purchased_btc_value = 0;
    //                 $total_trade_qty = 0;
    //                 $total_trade_purchased_price = 0;
    //                 $total_trade_qty_sold = 0;
                    
    //                 $symbol = $value['symbol'];
    //                 $price = $pricesArr[$symbol];
        
    //                 $open_trades = $this->Mod_api_calls->get_open_trades($user_id, $symbol, $exchange, $application_mode);
    //                 $costAvg_trades = $this->Mod_api_calls->get_costAvg_trades($user_id, $symbol, $exchange, $application_mode);
    //                 $sold_trades = $this->Mod_api_calls->get_sold_trades($user_id, $symbol, $exchange, $application_mode);
        
    //                 $open_trades = array_merge($open_trades, $costAvg_trades);
        
    //                 if (!empty($open_trades)) {
    //                     foreach ($open_trades as $tradee) {
    //                         if (isset($tradee['cost_avg_array'])) {
    //                             foreach ($tradee['cost_avg_array'] as $value_cost_avg_array) {
    //                                 if ($value_cost_avg_array['order_sold'] != 'yes') {
    //                                     $total_trade_qty += $value_cost_avg_array['filledQtyBuy'];
    //                                 }
    //                             }
    //                         } else {
    //                             $total_trade_qty += (float)$tradee['quantity'];
    //                         }
    //                     }
        
    //                     foreach ($open_trades as $tradee) {
    //                         if (isset($tradee['cost_avg_array'])) {
    //                             foreach ($tradee['cost_avg_array'] as $value_cost_avg_array) {
    //                                 if ($value_cost_avg_array['order_sold'] != 'yes') {
    //                                     $total_trade_purchased_price += $value_cost_avg_array['filledQtyBuy'] * $value_cost_avg_array['filledPriceBuy'];
    //                                 }
    //                             }
    //                         } else {
    //                             $total_trade_purchased_price += (float)$tradee['quantity'] * $tradee['purchased_price'];
    //                         }
    //                     }
    //                 }
        
    //                 if (!empty($sold_trades)) {
    //                     foreach ($sold_trades as $tradee) {
    //                         if (isset($tradee['cost_avg_array'])) {
    //                             foreach ($tradee['cost_avg_array'] as $value_cost_avg_array) {
    //                                 if ($value_cost_avg_array['order_sold'] == 'yes') {
    //                                     $total_trade_qty_sold += $value_cost_avg_array['filledQtyBuy'];
    //                                 }
    //                             }
    //                         } else {
    //                             $total_trade_qty_sold += (float)$tradee['quantity'];
    //                         }
    //                     }
    //                 }
        
    //                 if ($symbol != '') {
    //                     $usd_balance = $total_trade_qty * $price * $BTCUSDT_price;
    //                     $comitted_balance_usd_worth = $total_trade_qty * $price;
    //                     $sold_balance_worth = $total_trade_qty_sold * $price;
        
    //                     $total_comitted_balance_usd_worth += $comitted_balance_usd_worth;
    //                     $total_sold_btc_value += $sold_balance_worth;
    //                     $total_purchased_btc_value += $total_trade_purchased_price;
    //                     $profit_loss_on_ledger = $total_comitted_balance_usd_worth - $total_purchased_btc_value;
        
    //                     $open_investment_value = convert_btc_to_usdt($total_comitted_balance_usd_worth);
    //                     $closed_investment_value = convert_btc_to_usdt($total_sold_btc_value);
    //                     $total_investment = convert_btc_to_usdt($total_sold_btc_value + $total_comitted_balance_usd_worth);
        
    //                     if ($total_comitted_balance_usd_worth > 0) {
    //                         $open_investment_profit_loss_percentage = ($profit_loss_on_ledger / $total_comitted_balance_usd_worth) * 100;
    //                         $expected_receive_if_closed = $total_comitted_balance_usd_worth + $profit_loss_on_ledger;
    //                     } else {
    //                         $open_investment_profit_loss_percentage = 0;
    //                         $expected_receive_if_closed = 0;
    //                     }
        
    //                     $total_user_investment_ledger += $total_investment;
    //                     $total_user_open_investment_value += $open_investment_value;
    //                     $total_user_expected_if_closed += convert_btc_to_usdt($expected_receive_if_closed);
    //                     $total_user_open_trades += count($open_trades);
    //                 }
    //             }
        
    //             $total_all_users_investment_ledger += $total_user_investment_ledger;
    //             $total_all_users_open_investment_value += $total_user_open_investment_value;
    //             $total_all_users_expected_if_closed += $total_user_expected_if_closed;
    //             $total_all_users_open_trades += $total_user_open_trades;
    //         }

    //         echo '<pre>'; print_r('******************================*****************');
    //         echo '<pre>Total Investment in Ledger (All Users) :: $' . $total_all_users_investment_ledger;
    //         echo '<pre>Total Open Investment Value (All Users) :: $' . $total_all_users_open_investment_value;
    //         echo '<pre>Total Expected Amount If Closed (All Users) :: $' . $total_all_users_expected_if_closed;
    //         echo '<pre>Total Number of Open Trades (All Users) :: ' . $total_all_users_open_trades;
    //         echo '<pre>'; print_r('******************================*****************');
    //     }
    // }
    
    
    function convert_btc_to_usdt($btc_value) {
        global $BTCUSDT_price;
        return $btc_value * $BTCUSDT_price;
    }
    
    
    
    
}//end of controller