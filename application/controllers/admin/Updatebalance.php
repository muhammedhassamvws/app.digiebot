<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Updatebalance extends CI_Controller {
	function __construct() {
		parent::__construct();
		//load main template
		$this->stencil->layout('admin_layout');

		//load required slices
		$this->stencil->slice('admin_header_script');
		$this->stencil->slice('admin_header');
		$this->stencil->slice('admin_left_sidebar');
		$this->stencil->slice('admin_footer_script');

		// Load Modal
		$this->load->model('admin/mod_login');
		$this->load->model('admin/mod_users');

	}

	public function update_user_vallet_new($userid = '')
	{

		header('Content-type: application/json');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");

		if ($userid != '') {
			$this->mongo_db->where(array('_id' => $userid));
		} else {
			$this->mongo_db->where_in('application_mode', array('both', 'live'));
		}
		$user_arr = $this->mongo_db->get('users');
		$data =  iterator_to_array($user_arr);

		if (!empty($data)) {
			foreach ($data as $row) {
				$userId = (string) $row['_id'];
				sleep(1);
				$balance_arr = $this->binance_api->get_account_balance($userId);


				echo '<pre>';
				print_r($balance_arr);

				if (!empty($balance_arr)) {
					$coin_symbol = 'USDT';
					$account_balance = $balance_arr[$coin_symbol];
					$account_balance = $account_balance['available'];

					echo 'coin_symbol >>> '.$coin_symbol;
					echo "<br>";
					echo 'account_balance >>> '.$account_balance;
					echo "<br>";

					$this->update_balance($userId, $coin_symbol, $account_balance);
				}

				$this->mongo_db->where(array('user_id' => $userId));
				$coindata = $this->mongo_db->get('coins');
				$coinArr = iterator_to_array($coindata);



				if (!in_array('BNBBTC', array_column($coinArr, 'symbol'))) {
					$coinArr[] = array('symbol' => 'BNBBTC');

					echo 'BNBBTC';
					echo "<br>";
					echo $coinArr;
					echo "<br>";
				}

				if (!in_array('BTC', array_column($coinArr, 'symbol'))) {
					$coinArr[] = array('symbol' => 'BTC');
					echo 'BTC';
					echo "<br>";
					echo $coinArr;
					echo "<br>";
				}

				foreach ($coinArr as $row) {
					$coin_symbol = $row['symbol'];
					if ($coin_symbol != '') {
						$currncy = 'BTC';
						if ($coin_symbol != 'BTC') {
							$currncy = str_replace('BTC', '', $coin_symbol);
						}
						$account_balance = $balance_arr[$currncy];
						if ($account_balance['available'] != ''  &&  $account_balance['available'] != 0) {
							$account_balance = $account_balance['available'];
						} else {
							$account_balance =  0;
						}   

						echo 'currncy   ' . $currncy;  
						echo nl2br("\n");
						echo 'coin_symbol   ' . $coin_symbol;
						echo nl2br("\n");
						echo 'account_balance   ' . $account_balance;
						echo nl2br("\n");

						$this->update_balance($userId, $currncy, $account_balance);
					} //End of coin not empty
				} //End of dforeach
				echo 'completed';
			} //End of foreach
		} //End of if not empty		
	}//End of run



	public function update_user_vallet($userid=''){
		// // Allow from any origin
		// if (isset($_SERVER['HTTP_ORIGIN'])) {
		// 	// Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
		// 	// you want to allow, and if so:
		// 	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		// }

		$cronTime = false; 

		header('Content-type: application/json');
		// header("Access-Control-Allow-Origin: http://app2.digiebot.com");
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");

		// if($userid !=''){
		// 	$this->mongo_db->where(array('_id'=>$userid));
		// }else{
		// 	$this->mongo_db->where_in('application_mode', array('both','live'));
		// 	$cronTime = true;
		// }
		// $user_arr= $this->mongo_db->get('users');
		// $data =  iterator_to_array($user_arr);	
		
		$db = $this->mongo_db->customQuery();
        
		$pipeline = [
			[
				'$match' => [
					'application_mode' => ['$in' => ['both', 'live'] ],
					'$and' => [
						[
							'api_key' => ['$exists' => true],
						],
						[
							'api_key' => ['$ne' => null],
						],
						[
							'api_key' => ['$ne' => ''],
						],
						[
							'api_secret' => ['$exists' => true],
						],
						[
							'api_secret' => ['$ne' => null],
						],
						[
                            'api_secret' => ['$ne' => ''],
						],
                    ]
                 ]
            ],
        ];

        if(!empty($userid)){
            $pipeline[0]['$match']['_id'] = $this->mongo_db->mongoId((string) $userid);
        }else{
			$cronTime = true;
		}

        $users = $db->users->aggregate($pipeline);
        $data = iterator_to_array($users);

		//echo "<pre>"; print_r($data); 

		if(!empty($data)){
			foreach ($data as $user) {
				$userId = (string)$user['_id'];
				sleep(1);
				$balance_arr = $this->binance_api->get_account_balance($userId);

		
				echo '<pre>';
				print_r($balance_arr);
				// echo '<pre>';
				// print_r(count($balance_arr));
				// echo "</ br >\n";			

				if(!empty($balance_arr)){
					$coin_symbol = 'USDT';
					$account_balance = $balance_arr[$coin_symbol];
					$account_balance = $account_balance['available'];
					  
					$this->update_balance($userId,$coin_symbol,$account_balance);

					//update ASIM field value
					$db = $this->mongo_db->customQuery();
					$db->user_investment_binance->updateOne(['admin_id' => (string) $userId], ['$set' => ['exchange_enabled' => 'yes' ]]); 

					$set_array = [

						'is_api_key_valid'   	=>  'yes',
						'count_invalid_api' 	=>   0,   
						'account_block'      	=>  'no',
						'balance_modified_time' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
						'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))

					];

					//API key valid fieldes
					$db->users->updateOne(['_id' => $user['_id']], ['$set' => $set_array]);
				
				}else{
					//update ASIM field value
					$db = $this->mongo_db->customQuery();
					$db->user_investment_binance->updateOne(['admin_id' => (string) $userId], ['$set' => ['exchange_enabled' => 'no']]);
					
					//API key valid fieldes

					$set_array = [

						'is_api_key_valid'   	=>  'no',
						'api_line_number'		=>	'Updatebalance=>228',
						'count_invalid_api' 	=>   1,   
						'account_block'      	=>  'no',
						'balance_modified_time' => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
						'api_key_valid_checking'=> $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))

					];

					$db->users->updateOne(['_id' => $user['_id']], ['$set' => $set_array]);
                	$db->buy_orders->updateMany(['admin_id' => (string) $user['_id'], 'parent_status' => 'parent', 'status' => ['$ne'=>'canceled']], ['$set' => ['pick_parent' => 'no']]);
				}
					
				// $this->mongo_db->where(array('user_id'=>$userId));
				$this->mongo_db->where(array('user_id'=>'global', 'exchange_type'=>'binance'));
				$coindata = $this->mongo_db->get('coins');
				$coinArr = iterator_to_array($coindata);


				if(!in_array('BNBBTC', array_column($coinArr, 'symbol'))) {
					$coinArr[] = array('symbol'=>'BNBBTC');
				}

				if(!in_array('BTC', array_column($coinArr, 'symbol'))) {
					$coinArr[] = array('symbol'=>'BTC');
				}
	
				foreach ($coinArr as $row) {

					$coin_symbol = $row['symbol'];
					if($coin_symbol !=''){
						$currncy = 'BTC';
						if($coin_symbol !='BTC'){
							$currncy = str_replace('BTC', '', $coin_symbol);
						}else if($coin_symbol !='USDT'){
							$currncy = str_replace('USDT', '', $coin_symbol);
						}
						
						$arrttt = explode('USDT', $currncy);
						if (isset($arrttt[0]) && $arrttt[0] != '' && isset($arrttt[1]) && $arrttt[1] == '') {
							$currncy = $arrttt[0];
						}

					  $account_balance = $balance_arr[$currncy];
					  if($account_balance['available']!=''  &&  $account_balance['available']!=0){
						    	$account_balance = $account_balance['available'];
						}else{
								$account_balance =  0 ;
						}

						// echo "coin_symbol ".$coin_symbol. ' account_balance ' . $account_balance."\n";

						$this->update_balance($userId, $coin_symbol, $account_balance);
						
						$arr1 = explode('BTC', $coin_symbol);
						$arr2 = explode('USDT', $coin_symbol);
						if (($arr1[0] == '' && $arr1[1] == '') || ($arr2[0] == '' && $arr2[1] == '')) {
							$this->update_balance($userId, $coin_symbol, $account_balance);
						} else if ($arr1[1] == '') {
							$this->update_balance($userId, $arr1[0], $account_balance);

							$arr3 = explode('USDT', $coin_symbol);
							$this->update_balance($userId, $arr3[0], $account_balance);

						} else if ($arr2[1] == '') {

							if($coin_symbol == 'BTCUSDT'){
								$this->update_balance($userId, 'BTCUSDT', $account_balance);
							}else{
								$this->update_balance($userId, $arr2[0], $account_balance);
							}
						}

					}//End of coin not empty
					


				}//End of dforeach
				echo 'completed';
			}//End of foreach
		}//End of if not empty
		
		if($cronTime){
			//Save last Cron Executioon
			$this->last_cron_execution_time('update_user_vallet_binance', '30m', 'Cronjob to update user balance for Binance (*/30 * * * *)');
		}
	}//End of run


	public function update_balance($userId,$coin_symbol,$balance){

		$upd_data = array(
			'coin_symbol' => $coin_symbol,
			'user_id' => $userId,
			'coin_balance' => $balance,
		);
	
		$this->mongo_db->where(array('user_id'=>$userId,'coin_symbol'=>$coin_symbol));
		$data = $this->mongo_db->get('user_wallet');// Admin Id : 5c0912b7fc9aadaac61dd072
		
		$data = iterator_to_array($data);
		if(empty($data)){
			//Inserted--
			$ins = $this->mongo_db->insert('user_wallet', $upd_data);
			// echo '<pre>';
			// print_r($ins);
			// echo $coin_symbol . " ---------- " . $balance . "\n";

		}else{
			//Updated--
			$this->mongo_db->where(array('user_id'=>$userId,'coin_symbol'=>$coin_symbol));
			$this->mongo_db->set($upd_data);
			$ins = $this->mongo_db->update('user_wallet', $upd_data);

			// echo $coin_symbol. " ---------- " .$balance.  "\n"; 

		}
	}//End Of is_coin_exist_for_balance


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

} //En of controller

		