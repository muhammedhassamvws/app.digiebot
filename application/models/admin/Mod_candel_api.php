<?php

class mod_candel_api extends CI_Model {

	

	function __construct(){

		

        parent::__construct();

    }



     public function get_candelstick_data1()

 	{

		$created_datetime = date('Y-m-d G:i:s');

		$orig_date = new DateTime($created_datetime);

		$orig_date = $orig_date->getTimestamp();



		//echo $orig_date*1000; exit;





		// $dt = '1523088773000';

		// 		$datetime = $dt->toDateTime();

		// 		$created_date = $datetime->format(DATE_RSS);



		// 		$datetime = new DateTime($created_date);

		// 		$dt1 = $datetime->format('Y-m-d g:i:s A');



		// 		echo '<pre>';

		// 		print_r($dt1);

		// 		exit;













		$global_symbol = $this->session->userdata('global_symbol');





		

		$runQuery= $this->mongo_db->customQuery();



		// $resArr  = $runQuery->market_chart->drop();



		// echo '<pre>';

		// print_r($resArr);



		// exit;



		// echo $runQuery->market_chart->count();exit;







			 $options = array(



			 	'sort'=>array('created_date'=>-1),

			

			  'limit' => 4

			  

			);





		 $timestamp = '1519639200000';

		 $timestamp_obj =$this->mongo_db->timestamp($timestamp);





		

		$rep	=  $runQuery->chart_target_zones->find(array());



		





		// foreach ($rep as $key => $value) {

		// 	echo '<pre>';

		// 	print_r($value);

			

		// }



		// exit;



 

			 // db.collection.find({ Expiration: { $lte: new Date() } })







    $id =$this->mongo_db->mongoId('5ac74e971c0b76175c644b54');











		$this->mongo_db->where(array('coin'=> $global_symbol ,'periods'=>'1h'));

		$this->mongo_db->where_gte('_id', $id);



		//$this->mongo_db->where_gt('foo', 20);



		$this->mongo_db->limit(4);

		$this->mongo_db->sort(array('created_date'=> 'desc'));

		$responseArr = $this->mongo_db->get('market_chart');



		foreach($responseArr as $val_arr){



		  echo "<pre>";

		  print_r($val_arr);



		}



		exit;

		$final_arr = array();

		foreach($responseArr as $val_arr){

		$final_arr[] = array(

			'close' => $val_arr['close'],

			'open' => $val_arr['open'],

			'high' => $val_arr['high'],

			'low' => $val_arr['low'],

			'volume' => $val_arr['volume'],

			'openTime' => $val_arr['openTime'],

			'closeTime' => $val_arr['closeTime']

		);

		}

		// echo "<pre>";

		// print_r($final_arr);

		// exit;

		return $final_arr;





		$timestamp= '1519639200000';



	

		 $timestamp_obj =$this->mongo_db->timestamp($timestamp);

		 $this->mongo_db->where_gt('timestampDate', $timestamp_obj);



	}//end get_candelstick_data





	//get_chart_target_zones

	public function get_chart_target_zones($global_symbol){

		

		$admin_id = $this->session->userdata('admin_id');



		// $this->mongo_db->where(array('admin_id'=> $admin_id,'coin'=> $global_symbol));

		$this->mongo_db->where(array('coin'=> $global_symbol));

		$this->mongo_db->limit(5);

		$this->mongo_db->sort(array('_id'=> 'desc'));

		$responseArr = $this->mongo_db->get('chart_target_zones');



		$fullarray = array();

		foreach ($responseArr as  $valueArr) {	

			$start_date = $valueArr['start_date'];

			$end_date = $valueArr['end_date'];



			if(!empty($valueArr)){



				$returArr['start_value'] = (string)($valueArr['start_value']);

				$returArr['end_value'] = (string)($valueArr['end_value']);

				$returArr['unit_value'] = (string)($valueArr['unit_value']);

				$returArr['start_date'] =(string)json_decode($start_date);

				$returArr['end_date'] = (string)json_decode($end_date);

				$returArr['type'] = $valueArr['type'];

				

				

			}

			

			$fullarray[]= $returArr;

			

		}



		return $fullarray;



	}//end get_chart_target_zones



