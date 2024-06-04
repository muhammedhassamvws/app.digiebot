<?php
/**
 *
 */
class Error_sell extends CI_Controller {

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

        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }
        // if ($this->session->userdata('special_role') != 1) {
        //     redirect(base_url() . 'forbidden');
        // }

    }

    public function index() {
        //Login Check
        $this->mod_login->verify_is_admin_login();
        $coins = $this->mod_coins->get_all_coins();
        $data['coins'] = $coins;

        $start_date = date("Y-m-d H:i:s", strtotime("-5 days"));
        $end_date = date("Y-m-d H:i:s");

        $where['status'] = "error";
        $where['created_date']['$gte'] = $this->mongo_db->converToMongodttime($start_date);
        $where['created_date']['$lte'] = $this->mongo_db->converToMongodttime($end_date);

        $collection1 = 'sold_buy_orders';
        
        // $join = [
        //     'from' => $collection1,
        //     'localField' => 'sell_order_id',
        //     'foreignField' => '_id',
        //     'as' => 'sell_order',
        // ];
        
        // $query = [
        //     ['$lookup' => $join],
        //     ['$match' => $where],
        //     ['$sort' => ['created_date' => -1]],
        //     ['$limit' => 500],
        // ];

        $pipeline = array(
            array(
                '$lookup'	=> array(
                    'from'			=> 'buy_orders',
                    'localField' 	=> '_id',
                    'foreignField'	=> 'sell_order_id',
                    'as'			=> 'sell_order',
                )
            ),
            array('$unwind' => array( 'path' => '$sell_order', 'preserveNullAndEmptyArrays' => true)),
            array(
                '$redact'	=> array(
                    '$cond'	=> array(
                        'if'	=> array(
                            '$eq'	=> array('$_id', '$sell_order.sell_order_id'),
                        ),
                        'then'	=> '$$KEEP',
                        'else'	=> '$$PRUNE'
                     )
                 )
             ),
            array('$match' => $where),
            array('$limit' 	=> 500)
        );	

        // $collection2 = 'orders';
       
        $db = $this->mongo_db->customQuery();
        $response = $db->orders->aggregate($pipeline);
        $records = iterator_to_array($response);
        
        $data['records'] = $records;
        $this->stencil->paint('admin/trading_reports/error_sell', $data);

    }

    public function error_in_sell_report(){
        $start_date = date("Y-m-d H:i:s", strtotime("-3 days"));
        $end_date = date("Y-m-d H:i:s");

        $where['status'] = "error";
        $where['created_date']['$gte'] = $this->mongo_db->converToMongodttime($start_date);
        $where['created_date']['$lte'] = $this->mongo_db->converToMongodttime($end_date);

        $collection1 = 'buy_orders';
        
        $join = [
            'from' => $collection1,
            'localField' => 'sell_order_id',
            'foreignField' => '_id',
            'as' => 'sell_order',
        ];
        
        $query = [
            ['$lookup' => $join],
            ['$match' => $where],
            ['$sort' => ['created_date' => -1]],
            ['$limit' => 500],
        ];

        $collection2 = 'orders';
       
        $db = $this->mongo_db->customQuery();
        $response = $db->$collection2->aggregate($query);
        $records = iterator_to_array($response);

        echo "<pre>";
        print_r($records);
        exit;
    }
}
