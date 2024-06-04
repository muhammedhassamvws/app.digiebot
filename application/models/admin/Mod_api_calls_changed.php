<?php
class mod_api_calls extends CI_Model {

	/*Umer Abbas*/

	function __construct() {
		parent::__construct();
	}

	// save_binance_api_key
	public function save_binance_api_key($user_id, $api_key, $api_secret, $exchange) {


        // $this->mongo_db->where(array('user_id' => $user_id));
		// $get_settings = $this->mongo_db->get('settings');
		// $settings_arr = iterator_to_array($get_settings);


		// $orig_date22 = new DateTime();
		// $orig_date22 = $orig_date22->getTimestamp();
		// $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);

		if(!empty($exchange)){
            if($exchange != 'binance'){
                $exchange = "_$exchange";
            }else{
                $exchange = "";
            }
        }

		$ins_array = array(
			"api_key$exchange" => $api_key,
			"api_secret$exchange" => $api_secret,
		);
		// if (count($settings_arr) > 0) {
			$this->mongo_db->where(array('_id' => $user_id));
			$this->mongo_db->set($ins_array);
			$ins = $this->mongo_db->update('users');

			// $this->mongo_db->where(array('_id' => $admin_id));
			// $this->mongo_db->set($ins_array);
			// $get_settings = $this->mongo_db->update('users');


		// } else {
		// 	$ins = $this->mongo_db->insert('settings', $ins_array);
		// }

		if ($ins) {
			return true;
		} else {
			return false;
        }

	}// end save_binance_api_key

	// save_api_key //Umer Abbas [12-11-19] TODO:Remove old save_binance_api_key function after this goes live 
	public function save_api_key($user_id, $api_key, $api_secret, $exchange) {

		if(!empty($exchange)){
            if($exchange != 'binance'){
                $exchange = "_$exchange";
            }else{
                $exchange = "";
            }
        }

		$user_id = $this->mongo_db->mongoId($user_id);
		$where_arr = array(
			'_id' => $user_id,
		);

		$update_arr = array(
			'$set' => array(
				"api_key$exchange" => $api_key,
				"api_secret$exchange" => $api_secret,
			),
		);

		$options_arr = array(
			'upsert' => true,
		);
		$db = $this->mongo_db->customQuery();
		$update = $db->users->updateOne($where_arr, $update_arr, $options_arr);

		if ($update) {
			return true;
		} else {
			return false;
        }

	}// end save_api_key
    
    // get_user_api_key
	public function get_user_api_key($user_id) {

        $this->mongo_db->where(array('_id' => $user_id));
		$get_settings = $this->mongo_db->get('users');
		$settings_arr = iterator_to_array($get_settings);

		if (count($settings_arr) > 0) {
			return $settings_arr[0];
		}
        return array();

	}// end get_user_api_key

	//delete_coin //Umer Abbas [30-10-19]
    public function delete_coin($coin_id){
        
        $this->mongo_db->where(array('_id' => $coin_id));
        $coin = $this->mongo_db->delete('coins');
        return $coin;
    }//end delete_coin
	
	//user_profile //Umer Abbas [30-10-19]
	public function user_profile($user_id){
        
        $this->mongo_db->where(array('_id' => $user_id));
		$user = $this->mongo_db->get('users');
		
        return iterator_to_array($user);
	}//end user_profile
	
	//authenticate_user //Umer Abbas [7-11-19]
	public function authenticate_user($user_id, $password){
		
		$where = array(
			'_id' => $user_id,
			'password' => md5($password),
		);
        $this->mongo_db->where($where);
		$user = $this->mongo_db->get('users');
		
        return iterator_to_array($user);
	}//end authenticate_user
	
	//get_trading_status //Umer Abbas [31-10-19]
	public function get_trading_status(){   
	}//end get_trading_status

	//get_latest_market_price //Umer Abbas [4-11-19]
	public function get_latest_market_price($coin, $exchange=''){

		if(!empty($exchange)){
            if($exchange != 'binance'){
				if($exchange == 'bam'){
					$exchange = "market_prices_node_$exchange";
				}else{
					$exchange = "market_prices_$exchange";
				}
            }else{
                $exchange = "market_prices";
            }
        }else{
			$exchange = "market_prices";
		}
 
		$where = array('coin' => $coin);
		$sort_limit = array('sort' => array('created_date' => -1), 'limit' => 1);
		$db = $this->mongo_db->customQuery();
		$result = $db->$exchange->find($where, $sort_limit);
		
		return iterator_to_array($result);
		
	}//end get_latest_market_price
	
