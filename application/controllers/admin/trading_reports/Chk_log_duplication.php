<?php
/**
 *
 */
class Chk_log_duplication extends CI_Controller {

    function __construct() {

        parent::__construct();
        //load main template
        ini_set("memory_limit", -1);

        // ini_set("display_errors", E_ALL);
        // error_reporting(E_ALL);
        
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
        $this->load->model('admin/mod_cronjob_listing');
        
        // if ($this->session->userdata('user_role') != 1) {
        //     redirect(base_url() . 'forbidden');
        // }


        // if ($this->session->userdata('special_role') != 1) {
        //     redirect(base_url() . 'forbidden');
        // }

    }

    // duplicate_orders 
    // TODO: Get weekly duplicate buy_submitted or sell_submitted orders from orders_history_log
    public function duplicate_orders(){

        // set_time_limit(15);

        $request = $_GET;

        if(empty($request['st_dt']) && empty($request['nd_dt']) && empty($request['limit'])){
            die("Enter all required filters.");
        }

        // Convert string YYYY_MM_DD__hh_mm_ss to array(YYYY-MM-DD, hh-mm-ss);
        $rs_dt = explode(" ", str_replace("_", "-", str_replace("__", " ", $request['st_dt'])));
        $rn_dt = explode(" ", str_replace("_", "-", str_replace("__", " ", $request['nd_dt'])));

        $rs_d = $rs_dt[0];
        $rs_t = str_replace("-", ":", $rs_dt[1]); 
        $rn_d = $rn_dt[0];
        $rn_t = str_replace("-", ":", $rn_dt[1]);
        
        // Convert to mongo time
        $st = new DateTime(date($rs_d)." ".$rs_t);
        $nd = new DateTime(date($rn_d)." ".$rn_t);
        $t_st = $st->format('U') * 1000;
        $t_nd = $nd->format('U') * 1000;

        //Set parameters
        $mongo_sdt = new MongoDB\BSON\UTCDateTime($t_st);
        $mongo_ndt = new MongoDB\BSON\UTCDateTime($t_nd);
        $status_arr = array("buy_submitted", "sell_submitted");
        $limit = (int)$request['limit'];
        $page = (!empty($request['page']) ? $request['page'] : 0);
        $skip = (!empty($page) ? (int)$page*$limit : 0);

        $pipeline = array(
            array(
                '$project' => array(
                    "type" => 1,
                    "count" => 1,
                    "log_msg" => 1,
                    'created_date' => 1,
                    'order_id' => 1,
                ),
            ),
    
            array(
                '$match' => array(
                    'type' => array('$in' => $status_arr),
                    'created_date' => array('$gte' => $mongo_sdt, '$lte' => $mongo_ndt)
                ),
            ),
            array('$sort' => array('created_date' => -1)),
            array('$skip' => $skip),
            array('$limit' => $limit),
            
        );
    
        $allow = array('allowDiskUse' => true);
        $db = $this->mongo_db->customQuery();

        $response = $db->orders_history_log->aggregate($pipeline, $allow);
        $records = iterator_to_array($response);

        // print_r($records);die("*************** End RECORDS ***************");

        $res_arr = $this->unique_multidim_array($records, 'order_id');
        $temp = $res_arr;
        foreach($temp as $key=>$a){
            if($a['type'] == 'buy_submitted' && $a['type'] < 2){
                unset($res_arr[$key]);
            }
        }

        echo '<pre>';
        print_r("total duplicates found: ".count($res_arr)."\r\n");
        print_r($res_arr); die();

        die('<br>**************** End Script ****************');

    }// end duplicate_orders

