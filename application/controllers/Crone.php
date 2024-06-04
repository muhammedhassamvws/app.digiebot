<?php
ob_start();
defined('BASEPATH') or exit('No direct script access allowed');
/** **/
class Crone extends CI_Controller
{
    private $originalString = "vizzwebsolutions";
    private $keyCode = "vizzwebsolutions";
    function __construct(){
        parent::__construct();
        //load main template
        // ini_set("display_errors", E_ALL);
		// error_reporting(E_ALL);
        $this->load->helper('common_helper');
    }
    public function getUserIP($user_id)
    {
        $letIPAddress = "";
        if(empty($user_id)){
            return $letIPAddress;
        }
        $db = $this->mongo_db->customQuery();
        $id = $this->mongo_db->mongoId($user_id);
        $where = array();
        $where['_id'] = $id;
        $user = $db->users->findOne($where);
        if(!empty($user))
        {
            $arrResult = (array) $user;
            //print_r($arrResult);
            $letIPAddress = $arrResult['trading_ip'];
        }
        //echo 'trading_iptrading_iptrading_iptrading_iptrading_iptrading_iptrading_iptrading_iptrading_iptrading_iptrading_ip --- '.$letIPAddress;
        return $letIPAddress;

    }
    public function api_key_checking(){

        $db = $this->mongo_db->customQuery();
        $olderTime = $this->mongo_db->converToMongodttime(Date('Y-m-d H:i:s',strtotime('-12 hours') ));

        if( !empty($this->input->get()) ){

            $lookup = [
                [
                    '$match' => [
                        '$and' => [
                            [
                                'trading_ip' => ['$exists' => true],
                            ],
                            [
                                'trading_ip' => ['$nin' => ['', null] ],
                            ],
                        ],
                       'username'  => $this->input->get('userName'),
                    ]
                ],
                [
                    '$project' =>[
                        '_id'       =>  '$_id',
                        'username'  =>  '$username',
                        'trading_ip'=>  '$trading_ip',
                    ]
                ],
                // [
                //     '$sort' => ['api_key_valid_checking' => 1]
                // ],
                [
                    '$sort' => ['_id' => -1]
                ],
                [
                    '$limit' => 1
                ]

            ];
        }else{
         $or1 =   array( '$or' => array(
            array( 'account_block' => array( '$exists' => false ) ) ,
            array( 'account_block' => 'no' ) 
          ));
         $or2 =   array( '$or' => array(
            array( 'count_api_key_checking_crone' => array( '$exists' => false ) ) ,
            array( 'count_api_key_checking_crone' => array('$ne'=>1) ) 
          ));
          $or3 =   array( '$or' => array(
            array( 'count_invalid_api' => array( '$exists' => false ) ) ,
            array( 'count_invalid_api' => array('$gte'=>0)) 
          ));
          


        $query = array('is_api_key_valid'=>array('$ne'=>'yes'), 'application_mode'=>array('$in'=>array('both','live', 'test')),'trading_ip'=>array('$nin'=>array(null,'')),'$and' => array( $or1, $or2, $or3 ) );    


            $lookup = [
                [
                    '$match' => $query
                    // [

                    //     '$and' => [
                    //         [
                    //             'trading_ip' => ['$exists' => true],
                    //         ],
                    //         [
                    //             'trading_ip' => ['$nin' => ['', null] ],
                    //         ],
                            
                    //     ],
                    //     'application_mode' => ['$in' => ['both', 'live']],
                        
                    //     '$or' => [

                    //         ['account_block'  => ['$exists' => false]],
                    //         ['account_block'  => 'no'],
                    //     ],

                    //     '$or'  => [
                    //         ['api_key_valid_checking' => ['$exists' => false]],
                    //         ['api_key_valid_checking' => ['$lte' => $olderTime]]
                    //     ],

                    //     '$or' => [

                    //         ['count_invalid_api' => ['$exists' => false]],
                    //         ['count_invalid_api' => ['$lte' => 0]]
                    //     ],
                    // ]
                ],
                // [
                //     '$sort' => ['api_key_valid_checking' => 1]
                // ],
                [
                    '$sort' => ['_id' => -1]
                ],
                [
                    '$project' =>[
                        '_id'                   =>  '$_id',
                        'username'              =>  '$username',
                        'trading_ip'            =>  '$trading_ip',
                    ]
                ],
                
                [
                    '$limit' => 1
                ]

            ];
        }


        // echo json_encode($lookup);

        $get_users     =  $db->users->aggregate($lookup);
        $filter_Users  =  iterator_to_array($get_users);
        // echo "<pre>"; print_r($filter_Users); exit;
        echo "<br>Count : ".count($filter_Users);
        if(count($filter_Users) > 0){
            $total_users = count($filter_Users);
            for($u=0;$u<$total_users;$u++)
            {
               echo '<br>tradingIp: '.$filter_Users[$u]['trading_ip'];
               echo "<br> USERID ".(string)$filter_Users[$u]['_id'];
                    $Ipname = '';    
                    if( $filter_Users[$u]['trading_ip'] == '3.227.143.115'){
        
                        $Ipname = 'ip1';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.228.180.22'){
            
                        $Ipname = 'ip2';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.226.226.217'){
            
                        $Ipname = 'ip3';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.228.245.92'){
            
                        $Ipname = 'ip4';
                    }elseif($filter_Users[$u]['trading_ip'] == '54.157.102.20')
                    {
                         $Ipname = 'ip6';
                    }elseif($filter_Users[$u]['trading_ip'] == '18.170.235.202'){
                        $Ipname = 'ip7';
                    }
                    if(empty($Ipname))
                    {
                        $update_array = [
                            'empty_trading_ip'=>'yes',
                            'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
                        ];
        
                       $db->users->updateOne(['_id' => $this->mongo_db->mongoId((string)$filter_Users[$u]['_id']) ], ['$set' => $update_array ]);

                        echo 'IP is Empty';
                        continue;
                    }
                    $url = "https://".$Ipname.".digiebot.com/apiKeySecret/validateApiKeySecretAdmin";
                    //echo '<pre>'.$url;exit;
                    // check api is valid or not 
                    
                    $data = (string)$filter_Users[$u]['_id']; // Shahzad bhai asked to send user_id in this data
                    $key = "vizz@digiesolutions"; // Shahzad bhai asked to add this as a key
                    $iv = random_bytes(16);   // Generate a random initialization vector

                    // Encrypt the data using AES-256-CBC
                    $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);

                    // Combine IV and encrypted data for storage
                    $encryptedMessage = base64_encode($iv . $encryptedData);

                    echo "<br>";
                    echo "<br>";

                    // echo "encryptedMessage:: ". $encryptedMessage; exit;

                    $payLoadExchangeVarify = [
                      'user_id'    =>  (string)$filter_Users[$u]['_id']
                    ];
        
                    $req_arr = [
                        'req_type'     =>  'POST',
                        'req_url'      =>  $url,
                        'req_params'   =>  $payLoadExchangeVarify,
                    ];
                    $resp      = dynamicCURLHitForAPIChecking($req_arr, $encryptedMessage);
                    $respData  = json_decode($resp);
                    print_r($respData);echo '    check resp';
                    if($respData->success == 1 || $respData->api_key == "VALID"){
        
                        $update_array = [
                            'is_api_key_valid'      =>  "yes",
                            'count_invalid_api'     =>  0,
                            'empty_trading_ip'=>'',
                            'account_block'         =>  'no',
                            // 'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                            'count_api_key_checking_crone' => 1
                        ];
        
                         // for again request to binance wait for 3 sec and then send second call
                         $db->users->updateOne(['_id' => $this->mongo_db->mongoId((string)$filter_Users[$u]['_id']) ], ['$set' => $update_array ]);
                         $db->user_investment_binance->updateOne(['admin_id' => (string)$filter_Users[$u]['_id'] ], ['$set' => ['exchange_enabled' => 'yes','is_api_key_valid'=>'yes' ]]); 
                         echo "<br>SuccessFully Updated API key data valid 1";  
                     }elseif($respData->success == 0 || $respData->success == false){
         
                         $db->user_investment_binance->updateOne(['admin_id' => (string)$filter_Users[$u]['_id'] ], ['$set' => ['exchange_enabled' => 'no','is_api_key_valid'=>'no' ]]); 
                         $db->users->updateOne(['_id' => $this->mongo_db->mongoId((string)$filter_Users[$u]['_id']) ], ['$set' =>  ['is_api_key_valid' => "no",'api_line_number'=>'211', 'empty_trading_ip'=>'', 'count_invalid_api'  => 1, 'count_api_key_checking_crone' => 1]]); 
                         echo "<br>SuccessFully Updated API key data invalid 0";  
                     }
                     // echo "<br>SuccessFully Updated";  
            }
           
    }else{
        $or1 = [
            '$or' => [
                ['account_block' => ['$exists' => false]],
                ['account_block' => 'no']
            ]
        ];
        
        $query = [
            'application_mode' => ['$in' => ['both', 'live', 'test']],
            'trading_ip' => ['$nin' => [null, '']],
            '$and' => [$or1]
        ];
        
        $updateResult = $db->users->updateMany(
            $query,   
            ['$set' => ['count_api_key_checking_crone' => 0]]   
        );
        
        $updatedCount = $updateResult->getModifiedCount();
        
        echo "Updated $updatedCount documents.";
    }
        // if(count($filter_Users) > 0){
        //     echo '<br>tradingIp: '.$filter_Users[0]['trading_ip'];

