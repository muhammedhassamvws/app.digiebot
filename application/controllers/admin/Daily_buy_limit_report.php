<?php
/**
 *
 */
class Daily_buy_limit_report extends CI_Controller
{


    public function __construct()
    {

        parent::__construct();
        //load main template
        ini_set("memory_limit", -1);
        // ini_set("display_errors", E_ERROR);
        // error_reporting(E_ERROR);
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

            $data_arr['filter_buy_limit_report'] = $this->input->post();
            $this->session->set_userdata($data_arr);
        }
        $session_data = $this->session->userdata('filter_buy_limit_report');
        
        $collection = "daily_trade_buy_limit";
        
        $search = [];
        if (!empty($session_data)) {

            if ($session_data['filter_username'] != "") {
                $username = $session_data['filter_username'];
                $admin_id = $this->get_admin_id($username);
                $search['user_id'] = (string) $admin_id;
            }
            
            if (!empty($session_data['buy_greater_than'])) {
                $buy_greater_than = $session_data['buy_greater_than'];
                // $search['num_of_trades_buy_today'] = ['$gte'=> (int) $buy_greater_than];
                $search['$or'][] = ['BTCTradesTodayCount' => ['$gte'=> (int) $buy_greater_than]];
                $search['$or'][] = ['USDTTradesTodayCount' => ['$gte'=> (int) $buy_greater_than]];
            }
            
            if (!empty($session_data['limit_zero']) && $session_data['limit_zero'] == 'yes') {
                $search['daily_buy_usd_limit'] = 0;
            }
            
            if (!empty($session_data['limit_exceeded']) && $session_data['limit_exceeded'] == 'yes') {
                $search = [
                    'dailyTradeableBTC_usd_worth' => ['$exists' => true],
                    'dailyTradeableUSDT_usd_worth' => ['$exists' => true],
                ];
                // $search = ['$expr' => ['$gt' => [ '$daily_buy_usd_worth' , '$daily_buy_usd_limit' ]]];

                // [dailyTradeableBTC_usd_worth] => 0
                // [dailyTradeableUSDT_usd_worth] => 0

                // [BTCTradesTodayCount] => 0
                // [USDTTradesTodayCount] => 0

                // [daily_bought_btc_usd_worth] => 0
                // [daily_bought_usdt_usd_worth] => 0
            
                $search['$or'][] = ['$expr' => ['$gt' => [ '$daily_bought_btc_usd_worth' , '$dailyTradeableBTC_usd_worth' ]]]; 
                $search['$or'][] = ['$expr' => ['$gt' => [ '$daily_bought_usdt_usd_worth' , '$dailyTradeableUSDT_usd_worth' ]]]; 
            }
            
            if ($session_data['filter_by_exchange'] != "") {
                $exchange = $session_data['filter_by_exchange'];
                if ($exchange != 'binance') {
                    $collection = $collection.'_' . $exchange;
                }
            }
        }

        // echo "<pre>";
        // print_r($search);
        // die('testing');
        
        $connetct = $this->mongo_db->customQuery();
        $total_count = $connetct->$collection->count($search);

        /////////////////////// PAGINATION CODE START HERE /////////////////////////////////////
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = SURL . "admin/daily_buy_limit_report/index";
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

        $this->pagination->initialize($config);
        $page = $this->uri->segment(4);

        if (!isset($page)) {$page = 1;}
        $skip = ($page - 1) * $config["per_page"];
        $limit = $config["per_page"];

        $serial_number = ($page > 1 ? ($page * $config['per_page']) - 100 : 0);

        $data['pagination'] = $this->pagination->create_links();

        // echo "<pre>";
        // echo $collection."<br>";
        // echo "<br>";
        // print_r($search);
        // print_r($pending_options);
        // die('testing');

        $pending_options = array('skip' => $skip, 'limit' => intval(round($limit)));
        $pending_curser = $connetct->$collection->find($search, $pending_options);
        $buy_limit_arr = iterator_to_array($pending_curser);
        if(!empty($buy_limit_arr)){
            $data['buy_limit_arr'] = $buy_limit_arr;
            unset($pending_curser, $buy_limit_arr);

            $tempCount = count($data['buy_limit_arr']);
            for($i=0; $i<$tempCount; $i++){
                $data['buy_limit_arr'][$i]['username'] = $this->get_username_from_user($data['buy_limit_arr'][$i]['user_id']);
            }

        }
        
        $this->stencil->paint('admin/reports/daily_buy_limit_report', $data);

    }

    public function reset_filters_report($type)
    {
        $this->session->unset_userdata('filter_buy_limit_report');
        redirect(base_url() . 'admin/daily_buy_limit_report');
    }

   
    public function get_username_from_user($id)
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
    
    public function get_admin_id($username)
    {
        $customer = $this->mod_report->get_customer_by_username($username);
        return $customer['_id'];
    }

    public function get_all_usernames_ajax()
    {
        $this->mongo_db->sort(array('_id' => -1));
        $get_users = $this->mongo_db->get('users');
        $users_arr = iterator_to_array($get_users);
        $user_name_array = array_column($users_arr, 'username');
        unset($users_arr, $get_users);
        echo json_encode($user_name_array);
        exit;
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

}
