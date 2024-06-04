<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/* Hassan Code */
if(!function_exists('get_client_ip')) {
     function get_client_ip()
     {
          $ipaddress = '';
          if (getenv('HTTP_CLIENT_IP'))
              $ipaddress = getenv('HTTP_CLIENT_IP');
          else if(getenv('HTTP_X_FORWARDED_FOR'))
              $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
          else if(getenv('HTTP_X_FORWARDED'))
              $ipaddress = getenv('HTTP_X_FORWARDED');
          else if(getenv('HTTP_FORWARDED_FOR'))
              $ipaddress = getenv('HTTP_FORWARDED_FOR');
          else if(getenv('HTTP_FORWARDED'))
              $ipaddress = getenv('HTTP_FORWARDED');
          else if(getenv('REMOTE_ADDR'))
              $ipaddress = getenv('REMOTE_ADDR');
          else
              $ipaddress = 'UNKNOWN';
    
          return $ipaddress;
     }

}
if (!function_exists('get_coins')) {

	function get_coins() {

		$CI = &get_instance();
		if ($CI->session->userdata('user_role') == '1') {
			$CI->load->model('admin/mod_coins');
			$coins_arr = $CI->mod_coins->get_all_coins();
		} else {
			$CI->load->model('admin/mod_market');
			$coins_arr = $CI->mod_market->get_coins();

		}
		return $coins_arr;
	}

} //end
if (!function_exists('calculate_percentage')) {

	function calculate_percentage($purchased_price, $sell_price) {

		$current_data2222 = $sell_price - $purchased_price;
		$profit_data = ($current_data2222 * 100 / $purchased_price);
		$profit_data = number_format((float) $profit_data, 2, '.', '');

		return $profit_data;
	}

} //end calculate_percentage

if (!function_exists('get_global_password')) {

	function get_global_password() {

		$CI = &get_instance();
		$filter_arr['subtype'] = "superadmin_password";
		$CI->mongo_db->where($filter_arr);
		$get = $CI->mongo_db->get("superadmin_settings");

		$res = iterator_to_array($get);
		return $res[0]['updated_system_password'];
	}

} //end get_global_password


if (!function_exists('cmp')) {
	function cmp($a, $b) {
		return strcmp($a["numeric_level"], $b["numeric_level"]);
	}

} //end get_coins

//get_stop_loss //Umer Abbas [19-11-19]
if (!function_exists('get_stop_loss')) {
	function get_stop_loss($order_id, $trigger_type) {
		
		$CI = &get_instance();
		$order_id = $CI->mongo_db->mongoID($order_id);

		if($trigger_type == 'no'){

			$CI->mongo_db->where(array("_id" => $order_id));
			$get_arr = $CI->mongo_db->get('orders');
			$order_arr = iterator_to_array($get_arr);
			if(!empty($order_arr)){
				$order_arr = $order_arr[0];
				return (!empty($order_arr['stop_loss']) ? $order_arr['stop_loss'] : '--');
			}
			return '--';
		}else{
			$CI->mongo_db->where(array("_id" => $order_id));
			$get_arr = $CI->mongo_db->get('buy_orders');
			$order_arr = iterator_to_array($get_arr);
			if(!empty($order_arr)){
				$order_arr = $order_arr[0];
				return (!empty($order_arr['initial_trail_stop']) ? $order_arr['initial_trail_stop'] : '--');
			}
			return '--';
		}
	}
} //end get_stop_loss

if (!function_exists('getUserDetailsById')) {
	function getUserDetailsById($admin_id) {
		
		$CI = &get_instance();
		$admin_id = $CI->mongo_db->mongoID($admin_id);

		$CI->mongo_db->where(array("_id" => $admin_id));
		$get_arr = $CI->mongo_db->get('users');
		$userData = iterator_to_array($get_arr);
		if(count($userData) > 0 ){
			$data = $userData;
			if ($data) {
				return $data;
			}
			else{
				return '----';
			};
		}
	
	}
} //end get_stop_loss

if (!function_exists('checkCoinLedger')) {
    function checkCoinLedger($exchange, $admin_id_str, $symbol)
    {
        if ($exchange == 'binance') {
            $collectionOrders = 'buy_orders';
        } else {
            $collectionOrders = 'buy_orders_kraken';
        }

        $query = [
            'application_mode' => 'live',
            'status' => ['$ne' => 'canceled'],
            'cavg_parent' => ['$exists' => true],
            'admin_id' => $admin_id_str,
            'trigger_type' => 'barrier_percentile_trigger',
            'parent_status' => ['$ne' => 'parent'],
			'symbol' => $symbol
        ];

        // Execute the MongoDB query
		$CI = &get_instance();
        $db = $CI->mongo_db->customQuery();
        $balanceData = $db->$collectionOrders->find($query);
        $balanceArr = iterator_to_array($balanceData);

        // $balanceData = $CI->mongo_db->customQuery()->$collectionOrders->find($query);
		// $balanceArr = iterator_to_array($balanceData);

		if (count($balanceArr) > 0) {
			return 'Yes';
		}else{
			return 'No';
		}
    }
} //end get_stop_loss


//get_usd_worth //Umer Abbas [20-08-20]
if (!function_exists('get_usd_worth')) {
	function get_usd_worth($symbol, $quantity, $current_market_price, $BTCUSDT_price) {
		$tarr = explode('USDT', $symbol);
		if (isset($tarr[1]) && $tarr[1] == '') {
			$usd_worth = $quantity * $current_market_price;
		} else {
			$usd_worth = $quantity * $current_market_price * $BTCUSDT_price;

			// if ($symbol == "BTC") {
			// 	$usd_worth = $quantity * $BTCUSDT_price;
			// } else {
			// 	$usd_worth = $quantity * $current_market_price * $BTCUSDT_price;
			// }
		}
		return number_format($usd_worth, 2);
;
	}
} //end get_usd_worth

if (!function_exists('get_user_timezone')) {

	function get_user_timezone($user_id) {

		$CI = &get_instance();

		$CI->load->model('admin/mod_users');
		$timezone = $CI->mod_users->get_user_timezone($user_id);
		return $timezone;
	}

} //end get_coins

if (!function_exists('encrypt_decrypt')) {

	function encrypt_decrypt($action, $string) {
		$output = false;

		$encrypt_method = "AES-256-CBC";
		$secret_key = 'This is my secret key';
		$secret_iv = 'This is my secret iv';

		// hash
		$key = hash('sha256', $secret_key);

		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);

		if ($action == 'encrypt') {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		} else if ($action == 'decrypt') {
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}

		return $output;
	}

} //end get_coins

// if (!function_exists('time_elapsed_string')) {

// 	function time_elapsed_string($datetime, $timezone, $full = false) {
// 		$CI = &get_instance();
// 		$datetime2 = date("Y-m-d g:i:s A");
// 		$timezone = $timezone;
// 		$date = date_create($datetime2);
// 		date_timezone_set($date, timezone_open($timezone));
// 		$now1 = date_format($date, 'Y-m-d g:i:s A');
// 		$now = new DateTime($now1);
// 		$ago = new DateTime($datetime);
// 		$diff = $now->diff($ago);

// 		$diff->w = floor($diff->d / 7);
// 		$diff->d -= $diff->w * 7;

// 		$string = array(
// 			'y' => 'year',
// 			'm' => 'month',
// 			'w' => 'week',
// 			'd' => 'day',
// 			'h' => 'hour',
// 			'i' => 'minute',
// 			's' => 'second',
// 		);
// 		foreach ($string as $k => &$v) {
// 			if ($diff->$k) {
// 				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
// 			} else {
// 				unset($string[$k]);
// 			}
// 		}

// 		if (!$full) {
// 			$string = array_slice($string, 0, 1);
// 		}

// 		return $string ? implode(', ', $string) . ' ago' : 'just now';
// 	}

