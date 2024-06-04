        <?php if (!defined('BASEPATH')) {
            exit('No direct script access allowed');
        }

        if(!function_exists('convert_usdt_to_btc')){
            function convert_usdt_to_btc($usdt){
                $CI= &get_instance();
                $db = $CI->mongo_db->customQuery();
                $wherecoin['coin'] = 'BTCUSDT';
                $res = $db->market_prices->find($wherecoin);
                $btcPrice = iterator_to_array($res);
                $btcWorth = $usdt / $btcPrice[0]['price'];
                return $btcWorth;

            }
        }//end if function exists


        if(!function_exists('convert_btc_to_usdt')){
            function convert_btc_to_usdt($btc){
                $CI     =   &get_instance();
                $db     =   $CI->mongo_db->customQuery();

                $lookup = [
                    [
                        '$match' => [
                            'coin' => 'BTCUSDT'
                        ]
                    ],
                    [
                        '$group' =>[
                            '_id'   => '$coin',
                            'price' => ['$first' => '$price'],
                        ]
                    ]
                ];
                $res        =   $db->market_prices->aggregate($lookup);
                $btcPrice   =   iterator_to_array($res);
                $usdtWorth  =   $btc* $btcPrice[0]['price'];
                
                return $usdtWorth;
            }
        }//end if function exists

        if(!function_exists('convert_btc_to_usdt_according_exchange')){
            function convert_btc_to_usdt_according_exchange($btc, $exchange ){
                $CI     =   &get_instance();
                $db     =   $CI->mongo_db->customQuery();

                $collection_name_price  = ($exchange == 'binance')? 'market_prices': 'market_prices_'.$exchange;

                $lookup = [
                    [
                        '$match' => [
                            'coin' => 'BTCUSDT'
                        ]
                    ],
                    [
                        '$group' =>[
                            '_id'   => '$coin',
                            'price' => ['$first' => '$price'],
                        ]
                    ]
                ];
                $res        =   $db->$collection_name_price->aggregate($lookup);
                $btcPrice   =   iterator_to_array($res);
                $usdtWorth  =   $btc* $btcPrice[0]['price'];
                
                return $usdtWorth;
            }
        }//end if function exists


        if(!function_exists('max_min_price_get')){
            function max_min_price_get($coin, $start_date, $time_10_hours){
                $params_10_hours = array(
                    'coin'       => $coin,
                    'start_date' => (string)$start_date,
                    'end_date'   => (string)$time_10_hours,
                );
                    $jsondata = json_encode($params_10_hours);
                        $curl_10 = curl_init();
                        curl_setopt_array($curl_10, array(
                          CURLOPT_URL => "http://35.171.172.15:3000/api/minMaxMarketPrices",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => "",
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 0,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => "POST",
                          CURLOPT_POSTFIELDS =>$jsondata,
                          CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json"
                          ), 
                        ));
                        $response_price_10 = curl_exec($curl_10);	
                        curl_close($curl_10);
                        return json_decode($response_price_10);
            }
        }//end exist check

        if(!function_exists('getMarketValue')){
            function getMarketValue($coinSymbol, $exchange){
               
                $CI = &get_instance();
                $CI->load->model('admin/mod_coins');
      
                $collectionName = ($exchange == 'binance')? 'market_prices': 'market_prices_'.$exchange;
                $db = $CI->mongo_db->customQuery();
                $lookup = [
                    [
                        '$match' => [
                            'coin' => $coinSymbol
                        ]
                    ],
                    [
                        '$group' =>[
                            '_id'   => '$coin',
                            'price' => ['$first' => '$price'],
                        ]
                    ]
                ];

                $data = $db->$collectionName->aggregate($lookup);
                $response = iterator_to_array($data);
                return $response;
            }
        }

        if(!function_exists('getUserName')){
            function getUserName($adminId){
                $CI = &get_instance();
                $where['_id'] = $CI->mongo_db->mongoId($adminId);
                $CI->mongo_db->where($where);
                $response = $CI->mongo_db->get('users');
                $returnData = iterator_to_array($response);
                return $returnData;
            }
        }
        
        //convert coin balance into USDT
        if(!function_exists('convertCoinBalanceIntobtctoUSDT')){
            function convertCoinBalanceIntobtctoUSDT($coinSymbol, $coinBalance, $exchange ){

                $CI = &get_instance();
                $CI->load->model('admin/mod_coins');
      
                $collectionName = ($exchange == 'binance')? 'market_prices': 'market_prices_'.$exchange;
                $db = $CI->mongo_db->customQuery();
                $lookup = [
                    [
                        '$match' => [
                            'coin' => $coinSymbol
                        ]
                    ],
                    [
                        '$group' =>[
                            '_id'   => '$coin',
                            'price' => ['$first' => '$price'],
                        ]
                    ]
                ];

                $data = $db->$collectionName->aggregate($lookup);
                $response = iterator_to_array($data);

                $btc =  $response[0]['price'] * $coinBalance;


                $lookup1 = [
                    [
                        '$match' => [
                            'coin' => 'BTCUSDT'
                        ]
                    ],
                    [
                        '$group' =>[
                            '_id'   => '$coin',
                            'price' => ['$first' => '$price'],
                        ]
                    ]
                ];
                $res = $db->$collectionName->aggregate($lookup1);
                $btcPrice = iterator_to_array($res);
                $usdtWorth = $btc* $btcPrice[0]['price'];
                return $usdtWorth;
            }
        }

        if(!function_exists('convertCoinBalanceIntoUSDT')){
            function convertCoinBalanceIntoUSDT($coinSymbol, $coinBalance, $exchange ){
                $CI = &get_instance();
                $CI->load->model('admin/mod_coins');
      
                $collectionName = ($exchange == 'binance')? 'market_prices': 'market_prices_'.$exchange;
                $db = $CI->mongo_db->customQuery();
                $lookup = [
                    [
                        '$match' => [
                            'coin' => $coinSymbol
                        ]
                    ],
                    [
                        '$group' =>[
                            '_id'   => '$coin',
                            'price' => ['$first' => '$price'],
                        ]
                    ]
                ];

                $data = $db->$collectionName->aggregate($lookup);
                $response = iterator_to_array($data);

                $usdt =  $response[0]['price'] * $coinBalance;
                return $usdt;
            }
        }

        if(!function_exists('getAllMarketValue')){
            function getAllMarketValue($exchange){
                $CI = &get_instance();
                $CI->load->model('admin/mod_coins');
      
                $collectionName = ($exchange == 'binance')? 'market_prices': 'market_prices_'.$exchange;
                $db = $CI->mongo_db->customQuery();
                $lookup = [
                    [
                        '$group' =>[
                            '_id'   => '$coin',
                            'price' => ['$first' => '$price'],
                        ]
                    ]
                ];

                $data = $db->$collectionName->aggregate($lookup);
                $response = iterator_to_array($data);
                $prices_arr = array_column($response, 'price', '_id');

                return $prices_arr;
            }
        }

        if (!function_exists('get_current_market_prices')) {
            function get_current_market_prices($exchange, $coins=[]){
        
                $CI = &get_instance();
                $db = $CI->mongo_db->customQuery();
        
                if (empty($coins)) {
                    $coins_collection = ($exchange == 'binance') ? 'coins' : 'coins_' . $exchange;
                    $where = [
                        'user_id' => 'global',
                    ];
                    if ($exchange == 'binance') {
                        $where['exchange_type'] = 'binance';
                    }
        
                    $coins_data = $db->$coins_collection->find($where);
                    $coins = iterator_to_array($coins_data);
                    if (!empty($coins)) {
                        $coins = array_column($coins, 'symbol');
                        $coins = array_unique($coins);
                        $coins = array_values($coins);
                        unset($coins_data);
                    }
                }
        
                $prices_collection = ($exchange == 'binance') ? 'market_prices' : 'market_prices_' . $exchange;
        
                $pipeline = [
                    [
                        '$match' => [
                            'coin' => ['$in' => $coins],
                        ],
                    ],
                    [
                        '$sort' => [
                            'created_date' => -1,
                        ],
                    ],
                    [
                        '$group' => [
                            '_id' => '$coin',
                            'price' => ['$first' => '$price'],
                        ],
                    ],
                ];
        
                $prices_data = $db->$prices_collection->aggregate($pipeline);
                $prices_arr = iterator_to_array($prices_data);
        
                if ($prices_arr) {
                    $prices_arr = array_column($prices_arr, 'price', '_id');
                    unset($prices_data);
                    return $prices_arr;
                }
                return [];
        
            }
        } //end function


        if(!function_exists('getErrorTrades')){
            function getErrorTrades($exchange, $admin_id){

                $exchange = (string)$exchange;

                $CI = &get_instance();
                $db = $CI->mongo_db->customQuery();

                $lookup = [
                    [
                        '$match' => [
                            'admin_id'                  =>  (string)$admin_id,
                            'application_mode'          =>  'live',
                            'status'                    =>  ['$nin'  =>    ['LTH','FILLED','new', 'new_ERROR', 'canceled','fraction_submitted_buy']],
                            'cost_avg'                  =>  ['$nin' => ['yes', 'taking_child', 'completed']],
                            'parent_status'             =>  ['$ne' => 'parent']
                        ]
                    ],

                    [
                        '$project' => [
                            '_id'                =>  ['$toString' =>  '$_id'],
                            'purchased_price'    =>  '$purchased_price',
                            'symbol'             =>   '$symbol'
                        ]
                    ],

                ];

                $collectionName = ($exchange == 'binance')? 'buy_orders': 'buy_orders_'.$exchange;

                $respopnse  = $db->$collectionName->aggregate($lookup);
                $resReturn  =  iterator_to_array($respopnse);

                return $resReturn;
            }
        }//end function
        

        if(!function_exists('getLastWeekSoldTrades')){
            function getLastWeekSoldTrades($exchange, $admin_id){

                $exchange = (string)$exchange;

                $CI = &get_instance();
                $db = $CI->mongo_db->customQuery();

                $startTime  =  $CI->mongo_db->converToMongodttime(date('Y-m-d 00:00:00', strtotime('-7 days')));
                $endTime    =  $CI->mongo_db->converToMongodttime(date('Y-m-d 23:59:59'));

                $lookup = [
                    [
                        '$match' => [
                            'admin_id'                  =>  (string)$admin_id,  
                            'application_mode'          =>  'live',
                            'status'                    =>  'FILLED',
                            'is_sell_order'             =>  'sold',
                            'modified_date'             =>   ['$gte' => $startTime,  '$lte' => $endTime],
                            'cost_avg'                  =>   ['$nin' => ['yes', 'taking_child', 'completed']],
                            'parent_status'             =>   ['$ne' => 'parent']  
                        ]
                    ],

                    [
                        '$project' => [
                            '_id'                =>  ['$toString' =>  '$_id'],
                            'purchased_price'    =>  '$purchased_price',
                            'symbol'             =>  '$symbol',
                            'market_sold_price'  =>  '$market_sold_price'
                        ]
                    ],

                ];

                $collectionName = ($exchange == 'binance')? 'sold_buy_orders': 'sold_buy_orders_'.$exchange;

                $respopnseSOld  = $db->$collectionName->aggregate($lookup);
                $resReturnSold  =  iterator_to_array($respopnseSOld);

                return $resReturnSold;
            }
        }//end function

        if(!function_exists('getLastWeekBuyButStillUnderOpenLTH')){
            function getLastWeekBuyButStillUnderOpenLTH($exchange, $admin_id){

                $exchange = (string)$exchange;

                $CI = &get_instance();
                $db = $CI->mongo_db->customQuery();

                $startTime  =  $CI->mongo_db->converToMongodttime(date('Y-m-d 00:00:00', strtotime('-7 days')));
                $endTime    =  $CI->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));

                $lookup = [
                    [
                        '$match' => [
                            'admin_id'                  =>  (string)$admin_id,
                            'application_mode'          =>  'live',
                            'status'                    =>  ['$nin' =>   ['credentials_ERROR','canceled_ERROR','error' ,'new', 'new_ERROR', 'canceled', 'pause', 'submitted_buy', 'fraction_submitted_buy']],
                            'created_date'              =>  ['$gte' => $startTime, '$lte' => $endTime],
                            'cost_avg'                  =>  ['$nin' => ['yes', 'taking_child', 'completed']],
                            'parent_status'             =>  ['$ne' => 'parent']
                        ]
                    ],

                    [
                        '$project' => [
                            '_id'             =>  ['$toString' =>  '$_id'],
                            'purchased_price' => '$purchased_price',
                            'symbol'          =>  '$symbol'
                        ]
                    ],

                ];

                $collectionName = ($exchange == 'binance')? 'buy_orders': 'buy_orders_'.$exchange;

                $respopnseOpen  = $db->$collectionName->aggregate($lookup);
                $resReturnOpen  =  iterator_to_array($respopnseOpen);
                return $resReturnOpen;
            }
        }//end function


        if(!function_exists('plCalulate')){
            function plCalulate($purchased_price, $market_sold_price, $coinSymbol, $exchange){
                
                if(empty($market_sold_price)){ // if not sold

                    $res = getMarketValue($coinSymbol, $exchange);

                    $pl =  ((($res[0]['price'] - $purchased_price) * 100) / $purchased_price);

                    return $pl;
                }else{   // if sold

                    $pl =  ((($market_sold_price - $purchased_price) * 100) / $purchased_price);
                    return $pl;
                }
 
            }
        }//end hlper


        //calculate today worth
        if(!function_exists('todayBuyBTCAndUsdt')){
            function todayBuyBTCAndUsdt($exchange, $admin_id){
                $ci = &get_instance();
                $db = $ci->mongo_db->customQuery();

                $btcCoinArray = ['XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC'];
                $coin_array = ['EOSUSDT', 'LTCUSDT','XRPUSDT','NEOUSDT', 'QTUMUSDT','BTCUSDT'];

                $buy_order       =  ($exchange == 'binance') ? 'buy_orders' : 'buy_orders_'.$exchange;
                $sold_buy_orders =  ($exchange == 'binance') ? 'sold_buy_orders' : 'sold_buy_orders_'.$exchange;

                $greater_date   =   $ci->mongo_db->converToMongodttime(date('Y-m-d 7:59:00', strtotime('-1 days')) );
                $less_date      =   $ci->mongo_db->converToMongodttime(date('Y-m-d 7:59:00'));
                $lookup = [
                    [

                        '$match' => [
                            "admin_id"          =>  (string)$admin_id,
                            "application_mode"  =>  "live",
                            "created_date"      =>  ['$gte' => $greater_date,  '$lte' => $less_date],
                            "symbol"            =>  ['$in' => $btcCoinArray]   
                        ]
                    ],

                    [
                        '$addFields' => [

                            'purchased_price' => ['$toDouble' => '$purchased_price'],
                            'quantity'        => ['$toDouble' => '$quantity']
                        ]
                    ],

                    [
                        '$project' => [
                            '_id'   =>  null,
                            'invest_btc'  => ['$sum' => [ ['$multiply' => ['$purchased_price' , '$quantity' ] ] ]] 
                        ]
                    ],

                    [
                        '$group' => [
                            '_id' => null,
                            'invest_btc'  => ['$sum' =>  '$invest_btc']
                        ]
                    ],
                ];

                $lookupusdt = [
                    [

                        '$match' => [
                            "admin_id"          =>  (string)$admin_id,
                            "application_mode"  =>  "live",
                            "created_date"      =>  ['$gte' => $greater_date,  '$lte' => $less_date],
                            "symbol"            =>  ['$in' => $coin_array]   
                        ]
                    ],

                    [
                        '$addFields' => [

                            'purchased_price' => ['$toDouble' => '$purchased_price'],
                            'quantity'        => ['$toDouble' => '$quantity']
                        ]
                    ],



                    [
                        '$project' => [
                            '_id' =>  null,
                            'invest_usdt'     => ['$sum' => [ ['$multiply' => ['$purchased_price' , '$quantity' ] ] ]]
                        ]
                    ],

                    [
                        '$group' => [
                            '_id' => null,
                            'invest_usdt'  =>  ['$sum' => '$invest_usdt'],
                        ]
                    ]
                ];

                $soldBtcWorth   =  $db->$sold_buy_orders->aggregate($lookup);
                $soldResultBtc  = iterator_to_array($soldBtcWorth);

                $btcWorth   =  $db->$buy_order->aggregate($lookup);
                $resultBtc  = iterator_to_array($btcWorth);

                $usdtWorth  =  $db->$buy_order->aggregate($lookupusdt);
                $resultUsdt = iterator_to_array($usdtWorth);

                $soldUsdtWorth  =  $db->$sold_buy_orders->aggregate($lookupusdt);
                $soldResultUsdt = iterator_to_array($soldUsdtWorth);

                $final = [];
                $final['sold_btc']  = $soldResultBtc;
                $final['btc']       = $resultBtc;
                $final['usdt']      = $resultUsdt;
                $final['sold_usdt'] = $soldResultUsdt;
                return $final;
            }

        }

        //calculate 7 days worth 
        if(!function_exists('savenDaysBuyBTCAndUsdt')){
            function savenDaysBuyBTCAndUsdt($exchange, $admin_id){

                $ci = &get_instance();
                $db = $ci->mongo_db->customQuery();

                $btcCoinArray = ['XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC'];
                $coin_array = ['EOSUSDT', 'LTCUSDT','XRPUSDT','NEOUSDT', 'QTUMUSDT','BTCUSDT'];

                $buy_order       =  ($exchange == 'binance') ? 'buy_orders' : 'buy_orders_'.$exchange;
                $sold_buy_orders =  ($exchange == 'binance') ? 'sold_buy_orders' : 'sold_buy_orders_'.$exchange;

                $greater_date   =   $ci->mongo_db->converToMongodttime(date('Y-m-d 7:59:00', strtotime('-7 days')) );
                $less_date      =   $ci->mongo_db->converToMongodttime(date('Y-m-d 7:59:00'));
                $lookup = [
                    [

                        '$match' => [
                            "admin_id"          =>  (string)$admin_id,
                            "application_mode"  =>  "live",
                            "created_date"      =>  ['$gte' => $greater_date,  '$lte' => $less_date],
                            "symbol"            =>  ['$in' => $btcCoinArray]   
                        ]
                    ],

                    [
                        '$addFields' => [

                            'purchased_price' => ['$toDouble' => '$purchased_price'],
                            'quantity'        => ['$toDouble' => '$quantity']
                        ]
                    ],

                    [
                        '$project' => [
                            '_id'   =>  null,
                            'invest_btc'  => ['$sum' => [ ['$multiply' => ['$purchased_price' , '$quantity' ] ] ]] 
                        ]
                    ],

                    [
                        '$group' => [
                            '_id' => null,
                            'invest_btc'  => ['$sum' =>  '$invest_btc']
                        ]
                    ],
                ];

                $lookupusdt = [
                    [

                        '$match' => [
                            "admin_id"          =>  (string)$admin_id,
                            "application_mode"  =>  "live",
                            "created_date"      =>  ['$gte' => $greater_date,  '$lte' => $less_date],
                            "symbol"            =>  ['$in' => $coin_array]   
                        ]
                    ],

                    [
                        '$addFields' => [

                            'purchased_price' => ['$toDouble' => '$purchased_price'],
                            'quantity'        => ['$toDouble' => '$quantity']
                        ]
                    ],



                    [
                        '$project' => [
                            '_id' =>  null,
                            'invest_usdt'     => ['$sum' => [ ['$multiply' => ['$purchased_price' , '$quantity' ] ] ]]
                        ]
                    ],

                    [
                        '$group' => [
                            '_id' => null,
                            'invest_usdt'  =>  ['$sum' => '$invest_usdt'],
                        ]
                    ]
                ];

                $soldBtcWorth   =  $db->$sold_buy_orders->aggregate($lookup);
                $soldResultBtc  = iterator_to_array($soldBtcWorth);

                $btcWorth   =  $db->$buy_order->aggregate($lookup);
                $resultBtc  = iterator_to_array($btcWorth);

                $usdtWorth  =  $db->$buy_order->aggregate($lookupusdt);
                $resultUsdt = iterator_to_array($usdtWorth);

                $soldUsdtWorth  =  $db->$sold_buy_orders->aggregate($lookupusdt);
                $soldResultUsdt = iterator_to_array($soldUsdtWorth);

                $final = [];
                $final['sold_btc']  = $soldResultBtc;
                $final['btc']       = $resultBtc;
                $final['usdt']      = $resultUsdt;
                $final['sold_usdt'] = $soldResultUsdt;

                return $final;
            }

        }

        if(!function_exists('calculateManulOrdersInvestment')){
            function calculateManulOrdersInvestment($admin_id, $exchange){

                $buyCollection  = ($exchange == 'binance') ? 'buy_orders' : 'buy_orders_'.$exchange;
                $soldCollection = ($exchange == 'binance') ? 'sold_buy_orders' : 'sold_buy_orders_'.$exchange;

                $btc_coin_in_arrBinance = ['XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC'];
                $coinArrayUSDTBinanceTwo   = ['EOSUSDT', 'LTCUSDT','XRPUSDT','NEOUSDT', 'QTUMUSDT',  'BTCUSDT'];

                $admin_id = (string)$admin_id;
                $CI = &get_instance();
                $db = $CI->mongo_db->customQuery();

                $current = $CI->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));
                $mongo_time = $CI->mongo_db->converToMongodttime(date('Y-m-d H:i:s', strtotime('-1 month')));

                //manul order calculation start
                $agregateQueryBTC = [
                    [
                        '$match' => [

                            'application_mode'       =>  'live',
                            'admin_id'               =>  $admin_id,
                            'created_date'           =>  ['$gte' => $mongo_time, '$lte' => $current ],
                            'is_sell_order'          =>  'sold',
                            'trigger_type'           =>  'no',
                            'resume_status'          =>  ['$exists' => false],
                            'status'                 =>  'FILLED',
                            'cost_avg'               =>  ['$nin' => ['yes', 'taking_child', 'completed']],
                            'cavg_parent'            =>  ['$exists' => false ],
                            'symbol'                 =>  ['$in' => $btc_coin_in_arrBinance]
                        ]
                    ],

                    [
                        '$addFields' => [
                        'market_sold_price'  =>  ['$toDouble' => '$market_sold_price'],   
                        'purchased_price'    =>  ['$toDouble' => '$purchased_price'],
                        'quantity'           =>  ['$toDouble' => '$quantity']
                        ]
                    ],


                    [
                        '$group' => [
                        
                        '_id'                      =>  null,      
                        // 'sumOfAllPurchasedPrices'  =>  ['$sum' =>  ['$divide'   => [  ['$subtract' => ['$market_sold_price', '$purchased_price']], '$purchased_price']] ],
                        'btcsold'               =>  ['$sum' =>  ['$multiply' => ['$purchased_price',  '$quantity']] ],
                        // 'btcInvestmentCalBinance'  =>  ['$sum' =>  ['$multiply' => ['$purchased_price', '$quantity']] ],
                        // 'investProfitBTCBinance'   =>  ['$sum' =>  ['$multiply' => ['$market_sold_price', '$quantity']] ],
                        'count'                    =>  ['$sum' => 1]

                        ]
                    ],
                
                ];

                $agregateQueryUSDT = [
                    [
                        '$match' => [

                        'application_mode'       =>  'live',
                        'admin_id'               =>  $admin_id,
                        'created_date'           =>  ['$gte' => $mongo_time, '$lte' => $current ],
                        'is_sell_order'          =>  'sold',
                        'trigger_type'           =>  "no",
                        'status'                 =>  'FILLED',
                        'resume_status'          =>  ['$exists' => false],
                        'cost_avg'               =>  ['$nin' => ['yes', 'taking_child', 'completed']],
                        'cavg_parent'            =>  ['$exists' => false],
                        'symbol'                 =>  ['$in' => $coinArrayUSDTBinanceTwo]
                        ]
                    ],

                    [
                        '$addFields' => [
                        // 'market_sold_price'  =>  ['$toDouble' => '$market_sold_price'],
                        'purchased_price'    =>  ['$toDouble' => '$purchased_price'],
                        'quantity'           =>  ['$toDouble' => '$quantity']
                        ]
                    ],

                

                    [
                        '$group' => [
                        
                        '_id'                      =>  null,      
                        // 'sumOfAllPurchasedPrices'  =>  ['$sum' =>  ['$divide'   => [  ['$subtract' => ['$market_sold_price', '$purchased_price']], '$purchased_price']] ],
                        'usdtsold'              =>  ['$sum' =>  ['$multiply' => ['$purchased_price',  '$quantity']] ],
                        // 'usdtInvestmentCalBinance' =>  ['$sum' =>  ['$multiply' => ['$purchased_price', '$quantity']] ],
                        // 'investProfitUSDTBinance'  =>  ['$sum' =>  ['$multiply' => ['$market_sold_price', '$quantity']] ],
                        'count'                    =>  ['$sum' => 1 ]       

                        ]
                    ],
                
                ];

                $btcdataManul      =   $db->$soldCollection->aggregate($agregateQueryBTC);
                $btcdataManulRes   =   iterator_to_array($btcdataManul);

                $usdtdataManul     =   $db->$soldCollection->aggregate($agregateQueryUSDT);
                $usdtdataManulRes  =   iterator_to_array($usdtdataManul);


                $agregateQueryBTCOpenLTH = [
                    [
                        '$match' => [

                            'application_mode'       =>  'live',
                            'admin_id'               =>  $admin_id,
                            'created_date'           =>  ['$gte' => $mongo_time, '$lte' => $current ],
                            'is_sell_order'          =>  ['$ne' => 'sold'],
                            'trigger_type'           =>  'no',
                            'resume_status'          =>  ['$exists' => false],
                            'status'                 =>  ['$in' => ['FILLED',  'LTH']],
                            'cost_avg'               =>  ['$nin' => ['yes', 'taking_child', 'completed']],
                            'cavg_parent'            =>  ['$exists' => false ],
                            'symbol'                 =>  ['$in' => $btc_coin_in_arrBinance],
                            'count_avg_order'        =>  ['$exists' => false]
                        ]
                    ],

                    [
                        '$addFields' => [
                        'purchased_price'    =>  ['$toDouble' => '$purchased_price'],
                        'quantity'           =>  ['$toDouble' => '$quantity']
                        ]
                    ],


                    [
                        '$group' => [
                        '_id'                      =>  null,      
                        'btcOpenLth'               =>  ['$sum' =>  ['$multiply' => ['$purchased_price',  '$quantity']] ],
                        'count'                    =>  ['$sum' => 1]

                        ]
                    ],
                
                ];

                $agregateQueryUSDTOpenLTH = [
                    [
                        '$match' => [

                            'application_mode'       =>  'live',
                            'admin_id'               =>  $admin_id,
                            'created_date'           =>  ['$gte' => $mongo_time, '$lte' => $current ],
                            'is_sell_order'          =>  ['$ne' => 'sold'],
                            'trigger_type'           =>  'no',
                            'resume_status'          =>  ['$exists' => false],
                            'status'                 =>  ['$in' => ['FILLED',  'LTH']],
                            'cost_avg'               =>  ['$nin' => ['yes', 'taking_child', 'completed']],
                            'cavg_parent'            =>  ['$exists' => false ],
                            'symbol'                 =>  ['$in' => $btc_coin_in_arrBinance],
                            'count_avg_order'        =>  ['$exists' => false]
                        ]
                    ],

                    [
                        '$addFields' => [
                        'purchased_price'    =>  ['$toDouble' => '$purchased_price'],
                        'quantity'           =>  ['$toDouble' => '$quantity']
                        ]
                    ],


                    [
                        '$group' => [
                        
                        '_id'                      =>  null,      
                        'usdtopenLth'              =>  ['$sum' =>  ['$multiply' => ['$purchased_price',  '$quantity']] ],
                        'count'                    =>  ['$sum' => 1 ]       

                        ]
                    ],
                
                ];

                $btcdataManulopen      =   $db->$buyCollection->aggregate($agregateQueryBTCOpenLTH);
                $btcdataManulResOpen   =   iterator_to_array($btcdataManulopen);

                $usdtdataManulOpen     =   $db->$buyCollection->aggregate($agregateQueryUSDTOpenLTH);
                $usdtdataManulResOpen  =   iterator_to_array($usdtdataManulOpen);

                $returnArray = [

                    'btcInvest'       =>    $btcdataManulRes[0]['btcsold'] + $btcdataManulResOpen[0]['btcOpenLth'],
                    'usdtInvest'      =>    $usdtdataManulRes[0]['usdtsold'] + $usdtdataManulResOpen[0]['usdtopenLth'],
                                     
                ];

                return $returnArray;
            }
        }


        //calculate balance percentages
        if(!function_exists('calculatePercentage')){
            function calculatePercentage($balance, $customPkge, $total_btc_balanceBinance){

                $btcPkg  = 0;
                
                if($customPkge > 0 ){
                    
                    $btcPkg  = ($customPkge   >= $total_btc_balanceBinance)   ?  $total_btc_balanceBinance  : $customPkge  ;
                }

                if($btcPkg != 0 && $balance != 0 ){
                
                    $lth_balance_per = ($balance / $btcPkg)*100;           
                }else{
                
                    $lth_balance_per = 0;
                } 

                return $lth_balance_per;

            }
        }
        function get_kraken_details($user_id){
            $CI = &get_instance();
            $db = $CI->mongo_db->customQuery();
            $user_data = $db->kraken_credentials->find(['user_id'=>(string)$user_id]);
            $user_arr = iterator_to_array($user_data);
            if(count($user_arr) > 0){
                return $user_arr[0]['trading_ip'];
            }else{
                return 'N/A';
            }
        }