        //     if( $filter_Users[0]['trading_ip'] == '3.227.143.115'){

        //         $Ipname = 'ip1';
        //     }elseif($filter_Users[0]['trading_ip'] == '3.228.180.22'){
    
        //         $Ipname = 'ip2';
        //     }elseif($filter_Users[0]['trading_ip'] == '3.226.226.217'){
    
        //         $Ipname = 'ip3';
        //     }elseif($filter_Users[0]['trading_ip'] == '3.228.245.92'){
    
        //         $Ipname = 'ip4';
        //     }else{
    
        //         exit;
        //     }

        //     $url = "https://".$Ipname.".digiebot.com/apiKeySecret/validateApiKeySecretAdmin";

        //     // check api is valid or not 
        //     echo "<br>".(string)$filter_Users[0]['_id'];
        //     $payLoadExchangeVarify = [
        //       'user_id'    =>  (string)$filter_Users[0]['_id']
        //     ];

        //     $req_arr = [
        //         'req_type'     =>  'POST',
        //         'req_url'      =>  $url,
        //         'req_params'   =>  $payLoadExchangeVarify,
        //     ];
        //     $resp      = dynamicCURLHit($req_arr);
        //     $respData  = json_decode($resp);

        //     if($respData->success == 1 || $respData->api_key == "VALID"){

        //         $update_array = [
        //             'is_api_key_valid'      =>  "yes",
        //             'count_invalid_api'     =>  0,
        //             'account_block'         =>  'no',
        //             'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
        //         ];

        //          // for again request to binance wait for 3 sec and then send second call
        //         $db->users->updateOne(['_id' => $this->mongo_db->mongoId((string)$filter_Users[0]['_id']) ], ['$set' => $update_array ]);
        //         $db->user_investment_binance->updateOne(['admin_id' => (string)$filter_Users[0]['_id'] ], ['$set' => ['exchange_enabled' => 'yes' ]]); 
        //     }elseif($respData->success == 0 || $respData->success == false){