    //duplicate_orders_report
    public function duplicate_orders_report($reset=null) {
        //Login Check
        $this->mod_login->verify_is_admin_login();
        if ($this->input->post()) {
            $data_arr['u_duplicate_orders_report'] = $this->input->post();
            $this->session->set_userdata($data_arr);
        }

        if(!empty($reset)){
            if($reset == 'reset'){
               $this->reset_report_filters('u_duplicate_orders_report'); 
            }
            redirect(SURL."admin/trading_reports/chk_log_duplication/duplicate_orders_report");
        }else{
        
            $session_data = $this->session->userdata('u_duplicate_orders_report');
            if (isset($session_data)) {

                $collection = "buy_orders";
                if ($session_data['filter_by_coin']) {
                    $search['symbol'] = $session_data['filter_by_coin'];
                }
                if ($session_data['filter_by_mode']) {
                    $search['application_mode'] = $session_data['filter_by_mode'];
                }
                if ($session_data['filter_by_trigger']) {
                    $search['trigger_type'] = $session_data['filter_by_trigger'];
                }
                if ($session_data['filter_by_rule'] != "") {
                    $filter_by_rule = $session_data['filter_by_rule'];
                    $search['$or'] = array(
                        array("buy_rule_number" => intval($filter_by_rule)),
                        array("sell_rule_number" => intval($filter_by_rule)),
                    );

                }
                if ($session_data['filter_level'] != "") {
                    $order_level = $session_data['filter_level'];
                    $search['order_level'] = $order_level;
                }
                if ($session_data['filter_username'] != "") {
                    $username = $session_data['filter_username'];
                    $admin_id = $this->get_admin_id($username);
                    $search['admin_id'] = (string) $admin_id;
                }
                if ($session_data['optradio'] != "") {
                    if ($session_data['optradio'] == 'created_date') {
                        if ($session_data['selector'] == 'ASC') {
                            $oder_arr['created_date'] = 1;
                        } else {
                            $oder_arr['created_date'] = -1;
                        }

                    } elseif ($session_data['optradio'] == 'modified_date') {
                        if ($session_data['selector'] == 'ASC') {
                            $oder_arr['modified_date'] = 1;
                        } else {
                            $oder_arr['modified_date'] = -1;
                        }

                    }
                }
                if ($session_data['filter_by_status'] != "") {
                    $order_status = $session_data['filter_by_status'];
                    if ($order_status == 'new') {
                        $search['status'] = 'new';
                    } elseif ($order_status == 'error') {
                        $search['status'] = 'error';
                    } elseif ($order_status == 'open') {
                        $search['status'] = array('$in' => array('submitted', 'FILLED'));
                        $search['is_sell_order'] = 'yes';
                    } elseif ($order_status == 'sold') {
                        $search['status'] = 'FILLED';
                        $search['is_sell_order'] = 'sold';
                        $collection = "sold_buy_orders";
                    }elseif ($order_status == 'LTH') {
                        $search['status'] = 'LTH';
                        
                        
                    }
                }
                if ($session_data['filter_by_start_date'] != "" && $session_data['filter_by_end_date'] != "") {

                    $created_datetime = date('Y-m-d G:i:s', strtotime($session_data['filter_by_start_date']));
                    $orig_date = new DateTime($created_datetime);
                    $orig_date = $orig_date->getTimestamp();
                    $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

                    $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_data['filter_by_end_date']));
                    $orig_date22 = new DateTime($created_datetime22);
                    $orig_date22 = $orig_date22->getTimestamp();
                    $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
                    $search['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
                }

                $pipeline = array(
                    array(
                        '$project' => array(
                            "type" => 1,
                            "count" => 1,
                            "log_msg" => 1,
                            'created_date' => 1,
                            'order_id' => 1,
                        ),
                    ),
                    array(
                        '$match' => array(
                            'type' => array('$in' => array("buy_submitted", "sell_submitted")),
                        ),
                    ),
                );
                if(!empty($search['created_date'])){

                    $pipeline[1]['$match']['created_date'] = $search['created_date'];
                }

                $allow = array('allowDiskUse' => true);
                $db = $this->mongo_db->customQuery();
                $response = $db->orders_history_log->aggregate($pipeline, $allow);
                $records = iterator_to_array($response);

                // echo '<pre>';
                // print_r($records[0]);die();

                if(!empty($records)){
                    
                    $order_ids = [];
                    $res_arr = $this->unique_multidim_array($records, 'order_id');
                    $temp = $res_arr;
                    foreach($temp as $key=>$a){
                        if($a['type'] == 'buy_submitted' && $a['type'] < 2){
                            unset($res_arr[$key]);
                        }else{
                            $order_ids[] =  $a['order_id'];
                        }
                    }

                    $search['_id'] = array('$in' => $order_ids);
                }else{
                    //If no duplicate found the reset filters and redirect
                    $this->reset_report_filters('u_duplicate_orders_report');
                    redirect(SURL.'admin/trading_reports/chk_log_duplication/duplicate_orders_report');
                }

                // print_r($search);die('end');

                $search['parent_status'] = array('$ne' => 'parent');

                $connetct = $this->mongo_db->customQuery();

                $sold1_count = $connetct->sold_buy_orders->count($search);
                $pending1_count = $connetct->buy_orders->count($search);

                $total1_count = $sold1_count + $pending1_count;

                $qr_sold = array('skip' => $skip_sold, 'sort' => $oder_arr, 'limit' => $limit);
                $qr_pending = array('skip' => $skip_pending, 'sort' => $oder_arr, 'limit' => $limit);

                $sold_count = $connetct->sold_buy_orders->count($search, $qr_sold);
                $pending_count = $connetct->buy_orders->count($search, $qr_pending);

                $total_count = $sold_count + $pending_count;

                /////////////////////// PAGINATION CODE START HERE /////////////////////////////////////
                $this->load->library("pagination");
                $config = array();
                $config["base_url"] = SURL . "admin/trading_reports/chk_log_duplication/duplicate_orders_report";
                $config["total_rows"] = $total1_count;
                $config['per_page'] = 250;
                $config['num_links'] = 5;
                $config['use_page_numbers'] = TRUE;
                $config['uri_segment'] = 4;
                $config['reuse_query_string'] = TRUE;
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
                $this->pagination->initialize($config);
                $page = $this->uri->segment(5);

                if (!isset($page)) {$page = 1;}
                $start = ($page - 1) * $config["per_page"];
                $skip = $start;
                $skip_sold = $skip;
                $skip_pending = $skip;
                $limit = $config["per_page"];
                ////////////////////////////End Pagination Code///////////////////////////////////////

                $data['pagination'] = $this->pagination->create_links();

                /////////////////////// PAGINATION CODE END HERE /////////////////////////////////////
                $sold_percentage = ($sold_count / $total_count) * 100;
                $pending_percentage = ($pending_count / $total_count) * 100;

                $pending_limit = (500 / 100) * $pending_percentage;
                $sold_limit = (500 / 100) * $sold_percentage;

                
                $pending_options = array('skip' => abs($skip_pending), 'sort' => array('modified_date' => -1), 'limit' => abs(intval($pending_limit)));
                
                $sold_options = array('skip' => abs($skip_sold), 'sort' => array('modified_date' => -1), 'limit' => abs(intval($sold_limit)));


                $pending_curser = $connetct->buy_orders->find($search, $pending_options);
                $sold_curser = $connetct->sold_buy_orders->find($search, $sold_options);

                $pending_arr = iterator_to_array($pending_curser);
                $sold_arr = iterator_to_array($sold_curser);
                $orders = array_merge_recursive($pending_arr, $sold_arr);

                if($this->input->post('optradio') != ""){

                    if($this->input->post('optradio') == 'created_date'){

                        if($this->input->post('selector') == 'ASC'){

                            foreach($orders as $key => $part) {
                                $sort[$key] = (string) $part['created_date'];
                            }
                            array_multisort($sort, SORT_ASC, $orders);

                        }else{

                            foreach($orders as $key => $part) {
                                $sort[$key] = (string) $part['created_date'];
                                
                            }
                            array_multisort($sort, SORT_DESC, $orders);
                        }

                    }elseif($this->input->post('optradio') == 'modified_date'){

                        if($this->input->post('selector') == 'ASC'){
                            foreach ($orders as $key => $part) {
                                $sort[$key] = (string) $part['modified_date'];
                            }
                            array_multisort($sort, SORT_ASC, $orders);
                        }else{
                            foreach ($orders as $key => $part) {
                                $sort[$key] = (string) $part['modified_date'];
                            }
                            array_multisort($sort, SORT_DESC, $orders);
                        }
                        
                    }
                }else{
                    foreach ($orders as $key => $part) {
                        $sort[$key] = (string) $part['modified_date'];
                    }
                    array_multisort($sort, SORT_DESC, $orders);
                }

                $new_order_arrray = array();
                foreach ($orders as $order) {
                    $id = $order['admin_id'];
                    $data_user = $this->get_username_from_user($id);

                    $order['admin'] = $data_user;
                    $_id = $order['_id'];

                    $error = $this->get_error_type($_id);
                    $order['log'] = $error;
                    array_push($new_order_arrray, $order);
                }
                $data['orders'] = $new_order_arrray;

            }
            
            $coins = $this->mod_coins->get_all_coins();
            $data['coins'] = $coins;
            $this->stencil->paint('admin/trading_reports/duplicate_orders_report', $data);
        }
    } //End of duplicate_orders_report
    
    //repeating_orders
    public function repeating_orders($reset=null) {
        //Login Check
        $this->mod_login->verify_is_admin_login();
        if ($this->input->post()) {
            $data_arr['u_repeating_orders'] = $this->input->post();
            $this->session->set_userdata($data_arr);
        }

        if(!empty($reset)){
            if($reset == 'reset'){
               $this->reset_report_filters('u_repeating_orders'); 
            }
            redirect(SURL."admin/trading_reports/chk_log_duplication/repeating_orders");
        }else{
        
            $session_data = $this->session->userdata('u_repeating_orders');
            if (isset($session_data)) {

                $collection = "buy_orders";
                if ($session_data['filter_by_coin']) {
                    $search['symbol'] = $session_data['filter_by_coin'];
                }
                if ($session_data['filter_by_mode']) {
                    $search['application_mode'] = $session_data['filter_by_mode'];
                }
                if ($session_data['filter_by_trigger']) {
                    $search['trigger_type'] = $session_data['filter_by_trigger'];
                }
                if ($session_data['filter_by_rule'] != "") {
                    $filter_by_rule = $session_data['filter_by_rule'];
                    $search['$or'] = array(
                        array("buy_rule_number" => intval($filter_by_rule)),
                        array("sell_rule_number" => intval($filter_by_rule)),
                    );

                }
                if ($session_data['filter_level'] != "") {
                    $order_level = $session_data['filter_level'];
                    $search['order_level'] = $order_level;
                }
                if ($session_data['filter_username'] != "") {
                    $username = $session_data['filter_username'];
                    $admin_id = $this->get_admin_id($username);
                    $search['admin_id'] = (string) $admin_id;
                }
                if ($session_data['optradio'] != "") {
                    if ($session_data['optradio'] == 'created_date') {
                        if ($session_data['selector'] == 'ASC') {
                            $oder_arr['created_date'] = 1;
                        } else {
                            $oder_arr['created_date'] = -1;
                        }

                    } elseif ($session_data['optradio'] == 'modified_date') {
                        if ($session_data['selector'] == 'ASC') {
                            $oder_arr['modified_date'] = 1;
                        } else {
                            $oder_arr['modified_date'] = -1;
                        }

                    }
                }
                if ($session_data['filter_by_status'] != "") {
                    $order_status = $session_data['filter_by_status'];
                    if ($order_status == 'new') {
                        $search['status'] = 'new';
                    } elseif ($order_status == 'error') {
                        $search['status'] = 'error';
                    } elseif ($order_status == 'open') {
                        $search['status'] = array('$in' => array('submitted', 'FILLED'));
                        $search['is_sell_order'] = 'yes';
                    } elseif ($order_status == 'sold') {
                        $search['status'] = 'FILLED';
                        $search['is_sell_order'] = 'sold';
                        $collection = "sold_buy_orders";
                    }elseif ($order_status == 'LTH') {
                        $search['status'] = 'LTH';
                        
                        
                    }
                }
                if ($session_data['filter_by_start_date'] != "" && $session_data['filter_by_end_date'] != "") {

                    $created_datetime = date('Y-m-d G:i:s', strtotime($session_data['filter_by_start_date']));
                    $orig_date = new DateTime($created_datetime);
                    $orig_date = $orig_date->getTimestamp();
                    $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

                    $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_data['filter_by_end_date']));
                    $orig_date22 = new DateTime($created_datetime22);
                    $orig_date22 = $orig_date22->getTimestamp();
                    $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
                    $search['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
                }

                $pipeline = array(
                    array(
                        '$project' => array(
                            "buy_parent_id" => 1,
                            "count" => 1,
                            'created_date' => 1,
                        ),
                    ),
                    array(
                        '$match' => array(
                            'created_date' => $search['created_date'],
                        ),
                    ),
                );

                $allow = array('allowDiskUse' => true);
                $db = $this->mongo_db->customQuery();
                $response = $db->buy_orders->aggregate($pipeline, $allow);
                $records = iterator_to_array($response);

                // echo '<pre>';
                // print_r($records[0]);die('end');

                if(!empty($records)){
                    
                    $order_ids = $this->repeating_orders_array($records, 'buy_parent_id');
                    
                    if(!empty($order_ids)){
                        $search['_id'] = array('$in' => $order_ids);
                    }else{

                        //If no repeating orders found the reset filters and redirect
                        $this->reset_report_filters('u_repeating_orders');
                        redirect(SURL.'admin/trading_reports/chk_log_duplication/repeating_orders');
                    }
                }else{
                    //If no repeating orders found the reset filters and redirect
                    $this->reset_report_filters('u_repeating_orders');
                    redirect(SURL.'admin/trading_reports/chk_log_duplication/repeating_orders');
                }

                // print_r($search);die('end');

                $search['parent_status'] = array('$ne' => 'parent');

                $connetct = $this->mongo_db->customQuery();

                $sold1_count = $connetct->sold_buy_orders->count($search);
                $pending1_count = $connetct->buy_orders->count($search);

                $total1_count = $sold1_count + $pending1_count;

                $qr_sold = array('skip' => $skip_sold, 'sort' => $oder_arr, 'limit' => $limit);
                $qr_pending = array('skip' => $skip_pending, 'sort' => $oder_arr, 'limit' => $limit);

                $sold_count = $connetct->sold_buy_orders->count($search, $qr_sold);
                $pending_count = $connetct->buy_orders->count($search, $qr_pending);

                $total_count = $sold_count + $pending_count;

                /////////////////////// PAGINATION CODE START HERE /////////////////////////////////////
                $this->load->library("pagination");
                $config = array();
                $config["base_url"] = SURL . "admin/trading_reports/chk_log_duplication/repeating_orders";
                $config["total_rows"] = $total1_count;
                $config['per_page'] = 250;
                $config['num_links'] = 5;
                $config['use_page_numbers'] = TRUE;
                $config['uri_segment'] = 4;
                $config['reuse_query_string'] = TRUE;
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
                $this->pagination->initialize($config);
                $page = $this->uri->segment(5);

                if (!isset($page)) {$page = 1;}
                $start = ($page - 1) * $config["per_page"];
                $skip = $start;
                $skip_sold = $skip;
                $skip_pending = $skip;
                $limit = $config["per_page"];
                ////////////////////////////End Pagination Code///////////////////////////////////////

                $data['pagination'] = $this->pagination->create_links();

                /////////////////////// PAGINATION CODE END HERE /////////////////////////////////////
                $sold_percentage = ($sold_count / $total_count) * 100;
                $pending_percentage = ($pending_count / $total_count) * 100;

                $pending_limit = (500 / 100) * $pending_percentage;
                $sold_limit = (500 / 100) * $sold_percentage;

                
                $pending_options = array('skip' => abs($skip_pending), 'sort' => array('modified_date' => -1), 'limit' => abs(intval($pending_limit)));
                
                $sold_options = array('skip' => abs($skip_sold), 'sort' => array('modified_date' => -1), 'limit' => abs(intval($sold_limit)));


                $pending_curser = $connetct->buy_orders->find($search, $pending_options);
                $sold_curser = $connetct->sold_buy_orders->find($search, $sold_options);

                $pending_arr = iterator_to_array($pending_curser);
                $sold_arr = iterator_to_array($sold_curser);
                $orders = array_merge_recursive($pending_arr, $sold_arr);

                if($this->input->post('optradio') != ""){

                    if($this->input->post('optradio') == 'created_date'){

                        if($this->input->post('selector') == 'ASC'){

                            foreach($orders as $key => $part) {
                                $sort[$key] = (string) $part['created_date'];
                            }
                            array_multisort($sort, SORT_ASC, $orders);

                        }else{

                            foreach($orders as $key => $part) {
                                $sort[$key] = (string) $part['created_date'];
                                
                            }
                            array_multisort($sort, SORT_DESC, $orders);
                        }

                    }elseif($this->input->post('optradio') == 'modified_date'){

                        if($this->input->post('selector') == 'ASC'){
                            foreach ($orders as $key => $part) {
                                $sort[$key] = (string) $part['modified_date'];
                            }
                            array_multisort($sort, SORT_ASC, $orders);
                        }else{
                            foreach ($orders as $key => $part) {
                                $sort[$key] = (string) $part['modified_date'];
                            }
                            array_multisort($sort, SORT_DESC, $orders);
                        }
                        
                    }
                }else{
                    foreach ($orders as $key => $part) {
                        $sort[$key] = (string) $part['modified_date'];
                    }
                    array_multisort($sort, SORT_DESC, $orders);
                }

                $new_order_arrray = array();
                foreach ($orders as $order) {
                    $id = $order['admin_id'];
                    $data_user = $this->get_username_from_user($id);

                    $order['admin'] = $data_user;
                    $_id = $order['_id'];

                    $error = $this->get_error_type($_id);
                    $order['log'] = $error;
                    array_push($new_order_arrray, $order);
                }
                $data['orders'] = $new_order_arrray;

            }
            
            $coins = $this->mod_coins->get_all_coins();
            $data['coins'] = $coins;
            $this->stencil->paint('admin/trading_reports/repeating_orders_report', $data);
        }
    } //End repeating_orders


    // duplicate_order_confirmation 
    // TODO: Confirm if order is actually showing duplicate results orders_history_log and what are they ?
    public function duplicate_order_confirmation($order_id){

        $pipeline = array(
            array(
                '$project' => array(
                    "type" => 1,
                    "count" => 1,
                    "log_msg" => 1,
                    'created_date' => 1,
                    'order_id' => 1,
                ),
            ),
    
            array(
                '$match' => array(
                    'order_id' => new MongoDB\BSON\ObjectId($order_id)
                ),
            ),
            array('$sort' => array('created_date' => -1))
            
        );
    
        $allow = array('allowDiskUse' => true);
        $db = $this->mongo_db->customQuery();

        $response = $db->orders_history_log->aggregate($pipeline,$allow);
        $records = iterator_to_array($response);
        
        echo '<pre>';
        print_r("Total Orders found in orders_history_log: ".count($records)."\r\n");
        print_r($records);
        die('<br>**************** End Script ****************');

    }// end duplicate_order_confirmation

    public function index() {

        if($this->input->post()){

            $data_arr['filter_order_data'] = $this->input->post();
            $this->session->set_userdata($data_arr);

            $start_date = $this->input->post('filter_by_start_date');
            $end_date = $this->input->post('filter_by_end_date');
            $status = $this->input->post('filter_by_status');

            $start_date = date('Y-m-d', strtotime($start_date)) . " 00:00:00";
            $end_date = date('Y-m-d', strtotime($end_date)) . " 23:59:59";
            
            $orig_date = new DateTime($start_date);
            $orig_date = $orig_date->getTimestamp();
            $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

            $orig_date22 = new DateTime($end_date);
            $orig_date22 = $orig_date22->getTimestamp();
            $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);

            $data['filter_user_data']['filter_by_start_date'] = $start_date;
            $data['filter_user_data']['filter_by_end_date'] = $end_date;

            
            $data['userFinalData'] = $this->get_order_logs($start_date , $end_date , $status);
        }
   
        $this->stencil->paint('admin/trading_reports/chk_log_duplication',$data);
    }  

    public function get_order_logs($start_date , $end_date , $status) {


        $pipeline = array(
            array(
                '$project' => array(
                    "type" => 1,
                    "count" => 1,
                    "log_msg" => 1,
                    'created_date' => 1,
                    'order_id' => 1,
                ),
            ),
    
           array(
                '$match' => array(
                    'type' => $status,
                    'created_date' => array('$gte' => $start_date, '$lte' => $end_date),
                    
                ),
            ),
            
            array('$group' => array(
                '_id' => array('order_id' => '$order_id'),
                'count' => array('$sum' => 1),
                'type' => array('$first' => '$type'),
                'log_msg' => array('$first' => '$log_msg'),
                'order_id' => array('$first' => '$order_id'),
                'created_date' => array('$first' => '$created_date'),
            ),
            ),
            
            array('$sort' => array('created_date' => -1)),
            
        );
    
        $allow = array('allowDiskUse' => true);
        $db = $this->mongo_db->customQuery();

        $response = $db->orders_history_log->aggregate($pipeline,$allow);

        $row = iterator_to_array($response);
        $resp=array();
        foreach($row as $value){
            $res = array();
            $counts = $value['count'];
            $arr =  $value['order_id'];
            
            if($counts >= 2 ){

                $oid = (string) $arr;
                $uid = $this->get_buyorder($oid);
                //$uid = (string) $uid;
                $username = $this->get_users($uid);
                $coin = $this->coin($oid);
                if($uid){

                    $res['oid'] = $oid;
                    $res['user'] = $username;
                    $res['coin'] = $coin;
                    $res['counts'] = $counts;
                    $resp[] = $res;
                }
        
            }
            else{
                continue;
            }

        }

        $data['userFinalData'] = $resp;
        // echo "<pre>";
        // print_r($data);
        // exit;
        return $data['userFinalData'];

    }
    public function get_users($uid) {
        
        $where['_id'] = $uid;
        $this->mongo_db->where($where);
        $get_obj = $this->mongo_db->get('users');
        $buy_orders = iterator_to_array($get_obj);

        foreach($buy_orders as $row){

            $username = $row['username'];
            
        }
        return $username;

    }
    public function get_buyorder($o_id) {  
       
        $where['_id'] = $o_id;
        $this->mongo_db->where($where);

        $get_obj = $this->mongo_db->get('buy_orders');
        $buy_orders = iterator_to_array($get_obj);

        foreach($buy_orders as $row){
            
            $uid = $row['admin_id'];
        }
        return $uid;

    }

    public function get_order($order_id) {
       
        $where['_id'] = $order_id;
        $this->mongo_db->where($where);
        $get_obj = $this->mongo_db->get('buy_orders');
        $buy_orders = iterator_to_array($get_obj);
        
        echo "<pre>";
        print_r($buy_orders);
        exit;
      
    }
    public function coin($o_id) {

        //$o_id = (string) $order_ids;
        $where['_id'] = $o_id;
        $this->mongo_db->where($where);

        $get_obj = $this->mongo_db->get('buy_orders');
        $buy_orders = iterator_to_array($get_obj);
        

        foreach($buy_orders as $row){
            $coin = $row['symbol'];
        }

        return $coin;
    }
    public function reset_filter(){

        $this->session->unset_userdata('filter_order_data');
        redirect(base_url() . 'admin/trading_reports/chk_log_duplication/');

    }//End of reset_filter

    //reset_report_filters
    public function reset_report_filters($report=null) {
        $this->session->unset_userdata($report);
        return true;
    }//end reset_report_filters

    //unique_multidim_array
    public function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();
        
        $temp_d_count = 0;
        $duplicate_orders = [];
        
        $duplicate_temp_array = array();
        $duplicate_key_array = array();
        
        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }else{
                
                
                if (!in_array($val[$key], $duplicate_key_array)) {
                    $duplicate_key_array[] = (string)$val[$key];
                    $order_id = (string)$val[$key];
                    $duplicate_temp_array[$order_id] = $val;
                    $duplicate_temp_array[$order_id]['buy_submitted'] = ($val['type'] == 'buy_submitted' ? 1 : 0);
                    $duplicate_temp_array[$order_id]['sell_submitted'] = ($val['type'] == 'sell_submitted' ? 1 : 0);
                    
                }else{
                    
                    $order_id = $val[$key];
                    $t_do_buy = $duplicate_temp_array[$order_id]['buy_submitted'];
                    $t_do_sell = $duplicate_temp_array[$order_id]['sell_submitted'];
                    $duplicate_temp_array[$order_id]['buy_submitted'] = ($val['type'] == 'buy_submitted' ? $t_do_buy + 1 : $t_do_buy + 0);
                    $duplicate_temp_array[$order_id]['sell_submitted'] = ($val['type'] == 'sell_submitted' ? $t_do_sell + 1 : $t_do_sell +0);
                    
                }
                
            }
            $i++;
        }
        return $duplicate_temp_array;
        // return $temp_array;
    }//end unique_multidim_array

    //get_username_from_user
    public function get_username_from_user($id) {

        if (preg_match('/^[a-f\d]{24}$/i', $id)) {
            $customer = $this->mod_report->get_customer($this->mongo_db->mongoId($id));
        }
        return $customer;
    }//end get_username_from_user

    //get_error_type
    public function get_error_type($id) {
        $this->mongo_db->limit(1);
        $this->mongo_db->order_by(array('_id' => -1));
        $this->mongo_db->where(array('order_id' => $id, 'type' => array('$in' => array('buy_error', 'sell_error'))));
        $mongo_obj = $this->mongo_db->get('orders_history_log');
        $orders = iterator_to_array($mongo_obj);
        return $orders[0];

    }//end get_error_type

    //repeating_orders_array
    public function repeating_orders_array($array, $key) {
    
        $temp_arr = $array;
        $res_arr = [];
        $range = 5; //minutes
        foreach($array as $key=>$a){

            $t1 = ($a['created_date'])->toDateTime();
            $akey = (string)$a->buy_parent_id;
            $a_buy_parent_id = $akey;
            $res_arr[$akey][] = $a;
            foreach($array as $b){
                
                $b_buy_parent_id = (string)$b->buy_parent_id;
            
                if($a_buy_parent_id == $b_buy_parent_id && $a['_id'] != $b['_id']){
                    
                    $diff = $t1->diff(($b['created_date'])->toDateTime());

                    if($diff->i <= 5 && $diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h == 0){
                        if(array_key_exists($akey, $res_arr)){
                            $res_arr[$akey][] = $b;
                            unset($temp_arr[$key]);
                        }
                    }
                }
            }
            
            if(count($res_arr[$akey]) < 2){
                unset($res_arr[$akey]);
            }else{
                $res_arr[$akey] = array_unique($res_arr[$akey], SORT_REGULAR);
            }
        }

        $res_arr = array_values($res_arr);
        $res_arr = array_merge(...$res_arr);
        $res_arr = array_column($res_arr, '_id');

        return $res_arr;
        
        // echo '<pre>';
        // count($res_arr);
        // echo '<br>';
        // print_r($res_arr);
        // die('end');
        
        
        // $array = array(
        //     array(
        //         'id' => 1,
        //         'parent_id' => 22,
        //         'created_date' => '2007-09-01 04:10:58',
        //     ),array(
        //         'id' => 2,    
        //         'parent_id' => 21,    
        //         'created_date' => '2007-09-01 04:10:58',
        //     ),array(
        //         'id' => 3,
        //         'parent_id' => 22,
        //         'created_date' => '2007-09-01 04:12:58',
        //     ),array(
        //         'id' => 4,
        //         'parent_id' => 23,
        //         'created_date' => '2007-09-01 04:10:58',
        //     ),
        // );

        // $temp_arr = $array;
        // $res_arr = [];
        // foreach($array as $key=>$a){
        //     $t1 = new DateTime($a['created_at']);
        //     $akey = $a['parent_id'];
        //     $res_arr[$akey][] = $a;
        //     foreach($array as $b){
                
        //         if($a['parent_id'] == $b['parent_id'] && $a['id'] != $b['id']){
                    
        //             $diff = $t1->diff(new DateTime($b['created_at']));
        //             if($diff->i < 5){
        //                 if(array_key_exists($akey, $res_arr)){
                            
        //                     $res_arr[$akey][] = $b;
        //                     echo $diff->i;
        //                     unset($temp_arr[$key]);
        //                 }
        //             }
        //         }
        //     }
            
        //     if(count($res_arr[$akey]) < 2){
        //         unset($res_arr[$akey]);
        //     }else{
        //         $res_arr[$akey] = array_unique($res_arr[$akey], SORT_REGULAR);
        //     }
        // }

        // $res_arr = array_merge(...$res_arr);

        // print_r($res_arr);die('end');

    }//end repeating_orders_array
}
