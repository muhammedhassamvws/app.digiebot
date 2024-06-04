<?php
/**
 * 
 */
class mod_candle_base extends CI_Model
{
	
	function __construct()
	{
		# code...
	}

	public function save_candle_base($data)
	{
		//date_default_timezone_set("ASIA/KARACHI");
		extract($data);

		$start_date = date("Y-m-d g:i:s", strtotime($start_date));
		$end_date = date("Y-m-d g:i:s", strtotime($end_date));
		$start_date_mongo = $this->mongo_db->converToMongodttime($start_date);
		$end_date_mongo = $this->mongo_db->converToMongodttime($end_date);
		$ins_array = array(
			'coin' => $coins,
			'start_date' => $start_date_mongo,
			'end_date'=> $end_date_mongo,
			'base_value' => $base_candle,
			'interval' => $interval

		);

		$ins = $this->mongo_db->insert('base_candles',$ins_array);

		if ($ins) {
			return true;
		}
		else
		{
			return false;
		}
	}

		public function edit_candle_base($data)
	{
		//date_default_timezone_set("ASIA/KARACHI");
		extract($data);

		$start_date = date("Y-m-d g:i:s", strtotime($start_date));
		$end_date = date("Y-m-d g:i:s", strtotime($end_date));
		$start_date_mongo = $this->mongo_db->converToMongodttime($start_date);
		$end_date_mongo = $this->mongo_db->converToMongodttime($end_date);
		$ins_array = array(
			'coin' => $coins,
			'start_date' => $start_date_mongo,
			'end_date'=> $end_date_mongo,
			'base_value' => $base_candle,
			'interval' => $interval

		);
			$this->mongo_db->where(array('_id' => $id));
			$this->mongo_db->set($ins_array);
			$ins = $this->mongo_db->update('base_candles',$ins_array);

		if ($ins) {
			return true;
		}
		else
		{
			return false;
		}
	}

	public function get_all_data()
	{
		$get_data = $this->mongo_db->get('base_candles');
		$final_arr = array();
		foreach ($get_data as $key => $value) {
			if (!empty($value)) {
				$datetime = $value['start_date']->toDateTime();
		        $created_date = $datetime->format(DATE_RSS);

		        $datetime = new DateTime($created_date);
		        $datetime->format('Y-m-d g:i:s A');

		        $formated_date_time =  $datetime->format('Y-m-d g:i:s A');

				$datetime1 = $value['end_date']->toDateTime();
		        $created_date1 = $datetime1->format(DATE_RSS);

		        $datetime1 = new DateTime($created_date1);
		        $datetime1->format('Y-m-d g:i:s A');

		        $formated_date_time1 =  $datetime1->format('Y-m-d g:i:s A');


				$returnArr['id'] = $value['_id'];
				$returnArr['start_date'] = $formated_date_time;
				$returnArr['coin'] = $value['coin'];
				$returnArr['end_date'] = $formated_date_time1;
				$returnArr['base_value'] = $value['base_value'];
				$final_arr[] = $returnArr;
			}
		}
		return $final_arr;
	}

	public function get_base($id)
	{
		$this->mongo_db->where(array('_id' => $id));
		$get_data = $this->mongo_db->get('base_candles');
		$final_arr = array();
		foreach ($get_data as $key => $value) {
			if (!empty($value)) {
				$datetime = $value['start_date']->toDateTime();
		        $created_date = $datetime->format(DATE_RSS);

		        $datetime = new DateTime($created_date);
		        $datetime->format('Y-m-d g:i:s A');

		        $formated_date_time =  $datetime->format('Y-m-d g:i:s A');

				$datetime1 = $value['end_date']->toDateTime();
		        $created_date1 = $datetime1->format(DATE_RSS);

		        $datetime1 = new DateTime($created_date1);
		        $datetime1->format('Y-m-d g:i:s A');

		        $formated_date_time1 =  $datetime1->format('Y-m-d g:i:s A');


				$returnArr['id'] = $value['_id'];
				$returnArr['start_date'] = $formated_date_time;
				$returnArr['coin'] = $value['coin'];
				$returnArr['end_date'] = $formated_date_time1;
				$returnArr['base_value'] = $value['base_value'];
				$final_arr[] = $returnArr;
			}
		}
		return $final_arr;
	}

	public  function get_base_values($coin,$start_date,$interval)
	{
		
		$start_date = date("Y-m-d g:i:s", strtotime($start_date));
		
		$start_date_mongo = $this->mongo_db->converToMongodttime($start_date);

		$params['coin'] = $coin;
		$params['start_date'] 	= array('$lte' => $start_date_mongo);
		$params['end_date'] 	= array('$gte' => $start_date_mongo);
		$params['interval'] 	= $interval;
		$this->mongo_db->where($params);
		$this->mongo_db->sort(array('_id' => -1));
		$this->mongo_db->limit(1);
		$res = $this->mongo_db->get('base_candles');

		foreach ($res as $key => $value) {
			if (!empty($value)) {
				$datetime = $value['start_date']->toDateTime();
		        $created_date = $datetime->format(DATE_RSS);

		        $datetime = new DateTime($created_date);
		        $datetime->format('Y-m-d g:i:s A');

		        $formated_date_time =  $datetime->format('Y-m-d g:i:s A');

				$datetime1 = $value['end_date']->toDateTime();
		        $created_date1 = $datetime1->format(DATE_RSS);

		        $datetime1 = new DateTime($created_date1);
		        $datetime1->format('Y-m-d g:i:s A');

		        $formated_date_time1 =  $datetime1->format('Y-m-d g:i:s A');


				$returnArr['id'] = $value['_id'];
				$returnArr['start_date'] = $formated_date_time;
				$returnArr['coin'] = $value['coin'];
				$returnArr['end_date'] = $formated_date_time1;
				$returnArr['base_value'] = $value['base_value'];
				$final_arr[] = $returnArr;
			}
		}
		return $final_arr[0]['base_value'];
	}
}
?>