// } //end get_coins
if (!function_exists('time_elapsed_string')) {
	function time_elapsed_string($datetime, $timezone, $full = false) {
		$CI = &get_instance();
		$datetime2 = date("Y-m-d g:i:s A");
		$timezone = $timezone;
		$date = date_create($datetime2);
		date_timezone_set($date, timezone_open($timezone));
		$now1 = date_format($date, 'Y-m-d g:i:s A');
		$now = new DateTime($now1);
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

		if (!$full) {
			$string = array_slice($string, 0, 1);
		}

		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

} //end get_coins
if (!function_exists('walk')) {
	function walk($val, $key, $new_array) {

		$nums = explode(':', $val);
		$new_array[$nums[0]] = $nums[1];

		return $new_array;
	}
} // %%%%%%%%%% -- %%%%%%%%%%%%%

if (!function_exists('check_active_cron')) {
	function check_active_cron($url) {
		$CI = &get_instance();
		$CI->load->model("admin/mod_cronjob_listing");
		$test = $CI->mod_cronjob_listing->check_when_last_cron_ran($url);
		return $test;
	}
} // %%%%%%%%%% -- %%%%%%%%%%%%%

if (!function_exists('is_user_api_credential_exist')) {
	function is_user_api_credential_exist($user_id) {
		$CI = &get_instance();
		$CI->mongo_db->where(array("_id" => $user_id));
		$get_arr = $CI->mongo_db->get('users');
		$settings_arr = iterator_to_array($get_arr);
		$settings_arr = $settings_arr[0];
		$check_api_settings = false;
		if ($settings_arr['api_key'] != '' && $settings_arr['api_key'] != '') {
			$check_api_settings = true;
		}
		return $check_api_settings;
	} //End of is_user_api_credential_exist
} // %%%%%%%%%% -- %%%%%%%%%%%%%

if (!function_exists('function_start')) {
	function function_start($function_name) {
		$CI = &get_instance();
		$start_date_human_readible = date('Y-m-d H:i:s');
		$call_back_url = current_url();
		$start_date = $CI->mongo_db->converToMongodttime($start_date_human_readible);
		$insert_arr = array('function_name' => $function_name, 'start_date' => $start_date, 'start_date_human_readible' => $start_date_human_readible, 'start_status' => 'yes', 'cron_url' => $call_back_url);
		$CI->mongo_db->insert('function_process_completion_time', $insert_arr);
	}
} // %%%%%%%%%% -- %%%%%%%%%%%%%

if (!function_exists('function_stop')) {
	function function_stop($function_name) {
		$CI = &get_instance();
		$stop_date_human_readible = date('Y-m-d H:i:s');
		$stop_date = $CI->mongo_db->converToMongodttime($stop_date_human_readible);

		$upd_arr = array('function_name' => $function_name, 'stop_date' => $stop_date, 'stop_date_human_readible' => $stop_date_human_readible, 'stop_status' => 'yes');

		$where = array('function_name' => $function_name);
		$conn = $CI->mongo_db->customQuery();

		$conn->function_process_completion_time->updateMany($where, array('$set' => $upd_arr));
	}
} // %%% -- %%%%%

if (!function_exists('is_function_process_complete')) {
	function is_function_process_complete($function_name) {
		$CI = &get_instance();
		$CI->mongo_db->where(array('function_name' => $function_name));
		$CI->mongo_db->limit(1);
		$CI->mongo_db->order_by(array('start_date' => -1));
		$row = $CI->mongo_db->get('function_process_completion_time');
		$data = iterator_to_array($row);

		$response = false;
		if (!empty($data)) {
			$data = (array) $data[0];
			$stop_status = $data['stop_status'];
			if ($stop_status == 'yes') {
				$response = true;
			}

		}
		return $response;
	}
} // %%% -- %%%%%

if (!function_exists('is_script_take_more_time')) {
	function is_script_take_more_time($function_name, $wait_seconds = '') {
		$CI = &get_instance();

		$date = date('Y-m-d H:i:s', strtotime('-10 seconds'));
		if ($wait_seconds != '') {
			$date = date('Y-m-d H:i:s', strtotime('-' . $wait_seconds . ' seconds'));
		}
		$created_date = $CI->mongo_db->converToMongodttime($date);

		$where = array('stop_status' => null, 'function_name' => $function_name, 'start_date' => array('$lte' => $created_date));

		$conn = $CI->mongo_db->customQuery();
		$response = $conn->function_process_completion_time->find($where);
		$response = iterator_to_array($response);

		$reponse = false;
		if (!empty($response)) {
			$reponse = true;
		}

		return $reponse;

	} //End of
} //End of is_script_take_more_time

if (!function_exists('track_execution_of_function_time')) {

	function track_execution_of_function_time($function_name) {
		$CI = &get_instance();
		$call_back_url = current_url();
		$start_date_human_readible = date('Y-m-d H:i:s');
		$start_date = $CI->mongo_db->converToMongodttime($start_date_human_readible);
		$insert_arr = array('function_name' => $function_name, 'created_date' => $start_date, 'created_date_human_readible' => $start_date_human_readible, 'cron_url' => $call_back_url);
		$CI->mongo_db->insert('track_execution_of_function_time', $insert_arr);
	} // End of

} // --- End of track_execution_of_function_time ---

if (!function_exists('track_execution_of_cronjob')) {

	function track_execution_of_cronjob($duration, $summary) {
		$CI = &get_instance();
		$call_back_url = current_url();
		$start_date_human_readible = date('Y-m-d H:i:s');
		$start_date = $CI->mongo_db->converToMongodttime($start_date_human_readible);
		$insert_arr = array('last_updated_time' => $start_date, 'last_updated_time_human_readible' => $start_date_human_readible, 'cron_url' => $call_back_url, 'cron_duration' => $duration, 'cron_summary' => $summary);
		$where = array('cron_url' => $call_back_url);
		$conn = $CI->mongo_db->customQuery();
		$conn->cronjob_listing_update->updateOne($where, array('$set' => $insert_arr), array('upsert' => true));
	} // End of

} // --- End of track_execution_of_cronjob ---

if (!function_exists('convert_digits')) {

	function convert_digits($data) {

		$CI = &get_instance();

		$lenth = strlen(substr(strrchr($data, "."), 1));
		if ($lenth == 6) {
			$new_data = $data . '0';
		} else {

			$new_data = $data;
		}

		return $new_data;

	}

} //end convert_digits

if (!function_exists('num')) {

	function num($data) {
		$data = (float) $data;
		return number_format($data, 8, '.', '');
	}

} //end num

if (!function_exists('hitCurlRequest')) {
	function hitCurlRequest($req) {
        $req_params = $req['req_params'];
        $req_endpoint = $req['req_endpoint'];
        $req_type = $req['req_type'];

		$header   = $req['header'];
		$authorization = "Authorization: ".$header;
		$req_url = (!empty($req['req_url']) ? $req['req_url'] : '');
		if(!empty($req_url)){
			$url = $req_url;
		}else{
			//https://digiapis.digiebot.com/apiEndPoint
			$url = "https://digiapis.digiebot.com/apiEndPoint/".$req_endpoint;
			//$url = 'http://34.205.124.51:3010/apiEndPoint/'.$req_endpoint;
		}
		
		if(!empty($req_url)){
			$post_json = json_encode($req_params);
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => $req_type,
				CURLOPT_POSTFIELDS => $post_json,
				CURLOPT_HTTPHEADER => array(
					"Content-type: application/json",
					$authorization
				),
			));
		}else{			
			$post_json = json_encode($req_params);
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => $req_type,
				CURLOPT_POSTFIELDS => $post_json,
				CURLOPT_HTTPHEADER => array(
					"Content-type: application/json",
					$authorization
				),
			));
		}
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

		$response = json_decode($response, TRUE);
        $resp = array(
            'http_code' => $http_code,
            'response' => $response,
            'error' => $err,
        );


        return $resp;
	}
} //end num