	 public function get_candelstick_data()

 	{

	



		$global_symbol = $this->session->userdata('global_symbol');



		$this->mongo_db->where(array('coin'=> $global_symbol ,'periods'=>'1h'));

		

		$this->mongo_db->limit(100);

		 $this->mongo_db->sort(array('timestampDate'=> 'DESC'));

		$responseArr = $this->mongo_db->get('market_chart');





		$final_arr = array();



		foreach($responseArr as $val_arr){

			

			

		$final_arr[] = array(

			'timestampDate' =>$val_arr['timestampDate'],

			'close' => $val_arr['close'],

			'open' => $val_arr['open'],

			'high' => $val_arr['high'],

			'low' => $val_arr['low'],

			'volume' => $val_arr['volume'],

			'openTime' => $val_arr['openTime'],

			'closeTime' => $val_arr['closeTime']

		);

		}





		if(count($final_arr)>0){

			$arr_length = count($final_arr);

			echo  'from date'.$from_date = $final_arr[0]['openTime'];echo '<br>';

			echo 'todate'.$end_date = $final_arr[$arr_length-1]['openTime'];

			//exit;

		}

		echo '<pre>';

		print_r($final_arr);

		exit;

		return $final_arr;



	}//end get_candelstick_data







		public function get_candelstick_data_run(){



		 $global_symbol = $this->session->userdata('global_symbol');



		 $this->mongo_db->where(array('coin'=> $global_symbol ,'periods'=>'1h'));

		

		$this->mongo_db->limit(100);

		 $this->mongo_db->sort(array('timestampDate'=> 'DESC'));//ASC/DESC

		$responseArr = $this->mongo_db->get('market_chart');



	



		$final_arr = array();



		foreach($responseArr as $val_arr){

			

			

		$final_arr[] = array(

			'timestampDate' =>$val_arr['timestampDate'],

			'close' => $val_arr['close'],

			'open' => $val_arr['open'],

			'high' => $val_arr['high'],

			'low' => $val_arr['low'],

			'volume' => $val_arr['volume'],

			'openTime' => $val_arr['openTime'],

			'closeTime' => $val_arr['closeTime'],



			'coin' => $val_arr['coin'],

			'created_date' => $val_arr['created_date'],

			'periods' => $val_arr['periods'],

			'openTime_human_readible' => $val_arr['openTime_human_readible'],

			'closeTime_human_readible' => $val_arr['closeTime_human_readible'],

		);

		}





		// if(count($final_arr)>0){

		// 	$arr_length = count($final_arr);

		// 	echo  'from date'.$from_date = $final_arr[0]['openTime'];echo '<br>';

		// 	echo 'todate'.$end_date = $final_arr[$arr_length-1]['openTime'];

		// 	//exit;

		// }

		// echo '<pre>';

		// print_r($final_arr);

		// exit;

		return $final_arr;



		}/*** End of get_candelstick_data_run **/









		 public function get_candelstick_data_by_ajax()

 	{

	



		$global_symbol = $this->session->userdata('global_symbol');



		$this->mongo_db->where(array('coin'=> $global_symbol ,'periods'=>'1h'));

		

		$this->mongo_db->limit(48);

		$this->mongo_db->sort(array('created_date'=> 'desc'));

		$responseArr = $this->mongo_db->get('market_chart');



		$final_arr = array();

		foreach($responseArr as $val_arr){

		$final_arr[] = array(

			'timestampDate' =>$val_arr['timestampDate'],

			'close' => $val_arr['close'],

			'open' => $val_arr['open'],

			'high' => $val_arr['high'],

			'low' => $val_arr['low'],

			'volume' => $val_arr['volume'],

			'openTime' => $val_arr['openTime'],

			'closeTime' => $val_arr['closeTime']

		);

		}



		return $final_arr;



	}//end get_candelstick_data





    public function get_candelstick_data_minute()

