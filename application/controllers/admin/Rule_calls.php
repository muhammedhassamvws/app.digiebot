<?php
ob_start();
defined('BASEPATH') or exit('No direct script access allowed');
/** **/
class Rule_calls extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //load main template
        // 	ini_set("display_errors", E_ALL);
		// error_reporting(E_ALL);
        $this->stencil->layout('admin_layout');
        //load required slices
        $this->stencil->slice('admin_header_script');
        $this->stencil->slice('admin_header');
        $this->stencil->slice('admin_left_sidebar');
        $this->stencil->slice('admin_footer_script');
        // Load Modal
        $this->load->model('admin/mod_login');
        $this->load->model('admin/mod_coins');
    }
    public function rule_status_change(){
        $this->mod_login->verify_is_admin_login();
        $coin_array_all = $this->mod_coins->get_all_coins();  
        $coin_symbols = array_column($coin_array_all, 'symbol');
        $array_data = $this->mongo_db->get('order_levels'); 
        $response = iterator_to_array($array_data);
        
        $data['data_array'] = $response;
        $data['coin'] = $coin_symbols;
        $this->stencil->paint('admin/rule_calls/rule_status_change', $data);
    }

    public function update_rule_status(){
        $this->mod_login->verify_is_admin_login();
        $where['_id'] = $this->input->post('id');
        $where['level'] = $this->input->post('level');

        $set = array(
            'enable_status' => $this->input->post('enable_status')
        );
        $this->mongo_db->where($where);
        $this->mongo_db->set($set);
        $query1 = $this->mongo_db->update('order_levels');
        $this->rule_status_change();
    }

    public function rules_display(){
        $this->mod_login->verify_is_admin_login();
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://rules.digiebot.com/apiEndPoint/getcoinsrule15status",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization: checkstatus#y7DrszypFQOEZu9ESEEw",
            "cache-control: no-cache",
            "postman-token: feea7478-e64c-4f4c-6892-0129d727fa23"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $convert = json_decode($response);
            $data['hit_data'] = $convert;
        }

        $this->mod_login->verify_is_admin_login();
        $coin_array_all = $this->mod_coins->get_all_coins();

        $data['coins'] = $coin_array_all;
        $this->stencil->paint('admin/rule_calls/rule',$data);
    }

    public function update_rule(){
        $this->mod_login->verify_is_admin_login();
        if($this->input->post()){
            $filterData['search_data'] = $this->input->post();
            $this->session->set_userdata($filterData);
        }
        $this->input->post('coin');
        $this->input->post('mode');
        $this->input->post('status');

        $params = array(
            'coin'   => $this->input->post('coin'),
            'mode'   => $this->input->post('mode'),
            'status' => $this->input->post('status'),
        );
        
        $jsondata = json_encode($params);
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://rules.digiebot.com/apiEndPoint/setrulelevel15",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>$jsondata,
          CURLOPT_HTTPHEADER => array("Content-Type: application/json" , "authorization: rule#15(njVEkn2AEBppiqCZ"), 
        ));
        $response_price = curl_exec($curl);	
        curl_close($curl);                                
        $api_response = json_decode($response_price);
        $err = curl_error($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
            } else {
                $this->rules_display();
            }
        //echo"<pre>";print_r($postdata);
    }

    public function test(){
        ini_set("display_errors", E_ALL);
		error_reporting(E_ALL);
        $db = $this->mongo_db->customQuery();
        $query = [

            [
                '$lookup' => [
                    'from' => 'users',
                    'let' => [
                        'user_id_obj' => ['$toObjectId' => '$user_id'],
                    ],
                    'pipeline' => [
                        [
                            '$match' => [
                                'application_mode'    =>  'both'
                            ]
                        ],
                        [
                            '$limit' => 10
                        ]
                        // [
                        //     '$sort' => [ $field_weekly_wallet_stats_updated_date => 1]
                        // ],
                        // [
                        //     '$project' => [
                        //         '_id' => 1,
                        //         $field_weekly_wallet_stats_updated_date => 1
                        //     ]
                        // ],
                    ],
                    'as' => 'users'
                ]
            ],
            [
                '$limit' => 10
            ]
        ];

        $res = $db->kraken_credentials->aggregate($query); 
        $result = iterator_to_array($res);
        echo "<pre>";print_r($result);



    
        die('testing');

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

        $lookupnew = [
            [
                '$group' => [
                    '_id' => '$_id',
                    'active' => [
                        '$push' => [
                            '$cond' => [
                                'if' => ['$eq' => ['$exchange_enabled', 'yes']],
                                'then' =>  1,
                                'else' => 0
                            ]
                        ]
                    ],

                    'unactive' => [
                        '$push' => [
                            '$cond' => [
                                'if' => ['$eq' => ['$exchange_enabled', 'no']],
                                'then'  => 1,
                                'else' => 0
                            ]
                        ]
                    ],


                    'test' => [
                        '$push' => [
                            '$cond' => [
                                'if' => ['$in' => ['$exchange_enabled', ['yes', 'no']]],
                                'then' =>  0,
                                'else' => 1
                            ]
                        ]
                    ],
                ]
            ],


            [
                '$addFields' => [
                    'active' => ['$arrayElemAt' =>['$active' ,0]]
                ]
            ],

            
            [
                '$addFields' => [
                    'unactive' => ['$arrayElemAt' =>['$unactive' ,0]]
                ]
            ],

          

            [
                '$addFields' => [
                    'test' => ['$arrayElemAt' =>['$test' ,0]]
                ]
            ],


            [
                '$group' => [
                    '_id' => null,
                    'total' => ['$sum' => 1],
                    'active' => ['$sum' => '$active'],
                    'unactive' => ['$sum' => '$unactive'],
                    'test' => ['$sum' => '$test'],
                ]

            ]
        ];

        $res = $db->user_investment_binance->aggregate($lookup); 
        $result = iterator_to_array($res);
        echo "<pre>";print_r($result);

        $btc_coin_in_arrBinance = ['XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC'];
        $coinArrayUSDTBinance   = ['BTCUSDT', 'EOSUSDT', 'LTCUSDT','XRPUSDT','NEOUSDT', 'QTUMUSDT'];

        $db = $this->mongo_db->customQuery();

       
        $lookup = [
            [
                '$match' => [
                    'application_mode' => 'live',
                    'admin_id' => '5eb5a5a628914a45246bacc6',
                    'status'   => ['$in' => ['LTH', 'FILLED']]
                ]
            ],

            [
                '$group' => [
                
                    '_id'             =>  '$_id',      
                    
                    'btc' => [
                        '$push' => [
                            '$cond' => [ 
                                'if' => [ '$in' => [ '$symbol', $btc_coin_in_arrBinance ] ], 
                                'then' => ['$each' , ['$symbol', '$purchased_price', '$market_sold_price', '$quantity']], //['$each' , '$$ROOT'],
                                'else' => null//['$symbol', '$purchased_price', '$market_sold_price', '$quantity'],
                            ]
                        ]
                    ],

                    'usdt' => [
                        '$push' => [
                            '$cond' => [ 
                                'if' => [ '$in' => ['$symbol' ,  $coinArrayUSDTBinance] ], 
                                'then' => ['$each' , ['$symbol', '$purchased_price', '$market_sold_price', '$quantity']],   //['$each' , '$$ROOT'], 
                                'else' => null//['$symbol', '$purchased_price', '$market_sold_price', '$quantity'],
                            ]
                        ]
                    ]

                ]
            ],


            //purchased price
            [
                '$addFields' => [
                    'purchased_price_btc' =>[ '$arrayElemAt' => [ '$btc', 0 ] ],
                ]
            ],

            [
                '$addFields' => [
                    'purchased_price_btc' =>[ '$arrayElemAt' => [ '$purchased_price_btc', 1 ] ],
                ]
            ],
            [
                '$addFields' => [
                    'purchased_price_btc' =>[ '$arrayElemAt' => [ '$purchased_price_btc', 1 ] ],
                ]
            ],
            [
                '$addFields' => [
                    'purchased_price_btc' =>[ '$toDouble' =>  '$purchased_price_btc'],
                ]
            ],

            //quantity 
            [
                '$addFields' => [
                    'quantity_btc' =>[ '$arrayElemAt' => [ '$btc', 0 ] ],
                ]
            ],

            [
                '$addFields' => [
                    'quantity_btc' =>[ '$arrayElemAt' => [ '$quantity_btc', 1 ] ],
                ]
            ],
            [
                '$addFields' => [
                    'quantity_btc' =>[ '$arrayElemAt' => [ '$quantity_btc', 1 ] ],
                ]
            ],
            [
                '$addFields' => [
                    'quantity_btc' =>[ '$toDouble' =>  '$quantity_btc'],
                ]
            ],


            //usdt purchased price
            [
                '$addFields' => [
                    'purchased_price_usdt' =>[ '$arrayElemAt' => [ '$usdt', 0] ],
                ]
            ],

            [
                '$addFields' => [
                    'purchased_price_usdt' =>[ '$arrayElemAt' => [ '$purchased_price_usdt', 1 ] ],
                ]
            ],

            [
                '$addFields' => [
                    'purchased_price_usdt' =>[ '$arrayElemAt' => [ '$purchased_price_usdt', 1 ] ],
                ]
            ],


            [
                '$addFields' => [
                    'purchased_price_usdt' =>[ '$toDouble' =>  '$purchased_price_usdt' ],
                ]
            ],


            //usdt quantity
            [

                '$addFields' => [
                    'quantity_usdt' =>[ '$arrayElemAt' => [ '$usdt', 0] ],
                ]
            ],

            [
                '$addFields' => [
                    'quantity_usdt' =>[ '$arrayElemAt' => [ '$quantity_usdt', 1 ] ],
                ]
            ],

            [
                '$addFields' => [
                    'quantity_usdt' =>[ '$arrayElemAt' => [ '$quantity_usdt', 1 ] ],
                ]
            ],


            [
                '$addFields' => [
                    'quantity_usdt' =>[ '$toDouble' =>  '$quantity_usdt' ],
                ]
            ],


            [
                '$group' => [
                    '_id' => null,
                    'btcsold'         =>  ['$sum' =>  ['$multiply' => ['$purchased_price_btc',  '$quantity_btc']] ],
                    'usdtsold'        =>  ['$sum' =>  ['$multiply' => ['$purchased_price_usdt',  '$quantity_usdt']] ],

                ]
            ],
        ];

        echo "<br>=============>";
        $res = $db->buy_orders->aggregate($lookup); 
        $result = iterator_to_array($res);
        echo "<pre>";print_r($result);

        die("asim testing");

        $this->load->helper('new_common_helper');
        $getpriceArray = getAllMarketValue('binance');
        $pricesJsonArr = [];
        echo "<pre>";

        foreach($getpriceArray as $key=>$val){
            $pricesJsonArr[] = [
                'symbol' => $key,
                'price' => $val,
            ];
        }

        // print_r($pricesJsonArr);

        $per = 5;
        $rangeSell = 0.4;
        $rangeSL   = 0.2;
        $query =[
            [
                '$match' => [
                    'status' => [ '$in' =>  ['LTH','FILLED', 'FILLED_ERROR', 'SELL_ID_ERROR'] ],
                    'application_mode'  =>  'live',
                    'is_sell_order'     =>  'yes',
                    'count_avg_order'   =>  ['$exists'=> false],
                    'cost_avg'          =>  ['$nin' => ['yes', 'taking_child', 'completed']],
                    'cavg_parent'       =>  ['$ne' => 'yes'],
                    'move_to_cost_avg'  =>  ['$ne' =>  'yes']
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
                    'currPrice' =>[ '$arrayElemAt' => [ '$currPrice', 0 ] ],
                    'purchased_price' => [ '$toDouble' => '$purchased_price' ],
                ]
            ],

            [ 
                '$addFields' => [ 
                    'currPrice1' => [ '$toDouble' => '$currPrice.price' ]
                ] 
            ],
            [ 
                '$addFields' => [ 
                    'pl' => [ '$divide' => [ [ '$multiply' => [ [ '$subtract' => [ '$currPrice1', '$purchased_price' ] ], 100 ] ], '$purchased_price' ] ]

                ]
            ],
          
            [
                '$match' => [
                    // '$expr' => [ '$gte' => ['$pl', $per] ]
                    '$expr' => [ '$gte' => ['$pl', '$first_stop_loss_update'] ]
                ]
            ],

            [
                
                '$addFields' => [
                    'profitPer' => ['$sum' => [$rangeSell, '$defined_sell_percentage']], 
                    'ASL'       => ['$sum' => [$rangeSL,    '$custom_stop_loss_percentage']],

                    'newSellPriceCal'   => ['$divide' => ['$profitPer', '$purchased_price']],
                    'newSellPrice'      => ['$sum' => ['$newSellPriceCal', '$purchased_price']],

                    'initailTrail'      => ['$divide' => ['$ASL', '$purchased_price']],
                    'initailTrailNew'   => ['$sum' => ['$initailTrail', '$purchased_price']],

                    'nextUpdate'        =>  '$profitPer'
                ]
            ],
            [
                '$limit' => 10
            ]
        ];

        $get = $db->buy_orders->aggregate($query);
        $getresponse = iterator_to_array($get);
        print_r($getresponse);

        // let conditonTesting = {"$cond" : 
            //     [  
            //         {"$gt" : ["$Filed_Name" , 100]},
            //         {"$lt" : ["$fieldName",  50]},
            //         {"$fieldName" : ["$exists": true]},
            //         {"$add" : ["$fieldname1",  "$fieldName2"]}
            //     ]
            // };

            // $conditonTesting = [
            //     '$cond' => [
            //         ['$gt' => ['$Filed_Name' , 100]],
            //         ['$lt' => ['$fieldName',  50]],
            //         ['$fieldName' => ['$exists'=> true]],
            //         ['$add' => ['$fieldname1',  '$fieldName2']]

            //     ]
            // ];

            // $lookup = [
            //     [
            //         '$match' => 'asim'
            //     ],
            //     [
            //         '$redact' => [
            //             '$cond' => [
            //                 'if' => ['$eq' => [ '$level', 5 ] ],
            //                 'then' => '$$PRUNE',
            //                 'else' => '$$DESCEND'
            //             ],
            //         ],
            //     ],
            // ];

            // $document = $this->mongo_db->customQuery();
            // $pipeline = array(
            // array(
            // '$lookup' => array(
            // 'from' => 'cc_contacts',
            // 'localField' => 'lead_id',
            // 'foreignField' => 'id',
            // 'as' => 'cc_contacts',
            // )
            // ),
            // array('$unwind' => array( 'path' => '$cc_contacts', 'preserveNullAndEmptyArrays' => true)),
            // array(
            // '$redact' => array(
            // '$cond' => array(
            // 'if' => array(
            // '$eq' => array('$lead_id', '$cc_contacts.id'),
            // ),
            // 'then' => '$$KEEP',
            // 'else' => '$$PRUNE'
            // )
            // )
            // ),


                
            // echo "<pre>";print_r($_SERVER);
            // list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', $_SERVER['HTTP_AUTHORIZATION']);
            // $username = $this->input->server('PHP_AUTH_USER');
            // $password = $this->input->server('PHP_AUTH_PW');
            // $barrier = $this->input->server('HTTP_AUTHORIZATION');
            // $redirect = $this->input->server('REDIRECT_HTTP_AUTHORIZATION');
            // if ($username == 'Asim' && $password == '123' || $barrier == 'Bearer 12344' || $redirect == 'Bearer retertesgertert') {
            //     $name= 'asim';
            //     echo json_encode($name);
            // }else{
                
            //     $message_1 = array(
            //         'message' => 'error',
            //         'res'     =>'404'
            // );
            // echo json_encode($message_1);
            // }

            // file handling 
            // $this->load->helper('file');
            // $text = "i m Asim";
            // $file_path = "application".DIRECTORY_SEPARATOR."files" .DIRECTORY_SEPARATOR."asim.txt";
            // write_file($file_path, $text);
            // write_file($file_path, $text, 'a');   //write new text at the end of the file append mode
            // write_file($file_path, $text, 'r');   // read mode
            // write_file($file_path, $text, 'x'); // work only if file does't exists
            // $string = read_file($file_path); 
            // echo "finished";
            // end file handling

            //web scrapping testing
            // $url = 'https://www.whatmobile.com.pk/';
            // $curl = curl_init();
            // curl_setopt($curl, CURLOPT_URL, $url);
            // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // $result = curl_exec($curl); 
            // $expression = '#<img[^>]+>#';
            // $data ='';
            // preg_match_all($expression, $result, $data);
            // echo"<pre>";
            // print_r($data);
            //end data scrapping

            // use of composer pakage
            // include_once APPPATH . 'vendor_bk/autoload.php'; 
            // $parse = new \Smalot\pdfperser\parser();
            // $pdf = $parse->parseFile(APPPATCH.'vendor_bd/smalot/pdfparser/samples/Document1_foxitreader.pdf');
            // $text = $pdf->getText();
            // echo $text;
            // end composer pakage 


            //     start join query in mongo db
        //     $where['status'] = "error";
        // $collection1 = 'kraken_credentials';
        // $join = [
        //     'from' => $collection1,
        //     'localField' => '_id',
        //     'foreignField' => 'user_id',
        //     'as' => 'result',
        // ];
        // $query = [
        //     ['$lookup' => $join],
        //     ['$match' => []],
        //     ['$sort' => ['created_date' => -1]],
        //     ['$limit' => 100],
        // ];
        // $collection2 = 'users';
        // $db = $this->mongo_db->customQuery();
        // $response = $db->$collection2->aggregate($query);
        // $records = iterator_to_array($response);
        // echo "<pre>";print_r($records);
        // echo "<br>done";

        $lookup = [
            [
                '$match' => [

                    'application_mode'		=> 	'live',
                    'opportunityId'			=> 	$opportunityId,
                    'status'				=> 	['$in' 		=> 	['LTH', 'FILLED']],
                    'resume_status'  		=> 	['$ne' 		=> 	'resume'],
                    'cost_avg' 				=> 	['$ne' 		=> 	'yes'],
                    'cavg_parent' 			=> 	['$ne' 		=> 	'yes'],
                    'cavg_parent' 			=> 	['$exists' 	=> 	false],
                    'cost_avg'				=>	['$nin'    	=> 	['yes', 'completed', '']],

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
                    'currPrice' =>[ '$arrayElemAt' => [ '$currPrice', 0 ] ],
                    'purchased_price' => [ '$toDouble' => '$purchased_price' ],
                ]
            ],

            [ 
                '$addFields' => [ 
                    'currPrice1' => [ '$toDouble' => '$currPrice.price' ]
                ] 
            ],


            [ 
                '$addFields' => [ 

                    'openLthPurchasePrice' =>  ['$sum' =>  ['divide' => [  ['$subtract' => ['$currPrice1', '$purchased_price'] ],  '$purchased_price' ] ]  ],

                ] 
            ],

            [
                '$group' => [
                
                    '_id'             =>  '$_id',      
                    
                    'btc' => [
                        '$push' => [
                            '$cond' => [ 
                                'if' => [ '$in' => [ '$symbol', $btc_coin_in_arrBinance ] ], 
                                'then' => ['$each' , ['$symbol', '$purchased_price', '$market_sold_price', '$quantity', '$buy_fraction_filled_order_arr']], 
                                'else' => null
                            ]
                        ]
                    ],

                    'usdt' => [
                        '$push' => [
                            '$cond' => [ 
                                'if' => [ '$in' => ['$symbol' ,  $coinArrayUSDTBinance] ], 
                                'then' => ['$each' , ['$symbol', '$purchased_price', '$market_sold_price', '$quantity', '$buy_fraction_filled_order_arr']],    
                                'else' => null
                            ]
                        ]
                    ]

                ]
            ],

            // purchased price
            [
                '$addFields' => [
                    'purchased_price_btc' =>[ '$arrayElemAt' => [ '$btc', 0 ] ],
                ]
            ],

            [
                '$addFields' => [
                    'purchased_price_btc' =>[ '$arrayElemAt' => [ '$purchased_price_btc', 1 ] ],
                ]
            ],
            [
                '$addFields' => [
                    'purchased_price_btc' =>[ '$arrayElemAt' => [ '$purchased_price_btc', 1 ] ],
                ]
            ],
            [
                '$addFields' => [
                    'purchased_price_btc' =>[ '$toDouble' =>  '$purchased_price_btc'],
                ]
            ],

            // quantity 
            [
                '$addFields' => [
                    'quantity_btc' =>[ '$arrayElemAt' => [ '$btc', 0 ] ],
                ]
            ],

            [
                '$addFields' => [
                    'quantity_btc' =>[ '$arrayElemAt' => [ '$quantity_btc', 1 ] ],
                ]
            ],
            [
                '$addFields' => [
                    'quantity_btc' =>[ '$arrayElemAt' => [ '$quantity_btc', 1 ] ],
                ]
            ],
            [
                '$addFields' => [
                    'quantity_btc' =>[ '$toDouble' =>  '$quantity_btc'],
                ]
            ],


            // usdt purchased price
            [
                '$addFields' => [
                    'purchased_price_usdt' =>[ '$arrayElemAt' => [ '$usdt', 0] ],
                ]
            ],

            [
                '$addFields' => [
                    'purchased_price_usdt' =>[ '$arrayElemAt' => [ '$purchased_price_usdt', 1 ] ],
                ]
            ],

            [
                '$addFields' => [
                    'purchased_price_usdt' =>[ '$arrayElemAt' => [ '$purchased_price_usdt', 1 ] ],
                ]
            ],


            [
                '$addFields' => [
                    'purchased_price_usdt' =>[ '$toDouble' =>  '$purchased_price_usdt' ],
                ]
            ],


            // usdt quantity
            [

                '$addFields' => [
                    'quantity_usdt' =>[ '$arrayElemAt' => [ '$usdt', 0] ],
                ]
            ],

            [
                '$addFields' => [
                    'quantity_usdt' =>[ '$arrayElemAt' => [ '$quantity_usdt', 1 ] ],
                ]
            ],

            [
                '$addFields' => [
                    'quantity_usdt' =>[ '$arrayElemAt' => [ '$quantity_usdt', 1 ] ],
                ]
            ],


            [
                '$addFields' => [
                    'quantity_usdt' =>[ '$toDouble' =>  '$quantity_usdt' ],
                ]
            ],


            //commission btc
            [

                '$addFields' => [
                    'commissionBtc' =>[ '$arrayElemAt' => [ '$btc', 0] ],
                ]
            ],
            [

                '$addFields' => [
                    'commissionBtc' =>[ '$arrayElemAt' => [ '$commissionBtc', 1] ],
                ]
            ],
            [

                '$addFields' => [
                    'commissionBtc' =>[ '$arrayElemAt' => [ '$commissionBtc', 4] ],
                ]
            ],

            [

                '$addFields' => [
                    'commissionBtc' =>[ '$arrayElemAt' => [ '$commissionBtc', 0] ],
                ]
            ],



            //commission usdt
            [

                '$addFields' => [
                    'commissionUsdt' =>[ '$arrayElemAt' => [ '$usdt', 0] ],
                ]
            ],
            [

                '$addFields' => [
                    'commissionUsdt' =>[ '$arrayElemAt' => [ '$usdt', 1] ],
                ]
            ],
            
            [

                '$addFields' => [
                    'commissionUsdt' =>[ '$arrayElemAt' => [ '$usdt', 4] ],
                ]
            ],
            [

                '$addFields' => [
                    'commissionUsdt' =>[ '$arrayElemAt' => [ '$commissionUsdt', 0] ],
                ]
            ],



            [
                '$group' => [
                    '_id' => null,
                    'btcopenLth'         =>  ['$sum' =>  ['$multiply' => ['$purchased_price_btc',  '$quantity_btc']] ],
                    'usdtopenLth'        =>  ['$sum' =>  ['$multiply' => ['$purchased_price_usdt',  '$quantity_usdt']] ],
                    'commissionBtc'		 =>  ['$sum' => '$commission'],
                    'commissionUsdt'	 =>  ['$sum' => '$commission'],

                ]
            ],
        ];
    
    }

    public function balaceTest(){


        $user_id = '5eb5a5a628914a45246bacc6';
       
    }
}