if (!function_exists('dynamicCURLHit')) {
	function dynamicCURLHit($req) {
		// echo '<br>comming';

        $jsondataPayLoad   =  $req['req_params'];
        $req_url           =  $req['req_url'];
        // $req_type          =  $req['req_type'];
		// echo "<payload>: ".$jsondataPayLoad;


		// echo '<br>'.$req_url;
		$jsondataPayLoad = json_encode($jsondataPayLoad);

		$curl = curl_init();
		curl_setopt_array($curl, [
		CURLOPT_URL =>  $req_url,//"https://ip4.digiebot.com/apiKeySecret/validateApiKeySecretAdmin",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $jsondataPayLoad,
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/json"
		],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
        return $response;
	}
} //end num



if (!function_exists('array2csv')) {
	function array2csv($array){
		if (count($array) == 0) {
			return null;
		}
		ob_start();
		$df = fopen("php://output", 'w');
		fputcsv($df, array_keys((array) reset($array)));
		$arrLength = count($array);
        for ($i = 0; $i < $arrLength; $i++) {
			$row = &$array[$i];
			fputcsv($df, (array) $row);
			unset($row);
		}

		fclose($df);
		return ob_get_clean();
	} //array2csv
}


if (!function_exists('number_format_short')) {

	function number_format_short($n) {
		if ($n > 0 && $n < 1000) {
			// 1 - 999
			$n_format = number_format($n, 2, '.', '');
			$suffix = '';
		} else if ($n >= 1000 && $n < 1000000) {
			// 1k-999k
			$n_format = number_format($n / 1000, 2, '.', '');
			$suffix = 'K+';
		} else if ($n >= 1000000 && $n < 1000000000) {
			// 1m-999m
			$n_format = number_format($n / 1000000, 2, '.', '');
			$suffix = 'M+';
		} else if ($n >= 1000000000 && $n < 1000000000000) {
			// 1b-999b
			$n_format = number_format($n / 1000000000, 2, '.', '');
			$suffix = 'B+';
		} else if ($n >= 1000000000000) {
			// 1t+
			$n_format = number_format($n / 1000000000000, 2, '.', '');
			$suffix = 'T+';
		} else if ($n == 0) {
			$n_format = number_format($n, 2, '.', '');
			$suffix = '';
		}

		return !empty($n_format . $suffix) ? $n_format . $suffix : 0;
	}
}


if (!function_exists('send_mail')){
	function send_mail($user_id='', $subject='', $body='') {
		
		if(empty($user_id) || empty($subject) || empty($body)){
			return false;
		}else{

			// $CI = &get_instance();
	
			$params = [
				"user_id" => (string) $user_id,
				"subject" => $subject,
				"body" => $body,
			];
			$json = json_encode($params);

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://users.digiebot.com/cronjob/sendEmailAlertBlock",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $json,
				CURLOPT_HTTPHEADER => array(
				"authorization: Basic c2VuZEVtYWlsQWxlcnQ6NGU0NmQ5OWFjMjJhNGIwYWJlNTc2OGE3OGVlODdiOGM=",
				"cache-control: no-cache",
				"content-type: application/json",
				"postman-token: 1d56a24c-65fe-1556-bad1-cab868aaf9d0"
				),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			// $db = $CI->mongo_db->customQuery();
			// $db->tempDebugCol->insertOne(['log'=>''.$json.'']);

			return true;
		}

	}
}

/*?>  <option value="100" <?php
echo ($page_post_data['ask_buy_for'] == '100') ? 'selected="selected"' : '';
?>> Show 100</option>
<option value="1000" <?php
echo ($page_post_data['ask_buy_for'] == '1000') ? 'selected="selected"' : '';
?>> Show K</option>
<option value="10000" <?php
echo ($page_post_data['ask_buy_for'] == '10000') ? 'selected="selected"' : '';
?>> Show 10K</option>
<option value="100000" <?php
echo ($page_post_data['ask_buy_for'] == '100000') ? 'selected="selected"' : '';
?> > Show 100K</option>
<option value="1000000" <?php
echo ($page_post_data['ask_buy_for'] == '1000000') ? 'selected="selected"' : '';
?> > Show M</option>
<option value="10000000" <?php
echo ($page_post_data['ask_buy_for'] == '10000000') ? 'selected="selected"' : '';
?> > Show 10M</option><?php */

if (!function_exists('calculateProfitPercentage')) {
    function calculateProfitPercentage($purchased_price, $sold_price){

		if(!empty($purchased_price) && !empty($sold_price)){
			$diff = num($sold_price) - num($purchased_price);
			$profitPercentage = ( $diff/ num($purchased_price)) * 100;
			return (!is_nan($profitPercentage) ? number_format((float) $profitPercentage, 2, '.', '') : '-');
		}
		return '-';
    }
}


if (!function_exists('number_format_symbol')) {

	function number_format_symbol($n) {
		if ($n > 0 && $n < 1000) {
			// 1 - 999
			$n_format = number_format($n, 2, '.', '');
			$suffix = '';
		} else if ($n >= 1000 && $n < 10000) {
			// 1k-999k
			$n_format = number_format($n / 1000, 2, '.', '');
			$suffix = 'K+';
		} else if ($n >= 10000 && $n < 100000) {
			// 1m-999m
			$n_format = number_format($n / 1000000, 2, '.', '');
			$suffix = '10 K+';
		} else if ($n >= 100000 && $n < 1000000) {
			// 1b-999b
			$n_format = number_format($n / 1000000000, 2, '.', '');
			$suffix = '100 K+';
		} else if ($n >= 1000000 && $n < 10000000) {
			// 1t+
			$n_format = number_format($n / 1000000000000, 2, '.', '');
			$suffix = 'M+';
		} else if ($n >= 10000000 && $n < 100000000) {
			$n_format = number_format($n, 2, '.', '');
			$suffix = '10 M+';
		}

		return !empty($suffix) ? $suffix : 0;
	}
}

if (!function_exists('get_min_notation')) {

	function get_min_notation($symbol) {

		$CI = &get_instance();
		$CI->load->model('admin/mod_dashboard');

		$min_notation = $CI->mod_dashboard->get_coin_min_notation($symbol);
		return $min_notation;
	}

} //end get_coins

if (!function_exists('show_error_404()')) {
	function show_error_404() {
		redirect(base_url('not_found'));
	}
}

if (!function_exists('getAvgPrice')) {
	function getAvgPrice($symbol, $rule_number, $trigger_type) {

		$CI = &get_instance();
		$project = array(
			'$project' => array(
				"buy_order_id" => 1,

				"coin_symbol" => 1,
				"order_type" => 1,
				"rule" => 1,
			),
		);
		$match = array(

			'$match' => array(
				'order_type' => 'sell',
				'coin_symbol' => $symbol,
				"rule" => $rule_number,
			),

		);

		$sort = array('$sort' => array('hour' => -1));
		$limit = array('$limit' => 10000);
		$connect = $CI->mongo_db->customQuery();
		$record_of_rules_for_orders = $connect->record_of_rules_for_orders->aggregate(array($project, $match, $sort, $limit));
		$rulesSet_arr = iterator_to_array($record_of_rules_for_orders);
		$buy_order_IDS = array_column($rulesSet_arr, 'buy_order_id');

		if ($_SERVER['REMOTE_ADDR'] == '101.50.127.163') { //echo "<prE>";  print_r(  $buy_order_IDS); exit;
		}

		if (!empty($buy_order_IDS)) {

			$pipeline = array(
				'$group' => array(
					'_id' => null,
					'totalmarketsum' => array(
						'$sum' => '$market_value',
					),
					'totalsold_price' => array(
						'$sum' => '$market_sold_price',
					),
				),
			);
			$project = array(
				'$project' => array(
					"_id" => 1,
					"market_value" => 1,
					"market_sold_price" => 1,
				),
			);
			$match = array('_id', $buy_order_IDS);
			$sort = array(
				'$sort' => array(),
			);
			$limit = array(
				'$limit' => 1000,
			);
			$connect = $CI->mongo_db->customQuery();
			$record_of_rules_for_orders_rule = $connect->buy_orders->aggregate(array(
				$project,
				$pipeline,
				$limit,
			));
			$dataSum = iterator_to_array($record_of_rules_for_orders_rule);
			$sumArr = $dataSum[0];

			$market_sold_priceAll = num($sumArr['totalmarketsum']);
			$purachased_priceAll = num($sumArr['totalsold_price']);

			$soldPurchase = (num($market_sold_priceAll) - num($purachased_priceAll));
			$avgProfit = ($soldPurchase / $purachased_priceAll) * 100;
			return $avgProfit;
		} else {
			return 0;
		}
	}

}

if (!function_exists('get_total_buy_trading_points_db')) {
    function get_total_buy_trading_points_db($user_id, $exchange=''){

        $CI = &get_instance();
        
		$user_id = $CI->mongo_db->mongoId((string) $user_id);
        $db = $CI->mongo_db->customQuery();
        $user = $db->users->find(['_id'=>$user_id]);
        $user = iterator_to_array($user);
        if(!empty($user)){
            return isset($user[0]['trading_points_buy']) && $user[0]['trading_points_buy'] != '' ? $user[0]['trading_points_buy'] : 0;
        }
        return 0;
    }
} //end function
if (!function_exists('getOrderMarketBTCPrice')) {
    function getOrderMarketBTCPrice($buy_order_id, $exchange){
		$collection = ($exchange == 'binance') ? 'buy_orders' : 'buy_orders_' . $exchange;
        $CI = &get_instance();
		$order_id = $CI->mongo_db->mongoId($buy_order_id);
        $db = $CI->mongo_db->customQuery();
        $order = $db->$collection->find(['_id'=>$order_id]);
        $orderRes = iterator_to_array($order);
		if ($orderRes > 0) {
			return isset($orderRes[0]['buy_time_btc_price']);
		}else{
			return 0;
		}
		
		
    }
} //end function
if (!function_exists('getTwentyFourHourPrice')) {
    function getTwentyFourHourPrice($symbol){
		
		$CI = &get_instance();
		$db = $CI->mongo_db->customQuery();
		$usdtWorth = 0;
		$collectionName = '24hr_ticker_price_change_statistics';
		$results = $db->$collectionName->find(['symbol' => $symbol]);
		$res = iterator_to_array($results);
		$highPrice = null;
		// if ($symbol != 'BTC') {
		// 	echo "<pre>"; print_r($res[0]['data']['highPrice']); exit;
		// }
		$highPrice = $res[0]['data']['highPrice'];
        return $highPrice;	
        	
    }
} //end function

if(!function_exists('get_all_blocked_coins_country')){
	function get_all_blocked_coins_country($exchange=''){

		$CI = &get_instance();
		$db = $CI->mongo_db->customQuery();
		$usdtWorth = 0;
		$collectionName = (isset($exchange) && $exchange !== '') ? 'coins_kraken' : 'coins';
		$filters = array('blocked_country_array'=>array('$exists'=>true),'user_id'=>'global');
		$results = $db->$collectionName->find($filters);
		$res = iterator_to_array($results);
		if($res)
		{
			return $res;

		}else{

			return false;
		}
	}
}

if (!function_exists('get_current_market_prices')) {
    function get_current_market_prices($exchange, $coins=[]){

        $CI = &get_instance();
        $db = $CI->mongo_db->customQuery();

		if (empty($coins)) {
			$coins_collection = ($exchange == 'binance') ? 'coins' : 'coins_' . $exchange;
			$where = [
				'user_id' => 'global',
			];
			if ($exchange == 'binance') {
				$where['exchange_type'] = 'binance';
			}

			$coins_data = $db->$coins_collection->find($where);
			$coins = iterator_to_array($coins_data);
			if (!empty($coins)) {
				$coins = array_column($coins, 'symbol');
				$coins = array_unique($coins);
				$coins = array_values($coins);
				unset($coins_data);
			}
		}

		$prices_collection = ($exchange == 'binance') ? 'market_prices' : 'market_prices_' . $exchange;

		$pipeline = [
			[
				'$match' => [
					'coin' => ['$in' => $coins],
				],
			],
			[
				'$sort' => [
					'created_date' => -1,
				],
			],
			[
				'$group' => [
					'_id' => '$coin',
					'price' => ['$first' => '$price'],
				],
			],
		];

		$prices_data = $db->$prices_collection->aggregate($pipeline);
		$prices_arr = iterator_to_array($prices_data);

		if ($prices_arr) {
			$prices_arr = array_column($prices_arr, 'price', '_id');
			unset($prices_data);
			return $prices_arr;
		}
		return [];

    }
} //end function

if (!function_exists('getMarketValueOfCoin')) {
    function getMarketValueOfCoin($user_id, $symbol, $exchange){
		
        $CI = &get_instance();
        $db = $CI->mongo_db->customQuery();

		if ($user_id != '') {

			$orders_collection = ($exchange == 'binance') ? 'buy_orders' : 'buy_orders_' . $exchange;
			$where = [
				'application_mode' => 'live',
				'parent_status' => ['$ne' => 'parent'],
				'symbol' => $symbol, 
				'admin_id' => $user_id
			];
		
			$order_data = $db->$orders_collection->find($where);
			$order = iterator_to_array($order_data);
			// echo "<pre>"; print_r($order); exit;
			if (!empty($coins)) {
				$coins = array_column($coins, 'symbol');
				$coins = array_unique($coins);
				$coins = array_values($coins);
				unset($coins_data);
			}
		}

    }
} //end function


if (!function_exists('get_min_quantity')) {
    function get_min_quantity($exchange, $coins=[]){

        $CI = &get_instance();
        $db = $CI->mongo_db->customQuery();

		if (empty($coins)) {
			$coins_collection = ($exchange == "binance") ? "coins" : "coins_$exchange";
			$where = [
				'user_id' => 'global',
			];
			if ($exchange == 'binance') {
				$where['exchange_type'] = 'binance';
			}

			$coins_data = $db->$coins_collection->find($where);
			$coins = iterator_to_array($coins_data);
			if (!empty($coins)) {
				$coins = array_column($coins, 'symbol');
				$coins = array_unique($coins);
				$coins = array_values($coins);
				unset($coins_data);
			}
		}

		$prices_collection = ($exchange == "binance") ? "market_prices" : "market_prices_$exchange";

		$pipeline = [
			[
				'$match' => [
					'coin' => ['$in' => $coins],
				],
			],
			[
				'$sort' => [
					'created_date' => -1,
				],
			],
			[
				'$group' => [
					'_id' => '$coin',
					'price' => ['$first' => '$price'],
				],
			],
		];

		$prices_data = $db->$prices_collection->aggregate($pipeline);
		$prices_arr = iterator_to_array($prices_data);
		
		$pipeline = [
			[
				'$match' => [
					'symbol' => ['$in' => $coins],
				],
			],
		];
		$minNotation_collection = $exchange == "binance" || $exchange == "bam"  ? "market_min_notation" : "market_min_notation_$exchange";  
		$minNotation = $db->$minNotation_collection->aggregate($pipeline);
		$minNotation_arr = iterator_to_array($minNotation);
		unset($minNotation);

		$stepSizeArr = [];
		$minNotationArr = [];

		if(!empty($minNotation_arr)){
			$minNotationArr = array_column($minNotation_arr, 'min_notation', 'symbol');
		}
		
		if(!empty($minNotation_arr)){
			$stepSizeArr = array_column($minNotation_arr, 'stepSize', 'symbol');
		}

		if(!empty($prices_arr)){
			$prices_arr = array_column($prices_arr, 'price', '_id');
		}

		$minQtyArr = [];

		
		$BTCUSDT_price = 0;

		$coinsCount = count($coins);
		for($i=0; $i < $coinsCount; $i++){
			$symbol = $coins[$i];
			
			$min_not = (float) $minNotationArr[$symbol];
			$step_size = (float) $stepSizeArr[$symbol];
			$currentMarketPrice =  $prices_arr[$symbol];

			$extra_qty_percentage = 30;
			$extra_qty_val = 0;
			$extra_qty_val = ($extra_qty_percentage * $min_not)/100;
			$min_not += $extra_qty_val; 

			$per = $min_not / $currentMarketPrice;

			if($exchange == 'kraken'){
				$per = $min_not;
			}

			$minQty = $per+$step_size;
			
			$minQty = $per+$step_size;

			$toFixed = (float) strlen(substr(strrchr($step_size, "."), 1));

			$minQty = (float) number_format($minQty, $toFixed, '.', '');

			// $maxQty = (0.015 / (float) $currentMarketPrice);


			// $tarr = explode('USDT', $symbol);
			// if (isset($tarr[1]) && $tarr[1] == '') {
			// 	// echo "\r\n USDT coin";
			// 	$usd_balance = $balance['coin_balance'] * $BTCUSDT_price;
			// 	$convertamount = $price;
			// 	$convertamount = round($convertamount, 5);
			// } else {
			// 	// echo "\r\n BTC coin";
			// 	if ($symbol == "BTC") {
			// 		$usd_balance = $balance['coin_balance'] * $BTCUSDT_price;
			// 		$convertamount = $BTCUSDT_price;
			// 		$convertamount = round($convertamount, 5);
			// 	} else {
			// 		$usd_balance = $balance['coin_balance'] * $price * $BTCUSDT_price;
			// 		$convertamount = $price * $BTCUSDT_price;
			// 		$convertamount = round($convertamount, 5);
			// 	}
			// }


			$minQtyArr[$symbol] = [
				'currentMarketPrice' => $currentMarketPrice,
				'min_not' => $min_not,
				'min_qty' => $minQty,
				'step_size' => $step_size,
			];
		}

		if (!empty($minQtyArr)) {
			return $minQtyArr;
		}
		return [];

    }
} //end get_min_quantity


if (!function_exists('getAvgProfitLoss')) {
	function getAvgProfitLoss($symbol, $rule_number, $trigger_type) {

		$CI = &get_instance();
		$connect = $CI->mongo_db->customQuery();
		//'order_type' => 'sell',
		$project = array(
			'$project' => array(
				"buy_order_id" => 1,

				"coin_symbol" => 1,
				"order_type" => 1,
				"rule" => 1,
			),
		);
		$match = array(
			'$match' => array(
				'coin_symbol' => $symbol,
				"rule" => $rule_number,
			),
		);

		$sort = array('$sort' => array('hour' => -1));
		$limit = array('$limit' => 10000);

		$record_of_rules_for_orders = $connect->record_of_rules_for_orders->aggregate(array($project, $match, $sort, $limit));
		$rulesSet_arr = iterator_to_array($record_of_rules_for_orders);

		$sum = 0;
		$countBuyOrder = 1;
		foreach ($rulesSet_arr as $row) {

			$buy_order_id = $row['buy_order_id'];
			$skip = 0;
			$limit = 10000;
			$search_array = array('symbol' => $symbol);
			$search_array['_id'] = $CI->mongo_db->mongoId($buy_order_id);
			$qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
			$cursor = $connect->buy_orders->find($search_array, $qr);
			$res = iterator_to_array($cursor);
			$valueArr = (array) $res[0];

			$returArr = array();

			if (!empty($valueArr)) {

				$returArr['_id'] = $valueArr['_id'];
				$returArr['symbol'] = $valueArr['symbol'];
				$returArr['binance_order_id'] = isset($valueArr['binance_order_id']) ? $valueArr['binance_order_id'] : 0;
				$returArr['price'] = isset($valueArr['price']) ? $valueArr['price'] : 0;
				$returArr['quantity'] = isset($valueArr['quantity']) ? $valueArr['quantity'] : 0;
				$returArr['order_type'] = isset($valueArr['order_type']) ? $valueArr['order_type'] : 0;
				$returArr['market_value'] = isset($valueArr['market_value']) ? $valueArr['market_value'] : 0;
				$returArr['trail_check'] = isset($valueArr['trail_check']) ? $valueArr['trail_check'] : 0;
				$returArr['trail_interval'] = isset($valueArr['trail_interval']) ? $valueArr['trail_interval'] : 0;
				$returArr['buy_trail_price'] = isset($valueArr['buy_trail_price']) ? $valueArr['buy_trail_price'] : 0;
				$returArr['status'] = isset($valueArr['status']) ? $valueArr['status'] : '';
				$returArr['is_sell_order'] = isset($valueArr['is_sell_order']) ? $valueArr['is_sell_order'] : '';
				$returArr['market_sold_price'] = isset($valueArr['market_sold_price']) ? $valueArr['market_sold_price'] : '';
				$returArr['sell_order_id'] = isset($valueArr['sell_order_id']) ? $valueArr['sell_order_id'] : '';
				$returArr['admin_id'] = $valueArr['admin_id'];
				$returArr['trigger_type'] = isset($valueArr['trigger_type']) ? $valueArr['trigger_type'] : '';
				$returArr['buy_parent_id'] = isset($valueArr['buy_parent_id']) ? $valueArr['buy_parent_id'] : '';
				$returArr['inactive_status'] = isset($valueArr['inactive_status']) ? $valueArr['inactive_status'] : '';

				$returArr['pause_status'] = isset($valueArr['pause_status']) ? $valueArr['pause_status'] : '';
				$returArr['parent_status'] = isset($valueArr['parent_status']) ? $valueArr['parent_status'] : '';
				$returArr['application_mode'] = isset($valueArr['application_mode']) ? $valueArr['application_mode'] : '';

				if ($valueArr['status'] == 'FILLED') {
					$total_buy_amount += num($returArr['market_value']);
				}
				if ($returArr['is_sell_order'] == 'sold') {
					$total_sold_orders += 1;
					$total_sell_amount += num($returArr['market_sold_price']);
				}
				if ($returArr['is_sell_order'] == 'sold') {
					$market_sold_price = $returArr['market_sold_price'];
					$current_order_price = $returArr['market_value'];
					$quantity = $returArr['quantity'];
					$current_data2222 = $market_sold_price - $current_order_price;
					$profit_data = ($current_data2222 * 100 / $market_sold_price);
					$profit_data = number_format((float) $profit_data, 2, '.', '');
					$total_profit += $quantity * $profit_data;
					$total_quantity += $quantity;
				}
			}
			$fullarray[] = $returArr;
			$sum += $value;
			$countBuyOrder++;
		}

		if ($total_quantity == 0) {$total_quantity = 1;}

		$avg_profit = $total_profit / $total_quantity;
		$return_data['fullarray'] = $fullarray;
		$return_data['buyorder'] = $sum;
		$return_data['total_buy_amount'] = num($total_buy_amount);
		$return_data['total_sell_amount'] = num($total_sell_amount);
		$return_data['total_sold_orders'] = $total_sold_orders;
		$return_data['avg_profit'] = number_format($avg_profit, 2, '.', '');

		return $return_data;

	}

}

if (!function_exists('getBuyorderData')) {
	function getBuyorderData($symbol, $rule_number, $trigger_type) {

		$CI = &get_instance();
		$connect = $CI->mongo_db->customQuery();
		//'order_type' => 'sell',
		$project = array(
			'$project' => array(
				"buy_order_id" => 1,

				"coin_symbol" => 1,
				"order_type" => 1,
				"rule" => 1,
			),
		);
		$match = array(
			'$match' => array(
				'coin_symbol' => $symbol,
				"rule" => $rule_number,
			),
		);

		$sort = array('$sort' => array('hour' => -1));
		$limit = array('$limit' => 10000);

		$record_of_rules_for_orders = $connect->record_of_rules_for_orders->aggregate(array($project, $match, $sort, $limit));
		$rulesSet_arr = iterator_to_array($record_of_rules_for_orders);

		$sum = 0;
		$countBuyOrder = 1;
		foreach ($rulesSet_arr as $row) {

			$buy_order_id = $row['buy_order_id'];
			$skip = 0;
			$limit = 10000;
			$admin_id = '169';

			$search_array['symbol'] = $symbol;
			$search_array['admin_id'] = $admin_id;

			$search_array['_id'] = $CI->mongo_db->mongoId($buy_order_id);
			$qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
			$cursor = $connect->buy_orders->find($search_array, $qr);
			$res = iterator_to_array($cursor);

			$valueArr = (array) $res[0];
			$returArr = array();

			if (!empty($valueArr)) {

				$returArr['_id'] = $valueArr['_id'];
				$returArr['symbol'] = $valueArr['symbol'];
				$returArr['binance_order_id'] = isset($valueArr['binance_order_id']) ? $valueArr['binance_order_id'] : 0;
				$returArr['price'] = isset($valueArr['price']) ? $valueArr['price'] : 0;
				$returArr['quantity'] = isset($valueArr['quantity']) ? $valueArr['quantity'] : 0;
				$returArr['order_type'] = isset($valueArr['order_type']) ? $valueArr['order_type'] : 0;
				$returArr['market_value'] = isset($valueArr['market_value']) ? $valueArr['market_value'] : 0;
				$returArr['trail_check'] = isset($valueArr['trail_check']) ? $valueArr['trail_check'] : 0;
				$returArr['trail_interval'] = isset($valueArr['trail_interval']) ? $valueArr['trail_interval'] : 0;
				$returArr['buy_trail_price'] = isset($valueArr['buy_trail_price']) ? $valueArr['buy_trail_price'] : 0;
				$returArr['status'] = isset($valueArr['status']) ? $valueArr['status'] : '';
				$returArr['is_sell_order'] = isset($valueArr['is_sell_order']) ? $valueArr['is_sell_order'] : '';
				$returArr['market_sold_price'] = isset($valueArr['market_sold_price']) ? $valueArr['market_sold_price'] : '';
				$returArr['sell_order_id'] = isset($valueArr['sell_order_id']) ? $valueArr['sell_order_id'] : '';
				$returArr['admin_id'] = $valueArr['admin_id'];
				$returArr['trigger_type'] = isset($valueArr['trigger_type']) ? $valueArr['trigger_type'] : '';
				$returArr['buy_parent_id'] = isset($valueArr['buy_parent_id']) ? $valueArr['buy_parent_id'] : '';
				$returArr['inactive_status'] = isset($valueArr['inactive_status']) ? $valueArr['inactive_status'] : '';

				$returArr['pause_status'] = isset($valueArr['pause_status']) ? $valueArr['pause_status'] : '';
				$returArr['parent_status'] = isset($valueArr['parent_status']) ? $valueArr['parent_status'] : '';
				$returArr['application_mode'] = isset($valueArr['application_mode']) ? $valueArr['application_mode'] : '';

				if ($valueArr['status'] == 'FILLED') {
					$total_buy_amount += num($returArr['market_value']);
				}
				if ($returArr['is_sell_order'] == 'sold') {
					$total_sold_orders += 1;
					$total_sell_amount += num($returArr['market_sold_price']);
				}
				if ($returArr['is_sell_order'] == 'sold') {
					$market_sold_price = $returArr['market_sold_price'];
					$current_order_price = $returArr['market_value'];
					$quantity = $returArr['quantity'];
					$current_data2222 = $market_sold_price - $current_order_price;
					$profit_data = ($current_data2222 * 100 / $market_sold_price);
					$profit_data = number_format((float) $profit_data, 2, '.', '');
					$total_profit += $quantity * $profit_data;
					$total_quantity += $quantity;
				}
			}
			$fullarray[] = $returArr;
			$sum += $countBuyOrder;
			//$countBuyOrder++;
		}

		if ($total_quantity == 0) {$total_quantity = 1;}

		$avg_profit = $total_profit / $total_quantity;
		$return_data['fullarray'] = $fullarray;
		$return_data['buyorder'] = $sum;
		$return_data['total_buy_amount'] = num($total_buy_amount);
		$return_data['total_sell_amount'] = num($total_sell_amount);
		$return_data['total_sold_orders'] = $total_sold_orders;
		$return_data['avg_profit'] = number_format($avg_profit, 2, '.', '');
		//echo "<pre>";  print_r($return_data);    exit;
		return $return_data;
	}
}

if (!function_exists('verify_login()')) {
	function verify_login() {

		$ci = &get_instance();
		$logged_in = $ci->session->userdata('logged_in');
		if (!$logged_in) {
			$ci->session->set_flashdata('err_message', 'Please Login to access this section');
			redirect(base_url() . 'login');
		}
	}
}

if (!function_exists('convert_to_usd_price')) {
	function convert_to_usd_price($currency = 'USD', $value = "1") {
		$url = 'https://bitpay.com/api/rates/' . $currency;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15"));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		curl_close($ch);
		$info = json_decode($result, true);
		$ret = $info['rate'] * $value;
		return "$ " . number_format($ret, 2);
	}
}

if (!function_exists('btc_usd_convert')) {
	function btc_usd_convert($currency = 'USD', $value = "1") {
		$url = 'https://bitpay.com/api/rates/' . $currency;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15"));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		curl_close($ch);
		$info = json_decode($result, true);
		$ret = $info['rate'] * $value;
		return ($ret);
	}
}

if (!function_exists('print_me')) {
	function print_me($array, $name = 'shahzad') {

		if ($_SERVER['REMOTE_ADDR'] == '58.65.164.72' && $name == 'shahzad') {
			echo '<pre>';
			print_r($array);
			echo '</pre>';
			exit;

		} else if ($_SERVER['REMOTE_ADDR'] == '45.115.84.51' && $name == 'waqar') {
			echo '<pre>';
			print_r($array);
			echo '</pre>';
			exit;

		} else if ($_SERVER['REMOTE_ADDR'] == '58.65.164.72' && $name == 'saeed') {
			echo '<pre>';
			print_r($array);
			echo '</pre>';
			exit;

		}
	}
} //print_me

function unique_array($my_array, $key) {
	$result = array();
	$i = 0;
	$key_array = array();

	foreach ($my_array as $val) {
		if (!in_array($val[$key], $key_array)) {
			$key_array[$i] = $val[$key];
			$result[$i] = $val;
		}
		$i++;
	}
	return $result;
}


if(!function_exists('convertCoinBalanceIntoUSDT')){
	function convertCoinBalanceIntoUSDT($coinSymbol, $coinBalance, $exchange ){
		// echo "<br>symbol =====>>>>".$coinSymbol;
		// echo "<br>exchnage =========>>>>>>>".$exchange; 
		// echo "<br>coin balance =========>>>>>>>".$coinBalance;
		$CI = &get_instance();
		$CI->load->model('admin/mod_coins');

		$collectionName = ($exchange == 'binance')? 'market_prices': 'market_prices_'.$exchange;
		$db = $CI->mongo_db->customQuery();
		$lookup = [
			[
				'$match' => [
					'coin' => $coinSymbol
				]
			],
			[
				'$group' =>[
					'_id'   => '$coin',
					'price' => ['$first' => '$price'],
				]
			]
		];

		$data = $db->$collectionName->aggregate($lookup);
		$response = iterator_to_array($data);

		$usdt =  $response[0]['price'] * $coinBalance;
		// echo "<br> current market of this coin is ===>: ".$response[0]['price'];
		// echo"<br>converted USDT worth: ".$usdt;
		return $usdt;
	}
}

if(!function_exists('getMarketWothByQuantity')){
	function getMarketWothByQuantity($coinSymbol, $quantity, $exchange ){
	
		$CI = &get_instance();
		$CI->load->model('admin/mod_coins');

		$collectionName = ($exchange == 'binance')? 'market_prices': 'market_prices_'.$exchange;
		$db = $CI->mongo_db->customQuery();
		$lookup = [
			[
				'$match' => [
					'coin' => $coinSymbol
				]
			],
			[
				'$group' =>[
					'_id'   => '$coin',
					'price' => ['$first' => '$price'],
				]
			]
		];

		$data = $db->$collectionName->aggregate($lookup);
		$response = iterator_to_array($data);

		$usdt =  $response[0]['price'] * $quantity;
		return $usdt;
	}
}


if(!function_exists('convertCoinBalanceIntobtctoUSDT')){
	function convertCoinBalanceIntobtctoUSDT($coinSymbol, $coinBalance, $exchange ){

		// echo "<br>symbol =====>>>>".$coinSymbol;
		// echo "<br>exchnage =========>>>>>>>".$exchange; 
		// echo "<br>coin balance =========>>>>>>>".$coinBalance;
		$CI = &get_instance();
		$CI->load->model('admin/mod_coins');

		$collectionName = ($exchange == 'binance')? 'market_prices': 'market_prices_'.$exchange;
		$db = $CI->mongo_db->customQuery();
		$lookup = [
			[
				'$match' => [
					'coin' => $coinSymbol
				]
			],
			[
				'$group' =>[
					'_id'   => '$coin',
					'price' => ['$first' => '$price'],
				]
			]
		];
		$data = $db->$collectionName->aggregate($lookup);
		$response = iterator_to_array($data);
		$btc =  $response[0]['price'] * $coinBalance;
		// echo "<br> current market of this coin is ===>: ".$response[0]['price'];
		// echo"<br>converted btc: ".$btc;
		$lookup1 = [
			[
				'$match' => 
				[
					'coin' => 'BTCUSDT'
				]
			],
			[
				'$group' =>
				[
					'_id'   => '$coin',
					'price' => ['$first' => '$price'],
				]
			]
		];
		$res = $db->$collectionName->aggregate($lookup1);
		$btcPrice = iterator_to_array($res);
		$usdtWorth = $btc* $btcPrice[0]['price'];
		// echo "<br>BTC Current Price: ".$btcPrice[0]['price'];
		// echo "<br>USDT worth = : ".$usdtWorth;
		return $usdtWorth;
	}
}

if (!function_exists('dynamicCURLHitForAPICheckingKraken')) {
	function dynamicCURLHitForAPICheckingKraken($req) {
		// echo $encryptedKey; exit;
		// echo '<br>comming'; exit;
        $jsondataPayLoad   =  $req['req_params'];
        $req_url           =  $req['req_url'];
        // $req_type          =  $req['req_type'];
		// echo "<payload>: ".$jsondataPayLoad;
		// echo '<br>'.$req_url;
		$jsondataPayLoad = json_encode($jsondataPayLoad);
		$curl = curl_init();
		curl_setopt_array($curl, [
		CURLOPT_URL =>  $req_url,//"https://ip4.digiebot.com/apiKeySecret/validateApiKeySecretAdmin",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $jsondataPayLoad,
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/json",
			"Token:vizzwebsolutions12345678910",
		],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

        if ($response === false) {
            echo "Error: " . curl_error($curl);
            curl_close($curl);
            exit();
        }

		curl_close($curl);
        return $response;
	}
} //end num
//for test DG
if (!function_exists('dynamicCURLHitForAPICheckingDg')) {
	function dynamicCURLHitForAPICheckingDg($req) {
        $jsondataPayLoad   =  $req['req_params'];
        $req_url           =  $req['req_url'];
      
		$jsondataPayLoad = json_encode($jsondataPayLoad);
		$curl = curl_init();
		curl_setopt_array($curl, [
		CURLOPT_URL =>  $req_url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $jsondataPayLoad,
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/json",
			"Token:vizzwebsolutions12345678910",
		],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if(!empty($_COOKIE['hassam'])) {
			echo '<pre> err :: '; print_r($err);
            echo '<pre> $responseArr :: '; print_r($responseArr); exit;
        }

		if ($response === false) {
            echo "Error: " . curl_error($curl);
            curl_close($curl);
            exit();
        }
		// echo '<pre> response '; print_r($response);
		// echo '<br><pre>err '; print_r($err);
		// exit;
        return $response;
	}
} //end num
if (!function_exists('dynamicCURLHitForAPIChecking')) {
	function dynamicCURLHitForAPIChecking($req, $encryptedKey) {
		// echo $encryptedKey; exit;
		// echo '<br>comming'; exit;
        $jsondataPayLoad   =  $req['req_params'];
        $req_url           =  $req['req_url'];
        // $req_type          =  $req['req_type'];
		// echo "<payload>: ".$jsondataPayLoad;
		// echo '<br>'.$req_url;
		$jsondataPayLoad = json_encode($jsondataPayLoad);
		$curl = curl_init();
		curl_setopt_array($curl, [
		CURLOPT_URL =>  $req_url,//"https://ip4.digiebot.com/apiKeySecret/validateApiKeySecretAdmin",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $jsondataPayLoad,
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/json",
			"Token:vizzwebsolutions12345678910",
			"Authorization :".$encryptedKey
		],
		]);

		$response = curl_exec($curl);

		if ($response === false) {
            echo "Error: " . curl_error($curl);
            curl_close($curl);
            exit();
        }
		
		$err = curl_error($curl);
		curl_close($curl);
        return $response;

	}
} //end num

if (!function_exists('dynamicCURLHitForAPICheckingOpen')) {
	function dynamicCURLHitForAPICheckingOpen($req) {
		// echo $encryptedKey; exit;
		// echo '<br>comming'; exit;
        $jsondataPayLoad   =  $req['req_params'];
        $req_url           =  $req['req_url'];
        // $req_type          =  $req['req_type'];
		// echo "<payload>: ".$jsondataPayLoad;
		// echo '<br>'.$req_url;
		$jsondataPayLoad = json_encode($jsondataPayLoad);
		$curl = curl_init();
		curl_setopt_array($curl, [
		CURLOPT_URL =>  $req_url,//"https://ip4.digiebot.com/apiKeySecret/validateApiKeySecretAdmin",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $jsondataPayLoad,
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/json",
			"Token:vizzwebsolutions12345678910"
		],
		]);

		$response = curl_exec($curl);

		if ($response === false) {
            echo "Error: " . curl_error($curl);
            curl_close($curl);
            exit();
        }

		$err = curl_error($curl);
		curl_close($curl);
        return $response;
	}
} //end num

if (!function_exists('dynamicCURLHitForAPICheckingAdmin')) {
	function dynamicCURLHitForAPICheckingAdmin($req) {
		// echo '<br>comming';
        $jsondataPayLoad   =  $req['req_params'];
        $req_url           =  $req['req_url'];
        // $req_type          =  $req['req_type'];
		// echo "<payload>: ".$jsondataPayLoad;
		// echo '<br>'.$req_url;
		$jsondataPayLoad = json_encode($jsondataPayLoad);
		$curl = curl_init();
		curl_setopt_array($curl, [
		CURLOPT_URL =>  $req_url,//"https://ip4.digiebot.com/apiKeySecret/validateApiKeySecretAdmin",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $jsondataPayLoad,
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/json",
			"Token:vizzwebsolutions12345678910"
		],
		]);

		$response = curl_exec($curl);

		if ($response === false) {
            echo "Error: " . curl_error($curl);
            curl_close($curl);
            exit();
        }

		$err = curl_error($curl);
		curl_close($curl);
        return $response;
	}
} //end num


