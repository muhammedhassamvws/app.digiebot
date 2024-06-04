<?php
use Prophecy\Exception\Doubler\ReturnByReferenceException;
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {

        parent::__construct();

        //load main template
        $this->stencil->layout('admin_layout');

        //load required slices
        $this->stencil->slice('admin_header_script');
        $this->stencil->slice('admin_header');
        $this->stencil->slice('admin_left_sidebar');
        $this->stencil->slice('admin_footer_script');
         $this->load->helper('new_common_helper');
        // Load Modal
        $this->load->model('admin/mod_login');
        $this->load->model('admin/mod_users');
        $this->load->model('admin/mod_coins');
        $this->ipsArray = array('ip1'=>'3.227.143.115','ip2'=>'3.228.180.22','ip3'=>'3.226.226.217','ip4'=>'3.228.245.92','ip5'=>'54.157.102.20','admin_ip'=>'35.153.9.225','binance_ip'=>'18.170.235.202');
        $this->ipsArray_kraken = array('ip1'=>'3.227.143.115','ip2'=>'3.228.180.22','ip3'=>'3.226.226.217','ip4'=>'3.228.245.92','ip5'=>'54.157.102.20','admin_ip'=>'35.153.9.225');
    }

    public function index() {
    
        //Login Check
        $this->mod_login->verify_is_admin_login();


        $admin_id = $this->session->userdata('admin_id');

        $allow_ids = [
            '5c3a4986fc9aad6bbd55b4f2', 
            '5c0915bafc9aadaac61dd1b6',
            '5c0915befc9aadaac61dd1b8',
            '5f045015ffee8b379571eb22', //aliabbas
            '5f044a447da61a5f1d449825', //shahzad
            '5d9e02e2b2a7c428587808e2', //rabirabi
            '5e3a687ed190b3186c50cf82', //ejazbhatti
        ];

        if ($this->session->userdata('special_role') != 1 && !in_array($this->session->userdata('admin_id'), $allow_ids)) {
            redirect(base_url() . 'forbidden');
        }

        if ($this->input->post()) {
            $posted_data = $this->input->post();
            $trading_ip = $posted_data['filter_by_ip'];
            $allowed_ips = $this->ipsArray;
            $validate_ip =  $allowed_ips[$trading_ip];
            $posted_data['filter_by_ip'] = $validate_ip;
            
            $data_arr['filter_user_data'] = $posted_data;
            if(!empty($_COOKIE['hassam'])) {
              
                echo "<pre> data_arr => "; print_r($data_arr); 
                exit;
            }
            
            $this->session->set_userdata($data_arr);
            redirect(base_url() . 'admin/users/');
        }
        //Fetching users Record
        $count = $this->mod_users->count_all_users();
        $this->load->library('pagination');
        // *******************************************************************************************************************************************
        $config['base_url'] = SURL . 'admin/users/index';
        $config['total_rows'] = $count;
        $config['per_page'] = 20;
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

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 1;
        $start = ($page - 1) * $config["per_page"];

        $data['pagination'] = $this->pagination->create_links();
        /*********************************************************************************************************************************************/
        $users_arr = $this->mod_users->get_all_users($start, $config['per_page']);
        $data['users_arr'] = $users_arr;
        $data['allowed_ips'] = $this->ipsArray;
        //stencil is our templating library. Simply call view via it
        // echo "<pre>"; print_r($data['users_arr']); exit;
        $this->stencil->paint('admin/users/users', $data);
    } //End index

    public function csvreport() {

        $usersArr = $this->mod_users->getAllUsersForCsv();

        foreach ($usersArr as $row) {

            $finalArr['First Name'] = $row['first_name'];
            $finalArr['Last Name'] = $row['last_name'];
            $finalArr['Username'] = $row['username'];
            $finalArr['Email Address'] = $row['email_address'];
            $finalArr['Phone Number'] = $row['phone_number'];
            $finalArr['Application Mode'] = $row['application_mode'];
            $finalArr['Status'] = ($row['status'] == 0) ? 'Active' : 'InActive';
            $finalArr['User Role'] = ($row['user_role'] == 1) ? 'Role 1' : ' Other Role';
            $finalArr['Special Role'] = ($row['special_role'] == 1) ? 'Yes' : 'NO';
            $finalArr['Registeration Date'] = date('d, M Y g:i a', strtotime($row['created_date_human']));
            $finalArr['Trading IP'] = $row['trading_ip'];

            if ($customer['last_login_datetime'] == null || $customer['last_login_datetime'] == "") {
                $login_time = 'N/A';
            } else if (gettype($customer['last_login_datetime']) == 'object') {
                $login_time = $customer['last_login_datetime']->toDateTime();
                $login_time = $login_time->format("d, M Y g:i a");
            } else {
                $login_time = date("d, M Y g:i a", strtotime($customer['last_login_datetime']));
            }

            $finalArr['Last Login'] = $login_time;
            $finalArrAll[] = $finalArr;
        }
        if ($finalArrAll) {

            $filename = ("Users :" . date("Y-m-d Gis") . ".csv");
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
            echo $this->array2csv($finalArrAll);
        } //if($order_Array){
        exit;
    } //csvreport

    public function array2csv($array) {

        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys((array) reset($array)));

        foreach ($array as $key => $row) {
            //$rowNew  =  htmlspecialchars(trim(strip_tags($row)));
            fputcsv($df, (array) $row);
        }
        fclose($df);
        return ob_get_clean();
    } //array2csv

    public function reset_filters($type = '') {
        //Login Check
        $this->mod_login->verify_is_admin_login();
        $this->session->unset_userdata('filter_user_data');
        redirect(base_url() . 'admin/users');

    } //End reset_buy_filters
    public function add_user() {
        //Login Check
        $this->mod_login->verify_is_admin_login();
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }
        
        $data['allowed_ips'] = $this->ipsArray;
        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/users/add_user',$data);

    } //End add_user

    public function contact_us_process() {

        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData);
        //Adding contact_us_process
        $InsertData = $this->mod_users->contact_us_process($data);
        if ($InsertData) {
            $json_array['success'] = true;
            $json_array['userData'] = '';
        } else {
            $json_array['success'] = false;
            $json_array['userData'] = 'Error';
        }
        echo json_encode($json_array);
        exit;

    } //end add_user_process

    public function add_user_process() {

        //Login Check
        $this->mod_login->verify_is_admin_login();
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }
        //Adding add_user
        $posted_data  = $this->input->post();
        $trading_ip = $posted_data['trading_ip'];
        $allowed_ips = $this->ipsArray;
        $validate_ip =  $allowed_ips[$trading_ip];
        if(!empty($validate_ip))
        {
            $posted_data['trading_ip'] = $validate_ip;
        }
        else
        {
            unset($posted_data['trading_ip']);
        }
        // echo "<pre>";
        // print_r($posted_data);exit;
        
        $user_id = $this->mod_users->add_user($posted_data);

        if ($user_id) {

            $this->session->set_flashdata('ok_message', 'User added successfully.');
            redirect(base_url() . 'admin/users/add-user');

        } else {

            $this->session->set_flashdata('err_message', 'User cannot added. Something went wrong, please try again.');
            redirect(base_url() . 'admin/users/add-user');

        } //end if

    } //end add_user_process

    public function edit_user($user_id) {
        //Login Check
        $this->mod_login->verify_is_admin_login();
        $admin_id = $this->session->userdata('admin_id');
        $allow_ids = [
            '5c3a4986fc9aad6bbd55b4f2', 
            '5c0915bafc9aadaac61dd1b6',
            '5c0915befc9aadaac61dd1b8',
            '5f045015ffee8b379571eb22', //aliabbas
            '5f044a447da61a5f1d449825', //shahzad
            '5d9e02e2b2a7c428587808e2', //rabirabi
            '5e3a687ed190b3186c50cf82', //ejazbhatti
        ];

        if ($this->session->userdata('special_role') != 1 && !in_array($this->session->userdata('admin_id'), $allow_ids)) {
            redirect(base_url() . 'forbidden');
        }
       
        //Fetching user Record
        $user_arr = $this->mod_users->get_user($user_id);
        $data['user_arr'] = $user_arr;
        $data['user_id'] = $user_id;
        // $data['allowed_ips'] = $this->ipsArray;

        $this->stencil->paint('admin/users/edit_user', $data);

    } //End edit_user

    public function edit_role($user_id, $role_id) {
        $this->mod_login->verify_is_admin_login();
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }

        //Fetching user Record
        $user_arr = $this->mod_users->update_user_role($user_id, $role_id);
        redirect(base_url() . 'admin/users');
    }

    public function edit_status($user_id, $role_id) {
        $this->mod_login->verify_is_admin_login();
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }

        //Fetching user Record
        $user_arr = $this->mod_users->update_user_status($user_id, $role_id);
        redirect(base_url() . 'admin/users');
    }

    public function edit_user_process() {

        //Login Check
        $this->mod_login->verify_is_admin_login();
        $admin_id = $this->session->userdata('admin_id');

        $allow_ids = [
            '5c3a4986fc9aad6bbd55b4f2',
            '5c0915bafc9aadaac61dd1b6',
            '5c0915befc9aadaac61dd1b8',
            // '5f045015ffee8b379571eb22', //aliabbas
            // '5f044a447da61a5f1d449825', //shahzad
            // '5d9e02e2b2a7c428587808e2', //rabirabi
            // '5e3a687ed190b3186c50cf82', //ejazbhatti
        ];

        if ($this->session->userdata('special_role') != 1 && !in_array($this->session->userdata('admin_id'), $allow_ids)) {
            redirect(base_url() . 'forbidden');
        }
        //edit_user
        $posted_data  = $this->input->post();
        $trading_ip = $posted_data['trading_ip'];
        $update_both = '';
        $update_exchange = $posted_data['update_exchange'];
        $allowed_ips = $this->ipsArray;
        $validate_ip =  $allowed_ips[$trading_ip];
        if(!empty($validate_ip))
        {
            $posted_data['trading_ip'] = $validate_ip;
        }
        else
        {
            unset($posted_data['trading_ip']);
        }

        // if(isset($_COOKIE['sheraz']) && $_COOKIE['sheraz'] == 1){
        //     echo "<pre>";
        //     print_r($posted_data);exit;
        // }
        
        
        $user_id = $this->mod_users->edit_user($posted_data);
        $update_order = $this->update_orders_ip_by_user_ip($trading_ip,$posted_data['user_id'],$update_both,$update_exchange);
        
        if ($user_id) {

            redirect(base_url() . 'admin/users/edit-user/' . $user_id);

        } else {

            redirect(base_url() . 'admin/users/edit-user/' . $user_id);

        } //end if

    } //end edit_user_process

    public function delete_user($user_id) {

        //Login Check
        $this->mod_login->verify_is_admin_login();
        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }
        //Delete user
        $delete_user = $this->mod_users->delete_user($user_id);

        if ($delete_user) {

            $this->session->set_flashdata('ok_message', 'User deleted successfully.');
            redirect(base_url() . 'admin/users');

        } else {

            $this->session->set_flashdata('err_message', 'User can not deleted. Something went wrong, please try again.');
            redirect(base_url() . 'admin/users');

        } //end if

    } //end delete_user

    public function update_application_mode() {
        $user_id = $this->input->post('user_id');
        $mode = $this->input->post('mode');

        $user_arr = $this->mod_users->update_application_mode($user_id, $mode);
        redirect(base_url() . 'admin/users');
    }

    public function update_trigger_mode() {
        $user_id = $this->input->post('user_id');
        $mode = $this->input->post('mode');

        $user_arr = $this->mod_users->update_trigger_mode($user_id, $mode);
        echo "User Updated";
        exit;
    }
    public function update_app_mode() {
        $user_id = $this->input->post('user_id');
        $mode = $this->input->post('mode');

        $user_arr = $this->mod_users->update_app_mode($user_id, $mode);
        echo "User Updated";
        exit;
    }
    public function update_orders_ip_by_user_ip($ip,$user_id,$both = '',$exchange = ''){

                $ipsArray = array('ip1'=>'3.227.143.115','ip2'=>'3.228.180.22','ip3'=>'3.226.226.217','ip4'=>'3.228.245.92','ip5'=>'54.157.102.20','admin_ip'=>'35.153.9.225','binance_ip'=>'18.170.235.202');
                $admin_id = $user_id;
                $ip_name = $ip;
                $ip_address = $ipsArray[$ip_name];
                $search_array = [
                      'admin_id'            => (string)$admin_id,
                      'trading_ip'   =>  ['$ne'  => $ip_address]
                    ];
                $upd_arr['trading_ip'] = $ip_address;    
                $db = $this->mongo_db->customQuery();

                if($both != '' && $both == 'yes' && $exchange == ''){

                        $buy_orders_count   =   $db->buy_orders->count($search_array); 

                        if($buy_orders_count > 0 ){
                           $db->buy_orders->updateMany($search_array, array('$set' => $upd_arr));
                        }

                        $buy_orders_count_kraken   =   $db->buy_orders_kraken->count($search_array); 

                        if($buy_orders_count_kraken > 0 ){

                            $db->buy_orders_kraken->updateMany($search_array, array('$set' => $upd_arr));
                        }

                        $sold_orders_count   =   $db->sold_buy_orders->count($search_array); 
                        if($sold_orders_count > 0 ){

                           $db->sold_buy_orders->updateMany($search_array, array('$set' => $upd_arr));
                        }

                        $sold_orders_count_kraken   =   $db->sold_buy_orders_kraken->count($search_array);  

                        if($sold_orders_count_kraken > 0 ){
                          $db->sold_buy_orders_kraken->updateMany($search_array, array('$set' => $upd_arr));
                        }
                         $search_array_kr = [
                              'user_id'      => (string)$admin_id,
                              'trading_ip'   =>  ['$ne'  => $ip_address]
                            ];
                        $upd_arr_kr['trading_ip'] = $ip_address;   
                        $update_user_kraken   =   $db->kraken_credentials->count($search_array_kr);

                        if($update_user_kraken > 0 ){
                           $db->kraken_credentials->updateOne($search_array_kr, array('$set' => $upd_arr_kr));
                        }

                }else if($exchange != '' && $exchange == 'binance'){

                        $buy_orders_count   =   $db->buy_orders->count($search_array); 

                        if($buy_orders_count > 0 ){
                           $db->buy_orders->updateMany($search_array, array('$set' => $upd_arr));
                        }
                        $sold_orders_count   =   $db->sold_buy_orders->count($search_array); 
                        if($sold_orders_count > 0 ){

                           $db->sold_buy_orders->updateMany($search_array, array('$set' => $upd_arr));
                        }
                }else if($exchange != '' && $exchange == 'kraken'){

                        $buy_orders_count_kraken   =   $db->buy_orders_kraken->count($search_array); 

                        if($buy_orders_count_kraken > 0 ){

                            $db->buy_orders_kraken->updateMany($search_array, array('$set' => $upd_arr));
                        }
                        $sold_orders_count_kraken   =   $db->sold_buy_orders_kraken->count($search_array);  

                        if($sold_orders_count_kraken > 0 ){
                          $db->sold_buy_orders_kraken->updateMany($search_array, array('$set' => $upd_arr));
                        }
                         $search_array_kr = [
                              'user_id'      => (string)$admin_id,
                              'trading_ip'   =>  ['$ne'  => $ip_address]
                            ];
                        $upd_arr_kr['trading_ip'] = $ip_address;   
                        $update_user_kraken   =   $db->kraken_credentials->count($search_array_kr);
                        if($update_user_kraken > 0 ){
                           $db->kraken_credentials->updateOne($search_array_kr, array('$set' => $upd_arr_kr));
                        }
                }

                return 1;
        
              }
        public function get_less_traffic_trading_ip(){
                      $letPipeline=[['$match'=>[
                              'trading_ip'=>['$nin'=>['35.153.9.225','18.170.235.202']],
                              'exchange'=>"binance"
                            ]], ['$group'=> [
                                      '_id'=>'$trading_ip',
                                      'less_users_ip'=> [ '$min'=> '$active_users']
                              ]]];
                      $db = $this->mongo_db->customQuery();
                      $dataResponse = $db->ip_stats->aggregate($letPipeline);
                      $response = iterator_to_array($dataResponse);
                      $trading_ip_min=$response[0]->less_users_ip;
                      $trading_ip =$response[0]->_id;
                      foreach($response as $value){
                        if($value['less_users_ip'] < $trading_ip_min){
                            $trading_ip_min =$value['less_users_ip'];
                            $trading_ip= $value['_id'];
                        }
                      }
                       print_r($trading_ip);
                      
    }
  
    public function nomantest($a,$b,$c,$operator){
        
        echo 'The'.$operator.' of these numbers is '.$this->test($a,$b,$c,$operator);
    }
   public function test($a,$b,$c,$operator){
    $ans = 0;
    if($operator == 'sum'){
        $ans=$a+$b+$c;
    }
    else if($operator == 'sub'){
     $ans=$a-$b-$c;
    }
    else if($operator == 'mul'){
    $ans = $a*$b*$c;
    }
    else if($operator == 'div') {
        $ans = $a/$b/$c;
    }
    return $ans;
   }
   
   
    //////// return  ip with minimum number of users ///
    // public function users_tradin_points_consumed_calculation($exchange = ''){
    //     $exchange = 'kraken';
    //     $db = $this->mongo_db->customQuery();
    //     $get_users_array_kr = [[
    //                             '$match'=> [
    //                                     'user_id'=>[
    //                                         '$ne'=> ''
    //                                     ]
    //                                 ]
    //                             ], [
    //                                 '$project'=> [
    //                                     'user_id'=> 1
    //                                 ]
    //                             ]];
    //     $kraken_users_array   =  $db->kraken_credentials->aggregate($get_users_array_kr);
    //     $iterator_kraken_array = iterator_to_array($kraken_users_array);
    //     //echo '<pre>'; print_r($iterator_kraken_array);exit;
    //     $buyCollection = $exchange == "binance" ? "buy_orders" : "buy_orders_$exchange";
    //     $soldCollection = $exchange == "binance" ? "sold_buy_orders" : "sold_buy_orders_$exchange";

    //     $endTime = date('Y-m-d 07:00:00',strtotime('2021-06-05'));
    //     $startTime = date('Y-m-d 07:00:00',strtotime('2021-06-01'));
    //     //echo count($iterator_kraken_array);exit;
        
    //     while (strtotime($startTime) <= strtotime($endTime)) { // while loop btween dates range 
    //         $startTime = $this->mongo_db->converToMongodttime($startTime);
    //         $startTime_new = date ("Y-m-d h:i:s", strtotime("+24 hours", strtotime($startTime)));
    //         $endTime_mongo =  $this->mongo_db->converToMongodttime($startTime_new);
    //         $childsBuyPipeline = [
    //             [
    //                 '$match'=> [
    //                     'application_mode'=> 'live',
    //                     'admin_id'=> ['$eq'=>$iterator_kraken_array[]['user_id']],
    //                     'purchased_price'=> ['$exists'=> true],
    //                     'quantity'=> ['$exists'=> true],
    //                     'status'=> [
    //                         '$nin'=> [
    //                             'canceled',
    //                             'error',
    //                             'new_ERROR',
    //                             'FILLED_ERROR',
    //                             'submitted_ERROR',
    //                             'LTH_ERROR',
    //                             'canceled_ERROR',
    //                             'credentials_ERROR',
    //                         ]
    //                     ],
    //                     'resume_order_id' => ['$exists' => false],
    //                     'buy_date'=> [
    //                         '$gte'=> $startTime, 
    //                         '$lte'=> $endTime_mongo
    //                     ],
    //                 ]
    //             ],
    //             [ 
    //                 '$project'=> [ 
    //                     "symbol"=> 1,
    //                     "quantity"=> 1,
    //                     "purchased_price"=> 1,
    //                 ]
    //             ]
    //         ];
    //         $buy_orders = $db->$buyCollection->aggregate($childsBuyPipeline);
    //         $buy_orders = iterator_to_array($buy_orders);
    //         $sold_orders = $db->$soldCollection->aggregate($childsBuyPipeline);
    //         $sold_orders = iterator_to_array($sold_orders);
    //         $orders = array_merge($buy_orders, $sold_orders);
    //         echo count($orders)."-----------".count($buy_orders)."---------------".count($sold_orders)."<br>";
    //          //echo '<pre>'; print_r($iterator_kraken_array);exit;
    //         unset($buy_orders, $sold_orders);
    //         $BTCUSDT_price = get_current_market_prices($exchange,array('BTCUSDT'));// getting current market price 'BTCUSDT' for kraken Exchange,
    //         echo '<pre>'; print_r($BTCUSDT_price);
    //         if(!empty($orders) && !empty($BTCUSDT_price)){
    //             $total_usd_buy_worth = 0;
    //             $order_count = count($orders);
    //                 //for loop on orders to find total buy worth
    //             for($j=0; $j<$order_count; $j++){
    //                 $tarr = explode('USDT', $orders[$j]['symbol']);
    //                 if (isset($tarr[1]) && $tarr[1] == '') {
    //                                 // echo "\r\n USDT coin";
    //                     $usd_balance = $orders[$j]['quantity'] * $orders[$j]['purchased_price'];
    //                     $usd_balance = number_format($usd_balance, 2);
    //                 } else {
    //                                 // echo "\r\n BTC coin";
    //                     $usd_balance = $orders[$j]['quantity'] * $orders[$j]['purchased_price'] * $BTCUSDT_price['BTCUSDT'];
    //                     $usd_balance = number_format($usd_balance, 2);
    //                 }
    //                 $total_usd_buy_worth += (!is_nan($usd_balance) ? $usd_balance : 0);
    //             }
    //                 // echo "today buy_usd_worth $ $total_usd_buy_worth <br>";
    //                 // find points consumed today $100 = 1 point
    //             $pointUsdVal = 100;
    //             $points_consumed = $total_usd_buy_worth / $pointUsdVal;
    //             $points_consumed = number_format($points_consumed, 2);
    //             echo '<pre>'; print_r($date ("Y-m-d h:i:s",strtotime($startTime)));
    //             echo '<pre>'; print_r($points_consumed);exit;
    //             // $update_array_for_test_collection = [
    //             //     'date':];
    //             //temp_trading_points_calculations
    //             // lock these $points_consumed against userID and Exchange in a temporary collection.
    //             // date :
    //             // points_consumed
    //             // user_id:
    //             // exchange :
    //         }
    //         $startTime = date ("Y-m-d h:i:s", strtotime("+1 day", strtotime($startTime)));
    //         exit;
    //     }
    // }           
    public function create_trading_ip_html(){
        $user_id = $this->input->post('user_id');
        $exchange = $this->input->post('exchange');
        $db = $this->mongo_db->customQuery();
        $allowed_ips = array();
        if($exchange == 'binance'){
            $allowed_ips = ['binance_ip'=>'18.170.235.202'];
            $user_obj = $db->users->find(['_id'=>$this->mongo_db->mongoId($user_id)]);
        }else{
            $allowed_ips = $this->ipsArray_kraken;
            $user_obj = $db->kraken_credentials->find(['user_id'=>$user_id]);
        }
        //echo '<pre>';print_r($allowed_ips);
        $user_arr = iterator_to_array($user_obj);
        //echo '<pre>';print_r($user_arr);
        $html = '';
        $html .='<div class="form-group col-md-12"><label class="control-label" for="trading_ip">Trading IP</label><select name="trading_ip" class="form-control">';
                //echo '<pre>';print_r($html);
                foreach($allowed_ips as $key=>$value)
                    { 
                       $html .= '<option '.($user_arr[0]['trading_ip'] == $value ?'selected':'').' value="'.$key.'">'.$key.'</option>';
                    } 
        $html .='</select></div>';
        //echo '<pre>';print_r($html);
        echo json_encode($html);
    }
}