	//get_coin_balance //Umer Abbas [4-11-19]
	public function get_coin_balance($user_id, $coin_symbol, $exchange){

		//Get latest rate of btciusdt
		$btcusdt_arr = $this->get_latest_market_price('BTCUSDT', $exchange);

		$collection = $exchange == 'binance' ? 'user_wallet' : 'user_wallet_'.$exchange ;

		if(empty($collection)){
			return false;
		}else{

			if($exchange == 'kraken'){
				$tCoinSymbol = $coin_symbol;
				$tarr = explode('USDT', $coin_symbol);
				if (isset($tarr[1]) && $tarr[1] == '') {
					// echo "\r\n USDT coin";
					if ($tarr[0] == "BTC" || ($tarr[0] == "" && $tarr[1] == "")) {
						$tCoinSymbol = 'USDT';
					} else {
						$tCoinSymbol = $tarr[0];
					}
				} else {
					// echo "\r\n BTC coin";
					if ($tarr[0] == "BTC") {
						$tCoinSymbol = $tarr[0];
					} else {
						$tarr = explode('BTC', $coin_symbol);
						$tCoinSymbol = $tarr[0];
					}
				}
				$where = array('user_id' => $user_id, 'coin_symbol' => $tCoinSymbol);
			}else{
				$where = array('user_id' => $user_id, 'coin_symbol' => $coin_symbol);
			}

			$db = $this->mongo_db->customQuery();
			$result = $db->$collection->find($where);
			$result = iterator_to_array($result);
			//echo $_SERVER['REMOTE_ADDR'];
               if(!empty($_COOKIE['debug']))
                {
                    // echo $collection;
                    // echo json_encode($where);
                    // echo '------------------ CASE 1 ---------------------------';
                    // echo "<pre>";
                    // print_r($result);
                }
			if(!empty($result)){

				$result = $result[0];
				$btcusdt_arr = $btcusdt_arr[0];

				//Get latest rate of coin
				$coin_arr = $this->get_latest_market_price($coin_symbol, $exchange);
                if(!empty($_COOKIE['debug']))
                {
                    // echo $collection;
                    // echo json_encode($where);
                    // echo '------------------ CASE 1 ---------------------------';
                    // echo "<pre>";
                    // print_r($result);
                }    
				$coin_price = num((float) $coin_arr['price']);
                
				$usdt_price = num((float) $btcusdt_arr['price']);
				$coin_balance = (!empty($result['coin_balance']) ? num((float) $result['coin_balance']) : 0);
				$total_usd = ($coin_balance * $coin_price) * $usdt_price;
				$total_usd = number_format((float) $total_usd, 5, '.', '');

				$res_arr = array(
					'_id' => $result['_id'],
					'coin_symbol' => $coin_symbol,
					'available' => $result['available'],
					'coin_balance' => $result['coin_balance'],
					'onOrder' => $result['onOrder'],
					'user_id' => $result['user_id'],
					'total_open_orders_balance' => is_nan($result['total_open_orders_balance']) ? 0 : $result['total_open_orders_balance'],
					'total_open_lth_orders_balance' => is_nan($result['total_open_lth_orders_balance']) ? 0 : $result['total_open_lth_orders_balance'],
					'total_usd' => $total_usd,
					'coin_price' => $coin_price,
					'usdt_price' => $usdt_price,
				);
			
				return $res_arr;
			}else{
				return false;
			}
		}

        return false;

	}//end get_coin_balance

	//add_coin //Umer Abbas [5-11-19]
	public function add_coin($user_id, $coins_arr, $exchange){

		//Get latest rate of btciusdt
		$btcusdt_arr = $this->get_latest_market_price('BTCUSDT');

		$collection = null;
		if($exchange == 'binance'){
			$collection = 'coins';
		}else if($exchange == 'bam'){
			$collection = 'coins_bam';
		}else if($exchange == 'coinbasepro'){
			$collection = 'coins_coinbasepro';
		}

		if(empty($collection)){
			return false;
		}else{

			// delete all user coins
			// $db = $this->mongo_db->customQuery();
			// $delete_where['user_id'] = $user_id;
			// $db->coins->deleteMany($delete_where);
		
			$created_date = date('Y-m-d G:i:s');
		
			foreach($coins_arr as $coin) {
			    $coin_arr = $this->get_coin($coin);
		
			    $ins_data = array(
			        'user_id' => $user_id,
			        'symbol' => $coin_arr['symbol'],
			        'coin_name' => $coin_arr['coin_name'],
			        'coin_logo' => $coin_arr['coin_logo'],
			    );
		
				$ins_into_db = $this->mongo_db->insert($collection, $ins_data);
				
			}
			if ($ins_into_db) {
			    return true;
			}
		}

        return false;

	}//end add_coin