if(!function_exists('getAllMarketValue')){
	function getAllMarketValue($exchange){
		$CI = &get_instance();
		$CI->load->model('admin/mod_coins');

		$collectionName = ($exchange == 'binance')? 'market_prices': 'market_prices_'.$exchange;
		$db = $CI->mongo_db->customQuery();
		$lookup = [
			[
				'$group' =>[
					'_id'   => '$coin',
					'price' => ['$first' => '$price'],
				]
			]
		];

		$data = $db->$collectionName->aggregate($lookup);
		$response = iterator_to_array($data);
		$prices_arr = array_column($response, 'price', '_id');

		return $prices_arr;
	}

}
	if (!function_exists('get_coin_icon_by_symbol')) {
				function get_coin_icon_by_symbol($coin_symbol) {
					$CI = &get_instance();
					$db = $CI->mongo_db->customQuery();
										$lookup = [
						[
							'$match' => [
								'symbol' => ['$eq' => $coin_symbol]
							]
						],
						[
							'$project' =>[
								'_id'   => 0,
								'coin_logo' => 1,
							]
						],
						[
							'$sort'=>[
								  '_id'=> -1,
							]
						]
					];

					$data = $db->coins->aggregate($lookup);
					$response = iterator_to_array($data);
					//echo '<pre>';print_r($response);
					return $response[0]['coin_logo'];
					
				}
			} //end num
