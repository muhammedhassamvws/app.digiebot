<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - notification_test()
* - cronjob_push_notification()
* Classes list:
* - Api_notifications extends CI_Controller
*/
defined('BASEPATH') or exit('No direct script access allowed');

//require APPPATH . 'libraries/REST_Controller.php';


class Api_notifications extends CI_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        
        $this->stencil->layout('admin_layout');
        //load required slices
        $this->stencil->slice('admin_header_script');
        $this->stencil->slice('admin_header');
        $this->stencil->slice('admin_left_sidebar');
        $this->stencil->slice('admin_footer_script');
        $this->load->library('push_notifications');
        $this->load->model('admin/mod_cronjob_listing');
        $this->load->model('admin/mod_settings');
        $this->load->model('admin/mod_report');
        $this->load->model('admin/mod_coins');
        $this->load->model('admin/mod_Api_crownjob_status');
        //$this->load->model('admin/mod_cronjob_status');
        
    }

    public function notification_test() {
        $data = array(
            "title" => "Cronjob Alert",
            "url" => "http://google.com",
            "last_run" => "1 minute ago",
            "priority" => "high",
            "cron_duration" => ""
        );
        $this
            ->push_notifications
            ->android_notification_topic($data);
    }
    /**
     * @Author: Muhammad Waqar
     * @DateTime : 17-12-2019 (Tuesday)
     * @Description : Function To Send the Push Notifications. 
     * Cronjob To Run Every Minute to Check If There is any stopped Cronjob
     */
    public function cronjob_push_notification() {
        // $data = $this->mongo_db->get('cronjob_execution_logs');
        // $data = iterator_to_array($data);
        $api_url   = "http://35.171.172.15:3000/api/all_cronjobs";
        $data_json = file_get_contents($api_url);
        $data_arr  = (array)json_decode($data_json);
        $data_arr  = (array)$data_arr['data'];
        $arr       = json_decode(json_encode($data_arr) , true);

        $inactive  = false;
        foreach ($arr as $row) {
            $url = $row['name'];
            $post['name'] = $url;
            $curl = curl_init();

            //
            curl_setopt_array($curl, array(
                CURLOPT_PORT           => "3000",
                CURLOPT_URL            => "http://35.171.172.15:3000/api/all_cronjobs",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_POSTFIELDS     => json_encode($post) ,
                CURLOPT_HTTPHEADER     => array(
                    "cache-control: no-cache",
                    "content-type: application/json"
                ) ,
            ));

            $response              = curl_exec($curl);
            $err                   = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            }
            else {
                //echo $response;
                
            }
            $res_arr          = (array)json_decode($response);
            $res_arr          = $res_arr['data'];
            $res_arr          = json_decode(json_encode($res_arr) , true);
            $cron_duration    = $res_arr['cron_duration'];
            $last_updatedtime = $res_arr['last_updated_time_human_readible'];

            // $cron_duration_arr = explode(' ',$cron_duration);
            //Umer Abbas [6-11-19]
            $duration_arr     = str_split($cron_duration, 1);
            $time             = array_pop($duration_arr);
            $add_time         = strtoupper($time);
            $duration         = implode('', $duration_arr);

            $dt               = new DateTime($last_updatedtime);
            // echo $dt->format('Y-m-d H:i:s');
            $last_time        = date("Y-m-d H:i:s", strtotime($last_updatedtime));

            if ($time == 's') {
                $padding_duration = 2;
                $padding_time     = "M";
                $interval_str     = "PT$padding_duration$padding_time$duration$add_time";
                $dt->add(new DateInterval($interval_str));
                $param            = 12;

            }
            else if ($time == 'm') {

                $padding_duration = 5;
                $duration         = $duration + $padding_duration;
                $interval_str     = "PT$duration$add_time";
                $dt->add(new DateInterval($interval_str));
                $param            = 300;

            }
            else if ($time == 'h') {

                $padding_duration = 15;
                $padding_time     = "M";
                $interval_str     = "PT$duration$add_time$padding_duration$padding_time";
                $dt->add(new DateInterval($interval_str));

                $param        = 900;

            }
            else if ($time == 'd') {

                $interval_str = "P$duration$add_time";
                $dt->add(new DateInterval($interval_str));
                $padding_duration = 1;
                $padding_time     = "H";
                $interval_str     = "PT$padding_duration$padding_time";
                $dt->add(new DateInterval($interval_str));

                $param        = 3600;

            }
            else if ($time == 'w') {

                $interval_str = "P$duration$add_time";
                $dt->add(new DateInterval($interval_str));
                $padding_duration = 1;
                $padding_time     = "H";
                $interval_str     = "PT$padding_duration$padding_time";
                $dt->add(new DateInterval($interval_str));

                $param    = 3600;
            }

            $dt2 = new DateTime();

            if ($dt2 <= $dt) {
                //echo $url . " Is Active <br>";
                // $inactive = true;
                $data     = array(
                    "title" => "Cronjob Alert",
                    "url" => $url,
                    "last_run" => time_elapsed_string($last_updatedtime, $timezone, $full = true),
                    "priority" => "high",
                    "cron_duration" => $cron_duration
                );

                //$this->push_notifications->android_notification_topic($data);
                
            }
            else {
                //echo $url . " Is Inactive <br>";
                $inactive = true;
                $timezone = "UTC";
                $data     = array(
                    "title" => "Cronjob Alert",
                    "url" => $url,
                    "last_run" => time_elapsed_string($last_updatedtime, $timezone, $full = true),
                    "priority" => "high",
                    "cron_duration" => $cron_duration
                );

                $this->push_notifications->android_notification_topic($data);
            }
        }

        $this->last_cron_execution_time('cronjob_push_notification', '1m', 'Cronjob to send alerts about stopped cronjob on mobile app');
        
    }

    /**
     * End CronJob For Notification
    */
    public function hourly_cron_notification(){
        $this->cronjob_check_error_in_sell();
        $this->trade_on_off();
        $this->autotradebox();
        $this->newTicket();
        $this->newUser();
    }
    public function cronjob_check_error_in_sell(){
        $res_arr = $this->error_in_sell_report();
        if($res_arr['status']){
            $timezone = "UTC";
                $data     = array(
                    "title" => "Error In Sell Alert",
                    "url" => "There are Some Trades which are in Error",
                    "last_run" => "",
                    "priority" => "medium",
                    "cron_duration" => ""
                );

                $this->push_notifications->android_notification_topic($data);
        }
    }


    public function trade_on_off(){
        $res_arr = $this->check_trading_on_off();
        if(!$res_arr['status']){
            $timezone = "UTC";
                $data     = array(
                    "title" => "Trading Alert",
                    "url" => "All Type of Trading is Off By Admin",
                    "last_run" => "",
                    "priority" => "medium",
                    "cron_duration" => ""
                );

                $this->push_notifications->android_notification_topic($data);
        }
    }

    public function autotradebox(){
        $res_arr = $this->check_auto_trading_last_hour();
        if(!$res_arr['status']){
            $timezone = "UTC";
                $data     = array(
                    "title" => "Auto Trade Alert",
                    "url" => "No New Trade is created in last One Hour",
                    "last_run" => "",
                    "priority" => "medium",
                    "cron_duration" => ""
                );

                $this->push_notifications->android_notification_topic($data);
        }
    }

    public function newTicket(){
        $res_arr = $this->check_new_ticket();
        if($res_arr['status']){
            $timezone = "UTC";
                $data     = array(
                    "title" => "New Ticket Alert",
                    "url" => "There is a new ticket in BackOffice",
                    "last_run" => "",
                    "priority" => "medium",
                    "cron_duration" => ""
                );

                $this->push_notifications->android_notification_topic($data);
        }
    }

    public function newUser(){
        $res_arr = $this->check_new_user();
        if($res_arr['status']){
            $timezone = "UTC";
                $data     = array(
                    "title" => "User Need Approval",
                    "url" => "Some Users Need Approval in BackOffice",
                    "last_run" => "",
                    "priority" => "medium",
                    "cron_duration" => ""
                );

                $this->push_notifications->android_notification_topic($data);
        }
    }
    /********************************************************************************************************* */
    /********************************************************************************************************* */
    /********************************************************************************************************* */
    
    /**
     * @Author: Muhammad Waqar
     * @DateTime : 17-12-2019 (Tuesday)
     * @Description : API For Dashboard Of APP. 
     * Cronjob To Run Every Minute to Check If There is any stopped Cronjob
     */
    public function app_dashboard(){
        $crons_json = $this->first_cron_cron_listing();
        $error_in_sell_json = $this->error_in_sell_report();
        $auto_trade_last_hour = $this->check_auto_trading_last_hour();
        $trading_on_off = $this->check_trading_on_off();
        $new_ticket_json = $this->check_new_ticket();
        $new_user_json = $this->check_new_user();

        $final_json_arr = array(
            "cronbox" => $crons_json,
            "errorbox" => $error_in_sell_json,
            "autotradebox" => $auto_trade_last_hour,
            "tradeonoff" => $trading_on_off,
            "ticketbox" => $new_ticket_json,
            "newuserbox" => $new_user_json
        );

        $returnArr = array(
            "status" => 200,
            "data" => $final_json_arr,
            "message" => "Api Executed Successfully"
        );
        echo json_encode($returnArr);
    }
    /**
     * End API For Dashboard Of APP
    */

    //HELPERS: 
    public function first_cron_cron_listing(){
        
        // $data = $this->mongo_db->get('cronjob_execution_logs');
        // $data = iterator_to_array($data);

        $api_url = "http://35.171.172.15:3000/api/all_cronjobs";
        $data_json = file_get_contents($api_url);
        $data_arr = (array)json_decode($data_json);
        $data_arr = (array)$data_arr['data'];
        $arr = json_decode(json_encode($data_arr), TRUE);
        
        $inactive = false;
        foreach($arr as $row){
            $url = $row['name'];
            // $this->mongo_db->where(array('name' => $url));
            // $this->mongo_db->limit(1);
            // $this->mongo_db->order_by(array('_id' => -1));
            // $res = $this->mongo_db->get('cronjob_execution_logs');
            // $res_arr = iterator_to_array($res);

            // $cron_duration = $res_arr[0]['cron_duration'];
            // $last_updatedtime = $res_arr[0]['last_updated_time_human_readible'];

            $post['name'] = $url;
            $curl = curl_init();

            //
            curl_setopt_array($curl, array(
                CURLOPT_PORT => "3000",
                CURLOPT_URL => "http://35.171.172.15:3000/api/all_cronjobs",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($post),
                CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                //echo $response;
            }
            $res_arr = (array)json_decode($response);
            $res_arr = $res_arr['data'];
            $res_arr = json_decode(json_encode($res_arr),TRUE);
            $cron_duration = $res_arr['cron_duration'];
            $last_updatedtime = $res_arr['last_updated_time_human_readible'];

            // $cron_duration_arr = explode(' ',$cron_duration);

            //Umer Abbas [6-11-19]
            $duration_arr = str_split($cron_duration,1);
            $time = array_pop($duration_arr);
            $add_time = strtoupper($time);
            $duration = implode('', $duration_arr);

            $dt = new DateTime($last_updatedtime);
            // echo $dt->format('Y-m-d H:i:s');

            $last_time = date("Y-m-d H:i:s", strtotime($last_updatedtime));

            if($time == 's'){
                $padding_duration = 2;
                $padding_time = "M";
                $interval_str = "PT$padding_duration$padding_time$duration$add_time";
                $dt->add(new DateInterval($interval_str));
                $param = 12;
                
            }else if($time == 'm'){
                
                $padding_duration = 5;
                $duration = $duration + $padding_duration;
                $interval_str = "PT$duration$add_time";
                $dt->add(new DateInterval($interval_str));
                $param = 300;
                
            }else if($time == 'h'){
                
                $padding_duration = 15;
                $padding_time = "M";
                $interval_str = "PT$duration$add_time$padding_duration$padding_time";
                $dt->add(new DateInterval($interval_str));

                $param = 900;
                
            }else if($time == 'd'){
                
                $interval_str = "P$duration$add_time";
                $dt->add(new DateInterval($interval_str));
                $padding_duration = 1;
                $padding_time = "H";
                $interval_str = "PT$padding_duration$padding_time";
                $dt->add(new DateInterval($interval_str));

                $param = 3600;
                
            }else if($time == 'w'){
                
                $interval_str = "P$duration$add_time";
                $dt->add(new DateInterval($interval_str));
                $padding_duration = 1;
                $padding_time = "H";
                $interval_str = "PT$padding_duration$padding_time";
                $dt->add(new DateInterval($interval_str));

                $param = 3600;
            }
        
            $dt2 = new DateTime();

            if($dt2 <= $dt){
                //echo $url . " Is Active <br>";
                // $inactive = true;
            }else{
                //echo $url . " Is Inactive <br>";
                $inactive = true;
            }
                }

        $data_to_return = array(
            "success" => $inactive,
            "url" => "https://app.digiebot.com/admin/cron_listing"
        );

        return ($data_to_return);
        

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

        if(count($records) > 0){
            $data_to_return = array(
                "success" => true,
                "url" => "http://app.digiebot.com/admin/trading_reports/error_sell"
            );
    
        }else{
            $data_to_return = array(
                "success" => false,
                "url" => "http://app.digiebot.com/admin/trading_reports/error_sell"
            );
        }

        return ($data_to_return);
    }

    public function check_auto_trading_last_hour(){
        $start_date = date("Y-m-d H:i:s", strtotime("-1 hour"));
        $where['status'] = "FILLED";
        $where['trigger_type']['$nin'] = array("no", "", null);
        $where['created_date']['$gte'] = $this->mongo_db->converToMongodttime($start_date);

        $connetct = $this->mongo_db->customQuery();

        $sold1_count = $connetct->sold_buy_orders->count($where);
        $pending1_count = $connetct->buy_orders->count($where);

        $total1_count = $sold1_count + $pending1_count;

        if($total1_count > 0){
            $data_to_return = array(
                "success" => true,
                "url" => "http://app.digiebot.com/admin/reports/order_report"
            );
        }else{
            $data_to_return = array(
                "success" => false,
                "url" => "http://app.digiebot.com/admin/reports/order_report"
            );
        }
        return ($data_to_return);
    }

    public function check_trading_on_off(){
        $urls =  "https://rules.digiebot.com/apiEndPoint/check_trading_status";
        $resp = file_get_contents($urls);
        $data = (array)json_decode($resp);
        extract($data);

        if($trading_status == 'ON'){
            $data_to_return = array(
                "success" => true,
                "url" => $setting_url
            );
        }else{
            $data_to_return = array(
                "success" => false,
                "url" => $url
            );
        }
        return ($data_to_return);
    }

    public function check_new_ticket(){
        $urls =  "https://users.digiebot.com/cronjob/check-for-new-unread-tickets/";
        $resp = file_get_contents($urls);
        $data = (array)json_decode($resp);

        extract($data);

        if($status == 200){
            $data_to_return = array(
                "success" => true,
                "url" => $link
            );
        }else{
            $data_to_return = array(
                "success" => false,
                "url" => $link
            );
        }
        return ($data_to_return);
    }

    public function check_new_user(){
        $urls =  "https://users.digiebot.com/cronjob/get-users-need-approval/";
        $resp = file_get_contents($urls);
        $data = (array)json_decode($resp);

        extract($data);

        if($status == 200){
            $data_to_return = array(
                "success" => true,
                "url" => $link
            );
        }else{
            $data_to_return = array(
                "success" => false,
                "url" => $link
            );
        }
        return ($data_to_return);
    }

    public function last_cron_execution_time($name, $duration, $summary){
        //Hit CURL to update last cron execution time
        $params = [
           'name' => $name,
           'cron_duration' => $duration, 
           'cron_summary' => $summary,
        ];
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => '',
            'req_url' => 'http://35.171.172.15:3000/api/save_cronjob_execution',
        ];
        $resp = hitCurlRequest($req_arr);

    }//End last_cron_execution_time
    public function trade_history_report(){
        $collection_name = "trade_history_logs";
        $db = $this->mongo_db->customQuery(); 
        $coins = $this->mod_coins->get_all_coins();
        $data['coins'] = $coins;

        //delete deta older than 15 days
        $current_to_previous = date('Y-m-d H:i:s', strtotime('-15 days'));
        $converted_time      = $this->mongo_db->converToMongodttime($current_to_previous);
        $where['cron_run_time'] = array('$lte' => $converted_time);
        $where['coin']['$in'] = array_column($coins, 'symbol');
        $db->$collection_name->deleteMany($where);
       //end 

        if($this->input->post('filter_by_coin')){
            $search_arr['coin']['$in'] = $this->input->post('filter_by_coin');
        }else{
            $search_arr['coin']['$in'] = array_column($coins, 'symbol');
        }

        if($this->input->post('filter_by_start_date')){
            $input_start = date('Y-m-d H:00:00', strtotime($_POST['filter_by_start_date']));
            $start_hour_convert = $this->mongo_db->converToMongodttime($input_start);
        
            $search_arr['start_hour'] = $start_hour_convert;
        }
        $sreach_date = $db->$collection_name->find($search_arr);
        $responce = iterator_to_array($sreach_date);

        $config['base_url'] = base_url() .'admin/Api_notifications/trade_history_report';
        $config['total_rows'] = count($responce);
    
        $config['per_page'] = 21;
        $config['num_links'] = 3;
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 4;
        $config['reuse_query_string'] = TRUE;
    
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
    
        $config['prev_link'] = '&laquo;';
    
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
    
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
    
        $config['cur_tag_open'] = '<li class="active"><a href="#"><b>';
        $config['cur_tag_close'] = '</b></a></li>';
    
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
    
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            
        if($page !=0) 
        {
            $page = ($page-1) * $config['per_page'];
        }
        $data["links"] = $this->pagination->create_links();
        $query =
            [
                ['$match' => $search_arr],  
                ['$sort'  => ['start_hour' => -1]],
                ['$skip'  => $page],
                ['$limit' => $config['per_page']],
            ];
        $response_1 = $db->$collection_name->aggregate($query);   
        $response_data = iterator_to_array($response_1);
        $data['final_array'] = $response_data;
        $this->stencil->paint('admin/api_notification/data_history_report',$data);
    }//end function

}

