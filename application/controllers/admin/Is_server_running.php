<?php
class Is_server_running extends CI_Controller {
	
	
	function __construct() {
		parent::__construct();
	}
	// public function index() {

	// 	$response = $this->is_server_is_closed();
		
	// 	if($response){
	// 		$this->off_trading_automatically();
	// 	}

	// 	$date = date('Y-m-d H:i:s');
	// 	$created_date = $this->mongo_db->converToMongodttime($date);
	// 	$ins_arr = array('is_server_running'=>'yes','created_date'=>$created_date);
	// 	$this->mongo_db->insert('is_server_running',$ins_arr);
	// 	$this->delete_old_record();

	// 	$this->last_cron_execution_time('Is_server_running', '1m', 'Cronjob to find out if the server is up and running.');
		
	// }//End of index

	// public function is_server_is_closed(){
	// 	$date = date('Y-m-d H:i:s',strtotime('-15 minutes'));
	// 	$created_date = $this->mongo_db->converToMongodttime($date);
	// 	$db = $this->mongo_db->customQuery();
	// 	$search['created_date'] =array('$gte'=>$created_date);
	// 	$response_obj = $db->is_server_running->find($search);
	// 	$response = iterator_to_array($response_obj);

	// 	$resp = true;
	// 	if(count($response )>0){
	// 		$resp =false;
	// 	}
	// 	return $resp;
	// }//End of is_server_is_closed

	// public function delete_old_record(){
	// 	$date = date('Y-m-d H:i:s',strtotime('-20 minutes'));
	// 	$created_date = $this->mongo_db->converToMongodttime($date);
	// 	$db = $this->mongo_db->customQuery();
	// 	$search['created_date'] =array('$lte'=>$created_date);
	// 	$response = $db->is_server_running->deleteMany($search);
	// }//End of delete_old_record

	// public function off_trading_automatically(){
	// 	$date = date('Y-m-d H:i:s');
	// 	$created_date = $this->mongo_db->converToMongodttime($date);
	// 	$this->mongo_db->where(array('type'=>'automatic_on_of_trading'));
	// 	$upd_arr = array('status'=>'off','created_date'=>$created_date);
	// 	$this->mongo_db->set($upd_arr);
	// 	$resp = $this->mongo_db->update('trading_on_off_collection');

	// }//End of off_trading_automatically

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
	
}//End of controller