if(!function_exists('get_status_of_test_case')){ // helper func to get the test case status just for the listing
	function get_status_of_test_case($case_id){
		$CI = &get_instance();
		$db = $CI->mongo_db->customQuery();
		$lookup = [
			[
				'$match' =>[
					'is_fixed'   => false,
					'test_case' => $case_id,
				]
			]
		];

		$data = $db->test_cases_users->aggregate($lookup);
		$response = iterator_to_array($data);
		return count($response);
	}
}
if(!function_exists('get_user_doubt_test_case')){ // helper func to get the test case status just for the listing
	function get_user_doubt_test_case($case_id){
		if($case_id == 'TCB'){
			$collection = 'user_trade_history';
		}else{
			$collection = 'user_trade_history_kraken';
		}
		$CI = &get_instance();
		$db = $CI->mongo_db->customQuery();
		$lookup = [
			[
				'$match' =>[
					'status'   => 'user_doubt',
				]
			]
		];
		$data = $db->$collection->aggregate($lookup);
		$response = iterator_to_array($data);
		return count($response);
	}
}
function get_blocked_countries_of_coin($coin_symbol){
      if (!empty($coin_symbol)) {
        $CI = &get_instance();
        $mongo = $CI->mongo_db->customQuery();
        $iterator = $mongo->coins->findOne(['symbol' =>$coin_symbol,'user_id'=>'global']);
        if ($iterator) {
            return iterator_to_array($iterator);
        }
    }
}
function RestCountries() {
    return json_decode(file_get_contents('https://restcountries.com/v2/all'), true);
}
function get_user_accumulations($exchange,$user_id){
		$CI = &get_instance();
		$db = $CI->mongo_db->customQuery();
		if($exchange == 'kraken'){
			$collection = 'accumulation_weekly_kraken';
			$collection_monthly = 'accumulation_monthly_kraken';
		}else{
				$collection = 'accumulation_weekly_binance';
			  $collection_monthly = 'accumulation_monthly_binance';
		}
		$lookup = [
			[
				'$match' =>[
					'user_id'   => $user_id,
				]
			]
		];

		$data = $db->$collection->aggregate($lookup);
		$data_monthly = $db->$collection_monthly->aggregate($lookup);
		$data_week = iterator_to_array($data);
		$data_month = iterator_to_array($data_monthly);
		$result['weekly_accumulations'] = isset($data_week[0])?$data_week[0]['weekly_accumulations']:array();
		$result['monthly_accumulations'] = isset($data_month[0])?$data_month[0]['monthly_accumulations']:array();
		$response = $result;
		return $response;
}