 	{

	



		$global_symbol = $this->session->userdata('global_symbol');



		$this->mongo_db->where(array('coin'=> $global_symbol ,'periods'=>'1m'));

		

		$this->mongo_db->limit(48);

		$this->mongo_db->sort(array('created_date'=> 'desc'));

		$responseArr = $this->mongo_db->get('market_chart');



		$final_arr = array();

		foreach($responseArr as $val_arr){

		$final_arr[] = array(

			'timestampDate' =>$val_arr['timestampDate'],

			'close' => $val_arr['close'],

			'open' => $val_arr['open'],

			'high' => $val_arr['high'],

			'low' => $val_arr['low'],

			'volume' => $val_arr['volume'],

			'openTime' => $val_arr['openTime'],

			'closeTime' => $val_arr['closeTime']

		);

		}



		return $final_arr;



	}//end get_candelstick_data_minute



	 public function get_candelstick_data_day()

 	{

	



		$global_symbol = $this->session->userdata('global_symbol');



		$this->mongo_db->where(array('coin'=> $global_symbol ,'periods'=>'1d'));

		

		$this->mongo_db->limit(48);

		$this->mongo_db->sort(array('created_date'=> 'desc'));

		$responseArr = $this->mongo_db->get('market_chart');



		$final_arr = array();

		foreach($responseArr as $val_arr){

		$final_arr[] = array(

			'timestampDate' =>$val_arr['timestampDate'],

			'close' => $val_arr['close'],

			'open' => $val_arr['open'],

			'high' => $val_arr['high'],

			'low' => $val_arr['low'],

			'volume' => $val_arr['volume'],

			'openTime' => $val_arr['openTime'],

			'closeTime' => $val_arr['closeTime']

		);

		}



		return $final_arr;



	}//end get_candelstick_data_day













	







	 public function get_candelstick_data_pre($data)

 	{

		



		 $timestamp = $data['pre_time'];

		 $timestamp_obj =$this->mongo_db->timestamp($timestamp);

		$global_symbol = $this->session->userdata('global_symbol');



		$this->mongo_db->where(array('coin'=> $global_symbol ,'periods'=>'1h'));

		$this->mongo_db->where_lt('timestampDate', $timestamp_obj);



		$this->mongo_db->limit(48);

		$this->mongo_db->sort(array('created_date'=> 'desc'));

		$responseArr = $this->mongo_db->get('market_chart');



		$final_arr = array();

		foreach($responseArr as $val_arr){

		$final_arr[] = array(

			'timestampDate' =>$val_arr['timestampDate'],

			'close' => $val_arr['close'],

			'open' => $val_arr['open'],

			'high' => $val_arr['high'],

			'low' => $val_arr['low'],

			'volume' => $val_arr['volume'],

			'openTime' => $val_arr['openTime'],

			'closeTime' => $val_arr['closeTime']

		);

		}



		return $final_arr;



	}//end get_candelstick_data





      public function get_candelstick_data_next($data)

 	   {

		



		 $timestamp = $data['next_time'];

		 $timestamp_obj =$this->mongo_db->timestamp($timestamp);

		 $this->mongo_db->where_gt('timestampDate', $timestamp_obj);



		$global_symbol = $this->session->userdata('global_symbol');



		$this->mongo_db->where(array('coin'=> $global_symbol ,'periods'=>'1h'));

		$this->mongo_db->where_gt('timestampDate', $timestamp_obj);



		$this->mongo_db->limit(48);

		$this->mongo_db->sort(array('created_date'=> 'desc'));

		$responseArr = $this->mongo_db->get('market_chart');



		$final_arr = array();

		foreach($responseArr as $val_arr){

		$final_arr[] = array(

			'timestampDate' =>$val_arr['timestampDate'],

			'close' => $val_arr['close'],

			'open' => $val_arr['open'],

			'high' => $val_arr['high'],

			'low' => $val_arr['low'],

			'volume' => $val_arr['volume'],

			'openTime' => $val_arr['openTime'],

			'closeTime' => $val_arr['closeTime']

		);

		}



		return $final_arr;



	}//end get_candelstick_data_next

	











