<?php
ob_start();
defined('BASEPATH') or exit('No direct script access allowed');
/** **/
class Buy_orders extends CI_Controller
{
    function __construct(){
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
        $this->load->model('admin/mod_users');
        $this->load->model('admin/mod_coins');
        $this->load->model('admin/mod_buy_orders');


        //load library for session
        $this->load->library('session');
    }

    public function update_now_order(){
        $this->mod_login->verify_is_admin_login();
        $this->stencil->paint('admin/buy_order/new_update');
    }

    public function update_now_process(){
        $this->mod_login->verify_is_admin_login();
        $post_data = $this->input->post();
        $ipsArray = array('ip1'=>'3.227.143.115','ip2'=>'3.228.180.22','ip3'=>'3.226.226.217','ip4'=>'3.228.245.92','ip5' =>'35.153.9.225','ip6'=>'54.157.102.20','binance_ip'=>'18.170.235.202');
        
        $trading_ip_posted = $post_data['trading_ip'];
        $post_data['trading_ip'] = $ipsArray[$trading_ip_posted];
         // echo "<pre>";
         //    print_r($post_data);
         //    exit;
        $set_arr = [
            'purchased_price'       =>  (float)$post_data['purchased_price'],
            'quantity'              =>  (float)$post_data['quantity'],
            'loss_percentage'       =>  (float)$post_data['loss_percentage'],
            'sell_price'            =>  (float)$post_data['sell_price'],
            'iniatial_trail_stop'   =>  (float)$post_data['iniatial_trail_stop'], 
            'stop_loss'             =>  $post_data['stop_loss'],
            'sell_profit_percent'   =>  (float)$post_data['sell_profit_percent'],
            'lth_profit'            =>  (float)$post_data['lth_profit'],
            'market_value_usd'      =>  (float)$post_data['market_value_usd'],
            'status'                =>  $post_data['status'],
            'order_level'           =>  $post_data['order_level'],
            'auto_sell'             =>  $post_data['auto_sell'],
            'trail_check'           =>  $post_data['trail_check'],
            'trail_interval'        =>  (float)$post_data['trail_interval'],
            'sell_trail_price'      =>  (float)$post_data['sell_trail_price'],
            'sell_profit_percent'   =>  (float)$post_data['sell_profit_percent'],
            'is_sell_order'         =>  (string)$post_data['is_sell_order'],
            'trading_status'        =>  (string)$post_data['trading_status'],
            'new_child_buy_price'   =>  (float)$post_data['new_child_buy_price'],
            'new_child_price_upd'   =>  (string)$post_data['new_child_price_upd'],
            'mapped_order'          =>  true,
            'cost_avg'              =>  (string)$post_data['cost_avg'],
            'market_sold_price'     =>  (float)$post_data['market_sold_price'],  
            'market_value'          =>  (float)$post_data['market_value'],
            'market_value_usd'      =>  (float)$post_data['market_value_usd'],
            'avg_sell_price'        =>  (float)$post_data['avg_sell_price'],
            'avg_sell_price_three'  =>  (float)$post_data['avg_sell_price_three'],
            'move_to_cost_avg'      =>  (string)$post_data['move_to_cost_avg'],
            'quantity_all'          =>  (float)$post_data['quantity_all'],
            'trigger_type'          =>  $post_data['trigger_type'],
            'stop_loss_type'        =>  (string)$post_data['stop_loss_type'],
            'need_price_update'        =>  (string)$post_data['need_price_update'],
            'admin_modified_date'   =>  $this->mongo_db->converToMongodttime(date('Y-m-d h:i:s')),
        ];
        if(!empty($post_data['binance_order_id_sell'])){
           $set_arr['binance_order_id_sell'] = !empty($post_data['binance_order_id_sell'])?$post_data['binance_order_id_sell']:'';
        }
        if(!empty($post_data['trading_ip']))
        {
            $set_arr['trading_ip'] = $post_data['trading_ip'];
        }
        if($post_data['quantity_issue'] == 1){
            $set_arr['quantity_issue'] = $post_data['quantity_issue'];
        }

        if($post_data['tradeHistoryIssue'] == "yes"){
            $set_arr['trade_history_issue'] = 'yes';
        }
         if(!empty($_COOKIE['hassan'])) {
            echo "<pre>";
            print_r($post_data['_id']);
            print_r($set_arr);
            exit;
        }
        
        $id = $post_data['_id'];
        $exchange = $post_data['exchange'];
        $date = date('Y-m-d G:i:s');
        $order_created_month  = date('m', ($post_data['created_date'] / 1000));
        $order_created_month -= 1;
        $order_created_year  = date('Y', ($post_data['created_date'] / 1000));

        $insert_log_array = array(
            'order_id' => $id,
            'created_date' => $this->mongo_db->converToMongodttime($date),
            'log_msg' => 'Order buy array updated using manual interface:'.$this->session->userdata('admin_id'),
            'type' => 'edit_manul_interface',
            'show_hide_log' => 'yes'
        );
        $orderCreatedDate = date('Y-m-d H:i:s', ($post_data['created_date'] / 1000));
        $currentDateStatus = date('2019-12-27:04:21.91');
        if ($orderCreatedDate > $currentDateStatus){
            if ($exchange == 'binance') {
                $collection = $post_data['application_mode'] == 'live' ? "orders_history_log_live_".$order_created_year."_".$order_created_month : "orders_history_log_test_".$order_created_year."_".$order_created_month;
            }else{
                $collection = $post_data['application_mode'] == 'live' ? "orders_history_log_".$exchange."_".$order_created_year."_".$order_created_month : "orders_history_log_".$exchange."_test_".$order_created_year."_".$order_created_month;
            }
            
        } else {
            $collection = $exchange == "binance" ? "orders_history_log" : "orders_history_log_$exchange";
        }

        if (isset($post_data['application_mode']) && isset($post_data['created_date']) && isset($exchange)){   
            $this->mongo_db->insert($collection, $insert_log_array);
        }
    
        if ($exchange == 'bam') {
            $coll = 'buy_orders_bam';
        } elseif ($exchange == 'binance') {
            $coll = 'buy_orders';
        }elseif($exchange == 'kraken'){
            $coll = 'buy_orders_kraken';
        }
        $this->mongo_db->where(array('_id' => $this->mongo_db->mongoId((string)$id)));
        $this->mongo_db->set($set_arr);
        $query = $this->mongo_db->update($coll);
        // print_r( $query);exit;
        if ($query){
            $this->session->set_flashdata('query_message', "Your selected buy array record successfully updated Total effected records count = " . $query);
        } else {
            $this->session->set_flashdata('error_message', 'not updated something wrrong try again!');
        }
        redirect(SURL . "admin/buy_orders/update_now_order");
    }

