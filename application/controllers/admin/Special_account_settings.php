<?php
/**
 *
 */
class Special_account_settings extends CI_Controller
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

        // echo "<pre>";
        // print_r($search);
        // die('testing');

        // $connetct = $this->mongo_db->customQuery();
        // $total_count = $connetct->$collection->count($search);

        $data = [];

        $this->stencil->paint('admin/reports/special_account_settings', $data);

    }

    public function reset_filters_report($type)
    {
        $this->session->unset_userdata('filter_buy_limit_report');
        redirect(base_url() . 'admin/daily_buy_limit_report');
    }

    // public function get_username_from_user($id)
    // {
    //     if (!empty($id)) {
    //         $id = $this->mongo_db->mongoId($id);
    //         $this->mongo_db->where(['_id' => $id]);
    //         $get_users = $this->mongo_db->get('users');
    //         $users_arr = iterator_to_array($get_users);
    //         if (!empty($users_arr)) {
    //             $username = $users_arr[0]['username'];
    //             unset($users_arr, $get_users);
    //             return $username;
    //         }
    //     }
    //     return '';
    // }

    // public function get_admin_id($username)
    // {
    //     $customer = $this->mod_report->get_customer_by_username($username);
    //     return $customer['_id'];
    // }

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

    public function get_csv()
    {

        // ini_set("display_errors", E_ERROR);
        // error_reporting(E_ERROR);

        // $this->generate_csv($csvOrdersArr);
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