		public function  get_candelstick_data_from_database($global_symbol,$periods,$from_date_object,$to_date_object,$previous_date,$forward_date){

				$this->mongo_db->where(array('coin'=> $global_symbol ,'periods'=>$periods));
				if($from_date_object && $to_date_object){
					$this->mongo_db->where_gte('timestampDate', $from_date_object);
				    $this->mongo_db->where_lte('timestampDate', $to_date_object);
				}

				if($previous_date !=''){
					$previous_date = $previous_date/1000;
					$previous_date = date("Y-m-d H:i:s", $previous_date);
					$previous_date_date_mongo = $this->mongo_db->converToMongodttime($previous_date);
				    $this->mongo_db->where_lte('timestampDate',$previous_date_date_mongo);
				}

				if($forward_date !=''){
					$forward_date = $forward_date/1000;
					$forward_date = date("Y-m-d H:i:s", $forward_date);
					$forward_date_date_mongo = $this->mongo_db->converToMongodttime($forward_date);
					$this->mongo_db->where_gt('timestampDate',$forward_date_date_mongo);
					$this->mongo_db->sort(array('timestampDate'=> 'ASC'));//ASC/DESC
				} else{
					$this->mongo_db->sort(array('timestampDate'=> 'DESC'));//ASC/DESC
				}


	
				$this->mongo_db->limit(168);
				$responseArr = $this->mongo_db->get('market_chart');

				

				$final_arr = array();

				foreach($responseArr as $val_arr){

				$final_arr[] = array(

				'_id'=>	$myText = (string)$val_arr['_id'],

				'timestampDate' =>$val_arr['timestampDate'],

				'close' => $val_arr['close'],

				'open' => $val_arr['open'],

				'high' => $val_arr['high'],

				'low' => $val_arr['low'],

				'volume' => $val_arr['volume'],

				'openTime' => $val_arr['openTime'],

				'closeTime' => $val_arr['closeTime'],

				'coin' => $val_arr['coin'],
				'candel_status'=>$val_arr['candel_status'],
				'candle_type'=>$val_arr['candle_type'],

				'openTime_human_readible' => $val_arr['openTime_human_readible'],

			     'closeTime_human_readible' => $val_arr['closeTime_human_readible'],

				);

				}




				if($forward_date ==''){

					$final_arr = array_reverse($final_arr);
				}

				// echo '<pre>';

				// print_r($final_arr);

				// exit();



				return $final_arr;



		}/** End of **/

	





		public function get_market_history_for_candel($symbol){







	

		

            $pipeline = array(

		            '$group'=>array('_id'=>'$hour','volume'=>array('$sum'=>'$volume'),

		            'type'    => array('$first' => '$type'),

		            'coin'    => array('$first' => '$coin'),

		            'timestamp'    => array('$first' => '$timestamp'),

		            'price'    => array('$first' => '$price'),

		            'hour'    => array('$first' => '$hour'),

		            'hour_timestamp'    => array('$first' => '$hour_timestamp'),

	            ),

            );



            $project =  array(

	            '$project' => array(

		            "_id" => 1,

		            "price" => 1,

		            "volume"=>1,

		            "type"=>1,

		            "coin"=>1,

		            'timestamp'=>1,

		            'hour'=>1,

		            'hour_timestamp'=>1

	            )

            );



			$match = array(

					'$match' => array(

					'type'=>'ask',

					'coin'=>$symbol

				)

			);



			$sort = array('$sort'=>array('hour'=>-1));

			$limit = array('$limit'=>1000);





            $connect = $this->mongo_db->customQuery();



            $market_history_Arr_ask = $connect->market_trade_hourly_history_for_api_collection->aggregate(array($project,$match,$pipeline,$sort,$limit));



            $market_history_Arr_ask = iterator_to_array($market_history_Arr_ask);





          





            $ask_price_volume_arr = array();



            if(count($market_history_Arr_ask)>0){



            	foreach ($market_history_Arr_ask as $key => $value) {



            		$ask_price_volume_arr[$value['price']] = $value['volume'];



            		

            	}

            	



            }





          





            $match = array(

					'$match' => array(

					'type'=>'bid',

					'coin'=>$symbol

				)

			);

            $market_history_Arr_bit = $connect->market_trade_hourly_history_for_api_collection->aggregate(array($project,$match,$pipeline,$sort,$limit));



            $market_history_Arr_bit = iterator_to_array($market_history_Arr_bit);

          





            $bid_volume_arr = array();

            $total_volume   = array();

            if(count($market_history_Arr_bit)>0){



            	foreach ($market_history_Arr_bit as $key => $value) {



            		$total_volume[$value['hour']] = $ask_volume_arr[$value['hour']]+$value['volume'];



            		$bid_volume_arr[$value['hour']] = $value['volume'];

            	}

            	



            }



            $response_arr['bid_volume_arr'] =array_reverse($bid_volume_arr);

            $response_arr['ask_volume_arr'] =  array_reverse($ask_volume_arr);

            $response_arr['total_volume_arr']  =array_reverse($total_volume);



            $max_volumer = max($total_volume);









            return $response_arr;





            // foreach ($response_arr as $key ) {

            // 	foreach ($key as $key1 =>$val) {

            // 	echo 'ask volume'.$response_arr['ask_volume_arr'][$key1].'bid  '.$val.'---------'.$response_arr['total_volume'][$key1].'<br>';

            // 	}

            // }

		}







