<?php
/**
 *
 */
class Reports extends CI_Controller {

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
        $this->load->model('admin/mod_users');
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }
        // if ($this->session->userdata('special_role') != 1) {
        //     redirect(base_url() . 'forbidden');
        // }

    }
    public function order_reports() {

        redirect(base_url() . 'admin/order_report');
        
        //Login Check
        // error_reporting(E_ALL & ~E_NOTICE);
        // ini_set('display_errors', E_ALL & ~E_NOTICE);

        $this->mod_login->verify_is_admin_login();
        if ($this->input->post()) {
            $data_arr['filter_order_data'] = $this->input->post();
            $this->session->set_userdata($data_arr);
        }

        $session_data = $this->session->userdata('filter_order_data');
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
                //$search['$or'] = array("buy_rule_number" => $filter_by_rule, "sell_rule_number" => $filter_by_rule);
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

            //multi-select //Umer Abbas [12-11-19]
            if (!empty($session_data['filter_by_status'])) {
                $order_status = $session_data['filter_by_status'];

                $status_filter_arr = array();
                if (in_array('new', $order_status)) {
                    $status_filter_arr[] = 'new';
                }
                if (in_array('error', $order_status)) {
                    $status_filter_arr[] = 'error';
                }
                if (in_array('open', $order_status)) {
                    $status_filter_arr[] = 'submitted';
                    $status_filter_arr[] = 'FILLED';
                    $search['is_sell_order'] = 'yes';
                }
                if (in_array('sold', $order_status)) {
                    $status_filter_arr[] = 'FILLED';
                    $search['is_sell_order'] = 'sold';
                    // $collection = "sold_buy_orders";
                }
                if (in_array('LTH', $order_status)) {
                    $status_filter_arr[] = 'LTH';
                }
                if (in_array('error_in_sell', $order_status)) {
                    //TODO: find all orders with error in sell
                    // $error_in_sell_aggrigate = 'aggregate([
                    //     {
                    //     $lookup:
                    //         {
                    //             from: "orders",
                    //             let: { orders_status: "$sell_order_id", order_qty: "$ordered" },
                    //             pipeline: [
                    //                 { $match:
                    //                     { $expr:{ 
                    //                         $and:[
                    //                                 { $eq: [ "$stock_item",  "$$order_item" ] },
                    //                                 { $gte: [ "$instock", "$$order_qty" ] }
                    //                             ]
                    //                         }
                    //                     },
                    //                   $limit: 20
                    //                 },
                    //                 { $project: { stock_item: 0, _id: 0 } }
                    //             ],
                    //             as: "stockdata"
                    //         }
                    //     }
                    // ])';
                    
                    // $orders_arr = $connetct->buy_orders->$error_in_sell_aggrigate;
                    // $orders_arr = iterator_to_array($orders_arr);

                }
                if (in_array('submitted_for_sell', $order_status)) {
                    $status_filter_arr[] = 'submitted_for_sell';
                }
                if (in_array('FILLED_submitted_for_sell', $order_status)) {
                    $status_filter_arr[] = 'FILLED_submitted_for_sell';
                }
                if (in_array('submitted_error', $order_status)) {
                    $status_filter_arr[] = 'submitted_error';
                }
                if (in_array('submitted', $order_status)) {
                    $status_filter_arr[] = 'submitted';
                }
                $status_filter_arr = array_unique($status_filter_arr);
                $status_filter_arr = array_values($status_filter_arr); //to reindex the array

                $search['status'] = array('$in' => $status_filter_arr);
            }
            // echo '<pre>';
            // print_r($search['status']);
            // die('working on multi select filter');

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
            //Umer Abbas [1-11-19]
            if ($session_data['filter_by_exchange'] != "") {
                $order_exchange = $session_data['filter_by_exchange'];
                if ($order_exchange == 'bam') {
                    $search['exchange'] = 'bam';
                } elseif ($order_exchange == 'binance') {
                    $search['exchange'] = 'binance';
                } elseif ($order_exchange == 'coinbasepro') {
                    $search['exchange'] = 'coinbasepro';
                }
            }
            if (!empty($session_data['filter_by_nature'])) {
                if($session_data['filter_by_nature'] == 'auto'){

                    $search['trigger_type'] = array('$ne' => 'no');
                    if($search['status'] == 'new'){
                        $search['buy_parent_id'] = array('$exists' => true);
                    }
                    
                }else if($session_data['filter_by_nature'] == 'manual'){
                   
                    $search['trigger_type'] = 'no';
                    
                }else if($session_data['filter_by_nature'] == 'parent'){
                    
                    $search['parent_status'] = 'parent';
                    if ($search['status'] == 'new') {
                        $search['buy_parent_id'] = array('$exists' => true);
                    }

                }else{
                    $search['parent_status'] = array('$ne' => 'parent');
                }
            }else{
                $search['parent_status'] = array('$ne' => 'parent');
            }

            //$search['status'] = array('$ne' => 'canceled');
            // echo "<pre>";
            // print_r($search);
            // exit;

            // $qr_sold = array('skip' => $skip_sold, 'sort' => $oder_arr, 'limit' => $limit);
            // $qr_pending = array('skip' => $skip_pending, 'sort' => $oder_arr, 'limit' => $limit);

            $connetct = $this->mongo_db->customQuery();

            //Umer Abbas [1-11-19]
            if(!empty($search['exchange'])){
                if($search['exchange'] != 'binance'){
                    $collection_str1 = 'sold_buy_orders_'.$search['exchange'];
                    $collection_str2 = 'buy_orders_'.$search['exchange'];
                    $sold_count = $connetct->$collection_str1->count($search);
                    $pending_count = $connetct->$collection_str2->count($search);
                }else{
                    $sold_count = $connetct->sold_buy_orders->count($search);
                    $pending_count = $connetct->buy_orders->count($search);
                }
            }else{
                
                // print_r($search);
                $sold_count = $connetct->sold_buy_orders->count($search);
                $pending_count = $connetct->buy_orders->count($search);
            }


            

            $total_count = $sold_count + $pending_count;

            /////////////////////// PAGINATION CODE START HERE /////////////////////////////////////
            $this->load->library("pagination");
            $config = array();
            $config["base_url"] = SURL . "admin/reports/order_reports";
            $config["total_rows"] = $total_count;
            $config['per_page'] = 100;
            $config['num_links'] = 3;
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
            $page = $this->uri->segment(4);

            if (!isset($page)) {$page = 1;}
            $start = ($page - 1) * $config["per_page"];
            $skip = $start;
            $skip_sold = $skip;
            $skip_pending = $skip;
            $limit = $config["per_page"];
            ////////////////////////////End Pagination Code///////////////////////////////////////

            $data['pagination'] = $this->pagination->create_links();

            // echo '<pre>';
            // print_r($data['pagination']);
            // die('working');

            /////////////////////// PAGINATION CODE END HERE /////////////////////////////////////
            
            // $sold_percentage = ($sold_count / $total_count) * 100;
            // $pending_percentage = ($pending_count / $total_count) * 100;

            // $pending_limit = (500 / 100) * $pending_percentage;
            // $sold_limit = (500 / 100) * $sold_percentage;

            $pending_options = array('skip' => $skip_pending, 'sort' => array('modified_date' => -1), 'limit' => intval($limit));

            $sold_options = array('skip' => $skip_sold, 'sort' => array('modified_date' => -1), 'limit' => intval($limit));

            // $skip_sold = $skip_sold +(int)$sold_limit;
            // $skip_pending = $skip_pending +(int)$pending_limit;
            // $this->session->set_userdata(array('skip_sold'=>$skip_sold,'skip_pending'=>$skip_pending));
            

            //Umer Abbas [1-11-19]
            if(!empty($search['exchange'])){
                if($search['exchange'] != 'binance'){
                    $collection_str1 = 'sold_buy_orders_'.$search['exchange'];
                    $collection_str2 = 'buy_orders_'.$search['exchange'];
                    $pending_curser = $connetct->$collection_str1->find($search, $pending_options);
                    $sold_curser = $connetct->$collection_str2->find($search, $sold_options);
                }else{
                    $pending_curser = $connetct->buy_orders->find($search, $pending_options);
                    $sold_curser = $connetct->sold_buy_orders->find($search, $sold_options);
                }
            }else{
                $pending_curser = $connetct->buy_orders->find($search, $pending_options);
                $sold_curser = $connetct->sold_buy_orders->find($search, $sold_options);
            }

            $pending_arr = iterator_to_array($pending_curser);
            $sold_arr = iterator_to_array($sold_curser);
            $orders = array_merge_recursive($pending_arr, $sold_arr);

            //added by 06/08/2019

            if($this->input->post('optradio') != ""){

                if($this->input->post('optradio') == 'created_date'){

                    if($this->input->post('selector') == 'ASC'){

                        foreach ($orders as $key => $part) {
                            $sort[$key] = (string) $part['created_date'];
                        }
            
                        array_multisort($sort, SORT_ASC, $orders);

                    }else{

                        foreach ($orders as $key => $part) {
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

            //end

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
            // echo "<pre>";
            // print_r($new_order_arrray);exit;
            // $new_order_arrray['average'] = $test_arr;
            $data['orders'] = $new_order_arrray;

        }
        $coins = $this->mod_coins->get_all_coins();
        $data['coins'] = $coins;
        $this->stencil->paint('admin/reports/my_custom_order_report', $data);
    } //End of order_reports

    public function new_order_reports() {
        
        //Login Check
        // error_reporting(E_ALL & ~E_NOTICE);
        // ini_set('display_errors', E_ALL & ~E_NOTICE);

        $this->mod_login->verify_is_admin_login();
        if ($this->input->post()) {
            $data_arr['filter_order_data'] = $this->input->post();
            $this->session->set_userdata($data_arr);
        }

        $session_data = $this->session->userdata('filter_order_data');
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
                //$search['$or'] = array("buy_rule_number" => $filter_by_rule, "sell_rule_number" => $filter_by_rule);
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

            //multi-select //Umer Abbas [12-11-19]
            if (!empty($session_data['filter_by_status'])) {
                $order_status = $session_data['filter_by_status'];

                $status_filter_arr = array();
                if (in_array('new', $order_status)) {
                    $status_filter_arr[] = 'new';
                }
                if (in_array('error', $order_status)) {
                    $status_filter_arr[] = 'error';
                }
                if (in_array('open', $order_status)) {
                    $status_filter_arr[] = 'submitted';
                    $status_filter_arr[] = 'FILLED';
                    $search['is_sell_order'] = 'yes';
                }
                if (in_array('sold', $order_status)) {
                    $status_filter_arr[] = 'FILLED';
                    $search['is_sell_order'] = 'sold';
                    // $collection = "sold_buy_orders";
                }
                if (in_array('LTH', $order_status)) {
                    $status_filter_arr[] = 'LTH';
                }
                if (in_array('error_in_sell', $order_status)) {
                    
                    $orders_arr = $connetct->buy_orders->$error_in_sell_aggrigate;
                    $orders_arr = iterator_to_array($orders_arr);

                }
                $status_filter_arr = array_unique($status_filter_arr);
                $status_filter_arr = array_values($status_filter_arr); //to reindex the array

                $search['status'] = array('$in' => $status_filter_arr);
            }
            // echo '<pre>';
            // print_r($search['status']);
            // die('working on multi select filter');

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
            //Umer Abbas [1-11-19]
            if ($session_data['filter_by_exchange'] != "") {
                $order_exchange = $session_data['filter_by_exchange'];
                if ($order_exchange == 'bam') {
                    $search['exchange'] = 'bam';
                } elseif ($order_exchange == 'binance') {
                    $search['exchange'] = 'binance';
                } elseif ($order_exchange == 'coinbasepro') {
                    $search['exchange'] = 'coinbasepro';
                }
            }
            if (!empty($session_data['filter_by_nature'])) {
                if($session_data['filter_by_nature'] == 'auto'){

                    $search['trigger_type'] = array('$ne' => 'no');
                    if($search['status'] == 'new'){
                        $search['buy_parent_id'] = array('$exists' => true);
                    }
                    
                }else if($session_data['filter_by_nature'] == 'manual'){
                   
                    $search['trigger_type'] = 'no';
                    
                }else if($session_data['filter_by_nature'] == 'parent'){
                    
                    $search['parent_status'] = 'parent';
                    if ($search['status'] == 'new') {
                        $search['buy_parent_id'] = array('$exists' => true);
                    }

                }else{
                    $search['parent_status'] = array('$ne' => 'parent');
                }
            }else{
                $search['parent_status'] = array('$ne' => 'parent');
            }

            //$search['status'] = array('$ne' => 'canceled');
            // echo "<pre>";
            // print_r($search);
            // exit;

            // $qr_sold = array('skip' => $skip_sold, 'sort' => $oder_arr, 'limit' => $limit);
            // $qr_pending = array('skip' => $skip_pending, 'sort' => $oder_arr, 'limit' => $limit);

            $connetct = $this->mongo_db->customQuery();

            //Umer Abbas [1-11-19]
            if(!empty($search['exchange'])){
                if($search['exchange'] != 'binance'){
                    $collection_str1 = 'sold_buy_orders_'.$search['exchange'];
                    $collection_str2 = 'buy_orders_'.$search['exchange'];
                    $sold_count = $connetct->$collection_str1->count($search);
                    $pending_count = $connetct->$collection_str2->count($search);
                }else{
                    $sold_count = $connetct->sold_buy_orders->count($search);
                    $pending_count = $connetct->buy_orders->count($search);
                }
            }else{
                
                // print_r($search);
                $sold_count = $connetct->sold_buy_orders->count($search);
                $pending_count = $connetct->buy_orders->count($search);
            }


            

            $total_count = $sold_count + $pending_count;

            /////////////////////// PAGINATION CODE START HERE /////////////////////////////////////
            $this->load->library("pagination");
            $config = array();
            $config["base_url"] = SURL . "admin/reports/order_reports";
            $config["total_rows"] = $total_count;
            $config['per_page'] = 100;
            $config['num_links'] = 3;
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
            $page = $this->uri->segment(4);

            if (!isset($page)) {$page = 1;}
            $start = ($page - 1) * $config["per_page"];
            $skip = $start;
            $skip_sold = $skip;
            $skip_pending = $skip;
            $limit = $config["per_page"];
            ////////////////////////////End Pagination Code///////////////////////////////////////

            $data['pagination'] = $this->pagination->create_links();

            // echo '<pre>';
            // print_r($data['pagination']);
            // die('working');

            /////////////////////// PAGINATION CODE END HERE /////////////////////////////////////
            
            // $sold_percentage = ($sold_count / $total_count) * 100;
            // $pending_percentage = ($pending_count / $total_count) * 100;

            // $pending_limit = (500 / 100) * $pending_percentage;
            // $sold_limit = (500 / 100) * $sold_percentage;

            $pending_options = array('skip' => $skip_pending, 'sort' => array('modified_date' => -1), 'limit' => intval($limit));

            $sold_options = array('skip' => $skip_sold, 'sort' => array('modified_date' => -1), 'limit' => intval($limit));

            // $skip_sold = $skip_sold +(int)$sold_limit;
            // $skip_pending = $skip_pending +(int)$pending_limit;
            // $this->session->set_userdata(array('skip_sold'=>$skip_sold,'skip_pending'=>$skip_pending));
            

            //Umer Abbas [1-11-19]
            if(!empty($search['exchange'])){
                if($search['exchange'] != 'binance'){
                    $collection_str1 = 'sold_buy_orders_'.$search['exchange'];
                    $collection_str2 = 'buy_orders_'.$search['exchange'];
                    $pending_curser = $connetct->$collection_str1->find($search, $pending_options);
                    $sold_curser = $connetct->$collection_str2->find($search, $sold_options);
                }else{
                    $pending_curser = $connetct->buy_orders->find($search, $pending_options);
                    $sold_curser = $connetct->sold_buy_orders->find($search, $sold_options);
                }
            }else{
                $pending_curser = $connetct->buy_orders->find($search, $pending_options);
                $sold_curser = $connetct->sold_buy_orders->find($search, $sold_options);
            }

            $pending_arr = iterator_to_array($pending_curser);
            $sold_arr = iterator_to_array($sold_curser);
            $orders = array_merge_recursive($pending_arr, $sold_arr);

            //added by 06/08/2019

            if($this->input->post('optradio') != ""){

                if($this->input->post('optradio') == 'created_date'){

                    if($this->input->post('selector') == 'ASC'){

                        foreach ($orders as $key => $part) {
                            $sort[$key] = (string) $part['created_date'];
                        }
            
                        array_multisort($sort, SORT_ASC, $orders);

                    }else{

                        foreach ($orders as $key => $part) {
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

            //end

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
        $this->stencil->paint('admin/reports/new_custom_order_report', $data);
    } //End of new_order_reports

 
    function get_all_usernames_ajax() {
        $this->mongo_db->sort(array('_id' => -1));
        $get_users = $this->mongo_db->get('users');

        $users_arr = iterator_to_array($get_users);

        $user_name_array = array_column($users_arr, 'username');

        echo json_encode($user_name_array);
        exit;
    }

    public function csv_export_trades() {

        $data_arr['filter_order_data'] = $this->input->post();
        $session_post_data = $this->session->userdata('filter_order_data');

        $collection = "buy_orders";
        if ($session_post_data['filter_by_coin']) {
            $search['symbol'] = $session_post_data['filter_by_coin'];
        }
        if ($session_post_data['filter_by_mode']) {
            $search['application_mode'] = $session_post_data['filter_by_mode'];
        }
        if ($session_post_data['filter_by_trigger']) {
            $search['trigger_type'] = $session_post_data['filter_by_trigger'];
        }
        if ($session_post_data['filter_level']) {
            $order_level = $session_post_data['filter_level'];
            $search['order_level'] = $order_level;
        }
        if ($session_post_data['filter_username']) {
            $username = $session_post_data['filter_username'];
            $admin_id = $this->get_admin_id($username);
            $search['admin_id'] = (string) $admin_id;
        }
        if ($session_post_data['optradio']) {
            if ($session_post_data['optradio'] == 'created_date') {
                $oder_arr['created_date'] = -1;
            } elseif ($session_post_data['optradio'] == 'modified_date') {
                $oder_arr['modified_date'] = -1;
            }
        }
        if ($session_post_data['filter_by_status']) {
            $order_status = $this->input->post('filter_by_status');
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
            }
        }
        if ($session_post_data['filter_by_start_date'] != "" && $session_post_data['filter_by_end_date'] != "") {

            $created_datetime = date('Y-m-d G:i:s', strtotime($session_post_data['filter_by_start_date']));
            $orig_date = new DateTime($created_datetime);
            $orig_date = $orig_date->getTimestamp();
            $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

            $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_post_data['filter_by_end_date']));
            $orig_date22 = new DateTime($created_datetime22);
            $orig_date22 = $orig_date22->getTimestamp();
            $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
            $search['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
        }
        $search['parent_status'] = array('$ne' => 'parent');
        //$search['status'] = array('$ne' => 'canceled');

        $connetct = $this->mongo_db->customQuery();
        $pending_curser = $connetct->buy_orders->find($search);
        $sold_curser = $connetct->sold_buy_orders->find($search);

        $pending_arr = iterator_to_array($pending_curser);
        $sold_arr = iterator_to_array($sold_curser);

        $orders = array_merge_recursive($pending_arr, $sold_arr);

        foreach ($orders as $key => $part) {
            $sort[$key] = (string) $part['modified_date'];
        }

        array_multisort($sort, SORT_DESC, $orders);

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
        //$data['orders'] = $new_order_arrray;

        $full_arr = array();
        foreach ($new_order_arrray as $key => $value) {
            if (!empty($value)) {
                $retArr = array();

                if (isset($value['5_hour_max_market_price']) && $value['5_hour_max_market_price'] != '') {

                    $five_hour_max_market_price = $value['5_hour_max_market_price'];
                    $purchased_price = (float) $value['market_value'];
                    $profit = $five_hour_max_market_price - $purchased_price;

                    $profit_margin = ($profit / $five_hour_max_market_price) * 100;

                    $max_profit_per = ($profit) * (100 / $purchased_price);

                    $max_profit_per = number_format($max_profit_per, 2);
                }

                if (isset($value['5_hour_min_market_price']) && $value['5_hour_min_market_price'] != '') {

                    $market_lowest_value = $value['5_hour_min_market_price'];
                    $purchased_price = (float) $value['market_value'];
                    $profit = $market_lowest_value - $purchased_price;

                    $profit_margin = ($profit / $market_lowest_value) * 100;

                    $min_profit_per = ($profit) * (100 / $purchased_price);
                    $min_profit_per = number_format($min_profit_per, 2);
                }

                if (isset($value['market_heighest_value']) && $value['market_heighest_value'] != '') {

                    $five_hour_max_market_price1 = $value['market_heighest_value'];
                    $purchased_price1 = (float) $value['market_value'];
                    $profit1 = $five_hour_max_market_price1 - $purchased_price1;

                    $profit_margin1 = ($profit1 / $five_hour_max_market_price1) * 100;

                    $max_profit_per1 = ($profit1) * (100 / $purchased_price1);

                    $max_profit_per1 = number_format($max_profit_per1, 2);
                }

                if (isset($value['market_lowest_value']) && $value['market_lowest_value'] != '') {

                    $market_lowest_value2 = $value['market_lowest_value'];
                    $purchased_price2 = (float) $value['market_value'];
                    $profit2 = $market_lowest_value2 - $purchased_price2;

                    $profit_margin2 = ($profit2 / $market_lowest_value2) * 100;

                    $min_profit_per2 = ($profit2) * (100 / $purchased_price2);
                    $min_profit_per2 = number_format($min_profit_per2, 2);
                }

                $this->load->model('admin/mod_dashboard');

                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                if ($value['status'] == 'FILLED') {

                    if ($value['is_sell_order'] == 'yes') {

                        $current_data = num($market_value) - num($value['market_value']);
                        $market_data = ($current_data * 100 / $market_value);
                        $market_data = number_format((float) $market_data, 2, '.', '');
                    }
                    if ($value['is_sell_order'] == 'sold') {
                        $current_data = num($value['market_sold_price']) - num($value['market_value']);
                        $market_data = ($current_data * 100 / $value['market_sold_price']);
                        $market_data = number_format((float) $market_data, 2, '.', '');
                    }
                }

                $retArr['id'] = $value['_id'];
                $retArr['symbol'] = $value['symbol'];
                $retArr['price'] = $value['price'];
                $retArr['quantity'] = $value['quantity'];
                $retArr['order_type'] = $value['order_type'];
                $retArr['trigger_type'] = $value['trigger_type'];
                $retArr['binance_order_id'] = $value['binance_order_id'];
                $retArr['buy_parent_id'] = $value['buy_parent_id'];
                $retArr['application_mode'] = $value['application_mode'];
                $retArr['defined_sell_percentage'] = $value['defined_sell_percentage'];
                $retArr['order_level'] = $value['order_level'];
                $retArr['status'] = $value['status'];
                $retArr['is_sell_order'] = $value['is_sell_order'];
                $retArr['market_value'] = $value['market_value'];
                $retArr['sell_order_id'] = $value['sell_order_id'];
                $retArr['is_manual_buy'] = $value['is_manual_buy'];
                $retArr['is_manual_sold'] = $value['is_manual_sold'];
                $retArr['sell_rule_number'] = $value['sell_rule_number'];
                $retArr['buy_rule_number'] = $value['buy_rule_number'];
                $retArr['market_sold_price'] = $value['market_sold_price'];
                $retArr['profit_data'] = $market_data;
                $retArr['5_hour_max_market_price'] = $value['5_hour_max_market_price'];
                $retArr['5_hour_min_market_price'] = $value['5_hour_min_market_price'];
                $retArr['five_hour_max_profit'] = $max_profit_per;
                $retArr['five_hour_min_profit'] = $min_profit_per;
                $retArr['market_heighest_value'] = $value['market_heighest_value'];
                $retArr['market_lowest_value'] = $value['market_lowest_value'];
                $retArr['market_heighest_profit'] = $max_profit_per1;
                $retArr['market_lowest_profit'] = $min_profit_per2;
                $retArr['username'] = $value['admin']['username'];
                $retArr['email_address'] = $value['admin']['email_address'];
                $retArr['created_date'] = $value['created_date']->toDatetime()->format("d-M, Y H:i:s");
                $retArr['modified_date'] = $value['modified_date']->toDatetime()->format("d-M, Y H:i:s");
                $retArr['created_time_ago'] = time_elapsed_string($value['created_date']->toDatetime()->format("Y-m-d H:i:s"));
                $retArr['last_updated'] = time_elapsed_string($value['modified_date']->toDatetime()->format("Y-m-d H:i:s"));

                $fullarray[] = $retArr;
            }
        }
        $this->download_send_headers("order_report_" . date("Y-m-d_ Gisa") . ".csv");

        echo $this->array2csv($fullarray);

        exit;

    }

    public function coin_report() {
        $this->mod_login->verify_is_admin_login();
        if ($this->input->post()) {

            $data_arr['filter_order_data'] = $this->input->post();
            $this->session->set_userdata($data_arr);

            $collection = "buy_orders";
            $coin_array = array();
            if (!empty($this->input->post('filter_by_coin'))) {
                $coin_array = $this->input->post('filter_by_coin');
                //$search['symbol']['$in'] = $this->input->post('filter_by_coin');
            } else {
                $coin_array_all = $this->mod_coins->get_all_coins();
                $coin_array = array_column($coin_array_all, 'symbol');
            }

            if ($this->input->post('filter_by_mode')) {
                $search['order_mode'] = $this->input->post('filter_by_mode');
            }

            if ($this->input->post('group_filter') != "") {
                if ($this->input->post('group_filter') == 'rule_group') {
                    $filter = 'rule';
                } elseif ($this->input->post('group_filter') == 'trigger_group') {
                    $filter = 'trigger';
                }
            } else {
                $filter = 'all';
            }

            if ($_POST['filter_by_start_date'] != "" && $_POST['filter_by_end_date'] != "") {

                $created_datetime = date('Y-m-d G:i:s', strtotime($_POST['filter_by_start_date']));
                $orig_date = new DateTime($created_datetime);
                $orig_date = $orig_date->getTimestamp();
                $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

                $created_datetime22 = date('Y-m-d G:i:s', strtotime($_POST['filter_by_end_date']));
                $orig_date22 = new DateTime($created_datetime22);
                $orig_date22 = $orig_date22->getTimestamp();
                $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
                $search['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
            }

            //xit;

            if ($filter == 'all') {
                $conn = $this->mongo_db->customQuery();
                $order_arr_all = array();
                foreach ($coin_array as $key => $value) {
                    $search['symbol'] = $value;
                    $db_obj = $conn->sold_buy_orders->find($search);
                    $order_arr = iterator_to_array($db_obj);
                    $order_arr_all[$value] = $order_arr;
                }
                $data['full_arr'] = $order_arr_all;
                //exit;
            } else if ($filter == 'trigger') {
                $conn = $this->mongo_db->customQuery();
                $order_arr_all = array();
                $trigger_array = array("barrier_trigger", "barrier_percentile_trigger", "box_trigger");
                foreach ($coin_array as $key => $value) {
                    $search['symbol'] = $value;
                    foreach ($trigger_array as $key1 => $value_trigger) {
                        $search['trigger_type'] = $value_trigger;

                        $db_obj = $conn->sold_buy_orders->find($search);
                        $order_arr = iterator_to_array($db_obj);
                        $order_arr_all[$value][$value_trigger] = $order_arr;
                    }
                }
                $resultArr = $this->make_array_for_view($order_arr_all, 'trigger');
                $data['full_arr'] = $resultArr;
            } elseif ($filter == "rule") {
                if ($this->input->post('filter_by_trigger')) {
                    $search['trigger_type'] = $this->input->post('filter_by_trigger');
                }
                $conn = $this->mongo_db->customQuery();
                $order_arr_all = array();
                $trigger_array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);

                if ($this->input->post('filter_by_trigger') == 'barrier_trigger') {
                    $trigger_array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                } elseif ($this->input->post('filter_by_trigger') == 'barrier_percentile_trigger') {
                    $trigger_array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                }

                foreach ($coin_array as $key => $value) {
                    $search['symbol'] = $value;
                    foreach ($trigger_array as $key1 => $value_trigger) {

                        if ($this->input->post('filter_by_trigger') == 'barrier_trigger') {
                            $search['buy_rule_number'] = $value_trigger;
                        } elseif ($this->input->post('filter_by_trigger') == 'barrier_percentile_trigger') {
                            $search['order_level'] = "level_" . $value_trigger;
                        }

                        $db_obj = $conn->sold_buy_orders->find($search);
                        $order_arr = iterator_to_array($db_obj);
                        $order_arr_all[$value][$value_trigger] = $order_arr;
                    }
                }
                $resultArr = $this->make_array_for_view($order_arr_all, 'trigger');

                $data['full_arr'] = $resultArr;
            }

        }
        $coins = $this->mod_coins->get_all_coins();
        $data['coins'] = $coins;
        $this->stencil->paint('admin/reports/coin_order_report', $data);
    }

    public function make_array_for_view($order_arr, $values) {
        if ($values == 'trigger') {
            if (!empty($order_arr)) {

                foreach ($order_arr as $coin_key => $coin_arr) {
                    $coin = $coin_key;
                    $coin_count = 0;

                    foreach ($coin_arr as $key => $value) {
                        $trigger = $key;
                        $trigger_count = count($value);
                        $coin_count += $trigger_count;

                        $market_heighest_value = array_column($value, 'market_heighest_value');
                        $market_lowest_value = array_column($value, 'market_lowest_value');
                        $five_hour_max_market_price = array_column($value, '5_hour_max_market_price');
                        $five_hour_min_market_price = array_column($value, '5_hour_min_market_price');

                        $max_high = max($market_heighest_value);
                        $min_high = min($market_heighest_value);
                        $max_low = max($market_lowest_value);
                        $min_low = min($market_lowest_value);

                        $market_heighest_value = array_filter($market_heighest_value);
                        $max_high_average = array_sum($market_heighest_value) / count($market_heighest_value);

                        $market_lowest_value = array_filter($market_lowest_value);
                        $max_low_average = array_sum($market_lowest_value) / count($market_lowest_value);

                        $five_hour_max_market_price = array_filter($five_hour_max_market_price);
                        $high_five_average = array_sum($five_hour_max_market_price) / count($five_hour_max_market_price);

                        $five_hour_min_market_price = array_filter($five_hour_min_market_price);
                        $low_five_average = array_sum($five_hour_min_market_price) / count($five_hour_min_market_price);

                        $max_high_five = max($five_hour_max_market_price);
                        $min_high_five = min($five_hour_max_market_price);
                        $max_low_five = max($five_hour_min_market_price);
                        $min_low_five = min($five_hour_min_market_price);

                        $a = array_filter($a);
                        $average = array_sum($a) / count($a);

                        $avg_profit = 0;
                        $total_profit = 0;
                        $total_quantity = 0;
                        $winning = 0;
                        $losing = 0;
                        $top1 = 0;
                        $top2 = 0;
                        $bottom2 = 0;

                        $max_profit_per = array();
                        $min_profit_per1 = array();
                        $max_profit_pert = array();
                        $min_profit_per2 = array();

                        $opp = $this->calculate_no_of_oppurtunities($coin, $value);

                        foreach ($value as $col => $row) {
                            if (!empty($row)) {

                                if (isset($row['5_hour_max_market_price']) && $row['5_hour_max_market_price'] != '') {

                                    $five_hour_max_market_price = $row['5_hour_max_market_price'];
                                    $purchased_price = (float) $row['market_value'];
                                    $profit = $five_hour_max_market_price - $purchased_price;

                                    $profit_margin = ($profit / $five_hour_max_market_price) * 100;

                                    $max_profit_per_5 = ($profit) * (100 / $purchased_price);

                                    $max_profit_per[] = number_format($max_profit_per_5, 2);
                                }

                                if (isset($row['5_hour_min_market_price']) && $row['5_hour_min_market_price'] != '') {

                                    $market_lowest_value = $row['5_hour_min_market_price'];
                                    $purchased_price = (float) $row['market_value'];
                                    $profit = $market_lowest_value - $purchased_price;

                                    $profit_margin = ($profit / $market_lowest_value) * 100;

                                    $min_profit_per_5 = ($profit) * (100 / $purchased_price);
                                    $min_profit_per1[] = number_format($min_profit_per_5, 2);
                                }

                                if (isset($row['market_heighest_value']) && $row['market_heighest_value'] != '') {

                                    $five_hour_max_market_price1 = $row['market_heighest_value'];
                                    $purchased_price1 = (float) $row['market_value'];
                                    $profit1 = $five_hour_max_market_price1 - $purchased_price1;

                                    $profit_margin1 = ($profit1 / $five_hour_max_market_price1) * 100;

                                    $max_profit_per_t = ($profit1) * (100 / $purchased_price1);

                                    $max_profit_pert[] = number_format($max_profit_per_t, 2);
                                }

                                if (isset($row['market_lowest_value']) && $row['market_lowest_value'] != '') {

                                    $market_lowest_value2 = $row['market_lowest_value'];
                                    $purchased_price2 = (float) $row['market_value'];
                                    $profit2 = $market_lowest_value2 - $purchased_price2;

                                    $profit_margin2 = ($profit2 / $market_lowest_value2) * 100;

                                    $min_profit_per2_t = ($profit2) * (100 / $purchased_price2);
                                    $min_profit_per2[] = number_format($min_profit_per2_t, 2);
                                }

                                $total_sold_orders++;
                                $market_sold_price = $row['market_sold_price'];
                                $current_order_price = $row['market_value'];
                                $quantity = $row['quantity'];

                                $current_data2222 = $market_sold_price - $current_order_price;
                                $profit_data = ($current_data2222 * 100 / $market_sold_price);
                                if ($profit_data > 0) {
                                    $winning++;
                                } elseif ($profit_data < 0) {
                                    $losing++;
                                }

                                if ($profit_data >= 1 && $profit_data <= 2) {
                                    $top1++;
                                }
                                if ($profit_data >= 2) {
                                    $top2++;
                                }
                                if ($profit_data <= -2) {
                                    $bottom2++;
                                }
                                $profit_data = $profit_data; //- 0.4;
                                $profit_data = number_format((float) $profit_data, 2, '.', '');
                                $total_btc = $quantity * (float) $current_order_price;
                                $total_profit += $total_btc * $profit_data;
                                $total_quantity += $total_btc;
                            }
                        }
                        if ($total_quantity == 0) {
                            $total_quantity = 1;
                        }
                        $avg_profit = $total_profit / $total_quantity;

                        $max_profit_5h = (array_sum($max_profit_per) / count($max_profit_per));
                        $min_profit_5h = (array_sum($min_profit_per1) / count($min_profit_per1));
                        $max_profit_high = (array_sum($max_profit_pert) / count($max_profit_pert));
                        $min_profit_low = (array_sum($min_profit_per2) / count($min_profit_per2));

                        $retArr = array(
                            'trigger_count' => $trigger_count,
                            'avg_profit' => number_format($avg_profit, 2),
                            'max_high_average' => num($max_high_average),
                            'max_low_average' => num($max_low_average),
                            'high_five_average' => num($high_five_average),
                            'low_five_average' => num($low_five_average),
                            'max_profit_5h' => number_format($max_profit_5h, 2),
                            'min_profit_5h' => number_format($min_profit_5h, 2),
                            'max_profit_high' => number_format($max_profit_high, 2),
                            'min_profit_low' => number_format($min_profit_low, 2),
                            'winning_trades' => $winning,
                            'losing_trades' => $losing,
                            'top_1_per' => $top1,
                            'top_2_per' => $top2,
                            'bottom_2_per' => $bottom2,
                            'opp' => $opp,
                        );
                        $t_arr[$trigger] = $retArr;
                    }
                    $my_coin_data = $this->get_order_arr($coin_arr, $coin);
                    $t_arr['coin_meta'] = $my_coin_data;
                    $finalArray[$coin] = $t_arr;
                }

            }
        }
        //exit;
        // print_me($finalArray, "waqar");
        return $finalArray;
    }

    public function calculate_no_of_oppurtunities($coin, $trades) {
        $old_time = "";
        $op = 0;
        $tempArr = array();
        array_multisort(array_column($trades, "created_date"), SORT_ASC, $trades);
        foreach ($trades as $key => $value) {
            $time = $value['created_date']->toDateTime()->format("Y-m-d H");
            if ($time != $old_time) {
                $op++;
                //array_push($tempArr, $value);
                $old_time = $time;
                //array_push($tempArr, $time . ":00:00");
                $tempArr[$time][] = $value;
            } else {
                $tempArr[$time][] = $value;
            }
            //$tempArr[$time][] = $value;
        }
        $retArr = array();
        foreach ($tempArr as $key => $value) {
            $profit_data = 0;
            $total_btc = 0;
            $total_profit = 0;
            $total_quantity = 0;
            $avg_profit = 0;
            $total_sold_orders = 0;
            $max_profit_per = array();
            $min_profit_per1 = array();
            $max_profit_pert = array();
            $min_profit_per2 = array();
            foreach ($value as $key_1 => $valueArr) {
                $total_sold_orders++;

                if (isset($valueArr['5_hour_max_market_price']) && $valueArr['5_hour_max_market_price'] != '') {

                    $five_hour_max_market_price = $valueArr['5_hour_max_market_price'];
                    $purchased_price = (float) $valueArr['market_value'];
                    $profit = $five_hour_max_market_price - $purchased_price;

                    $profit_margin = ($profit / $five_hour_max_market_price) * 100;

                    $max_profit_per_5 = ($profit) * (100 / $purchased_price);

                    $max_profit_per[] = number_format($max_profit_per_5, 2);
                }

                if (isset($valueArr['5_hour_min_market_price']) && $valueArr['5_hour_min_market_price'] != '') {

                    $market_lowest_value = $valueArr['5_hour_min_market_price'];
                    $purchased_price = (float) $valueArr['market_value'];
                    $profit = $market_lowest_value - $purchased_price;

                    $profit_margin = ($profit / $market_lowest_value) * 100;

                    $min_profit_per_5 = ($profit) * (100 / $purchased_price);
                    $min_profit_per1[] = number_format($min_profit_per_5, 2);
                }

                if (isset($valueArr['market_heighest_value']) && $valueArr['market_heighest_value'] != '') {

                    $five_hour_max_market_price1 = $valueArr['market_heighest_value'];
                    $purchased_price1 = (float) $valueArr['market_value'];
                    $profit1 = $five_hour_max_market_price1 - $purchased_price1;

                    $profit_margin1 = ($profit1 / $five_hour_max_market_price1) * 100;

                    $max_profit_per_t = ($profit1) * (100 / $purchased_price1);

                    $max_profit_pert[] = number_format($max_profit_per_t, 2);
                }

                if (isset($valueArr['market_lowest_value']) && $valueArr['market_lowest_value'] != '') {

                    $market_lowest_value2 = $valueArr['market_lowest_value'];
                    $purchased_price2 = (float) $valueArr['market_value'];
                    $profit2 = $market_lowest_value2 - $purchased_price2;

                    $profit_margin2 = ($profit2 / $market_lowest_value2) * 100;

                    $min_profit_per2_t = ($profit2) * (100 / $purchased_price2);
                    $min_profit_per2[] = number_format($min_profit_per2_t, 2);
                }

                $market_sold_price = $valueArr['market_sold_price'];
                $current_order_price = $valueArr['market_value'];
                $quantity = $valueArr['quantity'];
                $current_data2222 = $market_sold_price - $current_order_price;
                $profit_data = ($current_data2222 * 100 / $market_sold_price);
                $profit_data = number_format((float) $profit_data, 2, '.', '');
                $total_btc = $quantity * (float) $current_order_price;
                $total_profit += $total_btc * $profit_data;
                $total_quantity += $total_btc;
            }
            if ($total_quantity == 0) {
                $total_quantity = 1;
            }
            $max_profit_5h = (array_sum($max_profit_per) / count($max_profit_per));
            $min_profit_5h = (array_sum($min_profit_per1) / count($min_profit_per1));
            $max_profit_high = (array_sum($max_profit_pert) / count($max_profit_pert));
            $min_profit_low = (array_sum($min_profit_per2) / count($min_profit_per2));
            $avg_profit = $total_profit / $total_quantity;

            $retArr['avg'][$key]['count'] = $total_sold_orders;
            $retArr['avg'][$key]['avg'] = $avg_profit;

            $retArr['avg'][$key]['max_profit_5h'] = $max_profit_5h;
            $retArr['avg'][$key]['min_profit_5h'] = $min_profit_5h;
            $retArr['avg'][$key]['max_profit_high'] = $max_profit_high;
            $retArr['avg'][$key]['min_profit_low'] = $min_profit_low;

            $five_hr_up_down = $this->get_high_low_value($coin, $key . ":00:00", date("Y-m-d H:i:s", strtotime("+5 hours", strtotime($key . ":00:00"))));
            $retArr['avg'][$key]['high_low'] = $five_hr_up_down;

        }
        // echo "<pre>";
        // print_r($tempArr);

        //print_me($retArr['avg'], 'waqar');
        $retArr['op'] = $op;
        return $retArr;
    }
    public function test_cases_listing(){
        $data['coins'] = 'hello';
        $this->stencil->paint('admin/reports/test_cases_listing', $data);
    }
    public function get_test_case_one_users($exchange = ''){
        if($exchange == '' || $exchange == 'binance'){
            $exchange = 'binance';
            $collection_name = 'buy_orders';
        }else if($exchange == 'kraken'){
            $collection_name = 'buy_orders_kraken';
        }
        $pipeline = [
                [
                    '$match'=> [
                        'admin_id'=>['$ne'=>''],
                        'symbol'=>['$exists'=>true],
                        'cost_avg'=>['$in'=>['yes','taking_child']],
                        'cavg_parent'=>'yes',
                        'parent_status'=>['$ne'=>'parent'],
                        'status'=>['$nin'=>['canceled','KEYFILLED_ERROR','FILLED_ERROR','ERROR']],
                        'application_mode'=>'live'
                    ]
                ], 
                [
                    '$project'=> [
                        '_id'=>0,
                        'admin_id'=>1,
                        'symbol'=>1,
                    ]
                ], 
                [
                    '$group'=> [
                        '_id'=>['admin_id'=>'$admin_id','symbol'=>'$symbol'],
                    ]
                ]
        ];
        $db = $this->mongo_db->customQuery();
        $users_id_and_array_raw = $db->$collection_name->aggregate($pipeline);
        $users_id_and_array = iterator_to_array($users_id_and_array_raw);
        //echo '<pre>';print_r($users_id_and_array);
        $created_date   = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s', strtotime('-1 year')));
        $database_array = array();
        //just remove the documents from collection on condition where is_fixed != true so fixed orders stay in the database then remove all the not fixed users and from my below script it will add them again by searching new if the previous user is auto fixed then he or she will be removed from temp collection
        $where_arr = array(
            'is_fixed' => ['$ne'=>true],
            'test_case' => ['$eq'=>'TC1'],
            'exchange' => ['$eq'=>$exchange],
        );
        $db->test_cases_users->deleteMany($where_arr);
        foreach ($users_id_and_array as $value) {
            //echo '<pre>';print_r($value->_id['admin_id']);exit;
            $pipeline_2 = [
                [
                    '$match'=>[
                        'admin_id'=>(string)$value->_id['admin_id'],
                        'cavg_parent'=>['$ne'=>'yes'],
                        'cost_avg'=>['$nin'=>['taking_child','yes','parent','completed']],
                        'symbol'=>(string)$value->_id['symbol'],
                        'parent_status'=>['$ne'=>'parent'],
                        'is_sell_order'=>['$ne'=>'sold'],
                        'created_date'=>['$gte'=>$created_date],
                        'status'=>['$in'=>['FILLED','LTH']],
                        'trigger_type'=>'barrier_percentile_trigger',
                        'application_mode'=>'live'
                    ]
                ]
            ];
            $get_result = $db->$collection_name->aggregate($pipeline_2);
            $get_result_array = iterator_to_array($get_result);
            if(count($get_result_array) > 0){
                $user_arr = $this->mod_users->get_user((string)$value->_id['admin_id']);
                $database_array['username'] = $user_arr['username'];
                $database_array['first_name'] = $user_arr['first_name'];
                $database_array['last_name'] = $user_arr['last_name'];
                $database_array['email_address'] = $user_arr['email_address'];
                $database_array['exchange'] = $exchange;
                $database_array['user_id'] = (string)$value->_id['admin_id'];
                $database_array['symbol'] = $value->_id['symbol'];
                $database_array['orders_count'] = count($get_result_array);
                $database_array['is_fixed'] = false;
                $database_array['test_case'] = 'TC1';
                //echo '<pre>';print_r($database_array);echo '<pre>';
                $db->test_cases_users->updateOne(['user_id'=>(string)$value->_id['admin_id'],'symbol'=>$value->_id['symbol'],'exchange'=>$exchange,'test_case'=>'TC1'],['$set'=>$database_array],['upsert'=>true]); // updating the user in the database so support or QA can fix those after watching listing.
                $database_array = array();
            }
        } // end foreach
        $this->test_cases_listing();
        //exit;
    }//end function
    public function get_TC1_users(){
        $this->mod_login->verify_is_admin_login();
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }

        if($this->input->post()){

            $this->session->unset_userdata('filters_users');
            $filter_data['filters_users'] = $this->input->post();
            $this->session->set_userdata($filter_data);
        }
        $db = $this->mongo_db->customQuery();
        $where_arr = array(
            'is_fixed' => ['$ne'=>true],
            'test_case' => ['$eq'=>'TC1'],
        );
        $users_data = $this->session->userdata('filters_users');
        if(isset($users_data['username']) && $users_data['username'] != ''){
            $where_arr['username']=new MongoDB\BSON\Regex(".*{$users_data['username']}.*", 'i');
        }
        if(isset($users_data['symbol']) && $users_data['symbol'] != ''){
            $where_arr['symbol']=new MongoDB\BSON\Regex(".*{$users_data['symbol']}.*", 'i');
        }
        if(isset($users_data['id']) && $users_data['id'] != ''){
            $where_arr['user_id']=strtolower((string)$users_data['id']);
        }
        if(isset($users_data['is_fixed']) && $users_data['is_fixed'] != '' && $users_data['is_fixed'] == 1){
            $where_arr['is_fixed']=true;
        }else{
            $where_arr['is_fixed']=false;
        }
        if(isset($users_data['exchange']) && $users_data['exchange'] != '' && $users_data['exchange'] == 'binance'){
            $where_arr['exchange']='binance';
        }else{
            $where_arr['exchange']='kraken';
        }
        
        $data_returnCount =  $db->test_cases_users->count($where_arr);
        if ($this->input->post('csv') == 'csv') {
            //echo 'ajshdjkahdjkas';
            $tokensarr  = $this->mod_report->get_all_testcase_users_for_csv($where_arr);
                //echo '<pre>'; print_r($tokensarr);exit;
            if ($tokensarr) {
                $filename = ("Test Case 1 Report:" . date("Y-m-d Gis") . ".csv");
                $now = gmdate("D, d M Y H:i:s"); // Set the Headers for csv
                header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
                header("Last-Modified: {$now} GMT");
                header('Content-Type: text/csv;');
                header("Pragma: no-cache");
                header("Expires: 0");
                header("Content-Type: application/force-download"); // force download
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename={$filename}"); // disposition / encoding on response body
                header("Content-Transfer-Encoding: binary");
                echo array2csv($tokensarr);
                exit;
            }
        }
        $data['total']        =   $data_returnCount;
        $config['base_url']           =   base_url() .'admin/Reports/get_TC1_users';
        $config['total_rows']         =   $data_returnCount;
        $config['per_page']           =   30;
        $config['num_links']          =   3;
        $config['use_page_numbers']   =   TRUE;
        $config['uri_segment']        =   4;
        $config['reuse_query_string'] =   TRUE;
        $config['next_link']          =   '&raquo;';
        $config['next_tag_open']      =   '<li>';
        $config['next_tag_close']     =   '</li>';
        $config['prev_link']          =   '&laquo;';
        $config['prev_tag_open']      =   '<li>';
        $config['prev_tag_close']     =   '</li>';
        $config['first_tag_open']     =   '<li>';
        $config['first_tag_close']    =   '</li>';
        $config['last_tag_open']      =   '<li>';
        $config['last_tag_close']     =   '</li>';
        $config['full_tag_open']      =   '<ul class="pagination">';
        $config['full_tag_close']     =   '</ul>';
        $config['cur_tag_open']       =   '<li class="active"><a href="#"><b>';
        $config['cur_tag_close']      =   '</b></a></li>'; 
        $config['num_tag_open']       =   '<li>';
        $config['num_tag_close']      =   '</li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        if($page !=0) {
        $page = ($page-1) * $config['per_page'];
        }
        $data["links"] = $this->pagination->create_links();
        $condition = ['skip'=>$page,'sort'=>['first_name' =>1],'limit' => $config['per_page']];
        $get_users_result = $db->test_cases_users->find($where_arr,$condition);
        $data['TC1_users'] = iterator_to_array($get_users_result);
        $this->stencil->paint('admin/reports/test_case_TC1', $data);
    }
    public function resetFilters(){
        $this->session->unset_userdata('filters_users');
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }
        $this->get_TC1_users();
    }
    public function markUserFixed(){
        $doc_id = $this->input->post('doc_id');
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }
        $custom = $this->mongo_db->customQuery();
        $search_array = ['_id'=> $this->mongo_db->mongoId((string)$doc_id)];
        $this->mod_login->verify_is_admin_login();
            $upd_arr['is_fixed'] = true; 
            $check_if_doc_id_exists   =   $custom->test_cases_users->count($search_array); // checking if document id exists or not if not then dont update
            if($check_if_doc_id_exists > 0 ){
               $custom->test_cases_users->updateOne($search_array, array('$set' => $upd_arr));
               $json_array['success'] = true;
            }else{
                $json_array['success'] = false;
            }
            echo json_encode($json_array);
            exit;
    }

    public function markUserStatus(){
        $doc_id = $this->input->post('doc_id');
        $status = $this->input->post('status');
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }
        $custom = $this->mongo_db->customQuery();
        $search_array = ['_id'=> $this->mongo_db->mongoId((string)$doc_id)];
        $this->mod_login->verify_is_admin_login();
            $upd_arr['status'] = $status; 
            $check_if_doc_id_exists   =   $custom->test_cases_users->count($search_array); 
            if($check_if_doc_id_exists > 0 ){
               $custom->test_cases_users->updateOne($search_array, array('$set' => $upd_arr));
               $json_array['success'] = true;
            }else{
                $json_array['success'] = false;
            }
            echo json_encode($json_array);
            exit;
    }

      public function get_test_case_two_users(){
        $connection = $this->mongo_db->customQuery(); 
        $endMongoTime = $this->mongo_db->converToMongodttime(date('2021-12-31 00:00:00'));
        $letPipeline_2=[
                [
                '$match'=>['status'=>['$in'=>['FILLED_ERROR','canceled','APIKEY_ERROR']],
                      'modified_date'=>['$gte'=>$endMongoTime],
                      'transaction_logs'=>['$exists'=>true],
                      'parent_status'=>['$ne'=>"parent"],
                       'trigger_type'=>'barrier_percentile_trigger',
                        'application_mode'=>'live',
                      'transaction_logs'=>['$elemMatch'=>['errorString'=>'Could not execute request! #7 ([ETrade:User Locked])']]]], 
                ['$group'=> [
                      '_id'=>'$admin_id',
                      ]
                ]
        ];
        $orders = $connection->buy_orders_kraken->aggregate($letPipeline_2);
        $buy_orders = iterator_to_array($orders);
        if(count($buy_orders) > 0){
            $where_arr = array(
            'is_fixed' => ['$ne'=>true],
            'test_case' => ['$eq'=>'TC2'],
            'exchange' => ['$eq'=>'kraken'],
            );
        $connection->test_cases_users->deleteMany($where_arr);
            foreach ($buy_orders as $value) {
                   $letPipeline_3=[
                            [
                            '$match'=>['status'=>['$in'=>['FILLED_ERROR','canceled','APIKEY_ERROR']],
                                  'admin_id'=>$value['_id'],
                                  'modified_date'=>['$gte'=>$endMongoTime],
                                  'transaction_logs'=>['$exists'=>true],
                                  'parent_status'=>['$ne'=>"parent"],
                                   'trigger_type'=>'barrier_percentile_trigger',
                                    'application_mode'=>'live',
                                  'transaction_logs'=>['$elemMatch'=>['errorString'=>'Could not execute request! #7 ([ETrade:User Locked])']]]],
                            [
                                '$sort'=>['_id'=>-1]
                            ], 
                            [
                                '$limit'=>1
                            ],
                          
                            [
                                '$project'=>['_id'=>0,
                                            'update_date_cancel'=>1]
                            ]
                    ];
                    $user_order = $connection->buy_orders_kraken->aggregate($letPipeline_3);
                    $user_order_array = iterator_to_array($user_order);
                    $user_arr = $this->mod_users->get_user((string)$value['_id']);
                    $database_array['username'] = $user_arr['username'];
                    $database_array['first_name'] = $user_arr['first_name'];
                    $database_array['last_name'] = $user_arr['last_name'];
                    $database_array['email_address'] = $user_arr['email_address'];
                    $database_array['exchange'] = 'kraken';
                    $database_array['user_id'] = (string)$value['_id'];
                    $database_array['is_fixed'] = false;
                    $database_array['Latest_Locked_date'] = isset($user_order_array[0]['update_date_cancel'])?date("Y-m-d H:i:s", (int)((string)$user_order_array[0]['update_date_cancel'])/1000):'';
                    $database_array['test_case'] = 'TC2';
                    //echo '<pre>';print_r($database_array);echo '<pre>';
                    $connection->test_cases_users->updateOne(['user_id'=>(string)$value['_id'],'exchange'=>'kraken','test_case'=>'TC2'],['$set'=>$database_array],['upsert'=>true]); // updating the user in the database so support or QA can fix those after watching listing.
                    $database_array = array();
            } // end foreach
        }
        $this->test_cases_listing();
    }
    public function get_TC2_users(){
        $this->mod_login->verify_is_admin_login();
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }

        if($this->input->post()){

            $this->session->unset_userdata('filters_users_tc2');
            $filter_data['filters_users_tc2'] = $this->input->post();
            $this->session->set_userdata($filter_data);
        }
        $db = $this->mongo_db->customQuery();
        $where_arr = array(
            'is_fixed' => ['$ne'=>true],
            'test_case' => ['$eq'=>'TC2'],
        );
        $users_data = $this->session->userdata('filters_users_tc2');
        if(isset($users_data['username']) && $users_data['username'] != ''){
            $where_arr['username']=new MongoDB\BSON\Regex(".*{$users_data['username']}.*", 'i');
        }
        if(isset($users_data['symbol']) && $users_data['symbol'] != ''){
            $where_arr['symbol']=new MongoDB\BSON\Regex(".*{$users_data['symbol']}.*", 'i');
        }
        if(isset($users_data['id']) && $users_data['id'] != ''){
            $where_arr['user_id']=strtolower((string)$users_data['id']);
        }
        if(isset($users_data['is_fixed']) && $users_data['is_fixed'] != '' && $users_data['is_fixed'] == 1){
            $where_arr['is_fixed']=true;
        }else{
            $where_arr['is_fixed']=false;
        }
        if(isset($users_data['exchange']) && $users_data['exchange'] != '' && $users_data['exchange'] == 'binance'){
            $where_arr['exchange']='binance';
        }else{
            $where_arr['exchange']='kraken';
        }
        
        $data_returnCount =  $db->test_cases_users->count($where_arr);
        // if ($this->input->post('csv') == 'csv') {
        //     //echo 'ajshdjkahdjkas';
        //     $tokensarr  = $this->mod_tokens->get_all_tokens_for_csv($where_arr);
        //         //echo '<pre>'; print_r($tokensarr);exit;
        //     if ($tokensarr) {
        //         $filename = ("Tokens Report:" . date("Y-m-d Gis") . ".csv");
        //         $now = gmdate("D, d M Y H:i:s"); // Set the Headers for csv
        //         header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        //         header("Last-Modified: {$now} GMT");
        //         header('Content-Type: text/csv;');
        //         header("Pragma: no-cache");
        //         header("Expires: 0");
        //         header("Content-Type: application/force-download"); // force download
        //         header("Content-Type: application/octet-stream");
        //         header("Content-Type: application/download");
        //         header("Content-Disposition: attachment;filename={$filename}"); // disposition / encoding on response body
        //         header("Content-Transfer-Encoding: binary");
        //         echo array2csv($tokensarr);
        //         exit;
        //     }
        // }
        $data['total']        =   $data_returnCount;
        $config['base_url']           =   base_url() .'admin/Reports/get_TC2_users';
        $config['total_rows']         =   $data_returnCount;
        $config['per_page']           =   30;
        $config['num_links']          =   3;
        $config['use_page_numbers']   =   TRUE;
        $config['uri_segment']        =   4;
        $config['reuse_query_string'] =   TRUE;
        $config['next_link']          =   '&raquo;';
        $config['next_tag_open']      =   '<li>';
        $config['next_tag_close']     =   '</li>';
        $config['prev_link']          =   '&laquo;';
        $config['prev_tag_open']      =   '<li>';
        $config['prev_tag_close']     =   '</li>';
        $config['first_tag_open']     =   '<li>';
        $config['first_tag_close']    =   '</li>';
        $config['last_tag_open']      =   '<li>';
        $config['last_tag_close']     =   '</li>';
        $config['full_tag_open']      =   '<ul class="pagination">';
        $config['full_tag_close']     =   '</ul>';
        $config['cur_tag_open']       =   '<li class="active"><a href="#"><b>';
        $config['cur_tag_close']      =   '</b></a></li>'; 
        $config['num_tag_open']       =   '<li>';
        $config['num_tag_close']      =   '</li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        if($page !=0) {
        $page = ($page-1) * $config['per_page'];
        }
        $data["links"] = $this->pagination->create_links();
        $condition = ['skip'=>$page,'sort'=>['first_name' =>1],'limit' => $config['per_page']];
        $get_users_result = $db->test_cases_users->find($where_arr,$condition);
        $data['TC2_users'] = iterator_to_array($get_users_result);
        $this->stencil->paint('admin/reports/test_case_TC2', $data);
    }
    public function resetFilters_tc2(){
        $this->session->unset_userdata('filters_users_tc2');
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }
        $this->get_TC2_users();
    }
    public function test_case_filled_error($exchange = ''){
        if($exchange == '' || $exchange == 'binance'){
            $exchange = 'binance';
            $collection = 'buy_orders';
        }else{
            $exchange = 'kraken';
            $collection = 'buy_orders_kraken';
        }
        $endMongoTime = $this->mongo_db->converToMongodttime(date('2022-08-01 00:00:00'));
        $piepline=[
            [
                '$match'=> [
                    'modified_date'=>['$gte'=>$endMongoTime]
                    ,'status'=>"FILLED_ERROR"
                ]
            ],[
                '$group'=> [
                    '_id'=> ['admin_id'=>'$admin_id','symbol'=>'$symbol'],
                    'count_orders'=> [
                        '$sum'=>1
                    ]
                ]
            ]
        ];
    $db = $this->mongo_db->customQuery();
    $users_object = $db->$collection->aggregate($piepline);
    $users_array = iterator_to_array($users_object);
    foreach ($users_array as  $value) {
    $user_arr = $this->mod_users->get_user((string)$value['_id']['admin_id']);
        $database_array['username'] = $user_arr['username'];
        $database_array['first_name'] = $user_arr['first_name'];
        $database_array['last_name'] = $user_arr['last_name'];
        $database_array['email_address'] = $user_arr['email_address'];
        $database_array['exchange'] = $exchange;
        $database_array['user_id'] = (string)$value['_id']['admin_id'];
        $database_array['symbol'] = $value['_id']['symbol'];
        $database_array['orders_count'] = (int)$value['count_orders'];
        $database_array['is_fixed'] = false;
        $database_array['test_case'] = 'TC3'; 
        $db->test_cases_users->updateOne(['user_id'=>(string)$value['_id']['admin_id'],'symbol'=>$value['_id']['symbol'],'exchange'=>$exchange,'test_case'=>'TC3'],['$set'=>$database_array],['upsert'=>true]); // updating the user in the database so support or QA can fix those after watching listing.
                $database_array = array();
    }// end foreach
    $this->test_cases_listing();
    }
    public function get_TC3_users(){
        $this->mod_login->verify_is_admin_login();
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }

        if($this->input->post()){

            $this->session->unset_userdata('filters_users_tc3');
            $filter_data['filters_users_tc3'] = $this->input->post();
            $this->session->set_userdata($filter_data);
        }
        $db = $this->mongo_db->customQuery();
        $where_arr = array(
            'is_fixed' => ['$ne'=>true],
            'test_case' => ['$eq'=>'TC3'],
        );
        $users_data = $this->session->userdata('filters_users_tc3');
        if(isset($users_data['username']) && $users_data['username'] != ''){
            $where_arr['username']=new MongoDB\BSON\Regex(".*{$users_data['username']}.*", 'i');
        }
        if(isset($users_data['symbol']) && $users_data['symbol'] != ''){
            $where_arr['symbol']=new MongoDB\BSON\Regex(".*{$users_data['symbol']}.*", 'i');
        }
        if(isset($users_data['id']) && $users_data['id'] != ''){
            $where_arr['user_id']=strtolower((string)$users_data['id']);
        }
        if(isset($users_data['is_fixed']) && $users_data['is_fixed'] != '' && $users_data['is_fixed'] == 1){
            $where_arr['is_fixed']=true;
        }else{
            $where_arr['is_fixed']=false;
        }
        if(isset($users_data['exchange']) && $users_data['exchange'] != '' && $users_data['exchange'] == 'binance'){
            $where_arr['exchange']='binance';
        }else{
            $where_arr['exchange']='kraken';
        }
        $data_returnCount =  $db->test_cases_users->count($where_arr);
        if ($this->input->post('csv') == 'csv') {
            //echo 'ajshdjkahdjkas';
            $tokensarr  = $this->mod_report->get_all_testcase_users_for_csv($where_arr);
                //echo '<pre>'; print_r($tokensarr);exit;
            if ($tokensarr) {
                $filename = ("Test Case 3 Report:" . date("Y-m-d Gis") . ".csv");
                $now = gmdate("D, d M Y H:i:s"); // Set the Headers for csv
                header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
                header("Last-Modified: {$now} GMT");
                header('Content-Type: text/csv;');
                header("Pragma: no-cache");
                header("Expires: 0");
                header("Content-Type: application/force-download"); // force download
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename={$filename}"); // disposition / encoding on response body
                header("Content-Transfer-Encoding: binary");
                echo array2csv($tokensarr);
                exit;
            }
        }
        $data['total']        =   $data_returnCount;
        $config['base_url']           =   base_url() .'admin/Reports/get_TC3_users';
        $config['total_rows']         =   $data_returnCount;
        $config['per_page']           =   30;
        $config['num_links']          =   3;
        $config['use_page_numbers']   =   TRUE;
        $config['uri_segment']        =   4;
        $config['reuse_query_string'] =   TRUE;
        $config['next_link']          =   '&raquo;';
        $config['next_tag_open']      =   '<li>';
        $config['next_tag_close']     =   '</li>';
        $config['prev_link']          =   '&laquo;';
        $config['prev_tag_open']      =   '<li>';
        $config['prev_tag_close']     =   '</li>';
        $config['first_tag_open']     =   '<li>';
        $config['first_tag_close']    =   '</li>';
        $config['last_tag_open']      =   '<li>';
        $config['last_tag_close']     =   '</li>';
        $config['full_tag_open']      =   '<ul class="pagination">';
        $config['full_tag_close']     =   '</ul>';
        $config['cur_tag_open']       =   '<li class="active"><a href="#"><b>';
        $config['cur_tag_close']      =   '</b></a></li>'; 
        $config['num_tag_open']       =   '<li>';
        $config['num_tag_close']      =   '</li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        if($page !=0) {
        $page = ($page-1) * $config['per_page'];
        }
        $data["links"] = $this->pagination->create_links();
        $condition = ['skip'=>$page,'sort'=>['first_name' =>1],'limit' => $config['per_page']];
        $get_users_result = $db->test_cases_users->find($where_arr,$condition);
        $data['TC3_users'] = iterator_to_array($get_users_result);
        //echo '<pre>';print_r($data);exit;
        $this->stencil->paint('admin/reports/test_case_TC3', $data);
    }
    public function resetFilters_tc3(){
        $this->session->unset_userdata('filters_users_tc3');
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }
        $this->get_TC3_users();
    }