	//get_all_user_coins //Umer Abbas [5-11-19]
	public function get_all_user_coins($user_id, $exchange='') {

		$where_arr = [
			'user_id' => $user_id,
			'symbol' => ['$nin' => ['', null, 'BTC', 'NCASHBTC']]
		];

		$coins_collection = '';

		if(!empty($exchange)){
			if($exchange == 'binance'){
				$where_arr['exchange_type'] = $exchange;
				$coins_collection = 'coins';
			}else{
				$coins_collection = 'coins_'.$exchange;
			}
		}

        $this->mongo_db->where($where_arr);
        // $this->mongo_db->sort(array('_id' => -1));
		$get_coins = $this->mongo_db->get($coins_collection);
		
		$coins_arr = iterator_to_array($get_coins);
        return $coins_arr;
    }//end get_all_user_coins
	
	//update_profile
	public function update_profile($user_id, $data) {
        extract($data);

		$update_arr = array();

        if (!empty($first_name)) {
            $update_arr['first_name'] = $first_name;
        }
        if (!empty($last_name)) {
            $update_arr['last_name'] = $last_name;
        }
        if (!empty($phone_number)) {
            $update_arr['phone_number'] = $phone_number;
        }
        if (!empty($timezone)) {
            $update_arr['timezone'] = $timezone;
        }
        if (!empty($profile_image)) {
            $update_arr['profile_image'] = $profile_image;
		}
		
		$update_arr['default_exchange'] = $default_exchange;

        $this->mongo_db->where(array('_id' => $user_id));
        $this->mongo_db->set($update_arr);
        $update = $this->mongo_db->update('users');

		if($update){
			return true;
		}
		return false;
	}//end update_profile

	//get_user_balance //Umer Abbas [7-11-19]
	public function get_user_balance($user_id, $coin=null, $exchange=null) {

		if(!empty($exchange)){
            if($exchange != 'binance'){
                $exchange = "_$exchange";
            }else{
                $exchange = "";
            }
		}
		
		$where_arr = array(
			'user_id' => $user_id,
		);

		if(!empty($coin)){
			$where_arr['coin_symbol'] = $coin;
		}

        $this->mongo_db->where($where_arr);
		$balance = $this->mongo_db->get("user_wallet$exchange");
		$user_balance = iterator_to_array($balance);

		if (!empty($user_balance)) {
			return $user_balance;
		}
        return array();

	}//end get_user_balance

	//get_user_coins //Umer Abbas [7-11-19]
	public function get_user_coins($user_id, $exchange) {

		$where_arr = array(
			'user_id' => $user_id,
		);
		if(!empty($exchange)){
			$where_arr['exchange_type'] = $exchange;
		}

        $this->mongo_db->where($where_arr);
		$get_coins = $this->mongo_db->get("coins");
		$coins_arr = iterator_to_array($get_coins);
		
		if(!empty($coins_arr)){
			return $coins_arr;
		}
		return array();
	}//get_user_coins
	
	//add_user_coins //Umer Abbas [7-11-19]
	public function add_user_coins($user_id, $coins, $exchange) {

		if(!empty($coins)){

			//delete all user coins
			$db = $this->mongo_db->customQuery();
			$delete_where['user_id'] = $user_id;
			$delete_where['exchange_type'] = $exchange;
			$db->coins->deleteMany($delete_where);

			$res_arr = array();
			foreach($coins as $coin){
	
				$global_coin = $this->get_global_coin($coin, $exchange); 
				if(!empty($global_coin)){

					$created_date = date('Y-m-d H:i:s');
					$created_date = $this->mongo_db->converToMongodttime($created_date);
					// $row = $coin;
					// unset($row['_id'], $row['user_id']);
					$row = array(
						'coin_name' => $global_coin['coin_name'],
						'symbol' => $global_coin['symbol'],
						'coin_logo' => $global_coin['coin_logo'],
						'exchange_type' => $global_coin['exchange_type'],
					);
					$row['user_id'] = $user_id;
					$row['created_date'] = $created_date;
					
					$coin = $this->mongo_db->insert("coins", $row);
		
					if($coin){
						unset($row['created_date']);
						$row['operation_status'] = true;
						$res_arr[] = $row;
					}else{
						unset($row['created_date']);
						$row['operation_status'] = false;
						$res_arr[] = $row;
					}
				}else{ 

					$row = array(
						'coin_name' => '',
						'symbol' => $coin,
						'coin_logo' => '',
						'exchange_type' => $exchange,
						'user_id' => $user_id,
						'operation_status' => false,
					);
					$res_arr[] = $row;
				}
			}
			return $res_arr;
		}
		return array();
		
	}//end add_user_coins
	