		public function get_candle_price_volume_detail($symbol,$start_date,$end_date,$unit_value){



			$bid_arr_volume = $this->get_bid_price_volume($symbol,$start_date,$end_date);

			$ask_arr_volume = $this->get_ask_price_volume($symbol,$start_date,$end_date);



			$total_volume_arr =array();

			$total_volume= 0;



			if(count($ask_arr_volume)>0){



				foreach ($ask_arr_volume as $price => $valume) {



					$total_volume_arr[$price] =$bid_arr_volume[$price] + $valume;

					

				}



			}





			foreach ($bid_arr_volume as $bid_price => $bid_volume) {



				if(!array_key_exists($bid_price, $total_volume_arr)){



					$total_volume_arr[$bid_price] =$bid_volume;

				}

			}





			$max_volume = max($total_volume_arr);



			$retur_data['bid_arr_volume'] = $bid_arr_volume;

			$retur_data['ask_arr_volume'] = $ask_arr_volume;

			$retur_data['total_volume_arr'] = $total_volume_arr;

			$retur_data['max_volume'] = $max_volume;

			$retur_data['unit_value'] = $unit_value;

			

			return $retur_data;

		}/** End of get_candle_price_volume_detail **/







		public function get_hour_volume_array_detail($symbol,$start_date,$end_date){



			$ask_arr_volume_hourly = $this->get_ask_price_volume_for_hour($symbol,$start_date,$end_date,'ask');

			$bid_arr_volume_hourly = $this->get_ask_price_volume_for_hour($symbol,$start_date,$end_date,'bid');









			$total_volume_arr =array();

			$total_volume= 0;



			if(count($ask_arr_volume_hourly)>0){



				foreach ($ask_arr_volume_hourly as $date => $valume) {



					$total_volume_arr[$date] =$bid_arr_volume_hourly[$date] + $valume;

					

				}



			}





			foreach ($bid_arr_volume_hourly as $bid_date => $bid_volume) {



				if(!array_key_exists($bid_date, $total_volume_arr)){



					$total_volume_arr[$bid_date] =$bid_volume;

				}

			}





			$max_volume = max($total_volume_arr);

			$retur_data['bid_hour_arr_volume'] = $bid_arr_volume_hourly;

			$retur_data['ask_hour_arr_volume'] = $ask_arr_volume_hourly;

			$retur_data['total_hour_volume_arr'] = $total_volume_arr;

			$retur_data['max_volume_hourly'] = $max_volume;

			return $retur_data;

		}/*** End of get_hour_volume_array_detail ***/





