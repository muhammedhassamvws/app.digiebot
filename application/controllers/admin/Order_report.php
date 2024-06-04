<?php
/**
 *
 */
class Order_report extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        //load main template
        ini_set("memory_limit", -1);
        ini_set("display_errors", E_ERROR);
        error_reporting(E_ERROR);
        $this->stencil->layout('admin_layout');
        //load required slices
        $this->stencil->slice('admin_header_script');
        $this->stencil->slice('admin_header');
        $this->stencil->slice('admin_left_sidebar');
        $this->stencil->slice('admin_footer_script');
        //if($_SERVER['REMOTE_ADDR'] == '101.50.127.131' ){
        //echo "<pre>";   print_r($responseArr); exit;
        //}

        //load models
        $this->load->model('admin/mod_report');
        $this->load->model('admin/mod_dashboard');
        $this->load->model('admin/mod_coins');
        $this->load->model('admin/mod_login');
        $this->load->model('admin/mod_buy_orders');
        $this->load->model('admin/mod_jwt');

        //helper 
        $this->load->helper('common_helper');
        $this->load->helper('new_common_helper');

        if ($this->session->userdata('user_role') != 1 && $this->session->userdata('admin_id') != '5e3a687ed190b3186c50cf82' && $this->session->userdata('admin_id') != '5d9e02e2b2a7c428587808e2') {
            // redirect(base_url() . 'forbidden');
            redirect(base_url() . 'admin/login');
        }
        // if ($this->session->userdata('special_role') != 1) {
        //     redirect(base_url() . 'forbidden');
        // }

    }

    public function index()
    {
        //Login Check
        $this->mod_login->verify_is_admin_login();

        $input_search_filters = $this->input->post();
        if (!empty($input_search_filters)) {

            $filter_is_empty = true;
            foreach ($input_search_filters as $kee => $filter_key) {
                if (!empty($filter_key)) {
                    if ($kee != 'optradio' && $kee != 'selector') {
                        $filter_is_empty = false;
                    }
                }
            }
            if ($filter_is_empty) {
                $this->reset_filters_report('all');
            }

            $data_arr['filter_order_data'] = $this->input->post();
            $this->session->set_userdata($data_arr);
        }
        $session_data = $this->session->userdata('filter_order_data');
        
        if (isset($session_data)) {

            $collection = "buy_orders";
            
            if ($session_data['filter_by_coin']) {
                if(!empty($session_data['filter_by_coin']) && $session_data['filter_by_coin'][0] !=""){
                $search['symbol']['$in'] = $session_data['filter_by_coin'];
                }
            }
            if ($session_data['filter_by_mode']) {
                $search['application_mode'] = $session_data['filter_by_mode'];
            }
            if ($session_data['filter_by_trigger']) {
                $search['trigger_type'] = $session_data['filter_by_trigger'];
            }
            if ($session_data['filter_by_rule'] != "") {
                $filter_by_rule = $session_data['filter_by_rule'];
                //$search['$or'] = array("buy_rule_number" => $filter_by_rule, "sell_rule_number" => $filter_by_rule);
                $search['$or'] = array(
                    array("buy_rule_number" => intval($filter_by_rule)),
                    array("sell_rule_number" => intval($filter_by_rule)),
                );

            }
            if (!empty($session_data['filter_level'])) {
                $order_level = $session_data['filter_level'];
                $search['order_level']['$in'] = $order_level;
            }
            if(!empty($session_data['tradingIp'])){
                $search['trading_ip'] = (string)$session_data['tradingIp'];
            }
            if (!empty($session_data['filter_by_coinNature'])) {
                if($session_data['filter_by_coinNature'] == 'btc'){
                    $type = 'BTC';
                    //$search['symbol'] = new MongoDB\BSON\Regex(".*{$type}.*", 'i');

                    $search['$and'] = [ 
                              ['symbol'=>new MongoDB\BSON\Regex(".*{$type}.*", 'i')], 
                              ['symbol'=>['$ne'=> 'BTCUSDT']],
                          ];
                }elseif($session_data['filter_by_coinNature'] == 'usdt'){
                    $type = 'USDT';
                    $search['symbol']=new MongoDB\BSON\Regex(".*{$type}.*", 'i');
                }
            }
            if ($session_data['filter_username_exclude'] != "") { // for excluding users from order report by sheraz sept 22 2021.
                $user_names_arr = explode(',',$session_data['filter_username_exclude']);
                $admin_ids = $this->get_admin_ids_excluding($user_names_arr);
                $search['admin_id'] = ['$nin'=>$admin_ids];
            }else{
                $filter_exchange = $session_data['filter_by_exchange'];
                $admin_ids = $this->get_admin_ids_excluding_investment_blocked($filter_exchange);
                //echo '<pre>';print_r($admin_ids);exit;
                $search['admin_id'] = ['$nin'=>$admin_ids];
            } // end for exclude.

            if ($session_data['filter_username'] != "") {
                $username = $session_data['filter_username'];
                $admin_id = $this->get_admin_id($username);
                $search['admin_id'] = (string) $admin_id;
            }
            
            if (!empty($session_data['opportunityId'])) {
                $search['opportunityId'] = $session_data['opportunityId'];
            }
             if (!empty($session_data['orderId'])) {
                $search['_id'] = $this->mongo_db->mongoId($session_data['orderId']);
            }
            $start_date_new = 0;
            $end_date_new = 0;
            $start_date_check = 0;
            $end_date_check = 0;
            if ($session_data['filter_by_start_date'] != "" && $session_data['filter_by_end_date'] != "") {

                // $created_datetime = date('Y-m-d H:i:s', strtotime($session_data['filter_by_start_date']));
                // $orig_date = new DateTime($created_datetime);
                //$orig_date = $orig_date->getTimestamp();
                // if(date('I')){
                //   echo 'i am in daylight saving';  
                // }
                // print_r($orig_date->getTimestamp());exit;
                //$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
                $start_date_new = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s',strtotime($session_data['filter_by_start_date']))); 

                // $created_datetime22 = date('Y-m-d H:i:s', strtotime($session_data['filter_by_end_date']));
                // $orig_date22 = new DateTime($created_datetime22);
                // $orig_date22 = $orig_date22->getTimestamp();
                // $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
                $end_date_new = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s',strtotime($session_data['filter_by_end_date']))); 
                $search['created_date'] = array('$gte' => $start_date_new, '$lte' => $end_date_new);
            }

            if ($session_data['filter_by_start_date_m'] != "" && $session_data['filter_by_end_date_m'] != "") {
                // $created_datetime = date('Y-m-d G:i:s', strtotime($session_data['filter_by_start_date_m']));
                // $orig_date = new DateTime($created_datetime);
                // $orig_date = $orig_date->getTimestamp();
                // $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
                $start_date_check = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s',strtotime($session_data['filter_by_start_date_m']))); 
                // $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_data['filter_by_end_date_m']));
                // $orig_date22 = new DateTime($created_datetime22);
                // $orig_date22 = $orig_date22->getTimestamp();
                // $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
                $end_date_check = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s',strtotime($session_data['filter_by_end_date_m'])));
                //echo '<pre>start'.$start_date_check; 
                //echo '<pre>end'.$end_date_check; exit;
                $search['modified_date'] = array('$gte' => $start_date_check, '$lte' => $end_date_check);
            }
            //multi-select //Umer Abbas [12-11-19]
            //echo '<pre>';print_r($session_data['filter_by_status']);exit;
            if (!empty($session_data['filter_by_status'])) {
                $order_status = $session_data['filter_by_status'];

                
                
                $status_filter_arr = array();
                if (in_array('new', $order_status)) {
                    $search['parent_status']['$ne'] = 'parent';
                    $status_filter_arr[] = 'new';
                    $status_filter_arr[] = 'new_ERROR';
                    $search['price']['$ne'] = '';
                }
                if (in_array('error', $order_status)) {
                    $status_filter_arr[] = 'error';
                }

                if(in_array('duplicateOrders', $order_status)){

                    $search['created_buy']   =   'asimScript';
                    $search['is_sell_order'] =   'sold';
                }

                if(in_array('duplicateOrdersOpen', $order_status)){

                    $search['created_buy']   =   'asimScript';
                    $search['is_sell_order'] =   'yes';
                    $search['status']['$nin']=   ['credentials_ERROR','canceled_ERROR','error' ,'new', 'new_ERROR', 'canceled', 'pause', 'submitted_buy', 'fraction_submitted_buy'];
                }

                if (in_array('open', $order_status)) {
                    // $status_filter_arr[] = 'submitted';
                    $status_filter_arr[] = 'FILLED';
                    // $status_filter_arr[] = 'FILLED_ERROR';
                    if ($session_data['filter_by_start_date_m'] != "" && $session_data['filter_by_end_date_m'] != "") {
                        $searchOpen['modified_date'] = array('$gte' => $start_date_check, '$lte' => $end_date_check);
                        $searchCost['modified_date'] = array('$gte' => $start_date_check, '$lte' => $end_date_check);
                    }elseif ($session_data['filter_by_start_date'] != "" && $session_data['filter_by_end_date'] != "") {
                        $searchOpen['created_date'] = array('$gte' => $start_date_new, '$lte' => $end_date_new);
                        $searchCost['created_date'] = array('$gte' => $start_date_new, '$lte' => $end_date_new);
                    }
                    $searchOpen['resume_order_id']  = ['$exists' => false];
                    $searchOpen['is_sell_order']    = 'yes';
                    $searchOpen['count_avg_order']  = ['$exists' => false];
                    $searchOpen['cost_avg']         = ['$nin' => ['yes', 'taking_child', 'completed']];
                    $searchOpen['cavg_parent']      = ['$ne' => 'yes'];
                    $searchOpen['move_to_cost_avg'] = ['$ne' => 'yes'];
                    $searchOpen['shifted_order_label']  =   ['$exists' => false];

                    // $searchCost['cavg_parent']      = 'yes';
                    // $searchCost['is_sell_order']    = 'yes';
                    // $searchCost['cost_avg']['$ne']  =  'completed';
                    // $searchOpen['cavg_parent']      = ['$ne' => 'yes'];
                    // $searchCost['count_avg_order']  = ['$exists' => false];
                    // $searchCost['move_to_cost_avg'] = ['$ne' => 'yes'];
                    // $searchCost['shifted_order_label']  =   ['$exists' => false];
                    $searchCost['resume_order_id']  = ['$exists' => false];
                    $searchCost['is_sell_order']    = 'yes';
                    $searchCost['count_avg_order']  = ['$exists' => false];
                    $searchCost['cost_avg']         = ['$nin' => ['yes', 'taking_child', 'completed']];
                    $searchCost['cavg_parent']      = ['$ne' => 'yes'];
                    $searchCost['move_to_cost_avg'] = ['$ne' => 'yes'];
                    $searchCost['shifted_order_label']  =   ['$exists' => false];
                    $search['$or'] = [$searchOpen, $searchCost];
                }
                if (in_array('sold', $order_status)) {
                   
                    $status_filter_arr[] = 'FILLED';
                    // $search['is_sell_order'] = 'sold';
                    // $search['is_manual_sold'] = ['$ne'=>'yes'];
                    // $search['count_avg_order'] = ['$exists'=>false];
                    // $search['cavg_parent'] = 'yes';  
                    if ($session_data['filter_by_start_date_m'] != "" && $session_data['filter_by_end_date_m'] != "") {
                        $searchSold['sell_date'] = array('$gte' => $start_date_check, '$lte' => $end_date_check);
                        $searchCostSold['sell_date'] = array('$gte' => $start_date_check, '$lte' => $end_date_check);
                    }elseif ($session_data['filter_by_start_date'] != "" && $session_data['filter_by_end_date'] != "") {
                        $searchSold['created_date'] = array('$gte' => $start_date_new, '$lte' => $end_date_new);
                        $searchCostSold['created_date'] = array('$gte' => $start_date_new, '$lte' => $end_date_new);
                    }
                    $searchSold['cavg_parent']      = ['$exists'=>false];
                    $searchSold['is_sell_order']    = 'sold';
                    //$searchSold['is_manual_sold']   = ['$ne'=>'yes'];
                    $searchSold['count_avg_order']  = ['$exists'=>false];
                    //$searchSold['is_manual_sold']   = ['$ne' => 'yes'];
                    $searchSold['shifted_order_label']  =   ['$exists' => false];
                    //$searchSold['accumulations']  =   ['$exists' => true];

                    $searchCostSold['cavg_parent']      =   'yes';   
                    $searchCostSold['cost_avg']         =   'completed';
                    //$searchCostSold['count_avg_order']  =   ['$exists'=>false];
                    //$searchCostSold['is_manual_sold']   =   ['$ne' => 'yes'];
                    $searchCostSold['shifted_order_label']  =   ['$exists' => false];
                    // $searchCostSold['accumulations']  =   ['$exists' => true];


                    $search['$or'] = [$searchSold, $searchCostSold];
                    // $collection = "sold_buy_orders";
                }
                
              
                if (in_array('sold_manually', $order_status)) {
                    $status_filter_arr[] = 'FILLED';
                    $search['is_sell_order']        =   'sold';
                    $search['is_manual_sold']       =   'yes';
                    $search['count_avg_order']      =   ['$exists'=>false];
                    $search['shifted_order_label']  =   ['$exists' => false];

                    // $search['cavg_parent'] = 'yes';            
                    // $search['cost_avg'] = 'completed';
                    // $search['count_avg_order'] = ['$exists'=>false];
                    // $collection = "sold_buy_orders";
                }
                if (in_array('LTH_sold', $order_status)) {
                    $status_filter_arr[] = 'FILLED';
                    // $searchlth['is_sell_order']     =   'sold';
                    // $searchlth['is_lth_order']      =   'yes';
                    // $searchlth['count_avg_order']   =   ['$exists'=>false];
                    // $searchlth['cavg_parent']       =   ['$ne' => 'yes'];  
                    // $searchlth['move_to_cost_avg']  =   ['$ne' => 'yes'];
                    // $searchlth['is_manual_sold']    =   ['$ne' => 'yes']; 
                    // $searchlth['shifted_order_label'] = ['$exists' => false];

                    // $searchCostLTH['cavg_parent']       =   'yes';            
                    // $searchCostLTH['cost_avg']          =   'completed';
                    // $searchCostLTH['count_avg_order']   =   ['$exists'=>false];
                    // $searchCostLTH['move_to_cost_avg']  =   'yes';  
                    // $searchCostLTH['is_sell_order']     =   'sold';
                    // $searchCostLTH['is_manual_sold']    =   ['$ne' => 'yes'];
                    // $searchCostLTH['shifted_order_label'] = ['$exists' => false];

                    $searchlth['is_sell_order']     =   'sold';
                    $searchlth['is_lth_order']      =   'yes';
                    $searchlth['count_avg_order']   =   ['$exists'=>false];
                    $searchlth['cost_avg']       =   ['$exists' => false]; 
                    $searchlth['cavg_parent']       =   ['$exists' => false]; 
                    $searchlth['move_to_cost_avg']  =   ['$ne' => 'yes'];
                    $searchlth['is_manual_sold']    =   ['$ne' => 'yes']; 
                    $searchlth['shifted_order_label'] = ['$exists' => false];

                    $searchCostLTH['cavg_parent']       =   ['$exists' => false];        
                    $searchCostLTH['cost_avg']       =   ['$exists' => false]; 
                    $searchCostLTH['count_avg_order']   =   ['$exists'=>false];
                    $searchCostLTH['move_to_cost_avg']  =   'yes';  
                    $searchCostLTH['is_sell_order']     =   'sold';
                    $searchCostLTH['is_manual_sold']    =   ['$ne' => 'yes'];
                    $searchCostLTH['shifted_order_label'] = ['$exists' => false];

                    $search['$or'] = [$searchlth, $searchCostLTH];
                }

                if(in_array('ca_single', $order_status)){
                    $status_filter_arr[] = 'FILLED';

                    $search['cavg_parent']      =   'yes';
                    $search['is_sell_order']    =   'yes';
                    $search['cost_avg']['$ne']  =  'completed';
                    $search['count_avg_order']  =   ['$exists' => false];
                    $search['shifted_order_label'] = ['$exists' => false];
                }

                if (in_array('lth_pause', $order_status)) {
                    $status_filter_arr[] = 'FILLED';
                    $search['is_sell_order'] = 'pause';
                    $search['shifted_order_label'] = ['$exists' => false];

                    // $search['is_sell_order'] = ['$in' => ['pause', 'resume_pause', 'resume_complete'] ];
                }

                if(in_array('shifted_order' , $order_status))
                {
                    $search['shifted_order_label'] = 
                    [
                        '$exists' =>  true,
                        '$eq' => 'shifted'
                    ];
                    $search['cavg_parent'] = ['$ne' => 'yes'];
                    
                }
                // echo "<pre>"; print_r($search); exit;
                if(in_array('shifted_open', $order_status)){
                    $status_filter_arr[] = 'FILLED';

                    $searchOpenShifted['resume_order_id']     =   ['$exists' => false];
                    $searchOpenShifted['is_sell_order']       =   'yes';
                    $searchOpenShifted['count_avg_order']     =   ['$exists' => false];
                    $searchOpenShifted['cost_avg']            =   ['$nin' => ['yes', 'taking_child', 'completed']];
                    $searchOpenShifted['cavg_parent']         =   ['$ne' => 'yes'];
                    $searchOpenShifted['move_to_cost_avg']    =   ['$ne' => 'yes'];
                    $searchOpenShifted['shifted_order_label'] =   ['$exists' => true];

                    $searchCostShifted['cavg_parent']         =   'yes';
                    $searchCostShifted['is_sell_order']       =   'yes';
                    $searchCostShifted['cost_avg']['$ne']     =   'completed';
                    $searchCostShifted['count_avg_order']     =   ['$exists' => false];
                    $searchCostShifted['move_to_cost_avg']    =   ['$ne' => 'yes'];
                    $searchCostShifted['shifted_order_label'] =   ['$exists' => true];

                    $search['$or'] = [$searchOpenShifted, $searchCostShifted];
                }

                if(in_array('shifted_lth', $order_status)){
                    $status_filter_arr[] = 'LTH' ; 

                    $searchOpenLThShifted['is_sell_order']             =   'yes';
                    $searchOpenLThShifted['count_avg_order']           =   ['$exists'=> false];
                    $searchOpenLThShifted['cost_avg']['$nin']          =   ['yes', 'taking_child', 'completed'];
                    $searchOpenLThShifted['cavg_parent']['$ne']        =   'yes';
                    $searchOpenLThShifted['move_to_cost_avg']['$ne']   =   'yes';
                    $searchOpenLThShifted['shifted_order_label']       =   ['$exists' => true];

                    $searchCostOpenLTHShifted['cavg_parent']           =   'yes';
                    $searchCostOpenLTHShifted['cost_avg']              =   'yes';
                    $searchCostOpenLTHShifted['count_avg_order']       =   ['$exists'=> false];
                    $searchCostOpenLTHShifted['move_to_cost_avg']      =   'yes';
                    $searchCostOpenLTHShifted['is_sell_order']         =   'yes';
                    $searchCostOpenLTHShifted['shifted_order_label']   =   ['$exists' => true];

                    $search['$or'] = [$searchOpenLThShifted, $searchCostOpenLTHShifted];
                }

                if(in_array('shifted_sold', $order_status)){
                    $status_filter_arr[] = 'FILLED';

                    $searchSoldShifted['cavg_parent']              =   ['$exists'=>false];
                    $searchSoldShifted['is_sell_order']            =   'sold';
                    $searchSoldShifted['is_manual_sold']           =   ['$ne'=>'yes'];
                    $searchSoldShifted['count_avg_order']          =   ['$exists'=>false];
                    $searchSoldShifted['is_manual_sold']           =   ['$ne' => 'yes'];
                    $searchSoldShifted['shifted_order_label']      =   ['$exists' => true];

                    $searchCostSoldShifted['cavg_parent']          =   'yes';   
                    $searchCostSoldShifted['cost_avg']             =   'completed';
                    $searchCostSoldShifted['count_avg_order']      =   ['$exists'=>false];
                    $searchCostSoldShifted['is_manual_sold']       =   ['$ne' => 'yes'];
                    $searchCostSoldShifted['shifted_order_label']  =   ['$exists' => true];

                    $search['$or'] = [$searchSoldShifted, $searchCostSoldShifted];                    
                }

                if (in_array('LTH', $order_status)) {
                    $status_filter_arr[] = 'LTH' ; 
                    // $status_filter_arr[] = 'FILLED';
                    $searchOpenLTh['is_sell_order']             =   'yes';
                    $searchOpenLTh['count_avg_order']           =   ['$exists'=> false];
                    $searchOpenLTh['cost_avg']['$nin']          =   ['yes', 'taking_child', 'completed'];
                    $searchOpenLTh['cavg_parent']['$ne']        =   'yes';
                    $searchOpenLTh['move_to_cost_avg']['$ne']   =   'yes';
                    $searchOpenLTh['shifted_order_label']       =   ['$exists' => false];

                    
                    $searchCostOpenLTH['is_sell_order']             =   'yes';
                    $searchCostOpenLTH['count_avg_order']           =   ['$exists'=> false];
                    $searchCostOpenLTH['cost_avg']['$nin']          =   ['yes', 'taking_child', 'completed'];
                    $searchCostOpenLTH['cavg_parent']['$ne']        =   'yes';
                    $searchCostOpenLTH['move_to_cost_avg']['$ne']   =   'yes';
                    $searchCostOpenLTH['shifted_order_label']       =   ['$exists' => false];

                    // $searchCostOpenLTH['cavg_parent']           =   'yes';
                    // $searchCostOpenLTH['cost_avg']              =   'yes';
                    // $searchCostOpenLTH['count_avg_order']       =   ['$exists'=> false];
                    // $searchCostOpenLTH['move_to_cost_avg']      =   'yes';
                    // $searchCostOpenLTH['is_sell_order']         =   'yes';
                    // $searchCostOpenLTH['shifted_order_label']   =   ['$exists' => false];

                    $search['$or'] = [$searchOpenLTh, $searchCostOpenLTH];
                }
                
                if(in_array('childBuy', $order_status)){

                    $status_filter_arr[] = 'FILLED';

                    $startingTime = $this->mongo_db->converToMongodttime(date('Y-m-d 00:00:00')); 
                    $endingTime   = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s', strtotime('-1 days')));

                    $search['created_date']         =       ['$lte' => $startingTime,  '$gte' => $endingTime];
                    $search['cavg_parent']          =       ['$exists' => false];
                    $search['is_sell_order']['$ne'] =       'sold';
                    $search['cost_avg']['$in']      =       ['yes', 'taking_child'];
                    //$search['count_avg_order']      =       ['$exists' => false];
                    //$search['shifted_order_label']  =       ['$exists' => false];
                    // if(isset($_COOKIE['sheraz']) && $_COOKIE['sheraz'] == 1){
                    //     echo json_encode($search);exit; 
                    // }
                }
                if(in_array('CA_child_buy',$order_status)){
                    $status_filter_arr[] = 'FILLED';
                    $status_filter_arr[] = 'LTH';
                    $status_filter_arr[] = 'CAVG_TAKING_CHILD';
                    $status_filter_arr[] = 'TAKING_CHILD';
                    // $startingTime = $this->mongo_db->converToMongodttime(date('Y-m-d 00:00:00')); 
                    // $endingTime   = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s', strtotime('-7 days')));
                    // $search['created_date']         =       ['$lte' => $startingTime,  '$gte' => $endingTime];
                    $search['cavg_parent']          =       ['$exists' => false];
                    $search['is_sell_order']['$ne'] =       'sold';
                    $search['cost_avg']['$in']      =       ['yes', 'taking_child'];
                    
                }

                if(in_array('CA_child_sold',$order_status)){
                    $status_filter_arr[] = 'FILLED';
                    // $startingTime = $this->mongo_db->converToMongodttime(date('Y-m-d 00:00:00')); 
                    // $endingTime   = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s', strtotime('-7 days')));
                    // $search['sell_date']         =       ['$lte' => $startingTime,  '$gte' => $endingTime];
                    $search['cavg_parent']          =       ['$exists' => false];
                    $search['is_sell_order']        =       'sold';
                    $search['cost_avg']['$in']      =       ['yes', 'taking_child','completed'];
                    
                }
                if(in_array('negative_sold',$order_status)){
                    $status_filter_arr[] = 'FILLED';
                    // $startingTime = $this->mongo_db->converToMongodttime(date('Y-m-d 00:00:00')); 
                    // $endingTime   = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s', strtotime('-7 days')));
                    // $search['sell_date']         =       ['$lte' => $startingTime,  '$gte' => $endingTime];
                    $search['is_sell_order']        =       'sold';
                    //$search['accumulations.profit']['$lt']      =   0;
                    $search['$expr']  =  ['$lt'=>['$market_sold_price','$purchased_price']];
                    
                }
                
                if(in_array('CA_Pre_july_2021', $order_status)){
                    $status_filter_arr[] = 'FILLED';
                    $startingTime = $this->mongo_db->converToMongodttime(date('2020-01-01 00:00:00')); 
                    $endingTime   = $this->mongo_db->converToMongodttime(date('2021-07-01 23:59:59'));
                    $search['modified_date']         =       ['$gte' => $startingTime,  '$lte' => $endingTime];
                    $search['cavg_parent']          =       ['$exists' => true];
                    //$search['is_sell_order']        =       'yes';
                    $search['cost_avg']['$in']      =       ['yes', 'taking_child'];
                    //$search['count_avg_order']      =       ['$exists' => false];
                    //$search['shifted_order_label']  =       ['$exists' => false];
                }
                
                if(in_array('profitOrders' , $order_status)){
                      if ($session_data['filter_by_start_date'] = "" && $session_data['filter_by_end_date'] = "") {
                            $startDate = date('Y-m-d' , strtotime('-10 days'));
                            $startMongoTime = $this->mongo_db->converToMongodttime($startDate);
                            $endDate = date('Y-m-d');
                            $endMongoTime = $this->mongo_db->converToMongodttime($endDate);
                            $search['created_date'] = array('$gte' => $startMongoTime, '$lte' => $endMongoTime);
                      }
                    $marketPricesArray = get_current_market_prices($session_data['filter_by_exchange']);
                    $status_filter_arr[] = 'LTH' ; 
                    $status_filter_arr[] = 'FILLED';

                    $symbol['is_sell_order']    = 'yes';
                    $symbol['count_avg_order']  = ['$exists' => false];

                    // $symbol['$or'] = [['cavg_parent' => ['$exists' => false]],  ['cavg_parent' => 'yes']];
                    // $symbol['cost_avg']['$nin']  =  ['completed', 'yes'];

                    // $cond['cavg_parent']   = ['$exists' => false];
                    // $cond['cost_avg']      = ['$exists' => false];
                    $cond['$and'] = [['cavg_parent' => ['$exists' => false]],  ['cost_avg' => ['$exists' => false]]];

                    $condi1['cavg_parent'] = 'yes';
                    
                    $symbol['$or'] = [$cond,  $condi1];

                    foreach ($marketPricesArray as $key=>$prices){
                        if($key == 'ZENBTC'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'XMRBTC'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'XLMBTC'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'TRXBTC'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'QTUMBTC'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'EOSUSDT'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'XRPBTC'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'NEOUSDT'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'DASHBTC'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'QTUMUSDT'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'POEBTC'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'LTCUSDT'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'XRPUSDT'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'ETHBTC'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'ADABTC'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'NEOBTC'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'BTCUSDT'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'EOSBTC'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'LINKBTC'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }elseif($key == 'ETCBTC'){
                            $conditionProfit['$expr'] = ['$gt' => [ $prices, '$sell_price']];
                            $symbol['symbol'] = $key;
                            $search['$or'][] =  ['$and' => [$conditionProfit,  $symbol]];
                        }
                    } 
                }
                if (in_array('takingparent', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'takingOrder';
                }
                if (in_array('takeparent', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'new';
                }
                if (in_array('play', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'new';
                    $search['pause_status'] = 'play';
                }
                if (in_array('pause', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'new';
                    $search['pause_status'] = 'pause';
                }
                if (in_array('pick_parent_yes', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $search['pick_parent'] = 'yes';
                }
                if (in_array('pick_parent_no', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $search['pick_parent'] = 'no';
                }
                if (in_array('sell_rule', $order_status)) {
                    $sell_rule_array_in = ['sell_on_sell_rule','level_1', 'level_2', 'level_3','level_4', 'level_5', 'level_6','level_7', 'level_8', 'level_9','level_10', 'level_11', 'level_12' ,'level_13','level_14', 'level_15', 'level_16'];
                    $search['cost_avg_array.order_type'] = ['$exists' => true];
                    $search['cost_avg_array.order_type']['$in'] = ['sell_on_sell_rule','level_1', 'level_2', 'level_3','level_4', 'level_5', 'level_6','level_7', 'level_8', 'level_9','level_10', 'level_11', 'level_12' ,'level_13','level_14', 'level_15', 'level_16'];
                }
                if (in_array('canceled', $order_status)) {
                    $status_filter_arr[] = 'canceled';
                }

                if (in_array('error_in_sell', $order_status)) {
                    // $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'error';
                }
                if (in_array('fraction_submitted_buy', $order_status)){

                    $status_filter_arr[] = 'fraction_submitted_buy';
                }
                if (in_array('new_ERROR', $order_status)) {
                    $status_filter_arr[] = 'new_ERROR';
                }
                if (in_array('FILLED_ERROR', $order_status)) {
                    $status_filter_arr[] = 'FILLED_ERROR';
                }
                if (in_array('KEYFILLED_ERROR', $order_status)) {
                    $status_filter_arr[] = 'KEYFILLED_ERROR';
                }
                if (in_array('IP_BAN_ERROR', $order_status)) {
                    $status_filter_arr[] = 'IP_BAN_ERROR';
                }
                if (in_array('api_error', $order_status)) {
                    $status_filter_arr[] = 'api_error';
                }
                
                // if (in_array('cost_submitted_all_for_sell', $order_status)) {
                //     $status_filter_arr[] = 'cost_submitted_1_for_sell';
                //     $status_filter_arr[] = 'cost_submitted_2_for_sell';
                //     $status_filter_arr[] = 'cost_submitted_3_for_sell';
                //     $status_filter_arr[] = 'cost_submitted_all_for_sell';
                // }
                if (in_array('cost_submitted_all_for_sell', $order_status)) {
                    $status_filter_arr[] = 'cost_submitted_1_for_sell';
                    $status_filter_arr[] = 'cost_submitted_2_for_sell';
                    $status_filter_arr[] = 'cost_submitted_3_for_sell';
                    $status_filter_arr[] = 'cost_submitted_all_for_sell';
                    $status_filter_arr[] = 'submitted_for_sell';
                    $status_filter_arr[] = 'submitted_ERROR';
                    $status_filter_arr[] = 'fraction_submitted_sell';
                }
                if (in_array('binance_sold_doubt', $order_status)) {
                    $status_filter_arr[] = 'binance_sold_doubt';
                }
                
                // if (in_array('submitted_ERROR', $order_status)) {
                //     $status_filter_arr[] = 'submitted_ERROR';
                // }

                if (in_array('LTH_ERROR', $order_status)) {
                    $status_filter_arr[] = 'LTH_ERROR';
                }
                if (in_array('canceled_ERROR', $order_status)) {
                    $status_filter_arr[] = 'canceled_ERROR';
                }
                // if (in_array('submitted_for_sell', $order_status)) {
                //     $status_filter_arr[] = 'submitted_for_sell';
                //     // echo "<br>testing";exit;
                // }
                // if (in_array('fraction_submitted_sell', $order_status)) {
                //     $status_filter_arr[] = 'fraction_submitted_sell';
                // }
                if (in_array('credentials_ERROR', $order_status)) {
                    $status_filter_arr[] = 'credentials_ERROR';
                }
                if(in_array('user_left',$order_status)){
                    $status_filter_arr[] = 'user_left';
                }
                if(in_array('COIN_BAN_ERROR',$order_status)){
                    $status_filter_arr[] = 'COIN_BAN_ERROR';
                }
                if(in_array('APIKEY_ERROR',$order_status)){
                    $status_filter_arr[] = 'APIKEY_ERROR';
                }
                if(in_array('TEMPAPILOCK_ERROR',$order_status)){
                    $status_filter_arr[] = 'TEMPAPILOCK_ERROR';
                }
                if(in_array('APINONCE',$order_status)){
                    $status_filter_arr[] = 'APINONCE_ERROR'; 
                }
                if (in_array('LOT_SIZE_ERROR', $order_status)) {
                    $status_filter_arr[] = 'LOT_SIZE_ERROR'; 
                }
                if (in_array('fraction_ERROR', $order_status)) {
                    $status_filter_arr[] = 'fraction_ERROR';
                }
                if (in_array('SELL_ID_ERROR', $order_status)) {
                    $status_filter_arr[] = 'SELL_ID_ERROR';
                }
                if (in_array('ARCHIVE', $order_status)) {
                    $status_filter_arr[] = 'ARCHIVE';
                }

                if (in_array('resume_paused', $order_status)) {
                    $search['is_sell_order'] = 'pause';
                    $search['show_order'] = ['$ne' => 'no'];
                }
                if (in_array('resumed', $order_status)) {
                    // $search['resume_order_id'] = ['$exists' => true];
                    $search['is_sell_order'] = 'resume_pause';
                    $search['resume_order_arr'] = ['$exists' => false];
                    $search['show_order'] = ['$ne' => 'no'];
                }
                if (in_array('resume_in_progress', $order_status)) {
                    $search['is_sell_order'] = 'resume_pause';
                    $search['resume_order_arr.0'] = ['$exists' => true];
                    $search['show_order'] = ['$ne' => 'no'];
                }
                if (in_array('resume_completed', $order_status)) {
                    $search['resume_status'] = 'completed';
                }
                 if (in_array('atg_yes', $order_status)) {
                    $search['auto_trade_generator'] = 'yes';
                     //$search['parent_status'] = 'parent';
                }
                if (in_array('resume_child_sold', $order_status)) {
                    $search['status'] = 'FILLED';
                    $search['is_sell_order'] = 'yes';
                    $search['resume_order_id'] = ['$exists' => true];
                }
                if (in_array('quantity_issue', $order_status)) {
                    $search['quantity_issue'] = '1';
                }
                if (in_array('cost_average', $order_status)) {
                    // $search['cavg_parent'] = ['$in'=>['yes', 'taking_child', 'completed']];
                    $search['cavg_parent'] = 'yes';
                    //$search['count_avg_order'] = ['$exists' => true]; this was commented on order of shehzad by sheraz
                    // [cost_avg] => completed
                }
                if (in_array('cost_average_orders', $order_status)) {
                    // $search['cavg_parent'] = ['$in'=>['yes', 'taking_child', 'completed']];
                    $search['cost_avg'] = ['$in'=>['yes','taking_child']];
                     $search['cavg_parent'] = 'yes';
                    // $search['count_avg_order'] = ['$exists' => true];
                    // [cost_avg] => completed
                }
                
                if (in_array('cost_averageSold', $order_status)) {
                    $search['cavg_parent'] = 'yes';            
                    $search['cost_avg'] = 'completed';
                    //$search['count_avg_order'] = ['$exists'=> true]; this was commented on order of shehzad by sheraz
                }
                if (in_array('cost_averageOpen', $order_status)) {
                    $search['cavg_parent'] = 'yes';
                    $search['cost_avg'] = ['$ne' => 'completed'];
                    $search['is_sell_order'] = ['$ne' => 'sold'];
                    $search['status'] = ['$ne'=>'canceled'];
                    // echo "<pre>"; print_r($search); exit;
                    //$search['count_avg_order'] = ['$exists'=> true]; this was commented on order of shehzad by sheraz
                    
                }
                if (in_array('cost_average_sold_status', $order_status)) {
                    $search['cavg_parent'] = 'yes';
                    $search['cost_avg'] = 'yes';
                    $search['trading_status'] = 'complete';
                    $search['status'] = 'FILLED';
                    
                }
                if (in_array('QUANTITY_ERROR', $order_status)) {
                    $search['status'] = 'QUANTITY_ERROR';
                }
                if (in_array('cost_average_orders_opportunity', $order_status)) {
                    //echo '<pre>heloo';
                    // $search['cavg_parent'] = ['$in'=>['yes', 'taking_child', 'completed']];
                    $search['cost_avg'] = ['$in'=>['yes','taking_child','CA_TAKING_CHILD','ca_taking_child','completed']];
                    $search['cavg_parent'] = ['$ne'=>'yes'];
                    $search['status'] = ['$ne'=>'canceled'];
                    // $search['count_avg_order'] = ['$exists' => true];
                    // [cost_avg] => completed
                }
                if (in_array('canceled_cost_average_orders_opportunity', $order_status)) {
                    //echo '<pre>heloo';
                    // $search['cavg_parent'] = ['$in'=>['yes', 'taking_child', 'completed']];
                    $search['cost_avg'] = ['$in'=>['yes','taking_child','CA_TAKING_CHILD','ca_taking_child','completed']];
                    $search['cavg_parent'] = ['$exists'=>false];
                    $search['status'] = 'canceled';
                    // $search['count_avg_order'] = ['$exists' => true];
                    // [cost_avg] => completed
                }
                
                $status_filter_arr = array_unique($status_filter_arr);
                $status_filter_arr = array_values($status_filter_arr); //to reindex the array
                // echo "<pre>"; print_r($status_filter_arr); exit;
                if (!in_array('parent', $order_status) && !in_array('takingparent', $order_status) && !in_array('takeparent', $order_status) && !in_array('play', $order_status) && !in_array('pause', $order_status) && !in_array('pick_parent_yes', $order_status) && !in_array('pick_parent_no', $order_status)) {
                    $search['parent_status'] = array('$ne' => 'parent');
                }

                if(!empty($status_filter_arr)){
                    $search['status'] = array('$in' => $status_filter_arr);
                }
                if (in_array('errors_tab', $order_status)) {
                    $search['status'] = ['$nin' => ['CA_TAKING_CHILD','new', 'FILLED', 'fraction_submitted_buy', 'canceled', 'LTH', 'submitted', 'submitted_for_sell', 'fraction_submitted_sell']];
                }
            }

            //Umer Abbas [1-11-19]

            if (!empty($session_data['filter_by_nature'])) {
                if ($session_data['filter_by_nature'] == 'auto') {

                    $search['trigger_type'] = array('$ne' => 'no');
                    // if($search['status'] == 'new'){
                    //     $search['buy_parent_id'] = array('$exists' => true);
                    // }

                } else if ($session_data['filter_by_nature'] == 'manual') {

                    $search['trigger_type'] = 'no';

                }
            }else{
                $search['trigger_type'] = array('$in' => array('no','barrier_percentile_trigger'));
            }

            // if(!empty($session_data['profit_value'])){

            // }

            //show parents
            if ((!empty($session_data['show_parents']) && $session_data['show_parents'] == 'yes') || (!empty($search['parent_status']) && $search['parent_status'] == 'parent')) {
                $search['parent_status'] = 'parent';
            }else{
                $search['parent_status'] = ['$exists' => false];
            } 
            
            //show admin Orders
            if ((!empty($session_data['adminOrder']) && $session_data['adminOrder'] == 'yes')) {
                $search['admin_id']['$eq'] = '5c0912b7fc9aadaac61dd072';
            }elseif(empty($search['admin_id'])){
                $search['admin_id'] = [];
                $search['admin_id']['$ne'] = '5c0912b7fc9aadaac61dd072';
            }
            
            if ($session_data['optradio'] != "") {
                if ($session_data['optradio'] == 'created_date') {
                    $oder_arr['optradio'] = 'created_date';
                    if ($session_data['selector'] == 'ASC') {
                        $oder_arr['selector'] = 1;
                        $oder_arr['created_date'] = 1;
                    } else {
                        $oder_arr['selector'] = -1;
                        $oder_arr['created_date'] = -1;
                    }
                } elseif ($session_data['optradio'] == 'modified_date') {
                    $oder_arr['optradio'] = 'modified_date';
                    if ($session_data['selector'] == 'ASC') {
                        $oder_arr['selector'] = 1;
                        $oder_arr['modified_date'] = 1;
                    } else {
                        $oder_arr['selector'] = -1;
                        $oder_arr['modified_date'] = -1;
                    }

                }
            }


            // echo "<pre>jawad ";print_r($search);exit;
            $connetct = $this->mongo_db->customQuery();

            if ($session_data['filter_by_exchange'] != "") {
                $order_exchange = $session_data['filter_by_exchange'];
                if ($order_exchange != 'binance') {
                    $collection_str1 = 'sold_buy_orders_' . $order_exchange;
                    $collection_str2 = 'buy_orders_' . $order_exchange;
                } else {
                    $collection_str1 = 'sold_buy_orders';
                    $collection_str2 = 'buy_orders';
                }
            } else {
                $collection_str1 = 'sold_buy_orders';
                $collection_str2 = 'buy_orders';
            }
            
            if (empty($search)) {
                $this->reset_filters_report('all');
            }
            // if (empty($session_data['filter_by_status'])) {
            //     $costAvgParentFilter = array_merge($search, [
            //         'cost_avg' => ['$exists' => true],
            //         'cavg_parent' => 'yes',
            //     ]);
            //     $tempSearchQuery['$or'][] = $costAvgParentFilter;

            //     //Set for second condition
            //     $search1 = array_merge($search, [
            //         'cost_avg'    => ['$nin' => ['taking_child', 'yes', 'completed']],
            //         'cavg_parent' => ['$exists' => false],
            //     ]);
            // }

            // $tempSearchQuery['$or'][] = $search1;
            // $search = $tempSearchQuery;

            // unset($tempSearchQuery);

           

            if(!empty($session_data['profit']) ){

                $this->load->helper('common_helper');
                $getpriceArray = getAllMarketValue($session_data['filter_by_exchange']);
                $pricesArray = [];
        
                foreach($getpriceArray as $key=>$val){
                    $pricesArray[] = [
                        'symbol' => $key,
                        'price' => $val,
                    ];
                }
                $profit = (float)$session_data['profit'];

                $search['purchased_price']   =  ['$gt' => 0];
                $search['market_sold_price'] =  ['$gt' => 0];
                $search['sell_price']        =  ['$gt' => 0];
                $search['created_date']      =  ['$exists' => true];



                $lookup = [
                    [
                        '$match'            =>  $search,
                    ],
                    [
                        '$addFields' => [
                            'current_market' => [
                                '$filter' => [
                                    'input' => $pricesArray,
                                    'as' => 'item',
                                    'cond' => [ '$eq' => [ '$$item.symbol', '$symbol' ] ]
                                ]
                            ]
                        ]
                    ],


                    [
                        '$addFields' => [

                            'current_price' => ['$arrayElemAt' => ['$current_market', 0 ]], 
                            'purchased_price'  => ['$toDouble' => '$purchased_price']
                        ]
                    ],


                    [
                        '$addFields' =>  [
                            
                            'current_price_new' => ['$toDouble'  => '$current_price.price']
                        ] 
                    ],
                   [
                        '$addFields' =>  [
                            
                            'collection_name' => $collection_str2
                        ] 
                    ],

                    [
                        '$addFields' => [
                            'pl' => [ '$divide' => [ [ '$multiply' => [ [ '$subtract' => [ '$current_price_new', '$purchased_price' ] ], 100 ] ], '$purchased_price' ] ]
                        ]

                    ],

                    [
                        '$match' => [
                            '$expr' => [ '$lte' => [ $profit,  '$pl'] ]                            
                        ]
                    ],

                ];


                $lookup2 = [
                    [
                        '$match'            =>  $search,
                    ],

                    [
                        '$addFields' => [

                            'purchased_price'    => ['$toDouble' => '$purchased_price'],
                            'market_sold_price'  => ['$toDouble' => '$market_sold_price']
                        ]
                    ],
                    [
                        '$addFields' =>  [
                            
                            'collection_name' => $collection_str1
                        ] 
                    ],

                    [
                        '$addFields' => [
                            'pl' => [ '$divide' => [ [ '$multiply' => [ [ '$subtract' => [ '$market_sold_price', '$purchased_price' ] ], 100 ] ], '$purchased_price' ] ],
                        ]

                    ],

                    [
                        '$match' => [
                            '$expr' => [ '$lte' => [ $profit,  '$pl'] ]                            
                        ]
                    ],

                ];

            }
            
            if(in_array('errors_tab', $order_status)){
                $sold_count = 0;
            }else{
                if(!empty($session_data['profit'])){

                    $sold_count1 = $connetct->$collection_str1->aggregate($lookup2, ['allowDiskUse' => true ]);
                    $sold_count2 = iterator_to_array($sold_count1);
                    $sold_count  = count($sold_count2);
                    
                }else{
                    $sold_count = $connetct->$collection_str1->count($search);
                }
            }

            if(!empty($session_data['profit'])){
                                    
                $pending_count1 = $connetct->$collection_str2->aggregate($lookup, ['allowDiskUse' => true ]);
                $pending_count2 = iterator_to_array($pending_count1);
                $pending_count  = count($pending_count2);
            }else{  

                $pending_count = $connetct->$collection_str2->count($search);
            }

            $total_count = $sold_count + $pending_count;


            /////////////////////// PAGINATION CODE START HERE /////////////////////////////////////
            $this->load->library("pagination");
            $config = array();
            $config["base_url"] = SURL . "admin/order_report/index";
            $config["total_rows"] = $total_count;
            $config['per_page'] = 100;
            $config['num_links'] = 3;
            $config['use_page_numbers'] = true;
            $config['uri_segment'] = 4;
            $config['reuse_query_string'] = true;
            $config["first_tag_open"] = '<li>';
            $config["first_tag_close"] = '</li>';
            $config["last_tag_open"] = '<li>';
            $config["last_tag_close"] = '</li>';
            $config['next_link'] = '&raquo;';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['prev_link'] = '&laquo;';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['first_link'] = 'First';
            $config['last_link'] = 'Last';
            $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            $config['cur_tag_open'] = '<li class="active"><a href="#"><b>';
            $config['cur_tag_close'] = '</b></a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

            if($pending_count <= 100 && $sold_count <= 100){
                $config['per_page'] = 200;
            }

            $this->pagination->initialize($config);
            $page = $this->uri->segment(4);

            if (!isset($page)) {$page = 1;}
            $start = ($page - 1) * $config["per_page"];
            $skip = $start;
            $skip_sold = $skip;
            $skip_pending = $skip;
            $limit = $config["per_page"];

            $serial_number = ($page > 1 ? ($page * $config['per_page']) - 100 : 0);
            ////////////////////////////End Pagination Code///////////////////////////////////////

            $data['pagination'] = $this->pagination->create_links();

            /////////////////////// PAGINATION CODE END HERE /////////////////////////////////////

            $sold_percentage = ($sold_count / $total_count) * 100;
            $pending_percentage = ($pending_count / $total_count) * 100;

            $pending_limit = (100 / 100) * $pending_percentage;
            $sold_limit = (100 / 100) * $sold_percentage;

            if ($pending_count <= 100 && $sold_count <= 100) {
                $skip_pending = 0;
                $pending_limit = 0;
                
                $skip_sold = 0;
                $sold_limit = 0;
            }

            $pending_options = array('skip' => $skip_pending, 'sort' => array($oder_arr['optradio'] => $oder_arr['selector']), 'limit' => intval(round($pending_limit)));
            $sold_options = array('skip' => $skip_sold, 'sort' => array($oder_arr['optradio'] => $oder_arr['selector']), 'limit' => intval(round($sold_limit)));

            if(!empty($session_data['profit'])){
                $pending_limit = round($pending_limit); 
                
                $search['purchased_price']   =  ['$gt' => 0];
                $search['market_sold_price'] =  ['$gt' => 0];
                $search['sell_price']        =  ['$gt' => 0];
                $search['created_date']      =  ['$exists' => true];

                $lookup1 = [
                    [
                        '$match'            =>  $search,
                    ],
                    [
                        '$addFields' => [

                            'current_market' => [
                                '$filter' => [
                                    'input' => $pricesArray,
                                    'as' => 'item',
                                    'cond' => [ '$eq' => [ '$$item.symbol', '$symbol' ] ]
                                ]
                            ]
                        ]
                    ],


                    [
                        '$addFields' => [

                            'current_price' => ['$arrayElemAt' => ['$current_market', 0 ]], 
                            'purchased_price'  => ['$toDouble' => '$purchased_price']
                        ]
                    ],


                    [
                        '$addFields' =>  [
                            
                            'current_price_new' => ['$toDouble'  => '$current_price.price']
                        ] 
                    ],
                    [
                        '$addFields' =>  [
                            
                            'collection_name' => $collection_str2
                        ] 
                    ],

                    [
                        '$addFields' => [
                            'pl' => [ '$divide' => [ [ '$multiply' => [ [ '$subtract' => [ '$current_price_new', '$purchased_price' ] ], 100 ] ], '$purchased_price' ] ]
                        ]

                    ],

                    [
                        '$match' => [
                            '$expr' => [ '$lte' => [ $profit,  '$pl'] ]                            
                        ]
                    ],

                    [
                        '$sort' =>  [ $oder_arr['optradio'] => $oder_arr['selector'] ]
                    ],
                    
                    // [ 
                    //     '$limit' => $pending_limit
                    // ], 
                        
                    [
                        '$skip' => $skip_pending, 

                    ],

                ];
                $pending_curser = $connetct->$collection_str2->aggregate($lookup1, ['allowDiskUse' => true ]);

            }else{
                
                $pending_curser = $connetct->$collection_str2->find($search, $pending_options);
            }
            if(in_array('errors_tab', $order_status)){
                $sold_curser = [];
            }else{
                if(!empty($session_data['profit'])){

                    $search['purchased_price']   =  ['$gt' => 0];
                    $search['market_sold_price'] =  ['$gt' => 0];
                    $search['sell_price']        =  ['$gt' => 0];
                    $search['created_date']      =  ['$exists' => true];



                    $lookup2 = [
                        [
                            '$match'            =>  $search,
                    
                        ],
    
                        [
                            '$addFields' => [
    
                                'purchased_price'    => ['$toDouble' => '$purchased_price'],
                                'market_sold_price'  => ['$toDouble' => '$market_sold_price']
                            ]
                        ],
                        [
                            '$addFields' =>  [
                            'collection_name' => $collection_str1
                            ] 
                        ],
        
                        [
                            '$addFields' => [
                                'pl' => [ '$divide' => [ [ '$multiply' => [ [ '$subtract' => [ '$market_sold_price', '$purchased_price' ] ], 100 ] ], '$purchased_price' ] ]
                            ]
    
                        ],
    
                        [
                            '$match' => [
                                '$expr' => [ '$lte' => [ $profit,  '$pl'] ]                            
                            ]
                        ],
    
                    ];
                    $pending_curser = $connetct->$collection_str1->aggregate($lookup2, ['allowDiskUse' => true ]);

                }else{
                    
                    $sold_curser = $connetct->$collection_str1->find($search, $sold_options);
                }
            }
            $pending_arr = iterator_to_array($pending_curser);            
            if (in_array('errors_tab', $order_status)) {

                $sold_arr = [];
            } else {
                if(!empty($session_data['profit'])){
                    $sold_arr = [];

                }else{

                    $sold_arr = iterator_to_array($sold_curser);
                }
            }
            if(!empty($session_data['profit'])){

                if(count($pending_arr) > 0){

                    $orders = $pending_arr ;
                }else{
                    $orders = [];
                }
            }else{

                $orders = array_merge_recursive($pending_arr, $sold_arr);
            }

            if ($session_data['optradio'] != "") {
                if ($session_data['optradio'] == 'created_date') {
                    if ($session_data['selector'] == 'ASC') {
                        foreach ($orders as $key => $part) {
                            $sort[$key] = (string) $part['created_date'];
                        }
                        array_multisort($sort, SORT_ASC, $orders);
                    } else {
                        foreach ($orders as $key => $part) {
                            $sort[$key] = (string) $part['created_date'];
                        }
                        array_multisort($sort, SORT_DESC, $orders);
                    }
                } elseif ($session_data['optradio'] == 'modified_date') {
                    if ($session_data['selector'] == 'ASC') {
                        foreach ($orders as $key => $part) {
                            $sort[$key] = (string) $part['modified_date'];
                        }
                        array_multisort($sort, SORT_ASC, $orders);
                    } else {
                        foreach ($orders as $key => $part) {
                            $sort[$key] = (string) $part['modified_date'];
                        }
                        array_multisort($sort, SORT_DESC, $orders);
                    }
                }
            } else {
                foreach ($orders as $key => $part) {
                    $sort[$key] = (string) $part['modified_date'];
                }
                array_multisort($sort, SORT_DESC, $orders);
            }
            //end
            // echo '<pre>';print_r($search);exit;
            
            $logged_user_id = $this->session->userdata('admin_id');
            $logged_timezone = get_user_timezone($logged_user_id);
            $logged_timezone = 'PKT';
            $new_order_arrray = array();
            // echo "<pre>"; print_r($orders); exit;
            // if(in_array('sell_rule' , $order_status)){

            //     $total_count = 0;
            // }
            foreach ($orders as $row) {
                // echo "<pre>"; print_r($row);
                $row1['_id'] = (string) $row['_id'];
                $row1["admin_id"] = $row["admin_id"];
                $fnc_data=$this->allocated_balance($row1["admin_id"], $input_search_filters['filter_by_exchange']);
                $row1["new_atg_allocated_btc"] = isset($fnc_data['new_atg_allocated_btc']) ? $fnc_data['new_atg_allocated_btc'] : '0';
                $row1["new_atg_allocated_usdt"] = isset($fnc_data['new_atg_allocated_usdt']) ? $fnc_data['new_atg_allocated_usdt'] : '0';
                $row1["remaining_btc_allocated"] = isset($fnc_data['remaining_btc_allocated']) ? $fnc_data['remaining_btc_allocated'] : '0';
                $row1["remaining_usdt_allocated"] = isset($fnc_data['remaining_usdt_allocated']) ? $fnc_data['remaining_usdt_allocated'] : '0';
                
                //$balance_info = $this->get_user_balance_info($row1["admin_id"], $input_search_filters['filter_by_exchange']);
                 
                // $row1["total_usdt"]  = $balance_info['available_balance'][2]['coin_balance'] + $balance_info['used_usdt_balance'];
                $row1['accumulations_data'] = array();
                if(isset($row['cost_avg_array'])){
                    //  echo "<pre>"; print_r($row['symbol']);
                    // $bulkOrders = [];
                    $total_profit = 0;
                    foreach ($row['cost_avg_array'] as $key => $cavg_value) {
                        if($cavg_value['order_sold'] == 'yes'){
                            
                            $conn = $this->mongo_db->customQuery();
                            $collection_name = '';
                            if($input_search_filters['filter_by_exchange'] == 'binance'){
                                $collection_name = 'sold_buy_orders';    
                            }else{
                                $collection_name = 'sold_buy_orders_'.$input_search_filters['filter_by_exchange'];
                            }
                            $cavg_data = $conn->$collection_name->find(['_id'=>$cavg_value['buy_order_id']]); 
                            $results= iterator_to_array($cavg_data);
                            $total_profit = $total_profit + $results[0]['accumulations']['profit'];  

                        }//end of if

                        $bulkOrders = [];
                        $uniqueBuyOrderIds = []; 
                        $filteredOrders = [];
                        foreach ($row['cost_avg_array'] as $key => $currentOrder) {
                            if ($currentOrder['order_sold'] == 'yes') {
                                $currentDate = (string)$currentOrder['sellTimeDate'];
                                $currentDate = (int)$currentDate;

                                $matchingOrders = [];
                                $currentBuyOrderID = (string)$currentOrder['buy_order_id'];

                                if (!in_array($currentBuyOrderID, $uniqueBuyOrderIds)) {
                                    foreach ($row['cost_avg_array'] as $otherOrder) {
                                        $otherBuyOrderID = (string)$otherOrder['buy_order_id'];
                                        if ($currentBuyOrderID != $otherBuyOrderID) {
                                            if ($otherOrder['order_sold'] == 'yes') {
                                                $otherDate = (string)$otherOrder['sellTimeDate'];
                                                $otherDate = (int)$otherDate;

                                                $timeDifference = abs($currentDate - $otherDate);
                                                $timeDifference = $timeDifference / 1000;

                                                if ($currentOrder['symbol'] == $otherOrder['symbol'] && ($timeDifference >= 0 && $timeDifference <= 60)) {
                                                    $matchingOrders[] = $otherOrder;
                                                }
                                            }
                                        }
                                    }

                                    if (!empty($matchingOrders)) {
                                        $matchingOrders[] = $currentOrder;
                                        $bulkOrders[] = $matchingOrders;
                                        $uniqueBuyOrderIds[] = $currentBuyOrderID;
                                    }
                                }
                            }
                        }

                        
                        foreach ($row['cost_avg_array'] as $currentOrder) {
                            $currentBuyOrderID = (string)$currentOrder['buy_order_id'];
                            
                            if (in_array($currentBuyOrderID, $uniqueBuyOrderIds)) {
                                // $filteredOrders['symbol'] = $currentOrder['symbol'];
                                $filteredOrders[] = $currentOrder;
                            }
                        }

                        if (isset($uniqueBuyOrderIds)) {
                          
                            if (!empty($bulkOrders[0])) {
                                $firstBulkOrder = $bulkOrders[0];
                        
                                foreach ($firstBulkOrder as &$order) {
                                    $order['symbol'] = $row['symbol'];
                                    $order['admin_id'] = $row['admin_id'];
                                } 
                            }
                             
                        }

                        
                    }
                    
                    $row1['accumulations_data']=$total_profit;
            }else{
                $row1['accumulations_data']='---';
            }//end of if
                // if (in_array("bulk_orders")) {
                    // echo "<pre>"; print_r($row['cost_avg_array']);
                    // echo "<pre>"; print_r($uniqueBuyOrderIds);
                    // exit;
                    $row1['bulk_orders']=$filteredOrders;
                // }
               
                $row1["5_hour_max_market_price"] = $row["5_hour_max_market_price"];
                $row1["5_hour_min_market_price"] = $row["5_hour_min_market_price"];
                $row1["market_lowest_value"] = $row["market_lowest_value"];
                $row1["market_heighest_value"] = $row["market_heighest_value"];
                #$row1["admin_id"] = $row["admin_id"];

                $row1["application_mode"] = $row["application_mode"];
                $row1['count_avg_order'] =  $row['count_avg_order'];
                $row1["binance_order_id"] = $row["binance_order_id"];
                if(is_string($row['buy_date'])){
                    $row1["buy_date"] = (isset($row['buy_date']) ? $row["buy_date"]:'N/A');
                }else{
                    $row1["buy_date"] = ((isset($row['buy_date']) ? $row["buy_date"]->toDateTime()->format("Y-m-d g:i:s A") : ""));
                }
                
                $row1["buy_parent_id"] = (string) $row["buy_parent_id"];
                $row1["parent_status"] = ($row["parent_status"] ?? '');
                $row1["resume_status"] = ($row["resume_status"] ?? '');
                $row1["resume_order_id"] = ($row["resume_order_id"] ?? '');
                $row1["resume_order_arr"] = ($row["resume_order_arr"] ?? []);

                // Created Date
                if(!is_string($row["created_date"])){
                    if (!empty($row["created_date"])) {
                        $c_date = new DateTime($row["created_date"]->toDateTime()->format("c"));
                        $c_date->setTimeZone(new DateTimeZone($logged_timezone));
                        $c_date_format = $c_date->format('Y-m-d g:i:s A T');

                        $row1["created_date"] = time_elapsed_string($row["created_date"]->toDateTime()->format("Y-m-d g:i:s A") , new DateTimeZone($logged_timezone));
                        $row1["created_date_hover"] = $c_date_format;
                    } else {
                        $row1["created_date"] = '';
                        $row1["created_date_hover"] = '';
                    }
                }else{
                   if (!empty($row["created_date"])) {
                        $row1["created_date"] = time_elapsed_string($row["created_date"] , new DateTimeZone($logged_timezone));
                        $row1['created_date_hover'] = '';
                    } else {
                        $row1["created_date"] = '';
                        $row1["created_date_hover"] = '';
                    } 
                }
                

                // Modified Date
                if (!empty($row["modified_date"])) {
                    if(is_string($row['modified_date'])){
                        $row1['orderFilledTime'] = $row["modified_date"];
                        $m_date_format = $row["modified_date"];-
                        $row1["modified_date"] = time_elapsed_string($row["modified_date"]->toDateTime()->format("c") , new DateTimeZone($logged_timezone));
                        $row1["modified_date_hover"] = $m_date_format;
                    }else{
                        $row1['orderFilledTime'] = $row["modified_date"];
                        $m_date = new DateTime($row["modified_date"]->toDateTime()->format("c"));
                        $m_date->setTimeZone(new DateTimeZone($logged_timezone));
                        $m_date_format = $m_date->format('Y-m-d H:i:s A T');
                        $row1["modified_date"] = time_elapsed_string($row["modified_date"]->toDateTime()->format("c") , new DateTimeZone($logged_timezone));
                        $row1["modified_date_hover"] = $m_date_format;
                    }
                    
                } else {
                    $row1['orderFilledTime'] = '';
                    $row1["modified_date"] = '';
                    $row1["modified_date_hover"] = '';
                }

                $loss_percentage = $row["loss_percentage"];
                $custom_stop_loss_percentage = $row['custom_stop_loss_percentage'];

                if ($row['trigger_type'] != 'no') {
                    $row1["loss_percentage"] = ($custom_stop_loss_percentage != '' ? $custom_stop_loss_percentage : $loss_percentage);
                } else {
                    $row1["loss_percentage"] = ($loss_percentage != '' ? $loss_percentage : $custom_stop_loss_percentage);
                }

                $row1["custom_stop_loss_percentage"] = $row["custom_stop_loss_percentage"];
                $row1["deep_price_on_off"] = $row["deep_price_on_off"];
                $row1["deep_price_percentage_buy"] = $row["deep_price_percentage_buy"];
                $row1["defined_sell_percentage"] = $row["defined_sell_percentage"];
                $row1["iniatial_trail_stop"] = $row["iniatial_trail_stop"];
                $row1["is_sell_order"] = $row["is_sell_order"];
                $row1["is_manual_sold"] = (!empty($row["is_manual_sold"]) ? $row["is_manual_sold"] : '');
                $row1["is_lth_order"] = $row["is_lth_order"];
                $row1["lth_functionality"] = $row["lth_functionality"];
                $row1["lth_profit"] = $row["lth_profit"];
                $row1["market_sold_price"] = $row["market_sold_price"];
                $row1["market_sold_price_usd"] = $row["market_sold_price_usd"];
                $row1["market_value"] = $row["market_value"];
                $row1["market_value_usd"] = $row["market_value_usd"];
                $row1['profit'] = $profit;
                $row1['cost_avg'] = ($row['cost_avg'] ?? '');
                $row1['cost_avg_array'] = (isset($row['cost_avg_array'])? $row['cost_avg_array'] : array());
                $row1["order_level"] = $row["order_level"];
                $row1["order_mode"] = $row["order_mode"];
                $row1["order_type"] = $row["order_type"];
                $row1["price"] = $row["price"];
                $row1["purchased_price"] = $row["purchased_price"];
                $row1["quantity"] = $row["quantity"];
                $row1["sell_order_id"] = (string) $row["sell_order_id"];
                $row1["sell_price"] = $row["sell_price"];
                $row1["sell_profit_percent"] = $row["sell_profit_percent"];
                $row1["status"] = $row["status"];
                $row1["stop_loss_rule"] = $row["stop_loss_rule"];
                $row1["symbol"] = $row["symbol"];
                $row1["tradeId"] = $row["tradeId"];
                $row1["trading_ip"] = $row["trading_ip"];
                $row1["trading_status"] = $row["trading_status"];
                $row1["transactTime"] = $row["transactTime"];
                $row1["trigger_type"] = $row["trigger_type"];
                $sell_ids_arr = (array) $row['exchange_sell_order_ids_arr'];
                $buy_ids_arr = (array) $row['exchange_buy_order_ids_arr'];

                $row1["script_fix"] = ($row["script_fix"] ?? '');
                $row1["quantity_issue"] = ($row["quantity_issue"] ?? '');
                $row1["pick_parent"] = ($row["pick_parent"] ?? '');
                $row1["cancel_hour"] = ($row["cancel_hour"] ?? '');
                $row1["cavg_parent"] = ($row["cavg_parent"] ?? '');
                $row1["market_sold_price"] = ($row["market_sold_price"] ?? '');
                $row1["sell_market_price"] = ($row["sell_market_price"] ?? '');
                $row1["order_send_time"] = ($row["order_send_time"] ?? '');
                $row1["avg_purchase_price"] = ($row["avg_purchase_price"] ?? '');
                $row1["avg_orders_ids"] = ($row["avg_orders_ids"] ?? '');
                $row1["sell_date"] = ($row["sell_date"] ?? '');
                $row1["deep_price_on_off"] = ($row["deep_price_on_off"] ?? '');
                $row1["shifted_order_label"] = ($row["shifted_order_label"] ?? '');
                $row1['buy_fraction_filled_order_arr'] = ($row['buy_fraction_filled_order_arr'] ?? '');
                $row1['sell_fraction_filled_order_arr'] = ($row['sell_fraction_filled_order_arr'] ?? '');
                $row1['sell_on_sell_hit']   = ($row['sell_on_sell_hit'] ?? '');
                $row1['buy_on_buy_hit']   = ($row['buy_on_buy_hit'] ?? '');
                $row1['buy_trail_check']   = ($row['buy_trail_check'] ?? '');
                $row1['buy_trail_interval']   = ($row['buy_trail_interval'] ?? '');
                $row1['avg_sell_price']   = ($row['avg_sell_price'] ?? 0);
                if(isset($row['cost_avg_array'])){ // setting CA combine accumulation
                    //$accumulations_combine = $this->get_combine_accumulations((string)$row['_id'];);
                    //$row1['accumulations_combine'] = $accumulations_combine;
                }
                if(isset($row['accumulations'])){
                    $row1['accumulations']   = $row['accumulations']['profit'];    
                }
                
                if(isset($row["highPrice_range"]) && $row["highPrice_range"] > 0){
                    $exchange_market = isset($order_exchange)?$order_exchange:'binance';
                    $curr_market_price = get_current_market_prices($exchange_market,[$row['symbol']]);
                    $price_diff = $curr_market_price[$row['symbol']] - $row["highPrice_range"];
                    $percentage_high = $price_diff / $row["highPrice_range"] * 100;
                    $row1["highPrice_range"] = $percentage_high;
                }else{
                     $row1["highPrice_range"] = 0;
                }
                if(isset($row["lowPrice_range"]) && $row["lowPrice_range"] > 0){
                    $exchange_market = isset($order_exchange)?$order_exchange:'binance';
                    $curr_market_price = get_current_market_prices($exchange_market,[$row['symbol']]);
                    $price_diff = $curr_market_price[$row['symbol']] - $row["lowPrice_range"];
                    $percentage_low = $price_diff / $row["lowPrice_range"] * 100;
                    $row1["lowPrice_range"] = $percentage_low;
                }else{
                     $row1["lowPrice_range"] = 0;
                }
                $row1["CRT1"] = isset($row["CRT1"])?$row["CRT1"]:'';
                $row1["CRT7"] = isset($row["CRT7"])?$row["CRT7"]:'';
                $row1["parent_pause"] = isset($row["parent_pause"])?$row["parent_pause"]:'';
                $row1["pause_by"] = isset($row["pause_by"])?$row["pause_by"]:'';
                if(isset($row["randomize_sort"])){
                    $row1["randomize_sort"] =  $row["randomize_sort"]; 
                }
                // Resume array total profit
                if (!empty($row["resume_order_id"]) && !empty($row["resume_order_arr"]) && in_array('resume_completed', $order_status)) {
                    $total = 0;
                    $resumeArrCount = count($row["resume_order_arr"]);  
                    if ($resumeArrCount > 0) {
                        for($ri = 0; $ri < $resumeArrCount; $ri++){
                            $total += $row["resume_order_arr"][$ri]['resumeLossPercentage'];
                        }
                    }
                    $lastRow = false;
                    //lth_pause check
                    if (!empty($row["status"]) && ($row['status'] == 'pause' || $row['status'] == 'FILLED') && !empty($row["is_sell_order"]) && ($row['is_sell_order'] == 'pause' || $row['is_sell_order'] == 'resume_pause')) {
                      $lastRow = true;
                      //sold check
                    } else if (!empty($row["is_sell_order"]) && $row['is_sell_order'] == 'sold') {
                      $lastRow = true;
                    }

                    if ($lastRow) {
                        $tObj = [];
          
                      if (!empty($row['last_purchase'])) {
                        $tObj['resumePurchased'] = $row['last_purchase'];
                      } else {
                        $tObj['resumePurchased'] = !empty($row['purchased_price']) ? $row['purchased_price'] : '';
                      }
          
                      if (!empty($row['last_sell'])) {
                        $tObj['resumeSell'] = $row['last_sell'];
                      } else {
                        $tObj['resumeSell'] = !empty($row['market_sold_price']) ? $row['market_sold_price'] : '';
                      }
          
                      $resLastP = @!is_nan($tObj['resumePurchased']) ? $tObj['resumePurchased'] : 0;
                      $resLastS = @!is_nan($tObj['resumeSell']) ? $tObj['resumeSell'] : 0;
          
                      $pl = 0;
                      if ($resLastP == 0 || $resLastS == 0) {
                        $pl = 0;
                      } else {
                        $pl = ((($resLastS - $resLastP) * 100) / $resLastP);
                      }
                      $pl = @!is_nan($pl) ? $pl : 0;
          
                      $total = $total + $pl;
                    }
                    $row1['resume_order_arr_total'] = @!is_nan($total) ? number_format($total, 2) : 0;
                }//End Resume array total profit


                if (count($sell_ids_arr) > 1) {
                    $row1['duplicate_sell'] = 'yes';
                } else {
                    $row1['duplicate_sell'] = 'no';
                }

                if (count($buy_ids_arr) > 1) {
                    $row1['duplicate_buy'] = 'yes';
                } else {
                    $row1['duplicate_buy'] = 'no';
                }

                $row1['opportunityId'] = (!empty($row['opportunityId']) ? $row['opportunityId'] : '');
                $row1['mapped_order'] = (!empty($row['mapped_order']) && $row['mapped_order'] == 1 ? 'yes' : '');

                $id = $row['admin_id'];
                $data_user = $this->get_username_from_user($id);

                $row1['admin'] = $data_user;
                $_id = $order['_id'];

                // $error = $this->get_error_type($_id);
                // $row1['log'] = $error;

                // $loop_iteration = 1;
                // $is_order_sell = false;
                // if(in_array('sell_rule', $order_status)) {
                // foreach($row['cost_avg_array'] as $cavg_array){
                //     $cavg_array_count = count($row['cost_avg_array']);
                //     if(($cavg_array_count - $loop_iteration) <= 2){
                        
                //         if($cavg_array['order_sold'] == 'yes' && in_array($cavg_array['order_type'] , $sell_rule_array_in) && $is_order_sell == false)
                //         {
                //             $is_order_sell = true;
                //             array_push($new_order_arrray, $row1);
                //             $total_count = count($new_order_arrray);
                //         }
                //     }

                //     $loop_iteration++;
                // }
                // }else{
                //     array_push($new_order_arrray, $row1);
                // }
                array_push($new_order_arrray, $row1);
            }

            $data['json'] = json_encode($session_data);
            $data['total_count'] = $total_count;
            $data['serial_number'] = $serial_number;
            $new_order_arrray['average'] = $test_arr;
            $data['orders'] = $new_order_arrray;
            // echo "<pre>"; print_r($data['orders']); exit;
        }
        $coins = $this->mod_coins->get_all_coins();
        $data['order_levels'] = $this->get_order_levels();
        $data['coins'] = $coins;
        if(isset($_COOKIE['sheraz']) && $_COOKIE['sheraz'] == 1){
            echo '<pre>';print_r($data);exit;    
        }
        $this->stencil->paint('admin/reports/new_order_report', $data);

    }




    // public function getResumeSoldPL($order){
        
    //     return 0;
    // }

    public function reset_filters_report($type)
    {
        $this->session->unset_userdata('filter_order_data');
        if ($type == 'coin') {
            redirect(base_url() . 'admin/reports/coin_report');
        }if ($type == 'meta') {
            redirect(base_url() . 'admin/reports/meta_coin_report');
        }
        redirect(base_url() . 'admin/order_report');
    }

    // public function order_reports()
    // {

    //     //Login Check
    //     // error_reporting(E_ALL & ~E_NOTICE);
    //     // ini_set('display_errors', E_ALL & ~E_NOTICE);

    //     $this->mod_login->verify_is_admin_login();
    //     if ($this->input->post()) {
    //         $data_arr['filter_order_data'] = $this->input->post();
    //         $this->session->set_userdata($data_arr);
    //     }

    //     $session_data = $this->session->userdata('filter_order_data');
    //     if (isset($session_data)) {

    //         $collection = "buy_orders";
    //         if ($session_data['filter_by_coin']) {
    //             $search['symbol'] = $session_data['filter_by_coin'];
    //         }
    //         if ($session_data['filter_by_mode']) {
    //             $search['application_mode'] = $session_data['filter_by_mode'];
    //         }
    //         if ($session_data['filter_by_trigger']) {
    //             $search['trigger_type'] = $session_data['filter_by_trigger'];
    //         }
    //         if ($session_data['filter_by_rule'] != "") {
    //             $filter_by_rule = $session_data['filter_by_rule'];
    //             //$search['$or'] = array("buy_rule_number" => $filter_by_rule, "sell_rule_number" => $filter_by_rule);
    //             $search['$or'] = array(
    //                 array("buy_rule_number" => intval($filter_by_rule)),
    //                 array("sell_rule_number" => intval($filter_by_rule)),
    //             );

    //         }
    //         if ($session_data['filter_level'] != "") {
    //             $order_level = $session_data['filter_level'];
    //             $search['order_level'] = $order_level;
    //         }
    //         if ($session_data['filter_username'] != "") {
    //             $username = $session_data['filter_username'];
    //             $admin_id = $this->get_admin_id($username);
    //             $search['admin_id'] = (string) $admin_id;
    //         }
    //         if ($session_data['optradio'] != "") {
    //             if ($session_data['optradio'] == 'created_date') {
    //                 if ($session_data['selector'] == 'ASC') {
    //                     $oder_arr['created_date'] = 1;
    //                 } else {
    //                     $oder_arr['created_date'] = -1;
    //                 }

    //             } elseif ($session_data['optradio'] == 'modified_date') {
    //                 if ($session_data['selector'] == 'ASC') {
    //                     $oder_arr['modified_date'] = 1;
    //                 } else {
    //                     $oder_arr['modified_date'] = -1;
    //                 }

    //             }
    //         }

    //         //multi-select //Umer Abbas [12-11-19]
    //         if (!empty($session_data['filter_by_status'])) {
    //             $order_status = $session_data['filter_by_status'];

    //             $status_filter_arr = array();
    //             if (in_array('new', $order_status)) {
    //                 $status_filter_arr[] = 'new';
    //             }
    //             if (in_array('error', $order_status)) {
    //                 $status_filter_arr[] = 'error';
    //             }
    //             if (in_array('open', $order_status)) {
    //                 $status_filter_arr[] = 'submitted';
    //                 $status_filter_arr[] = 'FILLED';
    //                 $search['is_sell_order'] = 'yes';
    //             }
    //             if (in_array('sold', $order_status)) {
    //                 $status_filter_arr[] = 'FILLED';
    //                 $search['is_sell_order'] = 'sold';
    //                 // $collection = "sold_buy_orders";
    //             }
    //             if (in_array('LTH', $order_status)) {
    //                 $status_filter_arr[] = 'LTH';
    //             }
    //             if (in_array('error_in_sell', $order_status)) {
    //                 //TODO: find all orders with error in sell
    //                 // $error_in_sell_aggrigate = 'aggregate([
    //                 //     {
    //                 //     $lookup:
    //                 //         {
    //                 //             from: "orders",
    //                 //             let: { orders_status: "$sell_order_id", order_qty: "$ordered" },
    //                 //             pipeline: [
    //                 //                 { $match:
    //                 //                     { $expr:{
    //                 //                         $and:[
    //                 //                                 { $eq: [ "$stock_item",  "$$order_item" ] },
    //                 //                                 { $gte: [ "$instock", "$$order_qty" ] }
    //                 //                             ]
    //                 //                         }
    //                 //                     },
    //                 //                   $limit: 20
    //                 //                 },
    //                 //                 { $project: { stock_item: 0, _id: 0 } }
    //                 //             ],
    //                 //             as: "stockdata"
    //                 //         }
    //                 //     }
    //                 // ])';

    //                 $orders_arr = $connetct->buy_orders->$error_in_sell_aggrigate;
    //                 $orders_arr = iterator_to_array($orders_arr);

    //             }
    //             $status_filter_arr = array_unique($status_filter_arr);
    //             $status_filter_arr = array_values($status_filter_arr); //to reindex the array

    //             $search['status'] = array('$in' => $status_filter_arr);
    //         }
    //         // echo '<pre>';
    //         // print_r($search['status']);
    //         // die('working on multi select filter');

    //         if ($session_data['filter_by_start_date'] != "" && $session_data['filter_by_end_date'] != "") {

    //             $created_datetime = date('Y-m-d G:i:s', strtotime($session_data['filter_by_start_date']));
    //             $orig_date = new DateTime($created_datetime);
    //             $orig_date = $orig_date->getTimestamp();
    //             $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

    //             $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_data['filter_by_end_date']));
    //             $orig_date22 = new DateTime($created_datetime22);
    //             $orig_date22 = $orig_date22->getTimestamp();
    //             $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
    //             $search['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
    //         }
    //         //Umer Abbas [1-11-19]

    //         if (!empty($session_data['filter_by_nature'])) {
    //             if ($session_data['filter_by_nature'] == 'auto') {

    //                 $search['trigger_type'] = array('$ne' => 'no');
    //                 if ($search['status'] == 'new') {
    //                     $search['buy_parent_id'] = array('$exists' => true);
    //                 }

    //             } else if ($session_data['filter_by_nature'] == 'manual') {

    //                 $search['trigger_type'] = 'no';

    //             } else if ($session_data['filter_by_nature'] == 'parent') {

    //                 $search['parent_status'] = 'parent';
    //                 if ($search['status'] == 'new') {
    //                     $search['buy_parent_id'] = array('$exists' => true);
    //                 }

    //             } else {
    //                 $search['parent_status'] = array('$ne' => 'parent');
    //             }
    //         } else {
    //             $search['parent_status'] = array('$ne' => 'parent');
    //         }

    //         //$search['status'] = array('$ne' => 'canceled');
    //         // echo "<pre>";
    //         // print_r($search);
    //         // exit;

    //         // $qr_sold = array('skip' => $skip_sold, 'sort' => $oder_arr, 'limit' => $limit);
    //         // $qr_pending = array('skip' => $skip_pending, 'sort' => $oder_arr, 'limit' => $limit);

    //         $connetct = $this->mongo_db->customQuery();

    //         if ($session_data['filter_by_exchange'] != "") {
    //             $order_exchange = $session_data['filter_by_exchange'];
    //             if ($order_exchange != 'binance') {
    //                 $collection_str1 = 'sold_buy_orders_' . $search['exchange'];
    //                 $collection_str2 = 'buy_orders_' . $search['exchange'];
    //             } else {
    //                 $collection_str1 = 'sold_buy_orders';
    //                 $collection_str2 = 'buy_orders';
    //             }
    //         }

    //         //Umer Abbas [1-11-19]
    //         if (!empty($search['exchange'])) {
    //             if ($search['exchange'] != 'binance') {
    //                 $collection_str1 = 'sold_buy_orders_' . $search['exchange'];
    //                 $collection_str2 = 'buy_orders_' . $search['exchange'];
    //                 $sold_count = $connetct->$collection_str1->count($search);
    //                 $pending_count = $connetct->$collection_str2->count($search);
    //             } else {
    //                 $sold_count = $connetct->sold_buy_orders->count($search);
    //                 $pending_count = $connetct->buy_orders->count($search);
    //             }
    //         } else {

    //             // print_r($search);
    //             $sold_count = $connetct->sold_buy_orders->count($search);
    //             $pending_count = $connetct->buy_orders->count($search);
    //         }

    //         $total_count = $sold_count + $pending_count;

    //         /////////////////////// PAGINATION CODE START HERE /////////////////////////////////////
    //         $this->load->library("pagination");
    //         $config = array();
    //         $config["base_url"] = SURL . "admin/reports/order_reports";
    //         $config["total_rows"] = $total_count;
    //         $config['per_page'] = 100;
    //         $config['num_links'] = 3;
    //         $config['use_page_numbers'] = true;
    //         $config['uri_segment'] = 4;
    //         $config['reuse_query_string'] = true;
    //         $config["first_tag_open"] = '<li>';
    //         $config["first_tag_close"] = '</li>';
    //         $config["last_tag_open"] = '<li>';
    //         $config["last_tag_close"] = '</li>';
    //         $config['next_link'] = '&raquo;';
    //         $config['next_tag_open'] = '<li>';
    //         $config['next_tag_close'] = '</li>';
    //         $config['prev_link'] = '&laquo;';
    //         $config['prev_tag_open'] = '<li>';
    //         $config['prev_tag_close'] = '</li>';
    //         $config['first_link'] = 'First';
    //         $config['last_link'] = 'Last';
    //         $config['full_tag_open'] = '<ul class="pagination">';
    //         $config['full_tag_close'] = '</ul>';
    //         $config['cur_tag_open'] = '<li class="active"><a href="#"><b>';
    //         $config['cur_tag_close'] = '</b></a></li>';
    //         $config['num_tag_open'] = '<li>';
    //         $config['num_tag_close'] = '</li>';
    //         $this->pagination->initialize($config);
    //         $page = $this->uri->segment(4);

    //         if (!isset($page)) {$page = 1;}
    //         $start = ($page - 1) * $config["per_page"];
    //         $skip = $start;
    //         $skip_sold = $skip;
    //         $skip_pending = $skip;
    //         $limit = $config["per_page"];
    //         ////////////////////////////End Pagination Code///////////////////////////////////////

    //         $data['pagination'] = $this->pagination->create_links();

    //         // echo '<pre>';
    //         // print_r($data['pagination']);
    //         // die('working');

    //         /////////////////////// PAGINATION CODE END HERE /////////////////////////////////////

    //         // $sold_percentage = ($sold_count / $total_count) * 100;
    //         // $pending_percentage = ($pending_count / $total_count) * 100;

    //         // $pending_limit = (500 / 100) * $pending_percentage;
    //         // $sold_limit = (500 / 100) * $sold_percentage;

    //         $pending_options = array('skip' => $skip_pending, 'sort' => array('modified_date' => -1), 'limit' => intval($limit));

    //         $sold_options = array('skip' => $skip_sold, 'sort' => array('modified_date' => -1), 'limit' => intval($limit));

    //         // $skip_sold = $skip_sold +(int)$sold_limit;
    //         // $skip_pending = $skip_pending +(int)$pending_limit;
    //         // $this->session->set_userdata(array('skip_sold'=>$skip_sold,'skip_pending'=>$skip_pending));

    //         //Umer Abbas [1-11-19]
    //         if (!empty($search['exchange'])) {
    //             if ($search['exchange'] != 'binance') {
    //                 $collection_str1 = 'sold_buy_orders_' . $search['exchange'];
    //                 $collection_str2 = 'buy_orders_' . $search['exchange'];
    //                 $pending_curser = $connetct->$collection_str1->find($search, $pending_options);
    //                 $sold_curser = $connetct->$collection_str2->find($search, $sold_options);
    //             } else {
    //                 $pending_curser = $connetct->buy_orders->find($search, $pending_options);
    //                 $sold_curser = $connetct->sold_buy_orders->find($search, $sold_options);
    //             }
    //         } else {
    //             $pending_curser = $connetct->buy_orders->find($search, $pending_options);
    //             $sold_curser = $connetct->sold_buy_orders->find($search, $sold_options);
    //         }

    //         $pending_arr = iterator_to_array($pending_curser);
    //         $sold_arr = iterator_to_array($sold_curser);
    //         $orders = array_merge_recursive($pending_arr, $sold_arr);

    //         //added by 06/08/2019

    //         if ($this->input->post('optradio') != "") {

    //             if ($this->input->post('optradio') == 'created_date') {

    //                 if ($this->input->post('selector') == 'ASC') {

    //                     foreach ($orders as $key => $part) {
    //                         $sort[$key] = (string) $part['created_date'];
    //                     }

    //                     array_multisort($sort, SORT_ASC, $orders);

    //                 } else {

    //                     foreach ($orders as $key => $part) {
    //                         $sort[$key] = (string) $part['created_date'];

    //                     }

    //                     array_multisort($sort, SORT_DESC, $orders);
    //                 }

    //             } elseif ($this->input->post('optradio') == 'modified_date') {

    //                 if ($this->input->post('selector') == 'ASC') {
    //                     foreach ($orders as $key => $part) {
    //                         $sort[$key] = (string) $part['modified_date'];
    //                     }

    //                     array_multisort($sort, SORT_ASC, $orders);
    //                 } else {
    //                     foreach ($orders as $key => $part) {
    //                         $sort[$key] = (string) $part['modified_date'];
    //                     }

    //                     array_multisort($sort, SORT_DESC, $orders);
    //                 }

    //             }
    //         } else {
    //             foreach ($orders as $key => $part) {
    //                 $sort[$key] = (string) $part['modified_date'];
    //             }

    //             array_multisort($sort, SORT_DESC, $orders);
    //         }

    //         //end

    //         $new_order_arrray = array();
    //         foreach ($orders as $order) {
    //             $id = $order['admin_id'];
    //             $data_user = $this->get_username_from_user($id);

    //             $order['admin'] = $data_user;
    //             $_id = $order['_id'];

    //             $error = $this->get_error_type($_id);
    //             $order['log'] = $error;
    //             array_push($new_order_arrray, $order);
    //         }
    //         // echo "<pre>";
    //         // print_r($new_order_arrray);exit;
    //         // $new_order_arrray['average'] = $test_arr;
    //         $orders = $new_order_arrray;

    //     }
    //     $coins = $this->mod_coins->get_all_coins();
    //     $data['coins'] = $coins;
    //     $this->stencil->paint('admin/reports/my_custom_order_report', $data);
    // } //End of order_reports

    // end of order_book_admin

    public function get_username_from_user($id)
    {

        // echo $id;
        // echo "<br>";
        if (preg_match('/^[a-f\d]{24}$/i', $id)) {
            $customer = $this->mod_report->get_customer($this->mongo_db->mongoId($id));
        }
        // $customer_name = ucfirst($customer['first_name']).' '.ucfirst($customer['last_name']);
        // $customer_username = $customer['username'];

        return $customer;

    }

    public function get_username_from_user_2($id)
    {
        if(!empty($id)){
            $id = $this->mongo_db->mongoId($id);
            $this->mongo_db->where(['_id'=> $id]);
            $get_users = $this->mongo_db->get('users');
            $users_arr = iterator_to_array($get_users);
            if(!empty($users_arr)){
                $username = $users_arr[0]['username'];
                unset($users_arr, $get_users);
                return $username;
            }
        }
        return '';
    }

    // public function get_error_type($id)
    // {
    //     $this->mongo_db->limit(1);
    //     $this->mongo_db->order_by(array('_id' => -1));
    //     $this->mongo_db->where(array('order_id' => $id, 'type' => array('$in' => array('buy_error', 'sell_error'))));
    //     $mongo_obj = $this->mongo_db->get('orders_history_log');
    //     $orders = iterator_to_array($mongo_obj);
    //     return $orders[0];

    // }

    // public function test_error_type($id)
    // {
    //     $this->mongo_db->where(array('order_id' => $id, 'type' => array('$in' => array('buy_error', 'sell_error'))));
    //     $mongo_obj = $this->mongo_db->get('orders_history_log');
    //     $orders = iterator_to_array($mongo_obj);
    //     echo "<pre>";
    //     print_r($orders);exit;

    // }

    public function get_admin_id($username)
    {
        $customer = $this->mod_report->get_customer_by_username($username);
        return $customer['_id'];
    }
    public function get_admin_ids_excluding($usernames_array)
    {
        $customer = $this->mod_report->get_admin_ids_excluding($usernames_array);
        $admin_ids_exclude=array();
        if($customer){
            foreach($customer as $value){
                array_push($admin_ids_exclude,(string)$value['_id']);
            }
        }
       return $admin_ids_exclude;
    }
    public function get_admin_ids_excluding_investment_blocked($exchange)
    {
        $customer = $this->mod_report->get_admin_ids_excluding_investment_blocked($exchange);
        $admin_ids_exclude=array();
        if($customer){
            foreach($customer as $value){
                array_push($admin_ids_exclude,(string)$value['admin_id']);
            }
        }
       return $admin_ids_exclude;
    }
    
    public function get_order_levels()
    {
        $mongo_obj = $this->mongo_db->get('order_levels');
        $levels = iterator_to_array($mongo_obj);

        $sorted_levels = [];
        foreach ($levels as $level) {
            $arr = explode('_', $level['level']);
            $obj = $level;
            $obj['sort_id'] = $arr[1];
            $sorted_levels[] = $obj;
        }
        $field = array_column($sorted_levels, 'sort_id');
        array_multisort($field, SORT_ASC, $sorted_levels);

        return $sorted_levels;
    }

    public function get_all_usernames_ajax()
    {
        $this->mongo_db->sort(array('_id' => -1));
        $get_users = $this->mongo_db->get('users');

        $users_arr = iterator_to_array($get_users);

        $user_name_array = array_column($users_arr, 'username');

        echo json_encode($user_name_array);
        exit;
    }

    // public function get_user_info()
    // {
    //     $id = $this->input->post('user_id');
    //     $customer = $this->mod_report->get_customer($id);

    //     if ($customer['last_login_datetime'] == null || $customer['last_login_datetime'] == "") {
    //         $login_time = 'N/A';
    //     } else if (gettype($customer['last_login_datetime']) == 'object') {
    //         $login_time = $customer['last_login_datetime']->toDateTime();
    //         $login_time = $login_time->format("jS F Y H:i:s");
    //     } else {
    //         $login_time = date("jS F Y H:i:s", strtotime($customer['last_login_datetime']));
    //     }

    //     $response = '<div class="col-12 col-sm-6 col-md-4 col-lg-12">
	// 					      <div class="our-team">
	// 					        <div class="picture">
	// 					          <img class="img-fluid" src="' . SURL . "assets/profile_images/" . (!empty($customer['profile_image']) ? $customer['profile_image'] : "user.png") . '">
	// 					        </div>
	// 					        <div class="team-content">
	// 					          <h3 class="name">' . ucfirst($customer['first_name']) . ' ' . ucfirst($customer['last_name']) . '</h3>
	// 										<h5><span class="label label-info">@' . $customer['username'] . '</span></h5>
	// 					          <h4 class="title">Last Login: ' . $login_time . '</h4>
	// 					        </div>
	// 					      </div>
	// 					    </div>
	// 							<div class="table-responsive">
	// 								  <table class="table">
	// 										<tr>
	// 											<th>User Id</td>
	// 											<td>' . $customer['_id'] . '</td>
	// 										<tr>

	// 										<tr>
	// 											<th>Email Address</td>
	// 											<td>' . $customer['email_address'] . '</td>
	// 										<tr>

	// 										<tr>
	// 											<th>Trading Ip</td>
	// 											<td>' . $customer['trading_ip'] . '</td>
	// 										<tr>
	// 										<tr>
	// 											<th>Application Mode</td>
	// 											<td>' . $customer['application_mode'] . '</td>
	// 										<tr>
	// 										<tr>
	// 											<th></td>
	// 											<td>' . (($customer['special_role'] == 1) ? "<label class='label label-success'>Special User</label>" : "<label class='label label-warning'>Normal User</label>") . '</td>
	// 										<tr>
	// 								  </table>
	// 								</div>';

    //     echo $response;
    //     exit;

    // }
    public function get_csv_bkp()
    {

        // ini_set("display_errors", E_ERROR);
        // error_reporting(E_ERROR);

        //Login Check
        $this->mod_login->verify_is_admin_login();

        $input_search_filters = $this->input->post();
        if (!empty($input_search_filters)) {

            $filter_is_empty = true;
            foreach ($input_search_filters as $kee => $filter_key) {
                if (!empty($filter_key)) {
                    if ($kee != 'optradio' && $kee != 'selector') {
                        $filter_is_empty = false; 
                    }
                }
            }
            if ($filter_is_empty) {
                $this->reset_filters_report('all');
            }

            $data_arr['filter_order_data'] = $this->input->post();
            $this->session->set_userdata($data_arr);
        }

        $session_data = $this->session->userdata('filter_order_data');

        if (isset($session_data)) {

            $collection = "buy_orders";
             if ($session_data['filter_by_coin']) {
                if(!empty($session_data['filter_by_coin']) && $session_data['filter_by_coin'][0] !=""){
                $search['symbol']['$in'] = $session_data['filter_by_coin'];
                }
            }
            if ($session_data['filter_by_mode']) {
                $search['application_mode'] = $session_data['filter_by_mode'];
            }
            if ($session_data['filter_by_trigger']) {
                $search['trigger_type'] = $session_data['filter_by_trigger'];
            }
            if ($session_data['filter_by_rule'] != "") {
                $filter_by_rule = $session_data['filter_by_rule'];
                //$search['$or'] = array("buy_rule_number" => $filter_by_rule, "sell_rule_number" => $filter_by_rule);
                $search['$or'] = array(
                    array("buy_rule_number" => intval($filter_by_rule)),
                    array("sell_rule_number" => intval($filter_by_rule)),
                );

            }
            if (!empty($session_data['filter_level'])) {
                $order_level = $session_data['filter_level'];
                $search['order_level']['$in'] = $order_level;
            }
            if (!empty($session_data['filter_by_coinNature'])) {
                if($session_data['filter_by_coinNature'] == 'btc'){
                    $type = 'BTC';
                    $search['$and'] = [ 
                              ['symbol'=>new MongoDB\BSON\Regex(".*{$type}.*", 'i')], 
                              ['symbol'=>['$ne'=> 'BTCUSDT']],
                          ];
                }elseif($session_data['filter_by_coinNature'] == 'usdt'){
                    $type = 'USDT';
                    $search['symbol']=new MongoDB\BSON\Regex(".*{$type}.*", 'i');
                }
            }
            if ($session_data['filter_username'] != "") {
                $username = $session_data['filter_username'];
                $admin_id = $this->get_admin_id($username);
                $search['admin_id'] = (string) $admin_id;
            }
            if (!empty($session_data['opportunityId'])) {
                $search['opportunityId'] = $session_data['opportunityId'];
            }
            //multi-select //Umer Abbas [12-11-19]
            if (!empty($session_data['filter_by_status'])) {
                $order_status = $session_data['filter_by_status'];

                $status_filter_arr = array();
                if (in_array('new', $order_status)) {
                    $search['parent_status']['$ne'] = 'parent';
                    $status_filter_arr[] = 'new';
                    $status_filter_arr[] = 'new_ERROR';
                    $search['price']['$ne'] = '';

                }
                if (in_array('error', $order_status)) {
                    $status_filter_arr[] = 'error';
                }
                if (in_array('open', $order_status)) {
                    // $status_filter_arr[] = 'submitted';
                    $status_filter_arr[] = 'FILLED';
                    // $status_filter_arr[] = 'FILLED_ERROR';
                    $search['resume_order_id'] = ['$exists' => false];
                    $search['is_sell_order'] = 'yes';
                }
                $start_date_check = 0;
                $end_date_check = 0;
            if ($session_data['filter_by_start_date_m'] != "" && $session_data['filter_by_end_date_m'] != "") {
                // $created_datetime = date('Y-m-d G:i:s', strtotime($session_data['filter_by_start_date_m']));
                // $orig_date = new DateTime($created_datetime);
                // $orig_date = $orig_date->getTimestamp();
                // $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
                $start_date_check = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s',strtotime($session_data['filter_by_start_date_m']))); 
                // $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_data['filter_by_end_date_m']));
                // $orig_date22 = new DateTime($created_datetime22);
                // $orig_date22 = $orig_date22->getTimestamp();
                // $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
                $end_date_check = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s',strtotime($session_data['filter_by_end_date_m'])));
                //echo '<pre>start'.$start_date_check; 
                //echo '<pre>end'.$end_date_check; exit;
                $search['modified_date'] = array('$gte' => $start_date_check, '$lte' => $end_date_check);
            }
                if (in_array('sold', $order_status)) {
                    $status_filter_arr[] = 'FILLED';
                    if ($session_data['filter_by_start_date_m'] != "" && $session_data['filter_by_end_date_m'] != "") {
                        $search['sell_date'] = array('$gte' => $start_date_check, '$lte' => $end_date_check);
                        //$search['sell_date'] = array('$gte' => $start_date_check, '$lte' => $end_date_check);
                    }elseif ($session_data['filter_by_start_date'] != "" && $session_data['filter_by_end_date'] != "") {
                        $search['created_date'] = array('$gte' => $start_date_new, '$lte' => $end_date_new);
                        //$search['created_date'] = array('$gte' => $start_date_new, '$lte' => $end_date_new);
                    }
                    $search['is_sell_order'] = 'sold';
                    $search['is_manual_sold'] = ['$ne' => 'yes'];
                    // $collection = "sold_buy_orders";
                }
                if (in_array('sold_manually', $order_status)) {
                    $status_filter_arr[] = 'FILLED';
                    $search['is_sell_order'] = 'sold';
                    $search['is_manual_sold'] = 'yes';
                    // $collection = "sold_buy_orders";
                }
                if (in_array('LTH_sold', $order_status)) {
                    $status_filter_arr[] = 'FILLED';
                    $search['is_sell_order'] = 'sold';
                    $search['is_lth_order'] = 'yes';
                    // $search['cost_avg'] = ['$exists' => false];
                }
                if (in_array('lth_pause', $order_status)) {
                    $status_filter_arr[] = 'FILLED';
                    $search['is_sell_order'] = 'pause';
                    // $search['is_sell_order'] = ['$in' => ['pause', 'resume_pause', 'resume_complete'] ];
                }
                if (in_array('LTH', $order_status)) {
                    $status_filter_arr[] = 'LTH';
                    $search['is_sell_order'] = 'yes';
                }
                if (in_array('takingparent', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'takingOrder';
                }
                if (in_array('takeparent', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'new';
                }
                if (in_array('play', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'new';
                    $search['pause_status'] = 'play';
                }
                if (in_array('pause', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'new';
                    $search['pause_status'] = 'pause';
                }
                if (in_array('pick_parent_yes', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $search['pick_parent'] = 'yes';
                }
                if (in_array('pick_parent_no', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $search['pick_parent'] = 'no';
                }
                if (in_array('canceled', $order_status)) {
                    $status_filter_arr[] = 'canceled';
                }
                if (in_array('error_in_sell', $order_status)) {
                    // $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'error';
                }
                
                if (in_array('new_ERROR', $order_status)) {
                    $status_filter_arr[] = 'new_ERROR';
                }
                if (in_array('FILLED_ERROR', $order_status)) {
                    $status_filter_arr[] = 'FILLED_ERROR';
                }
                if (in_array('submitted_ERROR', $order_status)) {
                    $status_filter_arr[] = 'submitted_ERROR';
                }
                if (in_array('LTH_ERROR', $order_status)) {
                    $status_filter_arr[] = 'LTH_ERROR';
                }
                if (in_array('canceled_ERROR', $order_status)) {
                    $status_filter_arr[] = 'canceled_ERROR';
                }
                if (in_array('submitted_for_sell', $order_status)) {
                    $status_filter_arr[] = 'submitted_for_sell';
                }
                if (in_array('fraction_submitted_sell', $order_status)) {
                    $status_filter_arr[] = 'fraction_submitted_sell';
                }
                if (in_array('credentials_ERROR', $order_status)) {
                    $status_filter_arr[] = 'credentials_ERROR';
                }
                if (in_array('fraction_ERROR', $order_status)) {
                    $status_filter_arr[] = 'fraction_ERROR';
                }
                if (in_array('SELL_ID_ERROR', $order_status)) {
                    $status_filter_arr[] = 'SELL_ID_ERROR';
                }
                if (in_array('resume_paused', $order_status)) {
                    $search['is_sell_order'] = 'pause';
                    $search['show_order'] = ['$ne' => 'no'];
                }
                if (in_array('resumed', $order_status)) {
                    // $search['resume_order_id'] = ['$exists' => true];
                    $search['is_sell_order'] = 'resume_pause';
                    $search['resume_order_arr'] = ['$exists' => false];
                    $search['show_order'] = ['$ne' => 'no'];
                }
                if (in_array('resume_in_progress', $order_status)) {
                    $search['is_sell_order'] = 'resume_pause';
                    $search['resume_order_arr.0'] = ['$exists' => true];
                    $search['show_order'] = ['$ne' => 'no'];
                }
                if (in_array('resume_completed', $order_status)) {
                    $search['resume_status'] = 'completed';
                }
                if (in_array('resume_child_sold', $order_status)) {
                    $search['status'] = 'FILLED';
                    $search['is_sell_order'] = 'yes';
                    $search['resume_order_id'] = ['$exists' => true];
                }

                if (in_array('quantity_issue', $order_status)) {
                    $search['quantity_issue'] = '1';
                }
                 if (in_array('cost_averageSold', $order_status)) {
                    $search['cavg_parent'] = 'yes';            
                    $search['cost_avg'] = 'completed';
                    //$search['count_avg_order'] = ['$exists'=> true]; this was commented on order of shehzad by sheraz
                }
                if (in_array('cost_averageOpen', $order_status)) {
                    $search['cavg_parent'] = 'yes';
                    $search['cost_avg']['$ne'] = 'completed';
                    $search['is_sell_order']['$ne'] = 'sold';
                    $search['status'] = ['$ne'=>'canceled'];
                    //$search['count_avg_order'] = ['$exists'=> true]; this was commented on order of shehzad by sheraz
                    
                }
                if (in_array('cost_average_sold_status', $order_status)) {
                    $search['cavg_parent'] = 'yes';
                    $search['cost_avg'] = 'yes';
                    $search['trading_status'] = 'complete';
                    $search['status'] = 'FILLED';
                    
                }

                if (in_array('errors_tab', $order_status)) {
                    $search['status'] = ['$nin'=>['CA_TAKING_CHILD','new', 'FILLED', 'fraction_submitted_buy', 'canceled', 'LTH', 'submitted', 'submitted_for_sell', 'fraction_submitted_sell']];
                }

                $status_filter_arr = array_unique($status_filter_arr);
                $status_filter_arr = array_values($status_filter_arr); //to reindex the array

                if (!in_array('parent', $order_status) && !in_array('takingparent', $order_status) && !in_array('takeparent', $order_status) && !in_array('play', $order_status) && !in_array('pause', $order_status) && !in_array('pick_parent_yes', $order_status) && !in_array('pick_parent_no', $order_status)) {
                    $search['parent_status'] = array('$ne' => 'parent');
                }

                if (!empty($status_filter_arr)) {
                    $search['status'] = array('$in' => $status_filter_arr);
                }
            }


            if ($session_data['filter_by_start_date'] != "" && $session_data['filter_by_end_date'] != "") {

                $created_datetime = date('Y-m-d H:i:s', strtotime($session_data['filter_by_start_date']));
                $orig_date = new DateTime($created_datetime);
                $orig_date = $orig_date->getTimestamp();
                $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

                $created_datetime22 = date('Y-m-d H:i:s', strtotime($session_data['filter_by_end_date']));
                $orig_date22 = new DateTime($created_datetime22);
                $orig_date22 = $orig_date22->getTimestamp();
                $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
                $search['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
            }

            if ($session_data['filter_by_start_date_m'] != "" && $session_data['filter_by_end_date_m'] != "") {

                $created_datetime = date('Y-m-d H:i:s', strtotime($session_data['filter_by_start_date_m']));
                $orig_date = new DateTime($created_datetime);
                $orig_date = $orig_date->getTimestamp();
                $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

                $created_datetime22 = date('Y-m-d H:i:s', strtotime($session_data['filter_by_end_date_m']));
                $orig_date22 = new DateTime($created_datetime22);
                $orig_date22 = $orig_date22->getTimestamp();
                $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
                $search['modified_date'] = array('$gte' => $start_date, '$lte' => $end_date);
            }
            //Umer Abbas [1-11-19]

            if (!empty($session_data['filter_by_nature'])) {
                if ($session_data['filter_by_nature'] == 'auto') {

                    $search['trigger_type'] = array('$ne' => 'no');

                } else if ($session_data['filter_by_nature'] == 'manual') {

                    $search['trigger_type'] = 'no';

                }
            }

            //show parents
            if ((!empty($session_data['show_parents']) && $session_data['show_parents'] == 'yes') || (!empty($search['parent_status']) && $search['parent_status'] == 'parent')) {
                $search['parent_status'] = 'parent';
            } else {
                $search['parent_status'] = ['$exists' => false];
            }

            if ($session_data['optradio'] != "") {
                if ($session_data['optradio'] == 'created_date') {
                    $oder_arr['optradio'] = 'created_date';
                    if ($session_data['selector'] == 'ASC') {
                        $oder_arr['selector'] = 1;
                        $oder_arr['created_date'] = 1;
                    } else {
                        $oder_arr['selector'] = -1;
                        $oder_arr['created_date'] = -1;
                    }
                } elseif ($session_data['optradio'] == 'modified_date') {
                    $oder_arr['optradio'] = 'modified_date';
                    if ($session_data['selector'] == 'ASC') {
                        $oder_arr['selector'] = 1;
                        $oder_arr['modified_date'] = 1;
                    } else {
                        $oder_arr['selector'] = -1;
                        $oder_arr['modified_date'] = -1;
                    }

                }
            }
            $connetct = $this->mongo_db->customQuery();

            if ($session_data['filter_by_exchange'] != "") {
                $order_exchange = $session_data['filter_by_exchange'];
                if ($order_exchange != 'binance') {
                    $collection_str1 = 'sold_buy_orders_' . $order_exchange;
                    $collection_str2 = 'buy_orders_' . $order_exchange;
                } else {
                    $collection_str1 = 'sold_buy_orders';
                    $collection_str2 = 'buy_orders';
                }
            } else {
                $collection_str1 = 'sold_buy_orders';
                $collection_str2 = 'buy_orders';
            }
            // echo $collection_str1. "=============".$collection_str2;
            // exit;

            if (empty($search)) {
                $this->reset_filters_report('all');
            }

            if (empty($session_data['filter_by_status'])) {
                $costAvgParentFilter = array_merge($search, [
                    'cost_avg' => ['$exists' => true],
                    'cavg_parent' => 'yes',
                ]);
                $tempSearchQuery['$or'][] = $costAvgParentFilter;

                //Set for second condition
                $search = array_merge($search, [
                    'cost_avg' => ['$nin' => ['taking_child', 'yes', 'completed']],
                    'cavg_parent' => ['$exists' => false],
                ]);
            }

            $tempSearchQuery['$or'][] = $search;
            $search = $tempSearchQuery;

            unset($tempSearchQuery);

            $pending_curser = $connetct->$collection_str2->find($search);
            $sold_curser = $connetct->$collection_str1->find($search);

            $pending_arr = iterator_to_array($pending_curser);
            $sold_arr = iterator_to_array($sold_curser);
            $orders = array_merge_recursive($pending_arr, $sold_arr);

            if ($session_data['optradio'] != "") {
                if ($session_data['optradio'] == 'created_date') {
                    if ($session_data['selector'] == 'ASC') {
                        foreach ($orders as $key => $part) {
                            $sort[$key] = (string) $part['created_date'];
                        }
                        array_multisort($sort, SORT_ASC, $orders);
                    } else {
                        foreach ($orders as $key => $part) {
                            $sort[$key] = (string) $part['created_date'];
                        }
                        array_multisort($sort, SORT_DESC, $orders);
                    }
                } elseif ($session_data['optradio'] == 'modified_date') {
                    if ($session_data['selector'] == 'ASC') {
                        foreach ($orders as $key => $part) {
                            $sort[$key] = (string) $part['modified_date'];
                        }
                        array_multisort($sort, SORT_ASC, $orders);
                    } else {
                        foreach ($orders as $key => $part) {
                            $sort[$key] = (string) $part['modified_date'];
                        }
                        array_multisort($sort, SORT_DESC, $orders);
                    }
                }
            } else {
                foreach ($orders as $key => $part) {
                    $sort[$key] = (string) $part['modified_date'];
                }
                array_multisort($sort, SORT_DESC, $orders);
            }
            //end

            $logged_user_id = $this->session->userdata('admin_id');
            $logged_timezone = get_user_timezone($logged_user_id);



            //interesting question to read for memory problems
            // https://stackoverflow.com/questions/1742215/php-foreach-loops-and-ressources

            $ordersArrLength = count($orders);
            $new_order_arrray = [];
            for ($i = 0; $i < $ordersArrLength; $i++) {
                $row = &$orders[$i];
                // if(isset($_COOKIE['sheraz']) && $_COOKIE['sheraz'] == 1){
                //  echo '<pre>'; print_r($row);exit;   
                // }
                $row1['_id'] = (string) $row['_id'];
                $row1["admin_id"] = $row["admin_id"];

                $row1["5_hour_max_market_price"] = $row["5_hour_max_market_price"];
                $row1["5_hour_min_market_price"] = $row["5_hour_min_market_price"];
                $row1["market_lowest_value"] = $row["market_lowest_value"];
                $row1["market_heighest_value"] = $row["market_heighest_value"];
                #$row1["admin_id"] = $row["admin_id"];

                $row1["application_mode"] = $row["application_mode"];
                $row1["binance_order_id"] = $row["binance_order_id"];
                $row1["buy_date"] = ((isset($row['buy_date']) ? $row["buy_date"]->toDateTime()->format("Y-m-d g:i:s A") : ""));
                $row1["buy_parent_id"] = (string) $row["buy_parent_id"];
                $row1["parent_status"] = ($row["parent_status"] ?? '');
                $row1["resume_status"] = ($row["resume_status"] ?? '');
                $row1["resume_order_id"] = ($row["resume_order_id"] ?? '');
                $row1["resume_order_arr"] = ($row["resume_order_arr"] ?? '');


                // Created Date
                if (!empty($row["created_date"])) {
                    $c_date = new DateTime($row["created_date"]->toDateTime()->format("c"));
                    $c_date->setTimeZone(new DateTimeZone($logged_timezone));
                    $c_date_format = $c_date->format('Y-m-d g:i:s A T');

                    $row1["created_date"] = time_elapsed_string($row["created_date"]->toDateTime()->format("Y-m-d g:i:s A"));
                    $row1["created_date_hover"] = $c_date_format;
                } else {
                    $row1["created_date"] = '';
                    $row1["created_date_hover"] = '';
                }

                // Modified Date

                if (!empty($row["modified_date"])) {
                    $m_date = new DateTime($row["modified_date"]->toDateTime()->format("c"));
                    $m_date->setTimeZone(new DateTimeZone($logged_timezone));
                    $m_date_format = $m_date->format('Y-m-d g:i:s A T');

                    $row1["modified_date"] = time_elapsed_string($row["modified_date"]->toDateTime()->format("Y-m-d g:i:s A"));
                    $row1["modified_date_hover"] = $m_date_format;
                } else {
                    $row1["modified_date"] = '';
                    $row1["modified_date_hover"] = '';
                }

                $loss_percentage = $row["loss_percentage"];
                $custom_stop_loss_percentage = $row['custom_stop_loss_percentage'];

                if ($row['trigger_type'] != 'no') {
                    $row1["loss_percentage"] = ($custom_stop_loss_percentage != '' ? $custom_stop_loss_percentage : $loss_percentage);
                } else {
                    $row1["loss_percentage"] = ($loss_percentage != '' ? $loss_percentage : $custom_stop_loss_percentage);
                }

                $row1["custom_stop_loss_percentage"] = $row["custom_stop_loss_percentage"];
                $row1["deep_price_on_off"] = $row["deep_price_on_off"];
                $row1["deep_price_percentage_buy"] = $row["deep_price_percentage_buy"];
                $row1["defined_sell_percentage"] = $row["defined_sell_percentage"];
                $row1["iniatial_trail_stop"] = $row["iniatial_trail_stop"];
                $row1["is_sell_order"] = $row["is_sell_order"];
                $row1["is_manual_sold"] = (!empty($row["is_manual_sold"]) ? $row["is_manual_sold"] : '');
                $row1["is_lth_order"] = $row["is_lth_order"];
                $row1["lth_functionality"] = $row["lth_functionality"];
                $row1["lth_profit"] = $row["lth_profit"];
                $row1["market_sold_price"] = $row["market_sold_price"];
                $row1["market_sold_price_usd"] = $row["market_sold_price_usd"];
                $row1["market_value"] = $row["market_value"];
                $row1["market_value_usd"] = $row["market_value_usd"];
                $row1['profit'] = $profit;
                
                $row1["order_level"] = $row["order_level"];
                $row1["order_mode"] = $row["order_mode"];
                $row1["order_type"] = $row["order_type"];
                $row1["price"] = $row["price"];
                $row1["purchased_price"] = $row["purchased_price"];
                $row1["quantity"] = $row["quantity"];
                $row1["sell_order_id"] = (string) $row["sell_order_id"];
                $row1["sell_price"] = $row["sell_price"];
                $row1["sell_profit_percent"] = $row["sell_profit_percent"];
                $row1["status"] = $row["status"];
                $row1["stop_loss_rule"] = $row["stop_loss_rule"];
                $row1["symbol"] = $row["symbol"];
                $row1["tradeId"] = $row["tradeId"];
                $row1["trading_ip"] = $row["trading_ip"];
                $row1["trading_status"] = $row["trading_status"];
                $row1["transactTime"] = $row["transactTime"];
                $row1["trigger_type"] = $row["trigger_type"];
                $sell_ids_arr = (array) $row['exchange_sell_order_ids_arr'];
                $buy_ids_arr = (array) $row['exchange_buy_order_ids_arr'];

                $row1["script_fix"] = ($row["script_fix"] ?? '');
                $row1["quantity_issue"] = ($row["quantity_issue"] ?? '');
                $row1["pick_parent"] = ($row["pick_parent"] ?? '');
                $row1["cancel_hour"] = ($row["cancel_hour"] ?? '');
                $row1["cavg_parent"] = ($row["cavg_parent"] ?? '');
                if(isset($row['accumulations'])){
                    $row1['accumulations']   = $row['accumulations']['profit'];    
                }

                // Resume array total profit
                if (!empty($row["resume_order_id"]) && !empty($row["resume_order_arr"]) && in_array('resume_completed', $order_status)) {
                    $total = 0;
                    $resumeArrCount = count($row["resume_order_arr"]);
                    if ($resumeArrCount > 0) {
                        for ($ri = 0; $ri < $resumeArrCount; $ri++) {
                            $total += $row["resume_order_arr"][$ri]['resumeLossPercentage'];
                        }
                    }
                    $lastRow = false;
                    //lth_pause check
                    if (!empty($row["status"]) && ($row['status'] == 'pause' || $row['status'] == 'FILLED') && !empty($row["is_sell_order"]) && ($row['is_sell_order'] == 'pause' || $row['is_sell_order'] == 'resume_pause')) {
                        $lastRow = true;
                        //sold check
                    } else if (!empty($row["is_sell_order"]) && $row['is_sell_order'] == 'sold') {
                        $lastRow = true;
                    }

                    if ($lastRow) {
                        $tObj = [];

                        if (!empty($row['last_purchase'])) {
                            $tObj['resumePurchased'] = $row['last_purchase'];
                        } else {
                            $tObj['resumePurchased'] = !empty($row['purchased_price']) ? $row['purchased_price'] : '';
                        }

                        if (!empty($row['last_sell'])) {
                            $tObj['resumeSell'] = $row['last_sell'];
                        } else {
                            $tObj['resumeSell'] = !empty($row['market_sold_price']) ? $row['market_sold_price'] : '';
                        }

                        $resLastP = @!is_nan($tObj['resumePurchased']) ? $tObj['resumePurchased'] : 0;
                        $resLastS = @!is_nan($tObj['resumeSell']) ? $tObj['resumeSell'] : 0;

                        $pl = 0;
                        if ($resLastP == 0 || $resLastS == 0) {
                            $pl = 0;
                        } else {
                            $pl = ((($resLastS - $resLastP) * 100) / $resLastP);
                        }
                        $pl = @!is_nan($pl) ? $pl : 0;

                        $total = $total + $pl;
                    }
                    $row1['resume_order_arr_total'] = @!is_nan($total) ? number_format($total, 2) : 0;
                } //End Resume array total profit


                if (count($sell_ids_arr) > 1) {
                    $row1['duplicate_sell'] = 'yes';
                } else {
                    $row1['duplicate_sell'] = 'no';
                }

                if (count($buy_ids_arr) > 1) {
                    $row1['duplicate_buy'] = 'yes';
                } else {
                    $row1['duplicate_buy'] = 'no';
                }

                $row1['opportunityId'] = (!empty($row['opportunityId']) ? $row['opportunityId'] : '');
                $row1['mapped_order'] = (!empty($row['mapped_order']) && $row['mapped_order'] == 1 ? 'yes' : '');
                

                $id = $row['admin_id'];
                $data_user = $this->get_username_from_user($id);

                $row1['admin'] = $data_user;
                $_id = $order['_id'];

                // $error = $this->get_error_type($_id);
                // $row1['log'] = $error;
                
                array_push($new_order_arrray, $row1);
                unset($row, $row1);
            }

            $data['json'] = json_encode($session_data);
            $data['total_count'] = $total_count;
            $data['serial_number'] = $serial_number;
            $new_order_arrray['average'] = $test_arr;
            $data['orders'] = $new_order_arrray;

            unset($new_order_arrray, $orders);
        }
        $coins = $this->mod_coins->get_all_coins();
        $data['coins'] = $coins;

        //set orders Array for CSV
        $csvOrdersArr = [];

        $srNum = 1;        
        $errArr = ['error', 'new_ERROR', 'FILLED_ERROR', 'submitted_ERROR', 'LTH_ERROR', 'canceled_ERROR', 'credentials_ERROR', 'fraction_ERROR', 'SELL_ID_ERROR'];
        $nanArr = ['nan', 'Nan', 'NAN', 'inf', 'Inf', 'INF', ''];


        $ordersArrLength = count($data['orders']);
        for ($i = 0; $i < $ordersArrLength; $i++) {
            $value = &$data['orders'][$i];
            // echo "<pre>"; print_r($value); exit;

            $csvOrder = [];

            $csvOrder['sr_num'] = $srNum;
            $csvOrder['order_id'] = $value['_id'];
            $srNum++;

            $csvOrder['Coin'] = $value['symbol'];
            $csvOrder['Price'] = (isset($value['purchased_price']) ? num($value['purchased_price']) : num($value['price']));

            //Order Type
            if ($value['trigger_type'] != 'no') {
                if ($value['trigger_type'] == 'barrier_percentile_trigger') {
                    $csvOrder['Order Type'] = 'BPT';
                } else {
                    $csvOrder['Order Type'] = strtoupper(str_replace('_', ' ', $value['trigger_type']));
                }
            } else {
                $csvOrder['Order Type'] = 'MANUAL ORDER';
            }
            //end Order Type
            
            // $market_value = $this->mod_dashboard->get_market_value($value['symbol']);
            $filter_user_data = $this->session->userdata('filter_order_data');
            if (!empty($filter_user_data['filter_by_exchange'])) {
                $order_exchange = $filter_user_data['filter_by_exchange'];
            }

            $BTCUSDTmarket_value = $this->mod_dashboard->get_market_value2('BTCUSDT', $order_exchange);
            $market_value = $this->mod_dashboard->get_market_value2($value['symbol'], $order_exchange);
            
            $logo = $this->mod_coins->get_coin_logo($value['symbol']);
            if ($value['status'] != 'new') {
                if ($value['status'] == 'FILLED') {
                    $market_value333 = num($value['purchased_price']);
                } else {
                    $market_value333 = num($value['market_value']);
                }
            } else {
                $market_value333 = num($market_value);
            }
            if ($value['status'] == 'new') {
                $current_order_price = num($value['price']);
            } else {
                if ($value['status'] == 'FILLED') {
                    $market_value333 = num($value['purchased_price']);
                } else {
                    $market_value333 = num($value['market_value']);
                }
            }

            if ($value['status'] == 'canceled' && (empty($value['parent_status']) || $value['parent_status'] != 'parent')) {
                $market_value333 = !empty($value['purchased_price']) ? num($value['purchased_price']) : num($value['price']);
            }

            $current_data = $market_value333 - $current_order_price;
            $market_data = ($current_data * 100 / $market_value333);
            $market_data = number_format((float) $market_data, 2, '.', '');

            $csvOrder['Level'] = $value['order_level'];
            $csvOrder['quantity'] = $value['quantity'];
            $usd_worth_tt = get_usd_worth($value['symbol'], $value['quantity'], $market_value333, $BTCUSDTmarket_value);
            $csvOrder['usd worth'] = "$".$usd_worth_tt;
            $csvOrder['Created Date'] = $value['created_date_hover'];
            $csvOrder['Market Sold Price'] = $value['market_sold_price'];
            $csvOrder['Accumulations'] = $value['accumulations'];
            // $csvOrder['Sell price'] = $value['sell_price']; // this sell price is different from the market sold price thats why i commented it out on jan 27 2023.
            //P/L
            $csvOrder['P/L'] = num($market_value333);
            //end Market(%)

            //Market(%)
            $csvOrder['Market(%)'] = ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') ? $market_data : '-';
            //end Market(%)

            //Status
            if ($value['is_sell_order'] == 'yes') {
                if ($value['status'] == 'LTH') {
                    $csvOrder['Status'] = "LTH OPEN";
                } elseif (in_array($value['status'], $errArr)) {
                    $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
                } elseif ($value['status'] == 'submitted_for_sell') {
                    $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
                } elseif ($value['status'] == 'fraction_submitted_sell') {
                    $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
                } elseif ($value['status'] == 'canceled') {
                    $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
                }elseif($value['status'] == 'new'){
                    $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
                }else{
                    $csvOrder['Status'] = 'OPEN';
                }
            } else {
                $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
            }
            if ($value['parent_status'] == 'parent') {
                $csvOrder['Status'] = 'Parent Order';
            }
            //end Status

            //Profit(%)
            if(!empty($value['resume_order_arr_total'])){
                $csvOrder['Profit(%)'] = $value['resume_order_arr_total'] . '%';
            }else{
                if ($value['market_sold_price'] != "") {
                    $market_sold_price = num($value['market_sold_price']);
                    $pp = num($value['purchased_price']);
                    $profitPercentage = calculateProfitPercentage($pp, $market_sold_price);
                    $csvOrder['Profit(%)'] = $profitPercentage . '%';
                } else {
                    if ($value['status'] == 'FILLED') {
                        if ($value['is_sell_order'] == 'yes') {
                            $pp = num($value['purchased_price']);
                            $profitPercentage = calculateProfitPercentage($pp, $market_value);
                            $csvOrder['Profit(%)'] = $profitPercentage . '%';
                        } else {
                            $csvOrder['Profit(%)'] = '-';
                        }
                    } elseif ($value['status'] == 'LTH') {
                        if ($value['is_sell_order'] == 'yes') {
                            $pp = num($value['purchased_price']);
                            $profitPercentage = calculateProfitPercentage($pp, $market_value);
                            $csvOrder['Profit(%)'] = $profitPercentage . '%';
                        } else {
                            $csvOrder['Profit(%)'] = '-';
                        }
                    } else {
                        $csvOrder['Profit(%)'] = '-';
                    }
                }
            }
            //end Profit(%)

            //Target Profit(%)
            // if ($value['trigger_type'] == 'no' && $value['status'] == 'LTH') {
            //     $target_profit = $value['lth_profit'];
            // }
            // if ($value['trigger_type'] == 'no' && $value['status'] == 'FILLED') {
            //     $target_profit = !empty($value['sell_profit_percent']) ? $value['sell_profit_percent'] : $value['defined_sell_percentage'];
            // }
            
            if ($value['trigger_type'] == 'no' && $value['status'] == 'LTH') {
                $target_profit = $value['lth_profit'];
            }else if ($value['trigger_type'] == 'no' && $value['status'] == 'FILLED') {
                $target_profit = $value['sell_profit_percent'];
            }else if ($value['trigger_type'] != 'no' && $value['status'] == 'LTH') {
                $target_profit = $value['lth_profit'];
            }else if ($value['trigger_type'] != 'no' && $value['status'] == 'FILLED') {
                $target_profit = $value['defined_sell_percentage'];
            }else if ($value['parent_status'] == 'parent') {
                $target_profit = $value['defined_sell_percentage'];
            }
            $csvOrder['Target Profit(%)'] = (!in_array($target_profit, $nanArr) ? $target_profit.'%' : '-');
            //end Target Profit(%)

            //Slippage(%)
            if (!in_array($target_profit, $nanArr) && !in_array($profitPercentage, $nanArr) && $value['is_sell_order'] == 'sold') {
                if ($value['is_lth_order'] == 'yes' && !in_array($value['lth_profit'], $nanArr)) {
                    $target_profit = $value['lth_profit'];
                }
                if ($profitPercentage >= $target_profit) {
                    $slippage = ($profitPercentage - $target_profit);
                    $slippage = round($slippage, 3) . '%';
                } else if ($profitPercentage < $target_profit) {
                    $slippage = ($target_profit - $profitPercentage);
                    $slippage = '-' . round($slippage, 3) . '%';
                } else {
                    $slippage = '-';
                }
            } else {
                $slippage = '-';
            }
            $csvOrder['Slippage(%)'] = $slippage;
            //end Slippage(%)

            //LTH
            if ($value['status'] == 'LTH') {
                $csvOrder['LTH'] = 'LTH';
            } else {
                if ($value['is_sell_order'] == 'sold' && $value['is_lth_order'] == 'yes') {
                    $csvOrder['LTH'] = 'LTH';
                } else {
                    $csvOrder['LTH'] = 'Normal';
                }
            }
            //end LTH

            //LTH Profit (%)
            $csvOrder['LTH Profit (%)'] = ($value['lth_profit'] != '' && !in_array(trim($value['lth_profit']), $nanArr) ? $value['lth_profit'] : '-');
            //end LTH Profit (%)

            //Stop Loss (%)
            $csvOrder['Stop Loss (%)'] = ($value['loss_percentage'] != '' && !in_array(trim($value['loss_percentage']), $nanArr) ? $value['loss_percentage'].($value['iniatial_trail_stop'] > $value['purchased_price'] ? " (+)" : " (-)") : '-');
            //end Stop Loss (%)

            //Sub Status
            if (in_array($value['status'], $errArr)) {
                $csvOrder['Sub Status'] = str_replace('_', ' ', $value['status']);
            } else {
                if ($value['status'] == 'FILLED') {
                    if ($value['is_sell_order'] == 'yes') {
                        if (!empty($value['sell_order_id'])) {
                            $sell_status = $this->mod_buy_orders->is_sell_order_in_error_status($value['sell_order_id']);
                            $sell_status_submit = $this->mod_buy_orders->is_sell_order_in_submitted_status($value['sell_order_id']);
                        } else {
                            $sell_status = '';
                            $sell_status_submit = '';
                        }
                        if ($sell_status) {
                        } elseif ($sell_status_submit) {
                            $csvOrder['Sub Status'] = 'SUBMITTED FOR SELL';
                        } else {
                            $csvOrder['Sub Status'] = 'WAITING FOR SELL';
                        }
                    } elseif ($value['is_sell_order'] == 'sold') {
                        $csvOrder['Sub Status'] = ($value['is_manual_sold'] == 'yes' ? 'Sold Manually' : 'Sold');
                    } elseif ($value['is_sell_order'] == 'pause') {
                        $csvOrder['Sub Status'] = 'Paused';
                    }
                } elseif ($value['status'] == 'LTH') {
                    if ($sell_status) {
                        $csvOrder['Sub Status'] = 'ERROR IN SELL ' . $sell_status . '';
                    } elseif ($sell_status_submit) {
                        $csvOrder['Sub Status'] = 'SUBMITTED FOR SELL';
                    } else {
                        $csvOrder['Sub Status'] = 'WAITING FOR SELL';
                    }
                } else {
                    $csvOrder['Sub Status'] = '-';
                }

                if ($value['resume_status'] == 'completed') {
                    $csvOrder['Sub Status'] = ' Resume Completed ';
                } else if ($value['status'] == 'FILLED' && ($value['is_sell_order'] == 'yes' || $value['is_sell_order'] == 'sold') && !empty($value['resume_order_id'])) {
                    $csvOrder['Sub Status'] = ' Resume Child ';
                } else if ($value['is_sell_order'] == 'resume_pause' && empty($value['resume_order_arr'])) {
                    $csvOrder['Sub Status'] = ' Resumed ';
                } else if ($value['is_sell_order'] == 'resume_pause' && !empty($value['resume_order_arr'])) {
                    $csvOrder['Sub Status'] = ' In Progress ';
                } else if ($value['is_sell_order'] == 'pause') {
                    $csvOrder['Sub Status'] = ' Paused ';
                }    
            }
            //end Sub Status
            
            if ($value['mapped_order'] == 'yes') {
                $csvOrder['Sub Status'] .= ' Mapped ';
            }
            if($value['script_fix'] != '' && $value['script_fix'] == 1){
                $csvOrder['Sub Status'] .= ', Script Fixed'; 
            }else if($value['script_fix'] != '' && $value['script_fix'] == 0){
                $csvOrder['Sub Status'] .= ', Script Ignored'; 
            }
            
            if($value['quantity_issue'] != '' && $value['quantity_issue'] == 0){
                $csvOrder['Sub Status'] .= ', Quantity Issue'; 
            }
            if($value['pick_parent'] == 'yes'){
                $csvOrder['Sub Status'] .= ', Pick parent (Yes)'; 
            }
            if($value['pick_parent'] == 'no'){
                $csvOrder['Sub Status'] .= ', Pick parent (No)'; 
            }
            if (!empty($value['cancel_hour'])) {
                $csvOrder['Sub Status'] .= ', Deep price';
            }
            if ($value['cavg_parent'] == 'yes') {
                $csvOrder['Sub Status'] .= ', Cost Avg Parent';
            }

            // Max(%)/Min(%)
            if (isset($value['market_heighest_value']) && $value['market_heighest_value'] != '') {
                $five_hour_max_market_price = $value['market_heighest_value'];
                $purchased_price = (float) $value['purchased_price'];
                $profit = $five_hour_max_market_price - $purchased_price;
                $profit_margin = ($profit / $five_hour_max_market_price) * 100;
                $profit_per = ($profit) * (100 / $purchased_price);
                $t_max = number_format($profit_per, 2) . '%';
            } else {
                $t_max = "-";
            }
            if (isset($value['market_lowest_value']) && $value['market_lowest_value'] != '') {
                $market_lowest_value = $value['market_lowest_value'];
                $purchased_price = (float) $value['purchased_price'];
                $profit = $market_lowest_value - $purchased_price;
                $profit_margin = ($profit / $market_lowest_value) * 100;
                $profit_per = ($profit) * (100 / $purchased_price);
                $t_min = number_format($profit_per, 2) . '%';
            } else {
                $t_min = "-";
            }
            $csvOrder['Max(%)/Min(%)'] = $t_max . " | " . $t_min;
            // end Max(%)/Min(%)

            //5hMax(%)/5hMin(%)
            if (isset($value['5_hour_max_market_price']) && $value['5_hour_max_market_price'] != '') {
                $five_hour_max_market_price = $value['5_hour_max_market_price'];
                $market_sold_price = (float) $value['market_sold_price'];
                $profit = $five_hour_max_market_price - $market_sold_price;
                $profit_margin = ($profit / $five_hour_max_market_price) * 100;
                $profit_per = ($profit) * (100 / $market_sold_price);
                $max_5h = number_format($profit_per, 2) . '%';
            } else {
                $max_5h = "-";
            }
            if (isset($value['5_hour_min_market_price']) && $value['5_hour_min_market_price'] != '') {
                $market_lowest_value = $value['5_hour_min_market_price'];
                $market_sold_price = (float) $value['market_sold_price'];
                $profit = $market_lowest_value - $market_sold_price;
                $profit_margin = ($profit / $market_lowest_value) * 100;
                $profit_per = ($profit) * (100 / $market_sold_price);
                $min_5h = number_format($profit_per, 2) . '%';
            } else {
                $min_5h = "-";
            }
            $csvOrder['5hMax(%)/5hMin(%)'] = $max_5h . " | " . $min_5h;
            // end 5hMax(%)/5hMin(%)

            $csvOrder['User Info'] = $value['admin']->username;
            $csvOrder['User IP'] = $value['admin']->trading_ip;
            $csvOrder['Last Modified Date'] = $value['modified_date_hover'];

            $csvOrdersArr[] = $csvOrder;

            unset($value, $csvOrder);
            
        }

        $this->generate_csv($csvOrdersArr);
    }


    public function get_csv()
    {

        // ini_set("display_errors", E_ERROR);
        // error_reporting(E_ERROR);

        //Login Check
        $this->mod_login->verify_is_admin_login();

        $input_search_filters = $this->input->post();
        if (!empty($input_search_filters)) {

            $filter_is_empty = true;
            foreach ($input_search_filters as $kee => $filter_key) {
                if (!empty($filter_key)) {
                    if ($kee != 'optradio' && $kee != 'selector') {
                        $filter_is_empty = false; 
                    }
                }
            }
            if ($filter_is_empty) {
                $this->reset_filters_report('all');
            }

            $data_arr['filter_order_data'] = $this->input->post();
            $this->session->set_userdata($data_arr);
        }

        $session_data = $this->session->userdata('filter_order_data');
// echo "<pre>"; print_r($session_data); exit;
        if (isset($session_data)) {

            $collection = "buy_orders";
             if ($session_data['filter_by_coin']) {
                if(!empty($session_data['filter_by_coin']) && $session_data['filter_by_coin'][0] !=""){
                $search['symbol']['$in'] = $session_data['filter_by_coin'];
                }
            }
            if ($session_data['filter_by_mode']) {
                $search['application_mode'] = $session_data['filter_by_mode'];
            }
            if ($session_data['filter_by_trigger']) {
                $search['trigger_type'] = $session_data['filter_by_trigger'];
            }
            if ($session_data['filter_by_rule'] != "") {
                $filter_by_rule = $session_data['filter_by_rule'];
                //$search['$or'] = array("buy_rule_number" => $filter_by_rule, "sell_rule_number" => $filter_by_rule);
                $search['$or'] = array(
                    array("buy_rule_number" => intval($filter_by_rule)),
                    array("sell_rule_number" => intval($filter_by_rule)),
                );

            }
            if (!empty($session_data['filter_level'])) {
                $order_level = $session_data['filter_level'];
                $search['order_level']['$in'] = $order_level;
            }
            if (!empty($session_data['filter_by_coinNature'])) {
                if($session_data['filter_by_coinNature'] == 'btc'){
                    $type = 'BTC';
                    $search['$and'] = [ 
                              ['symbol'=>new MongoDB\BSON\Regex(".*{$type}.*", 'i')], 
                              ['symbol'=>['$ne'=> 'BTCUSDT']],
                          ];
                }elseif($session_data['filter_by_coinNature'] == 'usdt'){
                    $type = 'USDT';
                    $search['symbol']=new MongoDB\BSON\Regex(".*{$type}.*", 'i');
                }
            }
            if ($session_data['filter_username'] != "") {
                $username = $session_data['filter_username'];
                $admin_id = $this->get_admin_id($username);
                $search['admin_id'] = (string) $admin_id;
            }
            if (!empty($session_data['opportunityId'])) {
                $search['opportunityId'] = $session_data['opportunityId'];
            }
            if (!empty($session_data['orderId'])) {
                $search['_id'] = $this->mongo_db->mongoId($session_data['orderId']);
            }
            //multi-select //Umer Abbas [12-11-19]
            if (!empty($session_data['filter_by_status'])) {
                $order_status = $session_data['filter_by_status'];

                $status_filter_arr = array();
                if (in_array('new', $order_status)) {
                    $search['parent_status']['$ne'] = 'parent';
                    $status_filter_arr[] = 'new';
                    $status_filter_arr[] = 'new_ERROR';
                    $search['price']['$ne'] = '';

                }
                if (in_array('error', $order_status)) {
                    $status_filter_arr[] = 'error';
                }
                if (in_array('open', $order_status)) {
                    // $status_filter_arr[] = 'submitted';
                    $status_filter_arr[] = 'FILLED';
                    // $status_filter_arr[] = 'FILLED_ERROR';
                    $search['resume_order_id'] = ['$exists' => false];
                    $search['is_sell_order'] = 'yes';
                }
                $start_date_check = 0;
                $end_date_check = 0;
            if ($session_data['filter_by_start_date_m'] != "" && $session_data['filter_by_end_date_m'] != "") {
                // $created_datetime = date('Y-m-d G:i:s', strtotime($session_data['filter_by_start_date_m']));
                // $orig_date = new DateTime($created_datetime);
                // $orig_date = $orig_date->getTimestamp();
                // $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
                $start_date_check = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s',strtotime($session_data['filter_by_start_date_m']))); 
                // $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_data['filter_by_end_date_m']));
                // $orig_date22 = new DateTime($created_datetime22);
                // $orig_date22 = $orig_date22->getTimestamp();
                // $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
                $end_date_check = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s',strtotime($session_data['filter_by_end_date_m'])));
                //echo '<pre>start'.$start_date_check; 
                //echo '<pre>end'.$end_date_check; exit;
                $search['modified_date'] = array('$gte' => $start_date_check, '$lte' => $end_date_check);
            }
                if (in_array('sold', $order_status)) {
                    $status_filter_arr[] = 'FILLED';
                    if ($session_data['filter_by_start_date_m'] != "" && $session_data['filter_by_end_date_m'] != "") {
                        $search['sell_date'] = array('$gte' => $start_date_check, '$lte' => $end_date_check);
                        //$search['sell_date'] = array('$gte' => $start_date_check, '$lte' => $end_date_check);
                    }elseif ($session_data['filter_by_start_date'] != "" && $session_data['filter_by_end_date'] != "") {
                        $search['created_date'] = array('$gte' => $start_date_new, '$lte' => $end_date_new);
                        //$search['created_date'] = array('$gte' => $start_date_new, '$lte' => $end_date_new);
                    }
                    $search['is_sell_order'] = 'sold';
                    $search['is_manual_sold'] = ['$ne' => 'yes'];
                    // $collection = "sold_buy_orders";
                }
                if (in_array('sold_manually', $order_status)) {
                    $status_filter_arr[] = 'FILLED';
                    $search['is_sell_order'] = 'sold';
                    $search['is_manual_sold'] = 'yes';
                    // $collection = "sold_buy_orders";
                }
                if (in_array('LTH_sold', $order_status)) {
                    $status_filter_arr[] = 'FILLED';
                    $search['is_sell_order'] = 'sold';
                    $search['is_lth_order'] = 'yes';
                }
                if (in_array('lth_pause', $order_status)) {
                    $status_filter_arr[] = 'FILLED';
                    $search['is_sell_order'] = 'pause';
                    // $search['is_sell_order'] = ['$in' => ['pause', 'resume_pause', 'resume_complete'] ];
                }
                if (in_array('LTH', $order_status)) {
                    $status_filter_arr[] = 'LTH';
                    $search['is_sell_order'] = 'yes';
                }
                if (in_array('takingparent', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'takingOrder';
                }
                if (in_array('takeparent', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'new';
                }
                if (in_array('play', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'new';
                    $search['pause_status'] = 'play';
                }
                if (in_array('pause', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'new';
                    $search['pause_status'] = 'pause';
                }
                if (in_array('pick_parent_yes', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $search['pick_parent'] = 'yes';
                }
                if (in_array('pick_parent_no', $order_status)) {
                    $search['parent_status'] = 'parent';
                    $search['pick_parent'] = 'no';
                }
                if (in_array('canceled', $order_status)) {
                    $status_filter_arr[] = 'canceled';
                }
                if (in_array('error_in_sell', $order_status)) {
                    // $search['parent_status'] = 'parent';
                    $status_filter_arr[] = 'error';
                }
                
                if (in_array('new_ERROR', $order_status)) {
                    $status_filter_arr[] = 'new_ERROR';
                }
                if (in_array('FILLED_ERROR', $order_status)) {
                    $status_filter_arr[] = 'FILLED_ERROR';
                }
                if (in_array('submitted_ERROR', $order_status)) {
                    $status_filter_arr[] = 'submitted_ERROR';
                }
                if (in_array('LTH_ERROR', $order_status)) {
                    $status_filter_arr[] = 'LTH_ERROR';
                }
                if (in_array('canceled_ERROR', $order_status)) {
                    $status_filter_arr[] = 'canceled_ERROR';
                }
                if (in_array('submitted_for_sell', $order_status)) {
                    $status_filter_arr[] = 'submitted_for_sell';
                }
                if (in_array('fraction_submitted_sell', $order_status)) {
                    $status_filter_arr[] = 'fraction_submitted_sell';
                }
                if (in_array('credentials_ERROR', $order_status)) {
                    $status_filter_arr[] = 'credentials_ERROR';
                }
                if (in_array('fraction_ERROR', $order_status)) {
                    $status_filter_arr[] = 'fraction_ERROR';
                }
                if (in_array('SELL_ID_ERROR', $order_status)) {
                    $status_filter_arr[] = 'SELL_ID_ERROR';
                }
                if (in_array('resume_paused', $order_status)) {
                    $search['is_sell_order'] = 'pause';
                    $search['show_order'] = ['$ne' => 'no'];
                }
                if (in_array('resumed', $order_status)) {
                    // $search['resume_order_id'] = ['$exists' => true];
                    $search['is_sell_order'] = 'resume_pause';
                    $search['resume_order_arr'] = ['$exists' => false];
                    $search['show_order'] = ['$ne' => 'no'];
                }
                if (in_array('resume_in_progress', $order_status)) {
                    $search['is_sell_order'] = 'resume_pause';
                    $search['resume_order_arr.0'] = ['$exists' => true];
                    $search['show_order'] = ['$ne' => 'no'];
                }
                if (in_array('resume_completed', $order_status)) {
                    $search['resume_status'] = 'completed';
                }
                if (in_array('resume_child_sold', $order_status)) {
                    $search['status'] = 'FILLED';
                    $search['is_sell_order'] = 'yes';
                    $search['resume_order_id'] = ['$exists' => true];
                }

                if (in_array('quantity_issue', $order_status)) {
                    $search['quantity_issue'] = '1';
                }
                 if (in_array('cost_averageSold', $order_status)) {
                    $search['cavg_parent'] = 'yes';            
                    $search['cost_avg'] = 'completed';
                    //$search['count_avg_order'] = ['$exists'=> true]; this was commented on order of shehzad by sheraz
                }
                if (in_array('cost_averageOpen', $order_status)) {
                    $search['cavg_parent'] = 'yes';
                    $search['cost_avg']['$ne'] = 'completed';
                    $search['is_sell_order']['$ne'] = 'sold';
                    $search['status'] = ['$ne'=>'canceled'];
                    //$search['count_avg_order'] = ['$exists'=> true]; this was commented on order of shehzad by sheraz
                    
                }
                if (in_array('cost_average_sold_status', $order_status)) {
                    $search['cavg_parent'] = 'yes';
                    $search['cost_avg'] = 'yes';
                    $search['trading_status'] = 'complete';
                    $search['status'] = 'FILLED';
                    
                }

                if (in_array('errors_tab', $order_status)) {
                    $search['status'] = ['$nin'=>['CA_TAKING_CHILD','new', 'FILLED', 'fraction_submitted_buy', 'canceled', 'LTH', 'submitted', 'submitted_for_sell', 'fraction_submitted_sell']];
                }

                $status_filter_arr = array_unique($status_filter_arr);
                $status_filter_arr = array_values($status_filter_arr); //to reindex the array

                if (!in_array('parent', $order_status) && !in_array('takingparent', $order_status) && !in_array('takeparent', $order_status) && !in_array('play', $order_status) && !in_array('pause', $order_status) && !in_array('pick_parent_yes', $order_status) && !in_array('pick_parent_no', $order_status)) {
                    $search['parent_status'] = array('$ne' => 'parent');
                }

                if (!empty($status_filter_arr)) {
                    $search['status'] = array('$in' => $status_filter_arr);
                }
            }


            if ($session_data['filter_by_start_date'] != "" && $session_data['filter_by_end_date'] != "") {

                $created_datetime = date('Y-m-d H:i:s', strtotime($session_data['filter_by_start_date']));
                $orig_date = new DateTime($created_datetime);
                $orig_date = $orig_date->getTimestamp();
                $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

                $created_datetime22 = date('Y-m-d H:i:s', strtotime($session_data['filter_by_end_date']));
                $orig_date22 = new DateTime($created_datetime22);
                $orig_date22 = $orig_date22->getTimestamp();
                $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
                $search['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
            }

            if ($session_data['filter_by_start_date_m'] != "" && $session_data['filter_by_end_date_m'] != "") {

                $created_datetime = date('Y-m-d H:i:s', strtotime($session_data['filter_by_start_date_m']));
                $orig_date = new DateTime($created_datetime);
                $orig_date = $orig_date->getTimestamp();
                $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

                $created_datetime22 = date('Y-m-d H:i:s', strtotime($session_data['filter_by_end_date_m']));
                $orig_date22 = new DateTime($created_datetime22);
                $orig_date22 = $orig_date22->getTimestamp();
                $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
                $search['modified_date'] = array('$gte' => $start_date, '$lte' => $end_date);
            }
            //Umer Abbas [1-11-19]

            if (!empty($session_data['filter_by_nature'])) {
                if ($session_data['filter_by_nature'] == 'auto') {

                    $search['trigger_type'] = array('$ne' => 'no');

                } else if ($session_data['filter_by_nature'] == 'manual') {

                    $search['trigger_type'] = 'no';

                }
            }

            //show parents
            if ((!empty($session_data['show_parents']) && $session_data['show_parents'] == 'yes') || (!empty($search['parent_status']) && $search['parent_status'] == 'parent')) {
                $search['parent_status'] = 'parent';
            } else {
                $search['parent_status'] = ['$exists' => false];
            }

            if ($session_data['optradio'] != "") {
                if ($session_data['optradio'] == 'created_date') {
                    $oder_arr['optradio'] = 'created_date';
                    if ($session_data['selector'] == 'ASC') {
                        $oder_arr['selector'] = 1;
                        $oder_arr['created_date'] = 1;
                    } else {
                        $oder_arr['selector'] = -1;
                        $oder_arr['created_date'] = -1;
                    }
                } elseif ($session_data['optradio'] == 'modified_date') {
                    $oder_arr['optradio'] = 'modified_date';
                    if ($session_data['selector'] == 'ASC') {
                        $oder_arr['selector'] = 1;
                        $oder_arr['modified_date'] = 1;
                    } else {
                        $oder_arr['selector'] = -1;
                        $oder_arr['modified_date'] = -1;
                    }

                }
            }
            $connetct = $this->mongo_db->customQuery();

            if ($session_data['filter_by_exchange'] != "") {
                $order_exchange = $session_data['filter_by_exchange'];
                if ($order_exchange != 'binance') {
                    $collection_str1 = 'sold_buy_orders_' . $order_exchange;
                    $collection_str2 = 'buy_orders_' . $order_exchange;
                } else {
                    $collection_str1 = 'sold_buy_orders';
                    $collection_str2 = 'buy_orders';
                }
            } else {
                $collection_str1 = 'sold_buy_orders';
                $collection_str2 = 'buy_orders';
            }
            // echo $collection_str1. "=============".$collection_str2;
            // exit;

            if (empty($search)) {
                $this->reset_filters_report('all');
            }

            if (empty($session_data['filter_by_status'])) {
                $costAvgParentFilter = array_merge($search, [
                    'cost_avg' => ['$exists' => true],
                    'cavg_parent' => 'yes',
                ]);
                $tempSearchQuery['$or'][] = $costAvgParentFilter;

                //Set for second condition
                $search = array_merge($search, [
                    'cost_avg' => ['$nin' => ['taking_child', 'yes', 'completed']],
                    'cavg_parent' => ['$exists' => false],
                ]);
            }

            $tempSearchQuery['$or'][] = $search;
            $search = $tempSearchQuery;

            unset($tempSearchQuery);
            // echo "<pre>"; print_r($search); exit; 
            $pending_curser = $connetct->$collection_str2->find($search);
            $sold_curser = $connetct->$collection_str1->find($search);

            $pending_arr = iterator_to_array($pending_curser);
            $sold_arr = iterator_to_array($sold_curser);
            $orders = array_merge_recursive($pending_arr, $sold_arr);
          
            
            if ($session_data['optradio'] != "") {
                if ($session_data['optradio'] == 'created_date') {
                    if ($session_data['selector'] == 'ASC') {
                        foreach ($orders as $key => $part) {
                            $sort[$key] = (string) $part['created_date'];
                        }
                        array_multisort($sort, SORT_ASC, $orders);
                    } else {
                        foreach ($orders as $key => $part) {
                            $sort[$key] = (string) $part['created_date'];
                        }
                        array_multisort($sort, SORT_DESC, $orders);
                    }
                } elseif ($session_data['optradio'] == 'modified_date') {
                    if ($session_data['selector'] == 'ASC') {
                        foreach ($orders as $key => $part) {
                            $sort[$key] = (string) $part['modified_date'];
                        }
                        array_multisort($sort, SORT_ASC, $orders);
                    } else {
                        foreach ($orders as $key => $part) {
                            $sort[$key] = (string) $part['modified_date'];
                        }
                        array_multisort($sort, SORT_DESC, $orders);
                    }
                }
            } else {
                foreach ($orders as $key => $part) {
                    $sort[$key] = (string) $part['modified_date'];
                }
                array_multisort($sort, SORT_DESC, $orders);
            }
            //end

            $logged_user_id = $this->session->userdata('admin_id');
            $logged_timezone = get_user_timezone($logged_user_id);



            //interesting question to read for memory problems
            // https://stackoverflow.com/questions/1742215/php-foreach-loops-and-ressources

            $ordersArrLength = count($orders);
           
            $new_order_arrray = [];
            for ($i = 0; $i < $ordersArrLength; $i++) {
                $row = &$orders[$i];
                // echo "<pre>"; print_r($row); exit;
                // if(isset($_COOKIE['sheraz']) && $_COOKIE['sheraz'] == 1){
                //  echo '<pre>'; print_r($row);exit;   
                // }
                $row1['_id'] = (string) $row['_id'];
                $row1["admin_id"] = $row["admin_id"];
                
                $row1["5_hour_max_market_price"] = $row["5_hour_max_market_price"];
                $row1["5_hour_min_market_price"] = $row["5_hour_min_market_price"];
                $row1["market_lowest_value"] = $row["market_lowest_value"];
                $row1["market_heighest_value"] = $row["market_heighest_value"];
                #$row1["admin_id"] = $row["admin_id"];

                $row1["application_mode"] = $row["application_mode"];
                $row1["binance_order_id"] = $row["binance_order_id"];
                $row1["buy_date"] = ((isset($row['buy_date']) ? $row["buy_date"]->toDateTime()->format("Y-m-d g:i:s A") : ""));
                $row1["buy_parent_id"] = (string) $row["buy_parent_id"];
                $row1["parent_status"] = ($row["parent_status"] ?? '');
                $row1["resume_status"] = ($row["resume_status"] ?? '');
                $row1["resume_order_id"] = ($row["resume_order_id"] ?? '');
                $row1["resume_order_arr"] = ($row["resume_order_arr"] ?? '');


                // Created Date
                if (!empty($row["created_date"])) {
                    if(is_string($row["created_date"])){
                        $row1["created_date"] = $row["created_date"];
                        $row1["created_date_hover"] = $row["created_date"];
                    }else{
                        $c_date = new DateTime($row["created_date"]->toDateTime()->format("c"));
                        $c_date->setTimeZone(new DateTimeZone($logged_timezone));
                        $c_date_format = $c_date->format('Y-m-d g:i:s A T');
                        $row1["created_date"] = time_elapsed_string($row["created_date"]->toDateTime()->format("Y-m-d g:i:s A"));
                        $row1["created_date_hover"] = $c_date_format;
                    }
                    
                } else {
                    $row1["created_date"] = '';
                    $row1["created_date_hover"] = '';
                }

                // Modified Date

                if (!empty($row["modified_date"])) {
                    if(is_string($row["modified_date"])){
                        $row1["modified_date"] = $row["modified_date"];
                        $row1["modified_date_hover"] = $row["modified_date"];
                    }else{
                        $m_date = new DateTime($row["modified_date"]->toDateTime()->format("c"));
                        $m_date->setTimeZone(new DateTimeZone($logged_timezone));
                        $m_date_format = $m_date->format('Y-m-d g:i:s A T');

                        $row1["modified_date"] = time_elapsed_string($row["modified_date"]->toDateTime()->format("Y-m-d g:i:s A"));
                        $row1["modified_date_hover"] = $m_date_format;
                    }
                    
                } else {
                    $row1["modified_date"] = '';
                    $row1["modified_date_hover"] = '';
                }

                $loss_percentage = $row["loss_percentage"];
                $custom_stop_loss_percentage = $row['custom_stop_loss_percentage'];

                if ($row['trigger_type'] != 'no') {
                    $row1["loss_percentage"] = ($custom_stop_loss_percentage != '' ? $custom_stop_loss_percentage : $loss_percentage);
                } else {
                    $row1["loss_percentage"] = ($loss_percentage != '' ? $loss_percentage : $custom_stop_loss_percentage);
                }

                $row1["custom_stop_loss_percentage"] = $row["custom_stop_loss_percentage"];
                $row1["deep_price_on_off"] = $row["deep_price_on_off"];
                $row1["deep_price_percentage_buy"] = $row["deep_price_percentage_buy"];
                $row1["defined_sell_percentage"] = $row["defined_sell_percentage"];
                $row1["iniatial_trail_stop"] = $row["iniatial_trail_stop"];
                $row1["is_sell_order"] = $row["is_sell_order"];
                $row1["is_manual_sold"] = (!empty($row["is_manual_sold"]) ? $row["is_manual_sold"] : '');
                $row1["is_lth_order"] = $row["is_lth_order"];
                $row1["lth_functionality"] = $row["lth_functionality"];
                $row1["lth_profit"] = $row["lth_profit"];
                $row1["market_sold_price"] = $row["market_sold_price"];
                $row1["market_sold_price_usd"] = $row["market_sold_price_usd"];
                $row1["market_value"] = $row["market_value"];
                $row1["market_value_usd"] = $row["market_value_usd"];
                $row1['profit'] = $profit;
                
                $row1["order_level"] = $row["order_level"];
                $row1["order_mode"] = $row["order_mode"];
                $row1["order_type"] = $row["order_type"];
                $row1["price"] = $row["price"];
                $row1["purchased_price"] = $row["purchased_price"];
                $row1["quantity"] = $row["quantity"];
                $row1["sell_order_id"] = (string) $row["sell_order_id"];
                $row1["sell_price"] = $row["sell_price"];
                $row1["sell_profit_percent"] = $row["sell_profit_percent"];
                $row1["status"] = $row["status"];
                $row1["stop_loss_rule"] = $row["stop_loss_rule"];
                $row1["symbol"] = $row["symbol"];
                $row1["tradeId"] = $row["tradeId"];
                $row1["trading_ip"] = $row["trading_ip"];
                $row1["trading_status"] = $row["trading_status"];
                $row1["transactTime"] = $row["transactTime"];
                $row1["trigger_type"] = $row["trigger_type"];
                $sell_ids_arr = (array) $row['exchange_sell_order_ids_arr'];
                $buy_ids_arr = (array) $row['exchange_buy_order_ids_arr'];

                $row1["script_fix"] = ($row["script_fix"] ?? '');
                $row1["quantity_issue"] = ($row["quantity_issue"] ?? '');
                $row1["pick_parent"] = ($row["pick_parent"] ?? '');
                $row1["cancel_hour"] = ($row["cancel_hour"] ?? '');
                $row1["cavg_parent"] = ($row["cavg_parent"] ?? '');
                if(isset($row['accumulations'])){
                    $row1['accumulations']   = $row['accumulations']['profit'];    
                }

                // Resume array total profit
                if (!empty($row["resume_order_id"]) && !empty($row["resume_order_arr"]) && in_array('resume_completed', $order_status)) {
                    $total = 0;
                    $resumeArrCount = count($row["resume_order_arr"]);
                    if ($resumeArrCount > 0) {
                        for ($ri = 0; $ri < $resumeArrCount; $ri++) {
                            $total += $row["resume_order_arr"][$ri]['resumeLossPercentage'];
                        }
                    }
                    $lastRow = false;
                    //lth_pause check
                    if (!empty($row["status"]) && ($row['status'] == 'pause' || $row['status'] == 'FILLED') && !empty($row["is_sell_order"]) && ($row['is_sell_order'] == 'pause' || $row['is_sell_order'] == 'resume_pause')) {
                        $lastRow = true;
                        //sold check
                    } else if (!empty($row["is_sell_order"]) && $row['is_sell_order'] == 'sold') {
                        $lastRow = true;
                    }

                    if ($lastRow) {
                        $tObj = [];

                        if (!empty($row['last_purchase'])) {
                            $tObj['resumePurchased'] = $row['last_purchase'];
                        } else {
                            $tObj['resumePurchased'] = !empty($row['purchased_price']) ? $row['purchased_price'] : '';
                        }

                        if (!empty($row['last_sell'])) {
                            $tObj['resumeSell'] = $row['last_sell'];
                        } else {
                            $tObj['resumeSell'] = !empty($row['market_sold_price']) ? $row['market_sold_price'] : '';
                        }

                        $resLastP = @!is_nan($tObj['resumePurchased']) ? $tObj['resumePurchased'] : 0;
                        $resLastS = @!is_nan($tObj['resumeSell']) ? $tObj['resumeSell'] : 0;

                        $pl = 0;
                        if ($resLastP == 0 || $resLastS == 0) {
                            $pl = 0;
                        } else {
                            $pl = ((($resLastS - $resLastP) * 100) / $resLastP);
                        }
                        $pl = @!is_nan($pl) ? $pl : 0;

                        $total = $total + $pl;
                    }
                    $row1['resume_order_arr_total'] = @!is_nan($total) ? number_format($total, 2) : 0;
                } //End Resume array total profit


                if (count($sell_ids_arr) > 1) {
                    $row1['duplicate_sell'] = 'yes';
                } else {
                    $row1['duplicate_sell'] = 'no';
                }

                if (count($buy_ids_arr) > 1) {
                    $row1['duplicate_buy'] = 'yes';
                } else {
                    $row1['duplicate_buy'] = 'no';
                }

                $row1['opportunityId'] = (!empty($row['opportunityId']) ? $row['opportunityId'] : '');
                $row1['mapped_order'] = (!empty($row['mapped_order']) && $row['mapped_order'] == 1 ? 'yes' : '');
                

                $id = $row['admin_id'];
                $data_user = $this->get_username_from_user($id);

                $row1['admin'] = $data_user;
                $_id = $order['_id'];

                // $error = $this->get_error_type($_id);
                // $row1['log'] = $error;
                
                array_push($new_order_arrray, $row1);
                unset($row, $row1);
            }

            $data['json'] = json_encode($session_data);
            $data['total_count'] = $total_count;
            $data['serial_number'] = $serial_number;
            $new_order_arrray['average'] = $test_arr;
            $data['orders'] = $new_order_arrray;
          
            unset($new_order_arrray, $orders);
        }
        $coins = $this->mod_coins->get_all_coins();
        $data['coins'] = $coins;

        //set orders Array for CSV
        $csvOrdersArr = [];

        $srNum = 1;        
        $errArr = ['error', 'new_ERROR', 'FILLED_ERROR', 'submitted_ERROR', 'LTH_ERROR', 'canceled_ERROR', 'credentials_ERROR', 'fraction_ERROR', 'SELL_ID_ERROR'];
        $nanArr = ['nan', 'Nan', 'NAN', 'inf', 'Inf', 'INF', ''];


        $ordersArrLength = count($data['orders']);

        $parentSrNum = 1;

        for ($i = 0; $i < $ordersArrLength; $i++) {
            $value = &$data['orders'][$i];
            // echo "<pre>"; print_r($value); exit;
            $csvOrder = [];

            $csvOrder['sr_num'] = $parentSrNum;
            $csvOrder['order_id'] = $value['_id'];
            $srNum++;

            $csvOrder['Coin'] = $value['symbol'];
            $csvOrder['Price'] = (isset($value['purchased_price']) ? num($value['purchased_price']) : num($value['price']));
            // echo "<pre>"; print_r($csvOrder); exit;
            //Order Type
             // Check and loop through cost_avg_array if it exists
             if (isset($value['_id'])) {
               
                if ($order_exchange != 'binance') {
                    $collection_str1 = 'sold_buy_orders_' . $order_exchange;
                    $collection_str2 = 'buy_orders_' . $order_exchange;
                } else {
                    $collection_str1 = 'sold_buy_orders';
                    $collection_str2 = 'buy_orders';
                }

                $pending = $connetct->$collection_str2->find(['_id' => $this->mongo_db->mongoId((string)$value['_id'])]);
                $sold_odr = $connetct->$collection_str1->find(['_id' => $this->mongo_db->mongoId((string)$value['_id'])]);
    
                $pending_odr_arr = iterator_to_array($pending);
                $sold_odr_arr = iterator_to_array($sold_odr);

                if (
                    (count($pending_odr_arr) > 0 || count($sold_odr_arr) > 0) &&
                    (isset($pending_odr_arr[0]['cost_avg_array']) || isset($sold_odr_arr[0]['cost_avg_array']))
                ){
                    $cost_avg_array = isset($pending_odr_arr[0]['cost_avg_array']) ? $pending_odr_arr[0]['cost_avg_array'] : $sold_odr_arr[0]['cost_avg_array'];
                    $main_parent_array = (array) $cost_avg_array;
                    $firstElement = array_shift($main_parent_array);
                    // echo "<pre>"; print_r( (array) $cost_avg_array); exit;
                    // $firstElement = array_shift( $cost_avg_array); // Remove the first element
                    // echo "first_element ". print_r($firstElement). " <--- 1st element \n \n \n <br>";
                    // $firstElement = array_shift($cost_avg_array); // Remove the first element
                    $subSrNum = 1; // Reset the subSrNum counter

                    foreach ($main_parent_array as $index => $costAvgValue) {
                        // echo "<pre>"; print_r($costAvgValue. " <--loop element"); exit;
                        $csvOrderChild = [];
                        $buy_order_id = $this->mongo_db->mongoId((string)$costAvgValue['buy_order_id']);
                        if ($costAvgValue['order_sold'] == 'yes') {
                            $child_order = $connetct->$collection_str1->find(['_id' => $buy_order_id]);
                            $child_order_data = iterator_to_array($child_order);
                            $child_order_array = $child_order_data[0];
                        }else{
                            $child_order = $connetct->$collection_str2->find(['_id' => $buy_order_id]);
                            $child_order_data = iterator_to_array($child_order);
                            $child_order_array = $child_order_data[0];
                        }
    
                        $csvOrderC['sr_num'] = $parentSrNum.'.'.$subSrNum;
                        $csvOrderC['order_id'] = $child_order_array['_id'];
                        $srNum++;
    
                        $csvOrderC['Coin'] = $child_order_array['symbol'];
                        $csvOrderC['Price'] = (isset($child_order_array['purchased_price']) ? num($child_order_array['purchased_price']) : num($child_order_array['price']));
                        // echo "<pre>"; print_r($csvOrderC); exit;
                        //Order Type
                        if ($child_order_array['trigger_type'] != 'no') {
                            if ($child_order_array['trigger_type'] == 'barrier_percentile_trigger') {
                                $csvOrderC['Order Type'] = 'BPT';
                            } else {
                                $csvOrderC['Order Type'] = strtoupper(str_replace('_', ' ', $child_order_array['trigger_type']));
                            }
                        } else {
                            $csvOrderC['Order Type'] = 'MANUAL ORDER';
                        }
                        //end Order Type
                        
                        // $market_value = $this->mod_dashboard->get_market_value($value['symbol']);
                        $filter_user_data = $this->session->userdata('filter_order_data');
                        if (!empty($filter_user_data['filter_by_exchange'])) {
                            $order_exchange = $filter_user_data['filter_by_exchange'];
                        }
    
                        $BTCUSDTmarket_value = $this->mod_dashboard->get_market_value2('BTCUSDT', $order_exchange);
                        $market_value = $this->mod_dashboard->get_market_value2($value['symbol'], $order_exchange);
                        
                        $logo = $this->mod_coins->get_coin_logo($child_order_array['symbol']);
                        if ($value['status'] != 'new') {
                            if ($child_order_array['status'] == 'FILLED') {
                                $market_value333 = num($child_order_array['purchased_price']);
                            } else {
                                $market_value333 = num($child_order_array['market_value']);
                            }
                        } else {
                            $market_value333 = num($market_value);
                        }
                        if ($child_order_array['status'] == 'new') {
                            $current_order_price = num($child_order_array['price']);
                        } else {
                            if ($child_order_array['status'] == 'FILLED') {
                                $market_value333 = num($child_order_array['purchased_price']);
                            } else {
                                $market_value333 = num($child_order_array['market_value']);
                            }
                        }
    
                        if ($child_order_array['status'] == 'canceled' && (empty($child_order_array['parent_status']) || $child_order_array['parent_status'] != 'parent')) {
                            $market_value333 = !empty($child_order_array['purchased_price']) ? num($child_order_array['purchased_price']) : num($child_order_array['price']);
                        }
    
                        $current_data = $market_value333 - $current_order_price;
                        $market_data = ($current_data * 100 / $market_value333);
                        $market_data = number_format((float) $market_data, 2, '.', '');
    
                        $csvOrderC['Level'] = $child_order_array['order_level'];
                        $csvOrderC['quantity'] = $child_order_array['quantity'];
                        $usd_worth_tt = get_usd_worth($child_order_array['symbol'], $child_order_array['quantity'], $market_value333, $BTCUSDTmarket_value);
                        $csvOrderC['usd worth'] = "$".$usd_worth_tt;
                        if (!empty($child_order_array["created_date"])) {
                            $created_m_date = new DateTime($child_order_array["created_date"]->toDateTime()->format("c"));
                            $created_m_date->setTimeZone(new DateTimeZone($logged_timezone));
                            $created_m_date_format = $created_m_date->format('Y-m-d g:i:s A T');
        
                            $created_date_time_csv["created_date"] = time_elapsed_string($child_order_array["created_date"]->toDateTime()->format("Y-m-d g:i:s A"));
                            $created_date_time_csv["created_date_hover"] = $created_m_date_format;
                        } else {
                            $created_date_time_csv["created_date"] = '';
                            $created_date_time_csv["created_date_hover"] = '';
                        }

                        $csvOrderC['Created Date'] = $created_date_time_csv["created_date_hover"];
                        $csvOrderC['Market Sold Price'] = $child_order_array['market_sold_price'];
                        $csvOrderC['Accumulations'] = $child_order_array['accumulations'];
                        // $csvOrderC['Sell price'] = $value['sell_price']; // this sell price is different from the market sold price thats why i commented it out on jan 27 2023.
                        //P/L
                        $csvOrderC['P/L'] = num($market_value333);
                        //end Market(%)
    
                        //Market(%)
                        $csvOrderC['Market(%)'] = ($child_order_array['is_sell_order'] != 'sold' && $child_order_array['is_sell_order'] != 'yes') ? $market_data : '-';
                        //end Market(%)
    
                        //Status
                        if ($child_order_array['is_sell_order'] == 'yes') {
                            if ($child_order_array['status'] == 'LTH') {
                                $csvOrderC['Status'] = "LTH OPEN";
                            } elseif (in_array($child_order_array['status'], $errArr)) {
                                $csvOrderC['Status'] = strtoupper(str_replace('_', ' ', $child_order_array['status']));
                            } elseif ($child_order_array['status'] == 'submitted_for_sell') {
                                $csvOrderC['Status'] = strtoupper(str_replace('_', ' ', $child_order_array['status']));
                            } elseif ($child_order_array['status'] == 'fraction_submitted_sell') {
                                $csvOrderC['Status'] = strtoupper(str_replace('_', ' ', $child_order_array['status']));
                            } elseif ($child_order_array['status'] == 'canceled') {
                                $csvOrderC['Status'] = strtoupper(str_replace('_', ' ', $child_order_array['status']));
                            }elseif($child_order_array['status'] == 'new'){
                                $csvOrderC['Status'] = strtoupper(str_replace('_', ' ', $child_order_array['status']));
                            }else{
                                $csvOrderC['Status'] = 'OPEN';
                            }
                        } else {
                            $csvOrderC['Status'] = strtoupper(str_replace('_', ' ', $child_order_array['status']));
                        }
                        if ($child_order_array['parent_status'] == 'parent') {
                            $csvOrderC['Status'] = 'Parent Order';
                        }
                        //end Status
    
                        //Profit(%)
                        if(!empty($child_order_array['resume_order_arr_total'])){
                            $csvOrderC['Profit(%)'] = $child_order_array['resume_order_arr_total'] . '%';
                        }else{
                            if ($child_order_array['market_sold_price'] != "") {
                                $market_sold_price = num($child_order_array['market_sold_price']);
                                $pp = num($child_order_array['purchased_price']);
                                $profitPercentage = calculateProfitPercentage($pp, $market_sold_price);
                                $csvOrderC['Profit(%)'] = $profitPercentage . '%';
                            } else {
                                if ($child_order_array['status'] == 'FILLED') {
                                    if ($child_order_array['is_sell_order'] == 'yes') {
                                        $pp = num($child_order_array['purchased_price']);
                                        $profitPercentage = calculateProfitPercentage($pp, $market_value);
                                        $csvOrderC['Profit(%)'] = $profitPercentage . '%';
                                    } else {
                                        $csvOrderC['Profit(%)'] = '-';
                                    }
                                } elseif ($child_order_array['status'] == 'LTH') {
                                    if ($child_order_array['is_sell_order'] == 'yes') {
                                        $pp = num($child_order_array['purchased_price']);
                                        $profitPercentage = calculateProfitPercentage($pp, $market_value);
                                        $csvOrderC['Profit(%)'] = $profitPercentage . '%';
                                    } else {
                                        $csvOrderC['Profit(%)'] = '-';
                                    }
                                } else {
                                    $csvOrderC['Profit(%)'] = '-';
                                }
                            }
                        }
                        //end Profit(%)
    
                        //Target Profit(%)
                        
                        if ($child_order_array['trigger_type'] == 'no' && $child_order_array['status'] == 'LTH') {
                            $target_profit = $child_order_array['lth_profit'];
                        }else if ($child_order_array['trigger_type'] == 'no' && $child_order_array['status'] == 'FILLED') {
                            $target_profit = $child_order_array['sell_profit_percent'];
                        }else if ($child_order_array['trigger_type'] != 'no' && $child_order_array['status'] == 'LTH') {
                            $target_profit = $child_order_array['lth_profit'];
                        }else if ($child_order_array['trigger_type'] != 'no' && $child_order_array['status'] == 'FILLED') {
                            $target_profit = $child_order_array['defined_sell_percentage'];
                        }else if ($child_order_array['parent_status'] == 'parent') {
                            $target_profit = $child_order_array['defined_sell_percentage'];
                        }
                        $csvOrderC['Target Profit(%)'] = (!in_array($target_profit, $nanArr) ? $target_profit.'%' : '-');
                        //end Target Profit(%)
    
                        //Slippage(%)
                        if (!in_array($target_profit, $nanArr) && !in_array($profitPercentage, $nanArr) && $child_order_array['is_sell_order'] == 'sold') {
                            if ($child_order_array['is_lth_order'] == 'yes' && !in_array($child_order_array['lth_profit'], $nanArr)) {
                                $target_profit = $child_order_array['lth_profit'];
                            }
                            if ($profitPercentage >= $target_profit) {
                                $slippage = ($profitPercentage - $target_profit);
                                $slippage = round($slippage, 3) . '%';
                            } else if ($profitPercentage < $target_profit) {
                                $slippage = ($target_profit - $profitPercentage);
                                $slippage = '-' . round($slippage, 3) . '%';
                            } else {
                                $slippage = '-';
                            }
                        } else {
                            $slippage = '-';
                        }
                        $csvOrderC['Slippage(%)'] = $slippage;
                        //end Slippage(%)
    
                        //LTH
                        if ($value['status'] == 'LTH') {
                            $csvOrderC['LTH'] = 'LTH';
                        } else {
                            if ($value['is_sell_order'] == 'sold' && $value['is_lth_order'] == 'yes') {
                                $csvOrderC['LTH'] = 'LTH';
                            } else {
                                $csvOrderC['LTH'] = 'Normal';
                            }
                        }
                        //end LTH
    
                        //LTH Profit (%)
                        $csvOrderC['LTH Profit (%)'] = ($child_order_array['lth_profit'] != '' && !in_array(trim($child_order_array['lth_profit']), $nanArr) ? $child_order_array['lth_profit'] : '-');
                        //end LTH Profit (%)
    
                        //Stop Loss (%)
                        $csvOrderC['Stop Loss (%)'] = ($child_order_array['loss_percentage'] != '' && !in_array(trim($child_order_array['loss_percentage']), $nanArr) ? $child_order_array['loss_percentage'].($child_order_array['iniatial_trail_stop'] > $child_order_array['purchased_price'] ? " (+)" : " (-)") : '-');
                        //end Stop Loss (%)
    
                        //Sub Status
                        if (in_array($child_order_array['status'], $errArr)) {
                            $csvOrderC['Sub Status'] = str_replace('_', ' ', $child_order_array['status']);
                        } else {
                            if ($child_order_array['status'] == 'FILLED') {
                                if ($child_order_array['is_sell_order'] == 'yes') {
                                    if (!empty($child_order_array['sell_order_id'])) {
                                        $sell_status = $this->mod_buy_orders->is_sell_order_in_error_status($child_order_array['sell_order_id']);
                                        $sell_status_submit = $this->mod_buy_orders->is_sell_order_in_submitted_status($child_order_array['sell_order_id']);
                                    } else {
                                        $sell_status = '';
                                        $sell_status_submit = '';
                                    }
                                    if ($sell_status) {
                                    } elseif ($sell_status_submit) {
                                        $csvOrderC['Sub Status'] = 'SUBMITTED FOR SELL';
                                    } else {
                                        $csvOrderC['Sub Status'] = 'WAITING FOR SELL';
                                    }
                                } elseif ($child_order_array['is_sell_order'] == 'sold') {
                                    $csvOrderC['Sub Status'] = ($child_order_array['is_manual_sold'] == 'yes' ? 'Sold Manually' : 'Sold');
                                } elseif ($child_order_array['is_sell_order'] == 'pause') {
                                    $csvOrderC['Sub Status'] = 'Paused';
                                }
                            } elseif ($child_order_array['status'] == 'LTH') {
                                if ($sell_status) {
                                    $csvOrderC['Sub Status'] = 'ERROR IN SELL ' . $sell_status . '';
                                } elseif ($sell_status_submit) {
                                    $csvOrderC['Sub Status'] = 'SUBMITTED FOR SELL';
                                } else {
                                    $csvOrderC['Sub Status'] = 'WAITING FOR SELL';
                                }
                            } else {
                                $csvOrderC['Sub Status'] = '-';
                            }
    
                            if ($child_order_array['resume_status'] == 'completed') {
                                $csvOrderC['Sub Status'] = ' Resume Completed ';
                            } else if ($child_order_array['status'] == 'FILLED' && ($child_order_array['is_sell_order'] == 'yes' || $child_order_array['is_sell_order'] == 'sold') && !empty($child_order_array['resume_order_id'])) {
                                $csvOrderC['Sub Status'] = ' Resume Child ';
                            } else if ($child_order_array['is_sell_order'] == 'resume_pause' && empty($child_order_array['resume_order_arr'])) {
                                $csvOrderC['Sub Status'] = ' Resumed ';
                            } else if ($child_order_array['is_sell_order'] == 'resume_pause' && !empty($child_order_array['resume_order_arr'])) {
                                $csvOrderC['Sub Status'] = ' In Progress ';
                            } else if ($child_order_array['is_sell_order'] == 'pause') {
                                $csvOrderC['Sub Status'] = ' Paused ';
                            }    
                        }
                        //end Sub Status
                        
                        if ($child_order_array['mapped_order'] == 'yes') {
                            $csvOrderC['Sub Status'] .= ' Mapped ';
                        }
                        if($child_order_array['script_fix'] != '' && $child_order_array['script_fix'] == 1){
                            $csvOrderC['Sub Status'] .= ', Script Fixed'; 
                        }else if($child_order_array['script_fix'] != '' && $child_order_array['script_fix'] == 0){
                            $csvOrderC['Sub Status'] .= ', Script Ignored'; 
                        }
                        
                        if($child_order_array['quantity_issue'] != '' && $child_order_array['quantity_issue'] == 0){
                            $csvOrderC['Sub Status'] .= ', Quantity Issue'; 
                        }
                        if($child_order_array['pick_parent'] == 'yes'){
                            $csvOrderC['Sub Status'] .= ', Pick parent (Yes)'; 
                        }
                        if($child_order_array['pick_parent'] == 'no'){
                            $csvOrderC['Sub Status'] .= ', Pick parent (No)'; 
                        }
                        if (!empty($child_order_array['cancel_hour'])) {
                            $csvOrderC['Sub Status'] .= ', Deep price';
                        }
                        if ($child_order_array['cavg_parent'] == 'yes') {
                            $csvOrderC['Sub Status'] .= ', Cost Avg Parent';
                        }
    
                        // Max(%)/Min(%)
                        if (isset($child_order_array['market_heighest_value']) && $child_order_array['market_heighest_value'] != '') {
                            $five_hour_max_market_price = $child_order_array['market_heighest_value'];
                            $purchased_price = (float) $child_order_array['purchased_price'];
                            $profit = $five_hour_max_market_price - $purchased_price;
                            $profit_margin = ($profit / $five_hour_max_market_price) * 100;
                            $profit_per = ($profit) * (100 / $purchased_price);
                            $t_max = number_format($profit_per, 2) . '%';
                        } else {
                            $t_max = "-";
                        }
                        if (isset($child_order_array['market_lowest_value']) && $child_order_array['market_lowest_value'] != '') {
                            $market_lowest_value = $child_order_array['market_lowest_value'];
                            $purchased_price = (float) $child_order_array['purchased_price'];
                            $profit = $market_lowest_value - $purchased_price;
                            $profit_margin = ($profit / $market_lowest_value) * 100;
                            $profit_per = ($profit) * (100 / $purchased_price);
                            $t_min = number_format($profit_per, 2) . '%';
                        } else {
                            $t_min = "-";
                        }
                        $csvOrderC['Max(%)/Min(%)'] = $t_max . " | " . $t_min;
                        // end Max(%)/Min(%)
    
                        //5hMax(%)/5hMin(%)
                        if (isset($child_order_array['5_hour_max_market_price']) && $child_order_array['5_hour_max_market_price'] != '') {
                            $five_hour_max_market_price = $child_order_array['5_hour_max_market_price'];
                            $market_sold_price = (float) $child_order_array['market_sold_price'];
                            $profit = $five_hour_max_market_price - $market_sold_price;
                            $profit_margin = ($profit / $five_hour_max_market_price) * 100;
                            $profit_per = ($profit) * (100 / $market_sold_price);
                            $max_5h = number_format($profit_per, 2) . '%';
                        } else {
                            $max_5h = "-";
                        }
                        if (isset($child_order_array['5_hour_min_market_price']) && $child_order_array['5_hour_min_market_price'] != '') {
                            $market_lowest_value = $child_order_array['5_hour_min_market_price'];
                            $market_sold_price = (float) $child_order_array['market_sold_price'];
                            $profit = $market_lowest_value - $market_sold_price;
                            $profit_margin = ($profit / $market_lowest_value) * 100;
                            $profit_per = ($profit) * (100 / $market_sold_price);
                            $min_5h = number_format($profit_per, 2) . '%';
                        } else {
                            $min_5h = "-";
                        }
                        $csvOrderC['5hMax(%)/5hMin(%)'] = $max_5h . " | " . $min_5h;
                        // end 5hMax(%)/5hMin(%)
                        $id = $child_order_array['admin_id'];
                        $data_user = $this->get_username_from_user($id);
                        $csvOrderC['User Info'] = $data_user->username;
                        $csvOrderC['User IP'] = $data_user->trading_ip;

                        //modified dateTime show in CSV
                        if (!empty($child_order_array["modified_date"])) {
                            // $created_m_date = new DateTime($child_order_array["modified_date"]->toDateTime()->format("c"));
                            // $created_m_date->setTimeZone(new DateTimeZone($logged_timezone));
                            // $created_m_date_format = $created_m_date->format('Y-m-d g:i:s A T');
        
                            $modified_date_time_csv["modified_date"] = '';//time_elapsed_string($child_order_array["modified_date"]->toDateTime()->format("Y-m-d g:i:s A"));
                            $modified_date_time_csv["modified_date_hover"] = '';// $created_m_date_format;
                        } else {
                            $modified_date_time_csv["modified_date"] = '';
                            $modified_date_time_csv["modified_date_hover"] = '';
                        }
                        //modified dateTime show in CSV end

                        $csvOrderC['Last Modified Date'] = $modified_date_time_csv["modified_date_hover"];
    
                        
                        $csvOrdersArr[] = $csvOrderC;
                        unset($child_order_array, $csvOrderC);
                        $subSrNum++;
                    }
                }

               
            }

            if ($value['trigger_type'] != 'no') {
                if ($value['trigger_type'] == 'barrier_percentile_trigger') {
                    $csvOrder['Order Type'] = 'BPT';
                } else {
                    $csvOrder['Order Type'] = strtoupper(str_replace('_', ' ', $value['trigger_type']));
                }
            } else {
                $csvOrder['Order Type'] = 'MANUAL ORDER';
            }
            //end Order Type
            
            // $market_value = $this->mod_dashboard->get_market_value($value['symbol']);
            $filter_user_data = $this->session->userdata('filter_order_data');
            if (!empty($filter_user_data['filter_by_exchange'])) {
                $order_exchange = $filter_user_data['filter_by_exchange'];
            }

            $BTCUSDTmarket_value = $this->mod_dashboard->get_market_value2('BTCUSDT', $order_exchange);
            $market_value = $this->mod_dashboard->get_market_value2($value['symbol'], $order_exchange);
            
            $logo = $this->mod_coins->get_coin_logo($value['symbol']);
            if ($value['status'] != 'new') {
                if ($value['status'] == 'FILLED') {
                    $market_value333 = num($value['purchased_price']);
                } else {
                    $market_value333 = num($value['market_value']);
                }
            } else {
                $market_value333 = num($market_value);
            }
            if ($value['status'] == 'new') {
                $current_order_price = num($value['price']);
            } else {
                if ($value['status'] == 'FILLED') {
                    $market_value333 = num($value['purchased_price']);
                } else {
                    $market_value333 = num($value['market_value']);
                }
            }

            if ($value['status'] == 'canceled' && (empty($value['parent_status']) || $value['parent_status'] != 'parent')) {
                $market_value333 = !empty($value['purchased_price']) ? num($value['purchased_price']) : num($value['price']);
            }

            $current_data = $market_value333 - $current_order_price;
            $market_data = ($current_data * 100 / $market_value333);
            $market_data = number_format((float) $market_data, 2, '.', '');

            $csvOrder['Level'] = $value['order_level'];

            if(isset($csvOrder['cost_avg_array']) && count($csvOrder['cost_avg_array'])){
                $csvOrder['quantity'] = $value['cost_avg_array'][0]['filledQtyBuy'];
            }else{
                $csvOrder['quantity'] = $value['quantity'];
            }

            $csvOrder['quantity'] = $value['quantity'];
            $usd_worth_tt = get_usd_worth($value['symbol'], $value['quantity'], $market_value333, $BTCUSDTmarket_value);
            $csvOrder['usd worth'] = "$".$usd_worth_tt;
            $csvOrder['Created Date'] = $value['created_date_hover'];
            $csvOrder['Market Sold Price'] = $value['market_sold_price'];
            $csvOrder['Accumulations'] = $value['accumulations'];
            // $csvOrder['Sell price'] = $value['sell_price']; // this sell price is different from the market sold price thats why i commented it out on jan 27 2023.
            //P/L
            $csvOrder['P/L'] = num($market_value333);
            //end Market(%)

            //Market(%)
            $csvOrder['Market(%)'] = ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') ? $market_data : '-';
            //end Market(%)

            //Status
            if ($value['is_sell_order'] == 'yes') {
                if ($value['status'] == 'LTH') {
                    $csvOrder['Status'] = "LTH OPEN";
                } elseif (in_array($value['status'], $errArr)) {
                    $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
                } elseif ($value['status'] == 'submitted_for_sell') {
                    $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
                } elseif ($value['status'] == 'fraction_submitted_sell') {
                    $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
                } elseif ($value['status'] == 'canceled') {
                    $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
                }elseif($value['status'] == 'new'){
                    $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
                }else{
                    $csvOrder['Status'] = 'OPEN';
                }
            } else {
                $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
            }
            if ($value['parent_status'] == 'parent') {
                $csvOrder['Status'] = 'Parent Order';
            }
            //end Status

            //Profit(%)
            if(!empty($value['resume_order_arr_total'])){
                $csvOrder['Profit(%)'] = $value['resume_order_arr_total'] . '%';
            }else{
                if ($value['market_sold_price'] != "") {
                    $market_sold_price = num($value['market_sold_price']);
                    $pp = num($value['purchased_price']);
                    $profitPercentage = calculateProfitPercentage($pp, $market_sold_price);
                    $csvOrder['Profit(%)'] = $profitPercentage . '%';
                } else {
                    if ($value['status'] == 'FILLED') {
                        if ($value['is_sell_order'] == 'yes') {
                            $pp = num($value['purchased_price']);
                            $profitPercentage = calculateProfitPercentage($pp, $market_value);
                            $csvOrder['Profit(%)'] = $profitPercentage . '%';
                        } else {
                            $csvOrder['Profit(%)'] = '-';
                        }
                    } elseif ($value['status'] == 'LTH') {
                        if ($value['is_sell_order'] == 'yes') {
                            $pp = num($value['purchased_price']);
                            $profitPercentage = calculateProfitPercentage($pp, $market_value);
                            $csvOrder['Profit(%)'] = $profitPercentage . '%';
                        } else {
                            $csvOrder['Profit(%)'] = '-';
                        }
                    } else {
                        $csvOrder['Profit(%)'] = '-';
                    }
                }
            }
            //end Profit(%)

            //Target Profit(%)
            
            if ($value['trigger_type'] == 'no' && $value['status'] == 'LTH') {
                $target_profit = $value['lth_profit'];
            }else if ($value['trigger_type'] == 'no' && $value['status'] == 'FILLED') {
                $target_profit = $value['sell_profit_percent'];
            }else if ($value['trigger_type'] != 'no' && $value['status'] == 'LTH') {
                $target_profit = $value['lth_profit'];
            }else if ($value['trigger_type'] != 'no' && $value['status'] == 'FILLED') {
                $target_profit = $value['defined_sell_percentage'];
            }else if ($value['parent_status'] == 'parent') {
                $target_profit = $value['defined_sell_percentage'];
            }
            $csvOrder['Target Profit(%)'] = (!in_array($target_profit, $nanArr) ? $target_profit.'%' : '-');
            //end Target Profit(%)

            //Slippage(%)
            if (!in_array($target_profit, $nanArr) && !in_array($profitPercentage, $nanArr) && $value['is_sell_order'] == 'sold') {
                if ($value['is_lth_order'] == 'yes' && !in_array($value['lth_profit'], $nanArr)) {
                    $target_profit = $value['lth_profit'];
                }
                if ($profitPercentage >= $target_profit) {
                    $slippage = ($profitPercentage - $target_profit);
                    $slippage = round($slippage, 3) . '%';
                } else if ($profitPercentage < $target_profit) {
                    $slippage = ($target_profit - $profitPercentage);
                    $slippage = '-' . round($slippage, 3) . '%';
                } else {
                    $slippage = '-';
                }
            } else {
                $slippage = '-';
            }
            $csvOrder['Slippage(%)'] = $slippage;
            //end Slippage(%)

            //LTH
            if ($value['status'] == 'LTH') {
                $csvOrder['LTH'] = 'LTH';
            } else {
                if ($value['is_sell_order'] == 'sold' && $value['is_lth_order'] == 'yes') {
                    $csvOrder['LTH'] = 'LTH';
                } else {
                    $csvOrder['LTH'] = 'Normal';
                }
            }
            //end LTH

            //LTH Profit (%)
            $csvOrder['LTH Profit (%)'] = ($value['lth_profit'] != '' && !in_array(trim($value['lth_profit']), $nanArr) ? $value['lth_profit'] : '-');
            //end LTH Profit (%)

            //Stop Loss (%)
            $csvOrder['Stop Loss (%)'] = ($value['loss_percentage'] != '' && !in_array(trim($value['loss_percentage']), $nanArr) ? $value['loss_percentage'].($value['iniatial_trail_stop'] > $value['purchased_price'] ? " (+)" : " (-)") : '-');
            //end Stop Loss (%)

            //Sub Status
            if (in_array($value['status'], $errArr)) {
                $csvOrder['Sub Status'] = str_replace('_', ' ', $value['status']);
            } else {
                if ($value['status'] == 'FILLED') {
                    if ($value['is_sell_order'] == 'yes') {
                        if (!empty($value['sell_order_id'])) {
                            $sell_status = $this->mod_buy_orders->is_sell_order_in_error_status($value['sell_order_id']);
                            $sell_status_submit = $this->mod_buy_orders->is_sell_order_in_submitted_status($value['sell_order_id']);
                        } else {
                            $sell_status = '';
                            $sell_status_submit = '';
                        }
                        if ($sell_status) {
                        } elseif ($sell_status_submit) {
                            $csvOrder['Sub Status'] = 'SUBMITTED FOR SELL';
                        } else {
                            $csvOrder['Sub Status'] = 'WAITING FOR SELL';
                        }
                    } elseif ($value['is_sell_order'] == 'sold') {
                        $csvOrder['Sub Status'] = ($value['is_manual_sold'] == 'yes' ? 'Sold Manually' : 'Sold');
                    } elseif ($value['is_sell_order'] == 'pause') {
                        $csvOrder['Sub Status'] = 'Paused';
                    }
                } elseif ($value['status'] == 'LTH') {
                    if ($sell_status) {
                        $csvOrder['Sub Status'] = 'ERROR IN SELL ' . $sell_status . '';
                    } elseif ($sell_status_submit) {
                        $csvOrder['Sub Status'] = 'SUBMITTED FOR SELL';
                    } else {
                        $csvOrder['Sub Status'] = 'WAITING FOR SELL';
                    }
                } else {
                    $csvOrder['Sub Status'] = '-';
                }

                if ($value['resume_status'] == 'completed') {
                    $csvOrder['Sub Status'] = ' Resume Completed ';
                } else if ($value['status'] == 'FILLED' && ($value['is_sell_order'] == 'yes' || $value['is_sell_order'] == 'sold') && !empty($value['resume_order_id'])) {
                    $csvOrder['Sub Status'] = ' Resume Child ';
                } else if ($value['is_sell_order'] == 'resume_pause' && empty($value['resume_order_arr'])) {
                    $csvOrder['Sub Status'] = ' Resumed ';
                } else if ($value['is_sell_order'] == 'resume_pause' && !empty($value['resume_order_arr'])) {
                    $csvOrder['Sub Status'] = ' In Progress ';
                } else if ($value['is_sell_order'] == 'pause') {
                    $csvOrder['Sub Status'] = ' Paused ';
                }    
            }
            //end Sub Status
            
            if ($value['mapped_order'] == 'yes') {
                $csvOrder['Sub Status'] .= ' Mapped ';
            }
            if($value['script_fix'] != '' && $value['script_fix'] == 1){
                $csvOrder['Sub Status'] .= ', Script Fixed'; 
            }else if($value['script_fix'] != '' && $value['script_fix'] == 0){
                $csvOrder['Sub Status'] .= ', Script Ignored'; 
            }
            
            if($value['quantity_issue'] != '' && $value['quantity_issue'] == 0){
                $csvOrder['Sub Status'] .= ', Quantity Issue'; 
            }
            if($value['pick_parent'] == 'yes'){
                $csvOrder['Sub Status'] .= ', Pick parent (Yes)'; 
            }
            if($value['pick_parent'] == 'no'){
                $csvOrder['Sub Status'] .= ', Pick parent (No)'; 
            }
            if (!empty($value['cancel_hour'])) {
                $csvOrder['Sub Status'] .= ', Deep price';
            }
            if ($value['cavg_parent'] == 'yes') {
                $csvOrder['Sub Status'] .= ', Cost Avg Parent';
            }

            // Max(%)/Min(%)
            if (isset($value['market_heighest_value']) && $value['market_heighest_value'] != '') {
                $five_hour_max_market_price = $value['market_heighest_value'];
                $purchased_price = (float) $value['purchased_price'];
                $profit = $five_hour_max_market_price - $purchased_price;
                $profit_margin = ($profit / $five_hour_max_market_price) * 100;
                $profit_per = ($profit) * (100 / $purchased_price);
                $t_max = number_format($profit_per, 2) . '%';
            } else {
                $t_max = "-";
            }
            if (isset($value['market_lowest_value']) && $value['market_lowest_value'] != '') {
                $market_lowest_value = $value['market_lowest_value'];
                $purchased_price = (float) $value['purchased_price'];
                $profit = $market_lowest_value - $purchased_price;
                $profit_margin = ($profit / $market_lowest_value) * 100;
                $profit_per = ($profit) * (100 / $purchased_price);
                $t_min = number_format($profit_per, 2) . '%';
            } else {
                $t_min = "-";
            }
            $csvOrder['Max(%)/Min(%)'] = $t_max . " | " . $t_min;
            // end Max(%)/Min(%)

            //5hMax(%)/5hMin(%)
            if (isset($value['5_hour_max_market_price']) && $value['5_hour_max_market_price'] != '') {
                $five_hour_max_market_price = $value['5_hour_max_market_price'];
                $market_sold_price = (float) $value['market_sold_price'];
                $profit = $five_hour_max_market_price - $market_sold_price;
                $profit_margin = ($profit / $five_hour_max_market_price) * 100;
                $profit_per = ($profit) * (100 / $market_sold_price);
                $max_5h = number_format($profit_per, 2) . '%';
            } else {
                $max_5h = "-";
            }
            if (isset($value['5_hour_min_market_price']) && $value['5_hour_min_market_price'] != '') {
                $market_lowest_value = $value['5_hour_min_market_price'];
                $market_sold_price = (float) $value['market_sold_price'];
                $profit = $market_lowest_value - $market_sold_price;
                $profit_margin = ($profit / $market_lowest_value) * 100;
                $profit_per = ($profit) * (100 / $market_sold_price);
                $min_5h = number_format($profit_per, 2) . '%';
            } else {
                $min_5h = "-";
            }
            $csvOrder['5hMax(%)/5hMin(%)'] = $max_5h . " | " . $min_5h;
            // end 5hMax(%)/5hMin(%)
            // echo "<pre>"; print_r($value['admin']); exit;
            $csvOrder['User Info'] = $value['admin']->username;
            $csvOrder['User IP'] = $value['admin']->trading_ip;
            $csvOrder['Last Modified Date'] = $value['modified_date_hover'];

             
           

            $csvOrdersArr[] = $csvOrder;
            // echo "<pre>"; print_r($csvOrdersArr); exit;
            unset($value, $csvOrder);
            $parentSrNum++;
            
           
        }
        //commented code for test
//         for ($i = 0; $i < $ordersArrLength; $i++) {
//             $value = &$orders[$i];

//             $csvOrder = [];

//             $csvOrder['sr_num'] = $srNum;
//             $csvOrder['order_id'] = $value['_id'];
//             $srNum++;

//             $csvOrder['Coin'] = $value['symbol'];
//             $csvOrder['Price'] = (isset($value['purchased_price']) ? num($value['purchased_price']) : num($value['price']));
//             // echo "<pre>"; print_r($csvOrder); exit;
//             //Order Type
//             if ($value['trigger_type'] != 'no') {
//                 if ($value['trigger_type'] == 'barrier_percentile_trigger') {
//                     $csvOrder['Order Type'] = 'BPT';
//                 } else {
//                     $csvOrder['Order Type'] = strtoupper(str_replace('_', ' ', $value['trigger_type']));
//                 }
//             } else {
//                 $csvOrder['Order Type'] = 'MANUAL ORDER';
//             }
//             //end Order Type
            
//             // $market_value = $this->mod_dashboard->get_market_value($value['symbol']);
//             $filter_user_data = $this->session->userdata('filter_order_data');
//             if (!empty($filter_user_data['filter_by_exchange'])) {
//                 $order_exchange = $filter_user_data['filter_by_exchange'];
//             }

//             $BTCUSDTmarket_value = $this->mod_dashboard->get_market_value2('BTCUSDT', $order_exchange);
//             $market_value = $this->mod_dashboard->get_market_value2($value['symbol'], $order_exchange);
            
//             $logo = $this->mod_coins->get_coin_logo($value['symbol']);
//             if ($value['status'] != 'new') {
//                 if ($value['status'] == 'FILLED') {
//                     $market_value333 = num($value['purchased_price']);
//                 } else {
//                     $market_value333 = num($value['market_value']);
//                 }
//             } else {
//                 $market_value333 = num($market_value);
//             }
//             if ($value['status'] == 'new') {
//                 $current_order_price = num($value['price']);
//             } else {
//                 if ($value['status'] == 'FILLED') {
//                     $market_value333 = num($value['purchased_price']);
//                 } else {
//                     $market_value333 = num($value['market_value']);
//                 }
//             }

//             if ($value['status'] == 'canceled' && (empty($value['parent_status']) || $value['parent_status'] != 'parent')) {
//                 $market_value333 = !empty($value['purchased_price']) ? num($value['purchased_price']) : num($value['price']);
//             }

//             $current_data = $market_value333 - $current_order_price;
//             $market_data = ($current_data * 100 / $market_value333);
//             $market_data = number_format((float) $market_data, 2, '.', '');

//             $csvOrder['Level'] = $value['order_level'];
//             $csvOrder['quantity'] = $value['quantity'];
//             $usd_worth_tt = get_usd_worth($value['symbol'], $value['quantity'], $market_value333, $BTCUSDTmarket_value);
//             $csvOrder['usd worth'] = "$".$usd_worth_tt;
//             $csvOrder['Created Date'] = $value['created_date_hover'];
//             $csvOrder['Market Sold Price'] = $value['market_sold_price'];
//             $csvOrder['Accumulations'] = $value['accumulations'];
//             // $csvOrder['Sell price'] = $value['sell_price']; // this sell price is different from the market sold price thats why i commented it out on jan 27 2023.
//             //P/L
//             $csvOrder['P/L'] = num($market_value333);
//             //end Market(%)

//             //Market(%)
//             $csvOrder['Market(%)'] = ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') ? $market_data : '-';
//             //end Market(%)

//             //Status
//             if ($value['is_sell_order'] == 'yes') {
//                 if ($value['status'] == 'LTH') {
//                     $csvOrder['Status'] = "LTH OPEN";
//                 } elseif (in_array($value['status'], $errArr)) {
//                     $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
//                 } elseif ($value['status'] == 'submitted_for_sell') {
//                     $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
//                 } elseif ($value['status'] == 'fraction_submitted_sell') {
//                     $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
//                 } elseif ($value['status'] == 'canceled') {
//                     $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
//                 }elseif($value['status'] == 'new'){
//                     $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
//                 }else{
//                     $csvOrder['Status'] = 'OPEN';
//                 }
//             } else {
//                 $csvOrder['Status'] = strtoupper(str_replace('_', ' ', $value['status']));
//             }
//             if ($value['parent_status'] == 'parent') {
//                 $csvOrder['Status'] = 'Parent Order';
//             }
//             //end Status

//             //Profit(%)
//             if(!empty($value['resume_order_arr_total'])){
//                 $csvOrder['Profit(%)'] = $value['resume_order_arr_total'] . '%';
//             }else{
//                 if ($value['market_sold_price'] != "") {
//                     $market_sold_price = num($value['market_sold_price']);
//                     $pp = num($value['purchased_price']);
//                     $profitPercentage = calculateProfitPercentage($pp, $market_sold_price);
//                     $csvOrder['Profit(%)'] = $profitPercentage . '%';
//                 } else {
//                     if ($value['status'] == 'FILLED') {
//                         if ($value['is_sell_order'] == 'yes') {
//                             $pp = num($value['purchased_price']);
//                             $profitPercentage = calculateProfitPercentage($pp, $market_value);
//                             $csvOrder['Profit(%)'] = $profitPercentage . '%';
//                         } else {
//                             $csvOrder['Profit(%)'] = '-';
//                         }
//                     } elseif ($value['status'] == 'LTH') {
//                         if ($value['is_sell_order'] == 'yes') {
//                             $pp = num($value['purchased_price']);
//                             $profitPercentage = calculateProfitPercentage($pp, $market_value);
//                             $csvOrder['Profit(%)'] = $profitPercentage . '%';
//                         } else {
//                             $csvOrder['Profit(%)'] = '-';
//                         }
//                     } else {
//                         $csvOrder['Profit(%)'] = '-';
//                     }
//                 }
//             }
//             //end Profit(%)

//             //Target Profit(%)
            
//             if ($value['trigger_type'] == 'no' && $value['status'] == 'LTH') {
//                 $target_profit = $value['lth_profit'];
//             }else if ($value['trigger_type'] == 'no' && $value['status'] == 'FILLED') {
//                 $target_profit = $value['sell_profit_percent'];
//             }else if ($value['trigger_type'] != 'no' && $value['status'] == 'LTH') {
//                 $target_profit = $value['lth_profit'];
//             }else if ($value['trigger_type'] != 'no' && $value['status'] == 'FILLED') {
//                 $target_profit = $value['defined_sell_percentage'];
//             }else if ($value['parent_status'] == 'parent') {
//                 $target_profit = $value['defined_sell_percentage'];
//             }
//             $csvOrder['Target Profit(%)'] = (!in_array($target_profit, $nanArr) ? $target_profit.'%' : '-');
//             //end Target Profit(%)

//             //Slippage(%)
//             if (!in_array($target_profit, $nanArr) && !in_array($profitPercentage, $nanArr) && $value['is_sell_order'] == 'sold') {
//                 if ($value['is_lth_order'] == 'yes' && !in_array($value['lth_profit'], $nanArr)) {
//                     $target_profit = $value['lth_profit'];
//                 }
//                 if ($profitPercentage >= $target_profit) {
//                     $slippage = ($profitPercentage - $target_profit);
//                     $slippage = round($slippage, 3) . '%';
//                 } else if ($profitPercentage < $target_profit) {
//                     $slippage = ($target_profit - $profitPercentage);
//                     $slippage = '-' . round($slippage, 3) . '%';
//                 } else {
//                     $slippage = '-';
//                 }
//             } else {
//                 $slippage = '-';
//             }
//             $csvOrder['Slippage(%)'] = $slippage;
//             //end Slippage(%)

//             //LTH
//             if ($value['status'] == 'LTH') {
//                 $csvOrder['LTH'] = 'LTH';
//             } else {
//                 if ($value['is_sell_order'] == 'sold' && $value['is_lth_order'] == 'yes') {
//                     $csvOrder['LTH'] = 'LTH';
//                 } else {
//                     $csvOrder['LTH'] = 'Normal';
//                 }
//             }
//             //end LTH

//             //LTH Profit (%)
//             $csvOrder['LTH Profit (%)'] = ($value['lth_profit'] != '' && !in_array(trim($value['lth_profit']), $nanArr) ? $value['lth_profit'] : '-');
//             //end LTH Profit (%)

//             //Stop Loss (%)
//             $csvOrder['Stop Loss (%)'] = ($value['loss_percentage'] != '' && !in_array(trim($value['loss_percentage']), $nanArr) ? $value['loss_percentage'].($value['iniatial_trail_stop'] > $value['purchased_price'] ? " (+)" : " (-)") : '-');
//             //end Stop Loss (%)

//             //Sub Status
//             if (in_array($value['status'], $errArr)) {
//                 $csvOrder['Sub Status'] = str_replace('_', ' ', $value['status']);
//             } else {
//                 if ($value['status'] == 'FILLED') {
//                     if ($value['is_sell_order'] == 'yes') {
//                         if (!empty($value['sell_order_id'])) {
//                             $sell_status = $this->mod_buy_orders->is_sell_order_in_error_status($value['sell_order_id']);
//                             $sell_status_submit = $this->mod_buy_orders->is_sell_order_in_submitted_status($value['sell_order_id']);
//                         } else {
//                             $sell_status = '';
//                             $sell_status_submit = '';
//                         }
//                         if ($sell_status) {
//                         } elseif ($sell_status_submit) {
//                             $csvOrder['Sub Status'] = 'SUBMITTED FOR SELL';
//                         } else {
//                             $csvOrder['Sub Status'] = 'WAITING FOR SELL';
//                         }
//                     } elseif ($value['is_sell_order'] == 'sold') {
//                         $csvOrder['Sub Status'] = ($value['is_manual_sold'] == 'yes' ? 'Sold Manually' : 'Sold');
//                     } elseif ($value['is_sell_order'] == 'pause') {
//                         $csvOrder['Sub Status'] = 'Paused';
//                     }
//                 } elseif ($value['status'] == 'LTH') {
//                     if ($sell_status) {
//                         $csvOrder['Sub Status'] = 'ERROR IN SELL ' . $sell_status . '';
//                     } elseif ($sell_status_submit) {
//                         $csvOrder['Sub Status'] = 'SUBMITTED FOR SELL';
//                     } else {
//                         $csvOrder['Sub Status'] = 'WAITING FOR SELL';
//                     }
//                 } else {
//                     $csvOrder['Sub Status'] = '-';
//                 }

//                 if ($value['resume_status'] == 'completed') {
//                     $csvOrder['Sub Status'] = ' Resume Completed ';
//                 } else if ($value['status'] == 'FILLED' && ($value['is_sell_order'] == 'yes' || $value['is_sell_order'] == 'sold') && !empty($value['resume_order_id'])) {
//                     $csvOrder['Sub Status'] = ' Resume Child ';
//                 } else if ($value['is_sell_order'] == 'resume_pause' && empty($value['resume_order_arr'])) {
//                     $csvOrder['Sub Status'] = ' Resumed ';
//                 } else if ($value['is_sell_order'] == 'resume_pause' && !empty($value['resume_order_arr'])) {
//                     $csvOrder['Sub Status'] = ' In Progress ';
//                 } else if ($value['is_sell_order'] == 'pause') {
//                     $csvOrder['Sub Status'] = ' Paused ';
//                 }    
//             }
//             //end Sub Status
            
//             if ($value['mapped_order'] == 'yes') {
//                 $csvOrder['Sub Status'] .= ' Mapped ';
//             }
//             if($value['script_fix'] != '' && $value['script_fix'] == 1){
//                 $csvOrder['Sub Status'] .= ', Script Fixed'; 
//             }else if($value['script_fix'] != '' && $value['script_fix'] == 0){
//                 $csvOrder['Sub Status'] .= ', Script Ignored'; 
//             }
            
//             if($value['quantity_issue'] != '' && $value['quantity_issue'] == 0){
//                 $csvOrder['Sub Status'] .= ', Quantity Issue'; 
//             }
//             if($value['pick_parent'] == 'yes'){
//                 $csvOrder['Sub Status'] .= ', Pick parent (Yes)'; 
//             }
//             if($value['pick_parent'] == 'no'){
//                 $csvOrder['Sub Status'] .= ', Pick parent (No)'; 
//             }
//             if (!empty($value['cancel_hour'])) {
//                 $csvOrder['Sub Status'] .= ', Deep price';
//             }
//             if ($value['cavg_parent'] == 'yes') {
//                 $csvOrder['Sub Status'] .= ', Cost Avg Parent';
//             }

//             // Max(%)/Min(%)
//             if (isset($value['market_heighest_value']) && $value['market_heighest_value'] != '') {
//                 $five_hour_max_market_price = $value['market_heighest_value'];
//                 $purchased_price = (float) $value['purchased_price'];
//                 $profit = $five_hour_max_market_price - $purchased_price;
//                 $profit_margin = ($profit / $five_hour_max_market_price) * 100;
//                 $profit_per = ($profit) * (100 / $purchased_price);
//                 $t_max = number_format($profit_per, 2) . '%';
//             } else {
//                 $t_max = "-";
//             }
//             if (isset($value['market_lowest_value']) && $value['market_lowest_value'] != '') {
//                 $market_lowest_value = $value['market_lowest_value'];
//                 $purchased_price = (float) $value['purchased_price'];
//                 $profit = $market_lowest_value - $purchased_price;
//                 $profit_margin = ($profit / $market_lowest_value) * 100;
//                 $profit_per = ($profit) * (100 / $purchased_price);
//                 $t_min = number_format($profit_per, 2) . '%';
//             } else {
//                 $t_min = "-";
//             }
//             $csvOrder['Max(%)/Min(%)'] = $t_max . " | " . $t_min;
//             // end Max(%)/Min(%)

//             //5hMax(%)/5hMin(%)
//             if (isset($value['5_hour_max_market_price']) && $value['5_hour_max_market_price'] != '') {
//                 $five_hour_max_market_price = $value['5_hour_max_market_price'];
//                 $market_sold_price = (float) $value['market_sold_price'];
//                 $profit = $five_hour_max_market_price - $market_sold_price;
//                 $profit_margin = ($profit / $five_hour_max_market_price) * 100;
//                 $profit_per = ($profit) * (100 / $market_sold_price);
//                 $max_5h = number_format($profit_per, 2) . '%';
//             } else {
//                 $max_5h = "-";
//             }
//             if (isset($value['5_hour_min_market_price']) && $value['5_hour_min_market_price'] != '') {
//                 $market_lowest_value = $value['5_hour_min_market_price'];
//                 $market_sold_price = (float) $value['market_sold_price'];
//                 $profit = $market_lowest_value - $market_sold_price;
//                 $profit_margin = ($profit / $market_lowest_value) * 100;
//                 $profit_per = ($profit) * (100 / $market_sold_price);
//                 $min_5h = number_format($profit_per, 2) . '%';
//             } else {
//                 $min_5h = "-";
//             }
//             $csvOrder['5hMax(%)/5hMin(%)'] = $max_5h . " | " . $min_5h;
//             // end 5hMax(%)/5hMin(%)

//             $csvOrder['User Info'] = $value['admin']->username;
//             $csvOrder['User IP'] = $value['admin']->trading_ip;
//             $csvOrder['Last Modified Date'] = $value['modified_date_hover'];

//             $csvOrdersArr[] = $csvOrder;
// // echo "<pre>"; print_r($csvOrdersArr); exit;
//             unset($value, $csvOrder);
            
//         }
        sort($csvOrdersArr);
        $this->generate_csv($csvOrdersArr);
    }

    public function generate_csv($orderArr)
    {
        if ($orderArr) {
            $filename = ("Orders Report " . date("Y-m-d Gis") . ".csv");
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
            echo array2csv($orderArr);
            exit;
        }
    }

    // public function test()
    // {

    //     $this->mongo_db->limit(1);
    //     // $this->mongo_db->sort(array('_id' => 'desc'));
    //     // $this->mongo_db->where(array('_id' => $this->mongo_db->mongoId('5e5171ea8f0d7b001a43013c')));
    //     $this->mongo_db->where(array('_id' => '5e5171ea8f0d7b001a43013c'));
    //     $responseArr = $this->mongo_db->get('sold_buy_orders');
    //     $responseArr = iterator_to_array($responseArr);
    //     echo "<pre>";
    //     print_r($responseArr);

    //     // echo "modified_date  ".$responseArr[0]['modified_date'];
    //     // echo "\r\n";
    //     // echo $datetime111 = new DateTime((string)$responseArr[0]['modified_date']);
    //     // echo "\r\n";
    //     // echo "------------------------------------------------------------- \r\n";

    //     // $datetime111->format('Y-m-d g:i:s A');

    //     // $user_id = $this->session->userdata('admin_id');
    //     // $timezone = get_user_timezone($user_id);

    //     // echo " new_timezone  ".$new_timezone = new DateTimeZone($timezone);
    //     // $datetime111->setTimezone($new_timezone);

    //     // echo "\r\n       formated_date_time1  ";
    //     // echo $formated_date_time1 = $datetime111->format('Y-m-d g:i:s A');
    //     // echo "\r\n";
    //     // echo $time_elapsed_string = time_elapsed_string($formated_date_time1, $timezone, false);

    //     echo $responseArr[0]['modified_date']->toDateTime()->format("Y-m-d H:i:s") . "\r\n";

    //     echo $formated_date_time1 = $responseArr[0]['modified_date']->toDateTime()->format('Y-m-d g:i:s A');
    //     echo time_elapsed_string($formated_date_time1) . "\r\n";

    //     echo $responseArr[0]['modified_date']->toDateTime()->format("Y-m-d H:i:s") . "\r\n";
    // }

    public function opportunity_order_report($opp_id, $exchange,$application_mode = '',$cavg_button = ''){
        //cost_average_orders_opportunity
        if(empty($opp_id)){
            $this->reset_filters_report('all');
        }
        //Reset previous filter
        $this->session->unset_userdata('filter_order_data');
        if($exchange == '' || empty($exchange)){
         $exchange = 'kraken'; 
        }
        //Set opportunity filter
        $filter_arr = [
                'filter_order_data' => [
                // 'filter_by_coin' => '', 
                'filter_by_mode' => 'live', 
                // 'filter_by_start_date' => '', 
                // 'filter_by_end_date' => '', 
                // 'filter_by_start_date_m' => '', 
                // 'filter_by_end_date_m' => '', 
                // 'filter_by_trigger' => '', 
                // 'filter_by_rule' => '', 
                // 'filter_by_nature' => '', 
                'filter_by_exchange' => $exchange,
                // 'filter_username' => '',
                'opportunityId' => $opp_id, 
                'optradio' => 'created_date',
                'selector' => 'DESC',
            ]
        ];
         if((string)$cavg_button == 'total'){
            //echo '<pre>hello';
            $filter_arr['filter_order_data']['filter_by_status'] = ['cost_average_orders_opportunity'];
        }else if($cavg_button == 'canceled'){
            $filter_arr['filter_order_data']['filter_by_status'] = ['canceled_cost_average_orders_opportunity'];
        }
        $this->session->set_userdata($filter_arr);
        redirect(base_url() . 'admin/order_report');

    }//end opportunity_order_report


    public function buy_limit_order_report($user_id, $exchange, $application_mode){

        $username = $this->get_username_from_user_2($user_id);

        //Reset previous filter
        $this->session->unset_userdata('filter_order_data');
        //Set opportunity filter
        $filter_arr = [
            'filter_order_data' => [
                // 'filter_by_coin' => '', 
                'filter_by_mode' => $application_mode, 
                // 'filter_by_start_date' => '', 
                // 'filter_by_end_date' => '', 
                // 'filter_by_start_date_m' => '', 
                // 'filter_by_end_date_m' => '', 
                // 'filter_by_trigger' => '', 
                // 'filter_by_rule' => '', 
                // 'filter_by_nature' => '', 
                'filter_by_exchange' => $exchange,
                'filter_username' => $username, 
                // 'opportunityId' => $opp_id, 
                'optradio' => 'created_date',
                'selector' => 'DESC',
            ]
        ];

        $this->session->set_userdata($filter_arr);
        redirect(base_url() . 'admin/order_report');

    }//end opportunity_order_report
    public function merge_old_cavg_to_new(){

        $order_id = $this->input->post('order_id');
        $exchange = $this->input->post('exchange');
        // avg_orders_ids
        //avg_orders_ids
        if($exchange == 'binance'){
            $collection_order = 'buy_orders';
            $collection_sold = 'sold_buy_orders'; 
        }else{
            $collection_order = 'buy_orders_kraken';
            $collection_sold = 'sold_buy_orders_kraken';
        }
        $connect = $this->mongo_db->customQuery();
        $search['_id'] = $this->mongo_db->mongoId((string)$order_id);
        $order_detail = $connect->$collection_order->find($search);
        $order_details = iterator_to_array($order_detail);
        $cost_avg_childs_array = array();
        if(count($order_details) > 0){
            if($order_details[0]['is_sell_order'] != 'sold'){
                $fraction_array = iterator_to_array($order_details[0]['buy_fraction_filled_order_arr']);
                if(array_key_exists(0,$fraction_array)){
                    $parent_first_child_array = [
                        'order_sold'=>'no',
                        'buy_order_id'=>$this->mongo_db->mongoId((string)$order_id),
                        'filledQtyBuy'=>$order_details[0]['buy_fraction_filled_order_arr'][0]['filledQty'],
                        'filledPriceBuy'=>$order_details[0]['buy_fraction_filled_order_arr'][0]['filledPrice'],
                        'commissionBuy'=>$order_details[0]['buy_fraction_filled_order_arr'][0]['commission'],
                        'orderFilledIdBuy'=>$order_details[0]['buy_fraction_filled_order_arr'][0]['orderFilledId'],
                        'buyTimeDate'=>$order_details[0]['buy_date']
                    ];    
                }else{
                    $parent_first_child_array = [
                        'order_sold'=>'no',
                        'buy_order_id'=>$this->mongo_db->mongoId((string)$order_id),
                        'filledQtyBuy'=>$order_details[0]['buy_fraction_filled_order_arr']['filledQty'],
                        'filledPriceBuy'=>$order_details[0]['buy_fraction_filled_order_arr']['filledPrice'],
                        'commissionBuy'=>$order_details[0]['buy_fraction_filled_order_arr']['commission'],
                        'orderFilledIdBuy'=>$order_details[0]['buy_fraction_filled_order_arr']['orderFilledId'],
                        'buyTimeDate'=>$order_details[0]['buy_date']
                    ];
                }
                array_push($cost_avg_childs_array,$parent_first_child_array);   
            }
            if(isset($order_details[0]['avg_orders_ids'])){
                $raw_array_child_ids = isset($order_details[0]['avg_orders_ids'])?$order_details[0]['avg_orders_ids']:array();
                $array_child_ids = iterator_to_array($raw_array_child_ids);
                foreach ($array_child_ids as $value) {
                    // code...
                    $pipe_line = [['$match'=>['_id'=>$this->mongo_db->mongoId((string)$value),'status'=>['$ne'=>'canceled']]]];
                    $get_child_data = $connect->$collection_order->aggregate($pipe_line);
                    $get_child_array = iterator_to_array($get_child_data);
                    if(count($get_child_array) > 0){
                        $parent_first_child_array = [
                            'order_sold'=>'no',
                            'buy_order_id'=>$this->mongo_db->mongoId((string)$value),
                            'filledQtyBuy'=>$get_child_array[0]['buy_fraction_filled_order_arr'][0]['filledQty'],
                            'filledPriceBuy'=>$get_child_array[0]['buy_fraction_filled_order_arr'][0]['filledPrice'],
                            'commissionBuy'=>$get_child_array[0]['buy_fraction_filled_order_arr'][0]['commission'],
                            'orderFilledIdBuy'=>$get_child_array[0]['buy_fraction_filled_order_arr'][0]['orderFilledId'],
                            'buyTimeDate'=>$get_child_array[0]['buy_date']
                        ];
                        array_push($cost_avg_childs_array,$parent_first_child_array);      
                    }else{
                        $pipe_line1 = [['$match'=>['_id'=>$this->mongo_db->mongoId((string)$value),'status'=>['$ne'=>'canceled'],'is_sell_order'=>['$eq'=>'sold']]]];
                        $get_child_data_sold = $connect->$collection_sold->aggregate($pipe_line1);
                        $get_child_array_sold = iterator_to_array($get_child_data_sold);
                        if(count($get_child_array_sold) > 0){
                        $parent_first_child_array = [
                            'order_sold'=>'yes',
                            'buy_order_id'=>$this->mongo_db->mongoId((string)$value),
                            'filledQtyBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['filledQty'],
                            'filledQtySell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['filledQty'],
                            'filledPriceBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['filledPrice'],
                            'filledPriceSell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['filledPrice'],
                            'commissionBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['commission'],
                            'commissionSell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['commission'],
                            'orderFilledIdBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['orderFilledId'],
                            'orderFilledIdSell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['orderFilledId'],
                            'sellOrderId'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['sellOrderId'],
                            'sellTimeDate'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['sellTimeDate'],
                            'buyTimeDate'=>$get_child_array_sold[0]['buy_date']
                        ];
                        array_push($cost_avg_childs_array,$parent_first_child_array);      
                        }
                    }
                }
            }
            $date = date('Y-m-d H:i:s');
            $update_array = ['move_to_cost_avg'=>'yes',
                'cost_avg_array'=>$cost_avg_childs_array,
                'modified_date'=>$this->mongo_db->converToMongodttime($date)
            ];
            $connect->$collection_order->updateOne($search,['$set'=>$update_array]);
            $resp['success']=true;
            $resp['data']=$order_id;
            $resp['message']='Order successfuly merged with new cost avg';
            $resp['exchange']=$exchange;
            echo json_encode($resp);exit;
            
        }else{
            //echo 'i am in else';
            $search['_id'] = $this->mongo_db->mongoId((string)$order_id);
            $order_detail_check = $connect->$collection_sold->find($search);
            $order_details_sold = iterator_to_array($order_detail_check);
            $cost_avg_childs_array = array();
            //echo '<pre>';print_r($order_details_sold);exit;
            if($order_details_sold[0]['is_sell_order'] == 'sold'){
                $fraction_array = iterator_to_array($order_details_sold[0]['sell_fraction_filled_order_arr']);
                if(array_key_exists(0,$fraction_array)){
                    $parent_first_child_array = [
                        'order_sold'=>'yes',
                        'buy_order_id'=>$this->mongo_db->mongoId((string)$order_id),
                        'filledQtyBuy'=>$order_details_sold[0]['buy_fraction_filled_order_arr'][0]['filledQty'],
                        'filledQtySell'=>$order_details_sold[0]['sell_fraction_filled_order_arr'][0]['filledQty'],
                        'filledPriceBuy'=>$order_details_sold[0]['buy_fraction_filled_order_arr'][0]['filledPrice'],
                        'filledPriceSell'=>$order_details_sold[0]['sell_fraction_filled_order_arr'][0]['filledPrice'],
                        'commissionBuy'=>$order_details_sold[0]['buy_fraction_filled_order_arr'][0]['commission'],
                        'commissionSell'=>$order_details_sold[0]['sell_fraction_filled_order_arr'][0]['commission'],
                        'orderFilledIdBuy'=>$order_details_sold[0]['buy_fraction_filled_order_arr'][0]['orderFilledId'],
                        'orderFilledIdSell'=>$order_details_sold[0]['sell_fraction_filled_order_arr'][0]['orderFilledId'],
                        'sellOrderId'=>$order_details_sold[0]['sell_order_id'],
                        'sellTimeDate'=>$order_details_sold[0]['sell_date'],
                        'buyTimeDate'=>$order_details_sold[0]['buy_date']
                    ];    
                }else{
                    $parent_first_child_array = [
                        'order_sold'=>'yes',
                        'buy_order_id'=>$this->mongo_db->mongoId((string)$order_id),
                        'filledQtyBuy'=>$order_details_sold[0]['buy_fraction_filled_order_arr'][0]['filledQty'],
                        'filledQtySell'=>$order_details_sold[0]['sell_fraction_filled_order_arr'][0]['filledQty'],
                        'filledPriceBuy'=>$order_details_sold[0]['buy_fraction_filled_order_arr'][0]['filledPrice'],
                        'filledPriceSell'=>$order_details_sold[0]['sell_fraction_filled_order_arr'][0]['filledPrice'],
                        'commissionBuy'=>$order_details_sold[0]['buy_fraction_filled_order_arr'][0]['commission'],
                        'commissionSell'=>$order_details_sold[0]['sell_fraction_filled_order_arr'][0]['commission'],
                        'orderFilledIdBuy'=>$order_details_sold[0]['buy_fraction_filled_order_arr'][0]['orderFilledId'],
                        'orderFilledIdSell'=>$order_details_sold[0]['sell_fraction_filled_order_arr'][0]['orderFilledId'],
                        'sellOrderId'=>$order_details_sold[0]['sell_order_id'],
                        'sellTimeDate'=>$order_details_sold[0]['sell_date'],
                        'buyTimeDate'=>$order_details_sold[0]['buy_date']
                    ];
                }
                array_push($cost_avg_childs_array,$parent_first_child_array);   
            }
            if(isset($order_details_sold[0]['avg_orders_ids'])){
                $raw_array_child_ids = isset($order_details_sold[0]['avg_orders_ids'])?$order_details_sold[0]['avg_orders_ids']:array();
                $array_child_ids = iterator_to_array($raw_array_child_ids);
                foreach ($array_child_ids as $value) {
                    // code...
                    $pipe_line = [['$match'=>['_id'=>$this->mongo_db->mongoId((string)$value),'status'=>['$ne'=>'canceled']]]];
                    $get_child_data = $connect->$collection_order->aggregate($pipe_line);
                    $get_child_array = iterator_to_array($get_child_data);
                    if(count($get_child_array) > 0){
                        $parent_first_child_array = [
                            'order_sold'=>'no',
                            'buy_order_id'=>$this->mongo_db->mongoId((string)$value),
                            'filledQtyBuy'=>$get_child_array[0]['buy_fraction_filled_order_arr'][0]['filledQty'],
                            'filledPriceBuy'=>$get_child_array[0]['buy_fraction_filled_order_arr'][0]['filledPrice'],
                            'commissionBuy'=>$get_child_array[0]['buy_fraction_filled_order_arr'][0]['commission'],
                            'orderFilledIdBuy'=>$get_child_array[0]['buy_fraction_filled_order_arr'][0]['orderFilledId'],
                            'buyTimeDate'=>$get_child_array[0]['buy_date']
                        ];
                        array_push($cost_avg_childs_array,$parent_first_child_array);      
                    }else{
                        $pipe_line1 = [['$match'=>['_id'=>$this->mongo_db->mongoId((string)$value),'status'=>['$ne'=>'canceled'],'is_sell_order'=>['$eq'=>'sold']]]];
                        $get_child_data_sold = $connect->$collection_sold->aggregate($pipe_line1);
                        $get_child_array_sold = iterator_to_array($get_child_data_sold);
                        if(count($get_child_array_sold) > 0){
                        $parent_first_child_array = [
                            'order_sold'=>'yes',
                            'buy_order_id'=>$this->mongo_db->mongoId((string)$value),
                            'filledQtyBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['filledQty'],
                            'filledQtySell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['filledQty'],
                            'filledPriceBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['filledPrice'],
                            'filledPriceSell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['filledPrice'],
                            'commissionBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['commission'],
                            'commissionSell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['commission'],
                            'orderFilledIdBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['orderFilledId'],
                            'orderFilledIdSell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['orderFilledId'],
                            'sellOrderId'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['sellOrderId'],
                            'sellTimeDate'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['sellTimeDate'],
                            'buyTimeDate'=>$get_child_array_sold[0]['buy_date']
                        ];
                        array_push($cost_avg_childs_array,$parent_first_child_array);      
                        }
                    }
                }
            }
            $date = date('Y-m-d H:i:s');
            $order_details_sold[0]['cost_avg_array']  = $cost_avg_childs_array;
            $order_details_sold[0]['modified_date']  = $this->mongo_db->converToMongodttime($date);
            $connect->$collection_order->updateOne($search,['$set'=>$order_details_sold[0]],['upsert'=>true]);
            $resp['success']=true;
            $resp['data']=$order_id;
            $resp['message']='Order successfuly merged with new cost avg';
            $resp['exchange']=$exchange;
            echo json_encode($resp);exit;
        }  
    }

    public function get_new_active_users($exchange = ''){
        if($exchange == '' || $exchange == 'binance'){
            $collection = 'orders';
        }else{
            $collection = 'orders_kraken';
        }
        $dateTimeFrom = $this->mongo_db->converToMongodttime(date('Y-m-d 24:00', strtotime('-90 days')));
        $pipeline = [
            [
                '$match'=> [
                    'status'=>['$ne'=>'canceled'],
                    'admin_id'=>['$exists'=>true],
                    'created_date'=>['$gt'=>$dateTimeFrom],
                    'trading_ip'=>"54.157.102.20"
                ]
            ], 
            [
                '$group'=>[
                    '_id'=>'$admin_id',
                ]
            ]
        ];
        $db = $this->mongo_db->customQuery();
        $user_ids = $db->$collection->aggregate($pipeline);
        $array = iterator_to_array($user_ids);
        foreach($array as $value){
            echo '<pre>';print_r($value['_id']);    
        }
        
    }
    public function upd_avg_price_new(){
        $order_id = $this->input->post('avg_sell_price_id');
        $avg_price = $this->input->post('avg_sell_price_new');
        $exchange = $this->input->post('exchange_avg_upd');
        if($exchange == 'binance' || $exchange == ''){
            $collection_name = 'buy_orders';
        }else{
            $collection_name = 'buy_orders_kraken';
        }
        //echo '<pre>';print_r($this->input->post());exit;
        $db = $this->mongo_db->customQuery();
        $update_order = $db->$collection_name->updateOne(['_id'=>$this->mongo_db->mongoId($order_id)],['$set'=>['avg_sell_price'=>(float)$avg_price]]);
        $resp['success'] = true;
        echo json_encode($resp);exit;
        //$this->index();
    }
    public function move_from_sold_to_buy(){
        $order_id = $this->input->post('order_id');
        $exchange = $this->input->post('exchange');
        // avg_orders_ids
        //avg_orders_ids
        if($exchange == 'binance'){
            $collection_order = 'buy_orders';
            $collection_sold = 'sold_buy_orders'; 
        }else{
            $collection_order = 'buy_orders_kraken';
            $collection_sold = 'sold_buy_orders_kraken';
        }
        $connect = $this->mongo_db->customQuery();
        $search['_id'] = $this->mongo_db->mongoId((string)$order_id);
        $order_detail = $connect->$collection_sold->find($search);
        $order_details = iterator_to_array($order_detail);
        $order_array = $order_details[0];
        if(isset($order_array['cavg_parent']) && !empty($order_array['cavg_parent'])){
            $order_array['all_buy_ids'] = "";
            $order_array['avg_price_all_upd'] = "" ;
            $order_array['avg_price_one_upd'] = "";
            $order_array['avg_price_three_upd'] = "";
            $order_array['cost_avg']   =  "yes";
            $order_array['cost_avg_buy'] = '';
            $order_array['move_to_cost_avg'] =  "yes";
            $order_array['new_child_buy_price'] = (float) 100000000 ;
            $order_array['avg_sell_price'] = (float) 100000000 ;
            $order_array['new_child_price_upd'] =  "no";
            $order_array['quantity_all'] = "";
            $order_array['quantity_one'] = "";
            $order_array['quantity_three'] = "";
            $order_array['is_sell_order'] =  "yes";
            $order_array['trading_status'] =  '';
        }else if(!isset($order_array['cavg_parent']) && ($order_array['cost_avg'] == 'yes' || $order_array['cost_avg'] == 'completed')){
            $order_array['cost_avg']   =  "yes";
            $order_array['move_to_cost_avg'] =  "yes";
            $order_array['avg_sell_price'] = (float) 100000000 ;
            $order_array['is_sell_order'] =  "yes";
            $order_array['trading_status'] =  '';
        }else{
            $order_array['avg_sell_price'] = (float) 100000000 ;
            $order_array['is_sell_order'] =  "yes";
            $order_array['trading_status'] =  '';
        }
        $connect->$collection_order->updateOne($search,['$set'=>$order_array],['upsert'=>true]);
        $connect->$collection_sold->deleteOne($search);
        $resp['success'] = true;
        echo json_encode($resp);exit;
        //echo '<pre>';print_r($order_array);exit;
    }
    public function move_order_to_buy_quantity(){
        $order_id = $this->input->post('order_id');
        $exchange = $this->input->post('exchange');
        // avg_orders_ids
        //avg_orders_ids
        if($exchange == 'binance'){
            $collection_order = 'buy_orders';
            $collection_sold = 'sold_buy_orders'; 
        }else{
            $collection_order = 'buy_orders_kraken';
            $collection_sold = 'sold_buy_orders_kraken';
        }
        $connect = $this->mongo_db->customQuery();
        $search['_id'] = $this->mongo_db->mongoId((string)$order_id);
        $order_detail = $connect->$collection_order->find($search);
        $order_details = iterator_to_array($order_detail);
        if(count($order_details) > 0){
            $order_array_upd['avg_sell_price'] = (float) 100000000;
            $order_array_upd['avg_sell_price_one'] =(float) 100000000;
            $order_array_upd['avg_sell_price_two'] =(float) 100000000;
            $order_array_upd['avg_sell_price_three'] =(float) 100000000 ;
            $order_array_upd['status']   =  "FILLED";
            $order_array_upd['new_child_price_upd'] =  "yes";
            $order_array_upd['sell_price'] = (float) 100000000;
            $order_array_upd['new_child_buy_price'] = (float) 100000000;
            
            $order_array_upd['avg_price_all_upd'] = "yes";
            $order_array_upd['avg_price_one_upd'] = "yes";
            $order_array_upd['avg_price_two_upd'] = "yes";
            $order_array_upd['avg_price_three_upd'] = "yes";
            $connect->$collection_order->updateOne($search,['$set'=>$order_array_upd]);
            $resp['success'] = true;
            echo json_encode($resp);exit;    
        }
        
        //echo '<pre>';print_r($order_array);exit;
    }
  public function check_buy_orders_in_sold_orders($exchange = '',$collection = ''){
        $this->load->helper('new_common_helper');
        if($exchange == '' || $exchange == 'binance'){
                $collection_name = 'sold_buy_orders';
                $collection_name_buy = 'buy_orders';
        }else{
                $collection_name = 'sold_buy_orders_kraken';
                $collection_name_buy = 'buy_orders_kraken';            
        }
        $db = $this->mongo_db->customQuery();
        $pipeline = [
            [
                '$match'=>['parent_status'=>['$ne'=>'parent'],'cavg_parent'=>'yes','cost_avg_array'=>['$exists'=>true]]
            ],
            // [
            //     '$limit'=>1000
            // ]
        ];
        $get_cost_avg_orders = $db->$collection_name->aggregate($pipeline);
        $array_orders = iterator_to_array($get_cost_avg_orders);
        $order_ids_double = array();
        $count = 0;
        $array_orders_ids = array();
        foreach ($array_orders as $value_array) {
            if(count($value_array['cost_avg_array']) > 1){
                foreach ($value_array['cost_avg_array'] as $key => $ca_value) {
                    if($ca_value['order_sold'] == 'yes'){
                            $check = $db->$collection_name_buy->count(['_id'=>$ca_value['buy_order_id']]);
                            if($check > 0){
                                echo 'Order Index : '.$key.'<pre>';
                                //echo 'Order next Quantity : '.$value_array['cost_avg_array'][$key + 1]['filledQtyBuy'].'<pre>';
                                echo 'Order Id : '.$value_array['_id'].'<pre>';
                                echo 'Child Order Id : '.(string)$ca_value['buy_order_id'].'<pre>';
                                echo 'User Id : '.$value_array['admin_id'].'<pre>';
                                echo 'Coin symbol : '.$value_array['symbol'].'<pre>';
                                echo '-----------------------------<pre>';
                                    $db->$collection_name_buy->updateOne(['_id'=>$ca_value['buy_order_id']],['$set'=>['trading_status'=>'complete','status'=>'FILLED','is_sell_order'=>'sold','cost_avg'=>'completed']]);
                             continue;    
                            }
                        //array_push($array_orders_ids,$value_array['_id']);
                    }
                }
            }
        }
    }
    public function check_sold_orders_in_buy_orders($exchange = '',$collection = ''){
        $this->load->helper('new_common_helper');
        if($exchange == '' || $exchange == 'binance'){
                $collection_name = 'sold_buy_orders';
                $collection_name_buy = 'buy_orders';
        }else{
                $collection_name = 'sold_buy_orders_kraken';
                $collection_name_buy = 'buy_orders_kraken';            
        }
        $db = $this->mongo_db->customQuery();
        $pipeline = [
            [
                '$match'=>['parent_status'=>['$ne'=>'parent'],'cavg_parent'=>'yes','cost_avg_array'=>['$exists'=>true]]
            ],
            // [
            //     '$limit'=>1000
            // ]
        ];
        $get_cost_avg_orders = $db->$collection_name_buy->aggregate($pipeline);
        $array_orders = iterator_to_array($get_cost_avg_orders);
        $order_ids_double = array();
        $count = 0;
        $array_orders_ids = array();
        foreach ($array_orders as $value_array) {
            if(count($value_array['cost_avg_array']) > 1){
                foreach ($value_array['cost_avg_array'] as $key => $ca_value) {
                    if($ca_value['order_sold'] == 'yes'){
                            $check = $db->$collection_name_buy->count(['_id'=>$ca_value['buy_order_id']]);
                            if($check > 0){
                                echo 'Order Index : '.$key.'<pre>';
                                //echo 'Order next Quantity : '.$value_array['cost_avg_array'][$key + 1]['filledQtyBuy'].'<pre>';
                                echo 'Order Id : '.$value_array['_id'].'<pre>';
                                echo 'Child Order Id : '.(string)$ca_value['buy_order_id'].'<pre>';
                                echo 'User Id : '.$value_array['admin_id'].'<pre>';
                                echo 'Coin symbol : '.$value_array['symbol'].'<pre>';
                                echo '-----------------------------<pre>';
                                if($exchange == 'kraken' && (int)$key != 0){
                                    $db->$collection_name_buy->updateOne(['_id'=>$ca_value['buy_order_id']],['$set'=>['trading_status'=>'complete','status'=>'FILLED','is_sell_order'=>'sold','cost_avg'=>'completed']]);    
                                }
                             continue;    
                            }
                            
                        //array_push($array_orders_ids,$value_array['_id']);
                    }
                }
            }
        }
    } // end of function 
    public function move_order_to_cavg_to_new(){
        $order_id = $this->input->post('order_id');
        $symbol = $this->input->post('symbol');
        $admin_id = $this->input->post('admin_id');
        $exchange = $this->input->post('exchange');
        // avg_orders_ids
        //avg_orders_ids
        if($exchange == 'binance'){
            $collection_order = 'buy_orders';
            $collection_sold = 'sold_buy_orders'; 
        }else{
            $collection_order = 'buy_orders_kraken';
            $collection_sold = 'sold_buy_orders_kraken';
        }
        $connect = $this->mongo_db->customQuery();
        //$search['_id'] = $this->mongo_db->mongoId((string)$order_id);
        $search['admin_id'] = $admin_id;
        $search['symbol'] = $symbol;
        $search['cavg_parent'] = 'yes';
        $order_detail_checking = $connect->$collection_order->find($search);
        $order_details_array = iterator_to_array($order_detail_checking);
        if(count($order_details_array) > 0){
            $direct_parent_child_id = (string)$order_details_array[0]['_id']; // ledger id as the direct parent child entry
            $costAvg_array = isset($order_details_array[0]['cost_avg_array'])?(array)$order_details_array[0]['cost_avg_array']:array();
            $pipe_line1 = [['$match'=>['_id'=>$this->mongo_db->mongoId((string)$order_id),'status'=>['$ne'=>'canceled'],'is_sell_order'=>['$eq'=>'sold']]]];
            $get_child_data_sold = $connect->$collection_sold->aggregate($pipe_line1);
            $get_child_array_sold = iterator_to_array($get_child_data_sold);
            // echo '<pre>';print_r($get)
            if(count($get_child_array_sold) > 0){
                $parent_first_child_array = [
                    'order_sold'=>'yes',
                    'buy_order_id'=>$this->mongo_db->mongoId((string)$order_id),
                    'filledQtyBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['filledQty'],
                    'filledQtySell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['filledQty'],
                    'filledPriceBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['filledPrice'],
                    'filledPriceSell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['filledPrice'],
                    'commissionBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['commission'],
                    'commissionSell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['commission'],
                    'orderFilledIdBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['orderFilledId'],
                    'orderFilledIdSell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['orderFilledId'],
                    'sellOrderId'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['sellOrderId'],
                    'sellTimeDate'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['sellTimeDate'],
                    'buyTimeDate'=>$get_child_array_sold[0]['buy_date']
                ];
                array_push($costAvg_array,new MongoDB\Model\BSONDocument($parent_first_child_array));
            }
            $date = date('Y-m-d H:i:s');
            // updating the parent order in buy collection
            $update_array = [
                'move_to_cost_avg'=>'yes',
                'cost_avg_array'=>$costAvg_array,
                'modified_date'=>$this->mongo_db->converToMongodttime($date)
            ];
            //echo '<pre>';print_r($update_array);
            $search2['admin_id'] = $admin_id;
            $search2['_id'] = $this->mongo_db->mongoId((string)$direct_parent_child_id);
            $search2['symbol'] = $symbol;
            $search2['cavg_parent'] = 'yes';
            //echo '<pre>';print_r($search2);exit;
            $connect->$collection_order->updateOne($search2,['$set'=>$update_array]);
            // updating the sold order in sold collection
            $search3['_id'] = $this->mongo_db->mongoId((string)$order_id);
            $search3['admin_id'] = $admin_id;
            $search3['symbol'] = $symbol;
            $update_array2 = [
                'move_to_cost_avg'=>'yes',
                'cost_avg'=>'completed',
                'ist_parent_child_buy_id'=>$this->mongo_db->mongoId((string)$direct_parent_child_id),
                'direct_parent_child_id'=>$this->mongo_db->mongoId((string)$direct_parent_child_id),
                'modified_date'=>$this->mongo_db->converToMongodttime($date),
                'moved_to_cost_avg_loss_sell'=>'yes'
            ];
            $connect->$collection_sold->updateOne($search3,['$set'=>$update_array2]);
            $resp['success']=true;
            $resp['data']=$order_id;
            $resp['message']='Order successfuly moved to cost avg';
            $resp['exchange']=$exchange;
            echo json_encode($resp);exit;
        }else{
            $pipe_line1 = [['$match'=>['_id'=>$this->mongo_db->mongoId((string)$order_id),'status'=>['$ne'=>'canceled'],'is_sell_order'=>['$eq'=>'sold']]]];
            $get_child_data_sold = $connect->$collection_sold->aggregate($pipe_line1);
            $get_child_array_sold = iterator_to_array($get_child_data_sold);
            $direct_parent_child_id = (string)$get_child_array_sold[0]['_id']; // ledger id as the direct parent child entry
            $costAvg_array = isset($get_child_array_sold[0]['cost_avg_array'])?(array)$get_child_array_sold[0]['cost_avg_array']:array();
            // echo '<pre>';print_r($get)
            $update_array = $get_child_array_sold[0];
            if(count($get_child_array_sold) > 0){
                $parent_first_child_array = [
                    'order_sold'=>'yes',
                    'buy_order_id'=>$this->mongo_db->mongoId((string)$order_id),
                    'filledQtyBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['filledQty'],
                    'filledQtySell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['filledQty'],
                    'filledPriceBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['filledPrice'],
                    'filledPriceSell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['filledPrice'],
                    'commissionBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['commission'],
                    'commissionSell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['commission'],
                    'orderFilledIdBuy'=>$get_child_array_sold[0]['buy_fraction_filled_order_arr'][0]['orderFilledId'],
                    'orderFilledIdSell'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['orderFilledId'],
                    'sellOrderId'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['sellOrderId'],
                    'sellTimeDate'=>$get_child_array_sold[0]['sell_fraction_filled_order_arr'][0]['sellTimeDate'],
                    'buyTimeDate'=>$get_child_array_sold[0]['buy_date']
                ];
                array_push($costAvg_array,new MongoDB\Model\BSONDocument($parent_first_child_array));
            }
            $date = date('Y-m-d H:i:s');
            // updating the parent order in buy collection
            $update_array['move_to_cost_avg'] = 'yes';
            $update_array['cavg_parent'] = 'yes';
            $update_array['cost_avg_array'] = $costAvg_array;
            $update_array['modified_date'] = $this->mongo_db->converToMongodttime($date);
            $update_array['pick_parent'] = 'yes';
            $update_array['avg_sell_price'] = (float) 100000000;
            $update_array['avg_sell_price_one'] =(float) 100000000;
            $update_array['avg_sell_price_two'] =(float) 100000000;
            $update_array['avg_sell_price_three'] =(float) 100000000 ;
            $update_array['status']   =  "FILLED";
            $update_array['new_child_price_upd'] =  "yes";
            $update_array['sell_price'] = (float) 100000000;
            $update_array['new_child_buy_price'] = (float) 100000000;
            $update_array['avg_price_all_upd'] = "yes";
            $update_array['avg_price_one_upd'] = "yes";
            $update_array['avg_price_two_upd'] = "yes";
            $update_array['avg_price_three_upd'] = "yes";
            $update_array['is_sell_order'] = "yes";
            $update_array['trading_status'] = "";
            $update_array['show_order'] = "yes";
            $update_array['cost_avg'] = "taking_child";
            unset($update_array['sell_fraction_filled_order_arr']);
            unset($update_array['sell_date']);
            //echo '<pre>';print_r($update_array);
            $search2['admin_id'] = $admin_id;
            $search2['_id'] = $this->mongo_db->mongoId((string)$direct_parent_child_id);
            $search2['symbol'] = $symbol;
            $search2['cavg_parent'] = 'yes';
            //echo '<pre>';print_r($search2);exit;
            $connect->$collection_order->updateOne($search2,['$set'=>$update_array], ['upsert' => true]);
            // updating the sold order in sold collection
            $search3['_id'] = $this->mongo_db->mongoId((string)$order_id);
            $search3['admin_id'] = $admin_id;
            $search3['symbol'] = $symbol;
            $update_array2 = [
                'move_to_cost_avg'=>'yes',
                // 'cost_avg'=>'completed',
                'ist_parent_child_buy_id'=>$this->mongo_db->mongoId((string)$direct_parent_child_id),
                'direct_parent_child_id'=>$this->mongo_db->mongoId((string)$direct_parent_child_id),
                'modified_date'=>$this->mongo_db->converToMongodttime($date),
                'moved_to_cost_avg_loss_sell'=>'yes'
            ];
            $connect->$collection_sold->updateOne($search3,['$set'=>$update_array2]);
            $resp['success']=true;
            $resp['data']=$order_id;
            $resp['message']='Order successfuly moved to cost avg';
            $resp['exchange']=$exchange;
            echo json_encode($resp);exit;
        }
    }
    public function allocated_balance($admin_id, $exchange){
        $conn = $this->mongo_db->customQuery();
        $collection_allocated='user_investment_'.$exchange;
        $allocated_data = $conn->$collection_allocated->find(['admin_id'=>$admin_id]);
        $results= iterator_to_array($allocated_data);
        $data = $results;
        $myVal['new_atg_allocated_btc'] = (float) $data[0]['new_atg_allocated_btc'];
        $myVal['new_atg_allocated_usdt'] = (float) $data[0]['new_atg_allocated_usdt'];
        $myVal['remaining_btc_allocated'] = (float) $data[0]['remaining_btc_allocated'];
        $myVal['remaining_usdt_allocated'] = (float) $data[0]['remaining_usdt_allocated'];
        return $myVal;
       
    }
    public function get_user_balance_info($user_id,$exchange){
        $userID=(string)$user_id;
        $token = $this->mod_jwt->custom_token($user_id);
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
}         
