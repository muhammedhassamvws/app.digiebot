<?php
/**
 *
 */
class Test extends CI_Controller {

    function __construct() {

        parent::__construct();

        // ini_set("display_errors", E_ALL);
        // error_reporting(E_ALL);

        $this->load->model('admin/mod_login');
        $this->mod_login->verify_is_admin_login();

           //helper 
        $this->load->helper('common_helper');

    }


    public function exchangeInfo(){
        
        $res = $this->binance_api->exchangeInfo();
        $coinsWeTrade = [
            'NEOBTC',
            'ETCBTC',
            'ZENBTC',
            'EOSBTC',
            'XEMBTC',
            'TRXBTC',
            'XLMBTC',
            'POEBTC',
            'QTUMBTC',
            'XRPBTC',
            'NEOUSDT',
            'BTCUSDT',
            'XRPUSDT',
            'QTUMUSDT',
            'ETHBTC',
            'LINKBTC',
            'XMRBTC',
            'DASHBTC',
            'ADABTC',
            'LTCUSDT',
            'EOSUSDT',
        ];

        echo "<pre>";
        echo "coinsWeTrade : <br>";
        print_r($coinsWeTrade);
        echo "exchange info : <br>";

        foreach($res['symbols'] as $val){
            if(in_array($val['symbol'], $coinsWeTrade)){
                print_r($val);
            }
        }

    }


    public function update_daily_buy_limit_test($user_id=''){

        //Get all live users
        if ($user_id != '') {
            $this->mongo_db->where(['_id' => $user_id]);
        } else {
            $this->mongo_db->where_in('application_mode', ['both', 'live']);
        }
        $result = $this->mongo_db->get('users');
        $users_arr = iterator_to_array($result);
        $user_ids = array_column($users_arr, '_id');
        unset($result, $users_arr);

        echo "<pre>";
        // print_r($user_ids);
        // die('testing');

        $users_count = count($user_ids);
        $user_id = '';
        for($i=0; $i < $users_count; $i++){
            $user_id = $user_ids[$i];

            //Get user daily_trade_buy_limit
            $collection_name = 'daily_trade_buy_limit';
            $this->mongo_db->where([
                'user_id' => (string) $user_id,
            ]);
            $result = $this->mongo_db->get($collection_name);
            $user_buy_limit = iterator_to_array($result);
            unset($result);

            if(count($user_buy_limit) > 0){
                unset($user_buy_limit);
                $upd_data = [
                    'daily_buy_usd_worth' => 0,
                    'num_of_trades_buy_today' => 0,
                    'daily_buy_usd_limit' => 200,
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
                    'daily_buy_usd_limit' => 200,
                    'created_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                    'modified_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                ];
                $this->mongo_db->insert($collection_name, $ins_data);
            }
        }

        echo "end script";

        //Save last Cron Executioon
        // $this->last_cron_execution_time('update_auto_trade_actual_tradeable_balance', '1d', 'Cronjob to update actual tradeable balance for Auto trade module');

    }//end update_daily_buy_limit_test