		function get_ask_price_volume_for_hour($symbol,$start_date,$end_date,$type){





			$connect = $this->mongo_db->customQuery();

            $pipeline = array(

		            '$group'=>array('_id'=>'$hour','volume'=>array('$sum'=>'$volume'),

		            'type'    => array('$first' => '$type'),

		            'coin'    => array('$first' => '$coin'),

		            'timestamp'    => array('$first' => '$timestamp'),

		            'price'    => array('$first' => '$price'),

		            'hour'    => array('$first' => '$hour'),

		            'hour_timestamp'    => array('$first' => '$hour_timestamp'),

	            ),

            );



            $project =  array(

	            '$project' => array(

		            "_id" => 1,

		            "price" => 1,

		            "volume"=>1,

		            "type"=>1,

		            "coin"=>1,

		            'timestamp'=>1,

		            'hour'=>1,

		            'hour_timestamp'=>1

	            )

            );



			$match = array(

					'$match' => array(

					'type'=>$type,

					'coin'=>$symbol,

					'hour'=>array('$gte'=>$start_date,'$lte'=>$end_date)



				)

			);











			$sort = array('$sort'=>array('hour'=>1));

			$limit = array('$limit'=>1000);





            $connect = $this->mongo_db->customQuery();



            $market_history_Arr_ask = $connect->market_trade_hourly_history_for_api_collection->aggregate(array($project,$match,$pipeline,$sort,$limit));



            $market_history_Arr_ask = iterator_to_array($market_history_Arr_ask);







            $ask_volume_arr = array();



            if(count($market_history_Arr_ask)>0){

            	foreach ($market_history_Arr_ask as $key => $value) {

            		$ask_volume_arr[$value['hour']] = $value['volume'];

            	}

            }



	   

            return $ask_volume_arr;



		}/** End of get_ask_price_volume_for_hour **/







		public function get_ask_price_volume($symbol,$start_date,$end_date){



			 	$connect = $this->mongo_db->customQuery();

			  



				$this->mongo_db->where('type','ask');

				$this->mongo_db->where('coin',$symbol);



				$this->mongo_db->where_gte('hour', $start_date);

				$this->mongo_db->where_lte('hour', $end_date);

				$this->mongo_db->sort(array('hour'=> 'desc'));



				$responseArr = $this->mongo_db->get('market_trade_hourly_history_for_api_collection');

				$responseArr  = iterator_to_array($responseArr);



				 $ask_price_volume_arr = array();

				 $full_arr =array();



            if(count($responseArr)>0){

            	foreach ($responseArr as $value) {

            		$ask_price_volume_arr[number_format($value['price'], 8)] = $value['volume'];

            	}

            }

             ksort($ask_price_volume_arr);

             return $ask_price_volume_arr;

		}/** End of get_bid_price_volume**/



		public function get_bid_price_volume($symbol,$start_date,$end_date){



			$connect = $this->mongo_db->customQuery();

			$this->mongo_db->where_gte('hour', $start_date);

			$this->mongo_db->where_lte('hour', $end_date);

			$this->mongo_db->where('type','bid');

			$this->mongo_db->where('coin',$symbol);

			$this->mongo_db->sort(array('hour'=> 'desc'));



			$responseArr = $this->mongo_db->get('market_trade_hourly_history_for_api_collection');

			$responseArr  = iterator_to_array($responseArr);





			$bid_price_volume_arr = array();

			



            if(count($responseArr)>0){



            	foreach ($responseArr as $value) {

 			

            		$bid_price_volume_arr[number_format($value['price'], 8)] = $value['volume'];	

            		

            	}

            }



            ksort($bid_price_volume_arr);

            return $bid_price_volume_arr;

          

		}/** End of get_bid_price_volume**/