    public function update_now_sell_process(){
        $this->mod_login->verify_is_admin_login();
        $post_data = $this->input->post();
        $ipsArray = array('ip1'=>'3.227.143.115','ip2'=>'3.228.180.22','ip3'=>'3.226.226.217','ip4'=>'3.228.245.92','ip5' =>'35.153.9.225','ip6'=>'54.157.102.20','binance_ip'=>'18.170.235.202');
        
        $trading_ip_posted = $post_data['trading_ip'];
        $post_data['trading_ip'] = $ipsArray[$trading_ip_posted];
        $set_arr = [
            'purchased_price'       =>  (float)$post_data['purchased_price'],
            'quantity'              =>  (float)$post_data['quantity'],
            'loss_percentage'       =>  (float)$post_data['loss_percentage'],
            'sell_price'            =>  (float)$post_data['sell_price'],
            'iniatial_trail_stop'   =>  (float)$post_data['iniatial_trail_stop'],
            'stop_loss'             =>  $post_data['stop_loss'],
            'sell_profit_percent'   =>  (float)$post_data['sell_profit_percent'],
            'lth_profit'            =>  (float)$post_data['lth_profit'],
            'market_value'          =>  (float)$post_data['market_value'],
            'market_value_usd'      =>  (float)$post_data['market_value_usd'],
            'status'                =>  $post_data['status'],
            'order_level'           =>  $post_data['order_level'],
            'auto_sell'             =>  $post_data['auto_sell'],
            'trail_check'           =>  $post_data['trail_check'],
            'trail_interval'        =>  (float)$post_data['trail_interval'],
            'sell_trail_price'      =>  (float)$post_data['sell_trail_price'],
            'sell_profit_percent'   =>  (float)$post_data['sell_profit_percent'],
            'mapped_order'          =>  true,
            'trigger_type'          =>  $post_data['trigger_type'],
            'created_date'          =>  $this->mongo_db->converToMongodttime($post_data['created_date'])
        ];
                
        if($post_data['tradeHistoryIssue'] == "yes"){   
            $set_arr['trade_history_issue'] = 'yes';
        }
         if(!empty($post_data['trading_ip']))
        {
            $set_arr['trading_ip'] = $post_data['trading_ip'];
        }
          if(!empty($_COOKIE['hassan'])) {
            echo "<pre>";
            print_r($set_arr);
            exit;
        }
        
        $id = $post_data['_id'];
        $exchange = $post_data['exchange'];
        $date = date('Y-m-d G:i:s');
        $order_created_month  = date('m', ($post_data['created_date'] / 1000));
        $order_created_month -= 1;
        $order_created_year  = date('Y', ($post_data['created_date'] / 1000));

        $insert_log_array = array(
            'order_id' => $id,
            'created_date' => $this->mongo_db->converToMongodttime($date),
            'log_msg' => 'Order sell array updated using manual interface: '.$this->session->userdata('admin_id'),
            'type' => 'edit_manul_interface',
            'show_hide_log' => 'yes'
        );
        if ($exchange == 'binance') {
            if ($post_data['application_mode'] == 'live') {
                $collection1 = "orders_history_log_live_" . $order_created_year . "_" . $order_created_month;
            } else {
                $collection1 = "orders_history_log_test_" . $order_created_year . "_" . $order_created_month;
            }
        } elseif($exchange == 'bam') {
            if ($post_data['application_mode'] == 'live') {
                $collection1 = "orders_history_log_" . $exchange . "_live_" . $order_created_year . "_" . $order_created_month;
            } else {
                $collection1  = "orders_history_log_" . $exchange . "_test_" . $order_created_year . "_" . $order_created_month;
            }
        }elseif($exchange == 'kraken') {
            if ($post_data['application_mode'] == 'live') {
                $collection1 = "orders_history_log_" . $exchange . "_live_" . $order_created_year . "_" . $order_created_month;
            } else {
                $collection1 = "orders_history_log_" . $exchange . "_test_" . $order_created_year . "_" . $order_created_month;
            }
        }
        if (isset($post_data['application_mode']) && isset($post_data['created_date']) && isset($exchange)) {
            $this->mongo_db->insert($collection1, $insert_log_array);
        }
        if ($exchange == 'bam') {
            $coll = 'orders_bam';
        } elseif ($exchange == 'binance') {
            $coll = 'orders';
        }elseif ($exchange == 'kraken') {
            $coll = 'orders_kraken';
        }
        $this->mongo_db->where(array('buy_order_id' => $id));
        $this->mongo_db->set($set_arr);
        $query1 = $this->mongo_db->update($coll);

        if ($query1) {
            $this->session->set_flashdata('query_message', "Your selected sell array record successfully updated Total effected records count = " . $query1);
        } else {
            $this->session->set_flashdata('error_message', 'not updated something wrrong try!');
        }
        redirect(SURL . "admin/buy_orders/update_now_order");
    }

