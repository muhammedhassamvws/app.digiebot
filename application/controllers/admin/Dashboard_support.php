<?php
class Dashboard_support extends CI_Controller {

    function __construct() {
        parent::__construct();
        ini_set("display_errors", 1);
        error_reporting(1);
        ini_set("memory_limit", -1);
        //load main template
        $this->stencil->layout('admin_layout');
        //load required slices
        $this->stencil->slice('admin_header_script');
        $this->stencil->slice('admin_header');
        $this->stencil->slice('admin_left_sidebar');
        $this->stencil->slice('admin_footer_script');
        // Load Modal
        $this->load->model('admin/mod_login');
        $this->load->model('admin/mod_users');
        $this->load->model('admin/mod_dashboard');
        $this->load->model('admin/mod_coins');
        $this->load->model('admin/mod_candel');
        $this->load->model('admin/mod_market');
        $this->load->model('admin/mod_barrier_trigger');
        $this->load->model('admin/mod_balance');
    }
    public function index() {
        $this->mod_login->verify_is_admin_login();
        $this->stencil->paint('admin/support_dashboard/dashboard', $data);
    }

    public function error_in_sell_report(){
        $start_date = date("Y-m-d H:i:s", strtotime("-5 days"));
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

    function testing_error_in_sell(){
        $start_date = date("Y-m-d H:i:s", strtotime("-5 days"));
        $end_date = date("Y-m-d H:i:s");

        $where['status'] = "error";
        $where['created_date']['$gte'] = $this->mongo_db->converToMongodttime($start_date);
        $where['created_date']['$lte'] = $this->mongo_db->converToMongodttime($end_date);

        $db = $this->mongo_db->customQuery();
        $response = $db->orders->find($where);
        $records = iterator_to_array($response);

        echo "<pre>";
        print_r($records);
        exit;
    }
    
}