    public function update_daily_buy_limit($user_id=''){

        //Get all live users
        if ($user_id != '') {
            $this->mongo_db->where(['_id' => $user_id]);
        } else {
            $this->mongo_db->where_in('application_mode', ['both', 'live']);
        }
        $result = $this->mongo_db->get('users');
        $users_arr = iterator_to_array($result);
        $user_ids = array_column($users_arr, '_id');
        unset($result, $users_arr);

        // echo "<pre>";
        // print_r($user_ids);
        // die('testing');

        $users_count = count($user_ids);
        $user_id = '';
        for($i=0; $i < $users_count; $i++){
            $user_id = $user_ids[$i];

            //Get BTCUSDTprice
            $this->mongo_db->where([
                'coin' => 'BTCUSDT',
            ]);
            $result = $this->mongo_db->get('user_wallet');
            $price_arr = iterator_to_array($result);
            unset($result);
            $BTCUSDT_price = (float) $price_arr[0]['price'];

            //Get user balance 
            $this->mongo_db->where([
                'user_id' => $user_id,
                'coin_symbol' => [ '$in' => ['BTC', 'USDT']],
            ]);
            $result = $this->mongo_db->get('user_wallet');
            $balance_arr = iterator_to_array($result);
            unset($result);
            $BTC_balance = 0; 
            $USDT_balance = 0;
            $total_balance_in_usd = 0;  
            for($j=0; $j<2; $j++){
                if($balance_arr[$j]['coin_symbol'] == 'BTC'){
                    $BTC_balance = (float) $balance_arr[$j]['coin_balance'];
                }else if($balance_arr[$j]['coin_symbol'] == 'USDT'){
                    $USDT_balance = (float) $balance_arr[$j]['coin_balance'];
                }
            }

            $total_balance_in_usd = (float) ($USDT_balance + ($BTC_balance * $BTCUSDT_price));
            
            // Get user package
            $params = [
                'user_id' => (string) $user_id,
                'handshake' => $this->get_temp_request_token(),
            ];
            $req_arr = [
                'req_type' => 'POST',
                'req_params' => $params,
                'req_endpoint' => '',
                'req_url' => 'https://users.digiebot.com/cronjob/GetUserSubscriptionDetails/',
            ];
            $resp = hitCurlRequest($req_arr);

            $package_limit = 1000;
            if($resp['http_code'] == 200 && !empty($resp['response']['trade_limit'])){
                $package_limit = (float) $resp['response']['trade_limit'];
            }
            
            //Get user auto trade settings
            $this->mongo_db->where([
                'user_id' => $user_id,
            ]);
            $result = $this->mongo_db->get('auto_trade_settings');
            $settings_arr = iterator_to_array($result);
            unset($result);

            $daily_balance_percentage = 15;
            if(!empty($settings_arr) && !empty($settings_arr[0]['step_4']['dailTradeAbleBalancePercentage'])){
                $daily_balance_percentage = (float) $settings_arr[0]['step_4']['dailTradeAbleBalancePercentage'];
                unset($settings_arr);
            }

            $total_tradeable_balance = 0;
            // TODO if available balnce is less than package limit then then use avaiable balnce as 
            if($total_balance_in_usd <= $package_limit){
                $total_tradeable_balance = $total_balance_in_usd; 
            }else{
                $total_tradeable_balance = $package_limit; 
            }

            $no_of_daily_trades = 5;
            $daily_limit_in_usd = 0; 
            $daily_limit_in_usd = ($daily_balance_percentage * $total_tradeable_balance) / 100;


            //Get user daily_trade_buy_limit
            $collection_name = 'daily_trade_buy_limit';
            $this->mongo_db->where([
                'user_id' => (string) $user_id,
            ]);
            $result = $this->mongo_db->get($collection_name);
            $user_buy_limit = iterator_to_array($result);
            unset($result);

            if(count($user_buy_limit) > 0){
                unset($user_buy_limit);
                $upd_data = [
                    'daily_buy_usd_worth' => 0,
                    'trades_buy_today' => 0,
                    'daily_buy_usd_limit' => 200,
                    'modified_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                ];
                $this->mongo_db->where("user_id", (string) $user_id);
                $this->mongo_db->set($upd_data);
                $this->mongo_db->update($collection_name);
            }else{
                $ins_data = [
                    'user_id' => (string) $user_id,
                    'daily_buy_usd_worth' => 0,
                    'trades_buy_today' => 0,
                    'daily_buy_usd_limit' => 200,
                    'created_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                    'modified_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                ];
                $this->mongo_db->insert($collection_name, $ins_data);
            }
        }

        echo "end script";

        //Save last Cron Executioon
        // $this->last_cron_execution_time('update_auto_trade_actual_tradeable_balance', '1d', 'Cronjob to update actual tradeable balance for Auto trade module');

    }//end update_daily_buy_limit


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

            echo "<pre>";
            echo "\r\n $total_users \r\n";
            print_r($user_ids);
            return $user_ids;
        }
        return [];
    }//end get_live_user_ids
    
    //get_live_atg_user_ids
    public function get_live_atg_user_ids(){

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
        $atg_users = $document->auto_trade_settings->aggregate($pipeline);
        $atg_users = iterator_to_array($atg_users);
        if (!empty($atg_users)) {
            $total_atg_users = count($atg_users);
            for ($i = 0; $i < $total_atg_users; $i++) {
                $user_ids[] = $this->mongo_db->mongoId($atg_users[$i]['user_id']);
            }
        }

        // unset($atg_users);

        $last_run = date('Y-m-d H:i:s', strtotime('-4 days'));
        $pipeline = [
            [
                '$match' => [
                    '_id' => ['$in' => $user_ids],
                    'application_mode' => 'both',
                    'atg_parents_update_cron_last_run' => ['$lte' => $this->mongo_db->converToMongodttime($last_run)],
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
                ],
            ],
            [
                '$limit' => 35,
            ],
        ];
        $document = $this->mongo_db->customQuery();
        $atg_users = $document->users->aggregate($pipeline);
        $atg_users = iterator_to_array($atg_users);

        echo "<pre>";
        // var_dump($atg_users[0]);
        // var_dump($atg_users[0]['_id']);
        // var_dump($atg_users);
        
        if(!empty($atg_users)){
            $total_users = count($atg_users);
            for($i=0;$i<$total_users; $i++){
                
                var_dump((string)$atg_users[$i]['_id']);
                // if(empty($atg_users[$i]['_id']) && $i != 0){
                //     continue;
                // }

            }
        }

        
    }//end get_live_atg_user_ids

    //randomize_sort_number
    public function randomize_sort_number(){

        // echo "<pre>";
        // echo date('Y-m-d H:i:s');


        $exchange = 'binance';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";

        $last_run = date('Y-m-d H:i:s', strtotime('-7 days'));
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
                '$project' => [
                    '_id' => 1,
                ],
            ],
            [
                '$limit' => 200,
            ],
        ];
        $db = $this->mongo_db->customQuery();
        $parent_orders = $db->$buy_collection->aggregate($pipeline);
        $parent_orders = iterator_to_array($parent_orders);

        if(!empty($parent_orders)){
            $count = count($parent_orders);
            for($i=0; $i<$count; $i++){
                $db->$buy_collection->updateOne(['_id'=>$parent_orders[$i]['_id']], ['$set'=>['randomize_sort'=>rand(0,1000), 'randomize_sort_date'=>$curr_time]]);
            }
        }
        unset($parent_orders);

        // echo "<br>";
        // echo date('Y-m-d H:i:s');
        echo "<br> ***************** script end ********************** <br>";
        
    }//end randomize_sort_number


    public function pick_parent_test(){

        $exchange = 'binance';
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
                                '$cond' => [ 'if' => [ '$gt' => ['$count', 1] ], 'then' => '$$KEEP', 'else' => '$$PRUNE' ]
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
                $db->$buy_collection->updateMany(['_id' => ['$in' => $result[$i]['recordArr'][0]['ids'] ]], ['$set'=>['pick_parent'=>'no']]);
                $db->$pick_parent_temp_collection->deleteOne(['_id' => $result[$i]['_id'] ]);
                unset($result[$i]);
            }
        }
        unset($result);
        // echo "<br> ***************** SCRIPT END unset pick parent which might exceed limit ********************** <br>";
        return true;
    }


    /* *****************  check users daily buy limit  ******************* */

    public function check_users_daily_buy_limit($opportunityId, $exchange){

        // $exchange = 'binance';
        // $opportunityId = '5f51ae2a59874054ec1858db';

        $user_ids = $this->get_executed_user_ids_by_opportunity_id($opportunityId, $exchange);
        $user_ids = $this->get_daily_limit_exceeded_users($user_ids, $exchange);
        $this->restrict_parents_for_limit_exceeded_users($user_ids, $exchange);
        unset($user_ids, $opportunityId, $exchange);

        //Save last Cron Executioon
        // $last_cron_exchange = "check_users_daily_buy_limit_$exchange";
        // $this->last_cron_execution_time($last_cron_exchange, '3d', 'Cronjob to check user buy limit after opportunity hit (when ever hit comes)');

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

    public function get_daily_limit_exceeded_users($user_ids=[], $exchange='binance'){

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

        if(!empty($user_ids)){
            $pipeline[0]['$match']['user_id']['$in'] = $user_ids;
        }

        $user_ids = $connetct->$limit_collection->aggregate($pipeline);
        $user_ids = iterator_to_array($user_ids);
        $user_ids = !empty($user_ids) ? (array) $user_ids[0]['user_ids'] : [];

        // print_r($user_ids);

        return $user_ids;
    }

    public function restrict_parents_for_limit_exceeded_users($user_ids, $exchange){
        
        $connetct = $this->mongo_db->customQuery();
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
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

    /* *****************  End check users daily buy limit  ******************* */

                
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
        
        if($resp['http_code'] == 200 && $resp['response']['status']){
            return (float) $resp['response']['trade_limit'];
        }
        return 1000;
    }

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
    }

    public function get_temp_request_token(){
        $token_arr = [
            'cf31f1bc3a0b3729f35832ff25c7f838',
            '34e0e2a1b05b11dccec3a1f0e55f12ed',
            'cd6d40934f1b41485a34e551961dea47',
            '674cf50e89bac56f29d1e7c919608247',
            'd34aaa3fb16773581167023ddda3b9b2',
            'e1812af878fb6323b022658aeab88981',
            '16f1f98832e8a22d334583d1b55ca74e',
            'e5f604c9e53e8bd397f7a0299a6c67ee',
            'ec4724447307c2973de0bda64c8ac4f7',
            'f221ca4ba18d776579cc442defd63c59',
            '2ef28a3a254745dd124b9425e2c54826',
            'cfa03debbee649ff160a6b74d83d8ff8',
            '95b119e31ad12564723790193f118231',
            '035b4ed3b93bae0e5f912acaf0dbb914',
            '647240ad2a6edd157c7986261d8527ee',
            '671837b9788b7f5b59f00815b74cd889',
            'df632a8d5703229bc031ce40e6dc16d9',
            '83c86ede18cc3bb07f9ecde100631f1e',
            '303da99664d5acc06de8ecda890ce52b',
            '81d1d45dbb19a90fdfae4f87865b136a',
            '63cdf744b7d76f27357ba1722da51ee6',
        ];
        return $token_arr[array_rand($token_arr)];
    }

    //get_user_daily_buy_limit_in_usd
    public function get_user_daily_buy_limit_in_usd($user_id, $exchange){

        echo "<pre>";

        $user_id = (string) $user_id;
        //get BTCUSDT price
        $price_collection = ($exchange == "binance" ? "market_prices" : "market_prices_$exchange");
        $this->mongo_db->where(['coin' => 'BTCUSDT']);
        $this->mongo_db->sort(['created_date' => -1]);
        $this->mongo_db->limit(1);
        $result = $this->mongo_db->get($price_collection);
        $priceArr = iterator_to_array($result);

        if (!empty($priceArr)) {
            $BTCUSDT_price = (float) $priceArr[0]['price'];

            //get user ATG settings
            $atg_settings_collection = ($exchange == "binance" ? "auto_trade_settings" : "auto_trade_settings_$exchange");
            $this->mongo_db->where(['user_id' => $user_id, 'application_mode' => 'live']);
            $this->mongo_db->limit(1);
            $result2 = $this->mongo_db->get($atg_settings_collection);
            $settingsArr = iterator_to_array($result2);

            $dail_buy_usd_worth = 0;
            $dailyTradeableBTC = 0;
            $dailyTradeableUSDT = 0;
            $dailyTradeableBTC_usd_worth = 0;
            $dailyTradeableUSDT_usd_worth = 0;
            if (!empty($settingsArr)) {
                if (!empty($settingsArr[0]['step_4']['dailyTradeableUSDT'])) {
                    $dailyTradeableUSDT = (float) $settingsArr[0]['step_4']['dailyTradeableUSDT'];

                    //add 10% extra
                    if ($dailyTradeableUSDT != 0) {
                        $dailyTradeableUSDT = $dailyTradeableUSDT + ((10 * $dailyTradeableUSDT) / 100);
                    }
                    $dailyTradeableUSDT_usd_worth = $dailyTradeableUSDT;
                    $dail_buy_usd_worth += $dailyTradeableUSDT;
                }
                if (!empty($settingsArr[0]['step_4']['dailyTradeableBTC'])) {
                    $dailyTradeableBTC = (float) $settingsArr[0]['step_4']['dailyTradeableBTC'];

                    //add 10% extra
                    if ($dailyTradeableBTC != 0) {
                        $dailyTradeableBTC = $dailyTradeableBTC + ((10 * $dailyTradeableBTC) / 100);
                    }
                    $dailyTradeableBTC_usd_worth = $dailyTradeableBTC * $BTCUSDT_price;
                    $dail_buy_usd_worth += $dailyTradeableBTC * $BTCUSDT_price;
                }

                $dail_buy_usd_worth = $dailyTradeableBTC_usd_worth + $dailyTradeableUSDT_usd_worth;

                $resArr = [
                    'dailyTradeableBTC' => $dailyTradeableBTC,
                    'dailyTradeableUSDT' => $dailyTradeableUSDT,
                    'dailyTradeableBTC_usd_worth' => $dailyTradeableBTC_usd_worth,
                    'dailyTradeableUSDT_usd_worth' => $dailyTradeableUSDT_usd_worth,
                    'daily_available_usd' => $dail_buy_usd_worth,
                ];

                //add 10% extra
                // if($dail_buy_usd_worth != 0){
                //     $dail_buy_usd_worth= $dail_buy_usd_worth + ((10 * $dail_buy_usd_worth) / 100);
                // }
                unset($result2, $settingsArr);
                // return $dail_buy_usd_worth;

                print_r($resArr);

                return $resArr;
            } else {

                //call new function to calculate from new formula
                // $daily_available_usd = $this->calculate_daily_tradeable_usd($user_id, $exchange);
                // return $daily_available_usd;

                $daily_limit_arr = $this->calculate_daily_tradeable_usd_new($user_id, $exchange);

                print_r($daily_limit_arr);

                return $daily_limit_arr;
            }
        }

        unset($result, $priceArr);
        return 200;
    } //end get_user_daily_buy_limit_in_usd

    //calculate_daily_tradeable_usd_new
    public function calculate_daily_tradeable_usd_new($user_id, $exchange){

        // echo "<pre>";
        $pricesArr = get_current_market_prices($exchange, ['BTCUSDT']);
        $data = $this->get_dashboard_wallet($user_id, $exchange);

        //TODO: find default daily limit with 15% according to his trade package and available balance
        $btc_percent = 30;
        $usdt_percent = 70;
        $daily_tradeable = 15;

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

                // $totalTradeAbleInUSD = ($btcInTrades * $pricesArr['BTCUSDT']) >= $tradeLimit ? 0 : (($tradeLimit / $pricesArr['BTCUSDT']) - $btcInTrades) * $pricesArr['BTCUSDT'];

                $remainingTradddeeAble = $tradeLimit - $usedUsdWorthInTrades > 0 ? $tradeLimit - $usedUsdWorthInTrades : 0;

                // echo('<br> 11111111 remainingTradddeeAble '. $remainingTradddeeAble);

                $availableBTC = ($remainingTradddeeAble / $pricesArr['BTCUSDT']) > $tempBalanceObj['BTC'] ? $tempBalanceObj['BTC'] : ($remainingTradddeeAble / $pricesArr['BTCUSDT']);

                $availableUSDT = $remainingTradddeeAble > $tempBalanceObj['USDT'] ? $tempBalanceObj['USDT'] : $remainingTradddeeAble;

                // $totalTradeAbleInUSD = $tradeLimit - usedUsdWorthInTrades > 0 ? $tradeLimit - usedUsdWorthInTrades : 0;

                if ($remainingTradddeeAble <= 0) {
                    // this.toastr.error('Your package has been exceed please upgrade to a bigger package', 'ERROR');
                }
            } else {

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

            //add 10% extra
            if ($dailyBtc != 0) {
                $dailyBtc = $dailyBtc + ((10 * $dailyBtc) / 100);
            }
            if ($dailyUsdt != 0) {
                $dailyUsdt = $dailyUsdt + ((10 * $dailyUsdt) / 100);
            }

            $dailyBtcUsdWorth = $dailyBtc * $pricesArr['BTCUSDT'];
            $dailyUsdtUsdWorth = $dailyUsdt;

            // echo ("dailTradeAbleBalancePercentage ', $dailTradeAbleBalancePercentage");
            // echo ("dailyBtc  $dailyBtc ------- dailyUsdt  $dailyUsdt");
            // echo ("dailyBtcUsdWorth  $dailyBtcUsdWorth ------- dailyUsdtUsdWorth  $dailyUsdtUsdWorth");

            $dailyTradeableBTC = $dailyBtc;
            $dailyTradeableUSDT = $dailyUsdt;

            $daily_available_usd = $dailyBtcUsdWorth + $dailyUsdtUsdWorth;

            $resArr = [
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

            //add 10% extra
            if ($daily_btc != 0) {
                $daily_btc = $daily_btc + ((10 * $daily_btc) / 100);
            }
            if ($daily_usdt != 0) {
                $daily_usdt = $daily_usdt + ((10 * $daily_usdt) / 100);
            }

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

        echo SURL . "assets/coin_logo/thumbs/" . $coin['coin_logo'];
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

    // public function umer($user_id='', $exchange=''){

    //     $points_key = $exchange == 'binance' ? "current_trading_points" : "current_trading_points_$exchange";
    //     $trading_points_collection = $exchange == 'binance' ? "trading_points_history" : "trading_points_history_$exchange";

    //     $pipeline = [
    //         [
    //             '$match' => [
    //                 'user_id' => (string) $user_id,
    //                 'action' => 'deduct',
    //             ],
    //         ],
    //         [
    //             '$group' => [
    //                 '_id' => null,
    //                 'total_consumed' => ['$sum' => '$points_consumed'],
    //             ],
    //         ],
    //         [
    //             '$project' => [
    //                 'total_consumed' => 1,
    //                 '_id' => 0,
    //             ],
    //         ],
    //     ];
    //     $document = $this->mongo_db->customQuery();
    //     $atg_users = $document->$trading_points_collection->aggregate($pipeline);
    //     $atg_users = iterator_to_array($atg_users);

    //     echo "<pre>";
    //     var_dump($atg_users[0]['total_consumed']);
    //     die('<br> testing <br>');


    //     $endTime = date('Y-m-d H:59:59');
    //     $startTime = date('Y-m-d H:00:00', strtotime('-24 hours'));
        
    //     $startTime = $this->mongo_db->converToMongodttime($startTime);
    //     $endTime =  $this->mongo_db->converToMongodttime($endTime);

    //     $pipeline = [
    //         [
    //             '$match'=> [
    //                 'purchased_price'=> ['$exists'=> true],
    //                 'quantity'=> ['$exists'=> true],
    //                 'status'=> [
    //                     '$nin'=> [
    //                         'canceled',
    //                         'error',
    //                         'new_ERROR',
    //                         'FILLED_ERROR',
    //                         'submitted_ERROR',
    //                         'LTH_ERROR',
    //                         'canceled_ERROR',
    //                         'credentials_ERROR',
    //                     ]
    //                 ],
    //                 'buy_date'=> [
    //                     '$gte'=> $startTime, 
    //                     '$lte'=> $endTime
    //                 ],
    //             ]
    //         ],
    //         [
    //             '$limit' => 50
    //         ],
    //         // [
    //         //     '$group' => [

    //         //         '_id' => null,
    //         //         'sub' => ['$subtract' => ['$market_sold_price', '$purchased_price']],
    //         //         // 'div' => ['$divide' => ['$sub' , '$purchased_price']],
    //         //         // 'per_trade_sold' =>  ['$multiply' => [ 100 , '$div']],
    //         //         // 'avg' =>  ['$divide' => [ '$per_trade_sold', '$count']],
    //         //     ],
    //         // ],
    //         [
    //             '$addFields' => [
    //                 'sub' => ['$subtract' => ['$market_sold_price', '$purchased_price']],
    //                 'div' => ['$divide' => ['$sub' , '$purchased_price']],
    //                 'per_trade_sold' =>  ['$multiply' => [ 100 , '$div']],
    //                 'avg' =>  ['$divide' => [ '$per_trade_sold', '$count']],
    //             ],
    //         ],
    //         [
    //             '$project' => [
    //                 'sub' => 1,
    //                 'div' => 1,
    //                 'per_trade_sold' =>  1,
    //                 'avg' => 1,
    //             ],
    //         ],
    //         // [
    //         //     '$group' => [
    //         //      '_id' => null,
    //         //      'div' => ['$divide' => ['sub' , '$purchased_price']],
    //         //     ],
    //         // ],

    //         // [
    //         //     '$group' => [
    //         //      '_id' => null,
    //         //      'per_trade_sold' =>  ['$multiply' => [ 100 , 'div']],
    //         //     ],
    //         // ],

    //         // [
    //         //     '$group' => [
    //         //      '_id' => null,
    //         //      'avg' =>  ['$divide' => [ 'per_trade_sold', '$count']],
    //         //     ],
    //     ];

    //     $document = $this->mongo_db->customQuery();
    //     $atg_users = $document->sold_buy_orders->aggregate($pipeline);
    //     $atg_users = iterator_to_array($atg_users);

    //     echo "<pre>";
    //     print_r($atg_users);
    //     die("<br> *************** End Script  ***************** <br>");






    //     // $pipeline = [
    //     //     [
    //     //         '$project' => [
    //     //             '_id' => 0,
    //     //             'user_id' => 1,
    //     //             'daily_buy_usd_worth' => 1,
    //     //             'num_of_trades_buy_today' => 1,
    //     //             'daily_buy_usd_limit' => 1,
    //     //         ],
    //     //     ],
    //     //     [
    //     //         '$addFields' => [
    //     //             'created_date' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
    //     //         ],
    //     //     ],
    //     // ];
    //     // $document = $this->mongo_db->customQuery();
    //     // $data111 = $document->daily_trade_buy_limit->aggregate($pipeline);
    //     // $dd111 = iterator_to_array($data111);


    //     // $connect = $this->mongo_db->customQuery();
    //     // $collectionName = "buy_orders";
    //     // $pipeline = [
    //     //     [
    //     //         '$match' => [
    //     //             'application_mode' => 'live',
    //     //             'parent_status' => 'parent',
    //     //             'status' => ['$ne' => 'canceled'],
    //     //             'auto_trade_generator' => 'yes',
    //     //         ],
    //     //     ],
    //     //     [
    //     //         '$group' => [
    //     //             "_id" => ['symbol' => '$symbol', 'admin_id' => '$admin_id'],
    //     //             "items" => ['$push' => '$$ROOT'],
    //     //         ],
    //     //     ],
    //     //     [
    //     //         '$sample' => ['size' => 5000],
    //     //     ],
    //     //     [
    //     //         '$group' => [
    //     //             '_id' => ['admin_id' => '$_id.admin_id', 'symbol' => '$_id.symbol'],
    //     //             'parent_ids' => ['$first' => '$items._id'],
    //     //         ],
    //     //     ],
    //     //     [
    //     //         '$limit' => 1,
    //     //     ],
    //     // ];

    //     // $get_users = $connect->$collectionName->aggregate($pipeline);
    //     // $users_arr = iterator_to_array($get_users);

    //     // if(!empty($users_arr)){
    //     //     $user_id = $users_arr[0]['_id']['admin_id'];
    //     //     $symbol = $users_arr[0]['_id']['symbol'];
    //     //     $tc =  count($users_arr[0]['parent_ids']);
    //     //     for($i = 0; $i < $tc; $i++){
    //     //         $parent_ids[$i] = (string) $users_arr[0]['parent_ids'][$i]; 
    //     //     }
    //     // }

    //     // echo "$symbol =============  $user_id <br>";
    //     // echo json_encode($parent_ids);

    //     // return true;





    //     //     // echo "<pre> testing ";
    //     // $connetct = $this->mongo_db->customQuery();

    //     // //get binance users with API key secret
    //     // $exchange = "binance";
    //     // $collection = $exchange == "binance" ? "users" : $exchange."_credentials";

    //     // $pipeline = [
    //     //     [
    //     //         '$match'=>[
    //     //             'api_key'=>['$exists'=>true],
    //     //             'api_secret'=>['$exists'=>true],
    //     //             'application_mode'=>'both'
    //     //         ]
    //     //     ],
    //     //     [
    //     //         '$group'=>[
    //     //             '_id'=>null,
    //     //             'user_ids'=>['$push'=>['$toString'=>'$_id']]
    //     //         ]
    //     //     ],
    //     //     [
    //     //         '$project'=>[
    //     //             'user_ids'=>1,
    //     //             '_id'=>0,
    //     //         ]
    //     //     ]
    //     // ];

    //     // //only pick user with api_secret credentials for exchanges
    //     // if($exchange != 'binance' && $exchange != ''){
    //     //   $collection_t = $exchange . "_credentials";
    //     //     $pipeline_t = [
    //     //         [
    //     //             '$match' => [
    //     //                 'api_key' => ['$exists' => true],
    //     //                 'api_secret' => ['$exists' => true],
    //     //             ],
    //     //         ],
    //     //         [
    //     //             '$group' => [
    //     //                 '_id' => null,
    //     //                 'user_ids' => ['$push' => ['$toObjectId' => '$user_id']],
    //     //             ],
    //     //         ],
    //     //         [
    //     //             '$project' => [
    //     //                 'user_ids' => 1,
    //     //                 '_id' => 0,
    //     //             ],
    //     //         ],
    //     //     ];

    //     //     $match_users = $connetct->$collection_t->aggregate($pipeline_t);
    //     //     $match_users = iterator_to_array($match_users);
    //     //     if(!empty($match_users)){
    //     //         $match_user_ids = array_column($match_users, 'user_ids');
    //     //         $pipeline[0]['$match']['_id']['$in'] = $match_user_ids; 
    //     //         unset($pipeline[0]['$match']['api_key'], $pipeline[0]['$match']['api_secret']); 
    //     //     }
    //     //     unset($collection_t, $pipeline_t, $match_users, $match_user_ids);
    //     // }

    //     // $users = $connetct->users->aggregate($pipeline);
    //     // $users = iterator_to_array($users);

    //     // if(!empty($users)){
    //     //     $user_ids = $users[0]['user_ids']; 
    //     //     $user_ids = (array) $user_ids;

    //     //     if(empty($user_ids)){
    //     //         return;
    //     //     }

    //     //     $user_ids = array_unique($user_ids);

    //     //     print_r($user_ids);
    //     // }

    // }

    // public function get_total_used_points($user_id, $exchange){

    //     $points_key = $exchange == 'binance' ? "current_trading_points" : "current_trading_points_$exchange";
    //     $trading_points_collection = $exchange == 'binance' ? "trading_points_history" : "trading_points_history_$exchange";

    //     $pipeline = [
    //         [
    //             '$match' => [
    //                 'user_id' => (string) $user_id,
    //                 'action'=> 'deduct',
    //             ],
    //         ],
    //         [
    //             '$group' => [
    //                 '_id' => null,
    //                 'total_consumed'=> ['$sum' => '$points_consumed']
    //             ],
    //         ],
    //         [
    //             '$project' => [
    //                 'total_consumed' => 1,
    //                 '_id' => 0,
    //             ],
    //         ],
    //     ];
    //     $document = $this->mongo_db->customQuery();
    //     $atg_users = $document->$trading_points_collection->aggregate($pipeline);
    //     $atg_users = iterator_to_array($atg_users);
        
    //     print_r($atg_users[0]['total_consumed']);

    //     return;
    // }


    // public function get_total_consumed_trading_points($user_id, $exchange){

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
    //     //     // print_r($response);
    //     //     return $response['points']; 
    //     // }
    //     // return 0;
    // }
    
    // public function get_current_trading_points($user_id, $exchange){

    //     $user_ids = [
    //         "5c091455fc9aadaac61dd110",
    //         "5c0914c3fc9aadaac61dd143",
    //         "5f5b8e72a266e84bee5aaeda",
    //         "5fb96b006008f969bf7d7c5b",
    //         "5d9d9482710a9027ff3da7b2",
    //         "5c091390fc9aadaac61dd0b7",
    //         "5e41ab693bde856ad75877fc",
    //         "5fd8ae12ecb99d435a07786f",
    //         "6023dad5d762f161c8558d05",
    //         "602b929ea62d3d778d5d2714",
    //         "602b925c7461d30bc42ea252",
    //         "5c0912c8fc9aadaac61dd078",
    //         "5c091379fc9aadaac61dd0ad",
    //         "5c091455fc9aadaac61dd110",
    //         "5e6256c3e98b670ea5115643",
    //         "5c09130dfc9aadaac61dd094",
    //         "5c091414fc9aadaac61dd0f3",
    //         "5c091511fc9aadaac61dd167",
    //         "5c0912defc9aadaac61dd081",
    //         "5c091379fc9aadaac61dd0ad",
    //         "5c091535fc9aadaac61dd178",
    //         "5c0915bafc9aadaac61dd1b6",
    //         "5c091307fc9aadaac61dd092",
    //         "5c09140afc9aadaac61dd0ee",
    //         "5c091359fc9aadaac61dd0a1",
    //         "5c0913c4fc9aadaac61dd0ce",
    //         "5c0914f5fc9aadaac61dd15a",
    //         "5d012308fc9aad1f5342e3c2",
    //         "5c0912cefc9aadaac61dd07a",
    //         "5c0914ddfc9aadaac61dd14f",
    //         "5c0915b4fc9aadaac61dd1b3",
    //         "5cc0ffe7fc9aad6e365d0fe2",
    //         "5c0912c8fc9aadaac61dd078",
    //         "5c0912fefc9aadaac61dd090",
    //         "5c091416fc9aadaac61dd0f4",
    //         "5c0912f6fc9aadaac61dd08c",
    //         "5d0123d9fc9aad77804ddbd2",
    //         "5c0914a9fc9aadaac61dd137",
    //         "5c091509fc9aadaac61dd163",
    //         "5c091524fc9aadaac61dd170",
    //         "5d0120acfc9aad892c701a02",
    //         "5c0912bdfc9aadaac61dd073",
    //         "5c0912d2fc9aadaac61dd07c",
    //         "5c091448fc9aadaac61dd10a",
    //         "5d0122befc9aadb6bc0d8a82",
    //         "5c0913cbfc9aadaac61dd0d1",
    //         "5c0914a6fc9aadaac61dd136",
    //         "5d01239dfc9aad5dec0b8182",
    //         "5d012441fc9aada67c548dc2",
    //         "5c0912e0fc9aadaac61dd082",
    //         "5c09139efc9aadaac61dd0bd",
    //         "5c0915a3fc9aadaac61dd1ab",
    //         "5c4652ebfc9aad404e70ed12",
    //         "5d012208fc9aad683b4e2d12",
    //         "5d01236afc9aad4a81317732",
    //         "5c091430fc9aadaac61dd100",
    //         "5c09152ffc9aadaac61dd175",
    //         "5d01213cfc9aad0f9b22a3d2",
    //         "5c091455fc9aadaac61dd110",
    //         "5c0914d9fc9aadaac61dd14d",
    //         "5c09154efc9aadaac61dd184",
    //         "5c0912c5fc9aadaac61dd077",
    //         "5c0912d9fc9aadaac61dd07f",
    //         "5c09130afc9aadaac61dd093",
    //         "5c0914fefc9aadaac61dd15e",
    //         "5c3e1457fc9aad9edd5b14d2",
    //         "5c0912eafc9aadaac61dd087",
    //         "5c09136dfc9aadaac61dd0a8",
    //         "5c0913affc9aadaac61dd0c5",
    //         "5c0914c3fc9aadaac61dd143",
    //         "5d012404fc9aad8b4b16ff52",
    //         "5da5b66bfd52536b6b22ed7a",
    //         "5ddf584fe35f9f7f4e041ed6",
    //         "5e7fb5f417489d16cc63d062",
    //         "5edaef01d5a8fd7f4d75bc24",
    //         "5edf27b8218cad4637477736",
    //         "5f57441138eede01fe6a9f87",
    //         "5f8441049c624b7e434acfec",
    //     ];

    //     // $db = $this->mongo_db->customQuery();
    //     // $users = $db->users->aggregate([
    //     //     [
    //     //         '$match' => [
    //     //             'application_mode' => 'both'
    //     //         ],
    //     //     ],
    //     //     [
    //     //         '$group' => [
    //     //             '_id' => null,
    //     //             'user_ids' => ['$push' => '$_id' ]
    //     //         ],
    //     //     ],
    //     // ]);
    //     // $users = iterator_to_array($users);
    //     // $user_ids = !empty($users) ? $users[0]['user_ids'] : [];
        
    //     echo "<pre>";
    //     // echo "<br>".count($user_ids)."<br>";

    //     // foreach($user_ids as $user_id){
    //         $total_points_buy = $this ->get_total_buy_trading_points((string) $user_id, $exchange);
    //         $total_points_consumed = $this ->get_total_consumed_trading_points((string) $user_id, $exchange);
    //         $curr_points = $total_points_buy - $total_points_consumed; 
    //         echo "$user_id :::::::::::::: $curr_points = $total_points_buy - $total_points_consumed <br>";
    //     // }

    //     return (!is_nan($curr_points) ? $curr_points : 0);
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
    //                 '$and' => [
    //                     [
    //                         'api_key' => ['$exists' => true],
    //                     ],
    //                     [
    //                         'api_key' => ['$nin' => ['', null] ],
    //                     ],
    //                     [
    //                         'api_secret' => ['$exists' => true],
    //                     ],
    //                     [
    //                         'api_secret' => ['$nin' => ['', null] ],
    //                     ],
    //                 ],
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
    
    //                 $email_subject = 'Your trading points has exceeded';
    //                 $email_body = "Your trading points has exceeded. Your current trading points are $curr_points. <br>";
    //                 $email_body .= "Please buy more trading points to keep trading using DigieBot.";
    
    //             }else if($curr_points <= 10){
                    
    //                 $send_mail = true;
    //                 $days_delay = 2;
    
    //                 $email_subject = 'Your trading points are low';
    //                 $email_body = "Your trading points are low. Your current trading points are $curr_points. <br>";
    //                 $email_body .= "When all your trading points are consumed your trading will be stopped. <br>";
    //                 $email_body .= "So please make sure you have enough trading points available to continue uninterrupted trading.";
    
    //             }else if($curr_points <= 30){
    
    //                 $send_mail = true;
    //                 $days_delay = 4;
    
    //                 $email_subject = 'Your trading points are getting low';
    //                 $email_body = "Your trading points are getting low. Your current trading points are $curr_points. <br>";
    //                 $email_body .= "When all your trading points are consumed your trading will be stopped. <br>";
    //                 $email_body .= "So please make sure you have enough trading points available to continue uninterrupted trading.";
    
    //             }else if($curr_points <= 50){
    
    //                 $send_mail = true;
    //                 $days_delay = 7;
    
    //                 $email_subject = 'Your trading points are getting low';
    //                 $email_body = "Your trading points are getting low. Your current trading points are $curr_points. <br>";
    //                 $email_body .= "When all your trading points are consumed your trading will be stopped. <br>";
    //                 $email_body .= "So please make sure you have enough trading points available to continue uninterrupted trading.";
    
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
    //                     // send_mail((string) $user_id, $email_subject, $email_body);
    
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

    // limit script end

    //random_parents_limit_check_script
    public function random_parents_limit_check_script(){

        echo "Start time ".date("d-m-y H:i:s a")."<br><br>";
        
        $exchange = 'binance';

        //empty pick_parents_temp collection
        $this->reset_parents_to_be_picked($exchange);

        $search = ['$expr' => ['$lt' => [ '$daily_buy_usd_worth' , '$daily_buy_usd_limit' ]]];
        
        $collection = $exchange == "binance" ? "daily_trade_buy_limit" : "daily_trade_buy_limit_$exchange";
        $connetct = $this->mongo_db->customQuery();
        $total_count = $connetct->$collection->count($search);

        //get all users who's limit is remaining
        $per_page = 50;
        $num_pages = (int) ceil($total_count / $per_page);


        echo "<br> total pages users  $num_pages<br>";

        if($num_pages > 0){

            //CURL hit hassan API for live levels and coins to set random parents for limit
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "rules.digiebot.com/apiEndPoint/currentActiveCoinsByLevels",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "authorization: coinsstatus_#y7DrszypFQOEZu9ESEEw",
                ),
            ));
            $curl_response = curl_exec($curl);
            curl_close($curl);

            $curl_result = json_decode($curl_response, true);
            $level_res_arr = [];
            if($curl_result['status'] == 200 && !empty($curl_result['result'])){
                $level_res_arr = $curl_result['result'];
                unset($curl_result['result']);
                shuffle($level_res_arr);
            }

            // echo "<pre>";
            // print_r($level_res_arr);
            // die('<br> *********** testing ************ </br>');

            //loop through users (who's limit is remaining) for a chunk
            for($i=1; $i<=$num_pages; $i++){
                
                $page = $i;
                $skip = ($page - 1) * $per_page;
                $limit = $per_page;
                
                $pending_options = array('skip' => $skip, 'limit' => $limit);
                $curser = $connetct->$collection->find($search, $pending_options);
                $pending_limit_arr = iterator_to_array($curser);

                if (!empty($pending_limit_arr)) {
                    $pending_limit_user_ids = array_column($pending_limit_arr, 'user_id');

                    $user_limit_arr = [];
                    $user_count = count($pending_limit_arr);
                    for($j=0; $j<$user_count; $j++){
                        $user_id = $pending_limit_arr[$j]['user_id'];
                        $user_limit_arr[$user_id] = [
                            'daily_buy_usd_worth'=> number_format($pending_limit_arr[$j]['daily_buy_usd_worth'], 2),
                            'daily_buy_usd_limit'=> number_format($pending_limit_arr[$j]['daily_buy_usd_limit'], 2)
                        ];
                    }
                    // echo "<pre>";
                    // print_r($pending_limit_user_ids);
                    // print_r($user_limit_arr);
                    // break;
                    unset($curser, $pending_limit_arr);

                    if(!empty($level_res_arr)){
                        $total_levels = count($level_res_arr);
                        for($j=0; $j<$total_levels; $j++){
                            
                            //make all parents pick_parents = 'yes' to 'no'; 
                            // $this->make_pick_parents_yes_to_no($pending_limit_user_ids, $exchange);
        
                            //pick 100 random parents in loop for these users and group by symbol admin id so don't have to repeat for balance
                            //Count total parents for this iteration 
        
                            //set current parent filter
                            $curr_level = $level_res_arr[$j]['level_name'];
                            $curr_symbol = $level_res_arr[$j]['coin'];
                            $curr_limit = $level_res_arr[$j]['limit'];
        
                            $buy_collection = $exchange == "binance" ? "buy_orders": "buy_orders_$exchange";
                            $piepline_total_count = [ 
                                [ 
                                    '$match'=> [ 
                                        'admin_id' => ['$in'=>$pending_limit_user_ids],
                                        'symbol' => $curr_symbol,
                                        'order_level' => $curr_level,
                                        'application_mode'=>'live',
                                        'parent_status'=>'parent', 
                                        'status'=> 'new',
                                        'pause_status' => 'play',
                                        'usd_worth' => ['$exists'=>true],
                                        'check_parent_limit' => ['$nin'=>['no', 'ignore']],
                                    ]
                                ],
                                [
                                    '$count'=> 'total_parents_of_current_iteration'
                                ]
                            ];
                            $result_piepline_total_count = $connetct->$buy_collection->aggregate($piepline_total_count);
                            $result_piepline_total_count = iterator_to_array($result_piepline_total_count);
                            // echo "<pre>";
                            // print_r($result_piepline_total_count);

                            if(!empty($result_piepline_total_count)){
                                $total_parents_of_current_iteration = $result_piepline_total_count[0]['total_parents_of_current_iteration'];

                                //for loop for all parents for all user for every level and every coin
                                $parents_per_page = 25;
                                $num_parents_pages = (int) ceil($total_parents_of_current_iteration / $parents_per_page);
                                for($k=0; $k<$num_parents_pages; $k++){
                                    $parents_page = $k;
                                    $parent_skip = ($parents_page - 1) * $parents_per_page;
                                    $parent_limit = $parents_per_page;

                                    $piepline_parents_per_page = [
                                        [
                                            '$match' => [
                                                'admin_id' => ['$in' => $pending_limit_user_ids],
                                                'symbol' => $curr_symbol,
                                                'order_level' => $curr_level,
                                                'application_mode' => 'live',
                                                'parent_status' => 'parent',
                                                'status' => 'new',
                                                'pause_status' => 'play',
                                                'usd_worth' => ['$exists' => true],
                                                'check_parent_limit' => ['$nin'=>['no', 'ignore']],
                                            ],
                                        ],
                                        [
                                            '$project' => [
                                                '_id' => 1,
                                                'admin_id' => 1,
                                                'symbol' => 1,
                                                'usd_worth' => 1,
                                            ],
                                        ],
                                        [
                                            '$limit' => $parent_limit,
                                        ],
                                    ];
                                    $result_parents_per_page = $connetct->$buy_collection->aggregate($piepline_parents_per_page);
                                    $result_parents_per_page = iterator_to_array($result_parents_per_page);

                                    //now check limit and insert into the temp collection from where we will grab ids and update //in buy orders collection
                                    if(!empty($result_parents_per_page)){

                                        $parent_ids_that_pass_limit_check = [];
                                        $parent_ids_that_fail_limit_check = [];
                                        $total_result_parents_per_page = count($result_parents_per_page);

                                        $curr_user_id = '';
                                        for($l=0; $l < $total_result_parents_per_page; $l++){

                                            $curr_user_id = $result_parents_per_page[$l]['admin_id'];
                                            
                                            $curr_user_buy_worth = $user_limit_arr[$curr_user_id]['daily_buy_usd_worth'] + $result_parents_per_page[$l]['usd_worth'];

                                            $curr_user_buy_limit = $user_limit_arr[$curr_user_id]['daily_buy_usd_limit'];

                                            // echo "$curr_user_buy_worth <= $curr_user_buy_limit <br>";

                                            if($curr_user_buy_worth <= $curr_user_buy_limit){
                                                //buy limit pass
                                                $parent_ids_that_pass_limit_check[] = $result_parents_per_page[$l]['_id'];
                                            }else{
                                                //buy limit fail
                                                $parent_ids_that_fail_limit_check[] = $result_parents_per_page[$l]['_id'];
                                            }
                                        }

                                        //pick_parent 
                                        $this->set_parents_to_be_picked($parent_ids_that_pass_limit_check, $exchange);
                                        //drop parents
                                        $this->drop_parents($parent_ids_that_fail_limit_check, $exchange);
                                    }

                                    unset($result_parents_per_page);
                                }

                            }
                            // break;

                        }//levels array loop end 
                    }else{
                        break;
                    }
                }//end pending_limit_arr for first set of users
                // break;

            }//end loop through users (who's limit is remaining) for a chunk
        }else{
            //make all parents pick_parents = 'yes' to 'no'; 
        }

        echo "<br><br>End time " . date("d-m-y H:i:s a") . "<br><br>";

        echo "<br> ************** Script End *************** <br> ";

    }//End random_parents_limit_check_script

    public function make_pick_parents_yes_to_no($user_ids=[], $exchange){
        if(!empty($user_ids) && !empty($exchange)){
            $search = [
                'admin_id' =>[
                    '$in' => $user_ids
                ],
                'application_mode' => 'live',
                'symbol' => ['$nin'=>['NCASHBTC', 'POEBTC']],
                'parent_status' => 'parent',
                'status' => ['$ne'=>'canceled'],
                // 'pause_status' => 'play',
            ];
            $update = [
                '$set' => [
                    'pick_parent' => 'no',
                ]
            ];
            $collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
            $connetct = $this->mongo_db->customQuery();
            $connetct->$collection->updateMany($search, $update);
            unset($user_ids, $search, $update, $connetct, $exchange);
        }
        return true;
    }
    
    public function drop_parents($parent_ids=[], $exchange){

        if(!empty($parent_ids) && !empty($exchange)){
            // echo "<br> drop parents <br>";
            $search = [
                '_id' =>[
                    '$in' => $parent_ids
                ]
            ];
            $update = [
                '$set' => [
                    'check_parent_limit' => 'no',
                    'pick_parent' => 'no',
                ]
            ];
            $collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
            $connetct = $this->mongo_db->customQuery();
            $connetct->$collection->updateMany($search, $update);
            unset($parent_ids, $search, $update, $connetct, $exchange);
        }
        return true;
    }
    
    public function set_parents_to_be_picked($parent_ids=[], $exchange){
        if(!empty($parent_ids) && !empty($exchange)){

            // echo "<br> set_parents_to_be_picked <br>";

            // $collection = $exchange == "binance" ? "pick_parents_temp" : "pick_parents_temp_$exchange";
            // $insert = ['parent_ids'=>$parent_ids]; 
            // $connetct = $this->mongo_db->customQuery();
            // $connetct->$collection->insertOne($insert);
            // unset($parent_ids, $insert, $connetct, $exchange);
            
            $search = [
                '_id' =>[
                    '$in' => $parent_ids
                    ]
                ];
            $update = [
                '$set' => [
                    'check_parent_limit' => 'ignore',
                    'pick_parent' => 'yes',
                ],
            ];
            $connetct = $this->mongo_db->customQuery();
            $collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
            $connetct->$collection->updateMany($search, $update);
            unset($parent_ids, $search, $update, $connetct, $exchange);
        }
        return true;
    }

    public function reset_parents_to_be_picked($exchange){
        
        $connetct = $this->mongo_db->customQuery();
        
        // $collection = $exchange == "binance" ? "pick_parents_temp" : "pick_parents_temp_$exchange";
        // $connetct->$collection->deleteMany([]);

        $search = [
            'application_mode' => 'live',
            'symbol' => ['$nin' => ['NCASHBTC', 'POEBTC']],
            'parent_status' => 'parent',
            'status' => ['$ne'=>'canceled'],
            // 'status' => 'new',
            // 'pause_status' => 'play',
        ];
        $update = [
            '$unset' => [
                'check_parent_limit' => '',
                'pick_parent' => '',
            ],
        ];
        $collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $connetct->$collection->updateMany($search, $update);

        return;
    }

    // limit script end


    //calculate current trading points
    public function calculate_current_trading_points(){

        echo "cron already set";
        return;

        // echo "<pre> testing ";
        $connetct = $this->mongo_db->customQuery();

        //get binance users with API key secret
        $exchange = "binance";
        $collection = $exchange == "binance" ? "users" : $exchange."_credentials";

        $pipeline = [
            [
                '$match'=>[
                    'api_key'=>['$exists'=>true],
                    'api_secret'=>['$exists'=>true],
                    'application_mode'=>'both'
                ]
            ],
            [
                '$group'=>[
                    '_id'=>null,
                    'user_ids'=>['$push'=>['$toString'=>'$_id']]
                ]
            ],
            [
                '$project'=>[
                    'user_ids'=>1,
                    '_id'=>0,
                ]
            ]
        ];

        //only pick user with api_secret credentials for exchanges
        if($exchange != 'binance' && $exchange != ''){
          $collection_t = $exchange . "_credentials";
            $pipeline_t = [
                [
                    '$match' => [
                        'api_key' => ['$exists' => true],
                        'api_secret' => ['$exists' => true],
                    ],
                ],
                [
                    '$group' => [
                        '_id' => null,
                        'user_ids' => ['$push' => ['$toObjectId' => '$user_id']],
                    ],
                ],
                [
                    '$project' => [
                        'user_ids' => 1,
                        '_id' => 0,
                    ],
                ],
            ];

            $match_users = $connetct->$collection_t->aggregate($pipeline_t);
            $match_users = iterator_to_array($match_users);
            if(!empty($match_users)){
                $match_user_ids = array_column($match_users, 'user_ids');
                $pipeline[0]['$match']['_id']['$in'] = $match_user_ids; 
                unset($pipeline[0]['$match']['api_key'], $pipeline[0]['$match']['api_secret']); 
            }
            unset($collection_t, $pipeline_t, $match_users, $match_user_ids);
        }

        $users = $connetct->users->aggregate($pipeline);
        $users = iterator_to_array($users);

        if(!empty($users)){
            $user_ids = $users[0]['user_ids']; 
            $user_ids = (array) $user_ids;

            if(empty($user_ids)){
                return;
            }

            shuffle($user_ids);

            $total_users = count($user_ids);
            //for loop on users
            for($i=0; $i<$total_users; $i++){
                $user_id = $user_ids[$i];
                
                // $user_id = '5c0912b7fc9aadaac61dd072';//admin
                // $user_id = '5c867e3ffc9aad347e165d32';//live user

                $buyCollection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
                $soldCollection = $exchange == "binance" ? "sold_buy_orders" : "sold_buy_orders_$exchange";

                $parents_pipeline = [
                    [
                        '$match' => [
                            'admin_id'=> $user_id,
                            'application_mode'=> 'live',
                            'parent_status'=> 'parent',
                            'status'=> [
                                '$ne'=> 'canceled'
                            ]
                        ]
                    ],
                    [
                        '$group' => [
                            '_id' => null,
                            'parent_ids' => ['$push' => '$_id'],
                        ],
                    ],
                    [
                        '$project' => [
                            'parent_ids' => 1,
                            '_id' => 0,
                        ],
                    ],
                ];
                $user_parent_ids =  $connetct->$buyCollection->aggregate($parents_pipeline);
                $user_parent_ids = iterator_to_array($user_parent_ids);
                if(!empty($user_parent_ids)){
                    $user_parent_ids = array_column($user_parent_ids, 'parent_ids');

                    $user_parent_ids = $user_parent_ids[0];

                    $endTime = date('Y-m-d H:59:59');
                    $startTime = date('Y-m-d H:00:00', strtotime('-24 hours'));
                    
                    $startTime = $this->mongo_db->converToMongodttime($startTime);
                    $endTime =  $this->mongo_db->converToMongodttime($endTime);

                    $childsBuyPipeline = [
                        [
                            '$match'=> [
                                'buy_parent_id'=> ['$in'=> $user_parent_ids],
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
                                    '$gte'=> $startTime, 
                                    '$lte'=> $endTime
                                ],
                            ]
                        ],
                        [ 
                            '$project'=> [ 
                                "symbol"=> 1,
                                "quantity"=> 1,
                                "purchased_price"=> 1,
                            ]
                        ]
                    ];
                    $buy_orders = $connetct->$buyCollection->aggregate($childsBuyPipeline);
                    $buy_orders = iterator_to_array($buy_orders);
        
                    $sold_orders = $connetct->$soldCollection->aggregate($childsBuyPipeline);
                    $sold_orders = iterator_to_array($sold_orders);
                    $orders = array_merge($buy_orders, $sold_orders);
                    // echo count($orders)."-----------".count($buy_orders)."---------------".count($sold_orders)."<br>";
                    unset($buy_orders, $sold_orders);

                    $BTCUSDT_price = $this->get_market_price('BTCUSDT', $exchange);
                    
                    if(!empty($orders) && !empty($BTCUSDT_price)){
                        //get current market_price for BTCUSDT
                        
                        $total_usd_buy_worth = 0;
                        $order_count = count($orders);
                        //for loop on orders to find total buy worth
                        for($j=0; $j<$order_count; $j++){
                            
                            $tarr = explode('USDT', $orders[$j]['symbol']);
                            if (isset($tarr[1]) && $tarr[1] == '') {
                                // echo "\r\n USDT coin";
                                $usd_balance = $orders[$j]['quantity'] * $orders[$j]['purchased_price'];
                                $usd_balance = number_format($usd_balance, 2);
                            } else {
                                // echo "\r\n BTC coin";
                                $usd_balance = $orders[$j]['quantity'] * $orders[$j]['purchased_price'] * $BTCUSDT_price;
                                $usd_balance = number_format($usd_balance, 2);
                            }
                            $total_usd_buy_worth += (!is_nan($usd_balance) ? $usd_balance : 0);
                        }

                        // echo "today buy_usd_worth $ $total_usd_buy_worth <br>";

                        // find points consumed today $100 = 1 point
                        $pointUsdVal = 100;
                        $points_consumed = $total_usd_buy_worth / $pointUsdVal;
                        $points_consumed = number_format($points_consumed, 2);
                        $this->update_trading_points($user_id, $exchange, $points_consumed);
                        //update current trading points 
                    }

                }
                // break;
            }
        }

        echo "<br><br> ********************** End Script **************************** <br>";
        return;
    }//End calculate current trading points


    //get_market_prices
    public function get_market_price_bk($coinsArr, $exchange){

        $connetct = $this->mongo_db->customQuery();

        $collection = ($exchange == 'binance') ? 'market_prices' : 'market_prices_'.$exchange;
        $pipeline = [
            [
                '$match' => [
                    'coin' => ['$in' => $coinsArr],
                ]
            ],
            [
                '$project' => [
                    "symbol" => 1,
                    "quantity" => 1,
                    "purchased_price" => 1,
                ],
            ],
            [
                '$group'=> [
                    '_id'=> '$coin', 
                    'price'=> ['$last'=> '$price'], 
                    'coin'=> ['$last'=> '$coin'] 
                ] 
            ], 
            [
                '$sort'=> ["created_date"=> -1 ] 
            ],
            [
                '$project'=>[
                    'coin'=>1,
                    'price'=>1,
                    '_id'=>0,
                ]
            ]
        ];
        $prices = $connetct->$collection->aggregate($pipeline);
        $prices = iterator_to_array($prices);

        if(!empty($prices)){
            $prices = array_column($prices, 'coin', $prices);
            return $prices; 
        }
        return [];
    }//end get_market_price

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

    //     $points_key = $exchange == 'binance' ? "current_trading_points" : "current_trading_points_$exchange";

    //     $user_id = $this->mongo_db->mongoId($user_id);
    //     $connetct = $this->mongo_db->customQuery();
    //     $user = $connetct->users->find(['_id'=>$user_id], [$points_key =>1]);
    //     $user = iterator_to_array($user);
        
    //     if(!empty($user)){
            
    //         $previous_points = $user[0][$points_key];

    //         $user_id = (string) $user_id;
    //         // echo "$user_id, $previous_points , $points_consumed";
    //         // return;

    //         //Save history entry
    //         $trading_points_collection =  $exchange == 'binance' ? "trading_points_history" : "trading_points_history_$exchange";
    //         $insert_data = [
    //             'user_id'=> (string) $user_id,
    //             'action'=> 'deduct',
    //             'points_consumed'=> (float) $points_consumed,
    //             'previous_points'=> (float) $previous_points,
    //             'created_date'=> date("Y-m-d H:i:s"),
    //         ];
    //         $connetct->$trading_points_collection->insertOne($insert_data);

    //         //Deduct daily points consumed
    //         $curr_points = $previous_points - $points_consumed;
    //         $curr_points = number_format($curr_points,2);
    //         $curr_points = $curr_points < 0 ? 0 : $curr_points;

    //         $update_data = [
    //             '$set' => [
    //                 $points_key => (float) $curr_points,
    //             ]
    //         ];
    //         $user_id = $this->mongo_db->mongoId($user_id);
    //         $connetct->users->updateOne(['_id'=>$user_id], $update_data);
    //     }
    //     return;

    // }//End update_trading_points


    public function index() {

        //$where['type'] = 'buy_submitted';
        $get_obj = $this->mongo_db->get('users');
        $buy_orders = iterator_to_array($get_obj);
        echo "<pre>";
        print_r($buy_orders);

        exit;
    }

    public function tahir() {

        echo "ok";
        exit;
    }
    
    public function buy_order_map($id = ''){

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set(array('status' => 'canceled'));
        $this->mongo_db->update('buy_orders');
        echo "<pre> ok";
        exit;

    }

    public function sold_order_map($id = ''){

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set(array('status' => 'canceled'));
        $this->mongo_db->update('sold_buy_orders');
        echo "<pre> ok";
        exit;

    }

    public function buy_order($id = '') {
        $this->mongo_db->where(array('_id' => $id));
        $row = $this->mongo_db->get('buy_orders');
        $data = iterator_to_array($row);
        echo '<pre>';
        print_r($data);
    }

    public function sold_buy_order($id = '') {
        $this->mongo_db->where(array('_id' => $id));
        $row = $this->mongo_db->get('sold_buy_orders');
        $data = iterator_to_array($row);
        echo '<pre>';
        print_r($data);
    }
    public function get_binance_balance($user_id) {
        $balance_arr = $this->binance_api->get_account_balance($user_id);
        echo "<pre>";
        print_r($balance_arr);
        die("\r\n************ End Script ***********");
    }
    public function get_binance_balanceNew($user_id) {

        $coinArray = ['BTC', 'LTC', 'ETH', 'NEO', 'BNB', 'QTUM', 'EOS', 'USDT', 'TRX' , 'LINK', 'ETC' ,'DASH', 'XMR', 'XRP', 'POE', 'ADA', 'XLM', 'NCASH' , 'XEM', 'ZEN'];
        $where['api_secret'] = ['$exists' => true];
        $where['api_key']    = ['$exists' => true];
        $cond = [['sort'=> ['created_date'=> -1 ]],['$limit' => 100]];
        // $marketPricesArray = get_current_market_prices('binance');
        // foreach($marketPricesArray as $key => $pr){
        //     if($key == 'BTCUSDT'){
        //         $btcPrice = $pr;
        //         echo "<br>BTCUSDT: ".$btcPrice;
        //     }
        // }
        $db = $this->mongo_db->customQuery();
        $usersDetail = $db->users->find($where, $cond);
        $returnUser  = iterator_to_array($usersDetail);
        unset($usersDetail);

        foreach( $returnUser as $user123){
            $id = (string)$user123['_id'];
            echo "<br>Username: ".$user123['username'];
            echo "<br>user_id: ". $id;
            $balance_arr = $this->binance_api->get_account_balance($id);
            $btcTotal = 0;
            if(count($balance_arr) > 0){
                foreach($balance_arr as $key=>$prices){
                   if(! in_array($key ,$coinArray) && $prices['available'] > 0){
                    //    $btcTotal += $btcPrice / $prices['available'];
                       echo "<br>Coin: ".$key;
                       echo "<br>Value: ".$prices['available'];
                    //    echo "<br>Value in BTC: ".($btcPrice / $prices['available']);
                       echo "<br> ===============================================================";
                   }
                }
            }
            // echo "<br>Total BTC :".$btcTotal;
            echo "<br>NEXT User Start";
            sleep(5);
        }
      
    }
    
    public function get_item($collection, $order_id) {
        
        // print_r("$collection ----- $order_id");
        // die("$collection ----- $order_id");

        $where['_id'] = $order_id;
        $this->mongo_db->where($where);
        $get_obj = $this->mongo_db->get($collection);
        $orders = iterator_to_array($get_obj);
        
        echo "<pre>";
        print_r($orders);
        die("\r\n************ End Script ***********");
     
    }
    
    public function dump_item($collection, $order_id) {
        
        $where['_id'] = $order_id;
        $this->mongo_db->where($where);
        $get_obj = $this->mongo_db->get($collection);
        $orders = iterator_to_array($get_obj);
        
        echo "<pre>";
        var_dump($orders);
        die("\r\n************ End Script ***********");
     
    }
    
    public function delete_item($collection, $order_id, $test='try') {
       
        if($test == 'permanent'){

            $this->mongo_db->where(array('_id' => $order_id));
            $this->mongo_db->delete($collection);
        }
		
        echo "<pre>";
        echo "One Item Delted \r\n";
        echo "Collection: $collection \r\n";
        echo "_id: $order_id \r\n";
        die("\r\n************ End Script ***********");
     
    }

    public function get_items($collection){

        $this->mongo_db->limit(50);
        $this->mongo_db->order_by(array('_id' => -1));
        $get_obj = $this->mongo_db->get($collection);
        $orders = iterator_to_array($get_obj);
        
        echo "<pre>";
        print_r($orders);
        die("\r\n************ End Script ***********");
     
    }

    public function mytest(){
        $data = array();
        //Umer Abbas [30-10-19]
        $this->load->model('admin/mod_settings');
        $trading_on_Off = $this->mod_settings->get_saved_on_off_trading();

        foreach ($trading_on_Off as $row) {
            if($row['type'] == 'automatic_on_of_trading'){
                $data['trading_status']['auto_trading_status'] = $row['status'];
            }
            if($row['type'] == 'custom_on_of_trading'){
                $data['trading_status']['custom_trading_status'] = $row['status'];;
            }
        }//End of foreach trading

        echo '<pre>';
        print_r($data);die('end');
    }

    //insert_order_history_log
    public function insert_order_history_log($id, $log_msg, $type, $show_error_log, $user_id){

        $created_date = date('Y-m-d G:i:s');

        $ins_error = array(
            'order_id' => $this->mongo_db->mongoId($id),
            'log_msg' => $log_msg,
            'type' => $type,
            'show_error_log' => $show_error_log,
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
        );
        $this->mongo_db->insert('orders_history_log', $ins_error);
        return true;
    }

    //move_orders_to_cancelled //Umer Abbas [1-11-19]
	public function move_orders_to_cancelled($order_id) {

        $order_ids = array(
            $this->mongo_db->mongoId($order_id),
        );
		$update_arr = array(
            'status' => 'canceled'
        );

        $db = $this->mongo_db->customQuery();
        $response = $db->buy_orders_bam->updateMany(array('_id' => array('$in' => $order_ids)), array('$set' => $update_arr));
        if($response){
            echo 'moved to canceled successfully <br>';
        }else{
            echo 'failed <br>';
        }

        // foreach($order_ids as $id){

        //     $this->mongo_db->where(array('_id' => $id));
        //     //$this->mongo_db->set($update_arr);
        //     $update = $this->mongo_db->get('buy_orders');

        //     echo "<pre>";
        //     print_r(iterator_to_array($update));
    
        //     if($update == true){
        //         echo $id.': moved to canceled successfully <br>';
        //     }else{
        //         echo $id.': failed <br>';
        //     }
        // }
		die('end');
    }//end move_orders_to_cancelled
    
    //update_order //Umer Abbas [11-12-19]
	public function update_order() {

        $order_ids = array(
            $this->mongo_db->mongoId('5dc1c0d8a3a9fc001918099d'),
        );
		$update_arr = array(
            'un_limit_child_orders' => 'no'
        );

        $db = $this->mongo_db->customQuery();
        $response = $db->buy_orders_bam->updateMany(array('_id' => array('$in' => $order_ids)), array('$set' => $update_arr));
        if($response){
            echo 'updated successfully <br>';
        }else{
            echo 'failed <br>';
        }
		die('end');
    }//end update_order

    //archive_all_parent_orders //Umer Abbas [5-11-19]
	public function archive_all_parent_orders($preview='preview', $try='try') {
        
        $where = array(
            'parent_status' => 'parent',
            'application_mode' => 'live',
        );

        // $limit = array('limit' => 1);

        $db = $this->mongo_db->customQuery();
        // $result = $db->buy_orders->find($where, $limit);
        $result = $db->buy_orders->find($where);
        
        $result = iterator_to_array($result);

        if($preview == 'run'){

            if(!empty($result)){

                $update_arr = array(
                    'status' => 'canceled',
                    'parent_status' => 'parent_canceled'
                );

                $this->load->model('admin/mod_barrier_trigger');
                echo 'Total parent orders: '.count($result).'<br>';
                $log_msg = 'Archived forcefully due to the fresh release done by Abbas.';

                $i = 0;
                foreach($result as $parent_order){
                    
                    $updt_where = array('_id' => $parent_order['_id']);
                    $db = $this->mongo_db->customQuery();
                    $response = $db->buy_orders->updateMany($updt_where, array('$set' => $update_arr));

                    if($response){
                        $i++;
                        $created_date = date('Y-m-d H:i:s');

                        $this->mod_barrier_trigger->insert_order_history_log($parent_order['_id'], $log_msg, 'paretn_status_updated', $parent_order['admin_id'], $created_date);

                    }else{
                        echo 'an error occured.<br>';
                        echo 'total archived: '.$i.'<br>';
                        break; 
                    }

                    if($try=='try'){

                        echo 'order_id: '.$parent_order['_id'].'<br>';
                        echo 'Try 1 <br>';
                        echo 'total archived: '.$i.'<br>';
                        break;
                    }
                }

            }else{
                echo 'no parent orders found. <br>';
            }
        }else{
            if(!empty($result)){
                echo '<pre>';
                echo 'Total Parent Orders: '.count($result).'<br><br>';
                print_r($result[0]);
            }else{
                echo 'no parent orders found. <br>';
            }
        }

		echo '<br>';
		die('***********  end script ************');
    }//end archive_all_parent_orders

    //move_orders_to_error //Umer Abbas [11-11-19]
	public function move_orders_to_error() {

        $order_ids = array(

            //rosemarie
            $this->mongo_db->mongoId('5dc4e2fa4aacde001a4a9494'),
            $this->mongo_db->mongoId('5dc4e5704aacde001a4a94b6'),
        );
		$update_arr = array(
            'status' => 'error'
        );

        $db = $this->mongo_db->customQuery();
        $response = $db->buy_orders->updateMany(array('_id' => array('$in' => $order_ids)), array('$set' => $update_arr));
        if($response){
            echo 'moved to error successfully <br>';
        }else{
            echo 'failed <br>';
        }
        
		die('complete: status set to error');
    }//end move_orders_to_error

    //orders_with_no_symbol //Umer Abbas [2-12-19]
	public function orders_with_no_symbol($action='print') {

		$where_arr = array(
            'symbol' => ['$in' => ['', null, NULL]]
        );

        $this->mongo_db->where($where_arr);
        $data2 = $this->mongo_db->get('buy_orders');
        $data_Arra2 = iterator_to_array($data2);
        echo '<pre>';
        echo "Number of orders  with no symbol: ".count($data_Arra2)."\r\n";
        print_r($data_Arra2);

        if($action == 'delete'){
            $db = $this->mongo_db->customQuery();
            $response = $db->buy_orders->deleteMany($where_arr);
            if($response){
                echo 'Deleted successfully <br>';
            }else{
                echo 'failed <br>';
            }
        }
        
		die("action=$action \r\n ************ Script end: delete_orders_with_no_symbol ***********");
    }//end orders_with_no_symbol
    
    //add_global_coins //Umer Abbas [12-11-19]
	public function add_global_coins() {
        die('remove die first from the code');
        $coins_arr = array(
            array(
                'user_id' => 'global',
                'coin_name' => 'Bitcoin Original',
                'symbol' => 'BTCUSDT',
                'coin_logo' => 'bitcoin_PNG47.png',
                'exchange_type' => 'coinbasepro',
            ),
        );

        foreach($coins_arr as $coin){
            $response = $this->mongo_db->insert('coins', $coin);
        }
        if($response){
            echo 'global coins added successfully <br>';
        }else{
            echo 'failed <br>';
        }

		die('end script');
    }//end add_global_coins

    public function confirm_order_records_stats($user_id=null){
        
        if(!empty($user_id)){
            
            echo '<pre>';
            //total sold
            $where_arr = array(
                'user_id' => $user_id,
                'is_sell_order' => 'sold',
                'status' => 'FILLED',
            );
            $db = $this->mongo_db->customQuery();
            $response = $db->buy_orders->find($where_arr);
            $response = iterator_to_array($response);

            echo "\r\nsold:";
            print_r($response);
            
            //total sold
            $where_arr = array(
                'user_id' => $user_id,
            );
            $db = $this->mongo_db->customQuery();
            $response = $db->buy_orders->find($where_arr);
            $response = iterator_to_array($response);
    
            echo "\r\ntotal:";
            print_r($response);
        }
        
        die('end script');
    }

    //TODO: find_open_order_with_no_sell_order //Umer Abbas [26-11-19]
    public function find_open_order_with_no_sell_order($user_id=''){

        ini_set("display_errors", E_ALL);
        error_reporting(E_ALL);

        $allow = array('allowDiskUse' => true);
        $fields = [
            "_id" => 1,
            "price" => 1,
            "quantity" => 1,
            "symbol" => 1,
            "order_type" =>  1,
            "admin_id" =>  1,
            "trigger_type" =>  1,
            "sell_price" =>  1,
            "created_date" => 1, 
            "modified_date" => 1, 
            "buy_date" => 1, 
            "buy_parent_id" => 1, 
            "iniatial_trail_stop" =>  1,
            "application_mode" =>  1,
            "order_mode" =>  1,
            "defined_sell_percentage" =>  1,
            "order_level" =>  1,
            "sell_profit_percent" =>  1,
            "lth_functionality" =>  1,
            "lth_profit" =>  1,
            "secondary_stop_loss_rule" => 1, 
            "secondary_stop_loss_status" => 1, 
            "activate_stop_loss_profit_percentage" => 1,
            "stop_loss_rule" =>  1,
            "deep_price_on_off" =>  1,
            "deep_price_percentage_buy" => 1,
            "cancel_order_on_off" =>  1,
            "cancel_order_hours_range_buy" =>  1,
            "purchased_price" =>  1,
            "status" =>  1,
            "trading_ip" =>  1,
            "expecteddeepPrice" =>  1,
            "binance_order_id" =>  1,
            "clientOrderId" =>  1,
            "market_value" =>  1,
            "tradeId" =>  1,
            "transactTime" =>  1,
            "is_sell_order" =>  1,
            "sell_order_id" => 1, 
            "market_value_usd" => 1,
            "is_lth_order" => 1,
            "market_heighest_value" => 1,
            "market_lowest_value" => 1,
        ];
        $where = [];
        // $where['status'] = ['$in' => ['submitted', 'FILLED']];
        // $where['admin_id'] = null;
        $where['created_date']['$gte'] = $this->mongo_db->converToMongodttime(date("2019-11-23 00:00:00"));
        
        // $join = [
        //     'from' => 'buy_orders',
        //     'localField' => '_id',
        //     'foreignField' => 'sell_order_id',
        //     'as' => 'buy_order',
        // ];
        
        $join = [
            'from' => 'buy_orders',
            'localField' => 'buy_order_id',
            'foreignField' => '_id',
            'as' => 'buy_order',
        ];
        
        $query = [
            ['$lookup' => $join],
            ['$match' => $where],
            ['$sort' => ['created_date' => 1]],
            ['$limit' => 100],
        ];

        $db = $this->mongo_db->customQuery();
        $response = $db->orders->aggregate($query);
        $records = iterator_to_array($response);

        echo '<pre>';
        // print_r("total sell orders: ".count($records)."\r\n");
        print_r($records);
    
        die('<br>**************** End Script ****************');
            
    }//end find_open_order_with_no_sell_order
    
    //TODO: find_open_buy_orders_with_sell_orders //Umer Abbas [27-11-19]
    public function find_open_buy_orders_with_sell_orders($user_id=''){

        ini_set("display_errors", E_ALL);
        error_reporting(E_ALL);

        $allow = array('allowDiskUse' => true);
        $fields = [
            "_id" => 1,
            "price" => 1,
            "quantity" => 1,
            "symbol" => 1,
            "order_type" =>  1,
            "admin_id" =>  1,
            "trigger_type" =>  1,
            "sell_price" =>  1,
            "created_date" => 1, 
            "modified_date" => 1, 
            "buy_date" => 1, 
            "buy_parent_id" => 1, 
            "iniatial_trail_stop" =>  1,
            "application_mode" =>  1,
            "order_mode" =>  1,
            "defined_sell_percentage" =>  1,
            "order_level" =>  1,
            "sell_profit_percent" =>  1,
            "lth_functionality" =>  1,
            "lth_profit" =>  1,
            "secondary_stop_loss_rule" => 1, 
            "secondary_stop_loss_status" => 1, 
            "activate_stop_loss_profit_percentage" => 1,
            "stop_loss_rule" =>  1,
            "deep_price_on_off" =>  1,
            "deep_price_percentage_buy" => 1,
            "cancel_order_on_off" =>  1,
            "cancel_order_hours_range_buy" =>  1,
            "purchased_price" =>  1,
            "status" =>  1,
            "trading_ip" =>  1,
            "expecteddeepPrice" =>  1,
            "binance_order_id" =>  1,
            "clientOrderId" =>  1,
            "market_value" =>  1,
            "tradeId" =>  1,
            "transactTime" =>  1,
            "is_sell_order" =>  1,
            "sell_order_id" => 1, 
            "market_value_usd" => 1,
            "is_lth_order" => 1,
            "market_heighest_value" => 1,
            "market_lowest_value" => 1,
        ];
        $where = [];
        $where['status'] = ['$in' => ['submitted', 'FILLED']];
        $where['created_date']['$gte'] = $this->mongo_db->converToMongodttime(date("2019-11-23 00:00:00"));
        
        $join = [
            'from' => 'orders',
            'localField' => '_id',
            'foreignField' => 'sell_order_id',
            'as' => 'sell_orders',
        ];
        
        $query = [
            ['$lookup' => $join],
            ['$match' => $where],
            ['$sort' => ['created_date' => 1]],
            ['$limit' => 100],
        ];

        $db = $this->mongo_db->customQuery();
        $response = $db->buy_orders->aggregate($query);
        $records = iterator_to_array($response);

        echo '<pre>';
        // print_r("total sell orders: ".count($records)."\r\n");
        print_r($records);
    
        die('<br>**************** End Script ****************');
            
    }//end find_open_buy_orders_with_sell_orders


    //TODO: buy_orders_script find open orders with no sell order //Umer Abbas [27-11-19]
    public function buy_orders_script(){ 

        $request = $this->input->get();
        $exchange = (!empty($request['exchange']) ? $request['exchange'] : '');
        if(!empty($exchange)){
            if($exchange == 'binance'){
                $exchange = '';
            }
        }
        $user_id = (!empty($request['user_id']) ? $request['user_id'] : null);
        $symbol = (!empty($request['coin']) ? $request['coin'] : null);
        $mode = (!empty($request['mode']) ? $request['mode'] : null);
        // $start_date = (!empty($request['sdt']) ? $request['sdt'] : '2019-11-01');
        $start_date = (!empty($request['sdt']) ? $request['sdt'] : date("Y-m-d G:i:s", strtotime("-30 day", strtotime(date('Y-m-d G:i:s')))));
        $end_date = (!empty($request['ndt']) ? $request['ndt'] : date('Y-m-d G:i:s')); //if no date is provided then use last 1 month interval in search
        $limit = (!empty($request['limit']) ? (int) $request['limit'] : 100);
        $action = (!empty($request['action']) ? $request['action'] : 'print');

        $where = [];
        
        if(!empty($user_id)){
            $where['admin_id'] = $user_id;
        }
        if(!empty($symbol)){
            $where['symbol'] = $symbol;
        }
        if(!empty($mode)){
            $where['application_mode'] = $mode;
        }

        $where['status'] = ['$in' => ['LTH','submitted', 'FILLED']];
        $where['trigger_type'] = 'barrier_percentile_trigger';
        $where['created_date']['$gte'] = $this->mongo_db->converToMongodttime($start_date);
        $where['created_date']['$lte'] = $this->mongo_db->converToMongodttime($end_date);

        $collection1 = 'buy_orders';
        if(!empty($exchange)){
            $collection1 = $collection1."_".$exchange; 
		}	
        
        $join = [
            'from' => $collection1,
            'localField' => 'sell_order_id',
            'foreignField' => '_id',
            'as' => 'sell_order',
        ];
        
        $query = [
            ['$lookup' => $join],
            ['$match' => $where],
            ['$sort' => ['created_date' => -1]],
            ['$limit' => $limit],
        ];

        $collection2 = 'buy_orders';
        if(!empty($exchange)){
            $collection2 = $collection2."_".$exchange; 
        }

        $db = $this->mongo_db->customQuery();
        $response = $db->$collection2->aggregate($query);
        $records = iterator_to_array($response);

        echo '<pre>';
        $res_arr = [];
        $buy_order_ids = [];

        $i = 0;
        foreach($records as $rec){

            $where_a = array(
                '_id' => $rec['sell_order_id'],
            );
            $this->mongo_db->where($where_a);
            $data = $this->mongo_db->get('orders');
            $data_Arra = iterator_to_array($data);
            // print_r($data_Arra);
            // echo '<br>';

            if(!array_key_exists('order_type', $data_Arra[0])){
                array_push($res_arr, $rec);
                $buy_order_ids[] = (string) $rec['_id'];
            }
            // echo $data_Arra[0]['purchased_price'];

            // $i++;
            // if($i > 50){
            //     break;
            // }

            // $arr = (array) $rec['sell_order'];
            // if(empty($arr)){
            // if(count($arr) > 0){
            // }else{
            //     array_push($res_arr, $rec);
            //     $buy_order_ids[] = (string) $rec['_id'];
            // }
        }
        
        echo '<pre>';
        print_r("total open orders with missing sell order: ".count($res_arr)."\r\n");
        print_r($buy_order_ids);
        
        if($action == 'create_sell_orders'){
            //Create all sell orders for all of these buy orders
            echo "Creating Sell orders for following ids \r\n";
            print_r($buy_order_ids);

            if(!empty($buy_order_ids)){

                foreach($buy_order_ids as $id){

                    $collection = ($exchange == 'bam') ? 'buy_orders_bam' : 'buy_orders';
                    $this->mongo_db->where(array('_id' => $id));
                    $data = $this->mongo_db->get($collection);
                    $date = date('Y-m-d h:i:s');
                    foreach ($data as $row) {
                        $sell_order_id = $row['sell_order_id'];
                        $ins_data['symbol'] = $row['symbol'];
                        $ins_data['purchased_price'] = $row['purchased_price'];
                        $ins_data['quantity'] = $row['quantity'];
                        $ins_data['order_type'] = $row['order_type'];
                        $ins_data['admin_id'] = $row['admin_id'];
                        $ins_data['stop_loss'] = $row['stop_loss'];
                        $ins_data['loss_percentage'] = $row['loss_percentage'];
                        $ins_data['created_date'] = $this->mongo_db->converToMongodttime($date);
                        $ins_data['modified_date'] = $this->mongo_db->converToMongodttime($date);
                        $ins_data['market_value'] = $row['market_value'];
                        $ins_data['application_mode'] = $row['application_mode'];
                        $ins_data['order_mode'] = $row['order_mode'];
                        $ins_data['trigger_type'] = $row['trigger_type'];
                        $ins_data['order_level'] = $row['order_level'];
                        $ins_data['sell_profit_percent'] = $row['sell_profit_percent'];
                        $ins_data['sell_price'] = $row['sell_price'];
                        $ins_data['status'] = 'new';
                        $ins_data['buy_order_id'] = $row['_id'];
        
                        $this->mongo_db->where(array('_id' => $sell_order_id));
                        $this->mongo_db->set($ins_data);
                        $collection = ($exchange == 'bam') ? 'orders_bam' : 'orders';
                        $this->mongo_db->update($collection);
                    }
                }//end foreach buy_order_ids
            }//end if buy_order_ids
        }//end if create_sell_orders

        if($action == 'print'){
            print_r($res_arr);
        }

        die('<br>**************** End Script ****************');
            
    }//end buy_orders_script


    //TODO: buy_orders_script_cronjob find open orders with no sell order //Umer Abbas [30-11-19]
    public function buy_orders_script_cronjob(){

        $request = [];
        $exchanges = ['bam', 'binanace'];
        foreach($exchanges as $exchange){

            $exchange = (!empty($request['exchange']) ? $request['exchange'] : '');
            if(!empty($exchange)){
                if($exchange == 'binance'){
                    $exchange = '';
                }
            }
            $user_id = (!empty($request['user_id']) ? $request['user_id'] : null);
            $symbol = (!empty($request['coin']) ? $request['coin'] : null);
            $mode = (!empty($request['mode']) ? $request['mode'] : 'live');
            $start_date = (!empty($request['sdt']) ? $request['sdt'] : date("Y-m-d G:i:s", strtotime("-30 day", strtotime(date('Y-m-d G:i:s')))));
            $end_date = (!empty($request['ndt']) ? $request['ndt'] : date('Y-m-d G:i:s')); //if no date is provided then use last 1 month interval in search
            $limit = (!empty($request['limit']) ? (int) $request['limit'] : 5000);
            $action = 'create_sell_orders';

            $where = [];
            
            if(!empty($user_id)){
                $where['admin_id'] = $user_id;
            }
            if(!empty($symbol)){
                $where['symbol'] = $symbol;
            }
            if(!empty($mode)){
                $where['application_mode'] = $mode;
            }

            $where['status'] = ['$in' => ['LTH','submitted', 'FILLED']];
            $where['trigger_type'] = 'barrier_percentile_trigger';
            $where['created_date']['$gte'] = $this->mongo_db->converToMongodttime($start_date);
            $where['created_date']['$lte'] = $this->mongo_db->converToMongodttime($end_date);

            $collection1 = 'buy_orders';
            if(!empty($exchange)){
                $collection1 = $collection1."_".$exchange; 
            }	
            
            $join = [
                'from' => $collection1,
                'localField' => 'sell_order_id',
                'foreignField' => '_id',
                'as' => 'sell_order',
            ];
            
            $query = [
                ['$lookup' => $join],
                ['$match' => $where],
                ['$sort' => ['created_date' => -1]],
                ['$limit' => $limit],
            ];

            $collection2 = 'buy_orders';
            if(!empty($exchange)){
                $collection2 = $collection2."_".$exchange; 
            }

            $db = $this->mongo_db->customQuery();
            $response = $db->$collection2->aggregate($query);
            $records = iterator_to_array($response);

            // echo '<pre>';
            $res_arr = [];
            $buy_order_ids = [];

            $i = 0;
            foreach($records as $rec){

                $where_a = array(
                    '_id' => $rec['sell_order_id'],
                );
                $this->mongo_db->where($where_a);
                $data = $this->mongo_db->get('orders');
                $data_Arra = iterator_to_array($data);

                if(!array_key_exists('order_type', $data_Arra[0])){
                    array_push($res_arr, $rec);
                    $buy_order_ids[] = (string) $rec['_id'];
                }

            }
            
            if($action == 'create_sell_orders'){
                //Create all sell orders for all of these buy orders
                // echo "Creating Sell orders for following ids \r\n";
                // print_r($buy_order_ids);

                if(!empty($buy_order_ids)){

                    foreach($buy_order_ids as $id){

                        $collection = ($exchange == 'bam') ? 'buy_orders_bam' : 'buy_orders';
                        $this->mongo_db->where(array('_id' => $id));
                        $data = $this->mongo_db->get($collection);
                        $date = date('Y-m-d h:i:s');
                        foreach ($data as $row) {
                            $sell_order_id = $row['sell_order_id'];
                            $ins_data['symbol'] = $row['symbol'];
                            $ins_data['purchased_price'] = $row['purchased_price'];
                            $ins_data['quantity'] = $row['quantity'];
                            $ins_data['order_type'] = $row['order_type'];
                            $ins_data['admin_id'] = $row['admin_id'];
                            $ins_data['stop_loss'] = $row['stop_loss'];
                            $ins_data['loss_percentage'] = $row['loss_percentage'];
                            $ins_data['created_date'] = $this->mongo_db->converToMongodttime($date);
                            $ins_data['modified_date'] = $this->mongo_db->converToMongodttime($date);
                            $ins_data['market_value'] = $row['market_value'];
                            $ins_data['application_mode'] = $row['application_mode'];
                            $ins_data['order_mode'] = $row['order_mode'];
                            $ins_data['trigger_type'] = $row['trigger_type'];
                            $ins_data['order_level'] = $row['order_level'];
                            $ins_data['sell_profit_percent'] = $row['sell_profit_percent'];
                            $ins_data['sell_price'] = $row['sell_price'];
                            $ins_data['status'] = 'new';
                            $ins_data['buy_order_id'] = $row['_id'];
            
                            $this->mongo_db->where(array('_id' => $sell_order_id));
                            $this->mongo_db->set($ins_data);
                            $collection = ($exchange == 'bam') ? 'orders_bam' : 'orders';
                            $this->mongo_db->update($collection);

                            //Create Order Log in orders_history_log collection 
                            $show_error_log = 'no';
                            $this->insert_order_history_log($id, 'Sell order was created by script', 'sell_order_created_by_script', $show_error_log, $user_id);
                        }
                    }//end foreach buy_order_ids
                }//end if buy_order_ids
            }//end if create_sell_orders
        }
        // die('<br>**************** End Script ****************');
            
    }//end buy_orders_script_cronjob

    public function  check_missing_sell_order_array($exchange='binance'){
            $where['status']['$in'] =  array('LTH','submitted','FILLED');
            $where['application_mode'] = 'live'; 
            $where['trigger_type'] = 'barrier_percentile_trigger';
            $start_date = date("Y-m-d G:i:s", strtotime("-30 day", strtotime(date('Y-m-d G:i:s'))));
            $end_date =  date('Y-m-d G:i:s');
            $where['created_date']['$gte'] = $this->mongo_db->converToMongodttime($start_date);
            $where['created_date']['$lte'] = $this->mongo_db->converToMongodttime($end_date);

            $collection1 = ($exchange=='bam' ? 'buy_orders_bam' : 'buy_orders' );
            $this->mongo_db->where($where);
            $data = $this->mongo_db->get($collection1);
            $data_Arra = iterator_to_array($data);
            
            $collection2 = ($exchange == 'bam' ? 'orders_bam' : 'orders');

            echo "<pre>";
            foreach($data_Arra as $od){

                $where2['buy_order_id'] = $od['_id'];
                $where2['admin_id']['$in'] = array('null', null, NULL, '');
                $start_date = date("Y-m-d G:i:s", strtotime("-1 day", strtotime(date('Y-m-d G:i:s'))));
                $where2['created_date']['$gte'] = $this->mongo_db->converToMongodttime($start_date);
                $where2['created_date']['$lte'] = $this->mongo_db->converToMongodttime($end_date);

                $this->mongo_db->where($where2);
                $data2 = $this->mongo_db->get($collection2);
                $data_Arra2 = iterator_to_array($data2);
                if(!empty($data_Arra2)){
                    // test before running this query
                    $id = $data_Arra2[0]['_id'];
                    echo  "sell order Id  : ".$id;
                    echo  "<br />";
                    $this->create_sell_order($id, $exchange);
                    print_r($data_Arra2);
                }
            }
            // print_r($data_Arra);
            die('end script');
    }

    public function  umer2(){

                // $where2['buy_order_id'] = $od['_id'];
                // $where2['admin_id']['$in'] = array('null', null, NULL, '');
                $where2['application_mode'] = 'live';
                $where2['trigger_type'] = 'barrier_percentile_trigger';
               

                $this->mongo_db->order_by(array('created_date'=>-1));
                $this->mongo_db->limit(2000);

                // $start_date = date("Y-m-d G:i:s", strtotime("-1 day", strtotime(date('Y-m-d G:i:s'))));
                // $where2['created_date']['$gte'] = $this->mongo_db->converToMongodttime($start_date);
                // $where2['created_date']['$lte'] = $this->mongo_db->converToMongodttime($end_date);
                $this->mongo_db->where($where2);
                $data2 = $this->mongo_db->get('orders');
                $data_Arra2 = iterator_to_array($data2);
                echo '<pre>';
                print_r($data_Arra2);

        
    }

    public function create_sell_order($id, $exchange){
        $collection = ($exchange == 'bam') ? 'buy_orders_bam' : 'buy_orders';
        $this->mongo_db->where(array('_id' => $id));
        $data = $this->mongo_db->get($collection);
        $date = date('Y-m-d h:i:s');
        foreach ($data as $row) {
            $sell_order_id = $row['sell_order_id'];
            $ins_data['symbol'] = $row['symbol'];
            $ins_data['purchased_price'] = $row['purchased_price'];
            $ins_data['quantity'] = $row['quantity'];
            $ins_data['order_type'] = $row['order_type'];
            $ins_data['admin_id'] = $row['admin_id'];
            $ins_data['stop_loss'] = $row['stop_loss'];
            $ins_data['loss_percentage'] = $row['loss_percentage'];
            $ins_data['created_date'] = $this->mongo_db->converToMongodttime($date);
            $ins_data['modified_date'] = $this->mongo_db->converToMongodttime($date);
            $ins_data['market_value'] = $row['market_value'];
            $ins_data['application_mode'] = $row['application_mode'];
            $ins_data['order_mode'] = $row['order_mode'];
            $ins_data['trigger_type'] = $row['trigger_type'];
            $ins_data['order_level'] = $row['order_level'];
            $ins_data['sell_profit_percent'] = $row['sell_profit_percent'];
            $ins_data['sell_price'] = $row['sell_price'];
            $ins_data['status'] = 'new';
            $ins_data['buy_order_id'] = $row['_id'];

            $this->mongo_db->where(array('_id' => $sell_order_id));
            $this->mongo_db->set($ins_data);
            $collection = ($exchange == 'bam') ? 'orders_bam' : 'orders';
            $this->mongo_db->update($collection);
        }
    }


    public function  get_item_with_condition(){
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', E_ALL & ~E_NOTICE);

        $request = $this->input->get();
        $collection = $request['col']; 
        $field = $request['field']; 
        $value = $request['val'];

        $ids_arr = ['_id', 'buy_order_id', 'sell_order_id', 'buy_parent_id', 'admin_id', 'user_id', 'order_id', 'sold_order_id', 'sold_buy_order_id', 'resume_order_id'];
        if(in_array($field, $ids_arr) && $value != 'global'){
            $where = [
                $field => ['$in'=> [$value, $this->mongo_db->mongoId($value)]],
            ];
        }else{
            $where = [
                $field => $value,
            ];
        }
        $this->mongo_db->where($where);
        $coins = $this->mongo_db->get($collection);
        $coins_arr = iterator_to_array($coins);
        echo '<pre>';
        print_r($coins_arr);

        die('******************* end script *******************');
    }
    
    public function  get_item_by_id_condition(){
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', E_ALL & ~E_NOTICE);

        $request = $this->input->get();
        $collection = $request['col']; 
        $field = $request['field']; 
        $value = $request['val'];
        $oid = $this->mongo_db->mongoId($value);
        $matcharray = array();
        $matcharray[] = $value;
        $matcharray[] = $oid;
        
        echo "<pre>";
        print_r($matcharray);
        echo $field;
        $where = array($field => array('$in' => $matcharray));
        print_r($where);
        // $where[$field]['$in'] = array($value, $oid);

        $this->mongo_db->where($where);
        $arr = $this->mongo_db->find($collection);
        $arr = iterator_to_array($arr);
        echo '<pre>';
        print_r($arr);


        // $arr = $this->mongo_db->get($collection);
        // $arr = iterator_to_array($arr);
        // echo '<pre>';
        // print_r($arr);

        die('******************* end script *******************');
    }

    //TODO: find data_types of the fields //Umer Abbas [16-12-19]
    public function order_test_dump(){
        // ini_set("display_errors" , 1);
        // error_reporting(E_ALL);
        
        $request = $this->input->get();
        if(!empty($request)){
            $exchange = (!empty($request['exchange']) ? $request['exchange'] : '');
            $exchange = ($exchange == '' || $exchange == 'binance' ? '' : "_$exchange");
            $id = (!empty($request['id']) ? $request['id'] : '');

            echo "<pre>";
            if(!empty($id)){

                echo "\r\n Time converted to TimeZone: Asia/Karachi \r\n";
                $timezone = "Asia/Karachi";
                $new_timezone = new DateTimeZone($timezone);

                echo "<br> *** Buy Order *** <br> \r\n";
                $collection = "buy_orders$exchange";
                $this->mongo_db->where(array('_id' => $id));
                $responseArr = $this->mongo_db->get($collection);
                $data = iterator_to_array($responseArr);
                $row = $data[0];
                
                if(!empty($row)){

                    var_dump($row);

                    echo "<br> *** Sell Order ***  <br> \r\n";

                    if (!empty($row['sell_order_id'])) {

                        $sell_order_id = (String) $row['sell_order_id'];
                        $collection2 = "orders$exchange";
                        $this->mongo_db->where(array('_id' => $sell_order_id));
                        $respArr = $this->mongo_db->get($collection2);
                        $data = iterator_to_array($respArr);
                        $row2 = $data[0];
                        
                        if(!empty($row2)){

                            var_dump($row2);
                        }

                    } else {
                        echo "<br> Sell Order Not found <br> \r\n";
                    }
                }else{
                    
                    echo "\r\n *** Buy Order not found  ***  \r\n";
                    echo "\r\n  \r\n";
                    echo "\r\n *** Try to find in sold orders ***  \r\n \r\n";

                    $collection = "sold_buy_orders$exchange";
                    $this->mongo_db->where(array('_id' => $id));
                    $responseArr = $this->mongo_db->get($collection);
                    $data = iterator_to_array($responseArr);
                    $row3 = $data[0];
                    
                    if(!empty($row3)){
                        
                        var_dump($row3);

                    }else{
                        echo "\r\n Order not found in sold buy orders \r\n";
                    }
                }
            }else{
                echo "\r\n Use get request to print order data like this:  http: //app.digiebot.com/admin/trading_reports/test/order_test_dump?exchange=bam&id=5ddbcd781425211d6253d8e2 \r\n";
                echo "\r\n <b>Note</b> if exchange not passed default exchange binance will be used \r\n";
            }
        }else{
            echo "\r\n <b>Use get request to print order data like this: <\b>  http: //app.digiebot.com/admin/trading_reports/test/order_test_dump?exchange=bam&id=5ddbcd781425211d6253d8e2 \r\n";
            echo "\r\n <b>Note</b> if exchange not passed default exchange binance will be used \r\n";
        }

        die('*************** End Script *****************');
    }//end order_test_dump

    
    public function distinct($collection, $field){

        $res = $this->mongo_db->distinct($collection, $field);
        // $records = iterator_to_array($res);
        echo '<pre>';
        print_r($res);

        die('******************* end script *******************');

    }


    public function orders_with_logs($collection, $field, $count){
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', E_ALL & ~E_NOTICE);

        $field_ = '$'.$field;
        $query = [
            ['$group' => [
                    '_id' => [ 'order_id' => '$order_id', $field => $field_ ], 
                    'uniqueIds' => [ '$addToSet' => '$_id' ],
                    'count' => [ '$sum' =>1 ] 
                ]
            ],
            ['$match' => ['count' => ['$gte' => (int)$count ]]],
        ];

        $db = $this->mongo_db->customQuery();
        $response = $db->$collection->aggregate($query);
        $records = iterator_to_array($response);
        echo '<pre>';
        // print_r($records);

        $typeArr = [];
        foreach($records as $rec){
            $typeArr[] = $rec['_id']['type'];
        }
        $typeArr = array_values(array_unique($typeArr));
        print_r($typeArr);

        die('******************* end script *******************');
    }
    
    public function order_ids_with_logs($collection, $count){
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', E_ALL & ~E_NOTICE);

        $query = [
            ['$group' => [
                    '_id' => [ 'order_id' => '$order_id'],
                    'count' => [ '$sum' =>1 ]
                ]
            ],
            ['$match' => ['count' => ['$gt' => (int)$count ]]],
        ];

        $db = $this->mongo_db->customQuery();
        $response = $db->$collection->aggregate($query);
        $records = iterator_to_array($response);
        echo '<pre>';
        print_r($records);

        // $typeArr = [];
        // foreach($records as $rec){
        //     $typeArr[] = $rec['_id']['type'];
        // }
        // $typeArr = array_values(array_unique($typeArr));
        // print_r($typeArr);

        die('******************* end script *******************');

    }


    public function delete_order_logs($collection, $order_id){
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', E_ALL & ~E_NOTICE);

        $oid = $this->mongo_db->mongoId($order_id);
        // $oid = $this->mongo_db->mongoId('5e15058d8d75be001962b7e7');
        $where = [
            // 'order_id' => $oid 
            'order_id' => ['$in' => [$order_id, $oid]] 
        ];

        $db = $this->mongo_db->customQuery();
        $response = $db->$collection->deleteMany($where);

        print_r($response);

        die('******************* end script *******************');

    }


    public function get_recent_buy_orders_users(){

        $exchange = 'binance';
        $connetct = $this->mongo_db->customQuery();

        $user_ids = $this->get_daily_limit_exceeded_users([], $exchange);
        $this->restrict_parents_for_limit_exceeded_users($user_ids, $exchange);

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
            unset($user_ids, $exchange);
        }

        //Save last Cron Executioon
        // $last_cron_exchange = "check_recent_buy_orders_users_$exchange";
        // $this->last_cron_execution_time($last_cron_exchange, '3m', 'Cronjob to check user buy limit after 3 minutes binance');

        echo "<br> **************** cron end **************** <br>";
        return;
    }


    public function unset_pick_parent_which_exceede_daily_limit($exchange){

        // $exchange = 'binance';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $daily_limit_collection = $exchange == "binance" ? "daily_trade_buy_limit" : "daily_trade_buy_limit_$exchange";
        $pick_parent_temp_collection = $exchange == "binance" ? "pick_parent_temp_test" : "pick_parent_temp_test_$exchange";
        

        // echo "$buy_collection , $daily_limit_collection, $pick_parent_temp_collection";
        // die();

        $pipeline = [
            [
                '$match' => [
                    'user_id' => ['$ne' =>'5c0912b7fc9aadaac61dd072'],
                    // 'user_id' => '5c0915befc9aadaac61dd1b8',
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


        echo "<pre>";
        print_r($result);
        die('<br>*********** End testing *********** <br>');

        // if(!empty($result)){
        //     $total = count($result);
        //     for($i=0; $i<$total; $i++){
        //         $result[$i]['recordArr'][0]['ids'];
        //         $db->$buy_collection->updateMany(['_id' => ['$in' => $result[$i]['recordArr'][0]['ids'] ]], ['$set'=>['pick_parent'=>'no']]);
        //         $db->$pick_parent_temp_collection->deleteOne(['_id' => $result[$i]['_id'] ]);
        //         unset($result[$i]);
        //     }
        // }
        // unset($result);

        // echo "<br> ***************** SCRIPT END unset pick parent which might exceed limit ********************** <br>";
        return true;
    }


    public function unset_pick_parent_based_on_base_currency_low_balance(){

        // die('testing dont run this code');

        $exchange = 'binance';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $user_wallet_collection = $exchange == "binance" ? "user_wallet" : "user_wallet_$exchange";
        $pick_parent_temp_collection = $exchange == "binance" ? "test_balance_pick_parent_temp" : "test_balance_pick_parent_temp_$exchange";

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
                                    '$cond' => ['if' => ['$eq' => [['$arrayElemAt' => ['$splitArr', 1]], '']], 'then' => '$$usdt_usd_worth', 'else' => '$$btc_usd_worth']
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

        // if (!empty($result)) {
        //     $total = count($result);
        //     for ($i = 0; $i < $total; $i++) {
        //         $result[$i]['recordArr'][0]['ids'];
        //         $db->$buy_collection->updateMany(['_id' => ['$in' => $result[$i]['recordArr'][0]['ids']]], ['$set' => ['pick_parent' => 'no']]);
        //         $db->$pick_parent_temp_collection->deleteOne(['_id' => $result[$i]['_id']]);
        //         unset($result[$i]);
        //     }
        // }
        // unset($result);

    }

    //test omi git push /pull

    public function asim(){

        $db = $this->mongo_db->customQuery();
        $data = $db->opportunity_logs_binance->find([], ['sort'=>['created_date'=> -1], 'limit'=>100]);
        $data = iterator_to_array($data);
        $data = array_column($data, 'created_date');
        $data = array_reverse($data);

        $daysArr = [];
        for($i=0;$i<100;$i++){
            $od = $data[$i];
            $dnumber = $od->toDateTime()->format("d");
            if(!in_array($dnumber, $daysArr)){
                $daysArr[] = $dnumber; 
            }
        }
        
        
        echo "<pre>";
        $d1 = $data[0];
        $first = $d1->toDateTime()->format("Y-m-d");
        $first = date('Y-m-d', strtotime($first.' -1 days'));
        echo "$first <br>";
        $customDayStart = date('Y-m-d H:i:s', strtotime($first.' 07:59:00'));
        echo "start custom day $customDayStart <br>";
        $nextCustomDayStart = date('Y-m-d H:i:s', strtotime($customDayStart.' +1 days'));
        echo "next custom day start $nextCustomDayStart <br>";

        $day = '';
        for($i=0;$i<100;$i++){
            $od = $data[$i];
            
            $day = date('Y-m-d H:i:s', strtotime($od->toDateTime()->format("Y-m-d H:i:s")));
            $dnumber = date('Y-m-d H:i:s', strtotime($od->toDateTime()->format("d")));

            if($day > $customDayStart && $day < $nextCustomDayStart){
                //do nothing
                echo $day;
            }else if(in_array($dnumber, $daysArr)){
                //Draw line
                echo "<br> $customDayStart --------------------------------------------------------------- $nextCustomDayStart<br><br>";
                echo $day;
                $customDayStart = $nextCustomDayStart;
                $nextCustomDayStart = date('Y-m-d H:i:s', strtotime($customDayStart . ' +1 days'));
            }else{

                echo "<br> $customDayStart --------------------------------------------------------------- $nextCustomDayStart<br><br>";
                $customDayStart = $nextCustomDayStart;
                $nextCustomDayStart = date('Y-m-d H:i:s', strtotime($customDayStart . ' +1 days'));
            }
            echo "<br>";
        }
        unset($data);
        exit;
    }


    //calculate_daily_tradeable_usd
    public function calculate_daily_tradeable_usd($user_id, $exchange){

        $pricesArr = get_current_market_prices($exchange, ['BTCUSDT']);
        $data = $this->get_dashboard_wallet($user_id, $exchange);

        //TODO: find default daily limit with 15% according to his trade package and available balance
        $btc_percent = 30;
        $usdt_percent = 70;
        $daily_tradeable = 15;

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

    }//end calculate_daily_tradeable_usd

    //get_dashboard_wallet
    public function get_dashboard_wallet($user_id, $exchange){

        //get btc and usdt balance
        $params = [
            'user_id' => $user_id,
            'exchange' => $exchange,
        ];
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => 'get_dashboard_wallet',
        ];
        $resp = hitCurlRequest($req_arr);
        
        if($resp['http_code'] == 200 && $resp['response']['status']){
            return $resp['response']['data'];
        }
        return false;
    }//end get_dashboard_wallet

    public function check_trading_points_exceed($user_id=''){

        // get users with trading on
        $db = $this->mongo_db->customQuery();
        $pipeline = [];
        if($user_id != ''){
            $user_id_object = $this->mongo_db->mongoId($user_id);
            $pipeline[] = ['$match' => ['_id'=>$user_id_object]];
        }
        $pipeline[] = ['$project' => ['_id'=>1, 'username'=>1]];

        $users = $db->users->aggregate($pipeline);
        $users = iterator_to_array($users);
        $total_users = count($users);
        
        // echo "<pre>";

        for($i = 0; $i < $total_users; $i++){

            // $username = $users[$i]['username'] ?? '';
            $user_id = (string) $users[$i]['_id'];

            $trading_points = $this->get_current_trading_points($user_id, 'binance');
            
            // echo "$username  ----  $user_id  ----  $trading_points<br>";

            if($trading_points > 0){
                //binance
                $db->users->updateOne(['_id' => $users[$i]['_id']], ['$set' => ['trading_status' => 'on']]);
                //bam
                $db->bam_credentials->updateOne(['user_id' => (string) $users[$i]['_id']], ['$set' => ['trading_status' => 'on']]);
                //kraken
                $db->kraken_credentials->updateOne(['user_id' => (string) $users[$i]['_id']], ['$set' => ['trading_status' => 'on']]);
            }else{
                //binance
                $db->users->updateOne(['_id' => $users[$i]['_id']], ['$set' => ['trading_status' => 'off']]);
                //bam
                $db->bam_credentials->updateOne(['user_id' => (string) $users[$i]['_id']], ['$set' => ['trading_status' => 'off']]);
                //kraken
                $db->kraken_credentials->updateOne(['user_id' => (string) $users[$i]['_id']], ['$set' => ['trading_status' => 'off']]);
            }

            sleep(1);
        }

        // print_r($users);

        echo "<br><br> ***********  End Script ************";

        // $where = ['application_mode' => 'live', 'parent_status' => 'parent', 'status' => ['$ne' => 'canceled']];
        // $set = ['$set'=>['pick_parent' => 'yes']];
        
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
                        'trading_status' => 'off'
                    ]
                ],
                [
                    '$group'=> [
                        '_id' => null,
                        'user_ids' => ['$push'=>[ '$toString'=>'$_id']],
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
                        'trading_status' => 'off'
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
            
            //bam
            $db->buy_orders_bam->updateMany(['admin_id'=>['$in'=>$exceeded_user_ids], 'application_mode'=> 'live', 'parent_status'=>'parent', 'status'=>['$ne'=>'canceled']], ['$set'=>['pick_parent'=>'yes']]);
            
            //kraken
            $db->buy_orders_kraken->updateMany(['admin_id'=>['$in'=>$exceeded_user_ids], 'application_mode'=> 'live', 'parent_status'=>'parent', 'status'=>['$ne'=>'canceled']], ['$set'=>['pick_parent'=>'yes']]);
        }

        return true;
    }


    public function delete_one_month_old_notifications(){
        
        $db = $this->mongo_db->customQuery();
        
        $end_date = date('Y-m-d H:i:s', strtotime('-30 days'));
        $end_date = $this->mongo_db->converToMongodttime($end_date);

        $where = [
            'created_date' => ['$lt' => $end_date ],
        ];

        $total_rec = $db->notifications->count($where);
        // $db->notifications->deleteOne_($where);

        echo "$total_rec";

    }


    public function delete_selected_logs_from_orders(){
        
        $db = $this->mongo_db->customQuery();
        
        $end_date = date('Y-m-d H:i:s', strtotime('-30 days'));
        $end_date = $this->mongo_db->converToMongodttime($end_date);

        $where = [
            'created_date' => ['$lt' => $end_date],
        ];

        $total_rec = $db->notifications->count($where);
        // $db->notifications->deleteOne_($where);

        echo "$total_rec";

    }


    public function delete_duplicate_old_logs(){

        $year = date('Y');
        $month = date('m') - 1;

        echo "$year -- $month";

        $exchanges_arr = ['binance', 'bam', 'live'];
        $mode_arr = ['binance', 'bam', 'live'];

        $collection_arr = []; 

        $exchange = 'binance';


        $old_log_collection = $exchange == "binance" ? "orders_history_log" : "orders_history_log_$exchange";
        $live_collection = $exchange == "binance" ? "orders_history_log_live_".$year."_".$month : "orders_history_log_".$exchange."_live_".$year."_".$month;
        $test_collection = $exchange == "binance" ? "orders_history_log_test_".$year."_".$month : "orders_history_log_".$exchange."_test_".$year."_".$month;

        // db.orders_history_log_test_2020_10.aggregate([
        //     { '$sort': { 'created_date': -1 } },
        //     { '$group': { '_id': { 'type': '$type', 'order_id': '$order_id' }, 'data': { '$push': '$$ROOT' }, 'sum': { '$sum': 1 } } },
        //     { '$match': { 'sum': { '$gt': 1 } } },
        //     { '$project': { '_id': 0, 'data': { '$slice': ["$data", 1, { '$subtract': [{ '$size': "$data" }, 1] }] } } },
        //     { '$unwind': '$data' },
        //     { '$project': { '_id': '$data._id' } },
        //     { '$group': { '_id': null, 'log_ids': { '$push': '$_id' } } },
        //     { '$project': { '_id': 0, 'log_ids': 1 } },
        //     { '$unwind': '$log_ids' },
        //     {'$out': 'check_duplicate_log_type_ids'}
        // ], { allowDiskUse: true }).toArray()
    }

    public function unset_pick_parent_based_on_base_currency_daily_limit(){

        $exchange = 'binance';
        $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
        $daily_limit_collection = $exchange == "binance" ? "daily_trade_buy_limit" : "daily_trade_buy_limit_$exchange";
        $pick_parent_temp_collection = $exchange == "binance" ? "testing_base_currency_daily_limit_pick_parent_temp" : "testing_base_currency_daily_limit_pick_parent_temp_$exchange";

        $pipeline = [
            [
                '$match' => [
                    'user_id' => '5c0912b7fc9aadaac61dd072',
                    // 'user_id' => ['$nin' => ['5c0912b7fc9aadaac61dd072', '5c0915befc9aadaac61dd1b8']],
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
            // for ($i = 0; $i < $total; $i++) {
            //     $result[$i]['recordArr'][0]['ids'];
            //     // $db->$buy_collection->updateMany(['_id' => ['$in' => $result[$i]['recordArr'][0]['ids']]], ['$set' => ['pick_parent' => 'no']]);
            //     // $db->$pick_parent_temp_collection->deleteOne(['_id' => $result[$i]['_id']]);
            //     unset($result[$i]);
            // }
        }
        unset($result);

        echo "<br> **************** cron end **************** <br>";

    }

    public function unset_pick_parent_based_on_base_currency_daily_limit_test(){

        // ini_set("display_errors", E_ALL);
        // error_reporting(E_ALL);

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
                    'daily_bought_btc_usd_worth' => '$daily_bought_btc_usd_worth',
                    'daily_bought_usdt_usd_worth' => '$daily_bought_usdt_usd_worth',
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
                        'daily_bought_btc_usd_worth' => '$daily_bought_btc_usd_worth',
                        'daily_bought_usdt_usd_worth' => '$daily_bought_usdt_usd_worth',
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
                                'btc_ten_dollar_more' => [ '$add' => [ '$$btc_usd_worth', 10 ] ],
                                'usdt_ten_dollar_more' => [ '$add' => [ '$$usdt_usd_worth', 10 ] ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'use_btc_val' => '$btc_ten_dollar_more',
                                'use_usdt_val' => '$usdt_ten_dollar_more',
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
                            '$addFields' => [
                                'bought_usd_worth' => [
                                    '$cond' => ['if' => ['$eq' => [['$arrayElemAt' => ['$splitArr', 1]], '']], 'then' => '$$daily_bought_usdt_usd_worth', 'else' => '$$daily_bought_btc_usd_worth']
                                ],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'remainingBalance' => [
                                    '$cond' => [
                                        'if' => [ '$eq' => ['$bought_usd_worth', 0] ],
                                        'then' => '$usd_worth',
                                        'else' => '$remainingBalance'
                                    ]
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

        echo "<pre>";
        echo count($result);
        echo "<br>";
        echo "<br>";
        print_r($result);

        echo "<br> **************** cron end **************** <br>";

    }

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
        
        echo "<pre>";

        for($i = 0; $i < $total_users; $i++){

            $username = $users[$i]['username'] ?? '';
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
                $db->users->updateOne(['_id' => $users[$i]['_id']], ['$set' => ['is_api_key_valid' => 'no']]);
                $db->buy_orders->updateMany(['admin_id' => $user_id, 'parent_status' => 'parent', 'status' => ['$ne'=>'canceled']], ['$set' => ['pick_parent' => 'no']]);
            }

            //kraken
            if($this->is_api_key_valid($user_id, 'kraken')){
                $db->kraken_credentials->updateOne(['user_id' => $user_id], ['$set' => ['is_api_key_valid' => 'yes']]);
                $keyArr[] = 'kraken';
            }else{
                $db->kraken_credentials->updateOne(['user_id' => $user_id], ['$set' => ['is_api_key_valid' => 'no']]);
                $db->buy_orders_kraken->updateMany(['admin_id' => $user_id, 'parent_status' => 'parent', 'status' => ['$ne'=>'canceled']], ['$set' => ['pick_parent' => 'no']]);
            }
            
            //bam
            if($this->is_api_key_valid($user_id, 'bam')){
                $db->bam_credentials->updateOne(['user_id' => $user_id], ['$set' => ['is_api_key_valid' => 'yes']]);
                $keyArr[] = 'bam';
            }else{
                $db->bam_credentials->updateOne(['user_id' => $user_id], ['$set' => ['is_api_key_valid' => 'no']]);
                $db->buy_orders_bam->updateMany(['admin_id' => $user_id, 'parent_status' => 'parent', 'status' => ['$ne'=>'canceled']], ['$set' => ['pick_parent' => 'no']]);
            }

            $userExchanges = implode(' | ', $keyArr);
            unset($keyArr);
            echo "$username  ----  $user_id  ----  $userExchanges<br>";
            
            // // sleep for 1 second
            // sleep(1);

            // //sleep for one second
            // usleep(1000000);

            //sleep for half second
            usleep(500000);
            
            // //sleep for quarter of a second
            // usleep(250000);
        }

        // print_r($users);

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

    public function get_min_qty($exchange='binance'){
        
        echo "<pre>";
        $res = get_min_quantity($exchange);

        $BTCUSDT = $res['BTCUSDT'];
        foreach($res as $coin=>$val){

            $tarr = explode('USDT', $coin);
            if (isset($tarr[1]) && $tarr[1] == '') {
                echo "\r\n USDT coin $coin === ".($val['min_qty'] * $val['currentMarketPrice']);
            } else {
                echo "\r\n BTC coin $coin === ".($val['min_qty'] * $val['currentMarketPrice'] * $BTCUSDT['currentMarketPrice']);
            }

        }

        print_r($res);

        die('<br>************* end testing *************');
    }

    public function removeCoinBalance(){
        $db = $this->mongo_db->customQuery();
        $where = [
            'api_key'=>['$in' => ['', null]],
            'api_secret'=>['$in' => ['', null]],
        ];
        $result = $db->users->find($where);
        $result = iterator_to_array($result);
        $total = count($result);
        
        $user_ids = [];
        for($i=0; $i<$total; $i++){
            $user_ids[] = (string) $result[$i]['_id'];
        }
        unset($result);
        
        $result = $db->user_wallet->deleteMany(['user_id'=>['$in' => $user_ids]]);
        print_r($result);


    }


    public function test_revert_parent_query($exchange=''){

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
        }else{
            echo "exchange is required, <br><br> test_revert_parent_query/exchange_name";
        }

    }


    public function find_parents_whith_child_pl_negative($exchange=''){

        if($exchange !=''){

            $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
            $temp_collection = $exchange == "binance" ? "parent_ids_revert_based_on_child_pl_negative" : "parent_ids_revert_based_on_child_pl_negative_$exchange";

            $pricesObjArr = get_current_market_prices($exchange);
            $pricesArr = [];
            echo "<pre>";

            foreach($pricesObjArr as $key=>$val){
                $pricesArr[] = [
                    'symbol' => $key,
                    'price' => $val,
                ];
            }
            unset($pricesObjArr);
            
            $pricesJsonArr = Json_encode($pricesArr);

            echo "$pricesJsonArr <br> <br>"; 

            /*
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
                                        
                                        { "status": { "$in": ["new", "new_ERROR", "BUY_ID_ERROR"] }, "price": { "$ne": "" } }, 
                                        
                                        { "status": { "$in": ["FILLED", "FILLED_ERROR", "SELL_ID_ERROR"] }, "is_sell_order": "yes", "is_lth_order": { "$ne": "yes" }, "cost_avg": "yes", "cavg_parent": "yes", "show_order": "yes", "avg_orders_ids.0": { "$exists": false }, "move_to_cost_avg": { "$ne": "yes" } },
                                         
                                        { "status": { "$in": ["FILLED", "FILLED_ERROR", "SELL_ID_ERROR"] }, "is_sell_order": "yes", "is_lth_order": { "$ne": "yes" }, "cost_avg": { "$nin": ["yes", "taking_child", "completed"] } }, 
                                         
                                         { "status": { "$in": ["submitted", "submitted_for_sell", "fraction_submitted_sell", "submitted_ERROR"] }, "cost_avg": { "$nin": ["taking_child", "yes", "completed"] } }

                                        ] 
                                } 
                            },
                            {
                                "$addFields": {
                                    "pricesArr": '.$pricesJsonArr.'
                                }
                            },
                            { 
                                "$addFields": { 
                                    "currPrice": { "pricesArr": { "$elemMatch": { "$pricesArr.symbol": "$symbol" } } }
                                } 
                            }, 
                            { 
                                "$project": { 
                                    "_id": 1, 
                                    "buy_parent_id": 1 ,
                                    "currPrice": 1,
                                    "symbol": 1
                                } 
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
    

            echo "$json_pipeline <br><br>";
            */

            $json_pipeline2 = '[
                {
                    "$match": {
                        "application_mode": "live",
                        "$or": [          
                            { "status": { "$in": ["new", "new_ERROR", "BUY_ID_ERROR"] }, "price": { "$ne": "" } }, 
                            
                            { "status": { "$in": ["FILLED", "FILLED_ERROR", "SELL_ID_ERROR"] }, "is_sell_order": "yes", "is_lth_order": { "$ne": "yes" }, "cost_avg": "yes", "cavg_parent": "yes", "show_order": "yes", "avg_orders_ids.0": { "$exists": false }, "move_to_cost_avg": { "$ne": "yes" } },
                                
                            { "status": { "$in": ["FILLED", "FILLED_ERROR", "SELL_ID_ERROR"] }, "is_sell_order": "yes", "is_lth_order": { "$ne": "yes" }, "cost_avg": { "$nin": ["yes", "taking_child", "completed"] } }, 
                                
                            { "status": { "$in": ["submitted", "submitted_for_sell", "fraction_submitted_sell", "submitted_ERROR"] }, "cost_avg": { "$nin": ["taking_child", "yes", "completed"] } }
                        ] 
                    }
                },
                {
                    "$sort":{
                        "modified_date": -1
                    }
                },
                {
                    "$limit":1000
                },
                {
                    "$addFields": {
                        "pricesArr": '.$pricesJsonArr.'
                    }
                },
                { 
                    "$addFields": { 
                        "currPrice": {
                            "$filter": {
                                "input": "$pricesArr",
                                "as": "item",
                                "cond": { "$eq": [ "$$item.symbol", "$symbol" ] }
                            }
                        }
                    } 
                },
                {
                    "$project": {
                        "admin_id": 1,
                        "symbol":1,
                        "quantity":1,
                        "purchased_price": { "$toDouble": "$purchased_price" },
                        "currPrice":{ "$arrayElemAt": [ "$currPrice", 0 ] },
                        "modified_date":1
                    }
                },
                { 
                    "$addFields": { 
                        "currPrice1": { "$toDouble": "$currPrice.price" }
                    } 
                },
                { 
                    "$addFields": { 
                        "pl": { "$divide": [ { "$multiply": [ { "$subtract": [ "$currPrice1", "$purchased_price" ] }, 100 ] }, "$purchased_price" ] }
                    }
                },
                {
                    "$project": {
                        "admin_id": 1,
                        "symbol":1,
                        "quantity":1,
                        "purchased_price":1,
                        "currPrice1": 1,
                        "pl":1,
                        "modified_date":1
                    }
                },
                {
                    "$match": {
                        "$expr": { "$lte": ["$pl", -5] }
                    }
                }
            ]';
            
            $pipeline = json_decode($json_pipeline2, true);
    
            $db = $this->mongo_db->customQuery();
            $result = $db->$buy_collection->aggregate($pipeline);
    
            // $db->$temp_collection->deleteMany(['recordArr.0' => ['$exists' => true]]);
            // $result = $db->$temp_collection->find([], ['_id' => 1,]);

            $result = iterator_to_array($result);
            
            // print_r($result);
            var_dump($result);
    
            $result = [];

            if (false && !empty($result)) {
                $ids = array_column($result, '_id'); 
                if(!empty($ids)){

                    // $total = count($ids);
                    // echo "<br> total parents processed: $total <br>";
    
                    // $modified_date = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));

                    // $db->$buy_collection->updateMany(['_id' => ['$in' => $ids]], ['$set' => ['status'=>'new', 'modified_date' => $modified_date, 'pause_status' => 'play', 'revert_and_play_by_manual_query'=>'yes' ]]);

                    // $db->$temp_collection->deleteMany([]);
                
                }
            }
            unset($result);
        }else{
            echo "exchange is required, <br><br> test_revert_parent_query/exchange_name";
        }

    }


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

            // echo count($result);
            // echo "<br><br>";
            print_r($result);
            // var_dump($result);
    
            if (!empty($result)) {

                $total = count($result);
                $ids = [];
                for($i=0; $i<$total; $i++){
                    $ids[] = $result[$i]['recordArr'][0]['_id'];
                }
                
                $data = $db->$buy_collection->find(['_id' => ['$in' => $ids], 'status' => 'takingOrder' ], ['status'=>1]);
                $data = iterator_to_array($data);
                $ids_filtered = array_column($data, '_id');
                
                print_r($ids_filtered);
                // echo "status takingOrder <br><br>";
                // echo count($data);
                // echo "<br><br>";
                // unset($data);

                // $modified_date = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));
                
                // $where = [
                //     '_id' => ['$in'=>$ids],
                //     'status' => ['$ne'=> 'canceled'],
                // ];
                // $set = [
                //     '$set' => [
                //         'status' => 'new',
                //         'modified_date' => $modified_date, 
                //     ],
                // ];
                // $db->$buy_collection->updateMany($where, $set);
            }
            unset($result, $ids);
        }else{
            // echo "exchange is required, <br><br> test_revert_parent_query/exchange_name";
        }

    }


    //calculate_daily_tradeable_usd_new_1111
    public function calculate_daily_tradeable_usd_new_1111($user_id, $exchange)
    {
        //get btc and usdt balance
        $params = [
            'user_id' => $user_id,
            'exchange' => $exchange,
            'baseCurrencyArr' => ['BTC', 'USDT'],
            'customBtcPackage' => 0.02,
            'customUsdtPackage' => 1000,
            'dailTradeAbleBalancePercentage' => 10,  
        ];
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => '',
            'req_url' => 'http://app.digiebot.com/admin/trading_reports/Atg/find_available_btc_usdt/'.$user_id.'/'.$exchange,
        ];
        $resp = hitCurlRequest($req_arr);

        // echo "<pre>";
        // print_r($resp);
        // echo "<br>";
        
        if($resp['http_code'] == 200 && $resp['response']['status']){
          
          $dailyTradeableBTC = $resp['response']['data']['dailyTradeableBTC'];
          $dailyTradeableUSDT = $resp['response']['data']['dailyTradeableUSDT'];
          $dailyBtcUsdWorth = $resp['response']['data']['dailyBtcUsdWorth'];
          $dailyUsdtUsdWorth = $resp['response']['data']['dailyUsdtUsdWorth'];
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

            // echo "if ran <br>";
            // print_r($resArr);

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

            // echo "<pre> if ran <br>";
            // print_r($resArr);

            return $resArr;
        }
    }//end calculate_daily_tradeable_usd_new_1111

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
            ];

            // print_r($pairs);
            // print_r($resp['response']);

            $db = $this->mongo_db->customQuery();

            foreach($pairsArr as $key=>$val){
                // echo "$key  ===  $val  ===  ".$resp['response']['result'][$key]['ordermin']."<br>";
                $db->market_min_notation_kraken->updateOne(['symbol' => $val], ['$set' => ['min_notation' => (float) $resp['response']['result'][$key]['ordermin'] ]]);
            }

            

        }

    }


    public function pick_parents_if_opportunity_comes($symbol='', $order_level='', $exchange=''){

        if(!empty($symbol) && !empty($order_level) && !empty($exchange)){

            $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
            $daily_limit_collection = $exchange == "binance" ? "daily_trade_buy_limit" : "daily_trade_buy_limit_$exchange";
    
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
                                    'application_mode' => 'live',
                                    'symbol' => $symbol,
                                    'order_level' => $order_level,
                                    'parent_status' => 'parent',
                                    'pause_status' => 'play',
                                    'pick_parent' => 'yes',
                                    '$expr' => [
                                        '$eq' => ['$admin_id', '$$user_id'],
                                    ],
                                ],
                            ],
                            [
                                '$addFields' => [
                                    'splitArr' => ['$split' => ['$symbol', 'USDT']],
                                    'btc_ten_dollar_more' => [ '$add' => [ '$$btc_usd_worth', 10 ] ],
                                    'usdt_ten_dollar_more' => [ '$add' => [ '$$usdt_usd_worth', 10 ] ],
                                ],
                            ],
                            [
                                '$addFields' => [
                                    'use_btc_val' => '$btc_ten_dollar_more',
                                    'use_usdt_val' => '$usdt_ten_dollar_more',
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
                                        '$lte' => ['$usd_worth', '$remainingBalance'],
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
                    '$redact' => [
                        '$cond' => ['if' => ['$gte' => [['$size' => '$recordArr.ids'], 1]], 'then' => '$$KEEP', 'else' => '$$PRUNE'],
                    ],
                ],
                [
                    '$group' => [
                        '_id' => null,
                        'myIds' => ['$push' => [ '$arrayElemAt' => [ [ '$arrayElemAt' => [ '$recordArr.ids', 0 ]], 0 ]]],
                    ]
                ],
                [
                    '$redact' => [
                        '$cond' => ['if' => ['$gte' => [['$size' => '$myIds'], 1]], 'then' => '$$KEEP', 'else' => '$$PRUNE'],
                    ],
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'myIds' => 1,
                    ]
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'recordArr' => 1,
                    ]
                ]
            ];
    
            $db = $this->mongo_db->customQuery();
            $result = $db->$daily_limit_collection->aggregate($pipeline);
            echo "<pre>";
            $result = iterator_to_array($result);
            print_r($result);
    
            // $db->$pick_parent_temp_collection->deleteMany(['recordArr' => ['$size' => 0]]);
    
            // $result = $db->$pick_parent_temp_collection->find([], ['_id' => 0, 'recordArr' => 1]);
            // $result = iterator_to_array($result);
    
            // echo "<pre>";
            // print_r($result);
        }


        echo "<br> **************** cron end **************** <br>";
    }


    public function check_users_with_last_buy(){

        $admin_ids = [
            "5c0912b7fc9aadaac61dd072",
            "5fc4bc8dbf30285b6e0f6435",
            "5c091515fc9aadaac61dd169",
            "5f9d5b1f09595d4f567fae62",
            "5fec55651b5b550b862aabc2",
            "601876b715be9331e523d764",
            "5c8482e1fc9aad8d69397c32",
            "5c091479fc9aadaac61dd121",
            "5c0915b0fc9aadaac61dd1b1",
            "5ec3ba2bbfd7a444ab1a1da8",
            "5c0915befc9aadaac61dd1b8",
            "5ce430bffc9aad1e870b3622",
            "5c0913adfc9aadaac61dd0c4",
            "5f6cba84e08cd706a933d5bc",
            "5f759ae92cbcdc45064c4de7",
            "5f69f307aaa0ae5497059482",
            "5f503e44640d66499f5ceabe",
            "5c144c4bfc9aad417c7780d2",
            "5e4ec6f98334a705c918e553",
            "5f4e068232ea6e62b56982c4",
            "5f535e80f6a8f570c1716286",
            "5ec57e02c1cbe0619950c9c2",
            "5ebef5a786ca94017c4505de",
            "5ea7fa4fc9eee72524636b90",
            "5eb31d5f12dcaf3e3f52a94a",
            "5c0915c6fc9aadaac61dd1bc",
            "5c83d1e3fc9aad95e66599e2",
            "5fe1ac700e57364de90fd9a2",
            "5eac53ff3db2132d636b6af9",
            "5eab51a3029ba851251c3188",
            "5c091467fc9aadaac61dd119",
            "5c091385fc9aadaac61dd0b2",
            "5e7641c8c5581450bb0ca045",
            "5c6f2c69fc9aad694e443eb2",
            "5ee78a1f877f9070900127f3",
            "5c86a5fafc9aada17869ad02",
            "5c83e901fc9aad1783794d22",
            "5de6ab154e992851b37f5d22",
            "5c091427fc9aadaac61dd0fc",
            "5e5041268ccecc25e34bea04",
            "5eb5a5a628914a45246bacc6",
            "5ebce07751ee1e2ead5d8193",
            "5f92b4fb716d9607a141d96f",
            "5d974b3a86804a4b2b6d47f2",
            "5c0913a2fc9aadaac61dd0bf",
            "5c09146efc9aadaac61dd11c",
            "5c091502fc9aadaac61dd160",
            "5ff0ebcc46ab4e637e332df4",
            "5c09145efc9aadaac61dd115",
            "5c0913bdfc9aadaac61dd0cb",
            "5c0913dafc9aadaac61dd0d8",
            "5c091531fc9aadaac61dd176",
            "5ebeb388b00c472074346682",
            "5c867e3ffc9aad347e165d32",
            "5ecffa5848639b7a0b0cf4c8",
            "5ec667cc1bb6e87b213c4df8",
            "5c8283cafc9aad8ef4477c52",
            "5ce5c30efc9aadb43a5107b2",
            "5c091387fc9aadaac61dd0b3",
            "5c09153bfc9aadaac61dd17b",
            "5ece18f12c0ea241bf7bc374",
            "5e98aa0807d9d357443bb934",
            "5eaaeddb3564ff1fec4c84dc",
            "5dbfbf173d2b4448516670c2",
            "5c09143ffc9aadaac61dd106",
            "5c46069ffc9aad5c5562aa42",
            "5da5b66bfd52536b6b22ed7a",
            "5c0914effc9aadaac61dd157",
            "5d8f525287040e7568215632",
            "5f786d5ffbf2c704354adca5",
            "5c0913b6fc9aadaac61dd0c8",
            "5eb153b156459800a05b3751",
            "5e8651b502a1f610c961d9cd",
            "5e63edd816b0646960785112",
            "5ed90b3ab3ac1c5d0a73c808",

        ];

        $connetct = $this->mongo_db->customQuery();

        $startTime = date('Y-m-d 07:59:59');
        
        $startTime = $this->mongo_db->converToMongodttime($startTime);
        
        $childsBuyPipeline = [
            [
                '$match'=> [
                    'admin_id' => ['$in'=> $admin_ids],
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
                        '$gte'=> $startTime,
                    ],
                ]
            ],
            [
                '$group' => [
                    '_id' => '$admin_id'
                ]
            ],
        ];
        $buy_orders = $connetct->buy_orders->aggregate($childsBuyPipeline);
        $buy_orders = iterator_to_array($buy_orders);

        $sold_orders = $connetct->sold_buy_orders->aggregate($childsBuyPipeline);
        $sold_orders = iterator_to_array($sold_orders);
        $orders = array_merge($buy_orders, $sold_orders);

        echo "<pre>";
        print_r($orders);


        foreach($admin_ids as $user_id){
            $where = [
                'admin_id' => $user_id,
                'application_mode' => 'live',
                'parent_status' => 'parent',
                'status' => ['$ne'=>'canceled'],
            ];
            $parentsCount = $connetct->buy_orders->count($where);

            if($parentsCount >= 100){
                echo "$user_id :::: parents $parentsCount <br>";
            }
        }


    }


    public function update_aggrassive_stop_loss($exchange=''){

        $exchange = 'binance';
        if($exchange !=''){

            $buy_collection = "temp_testing_buy_orders";
            
            $inc_dec_pl = 0.4;
            $pl_value = 1.5;

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
                        'status' => 'FILLED',
                        'is_sell_order' => 'yes',
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
                    '$match' => [
                        '$expr' => [ '$gt' => ['$currPrice', '$purchased_price'] ]
                    ]
                ],
                [ 
                    '$addFields' => [ 
                        'pl' => [ '$divide' => [ [ '$multiply' => [ [ '$subtract' => [ '$currPrice', '$purchased_price' ] ], 100 ] ], '$purchased_price' ] ]
                    ]
                ],
                [
                    '$match' => [
                        '$expr' => [ '$gte' => ['$pl', [ '$ifNull' => [ '$next_pl_check',  $pl_value] ] ] ]
                    ]
                ],
                [ 
                    '$addFields' => [ 
                        'curr_profit_sell_pl' => [ '$add' => [ '$pl', $inc_dec_pl ] ],
                        'curr_loss_sell_pl' => [ '$subtract' => [ '$pl', $inc_dec_pl ] ],
                    ]
                ],
                [ 
                    '$addFields' => [ 
                        'next_pl_check' => [ '$add' => [ '$curr_profit_sell_pl', $inc_dec_pl] ]
                    ]
                ],
                [ 
                    '$addFields' => [ 
                        'sell_price' => [ '$add' => [ '$purchased_price', [ '$divide' => [ [ '$multiply' => [ '$curr_profit_sell_pl', '$purchased_price' ] ], 100 ] ] ] ],
                        'iniatial_trail_stop' => [ '$subtract' => [ '$purchased_price', [ '$divide' => [ [ '$multiply' => [ '$curr_loss_sell_pl', '$purchased_price' ] ], 100 ] ] ] ],
                    ]
                ],
                [
                    '$project' => [
                        '_id' => 1,
                        'symbol' => 1,
                        'purchased_price' => 1,
                        'currPrice' => 1,
                        'pl' => 1,
                        'curr_profit_sell_pl' => 1,
                        'sell_price' => 1,
                        'curr_loss_sell_pl' => 1,
                        'iniatial_trail_stop' => 1,
                        'next_pl_check' =>1,
                    ]
                ],
            ];
    
            $db = $this->mongo_db->customQuery();
            $result = $db->$buy_collection->aggregate($pipeline);

            $result = iterator_to_array($result);
            echo "<pre>";
            print_r($result);
            // print_r($result);
            
            /*
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
            */
            unset($result);
        }else{
            // echo "exchange is required, <br><br> test_revert_parent_query/exchange_name";
        }

    }

    //kraken
    public function update_auto_trade_usd_worth_and_tradeable_balance_kraken(){

        echo "<pre>";

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

        $last_run = date('Y-m-d H:i:s', strtotime('-4 days'));
        $pipeline = [
            [
                '$match' => [
                    '_id' => ['$in' => $user_ids],
                    'application_mode' => 'both',
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
                ],
            ],
            [
                '$limit' => 1,
            ],
        ];


        print_r(json_encode($pipeline));

        $document = $this->mongo_db->customQuery();
        $atg_users = $document->users->aggregate($pipeline);
        $atg_users = iterator_to_array($atg_users);

        print_r($atg_users);

        echo "<br> ************************************ <br>";

        if (!empty($atg_users)) {
            $total_users = count($atg_users);
            for ($i = 0;$i < $total_users; $i++) {

                if(empty($atg_users[$i]['_id']) && $i != 0){
                    continue;
                }


                echo " $i   =>  ".(string) $atg_users[$i]['_id']. "<br>";

                // $params = [
                //     // "user_id" => "5c0912b7fc9aadaac61dd072",
                //     "user_id" => (string) $atg_users[$i]['_id'],
                //     "exchange" => "kraken",
                //     "application_mode" => "live",
                //     // "symbol" => "QTUMBTC",
                // ];
                // $req_arr = [
                //     'req_type' => 'POST',
                //     'req_params' => $params,
                //     // 'req_endpoint' => 'updateDailyTradeSettings',
                //     'req_endpoint' => 'updateDailyTradeSettings_digie',
                //     // 'req_url' => '',
                // ];
                // // $resp = hitCurlRequest($req_arr);


                //update last cron run time
                // $curr_time = date('Y-m-d H:i:s');
                // $where1 = ['_id' => $atg_users[$i]['_id']];
                // $upd['$set'] = [
                //     'atg_parents_update_cron_last_run_kraken' => $this->mongo_db->converToMongodttime($curr_time),
                // ];
                // $db = $this->mongo_db->customQuery();
                // $db->users->updateOne($where1, $upd);

                //sleep for 30 minutes and then execute for next user
                // sleep(30);

            }
        }

        //Save last Cron Executioon
        // $this->last_cron_execution_time('update_auto_trade_usd_worth_and_tradeable_balance_kraken', '7m', 'Cronjob to update usd_worth and tradeable balance for Auto trade module for kraken (*/7 * * * *)', 'updateQtyUSDWorth');


        echo "<br> ************** Script End *************** <br>";

    }//end update_auto_trade_usd_worth_and_tradeable_balance kraken

    public function dailyLimitAdjust($user_id='', $dailyLimitArr, $exchange){

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


        echo "<pre>";
        // $dailyTradeableBTC = 0.1;
        // $dailyTradeableUSDT = 10;
        
        $dailyTradeableBTC = $dailyLimitArr['dailyBtc'];
        $dailyTradeableUSDT = $dailyLimitArr['dailyUsdt'];
        echo "default daily BTC ::: $dailyTradeableBTC  --------------  default daily USDT :::  $dailyTradeableUSDT";
        echo "<br>";

        echo "BTC extra percent : $addBtcPercent  ---------- USDT extra percent $addUsdtPercent";
        echo "<br>";
        $dailyTradeableBTC = $dailyTradeableBTC + (($addBtcPercent * $dailyTradeableBTC) / 100);
        echo "added BTC :: $dailyTradeableBTC";
        echo "<br>";
        $dailyTradeableUSDT = $dailyTradeableUSDT + (($addUsdtPercent * $dailyTradeableUSDT) / 100);
        echo "added USDT :: $dailyTradeableUSDT";
        echo "<br>";

        print_r($result);

        $resposne = [
            'dailyBtc' => $dailyTradeableBTC,
            'dailyUsdt' => $dailyTradeableUSDT,
        ];

        print_r($resposne);

        return $resposne;

    }
    
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

        // return;

        // $options = [
        //     'symbol' => '',	// required
        //     'orderId' => '', // optional
        //     'startTime' => '', // optional	
        //     'endTime' => '', // optional
        //     'limit' => '', // optional
        //     'recvWindow' =>	'', // optional
        //     'timestamp' => '', // required
        // ];

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
            
            $pricesArr = get_current_market_prices('binance', []);
            $symbolsArr = array_keys($pricesArr);
    
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

        echo "<pre>";
        print_r($tradeHistory);
        
        return $tradeHistory;
    }

    public function updateUserTradeHistory($user_id='', $symbol='', $limit=1000){

        return;
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
                return;
            }else{
                $insData = [
                    'binance_trade_hisoty_import_user_in_progress' => (string) $user_id,
                    'created_date' => $this->mongo_db->converToMongodttime(date('Y-m-d G:i:s')),
                ];
                $db->$collectionName->insertOne($insData);
            }
            
            $pricesArr = get_current_market_prices('binance', []);
            $symbolsArr = array_keys($pricesArr);
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
                    // $this->saveTradesHistory($user_id, $trades);
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
            // $db->$collectionName->updateOne(['_id' => $mongo_user_id], $updData);

            $collectionName = 'binance_trade_history_update_status';
            $db->$collectionName->deleteMany([]);

        }

        echo('<br> ****************** End Script ****************** <br>');

    }

    public function testGetTradesHistory($user_id, $symbol, $limit=1000, $endTime='', $startTime='', $fromId=''){

        echo "<pre>";
        echo "$user_id, $symbol, $limit, $endTime, $startTime, $fromId <br>";

        if($endTime == 'no'){
            $endTime = '';
        }
        if($startTime == 'no'){
            $startTime = '';
        }

        $trades = $this->getTradesHistory($user_id, $symbol, $limit, $endTime, $startTime, $fromId);
        print_r($trades);
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
    
    /* *************** Save user weekly balance stats ***************** */
    public function update_weekly_wallet_stats($exchange){
        // $exchange = 'binance';

        $collection_name = $exchange == "binance" ? "users" :  $exchange."_credentials";
        $id_field = $exchange == "binance" ? '$_id' :  '$user_id';
        $field_weekly_wallet_stats_updated_date = $exchange == "binance" ? 'weekly_wallet_stats_updated_date' :  $exchange."_weekly_wallet_stats_updated_date";

        $start_date = $this->mongo_db->converToMongodttime(date('Y-m-d 00:00:00', strtotime('last sunday')));
        $end_date = $this->mongo_db->converToMongodttime(date('Y-m-d 23:59:59', strtotime('last sunday')));

        $db = $this->mongo_db->customQuery();
        $pipeline = [
            [
                '$match' => [
                    'api_key' => ['$exists' => true, '$nin' => ['', null]],
                    'api_secret' => ['$exists' => true, '$nin' => ['', null]],
                ],
            ],
            [
                '$project' => [
                    'my_id' => ['$toString' => $id_field]
                ] 
            ],
            [
                '$lookup' => [
                    'from' => 'users',
                    'let' => [
                        'user_id_obj' => ['$toObjectId' => '$my_id'],
                    ],
                    'pipeline' => [
                        [
                            '$match' => [
                                '$expr' => ['$eq'=> ['$_id', '$$user_id_obj']],
                                'application_mode' => ['$in'=>['both', 'live']],
                                '$or' => [
                                    [
                                        $field_weekly_wallet_stats_updated_date => ['$exists' => false],
                                    ],
                                    [
                                        $field_weekly_wallet_stats_updated_date => ['$gte' => $start_date, '$lte' => $end_date],
                                    ]
                                ]
                            ]
                        ],
                        [
                            '$sort' => [ $field_weekly_wallet_stats_updated_date => 1]
                        ],
                        [
                            '$project' => [
                                '_id' => 1,
                                $field_weekly_wallet_stats_updated_date => 1
                            ]
                        ],
                    ],
                    'as' => 'users'
                ]
            ],
            [
                '$match' => [
                    '$expr' => [
                        '$gt' => [ ['$size' => '$users' ], 0] 
                    ]
                ]
            ],
            [
                '$project' => [
                    'users' => 1,
                ]
            ],
            [
                '$unwind' => '$users'
            ],
            [
                '$sort' => ['users.'.$field_weekly_wallet_stats_updated_date => 1]
            ],
            [
                '$project' => [
                    '_id' => 1
                ]
            ],
            [
                '$limit' => 1
            ],
        ];
        $users = $db->$collection_name->aggregate($pipeline);
        $users = iterator_to_array($users);

        // echo "<pre>";

        // print_r($pipeline);

        // echo count($users);

        // print_r($users);

        // $usersArr = array_column($users, 'users');
        // print_r($usersArr);
        
        // print_r(array_column((array) $usersArr, '_id'));
        $this->saveWeeklyWalletStats((string) $users[0]['_id'], $exchange);
        
        unset($users);

    }

    public function saveWeeklyWalletStats($user_id, $exchange){


        // Cron time (*/1 * * * 7) 

        $btc_user_wallet = 0;
        $usdt_user_wallet = 0;
        $btc_committed = 0;
        $btc_committed = 0;
        $usdt_committed = 0;
        $btc_balance = 0;
        $usdt_balance = 0;
        $btc_weekly_gain = 0;
        $usdt_weekly_gain = 0;
        $BTCUSDT_used_btc_worth = 0;
        $BTCUSDT_used_usdt_worth = 0;
        $btc_account_worth = 0;
        $usdt_account_worth = 0;

        $params = [
            'exchange' => $exchange,
            'user_id' => $user_id,
        ];
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => 'get_dashboard_wallet',
        ];
        $resp = hitCurlRequest($req_arr);
        
        if($resp['http_code'] == 200 && $resp['response']['status']){

            foreach($resp['response']['data']['avaiableBalance'] as $val){

                if($val['coin_symbol'] == 'BTC'){
                
                    $btc_user_wallet = (float) $val['coin_balance'];
                
                }else if($val['coin_symbol'] == 'USDT'){
                   
                    $usdt_user_wallet = (float) $val['coin_balance'];
                
                }
            }

            // echo "<pre>";
            // print_r($resp['response']['data']);

            $openBalance_btc = $resp['response']['data']['openBalance']['onlyBtc'];
            $openBalance_usdt = $resp['response']['data']['openBalance']['onlyUsdt'];

            $lthBalance_btc = $resp['response']['data']['lthBalance']['onlyBtc'];
            $lthBalance_usdt = $resp['response']['data']['lthBalance']['onlyUsdt'];

            $costAvgBalance_btc = $resp['response']['data']['costAvgBalance']['onlyBtc'];
            $costAvgBalance_usdt = $resp['response']['data']['costAvgBalance']['onlyUsdt'];
            
            $BTCUSDT_used_btc_worth = $resp['response']['data']['openLthBTCUSDTBalance']['OpenLTHBtcWorth'];
            $BTCUSDT_used_usdt_worth = $resp['response']['data']['openLthBTCUSDTBalance']['OpenLTHUsdWorth'];

            $btc_committed = $costAvgBalance_btc + $openBalance_btc + $lthBalance_btc;
            $usdt_committed = $costAvgBalance_usdt + $openBalance_usdt + $lthBalance_usdt;

            $btc_balance = ($btc_user_wallet - $BTCUSDT_used_btc_worth) - $btc_committed;
            $usdt_balance = $usdt_user_wallet - $usdt_committed;

            $btc_account_worth = $btc_balance + $btc_committed;
            $usdt_account_worth = $usdt_balance + $usdt_committed;

            $stats = [
                'user_id' => (string) $user_id,
                'btc_user_wallet' => $btc_user_wallet,
                'usdt_user_wallet' => $usdt_user_wallet,
                'btc_committed' => $btc_committed,
                'usdt_committed' => $usdt_committed,
                'btc_balance' => $btc_balance,
                'usdt_balance' => $usdt_balance,
                'btc_account_worth' => $btc_account_worth,
                'usdt_account_worth' => $usdt_account_worth,
                'btc_weekly_gain' => $btc_weekly_gain,
                'usdt_weekly_gain' => $usdt_weekly_gain,
                'created_date' => $this->mongo_db->converToMongodttime(date('Y-m-d G:i:s')),
            ];

            //calculate weekly gain
            // $startTime = date('Y-m-d H:00:00', strtotime('-24 hours'));
            // $startTime = $this->mongo_db->converToMongodttime($startTime);

            $collectionName = $exchange == "binance" ? "weekly_user_wallet_stats" : "weekly_user_wallet_stats_$exchange";
            
            $db = $this->mongo_db->customQuery();
            $pipeline = [
                [
                    '$match' => [
                        'user_id' => (string) $user_id,       
                    ]
                ],
                [
                    '$sort' => [
                        'created_date' => -1,
                    ]
                ],
                [
                    '$limit' => 1
                ],
            ];
            $lastWeek = $db->$collectionName->aggregate($pipeline);
            $lastWeek = iterator_to_array($lastWeek);

            if(!empty($lastWeek)){
                $lastWeek = $lastWeek[0];

                $btc_weekly_gain = $btc_account_worth - $lastWeek['btc_account_worth'];
                $usdt_weekly_gain = $usdt_account_worth - $lastWeek['usdt_account_worth'];

                $stats['btc_weekly_gain'] = $btc_weekly_gain;
                $stats['usdt_weekly_gain'] = $usdt_weekly_gain;
            }

            $db->$collectionName->insertOne($stats);

            //update user 
            $field_weekly_wallet_stats_updated_date = $exchange == "binance" ? 'weekly_wallet_stats_updated_date' :  $exchange."_weekly_wallet_stats_updated_date";
            $user_id = $this->mongo_db->mongoId($user_id);
            $upd_data = [
                '$set' => [
                    $field_weekly_wallet_stats_updated_date => $this->mongo_db->converToMongodttime(date('Y-m-d G:i:s')) 
                ]
            ];
            $db->users->updateOne(['_id' => $user_id], $upd_data);

            // print_r($stats);
            unset($stats, $upd_data);

        }
        return true;

    }
    /* *************** End Save user weekly balance stats ************** */

    public function update_user_vallet_test($user_id=''){
		$balance_arr = $this->binance_api->get_account_balance($user_id);
		echo "<pre>";
		print_r($balance_arr);
	}//End of run

    public function getBinanceExchnageInfo(){

        $pricesArr = get_current_market_prices('binance', []);
        $digie_coins_arr = array_keys($pricesArr);

        $digie_coins_arr[] = 'BNBBTC';
        $digie_coins_arr[] = 'BNBUSDT';
        
        $data = $this->binance_api->exchangeInfo();
        $symbols_data = $data['symbols'];
        $total_symbols = count($data['symbols']);

        $digie_coins_data = [];

        for($i=0; $i < $total_symbols; $i++){
            if(in_array($symbols_data[$i]['symbol'], $digie_coins_arr)){
                $digie_coins_data[] = $symbols_data[$i];
            }
        }
        unset($symbols_data, $data);

        $min_notations_arr = [];

        $total_symbols = count($digie_coins_data);
        for($i=0; $i < $total_symbols; $i++){
            $symbol = $digie_coins_data[$i]['symbol'];
            $min_notation = '';
            $stepSize = '';
            foreach($digie_coins_data[$i]['filters'] as $val){
                if($val['filterType'] == 'MIN_NOTIONAL'){
                    $min_notation = (float) $val['minNotional'];
                }else if($val['filterType'] == 'LOT_SIZE'){
                    $stepSize = (float) $val['stepSize'];
                }
            }
            if(!empty($symbol) && $min_notation !== '' && $stepSize !== ''){
                $min_notations_arr[] = [
                    'symbol' => $symbol,
                    'min_notation' => $min_notation,
                    'stepSize' => $stepSize,
                ];
            }
        }
        unset($digie_coins_data);

        echo "<pre>";
        // print_r($digie_coins_data);
        // echo json_encode($data);
        print_r($min_notations_arr);

        if(!empty($min_notations_arr)){
            $db = $this->mongo_db->customQuery();
    
            $total_symbols = count($min_notations_arr);
            for($i=0; $i < $total_symbols; $i++){
                
                $coin = $db->market_min_notation->find(['symbol' => $min_notations_arr[$i]['symbol'] ]);
                $coin = iterator_to_array($coin);
                if(!empty($coin)){
                    //update
                    $db->market_min_notation->updateOne(['symbol' => $min_notations_arr[$i]['symbol']], ['$set' => $min_notations_arr[$i] ]);
                }else{
                    //insert
                    $db->market_min_notation->insertOne($min_notations_arr[$i]);
                }
            }
        }
        unset($min_notations_arr, $coin);

        return true;

    }

    public function testUserGet($userid=''){
        
        $db = $this->mongo_db->customQuery();
        
		$pipeline = [
			[
                '$match' => [
                    'application_mode' => ['$in' => ['both', 'live'] ],
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
                    ]
                 ]
            ],
        ];

        if(!empty($userid)){
            $pipeline[0]['$match']['_id'] = $this->mongo_db->mongoId((string) $userid);
        }

        $users = $db->users->aggregate($pipeline);
        $users = iterator_to_array($users);
        echo "<pre>";
        echo (count($users));
        echo "<br>";
        print_r($users);

    }
    
    public function testNewUserPickParentYesIfNo($user_id=''){
        
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

    }

    public function updateUserParentSortOrder($user_id='', $exchange=''){
    
        echo "<pre>";

        if(!empty($user_id) && !empty($exchange)){

            $buy_collection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
    
            $db = $this->mongo_db->customQuery();
            
            $where = [
                'admin_id' => $user_id,
                'application_mode' => 'live',
                'parent_status' => 'parent',
                'stauts' => ['$ne' => 'canceled'],
            ];
    
            $parents = $db->$buy_collection->find($where);
            $parents = iterator_to_array($parents);
            $old_sort_order = array_column($parents, 'randomize_sort');
            
            $curr_time = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));
            
            // foreach($parents as $val){
            //     $db->$buy_collection->updateOne(['_id'=>$val['_id']], ['$set'=>['randomize_sort'=>rand(900,1000), 'randomize_sort_date'=>$curr_time]]);
            // }
            echo "parent sort order updated <br>";

            $parents = $db->$buy_collection->find($where);
            $parents = iterator_to_array($parents);
            $new_sort_order = array_column($parents, 'randomize_sort', '_id');

            echo "<br>*************** Old Sort Order ******************* <br>";
            print_r($old_sort_order);
            echo "<br>*************** New Sort Order ******************* <br>";
            print_r($new_sort_order);

        }else{
            echo "/user_id/exchange is required";
        }

    }

    public function user_active_status_script($type='', $exchange){

        // TODO:: 1)  valid
        // TODO:: 2)  last 7 days history 
        // TODO:: 3)  after 12 hours again check 
        // TODO:: 4)  any valid swap
        // TODO:: 5)  send email

        // $type = 'regular'; // regular active status API that will check API key validity 
        // $type = '12hour'; // 12hour active status API that will check invalid API keys to see if API has become valid now  

        if($type == 'regular'){

            //loop through users
            // TODO:: 1)  valid




            
        }else if($type = '12hour'){
            
            //loop through users
            // pick invalid API key users and check validity

            // TODO:: 1)  valid

        }


    }

    public function getLiveUsersWithApiKeySet($exchange=''){
        
        $collection_name = $exchange == "binance" ? "users" :  $exchange."_credentials";
        $id_field = $exchange == "binance" ? '$_id' :  '$user_id';
        $is_key_valid_last_check = $exchange == "binance" ? 'is_key_valid_last_check' :  $exchange."_is_key_valid_last_check";
        $is_key_valid = $exchange == "binance" ? 'is_key_valid' :  $exchange."_is_key_valid";
        $start_date = $this->mongo_db->converToMongodttime(date('Y-m-d G:i:s', strtotime('-24 hours')));

        $db = $this->mongo_db->customQuery();
        $pipeline = [
            [
                '$match' => [
                    'api_key' => ['$exists' => true, '$nin' => ['', null]],
                    'api_secret' => ['$exists' => true, '$nin' => ['', null]],
                ],
            ],
            [
                '$project' => [
                    'my_id' => ['$toString' => $id_field]
                ] 
            ],
            [
                '$lookup' => [
                    'from' => 'users',
                    'let' => [
                        'user_id_obj' => ['$toObjectId' => '$my_id'],
                    ],
                    'pipeline' => [
                        [
                            '$match' => [
                                '$expr' => ['$eq'=> ['$_id', '$$user_id_obj']],
                                'application_mode' => ['$in'=>['both', 'live']],
                                $is_key_valid => ['$ne' => 'yes'],
                                '$and' => [
                                    [
                                        $is_key_valid_last_check => [ '$exists' => false]
                                    ],
                                    [
                                        $is_key_valid_last_check => [ '$lte' => $start_date]
                                    ],
                                ]
                            ]
                        ],
                        [
                            '$project' => [
                                '_id' => 1,
                            ]
                        ],
                    ],
                    'as' => 'users'
                ]
            ],
            [
                '$match' => [
                    '$expr' => [
                        '$gt' => [ ['$size' => '$users' ], 0] 
                    ]
                ]
            ],
            [
                '$project' => [
                    'users' => 1,
                ]
            ],
            [
                '$unwind' => '$users'
            ],
            [
                '$sort' => [
                    $is_key_valid_last_check => 1
                ]
            ],
            [
                '$project' => [
                    '_id' => 1
                ]
            ],
            [
                '$limit' => 1
            ],
        ];
        $users = $db->$collection_name->aggregate($pipeline);
        $users = iterator_to_array($users);
        
        // echo "<pre>";
        // echo "Total user count: ".count($users)."<br>";
        // print_r($users);

        return $users;

        // die('<br> ************* Testing code ************* <br>');
    }

    public function processActiveUserScriptForInvalidApiKey($user_id, $exchange){

        if($exchange == 'kraken'){
            //check validity
            $is_valid = $this->is_api_key_valid_new($user_id, $exchange, 'primary');
            if($is_valid){
                //update validity status and history
            }else{

            }
            //swap with secondary
            
            //$check validity 

        }

        //check validity
        $is_valid = $this->is_api_key_valid_new($user_id, $exchange);
        if($is_valid){
            //$swap
        }else{

        }
         

        // TODO:: 2)  last 7 days history 
        // TODO:: 3)  after 12 hours again check 
        // TODO:: 4)  any invalid swap
        // TODO:: 5)  send email

    }

    public function update_key_validity_status_and_history($user_id, $exchange){

        $api_key_valid_history_collection = $exchange == "binance" ? "api_key_valid_history" : "api_key_valid_history_$exchange"; 
        $db = $this->mongo_db->customQuery();

        

    }    

    public function is_api_key_valid_new($user_id, $exchange, $key_type='primary'){

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
                    }else if(!empty($user[0]['api_key_secondary']) && !empty($user[0]['api_secret_secondary'])){

                        $params = [
                            'user_id' => (string)$user_id,
                            "validating" => true,
                            "user_id" => $user[0]['user_id'],
                            "api_key"  => $user[0]['api_key_secondary'],
                            "api_secret"  => $user[0]['api_secret_secondary'],
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

                    }else if(!empty($user[0]['api_key_third_key']) && !empty($user[0]['api_secret_third_key'])){

                        $params = [
                            'user_id' => (string)$user_id,
                            "validating" => true,
                            "user_id" => $user[0]['user_id'],
                            "api_key"  => $user[0]['api_key_third_key'],
                            "api_secret"  => $user[0]['api_secret_third_key'],
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
                    
                }else if(!empty($user[0]['api_key_secondary']) && !empty($user[0]['api_secret_secondary'])){

                    $params = [
                        'user_id' => (string)$user_id,
                        "validating" => true,
                        "user_id" => $user[0]['user_id'],
                        "api_key"  => $user[0]['api_key_secondary'],
                        "api_secret"  => $user[0]['api_secret_secondary'],
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

                }else if(!empty($user[0]['api_key_third_key']) && !empty($user[0]['api_secret_third_key'])){

                    $params = [
                        'user_id' => (string)$user_id,
                        "validating" => true,
                        "user_id" => $user[0]['user_id'],
                        "api_key"  => $user[0]['api_key_third_key'],
                        "api_secret"  => $user[0]['api_secret_third_key'],
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

        echo "<pre>";
        echo $text = $keySecret ? "$user_id, $exchange key valid <br>" : "$user_id, $exchange key not valid <br>"; 

        return $keySecret ? true : false;
    }

    public function swap_api_key($user_id, $exchange, $swap_primary_key_with){

        $collectionName = $exchange == 'binance' ? "users" : $exchange."_credentials";

        $db = $this->mongo_db->customQuery();

        if($exchange == 'kraken'){
            
            $where = [
                'user_id' => (string) $user_id,
            ];

            $user = $db->$collectionName->find($where);
            $user = iterator_to_array($user);
            if(!empty($user)){
                $user = $user[0];

                if($swap_primary_key_with == 'secondary' && !empty($user['api_key_secondary']) && !empty($user['api_secret_secondary'])){

                    $where1 = [
                        '_id' => $user['_id'],
                    ];
                    $set = [
                        '$set' => [
                           'api_key' => $user['api_key_secondary'], 
                           'api_secret' => $user['api_secret_secondary'], 
                           'api_key_secondary' => $user['api_key'] ?? '', 
                           'api_secret_secondary' => $user['api_secret'] ?? '', 
                        ]
                    ];
                    $db->$collectionName->updateOne($where1, $set);

                }else if($swap_primary_key_with == 'third' && !empty($user['api_key_third_key']) && !empty($user['api_secret_third_key'])){

                    $where1 = [
                        '_id' => $user['_id'],
                    ];
                    $set = [
                        '$set' => [
                           'api_key' => $user['api_key_third_key'], 
                           'api_secret' => $user['api_secret_third_key'], 
                           'api_key_third_key' => $user['api_key'] ?? '', 
                           'api_secret_third_key' => $user['api_secret'] ?? '', 
                        ]
                    ];
                    $db->$collectionName->updateOne($where1, $set);

                }
            }
        }else if($exchange == 'binance'){
            //do nothing
        }else if($exchange == 'bam'){
            //do nothing
        }

        return true;

    }

    public function updateTradeHistoryPickUserCodeTest($exchange=''){

        $db = $this->mongo_db->customQuery();

        if(empty($exchange)){
            echo('pass exchange');
            return;
        }

        // $exchange = 'binance';
        
        $userField = $exchange == 'binance' ? 'duplicate_trade_history_script_date' : $exchange.'_duplicate_trade_history_script_date';

        $users = $this->getLiveUsersWithApiKeySet($exchange);

        echo "<pre>";
        // print_r($users);

        $user_ids = [];

        if(!empty($users)){
            $total_users = count($users);
            for($i=0; $i < $total_users; $i++){
                $user_ids[] = $users[$i]['_id'];
            }
        }

        // print_r($user_ids);
        
        if(!empty($user_ids)){
            $pipeline = [
                [
                    '$match' => [
                        '_id' => ['$in' => $user_ids],
                    ]
                ],
                [
                    '$sort' => [ $userField => 1],
                ],
                [
                    '$limit' => 1,
                ],
            ];
    
            $user = $db->users->aggregate($pipeline);
            $user = iterator_to_array($user);

            // echo "<br> ******************** single user <br>";
            // print_r($user);
            
            $user_id = $user[0]['_id'];
            echo (string) $user_id;

            //Hit CURL to check api key valid/invalid
            $params = [
                'user_id' => (string) $user_id,
                'exchange' => $exchange,
            ];
            $req_arr = [
                'req_type' => 'POST',
                'req_params' => $params,
                'req_endpoint' => 'checkBinanceDuplicatesForTradeHistory',
            ];
            $resp = hitCurlRequest($req_arr);
            
            unset($users, $user, $resp);
        }
   
    }

    public function testCurl($user_id){
        // Get user package
        $params = [
            'user_id' => (string) $user_id,
            'handshake' => $this->get_temp_request_token(),
        ];
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => '',
            'req_url' => 'https://users.digiebot.com/cronjob/GetUserSubscriptionDetails/',
        ];
        $resp = hitCurlRequest($req_arr);

        echo "<pre>";
        print_r($resp);
        // $package_limit = 1000;
        // if($resp['http_code'] == 200 && !empty($resp['response']['trade_limit'])){
        //     $package_limit = (float) $resp['response']['trade_limit'];
        // }
    }

    public function getPricesArr_test($exchange='binance'){
        $pricesArr = get_current_market_prices($exchange, []);
        // $digie_coins_arr = array_keys($pricesArr);
        echo "<pre>";
        print_r($pricesArr);
    }

}



 