    public function get_order_ajax(){
        $this->mod_login->verify_is_admin_login();
        $post_data = $this->input->post();
        $id = $post_data['orderid'];
        // $userid = $post_data['userid'];
        if (isset($post_data['exchange'])) {
            $exchange = $post_data['exchange'];
        } else {
            $exchange = 'binance';
        }
        // $exchange ='bam';
        // print_r($post_data); exit;
        //$search_id_check= $this->mongo_db->mongoId((string) $id);
        $check = $this->mongo_db->isValid((string)$id);
        if(!$check){
            $html_error = '
                <div class="blink_me" align="center" style="background-color:#e8afaf;" >
                <label style="margin-top:13px;font-size: 25px;">Inavlid id.</label>
                </div>
                ';
            $html_error1 = '
                <div class="blink_me" align="center" style="background-color:#e8afaf;" >
                <label style="margin-top:13px;font-size: 25px;">Invalid id.</label>
                </div>
                ';
              echo $html_error . '@@@@@' . $html_error1;
            exit;
        }
        if (isset($id) && isset($exchange) && !empty($id)) {
            $this->session->set_userdata('order_idd', $id);
            // $this->session->set_userdata('user_idd', $userid);
            $this->session->set_userdata('exchge', $exchange);
            $order = $this->get_order_by_id($id, $exchange);
        }else{
            $html_error = '
                <div class="blink_me" align="center" style="background-color:#e8afaf;" >
                <label style="margin-top:13px;font-size: 25px;">Please enter Order id.</label>
                </div>
                ';
            $html_error1 = '
                <div class="blink_me" align="center" style="background-color:#e8afaf;" >
                <label style="margin-top:13px;font-size: 25px;">Please enter Order id.</label>
                </div>
                ';
            echo $html_error . '@@@@@' . $html_error1;
            exit;
        } 
        // else {
        //     $id = $this->session->userdata('order_idd');
        //     $userid = $this->session->userdata('user_idd');
        //     $exchange = $this->session->userdata('exchge');
        //     $order = $this->get_order_by_id($id, $userid, $exchange);
        // }
        $ipsArray = array('ip1'=>'3.227.143.115','ip2'=>'3.228.180.22','ip3'=>'3.226.226.217','ip4'=>'3.228.245.92','ip5' =>'35.153.9.225','ip6'=>'54.157.102.20','binance_ip'=>'18.170.235.202');
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $buy_order = $order['buy_order'];
           // echo '<pre>';print_r($buy_order);exit;
            if(empty($buy_order)){
             $html_error = '
                <div class="blink_me" align="center" style="background-color:#e8afaf;" >
                <label style="margin-top:13px;font-size: 25px;">No Order found please look into another collection</label>
                </div>
                ';
            $html_error1 = '
                <div class="blink_me" align="center" style="background-color:#e8afaf;" >
                <label style="margin-top:13px;font-size: 25px;">No Order found please look into another collection</label>
                </div>
                ';
                echo $html_error . '@@@@@' . $html_error1;
                exit;
            }
      
    
        $html = '<form action="' . SURL . 'admin/buy_orders/update_now_process" method="post">';
        foreach ($buy_order as $attr => $value) {
            $in_arr = ['cost_avg' ,'order_level', 'market_value_usd','market_value','is_order_copyed','auto_sell','price', 'quantity', 'symbol', 'custom_stop_loss_percentage', 'trigger_type', 'sell_price', 'iniatial_trail_stop', 'defined_sell_percentage', 'sell_profit_percent', 'lth_profit', 'binance_order_id', 'binance_order_id_sell', 'status', 'trail_check', 'trail_interval', 'buy_trail_price', 'market_sold_price', 'is_sell_order', 'trading_status', 'purchased_price','trading_ip','new_child_buy_price','new_child_price_upd','avg_sell_price','avg_sell_price_three','quantity_all','move_to_cost_avg'];
            if (in_array($attr, $in_arr)) {
                if (gettype($value) == 'string') {
                    if ($attr != 'status' && $attr != 'trading_ip' && $attr != 'trigger_type' && $attr != 'symbol') {
                        $html .= '
                        <div class="form-group">
                            <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                            <input type="text" class="form-control" name = "' . $attr . '" id="' . $attr . '" value = "' . $value . '">
                        </div>
                        ';
                    }

                    if ($attr == 'status') {
                        $html .= '
                                <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                                <select name="' . $attr . '" style="width:100%;height:34px">
                                    <option value="' . $value . '"checked>' . $value . '</option>
                                    <option value="FILLED">FILLED</option>
                                    <option value="new">new</option>
                                    <option value="LTH">LTH</option>
                                    <option value="fraction_submitted_sell">fraction_submitted_sell</option>
                                    <option value="submitted_for_sell">submitted_for_sell</option>
                                    <option value="new_ERROR">new_ERROR</option>
                                    <option value="LTH_ERROR">LTH_ERROR</option>
                                    <option value="submitted_ERROR">submitted_ERROR</option>
                                    <option value="FILLED_ERROR">FILLED_ERROR</option>
                                    <option value="COIN_BAN_ERROR">COIN_BAN_ERROR</option>  
                                    <option value="IP_BAN_ERROR">IP_BAN_ERROR</option>  
                                    <option value="APIKEY_ERROR">APIKEY_Error</option>  
                                    <option value="binance_sold_doubt">Binance sold Doubt</option>  
                                    <option value="LOT_SIZE_ERROR">LOT SIZE ERROR</option>  
                                    <option value="canceled">canceled</option>
                                    <option value="ARCHIVE">Archive</option>
                                    <option value="CA_TAKING_CHILD">CA_TAKING_CHILD</option>
                                </select>';
                    }
                    
                    if ($attr == 'trading_ip') {
                        $ipsHtml = '';
                        $checked = '';
                        foreach ($ipsArray as $IPkey => $IPvalue) {
                           
                            $checked = $IPvalue == $value?"selected":"";
                            $ipsHtml.='<option value="' . $IPkey . '" '.$checked.'>' . $IPkey . '</option>';
                        }

                        $html .= '
                                <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                                <select name="' . $attr . '" style="width:100%;height:34px">
                                    '.$ipsHtml.'
                                </select>';
                    }

                    if ($attr == 'trigger_type' || $attr == 'symbol') {
                        $html .= '
                            <div class="form-group">
                                <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                                <input type="text" class="form-control" name = "' . $attr . '" id="' . $attr . '" value = "' . $value . '" readonly>
                            </div>
                            ';
                    }
                }  //end main if
                elseif (gettype($value) == 'float' || gettype($value) == 'double' || gettype($value) == 'integer' || $attr != 'trigger_type' || $attr != 'symbol' || $attr != 'trading_ip') {
                    $html .= '
                        <div class="form-group">
                            <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                            <input type="text" class="form-control" name = "' . $attr . '" id="' . $attr . '" value = "' . $value . '" >
                        </div>
                        ';
                }
            }
        }// end foreach
        $html .= '
        <div class="form-group">
            <input type="hidden" class="form-control" name = "application_mode" id="application_mode" value = "' . $buy_order['application_mode'] . '">
        </div>
        ';

        $html .= '
            <label for="trade History Issue"> tradeHistoryIssue :</label>
            <select name="tradeHistoryIssue" style="width:100%;height:34px">
                <option value="no"> No </option>
                <option value="yes"checked> Yes </option>
            </select>';

        $html .= '
            <label for="trade History Issue"> Stop Loss Type :</label>
            <select name="stop_loss_type" style="width:100%;height:34px">
                <option value="' . $buy_order['stop_loss_type'] . '"checked>' . $buy_order['stop_loss_type'] . '</option>
                <option value="negetive"> Negetive </option>
                <option value="positive"checked> Positive </option>
            </select>';
        $html .= '
            <label for="trade History Issue"> need price update :</label>
            <select name="need_price_update" style="width:100%;height:34px">
                <option value="' . $buy_order['need_price_update'] . '"checked>' . $buy_order['need_price_update'] . '</option>
                <option value="buy"> Buy </option>
                <option value="sell"checked> Sell </option>
            </select>';
            
        // $html .= '<div class="form-check">
        //       <input class="form-check-input" type="checkbox" value="1" id="check_created">
        //       <label class="form-check-label" for="check_created">
        //             Update Created Date
        //       </label>
        //     </div>';
        $html .= '
        <label>Created Date</label>';
        // $html .= '
        // <label>Created Date</label>
        // // <div class="form-group">
        // //     <input type="datetime-local" class="form-control" name = "created_date" id="created_date" value = "' .$buy_order['created_date'] .'">
        // // </div>
        // ';

        $html .= '
        <div class="form-group">
            <label for="quantity_issue">Qty Issue:</label>
            <input type="checkbox" id="quantity_issue" name="quantity_issue" value="1">
        </div>
        ';
        $html .= '
            <div class="form-group">
                <input type="hidden" class="form-control" name = "exchange" id="exchange" value = "' . $buy_order['exchange'] . '">
            </div>
            ';
        $html .= '
            <div class="form-group">
                <input type="hidden" class="form-control" name = "_id" id="_id" value = "' . $buy_order['_id'] . '">
            </div>
            ';
            
        /*            $html .= '
                <div class="form-group">
                <label for="is_sell_order">is sell order ( sold ):</label>
                    <input type="text" class="form-control" name = "is_sell_order" id="is_sell_order"placeholder="sold">
                </div>
                ';

                $html .= '
                <div class="form-group">
                <label for="trading_status">trading status ( complete ):</label>
                    <input type="text" class="form-control" name = "trading_status" id="trading_status"placeholder="complete">
                </div>
                ';

                $html .= '
                <div class="form-group">
                <label for="market_sold_price">market sold price ( 0.00000021 ):</label>
                    <input type="text" class="form-control" name = "market_sold_price" id="market_sold_price"placeholder="0.000000">
                </div>
                    ';
        */
        $html .= '<hr class="separator">
        <!-- Form actions -->
        <div class="form-actions">
        <button class="btn btn-success" id="add_order_p2" type"submit"><i class="fa fa-check-circle"></i> Update Order </button>
        </div>
        <!-- // Form actions END -->
        </form>';
        //$this->session->set_userdata('buy_data', $html);
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $sell_order = $order['sell_order'];
        // $html = "<form role='form method='POST' action = '".SURL."admin/buy_orders/update_now_process'>";
        $html1 = '<form action="' . SURL . 'admin/buy_orders/update_now_sell_process" method="post">';
        foreach ($sell_order as $attr => $value) {
            $in_arr = ['order_level','auto_sell','purchased_price', 'quantity', 'symbol', 'stop_loss', 'trigger_type', 'sell_price', 'iniatial_trail_stop', 'loss_percentage', 'sell_profit_percent', 'market_value', 'sell_binance_order_id', 'market_value_usd', 'status', 'trail_check', 'trail_interval', 'sell_trail_price','trading_ip'];
            if (in_array($attr, $in_arr)) {
                if (gettype($value) == 'string') {
                    if ($attr != 'status' && $attr != 'trading_ip' && $attr != 'trigger_type' && $attr != 'symbol') {
                        $html1 .= '
                        <div class="form-group">
                            <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                            <input type="text" class="form-control" name = "' . $attr . '" id="' . $attr . '" value = "' . $value . '">
                        </div>
                        ';
                    }
                    if ($attr == 'status') {
                        $html1 .= '
                            <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                            <select name="' . $attr . '" style="width:100%;height:34px">
                                <option value="' . $value . '"checked>' . $value . '</option>
                                <option value="FILLED">FILLED</option>
                                <option value="new">new</option>
                                <option value="LTH">LTH</option>
                                <option value="canceled">canceled</option>
                                <option value="new_ERROR">new_ERROR</option>
                                <option value="LTH_ERROR">LTH_ERROR</option>
                                <option value="submitted_ERROR">submitted_ERROR</option>
                                <option value="FILLED_ERROR">FILLED_ERROR</option> 
                            </select>';
                    }
                     if ($attr == 'trading_ip') {
                        $ipsHtml1 = '';
                        foreach ($ipsArray as $IPkey => $IPvalue) {
                            $checked = $IPvalue == $value?"checked":"";
                            $ipsHtml1.='<option value="' . $IPkey . '" '.$checked.'>' . $IPkey . '</option>';
                        }

                        $html1 .= '
                                <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                                <select name="' . $attr . '" style="width:100%;height:34px">
                                    '.$ipsHtml1.'
                                </select>';
                    }
                    if ($attr == 'trigger_type' || $attr == 'symbol') {
                        $html1 .= '
                            <div class="form-group">
                                <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                                <input type="text" class="form-control" name = "' . $attr . '" id="' . $attr . '" value = "' . $value . '" readonly>
                            </div>
                            ';
                    }
                } elseif (gettype($value) == 'double' || gettype($value) == 'integer') {
                    $html1 .= '
                        <div class="form-group">
                            <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                            <input type="text" class="form-control" name = "' . $attr . '" id="' . $attr . '" value = "' . $value . '">
                        </div>
                        ';
                }
            }
        }

       
        $html1 .= '
        <div class="form-group">
            <input type="hidden" class="form-control" name = "application_mode" id="application_mode" value = "' . $sell_order['application_mode'] . '">
        </div>
        ';
        $html1 .= '
        <label>Created Date</label>
        <div class="form-group">
            <input type="datetime-local" class="form-control" name = "created_date" id="created_date" value = "' . $sell_order['created_date'] . '">
        </div>
        ';

        $html1 .= '
            <label for="trade History Issue"> tradeHistoryIssue :</label>
            <select name="tradeHistoryIssue" style="width:100%;height:34px">
                <option value="no"> No </option>
                <option value="yes"checked> Yes </option>
            </select>';

        $html1 .= '
            <div class="form-group">
                <input type="hidden" class="form-control" name = "exchange" id="exchange" value = "' . $sell_order['exchange'] . '">
            </div>
            ';
        $html1 .= '
            <div class="form-group">
                <input type="hidden" class="form-control" name = "_id" id="_id" value = "' . $sell_order['buy_order_id'] . '">
            </div>
            ';
        $html1 .= '<hr class="separator">

        <!-- Form actions -->
        <div class="form-actions">
        <button class="btn btn-success" id="add_order_p2" type"submit"><i class="fa fa-check-circle"></i> Update Order </button>
        </div>
        <!-- // Form actions END -->
        </form>';
        // $this->session->set_userdata('sell_data', $html1);
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        echo $html . '@@@@@' . $html1;
        exit;
    }

