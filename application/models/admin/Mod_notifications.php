<?php

class mod_notifications extends CI_Model{

    var $notification_meta;

    public function __construct(){
        parent::__construct();

        $this->notification_meta = [
            'types' => [
                "buy_alerts",
                "security_alerts",
                "sell_alerts",
                "trading_alerts",
                "withdraw_alerts",
                "news_alerts",
            ],
            'priority' => [
                'high',
                'medium',
                'low'
            ],
        ];

    }

    //get_notifications
    public function get_notifications($admin_id, $time , $filter) {
        $search_array['admin_id'] = $admin_id;
        $timezone = get_user_timezone($admin_id);

        if($filter == 'important'){

            if ($time == 'today') {
                $date_start = date("Y-m-d 00:00:00", strtotime("today"));
                $date_end = date("Y-m-d 23:59:59", strtotime("today"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "high" ;
            }
            if ($time == 'yesterday') {
                $date_start = date("Y-m-d 00:00:00", strtotime("yesterday"));
                $date_end = date("Y-m-d 23:59:59", strtotime("yesterday"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "high" ;
            }
            if ($time == 'last_week') {
                $date_start = date("Y-m-d 00:00:00", strtotime("-7 days"));
                $date_end = date("Y-m-d 23:59:59", strtotime("-3 days"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "high" ;
            }

        }
        if($filter == 'medium'){
            if ($time == 'today') {
                $date_start = date("Y-m-d 00:00:00", strtotime("today"));
                $date_end = date("Y-m-d 23:59:59", strtotime("today"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "medium" ;
            }
            if ($time == 'yesterday') {
                $date_start = date("Y-m-d 00:00:00", strtotime("yesterday"));
                $date_end = date("Y-m-d 23:59:59", strtotime("yesterday"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "medium" ;
            }
            if ($time == 'last_week') {
                $date_start = date("Y-m-d 00:00:00", strtotime("-7 days"));
                $date_end = date("Y-m-d 23:59:59", strtotime("-3 days"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "medium" ;
            }
            
        }
        if($filter == 'normal'){
            if ($time == 'today') {
                $date_start = date("Y-m-d 00:00:00", strtotime("today"));
                $date_end = date("Y-m-d 23:59:59", strtotime("today"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "low" ;
                
            }
            if ($time == 'yesterday') {
                $date_start = date("Y-m-d 00:00:00", strtotime("yesterday"));
                $date_end = date("Y-m-d 23:59:59", strtotime("yesterday"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "low" ;
                
            }
            if ($time == 'last_week') {
                $date_start = date("Y-m-d 00:00:00", strtotime("-7 days"));
                $date_end = date("Y-m-d 23:59:59", strtotime("-3 days"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "low" ;
                
            }
            
        }else{
            if ($time == 'today') {
                $date_start = date("Y-m-d 00:00:00", strtotime("today"));
                $date_end = date("Y-m-d 23:59:59", strtotime("today"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
            }
            if ($time == 'yesterday') {
                $date_start = date("Y-m-d 00:00:00", strtotime("yesterday"));
                $date_end = date("Y-m-d 23:59:59", strtotime("yesterday"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
            }
            if ($time == 'last_week') {
                $date_start = date("Y-m-d 00:00:00", strtotime("-7 days"));
                $date_end = date("Y-m-d 23:59:59", strtotime("-3 days"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
            }
            
        }

        $this->mongo_db->order_by(array('_id' => -1));
        $this->mongo_db->where($search_array);
        $resp = $this->mongo_db->get('notifications');

        $notification_arr = array(); //iterator_to_array($resp);
        foreach ($resp as $key => $value) {

            $datetime = $value['created_date']->toDateTime();
            $created_date = $datetime->format(DATE_RSS);
            $datetime = new DateTime($created_date);
            $datetime->format('Y-m-d g:i:s A');

            $new_timezone = new DateTimeZone($timezone);
            $datetime->setTimezone($new_timezone);

            $formated_date_time = $datetime->format('Y-m-d g:i:s A');

            $retArr['_id'] = (string) $value['_id'];
            $retArr['admin_id'] = $value['admin_id'];
            $retArr['order_id'] = $value['order_id'];
            $retArr['exchange'] = ($value['exchange'] ?? '');
            $retArr['type'] = $value['type'];
            $retArr['priority'] = $value['priority'];
            $retArr['message'] = $value['message'];
            $retArr['symbol'] = $value['symbol'];
            $retArr['coin_logo'] = $value['coin_logo'];
            $retArr['created_date_human_readable'] = $value['created_date_human_readable'];
            $retArr['created_date'] = $formated_date_time;
            $time_elapsed_string = time_elapsed_string($formated_date_time, $timezone);
            $retArr['time_ago'] = $time_elapsed_string;
            $retArr['status'] = $value['status'];
            array_push($notification_arr, $retArr);
        }
        return $notification_arr;
    } //End get_notifications
    
    //add_notification
    public function add_notification($dataArr){
        
        $created_date = date('Y-m-d G:i:s');

        if ($dataArr['type'] == 'security_alerts') {
            $coin_logo = 'security_alert.png';
        } elseif (!empty($dataArr['symbol'])) {
            $this->load->model('admin/mod_coins');
            $coin_logo = $this->mod_coins->get_coin_logo($dataArr['symbol']);
        } else {
            $coin_logo = '';
        }
        $ins_data = array(
            'admin_id' => ($dataArr['admin_id'] ?? ''),
            'order_id' => ($dataArr['order_id'] ?? ''),
            'exchange' => ($dataArr['exchange'] ?? ''),
            'type' => ($dataArr['type'] ?? ''),
            'priority' => ($dataArr['priority'] ?? ''),
            'message' => strip_tags($dataArr['message'] ?? ''),
            'symbol' => ($dataArr['symbol'] ?? ''),
            'coin_logo' => $coin_logo,
            'interface' => ($dataArr['interface'] ?? ''),
            'status' => '0',
            'created_date_human_readable' => $created_date,
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
        );

        //Insert the record into the database.
        $this->mongo_db->insert('notifications', $ins_data);

        if($this->is_notification_alert_enable($dataArr['admin_id'], $dataArr['type'])){
            $this->send_push_notification($dataArr['admin_id'], $dataArr['type'], $dataArr['message']);
        }
        return true;
    }//End add_notification

    public function send_push_notification($admin_id, $type, $message){

        $this->load->library('push_notifications');
        $tokens = $this->get_device_token($admin_id);
        //$user_info = $this->get_user_settings($admin_id);
        foreach ($tokens as $value) {
            if (!empty($value)) {
                if ($value['device_type'] == 'android') {
                    $device_token = $value['device_token'];
                    $data['title'] = ucfirst(str_replace('_', ' ', $type)).' Notification';
                    $data['msg_desc'] = strip_tags($message);
                    $push = $this->push_notifications->android_notification($data, $device_token);
                } elseif ($value['device_type'] == 'ios') {
                    $device_token = $value['device_token'];
                    $data['title'] = ucfirst(str_replace('_', ' ', $type)).' Notification';
                    $data['msg_desc'] = strip_tags($message);
                    $push = $this->push_notifications->iOS($data, $device_token);
                }
            }
        }
    }

    public function get_device_token($admin_id){

        $this->mongo_db->where(array('admin_id' => $admin_id));
        $get = $this->mongo_db->get('users_device_tokens');

        return iterator_to_array($get);
    }

    public function test(){

        $type = $order['order_type'];
        $notification_message_arr = [
            'security_alerts' => [
                'high' => [
                    'Login Attempted From Account ' . $email . ' From IP address ' . $u_info['IP'] . ' Location ' . $u_info['location'] . 'Device ' . $u_info['Device'] . ' ' . $u_info['Browser'] . '',
                ],
            ],
            'buy_alerts' => [
                'medium' => [
                    $symbol . " Buy " . ucfirst($type) . " Trade is FILLED at price " . $market_price,
                ], 
                'low' => [
                    $symbol . " Order PAUSED Due to Low Quantity By System",
                ],
            ],
            'sell_alerts' => [
                'medium' => [
                    $symbol . " Sell " . ucfirst($type) . " Trade is FILLED at price " . $market_price,
                ],
            ],
            'withdraw_alerts' => [
                'low' => [
                    "Broker Fee " . $commission . " From " . $commissionAsset . " has token on this Trade",
                ],
            ],
            'trading_alerts' => [
                'high' => [
                    $symbol  . ucfirst($type) . " Trade got ERROR Because " . $error_msg,
                ],
                'medium' => [
                    $symbol . " Market Trade is SUBMITTED for Buy",
                    $symbol . " Market Trade is SUBMITTED for Sell",
                    $symbol . " Limit Trade is SUBMITTED for Buy",
                    $symbol . " Limit Trade is SUBMITTED for Sell",
                    $symbol . " STOP_LOSS_LIMIT Trade is SUBMITTED for Sell",
                ],
            ],
        ];
    }

    //get_notification_settings
    public function get_notification_settings($admin_id){
        $filter = [
            '_id' => $this->mongo_db->mongoId((string)$admin_id)
        ];
        $this->mongo_db->where($filter);
        $resp = $this->mongo_db->get('users');
        $user_arr = iterator_to_array($resp);
        $user_arr = $user_arr[0];
        $arr = [
            'security_alerts' => ($user_arr['security_alerts'] ?? 'on'),
            'buy_alerts' => ($user_arr['buy_alerts'] ?? 'on'),
            'sell_alerts' => ($user_arr['sell_alerts'] ?? 'on'),
            'withdraw_alerts' => ($user_arr['withdraw_alerts'] ?? 'on'),
            'trading_alerts' => ($user_arr['trading_alerts'] ?? 'on'),
            'news_alerts' => ($user_arr['news_alerts'] ?? 'on'),
        ];
        return $arr;
    }//End get_notification_settings

    //is_notification_alert_enable
    public function is_notification_alert_enable($admin_id, $type){
        if(empty($admin_id) || empty($type) || $type != 'security_alerts'){
            return false;
        }else{
            $notification_settings = $this->get_notification_settings($admin_id);
            $res = (!empty($notification_settings[$type]) && $notification_settings[$type] == 'off' ? false : true);
            return $res;
        }
    }//End is_notification_alert_enable 
   
}