	//get_global_coin //Umer Abbas [7-11-19]
	public function get_global_coin($coin, $exchange) {

		$where_arr = array(
			'user_id' => 'global',
			'symbol' => $coin,
			'exchange_type' => $exchange,
		);

        $this->mongo_db->where($where_arr);
		$coin = $this->mongo_db->get("coins");

		$coin = iterator_to_array($coin);
		if(!empty($coin)){

			return $coin[0];
		}
		return array();
	}//get_global_coin

	//get_global_coins //Umer Abbas [12-11-19]
	public function get_global_coins($exchange=null) {

		if(empty($exchange)){
			$exchange_arr = array('binance', 'bam', 'coinbasepro');

			$where_arr = array(
				'user_id' => 'global',
				'exchange_type' => array('$in' => $exchange_arr),
			);
		}else{
			$where_arr = array(
				'user_id' => 'global',
				'exchange_type' => $exchange,
			);
		}

		$db = $this->mongo_db->customQuery();
        $coins = $db->coins->find($where_arr);

		$coins = iterator_to_array($coins);
		if(!empty($coins)){
			return $coins;
		}
		return array();
	}//get_global_coins

	//validate_credentials //Umer Abbas [8-11-19] 
    public function validate_credentials($username, $password) {

        $search_Arr['username'] = $username;
        $search_Arr['password'] = md5($password);
        $search_Arr['status'] = (string) 0;
        $search_Arr['user_soft_delete'] = '0';
        // $search_Arr['app_enable'] = 'yes'; //Used for mobile app

        $this->mongo_db->where($search_Arr);
        $get = $this->mongo_db->get('users');
        $row = iterator_to_array($get);

        if (count($row) > 0) {

            $this->update_login_time($row['_id']);

            return $row[0];
		}
		return false;

	} //end validate_credentials

	//update_login_time //Umer Abbas [8-11-19]
	public function update_login_time($id) {
        $login_time = date("Y-m-d G:i:s");
        $upd_arr = array('last_login_datetime' => $this->mongo_db->converToMongodttime($login_time));

        $this->mongo_db->where(array("_id" => $id));
        $this->mongo_db->set($upd_arr);
        $this->mongo_db->update("users");

        return true;
	} //update_login_time

	//reset_password //Umer Abbas [20-11-19]
	public function reset_password($user_id, $password) {
		
		$user_id = $this->mongo_db->mongoId($user_id);
		$upd_arr = array('password' => md5($password));
		
		$this->mongo_db->where(array("_id" => $user_id));
        $this->mongo_db->set($upd_arr);
		$res = $this->mongo_db->update("users");
		if($res){
			return true;
		}else{
			return false;
		}
	} //reset_password
	
	//get_last_price //Umer Abbas [13-11-19] 
	public function get_last_price($symbol, $exchange='') {

		$collection = $exchange == 'binance' ? "market_prices" : "market_prices_".$exchange;

        $this->mongo_db->where(array('coin' => $symbol));
        $this->mongo_db->limit(1);
        $this->mongo_db->sort(array('created_date' => 'desc'));
        $responseArr = $this->mongo_db->get($collection);
		$price = iterator_to_array($responseArr);

		if (!empty($price)) {
			return num($price[0]['price']);
		} else {
			return 0;
		}
	}//end get_last_price