        ////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////                               ///////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////


    public function get_order_by_id($id, $exchange){
        $this->mod_login->verify_is_admin_login();
        // ini_set("display_errors", E_ALL);
        // $exchange == 'bam';
        error_reporting(E_ALL);
        if ($exchange == 'bam') {
            $coll = "buy_orders_bam";
            $coll2 = 'orders_bam';
        } elseif($exchange == 'binance') {
            $coll = "buy_orders";
            $coll2 = 'orders';
        } elseif($exchange == 'kraken') {
            $coll = "buy_orders_kraken";
            $coll2 = 'orders_kraken';
        }elseif($exchange == 'dg') {
            $coll = "buy_orders_dg";
            $coll2 = 'orders_dg';
        }elseif($exchange == 'okex') {
            $coll = "buy_orders_okex";
            $coll2 = 'orders_okex';
        }
        $search_arr['_id'] = $this->mongo_db->mongoId((string) $id);
        // $search_arr['admin_id'] = $userid;
        //echo json_encode($search_arr);
        $this->mongo_db->where($search_arr);
        $object = $this->mongo_db->get($coll);
        $object = iterator_to_array($object);
        if(empty($object)){
            return $object;
        }
        $object[0]['exchange'] = $exchange;
        
        //getting Sell Array
        //$search_arr1['buy_order_id'] = array('$in' => array((string)$id, $this->mongo_db->mongoId((string)$id)));
        //$search_arr1['buy_order_id'] = $this->mongo_db->mongoId((string)$id);
        $search_arr1['_id'] = $this->mongo_db->mongoId((string) $object[0]['sell_order_id']);
        // $search_arr1['admin_id'] = $userid;
        $this->mongo_db->where($search_arr1);
        $object1 = $this->mongo_db->get($coll2);
        $object1 = iterator_to_array($object1);
        $object1[0]['exchange'] = $exchange;
        $returnarray = ['buy_order' => $object[0], 'sell_order' => $object1[0]];
        return $returnarray;
    }
        //////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////                                  ////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////

    public function update_manual_entries(){
        $this->mod_login->verify_is_admin_login();
        if ($this->input->post()) {
            $upd_arr = array();
            $id = $this->input->post("order_id");
            $admin_id = $this->input->post("user_id");
            if ($this->input->post('purchased_price')) {
                $upd_arr['purchased_price'] = (float) $this->input->post('purchased_price');
            }
            if ($this->input->post('iniatial_trail_stop')) {
                $upd_arr['iniatial_trail_stop'] = (float) $this->input->post('iniatial_trail_stop');
            }
            if ($this->input->post('buy_trail_price')) {
                $upd_arr['buy_trail_price'] = (float) $this->input->post('buy_trail_price');
            }
            if ($this->input->post('market_value')) {
                $upd_arr['market_value'] = (float) $this->input->post('market_value');
            }
            if ($this->input->post('sell_price')) {
                $upd_arr['sell_price'] = (float) $this->input->post('sell_price');
            }

            // echo "<pre>";
            // print_r(array("admin_id" => $admin_id, "_id" => $this->mongo_db->mongoId($id)));
            // print_r($upd_arr);

            // $this->mongo_db->where(array("admin_id" => $admin_id, "_id" => $this->mongo_db->mongoId($id)));
            // $get = $this->mongo_db->get("buy_orders");
            // print_r(iterator_to_array($get));
            // exit;

            $db = $this->mongo_db->customQuery();
            $ins_buy = $db->buy_orders->updateOne(array("admin_id" => $admin_id, "_id" => $this->mongo_db->mongoId($id)), array('$set' => $upd_arr));
            $ins_sell = $db->orders->updateOne(array("admin_id" => $admin_id, "buy_order_id" => $this->mongo_db->mongoId($id)), array('$set' => $upd_arr));
            $data['buy_message'] = $ins_buy;
            $data['sell_message'] = $ins_sell;
        }
        $this->stencil->paint('admin/buy_order/update_manual_entries', $data);
    }

        ///////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////                                      /////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////

    public function sell_array(){
        $this->mod_login->verify_is_admin_login();
        $data = $this->input->post();
        $exchange = $data['exchange1'];
        // $account_id = $data['userid1'];
        $order_id = $data['orderid1'];
        if(isset($exchange) && isset($order_id)){
            $buy_array = $this->get_buy_array($exchange, $order_id);
        } 
        $date = date('Y-m-d G:i:s');
        if(isset($buy_array)){
            $make_sell_array= array(
                'status' => 'new',
                'buy_order_id' =>$buy_array['buy_order']['_id'],
                'sell_order_id' => $buy_array['buy_order']['sell_order_id'],
                'order_type' => $buy_array['buy_order']['order_type'],
                'trigger_type' =>$buy_array['buy_order']['trigger_type'],
                'application_mode' =>$buy_array['buy_order']['application_mode'],
                'order_mode' =>$buy_array['buy_order']['order_mode'],
                'mapped_order' => true,
                'created_date' =>$this->mongo_db->converToMongodttime($date),
                'purchased_price' =>$buy_array['buy_order']['purchased_price'],
                'quantity' =>$buy_array['buy_order']['quantity'],
                'admin_id' =>$buy_array['buy_order']['admin_id'],
                'order_level' =>$buy_array['buy_order']['order_level'],
                'sell_profit_percent'=>$buy_array['buy_order']['sell_profit_percent'],
                'sell_price'=>$buy_array['buy_order']['sell_price'],
                'iniatial_trail_stop'=>$buy_array['buy_order']['iniatial_trail_stop'],
                'custom_stop_loss_percentage' => $buy_array['buy_order']['custom_stop_loss_percentage']
            );  
            if ($exchange == 'bam') {
                $coll = 'orders_bam';
            } elseif ($exchange == 'binance') {
                $coll = 'orders';
            }elseif ($exchange == 'kraken') {
                $coll = 'orders_kraken';
            }
                $search['_id'] = $this->mongo_db->mongoId($buy_array['buy_order']['sell_order_id']);//search buy order id in sell colection and update new fileds their
                $custom = $this->mongo_db->customQuery(); 
                $upsert['upsert'] = true;
                $query = $custom->$coll->updateOne($search, ['$set'=> $make_sell_array], $upsert);
                // $this->mongo_db->where($search);
                // $this->mongo_db->set($make_sell_array);
                // $query = $this->mongo_db->update($coll);
                echo"<pre>";
                echo "col1".$coll;
                print_r($make_sell_array);
                print_r($search);
            if ($query) {
                //  $this->session->set_flashdata('query_message', "your selected order sell array are created successfully and Total effected rows = " . $query);
                return true;
            } else {
                return false;
            }
        }
    }