function dynamicCURLHitForCoinGecko($date, $retryCount = 3) {
    $req_url = 'https://api.coingecko.com/api/v3/coins/bitcoin/market_chart/range';

    $startTimestamp = strtotime($date);
    $endTimestamp = $startTimestamp + 86400;

    $params = [
        'vs_currency' => 'usd',
        'from' => $startTimestamp,
        'to' => $endTimestamp,
    ];

    $req_url .= '?' . http_build_query($params);

	echo '<pre>req url :: '; print_r($req_url); exit;

    for ($i = 0; $i < $retryCount; $i++) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $req_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
          
            echo "cURL Error: $err" . PHP_EOL;
        } else {
            $data = json_decode($response, true);

            if ($data && isset($data['prices'][0][1])) {
                $closingPrice = end($data['prices'])[1];
                return $closingPrice;
            } else {
                echo "Error: Unable to fetch the closing price for the specified date." . PHP_EOL;
                echo "Response: " . print_r($response, true) . PHP_EOL;
            }
        }

        $waitTime = pow(2, $i);
        sleep($waitTime);
    }

    return "Error: Unable to fetch the closing price after $retryCount retries.";
}



function getCurrentPrice($symbol){
	$filter = [
		'coin' => $symbol,
	];

	$CI = &get_instance();
	$db = $CI->mongo_db->customQuery();
	$results = $db->market_prices->find($filter);
	$response = iterator_to_array($results);
	// if ($symbol == 'NEOBTC') {
	// 	echo "<pre>"; print_r($reponse); exit;
	// }
	if (count($response) > 0 ) {
		$price = $response[0]['price'];
		return $price;
	}else{
		return 0;
	}
}

