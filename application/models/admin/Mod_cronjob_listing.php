<?php
  /**
   *
   */
  class mod_cronjob_listing extends CI_Model
  {

    function __construct()
    {
      parent::__construct();

    }

    public function get_cron_listing(){
        //$data = $this->mongo_db->get('cronjob_execution_logs');
        //$api_url = "http://35.171.172.15:3000/api/all_cronjobs";
        $data_json = file_get_contents('http://35.171.172.15:3000/api/all_cronjobs');
        //echo '<pre>ssada';print_r($data_json);exit;
        $data_arr = (array)json_decode($data_json,true);
        $data_arr = (array)$data_arr['data'];
        $arr = json_decode(json_encode($data_arr), TRUE);
        echo "<pre>";
        print_r($arr);
        exit;
        return $arr['data'];
      //echo 'Listing taking too much time..';exit;
    }

    public function add_cronjob($data){
      extract($data);
      $ins_data = array(
          'cron_name' => $cron_name,
          'cron_duration' => $cron_duration,
      );

      $cron_id = $this->mongo_db->insert('cronbjob_listing',$ins_data);

      return $cron_id;
    }

    public function check_when_last_cron_ran($url){

      $post['name'] = $url;
      $curl = curl_init();

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
        //$dt2->add(new DateInterval("PT1H2M3S"));
        $padding_duration = 2;
        $padding_time = "M";
        $interval_str = "PT$padding_duration$padding_time$duration$add_time";
        $dt->add(new DateInterval($interval_str));

        //$offset_time = date("Y-m-d H:i:s", strtotime("+ 1 minute", strtotime($last_time)));

        $param = 12;
        
      }else if($time == 'm'){
        
        $padding_duration = 5;
        $duration = $duration + $padding_duration;
        $interval_str = "PT$duration$add_time";
        $dt->add(new DateInterval($interval_str));

        //$offset_time = date("Y-m-d H:i:s", strtotime("+ 5 minute", strtotime($last_time)));

        $param = 300;
        
      }else if($time == 'h'){
        
        $padding_duration = 15;
        $padding_time = "M";
        $interval_str = "PT$duration$add_time$padding_duration$padding_time";
        $dt->add(new DateInterval($interval_str));

        //$offset_time = date("Y-m-d H:i:s", strtotime("+ 1 minute", strtotime($last_time)));
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
      //   $curr_date = date("Y-m-d H:i:s");
      //   $date_diff = strtotime($curr_date) - strtotime($last_time);

      //   $chk_time = ($param * $duration);
      //  if($date_diff <= $chk_time){
      //     return true;
      //   }else{
      //     return false;
      //   }
      // die('end forcefully');

      $dt2 = new DateTime();

      if($dt2 <= $dt){
          return true;
      }else{
          return false;
      }
      
      // $cron_duration_arr = explode(' ',$cron_duration);

      // $duration = $cron_duration_arr[0];
      // $time = $cron_duration_arr[1];

      // $param = 5;
      // switch($time){
      //   case 's':
      //     $param = 1+120;
      //     break;
      //   case 'm':
      //     $param = 60+120;
      //     break;
      //   case 'h':
      //     $param = (60 * 60) + 3600;
      //     break;
      //   case 'd':
      //     $param = (60 * 60 * 24) + 3600;
      //     break;
      //   case 'w':
      //     $param = (60 * 60 * 24 * 7)+3600;
      //     break;
      //   default:
      //     $param = 1;
      //     break;
      // }

      // $last_run_time =  strtotime($res_arr[0]['last_updated_time_human_readible']);
      // $now_time = strtotime(date("Y-m-d H:i:s"));

      // $difference_time = $now_time - $last_run_time;

      // if($difference_time <= (($duration*$param)+5)){
      //     return true;
      // }else{
      //     return false;
      // }


    }
    public function update_cronjob_priority($url_id, $priority) {

      $upd_arr['priority'] = $priority;

      $this->mongo_db->where(array('_id' => $url_id));

      $this->mongo_db->set($upd_arr);

      $this->mongo_db->update('cronjob_execution_logs');
    }

    public function delete_cronjob_listing($url_id) {

      $this->mongo_db->where(array('_id' => $url_id));
      $this->mongo_db->delete('cronjob_execution_logs');
      return true;
    }
  }

?>