    //adding sell_order_id 
    public function sell_order_id(){
        $this->mod_login->verify_is_admin_login();
        $data = $this->input->post();
        echo "<pre>";
        print_r($data);
        $exchange = $data['exchange'];
        // $account_id = $data['user_id'];
        $order_id = $data['order_id'];
        $this->load->helper('string');
        if ($exchange == 'bam') {
            $collection_name = 'buy_orders_bam';
        } elseif ($exchange == 'binance') {
            $collection_name = 'buy_orders';
        }elseif ($exchange == 'kraken') {
            $collection_name = 'buy_orders_kraken';
        }

        $sell_order_id = 0;
        $sell_order_id = random_string(28);
        echo "sell_order_id = ".$sell_order_id;
        if(isset($exchange) &&  isset($account_id) && isset($order_id)){
            $where['_id'] = $this->mongo_db->mongoId($order_id);
            // $where['admin_id'] = (string)$account_id;
            $set = array(
                'sell_order_id' => $this->mongo_db->mongoId($sell_order_id),
                'mapped_order' => true
            );
            $db = $this->mongo_db->customQuery();
            $upsert['upsert'] = true;
            $query = $db->$collection_name->updateOne($where, ['$set'=> $set], $upsert);
            // $db->$collection_name->updateOne($where, array('$set' =>  $set));
        }

    }
        ///////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////                                         ////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////

    public function get_buy_array($exchange, $order_id){
        $this->mod_login->verify_is_admin_login();
        if(isset($exchange) &&  isset($order_id))
        {
            if ($exchange == 'bam') {
                $coll = "buy_orders_bam";
            } elseif($exchange == 'binance'){
                $coll = "buy_orders";
            }elseif($exchange == 'kraken'){
                $coll = "buy_orders_kraken";
            }
            $search_arr['_id'] = $this->mongo_db->mongoId($order_id);
            // $search_arr['admin_id'] = (string)$account_id;
            //$search_arr['application_mode'] = 'test';
            $this->mongo_db->where($search_arr);
            $object = $this->mongo_db->get($coll);
            $object = iterator_to_array($object);
            $object[0]['exchange'] = $exchange;
            $buy_return = ['buy_order' => $object[0]];
            return   $buy_return;
        } // End main if
    }

