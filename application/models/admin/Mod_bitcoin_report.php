<?php
class mod_bitcoin_report extends CI_Model {

    function __construct() {

        parent::__construct();
    }

    

    public function count_all_users() {
        $filter = array();
        $session_post_data = $this->session->userdata('filter_user_data');
        if ($session_post_data['filter_by_name'] != "") {
            $filter_by_name = $session_post_data['filter_by_name'];
            $search_sting = trim($filter_by_name);
            $search_sting = str_replace("\\", "\\\\", $search_sting);
            $search_sting_like = new MongoDB\BSON\Regex(".*{$search_sting}.*", 'i');
            //$search_sting_like = new MongoDB\BSON\Regex(preg_quote($search_sting), 'i');
            $filter['$or'] = array(
                array('_id' => $search_sting_like),
                array('first_name' => $search_sting_like),
                array('last_name' => $search_sting_like),
                array('username' => $search_sting_like),
                array('phone_number' => $search_sting_like),
                array('email_address' => $search_sting_like),
            );
        }
        if ($session_post_data['filter_by_ip'] != "") {
            $filter_by_ip = $session_post_data['filter_by_ip'];
            $filter['trading_ip'] = $filter_by_ip;
        }

        if ($session_post_data['filter_by_id'] != "") {

            $filter_by_id = $session_post_data['filter_by_id'];
            $filter['_id'] = $this->mongo_db->mongoId($filter_by_id);
        }

        if ($session_post_data['filter_by_mode'] != "") {
            $filter_by_mode = $session_post_data['filter_by_mode'];
            $filter['application_mode'] = $filter_by_mode;
        }

        if ($session_post_data['filter_special'] != "") {
            $filter_special = $session_post_data['filter_special'];
            $filter['special_role'] = '1';
        }

        if ($session_post_data['filter_active'] != "") {
            $filter_special = $session_post_data['filter_active'];
            $filter['status'] = '1';
        }

        if ($session_post_data['filter_inactive'] != "") {
            $filter_special = $session_post_data['filter_inactive'];
            $filter['status'] = '0';
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

            $order_type = $session_post_data['filter_type'];
            $filter['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
        }
        if (!empty($_GET['query'])) {
            $search_sting = $_GET['query'];
            $search_sting = str_replace("\\", "\\\\", $search_sting);
            $search_sting_like = new MongoDB\BSON\Regex(preg_quote($search_sting), 'i');
            $filter['$or'] = array(
                array('first_name' => $search_sting_like),
                array('last_name' => $search_sting_like),
                array('username' => $search_sting_like),
                array('home_phone' => $search_sting_like),
                array('email_address' => $search_sting_like),
            );
        }

        $filter_new['application_mode'] = array('$in'=>array('both','live'));

        $this->mongo_db->where($filter_new);
        $total = $this->mongo_db->count("users");

        //$rest = $users_arr['total'];
        $rest = $total;

        return $rest;

    } //end count_all_users_sql



    public function get_all_users($start, $end) {
        $filter = array();
        $session_post_data = $this->session->userdata('filter_bitcoin_report');
        if ($session_post_data['filter_by_name'] != "") {
            $filter_by_name = $session_post_data['filter_by_name'];
            $search_sting = trim($filter_by_name);
            $search_sting = str_replace("\\", "\\\\", $search_sting);
            $search_sting_like = new MongoDB\BSON\Regex(".*{$search_sting}.*", 'i');
            //$search_sting_like = new MongoDB\BSON\Regex(preg_quote($search_sting), 'i');
            $filter['$or'] = array(
                array('_id' => $search_sting_like),
                array('first_name' => $search_sting_like),
                array('last_name' => $search_sting_like),
                array('username' => $search_sting_like),
                array('phone_number' => $search_sting_like),
                array('email_address' => $search_sting_like),
            );
        }
        if ($session_post_data['filter_by_ip'] != "") {

            $filter_by_ip = $session_post_data['filter_by_ip'];
            $filter['trading_ip'] = $filter_by_ip;
        }

        if ($session_post_data['filter_by_id'] != "") {

            $filter_by_id = $session_post_data['filter_by_id'];
            $filter['_id'] = $this->mongo_db->mongoId($filter_by_id);
        }

        $filter['application_mode'] = array('$in'=>array('both','live'));

        if ($session_post_data['filter_by_mode'] != "") {

            $filter_by_mode = $session_post_data['filter_by_mode'];
            $filter['application_mode'] = array('$in'=>array('both','live'));
            
        }

        if ($session_post_data['filter_special'] != "") {

            $filter_special = $session_post_data['filter_special'];
            $filter['special_role'] = '1';
        }

        if ($session_post_data['filter_active'] != "") {
            $filter_special = $session_post_data['filter_active'];
            $filter['status'] = '0';
        }

        if ($session_post_data['filter_inactive'] != "") {
            $filter_special = $session_post_data['filter_inactive'];
            $filter['status'] = '1';
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

            $order_type = $session_post_data['filter_type'];
            $filter['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
        }
        if (!empty($_GET['query'])) {
            $search_sting = $_GET['query'];
            $search_sting = str_replace("\\", "\\\\", $search_sting);
            $search_sting_like = new MongoDB\BSON\Regex(preg_quote($search_sting), 'i');
            $filter['$or'] = array(
                array('_id' => $search_sting_like),
                array('first_name' => $search_sting_like),
                array('last_name' => $search_sting_like),
                array('username' => $search_sting_like),
                array('phone_number' => $search_sting_like),
                array('email_address' => $search_sting_like),
            );
        }
        $db = $this->mongo_db->customQuery();
        $search = array();

        $qrr = array('sort' => array('_id' => -1), 'skip' => $start, 'limit' => $end);
        $get_users = $db->users->find($filter, $qrr);

        $users_arr = iterator_to_array($get_users);

        return $users_arr;

    } //end get_all_users




}
?>