        //         $db->user_investment_binance->updateOne(['admin_id' => (string)$filter_Users[0]['_id'] ], ['$set' => ['exchange_enabled' => 'no' ]]); 
        //         $db->users->updateOne(['_id' => $this->mongo_db->mongoId((string)$filter_Users[0]['_id']) ], ['$set' =>  ['api_key_valid_checking' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ,'is_api_key_valid' => "no", 'count_invalid_api'  => 1]]);
        //     }
        //     echo "<br>SuccessFully Updated";
        // }//end if
        echo "<br>Done";

    }//end function

    // invalid api key rechecking after 6 hours
    public function invalid_api_rechecking(){
        // die('stop');
        $db = $this->mongo_db->customQuery();
        $olderTime = $this->mongo_db->converToMongodttime(Date('Y-m-d H:i:s',strtotime('-6 hours') ));

        $lookup = [
            [
                '$match' => [
                    'application_mode' => ['$in' => ['both', 'live', 'test']],
                    // 'is_api_key_valid' => ['$ne' => 'yes'],

                    '$or' => [

                        ['account_block'    => 'no'],
                        ['account_block'    => ['$exists' => false]],
                    ],
                    
                    // '$or'  => [
                    //     ['api_key_valid_checking' => ['$exists' => false]],
                    //     ['api_key_valid_checking' => ['$lte' => $olderTime]]
                    // ],
                    

                    '$or'  => [
                            ['count_api_key_checking_crone' => ['$exists' => false]],
                            ['count_api_key_checking_crone' => ['$ne' => 1]]
                    ],
                    'count_invalid_api' => ['$gte' => 1, '$lte' => 4],
                
                    '$and' => [
                        [
                            'trading_ip' => ['$exists' => true],
                        ],
                        [
                            'trading_ip' => ['$nin' => ['', null] ],
                        ],
                    ],
                ]
            ],
            [
                '$project' =>[
                    '_id'                   =>  '$_id',
                    'username'              =>  '$username',
                    'count_invalid_api'     =>  '$count_invalid_api',
                    'trading_ip'            =>  '$trading_ip',
                ]
            ],
            // [
            //     '$sort' => ['api_key_valid_checking' => 1]
            // ],
            [
                '$sort' => ['_id' => -1]
            ],
            [
                '$limit' => 1
            ]

        ];


        $get_users     =  $db->users->aggregate($lookup);
        $filter_Users  =  iterator_to_array($get_users);
        echo "<pre>"; print_r($filter_Users); 
        if(count($filter_Users) > 0 ){

            echo '<br>tradingIp: '.$filter_Users[0]['trading_ip'];
            if( $filter_Users[0]['trading_ip'] == '3.227.143.115'){

                $Ipname = 'ip1';
            }elseif($filter_Users[0]['trading_ip'] == '3.228.180.22'){
    
                $Ipname = 'ip2';
            }elseif($filter_Users[0]['trading_ip'] == '3.226.226.217'){
    
                $Ipname = 'ip3';
            }elseif($filter_Users[0]['trading_ip'] == '3.228.245.92'){
    
                $Ipname = 'ip4';
            }elseif($filter_Users[0]['trading_ip'] == '54.157.102.20'){
    
                $Ipname = 'ip6';
            }elseif($filter_Users[0]['trading_ip'] == '18.170.235.202'){
    
                $Ipname = 'ip7';
            }
            else{
    
                exit;
            }

            $url = "https://".$Ipname.".digiebot.com/apiKeySecret/validateApiKeySecretAdmin";
            echo $url;
         
            echo "<br>"; 
            print_r((string)$filter_Users[0]['_id']). " <--- user id";
            $data = (string)$filter_Users[0]['_id']; 
          
            $key = "vizz@digiesolutions"; 
            $iv = random_bytes(16);   

            // Encrypt the data using AES-256-CBC
            $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);

            // Combine IV and encrypted data for storage
            $encryptedMessage = base64_encode($iv . $encryptedData);

            $payLoadExchangeVarify = [
                'user_id'    =>  (string)$filter_Users[0]['_id']
            ];

            $req_arr = [
                'req_type'     =>  'POST',
                'req_url'      =>  $url,
                'req_params'   =>  $payLoadExchangeVarify,
            ];
            if($Ipname == 'ip7'){
                $resp = dynamicCURLHitForAPIChecking($req_arr, $encryptedMessage);
            }else{
                $resp = dynamicCURLHitForAPICheckingOpen($req_arr);
            }
            
            $respData  = json_decode($resp);
            print_r($respData->success);
         
            if($respData->success == 1 || $respData->api_key == "VALID"){

                $api_key_swap = [
                    'is_api_key_valid'      =>  "yes",
                    'count_invalid_api'     =>  0,
                    'account_block'         =>  'no',
                    // 'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                    'count_api_key_checking_crone'=> 1
                ];
                $db->user_investment_binance->updateOne(['admin_id' => (string)$filter_Users[0]['_id'] ], ['$set' => ['exchange_enabled' => 'yes' ]]); 
                $db->users->updateOne(['_id' => $this->mongo_db->mongoId((string)$filter_Users[0]['_id']) ], ['$set' =>  $api_key_swap]);

                echo "<br>updated Sucessfully";
                
            }elseif($respData->success == 0 || $respData->success == false){
                
                
                $account_block = $filter_Users[0]['count_invalid_api'] + 1;
                $update_array_api = [
                    // 'api_key_valid_checking' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ,
                    'is_api_key_valid'       => "no", 
                    'api_line_number'       => "387", 
                    'count_invalid_api'      => $account_block,
                    'count_api_key_checking_crone' => 1
                ];

                if($filter_Users[0]['count_invalid_api'] == 4 ){

                    $update_array_api['account_block'] = 'yes';
                }
                $db->users->updateOne(['_id' => $this->mongo_db->mongoId((string)$filter_Users[0]['_id']) ], ['$set' =>  $update_array_api]);

                $date = date('Y-m-d H:i:s');
                $date = $this->mongo_db->converToMongodttime($date);
                $db->user_investment_binance->updateOne(['admin_id' => (string)$filter_Users[0]['_id'] ], ['$set' => ['exchange_enabled' => 'block', 'account_blocked_date' =>  $date]]);  
                echo "<br>"; echo " false";  
            }

        }else{
            $db->users->updateOne( ['$set' =>  ['count_api_key_checking_crone' => 0]]);
        }//end if 
        echo "<br>Done";

    }//end function


    //api key validation script checking for kraken
    public function api_key_checking_kraken($user_id = ""){
        $today = date('Y-m-d H:i:s');
        $today_date = $this->mongo_db->converToMongodttime($today);
        $db = $this->mongo_db->customQuery();
        // $olderTime = $this->mongo_db->converToMongodttime(Date('Y-m-d H:i:s',strtotime('-12 hours') ));
        // $or1 =   array( '$or' => array(
        //     array( 'account_block' => array( '$exists' => false ) ) ,
        //     array( 'account_block' => 'no' ) 
        //   ));
        // //  $or2 =   array( '$or' => array(
        // //     array( 'api_key_valid_checking' => array( '$exists' => false ) ) ,
        // //     array( 'api_key_valid_checking' => array('$lte'=>$olderTime) ) 
        // //   ));
        // $or2 =   array( '$or' => array(
        //     array( 'count_api_key_checking_crone' => array( '$exists' => false ) ) ,
        //     array( 'count_api_key_checking_crone' => array('$ne'=>1) ) 
        //   ));
        //   $or3 =   array( '$or' => array(
        //     array( 'count_invalid_api' => array( '$exists' => false ) ) ,
        //     array( 'count_invalid_api' => array('$lte'=>0)) 
        //   )); 
          
        // $query = array('is_api_key_valid'=>array('$ne'=>'yes'),'trading_ip'=>array('$nin'=>array('',null) ),'$and' => array( $or1, $or2, $or3 ) );
        // if(!empty($user_id))
        // {
        //     $query = array('user_id'=>$user_id);
        // }
        // echo "<pre>";
        // print_r($query);
        // $getUsers = [
        //     [
        //         '$match' => 
        //             // ['user_id' => "5c091479fc9aadaac61dd121"],
        //            $query
                
        //     ],
        //     [
        //         '$sort' => ['_id' => -1]
        //     ],
        //     // [
        //     //     '$sort' => ['api_key_valid_checking' => 1]
        //     // ],
        //      [
        //         '$project' => [
        //             'trading_ip'            =>  '$trading_ip',
        //             'user_id'  => '$user_id',
        //             'api_key_secondary'=>'$api_key_secondary',
        //             'api_secret_secondary'=>'$api_secret_secondary',
                    
                    
        //         ]
        //     ],

        //     [
        //         '$limit' => 4
        //     ]
        // ];
       
        // $get_users1     =  $db->kraken_credentials->aggregate($getUsers);
        // $filter_Users  =  iterator_to_array($get_users1);
        // echo "<pre>"; print_r($filter_users); exit;
        $db = $this->mongo_db->customQuery();
        $or1 = [
            '$or' => [
                ['account_block' => ['$exists' => false]],
                ['account_block' => 'no']
            ]
        ];
        
        $or2 = [
            '$or' => [
                ['count_api_key_checking_crone' => ['$exists' => false]],
                ['count_api_key_checking_crone' => ['$ne' => 1]]
            ]
        ];
        
        // $or3 = [
        //     '$or' => [
        //         ['count_invalid_api' => ['$exists' => false]],
        //         ['count_invalid_api' => ['$lte' => 0]]
        //     ]
        // ];
        
        // Base query with common conditions
        $baseQuery = [
            'is_api_key_valid' => ['$ne' => 'yes'],
            'trading_ip' => ['$nin' => ['', null]],
            // 'script_line' => "api_go_line_2511",
        ];
        
        // If user_id is provided, add it to the query
        if (!empty($user_id)) {
            $query = array_merge($baseQuery, ['user_id' => $user_id]);
        } else {
            // Combine OR conditions using $and
            $query = array_merge($baseQuery, ['$and' => [$or1, $or2]]);
        }
        
        $getUsers = [
            [
                '$match' => $query
            ],
            [
                '$sort' => ['_id' => -1]
            ],
            [
                '$project' => [
                    'trading_ip' => '$trading_ip',
                    'user_id' => '$user_id',
                    'api_key_secondary' => '$api_key_secondary',
                    'api_secret_secondary' => '$api_secret_secondary'
                ]
            ],
            [
                '$limit' => 1
            ]
        ];
        
        $get_users1 = $db->kraken_credentials->aggregate($getUsers);
        $filter_Users = iterator_to_array($get_users1);
        
        // echo "<pre>";
        // print_r($filter_Users); exit;
        if(count($filter_Users) > 0){
            $total_users = count($filter_Users);
            for($u=0;$u<$total_users;$u++)
            {
                    $letUserId = $filter_Users[$u]['user_id'];
                    // **** commented these below two lines 
                    // $getUserIP = $this->getUserIP($letUserId);
                    // $filter_Users[$u]['trading_ip'] = $getUserIP;
                    echo '<br>tradingIp: '.$filter_Users[$u]['trading_ip'];
                    echo "<br>admin_id : ".(string)$filter_Users[$u]['user_id'];
                    $Ipname = '';
                    if( $filter_Users[$u]['trading_ip'] == '3.227.143.115'){
        
                        $Ipname = 'ip1';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.228.180.22'){
            
                        $Ipname = 'ip2';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.226.226.217'){
            
                        $Ipname = 'ip3';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.228.245.92'){
                        $Ipname = 'ip4';
                    }elseif($filter_Users[$u]['trading_ip'] == '35.153.9.225'){
                        $Ipname = 'ip5';
                    }
                    elseif($filter_Users[$u]['trading_ip'] == '54.157.102.20')
                    {
                         $Ipname = 'ip6';
                    }
                    if($Ipname == '')
                    {
                         $update_array = [
                                  
                                     'empty_trading_ip'=>'yes',
                                    'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
                                ];
                
                                $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => $update_array ]);
                                //$db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'yes','is_api_key_valid'=>'yes' ]]);
                        echo '<br> IP is Empty';
                        continue;
                    }

                    // if($Ipname == 'ip5'){
                    //    $url = "https://ip5-kraken.digiebot.com/api/user/getApiKeySecretAdmin";
                    // }else{
                    //     $url = "https://".$Ipname."-kraken-balance.digiebot.com/getApiKeyAdmin";
                    // }
                    // asked by shahzad on 29-dec-2021 , all urls will be same with exact end point
                    $url = "https://".$Ipname."-kraken.digiebot.com/api/user/getApiKeySecretAdmin";
                    echo "<br>".$url;
                    // check api is valid or not 
                    // echo "<br>admin_id : ".(string)$filter_Users[$u]['user_id'];
                    //  echo "<pre>";
                    //  print_r($filter_users);
                    // if($filter_users[$u]['user_id'] == '60e3e8b8a74ae506845e8543')
                    // {
                    //     echo "<pre>";
                    //     print_r($filter_users);exit;
                    // }
                    $data = (string)$filter_Users[$u]['user_id']; // Shahzad bhai asked to send user_id in this data
                    $key = "vizz@digiesolutions"; // Shahzad bhai asked to add this as a key
                    $iv = random_bytes(16);   // Generate a random initialization vector

                    // Encrypt the data using AES-256-CBC
                    $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);

                    // Combine IV and encrypted data for storage
                    $encryptedMessage = base64_encode($iv . $encryptedData);

                    $payLoadExchangeVarify = [
                      'user_id'    =>  (string)$filter_Users[$u]['user_id']
                    ];
                    $req_arr = [
                        'req_type'     =>  'POST',
                        'req_url'      =>  $url,
                        'req_params'   =>  $payLoadExchangeVarify,
                    ];
                    $resp      = dynamicCURLHitForAPICheckingKraken($req_arr);
                    $respData  = json_decode($resp);
                    echo "<pre>";print_r($resp);
                    // die('testing');
                    if($respData->success == 1 || $respData->success == true ){
                        $update_array = [
                            'is_api_key_valid'      =>  "yes",
                            'count_invalid_api'     =>  0,
                            'key_used'              =>  '',
                             'empty_trading_ip'=>'',
                            'account_block'         =>  'no',
                            // 'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                            'count_api_key_checking_crone' => 1,
                            'api_key_checking_crone_date' => $today_date
                        ];
                        $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => $update_array ]);
                        $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'yes','is_api_key_valid'=>'yes', '']]); 
                       
                    }
                    else
                        {
                             $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'no','is_api_key_valid'=>'no' ]]); 
                            $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' =>  [ 'empty_trading_ip'=>'' ,'is_api_key_valid' => "no",'api_line_number'=>'543','bothKeysChecked'=>0, 'count_invalid_api'  => 1, 'count_api_key_checking_crone' => 1, 'api_key_checking_crone_date' => $today_date]]);     
                          
                        }
                        /* ** Important When We Will check secondary API keys we will uncomment below and comment out the Else Check Above.. */


                        // Do ASk Shahzad when to Include this code of checking Seconday Api Keys, asked by shahzad to comment out that for a while, 29-dec-2021 ..
                    // elseif($respData->success == 0 || $respData->success == false  ){
                    //     if(!empty($filter_Users[$u]['api_key_secondary']) && !empty($filter_Users[$u]['api_secret_secondary']))
                    //     {   

                    //         if($Ipname == 'ip5'){
                    //             $url = "https://ip5-kraken.digiebot.com/api/user/getApiKeySecretAdmin2";
                    //         }else{
                    //           $url = "https://".$Ipname."-kraken-balance.digiebot.com/getSecondApiKeyAdmin";  
                    //         }
                    //         $req_arr1 = [
                    //             'req_type'     =>  'POST',
                    //             'req_url'      =>  $url,
                    //             'req_params'   =>  $payLoadExchangeVarify,
                    //         ];
                    //         $resp      = dynamicCURLHitForAPIChecking($req_arr1);
                    //         $respData  = json_decode($resp);
                
                    //         echo "<pre>";print_r($respData);
                
                    //         // die('testing');
                    //         if($respData->success == 1 || $respData->success == true ){
                
                    //             $update_array = [
                    //                 'is_api_key_valid'      =>  "yes",
                    //                 'count_invalid_api'     =>  0,
                    //                 'key_used'              =>  'secondry',
                    //                 'account_block'         =>  'no',
                    //                  'empty_trading_ip'=>'',
                    //                 'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
                    //             ];
                
                    //             $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => $update_array ]);
                    //             $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'yes','is_api_key_valid'=>'yes' ]]); 
                    //         }
                    //         else
                    //         {
                    //             $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'no','is_api_key_valid'=>'no' ]]); 
                    //             $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' =>  [ 'empty_trading_ip'=>'','api_key_valid_checking' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ,'is_api_key_valid' => "no",'bothKeysChecked'=>1, 'count_invalid_api'  => 1]]);
                    //         } 
                    //     }
                    //     else
                    //     {
                    //          $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'no','is_api_key_valid'=>'no' ]]); 
                    //         $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' =>  [ 'empty_trading_ip'=>'','api_key_valid_checking' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ,'is_api_key_valid' => "no",'bothKeysChecked'=>0, 'count_invalid_api'  => 1]]);     
                    //     }
                       
                    // }
                    echo "<br>SuccessFully Updated";
                }
        }else{
            $or1 = [
                '$or' => [
                    ['account_block' => ['$exists' => false]],
                    ['account_block' => 'no']
                ]
            ];
            
            $query = [
                'trading_ip' => ['$nin' => [null, '']],
                '$and' => [$or1]
            ];
            
            $updateResult = $db->kraken_credentials->updateMany(
                $query,   
                ['$set' => ['count_api_key_checking_crone' => 0]]   
            );
            
            $updatedCount = $updateResult->getModifiedCount();
            echo "<br>";
            echo "Updated $updatedCount documents.";

        }
    
        echo "<br>Done";
    }//end function

      //api key validation script checking for kraken
      public function api_key_checking_dg($user_id = ""){
        $today = date('Y-m-d H:i:s');
        $today_date = $this->mongo_db->converToMongodttime($today);
        $db = $this->mongo_db->customQuery();
 
        $or1 = [
            '$or' => [
                ['account_block' => ['$exists' => false]],
                ['account_block' => 'no']
            ]
        ];
        
        $or2 = [
            '$or' => [
                ['count_api_key_checking_crone' => ['$exists' => false]],
                ['count_api_key_checking_crone' => ['$ne' => 1]]
            ]
        ];
        $baseQuery = [
            'is_api_key_valid' => ['$ne' => 'yes'],
            'trading_ip' => ['$nin' => ['', null]],
        ];
        if (!empty($user_id)) {
            $query = array_merge($baseQuery, ['user_id' => $user_id]);
        } else {
            $query = array_merge($baseQuery, ['$and' => [$or1, $or2]]);
        }
        
        $getUsers = [
            [
                '$match' => $query
            ],
            [
                '$sort' => ['_id' => -1]
            ],
            [
                '$project' => [
                    'trading_ip' => '$trading_ip',
                    'user_id' => '$user_id',
                    'api_key_secondary' => '$api_key_secondary',
                    'api_secret_secondary' => '$api_secret_secondary'
                ]
            ],
            [
                '$limit' => 1
            ]
        ];
        
        $get_users1 = $db->dg_credentials->aggregate($getUsers);
        $filter_Users = iterator_to_array($get_users1);
        // echo '<pre>'; print_r($filter_Users); exit;
        if(count($filter_Users) > 0){
            $total_users = count($filter_Users);
            for($u=0;$u<$total_users;$u++)
            {
                    $letUserId = $filter_Users[$u]['user_id'];
                    // echo '<br>tradingIp: '.$filter_Users[$u]['trading_ip'];
                    echo "<br>admin_id : ".(string)$filter_Users[$u]['user_id'];
                
                    $url = "http://44.206.125.3:3003/api/user/getApiKeySecretAdmin"; //live URL 44.206.125.3
                    
                    $data = (string)$filter_Users[$u]['user_id']; // Shahzad bhai asked to send user_id in this data
                    $key = "vizz@digiesolutions"; // Shahzad bhai asked to add this as a key
                    $iv = random_bytes(16);   // Generate a random initialization vector

                    // Encrypt the data using AES-256-CBC
                    $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);

                    // Combine IV and encrypted data for storage
                    $encryptedMessage = base64_encode($iv . $encryptedData);

                    $modifiedString = generateModifiedString($this->originalString);
                    $encryptedString = encryptCode($modifiedString, $this->keyCode);

                    $payLoadExchangeVarify = [
                      'user_id'    =>  (string)$filter_Users[$u]['user_id'],
                      'exchange'   =>  'dg',
                      'token'      =>   base64_encode($encryptedString)
                    ];
                    $req_arr = [
                        'req_type'     =>  'POST',
                        'req_url'      =>  $url,
                        'req_params'   =>  $payLoadExchangeVarify,
                    ];
                    $resp      = dynamicCURLHitForAPICheckingDg($req_arr);
                    $respData  = json_decode($resp);
                    echo "<pre>";print_r($resp);
                    // die('testing');
                    if($respData->success == 1 || $respData->success == true ){
                        $update_array = [
                            'is_api_key_valid'      =>  "yes",
                            'count_invalid_api'     =>  0,
                            'key_used'              =>  '',
                             'empty_trading_ip'=>'',
                            'account_block'         =>  'no',
                            // 'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                            'count_api_key_checking_crone' => 1,
                            'api_key_checking_crone_date' => $today_date
                        ];
                        $db->dg_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => $update_array ]);
                        // $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'yes','is_api_key_valid'=>'yes', '']]); 
                       
                    }
                    else
                        {
                            //  $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'no','is_api_key_valid'=>'no' ]]); 
                            $db->dg_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' =>  [ 'empty_trading_ip'=>'' ,'is_api_key_valid' => "no",'api_line_number'=>'543','bothKeysChecked'=>0, 'count_invalid_api'  => 1, 'count_api_key_checking_crone' => 1, 'api_key_checking_crone_date' => $today_date]]);     
                          
                        }
                    echo "<br>SuccessFully Updated";
                }
        }else{
            $or1 = [
                '$or' => [
                    ['account_block' => ['$exists' => false]],
                    ['account_block' => 'no']
                ]
            ];
            
            $query = [
                'trading_ip' => ['$nin' => [null, '']],
                '$and' => [$or1]
            ];
            
            $updateResult = $db->dg_credentials->updateMany(
                $query,   
                ['$set' => ['count_api_key_checking_crone' => 0]]   
            );
            
            $updatedCount = $updateResult->getModifiedCount();
            echo "<br>";
            echo "Updated $updatedCount documents.";

        }
    
        echo "<br>Done";
    }//end function


    // invalid api key rechecking after 6 hours
    public function invalid_api_rechecking_kraken(){
        $db = $this->mongo_db->customQuery();
        $olderTime = $this->mongo_db->converToMongodttime(Date('Y-m-d H:i:s',strtotime('-12 hours') ));
        $or1 =   array( '$or' => array(
            array( 'account_block' => array( '$exists' => false ) ) ,
            array( 'account_block' => 'no' ) 
          ));
         $or2 =   array( '$or' => array(
            array( 'count_api_key_checking_crone' => array( '$exists' => false ) ) ,
            array( 'count_api_key_checking_crone' => array('$ne'=>1) ) 
          ));
     
        $query = array('count_invalid_api'=>array('$gte'=>1,'$lte'=>4),'$or' => array( $or1, $or2) );
        $getUsers = [
            [
                '$match' => 
                    // 'user_id' => "5c091479fc9aadaac61dd121",
                   $query
                
            ],
            [
                '$sort' => ['_id' => -1]
            ],
             [
                '$project' => [
                    'trading_ip'            =>  '$trading_ip',
                    'user_id'  => '$user_id',
                     'api_key_secondary'=>'$api_key_secondary',
                    'api_secret_secondary'=>'$api_secret_secondary',
                    
                ]
            ],

            [
                '$limit' => 2
            ]
        ];
       
        $get_users1     =  $db->kraken_credentials->aggregate($getUsers);
        $filter_Users  =  iterator_to_array($get_users1);
        if(count($filter_Users) > 0){
            $total_users = count($filter_Users);
            for($u=0;$u<$total_users;$u++)
            {
                   $letUserId = $filter_Users[$u]['user_id'];
                   $getUserIP = $this->getUserIP($letUserId);
                    $filter_Users[$u]['trading_ip'] = $getUserIP;
                    echo '<br>tradingIp: '.$filter_Users[$u]['trading_ip'];
                    echo "<br>admin_id : ".(string)$filter_Users[$u]['user_id'];
                    $Ipname = '';
                    if( $filter_Users[$u]['trading_ip'] == '3.227.143.115'){
        
                        $Ipname = 'ip1';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.228.180.22'){
            
                        $Ipname = 'ip2';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.226.226.217'){
            
                        $Ipname = 'ip3';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.228.245.92'){
            
                        $Ipname = 'ip4';
                    }
                    elseif($filter_Users[$u]['trading_ip'] == '54.157.102.20')
                    {
                         $Ipname = 'ip6';
                    }elseif($filter_Users[$u]['trading_ip'] == '18.170.235.202')
                    {
                         $Ipname = 'ip7';
                    }
                    if($Ipname == '')
                    {
                        echo '<br> IP is Empty';
                        continue;
                    }
                    //$url = "https://".$Ipname."-kraken-balance.digiebot.com/getApiKeyAdmin";
                    // By Shahzad on 29-dec-2021 for all ips use this below url
                      // asked by shahzad on 29-dec-2021 , all urls will be same with exact end point
                    $url = "https://".$Ipname."-kraken.digiebot.com/api/user/getApiKeySecretAdmin";
                    echo "<br>".$url;
        
                    // check api is valid or not 
                    echo "<br>admin_id : ".(string)$filter_Users[$u]['user_id'];
                    $data = (string)$filter_Users[$u]['user_id']; // Shahzad bhai asked to send user_id in this data
                    $key = "vizz@digiesolutions"; // Shahzad bhai asked to add this as a key
                    $iv = random_bytes(16);   // Generate a random initialization vector

                    // Encrypt the data using AES-256-CBC
                    $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);

                    // Combine IV and encrypted data for storage
                    $encryptedMessage = base64_encode($iv . $encryptedData);


                    $payLoadExchangeVarify = [
                      'user_id'    =>  (string)$filter_Users[$u]['user_id']
                    ];
        
                    $req_arr = [
                        'req_type'     =>  'POST',
                        'req_url'      =>  $url,
                        'req_params'   =>  $payLoadExchangeVarify,
                    ];
                    if($Ipname == 'ip7'){
                        $resp = dynamicCURLHitForAPIChecking($req_arr, $encryptedMessage);
                    }else{
                        $resp = dynamicCURLHitForAPICheckingOpen($req_arr);
                    }
                    $respData  = json_decode($resp);
        
                    echo "<pre>";print_r($respData);
        
                    // die('testing');
                    if($respData->success == 1 || $respData->success == true ){
                          $api_key_swap = [
                            'is_api_key_valid'      =>  "yes",
                            'count_invalid_api'     =>  0,
                            'key_used'              =>  '',
                            'account_block'         =>  'no',
                            'count_api_key_checking_crone'=> 1
                            // 'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
                                ];
                        $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'yes' ]]); 
                        $db->kraken_credentials->updateOne(['user_id' =>(string)$filter_Users[$u]['user_id'] ], ['$set' =>  $api_key_swap]);
                    }
                    else
                        {
                            $account_block = $filter_Users[$u]['count_invalid_api'] + 1;
                            $update_array_api = [
                                // 'api_key_valid_checking' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ,
                                'count_api_key_checking_crone'=> 1,
                                'is_api_key_valid'       => "no", 
                                'api_line_number'       => "778", 
                                'count_invalid_api'      => $account_block
                            ];

                            if($filter_Users[$u]['count_invalid_api'] == 4 ){

                                $update_array_api['account_block'] = 'yes';
                            }
                            $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' =>  $update_array_api]);
                            
                            $date = date('Y-m-d H:i:s');
                            $date = $this->mongo_db->converToMongodttime($date);
                            $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'block' , 'account_blocked_date' =>  $date]]);      
                        }

                        /* ** Important When We Will check secondary API keys we will uncomment below and comment out the Else Check Above.. */


                    // Do ASk Shahzad when to Include this code of checking Seconday Api Keys, asked by shahzad to comment out that for a while, 29-dec-2021 ..



                    // elseif($respData->success == 0 || $respData->success == false  ){
                    //     if(!empty($filter_Users[$u]['api_key_secondary']) && !empty($filter_Users[$u]['api_secret_secondary']))
                    //     {
                    //           $url = "https://".$Ipname."-kraken-balance.digiebot.com/getSecondApiKeyAdmin";  
                    //         $req_arr1 = [
                    //             'req_type'     =>  'POST',
                    //             'req_url'      =>  $url,
                    //             'req_params'   =>  $payLoadExchangeVarify,
                    //         ];
                    //         $resp      = dynamicCURLHitForAPIChecking($req_arr1);
                    //         $respData  = json_decode($resp);
                
                    //         echo "<pre>";print_r($respData);
                
                    //         // die('testing');
                    //         if($respData->success == 1 || $respData->success == true ){
                
                    //             $update_array = [
                    //                 'is_api_key_valid'      =>  "yes",
                    //                 'count_invalid_api'     =>  0,
                    //                 'useSecondaryKey'       => 'yes',  // To Use Second API KEY
                    //                 'account_block'         =>  'no',
                    //                 'key_used'              =>  'secondry',
                    //                 'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
                    //             ];
                
                    //             $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => $update_array ]);
                    //             $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'yes','is_api_key_valid'=>'yes' ]]); 
                    //         }
                    //         else
                    //         {
                    //              $account_block = $filter_Users[$u]['count_invalid_api'] + 1;
                    //             $update_array_api = [
                    //             'api_key_valid_checking' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ,
                    //             'is_api_key_valid'       => "no", 
                    //             'count_invalid_api'      => $account_block
                    //         ];

                    //         if($filter_Users[$u]['count_invalid_api'] == 4 ){

                    //             $update_array_api['account_block'] = 'yes';
                    //         }
                    //         $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' =>  $update_array_api]);
                    //         $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'block' ]]);
                    //         } 
                    //     }
                    //     else
                    //     {
                    //           $account_block = $filter_Users[$u]['count_invalid_api'] + 1;
                    //         $update_array_api = [
                    //             'api_key_valid_checking' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ,
                    //             'is_api_key_valid'       => "no", 
                    //             'count_invalid_api'      => $account_block
                    //         ];

                    //         if($filter_Users[$u]['count_invalid_api'] == 4 ){

                    //             $update_array_api['account_block'] = 'yes';
                    //         }
                    //         $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' =>  $update_array_api]);
                    //         $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'block' ]]);      
                    //     }
                       
                    // }
                    echo "<br>SuccessFully Updated";
                }
            }else{
                $db->kraken_credentials->updateOne(['$set' =>  ['count_api_key_checking_crone' => 0]]);
            }

    }//end function


    public function api_key_checking_test(){
        //echo "404";exit;
        $db = $this->mongo_db->customQuery();

        // https://app.digiebot.com/crone/api_key_checking_test?page=1
        
        $pageNumber  = (float)$this->input->get('page');
        echo "<br>pageNumber: ".$pageNumber;
    
        $page = 0;
        if($pageNumber !=0){
            $page = (float)( ($pageNumber - 1) * 50);
        }

        echo '<br>Skip :'.$page;


        $lookup = [
            [
                '$match' => [
                    //'application_mode' => ['$in' => ['both', 'live']],
                    'username'=> ['$eq'=>"sheraztest"]
                ]
            ],
            [
                '$project' =>[
                    '_id'                   =>  '$_id',
                    'username'              =>  '$username',
                    'trading_ip'            =>  '$trading_ip',
                ]
            ],
            [
                '$sort' => ['api_key_valid_checking' => 1]
            ],
            
            [
                '$skip' => $page 
            ],
            [
                '$limit' => 50
            ],
        ];
        
        $get_users     =  $db->users->aggregate($lookup);
        $filter_Users  =  iterator_to_array($get_users);
        echo "<br>Count : ".count($filter_Users);

        if(count($filter_Users) > 0){
        //if(1 == 1){
            foreach ($filter_Users as $value){

                sleep(1);
    
                if( $value['trading_ip'] == '3.227.143.115'){
    
                    $Ipname = 'ip1';
                }elseif($value['trading_ip'] == '3.228.180.22'){
        
                    $Ipname = 'ip2';
                }elseif($value['trading_ip'] == '3.226.226.217'){
        
                    $Ipname = 'ip3';
                }elseif($value['trading_ip'] == '3.228.245.92'){
        
                    $Ipname = 'ip4';
                }else{
        
                    exit;
                }
    
                $url = "https://ip1.digiebot.com/apiKeySecret/validateApiKeySecretAdmin";
                // check api is valid or not 
                 echo "<br>".$url;
                 echo "<br>".(string)$value['_id'];
                $payLoadExchangeVarify = [
                  'user_id'    =>  (string)$value['_id']
                ];
    
                $req_arr = [
                    'req_type'     =>  'POST',
                    'req_url'      =>  $url,
                    'req_params'   =>  $payLoadExchangeVarify,
                ];
                $resp      = dynamicCURLHitForAPIChecking($req_arr);
                $respData  = json_decode($resp);
                

                 var_dump($respData);
                echo '<br>tradingIp====>>>>>>>'.$value['trading_ip']. '  response =====>>>>>>'. $respData->success ; exit;

                if($respData->success == 1 || $respData->api_key == "VALID"){

                    $update_array = [
                        'is_api_key_valid'      =>  "yes",
                        'count_invalid_api'     =>  0,
                        'account_block'         =>  'no',
                        'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
                    ];
    
                     // for again request to binance wait for 3 sec and then send second call
                    $db->users->updateOne(['_id' => $this->mongo_db->mongoId((string)$value['_id']) ], ['$set' => $update_array ]);
                    $db->user_investment_binance->updateOne(['admin_id' => (string)$value['_id'] ], ['$set' => ['exchange_enabled' => 'yes' ]]); 
                }elseif($respData->success == 0 || $respData->success == false){
    
                    $db->user_investment_binance->updateOne(['admin_id' => (string)$value['_id'] ], ['$set' => ['exchange_enabled' => 'no' ]]); 
                    $db->users->updateOne(['_id' => $this->mongo_db->mongoId((string)$value['_id']) ], ['$set' =>  ['api_key_valid_checking' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ,'is_api_key_valid' => "no",'api_line_number'=>'973', 'count_invalid_api'  => 1]]);
                }

            }//end loop

        }//end if
        echo "<br>Done";

    }//end function

    public function api_key_checking_kraken_test(){
        echo "404";exit;
        $db = $this->mongo_db->customQuery();
        $olderTime = $this->mongo_db->converToMongodttime(Date('Y-m-d H:i:s',strtotime('-12 hours') ));

        $getUsers = [
            [
                '$match' => [

                    'is_api_key_valid' => 'yes',

                    '$or' => [

                        ['account_block'  => ['$exists' => false]],
                        ['account_block'  => 'no'],
                    ],

                    '$or' => [
                        ['count_invalid_api' => ['$exists' => false]],
                        ['count_invalid_api' => ['$lte' => 0]]
                    ],
                ]
            ],
            [
                '$project' => [
                    'user_id'  => 1,
                    'username' => '$username'
                ]
            ],


            [
                '$lookup' => [
                  "from" => "users",
                  "let"=> [
                    "user_id" => ['$toObjectId' =>  '$user_id']
                  ],
                    "pipeline"=> [
                        [
                            '$match' => [

                                '$expr' => [
                                    '$eq'=> [
                                        '$_id',
                                        '$$user_id'
                                    ]
                                ],

                                '$and' => [
                                    [
                                        'trading_ip' => ['$exists' => true],
                                    ],
                                    [
                                        'trading_ip' => ['$nin' => ['', null] ],
                                    ],
                                ],
                            
                            ],
                        ],

                        [
                            '$project' => [
                                'trading_ip' => '$trading_ip'
                            ]
                        ],
                    
                    ],
                    "as" => "ip"
                ]
            ],

            [
                '$sort' => ['api_key_valid_checking' => 1]
            ],
            [
                '$limit' => 50
            ]
        ];

        $get_users1     =  $db->kraken_credentials->aggregate($getUsers);
        $filter_Users  =  iterator_to_array($get_users1);
        
        echo "<br>Get Count: ".count($filter_Users);
        // echo "<br>Trading ip ===================>>>>>>>>>>>>>..",$filter_Users[0]['ip'][0]['trading_ip'];

        if(count($filter_Users) > 0 ){
        
            foreach ($filter_Users as $value){

                echo "<br> lopp";
                if(!empty($value['ip'][0]['trading_ip']) ){
                    echo '<br>tradingIp: '.$value['ip'][0]['trading_ip'];
        
                    if( $value['ip'][0]['trading_ip'] == '3.227.143.115'){
        
                        $Ipname = 'ip1';
                    }elseif($value['ip'][0]['trading_ip'] == '3.228.180.22'){
            
                        $Ipname = 'ip2';
                    }elseif($value['ip'][0]['trading_ip'] == '3.226.226.217'){
            
                        $Ipname = 'ip3';
                    }elseif($value['ip'][0]['trading_ip'] == '3.228.245.92'){
            
                        $Ipname = 'ip4';
                    }else{
            
                        exit;
                    }
                    $url = "https://".$Ipname."-kraken-balance.digiebot.com/getApiKeyAdmin";
                    echo "<br>".$url;
        
                    // check api is valid or not 
                    echo "<br>admin_id : ".(string)$value['user_id'];
                    $payLoadExchangeVarify = [
                    'user_id'    =>  (string)$value['user_id']
                    ];
        
                    $req_arr = [
                        'req_type'     =>  'POST',
                        'req_url'      =>  $url,
                        'req_params'   =>  $payLoadExchangeVarify,
                    ];
                    sleep(2);
                    $resp      = dynamicCURLHitForAPIChecking($req_arr);
                    $respData  = json_decode($resp);
        
                    echo "<pre>";print_r($respData);
        
                    // die('testing');
                    echo "<br>   ".$respData->success;
                    if($respData->success == 1 || $respData->success == true ){
        
                        $update_array = [
                            'is_api_key_valid'      =>  "yes",
                            'count_invalid_api'     =>  0,
                            'account_block'         =>  'no',
                            'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
                        ];
        
                        $db->kraken_credentials->updateOne(['user_id' => (string)$value['user_id'] ], ['$set' => $update_array ]);
                        $db->user_investment_kraken->updateOne(['admin_id' => (string)$value['user_id'] ], ['$set' => ['exchange_enabled' => 'yes' ]]); 
                    }elseif($respData->success == 0 || $respData->success == false  ){
        
                        $db->user_investment_kraken->updateOne(['admin_id' => (string)$value['user_id'] ], ['$set' => ['exchange_enabled' => 'no' ]]); 
                        $db->kraken_credentials->updateOne(['user_id' => (string)$value['user_id'] ], ['$set' =>  ['api_key_valid_checking' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ,'is_api_key_valid' => "no",'api_line_number'=>'1128', 'count_invalid_api'  => 1]]);
                    }
                    echo "<br>SuccessFully Updated";
                }//end if
            }

        }//end if
        echo "<br>Done";
    }
    public function apiKeyCheckingForBinanceValidUser(){

        $db = $this->mongo_db->customQuery();
        $olderTime = $this->mongo_db->converToMongodttime(Date('Y-m-d H:i:s',strtotime('-2 days') ));

        if( !empty($this->input->get()) ){

            $lookup = [
                [
                    '$match' => [
                        '$and' => [
                            [
                                'trading_ip' => ['$exists' => true],
                            ],
                            [
                                'trading_ip' => ['$nin' => ['', null] ],
                            ],
                        ],
                       'username'  => $this->input->get('userName'),
                    ]
                ],
                [
                    '$project' =>[
                        '_id'       =>  '$_id',
                        'username'  =>  '$username',
                        'trading_ip'=>  '$trading_ip',
                    ]
                ],
                [
                    '$sort' => ['api_key_valid_checking' => 1]
                ],
                [
                    '$limit' => 4
                ]

            ];
        }else{
          
        $query = array('is_api_key_valid'=>array('$eq'=>'yes'),'application_mode'=>array('$in'=>array('both','live')),'trading_ip'=>array('$nin'=>array(null,'')),'$or' => 
                array(
                array( 'api_key_valid_checking' => array( '$exists' => false ) ) ,
                array( 'api_key_valid_checking' => array('$lte'=>$olderTime) ) 
                ) 
            );    
            $lookup = [
                [
                    '$match' => $query
                    // [

                    //     '$and' => [
                    //         [
                    //             'trading_ip' => ['$exists' => true],
                    //         ],
                    //         [
                    //             'trading_ip' => ['$nin' => ['', null] ],
                    //         ],
                            
                    //     ],
                    //     'application_mode' => ['$in' => ['both', 'live']],
                        
                    //     '$or' => [

                    //         ['account_block'  => ['$exists' => false]],
                    //         ['account_block'  => 'no'],
                    //     ],

                    //     '$or'  => [
                    //         ['api_key_valid_checking' => ['$exists' => false]],
                    //         ['api_key_valid_checking' => ['$lte' => $olderTime]]
                    //     ],

                    //     '$or' => [

                    //         ['count_invalid_api' => ['$exists' => false]],
                    //         ['count_invalid_api' => ['$lte' => 0]]
                    //     ],
                    // ]
                ],
                [
                    '$sort' => ['api_key_valid_checking' => 1]
                ],
                [
                    '$project' =>[
                        '_id'                   =>  '$_id',
                        'username'              =>  '$username',
                        'trading_ip'            =>  '$trading_ip',
                    ]
                ],
                
                [
                    '$limit' => 4
                ]

            ];
        }


        // echo json_encode($lookup);

        $get_users     =  $db->users->aggregate($lookup);
        $filter_Users  =  iterator_to_array($get_users);
        echo "<br>Count : ".count($filter_Users);
        if(count($filter_Users) > 0){
            $total_users = count($filter_Users);
            for($u=0;$u<$total_users;$u++)
            {
               echo '<br>tradingIp: '.$filter_Users[$u]['trading_ip'];
               echo "<br> USERID ".(string)$filter_Users[$u]['_id'];
                    $Ipname = '';    
                    if( $filter_Users[$u]['trading_ip'] == '3.227.143.115'){
        
                        $Ipname = 'ip1';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.228.180.22'){
            
                        $Ipname = 'ip2';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.226.226.217'){
            
                        $Ipname = 'ip3';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.228.245.92'){
            
                        $Ipname = 'ip4';
                    }elseif($filter_Users[$u]['trading_ip'] == '54.157.102.20')
                    {
                         $Ipname = 'ip6';
                    }
                    if(empty($Ipname))
                    {
                        $update_array = [
                            'empty_trading_ip'=>'yes',
                            'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
                        ];
        
                       $db->users->updateOne(['_id' => $this->mongo_db->mongoId((string)$filter_Users[$u]['_id']) ], ['$set' => $update_array ]);

                        echo 'IP is Empty';
                        continue;
                    }
                    $url = "https://".$Ipname.".digiebot.com/apiKeySecret/validateApiKeySecretAdmin";
        
                    // check api is valid or not 
                    
                    $payLoadExchangeVarify = [
                      'user_id'    =>  (string)$filter_Users[$u]['_id']
                    ];
        
                    $req_arr = [
                        'req_type'     =>  'POST',
                        'req_url'      =>  $url,
                        'req_params'   =>  $payLoadExchangeVarify,
                    ];
                    $resp      = dynamicCURLHitForAPIChecking($req_arr);
                    $respData  = json_decode($resp);
                    if($respData->success == 1 || $respData->api_key == "VALID"){
        
                        $update_array = [
                            'is_api_key_valid'      =>  "yes",
                            'count_invalid_api'     =>  0,
                            'empty_trading_ip'=>'',
                            'account_block'         =>  'no',
                            'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
                        ];
        
                         // for again request to binance wait for 3 sec and then send second call
                        $db->users->updateOne(['_id' => $this->mongo_db->mongoId((string)$filter_Users[$u]['_id']) ], ['$set' => $update_array ]);
                        $db->user_investment_binance->updateOne(['admin_id' => (string)$filter_Users[$u]['_id'] ], ['$set' => ['exchange_enabled' => 'yes','is_api_key_valid'=>'yes' ]]); 
                    }elseif($respData->success == 0 || $respData->success == false){
        
                        $db->user_investment_binance->updateOne(['admin_id' => (string)$filter_Users[$u]['_id'] ], ['$set' => ['exchange_enabled' => 'no','is_api_key_valid'=>'no' ]]); 
                        $db->users->updateOne(['_id' => $this->mongo_db->mongoId((string)$filter_Users[$u]['_id']) ], ['$set' =>  ['api_key_valid_checking' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ,'is_api_key_valid' => "no",'api_line_number'=>'1306', 'empty_trading_ip'=>'', 'count_invalid_api'  => 1]]);
                    }
                    echo "<br>SuccessFully Updated";  
            }
           
    }
        // if(count($filter_Users) > 0){
        //     echo '<br>tradingIp: '.$filter_Users[0]['trading_ip'];

        //     if( $filter_Users[0]['trading_ip'] == '3.227.143.115'){

        //         $Ipname = 'ip1';
        //     }elseif($filter_Users[0]['trading_ip'] == '3.228.180.22'){
    
        //         $Ipname = 'ip2';
        //     }elseif($filter_Users[0]['trading_ip'] == '3.226.226.217'){
    
        //         $Ipname = 'ip3';
        //     }elseif($filter_Users[0]['trading_ip'] == '3.228.245.92'){
    
        //         $Ipname = 'ip4';
        //     }else{
    
        //         exit;
        //     }

        //     $url = "https://".$Ipname.".digiebot.com/apiKeySecret/validateApiKeySecretAdmin";

        //     // check api is valid or not 
        //     echo "<br>".(string)$filter_Users[0]['_id'];
        //     $payLoadExchangeVarify = [
        //       'user_id'    =>  (string)$filter_Users[0]['_id']
        //     ];

        //     $req_arr = [
        //         'req_type'     =>  'POST',
        //         'req_url'      =>  $url,
        //         'req_params'   =>  $payLoadExchangeVarify,
        //     ];
        //     $resp      = dynamicCURLHit($req_arr);
        //     $respData  = json_decode($resp);

        //     if($respData->success == 1 || $respData->api_key == "VALID"){

        //         $update_array = [
        //             'is_api_key_valid'      =>  "yes",
        //             'count_invalid_api'     =>  0,
        //             'account_block'         =>  'no',
        //             'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
        //         ];

        //          // for again request to binance wait for 3 sec and then send second call
        //         $db->users->updateOne(['_id' => $this->mongo_db->mongoId((string)$filter_Users[0]['_id']) ], ['$set' => $update_array ]);
        //         $db->user_investment_binance->updateOne(['admin_id' => (string)$filter_Users[0]['_id'] ], ['$set' => ['exchange_enabled' => 'yes' ]]); 
        //     }elseif($respData->success == 0 || $respData->success == false){

        //         $db->user_investment_binance->updateOne(['admin_id' => (string)$filter_Users[0]['_id'] ], ['$set' => ['exchange_enabled' => 'no' ]]); 
        //         $db->users->updateOne(['_id' => $this->mongo_db->mongoId((string)$filter_Users[0]['_id']) ], ['$set' =>  ['api_key_valid_checking' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ,'is_api_key_valid' => "no", 'count_invalid_api'  => 1]]);
        //     }
        //     echo "<br>SuccessFully Updated";
        // }//end if
        echo "<br>Done";

    }//end function
    //api key validation script checking for kraken valid users