        ///////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////                                   ///////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////
    public function delete_order(){ 
        $this->mod_login->verify_is_admin_login();
        $data_array = $this->input->post();
        $exchange   = $data_array['exchange2'];
        // $account_id = $data_array['user_id2'];
        $order_id   = $data_array['order_id2'];
        // echo "<pre>";
        // print_r( $data_array);    
        // exit;
        if(isset($exchange) && isset($order_id)){
            $buy_array = $this->search_delete_order($exchange, $order_id);
            if($buy_array)
            {
                return $buy_array;
            }
        } 
    }
        //////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////                                   ////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////
    public function search_delete_order($exchange, $order_id){
        $this->mod_login->verify_is_admin_login();
        if(isset($exchange) &&  isset($order_id)){
            if ($exchange == 'bam') {
                $coll = "buy_orders_bam";
                $coll2 = 'orders_bam';
            } elseif($exchange == 'binance') {
                $coll = "buy_orders";
                $coll2 = 'orders';
            }elseif($exchange == 'kraken') {
                $coll = "buy_orders_kraken";
                $coll2 = 'orders_kraken';
            }
        }
        // $search_arr['_id'] = $this->mongo_db->mongoId((string)$order_id);
        // $search_arr['admin_id'] = $account_id;

        $db = $this->mongo_db->customQuery();
        $order = $db->$coll->find(array("_id" => $this->mongo_db->mongoId($order_id)));
    
        $data = iterator_to_array($order);
        $order_craeted_date = (string)$data[0]['created_date'];
        $order_craeted = $data[0]['created_date'];
        $application_mode = $data[0]['application_mode'];
        
        $current_time = (string)$this->mongo_db->converToMongodttime(date('2019-12-27 04:21:59'));
        $collectionName = ($exchange == 'binance') ? 'orders_history_log' : 'orders_history_log_'.$exchange;
        if ($order_craeted_date > $current_time) {
            $created = $order_craeted->toDateTime()->format("Y-m-d");
            $timestamp = strtotime($created); 
            $month= date("m", $timestamp);
            $month = ($month -1);
            $fullCollectionName =  $collectionName.'_'.$application_mode.'_'.date("Y", $timestamp).'_'.$month;
            echo "<br>if full collection name =".$fullCollectionName; 
        } else {
            $fullCollectionName = ($exchange == 'binance') ? 'orders_history_log' : 'orders_history_log_'.$exchange;
            echo "<br>else full collection name =".$fullCollectionName; 
        }
        // logs delete
        $searchArray_log['order_id'] = $this->mongo_db->mongoId($order_id);
        $log_delete = $db->$fullCollectionName->deleteMany($searchArray_log);
        // delete order
        $query_buy_remove = $db->$coll->deleteOne(array("_id" => $this->mongo_db->mongoId($order_id)));
        $query_sell_remove = $db->$coll2->deleteOne(array("buy_order_id" => $this->mongo_db->mongoId($order_id)));

        // echo "<br>Sell count remove = ".$query_sell_remove->getDeletedCount();
        // echo "<br> Log remove Count = ".$log_delete->getDeletedCount();
        // echo "<br>Buy count remove  = ".$query_buy_remove->getDeletedCount();
        if($query_sell_remove->getDeletedCount() >= 1 && $query_buy_remove->getDeletedCount() >= 1){
            return true;
        }else{
            return false;
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////              GET PARENT ORDER              ///////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////

    public function get_parent_order(){
        $this->mod_login->verify_is_admin_login();
        $this->stencil->paint('admin/buy_order/parent_status_revert');
    }

    public function get_parent_ajax(){  
        $this->mod_login->verify_is_admin_login();  
        $detail = $this->input->post();
        $parent_id = $detail['orderid'];
        $admin_id  = $detail['userid'];
        $exchange  = $detail['exchange'];
        if(isset($exchange) && isset($parent_id) && isset($admin_id))
        {
            $this->session->set_userdata('exchange', $exchange);
            $this->session->set_userdata('parent_id',$parent_id);
            $this->session->set_userdata('admin_id', $admin_id);
            $parent = $this->get_parent_array( $exchange, $parent_id,  $admin_id);
        }
        $parent_order1 = $parent['parent_order'];
        $html = '<form action="' . SURL . 'admin/buy_orders/update_parent" method="post">';
        foreach ($parent_order1 as $attr => $value) {
            $in_arr = ['pick_parent','parent_status', 'quantity', 'status' ,'pause_status' ,'defined_sell_percentage', 'sell_profit_percent', 'current_market_price', 'activate_stop_loss_profit_percentage'];
            if (in_array($attr, $in_arr)) {
                if (gettype($value) == 'string') {
                    if ($attr != 'status' && $attr!='pause_status') {
                        $html .= '
                        <div class="form-group">
                            <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                            <input type="text" class="form-control" name = "' . $attr . '" id="' . $attr . '" value = "' . $value . '">
                        </div>
                        ';
                    }

                    if ($attr == 'status') {
                        $html .= '
                        <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                        <select name="' . $attr . '" style="width:100%;height:34px">
                        <option value="' . $value . '"checked>' . $value . '</option>
                        <option value="new">new</option>
                        <option value="canceled">canceled</option>
                        <option value="takingOrder">takingOrder</option>
                        </select>';
                    }

                    if ($attr == 'pause_status') {
                        $html .= '
                        <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                        <select name="' . $attr . '" style="width:100%;height:34px">
                        <option value="' . $value . '"checked>' . $value . '</option>
                        <option value="play">play</option>
                        <option value="pause">pause</option>
                        </select>';
                    }

                    
                }  //end main if
                elseif (gettype($value) == 'float' || gettype($value) == 'double' || gettype($value) == 'integer' || $attr != 'trigger_type' || $attr != 'symbol') {
                    $html .= '
                    <div class="form-group">
                        <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                        <input type="number" class="form-control" name = "' . $attr . '" id="' . $attr . '" value = "' . $value . '" >
                    </div>';
                }
            }
        }   //end foreach loop

        $html .= '
            <div class="form-group">
                <input type="hidden" class="form-control" name = "exchange" id="exchange" value = "' . $parent_order1['exchange'] . '">
            </div>
            ';

        $html .= '
            <div class="form-group">
                <input type="hidden" class="form-control" name = "_id" id="_id" value = "' . $parent_order1['_id'] . '">
            </div>
            ';
    
        $html .= '<hr class="separator">
        <!-- Form actions -->
        <div class="form-actions">
        <button class="btn btn-success" id="add_order_p2" type"submit"><i class="fa fa-check-circle"></i> Update Order </button>
        </div>
        <!-- // Form actions END -->
        </form>';
        echo $html;
        exit;
    }

    //////////////////////////////////////////////////////////////////////////////////////////
    ////////////////             GET PARENT DETAIL            ////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////

    public function get_parent_array($exchange, $parent_id,  $admin_id){
        $this->mod_login->verify_is_admin_login();
        if($exchange == 'bam')
        {
            $collection = 'buy_orders_'.$exchange;
        }elseif($exchange == 'kraken'){
            $collection = 'buy_orders_'.$exchange;
        }elseif($exchange == 'binance'){
            $collection= 'buy_orders';
        }
        $search_arr['_id'] = $this->mongo_db->mongoId((string)$parent_id);
        $search_arr['admin_id'] = $admin_id;
        
        $this->mongo_db->where($search_arr);
        $object = $this->mongo_db->get($collection);
        $object = iterator_to_array($object);
        $object[0]['exchange'] = $exchange;
        $returnn  = ['parent_order' => $object[0]];
        return $returnn;
    }

    //////////////////////////////////////////////////////////////////////////////////////////
    /////////             UPDATE PARENT            ///////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////
    
    public function update_parent(){
        $this->mod_login->verify_is_admin_login();
        $post_data = $this->input->post();
        $set_arr = [
            'quantity' => (float)$post_data['quantity'],
            'status' => $post_data['status'],
            'pause_status' => $post_data['pause_status'],
            'parent_status'=> $post_data['parent_status'],
            'defined_sell_percentage' => (float)$post_data['defined_sell_percentage'],
            'sell_profit_percent' => (float)$post_data['sell_profit_percent'],
            'current_market_price' => (float)$post_data['current_market_price'],
            'pick_parent' => $post_data['pick_parent'],
            'activate_stop_loss_profit_percentage' => (float)$post_data['activate_stop_loss_profit_percentage'],
        ];
                        
        $id = $post_data['_id'];
        $exchange = $post_data['exchange'];
    
        if ($exchange == 'bam') {
            $coll = 'buy_orders_bam';
        } elseif($exchange == 'binance') {
            $coll = 'buy_orders';
        }elseif($exchange == 'kraken') {
            $coll = 'buy_orders_kraken';
        }
        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($set_arr);
        $query1 = $this->mongo_db->update($coll);
        if ($query1) {
            $this->session->set_flashdata('query_message', "Your selected parent array record successfully updated Total effected records count = " . $query1);
        } else {
            $this->session->set_flashdata('error_message', 'not updated something wrrong try!');
        }
        redirect(SURL . "admin/buy_orders/get_parent_order");
    } 

    ////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////                    GET SOLD ORDER                  ///////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////

    public function get_sold(){
        $this->mod_login->verify_is_admin_login();
        $this->stencil->paint('admin/buy_order/get_sold_orders');
    }

    public function get_sold_ajax(){ 
        $this->mod_login->verify_is_admin_login();   
        $detail = $this->input->post();
        $parent_id = $detail['orderid'];
        $exchange  = $detail['exchange'];
        $check = $this->mongo_db->isValid((string)$parent_id);
        if(!$check){
            $html_error = '
                <div class="blink_me" align="center" style="background-color:#e8afaf;" >
                <label style="margin-top:13px;font-size: 25px;">Inavlid id.</label>
                </div>
                ';
     
              echo $html_error;
            exit;
        }
        if($exchange !="" && $parent_id !=""){
            $this->session->set_userdata('exchange1', $exchange);
            $this->session->set_userdata('parent_id1',$parent_id);
            $parent = $this->get_sold_array($exchange, $parent_id);
        }
        if(empty($parent)){

                  $html_error = '
        <div class="blink_me" align="center" style="background-color:#e8afaf;" >
            <label style="margin-top:13px;font-size: 25px;">No Order found please look into another collection</label>
        </div>
        ';
            echo $html_error;
            exit;
        }
        $parent_order1 = $parent['sold_order'];
        $html = '<form action="' . SURL . 'admin/buy_orders/update_sold_order" method="post">';
        foreach ($parent_order1 as $attr => $value) {
            $in_arr = ['cost_avg', 'show_order', 'custom_stop_loss_percentage','sell_profit_percent','lth_profit','defined_sell_percentage','trading_status','status'  ,'buy_trail_price', 'sell_price', 'purchased_price', 'quantity', 'is_sell_order', 'market_sold_price', 'order_level','avg_sell_price','avg_sell_price_three','quantity_all'];
            if (in_array($attr, $in_arr)) {
                if (gettype($value) == 'string'){
                    $html .= '
                    <div class="form-group">
                        <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                        <input type="text" class="form-control" name = "' . $attr . '" id="' . $attr . '" value = "' . $value . '">
                    </div>
                    ';
                }elseif (gettype($value) == 'float' || gettype($value) == 'double' || gettype($value) == 'integer' || $attr != 'trigger_type' || $attr != 'symbol') {
                    $html .= '
                    <div class="form-group">
                        <label for="' . $attr . '">' . str_replace("_", " ", $attr) . ':</label>
                        <input type="text" class="form-control" name = "' . $attr . '" id="' . $attr . '" value = "' . $value . '" >
                    </div>
                    ';
                }
            }
        } //end foreach loop 

        // $html .= '
        // <label for="market_sold_price">Market Sold Price:</label>
        //     <div class="form-group">
        //         <input type="text" class="form-control" name = "market_sold_price" id="market_sold_price" placeholder = "market_sold_price">
        //     </div>
        //     ';
        $html .= '
        <div class="form-group">
            <label for="sell_date">Sell Date:</label>
            <input type="datetime-local" class="form-control" name = "sell_date" id="sell_date" >
        </div>
        ';

        $html .= '
        <div class="form-group">
            <label for="buy_date">Buy Date:</label>
            <input type="datetime-local" class="form-control" name = "buy_date" id="buy_date" >
        </div>
        ';

        $html .= '
            <div class="form-group">
                <input type="hidden" class="form-control" name = "exchange" id="exchange" value = "' . $parent_order1['exchange'] . '">
            </div>
            ';
        $html .= '
            <div class="form-group">
                <input type="hidden" class="form-control" name = "_id" id="_id" value = "' . $parent_order1['_id'] . '">
            </div>
            ';
        $html .= '<hr class="separator">
        <!-- Form actions -->
        <div class="form-actions">
        <button class="btn btn-success" id="add_order_p2" type"submit"><i class="fa fa-check-circle"></i> Update Sold Order </button>
        </div>
        <!-- // Form actions END -->
        </form>';
        echo $html;
        exit;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////
    //////////////////            GET SOLD ORDER DETAIL             ///////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////

    public function get_sold_array($exchange, $parent_id){
        $this->mod_login->verify_is_admin_login();
        if($exchange == 'bam'){
            $collection = 'sold_buy_orders_bam';
        }
        elseif($exchange == 'binance'){
            $collection= 'sold_buy_orders';
        } elseif($exchange == 'kraken'){
            $collection= 'sold_buy_orders_kraken';
        }
        $search_arr['_id'] = $this->mongo_db->mongoId((string)$parent_id);
        $this->mongo_db->where($search_arr);
        $object = $this->mongo_db->get($collection);

        $object = iterator_to_array($object);
        if(empty($object)){
            return $object;
        }
      
        $object[0]['exchange'] = $exchange;
        $returnn  = ['sold_order' => $object[0]];
        return $returnn;
    }

    ////////////////////////////////////////////////////////////////////////////////////////
    //////////          UPDATE SOLD ORDER           ///////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    public function update_sold_order(){
        $this->mod_login->verify_is_admin_login();
        $post_data = $this->input->post();
        if($post_data['sell_date']){
            $date = $post_data['sell_date'];
            $date_time =  $this->mongo_db->converToMongodttime($date);
        }
        if($post_data['buy_date']){
            $buy_date = $post_data['buy_date'];
            $buy_date_time =  $this->mongo_db->converToMongodttime($buy_date);
        }
        $set_arr = [
            'status'                    => $post_data['status'],
            'trading_status'            => $post_data['trading_status'],
            'buy_trail_price'           => (float)$post_data['buy_trail_price'],
            'sell_price'                => (float)$post_data['sell_price'],
            'purchased_price'           => (float)$post_data['purchased_price'],
            'is_sell_order'             => $post_data['is_sell_order'],
            'market_sold_price'         => (float)$post_data['market_sold_price'],
            'trading_status'            => $post_data['trading_status'],
            'order_level'               => $post_data['order_level'],
            'lth_profit'                => (float)$post_data['lth_profit'],
            'defined_sell_percentage'   => (float)$post_data['defined_sell_percentage'],
            'sell_profit_percent'       => (float)$post_data['sell_profit_percent'],
            'quantity'                  => (float)$post_data['quantity'],
            'mapped_order'              => true,
            'cost_avg'                  => (string)$post_data['cost_avg'],
            'custom_stop_loss_percentage'=>(float)$post_data['custom_stop_loss_percentage'],
            'avg_sell_price'=>(float)$post_data['avg_sell_price'],
            'avg_sell_price_three'=>(float)$post_data['avg_sell_price_three'],
            'quantity_all'=>(float)$post_data['quantity_all'],
            'show_order'                => $post_data['show_order'],
            //  'lth_functionality'        => 'yes',
            // 'csl_sold' =>'yes',
        ];
        if($buy_date_time > 0){
            $set_arr['buy_date'] = $buy_date_time;
        } 
        if($date_time > 0){
            $set_arr['sell_date'] = $date_time;
        }           
        $id = $post_data['_id'];
        $exchange = $post_data['exchange'];
        if ($exchange == 'bam') {
            $coll = 'sold_buy_orders_bam';
        } elseif($exchange == 'binance'){
            $coll = 'sold_buy_orders';
        }elseif($exchange == 'kraken'){
            $coll = 'sold_buy_orders_kraken';
        }
        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($set_arr);
        $query1 = $this->mongo_db->update($coll);
        if ($query1) {
            $this->session->set_flashdata('query_message_1', "Your selected parent array record successfully updated Total effected records count = " . $query1);
        } else {
            $this->session->set_flashdata('error_message_1', 'not updated something wrrong try!');
        }
        redirect(SURL . "admin/buy_orders/get_sold");
    } 

    public function create_child_view(){
        $this->mod_login->verify_is_admin_login();
        $coin_array_all = $this->mod_coins->get_all_coins();
        $data['coins'] = $coin_array_all;
        $this->stencil->paint('admin/buy_order/create_child', $data);
    }

    public function create_child_process(){
        print_r($this->input->post());
        if(!empty($this->input->post('userid')) && !empty($this->input->post('purchase_price')) && $this->input->post('orderType')){
            $purchase_price  = (float)$this->input->post('purchase_price');
            $userid          = (string)$this->input->post('userid');
            $orderId = (string)$this->input->post('order_id');
            $trading_ip      = $this->input->post('trading_ip');

            $bot_level = $this->input->post('filter_by_level');
            $quantity  = (float)$this->input->post('quantity');
            $symbol    = $this->input->post('filter_by_coin');
            $exchange = $this->input->post('exchange');
            $price_search['coin'] = $symbol;
            $this->mongo_db->where($price_search);
            if($this->input->post('exchange') == 'binance'){
                $priceses = $this->mongo_db->get('market_prices');
                $buy_array_collection = 'buy_orders';
                $sell_array_collection = 'orders';
            }elseif($this->input->post('exchange') == 'bam'){
                $priceses = $this->mongo_db->get('market_prices_bam');
                $buy_array_collection = 'buy_orders_bam';
                $sell_array_collection = 'orders_bam';
            }elseif($this->input->post('exchange') == 'kraken'){
                $priceses = $this->mongo_db->get('market_prices_kraken');
                $buy_array_collection = 'buy_orders_kraken';
                $sell_array_collection = 'orders_kraken';
            }elseif($this->input->post('exchange') == 'dg'){
                $priceses = $this->mongo_db->get('market_prices_dg');
                $buy_array_collection = 'buy_orders_dg';
                $sell_array_collection = 'orders_dg';
            }elseif($this->input->post('exchange') == 'okex'){
                $priceses = $this->mongo_db->get('market_prices_okex');
                $buy_array_collection = 'buy_orders_okex';
                $sell_array_collection = 'orders_okex';
            }
            $market_prices  = iterator_to_array($priceses);
            $current_market = $market_prices[0]['price'];
            $sell_price = (float)((($purchase_price / 100)* 1.2) + $purchase_price);
            $iniatial_trail_stop = (float)($purchase_price - (($purchase_price / 100)*1.2));
            $current_datetime = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));
            $buy_array = [
                'price'                        => $purchase_price,
                'quantity'                     => $quantity,   
                'symbol'                       => $symbol,
                'trading_ip'                   => $trading_ip,
                'admin_id'                     => $userid,
                'sell_price'                   => (float)$sell_price,
                'lth_functionality'            => 'yes',
                'activate_stop_loss_profit_percentage' => (float)1.2,
                'lth_profit'                   => (float)1.2,
                'stop_loss_rule'               => 'custom_stop_loss',
                'created_date'                 => $current_datetime,
                'modified_date'                => $current_datetime,
                'application_mode'             => 'live',
                'order_mode'                   => 'live', 
                'created_buy'                  => 'manul_report',
                'market_value'                 => (float)$current_market,
                'iniatial_trail_stop'          => $iniatial_trail_stop,
                'defined_sell_percentage'      => (float)1.2,
                'sell_profit_percent'          => (float)1.2,
                'is_sell_order'                => 'yes', 
                'auto_sell'                    => 'yes',
                'purchased_price'              =>  $purchase_price,
                'status'                       =>  'FILLED',
                'sell_order_id'                => '',
                'exchange'                     =>  $this->input->post('exchange'),
                'buy_fraction_filled_order_arr' => [
                    'filledPrice'           => $purchase_price,
                    'commission'      => '0',
                    'commissionPercentRatio' => '0',
                    'orderFilledId'   => $orderId,
                    'filledQty'       => $quantity,
                ],
            ];
            if($this->input->post('exchange') == 'kraken'){
                $buy_array['kraken_order_id'] = $orderId;
                $buy_array['tradeId']         = $orderId;
            }elseif($this->input->post('exchange') == 'binance'){
                $buy_array['binance_order_id'] = $orderId;
                $buy_array['tradeId']          = $orderId;
            }elseif($this->input->post('exchange') == 'dg'){
                $buy_array['dg_order_id'] = $orderId;
                $buy_array['tradeId']          = $orderId;
            }elseif($this->input->post('exchange') == 'okex'){
                $buy_array['okex_order_id'] = $orderId;
                $buy_array['tradeId']          = $orderId;
            }

            if($this->input->post('orderType') == 'manul'){
                $buy_array['trigger_type']      =  'no';
            }else{
                $buy_array['trigger_type']      = 'barrier_percentile_trigger';
                $buy_array['order_level']       = $bot_level; 
            }
            $sell_array = [
                'symbol'                       => $symbol,
                'quantity'                     => $quantity,  
                'market_value'                 => (float)$current_market,
                'sell_price'                   => (float)$sell_price, 
                'lth_functionality'            => 'yes',
                'lth_profit'                   => (float)1.2,
                'activate_stop_loss_profit_percentage' => (float)1.2,
                'stop_loss_rule'               => 'custom_stop_loss',
                'order_type'                   => 'market_order',
                'admin_id'                     => $userid,
                'trading_ip'                   => $trading_ip,
                'created_date'                 =>  $current_datetime,
                'modified_date'                =>  $current_datetime,
                'application_mode'             => 'live',
                'buy_order_id'                 => '',
                'created_buy'                  => 'manul_report',
                'iniatial_trail_stop'          => $iniatial_trail_stop,
                'order_mode'                   => 'live',
                'sell_profit_percent'          => (float)1.2,
                'status'                       => 'new',
                'purchased_price'              => $purchase_price,
                'defined_sell_percentage'      => (float)1.2
            ];

            if($this->input->post('orderType') == 'manul'){
                $sell_array['trigger_type']      =  'no';
            }else{
                $sell_array['trigger_type']      = 'barrier_percentile_trigger';
                $sell_array['order_level']       = $bot_level; 
            }

            $db = $this->mongo_db->customQuery();
            $buy_return  = $db->$buy_array_collection->insertOne($buy_array); // insert buy array
            $sell_return = $db->$sell_array_collection->insertOne($sell_array); // insert sell array
            $sell_set =[
                'buy_order_id' =>$buy_return->getInsertedId()
            ];
            $buy_set =[
                'sell_order_id' => $sell_return->getInsertedId()
            ];
            $where_buy['_id']      = $sell_return->getInsertedId();
            $where_buy['admin_id'] = $userid;
            $res = $db->$sell_array_collection->updateOne($where_buy, ['$set'=> $sell_set]);

            $where_sell['_id']      = $buy_return->getInsertedId();
            $where_sell['admin_id'] = $userid;
            $res = $db->$buy_array_collection->updateOne($where_sell, ['$set'=> $buy_set]);

            $id = (string)$buy_return->getInsertedId();
            $exchange = $this->input->post('exchange');
            $date = date('Y-m-d G:i:s');

            $insert_log_array = array(
                'order_id'      => $id,
                'created_date'  => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                'log_msg'       => 'Order is created by admin for migration Process',
                'type'          => 'edit_manul_interface',
                'show_hide_log' => 'yes'
            );

            
            if ($exchange == 'binance') {
                $collection1 = "orders_history_log_live_" . date('Y') . "_" . date('m', strtotime('-1 month'));
            } elseif($exchange == 'bam') {
                $collection1 = "orders_history_log_" . $exchange . "_live_" . date('Y') . "_" . date('m', strtotime('-1 month'));
            } elseif($exchange == 'kraken') {
                $collection1 = "orders_history_log_" . $exchange . "_live_" . date('Y') . "_" . date('m', strtotime('-1 month'));
            } elseif($exchange == 'dg') {
                $collection1 = "orders_history_log_" . $exchange . "_live_" . date('Y') . "_" . date('m', strtotime('-1 month'));
            } elseif($exchange == 'okex') {
                $collection1 = "orders_history_log_" . $exchange . "_live_" . date('Y') . "_" . date('m', strtotime('-1 month'));
            }
        
            if ( !empty($exchange) ) {
                $this->mongo_db->insert($collection1, $insert_log_array);
            }

            return true;
        }else{
            return false;
        }
    }//end function