	//get_open_trades //Umer Abbas [9-3-20] 
	public function get_open_trades_old($user_id, $symbol, $exchange='', $application_mode='live') {
		
		$where = [
			'admin_id' => $user_id,
			'application_mode' => $application_mode,
			'symbol' => $symbol,
		];

		//Open Filter
		$where['$or'][] = [
			'status' => [
				'$in' => ['FILLED', 'FILLED_ERROR', 'SELL_ID_ERROR']
			],
        	'is_sell_order' => 'yes',
        	'is_lth_order' => [
				'$ne' => 'yes',
			],
			'cost_avg' => [ 
				'$nin' => ['taking_child', 'yes', 'completed'],
			]
		];
		
		//LTH Filter
		$where['$or'][] = [
			'status' => [
				'$in' => ['LTH', 'LTH_ERROR']
			],
        	'is_sell_order' => 'yes',
			'cost_avg' => [ 
				'$nin' => ['taking_child', 'yes', 'completed'],
			]
		];

		// $where = [
		// 	'admin_id' => $user_id,
		// 	'application_mode' => $application_mode,
		// 	'symbol' => $symbol,
		// 	'status' => ['$in'=> ['FILLED', 'FILLED_ERROR', 'LTH']],
        // 	'is_sell_order' => 'yes',
		// ];

		$collection = ($exchange == 'binance' ? 'buy_orders' : 'buy_orders_'.$exchange);

        $this->mongo_db->where($where);
        $responseArr = $this->mongo_db->get($collection);
		$trades = iterator_to_array($responseArr);
		if (!empty($trades)) {
			return $trades;
		}
		return [];
	}//end get_open_trades

	//get_open_trades_test //Umer Abbas [22-10-20] 
	public function get_open_trades($user_id, $symbol, $exchange='', $application_mode='live', $trigger_type=NULL) {

		if($trigger_type == NULL){
			$where = [
				'admin_id' => $user_id,
				'application_mode' => $application_mode,
				'symbol' => $symbol,
			];
		}else{
			// echo $trigger_type;
			// exit;
			$where = [
				'admin_id' => $user_id,
				'application_mode' => $application_mode,
				'symbol' => $symbol,
				'trigger_type' => $trigger_type,
			];
		}

		//Open Filter
		$where['$or'][] = [
			'status' => ['$in' => ['FILLED', 'FILLED_ERROR', 'SELL_ID_ERROR']],
        	'is_sell_order' => 'yes',
        	'is_lth_order' => ['$ne' => 'yes'],
			'cost_avg' => 'yes',
			'cavg_parent' => 'yes',
			'avg_orders_ids.0' => ['$exists' => false ],
			'move_to_cost_avg'=> ['$ne' => 'yes'],
		];
		$where['$or'][] = [
			'status'=> [ '$in'=> ['FILLED', 'FILLED_ERROR', 'SELL_ID_ERROR']],
			'is_sell_order'=> 'yes',
			'cost_avg'=> [ '$nin'=> ['yes', 'taking_child', 'completed']],
			
		];
		
		//LTH Filter
		$where['$or'][] = [
			'status' => ['$in' => ['LTH', 'LTH_ERROR']],
        	'is_sell_order' => 'yes',
			'cost_avg' => ['$nin' => ['taking_child', 'yes', 'completed']]
		];

		//Submitted Filter
		$where['$or'][] = [
			'status' => ['$in' => ['submitted', 'submitted_for_sell', 'fraction_submitted_sell', 'submitted_ERROR'] ],
			'cost_avg' => [ '$nin' => ['taking_child', 'yes', 'completed'] ],
		];

		$collection = ($exchange == 'binance' ? 'buy_orders' : 'buy_orders_'.$exchange);

        $this->mongo_db->where($where);
        $responseArr = $this->mongo_db->get($collection);
		$trades = iterator_to_array($responseArr);
		if (!empty($trades)) {
			return $trades;
		}
		return [];
	}//end get_open_trades_test
	

