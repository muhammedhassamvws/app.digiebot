<?php
require APPPATH . '/libraries/CreatorJwt.php';

class Mod_jwt extends CI_Model
{
    public function __construct()
    {
        
        parent::__construct();
        $this->objOfJwt = new CreatorJwt();
        //header('Content-Type: application/json');
    }

    /*************Ganerate token this function use**************/

    public function LoginToken($admin_id, $username){
        $tokenData['id']        = (string)$admin_id;
        $tokenData['username']  = $username;
        // $tokenData['iat']       =  $this->mongo_db->converToMongodttime(Date('Y-m-d H:i:s'));
        // $tokenData['exp']       =  $this->mongo_db->converToMongodttime(Date('Y-m-d h:i:s', strtotime('20 days')));
        // $tokenData['timeStamp'] =  Date('Y-m-d h:i:s', strtotime('20 days'));

        $jwtToken               =  $this->objOfJwt->GenerateToken($tokenData);
        // echo $jwtToken;
        $jwtToken               =  'Token '.$jwtToken;
        $this->GetTokenData($jwtToken);
        return $jwtToken;
    }
    public function custom_token($admin_id){
          $tokenData['id']        = (string)$admin_id;
        //$tokenData['username']  = $username;
        // $tokenData['iat']       =  $this->mongo_db->converToMongodttime(Date('Y-m-d H:i:s'));
        // $tokenData['exp']       =  $this->mongo_db->converToMongodttime(Date('Y-m-d h:i:s', strtotime('20 days')));
        // $tokenData['timeStamp'] =  Date('Y-m-d h:i:s', strtotime('20 days'));

        $jwtToken               =  $this->objOfJwt->GenerateToken($tokenData);
        // echo $jwtToken;
        $jwtToken               =  'Token '.$jwtToken;
        $this->GetTokenData($jwtToken);
        return $jwtToken;
    } 
   /*************Use for token then fetch the data**************/        
    public function GetTokenData($token){
        // $received_Token = $this->input->request_headers('Authorization');
        $getToken =   trim($token);
        // echo "<pre>";print_r($getToken);
        try
        {   
            $jwtData = $this->objOfJwt->DecodeToken($getToken);
            // echo json_encode($jwtData);
            return json_encode($jwtData);
        }
        catch (Exception $e)
        {
            // echo '<br>data else';
            // http_response_code('401');
            // echo json_encode(array( "status" => false, "message" => $e->getMessage()));
            return false;
            exit;
        }
        // return $jwtData;
    }
    public function isValidUser($admin_id){
        //print_r($admin_id);exit;
        $db = $this->mongo_db->customQuery();
        $pipeline= [
            ['$match'=>['_id'=>$this->mongo_db->mongoId((string)$admin_id)]
            ]
        ];
        //$record = $db->users->count($this->mongo_db->mongoId((string)$admin_id));
        $record = $db->users->aggregate($pipeline);
        $record_arr = iterator_to_array($record);
        if(count($record_arr) > 0){
            return true;
        }else{
            return false;
        }
    }
    public function get_announcements($admin_id){
        
        //print_r($admin_id);exit;
        $db = $this->mongo_db->customQuery();
        $today_date = $this->mongo_db->converToMongodttime(date('Y-m-d'));
        $pipeline= [
            ['$match'=>['user_id'=>$this->mongo_db->mongoId((string)$admin_id),'showOnApp'=>true,'expiry'=>['$gte'=>$today_date]]
            ]
        ];
        //$record = $db->users->count($this->mongo_db->mongoId((string)$admin_id));
        $record = $db->user_alerts->aggregate($pipeline);
        $record_arr = iterator_to_array($record);
        if(count($record_arr) > 0){
            return $record_arr[0];
        }else{
            return false;
        }
    }
      public function custom_token_bearer($admin_id){
          $tokenData['id']        = (string)$admin_id;
        //$tokenData['username']  = $username;
        // $tokenData['iat']       =  $this->mongo_db->converToMongodttime(Date('Y-m-d H:i:s'));
        // $tokenData['exp']       =  $this->mongo_db->converToMongodttime(Date('Y-m-d h:i:s', strtotime('20 days')));
        // $tokenData['timeStamp'] =  Date('Y-m-d h:i:s', strtotime('20 days'));

        $jwtToken               =  $this->objOfJwt->GenerateToken($tokenData);
        // echo $jwtToken;
        $jwtToken               =  'Bearer '.$jwtToken;
        $this->GetTokenData($jwtToken);
        return $jwtToken;
    } 
    public function force_update_check($device,$version){
        
        //print_r($admin_id);exit;
        $db = $this->mongo_db->customQuery();
        //$record = $db->users->count($this->mongo_db->mongoId((string)$admin_id));
        $record = $db->Mobile_app_version_control->find(['device_type'=>$device]);
        $record_arr = iterator_to_array($record);
        if(count($record_arr) > 0){
            $version_ios = explode('.',$version);
            $version_check = '';
            foreach ($version_ios as $value) {
                $version_check .= $value;
            }
            $force_version = (int)$record_arr[0]['version_in_decimal'];
            if((int)$version_check < $force_version){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}