public function apiKeyCheckingForKrakenValidUser($user_id = ""){

        $db = $this->mongo_db->customQuery();
        $olderTime = $this->mongo_db->converToMongodttime(Date('Y-m-d H:i:s',strtotime('-2 days') ));
        $query = array('is_api_key_valid'=>array('$eq'=>'yes'),'trading_ip'=>array('$nin'=>array('',null)),'$or' => 
            array(
            array( 'api_key_valid_checking' => array( '$exists' => false ) ) ,
            array( 'api_key_valid_checking' => array('$lte'=>$olderTime) ) ) );
        if(!empty($user_id))
        {
            $query = array('user_id'=>$user_id);
        }
        $getUsers = [
            [
                '$match' => 
                    // 'user_id' => "5c091479fc9aadaac61dd121",
                   $query
                
            ],
            [
                '$sort' => ['api_key_valid_checking' => 1]
            ],
             [
                '$project' => [
                    'trading_ip'            =>  '$trading_ip',
                    'user_id'  => '$user_id',
                    'api_key_secondary'=>'$api_key_secondary',
                    'api_secret_secondary'=>'$api_secret_secondary',
                ]
            ],

            [
                '$limit' => 4
            ]
        ];
       
        $get_users1     =  $db->kraken_credentials->aggregate($getUsers);
        $filter_Users  =  iterator_to_array($get_users1);
        // echo '<pre>'; print_r($query);
        // echo '<pre>'; print_r($filter_Users);exit;
        if(count($filter_Users) > 0){
            $total_users = count($filter_Users);
            for($u=0;$u<$total_users;$u++)
            {
                    echo '<br>tradingIp: '.$filter_Users[$u]['trading_ip'];
                    echo "<br>admin_id : ".(string)$filter_Users[$u]['user_id'];
                    $Ipname = '';
                    if( $filter_Users[$u]['trading_ip'] == '3.227.143.115'){
        
                        $Ipname = 'ip1';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.228.180.22'){
            
                        $Ipname = 'ip2';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.226.226.217'){
            
                        $Ipname = 'ip3';
                    }elseif($filter_Users[$u]['trading_ip'] == '3.228.245.92'){
            
                        $Ipname = 'ip4';
                    }elseif($filter_Users[$u]['trading_ip'] == '35.153.9.225'){
            
                        $Ipname = 'ip5';
                    }
                    elseif($filter_Users[$u]['trading_ip'] == '54.157.102.20')
                    {
                         $Ipname = 'ip6';
                    }
                    if($Ipname == '')
                    {
                         $update_array = [
                                  
                                     'empty_trading_ip'=>'yes',
                                    'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
                                ];
                
                                $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => $update_array ]);
                                //$db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'yes','is_api_key_valid'=>'yes' ]]);
                        echo '<br> IP is Empty';
                        continue;
                    }
                    if($Ipname == 'ip5'){
                       $url = "https://ip5-kraken.digiebot.com/api/user/getApiKeySecretAdmin";
                    }else{
                        $url = "https://".$Ipname."-kraken.digiebot.com/api/user/getApiKeySecretAdmin";
                    }
                    
                    echo "<br>".$url;
        
                    // check api is valid or not 
                    // echo "<br>admin_id : ".(string)$filter_Users[$u]['user_id'];
                    //  echo "<pre>";
                    //  print_r($filter_users);
                    // if($filter_users[$u]['user_id'] == '60e3e8b8a74ae506845e8543')
                    // {
                    //     echo "<pre>";
                    //     print_r($filter_users);exit;
                    // }
                    $payLoadExchangeVarify = [
                      'user_id'    =>  (string)$filter_Users[$u]['user_id']
                    ];
        
                    $req_arr = [
                        'req_type'     =>  'POST',
                        'req_url'      =>  $url,
                        'req_params'   =>  $payLoadExchangeVarify,
                    ];
                    $resp      = dynamicCURLHitForAPIChecking($req_arr);
                    $respData  = json_decode($resp);
        
                    echo "<pre>";print_r($respData);
        
                    // die('testing');
                    if($respData->success == 1 || $respData->success == true ){
        
                        $update_array = [
                            'is_api_key_valid'      =>  "yes",
                            'count_invalid_api'     =>  0,
                            'key_used'              =>  '',
                             'empty_trading_ip'=>'',
                            'account_block'         =>  'no',
                            'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
                        ];
        
                        $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => $update_array ]);
                        $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'yes','is_api_key_valid'=>'yes' ]]); 
                    }elseif($respData->success == 0 || $respData->success == false  ){
                        if(!empty($filter_Users[$u]['api_key_secondary']) && !empty($filter_Users[$u]['api_secret_secondary']))
                        {   

                            if($Ipname == 'ip5'){
                                $url = "https://ip5-kraken.digiebot.com/api/user/getApiKeySecretAdmin2";
                            }else{
                              $url = "https://".$Ipname."-kraken-balance.digiebot.com/getSecondApiKeyAdmin";  
                            }
                            $req_arr1 = [
                                'req_type'     =>  'POST',
                                'req_url'      =>  $url,
                                'req_params'   =>  $payLoadExchangeVarify,
                            ];
                            $resp      = dynamicCURLHitForAPIChecking($req_arr1);
                            $respData  = json_decode($resp);
                
                            echo "<pre>";print_r($respData);
                
                            // die('testing');
                            if($respData->success == 1 || $respData->success == true ){
                
                                $update_array = [
                                    'is_api_key_valid'      =>  "yes",
                                    'count_invalid_api'     =>  0,
                                    'key_used'              =>  'secondry',
                                    'account_block'         =>  'no',
                                     'empty_trading_ip'=>'',
                                    'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
                                ];
                
                                $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => $update_array ]);
                                $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'yes','is_api_key_valid'=>'yes' ]]); 
                            }
                            else
                            {
                                $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'no','is_api_key_valid'=>'no' ]]); 
                                $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' =>  [ 'empty_trading_ip'=>'','api_key_valid_checking' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ,'is_api_key_valid' => "no",'api_line_number'=>'1533','bothKeysChecked'=>1, 'count_invalid_api'  => 1]]);
                            } 
                        }
                        else
                        {
                             $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' => ['exchange_enabled' => 'no','is_api_key_valid'=>'no' ]]); 
                            $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[$u]['user_id'] ], ['$set' =>  [ 'empty_trading_ip'=>'','api_key_valid_checking' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ,'is_api_key_valid' => "no",'api_line_number'=>'1539','bothKeysChecked'=>0, 'count_invalid_api'  => 1]]);     
                        }
                       
                    }
                    echo "<br>SuccessFully Updated";
                }
            }
        
       
        // echo "<br>Trading ip ===================>>>>>>>>>>>>>..",$filter_Users[0]['ip'][0]['trading_ip'];

        // die('testing');
        // if(count($filter_Users) > 0 && !empty($filter_Users[0]['trading_ip']) ){
        //     echo '<br>tradingIp: '.$filter_Users[0]['trading_ip'];
            
        //     if( $filter_Users[0]['trading_ip'] == '3.227.143.115'){

        //         $Ipname = 'ip1';
        //     }elseif($filter_Users[0]['trading_ip'] == '3.228.180.22'){
    
        //         $Ipname = 'ip2';
        //     }elseif($filter_Users[0]['trading_ip'] == '3.226.226.217'){
    
        //         $Ipname = 'ip3';
        //     }elseif($filter_Users[0]['trading_ip'] == '3.228.245.92'){
    
        //         $Ipname = 'ip4';
        //     }else{
    
        //         exit("NO IP FOUND");
        //     }
        //     $url = "https://".$Ipname."-kraken-balance.digiebot.com/getApiKeyAdmin";
        //     echo "<br>".$url;

        //     // check api is valid or not 
        //     echo "<br>admin_id : ".(string)$filter_Users[0]['user_id'];
        //     $payLoadExchangeVarify = [
        //       'user_id'    =>  (string)$filter_Users[0]['user_id']
        //     ];

        //     $req_arr = [
        //         'req_type'     =>  'POST',
        //         'req_url'      =>  $url,
        //         'req_params'   =>  $payLoadExchangeVarify,
        //     ];
        //     $resp      = dynamicCURLHit($req_arr);
        //     $respData  = json_decode($resp);

        //     echo "<pre>";print_r($respData);

        //     // die('testing');
        //     if($respData->success == 1 || $respData->success == true ){

        //         $update_array = [
        //             'is_api_key_valid'      =>  "yes",
        //             'count_invalid_api'     =>  0,
        //             'account_block'         =>  'no',
        //             'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))
        //         ];

        //         $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[0]['user_id'] ], ['$set' => $update_array ]);
        //         $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[0]['user_id'] ], ['$set' => ['exchange_enabled' => 'yes' ]]); 
        //     }elseif($respData->success == 0 || $respData->success == false  ){

        //         $db->user_investment_kraken->updateOne(['admin_id' => (string)$filter_Users[0]['user_id'] ], ['$set' => ['exchange_enabled' => 'no' ]]); 
        //         $db->kraken_credentials->updateOne(['user_id' => (string)$filter_Users[0]['user_id'] ], ['$set' =>  ['api_key_valid_checking' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ,'is_api_key_valid' => "no", 'count_invalid_api'  => 1]]);
        //     }
        //     echo "<br>SuccessFully Updated";
        // }//end if
        echo "<br>Done";
    }//end function

}