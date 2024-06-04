<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monthly_trading_volume extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //load main template
        $this->stencil->layout('admin_layout');
        
        // ini_set("display_errors", E_ALL);
        // error_reporting(E_ALL);
        //load required slices
        $this->stencil->slice('admin_header_script');
        $this->stencil->slice('admin_header');
        $this->stencil->slice('admin_left_sidebar');
        $this->stencil->slice('admin_footer_script');
        // Load Modal
        $this->load->model('admin/mod_login');
        $this->load->model('admin/mod_users');
        $this->load->model('admin/mod_coins');

        $this->load->helper('new_common_helper');
    }

    public function index(){
        
        $this->mod_login->verify_is_admin_login();
        // $custom = $this->mongo_db->customQuery();
        $this->load->library('mongo_db_3');
        $db_3 = $this->mongo_db_3->customQuery();

        $coin_array_all = $this->mod_coins->get_all_coins();

        if( $_GET['button'] == 'reset' ){

            $this->session->unset_userdata('user_post_data');
        }

        if($this->input->post()){

            $setSession['user_post_data'] = $this->input->post();
            $this->session->set_userdata($setSession);
        }

        $getFilterData = $this->session->userdata('user_post_data');

        if($getFilterData){
            if( !empty($getFilterData['filter_by_coin']) ){ 

                $searchCriteria['coin']['$in']  =   $getFilterData['filter_by_coin'];
            }else{

                $searchCriteria['coin'] = 'BTCUSDT';
            }

            if( !empty($getFilterData['start_date']) && !empty($getFilterData['end_date'])){
                $startDate  =  $this->mongo_db->converToMongodttime($getFilterData['start_date']);
                $endDate    =  $this->mongo_db->converToMongodttime($getFilterData['end_date']);
                
                $searchCriteria['timestampDate'] = ['$gte' => $startDate, '$lte' => $endDate];

            } else{

                $startDate  =  $this->mongo_db->converToMongodttime(date('Y-m-d 00:00'));
                $endDate    =  $this->mongo_db->converToMongodttime(date('Y-m-d 23:00'));

                $searchCriteria['timestampDate'] = ['$gte' => $startDate, '$lte' => $endDate];

            }

            $lookup = [
                [
                    '$match' => $searchCriteria
                ],
                [
                    '$project' => [

                        '_id' => 1,
                        'timestampDate' =>  '$timestampDate',
                        'coins'         =>  '$coin',
                        'high'          =>  '$high',
                        'low'           =>  '$low',
                    ]
                ],
                    
                // [
                //     '$limit' => 20
                // ]
            ];


            // $limitCount = count($getFilterData['filter_by_coin']);
            $get = $db_3->market_chart->aggregate($lookup);  
            $res = iterator_to_array($get);

        }

		$data['coins']   = $coin_array_all;
        $data['resData'] = $res;

        $this->stencil->paint('admin/monthly_trading_volume/high_low_chart', $data); 
    }
    //////////////////////////////
    //////////Cron for Chart ///////
    //////////////////////////////



    ///////////////////// update for old record in cron ///////////
        public function update_feeanalysis_by_exchange($exchange = '',$start_date = ''){
     
            if($exchange!=''){
                $exchange_lookup=$exchange;
                $collection_name="opportunity_logs_".$exchange_lookup;
                    if($exchange == "kraken"){
                        $collection_name_trades="buy_orders_".$exchange_lookup; 
                        $collection_name_trades_sold="sold_buy_orders_".$exchange_lookup; 
                    }else{
                        $collection_name_trades="buy_orders"; 
                        $collection_name_trades_sold="sold_buy_orders";
                    } 
            }else{
                $exchange_lookup = "binance";
                $collection_name="opportunity_logs_".$exchange_lookup;
                $collection_name_trades="buy_orders";
                $collection_name_trades_sold="sold_buy_orders";
            }
            if($start_date!=''){
                //var_dump($start_date);
                $st_date=explode("T",$start_date);
                $start_date_new = $st_date[0].' '.$st_date[1];
                $newDate = date("Y-m-d h:i", strtotime($start_date_new));
                $endDatee = date("Y-m-d", strtotime($start_date_new));
                $startDate = $this->mongo_db->converToMongodttime(date('Y-m-d h:i', strtotime('-1 day', strtotime($newDate))));
                $endDate = $this->mongo_db->converToMongodttime(date('Y-m-d h:i', strtotime($newDate)));
            }else{
                $startDate = $this->mongo_db->converToMongodttime(date('Y-m-d 07:59', strtotime('-1 days') ) );
                $endDatee = date("Y-m-d");
                $endDate   = $this->mongo_db->converToMongodttime(date('Y-m-d 07:59'));
            } 
        $lookup = [
            [
                '$match' => [
                    
                    'created_date' => ['$gte' => $startDate, '$lte' => $endDate],
                    'level'        => ['$in'  => ['level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18']],
                ]
            ],
            [
                '$group' => [

                    '_id' => null,
                    'btc_invest_amount'         => ['$sum' => '$btc_invest_amount'],
                    'usdt_invest_amount'        => ['$sum' => '$usdt_invest_amount'],
                    'buy_commision'             => ['$sum' => '$buy_commision'],
                    'sell_fee_coin_USDT'        => ['$sum' => '$sell_commision_qty_USDT'],
                    'sell_commission'           => ['$sum' => '$sell_commission'],
                    'buy_commision_qty'         => ['$sum' => '$buy_commision_qty_USDT'],
                    'count'                     => ['$sum' => 1] ,    
                    'sell_btc_in_$'             => ['$sum' => '$sell_btc_in_$'],
                    'sell_usdt'                 => ['$sum' => '$sell_usdt'],
                    'total_sell_in_usdt'        => ['$sum' => '$total_sell_in_usdt'],
                ]
            ],
        ];

    
        $db = $this->mongo_db->customQuery();
        
        $result = $db->$collection_name->aggregate($lookup);
        $resultResponse = iterator_to_array($result);

        $convertedUSDAmount   =   ($resultResponse[0]['btc_invest_amount'] > 0)  ?  convertCoinBalanceIntoUSDT('BTCUSDT', $resultResponse[0]['btc_invest_amount'], $exchange_lookup) : 0;
        $sell_commission_Usdt =   ($resultResponse[0]['sell_commission'] > 0)  ?  convertCoinBalanceIntoUSDT('BNB', $resultResponse[0]['sell_commission'], $exchange_lookup) : 0;
        
        //new query for calculate total buy/sell trade counts
        $lookupCount = [
            [
                '$match' => [
                    
                    'created_date' => ['$gte' => $startDate, '$lte' => $endDate],
                    'level'        => ['$in'  => ['level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18']],
                ]
            ],
            
            [
                '$addFields' => [

                    'open_lth'      => ['$toDouble' => '$open_lth'],
                    'sold'          => ['$toDouble' => '$sold'],
                    'other_status'  => ['$toDouble' => '$other_status'],
                    

                ]
            ],

            [
                '$project' => [

                    '_id' => 1,
                    'open_lth'=>'$open_lth',
                    'sold'=>'$sold',
                    'other_status'=>'$other_status',
                    'coin_arry'=> [ '$substr'=> [ '$coin', [ '$subtract'=> [ ['$strLenCP' => '$coin'], 3 ] ], -1 ]],
                    'totalBuyCount'  =>  ['$sum' => ['$open_lth', '$sold', '$other_status']],
                    'totalSoldCount' =>  ['$sum' => '$sold'],
                ]
            ],

            [
                '$group' => [
                    '_id' => null,
                    'totalBuyCount'   => ['$sum' => '$totalBuyCount'],
                    'totalSoldCount'  => ['$sum' => '$totalSoldCount'],
                    'totalBuyBtc' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$eq' => ['$coin_arry', 'BTC']],
                                'then' => ['$sum' => ['$open_lth', '$sold', '$other_status']],
                                'else' => 0
                            ]
                        ]
                    ],
                    'totalBuyUsdt' => [
                        '$sum' => [
                            '$cond' => [
                               'if' => ['$eq' => ['$coin_arry', 'SDT']],
                                'then' => ['$sum' => ['$open_lth', '$sold', '$other_status']],
                                'else' => 0
                            ]
                        ]
                    ],
                   'totalSoldUsdt' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$eq' => ['$coin_arry', 'SDT']],
                                'then' => ['$sum' => '$sold'],
                                'else' => 0
                            ]
                        ]
                    ],
                    'totalSoldBtc' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$eq' => ['$coin_arry', 'BTC']],
                                'then' => ['$sum' => '$sold'],
                                'else' => 0
                            ]
                        ]
                    ],
                    'totalBtcopportunities' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$eq' => ['$coin_arry', 'BTC']],
                                'then' => 1,
                                'else' => 0
                            ]
                        ]
                    ],
                    'totalusdtopportunities' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$eq' => ['$coin_arry', 'SDT']],
                                'then' => 1,
                                'else' => 0
                            ]
                        ]
                    ],
                    
                ]
            ],
        ];

        $resultCount         = $db->$collection_name->aggregate($lookupCount);
        $resultResponseCount = iterator_to_array($resultCount);
    // if(isset($_COOKIE['sheraz']) && $_COOKIE['sheraz'] == 1 ){
    //     echo "<pre>";
    //     print_r($resultResponseCount);exit;
    // }
        //get expected count
        $getTradeNumberCount = [
            'exchange'      =>  $exchange_lookup,
            'created_date'  =>   $this->mongo_db->converToMongodttime(date('Y-m-d')),
        ];
        $getTradeCount          =   $db->expected_trade_buy_count_history->find($getTradeNumberCount);
        $expectedCountResponse  =   iterator_to_array($getTradeCount);
        $daily_date_wise_trade_errors = [
                    ['$match'=> [
                        'modified_date'  =>  ['$gte' => $startDate, '$lte' => $endDate],
                        'trigger_type'=>"barrier_percentile_trigger",
                        'application_mode'=>"live",
                        'parent_status'=>['$ne'=>'parent']]],
                         [
                             '$group' => [
                                 '_id'=>null,
                        'errors_count' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$status',["FILLED_ERROR","IP_BAN_ERROR","COIN_BAN_ERROR","KEYFILLED_ERROR","TEMPAPILOCK_ERROR","API_ERROR","APIKEY_ERROR","APINONCE_ERROR"]]],
                                'then' => 1,
                                'else' => 0
                                ]
                            ]
                        ],
                        'cancelled_count' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$status',["canceled"]]],
                                'then' => 1,
                                'else' => 0
                                ]
                            ]
                        ],
                             ]
                         ]
                ];
                $cursor_trade_errors_count_binance = $db->$collection_name_trades->aggregate($daily_date_wise_trade_errors);
                $bin_errors_results_daily = iterator_to_array($cursor_trade_errors_count_binance);

                $total_buy_count = [
                    ['$match'=>
                        [
                          'created_date'=>['$gte'=>$startDate,'$lte'=>$endDate],
                          'status'=>['$in'=>['LTH','FILLED']],
                          'trigger_type'=>"barrier_percentile_trigger",
                          'application_mode'=>"live",
                          'parent_status'=>['$ne'=>'parent']
                        ]], 
                        [
                        '$group'=>
                         [
                          '_id'=> null,
                          'total_buy_count'=>[
                            '$sum'=>1
                          ]
                        ]]];
                $cursor_trade_count = $db->$collection_name_trades->aggregate($total_buy_count);
                $buy_count_trades_daily = iterator_to_array($cursor_trade_count);

                $total_sold_count_same_day = [
                    ['$match'=>
                        [
                          'buy_date'=>['$gte'=>$startDate,'$lte'=>$endDate],
                          'status'=>'FILLED',
                          'is_sell_order'=>'sold',
                          'application_mode'=>"live"
                        ]], 
                        [
                        '$group'=>
                         [
                          '_id'=> null,
                          'total_sold_count_same'=>[
                            '$sum'=>1
                          ]
                        ]]
                    ];
                $cursor_trade_count_same = $db->$collection_name_trades_sold->aggregate($total_sold_count_same_day);
                $sold_count_trades_daily_same = iterator_to_array($cursor_trade_count_same);

                $total_sold_count = [
                    ['$match'=>
                        [
                          'modified_date'=>['$gte'=>$startDate,'$lte'=>$endDate],
                          'status'=>'FILLED',
                          'is_sell_order'=>'sold',
                          'application_mode'=>"live"
                        ]], 
                        [
                        '$group'=>
                         [
                          '_id'=> null,
                          'total_sold_count'=>[
                            '$sum'=>1
                          ]
                        ]]
                    ];
                $cursor_trade_count = $db->$collection_name_trades_sold->aggregate($total_sold_count);
                $sold_count_trades_daily = iterator_to_array($cursor_trade_count);


                $total_trades_buy_count = $buy_count_trades_daily[0]['total_buy_count'] + $sold_count_trades_daily_same[0]['total_sold_count_same'] ;
                $total_trades_sold_count = $sold_count_trades_daily[0]['total_sold_count'];

    ///// count of cost avg child orders sold and buy.(sheraz sept 29 2021)
                    $total_buy_count = [
                    ['$match'=>
                        [
                          'created_date'=>['$gte'=>$startDate,'$lte'=>$endDate],
                          'status'=>['$in'=>['LTH','FILLED']],
                          'cost_avg'=>['$in'=>['yes','taking_child']],
                          'trigger_type'=>"barrier_percentile_trigger",
                          'application_mode'=>"live",
                          'cavg_parent'=>['$exists'=>false],
                          'count_avg_order'=>['$exists'=>false]
                        ]], 
                        [
                        '$group'=>
                         [
                          '_id'=> null,
                          'total_buy_count'=>[
                            '$sum'=>1
                          ]
                        ]]];
                $cursor_trade_count = $db->$collection_name_trades->aggregate($total_buy_count);
                $cavg_child_buy_count_trades_daily = iterator_to_array($cursor_trade_count);

                $total_sold_count_same_day = [
                    ['$match'=>
                        [
                          'buy_date'=>['$gte'=>$startDate,'$lte'=>$endDate],
                          'status'=>'FILLED',
                          'cost_avg'=>['$in'=>['yes','taking_child','completed']],
                          'cavg_parent'=>['$exists'=>false],
                          'count_avg_order'=>['$exists'=>false],
                          'is_sell_order'=>'sold',
                          'application_mode'=>"live"
                        ]], 
                        [
                        '$group'=>
                         [
                          '_id'=> null,
                          'total_sold_count_same'=>[
                            '$sum'=>1
                          ]
                        ]]
                    ];
                $cursor_trade_count_same = $db->$collection_name_trades_sold->aggregate($total_sold_count_same_day);
                $cvg_sold_count_trades_daily_same = iterator_to_array($cursor_trade_count_same);

                $total_sold_count = [
                    ['$match'=>
                        [
                          'modified_date'=>['$gte'=>$startDate,'$lte'=>$endDate],
                          'status'=>'FILLED',
                          'cost_avg'=>['$in'=>['yes','taking_child','completed']],
                          'cavg_parent'=>['$exists'=>false],
                          'count_avg_order'=>['$exists'=>false],
                          'is_sell_order'=>'sold',
                          'application_mode'=>"live"
                        ]], 
                        [
                        '$group'=>
                         [
                          '_id'=> null,
                          'total_sold_count'=>[
                            '$sum'=>1
                          ]
                        ]]
                    ];
                $cursor_trade_count = $db->$collection_name_trades_sold->aggregate($total_sold_count);
                $cvg_total_child_sold_count_trades_daily = iterator_to_array($cursor_trade_count);
        // Adding buy and sell of same date just to make buy count accurate accordingly.
                $cost_avg_total_trades_buy_count = $cavg_child_buy_count_trades_daily[0]['total_buy_count'] + $cvg_sold_count_trades_daily_same[0]['total_sold_count_same'] ;
        // total sold count dispite of when they created but sold today.
                $cost_avg_total_trades_sold_count = $cvg_total_child_sold_count_trades_daily[0]['total_sold_count'] ;
        // end count of cost avg orders count sold and buy.(sheraz sept 29 2021).


                // count of cost avg OPEN ledger orders sold and buy.(sheraz oct 05 2021)
                    $total_buy_count = [
                    ['$match'=>
                        [
                          'created_date'=>['$gte'=>$startDate,'$lte'=>$endDate],
                          'cost_avg'=>['$ne'=>'completed'],
                          'application_mode'=>"live",
                          'cavg_parent'=>'yes',
                         
                        ]], 
                        [
                        '$group'=>
                         [
                          '_id'=> null,
                          'total_buy_count'=>[
                            '$sum'=>1
                          ]
                        ]]];
                $cursor_trade_count = $db->$collection_name_trades->aggregate($total_buy_count);
                $cavg_ledger_buy_count_trades_daily = iterator_to_array($cursor_trade_count);

                $total_sold_count = [
                    ['$match'=>
                        [
                          'modified_date'=>['$gte'=>$startDate,'$lte'=>$endDate],
                          'status'=>'FILLED',
                          'cost_avg'=>['$in'=>['completed']],
                          'cavg_parent'=>'yes',
                          'is_sell_order'=>'sold',
                          'application_mode'=>"live"
                        ]], 
                        [
                        '$group'=>
                         [
                          '_id'=> null,
                          'total_sold_count'=>[
                            '$sum'=>1
                          ]
                        ]]
                    ];
                $cursor_trade_count = $db->$collection_name_trades_sold->aggregate($total_sold_count);
                $cvg_total_ledger_sold_count_trades_daily = iterator_to_array($cursor_trade_count);
         
                $cost_avg_ledger_open_total_trades_buy_count = $cavg_ledger_buy_count_trades_daily[0]['total_buy_count'];
            
                $cost_avg_ledger_close_total_trades_sold_count = $cvg_total_ledger_sold_count_trades_daily[0]['total_sold_count'] ;
    
        // end count of cost avg ledger orders count sold and buy.(sheraz oct 05 2021).

       
        $upsertedArray = [
            'created_date'           =>  $endDate,
            'total_invest'           =>  (float)($convertedUSDAmount + $resultResponse[0]['usdt_invest_amount']),
            'totaolInvestInbtc'      =>  $convertedUSDAmount,
            'totalInvestInbtc'       =>  $resultResponse[0]['btc_invest_amount'],
            'totaolInvestInUSDT'     =>  $resultResponse[0]['usdt_invest_amount'],
            'buy_commision'          =>  (float)($resultResponse[0]['buy_commision'] + $resultResponse[0]['buy_commision_qty_USDT']),            
            'sell_commission'        =>  (float)($sell_commission_Usdt + $resultResponse[0]['sell_fee_coin_USDT']),
            'numberOfOpportunities'  =>  $resultResponse[0]['count'],
            'totalBuyTradeCount'     =>  (float)($total_trades_buy_count),
            'totalSoldTradeCount'    =>  (float)($total_trades_sold_count),
            'totalBuyBtc'            =>  (float)($resultResponseCount[0]['totalBuyBtc']),
            'totalBuyUsdt'           =>  (float)($resultResponseCount[0]['totalBuyUsdt']),
            'totalSoldUsdt'          =>  (float)($resultResponseCount[0]['totalSoldUsdt']),
            'totalSoldBtc'           =>  (float)($resultResponseCount[0]['totalSoldBtc']),
            'expected_btc_count'     =>  (float)($expectedCountResponse[0]['total_btc_count']),
            'expected_usdt_count'    =>  (float)($expectedCountResponse[0]['total_usdt_count']),
            'month'                  =>  $endDatee,
            'exchange'               =>  $exchange_lookup,
            'mode'                   =>  'live',
            'sell_btc_in_$'          =>  (float)($resultResponse[0]['sell_btc_in_$']),
            'sell_usdt'              =>  (float)($resultResponse[0]['sell_usdt']),
            'total_sell_in_usdt'     =>  (float)($resultResponse[0]['total_sell_in_usdt']),
            'total_btc_opportunities'  =>(float)($resultResponseCount[0]['totalBtcopportunities']),
            'total_usdt_opportunities' =>(float)($resultResponseCount[0]['totalusdtopportunities']),
            'total_trades_errors_count'=>(float)($bin_errors_results_daily[0]['errors_count']),
            'total_trades_cancelled_count'=>(float)($bin_errors_results_daily[0]['cancelled_count']),
            'total_cavg_child_buy'=>(float)$cost_avg_total_trades_buy_count,
            'total_cavg_child_sold'=>(float)$cost_avg_total_trades_sold_count,
            'total_cavg_ledger_closed'=>(float)$cost_avg_ledger_close_total_trades_sold_count,
            'total_cavg_ledger_open'=>(float)$cost_avg_ledger_open_total_trades_buy_count,
        ];

        // echo "<pre>";
        // print_r($upsertedArray);exit;
        $upsertedWhere['month']          =  $endDatee;
        $upsertedWhere['exchange']       =  $exchange_lookup;
        $upsertedWhere['mode']           =  'live';
        $getRes = $db->daily_investment_chart_data->updateOne($upsertedWhere, ['$set' => $upsertedArray],  ['upsert' => true]);
        echo "<br>modified count". $getRes->getModifiedCount();
        echo "<br>upserted count". $getRes->getUpsertedCount();
        echo "<br>sucessfull updated";

    }
    ////////////////////////////end of sheraz's code /////////////
    //previousday fee analsis report binance
    public function fee_analysis_binance(){

        $this->update_feeanalysis_by_exchange('binance');
        return;


        ini_set("display_errors", E_ALL);
        error_reporting(E_ALL);
        $coin_arrayBtc  = ['XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC'];
        $coin_arrayUSDT = ['EOSUSDT', 'LTCUSDT','XRPUSDT','NEOUSDT', 'QTUMUSDT','BTCUSDT'];

        $startDate = $this->mongo_db->converToMongodttime(date('Y-m-d 07:59', strtotime('-1 days') ) );
        $endDate   = $this->mongo_db->converToMongodttime(date('Y-m-d 07:59'));
        
        $lookup = [
            [
                '$match' => [
                    
                    'created_date' => ['$gte' => $startDate, '$lte' => $endDate],
                    'level'        => ['$in'  => ['level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18']],
                ]
            ],
            [
                '$group' => [

                    '_id' => null,
                    'btc_invest_amount'         => ['$sum' => '$btc_invest_amount'],
                    'usdt_invest_amount'        => ['$sum' => '$usdt_invest_amount'],
                    'buy_commision'             => ['$sum' => '$buy_commision'],
                    'sell_fee_coin_USDT'        => ['$sum' => '$sell_commision_qty_USDT'],
                    'sell_commission'           => ['$sum' => '$sell_commission'],
                    'buy_commision_qty'         => ['$sum' => '$buy_commision_qty_USDT'],
                    'count'                     => ['$sum' => 1 ] ,    
                    'sell_btc_in_$'             => ['$sum' => '$sell_btc_in_$'],
                    'sell_usdt'                 => ['$sum' => '$sell_usdt'],
                    'total_sell_in_usdt'        => ['$sum' => '$total_sell_in_usdt'],
                ]
            ],
        ];

    
        $db = $this->mongo_db->customQuery();
        
        $result = $db->opportunity_logs_binance->aggregate($lookup);
        $resultResponse = iterator_to_array($result);

        $convertedUSDAmount   =   ($resultResponse[0]['btc_invest_amount'] > 0)  ?  convertCoinBalanceIntoUSDT('BTCUSDT', $resultResponse[0]['btc_invest_amount'], 'binance') : 0;
        $sell_commission_Usdt =   ($resultResponse[0]['sell_commission'] > 0)  ?  convertCoinBalanceIntoUSDT('BNB', $resultResponse[0]['sell_commission'], 'binance') : 0;
        
        //new query for calculate total buy/sell trade counts
        $lookupCount = [
            [
                '$match' => [
                    
                    'created_date' => ['$gte' => $startDate, '$lte' => $endDate],
                    'level'        => ['$in'  => ['level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18']],
                ]
            ],
            
            [
                '$addFields' => [

                    'open_lth'      => ['$toDouble' => '$open_lth'],
                    'sold'          => ['$toDouble' => '$sold'],
                    'other_status'  => ['$toDouble' => '$other_status'],
                    

                ]
            ],

            [
                '$project' => [

                    '_id' => 1,

                    'totalBuyCount'  =>  ['$sum' => ['$open_lth', '$sold', '$other_status']],
                    'totalSoldCount' =>  ['$sum' => '$sold'],

                     'totalBuyBtc' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$coin', $coin_arrayBtc]],
                                'then' => ['$sum' => ['$open_lth', '$sold', '$other_status']],
                                'else' => 0
                            ]
                        ]
                    ],

                    'totalBuyUsdt' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$coin' , $coin_arrayUSDT]],
                                'then' => ['$sum' => ['$open_lth', '$sold', '$other_status']],
                                'else' => 0
                            ]
                        ]
                    ],

                    'totalSoldUsdt' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$coin',  $coin_arrayUSDT]],
                                'then' => ['$sum' => '$sold'],
                                'else' => 0
                            ]
                        ]
                    ],

                    'totalSoldBtc' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$coin', $coin_arrayBtc]],
                                'then' => ['$sum' => '$sold'],
                                'else' => 0
                            ]
                        ]
                    ],

                ]
            ],

            [
                '$group' => [
                    '_id' => null,
                    'totalBuyCount'   => ['$sum' => '$totalBuyCount'],
                    'totalSoldCount'  => ['$sum' => '$totalSoldCount'],
                    'totalBuyBtc'     => ['$sum' => '$totalBuyBtc'],
                    'totalBuyUsdt'    => ['$sum' => '$totalBuyUsdt'],
                    'totalSoldUsdt'   => ['$sum' => '$totalSoldUsdt'],
                    'totalSoldBtc'    => ['$sum' => '$totalSoldBtc'],
                    
                ]
            ],
        ];

        $resultCount         = $db->opportunity_logs_binance->aggregate($lookupCount);
        $resultResponseCount = iterator_to_array($resultCount);

        //get expected count
        $getTradeNumberCount = [
            'exchange'  	=>  'binance',
            'created_date'  =>   $this->mongo_db->converToMongodttime(date('Y-m-d')),
        ];

        $getTradeCount 		    = 	$db->expected_trade_buy_count_history->find($getTradeNumberCount);
        $expectedCountResponse  = 	iterator_to_array($getTradeCount);
       
        $upsertedArray = [
            'created_date'           =>  $this->mongo_db->converToMongodttime(date('Y-m-d 07:59')),
            'total_invest'           =>  (float)($convertedUSDAmount + $resultResponse[0]['usdt_invest_amount']),
            'totaolInvestInbtc'      =>  $convertedUSDAmount,
            'totalInvestInbtc'       =>  $resultResponse[0]['btc_invest_amount'],
            'totaolInvestInUSDT'     =>  $resultResponse[0]['usdt_invest_amount'],
            'buy_commision'          =>  (float)($resultResponse[0]['buy_commision'] + $resultResponse[0]['buy_commision_qty_USDT']),            
            'sell_commission'        =>  (float)($sell_commission_Usdt + $resultResponse[0]['sell_fee_coin_USDT']),
            'numberOfOpportunities'  =>  $resultResponse[0]['count'],
            'totalBuyTradeCount'     =>  (float)($resultResponseCount[0]['totalBuyCount']),
            'totalSoldTradeCount'    =>  (float)($resultResponseCount[0]['totalSoldCount']),
            'totalBuyBtc'            =>  (float)($resultResponseCount[0]['totalBuyBtc']),
            'totalBuyUsdt'           =>  (float)($resultResponseCount[0]['totalBuyUsdt']),
            'totalSoldUsdt'          =>  (float)($resultResponseCount[0]['totalSoldUsdt']),
            'totalSoldBtc'           =>  (float)($resultResponseCount[0]['totalSoldBtc']),
            'expected_btc_count'     =>  (float)($expectedCountResponse[0]['total_btc_count']),
            'expected_usdt_count'    =>  (float)($expectedCountResponse[0]['total_usdt_count']),
            'month'                  =>  date('Y-m-d'),
            'exchange'               =>  'binance',
            'mode'                   =>  'live',
            'sell_btc_in_$'          =>  (float)$resultResponse[0]['$sell_btc_in_$'],
            'sell_usdt'              =>  (float)$resultResponse[0]['$sell_usdt'],
            'total_sell_in_usdt'     =>  (float)$resultResponse[0]['$total_sell_in_usdt'],
        ];
        echo "<pre>"; print_r($upsertedArray);
        $upsertedWhere['month']          =  date('Y-m-d');
        $upsertedWhere['exchange']       =  'binance';
        $upsertedWhere['mode']           =  'live';

        $getRes = $db->daily_investment_chart_data->updateOne($upsertedWhere, ['$set' => $upsertedArray],  ['upsert' => true]);
        echo "<br>modified count". $getRes->getModifiedCount();
        echo "<br>upserted count". $getRes->getUpsertedCount();
        echo "<br>sucessfull updated";
    }
    // previousday fee analysis for kraken 
    public function fee_analysis_kraken(){

        $this->update_feeanalysis_by_exchange('kraken');
        return;

        $coin_arrayBtc  = ['XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC'];
        $coin_arrayUSDT = ['EOSUSDT', 'LTCUSDT','XRPUSDT','NEOUSDT', 'QTUMUSDT','BTCUSDT'];

        $startDate = $this->mongo_db->converToMongodttime(date('Y-m-d 07:59', strtotime('-1 days') ) );
        $endDate   = $this->mongo_db->converToMongodttime(date('Y-m-d 07:59'));
        
        $lookup = [
            [
                '$match' => [
                    
                    'created_date' => ['$gte' => $startDate, '$lte' => $endDate],
                    'level'        => ['$in'  => ['level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18']],
                ]
            ],
            [
                '$group' => [

                    '_id' => null,
                    'btc_invest_amount'         => ['$sum' => '$btc_invest_amount'],
                    'usdt_invest_amount'        => ['$sum' => '$usdt_invest_amount'],
                    'sell_fee_respected_coin'   => ['$sum' => '$sell_fee_respected_coin'],
                    'count'                     => ['$sum' => 1 ],
                    'buy_commision_qty'         => ['$sum' => '$buy_commision_qty_USDT'],   
                    'open_lth'                  => ['$sum' =>  '$open_lth'],
                    'sold'                      => ['$sum' =>  '$sold'],   
                    'sell_commession'           => ['$sum' => '$sell_commision_qty_USDT'],
                    'other_status'              => ['$sum' =>  '$other_status'],
                    'sell_btc_in_$'             => ['$sum' => '$sell_btc_in_$'],
                    'sell_usdt'                 => ['$sum' => '$sell_usdt'],
                    'total_sell_in_usdt'        => ['$sum' => '$total_sell_in_usdt'],
                ]
            ],
        ];

        $db = $this->mongo_db->customQuery();
        $result = $db->opportunity_logs_kraken->aggregate($lookup);   
        $resultResponse = iterator_to_array($result);

        $convertedUSDAmount =   ($resultResponse[0]['btc_invest_amount'] > 0) ?   convertCoinBalanceIntoUSDT('BTCUSDT', $resultResponse[0]['btc_invest_amount'], 'kraken') : 0;
        
        $lookupCount = [
            [
                '$match' => [
                    
                    'created_date' => ['$gte' => $startDate, '$lte' => $endDate],
                    'level'        => ['$in'  => ['level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18']],
                ]
            ],
            
            [
                '$addFields' => [

                    'open_lth'      => ['$toDouble' => '$open_lth'],
                    'sold'          => ['$toDouble' => '$sold'],
                    'other_status'  => ['$toDouble' => '$other_status'],
                    

                ]
            ],

            [
                '$project' => [

                    '_id' => 1,

                    'totalBuyCount'  =>  ['$sum' => ['$open_lth', '$sold', '$other_status']],
                    'totalSoldCount' =>  ['$sum' => '$sold'],

                     'totalBuyBtc' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$coin', $coin_arrayBtc]],
                                'then' => ['$sum' => ['$open_lth', '$sold', '$other_status']],
                                'else' => 0
                            ]
                        ]
                    ],

                    'totalBuyUsdt' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$coin' , $coin_arrayUSDT]],
                                'then' => ['$sum' => ['$open_lth', '$sold', '$other_status']],
                                'else' => 0
                            ]
                        ]
                    ],

                    'totalSoldUsdt' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$coin',  $coin_arrayUSDT]],
                                'then' => ['$sum' => '$sold'],
                                'else' => 0
                            ]
                        ]
                    ],

                    'totalSoldBtc' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$coin', $coin_arrayBtc]],
                                'then' => ['$sum' => '$sold'],
                                'else' => 0
                            ]
                        ]
                    ],

                ]
            ],

            [
                '$group' => [
                    '_id' => null,
                    'totalBuyCount'   => ['$sum' => '$totalBuyCount'],
                    'totalSoldCount'  => ['$sum' => '$totalSoldCount'],
                    'totalBuyBtc'     => ['$sum' => '$totalBuyBtc'],
                    'totalBuyUsdt'    => ['$sum' => '$totalBuyUsdt'],
                    'totalSoldUsdt'   => ['$sum' => '$totalSoldUsdt'],
                    'totalSoldBtc'    => ['$sum' => '$totalSoldBtc'],
                    
                ]
            ],
        ];
       
        $resultCount         = $db->opportunity_logs_kraken->aggregate($lookupCount);
        $resultResponseCount = iterator_to_array($resultCount);

        //get expected count
        $getTradeNumberCount = [
            'exchange'  	=>  'kraken',
            'created_date'  =>   $this->mongo_db->converToMongodttime(date('Y-m-d')),
        ];

        $getTradeCount 		    = 	$db->expected_trade_buy_count_history->find($getTradeNumberCount);
        $expectedCountResponse  = 	iterator_to_array($getTradeCount);

        $upsertedArray = [
            'created_date'              =>  $this->mongo_db->converToMongodttime(date('Y-m-d 07:59')),
            'totaolInvestInbtc'         =>  $convertedUSDAmount,
            'total_invest'              =>  (float)($convertedUSDAmount + $resultResponse[0]['usdt_invest_amount']),
            'totalInvestInbtc'          =>  $resultResponse[0]['btc_invest_amount'],  
            'totaolInvestInUSDT'        =>  $resultResponse[0]['usdt_invest_amount'],
            'buy_commision_qty'         =>  $resultResponse[0]['buy_commision_qty_USDT'],
            'sell_commission'           =>  $resultResponse[0]['sell_commission'],
            'numberOfOpportunities'     =>  $resultResponse[0]['count'],
            'totalBuyTradeCount'        =>  (float)($resultResponse[0]['open_lth'] + $resultResponse[0]['sold'] + $resultResponse[0]['other_status']),
            'totalSoldTradeCount'       =>  (float)($resultResponse[0]['sold']),
            'totalBuyTradeCount'        =>  (float)($resultResponseCount[0]['totalBuyCount']),
            'totalSoldTradeCount'       =>  (float)($resultResponseCount[0]['totalSoldCount']),
            'totalBuyBtc'               =>  (float)($resultResponseCount[0]['totalBuyBtc']),
            'totalBuyUsdt'              =>  (float)($resultResponseCount[0]['totalBuyUsdt']),
            'totalSoldUsdt'             =>  (float)($resultResponseCount[0]['totalSoldUsdt']),
            'totalSoldBtc'              =>  (float)($resultResponseCount[0]['totalSoldBtc']),
            'expected_btc_count'        =>  (float)($expectedCountResponse[0]['total_btc_count']),
            'expected_usdt_count'       =>  (float)($expectedCountResponse[0]['total_usdt_count']),
            'month'                     =>  date('Y-m-d'),
            'exchange'                  =>  'kraken',
            'mode'                      =>  'live',
            'sell_btc_in_$'             =>  (float)$resultResponse[0]['$sell_btc_in_$'],
            'sell_usdt'                 =>  (float)$resultResponse[0]['$sell_usdt'],
            'total_sell_in_usdt'        =>  (float)$resultResponse[0]['$total_sell_in_usdt'],
        ];

        echo "<pre>";print_r($upsertedArray);
        $upsertedWhere['exchange']       =  'kraken';
        $upsertedWhere['month']          =   date('Y-m-d');
        $upsertedWhere['mode']           =  'live';

        $getRes = $db->daily_investment_chart_data->updateOne($upsertedWhere, ['$set' => $upsertedArray],  ['upsert' => true]);
        echo "<br>modified count". $getRes->getModifiedCount();
        echo "<br>upserted count". $getRes->getUpsertedCount();
        echo "<br>Successfully update";
    }
    // previous day fee analysis for bam
    public function fee_analysis_bam(){
        $this->update_feeanalysis_by_exchange('bam');
        return;
        
        $coin_arrayBtc = ['XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC'];    
        $USDTcoin_array = ['EOSUSDT', 'LTCUSDT','XRPUSDT','NEOUSDT', 'QTUMUSDT'];

        $startDate = $this->mongo_db->converToMongodttime(date('Y-m-d 07:59'));
        $endDate   = $this->mongo_db->converToMongodttime(date('Y-m-d 07:59', strtotime('+1 days')) );
        
        $lookup = [
            [
                '$match' => [
                    
                    'created_date' => ['$gte' => $startDate, '$lte' => $endDate],
                    'level'        => ['$in'  => ['level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18']],
                ]
            ],
            [
                '$group' => [

                    '_id' => null,
                    'btc_invest_amount'         => ['$sum' => '$btc_invest_amount'],
                    'usdt_invest_amount'        => ['$sum' => '$usdt_invest_amount'],
                    'buy_commision_qty'         => ['$sum' => '$buy_commision_qty'],
                    'buy_commision'             => ['$sum' => '$buy_commision'],
                    'sell_fee_respected_coin'   => ['$sum' => '$sell_fee_respected_coin'],
                    'sell_commission'           => ['$sum' => '$sell_commission'],
                    'count'                     => ['$sum' => 1 ],
                    'open_lth'                  => ['$sum' =>  '$open_lth'],
                    'sold'                      => ['$sum' =>  '$sold'],   
                    'other_status'              => ['$sum' =>  '$other_status'],
                    'sell_btc_in_$'             => ['$sum' => '$sell_btc_in_$'],
                    'sell_usdt'                 => ['$sum' => '$sell_usdt'],
                    'total_sell_in_usdt'        => ['$sum' => '$total_sell_in_usdt'],
                           
                ]
            ],
        ];

        $db = $this->mongo_db->customQuery();
        $result = $db->opportunity_logs_bam->aggregate($lookup);
        $resultResponse = iterator_to_array($result);
        $convertedUSDAmount =   ($resultResponse[0]['btc_invest_amount'] > 0) ?   convertCoinBalanceIntoUSDT('BTCUSDT', $resultResponse[0]['btc_invest_amount'], 'bam') : 0;

        //new query for calculate total buy/sell trade counts
        $lookupCount = [
            [
                '$match' => [
                    
                    'created_date' => ['$gte' => $startDate, '$lte' => $endDate],
                    'level'        => ['$in'  => ['level_5', 'level_6', 'level_8', 'level_10', 'level_11', 'level_12', 'level_13', 'level_17', 'level_18']],
                ]
            ],
            
            [
                '$addFields' => [

                    'open_lth'      => ['$toDouble' => '$open_lth'],
                    'sold'          => ['$toDouble' => '$sold'],
                    'other_status'  => ['$toDouble' => '$other_status'],
                    

                ]
            ],

            [
                '$project' => [

                    '_id' => 1,

                    'totalBuyCount'  =>  ['$sum' => ['$open_lth', '$sold', '$other_status']],
                    'totalSoldCount' =>  ['$sum' => '$sold'],

                     'totalBuyBtc' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$coin', $coin_arrayBtc]],
                                'then' => ['$sum' => ['$open_lth', '$sold', '$other_status']],
                                'else' => 0
                            ]
                        ]
                    ],

                    'totalBuyUsdt' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$coin' , $USDTcoin_array]],
                                'then' => ['$sum' => ['$open_lth', '$sold', '$other_status']],
                                'else' => 0
                            ]
                        ]
                    ],

                    'totalSoldUsdt' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$coin',  $coin_arrayUSDT]],
                                'then' => ['$sum' => '$sold'],
                                'else' => 0
                            ]
                        ]
                    ],

                    'totalSoldBtc' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$coin', $coin_arrayBtc]],
                                'then' => ['$sum' => '$sold'],
                                'else' => 0
                            ]
                        ]
                    ],

                ]
            ],

            [
                '$group' => [
                    '_id' => null,
                    'totalBuyCount'   => ['$sum' => '$totalBuyCount'],
                    'totalSoldCount'  => ['$sum' => '$totalSoldCount'],
                    'totalBuyBtc'     => ['$sum' => '$totalBuyBtc'],
                    'totalBuyUsdt'    => ['$sum' => '$totalBuyUsdt'],
                    'totalSoldUsdt'   => ['$sum' => '$totalSoldUsdt'],
                    'totalSoldBtc'    => ['$sum' => '$totalSoldBtc'],
                    
                ]
            ],
        ];

        $resultCount         = $db->opportunity_logs_bam->aggregate($lookupCount);
        $resultResponseCount = iterator_to_array($resultCount);


        //get expected count
        $getTradeNumberCount = [
            'exchange'  	=>  'bam',
            'created_date'  =>   $this->mongo_db->converToMongodttime(date('Y-m-d')),
        ];

        $getTradeCount 		    = 	$db->expected_trade_buy_count_history->find($getTradeNumberCount);
        $expectedCountResponse  = 	iterator_to_array($getTradeCount);


        $upsertedArray = [
            'created_date'            =>  $this->mongo_db->converToMongodttime(date('Y-m-d 07:59')),
            'totaolInvestInbtc'       =>  $convertedUSDAmount,
            'total_invest'            =>  (float)($convertedUSDAmount + $resultResponse[0]['usdt_invest_amount']),
            'totaolInvestInUSDT'      =>  $resultResponse[0]['usdt_invest_amount'],
            'buy_commision_qty'       =>  $resultResponse[0]['buy_commision_qty'],
            'buy_commision'           =>  $resultResponse[0]['buy_commision'],
            'sell_fee_respected_coin' =>  $resultResponse[0]['sell_fee_respected_coin'],
            'sell_commission'         =>  $resultResponse[0]['sell_commission'],
            'numberOfOpportunities'   =>  $resultResponse[0]['count'],
            'totalBuyTradeCount'      =>  (float)($resultResponse[0]['open_lth'] + $resultResponse[0]['sold'] + $resultResponse[0]['other_status']),
            'totalSoldTradeCount'     =>  (float)($resultResponse[0]['sold']),
            'totalBuyTradeCount'      =>  (float)($resultResponseCount[0]['totalBuyCount']),
            'totalSoldTradeCount'     =>  (float)($resultResponseCount[0]['totalSoldCount']),
            'totalBuyBtc'             =>  (float)($resultResponseCount[0]['totalBuyBtc']),
            'totalBuyUsdt'            =>  (float)($resultResponseCount[0]['totalBuyUsdt']),
            'totalSoldUsdt'           =>  (float)($resultResponseCount[0]['totalSoldUsdt']),
            'totalSoldBtc'            =>  (float)($resultResponseCount[0]['totalSoldBtc']),
            'expected_btc_count'      =>  (float)($expectedCountResponse[0]['total_btc_count']),
            'expected_usdt_count'     =>  (float)($expectedCountResponse[0]['total_usdt_count']),
            'month'                   =>  date('Y-m-d'),
            'exchange'                =>  'bam',
            'mode'                    =>  'live',
            'sell_btc_in_$'           =>  (float)$resultResponse[0]['$sell_btc_in_$'],
            'sell_usdt'               =>  (float)$resultResponse[0]['$sell_usdt'],
            'total_sell_in_usdt'      =>  (float)$resultResponse[0]['$total_sell_in_usdt'],
        ];
        $upsertedWhere['exchange']       =  'bam';
        $upsertedWhere['month']          =  date('Y-m-d');
        $upsertedWhere['mode']           =  'live';

        $getRes = $db->daily_investment_chart_data->updateOne($upsertedWhere, ['$set' => $upsertedArray],  ['upsert' => true]);
        echo "<br>modified count". $getRes->getModifiedCount();
        echo "<br>upserted count". $getRes->getUpsertedCount();
        echo "<br>Successfully update";
    }
    // count active users and inactive users and test users of all exchanges and insert in user_analysis_chart collection
    public function count_users_records_charts($start_date = ''){

         if($start_date!=''){
            //var_dump($start_date);
            $st_date=explode("T",$start_date);
            $start_date_new = $st_date[0].' '.$st_date[1];
            $newDate = date("Y-m-d h:i", strtotime($start_date_new));
            $endDatee = date("Y-m-d", strtotime($newDate));
            $startDate = $this->mongo_db->converToMongodttime($newDate);
            $startDatelogin = $this->mongo_db->converToMongodttime(date('Y-m-d h:i', strtotime('-1 day', strtotime($newDate))));
            //print_r($newDate);
         }else{
            $startDate = $this->mongo_db->converToMongodttime(date('Y-m-d 08:00'));
            $endDatee = date("Y-m-d");
            $startDatelogin = $this->mongo_db->converToMongodttime(date('Y-m-d 08:00', strtotime('-1 days') ) );
        }
        
        
        
        $collection_name_Binance    = 'user_investment_binance';
        $collection_name_kraken     = 'user_investment_kraken';
        $collection_name_Bam        = 'user_investment_bam';
        $db  = $this->mongo_db->customQuery();
        
        $lookup = [
            [
                '$group' => [
                    '_id' => null,
                    'Total' => ['$sum' => 1],
                    'active' => [
                        '$sum' => [
                            '$cond' => [
                                'if' =>['$and'=>[
                                    ['$eq' => ['$tradingStatus', 'on']],['$gt' => ['$total_balance', 0]],['$eq' => ['$is_api_key_valid','yes']]]],
                                'then' => 1,
                                'else' => 0
                            ]
                        ]
                    ],
                    
                    'unactive' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$eq' => ['$is_api_key_valid', 'no']],
                                'then' => 1,
                                'else' => 0
                            ]
                        ]
                    ],
                    
                    'test' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$in' => ['$exchange_enabled', ['yes', 'no']]],
                                'then' => 0,
                                'else' => 1
                            ]
                        ]
                    ],
                ]
            ],
        ];
        $get_binance    =    $db->$collection_name_Binance->aggregate($lookup);
        $get_binanceRes =    iterator_to_array($get_binance); /// Binance result

        $get_kraken     =    $db->$collection_name_kraken->aggregate($lookup);
        $get_krakenRes  =    iterator_to_array($get_kraken); ///// Kraken result
        
        $get_bam        =    $db->$collection_name_Bam->aggregate($lookup);
        $get_bamRes     =    iterator_to_array($get_bam);////// Bam result

        // echo '<pre>';print_r($get_binanceRes);
        // echo '*** KRAKEN ***';
        // echo '<pre>';print_r($get_krakenRes);exit;

 
        $loockupForInvestmentRepost = [
            [
                '$project' => [
                    '_id'  => 1,

                    'loginCount' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$and'=>[['$gte' => ['$last_login_time', $startDatelogin]],['$lt' => ['$last_login_time', $startDate]]]] ,
                                'then' => 1,
                                'else' => 0
                            ]
                        ]
                    ],
                    'daily_trades_users_count' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$or'=>[['$gt' => ['$previousDailyUSDTBuyTradeCount',0]],['$gt' => ['$previousDailyBtcBuyTradeCount',0]]]] ,
                                'then' => 1,
                                'else' => 0
                            ]
                        ]
                    ],

                    'blockUsers' => [
                        '$sum' =>[
                            '$cond' => [
                                'if' => ['$eq' => ['$account_block', 'yes']],
                                'then' => 1,
                                'else' => 0
                            ]
                        ]
                    ],

                    'balanceAre500_Greater' => [
                        '$sum' => [
                            '$cond' => [
                                
                                'if' => ['$gte' => ['$actual_deposit', 500]],
                                'then' => 1,
                                'else' => 0
                            ]
                        ]
                    ],


                    'balanceAre1000_Greater' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$gte' => ['$actual_deposit', 1000]],
                                'then' => 1,
                                'else' => 0
                            ]
                        ]
                    ],

                    'balanceAre2500_Greater' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$gte' => ['$actual_deposit', 2500]],
                                'then' => 1,
                                'else' => 0
                            ]
                        ]
                    ],


                    'lthBalance50_grater_per' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$gte' => ['$lth_cost_avg_balance_percentage', 50]],
                                'then' => 1,
                                'else' => 0
                            ]
                        ]
                    ],


                    'lthBalance30_grater_per' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$gte' => ['$lth_cost_avg_balance_percentage', 30]],
                                'then' => 1,
                                'else' => 0
                            ]
                        ]
                    ],


                    'lthBalance70_grater_per' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$gte' => ['$lth_cost_avg_balance_percentage', 70]],
                                'then' => 1,
                                'else' => 0
                            ]
                        ]
                    ],

                    'lthBalance100_grater_per' => [
                        '$sum' => [
                            '$cond' => [
                                'if' => ['$gte' => ['$lth_cost_avg_balance_percentage', 100]],
                                'then' => 1,
                                'else' => 0
                            ]
                        ]
                    ],


                ],
            ],


            [
                '$group' => [
                    '_id' => null,

                    'lthBalance50_grater_per' => ['$sum' => '$lthBalance50_grater_per'],
                    'lthBalance30_grater_per' => ['$sum' => '$lthBalance30_grater_per'],
                    'lthBalance70_grater_per' => ['$sum' => '$lthBalance70_grater_per'],
                    'lthBalance100_grater_per'=> ['$sum' => '$lthBalance100_grater_per'],
                    'daily_trades_users_count'=> ['$sum' => '$daily_trades_users_count'],
                    'blockUsers'              => ['$sum' => '$blockUsers'],
                    'balanceAre500_Greater'   => ['$sum' => '$balanceAre500_Greater'],
                    'balanceAre1000_Greater'  => ['$sum' => '$balanceAre1000_Greater'],
                    'balanceAre2500_Greater'  => ['$sum' => '$balanceAre2500_Greater'],
                    'loginCount'              => ['$sum' => '$loginCount']
                ]
            ]
        ];

        $userDetails 		    = 	$db->$collection_name_Binance->aggregate($loockupForInvestmentRepost);
        $userDSetailsBinance    = 	iterator_to_array($userDetails);

        $userDetails 		    = 	$db->$collection_name_kraken->aggregate($loockupForInvestmentRepost);
        $userDSetailsKraken     = 	iterator_to_array($userDetails);

        $userDetails 		    = 	$db->$collection_name_Bam->aggregate($loockupForInvestmentRepost);
        $userDSetailsBam        = 	iterator_to_array($userDetails);
        //////////// Total users who buy trades last 24 hours//////////
        $lookup_trade_users_count=[
        [
        '$match'=>
        [
          'created_date'=>['$gte'=>$startDatelogin,'$lte'=>$startDate],
          'status'=>['$in'=>['LTH','FILLED','OPEN']],
          'trigger_type'=>"barrier_percentile_trigger",
          'application_mode'=>"live"
        ]
            ], 
        [
            '$group'=>[
              '_id'=>'$admin_id',
              'trades_count'=> [
                '$sum'=>1
            ]
        ]
        ], 
        ['$group'=> [
              '_id'=> null,
              'users_ids'=> ['$push' => '$_id' ],
              'total_users_count'=>['$sum'=>1]
            ]]];

    $userDetails_count            =   $db->buy_orders->aggregate($lookup_trade_users_count);
    $userDSetailsBinance_count    =   iterator_to_array($userDetails_count);

    $userDetails_count            =   $db->buy_orders_kraken->aggregate($lookup_trade_users_count);
    $userDSetailsKraken_count     =   iterator_to_array($userDetails_count);
 
    
    if($userDSetailsBinance_count){ // if no order sold then empty array to pass to the sold array to exclude
        $users_array=$userDSetailsBinance_count[0]['users_ids'];
    }else{
        $users_array=array();
    }
    if($userDSetailsKraken_count){
        $krakenusers_array=$userDSetailsKraken_count[0]['users_ids'];
    }else{
        $krakenusers_array=array();
    }
    

     //////////// Total users who sold trades last 24 hours(Excluding the buy users from sold unique)//////////
    $lookup_trade_users_count=[
        [
        '$match'=>
        [
            'admin_id'=>['$nin'=>$users_array],
            'buy_date'=>['$gte'=>$startDatelogin,'$lte'=>$startDate],
            'status'=>['$in'=>['FILLED']],
            'application_mode'=>"live"
        ]
            ], 
        [
            '$group'=>[
            '_id'=>'$admin_id',
            'trades_count'=> [
            '$sum'=>1
            ]
        ]
        ], 
        ['$group'=> [
              '_id'=> null,
              'users_ids'=> ['$push' => '$_id' ],
              'total_users_count'=>['$sum'=>1]
            ]]];

    $userDetails_count            =   $db->sold_buy_orders->aggregate($lookup_trade_users_count);
    $userDSetailsBinance_count_sold    =   iterator_to_array($userDetails_count);

    $lookup_trade_users_count=[
        [
        '$match'=>
        [
            'admin_id'=>['$nin'=>$krakenusers_array],
            'buy_date'=>['$gte'=>$startDatelogin,'$lte'=>$startDate],
            'status'=>['$in'=>['FILLED']],
            'application_mode'=>"live"
        ]
            ], 
        [
            '$group'=>[
            '_id'=>'$admin_id',
            'trades_count'=> [
            '$sum'=>1
            ]
        ]
        ], 
        ['$group'=> [
              '_id'=> null,
              'users_ids'=> ['$push' => '$_id' ],
              'total_users_count'=>['$sum'=>1]
            ]]];

    $userDetails_count            =   $db->sold_buy_orders_kraken->aggregate($lookup_trade_users_count);
    $userDSetailsKraken_count_sold     =   iterator_to_array($userDetails_count);

  
    $binance_total_trades_users_count= $userDSetailsBinance_count[0]['total_users_count']+$userDSetailsBinance_count_sold[0]['total_users_count'];
    $kraken_total_trades_users_count= $userDSetailsKraken_count[0]['total_users_count']+$userDSetailsKraken_count_sold[0]['total_users_count'];
    // query for getting the users of previous 7 days trades 

        $lookup_trade_users_count_seven_days=[
            [
                '$match'=>[
                    'exchange'=>'binance'
                ]
            ],
            [
                '$group'=> [
                  '_id'=>null,
                  'seven_days_count_for_users_trades'=> [
                    '$sum'=>'$total_trades_users_count_on_ip'
                  ]
                ]
            ]
        ];

    $seven_days_userDetails_count            =   $db->ip_stats->aggregate($lookup_trade_users_count_seven_days);
    $userDSetailsbinance_count_seven_days     =   iterator_to_array($seven_days_userDetails_count);
    // for kraken count 
        $lookup_trade_users_count_seven_days=[
            [
                '$match'=>[
                    'exchange'=>'kraken'
                ]
            ],
            [
                '$group'=> [
                  '_id'=>null,
                  'seven_days_count_for_users_trades'=> [
                    '$sum'=>'$total_trades_users_count_on_ip'
                  ]
                ]
            ]
        ];

    $seven_days_userDetails_count            =   $db->ip_stats->aggregate($lookup_trade_users_count_seven_days);
    $userDSetailskraken_count_seven_days     =   iterator_to_array($seven_days_userDetails_count);
    $binance_block = $this->get_binance_blocked_users_exclude_kraken();
        $upsertedArray_binance = [
            'created_date'              =>  $startDate,
            'Total_users'               =>  $get_binanceRes[0]['Total'],
            'Active_users'              =>  $get_binanceRes[0]['active'],
            'Unactive_users'            =>  $get_binanceRes[0]['unactive'],
            'Test_users'                =>  $get_binanceRes[0]['test'],
            'exchange'                  =>  'binance',
            'month'                     =>  $endDatee,
            'lthBalance50_grater_per'   =>  $userDSetailsBinance[0]['lthBalance50_grater_per'],
            //'blockUsers'                =>  $userDSetailsBinance[0]['blockUsers'],
            'blockUsers'                =>  $binance_block,
            'lthBalance30_grater_per'   =>  $userDSetailsBinance[0]['lthBalance30_grater_per'],
            'lthBalance70_grater_per'   =>  $userDSetailsBinance[0]['lthBalance70_grater_per'],
            'lthBalance100_grater_per'  =>  $userDSetailsBinance[0]['lthBalance100_grater_per'],
            'balanceAre500_Greater'     =>  $userDSetailsBinance[0]['balanceAre500_Greater'],
            'balanceAre1000_Greater'    =>  $userDSetailsBinance[0]['balanceAre1000_Greater'],
            'balanceAre2500_Greater'    =>  $userDSetailsBinance[0]['balanceAre2500_Greater'],
            'loginCount'                =>  $userDSetailsBinance[0]['loginCount'],
            'daily_trades_users_count'  =>  $binance_total_trades_users_count,
            'seven_days_trades_users_count'  =>  $userDSetailsbinance_count_seven_days[0]['seven_days_count_for_users_trades']
        ]; // Binance array
        $upsertedArray_kraken = [
            'created_date'              =>  $startDate,
            'Total_users'               =>  $get_krakenRes[0]['Total'],
            'Active_users'              =>  $get_krakenRes[0]['active'],
            'Unactive_users'            =>  $get_krakenRes[0]['unactive'],
            'Test_users'                =>  $get_krakenRes[0]['test'],
            'exchange'                  =>  'kraken',
            'month'                     =>  $endDatee,
            'lthBalance50_grater_per'   =>  $userDSetailsKraken[0]['lthBalance50_grater_per'],
            'lthBalance30_grater_per'   =>  $userDSetailsBinance[0]['lthBalance30_grater_per'],
            'lthBalance70_grater_per'   =>  $userDSetailsBinance[0]['lthBalance70_grater_per'],
            'lthBalance100_grater_per'  =>  $userDSetailsBinance[0]['lthBalance100_grater_per'],
            'blockUsers'                =>  $userDSetailsKraken[0]['blockUsers'],
            'balanceAre500_Greater'     =>  $userDSetailsKraken[0]['balanceAre500_Greater'],
            'balanceAre1000_Greater'    =>  $userDSetailsKraken[0]['balanceAre1000_Greater'],
            'balanceAre2500_Greater'    =>  $userDSetailsKraken[0]['balanceAre2500_Greater'],
            'loginCount'                =>  $userDSetailsKraken[0]['loginCount'],
            'daily_trades_users_count'  =>  $kraken_total_trades_users_count,
            'seven_days_trades_users_count'  =>  $userDSetailskraken_count_seven_days[0]['seven_days_count_for_users_trades']
        ]; // Kraken array
        $upsertedArray_bam = [
            'created_date'              =>  $startDate,
            'Total_users'               =>  $get_bamRes[0]['Total'],
            'Active_users'              =>  $get_bamRes[0]['active'],
            'Unactive_users'            =>  $get_bamRes[0]['unactive'],
            'Test_users'                =>  $get_bamRes[0]['test'],
            'exchange'                  =>  'bam',
            'month'                     =>  $endDatee,
            'lthBalance50_grater_per'   =>  $userDSetailsBam[0]['lthBalance50_grater_per'],
            'lthBalance30_grater_per'   =>  $userDSetailsBinance[0]['lthBalance30_grater_per'],
            'lthBalance70_grater_per'   =>  $userDSetailsBinance[0]['lthBalance70_grater_per'],
            'lthBalance100_grater_per'  =>  $userDSetailsBinance[0]['lthBalance100_grater_per'],
            'blockUsers'                =>  $userDSetailsBam[0]['blockUsers'],
            'balanceAre500_Greater'     =>  $userDSetailsBam[0]['balanceAre500_Greater'],
            'balanceAre1000_Greater'    =>  $userDSetailsBam[0]['balanceAre1000_Greater'],
            'balanceAre2500_Greater'    =>  $userDSetailsBam[0]['balanceAre2500_Greater'],
            'loginCount'                =>  $userDSetailsBam[0]['loginCount'],
            'daily_trades_users_count'  =>  $userDSetailsBam[0]['daily_trades_users_count'],
        ]; // Bam array

        $upsertedWhere['exchange']          =  'binance';
        $upsertedWhere['month']             =  $endDatee;

        $upsertedWhereKraken['exchange']    =  'kraken';
        $upsertedWhereKraken['month']       = $endDatee;

        $upsertedWhereBam['exchange']       =  'bam';
        $upsertedWhereBam['month']          =  $endDatee;



        $db->user_analysis_chart_data->updateOne($upsertedWhere,       ['$set' => $upsertedArray_binance],  ['upsert' => true]);
        $upd = $db->user_analysis_chart_data->updateOne($upsertedWhereKraken, ['$set' => $upsertedArray_kraken],   ['upsert' => true]);

        echo "<br>update: ".$upd->getModifiedCount();
        echo "<br>upserted: ".$upd->getUpsertedCount();
        $db->user_analysis_chart_data->updateOne($upsertedWhereBam,    ['$set' => $upsertedArray_bam],      ['upsert' => true]);
        
        echo "<br>Successfully update";
    }
    
    //////////////////////////////
    //////////Cron for Chart ////
    //////////////////////////////

    // /fee_analysis
    public function resetFilter_monthly(){
                  $this->session->unset_userdata('filter_monthly_report');
                  $this->fee_analysis();
    }
    public function fee_analysis(){

        $this->mod_login->verify_is_admin_login();
          if($this->input->post()){

            $this->session->unset_userdata('filter_monthly_report');
            ////////// This is for Date filter on monthly report /////////////
            $filter_data_monthly['filter_monthly_report'] = $this->input->post();
            $this->session->set_userdata($filter_data_monthly);

            $startDate_added = $this->input->post('start_date');
            $endDate_added = $this->input->post('end_date');
            if(!empty($startDate_added) && !empty($endDate_added)){
                    $startMongoTime = $this->mongo_db->converToMongodttime($startDate_added);
                    $endMongoTime = $this->mongo_db->converToMongodttime($endDate_added);
            }elseif(!empty($startDate_added) && empty($endDate_added)){
                    $startMongoTime = $this->mongo_db->converToMongodttime($startDate_added);
                    $endDate = date('Y-m-d');
                    $endMongoTime = $this->mongo_db->converToMongodttime($endDate);
            }elseif(empty($startDate_added) && !empty($endDate_added)){
                $startMongoTime = $this->mongo_db->converToMongodttime(date('Y-m-d', strtotime('-30 days', strtotime($endDate_added))));
                 $endMongoTime = $this->mongo_db->converToMongodttime($endDate_added);
                }   

          }else{
                $startDate = date('Y-m-d' , strtotime('-30 days'));
                $startMongoTime = $this->mongo_db->converToMongodttime($startDate);
                $endDate = date('Y-m-d');
                $endMongoTime = $this->mongo_db->converToMongodttime($endDate);
            }
     
            ////////////// End date filters code //////////
        $db = $this->mongo_db->customQuery();
        $search = [
            [

                '$match' => [
                    'created_date'  =>  ['$gte' => $startMongoTime, '$lte' => $endMongoTime],
                    'exchange'      => 'binance'
                ]
            ],
        ];
        $cursor_binance = $db->daily_investment_chart_data->aggregate($search);
        $binance_result = iterator_to_array($cursor_binance);  

        $data = array();
        $binance_result[0]['buy_commision']             = 0;
        $binance_result[0]['numberOfOpportunities']     = 0;
        $binance_result[0]['sell_fee_respected_coin']   = 0;
        $binance_result[0]['totalInvestInbtc']          = 0;
        $binance_result[0]['totaolInvestInUSDT']        = 0;
        $binance_result[0]['totaolInvestInbtc']         = 0;
        $binance_result[0]['sell_commission']           = 0;
        $binance_result[0]['totalBuyTradeCount']        = 0;
        $binance_result[0]['totalBuyUsdt']              = 0;
        $binance_result[0]['totalSoldBtc']              = 0;
        $binance_result[0]['totalSoldTradeCount']       = 0;
        $binance_result[0]['totalSoldUsdt']             = 0;
        $binance_result[0]['expected_btc_count']        = 0;
        $binance_result[0]['expected_usdt_count']       = 0;
        $binance_result[0]['total_invest']              = 0;
        $binance_result[0]['sell_btc_in_$']             = 0;     
        $binance_result[0]['sell_usdt']                 = 0;
        $binance_result[0]['total_sell_in_usdt']        = 0;
        $binance_result[0]['total_btc_opportunities']   = 0;
        $binance_result[0]['total_usdt_opportunities']  = 0;
        $binance_result[0]['total_trades_errors_count']  = 0;
        $binance_result[0]['total_trades_cancelled_count']  = 0;

        $data['binance_result'] = $binance_result;
        $searchKraken = [
            [
                
                '$match' => [
                    'created_date'  =>  ['$gte' => $startMongoTime, '$lte' => $endMongoTime],
                    'exchange'      => 'kraken'
                ]
            ],
        ];      
        $cursor_kraken = $db->daily_investment_chart_data->aggregate($searchKraken);
        $kraken_result=iterator_to_array($cursor_kraken);

        $kraken_result[0]['buy_commision_qty']         = 0;
        $kraken_result[0]['numberOfOpportunities']     = 0;
        $kraken_result[0]['sell_fee_respected_coin']   = 0;
        $kraken_result[0]['totalInvestInbtc']          = 0;
        $kraken_result[0]['totaolInvestInUSDT']        = 0;
        $kraken_result[0]['totaolInvestInbtc']         = 0;
        $kraken_result[0]['totalBuyTradeCount']        = 0;
        $kraken_result[0]['totalBuyUsdt']              = 0;
        $kraken_result[0]['totalSoldBtc']              = 0;
        $kraken_result[0]['totalSoldTradeCount']       = 0;
        $kraken_result[0]['totalSoldUsdt']             = 0;
        $kraken_result[0]['expected_btc_count']        = 0;
        $kraken_result[0]['expected_usdt_count']       = 0;
        $kraken_result[0]['total_invest']              = 0;
        $kraken_result[0]['sell_commission']           = 0;
        $kraken_result[0]['sell_btc_in_$']             = 0;     
        $kraken_result[0]['sell_usdt']                 = 0;
        $kraken_result[0]['total_sell_in_usdt']        = 0;
        $kraken_result[0]['total_btc_opportunities']   = 0;
        $kraken_result[0]['total_usdt_opportunities']  = 0;
        $kraken_result[0]['total_trades_errors_count']  = 0;
        $kraken_result[0]['total_trades_cancelled_count']  = 0;

        // 'totalBuyBtc'               =>  (float)($resultResponseCount[0]['totalBuyBtc']),

        $data['kraken_result'] = $kraken_result;
                
        $searchBam = [
            [
                '$match' => [
                    'created_date'  =>  ['$gte' => $startMongoTime, '$lte' => $endMongoTime],
                    'exchange'      => 'bam'
                ]
            ],
        ];
        $cursor_bam = $db->daily_investment_chart_data->aggregate($searchBam);
        $bam_result=iterator_to_array($cursor_bam);

        $bam_result[0]['buy_commision']             = 0;
        $bam_result[0]['buy_commision_qty']         = 0;
        $bam_result[0]['numberOfOpportunities']     = 0;
        $bam_result[0]['sell_fee_respected_coin']   = 0;
        $bam_result[0]['totalInvestInbtc']          = 0;
        $bam_result[0]['totaolInvestInUSDT']        = 0;
        $bam_result[0]['totaolInvestInbtc']         = 0;
        $bam_result[0]['sell_commission']           = 0;
        $bam_result[0]['totalBuyTradeCount']        = 0;
        $bam_result[0]['totalBuyUsdt']              = 0;
        $bam_result[0]['totalSoldBtc']              = 0;
        $bam_result[0]['totalSoldTradeCount']       = 0;
        $bam_result[0]['totalSoldUsdt']             = 0;
        $bam_result[0]['expected_btc_count']        = 0;
        $bam_result[0]['expected_usdt_count']       = 0;
        $bam_result[0]['sell_btc_in_$']             = 0;     
        $bam_result[0]['sell_usdt']                 = 0;
        $bam_result[0]['total_sell_in_usdt']        = 0;

        $data['bam_result'] = $bam_result;
        $binance_search = [
            [

                '$match' => [
                    'created_date'  =>  ['$gte' => $startMongoTime, '$lte' => $endMongoTime],
                    'exchange'      => 'binance'
                ]
            ],
        ];
        $cursor_user_binance = $db->user_analysis_chart_data->aggregate($binance_search);
        $binance_users_results = iterator_to_array($cursor_user_binance);
        $kraken_search = [
            [

                '$match' => [
                    'created_date'  =>  ['$gte' => $startMongoTime, '$lte' => $endMongoTime],
                    'exchange'      => 'kraken'
                ]
            ],
        ];
        $cursor_user_kraken = $db->user_analysis_chart_data->aggregate($kraken_search);
        $kraken_users_results = iterator_to_array($cursor_user_kraken);

        // echo "<pre>";print_r($kraken_users_results);
        $bam_search = [
            [

                '$match' => [
                    'created_date'  =>  ['$gte' => $startMongoTime, '$lte' => $endMongoTime],
                    'exchange'      => 'bam'
                ]
            ],
        ];
        $cursor_user_bam = $db->user_analysis_chart_data->aggregate($bam_search);
        $bam_users_results = iterator_to_array($cursor_user_bam);

        $binance_users_results[0]['Active_users']              = 0;
        $binance_users_results[0]['Test_users']                = 0;
        $binance_users_results[0]['Total_users']               = 0;
        $binance_users_results[0]['Unactive_users']            = 0;
        $binance_users_results[0]['lthBalance50_grater_per']   = 0;
        $binance_users_results[0]['blockUsers']                = 0;
        $binance_users_results[0]['balanceAre500_Greater']     = 0;
        $binance_users_results[0]['balanceAre1000_Greater']    = 0;
        $binance_users_results[0]['balanceAre2500_Greater']    = 0;
        $binance_users_results[0]['loginCount']                = 0;
        $binance_users_results[0]['daily_trades_users_count']  = 0;
        $binance_users_results[0]['lthBa1lance30_grater_per'] = 0;
        $binance_users_results[0]['lthBalance70_grater_per']   = 0;
        $binance_users_results[0]['lthBalance100_grater_per']  = 0;

        $kraken_users_results[0]['Active_users']              = 0;
        $kraken_users_results[0]['Test_users']                = 0;
        $kraken_users_results[0]['Total_users']               = 0;
        $kraken_users_results[0]['Unactive_users']            = 0;
        $kraken_users_results[0]['lthBalance50_grater_per']   = 0;
        $kraken_users_results[0]['blockUsers']                = 0;
        $kraken_users_results[0]['balanceAre500_Greater']     = 0;
        $kraken_users_results[0]['balanceAre1000_Greater']    = 0;
        $kraken_users_results[0]['balanceAre2500_Greater']    = 0;
        $kraken_users_results[0]['loginCount']                = 0;
        $kraken_users_results[0]['daily_trades_users_count']  = 0;
        $$kraken_users_results[0]['lthBalance30_grater_per']  = 0;
        $kraken_users_results[0]['lthBalance70_grater_per']   = 0;
        $kraken_users_results[0]['lthBalance100_grater_per']  = 0;

        $bam_users_results[0]['Active_users']              = 0;
        $bam_users_results[0]['Test_users']                = 0;
        $bam_users_results[0]['Total_users']               = 0;
        $bam_users_results[0]['Unactive_users']            = 0;
        $bam_users_results[0]['lthBalance50_grater_per']   = 0;
        $bam_users_results[0]['blockUsers']                = 0;
        $bam_users_results[0]['balanceAre500_Greater']     = 0;
        $bam_users_results[0]['balanceAre1000_Greater']    = 0;
        $bam_users_results[0]['balanceAre2500_Greater']    = 0;
        $bam_users_results[0]['loginCount']                = 0;
        $bam_users_results[0]['lthBalance30_grater_per']  = 0;
        $bam_users_results[0]['lthBalance70_grater_per']   = 0;
        $bam_users_results[0]['lthBalance100_grater_per']  = 0;

        $data['binance_users_results'] = $binance_users_results;
        $data['kraken_users_results']  = $kraken_users_results;
        $data['bam_users_results']     = $bam_users_results;
    
        $this->stencil->paint('admin/monthly_trading_volume/fee_investegation', $data); 
    }//fee_analysis

    //get_count_users_records_chart 
    public function get_count_users_records_chart(){
        
        $collection_name_Binance    = 'user_investment_binance';
        $collection_name_kraken     = 'user_investment_kraken';
        $collection_name_Bam        = 'user_investment_bam';



        $db= $this->mongo_db->customQuery();
        $lookup = [
            [
                '$group' => [
                    '_id' => null,
                    'Total' => ['$sum' => 1],
                    
                    'active' => [
                        '$sum' => [
                        '$cond' => [
                            'if' => ['$eq' => ['$exchange_enabled', 'yes']],
                            'then' => 1,
                            'else' => 0
                            ]
                        ]
                    ],
                    
                    
                    'unactive' => [
                        '$sum' => [
                        '$cond' => [
                            'if' => ['$eq' => ['$exchange_enabled', 'no']],
                            'then' => 1,
                            'else' => 0
                            ]
                        ]
                    ],
                    
                    'test' => [
                        '$sum' => [
                            '$cond' => [
                            'if' => ['$in' => ['$exchange_enabled', ['yes', 'no']]],
                            'then' => 0,
                            'else' => 1
                            ]
                        ]
                    ],
                ]
            ],
        ];
        $get_binance    =    $db->$collection_name_Binance->aggregate($lookup);
        $get_binanceRes =    iterator_to_array($get_binance);

        $get_kraken     =    $db->$collection_name_kraken->aggregate($lookup);
        $get_krakenRes  =     iterator_to_array($get_kraken);

        $get_bam        =    $db->$collection_name_Bam->aggregate($lookup);
        $get_bamRes     =    iterator_to_array($get_bam);

        echo "<pre>";
        print_r($get_binanceRes).'next';
        print_r($get_krakenRes).'next';
        print_r($get_bamRes).'next';
    } //get_count_users_records_chart
    // code by sheraz on 8 nov 2021 
    // cronjob for backup of all orders in sold and buy orders collection 
    public function orders_backup_by_exchange($exchange = ''){ 
        $dateTimeFrom = $this->mongo_db->converToMongodttime(date('Y-m-d 08:00', strtotime('-7 days') ));
        $endDate = date('Y-m-d');
        $dateTimeTo = $this->mongo_db->converToMongodttime($endDate);
        if($exchange == '' || $exchange == "binance"){
            $buy_collection_name = "buy_orders";
            $sold_collection_name = "sold_buy_orders";   
            $orders_collection_name = "orders";   
            $collection_name_backup_buy = "backup_buy_orders";   
            $collection_name_backup_sold = "backup_sold_buy_orders";   
            $backup_order_collection_name = "backup_orders_new";   
        }else{
            $buy_collection_name = "buy_orders_kraken"   ;
            $sold_collection_name = "sold_buy_orders_kraken";
            $orders_collection_name = "orders_kraken";    
            $collection_name_backup_buy = "backup_buy_orders_kraken"; 
            $collection_name_backup_sold = "backup_sold_buy_orders_kraken";
            $backup_order_collection_name = "backup_orders_kraken_new"; 
        }
        $pipeline = [
            [
                '$match'=>
                    ['created_date'  =>  ['$gte' => $dateTimeFrom, '$lte' => $dateTimeTo]]
            ]
        ];
        $db= $this->mongo_db->customQuery();

        // ************************************************************************************************************************
        // ****************************************** Buy Orders backup monthly ***************************************************
        // ************************************************************************************************************************
        // ************************************************************************************************************************


        $get_buy_orders       =    $db->$buy_collection_name->aggregate($pipeline);// buy collection data copying in backup collection
        $get_buy_orders_array =    iterator_to_array($get_buy_orders);
        $ids_to_update = array();
        foreach($get_buy_orders_array as $value){
            $where_cond = [
                '_id'=>['$in'=>[$value['_id']]]
            ];
            echo '<pre>'; print_r($ids_to_update);
            $count = $db->$collection_name_backup_buy->updateOne($where_cond,['$set'=>$value],['upsert'=>true]);
        }// end buy orders foreach
        $update_main_collection_array = array(
            'collection_name'=>$collection_name_backup_buy,
            'last_updated_date'=>(string)date('Y-m-d h:i:s',strtotime('+5 hours')));
        $where_condition = ['collection_name'=>$collection_name_backup_buy];
        $db->backups_collection_setting->updateOne($where_condition,['$set'=>$update_main_collection_array],['upsert'=>true]); //collection setting main update

        // ************************************************************************************************************************
        // ****************************************** Sold orders collection backup weekly ****************************************
        // ************************************************************************************************************************
        // ************************************************************************************************************************


        $get_sold_buy_orders  =    $db->$sold_collection_name->aggregate($pipeline); // sold collection data copying in backup collection
        $get_sold_orders_array =    iterator_to_array($get_sold_buy_orders);
        foreach($get_sold_orders_array as $value_row){
            $where_cond_sold = [
                '_id'=>['$in'=>[$value_row['_id']]]
            ];
            //echo '<pre>'; print_r($ids_to_update);
             $count_sold = $db->$collection_name_backup_sold->updateOne($where_cond_sold,['$set'=>$value_row],['upsert'=>true]);
        }//end sold orders foreach
        $update_main_collection_array = array(
            'collection_name'=>$collection_name_backup_sold,
            'last_updated_date'=>(string)date('Y-m-d h:i:s',strtotime('+5 hours')));
        $where_condition = ['collection_name'=>$collection_name_backup_sold];
        $db->backups_collection_setting->updateOne($where_condition,['$set'=>$update_main_collection_array],['upsert'=>true]); //collection setting main update

        // ************************************************************************************************************************
        // ****************************************** Main Orders collection backup monthly ***************************************
        // ************************************************************************************************************************
        // ************************************************************************************************************************


        $get_orders  =    $db->$orders_collection_name->aggregate($pipeline); // main orders collection data copying in backup collection
        $get_main_orders =    iterator_to_array($get_orders);
        foreach($get_main_orders as $value_row){
            $where_cond_orders = [
                '_id'=>['$in'=>[$value_row['_id']]]
            ];
            //echo '<pre>'; print_r($ids_to_update);
             $count_orders = $db->$backup_order_collection_name->updateOne($where_cond_orders,['$set'=>$value_row],['upsert'=>true]);
        }//end sold orders foreach
        $update_main_collection_array = array(
            'collection_name'=>$backup_order_collection_name,
            'last_updated_date'=>(string)date('Y-m-d h:i:s',strtotime('+5 hours')));
        $where_condition = ['collection_name'=>$backup_order_collection_name];
        $db->backups_collection_setting->updateOne($where_condition,['$set'=>$update_main_collection_array],['upsert'=>true]); //collection setting main update
            echo '<pre>'.'buy_array'.'<pre>';print_r($count);//'Orders Copied To backup';
            echo '<pre>'.'SOld_array'.'<pre>';print_r($count_sold);//'Orders Copied To backup';
            echo '<pre>'.'orders_array'.'<pre>';print_r($count_orders);//'Orders Copied To backup';
            //echo '<pre>'.$count_sold.'Orders Copied To backup';
            exit;
    }

