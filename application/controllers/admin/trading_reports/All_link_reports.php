<?php
/**
 *
 */
class All_link_reports extends CI_Controller {

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

        // if ($this->session->userdata('user_role') != 1) {
        //     redirect(base_url() . 'forbidden');
        // }
        // if ($this->session->userdata('special_role') != 1) {
        //     redirect(base_url() . 'forbidden');
        // }

    }

    public function index() {
        //Login Check
        //$this->mod_login->verify_is_admin_login();

        $this->stencil->paint('admin/trading_reports/all_links');

    }
    public function buy_order_map($id = ''){

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set(array('status' => 'canceled'));
        $this->mongo_db->update('buy_orders');
        echo "<pre> ok";
        exit;

    }
    public function sold_order_map($id = ''){

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set(array('status' => 'canceled'));
        $this->mongo_db->update('sold_buy_orders');
        echo "<pre> ok";
        exit;

    }
    public function buy_order($id = '') {
        $this->mongo_db->where(array('_id' => $id));
        $row = $this->mongo_db->get('buy_orders');
        $data = iterator_to_array($row);
        echo '<pre>';
        print_r($data);
    }

    public function sold_buy_order($id = '') {
        $this->mongo_db->where(array('_id' => $id));
        $row = $this->mongo_db->get('sold_buy_orders');
        $data = iterator_to_array($row);
        echo '<pre>';
        print_r($data);
}
}