if (!function_exists('yesterdayQTYExtraLessBalance')) {
	function yesterdayQTYExtraLessBalance($user_id, $exchange, $symbol, $quantity='')
	{	
		if ($exchange == 'binance') {
			$collection = 'manage_coin_mirror';
		} else {
			$collection = 'manage_coin_mirror_kraken';
		}

		$filter = [
			"coin_balance_data.symbol" => $symbol,
			"user_id" => $user_id,
			"coin_balance_data.required_balance" => ['$ne' => $quantity]
		];

		$options = [
			'sort' => ['created_date' => -1],
		];

		// Execute the query
		$CI = &get_instance();
		$db = $CI->mongo_db->customQuery();
		// $db = $this->mongo_db->customQuery();
		$results = $db->$collection->find($filter, $options);

		$response = iterator_to_array($results);
		$response = $response[0]['coin_balance_data'];
		$filteredResponse = [];
	
		// if ($symbol == 'QTUMBTC') {
		// 	echo '<pre>filter :: '; print_r($filter);
		// 	echo '<pre>Res :: '; print_r($reponse); exit;
		// }
		if (!empty($response)) {

			if (is_array($response) || is_object($response)) {
				foreach ($response as $res) {
					if ($res['symbol'] == $symbol) {
						if (isset($res['extra_balance_usd_worth'])) {
							$res['extra_balance_usd_worth_exists'] = true;
						} else {
							$res['extra_balance_usd_worth_exists'] = false;
						}

						if (isset($res['required_balance_usd_worth'])) {
							$res['required_balance_usd_worth_exists'] = true;
						} else {
							$res['required_balance_usd_worth_exists'] = false;
						}
						
						// echo "<pre>"; print_r($res); exit;
						$filteredResponse[] = $res;
						break;
					}
				}
			}
			
			// echo "<pre>"; print_r($filteredResponse[0]); exit;
			if ($filteredResponse[0]['extra_balance_usd_worth_exists'] == true || $filteredResponse[0]['extra_balance_usd_worth_exists'] == 1) {
				$extra = "yesterday_extra";
				$responseValue['extra'] = $extra;
				$responseValue['value'] = $filteredResponse[0]['extra_balance'];
				$responseValue['extra_balance'] = $filteredResponse[0]['extra_balance_usd_worth'];
				
			}elseif($filteredResponse[0]['required_balance_usd_worth_exists'] == true || $filteredResponse[0]['required_balance_usd_worth_exists'] == 1){
				$less = "yesterday_less";
				$responseValue['less'] = $less;
				$responseValue['value'] = $filteredResponse[0]['required_balance'];
				$responseValue['less_balance'] = $filteredResponse[0]['required_balance_usd_worth'];
			}

			if ($responseValue) {
				return $responseValue;
			} else {
				return false;
			}
		}
	}
}