// cronjob for backup monthly of all important collections 
    public function collections_backup_monthly(){ 
        $dateTimeFrom = $this->mongo_db->converToMongodttime(date('Y-m-d 08:00', strtotime('-30 days') ));
        $endDate = date('Y-m-d');
        $dateTimeTo = $this->mongo_db->converToMongodttime($endDate);
            $users_collection_name = "users";
            $kraken_users_collection_name = "kraken_credentials";   
            $coins_collection_name = "coins";   
            $coins_kraken_collection_name = "coins_kraken";   
            $wallet_collection_name = "user_wallet";   
            $wallet_kraken_collection_name = "user_wallet_kraken";
            $backup_users_collection_name = "backup_users_new";
            $backup_kraken_users_collection_name = "backup_kraken_credentials_new";   
            $backup_coins_collection_name = "backup_coins_new";   
            $backup_coins_kraken_collection_name = "backup_coins_kraken_new";   
            $backup_wallet_collection_name = "backup_user_wallet_new";   
            $backup_wallet_kraken_collection_name = "backup_user_wallet_kraken_new";   
               
        $pipeline = [
            [
                '$match'=>
                    ['_id'  =>  ['$ne' => '']]
            ]
        ];
        $db= $this->mongo_db->customQuery();

        // ************************************************************************************************************************
        // ****************************************** Users collection backup weekly **********************************************
        // ************************************************************************************************************************
        // ************************************************************************************************************************


        $get_users = $db->$users_collection_name->aggregate($pipeline);// buy collection data copying in backup collection
        $get_users_array =  iterator_to_array($get_users);
        $ids_to_update = array();
        foreach($get_users_array as $value){
            $where_cond = [
                '_id'=>['$in'=>[$value['_id']]]
            ];
            $count = $db->$backup_users_collection_name->updateOne($where_cond,['$set'=>$value],['upsert'=>true]);
        }// end users array foreach
        $update_main_collection_array = array(
            'collection_name'=>$backup_users_collection_name,
            'last_updated_date'=>(string)date('Y-m-d h:i:s',strtotime('+5 hours')));
        $where_condition = ['collection_name'=>$backup_users_collection_name];
        $db->backups_collection_setting->updateOne($where_condition,['$set'=>$update_main_collection_array],['upsert'=>true]); //collection setting main update

        // ************************************************************************************************************************
        // ******************************************Kraken Credentials collection backup weekly **********************************
        // ************************************************************************************************************************
        // ************************************************************************************************************************

        $get_users_kraken = $db->$kraken_users_collection_name->aggregate($pipeline); // kraken Credentials collection data copying in backup collection
        $get_users_kraken_array =  iterator_to_array($get_users_kraken);
        foreach($get_users_kraken_array as $value_row){
            $where_cond_sold = [
                '_id'=>['$in'=>[$value_row['_id']]]
            ];
             $count_sold = $db->$backup_kraken_users_collection_name->updateOne($where_cond_sold,['$set'=>$value_row],['upsert'=>true]);
        }//end sold orders foreach
        $update_main_collection_array = array(
            'collection_name'=>$backup_kraken_users_collection_name,
            'last_updated_date'=>(string)date('Y-m-d h:i:s',strtotime('+5 hours')));
        $where_condition = ['collection_name'=>$backup_kraken_users_collection_name];
        $db->backups_collection_setting->updateOne($where_condition,['$set'=>$update_main_collection_array],['upsert'=>true]); //collection setting main update


        // ************************************************************************************************************************
        // ****************************************** Users Coins backup weekly **********************************************
        // ************************************************************************************************************************
        // ************************************************************************************************************************


        $get_coins  =    $db->$coins_collection_name->aggregate($pipeline); // coin collection data copying in backup collection
        $get_main_coins =    iterator_to_array($get_coins);
        foreach($get_main_coins as $value_row){
            $where_cond_orders = [
                '_id'=>['$in'=>[$value_row['_id']]]
            ];
             $count_orders = $db->$backup_coins_collection_name->updateOne($where_cond_orders,['$set'=>$value_row],['upsert'=>true]);
        }//end sold orders foreach
        $update_main_collection_array = array(
            'collection_name'=>$backup_coins_collection_name,
            'last_updated_date'=>(string)date('Y-m-d h:i:s',strtotime('+5 hours')));
        $where_condition = ['collection_name'=>$backup_coins_collection_name];
        $db->backups_collection_setting->updateOne($where_condition,['$set'=>$update_main_collection_array],['upsert'=>true]); //collection setting main update

        // ************************************************************************************************************************
        // ******************************************Kraken Users coin collection backup weekly ***********************************
        // ************************************************************************************************************************
        // ************************************************************************************************************************

        $get_coins_kraken  =    $db->$coins_kraken_collection_name->aggregate($pipeline); // coin collection data copying in backup collection
        $get_main_coins_kraken =    iterator_to_array($get_coins_kraken);
        foreach($get_main_coins_kraken as $value_row){
            $where_cond_orders = [
                '_id'=>['$in'=>[$value_row['_id']]]
            ];
             $count_orders = $db->$backup_coins_kraken_collection_name->updateOne($where_cond_orders,['$set'=>$value_row],['upsert'=>true]);
        }//end sold orders foreach
        $update_main_collection_array = array(
            'collection_name'=>$backup_coins_kraken_collection_name,
            'last_updated_date'=>(string)date('Y-m-d h:i:s',strtotime('+5 hours')));
        $where_condition = ['collection_name'=>$backup_coins_kraken_collection_name];
        $db->backups_collection_setting->updateOne($where_condition,['$set'=>$update_main_collection_array],['upsert'=>true]); //collection setting main update

        // ************************************************************************************************************************
        // ******************************************Users Wallet      collection backup weekly ***********************************
        // ************************************************************************************************************************
        // ************************************************************************************************************************


        $get_wallets_binance  =    $db->$wallet_collection_name->aggregate($pipeline); // coin collection data copying in backup collection
        $get_main_wallet =    iterator_to_array($get_wallets_binance);
        foreach($get_main_wallet as $value_row){
            $where_cond_orders = [
                '_id'=>['$in'=>[$value_row['_id']]]
            ];
             $count_orders = $db->$backup_wallet_collection_name->updateOne($where_cond_orders,['$set'=>$value_row],['upsert'=>true]);
        }//end sold orders foreach
        $update_main_collection_array = array(
            'collection_name'=>$backup_wallet_collection_name,
            'last_updated_date'=>(string)date('Y-m-d h:i:s',strtotime('+5 hours')));
        $where_condition = ['collection_name'=>$backup_wallet_collection_name];
        $db->backups_collection_setting->updateOne($where_condition,['$set'=>$update_main_collection_array],['upsert'=>true]); //collection setting main update

        // ************************************************************************************************************************
        // ******************************************Kraken Users Wallet collection backup weekly **********************************
        // ************************************************************************************************************************
        // ************************************************************************************************************************


        $get_wallet_kraken =    $db->$wallet_kraken_collection_name->aggregate($pipeline); // coin collection data copying in backup collection
        $get_main_wallet_kraken =    iterator_to_array($get_wallet_kraken);
        foreach($get_main_wallet_kraken as $value_row){
            $where_cond_orders = [
                '_id'=>['$in'=>[$value_row['_id']]]
            ];
             $count_orders = $db->$backup_wallet_kraken_collection_name->updateOne($where_cond_orders,['$set'=>$value_row],['upsert'=>true]);
        }//end sold orders foreach
        $update_main_collection_array = array(
            'collection_name'=>$backup_wallet_kraken_collection_name,
            'last_updated_date'=>(string)date('Y-m-d h:i:s',strtotime('+5 hours')));
        $where_condition = ['collection_name'=>$backup_wallet_kraken_collection_name];
        $db->backups_collection_setting->updateOne($where_condition,['$set'=>$update_main_collection_array],['upsert'=>true]); //collection setting main update
        echo '******** ALL DONE ********';
        exit;
    }
    public function get_binance_blocked_users_exclude_kraken(){
        $letPipeline_bin=[
                          [
                            '$match'=>[
                              'account_block'=>'yes',
                              'count_invalid_api'=>['$gte'=>3],
                            ]
                          ], 
                          [
                            '$project'=>['_id'=>1,
                            'username'=>1,
                            'email_address'=>1]
                          ]
                        ];
        $db = $this->mongo_db->customQuery();
        $dataResponse = $db->users->aggregate($letPipeline_bin);
        $response = iterator_to_array($dataResponse);
        $letPipeline_kraken=[
                          [
                            '$match'=>[
                              'user_id'=>['$ne'=>''],
                            ]
                          ], 
                          [
                            '$project'=>[
                              '_id'=>0,
                              'user_id'=>1
                            ]
                          ]
                        ];
        $db = $this->mongo_db->customQuery();
        $dataResponse_kr = $db->kraken_credentials->aggregate($letPipeline_kraken);
        $response_kr = iterator_to_array($dataResponse_kr);
        $kraken_users_array = array(); 
        foreach ($response_kr as $value) {
        array_push($kraken_users_array,$value['user_id']);
        }
        $binance_blocked_count = 0;
        $count_kraken_from_blocked = 0;
        $binance_users_ids = array();
        foreach ($response as $value) {
        if(!in_array((string)$value['_id'],$kraken_users_array)){
        $binance_blocked_count = $binance_blocked_count + 1;
        array_push($binance_users_ids,(string)$value['_id']);
        //print_r('found');
        }else{
        $count_kraken_from_blocked = $count_kraken_from_blocked +1; 
        }
                          
        }                    
    return $binance_blocked_count; 
  }
  public function get_user_points($user_id = '',$exchange = '',$start_date = '',$end_date= ''){ // script to find the users who lost points during our maintainace work
    $collection_name = 'buy_orders';
    if($exchange == 'binance' || $exchange == ''){
        $collection_name = 'buy_orders';
    }else {
        $collection_name = 'buy_orders_kraken';
    }
    if($start_date!=''){
        $st_date=explode("T",$start_date);
        $start_date_new = $st_date[0].' '.$st_date[1];
        $newDate = date("Y-m-d h:i", strtotime($start_date_new));
        $startDate = $this->mongo_db->converToMongodttime($newDate);
        // creating end date from parameters.
        $en_date=explode("T",$end_date);
        $end_date_new = $en_date[0].' '.$en_date[1];
        $newDate1 = date("Y-m-d h:i", strtotime($end_date_new));
        $endMongoTime = $this->mongo_db->converToMongodttime($newDate1);
    }else{
        $startDate = $this->mongo_db->converToMongodttime(date('2021-11-27 08:00'));
        $endMongoTime = $this->mongo_db->converToMongodttime(date('2021-12-29 08:00'));
    }
       $total_buy_count = [
            ['$match'=>
                [
                  'created_date'=>['$gte'=>$startDate,'$lte'=>$endMongoTime],
                  'status'=>['$in'=>['new_ERROR','canceled']],
                  'admin_id'=>['$eq'=>$user_id],
                  'parent_status'=>['$exists'=>false],
                  'trigger_type'=>"barrier_percentile_trigger",
                  'application_mode'=>"live",
                ]]
            ];
        $db = $this->mongo_db->customQuery();
        $cursor_trade_count = $db->$collection_name->aggregate($total_buy_count);
        $cavg_child_buy_count_trades_daily = iterator_to_array($cursor_trade_count);
        $total_worth = 0;
        foreach ($cavg_child_buy_count_trades_daily as $value) {
            $coin = substr($value['symbol'], -3);
            if($coin == 'BTC'){
                $btcusdt = convert_btc_to_usdt($value['purchased_price']);    
            }else{
                $btcusdt = $value['purchased_price'];
            }
            $usdt_worth =  (float)$btcusdt * (float)$value['quantity'];
            $total_worth = $total_worth + $usdt_worth ;// body for each loop
            $usdt_worth = 0;
            $btcusdt = 0;
            $coin = '';
        }//end for each loop
        $total_points_dedu = number_format(($total_worth/100),2); 
        if($total_worth > 0){
            //echo 'User total orders USDT worth =>  $'.$total_worth .'<pre>';
            echo '=>'.$total_points_dedu.',';
            //$points = number_format(($total_worth/100),2);    
        }else{
            echo '=>0,';
        } 
    }
    public function users_having_trades_cancel($exchange='',$start_date = '',$end_date= ''){
         if($start_date!=''){
        $st_date=explode("T",$start_date);
        $start_date_new = $st_date[0].' '.$st_date[1];
        $newDate = date("Y-m-d h:i", strtotime($start_date_new));
        $startDate = $this->mongo_db->converToMongodttime($newDate);
        // creating end date from parameters.
        $en_date=explode("T",$end_date);
        $end_date_new = $en_date[0].' '.$en_date[1];
        $newDate1 = date("Y-m-d h:i", strtotime($end_date_new));
        $endMongoTime = $this->mongo_db->converToMongodttime($newDate1);
        }else{
            $startDate = $this->mongo_db->converToMongodttime(date('2021-11-27 08:00'));
            $endMongoTime = $this->mongo_db->converToMongodttime(date('2021-12-29 08:00'));
        }
        $pipeline = [
            ['$match'=> [
                'status'=>['$in'=>['new_ERROR','canceled','new']],
                'parent_status'=>['$ne'=>'parent'],
                'application_mode'=>"live",
                'trigger_type'=>"barrier_percentile_trigger",
                'created_date'=>['$gte'=>$startDate,'$lte'=>$endMongoTime],
                    ]
            ], 
            ['$group'=>[
                '_id'=>['$toString'=>'$admin_id'],
                    ]
            ]
        ];
        $db = $this->mongo_db->customQuery();
        $collection_name = 'buy_orders';
        if($exchange == 'binance' || $exchange == ''){
            $collection_name = 'buy_orders';
        }else {
            $collection_name = 'buy_orders_kraken';
        }
        $cursor_trade_count = $db->$collection_name->aggregate($pipeline);
        $cavg_child_buy_count_trades_daily = iterator_to_array($cursor_trade_count);
        foreach ($cavg_child_buy_count_trades_daily as $value) {
                echo '<pre>'; echo "'".$value['_id']."'";
                $this->get_user_points((string)$value['_id'],$exchange);
        }    
        
    }
}