public function test_case_accumulation_finding($acc_type = '', $exchange = ''){ // script to find the abnormal accumulations 

        if($exchange == '' || $exchange == 'binance'){
            $exchange = 'binance';
            $collection = 'sold_buy_orders';
        }else{
            $exchange = 'kraken';
            $collection = 'sold_buy_orders_kraken';
        }
        $tc = '';
        if($acc_type == 'positive'){
            $search = ['profit'=>-1];
            $tc = 'TC4';
        }else{
            $search = ['profit'=>1];
            $tc = 'TC5';
        }
        //$endMongoTime = $this->mongo_db->converToMongodttime(date('2022-08-01 00:00:00'));
        $db = $this->mongo_db->customQuery();
        $where_arr = array(
            'is_fixed' => ['$eq'=>false],
            'test_case' => ['$eq'=>$tc],
            'exchange' => ['$eq'=>$exchange],
            );
        $db->test_cases_users->deleteMany($where_arr);
        $piepline=[
                    [
                        '$match'=>[
                          'accumulations.profit'=>['$exists'=>true],
                          'sell_date'=>['$exists'=>true],
                          'sell_date'=>['$ne'=>null],
                        ]
                    ], [
                        '$project'=> [
                              'admin_id'=>1,
                              'symbol'=>1,
                              'profit'=>'$accumulations.profit',
                              'sell_date' =>1,
                              'cointype'=> ['$substr'=> [ '$symbol', [ '$subtract'=> [ ['$strLenCP'=>'$symbol'], 3 ] ], -1 ]],
                            ]
                        ],[
                            '$match'=> [
                              'cointype'=>'SDT'
                            ]
                        ],[
                            '$sort'=> $search
                          ],[
                            '$limit'=> 20 
                          ]
                    ];
    
    $users_object = $db->$collection->aggregate($piepline);
    $users_array = iterator_to_array($users_object);
    // echo '<pre>'; print_r($users_array); exit;
    $pieplinebtc=[
                    [
                        '$match'=>[
                          'accumulations.profit'=>['$exists'=>true]
                        ]
                    ], [
                        '$project'=> [
                              'admin_id'=>1,
                              'symbol'=>1,
                              'profit'=>'$accumulations.profit',
                              'cointype'=> ['$substr'=> [ '$symbol', [ '$subtract'=> [ ['$strLenCP'=>'$symbol'], 3 ] ], -1 ]],
                            ]
                        ],[
                            '$match'=> [
                              'cointype'=>'BTC'
                            ]
                        ],[
                            '$sort'=>$search 
                          ],[
                            '$limit'=> 20 
                          ]
                    ];
    
    $users_object_btc = $db->$collection->aggregate($pieplinebtc);
    $users_array_btc = iterator_to_array($users_object_btc);
    //echo '<pre>';print_r($users_array);exit;
    $users_array_merged = array_merge($users_array,$users_array_btc);
    foreach ($users_array_merged as  $value) {
    $user_arr = $this->mod_users->get_user((string)$value['admin_id']);
        $database_array['username'] = $user_arr['username'];
        $database_array['first_name'] = $user_arr['first_name'];
        $database_array['last_name'] = $user_arr['last_name'];
        $database_array['email_address'] = $user_arr['email_address'];
        $database_array['exchange'] = $exchange;
        $database_array['user_id'] = (string)$value['admin_id'];
        $database_array['symbol'] = $value['symbol'];
        $database_array['symbol_type'] = $value['cointype'];
        $database_array['accumulations'] = $value['profit'];
        $database_array['order_id'] = (string)$value['_id'];
        $database_array['is_fixed'] = false;
        $database_array['test_case'] = $tc; 
        $database_array['sell_date'] = $value['sell_date'];
        $db->test_cases_users->updateOne(['user_id'=>(string)$value['admin_id'],'symbol'=>$value['symbol'],'exchange'=>$exchange,'test_case'=>'TC5','symbol_type'=>$value['cointype']],['$set'=>$database_array],['upsert'=>true]); // updating the user in the database so support or QA can fix those after watching listing.
        // echo "<br>";
        // echo '<pre>';print_r($database_array);
                $database_array = array();
    }// end foreach
    $this->test_cases_listing();
    }
    public function get_TC4_TC5_users(){
        $this->mod_login->verify_is_admin_login();
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }

        if($this->input->post()){

            $this->session->unset_userdata('filters_users_tc5');
            $filter_data['filters_users_tc5'] = $this->input->post();
            $this->session->set_userdata($filter_data);
        }
        $db = $this->mongo_db->customQuery();
        if(isset($_GET['tc'])){
            $tc = $_GET['tc'];
        }else{
            $tc = 'TC5';
        }
        
        $where_arr = array(
            'is_fixed' => ['$ne'=>true],
            'test_case' => ['$eq'=>$tc],
        );
        $users_data = $this->session->userdata('filters_users_tc5');
        if(isset($users_data['username']) && $users_data['username'] != ''){
            $where_arr['username']=new MongoDB\BSON\Regex(".*{$users_data['username']}.*", 'i');
        }
        if(isset($users_data['symbol']) && $users_data['symbol'] != ''){
            $where_arr['symbol']=new MongoDB\BSON\Regex(".*{$users_data['symbol']}.*", 'i');
        }
        if(isset($users_data['id']) && $users_data['id'] != ''){
            $where_arr['user_id']=strtolower((string)$users_data['id']);
        }
        if(isset($users_data['is_fixed']) && $users_data['is_fixed'] != '' && $users_data['is_fixed'] == 1){
            $where_arr['is_fixed']=true;
        }else{
            $where_arr['is_fixed']=false;
        }
        if(isset($users_data['exchange']) && $users_data['exchange'] != '' && $users_data['exchange'] == 'binance'){
            $where_arr['exchange']='binance';
        }else{
            $where_arr['exchange']='kraken';
        }
        if(isset($users_data['category']) && $users_data['category'] != '' && $users_data['category'] == 'BTC'){
            $where_arr['symbol_type']='BTC';
        }elseif(isset($users_data['category']) && $users_data['category'] != '' && $users_data['category'] == 'SDT'){
            $where_arr['symbol_type']='SDT';
        }
        $data_returnCount =  $db->test_cases_users->count($where_arr);
        // if ($this->input->post('csv') == 'csv') {
        //     //echo 'ajshdjkahdjkas';
        //     $tokensarr  = $this->mod_report->get_all_testcase_users_for_csv($where_arr);
        //         //echo '<pre>'; print_r($tokensarr);exit;
        //     if ($tokensarr) {
        //         $filename = ("Test Case 4 Report:" . date("Y-m-d Gis") . ".csv");
        //         $now = gmdate("D, d M Y H:i:s"); // Set the Headers for csv
        //         header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        //         header("Last-Modified: {$now} GMT");
        //         header('Content-Type: text/csv;');
        //         header("Pragma: no-cache");
        //         header("Expires: 0");
        //         header("Content-Type: application/force-download"); // force download
        //         header("Content-Type: application/octet-stream");
        //         header("Content-Type: application/download");
        //         header("Content-Disposition: attachment;filename={$filename}"); // disposition / encoding on response body
        //         header("Content-Transfer-Encoding: binary");
        //         echo array2csv($tokensarr);
        //         exit;
        //     }
        // }
        $data['total']        =   $data_returnCount;
        $config['base_url']           =   base_url() .'admin/Reports/get_TC4_TC5_users';
        $config['total_rows']         =   $data_returnCount;
        $config['per_page']           =   30;
        $config['num_links']          =   3;
        $config['use_page_numbers']   =   TRUE;
        $config['uri_segment']        =   4;
        $config['reuse_query_string'] =   TRUE;
        $config['next_link']          =   '&raquo;';
        $config['next_tag_open']      =   '<li>';
        $config['next_tag_close']     =   '</li>';
        $config['prev_link']          =   '&laquo;';
        $config['prev_tag_open']      =   '<li>';
        $config['prev_tag_close']     =   '</li>';
        $config['first_tag_open']     =   '<li>';
        $config['first_tag_close']    =   '</li>';
        $config['last_tag_open']      =   '<li>';
        $config['last_tag_close']     =   '</li>';
        $config['full_tag_open']      =   '<ul class="pagination">';
        $config['full_tag_close']     =   '</ul>';
        $config['cur_tag_open']       =   '<li class="active"><a href="#"><b>';
        $config['cur_tag_close']      =   '</b></a></li>'; 
        $config['num_tag_open']       =   '<li>';
        $config['num_tag_close']      =   '</li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        if($page !=0) {
        $page = ($page-1) * $config['per_page'];
        }
        $data["links"] = $this->pagination->create_links();
        $condition = ['skip'=>$page,'sort'=>['sell_date' =>-1],'limit' => $config['per_page']];
        $get_users_result = $db->test_cases_users->find($where_arr,$condition);
        $data['TC5_users'] = iterator_to_array($get_users_result);
        $data['test_case'] = $tc;
        //echo '<pre>';print_r($data);exit;
        $this->stencil->paint('admin/reports/test_case_TC5', $data);
    }
    public function resetFilters_tc5(){
        $this->session->unset_userdata('filters_users_tc5');
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }
        // $this->get_TC3_users();
        $this->get_TC4_TC5_users();
    }
    public function get_user_info()
	{
		$id = $this->input->post('user_id');
		$customer = $this->mod_report->get_customer($id);
        $lastLoginUTC = $customer['last_login_datetime']; // Assuming $customer['last_login_datetime'] is an instance of UTCDateTime
        $lastLoginDateTime = $lastLoginUTC->toDateTime(); // Convert UTCDateTime to DateTime
        $lastLoginFormatted = $lastLoginDateTime->format("Y-m-d g:i:s A"); // Format the DateTime object
        
		$response = '<div class="col-12 col-sm-6 col-md-4 col-lg-12">
								<div class="our-team">
								<div class="picture">
									<img class="img-fluid" src="' . SURL . "assets/profile_images/" . (!empty($customer['profile_image']) ? $customer['profile_image'] : "user.png") . '">
								</div>
								<div class="team-content">
									<h3 class="name">' . ucfirst($customer['first_name']) . ' ' . ucfirst($customer['last_name']) . '</h3>
                                    <br>
											<h5><b>Username: </b>' . $customer['username'] . '</h5>
                                            <h4 class="title">Last Login:' . $lastLoginFormatted . '</h4>

                                    
                                    <h4 class="title">Created Date: ' . $customer['created_date_human'] . '</h4>
								</div>
								</div>
							</div>
								<div class="table-responsive">
										<table class="table">
											<tr>
												<th>User Id</td>
												<td>' . $customer['_id'] . '</td>
											<tr>

											<tr>
												<th>Email Address</td>
												<td>' . $customer['email_address'] . '</td>
											<tr>

											<tr>
												<th>Trading Ip</td>
												<td>' . $customer['trading_ip'] . '</td>
											<tr>
											<tr>
												<th>Application Mode</td>
												<td>' . $customer['application_mode'] . '</td>
											<tr>
											<tr>
												<th></td>
												<td>' . (($customer['special_role'] == 1) ? "<label class='label label-success'>Special User</label>" : "<label class='label label-warning'>Normal User</label>") . '</td>
											<tr>
                                            <tr>
												<th>Country</td>
												<td>' . $customer['country'] . '</td>
											<tr>
                                            <tr>
												<th>Phone</td>
												<td>' . $customer['phone_number']. '</td>
											<tr>
                                            <tr>
												<th>Timezone</td>
												<td>' . $customer['timezone'] . '</td> 
											<tr>
										</table>
									</div>';

		echo $response;
		exit;
	}
    public function test_case_consumed_users($exchange = ''){

        if($exchange == '' || $exchange == 'binance'){
            $exchange = 'binance';
            $collection = 'user_investment_binance';
        }else{
            $exchange = 'kraken';
            $collection = 'user_investment_kraken';
        }
        $date_find = date('Y-m-d',strtotime('-7 days'));
        $endMongoTime = $this->mongo_db->converToMongodttime($date_find);
        $piepline=[[
                '$match'=> [
                    '$or' =>[
                        ['$and'=>[['perc_used_btc_total'=>['$gt'=>99]]]],['$and'=>[['perc_used_usdt_total'=>['$gt'=>99]]]]
                    ]
                ]
            ]
        ];
    $db = $this->mongo_db->customQuery();
    // $db->test_cases_users->updateMany([],['$unset' => ["perc_100_used_btc_date" => 1]]);
    // $db->test_cases_users->updateMany([],['$unset' => ["perc_100_used_usdt_date" => 1]]);
    
    $users_object = $db->$collection->aggregate($piepline);
    $users_array = iterator_to_array($users_object);
    foreach ($users_array as  $value) {
        $database_array['username'] = $value['username'];
        $database_array['first_name'] = $value['first_name'];
        $database_array['last_name'] = $value['last_name'];
        $database_array['btc_allocated_all'] = $value['allocateAllBTC'];
        $database_array['usdt_allocated_all'] = $value['allocateAllUSDT'];
        $database_array['exchange'] = $exchange;
        $database_array['user_id'] = (string)$value['admin_id'];
        $database_array['used_btc_perc'] = $value['perc_used_btc'];
        $database_array['used_usdt_perc'] = (int)$value['perc_used_usdt'];

        $database_array['perc_used_btc_total'] = $value['perc_used_btc_total'];
        $database_array['perc_used_usdt_total'] = (int)$value['perc_used_usdt_total'];

        $database_array['perc_100_used_btc_date'] = $value['perc_100_used_btc_date'];
        $database_array['perc_100_used_usdt_date'] = $value['perc_100_used_usdt_date'];
        $database_array['is_fixed'] = false;
        $database_array['test_case'] = 'TC6'; 
        $db->test_cases_users->updateOne(['user_id'=>(string)$value['admin_id'],'exchange'=>$exchange,'test_case'=>'TC6'],['$set'=>$database_array],['upsert'=>true]); // updating the user in the database so support or QA can fix those after watching listing.
                $database_array = array();
    }// end foreach
    $this->test_cases_listing();
    }
    public function get_TC6_users(){
        $this->mod_login->verify_is_admin_login();
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }

        if($this->input->post()){

            $this->session->unset_userdata('filters_users_tc6');
            $filter_data['filters_users_tc6'] = $this->input->post();
            $this->session->set_userdata($filter_data);
        }
        $db = $this->mongo_db->customQuery();
        $where_arr = array(
            'is_fixed' => ['$ne'=>true],
            'test_case' => ['$eq'=>'TC6'],
        );
        $users_data = $this->session->userdata('filters_users_tc6');
        if(isset($users_data['username']) && $users_data['username'] != ''){
            $where_arr['username']=new MongoDB\BSON\Regex(".*{$users_data['username']}.*", 'i');
        }
        if(isset($users_data['id']) && $users_data['id'] != ''){
            $where_arr['user_id']=strtolower((string)$users_data['id']);
        }
        if(isset($users_data['is_fixed']) && $users_data['is_fixed'] != '' && $users_data['is_fixed'] == 1){
            $where_arr['is_fixed']=true;
        }else{
            $where_arr['is_fixed']=false;
        }
        if(isset($users_data['exchange']) && $users_data['exchange'] != '' && $users_data['exchange'] == 'binance'){
            $where_arr['exchange']='binance';
        }else{
            $where_arr['exchange']='kraken';
        }
        $data_returnCount =  $db->test_cases_users->count($where_arr);
        if ($this->input->post('csv') == 'csv') {
            //echo 'ajshdjkahdjkas';
            $tokensarr  = $this->mod_report->get_all_testcase_users_for_csv($where_arr);
                //echo '<pre>'; print_r($tokensarr);exit;
            if ($tokensarr) {
                $filename = ("Test Case 6 Report:" . date("Y-m-d Gis") . ".csv");
                $now = gmdate("D, d M Y H:i:s"); // Set the Headers for csv
                header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
                header("Last-Modified: {$now} GMT");
                header('Content-Type: text/csv;');
                header("Pragma: no-cache");
                header("Expires: 0");
                header("Content-Type: application/force-download"); // force download
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename={$filename}"); // disposition / encoding on response body
                header("Content-Transfer-Encoding: binary");
                echo array2csv($tokensarr);
                exit;
            }
        }
        $data['total']        =   $data_returnCount;
        $config['base_url']           =   base_url() .'admin/Reports/get_TC6_users';
        $config['total_rows']         =   $data_returnCount;
        $config['per_page']           =   30;
        $config['num_links']          =   3;
        $config['use_page_numbers']   =   TRUE;
        $config['uri_segment']        =   4;
        $config['reuse_query_string'] =   TRUE;
        $config['next_link']          =   '&raquo;';
        $config['next_tag_open']      =   '<li>';
        $config['next_tag_close']     =   '</li>';
        $config['prev_link']          =   '&laquo;';
        $config['prev_tag_open']      =   '<li>';
        $config['prev_tag_close']     =   '</li>';
        $config['first_tag_open']     =   '<li>';
        $config['first_tag_close']    =   '</li>';
        $config['last_tag_open']      =   '<li>';
        $config['last_tag_close']     =   '</li>';
        $config['full_tag_open']      =   '<ul class="pagination">';
        $config['full_tag_close']     =   '</ul>';
        $config['cur_tag_open']       =   '<li class="active"><a href="#"><b>';
        $config['cur_tag_close']      =   '</b></a></li>'; 
        $config['num_tag_open']       =   '<li>';
        $config['num_tag_close']      =   '</li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        if($page !=0) {
        $page = ($page-1) * $config['per_page'];
        }
        $data["links"] = $this->pagination->create_links();
        $condition = [
            'skip'=>$page,
            'sort' => [
            'perc_100_used_btc_date' => -1,
            'perc_100_used_usdt_date' => -1
            ],
            'limit' => $config['per_page']];
        $get_users_result = $db->test_cases_users->find($where_arr,$condition);
        $data['TC6_users'] = iterator_to_array($get_users_result);
        // echo '<pre>';print_r($data['TC6_users']);exit;
        $this->stencil->paint('admin/reports/test_case_TC6', $data); 
    }
    public function resetFilters_tc6(){
        $this->session->unset_userdata('filters_users_tc6');
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }
        $this->get_TC6_users();
    }
    public function test_case_ca_parent_not_exist($exchange = ''){
        if($exchange == '' || $exchange == 'binance'){
            $exchange = 'binance';
            $collection = 'buy_orders';
        }else{
            $exchange = 'kraken';
            $collection = 'buy_orders_kraken';
        }
        $endMongoTime = $this->mongo_db->converToMongodttime(date('2022-08-01 00:00:00'));
        $piepline=[
            [
                '$match'=> [
                    'modified_date'=>['$gte'=>$endMongoTime]
                    ,'status'=>"FILLED_ERROR"
                ]
            ],[
                '$group'=> [
                    '_id'=> ['admin_id'=>'$admin_id','symbol'=>'$symbol'],
                    'count_orders'=> [
                        '$sum'=>1
                    ]
                ]
            ]
        ];
    $db = $this->mongo_db->customQuery();
    $users_object = $db->$collection->aggregate($piepline);
    $users_array = iterator_to_array($users_object);
    foreach ($users_array as  $value) {
    $user_arr = $this->mod_users->get_user((string)$value['_id']['admin_id']);
        $database_array['username'] = $user_arr['username'];
        $database_array['first_name'] = $user_arr['first_name'];
        $database_array['last_name'] = $user_arr['last_name'];
        $database_array['email_address'] = $user_arr['email_address'];
        $database_array['exchange'] = $exchange;
        $database_array['user_id'] = (string)$value['_id']['admin_id'];
        $database_array['symbol'] = $value['_id']['symbol'];
        $database_array['orders_count'] = (int)$value['count_orders'];
        $database_array['is_fixed'] = false;
        $database_array['test_case'] = 'TC3'; 
        $db->test_cases_users->updateOne(['user_id'=>(string)$value['_id']['admin_id'],'symbol'=>$value['_id']['symbol'],'exchange'=>$exchange,'test_case'=>'TC3'],['$set'=>$database_array],['upsert'=>true]); // updating the user in the database so support or QA can fix those after watching listing.
                $database_array = array();
    }// end foreach
    $this->test_cases_listing();
    }
     public function get_TC7_users(){
        $this->mod_login->verify_is_admin_login();
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }

        if($this->input->post()){

            $this->session->unset_userdata('filters_users_tc7');
            $filter_data['filters_users_tc7'] = $this->input->post();
            $this->session->set_userdata($filter_data);
        }
        $db = $this->mongo_db->customQuery();
        $where_arr = array(
            'is_fixed' => ['$ne'=>true],
            'test_case' => ['$eq'=>'TC7'],
        );
        $users_data = $this->session->userdata('filters_users_tc7');
        if(isset($users_data['username']) && $users_data['username'] != ''){
            $where_arr['username']=new MongoDB\BSON\Regex(".*{$users_data['username']}.*", 'i');
        }
        if(isset($users_data['id']) && $users_data['id'] != ''){
            $where_arr['user_id']=strtolower((string)$users_data['id']);
        }
        if(isset($users_data['is_fixed']) && $users_data['is_fixed'] != '' && $users_data['is_fixed'] == 1){
            $where_arr['is_fixed']=true;
        }else{
            $where_arr['is_fixed']=false;
        }
        if(isset($users_data['exchange']) && $users_data['exchange'] != '' && $users_data['exchange'] == 'binance'){
            $where_arr['exchange']='binance';
        }else{
            $where_arr['exchange']='kraken';
        }
        $data_returnCount =  $db->test_cases_users->count($where_arr);
        if ($this->input->post('csv') == 'csv') {
            //echo 'ajshdjkahdjkas';
            $tokensarr  = $this->mod_report->get_all_testcase_users_for_csv($where_arr);
                //echo '<pre>'; print_r($tokensarr);exit;
            if ($tokensarr) {
                $filename = ("Test Case 6 Report:" . date("Y-m-d Gis") . ".csv");
                $now = gmdate("D, d M Y H:i:s"); // Set the Headers for csv
                header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
                header("Last-Modified: {$now} GMT");
                header('Content-Type: text/csv;');
                header("Pragma: no-cache");
                header("Expires: 0");
                header("Content-Type: application/force-download"); // force download
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename={$filename}"); // disposition / encoding on response body
                header("Content-Transfer-Encoding: binary");
                echo array2csv($tokensarr);
                exit;
            }
        }
        $data['total']        =   $data_returnCount;
        $config['base_url']           =   base_url() .'admin/Reports/get_TC7_users';
        $config['total_rows']         =   $data_returnCount;
        $config['per_page']           =   30;
        $config['num_links']          =   3;
        $config['use_page_numbers']   =   TRUE;
        $config['uri_segment']        =   4;
        $config['reuse_query_string'] =   TRUE;
        $config['next_link']          =   '&raquo;';
        $config['next_tag_open']      =   '<li>';
        $config['next_tag_close']     =   '</li>';
        $config['prev_link']          =   '&laquo;';
        $config['prev_tag_open']      =   '<li>';
        $config['prev_tag_close']     =   '</li>';
        $config['first_tag_open']     =   '<li>';
        $config['first_tag_close']    =   '</li>';
        $config['last_tag_open']      =   '<li>';
        $config['last_tag_close']     =   '</li>';
        $config['full_tag_open']      =   '<ul class="pagination">';
        $config['full_tag_close']     =   '</ul>';
        $config['cur_tag_open']       =   '<li class="active"><a href="#"><b>';
        $config['cur_tag_close']      =   '</b></a></li>'; 
        $config['num_tag_open']       =   '<li>';
        $config['num_tag_close']      =   '</li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        if($page !=0) {
        $page = ($page-1) * $config['per_page'];
        }
        $data["links"] = $this->pagination->create_links();
        $condition = ['skip'=>$page,'sort'=>['first_name' =>1],'limit' => $config['per_page']];
        $get_users_result = $db->test_cases_users->find($where_arr,$condition);
        $data['TC7_users'] = iterator_to_array($get_users_result);
        //echo '<pre>';print_r($data);exit;
        $this->stencil->paint('admin/reports/test_case_TC7', $data);
    }
    public function resetFilters_tc7(){
        $this->session->unset_userdata('filters_users_tc7');
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }
        $this->get_TC6_users();
    }                             
}