		public function  get_date_range_for_history($symbol){









			$connect = $this->mongo_db->customQuery();

            $pipeline = array(

		            '$group'=>array('_id'=>'$hour','volume'=>array('$sum'=>'$volume'),

		            'type'    => array('$first' => '$type'),

		            'coin'    => array('$first' => '$coin'),

		            'timestamp'    => array('$first' => '$timestamp'),

		            'price'    => array('$first' => '$price'),

		            'hour'    => array('$first' => '$hour'),

		            'hour_timestamp'    => array('$first' => '$hour_timestamp'),

	            ),

            );



            $project =  array(

	            '$project' => array(

		            "_id" => 1,

		            "price" => 1,

		            "volume"=>1,

		            "type"=>1,

		            "coin"=>1,

		            'timestamp'=>1,

		            'hour'=>1,

		            'hour_timestamp'=>1

	            )

            );



			$match = array(

					'$match' => array(

					'type'=>'ask',

					'coin'=>$symbol

				)

			);



			$sort = array('$sort'=>array('hour'=>-1));

			$limit = array('$limit'=>8);





            $connect = $this->mongo_db->customQuery();



            $market_history_Arr_ask = $connect->market_trade_hourly_history_for_api_collection->aggregate(array($project,$match,$pipeline,$sort,$limit));



            $market_history_Arr_ask = iterator_to_array($market_history_Arr_ask);





          



            $var_total_hour_array= array();



            $ask_volume_arr = array();



            if(count($market_history_Arr_ask)>0){



            	foreach ($market_history_Arr_ask as $key => $value) {



            		$ask_volume_arr[$value['hour']] = $value['volume'];



            		array_push($var_total_hour_array, $value['hour']);

            	}

            	



            }



       

            return $var_total_hour_array;



		}/** End of get_date_range_for_history**/









	







		public function delete_data_from_data_base(){





	

				 $removeTime = date('Y-m-d G:i:s', strtotime('-1 hour', strtotime(date("Y-m-d G:i:s"))));







				$orig_date = new DateTime($removeTime);

				$orig_date=$orig_date->getTimestamp(); 

				$created_date = new MongoDB\BSON\UTCDateTime($orig_date*1000); 





				$db= $this->mongo_db->customQuery();



				

			



				///////////////////////////////////////////////////////////////

				$delectmarket_prices = $db->market_prices->deleteMany(array('created_date'=>array('$lte'=>$created_date)));



				/////////////////////////////////////////////////////////////////////////////



				///////////////////////////////////////////////////////////////



				//$delectmarket_depth = $db->market_depth->deleteMany(array('created_date'=>array('$lte'=>$created_date)));





				///////////////////////////////////////////////////////////////////



				///////////////////////////////////////////////////////////////



				$delectmarket_trades = $db->market_trades->deleteMany(array('created_date'=>array('$lte'=>$created_date)));





				

		}




	//get_order_array
	public function get_order_array($symbol)
	{
		//$symbol = $this->input->post('symbol');

		$search_Array = array(
			'symbol' => $symbol,
			'status' => 'FILLED',
			'is_sell_order' => 'sold',
			'admin_id' => '1'
		);
		$this->mongo_db->where($search_Array);
		$res = $this->mongo_db->get('buy_orders');
		$final_arr = array();
		$buy_orders_arr = iterator_to_array($res);
		
		foreach ($buy_orders_arr as $key => $arr) {
			$buy_order_id = $arr['_id'];
			$datetime = $arr['created_date']->toDateTime();
	        $created_date = $datetime->format(DATE_RSS);

	        $datetime = new DateTime($created_date);
	        $datetime->format('Y-m-d H:00:00');

	        $formated_date_time =  $datetime->format('Y-m-d H:00:00');
			$buy_order_date = $formated_date_time;

			$search['buy_order_id'] = $buy_order_id;
			$this->mongo_db->where($search);
			$res_sold = $this->mongo_db->get('orders');
			$sold_order_arr = iterator_to_array($res_sold);

			foreach ($sold_order_arr as $key => $value) {
				$buy_order_price = $value['purchased_price'];
				$sell_order_price = $value['sell_price'];
				$datetime = $value['created_date']->toDateTime();
		        $created_date = $datetime->format(DATE_RSS);

		        $datetime = new DateTime($created_date);
		        $datetime->format('Y-m-d H:00:00');

		        $formated_date_time =  $datetime->format('Y-m-d H:00:00');
				$sell_date = $formated_date_time;

				$final_arr[] = array(
					'buy_date' => $buy_order_date,
					'sell_date' => $sell_date,
					'buy_price' => $buy_order_price,
					'sell_price' => $sell_order_price
				);	 
			}
		}
		return $final_arr;
	}
	//end get_order_array











	

}

?>