	//get_costAvg_trades_test //Umer Abbas [22-10-20] 
	public function get_costAvg_trades($user_id, $symbol, $exchange='', $application_mode='live', $trigger_type=NULL) {
		
		if($trigger_type == NULL){
			$where = [
				'admin_id' => $user_id,
				'application_mode' => $application_mode,
				'symbol' => $symbol,
				'status' => ['$ne'=>'canceled'],
			];	
		}else{
			$where = [
				'admin_id' => $user_id,
				'application_mode' => $application_mode,
				'symbol' => $symbol,
				'status' => ['$ne'=>'canceled'],
				'trigger_type' => $trigger_type,
			];
		}

		//CostAvg Filter
		$where['$or'][] = [
			'cost_avg'=> ['$in'=> ['taking_child', 'yes']],
			'cavg_parent'=> 'yes',
			'show_order'=> 'yes',
			'move_to_cost_avg'=> 'yes',
		];
		
		$where['$or'][] = [
			'cost_avg'=> ['$in'=> ['taking_child', 'yes']],
			'cavg_parent'=> 'yes',
			'show_order'=> 'yes',
			'avg_orders_ids.0'=> ['$exists'=> true]
		];

		$buy_collection = ($exchange == 'binance' ? 'buy_orders' : 'buy_orders_'.$exchange);
		$sold_collection = ($exchange == 'binance' ? 'sold_buy_orders' : 'sold_buy_orders_'.$exchange);

        $this->mongo_db->where($where);
        $responseArr = $this->mongo_db->get($buy_collection);
		$buy_trades = iterator_to_array($responseArr);
		
		$this->mongo_db->where($where);
        $responseArr = $this->mongo_db->get($sold_collection);
		$sold_trades = iterator_to_array($responseArr);

		$trades = array_merge($buy_trades, $sold_trades);
		unset($buy_trades, $sold_trades);

		$trades_count = count($trades);
		$costAvgIds = array_column($trades, '_id');
		$avg_orders_ids = array_column($trades, 'avg_orders_ids');
		foreach($avg_orders_ids as $val){
			foreach($val as $v){
				$costAvgIds[] =	$v;
			}
		}
		// $costAvgIds = array_merge($costAvgIds, ...$avg_orders_ids);

		unset($avg_orders_ids);

		$where = [
			'_id' => ['$in' => $costAvgIds],
			'status' => ['$ne'=>'canceled'],
		];

		$this->mongo_db->where($where);
		$responseArr = $this->mongo_db->get($buy_collection);
		$trades = iterator_to_array($responseArr);

		if (!empty($trades)) {
			return $trades;
		}
		return [];
	}//end get_costAvg_trades_test

	//get_24_hour_price_change //Umer Abbas [13-11-19]
	public function get_24_hour_price_change($symbol, $exchange='') {
		
		$this->mongo_db->where(array('symbol' => $symbol));
        $res = $this->mongo_db->get('coin_price_change');
        $result_arr = iterator_to_array($res);

        return array(
			'change' => num($result_arr[0]['priceChange']),
			'percentage' => number_format($result_arr[0]['priceChangePercent'], 2)
		);
	}//end get_24_hour_price_change
	
	//get_market_trades //Umer Abbas [13-11-19]
	public function get_market_trades($user_id, $symbol, $exchange='') {

		$collection = 'buy_orders';
		if(!empty($exchange)){
			$collection = "$collection_$exchange";
		} 
		
		$db = $this->mongo_db->customQuery();
        $params = array(
            'symbol' => $symbol,
            'status' => 'FILLED',
            'is_sell_order' => 'yes',
            'admin_id' => $user_id,
		);
        $resp = $db->$collection->count($params);
        return $resp;
	}//end get_market_trades
	

	//Google Authentication API //Umer Abbas[10-02-20]
	public function get_secret_code(){ 
        require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
        $ga = new GoogleAuthenticator();
		$secret = $ga->createSecret();
		return $secret;
    }

    public function add_google_auth($admin_id, $is_check, $secret) {
		if ($is_check == 'yes') {
			$upd_arr = array(
				'google_auth' => $is_check,
				'google_auth_code' => $secret,
			);
		} else {
			$upd_arr = array(
				'google_auth' => $is_check,
				'google_auth_code' => NULL,
			);
		}

		$this->mongo_db->where(array('_id' => $admin_id));
		$this->mongo_db->set($upd_arr);
		$upd = $this->mongo_db->update('users');

		if($upd){
			return true;
		}else{
			return false;
		}

	}

	public function get_qr_code() {
        $secret = $this->session->userdata('google_auth_code');
        $email = $this->session->userdata('email_address');
        require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
        $ga = new GoogleAuthenticator();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($email, $secret, 'app.digiebot.com');
		
		return $qrCodeUrl;
    }

    public function verify_google_auth_code() {
        $code = $this->input->post('code');
        $secret = $this->session->userdata('google_auth_code');
        require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
        $ga = new GoogleAuthenticator();
        $checkResult = $ga->verifyCode($secret, $code, 2); // 2 = 2*30sec clock tolerance
        if ($checkResult) {
			return true;
        } else {
			return false;
        }
	}

} //End of Model
?>