        ///////////////////////////////////////////////////////////////////////////////////////
    //////////          Update Order P/L Up Down           /////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    public function PercentageUpDown(){
        $this->mod_login->verify_is_admin_login();
        $coin_array_all = $this->mod_coins->get_all_coins();
        $data['coins'] = $coin_array_all;
        $this->stencil->paint('admin/buy_order/percentageChange', $data);
    }
        ///////////////////////////////////////////////////////////////////////////////////////
    //////////          Update Order P/L Up Down   update function         /////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    public function PercentageUpDownUpdate(){
        $this->mod_login->verify_is_admin_login();
        if($this->input->post()){
            $filterData['userPostData'] = $this->input->post();
            $this->session->set_userdata($filterData);
            $chnagePercentage = $this->input->post('percentageChange');
            $percentageUpDown = $this->input->post('percentageUpDown');
            $exchange = $this->input->post('exchange');
            $searchOrder['_id'] = $this->mongo_db->mongoId($this->input->post('order_id'));
        
            $buyCollecton = $exchange == 'binance'? 'buy_orders': 'buy_orders_'.$exchange;
            $sellCollecton = $exchange == 'binance'? 'orders': 'orders_'.$exchange;

            $db = $this->mongo_db->customQuery();
            $order = $db->$buyCollecton->find($searchOrder);
            $returnBuy = iterator_to_array($order);
            $purchased_price = $returnBuy[0]['purchased_price'];
            if(count($returnBuy) > 0){
                if($percentageUpDown == 'positive'){
                    $newPurchasedPrice = (float)$purchased_price - (($chnagePercentage/ 100) * $purchased_price);
                }else{
                    $newPurchasedPrice = (float)(($chnagePercentage/ 100) * $purchased_price) + $purchased_price;   
                }
                $updateValue = [
                    'purchased_price'  => (float)$newPurchasedPrice
                ];

                if($returnBuy[0]['stop_loss'] == 'yes'){
                    $CSLPercentage = $returnBuy[0]['loss_percentage'];
                    if($purchased_price > $returnBuy[0]['iniatial_trail_stop']){

                        $newInitialTrailPrice = (float)$purchased_price - (($CSLPercentage/ 100) * $purchased_price);
                        $updateValue['iniatial_trail_stop'] = (float)$newInitialTrailPrice;

                    }elseif($purchased_price < $returnBuy[0]['iniatial_trail_stop']){

                        $newInitialTrailPrice = (float)$purchased_price + (($CSLPercentage/ 100) * $purchased_price);
                        $updateValue['iniatial_trail_stop'] = (float)$newInitialTrailPrice;

                    }else{
                        $updateValue['iniatial_trail_stop'] = (float)$returnBuy[0]['iniatial_trail_stop'];
                    }
                }
                
                $searchOrderSell['_id'] = $this->mongo_db->mongoId((string)$returnBuy[0]['sell_order_id']);

                // buy array purchase price changed
                $db->$buyCollecton->updateOne($searchOrder, ['$set' => $updateValue]);
                // Sell array purchase price changed
                $db->$sellCollecton->updateOne($searchOrderSell, ['$set' => $updateValue]);
                $this->session->set_flashdata('sucessMessage', 'Record Suceesfully updated');
            }else{
                $this->session->set_flashdata('error', 'Record Not Found Try Again!!');
            }
        }
        $this->PercentageUpDown();
    }//end function 

    // public function change_status_from_apierror_to_filled_kraken(){
    //             $exchange = 'kraken';
    //             $collection_name = "buy_orders_kraken";
    //              $db = $this->mongo_db->customQuery();
    //             $searchOrder['_id'] = $this->mongo_db->mongoId($this->input->post('order_id'));
    //             $modified_date  = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s' ,strtotime('2021-10-18')));
    //             $pipeline = [
    //                           'modified_date'=>['$gte'=>$modified_date],
    //                           'status'=>['$in'=>['APIKEY_ERROR']],
    //                           'transaction_logs'=>['$exists'=>true]
    //                         ];  
    //             // $result_orders = $db->$collection_name->aggregate($pipeline);
    //             // $final_id_arr = iterator_to_array($result_orders);
    //             // $ids_array = array();
    //             $array_updated = array('status'=>'FILLED');
    //             $count = $db->$collection_name->updateMany($pipeline, ['$set' => $array_updated]);
            
                    
               
                
    //             echo '<pre>'; print_r($count);    
    // }
}//En of controller