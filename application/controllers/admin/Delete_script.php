<?php
/**
 *
 */
class Delete_script extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    // public function index() {
    //     echo '<span style="color:red;font-size:25px;text-align:center;position: absolute;left: 25%;right: 25%;top: 50%;border: 103px solid;">This Controller is for deleting the scripts, its dangerous, don\'t make interact with this</span>';
    // }

    // public function run() {
    //     log_message('ERROR', 'Custom error here.');
    //     echo "Hello";
    // }

    // public function delete_completed_ready_temp_data() {
    //     $db = $this->mongo_db->customQuery();
    //     $search['order_status'] = 'complete';
    //     $response[] = $db->ready_orders_for_buy_ip_based->deleteMany($search);
    //     $db2 = $this->mongo_db->customQuery();
    //     $search2['order_status'] = 'soft_delete';
    //     $response[] = $db2->ready_orders_for_sell_ip_based->deleteMany($search2);

    //     //Save last Cron Executioon
    //     $this->last_cron_execution_time('delete_completed_ready_temp_data', '30m', 'Cronjob to delete completed ready orders for buy ip based and ready orders for sell ip based');

    // }//End of delete_completed_ready_temp_data

    // public function delete_order_time_track_collection(){
    //     $prevouse_date = date('Y-m-d H:i:s', strtotime('-4 minute'));
	// 	$date = $this->mongo_db->converToMongodttime($prevouse_date);
    //     $where['created_time_obj'] = array('$lte'=>$date);
    //     $response[] = $db2->ready_orders_for_sell_ip_based->deleteMany($where);
    // }//End of delete_order_time_track_collection


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


}//End 