// if (!function_exists('checkPreviousRecordsIfSameFromThreeDays')) {
//     function checkPreviousRecordsIfSameFromThreeDays($user_id, $exchange, $symbol)
//     {
//         $filter = [
//             'user_id' => $user_id,
//             'symbol' => $symbol,
//             'exchange' => $exchange,
//         ];

//         $options = [
//             'sort' => ['created_date' => -1],
//             'limit' => 3,
//         ];

//         $CI = &get_instance();
//         $db = $CI->mongo_db->customQuery();
//         $results = $db->extra_less_backup_coinwise->find($filter, $options);
//         $response = iterator_to_array($results);

		
//         if (count($response) >= 3) {
//             $quantities = array_column($response, 'quantity');
// 			if (count(array_unique($quantities)) === 1) {
// 				return $response[0];
// 			}
//         }

//         return false; 
//     }
	
// }

if (!function_exists('checkPreviousRecordsIfSameFromThreeDays')) {
    function checkPreviousRecordsIfSameFromThreeDays($user_id, $exchange, $symbol)
    {
        $filter = [
            'user_id' => $user_id,
            'symbol' => $symbol,
            'exchange' => $exchange,
        ];

        $options = [
            'sort' => ['created_date' => -1],
            'limit' => 4, 
        ];

        $CI = &get_instance();
        $db = $CI->mongo_db->customQuery();
        $results = $db->extra_less_backup_coinwise->find($filter, $options);
        $response = iterator_to_array($results);

        if (count($response) >= 4) {
            $quantities = array_column($response, 'quantity');
            $lastThree = array_slice($quantities, 0, 3);
			
            $last = $quantities[3]; 
			
			
            if (count(array_unique($lastThree)) === 1 && $lastThree[0] !== $last) {
				echo '<pre>'; print_r('=================================================================================================='. $symbol);
				echo '<pre>Last Three :: '; print_r($lastThree);
				echo '<pre>Last :: '; print_r($last);
                $result = [
                    'last_three_quantities' => $lastThree, 
                    'previous_quantity' => $last, 
                ];
                return $result;
            }
        }

        return false;
    }
}

if(!function_exists('convert_btc_to_usdt')){
	function convert_btc_to_usdt($btc){
		$CI     =   &get_instance();
		$db     =   $CI->mongo_db->customQuery();

		$lookup = [
			[
				'$match' => [
					'coin' => 'BTCUSDT'
				]
			],
			[
				'$group' =>[
					'_id'   => '$coin',
					'price' => ['$first' => '$price'],
				]
			]
		];
		$res        =   $db->market_prices->aggregate($lookup);
		$btcPrice   =   iterator_to_array($res);
		$usdtWorth  =   $btc* $btcPrice[0]['price'];
		
		return $usdtWorth;
	}
}//end if function exists
if(!function_exists('sort_coins_category_wise')){
	function sort_coins_category_wise($selected_coins){
		//putting 0 category in last
		usort($selected_coins, function ($a, $b) {
			$categoryA = $a['category'];
			$categoryB = $b['category'];
	  
			if ($categoryA == 0 && $categoryB == 0) {
				return strcmp($a['symbol'], $b['symbol']);
			}

			if ($categoryA == 0) {
				return 1;
			} elseif ($categoryB == 0) {
				return -1;
			}

			return $categoryA - $categoryB;
		});
		
		return $selected_coins;
	}
}//end if function exists

if (!function_exists('getBuyTimeBtcPrice')) {
    function getBuyTimeBtcPrice($orderId, $exchange) {
        // beacause of open orders we just need to check in the buy collection
        $collection = ($exchange == 'binance') ? 'buy_orders' : 'buy_orders_kraken';

		$db = $this->mongo_db->customQuery();

		$id = $orderId;
		$orderObj = $db->$collection->find(['_id'=> new MongoDB\BSON\ObjectId($id)]);
		$orderArr = iterator_to_array($orderObj);

		if (count($orderArr) > 0) {
			$btcPrice = $orderArr['buy_time_btc_price'];
			if (isset($btcPrice) && $btcPrice != null) {
				return $btcPrice;
			}else{
				return false;
			}
		}else{
			return false;
		}

    }
}


function encryptCode($plainText, $key) {
    $key = padKey($key);

    $cipher = 'aes-256-cbc';
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);

    $paddedPlainText = pkcs7Pad($plainText);

    $encryptedText = openssl_encrypt($paddedPlainText, $cipher, $key, OPENSSL_RAW_DATA, $iv);

    return base64_encode($iv . $encryptedText);
}

function padKey($key) {
    $requiredKeyLength = 32;

    if (strlen($key) == $requiredKeyLength) {
        return $key;
    }

    if (strlen($key) < $requiredKeyLength) {
        return str_pad($key, $requiredKeyLength, "\0");
    }

    return substr($key, 0, $requiredKeyLength);
}

function pkcs7Pad($data) {
    $blockSize = 16;
    $padding = $blockSize - (strlen($data) % $blockSize);
    return $data . str_repeat(chr($padding), $padding);
}

if (!function_exists('generateModifiedString')) {
    function generateModifiedString($originalString) {
        $prefix = mt_rand(100000, 999999);
        $suffix = mt_rand(100, 999);
        $modifiedString = $prefix . $originalString . $suffix;
        return $modifiedString;
    }
}

function fetchHistoricalPriceForDate($date) {
	// Binance API endpoint for historical k-line data
	$endpoint = 'https://api.binance.com/api/v3/klines';

	// Parameters for the API request
	$symbol = 'BTCUSDT'; // Trading pair
	$interval = '1d'; // Daily interval
	$startTime = strtotime($date . ' 00:00:00') * 1000; // Start time in milliseconds for the specified date
	$endTime = strtotime($date . ' 23:59:59') * 1000; // End time in milliseconds for the specified date

	// Construct the query string
	$queryString = http_build_query([
		'symbol' => $symbol,
		'interval' => $interval,
		'startTime' => $startTime,
		'endTime' => $endTime,
	]);

	// Construct the full URL
	$url = $endpoint . '?' . $queryString;

	// Make the API request
	$response = file_get_contents($url);

	// Check if the request was successful
	if ($response !== false) {
		// Decode the JSON response
		$data = json_decode($response, true);

		// Return the historical prices
		return $data;
	} else {
		// Return false if an error occurred
		return false;
	}
}
