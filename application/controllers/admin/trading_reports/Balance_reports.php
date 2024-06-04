<?php
/**
 *
 */
class Balance_reports extends CI_Controller {

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
      

        //load models
        $this->load->model('admin/mod_report');
        $this->load->model('admin/mod_dashboard');
        $this->load->model('admin/mod_coins');
        $this->load->model('admin/mod_login');
        $this->load->model('admin/mod_buy_orders');

        // if ($this->session->userdata('user_role') != 1) {
        //     redirect(base_url() . 'forbidden');
        // }

    }

    public function index() {

        $this->load->library("binance_api");

        //$this->mod_login->verify_is_admin_login();

        if ($this->input->post()) {

            $data_arr['filter_order_data'] = $this->input->post();

            $this->session->set_userdata($data_arr);

            $user_id_arr = $this->mod_report->get_customer_by_username($this->input->post("filter_username"));
             $username = $this->input->post("filter_username");
             $user_id = $user_id_arr['_id'];
            $symbol = $this->input->post("filter_by_coin");
            //$symbol =(string) $symbol;
            $data['filter_user_data']['filter_by_coin'] = $symbol;
            $data['filter_user_data']['filter_username'] = $this->input->post("filter_username");
            $data['userFinalData'] =$this->getallusers($user_id ,$symbol);
   
        }else{
            $data['userFinalData'] =$this->getallusers();
        }
         
        $coins = $this->mod_coins->get_all_coins();
        $data['coins'] = $coins;

        $this->stencil->paint('admin/trading_reports/balance_report',$data);
    }
    public function abc(){
        echo "tahir";
        exit;
    }

     public function getallusers($id ,$coin='TRXBTC'){
         //$this->mongo_db->limit(10);
         

        //  if($id){
            $where['_id'] = $id;
            $this->mongo_db->where($where);
            $data = $this->mongo_db->get('users');
            $data = iterator_to_array($data);

           
            
        // }else{

        //     $data = $this->mongo_db->get('users');
        //     $data = iterator_to_array($data);
            
        // }

        $reps = array();

        foreach($data as $row){
            $arr = array();
            
            $userId = (string) $row['_id'];
            $username = (string) $row['username'];

            $totalQty = $this->calulateQty($userId,$coin);
            $digidbaladf = $this->get_digi_bal($userId,$coin);
            $binancebaladf = $this->get_binance_bal($userId,$coin);
            
            if($totalQty !=0){
                $arr['_id']=$userId ;
                $arr['username'] = $username;
                $arr['totalQty'] = $totalQty;
                $arr['coin'] = $coin;
                $arr['digidbaladf'] = $digidbaladf;
                $arr['binancebal'] = $binancebaladf;
                $reps[] = $arr;
            }
           if($id){break;}
        }
         
        $data['userFinalData'] = $reps;
        
       return $data['userFinalData'];
        
     } //END OF   getallusers

     public function calulateQty($userId,$symbol){

        $where['application_mode'] = 'live';
        $where['status']['$in'] = array('FILLED','LTH');
        $where['symbol'] = $symbol;
        $where['is_sell_order'] = 'yes';
        $where['admin_id'] = $userId;
        $this->mongo_db->limit(10);
        $this->mongo_db->where($where);
        $get_obj = $this->mongo_db->get('buy_orders');
        $buy_orders = iterator_to_array($get_obj);
        $totalQty = 0;

        foreach($buy_orders as $row){

            $totalQty +=$row['quantity'];

        }
        $manual = $this->calulateQtylive($userId,$symbol);
        $total = $totalQty + $manual ;
        return $total;
     }//End of calulateQty
     public function calulateQtylive($userId,$symbol){

        $where['application_mode'] = 'live';
        $where['status']['$in'] = array('FILLED','LTH');
        $where['symbol'] = $symbol;
        $where['is_sell_order']['$ne'] = 'sold';
        $where['trigger_type'] = 'no';
        $where['admin_id'] = $userId;
        $this->mongo_db->limit(10);
        $this->mongo_db->where($where);
        $get_obj = $this->mongo_db->get('buy_orders');
        $buy_orders = iterator_to_array($get_obj);
        $totalty = 0;

        foreach($buy_orders as $row){

            $totalty +=$row['quantity'];

        }

        return $totalty;
     }//End of calulateQty

     public function get_binance_bal($user_id , $symbol ){

        $balance_arr = $this->binance_api->get_account_balance($user_id,$symbol);
        $symbol =  str_replace("BTC","",$symbol);
        return  $balance_arr[$symbol]['available'];  

     }//End of get_binance_bal

     public function get_digi_bal($userId ,$symbol){

        $_id = (string) $userId;
        $where['user_id'] = $_id;
        $where['coin_symbol'] = $symbol;
        $this->mongo_db->limit(10);
        $this->mongo_db->where($where);
        $data1 = $this->mongo_db->get('user_wallet');
        $data = iterator_to_array($data1);
        $reps1 = array();

        foreach($data as $row){

           $digi_bal= $row['coin_balance'];
        }
        return $digi_bal;
    }//End of get_digi_bal

    public function reset_filter(){

        $this->session->unset_userdata('filter_order_data');
        redirect(base_url() . 'admin/trading_reports/balance_reports');

    }//End of reset_filter


    
   
}
