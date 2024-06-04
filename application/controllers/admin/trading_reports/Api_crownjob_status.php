<?php

defined('BASEPATH') OR exit('No direct script access allowed');



require APPPATH . 'libraries/REST_Controller.php';




class Api_crownjob_status extends REST_Controller {



    function __construct() {
        // Construct the parent class
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }

        
        $this->load->library('push_notifications');
        $this->load->model('admin/mod_cronjob_listing');
        $this->load->model('admin/mod_settings');
        $this->load->model('admin/mod_report');
        $this->load->model('admin/mod_Api_crownjob_status');
        //$this->load->model('admin/mod_cronjob_status');

    }
   

    public function cronjob_process_get(){
        //Adding add_user
            $res = $this->mod_cronjob_listing->get_cron_listing();
            // echo "<pre>";
            // print_r($cron_list);
            // exit;
        if($res){
      
            foreach($res as $res_arr){
        
                $cron_duration = $res_arr['cron_duration'];
                $cron_url = $res_arr['name'];
                $priority = $res_arr['priority'];
                $cron_lastupdate = $res_arr['last_updated_time_human_readible'];
                $cron_duration_arr = explode(' ',$cron_duration);
            
                $duration = $cron_duration_arr[0];
                $time = $cron_duration_arr[1];

                //Umer Abbas [6-11-19]
                $duration_arr = str_split($cron_duration,1);
                $time = array_pop($duration_arr);
                $add_time = strtoupper($time);
                $duration = implode('', $duration_arr);

                $dt = new DateTime($last_updatedtime);

                $last_time = date("Y-m-d H:i:s", strtotime($last_updatedtime));

                if($time == 's'){
                    //$dt2->add(new DateInterval("PT1H2M3S"));
                    $padding_duration = 1;
                    $padding_time = "M";
                    $interval_str = "PT$padding_duration$padding_time$duration$add_time";
                    $dt->add(new DateInterval($interval_str));
                    
                }else if($time == 'm'){
                    
                    $padding_duration = 5;
                    $duration = $duration + $padding_duration;
                    $interval_str = "PT$duration$add_time";
                    $dt->add(new DateInterval($interval_str));

                }else if($time == 'h'){
                    
                    $padding_duration = 15;
                    $padding_time = "M";
                    $interval_str = "PT$duration$add_time$padding_duration$padding_time";
                    $dt->add(new DateInterval($interval_str));
                    
                }else if($time == 'd'){
                    
                    $interval_str = "P$duration$add_time";
                    $dt->add(new DateInterval($interval_str));
                    $padding_duration = 1;
                    $padding_time = "H";
                    $interval_str = "PT$padding_duration$padding_time";
                    $dt->add(new DateInterval($interval_str));
                    
                }else if($time == 'w'){
                    
                    $interval_str = "P$duration$add_time";
                    $dt->add(new DateInterval($interval_str));
                    $padding_duration = 1;
                    $padding_time = "H";
                    $interval_str = "PT$padding_duration$padding_time";
                    $dt->add(new DateInterval($interval_str));

                }

                $dt2 = new DateTime();

                if($dt2 <= $dt){

                    $ab = true;
                    continue;
                    
                }else{

                    $ab = false;
                    
                    $this->push_notifications->push_notification_android($cron_url , $cron_duration ,$cron_lastupdate);
                }
        
                // if($difference_time <= (($duration*$param)+5)){

                //     $ab = true;
                //     continue;
            
                // }else{
            
                //     $ab = false;
                    
                //     $this->push_notifications->push_notification_android($cron_url , $cron_duration ,$cron_lastupdate);
                // }
                
            
            } //end of foreach  

            //$data=$resp;
            
            $message = array(

                'status' => TRUE,

                'data' => $resp ,

                'message' => 'Successfully.',

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }else
        {
            $message = array(

                'status' => FALSE,

                'message' => 'Something Went Wrong',

            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }

    }//end of add_cronjob_process_get

    public function list_cronjob_running_get(){
        //Adding add_user
            $res = $this->mod_cronjob_listing->get_cron_listing();
            // echo "<pre>";
            // print_r($cron_list);
            // exit;
        if($res){
            $resp = array();
            foreach($res as $res_arr){
        
                $cron_duration = $res_arr['cron_duration'];
                $cron_url = $res_arr['name'];
                $priority = $res_arr['priority'];
                $cron_lastupdate = $res_arr['last_updated_time_human_readible'];
                $cron_duration_arr = explode(' ',$cron_duration);
            
                $duration = $cron_duration_arr[0];
                $time = $cron_duration_arr[1];

                //Umer Abbas [6-11-19]
                $duration_arr = str_split($cron_duration,1);
                $time = array_pop($duration_arr);
                $add_time = strtoupper($time);
                $duration = implode('', $duration_arr);

                $dt = new DateTime($last_updatedtime);

                $last_time = date("Y-m-d H:i:s", strtotime($last_updatedtime));

                if($time == 's'){
                    //$dt2->add(new DateInterval("PT1H2M3S"));
                    $padding_duration = 1;
                    $padding_time = "M";
                    $interval_str = "PT$padding_duration$padding_time$duration$add_time";
                    $dt->add(new DateInterval($interval_str));
                    
                }else if($time == 'm'){
                    
                    $padding_duration = 5;
                    $duration = $duration + $padding_duration;
                    $interval_str = "PT$duration$add_time";
                    $dt->add(new DateInterval($interval_str));

                }else if($time == 'h'){
                    
                    $padding_duration = 15;
                    $padding_time = "M";
                    $interval_str = "PT$duration$add_time$padding_duration$padding_time";
                    $dt->add(new DateInterval($interval_str));
                    
                }else if($time == 'd'){
                    
                    $interval_str = "P$duration$add_time";
                    $dt->add(new DateInterval($interval_str));
                    $padding_duration = 1;
                    $padding_time = "H";
                    $interval_str = "PT$padding_duration$padding_time";
                    $dt->add(new DateInterval($interval_str));
                    
                }else if($time == 'w'){
                    
                    $interval_str = "P$duration$add_time";
                    $dt->add(new DateInterval($interval_str));
                    $padding_duration = 1;
                    $padding_time = "H";
                    $interval_str = "PT$padding_duration$padding_time";
                    $dt->add(new DateInterval($interval_str));

                }

                $dt2 = new DateTime();

                if($dt2 <= $dt){

                    $arr['crons_url'] = $cron_url;
                    $arr['crons_duration'] = $cron_duration;
                    $arr['priority'] = $priority;
                    $arr['crons_lastupdate'] = $cron_lastupdate;
                    $arr['status'] = "working";
                    $resp[] = $arr ;
                    $ab = true;
                    continue;
                    
                }else{

                    $ab = false;
                    $arr['crons_url'] = $cron_url;
                    $arr['crons_duration'] = $cron_duration;
                    $arr['priority'] = $priority;
                    $arr['crons_lastupdate'] = $cron_lastupdate;
                    $arr['status'] = "notworking";
                    $resp[] = $arr ;
                    continue;
                }
            
                // $param = 5;
                // switch($time){
                //     case 'second':
                //     $param = 1+120;
                //     break;
                //     case 'minute':
                //     $param = 60+120;
                //     break;
                //     case 'minutes':
                //     $param = 60+120;
                //     break;
                //     case 'hour':
                //     $param = (60 * 60) + 3600;
                //     break;
                //     case 'day':
                //     $param = (60 * 60 * 24) + 3600;
                //     break;
                //     case 'week':
                //     $param = (60 * 60 * 24 * 7)+3600;
                //     break;
                //     default:
                //     $param = 1;
                //     break;
                // }
        
        
                // $last_run_time =  strtotime($res_arr['last_updated_time_human_readible']);
                // $now_time = strtotime(date("Y-m-d H:i:s"));
            
                // $difference_time = $now_time - $last_run_time;
                // $arr =array();
                // if($difference_time <= (($duration*$param)+5)){
                    
                //     $arr['crons_url'] = $cron_url;
                //     $arr['crons_duration'] = $cron_duration;
                //     $arr['priority'] = $priority;
                //     $arr['crons_lastupdate'] = $cron_lastupdate;
                //     $arr['status'] = "0";
                //     $resp[] = $arr ;
                //     $ab = true;
                //     continue;
            
                // }else{

                //     $ab = false;
                //     $arr['crons_url'] = $cron_url;
                //     $arr['crons_duration'] = $cron_duration;
                //     $arr['priority'] = $priority;
                //     $arr['crons_lastupdate'] = $cron_lastupdate;
                //     $arr['status'] = "1";
                //     $resp[] = $arr ;
                //     continue;
                // }
                
                //$resp[] = $arr ;
            } //end of foreach  

            //$data=$resp;
            
            $message = array(

                'status' => TRUE,

                'data' => $resp ,

                'message' => 'Successfully.',

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }else
        {
            $message = array(

                'status' => FALSE,

                'message' => 'Something Went Wrong',

            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }

    }//end of list_cronjob_running_get

    public function tradding_status_get() {

        $trading = $this->mod_settings->get_saved_on_off_trading();
        if($trading){

            $off_arrr = array();

            foreach ($trading as $row) {
                $off_arr =array();
                if($row['status'] == 'off'){
                   $type = $row['type'];
                   $status = 'off';
                   if($type == 'custom_on_of_trading'){

                    $off_arr['custom_off'] = 'Custom Trading Off by Admin';
                    $off_arr['status'] = '1';

                   }else if($type == 'automatic_on_of_trading'){

                    $off_arr['title'] = 'Automatic Trading Off by System';
                    $off_arr['status'] = '1';

                   }else if($type == 'buy_on_of_trading'){

                    $off_arr['title'] = 'Buy Trading Off by Admin'; 
                    $off_arr['status'] = '1';

                   }else if($type == 'sell_on_of_trading'){

                    $off_arr['title'] = 'Sell Trading Off by Admin'; 
                    $off_arr['status'] = '1';

                   }else if($type == 'buy_on_of_manual_trading'){

                    $off_arr['title'] = 'Manual buy Trading Off by Admin'; 
                    $off_arr['status'] = '1';

                   }else if($type == 'sell_on_of_manual_trading'){

                    $off_arr['title'] = 'Manual Sell Trading Off by Admin'; 
                    $off_arr['status'] = '1';

                   }else if($type == 'on_of_live_trading'){

                    $off_arr['title'] = 'Live Trading Off by Admin '; 
                    $off_arr['status'] = '1';

                   }else if($type == 'on_of_test_trading'){
                       
                    $off_arr['title'] = 'Test Trading Off by Admin ';
                    $off_arr['status'] = '1'; 
                   }
    
                   
                   $off_arrr[] = $off_arr; 
                   continue;

                }else if($row['status'] == 'on'){
                    $type = $row['type'];
                    $status = 'on';
                    if($type == 'custom_on_of_trading'){

                        $off_arr['title'] = 'Custom Trading On by Admin';
                        $off_arr['status'] = '0';

                       }else if($type == 'automatic_on_of_trading'){

                        $off_arr['title'] = 'Automatic Trading On by System';
                        $off_arr['status'] = '0';

                       }else if($type == 'buy_on_of_trading'){

                        $off_arr['title'] = 'Buy Trading On by Admin'; 
                        $off_arr['status'] = '0';

                       }else if($type == 'sell_on_of_trading'){

                        $off_arr['title'] = 'Sell Trading On by Admin'; 
                        $off_arr['status'] = '0';

                       }else if($type == 'buy_on_of_manual_trading'){

                        $off_arr['title'] = 'Manual buy Trading On by Admin'; 
                        $off_arr['status'] = '0';

                       }else if($type == 'sell_on_of_manual_trading'){

                        $off_arr['title'] = 'Manual Sell Trading On by Admin'; 
                        $off_arr['status'] = '0';

                       }else if($type == 'on_of_live_trading'){

                        $off_arr['title'] = 'Live Trading On by Admin '; 
                        $off_arr['status'] = '0';

                       }else if($type == 'on_of_test_trading'){

                        $off_arr['title'] = 'Test Trading On by Admin '; 
                        $off_arr['status'] = '0';
                       }
                       $off_arrr[] = $off_arr; 
                       continue;
                }
            }   //%%%%%% --  End of foreach -- %%%%%%%%%%%
            
            
            $message = array(

                'status' => TRUE,

                'data' => $off_arrr ,

                'message' => 'Successfully.',

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }else
        {
            $message = array(

                'status' => FALSE,

                'message' => 'Something Went Wrong',

            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }
    } //End of get_tradding_status

    public function balance_reports_post(){


        $coin = $this->post('coin');
        $user_id = $this->post('user_id');
        
        if($this->post()){
            $res = $this->mod_Api_crownjob_status->getallusers($coin = $coin  ,$user_id = $user_id);
        }else{
            $res = $this->mod_Api_crownjob_status->getallusers();
        }
            
        if($res){

            
            $message = array(

                'status' => TRUE,

                'data' => $res ,

                'message' => 'Successfully.',

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }else
        {
            $message = array(

                'status' => FALSE,

                'message' => 'Something Went Wrong',

            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }


    }//end of balance_reports_get

    public function users_get(){

        $resp = $this->mongo_db->get('users');
        $user_arr = iterator_to_array($resp);
        $res = array();
        foreach($user_arr as $row){
            $arr = array();
            $id = (string) $row['_id'];
            $arr['id'] = $id;
            $arr['username'] = $row['username'];

            $res[] = $arr; 
        }

            
        if($res){
            
            $message = array(

                'status' => TRUE,

                'data' => $res ,

                'message' => 'Successfully.',

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }else
        {
            $message = array(

                'status' => FALSE,

                'message' => 'Something Went Wrong',

            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }


    }//end of users_get

    public function server_status_get(){
        // $sdate = $this->post('sdate');
        // $edate = $this->post('edate');
        // $status = $this->post('status');

        // $created_datetime = date('Y-m-d G:i:s', strtotime($sdate));
        //     $orig_date = new DateTime($created_datetime);
        //     $orig_date = $orig_date->getTimestamp();
        //     $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

        //     $created_datetime22 = date('Y-m-d G:i:s', strtotime($edate));
        //     $orig_date22 = new DateTime($created_datetime22);
        //     $orig_date22 = $orig_date22->getTimestamp();
        //     $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);

        
    
        $res = $this->mod_Api_crownjob_status->is_server_is_closed();
            
        if($res == true){
            $status = "Server not Working.";
            $this->push_notifications->qnotification_android($status);
            
            $message = array(

                'status' => TRUE,

                'data' => $status ,

                'message' => 'Server not Working.',

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }else
        {
            $message = array(

                'status' => FALSE,

                'message' => 'Server Working Perfect',

            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }


    }//end of server_status_get


    public function user_trade_history_report_post(){
        $id = $this->post("id");
        //$startDate = $this->post("startDate");
        //$endDate = $this->post("endDate");

        
        if($this->post()){
           
            $user_id = $id;
            $data['rearrangedFinalData'] = $this->mod_Api_crownjob_status->get_user_trade_info($user_id);
        }

        if($user_id){

          
            
            $message = array(

                'status' => TRUE,

                'data' => $data['rearrangedFinalData'] ,

                'message' => 'Working.',

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }
        else
        {
          
            $message = array(

                'status' => FALSE,

                'message' => 'Not working',

            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }
        // echo json_encode($data['rearrangedFinalData']);


    }//end of server_status_get

    public function check_order_duplication_post(){
        $status = $this->post("status");
        $startDate = $this->post("startDate");
        $endDate = $this->post("endDate");

        $startDate = date('Y-m-d G:i:s', strtotime($startDate));
        

        $orig_date = new DateTime($startdate);
        $orig_date = $orig_date->getTimestamp();
        $startDate = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

        $endDate = date('Y-m-d G:i:s', strtotime($endDate));
        //$start = (string) $start;
        $orig_date22 = new DateTime($enddate);
        $orig_date22 = $orig_date22->getTimestamp();
        $endDate = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
        
        
        
        // if($this->post()){
           
            
            $data['rearrangedFinalData'] = $this->mod_Api_crownjob_status->get_order_logs($startDate , $endDate ,$status);
        // }

        // if($user_id){

          
            
        //     $message = array(

        //         'status' => TRUE,

        //         'data' => $data['rearrangedFinalData'] ,

        //         'message' => 'Working.',

        //     );

        //     $this->set_response($message, REST_Controller::HTTP_CREATED);
        // }
        // else
        // {
          
        //     $message = array(

        //         'status' => FALSE,

        //         'message' => 'Not working',

        //     );
        //     $this->set_response($message, REST_Controller::HTTP_CREATED);
        // }
         echo json_encode($data['rearrangedFinalData']);


    }//end of server_status_get

    public function user_trade_profit_report_post(){
        $id = $this->post("id");
        //$startDate = $this->post("startDate");
        //$endDate = $this->post("endDate");

        
        if($this->post()){
           
            $user_id = $id;
            $data['rearrangedFinalData'] = $this->mod_Api_crownjob_status->get_user_trade_info($user_id);
        }

        if($user_id){

          
            
            $message = array(

                'status' => TRUE,

                'data' => $data['rearrangedFinalData'] ,

                'message' => 'Working.',

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }
        else
        {
          
            $message = array(

                'status' => FALSE,

                'message' => 'Not working',

            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }
        
    }//user_trade_history_report_post

    public function add_records_post(){

        $id = $this->post("id");
        $coin = $this->post("coin");
        $price = $this->post("price");
        $qty = $this->post("qty");
        $date = $this->post("date");
        $rate  = $this->post("rate");
        $date_mongo = $this->mongo_db->converToMongodttime($date);

        
        if($this->post()){
           
            $arr['id'] = $id;
            $arr['coin'] = $coin;
            $arr['price'] = $price;
            $arr['qty'] = $qty;
            $arr['date'] = $date_mongo;
            $arr['rate'] = $rate;
            
            
            

            $data = $this->mod_Api_crownjob_status->add_records($arr);
        }

        if($data == true){

          
            
            $message = array(

                'status' => TRUE,

                'data' => $arr ,

                'message' => 'Working.',

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }
        else
        {
          
            $message = array(

                'status' => FALSE,

                'message' => 'Not working',

            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }
        
    }//user_trade_history_report_post

    public function fetch_records_post(){


        $data['abc'] = $this->mod_Api_crownjob_status->fetch_record();
        

        if($data){

          
            
            $message = array(

                'status' => TRUE,

                'data' => $data['abc'] ,

                'message' => 'Working.',

            );

            $this->set_response($message, REST_Controller::HTTP_OK);
        }
        else
        {
          
            $message = array(

                'status' => FALSE,

                'message' => 'Not working',

            );
            $this->set_response($message, REST_Controller::HTTP_OK);
        }
        
    }//user_trade_history_report_post
    public function test(){
        return $this->response(NULL, REST_Controller::HTTP_OK);
    }


}



